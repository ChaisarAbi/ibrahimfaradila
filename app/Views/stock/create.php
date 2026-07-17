<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
            <h4 class="page-title"><i class="fas fa-plus-circle me-2"></i>Tambah Stok</h4>
            <div class="card">
                <div class="card-header"><h5>Form Tambah Stok</h5></div>
                <div class="card-body">
                    <form action="/admin/stock/store" method="post">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Item</label>
                                <input type="text" name="item_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category" class="form-control" required>
                                    <option value="hewan">Hewan</option>
                                    <option value="bahan">Bahan Baku</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="quantity" class="form-control" value="0" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Min Threshold</label>
                                <input type="number" name="min_threshold" class="form-control" value="5" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Satuan</label>
                                <select name="unit" class="form-control" required>
                                    <option value="ekor">Ekor</option>
                                    <option value="kg">Kilogram</option>
                                    <option value="pcs">Pcs</option>
                                    <option value="pack">Pack</option>
                                    <option value="liter">Liter</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                        <a href="/admin/stock" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
            <footer>&copy; <?= date('Y') ?> Ibrahim Aqiqah - Sistem Penjadwalan</footer>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>