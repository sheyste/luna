
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUNA Dashboard</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css">
    <!-- DataTables Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.min.css">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f8f9fc;
        }
        #sidebar-wrapper {
            background: #000000ff;
            min-height: 100vh;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
        }
        .sidebar-heading {
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: 1px;
            color: #fff;
        }
        .list-group-item {
            border: none;
            margin-bottom: 6px;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
            font-size: 1.08rem;
            padding: 0.75rem 1rem;
            background: transparent;
            color: #fff;
        }
        .list-group-item:hover, .list-group-item.active {
            background: #2e59d9;
            color: #fff;
        }
        .navbar-brand {
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
        }
        main.container-fluid {
            padding-top: 32px;
        }
        @media (max-width: 991.98px) {
            #sidebar-wrapper {
                min-width: 70px;
                max-width: 70px;
                padding: 0;
            }
            .sidebar-heading, .list-group-item span {
                display: none;
            }
            .list-group-item i {
                margin-right: 0;
            }
        }
        .topbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(78,115,223,0.08);
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <nav class="border-end" id="sidebar-wrapper">
        <div class="sidebar-heading text-center py-4 px-3">
            <a class="navbar-brand" href="/home">
                <i class="fa-solid fa-cube"></i> <span>LUNA</span>
            </a>
        </div>
        <div class="list-group list-group-flush px-2">
            <a href="/home" class="list-group-item list-group-item-action">
                <i class="fa fa-home me-2"></i> <span>Home</span>
            </a>
            <a href="/users" class="list-group-item list-group-item-action">
                <i class="fa fa-users me-2"></i> <span>Users</span>
            </a>
            <a href="/inventory" class="list-group-item list-group-item-action">
                <i class="fa fa-boxes-stacked me-2"></i> <span>Inventory</span>
            </a>
            <a href="/menu" class="list-group-item list-group-item-action">
                <i class="fa fa-utensils me-2"></i> <span>Menu</span>
            </a>
            <a href="/production" class="list-group-item list-group-item-action">
                <i class="fa fa-industry me-2"></i> <span>Production</span>
            </a>
        </div>
    </nav>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper" class="flex-grow-1">
        <!-- Topbar -->
        <nav class="topbar d-flex align-items-center justify-content-between">
            <div>
                <button class="btn btn-link d-lg-none text-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar-wrapper" aria-controls="sidebar-wrapper">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUserMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="me-3 text-dark fw-semibold">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name'] ?? 'A'); ?>&background=4e73df&color=fff&size=32" class="rounded-circle" alt="User" width="32" height="32">
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUserMenu">
                    <li><a class="dropdown-item" href="/logout"><i class="fa fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>Logout</a></li>
                </ul>
            </div>
        </nav>
        <main role="main" class="container-fluid">
