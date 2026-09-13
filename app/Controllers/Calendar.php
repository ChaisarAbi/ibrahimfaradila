<?php namespace App\Controllers;

use App\Models\ScheduleModel;
use App\Models\OrderModel;
use App\Models\CustomerModel;

class Calendar extends BaseController
{
    public function getEvents()
    {
        $scheduleModel = new ScheduleModel();
        $schedules = $scheduleModel->getCalendarEvents();
        
        $events = [];
        
        if (!empty($schedules)) {
            foreach ($schedules as $schedule) {
                // Warna berdasarkan status + prioritas
                // Priority 1 = paling penting (merah tua), Priority 2 = sedang (oranye), Priority 3+ = normal (hijau)
                $baseColorsByStatus = [
                    'Scheduled'   => '#2196F3', // Biru untuk scheduled
                    'Processing'  => '#FF9800', // Orange untuk processing
                    'Completed'   => '#4CAF50', // Hijau untuk completed
                ];
                
                $status = $schedule['status'] ?? 'Scheduled';
                $baseColor = $baseColorsByStatus[$status] ?? '#2196F3';
                
                // Modifikasi warna berdasarkan prioritas
                if (isset($schedule['priority'])) {
                    if ($schedule['priority'] == 1) {
                        // Priority 1: Merah terang untuk deadline terdekat
                        $color = '#E53935'; // Red - high priority
                        $textColor = '#FFFFFF';
                    } elseif ($schedule['priority'] == 2) {
                        // Priority 2: Orange untuk prioritas sedang
                        $color = '#FF9800'; // Orange - medium priority
                        $textColor = '#FFFFFF';
                    } else {
                        // Priority 3+: Hijau sesuai status
                        $color = $baseColor;
                        $textColor = '#FFFFFF';
                    }
                } else {
                    $color = $baseColor;
                    $textColor = '#FFFFFF';
                }
                
                // Tampilkan info prioritas di title
                $priorityLabel = isset($schedule['priority']) ? ' P' . $schedule['priority'] : '';
                
                $events[] = [
                    'id'      => $schedule['id_schedule'],
                    'title'   => $schedule['title'] . $priorityLabel,
                    'start'   => $schedule['start'],
                    'color'   => $color,
                    'textColor' => $textColor,
                    'url'     => base_url('/admin/orders/edit/' . $schedule['order_id']),
                    'extendedProps' => [
                        'status'   => $status,
                        'priority' => $schedule['priority'] ?? '-',
                        'order_id' => $schedule['order_id']
                    ]
                ];
            }
        } else {
            // Fallback: Load from orders langsung
            $orderModel = new OrderModel();
            $customerModel = new CustomerModel();
            $orders = $orderModel->where('status !=', 'Cancelled')->findAll();
            
            foreach ($orders as $order) {
                $customer = $customerModel->find($order['customer_id']);
                $customerName = $customer ? $customer['name'] : 'Unknown';
                
                // Warna berdasarkan STATUS (tanpa prioritas karena ini fallback)
                switch ($order['status']) {
                    case 'Pending':
                        $color = '#FF9800'; // Orange - pending
                        $textColor = '#FFFFFF';
                        break;
                    case 'Scheduled':
                        $color = '#2196F3'; // Biru - scheduled
                        $textColor = '#FFFFFF';
                        break;
                    case 'Processing':
                        $color = '#FF9800'; // Orange - processing
                        $textColor = '#FFFFFF';
                        break;
                    case 'Completed':
                        $color = '#4CAF50'; // Hijau - completed
                        $textColor = '#FFFFFF';
                        break;
                    default:
                        $color = '#9E9E9E';
                        $textColor = '#FFFFFF';
                }
                
                $events[] = [
                    'id'      => 'order_' . $order['id_order'],
                    'title'   => $customerName . ' (' . $order['animal_type'] . ')',
                    'start'   => $order['slaughter_date'],
                    'color'   => $color,
                    'textColor' => $textColor,
                    'url'     => base_url('/admin/orders/edit/' . $order['id_order']),
                    'extendedProps' => [
                        'status' => $order['status']
                    ]
                ];
            }
        }
        
        return $this->response->setJSON($events);
    }

    /**
     * Calendar page (Admin & RPH) - same heatmap as dashboard with status management.
     */
    public function index()
    {
        $data = ['title' => 'Kalender Pesanan'];
        return $this->render('calendar/index', $data);
    }

    /**
     * AJAX endpoint for calendar heatmap - orders per day (Admin & RPH).
     * Same as Dashboard::getCalendarOrders
     */
    public function calendarOrders()
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
     * AJAX endpoint to update order status (Admin & RPH).
     * Allows changing status freely from the calendar modal.
     * Same as Dashboard::markCompleted
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
     * AJAX endpoint for bulk mark completed (Admin & RPH).
     * Same as Dashboard::bulkMarkCompleted
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
