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
    // Fetch existing blog data based on the ID provided
    $existingDataResult = $crud->getbyid('blogs', $editId);
    if ($existingDataResult['status'] === 200) {
        $existingData = $existingDataResult['data']; // Assign fetched data to the variable
    } else {
        $_SESSION['status_error'] = 'Error fetching blog data for editing';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null; // Get the ID from the form submission
    // Set form field values based on existing data if available
    $main_heading = $_POST['main_heading'] ?? '';
    $main_description = $_POST['main_description'] ?? '';
    $blog_author = $_POST['blog_author'] ?? '';
    $blog_author_role = $_POST['blog_author_role'] ?? '';
    $blog_views = $_POST['blog_views'] ?? '';
    $blog_comment_count = $_POST['blog_comment_count'] ?? '';
    $blog_heading = $_POST['blog_heading'] ?? '';
    $blog_short_description = $_POST['blog_short_description'] ?? '';
    $blog_content = $_POST['blog_content'] ?? '';
    // $highlighted_lines = $_POST['highlighted_lines'] ?? '';
    $slug = make_slug_base_name($blog_heading);

    // Define data array
    $data = [
        'main_heading' => $main_heading,
        'main_description' => $main_description,
        'blog_author' => $blog_author,
        'blog_author_role' => $blog_author_role,
        'blog_views' => $blog_views,
        'blog_comment_count' => $blog_comment_count,
        'blog_heading' => $blog_heading,
        'blog_short_description' => $blog_short_description,
        'blog_content' => $blog_content,
        // 'highlighted_lines' => $highlighted_lines,
        'slug' => $slug,
    ];

    // Handle file uploads
    $uploadsDir = 'uploads/blog/';
    // $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;
    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;

    // Create uploads directory if it doesn't exist
    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }

    // Upload file if present and set file path in data array
    $blog_img = isset($_FILES['blog_img']) && $_FILES['blog_img']['error'] == 0 ? uploadFile($_FILES['blog_img'], $absoluteUploadsDir) : null;
    $top_img = isset($_FILES['blog_top_image']) && $_FILES['blog_top_image']['error'] == 0 ? uploadFile($_FILES['blog_top_image'], $absoluteUploadsDir) : null;
    $bottom_img = isset($_FILES['blog_bottom_image']) && $_FILES['blog_bottom_image']['error'] == 0 ? uploadFile($_FILES['blog_bottom_image'], $absoluteUploadsDir) : null;
    $author_img = isset($_FILES['blog_author_image']) && $_FILES['blog_author_image']['error'] == 0 ? uploadFile($_FILES['blog_author_image'], $absoluteUploadsDir) : null;

    // Check if uploads were successful and update data array accordingly
    if ($blog_img && $blog_img['status']) {
        $data['blog_img'] =  $uploadsDir . $blog_img['file_name'];
    } else if ($blog_img) {
        $_SESSION['status_error'] = "Error uploading top image: " . $blog_img['message'];
    }
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

    if ($author_img && $author_img['status']) {
        $data['author_img'] =  $uploadsDir . $author_img['file_name'];
    } else if ($author_img) {
        $_SESSION['status_error'] = "Error uploading author image: " . $author_img['message'];
    }

    // Perform database operation
    if ($id) {
        // Fetch existing data to retain unchanged files
        $existingDataResult = $crud->getbyid('blogs', $id);
        if ($existingDataResult['status'] === 200) {
            $existingData = $existingDataResult['data'];
            if (!isset($data['blog_img'])) {
                $data['blog_img'] = $existingData['blog_img'];
            }
            if (!isset($data['top_img'])) {
                $data['top_img'] = $existingData['top_img'];
            }
            if (!isset($data['bottom_img'])) {
                $data['bottom_img'] = $existingData['bottom_img'];
            }
            if (!isset($data['author_img'])) {
                $data['author_img'] = $existingData['author_img'];
            }
        }

        // Update the blog with the new data
        $updateResult = $crud->update('blogs', $data, ['id' => $id]);
        if ($updateResult === true) {
            $_SESSION['status'] = 'Blog updated successfully';
        } else {
            $_SESSION['status_error'] = 'Error updating blog: ' . $updateResult;
        }
    } else {
        // Insert new blog
        $insertResult = $crud->insert('blogs', $data);
        if ($insertResult === true) {
            $_SESSION['status'] = 'Blog created successfully';
        } else {
            $_SESSION['status_error'] = 'Error creating blog: ' . $insertResult;
        }
    }
    header('Location: ' . $GLOBALS['site_url'] . 'blogs/add_blog');
    exit();
}

// Delete functionality
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $deleteResult = $crud->delete('blogs', $deleteId);
    if ($deleteResult === true) {
        $_SESSION['status'] = 'Blog deleted successfully';
    } else {
        $_SESSION['status_error'] = 'Error deleting blog: ' . $deleteResult;
    }
    header('Location: ' . $GLOBALS['site_url'] . 'blogs/add_blog');
    exit();
}

$blogs = $crud->getData('blogs', '', '', '');
?>

<body>
    <div class="wrapper">
        <?php include_once '../../navbar.php'; ?>
        <?php include '../../sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="card card-primary card-body">
                <?php echo alert_message(); ?>
                <div class="card-header">Manage Blogs</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>blogs/add_blog" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                        <input type="hidden" name="id" value="<?php echo $existingData['id'] ?? ''; ?>">
                        <div class="form-group">
                            <label for="main_heading">Main Heading:</label>
                            <input type="text" class="form-control" required name="main_heading" value="<?php echo $existingData['main_heading'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="main_description">Main Description:</label>
                            <input type="text" class="form-control" required name="main_description" value="<?php echo $existingData['main_description'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="blog_author">Author:</label>
                            <input type="text" class="form-control" required name="blog_author" value="<?php echo $existingData['blog_author'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="blog_author_role">Author Role:</label>
                            <input type="text" class="form-control" required name="blog_author_role" value="<?php echo $existingData['blog_author_role'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="blog_views">Views:</label>
                            <input type="text" class="form-control" name="blog_views" value="<?php echo $existingData['blog_views'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="blog_comment_count">Comment Count:</label>
                            <input type="text" class="form-control" name="blog_comment_count" value="<?php echo $existingData['blog_comment_count'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="blog_heading">Blog Heading:</label>
                            <input type="text" class="form-control" required name="blog_heading" value="<?php echo $existingData['blog_heading'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="blog_short_description">Short Description:</label>
                            <input type="text" class="form-control" required name="blog_short_description" value="<?php echo $existingData['blog_short_description'] ?? ''; ?>">
                        </div>

                        <!-- <div class="form-group">
                            <label for="highlighted_lines">Blog Highlighted Lines:</label>
                            <input type="text" class="form-control" name="highlighted_lines" value="<?php //echo $existingData['highlighted_lines'] ?? ''; 
                                                                                                    ?>">
                        </div> -->

                        <div class="form-group">
                            <label for="blog_content">Content:</label>
                            <textarea class="form-control" name="blog_content" id="blog_content" rows="10" cols="80"><?php echo $existingData['blog_content'] ?? ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="blog_img">Blog Image:</label>
                            <?php if (!empty($existingData['blog_img'])) : ?>
                                <img id="blog_img_preview" src="<?php echo $GLOBALS['site_url'] . $existingData['blog_img']; ?>" alt="blog_img" style="max-width: 150px;">
                            <?php else : ?>
                                <img id="blog_img_preview" style="max-width: 150px;">
                            <?php endif; ?>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="blog_img" id="blog_img" onchange="previewImage(event, 'blog_img_preview')">
                                    <label class="custom-file-label" for="blog_img">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="blog_author_image">Author Image:</label>
                            <?php if (!empty($existingData['author_img'])) : ?>
                                <img id="blog_author_image_preview" src="<?php echo $GLOBALS['site_url'] . $existingData['author_img']; ?>" alt="Author Image" style="max-width: 150px;">
                            <?php else : ?>
                                <img id="blog_author_image_preview" style="max-width: 150px;">
                            <?php endif; ?>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="blog_author_image" id="blog_author_image" onchange="previewImage(event, 'blog_author_image_preview')">
                                    <label class="custom-file-label" for="blog_author_image">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="blog_top_image">Blog Top Image:</label>
                            <?php if (!empty($existingData['top_img'])) : ?>
                                <img id="blog_top_image_preview" src="<?php echo $GLOBALS['site_url'] . $existingData['top_img']; ?>" alt="Top Image" style="max-width: 150px;">
                            <?php else : ?>
                                <img id="blog_top_image_preview" style="max-width: 150px;">
                            <?php endif; ?>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="blog_top_image" id="blog_top_image" onchange="previewImage(event, 'blog_top_image_preview')">
                                    <label class="custom-file-label" for="blog_top_image">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="blog_bottom_image">Blog Bottom Image:</label>
                            <?php if (!empty($existingData['bottom_img'])) : ?>
                                <img id="blog_bottom_image_preview" src="<?php echo $GLOBALS['site_url'] . $existingData['bottom_img']; ?>" alt="Bottom Image" style="max-width: 150px;">
                            <?php else : ?>
                                <img id="blog_bottom_image_preview" style="max-width: 150px;">
                            <?php endif; ?>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="blog_bottom_image" id="blog_bottom_image" onchange="previewImage(event, 'blog_bottom_image_preview')">
                                    <label class="custom-file-label" for="blog_bottom_image">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                            </div>
                        </div>

                        <div class="card_footer">
                            <input type="submit" class="btn btn-primary" value="<?php echo isset($existingData['id']) ? 'Update Blog' : 'Add Blog'; ?>">
                        </div>
                    </div>
                </form>

                <h2>Blogs List</h2>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Blog Data</h3>

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
                                <table class="table table-hover text-nowrap" id="blogs_table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Heading</th>
                                            <th>Author</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($blogs as $blog) : ?>
                                            <tr>
                                                <td><?php echo $blog['id']; ?></td>
                                                <td><?php echo $blog['main_heading']; ?></td>
                                                <td><?php echo $blog['blog_author']; ?></td>
                                                <td><?php $date = new DateTime($blog['updated_at']);
                                                    echo $date->format('F j, Y'); ?></td>
                                                <td>
                                                    <a href="add_blog?edit=<?php echo $blog['id']; ?>">Edit</a>
                                                    <a href="add_blog?delete=<?php echo $blog['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
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
        $(document).ready(function() {
            $('#blog_content').summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
                focus: true // Set focus to editable area after initializing summernote
            });
        });

        function previewImage(event, previewId) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById(previewId);
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <script>
        document.getElementById('table_search').addEventListener('keyup', function() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('table_search');
            filter = input.value.toLowerCase();
            table = document.getElementById('blogs_table');
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