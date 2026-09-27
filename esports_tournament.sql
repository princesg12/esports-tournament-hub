-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2026 at 01:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esports_tournament`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`admin_id`, `username`, `password_hash`) VALUES
(1, 'admin', 'admin123'),
(2, 'prince', 'prince123');

-- --------------------------------------------------------

--
-- Table structure for table `match_scores`
--

CREATE TABLE `match_scores` (
  `score_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `match_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `match_scores`
--

INSERT INTO `match_scores` (`score_id`, `player_id`, `score`, `match_date`) VALUES
(1, 1, 1500, '2026-09-27 11:03:53'),
(2, 1, 333, '2026-09-27 11:05:39');

-- --------------------------------------------------------

--
-- Table structure for table `registered_players`
--

CREATE TABLE `registered_players` (
  `player_id` int(11) NOT NULL,
  `player_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registered_players`
--

INSERT INTO `registered_players` (`player_id`, `player_name`, `email`, `mobile_no`, `game_id`, `registration_date`) VALUES
(1, 'Princegodhain', 'test@gmail.com', '9638325084', 2, '2026-09-27 10:36:38');

-- --------------------------------------------------------

--
-- Table structure for table `tournament_games`
--

CREATE TABLE `tournament_games` (
  `game_id` int(11) NOT NULL,
  `game_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournament_games`
--

INSERT INTO `tournament_games` (`game_id`, `game_name`, `category`) VALUES
(1, 'Trackmania', 'Racing'),
(2, 'Mecha BREAK', 'Action/Mech'),
(3, 'Blood Strike', 'First-Person Shooter');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `match_scores`
--
ALTER TABLE `match_scores`
  ADD PRIMARY KEY (`score_id`),
  ADD KEY `player_id` (`player_id`);

--
-- Indexes for table `registered_players`
--
ALTER TABLE `registered_players`
  ADD PRIMARY KEY (`player_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `tournament_games`
--
ALTER TABLE `tournament_games`
  ADD PRIMARY KEY (`game_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `match_scores`
--
ALTER TABLE `match_scores`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `registered_players`
--
ALTER TABLE `registered_players`
  MODIFY `player_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tournament_games`
--
ALTER TABLE `tournament_games`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `match_scores`
--
ALTER TABLE `match_scores`
  ADD CONSTRAINT `match_scores_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `registered_players` (`player_id`);

--
-- Constraints for table `registered_players`
--
ALTER TABLE `registered_players`
  ADD CONSTRAINT `registered_players_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `tournament_games` (`game_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
