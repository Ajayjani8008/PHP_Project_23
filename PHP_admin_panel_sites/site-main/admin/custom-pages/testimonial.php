<?php
session_start();
include_once '../config/Dbconfig.php';
include_once '../config/Crud.php';
include_once '../config/functions.php';
include_once '../auth/authentication.php';
include_once '../header.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$crud = new Crud();

// Check if 'edit' parameter is present in the URL
$existingData = [];
if (isset($_GET['edit_testimonial'])) {
    $editId = $_GET['edit_testimonial'];
    // Fetch existing testimonial data based on the ID provided
    $existingDataResult = $crud->getbyid('testimonials', $editId);
    if ($existingDataResult['status'] === 200) {
        $existingData = $existingDataResult['data']; // Assign fetched data to the variable
    } else {
        $_SESSION['status_error'] = 'Error fetching testimonial data for editing';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['testimonial_id'] ?? null; // Get the ID from the form submission
    // Set form field values based on existing data if available
    $name = $_POST['name'] ?? '';
    $position = $_POST['position'] ?? '';
    $message = $_POST['message'] ?? '';
    $existing_image = $_POST['existing_image'] ?? '';

    // Define data array
    $data = [
        'name' => $name,
        'position' => $position,
        'message' => $message
    ];

    // Handle file uploads
    $uploadsDir = 'uploads/home/';
    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;

    // Create uploads directory if it doesn't exist
    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }

    // Upload file if present and set file path in data array
    $testimonial_image = isset($_FILES['image']) && $_FILES['image']['error'] == 0 ? uploadFile($_FILES['image'], $absoluteUploadsDir) : null;

    // Check if upload was successful and update data array accordingly
    if ($testimonial_image && $testimonial_image['status']) {
        $data['image'] =  $uploadsDir . $testimonial_image['file_name'];
    } else if ($testimonial_image) {
        $_SESSION['status_error'] = "Error uploading image: " . $testimonial_image['message'];
    } else {
        $data['image'] = $existing_image; // Retain existing image if no new image is uploaded
    }

    // Perform database operation
    if ($id) {
        // Fetch existing data to retain unchanged files
        $existingDataResult = $crud->getbyid('testimonials', $id);
        if ($existingDataResult['status'] === 200) {
            $existingData = $existingDataResult['data'];
            if (!isset($data['image'])) {
                $data['image'] = $existingData['image'];
            }
        }

        // Update the testimonial with the new data
        $updateResult = $crud->update('testimonials', $data, ['id' => $id]);
        if ($updateResult === true) {
            $_SESSION['status'] = 'Testimonial updated successfully';
        } else {
            $_SESSION['status_error'] = 'Error updating testimonial: ' . $updateResult;
        }
    } else {
        // Insert new testimonial
        $insertResult = $crud->insert('testimonials', $data);
        if ($insertResult === true) {
            $_SESSION['status'] = 'Testimonial created successfully';
        } else {
            $_SESSION['status_error'] = 'Error creating testimonial: ' . $insertResult;
        }
    }
    header('Location: ' . $GLOBALS['site_url'] . 'testimonial');
    exit();
}

if (isset($_GET['delete_testimonial'])) {
    $deleteId = $_GET['delete_testimonial'];
    $deleteResult = $crud->delete('testimonials', $deleteId); // Pass $deleteId directly
    if ($deleteResult === true) {
        $_SESSION['status'] = 'Testimonial deleted successfully';
    } else {
        $_SESSION['status_error'] = 'Error deleting testimonial: ' . $deleteResult;
    }
    header('Location: ' . $GLOBALS['site_url'] . 'testimonial');
    exit();
}

$testimonials = $crud->getData('testimonials', '', '', '');
?>

<body>
    <div class="wrapper">
        <?php include_once '../navbar.php'; ?>
        <?php include '../sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="card card-primary card-body">
                <?php
                echo alert_message();
                ?>
                <div class="card-header"><?php echo isset($existingData['id']) ? 'Edit Testimonial' : 'Add New Testimonial'; ?></div>

                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>testimonial" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                        <input type="hidden" name="testimonial_id" value="<?php echo $existingData['id'] ?? ''; ?>">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" required name="name" value="<?php echo $existingData['name'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="position">Position:</label>
                            <input type="text" class="form-control" required name="position" value="<?php echo $existingData['position'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="message">Message:</label>
                            <textarea class="form-control" required name="message" rows="3"><?php echo $existingData['message'] ?? ''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <?php if (!empty($existingData['image'])) : ?>
                                <img id="imagePreview" src="<?php echo $GLOBALS['site_url'] . $existingData['image']; ?>" alt="Image" style="max-width: 100px; max-height: 100px;">
                            <?php else : ?>
                                <img id="imagePreview" src="#" alt="Image" style="display: none; max-width: 100px; max-height: 100px;">
                            <?php endif; ?>
                            <input type="hidden" name="existing_image" value="<?php echo $existingData['image'] ?? ''; ?>">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image" id="image" onchange="previewImage(event)">
                                    <label class="custom-file-label" for="image">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <input type="submit" class="btn btn-primary" name="save_data" value="<?php echo isset($existingData['id']) ? 'Update Testimonial' : 'Add Testimonial'; ?>">
                        </div>
                    </div>
                </form>

            </div>



            <!-- <div class="row"> -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="background-color:lightgoldenrodyellow">
                        <h3 class="card-title">Testimonials List</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" id="table_search" class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0 table-responsive" style="max-height: 665px; overflow-y: auto;">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Author</th>
                                    <th>Author Position</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="testimonial_table">
                                <?php foreach ($testimonials as $testimonial) : ?>
                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td><?php echo htmlspecialchars($testimonial['id']); ?></td>
                                        <td><?php echo htmlspecialchars($testimonial['name']); ?></td>
                                        <td><?php echo htmlspecialchars($testimonial['position']); ?></td>
                                        <td>
                                            <?php if (!empty($testimonial['image'])) : ?>
                                                <img src="<?php echo htmlspecialchars($GLOBALS['site_url'] . $testimonial['image']); ?>" alt="Image" style="max-width: 100px; max-height: 100px;">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="testimonial?edit_testimonial=<?php echo htmlspecialchars($testimonial['id']); ?>">Edit</a>
                                            <a href="testimonial?delete_testimonial=<?php echo htmlspecialchars($testimonial['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                    <tr class="expandable-body d-none">
                                        <td colspan="5">
                                            <p style="display: none;">
                                                <?php echo htmlspecialchars($testimonial['message']); ?>
                                            </p>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- </div> -->



        </div>
        <?php echo alert_message(); ?>
        <?php include_once '../footer.php'; ?>
    </div>


    <script>
        // Display selected file name in the input field
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });
    </script>
    <script>
        // Function to preview the selected image
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var imagePreview = document.getElementById('imagePreview');
                imagePreview.src = reader.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
        document.getElementById('table_search').addEventListener('keyup', function() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('table_search');
            filter = input.value.toLowerCase();
            table = document.getElementById('testimonial_table');
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