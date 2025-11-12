<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/UserModel.php';

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    /**
     * Display login page or redirect to home if already logged in.
     */
    public function index()
    {
        if ($this->isLoggedIn()) {
            header('Location: /home');
            exit;
        }
        $this->view('login');
    }

    /**
     * Handle login attempt.
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $error = '';

            if (empty($username) || empty($password)) {
                $error = 'Username and password are required.';
            } else {
                $user = $this->userModel->getByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    // Password is correct, regenerate session ID to prevent session fixation
                    session_regenerate_id(true);

                    // Start session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['first_name'];
                    $_SESSION['user_type'] = $user['user_type'];

                    // Redirect based on user type
                    if ($user['user_type'] === 'Inventory Staff') {
                        header('Location: /inventory');
                    } elseif ($user['user_type'] === 'Cashier' || $user['user_type'] === 'Kitchen Staff') {
                        header('Location: /production');
                    } else {
                        header('Location: /home');
                    }
                    exit;
                } else {
                    // Invalid credentials
                    // Use a generic error message to prevent user enumeration
                    $error = 'Invalid username or password.';
                }
            }

            // If login fails, show login page with error
            $this->view('login', ['error' => $error]);
        } else {
            // Redirect to login if accessed directly via GET
            header('Location: /');
            exit;
        }
    }

    /**
     * Handle logout.
     */
    public function logout()
    {
        // Unset all of the session variables
        $_SESSION = [];

        // Delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        header('Location: /');
        exit;
    }
}
