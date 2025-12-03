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

  <!-- Latest Low Stock Alerts -->
  <div class="col-md-5 mt-4">
    <div class="card">
      <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
          <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alerts
        </h5>
          <a href="/inventory/low-stock-alerts" class="btn btn-sm btn-light">View All</a>
      </div>
      <div class="card-body" style="max-height: 400px; overflow-y: auto;">
        <?php if (!empty($latestLowStockAlerts)): ?>
          <div class="list-group list-group-flush">
            <?php foreach ($latestLowStockAlerts as $alert): ?>
              <div class="list-group-item border-0">
                <div class="row align-items-center">
                  <div class="col-8">
                    <div class="d-flex align-items-center">
                      <div class="me-3">
                        <?php if ($alert['current_quantity'] == 0): ?>
                          <i class="fas fa-times-circle text-danger fs-5"></i>
                        <?php elseif ($alert['current_quantity'] <= ($alert['current_quantity'] * 0.1)): ?>
                          <i class="fas fa-exclamation-triangle text-warning fs-5"></i>
                        <?php else: ?>
                          <i class="fas fa-exclamation-circle text-warning fs-5"></i>
                        <?php endif; ?>
                      </div>
                      <h6 class="mb-1 fw-bold text-truncate" >
                        <?= htmlspecialchars($alert['item_name']) ?>
                      </h6>
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <span class="<?= $alert['current_quantity'] == 0 ? 'text-danger fw-bold' : 'text-warning fw-bold' ?>">
                      <?= $alert['current_quantity'] ?> <?= htmlspecialchars($alert['unit']) ?>
                    </span>
                    <br>
                    <small class="text-muted">
                      <?php if ($alert['current_quantity'] == 0): ?>
                        Out of Stock
                      <?php else: ?>
                        Low Stock
                      <?php endif; ?>
                    </small>
                  </div>
                </div>
                <hr class="my-2">
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-4">
            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
            <h6 class="text-success mb-1">All Stocks Sufficient</h6>
            <p class="text-muted mb-0 small">No items need immediate attention</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>



  <!-- Inventory Pie Chart and Low Stock Alerts -->
  <div class="col-md-7 mt-4">
    <div class="card">
      <div class="card-header bg-secondary text-white">
        <h5 class="card-title mb-0">
          <i class="fas fa-chart-pie me-2"></i>Top 10 Inventory Value
        </h5>
      </div>
      <div class="card-body">
        <canvas id="inventoryPieChart" ></canvas>
      </div>
    </div>
  </div>

  <?php if ($_SESSION['user_type'] === 'Manager'): ?>
  <!-- User Layout: Production Chart + Stacked Efficiency Data -->
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

  <div class="col-md-6 mt-4">
    <!-- Recent Production -->
    <div class="card mb-4">
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

    <!-- Wastage Rate -->
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
              <i class="fas fa-check-circle text-success"></i> Within acceptable range (15%)
            <?php endif; ?>
          </small>
        </div>
      </div>
    </div>
  </div>

  <?php else: ?>
  <!-- Non-User Layout: Production Chart + Daily Cost vs Profit -->
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
  <?php endif; ?>


</div>

<!-- Production Efficiency Section - Only show for non-Manager types -->
<?php if ($_SESSION['user_type'] !== 'Manager'): ?>
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
              <i class="fas fa-check-circle text-success"></i> Within acceptable range (15%)
            <?php endif; ?>
          </small>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>





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

  <?php if ($_SESSION['user_type'] !== 'Manager'): ?>
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
  <?php endif; ?>

  // Inventory Pie Chart
  const inventoryCtx = document.getElementById('inventoryPieChart').getContext('2d');
  const inventoryData2 = <?= json_encode($inventoryData) ?>;
  new Chart(inventoryCtx, {
    type: 'pie',
    data: {
      labels: inventoryData2.map(item => `${item.name} (₱${parseFloat(item.total_price).toLocaleString()})`),
      datasets: [{
        data: inventoryData2.map(item => item.total_price),
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
          position: 'right',
          labels: {
            generateLabels: function(chart) {
              const data = chart.data;
              if (data.labels.length && data.datasets.length) {
                return data.labels.map((label, i) => {
                  const value = data.datasets[0].data[i];
                  const backgroundColor = data.datasets[0].backgroundColor[i];
                  // Split long labels into multiple lines
                  const wrappedLabel = label.length > 25 ? label.substring(0, 18) + '\n' + label.substring(18) : label;
                  return {
                    text: `${wrappedLabel}`,
                    fillStyle: backgroundColor,
                    strokeStyle: backgroundColor,
                    lineWidth: 1,
                    hidden: false,
                    index: i
                  };
                });
              }
              return [];
            },
            boxWidth: 12,
            padding: 8,
            font: {
              size: 11
            },
            usePointStyle: true,
            maxWidth: 150
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const item = inventoryData2[context.dataIndex];
              return `${item.name}: ₱${parseFloat(item.total_price).toLocaleString()}`;
            }
          }
        }
      }
    }
  });

</script>



<?php include_once __DIR__ . '/layout/footer.php' ?>
