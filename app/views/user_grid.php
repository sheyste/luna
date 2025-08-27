<?php if (!empty($users)): ?>
    <?php foreach ($users as $user): ?>
        <tr>
            <td data-label="Username"><?= htmlspecialchars($user['username']) ?></td>
            <td data-label="Name"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
            <td data-label="Email"><?= htmlspecialchars($user['email']) ?></td>
            <td data-label="User Type"><?= htmlspecialchars($user['user_type']) ?></td>
            <td data-label="Actions" class="text-nowrap">
                <button class="btn btn-info btn-sm" onclick="User.show(<?= htmlspecialchars($user['id']) ?>)">
                    <i class="fa fa-edit"></i> Edit
                </button>
                <button class="btn btn-danger btn-sm" onclick="User.confirm(<?= htmlspecialchars($user['id']) ?>)">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>