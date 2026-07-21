<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
            <h4 class="page-title"><i class="fas fa-bell me-2"></i>Riwayat Notifikasi</h4>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Notifikasi Telegram</h5>
                    <div class="btn-group" role="group">
                        <a href="/admin/notifications/send-recap-today" class="btn btn-success btn-sm" onclick="return confirm('Kirim rekap pemotongan hari ini ke Telegram?')">
                            <i class="fas fa-chart-pie me-1"></i>Rekap Hari Ini
                        </a>
                        <a href="/admin/notifications/send-preview-tomorrow" class="btn btn-warning btn-sm text-white" onclick="return confirm('Kirim preview jadwal besok ke Telegram?')">
                            <i class="fas fa-calendar-day me-1"></i>Preview Besok
                        </a>
                        <a href="/admin/notifications/send-stock-alert" class="btn btn-danger btn-sm text-white" onclick="return confirm('Kirim notifikasi stok terkini ke Telegram?')">
                            <i class="fas fa-box me-1"></i>Alert Stok
                        </a>
                        <a href="/admin/notifications/test" class="btn btn-secondary btn-sm" onclick="return confirm('Kirim notifikasi uji coba ke Telegram?')">
                            <i class="fas fa-flask me-1"></i>Uji Coba
                        </a>
                    </div>
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