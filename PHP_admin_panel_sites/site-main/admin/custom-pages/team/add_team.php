<?php
session_start();
include_once '../../config/DbConfig.php';
include_once '../../config/Crud.php';
include_once '../../config/functions.php';
include_once '../../auth/authentication.php';
include_once '../../header.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$crud = new Crud();



// Check if 'edit' parameter is present in the URL
$existingData = [];
if (isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    // Fetch existing team member data based on the ID provided
    $existingDataResult = $crud->getbyid('our_team', $editId);
    if ($existingDataResult['status'] === 200) {
        $existingData = $existingDataResult['data']; // Assign fetched data to the variable
    } else {
        $_SESSION['status_error'] = 'Error fetching team member data for editing';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null; // Get the ID from the form submission
    // Set form field values based on existing data if available
    $name = $_POST['name'] ?? '';
    $role = $_POST['role'] ?? '';
    $url = $_POST['url'] ?? '';
    $slug = generateSlug($name);

    // Define data array
    $data = [
        'slug' => $slug,
        'name' => $name,
        'role' => $role,
        'url' => $url
    ];

    // Handle file uploads
    $uploadsDir = 'uploads/team/';
    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;

    // Create uploads directory if it doesn't exist
    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }

    // Upload file if present and set file path in data array
    $img = isset($_FILES['img']) && $_FILES['img']['error'] == 0 ? uploadFile($_FILES['img'], $absoluteUploadsDir) : null;

    // Check if upload was successful and update data array accordingly
    if ($img && $img['status']) {
        $data['img'] = $uploadsDir . $img['file_name'];
    } else if ($img) {
        $_SESSION['status_error_img'] = "Error uploading image: " . $img['message'];
    }

    // Perform database operation
    if ($id) {
        // Fetch existing data to retain unchanged files
        $existingDataResult = $crud->getbyid('our_team', $id);
        if ($existingDataResult['status'] === 200) {
            $existingData = $existingDataResult['data'];

            if (!isset($data['img'])) {
                $data['img'] = $existingData['img'];
            }
        }

        // Update the team member with the new data
        $updateResult = $crud->update('our_team', $data, ['id' => $id]);
        if ($updateResult === true) {
            $_SESSION['status'] = 'Team member updated successfully';
        } else {
            $_SESSION['status_error'] = 'Error updating team member: ' . $updateResult;
        }
    } else {
        // Insert new team member
        $insertResult = $crud->insert('our_team', $data);
        if ($insertResult === true) {
            $_SESSION['status'] = 'Team member created successfully';
        } else {
            $_SESSION['status_error'] = 'Error creating team member: ' . $insertResult;
        }
    }
    header('Location: ' . $GLOBALS['site_url'] . 'team/add_team');
    exit();
}

// Delete functionality
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $deleteResult = $crud->delete('our_team', $deleteId);
    if ($deleteResult === true) {
        $_SESSION['status'] = 'Team member deleted successfully';
    } else {
        $_SESSION['status_error'] = 'Error deleting team member: ' . $deleteResult;
    }
    header('Location: ' . $GLOBALS['site_url'] . 'team/add_team');
    exit();
}

$teamMembers = $crud->getData('our_team', '', '', '');
?>

<body>
    <div class="wrapper">
        <?php include_once '../../navbar.php'; ?>
        <?php include '../../sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="card card-primary card-body">
                <?php echo alert_message(); ?>
                <div class="card-header"><?php echo isset($existingData['id']) ? 'Update Team Member' : 'Add Team Member'; ?></div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>team/add_team" method="post" enctype="multipart/form-data">
                    <div class="card card-primary  card-body">
                        <input type="hidden" name="id" value="<?php echo $existingData['id'] ?? ''; ?>">

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" required name="name" value="<?php echo $existingData['name'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="role">Role:</label>
                            <input type="text" class="form-control" required name="role" value="<?php echo $existingData['role'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="url">URL:</label>
                            <input type="text" class="form-control" name="url" value="<?php echo $existingData['url'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="img">Image:</label>
                            <?php if (!empty($existingData['img'])) : ?>
                                <img src="<?php echo $GLOBALS['site_url'] . $existingData['img']; ?>" alt="Image" style="max-width: 150px;">
                            <?php endif; ?>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="img" id="img">
                                    <label class="custom-file-label" for="img">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <input type="submit" class="btn btn-primary" value="<?php echo isset($existingData['id']) ? 'Update Team Member' : 'Add Team Member'; ?>">
                        </div>
                    </div>
                </form>

                <script>
                    document.querySelectorAll('.custom-file-input').forEach(function(input) {
                        input.addEventListener('change', function() {
                            let fileName = this.files[0].name;
                            let label = this.nextElementSibling;
                            label.textContent = fileName;
                        });
                    });
                </script>
                <br>
                <h2>Team Members List</h2>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Team Members Data</h3>

                                <div class="card-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" id="table_search" name="table_search" class="form-control float-right" placeholder="Search">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="team_table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($teamMembers as $member) : ?>
                                            <tr>
                                                <td><?php echo $member['id']; ?></td>
                                                <td> <img style="height: 80px;" src="<?php echo $GLOBALS['site_url'] . $member['img'] ?>" alt=""></td>
                                                <td><?php echo $member['name']; ?></td>
                                                <td><?php echo $member['role']; ?></td>

                                                <td>
                                                    <a href="add_team?edit=<?php echo $member['id']; ?>">Edit</a>
                                                    <a href="add_team?delete=<?php echo $member['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>

        <?php include '../../footer.php'; ?>
    </div>

    <script>
        document.getElementById('table_search').addEventListener('keyup', function() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('table_search');
            filter = input.value.toLowerCase();
            table = document.getElementById('team_table');
            tr = table.getElementsByTagName('tr');

            for (i = 0; i < tr.length; i++) {
                tr[i].style.display = 'none'; // Initially hide all rows
                td = tr[i].getElementsByTagName('td');
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = ''; // Show the row if a match is found
                            break;
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>