-- Create temporary_cart table for the improved POS system
-- This table holds items before checkout completion

CREATE TABLE IF NOT EXISTS `temporary_cart` (
    `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
    `invoice` varchar(100) NOT NULL,
    `product` varchar(100) NOT NULL,
    `qty` varchar(100) NOT NULL,
    `amount` varchar(100) NOT NULL,
    `product_code` varchar(150) NOT NULL,
    `gen_name` varchar(200) NOT NULL,
    `price` varchar(100) NOT NULL,
    `discount` varchar(100) NOT NULL,
    `date` varchar(500) NOT NULL,
    `session_id` varchar(100) NOT NULL,
    PRIMARY KEY (`transaction_id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

-- Optional: Add index for better performance
CREATE INDEX idx_invoice ON temporary_cart (invoice);

CREATE INDEX idx_session ON temporary_cart (session_id);

CREATE INDEX idx_date ON temporary_cart (date);