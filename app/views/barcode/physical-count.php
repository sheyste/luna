<?php include_once __DIR__ . '/../layout/header_simple.php'; ?>

<style>
    body {
        background: #f8f9fc;
        padding: 20px;
    }
    
    .barcode-page {
        max-width: 500px;
        margin: 0 auto;
    }
    
    .item-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        padding: 20px;
        border-radius: 12px 12px 0 0;
        text-align: center;
    }
    
    .item-header h3 {
        margin: 0;
        font-weight: 500;
    }
    
    .item-details {
        background: white;
        padding: 30px;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .detail-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #007bff;
    }
    
    .detail-item h5 {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 1.2rem;
    }
    
    .detail-item p {
        margin: 5px 0;
        color: #666;
    }
    
    .count-input {
        margin-bottom: 30px;
    }
    
    .count-input label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: #333;
        font-size: 1.1rem;
    }
    
    .count-input input {
        width: 100%;
        padding: 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 1.2rem;
        text-align: center;
        background: white;
        box-sizing: border-box;
    }
    
    .count-input input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
    }
    
    .count-input input[readonly] {
        background-color: #e9ecef;
        border-color: #ced4da;
        cursor: not-allowed;
    }
    
    .count-input-group {
        display: flex;
        gap: 10px;
    }
    
    .count-input-group input {
        margin: 0;
    }
    
    .quantity-input {
        flex: 2;
    }
    
    .unit-input {
        flex: 1;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
    }
    
    .btn {
        flex: 1;
        padding: 15px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-primary {
        background: #007bff;
        color: white;
    }
    
    .btn-primary:hover {
        background: #0056b3;
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #545b62;
    }
    
    .notification {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
        display: none;
    }
    
    .notification.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .notification.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    /* Mobile optimizations */
    @media (max-width: 768px) {
        body {
            padding: 10px;
        }
        
        .item-header {
            padding: 16px;
        }
        
        .item-details {
            padding: 20px;
        }
        
        .count-input input {
            padding: 12px;
            font-size: 1.1rem;
        }
        
        .count-input-group {
            flex-direction: column;
            gap: 8px;
        }
        
        .quantity-input,
        .unit-input {
            flex: none;
        }
        
        .btn {
            padding: 12px;
            font-size: 0.95rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="barcode-page">
    <div class="item-header">
        <h3><i class="fa fa-clipboard-list"></i> Physical Count</h3>
    </div>
    
    <div class="item-details">
        <div id="notification" class="notification"></div>
        
        <div class="detail-item">
            <h5><?php echo htmlspecialchars($item['name']); ?></h5>
            <p><strong>Barcode:</strong> <?php echo htmlspecialchars($barcode); ?></p>
            <p><strong>Current System Count:</strong> <?php echo $item['quantity']; ?> <?php echo htmlspecialchars($item['unit']); ?></p>
            <p><strong>Unit Price:</strong> ₱<?php echo number_format($item['price'], 2); ?></p>
        </div>
        
        <div class="count-input">
            <label for="physical-count">Physical Count:</label>
            <div class="count-input-group">
                <input type="number" id="physical-count" class="quantity-input" min="0" step="0.01" placeholder="Enter quantity">
                <input type="text" id="physical-unit" class="unit-input" value="<?php echo htmlspecialchars($item['unit']); ?>" placeholder="Unit" readonly>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="/barcode" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Scanner
            </a>
            <button class="btn btn-primary" id="save-count">
                <i class="fa fa-save"></i> Save Count
            </button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    const itemId = <?php echo $item['id']; ?>;
    
    $('#physical-count').focus();
    
    $('#save-count').click(function() {
        savePhysicalCount();
    });
    
    $('#physical-count').keypress(function(e) {
        if (e.which === 13) {
            savePhysicalCount();
        }
    });
    
    function savePhysicalCount() {
        const physicalCount = parseFloat($('#physical-count').val());
        
        if (isNaN(physicalCount) || physicalCount < 0) {
            showNotification('Please enter a valid physical count', 'error');
            return;
        }
        
        $('#save-count').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
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
                    showNotification('Physical count saved successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = '/barcode';
                    }, 1500);
                } else {
                    showNotification(response.message || 'Failed to save physical count', 'error');
                    $('#save-count').prop('disabled', false).html('<i class="fa fa-save"></i> Save Count');
                }
            },
            error: function() {
                showNotification('Error saving physical count', 'error');
                $('#save-count').prop('disabled', false).html('<i class="fa fa-save"></i> Save Count');
            }
        });
    }
    
    function showNotification(message, type) {
        $('#notification')
            .removeClass('success error')
            .addClass(type)
            .text(message)
            .show();
        
        setTimeout(() => {
            $('#notification').fadeOut();
        }, 5000);
    }
});
</script>

<?php include_once __DIR__ . '/../layout/footer_simple.php' ?>