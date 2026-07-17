<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
            <h4 class="page-title"><i class="fas fa-plus-circle me-2"></i>Tambah Pesanan</h4>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header"><h5>Form Pesanan</h5></div>
                <div class="card-body">
                    <form action="/admin/orders/store" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success mb-3"><i class="fas fa-user me-2"></i>Data Pemesan</h6>
                                <div class="mb-3">
                                    <label class="form-label">Pilih Pelanggan Existing</label>
                                    <select name="customer_id" class="form-control" id="customer_id">
                                        <option value="">-- Pelanggan Baru --</option>
                                        <?php foreach ($customers as $c): ?>
                                        <option value="<?= $c['id_customer'] ?>">
                                            <?= esc($c['name']) ?> - <?= esc($c['child_name']) ?> (<?= esc($c['phone']) ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <hr>
                                <div id="new_customer_fields">
                                    <p class="text-muted small">Atau isi data pelanggan baru di bawah ini:</p>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Pemesan</label>
                                        <input type="text" name="customer_name" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Anak</label>
                                        <input type="text" name="child_name" class="form-control">
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Jenis Kelamin</label>
                                            <select name="gender" class="form-control" id="gender">
                                                <option value="">Pilih</option>
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="date" name="birth_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">No. Telepon</label>
                                        <input type="text" name="phone" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea name="address" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success mb-3"><i class="fas fa-box me-2"></i>Detail Pesanan</h6>
                                <div class="mb-3">
                                    <label class="form-label">Paket</label>
                                    <select name="package_id" class="form-control" id="package_id" required>
                                        <option value="">Pilih Paket</option>
                                        <?php foreach ($packages as $p): ?>
                                        <option value="<?= $p['id_package'] ?>" data-price="<?= $p['price'] ?>" data-boxes="<?= $p['box_count'] ?>">
                                            <?= $p['name'] ?> - Rp <?= number_format($p['price'], 0, ',', '.') ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Jenis Hewan</label>
                                        <select name="animal_type" class="form-control" required>
                                            <option value="Domba">Domba</option>
                                            <option value="Kambing">Kambing</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Gender Hewan</label>
                                        <select name="animal_gender" class="form-control" required>
                                            <option value="Jantan">Jantan</option>
                                            <option value="Betina">Betina</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Jumlah (Otomatis)</label>
                                        <input type="number" name="jumlah_anak" class="form-control" id="jumlah_anak" value="1" readonly>
                                        <small class="text-muted">Laki=2, Perempuan=1</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal Potong</label>
                                        <input type="date" name="slaughter_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal Antar</label>
                                        <input type="date" name="delivery_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Penyembelihan</label>
                                    <select name="penyembelihan" class="form-control" required>
                                        <option value="Dokumentasi">Dokumentasi</option>
                                        <option value="Video Call">Video Call</option>
                                        <option value="Visit">Visit</option>
                                    </select>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" name="use_photo_card" class="form-check-input" value="1" id="use_card">
                                            <label class="form-check-label" for="use_card">Kartu Foto</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" name="use_photo_certificate" class="form-check-input" value="1" id="use_cert">
                                            <label class="form-check-label" for="use_cert">Sertifikat Foto</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Foto Anak (opsional)</label>
                                    <input type="file" name="photo" class="form-control">
                                </div>
                                
                                <hr>
                                <h6 class="fw-bold text-success mb-3"><i class="fas fa-utensils me-2"></i>Menu & Box</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Menu Tulang</label>
                                        <select name="bone_menu_id" class="form-control">
                                            <option value="">Pilih</option>
                                            <?php foreach ($bone_menus as $b): ?>
                                            <option value="<?= $b['id_bone'] ?>"><?= $b['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Menu Daging</label>
                                        <select name="meat_menu_id" class="form-control">
                                            <option value="">Pilih</option>
                                            <?php foreach ($meat_menus as $m): ?>
                                            <option value="<?= $m['id_meat'] ?>"><?= $m['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Tipe Box</label>
                                        <select name="box_type" class="form-control" required>
                                            <option value="Box Premium">Box Premium</option>
                                            <option value="Bento Pack">Bento Pack</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Jumlah Box</label>
                                        <input type="number" name="jumlah_box" class="form-control" id="jumlah_box" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Total Harga</label>
                                        <input type="text" class="form-control fw-bold text-success" id="display_price" readonly>
                                        <input type="hidden" name="total_price" id="total_price">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Pesanan</button>
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
    // Autofill jumlah anak based on gender
    $('#gender').change(function() {
        if ($(this).val() === 'Laki-laki') {
            $('#jumlah_anak').val(2);
        } else {
            $('#jumlah_anak').val(1);
        }
    });
    
    // Toggle new customer fields based on selection
    $('#customer_id').change(function() {
        if ($(this).val() !== '') {
            // Existing customer selected - disable new customer fields
            $('#new_customer_fields input, #new_customer_fields select, #new_customer_fields textarea').prop('disabled', true);
        } else {
            // New customer - enable fields
            $('#new_customer_fields input, #new_customer_fields select, #new_customer_fields textarea').prop('disabled', false);
        }
    });
    // Trigger on page load
    $('#customer_id').trigger('change');
    
    // Auto-update price & boxes when package selected
    $('#package_id').change(function() {
        var selected = $(this).find('option:selected');
        var price = selected.data('price') || 0;
        var boxes = selected.data('boxes') || 0;
        $('#jumlah_box').val(boxes);
        $('#total_price').val(price);
        $('#display_price').val('Rp ' + new Intl.NumberFormat('id-ID').format(price));
    });
});
</script>
</body>
</html>