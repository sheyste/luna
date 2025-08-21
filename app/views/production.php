<?php include_once __DIR__ . '/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-between align-items-center mb-3">
        <div class="col">
            <h2>Production</h2>
        </div>
        <div class="col-auto">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductionModal">
                <i class="fa fa-plus"></i> Add Production
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="productionTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Menu</th>
                    <th>Barcode</th>
                    <th>Produced</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['id']) ?></td>
                            <td><?= htmlspecialchars($item['menu_name']) ?></td>
                            <td><?= htmlspecialchars($item['barcode'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['quantity_produced']) ?></td>
                            <td><?= htmlspecialchars($item['quantity_available']) ?></td>
                            <td>
                                <button class="btn btn-info btn-sm edit-btn" data-id="<?= htmlspecialchars($item['id']) ?>">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= htmlspecialchars($item['id']) ?>">
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

<!-- Add Production Modal -->
<div class="modal fade" id="addProductionModal" tabindex="-1" aria-labelledby="addProductionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="/production/add">
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
        <div class="mb-3">
          <label for="barcode" class="form-label">Barcode</label>
          <input type="text" class="form-control" id="barcode" name="barcode" readonly>
        </div>
        <div class="mb-3">
          <label for="quantityProduced" class="form-label">Produced</label>
          <input type="number" class="form-control" id="quantityProduced" name="quantity_produced" min="0" required>
        </div>
        <div class="mb-3">
          <label for="quantityAvailable" class="form-label">Available</label>
          <input type="number" class="form-control" id="quantityAvailable" name="quantity_available" min="0" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Add Item</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Production Modal -->
<div class="modal fade" id="editProductionModal" tabindex="-1" aria-labelledby="editProductionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="/production/edit">
      <div class="modal-header">
        <h5 class="modal-title" id="editProductionModalLabel">Edit Production Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editItemId" name="id">
        <div class="mb-3">
          <label for="editMenuId" class="form-label">Menu</label>
          <select class="form-select" id="editMenuId" name="menu_id" required>
            <option value="">-- Select Menu --</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="editBarcode" class="form-label">Barcode</label>
          <input type="text" class="form-control" id="editBarcode" name="barcode" readonly>
        </div>
        <div class="mb-3">
          <label for="editQuantityProduced" class="form-label">Produced</label>
          <input type="number" class="form-control" id="editQuantityProduced" name="quantity_produced" min="0" required>
        </div>
        <div class="mb-3">
          <label for="editQuantityAvailable" class="form-label">Available</label>
          <input type="number" class="form-control" id="editQuantityAvailable" name="quantity_available" min="0" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Changes</button>
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
    });

    // Auto-populate barcode and sku when a menu is selected in the Add modal
    $('#menuId').on('change', function() {
        var selectedMenuId = $(this).val();
        if (selectedMenuId) {
            var selectedMenu = menuData.find(menu => menu.id == selectedMenuId);
            if (selectedMenu) {
                // Assuming menu object has barcode and sku properties
                $('#barcode').val(selectedMenu.barcode || '');
            }
        } else {
            $('#barcode').val('');
        }
    });

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
                    $('#editQuantityAvailable').val(data.quantity_available);

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
        var itemId = $(this).data('id');
        $('#deleteItemId').val(itemId);
        $('#deleteProductionModal').modal('show');
    });
});


</script>