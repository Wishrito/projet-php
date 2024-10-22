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
DROP DATABASE IF EXISTS `crm_hopital`;
CREATE DATABASE IF NOT EXISTS `crm_hopital` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `crm_hopital`;

-- Listage de la structure de table crm_hopital. consultation
DROP TABLE IF EXISTS `consultation`;
CREATE TABLE IF NOT EXISTS `consultation` (
  `date` date NOT NULL,
  `patient_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `debrief` text,
  PRIMARY KEY (`date`,`patient_id`,`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.consultation : ~0 rows (environ)
DELETE FROM `consultation`;

-- Listage de la structure de table crm_hopital. employee
DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `ID` int NOT NULL,
  `service` int NOT NULL,
  `job` int NOT NULL,
  `hiring_date` date NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_service` (`service`),
  KEY `FK_job` (`job`),
  CONSTRAINT `FK_job` FOREIGN KEY (`job`) REFERENCES `job` (`ID`),
  CONSTRAINT `FK_service` FOREIGN KEY (`service`) REFERENCES `service` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='héritage de la table users, destiné aux employés';

-- Listage des données de la table crm_hopital.employee : ~0 rows (environ)
DELETE FROM `employee`;

-- Listage de la structure de table crm_hopital. job
DROP TABLE IF EXISTS `job`;
CREATE TABLE IF NOT EXISTS `job` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `libelle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.job : ~0 rows (environ)
DELETE FROM `job`;

-- Listage de la structure de table crm_hopital. message
DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `content` text,
  `file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `FK_message_users` FOREIGN KEY (`ID`) REFERENCES `users` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='stocke les infos sur les messages envoyés, comprenant la timestamp de chaque';

-- Listage des données de la table crm_hopital.message : ~0 rows (environ)
DELETE FROM `message`;

-- Listage de la structure de table crm_hopital. patient
DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `ID` int NOT NULL,
  `floor_lvl` int DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `background` text,
  PRIMARY KEY (`ID`),
  CONSTRAINT `FK_heritage` FOREIGN KEY (`ID`) REFERENCES `users` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='héritage de la table users, destiné aux patients';

-- Listage des données de la table crm_hopital.patient : ~0 rows (environ)
DELETE FROM `patient`;

-- Listage de la structure de table crm_hopital. service
DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `libelle` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.service : ~0 rows (environ)
DELETE FROM `service`;

-- Listage de la structure de table crm_hopital. users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `login` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `first_name` int DEFAULT NULL,
  `last_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `birth_date` date DEFAULT NULL,
  `leaving_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='contient les informations sur les utilisateurs';

-- Listage des données de la table crm_hopital.users : ~0 rows (environ)
DELETE FROM `users`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
