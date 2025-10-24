<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUNA Inventory Management System</title>
    <link rel="icon" type="image/x-icon" href="/assets/img/LUNA.png">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css">
    <!-- DataTables Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.min.css">
    <!-- DataTables Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- JsBarcode -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggler = document.getElementById('sidebar-toggler');
            const overlay = document.querySelector('.sidebar-overlay');
            const body = document.body;

            if (toggler) {
                toggler.addEventListener('click', function() {
                    body.classList.toggle('sidebar-toggled');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    body.classList.remove('sidebar-toggled');
                });
            }
        });
    </script>
</head>
<body>
<div class="page-wrapper">
    <div class="sidebar-overlay"></div>
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <a class="navbar-brand" href="/home">
                <img src="/assets/img/luna.svg" alt="LUNA" class="me-2" style="width: 20px; height: 20px;">
                <span>LUNA</span>
            </a>
        </div>
        <ul class="side-menu list-unstyled">
<?php if ($_SESSION['user_type'] !== 'Inventory Staff' && $_SESSION['user_type'] !== 'Cashier' && $_SESSION['user_type'] !== 'Kitchen Staff'): ?>
            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/home') !== false) echo 'active'; ?>">
                <a href="/home"><i class="fa fa-home"></i><span>Dashboard</span></a>
            </li>
<?php endif; ?>
<?php if ($_SESSION['user_type'] !== 'Owner'): ?>
            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/barcode') !== false) echo 'active'; ?>">
                <a href="/barcode"><i class="fa fa-barcode"></i><span>Barcode Scan</span></a>
            </li>
<?php endif; ?>
<?php if ($_SESSION['user_type'] !== 'Cashier' && $_SESSION['user_type'] !== 'Kitchen Staff'): ?>
            <li><h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1">
                    <span>Inventory</span>
                </h6></li>
            <li class="nav-item <?php if ($_SERVER['REQUEST_URI'] == '/inventory') echo 'active'; ?>">
                <a href="/inventory"><i class="fa fa-boxes-stacked"></i><span>Inventory</span></a>
            </li>
            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/inventory/physical-count') !== false) echo 'active'; ?>">
                <a href="/inventory/physical-count"><i class="fa fa-clipboard-list"></i><span>Physical Count</span></a>
            </li>
            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/purchase_order') !== false) echo 'active'; ?>">
                <a href="/purchase_order"><i class="fa fa-file-invoice"></i><span>Purchase Orders</span></a>
            </li>

            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/inventory/low-stock-alerts') !== false) echo 'active'; ?>">
                <a href="/inventory/low-stock-alerts"><i class="fa fa-exclamation-triangle"></i><span>Low Stock Alerts</span></a>
            </li>
<?php endif; ?>
<?php if ($_SESSION['user_type'] !== 'Inventory Staff'): ?>
            <li><h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1">
                    <span>Production</span>
                </h6></li>

            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/menu') !== false) echo 'active'; ?>">
                <a href="/menu"><i class="fa fa-utensils"></i><span>Menu</span></a>
            </li>
            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/production') !== false) echo 'active'; ?>">
                <a href="/production"><i class="fa fa-industry"></i><span>Production</span></a>
            </li>
<?php endif; ?>

<?php if ($_SESSION['user_type'] !== 'Owner' && $_SESSION['user_type'] !== 'Manager' && $_SESSION['user_type'] !== 'Inventory Staff' && $_SESSION['user_type'] !== 'Cashier' && $_SESSION['user_type'] !== 'Kitchen Staff'): ?>
            <li><h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1">
                    <span>System</span>
                </h6>
            </li>

            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/users') !== false) echo 'active'; ?>">
                <a href="/users"><i class="fa fa-users"></i><span>Users</span></a>
            </li>
            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/backup') !== false) echo 'active'; ?>">
                <a href="/backup"><i class="fa fa-download"></i><span>Backup & Restore</span></a>
            </li>
<?php endif; ?>
            

        </ul>
    </nav>

    <!-- /#sidebar -->

    <!-- Page Content -->
    <div id="content-wrapper">
        <!-- Topbar -->
        <header class="header d-flex justify-content-between align-items-center">
            <ul class="navbar-toolbar">
                <li>
                    <button class="btn btn-link sidebar-toggler" id="sidebar-toggler" type="button">
                        <i class="fa fa-bars"></i>
                    </button>
                </li>
            </ul>

            <ul class="navbar-toolbar">
                <li class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUserMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-3 d-none d-sm-inline text-dark fw-semibold">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name'] ?? 'A'); ?>&background=4e73df&color=fff&size=35" class="rounded-circle" alt="User" width="35" height="35">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUserMenu">
                        <li><a class="dropdown-item" href="/logout"><i class="fa fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </header>
        <!-- /#topbar -->
        <main role="main" class="container-fluid">


<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> <?= htmlspecialchars($_GET['error']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>
