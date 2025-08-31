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
    <div class="col-xl-3 col-md-6 mb-2">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Items in Inventory</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalItems ?? 0 ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-boxes-stacked fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Alerts Card -->
    <div class="col-xl-3 col-md-6 mb-2">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="row">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Low Stock</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $lowStockItems ?? 0 ?></div>
                            </div>
                            <div class="col">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Overstock</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $overStockItems ?? 0 ?></div>
                            </div>
                        </div>
                        <?php if (($lowStockItems ?? 0) >= 1 || ($overStockItems ?? 0) >= 1): ?>
                        <div class="row">
                            <div class="col">
                                <div class="text-xs text-secondary">
                                    <small>Needs attention</small>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Inventory Value Card -->
    <div class="col-xl-3 col-md-6 mb-2">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Inventory Value</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">&#8369;<?= number_format($totalInventoryValue ?? 0, 2) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-peso-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Purchase Orders Card -->
    <div class="col-xl-3 col-md-6 mb-2">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="row">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending PO's</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pendingPurchaseOrders ?? 0 ?></div>
                            </div>
                            <div class="col">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Ordered PO's</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $orderedPurchaseOrders ?? 0 ?></div>
                            </div>
                        </div>
                        <?php if (!empty($arrivingToday) && $arrivingToday > 0): ?>
                        <div class="row">
                            <div class="col">
                                <div class="text-xs text-secondary">
                                    <small><?= $arrivingToday ?> arriving today</small>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
  <!-- Production Chart -->
  <div class="col-md-6 mt-4">
    <div class="card">
      <div class="card-header">Production and Sales</div>
      <div class="card-body">
        <canvas id="productionChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Daily Cost-to-Profit -->
  <div class="col-md-6 mt-4">
    <div class="card">
      <div class="card-header">Daily Cost vs Profit</div>
      <div class="card-body">
        <canvas id="costProfitChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Inventory Pie Chart and Low Stock Alerts -->
  <div class="col-md-6 mt-4">
    <div class="card">
      <div class="card-header">Inventory Items Distribution</div>
      <div class="card-body">
        <canvas id="inventoryPieChart" ></canvas>
      </div>
    </div>
  </div>
  
  <!-- Latest Low Stock Alerts -->
  <div class="col-md-6 mt-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Latest Low Stock Alerts</span>
        <a href="/inventory/low-stock-alerts" class="btn btn-sm btn-primary">View All</a>
      </div>
      <div class="card-body">
        <?php if (!empty($latestLowStockAlerts)): ?>
          <div class="list-group">
            <?php foreach ($latestLowStockAlerts as $alert): ?>
              <a href="/inventory/low-stock-alerts" class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between">
                  <strong><?= htmlspecialchars($alert['item_name']) ?></strong>
                  <span><?= $alert['current_quantity'] ?> <?= htmlspecialchars($alert['unit']) ?></span>
                </div>
                <div class="d-flex justify-content-between">
                  <small class="text-muted"><?= htmlspecialchars(isset($alert['alert_date']) ? date('F j, Y g:i A', strtotime($alert['alert_date'])):'') ?></small>
                  <small>
                    <?php if ($alert['resolved'] == 1): ?>
                      <span class="badge bg-success">Resolved</span>
                    <?php else: ?>
                      <span class="badge bg-warning text-dark">Pending</span>
                    <?php endif; ?>
                  </small>
                </div>

              </a>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-muted text-center">No low stock alerts</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>


<!-- Content Row -->
<div class="row mt-3">

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
                <i class="fa fa-file-invoice fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Purchase Orders</h5>
                <p class="card-text text-muted">Manage Purchase Orders.</p>
                <a href="/purchase_order" class="btn btn-primary mt-auto">Go to Users</a>
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
      }, {
        label: 'Sold',
        data: <?= json_encode(array_column($productionData, 'total_sold')) ?>,
        borderColor: '#1cc88a',
        backgroundColor: 'rgba(28, 200, 138, 0.1)',
        fill: true
      }, {
        label: 'Wastage',
        data: <?= json_encode(array_column($productionData, 'total_wastage')) ?>,
        borderColor: '#e74a3b',
        backgroundColor: 'rgba(231, 74, 59, 0.1)',
        fill: true
      }]
    }
  });

  // Daily Cost vs Profit
  const costProfitCtx = document.getElementById('costProfitChart').getContext('2d');
  new Chart(costProfitCtx, {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_column($costProfitData, 'date')) ?>,
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

  // Inventory Pie Chart
  const inventoryCtx = document.getElementById('inventoryPieChart').getContext('2d');
  new Chart(inventoryCtx, {
    type: 'pie',
    data: {
      labels: <?= json_encode(array_column($inventoryData, 'name')) ?>,
      datasets: [{
        data: <?= json_encode(array_column($inventoryData, 'quantity')) ?>,
        backgroundColor: [
          '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
          '#858796', '#5a5c69', '#fd7e14', '#20c997', '#6f42c1'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'none'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return context.label + ': ' + context.parsed;
            }
          }
        }
      }
    }
  });

</script>


<?php include_once __DIR__ . '/layout/footer.php' ?>
