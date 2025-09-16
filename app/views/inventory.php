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
            display: flex; /* Use flexbox for better button layout */
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
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Inventory</h1>
</div>

<!-- Main Content Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Inventory List</h6>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addInventoryModal">
            <i class="fa fa-plus me-1"></i> Add Item
        </button>
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
                                                                      <i class="fa fa-print"></i> Print
                                                                  </button>
                                                                  <button class="btn btn-danger btn-sm delete-btn" data-id="<?= htmlspecialchars($item['id']) ?>">
                                                                      <i class="fa fa-trash"></i> Delete
                                                                  </button>
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
<div class="modal fade" id="addInventoryModal" tabindex="-1" aria-labelledby="addInventoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="/inventory/add">
      <div class="modal-header">
        <h5 class="modal-title" id="addInventoryModalLabel">Add Inventory Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="itemName" class="form-label">Name</label>
          <input type="text" class="form-control" id="itemName" name="name" required>
        </div>
        <div class="mb-3">
          <label for="itemCategory" class="form-label">Category</label>
          <select class="form-select" id="itemCategory" name="category" required>
            <option value="" selected disabled> </option>
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

        <div class="mb-3">
            <label for="itemBarcode" class="form-label">Barcode</label>
            <div class="input-group">
                <input type="number" class="form-control" id="itemBarcode" name="barcode">
                <button class="btn btn-outline-secondary" type="button" id="generateBarcodeBtn">Generate</button>
            </div>
            <div class="form-text">You can manually enter a barcode or generate one.</div>
        </div>
        <div class="mb-3">
          <label for="itemQty" class="form-label">Quantity</label>
          <input type="number" step="0.01" class="form-control" id="itemQty" name="quantity" min="0" required>
        </div>
        <div class="mb-3">
          <label for="itemMaxQty" class="form-label">Max Quantity</label>
          <input type="number" step="0.01" class="form-control" id="itemMaxQty" name="max_quantity" min="0" required>
        </div>
        <div class="mb-3">
          <label for="itemUnit" class="form-label">Unit</label>
          <select class="form-select" id="itemUnit" name="unit" required>
            <option value="pcs">pcs</option>
            <option value="Kg">Kg</option>
            <option value="g">g</option>
            <option value="mg">mg</option>
            <option value="L">L</option>
            <option value="ml">ml</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="itemPrice" class="form-label">Price (Per Unit)</label>
          <input type="number" class="form-control" id="itemPrice" name="price" min="0" step="0.01" required>
        </div>
        <div class="mb-3">
          <label for="itemPurchaseDate" class="form-label">Purchase Date</label>
          <input type="date" class="form-control" id="itemPurchaseDate" name="purchase_date" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Add Item</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Inventory Modal -->
<div class="modal fade" id="editInventoryModal" tabindex="-1" aria-labelledby="editInventoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="/inventory/edit">
      <div class="modal-header">
        <h5 class="modal-title" id="editInventoryModalLabel">Edit Inventory Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editItemId" name="id">
        <div class="mb-3">
          <label for="editItemName" class="form-label">Name</label>
          <input type="text" class="form-control" id="editItemName" name="name" required>
        </div>
        <div class="mb-3">
          <label for="editItemBarcode" class="form-label">Barcode</label>
          <input type="number" class="form-control" id="editItemBarcode" name="barcode" required>
        </div>
        <div class="mb-3">
          <label for="editItemQty" class="form-label">Quantity</label>
          <input type="number" step="0.01" class="form-control" id="editItemQty" name="quantity" min="0" required>
        </div>
        <div class="mb-3">
          <label for="editItemMaxQty" class="form-label">Max Quantity</label>
          <input type="number" step="0.01" class="form-control" id="editItemMaxQty" name="max_quantity" min="0" required>
        </div>
        <div class="mb-3">
          <label for="editItemUnit" class="form-label">Unit</label>
          <select class="form-select" id="editItemUnit" name="unit" required>
            <option value="pcs">pcs</option>
            <option value="Kg">Kg</option>
            <option value="g">g</option>
            <option value="mg">mg</option>
            <option value="L">L</option>
            <option value="ml">ml</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="editItemPrice" class="form-label">Price (Per Unit)</label>
          <input type="number" class="form-control" id="editItemPrice" name="price" min="0" step="0.01" required>
        </div>
        <div class="mb-3">
          <label for="editItemPurchaseDate" class="form-label">Purchase Date</label>
          <input type="date" class="form-control" id="editItemPurchaseDate" name="purchase_date" required>
        </div>
        <div class="mb-3">
          <label for="editItemCategory" class="form-label">Category</label>
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
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Changes</button>
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
    $('#inventoryTable').DataTable({
      "language": {
        "emptyTable": "No inventory items found"
      }
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