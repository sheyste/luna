<?php

require_once __DIR__ . '/../models/Inventory.php';
require BASE_PATH . '/core/Controller.php';

class InventoryController extends Controller
{
    private $inventoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->inventoryModel = new Inventory();
    }

    public function index()
    {
        $items = $this->inventoryModel->getAllItems();
        $this->view('inventory', ['items' => $items]);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->inventoryModel->create($_POST);
        }
        header('Location: /inventory');
        exit();
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->inventoryModel->update($_POST);
        }
        header('Location: /inventory');
        exit();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->inventoryModel->delete($_POST['id']);
        }
        header('Location: /inventory');
        exit();
    }

    public function getDetail()
    {
        if (isset($_GET['id'])) {
            $item = $this->inventoryModel->getItemById($_GET['id']);
            header('Content-Type: application/json');
            echo json_encode($item);
        }
    }

    public function getAll()
    {
        $items = $this->inventoryModel->getAllItems();
        header('Content-Type: application/json');
        echo json_encode($items);
    }
}