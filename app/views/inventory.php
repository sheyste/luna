<?php include_once __DIR__ . '/layout/header.php'; ?>

<style>
    /* Clickable row styling */
    .clickable-row {
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        position: relative; /* Needed for z-index to work */
    }

    .clickable-row:hover {
        background-color: #adb5bd !important; /* Darker background for more noticeable hover */
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.4); /* More pronounced shadow for depth */
        transform: translateY(-1px) scale(1.00); /* Move up slightly and scale for "pop" effect */
        z-index: 1; /* Bring to front on hover */
    }

    .clickable-row:hover td {
        background-color: transparent;
    }

    /* Prevent button clicks from triggering row click */
    .clickable-row td button {
        pointer-events: auto;
    }

    /* Actions column styling for all screen sizes */
    #inventoryTable td[data-label="Actions"] {
        display: flex;
        gap: 0.5rem; /* Add space between buttons */
        align-items: center;
    }

    /* Responsive table for mobile */
    @media (max-width: 767px) {
        #inventoryTable thead {
            display: none;
        }

        #inventoryTable, #inventoryTable tbody, #inventoryTable tr, #inventoryTable td {
            display: block;
            width: 100%;
        }

        #inventoryTable tr {
            margin-bottom: 1rem;
            border: 1px solid #ddd;
        }

        #inventoryTable td {
            text-align: right;
            padding-left: 50%;
            position: relative;
            border: none;
            border-bottom: 1px solid #eee;
        }

        /* Specific styling for the Actions column in mobile view */
        #inventoryTable td[data-label="Actions"] {
            text-align: left; /* Align buttons to the left */
            padding-left: 1rem; /* Adjust padding */
            flex-direction: column; /* Stack buttons vertically */
            gap: 0.5rem; /* Add space between buttons */
        }

        #inventoryTable td[data-label="Actions"] button {
            width: 100%; /* Make buttons take full width */
            text-align: center; /* Center text within buttons */
            font-size: 0.8rem; /* Slightly larger font size */
            padding: 0.6rem 0.5rem; /* Adjust padding for a bigger feel */
        }

        #inventoryTable td:last-of-type {
            border-bottom: 0;
        }

        #inventoryTable td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            width: 45%;
            font-weight: bold;
            text-align: left;
        }

        /* Hide the "Actions" label in mobile view */
        #inventoryTable td[data-label="Actions"]::before {
            content: "";
        }
    }

    /* Modern Edit Inventory Modal Styles */
    .modern-modal .modal-dialog {
        max-width: 600px;
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

    /* Green theme for Add Inventory Modal */
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

    /* Input group styling for barcode */
    .modern-modal .input-group {
        position: relative;
    }

    .modern-modal .input-group .btn {
        border-radius: 0 8px 8px 0;
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
    }

    .modern-modal .input-group .form-control {
        border-radius: 8px 0 0 8px;
        border-right: none;
    }

    .modern-modal .input-group .form-control:focus {
        border-right: 2px solid #4e73df;
    }

    /* Form text styling */
    .modern-modal .form-text {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
        color: #495057;
    }

    .dataTables_wrapper .dataTables_length select:focus,
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        background: white;
        outline: none;
    }

    .dataTables_wrapper .dataTables_length select:hover,
    .dataTables_wrapper .dataTables_filter input:hover {
        border-color: #ced4da;
        background: white;
    }

    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label {
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .dataTables_wrapper .dataTables_info {
        font-size: 0.875rem;
        color: #6c757d;
        padding-top: 1rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        padding: 0.375rem 0.75rem;
        margin: 0 0.125rem;
        background: white;
        color: #495057;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f8f9fa;
        border-color: #ced4da;
        color: #495057;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #4e73df;
        border-color: #4e73df;
        color: white;
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.3);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        background: #f8f9fa;
        border-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
    }

    /* Custom Excel Export Button Styling */
    .dt-buttons .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        transition: all 0.3s ease;
    }

    .dt-buttons .btn-success:hover {
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .dt-buttons .btn-success:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    /* DataTables Controls Layout */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }

    .dataTables_wrapper .dataTables_filter {
        text-align: right;
    }

    @media (max-width: 768px) {
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            text-align: left;
            margin-bottom: 0.5rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 100%;
            margin-top: 0.25rem;
        }

        .dt-buttons {
            margin-bottom: 1rem;
        }

        .dt-buttons .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Inventory</h1>
</div>

<!-- Main Content Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Inventory List</h6>
        <div class="d-flex gap-2">
            <?php if ($_SESSION['user_type'] !== 'User'): ?>
            <button class="btn btn-success btn-sm export-excel-btn" id="exportExcelBtn">
                <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel
            </button>
            <?php endif; ?>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addInventoryModal">
                <i class="bi bi-plus-circle me-1"></i> Add Item
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="inventoryTable" width="100%" cellspacing="0">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Stock Quantity</th>
                        <th>Max Quantity</th>
                        <th>Unit</th>
                        <th>Price (Per Unit)</th>
                        <th>Total Price</th>
                        <th>Purchase Date</th>
                        <th>Barcode</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                  <?php if (!empty($items)): ?>
                      <?php foreach ($items as $item): ?>
                          <?php
                              $quantity = (float)($item['quantity'] ?? 0);
                              $max_quantity = (float)($item['max_quantity'] ?? 0);
                              $price = (float)($item['price'] ?? 0);
                              $totalPrice = $quantity * $price;
                              $rowClass = '';
                              if ($max_quantity > 0) {
                                  $stockRatio = $quantity / $max_quantity;
                                  if ($stockRatio <= 0.2) {
                                      $rowClass = 'table-danger'; // Low stock
                                  } elseif ($stockRatio >= 1.1) {
                                      $rowClass = 'table-warning'; // Overstock
                                  }
                              }
                          ?>
                          <tr class="<?= $rowClass ?> clickable-row" data-id="<?= htmlspecialchars($item['id']) ?>">
                              <td data-label="Name"><?= htmlspecialchars($item['name'] ?? '') ?></td>
                              <td data-label="Category"><?= htmlspecialchars($item['category'] ?? '') ?></td>
                              <td data-label="Stock Quantity"><?= htmlspecialchars($item['quantity'] ?? '') ?></td>
                              <td data-label="Max Quantity"><?= htmlspecialchars($item['max_quantity'] ?? '') ?></td>
                              <td data-label="Unit"><?= htmlspecialchars($item['unit'] ?? '') ?></td>
                              <td data-label="Price (Per Unit)">&#8369;<?= htmlspecialchars(number_format($price, 2)) ?></td>
                              <td data-label="Total Price">&#8369;<?= htmlspecialchars(number_format($totalPrice, 2)) ?></td>
                              <td data-label="Purchase Date"><?= htmlspecialchars(isset($item['purchase_date']) ? date('F j, Y', strtotime($item['purchase_date'])):'') ?></td>
                              <td data-label="Barcode"><?= htmlspecialchars($item['barcode'] ?? '') ?></td>
                              <td data-label="Actions">
                                                                  <button class="btn btn-primary btn-sm print-btn" data-id="<?= htmlspecialchars($item['id']) ?>" data-barcode="<?= htmlspecialchars($item['barcode'] ?? '') ?>">
                                                                      <i class="bi bi-printer me-1"></i> Print
                                                                  </button>
                                                                  <?php if ($_SESSION['user_type'] === 'Admin'): ?>
                                                                  <button class="btn btn-danger btn-sm delete-btn" data-id="<?= htmlspecialchars($item['id']) ?>">
                                                                      <i class="bi bi-trash me-1"></i> Delete
                                                                  </button>
                                                                  <?php endif; ?>
                                                              </td>
                          </tr>
                      <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Inventory Modal -->
<div class="modal fade modern-modal add-modal" id="addInventoryModal" tabindex="-1" aria-labelledby="addInventoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="/inventory/add">
      <div class="modal-header">
        <h5 class="modal-title" id="addInventoryModalLabel">
          <i class="bi bi-plus-circle me-2"></i>Add Inventory Item
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="itemName" class="form-label">NAME</label>
              <input type="text" class="form-control" id="itemName" name="name" required placeholder="Enter item name" autocomplete="off">
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="itemCategory" class="form-label">CATEGORY</label>
              <select class="form-select" id="itemCategory" name="category" required>
                <option value="" selected disabled>Select category</option>
                <option value="Vegetables">Vegetables</option>
                <option value="Meat">Meat</option>
                <option value="Dairy">Dairy</option>
                <option value="Grains">Grains</option>
                <option value="Spices">Spices</option>
                <option value="Beverages">Beverages</option>
                <option value="Condiments">Condiments</option>
                <option value="Frozen">Frozen</option>
                <option value="Canned">Canned</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-8">
            <div class="mb-3">
              <label for="itemBarcode" class="form-label">BARCODE</label>
              <div class="input-group">
                <input type="number" class="form-control" id="itemBarcode" name="barcode" placeholder="Enter barcode" autocomplete="off">
                <button class="btn btn-outline-secondary" type="button" id="generateBarcodeBtn">
                  <i class="bi bi-dice-5 me-1"></i>Generate
                </button>
              </div>
              <div class="form-text">You can manually enter a barcode or generate one.</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label for="itemUnit" class="form-label">UNIT</label>
              <select class="form-select" id="itemUnit" name="unit" required>
                <option value="pcs">pcs</option>
                <option value="Kg">Kg</option>
                <option value="g">g</option>
                <option value="mg">mg</option>
                <option value="L">L</option>
                <option value="ml">ml</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="itemQty" class="form-label">QUANTITY</label>
              <input type="number" step="0.01" class="form-control" id="itemQty" name="quantity" min="0" required placeholder="0.00">
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="itemMaxQty" class="form-label">MAX QUANTITY</label>
              <input type="number" step="0.01" class="form-control" id="itemMaxQty" name="max_quantity" min="0" required placeholder="0.00">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="itemPrice" class="form-label">PRICE (PER UNIT)</label>
              <input type="number" class="form-control" id="itemPrice" name="price" min="0" step="0.01" required placeholder="0.00">
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="itemPurchaseDate" class="form-label">PURCHASE DATE</label>
              <input type="date" class="form-control" id="itemPurchaseDate" name="purchase_date" required>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-2"></i>Cancel
        </button>
        <button type="submit" class="btn btn-success">
          <i class="bi bi-plus-circle me-2"></i>Add Item
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Inventory Modal -->
<div class="modal fade modern-modal" id="editInventoryModal" tabindex="-1" aria-labelledby="editInventoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="/inventory/edit">
      <div class="modal-header">
        <h5 class="modal-title" id="editInventoryModalLabel">
          <i class="bi bi-pencil-square me-2"></i>Edit Inventory Item
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editItemId" name="id">

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="editItemName" class="form-label">NAME</label>
              <input type="text" class="form-control" id="editItemName" name="name" required placeholder="Enter item name">
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="editItemBarcode" class="form-label">BARCODE</label>
              <input type="number" class="form-control" id="editItemBarcode" name="barcode" required placeholder="Enter barcode">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="editItemQty" class="form-label">QUANTITY</label>
              <input type="number" step="0.01" class="form-control" id="editItemQty" name="quantity" min="0" required placeholder="0.00">
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="editItemMaxQty" class="form-label">MAX QUANTITY</label>
              <input type="number" step="0.01" class="form-control" id="editItemMaxQty" name="max_quantity" min="0" required placeholder="0.00">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="editItemUnit" class="form-label">UNIT</label>
              <select class="form-select" id="editItemUnit" name="unit" required>
                <option value="pcs">pcs</option>
                <option value="Kg">Kg</option>
                <option value="g">g</option>
                <option value="mg">mg</option>
                <option value="L">L</option>
                <option value="ml">ml</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="editItemPrice" class="form-label">PRICE (PER UNIT)</label>
              <input type="number" class="form-control" id="editItemPrice" name="price" min="0" step="0.01" required placeholder="0.00">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="editItemPurchaseDate" class="form-label">PURCHASE DATE</label>
              <input type="date" class="form-control" id="editItemPurchaseDate" name="purchase_date" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="editItemCategory" class="form-label">CATEGORY</label>
              <select class="form-select" id="editItemCategory" name="category" required>
                <option value="Vegetables">Vegetables</option>
                <option value="Meat">Meat</option>
                <option value="Dairy">Dairy</option>
                <option value="Grains">Grains</option>
                <option value="Spices">Spices</option>
                <option value="Beverages">Beverages</option>
                <option value="Condiments">Condiments</option>
                <option value="Frozen">Frozen</option>
                <option value="Canned">Canned</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-2"></i>Cancel
        </button>
        <?php if ($_SESSION['user_type'] !== 'User'): ?>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle me-2"></i>Save Changes
        </button>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<!-- Delete Inventory Modal -->
<div class="modal fade" id="deleteInventoryModal" tabindex="-1" aria-labelledby="deleteInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <form class="modal-content" method="post" action="/inventory/delete">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteInventoryModalLabel">Delete Inventory Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="deleteItemId" name="id">
        <p>Are you sure you want to delete this item?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>

<!-- Print Barcode Modal -->
<div class="modal fade" id="printBarcodeModal" tabindex="-1" aria-labelledby="printBarcodeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="printBarcodeModalLabel">Print Barcode</h5>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex flex-column">
        <iframe id="printBarcodeIframe" style="width: 100%; flex-grow: 1; border: none;"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/layout/footer.php' ?>

<script>
  $(document).ready(function() {
    var table = $('#inventoryTable').DataTable({
      "language": {
        "emptyTable": "No inventory items found"
      },
      dom: '<"row"<"col-sm-4"l><"col-sm-8"f>>rtip',
      lengthMenu: [
        [50, 100, 250, 500, -1],
        [50, 100, 250, 500, "All"]
      ]
    });

    // Handle Export to Excel button click
    $('#exportExcelBtn').on('click', function() {
      // Create a new button instance for export
      new $.fn.dataTable.Buttons(table, {
        buttons: [
          {
            extend: 'excelHtml5',
            text: 'Export to Excel',
            title: 'Inventory_Report_' + new Date().toISOString().split('T')[0],
            exportOptions: {
              columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] // Exclude Actions column (index 9)
            }
          }
        ]
      }).container().find('button').click();
    });

    // Handle clickable row click (for editing)
    $('#inventoryTable').on('click', '.clickable-row', function(e) {
      // Don't trigger row click if a button was clicked
      if ($(e.target).closest('button').length) {
        return;
      }
      
      var itemId = $(this).data('id');

      $.ajax({
        url: '/inventory/getDetail?id=' + itemId,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          if (data) {
            $('#editItemId').val(data.id);
            $('#editItemName').val(data.name);
            $('#editItemBarcode').val(data.barcode);
            $('#editItemQty').val(data.quantity);
            $('#editItemMaxQty').val(data.max_quantity);
            $('#editItemUnit').val(data.unit);
            $('#editItemPrice').val(data.price);
            $('#editItemPurchaseDate').val(data.purchase_date ? data.purchase_date.substring(0, 10) : '');
            $('#editItemCategory').val(data.category);
            $('#editInventoryModal').modal('show');
          }
        },
        error: function() {
          alert('Could not fetch item details.');
        }
      });
    });

    // Handle Delete button click
    $('#inventoryTable').on('click', '.delete-btn', function(e) {
        e.stopPropagation(); // Prevent row click
        var itemId = $(this).data('id');
        $('#deleteItemId').val(itemId);
        $('#deleteInventoryModal').modal('show');
    });

    // Handle Barcode generation
        $('#generateBarcodeBtn').on('click', function() {
            var barcode = Date.now();
            $('#itemBarcode').val(barcode);
        });
        
        // Handle Print button click
        $('#inventoryTable').on('click', '.print-btn', function(e) {
            e.stopPropagation(); // Prevent row click
            var itemId = $(this).data('id');
            var barcode = $(this).data('barcode');
            
            if (!barcode) {
                alert('No barcode available for this item.');
                return;
            }
            
            // Set the iframe source and show the modal
            var iframeSrc = '/inventory/print-barcode?id=' + itemId + '&barcode=' + encodeURIComponent(barcode);
            $('#printBarcodeIframe').attr('src', iframeSrc);
            $('#printBarcodeModal').modal('show');
        });

        // Handle printing from the modal iframe
        $('#printIframeContent').on('click', function() {
            var iframe = document.getElementById('printBarcodeIframe');
            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }
        });
});
</script>
