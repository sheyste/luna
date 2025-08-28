<?php include_once __DIR__ . '/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa fa-database me-2"></i>Database Backup</h2>
</div>

<?php if (isset($data['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-triangle me-2"></i><?php echo $data['error']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($data['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i><?php echo $data['success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Download Backup</h5>
            </div>
            <div class="card-body">
                <p>Click the button below to download a complete backup of the database.</p>
                <p>The backup will include all tables and their data in SQL format.</p>
                
                <a href="/backup/download" class="btn btn-primary">
                    <i class="fa fa-download me-2"></i>Download Database Backup
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Upload Backup</h5>
            </div>
            <div class="card-body">
                <p>Upload a previously downloaded backup file to restore the database.</p>
                <p class="text-danger"><strong>Warning:</strong> This will overwrite existing data.</p>
                
                <form action="/backup/upload" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="backupFile" class="form-label">Select Backup File</label>
                        <input type="file" class="form-control" id="backupFile" name="backupFile" accept=".sql" required>
                        <div class="form-text">Only .sql files are allowed</div>
                    </div>
                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to restore this backup? This will overwrite all current data.')">
                        <i class="fa fa-upload me-2"></i>Upload and Restore
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/layout/footer.php' ?>