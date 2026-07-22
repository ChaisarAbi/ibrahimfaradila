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

    <!-- Recap 24 Jam Card -->
    <?php if (!empty($upcoming_slaughter)): ?>
    <div class="card border-warning mb-4 animate-fade-up">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Pelanggan</th>
                            <th>Anak</th>
                            <th>Paket</th>
                            <th>Jam Potong</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($upcoming_slaughter as $u): ?>
                        <tr>
                            <td><strong>#<?= $u['id_order'] ?></strong></td>
                            <td><?= $u['customer_name'] ?? 'N/A' ?></td>
                            <td><?= $u['child_name'] ?? '-' ?></td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary"><?= $u['package_name'] ?? 'N/A' ?></span></td>
                            <td><?= $u['slaughter_time'] ? date('H:i', strtotime($u['slaughter_time'])) : '<em class="text-muted">Belum diatur</em>' ?></td>
                            <td>
                                <?php if (!empty($u['customer_phone'])): ?>
                                <a href="https://wa.me/62<?= preg_replace('/^0?/', '', $u['customer_phone']) ?>" target="_blank" class="btn btn-sm btn-outline-success" style="border-color:#25D366;color:#25D366;">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/admin/orders/edit/<?= $u['id_order'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Charts & Calendar Row -->
    <div class="row g-3 mb-4">
        <div class="col-lg-9">
            <div class="card animate-fade-up stagger-1 h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Kalender Penjadwalan</h5>
                    <small class="text-muted">Klik event untuk detail pesanan</small>
                </div>
                <div class="card-body p-3">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card animate-fade-up stagger-2 h-100">
                <div class="card-header py-2">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Statistik Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; width: 100%; height: 180px; margin: 0 auto;">
                        <canvas id="orderChart"></canvas>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><span class="badge bg-warning" style="width:10px;height:10px;display:inline-block;border-radius:50%;padding:0;vertical-align:middle;"></span> Pending</span>
                        <span class="fw-bold" id="pendingCount">0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><span class="badge bg-info" style="width:10px;height:10px;display:inline-block;border-radius:50%;padding:0;vertical-align:middle;"></span> Processing</span>
                        <span class="fw-bold" id="processingCount">0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><span class="badge bg-success" style="width:10px;height:10px;display:inline-block;border-radius:50%;padding:0;vertical-align:middle;"></span> Completed</span>
                        <span class="fw-bold" id="completedCount">0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><span class="badge bg-secondary" style="width:10px;height:10px;display:inline-block;border-radius:50%;padding:0;vertical-align:middle;"></span> Scheduled</span>
                        <span class="fw-bold" id="scheduledCount">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row: Weekly Orders + Stock -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card animate-fade-up stagger-3 h-100">
                <div class="card-header">
                    <h5><i class="fas fa-chart-line me-2 text-success"></i>Pesanan Mingguan</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; width: 100%; height: 220px;">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card animate-fade-up stagger-4 h-100">
                <div class="card-header">
                    <h5><i class="fas fa-warehouse me-2 text-warning"></i>Stok Terkini</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; width: 100%; height: 220px;">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Revenue Chart -->
    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="card animate-fade-up stagger-5">
                <div class="card-header">
                    <h5><i class="fas fa-money-bill-trend-up me-2 text-primary"></i>Pendapatan Bulanan</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; width: 100%; height: 250px;">
                        <canvas id="monthlyChart"></canvas>
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
        height: 'auto',
        aspectRatio: 2.2,
        contentHeight: 400
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
    
    // Load chart data for weekly, stock, and monthly charts
    fetch('/admin/dashboard/chart-data')
        .then(res => res.json())
        .then(data => {
            // Weekly Orders Chart (Bar)
            const weeklyCtx = document.getElementById('weeklyChart');
            if (weeklyCtx) {
                new Chart(weeklyCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.weekly_labels,
                        datasets: [{
                            label: 'Pesanan',
                            data: data.weekly_data,
                            backgroundColor: 'rgba(40, 167, 69, 0.6)',
                            borderColor: '#28a745',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } }
                        }
                    }
                });
            }
            
            // Stock Chart (Horizontal Bar)
            const stockCtx = document.getElementById('stockChart');
            if (stockCtx) {
                new Chart(stockCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.stock_labels,
                        datasets: [{
                            label: 'Jumlah',
                            data: data.stock_data,
                            backgroundColor: data.stock_colors,
                            borderColor: data.stock_colors.map(c => c),
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { beginAtZero: true, ticks: { stepSize: 1 } }
                        }
                    }
                });
            }
            
            // Monthly Revenue Chart (Line)
            const monthlyCtx = document.getElementById('monthlyChart');
            if (monthlyCtx) {
                new Chart(monthlyCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.monthly_labels,
                        datasets: [{
                            label: 'Pendapatan',
                            data: data.monthly_revenue,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#0d6efd',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }
        })
        .catch(err => console.log('Chart data not available:', err));
});
</script>
</body>
</html>