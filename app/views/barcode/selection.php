<?php include_once __DIR__ . '/../layout/header_simple.php'; ?>

<style>
    body {
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .selection-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
        box-sizing: border-box;
    }
    
    .selection-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        max-width: 600px;
        width: 100%;
        text-align: center;
    }
    
    .selection-header {
        margin-bottom: 30px;
    }
    
    .selection-header h1 {
        color: #2c3e50;
        font-size: 2rem;
        font-weight: 600;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }
    
    .selection-header .barcode-icon {
        color: #667eea;
        font-size: 2.5rem;
    }
    
    .selection-subtitle {
        color: #6c757d;
        font-size: 1rem;
        margin-bottom: 20px;
    }
    
    .barcode-info {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        border-left: 4px solid #667eea;
    }
    
    .barcode-info h3 {
        margin: 0 0 10px 0;
        color: #2c3e50;
        font-size: 1.2rem;
    }
    
    .barcode-number {
        font-family: 'Courier New', monospace;
        font-size: 1.1rem;
        color: #667eea;
        font-weight: bold;
        letter-spacing: 1px;
    }
    
    .actions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 600px) {
        .actions-grid {
            grid-template-columns: 1fr;
        }
        
        .selection-card {
            padding: 30px 20px;
            border-radius: 16px;
        }
        
        .selection-header h1 {
            font-size: 1.5rem;
        }
    }
    
    .action-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 2px solid #e9ecef;
        border-radius: 16px;
        padding: 25px 20px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: inherit;
        display: block;
        position: relative;
        overflow: hidden;
    }
    
    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.02) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .action-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 123, 255, 0.2);
        border-color: #007bff;
        text-decoration: none;
        color: inherit;
    }
    
    .action-card:hover::before {
        opacity: 1;
    }
    
    .action-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 2rem;
        color: white;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        position: relative;
        z-index: 2;
    }
    
    .action-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0 0 8px 0;
        color: #2c3e50;
        position: relative;
        z-index: 2;
    }
    
    .action-description {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 15px;
        position: relative;
        z-index: 2;
    }
    
    .action-details {
        background: rgba(0, 123, 255, 0.05);
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #495057;
        border-left: 3px solid #007bff;
        position: relative;
        z-index: 2;
    }
    
    /* Inventory-specific styling */
    .action-card.inventory .action-icon {
        background: linear-gradient(135deg, #28a745, #20c997);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }
    
    .action-card.inventory:hover {
        border-color: #28a745;
        box-shadow: 0 15px 40px rgba(40, 167, 69, 0.25);
    }
    
    .action-card.inventory::before {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.02) 100%);
    }
    
    .action-card.inventory .action-details {
        background: rgba(40, 167, 69, 0.05);
        border-left-color: #28a745;
    }
    
    /* Production-specific styling */
    .action-card.production .action-icon {
        background: linear-gradient(135deg, #fd7e14, #e55100);
        box-shadow: 0 6px 20px rgba(253, 126, 20, 0.4);
    }
    
    .action-card.production:hover {
        border-color: #fd7e14;
        box-shadow: 0 15px 40px rgba(253, 126, 20, 0.25);
    }
    
    .action-card.production::before {
        background: linear-gradient(135deg, rgba(253, 126, 20, 0.05) 0%, rgba(253, 126, 20, 0.02) 100%);
    }
    
    .action-card.production .action-details {
        background: rgba(253, 126, 20, 0.05);
        border-left-color: #fd7e14;
    }
    
    /* Menu-specific styling */
    .action-card.menu .action-icon {
        background: linear-gradient(135deg, #17a2b8, #138496);
        box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
    }
    
    .action-card.menu:hover {
        border-color: #17a2b8;
        box-shadow: 0 15px 40px rgba(23, 162, 184, 0.25);
    }
    
    .action-card.menu::before {
        background: linear-gradient(135deg, rgba(23, 162, 184, 0.05) 0%, rgba(23, 162, 184, 0.02) 100%);
    }
    
    .action-card.menu .action-details {
        background: rgba(23, 162, 184, 0.05);
        border-left-color: #17a2b8;
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #6c757d;
        color: white;
        padding: 12px 24px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .back-button:hover {
        background: #545b62;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
        text-decoration: none;
        color: white;
    }
    
    .exit-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(0, 0, 0, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 1.2rem;
    }
    
    .exit-btn:hover {
        background: rgba(255, 0, 0, 0.2);
        color: white;
        text-decoration: none;
        transform: scale(1.1);
    }
</style>

<div class="selection-container">
    <a href="/home" class="exit-btn" title="Exit to Home">
        <i class="fa fa-times"></i>
    </a>
    
    <div class="selection-card">
        <div class="selection-header">
            <h1>
                <i class="fa fa-search barcode-icon"></i>
                Choose Action
            </h1>
            <p class="selection-subtitle">Barcode found in multiple locations</p>
        </div>
        
        <div class="barcode-info">
            <h3><i class="fa fa-barcode"></i> Scanned Barcode</h3>
            <div class="barcode-number"><?php echo htmlspecialchars($barcode ?? ''); ?></div>
        </div>
        
        <div class="actions-grid">
            <?php if ($hasInventory ?? false): ?>
            <a href="/barcode/physical-count?barcode=<?php echo urlencode($barcode ?? ''); ?>&item_id=<?php echo $inventoryItem['id'] ?? ''; ?>" class="action-card inventory">
                <div class="action-icon">
                    <i class="fa fa-clipboard-list"></i>
                </div>
                <div class="action-title">Inventory Count</div>
                <div class="action-description">Update physical count for this item</div>
                <div class="action-details">
                    <strong><?php echo htmlspecialchars($inventoryItem['name'] ?? ''); ?></strong><br>
                    Current Stock: <?php echo $inventoryItem['quantity'] ?? 0; ?> <?php echo htmlspecialchars($inventoryItem['unit'] ?? ''); ?><br>
                    Max Quantity: <?php echo $inventoryItem['max_quantity'] ?? 0; ?> <?php echo htmlspecialchars($inventoryItem['unit'] ?? ''); ?>
                </div>
            </a>
            <?php endif; ?>
            
            <?php if ($hasProduction ?? false): ?>
            <a href="/barcode/production-actions?barcode=<?php echo urlencode($barcode ?? ''); ?>&item_id=<?php echo $productionItem['id'] ?? ''; ?>" class="action-card production">
                <div class="action-icon">
                    <i class="fa fa-industry"></i>
                </div>
                <div class="action-title">Production</div>
                <div class="action-description">Manage production for this item</div>
                <div class="action-details">
                    <strong><?php echo htmlspecialchars($productionItem['menu_name'] ?? $productionItem['name'] ?? ''); ?></strong><br>
                    Available: <?php echo $productionItem['quantity_available'] ?? 0; ?> units<br>
                    Produced: <?php echo $productionItem['quantity_produced'] ?? 0; ?> units
                </div>
            </a>
            <?php endif; ?>
            
            <?php if ($hasMenu ?? false): ?>
            <a href="/barcode/menu-actions?barcode=<?php echo urlencode($barcode ?? ''); ?>&item_id=<?php echo $menuItem['id'] ?? ''; ?>" class="action-card menu">
                <div class="action-icon">
                    <i class="fa fa-utensils"></i>
                </div>
                <div class="action-title">Menu Item</div>
                <div class="action-description">View menu item details</div>
                <div class="action-details">
                    <strong><?php echo htmlspecialchars($menuItem['name'] ?? ''); ?></strong><br>
                    Price: ₱<?php echo number_format($menuItem['price'] ?? 0, 2); ?>
                </div>
            </a>
            <?php endif; ?>
        </div>
        
        <a href="/barcode" class="back-button">
            <i class="fa fa-arrow-left"></i>
            Back to Scanner
        </a>
    </div>
</div>

<?php include_once __DIR__ . '/../layout/footer_simple.php'; ?>