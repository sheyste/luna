<?php include_once __DIR__ . '/layout/header.php'; ?>

<style>
    .menu-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border-radius: 12px;
        overflow: hidden;
    }

    .price-tag {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background-color: #1200b1ff;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 0.25rem;
        font-weight: bold;
    }

    /* Modern React-style input controls */
    .react-style-input-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }

    .react-style-input-group:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        background: #fff;
    }

    .react-style-input {
        width: 120px;
        text-align: center;
        font-size: 1.25rem;
        font-weight: 600;
        padding: 12px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.2s ease;
        background: white;
    }

    .react-style-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }

    .react-style-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: none;
        font-size: 1.25rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .react-style-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        box-shadow: none;
    }

    .react-style-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .react-style-btn:active:not(:disabled) {
        transform: translateY(0);
    }

    .react-style-btn-decrement {
        background: #ef4444;
        color: white;
    }

    .react-style-btn-increment {
        background: #10b981;
        color: white;
    }

    .react-style-btn-decrement:hover:not(:disabled) {
        background: #dc2626;
    }

    .react-style-btn-increment:hover:not(:disabled) {
        background: #059669;
    }

    .react-style-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }

    .react-style-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .react-style-card-header {
        background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .react-style-card-body {
        padding: 24px;
    }

    .react-style-modal-footer {
        padding: 20px;
        background: #f8fafc;
        border-radius: 0 0 12px 12px;
        display: flex;
        justify-content: space-between;
    }

    .react-style-btn-lg {
        padding: 12px 24px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .react-style-btn-primary {
        background: #3b82f6;
        color: white;
        border: none;
    }

    .react-style-btn-primary:hover {
        background: #2563eb;
    }

    .react-style-btn-secondary {
        background: #64748b;
        color: white;
        border: none;
    }

    .react-style-btn-secondary:hover {
        background: #475569;
    }

    .react-style-btn-warning {
        background: #f59e0b;
        color: white;
        border: none;
    }

    .react-style-btn-warning:hover {
        background: #d97706;
    }

    .react-style-menu-name {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 16px;
        text-align: center;
        color: #1e293b;
    }

    .react-style-quantity-display {
        font-size: 1.1rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 16px;
        text-align: center;
    }

    .react-style-quantity-value {
        color: #3b82f6;
        font-weight: 700;
    }

    .react-style-summary-card {
        background: #f1f5f9;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .react-style-summary-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .react-style-summary-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #3b82f6;
    }

    .react-style-alert {
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .react-style-alert-warning {
        background: #fef3c7;
        border: 1px solid #fde68a;
        color: #92400e;
    }

    .react-style-alert-info {
        background: #dbeafe;
        border: 1px solid #bfdbfe;
        color: #1e40af;
    }

    .react-style-icon {
        font-size: 1.5rem;
    }
</style>

<?php
$combinedItems = [];
if (!empty($items)) {
    foreach ($items as $item) {
        $menuId = $item['menu_id'];
        if (!isset($combinedItems[$menuId])) {
            $combinedItems[$menuId] = $item;
            $combinedItems[$menuId]['original_ids'] = [$item['id']];
            $combinedItems[$menuId]['total_wastage'] = $item['wastage'] ?? 0;
            $combinedItems[$menuId]['total_waste_cost'] = $item['waste_cost'] ?? 0;
        } else {
            $combinedItems[$menuId]['quantity_produced'] += $item['quantity_produced'];
            $combinedItems[$menuId]['quantity_available'] += $item['quantity_available'];
            $combinedItems[$menuId]['quantity_sold'] += $item['quantity_sold'];
            $combinedItems[$menuId]['total_wastage'] += $item['wastage'] ?? 0;
            // Recalculate combined costs and sales
            $combinedItems[$menuId]['total_cost'] += $item['total_cost'];
            $combinedItems[$menuId]['total_sales'] += $item['total_sales'];
            $combinedItems[$menuId]['total_waste_cost'] += $item['waste_cost'] ?? 0;
            // Recalculate profit as sales - cost only
            $combinedItems[$menuId]['profit'] = $combinedItems[$menuId]['total_sales'] - $combinedItems[$menuId]['total_cost'];
            $combinedItems[$menuId]['unit_cost'] = $combinedItems[$menuId]['total_cost'] / $combinedItems[$menuId]['quantity_produced'];
            $combinedItems[$menuId]['original_ids'][] = $item['id'];
            if (strtotime($item['created_at']) > strtotime($combinedItems[$menuId]['created_at'])) {
                $combinedItems[$menuId]['created_at'] = $item['created_at'];
            }
        }
    }
}
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Production</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Production</li>
        </ol>
    </nav>
</div>

<!-- Main Content Card -->
<div class=" mb-4">
    <div class="card-header py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-md-between">
        <!-- Search Bar on top in mobile, left in desktop -->
        <div class="input-group mb-3 mb-md-0" style="max-width: 350px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); border-radius: 8px; transition: all 0.2s ease;">
            <span class="input-group-text" style="border: none; background: transparent;"><i class="fa fa-search"></i></span>
            <input type="text" id="productionSearch" class="form-control" placeholder="Search..." style="border: none; box-shadow: none;">
            <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="border-radius: 0 8px 8px 0; border: none;">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <!-- Buttons on the bottom in mobile, right in desktop -->
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addProductionModal">
                <i class="fa fa-plus me-1"></i> Add Production
            </button>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateSoldModal">
                <i class="fa fa-edit me-1"></i> Update Sold
            </button>
            <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#updateWastageModal">
                <i class="fa fa-exclamation-triangle me-1"></i> Update Wastage
            </button>
        </div>
    </div>

    <div class="">
        <div class="row" id="production-container">
            <?php if (!empty($combinedItems)): ?>
                <?php foreach ($combinedItems as $item): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card menu-card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-body">
                                <div class="price-tag">Available: <?= htmlspecialchars($item['quantity_available']) ?></div>
                                <h5 class="card-title"><?= htmlspecialchars($item['menu_name']) ?></h5>
                                <p class="card-text text-muted">Barcode: <?= htmlspecialchars($item['barcode'] ?? '') ?></p>
                                <div class="mt-3">
                                    <p class="mb-1"><strong>Price:</strong> &#8369;<?= htmlspecialchars(number_format($item['price'] ?? 0, 2)) ?></p>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><strong>Produced:</strong> <?= htmlspecialchars($item['quantity_produced']) ?></span>
                                        <span><strong>Cost:</strong> &#8369;<?= htmlspecialchars(number_format($item['total_cost'] ?? 0, 2)) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><strong>Sold:</strong> <?= htmlspecialchars($item['quantity_sold']) ?></span>
                                        <span><strong>Sales:</strong> &#8369;<?= htmlspecialchars(number_format($item['total_sales'] ?? 0, 2)) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><strong>Wastage:</strong> <?= htmlspecialchars($item['total_wastage']) ?></span>
                                        <span><strong>Waste Cost:</strong> &#8369;<?= htmlspecialchars(number_format($item['total_waste_cost'] ?? 0, 2)) ?></span>
                                    </div>
                                    
                                    <p class="mb-1"><strong>Profit:</strong> &#8369;<?= htmlspecialchars(number_format($item['profit'] ?? 0, 2)) ?></p>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 d-flex justify-content-end gap-2">
                                <button class="btn btn-outline-danger btn-sm delete-btn" data-ids="<?= htmlspecialchars(implode(',', $item['original_ids'])) ?>" data-menu-name="<?= htmlspecialchars($item['menu_name']) ?>">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No production items found.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- Update Sold Modal -->
<div class="modal fade" id="updateSoldModal" tabindex="-1" aria-labelledby="updateSoldModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <form class="modal-content" method="post" action="/production/updateSold">
      
      <!-- Header -->
      <div class="react-style-card-header">
        <i class="bi bi-bag-check-fill react-style-icon"></i>
        <h4 class="modal-title fw-bold" id="updateSoldModalLabel">
          Update Sold Quantities
        </h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">

          
          <!-- Search Bar -->
          <div class="row mb-3">
              <div class="col-12">
                  <input type="text" class="form-control" id="soldSearch" placeholder="Search menu items...">
              </div>
          </div>
          
          <div class="row g-4">
          <?php if (!empty($combinedItems)): ?>
            <?php foreach ($combinedItems as $item): ?>
              <?php if ($item['quantity_produced'] > 0): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="react-style-card">
                    <div class="react-style-card-body">
                      <!-- Menu Name -->
                      <h5 class="react-style-menu-name"><?= htmlspecialchars($item['menu_name']) ?></h5>
                      
                      <!-- Available Quantity Display -->
                      <div class="react-style-quantity-display">
                        Available: <span class="react-style-quantity-value"><?= htmlspecialchars($item['quantity_available']) ?></span>
                      </div>
                      
                      <!-- Input Controls -->
                      <div class="react-style-input-group">
                        <button type="button"
                                class="react-style-btn react-style-btn-decrement decrement"
                                data-target="sold-<?= $item['menu_id'] ?>"
                                aria-label="Decrease quantity">
                          <i class="bi bi-dash-lg"></i>
                        </button>

                        <input type="number"
                               class="react-style-input"
                               name="sold[<?= $item['menu_id'] ?>]"
                               id="sold-<?= $item['menu_id'] ?>"
                               value="0"
                               min="0"
                               max="<?= $item['quantity_available'] ?>"
                               aria-label="Quantity sold for <?= htmlspecialchars($item['menu_name']) ?>">

                        <button type="button"
                                class="react-style-btn react-style-btn-increment increment"
                                data-target="sold-<?= $item['menu_id'] ?>"
                                aria-label="Increase quantity">
                          <i class="bi bi-plus-lg"></i>
                        </button>
                      </div>
                      
                      <!-- Summary Card -->
                      <div class="react-style-summary-card">
                        <div class="react-style-summary-title">Sales Value</div>
                        <div class="react-style-summary-value">&#8369;<span id="sales-value-<?= $item['menu_id'] ?>">0.00</span></div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="react-style-alert react-style-alert-warning">
                <i class="bi bi-exclamation-circle react-style-icon"></i>
                <div>
                  <strong>No production items found.</strong> Please add production items before updating sold quantities.
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Footer -->
      <div class="react-style-modal-footer">
        <button type="button" class="react-style-btn-lg react-style-btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle"></i> Cancel
        </button>
        <button type="submit" class="react-style-btn-lg react-style-btn-primary">
          <i class="bi bi-save-fill"></i> Save Changes
        </button>
      </div>
    </form>
  </div>
</div>



<!-- Add Production Modal -->
<div class="modal fade" id="addProductionModal" tabindex="-1" aria-labelledby="addProductionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="/production/add" id="addProductionForm">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductionModalLabel">Add Production Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
          <label for="menuId" class="form-label">Menu</label>
          <select class="form-select" id="menuId" name="menu_id" required>
            <option value="">-- Select Menu --</option>
          </select>
        </div>

        <!-- Menu Details -->
        <div id="menuDetails" class="mb-3" style="display: none;">
            <div class="card">
                <div class="card-header fw-bold">
                    Menu Details
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Price:</strong> <span id="menuPrice"></span></p>
                    <p class="mb-1"><strong>Ingredients:</strong></p>
                    <ul id="menuIngredients" class="list-unstyled ps-3 mb-0"></ul>
                </div>
            </div>
        </div>

        <div class="mb-3">
          <label for="barcode" class="form-label">Barcode</label>
          <input type="text" class="form-control" id="barcode" name="barcode" readonly>
        </div>
        <div class="mb-3">
          <label for="quantityProduced" class="form-label">Quantity</label>
          <input type="number" class="form-control" id="quantityProduced" name="quantity_produced" min="0" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="showSummaryBtn" class="btn btn-success">Add Item</button>
      </div>
    </form>
  </div>
</div>



<!-- Delete Production Modal -->
<div class="modal fade" id="deleteProductionModal" tabindex="-1" aria-labelledby="deleteProductionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="/production/delete">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteProductionModalLabel">Delete Production Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="deleteItemIds" name="ids">
        <p>Are you sure you want to delete all production entries for <strong id="deleteMenuName"></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>

<!-- Update Wastage Modal -->
<div class="modal fade" id="updateWastageModal" tabindex="-1" aria-labelledby="updateWastageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <form class="modal-content" method="post" action="/production/updateWastage">
      
      <!-- Header -->
      <div class="react-style-card-header" style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);">
        <i class="bi bi-exclamation-triangle-fill react-style-icon"></i>
        <h4 class="modal-title fw-bold" id="updateWastageModalLabel">
          Update Wastage Quantities
        </h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
          
          <!-- Search Bar -->
          <div class="row mb-3">
              <div class="col-12">
                  <input type="text" class="form-control" id="wastageSearch" placeholder="Search menu items...">
              </div>
          </div>
          
          <div class="row g-4">
          <?php if (!empty($combinedItems)): ?>
            <?php foreach ($combinedItems as $item): ?>
              <?php if ($item['quantity_produced'] > 0): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="react-style-card">
                    <div class="react-style-card-body">
                      <!-- Menu Name -->
                      <h5 class="react-style-menu-name"><?= htmlspecialchars($item['menu_name']) ?></h5>
                      
                      <!-- Available Quantity Display -->
                      <div class="react-style-quantity-display">
                        Available: <span class="react-style-quantity-value"><?= htmlspecialchars($item['quantity_available']) ?></span>
                      </div>
                      
                      <!-- Input Controls -->
                      <div class="react-style-input-group">
                        <button type="button"
                                class="react-style-btn react-style-btn-decrement decrement"
                                data-target="wastage-<?= $item['menu_id'] ?>"
                                aria-label="Decrease wastage">
                          <i class="bi bi-dash-lg"></i>
                        </button>

                        <input type="number"
                               class="react-style-input"
                               name="wastage[<?= $item['menu_id'] ?>]"
                               id="wastage-<?= $item['menu_id'] ?>"
                               value="0"
                               min="0"
                               max="<?= $item['quantity_available'] ?>"
                               aria-label="Quantity wasted for <?= htmlspecialchars($item['menu_name']) ?>">

                        <button type="button"
                                class="react-style-btn react-style-btn-increment increment"
                                data-target="wastage-<?= $item['menu_id'] ?>"
                                aria-label="Increase wastage">
                          <i class="bi bi-plus-lg"></i>
                        </button>
                      </div>
                      
                      <!-- Summary Card -->
                      <div class="react-style-summary-card">
                        <div class="react-style-summary-title">Waste Cost</div>
                        <div class="react-style-summary-value">&#8369;<span id="waste-cost-<?= $item['menu_id'] ?>">0.00</span></div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="react-style-alert react-style-alert-warning">
                <i class="bi bi-exclamation-circle react-style-icon"></i>
                <div>
                  <strong>No production items found.</strong> Please add production items before updating wastage quantities.
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Footer -->
      <div class="react-style-modal-footer">
        <button type="button" class="react-style-btn-lg react-style-btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle"></i> Cancel
        </button>
        <button type="submit" class="react-style-btn-lg react-style-btn-warning">
          <i class="bi bi-save-fill"></i> Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Production Summary Modal -->
<div class="modal fade" id="productionSummaryModal" tabindex="-1" aria-labelledby="productionSummaryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productionSummaryModalLabel">Confirm Production</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title fw-bold" id="summaryMenuName"></h5>
                <p class="card-text mb-0" id="summaryMenuPrice"></p>
                <p class="card-text fw-bold mt-2" id="summaryTotalPrice"></p>
            </div>
        </div>

        <p>This will deduct the following ingredients from inventory:</p>
        <ul id="summaryIngredientsList" class="list-group">
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmProductionBtn">Confirm & Add</button>
      </div>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/layout/footer.php' ?>

<script>
$(document).ready(function() {
    // No table to initialize - using cards instead
    var menuData = []; // To hold menu data for auto-populating fields

    // Load menus into dropdowns
    function loadMenus(selectId, selectedValue = null) {
        $.ajax({
            url: '/menu/getMenus',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                menuData = data; // Store menu data
                var $select = $(selectId);
                $select.empty().append('<option value="">-- Select Menu --</option>');
                data.forEach(function(menu) {
                    var selected = (menu.id == selectedValue) ? 'selected' : '';
                    $select.append('<option value="' + menu.id + '" ' + selected + '>' + menu.name + '</option>');
                });
            }
        });
    }

    // Populate when opening Add Production Modal
    $('#addProductionModal').on('show.bs.modal', function() {
        loadMenus('#menuId');
        // Reset form fields
        $('#addProductionModal form')[0].reset();
        $('#menuDetails').hide();
    });

    // Auto-populate barcode and other details when a menu is selected in the Add modal
    $('#menuId').on('change', function() {
        var selectedMenuId = $(this).val();
        var $menuDetails = $('#menuDetails');

        if (selectedMenuId) {
            $.ajax({
                url: '/menu/getDetail?id=' + selectedMenuId,
                method: 'GET',
                dataType: 'json',
                success: function(menu) {
                    if (menu) {
                        $('#barcode').val(menu.barcode || '');

                        // Populate details
                        $('#menuPrice').text(menu.price ? '₱' + parseFloat(menu.price).toFixed(2) : 'N/A');
                        var $ingredientsList = $('#menuIngredients').empty();

                        if (Array.isArray(menu.ingredients) && menu.ingredients.length > 0) {
                            menu.ingredients.forEach(function(ingredient) {
                                $.ajax({
                                    url: '/inventory/getDetail?id=' + ingredient.inventory_id,
                                    method: 'GET',
                                    dataType: 'json',
                                    success: function(inventoryItem) {
                                        if (inventoryItem) {
                                            var displayQuantity = Number.isInteger(ingredient.quantity) ? ingredient.quantity : parseFloat(ingredient.quantity).toFixed(3).replace(/\.?0+$/, "");
                                            $ingredientsList.append('<li>' + (inventoryItem.name || 'Unnamed Ingredient') + ' - ' + displayQuantity + ' ' + (inventoryItem.unit || '') + '</li>');
                                        }
                                    },
                                    error: function() {
                                        $ingredientsList.append('<li>Error loading ingredient details.</li>');
                                    }
                                });
                            });
                        } else {
                            $ingredientsList.append('<li>No ingredients listed for this menu.</li>');
                        }

                        $menuDetails.show();
                    } else {
                        $('#barcode').val('');
                        $menuDetails.hide();
                    }
                },
                error: function() {
                    alert('Could not fetch menu details.');
                    $('#barcode').val('');
                    $menuDetails.hide();
                }
            });
        } else {
            $('#barcode').val('');
            $menuDetails.hide();
        }
    });

    // Handle click on "Add Item" to show summary
    $('#addProductionModal').on('click', '#showSummaryBtn', function(e) {
        e.preventDefault();

        var form = $('#addProductionForm');
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        var menuId = $('#menuId').val();
        var quantityProduced = parseFloat($('#quantityProduced').val());

        $.ajax({
            url: '/menu/getDetail?id=' + menuId,
            method: 'GET',
            dataType: 'json',
            success: function(menu) {
                if (menu) {
                    // Populate menu details in summary modal
                    $('#summaryMenuName').text(menu.name || 'Selected Menu');
                    $('#summaryMenuPrice').text('Price: ' + (menu.price ? '₱' + parseFloat(menu.price).toFixed(2) : 'N/A'));

                    // Calculate and display total price
                    if (menu.price && quantityProduced > 0) {
                        var totalPrice = parseFloat(menu.price) * quantityProduced;
                        $('#summaryTotalPrice').text('Total Price: ₱' + totalPrice.toFixed(2));
                    } else {
                        $('#summaryTotalPrice').text('');
                    }

                    var $summaryList = $('#summaryIngredientsList').empty();
                    var allStockSufficient = true;

                    if (Array.isArray(menu.ingredients) && menu.ingredients.length > 0) {
                        var promises = [];

                        menu.ingredients.forEach(function(ingredient) {
                            var totalQuantity = ingredient.quantity * quantityProduced;
                            var deferred = $.Deferred();
                            promises.push(deferred.promise());

                            $.ajax({
                                url: '/inventory/getDetail?id=' + ingredient.inventory_id,
                                method: 'GET',
                                dataType: 'json',
                                success: function(inventoryItem) {
                                    if (inventoryItem) {
                                        var availableStock = parseFloat(inventoryItem.quantity);
                                        var hasEnoughStock = availableStock >= totalQuantity;
                                        if (!hasEnoughStock) {
                                            allStockSufficient = false;
                                        }

                                        var displayQuantity = Number.isInteger(totalQuantity) ? totalQuantity : totalQuantity.toFixed(3).replace(/\.?0+$/, "");
                                        var stockStatusHtml = `
                                            <span class="badge ${hasEnoughStock ? 'bg-success' : 'bg-danger'}">
                                                <i class="fa ${hasEnoughStock ? 'fa-check' : 'fa-times'} me-1"></i>
                                                Available: ${inventoryItem.quantity} ${inventoryItem.unit || ''}
                                            </span>
                                        `;

                                        $summaryList.append(`
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>${inventoryItem.name || 'Unnamed'}: <strong>${displayQuantity} ${inventoryItem.unit || ''}</strong> required</span>
                                                ${stockStatusHtml}
                                            </li>
                                        `);
                                    }
                                    deferred.resolve();
                                },
                                error: function() {
                                    $summaryList.append('<li class="list-group-item text-danger">Error loading ingredient details.</li>');
                                    allStockSufficient = false; // Consider error as insufficient stock
                                    deferred.resolve();
                                }
                            });
                        });

                        $.when.apply($, promises).done(function() {
                            var $confirmBtn = $('#confirmProductionBtn');
                            if (allStockSufficient) {
                                $confirmBtn.prop('disabled', false).removeClass('btn-danger').addClass('btn-primary').text('Confirm & Add');
                            } else {
                                $confirmBtn.prop('disabled', true).removeClass('btn-primary').addClass('btn-danger').text('Insufficient Stock');
                            }
                            $('#addProductionModal').modal('hide');
                            $('#productionSummaryModal').modal('show');
                        });
                    } else {
                        $summaryList.append('<li class="list-group-item">No ingredients will be deducted.</li>');
                        $('#confirmProductionBtn').prop('disabled', false).removeClass('btn-danger').addClass('btn-primary').text('Confirm & Add');
                        $('#addProductionModal').modal('hide');
                        $('#productionSummaryModal').modal('show');
                    }
                } else {
                    alert('Could not fetch menu details to calculate ingredients.');
                }
            },
            error: function() {
                alert('Could not fetch menu details.');
            }
        });
    });

    // Disable menu selector in Edit modal
    $('#editMenuId').prop('disabled', true);

    // Auto-populate barcode when a menu is selected in the Edit modal
    $('#editMenuId').on('change', function() {
        var selectedMenuId = $(this).val();
        if (selectedMenuId) {
            var selectedMenu = menuData.find(menu => menu.id == selectedMenuId);
            if (selectedMenu) {
                $('#editBarcode').val(selectedMenu.barcode || '');
            }
        } else {
            $('#editBarcode').val('');
        }
    });

    // Handle Edit button click
    $('#production-container').on('click', '.edit-btn', function() {
        var itemId = $(this).data('id');

        $.ajax({
            url: '/production/getDetail?id=' + itemId,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data) {
                    $('#editItemId').val(data.id);
                    $('#editBarcode').val(data.barcode);
                    $('#editQuantityProduced').val(data.quantity_produced);
                    $('#editQuantityAvailable').val(data.quantity_sold);

                    // Load menus and pre-select current
                    loadMenus('#editMenuId', data.menu_id);

                    $('#editProductionModal').modal('show');
                }
            },
            error: function() {
                alert('Could not fetch item details.');
            }
        });
    });

    // Handle Delete button click
    $('#production-container').on('click', '.delete-btn', function() {
        var itemIds = $(this).data('ids');
        var menuName = $(this).data('menu-name');
        $('#deleteItemIds').val(itemIds);
        $('#deleteMenuName').text(menuName);
        $('#deleteProductionModal').modal('show');
    });

    // --- Update Increment/Decrement for Sold and Wastage ---
    $(document).on('click', '.increment', function() {
        var targetId = $(this).data('target');
        var target = $('#' + targetId);
        var val = parseInt(target.val()) || 0;
        var max = parseInt(target.attr('max')) || Infinity;
        
        // Handle both sold and wastage increment
        if ((targetId && targetId.startsWith('sold-')) || (targetId && targetId.startsWith('wastage-'))) {
            if (val < max) {
                target.val(val + 1).trigger('change');
            }
        }
    });

    $(document).on('click', '.decrement', function() {
        var targetId = $(this).data('target');
        var target = $('#' + targetId);
        var val = parseInt(target.val()) || 0;
        
        // Handle both sold and wastage decrement
        if (((targetId && targetId.startsWith('sold-')) || (targetId && targetId.startsWith('wastage-'))) && val > 0) {
            target.val(val - 1).trigger('change');
        }
    });
    
    // Real-time calculation for sold quantities
    $(document).on('change keyup', 'input[name^="sold["]', function() {
        var menuId = $(this).attr('id').replace('sold-', '');
        var quantity = parseInt($(this).val()) || 0;
        var max = parseInt($(this).attr('max')) || 0;
        
        // Ensure quantity doesn't exceed available
        if (quantity > max) {
            quantity = max;
            $(this).val(quantity);
        }
        
        // Find the corresponding price in the combinedItems data
        var price = 0;
        <?php foreach ($combinedItems as $item): ?>
        if ('<?= $item['menu_id'] ?>' === menuId) {
            price = <?= $item['price'] ?? 0 ?>;
        }
        <?php endforeach; ?>
        
        var salesValue = (quantity * price).toFixed(2);
        $('#sales-value-' + menuId).text(salesValue);
    });
    
    // Real-time calculation for wastage quantities
    $(document).on('change keyup', 'input[name^="wastage["]', function() {
        var menuId = $(this).attr('id').replace('wastage-', '');
        var quantity = parseInt($(this).val()) || 0;
        var max = parseInt($(this).attr('max')) || 0;
        
        // Ensure quantity doesn't exceed available
        if (quantity > max) {
            quantity = max;
            $(this).val(quantity);
        }
        
        // Find the corresponding unit cost in the combinedItems data
        var unitCost = 0;
        <?php foreach ($combinedItems as $item): ?>
        if ('<?= $item['menu_id'] ?>' === menuId) {
            unitCost = <?= $item['unit_cost'] ?? 0 ?>;
        }
        <?php endforeach; ?>
        
        var wasteCost = (quantity * unitCost).toFixed(2);
        $('#waste-cost-' + menuId).text(wasteCost);
    });
    
    // Initialize calculations on modal show
    $('#updateSoldModal').on('show.bs.modal', function() {
        $('input[name^="sold["]').trigger('change');
    });
    
    $('#updateWastageModal').on('show.bs.modal', function() {
        $('input[name^="wastage["]').trigger('change');
    });
    
// Handle final confirmation from summary modal
$('#confirmProductionBtn').on('click', function() {
    $('#addProductionForm').submit();
});

// Search functionality for production items
$('#productionSearch').on('input', function() {
    var searchTerm = $(this).val().toLowerCase();
    
    $('#production-container .col-lg-4').each(function() {
        var menuName = $(this).find('.card-title').text().toLowerCase();
        var barcode = $(this).find('.card-text').text().toLowerCase();
        
        if (menuName.includes(searchTerm) || barcode.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

// Clear search functionality
$('#clearSearch').on('click', function() {
    $('#productionSearch').val('');
    $('#productionSearch').trigger('input');
});

// Search functionality for Update Sold modal
$('#soldSearch').on('input', function() {
    var searchTerm = $(this).val().toLowerCase();
    
    $('#updateSoldModal .col-md-6').each(function() {
        var menuName = $(this).find('.react-style-menu-name').text().toLowerCase();
        
        if (menuName.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

// Search functionality for Update Wastage modal
$('#wastageSearch').on('input', function() {
    var searchTerm = $(this).val().toLowerCase();
    
    $('#updateWastageModal .col-md-6').each(function() {
        var menuName = $(this).find('.react-style-menu-name').text().toLowerCase();
        
        if (menuName.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

// Clear search when modals are closed
$('#updateSoldModal, #updateWastageModal').on('hidden.bs.modal', function () {
    $(this).find('input[type="text"]').val('');
    $(this).find('.col-md-6').show();
});
});

</script>
