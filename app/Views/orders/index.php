<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
                </ol>
            </nav>
            <h4 class="page-title mb-0"><i class="fas fa-shopping-cart"></i>Data Pesanan</h4>
            <small class="text-muted">Kelola semua data pemesanan aqiqah</small>
        </div>
        <div>
            <a href="/admin/orders/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Tambah Pesanan
            </a>
        </div>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success animate-slide-in"><i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <!-- Filter Card -->
    <div class="card filter-card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small">Cari</label>
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Nama, No. Order...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Status</label>
                    <select id="filterStatus" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Processing">Processing</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Tanggal Potong</label>
                    <input type="date" id="filterDate" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Paket</label>
                    <select id="filterPackage" class="form-select form-select-sm">
                        <option value="">Semua Paket</option>
                        <?php foreach ($packages ?? [] as $p): ?>
                        <option value="<?= $p['name'] ?>"><?= $p['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small">&nbsp;</label>
                    <button class="btn btn-sm btn-outline-secondary w-100" onclick="resetFilters()"><i class="fas fa-undo"></i></button>
                </div>
                <div class="col-md-2 text-end">
                    <label class="form-label small">&nbsp;</label>
                    <div>
                        <span id="filterCount" class="badge bg-primary bg-opacity-10 text-primary">0 ditampilkan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Pesanan</h5>
            <div class="d-flex gap-2">
                <a href="/admin/scheduler/run" class="btn btn-info btn-sm">
                    <i class="fas fa-calendar-alt me-1"></i>Jalankan Scheduler
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="ordersTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Hewan</th>
                            <th>Tgl. Potong</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="width:140px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $o): ?>
                        <tr class="order-row" 
                            data-search="<?= strtolower(($o['customer_name'] ?? '') . ' ' . ($o['child_name'] ?? '')) ?>"
                            data-status="<?= $o['status'] ?>"
                            data-date="<?= $o['slaughter_date'] ?>"
                            data-package="<?= $o['package_name'] ?? '' ?>">
                            <td><strong>#<?= $o['id_order'] ?></strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;font-size:0.7rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <strong><?= $o['customer_name'] ?? 'N/A' ?></strong>
                                        <small class="d-block text-muted" style="font-size:0.75rem;"><?= $o['child_name'] ?? '' ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary"><?= $o['package_name'] ?? 'N/A' ?></span></td>
                            <td><?= $o['animal_type'] ?> <small class="text-muted">(<?= $o['animal_gender'] ?>)</small></td>
                            <td>
                                <i class="far fa-calendar me-1 text-muted"></i><?= date('d/m/Y', strtotime($o['slaughter_date'])) ?><br>
                                <small class="text-muted">Antar: <?= date('d/m/Y', strtotime($o['delivery_date'])) ?></small>
                            </td>
                            <td><strong>Rp <?= number_format($o['total_price'], 0, ',', '.') ?></strong></td>
                            <td>
                                <span class="badge badge-status bg-<?= $o['status'] == 'Completed' ? 'success' : ($o['status'] == 'Pending' ? 'warning' : ($o['status'] == 'Processing' ? 'info' : ($o['status'] == 'Cancelled' ? 'danger' : 'secondary'))) ?>">
                                    <?= $o['status'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-info btn-action" onclick="showDetail(<?= htmlspecialchars(json_encode($o)) ?>)" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="/admin/orders/edit/<?= $o['id_order'] ?>" class="btn btn-sm btn-warning btn-action" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/admin/reports/certificate/<?= $o['id_order'] ?>" class="btn btn-sm btn-success btn-action" target="_blank" title="Sertifikat">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <a href="/admin/reports/invitation/<?= $o['id_order'] ?>" class="btn btn-sm btn-secondary btn-action" target="_blank" title="Undangan">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    <a href="/admin/orders/delete/<?= $o['id_order'] ?>" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Hapus pesanan #<?= $o['id_order'] ?>?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($orders)): ?>
                        <tr id="emptyRow">
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h6>Belum Ada Pesanan</h6>
                                    <p>Mulai dengan membuat pesanan baru.</p>
                                    <a href="/admin/orders/create" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Buat Pesanan Baru</a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <footer>&copy; <?= date('Y') ?> Ibrahim Aqiqah - Sistem Penjadwalan</footer>
</main>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detail Pesanan #<span id="detailId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body py-3">
                                <h6 class="fw-bold text-success"><i class="fas fa-user me-2"></i>Data Pemesan</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr><td class="text-muted" style="width:100px">Nama</td><td>: <strong id="dCustomer"></strong></td></tr>
                                    <tr><td class="text-muted">Anak</td><td>: <span id="dChild"></span></td></tr>
                                    <tr><td class="text-muted">Gender</td><td>: <span id="dGender"></span></td></tr>
                                    <tr><td class="text-muted">Telepon</td><td>: <span id="dPhone"></span></td></tr>
                                    <tr><td class="text-muted">Alamat</td><td>: <span id="dAddress"></span></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body py-3">
                                <h6 class="fw-bold text-success"><i class="fas fa-box me-2"></i>Detail Pesanan</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr><td class="text-muted" style="width:100px">Paket</td><td>: <strong id="dPackage"></strong></td></tr>
                                    <tr><td class="text-muted">Hewan</td><td>: <span id="dAnimal"></span></td></tr>
                                    <tr><td class="text-muted">Jumlah</td><td>: <span id="dJumlah"></span> ekor</td></tr>
                                    <tr><td class="text-muted">Potong</td><td>: <span id="dSlaughter"></span></td></tr>
                                    <tr><td class="text-muted">Antar</td><td>: <span id="dDelivery"></span></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card bg-light border-0">
                            <div class="card-body py-3">
                                <h6 class="fw-bold text-success"><i class="fas fa-utensils me-2"></i>Menu & Box</h6>
                                <div id="detailMenu"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center p-3 bg-success bg-opacity-10 rounded-3">
                            <div>
                                <strong>Status:</strong> <span id="dStatus" class="badge badge-status"></span>
                                <span class="ms-3"><strong>Penyembelihan:</strong> <span id="dPenyembelihan"></span></span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">Total Harga</small>
                                <h4 class="fw-bold text-success mb-0" id="dTotal">Rp 0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="detailEditBtn" class="btn btn-warning"><i class="fas fa-edit me-1"></i>Edit</a>
                <a href="#" id="detailCertBtn" class="btn btn-success" target="_blank"><i class="fas fa-file-pdf me-1"></i>Sertifikat</a>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Filter System
function applyFilters() {
    const search = document.getElementById('searchInput').value.toLowerCase().trim();
    const status = document.getElementById('filterStatus').value;
    const date = document.getElementById('filterDate').value;
    const pkg = document.getElementById('filterPackage').value;
    let visible = 0;
    
    document.querySelectorAll('.order-row').forEach(row => {
        const searchText = row.dataset.search || '';
        const rowStatus = row.dataset.status || '';
        const rowDate = row.dataset.date || '';
        const rowPackage = row.dataset.package || '';
        
        const matchSearch = !search || searchText.includes(search);
        const matchStatus = !status || rowStatus === status;
        const matchDate = !date || rowDate === date;
        const matchPackage = !pkg || rowPackage === pkg;
        
        if (matchSearch && matchStatus && matchDate && matchPackage) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });
    
    document.getElementById('filterCount').textContent = visible + ' ditampilkan';
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterDate').value = '';
    document.getElementById('filterPackage').value = '';
    applyFilters();
}

document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('filterStatus').addEventListener('change', applyFilters);
document.getElementById('filterDate').addEventListener('change', applyFilters);
document.getElementById('filterPackage').addEventListener('change', applyFilters);

// Detail Modal
const ordersData = <?= json_encode($orders) ?>;
const customersData = <?= json_encode($customers ?? []) ?>;

function showDetail(order) {
    document.getElementById('detailId').textContent = order.id_order;
    
    const cust = customersData.find(c => c.id_customer == order.customer_id) || {};
    
    document.getElementById('dCustomer').textContent = cust.name || order.customer_name || '-';
    document.getElementById('dChild').textContent = cust.child_name || order.child_name || '-';
    document.getElementById('dGender').textContent = cust.gender || '-';
    document.getElementById('dPhone').textContent = cust.phone || '-';
    document.getElementById('dAddress').textContent = cust.address || '-';
    document.getElementById('dPackage').textContent = order.package_name || '-';
    document.getElementById('dAnimal').textContent = (order.animal_type || '-') + ' (' + (order.animal_gender || '-') + ')';
    document.getElementById('dJumlah').textContent = order.jumlah_anak || 1;
    document.getElementById('dSlaughter').textContent = order.slaughter_date || '-';
    document.getElementById('dDelivery').textContent = order.delivery_date || '-';
    document.getElementById('dPenyembelihan').textContent = order.penyembelihan || '-';
    document.getElementById('dTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(order.total_price || 0);
    
    const statusBadge = document.getElementById('dStatus');
    const s = order.status || 'Pending';
    const colors = {Completed:'success', Pending:'warning', Processing:'info', Scheduled:'secondary', Cancelled:'danger'};
    statusBadge.className = 'badge badge-status bg-' + (colors[s] || 'secondary');
    statusBadge.textContent = s;
    
    document.getElementById('detailEditBtn').href = '/admin/orders/edit/' + order.id_order;
    document.getElementById('detailCertBtn').href = '/admin/reports/certificate/' + order.id_order;
    
    document.getElementById('detailMenu').innerHTML = '<p class="text-muted mb-0">Menu & box details available in edit page.</p>';
    
    new bootstrap.Modal(document.getElementById('detailModal')).show();
}

setTimeout(applyFilters, 100);
</script>
</body>
</html>