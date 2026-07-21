<?php namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\CustomerModel;
use App\Models\PackageModel;
use App\Models\OrderDetailModel;
use App\Models\BoneMenuModel;
use App\Models\MeatMenuModel;

class Reports extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $totalOrders = $orderModel->countAllResults();
        $todayOrders = $orderModel->where('slaughter_date', date('Y-m-d'))->countAllResults();
        $completedOrders = $orderModel->where('status', 'Completed')->countAllResults();
        
        $data = [
            'title'           => 'Laporan',
            'total_orders'    => $totalOrders,
            'today_orders'    => $todayOrders,
            'completed_orders' => $completedOrders
        ];
        
        return view('reports/index', $data);
    }

    public function certificate($id_order = null)
    {
        if (!$id_order) {
            $id_order = $this->request->getGet('id_order');
        }
        if (!$id_order) {
            return redirect()->to('/admin/reports')->with('error', 'ID Order tidak valid');
        }
        
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $packageModel = new PackageModel();
        
        $order = $orderModel->find($id_order);
        $customer = $customerModel->find($order['customer_id']);
        $package = $packageModel->find($order['package_id']);
        
        // Add Indonesian day name
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $dayIndex = date('w', strtotime($order['slaughter_date']));
        $order['hari'] = $dayNames[$dayIndex];
        $order['tanggal'] = date('d F Y', strtotime($order['slaughter_date']));
        
        $data = [
            'order'     => $order,
            'customer'  => $customer,
            'package'   => $package,
            'details'   => (new \App\Models\OrderDetailModel())->where('order_id', $id_order)->findAll()
        ];
        
        $html = view('reports/certificate', $data);
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="sertifikat_aqiqah_' . $id_order . '.pdf"')
            ->setBody($pdfOutput);
    }
    
    public function detailPemesanan($id_order = null)
    {
        // Support both segment (/detail-pemesanan/2) and query string (?id_order=2)
        if (!$id_order) {
            $id_order = $this->request->getGet('id_order');
        }
        if (!$id_order) {
            return redirect()->to('/admin/reports')->with('error', 'ID Order tidak valid');
        }
        
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $packageModel = new PackageModel();
        $detailModel = new OrderDetailModel();
        
        $order = $orderModel->find($id_order);
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Order tidak ditemukan');
        }
        
        $customer = $customerModel->find($order['customer_id']);
        $package = $packageModel->find($order['package_id']);
        $details = $detailModel
            ->select('order_details.*, bone_menus.name as bone_name, meat_menus.name as meat_name')
            ->join('bone_menus', 'bone_menus.id_bone = order_details.bone_menu_id', 'left')
            ->join('meat_menus', 'meat_menus.id_meat = order_details.meat_menu_id', 'left')
            ->where('order_id', $id_order)
            ->findAll();
        
        $data = [
            'order'    => $order,
            'customer' => $customer,
            'package'  => $package,
            'details'  => $details
        ];
        
        $html = view('reports/detail_pemesanan', $data);
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="detail_pemesanan_' . $id_order . '.pdf"')
            ->setBody($pdfOutput);
    }

    public function invitation($id_order = null)
    {
        if (!$id_order) {
            $id_order = $this->request->getGet('id_order');
        }
        if (!$id_order) {
            return redirect()->to('/admin/reports')->with('error', 'ID Order tidak valid');
        }
        
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $packageModel = new PackageModel();
        
        $order = $orderModel->find($id_order);
        $customer = $customerModel->find($order['customer_id']);
        $package = $packageModel->find($order['package_id']);
        
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $dayIndex = date('w', strtotime($order['slaughter_date']));
        $order['hari'] = $dayNames[$dayIndex];
        $order['tanggal'] = date('d F Y', strtotime($order['slaughter_date']));
        
        $data = [
            'order'    => $order,
            'customer' => $customer,
            'package'  => $package
        ];
        
        $html = view('reports/invitation', $data);
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="undangan_aqiqah_' . $id_order . '.pdf"')
            ->setBody($pdfOutput);
    }
    
    public function orderReport($startDate = null, $endDate = null)
    {
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $packageModel = new PackageModel();
        
        // Support query string from form: ?start_date=...&end_date=...
        if (!$startDate) {
            $startDate = $this->request->getGet('start_date');
        }
        if (!$endDate) {
            $endDate = $this->request->getGet('end_date');
        }
        
        // Support both single date and range: if only 1 param or param contains '~', treat differently
        if (!$startDate) $startDate = date('Y-m-d');
        
        // Cek apakah parameter berupa "startDate/endDate" dari route
        if ($endDate === null && strpos($startDate, '~') !== false) {
            list($startDate, $endDate) = explode('~', $startDate, 2);
        }
        
        // Jika endDate tidak diisi, gunakan startDate (single day)
        if (!$endDate) $endDate = $startDate;
        
        // Query range
        $orders = $orderModel
            ->where('slaughter_date >=', $startDate)
            ->where('slaughter_date <=', $endDate)
            ->findAll();
        
        // Enrich orders with customer and package data
        $enrichedOrders = [];
        foreach ($orders as $o) {
            $customer = $customerModel->find($o['customer_id']);
            $package = $packageModel->find($o['package_id']);
            $o['customer_name'] = $customer ? $customer['name'] : '-';
            $o['child_name'] = $customer ? $customer['child_name'] : '-';
            $o['package_name'] = $package ? $package['name'] : '-';
            $enrichedOrders[] = $o;
        }
        
        $data = [
            'title'         => 'Laporan Order - ' . $startDate . ' s/d ' . $endDate,
            'orders'        => $enrichedOrders,
            'tanggal_awal'  => $startDate,
            'tanggal_akhir' => $endDate,
            'start_date'    => $startDate,
            'end_date'      => $endDate
        ];
        
        $html = view('reports/order_report', $data);
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="laporan_order_' . $startDate . '_sampai_' . $endDate . '.pdf"')
            ->setBody($pdfOutput);
    }
    
    public function orderReportPdf($id_order = null)
    {
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $packageModel = new PackageModel();
        $detailModel = new OrderDetailModel();
        $boneMenuModel = new BoneMenuModel();
        $meatMenuModel = new MeatMenuModel();
        
        $order = $orderModel->find($id_order);
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Order tidak ditemukan');
        }
        
        $customer = $customerModel->find($order['customer_id']);
        $package = $packageModel->find($order['package_id']);
        $details = $detailModel
            ->select('order_details.*, bone_menus.name as bone_name, meat_menus.name as meat_name')
            ->join('bone_menus', 'bone_menus.id_bone = order_details.bone_menu_id', 'left')
            ->join('meat_menus', 'meat_menus.id_meat = order_details.meat_menu_id', 'left')
            ->where('order_id', $id_order)
            ->findAll();
        
        $data = [
            'order'    => $order,
            'customer' => $customer,
            'package'  => $package,
            'details'  => $details
        ];
        
        $html = view('reports/order_report', $data);
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="pesanan_' . $id_order . '.pdf"')
            ->setBody($pdfOutput);
    }
    
    public function kitchenSheet($date = null)
    {
        if (!$date) $date = date('Y-m-d');
        
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $detailModel = new OrderDetailModel();
        $stockModel = new \App\Models\StockModel();
        
        $orders = $orderModel->where('slaughter_date', $date)->findAll();
        
        // Enrich orders with customer and detail data
        $enrichedOrders = [];
        foreach ($orders as $order) {
            $customer = $customerModel->find($order['customer_id']);
            $details = $detailModel->where('order_id', $order['id_order'])->findAll();
            
            $order['customer_name'] = $customer ? $customer['name'] : '-';
            $order['child_name'] = $customer ? $customer['child_name'] : '-';
            $order['bone_menu'] = !empty($details) ? 'Gulai' : 'Gulai';
            $order['meat_menu'] = !empty($details) ? 'Sate' : 'Sate';
            $order['box_type'] = !empty($details) ? $details[0]['box_type'] : 'Box Premium';
            $order['jumlah_box'] = 0;
            foreach ($details as $d) {
                $order['jumlah_box'] += $d['jumlah_box'];
            }
            
            $enrichedOrders[] = $order;
        }
        
        // Get stock data
        $stockKambing = $stockModel->where('item_name', 'Kambing')->first();
        $stockDomba = $stockModel->where('item_name', 'Domba')->first();
        
        $data = [
            'title'          => 'Lembar Kerja Dapur - ' . $date,
            'orders'         => $enrichedOrders,
            'tanggal'        => $date,
            'details'        => [],
            'stock_kambing'  => $stockKambing ? $stockKambing['quantity'] : 0,
            'stock_domba'    => $stockDomba ? $stockDomba['quantity'] : 0,
            'date'           => $date
        ];
        
        $html = view('reports/kitchen_sheet', $data);
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="lembar_kerja_dapur_' . $date . '.pdf"')
            ->setBody($pdfOutput);
    }
}