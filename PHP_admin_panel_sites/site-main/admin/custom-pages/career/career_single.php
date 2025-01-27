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
$blog_details_page = $crud->getData('blog_details_page', "id=1", '', '')[0];

// Check if the row with id = 1 exists
if (!$blog_details_page) {
    // If the row does not exist, initialize an empty array
    $blog_details_page = [
        'blog_details_title' => '',
        'blogs_section_heading' => '',
        'blogs_section_title' => '',
        'blogs_section_description' => ''
    ];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_blog_section'])) {
        // Update blog section data
        $blog_details_title = $_POST['blog_details_title'] ?? '';
        $blogs_section_heading = $_POST['blogs_section_heading'] ?? '';
        $blogs_section_title = $_POST['blogs_section_title'] ?? '';
        $blogs_section_description = $_POST['blogs_section_description'] ?? '';

        // Construct the section data
        $section_data = [
            'blog_details_title' => $blog_details_title,
            'blogs_section_heading' => $blogs_section_heading,
            'blogs_section_title' => $blogs_section_title,
            'blogs_section_description' => $blogs_section_description
        ];

        // Determine whether to insert or update
        if ($crud->getData('blog_details_page', "id=1", '', '')) {
            // Update the existing row
            $updateResult = $crud->update('blog_details_page', $section_data, ['id' => 1]);
            if ($updateResult === true) {
                $_SESSION['status'] = 'Blog Details updated successfully';
            } else {
                $_SESSION['status'] = 'Error updating Blog Details: ' . $updateResult;
            }
        } else {
            // Insert a new row (let the auto-increment handle the id)
            $insertResult = $crud->insert('blog_details_page', $section_data);
            if ($insertResult === true) {
                $_SESSION['status'] = 'Blog Details inserted successfully';
            } else {
                $_SESSION['status'] = 'Error inserting Blog Details: ' . $insertResult;
            }
        }

        header('Location: ' .$GLOBALS['site_url'].'career/career_single');
        exit();
    }
}

$details_title = $blog_details_page['blog_details_title'] ?? '';
$section_heading = $blog_details_page['blogs_section_heading'] ?? '';
$section_title = $blog_details_page['blogs_section_title'] ?? '';
$section_description = $blog_details_page['blogs_section_description'] ?? '';
?>
<body>
    <div class="wrapper">
        <!-- Header -->
        <?php include_once '../../navbar.php'; ?>

        <!-- Sidebar -->
        <?php include '../../sidebar.php'; ?>

        <div class="content-wrapper" style="min-height: 107px;">
            <div class="card-primary card-body">
                <!-- PHP logic for handling form submissions and displaying status messages -->
                <?php if (isset($_SESSION['status'])) { ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['status']; ?>
                    </div>
                <?php unset($_SESSION['status']);
                } ?>

                <!-- Form for editing blog section -->
                <h3>Blog Details Page </h3>
                <hr>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>career/career_single" method="post">
                    <div class="form-group">
                        <label for="blog_details_title">Blog Details Title :</label>
                        <input type="text" class="form-control" id="blog_details_title" name="blog_details_title" value="<?php echo htmlspecialchars($details_title); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="blogs_section_title">Blog Slider Title:</label>
                        <input type="text" class="form-control" id="blogs_section_title" name="blogs_section_title" value="<?php echo htmlspecialchars($section_title); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="blogs_section_heading">Blog Slider Heading:</label>
                        <input type="text" class="form-control" id="blogs_section_heading" name="blogs_section_heading" value="<?php echo htmlspecialchars($section_heading); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="blogs_section_description">Blog Slider Description:</label>
                        <textarea class="form-control" id="blogs_section_description" name="blogs_section_description" rows="3" required><?php echo htmlspecialchars($section_description); ?></textarea>
                    </div>
                    <button type="submit" name="save_blog_section" class="btn btn-primary">Save Changes</button>
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
