-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mar 21 Novembre 2017 à 13:35
-- Version du serveur :  5.7.15-log
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `projetphp`
--

-- --------------------------------------------------------

--
-- Structure de la table `jeux`
--

CREATE TABLE `jeux` (
  `jeu` varchar(100) NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  `genre` varchar(100) NOT NULL,
  `studio` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jeux`
--

INSERT INTO `jeux` (`jeu`, `image`, `genre`, `studio`) VALUES
('Limbo', 'limbo.jpg', 'Plateforme', 'Playdead'),
('Overwatch', 'overwatch.png', 'FPS', 'Blizzard'),
('Skyrim', 'skyrim.jpg', 'RPG', 'Bethesda'),
('The Witcher 3', 'witcher.png', 'RPG', 'CD Projekt Red'),
('World of Warcraft', 'wow.png', 'MMORPG', 'Blizzard');

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `comID` int(11) NOT NULL,
  `jeu` varchar(100) NOT NULL,
  `note` smallint(6) NOT NULL,
  `email` varchar(100) NOT NULL,
  `commentaire` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `notes`
--

INSERT INTO `notes` (`comID`, `jeu`, `note`, `email`, `commentaire`) VALUES
(2, 'The Witcher 3', 20, 'monsterslayer@gmail.km', 0x476f747461206c6f766520746869732067616d652e20416c74686f756768207468652073746f72792069732061776b776172646c792072656c6174626c652e2e2e),
(3, 'Limbo', 10, 'julmougenot@gmail.com', 0x4a6520636f7465206d616973206a65206e277920616920706173206a6f75c3a9),
(4, 'Overwatch', 18, 'julmougenot@gmail.com', 0x5355504552),
(5, 'Overwatch', 15, 'killian.kuppens@gmail.com', 0x4a2761696d65206269656e206d616973206a65206e6520737569732070617320617373657a20626f6e20646f6e63206a65207265706f727465206c6120666175746520737572206c6120636f6e63657074696f6e206475206a6575),
(6, 'Overwatch', 4, 'monsterslayer@gmail.km', 0x492063616e27742068616e646c6520746869732067616d652e2e2e20776879206973207468657265206f6e6c79206f6e652073776f72642d7769656c64696e6720636861726163746572203f),
(7, 'Skyrim', 18, 'monsterslayer@gmail.km', 0x546861742022647261676f6e626f726e22206c6f6f6b7320746f7567682e204920776f6e6465722077686174206d75746174696f6e732068652077656e74207468726f756768),
(8, 'Skyrim', 2, 'julmougenot@gmail.com', 0x4a27616920726174c3a9206d6573206578616d656e7320c3a0206361757365206465206365206a65752e203530306820717565206a65206e652072c3a9637570c3a97265726169206a616d616973203a63),
(9, 'World of Warcraft', 14, 'julmougenot@gmail.com', 0x4327657374206269656e2c206d616973206327657374207472c3a873206d6f636865206574207472c3a8732063686572),
(10, 'World of Warcraft', 1, 'monsterslayer@gmail.km', 0x46696768742073797374656d206973207765616b2c206e6f206f696c7320616761696e7374206d6f6e737465727320616e6420746865206772617068696373206172652070617468657469632e20476f747461206c6f766520746865204d6f6f6e6775617264277320476f6c64736869726520496e6e2074686f7567682e2e2e),
(12, 'Skyrim', 15, 'jeancrien@gmail.com', 0x436f6d6d656e74206f6e206661697420706f7572207574696c6973657220756e652067656d6d652073706972697475656c6c65203f);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `email` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `mdp` varchar(100) NOT NULL,
  `activ` smallint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`email`, `prenom`, `nom`, `mdp`, `activ`) VALUES
('admin', 'Game', 'Rating', 'admin', 0),
('jeancrien@gmail.com', 'Jean', 'Saisrien', 'quoi', 0),
('julmougenot@gmail.com', 'Julien', 'Mougenot', 'patapouf', 0),
('killian.kuppens@gmail.com', 'Killian', 'Kuppens', 'gems', 0),
('monsterslayer@gmail.km', 'Geralt', 'of Rivia', 'ciri', 0);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `jeux`
--
ALTER TABLE `jeux`
  ADD PRIMARY KEY (`jeu`),
  ADD UNIQUE KEY `jeu` (`jeu`);

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`comID`),
  ADD UNIQUE KEY `comID` (`comID`),
  ADD KEY `fk_jeu` (`jeu`),
  ADD KEY `fk_email` (`email`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `fk_email` FOREIGN KEY (`email`) REFERENCES `utilisateurs` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jeu` FOREIGN KEY (`jeu`) REFERENCES `jeux` (`jeu`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
