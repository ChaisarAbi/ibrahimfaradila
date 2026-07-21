<?= view('templates/header') ?>
<?= view('templates/sidebar') ?>
<main class="main-content">
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item"><a href="/admin/orders">Pesanan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </nav>
            <h4 class="page-title mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Pesanan</h4>
            <small class="text-muted">Buat data pemesanan aqiqah baru</small>
        </div>
    </div>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger animate-slide-in"><i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header"><h5><i class="fas fa-shopping-cart me-2"></i>Form Pesanan Baru</h5></div>
        <div class="card-body">
            <form action="/admin/orders/store" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="row g-3">
                    <!-- Data Pemesan -->
                    <div class="col-md-6">
                        <div class="form-section-title">
                            <i class="fas fa-user"></i> Data Pemesan
                            <small>Identitas pelanggan</small>
                        </div>
                        
                        <div class="mb-2">
                            <label class="form-label">Pilih Pelanggan Existing</label>
                            <select name="customer_id" class="form-select form-select-sm" id="customer_id">
                                <option value="">-- Pelanggan Baru --</option>
                                <?php foreach ($customers as $c): ?>
                                <option value="<?= $c['id_customer'] ?>">
                                    <?= esc($c['name']) ?> - <?= esc($c['child_name']) ?> (<?= esc($c['phone']) ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div id="new_customer_fields" class="p-3 bg-light rounded-3" style="border:1px dashed #d0d7de;">
                            <p class="text-muted small mb-2"><i class="fas fa-plus-circle me-1"></i>Isi data pelanggan baru:</p>
                            <div class="mb-2">
                                <label class="form-label">Nama Pemesan</label>
                                <input type="text" name="customer_name" class="form-control form-control-sm">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Nama Anak</label>
                                <input type="text" name="child_name" class="form-control form-control-sm">
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="gender" class="form-select form-select-sm" id="gender">
                                        <option value="">Pilih</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="birth_date" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="phone" class="form-control form-control-sm">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detail Pesanan -->
                    <div class="col-md-6">
                        <div class="form-section-title">
                            <i class="fas fa-box"></i> Detail Pesanan
                            <small>Paket & jadwal</small>
                        </div>
                        
                        <div class="mb-2">
                            <label class="form-label">Paket</label>
                            <select name="package_id" class="form-select form-select-sm" id="package_id" required>
                                <option value="">Pilih Paket</option>
                                <?php foreach ($packages as $p): ?>
                                <option value="<?= $p['id_package'] ?>" data-price="<?= $p['price'] ?>" data-boxes="<?= $p['box_count'] ?>">
                                    <?= $p['name'] ?> - Rp <?= number_format($p['price'], 0, ',', '.') ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Hewan</label>
                                <select name="animal_type" class="form-select form-select-sm" required>
                                    <option value="Domba">Domba</option>
                                    <option value="Kambing">Kambing</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender Hewan</label>
                                <select name="animal_gender" class="form-select form-select-sm" required>
                                    <option value="Jantan">Jantan</option>
                                    <option value="Betina">Betina</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <label class="form-label">Jumlah Anak</label>
                                <input type="number" name="jumlah_anak" class="form-control form-control-sm" id="jumlah_anak" value="1" readonly>
                                <small class="text-muted">Laki=2, Perempuan=1</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Potong</label>
                                <input type="date" name="slaughter_date" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Antar</label>
                                <input type="date" name="delivery_date" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Penyembelihan</label>
                                <select name="penyembelihan" class="form-select form-select-sm" required>
                                    <option value="Dokumentasi">Dokumentasi</option>
                                    <option value="Video Call">Video Call</option>
                                    <option value="Visit">Visit</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end gap-3 pb-2">
                                <div class="form-check">
                                    <input type="checkbox" name="use_photo_card" class="form-check-input" value="1" id="use_card">
                                    <label class="form-check-label small" for="use_card">Kartu Foto</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="use_photo_certificate" class="form-check-input" value="1" id="use_cert">
                                    <label class="form-check-label small" for="use_cert">Sertifikat Foto</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Foto Anak (opsional)</label>
                            <input type="file" name="photo" class="form-control form-control-sm" accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted">Max 2MB. Format: JPG/PNG.</small>
                        </div>
                    </div>
                </div>

                <!-- Menu & Box -->
                <div class="form-section-title mt-3">
                    <i class="fas fa-utensils"></i> Menu & Box
                    <small>Pilihan menu dan kemasan</small>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-3">
                        <label class="form-label">Menu Tulang</label>
                        <select name="bone_menu_id" class="form-select form-select-sm">
                            <option value="">Pilih</option>
                            <?php foreach ($bone_menus as $b): ?>
                            <option value="<?= $b['id_bone'] ?>"><?= $b['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Menu Daging</label>
                        <select name="meat_menu_id" class="form-select form-select-sm">
                            <option value="">Pilih</option>
                            <?php foreach ($meat_menus as $m): ?>
                            <option value="<?= $m['id_meat'] ?>"><?= $m['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipe Box</label>
                        <select name="box_type" class="form-select form-select-sm" required>
                            <option value="Box Premium">Box Premium</option>
                            <option value="Bento Pack">Bento Pack</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jumlah Box</label>
                        <input type="number" name="jumlah_box" class="form-control form-control-sm" id="jumlah_box" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Total Harga</label>
                        <input type="text" class="form-control form-control-sm fw-bold text-success" id="display_price" readonly>
                        <input type="hidden" name="total_price" id="total_price">
                    </div>
                </div>
                
                <hr>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Pesanan</button>
                    <a href="/admin/orders" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Batal</a>
                </div>
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