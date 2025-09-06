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
    
    .production-actions {
        margin: 30px 0;
    }
    
    .action-btn {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .btn-add-production {
        background: #28a745;
        color: white;
    }
    
    .btn-add-production:hover {
        background: #218838;
        transform: translateY(-2px);
    }
    
    .btn-update-sold {
        background: #ffc107;
        color: #212529;
    }
    
    .btn-update-sold:hover {
        background: #e0a800;
        transform: translateY(-2px);
    }
    
    .btn-update-wastage {
        background: #dc3545;
        color: white;
    }
    
    .btn-update-wastage:hover {
        background: #c82333;
        transform: translateY(-2px);
    }
    
    .btn-back {
        background: #6c757d;
        color: white;
        margin-top: 20px;
    }
    
    .btn-back:hover {
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
        
        .action-btn {
            padding: 12px;
            font-size: 0.95rem;
        }
    }
</style>

<div class="barcode-page">
    <div class="item-header">
        <h3><i class="fa fa-industry"></i> Production Actions</h3>
    </div>
    
    <div class="item-details">
        <div class="detail-item">
            <h5><?php echo htmlspecialchars($item['menu_name']); ?></h5>
            <p><strong>Barcode:</strong> <?php echo htmlspecialchars($barcode); ?></p>
            <p><strong>Produced:</strong> <?php echo $item['quantity_produced']; ?> units</p>
            <p><strong>Available:</strong> <?php echo $item['quantity_available']; ?> units</p>
            <p><strong>Sold:</strong> <?php echo $item['quantity_sold']; ?> units</p>
            <p><strong>Wastage:</strong> <?php echo $item['wastage']; ?> units</p>
            <p><strong>Price:</strong> ₱<?php echo number_format($item['price'], 2); ?></p>
        </div>
        
        <div class="production-actions">
            <button class="action-btn btn-add-production" onclick="window.location.href='/barcode/add-production?menu_id=<?php echo $item['menu_id']; ?>&barcode=<?php echo urlencode($barcode); ?>'">
                <i class="fa fa-plus"></i> Add Production
            </button>
            
            <button class="action-btn btn-update-sold" onclick="window.location.href='/barcode/update-sold?menu_id=<?php echo $item['menu_id']; ?>&barcode=<?php echo urlencode($barcode); ?>'">
                <i class="fa fa-shopping-cart"></i> Update Sold
            </button>
            
            <button class="action-btn btn-update-wastage" onclick="window.location.href='/barcode/update-wastage?menu_id=<?php echo $item['menu_id']; ?>&barcode=<?php echo urlencode($barcode); ?>'">
                <i class="fa fa-trash"></i> Update Wastage
            </button>
        </div>
        
        <button class="action-btn btn-back" onclick="window.location.href='/barcode'">
            <i class="fa fa-arrow-left"></i> Back to Scanner
        </button>
    </div>
</div>

<?php include_once __DIR__ . '/../layout/footer_simple.php' ?>