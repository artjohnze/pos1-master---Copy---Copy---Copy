-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2025 at 02:34 PM
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
  `initial_stock` int(11) NOT NULL,
  `qty_sold` int(10) NOT NULL,
  `expiry_date` varchar(500) NOT NULL,
  `date_arrival` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_code`, `gen_name`, `product_name`, `cost`, `o_price`, `price`, `profit`, `supplier`, `onhand_qty`, `qty`, `initial_stock`, `qty_sold`, `expiry_date`, `date_arrival`) VALUES
(45, 'paracetamol', 'qjsaj', 'de', '', '', '100', '', 'Supplier', 0, 91, 0, 5, '2025-10-17', '2025-10-14 12:18:12'),
(46, 'para ', 'qjsaj', 'baby', '', '', '12', '', 'Supplier', 0, 88, 0, 1, '2025-10-16', '2025-10-14 04:30:53');

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
(28, '726002', 'Admin', '10/07/25', 'cash', '100', '', '100', '', ''),
(29, '8333272', 'Admin', '10/08/25', 'cash', '100', '', '100', '', ''),
(30, '730270', 'Admin', '10/09/25', 'cash', '100', '', '100', '', ''),
(31, '7600522', 'Admin', '10/09/25', 'cash', '100', '', '1000', '', ''),
(32, 'RS-29332333', 'Admin', '10/09/25', 'cash', '100', '', '100', '', ''),
(33, 'RS-3324', 'Admin', '10/09/25', 'cash', '100', '', '100', '', ''),
(34, '220022', 'Admin', '10/09/25', 'cash', '100', '', '100', '', ''),
(35, '3353272', 'Admin', '10/09/25', 'cash', '100', '', '100', '', ''),
(36, 'RS-238229', 'Admin', '10/09/25', 'cash', '100', '', '100', '', ''),
(37, 'RS-323290', 'Admin', '10/09/25', 'cash', '100', '', '100', '', ''),
(38, 'RS-022243', 'Admin', '10/09/25', 'cash', '100', '', '100', '', ''),
(39, '20233228', 'Admin', '10/09/25', 'cash', '200', '', '200', '', ''),
(40, 'RS-392223', 'Admin', '10/09/25', 'cash', '100', '', '100', '', ''),
(41, 'RS-320334', 'Admin', '10/10/25', 'cash', '100', '', '100', '', ''),
(42, '636733', 'Admin', '10/10/25', 'cash', '100', '', '100', '', ''),
(43, '396002', 'Admin', '10/10/25', 'cash', '100', '', '100', '', ''),
(44, '2928530', 'cashier 1', '10/10/25', 'cash', '100', '', '100', '', ''),
(45, '880098', 'cashier 1', '10/10/25', 'cash', '100', '', '100', '', ''),
(46, '952983', 'cashier 1', '10/10/25', 'cash', '100', '', '100', '', ''),
(47, '', 'cashier 1', '10/10/25', 'cash', '100', '', '100', '', ''),
(48, '4328302', 'cashier 1', '10/10/25', 'cash', '100', '', '100', '', ''),
(49, '733979', 'cashier 1', '10/10/25', 'cash', '300', '', '300', '', ''),
(50, '849092', 'cashier 1', '10/10/25', 'cash', '100', '', '100', '', ''),
(51, '239428', 'cashier 1', '10/10/25', 'cash', '19900', '', '20000', '', ''),
(52, '285191', 'cashier 1', '10/10/25', 'cash', '100', '', '100', '', ''),
(53, '119222', 'cashier 1', '10/10/25', 'cash', '10000', '', '1000000', '', ''),
(54, '659299', 'cashier 1', '10/11/25', 'cash', '1000', '', '2000', '', ''),
(55, '787951', 'cashier 1', '10/11/25', 'cash', '2000', '', '3000', '', ''),
(56, '138842', 'cashier 1', '10/11/25', 'cash', '2000', '', '2000', '', ''),
(57, '860716', 'Cashier', '10/11/25', 'cash', '1', '', '100', '', ''),
(58, '441967', 'Cashier', '10/12/25', 'cash', '1000', '', '1000', '', ''),
(59, '751429', 'Cashier', '10/12/25', 'cash', '100', '', '100', '', ''),
(60, '578520', 'Cashier', '10/12/25', 'cash', '300', '', '300', '', ''),
(61, '726135', 'Cashier', '10/12/25', 'cash', '300', '', '1000', '', ''),
(62, '322322', 'Cashier', '10/12/25', 'cash', '100', '', '100', '', ''),
(63, '611257', 'Cashier', '10/12/25', 'cash', '100', '', '100', '', ''),
(64, '273903', 'Cashier', '10/12/25', 'cash', '100', '', '100', '', ''),
(65, '465972', 'Cashier', '10/13/25', 'cash', '100', '', '100', '', ''),
(66, '821883', 'Cashier', '10/13/25', 'cash', '100', '', '100', '', ''),
(67, '162159', 'Cashier', '10/13/25', 'cash', '100', '', '100', '', ''),
(68, '615742', 'Cashier', '10/13/25', 'cash', '100', '', '100', '', ''),
(69, '684316', 'Cashier', '10/13/25', 'cash', '100', '', '100', '', ''),
(70, '512842', 'Cashier', '10/13/25', 'cash', '22', '', '100', '', ''),
(71, '212084', 'Cashier', '10/13/25', 'cash', '199', '', '1000', '', ''),
(72, '124919', 'Cashier', '10/13/25', 'cash', '12', '', '100', '', ''),
(73, '613213', 'Cashier', '10/13/25', 'cash', '100', '', '100', '', ''),
(74, '616914', 'Cashier', '10/13/25', 'cash', '100', '', '100', '', ''),
(75, '602120', 'Cashier', '10/14/25', 'cash', '100', '', '100', '', ''),
(76, '690875', 'Cashier', '10/14/25', 'cash', '500', '', '1000', '', ''),
(77, '267718', 'Cashier', '10/14/25', 'cash', '500', '', '1000', '', ''),
(78, '411779', 'Cashier', '10/14/25', 'cash', '12', '', '100', '', '');

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
(26, '690875', '45', '5', '500', '', 'paracetamol', 'qjsaj', '', '100', '0', '10/14/25'),
(27, '267718', '45', '5', '500', '', 'paracetamol', 'qjsaj', '', '100', '0', '10/14/25'),
(28, '411779', '46', '1', '12', '', 'para ', 'qjsaj', '', '12', '0', '10/14/25');

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
(3, 46, 'RETURN_TO_SUPPLIER', -10, 99, 89, '2025-10-14 22:31:12', '1', 'Return to supplier. Reason: no space');

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
(11, 'Supplier', 'Cauayan city', '09067633732', '', 'for delivery');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_deliveries`
--

CREATE TABLE `supplier_deliveries` (
  `id` int(11) NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `product_code` varchar(200) NOT NULL,
  `gen_name` varchar(200) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` varchar(100) DEFAULT NULL,
  `expiry_date` varchar(100) DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_deliveries`
--

INSERT INTO `supplier_deliveries` (`id`, `supplier`, `product_code`, `gen_name`, `product_name`, `qty`, `price`, `expiry_date`, `status`, `submitted_at`) VALUES
(5, 'supplier 1', 'Biogesic', 'Paracetamol', 'Pain reliever', 100, '100', '2024-07-12', 'accepted', '2025-10-12 08:51:05'),
(6, 'supplier 1', 'Amoxil', 'Amoxicillin', 'Antibiotic medicine', 100, '100', '2024-03-12', 'accepted', '2025-10-12 09:01:49'),
(7, 'supplier 1', 'Neozep', 'Phenylephrine', 'Cold medicine', 100, '100', '2024-02-12', 'accepted', '2025-10-12 09:05:50'),
(8, 'supplier 1', 'Bioflu', 'Chlorphenamine', 'Flu relief', 100, '100', '2025-10-07', 'accepted', '2025-10-12 15:51:50'),
(9, 'supplier 1', 'Alaxan FR', 'Ibuprofen', 'Body pain', 100, '100', '2025-10-08', 'accepted', '2025-10-12 15:54:11'),
(10, 'supplier 1', 'Decolgen', 'Chlorphenamine', 'Cold remedy', 100, '100', '2025-10-10', 'accepted', '2025-10-12 15:55:04'),
(11, 'supplier 1', 'Solmux', 'Carbocisteine', 'Mucolytic', 100, '100', '2025-10-08', 'accepted', '2025-10-12 15:55:56'),
(12, 'supplier 1', 'Ventolin', 'Salbutamol', 'Asthma relief', 100, '100', '2029-07-13', 'accepted', '2025-10-12 15:57:08'),
(13, 'supplier 1', 'Medicol', 'Ibuprofen', 'Headache reliever', 100, '100', '2027-06-13', 'accepted', '2025-10-12 15:57:56'),
(14, 'supplier 1', 'Diatabs', 'Loperamide', 'Anti-diarrheal', 100, '100', '2030-06-13', 'accepted', '2025-10-12 15:58:38'),
(15, 'supplier 1', 'Claritin', 'Loratadine', 'Anti-allergy', 100, '100', '2028-10-13', 'rejected', '2025-10-12 16:26:37'),
(16, 'supplier 1', 'Mefenamic', 'Mefenamic Acid', 'Pain reliever', 100, '100', '2027-10-13', 'rejected', '2025-10-12 16:27:49'),
(17, 'supplier 1', 'Dulcolax', 'Bisacodyl', 'Laxative', 100, '100', '2028-10-13', 'rejected', '2025-10-12 16:28:24'),
(18, 'supplier 1', 'Ascof Lagundi', 'Lagundi Leaf Extract', 'Herbal cough', 100, '100', '2026-10-13', 'accepted', '2025-10-12 16:29:01'),
(20, 'supplier 1', '1', '2', '3', 1, '1', '2025-10-13', 'rejected', '2025-10-12 17:30:58'),
(21, 'supplier 1', 'q', 'w', 'e', 1, '2', '2025-10-16', 'rejected', '2025-10-12 17:31:44'),
(22, 'sup', '2', '3', '4', 100, '100', '2027-06-13', 'accepted', '2025-10-13 10:16:53');

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
(13, 46, 'Supplier', 10, 'no space', '2025-10-14 12:31:12');

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
(26, 'RS-2300800', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', '85dd7s3nulu8567ijekhv2s116'),
(27, 'RS-072263', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 'dv7lv7n1nptqrc4l5pp5nchvsh'),
(28, 'RS-822303', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 't006olek06frrb2aecq6v9nsfe'),
(29, '322523', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 't006olek06frrb2aecq6v9nsfe'),
(31, '023233', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 't006olek06frrb2aecq6v9nsfe'),
(32, '0397790', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 't006olek06frrb2aecq6v9nsfe'),
(45, '2333324', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 'k95v7lrln2mt826bbjqfkqqc40'),
(46, '027300', '1', '1', '100', 'para ', 'ce', '100', '0', '10/05/25', 'k95v7lrln2mt826bbjqfkqqc40'),
(49, '07232532', '1', '1', '100', 'para ', 'aa', '100', '0', '10/05/25', 'jc14mnu080gfuomvsh8tm8cdd0'),
(50, '325233', '1', '1', '100', 'para ', 'aa', '100', '0', '10/05/25', 'jc14mnu080gfuomvsh8tm8cdd0'),
(64, '78930303', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '10/09/25', 'eos9pa7na7673v40bmvn3t17ei'),
(65, 'RS-562325', '3', '2', '200', 'WY00', 'qjsaj', '100', '0', '10/09/25', 'pukditfcvmbrutc8n0a8ogg126'),
(66, '60506233', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '2025-10-09 17:44:38', 'te07me1mrbhdg639jn6j94arbp'),
(67, '403300', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '2025-10-09 17:45:05', 'te07me1mrbhdg639jn6j94arbp'),
(68, '233223', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '2025-10-09 17:45:09', 'te07me1mrbhdg639jn6j94arbp'),
(69, '70033723', '3', '2', '200', 'WY00', 'qjsaj', '100', '0', '2025-10-09 17:45:17', 'te07me1mrbhdg639jn6j94arbp'),
(70, '22332003', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '2025-10-09 17:46:51', 'keltcsjoi9aqpocpmsfb3mobo5'),
(71, '338202', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '2025-10-09 18:06:50', '5tbo4opimnrud2v6jeisnu37i2'),
(72, '8335033', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '10/09/25', 'ha898trsvdb15icukc59t2pbgn'),
(73, '3232573', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '2025-10-09 18:10:31', '0lkk2q9a643rds12g4eq5mvmje'),
(74, '233642', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '2025-10-09 18:10:44', '1f34924g6gmjk3o51iv70ilgik'),
(75, '229623', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '2025-10-09 18:12:49', '1f34924g6gmjk3o51iv70ilgik'),
(76, '328233', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '2025-10-09 18:13:48', '1f34924g6gmjk3o51iv70ilgik'),
(79, '039233', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '2025-10-09 18:15:30', '3ajopt60bq5a7fn0kglm7s20e5'),
(90, '2070229', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '10/10/25', 'bfiuti5i1fndnp3kqp4d4d4qpi'),
(92, '925719', '3', '5', '500', 'WY00', 'qjsaj', '100', '0', '10/10/25', 'e231gdk075tjfr1cv20p8hcsvs'),
(93, '827466', '3', '1', '100', 'WY00', 'qjsaj', '100', '0', '10/10/25', 'uh3ckmsbv40v0s2ult2nvejhch'),
(105, '496535', '23', '1', '10000', 'Paracetamol', 'Biogesic', '10000', '0', '10/12/25', 'gc3r2s02bd4kllbv4v6l8fi19s'),
(121, '672023', '39', '1', '100', 'art', '23', '100', '0', '10/13/25', 't3r429si378mg9acifnmdosleh'),
(122, '916804', '40', '1', '22', '1', '2', '22', '0', '10/13/25', 'ket9gdq6opumeohp8bajah3beo');

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
(11, 'cashier', 'cashier', 'Cashier', 'cashier');

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
-- Indexes for table `supplier_deliveries`
--
ALTER TABLE `supplier_deliveries`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `sales_order`
--
ALTER TABLE `sales_order`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `stock_log`
--
ALTER TABLE `stock_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `supliers`
--
ALTER TABLE `supliers`
  MODIFY `suplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `supplier_deliveries`
--
ALTER TABLE `supplier_deliveries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `supplier_returns`
--
ALTER TABLE `supplier_returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `temporary_cart`
--
ALTER TABLE `temporary_cart`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
