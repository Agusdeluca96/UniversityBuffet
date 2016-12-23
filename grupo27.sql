-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 10-12-2016 a las 20:32:42
-- Versión del servidor: 10.0.26-MariaDB-0+deb8u1
-- Versión de PHP: 5.6.24-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `grupo27`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
`id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`) VALUES
(1, 'Golosinas'),
(2, 'Bebidas'),
(3, 'Galletitas'),
(4, 'Snack'),
(6, 'Hamburguesas'),
(7, 'Hamburguesas'),
(8, 'Hamburguesas'),
(9, 'Hamburguesas'),
(10, 'Hamburguesas'),
(11, 'Hamburguesas'),
(12, 'Hamburguesas'),
(13, 'Hamburguesas'),
(14, 'Hamburguesas'),
(15, 'Hamburguesas'),
(16, 'Hamburguesas'),
(17, 'Hamburguesas'),
(18, 'Hamburguesas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE IF NOT EXISTS `compra` (
`id` int(11) NOT NULL,
  `factura` varchar(100) NOT NULL,
  `proveedor_cuit` varchar(15) NOT NULL,
  `fecha` date NOT NULL,
  `num_factura` int(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id`, `factura`, `proveedor_cuit`, `fecha`, `num_factura`) VALUES
(3, '50', '1212121212', '2016-10-01', 0),
(4, '51', '3131313131', '2016-10-02', 0),
(5, '55', '2121211212136', '2016-10-03', 0),
(6, '60', '989898787', '2016-10-04', 0),
(7, '10', '444444444', '2016-10-05', 0),
(8, '11', '666666666', '2016-10-06', 0),
(9, '15', '222222222', '2016-10-08', 0),
(10, '97', '2131312312', '2016-09-07', 0),
(17, '99', '33445566', '2016-08-09', 0),
(18, '100', '22334455', '2016-09-26', 0),
(19, '150', '11231312333', '2012-12-12', 0),
(20, '150', '11231312333', '2012-12-12', 0),
(21, '564', '27447785', '2016-10-12', 0),
(22, '5646', '484654', '2016-10-11', 0),
(23, '2147483647', '12342345346', '0000-00-00', 0),
(24, '567890', '08965654', '0000-00-00', 0),
(25, '12312323', '21312312', '2023-12-31', 0),
(26, '', '2144', '2016-10-28', 0),
(27, 'images.png', '77777777', '2016-10-25', 33),
(28, '', '123', '2016-12-10', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_producto`
--

CREATE TABLE IF NOT EXISTS `compra_producto` (
`id` int(11) NOT NULL,
  `compra_id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `compra_producto`
--

INSERT INTO `compra_producto` (`id`, `compra_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(4, 19, 1, 1, 10),
(7, 20, 4, 1, 10),
(8, 4, 1, 5, 20),
(9, 4, 2, 4, 30),
(10, 5, 5, 5, 55),
(11, 6, 6, 6, 66),
(12, 7, 7, 7, 77),
(13, 8, 8, 8, 88),
(14, 9, 9, 9, 99),
(15, 10, 10, 10, 100),
(16, 17, 17, 17, 177),
(17, 18, 18, 18, 188),
(18, 3, 1, 5, 15),
(19, 3, 2, 4, 14),
(20, 3, 2, 5, 15),
(21, 21, 16, 30, 5),
(22, 22, 1, 49, 45),
(23, 0, 2, 9, 3),
(24, 24, 1, 1, 1),
(25, 25, 1, 3, 2),
(27, 26, 8, 2, 2),
(28, 27, 2, 3, 4),
(29, 27, 0, 4, 1),
(39, 28, 4, 30, 1),
(40, 28, 18, 40, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE IF NOT EXISTS `configuracion` (
`id` int(5) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `contacto` varchar(50) NOT NULL,
  `paginas` int(5) NOT NULL,
  `mensaje` int(5) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `titulo`, `contacto`, `paginas`, `mensaje`, `descripcion`) VALUES
(1, 'Buffet de la Facultad de Informatica', 'buffet@info.unlp.com.ar', 5, 1, 'Bienvenido !! Esta es la pagina del buffet de informatica para hacer tus pedidos Online');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_del_dia`
--

CREATE TABLE IF NOT EXISTS `menu_del_dia` (
`id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `habilitado` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `menu_del_dia`
--

INSERT INTO `menu_del_dia` (`id`, `fecha`, `habilitado`) VALUES
(5, '2016-12-07', 1),
(6, '2016-12-08', 1),
(9, '2016-12-09', 1),
(10, '0000-00-00', 0),
(11, '0000-00-00', 0),
(12, '2016-11-10', 0),
(13, '2016-12-09', 0),
(14, '2016-12-09', 0),
(15, '2016-12-10', 1),
(16, '2016-12-11', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_del_dia_producto`
--

CREATE TABLE IF NOT EXISTS `menu_del_dia_producto` (
`id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `menu_del_dia_producto`
--

INSERT INTO `menu_del_dia_producto` (`id`, `producto_id`, `menu_id`) VALUES
(1, 1, 5),
(2, 2, 5),
(3, 3, 6),
(4, 4, 6),
(5, 18, 9),
(6, 3, 9),
(7, 16, 9),
(8, 1, 10),
(9, 2, 10),
(10, 1, 11),
(11, 2, 11),
(12, 3, 12),
(13, 2, 13),
(14, 2, 13),
(15, 16, 14),
(16, 8, 14),
(17, 18, 14),
(18, 1, 15),
(19, 2, 15),
(20, 4, 16),
(21, 3, 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE IF NOT EXISTS `pedido` (
`id` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `fecha_alta` date NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id`, `estado`, `fecha_alta`, `usuario_id`, `observaciones`) VALUES
(1, 0, '2016-12-06', 5, 'prueba 1'),
(2, 0, '2016-12-06', 5, 'prueba 12'),
(3, 0, '2016-12-06', 5, 'prueba 10'),
(4, 0, '2016-12-06', 5, 'safsad'),
(5, 0, '2016-12-09', 5, 'ssss'),
(6, 0, '2016-12-09', 5, '<script> alert("Hola");</script>'),
(7, 0, '2016-12-09', 11, 'alumnos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

CREATE TABLE IF NOT EXISTS `pedido_detalle` (
`id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pedido_detalle`
--

INSERT INTO `pedido_detalle` (`id`, `pedido_id`, `producto_id`, `cantidad`) VALUES
(1, 1, 1, 10),
(2, 1, 2, 10),
(3, 2, 1, 12),
(4, 2, 2, 12),
(5, 3, 1, 10),
(6, 3, 2, 10),
(7, 4, 1, 1),
(8, 4, 2, 2),
(9, 5, 8, 1),
(10, 5, 18, 4),
(11, 6, 8, 1),
(12, 7, 8, 1),
(13, 7, 16, 1),
(14, 7, 18, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE IF NOT EXISTS `producto` (
`id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `marca` varchar(45) NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_minimo` int(11) NOT NULL,
  `categoria` int(11) DEFAULT NULL,
  `proveedor` varchar(45) NOT NULL,
  `precio_venta_unitario` float NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `fecha_alta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `marca`, `stock`, `stock_minimo`, `categoria`, `proveedor`, `precio_venta_unitario`, `descripcion`, `fecha_alta`) VALUES
(1, 'Coca Cola', 'Coca Company', 1200, 100, 2, 'Cocacolero', 15, 'Elixir de la vida', '2016-10-05 13:01:53'),
(2, 'Lays', 'Frito-Lay', 216, 20, 4, 'Super Chino', 12, 'Saladisimas', '2016-10-05 13:04:53'),
(3, 'Cheetos', 'Frito-Lay', 200, 20, 4, 'Super Chino', 12, 'Para mancharse los dedos', '2016-10-05 13:08:23'),
(4, 'Dasani', 'Coca Company', 75, 20, 1, 'Super Chino', 10, 'Agua mineral', '2016-10-05 13:17:32'),
(6, 'Gomitas', 'Mogul', 42, 100, 1, 'Gomitas S.R.L.', 19, 'Unicas en el mercado', '2016-10-10 22:22:15'),
(7, 'Fanta', 'Fanta Company', 82, 100, 2, 'Fantero', 18, 'una bebida inigualable', '2016-10-10 22:22:15'),
(8, 'Sprite', 'Sprite Company', 2983781, 100, 2, 'Spritero', 22, 'el sabor del re-encuentro', '2016-10-10 22:24:17'),
(9, 'Chocolinas', 'Bagley', 1, 89, 3, 'Galletitas por doquier', 9, 'bajas en calorias ', '2016-10-10 22:24:17'),
(10, 'Pepitos', 'Bagley', 18, 100, 3, 'Pepitero', 11, 'bajas en calcio', '2016-10-10 22:26:06'),
(11, 'Palitos de la Selva', 'Arcor', 44, 50, 1, 'Golosinero', 19, 'los caramelos mas divertidos', '2016-10-10 22:26:06'),
(12, 'Sugus', 'Bagley', -12, 150, 1, 'Golosinero', 36, 'los mejores caramelos', '2016-10-10 22:28:48'),
(13, '3D', '3D Company', 67, 109, 4, 'Snack La Plata', 39, 'los mejores 3D de la ciudad', '2016-10-10 22:28:48'),
(14, 'Pepsi', 'Pepsi Company', 191, 199, 2, 'Pepsitero', 11, 'imitacion a la mejor bebida', '2016-10-10 22:40:25'),
(15, '7Up', '7Up Company', 187, 199, 2, 'Pepsitero', 15, 'te quita la sed', '2016-10-10 22:40:25'),
(16, 'Mantecol', 'Arcor', 123, 10, 4, 'Arcor', 20, 'aaaa', '2016-10-13 20:06:38'),
(18, 'Flynn Paff', 'Arcor', 99, 20, 1, 'Arcor', 1, 'Caramelos masticables', '2016-10-19 14:42:53'),
(20, 'prod 3', 'la marca', 28, 3, 4, 'la marca', 3.5, 'ffff', '2016-10-26 00:19:48'),
(21, 'SÃ¡ndwiches de jamÃ³n ', 'Ninguna', 44, 3, 6, 'la marca', 0, 'alguno', '2016-12-09 22:23:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telegram`
--

CREATE TABLE IF NOT EXISTS `telegram` (
`id` int(11) NOT NULL,
  `chat_id` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `telegram`
--

INSERT INTO `telegram` (`id`, `chat_id`) VALUES
(1, '273698747'),
(2, '202568189'),
(12, '294903925');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
`id` int(11) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `clave` varchar(45) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `documento` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `rol` int(11) NOT NULL,
  `habilitado` int(11) NOT NULL,
  `ubicacion` int(11) NOT NULL,
  `departamento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `usuario`, `clave`, `nombre`, `apellido`, `documento`, `email`, `telefono`, `rol`, `habilitado`, `ubicacion`, `departamento`) VALUES
(1, 'Agusdeluca96', '1234', 'Agustin', 'De Luca', '39557795', 'agusdeluca96@gmail.com', '2216063263', 4, 1, 1, NULL),
(2, 'superuser', 'superuser', 'Super', 'User', '38281812', 'superuser@cortes.com', '2218283903', 4, 1, 0, NULL),
(3, 'admin', 'admin', 'administra', 'dor', '16396394', 'admin@gmail.com', '02214740788', 0, 1, 8, ''),
(4, 'gestion', 'gestion', 'ges', 'tion', '17511009', 'gestion@gmail.com', '2214800866', 1, 1, 9, NULL),
(5, 'online', 'online', 'onli', 'ne', '39097372', 'online@gmail.com', '2216101884', 2, 1, 10, NULL),
(6, 'deshabilitado', 'deshabilitado', 'deshabili', 'tado', '37189364', 'deshabitado@gmail.com', '02356876590', 2, 0, 11, ''),
(8, 'prueba', 'prueba', 'prueba', 'prueba', '234555', 'cccc@ffff.vo', '333', 2, 0, 333, ''),
(9, 'NoSe', 'nadadenada', 'Nada', 'DeNada', '22222222', 'asd@asd.asd', '2222', 0, 1, 2, ''),
(10, 'prueba', '1234', 'prueba', 'p', '2222', 'cccc@ddd.com', '444', 3, 0, 0, 'alguno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE IF NOT EXISTS `venta` (
`id` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `codigo` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id`, `fecha`, `codigo`) VALUES
(11, '2016-10-23 22:55:15', 1),
(12, '2016-10-23 22:55:58', 1),
(13, '2016-10-23 22:56:32', 1),
(14, '2016-10-23 22:57:05', 1),
(15, '2016-10-23 22:57:27', 1),
(16, '2016-10-23 22:57:39', 1),
(17, '2016-10-23 22:58:10', 1),
(19, '2016-10-31 12:24:16', 1),
(20, '2016-12-09 22:48:49', 1),
(21, '2016-12-09 22:52:08', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_producto`
--

CREATE TABLE IF NOT EXISTS `venta_producto` (
`id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta_producto`
--

INSERT INTO `venta_producto` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(36, 12, 1, 4, 15),
(37, 13, 16, 4, 20),
(38, 13, 18, 5, 1),
(39, 13, 11, 6, 19),
(40, 14, 12, 25, 36),
(41, 15, 5, 4, 4),
(42, 15, 2, 1, 12),
(43, 16, 14, 6, 11),
(44, 17, 9, 2, 9),
(45, 17, 4, 2, 10),
(46, 17, 12, 5, 36),
(49, 19, 15, 2, 15),
(50, 19, 14, 2, 11),
(51, 19, 20, 5, 3.5),
(52, 11, 6, 8, 19),
(53, 11, 15, 4, 15),
(54, 11, 16, 5, 20);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compra_producto`
--
ALTER TABLE `compra_producto`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu_del_dia`
--
ALTER TABLE `menu_del_dia`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu_del_dia_producto`
--
ALTER TABLE `menu_del_dia_producto`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `telegram`
--
ALTER TABLE `telegram`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT de la tabla `compra_producto`
--
ALTER TABLE `compra_producto`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
MODIFY `id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `menu_del_dia`
--
ALTER TABLE `menu_del_dia`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT de la tabla `menu_del_dia_producto`
--
ALTER TABLE `menu_del_dia_producto`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `telegram`
--
ALTER TABLE `telegram`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
