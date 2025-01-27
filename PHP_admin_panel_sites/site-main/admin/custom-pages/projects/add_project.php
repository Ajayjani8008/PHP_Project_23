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
    // Fetch existing project data based on the ID provided
    $existingDataResult = $crud->getbyid('projects', $editId);
    if ($existingDataResult['status'] === 200) {
        $existingData = $existingDataResult['data']; // Assign fetched data to the variable
    } else {
        $_SESSION['status_error'] = 'Error fetching project data for editing';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null; // Get the ID from the form submission
    // Set form field values based on existing data if available
    $project_name = $_POST['project_name'] ?? '';
    $project_sub_title = $_POST['project_sub_title'] ?? '';
    $project_heading = $_POST['project_heading'] ?? '';
    $client = $_POST['client'] ?? '';
    $categories = $_POST['categories'] ?? '';
    $date = $_POST['date'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $additional_content = $_POST['additional_content'] ?? '';

    $slug = make_slug_base_name($project_name);

    // Define data array
    $data = [
        'project_name' => $project_name,
        'project_sub_title' => $project_sub_title,
        'project_heading' => $project_heading,
        'client' => $client,
        'categories' => $categories,
        'date' => $date,
        'tags' => $tags,
        'additional_content' => $additional_content,
        'slug' => $slug
    ];

    // Handle file uploads
    $uploadsDir = 'uploads/project/';
    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;

    // Create uploads directory if it doesn't exist
    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }

    // Upload files if present and set file path in data array
    $project_page_img = uploadFile($_FILES['project_page_img'], $absoluteUploadsDir);
    $featured_img = uploadFile($_FILES['featured_img'], $absoluteUploadsDir);
    $sub_image = uploadFile($_FILES['sub_image'], $absoluteUploadsDir);

    // Check if uploads were successful and update data array accordingly
    if ($project_page_img['status']) {
        $data['project_page_img'] = $uploadsDir . $project_page_img['file_name'];
    } else {
        $_SESSION['status_error_img'] = "Error uploading project  image: " . $project_page_img['message'];
    }
    if ($featured_img['status']) {
        $data['featured_img'] = $uploadsDir . $featured_img['file_name'];
    } else {
        $_SESSION['status_error_img'] = "Error uploading featured image: " . $featured_img['message'];
    }
    if ($sub_image['status']) {
        $data['sub_image'] = $uploadsDir . $sub_image['file_name'];
    } else {
        $_SESSION['status_error_img'] = "Error uploading sub image: " . $sub_image['message'];
    }

    // Perform database operation
    if ($id) {
        // Update the project with the new data
        $updateResult = $crud->update('projects', $data, ['id' => $id]);
        if ($updateResult === true) {
            $_SESSION['status'] = 'Project updated successfully';
        } else {
            $_SESSION['status_error'] = 'Error updating project: ' . $updateResult;
        }
    } else {
        // Insert new project
        $insertResult = $crud->insert('projects', $data);
        if ($insertResult === true) {
            $_SESSION['status'] = 'Project created successfully';
        } else {
            $_SESSION['status_error'] = 'Error creating project: ' . $insertResult;
        }
    }
    header('Location: ' . $GLOBALS['site_url'] . 'projects/add_project');
    exit();
}

// Delete functionality
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $deleteResult = $crud->delete('projects', $deleteId);
    if ($deleteResult === true) {
        $_SESSION['status'] = 'Project deleted successfully';
    } else {
        $_SESSION['status_error'] = 'Error deleting project: ' . $deleteResult;
    }
    header('Location: ' . $GLOBALS['site_url'] . 'projects/add_project');
    exit();
}

$projects = $crud->getData('projects', '', '', '');
?>


<body>
    <div class="wrapper">
        <?php include_once '../../navbar.php'; ?>
        <?php include '../../sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="card card-primary card-body">
                <?php echo alert_message(); ?>

                <div class="card-header">Manage Projects</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>projects/add_project" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                    <input type="hidden" name="id" value="<?php echo $existingData['id'] ?? ''; ?>">
                    
                        <div class="form-group">
                            <label for="project_name">Project Name:</label>
                            <input type="text" class="form-control" required name="project_name" value="<?php echo $existingData['project_name'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="project_sub_title">Project Sub title:</label>
                            <input type="text" class="form-control" required name="project_sub_title" value="<?php echo $existingData['project_sub_title'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="project_heading">Project Heading:</label>
                            <textarea class="form-control" required name="project_heading"><?php echo $existingData['project_heading'] ?? ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="client">Client:</label>
                            <input type="text" class="form-control" required name="client" value="<?php echo $existingData['client'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="categories">Categories:</label>
                            <input type="text" class="form-control" required name="categories" value="<?php echo $existingData['categories'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="date">Date:</label>
                            <input type="date" class="form-control" required name="date" value="<?php echo $existingData['date'] ?? ''; ?>">
                        </div>
                        <!-- <div class="form-group">
                            <label>Date:</label>
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" required name="date" value="<?php // echo $existingData['date'] ?? ''; 
                                                                                                                                                        ?>">
                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label for="tags">Tags:</label>
                            <input type="text" class="form-control" required name="tags" value="<?php echo $existingData['tags'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="additional_content">Additional Content:</label>
                            <textarea class="form-control" name="additional_content" id="additional_content"><?php echo $existingData['additional_content'] ?? ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="project_page_img">Porject Page View Image:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="project_page_img" name="project_page_img" onchange="previewImage(event, 'project_page_img_preview')">
                                    <label class="custom-file-label" for="project_page_img">Choose file</label>
                                </div>
                            </div>
                            <?php if (!empty($existingData['project_page_img'])) : ?>
                                <img src="<?php echo $GLOBALS['site_url'] . $existingData['project_page_img']; ?>" alt="Main Image" class="img-thumbnail" style="width:100px;height:auto;" id="project_page_img_preview">
                            <?php else : ?>
                                <img src="" alt="Main Image" class="img-thumbnail" style="width:100px;height:auto;display:none;" id="project_page_img_preview">
                            <?php endif; ?>
                        </div>


                        <div class="form-group">
                            <label for="featured_img">Project Detail Featurd Image:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="featured_img" name="featured_img" onchange="previewImage(event, 'featured_img_preview')">
                                    <label class="custom-file-label" for="featured_img">Choose file</label>
                                </div>
                            </div>
                            <?php if (!empty($existingData['featured_img'])) : ?>
                                <img src="<?php echo $GLOBALS['site_url'] . $existingData['featured_img']; ?>" alt="Main Image" class="img-thumbnail" style="width:100px;height:auto;" id="featured_img_preview">
                            <?php else : ?>
                                <img src="" alt="Main Image" class="img-thumbnail" style="width:100px;height:auto;display:none;" id="featured_img_preview">
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="sub_image">Sub Image:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="sub_image" name="sub_image" onchange="previewImage(event, 'sub_image_preview')">
                                    <label class="custom-file-label" for="sub_image">Choose file</label>
                                </div>
                            </div>
                            <?php if (!empty($existingData['sub_image'])) : ?>
                                <img src="<?php echo $GLOBALS['site_url'] . $existingData['sub_image']; ?>" alt="Sub Image" class="img-thumbnail" style="width:100px;height:auto;" id="sub_image_preview">
                            <?php else : ?>
                                <img src="" alt="Sub Image" class="img-thumbnail" style="width:100px;height:auto;display:none;" id="sub_image_preview">
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Add Project</button>
                        </div>
                    </div>
                </form>

                <h2>Existing Projects</h2>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">

                                <h3 class="card-title">Existing Projects</h3>

                                <div class="card-tools">
                                    <div class="input-group input-group-sm" id="projectsTable-searchInput" style="width: 150px;">
                                        <input type="text" name="table_search" id="table_search" class="form-control float-right" placeholder="Search">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped" id="projects_table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Porject Name</th>
                                        <th>Main Heading</th>
                                        <th>Client</th>
                                        <th>Categories</th>
                                        <th>Date</th>
                                        <th>Tags</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($projects as $project) : ?>
                                        <tr>
                                            <td><?php echo $project['id']; ?></td>
                                            <td><?php echo $project['project_name']; ?></td>
                                            <td><?php echo $project['project_heading']; ?></td>
                                            <td><?php echo $project['client']; ?></td>
                                            <td><?php echo $project['categories']; ?></td>
                                            <td><?php echo $project['date']; ?></td>
                                            <td><?php echo $project['tags']; ?></td>
                                            <td>
                                                <a href="?edit=<?php echo $project['id']; ?>" class="btn btn-warning">Edit</a>
                                                <a href="?delete=<?php echo $project['id']; ?>" class="btn btn-danger">Delete</a>
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
        <?php include '../../footer.php'; ?>
    </div>

    <script>
        $(document).ready(function() {
            $('#additional_content').summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
                focus: true // Set focus to editable area after initializing summernote
            });
        });

        function previewImage(event, previewId) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById(previewId);
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }


        function previewImage(event, previewId) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById(previewId);
                output.src = reader.result;
                output.style.display = 'block'; // Ensure the preview image is displayed
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
        document.getElementById('table_search').addEventListener('keyup', function() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('table_search');
            filter = input.value.toLowerCase();
            table = document.getElementById('projects_table');
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