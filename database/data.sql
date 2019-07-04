-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.37-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping data for table animal_welfare.animal_type: ~3 rows (approximately)
DELETE FROM `animal_type`;
/*!40000 ALTER TABLE `animal_type` DISABLE KEYS */;
INSERT INTO `animal_type` (`id`, `description`) VALUES
	(1, 'Dog'),
	(2, 'Cat'),
	(3, 'Donkey');
/*!40000 ALTER TABLE `animal_type` ENABLE KEYS */;

-- Dumping data for table animal_welfare.organisation: ~0 rows (approximately)
DELETE FROM `organisation`;
/*!40000 ALTER TABLE `organisation` DISABLE KEYS */;
INSERT INTO `organisation` (`id`, `name`) VALUES
	(1, 'Base Organisation');
/*!40000 ALTER TABLE `organisation` ENABLE KEYS */;

-- Dumping data for table animal_welfare.organisation_user: ~0 rows (approximately)
DELETE FROM `organisation_user`;
/*!40000 ALTER TABLE `organisation_user` DISABLE KEYS */;
INSERT INTO `organisation_user` (`id`, `organisation_id`, `username`, `password`, `fullname`, `contact_number`, `firebase_token`) VALUES
	(1, 1, 'jsincl4ir@gmail.com', 'password', 'James Sinclair', '12345678', 'fI6_uLK5uvE:APA91bH_qY0abwHAVR2h5vEabyE1IN9m0dOfvV6gUtW3y2E5XULyD9IvdOsoJwzGmgKFQyKkvs1uYm-gVlxn-mFSGH2hqosZGLN-5wMBrs67RcDBDUoncETVN_kkFKaCZ202iikKFkgb');
/*!40000 ALTER TABLE `organisation_user` ENABLE KEYS */;

-- Dumping data for table animal_welfare.permission: ~0 rows (approximately)
DELETE FROM `permission`;
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` (`id`, `permission_key`, `description`) VALUES
	(1, 'write', 'The user is allowed to edit animal and residence information.');
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;

-- Dumping data for table animal_welfare.residence: ~5 rows (approximately)
DELETE FROM `residence`;
/*!40000 ALTER TABLE `residence` DISABLE KEYS */;
INSERT INTO `residence` (`id`, `shack_id`, `street_address`, `latitude`, `longitude`, `notes`) VALUES
	(1, '1', 'Unavailable', -34.158124, 18.984279, 'Test 1'),
	(2, '55', '', -34.157421, 18.982413, 'Test 2'),
	(3, NULL, '44 Mariners Way, 20 Rendezvous Place', -34.152647, 18.876405, 'Us'),
	(4, NULL, '32 St Georges Street, Somerset West', -34.080463, 18.854298, 'Mom'),
	(5, '56', '1 test street', 55.5555, 55.5555, 'test notes');
/*!40000 ALTER TABLE `residence` ENABLE KEYS */;

-- Dumping data for table animal_welfare.user_permission: ~0 rows (approximately)
DELETE FROM `user_permission`;
/*!40000 ALTER TABLE `user_permission` DISABLE KEYS */;
INSERT INTO `user_permission` (`user_id`, `permission_id`, `date_added`) VALUES
	(1, 1, '2018-09-09 12:48:42');
/*!40000 ALTER TABLE `user_permission` ENABLE KEYS */;

-- Dumping data for table animal_welfare.animal: ~7 rows (approximately)
DELETE FROM `animal`;
/*!40000 ALTER TABLE `animal` DISABLE KEYS */;
INSERT INTO `animal` (`id`, `animal_type_id`, `residence_id`, `name`, `approximate_dob`, `notes`, `welfare_number`, `treatments`) VALUES
	(1, 1, 1, 'Scruffy', '2016-02-17', 'Test 1', '1', 'None'),
	(2, 2, 2, 'Katjie', '2012-02-15', 'Test Cat', '2', 'Tick and Flea'),
	(3, 1, 3, 'Toffee', '2013-04-15', 'Best Dog', '3', 'Many'),
	(4, 1, 3, 'Nikki', '2013-04-15', 'Bird Slayer', '4', 'Same as Toffee'),
	(5, 1, 3, 'Ouma', '2003-11-09', 'Old as time', '5', 'Not enough'),
	(6, 1, 4, 'Misha', '2005-05-20', 'Long in the tooth', '6', 'Tick and Flea'),
	(7, 3, 1, 'Katie', '2019-02-20', 'Post Note', 'Wel1', 'All the treatments');
/*!40000 ALTER TABLE `animal` ENABLE KEYS */;

-- Dumping data for table animal_welfare.reminder: ~3 rows (approximately)
DELETE FROM `reminder`;
/*!40000 ALTER TABLE `reminder` DISABLE KEYS */;
INSERT INTO `reminder` (`id`, `animal_id`, `user_id`, `reason`, `disabled`, `date`) VALUES
	(1, 3, 1, 'To give Beeno', b'0', '2019-02-16'),
	(2, 4, 1, 'Also to give Beenos', b'0', '2019-02-16'),
	(3, 1, 1, 'Post Test', b'0', '2019-02-20'),
	(4, 1, 1, 'Post Test', b'0', '2019-02-17');
/*!40000 ALTER TABLE `reminder` ENABLE KEYS */;

-- Dumping data for table animal_welfare.user_session: ~38 rows (approximately)
DELETE FROM `user_session`;
/*!40000 ALTER TABLE `user_session` DISABLE KEYS */;
INSERT INTO `user_session` (`id`, `user_id`, `token`, `os_version`, `device`, `uuid`, `date_created`, `date_updated`) VALUES
	(81, 1, 'XKqElgd0SqbT19WswQrCWcMuKDwXmyAgwasvZqVrqZVB6UCqXcLf64Kra31XY6PSCmkoGck9Sn0z8jmhMn2Ig3bbzkAIUN0miHAa', '4.0', 'S8', '123456', '2019-02-09 16:24:59', '2019-02-09 16:24:59'),
	(82, 1, 'k1mootIinOvgbta3TZk0AQfCiSo53GRP1nzVzZk7XlIt8K0NfWFRKr1NeYLdM0LYwKj8a9V1ALTNzSa5DRsdoLoQxPBTzwtIDnza', '4.0', 'S8', '123456', '2019-02-09 16:25:54', '2019-02-09 16:25:54'),
	(83, 1, 'kTkXzE8A5RwZC3tGFpI0npUcyDJnpEVPmv6RtGQHIWfEsfEwT5IsLX5z7kGQ89EUUFnYLKfsIWTc9J2ApUZygiR4b02GCiPZ8Io7', '4.0', 'S8', '123456', '2019-02-09 16:26:01', '2019-02-09 16:26:01'),
	(84, 1, 'w4tYoYC9IDU8rlzEWIicMi5wiKCufHyfQuDfbRrjHNxJgvMknz0gm81FAd2JTnOESvgTxh1HykLR7234QDIYTXaa837albTKk7CU', '4.0', 'S8', '123456', '2019-02-09 16:34:49', '2019-02-09 16:34:49'),
	(85, 1, 'qwhRoX9RLEKyaJP3aP3E5ORYgKMruMe2wic8d4jsV4Rrkj6WRrCTrwdHC7PToL1YKqiOGW3E5j1wrB57wnIMMRc4JmyMKdqH51eV', '4.0', 'S8', '123456', '2019-02-09 16:35:21', '2019-02-09 16:35:21'),
	(86, 1, 'wFrFUwLUNl5vXmC3GCp5hLRnpBFZIxYz6Ty16yFMf0emb1c73u0GjCeY99WSan1vBwJlXvcfdh0f8Lh6qgXYHEtIJfuxZy7D5E1S', '4.0', 'S8', '123456', '2019-02-09 16:36:00', '2019-02-09 16:36:00'),
	(87, 1, 'dEcOb8Ou8N4afBARdensgHA7uUhyBsOsEP2nLnUPJJwBW4HW06RbvI354DNYkakAywjuLKY81i8hqlHd1DnD3Xek9ZIOx7ENjm7C', '4.0', 'S8', '123456', '2019-02-09 16:37:14', '2019-02-09 16:37:14'),
	(88, 1, 'RLWWaWCkHe0LHO51UFIz1sbZIVR3dP5MqaJojTXaBp7Ni499fqzh3SqJ9VUYWI1W9KemBovY5bgQPEkKcacWdxPOKeKVFTmh8jFE', '4.0', 'S8', '123456', '2019-02-09 16:40:59', '2019-02-09 16:40:59'),
	(89, 1, '3vC8kMBF4EpiZFt0UhXG0RFK5OL93IXvZVlA2ee7rdkECd24uaWoGVb63gx5SPf1z8wQVB7AWWAvZqYcg7fsimRJy35GDp19IvgC', '4.0', 'S8', '123456', '2019-02-09 16:41:18', '2019-02-09 16:41:18'),
	(90, 1, 'jXVtuDjEukbJGSb2JscYlmtojHBvjD92N5PUVk2nAsNh6b5bcwSon3m6smzw3rshqAQ42FvKdD9ayO1XWpA3YB4UvcyvzUqiLXak', '4.0', 'S8', '123456', '2019-02-09 16:43:23', '2019-02-09 16:43:23'),
	(91, 1, 'SzCsj9RcFyJ2de74bVIEwVzMP7fTwUAN5DC9LZfjLadKaq15xmyIXi44HZ3Y0MPem3obf9yGqg7kXkkOWqOJB4XrRV0LdgdF1uqk', '4.0', 'S8', '123456', '2019-02-09 16:43:36', '2019-02-09 16:43:36'),
	(92, 1, 'NHUCQk1Ip5nNIbQ8z6UnPvwgc81Cq4ktJrNP9s10EYIxVUUlsKsjCQvCkQzTppMixcCQd08lESLijS6ji7QqnF8uo3CvUJwjsc8l', '4.0', 'S8', '123456', '2019-02-09 16:44:57', '2019-02-09 16:44:57'),
	(93, 1, 'Hf5eUAeHb0QsC0opSY313tdNQQ3nxL4AhMV83AfoHvmevaRGTHafzA67ycMEdymkWyJFr4rnNoflMGuT0DhnjzcUX8LQqS7vHlar', '4.0', 'S8', '123456', '2019-02-09 16:45:31', '2019-02-09 16:45:31'),
	(94, 1, 'Yd9qtJ59aRUKLR4Vhk5NQU2JrNFIHwGK2zG64p3mFOoDEFcM90uvOvBFEtpE9Pjm1FGb9x6LXJNp514w5xY8ozWFpjfmcH0dlcnt', '4.0', 'S8', '123456', '2019-02-09 16:45:36', '2019-02-09 16:45:36'),
	(95, 1, '8CrjQY3OgR0Om0qNozOAgreZk9Esn1rYFamzpT5COvK5VnpfuZ968Os4uRSmYBRDvkY6kvfNIqvxS5Axz6ixFKlKdHNUtqKqQvlf', '4.0', 'S8', '123456', '2019-02-09 16:45:37', '2019-02-09 16:45:37'),
	(96, 1, '4SmAWp1tgP3FKFKJufkyygLT16kiqhNFgESjuDFI2kguoCtqw4s3mvk2N6hC782Q2BSj84ZcuHaQakwatnk8Pw7jQuZfZM2KwliZ', '4.0', 'S8', '123456', '2019-02-09 16:45:38', '2019-02-09 16:45:38'),
	(97, 1, 'c23cPd91cnSPuosbtXX4c8Az7p3k6j0yUWBHN3rAo61eN3LI6vTs9dshtUy6zbMbpgy6To9HqfumPS1JA448xm38ylBmGJVQQkj0', '4.0', 'S8', '123456', '2019-02-09 16:45:39', '2019-02-09 16:45:39'),
	(98, 1, 'FB5kJSYizt5FNDMGTuC0Il6czTsaxWV8DfVdA6lhuZy3TkV6Idyc33Slf2O2UZgKorquWAf4JITiGeWkwcebf5J3sy7j9aGkUYM8', '4.0', 'S8', '123456', '2019-02-09 16:45:39', '2019-02-09 16:45:39'),
	(99, 1, 'JFkGiP0BvoD3Ou65YGfgaxcNEx8ndwKFf7qy74K22fmE0XNJK9FIlDqumR8lM5S1ukQA1Qy9zQOljL6z38nGUBESqDTPBRUeMKSW', '4.0', 'S8', '123456', '2019-02-09 16:45:40', '2019-02-09 16:45:40'),
	(100, 1, 'FkQS9eMIRtctLYgsF1mx32dBPTW4wvi5Qo7TmfYqYxdKzfRX85f2RASEfP3oGTGBYX0alBAuXEbMqFnrEo4Xqhj5Yf5oI30SrNWB', '4.0', 'S8', '123456', '2019-02-09 16:45:40', '2019-02-09 16:45:40'),
	(101, 1, 'EbuWV9jupi4OXBHwikcCt1MiOAeeuGS2kHBxQwZCXHsv6niLQP2diXNuFU589pTji9HMbciLWzBElXUF0qGtDWR3d3ALGiQcnxci', '4.0', 'S8', '123456', '2019-02-09 16:45:41', '2019-02-09 16:45:41'),
	(102, 1, 'THoNC2p8kvBY6Vpasm2BhuV6sblHJ9zb6dSRoIYiZq0maL8NZwqAk5aROzt2UBrd84pnLdJg8J2LTLZASmgXlXjmA6EWmbO3PzWG', '4.0', 'S8', '123456', '2019-02-09 16:45:41', '2019-02-09 16:45:41'),
	(103, 1, 'xarK4PPAO09Nls9ZYaZqIKHbadPYBb6TvfnbW4BwlswBhz5QebGGT2WfRXZTEIoY3qDjrcl3EdqhKRgxXjZExNpGZPWNdLHf37n3', '4.0', 'S8', '123456', '2019-02-09 16:45:42', '2019-02-09 16:45:42'),
	(104, 1, 'PunqMIwuJYVC6b13blQmpdc6vYS5BR819QjFslB0yxpk3LCIJjlcn8JYf1wXXJ1b7mcAv8CcmScMrHLeLCmXn8RZxhxKtdYt3Ut7', '4.0', 'S8', '123456', '2019-02-09 16:45:42', '2019-02-09 16:45:42'),
	(105, 1, 'Vs9U3bz5RXLNvTdsW9rt74wnRgB1gPQfbEmAT5LGyFOePFxDdamSOEn2EW756IeZDyWAuWrX1wPYPNDiHAcEZzu6dfDCS1DiM0Yf', '4.0', 'S8', '123456', '2019-02-09 16:47:47', '2019-02-09 16:47:47'),
	(106, 1, 'eBLBM46Yw1o2ZBlkUfWR1LKFVQb6aRXQUunFuNXgilCpKxkv4Pbf1oqXfJM2hA0pzeVmbueo8vz1mWR2DkfJampI5WjyYROFCI9z', '4.0', 'S8', '123456', '2019-02-09 16:48:05', '2019-02-09 16:48:05'),
	(107, 1, 'Nt7oOQSN3A77mcrQBfeBL9pYpvvfarnF8G2pJtFLk2FspOW3HyeV12TVGOzMwrO9HFtlUyiwd8R5oUnds1CNBy8RXBt9RtsYGRgO', '4.0', 'S8', '123456', '2019-02-09 16:49:37', '2019-02-09 16:49:37'),
	(108, 1, 'JY3lYpBuYcTahPdQNyGzVFaNmOd3qGjlwJNtjAQvzvNL38BEXA7SWBFEpji2wYIo6U6vG3chQduLSz72at7y6hDoJYKzYbtOCw3w', '8.0.0', 'samsung SM-G950F', '4e6ca849db0a7831', '2019-02-09 20:09:11', '2019-02-09 20:09:11'),
	(109, 1, 'i9jjDAKHM1PR849Hu1sEaUSB8352bhyfwpO3kSmlFVU3TIVnfbnvq7bbkkEA2TRtrmiidBI44iK1BWSxVSQhfx8rvVEL7r0We5GX', '8.0.0', 'samsung SM-G950F', '4e6ca849db0a7831', '2019-02-09 20:16:49', '2019-02-09 20:16:49'),
	(110, 1, '41MXkhUjun6kPSf4mvLfY6a1n2WICinoKLMQ9z3dK7YahSZPmVyxjFz3nuTPTEoecqwfbqZRZAxQXLssiILPDIDqq93VsSdc8hhw', '8.0.0', 'samsung SM-G950F', '4e6ca849db0a7831', '2019-02-09 20:20:13', '2019-02-09 20:20:13'),
	(111, 1, 'S9oE4qMF9GovysxJy0kTp95K3jclLh2Uh9DnmxB1raGMAiYonlUONNYnmRb03awi9JvCdtPPSsbO0yVRqczEauoChsuA08JiHFCY', '8.0.0', 'samsung SM-G950F', '4e6ca849db0a7831', '2019-02-09 20:26:22', '2019-02-09 20:26:22'),
	(112, 1, '8lqDPlroLYM8cqHI6yzGr1yUYNKUBI4P364UjDI8tXyaodIDWdZ47urkPCElXumayVvK6qsY5GQNhAvp3pvFEur2J0WZ3oxQbNZR', '8.0.0', 'samsung SM-G950F', '4e6ca849db0a7831', '2019-02-09 20:27:21', '2019-02-09 20:27:21'),
	(113, 1, 'k9pFBdLotKWVi0Wh4Q5MuwDrvoGEOezJalGW6MUx82wjEA7XNO1lcbiIikJJA9rjz76E3euzC64yzgn7uIQanGSYyEO8fn6OGwmK', '8.0.0', 'samsung SM-G950F', '4e6ca849db0a7831', '2019-02-09 20:27:24', '2019-02-09 20:27:24'),
	(114, 1, 'Vdov9jqH6FMG6gRJZrlkrqW19ofRFpVN8Ch3tJHDSlTmsKvPhgQhs76BrXuwGMaWlqIoxfhkT1xqmr6NAWwnvuNAWoMiQVQYHDyg', '8.0.0', 'samsung SM-G950F', '4e6ca849db0a7831', '2019-02-09 20:27:26', '2019-02-09 20:27:26'),
	(115, 1, '7YMFRQ56ONkaZJARe0Zw87KPBMW1FdKj80oCl1JIOpM5WU1XHuL1s2271u9uX11jXdSVRdCEcbQGZplDzi4VdChyAugl8ZeKEIXy', '4.0', 'S8', '123456', '2019-02-15 11:26:44', '2019-02-15 11:26:44'),
	(116, 1, 'GOaWtbGEnCYQI7LNO9QHWhQVC53z7DLvap5p358mlEz4aRAmhBp8r7RaToDLPMBlaX21L3jIcQmwuzskOO8OceK6bmvBNUXk3U9J', '4.0', 'S8', '123456', '2019-02-15 11:26:48', '2019-02-15 16:45:37'),
	(117, 1, 'NQjQ3qJuPVmlQZ6rwa5LdAFHzQKzekdgLAp2true5rWI0zrXdX5i975TuGljn3sf4cc9rvxZFsmfU0LbkrKDGeW8TtSVKTWLb8qJ', '4.0', 'S8', '123456', '2019-02-17 13:27:56', '2019-02-17 13:34:40'),
	(118, 1, 'QQVxMenD3MEld4VjCVnlOqfsk9zjl38hrAugf1KOrD4kQgraz9snkOlViDzhkPW8lsxKfMGRLp3RAtqRMfhEfRdnPPOuiQhCvfdu', '4.0', 'S8', '123456', '2019-02-17 21:37:33', '2019-02-17 22:33:34');
/*!40000 ALTER TABLE `user_session` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
