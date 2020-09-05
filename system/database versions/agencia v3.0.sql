-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-09-2020 a las 06:04:53
-- Versión del servidor: 10.1.34-MariaDB
-- Versión de PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `agencia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agencia`
--

CREATE TABLE `agencia` (
  `id_agencia` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `lugar` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `usuario` varchar(45) DEFAULT NULL,
  `contraseña` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alquilervehiculo`
--

CREATE TABLE `alquilervehiculo` (
  `idalquilerVehiculo` int(11) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asosoria`
--

CREATE TABLE `asosoria` (
  `id_asosoria` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

CREATE TABLE `cita` (
  `id_-cita` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` varchar(45) DEFAULT NULL,
  `hora` varchar(45) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `motivo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id_contactos` int(11) NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `descripcion_servicio` varchar(45) DEFAULT NULL,
  `tipo_servicio` varchar(45) DEFAULT NULL,
  `costos` float DEFAULT NULL,
  `foto` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

CREATE TABLE `cotizacion` (
  `id_cotizacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `fecha` varchar(45) DEFAULT NULL,
  `totol` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_contactos`
--

CREATE TABLE `detalle_contactos` (
  `id_tours` int(11) NOT NULL,
  `id_contactos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_encomienda`
--

CREATE TABLE `detalle_encomienda` (
  `id_producto` int(11) NOT NULL,
  `id_encomienda` int(11) NOT NULL,
  `cantidad` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_envio`
--

CREATE TABLE `detalle_envio` (
  `id_detalle_envio` int(11) NOT NULL,
  `id_encomienda` int(11) NOT NULL,
  `fecha` varchar(45) DEFAULT NULL,
  `hora` varchar(45) DEFAULT NULL,
  `lugar` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encomienda`
--

CREATE TABLE `encomienda` (
  `id_encomienda` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `costo` varchar(45) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `fecha` varchar(45) DEFAULT NULL,
  `destino_final` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulario`
--

CREATE TABLE `formulario` (
  `id_formulario` int(11) NOT NULL,
  `clusulas` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulario_migratorio`
--

CREATE TABLE `formulario_migratorio` (
  `id_formulario` varchar(45) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `id_asesoria` int(11) NOT NULL,
  `respuesta` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria`
--

CREATE TABLE `galeria` (
  `id_foto` int(11) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `identificador` int(11) NOT NULL,
  `foto_path` varchar(45) DEFAULT NULL,
  `principal` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `itinerario`
--

CREATE TABLE `itinerario` (
  `id_itinerario` int(11) NOT NULL,
  `id_tours` int(11) NOT NULL,
  `id_sitio_turistico` int(11) NOT NULL,
  `dia` int(11) DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `fecha` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento`
--

CREATE TABLE `mantenimiento` (
  `id_mantenimiento` int(11) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `fecha` varchar(45) DEFAULT NULL,
  `lugar` varchar(45) DEFAULT NULL,
  `mantenimiento_realizado` varchar(45) DEFAULT NULL,
  `piezas_cambiadas` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca_vehiculo`
--

CREATE TABLE `marca_vehiculo` (
  `id_marca` int(11) NOT NULL,
  `maca` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta`
--

CREATE TABLE `pregunta` (
  `id_pregunta` int(11) NOT NULL,
  `pregunta` varchar(45) DEFAULT NULL,
  `opcion_respuesta` enum('abierta','cerrada') DEFAULT NULL,
  `id_rama` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `permitido` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ramas`
--

CREATE TABLE `ramas` (
  `id_rama` int(11) NOT NULL,
  `nombre_rama` varchar(255) NOT NULL,
  `numero_rama` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `regla`
--

CREATE TABLE `regla` (
  `id_regla` int(11) NOT NULL,
  `peso-unidad` varchar(45) DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `tarifa` varchar(45) DEFAULT NULL,
  `comision` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` varchar(45) NOT NULL,
  `id_tours` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_reserva` datetime DEFAULT NULL,
  `formaPagoUtilizada` varchar(45) DEFAULT NULL,
  `resultadoTransaccion` varchar(45) DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `enlacePagoId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva_vuelo`
--

CREATE TABLE `reserva_vuelo` (
  `id_cliente` int(11) NOT NULL,
  `id_vuelo` int(11) NOT NULL,
  `fecha` varchar(45) DEFAULT NULL,
  `equipaje_extra` int(11) DEFAULT NULL,
  `adultos` int(11) DEFAULT NULL,
  `niños` int(11) DEFAULT NULL,
  `bebes` int(11) DEFAULT NULL,
  `total` float DEFAULT NULL,
  `descuentos` float DEFAULT NULL,
  `maletas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sitio_turistico`
--

CREATE TABLE `sitio_turistico` (
  `id_sitio_turistico` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `longitud` varchar(45) DEFAULT NULL,
  `latitud` varchar(45) DEFAULT NULL,
  `ubicacion` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `informacion_contacto` varchar(45) DEFAULT NULL,
  `tipo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tours-paquete`
--

CREATE TABLE `tours-paquete` (
  `id_tours` int(11) NOT NULL,
  `nombreTours` varchar(150) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `transporte` varchar(45) DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `lugar_salida` varchar(45) DEFAULT NULL,
  `incluye` varchar(45) DEFAULT NULL,
  `no_incluye` varchar(45) DEFAULT NULL,
  `foto` varchar(45) DEFAULT NULL,
  `promocion` varchar(45) DEFAULT NULL,
  `cupos_disponibles` int(11) DEFAULT NULL,
  `nombre_encargado` varchar(45) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `aprobado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `correo` varchar(45) DEFAULT NULL,
  `pass` varchar(45) DEFAULT NULL,
  `direccioin` varchar(45) DEFAULT NULL,
  `celulaar` varchar(45) DEFAULT NULL,
  `nivel` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE `vehiculo` (
  `idvehiculo` int(11) NOT NULL,
  `id_marca` int(11) NOT NULL,
  `id_agencia` int(11) NOT NULL,
  `modelo` varchar(45) DEFAULT NULL,
  `transmision` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vuelo`
--

CREATE TABLE `vuelo` (
  `id_vuelo` int(11) NOT NULL,
  `precio_turista` varchar(45) DEFAULT NULL,
  `precio_ejecutivo` float DEFAULT NULL,
  `precio_comercial` float DEFAULT NULL,
  `partida` datetime DEFAULT NULL,
  `llegada` datetime DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `foto` varchar(45) DEFAULT NULL,
  `condiciones` varchar(45) DEFAULT NULL,
  `escalas` tinyint(1) DEFAULT NULL,
  `ida_vuelta` tinyint(1) DEFAULT NULL,
  `aerolinea` varchar(45) DEFAULT NULL,
  `costo_maleta` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agencia`
--
ALTER TABLE `agencia`
  ADD PRIMARY KEY (`id_agencia`);

--
-- Indices de la tabla `alquilervehiculo`
--
ALTER TABLE `alquilervehiculo`
  ADD PRIMARY KEY (`idalquilerVehiculo`),
  ADD KEY `fk_alquilerVehiculo_vehiculo1_idx` (`id_vehiculo`),
  ADD KEY `fk_alquilerVehiculo_cliente1_idx` (`id_usuario`);

--
-- Indices de la tabla `asosoria`
--
ALTER TABLE `asosoria`
  ADD PRIMARY KEY (`id_asosoria`),
  ADD KEY `fk_asosoria_usuario1_idx` (`id_usuario`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `cita`
--
ALTER TABLE `cita`
  ADD PRIMARY KEY (`id_-cita`),
  ADD KEY `fk_cita_cliente1_idx` (`id_usuario`);

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id_contactos`);

--
-- Indices de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD PRIMARY KEY (`id_cotizacion`),
  ADD KEY `fk_cotizacion_cliente1_idx` (`id_usuario`);

--
-- Indices de la tabla `detalle_contactos`
--
ALTER TABLE `detalle_contactos`
  ADD KEY `fk_tours_has_contactos_contactos1_idx` (`id_contactos`),
  ADD KEY `fk_tours_has_contactos_tours1_idx` (`id_tours`);

--
-- Indices de la tabla `detalle_encomienda`
--
ALTER TABLE `detalle_encomienda`
  ADD KEY `fk_producto_has_encomienda_encomienda1_idx` (`id_encomienda`),
  ADD KEY `fk_producto_has_encomienda_producto1_idx` (`id_producto`);

--
-- Indices de la tabla `detalle_envio`
--
ALTER TABLE `detalle_envio`
  ADD PRIMARY KEY (`id_detalle_envio`),
  ADD KEY `fk_detalle_envio_encomienda1_idx` (`id_encomienda`);

--
-- Indices de la tabla `encomienda`
--
ALTER TABLE `encomienda`
  ADD PRIMARY KEY (`id_encomienda`),
  ADD KEY `fk_encomienda_cliente1_idx` (`id_usuario`);

--
-- Indices de la tabla `formulario`
--
ALTER TABLE `formulario`
  ADD PRIMARY KEY (`id_formulario`);

--
-- Indices de la tabla `formulario_migratorio`
--
ALTER TABLE `formulario_migratorio`
  ADD PRIMARY KEY (`id_formulario`),
  ADD UNIQUE KEY `id_asesoria` (`id_asesoria`),
  ADD KEY `fk_formulario_migratorio_pregunta1_idx` (`id_pregunta`);

--
-- Indices de la tabla `galeria`
--
ALTER TABLE `galeria`
  ADD PRIMARY KEY (`id_foto`);

--
-- Indices de la tabla `itinerario`
--
ALTER TABLE `itinerario`
  ADD PRIMARY KEY (`id_itinerario`),
  ADD KEY `fk_viaje_tours1_idx` (`id_tours`),
  ADD KEY `fk_itinerario_sitio_turistico1_idx` (`id_sitio_turistico`);

--
-- Indices de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD PRIMARY KEY (`id_mantenimiento`),
  ADD KEY `fk_mantenimiento_vehiculo1_idx` (`id_vehiculo`);

--
-- Indices de la tabla `marca_vehiculo`
--
ALTER TABLE `marca_vehiculo`
  ADD PRIMARY KEY (`id_marca`);

--
-- Indices de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD PRIMARY KEY (`id_pregunta`),
  ADD UNIQUE KEY `id_rama` (`id_rama`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_producto_categoria1_idx` (`id_categoria`);

--
-- Indices de la tabla `ramas`
--
ALTER TABLE `ramas`
  ADD PRIMARY KEY (`id_rama`);

--
-- Indices de la tabla `regla`
--
ALTER TABLE `regla`
  ADD PRIMARY KEY (`id_regla`),
  ADD KEY `fk_regla_producto1_idx` (`id_producto`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `fk_reservaTours_tours1_idx` (`id_tours`),
  ADD KEY `fk_reservaTours_cliente1_idx` (`id_usuario`);

--
-- Indices de la tabla `reserva_vuelo`
--
ALTER TABLE `reserva_vuelo`
  ADD KEY `fk_usuario_has_vuelo_vuelo1_idx` (`id_vuelo`),
  ADD KEY `fk_usuario_has_vuelo_usuario1_idx` (`id_cliente`);

--
-- Indices de la tabla `sitio_turistico`
--
ALTER TABLE `sitio_turistico`
  ADD PRIMARY KEY (`id_sitio_turistico`);

--
-- Indices de la tabla `tours-paquete`
--
ALTER TABLE `tours-paquete`
  ADD PRIMARY KEY (`id_tours`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD PRIMARY KEY (`idvehiculo`),
  ADD KEY `fk_vehiculo_marca_vehiculo1_idx` (`id_marca`),
  ADD KEY `fk_vehiculo_agencia1_idx` (`id_agencia`);

--
-- Indices de la tabla `vuelo`
--
ALTER TABLE `vuelo`
  ADD PRIMARY KEY (`id_vuelo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alquilervehiculo`
--
ALTER TABLE `alquilervehiculo`
  MODIFY `idalquilerVehiculo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id_contactos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id_foto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ramas`
--
ALTER TABLE `ramas`
  MODIFY `id_rama` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tours-paquete`
--
ALTER TABLE `tours-paquete`
  MODIFY `id_tours` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  MODIFY `idvehiculo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alquilervehiculo`
--
ALTER TABLE `alquilervehiculo`
  ADD CONSTRAINT `fk_alquilerVehiculo_cliente1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_alquilerVehiculo_vehiculo1` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculo` (`idvehiculo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `asosoria`
--
ALTER TABLE `asosoria`
  ADD CONSTRAINT `fk_asosoria_usuario1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `fk_cita_cliente1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD CONSTRAINT `fk_cotizacion_cliente1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_contactos`
--
ALTER TABLE `detalle_contactos`
  ADD CONSTRAINT `fk_tours_has_contactos_contactos1` FOREIGN KEY (`id_contactos`) REFERENCES `contactos` (`id_contactos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tours_has_contactos_tours1` FOREIGN KEY (`id_tours`) REFERENCES `tours-paquete` (`id_tours`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_encomienda`
--
ALTER TABLE `detalle_encomienda`
  ADD CONSTRAINT `fk_producto_has_encomienda_encomienda1` FOREIGN KEY (`id_encomienda`) REFERENCES `encomienda` (`id_encomienda`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_has_encomienda_producto1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_envio`
--
ALTER TABLE `detalle_envio`
  ADD CONSTRAINT `fk_detalle_envio_encomienda1` FOREIGN KEY (`id_encomienda`) REFERENCES `encomienda` (`id_encomienda`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `encomienda`
--
ALTER TABLE `encomienda`
  ADD CONSTRAINT `fk_encomienda_cliente1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `formulario_migratorio`
--
ALTER TABLE `formulario_migratorio`
  ADD CONSTRAINT `fk_formulario_migratorio_pregunta1` FOREIGN KEY (`id_pregunta`) REFERENCES `pregunta` (`id_pregunta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `formulario_migratorio_ibfk_1` FOREIGN KEY (`id_asesoria`) REFERENCES `asosoria` (`id_asosoria`);

--
-- Filtros para la tabla `itinerario`
--
ALTER TABLE `itinerario`
  ADD CONSTRAINT `fk_itinerario_sitio_turistico1` FOREIGN KEY (`id_sitio_turistico`) REFERENCES `sitio_turistico` (`id_sitio_turistico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_viaje_tours1` FOREIGN KEY (`id_tours`) REFERENCES `tours-paquete` (`id_tours`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD CONSTRAINT `fk_mantenimiento_vehiculo1` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculo` (`idvehiculo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD CONSTRAINT `pregunta_ibfk_1` FOREIGN KEY (`id_rama`) REFERENCES `ramas` (`id_rama`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_categoria1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `regla`
--
ALTER TABLE `regla`
  ADD CONSTRAINT `fk_regla_producto1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reservaTours_cliente1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reservaTours_tours1` FOREIGN KEY (`id_tours`) REFERENCES `tours-paquete` (`id_tours`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reserva_vuelo`
--
ALTER TABLE `reserva_vuelo`
  ADD CONSTRAINT `fk_usuario_has_vuelo_usuario1` FOREIGN KEY (`id_cliente`) REFERENCES `usuario` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario_has_vuelo_vuelo1` FOREIGN KEY (`id_vuelo`) REFERENCES `vuelo` (`id_vuelo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD CONSTRAINT `fk_vehiculo_agencia1` FOREIGN KEY (`id_agencia`) REFERENCES `agencia` (`id_agencia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vehiculo_marca_vehiculo1` FOREIGN KEY (`id_marca`) REFERENCES `marca_vehiculo` (`id_marca`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
