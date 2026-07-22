<?php namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\CustomerModel;
use App\Models\OrderDetailModel;
use App\Models\PackageModel;
use App\Models\StockModel;
use App\Models\NotificationModel;
use App\Models\TelegramRecipientModel;
use App\Libraries\TelegramBot;

class Notification extends BaseController
{
    public function sendTodayRecap()
    {
        $orderModel = new OrderModel();
        $detailModel = new OrderDetailModel();
        $packageModel = new PackageModel();
        $stockModel = new StockModel();
        $notifModel = new NotificationModel();
        $telegram = new TelegramBot();
        
        $today = date('Y-m-d');
        $todayOrders = $orderModel->where('slaughter_date', $today)
            ->where('status !=', 'Cancelled')
            ->findAll();
        
        $stocks = $stockModel->findAll();
        $stockInfo = '';
        $stockWarning = false;
        foreach ($stocks as $s) {
            $minThreshold = $s['min_threshold'] ?? 0;
            $status = $s['quantity'] <= $minThreshold ? 'STOK MENIPIS' : 'OK';
            $stockInfo .= "  - {$s['item_name']}: {$s['quantity']} {$s['unit']} {$status}\n";
            if ($s['quantity'] <= $minThreshold) $stockWarning = true;
        }
        
        $todayStr = strtoupper(date('d F Y'));
        
        if (empty($todayOrders)) {
            $msg = "REKAP HARIAN - {$todayStr}\n";
            $msg .= "==================================\n";
            $msg .= "Total Pesanan: 0\n";
            $msg .= "==================================\n";
            $msg .= "Tidak ada jadwal pemotongan untuk hari ini.\n";
            $msg .= "==================================\n";
            $msg .= "Stok Tersedia:\n";
            $msg .= $stockInfo;
            $msg .= "==================================\n";
            $msg .= "Sistem berjalan normal.";
            
            $telegram->sendMessage($msg);
            
            $notifModel->save([
                'type'    => 'daily_recap',
                'message' => $msg
            ]);
            
            return redirect()->to('/admin/dashboard')->with('success', 'Rekap hari ini (kosong) terkirim ke Telegram.');
        }
        
        $totalBox = 0;
        $dombaCount = 0;
        $kambingCount = 0;
        $menuBoneList = [];
        $menuMeatList = [];
        $deliveryToday = 0;
        $orderList = "";
        
        foreach ($todayOrders as $order) {
            $customerModel = new CustomerModel();
            $customer = $customerModel->find($order['customer_id']);
            $package = $packageModel->find($order['package_id']);
            
            $details = $detailModel
                ->select('order_details.*, bone_menus.name as bone_name, meat_menus.name as meat_name')
                ->join('bone_menus', 'bone_menus.id_bone = order_details.bone_menu_id', 'left')
                ->join('meat_menus', 'meat_menus.id_meat = order_details.meat_menu_id', 'left')
                ->where('order_id', $order['id_order'])
                ->findAll();
            
            $orderBoxCount = 0;
            foreach ($details as $d) {
                $orderBoxCount += (int)$d['jumlah_box'];
                if ($d['bone_name']) $menuBoneList[$d['bone_name']] = ($menuBoneList[$d['bone_name']] ?? 0) + 1;
                if ($d['meat_name']) $menuMeatList[$d['meat_name']] = ($menuMeatList[$d['meat_name']] ?? 0) + 1;
            }
            
            $totalBox += $orderBoxCount;
            
            if ($order['animal_type'] == 'Domba') $dombaCount++;
            else $kambingCount++;
            
            if ($order['delivery_date'] == $today) $deliveryToday++;
            
            $slaughterTime = substr($order['slaughter_time'], 0, 5);
            $orderList .= "  #{$order['id_order']} | {$customer['child_name']} | {$order['animal_type']} | {$package['name']} | {$orderBoxCount} box | {$slaughterTime} WIB\n";
        }
        
        $menuBoneStr = '';
        foreach ($menuBoneList as $name => $count) {
            $menuBoneStr .= "  - {$name}: {$count} order\n";
        }
        $menuMeatStr = '';
        foreach ($menuMeatList as $name => $count) {
            $menuMeatStr .= "  - {$name}: {$count} order\n";
        }
        
        $msg = "REKAP HARIAN - {$todayStr}\n";
        $msg .= "==================================\n";
        $msg .= "RINGKASAN\n";
        $msg .= "Total Pesanan: " . count($todayOrders) . "\n";
        $msg .= "Total Box: {$totalBox}\n";
        $msg .= "Domba: {$dombaCount}\n";
        $msg .= "Kambing: {$kambingCount}\n";
        $msg .= "Pengantaran Hari Ini: {$deliveryToday}\n";
        $msg .= "==================================\n";
        $msg .= "DAFTAR PESANAN:\n";
        $msg .= $orderList;
        $msg .= "==================================\n";
        $msg .= "MENU TULANG:\n";
        $msg .= ($menuBoneStr ?: "  - Tidak ada\n");
        $msg .= "==================================\n";
        $msg .= "MENU DAGING:\n";
        $msg .= ($menuMeatStr ?: "  - Tidak ada\n");
        $msg .= "==================================\n";
        $msg .= "STOK TERKINI:\n";
        $msg .= $stockInfo;
        if ($stockWarning) {
            $msg .= "==================================\n";
            $msg .= "PERHATIAN: Ada stok yang menipis! Segera lakukan pengadaan.\n";
        }
        $msg .= "==================================\n";
        $msg .= "Semoga lancar hari ini!";
        
        $telegram->sendMessage($msg);
        
        $notifModel->save([
            'type'    => 'daily_recap',
            'message' => $msg
        ]);
        
        return redirect()->to('/admin/dashboard')->with('success', 'Rekap hari ini terkirim ke Telegram.');
    }
    
    public function sendTomorrowPreview()
    {
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $packageModel = new PackageModel();
        $detailModel = new OrderDetailModel();
        $notifModel = new NotificationModel();
        $telegram = new TelegramBot();
        
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $orders = $orderModel->where('slaughter_date', $tomorrow)
            ->where('status !=', 'Cancelled')
            ->findAll();
        
        $tomorrowStr = strtoupper(date('d F Y', strtotime('+1 day')));
        
        if (empty($orders)) {
            $msg = "PREVIEW BESOK - {$tomorrowStr}\n";
            $msg .= "==================================\n";
            $msg .= "Tidak ada jadwal pemotongan untuk besok.\n";
            $msg .= "==================================\n";
            $msg .= "Sistem berjalan normal.";
            
            $telegram->sendMessage($msg);
            
            $notifModel->save([
                'type'    => 'tomorrow_preview',
                'message' => $msg
            ]);
            
            return redirect()->to('/admin/dashboard')->with('success', 'Preview besok (kosong) terkirim ke Telegram.');
        }
        
        $totalBox = 0;
        $dombaCount = 0;
        $kambingCount = 0;
        $orderList = "";
        
        foreach ($orders as $order) {
            $customer = $customerModel->find($order['customer_id']);
            $package = $packageModel->find($order['package_id']);
            
            $details = $detailModel
                ->select('order_details.*, bone_menus.name as bone_name, meat_menus.name as meat_name')
                ->join('bone_menus', 'bone_menus.id_bone = order_details.bone_menu_id', 'left')
                ->join('meat_menus', 'meat_menus.id_meat = order_details.meat_menu_id', 'left')
                ->where('order_id', $order['id_order'])
                ->findAll();
            
            $menuDetail = '';
            $orderBoxCount = 0;
            foreach ($details as $d) {
                $orderBoxCount += (int)$d['jumlah_box'];
                $boneName = $d['bone_name'] ?: '-';
                $meatName = $d['meat_name'] ?: '-';
                $menuDetail .= "    - {$boneName} + {$meatName} ({$d['box_type']}: {$d['jumlah_box']} box)\n";
            }
            
            $totalBox += $orderBoxCount;
            
            if ($order['animal_type'] == 'Domba') $dombaCount++;
            else $kambingCount++;
            
            $jmlAnakText = $order['jumlah_anak'] . ' ekor';
            $slaughterTime = substr($order['slaughter_time'], 0, 5);
            
            $fiturText = '';
            if ($order['use_photo_card'] || $order['use_photo_certificate']) {
                $fiturText = "    Fitur: ";
                if ($order['use_photo_card']) $fiturText .= 'Kartu Ucapan ';
                if ($order['use_photo_certificate']) $fiturText .= 'Sertifikat Foto';
            }
            
            $orderList .= "==================================\n";
            $orderList .= "  Order #{$order['id_order']}\n";
            $orderList .= "  Pemesan: {$customer['name']}\n";
            $orderList .= "  No. HP: {$customer['phone']}\n";
            $orderList .= "  Nama Anak: {$customer['child_name']} ({$customer['gender']})\n";
            $orderList .= "  Tanggal Lahir: {$customer['birth_date']}\n";
            $orderList .= "  Hewan: {$order['animal_type']} ({$order['animal_gender']}) - {$jmlAnakText}\n";
            $orderList .= "  Paket: {$package['name']}\n";
            $orderList .= "  Bobot: {$package['weight_type']} ({$package['min_weight']}-{$package['max_weight']} kg)\n";
            $orderList .= "  Box: {$orderBoxCount} box\n";
            $orderList .= "  Menu:\n";
            $orderList .= ($menuDetail ?: "    - Belum diatur\n");
            $orderList .= "  Alamat: {$customer['address']}\n";
            $orderList .= "  Jam Potong: {$slaughterTime} WIB\n";
            $orderList .= "  Tanggal Antar: {$order['delivery_date']}\n";
            $orderList .= "  Metode: {$order['penyembelihan']}\n";
            if ($fiturText) $orderList .= $fiturText . "\n";
        }
        
        $msg = "PREVIEW BESOK - {$tomorrowStr}\n";
        $msg .= "==================================\n";
        $msg .= "RINGKASAN\n";
        $msg .= "Total Pesanan: " . count($orders) . "\n";
        $msg .= "Total Box: {$totalBox}\n";
        $msg .= "Domba: {$dombaCount}\n";
        $msg .= "Kambing: {$kambingCount}\n";
        $msg .= "==================================\n";
        $msg .= "DETAIL PESANAN:\n";
        $msg .= $orderList;
        $msg .= "==================================\n";
        $msg .= "Pastikan hewan siap dan koordinasi dengan tim dapur.";
        
        $telegram->sendMessage($msg);
        
        $notifModel->save([
            'type'    => 'tomorrow_preview',
            'message' => $msg
        ]);
        
        return redirect()->to('/admin/dashboard')->with('success', 'Preview besok terkirim ke Telegram.');
    }

    
    public function sendHPlus1()
    {
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $packageModel = new PackageModel();
        $detailModel = new OrderDetailModel();
        $notifModel = new NotificationModel();
        $telegram = new TelegramBot();

        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $today = date('Y-m-d');
        
        $orders = $orderModel
            ->where('slaughter_date', $yesterday)
            ->where('status !=', 'Cancelled')
            ->findAll();

        $sentCount = 0;
        foreach ($orders as $order) {
            $customer = $customerModel->find($order['customer_id']);
            $package = $packageModel->find($order['package_id']);
            $details = $detailModel
                ->select('order_details.*, bone_menus.name as bone_name, meat_menus.name as meat_name')
                ->join('bone_menus', 'bone_menus.id_bone = order_details.bone_menu_id', 'left')
                ->join('meat_menus', 'meat_menus.id_meat = order_details.meat_menu_id', 'left')
                ->where('order_id', $order['id_order'])
                ->findAll();

            $totalBox = 0;
            $menuDetail = '';
            foreach ($details as $d) {
                $totalBox += (int)$d['jumlah_box'];
                $boneName = $d['bone_name'] ?: '-';
                $meatName = $d['meat_name'] ?: '-';
                $menuDetail .= "  - {$boneName} + {$meatName} ({$d['box_type']}: {$d['jumlah_box']} box)\n";
            }

            $infofAntar = '';
            if ($order['delivery_date'] === $today) {
                $infofAntar = "PENGANTARAN HARI INI!\nBarang akan diantar pada hari ini.\n";
            } else {
                $infofAntar = "Tanggal Antar: {$order['delivery_date']}\n";
            }

            $currentStatus = $order['status'];
            $statusEmoji = '';
            if ($currentStatus === 'Completed') $statusEmoji = 'Selesai';
            else if ($currentStatus === 'Processing') $statusEmoji = 'Diproses';
            else if ($currentStatus === 'Scheduled') $statusEmoji = 'Terjadwal';
            else $statusEmoji = $currentStatus;

            $msg = "FOLLOW-UP H+1 - PEMOTONGAN {$yesterday}\n";
            $msg .= "==================================\n";
            $msg .= "Order #{$order['id_order']}\n";
            $msg .= "Pemesan: {$customer['name']}\n";
            $msg .= "Nama Anak: {$customer['child_name']}\n";
            $msg .= "==================================\n";
            $msg .= "Hewan: {$order['animal_type']} ({$order['animal_gender']}) - {$order['jumlah_anak']} ekor\n";
            $msg .= "Paket: {$package['name']}\n";
            $msg .= "Total Box: {$totalBox}\n";
            $msg .= "==================================\n";
            $msg .= "Menu:\n";
            $msg .= ($menuDetail ?: "  - Tidak ada\n");
            $msg .= "==================================\n";
            $msg .= "Status Terkini: {$statusEmoji}\n";
            $msg .= $infofAntar;
            $msg .= "==================================\n";
            $msg .= "Mohon konfirmasi jika ada kendala atau perubahan.\n";

            $telegram->sendMessage($msg);

            $notifModel->save([
                'order_id' => $order['id_order'],
                'type'     => 'hplus1_followup',
                'message'  => $msg
            ]);

            $sentCount++;
        }

        return redirect()->to('/admin/notifications/manual')->with('success', "Follow-up H+1 terkirim untuk {$sentCount} pesanan.");
    }

    public function sendCustom()
    {
        $telegram = new TelegramBot();
        $notifModel = new NotificationModel();
        
        $message = $this->request->getPost('message');
        if (empty($message)) {
            return redirect()->back()->with('error', 'Pesan tidak boleh kosong.');
        }
        
        $now = date('d M Y H:i');
        $msg = "PENGUMUMAN\n";
        $msg .= "==================================\n";
        $msg .= $message . "\n";
        $msg .= "==================================\n";
        $msg .= "{$now} WIB";
        
        $telegram->sendMessage($msg);
        
        $notifModel->save([
            'type'    => 'custom',
            'message' => $msg
        ]);
        
        return redirect()->to('/admin/notifications/manual')->with('success', 'Notifikasi umum berhasil dikirim!');
    }

    public function manual()
    {
        $data = [
            'title' => 'Kirim Notifikasi Manual'
        ];
        return $this->render('notifications/manual', $data);
    }

    public function sendStockAlert()
    {
        $stockModel = new StockModel();
        $notifModel = new NotificationModel();
        $telegram = new TelegramBot();
        
        $stocks = $stockModel->findAll();
        $lowStockItems = [];
        $stockInfo = '';
        
        foreach ($stocks as $s) {
            $minThreshold = $s['min_threshold'] ?? 0;
            $status = $s['quantity'] <= $minThreshold ? 'STOK MENIPIS' : 'OK';
            $stockInfo .= "  - {$s['item_name']}: {$s['quantity']} {$s['unit']} {$status}\n";
            if ($s['quantity'] <= $minThreshold) {
                $lowStockItems[] = $s;
            }
        }
        
        $msg = "ALERT STOK TERKINI\n";
        $msg .= "==================================\n";
        $msg .= "Status Stok:\n";
        $msg .= $stockInfo;
        
        if (!empty($lowStockItems)) {
            $msg .= "==================================\n";
            $msg .= "ITEM STOK MENIPIS:\n";
            foreach ($lowStockItems as $item) {
                $msg .= "  - {$item['item_name']}: {$item['quantity']} {$item['unit']} (Min: {$item['min_threshold']})\n";
            }
            $msg .= "==================================\n";
            $msg .= "Segera lakukan pengadaan!\n";
        } else {
            $msg .= "==================================\n";
            $msg .= "Semua stok dalam batas aman.\n";
        }
        
        $telegram->sendMessage($msg);
        
        $notifModel->save([
            'type'    => 'stock_alert',
            'message' => $msg
        ]);
        
        return redirect()->to('/admin/notifications/history')->with('success', 'Notifikasi stok terkirim ke Telegram.');
    }

    public function test()
    {
        $telegram = new TelegramBot();
        $now = date('d M Y H:i');
        $msg = "UJI COBA NOTIFIKASI\n";
        $msg .= "==================================\n";
        $msg .= "Pesan ini adalah uji coba.\n";
        $msg .= "Jika Anda menerima ini, notifikasi berfungsi\n";
        $msg .= "==================================\n";
        $msg .= "{$now} WIB";
        
        $telegram->sendMessage($msg);
        
        return redirect()->to('/admin/notifications/history')->with('success', 'Notifikasi uji coba terkirim!');
    }
    
    public function history()
    {
        $notifModel = new NotificationModel();
        $data = [
            'title' => 'Riwayat Notifikasi',
            'notifications' => $notifModel->orderBy('sent_at', 'DESC')->findAll(50)
        ];
        return $this->render('notifications/history', $data);
    }

    public function recipients()
    {
        $recipientModel = new TelegramRecipientModel();
        $data = [
            'title' => 'Kelola Penerima Notifikasi',
            'recipients' => $recipientModel->findAll()
        ];
        return $this->render('notifications/recipients', $data);
    }

    public function addRecipient()
    {
        $recipientModel = new TelegramRecipientModel();

        $chatId = $this->request->getPost('chat_id');
        $name = $this->request->getPost('name');
        $type = $this->request->getPost('type');

        if (empty($chatId)) {
            return redirect()->back()->with('error', 'Chat ID tidak boleh kosong.');
        }

        $recipientModel->insert([
            'chat_id' => trim($chatId),
            'name'    => trim($name),
            'type'    => $type,
        ]);

        return redirect()->to('/admin/notifications/recipients')->with('success', 'Penerima notifikasi berhasil ditambahkan.');
    }

    public function deleteRecipient($id)
    {
        $recipientModel = new TelegramRecipientModel();
        $recipientModel->delete($id);
        return redirect()->to('/admin/notifications/recipients')->with('success', 'Penerima notifikasi berhasil dihapus.');
    }

}