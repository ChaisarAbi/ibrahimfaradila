<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1"><?= esc($title) ?></h4>
            <p class="text-muted mb-0" style="font-size:0.85rem;">Kirim notifikasi Telegram secara manual</p>
        </div>
        <a href="/admin/notifications/history" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-history me-1"></i> Riwayat
        </a>
    </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-2">
            <!-- Rekap Hari Ini -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm report-card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <div class="report-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Rekap Hari Ini</h6>
                                <small class="text-muted">Pemotongan hari ini</small>
                            </div>
                        </div>
                        <p class="text-muted small flex-grow-1 mb-2">Rekap lengkap total pesanan, box, menu tulang/daging, dan status stok terkini.</p>
                        <a href="/admin/notifications/send-recap-today" class="btn btn-success btn-sm w-100" onclick="return confirm('Kirim rekap hari ini?')">
                            <i class="fas fa-paper-plane me-1"></i> Kirim Rekap
                        </a>
                    </div>
                </div>
            </div>

            <!-- Preview Besok -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm report-card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <div class="report-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Preview Besok</h6>
                                <small class="text-muted">Jadwal pemotongan besok</small>
                            </div>
                        </div>
                        <p class="text-muted small flex-grow-1 mb-2">Preview detail pesanan besok termasuk menu, alamat, jam potong, dan fitur tambahan.</p>
                        <a href="/admin/notifications/send-preview-tomorrow" class="btn btn-warning btn-sm w-100" onclick="return confirm('Kirim preview besok?')">
                            <i class="fas fa-paper-plane me-1"></i> Kirim Preview
                        </a>
                    </div>
                </div>
            </div>

            <!-- Follow-up H+1 -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm report-card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <div class="report-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Follow-up H+1</h6>
                                <small class="text-muted">Setelah pemotongan</small>
                            </div>
                        </div>
                        <p class="text-muted small flex-grow-1 mb-2">Notifikasi follow-up untuk pesanan yang sudah dipotong kemarin, termasuk info pengantaran.</p>
                        <a href="/admin/notifications/send-hplus1" class="btn btn-info btn-sm text-white w-100" onclick="return confirm('Kirim follow-up H+1?')">
                            <i class="fas fa-paper-plane me-1"></i> Kirim Follow-up
                        </a>
                    </div>
                </div>
            </div>

            <!-- Alert Stok -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm report-card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <div class="report-icon bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Alert Stok</h6>
                                <small class="text-muted">Status stok terkini</small>
                            </div>
                        </div>
                        <p class="text-muted small flex-grow-1 mb-2">Notifikasi status semua item stok dan peringatan untuk stok yang menipis.</p>
                        <a href="/admin/notifications/send-stock-alert" class="btn btn-danger btn-sm w-100" onclick="return confirm('Kirim alert stok?')">
                            <i class="fas fa-paper-plane me-1"></i> Kirim Alert
                        </a>
                    </div>
                </div>
            </div>

            <!-- Uji Coba -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm report-card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <div class="report-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-vial"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Uji Coba Notifikasi</h6>
                                <small class="text-muted">Tes bot Telegram</small>
                            </div>
                        </div>
                        <p class="text-muted small flex-grow-1 mb-2">Kirim pesan uji coba untuk memastikan bot Telegram berfungsi dengan baik.</p>
                        <a href="/admin/notifications/test" class="btn btn-outline-primary btn-sm w-100" onclick="return confirm('Kirim uji coba notifikasi?')">
                            <i class="fas fa-paper-plane me-1"></i> Kirim Uji Coba
                        </a>
                    </div>
                </div>
            </div>

            <!-- Notifikasi Umum -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm report-card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <div class="report-icon bg-secondary bg-opacity-10 text-secondary">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Notifikasi Umum</h6>
                                <small class="text-muted">Pesan custom</small>
                            </div>
                        </div>
                        <form action="/admin/notifications/send-custom" method="post" class="flex-grow-1 d-flex flex-column">
                            <?= csrf_field() ?>
                            <div class="mb-2 flex-grow-1">
                                <textarea class="form-control form-control-sm" id="message" name="message" rows="2" placeholder="Tulis pesan yang akan dikirim..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100" onclick="return confirm('Kirim notifikasi umum?')">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer>&copy; <?= date('Y') ?> Ibrahim Aqiqah - Sistem Penjadwalan</footer>
</main>
</body>
</html>