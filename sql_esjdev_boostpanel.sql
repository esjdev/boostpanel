-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 16-Abr-2020 às 21:45
-- Versão do servidor: 5.7.23-23
-- versão do PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esjdev55_boostpanel`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `api_providers`
--

CREATE TABLE `api_providers` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `balance` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `currency` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type_parameter` enum('key','api_token') COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `configs`
--

CREATE TABLE `configs` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `configs`
--

INSERT INTO `configs` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_title', 'BoostPanel', '2020-03-30 18:31:24', '2020-04-01 10:49:14'),
(2, 'meta_description', 'BoostPanel is Cheapest and Best SMM Panel. We Provide services like Insatgram, Facebook, Twitter, Youtube and etc. ', '2020-03-30 18:31:24', '2020-04-01 10:49:14'),
(3, 'meta_keywords', 'SMM Panels, SMM Panel, SMM Reseller Panel, Cheapest SMM Panel ', '2020-03-30 18:31:24', '2020-04-01 10:49:14'),
(4, 'website_logo', 'logo.png', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(5, 'website_favicon', 'favicon.ico', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(6, 'javascript_embed_header', '', '2020-03-30 18:31:24', '2020-04-07 16:50:20'),
(7, 'javascript_embed_footer', '', '2020-03-30 18:31:24', '2020-04-07 16:50:20'),
(8, 'facebook_link', '', '2020-03-30 18:31:24', '2020-04-07 16:50:20'),
(9, 'twitter_link', '', '2020-03-30 18:31:24', '2020-04-07 16:50:20'),
(10, 'instagram_link', '', '2020-03-30 18:31:24', '2020-04-07 16:50:20'),
(11, 'youtube_link', '', '2020-03-30 18:31:24', '2020-04-07 16:50:20'),
(12, 'currency_code', 'USD', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(13, 'currency_decimal', '2', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(14, 'currency_symbol', '$', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(15, 'currency_decimal_separator', '.', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(16, 'currency_thousand_separator', ',', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(17, 'auto_currency_converter', 'off', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(18, 'terms_content', 'Lorem Lorem', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(19, 'policy_content', 'Lorem Lorem', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(20, 'registration_page', 'on', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(21, 'google_recaptcha', 'off', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(22, 'recaptcha_public_key', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(23, 'recaptcha_private_key', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(24, 'email', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(25, 'protocol', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(26, 'smtp_encryption', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(27, 'smtp_host', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(28, 'smtp_port', NULL, '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(29, 'smtp_username', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(30, 'smtp_password', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24');

-- --------------------------------------------------------

--
-- Estrutura da tabela `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'link_recover_password_subject', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(2, 'link_recover_password_content', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(3, 'verification_account_subject', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(4, 'verification_account_content', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(5, 'welcome_user_subject', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(6, 'welcome_user_content', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(7, 'new_user_to_admin_subject', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(8, 'new_user_to_admin_content', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(9, 'notification_ticket_reply_subject', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(10, 'notification_ticket_reply_content', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(11, 'payments_notification_subject', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(12, 'payments_notification_content', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24');

-- --------------------------------------------------------

--
-- Estrutura da tabela `lang`
--

CREATE TABLE `lang` (
  `id` int(11) NOT NULL,
  `ids` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lang_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `lang_list`
--

CREATE TABLE `lang_list` (
  `id` int(11) NOT NULL,
  `ids` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `country_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  `created` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `id_error` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `type` enum('result_services','general') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4,
  `desc_disables` text COLLATE utf8_unicode_ci,
  `desc_updates` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `notifications`
--

INSERT INTO `notifications` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'email_verification_new_account', '0', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(2, 'new_user_welcome', '0', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(3, 'new_user_notification', '0', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(4, 'notification_ticket', '0', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(5, 'payment_notification', '0', '2020-03-30 18:31:24', '2020-03-30 18:31:24');

-- --------------------------------------------------------

--
-- Estrutura da tabela `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('manual','api') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'manual',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `service_id` int(11) NOT NULL DEFAULT '0',
  `service_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'default',
  `api_provider_id` int(11) NOT NULL DEFAULT '0',
  `api_service_id` int(11) NOT NULL DEFAULT '0',
  `api_order_id` int(11) NOT NULL DEFAULT '0',
  `order_response_id_sub` int(11) DEFAULT '0',
  `order_response_posts_sub` int(11) DEFAULT '0',
  `min_sub` int(11) DEFAULT '0',
  `max_sub` int(11) DEFAULT '0',
  `posts_sub` int(11) DEFAULT '0',
  `delay_sub` int(11) DEFAULT '0',
  `expiry_sub` text COLLATE utf8_unicode_ci NOT NULL,
  `status_sub` enum('Active','Paused','Completed','Expired','Canceled') COLLATE utf8_unicode_ci DEFAULT 'Active',
  `link` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `usernames` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `username` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `hashtags` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `hashtag` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `media` text COLLATE utf8_unicode_ci,
  `comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `poll_answer_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seo_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `charge` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `status` enum('completed','processing','inprogress','pending','partial','refunded','canceled') COLLATE utf8_unicode_ci DEFAULT 'pending',
  `start_counter` int(11) NOT NULL DEFAULT '0',
  `remains` int(11) NOT NULL DEFAULT '0',
  `is_drip_feed` int(1) DEFAULT '0',
  `runs` int(11) DEFAULT '0',
  `interval` int(2) DEFAULT '0',
  `dripfeed_quantity` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(15) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `payments_integrations`
--

CREATE TABLE `payments_integrations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `payments_integrations`
--

INSERT INTO `payments_integrations` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'pagseguro_environment', 'Sandbox', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(2, 'pagseguro_name', 'PagSeguro', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(3, 'pagseguro_min_payment', '2', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(4, 'pagseguro_max_payment', '0', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(5, 'pagseguro_token', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(6, 'pagseguro_email', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(7, 'pagseguro_status', 'off', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(8, 'mercadopago_environment', 'Sandbox', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(9, 'mercadopago_name', 'MercadoPago', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(10, 'mercadopago_min_payment', '2', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(11, 'mercadopago_max_payment', '0', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(12, 'mercadopago_access_token', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(13, 'mercadopago_status', 'off', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(14, 'paypal_environment', 'Sandbox', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(15, 'paypal_name', 'PayPal', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(16, 'paypal_min_payment', '2', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(17, 'paypal_max_payment', '0', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(18, 'paypal_client_id', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(19, 'paypal_client_secret', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(20, 'paypal_status', 'off', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(21, 'stripe_name', 'Stripe', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(22, 'stripe_min_payment', '2', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(23, 'stripe_max_payment', '0', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(24, 'stripe_secret_key', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(25, 'stripe_publishable_key', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(26, 'stripe_status', 'off', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(27, '2checkout_environment', 'Sandbox', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(28, '2checkout_name', '2Checkout', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(29, '2checkout_min_payment', '2', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(30, '2checkout_max_payment', '0', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(31, '2checkout_publishable_key', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(32, '2checkout_private_key', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(33, '2checkout_seller_id', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(34, '2checkout_status', 'off', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(35, 'coinpayments_environment', 'Sandbox', '2020-03-30 18:31:24', '2020-03-30 23:07:26'),
(36, 'coinpayments_name', 'CoinPayments', '2020-03-30 18:31:24', '2020-03-30 23:07:26'),
(37, 'coinpayments_min_payment', '2.00', '2020-03-30 18:31:24', '2020-03-30 23:07:26'),
(38, 'coinpayments_max_payment', '0.00', '2020-03-30 18:31:24', '2020-03-30 23:07:26'),
(39, 'coinpayments_public_key', 'f9dc9e9c0003aaa7914e7f7c0cfced83fa8f23920220df8c87b6476f559af103', '2020-03-30 18:31:24', '2020-03-30 23:07:26'),
(40, 'coinpayments_private_key', '775d9F1e4a70e1c8dfF7AA28B1656e0bcAA4310c6DaB5bdD80D0957327f27Afc', '2020-03-30 18:31:24', '2020-03-30 23:07:26'),
(41, 'coinpayments_status', 'on', '2020-03-30 18:31:24', '2020-03-30 23:07:33'),
(42, 'skrill_name', 'Skrill', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(43, 'skrill_min_payment', '2', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(44, 'skrill_max_payment', '0', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(45, 'skrill_email', '', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(46, 'skrill_status', 'off', '2020-03-30 18:31:24', '2020-03-30 18:31:24'),
(47, 'manual_status', 'on', '2020-03-30 18:31:24', '2020-03-30 23:03:47'),
(48, 'payumoney_environment', 'Sandbox', '2020-04-08 03:00:00', '2020-04-08 03:00:00'),
(49, 'payumoney_name', 'PayUmoney', '2020-04-08 03:00:00', '2020-04-08 03:00:00'),
(50, 'payumoney_merchant_key', NULL, '2020-04-08 03:00:00', '2020-04-08 03:00:00'),
(51, 'payumoney_merchant_salt', NULL, '2020-04-08 03:00:00', '2020-04-08 03:00:00'),
(52, 'payumoney_min_payment', '2.00', '2020-04-08 03:00:00', '2020-04-08 03:00:00'),
(53, 'payumoney_max_payment', '0.00', '2020-04-08 03:00:00', '2020-04-08 03:00:00'),
(54, 'payumoney_status', 'off', '2020-04-08 03:00:00', '2020-04-08 03:00:00'),
(67, 'paytm_min_payment', '2.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'paytm_merchant_website', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'paytm_merchant_mid', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'paytm_merchant_key', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 'paytm_name', 'PayTM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 'paytm_environment', 'Sandbox', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'paytm_max_payment', '0.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'paytm_status', 'off', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'instamojo_environment', 'Sandbox', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'instamojo_name', 'Instamojo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'instamojo_api_key', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'instamojo_auth_token', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'instamojo_min_payment', '9.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'instamojo_max_payment', '0.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 'instamojo_status', 'off', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 'mollie_name', 'Mollie', '2020-04-14 11:56:00', '2020-04-14 11:56:00'),
(78, 'mollie_api_key', '', '2020-04-14 11:56:00', '2020-04-14 11:56:00'),
(79, 'mollie_min_payment', '2.00', '2020-04-14 11:56:00', '2020-04-14 11:56:00'),
(80, 'mollie_max_payment', '0.00', '2020-04-14 11:56:00', '2020-04-14 11:56:00'),
(81, 'mollie_status', 'off', '2020-04-14 11:56:00', '2020-04-14 11:56:00'),
(82, 'razorpay_name', 'RazorPay', '2020-04-14 11:56:00', '2020-04-14 11:56:00'),
(83, 'razorpay_key_id', 'rzp_test_4S7ngbGTTlvQBd', '2020-04-14 11:56:00', '2020-04-14 11:56:00'),
(84, 'razorpay_key_secret', 'U2MtPjxT0aVoUeLo6M0Ez3NR', '2020-04-14 11:56:00', '2020-04-14 11:56:00'),
(85, 'razorpay_min_payment', '2.00', '2020-04-14 11:56:00', '2020-04-14 11:56:00'),
(86, 'razorpay_max_payment', '0.00', '2020-04-14 11:56:00', '2020-04-14 11:56:00'),
(87, 'razorpay_status', 'on', '2020-04-14 11:56:00', '2020-04-14 11:56:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `description` text CHARACTER SET utf8mb4,
  `price` decimal(11,2) NOT NULL DEFAULT '0.00',
  `min` int(50) DEFAULT NULL,
  `max` int(50) DEFAULT NULL,
  `add_type` enum('manual','api') COLLATE utf8_unicode_ci DEFAULT 'manual',
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT 'default',
  `api_service_id` int(11) DEFAULT NULL,
  `api_provider_id` int(11) DEFAULT NULL,
  `dripfeed` int(1) DEFAULT '0',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('pending','answered','closed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `pay_or_order_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ticket_messages`
--

CREATE TABLE `ticket_messages` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `transaction_logs`
--

CREATE TABLE `transaction_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `status` enum('paid','processing','pending','refunded','canceled','in_dispute') COLLATE utf8_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` text COLLATE utf8_unicode_ci,
  `balance` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `role` enum('ADMIN','SUPPORT','USER','BANNED') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USER',
  `api_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activation_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `custom_rate` int(11) NOT NULL DEFAULT '0',
  `status` enum('Inactive','Active') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Inactive',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_providers`
--
ALTER TABLE `api_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lang`
--
ALTER TABLE `lang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lang_list`
--
ALTER TABLE `lang_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments_integrations`
--
ALTER TABLE `payments_integrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_providers`
--
ALTER TABLE `api_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `configs`
--
ALTER TABLE `configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `lang`
--
ALTER TABLE `lang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2559;

--
-- AUTO_INCREMENT for table `lang_list`
--
ALTER TABLE `lang_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments_integrations`
--
ALTER TABLE `payments_integrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=790;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
