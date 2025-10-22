<?php include_once __DIR__ . '/layout/header.php'; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Low Stock Alerts</h1>
</div>

<!-- Main Content Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Low Stock Alerts List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="alertsTable" width="100%" cellspacing="0">
                <thead class="table-dark">
                    <tr>
                        <th>Item Name</th>
                        <th>Current Quantity</th>
                        <th>Max Quantity</th>
                        <th>Unit</th>
                        <th>Alert Date</th>
                        <th>Status</th>
                        <th>Resolved</th>
                        <th>Sent Date</th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                  function getResolvedBadgeClass($resolved) {
                      return $resolved == 1 ? 'bg-success' : 'bg-secondary';
                  }
                  ?>
                  <?php if (!empty($alerts)): ?>
                      <?php foreach ($alerts as $alert): ?>
                          <?php
                              $quantity = (float)($alert['current_quantity'] ?? 0);
                              $max_quantity = (float)($alert['max_quantity'] ?? 0);
                              $percentage = ($max_quantity > 0) ? round(($quantity / $max_quantity) * 100, 2) : 0;
                              $statusClass = '';
                              
                              // Set row color based on resolved status first
                              if ($alert['resolved'] == 1) {
                                  // Resolved alerts get primary color
                                  $statusClass = 'table-primary';
                              } else {
                                  // Non-resolved alerts get color based on status
                                  switch ($alert['status']) {
                                      case 'pending':
                                          $statusClass = 'table-warning';
                                          break;
                                      case 'sent':
                                          $statusClass = 'table-info';
                                          break;
                                      case 'resolved':
                                          $statusClass = 'table-success';
                                          break;
                                  }
                              }
                          ?>
                          <tr class="<?= $statusClass ?>">
                              <td data-label="Item Name"><?= htmlspecialchars($alert['item_name'] ?? '') ?></td>
                              <td data-label="Current Quantity"><?= htmlspecialchars($alert['current_quantity'] ?? '') ?></td>
                              <td data-label="Max Quantity"><?= htmlspecialchars($alert['max_quantity'] ?? '') ?></td>
                              <td data-label="Unit"><?= htmlspecialchars($alert['unit'] ?? '') ?></td>
                              <td data-label="Alert Date"><?= htmlspecialchars(isset($alert['alert_date']) ? date('F j, Y g:i A', strtotime($alert['alert_date'])):'') ?></td>
                              <td data-label="Status"><?= htmlspecialchars(ucfirst($alert['status'] ?? '')) ?></td>
                              <td data-label="Resolved"><span class="badge <?= getResolvedBadgeClass($alert['resolved']) ?>"><?= $alert['resolved'] == 1 ? 'Yes' : 'No' ?></span></td>
                              <td data-label="Sent Date"><?= htmlspecialchars(isset($alert['sent_date']) && $alert['sent_date'] ? date('F j, Y g:i A', strtotime($alert['sent_date'])):'') ?></td>
                          </tr>
                      <?php endforeach; ?>

                  <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Responsive table for mobile */
    @media (max-width: 767px) {
        #alertsTable thead {
            display: none;
        }

        #alertsTable, #alertsTable tbody, #alertsTable tr, #alertsTable td {
            display: block;
            width: 100%;
        }

        #alertsTable tr {
            margin-bottom: 1rem;
            border: 1px solid #ddd;
        }

        #alertsTable td {
            text-align: right;
            padding-left: 50%;
            position: relative;
            border: none;
            border-bottom: 1px solid #eee;
        }

        #alertsTable td:last-of-type {
            border-bottom: 0;
        }

        #alertsTable td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            width: 45%;
            font-weight: bold;
            text-align: left;
        }
    }
</style>
<?php include_once __DIR__ . '/layout/footer.php' ?>

<script>
  $(document).ready(function() {
    $('#alertsTable').DataTable({
      "pageLength": 50, // Set entries per page to 50
      "lengthChange": true, // Disable the entries per page dropdown
      "language": {
        "emptyTable": "No low stock alerts found"
      },
      "order": [[ 4, "desc" ]], // Sort by alert date descending by default
      "ordering": false // Disable sorting functionality
    });
  });

</script>
