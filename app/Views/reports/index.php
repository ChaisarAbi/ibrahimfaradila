<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><?= esc($title) ?></h4>
                <p class="text-muted mb-0">Kelola dan cetak laporan aqiqah</p>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-circle-icon bg-white bg-opacity-25">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <h6 class="fw-light mb-0" style="font-size:0.78rem;">Total Pesanan</h6>
                            <h4 class="fw-bold mb-0"><?= $total_orders ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success bg-gradient text-white">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-circle-icon bg-white bg-opacity-25">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h6 class="fw-light mb-0" style="font-size:0.78rem;">Selesai</h6>
                            <h4 class="fw-bold mb-0"><?= $completed_orders ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-warning bg-gradient text-white">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-circle-icon bg-white bg-opacity-25">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <h6 class="fw-light mb-0" style="font-size:0.78rem;">Hari Ini</h6>
                            <h4 class="fw-bold mb-0"><?= $today_orders ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Laporan -->
        <div class="row g-2">
            <!-- Sertifikat -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm report-card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <div class="report-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Sertifikat Aqiqah</h6>
                                <small class="text-muted">PDF per order</small>
                            </div>
                        </div>
                        <p class="text-muted small flex-grow-1 mb-2">Cetak sertifikat aqiqah berisi data pemesan, anak, hewan, dan jadwal pemotongan.</p>
                        <form action="/admin/reports/certificate" method="get" class="d-flex gap-2">
                            <input type="number" class="form-control form-control-sm" name="id_order" placeholder="ID Order" required>
                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-print me-1"></i> Cetak</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Undangan -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm report-card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <div class="report-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-envelope-open-text"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Undangan Aqiqah</h6>
                                <small class="text-muted">PDF per order</small>
                            </div>
                        </div>
                        <p class="text-muted small flex-grow-1 mb-2">Cetak undangan aqiqah elektronik dengan desain elegan, siap dibagikan ke tamu.</p>
                        <form action="/admin/reports/invitation" method="get" class="d-flex gap-2">
                            <input type="number" class="form-control form-control-sm" name="id_order" placeholder="ID Order" required>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-print me-1"></i> Cetak</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Detail Pemesanan -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm report-card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <div class="report-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Detail Pemesanan</h6>
                                <small class="text-muted">PDF per order</small>
                            </div>
                        </div>
                        <p class="text-muted small flex-grow-1 mb-2">Cetak detail lengkap pemesanan termasuk menu, box, dan info customer.</p>
                        <form action="/admin/reports/detail-pemesanan" method="get" class="d-flex gap-2">
                            <input type="number" class="form-control form-control-sm" name="id_order" placeholder="ID Order" required>
                            <button type="submit" class="btn btn-info text-white btn-sm"><i class="fas fa-print me-1"></i> Cetak</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Laporan Order -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm report-card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <div class="report-icon bg-secondary bg-opacity-10 text-secondary">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Laporan Order</h6>
                                <small class="text-muted">PDF per tanggal</small>
                            </div>
                        </div>
                        <p class="text-muted small flex-grow-1 mb-2">Cetak rekap order berdasarkan rentang tanggal pemotongan (mingguan/bulanan).</p>
                        <form action="/admin/reports/order-report" method="get" class="row g-1">
                            <div class="col-6">
                                <input type="date" class="form-control form-control-sm" name="start_date" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control form-control-sm" name="end_date" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-secondary btn-sm w-100"><i class="fas fa-print me-1"></i> Cetak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Lembar Kerja Dapur -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm report-card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <div class="report-icon bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Lembar Kerja Dapur</h6>
                                <small class="text-muted">PDF harian</small>
                            </div>
                        </div>
                        <p class="text-muted small flex-grow-1 mb-2">Cetak lembar kerja tim dapur berisi daftar pesanan, menu, box, dan kebutuhan stok.</p>
                        <form action="/admin/reports/kitchen-sheet" method="get" class="d-flex gap-2">
                            <input type="date" class="form-control form-control-sm" name="date" value="<?= date('Y-m-d') ?>" required>
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-print me-1"></i> Cetak</button>
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