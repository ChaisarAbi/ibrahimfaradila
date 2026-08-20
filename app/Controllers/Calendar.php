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
}
