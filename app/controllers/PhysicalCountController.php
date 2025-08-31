<?php

require_once __DIR__ . '/../models/Inventory.php';
require_once BASE_PATH . '/core/Controller.php';
require_once __DIR__ . '/../models/PhysicalCount.php';

class PhysicalCountController extends Controller
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
        $pendingEntries = $this->physicalCountModel->getPendingEntries();
        $this->view('physical_count', ['items' => $items, 'pendingEntries' => $pendingEntries]);
    }

    public function addCountEntry()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (isset($input['id']) && isset($input['physicalCount'])) {
                // Get current inventory item details
                $inventoryItem = $this->inventoryModel->getItemById($input['id']);
                
                if ($inventoryItem) {
                    // Calculate difference and other metrics
                    $systemCount = $inventoryItem['quantity'];
                    $physicalCount = $input['physicalCount'];
                    $difference = $physicalCount - $systemCount;
                    $variancePercent = $systemCount != 0 ? ($difference / $systemCount) * 100 : 0;
                    $valueImpact = $difference * $inventoryItem['price'];
                    
                    // Check if entry already exists for this inventory item
                    $existingEntry = $this->physicalCountModel->getEntryByInventoryId($input['id']);
                    
                    if ($existingEntry) {
                        // Update existing entry
                        $entryData = [
                            'id' => $existingEntry['id'],
                            'physical_count' => $physicalCount,
                            'difference' => $difference,
                            'variance_percent' => $variancePercent,
                            'value_impact' => $valueImpact
                        ];
                        
                        $result = $this->physicalCountModel->updateEntry($entryData);
                    } else {
                        // Save new entry to physical count table (without updating inventory)
                        $entryData = [
                            'inventory_id' => $input['id'],
                            'item_name' => $inventoryItem['name'],
                            'system_count' => $systemCount,
                            'physical_count' => $physicalCount,
                            'difference' => $difference,
                            'variance_percent' => $variancePercent,
                            'value_impact' => $valueImpact,
                            'unit_price' => $inventoryItem['price']
                        ];
                        
                        $result = $this->physicalCountModel->addEntry($entryData);
                    }
                    
                    if ($result !== false) {
                        echo json_encode(['success' => true, 'message' => 'Count entry saved to database']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to save count entry']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Inventory item not found']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid data']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
    }

    public function getPendingEntries()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $pendingEntries = $this->physicalCountModel->getPendingEntries();
            echo json_encode(['success' => true, 'data' => $pendingEntries]);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
    }

    public function deleteCountEntry()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (isset($input['entryId'])) {
                $result = $this->physicalCountModel->deleteEntry($input['entryId']);
                
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Entry deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete entry']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid data']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
    }

    public function savePhysicalCount()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (isset($input['entries']) && is_array($input['entries'])) {
                foreach ($input['entries'] as $entryId) {
                    // Get the physical count entry from database
                    $entry = $this->physicalCountModel->getEntryById($entryId);
                    
                    if ($entry) {
                        // Update the inventory with the physical count
                        $this->inventoryModel->updateQuantity($entry['inventory_id'], $entry['physical_count']);
                        
                        // Mark entry as processed
                        $this->physicalCountModel->updateEntryStatus($entryId, 'saved');
                    }
                }
                
                echo json_encode(['success' => true, 'message' => 'Physical counts saved to inventory successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid data']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
    }
}