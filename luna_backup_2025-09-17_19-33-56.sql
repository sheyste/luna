-- Luna Database Backup
-- Generated on: 2025-09-17 19:33:56

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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `inventory`
INSERT INTO `inventory` VALUES ('17', 'Coffee Beans', 'Other', '1755947829873', '1475.00', '95.00', '100.00', 'Kg', '2025-08-22', '2025-08-23 19:17:09');
INSERT INTO `inventory` VALUES ('18', 'Almond Milk', 'Other', '1755947856126', '158.00', '100.00', '100.00', 'L', '2025-08-05', '2025-08-23 19:17:36');
INSERT INTO `inventory` VALUES ('19', 'Condensed Milk', 'Other', '1755948048271', '102.00', '100.00', '100.00', 'L', '2025-08-28', '2025-08-23 19:20:48');
INSERT INTO `inventory` VALUES ('20', 'Coke Swakto', 'Other', '1755949298187', '10.00', '100.00', '100.00', 'pcs', '2025-09-01', '2025-08-23 19:41:38');
INSERT INTO `inventory` VALUES ('26', 'All purpose flour', 'Canned', '1756371542892', '50.00', '2.00', '100.00', 'pcs', '2025-08-28', '2025-08-28 01:59:48');
INSERT INTO `inventory` VALUES ('28', 'chocolate chips bag', 'Other', '1756372302018', '80.00', '21.00', '100.00', 'Kg', '2025-09-01', '2025-08-28 02:12:24');
INSERT INTO `inventory` VALUES ('29', 'Bun', 'Other', '1756373032301', '10.00', '50.00', '100.00', 'pcs', '2025-08-22', '2025-08-28 02:24:37');
INSERT INTO `inventory` VALUES ('30', 'Hotdog', 'Meat', '1756373087061', '15.00', '74.00', '100.00', 'pcs', '2025-08-29', '2025-08-28 02:25:14');
INSERT INTO `inventory` VALUES ('32', 'Same Barocde', 'Vegetables', '2232', '21.00', '29.00', '21.00', 'pcs', '2025-09-06', '2025-09-06 13:08:26');

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
  `resolved` tinyint(1) NOT NULL DEFAULT '0',
  `sent_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `status` (`status`),
  CONSTRAINT `fk_low_stock_inventory` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `low_stock_alerts`
INSERT INTO `low_stock_alerts` VALUES ('79', '18', 'Almond Milk', '14.25', '100.00', 'L', '2025-08-31 01:57:43', 'sent', '1', '2025-08-31 01:57:47');
INSERT INTO `low_stock_alerts` VALUES ('80', '19', 'Condensed Milk', '15.40', '100.00', 'L', '2025-08-31 01:57:43', 'sent', '1', '2025-08-31 01:57:47');
INSERT INTO `low_stock_alerts` VALUES ('81', '20', 'Coke Swakto', '10.00', '100.00', 'pcs', '2025-08-31 01:57:43', 'sent', '1', '2025-08-31 01:57:47');
INSERT INTO `low_stock_alerts` VALUES ('82', '26', 'All purpose flour', '148.94', '1000.00', 'Kg', '2025-08-31 01:57:43', 'sent', '1', '2025-08-31 01:57:47');
INSERT INTO `low_stock_alerts` VALUES ('83', '28', 'chocolate chips bag', '2.60', '15.00', 'pcs', '2025-08-31 01:57:43', 'sent', '1', '2025-08-31 01:57:47');
INSERT INTO `low_stock_alerts` VALUES ('84', '29', 'Bun', '0.00', '100.00', 'pcs', '2025-08-31 01:57:43', 'sent', '1', '2025-08-31 01:57:47');
INSERT INTO `low_stock_alerts` VALUES ('85', '20', 'Coke Swakto', '10.00', '100.00', 'pcs', '2025-08-31 02:06:32', 'sent', '1', '2025-08-31 02:06:32');
INSERT INTO `low_stock_alerts` VALUES ('86', '20', 'Coke Swakto', '10.00', '100.00', 'pcs', '2025-08-31 02:23:56', 'sent', '1', '2025-08-31 02:23:59');
INSERT INTO `low_stock_alerts` VALUES ('87', '20', 'Coke Swakto', '0.00', '100.00', 'pcs', '2025-08-31 02:24:30', 'sent', '1', '2025-08-31 02:24:35');
INSERT INTO `low_stock_alerts` VALUES ('88', '20', 'Coke Swakto', '10.00', '100.00', 'pcs', '2025-08-31 02:33:22', 'sent', '1', '2025-08-31 02:33:26');
INSERT INTO `low_stock_alerts` VALUES ('89', '17', 'Coffee Beans', '10.00', '100.00', 'Kg', '2025-08-31 02:42:36', 'sent', '1', '2025-08-31 02:42:41');
INSERT INTO `low_stock_alerts` VALUES ('90', '29', 'Bun', '15.00', '100.00', 'pcs', '2025-08-31 02:43:48', 'sent', '1', '2025-08-31 02:45:02');
INSERT INTO `low_stock_alerts` VALUES ('91', '26', 'All purpose flour', '10.00', '100.00', 'Kg', '2025-08-31 12:49:46', 'sent', '1', '2025-08-31 12:49:47');
INSERT INTO `low_stock_alerts` VALUES ('92', '20', 'Coke Swakto', '10.00', '100.00', 'pcs', '2025-08-31 12:53:41', 'sent', '1', '2025-08-31 12:53:44');
INSERT INTO `low_stock_alerts` VALUES ('93', '26', 'All purpose flour', '1.00', '100.00', 'Kg', '2025-08-31 13:56:24', 'sent', '1', '2025-08-31 13:56:27');
INSERT INTO `low_stock_alerts` VALUES ('94', '20', 'Coke Swakto', '2.00', '100.00', 'pcs', '2025-08-31 14:21:36', 'sent', '1', '2025-08-31 14:22:01');
INSERT INTO `low_stock_alerts` VALUES ('95', '20', 'Coke Swakto', '2.00', '100.00', 'pcs', '2025-08-31 14:26:24', 'sent', '1', '2025-08-31 14:26:25');
INSERT INTO `low_stock_alerts` VALUES ('96', '17', 'Coffee Beans', '20.00', '100.00', 'Kg', '2025-08-31 14:28:36', 'sent', '1', '2025-08-31 14:28:43');
INSERT INTO `low_stock_alerts` VALUES ('97', '29', 'Bun', '8.00', '100.00', 'pcs', '2025-08-31 14:33:49', 'sent', '1', '2025-08-31 14:33:53');
INSERT INTO `low_stock_alerts` VALUES ('98', '18', 'Almond Milk', '6.00', '100.00', 'L', '2025-08-31 14:36:01', 'sent', '1', '2025-08-31 14:36:04');
INSERT INTO `low_stock_alerts` VALUES ('99', '26', 'All purpose flour', '9.00', '100.00', 'Kg', '2025-08-31 14:48:06', 'sent', '1', '2025-08-31 14:48:09');
INSERT INTO `low_stock_alerts` VALUES ('100', '30', 'Hotdog', '2.00', '100.00', 'pcs', '2025-08-31 14:51:43', 'sent', '1', '2025-08-31 14:51:45');
INSERT INTO `low_stock_alerts` VALUES ('101', '26', 'All purpose flour', '0.00', '100.00', 'Kg', '2025-08-31 14:58:03', 'sent', '1', '2025-08-31 14:58:06');
INSERT INTO `low_stock_alerts` VALUES ('102', '18', 'Almond Milk', '0.00', '100.00', 'L', '2025-08-31 14:58:03', 'sent', '1', '2025-08-31 14:58:06');
INSERT INTO `low_stock_alerts` VALUES ('103', '29', 'Bun', '0.00', '100.00', 'pcs', '2025-08-31 14:58:03', 'sent', '1', '2025-08-31 14:58:06');
INSERT INTO `low_stock_alerts` VALUES ('104', '26', 'All purpose flour', '2.00', '100.00', 'Kg', '2025-08-31 15:03:29', 'sent', '1', '2025-08-31 15:03:38');
INSERT INTO `low_stock_alerts` VALUES ('105', '18', 'Almond Milk', '2.00', '100.00', 'L', '2025-08-31 15:03:29', 'sent', '1', '2025-08-31 15:03:38');
INSERT INTO `low_stock_alerts` VALUES ('106', '29', 'Bun', '2.00', '100.00', 'pcs', '2025-08-31 15:03:29', 'sent', '1', '2025-08-31 15:03:38');
INSERT INTO `low_stock_alerts` VALUES ('107', '18', 'Almond Milk', '2.00', '100.00', 'L', '2025-08-31 15:18:39', 'sent', '1', '2025-08-31 15:18:42');
INSERT INTO `low_stock_alerts` VALUES ('108', '26', 'All purpose flour', '2.00', '100.00', 'Kg', '2025-08-31 15:18:39', 'sent', '1', '2025-08-31 15:18:42');
INSERT INTO `low_stock_alerts` VALUES ('109', '29', 'Bun', '2.00', '100.00', 'pcs', '2025-08-31 15:18:39', 'sent', '1', '2025-08-31 15:18:42');
INSERT INTO `low_stock_alerts` VALUES ('110', '18', 'Almond Milk', '2.00', '100.00', 'L', '2025-08-31 15:29:06', 'sent', '1', '2025-08-31 15:29:09');
INSERT INTO `low_stock_alerts` VALUES ('111', '26', 'All purpose flour', '2.00', '100.00', 'Kg', '2025-08-31 15:29:06', 'sent', '1', '2025-08-31 15:29:09');
INSERT INTO `low_stock_alerts` VALUES ('112', '29', 'Bun', '2.00', '100.00', 'pcs', '2025-08-31 15:29:06', 'sent', '1', '2025-08-31 15:29:09');
INSERT INTO `low_stock_alerts` VALUES ('113', '26', 'All purpose flour', '2.00', '100.00', 'Kg', '2025-08-31 15:34:17', 'sent', '1', '2025-08-31 15:34:23');
INSERT INTO `low_stock_alerts` VALUES ('114', '18', 'Almond Milk', '2.00', '100.00', 'L', '2025-08-31 15:34:17', 'sent', '1', '2025-08-31 15:34:23');
INSERT INTO `low_stock_alerts` VALUES ('115', '29', 'Bun', '2.00', '100.00', 'pcs', '2025-08-31 15:34:17', 'sent', '1', '2025-08-31 15:34:23');
INSERT INTO `low_stock_alerts` VALUES ('116', '26', 'All purpose flour', '7.00', '100.00', 'Kg', '2025-08-31 15:44:29', 'sent', '1', '2025-08-31 15:44:29');
INSERT INTO `low_stock_alerts` VALUES ('117', '18', 'Almond Milk', '7.00', '100.00', 'L', '2025-08-31 15:44:29', 'sent', '1', '2025-08-31 15:44:29');
INSERT INTO `low_stock_alerts` VALUES ('118', '29', 'Bun', '7.00', '100.00', 'pcs', '2025-08-31 15:44:29', 'sent', '1', '2025-08-31 15:44:29');
INSERT INTO `low_stock_alerts` VALUES ('119', '26', 'All purpose flour', '4.00', '100.00', 'Kg', '2025-08-31 15:51:10', 'sent', '1', '2025-08-31 15:51:13');
INSERT INTO `low_stock_alerts` VALUES ('120', '18', 'Almond Milk', '4.00', '100.00', 'L', '2025-08-31 15:51:10', 'sent', '1', '2025-08-31 15:51:13');
INSERT INTO `low_stock_alerts` VALUES ('121', '29', 'Bun', '4.00', '100.00', 'pcs', '2025-08-31 15:51:10', 'sent', '1', '2025-08-31 15:51:13');
INSERT INTO `low_stock_alerts` VALUES ('122', '28', 'chocolate chips bag', '16.00', '100.00', 'pcs', '2025-08-31 16:00:01', 'sent', '1', '2025-08-31 16:00:04');
INSERT INTO `low_stock_alerts` VALUES ('123', '17', 'Coffee Beans', '18.00', '100.00', 'Kg', '2025-08-31 16:01:38', 'sent', '1', '2025-08-31 16:01:39');
INSERT INTO `low_stock_alerts` VALUES ('124', '26', 'All purpose flour', '8.00', '100.00', 'Kg', '2025-08-31 16:06:05', 'sent', '1', '2025-08-31 16:06:11');
INSERT INTO `low_stock_alerts` VALUES ('125', '26', 'All purpose flour', '7.00', '100.00', 'Kg', '2025-08-31 16:10:53', 'sent', '1', '2025-08-31 16:10:55');
INSERT INTO `low_stock_alerts` VALUES ('126', '26', 'All purpose flour', '4.00', '100.00', 'Kg', '2025-08-31 16:13:29', 'sent', '1', '2025-08-31 16:13:31');
INSERT INTO `low_stock_alerts` VALUES ('127', '26', 'All purpose flour', '13.00', '100.00', 'Kg', '2025-08-31 16:33:38', 'sent', '1', '2025-08-31 16:33:42');
INSERT INTO `low_stock_alerts` VALUES ('128', '18', 'Almond Milk', '13.00', '100.00', 'L', '2025-08-31 16:33:38', 'sent', '1', '2025-08-31 16:33:42');
INSERT INTO `low_stock_alerts` VALUES ('129', '29', 'Bun', '13.00', '100.00', 'pcs', '2025-08-31 16:33:38', 'sent', '1', '2025-08-31 16:33:42');
INSERT INTO `low_stock_alerts` VALUES ('130', '19', 'Condensed Milk', '12.00', '100.00', 'L', '2025-08-31 19:29:12', 'sent', '1', '2025-08-31 19:29:21');
INSERT INTO `low_stock_alerts` VALUES ('131', '26', 'All purpose flour', '10.00', '100.00', 'Kg', '2025-09-01 21:56:25', 'sent', '1', '2025-09-01 21:56:28');
INSERT INTO `low_stock_alerts` VALUES ('132', '18', 'Almond Milk', '0.00', '100.00', 'L', '2025-09-03 15:01:05', 'sent', '1', '2025-09-03 15:01:17');
INSERT INTO `low_stock_alerts` VALUES ('133', '19', 'Condensed Milk', '4.00', '100.00', 'L', '2025-09-03 15:06:30', 'sent', '1', '2025-09-03 15:06:34');
INSERT INTO `low_stock_alerts` VALUES ('134', '19', 'Condensed Milk', '0.00', '100.00', 'L', '2025-09-06 12:11:40', 'sent', '1', '2025-09-06 12:11:42');
INSERT INTO `low_stock_alerts` VALUES ('135', '18', 'Almond Milk', '5.00', '100.00', 'L', '2025-09-06 12:17:30', 'sent', '1', '2025-09-06 12:17:34');
INSERT INTO `low_stock_alerts` VALUES ('136', '29', 'Bun', '20.00', '100.00', 'pcs', '2025-09-06 12:17:30', 'sent', '1', '2025-09-06 12:17:34');
INSERT INTO `low_stock_alerts` VALUES ('137', '32', 'Same Barocde', '4.00', '21.00', 'pcs', '2025-09-06 15:14:00', 'sent', '1', '2025-09-06 15:14:23');
INSERT INTO `low_stock_alerts` VALUES ('138', '26', 'All purpose flour', '9.00', '100.00', 'Kg', '2025-09-06 16:04:49', 'sent', '0', '2025-09-06 16:05:00');
INSERT INTO `low_stock_alerts` VALUES ('139', '20', 'Coke Swakto', '0.00', '100.00', 'pcs', '2025-09-06 16:06:06', 'sent', '1', '2025-09-06 16:06:14');
INSERT INTO `low_stock_alerts` VALUES ('140', '20', 'Coke Swakto', '11.00', '100.00', 'pcs', '2025-09-06 16:18:08', 'sent', '1', '2025-09-06 16:18:14');
INSERT INTO `low_stock_alerts` VALUES ('141', '20', 'Coke Swakto', '13.00', '100.00', 'pcs', '2025-09-06 16:18:58', 'sent', '1', '2025-09-06 16:19:05');
INSERT INTO `low_stock_alerts` VALUES ('142', '26', 'All purpose flour', '9.00', '100.00', 'Kg', '2025-09-08 02:03:37', 'sent', '0', '2025-09-08 02:03:42');
INSERT INTO `low_stock_alerts` VALUES ('143', '26', 'All purpose flour', '9.00', '100.00', 'Kg', '2025-09-10 07:28:21', 'sent', '0', '2025-09-10 07:28:30');
INSERT INTO `low_stock_alerts` VALUES ('144', '26', 'All purpose flour', '9.00', '100.00', 'Kg', '2025-09-12 00:14:05', 'sent', '0', '2025-09-12 00:14:10');
INSERT INTO `low_stock_alerts` VALUES ('145', '20', 'Coke Swakto', '10.00', '100.00', 'pcs', '2025-09-12 00:32:41', 'sent', '1', '2025-09-12 00:32:46');
INSERT INTO `low_stock_alerts` VALUES ('146', '20', 'Coke Swakto', '10.00', '100.00', 'pcs', '2025-09-16 02:30:56', 'sent', '1', '2025-09-16 02:31:00');
INSERT INTO `low_stock_alerts` VALUES ('147', '26', 'All purpose flour', '9.00', '100.00', 'Kg', '2025-09-16 02:30:56', 'sent', '0', '2025-09-16 02:31:00');
INSERT INTO `low_stock_alerts` VALUES ('148', '26', 'All purpose flour', '2.00', '100.00', 'Kg', '2025-09-17 19:46:04', 'sent', '0', '2025-09-17 19:46:10');

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
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `menu_ingredients`
INSERT INTO `menu_ingredients` VALUES ('15', '7', '18', '0.25');
INSERT INTO `menu_ingredients` VALUES ('16', '7', '17', '0.01');
INSERT INTO `menu_ingredients` VALUES ('17', '7', '19', '0.20');
INSERT INTO `menu_ingredients` VALUES ('18', '8', '20', '1.00');
INSERT INTO `menu_ingredients` VALUES ('35', '15', '29', '1.00');
INSERT INTO `menu_ingredients` VALUES ('36', '15', '30', '1.00');
INSERT INTO `menu_ingredients` VALUES ('46', '14', '26', '0.14');
INSERT INTO `menu_ingredients` VALUES ('47', '14', '28', '0.20');
INSERT INTO `menu_ingredients` VALUES ('48', '17', '19', '1.00');
INSERT INTO `menu_ingredients` VALUES ('49', '20', '32', '1.00');

-- Table structure for table `menus`
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `barcode` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `menus`
INSERT INTO `menus` VALUES ('7', 'Spanish Latte', '210.00', '1755948055385', '2025-08-23 19:22:20');
INSERT INTO `menus` VALUES ('8', 'Coke Swakto', '20.00', '1755949301450', '2025-08-23 19:41:51');
INSERT INTO `menus` VALUES ('14', 'Black Forest', '105.00', '1756371615076', '2025-08-28 02:00:31');
INSERT INTO `menus` VALUES ('15', 'Burger', '50.00', '1756373129990', '2025-08-28 02:26:27');
INSERT INTO `menus` VALUES ('17', 'condense milk only', '150.00', '1756574082318', '2025-08-31 01:14:55');
INSERT INTO `menus` VALUES ('20', 'Same BARC', '55.00', '2232', '2025-09-17 00:37:46');

-- Table structure for table `physical_count_entries`
DROP TABLE IF EXISTS `physical_count_entries`;
CREATE TABLE `physical_count_entries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `inventory_id` int NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `system_count` decimal(10,2) NOT NULL DEFAULT '0.00',
  `physical_count` decimal(10,2) NOT NULL DEFAULT '0.00',
  `difference` decimal(10,2) NOT NULL DEFAULT '0.00',
  `variance_percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `value_impact` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','saved') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `inventory_id` (`inventory_id`),
  KEY `status` (`status`),
  CONSTRAINT `fk_physical_count_inventory` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `physical_count_entries`
INSERT INTO `physical_count_entries` VALUES ('1', '29', 'Bun', '13.00', '50.00', '37.00', '284.62', '370.00', '10.00', 'saved', '2025-08-31 18:56:35', '2025-08-31 19:29:12');
INSERT INTO `physical_count_entries` VALUES ('4', '28', 'chocolate chips bag', '100.00', '21.00', '-79.00', '-79.00', '-6320.00', '80.00', 'saved', '2025-08-31 19:06:14', '2025-08-31 19:29:12');
INSERT INTO `physical_count_entries` VALUES ('5', '26', 'All purpose flour', '13.00', '10.00', '-3.00', '-23.08', '-150.00', '50.00', 'saved', '2025-08-31 19:06:31', '2025-08-31 19:29:12');
INSERT INTO `physical_count_entries` VALUES ('6', '19', 'Condensed Milk', '100.00', '12.00', '-88.00', '-88.00', '-8976.00', '102.00', 'saved', '2025-08-31 19:28:43', '2025-08-31 19:29:12');
INSERT INTO `physical_count_entries` VALUES ('7', '19', 'Condensed Milk', '12.00', '100.00', '88.00', '733.33', '8976.00', '102.00', 'saved', '2025-08-31 19:31:44', '2025-08-31 19:32:10');
INSERT INTO `physical_count_entries` VALUES ('8', '18', 'Almond Milk', '13.00', '100.00', '87.00', '669.23', '13746.00', '158.00', 'saved', '2025-08-31 19:31:59', '2025-08-31 19:36:34');
INSERT INTO `physical_count_entries` VALUES ('9', '18', 'Almond Milk', '100.00', '90.00', '-10.00', '-10.00', '-1580.00', '158.00', 'saved', '2025-08-31 19:42:29', '2025-08-31 20:09:42');
INSERT INTO `physical_count_entries` VALUES ('10', '30', 'Hotdog', '100.00', '90.00', '-10.00', '-10.00', '-150.00', '15.00', 'saved', '2025-08-31 20:09:32', '2025-08-31 20:09:42');
INSERT INTO `physical_count_entries` VALUES ('11', '29', 'Bun', '50.00', '100.00', '50.00', '100.00', '500.00', '10.00', 'saved', '2025-08-31 20:21:46', '2025-08-31 20:22:51');
INSERT INTO `physical_count_entries` VALUES ('12', '29', 'Bun', '100.00', '80.00', '-20.00', '-20.00', '-200.00', '10.00', 'saved', '2025-09-01 22:42:21', '2025-09-03 19:41:16');
INSERT INTO `physical_count_entries` VALUES ('13', '26', 'All purpose flour', '100.00', '90.00', '-10.00', '-10.00', '-500.00', '50.00', 'saved', '2025-09-03 13:05:20', '2025-09-03 19:41:16');
INSERT INTO `physical_count_entries` VALUES ('14', '30', 'Hotdog', '90.00', '95.00', '5.00', '5.56', '75.00', '15.00', 'saved', '2025-09-03 13:40:00', '2025-09-03 19:41:16');
INSERT INTO `physical_count_entries` VALUES ('15', '29', 'Bun', '80.00', '80.00', '0.00', '0.00', '0.00', '10.00', 'saved', '2025-09-03 19:42:44', '2025-09-06 15:24:34');
INSERT INTO `physical_count_entries` VALUES ('16', '26', 'All purpose flour', '90.00', '88.00', '-2.00', '-2.22', '-100.00', '50.00', 'saved', '2025-09-06 12:16:10', '2025-09-06 12:16:21');
INSERT INTO `physical_count_entries` VALUES ('17', '29', 'Bun', '59.00', '50.00', '-9.00', '-15.25', '-90.00', '10.00', 'saved', '2025-09-17 00:33:36', '2025-09-17 00:53:41');

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
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
INSERT INTO `production` VALUES ('53', '17', '30', '0', '30', '1756574082318', '0', '2025-08-31 01:15:11');
INSERT INTO `production` VALUES ('54', '8', '90', '0', '90', '1755949301450', '0', '2025-08-31 01:36:13');
INSERT INTO `production` VALUES ('55', '8', '90', '0', '90', '1755949301450', '0', '2025-08-31 01:42:33');
INSERT INTO `production` VALUES ('56', '8', '10', '0', '10', '1755949301450', '0', '2025-08-31 01:43:11');
INSERT INTO `production` VALUES ('57', '17', '10', '0', '10', '1756574082318', '0', '2025-08-31 01:43:58');
INSERT INTO `production` VALUES ('58', '8', '90', '0', '90', '1755949301450', '0', '2025-08-31 02:06:32');
INSERT INTO `production` VALUES ('59', '8', '90', '0', '90', '1755949301450', '0', '2025-08-31 02:23:56');
INSERT INTO `production` VALUES ('60', '8', '100', '0', '100', '1755949301450', '0', '2025-08-31 02:24:30');
INSERT INTO `production` VALUES ('61', '8', '90', '0', '90', '1755949301450', '0', '2025-08-31 02:33:22');
INSERT INTO `production` VALUES ('62', '8', '90', '0', '90', '1755949301450', '0', '2025-08-31 12:53:41');
INSERT INTO `production` VALUES ('63', '8', '98', '0', '98', '1755949301450', '0', '2025-08-31 14:21:36');
INSERT INTO `production` VALUES ('64', '8', '98', '98', '0', '1755949301450', '0', '2025-08-31 14:26:24');
INSERT INTO `production` VALUES ('71', '8', '20', '20', '0', '1755949301450', '0', '2025-09-03 13:54:57');
INSERT INTO `production` VALUES ('72', '8', '200', '200', '0', '1755949301450', '0', '2025-09-03 13:55:21');
INSERT INTO `production` VALUES ('73', '7', '360', '0', '360', '1755948055385', '0', '2025-09-03 15:01:05');
INSERT INTO `production` VALUES ('74', '7', '120', '0', '120', '1755948055385', '0', '2025-09-03 15:06:29');
INSERT INTO `production` VALUES ('75', '7', '20', '0', '20', '1755948055385', '0', '2025-09-03 15:07:00');
INSERT INTO `production` VALUES ('81', '8', '80', '80', '0', '1755949301450', '0', '2025-09-06 16:06:06');
INSERT INTO `production` VALUES ('82', '8', '89', '89', '0', '1755949301450', '0', '2025-09-06 16:18:08');
INSERT INTO `production` VALUES ('83', '8', '87', '87', '0', '1755949301450', '0', '2025-09-06 16:18:58');
INSERT INTO `production` VALUES ('84', '8', '90', '90', '0', '1755949301450', '0', '2025-09-12 00:32:41');
INSERT INTO `production` VALUES ('85', '15', '21', '0', '21', '1756373129990', '0', '2025-09-16 03:55:52');
INSERT INTO `production` VALUES ('86', '14', '50', '50', '0', '1756371615076', '0', '2025-09-17 00:14:17');
INSERT INTO `production` VALUES ('88', '20', '50', '14', '34', '2232', '2', '2025-09-17 00:44:34');

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `purchase_order_items`
INSERT INTO `purchase_order_items` VALUES ('3', '5', '19', '20', '200.00', '20', '2025-08-26 12:46:04', '2025-08-26 12:49:15');
INSERT INTO `purchase_order_items` VALUES ('4', '5', '18', '20', '200.00', '20', '2025-08-26 12:46:04', '2025-08-26 12:49:15');
INSERT INTO `purchase_order_items` VALUES ('5', '6', '17', '100', '10.00', '100', '2025-08-26 12:52:31', '2025-08-26 13:24:43');
INSERT INTO `purchase_order_items` VALUES ('6', '7', '19', '15', '20.00', '20', '2025-08-26 16:44:46', '2025-08-28 01:09:33');
INSERT INTO `purchase_order_items` VALUES ('7', '8', '20', '200', '10.00', NULL, '2025-08-26 17:54:56', '2025-08-26 17:54:56');
INSERT INTO `purchase_order_items` VALUES ('8', '9', '20', '200', '10.00', '200', '2025-08-26 18:24:42', '2025-09-01 21:59:31');
INSERT INTO `purchase_order_items` VALUES ('9', '10', '20', '20', '10.00', '20', '2025-08-27 11:10:31', '2025-08-27 11:11:38');
INSERT INTO `purchase_order_items` VALUES ('10', '11', '20', '93', '10.00', '93', '2025-08-28 01:52:24', '2025-08-28 01:52:36');
INSERT INTO `purchase_order_items` VALUES ('11', '12', '28', '9', '70.00', NULL, '2025-08-28 02:17:59', '2025-08-28 02:17:59');
INSERT INTO `purchase_order_items` VALUES ('12', '13', '28', '10', '80.00', '10', '2025-08-28 02:22:39', '2025-09-01 21:59:14');
INSERT INTO `purchase_order_items` VALUES ('13', '14', '26', '12', '20.00', '12', '2025-08-28 22:07:58', '2025-08-28 22:19:52');
INSERT INTO `purchase_order_items` VALUES ('14', '15', '26', '300', '50.00', '20', '2025-08-28 22:20:15', '2025-08-28 22:20:34');
INSERT INTO `purchase_order_items` VALUES ('15', '16', '20', '100', '15.00', '100', '2025-08-31 01:35:50', '2025-08-31 01:35:58');
INSERT INTO `purchase_order_items` VALUES ('16', '17', '20', '100', '15.00', '100', '2025-08-31 01:42:00', '2025-08-31 01:42:07');
INSERT INTO `purchase_order_items` VALUES ('17', '18', '29', '12', '5.00', NULL, '2025-09-01 21:58:09', '2025-09-01 21:58:09');
INSERT INTO `purchase_order_items` VALUES ('18', '19', '29', '32', '213.00', NULL, '2025-09-16 02:57:06', '2025-09-16 02:57:06');
INSERT INTO `purchase_order_items` VALUES ('19', '19', '17', '23', '432.00', NULL, '2025-09-16 02:57:06', '2025-09-16 02:57:06');

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `purchase_orders`
INSERT INTO `purchase_orders` VALUES ('5', 'PO-175620946555', 'Spice Masters', '2025-08-26', '2025-08-28', 'Received', '2025-08-26 12:46:04', '2025-08-28 06:40:21');
INSERT INTO `purchase_orders` VALUES ('6', 'PO-1754567211553', 'Farm Fresh Co', '2025-08-26', '2025-08-30', 'Received', '2025-08-26 12:52:31', '2025-08-28 06:40:06');
INSERT INTO `purchase_orders` VALUES ('7', 'PO-1756264212345', 'Makiki Marketing', '2025-08-26', '2025-08-30', 'Received', '2025-08-26 16:44:46', '2025-08-28 06:39:57');
INSERT INTO `purchase_orders` VALUES ('8', 'PO-1756202071252', 'Coca Cola', '2025-08-26', NULL, 'Ordered', '2025-08-26 17:54:56', '2025-08-26 17:55:34');
INSERT INTO `purchase_orders` VALUES ('9', 'PO-1756203861898', 'Coca Cola', '2025-08-26', '2025-08-27', 'Received', '2025-08-26 18:24:42', '2025-09-01 21:59:31');
INSERT INTO `purchase_orders` VALUES ('10', 'PO-1756264211553', 'CocaCola', '2025-08-27', '2025-08-28', 'Received', '2025-08-27 11:10:31', '2025-08-27 11:11:38');
INSERT INTO `purchase_orders` VALUES ('11', 'PO-1756371126399', 'Coca Cola', '2025-08-28', '2025-08-29', 'Received', '2025-08-28 01:52:24', '2025-08-28 01:52:36');
INSERT INTO `purchase_orders` VALUES ('12', 'PO-1756372651733', 'yan yan', '2025-08-28', NULL, 'Ordered', '2025-08-28 02:17:59', '2025-08-28 02:17:59');
INSERT INTO `purchase_orders` VALUES ('13', 'PO-1756372932252', 'yan yan', '2025-08-28', '2025-08-31', 'Received', '2025-08-28 02:22:39', '2025-09-01 21:59:14');
INSERT INTO `purchase_orders` VALUES ('14', 'PO-1756390064474', 'Test', '2025-08-28', '2025-08-29', 'Received', '2025-08-28 22:07:58', '2025-08-28 22:19:52');
INSERT INTO `purchase_orders` VALUES ('15', 'PO-1756390800017', 'test2', '2025-08-28', '2025-08-29', 'Received', '2025-08-28 22:20:15', '2025-08-28 22:20:34');
INSERT INTO `purchase_orders` VALUES ('16', 'PO-1756575335174', 'Coca Cola', '2025-08-30', '2025-09-01', 'Received', '2025-08-31 01:35:50', '2025-08-31 01:35:58');
INSERT INTO `purchase_orders` VALUES ('17', 'PO-1756575711660', 'wdads', '2025-08-30', NULL, 'Received', '2025-08-31 01:42:00', '2025-08-31 01:42:07');
INSERT INTO `purchase_orders` VALUES ('18', 'PO-1756735080518', 'wdads', '2025-09-01', '2025-09-25', 'Pending', '2025-09-01 21:58:09', '2025-09-16 04:13:24');
INSERT INTO `purchase_orders` VALUES ('19', 'PO-1757962603790', 'Asdwa', '2025-09-15', '2025-09-17', 'Ordered', '2025-09-16 02:57:06', '2025-09-16 02:57:26');

-- Table structure for table `user`
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_type` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `sign_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `receive_email` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether user wants to receive emails (0=No, 1=Yes)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table `user`
INSERT INTO `user` VALUES ('10', 'admin', 'Sheyste', 'Benerable', 'sbenerable.student@asiancollege.edu.ph', '$2y$10$5hH3u9wG9HQN2n9UJl5fturO7r8C6J9dD5C17VpAopT1cFjTF6/Su', 'Admin', '2025-07-22 21:38:42', '1');
INSERT INTO `user` VALUES ('11', 'user', 'Account', 'User', 'sheystebenerable@gmail.com', '$2y$10$SHjJQub0F4rcnH0YZzEzLuUl8Y6Y/vr.UMKHDCFHt9aLunOGv5HOS', 'User', '2025-08-02 11:48:48', '0');

