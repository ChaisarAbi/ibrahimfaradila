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
    private $monthsIndo = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember',
    ];

    private $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    private function formatTanggalIndo($dateStr)
    {
        $timestamp = strtotime($dateStr);
        $day = date('d', $timestamp);
        $monthEng = date('F', $timestamp);
        $year = date('Y', $timestamp);
        $monthIndo = $this->monthsIndo[$monthEng] ?? $monthEng;
        return $day . ' ' . $monthIndo . ' ' . $year;
    }

    private function formatTanggalShort($dateStr)
    {
        $timestamp = strtotime($dateStr);
        return date('d M Y', $timestamp);
    }

    public function sendTodayRecap()
    {
        $orderModel = new OrderModel();
        $detailModel = new OrderDetailModel();
        $packageModel = new PackageModel();
        $stockModel = new StockModel();
        $notifModel = new NotificationModel();
        $telegram = new TelegramBot();
        $customerModel = new CustomerModel();

        $today = date('Y-m-d');
        $todayOrders = $orderModel->where('slaughter_date', $today)
            ->where('status !=', 'Cancelled')
            ->findAll();

        // Hitung pengantaran hari ini
        $deliveryTodayCount = 0;
        foreach ($todayOrders as $order) {
            if ($order['delivery_date'] && $order['delivery_date'] == $today) {
                $deliveryTodayCount++;
            }
        }

        $todayIndo = $this->formatTanggalIndo($today);
        $dayName = $this->dayNames[date('w')];

        if (empty($todayOrders)) {
            $stocks = $stockModel->findAll();
            $stockCriticalLines = [];
            $stockOkCount = 0;
            foreach ($stocks as $s) {
                $minThreshold = $s['min_threshold'] ?? 0;
                if ($s['quantity'] <= $minThreshold) {
                    $stockCriticalLines[] = '⚠️ ' . $s['item_name'] . ': *' . $s['quantity'] . ' ' . $s['unit'] . '* (Menipis)';
                } else {
                    $stockOkCount++;
                }
            }

            $msg = "📊 *REKAP HARIAN AQIQAH*\n"
                 . '🗓 *' . $dayName . ', ' . $todayIndo . "*\n"
                 . "────────────────────\n\n"
                 . "Tidak ada jadwal pemotongan untuk hari ini.\n\n"
                 . "📦 *STATUS STOK*\n";
            if (!empty($stockCriticalLines)) {
                $msg .= implode("\n", $stockCriticalLines) . "\n";
            }
            if ($stockOkCount > 0) {
                $msg .= "✅ *{$stockOkCount} item* stok dalam kondisi aman.\n";
            }
            $msg .= "\n────────────────────\n"
                  . "Sistem berjalan normal.";

            $telegram->sendMessage($msg);

            $notifModel->save([
                'type'    => 'daily_recap',
                'message' => $msg
            ]);

            return redirect()->to('/admin/dashboard')->with('success', 'Rekap hari ini (kosong) terkirim ke Telegram.');
        }

        // --- Hitung ringkasan ---
        $totalBox = 0;
        $dombaCount = 0;
        $kambingCount = 0;
        $dapurBone = [];
        $dapurMeat = [];

        $orderList = '';

        foreach ($todayOrders as $order) {
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
                $boneName = $d['bone_name'] ?: '-';
                $meatName = $d['meat_name'] ?: '-';
                if ($boneName != '-') {
                    if (!isset($dapurBone[$boneName])) $dapurBone[$boneName] = 0;
                    $dapurBone[$boneName] += (int)$d['jumlah_box'];
                }
                if ($meatName != '-') {
                    if (!isset($dapurMeat[$meatName])) $dapurMeat[$meatName] = 0;
                    $dapurMeat[$meatName] += (int)$d['jumlah_box'];
                }
            }

            $totalBox += $orderBoxCount;
            if ($order['animal_type'] == 'Domba') $dombaCount++;
            else $kambingCount++;

            $slaughterTime = substr($order['slaughter_time'], 0, 5) ?: '-';
            $animalEmoji = $order['animal_type'] == 'Domba' ? '🐑' : '🐐';

            $orderList .= '🔹 `#' . $order['id_order'] . '` *' . ($customer['child_name'] ?: $customer['name']) . "*\n";
            $orderList .= '   └ ' . $animalEmoji . ' ' . $order['animal_type'] . ' | ' . ($package['name'] ?? '-') . ' | *' . $orderBoxCount . ' Box* | 🕒 ' . $slaughterTime . " WIB\n";
        }

        // Rekap Dapur
        $dapurLines = [];
        if (!empty($dapurBone)) {
            $boneParts = [];
            foreach ($dapurBone as $name => $count) {
                $boneParts[] = $name . ' (' . $count . ')';
            }
            $dapurLines[] = '• *Menu Tulang:* ' . implode(', ', $boneParts);
        }
        if (!empty($dapurMeat)) {
            $meatParts = [];
            foreach ($dapurMeat as $name => $count) {
                $meatParts[] = $name . ' (' . $count . ')';
            }
            $dapurLines[] = '• *Menu Daging:* ' . implode(', ', $meatParts);
        }

        // Stok
        $stocks = $stockModel->findAll();
        $stockCriticalLines = [];
        $stockOkCount = 0;
        foreach ($stocks as $s) {
            $minThreshold = $s['min_threshold'] ?? 0;
            if ($s['quantity'] <= $minThreshold) {
                $stockCriticalLines[] = '⚠️ ' . $s['item_name'] . ': *' . $s['quantity'] . ' ' . $s['unit'] . '* (Menipis)';
            } else {
                $stockOkCount++;
            }
        }

        // Hewan summary
        $hewanParts = [];
        if ($dombaCount > 0) $hewanParts[] = '🐑 ' . $dombaCount . ' Domba';
        if ($kambingCount > 0) $hewanParts[] = '🐐 ' . $kambingCount . ' Kambing';

        $msg = "📊 *REKAP HARIAN AQIQAH*\n"
             . '🗓 *' . $dayName . ', ' . $todayIndo . "*\n"
             . "────────────────────\n\n"
             . "📈 *RINGKASAN OPERASIONAL*\n"
             . '• Total Pesanan : *' . count($todayOrders) . " Order*\n"
             . '• Total Olahan  : *' . $totalBox . " Box*\n"
             . '• Rincian Hewan : ' . implode(' | ', $hewanParts) . "\n"
             . '• Pengantaran   : ' . ($deliveryTodayCount > 0 ? '🚚 *' . $deliveryTodayCount . ' Hari ini*' : '🚚 -') . "\n\n"
             . "📦 *DAFTAR PESANAN*\n"
             . $orderList . "\n";

        if (!empty($dapurLines)) {
            $msg .= "🍲 *REKAP DAPUR*\n"
                  . implode("\n", $dapurLines) . "\n\n";
        }

        $msg .= "📦 *STATUS STOK*";
        if (!empty($stockCriticalLines)) {
            $msg .= "\n" . implode("\n", $stockCriticalLines);
        }
        if ($stockOkCount > 0) {
            $msg .= "\n✅ Stok aman: *{$stockOkCount} item* lainnya tersembunyi";
        }

        $msg .= "\n\n────────────────────\n"
              . "✨ *Semoga operasional hari ini berjalan lancar!* 🙏";

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
        $tomorrowIndo = $this->formatTanggalIndo($tomorrow);
        $dayName = $this->dayNames[date('w', strtotime('+1 day'))];

        $orders = $orderModel->where('slaughter_date', $tomorrow)
            ->where('status !=', 'Cancelled')
            ->findAll();

        if (empty($orders)) {
            $msg = "📅 *PREVIEW JADWAL BESOK*\n"
                 . '🗓 *' . $dayName . ', ' . $tomorrowIndo . "*\n\n"
                 . "Tidak ada jadwal pemotongan untuk besok.\n"
                 . "────────────────────\n"
                 . "Sistem berjalan normal.";

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
        $orderDetails = '';

        foreach ($orders as $order) {
            $customer = $customerModel->find($order['customer_id']);
            $package = $packageModel->find($order['package_id']);

            $details = $detailModel
                ->select('order_details.*, bone_menus.name as bone_name, meat_menus.name as meat_name')
                ->join('bone_menus', 'bone_menus.id_bone = order_details.bone_menu_id', 'left')
                ->join('meat_menus', 'meat_menus.id_meat = order_details.meat_menu_id', 'left')
                ->where('order_id', $order['id_order'])
                ->findAll();

            $orderBoxCount = 0;
            $menuLines = [];
            foreach ($details as $d) {
                $orderBoxCount += (int)$d['jumlah_box'];
                $boneName = $d['bone_name'] ?: '-';
                $meatName = $d['meat_name'] ?: '-';
                $menuLines[] = $d['jumlah_box'] . ' Box: ' . $boneName . ' + ' . $meatName . ' (' . $d['box_type'] . ')';
            }

            $totalBox += $orderBoxCount;
            if ($order['animal_type'] == 'Domba') $dombaCount++;
            else $kambingCount++;

            $slaughterTime = substr($order['slaughter_time'], 0, 5) ?: '-';
            $deliveryDate = $order['delivery_date'] ? $this->formatTanggalShort($order['delivery_date']) : '-';
            $birthDate = $customer['birth_date'] ? $this->formatTanggalShort($customer['birth_date']) : '-';
            $jmlAnakText = $order['jumlah_anak'] . ' ekor';
            $animalEmoji = $order['animal_type'] == 'Domba' ? '🐑' : '🐐';

            $fiturList = [];
            if ($order['use_photo_card']) $fiturList[] = 'Kartu Ucapan';
            if ($order['use_photo_certificate']) $fiturList[] = 'Sertifikat';
            if (!empty($order['photo_path'])) $fiturList[] = 'Foto';
            $fiturText = !empty($fiturList) ? implode(', ', $fiturList) : '-';

            $orderDetails .= "────────────────────\n";
            $orderDetails .= '📋 *DETAIL PESANAN #' . $order['id_order'] . "*\n\n";
            $orderDetails .= '👤 *Pemesan:* ' . $customer['name'] . ' (`' . $customer['phone'] . "`)\n";
            $orderDetails .= '👶 *Anak:* ' . ($customer['child_name'] ?: '-') . ' (' . $customer['gender'] . ') | 🗓 ' . $birthDate . "\n";
            $orderDetails .= $animalEmoji . ' *Hewan:* ' . $order['animal_type'] . ' ' . $order['animal_gender'] . ' (' . $jmlAnakText . ")\n";
            $orderDetails .= '📦 *Paket:* ' . $package['name'] . ' (Bobot ' . $package['weight_type'] . ': ' . $package['min_weight'] . '–' . $package['max_weight'] . ' kg) | *Total ' . $orderBoxCount . " Box*\n\n";

            $orderDetails .= "🍱 *Menu Box:*\n";
            foreach ($menuLines as $ml) {
                $orderDetails .= ' ▫️ ' . $ml . "\n";
            }

            $orderDetails .= "\n⚙️ *Detail Operasional:*\n";
            $orderDetails .= ' 🕒 Jam Potong : *' . $slaughterTime . " WIB*\n";
            $orderDetails .= ' 🚚 Tgl Antar  : *' . $deliveryDate . "*\n";
            $orderDetails .= ' 🎥 Potong     : ' . ($order['penyembelihan'] ?: '-') . "\n";
            $orderDetails .= ' 📍 Alamat     : ' . ($customer['address'] ?: '-') . "\n";
            $orderDetails .= ' 🎁 Fitur       : ' . $fiturText . "\n\n";
        }

        $hewanParts = [];
        if ($dombaCount > 0) $hewanParts[] = '🐑 ' . $dombaCount . ' Domba';
        if ($kambingCount > 0) $hewanParts[] = '🐐 ' . $kambingCount . ' Kambing';

        $msg = "📅 *PREVIEW JADWAL BESOK*\n"
             . '🗓 *' . $dayName . ', ' . $tomorrowIndo . "*\n"
             . "────────────────────\n\n"
             . "📊 *SUMMARY BESOK*\n"
             . '• Total Order : *' . count($orders) . " Pesanan*\n"
             . '• Total Box   : *' . $totalBox . " Box*\n"
             . '• Hewan       : ' . implode(' | ', $hewanParts) . "\n\n"
             . $orderDetails
             . "────────────────────\n\n"
             . "💡 *Catatan:* Pastikan hewan siap dan koordinasi dengan tim dapur.";

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