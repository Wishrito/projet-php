-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour crm_hopital
CREATE DATABASE IF NOT EXISTS `crm_hopital` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `crm_hopital`;

-- Listage de la structure de table crm_hopital. consultation
CREATE TABLE IF NOT EXISTS `consultation` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `debrief` text NOT NULL,
  `patient_id` int NOT NULL,
  `medical_staff_id` int NOT NULL,
  `service_id` int DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_consultation_service` (`service_id`),
  KEY `FK_consultation_patient` (`patient_id`),
  KEY `FK_consultation_staff` (`medical_staff_id`),
  CONSTRAINT `FK_consultation_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`ID`),
  CONSTRAINT `FK_consultation_service` FOREIGN KEY (`service_id`) REFERENCES `service` (`ID`),
  CONSTRAINT `FK_consultation_staff` FOREIGN KEY (`medical_staff_id`) REFERENCES `medical_staff` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.consultation : ~3 rows (environ)
DELETE FROM `consultation`;

-- Listage de la structure de table crm_hopital. job
CREATE TABLE IF NOT EXISTS `job` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.job : ~77 rows (environ)
DELETE FROM `job`;
INSERT INTO `job` (`ID`, `libelle`) VALUES
	(0, 'Non Renseigné'),
	(1, 'représentant Santé Publique France'),
	(2, 'agent de Blanchisserie'),
	(3, 'agent de Stérilisation'),
	(4, 'agent des pompes funèbres'),
	(5, 'ASH'),
	(6, 'aide Médico-Psychologique'),
	(7, 'aide-soignant'),
	(8, 'ambulancier'),
	(9, 'analyste des données médicales'),
	(10, 'assistant dentaire'),
	(11, 'assistant des hôpitaux'),
	(12, 'assistant médical'),
	(13, 'assistant social'),
	(14, 'aumônière'),
	(15, 'auxiliaire de puériculture'),
	(16, 'biologiste médical'),
	(17, 'brancardier'),
	(18, 'chauffeur de collecte EFS'),
	(19, 'chef de caisse de Sécurité sociale'),
	(20, 'chef de clinique des Universités'),
	(21, 'chirurgien dentiste'),
	(22, 'clinicien (Loi HPST)'),
	(23, 'cuisinier'),
	(24, 'déontologue'),
	(25, 'diététicien'),
	(26, 'directeur ARS'),
	(27, 'directeur d’hôpital'),
	(28, 'dosimétriste'),
	(29, 'éducateur spécialisé \r\n'),
	(30, 'ergothérapeute'),
	(31, 'étudiant en PASS\r\n'),
	(32, 'étudiant interne\r\n'),
	(33, 'infirmier IDEC / IDER – EHPAD'),
	(34, 'infirmier'),
	(35, 'informaticien '),
	(36, 'animateur'),
	(37, 'manipulateur d’électroradiologie médicale\r\n'),
	(38, 'masseur kinésithérapeute\r\n'),
	(39, 'MCF-PH & PR-PH ou MCU-PH & PU-PH\r\n'),
	(40, 'médecin'),
	(41, 'médecin territorial\r\n'),
	(42, 'médiateur du Service public'),
	(43, 'opticien'),
	(44, 'orthophoniste'),
	(45, 'orthoptiste'),
	(46, 'ostéopathe'),
	(47, 'pédicure podologue\r\n'),
	(48, 'pharmacien'),
	(49, 'physicien médical\r\n'),
	(50, 'praticien attaché\r\n'),
	(51, 'praticien hospitalier'),
	(52, 'prothésiste'),
	(53, 'psychologue'),
	(54, 'psychomotricien'),
	(55, 'répartiteur'),
	(56, 'responsable budgétaire et financier'),
	(57, 'sage-femme / maïeuticien'),
	(58, 'secrétaire médical'),
	(59, 'conseiller juridique'),
	(60, 'socio-esthéticien'),
	(61, 'technicien de laboratoire\r\n'),
	(62, 'thanatopracteur'),
	(63, 'hypnothérapeute'),
	(64, 'agent CARSAT\r\n'),
	(65, 'agent thermal\r\n'),
	(66, 'attaché d’administration Hospitalière'),
	(67, 'ingénieur de la sécurité sanitaire\r\n'),
	(68, 'directeur des soins\r\n'),
	(69, 'chargé de la valorisation et de la recherche'),
	(70, 'ingénieur hospitalier\r\n'),
	(71, 'inspecteur de l’action sanitaire & sociale\r\n'),
	(72, 'visiteur.e médicale\r\n'),
	(73, 'directeur d’ESSMS\r\n'),
	(74, 'agent de service médico-social\r\n'),
	(75, 'admistrateur du site'),
	(76, 'système');

-- Listage de la structure de table crm_hopital. medical_record
CREATE TABLE IF NOT EXISTS `medical_record` (
  `id` int NOT NULL AUTO_INCREMENT,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diagnosis` text,
  `doctor_id` int DEFAULT NULL,
  `patient_id` int DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `FK_record_patient` (`patient_id`),
  KEY `FK_record_staff` (`doctor_id`),
  CONSTRAINT `FK_record_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`ID`),
  CONSTRAINT `FK_record_staff` FOREIGN KEY (`doctor_id`) REFERENCES `medical_staff` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.medical_record : ~0 rows (environ)
DELETE FROM `medical_record`;

-- Listage de la structure de table crm_hopital. medical_staff
CREATE TABLE IF NOT EXISTS `medical_staff` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `first_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `birth_date` date DEFAULT NULL,
  `leaving_date` timestamp NULL DEFAULT NULL,
  `hiring_date` date DEFAULT (curdate()),
  `service` int DEFAULT '0',
  `job` int DEFAULT '0',
  `profile_pic` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '../src/img/profile_pics/default.png',
  PRIMARY KEY (`ID`) USING BTREE,
  KEY `fk_staff_service` (`service`),
  KEY `fk_staff_job` (`job`),
  CONSTRAINT `fk_staff_job` FOREIGN KEY (`job`) REFERENCES `job` (`ID`),
  CONSTRAINT `fk_staff_service` FOREIGN KEY (`service`) REFERENCES `service` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.medical_staff : ~5 rows (environ)
DELETE FROM `medical_staff`;
INSERT INTO `medical_staff` (`ID`, `email`, `password`, `username`, `first_name`, `last_name`, `birth_date`, `leaving_date`, `hiring_date`, `service`, `job`, `profile_pic`) VALUES
	(1, 'admin@gmail.com', '$2y$10$bSCNkA6g9EMxxkHB8gDbVOII0IX0oEniRmWOUm2FDITL3EMSjljPi', 'admin', '', '', '2003-06-01', NULL, NULL, 0, 75, '../src/img/profile_pics/default.png'),
	(2, 'systeme@santeplus.com', '$2y$10$TkGI0uYo1j0nVflBt0vSceB1l0cw.in.8LENCebGUfeQx4dirH9A.', 'ssystème', 'santéplus', 'système', '2025-05-04', NULL, NULL, 0, 0, '../src/img/profile_pics/default.png'),
	(3, 'julien.hocde@exmeple.com', '$2y$10$kJUd6OCRW6P2r5xPvjn/7eFbBW5zfGSlzmA3iv83xjbBwbsFTIx9W', 'jhocdé', 'Julien', 'Hocdé', '1980-06-12', NULL, '2025-05-13', 7, 40, '../src/img/profile_pics/default.png'),
	(4, 'marie.moreau@exemple.com', '$2y$10$VCbYUt854OM7O95KcNrQ2.zmtoYJI2Q3iCV4mDNIfsQb3q0nzqH66', 'mmoreau', 'Marie', 'Moreau', '1990-09-03', NULL, '2025-05-13', 3, 34, '../src/img/profile_pics/default.png'),
	(5, 'youssef.khan@exemple.com', '$2y$10$OwalFCvT5p27T4O1mgTuceA2KW10gkHyviWNuodS9efKGzVFOZX1y', 'ykhan', 'Youssef', 'Khan', '1998-12-18', NULL, '2025-05-13', 5, 16, '../src/img/profile_pics/default.png'),
	(6, 'camille.bernard@exemple.com', '$2y$10$XmwoeOV/fJrKjPMbeh4hWOqEdZ.KfdJ8sbP2WXoadZS8TVy3mkBo6', 'cbernard', 'Camille', 'Bernard', '1975-04-30', NULL, '2025-05-13', 1, 51, '../src/img/profile_pics/default.png'),
	(7, 'anais.fernandez@exemple.com', '$2y$10$j8Z1FBZmFhVam1FaDtuGa.uvGAL7qV3cnUmf2q0agzDLfXs.SwCju', 'afernandez', 'Anaïs', 'Fernandez', '1994-08-09', NULL, '2025-05-13', 10, 57, '../src/img/profile_pics/default.png');

-- Listage de la structure de table crm_hopital. message
CREATE TABLE IF NOT EXISTS `message` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sender_id` int NOT NULL,
  `sender_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `receiver_id` int NOT NULL DEFAULT '0',
  `receiver_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.message : ~0 rows (environ)
DELETE FROM `message`;
INSERT INTO `message` (`ID`, `content`, `date`, `sender_id`, `sender_type`, `receiver_id`, `receiver_type`) VALUES
	(2, 'test', '2025-05-13 08:22:55', 1, 'medical_staff', 9, 'patient'),
	(4, 'oskour\r\n', '2025-05-13 08:33:51', 1, 'medical_staff', 8, 'medical_staff'),
	(5, 'ahaha', '2025-05-13 08:36:45', 1, 'medical_staff', 8, 'medical_staff'),
	(6, 'Bonjour', '2025-05-13 09:11:47', 5, 'medical_staff', 11, 'patient');

-- Listage de la structure de table crm_hopital. patient
CREATE TABLE IF NOT EXISTS `patient` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `email` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username` varchar(60) DEFAULT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `birth_date` date DEFAULT NULL,
  `admission_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `leaving_date` timestamp NULL DEFAULT NULL,
  `profile_pic` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '../src/img/profile_pics/default.png',
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.patient : ~1 rows (environ)
DELETE FROM `patient`;
INSERT INTO `patient` (`ID`, `email`, `password`, `username`, `first_name`, `last_name`, `birth_date`, `admission_date`, `leaving_date`, `profile_pic`) VALUES
	(7, 'lucie.roth@exemple.com', '$2y$10$nfqoRnYa47JQDr6457z5TOkBI2F/Ouzwj3I6fxRxF3J8eP8juEO1u', 'lroth', 'Lucie', 'Roth', '1992-04-15', '2025-05-13 08:58:44', NULL, '../src/img/profile_pics/default.png'),
	(8, 'thomas.dupont@exemple.com', '$2y$10$SuMY95UBKYo7Lg2MG96/l.RKPtZ3FbwdOJ5zKQ2wSdR3rkvcCpwXu', 'tdupont', 'Thomas', 'Dupont', '1985-07-20', '2025-05-13 08:59:52', NULL, '../src/img/profile_pics/default.png'),
	(9, 'lea.durand@exemple.com', '$2y$10$M32w84wiQvnpDZNljaxGN.1zZWhO1X4eTaEcjNjN0DO0y4LpoTX3S', 'ldurand', 'Léa', 'Durand', '2000-10-01', '2025-05-13 09:00:48', NULL, '../src/img/profile_pics/default.png'),
	(10, 'antoine.roux@exemple.com', '$2y$10$7atiQGbHyg7QbiONIUQWA.sWXyhN.DypRWYfFqI1hmM1.774u/DW6', 'aroux', 'Antoine', 'Roux', '1978-11-23', '2025-05-13 09:01:25', NULL, '../src/img/profile_pics/default.png'),
	(11, 'emma.leroy@exemple.com', '$2y$10$eY/dGxljEV1aKydTFdSbFuOcKwoFbLaOYxXdkM9f3WMB/A/WvaMPu', 'eleroy', 'Emma', 'Leroy', '1996-03-05', '2025-05-13 09:02:36', NULL, '../src/img/profile_pics/default.png');

-- Listage de la structure de table crm_hopital. service
CREATE TABLE IF NOT EXISTS `service` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.service : ~21 rows (environ)
DELETE FROM `service`;
INSERT INTO `service` (`ID`, `libelle`) VALUES
	(0, 'Non Renseigné'),
	(1, 'médecine générale'),
	(2, 'immunologie'),
	(3, 'radiologie'),
	(4, 'chirurgie'),
	(5, 'neurologie'),
	(6, 'pneumologie'),
	(7, 'cardiologie'),
	(8, 'odontologie'),
	(9, 'dermatologie'),
	(10, 'traitement des urgences\r\n'),
	(11, 'traumatologie'),
	(12, 'médecine interne\r\n'),
	(13, 'endocrinologie'),
	(14, 'anatomo-pathologie\r\n'),
	(15, 'hématologie'),
	(16, 'gastro-entérologie\r\n'),
	(17, 'urologie'),
	(18, 'pharmacie'),
	(19, 'maternité'),
	(20, 'pédiatrie');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
