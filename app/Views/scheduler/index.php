<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Jadwalkan (EDF)</li>
                </ol>
            </nav>
            <h4 class="page-title mb-0"><i class="fas fa-calendar-alt"></i>Jadwal Penjadwalan Aqiqah</h4>
            <small class="text-muted">Algorithm EDF (Earliest Deadline First) untuk menentukan prioritas penyembelihan</small>
        </div>
        <div>
            <form action="/admin/scheduler/run" method="POST" onsubmit="return confirm('Jalankan scheduler? Ini akan memproses semua pesanan Pending menjadi Scheduled.')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-success btn-sm" id="runSchedulerBtn">
                    <i class="fas fa-play"></i> Jalankan Scheduler EDF
                </button>
            </form>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success animate-slide-in"><i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- EDF Info Card -->
    <div class="card mb-3 border-0 shadow-sm" style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9);">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="text-success mb-2"><i class="fas fa-info-circle me-2"></i>Apa itu Algorithm EDF/EDD?</h5>
                    <p class="mb-2"><strong>EDF (Earliest Deadline First)</strong> atau <strong>EDD (Earliest Due Date)</strong> adalah algoritma penjadwalan yang memprioritaskan tugas dengan <em>deadline terdekat</em>.</p>
                    <ul class="mb-0 small text-muted">
                        <li><strong>Prinsip:</strong> Pesanan dengan tanggal potong paling awal akan mendapat prioritas #1</li>
                        <li><strong>Tujuan:</strong> Memastikan aqiqah yang dijadwalkan tepat waktu sesuai permintaan pelanggan</li>
                        <li><strong>Hasil:</strong> Semua pesanan Pending akan diurutkan berdasarkan tanggal potong terdekat</li>
                    </ul>
                </div>
                <div class="col-md-4 text-center">
                    <div style="font-size:4rem; opacity:0.3;">📅</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div style="font-size:2rem; color:#ffc107;">⏳</div>
                    <h3 class="fw-bold text-warning" id="pendingCount">-</h3>
                    <small class="text-muted">Pesanan Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div style="font-size:2rem; color:#2196f3;">📋</div>
                    <h3 class="fw-bold text-primary" id="scheduledCount">-</h3>
                    <small class="text-muted">Sudah Dijadwalkan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div style="font-size:2rem; color:#4caf50;">✅</div>
                    <h3 class="fw-bold text-success" id="completedCount">-</h3>
                    <small class="text-muted">Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div style="font-size:2rem; color:#ff9800;">🔥</div>
                    <h3 class="fw-bold text-danger" id="todayCount">-</h3>
                    <small class="text-muted">Hari Ini</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Result (Server-Side Rendered) -->
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Jadwal yang Telah Dibuat</h5>
            <span class="badge bg-success"><?= count($scheduled_orders ?? []) ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="scheduleTable">
                    <thead>
                        <tr>
                            <th width="60">Priority</th>
                            <th>ID Order</th>
                            <th>Pelanggan</th>
                            <th>Anak</th>
                            <th>Tgl. Potong</th>
                            <th>Paket</th>
                            <th>Hewan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($scheduled_orders)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox text-muted" style="font-size:1.5rem;"></i><br>
                                <span class="text-muted">Belum ada jadwal. Jalankan scheduler untuk membuat jadwal.</span>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($scheduled_orders as $i => $order): ?>
                        <tr>
                            <td><span class="badge bg-<?= $i === 0 ? 'danger' : ($i === 1 ? 'warning' : 'info') ?>"><?= $i + 1 ?></span></td>
                            <td><strong>#<?= $order['id_order'] ?></strong></td>
                            <td><?= htmlspecialchars($order['customer_name'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($order['child_name'] ?? '-') ?></td>
                            <td><?= date('d/m/Y', strtotime($order['slaughter_date'])) ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($order['package_name'] ?? '-') ?></span></td>
                            <td><?= htmlspecialchars($order['animal_type'] ?? '-') ?> (<?= htmlspecialchars($order['animal_gender'] ?? '-') ?>)</td>
                            <td><span class="badge badge-status bg-success"><?= $order['status'] ?? '-' ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pending Orders (Server-Side Rendered) -->
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Pesanan Pending (Menunggu Dijadwalkan)</h5>
            <span class="badge bg-warning"><?= count($pending_orders ?? []) ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="pendingTable">
                    <thead>
                        <tr>
                            <th>ID Order</th>
                            <th>Pelanggan</th>
                            <th>Anak</th>
                            <th>Tgl. Potong</th>
                            <th>Paket</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pending_orders)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-check-circle text-success" style="font-size:1.5rem;"></i><br>
                                <span class="text-success">Semua pesanan sudah dijadwalkan!</span>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($pending_orders as $order): ?>
                        <tr>
                            <td><strong>#<?= $order['id_order'] ?></strong></td>
                            <td><?= htmlspecialchars($order['customer_name'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($order['child_name'] ?? '-') ?></td>
                            <td><?= date('d/m/Y', strtotime($order['slaughter_date'])) ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($order['package_name'] ?? '-') ?></span></td>
                            <td><span class="badge badge-status bg-warning"><?= $order['status'] ?? '-' ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <footer>&copy; <?= date('Y') ?> Ibrahim Aqiqah - Sistem Penjadwalan</footer>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Stats from server-side data
document.addEventListener('DOMContentLoaded', function() {
    var stats = <?= json_encode($stats ?? []) ?>;
    document.getElementById('pendingCount').textContent = stats.pending || 0;
    document.getElementById('scheduledCount').textContent = stats.scheduled || 0;
    document.getElementById('completedCount').textContent = stats.completed || 0;
    document.getElementById('todayCount').textContent = stats.today || 0;
});

function formatDateClient(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    return days[d.getDay()] + ', ' + d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
}
</script>
</body>
</html>