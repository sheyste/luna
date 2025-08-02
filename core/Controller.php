<?php
/**
 * Main controller class
 */
class Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function view($view, $data = [])
    {
        $filePath = BASE_PATH . '/app/views/' . $view . '.php';
        if (file_exists($filePath)) {
            extract($data);
            include $filePath;
        } else {
            die("View not found: " . $filePath);
        }
    }

    protected function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    protected function checkAuth()
    {
        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }
}
