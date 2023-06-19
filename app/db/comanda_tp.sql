-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-06-2023 a las 22:21:05
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comanda_tp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `precioTotal` int(11) DEFAULT NULL,
  `estado` varchar(20) NOT NULL,
  `fotoMesa` varchar(50) NOT NULL,
  `puntaje` int(2) NOT NULL,
  `encuesta` varchar(66) NOT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `precioTotal`, `estado`, `fotoMesa`, `puntaje`, `encuesta`, `fechaBaja`) VALUES
(1, NULL, 'Pendiente', '', 0, '', NULL),
(2, NULL, 'Pendiente', '', 0, '', NULL),
(3, NULL, 'Pendiente', '', 0, '', NULL),
(4, NULL, 'Pendiente', '', 0, '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `codigoMesa` varchar(5) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigoMesa`, `estado`) VALUES
(6, 'sMcNd', 'Cerrada'),
(7, 'P9RiB', 'Cerrada'),
(8, 'K2J5M', 'Cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `codigoPedido` varchar(5) NOT NULL,
  `idMesa` int(8) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `nombreCliente` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `tiempoEstimado` time DEFAULT NULL,
  `tiempoInicio` time DEFAULT NULL,
  `tiempoEntregado` time DEFAULT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigoPedido`, `idMesa`, `idProducto`, `nombreCliente`, `estado`, `tiempoEstimado`, `tiempoInicio`, `tiempoEntregado`, `fechaBaja`) VALUES
(21, 'ycZXJ', 6, 5, 'Pepe', 'Entregado', '00:15:00', '16:58:38', '17:17:55', NULL),
(22, 'ycZXJ', 6, 2, 'Pepe', 'En preparacion', '00:15:00', '16:59:48', NULL, NULL),
(23, 'ycZXJ', 6, 2, 'Juan Carlos', 'Pendiente', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `precio` float NOT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `descripcion`, `tipo`, `precio`, `fechaBaja`) VALUES
(1, 'Pinta IPA', 'cervecero', 500, NULL),
(2, 'Papas con cheddar', 'cocinero', 800, NULL),
(3, 'Coca-cola', 'bartender', 300, NULL),
(4, 'Sandwich de Lomito XL', 'cocinero', 1500, '2023-06-19'),
(5, 'Milanesa Napolitana', 'cocinero', 900, NULL),
(6, 'Hamburguesa Completa', 'cocinero', 1000, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(500) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `token` varchar(500) DEFAULT NULL,
  `expiracionToken` time DEFAULT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `clave`, `rol`, `token`, `expiracionToken`, `fechaBaja`) VALUES
(11, 'roberto', '$2y$10$raWIEZ1Ix6spjafoeiDrmOhxPa2cIysVFgSiqqUgwb.F/NKMMdhJ6', 'mozo', NULL, NULL, NULL),
(12, 'pepe', '$2y$10$qVwOpLpAaTqGhr2OyWEdZuYOswYMmz36ClcQvKCIiDbyO4eXbkA2u', 'cocinero', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2ODcyMDU3MzksImV4cCI6MTY4NzI2NTczOSwiYXVkIjoiMzc5YWM2YzJkZWJjYmY4ZDI3MzI0MWRlZDg4ZjY1M2ZkMWExZDE5NCIsImRhdGEiOnsidXN1YXJpbyI6InBlcGUiLCJyb2wiOiJjb2NpbmVybyIsImNsYXZlIjoiJDJ5JDEwJHFWd09wTHBBYVRxR2hyMk95V0VkWnVZT3N3WU1tejM2Q2xjUXZLQ0lpRGJ5TzRlWGJrQTJ1In0sImFwcCI6IlRQIENvbWFuZGEifQ.YLWkJIamrXH1BWXLumB8ZXfCeTBgIrVa2uZzwDoLYgg', '838:59:59', NULL),
(13, 'raul', '$2y$10$1oxYAreORhL6klymQEi.Au565o5hSDRvbCYAkgODVWGd5RwV8mU/G', 'bartender', NULL, NULL, '2023-06-19'),
(14, 'tito', '$2y$10$olXCrpQ9ewHdMUMmibxC9uwQ6D6gmkw2asKN53ERTDpo9jCtRY1Ga', 'cervecero', NULL, NULL, '2023-06-19'),
(15, 'maria', '$2y$10$4XbEElJHziKYFEse1N4OAOwBPPkRCrif.dx5gteMOVJebPRNxSh3m', 'mozo', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2ODcyMDU3NTAsImV4cCI6MTY4NzI2NTc1MCwiYXVkIjoiMzc5YWM2YzJkZWJjYmY4ZDI3MzI0MWRlZDg4ZjY1M2ZkMWExZDE5NCIsImRhdGEiOnsidXN1YXJpbyI6Im1hcmlhIiwicm9sIjoibW96byIsImNsYXZlIjoiJDJ5JDEwJDRYYkVFbEpIemlLWUZFc2UxTjRPQU93QlBQa1JDcmlmLmR4NWd0ZU1PVkplYlBSTnhTaDNtIn0sImFwcCI6IlRQIENvbWFuZGEifQ.NJ0sJJ8Oh2cKGz6OaxWezrKmO695tPtNA5-uBJSQok0', '838:59:59', NULL),
(16, 'ana', '$2y$10$XAULPFBjZDuuToITqH82nuF7.MUTaMkd3y91Dc5Asn3XhcTLxa76.', 'candybar', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2ODcyMDQ4MjAsImV4cCI6MTY4NzI2NDgyMCwiYXVkIjoiMzc5YWM2YzJkZWJjYmY4ZDI3MzI0MWRlZDg4ZjY1M2ZkMWExZDE5NCIsImRhdGEiOnsidXN1YXJpbyI6ImFuYSIsInJvbCI6ImNhbmR5YmFyIiwiY2xhdmUiOiIkMnkkMTAkWEFVTFBGQmpaRHV1VG9JVHFIODJudUY3Lk1VVGFNa2QzeTkxRGM1QXNuM1hoY1RMeGE3Ni4ifSwiYXBwIjoiVFAgQ29tYW5kYSJ9.8HAf_q__Ea1nIhfIPBs_XdfT4K2fYxcqzw9x7siTjNk', '838:59:59', NULL),
(17, 'admin', '$2y$10$fawxBPHy3MzuXrort1Ro7eaARbpno6aVzk66ERssTMvMSd5erGyp2', 'socio', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2ODcyMDM5MjQsImV4cCI6MTY4NzI2MzkyNCwiYXVkIjoiMzc5YWM2YzJkZWJjYmY4ZDI3MzI0MWRlZDg4ZjY1M2ZkMWExZDE5NCIsImRhdGEiOnsidXN1YXJpbyI6ImFkbWluIiwicm9sIjoic29jaW8iLCJjbGF2ZSI6IiQyeSQxMCRmYXd4QlBIeTNNenVYcm9ydDFSbzdlYUFSYnBubzZhVnprNjZFUnNzVE12TVNkNWVyR3lwMiJ9LCJhcHAiOiJUUCBDb21hbmRhIn0.vBng3RRkkcITw-T1Yl76NyhcGGecBPnTF65LwILan4c', '838:59:59', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
