<?php
/**
 * Controller for Users page
 */
require BASE_PATH . '/core/Controller.php';
require BASE_PATH . '/app/models/UserModel.php';
require BASE_PATH . '/app/helpers/EmailHelper.php';

class UserController extends Controller
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();
        $this->model = new UserModel();
    }

    public function index()
    {
        $users = $this->model->getAll();
        $this->view('users', ['users' => $users]);
    }



    public function getDetail()
    {
        $response = [];
        $id = isset($_POST['id']) ? filter_var(trim($_POST['id']), FILTER_SANITIZE_STRING) : '';


        if (!empty($id)) {
            $response = $this->model->getByID($id);
        }

        echo json_encode($response);
    }

    public function getAll()
    {
        $users = $this->model->getAll();
        header('Content-Type: application/json');
        echo json_encode($users);
    }



    public function add()
    {
        $response = array('success' => false);
        $data = array(
            'username' => filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING),
            'first_name' => filter_var(trim($_POST['first_name']), FILTER_SANITIZE_STRING),
            'last_name' => filter_var(trim($_POST['last_name']), FILTER_SANITIZE_STRING),
            'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL),
            'user_type' => filter_var(trim($_POST['user_type']), FILTER_SANITIZE_STRING),
            'password' => password_hash(trim($_POST['password']), PASSWORD_DEFAULT) // Hash password on add
        );
        if (!empty($data['username']) && !empty($data['first_name']) && !empty($data['last_name']) && !empty($data['email']) && !empty($data['user_type']) && !empty($_POST['password'])) {
            $response['success'] = $this->model->insert($data);
        }
        echo json_encode($response);
    }

    public function edit()
    {
        $response = array('success' => false);
        $data = array(
            'id' => filter_var(trim($_POST['id']), FILTER_SANITIZE_STRING),
            'username' => filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING),
            'first_name' => filter_var(trim($_POST['first_name']), FILTER_SANITIZE_STRING),
            'last_name' => filter_var(trim($_POST['last_name']), FILTER_SANITIZE_STRING),
            'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL),
            'user_type' => filter_var(trim($_POST['user_type']), FILTER_SANITIZE_STRING)
        );
        // Only update password if provided
        if (!empty($_POST['password'])) {
            $data['password'] = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash password on update
        }
        if (!empty($data['id']) && !empty($data['first_name']) && !empty($data['last_name']) && !empty($data['email']) && !empty($data['user_type'])) {
            $response['success'] = $this->model->update($data);
        }
        echo json_encode($response);
    }

    public function delete()
    {
        $response = array('success' => false);
        $id = filter_var(trim($_POST['id']), FILTER_SANITIZE_STRING);

        if (!empty($id)) {
            $response['success'] = $this->model->delete($id);
        }

        echo json_encode($response);
    }

    public function testEmail()
    {
        // Set proper headers to prevent any output before JSON
        header('Content-Type: application/json');
        
        try {
            $response = ['success' => false, 'message' => ''];
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Admin') {
                $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
                $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);

                if (!empty($email) && !empty($name)) {
                    $subject = 'Test Email';
                    $body = 'Hello ' . $name . ',<br><br>This is a test email from the system.';
                    $emailResult = $this->sendEmail($email, $subject, $body);
                    
                    if ($emailResult['success']) {
                        $response['success'] = true;
                        $response['message'] = $emailResult['message'];
                        if (isset($emailResult['debug_info'])) {
                            $response['debug_info'] = $emailResult['debug_info'];
                        }
                    } else {
                        $response['message'] = $emailResult['message'];
                    }
                } else {
                    $response['message'] = 'Email and name are required';
                }
            } else {
                $response['message'] = 'Admin access required';
            }
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'PHP Error: ' . $e->getMessage()
            ];
        }
        
        echo json_encode($response);
        exit;
    }

    public function debugEmailConfig()
    {
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Admin') {
            $smtpUsername = getenv('SMTP_USERNAME');
            $smtpPassword = getenv('SMTP_PASSWORD');
            $fromEmail = getenv('FROM_EMAIL');
            $fromName = getenv('FROM_NAME');
            
            $response = [
                'env_file_exists' => file_exists(__DIR__ . '/../../.env'),
                'smtp_username' => $smtpUsername ? 'Set' : 'Not set',
                'smtp_password' => $smtpPassword ? 'Set (length: ' . strlen($smtpPassword) . ')' : 'Not set',
                'from_email' => $fromEmail ?: 'Using default: noreply@luna-mail.8800111.xyz',
                'from_name' => $fromName ?: 'Using default: LUNA Inventory System',
                'mail_function' => function_exists('mail') ? 'Available' : 'Not available'
            ];
            
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Admin access required']);
        }
    }

    private function sendEmail($to, $subject, $body)
    {
        // Use EmailHelper to send emails via SMTP
        $emailHelper = new EmailHelper();
        return $emailHelper->send($to, $subject, $body);
    }
}