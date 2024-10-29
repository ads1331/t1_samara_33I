-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 18 2024 г., 18:14
-- Версия сервера: 8.0.30
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `i_33`
--

-- --------------------------------------------------------

--
-- Структура таблицы `reports`
--

CREATE TABLE `reports` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `report_type` enum('public','private') COLLATE utf8mb4_general_ci DEFAULT 'private',
  `first_page_image` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `project_name`, `description`, `report_type`, `first_page_image`, `created_at`, `updated_at`) VALUES
(4, 1, '3', '123', 'public', 'public/reports/kVhnR2wWhshWUQGy6OlNs8VIUXedZGOiMFX83VL0.png', '2024-10-17 16:03:31', '2024-10-17 16:03:31');

-- --------------------------------------------------------

--
-- Структура таблицы `report_images`
--

CREATE TABLE `report_images` (
  `id` int NOT NULL,
  `report_id` int DEFAULT NULL,
  `image_path` text COLLATE utf8mb4_general_ci,
  `page_number` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `report_images`
--

INSERT INTO `report_images` (`id`, `report_id`, `image_path`, `page_number`) VALUES
(13, 4, 'public/reports/H6f3RHBmmFCabHLi2cmQZZLD3aSXed36CgB3xq8c.png', 2),
(14, 4, 'public/reports/M1xjabIKPMwL110AwdmxjZcJyNj8TZbfBz4StbrT', 3),
(15, 4, 'public/reports/9mbg8vH1ZiJwtOyoQRp5VCRVfP2IuVvQG2EUlI1L.png', 4),
(16, 4, 'public/reports/UpccRtutmb4QIaTuBnUz0jlmy6YTXExB7ju4m90m.png', 5),
(17, 4, 'public/reports/SWBxUpmf8XrBXgwJ20pqXfklXk1A5Blm3rw0IfQu.png', 6),
(18, 4, 'public/reports/LFBnwlOyOIS24qoFD8qzCMQYkkQxCl5hMEXgbGp1.png', 7),
(19, 4, 'public/reports/6PRbbvLQEBED7s50zCafuZpFCBCDuTTbEtgtv6aE.png', 8),
(20, 4, 'public/reports/xDbo0HXzLJrSeIh7CZdVcfy8cr1kqBXDc7KCQDJe.png', 9),
(21, 4, 'public/reports/yMfX6OPREvmTCdesjbV7NezAgHxeCxTZ1acgdEOr.png', 10),
(22, 4, 'public/reports/4sA3tvCyj3sDi5HtEMZ4wv1JuFB4M9aszMIgOKmk.png', 11);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, '123', 'afanasden21@mail.ru', '$2y$10$trZstmGryfvFDXZew2E3LO5I03K3kVQ4N/l2uziBd1uB81DPjXlFi', '2024-10-17 15:40:18', '2024-10-17 15:40:18');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `report_images`
--
ALTER TABLE `report_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_id` (`report_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `report_images`
--
ALTER TABLE `report_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `report_images`
--
ALTER TABLE `report_images`
  ADD CONSTRAINT `report_images_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
