<?php
require_once 'app/config.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME,
        DB_USERNAME,
        DB_PASSWORD
    );
    
    // Add the sign_date column to the user table
    $sql = "ALTER TABLE user ADD COLUMN sign_date DATE NULL DEFAULT NULL";
    $pdo->exec($sql);
    
    echo "Column 'sign_date' added successfully to the user table.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>