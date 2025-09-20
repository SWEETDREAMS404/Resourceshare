-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2025 at 03:53 PM
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
-- Database: `resource_share`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'Lecture Notes'),
(2, 'Assignments'),
(3, 'Books'),
(4, 'Exam Reviewers'),
(5, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`rating_id`, `resource_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(3, 2, 5, 4, '21312', '2025-09-18 14:23:33');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `resource_id`, `user_id`, `reason`, `created_at`) VALUES
(2, 5, 5, 'trying to report it with notif success for the user', '2025-09-11 11:42:41'),
(3, 5, 5, 'trying report with notif', '2025-09-11 11:45:27'),
(4, 5, 5, 'trying again reporting with notif', '2025-09-11 11:52:30');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `resource_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_mime` varchar(100) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`resource_id`, `user_id`, `category_id`, `title`, `description`, `file_path`, `file_mime`, `file_size`, `uploaded_at`) VALUES
(2, 2, 5, 'testing for png', 'png file upload test', 'uploads/1757588211_540899520_1253909113082968_5114867250361380296_n.png', 'image/png', 70251, '2025-09-11 10:56:51'),
(4, 2, 5, 'Test for pdf', 'pdf upload file test', 'uploads/1757588461_USD___DFD.pdf', 'application/pdf', 375720, '2025-09-11 11:01:01'),
(5, 2, 2, 'Docx file assignment', 'Docx Test Upload', 'uploads/1757588525_SYSARCH_Final_Project.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1089370, '2025-09-11 11:02:05'),
(8, 2, 3, 'landing page', 'the system landing page when loging in', 'uploads/1757593714_landing_page.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 94329, '2025-09-11 12:28:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `role`, `created_at`, `profile_pic`) VALUES
(1, 'student', '$2y$10$u1GmXqkq6gY9x6lYy0o7yO4Ov7kJZJjq4zqY7zqkEo4Y6Ta1s9yqG', 'student@example.com', 'user', '2025-09-11 09:23:18', 'default.png'),
(2, 'john', '$2y$10$gSc8lZ/gk5vliMZk.B5/FOtU7xhb9nOec3QlqQcAZf2tA3Cv4VHD.', 'johnjoshuabandola1@gmail.com', 'user', '2025-09-11 09:24:57', 'profile_2.jpg'),
(3, 'joshua', '$2y$10$LsLv9YqL9m.f8cNs0wf/WeLUlrLdTDfYaq9FMrWwbrhrt0p0dskbq', 'johnbandola444@gmail.com', 'user', '2025-09-11 09:25:22', 'profile_3.jpg'),
(5, 'admin', '$2y$10$HyyLTT5.Zxo6fIvSyc6kK.Hfhac.DnX6sOtIaL0jsTiQWaaxAenGO', 'admin@admin.com', 'admin', '2025-09-11 09:46:40', 'profile_5.jpg'),
(11, 'aujsc.siacor', '$2y$10$G6XLBa6repEo/U3MLUAOXuqmR1ZOi9mRd12P./GISkzh69UuRaQ8.', 'siacorjovylene9@gmail.com', 'user', '2025-09-12 05:20:04', 'default.png'),
(12, 'sample', '$2y$10$rMzMv7z2MiuK/Yjl.THOwOEOFeAst2AxXaF8awc2e/EfuTQDIkCRu', 'sampleuser@mail.com', 'user', '2025-09-18 11:01:13', 'profile_12.jpg'),
(13, 'sample1', '$2y$10$GmIYQbCpxvscuQmflDb8aucOUuEiIzZpX80qo06d5Dp6Q1yuXVb/K', 'sampleuser1@mail.com', 'user', '2025-09-18 11:03:36', 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `resource_id` (`resource_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `resource_id` (`resource_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`resource_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resources_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
