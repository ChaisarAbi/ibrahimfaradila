<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AqiqahSeeder extends Seeder
{
    public function run()
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
            [
                'username' => 'admin',
                'password' => hash('sha256', 'admin123'),
                'fullname' => 'Administrator',
                'role'     => 'admin'
            ],
            [
                'username' => 'staff1',
                'password' => hash('sha256', 'staff123'),
                'fullname' => 'Ahmad Fauzi',
                'role'     => 'staff'
            ],
            [
                'username' => 'staff2',
                'password' => hash('sha256', 'staff123'),
                'fullname' => 'Siti Rahmah',
                'role'     => 'staff'
            ],
            [
                'username' => 'dapur',
                'password' => hash('sha256', 'dapur123'),
                'fullname' => 'Tim Dapur',
                'role'     => 'kitchen'
            ]
        ];
        $this->db->table('users')->insertBatch($users);
        echo "  ✓ Users inserted (4 users)\n";

        // ===================== BONE MENUS =====================
        $boneMenus = [
            ['name' => 'Gulai'],
            ['name' => 'Sop'],
            ['name' => 'Tongseng'],
            ['name' => 'Rendang'],
        ];
        $this->db->table('bone_menus')->insertBatch($boneMenus);
        echo "  ✓ Bone menus inserted (4 menus)\n";

        // ===================== MEAT MENUS =====================
        $meatMenus = [
            ['name' => 'Sate tanpa tusuk'],
            ['name' => 'Teriyaki'],
            ['name' => 'Lada Hitam'],
            ['name' => 'Krengseng'],
            ['name' => 'Semur'],
            ['name' => 'Sambal Goreng'],
        ];
        $this->db->table('meat_menus')->insertBatch($meatMenus);
        echo "  ✓ Meat menus inserted (6 menus)\n";

        // ===================== PACKAGES =====================
        $packages = [
            ['name' => 'Paket Berkah A',    'weight_type' => 'A', 'min_weight' => 16, 'max_weight' => 17, 'box_count' => 50,  'price' => 2500000, 'is_special' => 0],
            ['name' => 'Paket Special A',    'weight_type' => 'A', 'min_weight' => 16, 'max_weight' => 17, 'box_count' => 50,  'price' => 3000000, 'is_special' => 1],
            ['name' => 'Paket Berkah B',    'weight_type' => 'B', 'min_weight' => 17, 'max_weight' => 18, 'box_count' => 60,  'price' => 2800000, 'is_special' => 0],
            ['name' => 'Paket Special B',    'weight_type' => 'B', 'min_weight' => 17, 'max_weight' => 18, 'box_count' => 60,  'price' => 3400000, 'is_special' => 1],
            ['name' => 'Paket Berkah C',    'weight_type' => 'C', 'min_weight' => 18, 'max_weight' => 20, 'box_count' => 80,  'price' => 3400000, 'is_special' => 0],
            ['name' => 'Paket Special C',    'weight_type' => 'C', 'min_weight' => 18, 'max_weight' => 20, 'box_count' => 80,  'price' => 4200000, 'is_special' => 1],
            ['name' => 'Paket Berkah D',    'weight_type' => 'D', 'min_weight' => 21, 'max_weight' => 23, 'box_count' => 100, 'price' => 3900000, 'is_special' => 0],
            ['name' => 'Paket Special D',    'weight_type' => 'D', 'min_weight' => 21, 'max_weight' => 23, 'box_count' => 100, 'price' => 4900000, 'is_special' => 1],
        ];
        $this->db->table('packages')->insertBatch($packages);
        echo "  ✓ Packages inserted (8 packages)\n";

        // ===================== CUSTOMERS =====================
        $customers = [
            [
                'name'       => 'H. Abdullah Husain',
                'child_name' => 'Muhammad Al-Fatih',
                'gender'     => 'Laki-laki',
                'birth_date' => '2026-01-15',
                'phone'      => '081234567890',
                'address'    => 'Jl. Merdeka No. 10, Jakarta Pusat'
            ],
            [
                'name'       => 'Siti Nurhaliza',
                'child_name' => 'Aisyah Putri',
                'gender'     => 'Perempuan',
                'birth_date' => '2026-02-20',
                'phone'      => '082345678901',
                'address'    => 'Jl. Sudirman No. 25, Jakarta Selatan'
            ],
            [
                'name'       => 'Ahmad Rizki Pratama',
                'child_name' => 'Bilal Arrasyid',
                'gender'     => 'Laki-laki',
                'birth_date' => '2026-03-10',
                'phone'      => '083456789012',
                'address'    => 'Perumahan Citra Indah Blok A5, Bekasi'
            ],
            [
                'name'       => 'Dewi Sartika',
                'child_name' => 'Haura Azkia',
                'gender'     => 'Perempuan',
                'birth_date' => '2026-04-05',
                'phone'      => '085678901234',
                'address'    => 'Jl. Diponegoro No. 88, Bandung'
            ],
            [
                'name'       => 'H. Bambang Supriyadi',
                'child_name' => 'Zaidan Al-Ghifari',
                'gender'     => 'Laki-laki',
                'birth_date' => '2026-01-28',
                'phone'      => '087890123456',
                'address'    => 'Komplek Bumi Asri No. 15, Tangerang'
            ],
            [
                'name'       => 'Fitriani Kusuma Dewi',
                'child_name' => 'Nadia Khairunnisa',
                'gender'     => 'Perempuan',
                'birth_date' => '2026-05-12',
                'phone'      => '089012345678',
                'address'    => 'Jl. Pahlawan No. 7, Depok'
            ],
            [
                'name'       => 'H. Dodi Hermawan',
                'child_name' => 'Rafi Ahmad',
                'gender'     => 'Laki-laki',
                'birth_date' => '2026-03-22',
                'phone'      => '081298765432',
                'address'    => 'Villa Bogor Indah Blok C3, Bogor'
            ],
            [
                'name'       => 'Rina Mahardani',
                'child_name' => 'Salwa Azzahra',
                'gender'     => 'Perempuan',
                'birth_date' => '2026-06-18',
                'phone'      => '082109876543',
                'address'    => 'Jl. Anggrek No. 33, Jakarta Timur'
            ],
            [
                'name'       => 'H. Agus Salim',
                'child_name' => 'Hafidz Al-Quran',
                'gender'     => 'Laki-laki',
                'birth_date' => '2026-04-01',
                'phone'      => '083210987654',
                'address'    => 'Perum Green Garden No. 10, Jakarta Barat'
            ],
            [
                'name'       => 'Mega Wulandari',
                'child_name' => 'Fatimah Az-Zahra',
                'gender'     => 'Perempuan',
                'birth_date' => '2026-07-08',
                'phone'      => '085432109876',
                'address'    => 'Jl. Cendrawasih No. 12, Karawang'
            ],
            [
                'name'       => 'H. Syamsul Bahri',
                'child_name' => 'Ibrahim Malik',
                'gender'     => 'Laki-laki',
                'birth_date' => '2026-05-30',
                'phone'      => '087654321098',
                'address'    => 'Komplek Permata Hijau No. 5, Serang'
            ],
            [
                'name'       => 'Lilis Suryani',
                'child_name' => 'Zahra Ramadhani',
                'gender'     => 'Perempuan',
                'birth_date' => '2026-08-15',
                'phone'      => '089876543210',
                'address'    => 'Jl. Kenanga No. 20, Cilegon'
            ],
        ];
        $this->db->table('customers')->insertBatch($customers);
        echo "  ✓ Customers inserted (12 customers)\n";

        // ===================== STOCKS =====================
        $stocks = [
            ['item_name' => 'Kambing', 'category' => 'hewan',  'quantity' => 25, 'min_threshold' => 5,  'unit' => 'ekor'],
            ['item_name' => 'Domba',   'category' => 'hewan',  'quantity' => 18, 'min_threshold' => 5,  'unit' => 'ekor'],
            ['item_name' => 'Beras',   'category' => 'bahan',  'quantity' => 200, 'min_threshold' => 30, 'unit' => 'kg'],
            ['item_name' => 'Minyak Goreng', 'category' => 'bahan', 'quantity' => 50, 'min_threshold' => 10, 'unit' => 'liter'],
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

        // ===================== ORDERS =====================
        $orders = [
            // 1 - order hari ini (slaughter today) - kambing jantan untuk laki-laki (2 anak)
            ['customer_id' => 1, 'package_id' => 2, 'animal_type' => 'Kambing', 'animal_gender' => 'Jantan', 'jumlah_anak' => 2, 'slaughter_date' => date('Y-m-d'), 'delivery_date' => date('Y-m-d', strtotime('+1 day')), 'slaughter_time' => '07:00:00', 'penyembelihan' => 'Dokumentasi', 'use_photo_card' => 1, 'use_photo_certificate' => 1, 'status' => 'Scheduled', 'total_price' => 3000000],
            // 2 - order besok - domba betina untuk perempuan
            ['customer_id' => 2, 'package_id' => 1, 'animal_type' => 'Domba',   'animal_gender' => 'Betina', 'jumlah_anak' => 1, 'slaughter_date' => date('Y-m-d', strtotime('+1 day')), 'delivery_date' => date('Y-m-d', strtotime('+2 days')), 'slaughter_time' => '08:00:00', 'penyembelihan' => 'Video Call', 'use_photo_card' => 1, 'use_photo_certificate' => 0, 'status' => 'Pending', 'total_price' => 2500000],
            // 3 - order 3 hari lagi - kambing jantan untuk laki-laki (2 anak) - special
            ['customer_id' => 3, 'package_id' => 4, 'animal_type' => 'Kambing', 'animal_gender' => 'Jantan', 'jumlah_anak' => 2, 'slaughter_date' => date('Y-m-d', strtotime('+3 days')), 'delivery_date' => date('Y-m-d', strtotime('+4 days')), 'slaughter_time' => '06:30:00', 'penyembelihan' => 'Visit', 'use_photo_card' => 0, 'use_photo_certificate' => 1, 'status' => 'Pending', 'total_price' => 3400000],
            // 4 - order yang sudah selesai (completed)
            ['customer_id' => 4, 'package_id' => 3, 'animal_type' => 'Domba',   'animal_gender' => 'Betina', 'jumlah_anak' => 1, 'slaughter_date' => date('Y-m-d', strtotime('-5 days')), 'delivery_date' => date('Y-m-d', strtotime('-4 days')), 'slaughter_time' => '09:00:00', 'penyembelihan' => 'Dokumentasi', 'use_photo_card' => 0, 'use_photo_certificate' => 0, 'status' => 'Completed', 'total_price' => 2800000],
            // 5 - order 4 hari lagi - kambing jantan special
            ['customer_id' => 5, 'package_id' => 6, 'animal_type' => 'Kambing', 'animal_gender' => 'Jantan', 'jumlah_anak' => 2, 'slaughter_date' => date('Y-m-d', strtotime('+4 days')), 'delivery_date' => date('Y-m-d', strtotime('+5 days')), 'slaughter_time' => '07:30:00', 'penyembelihan' => 'Video Call', 'use_photo_card' => 1, 'use_photo_certificate' => 1, 'status' => 'Scheduled', 'total_price' => 4200000],
            // 6 - besok juga - domba betina
            ['customer_id' => 6, 'package_id' => 1, 'animal_type' => 'Domba',   'animal_gender' => 'Betina', 'jumlah_anak' => 1, 'slaughter_date' => date('Y-m-d', strtotime('+1 day')), 'delivery_date' => date('Y-m-d', strtotime('+2 days')), 'slaughter_time' => '10:00:00', 'penyembelihan' => 'Dokumentasi', 'use_photo_card' => 1, 'use_photo_certificate' => 0, 'status' => 'Pending', 'total_price' => 2500000],
            // 7 - 2 hari lagi - kambing jantan
            ['customer_id' => 7, 'package_id' => 5, 'animal_type' => 'Kambing', 'animal_gender' => 'Jantan', 'jumlah_anak' => 2, 'slaughter_date' => date('Y-m-d', strtotime('+2 days')), 'delivery_date' => date('Y-m-d', strtotime('+3 days')), 'slaughter_time' => '06:00:00', 'penyembelihan' => 'Visit', 'use_photo_card' => 1, 'use_photo_certificate' => 1, 'status' => 'Pending', 'total_price' => 3400000],
            // 8 - 6 hari lalu (completed) - perempuan
            ['customer_id' => 8, 'package_id' => 3, 'animal_type' => 'Domba',   'animal_gender' => 'Betina', 'jumlah_anak' => 1, 'slaughter_date' => date('Y-m-d', strtotime('-6 days')), 'delivery_date' => date('Y-m-d', strtotime('-5 days')), 'slaughter_time' => '08:30:00', 'penyembelihan' => 'Dokumentasi', 'use_photo_card' => 0, 'use_photo_certificate' => 1, 'status' => 'Completed', 'total_price' => 2800000],
            // 9 - 7 hari lagi - kambing jantan special D
            ['customer_id' => 9, 'package_id' => 8, 'animal_type' => 'Kambing', 'animal_gender' => 'Jantan', 'jumlah_anak' => 2, 'slaughter_date' => date('Y-m-d', strtotime('+7 days')), 'delivery_date' => date('Y-m-d', strtotime('+8 days')), 'slaughter_time' => '07:00:00', 'penyembelihan' => 'Video Call', 'use_photo_card' => 1, 'use_photo_certificate' => 1, 'status' => 'Pending', 'total_price' => 4900000],
            // 10 - 5 hari lagi - domba betina
            ['customer_id' => 10, 'package_id' => 1, 'animal_type' => 'Domba',   'animal_gender' => 'Betina', 'jumlah_anak' => 1, 'slaughter_date' => date('Y-m-d', strtotime('+5 days')), 'delivery_date' => date('Y-m-d', strtotime('+6 days')), 'slaughter_time' => '09:00:00', 'penyembelihan' => 'Dokumentasi', 'use_photo_card' => 0, 'use_photo_certificate' => 0, 'status' => 'Pending', 'total_price' => 2500000],
            // 11 - 3 hari lagi (same as order 3) - domba jantan
            ['customer_id' => 11, 'package_id' => 2, 'animal_type' => 'Kambing', 'animal_gender' => 'Jantan', 'jumlah_anak' => 2, 'slaughter_date' => date('Y-m-d', strtotime('+3 days')), 'delivery_date' => date('Y-m-d', strtotime('+4 days')), 'slaughter_time' => '10:30:00', 'penyembelihan' => 'Visit', 'use_photo_card' => 1, 'use_photo_certificate' => 1, 'status' => 'Pending', 'total_price' => 3000000],
            // 12 - hari ini juga - domba betina
            ['customer_id' => 12, 'package_id' => 7, 'animal_type' => 'Domba',   'animal_gender' => 'Betina', 'jumlah_anak' => 1, 'slaughter_date' => date('Y-m-d'), 'delivery_date' => date('Y-m-d', strtotime('+1 day')), 'slaughter_time' => '08:00:00', 'penyembelihan' => 'Dokumentasi', 'use_photo_card' => 1, 'use_photo_certificate' => 0, 'status' => 'Scheduled', 'total_price' => 3900000],
        ];
        $this->db->table('orders')->insertBatch($orders);
        echo "  ✓ Orders inserted (12 orders)\n";

        // ===================== ORDER DETAILS =====================
        $orderDetails = [
            // Order 1 - Paket Special A (50 box) - Laki-laki => gulai + sate
            ['order_id' => 1, 'bone_menu_id' => 1, 'meat_menu_id' => 1, 'box_type' => 'Box Premium', 'jumlah_box' => 30],
            ['order_id' => 1, 'bone_menu_id' => 2, 'meat_menu_id' => 2, 'box_type' => 'Box Premium', 'jumlah_box' => 20],
            // Order 2 - Paket Berkah A (50 box) - Perempuan => sop + teriyaki
            ['order_id' => 2, 'bone_menu_id' => 2, 'meat_menu_id' => 2, 'box_type' => 'Bento Pack', 'jumlah_box' => 25],
            ['order_id' => 2, 'bone_menu_id' => 3, 'meat_menu_id' => 3, 'box_type' => 'Bento Pack', 'jumlah_box' => 25],
            // Order 3 - Paket Special B (60 box) - Laki-laki
            ['order_id' => 3, 'bone_menu_id' => 1, 'meat_menu_id' => 4, 'box_type' => 'Box Premium', 'jumlah_box' => 30],
            ['order_id' => 3, 'bone_menu_id' => 3, 'meat_menu_id' => 5, 'box_type' => 'Box Premium', 'jumlah_box' => 30],
            // Order 4 - Completed (50 box)
            ['order_id' => 4, 'bone_menu_id' => 1, 'meat_menu_id' => 1, 'box_type' => 'Box Premium', 'jumlah_box' => 30],
            ['order_id' => 4, 'bone_menu_id' => 2, 'meat_menu_id' => 5, 'box_type' => 'Box Premium', 'jumlah_box' => 20],
            // Order 5 - Paket Special C (80 box)
            ['order_id' => 5, 'bone_menu_id' => 4, 'meat_menu_id' => 1, 'box_type' => 'Box Premium', 'jumlah_box' => 40],
            ['order_id' => 5, 'bone_menu_id' => 1, 'meat_menu_id' => 3, 'box_type' => 'Box Premium', 'jumlah_box' => 40],
            // Order 6 - Paket Berkah A
            ['order_id' => 6, 'bone_menu_id' => 2, 'meat_menu_id' => 2, 'box_type' => 'Bento Pack', 'jumlah_box' => 50],
            // Order 7 - Paket Berkah C (80 box)
            ['order_id' => 7, 'bone_menu_id' => 3, 'meat_menu_id' => 6, 'box_type' => 'Bento Pack', 'jumlah_box' => 40],
            ['order_id' => 7, 'bone_menu_id' => 1, 'meat_menu_id' => 4, 'box_type' => 'Bento Pack', 'jumlah_box' => 40],
            // Order 8 - Completed
            ['order_id' => 8, 'bone_menu_id' => 2, 'meat_menu_id' => 1, 'box_type' => 'Box Premium', 'jumlah_box' => 30],
            ['order_id' => 8, 'bone_menu_id' => 4, 'meat_menu_id' => 5, 'box_type' => 'Box Premium', 'jumlah_box' => 20],
            // Order 9 - Paket Special D (100 box)
            ['order_id' => 9, 'bone_menu_id' => 1, 'meat_menu_id' => 1, 'box_type' => 'Box Premium', 'jumlah_box' => 50],
            ['order_id' => 9, 'bone_menu_id' => 2, 'meat_menu_id' => 2, 'box_type' => 'Box Premium', 'jumlah_box' => 50],
            // Order 10
            ['order_id' => 10, 'bone_menu_id' => 3, 'meat_menu_id' => 3, 'box_type' => 'Bento Pack', 'jumlah_box' => 25],
            ['order_id' => 10, 'bone_menu_id' => 2, 'meat_menu_id' => 6, 'box_type' => 'Bento Pack', 'jumlah_box' => 25],
            // Order 11
            ['order_id' => 11, 'bone_menu_id' => 1, 'meat_menu_id' => 4, 'box_type' => 'Box Premium', 'jumlah_box' => 25],
            ['order_id' => 11, 'bone_menu_id' => 4, 'meat_menu_id' => 5, 'box_type' => 'Box Premium', 'jumlah_box' => 25],
            // Order 12 - Paket Berkah D (100 box) - hari ini
            ['order_id' => 12, 'bone_menu_id' => 2, 'meat_menu_id' => 1, 'box_type' => 'Box Premium', 'jumlah_box' => 50],
            ['order_id' => 12, 'bone_menu_id' => 3, 'meat_menu_id' => 3, 'box_type' => 'Box Premium', 'jumlah_box' => 50],
        ];
        $this->db->table('order_details')->insertBatch($orderDetails);
        echo "  ✓ Order details inserted (22 details)\n";

        // ===================== SCHEDULES (EDF) =====================
        // Get all pending/scheduled orders sorted by slaughter_date ASC
        $allOrders = $this->db->table('orders')
            ->whereIn('status', ['Pending', 'Scheduled'])
            ->orderBy('slaughter_date', 'ASC')
            ->get()
            ->getResultArray();

        $schedules = [];
        $priority = 1;
        foreach ($allOrders as $order) {
            $schedules[] = [
                'order_id'       => $order['id_order'],
                'slaughter_date' => $order['slaughter_date'],
                'priority'       => $priority++,
                'status'         => ($order['slaughter_date'] <= date('Y-m-d')) ? 'In Progress' : 'Scheduled'
            ];
        }
        if (!empty($schedules)) {
            $this->db->table('schedules')->insertBatch($schedules);
            echo "  ✓ Schedules inserted (" . count($schedules) . " schedules)\n";
        } else {
            echo "  - No schedules to insert (no pending orders)\n";
        }

        // ===================== NOTIFICATIONS =====================
        $notifications = [
            [
                'order_id' => 1,
                'type'     => 'order_confirmation',
                'message'  => "✅ Konfirmasi Pesanan #1\n👤 H. Abdullah Husain\n📅 Penyembelihan: " . date('Y-m-d') . "\n🐑 Kambing Jantan - Paket Special A\n💰 Rp 3.000.000\nStatus: Terjadwal"
            ],
            [
                'order_id' => 4,
                'type'     => 'order_confirmation',
                'message'  => "✅ Konfirmasi Pesanan #4\n👤 Dewi Sartika\n📅 Penyembelihan: " . date('Y-m-d', strtotime('-5 days')) . "\n🐑 Domba Betina - Paket Berkah B\n💰 Rp 2.800.000\nStatus: Selesai"
            ],
            [
                'order_id' => 1,
                'type'     => '24h_reminder',
                'message'  => "🔔 *Pengingat 24 Jam!*\nHewan akan dipotong hari ini!\n📌 Order: #1\n👤 Pemesan: H. Abdullah Husain\n📅 Tanggal: " . date('Y-m-d') . "\n⏰ Waktu: 07:00"
            ],
            [
                'order_id' => 12,
                'type'     => '24h_reminder',
                'message'  => "🔔 *Pengingat 24 Jam!*\nHewan akan dipotong hari ini!\n📌 Order: #12\n👤 Pemesan: Mega Wulandari\n📅 Tanggal: " . date('Y-m-d') . "\n⏰ Waktu: 08:00"
            ],
            [
                'order_id' => 2,
                'type'     => '24h_reminder',
                'message'  => "🔔 *Pengingat 24 Jam!*\nHewan akan dipotong besok!\n📌 Order: #2\n👤 Pemesan: Siti Nurhaliza\n📅 Tanggal: " . date('Y-m-d', strtotime('+1 day')) . "\n⏰ Waktu: 08:00"
            ],
            [
                'order_id' => 6,
                'type'     => '24h_reminder',
                'message'  => "🔔 *Pengingat 24 Jam!*\nHewan akan dipotong besok!\n📌 Order: #6\n👤 Pemesan: Fitriani Kusuma Dewi\n📅 Tanggal: " . date('Y-m-d', strtotime('+1 day')) . "\n⏰ Waktu: 10:00"
            ],
            [
                'order_id' => null,
                'type'     => 'daily_recap',
                'message'  => "📊 *Rekap Harian - " . date('d/m/Y') . "*\n\n📦 Total Order: 12\n✅ Selesai: 2\n🔄 Diproses: 3\n⏳ Pending: 7\n\n💰 Pendapatan Bulan Ini: Rp 39.500.000\n🐑 Stok Kambing: 25 ekor\n🐏 Stok Domba: 18 ekor"
            ],
            [
                'order_id' => 5,
                'type'     => 'order_confirmation',
                'message'  => "✅ Konfirmasi Pesanan #5\n👤 H. Bambang Supriyadi\n📅 Penyembelihan: " . date('Y-m-d', strtotime('+4 days')) . "\n🐑 Kambing Jantan - Paket Special C\n💰 Rp 4.200.000\nStatus: Terjadwal"
            ],
            [
                'order_id' => null,
                'type'     => 'stock_alert',
                'message'  => "⚠️ *Peringatan Stok Menipis!*\n• Bumbu Sop: 25 pack\n• Tusuk Sate: 500 batang\n• Bento Pack: 200 buah\n\nSegera lakukan restock!"
            ],
            [
                'order_id' => null,
                'type'     => 'daily_recap',
                'message'  => "📊 *Rekap Harian - " . date('d/m/Y', strtotime('-1 day')) . "*\n\n📦 Total Order: 10\n✅ Selesai: 1\n🔄 Diproses: 2\n⏳ Pending: 7\n\n💰 Pendapatan: Rp 2.800.000"
            ]
        ];
        $this->db->table('notifications')->insertBatch($notifications);
        echo "  ✓ Notifications inserted (" . count($notifications) . " notifications)\n";

        // ===================== SETTINGS =====================
        $settings = [
            ['setting_key' => 'shop_name',        'setting_value' => 'Aqiqah Cahaya Berkah'],
            ['setting_key' => 'shop_address',     'setting_value' => 'Jl. Raya Bogor KM 30, Cibinong, Bogor'],
            ['setting_key' => 'shop_phone',       'setting_value' => '021-87965432'],
            ['setting_key' => 'shop_whatsapp',    'setting_value' => '6281234567890'],
            ['setting_key' => 'telegram_bot_token', 'setting_value' => ''],
            ['setting_key' => 'telegram_chat_id',   'setting_value' => ''],
            ['setting_key' => 'admin_email',      'setting_value' => 'admin@aqiqah-berkah.com'],
            ['setting_key' => 'pricing_note',     'setting_value' => 'Harga termasuk biaya penyembelihan, pengolahan, dan pengemasan'],
            ['setting_key' => 'last_scheduler_run', 'setting_value' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('settings')->insertBatch($settings);
        echo "  ✓ Settings inserted (9 settings)\n";

        echo "\n✅ Seeder selesai! Semua data dummy berhasil dimasukkan.\n";
        echo "   Login Admin: admin / admin123\n";
        echo "   Login Staff: staff1 / staff123\n";
        echo "   Login Dapur: dapur / dapur123\n";
        echo "   Total: 4 users, 12 customers, 12 orders, 22 order details, " . count($schedules) . " schedules\n";
    }
}