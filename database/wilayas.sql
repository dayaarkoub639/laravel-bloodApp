-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 05 fév. 2025 à 10:44
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `api_app_blood`
--

-- --------------------------------------------------------

--
-- Structure de la table `wilayas`
--

CREATE TABLE `wilayas` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Nom de la wilaya en arabe',
  `name_ascii` varchar(255) NOT NULL COMMENT 'Nom de la wilaya en caractères ASCII (français)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `wilayas`
--

INSERT INTO `wilayas` (`id`, `name`, `name_ascii`) VALUES
(1, 'أدرار', 'Adrar'),
(2, 'الشلف', 'Chlef'),
(3, 'الأغواط', 'Laghouat'),
(4, 'أم البواقي', 'Oum El Bouaghi'),
(5, 'باتنة', 'Batna'),
(6, 'بجاية', 'Bejaia'),
(7, 'بسكرة', 'Biskra'),
(8, 'بشار', 'Bechar'),
(9, 'البليدة', 'Blida'),
(10, 'البويرة', 'Bouira'),
(11, 'تامنغست', 'Tamanrasset'),
(12, 'تبسة', 'Tebessa'),
(13, 'تلمسان', 'Tlemcen'),
(14, 'تيارت', 'Tiaret'),
(15, 'تيزي وزو', 'Tizi Ouzou'),
(16, 'الجزائر', 'Alger'),
(17, 'الجلفة', 'Djelfa'),
(18, 'جيجل', 'Jijel'),
(19, 'سطيف', 'Setif'),
(20, 'سعيدة', 'Saida'),
(21, 'سكيكدة', 'Skikda'),
(22, 'سيدي بلعباس', 'Sidi Bel Abbes'),
(23, 'عنابة', 'Annaba'),
(24, 'قالمة', 'Guelma'),
(25, 'قسنطينة', 'Constantine'),
(26, 'المدية', 'Medea'),
(27, 'مستغانم', 'Mostaganem'),
(28, 'المسيلة', 'M\'Sila'),
(29, 'معسكر', 'Mascara'),
(30, 'ورقلة', 'Ouargla'),
(31, 'وهران', 'Oran'),
(32, 'البيض', 'El Bayadh'),
(33, 'إليزي', 'Illizi'),
(34, 'برج بوعريريج', 'Bordj Bou Arreridj'),
(35, 'بومرداس', 'Boumerdes'),
(36, 'الطارف', 'El Tarf'),
(37, 'تندوف', 'Tindouf'),
(38, 'تيسمسيلت', 'Tissemsilt'),
(39, 'الوادي', 'El Oued'),
(40, 'خنشلة', 'Khenchela'),
(41, 'سوق أهراس', 'Souk Ahras'),
(42, 'تيبازة', 'Tipaza'),
(43, 'ميلة', 'Mila'),
(44, 'عين الدفلى', 'Ain Defla'),
(45, 'النعامة', 'Naama'),
(46, 'عين تموشنت', 'Ain Temouchent'),
(47, 'غرداية', 'Ghardaia'),
(48, 'غليزان', 'Relizane'),
(49, 'تميمون', 'Timimoun'),
(50, 'برج باجي مختار', 'Bordj Badji Mokhtar'),
(51, 'أولاد جلال', 'Ouled Djellal'),
(52, 'بني عباس', 'Beni Abbes'),
(53, 'عين صالح', 'Ain Salah'),
(54, 'عين قزام', 'Ain Guezzam'),
(55, 'تقرت', 'Touggourt'),
(56, 'جانت', 'Djanet'),
(57, 'المغير', 'El M\'Ghair'),
(58, 'المنيعة', 'El Menia');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `wilayas`
--
ALTER TABLE `wilayas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `wilayas`
--
ALTER TABLE `wilayas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
