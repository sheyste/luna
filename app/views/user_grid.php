<table class="table table-hover table-bordered table-striped table-sm" id="users_grid">
                    <thead class="text-center table-dark fw-bold">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">First Name</th>
                            <th width="15%">Last Name</th>
                            <th width="20%">Email</th>
                            <th width="15%">Sign Date</th>
                            <th width="15%" class="td-actions">Options</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php if (!empty($data['users'])): ?>
                            <?php foreach ($data['users'] as $row): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['first_name']; ?></td>
                                <td><?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['sign_date']; ?></td>
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
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>