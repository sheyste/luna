<?php include_once __DIR__ . '/layout/header.php'; ?>

<style>
    .menu-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }


    .price-tag {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background-color: rgba(199, 0, 0, 0.7);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 0.25rem;
        font-weight: bold;
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Menus</h1>
</div>

<!-- Main Content -->
<div class="card-header py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
    <div class="input-group mb-3 mb-md-0" style="max-width: 350px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); border-radius: 8px; transition: all 0.2s ease;">
        <span class="input-group-text" style="border: none; background: transparent;"><i class="fa fa-search"></i></span>
        <input type="text" id="menuSearch" class="form-control" placeholder="Search..." style="border: none; box-shadow: none;">
        <button class="btn btn-outline-secondary" type="button" id="clearMenuSearch" style="border-radius: 0 8px 8px 0; border: none;">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMenuModal">
        <i class="fa fa-plus me-1"></i> Add Menu
    </button>
</div>

<div class="row" id="menu-container">
    <?php if (!empty($menus)): ?>
        <?php foreach ($menus as $menu): ?>
            <div class="col-lg-3 col-md-3 mb-4">
                <div class="card menu-card h-100 shadow-sm border-0 rounded-3" style="cursor: pointer;" data-id="<?= htmlspecialchars($menu['id']) ?>" data-barcode="<?= htmlspecialchars($menu['barcode']) ?>">
                    <div class="price-tag">&#8369;<?= htmlspecialchars(number_format($menu['price'] ?? 0, 2)) ?></div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($menu['name']) ?></h5>
                        <p class="card-text text-muted">Barcode: <?= htmlspecialchars($menu['barcode']) ?></p>
                        <div class="mt-3">
                            <p class="mb-1"><strong>Price:</strong> &#8369;<?= htmlspecialchars(number_format($menu['price'] ?? 0, 2)) ?></p>
                            <p class="mb-1"><strong>Cost:</strong> &#8369;<?= htmlspecialchars(number_format($menu['cost'] ?? 0, 2)) ?></p>
                            <?php
                                $price = $menu['price'] ?? 0;
                                $cost = $menu['cost'] ?? 0;
                                $profitMargin = ($price > 0) ? (($price - $cost) / $price) * 100 : 0;
                            ?>
                            <p class="mb-1"><strong>Profit Margin:</strong> <?= htmlspecialchars(number_format($profitMargin, 2)) ?>%</p>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-end gap-2">
                                        </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">No menu items found.</div>
        </div>
    <?php endif; ?>
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
            <label for="menuBarcode" class="form-label">Barcode</label>
            <div class="input-group">
                <input type="number" class="form-control" id="menuBarcode" name="barcode" required>
                <button class="btn btn-outline-secondary" type="button" id="generateBarcodeBtn">Generate</button>
            </div>
            <div class="form-text">You can manually enter a barcode or generate one.</div>
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
        <h5>Ingredients <span class="float-end">Total Cost: &#8369;<span id="edit-total-cost" class="fw-bold">0.00</span></span></h5>
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
        <div class="col-3">
            <div class="input-group">
                <input type="number" class="form-control ingredient-quantity" name="ingredients[quantity][]" placeholder="Qty" min="0" step="any" required>
                <span class="input-group-text unit-span"></span>
            </div>
        </div>
        <div class="col-1">
            <!-- Unit now displayed in input group -->
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
      <!-- Add modal footer here -->
      <div class="modal-footer">
           <button class="btn btn-primary btn-sm print-btn-modal" data-bs-dismiss="modal">
               <i class="fa fa-print"></i> Print Barcode
           </button>
           <button class="btn btn-info btn-sm edit-btn-modal" data-bs-dismiss="modal">
               <i class="fa fa-edit"></i> Edit
           </button>
           <button class="btn btn-outline-danger btn-sm delete-btn-modal" data-bs-dismiss="modal">
               <i class="fa fa-trash"></i> Delete
           </button>
       </div>
       <!-- Hidden fields to store data -->
       <input type="hidden" id="modalMenuId">
       <input type="hidden" id="modalMenuBarcode">
    </div>
  </div>
</div>

<!-- Print Barcode Modal -->
<div class="modal fade" id="printBarcodeModal" tabindex="-1" aria-labelledby="printBarcodeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="printBarcodeModalLabel">Print Barcode</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                  <td>&#8369;${cost.toFixed(2)}</td>
                `;
                ingredientList.appendChild(tr);
              });
            } else {
              ingredientList.innerHTML = '<tr><td colspan="4">No ingredients found.</td></tr>';
            }

            document.getElementById('totalIngredientCost').innerHTML = `&#8369;${totalCost.toFixed(2)}`;

            const modal = new bootstrap.Modal(document.getElementById('menuDetailModal'));
            modal.show();
          });
    }

    // Handle Card click
    $('#menu-container').on('click', '.menu-card', function(e) {
        var menuId = $(this).data('id');
        var menuBarcode = $(this).data('barcode'); // Get barcode from the card

        // Store menuId and barcode in the modal's hidden fields
        $('#modalMenuId').val(menuId);
        $('#modalMenuBarcode').val(menuBarcode);

        showMenuDetails(menuId); // This function already fetches details using menuId
    });
    
    // Handle Print button click within the modal
    $('#menuDetailModal').on('click', '.print-btn-modal', function() {
        var menuId = $('#modalMenuId').val();
        var barcode = $('#modalMenuBarcode').val();

        if (!barcode) {
            alert('No barcode available for this menu item.');
            return;
        }

        // Set the iframe source and show the modal
        var iframeSrc = '/menu/print-barcode?id=' + menuId + '&barcode=' + encodeURIComponent(barcode);
        $('#printBarcodeIframe').attr('src', iframeSrc);
        $('#printBarcodeModal').modal('show');
    });

    // Handle Edit button click within the modal
    $('#menuDetailModal').on('click', '.edit-btn-modal', function() {
        var menuId = $('#modalMenuId').val();

        // Fetch inventory items and menu details for editing
        $.when(
            fetchInventory(),
            $.ajax({
                url: '/menu/getDetail?id=' + menuId,
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
            alert('Could not fetch menu details for editing.');
        });
    });

    // Handle Delete button click within the modal
    $('#menuDetailModal').on('click', '.delete-btn-modal', function() {
        var menuId = $('#modalMenuId').val();
        $('#deleteMenuId').val(menuId);
        $('#deleteMenuModal').modal('show');
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
        $('#menuBarcode').val('');
        fetchInventory().done(function() {
            const container = $('#add-ingredients-container');
            container.empty(); // Clear previous
            $('#add-total-cost').text('0.00');
            addIngredientRow(container);
        });
    });

    $('#generateBarcodeBtn').on('click', function() {
        const sku = Date.now();
        $('#menuBarcode').val(sku);
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


    $('#edit-add-ingredient-btn').on('click', function() {
        addIngredientRow($('#edit-ingredients-container'));
    });

    // --- Delete Modal ---
    // The delete button is now inside the menu detail modal
    // Removed old handler: $('#menu-container').on('click', '.delete-btn', function() { ... });

    // Recalculate cost on ingredient change or quantity change
    $('#add-ingredients-container, #edit-ingredients-container').on('change input', '.ingredient-select, .ingredient-quantity', function() {
        const containerSelector = $(this).closest('#add-ingredients-container').length ? '#add-ingredients-container' : '#edit-ingredients-container';
        calculateTotalCost(containerSelector);
    });

    // Search functionality for menu items
    $('#menuSearch').on('input', function() {
        var searchTerm = $(this).val().toLowerCase();
        
        $('#menu-container .col-lg-4').each(function() {
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
    $('#clearMenuSearch').on('click', function() {
        $('#menuSearch').val('');
        $('#menuSearch').trigger('input');
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