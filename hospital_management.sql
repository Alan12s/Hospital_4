-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 07:37 AM
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
  `dni` varchar(20) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `disponibilidad` enum('disponible','no_disponible','en_cirugia') DEFAULT 'disponible',
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anestesistas`
--

INSERT INTO `anestesistas` (`id`, `nombre`, `dni`, `especialidad`, `disponibilidad`, `telefono`, `email`) VALUES
(8, 'Dr. Luis Miguel', '', 'Anestesiología General', 'disponible', '2645556709', 'luism@hospital.com'),
(13, 'Alexandro Brizuela', '', 'Anestesiología Pediátrica', 'disponible', '2645657890', 'alexx@hospital.com'),
(15, 'Dr. Ricardo Moreno', '28765432', 'Anestesiología en Cirugía Mayor', 'disponible', '2647654321', 'r.moreno@hospital.com'),
(16, 'Dra. Isabel Gutiérrez', '31876543', 'Anestesiología Obstétrica', 'disponible', '2648765432', 'i.gutierrez@hospital.com'),
(17, 'Dr. Joaquín Herrera', '33987654', 'Anestesiología Regional', 'disponible', '2649876543', 'j.herrera@hospital.com'),
(18, 'Dra. Mónica Delgado', '29654321', 'Anestesiología General', 'disponible', '2640987654', 'm.delgado@hospital.com');

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
(24, 'Dr. Alejandro Ruiz', '29876543', 1, '2647123456', 'a.ruiz@hospital.com', '2025-06-30 04:25:04', NULL, 'disponible'),
(25, 'Dra. Valentina Ortega', '31987654', 2, '2648234567', 'v.ortega@hospital.com', '2025-06-30 04:25:04', NULL, 'disponible'),
(26, 'Dr. Sebastián Molina', '33098765', 3, '2649345678', 's.molina@hospital.com', '2025-06-30 04:25:04', NULL, 'disponible'),
(27, 'Dra. Gabriela Herrera', '30765432', 4, '2640456789', 'g.herrera@hospital.com', '2025-06-30 04:25:04', NULL, 'disponible'),
(28, 'Dr. Nicolás Fernández', '32654321', 5, '2641567890', 'n.fernandez@hospital.com', '2025-06-30 04:25:04', NULL, 'disponible'),
(29, 'Dra. Camila Vásquez', '28543210', 1, '2642678901', 'c.vasquez@hospital.com', '2025-06-30 04:25:04', NULL, 'disponible'),
(30, 'Dr. Martín Cordero', '34432109', 3, '2643789012', 'm.cordero@hospital.com', '2025-06-30 04:25:04', NULL, 'disponible');

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
(3, 'Ana Martínez', '33445566', 'Enfermería Pediátrica', '1199887766', 'a.martinez@hospital.com', '2022-01-20', 'disponible'),
(15, 'Sofía Ramírez', '35789012', 'Enfermería Quirúrgica', '2647890123', 's.ramirez@hospital.com', '2021-08-15', 'disponible'),
(16, 'Miguel Torres', '32456789', 'Enfermería de Cuidados Intensivos', '2648901234', 'm.torres@hospital.com', '2020-11-20', 'disponible'),
(17, 'Laura Fernández', '29876543', 'Enfermería Pediátrica', '2649012345', 'l.fernandez@hospital.com', '2023-03-10', 'disponible'),
(18, 'Roberto Silva', '34567890', 'Enfermería de Urgencias', '2640123456', 'r.silva@hospital.com', '2022-07-05', 'disponible'),
(19, 'Carmen Morales', '31234567', 'Enfermería Quirúrgica', '2641234567', 'c.morales@hospital.com', '2021-12-01', 'disponible');

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
(5, 'Diana Espinoza', '33567890', 'Instrumentista Quirúrgico General', '2642345678', 'd.espinoza@hospital.com', '2022-05-15', 'disponible'),
(6, 'Carlos Mendoza', '30789012', 'Instrumentista en Cirugía Ortopédica', '2643456789', 'c.mendoza.inst@hospital.com', '2021-09-20', 'disponible'),
(7, 'Patricia Vega', '32890123', 'Instrumentista en Cirugía Urológica', '2644567890', 'p.vega@hospital.com', '2020-02-28', 'disponible'),
(8, 'Fernando López', '34901234', 'Instrumentista Quirúrgico General', '2645678901', 'f.lopez@hospital.com', '2023-01-10', 'disponible');

-- --------------------------------------------------------

--
-- Table structure for table `insumos`
--

CREATE TABLE `insumos` (
  `id_insumo` int(11) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `nombre` varchar(70) NOT NULL,
  `categoria` varchar(20) DEFAULT 'descartable',
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

INSERT INTO `insumos` (`id_insumo`, `codigo`, `nombre`, `categoria`, `tipo`, `cantidad`, `lote`, `fecha_vencimiento`, `tiene_vencimiento`, `ubicacion`) VALUES
(1, 'INS-001', 'Guantes quirúrgicos estériles', 'descartable', 'Consumible', 496, 'LOT-2025-01', '2028-09-30', 1, 'Almacén A, Estante 1'),
(2, 'INS-002', 'Jeringas 10ml', 'descartable', 'Consumible', 295, 'LOT-2025-02', '2029-01-30', 1, 'Almacén A, Estante 2'),
(3, 'INS-003', 'Gasas estériles 10x10', 'descartable', 'Consumible', 199, 'LOT-2025-03', '2027-09-15', 1, 'Almacén B, Estante 1'),
(6, 'INS-006', 'Bisturí #10', 'descartable', 'Instrumental', 50, 'LOT-2025-06', '2027-01-31', 1, 'Almacén C, Estante 2'),
(7, 'INS-007', 'Catéter intravenoso 18G', 'descartable', 'Material quirúrgico', 250, 'LOT-2025-07', '2028-11-30', 1, 'Almacén D, Estante 1'),
(9, 'INS-009', 'Bisturi', 'descartable', 'Material quirúrgico', 30, 'LOT-2025-09', '2028-06-18', 1, 'Almacen A, Estante 1'),
(11, 'INS-47BAAE', 'Cateter', 'descartable', 'Material quirúrgico', 230, 'LOTE-20250518-0011', '2027-10-18', 1, 'Almacen B, Estante 1'),
(14, 'INS-012', 'Sutura de seda 3-0', 'descartable', 'Material quirúrgico', 150, 'LOT-2025-12', '2029-03-15', 1, 'Almacén A, Estante 2'),
(15, 'INS-013', 'Sutura de nylon 4-0', 'descartable', 'Material quirúrgico', 120, 'LOT-2025-13', '2028-12-20', 1, 'Almacén A, Estante 2'),
(16, 'INS-014', 'Apósitos adhesivos grandes', 'descartable', 'Material de curación', 200, 'LOT-2025-14', '2027-08-30', 1, 'Almacén B, Estante 2'),
(17, 'INS-015', 'Sondas vesicales Foley 16Fr', 'descartable', 'Material quirúrgico', 80, 'LOT-2025-15', '2029-05-10', 1, 'Almacén C, Estante 3'),
(19, 'INS-017', 'Compresas quirúrgicas 30x30', 'descartable', 'Material quirúrgico', 300, 'LOT-2025-17', '2027-11-15', 1, 'Almacén A, Estante 3'),
(20, 'INS-018', 'Agujas hipodérmicas 21G', 'descartable', 'Consumible', 500, 'LOT-2025-18', '2030-02-28', 1, 'Almacén B, Estante 3'),
(21, 'INS-019', 'Solución salina 0.9% 500ml', 'descartable', 'Solución', 100, 'LOT-2025-19', '2027-06-30', 1, 'Almacén C, Estante 1'),
(22, 'INS-020', 'Drenajes Jackson-Pratt', 'descartable', 'Material quirúrgico', 40, 'LOT-2025-20', '2028-09-15', 1, 'Almacén D, Estante 3'),
(23, 'INS-021', 'Electrodos de electrocauterio', 'descartable', 'Material quirúrgico', 75, 'LOT-2025-21', '2029-01-10', 1, 'Almacén A, Estante 4'),
(24, 'INS-022', 'Batas quirúrgicas desechables', 'descartable', 'Protección personal', 180, 'LOT-2025-22', '2027-04-20', 1, 'Almacén B, Estante 1'),
(25, 'INS-023', 'Cánulas nasales de oxígeno', 'descartable', 'Material respiratorio', 120, 'LOT-2025-23', '2028-03-31', 1, 'Almacén C, Estante 2'),
(26, 'INS-024', 'Pinzas Kocher desechables', 'descartable', 'Instrumental', 90, 'LOT-2025-24', '2029-08-15', 1, 'Almacén D, Estante 1'),
(27, 'INS-025', 'Campos quirúrgicos estériles', 'descartable', 'Material quirúrgico', 250, 'LOT-2025-25', '2028-05-30', 1, 'Almacén A, Estante 1');

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
(1, 'Juan Pérez', 35, 'Alergia a la penicilina, hipertensión controlada', '30123456', '1122334455', 'Calle Falsa 123', 'juan.perez@example.com', 'Obra Social Provincia', 'santa rosa', '1990-01-17'),
(3, 'Carlos López', 42, 'Diabetes tipo 2, cirugía de apéndice 2010', '33445566', '1199887766', 'Calle Real 789', 'carlos.lopez@example.com', 'No tiene', 'Sarmiento', '1989-02-08'),
(4, 'Ana Martínez', 50, 'Artritis reumatoide, control anual', '32123456', '1133445566', 'Av. Libertad 333', 'ana.martinez@example.com', 'Obra Social Provincia', '25 de mayo', '1989-06-17'),
(15, 'Rosa Álvarez', 58, 'Colesterol alto, ex fumadora', '25456789', '2641122334', 'Av. San Martín 456', 'rosa.alvarez@email.com', 'OSECAC', 'Rawson', '1967-03-15'),
(16, 'Diego Ramírez', 33, 'Deportista, sin antecedentes relevantes', '31567890', '2642233445', 'Calle Mendoza 789', 'diego.ramirez@email.com', 'Swiss Medical', 'Capital', '1992-11-22'),
(17, 'Elena Castro', 45, 'Migraña crónica, alergia a aspirina', '27678901', '2643344556', 'Barrio Norte 123', 'elena.castro@email.com', 'Obra Social Provincia', 'Chimbas', '1980-07-08'),
(18, 'Mateo González', 29, 'Fractura previa en brazo izquierdo', '32789012', '2644455667', 'Villa Krause 321', 'mateo.gonzalez@email.com', 'PAMI', 'Rivadavia', '1996-01-30'),
(19, 'Lucía Herrera', 52, 'Diabetes tipo 2, hipertensión', '26890123', '2645566778', 'Barrio Sur 654', 'lucia.herrera@email.com', 'No tiene', 'Santa Lucía', '1973-09-12'),
(20, 'Andrés Morales', 38, 'Alergia a anestésicos locales', '30901234', '2646677889', 'Centro 987', 'andres.morales@email.com', 'Obra Social Provincia', 'Pocito', '1987-12-05');

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
  `duracion` int(11) NOT NULL,
  `id_quirofano` int(11) NOT NULL,
  `id_cirujano` int(11) NOT NULL,
  `id_cirujano_ayudante` int(11) DEFAULT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_anestesista` int(11) DEFAULT NULL,
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

INSERT INTO `turnos_quirurgicos` (`id`, `fecha`, `hora_inicio`, `hora_finalizacion`, `duracion`, `id_quirofano`, `id_cirujano`, `id_cirujano_ayudante`, `id_paciente`, `id_anestesista`, `id_instrumentador_principal`, `id_instrumentador_circulante`, `id_tecnico_anestesista`, `tipo_anestesia`, `complicaciones`, `procedimiento`, `estado`, `observaciones`, `created_at`, `updated_at`) VALUES
(38, '2025-07-10', '10:00:00', '11:00:00', 60, 1, 3, 1, 3, 8, 7, 6, 3, 'Regional', NULL, 'Prostatectomía (extirpación total o parcial de la próstata)', 'cancelado', NULL, '2025-06-30 04:35:40', '2025-06-30 05:10:26');

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
(1, 'Ramon', 'Areyuna', 'admin@hospital.com', 'admin', '2025-04-17 22:36:48', NULL, '2025-06-30 04:33:26', 'administrador', 1, '$2y$10$d68qdYU5o0GoL11xeayc7.seuNo7AF56cGxaoaMrEzoylfEVBFMTO', '047d35f49e26d7caa7bee79ecd1e636996b31437cf2c2c5755e90a594b639b19', 'e046c6d3cb97e1e62eead996f03128a78b37371c2ce825c137b56171a826a17f', '2025-04-19 22:37:33'),
(2, 'Maria', 'Herrera', 'maria@hospital.com', 'enfermera', '2025-04-19 21:23:13', NULL, NULL, 'enfermero', 1, '$2y$10$PGiSgn509EfsxTbc5hV9dOyPQb7cy46onNvsimJTP2p4GywWylA.C', NULL, NULL, NULL),
(5, 'Manuel', 'Gonzales', 'manuel@hospital.com', 'supervisor', '2025-04-19 21:29:55', NULL, '2025-06-27 21:25:23', 'supervisor', 1, '$2y$10$WakpL4f6cWWR0HfOn.Auc.jPNFy/mYKYSSem/vkvEZhkmaQ6IkZEC', NULL, NULL, NULL),
(6, 'Pablo', 'Alboran', 'pablo@hospital.com', 'enfermero', '2025-05-03 17:23:48', NULL, '2025-06-27 21:53:40', 'enfermero', 1, '$2y$10$iqggD1A3D0qL8eDygSs.5uyFsfWuyGHg.onOQlKwfidp4ZxEn2pPm', NULL, NULL, NULL),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `cirujanos`
--
ALTER TABLE `cirujanos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `enfermeros`
--
ALTER TABLE `enfermeros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `instrumentistas`
--
ALTER TABLE `instrumentistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `insumos`
--
ALTER TABLE `insumos`
  MODIFY `id_insumo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `turnos_quirurgicos`
--
ALTER TABLE `turnos_quirurgicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cirujanos`
--
ALTER TABLE `cirujanos`
  ADD CONSTRAINT `fk_cirujano_especialidad` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `insumos_cirugia`
--
ALTER TABLE `insumos_cirugia`
  ADD CONSTRAINT `fk_insumo_cirugia_insumo` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`id_insumo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_insumo_cirugia_turno` FOREIGN KEY (`id_turno`) REFERENCES `turnos_quirurgicos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_insumo_cirugia_usuario` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `fk_log_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `procedimientos`
--
ALTER TABLE `procedimientos`
  ADD CONSTRAINT `fk_procedimiento_especialidad` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `turnos_insumos`
--
ALTER TABLE `turnos_insumos`
  ADD CONSTRAINT `fk_turno_insumo_insumo` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`id_insumo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turno_insumo_turno` FOREIGN KEY (`id_turno`) REFERENCES `turnos_quirurgicos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `turnos_quirurgicos`
--
ALTER TABLE `turnos_quirurgicos`
  ADD CONSTRAINT `fk_turno_anestesista` FOREIGN KEY (`id_anestesista`) REFERENCES `anestesistas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turno_cirujano_ayudante` FOREIGN KEY (`id_cirujano_ayudante`) REFERENCES `cirujanos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turno_cirujano_principal` FOREIGN KEY (`id_cirujano`) REFERENCES `cirujanos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turno_instrumentista_circulante` FOREIGN KEY (`id_instrumentador_circulante`) REFERENCES `instrumentistas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turno_instrumentista_principal` FOREIGN KEY (`id_instrumentador_principal`) REFERENCES `instrumentistas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turno_paciente` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turno_quirofano` FOREIGN KEY (`id_quirofano`) REFERENCES `quirofanos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turno_tecnico_anestesista` FOREIGN KEY (`id_tecnico_anestesista`) REFERENCES `enfermeros` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
