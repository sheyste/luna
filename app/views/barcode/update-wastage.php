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
        background: linear-gradient(135deg, #dc3545, #c82333);
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
        border-left: 4px solid #dc3545;
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
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220,53,69,0.1);
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
        background: #dc3545;
        color: white;
    }
    
    .btn-primary:hover {
        background: #c82333;
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
        <h3><i class="fa fa-trash"></i> Update Wastage</h3>
    </div>
    
    <div class="item-details">
        <div id="notification" class="notification"></div>
        
        <div class="detail-item">
            <h5><?php echo htmlspecialchars($menu['name']); ?></h5>
            <p><strong>Barcode:</strong> <?php echo htmlspecialchars($barcode); ?></p>
            <p><strong>Available Units:</strong> <?php echo $menu['total_available'] ?? 0; ?> units</p>
            <p><strong>Current Wastage:</strong> <?php echo $menu['total_wastage'] ?? 0; ?> units</p>
            <p><strong>Price:</strong> ₱<?php echo number_format($menu['price'], 2); ?></p>
        </div>
        
        <div class="count-input">
            <label for="wastage-quantity">Wastage Quantity:</label>
            <input type="number" id="wastage-quantity" min="0" step="1" max="<?php echo $menu['total_available'] ?? 0; ?>" placeholder="Enter wastage quantity">
            <small class="form-text text-muted">Maximum available: <?php echo $menu['total_available'] ?? 0; ?> units</small>
        </div>
        
        <div class="action-buttons">
            <a href="/barcode/production-actions?barcode=<?php echo urlencode($barcode); ?>&item_id=<?php echo $menu['id']; ?>" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back
            </a>
            <button class="btn btn-primary" id="save-wastage">
                <i class="fa fa-save"></i> Update Wastage
            </button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    const menuId = <?php echo $menu['id']; ?>;
    const maxAvailable = <?php echo $menu['total_available'] ?? 0; ?>;
    
    $('#wastage-quantity').focus();
    
    $('#save-wastage').click(function() {
        updateWastage();
    });
    
    $('#wastage-quantity').keypress(function(e) {
        if (e.which === 13) {
            updateWastage();
        }
    });
    
    function updateWastage() {
        const quantity = parseInt($('#wastage-quantity').val());
        
        if (isNaN(quantity) || quantity <= 0) {
            showNotification('Please enter a valid quantity', 'error');
            return;
        }
        
        if (quantity > maxAvailable) {
            showNotification(`Cannot waste ${quantity} units. Only ${maxAvailable} units available.`, 'error');
            return;
        }
        
        $('#save-wastage').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
        
        const formData = new FormData();
        formData.append(`wastage[${menuId}]`, quantity);
        
        $.ajax({
            url: '/production/updateWastage',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showNotification('Wastage updated successfully!', 'success');
                setTimeout(() => {
                    window.location.href = '/barcode';
                }, 1500);
            },
            error: function() {
                showNotification('Error updating wastage', 'error');
                $('#save-wastage').prop('disabled', false).html('<i class="fa fa-save"></i> Update Wastage');
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