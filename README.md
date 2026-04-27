# LUNA Inventory Management System

A comprehensive inventory and production management system built with PHP using a Model-View-Controller (MVC) architecture. The system provides robust features for managing inventory, production, purchase orders, and low stock alerts with automated email notifications. The front-end is styled with Bootstrap 5 and uses jQuery and DataTables for dynamic table interactions.


## System Overview

<img width="1919" height="969" alt="image" src="https://github.com/user-attachments/assets/a0e36d43-ec1c-45ed-a386-762a9786a6bc" />
<img width="1919" height="970" alt="image" src="https://github.com/user-attachments/assets/a664f162-72a4-4a0a-9c84-e058066fb876" />
<img width="1919" height="907" alt="image" src="https://github.com/user-attachments/assets/9b2b09c8-fc15-4ddf-8bfc-92fa6e7d99af" />
<img width="1919" height="961" alt="image" src="https://github.com/user-attachments/assets/ff8367da-e67f-4a25-a21e-dec1e4a913cb" />
<img width="1919" height="977" alt="image" src="https://github.com/user-attachments/assets/35176ff6-f37c-4f56-a7c2-c8eccce10289" />
<img width="1919" height="967" alt="image" src="https://github.com/user-attachments/assets/45b86adf-6c35-4a9b-8c62-17c10c57de08" />
<img width="1919" height="968" alt="image" src="https://github.com/user-attachments/assets/6bd4abfa-d921-4c05-a0e4-351f166df2ba" />
<img width="1919" height="965" alt="image" src="https://github.com/user-attachments/assets/09b0864b-9ffb-4e48-ba14-a5706a273ffb" />
<img width="414" height="896" alt="IMG_1608" src="https://github.com/user-attachments/assets/18348b0c-e33a-48cd-84a1-c7734f12fe0d" />
<img width="377" height="795" alt="image" src="https://github.com/user-attachments/assets/36a3f446-c02f-46db-b415-6f6c0396d79d" />




## Features

### 🔐 Core Functionality
- **Secure User Authentication**: Username-based login and logout functionality with password hashing and session management
- **User Management (CRUD)**: Add, view, edit, and delete users with role-based permissions
- **Dashboard Overview**: Home page with quick access to all system modules

### 📦 Inventory Management
- **Complete CRUD Operations**: Create, read, update, and delete inventory items
- **Detailed Item Tracking**: Track item name, barcode, quantity, unit, price, purchase date, maximum quantity, and category
- **Real-time Inventory Updates**: Automatic inventory adjustments based on production and purchase orders
- **Data Visualization**: Dynamic tables with sorting, searching, and pagination using DataTables

### 📋 Physical Count System
- **Physical Count Tracking**: Dedicated system for inventory physical counting operations
- **Variance Analysis**: Automatic calculation of differences between system count and physical count
- **Percentage Variance**: Calculate variance percentages for accuracy assessment
- **Value Impact Analysis**: Calculate financial impact of inventory discrepancies
- **Pending Entries Management**: Track and manage pending physical count entries before applying to inventory
- **Batch Processing**: Save multiple physical count entries to inventory in batches

### 📲 Barcode System
- **Comprehensive Barcode Support**: Full barcode scanning functionality across all modules
- **Barcode Physical Count**: Use barcode scanners for physical inventory counting
- **Barcode Production Management**: Scan barcodes for production operations
- **Barcode Menu Actions**: Barcode integration with menu item operations
- **Production Updates via Barcode**: Update sold quantities and wastage using barcode scanning
- **Multi-Entity Barcode Support**: Barcodes for inventory items, menu items, and production batches
- **Flashlight Toggle**: Built-in flashlight button for improved barcode scanning in low-light conditions
- **Barcode Selection Interface**: Choose between inventory, production, or menu actions after scanning a barcode

### 🏭 Production Management
- **Menu-Based Production Tracking**: Manage production based on menu items with ingredient requirements
- **Advanced Batch Processing**: Track quantity produced, available, sold, and wastage with detailed analytics
- **Automatic Ingredient Deduction**: FIFO-based ingredient deduction from inventory when production is recorded
- **Cost and Profit Analysis**: Calculate unit costs, total costs, sales revenue, waste costs, and profit margins
- **Sales and Wastage Tracking**: FIFO-based updates for sold quantities and wastage across production batches
- **Production Capacity Planning**: Real-time calculation of maximum production based on available ingredients
- **Barcode Integration**: Full barcode support for production tracking and updates

### 🛒 Purchase Order Management
- **Multi-Item Purchase Orders**: Create purchase orders with multiple inventory items
- **Order Status Tracking**: Track purchase orders with statuses (Pending, Received, Cancelled)
- **Delivery Date Management**: Record expected delivery dates for purchase orders
- **Automatic Inventory Updates**: Automatically update inventory when purchase orders are marked as received
- **Price and Quantity Tracking**: Maintain records of unit prices and quantities for purchasing history

### 🚨 Advanced Low Stock Alert System
- **Intelligent Detection**: Automatic identification of low stock items (below 20% of maximum quantity)
- **Rich Email Notifications**: Professional HTML email alerts to administrators with modern responsive design
- **Smart Alert Management**: Prevents duplicate alerts with transaction-level locking
- **Auto-Resolution System**: Automatically resolves alerts when inventory levels are restored
- **Alert History Tracking**: Complete history of all low stock alerts with detailed resolution status
- **Manual Alert Processing**: On-demand checking and sending of low stock alerts
- **SMTP Integration**: Email notifications via SMTP with support for major email providers

### 🍽️ Menu Management
- **Menu Item Creation**: Define menu items with names, barcodes, and pricing
- **Advanced Ingredient Mapping**: Associate inventory items as ingredients with precise required quantities
- **Recipe Cost Analysis**: Automatic calculation of menu item costs based on ingredient prices
- **Recipe Management**: Maintain detailed ingredient recipes for consistent production planning
- **Barcode Support**: Full barcode integration for menu items

### ⚙️ System Administration
- **Database Backup & Restore**: Export database to SQL files and import from backups
- **Email Configuration**: SMTP integration with support for Gmail, Outlook, Yahoo, and custom SMTP servers
- **Email Testing Tools**: Built-in email testing and configuration debugging
- **Responsive UI**: Mobile-friendly interface built with Bootstrap 5
- **MVC Architecture**: Clean separation of business logic, data, and presentation layers

## Requirements

- XAMPP latest
- MySQL / Workbench

## Installation and Setup

1. **Clone the repository:**
   - Download this repository
   - Extract the contents to `htdocs` directory in your XAMPP installation

2. **Create the Database:**
   - Create a new database in MySQL named `luna`
   - Import the provided SQL backup file (`luna_backup_2025-09-17_19-33-56.sql`) to create tables and initial data
   - Or create the database schema manually if no backup file is available

3. **Configure Database Connection:**
   - Copy `.env sample` to `.env` (remove the space in the filename)
   - The default configuration should work with standard XAMPP setup:
     ```
     DB_HOST=localhost
     DB_NAME=luna
     DB_USERNAME=root
     DB_PASSWORD=rootroot
     DB_PORT=3306
     ```
   - Update the database credentials if your MySQL setup differs from the defaults

4. **Configure Email Settings (Optional):**
   - Update email configuration in `.env` for low stock alert notifications via SMTP:
     ```
     # SMTP Configuration
     SMTP_HOST=smtp.gmail.com
     SMTP_PORT=587
     SMTP_USERNAME=your-email@gmail.com
     SMTP_PASSWORD=your-app-password
     SMTP_SECURITY=tls
     FROM_EMAIL=noreply@yourdomain.com
     FROM_NAME=LUNA Inventory System
     ```
   - **Supported Email Providers:**
     - **Gmail**: Use app passwords for authentication
     - **Outlook/Hotmail**: Use regular credentials with TLS
     - **Yahoo Mail**: Use app passwords for authentication
     - **Custom SMTP**: Configure your own SMTP server
   - Email configuration is optional - the system will work without it, but low stock alerts won't be sent

5. **Run the Application:**
   - Start Apache and MySQL services in XAMPP Control Panel
   - Open your web browser and navigate to `http://localhost/your-folder-name`
   - You should see the login page
   - Use the default login credentials below to access the system

6. **Post-Installation Setup:**
   - After first login, consider changing the default admin password
   - Configure email settings if you want to receive low stock alerts
   - Test email functionality using the built-in email testing tools (Users → Test Email)

## Default Login Credentials

- **Username:** `admin`
- **Password:** `admin`
