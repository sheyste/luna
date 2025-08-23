<?php include_once __DIR__ . '/layout/header.php'; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Inventory</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Inventory</li>
        </ol>
    </nav>
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
            <table class="table table-bordered" id="inventoryTable" width="100%" cellspacing="0">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Barcode</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Price (Per Unit)</th>
                        <th>Total Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                  <?php if (!empty($items)): ?>
                      <?php foreach ($items as $item): ?>
                          <?php
                              $quantity = (float)($item['quantity'] ?? 0);
                              $price = (float)($item['price'] ?? 0);
                              $totalPrice = $quantity * $price;
                          ?>
                          <tr>
                              <td><?= htmlspecialchars($item['name'] ?? '') ?></td>
                              <td><?= htmlspecialchars($item['barcode'] ?? '') ?></td>
                              <td><?= htmlspecialchars($item['quantity'] ?? '') ?></td>
                              <td><?= htmlspecialchars($item['unit'] ?? '') ?></td>
                              <td>&#8369;<?= htmlspecialchars(number_format($price, 2)) ?></td>
                              <td>&#8369;<?= htmlspecialchars(number_format($totalPrice, 2)) ?></td>
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
          <label for="itemQty" class="form-label">Quantity</label>
          <input type="number" class="form-control" id="itemQty" name="quantity" min="0" required>
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
          <label for="editItemBARCODE" class="form-label">Barcode</label>
          <input type="text" class="form-control" id="editItemBARCODE" name="barcode" readonly>
        </div>
        <div class="mb-3">
          <label for="editItemQty" class="form-label">Quantity</label>
          <input type="number" class="form-control" id="editItemQty" name="quantity" min="0" required>
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

<?php include_once __DIR__ . '/layout/footer.php' ?>

<script>
  $(document).ready(function() {
    $('#inventoryTable').DataTable({
      "language": {
        "emptyTable": "No inventory items found"
      }
    });

    // Handle Edit button click
    $('#inventoryTable').on('click', '.edit-btn', function() {
      var itemId = $(this).data('id');

      $.ajax({
        url: '/inventory/getDetail?id=' + itemId,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          if (data) {
            $('#editItemId').val(data.id);
            $('#editItemName').val(data.name);
            $('#editItemBARCODE').val(data.barcode);
            $('#editItemQty').val(data.quantity);
            $('#editItemUnit').val(data.unit);
            $('#editItemPrice').val(data.price);
            $('#editInventoryModal').modal('show');
          }
        },
        error: function() {
          alert('Could not fetch item details.');
        }
      });
    });

    // Handle Delete button click
    $('#inventoryTable').on('click', '.delete-btn', function() {
        var itemId = $(this).data('id');
        $('#deleteItemId').val(itemId);
        $('#deleteInventoryModal').modal('show');
    });
});
</script>
