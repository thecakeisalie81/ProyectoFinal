-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: auto_todo
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Herramientas','Equipos manuales o eléctricos utilizados para reparar, ajustar o mantener vehículos.'),(2,'Repuestos','Componentes nuevos o reacondicionados que reemplazan partes dañadas o desgastadas de un automóvil.'),(3,'Lubricantes','Aceites, grasas y otros fluidos diseñados para reducir la fricción y proteger partes móviles del vehículo.'),(4,'Accesorios','Elementos adicionales que no son esenciales para el funcionamiento, pero mejoran la apariencia o funcionalidad del vehículo.'),(5,'Equipos de diagnóstico 22','Dispositivos electrónicos utilizados para identificar fallos y monitorear el estado de sistemas del automóvil.'),(6,'Limpieza y mantenimiento','Productos destinados a la limpieza, protección y conservación de la carrocería e interiores del vehículo.');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimiento`
--

DROP TABLE IF EXISTS `movimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimiento` (
  `id_movimiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `tipo_movimiento` enum('entrada','salida') DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `fecha_movimiento` datetime DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre` text DEFAULT NULL,
  PRIMARY KEY (`id_movimiento`),
  KEY `id_producto` (`id_producto`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `movimiento_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  CONSTRAINT `movimiento_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimiento`
--

LOCK TABLES `movimiento` WRITE;
/*!40000 ALTER TABLE `movimiento` DISABLE KEYS */;
INSERT INTO `movimiento` VALUES (32,75,'entrada',6,'2025-08-19 07:08:02',1,'Pistola de pintar'),(33,1,'salida',1,'2025-08-19 07:14:25',1,'Llave de impacto eléctrica'),(34,5,'entrada',1,'2025-08-19 07:14:49',1,'Pinza de presión'),(35,1,'entrada',10,'2025-06-02 00:00:00',1,'Llave de impacto eléctrica'),(36,3,'salida',3,'2025-06-04 00:00:00',6,'Gato hidráulico 2T'),(37,4,'entrada',5,'2025-06-06 00:00:00',1,'Compresor de aire portátil'),(38,5,'salida',2,'2025-06-07 00:00:00',1,'Pinza de presión'),(39,6,'entrada',7,'2025-06-08 00:00:00',6,'Multímetro digital'),(40,7,'entrada',4,'2025-06-10 00:00:00',1,'Extractor de poleas'),(41,8,'salida',6,'2025-06-12 00:00:00',6,'Martillo de goma'),(42,9,'entrada',8,'2025-06-13 00:00:00',1,'Sierra portátil eléctrica'),(43,10,'salida',5,'2025-06-15 00:00:00',6,'Cortador de tubos'),(44,11,'entrada',12,'2025-06-16 00:00:00',1,'Filtro de aceite'),(45,12,'entrada',9,'2025-06-18 00:00:00',6,'Pastillas de freno'),(46,13,'salida',4,'2025-06-19 00:00:00',1,'Correa de distribución'),(47,14,'entrada',6,'2025-06-21 00:00:00',1,'Batería 12V 65Ah'),(48,15,'salida',3,'2025-06-22 00:00:00',6,'Amortiguador delantero'),(49,16,'entrada',5,'2025-06-24 00:00:00',1,'Radiador de aluminio'),(50,17,'entrada',7,'2025-06-25 00:00:00',6,'Sensor de oxígeno'),(51,18,'salida',2,'2025-06-27 00:00:00',1,'Alternador 120A'),(52,19,'entrada',4,'2025-06-29 00:00:00',6,'Motor de arranque'),(53,20,'entrada',9,'2025-07-01 00:00:00',1,'Kit de embrague'),(54,21,'salida',6,'2025-07-02 00:00:00',1,'Aceite sintético 10W40'),(55,22,'entrada',10,'2025-07-04 00:00:00',6,'Grasa multiusos'),(56,23,'salida',4,'2025-07-05 00:00:00',1,'Aceite para transmisión'),(57,24,'entrada',8,'2025-07-07 00:00:00',6,'Anticongelante refrigerante'),(58,25,'entrada',6,'2025-07-08 00:00:00',1,'Líquido de frenos DOT-4'),(59,26,'salida',3,'2025-07-10 00:00:00',6,'Aceite de motor mineral'),(60,27,'entrada',5,'2025-07-12 00:00:00',1,'Lubricante para cadenas'),(61,28,'entrada',7,'2025-07-13 00:00:00',6,'Fluido hidráulico'),(62,29,'salida',2,'2025-07-15 00:00:00',1,'Aditivo para aceite'),(63,30,'entrada',4,'2025-07-16 00:00:00',6,'Sellador de radiador'),(64,31,'entrada',8,'2025-07-18 00:00:00',1,'Tapones decorativos para válvulas'),(65,32,'salida',3,'2025-07-20 00:00:00',6,'Soporte para celular'),(66,33,'entrada',6,'2025-07-22 00:00:00',1,'Cubrevolante de cuero'),(67,34,'entrada',10,'2025-07-24 00:00:00',6,'Alfombrillas universales'),(68,35,'salida',5,'2025-07-26 00:00:00',1,'Cámara de retroceso'),(69,36,'entrada',7,'2025-07-28 00:00:00',6,'Portaplacas con luz LED'),(70,37,'entrada',4,'2025-07-29 00:00:00',1,'Difusor de aire decorativo'),(71,38,'salida',6,'2025-08-01 00:00:00',6,'Ambientador de auto'),(72,39,'entrada',9,'2025-08-03 00:00:00',1,'Organizador de asiento'),(73,40,'salida',5,'2025-08-05 00:00:00',1,'Protector solar para parabrisas'),(74,41,'entrada',11,'2025-08-08 00:00:00',6,'Escáner OBD2'),(75,NULL,'salida',46,'2025-08-19 20:26:08',1,'Cera líquida pulidora'),(76,76,'entrada',10,'2025-08-19 20:27:37',1,'Iguana azul');
/*!40000 ALTER TABLE `movimiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `stock_actual` int(11) DEFAULT NULL,
  `stock_minimo` int(11) DEFAULT NULL,
  `imagen` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `id_proveedor_foreign` (`id_proveedor`),
  KEY `id_categoria_foreign` (`id_categoria`) USING BTREE,
  CONSTRAINT `id_categoria_foreforeign` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  CONSTRAINT `id_proveedor_foreign` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (1,'Llave de impacto eléctrica','HER001',1,1,120.50,26,5,'../assets/img/LLAVE IMPACTO 1_2_ 20V 1030 LBS-FT DCF900P2 DEWALT.jpg'),(3,'Gato hidráulico 2T','HER003',1,1,75.30,43,5,'../assets/img/75475120-1678388253.jpg'),(4,'Compresor de aire portátil','HER004',1,1,89.90,12,5,'../assets/img/NCA-2_1.webp'),(5,'Pinza de presión','HER005',1,1,18.45,13,5,'../assets/img/10601@3x.jpg'),(6,'Multímetro digital','HER006',1,1,35.99,23,5,'../assets/img/35_UT33Cmas.webp'),(7,'Extractor de poleas','HER007',1,1,42.00,1,5,'../assets/img/44028003PDM001G.jpg'),(8,'Martillo de goma','HER008',1,1,15.75,14,5,'../assets/img/83320.jpg'),(9,'Sierra portátil eléctrica','HER009',1,1,109.20,71,5,'../assets/img/1450W-High-Quality-Professional-Powerful-185mm-Portable-Electric-Circular-Saw.avif'),(10,'Cortador de tubos','HER010',1,1,29.99,73,5,'../assets/img/[ST97303ST] CORTADOR DE TUBOS 6-64 MM ST97303ST SATA.jpg'),(11,'Filtro de aceite','REP001',2,2,10.20,71,5,'../assets/img/filtro-de-aceite-2631027200.webp'),(12,'Pastillas de freno','REP002',2,2,28.50,59,5,'../assets/img/7_3-Pastillas-de-freno_blog.jpg'),(13,'Correa de distribución','REP003',2,2,32.75,79,5,'../assets/img/correa-distribucion-2.jpg'),(14,'Batería 12V 65Ah','REP004',2,2,95.00,61,5,'../assets/img/61WYsEMkMoL._AC_SL1000_.jpg'),(15,'Amortiguador delantero','REP005',2,2,85.90,66,5,'../assets/img/GetMultimediaProducto.jpeg'),(16,'Radiador de aluminio','REP006',2,2,110.60,69,5,'../assets/img/images.jpeg'),(17,'Sensor de oxígeno','REP007',2,2,45.00,69,5,'../assets/img/Oxygen-Sensor2-web.webp'),(18,'Alternador 120A','REP008',2,2,155.30,59,5,'../assets/img/51kXXcVMmHL._UF894,1000_QL80_.jpg'),(19,'Motor de arranque','REP009',2,2,130.75,9,5,'../assets/img/motor-arranque_1579434164.jpg'),(20,'Kit de embrague','REP010',2,2,165.50,27,5,'../assets/img/KITEMBGRAGUE1-800X400.webp'),(21,'Aceite sintético 10W40','LUB001',3,3,28.99,29,5,'../assets/img/aceite-bel-ray-exs-synthetic-10w-40-1litro-Photoroom.webp'),(22,'Grasa multiusos','LUB002',3,3,9.85,66,5,'../assets/img/GRAS-45_800x.webp'),(23,'Aceite para transmisión','LUB003',3,3,24.60,4,5,'../assets/img/67-product-5f3b0c0dd9ac1-torco-mtf-manual-transmission-fluid-grande-2x.png'),(24,'Anticongelante refrigerante','LUB004',3,3,18.90,61,5,'../assets/img/gallon-227076-delo-xlc-coolant--concentrate-311x311.png'),(25,'Líquido de frenos DOT-4','LUB005',3,3,12.30,53,5,'../assets/img/529549-800-450.webp'),(26,'Aceite de motor mineral','LUB006',3,3,22.00,3,5,'../assets/img/100015428.jpg_20250710161110923815.webp'),(27,'Lubricante para cadenas','LUB007',3,3,13.45,16,5,'../assets/img/7068738.jpg'),(28,'Fluido hidráulico','LUB008',3,3,21.80,72,5,'../assets/img/100015555.jpg_20250607213015707843.webp'),(29,'Aditivo para aceite','LUB009',3,3,15.99,72,5,'../assets/img/159371.jpg'),(30,'Sellador de radiador','LUB010',3,3,10.25,64,5,'../assets/img/21184_1.jpg'),(31,'Tapones decorativos para válvulas','ACC001',4,4,4.99,24,5,'../assets/img/d6ff2f6c-eb71-4f41-80dc-d7441cee0c24.jpg'),(32,'Soporte para celular','ACC002',4,4,12.75,11,5,'../assets/img/images.jpeg'),(33,'Cubrevolante de cuero','ACC003',4,4,18.30,60,5,'../assets/img/file.jpg'),(34,'Alfombrillas universales','ACC004',4,4,22.00,31,5,'../assets/img/NzE5NTc4Mj-500x500.jpg'),(35,'Cámara de retroceso','ACC005',4,4,35.90,53,5,'../assets/img/4745-product-63405b7c6fe2f-55.jpg'),(36,'Portaplacas con luz LED','ACC006',4,4,14.99,12,5,'../assets/img/10441-Portaplaca-Luz-LED-600x600.jpg'),(37,'Difusor de aire decorativo','ACC007',4,4,8.50,64,5,'../assets/img/images (1).jpeg'),(38,'Ambientador de auto','ACC008',4,4,3.99,41,5,'../assets/img/como_elegir_mi_ambientador_para_el_coche_19369_600.jpg'),(39,'Organizador de asiento','ACC009',4,4,16.75,17,5,'../assets/img/YjFlZGM3OD-500x500.webp'),(40,'Protector solar para parabrisas','ACC010',4,4,11.60,39,5,'../assets/img/71znB0jHRpL._UF894,1000_QL80_.jpg'),(41,'Escáner OBD2','EDI001',5,5,65.99,68,5,'../assets/img/THINKSCANPLUSS7_MAINIMAGE_700x700.webp'),(42,'Medidor de presión de aceite','EDI002',5,5,35.20,62,5,'../assets/img/30_YT-73030.webp'),(43,'Analizador de gases de escape','EDI003',5,5,155.80,26,5,'../assets/img/gases-compressed.jpg'),(44,'Probador de batería digital','EDI004',5,5,42.60,27,5,'../assets/img/59_YT-8311.avif'),(45,'Estetoscopio mecánico','EDI005',5,5,18.90,56,5,'../assets/img/t-0998.jpg'),(46,'Termómetro láser infrarrojo','EDI006',5,5,28.30,40,5,'../assets/img/111TMP700.jpg'),(47,'Osciloscopio portátil','EDI007',5,5,135.99,34,5,'../assets/img/ZGYzMzQ3MW-500x500.jpg'),(48,'Probador de inyectores','EDI008',5,5,60.75,48,5,'../assets/img/NzFhM2ZiNj-500x500.jpg'),(49,'Sensor de RPM inalámbrico','EDI009',5,5,47.20,61,5,'../assets/img/OGU3ZTViZD-500x500.jpg'),(50,'Detector de fugas por presión','EDI010',5,5,59.90,1,5,'../assets/img/71GqeU3zYAL._UF894,1000_QL80_.jpg'),(51,'Shampoo para carrocería','LIM001',6,6,9.45,62,5,'../assets/img/100015482.jpg_20250607213133511442.webp'),(53,'Limpiador de interiores','LIM003',6,6,10.20,76,5,'../assets/img/MEGUIARS-INTERIOR-DETAILER-CLEANER.webp'),(54,'Desinfectante para volante y palanca','LIM004',6,6,7.80,14,5,'../assets/img/E303013000_01.avif'),(55,'Toallas de microfibra','LIM005',6,6,5.99,3,5,'../assets/img/7258782.jpg'),(56,'Pulidor de faros','LIM006',6,6,12.50,52,5,'../assets/img/100015494.jpg_20250607212956926363.webp'),(57,'Acondicionador de cuero','LIM007',6,6,13.25,13,5,'../assets/img/leather-treatment-acondicionador-para-cuero-16oz.jpg'),(58,'Limpiador de vidrios','LIM008',6,6,6.90,71,5,'../assets/img/0008313_limpiador-para-vidrios-870ml.png'),(59,'Eliminador de olores','LIM009',6,6,4.50,75,5,'../assets/img/100003687.jpg_20250710150613978890.webp'),(60,'Kit de limpieza express','LIM010',6,6,19.99,1,5,'../assets/img/unnamed.jpg'),(75,'Pistola de pintar','HER002',1,1,55.00,6,5,'../assets/img/0000000191227.webp'),(76,'Iguana azul','3445',6,5,23.00,10,5,'../assets/img/12d6c481c68347ce173797418712090e2e2ce18d_file.webp');
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedor`
--

DROP TABLE IF EXISTS `proveedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `telefono` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedor`
--

LOCK TABLES `proveedor` WRITE;
/*!40000 ALTER TABLE `proveedor` DISABLE KEYS */;
INSERT INTO `proveedor` VALUES (1,'Autotools S.A.','Carlos Ramírez','Zona Industrial Norte, San José, Costa Rica',22345678),(2,'Repuestos El Motor','Laura Gómez','Av. Central 125, Alajuela, Costa Rica',24876543),(3,'LubriCentro Tropical','Daniel Morales','Carrera 8, Heredia, Costa Rica',22664521),(4,'Accesorios Flash','Andrea Salas','Boulevard Los Árboles, Cartago, Costa Rica',25748963),(5,'TecnoDiagnóstico Ltda.','Roberto Pineda','Parque Empresarial Omega, San Pedro, Costa Rica',22987654),(6,'Brillo Total','Melissa Castro','Ruta 27, Escazú, Costa Rica',22233456);
/*!40000 ALTER TABLE `proveedor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `respaldo`
--

DROP TABLE IF EXISTS `respaldo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `respaldo` (
  `id_respaldo` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_respaldo` datetime DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_respaldo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `respaldo`
--

LOCK TABLES `respaldo` WRITE;
/*!40000 ALTER TABLE `respaldo` DISABLE KEYS */;
/*!40000 ALTER TABLE `respaldo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `contra` varchar(255) DEFAULT NULL,
  `rol` enum('admin','usuario') DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'LuisDavid','www.lui81@gmail.com','$2y$10$k5v9MwgERugThPyiyUN5Su.s4DJuHapAZXQISr.e3c0FQ6Yml2msW','admin','2025-07-20 18:39:51'),(6,'Pedrito','thesky1208@gmail.com','$2y$10$4KmWF1uJIbYNyCaijeqDNOI1RkSY68bdn6X1sZArQ8idSSVqBArgq','usuario','2025-08-10 20:00:31');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-19 20:29:02
