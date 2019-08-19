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

USE `animal_welfare`;

-- Dumping data for table animal_welfare.animal_type: ~3 rows (approximately)
DELETE FROM `animal_type`;
/*!40000 ALTER TABLE `animal_type` DISABLE KEYS */;
INSERT INTO `animal_type` (`id`, `description`) VALUES
	(1, 'Dog'),
	(2, 'Cat'),
	(3, 'Donkey'),
	(4, 'Pig');
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
INSERT INTO `residence` (`id`, `shack_id`, `street_address`, `latitude`, `longitude`, `notes`, `resident_name`, `tel_no`, `id_no`) VALUES
	(1, '1', 'Unavailable', -34.158124, 18.984279, 'Test 1', 'Test Name 1', '0987654321', '0987654321098'),
	(2, '55', '', -34.157421, 18.982413, 'Test 2', 'Test Name 2', '0987654321', '0987654321098'),
	(3, NULL, '44 Mariners Way, 20 Rendezvous Place', -34.152647, 18.876405, 'Us', 'James Sinclair', '0723868876', '8712175199084'),
	(4, NULL, '32 St Georges Street, Somerset West', -34.080463, 18.854298, 'Mom', 'Penny Sinclair', '0823313227', '0987654321098'),
	(5, '56', '1 test street', 55.5555, 55.5555, 'test notes', 'Test Name 3', '0987654321', '0987654321098');
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
INSERT INTO `animal` (`id`, `animal_type_id`, `residence_id`, `name`, `approximate_dob`, `notes`, `treatments`, `gender`, `sterilised`) VALUES
	(1, 1, 1, 'Scruffy', '2016-02-17', 'Test 1', 'None', 'MALE', 1),
	(2, 2, 2, 'Katjie', '2012-02-15', 'Test Cat', 'Tick and Flea', 'FEMALE', 0),
	(3, 1, 3, 'Toffee', '2013-04-15', 'Best Dog', 'Many', 'MALE', 1),
	(4, 1, 3, 'Nikki', '2013-04-15', 'Bird Slayer', 'Same as Toffee', 'FEMALE', 1),
	(5, 1, 3, 'Ouma', '2003-11-09', 'Old as time', 'Not enough', 'FEMALE', 1),
	(6, 1, 4, 'Misha', '2005-05-20', 'Long in the tooth', 'Tick and Flea', 'FEMALE', 1),
	(7, 3, 1, 'Katie', '2019-02-20', 'Post Note', 'All the treatments', 'FEMALE', 1);
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

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
