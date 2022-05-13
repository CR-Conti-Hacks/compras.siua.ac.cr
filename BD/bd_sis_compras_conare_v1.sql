-- MySQL dump 10.13  Distrib 8.0.19, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: bd_sis_compras_conare.sql
-- ------------------------------------------------------
-- Server version	8.0.19-0ubuntu0.19.10.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (2,'Impresoras');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (2,'Zebra-ZQ630-RFID-Series.jpg','image/jpeg'),(3,'3-x-1-polypro-4000d-rfid-labels-10026635-49e.jpg','image/jpeg');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `categorie_id` int unsigned NOT NULL,
  `media_id` int DEFAULT '0',
  `date` datetime NOT NULL,
  `description` blob,
  `caracteristicas` blob,
  `justificacion` blob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `categorie_id` (`categorie_id`),
  KEY `media_id` (`media_id`),
  CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (5,'zebra RFID ZQ630R','194',1000.00,575000.00,2,2,'2020-02-26 15:13:10',_binary 'Impresora móvil de etiquetas RFID',_binary 'Marca: Zebra |\r\nModelo: RFID ZQ630\r\nDimensiones pulgadas: 7,35 in L x 6,5 in A x 3,25 in Al |\r\nDimenciones mm: 187 mm L x 165 mm A x 82,5 mm Al |\r\nPeso (con batería): 2,45 lb/1,1 kg |\r\nSistema operativo: Link-OS® |\r\nResolución: 203 ppp/8 puntos por mm |\r\nMemoria: Memoria RAM de 256 MB; memoria Flash de 512 MB (estándar) |\r\nProporciones de códigos de barras: 1,5:1; 2:1; 2,5:1; 3:1; 3,5:1 |\r\nCódigos de barras lineales: Code 39, Code 93, UCC/EAN128, Code 128, Codabar (NW-7), Interleaved 2-of-5, UPC-A, UPC-E, complemento de 2 y 5 dígitos, EAN-8, EAN-13, complemento de 2 y 5 dígitos |\r\nComunicación: Radiofrecuencia 802.11ac ZebraNet® con compatibilidad para: Cifrado WEP, TKIP y AES, WPA y WPA2, 802.1x (con WEP, WPA o WPA2),EAP-FAST, EAP-TTLS, EAP-TLS, PEAP, LEAP, 802.11d y 802.11i, Radio dual Bluetooth 4.1 simultánea, Certificación wifi, Compatibilidad con modo ad hoc |\r\nRadiofrecuencia Bluetooth 4.1 (estándar y BLE) |\r\nEspecificaciones de las comunicaciones por cable: Interfaz de alta velocidad USB 2.0 (12 Mbps), \r\nCompatibilidad con Ethernet 10/100(mediante base para carga) |\r\nCódigos de barras 2D: PDF417, MicroPDF417, MaxiCode, QR Code, familia GS1/DataBar™ (RSS), Aztec, MSI/Plessey, FIM Postnet, Data Matrix, TLC39 |\r\nAncho máximo de impresión: 4,1 in/104 mm |\r\nVelocidad máxima de impresión: Hasta 4,5 in/115 mm por segundo |\r\nLongitud de impresión: Mínimo: 0,5 in/12,7 mm Máximo: 32 in/813 mm |\r\nRFID: Admite etiquetas compatibles con los protocolos RFID UHF EPC Gen 2 V2, ISO/IEC 18000-63 y RAIN |\r\nConectividad: Conectividad para los sistemas operativos iOS de Apple®, Android y Windows Mobile |\r\nImpresión térmica directa de códigos de barras, texto y gráficos |\r\nLenguajes de programación CPCL, EPL™ y ZPL® |\r\nBatería de ion de litio PowerPrecision+ removible y recargable de 6800 mAh (nominal) 49,4 Wh |\r\nCargador de batería integrado |\r\nClip para cinturón para una impresión cómoda y discreta |\r\nSensor de etiqueta presente para expedir solo una etiqueta por vez |\r\nBarra de corte para retirar fácilmente los materiales |\r\nCarga de materiales central para varios anchos de rollo |\r\nPantalla a color grande y fácil de leer (288 x 240 píxeles) |\r\nResistencia a múltiples caídas desde 6 ft/1,83 m sobre concreto |\r\nCalificación IP43 de resistencia al agua |\r\nProcesador ARM de 32 bits a 400 MHz |\r\nPermite realizar impresiones verticales y horizontales |\r\nPueden usarse en cualquier orientación\r\n',_binary 'Para proyecto de mejora y optimización del sistema de inventario utilizando etiquetas RFID'),(6,'Etiquetas Zebra RFID - 10026635 -PolyPro 4000D RFID Labels','189',50.00,28750.00,2,3,'2020-02-26 15:52:33',_binary 'Rollo de etiquetas RFID, para impresora Zebra ZQ630',_binary 'Marca: Zebra |\r\nNúmero de parte: 10026635 |\r\nNombre: PolyPro 4000D RFID Labels |\r\nTamaño: 3&quot; x 1&quot; |\r\nAncho: 3&quot; |\r\nAlto: 1&quot; |\r\nTamaño core: 0.75&quot; Core / Mobile |\r\nEtiquetas por rollo: 260 |\r\nColor: Blanco |\r\nFormato: rollo\r\nAdhesivo: All-Temp Permanent  |\r\nEtiqueta Final: Mate |\r\nMétodo de impresión: Direct Thermal |\r\nFull Case Quantity: 12 |\r\nFull Case Weight: 3 |\r\nCompatibilidad impresora:  Zebra ZQ630\r\nMaterial: Polypropylene |\r\nRoll Weight: 0.25 |\r\nOutside Diameter: 2.25&quot; OD |\r\nZebra Label Material: RFID \r\n',_binary 'Para proyecto de mejora y optimización del sistema de inventario utilizando etiquetas RFID');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (2,6,8,230000.00,'2020-02-26'),(3,5,1,575000.00,'2020-02-26'),(4,6,2,57500.00,'2020-02-26'),(5,5,5,2875000.00,'2020-02-26');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) NOT NULL,
  `group_level` int NOT NULL,
  `group_status` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_level` (`group_level`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_groups`
--

LOCK TABLES `user_groups` WRITE;
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
INSERT INTO `user_groups` VALUES (1,'Admin',1,1),(2,'Special',2,0),(3,'User',3,1);
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `user_level` (`user_level`),
  CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'UGIT','ugit','29d07adc770fdbf52bd7b8882d4b11d8ec6c68c7',1,'pzg9wa7o1.jpg',1,'2020-03-02 08:38:03'),(2,'Special User','special','ba36b97a41e7faf742ab09bf88405ac04f99599a',2,'no_image.jpg',1,'2017-06-16 07:11:26'),(3,'Default User','user','12dea96fec20593566ab75692c9949596833adc9',3,'no_image.jpg',1,'2017-06-16 07:11:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-03-02  8:43:43
