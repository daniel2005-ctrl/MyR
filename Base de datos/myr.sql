-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-04-2025 a las 21:43:31
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `myr`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerUsuarioPermisos` (IN `user_id` INT)   BEGIN
    SELECT u.id_usu, u.nombre, u.correo, u.rol, 
           GROUP_CONCAT(tp.nombre SEPARATOR ', ') AS permisos
    FROM usuarios u
    LEFT JOIN usuario_permisos up ON u.id_usu = up.id_usu
    LEFT JOIN tipo_permisos tp ON up.id_tipo = tp.id_tipo
    WHERE u.id_usu = user_id
    GROUP BY u.id_usu;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_pro` int(11) DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `telefono`, `email`, `id_pro`, `fecha_envio`) VALUES
(3, 'Samuel', '3026248589', 'samitarazonasan@gmail.com', 1, '2025-04-11 14:36:44'),
(5, 'Samuel', '3026248589', 'dixtroller365@gmail.com', 6, '2025-04-11 14:41:46'),
(6, 'Mariana', '1234567891', 'martin@gmail.com', 7, '2025-04-11 14:42:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id_pro` int(11) NOT NULL,
  `nombre_pro` varchar(30) NOT NULL,
  `tipo_pro` enum('vis','no vis','vip') NOT NULL,
  `tamano_pro` varchar(30) NOT NULL,
  `descripcion` text NOT NULL,
  `terminado` tinyint(1) NOT NULL,
  `imagenes_pro` varchar(255) NOT NULL,
  `videos_pro` varchar(255) NOT NULL,
  `ubicacion_pro` varchar(30) NOT NULL,
  `id_usu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id_pro`, `nombre_pro`, `tipo_pro`, `tamano_pro`, `descripcion`, `terminado`, `imagenes_pro`, `videos_pro`, `ubicacion_pro`, `id_usu`) VALUES
(1, 'MyR32', 'vis', '70-52 mtrs²', '', 1, '', '', 'Dirección: Calle Principal #12', 1),
(6, 'Alto De Rincon De Varsovia', 'vis', '70-52 mtrs²', '', 1, '', '', 'Dirección: Calle Principal #12', 1),
(7, 'Prados de Varsovia', 'vis', '70-52 mtrs²', '', 1, '', '', 'Dirección: Calle Principal #12', 1),
(8, 'Rincon De Varsovia', 'vis', '70-52 mtrs² ', '', 1, '', '', 'Dirección: Calle Principal #12', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subsidios`
--

CREATE TABLE `subsidios` (
  `id.nombre` varchar(30) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `ID_Proyecto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_permisos`
--

CREATE TABLE `tipo_permisos` (
  `id_tipo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL COMMENT 'Ej: ver_proyecto, editar_proyecto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_permisos`
--

INSERT INTO `tipo_permisos` (`id_tipo`, `nombre`) VALUES
(9, 'asignar_proyecto'),
(4, 'cambiar_contraseña'),
(16, 'configurar_sistema'),
(5, 'crear_proyecto'),
(10, 'crear_tarea'),
(3, 'editar_perfil'),
(7, 'editar_proyecto'),
(12, 'editar_tarea'),
(8, 'eliminar_proyecto'),
(13, 'eliminar_tarea'),
(15, 'exportar_reporte'),
(14, 'generar_reporte'),
(17, 'gestionar_roles'),
(1, 'gestionar_usuarios'),
(6, 'ver_proyecto'),
(11, 'ver_tarea'),
(2, 'ver_usuarios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usu` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','usuario') NOT NULL DEFAULT 'usuario',
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usu`, `nombre`, `email`, `password`, `rol`, `estado`, `fecha_creacion`) VALUES
(1, 'admin', 'admin@gmail.com', 'password', 'admin', 1, '2025-04-11 19:34:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_permisos`
--

CREATE TABLE `usuario_permisos` (
  `id_usu` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `id_pro` (`id_pro`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id_pro`),
  ADD KEY `id_admin` (`id_usu`);

--
-- Indices de la tabla `subsidios`
--
ALTER TABLE `subsidios`
  ADD PRIMARY KEY (`id.nombre`) USING BTREE,
  ADD UNIQUE KEY `ID_Proyecto` (`ID_Proyecto`);

--
-- Indices de la tabla `tipo_permisos`
--
ALTER TABLE `tipo_permisos`
  ADD PRIMARY KEY (`id_tipo`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usu`),
  ADD UNIQUE KEY `correo` (`email`);

--
-- Indices de la tabla `usuario_permisos`
--
ALTER TABLE `usuario_permisos`
  ADD PRIMARY KEY (`id_usu`,`id_tipo`),
  ADD KEY `id_tipo` (`id_tipo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id_pro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tipo_permisos`
--
ALTER TABLE `tipo_permisos`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`id_pro`) REFERENCES `proyectos` (`id_pro`);

--
-- Filtros para la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD CONSTRAINT `proyectos_ibfk_2` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`);

--
-- Filtros para la tabla `subsidios`
--
ALTER TABLE `subsidios`
  ADD CONSTRAINT `subsidios_ibfk_1` FOREIGN KEY (`ID_Proyecto`) REFERENCES `proyectos` (`id_pro`);

--
-- Filtros para la tabla `usuario_permisos`
--
ALTER TABLE `usuario_permisos`
  ADD CONSTRAINT `usuario_permisos_ibfk_1` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_permisos_ibfk_2` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_permisos` (`id_tipo`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
