<?php include_once __DIR__ . '/layout/header.php'; ?>

<style>
    .status-field-highlight {
        background-color: #fff3cd; /* A light yellow color */
        border-radius: 5px;
        padding: 1rem;
        border: 1px solid #ffeeba;
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
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Purchase Orders</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Purchase Orders</li>
        </ol>
    </nav>
</div>

<!-- Main Content Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Purchase Orders</h6>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addPOModal">
            <i class="fa fa-plus me-1"></i> Add Purchase Order
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="poTable" width="100%">
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
                    ?>
                    <?php foreach ($grouped_orders as $order): ?>
                        <tr class="<?= getStatusRowClass($order['status']) ?>">
                            <td data-label="PO Number"><?= htmlspecialchars($order['po_number']) ?></td>
                            <td data-label="Supplier"><?= htmlspecialchars($order['supplier']) ?></td>
                            <td data-label="Items"><span class="badge bg-secondary"><?= htmlspecialchars($order['item_count']) ?></span></td>
                            <td data-label="Total Amount">&#8369;<?= htmlspecialchars(number_format($order['total_amount'], 2)) ?></td>
                            <td data-label="Order Date"><?= htmlspecialchars(date('F j, Y', strtotime($order['order_date']))) ?></td>
                            <td data-label="Expected Delivery"><?= $order['expected_delivery'] && $order['expected_delivery'] != '0000-00-00 00:00:00' 
                                    ? htmlspecialchars(date('F j, Y', strtotime($order['expected_delivery']))) 
                                    : 'N/A' ?></td>

                            <td data-label="Status"><?= htmlspecialchars($order['status']) ?></td>
                            <td data-label="Actions" class="text-nowrap">
                                <button class="btn btn-primary btn-sm view-btn" data-id="<?= $order['id'] ?>">
                                    <i class="fa fa-eye"></i> View
                                </button>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $order['id'] ?>">
                                    <i class="fa fa-edit"></i> Update
                                </button>
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
                <input type="date" id="addExpected" name="expected_delivery" class="form-control">
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
        <div class="col-md-4"><label>Item</label><select name="items[0][inventory_id]" class="form-select item-select" required><option value="" selected disabled>-- Select Item --</option><?php foreach($inventory as $item): ?><option value="<?= $item['id'] ?>" data-unit="<?= htmlspecialchars($item['unit'] ?? 'unit') ?>"><?= htmlspecialchars($item['name']) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-2">
            <label>Quantity</label>
            <div class="input-group">
                <input type="number" name="items[0][quantity]" class="form-control" required min="1">
                <span class="input-group-text item-unit">--</span>
            </div>
        </div>
        <div class="col-md-2">
            <label>Price (Per Unit)</label>
            <input type="number" name="items[0][unit_price]" class="form-control" step="0.01" required min="0">
        </div>
        <div class="col-md-2 received-qty-container" style="display: none;"><label>Received Qty</label><input type="number" name="items[0][received_quantity]" class="form-control" step="0.01" min="0"></div>
        <div class="col-md-2"><button type="button" class="btn btn-danger remove-po-item-btn w-100">Remove</button></div>
    </div>
</div>

<!-- View PO Modal -->
<div class="modal fade" id="viewPOModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Purchase Order Details</h5>
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
                         <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Order Date</span>
                            <strong id="viewOrderDate"></strong>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Expected Delivery</span>
                            <strong id="viewExpected"></strong>
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
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit PO Modal -->
<div class="modal fade" id="editPOModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="post" action="/purchase_order/edit">
      <input type="hidden" id="editPOId" name="id">
      <div class="modal-header">
        <h5 class="modal-title">Edit Purchase Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6 mb-2">
                <label for="editPONumber">PO Number</label>
                <input type="text" id="editPONumber" name="po_number" class="form-control" required>
            </div>
            <div class="col-md-6 mb-2">
                <label for="editSupplier">Supplier</label>
                <input type="text" id="editSupplier" name="supplier" class="form-control" required>
            </div>
            <div class="col-md-6 mb-2">
                <label for="editOrderDate">Order Date</label>
                <input type="date" id="editOrderDate" name="order_date" class="form-control" required>
            </div>
            <div class="col-md-6 mb-2">
                <label for="editExpected">Expected Delivery</label>
                <input type="date" id="editExpected" name="expected_delivery" class="form-control">
            </div>
            <div class="col-md-12 mb-2 status-field-highlight">
                <label for="editStatus">Status</label>
                <select id="editStatus" name="status" class="form-select">
                    <option value="Pending">Pending</option>
                    <option value="Ordered">Ordered</option>
                    <option value="Received">Received</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mt-3">Items</h6>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="edit-add-po-item-btn">
                <i class="fa fa-plus"></i> Add Item
            </button>
        </div>
        <div id="edit-po-items-container" class="mt-2">
            <!-- Item rows will be populated by JavaScript -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<?php include_once __DIR__ . '/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    // Custom sorting for status
    $.fn.dataTable.ext.type.order['status-order-pre'] = function (d) {
        switch (d) {
            case 'Pending': return 1;
            case 'Ordered': return 2;
            case 'Received': return 3;
            case 'Cancelled': return 4;
            default: return 5;
        }
    };

    var table = $('#poTable').DataTable({
        "columnDefs": [
            { "type": "status-order", "targets": 6 }
        ],
        "order": [[6, "asc"]]
    });

    const viewPOModal = new bootstrap.Modal(document.getElementById('viewPOModal'));
    const editPOModal = new bootstrap.Modal(document.getElementById('editPOModal'));
    const addPOModalEl = document.getElementById('addPOModal');

    $('#addPOModal form').on('submit', function(e) {
        if ($('#po-items-container .po-item-row').length === 0) {
            e.preventDefault();
            alert('Please add at least one item to the purchase order.');
            return false;
        }
    });

    $('#editPOModal form').on('submit', function(e) {
        if ($('#edit-po-items-container .po-item-row').length === 0) {
            e.preventDefault();
            alert('A purchase order must have at least one item.');
            return false;
        }

        if ($('#editStatus').val() === 'Received') {
            let allReceivedQuantitiesFilled = true;
            $('#edit-po-items-container .po-item-row').each(function() {
                const receivedQtyInput = $(this).find('input[name*="[received_quantity]"]');
                if (receivedQtyInput.val() === '') {
                    allReceivedQuantitiesFilled = false;
                    // Highlight the empty field
                    receivedQtyInput.addClass('is-invalid');
                } else {
                    receivedQtyInput.removeClass('is-invalid');
                }
            });

            if (!allReceivedQuantitiesFilled) {
                e.preventDefault();
                // Use a more noticeable warning, perhaps a custom modal or a toast notification
                alert('Please fill in all "Received Qty" fields before marking as received.');
                return false;
            }
        }
    });

    let editItemIndex = 0;
    let itemIndex = 0;

    function addPOItemRow() {
        const template = $('#po-item-template').html();
        const newRow = $(template);
        
        newRow.find('[name]').each(function() {
            const name = $(this).attr('name').replace(/\[0\]/, `[${itemIndex}]`);
            $(this).attr('name', name);
        });
        
        $('#po-items-container').append(newRow);
        itemIndex++;
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
    });

    function addPOItemRowToEditModal() {
        const template = $('#po-item-template').html();
        const newRow = $(template);
        
        newRow.find('[name]').each(function() {
            const name = $(this).attr('name').replace(/\[0\]/, `[new_${editItemIndex}]`);
            $(this).attr('name', name);
        });
        
        newRow.find('input[name*="[id]"]').val('');
        $('#edit-po-items-container').append(newRow);
        editItemIndex++;
    }

    $('#edit-add-po-item-btn').on('click', addPOItemRowToEditModal);

    $('#edit-po-items-container').on('click', '.remove-po-item-btn', function() {
        const row = $(this).closest('.po-item-row');
        const itemIdInput = row.find('input[name*="[id]"]');
        
        if (itemIdInput.val()) {
            const form = $('#editPOModal form');
            form.append(`<input type="hidden" name="deleted_items[]" value="${itemIdInput.val()}">`);
        }
        
        row.remove();
    });

    function updateItemUnit(selectElement) {
        const selectedOption = $(selectElement).find('option:selected');
        const unit = selectedOption.data('unit');
        const unitSpan = $(selectElement).closest('.po-item-row').find('.item-unit');
        if ($(selectElement).val() && unit) {
            unitSpan.text(unit);
        } else {
            unitSpan.text('--');
        }
    }

    $('#po-items-container, #edit-po-items-container').on('change', '.item-select', function() {
        updateItemUnit(this);
    });

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

    // View PO
    $('#poTable').on('click', '.view-btn', function() {
        var id = $(this).data('id');
        fetchPOData(id, function(data) {
            $('#viewPO').text(data.po_number);
            $('#viewSupplier').text(data.supplier);
            $('#viewOrderDate').text(formatDate(data.order_date));
            $('#viewExpected').text(formatDate(data.expected_delivery));

            const $statusBadge = $('#viewStatus');
            $statusBadge.text(data.status);
            $statusBadge.removeClass('bg-success bg-info bg-danger bg-warning text-dark')
                        .addClass(getStatusBadgeClass(data.status));

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

            viewPOModal.show();
        });
    });

    // Edit PO
    $('#poTable').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        fetchPOData(id, function(data) {
            $('#editPOId').val(data.id); // Main PO ID
            $('#editPONumber').val(data.po_number);
            $('#editSupplier').val(data.supplier);
            $('#editOrderDate').val(data.order_date);
            $('#editExpected').val(data.expected_delivery);
            $('#editStatus').val(data.status).trigger('change');

            const itemsContainer = $('#edit-po-items-container');
            itemsContainer.empty();
            $('#editPOModal input[name="deleted_items[]"]').remove();
            editItemIndex = 0;

            if (data.items && data.items.length > 0) {
                data.items.forEach(function(item) {
                    const template = $('#po-item-template').html();
                    const newRow = $(template);

                    newRow.find('[name]').each(function() {
                        const name = $(this).attr('name').replace(/\[0\]/, `[${item.id}]`);
                        $(this).attr('name', name);
                    });

                    newRow.find('input[name*="[id]"]').val(item.id);
                    newRow.find('select[name*="[inventory_id]"]').val(item.inventory_id);
                    newRow.find('input[name*="[quantity]"]').val(item.quantity);
                    newRow.find('input[name*="[unit_price]"]').val(item.unit_price);
                    newRow.find('input[name*="[received_quantity]"]').val(item.received_quantity || '');
                    
                    updateItemUnit(newRow.find('.item-select'));
                    itemsContainer.append(newRow);
                });
            }

            editPOModal.show();
        });
    });

    $('#editStatus').on('change', function() {
        const showReceived = $(this).val() === 'Received';
        $('#edit-po-items-container .received-qty-container').toggle(showReceived);
    });
});
</script>
