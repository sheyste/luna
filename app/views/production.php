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
        z-index: 10;
        max-width: calc(100% - 2rem);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-title {
        margin-right: 5rem;
        word-wrap: break-word;
        hyphens: auto;
        line-height: 1.3;
    }

    /* Responsive adjustments for medium and small screens */
    @media (max-width: 900px) {
        .price-tag {
            position: relative;
            top: auto;
            right: auto;
            margin-bottom: 0.5rem;
            display: inline-block;
            max-width: none;
            white-space: normal;
            overflow: visible;
            text-overflow: visible;
        }

        .card-title {
            margin-right: 0;
        }

        .card-body {
            position: relative;
        }
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

    .react-style-card-header .btn-close {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 0.5rem 0.5rem;
        z-index: 1;
    }

    /* Modern Modal Styles */
    .modern-modal .modal-dialog {
        max-width: 800px;
        margin: 1.75rem auto;
    }

    .modern-modal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    }

    .modern-modal .modal-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 1.5rem 2rem;
        border: none;
        position: relative;
    }

    .modern-modal .modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    /* Green theme for Add Modal */
    .modern-modal.add-modal .modal-header {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    }

    .modern-modal.add-modal .btn-primary {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .modern-modal.add-modal .btn-primary:hover {
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }

    .modern-modal.add-modal .form-control:focus,
    .modern-modal.add-modal .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .modern-modal .modal-title {
        font-weight: 600;
        font-size: 1.25rem;
        position: relative;
        z-index: 1;
        margin: 0;
    }

    .modern-modal .btn-close {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        position: relative;
        z-index: 1;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .modern-modal .btn-close:hover {
        opacity: 1;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .modern-modal .btn-close::after {
        content: "";
    }

    .modern-modal .btn-close i {
        color: white;
        font-size: 1.2rem;
    }

    .modern-modal .modal-body {
        padding: 2rem;
        background: white;
    }

    .modern-modal .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-modal .form-control,
    .modern-modal .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .modern-modal .form-control:focus,
    .modern-modal .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        background: white;
        transform: translateY(-1px);
    }

    .modern-modal .form-control:hover,
    .modern-modal .form-select:hover {
        border-color: #ced4da;
        background: white;
    }

    .modern-modal .mb-3 {
        margin-bottom: 1.5rem !important;
    }

    .modern-modal .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .modern-modal .btn {
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .modern-modal .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .modern-modal .btn:hover::before {
        left: 100%;
    }

    .modern-modal .btn-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
    }

    .modern-modal .btn-primary:hover {
        background: linear-gradient(135deg, #224abe 0%, #4e73df 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
        color: white;
    }

    .modern-modal .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .modern-modal .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }

    /* Mobile Responsiveness */
    @media (max-width: 576px) {
        .modern-modal .modal-dialog {
            margin: 0.5rem;
            max-width: none;
        }

        .modern-modal .modal-header {
            padding: 1rem 1.5rem;
        }

        .modern-modal .modal-body {
            padding: 1.5rem;
        }

        .modern-modal .modal-footer {
            padding: 1rem 1.5rem;
            flex-direction: column;
        }

        .modern-modal .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .modern-modal .form-control,
        .modern-modal .form-select {
            padding: 0.625rem 0.875rem;
            font-size: 0.9rem;
        }
    }

    /* Animation for modal appearance */
    .modern-modal.fade .modal-dialog {
        transform: scale(0.9) translateY(-20px);
        transition: transform 0.3s ease-out;
    }

    .modern-modal.show .modal-dialog {
        transform: scale(1) translateY(0);
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
</div>

<!-- Main Content Card -->
<div class=" mb-4">
    <div class="card-header py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-md-between">
        <!-- Filter Dropdown and Search Bar -->
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-3 mb-md-0">
            <!-- Date Filter and Custom Date Range -->
            <div class="d-flex flex-column gap-2">
                <!-- Date Filter Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dateFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-calendar me-2"></i>Filter by Date: <span id="currentFilter">Today</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dateFilterDropdown">
                        <li><a class="dropdown-item" href="#" data-filter="all">All</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="today">Today</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="week">This Week</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="month">This Month</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="year">This Year</a></li>
                    </ul>
                </div>
                <!-- Custom Date Range Inputs -->
                <div id="customDateRange" class="d-flex flex-column flex-sm-row gap-2">
                    <input type="date" id="startDate" class="form-control form-control-sm">
                    <div class="d-flex justify-content-center">
                        <span>to</span>
                    </div>
                    <input type="date" id="endDate" class="form-control form-control-sm">
                    <button id="applyCustomFilter" class="btn btn-primary btn-sm w-100 w-sm-auto">Apply</button>
                </div>
            </div>
            <!-- Search Bar on top in mobile, left in desktop -->
            <div class="input-group" style="max-width: 350px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); border-radius: 8px; transition: all 0.2s ease;">
                <span class="input-group-text" style="border: none; background: transparent;"><i class="fa fa-search"></i></span>
                <input type="text" id="productionSearch" class="form-control" placeholder="Search..." style="border: none; box-shadow: none;">
                <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="border-radius: 0 8px 8px 0; border: none;">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <!-- Buttons on the bottom in mobile, right in desktop -->
        <div class="d-flex flex-wrap gap-2">
            <?php if ($_SESSION['user_type'] !== 'User'): ?>
            <button class="btn btn-success btn-sm" id="exportExcelBtn">
                <i class="fa fa-file-excel-o me-1"></i> Export to Excel
            </button>
            <?php endif; ?>
            <?php if ($_SESSION['user_type'] !== 'Cashier' && $_SESSION['user_type'] !== 'User'): ?>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addProductionModal">
                <i class="fa fa-plus me-1"></i> Add Production
            </button>
            <?php endif; ?>
            <?php if ($_SESSION['user_type'] !== 'Kitchen Staff' && $_SESSION['user_type'] !== 'User'): ?>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateSoldModal">
                <i class="fa fa-edit me-1"></i> Update Sold
            </button>
            <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#updateWastageModal">
                <i class="fa fa-exclamation-triangle me-1"></i> Update Wastage
            </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="">
        <div class="row" id="production-container">
            <?php if (!empty($combinedItems)): ?>
                <?php foreach ($combinedItems as $item): ?>
                    <div class="col-lg-3 col-md-3 mb-4" data-created-at="<?= htmlspecialchars($item['created_at']) ?>" data-menu-id="<?= htmlspecialchars($item['menu_id']) ?>">
                        <div class="card menu-card h-100 shadow-sm border-0 rounded-3" data-menu-id="<?= htmlspecialchars($item['menu_id']) ?>" data-menu-name="<?= htmlspecialchars($item['menu_name']) ?>" style="cursor: pointer;">
                            <div class="card-body">
                                <div class="price-tag">Available: <?= htmlspecialchars($item['quantity_available']) ?></div>
                                <h5 class="card-title"><?= htmlspecialchars($item['menu_name']) ?></h5>
                                <p class="card-text text-muted">Barcode: <?= htmlspecialchars($item['barcode'] ?? '') ?></p>
                                <div class="mt-3">
                                    <p class="mb-1"><strong>Price:</strong> &#8369;<?= htmlspecialchars(number_format($item['price'] ?? 0, 2)) ?></p>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="produced-qty"><strong>Produced:</strong> <?= htmlspecialchars($item['quantity_produced']) ?></span>
                                        <span class="total-cost"><strong>Cost:</strong> &#8369;<?= htmlspecialchars(number_format($item['total_cost'] ?? 0, 2)) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="sold-qty"><strong>Sold:</strong> <?= htmlspecialchars($item['quantity_sold']) ?></span>
                                        <span class="total-sales"><strong>Sales:</strong> &#8369;<?= htmlspecialchars(number_format($item['total_sales'] ?? 0, 2)) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="wastage-qty"><strong>Wastage:</strong> <?= htmlspecialchars($item['total_wastage']) ?></span>
                                        <span class="waste-cost"><strong>Waste Cost:</strong> &#8369;<?= htmlspecialchars(number_format($item['total_waste_cost'] ?? 0, 2)) ?></span>
                                    </div>

                                    <p class="mb-1 profit"><strong>Profit:</strong> &#8369;<?= htmlspecialchars(number_format($item['profit'] ?? 0, 2)) ?></p>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 d-flex justify-content-end gap-2">
                                <?php if ($_SESSION['user_type'] === 'Admin' || $_SESSION['user_type'] === 'Manager'): ?>
                                <button class="btn btn-outline-danger btn-sm delete-btn" data-ids="<?= htmlspecialchars(implode(',', $item['original_ids'])) ?>" data-menu-name="<?= htmlspecialchars($item['menu_name']) ?>">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                                <?php endif; ?>
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
<div class="modal fade modern-modal" id="updateSoldModal" tabindex="-1" aria-labelledby="updateSoldModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form class="modal-content" method="post" action="/production/updateSold">

      <!-- Header -->
      <div class="modal-header">
        <h4 class="modal-title fw-bold" id="updateSoldModalLabel">
          <i class="bi bi-bag-check-fill me-2"></i>Update Sold Quantities
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="sold-ingredients-container">
            <!-- Sold item rows will be added here by JS -->
        </div>
        <button type="button" class="btn btn-primary btn-sm" id="add-sold-item-btn">
            <i class="fa fa-plus"></i> Add Item
        </button>
        <div class="d-flex justify-content-end mt-3">
            <span class="fw-bold">Total Sales: ₱<span id="sold-total-sales">0.00</span></span>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="sold-submit-btn" disabled>
          <i class="bi bi-save-fill me-1"></i>Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Sold Item Row Template -->
<div id="sold-item-row-template" style="display: none;">
    <div class="row ingredient-row mb-3 align-items-center g-2">
        <div class="col-12 col-md-4">
            <select class="form-select sold-menu-select" name="sold_menu" required>
                <option value="" selected disabled>Select Menu Item</option>
                <?php if (!empty($combinedItems)): ?>
                  <?php foreach ($combinedItems as $item): ?>
                    <?php if ($item['quantity_produced'] > 0): ?>
                      <option value="<?= $item['menu_id'] ?>" data-available="<?= $item['quantity_available'] ?>" data-price="<?= $item['price'] ?? 0 ?>">
                        <?= htmlspecialchars($item['menu_name']) ?>
                      </option>
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <input type="number" class="form-control sold-quantity" name="sold_quantity" placeholder="Qty" min="0" step="1" required>
        </div>
        <div class="col-6 col-md-2 text-center text-md-start">
            <small class="available-qty-span fw-bold text-muted">Available: 0</small>
        </div>
        <div class="col-4 col-md-2 text-center text-md-start">
            <span class="sold-value-span fw-bold">₱0.00</span>
        </div>
        <div class="col-2 col-md-1 text-end">
            <button type="button" class="btn btn-danger btn-sm remove-sold-row"><i class="fa fa-trash"></i></button>
        </div>
    </div>
</div>



<!-- Add Production Modal -->
<div class="modal fade modern-modal add-modal" id="addProductionModal" tabindex="-1" aria-labelledby="addProductionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="/production/add" id="addProductionForm">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductionModalLabel">Add Production Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
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
<div class="modal fade modern-modal" id="updateWastageModal" tabindex="-1" aria-labelledby="updateWastageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form class="modal-content" method="post" action="/production/updateWastage">

      <!-- Header -->
      <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);">
        <h4 class="modal-title fw-bold" id="updateWastageModalLabel">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>Update Wastage Quantities
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="wastage-ingredients-container">
            <!-- Wastage item rows will be added here by JS -->
        </div>
        <button type="button" class="btn btn-warning btn-sm" id="add-wastage-item-btn">
            <i class="fa fa-plus"></i> Add Item
        </button>
        <div class="d-flex justify-content-end mt-3">
            <span class="fw-bold">Total Waste Cost: ₱<span id="wastage-total-cost">0.00</span></span>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" id="wastage-submit-btn" disabled>
          <i class="bi bi-save-fill me-1"></i>Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Wastage Item Row Template -->
<div id="wastage-item-row-template" style="display: none;">
    <div class="row ingredient-row mb-3 align-items-center g-2">
        <div class="col-12 col-md-4">
            <select class="form-select wastage-menu-select" name="wastage_menu" required>
                <option value="" selected disabled>Select Menu Item</option>
                <?php if (!empty($combinedItems)): ?>
                  <?php foreach ($combinedItems as $item): ?>
                    <?php if ($item['quantity_produced'] > 0): ?>
                      <option value="<?= $item['menu_id'] ?>" data-available="<?= $item['quantity_available'] ?>" data-unit-cost="<?= $item['unit_cost'] ?? 0 ?>">
                        <?= htmlspecialchars($item['menu_name']) ?>
                      </option>
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <input type="number" class="form-control wastage-quantity" name="wastage_quantity" placeholder="Qty" min="0" step="1" required>
        </div>
        <div class="col-6 col-md-2 text-center text-md-start">
            <small class="available-qty-span fw-bold text-muted">Available: 0</small>
        </div>
        <div class="col-4 col-md-2 text-center text-md-start">
            <span class="wastage-cost-span fw-bold">₱0.00</span>
        </div>
        <div class="col-2 col-md-1 text-end">
            <button type="button" class="btn btn-danger btn-sm remove-wastage-row"><i class="fa fa-trash"></i></button>
        </div>
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

<!-- Menu Details Modal -->
<div class="modal fade modern-modal" id="menuDetailsModal" tabindex="-1" aria-labelledby="menuDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <!-- Header -->
      <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
        <h5 class="modal-title fw-bold" id="menuDetailsModalLabel">
          <i class="bi bi-info-circle-fill me-2"></i>Menu Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <!-- Menu Info Card -->
        <div class="card mb-4">
          <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="bi bi-cup-straw me-2"></i>Menu Information</h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <p><strong>Name:</strong> <span id="modalMenuName"></span></p>
                <p><strong>Price:</strong> ₱<span id="modalMenuPrice"></span></p>
              </div>
              <div class="col-md-6">
                <p><strong>Max Producible:</strong> <span id="modalMaxProducible" class="badge bg-primary fs-6"></span></p>
                <p><strong>Limiting Factor:</strong> <span id="modalLimitingFactor"></span></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Ingredients List -->
        <div class="card">
          <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="bi bi-list-check me-2"></i>Ingredients & Stock Status</h6>
          </div>
          <div class="card-body">
            <div id="ingredientsContainer" class="row g-3">
              <!-- Ingredients will be loaded here -->
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Ingredient Row Template -->
<div id="ingredient-row-template" style="display: none;">
  <div class="col-md-6 col-lg-4">
    <div class="card h-100 border">
      <div class="card-body">
        <h6 class="card-title ingredient-name"></h6>
        <div class="ingredient-details">
          <p class="mb-1"><small><strong>Required per unit:</strong> <span class="required-qty"></span></small></p>
          <p class="mb-1"><small><strong>Available Stock:</strong> <span class="stock-qty"></span></small></p>
          <p class="mb-1"><small><strong>Producible:</strong> <span class="producible-qty"></span></small></p>
        </div>
        <div class="progress mt-2" style="height: 6px;">
          <div class="progress-bar progress-bar-striped ingredient-status" role="progressbar" style="width: 0%"></div>
        </div>
        <div class="mt-1 ingredient-status-text"></div>
      </div>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/layout/footer.php' ?>

<script>
var allItems = <?php echo json_encode($items); ?>;
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
                                url: '/production/getInventoryDetail?id=' + ingredient.inventory_id,
                                method: 'GET',
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success && response.data) {
                                        var inventoryItem = response.data;
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
                                url: '/production/getInventoryDetail?id=' + ingredient.inventory_id,
                                method: 'GET',
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success && response.data) {
                                        var inventoryItem = response.data;
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
    
    // Initialize calculations on modal show and add default row
    $('#updateSoldModal').on('show.bs.modal', function() {
        // Add one default row
        var template = $('#sold-item-row-template .ingredient-row').clone();
        $('#sold-ingredients-container').append(template);
        updateSoldSubmitButton();

        $('input[name^="sold["]').trigger('change');
    });

    $('#updateWastageModal').on('show.bs.modal', function() {
        // Add one default row
        var template = $('#wastage-item-row-template .ingredient-row').clone();
        $('#wastage-ingredients-container').append(template);
        updateWastageSubmitButton();

        $('input[name^="wastage["]').trigger('change');
    });
    
// Handle final confirmation from summary modal
$('#confirmProductionBtn').on('click', function() {
    $('#addProductionForm').submit();
});

// Search functionality for production items
$('#productionSearch').on('input', function() {
    var searchTerm = $(this).val().toLowerCase();

    $('#production-container .col-lg-3').each(function() {
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

    // --- Dropdown-style Update Sold Modal Functionality ---
    // Add sold item row
    $('#add-sold-item-btn').on('click', function() {
        var template = $('#sold-item-row-template .ingredient-row').clone();
        $('#sold-ingredients-container').append(template);
        updateSoldSubmitButton();
    });

    // Add wastage item row
    $('#add-wastage-item-btn').on('click', function() {
        var template = $('#wastage-item-row-template .ingredient-row').clone();
        $('#wastage-ingredients-container').append(template);
        updateWastageSubmitButton();
    });

    // Handle form submission for sold modal
    $('#updateSoldModal form').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData();
        var soldData = {};

        $('#sold-ingredients-container .ingredient-row').each(function() {
            var menuId = $(this).find('.sold-menu-select').val();
            var quantity = parseInt($(this).find('.sold-quantity').val()) || 0;

            if (menuId && quantity > 0) {
                soldData[menuId] = quantity;
            }
        });

        // Add sold data to form
        Object.keys(soldData).forEach(function(menuId) {
            formData.append('sold[' + menuId + ']', soldData[menuId]);
        });

        // Submit via AJAX
        $.ajax({
            url: '/production/updateSold',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#updateSoldModal').modal('hide');
                location.reload(); // Reload to show updated data
            },
            error: function(xhr, status, error) {
                alert('Error updating sold quantities: ' + error);
            }
        });
    });

    // Handle form submission for wastage modal
    $('#updateWastageModal form').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData();
        var wastageData = {};

        $('#wastage-ingredients-container .ingredient-row').each(function() {
            var menuId = $(this).find('.wastage-menu-select').val();
            var quantity = parseInt($(this).find('.wastage-quantity').val()) || 0;

            if (menuId && quantity > 0) {
                wastageData[menuId] = quantity;
            }
        });

        // Add wastage data to form
        Object.keys(wastageData).forEach(function(menuId) {
            formData.append('wastage[' + menuId + ']', wastageData[menuId]);
        });

        // Submit via AJAX
        $.ajax({
            url: '/production/updateWastage',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#updateWastageModal').modal('hide');
                location.reload(); // Reload to show updated data
            },
            error: function(xhr, status, error) {
                alert('Error updating wastage quantities: ' + error);
            }
        });
    });

    // Remove sold row
    $(document).on('click', '.remove-sold-row', function() {
        $(this).closest('.ingredient-row').remove();
        calculateSoldTotal();
        updateSoldSubmitButton();
    });

    // Remove wastage row
    $(document).on('click', '.remove-wastage-row', function() {
        $(this).closest('.ingredient-row').remove();
        calculateWastageTotal();
        updateWastageSubmitButton();
    });

    // Handle sold menu selection change
    $(document).on('change', '.sold-menu-select', function() {
        var $row = $(this).closest('.ingredient-row');
        var selectedOption = $(this).find('option:selected');
        var available = selectedOption.data('available') || 0;
        var price = selectedOption.data('price') || 0;

        // Update available quantity display
        $row.find('.available-qty-span').text('Available: ' + available);

        // Set max quantity to available
        $row.find('.sold-quantity').attr('max', available);

        // Store price for calculation
        $row.data('price', price);

        // Calculate current value
        var quantity = parseInt($row.find('.sold-quantity').val()) || 0;
        var value = (quantity * price).toFixed(2);
        $row.find('.sold-value-span').text('₱' + value);

        calculateSoldTotal();
        updateSoldSubmitButton();
    });

    // Handle wastage menu selection change
    $(document).on('change', '.wastage-menu-select', function() {
        var $row = $(this).closest('.ingredient-row');
        var selectedOption = $(this).find('option:selected');
        var available = selectedOption.data('available') || 0;
        var unitCost = selectedOption.data('unit-cost') || 0;

        // Update available quantity display
        $row.find('.available-qty-span').text('Available: ' + available);

        // Set max quantity to available
        $row.find('.wastage-quantity').attr('max', available);

        // Store unit cost for calculation
        $row.data('unit-cost', unitCost);

        // Calculate current cost
        var quantity = parseInt($row.find('.wastage-quantity').val()) || 0;
        var cost = (quantity * unitCost).toFixed(2);
        $row.find('.wastage-cost-span').text('₱' + cost);

        calculateWastageTotal();
        updateWastageSubmitButton();
    });

    // Handle sold quantity change
    $(document).on('change keyup', '.sold-quantity', function() {
        var $row = $(this).closest('.ingredient-row');
        var quantity = parseInt($(this).val()) || 0;
        var max = parseInt($(this).attr('max')) || 0;
        var price = $row.data('price') || 0;

        // Ensure quantity doesn't exceed available
        if (quantity > max) {
            quantity = max;
            $(this).val(quantity);
        }

        var value = (quantity * price).toFixed(2);
        $row.find('.sold-value-span').text('₱' + value);

        calculateSoldTotal();
        updateSoldSubmitButton();
    });

    // Handle wastage quantity change
    $(document).on('change keyup', '.wastage-quantity', function() {
        var $row = $(this).closest('.ingredient-row');
        var quantity = parseInt($(this).val()) || 0;
        var max = parseInt($(this).attr('max')) || 0;
        var unitCost = $row.data('unit-cost') || 0;

        // Ensure quantity doesn't exceed available
        if (quantity > max) {
            quantity = max;
            $(this).val(quantity);
        }

        var cost = (quantity * unitCost).toFixed(2);
        $row.find('.wastage-cost-span').text('₱' + cost);

        calculateWastageTotal();
        updateWastageSubmitButton();
    });

    // Calculate total sales
    function calculateSoldTotal() {
        var total = 0;
        $('#sold-ingredients-container .ingredient-row').each(function() {
            var valueText = $(this).find('.sold-value-span').text().replace('₱', '');
            total += parseFloat(valueText) || 0;
        });
        $('#sold-total-sales').text(total.toFixed(2));
    }

    // Calculate total waste cost
    function calculateWastageTotal() {
        var total = 0;
        $('#wastage-ingredients-container .ingredient-row').each(function() {
            var costText = $(this).find('.wastage-cost-span').text().replace('₱', '');
            total += parseFloat(costText) || 0;
        });
        $('#wastage-total-cost').text(total.toFixed(2));
    }

    // Update submit button state for sold modal
    function updateSoldSubmitButton() {
        var hasRows = $('#sold-ingredients-container .ingredient-row').length > 0;
        var hasValidSelections = false;

        if (hasRows) {
            $('#sold-ingredients-container .ingredient-row').each(function() {
                var menuSelected = $(this).find('.sold-menu-select').val() !== '';
                var quantity = parseInt($(this).find('.sold-quantity').val()) || 0;
                if (menuSelected && quantity > 0) {
                    hasValidSelections = true;
                    return false; // Break loop
                }
            });
        }

        $('#sold-submit-btn').prop('disabled', !hasValidSelections);
    }

    // Update submit button state for wastage modal
    function updateWastageSubmitButton() {
        var hasRows = $('#wastage-ingredients-container .ingredient-row').length > 0;
        var hasValidSelections = false;

        if (hasRows) {
            $('#wastage-ingredients-container .ingredient-row').each(function() {
                var menuSelected = $(this).find('.wastage-menu-select').val() !== '';
                var quantity = parseInt($(this).find('.wastage-quantity').val()) || 0;
                if (menuSelected && quantity > 0) {
                    hasValidSelections = true;
                    return false; // Break loop
                }
            });
        }

        $('#wastage-submit-btn').prop('disabled', !hasValidSelections);
    }

    // Reset modals when closed
    $('#updateSoldModal').on('hidden.bs.modal', function() {
        $('#sold-ingredients-container').empty();
        $('#sold-total-sales').text('0.00');
        $('#sold-submit-btn').prop('disabled', true);
    });

    $('#updateWastageModal').on('hidden.bs.modal', function() {
        $('#wastage-ingredients-container').empty();
        $('#wastage-total-cost').text('0.00');
        $('#wastage-submit-btn').prop('disabled', true);
    });

    // Date filter functionality
    $('a.dropdown-item[data-filter]').on('click', function(e) {
        e.preventDefault();
        var filter = $(this).data('filter');
        $('#currentFilter').text($(this).text());
        filterProductionCards(filter);
    });

    // Apply custom filter
    $('#applyCustomFilter').on('click', function() {
        var startDateStr = $('#startDate').val();
        var endDateStr = $('#endDate').val();
        if (startDateStr && endDateStr) {
            var startDate = new Date(startDateStr);
            var endDate = new Date(endDateStr);
            endDate.setHours(23, 59, 59, 999);
            filterProductionCards('custom', startDate, endDate);
        } else {
            alert('Please select both start and end dates.');
        }
    });

    function filterProductionCards(filter, customStartDate = null, customEndDate = null) {
        var now = new Date();
        var startDate = customStartDate;
        var endDate = customEndDate;

        if (!startDate || !endDate) {
            switch(filter) {
                case 'today':
                    startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                    endDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);
                    break;
                case 'week':
                    var dayOfWeek = now.getDay(); // 0 = Sunday, 1 = Monday, etc.
                    var monday = new Date(now);
                    monday.setDate(now.getDate() - dayOfWeek + 1); // Monday of this week
                    monday.setHours(0, 0, 0, 0);
                    var sunday = new Date(monday);
                    sunday.setDate(monday.getDate() + 6); // Sunday of this week
                    sunday.setHours(23, 59, 59, 999);
                    startDate = monday;
                    endDate = sunday;
                    break;
                case 'month':
                    startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                    endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
                    break;
                case 'year':
                    startDate = new Date(now.getFullYear(), 0, 1);
                    endDate = new Date(now.getFullYear(), 11, 31, 23, 59, 59);
                    break;
                case 'all':
                default:
                    startDate = null;
                    endDate = null;
                    break;
            }
        }

        // Group items by menu_id and calculate totals
        var grouped = {};
        allItems.forEach(function(item) {
            var menuId = item.menu_id;
            var itemDate = new Date(item.created_at);

            if (!startDate || !endDate || (itemDate >= startDate && itemDate <= endDate)) {
                if (!grouped[menuId]) {
                    grouped[menuId] = {
                        quantity_produced: 0,
                        total_cost: 0,
                        quantity_sold: 0,
                        total_sales: 0,
                        total_wastage: 0,
                        total_waste_cost: 0
                    };
                }
                grouped[menuId].quantity_produced += parseFloat(item.quantity_produced) || 0;
                grouped[menuId].total_cost += parseFloat(item.total_cost) || 0;
                grouped[menuId].quantity_sold += parseFloat(item.quantity_sold) || 0;
                grouped[menuId].total_sales += parseFloat(item.total_sales) || 0;
                grouped[menuId].total_wastage += parseFloat(item.wastage) || 0;
                grouped[menuId].total_waste_cost += parseFloat(item.waste_cost) || 0;
            }
        });

        // Update each card
        $('#production-container .col-lg-3').each(function() {
            var menuId = $(this).data('menu-id');
            var data = grouped[menuId] || {
                quantity_produced: 0,
                total_cost: 0,
                quantity_sold: 0,
                total_sales: 0,
                total_wastage: 0,
                total_waste_cost: 0
            };

            var available = data.quantity_produced - data.quantity_sold - data.total_wastage;
            var profit = data.total_sales - data.total_cost;

            // Update price tag
            $(this).find('.price-tag').text('Available: ' + available);

            // Update spans
            $(this).find('.produced-qty').html('<strong>Produced:</strong> ' + data.quantity_produced);
            $(this).find('.total-cost').html('<strong>Cost:</strong> &#8369;' + data.total_cost.toFixed(2));
            $(this).find('.sold-qty').html('<strong>Sold:</strong> ' + data.quantity_sold);
            $(this).find('.total-sales').html('<strong>Sales:</strong> &#8369;' + data.total_sales.toFixed(2));
            $(this).find('.wastage-qty').html('<strong>Wastage:</strong> ' + data.total_wastage);
            $(this).find('.waste-cost').html('<strong>Waste Cost:</strong> &#8369;' + data.total_waste_cost.toFixed(2));
            $(this).find('.profit').html('<strong>Profit:</strong> &#8369;' + profit.toFixed(2));
        });
    }

    // Apply default today filter on page load
    filterProductionCards('today');

    // Handle Export to Excel button click
    $('#exportExcelBtn').on('click', function() {
        window.location.href = '/production/exportExcel';
    });

    // Handle menu card click to show details modal
    $('#production-container').on('click', '.menu-card .card-body', function(e) {
        // Prevent event bubbling if clicking on delete button
        if ($(e.target).closest('.delete-btn').length > 0) {
            return;
        }

        var $card = $(this).closest('.menu-card');
        var menuId = $card.data('menu-id');
        var menuName = $card.data('menu-name');

        $('#menuDetailsModalLabel').text('Menu Details - ' + menuName);

        // Fetch menu details
        $.ajax({
            url: '/menu/getDetail?id=' + menuId,
            method: 'GET',
            dataType: 'json',
            success: function(menu) {
                if (menu) {
                    // Populate menu info
                    $('#modalMenuName').text(menu.name || 'N/A');
                    $('#modalMenuPrice').text(parseFloat(menu.price || 0).toFixed(2));

                    if (Array.isArray(menu.ingredients) && menu.ingredients.length > 0) {
                        var maxProducible = Infinity;
                        var limitingIngredient = '';

                        // Clear previous ingredients
                        $('#ingredientsContainer').empty();

                        // Use promise to handle multiple AJAX calls
                        var ingredientsProcessed = 0;
                        var totalIngredients = menu.ingredients.length;

                        menu.ingredients.forEach(function(ingredient) {
                            // Get ingredient details from inventory
                            $.ajax({
                                url: '/production/getInventoryDetail?id=' + ingredient.inventory_id,
                                method: 'GET',
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success && response.data) {
                                        var inventoryItem = response.data;
                                        var requiredQty = parseFloat(ingredient.quantity);
                                        var availableQty = parseFloat(inventoryItem.quantity || 0);
                                        var producible = Math.floor(availableQty / requiredQty);

                                        // Track limiting ingredient
                                        if (producible < maxProducible) {
                                            maxProducible = producible;
                                            limitingIngredient = inventoryItem.name;
                                        }

                                        // Create ingredient card
                                        var template = $('#ingredient-row-template .col-md-6').clone();

                                        template.find('.ingredient-name').text(inventoryItem.name || 'Unnamed Ingredient');
                                        template.find('.required-qty').text(requiredQty + ' ' + (inventoryItem.unit || ''));
                                        template.find('.stock-qty').text(availableQty + ' ' + (inventoryItem.unit || ''));

                                        // Style based on availability
                                        var statusClass = 'bg-success';
                                        var statusWidth = 100;
                                        var statusText = 'Sufficient';

                                        if (availableQty < requiredQty) {
                                            statusClass = 'bg-danger';
                                            statusWidth = 0;
                                            statusText = 'Insufficient';
                                            producible = 0;
                                        } else if (availableQty < requiredQty * 10) {
                                            statusClass = 'bg-warning';
                                            statusWidth = (availableQty / (requiredQty * 10)) * 100;
                                            statusText = 'Low Stock';
                                        }

                                        template.find('.producible-qty').text(producible);
                                        template.find('.ingredient-status').removeClass('bg-success bg-warning bg-danger').addClass(statusClass).css('width', statusWidth + '%');
                                        template.find('.ingredient-status-text').text(statusText);

                                        $('#ingredientsContainer').append(template);
                                    }
                                    ingredientsProcessed++;
                                    if (ingredientsProcessed === totalIngredients) {
                                        // All ingredients processed, update summary
                                        var maxDisplay = maxProducible === Infinity ? 'Unlimited' : maxProducible;
                                        $('#modalMaxProducible').text(maxDisplay);
                                        $('#modalLimitingFactor').text(limitingIngredient || 'N/A');

                                        // Show modal
                                        $('#menuDetailsModal').modal('show');
                                    }
                                },
                                error: function() {
                                    var template = $('#ingredient-row-template .col-md-6').clone();
                                    template.find('.ingredient-name').text('Error loading ingredient');
                                    template.find('.required-qty').text('Error');
                                    template.find('.stock-qty').text('Error');
                                    template.find('.producible-qty').text('0');
                                    $('#ingredientsContainer').append(template);

                                    ingredientsProcessed++;
                                    if (ingredientsProcessed === totalIngredients) {
                                        $('#modalMaxProducible').text('0');
                                        $('#modalLimitingFactor').text('Error loading data');
                                        $('#menuDetailsModal').modal('show');
                                    }
                                }
                            });
                        });
                    } else {
                        $('#ingredientsContainer').empty().append('<div class="col-12"><div class="alert alert-info">No ingredients found for this menu.</div></div>');
                        $('#modalMaxProducible').text('Unlimited');
                        $('#modalLimitingFactor').text('N/A');
                        $('#menuDetailsModal').modal('show');
                    }
                } else {
                    alert('Could not fetch menu details.');
                }
            },
            error: function() {
                alert('Error fetching menu details.');
            }
        });
    });
});

</script>
