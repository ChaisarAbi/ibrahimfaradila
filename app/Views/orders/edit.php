<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
            <h4 class="page-title"><i class="fas fa-edit me-2"></i>Edit Pesanan #<?= $order['id_order'] ?></h4>
            
            <div class="card">
                <div class="card-header"><h5>Form Edit Pesanan</h5></div>
                <div class="card-body">
                    <form action="/admin/orders/update/<?= $order['id_order'] ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success mb-3"><i class="fas fa-user me-2"></i>Data Pemesan</h6>
                                <input type="hidden" name="customer_id" value="<?= $order['customer_id'] ?>">
                                <div class="mb-3">
                                    <label class="form-label">Pelanggan</label>
                                    <select name="customer_id" class="form-control" id="customer_id">
                                        <option value="">-- Pilih Pelanggan --</option>
                                        <?php foreach ($customers as $c): ?>
                                        <option value="<?= $c['id_customer'] ?>" <?= $c['id_customer'] == $order['customer_id'] ? 'selected' : '' ?>>
                                            <?= esc($c['name']) ?> - <?= esc($c['child_name']) ?> (<?= esc($c['phone']) ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div id="edit_customer_fields">
                                    <hr>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Pemesan</label>
                                        <input type="text" name="customer_name" class="form-control" value="<?= $customer['name'] ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Anak</label>
                                        <input type="text" name="child_name" class="form-control" value="<?= $customer['child_name'] ?>">
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Jenis Kelamin</label>
                                            <select name="gender" class="form-control" id="gender">
                                                <option value="Laki-laki" <?= $customer['gender'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                                <option value="Perempuan" <?= $customer['gender'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="date" name="birth_date" class="form-control" value="<?= $customer['birth_date'] ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">No. Telepon</label>
                                        <input type="text" name="phone" class="form-control" value="<?= $customer['phone'] ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea name="address" class="form-control" rows="2"><?= $customer['address'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success mb-3"><i class="fas fa-box me-2"></i>Detail Pesanan</h6>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Scheduled" <?= $order['status'] == 'Scheduled' ? 'selected' : '' ?>>Scheduled</option>
                                        <option value="Processing" <?= $order['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                                        <option value="Completed" <?= $order['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="Cancelled" <?= $order['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Paket</label>
                                    <select name="package_id" class="form-control" id="package_id" required>
                                        <option value="">Pilih Paket</option>
                                        <?php foreach ($packages as $p): ?>
                                        <option value="<?= $p['id_package'] ?>" data-price="<?= $p['price'] ?>" data-boxes="<?= $p['box_count'] ?>" <?= $order['package_id'] == $p['id_package'] ? 'selected' : '' ?>>
                                            <?= $p['name'] ?> - Rp <?= number_format($p['price'], 0, ',', '.') ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Jenis Hewan</label>
                                        <select name="animal_type" class="form-control">
                                            <option value="Domba" <?= $order['animal_type'] == 'Domba' ? 'selected' : '' ?>>Domba</option>
                                            <option value="Kambing" <?= $order['animal_type'] == 'Kambing' ? 'selected' : '' ?>>Kambing</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Gender Hewan</label>
                                        <select name="animal_gender" class="form-control">
                                            <option value="Jantan" <?= $order['animal_gender'] == 'Jantan' ? 'selected' : '' ?>>Jantan</option>
                                            <option value="Betina" <?= $order['animal_gender'] == 'Betina' ? 'selected' : '' ?>>Betina</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal Potong</label>
                                        <input type="date" name="slaughter_date" class="form-control" value="<?= $order['slaughter_date'] ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal Antar</label>
                                        <input type="date" name="delivery_date" class="form-control" value="<?= $order['delivery_date'] ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Total Harga</label>
                                        <input type="text" class="form-control fw-bold text-success" id="display_price" value="Rp <?= number_format($order['total_price'], 0, ',', '.') ?>" readonly>
                                        <input type="hidden" name="total_price" id="total_price" value="<?= $order['total_price'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Pesanan</button>
                        <a href="/admin/orders" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
            <footer>&copy; <?= date('Y') ?> Ibrahim Aqiqah - Sistem Penjadwalan</footer>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#package_id').change(function() {
        var selected = $(this).find('option:selected');
        var price = selected.data('price') || 0;
        $('#total_price').val(price);
        $('#display_price').val('Rp ' + new Intl.NumberFormat('id-ID').format(price));
    });
});
</script>
</body>
</html>