-- Configuración inicial
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `crud_tareas`
--
CREATE DATABASE IF NOT EXISTS `crud_tareas`;
USE `crud_tareas`;

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Datos iniciales de ejemplo
--

INSERT INTO `tareas` (`nombre`, `descripcion`) VALUES
('Tarea 1', 'hacer un informe detallado de su proyecto de software'),
('Tarea No 3', 'Diseñar los módulos de codificación de su proyecto de software');

--
-- Procedimientos almacenados para el CRUD
--

DELIMITER $$

-- CREATE: Crear nueva tarea
CREATE PROCEDURE `crear_tarea`(
    IN p_nombre VARCHAR(100),
    IN p_descripcion TEXT
)
BEGIN
    INSERT INTO tareas (nombre, descripcion)
    VALUES (p_nombre, p_descripcion);
    SELECT LAST_INSERT_ID() as id;
END$$

-- READ: Obtener todas las tareas
CREATE PROCEDURE `obtener_tareas`()
BEGIN
    SELECT * FROM tareas;
END$$

-- READ: Obtener una tarea específica
CREATE PROCEDURE `obtener_tarea`(
    IN p_id INT
)
BEGIN
    SELECT * FROM tareas WHERE id = p_id;
END$$

-- UPDATE: Actualizar una tarea
CREATE PROCEDURE `modificar_tarea`(
    IN p_id INT,
    IN p_nombre VARCHAR(100),
    IN p_descripcion TEXT
)
BEGIN
    UPDATE tareas 
    SET nombre = p_nombre,
        descripcion = p_descripcion
    WHERE id = p_id;
END$$

-- DELETE: Eliminar una tarea
CREATE PROCEDURE `eliminar_tarea`(
    IN p_id INT
)
BEGIN
    DELETE FROM tareas WHERE id = p_id;
END$$

DELIMITER ;