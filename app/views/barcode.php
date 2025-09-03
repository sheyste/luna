<?php include_once __DIR__ . '/layout/header_simple.php'; ?>

<style>
    body {
        margin: 0;
        padding: 0;
        background: #000;
        overflow: hidden;
    }
    
    .fullscreen-scanner {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: #000;
        display: flex;
        flex-direction: column;
    }
    
    .scanner-header {
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 20;
        text-align: center;
        color: white;
    }
    
    .scanner-header h1 {
        font-size: 1.5rem;
        font-weight: 300;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
    
    .scanner-header .subtitle {
        font-size: 0.9rem;
        opacity: 0.8;
        margin-top: 5px;
    }
    
    #scanner-element {
        width: 100%;
        height: 100%;
        position: relative;
    }
    
    #scanner-element canvas,
    #scanner-element video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Mobile specific adjustments */
    @media (max-width: 768px) {
        #scanner-element canvas,
        #scanner-element video {
            object-fit: contain; /* Prevent excessive cropping on mobile */
            object-position: center;
        }
        
        .scanner-overlay {
            width: 280px;
            height: 180px;
        }
        
        .scanner-corners {
            width: 300px;
            height: 200px;
        }
        
        .scanner-header h1 {
            font-size: 1.3rem;
        }
        
        .scanner-controls {
            bottom: 80px;
        }
        
        .scanner-status {
            bottom: 160px;
            font-size: 0.9rem;
            padding: 12px 20px;
        }
    }
    
    .scanner-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        height: 200px;
        border: 3px solid #00ff88;
        border-radius: 12px;
        pointer-events: none;
        z-index: 10;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; border-color: #00ff88; }
        50% { opacity: 0.6; border-color: #00cc66; }
    }
    
    .scanner-corners {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 320px;
        height: 220px;
        pointer-events: none;
        z-index: 15;
    }
    
    .scanner-corners::before,
    .scanner-corners::after {
        content: '';
        position: absolute;
        width: 30px;
        height: 30px;
        border: 4px solid #00ff88;
    }
    
    .scanner-corners::before {
        top: 0;
        left: 0;
        border-right: none;
        border-bottom: none;
        border-radius: 12px 0 0 0;
    }
    
    .scanner-corners::after {
        top: 0;
        right: 0;
        border-left: none;
        border-bottom: none;
        border-radius: 0 12px 0 0;
    }
    
    .scanner-corners .bottom-left,
    .scanner-corners .bottom-right {
        position: absolute;
        width: 30px;
        height: 30px;
        border: 4px solid #00ff88;
        bottom: 0;
    }
    
    .scanner-corners .bottom-left {
        left: 0;
        border-right: none;
        border-top: none;
        border-radius: 0 0 0 12px;
    }
    
    .scanner-corners .bottom-right {
        right: 0;
        border-left: none;
        border-top: none;
        border-radius: 0 0 12px 0;
    }
    
    .scanner-status {
        position: absolute;
        bottom: 160px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 15px 25px;
        border-radius: 25px;
        font-size: 1rem;
        z-index: 10;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .scanner-controls {
        position: absolute;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 20;
        display: flex;
        gap: 15px;
    }
    
    .scanner-btn {
        width: 60px;
        height: 60px;
        border: none;
        border-radius: 50%;
        background: rgba(255,255,255,0.9);
        color: #333;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .scanner-btn:hover {
        background: rgba(255,255,255,1);
        transform: scale(1.1);
    }
    
    .scanner-btn.active {
        background: #00ff88;
        color: white;
    }
    
    .no-camera {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        background: #1a1a1a;
        color: #ccc;
        flex-direction: column;
        text-align: center;
    }
    
    .no-camera i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    .scan-success {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 255, 136, 0.9);
        color: white;
        padding: 20px 30px;
        border-radius: 12px;
        font-size: 1.2rem;
        z-index: 25;
        animation: scanSuccess 0.5s ease-out;
        display: none;
    }
    
    @keyframes scanSuccess {
        0% { transform: translate(-50%, -50%) scale(0.8); opacity: 0; }
        100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
    }
    
    .exit-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 20;
        background: rgba(0,0,0,0.5);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .exit-btn:hover {
        background: rgba(255,0,0,0.7);
    }
    
    /* Physical Count Modal */
    .physical-count-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.8);
        backdrop-filter: blur(5px);
    }
    
    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 0;
        border: none;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        animation: modalSlideIn 0.3s ease-out;
        max-height: 80vh;
        overflow-y: auto;
    }
    
    /* Mobile modal improvements */
    @media (max-width: 768px) {
        .modal-content {
            margin: 5% auto;
            width: 95%;
            max-height: 90vh;
            border-radius: 16px;
        }
        
        .modal-header {
            padding: 16px 20px;
            position: sticky;
            top: 0;
            z-index: 10;
            border-radius: 16px 16px 0 0;
        }
        
        .modal-header h4 {
            font-size: 1.1rem;
        }
        
        .modal-body {
            padding: 20px 16px;
        }
        
        .modal-actions {
            padding: 16px;
            position: sticky;
            bottom: 0;
            background: #f8f9fa;
            border-radius: 0 0 16px 16px;
            border-top: 1px solid #dee2e6;
        }
        
        .btn {
            padding: 12px 20px;
            font-size: 0.95rem;
            min-height: 44px; /* Touch-friendly height */
        }
        
        .btn-block {
            padding: 14px 20px;
            font-size: 1rem;
            margin-bottom: 12px;
        }
    }
    
    @keyframes modalSlideIn {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .modal-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        padding: 20px;
        border-radius: 12px 12px 0 0;
        text-align: center;
    }
    
    .modal-header h4 {
        margin: 0;
        font-weight: 500;
    }
    
    .modal-body {
        padding: 30px;
    }
    
    .item-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #007bff;
    }
    
    .item-info h5 {
        margin: 0 0 10px 0;
        color: #333;
    }
    
    .item-info p {
        margin: 5px 0;
        color: #666;
    }
    
    .count-input {
        margin-bottom: 20px;
    }
    
    .count-input label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }
    
    .count-input input {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 1.1rem;
        text-align: center;
        background: white;
    }
    
    .count-input input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
    }
    
    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }
    
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
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
    
    .production-actions {
        margin: 20px 0;
    }
    
    .btn-block {
        width: 100%;
        margin-bottom: 10px;
    }
    
    .btn-success {
        background: #28a745;
        color: white;
    }
    
    .btn-success:hover {
        background: #218838;
    }
    
    .btn-warning {
        background: #ffc107;
        color: #212529;
    }
    
    .btn-warning:hover {
        background: #e0a800;
    }
    
    .btn-danger {
        background: #dc3545;
        color: white;
    }
    
    .btn-danger:hover {
        background: #c82333;
    }
    
    .ingredients-container {
        max-height: 200px;
        overflow-y: auto;
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 8px;
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
    
    .ingredient-details {
        font-size: 0.85rem;
        color: #6c757d;
        line-height: 1.4;
    }
    
    .ingredient-need-have {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }
    
    .ingredient-have {
        margin-left: auto;
    }
    
    .ingredient-need-have strong {
        color: #495057;
    }
    
    .max-production-info {
        background: linear-gradient(135deg, #e3f2fd, #f1f8ff);
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 15px;
        border-left: 4px solid #2196f3;
        box-shadow: 0 2px 4px rgba(33, 150, 243, 0.1);
    }
    
    .max-production-info p {
        margin: 0;
        color: #1565c0;
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .max-production-info #max-production {
        color: #0d47a1;
        font-size: 1.1rem;
        font-weight: 700;
    }
    
    /* Mobile improvements for ingredients */
    @media (max-width: 768px) {
        .ingredients-container {
            max-height: 250px;
            padding: 6px;
        }
        
        .ingredient-item {
            padding: 10px 12px;
            margin-bottom: 6px;
        }
        
        .ingredient-name {
            font-size: 0.9rem;
        }
        
        .ingredient-details {
            font-size: 0.8rem;
        }
        
        .ingredient-need-have {
            flex-direction: row;
            justify-content: space-between;
        }
        
        .ingredient-have {
            margin-left: 0;
        }
        
        .ingredient-status {
            font-size: 0.7rem;
            padding: 2px 6px;
        }
        
        .max-production-info {
            padding: 12px 14px;
            margin-bottom: 12px;
        }
        
        .max-production-info p {
            font-size: 0.9rem;
        }
        
        .count-input input {
            font-size: 1.1rem;
            padding: 14px 12px;
            min-height: 44px;
        }
        
        .count-input label {
            font-size: 0.95rem;
            margin-bottom: 10px;
        }
        
        .form-text {
            font-size: 0.8rem;
            margin-top: 8px;
        }
        
        .item-info {
            padding: 12px 14px;
            margin-bottom: 16px;
        }
        
        .item-info h5 {
            font-size: 1.05rem;
            margin-bottom: 8px;
        }
        
        .item-info p {
            font-size: 0.85rem;
            margin: 3px 0;
        }
    }
</style>

<div class="fullscreen-scanner">
    <div class="scanner-header">
        <h1>Barcode Scanner</h1>
        <div class="subtitle">Point camera at barcode to scan</div>
    </div>
    
    <button class="exit-btn" onclick="window.location.href='/home'">
        <i class="fa fa-times"></i>
    </button>
    
    <div id="scanner-element">
        <div class="no-camera" id="no-camera-message">
            <i class="fa fa-camera"></i>
            <h3>Camera Scanner</h3>
            <p>Click start to activate camera</p>
        </div>
    </div>
    
    <div class="scanner-overlay" id="scanner-overlay" style="display: none;"></div>
    <div class="scanner-corners" id="scanner-corners" style="display: none;">
        <div class="bottom-left"></div>
        <div class="bottom-right"></div>
    </div>
    
    <div class="scanner-status" id="scanner-status" style="display: none;">
        Initializing scanner...
    </div>
    
    <div class="scan-success" id="scan-success">
        <i class="fa fa-check-circle"></i> Barcode Detected!
    </div>
    
    <div class="scanner-controls">
        <button class="scanner-btn" id="startScanner" title="Start Scanner">
            <i class="fa fa-play"></i>
        </button>
        <button class="scanner-btn" id="stopScanner" title="Stop Scanner">
            <i class="fa fa-stop"></i>
        </button>
    </div>
</div>

<!-- Physical Count Modal -->
<div id="physicalCountModal" class="physical-count-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4><i class="fa fa-clipboard-list"></i> Physical Count</h4>
        </div>
        <div class="modal-body">
            <div class="item-info" id="item-info">
                <!-- Item details will be populated here -->
            </div>
            <div class="count-input">
                <label for="physical-count">Physical Count:</label>
                <input type="number" id="physical-count" min="0" step="0.01" placeholder="Enter physical count">
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn btn-secondary" id="cancel-count">Cancel</button>
            <button class="btn btn-primary" id="save-count">Save Count</button>
        </div>
    </div>
</div>

<!-- Production Actions Modal -->
<div id="productionActionsModal" class="physical-count-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4><i class="fa fa-industry"></i> Production Actions</h4>
        </div>
        <div class="modal-body">
            <div class="item-info" id="production-item-info">
                <!-- Production item details will be populated here -->
            </div>
            <div class="production-actions">
                <button class="btn btn-success btn-block mb-2" id="add-production-btn">
                    <i class="fa fa-plus"></i> Add Production
                </button>
                <button class="btn btn-warning btn-block mb-2" id="update-sold-btn">
                    <i class="fa fa-shopping-cart"></i> Update Sold
                </button>
                <button class="btn btn-danger btn-block mb-2" id="update-wastage-btn">
                    <i class="fa fa-trash"></i> Update Wastage
                </button>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn btn-secondary" id="cancel-production">Cancel</button>
        </div>
    </div>
</div>

<!-- Production Input Modal -->
<div id="productionInputModal" class="physical-count-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 id="production-input-title"><i class="fa fa-edit"></i> Production Input</h4>
        </div>
        <div class="modal-body">
            <div class="item-info" id="production-input-item-info">
                <!-- Production item details will be populated here -->
            </div>
            <div id="ingredients-section" style="display: none;">
                <h6><strong>Required Ingredients:</strong></h6>
                <div id="ingredients-list" class="ingredients-container">
                    <!-- Ingredients will be loaded here -->
                </div>
                <div class="max-production-info">
                    <p><strong>Maximum Production Possible:</strong> <span id="max-production">0</span> units</p>
                </div>
            </div>
            <div class="count-input">
                <label for="production-input" id="production-input-label">Quantity:</label>
                <input type="number" id="production-input" min="0" step="1" placeholder="Enter quantity">
                <small class="form-text text-muted" id="production-limit-text"></small>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn btn-secondary" id="cancel-production-input">Cancel</button>
            <button class="btn btn-primary" id="save-production-input">Save</button>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- QuaggaJS Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

<script>
$(document).ready(function() {
    // Data from PHP
    const inventoryItems = <?php echo json_encode($inventoryItems ?? []); ?>;
    const menuItems = <?php echo json_encode($menuItems ?? []); ?>;
    const productionItems = <?php echo json_encode($productionItems ?? []); ?>;
    
    console.log('Inventory items loaded:', inventoryItems.length);
    console.log('Menu items loaded:', menuItems.length);
    console.log('Production items loaded:', productionItems.length);
    
    let scannerActive = false;
    let currentScannedItem = null;
    let currentProductionAction = '';
    
    // Start scanner on page load - delay to ensure DOM is ready
    setTimeout(() => {
        startQuaggaScanner();
    }, 500);
    
    // Scanner controls
    $('#startScanner').click(function() {
        if (!scannerActive) {
            startQuaggaScanner();
        }
    });
    
    $('#stopScanner').click(function() {
        if (scannerActive) {
            stopQuaggaScanner();
        }
    });
    
    // Physical count modal actions
    $('#cancel-count').click(function() {
        $('#physicalCountModal').hide();
        currentScannedItem = null;
    });
    
    $('#save-count').click(function() {
        savePhysicalCount();
    });
    
    // Enter key to save count
    $('#physical-count').keypress(function(e) {
        if (e.which === 13) {
            savePhysicalCount();
        }
    });
    
    // Production actions modal
    $('#cancel-production').click(function() {
        $('#productionActionsModal').hide();
        currentScannedItem = null;
    });
    
    $('#add-production-btn').click(function() {
        showAddProductionModal();
    });
    
    $('#update-sold-btn').click(function() {
        showProductionInputModal('sold', 'Update Sold', 'Quantity Sold:');
    });
    
    $('#update-wastage-btn').click(function() {
        showProductionInputModal('wastage', 'Update Wastage', 'Wastage Quantity:');
    });
    
    // Production input modal
    $('#cancel-production-input').click(function() {
        $('#productionInputModal').hide();
    });
    
    $('#save-production-input').click(function() {
        saveProductionInput();
    });
    
    // Enter key to save production input
    $('#production-input').keypress(function(e) {
        if (e.which === 13) {
            saveProductionInput();
        }
    });
    
    function startQuaggaScanner() {
        $('#no-camera-message').hide();
        $('#scanner-overlay').show();
        $('#scanner-corners').show();
        $('#scanner-status').show().text('Initializing scanner...');
        
        // Detect if mobile device
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
        // Configure camera constraints - simpler for mobile to prevent zoom issues
        const cameraConstraints = {
            width: window.innerWidth,
            height: window.innerHeight,
            facingMode: "environment"
        };
        
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner-element'),
                constraints: cameraConstraints
            },
            locator: {
                patchSize: "medium",
                halfSample: true
            },
            numOfWorkers: 2,
            decoder: {
                readers: [
                    "code_128_reader",
                    "ean_reader",
                    "ean_8_reader",
                    "code_39_reader",
                    "upc_reader",
                    "i2of5_reader"
                ]
            },
            locate: true
        }, function(err) {
            if (err) {
                console.error('Scanner initialization error:', err);
                $('#scanner-status').text('Camera not available');
                $('#no-camera-message').show().html(`
                    <i class="fa fa-exclamation-triangle"></i>
                    <h3>Camera Error</h3>
                    <p>Please check camera permissions</p>
                `);
                $('#scanner-overlay').hide();
                $('#scanner-corners').hide();
                return;
            }
            
            console.log("Scanner initialized successfully");
            Quagga.start();
            scannerActive = true;
            $('#scanner-status').text('Ready to scan');
            $('#startScanner').addClass('active');
            $('#stopScanner').removeClass('active');
        });
        
        Quagga.onDetected(function(result) {
            const code = result.codeResult.code;
            console.log('Barcode detected:', code);
            
            // Visual feedback
            $('#scanner-status').text('Barcode detected!').addClass('scan-beep');
            setTimeout(() => {
                $('#scanner-status').removeClass('scan-beep');
                if (scannerActive) {
                    $('#scanner-status').text('Ready to scan');
                }
            }, 1000);
            
            // Search for the barcode
            handleScannedBarcode(code);
        });
    }
    
    function stopQuaggaScanner() {
        if (scannerActive) {
            Quagga.stop();
            scannerActive = false;
            $('#scanner-overlay').hide();
            $('#scanner-corners').hide();
            $('#scanner-status').hide();
            $('#no-camera-message').show().html(`
                <i class="fa fa-camera"></i>
                <h3>Scanner Stopped</h3>
                <p>Click start to activate camera</p>
            `);
            $('#startScanner').removeClass('active');
            $('#stopScanner').addClass('active');
        }
    }
    
    function showScanSuccess() {
        $('#scan-success').show();
        setTimeout(() => {
            $('#scan-success').hide();
        }, 1000);
    }
    
    function handleScannedBarcode(barcode) {
        // Search in inventory first
        const inventoryItem = inventoryItems.find(item => item.barcode === barcode);
        
        if (inventoryItem) {
            // Handle inventory barcode - redirect to physical count page
            window.location.href = `/barcode/physical-count?barcode=${encodeURIComponent(barcode)}&item_id=${inventoryItem.id}`;
            return;
        }
        
        // Search in production items
        const productionItem = productionItems.find(item => item.barcode === barcode);
        
        if (productionItem) {
            // Handle production barcode - redirect to production actions page
            window.location.href = `/barcode/production-actions?barcode=${encodeURIComponent(barcode)}&item_id=${productionItem.id}`;
            return;
        }
        
        // Search in menu items
        const menuItem = menuItems.find(item => item.barcode === barcode);
        
        if (menuItem) {
            // Handle menu barcode - redirect to menu actions page
            window.location.href = `/barcode/menu-actions?barcode=${encodeURIComponent(barcode)}&item_id=${menuItem.id}`;
            return;
        }
        
        // Barcode not found - continue scanning silently
        console.log('Barcode not found:', barcode);
    }
    
    function showPhysicalCountModal(item, barcode) {
        const itemInfo = `
            <h5>${escapeHtml(item.name)}</h5>
            <p><strong>Barcode:</strong> ${escapeHtml(barcode)}</p>
            <p><strong>Current System Count:</strong> ${item.quantity} ${escapeHtml(item.unit)}</p>
            <p><strong>Unit Price:</strong> ₱${parseFloat(item.price).toFixed(2)}</p>
        `;
        
        $('#item-info').html(itemInfo);
        $('#physical-count').val('').focus();
        $('#physicalCountModal').show();
    }
    
    function showProductionActionsModal(item, barcode) {
        const itemInfo = `
            <h5>${escapeHtml(item.menu_name)}</h5>
            <p><strong>Barcode:</strong> ${escapeHtml(barcode)}</p>
            <p><strong>Produced:</strong> ${item.quantity_produced}</p>
            <p><strong>Available:</strong> ${item.quantity_available}</p>
            <p><strong>Sold:</strong> ${item.quantity_sold}</p>
            <p><strong>Wastage:</strong> ${item.wastage}</p>
            <p><strong>Price:</strong> ₱${parseFloat(item.price).toFixed(2)}</p>
        `;
        
        $('#production-item-info').html(itemInfo);
        $('#productionActionsModal').show();
    }
    
    function showProductionInputModal(action, title, label) {
        currentProductionAction = action;
        $('#production-input-title').html(`<i class="fa fa-edit"></i> ${title}`);
        $('#production-input-label').text(label);
        $('#production-input').val('').focus();
        
        // Copy item info to input modal
        const itemInfo = $('#production-item-info').html();
        $('#production-input-item-info').html(itemInfo);
        
        // Hide ingredients section for non-add actions
        $('#ingredients-section').hide();
        $('#production-limit-text').text('');
        
        $('#productionActionsModal').hide();
        $('#productionInputModal').show();
    }
    
    function showAddProductionModal() {
        if (!currentScannedItem) {
            showNotification('No item selected', 'error');
            return;
        }
        
        currentProductionAction = 'add';
        $('#production-input-title').html(`<i class="fa fa-plus"></i> Add Production`);
        $('#production-input-label').text('Quantity to Produce:');
        $('#production-input').val('').attr('max', '0');
        
        // Copy item info to input modal
        const itemInfo = $('#production-item-info').html();
        $('#production-input-item-info').html(itemInfo);
        
        // Show ingredients section and load data
        $('#ingredients-section').show();
        loadMenuIngredients(currentScannedItem.menu_id);
        
        $('#productionActionsModal').hide();
        $('#productionInputModal').show();
    }
    
    function loadMenuIngredients(menuId) {
        $.ajax({
            url: `/production/getMenuIngredients?menu_id=${menuId}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
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
        // Store ingredients data for validation
        currentIngredients = ingredients;
        
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
                    <div class="ingredient-details">
                        <div class="ingredient-need-have">
                            <span><strong>Need:</strong> ${ingredient.required_quantity} ${escapeHtml(ingredient.unit)}</span>
                            <span class="ingredient-have"><strong>Have:</strong> ${ingredient.available_quantity} ${escapeHtml(ingredient.unit)}</span>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#ingredients-list').html(ingredientsHtml);
        $('#max-production').text(maxProduction);
        
        // Set input constraints
        $('#production-input').attr('max', maxProduction);
        
        if (maxProduction === 0) {
            $('#production-limit-text').text('Cannot produce - insufficient ingredients').css('color', '#dc3545');
            $('#save-production-input').prop('disabled', true);
        } else {
            $('#production-limit-text').text(`Maximum possible: ${maxProduction} units`).css('color', '#28a745');
            $('#save-production-input').prop('disabled', false);
        }
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
    
    function savePhysicalCount() {
        const physicalCount = parseFloat($('#physical-count').val());
        
        if (isNaN(physicalCount) || physicalCount < 0) {
            showNotification('Please enter a valid physical count', 'error');
            return;
        }
        
        if (!currentScannedItem) {
            showNotification('No item selected', 'error');
            return;
        }
        
        // Save to physical count via API
        $.ajax({
            url: '/inventory/addCountEntry',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                id: currentScannedItem.id,
                physicalCount: physicalCount
            }),
            success: function(response) {
                if (response.success) {
                    showNotification('Physical count saved successfully', 'success');
                    $('#physicalCountModal').hide();
                    currentScannedItem = null;
                } else {
                    showNotification(response.message || 'Failed to save physical count', 'error');
                }
            },
            error: function() {
                showNotification('Error saving physical count', 'error');
            }
        });
    }
    
    let currentIngredients = []; // Store current ingredients data
    
    function saveProductionInput() {
        const inputValue = parseInt($('#production-input').val());
        const maxAllowed = parseInt($('#production-input').attr('max')) || 0;
        
        if (isNaN(inputValue) || inputValue <= 0) {
            showNotification('Please enter a valid quantity', 'error');
            return;
        }
        
        // Enhanced validation for add production
        if (currentProductionAction === 'add') {
            if (maxAllowed === 0) {
                showNotification('Cannot produce - insufficient ingredients in inventory', 'error');
                return;
            }
            
            if (inputValue > maxAllowed) {
                // Find limiting ingredient for better error message
                const limitingIngredient = findLimitingIngredient(inputValue);
                const errorMsg = limitingIngredient ?
                    `Cannot produce ${inputValue} units. Limited by "${limitingIngredient.name}" (need ${(inputValue * limitingIngredient.required).toFixed(2)} ${limitingIngredient.unit}, have ${limitingIngredient.available.toFixed(2)} ${limitingIngredient.unit}). Maximum possible: ${maxAllowed} units.` :
                    `Cannot produce ${inputValue} units. Maximum possible is ${maxAllowed} units.`;
                
                showNotification(errorMsg, 'error');
                return;
            }
        }
        
        if (!currentScannedItem || !currentProductionAction) {
            showNotification('No item or action selected', 'error');
            return;
        }
        
        let endpoint = '';
        let data = {};
        
        switch (currentProductionAction) {
            case 'add':
                endpoint = '/production/add';
                data = {
                    menu_id: currentScannedItem.menu_id,
                    quantity_produced: inputValue,
                    barcode: currentScannedItem.barcode
                };
                break;
            case 'sold':
                endpoint = '/production/updateSold';
                data = {
                    sold: {}
                };
                data.sold[currentScannedItem.menu_id] = inputValue;
                break;
            case 'wastage':
                endpoint = '/production/updateWastage';
                data = {
                    wastage: {}
                };
                data.wastage[currentScannedItem.menu_id] = inputValue;
                break;
        }
        
        // For add production, use form data; for others use JSON
        if (currentProductionAction === 'add') {
            const formData = new FormData();
            Object.keys(data).forEach(key => {
                formData.append(key, data[key]);
            });
            
            $.ajax({
                url: endpoint,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    showNotification('Production updated successfully', 'success');
                    $('#productionInputModal').hide();
                    currentScannedItem = null;
                    currentProductionAction = '';
                    resumeScanning();
                },
                error: function() {
                    showNotification('Error updating production', 'error');
                }
            });
        } else {
            const formData = new FormData();
            Object.keys(data).forEach(key => {
                if (typeof data[key] === 'object') {
                    Object.keys(data[key]).forEach(subKey => {
                        formData.append(`${key}[${subKey}]`, data[key][subKey]);
                    });
                } else {
                    formData.append(key, data[key]);
                }
            });
            
            $.ajax({
                url: endpoint,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    showNotification('Production updated successfully', 'success');
                    $('#productionInputModal').hide();
                    currentScannedItem = null;
                    currentProductionAction = '';
                },
                error: function() {
                    showNotification('Error updating production', 'error');
                }
            });
        }
    }
    
    function showNotification(message, type) {
        // Update scanner status with notification
        const colors = {
            success: '#00ff88',
            error: '#ff4757',
            warning: '#ffa726',
            info: '#3742fa'
        };
        
        $('#scanner-status')
            .text(message)
            .css('background', colors[type] || '#333')
            .show();
            
        setTimeout(() => {
            if (scannerActive) {
                $('#scanner-status')
                    .text('Ready to scan')
                    .css('background', 'rgba(0,0,0,0.8)');
            }
        }, 3000);
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Stop scanner when page is hidden/unloaded
    $(window).on('beforeunload', function() {
        if (scannerActive) {
            stopQuaggaScanner();
        }
    });
    
    document.addEventListener('visibilitychange', function() {
        if (document.hidden && scannerActive) {
            stopQuaggaScanner();
        }
    });
    
    // Auto-focus physical count input when modal opens
    $('#physicalCountModal').on('show', function() {
        setTimeout(() => {
            $('#physical-count').focus();
        }, 100);
    });
});
</script>

<?php include_once __DIR__ . '/layout/footer_simple.php' ?>