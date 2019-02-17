-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 10, 2018 at 07:28 AM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `animal_welfare`
--
DROP DATABASE `animal_welfare`;
CREATE DATABASE IF NOT EXISTS `animal_welfare` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `animal_welfare`;

-- --------------------------------------------------------

--
-- Table structure for table `animal`
--

CREATE TABLE `animal` (
  `id` int(11) NOT NULL,
  `animal_type_id` int(11) NOT NULL,
  `residence_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `approximate_dob` date DEFAULT NULL COMMENT 'Set when the users sends the approximate age through.',
  `notes` text NOT NULL,
  `welfare_number` varchar(15) DEFAULT NULL,
  `treatments` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `animal_type`
--

CREATE TABLE `animal_type` (
  `id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `animal_type`
--

INSERT INTO `animal_type` (`id`, `description`) VALUES
(1, 'Dog'),
(2, 'Cat'),
(3, 'Donkey');

-- --------------------------------------------------------

--
-- Table structure for table `organisation`
--

CREATE TABLE `organisation` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `organisation`
--

INSERT INTO `organisation` (`id`, `name`) VALUES
(1, 'Base Organisation');

-- --------------------------------------------------------

--
-- Table structure for table `organisation_user`
--

CREATE TABLE `organisation_user` (
  `id` int(11) NOT NULL,
  `organisation_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `firebase_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `organisation_user`
--

INSERT INTO `organisation_user` (`id`, `organisation_id`, `username`, `password`, `fullname`, `contact_number`, `firebase_token`) VALUES
(1, 1, 'jsincl4ir@gmail.com', 'password', 'James Sinclair', '12345678', 'c7K5mwR7eU8:APA91bEo060-5u9UhgrYhRPYBkhZb7tnHPQpgaspzabibzPNvmMt_yZw9EBn4rTRs9s16UIlzEE6cN0-O_M2OJgl4l5G_Wu-fdcY635WJ_2fM2U1Ch0D2oYTkRhFLX2YjbSvPOTd-SGL');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `permission_key` varchar(10) NOT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `permission_key`, `description`) VALUES
(1, 'write', 'The user is allowed to edit animal and residence information.');

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE `reminder` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
	`disabled` BIT(1) NOT NULL DEFAULT b'0',
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `residence`
--

CREATE TABLE `residence` (
  `id` int(11) NOT NULL,
  `shack_id` varchar(100) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_permission`
--

CREATE TABLE `user_permission` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_permission`
--

INSERT INTO `user_permission` (`user_id`, `permission_id`, `date_added`) VALUES
(1, 1, '2018-09-09 12:48:42');

-- --------------------------------------------------------

--
-- Table structure for table `user_session`
--

CREATE TABLE `user_session` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `os_version` varchar(50) NOT NULL,
  `device` varchar(100) NOT NULL,
  `uuid` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `animal_name_index` (`name`),
  ADD KEY `animal_residence` (`residence_id`),
  ADD KEY `animal_animal_type` (`animal_type_id`);

--
-- Indexes for table `animal_type`
--
ALTER TABLE `animal_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisation`
--
ALTER TABLE `organisation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisation_user`
--
ALTER TABLE `organisation_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_index` (`username`),
  ADD KEY `user_organisation` (`organisation_id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_key` (`permission_key`);

--
-- Indexes for table `reminder`
--
ALTER TABLE `reminder`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reminder_animal` (`animal_id`),
  ADD KEY `reminder_user` (`user_id`);

--
-- Indexes for table `residence`
--
ALTER TABLE `residence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shack_id_index` (`shack_id`),
  ADD KEY `street_address_index` (`street_address`);

--
-- Indexes for table `user_permission`
--
ALTER TABLE `user_permission`
  ADD PRIMARY KEY (`user_id`,`permission_id`),
  ADD KEY `user_permission_permission` (`permission_id`);

--
-- Indexes for table `user_session`
--
ALTER TABLE `user_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animal`
--
ALTER TABLE `animal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `animal_type`
--
ALTER TABLE `animal_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `organisation`
--
ALTER TABLE `organisation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `organisation_user`
--
ALTER TABLE `organisation_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reminder`
--
ALTER TABLE `reminder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `residence`
--
ALTER TABLE `residence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_session`
--
ALTER TABLE `user_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `animal`
--
ALTER TABLE `animal`
  ADD CONSTRAINT `animal_animal_type` FOREIGN KEY (`animal_type_id`) REFERENCES `animal_type` (`id`),
  ADD CONSTRAINT `animal_residence` FOREIGN KEY (`residence_id`) REFERENCES `residence` (`id`);

--
-- Constraints for table `organisation_user`
--
ALTER TABLE `organisation_user`
  ADD CONSTRAINT `user_organisation` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`);

--
-- Constraints for table `reminder`
--
ALTER TABLE `reminder`
  ADD CONSTRAINT `reminder_animal` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`),
  ADD CONSTRAINT `reminder_user` FOREIGN KEY (`user_id`) REFERENCES `organisation_user` (`id`);

--
-- Constraints for table `user_permission`
--
ALTER TABLE `user_permission`
  ADD CONSTRAINT `user_permission_permission` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`),
  ADD CONSTRAINT `user_permission_user` FOREIGN KEY (`user_id`) REFERENCES `organisation_user` (`id`);

--
-- Constraints for table `user_session`
--
ALTER TABLE `user_session`
  ADD CONSTRAINT `session_user` FOREIGN KEY (`user_id`) REFERENCES `organisation_user` (`id`);
