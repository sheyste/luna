<?php include_once __DIR__ . '/layout/header.php'; ?>

<style>
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
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Physical Count Entry (Inventory Items)</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/inventory">Inventory</a></li>
            <li class="breadcrumb-item active" aria-current="page">Physical Count Entry</li>
        </ol>
    </nav>
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
        <button id="saveToInventoryBtn" class="btn btn-success" disabled>Save All to Inventory</button>
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
<div class="modal fade" id="addPhysicalCountModal" tabindex="-1" aria-labelledby="addPhysicalCountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <div class="d-flex align-items-center">
                    <i class="fas fa-clipboard-list me-3 fs-4"></i>
                    <h5 class="modal-title mb-0" id="addPhysicalCountModalLabel">Add Physical Count</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="physicalCountForm">
                    <!-- Item Selection Card -->
                    <div class="card border-0 bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title text-primary mb-3">
                                <i class="fas fa-box me-2"></i>Item Selection
                            </h6>
                            <div class="mb-3">
                                <label for="itemSelect" class="form-label fw-semibold">Select Item</label>
                                <select class="form-select form-select-lg shadow-sm" id="itemSelect" required>
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
                                <i class="fas fa-calculator me-2"></i>Physical Count Entry
                            </h6>
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label for="systemCount" class="form-label fw-semibold">System Count</label>
                                    <input type="text" class="form-control form-control-lg bg-white" id="systemCount" readonly placeholder="Select item first">
                                </div>
                                <div class="col-md-6">
                                    <label for="physicalCount" class="form-label fw-semibold">Physical Count</label>
                                    <input type="number" step="0.01" class="form-control form-control-lg shadow-sm" id="physicalCount" min="0" required placeholder="Enter counted amount">
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
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="submit" form="physicalCountForm" class="btn btn-primary px-4">
                    <i class="fas fa-plus me-2"></i>Add to Count List
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
                            <button class="btn btn-success btn-sm save-individual-btn" data-entry-id="${entry.id}">
                                <i class="fa fa-save"></i> Save
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-entry-id="${entry.id}">
                                <i class="fa fa-trash"></i> 
                            </button>
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
        
        // Handle individual save button click
        $('#physicalCountTable').on('click', '.save-individual-btn', function() {
            const entryId = $(this).data('entry-id');
            
            if (confirm('This will update the inventory quantity for this item. Are you sure?')) {
                $.ajax({
                    url: '/inventory/savePhysicalCount',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ entries: [entryId] }),
                    success: function(response) {
                        if (response.success) {
                            alert('Physical count saved to inventory successfully!');
                            // Reload the table from database
                            loadPendingEntries();
                        } else {
                            alert('Error saving physical count: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error saving physical count: ' + error);
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