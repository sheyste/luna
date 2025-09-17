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
            'receive_email' => isset($_POST['receive_email']) ? (int)$_POST['receive_email'] : 0,
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
            'user_type' => filter_var(trim($_POST['user_type']), FILTER_SANITIZE_STRING),
            'receive_email' => isset($_POST['receive_email']) ? (int)$_POST['receive_email'] : 0
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
        // Prevent any output before JSON
        ob_clean();
        
        // Set proper headers to prevent any output before JSON
        header('Content-Type: application/json');
        
        try {
            $response = ['success' => false, 'message' => ''];
            
            // Check if user is admin
            if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
                $response['message'] = 'Admin access required';
                echo json_encode($response);
                exit;
            }
            
            // Validate input
            $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
            $name = isset($_POST['name']) ? filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING) : '';

            if (empty($email) || empty($name)) {
                $response['message'] = 'Email and name are required';
                echo json_encode($response);
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response['message'] = 'Invalid email address format';
                echo json_encode($response);
                exit;
            }

            $subject = 'SMTP Test Email - LUNA Inventory System';
            $body = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>SMTP Test Email</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #293145; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; background-color: #f9f9f9; }
                    .success { color: #16a34a; font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>LUNA Inventory System</h2>
                    </div>
                    <div class="content">
                        <h3>SMTP Test Email</h3>
                        <p>Hello ' . htmlspecialchars($name) . ',</p>
                        <p class="success">✅ Congratulations! Your SMTP configuration is working correctly.</p>
                        <p>This test email was sent successfully via SMTP to verify your email settings.</p>
                        <hr>
                        <p><strong>Test Details:</strong></p>
                        <ul>
                            <li>Sent at: ' . date('Y-m-d H:i:s') . '</li>
                            <li>Recipient: ' . htmlspecialchars($email) . '</li>
                            <li>SMTP Host: ' . (getenv('SMTP_HOST') ?: 'Not configured') . '</li>
                            <li>SMTP Port: ' . (getenv('SMTP_PORT') ?: 'Not configured') . '</li>
                            <li>Security: ' . (getenv('SMTP_SECURITY') ?: 'Not configured') . '</li>
                        </ul>
                    </div>
                </div>
            </body>
            </html>';
            
            $emailResult = $this->sendEmail($email, $subject, $body);
            
            if ($emailResult['success']) {
                $response['success'] = true;
                $response['message'] = $emailResult['message'];
                $response['method'] = isset($emailResult['method']) ? $emailResult['method'] : 'SMTP';
                $response['smtp_host'] = isset($emailResult['smtp_host']) ? $emailResult['smtp_host'] : '';
            } else {
                $response['message'] = $emailResult['message'];
                // Include additional error details if available
                if (isset($emailResult['http_code'])) {
                    $response['http_code'] = $emailResult['http_code'];
                }
                if (isset($emailResult['response'])) {
                    $response['response'] = $emailResult['response'];
                }
                if (isset($emailResult['headers'])) {
                    $response['headers'] = $emailResult['headers'];
                }
            }
            
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'PHP Error: ' . $e->getMessage()
            ];
        } catch (Error $e) {
            $response = [
                'success' => false,
                'message' => 'Fatal Error: ' . $e->getMessage()
            ];
        }
        
        echo json_encode($response);
        exit;
    }

    public function debugEmailConfig()
    {
        // Prevent any output before JSON
        ob_clean();
        
        // Set proper headers to prevent any output before JSON
        header('Content-Type: application/json');
        
        try {
            // Check if user is admin
            if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
                echo json_encode(['error' => 'Admin access required']);
                exit;
            }
            
            $smtpHost = getenv('SMTP_HOST');
            $smtpPort = getenv('SMTP_PORT');
            $smtpUsername = getenv('SMTP_USERNAME');
            $smtpPassword = getenv('SMTP_PASSWORD');
            $smtpSecurity = getenv('SMTP_SECURITY');
            $fromEmail = getenv('FROM_EMAIL');
            $fromName = getenv('FROM_NAME');
            
            $response = [
                'env_file_exists' => file_exists(__DIR__ . '/../../.env'),
                'smtp_host' => $smtpHost ?: 'Not configured',
                'smtp_port' => $smtpPort ?: 'Using default: 587',
                'smtp_username' => $smtpUsername ? 'Set (length: ' . strlen($smtpUsername) . ')' : 'Not set',
                'smtp_password' => $smtpPassword ? 'Set (length: ' . strlen($smtpPassword) . ')' : 'Not set',
                'smtp_security' => $smtpSecurity ?: 'Using default: tls',
                'from_email' => $fromEmail ?: 'Using default: noreply@luna-inventory.local',
                'from_name' => $fromName ?: 'Using default: LUNA Inventory System',
                'curl_extension' => extension_loaded('curl') ? 'Available' : 'Not available',
                'openssl_extension' => extension_loaded('openssl') ? 'Available (required for TLS/SSL)' : 'Not available'
            ];
            
            echo json_encode($response);
            
        } catch (Exception $e) {
            echo json_encode([
                'error' => 'Configuration check failed',
                'message' => $e->getMessage()
            ]);
        } catch (Error $e) {
            echo json_encode([
                'error' => 'Fatal error during configuration check',
                'message' => $e->getMessage()
            ]);
        }
        
        exit;
    }

    private function sendEmail($to, $subject, $body)
    {
        // Use EmailHelper to send emails via SMTP
        $emailHelper = new EmailHelper();
        return $emailHelper->send($to, $subject, $body);
    }
}
