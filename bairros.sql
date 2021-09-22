-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 15-Mar-2020 às 22:43
-- Versão do servidor: 5.7.26
-- versão do PHP: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lunch`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `bairros`
--

DROP TABLE IF EXISTS `bairros`;
CREATE TABLE IF NOT EXISTS `bairros` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `bairros`
--

INSERT INTO `bairros` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, '14 de Novembro', NULL, NULL),
(2, 'Alto Alegre', NULL, NULL),
(3, 'Brasília', NULL, NULL),
(4, 'Brazmadeira', NULL, NULL),
(5, 'Canadá', NULL, NULL),
(6, 'Cancelli', NULL, NULL),
(7, 'Cascavel Velho', NULL, NULL),
(8, 'Cataratas', NULL, NULL),
(9, 'Centralito', NULL, NULL),
(10, 'Centro', NULL, NULL),
(11, 'Ciro Nardi', NULL, NULL),
(12, 'Claudete', NULL, NULL),
(13, 'Condomínio Residencial Gramado ii', NULL, NULL),
(14, 'Coqueiral', NULL, NULL),
(15, 'Country', NULL, NULL),
(16, 'Distrito Industrial', NULL, NULL),
(17, 'Esmeralda', NULL, NULL),
(18, 'Espigão Azul', NULL, NULL),
(19, 'Floresta', NULL, NULL),
(20, 'Guarujá', NULL, NULL),
(21, 'Interlagos', NULL, NULL),
(22, 'Jardim Belo Horizonte', NULL, NULL),
(23, 'Loteamento Fag', NULL, NULL),
(24, 'Maria Luíza', NULL, NULL),
(25, 'Morumbi', NULL, NULL),
(26, 'Neva', NULL, NULL),
(27, 'Nova Cidade', NULL, NULL),
(28, 'Núcleo de Produção', NULL, NULL),
(29, 'Pacaembu', NULL, NULL),
(30, 'Parque São Paulo', NULL, NULL),
(31, 'Parque Verde', NULL, NULL),
(32, 'Periolo', NULL, NULL),
(33, 'Pioneiros Catarinenses', NULL, NULL),
(34, 'Recanto Tropical', NULL, NULL),
(35, 'Região do Lago', NULL, NULL),
(36, 'Residencial Golden Garden', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
