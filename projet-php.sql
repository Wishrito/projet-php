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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.consultation : ~10 rows (environ)
INSERT INTO `consultation` (`ID`, `debrief`, `patient_id`, `medical_staff_id`, `service_id`, `date`) VALUES
	(25, 'Patient présente des douleurs thoraciques légères. ECG effectué, RAS. Suivi dans 1 semaine.', 8, 3, 7, '2025-05-13 08:00:00'),
	(26, 'Suivi post-opératoire de l’appendicectomie. Cicatrisation normale. Pas de fièvre.', 9, 4, 3, '2025-05-13 09:30:00'),
	(27, 'Examen gynécologique de routine. Aucun problème détecté. Prochain contrôle dans 1 an.', 9, 7, 10, '2025-05-13 12:00:00'),
	(28, 'Consultation diabète : glycémie bien contrôlée, adapter légèrement l’alimentation.', 10, 6, 1, '2025-05-13 13:45:00'),
	(29, 'Douleurs lombaires persistantes. Prescription IRM lombaire.', 9, 5, 6, '2025-05-12 07:15:00'),
	(30, 'Fièvre persistante depuis 3 jours. Suspicion infection virale.', 8, 3, 2, '2025-05-11 08:30:00'),
	(31, 'Plaie au bras gauche, sutures posées. Contrôle dans 5 jours.', 8, 4, 5, '2025-05-10 14:00:00'),
	(32, 'Contrôle tension artérielle. Résultats satisfaisants.', 9, 6, 1, '2025-05-09 06:00:00'),
	(33, 'Patient très fatigué, bilan sanguin prescrit.', 10, 7, 4, '2025-05-08 09:45:00'),
	(34, 'Contrôle de grossesse. Tout est normal.', 7, 7, 10, '2025-05-07 11:00:00');

-- Listage de la structure de table crm_hopital. job
DROP TABLE IF EXISTS `job`;
CREATE TABLE IF NOT EXISTS `job` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.job : ~77 rows (environ)
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
DROP TABLE IF EXISTS `medical_record`;
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.medical_record : ~10 rows (environ)
INSERT INTO `medical_record` (`id`, `record_date`, `diagnosis`, `doctor_id`, `patient_id`, `notes`) VALUES
	(12, '2025-05-13 08:00:00', 'Suspicion angor d’effort exclue. ECG normal.', 3, 8, 'Repos conseillé, pas de traitement pour l’instant.'),
	(13, '2025-05-13 09:30:00', 'Suivi post-opératoire. Cicatrisation OK.', 4, 9, 'Surveillance température + pansement.'),
	(14, '2025-05-13 12:00:00', 'Bilan gynécologique normal.', 7, 11, 'Aucun antécédent préoccupant, suivi annuel.'),
	(15, '2025-05-13 13:45:00', 'Diabète de type 2 stabilisé.', 6, 10, 'Suivi trimestriel recommandé.'),
	(16, '2025-05-12 07:15:00', 'Lombalgie chronique.', 5, 7, 'IRM prescrite, antalgiques à adapter.'),
	(17, '2025-05-11 08:30:00', 'Infection virale probable.', 3, 8, 'Repos, hydratation, antipyrétiques.'),
	(18, '2025-05-10 14:00:00', 'Plaie traumatique.', 4, 11, 'Sutures posées, pansements tous les 2 jours.'),
	(19, '2025-05-09 06:00:00', 'HTA contrôlée.', 6, 7, 'Surveillance mensuelle recommandée.'),
	(20, '2025-05-08 09:45:00', 'Fatigue chronique à explorer.', 7, 11, 'Bilan biologique en cours.'),
	(21, '2025-05-07 11:00:00', 'Suivi de grossesse normal.', 7, 7, 'Suivi mensuel jusqu’au 3e trimestre.');

-- Listage de la structure de table crm_hopital. medical_staff
DROP TABLE IF EXISTS `medical_staff`;
CREATE TABLE IF NOT EXISTS `medical_staff` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_password_edit` timestamp NULL DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `first_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `birth_date` date DEFAULT NULL,
  `leaving_date` timestamp NULL DEFAULT NULL,
  `hiring_date` date DEFAULT NULL,
  `service` int DEFAULT '0',
  `job` int DEFAULT '0',
  `profile_pic` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '../src/img/profile_pics/default.png',
  `is_suspended` int DEFAULT '0',
  `first_connection` tinyint DEFAULT '1',
  PRIMARY KEY (`ID`) USING BTREE,
  KEY `fk_staff_service` (`service`),
  KEY `fk_staff_job` (`job`),
  CONSTRAINT `fk_staff_job` FOREIGN KEY (`job`) REFERENCES `job` (`ID`),
  CONSTRAINT `fk_staff_service` FOREIGN KEY (`service`) REFERENCES `service` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.medical_staff : ~7 rows (environ)
INSERT INTO `medical_staff` (`ID`, `email`, `password`, `last_password_edit`, `username`, `first_name`, `last_name`, `birth_date`, `leaving_date`, `hiring_date`, `service`, `job`, `profile_pic`, `is_suspended`, `first_connection`) VALUES
	(1, 'admin@gmail.com', '$2y$10$bSCNkA6g9EMxxkHB8gDbVOII0IX0oEniRmWOUm2FDITL3EMSjljPi', NULL, 'admin', 'Admin', 'Admin', '2003-06-01', NULL, NULL, 0, 75, '../src/img/profile_pics/Staff_4.png', 0, 0),
	(2, 'systeme@santeplus.com', '$2y$10$L7Vs9KkiV5z71q.bamI3AuRARVvE/9kLIQGEaVyDqsgJx80/Owj72', NULL, 'ssystème', 'santéplus', 'système', '2025-05-04', NULL, NULL, 0, 0, '../src/img/logo_medical.png', 0, 0),
	(3, 'julien.hocde@exmeple.com', '$2y$10$g73uc4EwtN0zAMAWF8YCUOZHczCZ/H6gVnI8/PS.A6EpZhMVbemnW', NULL, 'jhocdé', 'Julien', 'Hocdé', '1980-06-12', NULL, '2025-05-13', 7, 40, '../src/img/profile_pics/default.png', 0, 0),
	(4, 'marie.moreau@exemple.com', '$2y$10$vo2IZg3rCO55Lo.FjWhi/.JRAYp8o0a2VP8Bp.GH5tXDhyHInxIKe', NULL, 'mmoreau', 'Marie', 'Moreau', '1990-09-03', NULL, '2025-05-13', 3, 34, '../src/img/profile_pics/default.png', 0, 0),
	(5, 'youssef.khan@exemple.com', '$2y$10$yEI11rYmoD887RjThSPaxO8VJfxAlAWEzBD55Y5BzO.x.gHjDX0ua', NULL, 'ykhan', 'Youssef', 'Khan', '1998-12-18', NULL, '2025-05-13', 5, 16, '../src/img/profile_pics/Staff_3.png', 0, 0),
	(6, 'camille.bernard@exemple.com', '$2y$10$w1PECGGze79ViPoNBmm84.YsTm5opNXLJGh9PQ4j2oDJww0wdl4BK', NULL, 'cbernard', 'Camille', 'Bernard', '1975-04-30', NULL, '2025-05-13', 1, 51, '../src/img/profile_pics/default.png', 0, 0),
	(7, 'anais.fernandez@exemple.com', '$2y$10$j8G.2q5mhhnurtAIFYu.8eYaUOMa0ZN5x6nnSg6ofBTPOuH34QlDa', NULL, 'afernandez', 'Anaïs', 'Fernandez', '1994-08-09', NULL, '2025-05-13', 10, 57, '../src/img/profile_pics/default.png', 0, 0);

-- Listage de la structure de table crm_hopital. message
DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sender_id` int NOT NULL,
  `sender_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `receiver_id` int NOT NULL DEFAULT '0',
  `receiver_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.message : ~10 rows (environ)
INSERT INTO `message` (`ID`, `content`, `date`, `sender_id`, `sender_type`, `receiver_id`, `receiver_type`) VALUES
	(45, 'Bonjour M. Dupont, votre prochain rendez-vous est confirmé pour demain à 10h.', '2025-05-14 07:00:00', 3, 'medical_staff', 8, 'patient'),
	(46, 'Merci docteur, à demain.', '2025-05-14 07:05:00', 8, 'patient', 3, 'medical_staff'),
	(47, 'Peux-tu m’envoyer les résultats de Mme Durand ?', '2025-05-14 07:10:00', 4, 'medical_staff', 6, 'medical_staff'),
	(48, 'Rendez-vous pris avec le cardiologue pour le 16/05.', '2025-05-14 08:00:00', 5, 'medical_staff', 11, 'patient'),
	(49, 'Bonjour, j’ai eu des vertiges ce matin, dois-je m’inquiéter ?', '2025-05-13 06:30:00', 9, 'patient', 4, 'medical_staff'),
	(50, 'Merci pour l’ordonnance envoyée hier.', '2025-05-12 15:00:00', 12, 'patient', 3, 'medical_staff'),
	(51, 'Je suis absent vendredi, peux-tu assurer les urgences ?', '2025-05-11 16:45:00', 6, 'medical_staff', 7, 'medical_staff'),
	(52, 'Patient en salle d’attente depuis 20 minutes.', '2025-05-10 07:15:00', 7, 'medical_staff', 5, 'medical_staff'),
	(53, 'N’oubliez pas votre prise de sang prévue jeudi.', '2025-05-09 08:00:00', 3, 'medical_staff', 14, 'patient'),
	(54, 'Je confirme la réception de vos examens, tout est en ordre.', '2025-05-08 12:20:00', 4, 'medical_staff', 10, 'patient');

-- Listage de la structure de table crm_hopital. patient
DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `email` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username` varchar(60) DEFAULT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `birth_date` date DEFAULT NULL,
  `admission_date` timestamp NULL DEFAULT NULL,
  `leaving_date` timestamp NULL DEFAULT NULL,
  `floor_lvl` int DEFAULT '0',
  `profile_pic` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '../src/img/profile_pics/default.png',
  `last_password_edit` timestamp NULL DEFAULT NULL,
  `is_suspended` tinyint(1) DEFAULT '0',
  `first_connection` tinyint DEFAULT '1',
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.patient : ~5 rows (environ)
INSERT INTO `patient` (`ID`, `email`, `password`, `username`, `first_name`, `last_name`, `birth_date`, `admission_date`, `leaving_date`, `floor_lvl`, `profile_pic`, `last_password_edit`, `is_suspended`, `first_connection`) VALUES
	(7, 'lucie.roth@exemple.com', '$2y$10$/A.qxpEdOTt49LTSYNJ8c.g4OqxsbxYAhM/0B9ps5ARCsuSuNUMMa', 'lroth', 'Lucie', 'Roth', '1992-04-15', '2025-05-13 08:58:44', NULL, 0, '../src/img/profile_pics/default.png', NULL, 1, 0),
	(8, 'thomas.dupont@exemple.com', '$2y$10$zThYtzIMAuwkULcTT3oEke47yPMgWJse2bI6HJ9OBAmqGTxzduSRi', 'tdupont', 'Thomas', 'Dupont', '1985-07-20', '2025-05-13 08:59:52', NULL, 0, '../src/img/profile_pics/default.png', NULL, 0, 0),
	(9, 'lea.durand@exemple.com', '$2y$10$/TamWIPOUBTmArDHo4Gy3.uJbzKfP5/eavYAdsanpPsppFkHTwIoC', 'ldurand', 'Léa', 'Durand', '2000-10-01', '2025-05-13 09:00:48', NULL, 0, '../src/img/profile_pics/default.png', NULL, 0, 0),
	(10, 'antoine.roux@exemple.com', '$2y$10$qyONviljZRbxpDVDhbKTm.VLuGyAnHU4Iw6h6BoGvkqEIqve7k.QS', 'aroux', 'Antoine', 'Roux', '1978-11-23', '2025-05-13 09:01:25', NULL, 0, '../src/img/profile_pics/default.png', NULL, 0, 0),
	(11, 'emma.leroy@exemple.com', '$2y$10$ZbPYznUhyx8PZnTqsxlPFOhA1b60Qo20mB2rk69iXl.mw/ZkL.fhu', 'eleroy', 'Emma', 'Leroy', '1996-03-05', '2025-05-13 09:02:36', NULL, 0, '../src/img/profile_pics/default.png', NULL, 0, 0);

-- Listage de la structure de table crm_hopital. service
DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table crm_hopital.service : ~21 rows (environ)
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
