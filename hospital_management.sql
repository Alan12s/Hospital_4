-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 12:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `anestesistas`
--

CREATE TABLE `anestesistas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `disponibilidad` enum('disponible','no_disponible','en_cirugia') DEFAULT 'disponible',
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anestesistas`
--

INSERT INTO `anestesistas` (`id`, `nombre`, `especialidad`, `disponibilidad`, `telefono`, `email`) VALUES
(1, 'Dra. Laura Méndez', 'Anestesiología General', 'disponible', '1122334455', 'l.mendez@hospital.com'),
(3, 'Dra. Adriana Castro', 'Anestesiología Pediátrica', 'disponible', '1144556677', 'a.castro@hospital.com'),
(4, 'Dr. Marcos Vidal', 'Anestesiología en Dolor Crónico', 'disponible', '1155667788', 'm.vidal@hospital.com'),
(5, 'Dra. Silvia Paredes', 'Anestesiología General', 'disponible', '1166778899', 's.paredes@hospital.com'),
(7, 'Enzo Cordoba', 'Anestesiología General', 'disponible', '2643418049', 'enzo@hospital.com'),
(8, 'Dr. Luis Miguel', 'Anestesiología General', 'disponible', '2645556709', 'luism@hospital.com');

-- --------------------------------------------------------

--
-- Table structure for table `cirujanos`
--

CREATE TABLE `cirujanos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `disponibilidad` enum('disponible','no_disponible','en_cirugia') DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cirujanos`
--

INSERT INTO `cirujanos` (`id`, `nombre`, `dni`, `id_especialidad`, `telefono`, `email`, `created_at`, `updated_at`, `disponibilidad`) VALUES
(1, 'Dr. Carlos Mendoza', '28456789', 1, '1156789234', 'c.mendoza@hospital.com', '2025-05-08 04:50:38', NULL, 'disponible'),
(3, 'Dr. Javier Torres', '27456321', 4, '1167892345', 'j.torres@hospital.com', '2025-05-09 07:30:06', '2025-05-18 00:49:31', 'disponible'),
(6, 'Dra. Patricia Vargas', '33456784', 2, '1192345678', 'p.vargas@hospital.com', '2025-05-09 07:30:06', NULL, 'disponible'),
(7, 'Dr. Roberto Jiménez', '30456785', 3, '1123456789', 'r.jimenez@hospital.com', '2025-05-09 07:30:06', NULL, 'disponible'),
(8, 'Dra. Carmen Ruiz', '32456786', 4, '1134567890', 'c.ruiz@hospital.com', '2025-05-09 07:30:06', '2025-05-18 00:49:13', 'disponible'),
(11, 'Dr. Fernando Ríos', '27456789', 5, '1167890123', 'f.rios@hospital.com', '2025-05-09 07:30:06', NULL, 'disponible'),
(16, 'Victoria Lozano', '33784130', 1, '2641234539', 'victoria@hospital.com', '2025-05-17 16:18:51', NULL, 'disponible'),
(17, 'Maria Mendoza', '34798455', 2, '2641234509', 'mariam@hospital.com', '2025-05-20 14:44:01', NULL, 'disponible'),
(18, 'Luis Gomez', '40390567', 5, '2649993412', 'luis@hospital.com', '2025-06-08 17:47:37', '2025-06-08 17:47:37', 'disponible');

-- --------------------------------------------------------

--
-- Table structure for table `enfermeros`
--

CREATE TABLE `enfermeros` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `especialidad` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `disponibilidad` enum('disponible','no_disponible','en_cirugia') DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enfermeros`
--

INSERT INTO `enfermeros` (`id`, `nombre`, `dni`, `especialidad`, `telefono`, `email`, `fecha_ingreso`, `disponibilidad`) VALUES
(1, 'María López', '30123456', 'Enfermería Quirúrgica', '1122334455', 'm.lopez@hospital.com', '2020-05-15', 'disponible'),
(2, 'Carlos Rojas', '28987654', 'Enfermería de Urgencias', '1155667788', 'c.rojas@hospital.com', '2019-03-10', 'disponible'),
(3, 'Ana Martínez', '33445566', 'Enfermería Pediátrica', '1199887766', 'a.martinez@hospital.com', '2022-01-20', 'disponible'),
(7, 'Macarena Bustos', '40633924', 'Enfermería Quirurgica', '2646552093', 'macarena@hospital.com', NULL, 'disponible'),
(8, 'Luis Tello', '39453981', 'Enfermería Quirurgica', '2642349012', 'luist@hospital.com', NULL, 'disponible'),
(9, 'Luis Guerra', '37238001', 'Enfermería de Urgencias', '2646729012', 'luisg@gmail.com', NULL, 'disponible'),
(10, 'Miguel Arjona', '30458231', 'Enfermería Quirúrgica', '2648790121', 'miguel@hospital.com', NULL, 'disponible'),
(11, 'Maria Nuñes', '40390560', 'Enfermería Pediátrica', '2645657899', 'marias@hospital.com', '2025-06-04', 'disponible'),
(12, 'Alexandro Brizuela', '38903123', 'Enfermería de Urgencias', '2645318901', 'alex@hospital.com', '2025-02-05', 'disponible');

-- --------------------------------------------------------

--
-- Table structure for table `especialidades`
--

CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `especialidades`
--

INSERT INTO `especialidades` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Cirugía General', 'Especialidad médica que se ocupa de las operaciones quirúrgicas'),
(2, 'Ginecología', 'Especialidad médica que trata la salud del aparato reproductor femenino'),
(3, 'Traumatología', 'Especialidad médica que trata lesiones del sistema musculoesquelético'),
(4, 'Urología', 'Especialidad médica que trata el sistema urinario y reproductor masculino'),
(5, 'Odontología', 'Especialidad médica que trata la salud bucodental');

-- --------------------------------------------------------

--
-- Table structure for table `instrumentistas`
--

CREATE TABLE `instrumentistas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `especialidad` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `disponibilidad` enum('disponible','no_disponible','en_cirugia') DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instrumentistas`
--

INSERT INTO `instrumentistas` (`id`, `nombre`, `dni`, `especialidad`, `telefono`, `email`, `fecha_ingreso`, `disponibilidad`) VALUES
(2, 'Dr. Leon Kennedy', '33089333', 'Instrumentista Quirúrgico General', '2645659081', 'kennedy@hospital.com', '2025-06-25', 'disponible');

-- --------------------------------------------------------

--
-- Table structure for table `insumos`
--

CREATE TABLE `insumos` (
  `id_insumo` int(11) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `nombre` varchar(70) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `lote` varchar(100) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `tiene_vencimiento` tinyint(1) NOT NULL DEFAULT 0,
  `ubicacion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insumos`
--

INSERT INTO `insumos` (`id_insumo`, `codigo`, `nombre`, `tipo`, `cantidad`, `lote`, `fecha_vencimiento`, `tiene_vencimiento`, `ubicacion`) VALUES
(1, 'INS-001', 'Guantes quirúrgicos estériles', 'Consumible', 500, 'LOT-2025-01', '2028-09-30', 1, 'Almacén A, Estante 1'),
(2, 'INS-002', 'Jeringas 10ml', 'Consumible', 300, 'LOT-2025-02', '2029-01-30', 1, 'Almacén A, Estante 2'),
(3, 'INS-003', 'Gasas estériles 10x10', 'Consumible', 200, 'LOT-2025-03', '2027-09-15', 1, 'Almacén B, Estante 1'),
(5, 'INS-005', 'Mascarillas N95', 'Protección personal', 400, 'LOT-2025-05', '2028-01-30', 1, 'Almacén C, Estante 1'),
(6, 'INS-006', 'Bisturí #10', 'Instrumental', 50, 'LOT-2025-06', '2027-01-31', 1, 'Almacén C, Estante 2'),
(7, 'INS-007', 'Catéter intravenoso 18G', 'Material quirúrgico', 250, 'LOT-2025-07', '2028-11-30', 1, 'Almacén D, Estante 1'),
(9, 'INS-009', 'Bisturi', 'Material quirúrgico', 30, 'LOT-2025-09', '2028-06-18', 1, 'Almacen A, Estante 1'),
(10, 'INS-010', 'Gasa', 'Material quirúrgico', 20, 'LOTE-20250518-0010', '2027-12-18', 1, 'Almacen A, Estante 3'),
(11, 'INS-47BAAE', 'Cateter', 'Material quirúrgico', 230, 'LOTE-20250518-0011', '2027-10-18', 1, 'Almacen B, Estante 1'),
(12, 'INS-556556', 'Pinzas Desechables', 'Material quirúrgico', 100, 'LOTE-20250519-0012', '2028-06-18', 1, 'Almacen A, Estante 1');

-- --------------------------------------------------------

--
-- Table structure for table `insumos_cirugia`
--

CREATE TABLE `insumos_cirugia` (
  `id` int(11) NOT NULL,
  `id_turno` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `registrado_por` int(11) DEFAULT NULL COMMENT 'ID del usuario que registró el insumo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `accion` varchar(255) NOT NULL,
  `tabla` varchar(100) NOT NULL,
  `datos` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `usuario_id`, `accion`, `tabla`, `datos`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'Actualización de enfermero', 'enfermeros', '{\"id\":\"7\",\"data\":{\"nombre\":\"Macarena Bustos\",\"dni\":\"40633924\",\"especialidad\":\"Enfermer??a quirurgica\",\"telefono\":\"2646552093\",\"email\":\"macarena@hospital.com\",\"disponibilidad\":\"disponible\"}}', '::1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-05-16 19:02:30'),
(2, 1, 'Actualización de enfermero', 'enfermeros', '{\"id\":\"7\",\"data\":{\"nombre\":\"Macarena Bustos\",\"dni\":\"40633924\",\"especialidad\":\"Enfermer\\u00eda quirurgica\",\"telefono\":\"2646552093\",\"email\":\"macarena@hospital.com\",\"disponibilidad\":\"disponible\"}}', '::1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-05-16 19:14:23'),
(3, 1, 'Actualización de enfermero', 'enfermeros', '{\"id\":\"7\",\"data\":{\"nombre\":\"Macarena Bustos\",\"dni\":\"40633924\",\"especialidad\":\"Enfermería Quirurgica\",\"telefono\":\"2646552093\",\"email\":\"macarena@hospital.com\",\"disponibilidad\":\"disponible\"}}', '::1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-05-16 19:15:03'),
(4, 1, 'Actualización de enfermero', 'enfermeros', '{\"id\":\"7\",\"data\":{\"nombre\":\"Macarena Bustos\",\"dni\":\"40633924\",\"especialidad\":\"Enfermería Quirurgica\",\"telefono\":\"2646552093\",\"email\":\"macarena@hospital.com\",\"disponibilidad\":\"disponible\"}}', '::1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-05-16 19:16:13'),
(5, 1, 'Actualización de enfermero', 'enfermeros', '{\"id\":\"7\",\"data\":{\"nombre\":\"Macarena Bustos\",\"dni\":\"40633924\",\"especialidad\":\"Enfermeria quirurgica\",\"telefono\":\"2646552093\",\"email\":\"macarena@hospital.com\",\"disponibilidad\":\"disponible\"}}', '::1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-05-16 19:23:43'),
(6, 1, 'Creación de enfermero', 'enfermeros', '{\"nombre\":\"Jose Burgoa\",\"dni\":\"44444444\",\"especialidad\":\"Enfermería Quirurgica\",\"telefono\":\"2645552093\",\"email\":\"joses@gmail.com\",\"disponibilidad\":\"disponible\"}', '::1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-05-16 19:48:14');

-- --------------------------------------------------------

--
-- Table structure for table `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `historial_medico` text DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `obra_social` varchar(100) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pacientes`
--

INSERT INTO `pacientes` (`id`, `nombre`, `edad`, `historial_medico`, `dni`, `telefono`, `direccion`, `email`, `obra_social`, `departamento`, `fecha_nacimiento`) VALUES
(1, 'Juan Pérez', 35, 'Alergia a la penicilina, hipertensión controlada', '30123456', '1122334455', 'Calle Falsa 123', 'juan.perez@example.com', 'Obra Social Provincia', 'Sarmiento', '1990-01-10'),
(2, 'María González', 28, 'Asma leve, vacunas al día', '28987654', '1155667788', 'Av. Siempreviva 456', 'maria.gonzalez@example.com', 'No tiene', 'Caucete', '1983-06-10'),
(3, 'Carlos López', 42, 'Diabetes tipo 2, cirugía de apéndice 2010', '33445566', '1199887766', 'Calle Real 789', 'carlos.lopez@example.com', 'No tiene', 'Sarmiento', '1989-02-08'),
(4, 'Ana Martínez', 50, 'Artritis reumatoide, control anual', '32123456', '1133445566', 'Av. Libertad 333', 'ana.martinez@example.com', 'Obra Social Provincia', '25 de mayo', '1989-06-17'),
(5, 'Pedro Sánchez', 22, 'Ninguna condición conocida', '22234567', '1144556677', 'Calle Nueva 654', 'pedro.sanchez@example.com', 'PAMI', '25 de mayo', '1970-04-02');

-- --------------------------------------------------------

--
-- Table structure for table `procedimientos`
--

CREATE TABLE `procedimientos` (
  `id` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `procedimientos`
--

INSERT INTO `procedimientos` (`id`, `id_especialidad`, `nombre`) VALUES
(1, 1, 'Apendicectomía (extirpación del apéndice)'),
(2, 1, 'Colecistectomía (extirpación de la vesícula biliar)'),
(3, 1, 'Herniorrafias (reparación de hernias inguinales, umbilicales, etc.)'),
(4, 1, 'Cirugía intestinal (resecciones, anastomosis)'),
(5, 1, 'Cirugía gástrica (gastrectomía parcial o total)'),
(6, 2, 'Histerectomía (extirpación del útero)'),
(7, 2, 'Salpingooforectomía (extirpación de trompas y ovarios)'),
(8, 2, 'Laparoscopias ginecológicas diagnósticas y terapéuticas'),
(9, 2, 'Cirugía de quistes ováricos'),
(10, 2, 'Conización cervical'),
(11, 3, 'Reducción y fijación de fracturas'),
(12, 3, 'Artroplastias (reemplazo de articulaciones)'),
(13, 3, 'Artroscopias diagnósticas y terapéuticas'),
(14, 3, 'Reparación de ligamentos y tendones'),
(15, 3, 'Cirugía de columna vertebral'),
(16, 3, 'Reparación de lesiones meniscales'),
(17, 4, 'Prostatectomía (extirpación total o parcial de la próstata)'),
(18, 4, 'Nefrectomía (extirpación del riñón)'),
(19, 4, 'Cistectomía (extirpación de la vejiga)'),
(20, 4, 'Circuncisión'),
(21, 4, 'Vasectomía'),
(22, 4, 'Cirugía para incontinencia urinaria'),
(23, 5, 'Extracción de piezas dentales complejas'),
(24, 5, 'Cirugía de terceros molares (muelas del juicio)'),
(25, 5, 'Cirugía ortognática (corrección de malformaciones maxilofaciales)'),
(26, 5, 'Implantes dentales'),
(27, 5, 'Injertos óseos en maxilares');

-- --------------------------------------------------------

--
-- Table structure for table `quirofanos`
--

CREATE TABLE `quirofanos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('activo','inactivo','mantenimiento') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quirofanos`
--

INSERT INTO `quirofanos` (`id`, `nombre`, `descripcion`, `estado`) VALUES
(1, 'Quirófano 1', 'Quirófano principal para cirugías generales', 'activo'),
(2, 'Quirófano 2', 'Quirófano para cirugías de menor complejidad', 'activo');

-- --------------------------------------------------------

--
-- Table structure for table `turnos_insumos`
--

CREATE TABLE `turnos_insumos` (
  `id` int(11) NOT NULL,
  `id_turno` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `turnos_quirurgicos`
--

CREATE TABLE `turnos_quirurgicos` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_finalizacion` time DEFAULT NULL,
  `id_quirofano` int(11) NOT NULL,
  `id_cirujano` int(11) NOT NULL,
  `id_cirujano_ayudante` int(11) DEFAULT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_anestesista` int(11) DEFAULT NULL,
  `id_enfermero` int(11) DEFAULT NULL,
  `id_instrumentador_principal` int(11) DEFAULT NULL,
  `id_instrumentador_circulante` int(11) DEFAULT NULL,
  `id_tecnico_anestesista` int(11) DEFAULT NULL,
  `tipo_anestesia` varchar(100) DEFAULT NULL,
  `complicaciones` text DEFAULT NULL,
  `procedimiento` varchar(255) NOT NULL,
  `estado` enum('programado','en_proceso','completado','cancelado','agendada','urgencia') DEFAULT 'programado',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `turnos_quirurgicos`
--

INSERT INTO `turnos_quirurgicos` (`id`, `fecha`, `hora_inicio`, `hora_finalizacion`, `id_quirofano`, `id_cirujano`, `id_cirujano_ayudante`, `id_paciente`, `id_anestesista`, `id_enfermero`, `id_instrumentador_principal`, `id_instrumentador_circulante`, `id_tecnico_anestesista`, `tipo_anestesia`, `complicaciones`, `procedimiento`, `estado`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, '2025-05-22', '15:00:00', NULL, 1, 1, NULL, 3, 1, 1, NULL, NULL, NULL, NULL, NULL, 'Apendicectomía', 'programado', 'Paciente alergico a la penicilina', '2025-05-13 21:16:37', '2025-05-13 21:17:21'),
(2, '2025-05-30', '16:00:00', NULL, 2, 1, NULL, 2, 5, 2, NULL, NULL, NULL, NULL, NULL, 'Colecistectomía (extirpación de la vesícula biliar)', 'programado', '--', '2025-05-16 14:15:40', '2025-05-18 23:10:53'),
(3, '2025-05-29', '16:00:00', NULL, 1, 7, NULL, 2, NULL, 2, NULL, NULL, NULL, NULL, NULL, 'Reducción y fijación de fracturas', 'cancelado', '--', '2025-05-17 13:54:02', '2025-05-17 16:06:08'),
(4, '2025-06-07', '08:00:00', NULL, 1, 7, NULL, 5, 4, 2, NULL, NULL, NULL, NULL, NULL, 'Reducción y fijación de fracturas', 'programado', '', '2025-05-17 16:06:51', '2025-05-17 16:06:51'),
(5, '2025-06-20', '13:00:00', NULL, 1, 11, NULL, 4, 1, 3, NULL, NULL, NULL, NULL, NULL, 'Cirugía de terceros molares (muelas del juicio)', 'programado', '--', '2025-05-18 23:14:27', '2025-05-18 23:14:27'),
(7, '2025-05-19', '19:00:00', NULL, 2, 7, NULL, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'Reparación de lesiones meniscales', 'cancelado', '', '2025-05-19 21:10:04', '2025-05-20 15:10:59'),
(8, '2025-05-20', '09:00:00', NULL, 1, 3, NULL, 3, 4, 8, NULL, NULL, NULL, NULL, NULL, 'Cirugía para incontinencia urinaria', 'programado', '', '2025-05-20 11:51:50', '2025-05-20 11:51:50'),
(9, '2025-05-30', '12:00:00', NULL, 1, 11, NULL, 1, 7, 9, NULL, NULL, NULL, NULL, NULL, 'Extracción de piezas dentales complejas', 'cancelado', '', '2025-05-20 15:12:02', '2025-05-20 15:12:27'),
(10, '2025-05-29', '12:00:00', NULL, 2, 3, NULL, 1, 7, 9, NULL, NULL, NULL, NULL, NULL, 'Cirugía para incontinencia urinaria', 'programado', '', '2025-05-20 15:12:59', '2025-05-20 15:12:59');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL,
  `ultimo_acceso` datetime DEFAULT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'usuario',
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `username`, `fecha_registro`, `fecha_actualizacion`, `ultimo_acceso`, `rol`, `estado`, `password`, `remember_token`, `reset_token`, `reset_expires`) VALUES
(1, 'Ramon', 'Areyuna', 'admin@hospital.com', 'admin', '2025-04-17 22:36:48', NULL, '2025-06-18 21:31:27', 'administrador', 1, '$2y$10$d68qdYU5o0GoL11xeayc7.seuNo7AF56cGxaoaMrEzoylfEVBFMTO', '047d35f49e26d7caa7bee79ecd1e636996b31437cf2c2c5755e90a594b639b19', 'e046c6d3cb97e1e62eead996f03128a78b37371c2ce825c137b56171a826a17f', '2025-04-19 22:37:33'),
(2, 'Maria', 'Herrera', 'maria@hospital.com', 'enfermera', '2025-04-19 21:23:13', NULL, NULL, 'enfermero', 1, '$2y$10$PGiSgn509EfsxTbc5hV9dOyPQb7cy46onNvsimJTP2p4GywWylA.C', NULL, NULL, NULL),
(5, 'Manuel', 'Gonzales', 'manuel@hospital.com', 'supervisor', '2025-04-19 21:29:55', NULL, '2025-05-03 17:14:39', 'supervisor', 1, '$2y$10$WakpL4f6cWWR0HfOn.Auc.jPNFy/mYKYSSem/vkvEZhkmaQ6IkZEC', NULL, NULL, NULL),
(6, 'Pablo', 'Alboran', 'pablo@hospital.com', 'enfermero', '2025-05-03 17:23:48', NULL, '2025-05-16 05:45:49', 'enfermero', 1, '$2y$10$iqggD1A3D0qL8eDygSs.5uyFsfWuyGHg.onOQlKwfidp4ZxEn2pPm', NULL, NULL, NULL),
(9, 'Lujan Ana', 'Cordoba', 'lujan@hospital.com', 'lujan', '2025-06-15 00:11:47', '2025-06-15 00:12:56', '2025-06-15 00:13:10', 'cirujano', 1, '$2y$10$t2MeMCk.xuk5soeKkQhmPulOItssxXFL0TfuQLDXK0JLxsbtGKRH6', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anestesistas`
--
ALTER TABLE `anestesistas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cirujanos`
--
ALTER TABLE `cirujanos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_medico_especialidad` (`id_especialidad`);

--
-- Indexes for table `enfermeros`
--
ALTER TABLE `enfermeros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `instrumentistas`
--
ALTER TABLE `instrumentistas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `insumos`
--
ALTER TABLE `insumos`
  ADD PRIMARY KEY (`id_insumo`);

--
-- Indexes for table `insumos_cirugia`
--
ALTER TABLE `insumos_cirugia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_turno` (`id_turno`),
  ADD KEY `id_insumo` (`id_insumo`),
  ADD KEY `registrado_por` (`registrado_por`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `procedimientos`
--
ALTER TABLE `procedimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_procedimiento_especialidad` (`id_especialidad`);

--
-- Indexes for table `quirofanos`
--
ALTER TABLE `quirofanos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `turnos_insumos`
--
ALTER TABLE `turnos_insumos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_turno_insumo` (`id_turno`),
  ADD KEY `fk_insumo_turno` (`id_insumo`);

--
-- Indexes for table `turnos_quirurgicos`
--
ALTER TABLE `turnos_quirurgicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sala_id` (`id_quirofano`),
  ADD KEY `medico_id` (`id_cirujano`),
  ADD KEY `fk_turno_paciente` (`id_paciente`),
  ADD KEY `fk_turno_anestesista` (`id_anestesista`),
  ADD KEY `fk_turno_enfermero` (`id_enfermero`),
  ADD KEY `fk_turno_cirujano_ayudante` (`id_cirujano_ayudante`),
  ADD KEY `fk_turno_instrumentador_principal` (`id_instrumentador_principal`),
  ADD KEY `fk_turno_instrumentador_circulante` (`id_instrumentador_circulante`),
  ADD KEY `fk_turno_tecnico_anestesista` (`id_tecnico_anestesista`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `EMAIL` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anestesistas`
--
ALTER TABLE `anestesistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cirujanos`
--
ALTER TABLE `cirujanos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `enfermeros`
--
ALTER TABLE `enfermeros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `instrumentistas`
--
ALTER TABLE `instrumentistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `insumos`
--
ALTER TABLE `insumos`
  MODIFY `id_insumo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `insumos_cirugia`
--
ALTER TABLE `insumos_cirugia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `procedimientos`
--
ALTER TABLE `procedimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `quirofanos`
--
ALTER TABLE `quirofanos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `turnos_insumos`
--
ALTER TABLE `turnos_insumos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `turnos_quirurgicos`
--
ALTER TABLE `turnos_quirurgicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cirujanos`
--
ALTER TABLE `cirujanos`
  ADD CONSTRAINT `fk_medico_especialidad` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `insumos_cirugia`
--
ALTER TABLE `insumos_cirugia`
  ADD CONSTRAINT `insumos_cirugia_ibfk_1` FOREIGN KEY (`id_turno`) REFERENCES `turnos_quirurgicos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `insumos_cirugia_ibfk_2` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`id_insumo`),
  ADD CONSTRAINT `insumos_cirugia_ibfk_3` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `procedimientos`
--
ALTER TABLE `procedimientos`
  ADD CONSTRAINT `fk_procedimiento_especialidad` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `turnos_insumos`
--
ALTER TABLE `turnos_insumos`
  ADD CONSTRAINT `fk_insumo_turno` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`id_insumo`),
  ADD CONSTRAINT `fk_turno_insumo` FOREIGN KEY (`id_turno`) REFERENCES `turnos_quirurgicos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `turnos_quirurgicos`
--
ALTER TABLE `turnos_quirurgicos`
  ADD CONSTRAINT `fk_turno_anestesista` FOREIGN KEY (`id_anestesista`) REFERENCES `anestesistas` (`id`),
  ADD CONSTRAINT `fk_turno_cirujano` FOREIGN KEY (`id_cirujano`) REFERENCES `cirujanos` (`id`),
  ADD CONSTRAINT `fk_turno_cirujano_ayudante` FOREIGN KEY (`id_cirujano_ayudante`) REFERENCES `cirujanos` (`id`),
  ADD CONSTRAINT `fk_turno_enfermero` FOREIGN KEY (`id_enfermero`) REFERENCES `enfermeros` (`id`),
  ADD CONSTRAINT `fk_turno_instrumentador_circulante` FOREIGN KEY (`id_instrumentador_circulante`) REFERENCES `enfermeros` (`id`),
  ADD CONSTRAINT `fk_turno_instrumentador_principal` FOREIGN KEY (`id_instrumentador_principal`) REFERENCES `enfermeros` (`id`),
  ADD CONSTRAINT `fk_turno_paciente` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `fk_turno_tecnico_anestesista` FOREIGN KEY (`id_tecnico_anestesista`) REFERENCES `enfermeros` (`id`),
  ADD CONSTRAINT `turnos_quirurgicos_ibfk_1` FOREIGN KEY (`id_quirofano`) REFERENCES `quirofanos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
