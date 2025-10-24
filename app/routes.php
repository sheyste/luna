<?php
/**
 *  define routes with its controllers and actions
 */
$routes = array(
    '/'             => array('AuthController', 'index'),
    '/login'        => array('AuthController', 'login'),
    '/logout'       => array('AuthController', 'logout'),
    '/home'         => array('HomeController', 'index'),
    
    '/inventory'    => array('InventoryController', 'index'),
    '/inventory/add' => array('InventoryController', 'add'),
    '/inventory/edit' => array('InventoryController', 'edit'),
    '/inventory/delete' => array('InventoryController', 'delete'),
    '/inventory/getDetail' => array('InventoryController', 'getDetail'),
        '/inventory/getAll' => array('InventoryController', 'getAll'),
        '/inventory/print-barcode' => array('InventoryController', 'printBarcode'),
        '/inventory/physical-count' => array('PhysicalCountController', 'index'),
    '/inventory/addCountEntry' => array('PhysicalCountController', 'addCountEntry'),
    '/inventory/getPendingEntries' => array('PhysicalCountController', 'getPendingEntries'),
    '/inventory/deleteCountEntry' => array('PhysicalCountController', 'deleteCountEntry'),
    '/inventory/savePhysicalCount' => array('PhysicalCountController', 'savePhysicalCount'),
    '/inventory/physical-count-export' => array('PhysicalCountController', 'exportExcel'),
    '/inventory/check-low-stock-db' => array('InventoryController', 'checkLowStockDb'),
    '/inventory/check-and-send-alerts' => array('InventoryController', 'checkAndSendAlerts'),
    '/inventory/low-stock-alerts' => array('LowStockAlertController', 'index'),
    '/inventory/auto-update-alerts' => array('LowStockAlertController', 'autoUpdateAlerts'),
    '/inventory/check-low-stock-db' => array('LowStockAlertController', 'checkLowStockDb'),
    '/inventory/check-and-send-alerts' => array('LowStockAlertController', 'checkAndSendAlerts'),
    '/inventory/test-smtp-connection' => array('LowStockAlertController', 'testSMTPConnection'),
    '/inventory/send-test-email' => array('LowStockAlertController', 'sendTestEmail'),

    '/production'    => array('ProductionController', 'index'),
    '/production/add' => array('ProductionController', 'add'),
    '/production/edit' => array('ProductionController', 'edit'),
    '/production/delete' => array('ProductionController', 'delete'),
    '/production/getDetail' => array('ProductionController', 'getDetail'),
    '/production/getMenuIngredients' => array('ProductionController', 'getMenuIngredients'),
    '/production/updateSold' => array('ProductionController', 'updateSold'),
    '/production/updateWastage' => array('ProductionController', 'updateWastage'),
    '/production/getInventoryDetail' => array('ProductionController', 'getInventoryDetail'),
    '/production/exportExcel' => array('ProductionController', 'exportExcel'),

    '/users'        => array('UserController', 'index'),
    '/users/show'   => array('UserController', 'getDetail'),
    '/users/add'    => array('UserController', 'add'),
    '/users/edit'   => array('UserController', 'edit'),
    '/users/delete' => array('UserController', 'delete'),
    '/users/getAll' => array('UserController', 'getAll'),
    '/users/test-email' => array('UserController', 'testEmail'),
    '/users/debug-email-config' => array('UserController', 'debugEmailConfig'),

    '/menu'         => array('MenuController', 'index'),
    '/menu/add'     => array('MenuController', 'add'),
    '/menu/edit'    => array('MenuController', 'edit'),
    '/menu/delete'  => array('MenuController', 'delete'),
        '/menu/getMenus' => array('MenuController', 'getMenus'),
        '/menu/getDetail' => array('MenuController', 'getDetail'),
        '/menu/print-barcode' => array('MenuController', 'printBarcode'),

    // Updated Purchase Orders routes
    '/purchase_order' => array('PurchaseOrderController', 'index'),
    '/purchase_order/add' => array('PurchaseOrderController', 'add'),
    '/purchase_order/delete' => array('PurchaseOrderController', 'delete'),
    '/purchase_order/get' => array('PurchaseOrderController', 'get'),
    '/purchase_order/edit' => array('PurchaseOrderController', 'edit'),

    // Backup routes
    '/backup' => array('BackupController', 'index'),
    '/backup/download' => array('BackupController', 'download'),
    '/backup/upload' => array('BackupController', 'upload'),

    // Barcode routes
    '/barcode' => array('BarcodeController', 'index'),
    '/barcode/selection' => array('BarcodeController', 'selection'),
    '/barcode/physical-count' => array('BarcodeController', 'physicalCount'),
    '/barcode/production-actions' => array('BarcodeController', 'productionActions'),
    '/barcode/add-production' => array('BarcodeController', 'addProduction'),
    '/barcode/update-sold' => array('BarcodeController', 'updateSold'),
    '/barcode/update-wastage' => array('BarcodeController', 'updateWastage'),
    '/barcode/menu-actions' => array('BarcodeController', 'menuActions'),

);

session_start();

// Handle redirections for users who don't have access to dashboard
if (isset($_SESSION['user_type'])) {
    $request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if ($request === '/home') {
        if ($_SESSION['user_type'] === 'Inventory Staff') {
            header('Location: /inventory');
            exit;
        } elseif ($_SESSION['user_type'] === 'Cashier' || $_SESSION['user_type'] === 'Kitchen Staff') {
            header('Location: /production');
            exit;
        }
    }
}
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'User') {
    unset($routes['/users']);
    unset($routes['/backup']);
}

// Restrictions for Inventory Staff
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'Inventory Staff') {
    // Remove system/admin routes
    unset($routes['/home']);
    unset($routes['/users']);
    unset($routes['/users/show']);
    unset($routes['/users/add']);
    unset($routes['/users/edit']);
    unset($routes['/users/delete']);
    unset($routes['/users/getAll']);
    unset($routes['/users/test-email']);
    unset($routes['/users/debug-email-config']);
    unset($routes['/backup']);
    unset($routes['/backup/download']);
    unset($routes['/backup/upload']);
    // Remove non-inventory related routes
    unset($routes['/menu']);
    unset($routes['/menu/add']);
    unset($routes['/menu/edit']);
    unset($routes['/menu/delete']);
    unset($routes['/menu/getMenus']);
    unset($routes['/menu/getDetail']);
    unset($routes['/menu/print-barcode']);
    unset($routes['/production']);
    unset($routes['/production/add']);
    unset($routes['/production/edit']);
    unset($routes['/production/delete']);
    unset($routes['/production/getDetail']);
    unset($routes['/production/getMenuIngredients']);
    unset($routes['/production/updateSold']);
    unset($routes['/production/updateWastage']);
    unset($routes['/production/exportExcel']);
}

// Restrictions for Cashier
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'Cashier') {
    // Remove system/admin routes
    unset($routes['/home']);
    unset($routes['/users']);
    unset($routes['/users/show']);
    unset($routes['/users/add']);
    unset($routes['/users/edit']);
    unset($routes['/users/delete']);
    unset($routes['/users/getAll']);
    unset($routes['/users/test-email']);
    unset($routes['/users/debug-email-config']);
    unset($routes['/backup']);
    unset($routes['/backup/download']);
    unset($routes['/backup/upload']);
    // Remove non-cashier related routes
    unset($routes['/inventory']);
    unset($routes['/inventory/add']);
    unset($routes['/inventory/edit']);
    unset($routes['/inventory/delete']);
    unset($routes['/inventory/getDetail']);
    unset($routes['/inventory/getAll']);
    unset($routes['/inventory/print-barcode']);
    unset($routes['/inventory/physical-count']);
    unset($routes['/inventory/addCountEntry']);
    unset($routes['/inventory/getPendingEntries']);
    unset($routes['/inventory/deleteCountEntry']);
    unset($routes['/inventory/savePhysicalCount']);
    unset($routes['/inventory/physical-count-export']);
    unset($routes['/inventory/check-low-stock-db']);
    unset($routes['/inventory/check-and-send-alerts']);
    unset($routes['/inventory/low-stock-alerts']);
    unset($routes['/inventory/auto-update-alerts']);
    unset($routes['/inventory/check-low-stock-db']);
    unset($routes['/inventory/check-and-send-alerts']);
    unset($routes['/inventory/test-smtp-connection']);
    unset($routes['/inventory/send-test-email']);
    unset($routes['/purchase_order']);
    unset($routes['/purchase_order/add']);
    unset($routes['/purchase_order/delete']);
    unset($routes['/purchase_order/get']);
    unset($routes['/purchase_order/edit']);
}

// Restrictions for Kitchen Staff (same as Cashier)
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'Kitchen Staff') {
    // Remove system/admin routes
    unset($routes['/home']);
    unset($routes['/users']);
    unset($routes['/users/show']);
    unset($routes['/users/add']);
    unset($routes['/users/edit']);
    unset($routes['/users/delete']);
    unset($routes['/users/getAll']);
    unset($routes['/users/test-email']);
    unset($routes['/users/debug-email-config']);
    unset($routes['/backup']);
    unset($routes['/backup/download']);
    unset($routes['/backup/upload']);
    // Remove non-kitchen-staff related routes
    unset($routes['/inventory']);
    unset($routes['/inventory/add']);
    unset($routes['/inventory/edit']);
    unset($routes['/inventory/delete']);
    unset($routes['/inventory/getDetail']);
    unset($routes['/inventory/getAll']);
    unset($routes['/inventory/print-barcode']);
    unset($routes['/inventory/physical-count']);
    unset($routes['/inventory/addCountEntry']);
    unset($routes['/inventory/getPendingEntries']);
    unset($routes['/inventory/deleteCountEntry']);
    unset($routes['/inventory/savePhysicalCount']);
    unset($routes['/inventory/physical-count-export']);
    unset($routes['/inventory/check-low-stock-db']);
    unset($routes['/inventory/check-and-send-alerts']);
    unset($routes['/inventory/low-stock-alerts']);
    unset($routes['/inventory/auto-update-alerts']);
    unset($routes['/inventory/check-low-stock-db']);
    unset($routes['/inventory/check-and-send-alerts']);
    unset($routes['/inventory/test-smtp-connection']);
    unset($routes['/inventory/send-test-email']);
    unset($routes['/purchase_order']);
    unset($routes['/purchase_order/add']);
    unset($routes['/purchase_order/delete']);
    unset($routes['/purchase_order/get']);
    unset($routes['/purchase_order/edit']);
}
