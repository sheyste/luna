<?php include_once __DIR__ . '/layout/header.php'; ?>

<style>
  tr:hover {
    cursor: pointer; /* Changes the cursor to a hand pointer */
  }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Menus</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Menus</li>
        </ol>
    </nav>
</div>

<!-- Main Content Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Menu List</h6>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addMenuModal">
            <i class="fa fa-plus me-1"></i> Add Menu
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="menuTable" width="100%" cellspacing="0">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Barcode</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($menus)): ?>
                        <?php foreach ($menus as $menu): ?>
                            <tr class="menu-row" data-id="<?= $menu['id'] ?>">
                                <td><?= htmlspecialchars($menu['name']) ?></td>
                                <td><?= htmlspecialchars($menu['barcode']) ?></td>
                                <td>&#8369;<?= htmlspecialchars(number_format($menu['price'] ?? 0, 2)) ?></td>
                                <td class="text-nowrap">
                                    <button class="btn btn-primary btn-sm view-btn me-3" data-id="<?= htmlspecialchars($menu['id']) ?>">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button class="btn btn-info btn-sm edit-btn me-3" data-id="<?= htmlspecialchars($menu['id']) ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= htmlspecialchars($menu['id']) ?>">
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

<!-- Add Menu Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="post" action="/menu/add">
      <div class="modal-header">
        <h5 class="modal-title" id="addMenuModalLabel">Add Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                  <label for="menuName" class="form-label">Menu Name</label>
                  <input type="text" class="form-control" id="menuName" name="name" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                  <label for="menuPrice" class="form-label">Selling Price</label>
                  <input type="number" class="form-control" id="menuPrice" name="price" required min="0" step="0.01">
                </div>
            </div>
        </div>
        <div class="mb-3">
          <label for="menuBarcode" class="form-label">Menu Barcode</label>
          <input type="text" class="form-control" id="menuBarcode" name="barcode" required>
        </div>
        <hr>
        <h5>Ingredients <span class="float-end">Total Cost: <span id="add-total-cost" class="fw-bold">0.00</span></span></h5>
        <div id="add-ingredients-container">
            <!-- Ingredient rows will be added here by JS -->
        </div>
        <button type="button" class="btn btn-primary btn-sm" id="add-ingredient-btn">
            <i class="fa fa-plus"></i> Add Ingredient
        </button>
      </div>
      <div class="modal-footer"> 
        <button type="submit" class="btn btn-success">Save Menu</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Menu Modal -->
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="post" action="/menu/edit">
      <div class="modal-header">
        <h5 class="modal-title" id="editMenuModalLabel">Edit Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editMenuId" name="id">
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                  <label for="editMenuName" class="form-label">Menu Name</label>
                  <input type="text" class="form-control" id="editMenuName" name="name" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                  <label for="editMenuPrice" class="form-label">Selling Price</label>
                  <input type="number" class="form-control" id="editMenuPrice" name="price" required min="0" step="0.01">
                </div>
            </div>
        </div>
        <div class="mb-3">
          <label for="editMenuBarcode" class="form-label">Menu Barcode</label>
          <input type="text" class="form-control" id="editMenuBarcode" name="barcode" readonly>
        </div>
        <hr>
        <h5>Ingredients <span class="float-end">Total Cost: <span id="edit-total-cost" class="fw-bold">0.00</span></span></h5>
        <div id="edit-ingredients-container">
            <!-- Ingredient rows will be added here by JS -->
        </div>
        <button type="button" class="btn btn-primary btn-sm" id="edit-add-ingredient-btn">
            <i class="fa fa-plus"></i> Add Ingredient
        </button>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Menu Modal -->
<div class="modal fade" id="deleteMenuModal" tabindex="-1" aria-labelledby="deleteMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="/menu/delete">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteMenuModalLabel">Delete Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="deleteMenuId" name="id">
        <p>Are you sure you want to delete this menu and its recipe?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>

<!-- Ingredient Row Template -->
<div id="ingredient-row-template" style="display: none;">
    <div class="row ingredient-row mb-2 align-items-center">
        <div class="col-4">
            <select class="form-select ingredient-select" name="ingredients[inventory_id][]" required>
                <option value="" selected disabled>Select Ingredient</option>
                <!-- Options populated by JS -->
            </select>
        </div>
        <div class="col-2">
            <input type="number" class="form-control ingredient-quantity" name="ingredients[quantity][]" placeholder="Qty" min="0" step="any" required>
        </div>
        <div class="col-2">
            <span class="unit-span"></span>
        </div>
        <div class="col-2">
            <span class="cost-span fw-bold">0.00</span>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-danger btn-sm remove-ingredient-btn"><i class="fa fa-trash"></i></button>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="menuDetailModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Menu Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex justify-content-between">
            <h6 id="menuDetailName"></h6>
            <h6 id="menuDetailPrice" class="text-success fw-bold"></h6>
        </div>
        <hr class="mt-1">
        <h6>Recipe:</h6>
        <table class="table table-sm">
          <thead>
            <tr>
              <th>Ingredient</th>
              <th>Quantity</th>
              <th>Unit</th>
              <th>Cost</th>
            </tr>
          </thead>
          <tbody id="ingredientList"></tbody>
          <tfoot>
              <tr>
                  <th colspan="3" class="text-end">Total Ingredient Cost:</th>
                  <th id="totalIngredientCost"></th>
              </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/layout/footer.php' ?>

<script>
$(document).ready(function() {
    $('#menuTable').DataTable();

    let inventoryItems = [];

    function showMenuDetails(menuId) {
        fetch(`/menu/getDetail?id=${menuId}`)
          .then(response => response.json())
          .then(data => {
            document.getElementById('menuDetailName').textContent = data.name;
            document.getElementById('menuDetailPrice').innerHTML = `Price: &#8369;${parseFloat(data.price || 0).toFixed(2)}`;
            const ingredientList = document.getElementById('ingredientList');
            ingredientList.innerHTML = '';
            let totalCost = 0;

            if (data.ingredients && data.ingredients.length > 0) {
              data.ingredients.forEach(ing => {
                const cost = (parseFloat(ing.price) || 0) * (parseFloat(ing.quantity) || 0);
                totalCost += cost;
                const tr = document.createElement('tr');
                tr.innerHTML = `
                  <td>${ing.name}</td>
                  <td>${ing.quantity}</td>
                  <td>${ing.unit}</td>
                  <td>${cost.toFixed(2)}</td>
                `;
                ingredientList.appendChild(tr);
              });
            } else {
              ingredientList.innerHTML = '<tr><td colspan="4">No ingredients found.</td></tr>';
            }

            document.getElementById('totalIngredientCost').textContent = totalCost.toFixed(2);

            const modal = new bootstrap.Modal(document.getElementById('menuDetailModal'));
            modal.show();
          });
    }

    // Handle row click to view details
    $('#menuTable tbody').on('click', 'tr.menu-row', function(e) {
        // Prevent modal from opening if a button was clicked
        if ($(e.target).closest('button').length) {
            return;
        }
        const menuId = $(this).data('id');
        if (menuId) { // Make sure we have an ID
            showMenuDetails(menuId);
        }
    });
    
    // Handle View button click
    $('#menuTable').on('click', '.view-btn', function() {
        var menuId = $(this).data('id');
        showMenuDetails(menuId);
    });

    // Fetch inventory items to be used in dropdowns
    function fetchInventory() {
        if (inventoryItems.length === 0) {
            return $.ajax({
                url: '/inventory/getAll', // Assumes an endpoint that returns all inventory items
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    inventoryItems = data;
                }
            });
        }
        return $.Deferred().resolve().promise();
    }

    function populateIngredientSelect(selectElement) {
        selectElement.empty().append('<option value="" selected disabled>Select Ingredient</option>');
        inventoryItems.forEach(function(item) {
            selectElement.append(`<option value="${item.id}" data-unit="${item.unit}" data-price="${item.price || 0}">${item.name} (${item.unit})</option>`);
        });
    }

    function addIngredientRow(container) {
        const template = $('#ingredient-row-template .ingredient-row').clone();
        const select = template.find('.ingredient-select');
        populateIngredientSelect(select);
        container.append(template);
    }

    function calculateTotalCost(containerSelector) {
        let totalCost = 0;
        $(`${containerSelector} .ingredient-row`).each(function() {
            const select = $(this).find('.ingredient-select');
            const quantityInput = $(this).find('.ingredient-quantity');
            const costSpan = $(this).find('.cost-span');

            const price = parseFloat(select.find('option:selected').data('price')) || 0;
            const quantity = parseFloat(quantityInput.val()) || 0;
            const rowCost = price * quantity;

            costSpan.text(rowCost.toFixed(2));
            totalCost += rowCost;
        });

        const totalCostSelector = containerSelector.includes('add') ? '#add-total-cost' : '#edit-total-cost';
        $(totalCostSelector).text(totalCost.toFixed(2));
    }

    // --- Add Modal ---
    $('#addMenuModal').on('shown.bs.modal', function () {
        // Generate SKU
        const sku = Date.now();
        $('#menuBarcode').val(sku);

        fetchInventory().done(function() {
            const container = $('#add-ingredients-container');
            container.empty(); // Clear previous
            $('#add-total-cost').text('0.00');
            addIngredientRow(container);
        });
    });

    $('#add-ingredient-btn').on('click', function() {
        addIngredientRow($('#add-ingredients-container'));
    });

    $('#add-ingredients-container, #edit-ingredients-container').on('click', '.remove-ingredient-btn', function() {
        const containerSelector = $(this).closest('#add-ingredients-container').length ? '#add-ingredients-container' : '#edit-ingredients-container';
        $(this).closest('.ingredient-row').remove();
        calculateTotalCost(containerSelector);
    });

    $('#add-ingredients-container, #edit-ingredients-container').on('change keyup', '.ingredient-select, .ingredient-quantity', function() {
        const selectedOption = $(this).find('option:selected');
        const containerSelector = $(this).closest('#add-ingredients-container').length ? '#add-ingredients-container' : '#edit-ingredients-container';
        const unit = selectedOption.data('unit');
        $(this).closest('.ingredient-row').find('.unit-span').text(unit || '');
    });

    // --- Edit Modal ---
    $('#menuTable').on('click', '.edit-btn', function() {
        var menuId = $(this).data('id');

        $.when(
            fetchInventory(),
            $.ajax({
                url: '/menu/getDetail?id=' + menuId, // Assumes an endpoint for menu details
                method: 'GET',
                dataType: 'json'
            })
        ).done(function(inventoryResult, menuResult) {
            const data = menuResult[0]; // AJAX result is an array [data, status, xhr]
            if(data) {
                $('#editMenuId').val(data.id);
                $('#editMenuName').val(data.name);
                $('#editMenuBarcode').val(data.barcode);
                $('#editMenuPrice').val(parseFloat(data.price || 0).toFixed(2));

                const container = $('#edit-ingredients-container');
                container.empty();

                if (data.ingredients && data.ingredients.length > 0) {
                    data.ingredients.forEach(function(ing) {
                        const template = $('#ingredient-row-template .ingredient-row').clone();
                        const select = template.find('.ingredient-select');
                        populateIngredientSelect(select);

                        select.val(ing.inventory_id);
                        template.find('.ingredient-quantity').val(ing.quantity);
                        template.find('.unit-span').text(ing.unit || '');

                        container.append(template);
                    });
                } else {
                    addIngredientRow(container);
                }

                calculateTotalCost('#edit-ingredients-container');
                $('#editMenuModal').modal('show');
            }
        }).fail(function() {
            alert('Could not fetch menu details.');
        });
    });

    $('#edit-add-ingredient-btn').on('click', function() {
        addIngredientRow($('#edit-ingredients-container'));
    });

    // --- Delete Modal ---
    $('#menuTable').on('click', '.delete-btn', function() {
        var menuId = $(this).data('id');
        $('#deleteMenuId').val(menuId);
        $('#deleteMenuModal').modal('show');
    });

    // Recalculate cost on ingredient change or quantity change
    $('#add-ingredients-container, #edit-ingredients-container').on('change input', '.ingredient-select, .ingredient-quantity', function() {
        const containerSelector = $(this).closest('#add-ingredients-container').length ? '#add-ingredients-container' : '#edit-ingredients-container';
        calculateTotalCost(containerSelector);
    });
});
</script>