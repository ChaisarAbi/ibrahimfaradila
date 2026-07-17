#!/bin/bash
# ============================================
# CRON JOB NOTIFICATIONS - Sistem Aqiqah
# ============================================
# Script ini menjalankan notifikasi Telegram 
# via Spark CLI dengan mode berbeda:
#   reminders   -> Kirim pengingat 24 jam (06:00 & 18:00)
#   recap       -> Kirim rekap harian (23:00)
#   all         -> Kirim keduanya
# ============================================
#
# Cara Install di VPS / Linux Server:
# 1. Letakkan script ini di folder project
# 2. chmod +x cron-notifications.sh
# 3. Edit crontab: crontab -e
# 4. Tambahkan baris berikut:
#
#    # Pengingat 24 jam (06:00 & 18:00)
#    0 6 * * * /var/www/project-aqiqah/cron-notifications.sh reminders >> /var/www/project-aqiqah/writable/logs/cron-notifications.log 2>&1
#    0 18 * * * /var/www/project-aqiqah/cron-notifications.sh reminders >> /var/www/project-aqiqah/writable/logs/cron-notifications.log 2>&1
#
#    # Rekap harian (23:00)
#    0 23 * * * /var/www/project-aqiqah/cron-notifications.sh recap >> /var/www/project-aqiqah/writable/logs/cron-notifications.log 2>&1
#
# ============================================

# Mode: reminders / recap / all
MODE="${1:-all}"

# Konfigurasi Path (Sesuaikan dengan lokasi deploy)
PROJECT_DIR="/var/www/project-aqiqah"
PHP_BIN="/usr/bin/php"
SPARK="spark"

# Waktu eksekusi
DATETIME=$(date '+%Y-%m-%d %H:%M:%S')

echo "========================================"
echo "[$DATETIME] Memulai cron notifications (mode: $MODE)..."
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

# Jalankan Spark Command sesuai mode
echo "[$(date '+%H:%M:%S')] Menjalankan notifications:send dengan mode: $MODE..."
$PHP_BIN $SPARK notifications:send "$MODE"

# Cek exit code
if [ $? -eq 0 ]; then
    echo "[$(date '+%H:%M:%S')] ✅ Notifikasi ($MODE) berhasil dikirim."
else
    echo "[$(date '+%H:%M:%S')] ❌ Gagal mengirim notifikasi ($MODE)."
fi

echo "========================================"
echo "[$(date '+%H:%M:%S')] Selesai."
echo "========================================"
echo ""