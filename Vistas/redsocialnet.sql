-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2023 a las 05:28:22
-- Versión del servidor: 10.1.36-MariaDB
-- Versión de PHP: 7.0.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `redsocialnet`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `perfil_id` int(200) NOT NULL,
  `usuario_id` int(200) NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `country` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagen_perfil` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`perfil_id`, `usuario_id`, `name`, `last_name`, `date_of_birth`, `country`, `city`, `imagen_perfil`) VALUES
(1, 1, 'Alex', 'Cruz', '1997-09-24', 'co', 'Bogota  D.C.', '../Img/User-Profile.png'),
(2, 2, 'Hernán', 'Córtes', '1999-02-02', 'co', 'Bogota  D.C.', '../Img/User-Profile.png'),
(3, 3, 'Andrés', 'Nivia', '1999-06-24', 'be', 'Bruxelles-Capitale', '../Img/User-Profile.png'),
(4, 4, 'Damián', 'Perez', '2001-10-10', 'fr', 'Paris', '../Img/User-Profile.png'),
(5, 5, 'Germán', 'Piña', '1998-11-10', 'au', 'Victoria-Daly', '../Img/User-Profile.png'),
(6, 6, 'Jesús', 'León', '1988-12-10', 'ar', 'Departamento de Libertador General San Martin', '../Img/User-Profile.png'),
(7, 7, 'Martín', 'Haller', '1989-04-14', 'be', 'Bruxelles-Capitale', '../Img/User-Profile.png'),
(8, 8, 'Omán', 'Torres', '1990-01-14', 'bj', 'Cotonou', '../Img/User-Profile.png'),
(9, 9, 'Raúl', 'Paez', '1999-11-10', 'pa', 'Distrito de Taboga', '../Img/User-Profile.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `publicacion_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `texto` text NOT NULL,
  `imagen_ruta` varchar(255) DEFAULT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguidores`
--

CREATE TABLE `seguidores` (
  `id` int(11) NOT NULL,
  `seguidor_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguidos`
--

CREATE TABLE `seguidos` (
  `id` int(11) NOT NULL,
  `seguidor_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuario_id` int(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `email`, `password`) VALUES
(1, 'alexone0924@gmail.com', '$2y$10$BLzbNpgaAz/4aKWoYINt/.5ykqtTU552LuKG0w1rK2P8eDuCsAUP.'),
(2, 'hernan@gmail.com', '$2y$10$fkTbQhfagUizBS/gq5LxSOvjt8Rd0VnXQKV1QJDSlm/bzino8rfVO'),
(3, 'andres@gmail.com', '$2y$10$LQ1WXDr4xFNzI1msv6MUdO5j9ULhUx/9fCM.AkJvjgFS0aEqC4w0G'),
(4, 'damina@gmail.com', '$2y$10$YM1ufyIC0j3yLIC4hn8Pje8HduAWIF85bOO3QRCp7r9lg5xAfXzAG'),
(5, 'german@gmail.com', '$2y$10$moO8xjTuPPxEjNqx6DUgCebZIZlPZvSbzuzwoIdvGJGDhQh1T4X0S'),
(6, 'jesus@gmail.com', '$2y$10$nxZAsO.6J2v15TnpryCUvupagmGIfIvFBbVKgBZrEXIP0W6AtDMjm'),
(7, 'martin@gmail.com', '$2y$10$UwvHZSpTp668EEaxkMexmOGeR3mBGAbVl.IoNXRFo7T0dyDknyHGC'),
(8, 'torres@gmail.com', '$2y$10$5zY5KojCRM00F2xbwMvWR.X06egzip/tIruSWNa5kJlRcfgzSneri'),
(9, 'raul@gmail.com', '$2y$10$M2slX1xJPoQatqHty0VrEOufsyauMLMBh9fUAEIEEagwwzc9.7s76');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`perfil_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`publicacion_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `seguidores`
--
ALTER TABLE `seguidores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_seguidores_usuarios` (`seguidor_id`),
  ADD KEY `FK_seguidores_perfiles` (`usuario_id`);

--
-- Indices de la tabla `seguidos`
--
ALTER TABLE `seguidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_seguidos_usuarios` (`seguidor_id`),
  ADD KEY `FK_seguidos_perfiles` (`usuario_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `perfil_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `publicacion_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `seguidores`
--
ALTER TABLE `seguidores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `seguidos`
--
ALTER TABLE `seguidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD CONSTRAINT `perfiles_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Filtros para la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Filtros para la tabla `seguidores`
--
ALTER TABLE `seguidores`
  ADD CONSTRAINT `FK_seguidores_perfiles` FOREIGN KEY (`usuario_id`) REFERENCES `perfiles` (`usuario_id`),
  ADD CONSTRAINT `FK_seguidores_usuarios` FOREIGN KEY (`seguidor_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Filtros para la tabla `seguidos`
--
ALTER TABLE `seguidos`
  ADD CONSTRAINT `FK_seguidos_perfiles` FOREIGN KEY (`usuario_id`) REFERENCES `perfiles` (`usuario_id`),
  ADD CONSTRAINT `FK_seguidos_usuarios` FOREIGN KEY (`seguidor_id`) REFERENCES `usuario` (`usuario_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
