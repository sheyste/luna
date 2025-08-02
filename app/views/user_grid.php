<table class="table table-hover table-bordered table-striped table-sm" id="users_grid">
                    <thead class="text-center table-dark fw-bold">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Username</th>
                            <th width="15%">First Name</th>
                            <th width="15%">Last Name</th>
                            <th width="20%">Email</th>
                            <th width="10%">User Type</th>
                            <th width="15%">Sign Date</th>
                            <th width="10%" class="td-actions">Options</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php if (!empty($data['users'])): ?>
                            <?php foreach ($data['users'] as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['username'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['first_name']) ?></td>
                                <td><?= htmlspecialchars($row['last_name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['user_type'] ?? 'User') ?></td>
                                <td><?= htmlspecialchars($row['sign_date']) ?></td>
                                <td>
                                    <button type="button" id="<?php echo $row['id']; ?>" class="btn btn-info btn-sm" onclick="User.show(this);">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" id="<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="User.confirm(this);">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>