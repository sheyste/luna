<?php include_once __DIR__ . '/layout/header.php'; ?>

<style>
    /* Enhanced Mobile Responsive Styles */
    @media (max-width: 767px) {
        /* Page Header Mobile */
        .d-flex.justify-content-between.align-items-center.mb-4 {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 1rem;
        }
        
        .d-flex.justify-content-between.align-items-center.mb-4 h1 {
            font-size: 1.5rem;
            margin-bottom: 0;
        }

        /* Card Header Mobile */
        .card-header .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.75rem;
        }
        
        .card-header h6 {
            margin-bottom: 0 !important;
        }
        
        .card-header > div {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .card-header .btn {
            flex: 1;
            min-width: 120px;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }

        /* Responsive Table */
        #userTable thead {
            display: none;
        }

        #userTable, #userTable tbody, #userTable tr, #userTable td {
            display: block;
            width: 100%;
        }

        #userTable tr {
            margin-bottom: 1.25rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background: #fff;
            overflow: hidden;
        }

        #userTable td {
            text-align: right;
            padding: 0.75rem 1rem;
            position: relative;
            border: none;
            border-bottom: 1px solid #f1f3f4;
            min-height: 3rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #userTable td:last-of-type {
            border-bottom: 0;
            background-color: #f8f9fa;
        }

        #userTable td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #495057;
            text-align: left;
            flex: 0 0 35%;
            margin-right: 1rem;
        }
        
        #userTable td:last-of-type::before {
            content: attr(data-label);
            margin-bottom: 0.5rem;
        }

        /* Action buttons in mobile table */
        #userTable td:last-of-type {
            flex-direction: column;
            align-items: stretch;
            padding: 1rem;
        }
        
        #userTable td .btn {
            margin: 0.25rem 0;
            width: 100%;
            font-size: 0.875rem;
            padding: 0.5rem;
        }
        
        #userTable td .btn i {
            margin-right: 0.5rem;
        }

        /* SMTP Testing Card Mobile */
        .card-body .row {
            flex-direction: column;
        }
        
        .card-body .col-md-6 {
            margin-bottom: 1rem;
        }
        
        .card-body .col-md-6:first-child {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .card-body .col-md-6 .btn {
            width: 100%;
            margin: 0;
        }

        /* Modal Mobile Improvements */
        .modal-dialog {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }
        
        .modal-body {
            padding: 1.5rem 1rem;
        }
        
        .modal-footer {
            padding: 1rem;
            flex-direction: column-reverse;
            gap: 0.5rem;
        }
        
        .modal-footer .btn {
            width: 100%;
            margin: 0;
        }
        
        .modal-title {
            font-size: 1.25rem;
        }

        /* Form improvements for mobile */
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            font-size: 16px; /* Prevents zoom on iOS */
            padding: 0.75rem;
        }

        /* Alert improvements */
        .alert {
            font-size: 0.875rem;
            padding: 0.75rem;
        }
        
        /* DataTable mobile adjustments */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            width: 100%;
            margin-left: 0;
            margin-top: 0.5rem;
        }
        
        .dataTables_wrapper .dataTables_length select {
            margin: 0 0.5rem;
        }
    }

    /* Tablet adjustments */
    @media (min-width: 768px) and (max-width: 991px) {
        .card-header .btn {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }
        
        #userTable td .btn {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
            margin: 0.125rem;
        }
    }

    /* Small mobile devices */
    @media (max-width: 480px) {
        .container-fluid {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .card {
            margin-bottom: 1rem;
        }
        
        .card-header {
            padding: 1rem 0.75rem;
        }
        
        .card-body {
            padding: 1rem 0.75rem;
        }
        
        #userTable td {
            padding: 0.5rem 0.75rem;
            min-height: 2.5rem;
        }
        
        #userTable td::before {
            flex: 0 0 40%;
            font-size: 0.875rem;
        }
        
        .modal-dialog {
            margin: 0.5rem;
            max-width: calc(100% - 1rem);
        }
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Users</h1>
</div>

<!-- Main Content Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-bold text-primary">User List</h6>
                         <div>
                 <button class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalAddUser">
                     <i class="fa fa-user-plus me-1"></i> Add User
                 </button>
                 <button class="btn btn-info btn-sm" id="debugEmailBtn">
                     <i class="fa fa-cog me-1"></i> Debug SMTP Config
                 </button>
             </div>
        </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="userTable" width="100%" cellspacing="0">
                <thead class="table-dark">
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Receive Email</th>
                        <th>Sign Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td data-label="Username"><?= htmlspecialchars($user['username']) ?></td>
                                <td data-label="Name"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                <td data-label="Email"><?= htmlspecialchars($user['email']) ?></td>
                                <td data-label="User Type"><?= htmlspecialchars($user['user_type']) ?></td>
                                <td data-label="Receive Email"><?= isset($user['receive_email']) && $user['receive_email'] == 1 ? 'Yes' : 'No' ?></td>
                                <td data-label="Sign Date"><?= htmlspecialchars($user['sign_date']) ?></td>
                                <td data-label="Actions" class="text-nowrap">
                                    <button class="btn btn-info btn-sm edit-btn" data-id="<?= htmlspecialchars($user['id']) ?>">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= htmlspecialchars($user['id']) ?>">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Admin'): ?>
                                        <button class="btn btn-secondary btn-sm test-email-btn" data-email="<?= htmlspecialchars($user['email']) ?>" data-name="<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>">
                                            <i class="fa fa-envelope"></i> Test Email
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SMTP Testing Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-success">SMTP Email Testing</h6>
        <small class="text-muted">Test your SMTP configuration</small>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-info btn-sm me-2" onclick="testSMTPConnection()">
                    <i class="fas fa-plug"></i> Test SMTP Connection
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="showTestEmailModal()">
                    <i class="fas fa-envelope"></i> Send Test Email
                </button>
            </div>
            <div class="col-md-6">
                <div id="smtp-test-result" class="alert" style="display: none; margin-bottom: 0;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="modalAddUser" tabindex="-1" aria-labelledby="modalAddUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddUserLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" method="post">
                    <div class="mb-3">
                        <label for="add_username" class="form-label">Username (*)</label>
                        <input type="text" class="form-control" name="username" id="add_username" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_first_name" class="form-label">First Name (*)</label>
                        <input type="text" class="form-control" name="first_name" id="add_first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_last_name" class="form-label">Last Name (*)</label>
                        <input type="text" class="form-control" name="last_name" id="add_last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_email" class="form-label">Email (*)</label>
                        <input type="email" class="form-control" name="email" id="add_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_user_type" class="form-label">User Type (*)</label>
                        <select class="form-select" name="user_type" id="add_user_type" required>
                            <option value="Admin">Admin</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_receive_email" class="form-label">Receive Email</label>
                        <select class="form-select" name="receive_email" id="add_receive_email">
                            <option value="1">Yes</option>
                            <option value="0" selected>No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_password" class="form-label">Password (*)</label>
                        <input type="password" class="form-control" name="password" id="add_password" required>
                    </div>
                </form>
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert" style="display:none;">
                    <p>Error</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="addUserBtn">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditUserLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="post">
                    <input type="hidden" name="id" id="edit_id" value="" readonly>
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Username (*)</label>
                        <input type="text" class="form-control" name="username" id="edit_username" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_first_name" class="form-label">First Name (*)</label>
                        <input type="text" class="form-control" name="first_name" id="edit_first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_last_name" class="form-label">Last Name (*)</label>
                        <input type="text" class="form-control" name="last_name" id="edit_last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email (*)</label>
                        <input type="email" class="form-control" name="email" id="edit_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_user_type" class="form-label">User Type (*)</label>
                        <select class="form-select" name="user_type" id="edit_user_type" required>
                            <option value="Admin">Admin</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_receive_email" class="form-label">Receive Email</label>
                        <select class="form-select" name="receive_email" id="edit_receive_email">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password (Leave blank to keep current)</label>
                        <input type="password" class="form-control" name="password" id="edit_password">
                    </div>
                </form>
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert" style="display:none;">
                    <p>Error</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="updateUserBtn">Save</button>
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
                <button type="button" class="btn btn-danger" id="deleteUserBtn">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1" aria-labelledby="testEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testEmailModalLabel">Send Test Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="testEmailForm">
                    <div class="mb-3">
                        <label for="testEmail" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="testEmail" placeholder="Enter email address" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        This will send a test email to verify your SMTP configuration is working correctly.
                    </div>
                    <div id="test-email-result" class="alert" style="display: none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendTestEmail()">
                    <i class="fas fa-paper-plane"></i> Send Test Email
                </button>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    var userTable = $('#userTable').DataTable();

    const User = {
        htmlspecialchars: function(str) {
            if (typeof str !== 'string') {
                return str;
            }
            return str.replace(/&/g, '&')
                      .replace(/</g, '<')
                      .replace(/>/g, '>')
                      .replace(/"/g, '"')
                      .replace(/'/g, '&#039;');
        },
        show: function(id) {
            const data = { "id": id };

            $.ajax({
                url: '/users/show',
                type: 'POST',
                dataType: 'JSON',
                data: data,
                success: function(response) {
                    $('#edit_username').val(User.htmlspecialchars(response.username));
                    $('#edit_first_name').val(User.htmlspecialchars(response.first_name));
                    $('#edit_last_name').val(User.htmlspecialchars(response.last_name));
                    $('#edit_email').val(User.htmlspecialchars(response.email));
                    $('#edit_user_type').val(User.htmlspecialchars(response.user_type));
                    $('#edit_receive_email').val(User.htmlspecialchars(response.receive_email));
                    $('#edit_id').val(User.htmlspecialchars(id));
                    $('#modalEditUser').modal('show');
                },
                error: function(error) {
                    console.warn('error ' + error);
                    $('#modalEditUser .alert-danger p').text('Error trying load');
                    $('#modalEditUser .alert-danger').show();
                }
            });
        },
        confirm: function(id) {
            $('#modalDelUser').modal('show');
            $('#id_del').val(id);
        },
        add: function() {
            const form = $("#addUserForm").serializeArray();

            if ($.trim($('#add_username').val()) === '' || $.trim($('#add_first_name').val()) === '' || $.trim($('#add_last_name').val()) === '' || $.trim($('#add_email').val()) === '' || $.trim($('#add_user_type').val()) === '' || $.trim($('#add_password').val()) === '') {
                $('#modalAddUser .alert-danger p').text('All fields required');
                $('#modalAddUser .alert-danger').show();
            } else {
                $.ajax({
                    url: '/users/add',
                    type: 'POST',
                    dataType: 'JSON',
                    data: form,
                    success: function(response) {
                        if (response.success) {
                            $("#modalAddUser").modal('hide');
                            location.reload();
                        } else {
                            $('#modalAddUser .alert-danger p').text('Error saving user');
                            $('#modalAddUser .alert-danger').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#modalAddUser .alert-danger p').text('Error saving user: ' + error);
                        $('#modalAddUser .alert-danger').show();
                    }
                });
            }
        },
        update: function() {
            const form = $("#editUserForm").serializeArray();

            if ($.trim($('#edit_username').val()) === '' || $.trim($('#edit_first_name').val()) === '' || $.trim($('#edit_last_name').val()) === '' || $.trim($('#edit_email').val()) === '' || $.trim($('#edit_user_type').val()) === '') {
                $('#modalEditUser .alert-danger p').text('All fields required');
                $('#modalEditUser .alert-danger').show();
            } else {
                $.ajax({
                    url: '/users/edit',
                    type: 'POST',
                    dataType: 'JSON',
                    data: form,
                    success: function(response) {
                        if (response.success) {
                            $("#modalEditUser").modal('hide');
                            location.reload();
                        } else {
                            $('#modalEditUser .alert-danger p').text('Error saving user');
                            $('#modalEditUser .alert-danger').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#modalEditUser .alert-danger p').text('Error saving user: ' + error);
                        $('#modalEditUser .alert-danger').show();
                    }
                });
            }
        },
        delete: function() {
            const data = { 'id': $('#id_del').val() };

            $.ajax({
                url: '/users/delete',
                type: 'POST',
                dataType: 'JSON',
                data: data,
                success: function(response) {
                    if (response.success) {
                        location.reload();
                        $("#modalDelUser").modal('hide');
                    } else {
                        $('#modalDelUser .alert-danger p').text('Error deleting user');
                        $('#modalDelUser .alert-danger').show();
                    }
                },
                error: function(xhr, status, error) {
                    $('#modalDelUser .alert-danger p').text('Error deleting user: ' + error);
                    $('#modalDelUser .alert-danger').show();
                }
            });
        },
        testEmail: function(email, name) {
            const data = { email: email, name: name };

            $.ajax({
                url: '/users/test-email',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    // Show loading message
                    alert('📧 Sending test email via SMTP...\n\nPlease wait...');
                },
                success: function(response, textStatus, xhr) {
                    try {
                        // Try to parse JSON if response is string
                        if (typeof response === 'string') {
                            response = JSON.parse(response);
                        }
                        
                        if (response.success) {
                            let successMessage = '✅ SMTP Test Email Sent Successfully!\n\n';
                            successMessage += 'Recipient: ' + name + ' (' + email + ')\n';
                            successMessage += 'Method: ' + (response.method || 'SMTP') + '\n';
                            if (response.smtp_host) {
                                successMessage += 'SMTP Host: ' + response.smtp_host + '\n';
                            }
                            successMessage += 'Message: ' + response.message;
                            alert(successMessage);
                        } else {
                            let errorMessage = '❌ SMTP Test Email Failed\n\n';
                            errorMessage += 'Recipient: ' + name + ' (' + email + ')\n';
                            errorMessage += 'Error: ' + (response.message || 'Unknown error');
                            if (response.http_code) {
                                errorMessage += '\nHTTP Code: ' + response.http_code;
                            }
                            alert(errorMessage);
                        }
                    } catch (parseError) {
                        // Handle JSON parsing error - server returned HTML/PHP error
                        let errorMessage = '❌ Server Response Error\n\n';
                        errorMessage += 'The server returned an invalid response (likely PHP error)\n\n';
                        errorMessage += 'Server Response (first 200 chars):\n';
                        errorMessage += response.toString().substring(0, 200) + '...\n\n';
                        errorMessage += 'Please check:\n';
                        errorMessage += '1. PHP error logs\n';
                        errorMessage += '2. SMTP configuration in .env file\n';
                        errorMessage += '3. Server permissions and extensions';
                        alert(errorMessage);
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = '❌ Request Failed\n\n';
                    
                    if (xhr.status === 0) {
                        errorMessage += 'Network Error: Could not connect to server\n';
                        errorMessage += 'Check if the server is running and accessible.';
                    } else if (xhr.status >= 500) {
                        errorMessage += 'Server Error (' + xhr.status + ')\n';
                        errorMessage += 'The server encountered an internal error.\n';
                        errorMessage += 'Check server logs for details.';
                    } else if (xhr.status === 404) {
                        errorMessage += 'Endpoint Not Found (404)\n';
                        errorMessage += 'The test email endpoint may not be configured correctly.';
                    } else {
                        errorMessage += 'HTTP Error: ' + xhr.status + '\n';
                        errorMessage += 'Status: ' + status + '\n';
                        errorMessage += 'Error: ' + error;
                    }
                    
                    // Try to get more info from response
                    if (xhr.responseText && xhr.responseText.length > 0) {
                        errorMessage += '\n\nServer Response (first 200 chars):\n';
                        errorMessage += xhr.responseText.substring(0, 200) + '...';
                    }
                    
                    alert(errorMessage);
                }
            });
        }
    };

    // Event listeners
    $('#userTable').on('click', '.edit-btn', function() {
        var userId = $(this).data('id');
        User.show(userId);
    });

    $('#userTable').on('click', '.delete-btn', function() {
        var userId = $(this).data('id');
        User.confirm(userId);
    });

    $('#userTable').on('click', '.test-email-btn', function() {
        var email = $(this).data('email');
        var name = $(this).data('name');
        User.testEmail(email, name);
    });

    $('#addUserBtn').on('click', function() {
        User.add();
    });

    $('#updateUserBtn').on('click', function() {
        User.update();
    });

    $('#deleteUserBtn').on('click', function() {
        User.delete();
    });

    $('#debugEmailBtn').on('click', function() {
        $.ajax({
            url: '/users/debug-email-config',
            type: 'GET',
            dataType: 'JSON',
            success: function(response) {
                let message = 'SMTP Email Configuration Debug:\n\n';
                message += 'Environment file exists: ' + response.env_file_exists + '\n';
                message += 'SMTP Host: ' + response.smtp_host + '\n';
                message += 'SMTP Port: ' + response.smtp_port + '\n';
                message += 'SMTP Username: ' + response.smtp_username + '\n';
                message += 'SMTP Password: ' + response.smtp_password + '\n';
                message += 'SMTP Security: ' + response.smtp_security + '\n';
                message += 'From Email: ' + response.from_email + '\n';
                message += 'From Name: ' + response.from_name + '\n';
                message += 'cURL Extension: ' + response.curl_extension + '\n';
                message += 'OpenSSL Extension: ' + response.openssl_extension;
                alert(message);
            },
            error: function() {
                alert('Error checking SMTP configuration');
            }
        });
    });

    // Reset forms and alerts when modals are hidden
    $('#modalAddUser').on('hidden.bs.modal', function(e){
        $(this).find('form').trigger('reset');
        $(this).find('.alert').hide();
    });

    $('#modalEditUser').on('hidden.bs.modal', function(e){
        $(this).find('form').trigger('reset');
        $(this).find('.alert').hide();
    });

    // SMTP Testing Functions
    window.testSMTPConnection = function() {
        const resultDiv = $('#smtp-test-result');
        resultDiv.removeClass('alert-success alert-danger alert-warning').addClass('alert-info').show();
        resultDiv.html('<i class="fas fa-spinner fa-spin"></i> Testing SMTP connection...');
        
        $.ajax({
            url: '/inventory/test-smtp-connection',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    resultDiv.removeClass('alert-info alert-danger').addClass('alert-success');
                    resultDiv.html(`
                        <i class="fas fa-check-circle"></i> <strong>Connection Successful!</strong><br>
                        <small>SMTP Host: ${response.smtp_host || 'Not configured'}<br>
                        Port: ${response.smtp_port || 'Not configured'}<br>
                        Security: ${response.smtp_security || 'Not configured'}</small>
                    `);
                } else {
                    resultDiv.removeClass('alert-info alert-success').addClass('alert-danger');
                    resultDiv.html(`<i class="fas fa-exclamation-circle"></i> <strong>Connection Failed:</strong> ${response.message}`);
                }
            },
            error: function(xhr, status, error) {
                resultDiv.removeClass('alert-info alert-success').addClass('alert-danger');
                resultDiv.html(`<i class="fas fa-exclamation-circle"></i> <strong>Error:</strong> Failed to test SMTP connection`);
            }
        });
    };

    window.showTestEmailModal = function() {
        $('#testEmailModal').modal('show');
        $('#test-email-result').hide();
        $('#testEmail').val('');
    };

    window.sendTestEmail = function() {
        const email = $('#testEmail').val();
        const resultDiv = $('#test-email-result');
        
        if (!email) {
            resultDiv.removeClass('alert-success alert-danger').addClass('alert-warning').show();
            resultDiv.html('<i class="fas fa-exclamation-triangle"></i> Please enter an email address.');
            return;
        }
        
        if (!isValidEmail(email)) {
            resultDiv.removeClass('alert-success alert-danger').addClass('alert-warning').show();
            resultDiv.html('<i class="fas fa-exclamation-triangle"></i> Please enter a valid email address.');
            return;
        }
        
        resultDiv.removeClass('alert-success alert-danger alert-warning').addClass('alert-info').show();
        resultDiv.html('<i class="fas fa-spinner fa-spin"></i> Sending test email...');
        
        $.ajax({
            url: '/inventory/send-test-email',
            method: 'POST',
            data: { email: email },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    resultDiv.removeClass('alert-info alert-danger').addClass('alert-success');
                    resultDiv.html(`
                        <i class="fas fa-check-circle"></i> <strong>Email Sent Successfully!</strong><br>
                        <small>Test email sent to: ${response.recipient}</small>
                    `);
                    
                    // Also update the main SMTP test result
                    const mainResultDiv = $('#smtp-test-result');
                    mainResultDiv.removeClass('alert-info alert-danger').addClass('alert-success').show();
                    mainResultDiv.html(`<i class="fas fa-check-circle"></i> <strong>SMTP Working!</strong> Test email sent to ${response.recipient}`);
                } else {
                    resultDiv.removeClass('alert-info alert-success').addClass('alert-danger');
                    resultDiv.html(`<i class="fas fa-exclamation-circle"></i> <strong>Failed to send email:</strong> ${response.message}`);
                }
            },
            error: function(xhr, status, error) {
                resultDiv.removeClass('alert-info alert-success').addClass('alert-danger');
                resultDiv.html(`<i class="fas fa-exclamation-circle"></i> <strong>Error:</strong> Failed to send test email`);
            }
        });
    };

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});
</script>
