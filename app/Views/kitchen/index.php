<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dapur</li>
                </ol>
            </nav>
            <h4 class="page-title mb-0"><i class="fas fa-utensils me-2"></i>Dapur / Kitchen</h4>
            <small class="text-muted">Lembar kerja dapur untuk hari ini</small>
        </div>
        <div>
            <a href="/admin/reports/kitchen-sheet" class="btn btn-primary btn-sm" target="_blank">
                <i class="fas fa-print me-1"></i>Cetak Lembar Kerja
            </a>
            <a href="/admin/reports/order-report/<?= date('Y-m-d') ?>" class="btn btn-info btn-sm" target="_blank">
                <i class="fas fa-file-pdf me-1"></i>Laporan Hari Ini
            </a>
        </div>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success animate-slide-in"><i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted text-uppercase fw-semibold">Pesanan Hari Ini</small>
                            <h3 class="fw-bold mb-0 text-primary"><?= count($orders) ?></h3>
                        </div>
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-clipboard-list text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted text-uppercase fw-semibold">Total Box</small>
                            <h3 class="fw-bold mb-0 text-success"><?= $total_boxes ?></h3>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="fas fa-box text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted text-uppercase fw-semibold">Menu Tulang</small>
                            <h3 class="fw-bold mb-0 text-warning"><?= $totalBoneMenus ?></h3>
                        </div>
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-drumstick-bite text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted text-uppercase fw-semibold">Menu Daging</small>
                            <h3 class="fw-bold mb-0 text-danger"><?= $totalMeatMenus ?></h3>
                        </div>
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-utensils text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Kitchen Orders -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Pesanan Hari Ini</h5>
            <div>
                <input type="date" id="kitchenDate" class="form-control form-control-sm d-inline-block" style="width:160px" value="<?= date('Y-m-d') ?>">
                <button class="btn btn-sm btn-outline-secondary ms-1" onclick="loadKitchenData()"><i class="fas fa-sync-alt"></i></button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="kitchenTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pelanggan</th>
                            <th>Anak</th>
                            <th>Paket</th>
                            <th>Hewan</th>
                            <th>Menu Tulang</th>
                            <th>Menu Daging</th>
                            <th>Tipe Box</th>
                            <th>Jumlah Box</th>
                            <th style="width:100px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><strong>#<?= $o['id_order'] ?></strong></td>
                            <td><?= $o['customer_name'] ?? 'N/A' ?></td>
                            <td><?= $o['child_name'] ?? 'N/A' ?></td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary"><?= $o['package_name'] ?? 'N/A' ?></span></td>
                            <td><?= $o['animal_type'] ?> (<?= $o['animal_gender'] ?>)</td>
                            <td><?= $o['bone_menu'] ?? '-' ?></td>
                            <td><?= $o['meat_menu'] ?? '-' ?></td>
                            <td><?= $o['box_type'] ?? '-' ?></td>
                            <td><strong><?= $o['jumlah_box'] ?? 0 ?></strong></td>
                            <td>
                                <span class="badge badge-status bg-<?= $o['status'] == 'Processing' ? 'info' : ($o['status'] == 'Completed' ? 'success' : ($o['status'] == 'Scheduled' ? 'secondary' : 'warning')) ?>">
                                    <?= $o['status'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <i class="fas fa-calendar-day"></i>
                                    <h6>Tidak Ada Pesanan Hari Ini</h6>
                                    <p>Tidak ada jadwal pemotongan untuk tanggal yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Stock Summary -->
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i>Ringkasan Stok</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Min. Threshold</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stocks as $s): ?>
                        <tr>
                            <td><strong><?= $s['item_name'] ?></strong></td>
                            <td><span class="badge bg-info bg-opacity-10 text-info"><?= $s['category'] ?></span></td>
                            <td><?= $s['quantity'] ?> <?= $s['unit'] ?></td>
                            <td><?= $s['min_threshold'] ?></td>
                            <td>
                                <?php if ($s['quantity'] <= $s['min_threshold']): ?>
                                    <span class="badge bg-danger">Stok Menipis</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Tersedia</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <footer>&copy; <?= date('Y') ?> Ibrahim Aqiqah - Sistem Penjadwalan</footer>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function loadKitchenData() {
    const date = document.getElementById('kitchenDate').value;
    if (date) {
        window.location.href = '/admin/kitchen?date=' + date;
    }
}
</script>
</body>
</html>