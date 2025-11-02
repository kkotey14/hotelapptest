-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: hotelapp
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (18,'101','Queen','https://images.pexels.com/photos/4890676/pexels-photo-4890676.jpeg','Cozy queen with city view',12999,2,1),(19,'102','King','https://images.pexels.com/photos/33495802/pexels-photo-33495802.jpeg','Spacious king room near lobby',15999,3,1),(20,'201','Suite','https://images.pexels.com/photos/2506990/pexels-photo-2506990.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2','One-bedroom suite, living area',21999,4,1),(21,'202','Double','https://images.pexels.com/photos/20276961/pexels-photo-20276961/free-photo-of-twin-room-hotel-london-mowbray-court-hotel-central-london.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2','Two doubles for families',13999,4,1);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `room_photos`
--

LOCK TABLES `room_photos` WRITE;
/*!40000 ALTER TABLE `room_photos` DISABLE KEYS */;
INSERT INTO `room_photos` VALUES (2,21,'https://images.pexels.com/photos/6585755/pexels-photo-6585755.jpeg','bathroom',NULL,'2025-09-24 20:48:40'),(4,21,'https://images.pexels.com/photos/20276961/pexels-photo-20276961.jpeg','main',NULL,'2025-09-24 20:49:48'),(5,20,'https://images.pexels.com/photos/17840522/pexels-photo-17840522.jpeg','bathroom',NULL,'2025-09-24 20:50:44'),(6,20,'https://images.pexels.com/photos/2506990/pexels-photo-2506990.jpeg','main',NULL,'2025-09-24 20:51:08'),(7,19,'https://images.pexels.com/photos/33495802/pexels-photo-33495802.jpeg','main',NULL,'2025-09-24 20:51:33'),(8,19,'https://images.pexels.com/photos/6585755/pexels-photo-6585755.jpeg','bathroom',NULL,'2025-09-24 20:52:14'),(9,18,'https://images.pexels.com/photos/6585755/pexels-photo-6585755.jpeg','bathroom',NULL,'2025-09-24 20:52:31'),(10,18,'https://images.pexels.com/photos/4890676/pexels-photo-4890676.jpeg','main',NULL,'2025-09-24 20:52:54');
/*!40000 ALTER TABLE `room_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-02 15:10:49
