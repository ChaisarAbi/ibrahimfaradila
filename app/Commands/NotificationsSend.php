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
            // Stok info - tampilkan semua item
            $stocks = $stockModel->findAll();
            $stockLines = [];
            foreach ($stocks as $s) {
                $minThreshold = $s['min_threshold'] ?? 0;
                $icon = $s['quantity'] <= $minThreshold ? "\xE2\x9A\xA0\xEF\xB8\x8F" : "\xE2\x9C\x85";
                $status = $s['quantity'] <= $minThreshold ? ' (Menipis)' : '';
                $stockLines[] = $icon . ' ' . $s['item_name'] . ': *' . $s['quantity'] . ' ' . $s['unit'] . '*' . $status;
            }

            $msg = "\xF0\x9F\x93\x8A *REKAP HARIAN AQIQAH*\n"
                 . "\xF0\x9F\x97\x93 *" . $dayName . ', ' . $todayIndo . "*\n"
                 . "────────────────────\n\n"
                 . "Tidak ada jadwal pemotongan untuk hari ini.\n\n"
                 . "\xF0\x9F\x93\xA6 *STATUS STOK*\n";
            if (!empty($stockLines)) {
                $msg .= implode("\n", $stockLines) . "\n";
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
        $dapurBone = [];
        $dapurMeat = [];

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
            $animalEmoji = $order['animal_type'] == 'Domba' ? "\xF0\x9F\x90\x91" : "\xF0\x9F\x90\x90";

            // Compact order line
            $orderList .= "\xF0\x9F\x94\xB9 `#" . $order['id_order'] . '` *' . ($customer['child_name'] ?: $customer['name']) . "*\n";
            $orderList .= '   \xE2\x94\x94 ' . $animalEmoji . ' ' . $order['animal_type'] . ' | ' . ($package['name'] ?? '-') . ' | *' . $orderBoxCount . ' Box* | ' . "\xF0\x9F\x95\x92" . ' ' . $slaughterTime . " WIB\n";
        }

        // --- Rekap Dapur ---
        $dapurLines = [];
        if (!empty($dapurBone)) {
            $boneParts = [];
            foreach ($dapurBone as $name => $count) {
                $boneParts[] = $name . ' (' . $count . ')';
            }
            $dapurLines[] = "\xE2\x80\xA2 *Menu Tulang:* " . implode(', ', $boneParts);
        }
        if (!empty($dapurMeat)) {
            $meatParts = [];
            foreach ($dapurMeat as $name => $count) {
                $meatParts[] = $name . ' (' . $count . ')';
            }
            $dapurLines[] = "\xE2\x80\xA2 *Menu Daging:* " . implode(', ', $meatParts);
        }

        // --- Stok: tampilkan semua item ---
        $stocks = $stockModel->findAll();
        $stockLines = [];
        foreach ($stocks as $s) {
            $minThreshold = $s['min_threshold'] ?? 0;
            $icon = $s['quantity'] <= $minThreshold ? "\xE2\x9A\xA0\xEF\xB8\x8F" : "\xE2\x9C\x85";
            $status = $s['quantity'] <= $minThreshold ? ' (Menipis)' : '';
            $stockLines[] = $icon . ' ' . $s['item_name'] . ': *' . $s['quantity'] . ' ' . $s['unit'] . '*' . $status;
        }

        // Build hewan summary
        $hewanParts = [];
        if ($ringkasan['dombaCount'] > 0) $hewanParts[] = "\xF0\x9F\x90\x91 " . $ringkasan['dombaCount'] . ' Domba';
        if ($ringkasan['kambingCount'] > 0) $hewanParts[] = "\xF0\x9F\x90\x90 " . $ringkasan['kambingCount'] . ' Kambing';

        $msg = "\xF0\x9F\x93\x8A *REKAP HARIAN AQIQAH*\n"
             . "\xF0\x9F\x97\x93 *" . $dayName . ', ' . $todayIndo . "*\n"
             . "────────────────────\n\n"
             . "\xF0\x9F\x93\x88 *RINGKASAN OPERASIONAL*\n"
             . "\xE2\x80\xA2 Total Pesanan : *" . $ringkasan['totalOrders'] . " Order*\n"
             . "\xE2\x80\xA2 Total Olahan  : *" . $ringkasan['totalBox'] . " Box*\n"
             . "\xE2\x80\xA2 Rincian Hewan : " . implode(' | ', $hewanParts) . "\n"
             . "\xE2\x80\xA2 Pengantaran   : " . ($deliveryTodayCount > 0 ? "\xF0\x9F\x9A\x9A *" . $deliveryTodayCount . ' Hari ini*' : "\xF0\x9F\x9A\x9A -") . "\n\n"
             . "\xF0\x9F\x93\xA6 *DAFTAR PESANAN*\n"
             . $orderList . "\n";

        if (!empty($dapurLines)) {
            $msg .= "\xF0\x9F\x8D\xB2 *REKAP DAPUR*\n"
                  . implode("\n", $dapurLines) . "\n\n";
        }

        $msg .= "\xF0\x9F\x93\xA6 *STATUS STOK*\n";
        if (!empty($stockLines)) {
            $msg .= implode("\n", $stockLines);
        }

        $msg .= "\n\n────────────────────\n"
              . "\xE2\x9C\xA8 *Semoga operasional hari ini berjalan lancar!* \xF0\x9F\x99\x8F";

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
            $msg = "\xF0\x9F\x93\x85 *PREVIEW JADWAL BESOK*\n"
                 . "\xF0\x9F\x97\x93 *" . $dayName . ', ' . $tomorrowIndo . "*\n\n"
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
            $animalEmoji = $order['animal_type'] == 'Domba' ? "\xF0\x9F\x90\x91" : "\xF0\x9F\x90\x90";

            $fiturList = [];
            if ($order['use_photo_card']) $fiturList[] = 'Kartu Ucapan';
            if ($order['use_photo_certificate']) $fiturList[] = 'Sertifikat';
            if (!empty($order['photo_path'])) $fiturList[] = 'Foto';
            $fiturText = !empty($fiturList) ? implode(', ', $fiturList) : '-';

            $orderDetails .= "────────────────────\n";
            $orderDetails .= "\xF0\x9F\x93\x8B *DETAIL PESANAN #" . $order['id_order'] . "*\n\n";
            $orderDetails .= "\xF0\x9F\x91\xA4 *Pemesan:* " . $customer['name'] . ' (`' . $customer['phone'] . "`)\n";
            $orderDetails .= "\xF0\x9F\x91\xB6 *Anak:* " . ($customer['child_name'] ?: '-') . ' (' . $customer['gender'] . ') | ' . "\xF0\x9F\x97\x93" . ' ' . $birthDate . "\n";
            $orderDetails .= $animalEmoji . ' *Hewan:* ' . $order['animal_type'] . ' ' . $order['animal_gender'] . ' (' . $jmlAnakText . ")\n";
            $orderDetails .= "\xF0\x9F\x93\xA6 *Paket:* " . $package['name'] . ' (Bobot ' . $package['weight_type'] . ': ' . $package['min_weight'] . "\xE2\x80\x93" . $package['max_weight'] . ' kg) | *Total ' . $orderBoxCount . " Box*\n\n";

            $orderDetails .= "\xF0\x9F\x8D\xB1 *Menu Box:*\n";
            foreach ($menuLines as $ml) {
                $orderDetails .= ' \xE2\x96\xAB\xEF\xB8\x8F ' . $ml . "\n";
            }

            $orderDetails .= "\n\xE2\x9A\x99\xEF\xB8\x8F *Detail Operasional:*\n";
            $orderDetails .= ' ' . "\xF0\x9F\x95\x92" . ' Jam Potong : *' . $slaughterTime . " WIB*\n";
            $orderDetails .= ' ' . "\xF0\x9F\x9A\x9A" . ' Tgl Antar  : *' . $deliveryDate . "*\n";
            $orderDetails .= ' ' . "\xF0\x9F\x8E\xA5" . ' Potong     : ' . ($order['penyembelihan'] ?: '-') . "\n";
            $orderDetails .= ' ' . "\xF0\x9F\x93\x8D" . ' Alamat     : ' . ($customer['address'] ?: '-') . "\n";
            $orderDetails .= ' ' . "\xF0\x9F\x8E\x81" . ' Fitur       : ' . $fiturText . "\n\n";
        }

        // Summary hewan
        $hewanParts = [];
        if ($ringkasan['dombaCount'] > 0) $hewanParts[] = "\xF0\x9F\x90\x91 " . $ringkasan['dombaCount'] . ' Domba';
        if ($ringkasan['kambingCount'] > 0) $hewanParts[] = "\xF0\x9F\x90\x90 " . $ringkasan['kambingCount'] . ' Kambing';

        $msg = "\xF0\x9F\x93\x85 *PREVIEW JADWAL BESOK*\n"
             . "\xF0\x9F\x97\x93 *" . $dayName . ', ' . $tomorrowIndo . "*\n"
             . "────────────────────\n\n"
             . "\xF0\x9F\x93\x8A *SUMMARY BESOK*\n"
             . "\xE2\x80\xA2 Total Order : *" . $ringkasan['totalOrders'] . " Pesanan*\n"
             . "\xE2\x80\xA2 Total Box   : *" . $ringkasan['totalBox'] . " Box*\n"
             . "\xE2\x80\xA2 Hewan       : " . implode(' | ', $hewanParts) . "\n\n"
             . $orderDetails
             . "────────────────────\n\n"
             . "\xF0\x9F\x92\xA1 *Catatan:* Pastikan hewan siap dan koordinasi dengan tim dapur.";

        $telegram->sendMessage($msg);

        $notifModel->save([
            'type' => 'tomorrow_preview',
            'message' => $msg,
        ]);

        CLI::write("  Preview besok terkirim (" . count($orders) . " pesanan, " . $ringkasan['totalBox'] . " box)", 'green');
    }
}