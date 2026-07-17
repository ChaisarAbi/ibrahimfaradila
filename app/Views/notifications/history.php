<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
            <h4 class="page-title"><i class="fas fa-bell me-2"></i>Riwayat Notifikasi</h4>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Notifikasi Telegram</h5>
                    <a href="/admin/notifications/send-reminders" class="btn btn-success btn-sm" onclick="return confirm('Kirim pengingat 24 jam untuk pesanan besok?')">
                        <i class="fas fa-paper-plane me-1"></i>Kirim Pengingat 24 Jam
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>#</th><th>Tipe</th><th>Pesan</th><th>Dikirim</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach ($notifications as $n): ?>
                                <tr>
                                    <td><?= $n['id_notif'] ?></td>
                                    <td><span class="badge bg-info"><?= $n['type'] ?></span></td>
                                    <td><?= substr($n['message'], 0, 50) ?>...</td>
                                    <td><?= $n['sent_at'] ?></td>
                                    <td><?= $n['is_read'] ? '<span class="badge bg-success">Dibaca</span>' : '<span class="badge bg-secondary">Baru</span>' ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($notifications)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada notifikasi</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>