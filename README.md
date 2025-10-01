# LUNA Inventory System

A comprehensive inventory and production management system built with PHP using a Model-View-Controller (MVC) architecture. The system provides robust features for managing inventory, production, purchase orders, and low stock alerts with automated email notifications. The front-end is styled with Bootstrap 5 and uses jQuery and DataTables for dynamic table interactions.

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

## User Roles and Permissions

The system supports three user types with different levels of access and permissions:

### 👑 Admin
**Full System Access**
- **User Management**: Create, edit, delete, and manage all user accounts
- **Inventory Management**: Full CRUD operations including item deletion
- **Production Management**: Full control including production deletion
- **Menu Management**: Create, edit, and delete menu items
- **Purchase Orders**: Complete management of purchase orders
- **Physical Count**: Full access to inventory counting operations
- **Low Stock Alerts**: View alerts, configure email settings, and test SMTP
- **Backup & Restore**: Database backup and restore operations
- **Barcode System**: Full access to all barcode operations
- **Email Testing**: Send test emails and debug SMTP configuration

### 👨‍💼 Manager
**Operational Management Access**
- **Inventory Management**: View, add, and edit inventory items (cannot delete)
- **Production Management**: View and manage production batches (cannot delete production records)
- **Menu Management**: View menu items (cannot edit or delete)
- **Purchase Orders**: Full management of purchase orders
- **Physical Count**: Full access to inventory counting operations
- **Low Stock Alerts**: View low stock alerts (cannot access alert management)
- **Barcode System**: Full access to barcode operations

### 👤 User
**Basic Operational Access**
- **Inventory Management**: View inventory items only (read-only)
- **Production Management**: View production records (cannot add, edit, or delete)
- **Menu Management**: View menu items only (read-only)
- **Purchase Orders**: View purchase orders only (read-only)
- **Physical Count**: Limited access to physical counting
- **Barcode System**: Limited barcode scanning for assigned tasks
- **No Access**: User management, backup/restore, low stock alerts, email testing

## System Modules

### 1. Dashboard
- Provides an overview of the system
- Quick navigation to all modules

### 2. Inventory Management
- View all inventory items in a searchable table with barcode support
- Add new inventory items with category and barcode fields
- Edit existing inventory details
- Delete inventory items (Admin only)
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
- Production deletion (Admin only)

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
- Manual alert processing and email testing tools (Admin only)

### 7. Menu Management
- Comprehensive menu item management with barcode support
- Advanced ingredient mapping with cost analysis
- Recipe management for consistent production planning
- Automatic cost calculation based on ingredient prices
- Menu editing and deletion (Admin only)

### 8. Barcode System
- Universal barcode scanning across all modules
- Barcode-enabled physical counting operations
- Production management via barcode scanning
- Menu item operations with barcode integration
- Support for inventory, menu, and production item barcodes

### 9. User Management (Admin Only)
- Username-based authentication system
- Add, edit, and delete users with role-based permissions
- Secure password hashing and session management
- Email testing and SMTP configuration debugging (Admin only)

### 10. Backup & Restore (Admin Only)
- Download database backups
- Upload and restore from backup files
- Complete data protection solution


## System Context Diagram

The following diagram illustrates the high-level context of the LUNA Inventory System, showing the main actors and external systems it interacts with:

```
+----------------+     +-----------------+     +----------------+
|     Admin      |     |    Manager      |     |      User      |
+----------------+     +-----------------+     +----------------+
         |                     |                     |
         |                     |                     |
         +---------------------+---------------------+
                               |
                               v
                    +---------------------+
                    |   LUNA System       |
                    | (Web Application)   |
                    +---------------------+
                               |
                               |
         +---------------------+---------------------+
         |                     |                     |
         v                     v                     v
+----------------+     +-----------------+     +-----------------+
| MySQL Database |     |  SMTP Server    |     | Barcode Scanner |
|   (Data)       |     |   (Email)       |     |   (Hardware)    |
+----------------+     +-----------------+     +-----------------+
```

### Data Flow Description:
- **Users (Admin/Manager/User)**: Interact with the web interface to perform inventory operations
- **LUNA System**: Core application handling business logic, user authentication, and data processing
- **MySQL Database**: Stores all system data including inventory, users, production records, etc.
- **SMTP Server**: Handles email notifications for low stock alerts and system communications
- **Barcode Scanner**: External hardware device for scanning barcodes in inventory and production operations

## Program Flow Charts

### Admin User Flow
```
Login
  ↓
Dashboard
  ↓
┌─────────────────────────────────────────────────────────────┐
│                    Admin Control Panel                      │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │  Inventory  │  │ Production  │  │    Menu     │         │
│  │ Management  │  │ Management  │  │ Management  │         │
│  └─────────────┘  └─────────────┘  └─────────────┘         │
│         ↓              ↓              ↓                     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │ Physical    │  │ Purchase    │  │   User      │         │
│  │ Count       │  │ Orders      │  │ Management  │         │
│  └─────────────┘  └─────────────┘  └─────────────┘         │
│         ↓              ↓              ↓                     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │ Low Stock   │  │  Backup &   │  │   Barcode   │         │
│  │ Alerts      │  │  Restore    │  │   System    │         │
│  └─────────────┘  └─────────────┘  └─────────────┘         │
└─────────────────────────────────────────────────────────────┘
         ↓
     Logout
```



### Manager User Flow
```
Login
  ↓
Dashboard
  ↓
┌─────────────────────────────────────────────────────────────┐
│                  Manager Operations Panel                   │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │  Inventory  │  │ Production  │  │    Menu     │         │
│  │ Management  │  │ Management  │  │ Management  │         │
│  │ (View/Add/  │  │ (View/Manage)│  │ (View Only) │         │
│  │  Edit)      │  └─────────────┘  └─────────────┘         │
│  └─────────────┘                                            │
│         ↓                                                   │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │ Physical    │  │ Purchase    │  │ Low Stock   │         │
│  │ Count       │  │ Orders      │  │ Alerts      │         │
│  │ (Full)      │  │ (Full)      │  │ (View Only)  │         │
│  └─────────────┘  └─────────────┘  └─────────────┘         │
│                                                            │
│  ┌─────────────┐                                           │
│  │   Barcode   │                                           │
│  │   System    │                                           │
│  │ (Full)      │                                           │
│  └─────────────┘                                           │
└─────────────────────────────────────────────────────────────┘
         ↓
     Logout
```

### User Flow
```
Login
  ↓
Dashboard
  ↓
┌─────────────────────────────────────────────────────────────┐
│                   User Access Panel                         │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │  Inventory  │  │ Production  │  │    Menu     │         │
│  │ (View Only) │  │ (View Only) │  │ (View Only) │         │
│  └─────────────┘  └─────────────┘  └─────────────┘         │
│                                                            │
│  ┌─────────────┐  ┌─────────────┐                          │
│  │ Purchase    │  │ Physical    │                          │
│  │ Orders      │  │ Count       │                          │
│  │ (View Only) │  │ (Limited)   │                          │
│  └─────────────┘  └─────────────┘                          │
│                                                            │
│  ┌─────────────┐                                           │
│  │   Barcode   │                                           │
│  │   System    │                                           │
│  │ (Limited)   │                                           │
│  └─────────────┘                                           │
└─────────────────────────────────────────────────────────────┘
         ↓
     Logout
```



## Use Case Diagram


The following diagram illustrates the use cases of the LUNA Inventory System, showing the interactions between different user types and system functionalities:

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                              LUNA Inventory System                             │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                 │
│          ┌─────────────┐          ┌─────────────┐          ┌─────────────┐     │
│          │   Admin     │          │   Manager   │          │    User     │     │
│          │             │          │             │          │             │     │
│          └──────┬──────┘          └──────┬──────┘          └──────┬──────┘     │
│                 │                       │                       │              │
│                 │                       │                       │              │
│          ┌──────▼──────┐          ┌──────▼──────┐          ┌──────▼──────┐     │
│          │  Login to   │          │  Login to   │          │  Login to   │     │
│          │   System    │          │   System    │          │   System    │     │
│          └──────┬──────┘          └──────┬──────┘          └──────┬──────┘     │
│                 │                       │                       │              │
│                 │                       │                       │              │
│          ┌──────▼──────┐          ┌──────▼──────┐          ┌──────▼──────┐     │
│          │   Access    │          │   Access    │          │   Access    │     │
│          │  Dashboard  │          │  Dashboard  │          │  Dashboard  │     │
│          └──────┬──────┘          └──────┬──────┘          └──────┬──────┘     │
│                 │                       │                       │              │
│                 │                       │                       │              │
│    ┌────────────▼────────────┐   ┌──────▼──────┐          ┌──────▼──────┐     │
│    │     Manage Users       │   │Manage Inventory│          │View Inventory│     │
│    │   (Create/Edit/Delete) │   │(Add/Edit/View)│          │  (Read Only) │     │
│    └────────────┬────────────┘   └──────┬──────┘          └──────┬──────┘     │
│                 │                       │                       │              │
│    ┌────────────▼────────────┐   ┌──────▼──────┐          ┌──────▼──────┐     │
│    │   Database Backup &    │   │Manage Production│          │View Production│     │
│    │       Restore          │   │(Full Control) │          │  (Read Only) │     │
│    └────────────┬────────────┘   └──────┬──────┘          └──────┬──────┘     │
│                 │                       │                       │              │
│    ┌────────────▼────────────┐   ┌──────▼──────┐          ┌──────▼──────┐     │
│    │   Configure Email &    │   │  Manage Menu │          │  View Menu   │     │
│    │     SMTP Settings      │   │ (View Only)  │          │  (Read Only) │     │
│    └────────────┬────────────┘   └──────┬──────┘          └──────┬──────┘     │
│                 │                       │                       │              │
│    ┌────────────▼────────────┐   ┌──────▼──────┐          ┌──────▼──────┐     │
│    │    Test Email Config   │   │Manage Purchase│          │View Purchase │     │
│    │                        │   │   Orders     │          │  Orders      │     │
│    └────────────┬────────────┘   └──────┬──────┘          └──────┬──────┘     │
│                 │                       │                       │              │
│    ┌────────────▼────────────┐   ┌──────▼──────┐          ┌──────▼──────┐     │
│    │   Full Barcode Access  │   │ Full Barcode │          │Limited Barcode│     │
│    │                        │   │   Access     │          │   Access     │     │
│    └────────────┬────────────┘   └──────┬──────┘          └──────┬──────┘     │
│                 │                       │                       │              │
│    ┌────────────▼────────────┐   ┌──────▼──────┐          ┌──────▼──────┐     │
│    │  Physical Count (Full) │   │Physical Count│          │Physical Count│     │
│    │                        │   │   (Full)     │          │  (Limited)   │     │
│    └────────────┬────────────┘   └──────┬──────┘          └──────┬──────┘     │
│                 │                       │                       │              │
│    ┌────────────▼────────────┐   ┌──────▼──────┐          ┌──────▼──────┐     │
│    │ Low Stock Alerts (Full)│   │View Low Stock│          │   No Access  │     │
│    │                        │   │   Alerts     │          │              │     │
│    └─────────────────────────┘   └─────────────┘          └─────────────┘     │
│                                                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### Use Case Descriptions:

#### Admin Use Cases:
- **Manage Users**: Create, edit, delete user accounts and manage permissions
- **Database Backup & Restore**: Export and import database backups
- **Configure Email & SMTP**: Set up email server configurations
- **Test Email Config**: Send test emails to verify SMTP settings
- **Full Barcode Access**: Complete access to all barcode scanning features
- **Physical Count (Full)**: Complete physical inventory counting capabilities
- **Low Stock Alerts (Full)**: Configure, view, and manage low stock alert system

#### Manager Use Cases:
- **Manage Inventory**: Add, edit, and view inventory items (cannot delete)
- **Manage Production**: Full control over production batches and tracking
- **Manage Menu**: View menu items (read-only access)
- **Manage Purchase Orders**: Create and manage purchase orders
- **Full Barcode Access**: Complete barcode scanning capabilities
- **Physical Count (Full)**: Full physical inventory counting access
- **View Low Stock Alerts**: View low stock alert information

#### User Use Cases:
- **View Inventory**: Read-only access to inventory items
- **View Production**: Read-only access to production records
- **View Menu**: Read-only access to menu items
- **View Purchase Orders**: Read-only access to purchase orders
- **Limited Barcode Access**: Restricted barcode scanning for assigned tasks
- **Physical Count (Limited)**: Limited access to physical counting features
- **No Access**: Cannot access user management, backup/restore, or low stock alerts

## Sequence Diagrams

### Admin User Sequence Diagram
```
┌─────────┐     ┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│  Admin  │     │   Web Browser   │     │   LUNA System   │     │   MySQL DB      │
└────┬────┘     └─────────┬───────┘     └─────────┬───────┘     └─────────┬───────┘
     │                   │                       │                       │
     │ 1. Access Login   │                       │                       │
     ├──────────────────►│                       │                       │
     │                   │ 2. Display Login Form │                       │
     │                   │◄──────────────────────┼                       │
     │ 3. Enter Credentials                      │                       │
     ├──────────────────►│                       │                       │
     │                   │ 4. Submit Login       │                       │
     │                   │──────────────────────►│                       │
     │                   │                       │ 5. Validate User      │
     │                   │                       ├──────────────────────►│
     │                   │                       │ 6. Return User Data   │
     │                   │                       │◄──────────────────────┼
     │                   │                       │ 7. Create Session     │
     │                   │                       │                       │
     │                   │ 8. Redirect to Dashboard                      │
     │                   │◄──────────────────────┼                       │
     │ 9. Display Dashboard                      │                       │
     │◄──────────────────┼                       │                       │
     │                   │                       │                       │
     │ 10. Access User Management               │                       │
     ├──────────────────►│                       │                       │
     │                   │ 11. Request User List │                       │
     │                   │──────────────────────►│                       │
     │                   │                       │ 12. Query Users       │
     │                   │                       ├──────────────────────►│
     │                   │                       │ 13. Return User Data  │
     │                   │                       │◄──────────────────────┼
     │                   │                       │ 14. Render User List  │
     │                   │ 15. Display User Management                   │
     │                   │◄──────────────────────┼                       │
     │ 16. Display User Management               │                       │
     │◄──────────────────┼                       │                       │
     │                   │                       │                       │
     │ 17. Logout        │                       │                       │
     ├──────────────────►│                       │                       │
     │                   │ 18. Destroy Session   │                       │
     │                   │──────────────────────►│                       │
     │                   │ 19. Redirect to Login │                       │
     │                   │◄──────────────────────┼                       │
```

### Manager User Sequence Diagram
```
┌──────────┐     ┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│ Manager  │     │   Web Browser   │     │   LUNA System   │     │   MySQL DB      │
└─────┬────┘     └─────────┬───────┘     └─────────┬───────┘     └─────────┬───────┘
      │                   │                       │                       │
      │ 1. Access Login   │                       │                       │
      ├──────────────────►│                       │                       │
      │                   │ 2. Display Login Form │                       │
      │                   │◄──────────────────────┼                       │
      │ 3. Enter Credentials                      │                       │
      ├──────────────────►│                       │                       │
      │                   │ 4. Submit Login       │                       │
      │                   │──────────────────────►│                       │
      │                   │                       │ 5. Validate User      │
      │                   │                       ├──────────────────────►│
      │                   │                       │ 6. Return User Data   │
      │                   │                       │◄──────────────────────┼
      │                   │                       │ 7. Create Session     │
      │                   │                       │                       │
      │                   │ 8. Redirect to Dashboard                      │
      │                   │◄──────────────────────┼                       │
      │ 9. Display Dashboard                      │                       │
      │◄──────────────────┼                       │                       │
      │                   │                       │                       │
      │ 10. Access Inventory Management          │                       │
      ├──────────────────►│                       │                       │
      │                   │ 11. Request Inventory│                       │
      │                   │──────────────────────►│                       │
      │                   │                       │ 12. Query Inventory   │
      │                   │                       ├──────────────────────►│
      │                   │                       │ 13. Return Data       │
      │                   │                       │◄──────────────────────┼
      │                   │                       │ 14. Render Table      │
      │                   │ 15. Display Inventory                        │
      │                   │◄──────────────────────┼                       │
      │ 16. Display Inventory                     │                       │
      │◄──────────────────┼                       │                       │
      │                   │                       │                       │
      │ 17. Add New Item  │                       │                       │
      ├──────────────────►│                       │                       │
      │                   │ 18. Submit Form       │                       │
      │                   │──────────────────────►│                       │
      │                   │                       │ 19. Validate & Save   │
      │                   │                       ├──────────────────────►│
      │                   │                       │ 20. Confirm Save      │
      │                   │                       │◄──────────────────────┼
      │                   │                       │ 21. Success Message   │
      │                   │ 22. Redirect to List  │                       │
      │                   │◄──────────────────────┼                       │
      │                   │                       │                       │
      │ 23. Logout        │                       │                       │
      ├──────────────────►│                       │                       │
      │                   │ 24. Destroy Session   │                       │
      │                   │──────────────────────►│                       │
      │                   │ 25. Redirect to Login │                       │
      │                   │◄──────────────────────┼                       │
```

### User Sequence Diagram
```
┌────────┐     ┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│  User  │     │   Web Browser   │     │   LUNA System   │     │   MySQL DB      │
└───┬────┘     └─────────┬───────┘     └─────────┬───────┘     └─────────┬───────┘
    │                   │                       │                       │
    │ 1. Access Login   │                       │                       │
    ├──────────────────►│                       │                       │
    │                   │ 2. Display Login Form │                       │
    │                   │◄──────────────────────┼                       │
    │ 3. Enter Credentials                      │                       │
    ├──────────────────►│                       │                       │
    │                   │ 4. Submit Login       │                       │
    │                   │──────────────────────►│                       │
    │                   │                       │ 5. Validate User      │
    │                   │                       ├──────────────────────►│
    │                   │                       │ 6. Return User Data   │
    │                   │                       │◄──────────────────────┼
    │                   │                       │ 7. Create Session     │
    │                   │                       │                       │
    │                   │ 8. Redirect to Dashboard                      │
    │                   │◄──────────────────────┼                       │
    │ 9. Display Dashboard                      │                       │
    │◄──────────────────┼                       │                       │
    │                   │                       │                       │
    │ 10. View Inventory│                       │                       │
    ├──────────────────►│                       │                       │
    │                   │ 11. Request Inventory│                       │
    │                   │──────────────────────►│                       │
    │                   │                       │ 12. Query Inventory   │
    │                   │                       ├──────────────────────►│
    │                   │                       │ 13. Return Data       │
    │                   │                       │◄──────────────────────┼
    │                   │                       │ 14. Render Read-only  │
    │                   │ 15. Display Inventory                        │
    │                   │◄──────────────────────┼                       │
    │ 16. Display Inventory                     │                       │
    │◄──────────────────┼                       │                       │
    │                   │                       │                       │
    │ 17. View Production                       │                       │
    ├──────────────────►│                       │                       │
    │                   │ 18. Request Production                       │
    │                   │──────────────────────►│                       │
    │                   │                       │ 19. Query Production  │
    │                   │                       ├──────────────────────►│
    │                   │                       │ 20. Return Data       │
    │                   │                       │◄──────────────────────┼
    │                   │                       │ 21. Render Read-only  │
    │                   │ 22. Display Production                       │
    │                   │◄──────────────────────┼                       │
    │                   │                       │                       │
    │ 23. Logout        │                       │                       │
    ├──────────────────►│                       │                       │
    │                   │ 24. Destroy Session   │                       │
    │                   │──────────────────────►│                       │
    │                   │ 25. Redirect to Login │                       │
    │                   │◄──────────────────────┼                       │
```
=======
