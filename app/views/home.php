<?php include_once __DIR__ . '/layout/header.php'; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fa fa-users fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Users</h5>
                <p class="card-text text-muted">Manage application users and roles.</p>
                <a href="/users" class="btn btn-primary mt-auto">Go to Users</a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fa fa-boxes-stacked fa-3x text-info mb-3"></i>
                <h5 class="card-title">Inventory</h5>
                <p class="card-text text-muted">Manage stock levels and items.</p>
                <a href="/inventory" class="btn btn-info mt-auto">Go to Inventory</a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fa fa-utensils fa-3x text-warning mb-3"></i>
                <h5 class="card-title">Menu</h5>
                <p class="card-text text-muted">Manage available menu items.</p>
                <a href="/menu" class="btn btn-warning mt-auto">Go to Menu</a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fa fa-industry fa-3x text-success mb-3"></i>
                <h5 class="card-title">Production</h5>
                <p class="card-text text-muted">Track production and output.</p>
                <a href="/production" class="btn btn-success mt-auto">Go to Production</a>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/layout/footer.php' ?>
