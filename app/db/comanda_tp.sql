-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-06-2023 a las 22:45:26
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
  `estado` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigoMesa`, `estado`) VALUES
(6, 'sMcNd', 'cerrada'),
(7, 'P9RiB', 'cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `codigoPedido` varchar(5) NOT NULL,
  `idMesa` int(8) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idFactura` int(11) NOT NULL,
  `nombreCliente` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `tiempoEstimado` time DEFAULT NULL,
  `tiempoInicio` time DEFAULT NULL,
  `tiempoFinal` time DEFAULT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigoPedido`, `idMesa`, `idProducto`, `idFactura`, `nombreCliente`, `estado`, `tiempoEstimado`, `tiempoInicio`, `tiempoFinal`, `fechaBaja`) VALUES
(2, 'XQKwe', 6, 1, 0, 'Juan Carlos', 'Pendiente', NULL, NULL, NULL, NULL),
(3, 'Yhu4g', 6, 1, 0, 'Roberto', 'Pendiente', NULL, NULL, NULL, NULL),
(4, 'pwcCH', 6, 1, 0, 'Roberto', 'Pendiente', NULL, NULL, NULL, NULL),
(5, 'BnXa1', 7, 1, 0, 'Roberto', 'Pendiente', NULL, NULL, NULL, NULL),
(6, '1kalJ', 7, 1, 0, 'Roberto', 'Pendiente', NULL, NULL, NULL, NULL),
(7, 'nQMpB', 7, 1, 0, 'Roberto', 'Pendiente', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `descripcion`, `tipo`, `precio`) VALUES
(1, 'Pinta IPA', 'cerveza', 500),
(2, 'Papas con cheddar', 'comida', 800),
(3, 'Coca-cola', 'bebida', 300);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(250) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `token` varchar(500) DEFAULT NULL,
  `expiracionToken` time DEFAULT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `clave`, `rol`, `token`, `expiracionToken`, `fechaBaja`) VALUES
(10, 'admin', '$2y$10$GIxPuyjENd6pHSPIVH0d8O8/ri1yhchIHubT1fRsr2oaf7VZp9iPG', 'socio', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2ODcxMTIxMjIsImV4cCI6MTY4NzE3MjEyMiwiYXVkIjoiMzc5YWM2YzJkZWJjYmY4ZDI3MzI0MWRlZDg4ZjY1M2ZkMWExZDE5NCIsImRhdGEiOnsidXN1YXJpbyI6ImFkbWluIiwiY2xhdmUiOiIkMnkkMTAkR0l4UHV5akVOZDZwSFNQSVZIMGQ4TzhcL3JpMXloY2hJSHViVDFmUnNyMm9hZjdWWnA5aVBHIn0sImFwcCI6IlRQIENvbWFuZGEifQ.uTy84ZtNUZCtnGW5AwfPZfFt4cFS8pIZe3xUVm5t-VM', '838:59:59', NULL),
(11, 'roberto', '$2y$10$raWIEZ1Ix6spjafoeiDrmOhxPa2cIysVFgSiqqUgwb.F/NKMMdhJ6', 'mozo', NULL, NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
