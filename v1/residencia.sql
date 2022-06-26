-- phpMyAdmin SQL Dump
-- version 5.2.0-1.fc36
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 26, 2022 at 07:41 PM
-- Server version: 10.5.16-MariaDB
-- PHP Version: 8.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `residencia`
--
CREATE DATABASE IF NOT EXISTS `residencia` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `residencia`;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `nombre` varchar(90) NOT NULL,
  `descripcion` text NOT NULL,
  `encargado` varchar(40) NOT NULL,
  `area` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `nombre`, `descripcion`, `encargado`, `area`) VALUES
(1, 'Drones', 'Descripcion drones', 'Encargado Drones', 'sistemas'),
(3, 'Konklux', 'User-friendly well-modulated architecture', 'Eleanora Balham', 'Sales'),
(4, 'Greenlam', 'Assimilated 3rd generation methodology', 'Annamaria Allnatt', 'Research and Development'),
(5, 'Alphazap', 'Advanced coherent function', 'Ulysses Waything', 'Support'),
(6, 'Alphazap', 'Digitized responsive installation', 'Mohandas Zoane', 'Training'),
(7, 'Alphazap', 'Universal coherent Graphic Interface', 'Iona Leinster', 'Product Management'),
(8, 'Zathin', 'Synergistic explicit conglomeration', 'Veronica Wycliff', 'Sales'),
(9, 'Solarbreeze', 'Implemented upward-trending product', 'Filippo Harlin', 'Support'),
(10, 'Rank', 'Persevering heuristic attitude', 'Rebecka Gredden', 'Human Resources'),
(11, 'Holdlamis', 'Down-sized regional help-desk', 'Annabelle Redgate', 'Support'),
(12, 'Pannier', 'Upgradable fault-tolerant implementation', 'Annamarie Firle', 'Product Management'),
(13, 'Y-find', 'Proactive impactful middleware', 'Freemon Hercules', 'Legal'),
(14, 'Wrapsafe', 'Exclusive fault-tolerant approach', 'Mathias Sandwich', 'Services'),
(15, 'Span', 'Multi-channelled background capacity', 'Bruce Cluley', 'Support'),
(16, 'Fixflex', 'Open-architected foreground Graphical User Interface', 'Dewie Colbran', 'Product Management'),
(17, 'Y-find', 'Adaptive contextually-based database', 'Granville Dorbin', 'Human Resources'),
(18, 'Bitchip', 'Profound dynamic core', 'Bran Goscomb', 'Support'),
(19, 'Zathin', 'Ameliorated asynchronous firmware', 'Glynis Blaisdale', 'Legal'),
(20, 'Trippledex', 'Grass-roots global artificial intelligence', 'Iris Plows', 'Product Management'),
(21, 'Sub-Ex', 'Team-oriented dynamic secured line', 'Cassie Phizaclea', 'Engineering'),
(22, 'Treeflex', 'Organic next generation portal', 'Charita Tucker', 'Research and Development'),
(23, 'Cookley', 'Managed reciprocal hierarchy', 'Cassey Laws', 'Business Development'),
(24, 'Flexidy', 'Focused solution-oriented firmware', 'Velvet Morecombe', 'Services'),
(26, 'Duobam', 'Optional national functionalities', 'Rowland Verrier', 'Product Management'),
(27, 'Alpha', 'Extended human-resource system engine', 'Jamaal Piggen', 'Services'),
(28, 'Estudio de mercado', 'Descripcion estudio de mercado', 'Encargado estudio de mercado', 'ISC');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellidoPaterno` varchar(40) NOT NULL,
  `apellidoMaterno` varchar(40) NOT NULL,
  `numeroDeControl` varchar(8) NOT NULL,
  `claveApi` varchar(60) NOT NULL,
  `idProject` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `nombre`, `apellidoPaterno`, `apellidoMaterno`, `numeroDeControl`, `claveApi`, `idProject`) VALUES
(5, 'Marco', 'De Jesus', 'Caceres', '18390579', '6e575ebc8518a0c6b3abc424deb20d59', 1),
(9, 'Juan', 'Perez', 'Perez', '22390555', '27f814f9364302c1b065a2b237afc1f0', 1),
(10, 'Luis', 'Lopez', 'Gonzalez', '22390500', '03fa848adf9e8fa9bab2d69f5ee3f24d', 1),
(12, 'Esmeralda', 'Castillo', 'Sanchez', '22390533', '8127f388536bea58dc29cc8d8afd6147', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numeroDeControl` (`numeroDeControl`),
  ADD KEY `idProject` (`idProject`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`idProject`) REFERENCES `project` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
