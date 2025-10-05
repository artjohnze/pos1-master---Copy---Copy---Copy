-- SQL Script to Remove Profit Columns from POS Database
-- Run this script to update your existing database after removing profit functionality

-- Remove profit column from products table
ALTER TABLE `products` DROP COLUMN `profit`;

-- Remove profit column from sales table
ALTER TABLE `sales` DROP COLUMN `profit`;

-- Remove profit column from sales_order table
ALTER TABLE `sales_order` DROP COLUMN `profit`;

-- Note: This script will permanently remove profit-related data from your database
-- Make sure to backup your database before running this script if you need to preserve profit data