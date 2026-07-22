#!/bin/bash
# ============================================
# CRON JOB NOTIFICATIONS - Sistem Aqiqah
# ============================================
# Script ini menjalankan notifikasi Telegram 
# via Spark CLI dengan mode berbeda:
#   today       -> Kirim rekap pemotongan hari ini (23:59 WIB)
#   tomorrow    -> Kirim preview jadwal besok (00:00 WIB)
#   all         -> Kirim rekap hari ini + preview besok (default)
# ============================================
#
# Cara Install di VPS / Linux Server:
# 1. Letakkan script ini di folder project
# 2. chmod +x cron-notifications.sh
# 3. Edit crontab: crontab -e
# 4. Tambahkan baris berikut:
#
#    # Rekap pemotongan hari ini (23:59 WIB)
#    59 23 * * * /var/www/project-aqiqah/cron-notifications.sh today >> /var/www/project-aqiqah/writable/logs/cron-notifications.log 2>&1
#
#    # Preview jadwal besok (00:00 WIB)
#    0 0 * * * /var/www/project-aqiqah/cron-notifications.sh tomorrow >> /var/www/project-aqiqah/writable/logs/cron-notifications.log 2>&1
#
# ============================================

# Mode: today / tomorrow / all
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