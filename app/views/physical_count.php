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

    /* Actions column styling for all screen sizes */
    #inventoryTable td[data-label="Actions"] {
        display: flex;
        gap: 0.5rem; /* Add space between buttons */
        align-items: center;
    }

    /* Responsive table for mobile */
    @media (max-width: 767px) {
        #physicalCountTable thead {
            display: none;
        }

        #physicalCountTable, #physicalCountTable tbody, #physicalCountTable tr, #physicalCountTable td {
            display: block;
            width: 100%;
        }

        #physicalCountTable tr {
            margin-bottom: 1rem;
            border: 1px solid #ddd;
        }

        #physicalCountTable td {
            text-align: right;
            padding-left: 50%;
            position: relative;
            border: none;
            border-bottom: 1px solid #eee;
        }

        #physicalCountTable td:last-of-type {
            border-bottom: 0;
        }

        #physicalCountTable td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            width: 45%;
            font-weight: bold;
            text-align: left;
        }

        /* Hide the "Actions" label in mobile view */
        #physicalCountTable td[data-label="Actions"]::before {
            content: "";
        }
    }

    /* Modern Edit Inventory Modal Styles */
    .modern-modal .modal-dialog {
        max-width: 600px;
        margin: 1.75rem auto;
    }

    .modern-modal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    }

    .modern-modal .modal-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 1.5rem 2rem;
        border: none;
        position: relative;
    }

    .modern-modal .modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    /* Green theme for Add Inventory Modal */
    .modern-modal.add-modal .modal-header {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    }

    .modern-modal.add-modal .btn-primary {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .modern-modal.add-modal .btn-primary:hover {
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }

    .modern-modal.add-modal .form-control:focus,
    .modern-modal.add-modal .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .modern-modal .modal-title {
        font-weight: 600;
        font-size: 1.25rem;
        position: relative;
        z-index: 1;
        margin: 0;
    }

    .modern-modal .btn-close {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        position: relative;
        z-index: 1;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .modern-modal .btn-close:hover {
        opacity: 1;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .modern-modal .btn-close::after {
        content: "";
    }

    .modern-modal .btn-close i {
        color: white;
        font-size: 1.2rem;
    }

    .modern-modal .modal-body {
        padding: 2rem;
        background: white;
    }

    .modern-modal .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-modal .form-control,
    .modern-modal .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .modern-modal .form-control:focus,
    .modern-modal .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        background: white;
        transform: translateY(-1px);
    }

    .modern-modal .form-control:hover,
    .modern-modal .form-select:hover {
        border-color: #ced4da;
        background: white;
    }

    .modern-modal .mb-3 {
        margin-bottom: 1.5rem !important;
    }

    .modern-modal .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .modern-modal .btn {
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .modern-modal .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .modern-modal .btn:hover::before {
        left: 100%;
    }

    .modern-modal .btn-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
    }

    .modern-modal .btn-primary:hover {
        background: linear-gradient(135deg, #224abe 0%, #4e73df 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
        color: white;
    }

    .modern-modal .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .modern-modal .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }

    /* Mobile Responsiveness */
    @media (max-width: 576px) {
        .modern-modal .modal-dialog {
            margin: 0.5rem;
            max-width: none;
        }

        .modern-modal .modal-header {
            padding: 1rem 1.5rem;
        }

        .modern-modal .modal-body {
            padding: 1.5rem;
        }

        .modern-modal .modal-footer {
            padding: 1rem 1.5rem;
            flex-direction: column;
        }

        .modern-modal .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .modern-modal .form-control,
        .modern-modal .form-select {
            padding: 0.625rem 0.875rem;
            font-size: 0.9rem;
        }
    }

    /* Animation for modal appearance */
    .modern-modal.fade .modal-dialog {
        transform: scale(0.9) translateY(-20px);
        transition: transform 0.3s ease-out;
    }

    .modern-modal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }

    /* Input group styling for barcode */
    .modern-modal .input-group {
        position: relative;
    }

    .modern-modal .input-group .btn {
        border-radius: 0 8px 8px 0;
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
    }

    .modern-modal .input-group .form-control {
        border-radius: 8px 0 0 8px;
        border-right: none;
    }

    .modern-modal .input-group .form-control:focus {
        border-right: 2px solid #4e73df;
    }

    /* Form text styling */
    .modern-modal .form-text {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
        color: #495057;
    }

    .dataTables_wrapper .dataTables_length select:focus,
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        background: white;
        outline: none;
    }

    .dataTables_wrapper .dataTables_length select:hover,
    .dataTables_wrapper .dataTables_filter input:hover {
        border-color: #ced4da;
        background: white;
    }

    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label {
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .dataTables_wrapper .dataTables_info {
        font-size: 0.875rem;
        color: #6c757d;
        padding-top: 1rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        padding: 0.375rem 0.75rem;
        margin: 0 0.125rem;
        background: white;
        color: #495057;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f8f9fa;
        border-color: #ced4da;
        color: #495057;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #4e73df;
        border-color: #4e73df;
        color: white;
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.3);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        background: #f8f9fa;
        border-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
    }

    /* Custom Excel Export Button Styling */
    .dt-buttons .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        transition: all 0.3s ease;
    }

    .dt-buttons .btn-success:hover {
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .dt-buttons .btn-success:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    /* DataTables Controls Layout */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }

    .dataTables_wrapper .dataTables_filter {
        text-align: right;
    }

    @media (max-width: 768px) {
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            text-align: left;
            margin-bottom: 0.5rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 100%;
            margin-top: 0.25rem;
        }

        .dt-buttons {
            margin-bottom: 1rem;
        }

        .dt-buttons .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Physical Count Entry (Inventory Items)</h1>
</div>

<!-- Add Physical Count Button -->
<div class="mb-4">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPhysicalCountModal">
        <i class="fa fa-plus"></i> Add Physical Count
    </button>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Discrepancies</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalDiscrepancies">0</div>
                        <div class="text-xs text-muted">Products with variance</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Value at Risk</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="valueAtRisk">₱0.00</div>
                        <div class="text-xs text-muted">Total discrepancy value</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Overages</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="overages">0</div>
                        <div class="text-xs text-muted">Physical > System</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Shortages</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="shortages">0</div>
                        <div class="text-xs text-muted">Physical < System</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Physical Count Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Physical Count Results</h6>
        <div class="d-flex gap-2">
            <?php if ($_SESSION['user_type'] !== 'Inventory Staff'): ?>
            <button id="saveToInventoryBtn" class="btn btn-success" disabled>Save All to Inventory</button>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="physicalCountTable" width="100%" cellspacing="0">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>System Count</th>
                        <th>Physical Count</th>
                        <th>Difference</th>
                        <th>Variance %</th>
                        <th>Value Impact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Physical Count Modal -->
<div class="modal fade modern-modal" id="addPhysicalCountModal" tabindex="-1" aria-labelledby="addPhysicalCountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPhysicalCountModalLabel">
                    <i class="bi bi-clipboard-list me-2"></i>Add Physical Count
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
            </div>
            <div class="modal-body">
                <form id="physicalCountForm">
                    <!-- Item Selection Card -->
                    <div class="card border-0 bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title text-primary mb-3">
                                <i class="bi bi-box me-2"></i>Item Selection
                            </h6>
                            <div class="mb-3">
                                <label for="itemSelect" class="form-label">SELECT ITEM</label>
                                <select class="form-select form-select-lg" id="itemSelect" required>
                                    <option value="">Choose an inventory item...</option>
                                    <?php if (!empty($items)): ?>
                                        <?php foreach ($items as $item): ?>
                                            <option value="<?= htmlspecialchars($item['id']) ?>"
                                                    data-name="<?= htmlspecialchars($item['name']) ?>"
                                                    data-system="<?= htmlspecialchars($item['quantity']) ?>"
                                                    data-price="<?= htmlspecialchars($item['price']) ?>"
                                                    data-unit="<?= htmlspecialchars($item['unit']) ?>">
                                                <?= htmlspecialchars($item['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Selected Item Info -->
                            <div id="selectedItemInfo" class="d-none">
                                <div class="alert alert-info border-0 bg-info bg-opacity-10">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-muted">Item Name</small>
                                            <div class="fw-bold" id="selectedItemName">-</div>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Current Stock</small>
                                            <div class="fw-bold text-primary" id="selectedItemStock">-</div>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Unit</small>
                                            <div class="fw-bold" id="selectedItemUnit">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Count Entry Card -->
                    <div class="card border-0 bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title text-primary mb-3">
                                <i class="bi bi-calculator me-2"></i>Physical Count Entry
                            </h6>
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label for="systemCount" class="form-label">SYSTEM COUNT</label>
                                    <input type="text" class="form-control form-control-lg bg-white" id="systemCount" readonly placeholder="Select item first">
                                </div>
                                <div class="col-md-6">
                                    <label for="physicalCount" class="form-label">PHYSICAL COUNT</label>
                                    <input type="number" step="0.01" class="form-control form-control-lg" id="physicalCount" min="0" required placeholder="Enter counted amount">
                                </div>
                            </div>

                            <!-- Difference Preview -->
                            <div id="differencePreview" class="mt-3 d-none">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="p-3 rounded bg-white border">
                                            <small class="text-muted">Difference Preview</small>
                                            <div class="fw-bold fs-5" id="previewDifference">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
                <button type="submit" form="physicalCountForm" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Add to Count List
                </button>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/layout/footer.php' ?>

<script>
    $(document).ready(function() {
        // Load pending entries on page load
        loadPendingEntries();
        
        // Handle item selection change
        $('#itemSelect').on('change', function() {
            const selectedOption = $(this).find(':selected');
            const itemId = $(this).val();
            
            if (itemId) {
                const itemName = selectedOption.data('name');
                const systemCount = selectedOption.data('system');
                const unit = selectedOption.data('unit');
                
                // Show selected item info
                $('#selectedItemInfo').removeClass('d-none');
                $('#selectedItemName').text(itemName);
                $('#selectedItemStock').text(systemCount + ' ' + unit);
                $('#selectedItemUnit').text(unit);
                
                // Set system count
                $('#systemCount').val(systemCount + ' ' + unit);
                
                // Enable physical count input
                $('#physicalCount').prop('disabled', false).focus();
            } else {
                // Hide selected item info
                $('#selectedItemInfo').addClass('d-none');
                $('#systemCount').val('');
                $('#physicalCount').prop('disabled', true).val('');
                $('#differencePreview').addClass('d-none');
            }
        });
        
        // Handle physical count input change for difference preview
        $('#physicalCount').on('input', function() {
            const selectedOption = $('#itemSelect').find(':selected');
            const systemCount = parseFloat(selectedOption.data('system')) || 0;
            const physicalCount = parseFloat($(this).val()) || 0;
            
            if (systemCount > 0 && physicalCount >= 0) {
                const difference = physicalCount - systemCount;
                let differenceClass = 'text-dark';
                let differenceIcon = 'fa-equals';
                
                if (difference > 0) {
                    differenceClass = 'text-success';
                    differenceIcon = 'fa-arrow-up';
                } else if (difference < 0) {
                    differenceClass = 'text-danger';
                    differenceIcon = 'fa-arrow-down';
                }
                
                $('#differencePreview').removeClass('d-none');
                $('#previewDifference').html(`
                    <i class="fas ${differenceIcon} me-2"></i>
                    <span class="${differenceClass}">${difference > 0 ? '+' : ''}${difference.toFixed(2)}</span>
                `);
            } else {
                $('#differencePreview').addClass('d-none');
            }
        });
        
        // Handle form submission - Add to count list (save to database)
        $('#physicalCountForm').on('submit', function(e) {
            e.preventDefault();
            
            const selectedItem = $('#itemSelect').find(':selected');
            const itemId = $('#itemSelect').val();
            const physicalCount = parseFloat($('#physicalCount').val()) || 0;
            
            if (!itemId) {
                alert('Please select an item');
                return;
            }
            
            if (physicalCount < 0) {
                alert('Physical count cannot be negative');
                return;
            }
            
            // Send data to server to save to database
            $.ajax({
                url: '/inventory/addCountEntry',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    id: itemId,
                    physicalCount: physicalCount
                }),
                success: function(response) {
                    if (response.success) {
                        alert('Count entry added to database successfully!');
                        // Reload the table from database
                        loadPendingEntries();
                        // Reset form
                        resetModalForm();
                        // Close modal
                        $('#addPhysicalCountModal').modal('hide');
                    } else {
                        alert('Error adding count entry: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error adding count entry: ' + error);
                }
            });
        });
        
        // Reset modal form function
        function resetModalForm() {
            $('#itemSelect').val('');
            $('#physicalCount').val('').prop('disabled', true);
            $('#systemCount').val('');
            $('#selectedItemInfo').addClass('d-none');
            $('#differencePreview').addClass('d-none');
        }
        
        // Reset form when modal is closed
        $('#addPhysicalCountModal').on('hidden.bs.modal', function() {
            resetModalForm();
        });
        
        // Load pending entries from database
        function loadPendingEntries() {
            $.ajax({
                url: '/inventory/getPendingEntries',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        updatePhysicalCountTable(response.data);
                    } else {
                        console.error('Error loading pending entries:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading pending entries:', error);
                }
            });
        }
        
        // Update physical count table with database data
        function updatePhysicalCountTable(entries) {
            const tbody = $('#physicalCountTable tbody');
            tbody.empty();
            
            let hasData = false;
            
            entries.forEach(function(entry) {
                const difference = parseFloat(entry.difference);
                const variancePercent = parseFloat(entry.variance_percent);
                const valueImpact = parseFloat(entry.value_impact);
                
                // Color code for difference: red if negative, green if positive, black if zero
                let differenceColor = 'text-dark';
                if (difference < 0) {
                    differenceColor = 'text-danger';
                } else if (difference > 0) {
                    differenceColor = 'text-success';
                }
                
                const row = `
                    <tr data-entry-id="${entry.id}" data-inventory-id="${entry.inventory_id}">
                        <td data-label="Product">${entry.item_name}</td>
                        <td data-label="System Count">${entry.system_count}</td>
                        <td data-label="Physical Count">${entry.physical_count}</td>
                        <td data-label="Difference"><span class="${differenceColor} fw-bold">${difference.toFixed(2)}</span></td>
                        <td data-label="Variance %"><span class="badge bg-danger">${variancePercent.toFixed(2)}%</span></td>
                        <td data-label="Value Impact">₱${valueImpact.toFixed(2)}</td>
                        <td data-label="Actions">
                            <?php if ($_SESSION['user_type'] !== 'Inventory Staff'): ?>
                            <button class="btn btn-danger btn-sm delete-btn" data-entry-id="${entry.id}">
                                <i class="fa fa-trash"></i>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                `;
                
                tbody.append(row);
                hasData = true;
            });
            
            // Update summary cards
            updateSummaryCards(entries);
            
            // Enable/disable save button
            $('#saveToInventoryBtn').prop('disabled', !hasData);
        }
        
        // Update summary cards with calculated metrics
        function updateSummaryCards(entries) {
            let totalDiscrepancies = 0;
            let totalValueAtRisk = 0;
            let totalOverages = 0;
            let totalShortages = 0;
            
            entries.forEach(function(entry) {
                const difference = parseFloat(entry.difference);
                const valueImpact = parseFloat(entry.value_impact);
                
                // Count items with any difference (positive or negative)
                if (difference !== 0) {
                    totalDiscrepancies++;
                }
                
                // Total absolute value of all impacts
                totalValueAtRisk += Math.abs(valueImpact);
                
                // Count items with overages (positive differences) and shortages (negative differences)
                if (difference > 0) {
                    totalOverages++;
                } else if (difference < 0) {
                    totalShortages++;
                }
            });
            
            // Update the card values
            $('#totalDiscrepancies').text(totalDiscrepancies);
            $('#valueAtRisk').text('₱' + totalValueAtRisk.toFixed(2));
            $('#overages').text(totalOverages);
            $('#shortages').text(totalShortages);
        }
        
        // Handle delete button click
        $('#physicalCountTable').on('click', '.delete-btn', function() {
            const entryId = $(this).data('entry-id');
            
            if (confirm('Are you sure you want to delete this entry?')) {
                $.ajax({
                    url: '/inventory/deleteCountEntry',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ entryId: entryId }),
                    success: function(response) {
                        if (response.success) {
                            alert('Entry deleted successfully!');
                            loadPendingEntries();
                        } else {
                            alert('Error deleting entry: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error deleting entry: ' + error);
                    }
                });
            }
        });
        
        // Handle save to inventory button click
        $('#saveToInventoryBtn').on('click', function() {
            // Get all entry IDs from the table
            const entryIds = [];
            $('#physicalCountTable tbody tr').each(function() {
                const entryId = $(this).data('entry-id');
                if (entryId) {
                    entryIds.push(entryId);
                }
            });

            if (entryIds.length === 0) {
                alert('No items to save');
                return;
            }

            if (confirm('This will update inventory quantities with physical count values. Are you sure?')) {
                // Send entry IDs to server
                $.ajax({
                    url: '/inventory/savePhysicalCount',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ entries: entryIds }),
                    success: function(response) {
                        if (response.success) {
                            alert('Physical counts saved to inventory successfully!');
                            // Trigger CSV download
                            window.location.href = '/inventory/physical-count-export';
                            // Reload the table from database
                            loadPendingEntries();
                        } else {
                            alert('Error saving physical counts: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error saving physical counts: ' + error);
                    }
                });
            }
        });
    });
</script>
