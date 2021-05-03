-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : lun. 03 mai 2021 à 21:23
-- Version du serveur :  5.7.32
-- Version de PHP : 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données : `grammaireturque`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210502062808', '2021-05-02 06:28:21', 41),
('DoctrineMigrations\\Version20210502070012', '2021-05-02 07:00:18', 57),
('DoctrineMigrations\\Version20210502070440', '2021-05-02 07:05:02', 72),
('DoctrineMigrations\\Version20210502071439', '2021-05-02 07:14:44', 41),
('DoctrineMigrations\\Version20210502123600', '2021-05-02 12:37:44', 46),
('DoctrineMigrations\\Version20210502123611', '2021-05-02 17:04:23', 41),
('DoctrineMigrations\\Version20210502165738', '2021-05-02 17:04:23', 7),
('DoctrineMigrations\\Version20210502165842', '2021-05-02 17:09:48', 76),
('DoctrineMigrations\\Version20210502170924', '2021-05-02 17:09:48', 32),
('DoctrineMigrations\\Version20210502171141', '2021-05-02 17:11:46', 82),
('DoctrineMigrations\\Version20210502181054', '2021-05-02 18:10:58', 47),
('DoctrineMigrations\\Version20210502183237', '2021-05-02 18:32:45', 91);

-- --------------------------------------------------------

--
-- Structure de la table `joueurs`
--

CREATE TABLE `joueurs` (
  `id` int(11) NOT NULL,
  `partie_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mots`
--

CREATE TABLE `mots` (
  `id` int(11) NOT NULL,
  `turc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `francais` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adjectif` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mots`
--

INSERT INTO `mots` (`id`, `turc`, `francais`, `adjectif`) VALUES
(1, 'evde', 'à la maison', 0),
(2, 'hasta', 'malade', 1),
(3, 'ibadet salonunda', 'à la salle', 0),
(4, 'müsait', 'disponible', 1),
(5, 'mutlu', 'heureux', 0),
(6, 'yorgun', 'fatigué', 1),
(7, 'tatilde', 'en vacances', 0),
(8, 'davetli', 'invité', 1),
(9, 'işte', 'au travail', 0),
(10, 'burada', 'là', 0);

-- --------------------------------------------------------

--
-- Structure de la table `partie`
--

CREATE TABLE `partie` (
  `id` int(11) NOT NULL,
  `nbrjoueurs` int(11) NOT NULL,
  `date` date NOT NULL,
  `passe` tinyint(1) NOT NULL,
  `present` tinyint(1) NOT NULL,
  `futur` tinyint(1) NOT NULL,
  `je` tinyint(1) NOT NULL,
  `tu` tinyint(1) NOT NULL,
  `il` tinyint(1) NOT NULL,
  `nous` tinyint(1) NOT NULL,
  `vous` tinyint(1) NOT NULL,
  `ils` tinyint(1) NOT NULL,
  `affirmation` tinyint(1) NOT NULL,
  `question` tinyint(1) NOT NULL,
  `negation` tinyint(1) NOT NULL,
  `tour` int(11) NOT NULL,
  `ordre` longtext COLLATE utf8mb4_unicode_ci COMMENT '(DC2Type:array)',
  `cycletour` int(11) NOT NULL,
  `lastmot` int(11) DEFAULT NULL,
  `lasttemps` int(11) DEFAULT NULL,
  `lasttype` int(11) DEFAULT NULL,
  `lastpersonne` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `joueurs`
--
ALTER TABLE `joueurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F0FD889DE075F7A4` (`partie_id`);

--
-- Index pour la table `mots`
--
ALTER TABLE `mots`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `partie`
--
ALTER TABLE `partie`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `joueurs`
--
ALTER TABLE `joueurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `mots`
--
ALTER TABLE `mots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `partie`
--
ALTER TABLE `partie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `joueurs`
--
ALTER TABLE `joueurs`
  ADD CONSTRAINT `FK_F0FD889DE075F7A4` FOREIGN KEY (`partie_id`) REFERENCES `partie` (`id`);