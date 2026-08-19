# Panduan Pull Update di VPS (Sudah Deploy)

## Langkah Pull Update Terbaru

```bash
# 1. Masuk ke direktori project
cd /var/www/aqiqah

# 2. Ambil perubahan terbaru dari GitHub
git pull origin main

# 3. Install/update dependencies PHP (jika ada perubahan di composer.json)
composer install --no-dev --optimize-autoloader

# 4. Jika ada perubahan database schema, jalankan migration
php spark migrate --current

# 5. Clear cache aplikasi
php spark cache:clear

# 6. Restart PHP-FPM (jika ada perubahan konfigurasi PHP)
sudo systemctl restart php8.1-fpm
```

## Jika Ada Perubahan File Penting

Setelah `git pull`, cek file yang berubah:
```bash
git diff HEAD~1 --name-only
```

File yang perlu diperhatikan setelah pull:
- **`.env`** → JANGAN di-overwrite! Gunakan `.env.example` sebagai referensi jika perlu update variabel baru
- **`app/Config/*.php`** → Jika ada perubahan config, merge manual (bukan replace)
- **Public files** → Pastikan assets terakses

## Checklist Setelah Pull

- [ ] `git pull origin main` selesai tanpa error
- [ ] `composer install` dijalankan (jika ada perubahan composer.json)
- [ ] `php spark migrate --current` dijalankan (jika ada migration baru)
- [ ] `php spark cache:clear` dijalankan
- [ ] Akses website untuk testing
- [ ] Test fitur login
- [ ] Test upload foto
- [ ] Test generate sertifikat
- [ ] Cek log: `tail -f writable/logs/log.txt`

## Troubleshooting Saat Pull

### Konflik merge
```bash
# Lihat file yang konflik
git status

# Edit file yang konflik, lalu:
git add <file-yang-sudah-dimerge>
git commit -m "Fix merge conflict"
```

### Error permission setelah pull
```bash
sudo chmod -R 755 writable/ uploads/
sudo chown -R www-data:www-data /var/www/aqiqah/
```

### Website error blank page
```bash
# Cek log
tail -f writable/logs/log.txt

# Clear cache
php spark cache:clear

# Check environment mode
grep 'app.env' .env
```

---

## Ringkasan Cepat (One-Liner)

```bash
cd /var/www/aqiqah && git pull origin main && composer install --no-dev --optimize-autoloader && php spark migrate --current && php spark cache:clear && sudo systemctl restart php8.1-fpm