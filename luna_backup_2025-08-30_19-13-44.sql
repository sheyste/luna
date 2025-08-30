-- Luna Database Backup
-- Generated on: 2025-08-30 19:13:44

-- Table structure for table `inventory`
DROP TABLE IF EXISTS `inventory`;
CREATE TABLE `inventory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(100) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `inventory`
INSERT INTO `inventory` VALUES ('17', 'Coffee Beans', '', '1755947829873', '1475.00', '109.77', '100.00', 'Kg', '2025-08-22', '2025-08-23 19:17:09');
INSERT INTO `inventory` VALUES ('18', 'Almond Milk', '', '1755947856126', '158.00', '24.25', '100.00', 'L', '2025-08-05', '2025-08-23 19:17:36');
INSERT INTO `inventory` VALUES ('19', 'Condensed Milk', '', '1755948048271', '102.00', '45.40', '100.00', 'L', '2025-08-28', '2025-08-23 19:20:48');
INSERT INTO `inventory` VALUES ('20', 'Coke Swakto', '', '1755949298187', '10.00', '0.00', '100.00', 'pcs', '2025-08-28', '2025-08-23 19:41:38');
INSERT INTO `inventory` VALUES ('26', 'All purpose flour', 'Canned', '1756371542892', '50.00', '148.94', '1000.00', 'Kg', '2025-08-28', '2025-08-28 01:59:48');
INSERT INTO `inventory` VALUES ('27', 'condense milk', '', '1756372261350', '47.00', '38.00', '120.00', 'pcs', '2025-08-29', '2025-08-28 02:11:28');
INSERT INTO `inventory` VALUES ('28', 'chocolate chips bag', '', '1756372302018', '80.00', '2.60', '15.00', 'pcs', '2025-08-29', '2025-08-28 02:12:24');
INSERT INTO `inventory` VALUES ('29', 'Bun', '', '1756373032301', '10.00', '0.00', '100.00', 'pcs', '2025-08-22', '2025-08-28 02:24:37');
INSERT INTO `inventory` VALUES ('30', 'Hotdog', '', '1756373087061', '15.00', '50.00', '100.00', 'pcs', '2025-08-29', '2025-08-28 02:25:14');

-- Table structure for table `low_stock_alerts`
DROP TABLE IF EXISTS `low_stock_alerts`;
CREATE TABLE `low_stock_alerts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `current_quantity` decimal(10,2) NOT NULL,
  `max_quantity` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `alert_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','sent','resolved') NOT NULL DEFAULT 'pending',
  `sent_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `status` (`status`),
  CONSTRAINT `fk_low_stock_inventory` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `low_stock_alerts`
INSERT INTO `low_stock_alerts` VALUES ('1', '20', 'Coke Swakto', '0.00', '100.00', 'pcs', '2025-08-31 01:04:53', 'sent', '2025-08-31 01:11:02');
INSERT INTO `low_stock_alerts` VALUES ('2', '26', 'All purpose flour', '148.94', '1000.00', 'Kg', '2025-08-31 01:04:53', 'sent', '2025-08-31 01:11:02');
INSERT INTO `low_stock_alerts` VALUES ('3', '28', 'chocolate chips bag', '2.60', '15.00', 'pcs', '2025-08-31 01:04:53', 'sent', '2025-08-31 01:11:02');
INSERT INTO `low_stock_alerts` VALUES ('4', '29', 'Bun', '0.00', '100.00', 'pcs', '2025-08-31 01:04:53', 'sent', '2025-08-31 01:11:02');

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `menu_ingredients`
INSERT INTO `menu_ingredients` VALUES ('15', '7', '18', '0.25');
INSERT INTO `menu_ingredients` VALUES ('16', '7', '17', '0.01');
INSERT INTO `menu_ingredients` VALUES ('17', '7', '19', '0.20');
INSERT INTO `menu_ingredients` VALUES ('18', '8', '20', '1.00');
INSERT INTO `menu_ingredients` VALUES ('32', '14', '26', '0.13');
INSERT INTO `menu_ingredients` VALUES ('33', '14', '28', '0.20');
INSERT INTO `menu_ingredients` VALUES ('34', '14', '27', '0.70');
INSERT INTO `menu_ingredients` VALUES ('35', '15', '29', '1.00');
INSERT INTO `menu_ingredients` VALUES ('36', '15', '30', '1.00');
INSERT INTO `menu_ingredients` VALUES ('37', '16', '20', '1.00');

-- Table structure for table `menus`
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `barcode` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `menus`
INSERT INTO `menus` VALUES ('7', 'Spanish Latte', '210.00', '1755948055385', '2025-08-23 19:22:20');
INSERT INTO `menus` VALUES ('8', 'Coke Swakto', '20.00', '1755949301450', '2025-08-23 19:41:51');
INSERT INTO `menus` VALUES ('14', 'Black Forest', '105.00', '1756371615076', '2025-08-28 02:00:31');
INSERT INTO `menus` VALUES ('15', 'Burger', '50.00', '1756373129990', '2025-08-28 02:26:27');
INSERT INTO `menus` VALUES ('16', 'New Item', '12.00', '1756394999262', '2025-08-28 23:30:06');

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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `production`
INSERT INTO `production` VALUES ('30', '7', '6', '0', '6', '1755948055385', NULL, '2025-08-25 22:18:21');
INSERT INTO `production` VALUES ('33', '8', '20', '0', '20', '1755949301450', NULL, '2025-08-25 23:24:37');
INSERT INTO `production` VALUES ('34', '8', '5', '0', '5', '1755949301450', NULL, '2025-08-25 23:24:44');
INSERT INTO `production` VALUES ('35', '8', '2', '0', '2', '1755949301450', NULL, '2025-08-25 23:25:21');
INSERT INTO `production` VALUES ('36', '7', '21', '0', '19', '1755948055385', '2', '2025-08-25 23:39:43');
INSERT INTO `production` VALUES ('37', '7', '1', '0', '0', '1755948055385', '1', '2025-08-25 23:40:07');
INSERT INTO `production` VALUES ('38', '7', '2', '0', '0', '1755948055385', '2', '2025-08-26 09:38:39');
INSERT INTO `production` VALUES ('39', '8', '20', '0', '20', '1755949301450', NULL, '2025-08-26 16:50:44');
INSERT INTO `production` VALUES ('40', '7', '1', '0', '0', '1755948055385', '1', '2025-08-26 18:20:56');
INSERT INTO `production` VALUES ('41', '8', '2', '0', '2', '1755949301450', NULL, '2025-08-27 11:24:05');
INSERT INTO `production` VALUES ('42', '8', '10', '0', '2', '1755949301450', '8', '2025-08-27 11:27:22');
INSERT INTO `production` VALUES ('43', '8', '1', '0', '0', '1755949301450', '1', '2025-08-27 23:28:25');
INSERT INTO `production` VALUES ('44', '7', '20', '0', '0', '1755948055385', '20', '2025-08-28 00:11:27');
INSERT INTO `production` VALUES ('45', '7', '2', '0', '0', '1755948055385', '2', '2025-08-28 01:03:53');
INSERT INTO `production` VALUES ('47', '8', '80', '0', '50', '1755949301450', '30', '2025-08-28 01:53:10');
INSERT INTO `production` VALUES ('50', '14', '12', '0', '12', '1756371615076', '0', '2025-08-28 02:18:50');
INSERT INTO `production` VALUES ('51', '15', '50', '0', '45', '1756373129990', '5', '2025-08-28 02:27:01');

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
  CONSTRAINT `fk_po_items_to_inventory` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_po_items_to_po` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `purchase_order_items`
INSERT INTO `purchase_order_items` VALUES ('3', '5', '19', '20', '200.00', '20', '2025-08-26 12:46:04', '2025-08-26 12:49:15');
INSERT INTO `purchase_order_items` VALUES ('4', '5', '18', '20', '200.00', '20', '2025-08-26 12:46:04', '2025-08-26 12:49:15');
INSERT INTO `purchase_order_items` VALUES ('5', '6', '17', '100', '10.00', '100', '2025-08-26 12:52:31', '2025-08-26 13:24:43');
INSERT INTO `purchase_order_items` VALUES ('6', '7', '19', '15', '20.00', '20', '2025-08-26 16:44:46', '2025-08-28 01:09:33');
INSERT INTO `purchase_order_items` VALUES ('7', '8', '20', '200', '10.00', NULL, '2025-08-26 17:54:56', '2025-08-26 17:54:56');
INSERT INTO `purchase_order_items` VALUES ('8', '9', '20', '200', '10.00', NULL, '2025-08-26 18:24:42', '2025-08-26 18:24:42');
INSERT INTO `purchase_order_items` VALUES ('9', '10', '20', '20', '10.00', '20', '2025-08-27 11:10:31', '2025-08-27 11:11:38');
INSERT INTO `purchase_order_items` VALUES ('10', '11', '20', '93', '10.00', '93', '2025-08-28 01:52:24', '2025-08-28 01:52:36');
INSERT INTO `purchase_order_items` VALUES ('11', '12', '28', '9', '70.00', NULL, '2025-08-28 02:17:59', '2025-08-28 02:17:59');
INSERT INTO `purchase_order_items` VALUES ('12', '13', '28', '10', '80.00', NULL, '2025-08-28 02:22:39', '2025-08-28 02:22:39');
INSERT INTO `purchase_order_items` VALUES ('13', '14', '26', '12', '20.00', '12', '2025-08-28 22:07:58', '2025-08-28 22:19:52');
INSERT INTO `purchase_order_items` VALUES ('14', '15', '26', '300', '50.00', '20', '2025-08-28 22:20:15', '2025-08-28 22:20:34');

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `purchase_orders`
INSERT INTO `purchase_orders` VALUES ('5', 'PO-175620946555', 'Spice Masters', '2025-08-26', '2025-08-28', 'Received', '2025-08-26 12:46:04', '2025-08-28 06:40:21');
INSERT INTO `purchase_orders` VALUES ('6', 'PO-1754567211553', 'Farm Fresh Co', '2025-08-26', '2025-08-30', 'Received', '2025-08-26 12:52:31', '2025-08-28 06:40:06');
INSERT INTO `purchase_orders` VALUES ('7', 'PO-1756264212345', 'Makiki Marketing', '2025-08-26', '2025-08-30', 'Received', '2025-08-26 16:44:46', '2025-08-28 06:39:57');
INSERT INTO `purchase_orders` VALUES ('8', 'PO-1756202071252', 'Coca Cola', '2025-08-26', NULL, 'Ordered', '2025-08-26 17:54:56', '2025-08-26 17:55:34');
INSERT INTO `purchase_orders` VALUES ('9', 'PO-1756203861898', 'Coca Cola', '2025-08-26', '2025-08-27', 'Ordered', '2025-08-26 18:24:42', '2025-08-26 18:24:56');
INSERT INTO `purchase_orders` VALUES ('10', 'PO-1756264211553', 'CocaCola', '2025-08-27', '2025-08-28', 'Received', '2025-08-27 11:10:31', '2025-08-27 11:11:38');
INSERT INTO `purchase_orders` VALUES ('11', 'PO-1756371126399', 'Coca Cola', '2025-08-28', '2025-08-29', 'Received', '2025-08-28 01:52:24', '2025-08-28 01:52:36');
INSERT INTO `purchase_orders` VALUES ('12', 'PO-1756372651733', 'yan yan', '2025-08-28', NULL, 'Ordered', '2025-08-28 02:17:59', '2025-08-28 02:17:59');
INSERT INTO `purchase_orders` VALUES ('13', 'PO-1756372932252', 'yan yan', '2025-08-28', '2025-09-02', 'Ordered', '2025-08-28 02:22:39', '2025-08-28 02:22:39');
INSERT INTO `purchase_orders` VALUES ('14', 'PO-1756390064474', 'Test', '2025-08-28', '2025-08-29', 'Received', '2025-08-28 22:07:58', '2025-08-28 22:19:52');
INSERT INTO `purchase_orders` VALUES ('15', 'PO-1756390800017', 'test2', '2025-08-28', '2025-08-29', 'Received', '2025-08-28 22:20:15', '2025-08-28 22:20:34');

-- Table structure for table `user`
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_type` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `sign_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table `user`
INSERT INTO `user` VALUES ('10', 'admin', 'Sheyste', 'Benerable', 'sheystebenerable@gmail.com', '$2y$10$5hH3u9wG9HQN2n9UJl5fturO7r8C6J9dD5C17VpAopT1cFjTF6/Su', 'Admin', '2025-07-22 21:38:42');
INSERT INTO `user` VALUES ('11', 'user', 'dwads', 'hellowd', 'sbenerable.student@asiancollege.edu.ph', '$2y$10$SHjJQub0F4rcnH0YZzEzLuUl8Y6Y/vr.UMKHDCFHt9aLunOGv5HOS', 'User', '2025-08-02 11:48:48');

