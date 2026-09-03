<?php namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\CustomerModel;
use App\Models\PackageModel;
use App\Models\StockModel;
use App\Models\ScheduleModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $stockModel = new StockModel();
        
        // Get total stock count
        $stocks = $stockModel->findAll();
        $total_stock = 0;
        foreach ($stocks as $s) {
            $total_stock += $s['quantity'];
        }
        
        // Get recent orders with join to customers and packages
        // NOTE: Auto status updates are handled by Scheduler only, NOT here.
        // Dashboard should display current state without modifying it.
        $db = \Config\Database::connect();
        
        $recent_orders = $db->table('orders')
            ->select('orders.*, customers.name as customer_name, packages.name as package_name')
            ->join('customers', 'customers.id_customer = orders.customer_id')
            ->join('packages', 'packages.id_package = orders.package_id')
            ->orderBy('orders.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();
        
        // Orders for next 24 hours (tomorrow's slaughter)
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $upcoming_slaughter = $db->table('orders')
            ->select('orders.*, customers.name as customer_name, customers.phone as customer_phone, customers.child_name, packages.name as package_name')
            ->join('customers', 'customers.id_customer = orders.customer_id')
            ->join('packages', 'packages.id_package = orders.package_id')
            ->where('orders.slaughter_date', $tomorrow)
            ->whereIn('orders.status', ['Pending', 'Scheduled'])
            ->orderBy('orders.slaughter_time', 'ASC')
            ->get()
            ->getResultArray();
        
        $monthlyRevenue = $orderModel->getMonthlyRevenue();
        $monthlyRevenue = $monthlyRevenue ? (float)$monthlyRevenue : 0;
        
        $data = [
            'title' => 'Dashboard',
            'total_orders' => $orderModel->countAllResults(),
            'today_orders' => count($orderModel->getTodayOrders()),
            'total_customers' => $customerModel->countAllResults(),
            'stock_hewan' => $stockModel->where('category', 'hewan')->findAll(),
            'total_stock' => $total_stock,
            'monthly_revenue' => $monthlyRevenue,
            'recent_orders' => $recent_orders,
            'upcoming_slaughter' => $upcoming_slaughter,
            'upcoming_date' => $tomorrow,
        ];
        
        return $this->render('dashboard/index', $data);
    }
    
    public function chartData()
    {
        $orderModel = new OrderModel();
        $stockModel = new StockModel();
        $db = \Config\Database::connect();
        
        // --- Weekly orders (last 7 days) ---
        $weeklyData = [];
        $weeklyLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $weeklyLabels[] = date('D', strtotime($date));
            $count = $db->table('orders')
                ->where('DATE(created_at)', $date)
                ->countAllResults();
            $weeklyData[] = (int)$count;
        }
        
        // --- Monthly revenue (last 12 months) ---
        $monthlyRevenue = [];
        // --- Monthly orders count (last 12 months) ---
        $monthlyOrders = [];
        $monthlyLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthlyLabels[] = date('M', strtotime($month . '-01'));
            $count = $db->table('orders')
                ->like('created_at', $month, 'after')
                ->countAllResults();
            $monthlyOrders[] = (int)$count;
            $revenue = $db->table('orders')
                ->select('COALESCE(SUM(total_price), 0) as total')
                ->like('created_at', $month, 'after')
                ->get()
                ->getRow()
                ->total;
            $monthlyRevenue[] = (int)$revenue;
        }
        
        // --- Stock values ---
        $stocks = $stockModel->findAll();
        $stockLabels = [];
        $stockData = [];
        $stockColors = [];
        foreach ($stocks as $s) {
            $stockLabels[] = $s['item_name'];
            $stockData[] = (int)$s['quantity'];
            $minThreshold = $s['min_threshold'] ?? 0;
            $stockColors[] = $s['quantity'] <= $minThreshold ? '#ef4444' : '#22c55e';
        }
        
        return $this->response->setJSON([
            'weekly_labels' => $weeklyLabels,
            'weekly_data' => $weeklyData,
            'monthly_labels' => $monthlyLabels,
            'monthly_orders' => $monthlyOrders,
            'monthly_revenue' => $monthlyRevenue,
            'stock_labels' => $stockLabels,
            'stock_data' => $stockData,
            'stock_colors' => $stockColors,
        ]);
    }
    
    /**
     * AJAX endpoint for calendar heatmap - orders per day
     */
    public function getCalendarOrders()
    {
        $db = \Config\Database::connect();
        $month = $this->request->getGet('month') ?? date('Y-m');
        
        // Get all orders for the month with joins
        $orders = $db->table('orders')
            ->select('orders.id_order, orders.status, orders.slaughter_date, orders.delivery_date, orders.total_price, orders.animal_type, customers.name as customer_name, customers.child_name, packages.name as package_name')
            ->join('customers', 'customers.id_customer = orders.customer_id', 'left')
            ->join('packages', 'packages.id_package = orders.package_id', 'left')
            ->like('orders.slaughter_date', $month, 'after')
            ->orderBy('orders.slaughter_date', 'ASC')
            ->get()
            ->getResultArray();
        
        // Group by date
        $calendarData = [];
        foreach ($orders as $order) {
            $date = $order['slaughter_date'];
            if (!isset($calendarData[$date])) {
                $calendarData[$date] = [
                    'count' => 0,
                    'boxes' => 0,
                    'revenue' => 0,
                    'orders' => []
                ];
            }
            $calendarData[$date]['count']++;
            $calendarData[$date]['revenue'] += (int)$order['total_price'];
            $calendarData[$date]['orders'][] = [
                'id_order' => $order['id_order'],
                'customer_name' => $order['customer_name'] ?? 'N/A',
                'child_name' => $order['child_name'] ?? '-',
                'package_name' => $order['package_name'] ?? 'N/A',
                'animal_type' => $order['animal_type'],
                'total_price' => $order['total_price'],
                'status' => $order['status'],
                'delivery_date' => $order['delivery_date']
            ];
        }
        
        return $this->response->setJSON([
            'calendar_data' => $calendarData,
            'orders' => $orders
        ]);
    }
    
    /**
     * AJAX endpoint to update order status
     * Allows changing status freely from the calendar modal
     */
    public function markCompleted()
    {
        $db = \Config\Database::connect();
        
        // Get raw request data to support both JSON and form-encoded
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $orderId = $input['id'] ?? null;
        $newStatus = $input['status'] ?? null;
        
        if (!$orderId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }
        
        // Get the order first
        $order = $db->table('orders')->where('id_order', $orderId)->get()->getRowArray();
        
        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }
        
        // If no status provided, default to Completed (backwards compatible)
        if (empty($newStatus)) {
            $newStatus = 'Completed';
        }
        
        // Validate status value
        $validStatuses = ['Pending', 'Scheduled', 'Processing', 'Completed', 'Cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Invalid status: ' . $newStatus
            ]);
        }
        
        // If switching to Completed or Scheduled, check delivery_datetime condition
        if (in_array($newStatus, ['Completed', 'Scheduled']) && $order['delivery_date']) {
            $now = new \DateTime();
            $deliveryDatetime = new \DateTime($order['delivery_date'] . ' ' . ($order['delivery_time'] ?? '00:00:00'));
            if ($newStatus === 'Completed' && $now < $deliveryDatetime) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Belum bisa mark Completed - Pengantaran belum lewat (Delivery: ' . $order['delivery_date'] . ' ' . ($order['delivery_time'] ?? '-') . ', Current: ' . $now->format('Y-m-d H:i') . ')'
                ]);
            }
        }
        
        // Perform the update
        $db->table('orders')
            ->where('id_order', $orderId)
            ->set(['status' => $newStatus])
            ->update();
        
        // Verify the update was successful
        $updated = $db->table('orders')
            ->where('id_order', $orderId)
            ->get()
            ->getRowArray();
        
        if ($updated && $updated['status'] === $newStatus) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Order #' . $orderId . ' status changed to ' . $newStatus,
                'new_status' => $newStatus
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update status. Database verification failed.'
            ]);
        }
    }
    
    /**
     * AJAX endpoint for bulk mark completed
     */
    public function bulkMarkCompleted()
    {
        $db = \Config\Database::connect();
        $orderIds = $this->request->getJSON()->ids ?? [];
        $updated = 0;
        
        if (empty($orderIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No orders selected', 'updated' => 0]);
        }
        
        foreach ($orderIds as $orderId) {
            $order = $db->table('orders')->where('id_order', $orderId)->get()->getRowArray();
            
            if ($order && $order['status'] !== 'Completed' && $order['status'] !== 'Cancelled') {
                $now = new \DateTime();
                $deliveryDatetime = new \DateTime($order['delivery_date'] . ' ' . ($order['delivery_time'] ?? '00:00:00'));
                if (!$order['delivery_date'] || $now >= $deliveryDatetime) {
                    $db->table('orders')
                        ->where('id_order', $orderId)
                        ->set(['status' => 'Completed'])
                        ->update();
                    $updated++;
                }
            }
        }
        
        return $this->response->setJSON([
            'success' => true, 
            'message' => "$updated order(s) marked as Completed",
            'updated' => $updated
        ]);
    }
}
