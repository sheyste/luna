# 🌙 LUNA User Manual

Welcome to the **LUNA Inventory Management System** user manual. This guide will walk you through the setup process and provide detailed instructions on how to use each feature of the system.

---

## 📋 Table of Contents
1. [System Overview](#-system-overview)
2. [Setup & Installation](#-setup--installation)
3. [Getting Started](#-getting-started)
4. [User Roles & Permissions](#-user-roles--permissions)
5. [Module Guides](#-module-guides)
   - [Inventory Management](#inventory-management)
   - [Physical Count System](#physical-count-system)
   - [Menu Management](#menu-management)
   - [Production Tracking](#production-tracking)
   - [Purchase Orders](#purchase-orders)
   - [Barcode Scanning](#barcode-scanning)
   - [Low Stock Alerts](#low-stock-alerts)
6. [Administration](#-administration)
7. [Troubleshooting](#-troubleshooting)

---

## 🌐 System Overview

LUNA is a comprehensive inventory and production management solution designed for businesses that need to track raw ingredients, manage production batches, and monitor sales and wastage. It features a robust barcode system, real-time stock alerts, and multi-user role management.

---

## 🛠 Setup & Installation

Follow these steps to get the system running on your local machine:

### 1. Prerequisites
- **XAMPP**: Download and install the latest version of [XAMPP](https://www.apachefriends.org/).
- **Web Browser**: Chrome, Firefox, or Edge is recommended.

### 2. Files Placement
- Extract the LUNA project folder into the `htdocs` directory of your XAMPP installation (usually `C:\xampp\htdocs`).

### 3. Database Setup
1. Open the **XAMPP Control Panel** and start **Apache** and **MySQL**.
2. Go to `http://localhost/phpmyadmin` in your browser.
3. Create a new database named `luna`.
4. Click on the `luna` database, go to the **Import** tab, and select the `LUNA Database Blank.sql` or the latest backup file (e.g., `luna_backup_...sql`) from the project root.
5. Click **Go** to import the tables.

### 4. Configuration
1. In the project root, find the file `.env sample`.
2. Rename it to `.env`.
3. Open `.env` in a text editor and update the database settings if necessary:
   ```env
   DB_HOST=localhost
   DB_NAME=luna
   DB_USERNAME=root
   DB_PASSWORD=          # Leave blank if no password is set in XAMPP
   DB_PORT=3306
   ```

---

## 🚀 Getting Started

1. Open your browser and navigate to `http://localhost/your-folder-name`.
2. You will be greeted by the **Login Page**.
3. Use the default credentials for the first login:
   - **Username**: `admin`
   - **Password**: `admin`
4. **Important**: Change your password immediately after logging in for security.

---

## 👥 User Roles & Permissions

The system uses Role-Based Access Control (RBAC). Here is a quick summary:

| Role | Description | Key Permissions |
| :--- | :--- | :--- |
| **Admin** | Full system access | Everything, including backups and user management. |
| **Owner** | High-level oversight | View all charts, manage menu items/recipes. |
| **Manager** | Operational lead | Manage inventory, production, and purchase orders. |
| **Inventory Staff** | Stock management | Inventory CRUD, physical counts, purchase orders. |
| **Cashier** | Sales processing | Update sales and wastage, add production. |
| **Kitchen Staff** | Production worker | Add production batches, view menu. |

---

## 📦 Module Guides

### Inventory Management
- **Viewing Stocks**: The primary table shows current stock levels, prices, and barcodes.
- **Adding Items**: Click "Add Item" to register new stock. Ensure you set a `Maximum Quantity` for low-stock alerts to work.
- **Low Stock Indicator**: Items below 20% of their maximum quantity will be highlighted in **red**.

### Physical Count System
- **Performing a Count**: Navigate to "Physical Count". You can scan barcodes or enter items manually.
- **Variance Analysis**: The system automatically compares your physical count with the system record.
- **Applying Changes**: Once verified, you can "Save to Inventory" to update the system quantities to match the physical count.

### Menu Management
- **Creating Recipes**: Define your menu items (e.g., "Classic Burger").
- **Ingredient Mapping**: Assign inventory items as ingredients. Specify the exact quantity required for one unit of the menu item.
- **Cost Analysis**: The system calculates the total production cost based on ingredient prices.

### Production Tracking
- **Recording Production**: When you cook or manufacture items, record them here. The system will automatically deduct ingredients from the inventory.
- **Sales & Wastage**: Update how many items were sold and how many were wasted. This helps in calculating profit and loss.
- **Batch Management**: Every production run is tracked as a "Batch" for FIFO accuracy.

### Purchase Orders
- **Ordering**: Create POs for suppliers. You can add multiple items to a single order.
- **Receiving**: When items arrive, mark the PO as "Received". Stock levels will automatically increase.

### Barcode Scanning
- **USB Scanners**: Plug in your scanner. The system is designed to work with standard USB HID scanners.
- **Mobile/Webcam**: In supported modules (like Production or Physical Count), you can use your device's camera as a scanner.
- **Quick Actions**: Scanning a barcode can trigger specific actions like adding an item to a list or updating sales.

### Low Stock Alerts
- **Email Notifications**: If configured in `.env`, the system sends automated emails when stock is low.
- **Alert History**: View all past alerts and their resolution status in the "Low Stock Alerts" module.

---

## ⚙️ Administration

### Backup & Restore
- **Backup**: Go to "Backup" and click "Download Backup" to save your database state.
- **Restore**: Upload a previous `.sql` backup file to revert to a specific state. **Warning**: This replaces all current data.

### User Management
- Create accounts for your staff and assign them the appropriate roles.
- You can reset passwords or deactivate accounts if needed.

---

## 🔍 Troubleshooting

- **Login Failed**: Ensure XAMPP is running and the `.env` configuration matches your database credentials.
- **Emails Not Sending**: Check your SMTP settings in `.env`. If using Gmail, you may need an "App Password".
- **Barcode Not Recognized**: Ensure the barcode is correctly registered in the Inventory or Menu module.
- **Database Error**: Check the `logs` (if available) or ensure the MySQL service is active in XAMPP.

---
*LUNA - Empowering your inventory management with precision and ease.*
