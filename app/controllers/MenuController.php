<?php

require_once __DIR__ . '/../models/Menu.php';
require_once BASE_PATH . '/core/Controller.php';

class MenuController extends Controller
{
    private $menuModel;

    public function __construct()
    {
        parent::__construct();
        $this->checkAuth(); 
        $this->menuModel = new Menu();
    }

    public function index()
    {
        $menus = $this->menuModel->getAll();
        $this->view('menu', ['menus' => $menus]);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->menuModel->create($_POST);
        }
        header('Location: /menu');
        exit();
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->menuModel->update($_POST);
        }
        header('Location: /menu');
        exit();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->menuModel->delete($_POST['id']);
        }
        header('Location: /menu');
        exit();
    }

    public function getDetail()
    {
        if (isset($_GET['id'])) {
            $menu = $this->menuModel->getById($_GET['id']);
            header('Content-Type: application/json');
            echo json_encode($menu);
        }
    }

    public function getMenus()
    {
        header('Content-Type: application/json');
        echo json_encode($this->menuModel->getAll());
    }
}
