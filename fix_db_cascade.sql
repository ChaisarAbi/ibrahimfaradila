-- Fix 1b: Remove CASCADE DELETE from orders -> customers FK
-- Run this SQL in phpMyAdmin or MySQL CLI
USE aqiqah_db;

-- Check foreign key constraint name for orders.customer_id
SELECT CONSTRAINT_NAME 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = 'aqiqah_db' 
  AND TABLE_NAME = 'orders' 
  AND COLUMN_NAME = 'customer_id' 
  AND REFERENCED_TABLE_NAME = 'customers';

-- Drop existing foreign keys (no IF EXISTS in MySQL 5.7, use try/ignore)
ALTER TABLE orders DROP FOREIGN KEY orders_ibfk_1;
ALTER TABLE orders DROP FOREIGN KEY orders_ibfk_2;
ALTER TABLE orders DROP FOREIGN KEY fk_orders_customer;

-- Re-add without CASCADE
ALTER TABLE orders 
  ADD CONSTRAINT fk_orders_customer 
  FOREIGN KEY (customer_id) REFERENCES customers(id_customer);

-- Keep CASCADE on order_details -> orders (intended)
ALTER TABLE order_details DROP FOREIGN KEY order_details_ibfk_1;
ALTER TABLE order_details DROP FOREIGN KEY fk_order_details_order;

ALTER TABLE order_details 
  ADD CONSTRAINT fk_order_details_order 
  FOREIGN KEY (order_id) REFERENCES orders(id_order) ON DELETE CASCADE;

-- Keep CASCADE on schedules -> orders (intended)  
ALTER TABLE schedules DROP FOREIGN KEY schedules_ibfk_1;
ALTER TABLE schedules DROP FOREIGN KEY fk_schedules_order;

ALTER TABLE schedules 
  ADD CONSTRAINT fk_schedules_order 
  FOREIGN KEY (order_id) REFERENCES orders(id_order) ON DELETE CASCADE;
