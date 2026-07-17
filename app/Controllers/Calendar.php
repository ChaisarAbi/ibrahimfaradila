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
                // Warna berdasarkan priority
                if ($schedule['priority'] == 1) {
                    $color = '#dc3545'; // Merah
                } elseif ($schedule['priority'] == 2) {
                    $color = '#ffc107'; // Kuning
                } else {
                    $color = '#28a745'; // Hijau
                }
                
                $events[] = [
                    'id'    => $schedule['id_schedule'],
                    'title' => $schedule['title'] . ' (P' . $schedule['priority'] . ')',
                    'start' => $schedule['start'],
                    'color' => $color,
                    'textColor' => ($schedule['priority'] == 2) ? '#000' : '#fff',
                    'url'   => base_url('/admin/orders/edit/' . $schedule['order_id'])
                ];
            }
        } else {
            // Fallback: Load from orders directly
            $orderModel = new OrderModel();
            $customerModel = new CustomerModel();
            $orders = $orderModel->where('status !=', 'Cancelled')->findAll();
            
            foreach ($orders as $order) {
                $customer = $customerModel->find($order['customer_id']);
                $customerName = $customer ? $customer['name'] : 'Unknown';
                
                $events[] = [
                    'id'    => 'order_' . $order['id_order'],
                    'title' => $customerName . ' (' . $order['animal_type'] . ')',
                    'start' => $order['slaughter_date'],
                    'color' => '#17a2b8',
                    'textColor' => '#fff',
                    'url'   => base_url('/admin/orders/edit/' . $order['id_order'])
                ];
            }
        }
        
        return $this->response->setJSON($events);
    }
}
