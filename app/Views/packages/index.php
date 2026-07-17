<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="page-title mb-0"><i class="fas fa-box me-2"></i>Data Paket</h4>
            <small class="text-muted">Kelola paket aqiqah yang tersedia</small>
        </div>
        <a href="/admin/packages/create" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Tambah Paket</a>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success animate-slide-in"><i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small">Cari</label>
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Nama paket...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Tipe Berat</label>
                    <select id="filterType" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="A">A (16-17kg)</option>
                        <option value="B">B (17-18kg)</option>
                        <option value="C">C (18-20kg)</option>
                        <option value="D">D (21-23kg)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Special</label>
                    <select id="filterSpecial" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="special">Special Only</option>
                        <option value="regular">Regular Only</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small">&nbsp;</label>
                    <button class="btn btn-sm btn-outline-secondary w-100" onclick="resetFilters()"><i class="fas fa-undo"></i></button>
                </div>
                <div class="col-md-2 text-end">
                    <label class="form-label small">&nbsp;</label>
                    <div><span id="filterCount" class="badge bg-primary">0 ditampilkan</span></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Package Summary Cards -->
    <?php
        $total_packages = count($packages);
        $min_price = $packages ? min(array_column($packages, 'price')) : 0;
        $max_price = $packages ? max(array_column($packages, 'price')) : 0;
        $special_count = 0;
        foreach ($packages as $p) { if ($p['is_special']) $special_count++; }
    ?>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-1">
                <div class="d-flex justify-content-between mb-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-box"></i></div>
                    <span class="stat-trend bg-primary bg-opacity-10 text-primary"><i class="fas fa-list me-1"></i>Total</span>
                </div>
                <div>
                    <small class="text-muted">Total Paket</small>
                    <h4 class="mb-0 fw-bold"><?= $total_packages ?></h4>
                    <small class="text-muted mt-1 d-block">Paket tersedia</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-2">
                <div class="d-flex justify-content-between mb-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-crown"></i></div>
                    <span class="stat-trend bg-warning bg-opacity-10 text-warning"><i class="fas fa-star me-1"></i>Special</span>
                </div>
                <div>
                    <small class="text-muted">Paket Special</small>
                    <h4 class="mb-0 fw-bold"><?= $special_count ?></h4>
                    <small class="text-muted mt-1 d-block">Paket premium</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-3">
                <div class="d-flex justify-content-between mb-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-arrow-down"></i></div>
                    <span class="stat-trend bg-success bg-opacity-10 text-success"><i class="fas fa-tag me-1"></i>Min</span>
                </div>
                <div>
                    <small class="text-muted">Harga Termurah</small>
                    <h4 class="mb-0 fw-bold">Rp <?= number_format($min_price, 0, ',', '.') ?></h4>
                    <small class="text-muted mt-1 d-block">Paket Berkah A</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card animate-fade-up stagger-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="fas fa-arrow-up"></i></div>
                    <span class="stat-trend bg-danger bg-opacity-10 text-danger"><i class="fas fa-tag me-1"></i>Max</span>
                </div>
                <div>
                    <small class="text-muted">Harga Termahal</small>
                    <h4 class="mb-0 fw-bold">Rp <?= number_format($max_price, 0, ',', '.') ?></h4>
                    <small class="text-muted mt-1 d-block">Paket Special D</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-list me-2"></i>Daftar Paket</h5>
            <span><small class="text-muted">Total: <?= $total_packages ?></small></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>#</th><th>Nama</th><th>Tipe</th><th>Berat</th><th>Box</th><th>Harga</th><th>Special</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($packages as $p): ?>
                        <tr class="package-row"
                            data-search="<?= strtolower($p['name'] ?? '') ?>"
                            data-type="<?= $p['weight_type'] ?? '' ?>"
                            data-special="<?= $p['is_special'] ? 'special' : 'regular' ?>">
                            <td><?= $p['id_package'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;font-size:0.8rem;">
                                        <i class="fas fa-<?= $p['is_special'] ? 'crown' : 'box' ?>"></i>
                                    </div>
                                    <strong><?= $p['name'] ?></strong>
                                </div>
                            </td>
                            <td><span class="badge bg-info bg-opacity-10 text-info">Tipe <?= $p['weight_type'] ?></span></td>
                            <td><?= $p['min_weight'] ?> - <?= $p['max_weight'] ?> kg</td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary"><?= $p['box_count'] ?> Box</span></td>
                            <td><strong>Rp <?= number_format($p['price'], 0, ',', '.') ?></strong></td>
                            <td>
                                <?php if ($p['is_special']): ?>
                                    <span class="badge bg-warning badge-status"><i class="fas fa-crown me-1"></i>Special</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary badge-status">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/admin/packages/edit/<?= $p['id_package'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="/admin/packages/delete/<?= $p['id_package'] ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus paket ini?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($packages)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-box fa-2x mb-2 d-block"></i>
                            Belum ada paket. <a href="/admin/packages/create" class="text-success">Tambah paket</a>
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
    const type = document.getElementById('filterType').value;
    const special = document.getElementById('filterSpecial').value;
    let visible = 0;
    
    document.querySelectorAll('.package-row').forEach(row => {
        const matchSearch = !search || (row.dataset.search || '').includes(search);
        const matchType = !type || row.dataset.type === type;
        const matchSpecial = !special || row.dataset.special === special;
        
        row.style.display = (matchSearch && matchType && matchSpecial) ? '' : 'none';
        if (matchSearch && matchType && matchSpecial) visible++;
    });
    
    document.getElementById('filterCount').textContent = visible + ' ditampilkan';
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterType').value = '';
    document.getElementById('filterSpecial').value = '';
    applyFilters();
}

document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('filterType').addEventListener('change', applyFilters);
document.getElementById('filterSpecial').addEventListener('change', applyFilters);
setTimeout(applyFilters, 100);
</script>
</body>
</html>