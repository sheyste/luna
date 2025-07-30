# LUNA Inventory System

A simple, lightweight inventory and user management system built with PHP using a basic MVC (Model-View-Controller) architecture. The front-end is styled with Bootstrap 5 and uses jQuery and DataTables for dynamic table interactions.

## Features

-   **Secure User Authentication**: Login and logout functionality with password hashing and session management.
-   **User Management (CRUD)**: Add, view, edit, and delete users.
-   **Inventory Management (CRUD)**: Basic structure for managing inventory items.
-   **MVC Architecture**: Organized codebase separating business logic, data, and presentation.
-   **Responsive UI**: Built with Bootstrap 5 for a clean and responsive user interface on all devices.
-   **Dynamic Data Tables**: Uses jQuery DataTables for feature-rich tables with sorting, searching, and pagination.

## Requirements

-   XAMPP latest
-   MySQL / Workbench

## Installation and Setup

1.  **Clone the repository:**
    -    Download this repository
    -    Export the contents in `htdocs` in xampp

3.  **Import the Database Schema:**
    -   Import `luna.sql` file to workbench.

4.  **Configure Database Connection:**
    -   Open the file `core/Model.php`.
    -   Update the database connection details in the `connectDB()` method to match your environment.

5.  **Run the Application:**
    -   Place the project folder inside your web server's root directory (e.g., `htdocs` for XAMPP).
    -   Open your web browser and navigate to `http://localhost/`.
    -   You should see the login page.

## Default Login Credentials

-   **Email:** `admin@admin.com`
-   **Password:** `admin`

