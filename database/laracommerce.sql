-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2021 at 04:50 PM
-- Server version: 5.7.24
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laracommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_menus`
--

CREATE TABLE `admin_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_menus`
--

INSERT INTO `admin_menus` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'primary', '2021-02-20 17:26:52', '2021-02-20 17:30:57'),
(2, 'secondary', '2021-02-20 17:34:51', '2021-02-20 17:34:51'),
(3, 'footer-1', '2021-02-20 17:58:30', '2021-02-20 17:58:30'),
(4, 'footer-2', '2021-02-20 18:01:18', '2021-02-20 18:01:18');

-- --------------------------------------------------------

--
-- Table structure for table `admin_menu_items`
--

CREATE TABLE `admin_menu_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu` bigint(20) UNSIGNED NOT NULL,
  `depth` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_menu_items`
--

INSERT INTO `admin_menu_items` (`id`, `label`, `link`, `parent`, `sort`, `class`, `menu`, `depth`, `created_at`, `updated_at`) VALUES
(1, 'About Us', '#', 0, 0, NULL, 1, 0, '2021-02-20 17:27:04', '2021-02-20 17:27:06'),
(2, 'Conatct', '#', 0, 0, NULL, 2, 0, '2021-02-20 17:34:56', '2021-02-20 17:35:32'),
(3, 'Privacy & Policy', '#', 2, 1, NULL, 2, 1, '2021-02-20 17:35:32', '2021-02-20 17:35:35'),
(4, 'Support', '#', 0, 0, NULL, 3, 0, '2021-02-20 17:58:37', '2021-02-20 17:58:38');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `taxonomy_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visibility` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `parent_id`, `taxonomy_type`, `visibility`, `created_at`, `updated_at`) VALUES
(1, 'Homepage Slider', 'homepage-slider', NULL, NULL, NULL, 'slider', 1, '2021-02-19 05:27:04', '2021-02-19 05:27:04'),
(2, 'Slider Right Side Banner', 'slider-right-side-banner', NULL, NULL, NULL, 'slider', 1, '2021-03-06 07:43:51', '2021-03-06 07:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `frontend_settings`
--

CREATE TABLE `frontend_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meta_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `frontend_settings`
--

INSERT INTO `frontend_settings` (`id`, `meta_name`, `meta_value`, `created_at`, `updated_at`) VALUES
(1, 'site_logoimg_id', '3', '2021-02-13 15:25:35', '2021-02-14 08:20:02'),
(2, 'home_slider', '1', '2021-02-13 15:25:35', '2021-02-19 05:35:09'),
(3, 'home_product_category', '[\"1\"]', '2021-02-13 15:25:35', '2021-03-06 09:26:00'),
(4, 'company_phone', '01923', NULL, '2021-03-06 09:17:48'),
(5, 'home_slider_right_side_banner', '2', NULL, '2021-03-06 07:47:02'),
(6, 'footer_content', 'trtrc', NULL, '2021-03-06 09:17:44'),
(7, 'fb_url', 'facebook.com', NULL, '2021-03-06 09:22:34'),
(8, 'twitter_url', 'twitter.com', NULL, '2021-03-06 09:26:00'),
(9, 'instagram_url', 'instagram.com', NULL, '2021-03-06 09:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `medias`
--

CREATE TABLE `medias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_extension` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_directory` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medias`
--

INSERT INTO `medias` (`id`, `user_id`, `original_name`, `filename`, `file_type`, `file_size`, `file_extension`, `file_directory`, `created_at`, `updated_at`) VALUES
(1, 1, 'a', 'product23-1613302048.jpg', 'b', 'c', 'd', 'e', '2021-02-14 05:27:28', '2021-02-14 05:27:28'),
(2, 1, 'a', 'product2-1613302307.jpg', 'b', 'c', 'd', 'e', '2021-02-14 05:31:47', '2021-02-14 05:31:47'),
(3, 1, 'a', 'logo-1613312398.png', 'b', 'c', 'd', 'e', '2021-02-14 08:19:58', '2021-02-14 08:19:58'),
(4, 1, 'a', 'brand1-1613688284.jpg', 'b', 'c', 'd', 'e', '2021-02-18 16:44:44', '2021-02-18 16:44:44'),
(5, 1, 'a', 'brand4-1613688352.jpg', 'b', 'c', 'd', 'e', '2021-02-18 16:45:52', '2021-02-18 16:45:52'),
(6, 1, 'a', 'brand8-1613688446.jpg', 'b', 'c', 'd', 'e', '2021-02-18 16:47:26', '2021-02-18 16:47:26'),
(7, 1, 'a', 'brand2-1613690200.jpg', 'b', 'c', 'd', 'e', '2021-02-18 17:16:40', '2021-02-18 17:16:40'),
(8, 1, 'a', 'ebay-1613732948.png', 'b', 'c', 'd', 'e', '2021-02-19 05:09:08', '2021-02-19 05:09:08'),
(9, 1, 'a', 'slider1-1613734062.jpg', 'b', 'c', 'd', 'e', '2021-02-19 05:27:42', '2021-02-19 05:27:42'),
(10, 1, 'a', 'slider6-1614240606.jpg', 'b', 'c', 'd', 'e', '2021-02-25 02:10:06', '2021-02-25 02:10:06'),
(11, 1, 'a', 'ch-progress3-1615046227.png', 'b', 'c', 'd', 'e', '2021-03-06 09:57:07', '2021-03-06 09:57:07'),
(12, 1, 'a', 'companion-book-physics-1615046329.jpg', 'b', 'c', 'd', 'e', '2021-03-06 09:58:49', '2021-03-06 09:58:49'),
(13, 1, 'a', 'copaninon-book-zoology-1615046342.jpg', 'b', 'c', 'd', 'e', '2021-03-06 09:59:02', '2021-03-06 09:59:02'),
(14, 1, 'a', 'manipulation-1-1615046361.jpg', 'b', 'c', 'd', 'e', '2021-03-06 09:59:21', '2021-03-06 09:59:21'),
(15, 1, 'a', 'download-1615046539.jpg', 'b', 'c', 'd', 'e', '2021-03-06 10:02:19', '2021-03-06 10:02:19'),
(16, 1, 'a', 'nissan-1615046583.png', 'b', 'c', 'd', 'e', '2021-03-06 10:03:03', '2021-03-06 10:03:03');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2021_01_30_145239_create_product_categories_table', 1),
(4, '2021_01_30_145810_create_product_brands_table', 1),
(5, '2021_01_30_150031_create_products_table', 1),
(6, '2021_01_30_151530_create_medias_table', 1),
(7, '2021_02_08_225954_create_terms_table', 1),
(8, '2021_02_08_230957_create_posts_table', 1),
(9, '2021_02_09_195757_create_term_taxonomy_table', 1),
(10, '2021_02_09_203137_create_categories_table', 1),
(11, '2021_02_10_101053_create_frontend_settings', 1),
(12, '2021_02_13_035224_create_roles_table', 1),
(14, '2021_02_13_181826_create_product_carts_table', 1),
(17, '2021_02_15_002231_create_product_order_details_table', 3),
(21, '2021_02_15_004734_create_user_address_books_table', 5),
(23, '2021_02_13_181755_create_product_orders_table', 6),
(24, '2021_02_17_222859_create_product_attributes_table', 7),
(25, '2021_02_17_223045_create_product_attribute_values_table', 7),
(26, '2021_02_18_125706_create_product_coupons_table', 8),
(27, '2021_02_20_024901_create_store_settings_table', 9),
(28, '2017_08_11_073824_create_menus_wp_table', 10),
(29, '2017_08_11_074006_create_menu_items_wp_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `status` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `name`, `email`, `phone`, `amount`, `address`, `status`, `transaction_id`, `currency`) VALUES
(20, 'Customer Name', 'customer@mail.com', '8801XXXXXXXXX', 10, 'Customer Address', 'Pending', '602bbdd293979', 'BDT'),
(21, 'Customer Name', 'customer@mail.com', '8801XXXXXXXXX', 10, 'Customer Address', 'Pending', '602bbdee724c2', 'BDT'),
(22, 'Customer Name', 'customer@mail.com', '8801XXXXXXXXX', 10, 'Customer Address', 'Pending', '602bbea0f3d0e', 'BDT'),
(23, 'Customer Name', 'customer@mail.com', '8801XXXXXXXXX', 10, 'Customer Address', 'Pending', '602bbea506e20', 'BDT'),
(24, 'Customer Name', 'customer@mail.com', '8801XXXXXXXXX', 10, 'Customer Address', 'Pending', '602bbeb38a115', 'BDT'),
(25, 'Customer Name', 'customer@mail.com', '8801XXXXXXXXX', 10, 'Customer Address', 'Pending', '602bbedd6c1b2', 'BDT'),
(26, 'Customer Name', 'customer@mail.com', '8801XXXXXXXXX', 10, 'Customer Address', 'Pending', '602bbee786825', 'BDT'),
(27, 'Customer Name', 'customer@mail.com', '8801XXXXXXXXX', 10, 'Customer Address', 'Pending', '602bbfe90002f', 'BDT'),
(28, 'Customer Name', 'customer@mail.com', '8801XXXXXXXXX', 10, 'Customer Address', 'Pending', '602bc00e9bafd', 'BDT'),
(29, 'Customer Name', 'customer@mail.com', '8801XXXXXXXXX', 10, 'Customer Address', 'Pending', '602bc02fdf8f1', 'BDT'),
(30, 'Customer Name', 'customer@mail.com', '8801XXXXXXXXX', 10, 'Customer Address', 'Pending', '602bd694d8235', 'BDT'),
(31, 'Customer Name', 'customer@mail.com', '8801XXXXXXXXX', 10, 'Customer Address', 'Pending', '602bd6d7c2106', 'BDT');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `term_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'page',
  `category_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `name`, `slug`, `description`, `featured_image`, `term_type`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'About Us', 'about-us', '<p>rtrtrtrtrtr</p>', NULL, 'page', '', '2021-02-16 17:32:41', '2021-02-20 16:08:23'),
(2, 'Get buy', 'get-buy', NULL, '9', 'slider', '1', '2021-02-19 05:27:56', '2021-02-19 05:28:24'),
(3, 'Slider Right Side Banner', 'https://facebook.com', NULL, NULL, 'slider', '2', '2021-03-06 07:45:11', '2021-03-06 07:45:11');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `category_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `specification` longtext COLLATE utf8mb4_unicode_ci,
  `short_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regular_price` int(11) DEFAULT NULL,
  `sale_price` int(11) DEFAULT NULL,
  `purchase_price` int(11) DEFAULT NULL,
  `attribute` json DEFAULT NULL,
  `refundable` int(11) NOT NULL DEFAULT '0',
  `shipping_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cost` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_stock` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_stock` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visibility` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `category_id`, `brand_id`, `title`, `description`, `specification`, `short_description`, `slug`, `code`, `regular_price`, `sale_price`, `purchase_price`, `attribute`, `refundable`, `shipping_type`, `shipping_cost`, `total_stock`, `current_stock`, `product_image`, `featured_image`, `visibility`, `created_at`, `updated_at`) VALUES
(3, 1, '2,1', NULL, 'Samsung Galaxy S20', NULL, NULL, NULL, 'samsung-galaxy-s20', NULL, 35000, 9, NULL, '{\"Size\": [\"M\"], \"Color\": [\"Red\", \"Green\"]}', 0, '0', NULL, NULL, NULL, '[\"2\"]', '2', '1', '2021-02-14 05:31:55', '2021-02-17 22:27:39'),
(7, 1, '1,4', 2, 'Iphone 12', NULL, NULL, NULL, 'iphone-12', NULL, 78000, NULL, NULL, NULL, 0, '0', NULL, NULL, NULL, '[\"1\"]', '1', '1', '2021-02-14 05:28:18', '2021-02-18 07:33:28');

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attributes`
--

INSERT INTO `product_attributes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Color', '2021-02-17 22:43:57', '2021-03-05 13:31:48'),
(2, 'Size', '2021-02-17 22:43:57', '2021-02-17 22:43:57');

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attributes_id` bigint(20) UNSIGNED DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attribute_values`
--

INSERT INTO `product_attribute_values` (`id`, `attributes_id`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 'Red', NULL, '2021-03-05 13:54:31'),
(2, 1, 'Green', NULL, NULL),
(3, 2, 'S', NULL, NULL),
(4, 2, 'M', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_brands`
--

CREATE TABLE `product_brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visibility` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_brands`
--

INSERT INTO `product_brands` (`id`, `name`, `slug`, `image`, `visibility`, `created_at`, `updated_at`) VALUES
(2, 'Apple', 'apple', '4', 1, '2021-02-16 15:49:41', '2021-02-18 16:44:50'),
(3, 'Samsung', 'samsung', '5', 1, '2021-02-18 16:45:55', '2021-02-18 16:45:55'),
(4, 'Vivo', 'vivo', '6', 1, '2021-02-18 16:47:30', '2021-02-18 16:47:30'),
(5, 'Oppo', 'oppo', '7', 1, '2021-02-18 17:16:44', '2021-02-18 17:16:44'),
(6, 'Ebay', 'ebay', '8', 1, '2021-02-19 05:09:16', '2021-02-19 05:09:16');

-- --------------------------------------------------------

--
-- Table structure for table `product_carts`
--

CREATE TABLE `product_carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `visibility` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `description`, `slug`, `image`, `parent_id`, `visibility`, `created_at`, `updated_at`) VALUES
(1, 'Smartphone', NULL, 'smartphone', NULL, 2, 1, '2021-02-14 05:31:06', '2021-02-16 16:08:16'),
(2, 'Electronics', NULL, 'electronics', NULL, NULL, 1, '2021-02-16 16:04:45', '2021-02-16 16:04:54'),
(3, 'Home Appliences', NULL, 'home-appliences', NULL, NULL, 1, '2021-02-16 16:07:59', '2021-02-16 16:07:59'),
(4, 'Apple', NULL, 'apple', NULL, 1, 1, '2021-02-16 16:35:56', '2021-02-16 16:35:56');

-- --------------------------------------------------------

--
-- Table structure for table `product_coupons`
--

CREATE TABLE `product_coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expired_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_coupons`
--

INSERT INTO `product_coupons` (`id`, `code`, `type`, `value`, `amount`, `expired_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'abc', 'fixed', NULL, '30', NULL, '1', NULL, NULL),
(2, 'def', 'fixed', NULL, '50', NULL, '0', NULL, NULL),
(3, 'cdef', 'percentage_off', NULL, '3', NULL, '1', '2021-02-19 18:59:35', '2021-02-19 19:11:54');

-- --------------------------------------------------------

--
-- Table structure for table `product_orders`
--

CREATE TABLE `product_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_thana` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` double NOT NULL,
  `use_coupone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cost` double DEFAULT NULL,
  `coupone_discount` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tran_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `shiping_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_orders`
--

INSERT INTO `product_orders` (`id`, `order_code`, `user_id`, `customer_name`, `customer_phone`, `customer_address`, `customer_thana`, `customer_postal_code`, `customer_city`, `customer_country`, `total_amount`, `use_coupone`, `shipping_cost`, `coupone_discount`, `currency`, `tran_id`, `note`, `payment_status`, `payment_type`, `delivery_status`, `shiping_type`, `created_at`, `updated_at`) VALUES
(1, '#OD-646870614', 2, 'Noushad Nipun', '01823633792', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', 129, '', 120, '', 'BDT', '', NULL, 'Paid', 'Cash On Delivery', 'Pending', NULL, '2021-03-05 12:45:20', '2021-03-05 12:45:20'),
(2, '#OD-646970614', 2, 'Noushad Nipun', '01823633792', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', 120, '', 120, '', 'BDT', '60427c49d4456', NULL, 'Pending', 'Sslcommerz Paymeny Gateway', 'Pending', NULL, '2021-03-05 12:45:29', '2021-03-05 12:45:29'),
(3, '#OD-702060430', 2, 'Noushad Nipun', '01823633792', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', 129, '', 120, '', 'BDT', '60427fd6a752a', NULL, 'Pending', 'Sslcommerz Paymeny Gateway', 'Pending', NULL, '2021-03-05 13:00:38', '2021-03-05 13:00:38'),
(4, '#OD-703140501', 2, 'Noushad Nipun', '01823633792', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', 129, '', 120, '', 'BDT', '6042801a35d4b', NULL, 'Pending', 'Sslcommerz Paymeny Gateway', 'Pending', NULL, '2021-03-05 13:01:46', '2021-03-05 13:01:46'),
(5, '#OD-705200503', 2, 'Noushad Nipun', '01823633792', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', 138, '', 120, '', 'BDT', '604280989a9ee', NULL, 'Pending', 'Sslcommerz Paymeny Gateway', 'Pending', NULL, '2021-03-05 13:03:52', '2021-03-05 13:03:52'),
(6, '#OD-705730504', 2, 'Noushad Nipun', '01823633792', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', 138, '', 120, '', 'BDT', '604280a5b3771', NULL, 'Pending', 'Sslcommerz Paymeny Gateway', 'Pending', NULL, '2021-03-05 13:04:05', '2021-03-05 13:04:05'),
(7, '#OD-712220510', 2, 'Noushad Nipun', '01823633792', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', 138, '', 120, '', 'BDT', '6042823e7a36e', NULL, 'Pending', 'Sslcommerz Paymeny Gateway', 'Pending', NULL, '2021-03-05 13:10:54', '2021-03-05 13:10:54'),
(8, '#OD-712860511', 2, 'Noushad Nipun', '01823633792', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', 138, '', 120, '', 'BDT', '60428256a6c2a', NULL, 'Pending', 'Sslcommerz Paymeny Gateway', 'Pending', NULL, '2021-03-05 13:11:18', '2021-03-05 13:11:18'),
(9, '#OD-713040511', 2, 'Noushad Nipun', '01823633792', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', 138, '', 120, '', 'BDT', '60428268bf875', NULL, 'Pending', 'Sslcommerz Paymeny Gateway', 'Pending', NULL, '2021-03-05 13:11:36', '2021-03-05 13:11:36'),
(10, '#OD-713170511', 2, 'Noushad Nipun', '01823633792', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', 138, '', 120, '', 'BDT', '604282752803f', NULL, 'Pending', 'Sslcommerz Paymeny Gateway', 'Pending', NULL, '2021-03-05 13:11:49', '2021-03-05 13:11:49'),
(11, '#OD-713220511', 2, 'Noushad Nipun', '01823633792', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', 138, '', 120, '', 'BDT', '6042827a20f4f', NULL, 'Pending', 'Sslcommerz Paymeny Gateway', 'Pending', NULL, '2021-03-05 13:11:54', '2021-03-05 13:11:54'),
(12, '#OD-713740512', 2, 'Noushad Nipun', '01823633792', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', 138, '', 120, '', 'BDT', '604282869fa84', NULL, 'Pending', 'Sslcommerz Paymeny Gateway', 'Pending', NULL, '2021-03-05 13:12:06', '2021-03-05 13:12:06');

-- --------------------------------------------------------

--
-- Table structure for table `product_order_details`
--

CREATE TABLE `product_order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `attribute` json DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_order_details`
--

INSERT INTO `product_order_details` (`id`, `user_id`, `order_id`, `product_id`, `attribute`, `qty`, `price`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 3, '{\"Size\": [\"M\"], \"Color\": [\"Red\"]}', 1, 9, '2021-03-05 12:45:20', '2021-03-05 12:45:20'),
(2, 2, 3, 3, '{\"Size\": [\"M\"], \"Color\": [\"Red\"]}', 1, 9, '2021-03-05 13:00:38', '2021-03-05 13:00:38'),
(3, 2, 4, 3, '{\"Size\": [\"M\"], \"Color\": [\"Red\"]}', 1, 9, '2021-03-05 13:01:46', '2021-03-05 13:01:46'),
(4, 2, 5, 3, '{\"Size\": [\"M\"], \"Color\": [\"Red\"]}', 2, 18, '2021-03-05 13:03:52', '2021-03-05 13:03:52'),
(5, 2, 6, 3, '{\"Size\": [\"M\"], \"Color\": [\"Red\"]}', 2, 18, '2021-03-05 13:04:05', '2021-03-05 13:04:05'),
(6, 2, 7, 3, '{\"Size\": [\"M\"], \"Color\": [\"Red\"]}', 2, 18, '2021-03-05 13:10:54', '2021-03-05 13:10:54'),
(7, 2, 8, 3, '{\"Size\": [\"M\"], \"Color\": [\"Red\"]}', 2, 18, '2021-03-05 13:11:18', '2021-03-05 13:11:18'),
(8, 2, 9, 3, '{\"Size\": [\"M\"], \"Color\": [\"Red\"]}', 2, 18, '2021-03-05 13:11:36', '2021-03-05 13:11:36'),
(9, 2, 10, 3, '{\"Size\": [\"M\"], \"Color\": [\"Red\"]}', 2, 18, '2021-03-05 13:11:49', '2021-03-05 13:11:49'),
(10, 2, 11, 3, '{\"Size\": [\"M\"], \"Color\": [\"Red\"]}', 2, 18, '2021-03-05 13:11:54', '2021-03-05 13:11:54'),
(11, 2, 12, 3, '{\"Size\": [\"M\"], \"Color\": [\"Red\"]}', 2, 18, '2021-03-05 13:12:06', '2021-03-05 13:12:06');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'An Administrator', '2021-02-13 15:25:35', '2021-02-13 15:25:35'),
(2, 'Editor', 'editor', 'A Website Manager', '2021-02-13 15:25:35', '2021-02-13 15:25:35'),
(3, 'Customer', 'customer', 'A Customer', '2021-02-13 15:25:35', '2021-02-13 15:25:35');

-- --------------------------------------------------------

--
-- Table structure for table `store_settings`
--

CREATE TABLE `store_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meta_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `store_settings`
--

INSERT INTO `store_settings` (`id`, `meta_name`, `meta_value`, `created_at`, `updated_at`) VALUES
(1, 'shipping_type', 'flat_rate', NULL, '2021-02-19 23:52:54'),
(2, 'shipping_flat_rate', '120', NULL, '2021-02-19 23:30:28'),
(3, 'ssl_store_id', 'icon4602b36900b1e5', NULL, '2021-03-05 13:00:04'),
(4, 'ssl_store_password', 'icon4602b36900b1e5@ssl', NULL, '2021-03-05 13:11:42'),
(5, 'ssl_sandbox_live', 'sandbox', NULL, '2021-03-05 13:00:04');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Page', 'page', '2021-02-13 15:25:35', '2021-02-13 15:25:35'),
(2, 'Slider', 'slider', '2021-02-13 15:25:35', '2021-02-13 15:25:35');

-- --------------------------------------------------------

--
-- Table structure for table `term_taxonomy`
--

CREATE TABLE `term_taxonomy` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `term_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `term_taxonomy`
--

INSERT INTO `term_taxonomy` (`id`, `name`, `slug`, `type`, `term_type`, `created_at`, `updated_at`) VALUES
(1, 'Categories', 'slider', NULL, 'slider', '2021-02-13 15:25:35', '2021-02-13 15:25:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT '3',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `email_verified_at`, `phone`, `avatar`, `adress`, `postcode`, `district`, `ip_address`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Noushad Nipun', 'system@nipun.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$VA.VNsesx0AK4/6kmvSjcue5mW.LVUQWsSRN/nvvvogM1CZM/8ohG', 'hXUQrCleu2q38hvRkMHSckQNa2Ed47LzKx9PbrSBO4X8WYtfPLKlt4eUvegF', '2021-02-13 15:25:35', '2021-02-13 15:25:35'),
(2, 3, 'Riptware Web Technolgy', 'riptware@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$SLq9gliPm6uFkyJWlqSMYOzeMO5dXfAUBTYoJOLx0u3u9qhAJKrL.', 'YcSm8vx6RuhEU0RI3ivwF0TY639fBNoGM1UHF2l124RibhBwfTIjQbCHdHJZ', '2021-02-14 12:49:20', '2021-02-14 20:32:54');

-- --------------------------------------------------------

--
-- Table structure for table `user_address_books`
--

CREATE TABLE `user_address_books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thana` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `set_default` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_address_books`
--

INSERT INTO `user_address_books` (`id`, `user_id`, `name`, `address`, `thana`, `postal_code`, `city`, `country`, `phone`, `set_default`, `created_at`, `updated_at`) VALUES
(1, 2, 'Noushad Nipun', '74, East Tejturi Bazar, Tejgoan', 'Ghatail', '1215', 'Dhaka', 'Bangladesh', '01823633792', 1, '2021-02-17 11:25:06', '2021-02-17 11:51:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_menus`
--
ALTER TABLE `admin_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_menu_items`
--
ALTER TABLE `admin_menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_menu_items_menu_foreign` (`menu`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `frontend_settings`
--
ALTER TABLE `frontend_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `frontend_settings_meta_name_unique` (`meta_name`);

--
-- Indexes for table `medias`
--
ALTER TABLE `medias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`),
  ADD KEY `posts_term_type_foreign` (`term_type`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_attribute_values_attributes_id_foreign` (`attributes_id`);

--
-- Indexes for table `product_brands`
--
ALTER TABLE `product_brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_brands_slug_unique` (`slug`);

--
-- Indexes for table `product_carts`
--
ALTER TABLE `product_carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_carts_user_id_foreign` (`user_id`),
  ADD KEY `product_carts_product_id_foreign` (`product_id`),
  ADD KEY `product_carts_order_id_foreign` (`order_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_categories_slug_unique` (`slug`);

--
-- Indexes for table `product_coupons`
--
ALTER TABLE `product_coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_coupons_code_unique` (`code`);

--
-- Indexes for table `product_orders`
--
ALTER TABLE `product_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_orders_order_code_unique` (`order_code`),
  ADD KEY `product_orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `product_order_details`
--
ALTER TABLE `product_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_order_details_user_id_foreign` (`user_id`),
  ADD KEY `product_order_details_order_id_foreign` (`order_id`),
  ADD KEY `product_order_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_settings`
--
ALTER TABLE `store_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `store_settings_meta_name_unique` (`meta_name`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `terms_slug_unique` (`slug`);

--
-- Indexes for table `term_taxonomy`
--
ALTER TABLE `term_taxonomy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `term_taxonomy_slug_unique` (`slug`),
  ADD KEY `term_taxonomy_term_type_foreign` (`term_type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- Indexes for table `user_address_books`
--
ALTER TABLE `user_address_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_address_books_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_menus`
--
ALTER TABLE `admin_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin_menu_items`
--
ALTER TABLE `admin_menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `frontend_settings`
--
ALTER TABLE `frontend_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `medias`
--
ALTER TABLE `medias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_brands`
--
ALTER TABLE `product_brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_carts`
--
ALTER TABLE `product_carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_coupons`
--
ALTER TABLE `product_coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_orders`
--
ALTER TABLE `product_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_order_details`
--
ALTER TABLE `product_order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `store_settings`
--
ALTER TABLE `store_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `term_taxonomy`
--
ALTER TABLE `term_taxonomy`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_address_books`
--
ALTER TABLE `user_address_books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_menu_items`
--
ALTER TABLE `admin_menu_items`
  ADD CONSTRAINT `admin_menu_items_menu_foreign` FOREIGN KEY (`menu`) REFERENCES `admin_menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_term_type_foreign` FOREIGN KEY (`term_type`) REFERENCES `terms` (`slug`) ON DELETE CASCADE;

--
-- Constraints for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_attributes_id_foreign` FOREIGN KEY (`attributes_id`) REFERENCES `product_attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_carts`
--
ALTER TABLE `product_carts`
  ADD CONSTRAINT `product_carts_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `product_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_orders`
--
ALTER TABLE `product_orders`
  ADD CONSTRAINT `product_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_order_details`
--
ALTER TABLE `product_order_details`
  ADD CONSTRAINT `product_order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `product_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_order_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `term_taxonomy`
--
ALTER TABLE `term_taxonomy`
  ADD CONSTRAINT `term_taxonomy_term_type_foreign` FOREIGN KEY (`term_type`) REFERENCES `terms` (`slug`) ON DELETE CASCADE;

--
-- Constraints for table `user_address_books`
--
ALTER TABLE `user_address_books`
  ADD CONSTRAINT `user_address_books_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
