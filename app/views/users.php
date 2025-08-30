<?php include_once __DIR__ . '/layout/header.php'; ?>

<style>
    /* Responsive table for mobile */
    @media (max-width: 767px) {
        #userTable thead {
            display: none;
        }

        #userTable, #userTable tbody, #userTable tr, #userTable td {
            display: block;
            width: 100%;
        }

        #userTable tr {
            margin-bottom: 1rem;
            border: 1px solid #ddd;
        }

        #userTable td {
            text-align: right;
            padding-left: 50%;
            position: relative;
            border: none;
            border-bottom: 1px solid #eee;
        }

        #userTable td:last-of-type {
            border-bottom: 0;
        }

        #userTable td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            width: 45%;
            font-weight: bold;
            text-align: left;
        }
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Users</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
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
                     <i class="fa fa-bug me-1"></i> Debug Email Config
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
                dataType: 'JSON',
                data: data,
                success: function(response) {
                    if (response.success) {
                        alert('Test email sent successfully to ' + name);
                    } else {
                        alert('Failed to send test email: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while sending the test email: ' + error);
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
                let message = 'Email Configuration Debug:\n\n';
                message += 'Environment file exists: ' + response.env_file_exists + '\n';
                message += 'SMTP Username: ' + response.smtp_username + '\n';
                message += 'SMTP Password: ' + response.smtp_password + '\n';
                message += 'From Email: ' + response.from_email + '\n';
                message += 'From Name: ' + response.from_name + '\n';
                message += 'Mail Function: ' + response.mail_function;
                alert(message);
            },
            error: function() {
                alert('Error checking email configuration');
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
});
</script>