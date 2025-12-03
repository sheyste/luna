-- Luna Database Backup
-- Generated on: 2025-12-03 01:44:25

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
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `inventory`
INSERT INTO `inventory` VALUES ('38', 'Butter/Margarine 200g', 'Dairy', '1764652921802', '60.00', '6.60', '25.00', 'pcs', '2025-09-17', '2025-12-02 13:24:11');
INSERT INTO `inventory` VALUES ('39', 'bay leaf / laurel leaf 15g pack', 'Spices', '135194728136', '7.00', '3.00', '10.00', 'pcs', '2025-09-17', '2025-12-02 13:29:23');
INSERT INTO `inventory` VALUES ('40', 'Granulated black pepper 35g pack', 'Spices', '1764653495337', '25.00', '2.00', '5.00', 'pcs', '2025-09-09', '2025-12-02 13:31:59');
INSERT INTO `inventory` VALUES ('41', 'tomato sauce 250g pack', 'Condiments', '1764653703350', '28.00', '45.00', '60.00', 'pcs', '2025-08-31', '2025-12-02 13:36:39');
INSERT INTO `inventory` VALUES ('42', 'Salt', 'Spices', '1764653882562', '25.00', '5.90', '10.00', 'Kg', '2025-09-04', '2025-12-02 13:38:56');
INSERT INTO `inventory` VALUES ('43', 'Garlic', 'Vegetables', '1764653970972', '0.16', '1000.00', '2000.00', 'g', '2025-09-25', '2025-12-02 13:41:28');
INSERT INTO `inventory` VALUES ('45', 'Ground Beef', 'Meat', '1764654130777', '430.00', '5.00', '10.00', 'Kg', '2025-10-06', '2025-12-02 13:43:27');
INSERT INTO `inventory` VALUES ('46', 'Carrots', 'Vegetables', '1764654268956', '130.00', '3.00', '5.00', 'Kg', '2025-09-25', '2025-12-02 13:45:35');
INSERT INTO `inventory` VALUES ('47', 'Oregano 10g', 'Spices', '1764654436737', '77.00', '26.00', '50.00', 'pcs', '2025-09-03', '2025-12-02 13:48:01');
INSERT INTO `inventory` VALUES ('48', 'White Sugar', 'Spices', '1764654533643', '0.20', '1500.00', '3000.00', 'g', '2025-09-05', '2025-12-02 13:49:10');
INSERT INTO `inventory` VALUES ('49', 'Pepper', 'Vegetables', '1764654272370', '170.00', '5.00', '10.00', 'Kg', '2025-11-17', '2025-12-02 13:51:49');
INSERT INTO `inventory` VALUES ('50', 'Parmesan cheese 454g', 'Dairy', '1755948055385', '690.00', '2.00', '4.00', 'pcs', '2025-09-15', '2025-12-02 13:52:10');
INSERT INTO `inventory` VALUES ('51', 'Quickmely cheese 430g', 'Dairy', '1764655094881', '220.00', '5.00', '10.00', 'pcs', '2025-09-12', '2025-12-02 13:58:25');
INSERT INTO `inventory` VALUES ('52', 'Ketchup 320g', 'Condiments', '1764655168915', '32.25', '11.00', '30.00', 'pcs', '2025-08-31', '2025-12-02 14:02:03');
INSERT INTO `inventory` VALUES ('53', 'Mayonnaise 220ml', 'Condiments', '1764655391729', '124.75', '27.00', '40.00', 'pcs', '2025-08-26', '2025-12-02 14:03:56');
INSERT INTO `inventory` VALUES ('54', 'Chicken Breast ', 'Meat', '1764655574790', '228.00', '5.00', '15.00', 'Kg', '2025-09-12', '2025-12-02 14:07:25');
INSERT INTO `inventory` VALUES ('55', 'Brown Sugar', 'Spices', '1764655696465', '73.00', '3.00', '5.00', 'Kg', '2025-08-25', '2025-12-02 14:08:33');
INSERT INTO `inventory` VALUES ('56', 'Celery', 'Vegetables', '1764655383602', '0.38', '800.00', '1000.00', 'g', '2025-07-26', '2025-12-02 14:08:51');
INSERT INTO `inventory` VALUES ('57', 'Ginger ', 'Vegetables', '1764655766571', '120.00', '2.00', '3.00', 'Kg', '2025-08-29', '2025-12-02 14:10:08');
INSERT INTO `inventory` VALUES ('58', 'Soy Sauce', 'Condiments', '1764655892742', '52.00', '7.00', '15.00', 'L', '2025-09-04', '2025-12-02 14:14:01');
INSERT INTO `inventory` VALUES ('59', 'Vinegar', 'Condiments', '1764656109260', '43.00', '8.00', '15.00', 'L', '2025-09-08', '2025-12-02 14:15:41');
INSERT INTO `inventory` VALUES ('60', 'Tomato paste 250g pack', 'Condiments', '1764655841550', '26.50', '35.00', '60.00', 'pcs', '2025-05-07', '2025-12-02 14:18:35');
INSERT INTO `inventory` VALUES ('61', 'cornstarch', 'Bakery', '1764656263120', '0.12', '800.00', '800.00', 'g', '2025-10-08', '2025-12-02 14:19:37');
INSERT INTO `inventory` VALUES ('62', 'Italian seasoning ', 'Spices', '1764657398188', '0.50', '200.00', '500.00', 'g', '2025-05-07', '2025-12-02 14:42:24');
INSERT INTO `inventory` VALUES ('63', 'Del Monte Spaghetti ', 'Grains', '1764660152230', '0.09', '3000.00', '5000.00', 'g', '2025-04-01', '2025-12-02 15:23:14');
INSERT INTO `inventory` VALUES ('64', 'Garlic Powder', 'Spices', '1764664407501', '0.43', '450.00', '1000.00', 'g', '2025-05-05', '2025-12-02 16:36:21');
INSERT INTO `inventory` VALUES ('65', 'Cooking Oil', 'Condiments', '1764664860603', '158.00', '11.00', '25.00', 'L', '2025-04-09', '2025-12-02 16:44:57');
INSERT INTO `inventory` VALUES ('66', 'Singles American Cheese 24 Slices pack', 'Dairy', '1764665646145', '150.00', '35.00', '50.00', 'pcs', '2025-03-16', '2025-12-02 17:04:28');
INSERT INTO `inventory` VALUES ('67', 'Burger Buns', 'Grains', '1764680274748', '8.00', '95.00', '200.00', 'pcs', '2025-03-26', '2025-12-02 20:58:58');
INSERT INTO `inventory` VALUES ('68', 'Lettuce', 'Vegetables', '1764680544877', '0.35', '457.00', '1000.00', 'g', '2025-05-05', '2025-12-02 21:03:58');
INSERT INTO `inventory` VALUES ('71', 'Tomato ', 'Vegetables', '1764681125598', '15.00', '150.00', '200.00', 'pcs', '2025-07-27', '2025-12-02 21:14:55');
INSERT INTO `inventory` VALUES ('72', 'White Onion', 'Vegetables', '1764681567340', '0.12', '3000.00', '5000.00', 'g', '2025-08-03', '2025-12-02 21:22:14');
INSERT INTO `inventory` VALUES ('73', 'Pickles', 'Vegetables', '1764681942867', '2.78', '100.00', '100.00', 'pcs', '2025-02-07', '2025-12-02 21:32:49');
INSERT INTO `inventory` VALUES ('74', 'Mustard', 'Condiments', '1764682597807', '0.35', '800.00', '1000.00', 'g', '2025-03-26', '2025-12-02 21:37:05');
INSERT INTO `inventory` VALUES ('76', 'Mirin ', 'Condiments', '1764683251133', '0.32', '1000.00', '1000.00', 'ml', '2025-03-09', '2025-12-02 21:48:58');
INSERT INTO `inventory` VALUES ('77', 'Watermelon', 'Other', '1764683486486', '80.00', '10.00', '30.00', 'pcs', '2025-05-08', '2025-12-02 22:03:05');
INSERT INTO `inventory` VALUES ('78', 'Black pepper', 'Spices', '1764684384371', '1.31', '175.00', '500.00', 'g', '2025-07-31', '2025-12-02 22:07:12');
INSERT INTO `inventory` VALUES ('79', 'Watermelon Juice Powder', 'Other', '1764684364914', '0.30', '500.00', '1000.00', 'g', '2025-09-01', '2025-12-02 22:07:27');
INSERT INTO `inventory` VALUES ('80', 'Ice cubes', 'Beverages', '1764685191241', '0.02', '4500.00', '10000.00', 'g', '2025-04-01', '2025-12-02 22:32:08');
INSERT INTO `inventory` VALUES ('81', 'Chocolate Ice Cream', 'Frozen', '1764686068102', '0.35', '1000.00', '3000.00', 'g', '2025-06-17', '2025-12-02 22:42:33');
INSERT INTO `inventory` VALUES ('82', 'Fresh Milk', 'Dairy', '1764689973744', '0.11', '3000.00', '5000.00', 'ml', '2025-06-27', '2025-12-02 23:43:52');
INSERT INTO `inventory` VALUES ('83', 'Chocolate Syrup', 'Condiments', '1764690465366', '0.15', '3000.00', '5000.00', 'ml', '2025-03-28', '2025-12-02 23:49:13');
INSERT INTO `inventory` VALUES ('84', 'Ripe Mangoes', 'Other', '1764690595754', '165.00', '2.50', '5.00', 'Kg', '2025-07-17', '2025-12-02 23:51:37');
INSERT INTO `inventory` VALUES ('85', 'Mango Juice Powder', 'Juices', '1764690766428', '0.20', '1000.00', '2000.00', 'g', '2025-01-28', '2025-12-02 23:54:47');
INSERT INTO `inventory` VALUES ('86', 'Native Chicken', 'Meat', '1764691431967', '250.00', '10.00', '25.00', 'Kg', '2025-07-07', '2025-12-03 00:07:01');
INSERT INTO `inventory` VALUES ('87', 'Sayote', 'Vegetables', '1764692249216', '54.00', '5.00', '8.00', 'Kg', '2025-03-29', '2025-12-03 00:17:45');
INSERT INTO `inventory` VALUES ('88', 'Malunggay Leaves', 'Vegetables', '1764692358416', '0.08', '800.00', '1000.00', 'g', '2025-09-08', '2025-12-03 00:19:58');
INSERT INTO `inventory` VALUES ('89', 'Lemongrass', 'Spices', '1764693558681', '0.40', '500.00', '1000.00', 'g', '2025-07-17', '2025-12-03 00:39:57');
INSERT INTO `inventory` VALUES ('90', 'knorr Chicken Cube', 'Condiments', '1764693653159', '6.00', '100.00', '200.00', 'pcs', '2025-01-18', '2025-12-03 00:41:20');
INSERT INTO `inventory` VALUES ('91', 'All-Purpose Flour', 'Grains', '1764693820376', '0.05', '2000.00', '3000.00', 'g', '2025-07-16', '2025-12-03 00:44:15');
INSERT INTO `inventory` VALUES ('92', 'Chili Leaves bundle', 'Vegetables', '1764693834379', '30.00', '9.00', '15.00', 'pcs', '2025-10-20', '2025-12-03 00:44:58');
INSERT INTO `inventory` VALUES ('93', 'Eggs', 'Dairy', '1764693895052', '8.00', '150.00', '200.00', 'pcs', '2025-08-28', '2025-12-03 00:45:15');
INSERT INTO `inventory` VALUES ('94', 'French Fries', 'Frozen', '1764694022713', '0.12', '2000.00', '5000.00', 'g', '2025-04-04', '2025-12-03 00:47:42');
INSERT INTO `inventory` VALUES ('95', 'Pork Ears', 'Meat', '1764694113605', '300.00', '7.00', '15.00', 'Kg', '2025-05-05', '2025-12-03 00:49:03');
INSERT INTO `inventory` VALUES ('96', 'Chili Pepper', 'Spices', '1764694473737', '0.40', '300.00', '500.00', 'g', '2025-06-08', '2025-12-03 00:55:02');
INSERT INTO `inventory` VALUES ('97', 'Chicken Skin', 'Meat', '1764694667115', '150.00', '5.00', '10.00', 'Kg', '2025-03-07', '2025-12-03 00:58:19');
INSERT INTO `inventory` VALUES ('98', 'Beef Slices', 'Meat', '1764694849545', '450.00', '7.00', '15.00', 'Kg', '2025-05-17', '2025-12-03 01:01:16');
INSERT INTO `inventory` VALUES ('99', 'Mushrooms', 'Vegetables', '1764694949171', '0.40', '1000.00', '2000.00', 'g', '2025-06-18', '2025-12-03 01:03:08');
INSERT INTO `inventory` VALUES ('100', 'Kangkong Leaves', 'Vegetables', '', '0.30', '1000.00', '2000.00', 'g', '2025-03-31', '2025-12-03 01:04:52');
INSERT INTO `inventory` VALUES ('101', 'Honey', 'Condiments', '1764695180364', '370.00', '0.46', '1.00', 'Kg', '2025-06-11', '2025-12-03 01:08:21');
INSERT INTO `inventory` VALUES ('102', 'Mango Powder', 'Beverages', '1764695922783', '180.00', '2.00', '3.00', 'Kg', '2025-08-30', '2025-12-03 01:21:24');
INSERT INTO `inventory` VALUES ('103', 'Shrimp', 'Seafood', '1764696403651', '420.00', '1.00', '3.00', 'Kg', '2025-11-28', '2025-12-03 01:27:08');
INSERT INTO `inventory` VALUES ('104', 'Pork Tenderlion ', 'Meat', '1764696601120', '330.00', '3.00', '10.00', 'Kg', '2025-11-17', '2025-12-03 01:30:17');
INSERT INTO `inventory` VALUES ('105', 'Pork Belly', 'Meat', '1764696642441', '290.00', '3.00', '5.00', 'Kg', '2025-11-19', '2025-12-03 01:31:07');
INSERT INTO `inventory` VALUES ('106', 'Sitaw', 'Vegetables', '1764718391498', '5.00', '100.00', '200.00', 'pcs', '2025-03-09', '2025-12-03 07:33:51');
INSERT INTO `inventory` VALUES ('109', 'Radish', 'Vegetables', '1764718544155', '25.00', '100.00', '200.00', 'pcs', '2025-07-27', '2025-12-03 07:35:57');
INSERT INTO `inventory` VALUES ('111', 'Eggplant', 'Vegetables', '1764718645788', '15.00', '100.00', '200.00', 'pcs', '2025-02-02', '2025-12-03 07:37:35');
INSERT INTO `inventory` VALUES ('112', 'Okra', 'Vegetables', '1764718687206', '1.50', '100.00', '200.00', 'pcs', '2025-05-25', '2025-12-03 07:39:01');
INSERT INTO `inventory` VALUES ('113', 'Green Chili', 'Vegetables', '1764718830900', '3.00', '200.00', '200.00', 'pcs', '2025-01-17', '2025-12-03 07:41:04');
INSERT INTO `inventory` VALUES ('114', 'Sinigang mix', 'Condiments', '1764718955936', '15.00', '200.00', '200.00', 'pcs', '2025-04-05', '2025-12-03 07:47:32');

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
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `low_stock_alerts`
INSERT INTO `low_stock_alerts` VALUES ('228', '42', 'salt', '6.00', '60.00', 'Kg', '2025-12-02 13:38:57', 'sent', '1', '2025-12-02 13:39:00');
INSERT INTO `low_stock_alerts` VALUES ('230', '50', 'Parmesan cheese 454g', '1.00', '4.00', 'pcs', '2025-12-02 14:13:06', 'sent', '1', '2025-12-02 14:13:10');
INSERT INTO `low_stock_alerts` VALUES ('231', '54', 'chicken Breast ', '5.00', '20.00', 'Kg', '2025-12-02 14:13:06', 'sent', '1', '2025-12-02 14:13:10');
INSERT INTO `low_stock_alerts` VALUES ('232', '56', 'Celery', '200.00', '1000.00', 'g', '2025-12-02 14:28:56', 'sent', '1', '2025-12-02 14:29:01');
INSERT INTO `low_stock_alerts` VALUES ('233', '63', 'Del Monte Spaghetti ', '900.00', '5000.00', 'g', '2025-12-02 16:29:27', 'sent', '1', '2025-12-02 16:29:32');
INSERT INTO `low_stock_alerts` VALUES ('235', '63', 'Del Monte Spaghetti ', '1000.00', '5000.00', 'g', '2025-12-02 22:12:34', 'sent', '1', '2025-12-02 22:12:40');
INSERT INTO `low_stock_alerts` VALUES ('236', '77', 'Watermelon', '10.00', '40.00', 'pcs', '2025-12-02 22:16:35', 'sent', '1', '2025-12-02 22:16:40');
INSERT INTO `low_stock_alerts` VALUES ('237', '72', 'White Onion', '100.00', '500.00', 'pcs', '2025-12-02 23:02:35', 'sent', '1', '2025-12-02 23:02:38');
INSERT INTO `low_stock_alerts` VALUES ('238', '45', 'Ground Beef', '2.20', '10.00', 'Kg', '2025-12-02 23:38:20', 'sent', '1', '2025-12-02 23:38:25');
INSERT INTO `low_stock_alerts` VALUES ('239', '72', 'White Onion', '1000.00', '5000.00', 'g', '2025-12-03 00:30:41', 'sent', '1', '2025-12-03 00:30:44');
INSERT INTO `low_stock_alerts` VALUES ('240', '95', 'Pork Ears', '5.00', '20.00', 'Kg', '2025-12-03 00:49:03', 'sent', '1', '2025-12-03 00:49:08');

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
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `menu_ingredients`
INSERT INTO `menu_ingredients` VALUES ('126', '23', '63', '200.00');
INSERT INTO `menu_ingredients` VALUES ('127', '23', '43', '25.00');
INSERT INTO `menu_ingredients` VALUES ('128', '23', '60', '0.12');
INSERT INTO `menu_ingredients` VALUES ('129', '23', '47', '0.25');
INSERT INTO `menu_ingredients` VALUES ('130', '23', '46', '0.07');
INSERT INTO `menu_ingredients` VALUES ('131', '23', '42', '0.01');
INSERT INTO `menu_ingredients` VALUES ('132', '23', '78', '10.00');
INSERT INTO `menu_ingredients` VALUES ('133', '23', '39', '0.00');
INSERT INTO `menu_ingredients` VALUES ('134', '23', '50', '0.04');
INSERT INTO `menu_ingredients` VALUES ('135', '23', '65', '0.03');
INSERT INTO `menu_ingredients` VALUES ('136', '23', '45', '0.15');
INSERT INTO `menu_ingredients` VALUES ('137', '23', '41', '1.60');
INSERT INTO `menu_ingredients` VALUES ('138', '23', '48', '1.00');
INSERT INTO `menu_ingredients` VALUES ('139', '23', '62', '0.50');
INSERT INTO `menu_ingredients` VALUES ('140', '23', '78', '1.00');
INSERT INTO `menu_ingredients` VALUES ('141', '23', '56', '5.00');
INSERT INTO `menu_ingredients` VALUES ('142', '23', '72', '1.00');
INSERT INTO `menu_ingredients` VALUES ('143', '25', '101', '0.03');
INSERT INTO `menu_ingredients` VALUES ('144', '25', '80', '80.00');
INSERT INTO `menu_ingredients` VALUES ('145', '25', '82', '250.00');
INSERT INTO `menu_ingredients` VALUES ('146', '25', '77', '0.50');
INSERT INTO `menu_ingredients` VALUES ('147', '25', '79', '45.00');
INSERT INTO `menu_ingredients` VALUES ('148', '26', '84', '0.40');
INSERT INTO `menu_ingredients` VALUES ('149', '26', '101', '0.03');
INSERT INTO `menu_ingredients` VALUES ('150', '26', '82', '250.00');
INSERT INTO `menu_ingredients` VALUES ('151', '26', '85', '45.00');
INSERT INTO `menu_ingredients` VALUES ('152', '26', '80', '80.00');
INSERT INTO `menu_ingredients` VALUES ('218', '24', '45', '0.20');
INSERT INTO `menu_ingredients` VALUES ('219', '24', '66', '1.00');
INSERT INTO `menu_ingredients` VALUES ('220', '24', '38', '0.14');
INSERT INTO `menu_ingredients` VALUES ('221', '24', '67', '1.00');
INSERT INTO `menu_ingredients` VALUES ('222', '24', '42', '0.01');
INSERT INTO `menu_ingredients` VALUES ('223', '24', '78', '5.00');
INSERT INTO `menu_ingredients` VALUES ('224', '24', '72', '0.08');
INSERT INTO `menu_ingredients` VALUES ('225', '24', '53', '0.10');
INSERT INTO `menu_ingredients` VALUES ('226', '24', '52', '0.50');
INSERT INTO `menu_ingredients` VALUES ('227', '24', '64', '1.00');
INSERT INTO `menu_ingredients` VALUES ('228', '24', '68', '0.25');
INSERT INTO `menu_ingredients` VALUES ('229', '24', '73', '2.00');
INSERT INTO `menu_ingredients` VALUES ('230', '27', '105', '0.25');
INSERT INTO `menu_ingredients` VALUES ('231', '27', '103', '0.15');
INSERT INTO `menu_ingredients` VALUES ('232', '27', '72', '200.00');
INSERT INTO `menu_ingredients` VALUES ('233', '27', '71', '0.50');
INSERT INTO `menu_ingredients` VALUES ('234', '27', '100', '200.00');
INSERT INTO `menu_ingredients` VALUES ('235', '27', '87', '0.40');
INSERT INTO `menu_ingredients` VALUES ('236', '27', '42', '0.06');
INSERT INTO `menu_ingredients` VALUES ('237', '27', '92', '1.00');
INSERT INTO `menu_ingredients` VALUES ('238', '27', '96', '10.00');
INSERT INTO `menu_ingredients` VALUES ('239', '27', '65', '0.04');
INSERT INTO `menu_ingredients` VALUES ('240', '27', '78', '5.00');
INSERT INTO `menu_ingredients` VALUES ('241', '27', '111', '0.50');
INSERT INTO `menu_ingredients` VALUES ('242', '27', '112', '5.00');
INSERT INTO `menu_ingredients` VALUES ('243', '27', '114', '0.25');
INSERT INTO `menu_ingredients` VALUES ('244', '27', '109', '0.50');
INSERT INTO `menu_ingredients` VALUES ('245', '27', '113', '10.00');
INSERT INTO `menu_ingredients` VALUES ('246', '27', '106', '5.00');

-- Table structure for table `menus`
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `barcode` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `menus`
INSERT INTO `menus` VALUES ('23', 'Pasta Bolognese', '285.00', '1764685688710', '2025-12-02 22:56:06');
INSERT INTO `menus` VALUES ('24', 'Smashed & Chessy Burger', '320.00', '1764687449765', '2025-12-02 23:10:59');
INSERT INTO `menus` VALUES ('25', 'Watermelon Shake', '175.00', '1764695458875', '2025-12-03 01:13:44');
INSERT INTO `menus` VALUES ('26', 'Mango Shake', '175.00', '1764696140700', '2025-12-03 01:24:52');
INSERT INTO `menus` VALUES ('27', 'Pork & Shrimp Sinigang', '420.00', '1764696735827', '2025-12-03 01:40:28');

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `physical_count_entries`

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
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `production`
INSERT INTO `production` VALUES ('116', '24', '10', '0', '8', '1764687449765', '2', '2025-12-02 23:38:20');

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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `purchase_order_items`

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `purchase_orders`

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table `user`
INSERT INTO `user` VALUES ('10', 'admin', 'Admin', 'Account', 'sbenerable.student@asiancollege.edu.ph', '$2y$10$5hH3u9wG9HQN2n9UJl5fturO7r8C6J9dD5C17VpAopT1cFjTF6/Su', 'Admin', '2025-07-22 21:38:42', '1');
INSERT INTO `user` VALUES ('11', 'owner', 'Owner', 'Account', 'nbarbas.student@asiancollege.edu.ph', '$2y$10$ZBaiXvbUAvSBCzG0opDIHO5RVzGZtUi/ex7UqKVJYfrHOTCPrnwzW', 'Owner', '2025-08-02 11:48:48', '0');
INSERT INTO `user` VALUES ('15', 'manager', 'Manager', 'Account', 'jtdeliga.student@asiancollege.edu.ph', '$2y$10$wgrOjI1HIN05Is.HjdkGRezkdRm4.rtrMPOaSbfd06Mm100FmaHQO', 'Manager', '2025-10-01 00:00:00', '0');
INSERT INTO `user` VALUES ('19', 'inventory', 'Inventory', 'Account', 'ssamosco.student@asiancollege.edu.ph', '$2y$10$N/7bXVoK9mk7PT3Zehuy3e28t1APuZf7dpQsIEv8rPCXBL.68EP2W', 'Inventory Staff', '2025-10-22 00:00:00', '0');
INSERT INTO `user` VALUES ('22', 'cashier', 'cash', 'ier', 'nbarbas.student@asiancollege.edu.ph', '$2y$10$oFZuC1vAONmmRMiQuXhBa.ZhGU.JnJeGfD1poTJOsEmPTOMZ/D1tq', 'Cashier', '2025-10-28 00:00:00', '0');
INSERT INTO `user` VALUES ('24', 'kitchen', 'kit', 'chen', 'nbarbas.student@asiancollege.edu.ph', '$2y$10$sNoRd7.CwLdVap.PBP0AR.G1.eBPMIXIjR4VPlP7k/VL4yOCLUMia', 'Kitchen Staff', '2025-10-28 00:00:00', '0');

