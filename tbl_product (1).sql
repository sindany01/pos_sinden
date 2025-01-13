-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2025 at 09:08 AM
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
(2, 'ครีมกันแดด', '', 39.00, 0.00, 0.00, 0.00, 97, 30.00, 0, '', '', '2025-01-09 08:12:39'),
(3, 'เลย์', '', 20.00, 0.00, 0.00, 0.00, 95, 0.00, 0, '', '81476044620250105_111951.jpg', '2025-01-05 04:19:51'),
(4, 'น้ำโค้ก', '', 19.00, 0.00, 0.00, 0.00, 95, 0.00, 0, '', '50972155920250105_112622.jpg', '2025-01-05 04:26:22'),
(5, 'แป้ง', '', 25.00, 0.00, 0.00, 0.00, 93, 20.00, 0, '', '', '2025-01-09 08:14:45'),
(6, 'น้ำเปล่า', '', 5.00, 0.00, 0.00, 0.00, 97, 4.00, 0, '', '', '2025-01-09 08:13:20'),
(7, 'adgfadf', '', 20.00, 0.00, 0.00, 0.00, 100, 10.00, 0, '', '', '2025-01-11 07:49:02'),
(8, 'ขนมปัง', '', 10.00, 0.00, 0.00, 0.00, 98, 8.00, 0, '', '', '2025-01-09 09:55:09'),
(9, 'ลูกอม', '', 2.00, 0.00, 0.00, 0.00, 99, 0.80, 0, '', '', '2025-01-10 16:19:42'),
(10, 'ฟหก', '', 23.00, 0.00, 0.00, 0.00, 99, 20.00, 0, '', '', '2025-01-10 16:20:20'),
(11, 'ฟหกๆไำ', '', 11.00, 0.00, 0.00, 0.00, 99, 10.00, 0, '', '', '2025-01-10 16:20:45'),
(12, 'jytr', '', 5.00, 0.00, 0.00, 0.00, 100, 3.00, 0, '', '', '2025-01-11 07:48:09'),
(13, 'gjo', '', 100.00, 0.00, 0.00, 0.00, 100, 80.00, 0, '', '', '2025-01-11 07:46:51'),
(1111111111111, 'มาม่า', '', 7.00, 0.00, 0.00, 0.00, 97, 5.00, 0, '', '', '2025-01-09 08:10:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`p_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `p_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7777777777785;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
