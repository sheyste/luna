<?php include_once __DIR__ . '/layout/header.php'; ?>

<style>
    .status-field-highlight {
        background-color: #fff3cd; /* A light yellow color */
        border-radius: 5px;
        padding: 1rem;
        border: 1px solid #ffeeba;
    }

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
    #poTable td[data-label="Actions"] {
        display: flex;
        gap: 0.5rem; /* Add space between buttons */
        align-items: center;
    }

    /* Responsive table for mobile */
    @media (max-width: 767px) {
        #poTable thead {
            display: none;
        }

        #poTable, #poTable tbody, #poTable tr, #poTable td {
            display: block;
            width: 100%;
        }

        #poTable tr {
            margin-bottom: 1rem;
            border: 1px solid #ddd;
        }

        #poTable td {
            text-align: right;
            padding-left: 50%;
            position: relative;
            border: none;
            border-bottom: 1px solid #eee;
        }

        /* Specific styling for the Actions column in mobile view */
        #poTable td[data-label="Actions"] {
            text-align: left; /* Align buttons to the left */
            padding-left: 1rem; /* Adjust padding */
            flex-direction: column; /* Stack buttons vertically */
            gap: 0.5rem; /* Add space between buttons */
        }

        #poTable td[data-label="Actions"] button {
            width: 100%; /* Make buttons take full width */
            text-align: center; /* Center text within buttons */
            font-size: 0.8rem; /* Slightly larger font size */
            padding: 0.6rem 0.5rem; /* Adjust padding for a bigger feel */
        }

        #poTable td:last-of-type {
            border-bottom: 0;
        }

        #poTable td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            width: 45%;
            font-weight: bold;
            text-align: left;
        }

        /* Hide the "Actions" label in mobile view */
        #poTable td[data-label="Actions"]::before {
            content: "";
        }
    }
</style>

<style media="print">
    body * {
        visibility: hidden;
    }
    #viewPOModal * {
        visibility: visible;
    }
    .print-exclude {
        display: none;
    }
    #viewPOModal {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
        position: absolute;
        left: 0;
        top: 0;
    }
    .modal-dialog {
        max-width: 100%;
        margin: 0;
    }
    .modal-content {
        border: none;
    }
    .table-responsive {
        overflow: visible !important;
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Purchase Orders</h1>
</div>

<!-- Main Content Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Purchase Orders</h6>
<?php if ($_SESSION['user_type'] !== 'Inventory Staff'): ?>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addPOModal">
            <i class="fa fa-plus me-1"></i> Add Purchase Order
        </button>
<?php endif; ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="poTable" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Order Date</th>
                        <th>Expected Delivery</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grouped_orders = [];
                    foreach ($orders as $item) {
                        $po_id = $item['id'];
                        if (!isset($grouped_orders[$po_id])) {
                            $grouped_orders[$po_id] = [
                                'id' => $po_id,
                                'po_number' => $item['po_number'],
                                'supplier' => $item['supplier'],
                                'order_date' => $item['order_date'],
                                'expected_delivery' => $item['expected_delivery'],
                                'status' => $item['status'],
                                'total_amount' => 0,
                                'item_count' => 0
                            ];
                        }
                        $grouped_orders[$po_id]['total_amount'] += $item['quantity'] * $item['unit_price'];
                        $grouped_orders[$po_id]['item_count']++;
                    }

                    function getStatusRowClass($status) {
                        switch (strtolower($status)) {
                            case 'received':
                                return 'table-success';
                            case 'pending':
                                return 'table-warning';
                            case 'ordered':
                                return 'table-info';
                            case 'cancelled':
                                return 'table-danger';
                            default:
                                return '';
                        }
                    }

                    function getStatusBadgeClass($status) {
                        switch ($status) {
                            case 'Received':
                                return 'bg-success';
                            case 'Ordered':
                                return 'bg-info text-dark';
                            case 'Cancelled':
                                return 'bg-danger';
                            case 'Pending':
                            default:
                                return 'bg-warning text-dark';
                        }
                    }
                    ?>
                    <?php foreach ($grouped_orders as $order): ?>
                        <tr class="<?= getStatusRowClass($order['status']) ?> clickable-row" data-id="<?= $order['id'] ?>">
                            <td data-label="PO Number"><?= htmlspecialchars($order['po_number']) ?></td>
                            <td data-label="Supplier"><?= htmlspecialchars($order['supplier']) ?></td>
                            <td data-label="Items"><span class="badge bg-secondary"><?= htmlspecialchars($order['item_count']) ?></span></td>
                            <td data-label="Total Amount">&#8369;<?= htmlspecialchars(number_format($order['total_amount'], 2)) ?></td>
                            <td data-label="Order Date"><?= htmlspecialchars(date('F j, Y', strtotime($order['order_date']))) ?></td>
                            <td data-label="Expected Delivery"><?= $order['expected_delivery'] && $order['expected_delivery'] != '0000-00-00 00:00:00'
                                    ? htmlspecialchars(date('F j, Y', strtotime($order['expected_delivery'])))
                                    : 'N/A' ?></td>

                            <td data-label="Status"><span class="badge <?= getStatusBadgeClass($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span></td>
                            <td data-label="Actions">
                                <?php if ($order['status'] === 'Pending'): ?>
                                    <button class="btn btn-primary btn-sm status-btn" data-id="<?= $order['id'] ?>" data-action="ordered">
                                        <i class="fa fa-shopping-cart"></i> Order
                                    </button>
                                <?php elseif ($order['status'] === 'Ordered'): ?>
                                    <button class="btn btn-success btn-sm status-btn" data-id="<?= $order['id'] ?>" data-action="received">
                                        <i class="fa fa-truck"></i> Receive
                                    </button>
                                <?php elseif ($order['status'] === 'Received' || $order['status'] === 'Cancelled'): ?>
                                    <button class="btn btn-secondary btn-sm view-btn" data-id="<?= $order['id'] ?>">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                <?php endif; ?>
                                <?php if ($order['status'] !== 'Received' && $order['status'] !== 'Cancelled'): ?>
                                    <button class="btn btn-danger btn-sm status-btn" data-id="<?= $order['id'] ?>" data-action="cancelled">
                                        <i class="fa fa-times"></i> Cancel
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add PO Modal -->
<div class="modal fade" id="addPOModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="post" action="/purchase_order/add">
      <input type="hidden" name="status" value="Pending">
      <div class="modal-header">
        <h5 class="modal-title">Add Purchase Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6 mb-2">
                <label for="addPONumber">PO Number</label>
                <input type="text" id="addPONumber" name="po_number" class="form-control" required >
            </div>
            <div class="col-md-6 mb-2">
                <label for="addSupplier">Supplier</label>
                <input type="text" id="addSupplier" name="supplier" class="form-control" required>
            </div>
            <div class="col-md-6 mb-2">
                <label for="addOrderDate">Order Date</label>
                <input type="date" id="addOrderDate" name="order_date" class="form-control" required>
            </div>
            <div class="col-md-6 mb-2">
                <label for="addExpected">Expected Delivery</label>
                <input type="date" id="addExpected" name="expected_delivery" class="form-control" required>
            </div>
        </div>
        <hr>
        <h6 class="mt-3">Items</h6>
        <div id="po-items-container">
            <!-- Item rows will be added here by JavaScript -->
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-po-item-btn">
            <i class="fa fa-plus"></i> Add Item
        </button>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Add Purchase Order</button>
      </div>
    </form>
  </div>
</div>

<!-- Hidden template for PO item row -->
<div id="po-item-template" style="display: none;">
    <div class="row align-items-end po-item-row mb-2 g-2">
        <input type="hidden" name="items[0][id]" value="">
        <div class="col-md-4"><label>Item</label><select name="items[0][inventory_id]" class="form-select item-select" required><option value="" selected disabled>-- Select Item --</option></select></div>
        <div class="col-md-3">
            <label>Quantity</label>
            <div class="input-group">
                <input type="number" name="items[0][quantity]" class="form-control" required min="1">
                <span class="input-group-text item-unit">--</span>
            </div>
        </div>
        <div class="col-md-2 received-qty-container" style="display: none;"><label>Received Qty</label><input type="number" name="items[0][received_quantity]" class="form-control" step="0.01" min="0"></div>
        <div class="col-md-3"><button type="button" class="btn btn-danger remove-po-item-btn w-100">Remove</button></div>
    </div>
</div>

<!-- Quantity Warning Modal -->
<div class="modal fade" id="quantityWarningModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark">
                    <i class="fas fa-exclamation-triangle me-2"></i>Inventory Limit Warning
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>Warning:</strong> The quantity you entered will exceed the maximum inventory limit for this item.
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Item:</strong> <span id="warningItemName"></span></p>
                        <p><strong>Entered Quantity:</strong> <span id="warningEnteredQty"></span> units</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Current Stock:</strong> <span id="warningCurrentQty"></span> units</p>
                        <p><strong>Maximum Capacity:</strong> <span id="warningMaxQty"></span> units</p>
                        <p><strong>After Addition:</strong> <span id="warningAfterAddition"></span> units</p>
                    </div>
                </div>

                <hr>
                <div class="alert alert-info">
                    <strong>Recommended Action:</strong>
                    <p>To stay within the inventory limit, consider reducing the quantity to:</p>
                    <p class="fw-bold text-primary fs-5" id="warningRecommendedQty"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Adjust Quantity</button>
            </div>
        </div>
    </div>
</div>

<!-- View PO Modal -->
<div class="modal fade" id="viewPOModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">SOL DEL LUNA - Inventory </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">PO Number</h6>
                        <p class="fs-5 fw-bold" id="viewPO"></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Supplier</h6>
                        <p class="fs-5" id="viewSupplier"></p>
                    </div>
                </div>

                <h6 class="text-muted mb-2">Items</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Unit Price</th>
                                <th>Received Qty</th>
                                <th>Line Total</th>
                            </tr>
                        </thead>
                        <tbody id="view-items-container">
                            <!-- JS will populate this -->
                        </tbody>
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                         <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Order Date:</span>
                            <strong id="viewOrderDate"></strong>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Expected Delivery:</span>
                            <strong id="viewExpected"></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between py-2" id="updatedDateRow">
                            <span class="text-muted">Updated Date:</span>
                            <strong id="viewUpdatedDate"></strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                            <h6 class="mb-0">Status</h6>
                            <span id="viewStatus" class="badge fs-6"></span>
                        </div>
                        <div class="d-flex justify-content-between p-3">
                            <h5 class="mb-0">Grand Total</h5>
                            <h5 class="mb-0 fw-bold">&#8369;<span id="viewGrandTotal"></span></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-outline-secondary print-exclude" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary print-exclude" onclick="window.print()">Print</button>
            </div>
        </div>
    </div>
</div>



<?php include_once __DIR__ . '/layout/footer.php'; ?>

<script>
const inventoryData = <?= json_encode($inventory) ?>;

$(document).ready(function() {
    // Custom sorting for status
    $.fn.dataTable.ext.type.order['status-order-pre'] = function (d) {
        // Extract text content if d is HTML
        var text = typeof d === 'string' && d.indexOf('<') !== -1 ? $(d).text() : d;
        switch (text.toLowerCase()) {
            case 'pending': return 1;
            case 'ordered': return 2;
            case 'received': return 3;
            case 'cancelled': return 4;
            default: return 5;
        }
    };

    var table = $('#poTable').DataTable({
        "columnDefs": [
            {
                "type": "status-order",
                "targets": 6
            }
        ],
        "order": [[6, "asc"]]
    });

    const viewPOModal = new bootstrap.Modal(document.getElementById('viewPOModal'));
    const addPOModalEl = document.getElementById('addPOModal');

    $('#addPOModal form').on('submit', function(e) {
        if ($('#po-items-container .po-item-row').length === 0) {
            e.preventDefault();
            alert('Please add at least one item to the purchase order.');
            return false;
        }
    });
    let itemIndex = 0;

    function populateItemSelect(selectElement, selectedValue = '') {
        const currentSelectedItems = getSelectedItemIds();
        // Remove the selected value of this select if it's currently selected, so it doesn't filter itself out if already selected
        if (selectedValue && currentSelectedItems.includes(selectedValue)) {
            const index = currentSelectedItems.indexOf(selectedValue);
            if (index > -1) {
                currentSelectedItems.splice(index, 1);
            }
        }
        $(selectElement).empty();
        $(selectElement).append('<option value="" selected disabled>-- Select Item --</option>');
        inventoryData.forEach(item => {
            if (!currentSelectedItems.includes(item.id.toString())) {
                const option = $('<option></option>');
                option.val(item.id);
                option.text(item.name);
                option.data('unit', item.unit || 'unit');
                option.data('price', item.price || '0.00');
                option.data('max-quantity', item.max_quantity || '0');
                option.data('current-quantity', item.quantity || '0');
                if (selectedValue == item.id) {
                    option.prop('selected', true);
                }
                $(selectElement).append(option);
            }
        });
    }

    function getSelectedItemIds() {
        const selectedIds = [];
        $('#po-items-container .item-select').each(function() {
            const val = $(this).val();
            if (val) {
                selectedIds.push(val);
            }
        });
        return selectedIds;
    }

    function addPOItemRow() {
        const template = $('#po-item-template').html();
        const newRow = $(template);
        
        newRow.find('[name]').each(function() {
            const name = $(this).attr('name').replace(/\[0\]/, `[${itemIndex}]`);
            $(this).attr('name', name);
        });
        
        $('#po-items-container').append(newRow);
        const selectEl = newRow.find('.item-select');
        populateItemSelect(selectEl[0]);
        itemIndex++;
    }

    function updateAllSelects() {
        $('#po-items-container .item-select').each(function() {
            const currentVal = $(this).val();
            populateItemSelect(this, currentVal);
        });
    }

    addPOModalEl.addEventListener('shown.bs.modal', function () {
        // Auto-generate PO Number
        const timestamp = new Date().getTime();
        $('#addPONumber').val(`PO-${timestamp}`);

        const today = new Date().toISOString().split('T')[0];
        $('#addOrderDate').val(today);
        
        $('#po-items-container').empty();
        itemIndex = 0;
        addPOItemRow();
    });

    $('#add-po-item-btn').on('click', addPOItemRow);

    $('#po-items-container').on('click', '.remove-po-item-btn', function() {
        $(this).closest('.po-item-row').remove();
        updateAllSelects(); // Re-enable options in other selects
    });

    function updateItemInfo(selectElement) {
        const selectedOption = $(selectElement).find('option:selected');
        const unit = selectedOption.data('unit');

        const unitSpan = $(selectElement).closest('.po-item-row').find('.item-unit');

        if ($(selectElement).val() && unit) {
            unitSpan.text(unit);
        } else {
            unitSpan.text('--');
        }
    }

    function checkQuantityLimit(inputElement) {
        const quantityInput = $(inputElement);
        const quantity = parseFloat(quantityInput.val()) || 0;
        const itemSelect = quantityInput.closest('.po-item-row').find('.item-select');
        const selectedOption = itemSelect.find('option:selected');

        if (selectedOption.val()) {
            const currentQuantity = parseFloat(selectedOption.data('current-quantity')) || 0;
            const maxQuantity = parseFloat(selectedOption.data('max-quantity')) || 0;

            if (currentQuantity + quantity > maxQuantity) {
                const recommendedQuantity = Math.max(0, maxQuantity - currentQuantity);

                // Populate warning modal
                $('#warningItemName').text(selectedOption.text());
                $('#warningEnteredQty').text(quantity);
                $('#warningCurrentQty').text(currentQuantity);
                $('#warningMaxQty').text(maxQuantity);
                $('#warningAfterAddition').text(currentQuantity + quantity);
                $('#warningRecommendedQty').text(`${recommendedQuantity} units`);

                // Show modal without adding additional backdrop (since Add PO modal is already open)
                const warningModal = new bootstrap.Modal(document.getElementById('quantityWarningModal'), {
                    backdrop: false
                });
                warningModal.show();
            }
        }
    }

    $('#po-items-container').on('change', '.item-select', function() {
        updateItemInfo(this);
        updateAllSelects(); // Update other selects when this selection changes
        // Check quantity limit when item changes if quantity is already entered
        const quantityInput = $(this).closest('.po-item-row').find('input[name*="[quantity]"]');
        if (quantityInput.val()) {
            checkQuantityLimit(quantityInput[0]);
        }
    });

    $('#po-items-container').on('input', 'input[name*="[quantity]"]', function() {
        checkQuantityLimit(this);
    });

    $('#po-items-container').on('change', 'input[name*="[quantity]"]', function() {
        checkQuantityLimit(this);
    });

    function populateViewModal(data) {
        $('#viewPO').text(data.po_number);
        $('#viewSupplier').text(data.supplier);
        $('#viewOrderDate').text(formatDate(data.order_date));
        $('#viewExpected').text(formatDate(data.expected_delivery));

        const $statusBadge = $('#viewStatus');
        $statusBadge.text(data.status);
        $statusBadge.removeClass('bg-success bg-info bg-danger bg-warning text-dark')
                    .addClass(getStatusBadgeClass(data.status));

        // Handle updated date display - show for all purchase orders
        $('#viewUpdatedDate').text(data.updated_at ? formatDate(data.updated_at.split(' ')[0]) : 'N/A'); // Extract date part only

        const itemsContainer = $('#view-items-container');
        itemsContainer.empty();
        let grandTotal = 0;

        if (data.items && data.items.length > 0) {
            data.items.forEach(function(item) {
                const quantity = parseFloat(item.quantity);
                const unitPrice = parseFloat(item.unit_price);
                const lineTotal = quantity * unitPrice;
                grandTotal += lineTotal;

                const row = $('<tr>');
                row.append($('<td>').text(item.item_name));
                row.append($('<td>').text(quantity));
                row.append($('<td>').text(item.unit || 'N/A'));
                row.append($('<td>').text(unitPrice.toFixed(2)));
                row.append($('<td>').text(item.received_quantity || 0));
                row.append($('<td>').text(lineTotal.toFixed(2)));

                itemsContainer.append(row);
            });
        }

        $('#viewGrandTotal').text(grandTotal.toFixed(2));
    }

    function fetchPOData(poId, callback) {
        $.getJSON(`/purchase_order/get?id=${poId}`, function(data) {
            if (callback) callback(data);
        }).fail(function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
        });
    }

    function formatDate(dateString) {
        if (!dateString || dateString.indexOf('-') === -1) return dateString;
        const parts = dateString.split('-');
        return `${parts[1]}/${parts[2]}/${parts[0]}`;
    }

    function getStatusBadgeClass(status) {
        switch (status) {
            case 'Received':
                return 'bg-success';
            case 'Ordered':
                return 'bg-info text-dark';
            case 'Cancelled':
                return 'bg-danger';
            case 'Pending':
            default:
                return 'bg-warning text-dark';
        }
    }

    function getStatusRowClass(status) {
        switch (status.toLowerCase()) {
            case 'received':
                return 'table-success';
            case 'pending':
                return 'table-warning';
            case 'ordered':
                return 'table-info';
            case 'cancelled':
                return 'table-danger';
            default:
                return '';
        }
    }

    // Handle status button clicks
    $('#poTable').on('click', '.status-btn', function() {
        var poId = $(this).data('id');
        var action = $(this).data('action');
        var confirmMessage = '';

        switch (action) {
            case 'ordered':
                confirmMessage = 'Are you sure you want to mark this purchase order as Ordered?';
                break;
            case 'received':
                confirmMessage = 'Are you sure you want to mark this purchase order as Received?';
                break;
            case 'cancelled':
                confirmMessage = 'Are you sure you want cancel this purchase order?';
                break;
        }

        if (confirm(confirmMessage)) {
            // Show loading state
            $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '/purchase_order/updateStatus',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    id: poId,
                    status: action.charAt(0).toUpperCase() + action.slice(1) // Capitalize first letter
                }),
                success: function(response) {
                    if (response.success) {
                        // Update the status badge and row styling
                        var $row = $('#poTable tbody tr[data-id="' + poId + '"]');
                        var $statusCell = $row.find('td[data-label="Status"] span.badge');

                        // Remove current classes and add new ones
                        $statusCell.removeClass('bg-success bg-info bg-danger bg-warning text-dark')
                                  .addClass(getStatusBadgeClass(response.status))
                                  .text(response.status);

                        // Update row classes
                        $row.removeClass('table-success table-warning table-info table-danger')
                            .addClass(getStatusRowClass(response.status));

                        // Update actions buttons
                        var actionsHtml = '';
                        if (response.status === 'Pending') {
                            actionsHtml = `
                                <button class="btn btn-primary btn-sm status-btn" data-id="${poId}" data-action="ordered">
                                    <i class="fa fa-shopping-cart"></i> Order
                                </button>
                            `;
                        } else if (response.status === 'Ordered') {
                            actionsHtml = `
                                <button class="btn btn-success btn-sm status-btn" data-id="${poId}" data-action="received">
                                    <i class="fa fa-truck"></i> Receive
                                </button>
                            `;
                        }
                        actionsHtml += `
                            <button class="btn btn-danger btn-sm status-btn" data-id="${poId}" data-action="cancelled">
                                <i class="fa fa-times"></i> Cancel
                            </button>
                        `;

                        $row.find('td[data-label="Actions"]').html(actionsHtml);

                        // Refresh the DataTable
                        table.row($row).invalidate().draw(false);

                        alert('Purchase order status updated successfully!');
                    } else {
                        alert('Error updating status: ' + (response.message || 'Unknown error'));
                        // Re-enable button
                        $(this).prop('disabled', false).html(originalText);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error updating status: ' + error);
                    // Re-enable button
                    $(this).prop('disabled', false).html(originalText);
                }
            });
        } else {
            // Enable button if canceled
            $(this).prop('disabled', false);
        }
    });

    // Handle view button click
    $('#poTable').on('click', '.view-btn', function() {
        var id = $(this).data('id');
        fetchPOData(id, function(data) {
            populateViewModal(data);
            viewPOModal.show();
        });
    });

    // Handle clickable row click (for viewing)
    $('#poTable').on('click', '.clickable-row', function(e) {
        if ($(e.target).closest('button').length) {
            return;
        }

        var id = $(this).data('id');
        fetchPOData(id, function(data) {
            populateViewModal(data);
            viewPOModal.show();
        });
    });
});


</script>
