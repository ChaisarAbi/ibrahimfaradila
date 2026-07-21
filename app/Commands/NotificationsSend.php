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

    private function sendTodayRecap()
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
            $status = $s['quantity'] <= $minThreshold ? ' STOK MENIPIS' : ' OK';
            $stockInfo .= '  ' . $s['item_name'] . ': ' . $s['quantity'] . ' ' . $s['unit'] . ' ' . $status . "\n";
            if ($s['quantity'] <= $minThreshold) {
                $stockWarning = true;
            }
        }

        $todayIndo = $this->formatTanggalIndo($today);

        if (empty($todayOrders)) {
            $msg = "REKAP HARIAN - " . $todayIndo . "\n"
                 . "----------------------------\n"
                 . "Total Pesanan: 0\n"
                 . "----------------------------\n"
                 . "Tidak ada jadwal pemotongan untuk hari ini.\n"
                 . "----------------------------\n"
                 . "Stok Tersedia:\n"
                 . $stockInfo
                 . "----------------------------\n"
                 . "Sistem berjalan normal.";

            $telegram->sendMessage($msg);
            CLI::write('  - Tidak ada pesanan hari ini', 'yellow');
            return;
        }

        $totalBox = 0;
        $dombaCount = 0;
        $kambingCount = 0;
        $deliveryToday = 0;
        $menuBoneList = [];
        $menuMeatList = [];
        $orderList = '';
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
                if ($d['bone_name']) {
                    $menuBoneList[$d['bone_name']] = ($menuBoneList[$d['bone_name']] ?? 0) + 1;
                }
                if ($d['meat_name']) {
                    $menuMeatList[$d['meat_name']] = ($menuMeatList[$d['meat_name']] ?? 0) + 1;
                }
            }

            $totalBox += $orderBoxCount;

            if ($order['animal_type'] == 'Domba') {
                $dombaCount++;
            } else {
                $kambingCount++;
            }

            if ($order['delivery_date'] == $today) {
                $deliveryToday++;
            }

            $customerName = $customer ? $customer['child_name'] : 'N/A';
            $slaughterTime = substr($order['slaughter_time'], 0, 5);
            $orderList .= '  #' . $order['id_order'] . ' | ' . $customerName . ' | ' . $order['animal_type'] . ' | ' . $package['name'] . ' | ' . $orderBoxCount . ' box | ' . $slaughterTime . " WIB\n";
        }

        $menuBoneStr = '';
        foreach ($menuBoneList as $name => $count) {
            $menuBoneStr .= '  ' . $name . ': ' . $count . " order\n";
        }
        $menuMeatStr = '';
        foreach ($menuMeatList as $name => $count) {
            $menuMeatStr .= '  ' . $name . ': ' . $count . " order\n";
        }

        $msg = "REKAP HARIAN - " . $todayIndo . "\n"
             . "----------------------------\n"
             . "RINGKASAN\n"
             . "Total Pesanan: " . count($todayOrders) . "\n"
             . "Total Box: " . $totalBox . "\n"
             . "Domba: " . $dombaCount . "\n"
             . "Kambing: " . $kambingCount . "\n"
             . "Pengantaran Hari Ini: " . $deliveryToday . "\n"
             . "----------------------------\n"
             . "DAFTAR PESANAN:\n"
             . $orderList
             . "----------------------------\n"
             . "MENU TULANG:\n"
             . ($menuBoneStr ? $menuBoneStr : "  - Tidak ada\n")
             . "----------------------------\n"
             . "MENU DAGING:\n"
             . ($menuMeatStr ? $menuMeatStr : "  - Tidak ada\n")
             . "----------------------------\n"
             . "STOK TERKINI:\n"
             . $stockInfo
             . ($stockWarning ? "----------------------------\nPERHATIAN: Ada stok yang menipis! Segera lakukan pengadaan.\n" : '')
             . "----------------------------\n"
             . "Semoga lancar hari ini!";

        $telegram->sendMessage($msg);

        $notifModel->save([
            'type' => 'daily_recap',
            'message' => $msg,
        ]);

        CLI::write("  Rekap harian terkirim (" . count($todayOrders) . " pesanan, " . $totalBox . " box)", 'green');
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

        $orders = $orderModel->where('slaughter_date', $tomorrow)
            ->where('status !=', 'Cancelled')
            ->findAll();

        if (empty($orders)) {
            $msg = "PREVIEW BESOK - " . $tomorrowIndo . "\n"
                 . "----------------------------\n"
                 . "Tidak ada jadwal pemotongan untuk besok.\n"
                 . "----------------------------\n"
                 . "Sistem berjalan normal.";

            $telegram->sendMessage($msg);

            $notifModel->save([
                'type' => 'tomorrow_preview',
                'message' => $msg,
            ]);

            CLI::write('  - Tidak ada pesanan untuk besok', 'yellow');
            return;
        }

        $totalBox = 0;
        $dombaCount = 0;
        $kambingCount = 0;
        $orderList = '';

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
                $menuDetail .= '    ' . $boneName . ' + ' . $meatName . ' (' . $d['box_type'] . ': ' . $d['jumlah_box'] . " box)\n";
            }

            $totalBox += $orderBoxCount;

            if ($order['animal_type'] == 'Domba') {
                $dombaCount++;
            } else {
                $kambingCount++;
            }

            $jmlAnakText = $order['jumlah_anak'] . ' ekor';
            $slaughterTime = substr($order['slaughter_time'], 0, 5);
            $deliveryDate = $order['delivery_date'] ? $this->formatTanggalIndo($order['delivery_date']) : '-';

            $fiturText = '';
            if ($order['use_photo_card'] || $order['use_photo_certificate']) {
                $fiturText = '    Fitur: ';
                if ($order['use_photo_card']) {
                    $fiturText .= 'Kartu Ucapan ';
                }
                if ($order['use_photo_certificate']) {
                    $fiturText .= 'Sertifikat Foto';
                }
            }

            $orderList .= "----------------------------\n";
            $orderList .= "  Order #" . $order['id_order'] . "\n";
            $orderList .= "  Pemesan: " . $customer['name'] . "\n";
            $orderList .= "  No. HP: " . $customer['phone'] . "\n";
            $orderList .= "  Nama Anak: " . $customer['child_name'] . " (" . $customer['gender'] . ")\n";
            $orderList .= "  Tanggal Lahir: " . $customer['birth_date'] . "\n";
            $orderList .= "  Hewan: " . $order['animal_type'] . " (" . $order['animal_gender'] . ") - " . $jmlAnakText . "\n";
            $orderList .= "  Paket: " . $package['name'] . "\n";
            $orderList .= "  Bobot: " . $package['weight_type'] . " (" . $package['min_weight'] . "-" . $package['max_weight'] . " kg)\n";
            $orderList .= "  Box: " . $orderBoxCount . " box\n";
            $orderList .= "  Menu:\n";
            $orderList .= $menuDetail ? $menuDetail : "    - Belum diatur\n";
            $orderList .= "  Alamat: " . $customer['address'] . "\n";
            $orderList .= "  Jam Potong: " . $slaughterTime . " WIB\n";
            $orderList .= "  Tanggal Antar: " . $deliveryDate . "\n";
            $orderList .= "  Metode: " . $order['penyembelihan'] . "\n";
            if ($fiturText) {
                $orderList .= $fiturText . "\n";
            }
        }

        $msg = "PREVIEW BESOK - " . $tomorrowIndo . "\n"
             . "----------------------------\n"
             . "RINGKASAN\n"
             . "Total Pesanan: " . count($orders) . "\n"
             . "Total Box: " . $totalBox . "\n"
             . "Domba: " . $dombaCount . "\n"
             . "Kambing: " . $kambingCount . "\n"
             . "----------------------------\n"
             . "DETAIL PESANAN:\n"
             . $orderList
             . "----------------------------\n"
             . "Pastikan hewan siap dan koordinasi dengan tim dapur.";

        $telegram->sendMessage($msg);

        $notifModel->save([
            'type' => 'tomorrow_preview',
            'message' => $msg,
        ]);

        CLI::write("  Preview besok terkirim (" . count($orders) . " pesanan, " . $totalBox . " box)", 'green');
    }
}