<?php namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\CustomerModel;
use App\Models\PackageModel;
use App\Models\OrderDetailModel;
use App\Models\BoneMenuModel;
use App\Models\MeatMenuModel;

class Reports extends BaseController
{
    public function certificate($id_order = null)
    {
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
    
    public function invitation($id_order = null)
    {
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
    
    public function orderReport($date = null)
    {
        if (!$date) $date = date('Y-m-d');
        
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $packageModel = new PackageModel();
        
        $orders = $orderModel->where('slaughter_date', $date)->findAll();
        
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
        
        $monthStart = date('Y-m-01', strtotime($date));
        
        $data = [
            'title'         => 'Laporan Order - ' . $date,
            'orders'        => $enrichedOrders,
            'tanggal_awal'  => $monthStart,
            'tanggal_akhir' => $date,
            'date'          => $date
        ];
        
        $html = view('reports/order_report', $data);
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="laporan_order_' . $date . '.pdf"')
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