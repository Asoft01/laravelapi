-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2021 at 11:28 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravelapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(3, '2019_08_19_000000_create_failed_jobs_table', 1);

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `api_token`, `created_at`, `updated_at`) VALUES
(1, 'hamid', 'lekhad19@gmail.com', NULL, '$2y$10$lNp.7kFTb3vAPL4zFZcs3OdrSlazkhW7.wd5RLUiV4xyLPgRkl6Z6', NULL, '', NULL, NULL),
(2, 'john', 'john@gmail.com', NULL, '$2y$10$eBgn9zsgpxtF/P4TsxJ0reORxgRzPElSj4lel.AR7G3ZCPnRjPdmW', NULL, '', NULL, NULL),
(4, 'amit', 'amit@gmail.com', NULL, '$2y$10$n5p5kQh8b4JzOwGB2AkJmOTMwQ/DttR2q045TG7K/qqIdzcRrLLV2', NULL, '', '2021-05-13 19:21:16', '2021-05-13 19:21:16'),
(5, 'wale', 'wale@gmail.com', NULL, '$2y$10$t/7vOR0/eJIbcBN9006GeuURJR52ndoUPfL5PhYngCZd6cng9DJia', NULL, '', '2021-05-13 19:27:02', '2021-05-13 19:27:02'),
(18, 'amit', 'lekhad129@gmail.com', NULL, '$2y$10$4qj4yAXyh9ZyiurT7mV/GOKibqpPuI4vXNVhGGRM.cYsB3sX/Kp1G', NULL, 'zlqkH22Zn7si3bG1DjMlkgTujFm3Q383krwrHPKlW1Uz6ul1hLM4ECUIdn6l', '2021-05-28 03:34:10', '2021-05-30 08:07:52'),
(19, 'wale', 'walex@gmail.com', NULL, '$2y$10$m6PkUPb4zCtMcZqrJ17UG.Ctq03DCBCazho96HfbiFOM4GzlTpK2e', NULL, 'gpDYY7Ezj6OryjdhtOVEp75JYQ37iTlpfRYLR0rwo4vKbBETsTSjpJv1dxmh', '2021-05-28 03:38:02', '2021-05-28 03:38:02'),
(20, 'wale', 'walex1@gmail.com', NULL, '$2y$10$sEKnxSMxbr/dxslUtx.iBubCze9bZT5Db07vDpzpR1Yih8fNQRtqm', NULL, 'wUfuDLXCSnqarhQjueSuK4YSuG1p6hI0cFmbxwbIGWDsseCIxPrfGZwL0vlZ', '2021-05-28 03:39:56', '2021-05-28 03:39:56'),
(21, 'wale', 'walex3@gmail.com', NULL, '$2y$10$ISrNe0c8TFGW1wPWOMwZg.LcB4FDfOUqcqB97Aw0Ndsr3LpGUZct.', NULL, 'JWzuyg57ydLFdoIxNqFtVhjJVCo0LbYSk57tgHrbnIwnuDRGZCt8DqWExoyz', '2021-05-30 07:39:58', '2021-05-30 07:39:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
