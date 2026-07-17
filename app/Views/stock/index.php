<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="page-title mb-0"><i class="fas fa-boxes me-2"></i>Manajemen Stok</h4>
            <small class="text-muted">Kelola stok hewan dan bahan baku dapur</small>
        </div>
        <a href="/admin/stock/create" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Tambah Stok</a>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success animate-slide-in"><i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">Cari</label>
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Nama item...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Kategori</label>
                    <select id="filterCategory" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="hewan">Hewan</option>
                        <option value="bahan">Bahan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Status</label>
                    <select id="filterStatus" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="danger">Stok Menipis</option>
                        <option value="success">Aman</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small">&nbsp;</label>
                    <button class="btn btn-sm btn-outline-secondary w-100" onclick="resetFilters()"><i class="fas fa-undo"></i></button>
                </div>
                <div class="col-md-1 text-end">
                    <label class="form-label small">&nbsp;</label>
                    <div><span id="filterCount" class="badge bg-primary">0 ditampilkan</span></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stock Summary Cards -->
    <?php
        $total_hewan = 0;
        $total_bahan = 0;
        $alert_count = 0;
        foreach ($stocks as $s) {
            if ($s['category'] == 'hewan') $total_hewan += $s['quantity'];
            if ($s['category'] == 'bahan') $total_bahan += $s['quantity'];
            if ($s['quantity'] <= $s['min_threshold']) $alert_count++;
        }
    ?>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-1">
                <div class="d-flex justify-content-between mb-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-horse"></i></div>
                    <span class="stat-trend bg-success bg-opacity-10 text-success"><i class="fas fa-tag me-1"></i>Hewan</span>
                </div>
                <div>
                    <small class="text-muted">Total Hewan</small>
                    <h4 class="mb-0 fw-bold"><?= $total_hewan ?></h4>
                    <small class="text-muted mt-1 d-block">Ekor</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-2">
                <div class="d-flex justify-content-between mb-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fas fa-drumstick-bite"></i></div>
                    <span class="stat-trend bg-info bg-opacity-10 text-info"><i class="fas fa-tag me-1"></i>Bahan</span>
                </div>
                <div>
                    <small class="text-muted">Total Bahan</small>
                    <h4 class="mb-0 fw-bold"><?= $total_bahan ?></h4>
                    <small class="text-muted mt-1 d-block">Item tersedia</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-3">
                <div class="d-flex justify-content-between mb-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-exclamation-triangle"></i></div>
                    <span class="stat-trend bg-warning bg-opacity-10 text-warning"><i class="fas fa-bell me-1"></i>Alert</span>
                </div>
                <div>
                    <small class="text-muted">Stok Menipis</small>
                    <h4 class="mb-0 fw-bold"><?= $alert_count ?></h4>
                    <small class="text-muted mt-1 d-block">Perlu restock</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-chart-simple"></i></div>
                    <span class="stat-trend bg-primary bg-opacity-10 text-primary"><i class="fas fa-list me-1"></i>Total</span>
                </div>
                <div>
                    <small class="text-muted">Semua Item</small>
                    <h4 class="mb-0 fw-bold"><?= count($stocks) ?></h4>
                    <small class="text-muted mt-1 d-block">Item terdaftar</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-list me-2"></i>Daftar Stok</h5>
            <span><small class="text-muted">Total: <?= count($stocks) ?></small></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>#</th><th>Nama Item</th><th>Kategori</th><th>Jumlah</th><th>Satuan</th><th>Min Threshold</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stocks as $s): ?>
                        <?php $isLow = $s['quantity'] <= $s['min_threshold']; ?>
                        <tr class="stock-row
                            <?= $isLow ? 'table-danger' : '' ?>"
                            data-search="<?= strtolower($s['item_name'] ?? '') ?>"
                            data-category="<?= $s['category'] ?? '' ?>"
                            data-status="<?= $isLow ? 'danger' : 'success' ?>">
                            <td><?= $s['id_stock'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-<?= $s['category'] == 'hewan' ? 'success' : 'info' ?> bg-opacity-10 text-<?= $s['category'] == 'hewan' ? 'success' : 'info' ?> d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;font-size:0.8rem;">
                                        <i class="fas fa-<?= $s['category'] == 'hewan' ? 'horse' : 'drumstick-bite' ?>"></i>
                                    </div>
                                    <strong><?= $s['item_name'] ?></strong>
                                </div>
                            </td>
                            <td><span class="badge bg-<?= $s['category'] == 'hewan' ? 'success' : 'info' ?> bg-opacity-10 text-<?= $s['category'] == 'hewan' ? 'success' : 'info' ?>"><?= ucfirst($s['category']) ?></span></td>
                            <td><strong class="<?= $isLow ? 'text-danger' : 'text-success' ?>"><?= $s['quantity'] ?></strong></td>
                            <td><?= $s['unit'] ?></td>
                            <td><?= $s['min_threshold'] ?></td>
                            <td>
                                <?php if ($isLow): ?>
                                    <span class="badge bg-danger badge-status"><i class="fas fa-exclamation-triangle me-1"></i>Stok Menipis</span>
                                <?php else: ?>
                                    <span class="badge bg-success badge-status"><i class="fas fa-check me-1"></i>Aman</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/admin/stock/edit/<?= $s['id_stock'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="/admin/stock/delete/<?= $s['id_stock'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus <?= $s['item_name'] ?>?')" title="Hapus"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($stocks)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-boxes fa-2x mb-2 d-block"></i>
                            Belum ada data stok. <a href="/admin/stock/create" class="text-success">Tambah stok</a>
                        </td></tr>
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
function applyFilters() {
    const search = document.getElementById('searchInput').value.toLowerCase().trim();
    const category = document.getElementById('filterCategory').value;
    const status = document.getElementById('filterStatus').value;
    let visible = 0;
    
    document.querySelectorAll('.stock-row').forEach(row => {
        const matchSearch = !search || (row.dataset.search || '').includes(search);
        const matchCategory = !category || row.dataset.category === category;
        const matchStatus = !status || row.dataset.status === status;
        
        row.style.display = (matchSearch && matchCategory && matchStatus) ? '' : 'none';
        if (matchSearch && matchCategory && matchStatus) visible++;
    });
    
    document.getElementById('filterCount').textContent = visible + ' ditampilkan';
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterCategory').value = '';
    document.getElementById('filterStatus').value = '';
    applyFilters();
}

document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('filterCategory').addEventListener('change', applyFilters);
document.getElementById('filterStatus').addEventListener('change', applyFilters);
setTimeout(applyFilters, 100);
</script>
</body>
</html>