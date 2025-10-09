<?php
/**
 * Main model class
 */

require_once BASE_PATH . '/app/config.php';

class Model
{
    protected $table;
    protected $db;

    function __construct()
    {

    }

    protected function connectDB()
    {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME,
                DB_USERNAME,
                DB_PASSWORD
            );
            return $pdo;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }
    }
}
