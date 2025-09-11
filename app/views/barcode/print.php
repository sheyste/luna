<?php include_once __DIR__ . '/../layout/header_simple.php'; ?>

<!-- JsBarcode -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<style>
    body {
        margin: 0;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8f9fa;
    }
    
    .print-container {
        max-width: 400px;
        margin: 0 auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .item-header {
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e9ecef;
    }
    
    .item-header h2 {
        color: #2c3e50;
        margin: 0 0 10px 0;
        font-size: 1.8rem;
    }
    
    .item-details {
        color: #6c757d;
        font-size: 1.1rem;
    }
    
    .barcode-container {
        margin: 30px 0;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    #barcode {
        width: 100%;
        height: 100px;
    }
    
    .barcode-number {
        font-family: 'Courier New', monospace;
        font-size: 1.2rem;
        color: #495057;
        margin-top: 15px;
        letter-spacing: 1px;
        font-weight: bold;
    }
    
    .print-area {
        display: none;
    }
    
    .print-barcode-number {
        font-family: 'Courier New', monospace;
        font-size: 16px;
        color: #000;
        margin-top: 10px;
        letter-spacing: 1px;
        font-weight: bold;
    }
    
    .print-btn {
        background: #007bff;
        color: white;
        border: none;
        padding: 12px 30px;
        font-size: 1.1rem;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
    }
    
    .print-btn:hover {
        background: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,123,255,0.3);
    }
    
    @media print {
            @page {
                size: 50mm 30mm;
                margin: 0;
            }
            
            html, body {
                background: white;
                width: 50mm;
                height: 30mm;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .print-container {
                box-shadow: none;
                border-radius: 0;
                padding: 0;
                width: 50mm;
                height: 30mm;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                margin: 0;
                page-break-after: avoid;
                page-break-inside: avoid;
                page-break-before: avoid;
            }
            
            .print-btn, .item-header, .barcode-container, script {
                display: none !important;
            }
            
            .print-area {
                display: block;
                text-align: center;
                width: 100%;
                padding: 1mm;
                page-break-after: avoid;
                page-break-inside: avoid;
                page-break-before: avoid;
            }
            
            #printBarcode {
                width: 100%;
                height: 15mm;
                page-break-after: avoid;
                page-break-inside: avoid;
                page-break-before: avoid;
            }
            
            .print-barcode-number {
                font-size: 10px;
                margin-top: 1mm;
                page-break-after: avoid;
                page-break-inside: avoid;
                page-break-before: avoid;
            }
        }
</style>

<div class="print-container">
    <div class="item-header">
        <?php
        // Handle both inventory items and menu items
        $itemName = '';
        $itemDetails = [];
        
        if (isset($item)) {
            // This is an inventory item
            $itemName = $item['name'] ?? 'Unknown Item';
            $itemDetails[] = 'Category: ' . htmlspecialchars($item['category'] ?? 'N/A');
            $itemDetails[] = 'Quantity: ' . htmlspecialchars($item['quantity'] ?? '0') . ' ' . htmlspecialchars($item['unit'] ?? '');
        } elseif (isset($menu)) {
            // This is a menu item
            $itemName = $menu['name'] ?? 'Unknown Menu Item';
            $itemDetails[] = 'Price: ₱' . number_format($menu['price'] ?? 0, 2);
        } else {
            $itemName = 'Unknown Item';
        }
        ?>
        <h2><?php echo htmlspecialchars($itemName); ?></h2>
        <div class="item-details">
            <?php foreach ($itemDetails as $detail): ?>
                <p><?php echo $detail; ?></p>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="barcode-container">
        <svg id="barcode"></svg>
        <div class="barcode-number"><?php echo htmlspecialchars($barcode); ?></div>
    </div>
    
    <div class="print-area" id="printArea">
        <svg id="printBarcode"></svg>
        <div class="print-barcode-number"><?php echo htmlspecialchars($barcode); ?></div>
    </div>
    
    <button class="print-btn" onclick="printBarcode()">
        <i class="fa fa-print"></i> Print Barcode
    </button>
</div>

<script>
    // Generate the barcode when the page loads
    function generateBarcode() {
        // Check if JsBarcode is available
        if (typeof JsBarcode !== 'undefined') {
            // Generate barcode for display
            JsBarcode("#barcode", "<?php echo $barcode; ?>", {
                format: "CODE128",
                displayValue: false,
                width: 2,
                height: 100,
                margin: 10,
                background: "#f8f9fa"
            });
            
            // Generate barcode for printing with smaller dimensions
            JsBarcode("#printBarcode", "<?php echo $barcode; ?>", {
                format: "CODE128",
                displayValue: false,
                width: 1,
                height: 40,
                margin: 3
            });
        } else {
            console.error('JsBarcode library not loaded');
            // Fallback: show the barcode number
            document.getElementById('barcode').innerHTML = '<div class="alert alert-warning">Barcode library not available. Barcode number: <?php echo $barcode; ?></div>';
        }
    }
    
    // Print only the barcode
    function printBarcode() {
        window.print();
    }
    
    // Try to generate the barcode when the page loads
    function initBarcode() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', generateBarcode);
        } else {
            // Add a small delay to ensure the library is loaded
            setTimeout(generateBarcode, 100);
        }
    }
    
    // Initialize the barcode generation
    initBarcode();
</script>

<?php include_once __DIR__ . '/../layout/footer_simple.php'; ?>