-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2025 at 02:14 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member`
--

CREATE TABLE `tbl_member` (
  `mem_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `ref_l_id` int(11) NOT NULL,
  `mem_name` varchar(50) NOT NULL,
  `mem_username` varchar(20) NOT NULL,
  `mem_password` varchar(100) NOT NULL,
  `mem_img` varchar(200) NOT NULL,
  `dateinsert` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_member`
--

INSERT INTO `tbl_member` (`mem_id`, `ref_l_id`, `mem_name`, `mem_username`, `mem_password`, `mem_img`, `dateinsert`) VALUES
(001, 1, 'Sindennnn', '1', '356a192b7913b04c54574d18c28d46e6395428ab', '133377837120250104_142609.jpg', '2019-09-04 03:40:46'),
(022, 2, 'MEM', 'mem22', '362ffba30a19e9abd2d90c45b6890c632da9675a', '27100874020210707_113953.png', '2020-04-23 10:38:09'),
(029, 1, 'ddd', 'ddd', '9c969ddf454079e3d439973bbab63ea6233e4087', '201877457320250104_135851.jpg', '2025-01-04 06:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `order_id` int(4) UNSIGNED ZEROFILL NOT NULL,
  `mem_id` int(11) NOT NULL,
  `receive_name` varchar(100) NOT NULL COMMENT 'ชื่อผู้รับ',
  `order_status` int(1) NOT NULL,
  `b_name` varchar(100) DEFAULT NULL COMMENT 'ชื่อธนาคาร',
  `pay_amount` float(10,2) DEFAULT NULL,
  `pay_amount2` float(10,2) NOT NULL,
  `order_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`order_id`, `mem_id`, `receive_name`, `order_status`, `b_name`, `pay_amount`, `pay_amount2`, `order_date`) VALUES
(0001, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 60.00, 60.00, '2025-01-05 12:22:04'),
(0002, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 80.00, 80.00, '2025-01-05 14:51:41'),
(0003, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 80.00, 80.00, '2025-01-05 14:58:33'),
(0004, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 80.00, 80.00, '2025-01-05 15:19:26'),
(0005, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 38.00, 38.00, '2025-01-05 15:22:01'),
(0006, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 78.00, 78.00, '2025-01-05 15:39:50'),
(0007, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 39.00, 39.00, '2025-01-05 15:41:09'),
(0008, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 39.00, 39.00, '2025-01-05 15:45:52'),
(0009, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 19.00, 19.00, '2025-01-05 15:46:51'),
(0010, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 40.00, 50.00, '2025-01-06 21:46:52'),
(0011, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 40.00, 50.00, '2025-01-06 21:46:52'),
(0012, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 129.00, 129.00, '2025-01-09 15:30:27'),
(0013, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 19.00, 19.00, '2025-01-09 15:30:32'),
(0014, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 39.00, 40.00, '2025-01-09 16:16:59'),
(0015, 1, 'ลูกค้าหน้าร้าน', 4, 'ชำระหน้าร้าน', 260.00, 260.00, '2025-01-09 16:56:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_detail`
--

CREATE TABLE `tbl_order_detail` (
  `d_id` int(10) NOT NULL,
  `order_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `p_c_qty` int(11) NOT NULL,
  `total` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_order_detail`
--

INSERT INTO `tbl_order_detail` (`d_id`, `order_id`, `p_id`, `p_c_qty`, `total`) VALUES
(1, 3, 3, 4, 80),
(2, 4, 3, 4, 80),
(3, 5, 4, 2, 38),
(4, 6, 4, 2, 38),
(5, 6, 3, 2, 40),
(6, 7, 3, 1, 20),
(7, 7, 4, 1, 19),
(8, 8, 4, 1, 19),
(9, 8, 3, 1, 20),
(10, 9, 4, 1, 19),
(11, 10, 3, 2, 40),
(12, 12, 4, 2, 38),
(13, 12, 1, 1, 7),
(14, 12, 2, 1, 39),
(15, 12, 6, 1, 5),
(16, 12, 5, 1, 25),
(17, 12, 7, 1, 15),
(18, 13, 4, 1, 19),
(19, 14, 4, 1, 19),
(20, 14, 3, 1, 20),
(21, 15, 1, 1, 0),
(22, 15, 2, 1, 39),
(23, 15, 3, 2, 40),
(24, 15, 4, 1, 19),
(25, 15, 5, 5, 125),
(26, 15, 6, 1, 5),
(27, 15, 7, 1, 0),
(28, 15, 2147483647, 1, 7),
(29, 15, 2147483647, 1, 15),
(30, 15, 8, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `p_id` bigint(20) NOT NULL,
  `p_name` varchar(150) NOT NULL,
  `p_type` varchar(20) NOT NULL,
  `p_price` float(100,2) NOT NULL,
  `p_price2` float(100,2) NOT NULL,
  `p_pricemember` float(100,2) NOT NULL,
  `p_pricemember2` float(100,2) NOT NULL,
  `p_qty` int(11) NOT NULL,
  `p_cost` float(100,2) NOT NULL,
  `p_outstock` int(11) NOT NULL,
  `p_unit` varchar(20) NOT NULL,
  `p_img` varchar(255) NOT NULL,
  `p_date_save` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`p_id`, `p_name`, `p_type`, `p_price`, `p_price2`, `p_pricemember`, `p_pricemember2`, `p_qty`, `p_cost`, `p_outstock`, `p_unit`, `p_img`, `p_date_save`) VALUES
(2, 'ครีมกันแดด', '', 39.00, 0.00, 0.00, 0.00, 98, 30.00, 0, '', '', '2025-01-09 08:12:39'),
(3, 'เลย์', '', 20.00, 0.00, 0.00, 0.00, 97, 0.00, 0, '', '81476044620250105_111951.jpg', '2025-01-05 04:19:51'),
(4, 'น้ำโค้ก', '', 19.00, 0.00, 0.00, 0.00, 95, 0.00, 0, '', '50972155920250105_112622.jpg', '2025-01-05 04:26:22'),
(5, 'แป้ง', '', 25.00, 0.00, 0.00, 0.00, 94, 20.00, 0, '', '', '2025-01-09 08:14:45'),
(6, 'น้ำเปล่า', '', 5.00, 0.00, 0.00, 0.00, 98, 4.00, 0, '', '', '2025-01-09 08:13:20'),
(8, 'ขนมปัง', '', 10.00, 0.00, 0.00, 0.00, 99, 8.00, 0, '', '', '2025-01-09 09:55:09'),
(1111111111111, 'มาม่า', '', 7.00, 0.00, 0.00, 0.00, 98, 5.00, 0, '', '', '2025-01-09 08:10:55'),
(7777777777777, 'กาแฟกระป๋อง', '', 15.00, 0.00, 0.00, 0.00, 98, 13.00, 0, '', '', '2025-01-09 08:15:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_member`
--
ALTER TABLE `tbl_member`
  ADD PRIMARY KEY (`mem_id`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `tbl_order_detail`
--
ALTER TABLE `tbl_order_detail`
  ADD PRIMARY KEY (`d_id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`p_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_member`
--
ALTER TABLE `tbl_member`
  MODIFY `mem_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `order_id` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_order_detail`
--
ALTER TABLE `tbl_order_detail`
  MODIFY `d_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `p_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7777777777779;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
