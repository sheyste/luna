<?php include_once __DIR__ . '/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-between align-items-center mb-3">
        <div class="col">
            <h2>Inventory</h2>
        </div>
        <div class="col-auto">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addInventoryModal">
                <i class="fa fa-plus"></i> Add Item
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="inventoryTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Quantity</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['id']) ?></td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['sku']) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td><?= htmlspecialchars($item['location']) ?></td>
                            <td>
                                <!-- Add edit/delete buttons here if needed -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No inventory items found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
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
          <label for="itemSKU" class="form-label">SKU</label>
          <input type="text" class="form-control" id="itemSKU" name="sku" required>
        </div>
        <div class="mb-3">
          <label for="itemQty" class="form-label">Quantity</label>
          <input type="number" class="form-control" id="itemQty" name="quantity" min="0" required>
        </div>
        <div class="mb-3">
          <label for="itemLocation" class="form-label">Location</label>
          <input type="text" class="form-control" id="itemLocation" name="location">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Add Item</button>
      </div>
    </form>
  </div>
</div>

<?php include_once __DIR__ . '/layout/footer.php' ?>

<script>
$(document).ready(function() {
    $('#inventoryTable').DataTable();
});
</script>