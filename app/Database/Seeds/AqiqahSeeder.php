<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AqiqahSeeder extends Seeder
{
    public function run()
    {
        $this->createSchemaIfNeeded();
        $this->truncateAndSeed();
    }

    private function createSchemaIfNeeded()
    {
        // Cek apakah tabel users sudah ada
        $tableExists = $this->db->query("SHOW TABLES LIKE 'users'")->getNumRows() > 0;
        if ($tableExists) {
            echo "  ✓ Tables already exist, skipping schema creation\n";
            return;
        }

        echo "  ⏳ Creating schema from database.sql...\n";

        // Baca file database.sql dari root project
        $sqlPath = ROOTPATH . 'database.sql';
        if (!file_exists($sqlPath)) {
            echo "  ✗ database.sql not found at: $sqlPath\n";
            return;
        }

        $sql = file_get_contents($sqlPath);

        // Hapus komentar SQL (baris diawali -- atau #)
        $sql = preg_replace('/^(--|#).*$/m', '', $sql);

        // Split semua query berdasarkan titik koma
        $queries = explode(';', $sql);

        $createCount = 0;
        foreach ($queries as $query) {
            $trimmed = trim($query);
            if (empty($trimmed)) continue;
            if (preg_match('/^USE /i', $trimmed)) continue;
            if (preg_match('/^INSERT /i', $trimmed)) continue;
            if (preg_match('/^CREATE DATABASE/i', $trimmed)) continue;

            // Tambahin ; kembali untuk execute
            $fullQuery = $trimmed . ';';
            $this->db->query($fullQuery);
            $createCount++;
        }

        echo "  ✓ Schema created ($createCount tables/indices)\n";
    }

    private function truncateAndSeed()
    {
        // Disable FK checks, truncate, then re-enable
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('notifications')->truncate();
        $this->db->table('schedules')->truncate();
        $this->db->table('order_details')->truncate();
        $this->db->table('orders')->truncate();
        $this->db->table('customers')->truncate();
        $this->db->table('stocks')->truncate();
        $this->db->table('settings')->truncate();
        $this->db->table('packages')->truncate();
        $this->db->table('meat_menus')->truncate();
        $this->db->table('bone_menus')->truncate();
        $this->db->table('users')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        echo "  ✓ Existing data truncated\n";

        // ===================== USERS =====================
        $users = [
            ['username' => 'admin',  'password' => password_hash('admin123', PASSWORD_BCRYPT),  'fullname' => 'Administrator', 'role' => 'admin'],
            ['username' => 'rph',    'password' => password_hash('rph123', PASSWORD_BCRYPT),    'fullname' => 'RPH Operasional', 'role' => 'rph'],
            ['username' => 'dapur',  'password' => password_hash('dapur123', PASSWORD_BCRYPT),  'fullname' => 'Tim Dapur',      'role' => 'dapur'],
        ];
        $this->db->table('users')->insertBatch($users);
        echo "  ✓ Users inserted (3 users)\n";

        // ===================== BONE MENUS =====================
        $boneMenus = [
            ['name' => 'Gulai'], ['name' => 'Sop'], ['name' => 'Tongseng'], ['name' => 'Rendang'],
        ];
        $this->db->table('bone_menus')->insertBatch($boneMenus);
        echo "  ✓ Bone menus inserted (4 menus)\n";

        // ===================== MEAT MENUS =====================
        $meatMenus = [
            ['name' => 'Sate tanpa tusuk'], ['name' => 'Teriyaki'], ['name' => 'Lada Hitam'],
            ['name' => 'Krengseng'], ['name' => 'Semur'], ['name' => 'Sambal Goreng'],
        ];
        $this->db->table('meat_menus')->insertBatch($meatMenus);
        echo "  ✓ Meat menus inserted (6 menus)\n";

        // ===================== PACKAGES =====================
        $packages = [
            ['name' => 'Paket Berkah A',  'weight_type' => 'A', 'min_weight' => 16, 'max_weight' => 17, 'box_count' => 50,  'price' => 2500000, 'is_special' => 0],
            ['name' => 'Paket Special A',  'weight_type' => 'A', 'min_weight' => 16, 'max_weight' => 17, 'box_count' => 50,  'price' => 3000000, 'is_special' => 1],
            ['name' => 'Paket Berkah B',  'weight_type' => 'B', 'min_weight' => 17, 'max_weight' => 18, 'box_count' => 60,  'price' => 2800000, 'is_special' => 0],
            ['name' => 'Paket Special B',  'weight_type' => 'B', 'min_weight' => 17, 'max_weight' => 18, 'box_count' => 60,  'price' => 3400000, 'is_special' => 1],
            ['name' => 'Paket Berkah C',  'weight_type' => 'C', 'min_weight' => 18, 'max_weight' => 20, 'box_count' => 80,  'price' => 3400000, 'is_special' => 0],
            ['name' => 'Paket Special C',  'weight_type' => 'C', 'min_weight' => 18, 'max_weight' => 20, 'box_count' => 80,  'price' => 4200000, 'is_special' => 1],
            ['name' => 'Paket Berkah D',  'weight_type' => 'D', 'min_weight' => 21, 'max_weight' => 23, 'box_count' => 100, 'price' => 3900000, 'is_special' => 0],
            ['name' => 'Paket Special D',  'weight_type' => 'D', 'min_weight' => 21, 'max_weight' => 23, 'box_count' => 100, 'price' => 4900000, 'is_special' => 1],
        ];
        $this->db->table('packages')->insertBatch($packages);
        echo "  ✓ Packages inserted (8 packages)\n";

        // ===================== CUSTOMERS =====================
        $customers = [
            ['name' => 'H. Abdullah Husain',     'child_name' => 'Muhammad Al-Fatih',  'gender' => 'Laki-laki',  'birth_date' => '2026-01-15', 'phone' => '081234567890', 'address' => 'Jl. Merdeka No. 10, Jakarta Pusat'],
            ['name' => 'Siti Nurhaliza',          'child_name' => 'Aisyah Putri',       'gender' => 'Perempuan',  'birth_date' => '2026-02-20', 'phone' => '082345678901', 'address' => 'Jl. Sudirman No. 25, Jakarta Selatan'],
            ['name' => 'Ahmad Rizki Pratama',     'child_name' => 'Bilal Arrasyid',     'gender' => 'Laki-laki',  'birth_date' => '2026-03-10', 'phone' => '083456789012', 'address' => 'Perumahan Citra Indah Blok A5, Bekasi'],
            ['name' => 'Dewi Sartika',            'child_name' => 'Haura Azkia',        'gender' => 'Perempuan',  'birth_date' => '2026-04-05', 'phone' => '085678901234', 'address' => 'Jl. Diponegoro No. 88, Bandung'],
            ['name' => 'H. Bambang Supriyadi',    'child_name' => 'Zaidan Al-Ghifari',  'gender' => 'Laki-laki',  'birth_date' => '2026-01-28', 'phone' => '087890123456', 'address' => 'Komplek Bumi Asri No. 15, Tangerang'],
            ['name' => 'Fitriani Kusuma Dewi',    'child_name' => 'Nadia Khairunnisa',  'gender' => 'Perempuan',  'birth_date' => '2026-05-12', 'phone' => '089012345678', 'address' => 'Jl. Pahlawan No. 7, Depok'],
            ['name' => 'H. Dodi Hermawan',        'child_name' => 'Rafi Ahmad',         'gender' => 'Laki-laki',  'birth_date' => '2026-03-22', 'phone' => '081298765432', 'address' => 'Villa Bogor Indah Blok C3, Bogor'],
            ['name' => 'Rina Mahardani',          'child_name' => 'Salwa Azzahra',      'gender' => 'Perempuan',  'birth_date' => '2026-06-18', 'phone' => '082109876543', 'address' => 'Jl. Anggrek No. 33, Jakarta Timur'],
            ['name' => 'H. Agus Salim',           'child_name' => 'Hafidz Al-Quran',    'gender' => 'Laki-laki',  'birth_date' => '2026-04-01', 'phone' => '083210987654', 'address' => 'Perum Green Garden No. 10, Jakarta Barat'],
            ['name' => 'Mega Wulandari',          'child_name' => 'Fatimah Az-Zahra',   'gender' => 'Perempuan',  'birth_date' => '2026-07-08', 'phone' => '085432109876', 'address' => 'Jl. Cendrawasih No. 12, Karawang'],
            ['name' => 'H. Syamsul Bahri',        'child_name' => 'Ibrahim Malik',      'gender' => 'Laki-laki',  'birth_date' => '2026-05-30', 'phone' => '087654321098', 'address' => 'Komplek Permata Hijau No. 5, Serang'],
            ['name' => 'Lilis Suryani',           'child_name' => 'Zahra Ramadhani',    'gender' => 'Perempuan',  'birth_date' => '2026-08-15', 'phone' => '089876543210', 'address' => 'Jl. Kenanga No. 20, Cilegon'],
            ['name' => 'H. Faisal Rahman',        'child_name' => 'Ahmad Syauqi',       'gender' => 'Laki-laki',  'birth_date' => '2026-06-01', 'phone' => '081111222333', 'address' => 'Jl. Mawar No. 5, Bogor'],
            ['name' => 'Nurul Hidayah',           'child_name' => 'Hana Safira',        'gender' => 'Perempuan',  'birth_date' => '2026-07-20', 'phone' => '082222333444', 'address' => 'Perum Pondok Indah Blok D, Depok'],
            ['name' => 'H. Hasan Basri',          'child_name' => 'Yusuf Al-Farizi',    'gender' => 'Laki-laki',  'birth_date' => '2026-05-05', 'phone' => '083333444555', 'address' => 'Komplek Puri Mutiara No. 8, Bekasi'],
            ['name' => 'Ratna Sari Dewi',         'child_name' => 'Sofia Rahmawati',    'gender' => 'Perempuan',  'birth_date' => '2026-08-01', 'phone' => '085555666777', 'address' => 'Jl. Melati No. 12, Tangerang'],
            ['name' => 'H. Zainal Arifin',        'child_name' => 'Mikail Al-Ghifari',  'gender' => 'Laki-laki',  'birth_date' => '2026-06-15', 'phone' => '087777888999', 'address' => 'Villa Bintang Mas Blok A, Serang'],
            ['name' => 'Amalia Putri',            'child_name' => 'Aisyah Ramadhani',   'gender' => 'Perempuan',  'birth_date' => '2026-09-01', 'phone' => '089999000111', 'address' => 'Jl. Flamboyan No. 22, Jakarta Timur'],
            ['name' => 'H. Burhanuddin',          'child_name' => 'Ishaq Al-Karim',     'gender' => 'Laki-laki',  'birth_date' => '2026-07-12', 'phone' => '081000111222', 'address' => 'Perum Bukit Indah No. 3, Cilegon'],
        ];
        $this->db->table('customers')->insertBatch($customers);
        echo "  ✓ Customers inserted (" . count($customers) . " customers)\n";

        // ===================== STOCKS =====================
        $stocks = [
            ['item_name' => 'Kambing',     'category' => 'hewan', 'quantity' => 25, 'min_threshold' => 5,  'unit' => 'ekor'],
            ['item_name' => 'Domba',       'category' => 'hewan', 'quantity' => 18, 'min_threshold' => 5,  'unit' => 'ekor'],
            ['item_name' => 'Beras',       'category' => 'bahan', 'quantity' => 200, 'min_threshold' => 30, 'unit' => 'kg'],
            ['item_name' => 'Minyak Goreng','category' => 'bahan', 'quantity' => 50, 'min_threshold' => 10, 'unit' => 'liter'],
            ['item_name' => 'Bumbu Gulai',  'category' => 'bahan', 'quantity' => 30, 'min_threshold' => 5,  'unit' => 'pack'],
            ['item_name' => 'Bumbu Sop',    'category' => 'bahan', 'quantity' => 25, 'min_threshold' => 5,  'unit' => 'pack'],
            ['item_name' => 'Tusuk Sate',   'category' => 'bahan', 'quantity' => 500, 'min_threshold' => 100, 'unit' => 'batang'],
            ['item_name' => 'Kecap',        'category' => 'bahan', 'quantity' => 20, 'min_threshold' => 5,  'unit' => 'botol'],
            ['item_name' => 'Santan',       'category' => 'bahan', 'quantity' => 40, 'min_threshold' => 10, 'unit' => 'pack'],
            ['item_name' => 'Box Premium',  'category' => 'bahan', 'quantity' => 300, 'min_threshold' => 50, 'unit' => 'buah'],
            ['item_name' => 'Bento Pack',   'category' => 'bahan', 'quantity' => 200, 'min_threshold' => 50, 'unit' => 'buah'],
        ];
        $this->db->table('stocks')->insertBatch($stocks);
        echo "  ✓ Stocks inserted (11 items)\n";

        // ================================================================
        //  GENERATE ORDERS FOR 14 DAYS (Hari ini + 13 hari ke depan)
        // ================================================================
        $animalTypes = ['Kambing', 'Domba'];
        $animalGenders = ['Jantan', 'Betina'];
        $penyembelihan = ['Dokumentasi', 'Video Call', 'Visit'];
        $slaughterTimes = ['06:00:00', '06:30:00', '07:00:00', '07:30:00', '08:00:00', '08:30:00', '09:00:00', '09:30:00', '10:00:00'];

        $ordersPerDay = [2, 2, 2, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1];

        $today = date('Y-m-d');
        $allOrders = [];
        $customerIdx = 0;
        $timeIdx = 0;

        foreach ($ordersPerDay as $dayOffset => $count) {
            $slaughterDate = date('Y-m-d', strtotime("+{$dayOffset} days"));
            $deliveryDate = date('Y-m-d', strtotime("+{$dayOffset} days +1 day"));

            for ($o = 0; $o < $count; $o++) {
                $customerIdx++; // 1-based
                $pkgId = ($customerIdx % 8) + 1; // 1-8
                $animalType = $animalTypes[$customerIdx % 2];
                $animalGender = $animalGenders[$customerIdx % 2];
                $jmlAnak = ($customerIdx % 2 == 0) ? 2 : 1;
                $peny = $penyembelihan[$customerIdx % 3];
                $useCard = ($customerIdx % 3 != 0);
                $useCert = ($customerIdx % 2 == 0);

                $status = ($dayOffset == 0) ? 'Scheduled' : 'Pending';

                $slaughterTime = $slaughterTimes[$timeIdx % count($slaughterTimes)];
                $timeIdx++;

                $pkgPrices = [2500000, 3000000, 2800000, 3400000, 3400000, 4200000, 3900000, 4900000];
                $totalPrice = $pkgPrices[$pkgId - 1];

                $allOrders[] = [
                    'customer_id'           => $customerIdx,
                    'package_id'            => $pkgId,
                    'animal_type'           => $animalType,
                    'animal_gender'         => $animalGender,
                    'jumlah_anak'           => $jmlAnak,
                    'slaughter_date'        => $slaughterDate,
                    'delivery_date'         => $deliveryDate,
                    'slaughter_time'        => $slaughterTime,
                    'penyembelihan'         => $peny,
                    'use_photo_card'        => $useCard ? 1 : 0,
                    'use_photo_certificate' => $useCert ? 1 : 0,
                    'status'                => $status,
                    'total_price'           => $totalPrice,
                ];
            }
        }

        $this->db->table('orders')->insertBatch($allOrders);
        echo "  ✓ Orders inserted (" . count($allOrders) . " orders for 14 days)\n";

        // ===================== ORDER DETAILS =====================
        $orderDetails = [];
        $boneMenuCombos = [
            [1, 1], [1, 2], [2, 2], [2, 3],
            [3, 1], [3, 4], [4, 5], [1, 5],
            [2, 1], [4, 1], [4, 3], [1, 3],
            [2, 6], [3, 6], [1, 4], [2, 5],
            [3, 5], [4, 4], [4, 2],
        ];
        $boxTypes = ['Box Premium', 'Bento Pack'];

        for ($oid = 1; $oid <= count($allOrders); $oid++) {
            $combo = $boneMenuCombos[($oid - 1) % count($boneMenuCombos)];
            $bType = $boxTypes[$oid % 2];
            $totalBox = ($oid % 2 == 0) ? 25 : 30;

            $orderDetails[] = [
                'order_id'    => $oid,
                'bone_menu_id' => $combo[0],
                'meat_menu_id' => $combo[1],
                'box_type'     => $bType,
                'jumlah_box'   => $totalBox,
            ];

            if ($oid % 2 == 1 && $oid <= count($allOrders)) {
                $combo2 = $boneMenuCombos[($oid + 3) % count($boneMenuCombos)];
                $bType2 = ($oid % 2 == 0) ? 'Box Premium' : 'Bento Pack';
                $orderDetails[] = [
                    'order_id'    => $oid,
                    'bone_menu_id' => $combo2[0],
                    'meat_menu_id' => $combo2[1],
                    'box_type'     => $bType2,
                    'jumlah_box'   => $totalBox,
                ];
            }
        }

        $this->db->table('order_details')->insertBatch($orderDetails);
        echo "  ✓ Order details inserted (" . count($orderDetails) . " details)\n";

        // ===================== SCHEDULES (EDF) =====================
        $pendingOrders = $this->db->table('orders')
            ->whereIn('status', ['Pending', 'Scheduled'])
            ->orderBy('slaughter_date', 'ASC')
            ->orderBy('id_order', 'ASC')
            ->get()
            ->getResultArray();

        $schedules = [];
        $priority = 1;
        foreach ($pendingOrders as $order) {
            $isToday = ($order['slaughter_date'] == $today);
            $schedules[] = [
                'order_id'       => $order['id_order'],
                'slaughter_date' => $order['slaughter_date'],
                'priority'       => $priority++,
                'status'         => $isToday ? 'In Progress' : 'Scheduled',
            ];
        }
        if (!empty($schedules)) {
            $this->db->table('schedules')->insertBatch($schedules);
            echo "  ✓ Schedules inserted (" . count($schedules) . " schedules)\n";
        }

        // ================================================================
        //  NOTIFICATIONS
        // ================================================================
        $notifications = [];
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $allOrdersData = $this->db->table('orders')
            ->join('customers', 'customers.id_customer = orders.customer_id')
            ->join('packages', 'packages.id_package = orders.package_id')
            ->select('orders.*, customers.name as customer_name, customers.child_name, customers.gender as child_gender, customers.phone, customers.birth_date, customers.address, packages.name as package_name')
            ->get()
            ->getResultArray();

        foreach ($allOrdersData as $order) {
            $hargaFormat = number_format($order['total_price'], 0, ',', '.');
            $jmlAnakText = $order['jumlah_anak'] . ' ekor';

            $notifications[] = [
                'order_id' => $order['id_order'],
                'type'     => 'order_confirmation',
                'message'  => "✅ Konfirmasi Pesanan #{$order['id_order']}\n👤 {$order['customer_name']}\n📅 Penyembelihan: {$order['slaughter_date']}\n🐑 {$order['animal_type']} {$order['animal_gender']} - {$order['package_name']}\n💰 Rp {$hargaFormat}\nStatus: Terjadwal",
            ];

            if ($order['slaughter_date'] == $tomorrow) {
                $notifications[] = [
                    'order_id' => $order['id_order'],
                    'type'     => '24h_reminder',
                    'message'  => "🔔 *Pengingat 24 Jam!*\nHewan akan dipotong besok!\n📌 Order: #{$order['id_order']}\n👤 Pemesan: {$order['customer_name']}\n👶 Anak: {$order['child_name']}\n📅 Tanggal: {$order['slaughter_date']}\n⏰ Waktu: {$order['slaughter_time']}",
                ];
            }
        }

        // DAILY RECAP untuk hari ini
        $todayOrders = array_filter($allOrdersData, function ($o) use ($today) {
            return $o['slaughter_date'] == $today;
        });

        $totalBox = 0;
        $totalPrice = 0;
        $dombaCount = 0;
        $kambingCount = 0;
        $orderList = "";

        foreach ($todayOrders as $order) {
            $details = $this->db->table('order_details')
                ->where('order_id', $order['id_order'])
                ->get()
                ->getResultArray();
            $boxCount = 0;
            foreach ($details as $d) {
                $boxCount += (int)$d['jumlah_box'];
            }
            $totalBox += $boxCount;
            $totalPrice += $order['total_price'];
            if ($order['animal_type'] == 'Domba') $dombaCount++;
            else $kambingCount++;
            $hargaOrder = number_format($order['total_price'], 0, ',', '.');
            $orderList .= "  #{$order['id_order']} | {$order['child_name']} | {$order['animal_type']} | {$order['package_name']} | Rp {$hargaOrder}\n";
        }

        $stockInfo = '';
        $stocksList = $this->db->table('stocks')->get()->getResultArray();
        $stockWarning = false;
        foreach ($stocksList as $s) {
            $status = $s['quantity'] <= $s['min_threshold'] ? '⚠️ STOK MENIPIS' : '✅ OK';
            $stockInfo .= "• {$s['item_name']}: {$s['quantity']} {$s['unit']} {$status}\n";
            if ($s['quantity'] <= $s['min_threshold']) $stockWarning = true;
        }

        $totalPendapatan = number_format($totalPrice, 0, ',', '.');
        $totalHariIni = count($todayOrders);

        $recapMsg = "📊 *REKAP HARIAN - " . strtoupper(date('d F Y')) . "*\n"
                  . "━━━━━━━━━━━━━━━━━━━━\n"
                  . "📦 Total Pesanan: {$totalHariIni}\n"
                  . "📦 Total Box: {$totalBox}\n"
                  . "🐏 Domba: {$dombaCount}\n"
                  . "🐐 Kambing: {$kambingCount}\n"
                  . "💰 Total Pendapatan: Rp {$totalPendapatan}\n"
                  . "━━━━━━━━━━━━━━━━━━━━\n"
                  . "📋 DAFTAR PESANAN:\n"
                  . ($orderList ?: "  - Tidak ada pesanan hari ini\n")
                  . "━━━━━━━━━━━━━━━━━━━━\n"
                  . "📦 STOK TERKINI:\n"
                  . $stockInfo
                  . ($stockWarning ? "⚠️ Ada stok yang menipis! Segera lakukan pengadaan.\n" : "")
                  . "━━━━━━━━━━━━━━━━━━━━\n"
                  . "⚡ Semoga lancar hari ini! 🙏";

        $notifications[] = [
            'order_id' => null,
            'type'     => 'daily_recap',
            'message'  => $recapMsg,
        ];

        $this->db->table('notifications')->insertBatch($notifications);
        echo "  ✓ Notifications inserted (" . count($notifications) . " notifications)\n";

        // ===================== SETTINGS =====================
        $settings = [
            ['setting_key' => 'shop_name',           'setting_value' => 'Aqiqah Cahaya Berkah'],
            ['setting_key' => 'shop_address',        'setting_value' => 'Jl. Raya Bogor KM 30, Cibinong, Bogor'],
            ['setting_key' => 'shop_phone',          'setting_value' => '021-87965432'],
            ['setting_key' => 'shop_whatsapp',       'setting_value' => '6281234567890'],
            ['setting_key' => 'telegram_bot_token',  'setting_value' => ''],
            ['setting_key' => 'telegram_chat_id',    'setting_value' => ''],
            ['setting_key' => 'admin_email',         'setting_value' => 'admin@aqiqah-berkah.com'],
            ['setting_key' => 'pricing_note',        'setting_value' => 'Harga termasuk biaya penyembelihan, pengolahan, dan pengemasan'],
            ['setting_key' => 'last_scheduler_run',  'setting_value' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('settings')->insertBatch($settings);
        echo "  ✓ Settings inserted (9 settings)\n";

        echo "\n✅ Seeder selesai! Data dummy selama 14 hari berhasil dimasukkan.\n";
        echo "   Login Admin: admin / admin123\n";
        echo "   Login RPH:   rph   / rph123\n";
        echo "   Login Dapur: dapur / dapur123\n";
        echo "   Total: 3 users, " . count($customers) . " customers, " . count($allOrders) . " orders, "
             . count($orderDetails) . " order details, " . count($schedules) . " schedules\n";
    }
}