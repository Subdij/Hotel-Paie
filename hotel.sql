-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 23 nov. 2024 à 23:18
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `hotel`
--

-- --------------------------------------------------------

--
-- Structure de la table `annulation`
--

CREATE TABLE `annulation` (
  `id_annulation` int(11) NOT NULL AUTO_INCREMENT,
  `id_reservation` int(11) NOT NULL,
  PRIMARY KEY (`id_annulation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chambre`
--

CREATE TABLE `chambre` (
  `id_chambre` int(11) NOT NULL AUTO_INCREMENT,
  `type_chambre` varchar(100) NOT NULL,
  `prix_par_nuit` float NOT NULL,
  PRIMARY KEY (`id_chambre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(100) NOT NULL,
  `Prenom` varchar(100) NOT NULL,
  `num_tél` varchar(15) NOT NULL,
  `adresse_mail` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `disponibilité`
--

CREATE TABLE `disponibilité` (
  `id_disponibilité` int(11) NOT NULL AUTO_INCREMENT,
  `id_chambre` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  PRIMARY KEY (`id_disponibilité`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `id_paiement` int(11) NOT NULL AUTO_INCREMENT,
  `mode_paiement` varchar(100) NOT NULL,
  `statut_paiement` varchar(100) NOT NULL,
  `date_paiement` date DEFAULT NULL,
  `prix_total` float NOT NULL,
  `id_reservation` int(11) NOT NULL,
  PRIMARY KEY (`id_paiement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `réservation`
--

CREATE TABLE `réservation` (
  `id_reservation` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_voyageurs` int(11) NOT NULL,
  `date_reservation` date NOT NULL,
  `date_debut_sejour` date NOT NULL,
  `date_fin_sejour` date NOT NULL,
  `options` varchar(150) DEFAULT NULL,
  `prix_total` float NOT NULL,
  `id_client` int(11) NOT NULL,
  PRIMARY KEY (`id_reservation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `annulation`
--
ALTER TABLE `annulation`
  ADD CONSTRAINT `annulation_reservation_fk` FOREIGN KEY (`id_reservation`) REFERENCES `réservation` (`id_reservation`);

--
-- Index pour la table `disponibilité`
--
ALTER TABLE `disponibilité`
  ADD CONSTRAINT `disponibilite_chambre_fk` FOREIGN KEY (`id_chambre`) REFERENCES `chambre` (`id_chambre`),
  ADD CONSTRAINT `disponibilite_reservation_fk` FOREIGN KEY (`id_reservation`) REFERENCES `réservation` (`id_reservation`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `paiement_reservation_fk` FOREIGN KEY (`id_reservation`) REFERENCES `réservation` (`id_reservation`);

--
-- Index pour la table `réservation`
--
ALTER TABLE `réservation`
  ADD CONSTRAINT `reservation_client_fk` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
