-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2023 a las 22:53:46
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
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(8) NOT NULL,
  `codigoMesa` varchar(15) NOT NULL,
  `codigoPedido` varchar(15) NOT NULL,
  `puntuacionMesa` int(5) NOT NULL,
  `puntuacionRestaurante` int(5) NOT NULL,
  `puntuacionMozo` int(5) NOT NULL,
  `puntuacionCocinero` int(5) NOT NULL,
  `experiencia` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id`, `codigoMesa`, `codigoPedido`, `puntuacionMesa`, `puntuacionRestaurante`, `puntuacionMozo`, `puntuacionCocinero`, `experiencia`) VALUES
(1, 'P9RiB', 'HNoxo', 7, 8, 9, 8, 'La pase muy bien, rica comida'),
(2, 'K2J5M', 'IdzMF', 2, 4, 5, 1, 'No me gusto');

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
(7, 'P9RiB', 'Pagando'),
(8, 'K2J5M', 'Pagando'),
(9, 'FxnCu', 'Cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `codigoPedido` varchar(5) NOT NULL,
  `fotoMesa` varchar(150) DEFAULT NULL,
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

INSERT INTO `pedidos` (`id`, `codigoPedido`, `fotoMesa`, `idMesa`, `idProducto`, `nombreCliente`, `estado`, `tiempoEstimado`, `tiempoInicio`, `tiempoEntregado`, `fechaBaja`) VALUES
(34, 'IdzMF', './img/2023-06-26_10-16-02_Roberto_Mesa_8.jpg', 8, 57, 'Roberto', 'Entregado', '00:25:00', '10:17:23', '10:19:49', NULL),
(35, 'IdzMF', './img/2023-06-26_10-16-09_Roberto_Mesa_8.jpg', 8, 60, 'Roberto', 'Pendiente', NULL, NULL, NULL, NULL),
(36, 'IdzMF', './img/2023-06-26_10-16-19_Maria_Mesa_8.jpg', 8, 59, 'Maria', 'Pendiente', NULL, NULL, NULL, NULL),
(37, 'IdzMF', './img/2023-06-26_10-16-25_Maria_Mesa_8.jpg', 8, 58, 'Maria', 'En preparacion', '00:25:00', '10:17:28', NULL, NULL),
(38, 'IdzMF', './img/2023-06-26_10-16-35_Lucia_Mesa_8.jpg', 8, 58, 'Lucia', 'En preparacion', '00:25:00', '10:17:31', NULL, NULL),
(39, 'HNoxo', './img/2023-06-26_12-08-53_Sofia_Mesa_7.jpg', 7, 58, 'Sofia', 'Entregado', '00:25:00', '12:11:21', '12:11:46', NULL);

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
(6, 'Hamburguesa Completa', 'cocinero', 1000, NULL),
(57, 'Milanesa a Caballo', 'cocinero', 1500, NULL),
(58, 'Hamburguesa de Garbanzo', 'cocinero', 2500, NULL),
(59, 'Daikiri', 'bartender', 3000, NULL),
(60, 'Corona', 'cervecero', 1000, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(500) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `clave`, `rol`, `fechaBaja`) VALUES
(11, 'roberto', '$2y$10$raWIEZ1Ix6spjafoeiDrmOhxPa2cIysVFgSiqqUgwb.F/NKMMdhJ6', 'mozo', NULL),
(12, 'pepe', '$2y$10$qVwOpLpAaTqGhr2OyWEdZuYOswYMmz36ClcQvKCIiDbyO4eXbkA2u', 'cocinero', NULL),
(13, 'carlos', '$2y$10$H/yyqXxsEdiFOurxV2mQr.SGyFq8tj/uYYKwVSjmgGAJEs4Rp.GK6', 'bartender', NULL),
(14, 'tito', '$2y$10$olXCrpQ9ewHdMUMmibxC9uwQ6D6gmkw2asKN53ERTDpo9jCtRY1Ga', 'cervecero', '2023-06-19'),
(15, 'maria', '$2y$10$4XbEElJHziKYFEse1N4OAOwBPPkRCrif.dx5gteMOVJebPRNxSh3m', 'mozo', NULL),
(16, 'ana', '$2y$10$XAULPFBjZDuuToITqH82nuF7.MUTaMkd3y91Dc5Asn3XhcTLxa76.', 'candybar', NULL),
(17, 'admin', '$2y$10$fawxBPHy3MzuXrort1Ro7eaARbpno6aVzk66ERssTMvMSd5erGyp2', 'socio', NULL),
(19, 'mozo', '$2y$10$VBPiqxOGMedxrHYljxQHlOcA7UF2vmwGD.7IzIPRbEOgYsjpetQru', 'mozo', NULL),
(20, 'cocinero', '$2y$10$OFmwx3XcnN/7FLzcCklhAO0a6JH/KzICxfTxTmsD0yMyV4GKYNk0q', 'cocinero', NULL),
(21, 'bartender', '$2y$10$Hhub3IFBQahsfaMyA9OShO4Dc44qy0ej1OjZuEIGKoegzs0Z0klRm', 'bartender', NULL),
(22, 'socio', '$2y$10$xv89ELdsCxz76CjHVzWtOe08reVyAqSdVs46VyoBa6GEZZozWSjRy', 'socio', NULL),
(23, 'cervecero', '$2y$10$E25tZ.G06N9mEINQD7aB2.euhRttdgoH2g.N5SoRi2BdU71V0dRyq', 'cervecero', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
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
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
