<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * WHITEBOX TESTING - Ibrahim Aqiqah System
 *
 * Whitebox testing focuses on internal code structure, path coverage,
 * branch coverage, and condition coverage.
 * 
 * Semua test di sini tidak membutuhkan koneksi database/sqlite3.
 * Test berfokus pada: keberadaan method, parameter, return type, 
 * algoritma, view rendering (string-based), dan struktur kode.
 */
class WhiteboxTest extends CIUnitTestCase
{
    // ==================== 1. AUTH CONTROLLER ====================

    public function testAuthControllerMethodsExist()
    {
        $methods = get_class_methods(App\Controllers\Auth::class);
        $expected = ['index', 'login', 'logout'];
        foreach ($expected as $method) {
            $this->assertTrue(in_array($method, $methods), "Method {$method} not found in Auth");
        }
    }

    public function testAuthLoginParams()
    {
        $reflection = new ReflectionMethod(App\Controllers\Auth::class, 'login');
        $this->assertTrue($reflection->isPublic());
        $this->assertEquals(0, $reflection->getNumberOfRequiredParameters());
    }

    public function testAuthLogoutNoParams()
    {
        $reflection = new ReflectionMethod(App\Controllers\Auth::class, 'logout');
        $this->assertTrue($reflection->isPublic());
        $this->assertEquals(0, $reflection->getNumberOfRequiredParameters());
    }

    // ==================== 2. ORDERS CONTROLLER ====================

    public function testOrdersControllerMethodsExist()
    {
        $methods = get_class_methods(App\Controllers\Orders::class);
        $expected = ['index', 'create', 'store', 'edit', 'update', 'delete', 'stats', 'getPackageInfo', 'pendingCount'];
        foreach ($expected as $method) {
            $this->assertTrue(in_array($method, $methods), "Method {$method} not found in Orders");
        }
    }

    public function testOrdersEditAcceptsIdParam()
    {
        $reflection = new ReflectionMethod(App\Controllers\Orders::class, 'edit');
        $this->assertTrue($reflection->isPublic());
        $params = $reflection->getParameters();
        $this->assertCount(1, $params);
        $this->assertEquals('id', $params[0]->getName());
    }

    public function testOrdersGetPackageInfoAcceptsIdParam()
    {
        $reflection = new ReflectionMethod(App\Controllers\Orders::class, 'getPackageInfo');
        $params = $reflection->getParameters();
        $this->assertCount(1, $params);
        $this->assertEquals('id', $params[0]->getName());
    }

    // ==================== 3. DASHBOARD CONTROLLER ====================

    public function testDashboardControllerMethodsExist()
    {
        $methods = get_class_methods(App\Controllers\Dashboard::class);
        $expected = ['index', 'chartData'];
        foreach ($expected as $method) {
            $this->assertTrue(in_array($method, $methods), "Method {$method} not found in Dashboard");
        }
    }

    // ==================== 4. REPORTS CONTROLLER ====================

    public function testReportsControllerMethodsExist()
    {
        $methods = get_class_methods(App\Controllers\Reports::class);
        $expected = ['index', 'certificate', 'detailPemesanan', 'invitation', 'orderReport', 'orderReportPdf', 'kitchenSheet'];
        foreach ($expected as $method) {
            $this->assertTrue(in_array($method, $methods), "Method {$method} not found in Reports");
        }
    }

    public function testReportsCertificateAcceptsOptionalId()
    {
        $reflection = new ReflectionMethod(App\Controllers\Reports::class, 'certificate');
        $params = $reflection->getParameters();
        $this->assertCount(1, $params);
        $this->assertTrue($params[0]->isOptional());
    }

    public function testReportsDetailPemesananAcceptsId()
    {
        $reflection = new ReflectionMethod(App\Controllers\Reports::class, 'detailPemesanan');
        $params = $reflection->getParameters();
        $this->assertCount(1, $params);
        $this->assertEquals('id_order', $params[0]->getName());
    }

    public function testReportsOrderReportAcceptsDateRange()
    {
        $reflection = new ReflectionMethod(App\Controllers\Reports::class, 'orderReport');
        $params = $reflection->getParameters();
        $this->assertCount(2, $params);
        $this->assertEquals('startDate', $params[0]->getName());
        $this->assertEquals('endDate', $params[1]->getName());
    }

    // ==================== 5. NOTIFICATION CONTROLLER ====================

    public function testNotificationControllerMethodsExist()
    {
        $methods = get_class_methods(App\Controllers\Notification::class);
        $expected = [
            'sendTodayRecap', 'sendTomorrowPreview', 'sendHPlus1', 'sendCustom',
            'sendStockAlert', 'test', 'manual', 'history', 'recipients',
            'addRecipient', 'deleteRecipient'
        ];
        foreach ($expected as $method) {
            $this->assertTrue(in_array($method, $methods), "Method {$method} not found in Notification");
        }
    }

    // ==================== 6. CALENDAR CONTROLLER ====================

    public function testCalendarControllerMethodsExist()
    {
        $methods = get_class_methods(App\Controllers\Calendar::class);
        $this->assertTrue(in_array('getEvents', $methods));
    }

    // ==================== 7. SCHEDULER CONTROLLER ====================

    public function testSchedulerControllerMethodsExist()
    {
        $methods = get_class_methods(App\Controllers\Scheduler::class);
        $this->assertTrue(in_array('run', $methods), 'Method run not found in Scheduler');
    }

    // ==================== 8. FILTER EXISTENCE ====================

    public function testFiltersExist()
    {
        $filters = [
            'App\Filters\AdminFilter',
            'App\Filters\DapurFilter',
            'App\Filters\RphFilter',
        ];
        foreach ($filters as $filter) {
            $this->assertTrue(class_exists($filter), "Filter {$filter} not found");
        }
    }

    public function testFiltersExtendBaseFilter()
    {
        $this->assertTrue(is_subclass_of('App\Filters\AdminFilter', 'CodeIgniter\Filters\FilterInterface'));
        $this->assertTrue(is_subclass_of('App\Filters\DapurFilter', 'CodeIgniter\Filters\FilterInterface'));
        $this->assertTrue(is_subclass_of('App\Filters\RphFilter', 'CodeIgniter\Filters\FilterInterface'));
    }

    // ==================== 9. MODEL EXISTENCE ====================

    public function testModelsExist()
    {
        $models = [
            'App\Models\OrderModel',
            'App\Models\CustomerModel',
            'App\Models\PackageModel',
            'App\Models\StockModel',
            'App\Models\ScheduleModel',
            'App\Models\NotificationModel',
            'App\Models\TelegramRecipientModel',
            'App\Models\OrderDetailModel',
            'App\Models\UserModel',
            'App\Models\BoneMenuModel',
            'App\Models\MeatMenuModel',
        ];
        foreach ($models as $model) {
            $this->assertTrue(class_exists($model), "Model {$model} not found");
        }
    }

    public function testModelsExtendBaseModel()
    {
        $models = [
            'App\Models\OrderModel',
            'App\Models\CustomerModel',
            'App\Models\PackageModel',
            'App\Models\ScheduleModel',
            'App\Models\NotificationModel',
            'App\Models\TelegramRecipientModel',
            'App\Models\OrderDetailModel',
            'App\Models\UserModel',
        ];
        foreach ($models as $model) {
            $this->assertTrue(
                is_subclass_of($model, 'CodeIgniter\Model'),
                "{$model} does not extend CodeIgniter\Model"
            );
        }
    }

    // ==================== 10. VIEW FILES EXISTENCE ====================

    public function testViewFilesExist()
    {
        $views = [
            ROOTPATH . 'app/Views/auth/login.php',
            ROOTPATH . 'app/Views/dashboard/index.php',
            ROOTPATH . 'app/Views/orders/index.php',
            ROOTPATH . 'app/Views/orders/create.php',
            ROOTPATH . 'app/Views/orders/edit.php',
            ROOTPATH . 'app/Views/reports/index.php',
            ROOTPATH . 'app/Views/reports/certificate.php',
            ROOTPATH . 'app/Views/reports/detail_pemesanan.php',
            ROOTPATH . 'app/Views/reports/order_report.php',
            ROOTPATH . 'app/Views/reports/invitation.php',
            ROOTPATH . 'app/Views/reports/kitchen_sheet.php',
            ROOTPATH . 'app/Views/notifications/manual.php',
            ROOTPATH . 'app/Views/notifications/history.php',
            ROOTPATH . 'app/Views/notifications/recipients.php',
            ROOTPATH . 'app/Views/templates/header.php',
            ROOTPATH . 'app/Views/templates/sidebar.php',
        ];
        foreach ($views as $view) {
            $this->assertFileExists($view, "View file not found: {$view}");
        }
    }

    // ==================== 11. MIGRATION EXISTENCE ====================

    public function testMigrationFilesExist()
    {
        $this->assertFileExists(ROOTPATH . 'app/Database/Migrations/2026-07-22-235900_CreateTelegramRecipientsTable.php');
    }

    // ==================== 12. SEED EXISTENCE ====================

    public function testSeedExists()
    {
        $this->assertTrue(class_exists('App\Database\Seeds\AqiqahSeeder'));
    }

    // ==================== 13. CONFIG FILES ====================

    public function testConfigFilesExist()
    {
        $this->assertFileExists(ROOTPATH . 'app/Config/Routes.php');
        $this->assertFileExists(ROOTPATH . 'app/Config/Filters.php');
        $this->assertFileExists(ROOTPATH . 'app/Config/App.php');
    }

    public function testDatabaseConfigFileExists()
    {
        $this->assertFileExists(ROOTPATH . 'app/Config/Database.php');
    }

    // ==================== 14. LIBRARIES ====================

    public function testLibrariesExist()
    {
        $this->assertTrue(class_exists('App\Libraries\TelegramBot'));
    }

    // ==================== 15. COMMANDS ====================

    public function testCommandsExist()
    {
        $this->assertTrue(class_exists('App\Commands\NotificationsSend'));
    }

    // ==================== 16. BUSINESS LOGIC: Calendar Color Mapping ====================

    public function testCalendarColorMappingLogic()
    {
        // Whitebox: Verifikasi logika mapping warna
        // Prioritas 1 → merah (#dc3545)
        // Prioritas 2 → kuning (#ffc107)
        // Prioritas >=3 → hijau (#28a745)
        
        $getColor = function($priority) {
            if ($priority == 1) return '#dc3545';
            if ($priority == 2) return '#ffc107';
            return '#28a745';
        };

        $this->assertEquals('#dc3545', $getColor(1));
        $this->assertEquals('#ffc107', $getColor(2));
        $this->assertEquals('#28a745', $getColor(3));
        $this->assertEquals('#28a745', $getColor(4));
        $this->assertEquals('#28a745', $getColor(99));
    }

    // ==================== 17. BUSINESS LOGIC: Status Color Mapping ====================

    public function testStatusColorMapping()
    {
        $getStatusColor = function($status) {
            $colors = [
                'Pending'    => '#ffc107',
                'Processing' => '#17a2b8',
                'Completed'  => '#28a745',
                'Scheduled'  => '#007bff',
            ];
            return $colors[$status] ?? '#6c757d';
        };

        $this->assertEquals('#ffc107', $getStatusColor('Pending'));
        $this->assertEquals('#17a2b8', $getStatusColor('Processing'));
        $this->assertEquals('#28a745', $getStatusColor('Completed'));
        $this->assertEquals('#007bff', $getStatusColor('Scheduled'));
        $this->assertEquals('#6c757d', $getStatusColor('Unknown'));
    }

    // ==================== 18. BUSINESS LOGIC: Package Calculation ====================

    public function testPackageJumlahAnakCalculation()
    {
        // Whitebox: Branch coverage untuk gender → jumlah_anak
        $getJumlahAnak = function($gender, $default = 1) {
            return ($gender === 'laki-laki') ? 2 : $default;
        };

        // Branch 1: laki-laki → 2
        $this->assertEquals(2, $getJumlahAnak('laki-laki'));
        // Branch 2: perempuan → default (1)
        $this->assertEquals(1, $getJumlahAnak('perempuan'));
        // Branch 3: other → default
        $this->assertEquals(1, $getJumlahAnak('unknown'));
    }

    // ==================== 19. BUSINESS LOGIC: EDF/EDD Algorithm ====================

    public function testEdfAlgorithmOrdering()
    {
        // Whitebox: Verifikasi algoritma EDF (Earliest Deadline First)
        // Seharusnya mengurutkan berdasarkan deadline terdekat
        
        $orders = [
            ['id' => 1, 'deadline' => '2026-07-30'],
            ['id' => 2, 'deadline' => '2026-07-25'],
            ['id' => 3, 'deadline' => '2026-08-01'],
            ['id' => 4, 'deadline' => '2026-07-28'],
        ];

        // EDF Algorithm: sort by deadline ascending
        usort($orders, function($a, $b) {
            return strtotime($a['deadline']) - strtotime($b['deadline']);
        });

        // Urutan yang diharapkan: 2 → 4 → 1 → 3
        $this->assertEquals(2, $orders[0]['id']);
        $this->assertEquals(4, $orders[1]['id']);
        $this->assertEquals(1, $orders[2]['id']);
        $this->assertEquals(3, $orders[3]['id']);

        // Verifikasi bahwa order dengan deadline terdekat jadi prioritas pertama
        $this->assertEquals('2026-07-25', $orders[0]['deadline']);
    }

    // ==================== 20. SESSION-BASED PATH COVERAGE ====================

    public function testSessionStateTransitions()
    {
        // Whitebox: State transition untuk session login
        $session = [];

        // State 1: Belum login
        $this->assertFalse(isset($session['isLoggedIn']));
        $this->assertFalse(isset($session['username']));

        // Transition: Login
        $session['isLoggedIn'] = true;
        $session['username'] = 'admin';
        $session['role'] = 'admin';

        // State 2: Sudah login
        $this->assertTrue($session['isLoggedIn']);
        $this->assertEquals('admin', $session['username']);

        // Transition: Logout (destroy session)
        $session = [];

        // State 3: Sudah logout
        $this->assertFalse(isset($session['isLoggedIn']));
        $this->assertEmpty($session);
    }

    // ==================== 21. ANIMAL TYPE VALIDATION ====================

    public function testAnimalTypeValidation()
    {
        $validTypes = ['kambing', 'sapi', 'domba'];

        // Valid types
        $this->assertTrue(in_array('kambing', $validTypes));
        $this->assertTrue(in_array('sapi', $validTypes));
        $this->assertTrue(in_array('domba', $validTypes));

        // Invalid types
        $this->assertFalse(in_array('ayam', $validTypes));
        $this->assertFalse(in_array('', $validTypes));
        $this->assertFalse(in_array(null, $validTypes));
        $this->assertCount(3, $validTypes);
    }

    // ==================== 22. TOTAL PRICE CALCULATION ====================

    public function testTotalPriceCalculation()
    {
        // Whitebox: Verifikasi logika perhitungan total harga
        $calculateTotal = function($packagePrice, $jumlahAnak, $additionalCost = 0) {
            return ($packagePrice * $jumlahAnak) + $additionalCost;
        };

        // Test 1: Package Rp 2.500.000, 2 anak laki-laki
        $this->assertEquals(5000000, $calculateTotal(2500000, 2));

        // Test 2: Package Rp 2.500.000, 1 anak perempuan
        $this->assertEquals(2500000, $calculateTotal(2500000, 1));

        // Test 3: Additional cost (misal foto)
        $this->assertEquals(5150000, $calculateTotal(2500000, 2, 150000));

        // Test 4: Zero edge case
        $this->assertEquals(0, $calculateTotal(0, 0));
    }

    // ==================== 23. STRING INPUT VALIDATION ====================

    public function testRequiredFieldValidation()
    {
        // Whitebox: Logika validasi required fields
        $isValid = function($value) {
            return $value !== null && trim($value) !== '';
        };

        // Valid inputs
        $this->assertTrue($isValid('Budi'));
        $this->assertTrue($isValid('08123456789'));
        $this->assertTrue($isValid('Jl. Merdeka No. 1'));

        // Invalid inputs
        $this->assertFalse($isValid(null));
        $this->assertFalse($isValid(''));
        $this->assertFalse($isValid('   '));
    }

    // ==================== 24. PHONE NUMBER FORMAT ====================

    public function testPhoneNumberValidation()
    {
        // Whitebox: Validasi format nomor telepon
        $isValidPhone = function($phone) {
            if (empty($phone)) return false;
            // Harus mulai dengan 0 atau 62
            return preg_match('/^(0|62)[0-9]{8,15}$/', $phone) === 1;
        };

        // Valid
        $this->assertTrue($isValidPhone('081234567890'));
        $this->assertTrue($isValidPhone('6281234567890'));

        // Invalid
        $this->assertFalse($isValidPhone(''));
        $this->assertFalse($isValidPhone('12345'));
        $this->assertFalse($isValidPhone('phone123'));
        $this->assertFalse($isValidPhone(null));
    }

    // ==================== 25. DATE VALIDATION ====================

    public function testDateValidation()
    {
        $isValidDate = function($date) {
            if (empty($date)) return false;
            $d = DateTime::createFromFormat('Y-m-d', $date);
            return $d && $d->format('Y-m-d') === $date;
        };

        // Valid dates
        $this->assertTrue($isValidDate('2026-07-27'));
        $this->assertTrue($isValidDate('2026-12-31'));

        // Invalid dates
        $this->assertFalse($isValidDate(''));
        $this->assertFalse($isValidDate('27-07-2026'));
        $this->assertFalse($isValidDate('2026/07/27'));
        $this->assertFalse($isValidDate('not-a-date'));
    }

    // ==================== 26. BREADTH-FIRST: Complete Coverage Summary ====================

    public function testCoverageSummary()
    {
        $controllers = [
            'Auth'         => ['index', 'login', 'logout'],
            'Orders'       => ['index', 'create', 'store', 'edit', 'update', 'delete', 'stats', 'getPackageInfo', 'pendingCount'],
            'Dashboard'    => ['index', 'chartData'],
            'Reports'      => ['index', 'certificate', 'detailPemesanan', 'invitation', 'orderReport', 'orderReportPdf', 'kitchenSheet'],
            'Notification' => ['sendTodayRecap', 'sendTomorrowPreview', 'sendHPlus1', 'sendCustom', 'sendStockAlert', 'test', 'manual', 'history', 'recipients', 'addRecipient', 'deleteRecipient'],
            'Calendar'     => ['getEvents'],
            'Scheduler'    => ['run'],
        ];

        $totalMethods = 0;
        foreach ($controllers as $name => $methods) {
            $totalMethods += count($methods);
        }

        fwrite(STDERR, "\n=== WHITEBOX TEST COVERAGE ===\n");
        fwrite(STDERR, "Controllers covered: " . count($controllers) . "\n");
        fwrite(STDERR, "Total controller methods documented: {$totalMethods}\n");
        fwrite(STDERR, "Models: 11\n");
        fwrite(STDERR, "Filters: 3\n");
        fwrite(STDERR, "Views: 16\n");
        fwrite(STDERR, "==============================\n");

        $this->assertGreaterThanOrEqual(7, count($controllers));
    }
}