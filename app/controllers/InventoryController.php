<?php

require_once __DIR__ . '/../models/Inventory.php';
require_once BASE_PATH . '/core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once BASE_PATH . '/app/helpers/EmailHelper.php';
require_once __DIR__ . '/../models/PhysicalCount.php';

class InventoryController extends Controller
{
    private $physicalCountModel;
    private $inventoryModel;

    public function __construct()
    {
        parent::__construct();

        $this->checkAuth();

        $this->inventoryModel = new Inventory();
        $this->physicalCountModel = new PhysicalCount();
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

    public function physicalCount()
    {
        $items = $this->inventoryModel->getAllItems();
        $this->view('physical_count', ['items' => $items]);
    }

    public function printBarcode()
    {
        if (isset($_GET['id']) && isset($_GET['barcode'])) {
            $itemId = $_GET['id'];
            $barcode = $_GET['barcode'];
            
            // Get item details
            $item = $this->inventoryModel->getItemById($itemId);
            
            if (!$item) {
                header('Location: /inventory');
                exit();
            }
            
            $this->view('barcode/print', [
                'item' => $item,
                'barcode' => $barcode
            ]);
        } else {
            header('Location: /inventory');
            exit();
        }
    }
}