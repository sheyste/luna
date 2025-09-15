# LUNA Inventory System

A comprehensive inventory and production management system built with PHP using a Model-View-Controller (MVC) architecture. The system provides robust features for managing inventory, production, purchase orders, and low stock alerts with automated email notifications. The front-end is styled with Bootstrap 5 and uses jQuery and DataTables for dynamic table interactions.

## Features

### 🔐 Core Functionality
- **🔒 Secure User Authentication**: Username-based login and logout functionality with password hashing and session management
- **👥 User Management (CRUD)**: Add, view, edit, and delete users with role-based permissions
- **📊 Dashboard Overview**: Home page with quick access to all system modules

### 📦 Inventory Management
- **✨ Complete CRUD Operations**: Create, read, update, and delete inventory items
- **🏷️ Detailed Item Tracking**: Track item name, barcode, quantity, unit, price, purchase date, maximum quantity, and category
- **⚡ Real-time Inventory Updates**: Automatic inventory adjustments based on production and purchase orders
- **📈 Data Visualization**: Dynamic tables with sorting, searching, and pagination using DataTables

### 📋 Physical Count System
- **📱 Physical Count Tracking**: Dedicated system for inventory physical counting operations
- **🔍 Variance Analysis**: Automatic calculation of differences between system count and physical count
- **📊 Percentage Variance**: Calculate variance percentages for accuracy assessment
- **💰 Value Impact Analysis**: Calculate financial impact of inventory discrepancies
- **⏳ Pending Entries Management**: Track and manage pending physical count entries before applying to inventory
- **📦 Batch Processing**: Save multiple physical count entries to inventory in batches

### 📲 Barcode System
- **🎯 Comprehensive Barcode Support**: Full barcode scanning functionality across all modules
- **📋 Barcode Physical Count**: Use barcode scanners for physical inventory counting
- **🏭 Barcode Production Management**: Scan barcodes for production operations
- **🍽️ Barcode Menu Actions**: Barcode integration with menu item operations
- **📊 Production Updates via Barcode**: Update sold quantities and wastage using barcode scanning
- **🔗 Multi-Entity Barcode Support**: Barcodes for inventory items, menu items, and production batches

### 🏭 Production Management
- **🍽️ Menu-Based Production Tracking**: Manage production based on menu items with ingredient requirements
- **📊 Advanced Batch Processing**: Track quantity produced, available, sold, and wastage with detailed analytics
- **⚙️ Automatic Ingredient Deduction**: FIFO-based ingredient deduction from inventory when production is recorded
- **💹 Cost and Profit Analysis**: Calculate unit costs, total costs, sales revenue, waste costs, and profit margins
- **📈 Sales and Wastage Tracking**: FIFO-based updates for sold quantities and wastage across production batches
- **🎯 Production Capacity Planning**: Real-time calculation of maximum production based on available ingredients
- **📲 Barcode Integration**: Full barcode support for production tracking and updates

### 🛒 Purchase Order Management
- **📝 Multi-Item Purchase Orders**: Create purchase orders with multiple inventory items
- **📊 Order Status Tracking**: Track purchase orders with statuses (Pending, Received, Cancelled)
- **📅 Delivery Date Management**: Record expected delivery dates for purchase orders
- **🔄 Automatic Inventory Updates**: Automatically update inventory when purchase orders are marked as received
- **💵 Price and Quantity Tracking**: Maintain records of unit prices and quantities for purchasing history

### 🚨 Advanced Low Stock Alert System
- **🔍 Intelligent Detection**: Automatic identification of low stock items (below 20% of maximum quantity)
- **📧 Rich Email Notifications**: Professional HTML email alerts to administrators with modern responsive design
- **🛡️ Smart Alert Management**: Prevents duplicate alerts with transaction-level locking
- **✅ Auto-Resolution System**: Automatically resolves alerts when inventory levels are restored
- **📜 Alert History Tracking**: Complete history of all low stock alerts with detailed resolution status
- **⚡ Manual Alert Processing**: On-demand checking and sending of low stock alerts
- **📨 SMTP Integration**: Email notifications via SMTP with support for major email providers

### 🍽️ Menu Management
- **➕ Menu Item Creation**: Define menu items with names, barcodes, and pricing
- **🔗 Advanced Ingredient Mapping**: Associate inventory items as ingredients with precise required quantities
- **💰 Recipe Cost Analysis**: Automatic calculation of menu item costs based on ingredient prices
- **📋 Recipe Management**: Maintain detailed ingredient recipes for consistent production planning
- **📲 Barcode Support**: Full barcode integration for menu items

### ⚙️ System Administration
- **💾 Database Backup & Restore**: Export database to SQL files and import from backups
- **📧 Email Configuration**: SMTP integration with support for Gmail, Outlook, Yahoo, and custom SMTP servers
- **🧪 Email Testing Tools**: Built-in email testing and configuration debugging
- **📱 Responsive UI**: Mobile-friendly interface built with Bootstrap 5
- **🏗️ MVC Architecture**: Clean separation of business logic, data, and presentation layers

## Requirements

- XAMPP latest
- MySQL / Workbench

## Installation and Setup

1. **Clone the repository:**
   - Download this repository
   - Extract the contents to `htdocs` directory in your XAMPP installation

2. **Create the Database:**
   - Create a new database in MySQL named `luna`
   - Import the provided SQL backup file (`luna_backup_2025-08-31_14-44-52.sql`) to create tables and initial data
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

## System Modules

### 1. Dashboard
- Provides an overview of the system
- Quick navigation to all modules

### 2. Inventory Management
- View all inventory items in a searchable table with barcode support
- Add new inventory items with category and barcode fields
- Edit existing inventory details
- Delete inventory items
- Real-time low stock indicators

### 3. Physical Count System
- Barcode-enabled physical inventory counting
- Variance analysis with percentage calculations
- Value impact assessment for discrepancies
- Pending entries management before inventory updates
- Batch processing of physical count results

### 4. Production Management
- Menu-based production tracking with barcode integration
- Advanced batch processing with cost and profit analysis
- FIFO-based ingredient deduction and sales tracking
- Production capacity planning based on available ingredients
- Real-time updates for sold quantities and wastage

### 5. Purchase Orders
- Create and manage purchase orders
- Track order status (Pending, Received, Cancelled)
- Record expected delivery dates
- Multi-item purchase orders with automatic inventory updates

### 6. Advanced Low Stock Alerts
- Intelligent alert detection with duplicate prevention
- Professional HTML email notifications via SMTP
- Auto-resolution system when stock is replenished
- Alert history tracking with detailed status information
- Manual alert processing and email testing tools

### 7. Menu Management
- Comprehensive menu item management with barcode support
- Advanced ingredient mapping with cost analysis
- Recipe management for consistent production planning
- Automatic cost calculation based on ingredient prices

### 8. Barcode System
- Universal barcode scanning across all modules
- Barcode-enabled physical counting operations
- Production management via barcode scanning
- Menu item operations with barcode integration
- Support for inventory, menu, and production item barcodes

### 9. User Management
- Username-based authentication system
- Add, edit, and delete users with role-based permissions
- Secure password hashing and session management

### 10. Backup & Restore
- Download database backups
- Upload and restore from backup files
- Complete data protection solution


Thank you!

22088674(33043)