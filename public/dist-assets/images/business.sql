-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2022 at 07:55 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `business`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(300) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `user_name`, `password`, `first_name`, `last_name`, `email`, `phone`, `created_at`, `updated_at`, `status`) VALUES
(1, 'admin', '675c015f312014a13a413443488f2fbd', 'SRI', 'ADMIN', 'saisridharn@gmail.com', '9666544180', '1589865458', '1589865458', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `orders_id` int(11) DEFAULT NULL,
  `products_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL COMMENT 'Note: Per KG',
  `unit_price` double NOT NULL,
  `shipping_charges` decimal(10,0) NOT NULL DEFAULT 0,
  `total_amount` double NOT NULL,
  `description` text NOT NULL,
  `cart_session_id` varchar(60) NOT NULL,
  `cart_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'if cart_status = 0 remove the cart present list  ',
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `users_id`, `orders_id`, `products_id`, `qty`, `unit_price`, `shipping_charges`, `total_amount`, `description`, `cart_session_id`, `cart_status`, `created_at`, `updated_at`, `status`) VALUES
(1, 2, 23, 21, 1, 950, '0', 950, '<p>Ghee</p>\r\n', '', 0, '1629740549', '', 1),
(2, 2, 23, 22, 1, 20, '0', 20, '<p><a href=\"https://www.flipkart.com/grocery/snacks-beverages/biscuits/pr?sid=73z%2Cujs%2Ceb9&amp;marketplace=GROCERY\">Biscuit</a></p>\r\n', '', 0, '1629740730', '', 1),
(3, 2, 23, 23, 1, 185, '0', 185, '<p><a href=\"https://www.flipkart.com/grocery/staples/ghee-oils/sunflower-oil/pr?sid=73z,bpe,4wu,4vo&amp;marketplace=GROCERY\">Sunflower Oil</a></p>\r\n', '', 0, '1629740759', '', 1),
(4, 2, 23, 24, 2, 60, '0', 120, '<p><a href=\"https://www.flipkart.com/grocery/personal-baby-care/soaps-body-wash/soaps/pr?sid=73z%2Cnjl%2Csn6%2Cr6y&amp;marketplace=GROCERY\">Soap</a></p>\r\n', '', 0, '1629740808', '1629740886', 1),
(5, 2, 26, 22, 2, 20, '0', 40, '<p><a href=\"https://www.flipkart.com/grocery/snacks-beverages/biscuits/pr?sid=73z%2Cujs%2Ceb9&amp;marketplace=GROCERY\">Biscuit</a></p>\r\n', '', 0, '1629743116', '', 1),
(6, 3, NULL, 1, 1, 90, '0', 90, '<p>It is sold in more than 150 countries and it is considered a fish that is easy to combine in any cuisine. You can have it on your menu everyday without getting bored. Now that is why we call it:&nbsp; Your Everyday Fish. Find out more about the healthy aspects and discover a wide variety of recipes.</p>\r\n', '', 1, '1631553672', '', 1),
(7, 3, NULL, 2, 1, 160, '0', 160, '<p>It is sold in more than 150 countries and it is considered a fish that is easy to combine in any cuisine. You can have it on your menu everyday without getting bored. Now that is why we call it:&nbsp; Your Everyday Fish. Find out more about the healthy aspects and discover a wide variety of recipes.</p>\r\n', '', 1, '1632033324', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `code` varchar(30) NOT NULL,
  `name` varchar(120) NOT NULL,
  `per` decimal(24,2) NOT NULL,
  `image` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `code`, `name`, `per`, `image`, `status`) VALUES
(1, 'CAT00001', 'Grocery', '10.00', 'uploads/categorys/9d61d.jpg', 1),
(2, 'CAT10002', 'Vegetables', '5.00', 'uploads/categorys/2a4f2.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `name`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Vizag', '155152555', '155152555', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('4hshg0qh9k9p7nt17mia9k8khq', '127.0.0.1', 1628789164, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393136343b),
('02tkrut7at06a8kdhrq3jij4f3', '127.0.0.1', 1628789168, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393136383b),
('p0s200b3c5sg2la07crhavup80', '127.0.0.1', 1628789174, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393137343b69647c733a313a2231223b757365725f6e616d657c733a353a2261646d696e223b6c6f676765645f696e7c623a313b737563636573735f6d6573736167657c733a34333a222741646d696e2044617368626f617264272c202757656c636f6d6520746f204b756d617220444841424127223b5f5f63695f766172737c613a313a7b733a31353a22737563636573735f6d657373616765223b733a333a226e6577223b7d),
('jq0g15eal8rrjuomsdnl0st330', '127.0.0.1', 1628789174, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393137343b),
('70pitnkami9rahlqmdtngj7do3', '127.0.0.1', 1628789201, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393230313b),
('ltqcooj13slm4bvk1fgred8bt6', '127.0.0.1', 1628789202, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393230323b),
('r5ar90rduej80u4gctta0odtki', '127.0.0.1', 1628789208, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393230383b69647c733a313a2231223b757365725f6e616d657c733a353a2261646d696e223b6c6f676765645f696e7c623a313b737563636573735f6d6573736167657c733a34333a222741646d696e2044617368626f617264272c202757656c636f6d6520746f204b756d617220444841424127223b5f5f63695f766172737c613a313a7b733a31353a22737563636573735f6d657373616765223b733a333a226e6577223b7d),
('f1akfo1cjop45a4o1rfuflsfe6', '127.0.0.1', 1628789208, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393230383b),
('irt5f6319m6voh0pbtmfftlus6', '127.0.0.1', 1628789260, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393236303b),
('1h911ppbbkv5gtsm7mme1njmvf', '127.0.0.1', 1628789262, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393236323b),
('g9tqne6m5ms43qg84kppi282l4', '127.0.0.1', 1628789268, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393236383b69647c733a313a2231223b757365725f6e616d657c733a353a2261646d696e223b6c6f676765645f696e7c623a313b737563636573735f6d6573736167657c733a34333a222741646d696e2044617368626f617264272c202757656c636f6d6520746f204b756d617220444841424127223b5f5f63695f766172737c613a313a7b733a31353a22737563636573735f6d657373616765223b733a333a226e6577223b7d),
('fi46e0v8fqkqcuj6naka7tt02n', '127.0.0.1', 1628789269, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393236393b),
('vkkp9hhecf9psupenst778fqb3', '127.0.0.1', 1628789273, 0x5f5f63695f6c6173745f726567656e65726174657c693a313632383738393237333b);

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `commission` decimal(24,2) NOT NULL,
  `type` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `discount` varchar(50) NOT NULL,
  `from_date` varchar(50) NOT NULL,
  `to_date` varchar(50) NOT NULL,
  `created_at` varchar(50) NOT NULL,
  `updated_at` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `name`, `discount`, `from_date`, `to_date`, `created_at`, `updated_at`, `status`) VALUES
(2, 'sagar', '10', '1591468200', '1591986600', '1591529694', '1591529694', 1);

-- --------------------------------------------------------

--
-- Table structure for table `favourite`
--

CREATE TABLE `favourite` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gst`
--

CREATE TABLE `gst` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `gst` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gst`
--

INSERT INTO `gst` (`id`, `name`, `gst`, `status`) VALUES
(1, 'Zero GST', 0, 1),
(2, '5% GST', 5, 1),
(3, '10% GST', 10, 1),
(4, '12% GST', 12, 1),
(5, '18% GST', 18, 1),
(6, '21% GST', 21, 1),
(7, '28% GST', 28, 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `code` varchar(30) NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `category` int(11) NOT NULL,
  `subcategory` int(11) NOT NULL,
  `rate` decimal(24,2) NOT NULL,
  `gstId` int(11) NOT NULL,
  `discount` decimal(24,2) NOT NULL,
  `commission` decimal(24,2) NOT NULL,
  `volume_points` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `code`, `name`, `description`, `category`, `subcategory`, `rate`, `gstId`, `discount`, `commission`, `volume_points`, `status`) VALUES
(1, 'ITM00001', 'Wireless Bluetooth Tower Speaker ', 'Bluetooth 5.0, USB for MP3 Playback, FM, AUX, Bass-treble-gain control, Stereo Sound, Ground-shaking bass', 1, 2, '7190.00', 4, '10.00', '125.00', 15, 1),
(2, 'ITM00002', 'Wireless Bluetooth Multimedia Speaker', 'Enjoy the finest quality sound with this passionately crafted multimedia speaker in premium wood. ', 1, 1, '2999.00', 5, '5.00', '200.00', 25, 1);

-- --------------------------------------------------------

--
-- Table structure for table `item_images`
--

CREATE TABLE `item_images` (
  `id` int(11) NOT NULL,
  `itemId` int(11) NOT NULL,
  `image` varchar(150) NOT NULL,
  `path` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_images`
--

INSERT INTO `item_images` (`id`, `itemId`, `image`, `path`, `status`) VALUES
(1, 2, '6b6ba.jpg', 'uploads/items/6b6ba.jpg', 1),
(2, 2, 'a60d2.jpg', 'uploads/items/a60d2.jpg', 1),
(3, 2, 'b1fd6.jpg', 'uploads/items/b1fd6.jpg', 1),
(4, 1, '22059.jpg', 'uploads/items/22059.jpg', 1),
(5, 1, 'db799.jpg', 'uploads/items/db799.jpg', 1),
(6, 1, '0fecf.jpg', 'uploads/items/0fecf.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `item_per`
--

CREATE TABLE `item_per` (
  `id` int(11) NOT NULL,
  `code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `item_per`
--

INSERT INTO `item_per` (`id`, `code`, `created_at`, `updated_at`, `status`) VALUES
(1, 'KG', '1496139853', '1496139853', 1);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `image` text NOT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `city_id`, `name`, `image`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 'Sitamma Dara', 'web_assets/uploads/1538232311_location_image.jpg', '1536986973', '1538232311', 1),
(2, 1, 'Akkayapalem', 'web_assets/uploads/1538230078_location_image.jpg', '1536986980', '1538230078', 1),
(3, 1, 'NAD Junction', 'web_assets/uploads/1538230089_location_image.jpg', '1536986986', '1538230089', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mcategory`
--

CREATE TABLE `mcategory` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` text NOT NULL,
  `created_at` varchar(150) NOT NULL,
  `updated_at` varchar(150) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `product_name` varchar(250) NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `mobile` varchar(25) NOT NULL,
  `products_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `product_name`, `user_name`, `mobile`, `products_id`, `seller_id`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Orange', 'ujkumar', '9666544180', 1, 1, '1537635015', '1537635015', 1),
(2, 'Orange', 'ujkumar', '9666544180', 1, 1, '1537635090', '1537635090', 1),
(3, 'Orange', 'Sridhar', '8328424989', 1, 1, '1537685626', '1537685626', 1),
(4, 'flowerwas', 'Ram', '9454751455', 5, 4, '1537692974', '1537692974', 1),
(5, 'DICE', 'Jithendra', '9666544180', 2, 1, '1537702480', '1537702480', 1),
(6, 'DICE', 'Jithendra', '9666544180', 2, 1, '1537702543', '1537702543', 1),
(7, 'DICE', 'jk', '9666544180', 2, 1, '1537702554', '1537702554', 1),
(8, 'DICE', 'ujkumar', '9666544180', 2, 1, '1537702566', '1537702566', 1),
(9, 'DICE', 'ujkumar', '9666544180', 2, 1, '1537702615', '1537702615', 1),
(10, 'flowerwas', 'ujkumar', '9666544180', 5, 4, '1537702621', '1537702621', 1),
(11, 'flowerwas1', 'admin', '9666544180', 6, 1, '1537702631', '1537702631', 1),
(12, 'DICE', 'admin', '9666544180', 2, 1, '1537702678', '1537702678', 1),
(13, 'DICE', 'admin', '9666544180', 2, 1, '1537702716', '1537702716', 1),
(14, 'flowerwas1', 'sridhar', '8328424989', 6, 1, '1537702822', '1537702822', 1),
(15, 'flowerwas1', 'Test', '9666544180', 6, 1, '1537705706', '1537705706', 1),
(16, 'dice', 'sri', '8328424989', 2, 1, '1537710636', '1537710636', 1),
(17, 'dice', 'sri', '8328424989', 2, 1, '1537710670', '1537710670', 1),
(18, 'dice', 'sri', '8328424989', 2, 1, '1537710676', '1537710676', 1),
(19, 'DICE', 'sri', '8328424989', 2, 1, '1537710796', '1537710796', 1),
(20, 'dice', 'sri', '8328424989', 2, 1, '1537710803', '1537710803', 1),
(21, 'dice', 'sri', '8328424989', 2, 1, '1537710901', '1537710901', 1),
(22, 'DICE', 'sri', '8328424989', 2, 1, '1537710968', '1537710968', 1),
(23, 'Dice', 'JK', '9666544180', 2, 1, '1537711061', '1537711061', 1),
(24, 'Dice', 'JK', '9666544180', 2, 1, '1537711117', '1537711117', 1),
(25, 'Dice', 'JK', '9666544180', 2, 1, '1537711143', '1537711143', 1),
(26, 'flowerwas1', 'sridhar', '8328424988', 6, 1, '1537711178', '1537711178', 1),
(27, 'flowerwas1', 'sridhar', '8328424988', 1, 1, '1537711298', '1537711298', 1),
(28, 'flowerwas1', 'sridhar', '8328424988', 6, 1, '1537711312', '1537711312', 1),
(29, 'flowerwas1', 'sridhar', '8328424988', 1, 1, '1537711332', '1537711332', 1),
(30, 'flowerwas1', 'sridhar', '8328424988', 1, 1, '1537711352', '1537711352', 1),
(31, 'flowerwas1', 'sridhar', '8328424988', 1, 1, '1537723128', '1537723128', 1),
(32, 'flowerwas1', 'sridhar', '8328424988', 6, 1, '1537724302', '1537724302', 1),
(33, 'flowerwas', 'vidhysagar', '7093955027', 5, 4, '1537889064', '1537889064', 1),
(34, 'flowerwas1', 'vidhysagar', '7093955027', 1, 1, '1537952658', '1537952658', 1),
(35, 'flowerwas1', 'vidhysagar', '7093955027', 1, 1, '1537952674', '1537952674', 1),
(36, 'flowerwas1', 'admin', '9666544180', 6, 1, '1538201246', '1538201246', 1),
(37, 'flowerwas1', 'admin', '9666544180', 6, 1, '1538201279', '1538201279', 1),
(38, 'flowerwas', 'ujkumar', '9666544180', 5, 4, '1538201712', '1538201712', 1),
(39, 'flowerwas', 'ujkumar', '9666544180', 5, 4, '1538201741', '1538201741', 1),
(40, 'flowerwas', 'admin', '9666544180', 5, 4, '1538201800', '1538201800', 1),
(41, 'flowerwas1', 'sridhar', '8328424989', 1, 1, '1538202321', '1538202321', 1),
(42, 'flowerwas', 'Jithendra ', '9666544180', 5, 4, '1538202668', '1538202668', 1),
(43, 'flowerwas', 'Jithendra ', '9666544180', 5, 4, '1538202707', '1538202707', 1),
(44, 'flowerwas', 'Jithendra ', '9666544180', 5, 4, '1538202717', '1538202717', 1),
(45, 'flowerwas', 'Jithendra', '9666544180', 5, 4, '1538202804', '1538202804', 1),
(46, 'flowerwas', 'Jithendra ', '9666544180', 5, 4, '1538202849', '1538202849', 1),
(47, 'flowerwas', 'Jithendra', '9666544180', 5, 4, '1538202854', '1538202854', 1),
(48, 'flowerwas', 'Jithendra', '9666544180', 5, 4, '1538202918', '1538202918', 1),
(49, 'flowerwas', 'Jithendra ', '9666544180', 5, 4, '1538202925', '1538202925', 1),
(50, 'flowerwas', 'Jithendra ', '9666544180', 5, 4, '1538202959', '1538202959', 1),
(51, 'flowerwas', '9666544180', '9390090966', 5, 4, '1538202984', '1538202984', 1),
(52, 'flowerwas1', 'Dj yy', '9666544180', 1, 1, '1538203030', '1538203030', 1),
(53, 'flowerwas1', 'Guhg', '9666544180', 1, 1, '1538203051', '1538203051', 1),
(54, 'flowerwas', 'Jjgh', '9666544180', 5, 4, '1538203131', '1538203131', 1),
(55, 'flowerwas', 'Gjh', '9666544180', 5, 4, '1538203248', '1538203248', 1),
(56, 'flowerwas1', 'Jithendra ', '9666544180', 1, 1, '1538203261', '1538203261', 1),
(57, 'flowerwas', 'Gfh', '8888888888', 5, 4, '1538203525', '1538203525', 1),
(58, 'flowerwas1', 'Gfh', '8888888888', 1, 1, '1538203855', '1538203855', 1),
(59, 'Dice', 'JK', '9666544180', 2, 1, '1538204129', '1538204129', 1),
(60, 'flowerwas1', 'sri', '8464061136', 1, 1, '1538204752', '1538204752', 1),
(61, 'DICE', 'sri', '8464061136', 2, 1, '1538204762', '1538204762', 1),
(62, 'flowerwas', 'Hihh', '9666544180', 5, 4, '1538360288', '1538360288', 1),
(63, 'flowerwas1', 'Fh', '9666544180', 6, 1, '1538360307', '1538360307', 1),
(64, 'flowerwas', 'sri', '8464061136', 5, 4, '1538400422', '1538400422', 1),
(65, 'flowerwas1', 'sri', '8464061136', 1, 1, '1538403314', '1538403314', 1),
(66, 'flowerwas1', 'vidhysagar', '7093955027', 1, 1, '1538403914', '1538403914', 1),
(67, 'flowerwas1', 'vidhysagar', '7093955027', 1, 1, '1538403978', '1538403978', 1),
(68, 'flowerwas1', 'sri', '8328424989', 1, 1, '1538404011', '1538404011', 1),
(69, 'flowerwas1', 'sri', '8328424989', 1, 1, '1538404081', '1538404081', 1),
(70, 'flowerwas1', 'sri', '8328424989', 1, 1, '1538404504', '1538404504', 1),
(71, 'flowerwas1', 'sri', '8328424989', 1, 1, '1538464891', '1538464891', 1),
(72, 'flowerwas1', 'sri', '8328424989', 6, 1, '1538592748', '1538592748', 1),
(73, 'flowerwas1', 'sri', '8328424989', 1, 1, '1538592753', '1538592753', 1),
(74, 'flowerwas', 'sri', '8328424989', 5, 4, '1538598831', '1538598831', 1),
(75, 'flowerwas1', 'sridhar', '8464061136', 1, 1, '1538733150', '1538733150', 1),
(76, 'flowerwas1', 'sridhar', '8464061136', 1, 1, '1538760282', '1538760282', 1),
(77, 'flowerwas', 'sagar', '7093955027', 5, 4, '1538999992', '1538999992', 1),
(78, 'DICE', 'sagar', '7093955027', 2, 1, '1539000133', '1539000133', 1),
(79, 'flowerwas', 'Fh', '9666544180', 5, 4, '1539001035', '1539001035', 1),
(80, 'flowerwas', 'Fh', '9666544180', 5, 4, '1539001038', '1539001038', 1),
(81, 'flowerwas', 'Fh', '9666544180', 5, 4, '1539001041', '1539001041', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `buyer_name` varchar(150) DEFAULT NULL,
  `amount` decimal(24,2) DEFAULT NULL,
  `purpose` varchar(120) DEFAULT NULL,
  `expires_at` varchar(60) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `send_sms` int(11) DEFAULT NULL,
  `send_email` int(11) DEFAULT NULL,
  `sms_status` int(11) DEFAULT NULL,
  `email_status` int(11) DEFAULT NULL,
  `shorturl` text DEFAULT NULL,
  `longurl` text DEFAULT NULL,
  `redirect_url` text DEFAULT NULL,
  `webhook` varchar(100) DEFAULT NULL,
  `allow_repeated_payments` varchar(100) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `modified_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `phone`, `email`, `buyer_name`, `amount`, `purpose`, `expires_at`, `status`, `send_sms`, `send_email`, `sms_status`, `email_status`, `shorturl`, `longurl`, `redirect_url`, `webhook`, `allow_repeated_payments`, `customer_id`, `created_at`, `modified_at`) VALUES
(1, NULL, NULL, NULL, '10.00', 'TEST', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/3d02fe1eb5ef45e19e7ae1c79792859b', 'https://karshaka.org/admin/latest/', NULL, '0', NULL, '2021-09-04', '2021-09-04'),
(2, NULL, NULL, NULL, '665.68', 'TEST', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/569311a02b8346b09e0c7f5f052738e5', 'https://karshaka.org/admin/latest/', NULL, '0', NULL, '2021-09-04', '2021-09-04'),
(3, NULL, NULL, NULL, '10.00', 'TEST', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/f9a9b166b97f474f872e698ae398a553', 'https://karshaka.org/admin/latest/Checkout/success', NULL, '0', NULL, '2021-09-04', '2021-09-04'),
(4, NULL, NULL, NULL, '10.00', 'TEST', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/d98456cde2ca4c0cbeee0720e60b33f6', 'https://karshaka.org/admin/latest/Checkout/success', NULL, '0', NULL, '2021-09-09', '2021-09-09'),
(5, NULL, NULL, NULL, '160.00', 'TEST', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/7bd6a4d0dc77486385e593f2368128a5', 'https://karshaka.org/admin/latest/Checkout/success', NULL, '0', NULL, '2021-09-09', '2021-09-09'),
(6, NULL, NULL, NULL, '910.00', 'TEST', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/f516bcf8a9ca4e18a3a5d397b4db2a5f', 'https://karshaka.org/admin/latest/Checkout/success', NULL, '0', NULL, '2021-09-13', '2021-09-13'),
(7, NULL, NULL, NULL, '910.00', 'TEST', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/c7d542ef7498407cb3c1589b86338cdd', 'https://karshaka.org/admin/latest/Checkout/success', NULL, '0', NULL, '2021-09-13', '2021-09-13'),
(8, NULL, NULL, NULL, '910.00', 'TEST', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/11a01684c25c44ff8aab32a13b3c465d', 'https://karshaka.org/Checkout/success', NULL, '0', NULL, '2021-09-15', '2021-09-15'),
(9, NULL, NULL, NULL, '910.00', 'Cart Payment', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/bf43a2d2f6384f798cabc11933d1d19c', 'https://karshaka.org/Checkout/success', NULL, '0', NULL, '2021-09-15', '2021-09-15'),
(10, NULL, NULL, NULL, '1660.00', 'Cart Payment', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/e3f30c585318401cbbc05af67173c97b', 'https://karshaka.org/Checkout/success', NULL, '0', NULL, '2021-09-19', '2021-09-19'),
(11, NULL, NULL, NULL, '10.00', 'Cart Payment', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/0975878355a149ccbfc927d81bb48aa0', 'https://karshaka.org/Checkout/success', NULL, '0', NULL, '2022-04-17', '2022-04-17'),
(12, NULL, NULL, NULL, '7180.00', 'Cart Payment', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/a95f00cd6aee4c38a5809500cc838721', 'https://karshaka.org/Checkout/success', NULL, '0', NULL, '2022-04-27', '2022-04-27'),
(13, NULL, NULL, NULL, '5988.00', 'Cart Payment', NULL, 0, 0, 0, NULL, NULL, NULL, 'https://www.instamojo.com/@Karshaka/a8b0b7c8d25b4a449ace91a7852586c3', 'https://karshaka.org/Checkout/success', NULL, '0', NULL, '2022-04-30', '2022-04-30');

-- --------------------------------------------------------

--
-- Table structure for table `orders_old`
--

CREATE TABLE `orders_old` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `order_number` varchar(250) DEFAULT NULL,
  `order_qty` int(11) NOT NULL,
  `order_total_items` int(11) NOT NULL,
  `order_cart_session` varchar(60) DEFAULT NULL,
  `email` varchar(60) NOT NULL,
  `mobile` varchar(60) NOT NULL,
  `address` text NOT NULL,
  `pincode` int(11) DEFAULT NULL,
  `orderprice` double NOT NULL,
  `promo` varchar(50) NOT NULL,
  `promo_price` double NOT NULL,
  `shippingprice` double NOT NULL,
  `gst` double NOT NULL,
  `totalpayableprice` double NOT NULL,
  `priest_type` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `catering_type` enum('YES','NO') DEFAULT 'NO',
  `order_status` tinyint(4) NOT NULL DEFAULT 0,
  `order_date` varchar(25) DEFAULT NULL,
  `payment_type` enum('COD','ONLINE') DEFAULT NULL,
  `payment_status_type` enum('SUCCESS','PENDING','FAILED') DEFAULT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `item_name` varchar(180) DEFAULT NULL,
  `amount` decimal(24,2) DEFAULT NULL,
  `offer_price` decimal(24,2) DEFAULT NULL,
  `discount_amount` decimal(24,2) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `qty`, `item_name`, `amount`, `offer_price`, `discount_amount`, `user`, `created_at`, `status`) VALUES
(2, NULL, 2, 2, 'Wireless Bluetooth Multimedia Speaker', '2999.00', '5.00', '2994.00', 9, '2022-05-01 03:25:37', 1),
(3, 'pay_JPyntkzPShDw04', 2, 2, 'Wireless Bluetooth Multimedia Speaker', '2999.00', '5.00', '2994.00', 9, '2022-05-01 03:28:03', 1),
(4, 'pay_JPyzEGV4GECLMF', 2, 2, 'Wireless Bluetooth Multimedia Speaker', '2999.00', '5.00', '2994.00', 9, '2022-05-01 03:38:48', 1),
(5, 'pay_JUc8pvU1eDMAQT', 2, 3, 'Wireless Bluetooth Multimedia Speaker', '2999.00', '5.00', '2994.00', 21, '2022-05-13 08:32:55', 1),
(6, 'pay_JUc8pvU1eDMAQT', 1, 2, 'Wireless Bluetooth Tower Speaker ', '7190.00', '10.00', '7180.00', 21, '2022-05-13 08:32:55', 1),
(7, 'pay_JWEO9OiilzPfCw', 2, 3, 'Wireless Bluetooth Multimedia Speaker', '2999.00', '5.00', '2994.00', 21, '2022-05-17 10:36:48', 1),
(8, 'pay_JWFv0T7siOlwo6', 1, 2, 'Wireless Bluetooth Tower Speaker ', '7190.00', '10.00', '7180.00', 21, '2022-05-17 12:06:35', 1),
(9, 'pay_JWFw88HIgVuG4m', 1, 2, 'Wireless Bluetooth Tower Speaker ', '7190.00', '10.00', '7180.00', 21, '2022-05-17 12:07:40', 1),
(10, 'pay_JWG4JuKWAiwFzh', 2, 2, 'Wireless Bluetooth Multimedia Speaker', '2999.00', '5.00', '2994.00', 21, '2022-05-17 12:15:25', 1),
(11, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:26:20', 1),
(12, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:26:22', 1),
(13, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:27:04', 1),
(14, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:27:43', 1),
(15, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:28:23', 1),
(16, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:29:52', 1),
(17, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:30:14', 1),
(18, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:30:35', 1),
(19, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:30:49', 1),
(20, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:31:03', 1),
(21, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:33:40', 1),
(22, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:34:16', 1),
(23, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:34:59', 1),
(24, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:36:18', 1),
(25, 'pay_JWG4JuKWAiwFzh', 1, 1, 'test_product', '100.00', '10.00', '90.00', 21, '2022-05-17 12:38:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `item_per` varchar(25) DEFAULT NULL,
  `price` float(20,2) NOT NULL,
  `offer_price` double(20,2) DEFAULT NULL,
  `image` text NOT NULL,
  `product_status_type` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `vendorId` int(11) NOT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `item_per`, `price`, `offer_price`, `image`, `product_status_type`, `vendorId`, `created_at`, `updated_at`, `status`) VALUES
(1, 5, 'Product 1', '<p>It is sold in more than 150 countries and it is considered a fish that is easy to combine in any cuisine. You can have it on your menu everyday without getting bored. Now that is why we call it:&nbsp; Your Everyday Fish. Find out more about the healthy aspects and discover a wide variety of recipes.</p>\r\n', 'KG', 100.00, 90.00, 'web_assets/uploads/1630336812_products_image.jpg', 'Active', 0, '1630336813', '1630336813', 1),
(2, 5, 'Product 2', '<p>It is sold in more than 150 countries and it is considered a fish that is easy to combine in any cuisine. You can have it on your menu everyday without getting bored. Now that is why we call it:&nbsp; Your Everyday Fish. Find out more about the healthy aspects and discover a wide variety of recipes.</p>\r\n', 'KG', 200.00, 160.00, 'web_assets/uploads/1630336842_products_image.PNG', 'Active', 0, '1630336842', '1630336842', 1),
(3, 5, 'Samsung Mobile ', 'Description of Sun', 'Pic', 1000.00, 8000.00, 'web_assets/uploads/1630336882_products_image.jpg', 'Inactive', 3, '1630336882', '1632520319', 1),
(4, 5, 'Product 4', 'Product Description', 'NOS', 250.00, 200.00, 'web_assets/uploads/1630435763_products_image.jpg', 'Active', 3, '1630435763', '', 1),
(5, 5, 'Product 5', 'Product Description', 'KG', 1000.00, 950.00, 'web_assets/uploads/1630435915_products_image.jpg', 'Active', 3, '1630435915', '', 1),
(6, 5, 'Product 6', 'Product Description', 'NOS', 800.00, 750.00, 'web_assets/uploads/1630440512_products_image.jpg', 'Active', 3, '1630440512', '', 1),
(7, 5, 'Product 7', '<p>Product Description</p>\r\n', 'NOS', 1000.00, 950.00, 'web_assets/uploads/1630603461_products_image.jpeg', 'Active', 3, '1630603461', '1630603461', 1),
(8, 5, 'Product 8', '<p>Product Descrioptipon</p>\r\n', 'NOS', 5562.36, 5501.36, 'web_assets/uploads/1630603672_products_image.jpg', 'Inactive', 1, '1630603672', '1630604433', 1),
(9, 4, 'Product 9', '<p>product Description</p>\r\n', 'NOS', 1050.01, 999.99, 'web_assets/uploads/1630604515_products_image.jpg', 'Active', 1, '1630604515', '1630604515', 1),
(10, 4, 'Product 10', '<p>It is sold in more than 150 countries and it is considered a fish that is easy to combine in any cuisine. You can have it on your menu everyday without getting bored. Now that is why we call it:&nbsp; Your Everyday Fish. Find out more about the healthy aspects and discover a wide variety of recipes.</p>\r\n', 'KG', 100.00, 90.00, 'web_assets/uploads/1630336812_products_image.jpg', 'Active', 0, '1630336813', '1630336813', 1),
(11, 4, 'Product 11', '<p>It is sold in more than 150 countries and it is considered a fish that is easy to combine in any cuisine. You can have it on your menu everyday without getting bored. Now that is why we call it:&nbsp; Your Everyday Fish. Find out more about the healthy aspects and discover a wide variety of recipes.</p>\r\n', 'KG', 200.00, 160.00, 'web_assets/uploads/1630336842_products_image.PNG', 'Active', 0, '1630336842', '1630336842', 1),
(12, 4, 'Product 12', '<p>It is sold in more than 150 countries and it is considered a fish that is easy to combine in any cuisine. You can have it on your menu everyday without getting bored. Now that is why we call it:&nbsp; Your Everyday Fish. Find out more about the healthy aspects and discover a wide variety of recipes.</p>\r\n', 'KG', 250.00, 200.00, 'web_assets/uploads/1630336882_products_image.jpg', 'Active', 2, '1630336882', '1630336882', 1),
(13, 4, 'Product 13', 'Product Description', 'NOS', 250.00, 200.00, 'web_assets/uploads/1630435763_products_image.jpg', 'Active', 3, '1630435763', '', 1),
(14, 4, 'Product 14', 'Product Description', 'KG', 1000.00, 950.00, 'web_assets/uploads/1630435915_products_image.jpg', 'Active', 3, '1630435915', '', 1),
(15, 4, 'Product 15', 'Product Description', 'NOS', 800.00, 750.00, 'web_assets/uploads/1630440512_products_image.jpg', 'Active', 3, '1630440512', '', 1),
(16, 4, 'Product 16', '<p>Product Description</p>\r\n', 'NOS', 1000.00, 950.00, 'web_assets/uploads/1630603461_products_image.jpeg', 'Active', 3, '1630603461', '1630603461', 1),
(17, 4, 'Product 17', '<p>Product Descrioptipon</p>\r\n', 'NOS', 5562.36, 5501.36, 'web_assets/uploads/1630603672_products_image.jpg', 'Inactive', 1, '1630603672', '1630604433', 1),
(18, 4, 'Product 18', '<p>product Description</p>\r\n', 'NOS', 1050.01, 999.99, 'web_assets/uploads/1630604515_products_image.jpg', 'Active', 1, '1630604515', '1630604515', 1),
(19, 3, 'Grocery', '<p>This is Product Description</p>\r\n', 'NOS', 152.57, 2.00, 'web_assets/uploads/1630686387_products_image.jpg', 'Active', 3, '1630686387', '1630686517', 1),
(20, 5, 'sample Product', 'description', 'nos', 250.00, 200.00, 'web_assets/uploads/e264926f62d41ac2c995d1a547c39f28.jpg', 'Inactive', 3, '1632158235', '1632158235', 1),
(21, 3, 'Samsung Mobile M20', 'Description of Samsung Mobile', 'Pic', 1000.00, 8000.00, 'web_assets/uploads/733ccfd29b62c1d0e69c5462956944ae.jpg', 'Inactive', 3, '1632517471', '1632521035', 1),
(22, 1, 'HTC MO21RV3C', 'Htc Mobile description', 'PIC', 16500.00, 15000.00, 'web_assets/uploads/daf696a8403b64fdbfaa179ce3a792ec.jpg', 'Inactive', 3, '1632521500', '1632521500', 1);

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(11) NOT NULL,
  `refcode` varchar(60) NOT NULL,
  `memtype` tinyint(4) NOT NULL COMMENT '1->Vendor, 2->customer',
  `name` varchar(160) DEFAULT NULL,
  `email` text DEFAULT NULL,
  `username` varchar(60) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `referenceId` int(11) NOT NULL DEFAULT 0,
  `active_status` tinyint(1) NOT NULL DEFAULT 0,
  `payment_status` tinyint(1) NOT NULL DEFAULT 0,
  `plan` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `jdate` date DEFAULT NULL,
  `path` text NOT NULL,
  `image` text NOT NULL,
  `pan` varchar(30) NOT NULL,
  `aadhar` varchar(35) NOT NULL,
  `accno` varchar(90) NOT NULL,
  `bankname` varchar(120) NOT NULL,
  `ifsc` varchar(30) NOT NULL,
  `bankbranch` varchar(150) NOT NULL,
  `panfront` text NOT NULL,
  `panback` text NOT NULL,
  `aadharfront` text NOT NULL,
  `aadharback` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `refcode`, `memtype`, `name`, `email`, `username`, `password`, `phone`, `address`, `referenceId`, `active_status`, `payment_status`, `plan`, `status`, `jdate`, `path`, `image`, `pan`, `aadhar`, `accno`, `bankname`, `ifsc`, `bankbranch`, `panfront`, `panback`, `aadharfront`, `aadharback`) VALUES
(1, 'BP8674092', 0, 'Super User', 'su@absolin.in', 'su', 'e10adc3949ba59abbe56e057f20f883e', '9177012346', 'D.NO.9-3-22/1, Pitapuram Colony, Visakhpatnam-530003', 0, 1, 0, 0, 1, '2021-07-07', '', '', '', '', '', '', '', '', '', '', '', ''),
(27, 'DP9934710', 1, 'Rajesh', 'rajesh@vowerp.com', 'rajesh', '675c015f312014a13a413443488f2fbd', '9247916929', 'Santhapalem', 1, 1, 1, 2, 1, '2022-05-25', '', '', '', '', '', '', '', '', '', '', '', ''),
(28, 'SP315484', 3, 'ABC Company', '', 'SP315484', '5f4dcc3b5aa765d61d8327deb882cf99', '8521479630', 'Maddilapalem, VSP', 0, 0, 0, 0, 1, '2022-05-25', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(253) NOT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Seller', '1537080043', '', 1),
(2, 'User', '1537080043', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `scategory`
--

CREATE TABLE `scategory` (
  `id` int(11) NOT NULL,
  `mcategory_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` text NOT NULL,
  `created_at` varchar(50) NOT NULL,
  `updated_at` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `site_title` varchar(300) NOT NULL,
  `site_location` text NOT NULL,
  `enquiry_email` varchar(100) NOT NULL,
  `from_email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `gst_percentage` double NOT NULL DEFAULT 0,
  `enquiry_phone` varchar(250) NOT NULL,
  `logo_image` varchar(100) NOT NULL,
  `favicon_image` varchar(100) NOT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_title`, `site_location`, `enquiry_email`, `from_email`, `phone`, `gst_percentage`, `enquiry_phone`, `logo_image`, `favicon_image`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Karshak', 'Visakhapatnam', 'support@Karshak.in', 'support@Karshak.in', '9123456789', 18, '9123456789', 'web_assets/images/logo.png', 'web_assets/uploads/1627715917_site_settings_favicon_image.png', '1488536379', '1627715917', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `image` varchar(250) NOT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `title`, `image`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Slider 1', 'web_assets/uploads/1630336465_sliders_image.jpg', '1630336465', '1630336465', 1),
(2, 'Slider 2', 'web_assets/uploads/1630336477_sliders_image.jpg', '1630336477', '1630336477', 1),
(3, 'Slider 3', 'web_assets/uploads/1630336490_sliders_image.jpg', '1630336490', '1630336490', 1),
(4, 'Slider 4', 'web_assets/uploads/1630336504_sliders_image.jpg', '1630336504', '1630336504', 1),
(5, 'Slider 5', 'web_assets/uploads/1630336519_sliders_image.jpg', '1630336519', '1630336519', 1),
(6, 'Horizontal', 'web_assets/uploads/1630766960_sliders_image.jpg', '1630766961', '1630766961', 1),
(7, 'Slider 8', 'web_assets/uploads/1630767246_sliders_image.jpg', '1630767246', '1630767246', 1),
(8, 'Large Width', 'web_assets/uploads/1630767280_sliders_image.jpg', '1630767280', '1630767280', 1),
(9, 'Testing Slider', 'web_assets/uploads/1630770649_sliders_image.jpg', '1630770649', '1630770649', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stockpoint`
--

CREATE TABLE `stockpoint` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(12) NOT NULL,
  `username` varchar(120) NOT NULL,
  `qty` int(11) NOT NULL,
  `items` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stockpoint`
--

INSERT INTO `stockpoint` (`id`, `userId`, `name`, `address`, `phone`, `username`, `qty`, `items`, `date`, `status`) VALUES
(1, 28, 'ABC Company', 'Maddilapalem, VSP', '8521479630', 'Nagesh Medisetty', 0, '', '2022-05-25 05:38:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stockpointitems`
--

CREATE TABLE `stockpointitems` (
  `id` int(11) NOT NULL,
  `stockpointId` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `soldqty` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stockpointitems`
--

INSERT INTO `stockpointitems` (`id`, `stockpointId`, `qty`, `soldqty`, `product`, `status`) VALUES
(1, 1, 100, 0, 1, 1),
(2, 1, 150, 0, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `id` int(11) NOT NULL,
  `code` varchar(30) NOT NULL,
  `category` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `image` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`id`, `code`, `category`, `name`, `image`, `status`) VALUES
(1, 'SCAT00001', 1, 'Sub Category', 'uploads/subcategorys/d4143.jpg', 1),
(2, 'SCAT00002', 1, 'Oils', 'uploads/subcategorys/21a9a.jpg', 1),
(3, 'SCAT00003', 2, 'General Manager The General Manager MKTG', 'uploads/subcategorys/dac0c.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscribe`
--

CREATE TABLE `subscribe` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type` tinyint(4) NOT NULL COMMENT '1 => Vendor\r\n2 => Customer',
  `name` varchar(250) DEFAULT NULL,
  `email` varchar(250) NOT NULL,
  `mobile` varchar(25) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `pincode` int(11) DEFAULT NULL,
  `password` varchar(225) DEFAULT NULL,
  `token` longtext DEFAULT NULL,
  `otp` varchar(250) DEFAULT NULL,
  `otp_status` enum('Verified','NotVerified') DEFAULT 'NotVerified',
  `image` varchar(250) DEFAULT NULL,
  `active_status_type` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive',
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `name`, `email`, `mobile`, `address`, `pincode`, `password`, `token`, `otp`, `otp_status`, `image`, `active_status_type`, `created_at`, `updated_at`, `status`) VALUES
(1, 0, '', '', '7093955027', '', NULL, NULL, NULL, '9228', 'NotVerified', NULL, 'Inactive', '1624737999', '1628524675', 1),
(2, 1, 'Nagesh Medisetty', 'nageshvb2028@gmail.com', '9177012346', 'Maddilapalem, Visakhapatnam', 530003, NULL, NULL, NULL, 'Verified', NULL, 'Active', '1628348448', '1629741432', 1),
(3, 1, 'Rajesh Kumar Raju Alluri', 'rajesh_alluri@gmail.com', '9247916929', '18-441/1, Chinna Gadhili, Hanumanthavaka, Visakhapatnam, Andhra Pradesh 530040, India', NULL, 'fb0eec58ddc2c6caaa5e5c33d6a25ece', NULL, '4372', 'NotVerified', NULL, 'Active', '1630428767', '1632034693', 1),
(4, 1, 'Kurma Medisetty', 'kurmamkurmam@gmail.com', '9885320884', NULL, NULL, 'fb0eec58ddc2c6caaa5e5c33d6a25ece', NULL, NULL, 'NotVerified', NULL, 'Active', '1630429620', '', 1),
(5, 2, 'niharika', 'niha@gmail.com', '9177012345', NULL, NULL, '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL, 'NotVerified', NULL, 'Active', '1631520786', '', 1),
(6, 1, 'Neeharika Medisetty', 'neeha@absolin.com', '9014455188', 'Maddilapalem, Visakhapatnam', NULL, 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'NotVerified', NULL, 'Active', '1632315125', '1632315125', 1);

-- --------------------------------------------------------

--
-- Table structure for table `valumepoints`
--

CREATE TABLE `valumepoints` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `value` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_items`
-- (See below for the actual view)
--
CREATE TABLE `view_items` (
`id` int(11)
,`code` varchar(30)
,`name` varchar(120)
,`description` text
,`category` varchar(120)
,`rate` decimal(24,2)
,`gstId` int(11)
,`gst` varchar(120)
,`discount` decimal(24,2)
,`commission` decimal(24,2)
,`status` tinyint(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_products`
-- (See below for the actual view)
--
CREATE TABLE `view_products` (
`id` int(11)
,`category_id` int(11)
,`name` varchar(250)
,`description` text
,`item_per` varchar(25)
,`price` float(20,2)
,`offer_price` double(20,2)
,`image` text
,`product_status_type` enum('Active','Inactive')
,`vendorId` int(11)
,`created_at` varchar(25)
,`updated_at` varchar(25)
,`status` tinyint(1)
,`categoryname` varchar(120)
,`vendorname` varchar(250)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_register`
-- (See below for the actual view)
--
CREATE TABLE `view_register` (
`id` int(11)
,`refcode` varchar(60)
,`memtype` tinyint(4)
,`name` varchar(160)
,`email` text
,`username` varchar(60)
,`password` text
,`phone` varchar(12)
,`pan` varchar(30)
,`aadhar` varchar(35)
,`accno` varchar(90)
,`bankname` varchar(120)
,`ifsc` varchar(30)
,`bankbranch` varchar(150)
,`address` text
,`referenceId` int(11)
,`active_status` tinyint(1)
,`payment_status` tinyint(1)
,`plan` tinyint(1)
,`status` tinyint(1)
,`jdate` date
,`path` text
,`image` text
,`member` varchar(11)
,`walletamt` decimal(46,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_stockpoint`
-- (See below for the actual view)
--
CREATE TABLE `view_stockpoint` (
`id` int(11)
,`userId` int(11)
,`username` varchar(120)
,`loginuser` varchar(60)
,`name` varchar(100)
,`address` text
,`phone` varchar(12)
,`items` text
,`date` timestamp
,`status` tinyint(1)
,`totalitems` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `wallet_amt` decimal(24,2) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `payment_active` tinyint(1) NOT NULL,
  `order_id` text NOT NULL,
  `type` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`id`, `userId`, `wallet_amt`, `date`, `status`, `payment_active`, `order_id`, `type`) VALUES
(20, 27, '3500.00', '2022-05-25', 1, 0, '', 0);

-- --------------------------------------------------------

--
-- Structure for view `view_items`
--
DROP TABLE IF EXISTS `view_items`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_items`  AS SELECT `items`.`id` AS `id`, `items`.`code` AS `code`, `items`.`name` AS `name`, `items`.`description` AS `description`, (select `category`.`name` from `category` where `category`.`id` = `items`.`category`) AS `category`, `items`.`rate` AS `rate`, `items`.`gstId` AS `gstId`, (select `gst`.`name` from `gst` where `gst`.`id` = `items`.`gstId`) AS `gst`, `items`.`discount` AS `discount`, `items`.`commission` AS `commission`, `items`.`status` AS `status` FROM `items` ;

-- --------------------------------------------------------

--
-- Structure for view `view_products`
--
DROP TABLE IF EXISTS `view_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_products`  AS SELECT `products`.`id` AS `id`, `products`.`category_id` AS `category_id`, `products`.`name` AS `name`, `products`.`description` AS `description`, `products`.`item_per` AS `item_per`, `products`.`price` AS `price`, `products`.`offer_price` AS `offer_price`, `products`.`image` AS `image`, `products`.`product_status_type` AS `product_status_type`, `products`.`vendorId` AS `vendorId`, `products`.`created_at` AS `created_at`, `products`.`updated_at` AS `updated_at`, `products`.`status` AS `status`, (select `category`.`name` from `category` where `category`.`id` = `products`.`category_id`) AS `categoryname`, (select `users`.`name` from `users` where `users`.`id` = `products`.`vendorId`) AS `vendorname` FROM `products` ;

-- --------------------------------------------------------

--
-- Structure for view `view_register`
--
DROP TABLE IF EXISTS `view_register`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_register`  AS SELECT `register`.`id` AS `id`, `register`.`refcode` AS `refcode`, `register`.`memtype` AS `memtype`, `register`.`name` AS `name`, `register`.`email` AS `email`, `register`.`username` AS `username`, `register`.`password` AS `password`, `register`.`phone` AS `phone`, `register`.`pan` AS `pan`, `register`.`aadhar` AS `aadhar`, `register`.`accno` AS `accno`, `register`.`bankname` AS `bankname`, `register`.`ifsc` AS `ifsc`, `register`.`bankbranch` AS `bankbranch`, `register`.`address` AS `address`, `register`.`referenceId` AS `referenceId`, `register`.`active_status` AS `active_status`, `register`.`payment_status` AS `payment_status`, `register`.`plan` AS `plan`, `register`.`status` AS `status`, `register`.`jdate` AS `jdate`, `register`.`path` AS `path`, `register`.`image` AS `image`, CASE WHEN `register`.`memtype` = 1 THEN 'Vendor' WHEN `register`.`memtype` = 2 THEN 'Customer' ELSE 'Stock Point' END AS `member`, (select coalesce(sum(`wallet`.`wallet_amt`),0) from `wallet` where `wallet`.`userId` = `register`.`id`) AS `walletamt` FROM `register` WHERE `register`.`status` = 1 AND `register`.`id` <> 1 ;

-- --------------------------------------------------------

--
-- Structure for view `view_stockpoint`
--
DROP TABLE IF EXISTS `view_stockpoint`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_stockpoint`  AS SELECT `stockpoint`.`id` AS `id`, `stockpoint`.`userId` AS `userId`, `stockpoint`.`username` AS `username`, (select `register`.`username` from `register` where `register`.`id` = `stockpoint`.`userId`) AS `loginuser`, `stockpoint`.`name` AS `name`, `stockpoint`.`address` AS `address`, `stockpoint`.`phone` AS `phone`, `stockpoint`.`items` AS `items`, `stockpoint`.`date` AS `date`, `stockpoint`.`status` AS `status`, (select count(0) from `stockpointitems` where `stockpointitems`.`stockpointId` = `stockpoint`.`id`) AS `totalitems` FROM `stockpoint` WHERE `stockpoint`.`status` = 1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_id` (`products_id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `orders_id` (`orders_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favourite`
--
ALTER TABLE `favourite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `gst`
--
ALTER TABLE `gst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_images`
--
ALTER TABLE `item_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_per`
--
ALTER TABLE `item_per`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `mcategory`
--
ALTER TABLE `mcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_old`
--
ALTER TABLE `orders_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scategory`
--
ALTER TABLE `scategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stockpoint`
--
ALTER TABLE `stockpoint`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stockpointitems`
--
ALTER TABLE `stockpointitems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribe`
--
ALTER TABLE `subscribe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `valumepoints`
--
ALTER TABLE `valumepoints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10006;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `favourite`
--
ALTER TABLE `favourite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gst`
--
ALTER TABLE `gst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `item_images`
--
ALTER TABLE `item_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `item_per`
--
ALTER TABLE `item_per`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mcategory`
--
ALTER TABLE `mcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders_old`
--
ALTER TABLE `orders_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `scategory`
--
ALTER TABLE `scategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `stockpoint`
--
ALTER TABLE `stockpoint`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stockpointitems`
--
ALTER TABLE `stockpointitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subscribe`
--
ALTER TABLE `subscribe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `valumepoints`
--
ALTER TABLE `valumepoints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
