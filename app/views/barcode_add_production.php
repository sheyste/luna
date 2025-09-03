<?php include_once __DIR__ . '/layout/header_simple.php'; ?>

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
        background: linear-gradient(135deg, #28a745, #1e7e34);
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
        border-left: 4px solid #28a745;
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
    
    .ingredients-section {
        margin-bottom: 30px;
    }
    
    .ingredients-section h6 {
        margin-bottom: 15px;
        font-weight: 600;
        color: #333;
    }
    
    .ingredients-container {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 8px;
        margin-bottom: 15px;
    }
    
    .ingredient-item {
        display: flex;
        flex-direction: column;
        padding: 12px 14px;
        margin-bottom: 8px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #28a745;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .ingredient-item.insufficient {
        border-left-color: #dc3545;
        background: #fff5f5;
    }
    
    .ingredient-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }
    
    .ingredient-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: #2c3e50;
    }
    
    .ingredient-status {
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .ingredient-status.sufficient {
        background: #28a745;
    }
    
    .ingredient-status.insufficient {
        background: #dc3545;
    }
    
    .ingredient-need-have {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .ingredient-have {
        font-weight: 500;
    }
    
    .max-production-info {
        background: linear-gradient(135deg, #e3f2fd, #f1f8ff);
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 15px;
        border-left: 4px solid #2196f3;
        box-shadow: 0 2px 4px rgba(33, 150, 243, 0.1);
        text-align: center;
    }
    
    .max-production-info p {
        margin: 0;
        color: #1565c0;
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .max-production-info #max-production {
        color: #0d47a1;
        font-size: 1.2rem;
        font-weight: 700;
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
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40,167,69,0.1);
    }
    
    .form-text {
        font-size: 0.85rem;
        margin-top: 8px;
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
    
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .btn-primary {
        background: #28a745;
        color: white;
    }
    
    .btn-primary:hover:not(:disabled) {
        background: #218838;
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
        
        .ingredients-container {
            max-height: 250px;
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
        <h3><i class="fa fa-plus"></i> Add Production</h3>
    </div>
    
    <div class="item-details">
        <div id="notification" class="notification"></div>
        
        <div class="detail-item">
            <h5><?php echo htmlspecialchars($menu['name']); ?></h5>
            <p><strong>Barcode:</strong> <?php echo htmlspecialchars($barcode); ?></p>
            <p><strong>Price:</strong> ₱<?php echo number_format($menu['price'], 2); ?></p>
        </div>
        
        <div class="ingredients-section">
            <h6><strong>Required Ingredients:</strong></h6>
            <div id="ingredients-list" class="ingredients-container">
                <!-- Ingredients will be loaded here -->
            </div>
            <div class="max-production-info">
                <p><strong>Maximum Production Possible:</strong> <span id="max-production">Loading...</span> units</p>
            </div>
        </div>
        
        <div class="count-input">
            <label for="production-quantity">Quantity to Produce:</label>
            <input type="number" id="production-quantity" min="0" step="1" placeholder="Enter quantity">
            <small class="form-text" id="production-limit-text"></small>
        </div>
        
        <div class="action-buttons">
            <a href="/barcode/production-actions?barcode=<?php echo urlencode($barcode); ?>&item_id=<?php echo $menu['id']; ?>" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back
            </a>
            <button class="btn btn-primary" id="save-production">
                <i class="fa fa-save"></i> Save Production
            </button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    const menuId = <?php echo $menu['id']; ?>;
    let currentIngredients = [];
    
    loadMenuIngredients();
    $('#production-quantity').focus();
    
    $('#save-production').click(function() {
        saveProduction();
    });
    
    $('#production-quantity').keypress(function(e) {
        if (e.which === 13) {
            saveProduction();
        }
    });
    
    function loadMenuIngredients() {
        $.ajax({
            url: `/production/getMenuIngredients?menu_id=${menuId}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    currentIngredients = response.ingredients;
                    displayIngredients(response.ingredients, response.max_production);
                } else {
                    showNotification('Failed to load ingredients', 'error');
                }
            },
            error: function() {
                showNotification('Error loading ingredients', 'error');
            }
        });
    }
    
    function displayIngredients(ingredients, maxProduction) {
        let ingredientsHtml = '';
        
        ingredients.forEach(function(ingredient) {
            const statusClass = ingredient.sufficient ? 'sufficient' : 'insufficient';
            const statusText = ingredient.sufficient ? 'OK' : 'Low';
            
            ingredientsHtml += `
                <div class="ingredient-item ${ingredient.sufficient ? '' : 'insufficient'}">
                    <div class="ingredient-header">
                        <div class="ingredient-name">${escapeHtml(ingredient.ingredient_name)}</div>
                        <span class="ingredient-status ${statusClass}">${statusText}</span>
                    </div>
                    <div class="ingredient-need-have">
                        <span><strong>Need:</strong> ${ingredient.required_quantity} ${escapeHtml(ingredient.unit)}</span>
                        <span class="ingredient-have"><strong>Have:</strong> ${ingredient.available_quantity} ${escapeHtml(ingredient.unit)}</span>
                    </div>
                </div>
            `;
        });
        
        $('#ingredients-list').html(ingredientsHtml);
        $('#max-production').text(maxProduction);
        
        $('#production-quantity').attr('max', maxProduction);
        
        if (maxProduction === 0) {
            $('#production-limit-text').text('Cannot produce - insufficient ingredients').css('color', '#dc3545');
            $('#save-production').prop('disabled', true);
        } else {
            $('#production-limit-text').text(`Maximum possible: ${maxProduction} units`).css('color', '#28a745');
            $('#save-production').prop('disabled', false);
        }
    }
    
    function saveProduction() {
        const quantity = parseInt($('#production-quantity').val());
        const maxAllowed = parseInt($('#production-quantity').attr('max')) || 0;
        
        if (isNaN(quantity) || quantity <= 0) {
            showNotification('Please enter a valid quantity', 'error');
            return;
        }
        
        if (maxAllowed === 0) {
            showNotification('Cannot produce - insufficient ingredients in inventory', 'error');
            return;
        }
        
        if (quantity > maxAllowed) {
            const limitingIngredient = findLimitingIngredient(quantity);
            const errorMsg = limitingIngredient ? 
                `Cannot produce ${quantity} units. Limited by "${limitingIngredient.name}" (need ${(quantity * limitingIngredient.required).toFixed(2)} ${limitingIngredient.unit}, have ${limitingIngredient.available.toFixed(2)} ${limitingIngredient.unit}). Maximum possible: ${maxAllowed} units.` :
                `Cannot produce ${quantity} units. Maximum possible is ${maxAllowed} units.`;
            
            showNotification(errorMsg, 'error');
            return;
        }
        
        $('#save-production').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
        const formData = new FormData();
        formData.append('menu_id', menuId);
        formData.append('quantity_produced', quantity);
        formData.append('barcode', '<?php echo $barcode; ?>');
        
        $.ajax({
            url: '/production/add',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showNotification('Production added successfully!', 'success');
                setTimeout(() => {
                    window.location.href = '/barcode';
                }, 1500);
            },
            error: function() {
                showNotification('Error adding production', 'error');
                $('#save-production').prop('disabled', false).html('<i class="fa fa-save"></i> Save Production');
            }
        });
    }
    
    function findLimitingIngredient(requestedQuantity) {
        if (!currentIngredients || currentIngredients.length === 0) {
            return null;
        }
        
        let limitingIngredient = null;
        let smallestRatio = Infinity;
        
        currentIngredients.forEach(function(ingredient) {
            if (ingredient.required_quantity > 0) {
                const neededTotal = ingredient.required_quantity * requestedQuantity;
                const availableQuantity = parseFloat(ingredient.available_quantity);
                
                if (neededTotal > availableQuantity) {
                    const ratio = availableQuantity / ingredient.required_quantity;
                    if (ratio < smallestRatio) {
                        smallestRatio = ratio;
                        limitingIngredient = {
                            name: ingredient.ingredient_name,
                            required: ingredient.required_quantity,
                            available: availableQuantity,
                            unit: ingredient.unit,
                            neededTotal: neededTotal
                        };
                    }
                }
            }
        });
        
        return limitingIngredient;
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
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>

<?php include_once __DIR__ . '/layout/footer_simple.php' ?>