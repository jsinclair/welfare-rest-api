-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 03, 2018 at 12:31 PM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `animal_welfare`
--
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
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `animal_treatment`
--

CREATE TABLE `animal_treatment` (
  `animal_id` int(11) NOT NULL,
  `treatment_id` int(11) NOT NULL,
  `note` text NOT NULL
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
  `contact_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `organisation_user`
--

INSERT INTO `organisation_user` (`id`, `organisation_id`, `username`, `password`, `fullname`, `contact_number`) VALUES
(1, 1, 'jsincl4ir@gmail.com', 'password', 'James Sinclair', '12345678');

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE `reminder` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
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
-- Table structure for table `treatment`
--

CREATE TABLE `treatment` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `treatment`
--

INSERT INTO `treatment` (`id`, `description`) VALUES
(1, 'Deworm'),
(2, 'Tick/Flea'),
(3, 'Vaccination');

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
-- Dumping data for table `user_session`
--

INSERT INTO `user_session` (`id`, `user_id`, `token`, `os_version`, `device`, `uuid`, `date_created`, `date_updated`) VALUES
(1, 1, 'Ix614Vk0NwlGt1jMzQ7Y9JYS3IXkN5Np2sFSaA5PqAvBJjv9LlXQGjfzM9ZP20vo240l8aetwrv1SssEEnNVWcvdkjBmAFC6DX7y', '4.0', 'S8', '123456', '2018-07-12 20:31:32', '2018-07-12 20:31:32'),
(2, 1, 'Q1WsQleOjAzmkh95TcxRWtMge0TAakbGoHKCPMTELxN4XpXGQgPXe35tEzNaJdeMZPTCmO0wSXpEZztXvxIvGDg7yTqi9iXjFZRW', '4.0', 'S8', '123456', '2018-07-12 20:32:36', '2018-07-12 20:32:36'),
(3, 1, 'B1k81YfZEHfam952cijF1g2vKALLUy3HmV69DLTmMawyJYCMAPu7j6R63r456Z3kSk1tsk49uLvocV1l2Glc2b4GFhhytFVEvC5p', '4.0', 'S8', '123456', '2018-07-18 21:44:47', '2018-07-18 21:44:47'),
(4, 1, 'k9V80k871PWoobqs4jhNF0s5NLzDQNSN1QU9EoY6hupgdboUJjSCLgV9zZxM9wxEqLk5PMsCBmtDh6EAJifey8Lv99bHQWzzp9Sy', '4.0', 'S8', '123456', '2018-07-18 21:45:19', '2018-07-18 21:45:19'),
(5, 1, 'INT0ndCa6VKQa3Yk8O2Vj2aKPWFFXqdMSQTHSlrbiLBqaP16kvbXrS5FH83ka9MGCxyOQTbFmdWrFgH4rkI1zgTOJgQEbLEI0VY6', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-18 21:59:30', '2018-07-18 21:59:30'),
(6, 1, 'JZWMbPSZW6L3c6qPYMj5Qw7kS344mJH1RNXt0gp556edh3mChGxIBug1VNHyOz6aywgKg9ZkReNrNBhwOUyn5Ppxg4Ud8SERsHug', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-19 20:38:46', '2018-07-19 20:38:46'),
(7, 1, 'uENQsSzFZ9dl6SQreUscpxoqTDZ9b8HsmMb2pywvVnYviN7ozYNls02AOqRJQMY1QA4MP72V1hkMNKDJMgN3dhSflTiXbP3QRLEx', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-19 21:07:58', '2018-07-19 21:07:58'),
(8, 1, '2bBIWOEVcEscNdHrAoR45hz9CzEZEA23jvhH0m6SDy7dmIt90LTnBkZfMY0EdJ74gsDXqfF9s0u7mooQEx4ccwgKHYeCSCrU5oiF', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-19 21:12:05', '2018-07-19 21:12:05'),
(9, 1, 'fWaTD5FTyzxqKm5ChoY9PEjlHXZsPowcJUcMvasao4FeMfmFa9D5lW7ZatbdZ4IXzBuMioof9yZs9ZUvbAN1tnAicyw6kPmSzcan', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-19 21:14:23', '2018-07-19 21:14:23'),
(10, 1, 'jx4SQYSRVOk29P2xKo8kXsboVXc24pQ92zLSMlAT9JnCEK6T4QoKfhKpHy7qzgWrJS97mVh5bLnGXFLjYUAQU124CNbVDN50S2Xn', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-19 21:19:19', '2018-07-19 21:19:19'),
(11, 1, 'p8QtCAP56AXe07WMuM7hvpH2wbgWb50vB3W7F46nroCQsTuzZ8Ze0QiukTnMOwtIgpUMY5MHBhqZLRGZxp6HKUlev08AyzuZfNIj', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-19 21:19:31', '2018-07-19 21:19:31'),
(12, 1, 'HUnSd61eDpk6nyYzkPK5Vl7VvVM2RwWHcSUKCDaaKKkx2JIeFoqviZGOGBP3PLhH5VTT7WdVVAWY6HxWtPpOPoihNkHJy2liMmu8', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-19 21:19:53', '2018-07-19 21:19:53'),
(13, 1, '846mHSkjqehSjfBQw958zDwIGARw732R0TGYXKxKyWqatmSgBQcQANibZkTnrRRynhqL7Qmv7HFblJijnQd9pccONhd0DyjtdIck', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-19 21:51:31', '2018-07-19 21:51:31'),
(14, 1, 'WejbYm4d4q5PIgNUSTSdkNCdKSh1nXfgKAwkzr9rvun5Kh7y9k5DRr0fBZ3FvpwqQYiKgoDlLeAA6tXl2ap4EMa8eejT2KhocoRd', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-19 21:56:49', '2018-07-19 21:56:49'),
(15, 1, 'zLGdqPQGAgai9iREE99e5lzpQlxTEm1GHZ4hNxrgnQxPpFqV3uzKVkS2IWnfc0VkhL0rrIKFKlm3ppfgLJVuhzCuT4LcRgqHUj7x', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-19 22:01:15', '2018-07-19 22:01:15'),
(16, 1, '4VoUDhbmcd8z8x7mKWlVsRMYvR3dZOKGtCyBtLXHGjoAKF1DPl3alML7KymzBhNTC4w7zH7XCDphtHWOPuhlXoDryBuGWFEZJ4bq', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-19 22:04:20', '2018-07-19 22:04:20'),
(17, 1, 'vDfQEzjIbNcCEqKKb9gb6XxG4dC8QmHdV3K4doaRccdZOgsXhyU4lBjidhbyS8qEBcPeM2T1Xitt9BJIBJtRmn255UuA4dHquX9l', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-19 22:05:22', '2018-07-19 22:05:22'),
(18, 1, 'HTbkOz53H12sIO1qxpEyynQDgWtg5d4exGvJ5lcaG37xk4o02IuHq4RYwuIqbhCwdDdA8PzB9lEjgv7Kh0WzwpSpjBsH8eeNYjvk', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-23 17:24:06', '2018-07-23 17:24:06'),
(19, 1, 'gEI67MnNx1TG2GhrOzAzKK3VjX4FluXx4kmNWP7hGcHac9a1pVe3L15Il9bPN8ieUR80reknv1VCS58JXDuskhlkjLMTbD43lRxz', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-23 17:45:28', '2018-07-23 17:45:28'),
(20, 1, 'sJMSS2bKaiMXjjDoIE94ri4Sdz5c9N23d5UWB2b9OePLRzoe9W8oNVY827TLMbkQ3Y83IfmwguI0nTHWI0rwga723zKw5nXTjX83', '4.0', 'S8', '123456', '2018-07-25 14:52:58', '2018-07-25 14:52:58'),
(21, 1, 'qtUmisprrHIgsnFqBLZZSfPoT7Dm26tTOMMeMn49joXfLBXN1mf7xsf1Mt7god4nbTAPHgWCAUGHzyS5f5Svo0YHkMFNyxIgLwlV', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-26 13:32:42', '2018-07-26 13:32:42'),
(22, 1, '7uv8dmV37A8NHXDNn4okQdY6xe3vcF8DgHhfQ83nlZo3exAy09udqNvKdiRvyzpJfXkKsZwUGonRdAThFiFmPplSNBXqNyPj24H6', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-26 13:34:00', '2018-07-26 13:34:00'),
(23, 1, 'lGs2t2f3xA5BtjTqAL5VYPnDG5IiCmlfRv3yzBsCiVxIVjAtW8AI9yhuiBxSIuAonPaxTTlv7YhBbfcRYBHesGrKZbeU593ZaIfh', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-26 13:34:04', '2018-07-26 13:34:04'),
(24, 1, 'PzTSUkZYfALvxLHRoUnCUyzItTzPMQEkhjda22bdlIipUH89cEeJCxf7nB4X5COP3b8biDLLqV4iyD6WZ6rqJUQvo8Vz0LOYiCZx', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-07-26 13:38:39', '2018-07-26 13:38:39'),
(25, 1, 'gEDBQiammN8GzyWp4vCGTEx7NjA9lU6A4NBQ2iQkfajBnP9AFizo9pyPDlENqsopLlIOUcWXzyVw7ynGu8zvXxlh1PIBDfbPTcca', '4.0', 'S8', '123456', '2018-08-30 09:22:45', '2018-08-30 09:22:45'),
(26, 1, '6gLfXmoWyHRL5wp37NZLrLXAcibIircwtE1B42W1gJptFD4bEmyzMErIFyOPGAkdg5qISTnJx58jf7N5wTq1pQSY6WFK8PrHi2Kh', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-08-30 09:47:00', '2018-08-30 09:47:00'),
(27, 1, '2cB7SsnBcsXInRDa83EDAUI0mUYqciUoFFOE6jhNpoRDdVOLLDHj07FrdHlf75mnsU7XMZZGfMZAu4oI5U1iBYXc3EPLGmfMU1M8', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-08-30 10:08:09', '2018-08-30 10:08:09'),
(28, 1, 'ZuIEE1BzHvKo6H8iA7Vf1eVq5bDb3IQgr8fvhhhKnIT5kge7y5rB9y11jhZQ3yZv0LHWQUU2zcDnaOlPo5TXOwXdTAWqcpljfrfG', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-08-30 10:09:27', '2018-08-30 10:09:27'),
(29, 1, 'cQ0ELRmlTS6626V7nO2i9orIaMENACFs6Wj7lVX0yM9KIMHzF0XsMM0KKoRtgInrz6waKklfAneuwbQMe5wejVoBMhxN8WuZymle', '4.0', 'S8', '123456', '2018-08-30 11:12:16', '2018-08-30 11:12:16'),
(30, 1, 'Qo8kkxkeST8MxjbLGcBnhPL25bwOGMHwIqCBxNY9lLzMH4N1pIhmUxNOvTtb2z5uqbtskBVPatMP9lLMRitjry4BHcBIr8Hkmu57', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-08-30 11:48:27', '2018-08-30 11:48:27'),
(31, 1, 'FoyXy3QUa2S7iKGZAOWMvM6ME5BcjRZ5XDAJTWgydQeSwLrOEnHqiVxjVfsqsZw915SJEqdO6aTyNiKWqZ52Gys4LV5t3mP1e5lh', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-08-30 11:51:45', '2018-08-30 11:51:45'),
(32, 1, 'DYspnKlSJdAWhj94LyfkrzTfQlyrUvqukFnsy6Hpp1wvIo5WM5co8OWMENcZAW5uB3CLVTlCYBO20ldTjZvbgr9DZ2EQIxq3s1e1', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-08-30 11:53:14', '2018-08-30 11:53:14'),
(33, 1, '9veTdhs8Xmc8MoY7OWNmC8hFlbFTNBXRyosNwP8kSZVAAFVVFOydbOX5HLxnf1aubzhWRBEfJmnMjH0aijXbdcIAyOUWwJ1mHLNC', '8.0.0', 'samsung SM-G950F', 'b45a3ee7d6a3cd2d', '2018-08-30 11:54:58', '2018-08-30 11:54:58'),
(34, 1, 'PBR9Gm6heXrWbaGRB8gUkfpiOzKABHg2WfzNdtp927uldDNsd3OW1h4bLqW3jrU5m1SYnmXwqYk0ueWruO0xg5Gtd4Xok1vJJmoR', '4.0', 'S8', '123456', '2018-08-31 11:39:26', '2018-08-31 11:39:26');

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
-- Indexes for table `animal_treatment`
--
ALTER TABLE `animal_treatment`
  ADD PRIMARY KEY (`animal_id`,`treatment_id`),
  ADD KEY `animal_treatment_treatment` (`treatment_id`);

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
-- Indexes for table `treatment`
--
ALTER TABLE `treatment`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `treatment`
--
ALTER TABLE `treatment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_session`
--
ALTER TABLE `user_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

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
-- Constraints for table `animal_treatment`
--
ALTER TABLE `animal_treatment`
  ADD CONSTRAINT `animal_treatment_animal` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`),
  ADD CONSTRAINT `animal_treatment_treatment` FOREIGN KEY (`treatment_id`) REFERENCES `treatment` (`id`);

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
-- Constraints for table `user_session`
--
ALTER TABLE `user_session`
  ADD CONSTRAINT `session_user` FOREIGN KEY (`user_id`) REFERENCES `organisation_user` (`id`);
