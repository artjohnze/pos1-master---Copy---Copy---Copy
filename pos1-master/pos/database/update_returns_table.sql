-- Add updated_by column to supplier_returns table
ALTER TABLE supplier_returns
ADD COLUMN updated_by VARCHAR(100) DEFAULT NULL,
ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL;