<?php include_once __DIR__ . '/layout/header.php'; ?>

<?php
$combinedItems = [];
if (!empty($items)) {
    foreach ($items as $item) {
        $menuId = $item['menu_id'];
        if (!isset($combinedItems[$menuId])) {
            $combinedItems[$menuId] = $item;
            $combinedItems[$menuId]['original_ids'] = [$item['id']];
        } else {
            $combinedItems[$menuId]['quantity_produced'] += $item['quantity_produced'];
            $combinedItems[$menuId]['quantity_available'] += $item['quantity_available'];
            $combinedItems[$menuId]['quantity_sold'] += $item['quantity_sold'];
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
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Production List</h6>
        <div>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addProductionModal">
                <i class="fa fa-plus me-1"></i> Add Production
            </button>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateSoldModal">
                <i class="fa fa-edit me-1"></i> Update Sold
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="productionTable" width="100%" cellspacing="0">
              <thead class="table-dark">
                  <tr>
                      <th>Menu</th>
                      <th>Barcode</th>
                      <th>Produced</th>
                      <th>Available</th>
                      <th>Sold</th>
                      <th>Updated At</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  <?php if (!empty($combinedItems)): ?>
                      <?php foreach ($combinedItems as $item): ?>
                          <tr>
                              <td><?= htmlspecialchars($item['menu_name']) ?></td>
                              <td><?= htmlspecialchars($item['barcode'] ?? '') ?></td>
                              <td><?= htmlspecialchars($item['quantity_produced']) ?></td>
                              <td><?= htmlspecialchars($item['quantity_available']) ?></td>
                              <td><?= htmlspecialchars($item['quantity_sold']) ?></td>
                              <td><?= htmlspecialchars(date('F d, Y H:i:s', strtotime($item['created_at']))) ?></td>
                              <td>
                                  <button class="btn btn-danger btn-sm delete-btn" data-ids="<?= htmlspecialchars(implode(',', $item['original_ids'])) ?>" data-menu-name="<?= htmlspecialchars($item['menu_name']) ?>">
                                      <i class="fa fa-trash"></i>
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


<!-- Update Sold Modal -->
<div class="modal fade" id="updateSoldModal" tabindex="-1" aria-labelledby="updateSoldModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <form class="modal-content p-3" method="post" action="/production/updateSold">
      
      <!-- Header -->
      <div class="modal-header bg-primary text-white">
        <h4 class="modal-title fw-bold" id="updateSoldModalLabel">
          <i class="bi bi-bag-check-fill me-2"></i> Update Sold Quantities
        </h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="row g-4">
          <?php if (!empty($combinedItems)): ?>
            <?php foreach ($combinedItems as $item): ?>
              <?php if ($item['quantity_produced'] > 0): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="card shadow-lg border-0 h-100">
                    <div class="card-body text-center p-4 bg-light rounded">
                      
                      <!-- Menu Name -->
                      <h5 class="fw-bold text-primary mb-3"><?= htmlspecialchars($item['menu_name']) ?></h5>
                      
                      <!-- Input Controls -->
                      <div class="d-flex justify-content-center align-items-center gap-3">
                        <button type="button" 
                                class="btn btn-lg btn-danger decrement" 
                                data-target="sold-<?= $item['menu_id'] ?>">
                          <i class="bi bi-dash-lg"></i>
                        </button>

                        <input type="number" 
                               class="form-control form-control-lg text-center fw-bold" 
                               style="max-width:120px; font-size:1.4rem;" 
                               name="sold[<?= $item['menu_id'] ?>]" 
                               id="sold-<?= $item['menu_id'] ?>" 
                               value="0" 
                               min="0">

                        <button type="button" 
                                class="btn btn-lg btn-success increment" 
                                data-target="sold-<?= $item['menu_id'] ?>">
                          <i class="bi bi-plus-lg"></i>
                        </button>
                      </div>
                      
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-warning text-center fs-5">
                <i class="bi bi-exclamation-circle me-2"></i> No production items found.
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-2"></i> Cancel
        </button>
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="bi bi-save-fill me-2"></i> Save Changes
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
          <label for="quantityProduced" class="form-label">Produced</label>
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
    $('#productionTable').DataTable();
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
                                        var displayQuantity = Number.isInteger(totalQuantity) ? totalQuantity : totalQuantity.toFixed(3).replace(/\.?0+$/, "");
                                        $summaryList.append('<li class="list-group-item">' + (inventoryItem.name || 'Unnamed') + ': ' + displayQuantity + ' ' + (inventoryItem.unit || '') + '</li>');
                                    }
                                    deferred.resolve();
                                },
                                error: function() {
                                    $summaryList.append('<li class="list-group-item text-danger">Error loading ingredient details.</li>');
                                    deferred.resolve();
                                }
                            });
                        });

                        $.when.apply($, promises).done(function() {
                            $('#productionSummaryModal').modal('show');
                        });
                    } else {
                        $summaryList.append('<li class="list-group-item">No ingredients will be deducted.</li>');
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
    $('#productionTable').on('click', '.edit-btn', function() {
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
    $('#productionTable').on('click', '.delete-btn', function() {
        var itemIds = $(this).data('ids');
        var menuName = $(this).data('menu-name');
        $('#deleteItemIds').val(itemIds);
        $('#deleteMenuName').text(menuName);
        $('#deleteProductionModal').modal('show');
    });

    // --- Update Sold Increment/Decrement ---
    $(document).on('click', '.increment', function() {
        var target = $('#' + $(this).data('target'));
        var val = parseInt(target.val()) || 0;
        target.val(val + 1);
    });

    $(document).on('click', '.decrement', function() {
        var target = $('#' + $(this).data('target'));
        var val = parseInt(target.val()) || 0;
        if (val > 0) target.val(val - 1);
    });

    // Handle final confirmation from summary modal
    $('#confirmProductionBtn').on('click', function() {
        $('#addProductionForm').submit();
    });
});
</script>
