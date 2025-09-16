<?php include_once __DIR__ . '/../layout/header_simple.php'; ?>

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
            object-fit: contain;
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
    
    // Start scanner on page load
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
    
    function startQuaggaScanner() {
        $('#no-camera-message').hide();
        $('#scanner-overlay').show();
        $('#scanner-corners').show();
        $('#scanner-status').show().text('Initializing scanner...');
        
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
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
            $('#scanner-status').text('Barcode detected!');
            showScanSuccess();
            
            // Handle the scanned barcode
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
        // Search in all item types
        const inventoryItem = inventoryItems.find(item => item.barcode === barcode);
        const productionItem = productionItems.find(item => item.barcode === barcode);
        const menuItem = menuItems.find(item => item.barcode === barcode);
        
        // Check what we found
        const hasInventory = !!inventoryItem;
        const hasProduction = !!productionItem;
        const hasMenu = !!menuItem;
        
        // Show selection page if barcode exists in both Inventory and Menu
        if (hasInventory && hasMenu) {
            console.log('Found in both inventory and menu, showing selection page');
            const params = new URLSearchParams();
            params.append('barcode', barcode);
            params.append('inventory_id', inventoryItem.id);
            params.append('menu_id', menuItem.id);
            window.location.href = `/barcode/selection?${params.toString()}`;
            return;
        }
        
        // Direct navigation for single or prioritized matches
        if (hasProduction) {
            console.log('Production only, going directly');
            redirectToAction('production', productionItem, barcode);
            return;
        }
        
        if (hasInventory) {
            console.log('Inventory only, going directly');
            redirectToAction('inventory', inventoryItem, barcode);
            return;
        }
        
        if (hasMenu) {
            console.log('Menu only, going directly to add production');
            window.location.href = `/barcode/add-production?barcode=${encodeURIComponent(barcode)}&menu_id=${menuItem.id}`;
            return;
        }
        
        // Barcode not found
        console.log('Barcode not found:', barcode);
        showNotification('Barcode not found', 'error');
    }
    
    function redirectToAction(type, item, barcode) {
        switch (type) {
            case 'inventory':
                window.location.href = `/barcode/physical-count?barcode=${encodeURIComponent(barcode)}&item_id=${item.id}`;
                break;
            case 'production':
                window.location.href = `/barcode/production-actions?barcode=${encodeURIComponent(barcode)}&item_id=${item.id}`;
                break;
        }
    }
    
    function showNotification(message, type) {
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
});
</script>

<?php include_once __DIR__ . '/../layout/footer_simple.php'; ?>