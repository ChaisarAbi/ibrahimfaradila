<?php namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\OrderModel;
use App\Models\CustomerModel;
use App\Models\PackageModel;
use App\Models\OrderDetailModel;
use App\Models\StockModel;
use App\Models\NotificationModel;
use App\Libraries\TelegramBot;

class NotificationsSend extends BaseCommand
{
    protected $group       = 'Notifications';
    protected $name        = 'notifications:send';
    protected $description = 'Send 24h reminders and daily recap via Telegram';

    private $monthsIndo = [
        'January'   => 'Januari',
        'February'  => 'Februari',
        'March'     => 'Maret',
        'April'     => 'April',
        'May'       => 'Mei',
        'June'      => 'Juni',
        'July'      => 'Juli',
        'August'    => 'Agustus',
        'September' => 'September',
        'October'   => 'Oktober',
        'November'  => 'November',
        'December'  => 'Desember',
    ];

    public function run(array $params)
    {
        // Paksa timezone WIB
        date_default_timezone_set('Asia/Jakarta');

        // Determine mode from first parameter
        $mode = $params[0] ?? 'all';
        
        CLI::write('====================================', 'yellow');
        CLI::write('  SISTEM NOTIFIKASI TELEGRAM', 'yellow');
        CLI::write('====================================', 'yellow');
        
        $telegram = new TelegramBot();
        
        if ($mode === 'all') {
            CLI::write('🔌 Menghubungkan ke Telegram...', 'cyan');
            $testMsg = "🤖 <b>Sistem Aqiqah Online</b>\nBot Telegram berhasil terhubung!\nWaktu: " . $this->now('d/m/Y H:i');
            $testResult = $telegram->sendMessage($testMsg);
            
            if ($testResult === false) {
                CLI::error('❌ Gagal terhubung ke Telegram. Cek token di .env');
                CLI::write('Pastikan TELEGRAM_BOT_TOKEN dan TELEGRAM_CHAT_ID terisi di file .env', 'red');
                return;
            }
            
            $response = json_decode($testResult, true);
            if (!$response || !($response['ok'] ?? false)) {
                CLI::error('❌ Gagal kirim test message: ' . ($response['description'] ?? 'Unknown error'));
                CLI::write('Response: ' . $testResult, 'red');
                return;
            }
            
            CLI::write('✅ Bot Telegram terhubung!', 'green');
        }
        
        CLI::write('');
        
        if ($mode === 'all' || $mode === 'reminders') {
            CLI::write('⏰ [' . $this->now('H:i') . '] Mengirim pengingat 24 jam...', 'yellow');
            $this->sendReminders();
        }
        
        if ($mode === 'all' || $mode === 'recap') {
            CLI::write('📊 [' . $this->now('H:i') . '] Mengirim rekap harian...', 'yellow');
            $this->sendRecap();
        }
        
        CLI::write('');
        CLI::write('====================================', 'green');
        CLI::write('  ✅ NOTIFIKASI SELESAI!', 'green');
        CLI::write('====================================', 'green');
    }

    private function now($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

    private function formatTanggalIndo($dateStr)
    {
        $timestamp = strtotime($dateStr);
        $day  = date('d', $timestamp);
        $monthEng = date('F', $timestamp);
        $year = date('Y', $timestamp);
        $monthIndo = $this->monthsIndo[$monthEng] ?? $monthEng;
        return "{$day} {$monthIndo} {$year}";
    }

    private function getTotalBoxFromDetails($orderId)
    {
        $detailModel = new OrderDetailModel();
        $details = $detailModel->where('order_id', $orderId)->findAll();
        $total = 0;
        foreach ($details as $d) {
            $total += (int)$d['jumlah_box'];
        }
        return $total;
    }

    /**
     * Kirim reminder pesanan dengan DUA mekanisme:
     * 1. H-1 (backup): order dengan slaughter_date == tomorrow → selalu kirim
     * 2. Real-time ≤24 jam: order yang slaughter_timestamp <= now+24h
     * 
     * Untuk menghindari duplikasi, satu order hanya kirim 1 pesan
     * dengan label yang sesuai (prioritas real-time jika memenuhi keduanya).
     */
    private function sendReminders()
    {
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $packageModel = new PackageModel();
        $detailModel = new OrderDetailModel();
        $notifModel = new NotificationModel();
        $telegram = new TelegramBot();

        $now = $this->now('Y-m-d H:i:s');
        $nowTimestamp = strtotime($now);
        $plus24hTimestamp = $nowTimestamp + 86400; // +24 jam
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        // Ambil semua order non-completed, slaughter_date >= hari ini
        $orders = $orderModel
            ->where('slaughter_date >=', date('Y-m-d'))
            ->where('status !=', 'Completed')
            ->findAll();

        $sentCount = 0;
        foreach ($orders as $order) {
            // Gabungkan slaughter_date + slaughter_time
            $slaughterDatetime = $order['slaughter_date'] . ' ' . ($order['slaughter_time'] ?? '00:00:00');
            $slaughterTimestamp = strtotime($slaughterDatetime);

            // Cek apakah H-1 (backup)
            $isH1Backup = ($order['slaughter_date'] === $tomorrow);

            // Cek apakah real-time ≤24 jam (dan belum lewat)
            $isRealtime = ($slaughterTimestamp > $nowTimestamp && $slaughterTimestamp <= $plus24hTimestamp);

            // Lewati jika tidak memenuhi salah satu kondisi
            if (!$isH1Backup && !$isRealtime) {
                continue;
            }

            // Tentukan label
            $label = '';
            if ($isH1Backup && $isRealtime) {
                // Hitung sisa jam untuk real-time
                $selisihJam = round(($slaughterTimestamp - $nowTimestamp) / 3600, 1);
                $jamLabel = $selisihJam < 1 
                    ? 'Kurang dari 1 jam lagi'
                    : ($selisihJam == 1 ? '1 jam lagi' : "{$selisihJam} jam lagi");
                $label = "H-1 · {$jamLabel}";
            } elseif ($isH1Backup) {
                $label = 'H-1: Besok';
            } else {
                $selisihJam = round(($slaughterTimestamp - $nowTimestamp) / 3600, 1);
                $label = $selisihJam < 1 
                    ? 'Kurang dari 1 jam lagi'
                    : ($selisihJam == 1 ? '1 jam lagi' : "{$selisihJam} jam lagi");
            }

            $customer = $customerModel->find($order['customer_id']);
            $package = $packageModel->find($order['package_id']);
            $details = $detailModel
                ->select('order_details.*, bone_menus.name as bone_name, meat_menus.name as meat_name')
                ->join('bone_menus', 'bone_menus.id_bone = order_details.bone_menu_id', 'left')
                ->join('meat_menus', 'meat_menus.id_meat = order_details.meat_menu_id', 'left')
                ->where('order_id', $order['id_order'])
                ->findAll();
            
            $menuDetail = '';
            $totalBox = 0;
            foreach ($details as $d) {
                $boneName = $d['bone_name'] ? $d['bone_name'] : '-';
                $meatName = $d['meat_name'] ? $d['meat_name'] : '-';
                $menuDetail .= "  • {$boneName} + {$meatName} ({$d['box_type']}: {$d['jumlah_box']} box)\n";
                $totalBox += (int)$d['jumlah_box'];
            }
            
            $fiturText = '';
            if ($order['use_photo_card'] || $order['use_photo_certificate']) {
                $fiturText = "🖼 Fitur: ";
                if ($order['use_photo_card']) $fiturText .= 'Kartu Ucapan ';
                if ($order['use_photo_certificate']) $fiturText .= 'Sertifikat Foto';
            }
            
            $jmlAnakText = $order['jumlah_anak'] . ' ekor';
            $hargaFormat = number_format($order['total_price'], 0, ',', '.');
            $menuDetailText = $menuDetail ? $menuDetail : "  - Belum diatur\n";

            $slaughterDateIndo = $this->formatTanggalIndo($order['slaughter_date']);
            $deliveryDateIndo = $this->formatTanggalIndo($order['delivery_date']);
            $slaughterTime = substr($order['slaughter_time'], 0, 5); // HH:mm tanpa detik
            
            $msg = "🔔 <b>PENGINGAT PEMOTONGAN ({$label})</b>\n"
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
                 . "├─ Box: {$totalBox} box\n"
                 . "└─ Harga: Rp {$hargaFormat}\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "<b>🍽 Menu Detail:</b>\n"
                 . $menuDetailText
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "📍 Alamat: {$customer['address']}\n"
                 . "🕐 Jam Potong: {$slaughterTime} WIB\n"
                 . "📅 Tanggal Potong: {$slaughterDateIndo}\n"
                 . "📦 Tanggal Antar: {$deliveryDateIndo}\n"
                 . "🎥 Metode: {$order['penyembelihan']}\n"
                 . ($fiturText ? $fiturText . "\n" : "")
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "⚡ Pastikan hewan siap dan koordinasi dengan tim dapur.\n";
            
            $telegram->sendMessage($msg);
            
            $notifModel->save([
                'order_id' => $order['id_order'],
                'type'     => '24h_reminder',
                'message'  => $msg
            ]);
            
            $sentCount++;
        }
        
        CLI::write("  ✅ {$sentCount} pengingat terkirim", 'green');
    }
    
    private function sendRecap()
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
        
        // Data stok
        $stocks = $stockModel->findAll();
        $stockInfo = '';
        $stockWarning = false;
        foreach ($stocks as $s) {
            $status = $s['quantity'] <= $s['min_threshold'] ? '⚠️ STOK MENIPIS' : '✅ OK';
            $stockInfo .= "  • {$s['item_name']}: {$s['quantity']} {$s['unit']} {$status}\n";
            if ($s['quantity'] <= $s['min_threshold']) $stockWarning = true;
        }
        
        $todayIndo = $this->formatTanggalIndo($today);
        
        if (empty($todayOrders)) {
            $msg = "📊 <b>REKAP HARIAN — {$todayIndo}</b>\n"
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
            CLI::write('  → Tidak ada pesanan hari ini', 'yellow');
            return;
        }
        
        $totalBox = 0;
        $totalPrice = 0;
        $dombaCount = 0;
        $kambingCount = 0;
        $deliveryToday = 0;
        $deliveryTomorrow = 0;
        $menuBoneList = [];
        $menuMeatList = [];
        $orderList = "";
        $customerModel = new CustomerModel();
        
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
                if ($d['bone_name']) $menuBoneList[$d['bone_name']] = ($menuBoneList[$d['bone_name']] ?? 0) + 1;
                if ($d['meat_name']) $menuMeatList[$d['meat_name']] = ($menuMeatList[$d['meat_name']] ?? 0) + 1;
            }
            
            $totalBox += $orderBoxCount;
            $totalPrice += $order['total_price'];
            
            if ($order['animal_type'] == 'Domba') $dombaCount++;
            else $kambingCount++;
            
            if ($order['delivery_date'] == $today) $deliveryToday++;
            if ($order['delivery_date'] == date('Y-m-d', strtotime('+1 day'))) $deliveryTomorrow++;
            
            $hargaOrder = number_format($order['total_price'], 0, ',', '.');
            $customerName = $customer ? $customer['child_name'] : 'N/A';
            $slaughterTime = substr($order['slaughter_time'], 0, 5);
            $orderList .= "  #{$order['id_order']} | {$customerName} | {$order['animal_type']} | {$package['name']} | {$orderBoxCount} box | Rp {$hargaOrder} | {$slaughterTime} WIB\n";
        }
        
        $menuBoneStr = '';
        foreach ($menuBoneList as $name => $count) {
            $menuBoneStr .= "  • {$name}: {$count} order\n";
        }
        $menuMeatStr = '';
        foreach ($menuMeatList as $name => $count) {
            $menuMeatStr .= "  • {$name}: {$count} order\n";
        }
        
        $totalPendapatan = number_format($totalPrice, 0, ',', '.');
        
        $msg = "📊 <b>REKAP HARIAN — {$todayIndo}</b>\n"
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "<b>📊 RINGKASAN</b>\n"
             . "📦 Total Pesanan: " . count($todayOrders) . "\n"
             . "📦 Total Box: {$totalBox}\n"
             . "🐏 Domba: {$dombaCount}\n"
             . "🐐 Kambing: {$kambingCount}\n"
             . "🚚 Pengantaran Hari Ini: {$deliveryToday}\n"
             . ($deliveryTomorrow ? "🚚 Pengantaran Besok: {$deliveryTomorrow}\n" : "")
             . "💰 Total Pendapatan: Rp {$totalPendapatan}\n"
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "<b>📋 DAFTAR PESANAN:</b>\n"
             . $orderList
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "<b>🍖 MENU TULANG:</b>\n"
             . ($menuBoneStr ? $menuBoneStr : "  - Tidak ada\n")
             . "━━━━━━━━━━━━━━━━━━━━\n"
             . "<b>🥩 MENU DAGING:</b>\n"
             . ($menuMeatStr ? $menuMeatStr : "  - Tidak ada\n")
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
        
        CLI::write("  ✅ Rekap harian terkirim (" . count($todayOrders) . " pesanan, {$totalBox} box)", 'green');
    }
}