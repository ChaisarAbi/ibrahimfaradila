CREATE DATABASE IF NOT EXISTS aqiqah_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aqiqah_db;

-- 1. USERS (untuk login admin/staff)
CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100),
    role ENUM('admin', 'rph', 'dapur') DEFAULT 'admin',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. PACKAGES (Paket)
CREATE TABLE IF NOT EXISTS packages (
    id_package INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    weight_type CHAR(1) NOT NULL,
    min_weight DECIMAL(5,2),
    max_weight DECIMAL(5,2),
    box_count INT NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    is_special BOOLEAN DEFAULT FALSE
);

-- 3. BONE MENUS (Menu Tulang)
CREATE TABLE IF NOT EXISTS bone_menus (
    id_bone INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- 4. MEAT MENUS (Menu Daging)
CREATE TABLE IF NOT EXISTS meat_menus (
    id_meat INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- 5. CUSTOMERS (Data Pemesan)
CREATE TABLE IF NOT EXISTS customers (
    id_customer INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    child_name VARCHAR(100) NOT NULL,
    gender ENUM('Laki-laki', 'Perempuan') NOT NULL,
    birth_date DATE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 6. ORDERS (Transaksi Utama)
CREATE TABLE IF NOT EXISTS orders (
    id_order INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    package_id INT NOT NULL,
    animal_type ENUM('Domba', 'Kambing') NOT NULL,
    animal_gender ENUM('Jantan', 'Betina') NOT NULL,
    jumlah_anak INT DEFAULT 1,
    slaughter_date DATE NOT NULL,
    delivery_date DATE NOT NULL,
    slaughter_time TIME,
    penyembelihan ENUM('Dokumentasi', 'Video Call', 'Visit') NOT NULL,
    use_photo_card BOOLEAN DEFAULT FALSE,
    use_photo_certificate BOOLEAN DEFAULT FALSE,
    photo_path VARCHAR(255) NULL,
    status ENUM('Pending', 'Scheduled', 'Processing', 'Completed', 'Cancelled') DEFAULT 'Pending',
    total_price DECIMAL(12,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id_customer) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES packages(id_package)
);

-- 7. ORDER_DETAILS (Menu & Box type per Order)
CREATE TABLE IF NOT EXISTS order_details (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    bone_menu_id INT NULL,
    meat_menu_id INT NULL,
    box_type ENUM('Box Premium', 'Bento Pack') NOT NULL,
    jumlah_box INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id_order) ON DELETE CASCADE,
    FOREIGN KEY (bone_menu_id) REFERENCES bone_menus(id_bone),
    FOREIGN KEY (meat_menu_id) REFERENCES meat_menus(id_meat)
);

-- 8. SCHEDULES (Hasil algoritma EDF/EDD)
CREATE TABLE IF NOT EXISTS schedules (
    id_schedule INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    slaughter_date DATE NOT NULL,
    priority INT NOT NULL,
    status ENUM('Scheduled', 'In Progress', 'Done') DEFAULT 'Scheduled',
    FOREIGN KEY (order_id) REFERENCES orders(id_order) ON DELETE CASCADE
);

-- 9. NOTIFICATIONS (Log notifikasi Telegram)
CREATE TABLE IF NOT EXISTS notifications (
    id_notif INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NULL,
    type ENUM('24h_reminder', 'stock_alert', 'order_confirmation', 'daily_recap') NOT NULL,
    message TEXT NOT NULL,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE
);

-- 10. STOCK (Stok hewan & bahan dapur sederhana)
CREATE TABLE IF NOT EXISTS stocks (
    id_stock INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    category ENUM('hewan', 'bahan') DEFAULT 'hewan',
    quantity INT NOT NULL,
    min_threshold INT NOT NULL DEFAULT 5,
    unit VARCHAR(20) DEFAULT 'ekor',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 11. TELEGRAM RECIPIENTS (Penerima Notifikasi)
CREATE TABLE IF NOT EXISTS telegram_recipients (
    id_recipient INT AUTO_INCREMENT PRIMARY KEY,
    chat_id VARCHAR(100) NOT NULL,
    name VARCHAR(255) NULL,
    type ENUM('personal', 'group') DEFAULT 'personal',
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 12. SETTINGS (Konfigurasi Telegram & Toko)
CREATE TABLE IF NOT EXISTS settings (
    id_setting INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT NOT NULL
);

-- INSERT MASTER DATA
INSERT INTO bone_menus (name) VALUES ('Gulai'), ('Sop'), ('Tongseng');
INSERT INTO meat_menus (name) VALUES ('Sate tanpa tusuk'), ('Teriyaki'), ('Lada Hitam'), ('Krengseng'), ('Semur');

INSERT INTO packages (name, weight_type, min_weight, max_weight, box_count, price, is_special) VALUES
('Paket Berkah A', 'A', 16, 17, 50, 2500000, 0),
('Paket Special A', 'A', 16, 17, 50, 3000000, 1),
('Paket Berkah B', 'B', 17, 18, 60, 2800000, 0),
('Paket Special B', 'B', 17, 18, 60, 3400000, 1),
('Paket Berkah C', 'C', 18, 20, 80, 3400000, 0),
('Paket Special C', 'C', 18, 20, 80, 4200000, 1),
('Paket Berkah D', 'D', 21, 23, 100, 3900000, 0),
('Paket Special D', 'D', 21, 23, 100, 4900000, 1);

-- Insert User default (password bcrypt)
INSERT INTO users (username, password, fullname, role) VALUES
('admin', '$2y$10$fGKEqVLlcihXDvgR5kwDVe4MVbwcfhs/5IfPIJ09GPiDF9uMjmQ.m', 'Administrator', 'admin'),
('rph',   '$2y$10$pauCsYPIqMibPmI/l365k.5uBBPdkIR6q0fJm8KNIx/Z5uLtNMoG.', 'RPH Operasional', 'rph'),
('dapur', '$2y$10$yrANE9nwUuxSBAZB3Prz/.8Cqs27HU/YE8zPyOrT/bKuZTyD3nzTC', 'Tim Dapur', 'dapur');

-- Insert Stock default
INSERT INTO stocks (item_name, category, quantity, min_threshold) VALUES
('Kambing', 'hewan', 20, 5),
('Domba', 'hewan', 15, 5);