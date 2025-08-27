-- Luna Database Backup
-- Generated on: 2025-08-27 19:20:47

-- Table structure for table `inventory`
DROP TABLE IF EXISTS `inventory`;
CREATE TABLE `inventory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(100) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `inventory`
INSERT INTO `inventory` VALUES ('17', 'Coffee Beans', '1755947829873', '1475.00', '109.77', '100.00', 'Kg', '2025-08-22', '2025-08-23 19:17:09');
INSERT INTO `inventory` VALUES ('18', 'Almond Milk', '1755947856126', '158.00', '24.25', '100.00', 'L', '2025-08-05', '2025-08-23 19:17:36');
INSERT INTO `inventory` VALUES ('19', 'Condensed Milk', '1755948048271', '102.00', '25.40', '100.00', 'L', '2025-08-08', '2025-08-23 19:20:48');
INSERT INTO `inventory` VALUES ('20', 'Coke Swakto', '1755949298187', '10.00', '7.00', '100.00', 'pcs', '2025-08-27', '2025-08-23 19:41:38');

-- Table structure for table `menu_ingredients`
DROP TABLE IF EXISTS `menu_ingredients`;
CREATE TABLE `menu_ingredients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_id` int NOT NULL,
  `inventory_id` int NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  KEY `inventory_id` (`inventory_id`),
  CONSTRAINT `menu_ingredients_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_ingredients_ibfk_2` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `menu_ingredients`
INSERT INTO `menu_ingredients` VALUES ('15', '7', '18', '0.25');
INSERT INTO `menu_ingredients` VALUES ('16', '7', '17', '0.01');
INSERT INTO `menu_ingredients` VALUES ('17', '7', '19', '0.20');
INSERT INTO `menu_ingredients` VALUES ('18', '8', '20', '1.00');

-- Table structure for table `menus`
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `barcode` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `menus`
INSERT INTO `menus` VALUES ('7', 'Spanish Latte', '210.00', '1755948055385', '2025-08-23 19:22:20');
INSERT INTO `menus` VALUES ('8', 'Coke Swakto', '20.00', '1755949301450', '2025-08-23 19:41:51');

-- Table structure for table `production`
DROP TABLE IF EXISTS `production`;
CREATE TABLE `production` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_id` int NOT NULL,
  `quantity_produced` int NOT NULL,
  `quantity_available` int NOT NULL,
  `quantity_sold` int NOT NULL,
  `barcode` varchar(225) DEFAULT NULL,
  `wastage` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_production_menu` (`menu_id`),
  CONSTRAINT `fk_production_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `production`
INSERT INTO `production` VALUES ('30', '7', '6', '0', '6', '1755948055385', NULL, '2025-08-25 22:18:21');
INSERT INTO `production` VALUES ('33', '8', '20', '0', '20', '1755949301450', NULL, '2025-08-25 23:24:37');
INSERT INTO `production` VALUES ('34', '8', '5', '0', '5', '1755949301450', NULL, '2025-08-25 23:24:44');
INSERT INTO `production` VALUES ('35', '8', '2', '0', '2', '1755949301450', NULL, '2025-08-25 23:25:21');
INSERT INTO `production` VALUES ('36', '7', '21', '0', '19', '1755948055385', '2', '2025-08-25 23:39:43');
INSERT INTO `production` VALUES ('37', '7', '1', '1', '0', '1755948055385', NULL, '2025-08-25 23:40:07');
INSERT INTO `production` VALUES ('38', '7', '2', '2', '0', '1755948055385', NULL, '2025-08-26 09:38:39');
INSERT INTO `production` VALUES ('39', '8', '20', '0', '20', '1755949301450', NULL, '2025-08-26 16:50:44');
INSERT INTO `production` VALUES ('40', '7', '1', '1', '0', '1755948055385', NULL, '2025-08-26 18:20:56');
INSERT INTO `production` VALUES ('41', '8', '2', '0', '2', '1755949301450', NULL, '2025-08-27 11:24:05');
INSERT INTO `production` VALUES ('42', '8', '10', '7', '2', '1755949301450', '1', '2025-08-27 11:27:22');
INSERT INTO `production` VALUES ('43', '8', '1', '1', '0', '1755949301450', NULL, '2025-08-27 23:28:25');
INSERT INTO `production` VALUES ('44', '7', '20', '20', '0', '1755948055385', NULL, '2025-08-28 00:11:27');
INSERT INTO `production` VALUES ('45', '7', '2', '2', '0', '1755948055385', '0', '2025-08-28 01:03:53');

-- Table structure for table `purchase_order_items`
DROP TABLE IF EXISTS `purchase_order_items`;
CREATE TABLE `purchase_order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int NOT NULL,
  `inventory_id` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `received_quantity` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `purchase_order_id` (`purchase_order_id`),
  KEY `inventory_id` (`inventory_id`),
  CONSTRAINT `fk_po_items_to_inventory` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_po_items_to_po` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `purchase_order_items`
INSERT INTO `purchase_order_items` VALUES ('3', '5', '19', '20', '200.00', '20', '2025-08-26 12:46:04', '2025-08-26 12:49:15');
INSERT INTO `purchase_order_items` VALUES ('4', '5', '18', '20', '200.00', '20', '2025-08-26 12:46:04', '2025-08-26 12:49:15');
INSERT INTO `purchase_order_items` VALUES ('5', '6', '17', '100', '10.00', '100', '2025-08-26 12:52:31', '2025-08-26 13:24:43');
INSERT INTO `purchase_order_items` VALUES ('6', '7', '19', '15', '20.00', NULL, '2025-08-26 16:44:46', '2025-08-26 16:44:46');
INSERT INTO `purchase_order_items` VALUES ('7', '8', '20', '200', '10.00', NULL, '2025-08-26 17:54:56', '2025-08-26 17:54:56');
INSERT INTO `purchase_order_items` VALUES ('8', '9', '20', '200', '10.00', NULL, '2025-08-26 18:24:42', '2025-08-26 18:24:42');
INSERT INTO `purchase_order_items` VALUES ('9', '10', '20', '20', '10.00', '20', '2025-08-27 11:10:31', '2025-08-27 11:11:38');

-- Table structure for table `purchase_orders`
DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `po_number` varchar(50) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `order_date` date NOT NULL,
  `expected_delivery` date DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `po_number` (`po_number`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `purchase_orders`
INSERT INTO `purchase_orders` VALUES ('5', '1', 'Spice Masters', '2025-08-26', '2025-08-28', 'Received', '2025-08-26 12:46:04', '2025-08-26 12:49:15');
INSERT INTO `purchase_orders` VALUES ('6', '2', 'Farm Fresh Co', '2025-08-26', '2025-08-30', 'Received', '2025-08-26 12:52:31', '2025-08-26 13:24:43');
INSERT INTO `purchase_orders` VALUES ('7', '22334', 'Makiki Marketing', '2025-08-26', '2025-08-30', 'Pending', '2025-08-26 16:44:46', '2025-08-26 16:44:46');
INSERT INTO `purchase_orders` VALUES ('8', 'PO-1756202071252', 'Coca Cola', '2025-08-26', NULL, 'Ordered', '2025-08-26 17:54:56', '2025-08-26 17:55:34');
INSERT INTO `purchase_orders` VALUES ('9', 'PO-1756203861898', 'Coca Cola', '2025-08-26', '2025-08-27', 'Ordered', '2025-08-26 18:24:42', '2025-08-26 18:24:56');
INSERT INTO `purchase_orders` VALUES ('10', 'PO-1756264211553', 'CocaCola', '2025-08-27', '2025-08-28', 'Received', '2025-08-27 11:10:31', '2025-08-27 11:11:38');

-- Table structure for table `user`
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_type` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `sign_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table `user`
INSERT INTO `user` VALUES ('10', 'admin', 'Sheyste', 'Bene', 'sheystebenerable@gmail.com', '$2y$10$5hH3u9wG9HQN2n9UJl5fturO7r8C6J9dD5C17VpAopT1cFjTF6/Su', 'Admin', '2025-07-22 21:38:42');
INSERT INTO `user` VALUES ('11', 'user', 'dwads', 'dawdsda', 'user@user.com', '$2y$10$SHjJQub0F4rcnH0YZzEzLuUl8Y6Y/vr.UMKHDCFHt9aLunOGv5HOS', 'User', '2025-08-02 11:48:48');

