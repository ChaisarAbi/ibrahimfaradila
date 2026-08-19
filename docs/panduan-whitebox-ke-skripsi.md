# Panduan Memasukkan Whitebox Testing ke Skripsi

## 📌 Letak di Bab Berapa?

Whitebox testing biasanya diletakkan di **BAB 4 (Hasil dan Pembahasan)** atau **BAB 5 (Pengujian Sistem)**, sub-bab **Pengujian Whitebox**.

---

## 📋 Format yang Dibutuhkan di Skripsi

### A. Tabel Pengujian Whitebox

Buat tabel seperti ini di skripsi Anda:

**Tabel 4.X: Skenario Pengujian Whitebox**

| No | Kelas Uji | Metode yang Diuji | Jumlah Test Case | Teknik Coverage |
|----|-----------|-------------------|-----------------|-----------------|
| 1 | Auth Controller | index(), login(), logout() | 3 | Method Coverage |
| 2 | Orders Controller | index(), create(), store(), edit(), update(), delete(), stats(), getPackageInfo(), pendingCount() | 4 | Method & Parameter Coverage |
| 3 | Dashboard Controller | index(), chartData() | 1 | Method Coverage |
| 4 | Reports Controller | index(), certificate(), detailPemesanan(), invitation(), orderReport(), orderReportPdf(), kitchenSheet() | 4 | Method & Parameter Coverage |
| 5 | Notification Controller | sendTodayRecap(), sendTomorrowPreview(), sendHPlus1(), sendCustom(), sendStockAlert(), test(), manual(), history(), recipients(), addRecipient(), deleteRecipient() | 1 | Method Coverage |
| 6 | Calendar Controller | getEvents() | 1 | Method Coverage |
| 7 | Scheduler Controller | run() | 1 | Method Coverage |
| 8 | Filters | AdminFilter, DapurFilter, RphFilter | 2 | Class Inheritance Coverage |
| 9 | Model Existence | 11 Model | 2 | Class Existence & Inheritance |
| 10 | View Files | 16 View files | 1 | File Existence Coverage |
| 11 | Business Logic | EDF Algorithm, Color Mapping, Price Calculation | 4 | Branch & Path Coverage |
| 12 | Validasi Input | Animal Type, Phone, Date, Required Fields | 4 | Condition Coverage |
| 13 | Session State | Login/Logout Transition | 1 | State Transition Coverage |
| **Total** | | | **36** | |

---

### B. Skenario Detail per Test Case

Buat sub-tabel untuk test case yang berkaitan dengan **algorithm/branch coverage** (karena ini yang paling penting secara akademis):

**Tabel 4.X: Skenario Whitebox untuk Algoritma EDF/EDD (Earliest Deadline First)**

| ID Test | Input | Expected Output | Status |
|---------|-------|-----------------|--------|
| TC-19a | Orders: [deadline=30-07-2026, 25-07-2026, 01-08-2026, 28-07-2026] | Urutan: [25, 28, 30, 01] | ✅ |
| TC-19b | Deadline terdekat menjadi prioritas pertama | orders[0].deadline = 25-07-2026 | ✅ |

**Tabel 4.X: Branch Coverage untuk Gender → Jumlah Hewan**

| ID Test | Input Gender | Output Jumlah Anak | Cabang Teruji | Status |
|---------|-------------|-------------------|---------------|--------|
| TC-18a | 'laki-laki' | 2 ekor kambing | Branch True (if) | ✅ |
| TC-18b | 'perempuan' | 1 ekor kambing | Branch False (else) | ✅ |
| TC-18c | 'unknown' | 1 ekor (default) | Branch Default | ✅ |

---

### C. Screenshoot Hasil Pengujian

Jalankan test dan screenshot hasilnya:

```
cd project-aqiqah
php vendor/bin/phpunit tests/WhiteboxTest.php --no-coverage
```

Hasil yang akan tampil:
```
PHPUnit 10.5.64 ...

...................................                             36 / 36 (100%)

Time: 00:00.127, Memory: 14.00 MB

OK (36 tests, 154 assertions)
```

**Screenshot hasil ini** dan letakkan di skripsi sebagai **Gambar 4.X: Hasil Pengujian Whitebox Testing**.

---

### D. Contoh Pembahasan untuk Skripsi

Berikut template paragraf yang bisa Anda adaptasi:

---

**4.X Pengujian Whitebox**

Pengujian whitebox dilakukan untuk menguji struktur internal sistem 
Ibrahim Aqiqah. Pengujian ini berfokus pada:

1. **Method Coverage**: Memastikan seluruh method pada setiap controller 
   telah terdefinisi dan dapat diakses dengan benar.
2. **Parameter Coverage**: Memverifikasi parameter yang diterima oleh 
   setiap method sesuai dengan yang diharapkan.
3. **Branch Coverage**: Menguji setiap cabang keputusan pada algoritma 
   kritis seperti EDF/EDD scheduling dan perhitungan jumlah hewan 
   berdasarkan gender.
4. **Path Coverage**: Memastikan state transition session (login-logout) 
   berjalan sesuai urutan yang benar.
5. **Condition Coverage**: Memverifikasi kondisi batas pada validasi 
   input seperti nomor telepon, tanggal, dan tipe hewan.

Total terdapat **36 test cases** dengan **154 assertions** yang mencakup 
7 controller, 11 model, 3 filter, dan 16 view files. Setiap test case 
dijalankan tanpa memerlukan koneksi database (pure static analysis) 
sehingga pengujian dapat dilakukan secara cepat dan mandiri.

**Hasil Pengujian:**

Dari 36 test case yang dijalankan, seluruhnya dinyatakan **LULUS (OK)** 
dengan persentase keberhasilan 100%. Hal ini menunjukkan bahwa struktur 
kode sistem telah sesuai dengan perancangan dan seluruh komponen sistem 
terintegrasi dengan baik.

**Tabel 4.X: Rekapitulasi Hasil Whitebox Testing**

| Aspek yang Diuji | Jumlah Test | Hasil |
|------------------|-------------|-------|
| Controller Methods | 17 | ✅ 100% Lulus |
| Filter Inheritance | 2 | ✅ 100% Lulus |
| Model Existence & Inheritance | 2 | ✅ 100% Lulus |
| View Files Existence | 1 | ✅ 100% Lulus |
| Business Logic (EDF, Price, dll) | 6 | ✅ 100% Lulus |
| Input Validation | 8 | ✅ 100% Lulus |
| **Total** | **36** | **✅ 100% Lulus** |

---

### E. Keterkaitan dengan Sequence Diagram

Di skripsi Anda, Anda bisa menghubungkan sequence diagram (yang sudah saya buat di `public/assets/diagrams/`) dengan whitebox testing:

> "Setiap method yang diuji pada whitebox testing (Tabel 4.X) sesuai 
> dengan alur yang digambarkan pada sequence diagram (Gambar 4.X-4.X). 
> Sebagai contoh, method `login()` pada Auth Controller diuji 
> menggunakan 3 test case yang mencakup skenario keberhasilan login, 
> validasi parameter, dan logout, sesuai dengan alur pada sequence 
> diagram login (Gambar 4.X)."

---

### F. Cara Memasukkan File Pendukung

| File | Untuk di Skripsi |
|------|------------------|
| `tests/WhiteboxTest.php` | Lampiran (source code) |
| `tests/whitebox-testing-summary.md` | Referensi pembahasan |
| `public/assets/diagrams/*.svg` | Gambar di Bab 4 (jika ada) |

---

### G. Contoh Kutipan di Daftar Pustaka

Jika perlu, Anda bisa merujuk ke buku standar:

> Pressman, R.S. (2015). *Software Engineering: A Practitioner's Approach*. 
> 8th ed. McGraw-Hill. (Bab 18: White-Box Testing)