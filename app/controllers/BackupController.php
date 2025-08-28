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

    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if file was uploaded without errors
            if (isset($_FILES['backupFile']) && $_FILES['backupFile']['error'] === 0) {
                $fileTmpPath = $_FILES['backupFile']['tmp_name'];
                $fileName = $_FILES['backupFile']['name'];
                $fileSize = $_FILES['backupFile']['size'];
                $fileType = $_FILES['backupFile']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                // Check if file is a SQL file
                if ($fileExtension === 'sql') {
                    // Read the SQL file
                    $sqlContent = file_get_contents($fileTmpPath);
                    
                    if ($sqlContent === false) {
                        $this->view('backup', ['error' => 'Failed to read the backup file.']);
                        return;
                    }

                    // Split the SQL content into individual statements
                    $statements = explode(";\n", $sqlContent);
                    
                    // Execute each statement
                    $this->conn->query("SET foreign_key_checks = 0");
                    foreach ($statements as $statement) {
                        $statement = trim($statement);
                        if (!empty($statement)) {
                            if (!$this->conn->query($statement)) {
                                $this->conn->query("SET foreign_key_checks = 1");
                                $this->view('backup', ['error' => 'Error executing SQL statement: ' . $this->conn->error]);
                                return;
                            }
                        }
                    }
                    $this->conn->query("SET foreign_key_checks = 1");
                    
                    $this->view('backup', ['success' => 'Database restored successfully from ' . $fileName]);
                } else {
                    $this->view('backup', ['error' => 'Invalid file type. Please upload a .sql file.']);
                }
            } else {
                $this->view('backup', ['error' => 'Error uploading file. Please try again.']);
            }
        } else {
            // Redirect to backup page if not a POST request
            header('Location: /backup');
            exit;
        }
    }
}