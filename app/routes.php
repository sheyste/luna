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
    '/inventory/load' => array('InventoryController', 'load'),
    '/inventory/show' => array('InventoryController', 'getDetail'),
    '/inventory/search' => array('InventoryController', 'search'),
    '/inventory/export' => array('InventoryController', 'export'),
    '/inventory/import' => array('InventoryController', 'import'),
    '/inventory/print' => array('InventoryController', 'print'),
    '/inventory/printable' => array('InventoryController', 'printable'),
    '/inventory/printable/view' => array('InventoryController', 'printableView'),
    '/inventory/printable/export' => array('InventoryController', 'printableExport'),
    '/inventory/printable/import' => array('InventoryController', 'printableImport'),
    '/inventory/printable/delete' => array('InventoryController', 'printableDelete'),       
    '/users'        => array('UserController', 'index'),
    '/users/load'   => array('UserController', 'load'),
    '/users/show'   => array('UserController', 'getDetail'),
    '/users/add'    => array('UserController', 'add'),
    '/users/edit'   => array('UserController', 'edit'),
    '/users/delete' => array('UserController', 'delete'),
);
