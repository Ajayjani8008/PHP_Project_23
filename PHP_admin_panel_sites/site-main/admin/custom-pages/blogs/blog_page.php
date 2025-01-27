<?php
session_start();
include_once '../../config/Dbconfig.php';
include_once '../../config/Crud.php';
include_once '../../config/functions.php';
include_once '../../auth/authentication.php';
include_once '../../header.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$crud = new Crud();
$result = $crud->getData('blog_page', "id=1", '', '');

$blog_page = [];
if (!empty($result)) {
    $blog_page = $result[0];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_blog_page'])) {
        // Update blog section data
        $blog_page_heading = $_POST['blog_page_heading'] ?? '';
        $blog_page_description = $_POST['blog_page_description'] ?? '';

        // Construct the section data
        $blog_page_data = [
            'blog_page_heading' => $blog_page_heading,
            'blog_page_description' => $blog_page_description
        ];

        $uploadsDir = 'uploads/blog/';
        $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;

        if (!file_exists($absoluteUploadsDir)) {
            mkdir($absoluteUploadsDir, 0775, true);
        }

        $blog_page_img = isset($_FILES['blog_page_featured_img']) && $_FILES['blog_page_featured_img']['error'] == 0 ? uploadFile($_FILES['blog_page_featured_img'], $absoluteUploadsDir) : null;

        if ($blog_page_img && $blog_page_img['status']) {
            $blog_page_data['blog_page_img'] = $uploadsDir . $blog_page_img['file_name'];
        } else if ($blog_page_img) {
            $_SESSION['status_error'] = "Error uploading top image: " . $blog_page_img['message'];
        }

        // Determine whether to insert or update
        if (!empty($result)) {
            // Update the existing row
            $updateResult = $crud->update('blog_page', $blog_page_data, ['id' => 1]);
            if ($updateResult === true) {
                $_SESSION['status'] = 'Blog Details updated successfully';
            } else {
                $_SESSION['status_error'] = 'Error updating Blog Details: ' . $updateResult;
            }
        } else {
            // Insert a new row (let the auto-increment handle the id)
            $insertResult = $crud->insert('blog_page', $blog_page_data);
            if ($insertResult === true) {
                $_SESSION['status'] = 'Blog Details inserted successfully';
            } else {
                $_SESSION['status_error'] = 'Error inserting Blog Details: ' . $insertResult;
            }
        }

        header('Location: ' . $GLOBALS['site_url'] . 'blogs/blog_page');
        exit();
    }
}

$blog_page_img = isset($blog_page['blog_page_img']) ? $blog_page['blog_page_img'] : '';
$blog_page_heading_text = isset($blog_page['blog_page_heading']) ? $blog_page['blog_page_heading'] : '';
$blog_page_description_text = isset($blog_page['blog_page_description']) ? $blog_page['blog_page_description'] : '';
?>

<body>
    <div class="wrapper">
        <!-- Header -->
        <?php include_once '../../navbar.php'; ?>

        <!-- Sidebar -->
        <?php include '../../sidebar.php'; ?>

        <div class="content-wrapper" style="min-height: 107px;">
            <div class="card card-primary card-body">
                <?php echo alert_message(); ?>

                <div class="card-header">Blog Page</div>
                <form action="<?php echo htmlspecialchars($GLOBALS["site_url"]); ?>blogs/blog_page" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="blog_page_featured_img">Blog Page Featured Image:</label>
                            <input type="file" class="form-control-file" id="blog_page_featured_img" name="blog_page_featured_img">
                            <?php if (!empty($blog_page['blog_page_img'])) { ?>
                                <img src="<?php echo $GLOBALS['site_url'] . $blog_page['blog_page_img']; ?>" alt="Blog Image" style="max-width: 200px; margin-top: 10px;">
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            <label for="blog_page_heading">Blog Page Heading:</label>
                            <input type="text" class="form-control" id="blog_page_heading" name="blog_page_heading" value="<?php echo htmlspecialchars($blog_page_heading_text); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="blog_page_description">Blog Page Description:</label>
                            <textarea class="form-control" id="blog_page_description" name="blog_page_description" rows="3" required><?php echo htmlspecialchars($blog_page_description_text); ?></textarea>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_blog_page" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>

            </div>
            <!-- Display status messages -->
            <?php echo alert_message(); ?>
        </div>

        <!-- Footer -->
        <?php include '../../footer.php'; ?>
    </div>
</body>

</html>