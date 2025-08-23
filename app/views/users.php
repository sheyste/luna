<?php include_once __DIR__ . '/layout/header.php'; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Users</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
</div>

<!-- Main Content Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">User List</h6>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalUserForm">
            <i class="fa fa-user-plus me-1"></i> Add User
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <div id="grid_container">
                <?php include_once __DIR__ . "/user_grid.php"; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="modalUserForm" tabindex="-1" aria-labelledby="modalUserFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUserFormLabel">User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" method="post">
                    <input type="hidden" name="edit_mode" id="edit_mode" value="0" readonly>
                    <input type="hidden" name="id" id="id" value="" readonly>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username (*)</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name (*)</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name (*)</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email (*)</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_type" class="form-label">User Type (*)</label>
                        <select class="form-select" name="user_type" id="user_type" required>
                            <option value="Admin">Admin</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (*)</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                </form>
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert" style="display:none;">
                    <p>Error</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="User.save(this);">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="modalDelUser" tabindex="-1" aria-labelledby="modalDelUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDelUserLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="" id="id_del" value="" readonly>
                <span>Are you sure you want to delete this user?</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="User.delete(this);">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/layout/footer.php'; ?>
