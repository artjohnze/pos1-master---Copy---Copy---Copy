-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 08:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sales`
--

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE `collection` (
  `transaction_id` int(11) NOT NULL,
  `date` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `invoice` varchar(100) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `remarks` varchar(100) NOT NULL,
  `balance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `membership_number` varchar(100) NOT NULL,
  `prod_name` varchar(550) NOT NULL,
  `expected_date` varchar(500) NOT NULL,
  `note` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
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
  `date_arrival` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_code`, `gen_name`, `product_name`, `cost`, `o_price`, `price`, `profit`, `supplier`, `onhand_qty`, `qty`, `qty_sold`, `expiry_date`, `date_arrival`) VALUES
(2, 'paracetamol', 'pain', 'killer', '', '10', '100', '', 'supplier 1', 0, 30, 0, '2025-10-07', '2025-10-07 06:09:02');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `transaction_id` int(11) NOT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `suplier` varchar(100) NOT NULL,
  `remarks` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases_item`
--

CREATE TABLE `purchases_item` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `cost` varchar(100) NOT NULL,
  `invoice` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `transaction_id` int(11) NOT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `cashier` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `profit` varchar(100) NOT NULL,
  `due_date` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `balance` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`transaction_id`, `invoice_number`, `cashier`, `date`, `type`, `amount`, `profit`, `due_date`, `name`, `balance`) VALUES
(27, '00220', 'Admin', '10/07/25', 'cash', '100', '', '100', '', ''),
(28, '726002', 'Admin', '10/07/25', 'cash', '100', '', '100', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `sales_order`
--

CREATE TABLE `sales_order` (
  `transaction_id` int(11) NOT NULL,
  `invoice` varchar(100) NOT NULL,
  `product` varchar(100) NOT NULL,
  `qty` varchar(100) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `profit` varchar(100) NOT NULL,
  `product_code` varchar(150) NOT NULL,
  `gen_name` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` varchar(100) NOT NULL,
  `discount` varchar(100) NOT NULL,
  `date` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sales_order`
--

INSERT INTO `sales_order` (`transaction_id`, `invoice`, `product`, `qty`, `amount`, `profit`, `product_code`, `gen_name`, `name`, `price`, `discount`, `date`) VALUES
(5, '726002', '2', '1', '100', '', 'paracetamol', 'pain', '', '100', '0', '10/07/25');

-- --------------------------------------------------------

--
-- Table structure for table `stock_log`
--

CREATE TABLE `stock_log` (
  `log_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL COMMENT 'ADD_STOCK, SALE, RETURN_TO_SUPPLIER, etc.',
  `quantity_changed` int(11) NOT NULL COMMENT 'Positive for additions, negative for reductions',
  `old_quantity` int(11) NOT NULL,
  `new_quantity` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stock_log`
--

INSERT INTO `stock_log` (`log_id`, `product_id`, `action_type`, `quantity_changed`, `old_quantity`, `new_quantity`, `date_created`, `user_id`, `notes`) VALUES
(4, 2, 'RETURN_TO_SUPPLIER', -19, 49, 30, '2025-10-07 16:09:21', '1', 'Return to supplier. Reason: out of date');

-- --------------------------------------------------------

--
-- Table structure for table `supliers`
--

CREATE TABLE `supliers` (
  `suplier_id` int(11) NOT NULL,
  `suplier_name` varchar(100) NOT NULL,
  `suplier_address` varchar(100) NOT NULL,
  `suplier_contact` varchar(100) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `note` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `supliers`
--

INSERT INTO `supliers` (`suplier_id`, `suplier_name`, `suplier_address`, `suplier_contact`, `contact_person`, `note`) VALUES
(1, 'supplier 1', 'Cauayan city', '09076364645', '', 'delivered');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_returns`
--

CREATE TABLE `supplier_returns` (
  `return_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier` varchar(100) NOT NULL,
  `quantity` int(10) NOT NULL,
  `reason` text NOT NULL,
  `return_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `supplier_returns`
--

INSERT INTO `supplier_returns` (`return_id`, `product_id`, `supplier`, `quantity`, `reason`, `return_date`) VALUES
(6, 2, 'supplier 1', 19, 'out of date', '2025-10-07 06:09:21');

-- --------------------------------------------------------

--
-- Table structure for table `temporary_cart`
--

CREATE TABLE `temporary_cart` (
  `transaction_id` int(11) NOT NULL,
  `invoice` varchar(100) NOT NULL,
  `product` varchar(100) NOT NULL,
  `qty` varchar(100) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `product_code` varchar(150) NOT NULL,
  `gen_name` varchar(200) NOT NULL,
  `price` varchar(100) NOT NULL,
  `discount` varchar(100) NOT NULL,
  `date` varchar(500) NOT NULL,
  `session_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `temporary_cart`
--

INSERT INTO `temporary_cart` (`transaction_id`, `invoice`, `product`, `qty`, `amount`, `product_code`, `gen_name`, `price`, `discount`, `date`, `session_id`) VALUES
(1, '3082422', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', '8leeenmq9g76gq4lgbju1bktue'),
(2, '733223', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', '8leeenmq9g76gq4lgbju1bktue'),
(3, '2330333', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', '8leeenmq9g76gq4lgbju1bktue'),
(4, '8674246', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', '8leeenmq9g76gq4lgbju1bktue'),
(5, '329003', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', '8leeenmq9g76gq4lgbju1bktue'),
(6, '2022242', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', '8leeenmq9g76gq4lgbju1bktue'),
(7, '-30333', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', '8leeenmq9g76gq4lgbju1bktue'),
(8, '5384330', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 'hlm7t06p0biet46va35nmnbuid'),
(9, '0337383', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 'hlm7t06p0biet46va35nmnbuid'),
(10, '95402322', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 'hlm7t06p0biet46va35nmnbuid'),
(11, '03327272', '1', '3', '300', 'para ', 'ce', '100', '0', '10/05/25', 'hlm7t06p0biet46va35nmnbuid'),
(12, 'RS-36324326', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 'hlm7t06p0biet46va35nmnbuid'),
(16, '22926293', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 'hlm7t06p0biet46va35nmnbuid'),
(17, '032302', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 'hlm7t06p0biet46va35nmnbuid'),
(19, '2080050', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', '85dd7s3nulu8567ijekhv2s116'),
(20, '333903', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', '85dd7s3nulu8567ijekhv2s116'),
(24, '0232333', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', '85dd7s3nulu8567ijekhv2s116'),
(26, 'RS-2300800', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', '85dd7s3nulu8567ijekhv2s116'),
(27, 'RS-072263', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 'dv7lv7n1nptqrc4l5pp5nchvsh'),
(28, 'RS-822303', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 't006olek06frrb2aecq6v9nsfe'),
(29, '322523', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 't006olek06frrb2aecq6v9nsfe'),
(31, '023233', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 't006olek06frrb2aecq6v9nsfe'),
(32, '0397790', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 't006olek06frrb2aecq6v9nsfe'),
(45, '2333324', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 'k95v7lrln2mt826bbjqfkqqc40'),
(46, '027300', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 'k95v7lrln2mt826bbjqfkqqc40'),
(49, '07232532', '1', '1', '100', 'para ', 'aa', '100', '0', '10/05/25', 'jc14mnu080gfuomvsh8tm8cdd0'),
(50, '325233', '1', '1', '100', 'para ', 'aa', '100', '0', '10/05/25', 'jc14mnu080gfuomvsh8tm8cdd0');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `name`, `position`) VALUES
(1, 'admin', 'admin', 'Admin', 'admin'),
(2, 'cashier', 'cashier', 'Cashier', 'admin'),
(3, 'admin', 'admin123', 'Administrator', 'admin'),
(4, 'art', 'art', 'Admin', 'admin'),
(5, 'admin', 'admin1', 'Cashier', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `purchases_item`
--
ALTER TABLE `purchases_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `sales_order`
--
ALTER TABLE `sales_order`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `stock_log`
--
ALTER TABLE `stock_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_date` (`date_created`),
  ADD KEY `idx_action_type` (`action_type`);

--
-- Indexes for table `supliers`
--
ALTER TABLE `supliers`
  ADD PRIMARY KEY (`suplier_id`);

--
-- Indexes for table `supplier_returns`
--
ALTER TABLE `supplier_returns`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `temporary_cart`
--
ALTER TABLE `temporary_cart`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `collection`
--
ALTER TABLE `collection`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases_item`
--
ALTER TABLE `purchases_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `sales_order`
--
ALTER TABLE `sales_order`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stock_log`
--
ALTER TABLE `stock_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supliers`
--
ALTER TABLE `supliers`
  MODIFY `suplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier_returns`
--
ALTER TABLE `supplier_returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `temporary_cart`
--
ALTER TABLE `temporary_cart`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stock_log`
--
ALTER TABLE `stock_log`
  ADD CONSTRAINT `stock_log_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE;

--
-- Constraints for table `supplier_returns`
--
ALTER TABLE `supplier_returns`
  ADD CONSTRAINT `supplier_returns_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
