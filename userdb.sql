-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2026 at 08:07 AM
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
-- Database: `userdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `instructor_email` varchar(255) DEFAULT NULL,
  `student_email` varchar(255) DEFAULT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sender` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `instructor_email`, `student_email`, `student_name`, `message`, `sender`, `created_at`) VALUES
(2, 'instructor@gmail.com', 'user@gmail.com', 'User', 'hi sir ', 'student', '2026-02-15 06:11:36'),
(3, 'instructor@gmail.com', 'user@gmail.com', 'Student', 'told what u have doubt', 'instructor', '2026-02-15 06:12:18'),
(4, 'instructor@gmail.com', 'user@gmail.com', 'User', 'nnl;', 'student', '2026-02-16 06:28:52');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `submitted_at`) VALUES
(2, 'user', 'user@gmail.com', 'oiSSJDOLIKJFS\'A;', 'SALK.JIAL,HSO;KSVDS;JPFCCVB', '2026-02-26 15:01:19');

-- --------------------------------------------------------

--
-- Table structure for table `course_enrollments`
--

CREATE TABLE `course_enrollments` (
  `id` int(11) NOT NULL,
  `student_email` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `enroll_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `progress` int(3) DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_enrollments`
--

INSERT INTO `course_enrollments` (`id`, `student_email`, `username`, `user_email`, `course_name`, `enroll_date`, `progress`) VALUES
(1, NULL, 'User', 'user@gmail.com', 'Full Stack Web Development', '2026-02-15 08:21:23', 100),
(2, NULL, 'User', 'user@gmail.com', 'Python Masterclass', '2026-02-16 17:47:24', 100),
(4, NULL, 'User', 'user@gmail.com', 'Java Programming Masterclass', '2026-03-01 05:45:50', 100),
(5, NULL, 'User', 'user@gmail.com', 'AWS Cloud Architect', '2026-03-01 06:14:15', 100),
(6, NULL, 'User', 'user@gmail.com', 'React JS Bootcamp', '2026-03-01 06:19:19', 100);

-- --------------------------------------------------------

--
-- Table structure for table `instructor_applications`
--

CREATE TABLE `instructor_applications` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `highest_degree` varchar(150) DEFAULT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_applications`
--

INSERT INTO `instructor_applications` (`id`, `first_name`, `last_name`, `email`, `phone`, `highest_degree`, `subject`, `address`, `created_at`, `status`) VALUES
(3, 'instructor', 'I', 'instructor@gmail.com', '1234567890', 'Graduate', 'Technology', 'chennai', '2026-02-15 03:10:01', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_courses`
--

CREATE TABLE `instructor_courses` (
  `id` int(11) NOT NULL,
  `instructor_name` varchar(100) NOT NULL,
  `instructor_email` varchar(100) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `video_link` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `content` longtext DEFAULT NULL,
  `course_type` varchar(50) DEFAULT 'video'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_courses`
--

INSERT INTO `instructor_courses` (`id`, `instructor_name`, `instructor_email`, `course_name`, `video_link`, `upload_date`, `content`, `course_type`) VALUES
(3, 'instructor', 'instructor@gmail.com', 'java', 'https://youtu.be/IT2durkDCXM?si=o_BZrBCbzY3NzQIC', '2026-02-15 07:17:47', '', 'youtube'),
(4, 'instructor', 'instructor@gmail.com', 'Html', 'https://youtu.be/fDvVCS17P5A?si=ASaQwtAn9RYSswPz', '2026-02-15 08:28:06', '', 'youtube'),
(6, 'instructor', 'instructor@gmail.com', 'HTML FOR BEGINERS', 'https://youtu.be/G3e-cpL7ofc?si=GOoWsHM47GjLU2eo', '2026-02-27 09:47:59', '', 'youtube'),
(7, 'instructor', 'instructor@gmail.com', 'AWS Cloud Architect', 'https://www.youtube.com/live/5gnoVjpfWxU?si=-7RhvxeAa0wnSufz', '2026-03-01 07:03:02', '', 'youtube');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `profile_pic` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `profile_pic`) VALUES
(1, 'Admin', 'admin@gmail.com', 'admin123', 'admin', 'default.png'),
(5, 'User', 'user@gmail.com', '$2y$10$0htCTFHcocFJSWHudFO.lODtbb7WncShHqBJWPU8FOOkSwbA390Ye', 'user', '1771134349_joker.jpg'),
(6, 'instructor', 'instructor@gmail.com', '$2y$10$YZxV0UZLpOZtk9LZZ78Hu.TVNjzWNe0LzaCWyJfrqlfFi66MAwiV.', 'user', 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_enrollments`
--
ALTER TABLE `course_enrollments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instructor_applications`
--
ALTER TABLE `instructor_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instructor_courses`
--
ALTER TABLE `instructor_courses`
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
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `course_enrollments`
--
ALTER TABLE `course_enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `instructor_applications`
--
ALTER TABLE `instructor_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `instructor_courses`
--
ALTER TABLE `instructor_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
