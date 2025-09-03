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
        background: linear-gradient(135deg, #17a2b8, #138496);
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
        border-left: 4px solid #17a2b8;
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
    
    .info-message {
        background: #d1ecf1;
        color: #0c5460;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #17a2b8;
        margin-bottom: 30px;
        text-align: center;
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
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #545b62;
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
        
        .btn {
            padding: 12px;
            font-size: 0.95rem;
        }
    }
</style>

<div class="barcode-page">
    <div class="item-header">
        <h3><i class="fa fa-utensils"></i> Menu Item</h3>
    </div>
    
    <div class="item-details">
        <div class="detail-item">
            <h5><?php echo htmlspecialchars($item['name']); ?></h5>
            <p><strong>Barcode:</strong> <?php echo htmlspecialchars($barcode); ?></p>
            <p><strong>Price:</strong> ₱<?php echo number_format($item['price'], 2); ?></p>
        </div>
        
        <div class="info-message">
            <i class="fa fa-info-circle"></i>
            <p><strong>Menu Item Detected</strong></p>
            <p>Menu item functionality will be implemented in a future update.</p>
        </div>
        
        <div class="action-buttons">
            <a href="/barcode" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Scanner
            </a>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/layout/footer_simple.php' ?>