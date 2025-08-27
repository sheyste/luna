<?php
/**
 * Controller for Database Backup functionality
 */
require BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/config.php';

class BackupController extends Controller
{
    private $conn;

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
        if ($this->conn->connect_error) {
            die('Database connection failed: ' . $this->conn->connect_error);
        }
    }

    public function index() {
        // Display the backup page
        $this->view('backup');
    }

    public function download() {
        // Set headers for file download
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="luna_backup_' . date('Y-m-d_H-i-s') . '.sql"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Get all table names
        $tables = array();
        $result = $this->conn->query("SHOW TABLES");
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }

        // Generate SQL dump
        $sqlDump = "-- Luna Database Backup\n";
        $sqlDump .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($tables as $table) {
            // Get table structure
            $sqlDump .= "-- Table structure for table `$table`\n";
            $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n";
            $createTableResult = $this->conn->query("SHOW CREATE TABLE `$table`");
            $createTableRow = $createTableResult->fetch_row();
            $sqlDump .= $createTableRow[1] . ";\n\n";

            // Get table data
            $sqlDump .= "-- Dumping data for table `$table`\n";
            $dataResult = $this->conn->query("SELECT * FROM `$table`");
            if ($dataResult->num_rows > 0) {
                while ($row = $dataResult->fetch_assoc()) {
                    $sqlDump .= "INSERT INTO `$table` VALUES (";
                    $values = array();
                    foreach ($row as $value) {
                        $values[] = is_null($value) ? 'NULL' : "'" . $this->conn->real_escape_string($value) . "'";
                    }
                    $sqlDump .= implode(', ', $values) . ");\n";
                }
            }
            $sqlDump .= "\n";
        }

        // Output the SQL dump
        echo $sqlDump;
        exit;
    }
}