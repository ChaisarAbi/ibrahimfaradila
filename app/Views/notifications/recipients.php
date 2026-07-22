<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="container-fluid p-3">
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Kelola Penerima Notifikasi</h4>
            <p class="text-muted small mb-0">Daftar penerima notifikasi Telegram</p>
        </div>
        <a href="/admin/notifications/manual" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Form tambah penerima -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="fas fa-plus-circle me-2 text-success"></i>Tambah Penerima</h6>
                </div>
                <div class="card-body">
                    <form action="/admin/notifications/add-recipient" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Chat ID <span class="text-danger">*</span></label>
                            <input type="text" name="chat_id" class="form-control form-control-sm" placeholder="Contoh: 1234567890" required>
                            <div class="form-text">Dapatkan chat ID dari @userinfobot di Telegram.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama / Label</label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Misal: Grup Tim Dapur">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Tipe</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="personal">Personal (Chat Pribadi)</option>
                                <option value="group">Group (Grup Telegram)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar penerima -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="fas fa-list me-2 text-success"></i>Daftar Penerima</h6>
                    <span class="badge bg-success"><?= count($recipients) ?> penerima</span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recipients)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users-slash text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-0">Belum ada penerima notifikasi.</p>
                            <small class="text-muted">Tambahkan penerima baru di form sebelah kiri.</small>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Chat ID</th>
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recipients as $r): ?>
                                    <tr>
                                        <td><code><?= esc($r['chat_id']) ?></code></td>
                                        <td><?= esc($r['name'] ?? '-') ?></td>
                                        <td>
                                            <?php if ($r['type'] === 'group'): ?>
                                                <span class="badge bg-info">Group</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Personal</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="/admin/notifications/delete-recipient/<?= $r['id_recipient'] ?>" 
                                               class="btn btn-outline-danger btn-sm"
                                               onclick="return confirm('Hapus penerima ini?')"
                                               title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</main>
<script>
    // Konfirmasi sebelum hapus
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Hapus penerima ini?')) {
                e.preventDefault();
            }
        });
    });
    // Auto-hide alert
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => a.remove());
    }, 5000);
</script>
</body>
</html>
