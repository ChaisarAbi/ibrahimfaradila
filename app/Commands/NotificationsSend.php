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
    protected $group = 'Notifications';
    protected $name = 'notifications:send';
    protected $description = 'Send today recap and tomorrow preview via Telegram (12 PM WIB)';

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

    public function run(array $params)
    {
        date_default_timezone_set('Asia/Jakarta');

        $mode = $params[0] ?? 'all';

        CLI::write('====================================', 'yellow');
        CLI::write('  SISTEM NOTIFIKASI TELEGRAM', 'yellow');
        CLI::write('====================================', 'yellow');

        $telegram = new TelegramBot();

        if ($mode === 'all') {
            CLI::write(' Menghubungkan ke Telegram...', 'cyan');
            $testMsg = "Bot Telegram berhasil terhubung!\nWaktu: " . $this->now('d/m/Y H:i');
            $testResult = $telegram->sendMessage($testMsg);

            if ($testResult === false) {
                CLI::error(' Gagal terhubung ke Telegram. Cek token di .env');
                CLI::write('Pastikan TELEGRAM_BOT_TOKEN dan TELEGRAM_CHAT_ID terisi di file .env', 'red');
                return;
            }

            $response = json_decode($testResult, true);
            if (!$response || !($response['ok'] ?? false)) {
                CLI::error(' Gagal kirim test message: ' . ($response['description'] ?? 'Unknown error'));
                CLI::write('Response: ' . $testResult, 'red');
                return;
            }

            CLI::write(' Bot Telegram terhubung!', 'green');
        }

        CLI::write('');

        if ($mode === 'all' || $mode === 'today') {
            CLI::write('[' . $this->now('H:i') . '] Mengirim rekap hari ini...', 'yellow');
            $this->sendTodayRecap();
        }

        if ($mode === 'all' || $mode === 'tomorrow') {
            CLI::write('[' . $this->now('H:i') . '] Mengirim preview besok...', 'yellow');
            $this->sendTomorrowPreview();
        }

        CLI::write('');
        CLI::write('====================================', 'green');
        CLI::write('  NOTIFIKASI SELESAI!', 'green');
        CLI::write('====================================', 'green');
    }

    private function now($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

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

    private function buildRingkasan($orders)
    {
        $totalBox = 0;
        $dombaCount = 0;
        $kambingCount = 0;
        $detailModel = new OrderDetailModel();

        foreach ($orders as $order) {
            $details = $detailModel->where('order_id', $order['id_order'])->findAll();
            $orderBoxCount = 0;
            foreach ($details as $d) {
                $orderBoxCount += (int)$d['jumlah_box'];
            }
            $totalBox += $orderBoxCount;
            if ($order['animal_type'] == 'Domba') {
                $dombaCount++;
            } else {
                $kambingCount++;
            }
        }

        return [
            'totalOrders' => count($orders),
            'totalBox' => $totalBox,
            'dombaCount' => $dombaCount,
            'kambingCount' => $kambingCount,
        ];
    }

    private function sendTodayRecap()
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
            // Stok info
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
            CLI::write('  - Tidak ada pesanan hari ini', 'yellow');
            return;
        }

        $ringkasan = $this->buildRingkasan($todayOrders);

        // --- Daftar Pesanan Compact ---
        $orderList = '';
        // --- Rekap Dapur ---
        $dapurBone = [];    // menu tulang
        $dapurMeat = [];    // menu daging

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

            $slaughterTime = substr($order['slaughter_time'], 0, 5) ?: '-';
            $animalEmoji = $order['animal_type'] == 'Domba' ? '🐑' : '🐐';

            // Compact order line
            $orderList .= '🔹 `#' . $order['id_order'] . '` *' . ($customer['child_name'] ?: $customer['name']) . "*\n";
            $orderList .= '   └ ' . $animalEmoji . ' ' . $order['animal_type'] . ' | ' . ($package['name'] ?? '-') . ' | *' . $orderBoxCount . ' Box* | 🕒 ' . $slaughterTime . " WIB\n";
        }

        // --- Rekap Dapur ---
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

        // --- Stok: hanya tampilkan yang critical/warning ---
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

        // Build hewan summary
        $hewanParts = [];
        if ($ringkasan['dombaCount'] > 0) $hewanParts[] = '🐑 ' . $ringkasan['dombaCount'] . ' Domba';
        if ($ringkasan['kambingCount'] > 0) $hewanParts[] = '🐐 ' . $ringkasan['kambingCount'] . ' Kambing';

        $msg = "📊 *REKAP HARIAN AQIQAH*\n"
             . '🗓 *' . $dayName . ', ' . $todayIndo . "*\n"
             . "────────────────────\n\n"
             . "📈 *RINGKASAN OPERASIONAL*\n"
             . '• Total Pesanan : *' . $ringkasan['totalOrders'] . " Order*\n"
             . '• Total Olahan  : *' . $ringkasan['totalBox'] . " Box*\n"
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
            'type' => 'daily_recap',
            'message' => $msg,
        ]);

        CLI::write("  Rekap harian terkirim (" . count($todayOrders) . " pesanan, " . $ringkasan['totalBox'] . " box)", 'green');
    }

    private function sendTomorrowPreview()
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
                'type' => 'tomorrow_preview',
                'message' => $msg,
            ]);

            CLI::write('  - Tidak ada pesanan untuk besok', 'yellow');
            return;
        }

        $ringkasan = $this->buildRingkasan($orders);
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

        // Summary hewan
        $hewanParts = [];
        if ($ringkasan['dombaCount'] > 0) $hewanParts[] = '🐑 ' . $ringkasan['dombaCount'] . ' Domba';
        if ($ringkasan['kambingCount'] > 0) $hewanParts[] = '🐐 ' . $ringkasan['kambingCount'] . ' Kambing';

        $msg = "📅 *PREVIEW JADWAL BESOK*\n"
             . '🗓 *' . $dayName . ', ' . $tomorrowIndo . "*\n"
             . "────────────────────\n\n"
             . "📊 *SUMMARY BESOK*\n"
             . '• Total Order : *' . $ringkasan['totalOrders'] . " Pesanan*\n"
             . '• Total Box   : *' . $ringkasan['totalBox'] . " Box*\n"
             . '• Hewan       : ' . implode(' | ', $hewanParts) . "\n\n"
             . $orderDetails
             . "────────────────────\n\n"
             . "💡 *Catatan:* Pastikan hewan siap dan koordinasi dengan tim dapur.";

        $telegram->sendMessage($msg);

        $notifModel->save([
            'type' => 'tomorrow_preview',
            'message' => $msg,
        ]);

        CLI::write("  Preview besok terkirim (" . count($orders) . " pesanan, " . $ringkasan['totalBox'] . " box)", 'green');
    }
}