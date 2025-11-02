-- Cleaned schema with only public data: rooms and room_photos

-- Table structure for table `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `email` varchar(120) NOT NULL,
  `role` enum('customer','staff','admin') DEFAULT 'customer',
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `bookings`
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `guests` int(11) NOT NULL DEFAULT 1,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `room_id` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `payments`
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `provider` varchar(20) DEFAULT 'stripe',
  `status` enum('unpaid','paid','failed','refunded') DEFAULT 'unpaid',
  `amount_cents` int(11) NOT NULL,
  `charge_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `reviews`
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `rooms`
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(10) NOT NULL,
  `type` varchar(40) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `rate_cents` int(11) NOT NULL,
  `max_guests` int(11) DEFAULT 2,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `rooms`
INSERT INTO `rooms` VALUES 
(18,'101','Queen','https://images.pexels.com/photos/4890676/pexels-photo-4890676.jpeg','Cozy queen with city view',12999,2,1),
(19,'102','King','https://images.pexels.com/photos/33495802/pexels-photo-33495802.jpeg','Spacious king room near lobby',15999,3,1),
(20,'201','Suite','https://images.pexels.com/photos/2506990/pexels-photo-2506990.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2','One-bedroom suite, living area',21999,4,1),
(21,'202','Double','https://images.pexels.com/photos/20276961/pexels-photo-20276961/free-photo-of-twin-room-hotel-london-mowbray-court-hotel-central-london.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2','Two doubles for families',13999,4,1);

-- Table structure for table `room_photos`
DROP TABLE IF EXISTS `room_photos`;
CREATE TABLE `room_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `photo_type` enum('main','bathroom','other') NOT NULL DEFAULT 'main',
  `caption` varchar(140) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_room_photos_room` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `room_photos`
INSERT INTO `room_photos` VALUES 
(2,21,'https://images.pexels.com/photos/6585755/pexels-photo-6585755.jpeg','bathroom',NULL,'2025-09-24 20:48:40'),
(4,21,'https://images.pexels.com/photos/20276961/pexels-photo-20276961.jpeg','main',NULL,'2025-09-24 20:49:48'),
(5,20,'https://images.pexels.com/photos/17840522/pexels-photo-17840522.jpeg','bathroom',NULL,'2025-09-24 20:50:44'),
(6,20,'https://images.pexels.com/photos/2506990/pexels-photo-2506990.jpeg','main',NULL,'2025-09-24 20:51:08'),
(7,19,'https://images.pexels.com/photos/33495802/pexels-photo-33495802.jpeg','main',NULL,'2025-09-24 20:51:33'),
(8,19,'https://images.pexels.com/photos/6585755/pexels-photo-6585755.jpeg','bathroom',NULL,'2025-09-24 20:52:14'),
(9,18,'https://images.pexels.com/photos/6585755/pexels-photo-6585755.jpeg','bathroom',NULL,'2025-09-24 20:52:31'),
(10,18,'https://images.pexels.com/photos/4890676/pexels-photo-4890676.jpeg','main',NULL,'2025-09-24 20:52:54');

