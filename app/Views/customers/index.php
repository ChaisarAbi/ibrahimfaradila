<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="page-title mb-0"><i class="fas fa-users me-2"></i>Data Pelanggan</h4>
            <small class="text-muted">Kelola data pelanggan aqiqah</small>
        </div>
        <a href="/admin/customers/create" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Tambah Pelanggan</a>
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
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Nama, Anak, Telepon...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Gender</label>
                    <select id="filterGender" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
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
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-list me-2"></i>Daftar Pelanggan</h5>
            <span><small class="text-muted">Total: <?= count($customers) ?></small></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>#</th><th>Nama</th><th>Anak</th><th>Gender</th><th>Telepon</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $c): ?>
                        <tr class="customer-row"
                            data-search="<?= strtolower(($c['name'] ?? '') . ' ' . ($c['child_name'] ?? '') . ' ' . ($c['phone'] ?? '')) ?>"
                            data-gender="<?= $c['gender'] ?? '' ?>">
                            <td><?= $c['id_customer'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;font-size:0.7rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <strong><?= $c['name'] ?></strong>
                                </div>
                            </td>
                            <td><?= $c['child_name'] ?></td>
                            <td>
                                <span class="badge bg-<?= ($c['gender'] ?? '') == 'Laki-laki' ? 'info' : 'pink' ?> bg-opacity-10 text-<?= ($c['gender'] ?? '') == 'Laki-laki' ? 'info' : 'danger' ?>">
                                    <i class="fas fa-<?= ($c['gender'] ?? '') == 'Laki-laki' ? 'mars' : 'venus' ?> me-1"></i><?= $c['gender'] ?>
                                </span>
                            </td>
                            <td><i class="fab fa-whatsapp text-success me-1"></i><?= $c['phone'] ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/admin/customers/edit/<?= $c['id_customer'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="/admin/customers/delete/<?= $c['id_customer'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pelanggan <?= $c['name'] ?>?')" title="Hapus"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($customers)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-users fa-2x mb-2 d-block"></i>
                            Belum ada pelanggan. <a href="/admin/customers/create" class="text-success">Tambah pelanggan</a>
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
    const gender = document.getElementById('filterGender').value;
    let visible = 0;
    
    document.querySelectorAll('.customer-row').forEach(row => {
        const matchSearch = !search || (row.dataset.search || '').includes(search);
        const matchGender = !gender || row.dataset.gender === gender;
        
        row.style.display = (matchSearch && matchGender) ? '' : 'none';
        if (matchSearch && matchGender) visible++;
    });
    
    document.getElementById('filterCount').textContent = visible + ' ditampilkan';
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterGender').value = '';
    applyFilters();
}

document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('filterGender').addEventListener('change', applyFilters);
setTimeout(applyFilters, 100);
</script>
</body>
</html>