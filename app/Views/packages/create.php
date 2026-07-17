<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
            <h4 class="page-title"><i class="fas fa-plus-circle me-2"></i>Tambah Paket</h4>
            <div class="card">
                <div class="card-header"><h5>Form Paket</h5></div>
                <div class="card-body">
                    <form action="/admin/packages/store" method="post">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Paket</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tipe Berat</label>
                                    <select name="weight_type" class="form-control" required>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Min Berat (kg)</label>
                                        <input type="number" step="0.01" name="min_weight" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Max Berat (kg)</label>
                                        <input type="number" step="0.01" name="max_weight" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Box</label>
                                    <input type="number" name="box_count" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Harga (Rp)</label>
                                    <input type="number" name="price" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_special" class="form-check-input" value="1" id="is_special">
                                        <label class="form-check-label" for="is_special">Paket Special</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                        <a href="/admin/packages" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
            <footer>&copy; <?= date('Y') ?> Ibrahim Aqiqah - Sistem Penjadwalan</footer>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>