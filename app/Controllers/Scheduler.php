<?php namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\ScheduleModel;

class Scheduler extends BaseController
{
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
        
        return redirect()->to('/admin/dashboard')->with('success', 'Scheduler EDF/EDD berhasil dijalankan. ' . ($priority - 1) . ' pesanan dijadwalkan.');
    }
}