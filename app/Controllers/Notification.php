<?php namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\CustomerModel;
use App\Models\OrderDetailModel;
use App\Models\PackageModel;
use App\Models\StockModel;
use App\Models\NotificationModel;
use App\Libraries\TelegramBot;

class Notification extends BaseController
{
    public function send24hReminders()
    {
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $packageModel = new PackageModel();
        $detailModel = new OrderDetailModel();
        $notifModel = new NotificationModel();
        $telegram = new TelegramBot();
        
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $orders = $orderModel->where('slaughter_date', $tomorrow)
            ->where('status !=', 'Completed')
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
                $menuDetail .= "  • {$boneName} + {$meatName} ({$d['box_type']}: {$d['jumlah_box']} box)\n";
            }
            
            // Hitung penyembelihan berdasarkan gender
            $jmlAnakText = $order['jumlah_anak'] . ' ekor';
            
            // Fitur foto
            $fiturText = '';
            if ($order['use_photo_card'] || $order['use_photo_certificate']) {
                $fiturText = "🖼 Fitur: ";
                if ($order['use_photo_card']) $fiturText .= 'Kartu Ucapan ';
                if ($order['use_photo_certificate']) $fiturText .= 'Sertifikat Foto';
                $fiturText .= "\n";
            }
            
            $hargaFormat = number_format($order['total_price'], 0, ',', '.');
            $menuDetailText = $menuDetail ?: "  - Belum diatur\n";
            
            $msg = "🔔 <b>PENGINGAT 24 JAM — PEMOTONGAN AQIQAH</b>\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "📌 <b>Order #{$order['id_order']}</b>\n"
                 . "👤 Pemesan: {$customer['name']}\n"
                 . "📞 No. HP: {$customer['phone']}\n"
                 . "👶 Nama Anak: {$customer['child_name']} ({$customer['gender']})\n"
                 . "📅 Tanggal Lahir: {$customer['birth_date']}\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "🐏 Hewan: {$order['animal_type']} ({$order['animal_gender']}) — {$jmlAnakText}\n"
                 . "📦 Paket: {$package['name']}\n"
                 . "├─ Bobot: {$package['weight_type']} ({$package['min_weight']}-{$package['max_weight']} kg)\n"
                 . "├─ Box: {$package['box_count']} box\n"
                 . "└─ Harga: Rp {$hargaFormat}\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "<b>🍽 Menu Detail:</b>\n"
                 . $menuDetailText
                  . "━━━━━━━━━━━━━━━━━━━━\n"
                  . "📍 Alamat: {$customer['address']}\n"
                  . "🕐 Jam Potong: {$order['slaughter_time']} WIB\n"
                  . "📦 Tanggal Antar: {$order['delivery_date']}\n"
                  . "🎥 Metode: {$order['penyembelihan']}\n"
                  . $fiturText
                  . "━━━━━━━━━━━━━━━━━━━━\n"
                  . "⚡ Pastikan hewan siap dan koordinasi dengan tim dapur.\n";
            
            $telegram->sendMessage($msg);
            
            // Log ke database
            $notifModel->save([
                'order_id' => $order['id_order'],
                'type'     => '24h_reminder',
                'message'  => $msg
            ]);
            
            $sentCount++;
        }
        
        return redirect()->to('/admin/dashboard')->with('success', "Notifikasi 24 jam terkirim untuk {$sentCount} pesanan besok.");
    }
    
    public function sendDailyRecap()
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
        
        // Ambil data stok
        $stocks = $stockModel->findAll();
        $stockInfo = '';
        $stockWarning = false;
        foreach ($stocks as $s) {
            $status = $s['quantity'] <= $s['min_threshold'] ? '⚠️ STOK MENIPIS' : '✅ OK';
            $stockInfo .= "  • {$s['item_name']}: {$s['quantity']} {$s['unit']} {$status}\n";
            if ($s['quantity'] <= $s['min_threshold']) $stockWarning = true;
        }
        
        if (empty($todayOrders)) {
            $msg = "📊 <b>REKAP HARIAN — " . strtoupper(date('d F Y')) . "</b>\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "📦 <b>Total Pesanan:</b> 0\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "Tidak ada jadwal pemotongan untuk hari ini.\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "<b>📦 Stok Tersedia:</b>\n"
                 . $stockInfo
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "✅ Sistem berjalan normal.";
            
            $telegram->sendMessage($msg);
            
            $notifModel->save([
                'type'    => 'daily_recap',
                'message' => $msg
            ]);
            
            return redirect()->to('/admin/dashboard')->with('success', 'Rekap harian (kosong) terkirim ke Telegram.');
        }
        
        // Hitung statistik
        $totalBox = 0;
        $totalPrice = 0;
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
            $totalPrice += $order['total_price'];
            
            if ($order['animal_type'] == 'Domba') $dombaCount++;
            else $kambingCount++;
            
            if ($order['delivery_date'] == $today) $deliveryToday++;
            
            $hargaOrder = number_format($order['total_price'], 0, ',', '.');
            $orderList .= "  #{$order['id_order']} | {$customer['child_name']} | {$order['animal_type']} ({$order['animal_gender']}) | {$package['name']} | Rp {$hargaOrder}\n";
        }
        
        $menuBoneStr = '';
        foreach ($menuBoneList as $name => $count) {
            $menuBoneStr .= "  • {$name}: {$count} order\n";
        }
        $menuMeatStr = '';
        foreach ($menuMeatList as $name => $count) {
            $menuMeatStr .= "  • {$name}: {$count} order\n";
        }
        
        $msg = "📊 <b>REKAP HARIAN — " . strtoupper(date('d F Y')) . "</b>\n"
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "<b>📊 RINGKASAN</b>\n"
             . "📦 Total Pesanan: " . count($todayOrders) . "\n"
             . "📦 Total Box: {$totalBox}\n"
             . "🐏 Domba: {$dombaCount}\n"
             . "🐐 Kambing: {$kambingCount}\n"
             . "🚚 Pengantaran Hari Ini: {$deliveryToday}\n"
             . "💰 Total Pendapatan: Rp " . number_format($totalPrice, 0, ',', '.') . "\n"
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "<b>📋 DAFTAR PESANAN:</b>\n"
             . $orderList
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "<b>🍖 MENU TULANG:</b>\n"
             . ($menuBoneStr ?: "  - Tidak ada\n")
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "<b>🥩 MENU DAGING:</b>\n"
             . ($menuMeatStr ?: "  - Tidak ada\n")
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "<b>📦 STOK TERKINI:</b>\n"
             . $stockInfo
             . ($stockWarning ? "━━━━━━━━━━━━━━━━━━━━\n⚠️ <b>PERHATIAN:</b> Ada stok yang menipis! Segera lakukan pengadaan.\n" : "")
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "⚡ Semoga lancar hari ini! 🙏";
        
        $telegram->sendMessage($msg);
        
        $notifModel->save([
            'type'    => 'daily_recap',
            'message' => $msg
        ]);
        
        return redirect()->to('/admin/dashboard')->with('success', 'Rekap harian lengkap terkirim ke Telegram.');
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
}