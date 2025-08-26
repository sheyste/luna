<?php include_once __DIR__ . '/layout/header.php'; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item active" aria-current="page">Information</li>
        </ol>
    </nav>
</div>


<!-- Info Cards Row -->
<div class="row">

    <!-- Total Stocks in Inventory Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Stocks in Inventory</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalItems ?? 0 ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-boxes-stacked fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Items Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Low Stock Items</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $lowStockItems ?? 0 ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Inventory Value Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Inventory Value</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">&#8369;<?= number_format($totalInventoryValue ?? 0, 2) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-peso-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Produced Amount Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Production Available
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalProducedAmount ?? 0 ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-industry fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
  <!-- Production Chart -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">Production Forecast</div>
      <div class="card-body">
        <canvas id="productionChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Weekly Cost-to-Profit -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">Cost vs Profit</div>
      <div class="card-body">
        <canvas id="costProfitChart"></canvas>
      </div>
    </div>
  </div>
</div>


<!-- Content Row -->
<div class="row mt-3">
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



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Production
  const productionCtx = document.getElementById('productionChart').getContext('2d');
  new Chart(productionCtx, {
    type: 'line',
    data: {
      labels: <?= json_encode(array_column($productionData, 'date')) ?>,
      datasets: [{
        label: 'Produced',
        data: <?= json_encode(array_column($productionData, 'total_produced')) ?>,
        borderColor: '#4e73df',
        backgroundColor: 'rgba(78,115,223,0.1)',
        fill: true
      }]
    }
  });

  // Cost vs Profit
  const costProfitCtx = document.getElementById('costProfitChart').getContext('2d');
  new Chart(costProfitCtx, {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_column($costProfitData, 'week')) ?>,
      datasets: [
        {
          label: 'Cost',
          data: <?= json_encode(array_column($costProfitData, 'total_cost')) ?>,
          backgroundColor: '#e74a3b'
        },
        {
          label: 'Profit',
          data: <?= json_encode(array_column($costProfitData, 'total_profit')) ?>,
          backgroundColor: '#1cc88a'
        }
      ]
    }
  });


</script>


<?php include_once __DIR__ . '/layout/footer.php' ?>
