SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `agencia` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `agencia` ;

-- -----------------------------------------------------
-- Table `agencia`.`tours-paquete`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`tours-paquete` (
  `id_tours` INT NULL AUTO_INCREMENT ,
  `nombreTours` VARCHAR(150) NULL ,
  `descripcion` VARCHAR(45) NULL ,
  `transporte` VARCHAR(45) NULL ,
  `fecha_salida` DATE NULL ,
  `lugar_salida` VARCHAR(45) NULL ,
  `incluye` VARCHAR(45) NULL ,
  `no_incluye` VARCHAR(45) NULL ,
  `foto` VARCHAR(45) NULL ,
  `promocion` VARCHAR(45) NULL ,
  `cupos_disponibles` INT NULL ,
  `nombre_encargado` VARCHAR(45) NULL ,
  `estado` VARCHAR(45) NULL ,
  `tipo` VARCHAR(45) NULL ,
  `aprobado` TINYINT(1) NULL ,
  PRIMARY KEY (`id_tours`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`contactos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`contactos` (
  `id_contactos` INT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(150) NULL ,
  `telefono` VARCHAR(45) NULL ,
  `descripcion_servicio` VARCHAR(45) NULL ,
  `tipo_servicio` VARCHAR(45) NULL ,
  `costos` FLOAT NULL ,
  `foto` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_contactos`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`marca_vehiculo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`marca_vehiculo` (
  `id_marca` INT NOT NULL ,
  `maca` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_marca`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`agencia`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`agencia` (
  `id_agencia` INT NOT NULL ,
  `nombre` VARCHAR(45) NULL ,
  `lugar` VARCHAR(45) NULL ,
  `descripcion` VARCHAR(45) NULL ,
  `usuario` VARCHAR(45) NULL ,
  `contraseña` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_agencia`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`vehiculo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`vehiculo` (
  `idvehiculo` INT NULL AUTO_INCREMENT ,
  `id_marca` INT NOT NULL ,
  `id_agencia` INT NOT NULL ,
  `modelo` VARCHAR(45) NULL ,
  `transmision` VARCHAR(45) NULL ,
  `` VARCHAR(45) NULL ,
  PRIMARY KEY (`idvehiculo`) ,
  INDEX `fk_vehiculo_marca_vehiculo1_idx` (`id_marca` ASC) ,
  INDEX `fk_vehiculo_agencia1_idx` (`id_agencia` ASC) ,
  CONSTRAINT `fk_vehiculo_marca_vehiculo1`
    FOREIGN KEY (`id_marca` )
    REFERENCES `agencia`.`marca_vehiculo` (`id_marca` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehiculo_agencia1`
    FOREIGN KEY (`id_agencia` )
    REFERENCES `agencia`.`agencia` (`id_agencia` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`usuario`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`usuario` (
  `id_cliente` INT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NULL ,
  `correo` VARCHAR(45) NULL ,
  `pass` VARCHAR(45) NULL ,
  `direccioin` VARCHAR(45) NULL ,
  `celulaar` VARCHAR(45) NULL ,
  `nivel` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_cliente`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`reserva`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`reserva` (
  `id_reserva` VARCHAR(45) NOT NULL ,
  `id_tours` INT NOT NULL ,
  `id_usuario` INT NOT NULL ,
  `fecha_reserva` DATETIME NULL ,
  `formaPagoUtilizada` VARCHAR(45) NULL ,
  `resultadoTransaccion` VARCHAR(45) NULL ,
  `monto` DOUBLE NULL ,
  `cantidad` INT NULL ,
  `enlacePagoId` INT NULL ,
  INDEX `fk_reservaTours_tours1_idx` (`id_tours` ASC) ,
  INDEX `fk_reservaTours_cliente1_idx` (`id_usuario` ASC) ,
  PRIMARY KEY (`id_reserva`) ,
  CONSTRAINT `fk_reservaTours_tours1`
    FOREIGN KEY (`id_tours` )
    REFERENCES `agencia`.`tours-paquete` (`id_tours` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reservaTours_cliente1`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `agencia`.`usuario` (`id_cliente` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`alquilerVehiculo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`alquilerVehiculo` (
  `idalquilerVehiculo` INT NULL AUTO_INCREMENT ,
  `id_vehiculo` INT NOT NULL ,
  `id_usuario` INT NOT NULL ,
  PRIMARY KEY (`idalquilerVehiculo`) ,
  INDEX `fk_alquilerVehiculo_vehiculo1_idx` (`id_vehiculo` ASC) ,
  INDEX `fk_alquilerVehiculo_cliente1_idx` (`id_usuario` ASC) ,
  CONSTRAINT `fk_alquilerVehiculo_vehiculo1`
    FOREIGN KEY (`id_vehiculo` )
    REFERENCES `agencia`.`vehiculo` (`idvehiculo` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_alquilerVehiculo_cliente1`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `agencia`.`usuario` (`id_cliente` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`sitio_turistico`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`sitio_turistico` (
  `id_sitio_turistico` INT NOT NULL ,
  `nombre` VARCHAR(45) NULL ,
  `longitud` VARCHAR(45) NULL ,
  `latitud` VARCHAR(45) NULL ,
  `ubicacion` VARCHAR(45) NULL ,
  `descripcion` VARCHAR(45) NULL ,
  `informacion_contacto` VARCHAR(45) NULL ,
  `tipo` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_sitio_turistico`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`itinerario`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`itinerario` (
  `id_itinerario` INT NOT NULL ,
  `id_tours` INT NOT NULL ,
  `id_sitio_turistico` INT NOT NULL ,
  `dia` INT NULL ,
  `hora` TIME NULL ,
  `fecha` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_itinerario`) ,
  INDEX `fk_viaje_tours1_idx` (`id_tours` ASC) ,
  INDEX `fk_itinerario_sitio_turistico1_idx` (`id_sitio_turistico` ASC) ,
  CONSTRAINT `fk_viaje_tours1`
    FOREIGN KEY (`id_tours` )
    REFERENCES `agencia`.`tours-paquete` (`id_tours` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_itinerario_sitio_turistico1`
    FOREIGN KEY (`id_sitio_turistico` )
    REFERENCES `agencia`.`sitio_turistico` (`id_sitio_turistico` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`mantenimiento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`mantenimiento` (
  `id_mantenimiento` INT NOT NULL ,
  `id_vehiculo` INT NOT NULL ,
  `fecha` VARCHAR(45) NULL ,
  `lugar` VARCHAR(45) NULL ,
  `mantenimiento_realizado` VARCHAR(45) NULL ,
  `piezas_cambiadas` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_mantenimiento`) ,
  INDEX `fk_mantenimiento_vehiculo1_idx` (`id_vehiculo` ASC) ,
  CONSTRAINT `fk_mantenimiento_vehiculo1`
    FOREIGN KEY (`id_vehiculo` )
    REFERENCES `agencia`.`vehiculo` (`idvehiculo` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`formulario`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`formulario` (
  `id_formulario` INT NOT NULL ,
  `clusulas` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_formulario`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`categoria`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`categoria` (
  `id_categoria` INT NOT NULL ,
  `nombre` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_categoria`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`producto`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`producto` (
  `id_producto` INT NOT NULL ,
  `id_categoria` INT NOT NULL ,
  `nombre` VARCHAR(45) NULL ,
  `permitido` TINYINT(1) NULL ,
  PRIMARY KEY (`id_producto`) ,
  INDEX `fk_producto_categoria1_idx` (`id_categoria` ASC) ,
  CONSTRAINT `fk_producto_categoria1`
    FOREIGN KEY (`id_categoria` )
    REFERENCES `agencia`.`categoria` (`id_categoria` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`regla`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`regla` (
  `id_regla` INT NOT NULL ,
  `peso-unidad` VARCHAR(45) NULL ,
  `id_producto` INT NOT NULL ,
  `tarifa` VARCHAR(45) NULL ,
  `comision` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_regla`) ,
  INDEX `fk_regla_producto1_idx` (`id_producto` ASC) ,
  CONSTRAINT `fk_regla_producto1`
    FOREIGN KEY (`id_producto` )
    REFERENCES `agencia`.`producto` (`id_producto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`encomienda`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`encomienda` (
  `id_encomienda` INT NOT NULL ,
  `id_usuario` INT NOT NULL ,
  `direccion` VARCHAR(45) NULL ,
  `costo` VARCHAR(45) NULL ,
  `estado` VARCHAR(45) NULL ,
  `fecha` VARCHAR(45) NULL ,
  `destino_final` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_encomienda`) ,
  INDEX `fk_encomienda_cliente1_idx` (`id_usuario` ASC) ,
  CONSTRAINT `fk_encomienda_cliente1`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `agencia`.`usuario` (`id_cliente` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`detalle_encomienda`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`detalle_encomienda` (
  `id_producto` INT NOT NULL ,
  `id_encomienda` INT NOT NULL ,
  `cantidad` VARCHAR(45) NULL ,
  INDEX `fk_producto_has_encomienda_encomienda1_idx` (`id_encomienda` ASC) ,
  INDEX `fk_producto_has_encomienda_producto1_idx` (`id_producto` ASC) ,
  CONSTRAINT `fk_producto_has_encomienda_producto1`
    FOREIGN KEY (`id_producto` )
    REFERENCES `agencia`.`producto` (`id_producto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_has_encomienda_encomienda1`
    FOREIGN KEY (`id_encomienda` )
    REFERENCES `agencia`.`encomienda` (`id_encomienda` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`detalle_envio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`detalle_envio` (
  `id_detalle_envio` INT NOT NULL ,
  `id_encomienda` INT NOT NULL ,
  `fecha` VARCHAR(45) NULL ,
  `hora` VARCHAR(45) NULL ,
  `lugar` VARCHAR(45) NULL ,
  `descripcion` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_detalle_envio`) ,
  INDEX `fk_detalle_envio_encomienda1_idx` (`id_encomienda` ASC) ,
  CONSTRAINT `fk_detalle_envio_encomienda1`
    FOREIGN KEY (`id_encomienda` )
    REFERENCES `agencia`.`encomienda` (`id_encomienda` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`cotizacion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`cotizacion` (
  `id_cotizacion` INT NOT NULL ,
  `id_usuario` INT NOT NULL ,
  `descripcion` VARCHAR(45) NULL ,
  `fecha` VARCHAR(45) NULL ,
  `totol` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_cotizacion`) ,
  INDEX `fk_cotizacion_cliente1_idx` (`id_usuario` ASC) ,
  CONSTRAINT `fk_cotizacion_cliente1`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `agencia`.`usuario` (`id_cliente` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`vuelo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`vuelo` (
  `id_vuelo` INT NOT NULL ,
  `precio_turista` VARCHAR(45) NULL ,
  `precio_ejecutivo` FLOAT NULL ,
  `precio_comercial` FLOAT NULL ,
  `partida` DATETIME NULL ,
  `llegada` DATETIME NULL ,
  `estado` VARCHAR(45) NULL ,
  `foto` VARCHAR(45) NULL ,
  `condiciones` VARCHAR(45) NULL ,
  `escalas` TINYINT(1) NULL ,
  `ida_vuelta` TINYINT(1) NULL ,
  `aerolinea` VARCHAR(45) NULL ,
  `costo_maleta` FLOAT NULL ,
  PRIMARY KEY (`id_vuelo`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`cita`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`cita` (
  `id_-cita` INT NOT NULL ,
  `id_usuario` INT NOT NULL ,
  `fecha` VARCHAR(45) NULL ,
  `hora` VARCHAR(45) NULL ,
  `estado` VARCHAR(45) NULL ,
  `motivo` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_-cita`) ,
  INDEX `fk_cita_cliente1_idx` (`id_usuario` ASC) ,
  CONSTRAINT `fk_cita_cliente1`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `agencia`.`usuario` (`id_cliente` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`pregunta`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`pregunta` (
  `id_pregunta` INT NOT NULL ,
  `pregunta` VARCHAR(45) NULL ,
  `respuesta` VARCHAR(45) NULL ,
  `tipo` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_pregunta`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`formulario_migratorio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`formulario_migratorio` (
  `id_formulario` VARCHAR(45) NOT NULL ,
  `id_pregunta` INT NOT NULL ,
  `nombre` VARCHAR(45) NULL ,
  INDEX `fk_formulario_migratorio_pregunta1_idx` (`id_pregunta` ASC) ,
  PRIMARY KEY (`id_formulario`) ,
  CONSTRAINT `fk_formulario_migratorio_pregunta1`
    FOREIGN KEY (`id_pregunta` )
    REFERENCES `agencia`.`pregunta` (`id_pregunta` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`asosoria`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`asosoria` (
  `id_asosoria` INT NOT NULL ,
  `id_usuario` INT NOT NULL ,
  `id_formulario` VARCHAR(45) NOT NULL ,
  `fecha` VARCHAR(45) NULL ,
  `otros_datos` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_asosoria`) ,
  INDEX `fk_asosoria_usuario1_idx` (`id_usuario` ASC) ,
  INDEX `fk_asosoria_formulario_migratorio1_idx` (`id_formulario` ASC) ,
  CONSTRAINT `fk_asosoria_usuario1`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `agencia`.`usuario` (`id_cliente` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_asosoria_formulario_migratorio1`
    FOREIGN KEY (`id_formulario` )
    REFERENCES `agencia`.`formulario_migratorio` (`id_formulario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`foto`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`foto` (
  `id_foto` INT NOT NULL ,
  `id_sitio_turistico` INT NOT NULL ,
  `foto_path` VARCHAR(45) NULL ,
  `principal` TINYINT(1) NULL ,
  PRIMARY KEY (`id_foto`) ,
  INDEX `fk_foto_sitio_turistico1_idx` (`id_sitio_turistico` ASC) ,
  CONSTRAINT `fk_foto_sitio_turistico1`
    FOREIGN KEY (`id_sitio_turistico` )
    REFERENCES `agencia`.`sitio_turistico` (`id_sitio_turistico` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`detalle_contactos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`detalle_contactos` (
  `id_tours` INT NOT NULL ,
  `id_contactos` INT NOT NULL ,
  INDEX `fk_tours_has_contactos_contactos1_idx` (`id_contactos` ASC) ,
  INDEX `fk_tours_has_contactos_tours1_idx` (`id_tours` ASC) ,
  CONSTRAINT `fk_tours_has_contactos_tours1`
    FOREIGN KEY (`id_tours` )
    REFERENCES `agencia`.`tours-paquete` (`id_tours` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tours_has_contactos_contactos1`
    FOREIGN KEY (`id_contactos` )
    REFERENCES `agencia`.`contactos` (`id_contactos` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agencia`.`reserva_vuelo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agencia`.`reserva_vuelo` (
  `id_cliente` INT NOT NULL ,
  `id_vuelo` INT NOT NULL ,
  `fecha` VARCHAR(45) NULL ,
  `equipaje_extra` INT NULL ,
  `adultos` INT NULL ,
  `niños` INT NULL ,
  `bebes` INT NULL ,
  `total` FLOAT NULL ,
  `descuentos` FLOAT NULL ,
  `maletas` INT NULL ,
  INDEX `fk_usuario_has_vuelo_vuelo1_idx` (`id_vuelo` ASC) ,
  INDEX `fk_usuario_has_vuelo_usuario1_idx` (`id_cliente` ASC) ,
  CONSTRAINT `fk_usuario_has_vuelo_usuario1`
    FOREIGN KEY (`id_cliente` )
    REFERENCES `agencia`.`usuario` (`id_cliente` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_vuelo_vuelo1`
    FOREIGN KEY (`id_vuelo` )
    REFERENCES `agencia`.`vuelo` (`id_vuelo` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `agencia` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
