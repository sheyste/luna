<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <span class="text-muted">SOL DEL LUNA Inventory System &copy; 2025</span>
    </div>
</footer>


</main>
        
    </div> <!-- /#page-content-wrapper -->
</div> <!-- /#wrapper -->

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.min.js"></script>
<script src="./assets/js/app.js"></script>
<script>
    $(document).ready(function() {
        // Low stock checking now sends emails directly
    });
    
    // Function to check for pending low stock alerts and send emails
    function checkAndSendLowStockAlerts() {
        $.ajax({
            url: '/inventory/check-and-send-alerts',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.sent_count > 0) {
                    console.log('Low stock alerts sent: ' + data.sent_count);
                }
            },
            error: function() {
                // Handle error silently
            }
        });
    }
    
    // Check for and send low stock alerts every 3 seconds
    setInterval(checkAndSendLowStockAlerts, 3000);
    
    // Also check on page load
    checkAndSendLowStockAlerts();
    
    // Auto-update alert resolution status every 3 seconds
    function autoUpdateAlerts() {
        $.ajax({
            url: '/inventory/auto-update-alerts',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.updated_count > 0) {
                    console.log('Alerts updated: ' + data.updated_count);
                }
            },
            error: function() {
                // Handle error silently
            }
        });
    }
    
    // Check and update alert resolution status every 3 seconds
    setInterval(autoUpdateAlerts, 3000);
    
    // Also check on page load
    autoUpdateAlerts();
    // Log "checking is finish" every 3 seconds
    setInterval(function() {
        console.log("checking is finish");
    }, 3000);
    
</script>
</body>
</html>