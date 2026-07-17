#!/bin/bash
# ============================================
# CRON JOB NOTIFICATIONS - Sistem Aqiqah
# ============================================
# Script ini menjalankan notifikasi Telegram 
# (24h reminders + daily recap) via Spark CLI
# ============================================
# 
# Cara Install di VPS / Linux Server:
# 1. Letakkan script ini di folder project
# 2. chmod +x cron-notifications.sh
# 3. Edit crontab: crontab -e
# 4. Tambahkan baris berikut:
#
#    # Jalankan reminder setiap jam 06:00 pagi
#    0 6 * * * /path/to/project-aqiqah/cron-notifications.sh >> /path/to/project-aqiqah/writable/logs/cron-notifications.log 2>&1
#
#    # Jalankan juga jam 18:00 sebagai backup
#    0 18 * * * /path/to/project-aqiqah/cron-notifications.sh >> /path/to/project-aqiqah/writable/logs/cron-notifications.log 2>&1
#
# ============================================

# Konfigurasi Path (Sesuaikan dengan lokasi deploy)
PROJECT_DIR="/var/www/project-aqiqah"
PHP_BIN="/usr/bin/php"
SPARK="spark"

# Waktu eksekusi
DATETIME=$(date '+%Y-%m-%d %H:%M:%S')

echo "========================================"
echo "[$DATETIME] Memulai cron notifications..."
echo "========================================"

# Pindah ke direktori project
cd "$PROJECT_DIR" || {
    echo "[ERROR] Gagal pindah ke direktori $PROJECT_DIR"
    exit 1
}

# Cek file .env
if [ ! -f ".env" ]; then
    echo "[ERROR] File .env tidak ditemukan! Pastikan sudah dikonfigurasi."
    exit 1
fi

# Jalankan Spark Command
echo "[$(date '+%H:%M:%S')] Menjalankan notifications:send..."
$PHP_BIN $SPARK notifications:send

# Cek exit code
if [ $? -eq 0 ]; then
    echo "[$(date '+%H:%M:%S')] ✅ Notifikasi berhasil dikirim."
else
    echo "[$(date '+%H:%M:%S')] ❌ Gagal mengirim notifikasi."
fi

echo "========================================"
echo "[$(date '+%H:%M:%S')] Selesai."
echo "========================================"
echo ""