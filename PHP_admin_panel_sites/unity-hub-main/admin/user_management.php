<?php
session_start();
include_once 'config/DbConfig.php';
include_once 'config/Crud.php';
include_once 'config/Validation.php';
include_once 'config/functions.php';
include_once 'includes/header.php';
$conn = new DbConfig();
$crud = new Crud();

if ($_SESSION['user_data']['role_id'] != 1) {
    header("Location: " . $GLOBALS['admin_site_url']);
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id']) && isset($_POST['new_role_id'])) {
    $userId = $_POST['user_id'];
    $newRoleId = $_POST['new_role_id'];

    $updateRoleQuery = "UPDATE users SET role_id = ? WHERE id = ?";
    $stmt = $crud->connection->prepare($updateRoleQuery);
    $stmt->bind_param("ii", $newRoleId, $userId);

    if ($stmt->execute()) {
        echo "Role updated successfully.";
    } else {
        echo "Error updating role: " . $stmt->error;
    }

    $stmt->close();
}

// Handle form submission for deleting users
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user_id'])) {


    $deleteUserId = $_POST['delete_user_id'];


    // Delete the user from the database
    $deleteUserQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $crud->connection->prepare($deleteUserQuery);
    $stmt->bind_param("i", $deleteUserId);

    if ($stmt->execute()) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
}


// Tables involved
$tables = ['users', 'roles', 'role_permissions'];
$joins = [
    'roles',
    'role_permissions'
];
$joinConditions = [
    'users.role_id = roles.id',
    'roles.id = role_permissions.role_id'
];
$columns = 'users.id, users.name, users.username, users.email, roles.role_name';

// Fetch users and roles
$usersData = $crud->getMJoinData($tables, '', $columns, $joins, $joinConditions);

// Fetch permissions for each role
$permissionsQuery = "SELECT users.id AS user_id, permissions.permission_name
    FROM users
    JOIN role_permissions ON users.role_id = role_permissions.role_id
    JOIN permissions ON role_permissions.permission_id = permissions.id
    ORDER BY users.id";
$permissionsResult = $crud->connection->query($permissionsQuery);

// Organize permissions by user ID
$permissionsByUser = [];
while ($permRow = $permissionsResult->fetch_assoc()) {
    $permissionsByUser[$permRow['user_id']][] = $permRow['permission_name'];
}
?>

<?php
include_once 'includes/navbar.php';
?>
<?php
include_once "includes/sidebar.php";
?>

<main id="main" class="main">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Manage Users</h5>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">User ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">User Role</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Permissions</th>
                            <th scope="col">Assign Role</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usersData as $user) { ?>
                            <tr>
                                <td scope="row"><?php echo $user['id']; ?></td>
                                <td><?php echo $user['name']; ?></td>
                                <td><?php echo $user['role_name']; ?></td>
                                <td><?php echo $user['username']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php
                                    if (isset($permissionsByUser[$user['id']])) {
                                        echo implode(', ', $permissionsByUser[$user['id']]);
                                    } else {
                                        echo "None";
                                    }
                                    ?></td>
                                <td>
                                    <?php
                                    if ($user['role_name'] !== 'super_admin'):
                                    ?>
                                        <form method="POST" action="" style="display: flex; align-items: center; gap: 10px;">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                                            <select name="new_role_id" class="form-select" style="flex: 1;">
                                                <?php
                                                // fetch all roles
                                                $rolesQuery = "SELECT id, role_name FROM roles";
                                                $rolesResult = $crud->connection->query($rolesQuery);
                                                while ($role = $rolesResult->fetch_assoc()) {
                                                    echo '<option value="' . $role['id'] . '"' . ($role['id'] == $user['role_id'] ? ' selected' : '') . '>' . $role['role_name'] . '</option>';
                                                }
                                                ?>
                                            </select>

                                            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you certain you want to assign this role to the user?');" style="flex-shrink: 0;">
                                                Assign Role
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                </td>
                                <td>
                                    <?php
                                    if ($user['role_name'] !== 'super_admin'):
                                    ?>
                                        <form method="POST" action="">
                                            <input type="hidden" name="delete_user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" name="delete_user" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<?php
include_once 'includes/footer.php';
?>