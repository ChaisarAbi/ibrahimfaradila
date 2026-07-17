<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
            <h4 class="page-title mb-0"><i class="fas fa-tachometer-alt"></i>Dashboard</h4>
            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?= date('l, d F Y') ?></small>
        </div>
        <div>
            <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
        </div>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success animate-slide-in">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <!-- Stat Cards Modern -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-1">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success">
                        <i class="fas fa-chart-line me-1"></i>+12%
                    </span>
                </div>
                <div>
                    <div class="stat-label text-muted">Total Pesanan</div>
                    <h4 class="mb-0"><?= $total_orders ?></h4>
                    <div class="stat-footer">Semua pesanan terdaftar</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-2">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <span class="badge bg-info bg-opacity-10 text-info">
                        <i class="fas fa-clock me-1"></i>Hari Ini
                    </span>
                </div>
                <div>
                    <div class="stat-label text-muted">Pesanan Hari Ini</div>
                    <h4 class="mb-0"><?= $today_orders ?></h4>
                    <div class="stat-footer">Jadwal pemotongan hari ini</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-tag"></i>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-arrow-up me-1"></i>Stok
                    </span>
                </div>
                <div>
                    <div class="stat-label text-muted">Stok Hewan</div>
                    <h4 class="mb-0"><?= $total_stock ?></h4>
                    <div class="stat-footer">Kambing & Domba tersedia</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-calendar me-1"></i>Bulan Ini
                    </span>
                </div>
                <div>
                    <div class="stat-label text-muted">Pendapatan Bulan Ini</div>
                    <h4 class="mb-0">Rp <?= number_format($monthly_revenue, 0, ',', '.') ?></h4>
                    <div class="stat-footer">Total pendapatan kotor</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Calendar Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="card animate-fade-up stagger-1 h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-calendar-alt me-2"></i>Kalender Penjadwalan</h5>
                    <div>
                        <span class="badge bg-danger me-1">Priority 1</span>
                        <span class="badge bg-warning me-1">Priority 2</span>
                        <span class="badge bg-success">Priority 3+</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card animate-fade-up stagger-2 h-100">
                <div class="card-header">
                    <h5><i class="fas fa-chart-pie me-2"></i>Statistik Pesanan</h5>
                </div>
                <div class="card-body" style="padding-bottom: 8px;">
                    <div class="chart-container" style="position: relative; width: 100%; height: 220px; margin: 0 auto;">
                        <canvas id="orderChart"></canvas>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-circle text-warning me-2" style="font-size:0.6rem;"></i>Pending</span>
                        <span class="fw-bold" id="pendingCount">0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-circle text-info me-2" style="font-size:0.6rem;"></i>Processing</span>
                        <span class="fw-bold" id="processingCount">0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-circle text-success me-2" style="font-size:0.6rem;"></i>Completed</span>
                        <span class="fw-bold" id="completedCount">0</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><i class="fas fa-circle text-secondary me-2" style="font-size:0.6rem;"></i>Scheduled</span>
                        <span class="fw-bold" id="scheduledCount">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notification Panel -->
    <div class="card animate-fade-up stagger-3 mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fab fa-telegram me-2 text-primary"></i>Notifikasi Telegram</h5>
            <span class="badge bg-primary bg-opacity-10 text-primary"><i class="fas fa-bolt me-1"></i>Real-time</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="d-flex align-items-center p-3 rounded-3 bg-light">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-size:1.2rem;">
                            <i class="fab fa-telegram-plane"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Status Bot</small>
                            <strong class="text-success"><i class="fas fa-circle me-1" style="font-size:0.5rem;"></i>Terhubung</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="/admin/notifications/send-reminders" class="text-decoration-none" onclick="return confirm('Kirim pengingat 24 jam via Telegram?')">
                        <div class="d-flex align-items-center p-3 rounded-3 bg-light hover-shadow">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-size:1.2rem;">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Kirim Notifikasi</small>
                                <strong>Pengingat 24 Jam</strong>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/notifications/send-recap" class="text-decoration-none" onclick="return confirm('Kirim rekap harian via Telegram?')">
                        <div class="d-flex align-items-center p-3 rounded-3 bg-light hover-shadow">
                            <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-size:1.2rem;">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Kirim Notifikasi</small>
                                <strong>Rekap Harian</strong>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12">
                    <div class="d-flex align-items-center p-2 bg-light rounded-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        <small class="text-muted">
                            Notifikasi otomatis: jalankan <code>php spark notifications:send</code> setiap pukul 06:00 via Task Scheduler.
                            <a href="/admin/notifications/history" class="ms-2"><i class="fas fa-history me-1"></i>Lihat Riwayat</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card animate-fade-up stagger-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-list me-2"></i>Pesanan Terbaru</h5>
            <div>
                <a href="/admin/orders" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye me-1"></i> Lihat Semua
                </a>
                <a href="/admin/orders/create" class="btn btn-success btn-sm ms-1">
                    <i class="fas fa-plus me-1"></i> Tambah
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Tanggal Potong</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $o): ?>
                        <tr>
                            <td><strong>#<?= $o['id_order'] ?></strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;font-size:0.8rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <strong><?= $o['customer_name'] ?? 'N/A' ?></strong>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary"><?= $o['package_name'] ?? 'N/A' ?></span></td>
                            <td><i class="far fa-calendar me-1 text-muted"></i><?= date('d/m/Y', strtotime($o['slaughter_date'])) ?></td>
                            <td><strong>Rp <?= number_format($o['total_price'], 0, ',', '.') ?></strong></td>
                            <td>
                                <span class="badge badge-status bg-<?= $o['status'] == 'Completed' ? 'success' : ($o['status'] == 'Pending' ? 'warning' : ($o['status'] == 'Processing' ? 'info' : 'secondary')) ?>">
                                    <?= $o['status'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recent_orders)): ?>
                        <tr><td colspan="6" class="text-center py-4">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h6>Belum Ada Pesanan</h6>
                                <p>Mulai dengan membuat pesanan baru.</p>
                                <a href="/admin/orders/create" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Buat Pesanan</a>
                            </div>
                        </td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <footer>&copy; <?= date('Y') ?> Ibrahim Aqiqah - Sistem Penjadwalan. All rights reserved.</footer>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ once: true });

document.addEventListener('DOMContentLoaded', function() {
    // FullCalendar
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        locale: 'id',
        events: '/admin/calendar/events',
        eventClick: function(info) {
            alert(info.event.extendedProps.description || 'Order #' + info.event.id);
        },
        height: 400,
        aspectRatio: 1.8
    });
    calendar.render();

    // Chart.js - Pie chart for order status
    fetch('/admin/orders/stats')
        .then(res => res.json())
        .then(data => {
            if (data && data.status_counts) {
                const ctx = document.getElementById('orderChart').getContext('2d');
                document.getElementById('pendingCount').textContent = data.status_counts.Pending || 0;
                document.getElementById('processingCount').textContent = data.status_counts.Processing || 0;
                document.getElementById('completedCount').textContent = data.status_counts.Completed || 0;
                document.getElementById('scheduledCount').textContent = data.status_counts.Scheduled || 0;
                
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Processing', 'Completed', 'Scheduled'],
                        datasets: [{
                            data: [
                                data.status_counts.Pending || 0,
                                data.status_counts.Processing || 0,
                                data.status_counts.Completed || 0,
                                data.status_counts.Scheduled || 0
                            ],
                            backgroundColor: [
                                '#ffc107',
                                '#17a2b8',
                                '#28a745',
                                '#6c757d'
                            ],
                            borderWidth: 0,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '55%',
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        })
        .catch(err => console.log('Stats not available'));
});
</script>
</body>
</html>