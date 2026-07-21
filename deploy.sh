#!/bin/bash

# ============================================================
# 🚀 Deploy Script - Ibrahim Aqiqah
# This script is executed on VPS after push to main branch
# ============================================================

set -e  # Exit on error

# --- Configuration ---
PROJECT_DIR="/var/www/ibrahimfaradila"
REPO_URL="https://github.com/ChaisarAbi/ibrahimfaradila.git"
BRANCH="main"
PHP_VERSION="8.2"
COMPOSER="/usr/bin/composer"
MYSQL_USER="root"
MYSQL_PASSWORD=""  # Sesuaikan dengan MySQL root password atau gunakan user terpisah
DB_NAME="aqiqah_db"

# --- Timestamp ---
echo "============================================"
echo "  🚀 Deploy started at $(date)"
echo "============================================"

# --- Step 1: Pull latest code ---
echo ""
echo "[1/6] 📦 Pulling latest code from repository..."
cd $PROJECT_DIR
git fetch origin $BRANCH
git reset --hard origin/$BRANCH

# --- Step 2: Install/update PHP dependencies ---
echo ""
echo "[2/6] 📦 Installing PHP dependencies with Composer..."
$COMPOSER install --no-dev --optimize-autoloader --no-interaction --working-dir=$PROJECT_DIR

# --- Step 3: Set permissions ---
echo ""
echo "[3/6] 🔐 Setting permissions..."
chown -R www-data:www-data $PROJECT_DIR
chmod -R 755 $PROJECT_DIR
chmod -R 775 $PROJECT_DIR/writable
chmod -R 775 $PROJECT_DIR/public/uploads

# --- Step 4: Database migration (if applicable) ---
echo ""
echo "[4/6] 🗄️  Updating database..."
if [ -f "$PROJECT_DIR/database.sql" ]; then
    mysql -u $MYSQL_USER -p$MYSQL_PASSWORD $DB_NAME < $PROJECT_DIR/database.sql 2>/dev/null || echo "  ⚠️  DB migration skipped or already up to date."
fi

# --- Step 5: Clear cache ---
echo ""
echo "[5/6] 🧹 Clearing application cache..."
rm -rf $PROJECT_DIR/writable/cache/*
rm -rf $PROJECT_DIR/writable/debugbar/*
rm -rf $PROJECT_DIR/writable/logs/*.log

# --- Step 6: Reload PHP-FPM ---
echo ""
echo "[6/6] 🔄 Reloading PHP-FPM..."
if systemctl is-active --quiet php$PHP_VERSION-fpm; then
    systemctl reload php$PHP_VERSION-fpm
    echo "  ✅ PHP-FPM reloaded."
else
    echo "  ⚠️  PHP-FPM not found, trying php8.3 or php8.1..."
    for ver in 8.3 8.1 8.0; do
        if systemctl is-active --quiet php$ver-fpm; then
            systemctl reload php$ver-fpm
            echo "  ✅ PHP $ver-FPM reloaded."
            break
        fi
    done
fi

# --- Final ---
echo ""
echo "============================================"
echo "  ✅ Deploy completed at $(date)"
echo "============================================"