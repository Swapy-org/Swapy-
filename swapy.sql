-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-09-2026 a las 14:55:01
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
-- Base de datos: `swapy`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `pkfk_id_doc` int(11) NOT NULL,
  `id_admin` bigint(20) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `n_categoria` varchar(45) NOT NULL
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

CREATE TABLE `chats` (
  `id_chat` int(11) NOT NULL,
  `fk_id_doc` int(11) NOT NULL,
  `fk_id_usuario` int(11) NOT NULL,
  `estado_chat` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `chats`
--

INSERT INTO `chats` (`id_chat`, `fk_id_doc`, `fk_id_usuario`, `estado_chat`) VALUES
(1, 1, 9, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conversaciones`
--

CREATE TABLE `conversaciones` (
  `id_conversacion` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_iniciador` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `ultimo_mensaje` varchar(255) DEFAULT NULL,
  `actualizado_en` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conversaciones`
--

INSERT INTO `conversaciones` (`id_conversacion`, `id_producto`, `id_iniciador`, `id_destinatario`, `ultimo_mensaje`, `actualizado_en`) VALUES
(1, 4, 15, 9, NULL, '2026-09-04 23:08:14'),
(2, 5, 15, 9, NULL, '2026-09-04 23:15:39'),
(3, 5, 17, 9, '¡Hola! 😄 Soy el que tiene el mapa mental que buscas. Está en buen estado y el valor de referencia es $1.000 COP. ¿Te interesa saber más o tienes alguna propuesta de intercambio?', '2026-09-06 00:38:50'),
(4, 4, 17, 9, NULL, '2026-09-06 00:50:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `denuncias`
--

CREATE TABLE `denuncias` (
  `id_denuncia` int(11) NOT NULL,
  `correo_denunciante` varchar(255) NOT NULL,
  `correo_denunciado` varchar(255) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` varchar(50) DEFAULT 'Pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `denuncias`
--

INSERT INTO `denuncias` (`id_denuncia`, `correo_denunciante`, `correo_denunciado`, `motivo`, `descripcion`, `estado`, `fecha_creacion`) VALUES
(1, 'cliente@gmail.com', 'usuario2@gmail.com', 'Producto defectuoso', 'El producto entregado no coincide con las fotos de la publicación.', 'Pendiente', '2026-09-04 11:44:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `pkfk_id_doc` int(11) NOT NULL,
  `id_empleado` bigint(20) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`pkfk_id_doc`, `id_empleado`, `correo`, `contrasena`) VALUES
(1, 100200300, 'empleado@swapy.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(1, 1022987653, 'rodriguezjhondavid57@gmail.com', '$2y$10$H3Y1FP.46BcAwoljBdhvFO3IkGdTeMgE6C1cwx/X1uN3tR/uvQYUK');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado_perfil`
--

CREATE TABLE `empleado_perfil` (
  `fk_id_empleado` bigint(20) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `ciudad` varchar(60) DEFAULT NULL,
  `medio_transporte` varchar(40) DEFAULT NULL,
  `cuenta_pago` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado_perfil`
--

INSERT INTO `empleado_perfil` (`fk_id_empleado`, `telefono`, `ciudad`, `medio_transporte`, `cuenta_pago`) VALUES
(100200300, '3001234567', 'Bogotá', 'Motocicleta', 'Nequi - 3001234567');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrega`
--

CREATE TABLE `entrega` (
  `id_entrega` int(11) NOT NULL,
  `fk_id_intercambio` int(11) NOT NULL,
  `fk_id_empleado` bigint(20) DEFAULT NULL,
  `solicitante` varchar(100) DEFAULT NULL,
  `direccion_recogida` varchar(255) NOT NULL,
  `direccion_entrega` varchar(255) NOT NULL,
  `hora_limite` time DEFAULT NULL,
  `estado` enum('Pendiente','Aceptada','En_ruta','Entregada','Fallida','Cancelada') NOT NULL DEFAULT 'Pendiente',
  `confirmacion_origen` tinyint(1) NOT NULL DEFAULT 0,
  `confirmacion_destino` tinyint(1) NOT NULL DEFAULT 0,
  `intentos_fallidos` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrega`
--

INSERT INTO `entrega` (`id_entrega`, `fk_id_intercambio`, `fk_id_empleado`, `solicitante`, `direccion_recogida`, `direccion_entrega`, `hora_limite`, `estado`, `confirmacion_origen`, `confirmacion_destino`, `intentos_fallidos`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 1, NULL, 'Cliente Prueba', 'Calle 100 # 15-20', 'Carrera 68 # 80-10', '14:30:00', 'Pendiente', 0, 0, 0, '2026-09-04 11:44:04', '2026-09-04 11:44:04'),
(2, 2, 100200300, 'Cliente Prueba', 'Carrera 7 # 45-10', 'Calle 170 # 20-12', '18:00:00', 'Aceptada', 1, 0, 0, '2026-09-04 11:44:04', '2026-09-04 11:44:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faqs`
--

CREATE TABLE `faqs` (
  `id_faq` int(11) NOT NULL,
  `pregunta` text NOT NULL,
  `respuesta` text NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `orden` int(11) DEFAULT 1,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id_img` int(11) NOT NULL,
  `img_producto` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impulsar`
--

CREATE TABLE `impulsar` (
  `cod_impulso` int(11) NOT NULL,
  `pago` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intercambio`
--

CREATE TABLE `intercambio` (
  `id_intercambio` int(11) NOT NULL,
  `fk_id_chat` int(11) NOT NULL,
  `n_producto_publicado` varchar(35) DEFAULT NULL,
  `n_producto_ofertado` varchar(35) DEFAULT NULL,
  `fecha_cierre` date DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'En proceso',
  `fk_id_empleado` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `intercambio`
--

INSERT INTO `intercambio` (`id_intercambio`, `fk_id_chat`, `n_producto_publicado`, `n_producto_ofertado`, `fecha_cierre`, `fecha_entrega`, `direccion`, `estado`, `fk_id_empleado`) VALUES
(1, 1, 'Estructura', 'Mapa Mental', '2026-08-30', '2026-09-01', 'Calle 100 # 15-20, Bogotá', 'En proceso', NULL),
(2, 1, 'Consola PS4', 'Bicicleta MTB', '2026-08-28', '2026-08-29', 'Carrera 7 # 45-10, Bogotá', 'En proceso', 100200300);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intercambios`
--

CREATE TABLE `intercambios` (
  `id_intercambio` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_solicitante` int(11) NOT NULL,
  `id_propietario` int(11) NOT NULL,
  `confirmado_solicitante` tinyint(1) NOT NULL DEFAULT 0,
  `confirmado_propietario` tinyint(1) NOT NULL DEFAULT 0,
  `direccion` varchar(255) NOT NULL,
  `descripcion_lugar` text NOT NULL,
  `descripcion_producto` text NOT NULL,
  `estado` varchar(30) NOT NULL DEFAULT 'pendiente',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `intercambios`
--

INSERT INTO `intercambios` (`id_intercambio`, `id_producto`, `id_solicitante`, `id_propietario`, `confirmado_solicitante`, `confirmado_propietario`, `direccion`, `descripcion_lugar`, `descripcion_producto`, `estado`, `creado_en`) VALUES
(1, 5, 17, 9, 1, 0, 'carrera 5 23-12', 'estacion de transmileno el dorado', 'audifonos inalambricos de alta definicion', 'pendiente', '2026-09-06 00:39:49'),
(2, 4, 17, 9, 1, 0, 'avenida 46 con caracas', 'cerca de la estacion del portal sur', 'estructura fisica de un mapa conceptual avanzado', 'aceptado', '2026-09-06 00:51:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id_mensaje` int(11) NOT NULL,
  `fk_id_doc` int(11) NOT NULL,
  `fk_id_usuario` int(11) NOT NULL,
  `fk_id_chat` int(11) NOT NULL,
  `contenido_mensaje` varchar(255) DEFAULT NULL,
  `fecha_hora_envio` datetime DEFAULT NULL,
  `estado_mensaje` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_chat`
--

CREATE TABLE `mensajes_chat` (
  `id_mensaje_chat` int(11) NOT NULL,
  `id_conversacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL DEFAULT 0,
  `tipo` varchar(20) NOT NULL DEFAULT 'usuario',
  `contenido` text NOT NULL,
  `creado_en` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mensajes_chat`
--

INSERT INTO `mensajes_chat` (`id_mensaje_chat`, `id_conversacion`, `id_usuario`, `tipo`, `contenido`, `creado_en`) VALUES
(1, 3, 17, 'usuario', 'hola busco lo que vendes', '2026-09-06 00:38:50'),
(2, 3, 0, 'ia', '¡Hola! 😄 Soy el que tiene el mapa mental que buscas. Está en buen estado y el valor de referencia es $1.000 COP. ¿Te interesa saber más o tienes alguna propuesta de intercambio?', '2026-09-06 00:38:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `fkpk_id_doc` int(11) NOT NULL,
  `documento` bigint(20) NOT NULL,
  `primer_nombre` varchar(15) NOT NULL,
  `segundo_nombre` varchar(15) DEFAULT NULL,
  `primer_apellido` varchar(12) NOT NULL,
  `segundo_apellido` varchar(12) DEFAULT NULL,
  `fk_id_recuperar_cuenta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`fkpk_id_doc`, `documento`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `fk_id_recuperar_cuenta`) VALUES
(1, 100200300, 'Carlos', NULL, 'Empledo', NULL, 1),
(1, 1022987653, 'Empleado', NULL, 'Swapy', NULL, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `problemas_frecuentes`
--

CREATE TABLE `problemas_frecuentes` (
  `id_problema` int(11) NOT NULL,
  `titulo_problema` varchar(45) DEFAULT NULL,
  `solucion` varchar(45) DEFAULT NULL,
  `fk_id_doc` int(11) NOT NULL,
  `fk_id_admin` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(50) NOT NULL,
  `valor_estimado` int(11) DEFAULT NULL,
  `desc_producto` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fk_id_categoria` int(11) NOT NULL,
  `fk_cod_impulso` int(11) DEFAULT NULL,
  `fk_id_doc` int(11) NOT NULL,
  `fk_id_usuario` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `valor_estimado`, `desc_producto`, `imagen`, `fk_id_categoria`, `fk_cod_impulso`, `fk_id_doc`, `fk_id_usuario`, `estado`) VALUES
(4, 'estructura', 199000, 'pah', 'productos/1781564279_85d736798bfb9d929133.png', 5, NULL, 1, 9, 'Activo'),
(5, 'mapa mental', 1000, 'informacion', 'productos/1781564566_505c7dcdec272b22a22e.png', 8, NULL, 1, 9, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_has_imagenes`
--

CREATE TABLE `productos_has_imagenes` (
  `pkfk_id_producto` int(11) NOT NULL,
  `pkfk_id_img` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `valor_minimo` decimal(12,2) DEFAULT 0.00,
  `descripcion_deseado` text DEFAULT NULL,
  `categoria_deseada` varchar(100) DEFAULT NULL,
  `imagen` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperar_cuenta`
--

CREATE TABLE `recuperar_cuenta` (
  `id_recuperar_cuenta` int(11) NOT NULL,
  `codigo_verif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recuperar_cuenta`
--

INSERT INTO `recuperar_cuenta` (`id_recuperar_cuenta`, `codigo_verif`) VALUES
(1, 123456),
(2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_perfil`
--

CREATE TABLE `reporte_perfil` (
  `id_reporte` int(11) NOT NULL,
  `motivo` varchar(45) DEFAULT NULL,
  `fk_id_doc` int(11) NOT NULL,
  `fk_id_admin` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_doc`
--

CREATE TABLE `t_doc` (
  `id_doc` int(11) NOT NULL,
  `tipo_doc` varchar(10) NOT NULL
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

CREATE TABLE `usuario` (
  `pkfk_id_doc` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(50) NOT NULL,
  `numero_documento` varchar(30) DEFAULT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(50) DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'Activo',
  `codigo_verificacion` varchar(10) DEFAULT NULL,
  `otp_expira` datetime DEFAULT NULL,
  `verificado` varchar(5) DEFAULT NULL,
  `premium` tinyint(1) DEFAULT 0,
  `nombre` varchar(60) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `categorias` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`pkfk_id_doc`, `id_usuario`, `username`, `telefono`, `correo`, `numero_documento`, `contrasena`, `rol`, `estado`, `codigo_verificacion`, `otp_expira`, `verificado`, `premium`, `nombre`, `descripcion`, `foto`, `categorias`) VALUES
(1, 1, NULL, NULL, 'admin@gmail.com', NULL, '$2y$10$vMInTOJFZmlwHcooOQL5fej4VxRzp1FFW4iyygjOyklum1XSoVbJa', 'Administrador', 'Activo', '971021', NULL, 'NO', 0, NULL, NULL, NULL, NULL),
(1, 9, NULL, NULL, 'cliente@gmail.com', NULL, '$2y$10$CGLhXYd12LIIouJWnfODduRpOJXARAVQR8yG9X9M.RIXm41QBbxtW', 'Cliente', 'Activo', '975109', NULL, 'NO', 0, NULL, NULL, NULL, NULL),
(1, 14, NULL, NULL, 'maria@gmail.com', '1028785128', '$2y$10$zJv16I6pDB1JhQt4EZjYgu3eacTXYCQGOUKtg9FOBFDpw2rgAeZQa', 'Empleado', 'Activo', NULL, NULL, 'SI', 0, NULL, NULL, NULL, NULL),
(1, 16, 'jhon', '3212332343', 'rodriguezjhondavid57@gmail.com', '1022987653', '$2y$10$uFg6szFu3wensWEUO/5Uku0DXsh8To7Z7oWrPy3XIURDUSmPQhEQS', 'Empleado', 'Activo', NULL, NULL, 'SI', 0, NULL, NULL, NULL, NULL),
(1, 17, 'maribel', '3118551530', 'mari.rodrip75@gmail.com', '4567876545', '$2y$10$BTEUd30WkdF/XmyVxoIexOlBVNMZVjJ3YWFSt4ITHnpaNfeedii/e', 'Cliente', 'Activo', NULL, NULL, 'SI', 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_denuncias`
--

CREATE TABLE `usuario_denuncias` (
  `id_denuncia` int(11) NOT NULL,
  `id_usuario_denunciante` int(11) NOT NULL,
  `id_usuario_denunciado` int(11) DEFAULT NULL,
  `tipo_denuncia` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `respuesta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('pendiente','en_revision','resuelta','rechazada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_respuesta` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_preguntas`
--

CREATE TABLE `usuario_preguntas` (
  `id_pregunta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `pregunta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `respuesta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('pendiente','respondida','cerrada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_respuesta` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_premium`
--

CREATE TABLE `usuario_premium` (
  `id_premium` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_inicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_fin` timestamp NULL DEFAULT NULL,
  `plan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'basico',
  `estado` enum('activo','cancelado','vencido') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`pkfk_id_doc`,`id_admin`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id_chat`),
  ADD KEY `fk_chats_usuario` (`fk_id_usuario`);

--
-- Indices de la tabla `conversaciones`
--
ALTER TABLE `conversaciones`
  ADD PRIMARY KEY (`id_conversacion`),
  ADD UNIQUE KEY `uq_conversacion_producto_usuario` (`id_producto`,`id_iniciador`,`id_destinatario`),
  ADD KEY `idx_conversaciones_iniciador` (`id_iniciador`),
  ADD KEY `idx_conversaciones_destinatario` (`id_destinatario`);

--
-- Indices de la tabla `denuncias`
--
ALTER TABLE `denuncias`
  ADD PRIMARY KEY (`id_denuncia`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`pkfk_id_doc`,`id_empleado`);

--
-- Indices de la tabla `empleado_perfil`
--
ALTER TABLE `empleado_perfil`
  ADD PRIMARY KEY (`fk_id_empleado`);

--
-- Indices de la tabla `entrega`
--
ALTER TABLE `entrega`
  ADD PRIMARY KEY (`id_entrega`),
  ADD KEY `fk_entrega_intercambio` (`fk_id_intercambio`),
  ADD KEY `idx_entrega_empleado` (`fk_id_empleado`);

--
-- Indices de la tabla `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id_faq`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id_img`);

--
-- Indices de la tabla `impulsar`
--
ALTER TABLE `impulsar`
  ADD PRIMARY KEY (`cod_impulso`);

--
-- Indices de la tabla `intercambio`
--
ALTER TABLE `intercambio`
  ADD PRIMARY KEY (`id_intercambio`),
  ADD KEY `fk_intercambio_chats` (`fk_id_chat`),
  ADD KEY `idx_intercambio_empleado` (`fk_id_empleado`);

--
-- Indices de la tabla `intercambios`
--
ALTER TABLE `intercambios`
  ADD PRIMARY KEY (`id_intercambio`),
  ADD KEY `idx_intercambio_producto` (`id_producto`),
  ADD KEY `idx_intercambio_solicitante` (`id_solicitante`),
  ADD KEY `idx_intercambio_propietario` (`id_propietario`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `fk_mensajes_chats` (`fk_id_chat`);

--
-- Indices de la tabla `mensajes_chat`
--
ALTER TABLE `mensajes_chat`
  ADD PRIMARY KEY (`id_mensaje_chat`),
  ADD KEY `idx_mensajes_chat_conversacion` (`id_conversacion`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`fkpk_id_doc`,`documento`),
  ADD KEY `fk_persona_recuperar` (`fk_id_recuperar_cuenta`);

--
-- Indices de la tabla `problemas_frecuentes`
--
ALTER TABLE `problemas_frecuentes`
  ADD PRIMARY KEY (`id_problema`),
  ADD KEY `fk_problemas_admin` (`fk_id_doc`,`fk_id_admin`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_productos_categoria` (`fk_id_categoria`),
  ADD KEY `fk_productos_impulso` (`fk_cod_impulso`),
  ADD KEY `fk_productos_usuario` (`fk_id_usuario`);

--
-- Indices de la tabla `productos_has_imagenes`
--
ALTER TABLE `productos_has_imagenes`
  ADD PRIMARY KEY (`pkfk_id_producto`,`pkfk_id_img`),
  ADD KEY `fk_phi_img` (`pkfk_id_img`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`usuario_id`);

--
-- Indices de la tabla `recuperar_cuenta`
--
ALTER TABLE `recuperar_cuenta`
  ADD PRIMARY KEY (`id_recuperar_cuenta`);

--
-- Indices de la tabla `reporte_perfil`
--
ALTER TABLE `reporte_perfil`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `fk_reporte_admin` (`fk_id_doc`,`fk_id_admin`);

--
-- Indices de la tabla `t_doc`
--
ALTER TABLE `t_doc`
  ADD PRIMARY KEY (`id_doc`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_usuario_tdoc` (`pkfk_id_doc`);

--
-- Indices de la tabla `usuario_denuncias`
--
ALTER TABLE `usuario_denuncias`
  ADD PRIMARY KEY (`id_denuncia`),
  ADD KEY `idx_usuario_denuncias_denunciante` (`id_usuario_denunciante`),
  ADD KEY `idx_usuario_denuncias_denunciado` (`id_usuario_denunciado`);

--
-- Indices de la tabla `usuario_preguntas`
--
ALTER TABLE `usuario_preguntas`
  ADD PRIMARY KEY (`id_pregunta`),
  ADD KEY `idx_usuario_preguntas_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuario_premium`
--
ALTER TABLE `usuario_premium`
  ADD PRIMARY KEY (`id_premium`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `conversaciones`
--
ALTER TABLE `conversaciones`
  MODIFY `id_conversacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `denuncias`
--
ALTER TABLE `denuncias`
  MODIFY `id_denuncia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `entrega`
--
ALTER TABLE `entrega`
  MODIFY `id_entrega` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id_faq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `intercambios`
--
ALTER TABLE `intercambios`
  MODIFY `id_intercambio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mensajes_chat`
--
ALTER TABLE `mensajes_chat`
  MODIFY `id_mensaje_chat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuario_denuncias`
--
ALTER TABLE `usuario_denuncias`
  MODIFY `id_denuncia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario_preguntas`
--
ALTER TABLE `usuario_preguntas`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario_premium`
--
ALTER TABLE `usuario_premium`
  MODIFY `id_premium` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `fk_admin_persona` FOREIGN KEY (`pkfk_id_doc`,`id_admin`) REFERENCES `persona` (`fkpk_id_doc`, `documento`);

--
-- Filtros para la tabla `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `fk_chats_usuario` FOREIGN KEY (`fk_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `fk_empleado_persona` FOREIGN KEY (`pkfk_id_doc`,`id_empleado`) REFERENCES `persona` (`fkpk_id_doc`, `documento`);

--
-- Filtros para la tabla `entrega`
--
ALTER TABLE `entrega`
  ADD CONSTRAINT `fk_entrega_intercambio` FOREIGN KEY (`fk_id_intercambio`) REFERENCES `intercambio` (`id_intercambio`) ON DELETE CASCADE;

--
-- Filtros para la tabla `intercambio`
--
ALTER TABLE `intercambio`
  ADD CONSTRAINT `fk_intercambio_chats` FOREIGN KEY (`fk_id_chat`) REFERENCES `chats` (`id_chat`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `fk_mensajes_chats` FOREIGN KEY (`fk_id_chat`) REFERENCES `chats` (`id_chat`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mensajes_chat`
--
ALTER TABLE `mensajes_chat`
  ADD CONSTRAINT `fk_mensajes_chat_conversacion` FOREIGN KEY (`id_conversacion`) REFERENCES `conversaciones` (`id_conversacion`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`fk_id_categoria`) REFERENCES `categorias` (`id_categoria`),
  ADD CONSTRAINT `fk_productos_impulso` FOREIGN KEY (`fk_cod_impulso`) REFERENCES `impulsar` (`cod_impulso`),
  ADD CONSTRAINT `fk_productos_usuario` FOREIGN KEY (`fk_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos_has_imagenes`
--
ALTER TABLE `productos_has_imagenes`
  ADD CONSTRAINT `fk_phi_img` FOREIGN KEY (`pkfk_id_img`) REFERENCES `imagenes` (`id_img`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_phi_productos` FOREIGN KEY (`pkfk_id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reporte_perfil`
--
ALTER TABLE `reporte_perfil`
  ADD CONSTRAINT `fk_reporte_admin` FOREIGN KEY (`fk_id_doc`,`fk_id_admin`) REFERENCES `administrador` (`pkfk_id_doc`, `id_admin`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_tdoc` FOREIGN KEY (`pkfk_id_doc`) REFERENCES `t_doc` (`id_doc`);

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
