# LUNA Inventory System

A comprehensive inventory and production management system built with PHP using a Model-View-Controller (MVC) architecture. The system provides robust features for managing inventory, production, purchase orders, and low stock alerts with automated email notifications. The front-end is styled with Bootstrap 5 and uses jQuery and DataTables for dynamic table interactions.

## Features

### Core Functionality
- **Secure User Authentication**: Login and logout functionality with password hashing and session management
- **User Management (CRUD)**: Add, view, edit, and delete users with role-based permissions
- **Dashboard Overview**: Home page with quick access to all system modules

### Inventory Management
- **Complete CRUD Operations**: Create, read, update, and delete inventory items
- **Detailed Item Tracking**: Track item name, quantity, unit, price, purchase date, and maximum quantity
- **Real-time Inventory Updates**: Automatic inventory adjustments based on production and purchase orders
- **Data Visualization**: Dynamic tables with sorting, searching, and pagination using DataTables

### Production Management
- **Menu-Based Production Tracking**: Manage production based on menu items with ingredient requirements
- **Batch Production Records**: Track quantity produced, available, sold, and wastage
- **Automatic Ingredient Deduction**: Automatically deduct ingredients from inventory when production is recorded
- **Sales and Wastage Tracking**: Update sold quantities and wastage for accurate inventory management
- **Barcode Support**: Optional barcode tracking for production items

### Purchase Order Management
- **Multi-Item Purchase Orders**: Create purchase orders with multiple inventory items
- **Order Status Tracking**: Track purchase orders with statuses (Pending, Received, Cancelled)
- **Delivery Date Management**: Record expected delivery dates for purchase orders
- **Automatic Inventory Updates**: Automatically update inventory when purchase orders are marked as received
- **Price and Quantity Tracking**: Maintain records of unit prices and quantities for purchasing history

### Low Stock Alert System
- **Automated Detection**: Automatic identification of low stock items (below 20% of maximum quantity)
- **Email Notifications**: Automated email alerts to administrators when low stock items are detected
- **Alert Resolution Tracking**: Automatic resolution of alerts when inventory levels are restored
- **Alert History**: Complete history of all low stock alerts with resolution status
- **Manual Alert Checking**: On-demand checking for low stock items

### Menu Management
- **Menu Item Creation**: Define menu items with names for production tracking
- **Ingredient Mapping**: Associate inventory items as ingredients for menu items with required quantities
- **Recipe Management**: Maintain ingredient recipes for consistent production planning

### System Administration
- **Database Backup & Restore**: Export database to SQL files and import from backups
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
   - Create a new database in MySQL (e.g., `luna_inventory`)
   - Import the provided SQL schema file (`luna.sql`) to create tables and initial data

3. **Configure Database Connection:**
   - Copy `.env.sample` to `.env`
   - Update the database connection details in the `.env` file to match your environment:
     ```
     DB_HOST=localhost
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     DB_NAME=luna_inventory
     DB_PORT=3306
     ```

4. **Configure Email Settings (Optional):**
   - Update email configuration in `.env` for low stock alert notifications:
     ```
     EMAIL_HOST=smtp.gmail.com
     EMAIL_PORT=587
     EMAIL_USERNAME=your_email@gmail.com
     EMAIL_PASSWORD=your_app_password
     EMAIL_ENCRYPTION=tls
     ```

5. **Run the Application:**
   - Start Apache and MySQL services in XAMPP
   - Place the project folder inside your web server's root directory (e.g., `htdocs` for XAMPP)
   - Open your web browser and navigate to `http://localhost/your-folder-name`
   - You should see the login page

## Default Login Credentials

- **Email:** `admin@admin.com`
- **Password:** `admin`

## System Modules

### 1. Dashboard
- Provides an overview of the system
- Quick navigation to all modules

### 2. Inventory Management
- View all inventory items in a searchable table
- Add new inventory items
- Edit existing inventory details
- Delete inventory items
- Real-time low stock indicators

### 3. Production Management
- Track production of menu items
- Record quantities produced, sold, and wasted
- View production history
- Automatic ingredient deduction from inventory

### 4. Purchase Orders
- Create and manage purchase orders
- Track order status (Pending, Received, Cancelled)
- Record expected delivery dates
- Multi-item purchase orders

### 5. Low Stock Alerts
- View all low stock alerts
- Track alert resolution status
- Manual trigger for low stock checks
- Automated email notifications

### 6. Menu Management
- Manage menu items
- Define ingredient requirements for menu items
- View menu item details

### 7. User Management
- Add new users
- Edit user details
- Delete users
- Role-based access control

### 8. Backup & Restore
- Download database backups
- Upload and restore from backup files
- Complete data protection solution


Thank you!

22088674(33043)