<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUNA Dashboard</title>
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
    <style>
        :root {
            --sidebar-width: 240px;
            --sidebar-bg: #293145;
            --sidebar-link-color: #c3c7d5;
            --sidebar-link-hover-bg: #333c54;
            --sidebar-link-active-bg: #1f2639;
            --sidebar-link-active-border: #3498db;
            --topbar-height: 60px;
            --page-bg: #f3f4f6;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: var(--page-bg);
            font-size: 0.9rem;
            overflow-x: hidden;
        }

        .page-wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        #sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            overflow-y: auto;
            overflow-x: hidden;
            background: var(--sidebar-bg);
            color: #fff;
            transition: margin-left 0.35s ease-in-out;
            box-shadow: 0 0 25px rgba(0,0,0,0.1);
            z-index: 1030;
        }

        .sidebar-header {
            padding: 1rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid #333c54;
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-header .navbar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .side-menu {
            padding: 1rem 0;
            list-style: none;
            margin: 0;
        }

        .side-menu .nav-item a {
            color: var(--sidebar-link-color);
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            font-weight: 500;
            border-left: 4px solid transparent;
        }

        .side-menu .nav-item a:hover {
            background: var(--sidebar-link-hover-bg);
            color: #fff;
        }

        .side-menu .nav-item.active > a {
            background: var(--sidebar-link-active-bg);
            color: #fff;
            border-left-color: var(--sidebar-link-active-border);
        }

        .side-menu .nav-item i {
            width: 25px;
            text-align: center;
            font-size: 1.1rem;
            margin-right: 10px;
        }

        #content-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .header {
            height: var(--topbar-height);
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            flex-shrink: 0;
            z-index: 1020;
        }
        
        .sidebar-toggler {
            font-size: 1.2rem;
            color: #6c757d;
        }

        .navbar-toolbar {
            display: flex;
            align-items: center;
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .dropdown-toggle::after {
            display: none;
        }

        main {
            padding: 1.5rem;
            flex-grow: 1;
            overflow-y: auto;
        }

        /* Sidebar Toggled State */
        body.sidebar-toggled #sidebar {
            margin-left: calc(-1 * var(--sidebar-width));
        }
        
        @media (max-width: 991.98px) {
            #sidebar {
                position: fixed;
                height: 100%;
                margin-left: calc(-1 * var(--sidebar-width));
            }
            body.sidebar-toggled #sidebar {
                margin-left: 0;
            }
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1029; /* Below sidebar (1030) */
                display: none;
            }
            body.sidebar-toggled .sidebar-overlay {
                display: block;
            }
        }
    </style>
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
                <i class="fa-solid fa-cube me-2"></i>
                <span>LUNA</span>
            </a>
        </div>
        <ul class="side-menu list-unstyled">
            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/home') !== false) echo 'active'; ?>">
                <a href="/home"><i class="fa fa-home"></i><span>Dashboard</span></a>
            </li>

            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/inventory') !== false) echo 'active'; ?>">
                <a href="/inventory"><i class="fa fa-boxes-stacked"></i><span>Inventory</span></a>
            </li>
                        
            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/purchase_order') !== false) echo 'active'; ?>">
                <a href="/purchase_order"><i class="fa fa-file-invoice"></i><span>Purchase Orders</span></a>
            </li>
            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/menu') !== false) echo 'active'; ?>">
                <a href="/menu"><i class="fa fa-utensils"></i><span>Menu</span></a>
            </li>
            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/production') !== false) echo 'active'; ?>">
                <a href="/production"><i class="fa fa-industry"></i><span>Production</span></a>
            </li>
            <hr>
            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/users') !== false) echo 'active'; ?>">
                <a href="/users"><i class="fa fa-users"></i><span>Users</span></a>
            </li>
            <li class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/barcode') !== false) echo 'active'; ?>">
                <a href="/barcode"><i class="fa fa-barcode"></i><span>Barcode Scan</span></a>
            </li>

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