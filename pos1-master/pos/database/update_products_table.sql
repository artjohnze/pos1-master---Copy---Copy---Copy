-- Drop existing products table if it exists
DROP TABLE IF EXISTS `products`;

-- Create new products table with updated schema
CREATE TABLE `products` (
    `product_id` int(11) NOT NULL AUTO_INCREMENT,
    `product_code` varchar(200) NOT NULL,
    `gen_name` varchar(200) NOT NULL,
    `product_name` varchar(200) NOT NULL,
    `cost` varchar(100) NOT NULL,
    `o_price` varchar(100) NOT NULL,
    `price` varchar(100) NOT NULL,
    `profit` varchar(100) NOT NULL,
    `supplier` varchar(100) NOT NULL,
    `onhand_qty` int(10) NOT NULL,
    `qty` int(10) NOT NULL,
    `qty_sold` int(10) NOT NULL,
    `expiry_date` varchar(500) NOT NULL,
    `date_arrival` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`product_id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;