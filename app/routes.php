<?php
/**
 *  define routes with its controllers and actions 
 */
const routes = array(
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
    '/inventory/check-low-stock-db' => array('InventoryController', 'checkLowStockDb'),
    '/inventory/check-and-send-alerts' => array('InventoryController', 'checkAndSendAlerts'),
    '/inventory/low-stock-alerts' => array('LowStockAlertController', 'index'),
    '/inventory/auto-update-alerts' => array('LowStockAlertController', 'autoUpdateAlerts'),
    '/inventory/check-low-stock-db' => array('LowStockAlertController', 'checkLowStockDb'),
    '/inventory/check-and-send-alerts' => array('LowStockAlertController', 'checkAndSendAlerts'),

    '/production'    => array('ProductionController', 'index'),
    '/production/add' => array('ProductionController', 'add'),
    '/production/edit' => array('ProductionController', 'edit'),
    '/production/delete' => array('ProductionController', 'delete'),
    '/production/getDetail' => array('ProductionController', 'getDetail'),
    '/production/updateSold' => array('ProductionController', 'updateSold'),
    '/production/updateWastage' => array('ProductionController', 'updateWastage'),

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

    // Updated Purchase Orders routes
    '/purchase_order' => array('PurchaseOrderController', 'index'),
    '/purchase_order/add' => array('PurchaseOrderController', 'add'),
    '/purchase_order/delete' => array('PurchaseOrderController', 'delete'),
    '/purchase_order/get' => array('PurchaseOrderController', 'get'),
    '/purchase_order/edit' => array('PurchaseOrderController', 'edit'),

    // Backup routes
    '/backup' => array('BackupController', 'index'),
    '/backup/download' => array('BackupController', 'download'),
    '/backup/upload' => array('BackupController', 'upload')

);
