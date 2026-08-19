<?php namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\ScheduleModel;
use App\Models\CustomerModel;

class Scheduler extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        
        // Get stats
        $pending = $orderModel->where('status', 'Pending')->countAllResults();
        $scheduled = $orderModel->where('status', 'Scheduled')->countAllResults();
        $completed = $orderModel->where('status', 'Completed')->countAllResults();
        $today = $orderModel->where('slaughter_date', date('Y-m-d'))->countAllResults();
        
        // Get scheduled orders with customer info
        $db = \Config\Database::connect();
        $scheduledOrders = $db->table('orders')
            ->select('orders.*, customers.name as customer_name, customers.child_name, packages.name as package_name')
            ->join('customers', 'customers.id_customer = orders.customer_id')
            ->join('packages', 'packages.id_package = orders.package_id')
            ->where('orders.status', 'Scheduled')
            ->orderBy('orders.slaughter_date', 'ASC')
            ->get()->getResultArray();
        
        // Get pending orders
        $pendingOrders = $db->table('orders')
            ->select('orders.*, customers.name as customer_name, customers.child_name, packages.name as package_name')
            ->join('customers', 'customers.id_customer = orders.customer_id')
            ->join('packages', 'packages.id_package = orders.package_id')
            ->where('orders.status', 'Pending')
            ->orderBy('orders.slaughter_date', 'ASC')
            ->get()->getResultArray();
        
        $data = [
            'title'           => 'Jadwalkan (EDF)',
            'stats'           => [
                'pending'     => $pending,
                'scheduled'   => $scheduled,
                'completed'   => $completed,
                'today'       => $today
            ],
            'scheduled_orders' => $scheduledOrders,
            'pending_orders'   => $pendingOrders
        ];
        
        return $this->render('scheduler/index', $data);
    }
    
    public function run()
    {
        $orderModel = new OrderModel();
        $scheduleModel = new ScheduleModel();
        
        // Ambil semua pesanan dengan status Pending
        $orders = $orderModel->where('status', 'Pending')->findAll();
        
        // EDF Algorithm: Urutkan berdasarkan slaughter_date terdekat
        usort($orders, function($a, $b) {
            return strtotime($a['slaughter_date']) - strtotime($b['slaughter_date']);
        });
        
        // Hapus jadwal lama
        $scheduleModel->truncate();
        
        $priority = 1;
        foreach ($orders as $order) {
            $scheduleModel->insert([
                'order_id'       => $order['id_order'],
                'slaughter_date' => $order['slaughter_date'],
                'priority'       => $priority++
            ]);
            $orderModel->update($order['id_order'], ['status' => 'Scheduled']);
        }
        
        return redirect()->to('/admin/scheduler')->with('success', 'Scheduler EDF/EDD berhasil dijalankan. ' . ($priority - 1) . ' pesanan dijadwalkan.');
    }
}