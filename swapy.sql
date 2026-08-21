-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 18-06-2026 a las 22:53:23
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
-- Base de datos: `swapy`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

DROP TABLE IF EXISTS `administrador`;
CREATE TABLE IF NOT EXISTS `administrador` (
  `pkfk_id_doc` int NOT NULL,
  `id_admin` bigint NOT NULL,
  `correo` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `contraseña` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`pkfk_id_doc`,`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int NOT NULL,
  `n_categoria` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `n_categoria`) VALUES
(1, 'Tecnología'),
(2, 'Hogar'),
(3, 'Juegos'),
(4, 'Moda'),
(5, 'Deportes'),
(6, 'Mascotas'),
(7, 'Automóvil'),
(8, 'Otros');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chats`
--

DROP TABLE IF EXISTS `chats`;
CREATE TABLE IF NOT EXISTS `chats` (
  `id_chat` int NOT NULL,
  `fk_id_doc` int NOT NULL,
  `fk_id_usuario` bigint NOT NULL,
  `estado_chat` tinytext COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_chat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `denuncias`
--

DROP TABLE IF EXISTS `denuncias`;
CREATE TABLE IF NOT EXISTS `denuncias` (
  `id_denuncia` int NOT NULL AUTO_INCREMENT,
  `correo_denunciante` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `correo_denunciado` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  `estado` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_denuncia`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faqs`
--

DROP TABLE IF EXISTS `faqs`;
CREATE TABLE IF NOT EXISTS `faqs` (
  `id_faq` int NOT NULL AUTO_INCREMENT,
  `pregunta` text COLLATE utf8mb4_general_ci NOT NULL,
  `respuesta` text COLLATE utf8mb4_general_ci NOT NULL,
  `categoria` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `orden` int DEFAULT '1',
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_faq`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

DROP TABLE IF EXISTS `imagenes`;
CREATE TABLE IF NOT EXISTS `imagenes` (
  `id_img` int NOT NULL,
  `img_producto` blob,
  PRIMARY KEY (`id_img`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impulsar`
--

DROP TABLE IF EXISTS `impulsar`;
CREATE TABLE IF NOT EXISTS `impulsar` (
  `cod_impulso` int NOT NULL,
  `pago` int NOT NULL,
  PRIMARY KEY (`cod_impulso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intercambio`
--

DROP TABLE IF EXISTS `intercambio`;
CREATE TABLE IF NOT EXISTS `intercambio` (
  `id_intercambio` int NOT NULL,
  `fk_id_chat` int NOT NULL,
  `n_producto_publicado` varchar(35) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `n_producto_ofertado` varchar(35) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_cierre` date DEFAULT NULL,
  PRIMARY KEY (`id_intercambio`),
  KEY `fk_intercambio_chats` (`fk_id_chat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

DROP TABLE IF EXISTS `mensajes`;
CREATE TABLE IF NOT EXISTS `mensajes` (
  `id_mensaje` int NOT NULL,
  `fk_id_doc` int NOT NULL,
  `fk_id_usuario` bigint NOT NULL,
  `fk_id_chat` int NOT NULL,
  `contenido_mensaje` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_hora_envio` datetime DEFAULT NULL,
  `estado_mensaje` tinytext COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_mensaje`),
  KEY `fk_mensajes_chats` (`fk_id_chat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

DROP TABLE IF EXISTS `persona`;
CREATE TABLE IF NOT EXISTS `persona` (
  `fkpk_id_doc` int NOT NULL,
  `documento` bigint NOT NULL,
  `primer_nombre` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `segundo_nombre` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `primer_apellido` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `segundo_apellido` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fk_id_recuperar_cuenta` int NOT NULL,
  PRIMARY KEY (`fkpk_id_doc`,`documento`),
  KEY `fk_persona_recuperar` (`fk_id_recuperar_cuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `problemas_frecuentes`
--

DROP TABLE IF EXISTS `problemas_frecuentes`;
CREATE TABLE IF NOT EXISTS `problemas_frecuentes` (
  `id_problema` int NOT NULL,
  `titulo_problema` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `solucion` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fk_id_doc` int NOT NULL,
  `fk_id_admin` bigint NOT NULL,
  PRIMARY KEY (`id_problema`),
  KEY `fk_problemas_admin` (`fk_id_doc`,`fk_id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `nombre_producto` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `valor_estimado` int DEFAULT NULL,
  `desc_producto` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fk_id_categoria` int NOT NULL,
  `fk_cod_impulso` int DEFAULT NULL,
  `fk_id_doc` int NOT NULL,
  `fk_id_usuario` bigint NOT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `fk_productos_categoria` (`fk_id_categoria`),
  KEY `fk_productos_impulso` (`fk_cod_impulso`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `valor_estimado`, `desc_producto`, `imagen`, `fk_id_categoria`, `fk_cod_impulso`, `fk_id_doc`, `fk_id_usuario`) VALUES
(4, 'estructura', 199000, 'pah', 'productos/1781564279_85d736798bfb9d929133.png', 5, NULL, 1, 9),
(5, 'mapa mental', 1000, 'informacion', 'productos/1781564566_505c7dcdec272b22a22e.png', 8, NULL, 1, 9),
(6, 'fierro golpeador de ', 1000, 'fierro golpeador de parejas felices', NULL, 1, NULL, 1, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_has_imagenes`
--

DROP TABLE IF EXISTS `productos_has_imagenes`;
CREATE TABLE IF NOT EXISTS `productos_has_imagenes` (
  `pkfk_id_producto` int NOT NULL,
  `pkfk_id_img` int NOT NULL,
  PRIMARY KEY (`pkfk_id_producto`,`pkfk_id_img`),
  KEY `fk_phi_img` (`pkfk_id_img`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

DROP TABLE IF EXISTS `publicaciones`;
CREATE TABLE IF NOT EXISTS `publicaciones` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` int UNSIGNED NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `categoria` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `valor_minimo` decimal(12,2) DEFAULT '0.00',
  `descripcion_deseado` text COLLATE utf8mb4_general_ci,
  `categoria_deseada` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `imagen` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperar_cuenta`
--

DROP TABLE IF EXISTS `recuperar_cuenta`;
CREATE TABLE IF NOT EXISTS `recuperar_cuenta` (
  `id_recuperar_cuenta` int NOT NULL,
  `codigo_verif` int NOT NULL,
  PRIMARY KEY (`id_recuperar_cuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_perfil`
--

DROP TABLE IF EXISTS `reporte_perfil`;
CREATE TABLE IF NOT EXISTS `reporte_perfil` (
  `id_reporte` int NOT NULL,
  `motivo` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fk_id_doc` int NOT NULL,
  `fk_id_admin` bigint NOT NULL,
  PRIMARY KEY (`id_reporte`),
  KEY `fk_reporte_admin` (`fk_id_doc`,`fk_id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_doc`
--

DROP TABLE IF EXISTS `t_doc`;
CREATE TABLE IF NOT EXISTS `t_doc` (
  `id_doc` int NOT NULL,
  `tipo_doc` tinytext COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_doc`
--

INSERT INTO `t_doc` (`id_doc`, `tipo_doc`) VALUES
(1, 'CC'),
(2, 'TI'),
(3, 'CE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `pkfk_id_doc` int NOT NULL,
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `contraseña` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'Activo',
  `codigo_verificacion` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `otp_expira` datetime DEFAULT NULL,
  `verificado` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `premium` tinyint(1) DEFAULT '0',
  `nombre` varchar(60) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `categorias` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`pkfk_id_doc`, `id_usuario`, `username`, `telefono`, `correo`, `contraseña`, `rol`, `estado`, `codigo_verificacion`, `otp_expira`, `verificado`, `premium`, `nombre`, `descripcion`, `foto`, `categorias`) VALUES
(1, 1, NULL, NULL, 'admin@gmail.com', '$2y$10$vMInTOJFZmlwHcooOQL5fej4VxRzp1FFW4iyygjOyklum1XSoVbJa', 'Administrador', 'Activo', '971021', NULL, 'NO', 0, NULL, NULL, NULL, NULL),
(1, 8, NULL, NULL, 'empleado@gmail.com', '$2y$10$5zSNMjuHhFVkzvnvzbaxROyZ8uKmEbSiBQjD24UjNTIUlM7mT9Ps.', 'Empleado', 'Activo', '674716', NULL, 'NO', 0, NULL, NULL, NULL, NULL),
(1, 9, NULL, NULL, 'cliente@gmail.com', '$2y$10$CGLhXYd12LIIouJWnfODduRpOJXARAVQR8yG9X9M.RIXm41QBbxtW', 'Cliente', 'Activo', '975109', NULL, 'NO', 0, NULL, NULL, NULL, NULL),
(1, 10, 'juan pablo', '3214335346', 'leo@gmail.com', '$2y$10$NHT2czNEMp.WJW9N1QmXY.7.5uWsfjUx8LhkjpQtiypmJkT1Fs9DO', 'Cliente', 'Activo', NULL, NULL, 'SI', 0, NULL, NULL, NULL, NULL),
(1, 11, 'Prueba Postman', '300000000', 'viktor@gmail.com', '$2y$10$8LqgJWMAMzoNOV5XbkAqZeIUTHL.DJXxj/nzNP0FELTjebvcirc/u', 'Cliente', 'Activo', NULL, NULL, 'SI', 0, NULL, 'Este cambio fue hecho desde Postman', 'perfiles/1781822314_4f8bcdd2a514a7374d13.png', 'mascotas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_denuncias`
--

DROP TABLE IF EXISTS `usuario_denuncias`;
CREATE TABLE IF NOT EXISTS `usuario_denuncias` (
  `id_denuncia` int NOT NULL AUTO_INCREMENT,
  `id_usuario_denunciante` int NOT NULL,
  `id_usuario_denunciado` int DEFAULT NULL,
  `tipo_denuncia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `respuesta` longtext COLLATE utf8mb4_unicode_ci,
  `estado` enum('pendiente','en_revision','resuelta','rechazada') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_respuesta` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_denuncia`),
  KEY `idx_usuario_denuncias_denunciante` (`id_usuario_denunciante`),
  KEY `idx_usuario_denuncias_denunciado` (`id_usuario_denunciado`),
  KEY `idx_usuario_denuncias_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_preguntas`
--

DROP TABLE IF EXISTS `usuario_preguntas`;
CREATE TABLE IF NOT EXISTS `usuario_preguntas` (
  `id_pregunta` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `pregunta` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `respuesta` longtext COLLATE utf8mb4_unicode_ci,
  `estado` enum('pendiente','respondida','cerrada') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_respuesta` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pregunta`),
  KEY `idx_usuario_preguntas_usuario` (`id_usuario`),
  KEY `idx_usuario_preguntas_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_premium`
--

DROP TABLE IF EXISTS `usuario_premium`;
CREATE TABLE IF NOT EXISTS `usuario_premium` (
  `id_premium` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `fecha_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_fin` timestamp NULL DEFAULT NULL,
  `plan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'basico',
  `estado` enum('activo','cancelado','vencido') COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  PRIMARY KEY (`id_premium`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  KEY `idx_usuario_premium_usuario` (`id_usuario`),
  KEY `idx_usuario_premium_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `fk_admin_persona` FOREIGN KEY (`pkfk_id_doc`,`id_admin`) REFERENCES `persona` (`fkpk_id_doc`, `documento`);

--
-- Filtros para la tabla `intercambio`
--
ALTER TABLE `intercambio`
  ADD CONSTRAINT `fk_intercambio_chats` FOREIGN KEY (`fk_id_chat`) REFERENCES `chats` (`id_chat`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `fk_mensajes_chats` FOREIGN KEY (`fk_id_chat`) REFERENCES `chats` (`id_chat`);

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `fk_persona_recuperar` FOREIGN KEY (`fk_id_recuperar_cuenta`) REFERENCES `recuperar_cuenta` (`id_recuperar_cuenta`),
  ADD CONSTRAINT `fk_persona_tipoDoc` FOREIGN KEY (`fkpk_id_doc`) REFERENCES `t_doc` (`id_doc`);

--
-- Filtros para la tabla `problemas_frecuentes`
--
ALTER TABLE `problemas_frecuentes`
  ADD CONSTRAINT `fk_problemas_admin` FOREIGN KEY (`fk_id_doc`,`fk_id_admin`) REFERENCES `administrador` (`pkfk_id_doc`, `id_admin`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`fk_id_categoria`) REFERENCES `categorias` (`id_categoria`);

--
-- Filtros para la tabla `productos_has_imagenes`
--
ALTER TABLE `productos_has_imagenes`
  ADD CONSTRAINT `fk_phi_img` FOREIGN KEY (`pkfk_id_img`) REFERENCES `imagenes` (`id_img`),
  ADD CONSTRAINT `fk_phi_productos` FOREIGN KEY (`pkfk_id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `reporte_perfil`
--
ALTER TABLE `reporte_perfil`
  ADD CONSTRAINT `fk_reporte_admin` FOREIGN KEY (`fk_id_doc`,`fk_id_admin`) REFERENCES `administrador` (`pkfk_id_doc`, `id_admin`);

--
-- Filtros para la tabla `usuario_denuncias`
--
ALTER TABLE `usuario_denuncias`
  ADD CONSTRAINT `usuario_denuncias_ibfk_1` FOREIGN KEY (`id_usuario_denunciante`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_denuncias_ibfk_2` FOREIGN KEY (`id_usuario_denunciado`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `usuario_preguntas`
--
ALTER TABLE `usuario_preguntas`
  ADD CONSTRAINT `usuario_preguntas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_premium`
--
ALTER TABLE `usuario_premium`
  ADD CONSTRAINT `usuario_premium_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
