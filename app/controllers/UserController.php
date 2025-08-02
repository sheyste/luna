<?php
/**
 * Controller for Users page
 */
require BASE_PATH . '/core/Controller.php';
require BASE_PATH . '/app/models/UserModel.php';

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

    public function load()
    {
        $users = $this->model->getAll();
        $this->view('user_grid', ['users' => $users]);
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
}
