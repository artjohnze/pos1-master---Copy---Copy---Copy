-- Create stock_log table to track all stock changes
-- This table will keep a history of stock additions, sales, and adjustments

CREATE TABLE IF NOT EXISTS `stock_log` (
    `log_id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `action_type` varchar(50) NOT NULL COMMENT 'ADD_STOCK, SALE, ADJUSTMENT, etc.',
    `quantity_changed` int(11) NOT NULL COMMENT 'Positive for additions, negative for reductions',
    `old_quantity` int(11) NOT NULL,
    `new_quantity` int(11) NOT NULL,
    `date_created` datetime NOT NULL,
    `user_id` varchar(100) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    PRIMARY KEY (`log_id`),
    KEY `idx_product_id` (`product_id`),
    KEY `idx_date` (`date_created`),
    KEY `idx_action_type` (`action_type`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

-- Add some sample indexes for better performance
-- ALTER TABLE stock_log ADD INDEX idx_product_date (product_id, date_created);
-- ALTER TABLE stock_log ADD INDEX idx_user_date (user_id, date_created);