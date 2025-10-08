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



## System Activity Diagram

The following activity diagram illustrates the overall workflow and activities within the LUNA Inventory System:

```
┌─────────────────┐
│   Start System  │
└───────┬─────────┘
        │
        v
┌─────────────────┐
│   User Login    │
│   (Admin/Manager│
│      /User)     │
└───────┬─────────┘
        │
        v
┌─────────────────┐     ┌─────────────────┐
│ Access Dashboard│────►│   Check User    │
│                 │     │     Role        │
└───────┬─────────┘     └───────┬─────────┘
        │                       │
        v                       v
┌─────────────────┐     ┌─────────────────┐
│ Select Module   │     │   Admin Path    │
│ (Inventory/Prod/│     └───────┬─────────┘
│  Menu/etc.)     │             │
└───────┬─────────┘             v
        │             ┌─────────────────┐
        v             │ User Management │
┌─────────────────┐  │ (Create/Edit/   │
│   Module Access │  │   Delete Users) │
│   Check         │  └───────┬─────────┘
└───────┬─────────┘          │
        │                    v
        v           ┌─────────────────┐
┌─────────────────┐ │ System Admin    │
│   Permission    │ │ (Backup/Restore│
│   Granted?      │ │ Email Config)   │
└───────┬─────────┘ └───────┬─────────┘
   ├────┘                    │
   │ No                      v
   v               ┌─────────────────┐
┌─────────────────┐│   Manager Path  │
│  Access Denied  │└───────┬─────────┘
│  (Show Error)   │        │
└───────┬─────────┘        v
        │         ┌─────────────────┐
        v         │ Manage Inventory│
┌─────────────────┐│ (Add/Edit/View │
│     End         ││ Cannot Delete) │
└─────────────────┘└───────┬─────────┘
                          │
                          v
                 ┌─────────────────┐
                 │ Manage Production│
                 │ (Full Control)   │
                 └───────┬─────────┘
                          │
                          v
                 ┌─────────────────┐
                 │  View Reports   │
                 │ (Low Stock      │
                 │   Alerts)       │
                 └───────┬─────────┘
                          │
                          v
                 ┌─────────────────┐
                 │   User Path     │
                 └───────┬─────────┘
                          │
                          v
                 ┌─────────────────┐
                 │ View Inventory  │
                 │ (Read Only)     │
                 └───────┬─────────┘
                          │
                          v
                 ┌─────────────────┐
                 │ View Production │
                 │ (Read Only)     │
                 └───────┬─────────┘
                          │
                          v
                 ┌─────────────────┐
                 │ Limited Barcode │
                 │ Operations      │
                 └───────┬─────────┘
                          │
                          v
                 ┌─────────────────┐
                 │   User Logout   │
                 └───────┬─────────┘
                          │
                          v
                 ┌─────────────────┐
                 │   System End    │
                 └─────────────────┘
```

### Activity Flow Description:

1. **System Start**: User initiates access to the LUNA system
2. **User Login**: Authentication process for Admin, Manager, or User roles
3. **Dashboard Access**: Main system interface loads
4. **Role Check**: System determines user permissions based on role
5. **Module Selection**: User chooses specific functionality (Inventory, Production, etc.)
6. **Permission Check**: System validates if user has access to selected module
7. **Role-Specific Paths**:
   - **Admin Path**: Full system access including user management and system administration
   - **Manager Path**: Operational management with add/edit capabilities
   - **User Path**: Read-only access to basic modules
8. **Operations**: Users perform allowed activities within their permission level
9. **Logout**: Session termination and system exit
