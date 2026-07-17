<?php namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderDetailModel;
use App\Models\StockModel;
use App\Models\BoneMenuModel;
use App\Models\MeatMenuModel;

class Kitchen extends BaseController
{
    public function index($date = null)
    {
        if (!$date) $date = date('Y-m-d');
        
        $orderModel = new OrderModel();
        $detailModel = new OrderDetailModel();
        $stockModel = new StockModel();
        
        $orders = $orderModel->getOrdersWithCustomer();
        
        // Filter orders by slaughter_date
        $todayOrders = array_filter($orders, function($o) use ($date) {
            return $o['slaughter_date'] == $date;
        });
        
        // Calculate total boxes and ensure array indexing
        $total_boxes = 0;
        $ordersWithMenu = [];
        foreach ($todayOrders as $order) {
            $details = $detailModel->select('order_details.*, bone_menus.name as bone_name, meat_menus.name as meat_name')
                ->join('bone_menus', 'bone_menus.id_bone = order_details.bone_menu_id', 'left')
                ->join('meat_menus', 'meat_menus.id_meat = order_details.meat_menu_id', 'left')
                ->where('order_id', $order['id_order'])
                ->findAll();
            
            $firstDetail = !empty($details) ? $details[0] : [];
            $order['bone_menu'] = $firstDetail['bone_name'] ?? '-';
            $order['meat_menu'] = $firstDetail['meat_name'] ?? '-';
            $order['box_type'] = $firstDetail['box_type'] ?? '-';
            $order['jumlah_box'] = $firstDetail['jumlah_box'] ?? 0;
            $total_boxes += (int)($firstDetail['jumlah_box'] ?? 0);
            $ordersWithMenu[] = $order;
        }
        
        // Count unique bone and meat menus for today
        $boneMenus = [];
        $meatMenus = [];
        foreach ($ordersWithMenu as $o) {
            if (!empty($o['bone_menu']) && $o['bone_menu'] != '-') $boneMenus[$o['bone_menu']] = true;
            if (!empty($o['meat_menu']) && $o['meat_menu'] != '-') $meatMenus[$o['meat_menu']] = true;
        }
        
        $data = [
            'title'          => 'Dapur - ' . $date,
            'orders'         => $ordersWithMenu,
            'stocks'         => $stockModel->findAll(),
            'total_today'    => count($todayOrders),
            'total_boxes'    => $total_boxes,
            'totalBoneMenus' => count($boneMenus),
            'totalMeatMenus' => count($meatMenus),
            'current_date'   => $date
        ];
        
        return $this->render('kitchen/index', $data);
    }
    
    public function updateStatus($id = null)
    {
        $orderModel = new OrderModel();
        $status = $this->request->getVar('status');
        $orderModel->update($id, ['status' => $status]);
        
        return redirect()->to('/admin/kitchen')->with('success', 'Status pesanan #' . $id . ' diubah ke ' . $status);
    }
    
    public function updateStock()
    {
        $stockModel = new StockModel();
        $id_stock = $this->request->getVar('id_stock');
        $quantity = $this->request->getVar('quantity');
        
        $stockModel->update($id_stock, ['quantity' => $quantity]);
        
        return redirect()->to('/admin/kitchen')->with('success', 'Stok berhasil diupdate.');
    }
}