-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 28 2021 г., 09:11
-- Версия сервера: 10.2.31-MariaDB
-- Версия PHP: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `parcelhub`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `scan_plan_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comma_separated_emails` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `category_audit`
--

CREATE TABLE `category_audit` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discriminator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_hash` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diffs` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user_fqdn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user_firewall` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `house_number` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apartment_number` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `location`
--

INSERT INTO `location` (`id`, `name`, `street`, `house_number`, `apartment_number`, `postal_code`, `city`, `created`, `updated`) VALUES
(1, 'test', 'test', '12344', '124', '123', 'test', '2021-07-27 11:15:20', '2021-07-27 11:15:20');

-- --------------------------------------------------------

--
-- Структура таблицы `location_audit`
--

CREATE TABLE `location_audit` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discriminator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_hash` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diffs` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user_fqdn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user_firewall` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `location_audit`
--

INSERT INTO `location_audit` (`id`, `type`, `object_id`, `discriminator`, `transaction_hash`, `diffs`, `blame_id`, `blame_user`, `blame_user_fqdn`, `blame_user_firewall`, `ip`, `created_at`) VALUES
(1, 'insert', '1', NULL, '66a389a6d222c3d166c063601455619a815dcf22', '{\"name\":{\"new\":\"test\",\"old\":null},\"street\":{\"new\":\"test\",\"old\":null},\"houseNumber\":{\"new\":\"12344\",\"old\":null},\"apartmentNumber\":{\"new\":\"124\",\"old\":null},\"postalCode\":{\"new\":\"123\",\"old\":null},\"city\":{\"new\":\"test\",\"old\":null},\"created\":{\"new\":\"2021-07-27 11:15:20\",\"old\":null},\"updated\":{\"new\":\"2021-07-27 11:15:20\",\"old\":null}}', '1', 'p.burkovskiy@gmail.com', 'DH\\Auditor\\User\\User', 'main', '127.0.0.1', '2021-07-27 08:15:20');

-- --------------------------------------------------------

--
-- Структура таблицы `parcel`
--

CREATE TABLE `parcel` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `category_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `downloaded_by_user_id` int(11) DEFAULT NULL,
  `created_by_user_id` int(11) DEFAULT NULL,
  `modified_by_user_id` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode_number` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `scan_ordered` datetime DEFAULT NULL,
  `scan_inserted` datetime DEFAULT NULL,
  `scan_due` double NOT NULL DEFAULT 0,
  `weight` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `parcel_audit`
--

CREATE TABLE `parcel_audit` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discriminator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_hash` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diffs` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user_fqdn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user_firewall` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `parcel_status`
--

CREATE TABLE `parcel_status` (
  `id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `parcel_status_audit`
--

CREATE TABLE `parcel_status_audit` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discriminator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_hash` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diffs` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user_fqdn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user_firewall` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `scan_plan`
--

CREATE TABLE `scan_plan` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scan` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `scan_plan`
--

INSERT INTO `scan_plan` (`id`, `name`, `scan`) VALUES
(1, 'test', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `scan_plan_audit`
--

CREATE TABLE `scan_plan_audit` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discriminator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_hash` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diffs` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user_fqdn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user_firewall` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `scan_plan_audit`
--

INSERT INTO `scan_plan_audit` (`id`, `type`, `object_id`, `discriminator`, `transaction_hash`, `diffs`, `blame_id`, `blame_user`, `blame_user_fqdn`, `blame_user_firewall`, `ip`, `created_at`) VALUES
(1, 'insert', '1', NULL, '45e902bd7c179f8d19d97a72e8d6f65d4d55b57c', '{\"name\":{\"new\":\"test\",\"old\":null},\"scan\":{\"new\":false,\"old\":null}}', '1', 'p.burkovskiy@gmail.com', 'DH\\Auditor\\User\\User', 'main', '127.0.0.1', '2021-07-27 08:42:16');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `first_name` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_email_token` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 0,
  `roles` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:json)',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `last_pass_reset_request` datetime DEFAULT NULL,
  `pass_reset_token` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address_during_registration` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `location_id`, `first_name`, `last_name`, `password`, `email`, `new_email`, `new_email_token`, `enabled`, `roles`, `created`, `updated`, `last_pass_reset_request`, `pass_reset_token`, `ip_address_during_registration`) VALUES
(1, NULL, 'test', 'test', '$argon2id$v=19$m=65536,t=4,p=1$EpnkAgEWfxsU4kn1mXJFFQ$4yLOB+UphXVSv7dIeluYnDN8Yy9l+s75BMlqJ+bNAQw', 'p.b@gmail.com', NULL, NULL, 1, '[\"ROLE_ADMIN\",\"ROLE_USER\",\"ROLE_LOCATION_MODERATOR\",\"ROLE_LOCATION_ADMIN\"]', '2021-07-27 10:24:55', '2021-07-27 11:48:28', NULL, NULL, NULL),
(2, NULL, 'arr', 'arr', '$2y$13$5X01QgcS/7.U9aTske25puHyNIxuoMBknBQpdypcBJfmqpevkZm.6', 'arr', NULL, NULL, 0, '[\"ROLE_ADMIN\"]', '2021-07-28 08:50:52', '2021-07-28 08:50:52', NULL, NULL, NULL),
(3, NULL, 'arrs', 'arrs', '$2y$13$Up4Wy6pALsrPX3r.5FGrjubNlQVfp/jBEe2/Rqvr/10P01y/otuRW', 'arrs', NULL, NULL, 0, '[\"ROLE_ADMIN\"]', '2021-07-28 08:51:39', '2021-07-28 08:51:39', NULL, NULL, NULL),
(4, NULL, NULL, NULL, '$2y$13$W7Tc8xV6k2QAU2/hb1WhLepbGtnkz4XkuoIH7GZLXE/Wb/dK4WNqy', 'arr@gmail.com', NULL, NULL, 0, '[\"ROLE_ADMIN\"]', '2021-07-28 08:57:36', '2021-07-28 08:57:36', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_audit`
--

CREATE TABLE `user_audit` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discriminator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_hash` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diffs` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user_fqdn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blame_user_firewall` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_audit`
--

INSERT INTO `user_audit` (`id`, `type`, `object_id`, `discriminator`, `transaction_hash`, `diffs`, `blame_id`, `blame_user`, `blame_user_fqdn`, `blame_user_firewall`, `ip`, `created_at`) VALUES
(1, 'update', '1', NULL, 'eac053efd268842a2fac8232bc6136fe4a2151cb', '{\"password\":{\"new\":\"$argon2id$v=19$m=65536,t=4,p=1$EpnkAgEWfxsU4kn1mXJFFQ$4yLOB+UphXVSv7dIeluYnDN8Yy9l+s75BMlqJ+bNAQw\",\"old\":null},\"updated\":{\"new\":\"2021-07-27 11:09:53\",\"old\":\"2021-07-27 10:24:55\"}}', NULL, NULL, NULL, 'main', '127.0.0.1', '2021-07-27 08:09:53'),
(2, 'update', '1', NULL, '63d7980c6e8b58101859332e6dc33040bedb7dc6', '{\"roles\":{\"new\":\"[\\\"ROLE_ADMIN\\\",\\\"ROLE_USER\\\",\\\"ROLE_LOCATION_MODERATOR\\\",\\\"ROLE_LOCATION_ADMIN\\\"]\",\"old\":\"[\\\"ROLE_ADMIN\\\"]\"},\"updated\":{\"new\":\"2021-07-27 11:48:28\",\"old\":\"2021-07-27 11:09:53\"}}', '1', 'p.burkovskiy@gmail.com', 'DH\\Auditor\\User\\User', 'main', '127.0.0.1', '2021-07-27 08:48:28'),
(3, 'insert', '2', NULL, 'e62ffa05ae321297e093cc1aead2f816dea378cd', '{\"firstName\":{\"new\":\"arr\",\"old\":null},\"lastName\":{\"new\":\"arr\",\"old\":null},\"password\":{\"new\":\"$2y$13$5X01QgcS\\/7.U9aTske25puHyNIxuoMBknBQpdypcBJfmqpevkZm.6\",\"old\":null},\"email\":{\"new\":\"arr\",\"old\":null},\"enabled\":{\"new\":false,\"old\":null},\"roles\":{\"new\":\"[\\\"ROLE_ADMIN\\\"]\",\"old\":null},\"created\":{\"new\":\"2021-07-28 08:50:52\",\"old\":null},\"updated\":{\"new\":\"2021-07-28 08:50:52\",\"old\":null}}', NULL, NULL, NULL, NULL, NULL, '2021-07-28 05:50:52'),
(4, 'insert', '3', NULL, '2af96ba5de655d22ff5912e9a1ae1c3485423bee', '{\"firstName\":{\"new\":\"arrs\",\"old\":null},\"lastName\":{\"new\":\"arrs\",\"old\":null},\"password\":{\"new\":\"$2y$13$Up4Wy6pALsrPX3r.5FGrjubNlQVfp\\/jBEe2\\/Rqvr\\/10P01y\\/otuRW\",\"old\":null},\"email\":{\"new\":\"arrs\",\"old\":null},\"enabled\":{\"new\":false,\"old\":null},\"roles\":{\"new\":\"[\\\"ROLE_ADMIN\\\"]\",\"old\":null},\"created\":{\"new\":\"2021-07-28 08:51:39\",\"old\":null},\"updated\":{\"new\":\"2021-07-28 08:51:39\",\"old\":null}}', NULL, NULL, NULL, NULL, NULL, '2021-07-28 05:51:39'),
(5, 'insert', '4', NULL, '2884fe0c337692f4569bc30b4dfc360b3e981190', '{\"password\":{\"new\":\"$2y$13$W7Tc8xV6k2QAU2\\/hb1WhLepbGtnkz4XkuoIH7GZLXE\\/Wb\\/dK4WNqy\",\"old\":null},\"email\":{\"new\":\"arr@gmail.com\",\"old\":null},\"enabled\":{\"new\":false,\"old\":null},\"roles\":{\"new\":\"[\\\"ROLE_ADMIN\\\"]\",\"old\":null},\"created\":{\"new\":\"2021-07-28 08:57:36\",\"old\":null},\"updated\":{\"new\":\"2021-07-28 08:57:36\",\"old\":null}}', NULL, NULL, NULL, NULL, NULL, '2021-07-28 05:57:36');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_index` (`created`),
  ADD KEY `IDX_64C19C1B2406CA5` (`scan_plan_id`),
  ADD KEY `IDX_64C19C164D218E` (`location_id`),
  ADD KEY `IDX_64C19C17E3C61F9` (`owner_id`);

--
-- Индексы таблицы `category_audit`
--
ALTER TABLE `category_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_9d60be1ae31861a527fd590d589be976_idx` (`type`),
  ADD KEY `object_id_9d60be1ae31861a527fd590d589be976_idx` (`object_id`),
  ADD KEY `discriminator_9d60be1ae31861a527fd590d589be976_idx` (`discriminator`),
  ADD KEY `transaction_hash_9d60be1ae31861a527fd590d589be976_idx` (`transaction_hash`),
  ADD KEY `blame_id_9d60be1ae31861a527fd590d589be976_idx` (`blame_id`),
  ADD KEY `created_at_9d60be1ae31861a527fd590d589be976_idx` (`created_at`);

--
-- Индексы таблицы `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `location_audit`
--
ALTER TABLE `location_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_fc44ca3cdb453a48d55c90118c477a41_idx` (`type`),
  ADD KEY `object_id_fc44ca3cdb453a48d55c90118c477a41_idx` (`object_id`),
  ADD KEY `discriminator_fc44ca3cdb453a48d55c90118c477a41_idx` (`discriminator`),
  ADD KEY `transaction_hash_fc44ca3cdb453a48d55c90118c477a41_idx` (`transaction_hash`),
  ADD KEY `blame_id_fc44ca3cdb453a48d55c90118c477a41_idx` (`blame_id`),
  ADD KEY `created_at_fc44ca3cdb453a48d55c90118c477a41_idx` (`created_at`);

--
-- Индексы таблицы `parcel`
--
ALTER TABLE `parcel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_barcode_number` (`barcode_number`),
  ADD KEY `downloaded_by_user_index` (`downloaded_by_user_id`),
  ADD KEY `created_index` (`created`),
  ADD KEY `seen_in_category_index` (`category_id`,`seen`),
  ADD KEY `IDX_C99B5D6012469DE2` (`category_id`),
  ADD KEY `IDX_C99B5D606BF700BD` (`status_id`),
  ADD KEY `IDX_C99B5D607D182D95` (`created_by_user_id`),
  ADD KEY `IDX_C99B5D60DD5BE62E` (`modified_by_user_id`);

--
-- Индексы таблицы `parcel_audit`
--
ALTER TABLE `parcel_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_266490a05665094f7fef6760723f18e7_idx` (`type`),
  ADD KEY `object_id_266490a05665094f7fef6760723f18e7_idx` (`object_id`),
  ADD KEY `discriminator_266490a05665094f7fef6760723f18e7_idx` (`discriminator`),
  ADD KEY `transaction_hash_266490a05665094f7fef6760723f18e7_idx` (`transaction_hash`),
  ADD KEY `blame_id_266490a05665094f7fef6760723f18e7_idx` (`blame_id`),
  ADD KEY `created_at_266490a05665094f7fef6760723f18e7_idx` (`created_at`);

--
-- Индексы таблицы `parcel_status`
--
ALTER TABLE `parcel_status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `parcel_status_audit`
--
ALTER TABLE `parcel_status_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_a79a90f7463a0de185f6db97d5fad072_idx` (`type`),
  ADD KEY `object_id_a79a90f7463a0de185f6db97d5fad072_idx` (`object_id`),
  ADD KEY `discriminator_a79a90f7463a0de185f6db97d5fad072_idx` (`discriminator`),
  ADD KEY `transaction_hash_a79a90f7463a0de185f6db97d5fad072_idx` (`transaction_hash`),
  ADD KEY `blame_id_a79a90f7463a0de185f6db97d5fad072_idx` (`blame_id`),
  ADD KEY `created_at_a79a90f7463a0de185f6db97d5fad072_idx` (`created_at`);

--
-- Индексы таблицы `scan_plan`
--
ALTER TABLE `scan_plan`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `scan_plan_audit`
--
ALTER TABLE `scan_plan_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_d1921b449581e32801dfe4c829c06f03_idx` (`type`),
  ADD KEY `object_id_d1921b449581e32801dfe4c829c06f03_idx` (`object_id`),
  ADD KEY `discriminator_d1921b449581e32801dfe4c829c06f03_idx` (`discriminator`),
  ADD KEY `transaction_hash_d1921b449581e32801dfe4c829c06f03_idx` (`transaction_hash`),
  ADD KEY `blame_id_d1921b449581e32801dfe4c829c06f03_idx` (`blame_id`),
  ADD KEY `created_at_d1921b449581e32801dfe4c829c06f03_idx` (`created_at`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD KEY `IDX_8D93D64964D218E` (`location_id`),
  ADD KEY `created_index` (`created`);

--
-- Индексы таблицы `user_audit`
--
ALTER TABLE `user_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_e06395edc291d0719bee26fd39a32e8a_idx` (`type`),
  ADD KEY `object_id_e06395edc291d0719bee26fd39a32e8a_idx` (`object_id`),
  ADD KEY `discriminator_e06395edc291d0719bee26fd39a32e8a_idx` (`discriminator`),
  ADD KEY `transaction_hash_e06395edc291d0719bee26fd39a32e8a_idx` (`transaction_hash`),
  ADD KEY `blame_id_e06395edc291d0719bee26fd39a32e8a_idx` (`blame_id`),
  ADD KEY `created_at_e06395edc291d0719bee26fd39a32e8a_idx` (`created_at`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `category_audit`
--
ALTER TABLE `category_audit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `location_audit`
--
ALTER TABLE `location_audit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `parcel_audit`
--
ALTER TABLE `parcel_audit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `parcel_status`
--
ALTER TABLE `parcel_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `parcel_status_audit`
--
ALTER TABLE `parcel_status_audit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `scan_plan`
--
ALTER TABLE `scan_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `scan_plan_audit`
--
ALTER TABLE `scan_plan_audit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `user_audit`
--
ALTER TABLE `user_audit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_C1EE637C64D218E` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_C1EE637C7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_C1EE637CB2406CA5` FOREIGN KEY (`scan_plan_id`) REFERENCES `scan_plan` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `parcel`
--
ALTER TABLE `parcel`
  ADD CONSTRAINT `FK_8E02EE0A12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8E02EE0A6BF700BD` FOREIGN KEY (`status_id`) REFERENCES `parcel_status` (`id`),
  ADD CONSTRAINT `FK_8E02EE0A7C63C9C0` FOREIGN KEY (`downloaded_by_user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_8E02EE0A7D182D95` FOREIGN KEY (`created_by_user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_8E02EE0ADD5BE62E` FOREIGN KEY (`modified_by_user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64964D218E` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
