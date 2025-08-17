<?php include_once __DIR__ . '/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-between align-items-center mb-3">
        <div class="col">
            <h2>Menus</h2>
        </div>
        <div class="col-auto">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                <i class="fa fa-plus"></i> Add Menu
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="menuTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($menus)): ?>
                    <?php foreach ($menus as $menu): ?>
                        <tr class="menu-row" data-id="<?= $menu['id'] ?>">
                            <td><?= htmlspecialchars($menu['id']) ?></td>
                            <td><?= htmlspecialchars($menu['name']) ?></td>
                            <td>
                                <button class="btn btn-info btn-sm edit-btn" data-id="<?= htmlspecialchars($menu['id']) ?>">
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

<!-- Add Menu Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="post" action="/menu/add">
      <div class="modal-header">
        <h5 class="modal-title" id="addMenuModalLabel">Add Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="menuName" class="form-label">Menu Name</label>
          <input type="text" class="form-control" id="menuName" name="name" required>
        </div> 
        <hr>
        <h5>Ingredients</h5>
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
        <div class="mb-3">
          <label for="editMenuName" class="form-label">Menu Name</label>
          <input type="text" class="form-control" id="editMenuName" name="name" required>
        </div> 
        <hr>
        <h5>Ingredients</h5>
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
        <div class="col-5">
            <select class="form-select ingredient-select" name="ingredients[inventory_id][]" required>
                <option value="" selected disabled>Select Ingredient</option>
                <!-- Options populated by JS -->
            </select>
        </div>
        <div class="col-3">
            <input type="number" class="form-control" name="ingredients[quantity][]" placeholder="Quantity" min="0" step="any" required>
        </div> 
        <div class="col-2">
            <span class="unit-span"></span>
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
        <h6 id="menuName"></h6>
        <table class="table">
          <thead>
            <tr>
              <th>Ingredient</th>
              <th>Quantity</th>
              <th>Unit</th>
            </tr>
          </thead>
          <tbody id="ingredientList"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.menu-row').forEach(row => {
  row.addEventListener('click', function (e) {
    // Prevent row click if a button inside the row was clicked
    if (e.target.closest('.edit-btn') || e.target.closest('.delete-btn')) {
      return;
    }

    const menuId = this.dataset.id;
    fetch(`/menu/getDetail?id=${menuId}`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('menuName').textContent = data.name;
        const ingredientList = document.getElementById('ingredientList');
        ingredientList.innerHTML = '';

        if (data.ingredients && data.ingredients.length > 0) {
          data.ingredients.forEach(ing => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
              <td>${ing.name}</td>
              <td>${ing.quantity}</td>
              <td>${ing.unit}</td>
            `;
            ingredientList.appendChild(tr);
          });
        } else {
          ingredientList.innerHTML = '<tr><td colspan="3">No ingredients found.</td></tr>';
        }

        const modal = new bootstrap.Modal(document.getElementById('menuDetailModal'));
        modal.show();
      });
  });
});
</script>




<?php include_once __DIR__ . '/layout/footer.php' ?>

<script>
$(document).ready(function() {
    $('#menuTable').DataTable();

    let inventoryItems = [];

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
            selectElement.append(`<option value="${item.id}" data-unit="${item.unit}">${item.name} (${item.unit})</option>`);
        });
    }

    function addIngredientRow(container) {
        const template = $('#ingredient-row-template .ingredient-row').clone();
        const select = template.find('.ingredient-select');
        populateIngredientSelect(select);
        container.append(template);
    }

    // --- Add Modal ---
    $('#addMenuModal').on('shown.bs.modal', function () {
        fetchInventory().done(function() {
            const container = $('#add-ingredients-container');
            container.empty(); // Clear previous
            addIngredientRow(container);
        });
    });

    $('#add-ingredient-btn').on('click', function() {
        addIngredientRow($('#add-ingredients-container'));
    });

    $('#add-ingredients-container, #edit-ingredients-container').on('click', '.remove-ingredient-btn', function() {
        $(this).closest('.ingredient-row').remove();
    });

    $('#add-ingredients-container, #edit-ingredients-container').on('change', '.ingredient-select', function() {
        const selectedOption = $(this).find('option:selected');
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

                const container = $('#edit-ingredients-container');
                container.empty();

                if (data.ingredients && data.ingredients.length > 0) {
                    data.ingredients.forEach(function(ing) {
                        const template = $('#ingredient-row-template .ingredient-row').clone();
                        const select = template.find('.ingredient-select');
                        populateIngredientSelect(select);
                        
                        select.val(ing.inventory_id);
                        template.find('input[name="ingredients[quantity][]"]').val(ing.quantity);
                        template.find('.unit-span').text(ing.unit || '');
                        
                        container.append(template);
                    });
                } else {
                    addIngredientRow(container);
                }

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
});
</script>