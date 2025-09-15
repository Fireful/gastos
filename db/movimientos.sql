-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 15-09-2025 a las 10:28:20
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gastos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

DROP TABLE IF EXISTS `movimientos`;
CREATE TABLE IF NOT EXISTS `movimientos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` enum('ingreso','gasto') NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `categoria` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'General',
  `concepto` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `nota` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id`, `tipo`, `cantidad`, `categoria`, `concepto`, `fecha`, `nota`) VALUES
(16, 'gasto', 43.90, 'General', 'Moto', '2025-02-20', NULL),
(15, 'ingreso', 200.00, 'Nómina', 'Nómina', '2025-01-31', NULL),
(22, 'ingreso', 30.00, 'Otros', 'Traspaso Propio', '2025-02-25', NULL),
(17, 'gasto', 20.00, 'Otros', 'Cajero', '2025-02-20', NULL),
(18, 'ingreso', 50.00, 'Otros', 'Otros', '2025-02-22', NULL),
(19, 'gasto', 136.00, 'Comida', 'Comida', '2025-02-21', NULL),
(20, 'ingreso', 43.90, 'Otros', 'Miravia', '2025-02-23', NULL),
(21, 'gasto', 44.91, 'Transporte', 'Miravia', '2025-02-23', NULL),
(23, 'gasto', 13.05, 'General', 'Aliexpress', '2025-02-27', NULL),
(24, 'gasto', 39.95, 'General', 'MyCard', '2025-03-01', NULL),
(25, 'gasto', 10.00, 'General', 'Cajero', '2025-03-07', NULL),
(26, 'gasto', 9.95, 'Transporte', 'Shapeheart Iman', '2025-03-26', NULL),
(27, 'ingreso', 400.00, 'Nómina', 'Nomina Septiembre', '2025-09-07', NULL),
(28, 'ingreso', 150.00, 'General', 'Moto', '2025-02-24', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
