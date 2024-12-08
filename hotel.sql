-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 08 déc. 2024 à 00:54
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
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `num_tel` varchar(15) NOT NULL,
  `adresse_mail` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id_reservation` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_voyageurs` int(11) NOT NULL,
  `date_reservation` date NOT NULL,
  `date_debut_sejour` date NOT NULL,
  `date_fin_sejour` date NOT NULL,
  `options` varchar(150) DEFAULT NULL,
  `prix_total` float NOT NULL,
  `id_client` int(11) NOT NULL,
  PRIMARY KEY (`id_reservation`),
  FOREIGN KEY (`id_client`) REFERENCES `client`(`id_client`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `annulation`
--

CREATE TABLE `annulation` (
  `id_annulation` int(11) NOT NULL AUTO_INCREMENT,
  `id_reservation` int(11) NOT NULL,
  PRIMARY KEY (`id_annulation`),
  FOREIGN KEY (`id_reservation`) REFERENCES `reservation`(`id_reservation`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chambre`
--

CREATE TABLE `chambre` (
  `id_chambre` int(11) NOT NULL AUTO_INCREMENT,
  `type_chambre` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `capacite_max` int(11) NOT NULL,
  `prix_par_nuit` float NOT NULL,
  `url_image` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_chambre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chambre`
--

INSERT INTO `chambre` (`id_chambre`, `type_chambre`, `description`, `capacite_max`, `prix_par_nuit`, `url_image`) VALUES
(1, 'Simple', 'Chambre simple avec un lit simple et une salle de bain privée.', 1, 60, 'images/chambre_simple.webp'),
(2, 'Simple', 'Chambre simple avec un lit simple et une salle de bain privée.', 1, 60, 'images/chambre_simple.webp'),
(3, 'Double', 'Chambre double avec un lit double, une salle de bain privée et une vue sur le jardin.', 2, 80, 'images/chambre_double.jpg'),
(4, 'Double', 'Chambre double avec un lit double, une salle de bain privée et une vue sur le jardin.', 2, 80, 'images/chambre_double.jpg'),
(5, 'Twin', 'Chambre twin avec deux lits simples et une salle de bain privée.', 2, 85, 'images/chambre_twins.jpg'),
(6, 'Twin', 'Chambre twin avec deux lits simples et une salle de bain privée.', 2, 85, 'images/chambre_twins.jpg'),
(7, 'Famille', 'Chambre familiale avec un lit double, deux lits simples et une salle de bain privée.', 4, 130, 'images/chambre_famille.jpg'),
(8, 'Famille', 'Chambre familiale avec un lit double, deux lits simples et une salle de bain privée.', 4, 130, 'images/chambre_famille.jpg'),
(9, 'Deluxe', 'Chambre deluxe avec un lit double king-size, une salle de bain privée un balcon privé.', 2, 110, 'images/chambre_deluxe.jpg'),
(10, 'Deluxe', 'Chambre deluxe avec un lit double king-size, une salle de bain privée un balcon privé.', 2, 110, 'images/chambre_deluxe.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `disponibilite`
--

CREATE TABLE `disponibilite` (
  `id_disponibilite` int(11) NOT NULL AUTO_INCREMENT,
  `id_chambre` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  PRIMARY KEY (`id_disponibilite`),
  FOREIGN KEY (`id_chambre`) REFERENCES `chambre`(`id_chambre`) ON DELETE CASCADE,
  FOREIGN KEY (`id_reservation`) REFERENCES `reservation`(`id_reservation`) ON DELETE CASCADE
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
  PRIMARY KEY (`id_paiement`),
  FOREIGN KEY (`id_reservation`) REFERENCES `reservation`(`id_reservation`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `nom`, `prenom`, `num_tel`, `adresse_mail`, `mot_de_passe`) VALUES
(0, 'Admin', '', '', 'admin@admin.com', '$2y$10$eX888Tjjp712Z95aOgOjU.gkdzyTvleHjCKf0EQYjp8lZUa9ITzsm'),
(6, 'CHAABI', 'Ziyad', '0614313035', 'ziyadou2011@hotmail.fr', '$2y$10$gyao3JQKYs4zhFwiMlRSoO1MfyFJgbJ8X5LGFcFYGnCkYvUOaFcTu');

-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `annulation`
--
ALTER TABLE `annulation`
  MODIFY `id_annulation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `chambre`
--
ALTER TABLE `chambre`
  MODIFY `id_chambre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `disponibilite`
--
ALTER TABLE `disponibilite`
  MODIFY `id_disponibilite` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id_paiement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
