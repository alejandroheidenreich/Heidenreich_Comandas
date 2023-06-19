-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-06-2023 a las 10:37:57
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigoMesa`, `estado`) VALUES
(6, 'sMcNd', 'Esperando Pedido'),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigoPedido`, `idMesa`, `idProducto`, `nombreCliente`, `estado`, `tiempoEstimado`, `tiempoInicio`, `tiempoEntregado`, `fechaBaja`) VALUES
(21, 'ycZXJ', 6, 5, 'Pepe', 'Pendiente', NULL, NULL, NULL, NULL),
(22, 'ycZXJ', 6, 2, 'Pepe', 'Pendiente', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `descripcion`, `tipo`, `precio`) VALUES
(1, 'Pinta IPA', 'cervecero', 500),
(2, 'Papas con cheddar', 'cocinero', 800),
(3, 'Coca-cola', 'bartender', 300),
(4, 'Sandwich', 'cocinero', 500),
(5, 'Milanesa Napolitana', 'cocinero', 900),
(6, 'Hamburguesa Completa', 'cocinero', 1000);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `clave`, `rol`, `token`, `expiracionToken`, `fechaBaja`) VALUES
(10, 'admin', '$2y$10$GIxPuyjENd6pHSPIVH0d8O8/ri1yhchIHubT1fRsr2oaf7VZp9iPG', 'socio', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2ODcxNTM0NTcsImV4cCI6MTY4NzIxMzQ1NywiYXVkIjoiMjdmNzFjMGU1ODU0M2ZhNWUyYTUxNTJkMDgxOTc0NTJhOGQ4MTJmYyIsImRhdGEiOnsidXN1YXJpbyI6ImFkbWluIiwicm9sIjoic29jaW8iLCJjbGF2ZSI6IiQyeSQxMCRHSXhQdXlqRU5kNnBIU1BJVkgwZDhPOFwvcmkxeWhjaElIdWJUMWZSc3Iyb2FmN1ZacDlpUEcifSwiYXBwIjoiVFAgQ29tYW5kYSJ9.b7CEzMD1ImgjXGRlesSp4597d-Mj-fq0F-Xuth9KpBs', '838:59:59', NULL),
(11, 'roberto', '$2y$10$raWIEZ1Ix6spjafoeiDrmOhxPa2cIysVFgSiqqUgwb.F/NKMMdhJ6', 'mozo', NULL, NULL, NULL),
(12, 'pepe', '$2y$10$3j3QWK6pfgC7iFI4oggjieqMSI6Ws0l/Tx1xGTZ3q/0Bq9msT7qeK', 'cocinero', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2ODcxNjMzODYsImV4cCI6MTY4NzIyMzM4NiwiYXVkIjoiMjdmNzFjMGU1ODU0M2ZhNWUyYTUxNTJkMDgxOTc0NTJhOGQ4MTJmYyIsImRhdGEiOnsidXN1YXJpbyI6InBlcGUiLCJyb2wiOiJjb2NpbmVybyIsImNsYXZlIjoiJDJ5JDEwJDNqM1FXSzZwZmdDN2lGSTRvZ2dqaWVxTVNJNldzMGxcL1R4MXhHVFozcVwvMEJxOW1zVDdxZUsifSwiYXBwIjoiVFAgQ29tYW5kYSJ9.lxjtDGcS7R8nkShTgKtk51NX0IdCQJQkY84BkPwTIXo', '00:00:00', NULL),
(13, 'raul', '$2y$10$1oxYAreORhL6klymQEi.Au565o5hSDRvbCYAkgODVWGd5RwV8mU/G', 'bartender', NULL, NULL, NULL),
(14, 'tito', '$2y$10$f47Q6fIN1CE3jAiPXboVL.ptSLW4iQlfBKmB9imy1b3A0UdUNiGYO', 'socio', NULL, NULL, NULL),
(15, 'maria', '$2y$10$NOmxeoGLztJuaeuZLsIyBOjiFEhd404n.g.Eh2F0sJqqn9d.veZMy', 'mozo', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2ODcxNjAwNjAsImV4cCI6MTY4NzIyMDA2MCwiYXVkIjoiMjdmNzFjMGU1ODU0M2ZhNWUyYTUxNTJkMDgxOTc0NTJhOGQ4MTJmYyIsImRhdGEiOnsidXN1YXJpbyI6Im1hcmlhIiwicm9sIjoibW96byIsImNsYXZlIjoiJDJ5JDEwJE5PbXhlb0dMenRKdWFldVpMc0l5Qk9qaUZFaGQ0MDRuLmcuRWgyRjBzSnFxbjlkLnZlWk15In0sImFwcCI6IlRQIENvbWFuZGEifQ.yyj8cWuYbrfPLv10hZF2d2E3ElpmctnkxGH4OcrPEcc', '00:00:00', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
