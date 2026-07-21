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
        
        $data = [
            'title' => 'Dashboard',
            'total_orders' => $orderModel->countAllResults(),
            'today_orders' => count($orderModel->getTodayOrders()),
            'total_customers' => $customerModel->countAllResults(),
            'stock_hewan' => $stockModel->where('category', 'hewan')->findAll(),
            'total_stock' => $total_stock,
            'monthly_revenue' => $orderModel->getMonthlyRevenue(),
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
        
        // --- Monthly revenue (last 6 months) ---
        $monthlyRevenue = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthlyLabels[] = date('M', strtotime($month . '-01'));
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
            'monthly_revenue' => $monthlyRevenue,
            'stock_labels' => $stockLabels,
            'stock_data' => $stockData,
            'stock_colors' => $stockColors,
        ]);
    }
}
