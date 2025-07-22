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

-   PHP 7.4 or higher
-   MySQL / MariaDB
-   Web Server (Apache, Nginx) - XAMPP or WAMP is recommended for local development.

## Installation and Setup

1.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd <project-directory>
    ```

2.  **Create a Database:**
    -   Create a new database in your MySQL server (e.g., via phpMyAdmin). Let's name it `luna_db`.

3.  **Import the Database Schema:**
    -   Create a file named `database.sql` in the root of the project and add the following SQL to create the necessary `user` table and a default admin user.

    ```sql
    CREATE DATABASE IF NOT EXISTS `luna_db`;
    USE `luna_db`;

    CREATE TABLE `user` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `first_name` varchar(50) NOT NULL,
      `last_name` varchar(50) NOT NULL,
      `email` varchar(100) NOT NULL,
      `password` varchar(255) NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    -- Insert a default admin user
    -- Password is 'password'
    INSERT INTO `user` (`first_name`, `last_name`, `email`, `password`) VALUES
    ('Admin', 'User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
    ```
    -   Import this `database.sql` file into your `luna_db` database.

4.  **Configure Database Connection:**
    -   Open the file `core/Model.php`.
    -   Update the database connection details in the `connectDB()` method to match your environment.

5.  **Run the Application:**
    -   Place the project folder inside your web server's root directory (e.g., `htdocs` for XAMPP).
    -   Open your web browser and navigate to `http://localhost/your-project-folder/`.
    -   You should see the login page.

## Default Login Credentials

-   **Email:** `admin@example.com`
-   **Password:** `password`

## Project Structure

```
./
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
├── assets/
├── core/
└── routes.php
```