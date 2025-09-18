<?php include_once __DIR__ . '/layout/header.php'; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item active" aria-current="page"></li>
        </ol>
    </nav>
</div>


<!-- Info Cards Row -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $totalItems ?? 0 ?></h3>
                <p>Items in Inventory</p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes-stacked"></i>
            </div>
            <a href="/inventory" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $lowStockItems ?? 0 ?>/<?= $overStockItems ?? 0 ?></h3>
                <p>Low / Over Stock</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="/inventory/low-stock-alerts" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>&#8369;<?= number_format($totalInventoryValue ?? 0, 2) ?></h3>
                <p>Inventory Value</p>
            </div>
            <div class="icon">
                <i class="fas fa-peso-sign"></i>
            </div>
            <a href="/inventory" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $pendingPurchaseOrders ?? 0 ?>/<?= $orderedPurchaseOrders ?? 0 ?></h3>
                <p>Pending / Ordered PO's</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice"></i>
            </div>
            <a href="/purchase_order" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>


<div class="row">

  <!-- Inventory Pie Chart and Low Stock Alerts -->
  <div class="col-md-4 mt-4">
    <div class="card">
      <div class="card-header bg-secondary text-white">
        <h5 class="card-title mb-0">
          <i class="fas fa-chart-pie me-2"></i>Inventory Items Distribution
        </h5>
      </div>
      <div class="card-body">
        <canvas id="inventoryPieChart" ></canvas>
      </div>
    </div>
  </div>
  
  <!-- Latest Low Stock Alerts -->
  <div class="col-md-4 mt-4">
    <div class="card">
      <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
          <i class="fas fa-exclamation-triangle me-2"></i>Latest Low Stock Alerts
        </h5>
        <a href="/inventory/low-stock-alerts" class="btn btn-sm btn-light">View All</a>
      </div>
      <div class="card-body">
        <?php if (!empty($latestLowStockAlerts)): ?>
          <div class="list-group">
            <?php foreach ($latestLowStockAlerts as $alert): ?>
              <div class="list-group-item">
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
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-muted text-center">No low stock alerts</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Ingredient Availability Alerts -->
  <div class="col-md-4 mt-4">
    <div class="card">
      <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
          <i class="fas fa-exclamation-triangle me-2"></i>Ingredient Alerts
        </h5>
        <a href="/production" class="btn btn-sm btn-light">View All</a>
      </div>
      <div class="card-body">
        <?php if (!empty($ingredientAlerts)): ?>
          <div class="list-group list-group-flush">
            <?php foreach ($ingredientAlerts as $alert): ?>
              <div class="list-group-item px-0">
                <div class="d-flex justify-content-between align-items-center">
                  <strong class="text-truncate" style="max-width: 150px;"><?= htmlspecialchars($alert['menu_name']) ?></strong>
                  <span class="badge bg-danger">
                    <?= $alert['max_producible'] ?? 0 ?> left
                  </span>
                </div>
                <small class="text-muted">Can produce max <?= $alert['max_producible'] ?? 0 ?> units</small>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-3">
            <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
            <p class="text-muted mb-0">All ingredients sufficient</p>
          </div>
        <?php endif; ?>
        <div class="mt-2">
          <small class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Shows menu items that can produce fewer than 20 units before running out of ingredients
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- Production Chart -->
  <div class="col-md-6 mt-4">
    <div class="card">
      <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">
          <i class="fas fa-chart-line me-2"></i>Production and Sales
        </h5>
      </div>
      <div class="card-body">
        <canvas id="productionChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Daily Cost-to-Profit -->
  <div class="col-md-6 mt-4">
    <div class="card">
      <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">
          <i class="fas fa-coins me-2"></i>Daily Cost vs Profit
        </h5>
      </div>
      <div class="card-body">
        <canvas id="costProfitChart"></canvas>
      </div>
    </div>
  </div>


</div>

<!-- Production Efficiency Section -->
<div class="row mt-4">
  <!-- Today's Production Totals -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
          <i class="fas fa-industry me-2"></i>Recent Production
        </h5>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-4">
            <h4 class="text-primary"><?= $totalProducedRecent ?? 0 ?></h4>
            <small class="text-muted">Produced (7d)</small>
          </div>
          <div class="col-4">
            <h4 class="text-success"><?= $totalSoldRecent ?? 0 ?></h4>
            <small class="text-muted">Sold (7d)</small>
          </div>
          <div class="col-4">
            <h4 class="text-warning"><?= $totalWastageRecent ?? 0 ?></h4>
            <small class="text-muted">Wastage (7d)</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Wastage Percentage -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header bg-warning text-white">
        <h5 class="card-title mb-0">
          <i class="fas fa-chart-pie me-2"></i>Wastage Rate
        </h5>
      </div>
      <div class="card-body">
        <div class="row text-center mb-2">
          <div class="col-4">
            <h5 class="<?= ($wastagePercentageToday ?? 0) > 15 ? 'text-danger' : 'text-success' ?>">
              <?= $wastagePercentageToday ?? 0 ?>%
            </h5>
            <small class="text-muted">Today</small>
          </div>
          <div class="col-4">
            <h5 class="<?= ($wastagePercentageWeek ?? 0) > 15 ? 'text-danger' : 'text-warning' ?>">
              <?= $wastagePercentageWeek ?? 0 ?>%
            </h5>
            <small class="text-muted">7 Days</small>
          </div>
          <div class="col-4">
            <h5 class="<?= ($wastagePercentageMonth ?? 0) > 15 ? 'text-danger' : 'text-info' ?>">
              <?= $wastagePercentageMonth ?? 0 ?>%
            </h5>
            <small class="text-muted">30 Days</small>
          </div>
        </div>
        <div class="text-center">
          <small class="text-muted">
            <?php if (($wastagePercentageWeek ?? 0) > 15): ?>
              <i class="fas fa-exclamation-triangle text-danger"></i> High wastage rate this week
            <?php else: ?>
              <i class="fas fa-check-circle text-success"></i> Within acceptable range
            <?php endif; ?>
          </small>
        </div>
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
