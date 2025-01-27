-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2024 at 01:30 PM
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
-- Database: `unity_hub`
--
CREATE DATABASE IF NOT EXISTS `unity_hub` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `unity_hub`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `parent_id`) VALUES
(14, 'Services', '2024-08-22 13:51:05', NULL),
(15, 'FAQs', '2024-08-22 13:51:16', NULL),
(16, 'Testimonials', '2024-08-22 13:51:25', NULL),
(19, 'Portfolio', '2024-08-22 13:51:43', NULL),
(20, 'Blog', '2024-08-22 13:51:47', NULL),
(21, 'Resources', '2024-08-22 13:51:53', NULL),
(22, 'Page', '2024-08-22 13:55:04', NULL),
(44, 'FAQ', '2024-08-23 11:07:29', 15);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `slug` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `featured_image` varchar(255) DEFAULT NULL,
  `updated_at` date NOT NULL DEFAULT current_timestamp(),
  `category` varchar(500) DEFAULT NULL,
  `tags` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `author`, `created_at`, `slug`, `meta_description`, `status`, `featured_image`, `updated_at`, `category`, `tags`) VALUES
(21, 'Home', '<p>The home page content</p>', 'rajkumar', '2024-08-23 11:22:40', 'home-1', 'This content is show  means meta is added', 'published', 'uploads/page/Rectangle-2.png', '2024-08-30', '21,22', '4,6'),
(22, 'About us', '<p>new page about us</p>', 'icon', '2024-08-23 11:25:20', 'about-us-1', 'this is meta', 'published', 'uploads/page/Rectangle-2.png', '2024-08-31', '44,21,22', '6,9');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission_name` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission_name`) VALUES
(1, 'create'),
(2, 'edit'),
(3, 'view'),
(4, 'delete');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `excerpt` varchar(2000) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `author` varchar(200) DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `publish_date` date NOT NULL DEFAULT current_timestamp(),
  `slug` varchar(5000) NOT NULL,
  `updated_at` date NOT NULL DEFAULT current_timestamp(),
  `category` varchar(500) DEFAULT NULL,
  `tags` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `excerpt`, `image`, `content`, `author`, `status`, `publish_date`, `slug`, `updated_at`, `category`, `tags`) VALUES
(34, 'this  is the new post', 'this is  short description', 'uploads/post/slides-1.jpg', '<p>the content</p>', 'icon', 'published', '2024-08-22', 'this-is-the-new-post', '2024-08-22', NULL, NULL),
(42, 'how write content2', 'Creating social media posts is a great way to hone your content writing skills', 'uploads/post/slides-2.jpg', '<p>Creating social media posts is a great way to hone your content writing skills. Since posts are typically very short, snappy, and quick, you can easily try out different styles of writing and see what people respond to. It&rsquo;s easy to change direction and adapt if you need to tweak your writing style since social media posts are typically fluid and changeable by nature. You can also practice A/B testing with your social media ads&mdash;try writing two different posts and sending it to similar demographics and see which one performs better..</p>\r\n<p>Before you write social media posts, make sure to check character limits as well as the demographics of customers who use each platform. This will help you word the post for each platform in a way that will attract the most potential customers.</p>\r\n<h2>Content Writing Example #2: Website Content</h2>\r\n<p>Your website is a huge piece of content writing that is often overlooked as content. From writing each page to creating Frequently Asked Questions or a resource center for customers, your website is often the first point of contact for potential customers. Keep in mind that content writing for your website needs to meet customers who are at all different places in their journey to purchasing, so you should have content that engages customers at all levels.</p>\r\n<h2>Content Writing Example #3: Newsletters and Emails</h2>\r\n<p>One thing that sets newsletters and customer emails apart from other types of content writing is the fact that typically your audience for these pieces of content are people who are already customers and familiar or loyal to your brand. Offering unique content that they cannot get other places can help keep their interest and make them pay attention to your emails.</p>\r\n<h2>Content Writing Example #4: Infographics</h2>\r\n<p>While you may not think of an infographic as a piece of content writing, the truth is that infographics are a combination of visual and written. Think about different statistics. Or interesting facts about your company or products, and use those to create infographics to distribute to your social media channels. For a simpler infographics, you can try creating a comparison sheet. A simple chart that compares your products to others can help educate your customers and make the case for your product.</p>\r\n<h2>Content Writing Example #5: Ebooks</h2>\r\n<p>Creating ebooks for your customers is a great way to offer in-depth explorations of your products and services. This is a great educational tool and can help you to convince potential customers to convert to actual customers. Content writing for these pieces can be more technical, but make sure that you keep the jargon and specialized vocabulary to a minimum or explain it so that you bring your audience along with you.</p>\r\n<h2>Content Writing Example #6: White Papers</h2>', 'icon', 'published', '2024-08-21', 'how-write-content2', '2024-08-31', '15,44', '6,9');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'super_admin'),
(2, 'admin'),
(3, 'user'),
(4, 'writer'),
(5, 'subscriber');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 2),
(2, 3),
(3, 3),
(4, 1),
(4, 2),
(4, 3),
(5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_icon` varchar(500) DEFAULT NULL,
  `site_logo` varchar(500) DEFAULT NULL,
  `site_name` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_icon`, `site_logo`, `site_name`) VALUES
(1, 'uploads/site-settings/favicon.png', 'uploads/site-settings/logo.png', 'Unity Hub');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `created_at`) VALUES
(4, 'tag', '2024-08-22 13:52:15'),
(6, 'tag3', '2024-08-23 06:57:27'),
(9, 'tag5', '2024-08-23 11:04:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `email` varchar(500) NOT NULL,
  `name` varchar(500) NOT NULL,
  `token` varchar(500) NOT NULL,
  `profile_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role_id`, `email`, `name`, `token`, `profile_data`) VALUES
(5, 'icon', '$2y$10$iFRaD5Ar7OVy4F8YjOv58ukwf2JEmDmRoa3w4lbrFZIIVaP95DwlO', 1, 'nathan.freeman9401@gmail.com', 'icon', 'e7e63663ec91b244cec88143145c0d84changedpassword', '{\"profileImage\":\"uploads\\/messages-3.jpg\",\"about\":\"\",\"company\":\"icon techsoft pvt ltd.\",\"job\":\"unity hub\",\"country\":\"\",\"address\":\"\",\"phone\":\"\",\"twitter\":\"\",\"facebook\":\"\",\"instagram\":\"\",\"linkedin\":\"\"}'),
(9, 'rajkumar_1', '$2y$10$qEw1lWGvwTPT/IUIltVGXutufkZVqiXBWPCA53Ew.a6qBYezHloFm', 4, 'rajkumar@gmail.com', 'rajkumar', 'db99b77e6265582a162ce14237981ccf', ''),
(23, 'newuser', '$2y$10$1trCrarVqSKCxzOXmZ3TMuJX7zIfSILxRnMLId9QU/WEH9PoesI4W', 3, 'newuser@gmail.com', 'newuser', '8fcbcf8e1935582bc721cd32b16dbd12', NULL),
(24, 'prit', '$2y$10$2uD.MrXBvohVB1VUC4zAwubKP2D4iPoeeI4EGZmCtKuDLBCG582p2', 2, 'print9727@gmail.com', 'prit', '86ec3630d7a2800ddfa626e05f05f5e8', '{\"profileImage\":\"uploads\\/profile-img.jpg\",\"about\":\"\",\"company\":\"\",\"job\":\"\",\"country\":\"\",\"address\":\"\",\"phone\":\"\",\"twitter\":\"\",\"facebook\":\"\",\"instagram\":\"\",\"linkedin\":\"\"}'),
(25, 'parth', '$2y$10$1K/vRVNnLOkzJZFeopbBmuc/QRfG1ICpjlx.3qZQpLcX98GPsX1f2', 5, 'parthbhatt9737@gmail.com', 'parth', '6e0d88f5f9947feb4c3d072824d71aa3changedpassword', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
