<?php include_once __DIR__ . '/layout/header.php'; ?>

<style>
    .scanner-container {
        position: relative;
        max-width: 640px;
        margin: 0 auto;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    #scanner-element {
        width: 100%;
        height: 400px;
        background: #000;
        position: relative;
    }
    
    #scanner-element canvas,
    #scanner-element video {
        max-width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .scanner-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 250px;
        height: 150px;
        border: 2px solid #28a745;
        border-radius: 8px;
        pointer-events: none;
        z-index: 10;
    }
    
    .scanner-overlay::before {
        content: '';
        position: absolute;
        top: -10px;
        left: -10px;
        right: -10px;
        bottom: -10px;
        border: 20px solid rgba(40, 167, 69, 0.1);
        border-radius: 12px;
    }
    
    .scanner-status {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        z-index: 10;
    }
    
    .result-card {
        animation: slideUp 0.3s ease-out;
        border-left: 4px solid #28a745;
    }
    
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .scan-beep {
        color: #28a745;
        font-weight: bold;
    }
    
    .no-camera {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 400px;
        background: #f8f9fa;
        color: #6c757d;
        flex-direction: column;
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Barcode Scanner</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Barcode Scanner</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Scanner Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fa fa-camera me-2"></i>Camera Scanner
                </h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success btn-sm" id="startScanner">
                        <i class="fa fa-play"></i> Start
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="stopScanner">
                        <i class="fa fa-stop"></i> Stop
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="scanner-container">
                    <div id="scanner-element">
                        <div class="no-camera" id="no-camera-message">
                            <i class="fa fa-camera-retro fa-3x mb-3"></i>
                            <p class="mb-0">Click "Start" to activate camera scanner</p>
                        </div>
                    </div>
                    <div class="scanner-overlay" id="scanner-overlay" style="display: none;"></div>
                    <div class="scanner-status" id="scanner-status" style="display: none;">
                        Initializing scanner...
                    </div>
                </div>
            </div>
        </div>

        <!-- Manual Input -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fa fa-keyboard me-2"></i>Manual Barcode Input
                </h5>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <input type="text" class="form-control" id="manual-barcode" placeholder="Enter barcode manually...">
                    <button class="btn btn-primary" type="button" id="manual-search">
                        <i class="fa fa-search"></i> Search
                    </button>
                    <button class="btn btn-outline-secondary" type="button" id="clear-manual">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Scan Results -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fa fa-list me-2"></i>Scan Results
                </h5>
            </div>
            <div class="card-body">
                <div id="scan-results">
                    <div class="text-center text-muted">
                        <i class="fa fa-barcode fa-3x mb-3"></i>
                        <p>No items scanned yet</p>
                        <small>Scan a barcode or enter it manually to see results</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scan History -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fa fa-history me-2"></i>Scan History
                </h5>
                <button class="btn btn-outline-danger btn-sm" id="clear-history">
                    <i class="fa fa-trash"></i> Clear History
                </button>
            </div>
            <div class="card-body">
                <div id="scan-history" class="row">
                    <div class="col-12 text-center text-muted">
                        <p>No scan history yet</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/layout/footer.php' ?>

<!-- QuaggaJS Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

<script>
$(document).ready(function() {
    // Data from PHP
    const inventoryItems = <?php echo json_encode($inventoryItems); ?>;
    const menuItems = <?php echo json_encode($menuItems); ?>;
    
    let scannerActive = false;
    let scanHistory = JSON.parse(localStorage.getItem('scanHistory')) || [];
    
    // Initialize
    updateScanHistory();
    
    // Start scanner
    $('#startScanner').click(function() {
        if (!scannerActive) {
            startQuaggaScanner();
        }
    });
    
    // Stop scanner
    $('#stopScanner').click(function() {
        if (scannerActive) {
            stopQuaggaScanner();
        }
    });
    
    // Manual search
    $('#manual-search').click(function() {
        const barcode = $('#manual-barcode').val().trim();
        if (barcode) {
            searchBarcode(barcode);
        }
    });
    
    // Clear manual input
    $('#clear-manual').click(function() {
        $('#manual-barcode').val('');
    });
    
    // Enter key for manual search
    $('#manual-barcode').keypress(function(e) {
        if (e.which === 13) {
            $('#manual-search').click();
        }
    });
    
    // Clear history
    $('#clear-history').click(function() {
        if (confirm('Are you sure you want to clear scan history?')) {
            scanHistory = [];
            localStorage.removeItem('scanHistory');
            updateScanHistory();
        }
    });
    
    function startQuaggaScanner() {
        $('#no-camera-message').hide();
        $('#scanner-overlay').show();
        $('#scanner-status').show().text('Initializing scanner...');
        
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner-element'),
                constraints: {
                    width: 640,
                    height: 400,
                    facingMode: "environment" // Use back camera on mobile
                }
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
                    "code_39_vin_reader",
                    "codabar_reader",
                    "upc_reader",
                    "upc_e_reader",
                    "i2of5_reader"
                ]
            },
            locate: true
        }, function(err) {
            if (err) {
                console.error('Scanner initialization error:', err);
                $('#scanner-status').text('Camera not available or permission denied');
                $('#no-camera-message').show().html(`
                    <i class="fa fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                    <p class="mb-0">Camera not available</p>
                    <small>Please check camera permissions or use manual input</small>
                `);
                $('#scanner-overlay').hide();
                return;
            }
            
            console.log("Scanner initialized successfully");
            Quagga.start();
            scannerActive = true;
            $('#scanner-status').text('Ready to scan - Point camera at barcode');
            $('#startScanner').prop('disabled', true);
            $('#stopScanner').prop('disabled', false);
        });
        
        Quagga.onDetected(function(result) {
            const code = result.codeResult.code;
            console.log('Barcode detected:', code);
            
            // Visual feedback
            $('#scanner-status').text('Barcode detected!').addClass('scan-beep');
            setTimeout(() => {
                $('#scanner-status').removeClass('scan-beep');
                if (scannerActive) {
                    $('#scanner-status').text('Ready to scan - Point camera at barcode');
                }
            }, 1000);
            
            // Search for the barcode
            searchBarcode(code);
        });
    }
    
    function stopQuaggaScanner() {
        if (scannerActive) {
            Quagga.stop();
            scannerActive = false;
            $('#scanner-overlay').hide();
            $('#scanner-status').hide();
            $('#no-camera-message').show().html(`
                <i class="fa fa-camera-retro fa-3x mb-3"></i>
                <p class="mb-0">Scanner stopped</p>
                <small>Click "Start" to activate camera scanner</small>
            `);
            $('#startScanner').prop('disabled', false);
            $('#stopScanner').prop('disabled', true);
        }
    }
    
    function searchBarcode(barcode) {
        // Search in inventory
        const inventoryItem = inventoryItems.find(item => item.barcode === barcode);
        
        // Search in menus
        const menuItem = menuItems.find(item => item.barcode === barcode);
        
        let result = null;
        let type = '';
        
        if (inventoryItem) {
            result = inventoryItem;
            type = 'inventory';
        } else if (menuItem) {
            result = menuItem;
            type = 'menu';
        }
        
        if (result) {
            displayResult(result, type, barcode);
            addToHistory(result, type, barcode);
        } else {
            displayNoResult(barcode);
            addToHistory(null, 'unknown', barcode);
        }
    }
    
    function displayResult(item, type, barcode) {
        let html = `
            <div class="result-card card border-success mb-3">
                <div class="card-header bg-success text-white">
                    <i class="fa fa-check-circle me-2"></i>Item Found
                </div>
                <div class="card-body">
                    <h6 class="card-title">${escapeHtml(item.name)}</h6>
                    <p class="card-text">
                        <strong>Barcode:</strong> ${escapeHtml(barcode)}<br>
                        <strong>Type:</strong> <span class="badge ${type === 'inventory' ? 'bg-primary' : 'bg-info'}">${type === 'inventory' ? 'Inventory' : 'Menu'}</span><br>
                        <strong>Price:</strong> ₱${parseFloat(item.price || 0).toFixed(2)}
        `;
        
        if (type === 'inventory') {
            html += `<br><strong>Stock:</strong> ${item.quantity} ${escapeHtml(item.unit)}`;
        }
        
        html += `
                    </p>
                    <div class="d-flex gap-2">
                        <a href="/${type}" class="btn btn-sm btn-primary">
                            <i class="fa fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        `;
        
        $('#scan-results').html(html);
    }
    
    function displayNoResult(barcode) {
        const html = `
            <div class="result-card card border-warning mb-3">
                <div class="card-header bg-warning text-dark">
                    <i class="fa fa-exclamation-triangle me-2"></i>Item Not Found
                </div>
                <div class="card-body">
                    <h6 class="card-title">Unknown Barcode</h6>
                    <p class="card-text">
                        <strong>Barcode:</strong> ${escapeHtml(barcode)}<br>
                        <span class="text-muted">This barcode is not found in inventory or menu items.</span>
                    </p>
                </div>
            </div>
        `;
        
        $('#scan-results').html(html);
    }
    
    function addToHistory(item, type, barcode) {
        const historyItem = {
            barcode: barcode,
            item: item,
            type: type,
            timestamp: new Date().toISOString(),
            found: item !== null
        };
        
        // Add to beginning of array
        scanHistory.unshift(historyItem);
        
        // Keep only last 20 items
        if (scanHistory.length > 20) {
            scanHistory = scanHistory.slice(0, 20);
        }
        
        // Save to localStorage
        localStorage.setItem('scanHistory', JSON.stringify(scanHistory));
        
        // Update display
        updateScanHistory();
    }
    
    function updateScanHistory() {
        if (scanHistory.length === 0) {
            $('#scan-history').html(`
                <div class="col-12 text-center text-muted">
                    <p>No scan history yet</p>
                </div>
            `);
            return;
        }
        
        let html = '';
        scanHistory.forEach((item, index) => {
            const date = new Date(item.timestamp);
            const timeStr = date.toLocaleString();
            
            html += `
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 ${item.found ? 'text-success' : 'text-warning'}">
                                        <i class="fa fa-${item.found ? 'check-circle' : 'exclamation-triangle'} me-1"></i>
                                        ${item.found ? escapeHtml(item.item.name) : 'Unknown Item'}
                                    </h6>
                                    <small class="text-muted">
                                        ${escapeHtml(item.barcode)} • ${timeStr}
                                        ${item.found ? `• <span class="badge bg-${item.type === 'inventory' ? 'primary' : 'info'} badge-sm">${item.type}</span>` : ''}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#scan-history').html(html);
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
});
</script>