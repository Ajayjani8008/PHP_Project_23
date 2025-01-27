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
    // Fetch existing career data based on the ID provided
    $existingDataResult = $crud->getbyid('careers', $editId);
    if ($existingDataResult['status'] === 200) {
        $existingData = $existingDataResult['data']; // Assign fetched data to the variable
    } else {
        $_SESSION['status_error'] = 'Error fetching career data for editing';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null; // Get the ID from the form submission
    // Set form field values based on existing data if available
    $career_name = $_POST['career_name'] ?? '';
    $career_short_description = $_POST['career_short_description'] ?? '';
    $main_heading = $_POST['main_heading'] ?? '';
    $main_description = $_POST['main_description'] ?? '';
    $career_content = $_POST['career_content'] ?? '';
    $highlighted_lines = $_POST['highlighted_lines'] ?? '';
    $slug = generateSlug($career_name);
    // $slug= make_slug_base_name($career_name);

    // Define data array
    $data = [
        'slug' => $slug,
        'career_name' => $career_name,
        'career_short_description' => $career_short_description,
        'main_heading' => $main_heading,
        'main_description' => $main_description,
        'career_content' => $career_content,
        'highlighted_lines' => $highlighted_lines
    ];

    // Handle file uploads
    $uploadsDir = 'uploads/career/';
    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;

    // Create uploads directory if it doesn't exist
    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }

    // Upload file if present and set file path in data array
    $top_img = isset($_FILES['career_top_image']) && $_FILES['career_top_image']['error'] == 0 ? uploadFile($_FILES['career_top_image'], $absoluteUploadsDir) : null;
    $bottom_img = isset($_FILES['career_bottom_image']) && $_FILES['career_bottom_image']['error'] == 0 ? uploadFile($_FILES['career_bottom_image'], $absoluteUploadsDir) : null;

    // Check if uploads were successful and update data array accordingly

    if ($top_img && $top_img['status']) {
        $data['top_img'] =  $uploadsDir . $top_img['file_name'];
    } else if ($top_img) {
        $_SESSION['status_error'] = "Error uploading top image: " . $top_img['message'];
    }

    if ($bottom_img && $bottom_img['status']) {
        $data['bottom_img'] = $uploadsDir . $bottom_img['file_name'];
    } else if ($bottom_img) {
        $_SESSION['status_error'] = "Error uploading bottom image: " . $bottom_img['message'];
    }


    // Perform database operation
    if ($id) {
        // Fetch existing data to retain unchanged files
        $existingDataResult = $crud->getbyid('careers', $id);
        if ($existingDataResult['status'] === 200) {
            $existingData = $existingDataResult['data'];

            if (!isset($data['top_img'])) {
                $data['top_img'] = $existingData['top_img'];
            }
            if (!isset($data['bottom_img'])) {
                $data['bottom_img'] = $existingData['bottom_img'];
            }
        }

        // Update the career with the new data
        $updateResult = $crud->update('careers', $data, ['id' => $id]);
        if ($updateResult === true) {
            $_SESSION['status'] = 'career updated successfully';
        } else {
            $_SESSION['status_error'] = 'Error updating career: ' . $updateResult;
        }
    } else {
        // Insert new career
        $insertResult = $crud->insert('careers', $data);
        if ($insertResult === true) {
            $_SESSION['status'] = 'career created successfully';
        } else {
            $_SESSION['status_error'] = 'Error creating career: ' . $insertResult;
        }
    }
    header('Location: ' . $GLOBALS['site_url'] . 'career/add_career');
    exit();
}

// Delete functionality
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $deleteResult = $crud->delete('careers', $deleteId);
    if ($deleteResult === true) {
        $_SESSION['status'] = 'career deleted successfully';
    } else {
        $_SESSION['status_error'] = 'Error deleting career: ' . $deleteResult;
    }
    header('Location: ' . $GLOBALS['site_url'] . 'career/add_career');
    exit();
}

$careers = $crud->getData('careers', '', '', '');
?>

<body>
    <div class="wrapper">
        <?php include_once '../../navbar.php'; ?>
        <?php include '../../sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="card card-primary card-body">
                <?php echo alert_message(); ?>

                <div class="card-header"><?php echo isset($existingData['id']) ? 'Edit Career' : 'Add New Career'; ?></div>

                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>career/add_career" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                        <input type="hidden" name="id" value="<?php echo $existingData['id'] ?? ''; ?>">

                        <div class="form-group">
                            <label for="career_name">Career Name:</label>
                            <input type="text" class="form-control" required name="career_name" value="<?php echo $existingData['career_name'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="career_short_description">Short Description:</label>
                            <input type="text" class="form-control" required name="career_short_description" value="<?php echo $existingData['career_short_description'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="main_heading">Main Heading:</label>
                            <input type="text" class="form-control" required name="main_heading" value="<?php echo $existingData['main_heading'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="main_description">Main Description:</label>
                            <input type="text" class="form-control" required name="main_description" value="<?php echo $existingData['main_description'] ?? ''; ?>">
                        </div>


                        <div class="form-group">
                            <label for="highlighted_lines">Career Highlighted Lines:</label>
                            <input type="text" class="form-control" name="highlighted_lines" value="<?php echo $existingData['highlighted_lines'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="career_content">Content:</label>
                            <textarea class="form-control" name="career_content" id="career_content" rows="10" cols="80"><?php echo $existingData['career_content'] ?? ''; ?></textarea>
                        </div>

                        <!-- Top Image Upload Section -->
                        <div class="form-group">
                            <label for="career_top_image">Career Top Image:</label>
                            <?php if (!empty($existingData['top_img'])) : ?>
                                <img src="<?php echo $GLOBALS['site_url'] . $existingData['top_img']; ?>" alt="Top Image" style="max-width: 150px;" id="top_image_preview">
                            <?php else : ?>
                                <img src="#" alt="Top Image Preview" style="max-width: 150px; display: none;" id="top_image_preview">
                            <?php endif; ?>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="career_top_image" id="career_top_image" onchange="previewImage(event, 'top_image_preview')">
                                    <label class="custom-file-label" for="career_top_image">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Image Upload Section -->
                        <div class="form-group">
                            <label for="career_bottom_image">Career Bottom Image:</label>
                            <?php if (!empty($existingData['bottom_img'])) : ?>
                                <img src="<?php echo $GLOBALS['site_url'] . $existingData['bottom_img']; ?>" alt="Bottom Image" style="max-width: 150px;" id="bottom_image_preview">
                            <?php else : ?>
                                <img src="#" alt="Bottom Image Preview" style="max-width: 150px; display: none;" id="bottom_image_preview">
                            <?php endif; ?>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="career_bottom_image" id="career_bottom_image" onchange="previewImage(event, 'bottom_image_preview')">
                                    <label class="custom-file-label" for="career_bottom_image">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">

                            <input type="submit" class="btn btn-primary" value="<?php echo isset($existingData['id']) ? 'Update career' : 'Add career'; ?>">
                        </div>
                    </div>

                </form>
                <script>
                    CKEDITOR.replace('career_content');

                    document.querySelectorAll('.custom-file-input').forEach(function(input) {
                        input.addEventListener('change', function() {
                            let fileName = this.files[0].name;
                            let label = this.nextElementSibling;
                            label.textContent = fileName;
                        });
                    });
                </script>

                <br>

                <h2>
                    Careers List
                </h2>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Career Data</h3>

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
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Short Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="career_table">
                                        <?php foreach ($careers as $career) : ?>
                                            <tr>
                                                <td><?php echo $career['id']; ?></td>
                                                <td><?php echo $career['career_name']; ?></td>
                                                <td><?php echo $career['career_short_description']; ?></td>
                                                <td>
                                                    <a href="add_career?edit=<?php echo $career['id']; ?>">Edit</a>
                                                    <a href="add_career?delete=<?php echo $career['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
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
        function previewImage(event, previewId) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById(previewId);
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        document.querySelectorAll('.custom-file-input').forEach(function(input) {
            input.addEventListener('change', function() {
                let fileName = this.files[0].name;
                let label = this.nextElementSibling;
                label.textContent = fileName;
            });
        });
    </script>
    <script>
        document.getElementById('table_search').addEventListener('keyup', function() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('table_search');
            filter = input.value.toLowerCase();
            table = document.getElementById('career_table');
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