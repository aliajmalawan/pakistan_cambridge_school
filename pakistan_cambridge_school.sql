-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2026 at 06:34 AM
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
-- Database: `pakistan_cambridge_school`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_calendar`
--

CREATE TABLE `academic_calendar` (
  `id` int(10) UNSIGNED NOT NULL,
  `term` varchar(120) NOT NULL,
  `period` varchar(80) NOT NULL DEFAULT '',
  `detail` varchar(400) NOT NULL DEFAULT '',
  `milestone` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_calendar`
--

INSERT INTO `academic_calendar` (`id`, `term`, `period`, `detail`, `milestone`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'First Term', 'April – August', 'Core teaching term, building foundations for the year ahead. — placeholder date, replace via Admin → Academic Calendar.', 0, 1, 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32'),
(2, 'First Term Examinations', 'Mid September', 'Written examinations covering the first term syllabus. — placeholder date, replace via Admin → Academic Calendar.', 1, 2, 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32'),
(3, 'Second Term', 'September – December', 'Continued instruction with periodic class tests. — placeholder date, replace via Admin → Academic Calendar.', 0, 3, 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32'),
(4, 'Winter Break', 'Late December – Early January', 'Campus closed; classes resume in the new year. — placeholder date, replace via Admin → Academic Calendar.', 0, 4, 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32'),
(5, 'Third Term', 'January – March', 'Final term of the session, revision-focused. — placeholder date, replace via Admin → Academic Calendar.', 0, 5, 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32'),
(6, 'Annual Examinations', 'March', 'Final examinations for promotion to the next class. — placeholder date, replace via Admin → Academic Calendar.', 1, 6, 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32');

-- --------------------------------------------------------

--
-- Table structure for table `academic_framework`
--

CREATE TABLE `academic_framework` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(120) NOT NULL,
  `body` varchar(600) NOT NULL DEFAULT '',
  `icon` varchar(40) NOT NULL DEFAULT 'book',
  `accent` varchar(24) NOT NULL DEFAULT 'accent-blue',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_framework`
--

INSERT INTO `academic_framework` (`id`, `title`, `body`, `icon`, `accent`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(4, 'Discipline & Character', 'Academic standards are matched with clear expectations for conduct, punctuality and respect.', 'shield', 'accent-orange', 4, 'published', '2026-08-25 09:21:07', '2026-08-25 09:21:07'),
(5, 'Individual Attention', 'Manageable class sizes mean teachers know their students well enough to know where each one needs support.', 'message', 'accent-rose', 5, 'published', '2026-08-25 09:21:07', '2026-08-25 09:21:07'),
(6, 'Recognition & Growth', 'Consistent effort and improvement are recognised, not only top results.', 'award', 'accent-amber', 6, 'published', '2026-08-25 09:21:07', '2026-08-25 09:21:07');

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `actor` varchar(120) NOT NULL,
  `action` varchar(40) NOT NULL,
  `subject_type` varchar(40) NOT NULL,
  `subject_label` varchar(220) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `actor`, `action`, `subject_type`, `subject_label`, `created_at`) VALUES
(1, 1, 'Site Administrator', 'login', 'auth', 'Signed in to the admin panel', '2026-08-21 10:29:12'),
(2, 1, 'Site Administrator', 'updated', 'settings', 'Site settings and logo', '2026-08-21 10:29:58'),
(3, 1, 'Site Administrator', 'updated', 'settings', 'Site settings and logo', '2026-08-21 10:48:02'),
(4, 1, 'Site Administrator', 'login', 'auth', 'Signed in to the admin panel', '2026-08-21 11:29:31'),
(5, 1, 'Site Administrator', 'login', 'auth', 'Signed in to the admin panel', '2026-08-24 08:40:31'),
(6, 1, 'Site Administrator', 'login', 'auth', 'Signed in to the admin panel', '2026-08-24 08:41:32'),
(7, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 09:05:32'),
(8, 1, 'Site Administrator', 'created', 'menu', 'About Us', '2026-08-24 09:06:48'),
(9, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 09:07:01'),
(10, 1, 'Site Administrator', 'deleted', 'menu', 'Contact', '2026-08-24 09:08:25'),
(11, 1, 'Site Administrator', 'deleted', 'menu', 'Facilities', '2026-08-24 09:08:30'),
(12, 1, 'Site Administrator', 'deleted', 'menu', 'Campus Life', '2026-08-24 09:08:35'),
(13, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 09:08:48'),
(14, 1, 'Site Administrator', 'created', 'menu', 'Academics', '2026-08-24 09:09:14'),
(15, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 09:09:23'),
(16, 1, 'Site Administrator', 'updated', 'menu', 'Academic Overview', '2026-08-24 09:09:55'),
(17, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 09:09:59'),
(18, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 09:10:20'),
(19, 1, 'Site Administrator', 'updated', 'menu', 'Apply Online', '2026-08-24 09:11:11'),
(20, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 09:11:14'),
(21, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 09:12:00'),
(22, 1, 'Site Administrator', 'created', 'menu', 'Campus Life', '2026-08-24 09:16:26'),
(23, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 09:16:30'),
(24, 1, 'Site Administrator', 'created', 'menu', 'Gallery', '2026-08-24 09:37:18'),
(25, 1, 'Site Administrator', 'created', 'menu', 'News & Notices', '2026-08-24 09:37:24'),
(26, 1, 'Site Administrator', 'created', 'menu', 'Contact', '2026-08-24 09:38:06'),
(27, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 09:38:09'),
(28, 1, 'Site Administrator', 'created', 'menu', 'Facilities', '2026-08-24 09:39:02'),
(29, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 09:39:18'),
(30, 1, 'Site Administrator', 'created', 'menu', 'Admissions', '2026-08-24 09:45:16'),
(31, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 09:45:49'),
(32, 1, 'Site Administrator', 'uploaded', 'gallery', '12 photos → Study Tour', '2026-08-24 10:21:01'),
(33, 1, 'Site Administrator', 'uploaded', 'gallery', '12 photos → Study Tour', '2026-08-24 10:21:21'),
(34, 1, 'Site Administrator', 'login', 'auth', 'Signed in to the admin panel', '2026-08-24 10:23:26'),
(35, 1, 'Site Administrator', 'uploaded', 'gallery', '12 photos → Study Tour', '2026-08-24 10:24:54'),
(36, 1, 'Site Administrator', 'uploaded', 'gallery', '3 photos → Study Tour', '2026-08-24 10:26:27'),
(37, 1, 'Site Administrator', 'uploaded', 'gallery', '9 photos → Teacher\'s Day', '2026-08-24 10:27:42'),
(38, 1, 'Site Administrator', 'uploaded', 'gallery', '8 photos → Sports', '2026-08-24 10:31:29'),
(39, 1, 'Site Administrator', 'uploaded', 'gallery', '8 photos → Sports', '2026-08-24 10:32:35'),
(40, 1, 'Site Administrator', 'uploaded', 'gallery', '5 photos → Events & Ceremonies', '2026-08-24 10:34:30'),
(41, 1, 'Site Administrator', 'uploaded', 'gallery', '3 photos → Annual Prize Distribution', '2026-08-24 10:39:51'),
(42, 1, 'Site Administrator', 'uploaded', 'gallery', '9 photos → Cultural Day', '2026-08-24 10:41:44'),
(43, 1, 'Site Administrator', 'uploaded', 'gallery', '4 photos → National Days', '2026-08-24 10:42:30'),
(44, 1, 'Site Administrator', 'updated', 'menu', 'Rearranged menu structure', '2026-08-24 10:43:14'),
(45, 1, 'Site Administrator', 'updated', 'settings', 'Site settings', '2026-08-25 05:48:29'),
(46, 1, 'Site Administrator', 'updated', 'settings', 'Site settings', '2026-08-25 05:53:21'),
(47, 1, 'Site Administrator', 'updated', 'settings', 'Site settings', '2026-08-25 05:56:38'),
(48, 1, 'Site Administrator', 'updated', 'settings', 'Site settings', '2026-08-25 06:02:50'),
(49, 1, 'Site Administrator', 'updated', 'settings', 'Site settings', '2026-08-25 06:04:03'),
(50, 1, 'Site Administrator', 'updated', 'settings', 'Site settings', '2026-08-25 06:07:36'),
(51, 1, 'Site Administrator', 'updated', 'settings', 'Site settings', '2026-08-25 06:11:03'),
(52, 1, 'Site Administrator', 'updated', 'settings', 'Site settings', '2026-08-25 06:12:28'),
(53, 1, 'Site Administrator', 'login', 'auth', 'Signed in to the admin panel', '2026-08-25 07:24:00'),
(54, 1, 'Site Administrator', 'login', 'auth', 'Signed in to the admin panel', '2026-08-25 08:11:45'),
(55, 1, 'Site Administrator', 'updated', 'settings', 'Site settings', '2026-08-25 11:09:19'),
(56, NULL, 'Ayesha Khan', 'submitted', 'admission', 'PCS-26-0001 — Ayesha Khan', '2026-08-26 07:56:10'),
(57, 1, 'Site Administrator', 'login', 'auth', 'Signed in to the admin panel', '2026-08-26 08:08:00'),
(58, 1, 'Ali Ajmal Awan', 'submitted', 'admission', 'PCS-26-0001 — Ali Ajmal Awan', '2026-08-26 08:41:30'),
(59, NULL, 'Bug Test One', 'submitted', 'admission', 'PCS-26-0003 — Bug Test One', '2026-08-26 11:51:12'),
(60, NULL, 'Bug Test Two', 'submitted', 'admission', 'PCS-26-0004 — Bug Test Two', '2026-08-26 11:51:13'),
(61, NULL, 'Bug Test Three', 'submitted', 'admission', 'PCS-26-0005 — Bug Test Three', '2026-08-26 11:58:01'),
(62, NULL, 'Test Parent', 'submitted', 'message', 'Admission Query — Test Parent', '2026-08-27 03:54:23'),
(63, NULL, 'Second Test', 'submitted', 'message', 'General Enquiry — Second Test', '2026-08-27 03:59:39'),
(64, 1, 'Ali Ajmal Awan', 'submitted', 'message', 'Test — Ali Ajmal Awan', '2026-08-27 04:08:33'),
(65, 1, 'Site Administrator', 'login', 'auth', 'Signed in to the admin panel', '2026-08-27 06:11:48'),
(66, 1, 'Site Administrator', 'updated', 'settings', 'Site settings', '2026-08-27 06:12:05'),
(67, 1, 'Site Administrator', 'updated', 'settings', 'Site settings', '2026-08-27 06:24:13'),
(68, 1, 'Site Administrator', 'updated', 'settings', 'Site settings', '2026-08-27 08:00:08');

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `app_no` varchar(20) NOT NULL,
  `student_name` varchar(120) NOT NULL,
  `father_name` varchar(120) NOT NULL,
  `bform` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `class_applied` varchar(60) NOT NULL,
  `prev_school` varchar(200) NOT NULL DEFAULT '',
  `guardian_phone` varchar(20) NOT NULL,
  `address` varchar(300) NOT NULL,
  `notes` varchar(500) NOT NULL DEFAULT '',
  `status` enum('pending','under_review','accepted','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admissions`
--

INSERT INTO `admissions` (`id`, `app_no`, `student_name`, `father_name`, `bform`, `dob`, `gender`, `class_applied`, `prev_school`, `guardian_phone`, `address`, `notes`, `status`, `created_at`) VALUES
(2, 'PCS-26-0001', 'Ali Ajmal Awan', 'Ajmal Hussain Malik', '34502-0000000-1', '2002-07-17', 'male', '2nd Year (Pre-Engineering)', '', '03087905450', 'Office #2, 2nd Floor Asad Plaza Gamtala Chowk Shakargarh,', '', 'pending', '2026-08-26 08:41:29');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(200) NOT NULL,
  `title` varchar(220) NOT NULL,
  `author` varchar(120) NOT NULL DEFAULT '',
  `excerpt` varchar(300) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `published_at` datetime NOT NULL,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--

CREATE TABLE `calendar_events` (
  `id` int(10) UNSIGNED NOT NULL,
  `session` varchar(16) NOT NULL DEFAULT '',
  `title` varchar(160) NOT NULL,
  `starts_on` date NOT NULL,
  `ends_on` date DEFAULT NULL,
  `type` enum('holiday','examination','event','meeting','deadline') NOT NULL DEFAULT 'event',
  `detail` varchar(400) NOT NULL DEFAULT '',
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `calendar_events`
--

INSERT INTO `calendar_events` (`id`, `session`, `title`, `starts_on`, `ends_on`, `type`, `detail`, `status`, `created_at`, `updated_at`) VALUES
(1, '2026-27', 'New Session Begins', '2026-04-01', NULL, 'event', 'First day of the new academic session. — placeholder date, replace via Admin → Academic Calendar.', 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32'),
(2, '2026-27', 'Summer Vacations', '2026-06-01', '2026-08-10', 'holiday', 'Campus closed for summer break. — placeholder date, replace via Admin → Academic Calendar.', 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32'),
(3, '2026-27', 'First Term Examinations', '2026-09-15', '2026-09-25', 'examination', 'First term examinations for all classes. — placeholder date, replace via Admin → Academic Calendar.', 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32'),
(4, '2026-27', 'Parent-Teacher Meeting', '2026-10-10', NULL, 'meeting', 'Term progress discussed with guardians. — placeholder date, replace via Admin → Academic Calendar.', 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32'),
(5, '2026-27', 'Result Announcement Deadline', '2026-10-20', NULL, 'deadline', 'First term report cards issued. — placeholder date, replace via Admin → Academic Calendar.', 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32'),
(6, '2026-27', 'Winter Vacations', '2026-12-25', '2027-01-05', 'holiday', 'Campus closed for winter break. — placeholder date, replace via Admin → Academic Calendar.', 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32'),
(7, '2026-27', 'Annual Examinations', '2027-03-01', '2027-03-15', 'examination', 'Final examinations for the session. — placeholder date, replace via Admin → Academic Calendar.', 'published', '2026-08-25 10:17:32', '2026-08-25 10:17:32');

-- --------------------------------------------------------

--
-- Table structure for table `class_subjects`
--

CREATE TABLE `class_subjects` (
  `id` int(10) UNSIGNED NOT NULL,
  `class_name` varchar(80) NOT NULL,
  `subjects` varchar(400) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `is_read`, `created_at`) VALUES
(1, 'Ali Ajmal Awan', 'aliajmalawan@gmail.com', '03001234567', 'Test', 'Testing from developer', 1, '2026-08-27 04:08:32');

-- --------------------------------------------------------

--
-- Table structure for table `core_values`
--

CREATE TABLE `core_values` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(80) NOT NULL,
  `description` varchar(400) NOT NULL,
  `icon` varchar(30) NOT NULL DEFAULT 'shield',
  `accent` varchar(20) NOT NULL DEFAULT 'accent-blue',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `core_values`
--

INSERT INTO `core_values` (`id`, `title`, `description`, `icon`, `accent`, `sort_order`, `status`, `created_at`) VALUES
(1, 'Excellence in Learning', 'High expectations paired with real support — clear goals, strong foundations and a culture that takes every subject seriously.', 'award', 'accent-amber', 1, 'published', '2026-08-24 07:49:32'),
(2, 'Integrity & Character', 'Honesty, discipline and responsibility are treated as seriously as academic results, in the classroom and out of it.', 'shield', 'accent-blue', 2, 'published', '2026-08-24 07:49:32'),
(3, 'Resilience', 'We build students who can meet a difficult problem, a hard exam or a setback and keep going.', 'mountain', 'accent-teal', 3, 'published', '2026-08-24 07:49:32'),
(4, 'Community & Care', 'A school where every student is known by name, and every family is treated as a partner, not a customer.', 'users', 'accent-violet', 4, 'published', '2026-08-24 07:49:32'),
(5, 'Curiosity', 'Questions are welcomed, not just answers — learning is encouraged for its own sake, not only for the exam.', 'book', 'accent-rose', 5, 'published', '2026-08-24 07:49:32'),
(6, 'Confident Communication', 'Students are encouraged to ask, present, discuss and lead — in the classroom, in competitions and on stage.', 'message', 'accent-orange', 6, 'published', '2026-08-24 07:49:32');

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_prefs`
--

CREATE TABLE `dashboard_prefs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `widgets` varchar(500) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(180) NOT NULL,
  `description` varchar(300) NOT NULL DEFAULT '',
  `category` enum('admissions','academics','forms','results','general') NOT NULL DEFAULT 'general',
  `file_path` varchar(255) DEFAULT NULL,
  `external_url` varchar(300) NOT NULL DEFAULT '',
  `file_size` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `file_ext` varchar(10) NOT NULL DEFAULT '',
  `download_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(120) NOT NULL,
  `description` varchar(400) NOT NULL DEFAULT '',
  `icon` varchar(30) NOT NULL DEFAULT 'shield',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `title`, `description`, `icon`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Science Laboratories', 'Dedicated spaces for physics, chemistry and biology practicals, supporting the syllabus taught in class.', 'flaskLab', 1, 'published', '2026-08-26 12:42:15', '2026-08-26 12:42:15'),
(2, 'Computer Lab', 'Hands-on computer classes as part of the curriculum from the middle school years onward.', 'cog', 2, 'published', '2026-08-26 12:42:15', '2026-08-26 12:42:15'),
(3, 'Library & Reading Room', 'A quiet space stocked with textbooks and reading material for independent study.', 'book', 3, 'published', '2026-08-26 12:42:15', '2026-08-26 12:42:15'),
(4, 'Sports Ground', 'Space for daily physical activity, house matches and inter-house sports competitions.', 'mountain', 4, 'published', '2026-08-26 12:42:15', '2026-08-26 12:42:15'),
(5, 'Transport Service', 'Van routes covering key areas of Hafizabad for students who need pick and drop.', 'bus', 5, 'published', '2026-08-26 12:42:15', '2026-08-26 12:42:15'),
(6, 'Prayer Area', 'A dedicated, clean space for daily prayers on campus.', 'shield', 6, 'published', '2026-08-26 12:42:15', '2026-08-26 12:42:15'),
(7, 'Assembly Hall', 'A shared space for morning assembly, school functions and events.', 'award', 7, 'published', '2026-08-26 12:42:15', '2026-08-26 12:42:15'),
(8, 'CCTV & Security', 'Monitored entry points and campus security during school hours.', 'users', 8, 'published', '2026-08-26 12:42:15', '2026-08-26 12:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `designation` varchar(120) NOT NULL,
  `department` varchar(80) NOT NULL DEFAULT '',
  `qualification` varchar(200) NOT NULL DEFAULT '',
  `photo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `name`, `designation`, `department`, `qualification`, `photo`, `bio`, `sort_order`, `status`, `created_at`) VALUES
(1, 'Ali Ajmal Awan', 'Teacher', 'Administration', 'Placeholder card — replace with real details via Admin → Faculty', 'faculty/20260827-091232-6655c08a.jpg', '', 1, 'published', '2026-08-24 07:49:32'),
(2, 'Huma Razaq', 'Vice Princiipal', 'Academics', 'Placeholder card — replace with real details via Admin → Faculty', NULL, '', 2, 'published', '2026-08-24 07:49:32'),
(3, 'Afshan', 'Teacher', 'Academics', 'Placeholder card — replace with real details via Admin → Faculty', NULL, '', 3, 'published', '2026-08-24 07:49:32'),
(4, 'Farah', 'Teacher', 'Academics', 'Placeholder card — replace with real details via Admin → Faculty', NULL, '', 4, 'published', '2026-08-24 07:49:32'),
(5, 'Tuba', 'Teacher', '', '', NULL, '', 5, 'published', '2026-08-25 07:39:27'),
(6, 'Arooj Fatima', 'Teacher', '', '', NULL, '', 6, 'published', '2026-08-25 07:42:02');

-- --------------------------------------------------------

--
-- Table structure for table `fee_notes`
--

CREATE TABLE `fee_notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `panel` enum('concession','payment') NOT NULL DEFAULT 'concession',
  `body` varchar(300) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fee_notes`
--

INSERT INTO `fee_notes` (`id`, `panel`, `body`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(3, 'concession', 'Eligible students may receive fee concessions according to school policy. Concessions may be considered for siblings, merit, or special circumstances, subject to management approval and applicable terms.', 0, 'published', '2026-08-26 18:55:19', '2026-08-26 18:55:19'),
(4, 'payment', 'Parents are requested to pay fees by the due date stated on the challan. Please retain the payment receipt and contact the school office promptly regarding any payment discrepancy or fee-related assistance.', 0, 'published', '2026-08-26 18:55:44', '2026-08-26 18:55:44');

-- --------------------------------------------------------

--
-- Table structure for table `fee_structure`
--

CREATE TABLE `fee_structure` (
  `id` int(10) UNSIGNED NOT NULL,
  `class_group` varchar(80) NOT NULL,
  `admission_fee` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `monthly_fee` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `exam_fee` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `notes` varchar(200) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fee_structure`
--

INSERT INTO `fee_structure` (`id`, `class_group`, `admission_fee`, `monthly_fee`, `exam_fee`, `notes`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(5, 'Playgroup – KG', 3000, 1800, 500, 'Includes activity materials', 1, 'published', '2026-08-26 12:13:40', '2026-08-26 12:13:40'),
(6, 'Grades I – V', 4000, 2200, 600, 'Sibling concession applies from the second child', 2, 'published', '2026-08-26 12:13:40', '2026-08-26 12:13:40'),
(7, 'Grades VI – VIII', 5000, 2800, 700, 'Computer lab charges included', 3, 'published', '2026-08-26 12:13:40', '2026-08-26 12:13:40'),
(8, 'Grades IX – X (Science)', 6500, 3500, 900, 'Laboratory and practical charges included', 4, 'published', '2026-08-26 12:13:40', '2026-08-26 12:13:40'),
(9, '1st & 2nd Year (F.Sc)', 8000, 4500, 1200, 'Board registration billed separately at cost', 5, 'published', '2026-08-26 12:13:40', '2026-08-26 12:13:40');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_albums`
--

CREATE TABLE `gallery_albums` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(160) NOT NULL,
  `title` varchar(160) NOT NULL,
  `description` varchar(300) NOT NULL DEFAULT '',
  `cover` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_albums`
--

INSERT INTO `gallery_albums` (`id`, `slug`, `title`, `description`, `cover`, `sort_order`, `status`, `created_at`) VALUES
(1, 'study-tour', 'Study Tour', '', NULL, 0, 'published', '2026-08-24 10:19:43'),
(2, 'teacher-s-day', 'Teacher\'s Day', '', NULL, 0, 'published', '2026-08-24 10:27:13'),
(3, 'sports', 'Sports', '', NULL, 0, 'published', '2026-08-24 10:30:25'),
(4, 'events-ceremonies', 'Events & Ceremonies', '', NULL, 0, 'published', '2026-08-24 10:33:56'),
(5, 'annual-prize-distribution', 'Annual Prize Distribution', '', NULL, 0, 'published', '2026-08-24 10:39:17'),
(6, 'cultural-day', 'Cultural Day', '', NULL, 0, 'published', '2026-08-24 10:40:39'),
(7, 'national-days', 'National Days', '', NULL, 0, 'published', '2026-08-24 10:42:12');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `album_id` int(10) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(200) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `album_id`, `image`, `caption`, `sort_order`, `created_at`) VALUES
(25, 1, 'gallery/20260824-152437-0cde210c.jpg', '', 0, '2026-08-24 10:24:38'),
(26, 1, 'gallery/20260824-152439-e9838231.jpg', '', 0, '2026-08-24 10:24:39'),
(27, 1, 'gallery/20260824-152440-e25567d1.jpg', '', 0, '2026-08-24 10:24:40'),
(28, 1, 'gallery/20260824-152441-feab052e.jpg', '', 0, '2026-08-24 10:24:42'),
(29, 1, 'gallery/20260824-152442-3859736b.jpg', '', 0, '2026-08-24 10:24:43'),
(30, 1, 'gallery/20260824-152443-bcc7ad6b.jpg', '', 0, '2026-08-24 10:24:45'),
(31, 1, 'gallery/20260824-152445-15648655.jpg', '', 0, '2026-08-24 10:24:46'),
(32, 1, 'gallery/20260824-152446-caea7f7e.jpg', '', 0, '2026-08-24 10:24:48'),
(33, 1, 'gallery/20260824-152448-63d555b9.jpg', '', 0, '2026-08-24 10:24:50'),
(34, 1, 'gallery/20260824-152450-1a1ba920.jpg', '', 0, '2026-08-24 10:24:51'),
(35, 1, 'gallery/20260824-152452-9b5469a9.jpg', '', 0, '2026-08-24 10:24:52'),
(36, 1, 'gallery/20260824-152453-b0765ec1.jpg', '', 0, '2026-08-24 10:24:54'),
(37, 1, 'gallery/20260824-152623-61adf3f1.jpg', '', 0, '2026-08-24 10:26:24'),
(38, 1, 'gallery/20260824-152625-b5ca5cdd.jpg', '', 0, '2026-08-24 10:26:25'),
(39, 1, 'gallery/20260824-152626-9691cae6.jpg', '', 0, '2026-08-24 10:26:27'),
(40, 2, 'gallery/20260824-152728-e9330090.jpg', '', 0, '2026-08-24 10:27:28'),
(41, 2, 'gallery/20260824-152729-94655e4a.jpg', '', 0, '2026-08-24 10:27:30'),
(42, 2, 'gallery/20260824-152730-821f6a5e.jpg', '', 0, '2026-08-24 10:27:31'),
(43, 2, 'gallery/20260824-152731-2038411e.jpg', '', 0, '2026-08-24 10:27:32'),
(44, 2, 'gallery/20260824-152733-cf5110a3.jpg', '', 0, '2026-08-24 10:27:34'),
(45, 2, 'gallery/20260824-152734-c272824a.jpg', '', 0, '2026-08-24 10:27:35'),
(46, 2, 'gallery/20260824-152735-f4e5f305.jpg', '', 0, '2026-08-24 10:27:36'),
(47, 2, 'gallery/20260824-152739-a20536c8.jpg', '', 0, '2026-08-24 10:27:41'),
(48, 2, 'gallery/20260824-152741-500ad5bb.jpg', '', 0, '2026-08-24 10:27:42'),
(49, 3, 'gallery/20260824-153045-6aa88440.jpg', '', 0, '2026-08-24 10:30:47'),
(50, 3, 'gallery/20260824-153048-7865a56b.jpg', '', 0, '2026-08-24 10:30:50'),
(51, 3, 'gallery/20260824-153052-f8c037aa.jpg', '', 0, '2026-08-24 10:30:55'),
(52, 3, 'gallery/20260824-153057-0f749e6f.jpg', '', 0, '2026-08-24 10:31:00'),
(53, 3, 'gallery/20260824-153102-0ee60492.jpg', '', 0, '2026-08-24 10:31:04'),
(54, 3, 'gallery/20260824-153106-78e0da90.jpg', '', 0, '2026-08-24 10:31:08'),
(55, 3, 'gallery/20260824-153113-9829e5cc.jpg', '', 0, '2026-08-24 10:31:23'),
(56, 3, 'gallery/20260824-153125-03f70c3e.jpg', '', 0, '2026-08-24 10:31:29'),
(57, 3, 'gallery/20260824-153130-b65fa903.jpg', '', 0, '2026-08-24 10:31:36'),
(58, 3, 'gallery/20260824-153138-a6c753fc.jpg', '', 0, '2026-08-24 10:31:40'),
(59, 3, 'gallery/20260824-153142-1563b886.jpg', '', 0, '2026-08-24 10:31:51'),
(60, 3, 'gallery/20260824-153159-720be088.jpg', '', 0, '2026-08-24 10:32:06'),
(61, 3, 'gallery/20260824-153209-e0ac55df.jpg', '', 0, '2026-08-24 10:32:21'),
(62, 3, 'gallery/20260824-153224-947088c1.jpg', '', 0, '2026-08-24 10:32:27'),
(63, 3, 'gallery/20260824-153230-eb2ed582.jpg', '', 0, '2026-08-24 10:32:32'),
(64, 3, 'gallery/20260824-153233-7523234c.jpg', '', 0, '2026-08-24 10:32:35'),
(65, 4, 'gallery/20260824-153413-ec107a22.jpg', '', 0, '2026-08-24 10:34:15'),
(66, 4, 'gallery/20260824-153416-ca9be335.jpg', '', 0, '2026-08-24 10:34:19'),
(67, 4, 'gallery/20260824-153420-c078a413.jpg', '', 0, '2026-08-24 10:34:22'),
(68, 4, 'gallery/20260824-153424-21e22bc2.jpg', '', 0, '2026-08-24 10:34:26'),
(69, 4, 'gallery/20260824-153428-5a7a5f01.jpg', '', 0, '2026-08-24 10:34:30'),
(70, 5, 'gallery/20260824-153936-b5420530.jpg', '', 0, '2026-08-24 10:39:42'),
(71, 5, 'gallery/20260824-153945-513e5a0b.jpg', '', 0, '2026-08-24 10:39:46'),
(72, 5, 'gallery/20260824-153949-e62423cf.jpg', '', 0, '2026-08-24 10:39:51'),
(73, 6, 'gallery/20260824-154057-60dd2b06.jpg', '', 0, '2026-08-24 10:41:00'),
(74, 6, 'gallery/20260824-154105-3287a84f.jpg', '', 0, '2026-08-24 10:41:08'),
(75, 6, 'gallery/20260824-154113-14d32a42.jpg', '', 0, '2026-08-24 10:41:17'),
(76, 6, 'gallery/20260824-154122-9a626b6f.jpg', '', 0, '2026-08-24 10:41:24'),
(77, 6, 'gallery/20260824-154126-5df3c339.jpg', '', 0, '2026-08-24 10:41:28'),
(78, 6, 'gallery/20260824-154130-82c72ba8.jpg', '', 0, '2026-08-24 10:41:32'),
(79, 6, 'gallery/20260824-154135-71568c96.jpg', '', 0, '2026-08-24 10:41:36'),
(80, 6, 'gallery/20260824-154139-cea6b71d.jpg', '', 0, '2026-08-24 10:41:40'),
(81, 6, 'gallery/20260824-154142-914832d4.jpg', '', 0, '2026-08-24 10:41:44'),
(82, 7, 'gallery/20260824-154226-44fcdcae.jpg', '', 0, '2026-08-24 10:42:27'),
(83, 7, 'gallery/20260824-154228-ef1cbec5.jpg', '', 0, '2026-08-24 10:42:28'),
(84, 7, 'gallery/20260824-154228-31bd63e4.jpg', '', 0, '2026-08-24 10:42:29'),
(85, 7, 'gallery/20260824-154229-54de56a3.jpg', '', 0, '2026-08-24 10:42:30');

-- --------------------------------------------------------

--
-- Table structure for table `grading_scale`
--

CREATE TABLE `grading_scale` (
  `id` int(10) UNSIGNED NOT NULL,
  `grade` varchar(8) NOT NULL,
  `band` varchar(40) NOT NULL,
  `remark` varchar(120) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grading_scale`
--

INSERT INTO `grading_scale` (`id`, `grade`, `band`, `remark`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'A+', '90% and above', 'Outstanding', 1, 'published', '2026-08-25 09:21:07', '2026-08-25 09:21:07'),
(2, 'A', '80% – 89%', 'Excellent', 2, 'published', '2026-08-25 09:21:07', '2026-08-25 09:21:07'),
(3, 'B', '70% – 79%', 'Very Good', 3, 'published', '2026-08-25 09:21:07', '2026-08-25 09:21:07'),
(4, 'C', '60% – 69%', 'Good', 4, 'published', '2026-08-25 09:21:07', '2026-08-25 09:21:07'),
(5, 'D', '50% – 59%', 'Satisfactory', 5, 'published', '2026-08-25 09:21:07', '2026-08-25 09:21:07'),
(6, 'E', '40% – 49%', 'Pass', 6, 'published', '2026-08-25 09:21:07', '2026-08-25 09:21:07'),
(7, 'F', 'Below 40%', 'Fail', 7, 'published', '2026-08-25 09:21:07', '2026-08-25 09:21:07');

-- --------------------------------------------------------

--
-- Table structure for table `leadership`
--

CREATE TABLE `leadership` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `designation` varchar(120) NOT NULL,
  `qualification` varchar(200) NOT NULL DEFAULT '',
  `tenure` varchar(80) NOT NULL DEFAULT '',
  `photo` varchar(255) DEFAULT NULL,
  `bio` varchar(500) NOT NULL DEFAULT '',
  `message` mediumtext NOT NULL DEFAULT '',
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(160) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leadership`
--

INSERT INTO `leadership` (`id`, `name`, `designation`, `qualification`, `tenure`, `photo`, `bio`, `message`, `featured`, `email`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Naeem Qamar', 'CEO', '', '', 'leadership/20260825-104416-8c8a4826.jpg', '', '“At Pakistan Cambridge School, our commitment goes beyond academic excellence. We strive to nurture confident, disciplined, and compassionate individuals who are equipped with knowledge, character, and the courage to lead. Through a supportive learning environment and strong values, we aim to empower every student to discover their potential and contribute positively to society. Together, we are building a brighter future, one student at a time.”', 1, '', 1, 'published', '2026-08-25 02:55:48', '2026-08-25 07:45:54'),
(2, 'Ahmad Younas', 'Director', '', '', 'leadership/20260825-104508-61b62abc.jpg', '', '“At Pakistan Cambridge School, we believe that meaningful education prepares students not only for academic success but also for the challenges of tomorrow. Our vision is to create an environment where students are encouraged to think independently, embrace innovation, develop strong character, and pursue excellence. With the dedication of our teachers, the support of our parents, and the determination of our students, we are committed to shaping responsible, confident, and future-ready leaders.”', 0, '', 2, 'published', '2026-08-25 02:55:48', '2026-08-25 07:27:20'),
(3, 'Amna Yousaf', 'Principal', '', '', NULL, '', 'At Pakistan Cambridge School, we believe that every child has the potential to achieve greatness when guided by the right values, opportunities, and encouragement. Our aim is to provide a nurturing environment where students can grow academically, develop confidence, and build strong character. With dedicated teachers and the continued support of parents, we are committed to helping our students become responsible, compassionate, and successful individuals who can make a positive difference in the world.”', 0, '', 3, 'published', '2026-08-25 07:47:41', '2026-08-25 07:49:41');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(160) NOT NULL,
  `ip_hash` char(64) NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `description` varchar(200) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `slug`, `description`, `created_at`) VALUES
(1, 'Header Menu', 'main', 'Main navigation shown in the site header.', '2026-08-21 06:05:24'),
(2, 'Footer - Quick Links', 'footer-quick', 'First footer column.', '2026-08-21 06:05:24'),
(3, 'Footer - Admissions', 'footer-admissions', 'Second footer column.', '2026-08-21 06:05:24');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `label` varchar(80) NOT NULL,
  `link_type` enum('route','page','custom','none') NOT NULL DEFAULT 'route',
  `route` varchar(120) NOT NULL DEFAULT '',
  `page_id` int(10) UNSIGNED DEFAULT NULL,
  `custom_url` varchar(300) NOT NULL DEFAULT '',
  `new_tab` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `label`, `link_type`, `route`, `page_id`, `custom_url`, `new_tab`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Home', 'route', '/', NULL, '', 0, 1, 'published', '2026-08-21 06:05:24', '2026-08-24 09:05:31'),
(2, 1, NULL, 'About', 'page', '', 1, '', 0, 2, 'published', '2026-08-21 06:05:24', '2026-08-24 09:05:31'),
(3, 1, 2, 'Vision & Mission', 'route', '/vision-mission', NULL, '', 0, 4, 'published', '2026-08-21 06:05:24', '2026-08-24 09:07:01'),
(4, 1, 2, 'Leadership', 'route', '/leadership', NULL, '', 0, 5, 'published', '2026-08-21 06:05:24', '2026-08-24 09:07:01'),
(5, 1, NULL, 'Academics', 'route', '/academics', NULL, '', 0, 7, 'published', '2026-08-21 06:05:24', '2026-08-24 09:07:01'),
(6, 1, 5, 'Programs', 'route', '/programs', NULL, '', 0, 10, 'published', '2026-08-21 06:05:24', '2026-08-24 09:45:49'),
(7, 1, 5, 'Academic Calendar', 'route', '/academic-calendar', NULL, '', 0, 9, 'published', '2026-08-21 06:05:24', '2026-08-24 09:12:00'),
(8, 1, 42, 'Apply Online', 'route', '/admissions', NULL, '', 0, 12, 'published', '2026-08-21 06:05:24', '2026-08-24 09:45:49'),
(9, 1, 42, 'Fee Structure', 'route', '/fees', NULL, '', 0, 13, 'published', '2026-08-21 06:05:24', '2026-08-24 09:45:49'),
(10, 1, 2, 'Faculty', 'route', '/faculty', NULL, '', 0, 6, 'published', '2026-08-21 06:05:24', '2026-08-24 09:07:01'),
(20, 2, NULL, 'About Us', 'page', '', 1, '', 0, 10, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(21, 2, NULL, 'Academics', 'route', '/academics', NULL, '', 0, 20, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(22, 2, NULL, 'Academic Programs', 'route', '/programs', NULL, '', 0, 30, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(23, 2, NULL, 'Our Faculty', 'route', '/faculty', NULL, '', 0, 40, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(24, 2, NULL, 'News & Notices', 'route', '/news', NULL, '', 0, 50, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(25, 2, NULL, 'Gallery', 'route', '/gallery', NULL, '', 0, 60, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(30, 3, NULL, 'Apply Online', 'route', '/admissions', NULL, '', 0, 10, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(31, 3, NULL, 'Fee Structure', 'route', '/fees', NULL, '', 0, 20, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(32, 3, NULL, 'Downloads & Forms', 'route', '/downloads', NULL, '', 0, 30, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(33, 3, NULL, 'Rules & Discipline', 'page', '', 4, '', 0, 40, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(34, 3, NULL, 'Contact the Office', 'route', '/contact', NULL, '', 0, 50, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(35, 1, 2, 'About Us', 'page', '', 1, '', 0, 3, 'published', '2026-08-24 09:06:48', '2026-08-24 09:07:01'),
(36, 1, 5, 'Academic Overview', 'route', '/academics', NULL, '', 0, 8, 'published', '2026-08-24 09:09:14', '2026-08-24 09:09:55'),
(37, 1, NULL, 'Campus Life', 'page', '', 3, '', 0, 14, 'published', '2026-08-24 09:16:26', '2026-08-24 09:45:49'),
(38, 1, NULL, 'Gallery', 'route', '/gallery', NULL, '', 0, 17, 'published', '2026-08-24 09:37:18', '2026-08-24 09:45:49'),
(39, 1, 37, 'News & Notices', 'route', '/news', NULL, '', 0, 16, 'published', '2026-08-24 09:37:24', '2026-08-24 09:45:49'),
(40, 1, NULL, 'Contact', 'route', '/contact', NULL, '', 0, 19, 'published', '2026-08-24 09:38:06', '2026-08-24 10:43:14'),
(41, 1, 37, 'Facilities', 'page', '', 2, '', 0, 15, 'published', '2026-08-24 09:39:02', '2026-08-24 09:45:49'),
(42, 1, NULL, 'Admissions', 'route', '/admissions', NULL, '', 0, 11, 'published', '2026-08-24 09:45:16', '2026-08-24 09:45:49'),
(43, 1, NULL, 'Blog', 'route', '/blogs', NULL, '', 0, 18, 'published', '2026-08-24 10:19:05', '2026-08-24 10:43:14');

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

CREATE TABLE `milestones` (
  `id` int(10) UNSIGNED NOT NULL,
  `year` varchar(16) NOT NULL,
  `title` varchar(140) NOT NULL,
  `detail` varchar(400) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(200) NOT NULL,
  `title` varchar(220) NOT NULL,
  `category` enum('news','event','notice') NOT NULL DEFAULT 'news',
  `excerpt` varchar(300) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `published_at` datetime NOT NULL,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `page_type` enum('content','system') NOT NULL DEFAULT 'content',
  `slug` varchar(160) NOT NULL,
  `route` varchar(120) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `lede` varchar(400) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `meta_description` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `show_in_menu` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `page_type`, `slug`, `route`, `title`, `lede`, `content`, `meta_description`, `image`, `show_in_menu`, `sort_order`, `status`, `updated_at`, `created_at`) VALUES
(1, 'content', 'about', NULL, 'About Us', 'A school built for Hafizabad — where academic excellence, character and confidence grow together, from Playgroup to Higher Secondary.', '<p>Pakistan Cambridge School Hafizabad exists for one reason: to give students in Hafizabad an education that stands up anywhere — strong academics, real character, and the confidence to use both.</p><p>We bring together experienced teachers, a structured curriculum and a genuine focus on every learner, from their very first day of Playgroup through to Higher Secondary. Along the way, students are encouraged to ask questions, take part, lead and grow — not just pass exams.</p>', 'About Pakistan Cambridge School Hafizabad — our mission, vision, journey and the people who lead it.', NULL, 1, 1, 'published', '2026-08-25 02:55:47', '2026-08-21 06:05:24'),
(2, 'content', 'facilities', NULL, 'Facilities', 'A campus set up for daily learning, activity and worship — not just classrooms.', '<p>Beyond the classroom, Pakistan Cambridge School Hafizabad maintains the facilities students use every day — from laboratories and the library to sports grounds and transport.</p>', 'Campus facilities at Pakistan Cambridge School Hafizabad — laboratories, library, computer lab, sports ground, transport and more.', NULL, 1, 2, 'published', '2026-08-26 12:42:15', '2026-08-21 06:05:24'),
(3, 'content', 'campus-life', NULL, 'Campus Life', '', '', '', NULL, 1, 3, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(4, 'content', 'rules', NULL, 'Rules & Discipline', '', '', '', NULL, 0, 4, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(5, 'system', 'sys-home', '/', 'Pakistan Cambridge School', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(6, 'system', 'sys-vision-mission', '/vision-mission', 'Vision & Mission', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(7, 'system', 'sys-programs', '/programs', 'Academic Programs', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(8, 'system', 'sys-fees', '/fees', 'Fee Structure', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(9, 'system', 'sys-faculty', '/faculty', 'Our Faculty', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(10, 'system', 'sys-news', '/news', 'News & Notices', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(11, 'system', 'sys-gallery', '/gallery', 'Gallery', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(12, 'system', 'sys-admissions', '/admissions', 'Admissions', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(13, 'system', 'sys-downloads', '/downloads', 'Downloads & Forms', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(14, 'system', 'sys-contact', '/contact', 'Contact Us', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(15, 'system', 'sys-search', '/search', 'Search', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(16, 'system', 'sys-leadership', '/leadership', 'Leadership', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24'),
(17, 'system', 'sys-academics', '/academics', 'Academics', 'A curriculum built on structure, not shortcuts — clear expectations at every grade, from Playgroup to Higher Secondary.', '<p>Academics at Pakistan Cambridge School Hafizabad follow a simple principle: strong foundations before specialisation. Every grade has a mapped syllabus, every subject has a qualified teacher, and every student\'s progress is tracked continuously — not just at exam time.</p>', 'How academics work at Pakistan Cambridge School Hafizabad — curriculum framework and grading scale.', NULL, 0, 0, 'published', '2026-08-25 09:21:07', '2026-08-21 06:05:24'),
(18, 'system', 'sys-academic-calendar', '/academic-calendar', 'Academic Calendar', '', '', '', NULL, 0, 0, 'published', '2026-08-21 06:05:24', '2026-08-21 06:05:24');

-- --------------------------------------------------------

--
-- Table structure for table `page_views`
--

CREATE TABLE `page_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `visitor_hash` char(64) NOT NULL,
  `referrer` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_views`
--

INSERT INTO `page_views` (`id`, `path`, `visitor_hash`, `referrer`, `created_at`) VALUES
(1, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 06:29:21'),
(2, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 10:21:43'),
(3, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 10:21:45'),
(4, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 10:21:46'),
(5, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 10:55:12'),
(6, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 10:55:44'),
(7, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:03:57'),
(8, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:03:58'),
(9, '/galleryy', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:04:03'),
(10, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:04:08'),
(11, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:16:56'),
(12, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:00'),
(13, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:04'),
(14, '/vision-mission', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:06'),
(15, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:16'),
(16, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:22'),
(17, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:24'),
(18, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:27'),
(19, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:31'),
(20, '/page/campus-life', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:32'),
(21, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:33'),
(22, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:35'),
(23, '/page/rules', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:39'),
(24, '/page/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:43'),
(25, '/search', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:46'),
(26, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:17:49'),
(27, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-21 11:29:57'),
(28, '/', 'd0a27ad69efab63ce8302cc59f395256ee5097d011c429ce86f4a8d03ef90c1c', '', '2026-08-23 09:44:56'),
(29, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-23 12:54:02'),
(30, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 07:13:25'),
(31, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 07:21:09'),
(32, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 07:31:16'),
(33, '/', '2f476f250fe6c1591a9f6b88713f767d78019007baf3cb116017f8b21e9dcd7d', '', '2026-08-24 07:46:21'),
(34, '/', '87eda7490a60479dde767f34114e9444a889136a8c7e392fb29af3779eebb14d', '', '2026-08-24 07:56:40'),
(35, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 07:57:55'),
(36, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 08:01:51'),
(37, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 08:07:04'),
(38, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 08:09:21'),
(39, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 08:31:43'),
(40, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 08:43:50'),
(41, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 08:54:03'),
(42, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 08:56:08'),
(43, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 08:56:12'),
(44, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 08:59:05'),
(45, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:01:31'),
(46, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:01:33'),
(47, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:04:09'),
(48, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:05:37'),
(49, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:07:04'),
(50, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:08:51'),
(51, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:09:30'),
(52, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:10:06'),
(53, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:10:12'),
(54, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:10:15'),
(55, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:10:23'),
(56, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:11:23'),
(57, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:12:03'),
(58, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:12:07'),
(59, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:12:09'),
(60, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:12:11'),
(61, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:12:16'),
(62, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:12:25'),
(63, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:12:29'),
(64, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:16:34'),
(65, '/page/campus-life', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:22:10'),
(66, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:25:53'),
(67, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:28:23'),
(68, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:32:08'),
(69, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:35:51'),
(70, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:37:00'),
(71, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:38:12'),
(72, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:38:25'),
(73, '/page/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:38:29'),
(74, '/page/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:39:21'),
(75, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:40:25'),
(76, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:40:28'),
(77, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:40:29'),
(78, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:42:02'),
(79, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:44:29'),
(80, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:45:53'),
(81, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:46:51'),
(82, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:46:54'),
(83, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:46:58'),
(84, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:49:02'),
(85, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 09:49:03'),
(86, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:03:56'),
(87, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:05:27'),
(88, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:05:32'),
(89, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:07:52'),
(90, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:07:53'),
(91, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:08:14'),
(92, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:14:03'),
(93, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:18:48'),
(94, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:21:27'),
(95, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:24:21'),
(96, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:25:02'),
(97, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:27:45'),
(98, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:27:52'),
(99, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:29:02'),
(100, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:29:16'),
(101, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:32:35'),
(102, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:33:00'),
(103, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:35:44'),
(104, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:42:31'),
(105, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:42:49'),
(106, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:43:19'),
(107, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:43:39'),
(108, '/blogs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:43:40'),
(109, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:50:09'),
(110, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:50:12'),
(111, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 10:58:37'),
(112, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:06:37'),
(113, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:11:31'),
(114, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:11:40'),
(115, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:12:15'),
(116, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:22:59'),
(117, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:23:02'),
(118, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:27:44'),
(119, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:31:02'),
(120, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:34:30'),
(121, '/blogs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:34:47'),
(122, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:35:19'),
(123, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:35:29'),
(124, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:35:34'),
(125, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:36:47'),
(126, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:37:20'),
(127, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:38:40'),
(128, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:38:43'),
(129, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:41:59'),
(130, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:43:03'),
(131, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:45:27'),
(132, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:46:07'),
(133, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:48:00'),
(134, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:51:55'),
(135, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 11:54:58'),
(136, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:03:45'),
(137, '/search', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:07:26'),
(138, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:07:30'),
(139, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:08:59'),
(140, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:13:32'),
(141, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:14:31'),
(142, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:16:19'),
(143, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:17:12'),
(144, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:17:17'),
(145, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:17:20'),
(146, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:23:33'),
(147, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:24:42'),
(148, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:24:53'),
(149, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:28:54'),
(150, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-24 12:29:34'),
(151, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 03:00:27'),
(152, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 03:00:51'),
(153, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 03:04:05'),
(154, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 03:04:10'),
(155, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 03:04:45'),
(156, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 03:11:23'),
(157, '/vision-mission', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 03:16:22'),
(158, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 03:16:29'),
(159, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 03:30:52'),
(160, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 03:30:56'),
(161, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 03:32:08'),
(162, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 03:57:32'),
(163, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:14:42'),
(164, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:18:58'),
(165, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:20:20'),
(166, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:24:02'),
(167, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:28:06'),
(168, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:29:06'),
(169, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:29:22'),
(170, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:30:03'),
(171, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:30:49'),
(172, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:36:50'),
(173, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:40:32'),
(174, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:41:23'),
(175, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:43:20'),
(176, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:45:27'),
(177, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:45:59'),
(178, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:49:02'),
(179, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:49:18'),
(180, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:50:05'),
(181, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:55:54'),
(182, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:58:23'),
(183, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:59:17'),
(184, '/vision-mission', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:59:20'),
(185, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:59:23'),
(186, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 04:59:26'),
(187, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:05:07'),
(188, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:05:17'),
(189, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:05:22'),
(190, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:05:23'),
(191, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:15:02'),
(192, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:23:08'),
(193, '/about', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:32:25'),
(194, '/vision-mission', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:32:39'),
(195, '/vision-mission', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:36:16'),
(196, '/vision-mission', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:45:37'),
(197, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:45:40'),
(198, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:45:53'),
(199, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:46:21'),
(200, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:48:36'),
(201, '/vision-mission', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:49:25'),
(202, '/vision-mission', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:49:30'),
(203, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:51:12'),
(204, '/vision-mission', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:51:15'),
(205, '/vision-mission', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:53:24'),
(206, '/vision-mission', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:56:42'),
(207, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:56:51'),
(208, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 05:58:14'),
(209, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:07:39'),
(210, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:07:48'),
(211, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:08:03'),
(212, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:09:46'),
(213, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:11:12'),
(214, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:12:33'),
(215, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:13:57'),
(216, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:19:04'),
(217, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:19:08'),
(218, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:20:07'),
(219, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:20:27'),
(220, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:24:48'),
(221, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:24:56'),
(222, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:25:12'),
(223, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:26:05'),
(224, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:26:29'),
(225, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:26:33'),
(226, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:28:55'),
(227, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:39:39'),
(228, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:41:12'),
(229, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:43:58'),
(230, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:54:31'),
(231, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:54:53'),
(232, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:55:01'),
(233, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 06:55:07'),
(234, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:07:13'),
(235, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:15:23'),
(236, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:25:26'),
(237, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:26:31'),
(238, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:27:22'),
(239, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:27:29'),
(240, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:28:44'),
(241, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:34:32'),
(242, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:35:45'),
(243, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:35:55'),
(244, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:37:38'),
(245, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:38:19'),
(246, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:40:40'),
(247, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:40:55'),
(248, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:44:18'),
(249, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:44:39'),
(250, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:44:41'),
(251, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:44:45'),
(252, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:45:37'),
(253, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:45:58'),
(254, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:46:09'),
(255, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:47:45'),
(256, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:47:48'),
(257, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:47:50'),
(258, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:49:45'),
(259, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:49:52'),
(260, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:50:38'),
(261, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 07:51:54'),
(262, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 08:03:41'),
(263, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 08:03:43'),
(264, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 08:03:48'),
(265, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 08:13:20'),
(266, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 08:40:01'),
(267, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 08:40:05'),
(268, '/blogs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 08:45:37'),
(269, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 08:45:47'),
(270, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 08:45:54'),
(271, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 08:46:01'),
(272, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 08:46:05'),
(273, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:01:08'),
(274, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:06:23'),
(275, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:06:29'),
(276, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:06:32'),
(277, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:12:59'),
(278, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:13:05'),
(279, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:15:07'),
(280, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:15:11'),
(281, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:15:22'),
(282, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:15:25'),
(283, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:19:00'),
(284, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:19:04'),
(285, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:19:48'),
(286, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:19:53'),
(287, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:20:16'),
(288, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:20:44'),
(289, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:20:48'),
(290, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:20:52'),
(291, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:20:57'),
(292, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:25:48'),
(293, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:26:58'),
(294, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:27:03'),
(295, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:51:14'),
(296, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:54:43'),
(297, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 09:57:29'),
(298, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:07:27'),
(299, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:07:33'),
(300, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:07:37'),
(301, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:07:42'),
(302, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:07:53'),
(303, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:08:41'),
(304, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:08:50'),
(305, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:10:16'),
(306, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:11:35'),
(307, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:12:59'),
(308, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:13:25'),
(309, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:13:39'),
(310, '/blogs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:14:16'),
(311, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:14:19'),
(312, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:14:25'),
(313, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:15:22'),
(314, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:15:26'),
(315, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:25:15'),
(316, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:29:32'),
(317, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:32:50'),
(318, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:33:09'),
(319, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:33:20'),
(320, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:39:36'),
(321, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:40:17'),
(322, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:40:24'),
(323, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:47:47'),
(324, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:49:23'),
(325, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:49:36'),
(326, '/blogs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:49:41'),
(327, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:49:43'),
(328, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:49:44'),
(329, '/news', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:49:47'),
(330, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:49:49'),
(331, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 10:51:33'),
(332, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:04:07'),
(333, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:04:35'),
(334, '/0321%203351753', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:08:49'),
(335, '/0321%203351753', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:08:53'),
(336, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:09:00'),
(337, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:09:22'),
(338, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:18:30'),
(339, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:19:39'),
(340, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:22:12'),
(341, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:24:10'),
(342, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:27:06'),
(343, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:27:12'),
(344, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:27:15'),
(345, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:27:36'),
(346, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:27:40'),
(347, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:28:25'),
(348, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:28:43'),
(349, '/academic-calendar', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:29:27'),
(350, '/programs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:30:38'),
(351, '/academics', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:30:44'),
(352, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-25 11:42:24'),
(353, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 06:41:18'),
(354, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 06:41:35'),
(355, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 07:04:00'),
(356, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 07:04:26'),
(357, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 07:31:32'),
(358, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 07:31:36'),
(359, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 07:48:36'),
(360, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 07:48:46'),
(361, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 08:02:16'),
(362, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 08:39:47'),
(363, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 08:39:51'),
(364, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 08:41:32'),
(365, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 08:42:17'),
(366, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 08:47:23'),
(367, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 08:52:04'),
(368, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 08:52:07'),
(369, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 09:02:11'),
(370, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 09:02:20'),
(371, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 09:02:28'),
(372, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 09:04:10'),
(373, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 09:06:56'),
(374, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 09:13:46'),
(375, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 09:15:49'),
(376, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 09:16:04'),
(377, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 09:24:33'),
(378, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 09:29:40'),
(379, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 09:30:26'),
(380, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 10:56:02'),
(381, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 11:38:19'),
(382, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 11:46:58'),
(383, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:07:00'),
(384, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:19:54'),
(385, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:20:28'),
(386, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:29:10'),
(387, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:31:54'),
(388, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:36:55'),
(389, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:39:06'),
(390, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:39:07'),
(391, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:39:08'),
(392, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:39:09'),
(393, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:39:09'),
(394, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:39:09'),
(395, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:39:15'),
(396, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:39:22'),
(397, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:43:43'),
(398, '/news', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:43:59'),
(399, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:44:08'),
(400, '/facilities', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:47:09'),
(401, '/news', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:47:14'),
(402, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 12:47:48'),
(403, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:00:19'),
(404, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:02:04'),
(405, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:06:18'),
(406, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:06:21'),
(407, '/blogs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:09:42'),
(408, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:09:55'),
(409, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:12:23'),
(410, '/blogs', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:12:34'),
(411, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:12:39'),
(412, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:12:47'),
(413, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:12:55'),
(414, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:12:57'),
(415, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 13:13:07'),
(416, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 16:47:44'),
(417, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 16:47:59'),
(418, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 17:10:40'),
(419, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 17:11:05'),
(420, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:21:34'),
(421, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:22:52'),
(422, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:22:54'),
(423, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:23:12'),
(424, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:25:26'),
(425, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:25:29'),
(426, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:25:56'),
(427, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:28:49'),
(428, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:30:16'),
(429, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:32:47'),
(430, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:35:20'),
(431, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:37:03'),
(432, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:37:17'),
(433, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:41:56'),
(434, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:48:02'),
(435, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:49:28'),
(436, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:50:51'),
(437, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:51:28'),
(438, '/fees', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:55:50'),
(439, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 18:56:13'),
(440, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 19:11:19'),
(441, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 19:13:34'),
(442, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 19:13:53'),
(443, '/leadership', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 19:14:36'),
(444, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-26 19:14:56'),
(445, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 01:50:42'),
(446, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 01:50:47'),
(447, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 03:07:34'),
(448, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 03:07:40'),
(449, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 03:17:48'),
(450, '/', 'd0a27ad69efab63ce8302cc59f395256ee5097d011c429ce86f4a8d03ef90c1c', '', '2026-08-27 03:31:26'),
(451, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 03:32:34'),
(452, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 03:32:43');
INSERT INTO `page_views` (`id`, `path`, `visitor_hash`, `referrer`, `created_at`) VALUES
(453, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 03:38:00'),
(454, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 03:42:47'),
(455, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 03:50:29'),
(456, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 03:53:10'),
(457, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 03:54:16'),
(458, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 03:57:26'),
(459, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 03:58:44'),
(460, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:02:26'),
(461, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:02:43'),
(462, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:07:22'),
(463, '/contact', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:08:34'),
(464, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:11:21'),
(465, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:12:35'),
(466, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:12:38'),
(467, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:14:29'),
(468, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:21:10'),
(469, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:21:58'),
(470, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:22:01'),
(471, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:24:19'),
(472, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:26:03'),
(473, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:28:14'),
(474, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:28:20'),
(475, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:29:01'),
(476, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:29:12'),
(477, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:35:49'),
(478, '/faculty', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 04:35:52'),
(479, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 05:53:43'),
(480, '/gallery', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 05:56:41'),
(481, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 06:02:11'),
(482, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 06:10:48'),
(483, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 06:10:52'),
(484, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 06:10:59'),
(485, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 06:12:16'),
(486, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 06:13:45'),
(487, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 06:17:27'),
(488, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 06:23:17'),
(489, '/admissions', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 06:23:29'),
(490, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 06:23:32'),
(491, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 06:24:15'),
(492, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 06:31:52'),
(493, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:03:13'),
(494, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:06:02'),
(495, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:10:32'),
(496, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:11:23'),
(497, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:25:13'),
(498, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:32:32'),
(499, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:36:33'),
(500, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:39:52'),
(501, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:41:07'),
(502, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:41:39'),
(503, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:41:43'),
(504, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:45:38'),
(505, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:46:06'),
(506, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:48:49'),
(507, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:54:56'),
(508, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:57:12'),
(509, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 07:59:51'),
(510, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 08:00:11'),
(511, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 08:44:50'),
(512, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 08:49:38'),
(513, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 08:50:55'),
(514, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 08:55:19'),
(515, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 08:57:47'),
(516, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 09:12:14'),
(517, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 09:25:35'),
(518, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 09:35:15'),
(519, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 09:42:43'),
(520, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 09:43:38'),
(521, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 09:45:13'),
(522, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 09:46:11'),
(523, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 09:48:24'),
(524, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 09:50:45'),
(525, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 09:51:32'),
(526, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 09:52:30'),
(527, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 09:55:01'),
(528, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:00:31'),
(529, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:13:24'),
(530, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:14:57'),
(531, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:19:03'),
(532, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:24:44'),
(533, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:27:44'),
(534, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:27:57'),
(535, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:31:56'),
(536, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:32:00'),
(537, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:32:52'),
(538, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:36:35'),
(539, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:37:43'),
(540, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:40:21'),
(541, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 10:41:33'),
(542, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:43:25'),
(543, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:45:12'),
(544, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:47:08'),
(545, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:50:25'),
(546, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:51:26'),
(547, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:52:56'),
(548, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:53:09'),
(549, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:54:26'),
(550, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 10:57:19'),
(551, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 11:11:50'),
(552, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 11:15:45'),
(553, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 11:17:35'),
(554, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 11:17:54'),
(555, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 11:19:10'),
(556, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 11:19:41'),
(557, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 11:19:51'),
(558, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 11:23:57'),
(559, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 11:24:13'),
(560, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 11:26:49'),
(561, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 11:26:53'),
(562, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 11:29:52'),
(563, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 11:30:11'),
(564, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 11:31:15'),
(565, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 11:39:10'),
(566, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 11:41:39'),
(567, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 11:41:49'),
(568, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 11:48:16'),
(569, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 12:15:44'),
(570, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 12:44:08'),
(571, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 12:44:17'),
(572, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 12:55:05'),
(573, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 13:01:23'),
(574, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 13:04:46'),
(575, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 13:08:56'),
(576, '/', 'fc8540138aec87b4ee876cbe33f9abbd84c9500f1a75d4e7b17a241978e2bd3a', '', '2026-08-27 13:09:08'),
(577, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 13:11:39'),
(578, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 13:15:57'),
(579, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 13:19:07'),
(580, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 13:28:12'),
(581, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 13:42:05'),
(582, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 13:47:58'),
(583, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 13:49:43'),
(584, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 13:51:59'),
(585, '/', '12e0f8112927a61f7f037c2afbecb2086efbe54605132c3e49abff5b12b281c3', '', '2026-08-27 19:23:43'),
(586, '/', 'a51b1d7fb67857ebfa40a8050706bfaaf39c545f3bd48bbf0bf82d6b9f878a9c', '', '2026-09-17 09:49:13'),
(587, '/', '8a3d750605927311d8dc37515d76190f21be322b8412eec224c58d18db1c7b37', '', '2026-09-17 10:29:44'),
(588, '/', '8a3d750605927311d8dc37515d76190f21be322b8412eec224c58d18db1c7b37', '', '2026-09-17 10:29:45'),
(589, '/', '8a3d750605927311d8dc37515d76190f21be322b8412eec224c58d18db1c7b37', '', '2026-09-17 11:07:07'),
(590, '/', '8a3d750605927311d8dc37515d76190f21be322b8412eec224c58d18db1c7b37', '', '2026-09-17 11:29:44'),
(591, '/', '8a3d750605927311d8dc37515d76190f21be322b8412eec224c58d18db1c7b37', '', '2026-09-18 03:45:42');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(160) NOT NULL,
  `name` varchar(160) NOT NULL,
  `level` varchar(80) NOT NULL,
  `description` text NOT NULL,
  `subjects` varchar(500) NOT NULL DEFAULT '',
  `icon` varchar(40) NOT NULL DEFAULT 'book',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `slug`, `name`, `level`, `description`, `subjects`, `icon`, `sort_order`, `status`, `created_at`) VALUES
(1, 'early-years', 'Early Years', 'Playgroup • Nursery • Prep', 'A warm start built around language, numeracy, curiosity and social development.', '', 'sun', 1, 'published', '2026-08-24 07:49:32'),
(2, 'primary', 'Primary', 'Grades I – V', 'Strong foundations in core subjects, reading, mathematics, science and creative learning.', '', 'book', 2, 'published', '2026-08-24 07:49:32'),
(3, 'middle-school', 'Middle School', 'Grades VI – VIII', 'Subject-focused learning with technology, communication, sports and student leadership.', '', 'mountain', 3, 'published', '2026-08-24 07:49:32'),
(4, 'secondary', 'Secondary', 'Grades IX – X', 'Focused board preparation, practical learning and structured academic support.', '', 'flask', 4, 'published', '2026-08-24 07:49:32');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `skey` varchar(80) NOT NULL,
  `svalue` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `skey`, `svalue`) VALUES
(1, 'site_name', 'Pakistan Cambridge School'),
(2, 'site_name_ur', ''),
(3, 'tagline', 'Empowering tomorrow\'s Leaders through 21st-century skills,robotics and digital schooling in Hafizabad'),
(4, 'phone', '0321 3351753'),
(5, 'email', 'pcsshfd@gmail.com'),
(6, 'address', 'Farooq-e-Azam Road, Street Imam Bargah, Hafizabad, Punjab 52110, Pakistan'),
(7, 'facebook', 'https://www.facebook.com/Pakistancambridgeschool'),
(8, 'youtube', '#'),
(9, 'whatsapp', '0321 3351753'),
(10, 'map_embed', 'https://www.google.com/maps?q=FAROOQ-E-AZAM%20ROAD%20STREET%20IMAM%20BARGAH%20HAFIZABAD%2C%20Hafizabad%2C%20Pakistan%2C%2052110&output=embed'),
(11, 'stat_students', '600+'),
(12, 'stat_faculty', '40+'),
(13, 'stat_pass_rate', '98%'),
(14, 'stat_years', '25+'),
(15, 'admissions_open', '1'),
(16, 'admissions_note', ''),
(17, 'footer_about', ''),
(18, 'vision', 'To be recognised as a leading school in Hafizabad — known for the strength of our academics, the character of our students, and the trust of the families we serve.'),
(19, 'mission', 'To provide every student with a disciplined, caring and academically rigorous environment where they can build the knowledge, character and confidence to succeed — in examinations, and in life beyond them.'),
(20, 'logo_custom', '1'),
(21, 'logo_version', '1787309282'),
(235, 'app_play_store_url', 'https://play.google.com/store/apps/details?id=com.educationportal'),
(251, 'app_app_store_url', '');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(10) UNSIGNED NOT NULL,
  `eyebrow` varchar(120) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(300) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `cta_text` varchar(60) NOT NULL DEFAULT '',
  `cta_link` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `eyebrow`, `title`, `subtitle`, `image`, `cta_text`, `cta_link`, `sort_order`, `status`, `created_at`) VALUES
(1, 'Pakistan Cambridge School • Hafizabad', 'Where Ambition Meets Education.', 'A modern school community in Hafizabad where academic excellence, character, confidence and leadership grow together — from Playgroup to Higher Secondary.', 'sliders/20260825-093022-ff9ae65a.jpg', 'Apply Now', '/admissions', 1, 'published', '2026-08-24 07:49:32'),
(2, 'Academic Programs', 'A Clear Pathway, From First Class To Final Board.', 'Structured programs for every stage — Early Years, Primary, Middle, Secondary and Cambridge Pathways — each built to carry students forward with confidence.', 'sliders/20260825-093038-b789d686.jpg', 'See Our Programs', '/programs', 2, 'published', '2026-08-24 07:49:32'),
(3, 'Beyond The Classroom', 'Strong Minds Need Strong Grounds.', 'Sports, competitions, clubs and campus life that help every student discover — and use — their strengths.', 'sliders/20260826-114114-f7d59105.jpg', 'Explore Campus Life', '/page/facilities', 3, 'published', '2026-08-24 07:49:32');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `role` varchar(120) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','editor') NOT NULL DEFAULT 'editor',
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `last_login_at`, `created_at`) VALUES
(1, 'Site Administrator', 'admin@pcshfd.com', '$2y$10$Dqnf1L40dnIbKK.zKMGGUO5qIk91c4jjaM8albk88RBdmhCLoZFVW', 'superadmin', 'active', '2026-08-27 11:11:48', '2026-08-21 06:05:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_calendar`
--
ALTER TABLE `academic_calendar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academic_framework`
--
ALTER TABLE `academic_framework`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activity_created` (`created_at`),
  ADD KEY `idx_activity_subject` (`subject_type`);

--
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_no` (`app_no`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_blogs_published` (`status`,`published_at`);

--
-- Indexes for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_session_date` (`session`,`starts_on`);

--
-- Indexes for table `class_subjects`
--
ALTER TABLE `class_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_values`
--
ALTER TABLE `core_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dashboard_prefs`
--
ALTER TABLE `dashboard_prefs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_notes`
--
ALTER TABLE `fee_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_panel_sort` (`panel`,`sort_order`);

--
-- Indexes for table `fee_structure`
--
ALTER TABLE `fee_structure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_albums`
--
ALTER TABLE `gallery_albums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gallery_album` (`album_id`);

--
-- Indexes for table `grading_scale`
--
ALTER TABLE `grading_scale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leadership`
--
ALTER TABLE `leadership`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_time` (`email`,`created_at`),
  ADD KEY `idx_ip_time` (`ip_hash`,`created_at`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_menu_parent` (`parent_id`),
  ADD KEY `idx_menu_sort` (`sort_order`),
  ADD KEY `fk_menu_page` (`page_id`),
  ADD KEY `idx_menu_items_menu` (`menu_id`);

--
-- Indexes for table `milestones`
--
ALTER TABLE `milestones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `idx_pages_route` (`route`);

--
-- Indexes for table `page_views`
--
ALTER TABLE `page_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_views_created` (`created_at`),
  ADD KEY `idx_views_path` (`path`),
  ADD KEY `idx_views_visitor` (`visitor_hash`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skey` (`skey`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_calendar`
--
ALTER TABLE `academic_calendar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `academic_framework`
--
ALTER TABLE `academic_framework`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `class_subjects`
--
ALTER TABLE `class_subjects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `core_values`
--
ALTER TABLE `core_values`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dashboard_prefs`
--
ALTER TABLE `dashboard_prefs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `fee_notes`
--
ALTER TABLE `fee_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fee_structure`
--
ALTER TABLE `fee_structure`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gallery_albums`
--
ALTER TABLE `gallery_albums`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `grading_scale`
--
ALTER TABLE `grading_scale`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `leadership`
--
ALTER TABLE `leadership`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `milestones`
--
ALTER TABLE `milestones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `page_views`
--
ALTER TABLE `page_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=592;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=299;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dashboard_prefs`
--
ALTER TABLE `dashboard_prefs`
  ADD CONSTRAINT `fk_prefs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD CONSTRAINT `fk_gallery_album` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_menu_items_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_menu_page` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_menu_parent` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
