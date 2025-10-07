CREATE TABLE `supplier_returns` (
    `return_id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `supplier` varchar(100) NOT NULL,
    `quantity` int(10) NOT NULL,
    `reason` text NOT NULL,
    `return_date` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`return_id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `supplier_returns_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;