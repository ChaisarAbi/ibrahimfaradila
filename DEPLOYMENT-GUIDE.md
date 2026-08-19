# Deployment Guide - Sistem Manajemen Aqiqah/Akita Alaq

## 📋 Daftar Isi
1. [Persiapan Server VPS](#persiapan-server-vps)
2. [Step-by-Step Pull dari GitHub](#step-by-step-pull-dari-github)
3. [Konfigurasi Environment](#konfigurasi-environment)
4. [Database Setup](#database-setup)
5. [File Permissions](#file-permissions)
6. [Running Services](#running-services)
7. [Troubleshooting](#troubleshooting)

---

## Persiapan Server VPS

### Spesifikasi Minimum
- **OS**: Ubuntu 20.04/22.04 LTS atau CentOS 8+
- **RAM**: 1GB minimum
- **PHP**: 8.0+ (dengan extensions: GD, PDO MySQL, mbstring, intl, xml)
- **Web Server**: Nginx/Apache
- **Database**: MySQL 5.7+ / MariaDB 10.5+
- **Composer**: Latest version

### Instalasi Stack LEMP/LAMP

#### Ubuntu/Debian
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Nginx, MySQL, PHP 8.x
sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql php8.1-common php8.1-mbstring php8.1-xml php8.1-gd php8.1-intl -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Git
sudo apt install git -y
```

#### CentOS/RHEL
```bash
# Update system
sudo yum update -y

# Install EPEL repository
sudo yum install epel-release -y

# Install stack packages
sudo yum install nginx mysql-server php php-fpm php-mysqlnd php-mbstring php-xml php-gd php-intl git composer -y

# Start services
sudo systemctl start nginx
sudo systemctl start mysqld
sudo systemctl start php-fpm
```

---

## Step-by-Step Pull dari GitHub

### 1. Clone Repository
```bash
# Buat direktori project
cd /var/www

# Clone repository (ganti URL dengan repository Anda)
git clone https://github.com/USERNAME/REPOSITORY.git aqiqah
cd aqiqah
```

### 2. Setup Branch
```bash
# Lihat branch yang tersedia
git branch -a

# Checkout branch production (atau develop untuk testing)
git checkout main

# Pull update terbaru
git pull origin main
```

### 3. Install Dependencies
```bash
# Install PHP dependencies via Composer
composer install --no-dev --optimize-autoloader

# Jika ada Node.js dependencies (opsional)
# npm install
```

### 4. Konfigurasi Environment

#### Copy dan Edit File Env
```bash
# Copy file contoh environment
cp env .env

# Edit file .env
nano .env
```

#### Konfigurasi `.env` yang Benar
```env
#--------------------------------------------------------------------
# BASE APP CONFIG
#--------------------------------------------------------------------
app.baseURL = 'https:// Domain-anda.com'
app.env = production

#--------------------------------------------------------------------
# DATABASE CONNECTIONS
#--------------------------------------------------------------------
database.default.hostname = localhost
database.default.database = aqiqah_db
database.default.username = aqiqah_user
database.default.password = PASSWORD_YANG_KUAT
database.default.DBDriver = MySQLi
database.default.DBPrefix =

#--------------------------------------------------------------------
# ENERGY CORE CONFIG (Dynamic Config System)
#--------------------------------------------------------------------
energycore.db.hostname = localhost
energycore.db.database = aqiqah_db
energycore.db.username = aqiqah_user
energycore.db.password = PASSWORD_YANG_KUAT
energycore.db.DBDriver = MySQLi

#--------------------------------------------------------------------
# FILE UPLOAD PATHS
#--------------------------------------------------------------------
# Pastikan path ini writable oleh web server
uploads.path = /var/www/aqiqah/uploads
photoPath = {uploads.path}/photos

#--------------------------------------------------------------------
# DOMPDF CONFIG (Untuk Generate Sertifikat)
#--------------------------------------------------------------------
DOMPDF_ENABLE_CSS_FLOAT = true
DOMPDF_ENABLE_HTML5PARSER = true
DOMPDF_DEBUG_PDF_INFO = false
```

### 5. Database Setup

#### Buat Database dan User
```bash
# Login ke MySQL
mysql -u root -p

# Jalankan SQL berikut di dalam MySQL
CREATE DATABASE aqiqah_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'aqiqah_user'@'localhost' IDENTIFIED BY 'PASSWORD_YANG_KUAT';
GRANT ALL PRIVILEGES ON aqiqah_db.* TO 'aqiqah_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Import Database Schema
```bash
# Import dari file database.sql
mysql -u aqiqah_user -p aqiqah_db < database.sql

# Atau jika pakai seeder
php spark db:seed AqiqahSeeder
```

### 6. File Permissions
```bash
# Set permissions untuk writable directories
sudo chmod -R 755 writable/
sudo chmod -R 755 uploads/
sudo chmod 644 .env

# Set owner ke web server (Nginx: www-data, Apache: apache)
sudo chown -R www-data:www-data /var/www/aqiqah/

# Untuk CentOS (Apache owner adalah apache)
sudo chown -R apache:apache /var/www/aqiqah/
```

### 7. Web Server Configuration

#### Nginx Configuration
```bash
sudo nano /etc/nginx/sites-available/aqiqah
```

```nginx
server {
    listen 80;
    server_name domain-anda.com;
    
    root /var/www/aqiqah/public;
    index index.php;
    
    # Increase upload size for photos
    client_max_body_size 10M;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Deny access to hidden files
    location ~ /\. {
        deny all;
    }
    
    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/aqiqah /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### Apache Configuration
```bash
sudo nano /etc/apache2/sites-available/aqiqah.conf
```

```apache
<VirtualHost *:80>
    ServerName domain-anda.com
    DocumentRoot /var/www/aqiqah/public
    
    <Directory /var/www/aqiqah/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Increase upload size
    php_value upload_max_filesize 10M
    php_value post_max_size 10M
</VirtualHost>
```

Enable site:
```bash
sudo a2ensite aqiqah.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 8. SSL Certificate (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Get certificate
sudo certbot --nginx -d domain-anda.com

# Auto-renewal is configured automatically
```

### 9. Running Services

#### Start dan Enable Services
```bash
# Nginx
sudo systemctl enable nginx
sudo systemctl start nginx

# PHP-FPM
sudo systemctl enable php8.1-fpm
sudo systemctl start php8.1-fpm

# MySQL
sudo systemctl enable mysql
sudo systemctl start mysql

# Check status
sudo systemctl status nginx
sudo systemctl status php8.1-fpm
sudo systemctl status mysql
```

#### Cron Jobs untuk Notifikasi Otomatis
```bash
# Edit crontab
crontab -e

# Tambahkan baris berikut untuk notifikasi terjadwal
# Jalankan setiap jam pada menit ke-0
0 * * * * /usr/bin/php /var/www/aqiqah/spark notifications:send >> /var/www/aqiqah/writable/logs/cron.log 2>&1

# Opsi: Jalankan backup database setiap hari jam 2 pagi
0 2 * * * mysqldump -u aqiqah_user -p'PASSWORD' aqiqah_db > /backup/aqiqah_$(date +\%Y\%m\%d).sql 2>&1
```

---

## Troubleshooting

### 1. Sertifikat tidak muncul (blank/kosong)
```bash
# Cek apakah GD extension aktif
php -m | grep gd

# Jika belum, install:
sudo apt install php8.1-gd
sudo systemctl restart php8.1-fpm

# Cek permissions folder writable
ls -la writable/
chmod -R 755 writable/
```

### 2. Error "Class 'Dompdf\Dompdf' not found"
```bash
# Reinstall dependencies
composer install --no-dev --optimize-autoloader

# Clear cache
php spark cache:clear
```

### 3. Database connection error
```bash
# Cek MySQL running
sudo systemctl status mysql

# Test connection
mysql -u aqiqah_user -p -h localhost

# Cek konfigurasi di .env
cat .env | grep database
```

### 4. Permission denied untuk upload
```bash
# Set ownership yang benar
sudo chown -R www-data:www-data /var/www/aqiqah/uploads
sudo chmod -R 755 /var/www/aqiqah/uploads

# Jika menggunakan SELinux (CentOS)
sudo setsebool -P httpd_can_network_connect 1
sudo semanage fcontext -a -t httpd_sys_rw_content_t "/var/www/aqiqah/uploads(/.*)?"
sudo restorecon -R /var/www/aqiqah/uploads
```

### 5. Log Files untuk Debugging
```bash
# Application logs
tail -f writable/logs/log.txt

# Nginx error logs
sudo tail -f /var/log/nginx/error.log

# Apache error logs
sudo tail -f /var/log/apache2/error.log

# MySQL error logs
sudo tail -f /var/log/mysql/error.log
```

### 6. Cek PHP Error
```bash
# Lihat error PHP
sudo tail -f /var/log/php8.1-fpm.log

# Test PHP configuration
php -i | grep upload_max_filesize
php -i | memory_limit
```

---

## Checklist Deployment

- [ ] Server siap (LEMP/LAMP stack terinstall)
- [ ] Git terinstall
- [ ] Composer terinstall
- [ ] Repository di-clone
- [ ] `composer install` dijalankan
- [ ] File `.env` dikonfigurasi
- [ ] Database dibuat dan di-import
- [ ] User database dibuat dengan akses yang tepat
- [ ] File permissions diset (writable/, uploads/)
- [ ] Web server dikonfigurasi (Nginx/Apache)
- [ ] SSL certificate di-install (optional tapi direkomendasikan)
- [ ] Services di-start dan di-enable
- [ ] Cron jobs dikonfigurasi (untuk notifikasi otomatis)
- [ ] Testing akses website
- [ ] Testing login
- [ ] Testing upload foto
- [ ] Testing generate sertifikat
- [ ] Testing notification

---

## Quick Commands Reference

```bash
# Deploy terbaru
cd /var/www/aqiqah
git pull origin main
composer install --no-dev --optimize-autoloader
php spark migrate --latest  # jika ada migration baru
php spark cache:clear
sudo systemctl restart nginx  # atau apache2

# Monitor logs
tail -f writable/logs/log.txt
sudo tail -f /var/log/nginx/error.log

# Backup database
mysqldump -u aqiqah_user -p'PASSWORD' aqiqah_db > backup_$(date +%Y%m%d).sql

# Restore database
mysql -u aqiqah_user -p'PASSWORD' aqiqah_db < backup_YYYYMMDD.sql

# Cek status aplikasi
php spark info
php spark db:probe
```

---

## Contact & Support

Jika menemukan masalah saat deployment:
1. Cek log files di folder `writable/logs/`
2. Pastikan semua environment variables di `.env` sudah benar
3. Verifikasi semua PHP extensions terinstall
4. Cek firewall rules untuk port 80/443 dan MySQL