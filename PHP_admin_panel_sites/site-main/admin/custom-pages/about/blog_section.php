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
$about_data = $crud->getData('about_us', "id=1", '', '')[0];
$blog_section_data = json_decode($about_data['blog_section'], true) ?? [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_details'])) {
        // Update blog_section section data
        $title = $_POST['title'] ?? '';
        $heading = $_POST['heading'] ?? '';
        $description = $_POST['description'] ?? '';

        // Construct the section data
        $section_data = [
            'title' => $title,
            'heading' => $heading,
            'description' => $description
        ];

        // Encode JSON data
        $json_encoded_data = json_encode($section_data);

        // Update database
        $data = ['blog_section' => $json_encoded_data];
        $result = $crud->update('about_us', $data, ['id' => 1]);

        if ($result === true) {
            $_SESSION['status'] = "Blog section updated successfully";
        } else {
            $_SESSION['status_error'] = "Blog section update failed";
        }

        header('Location: ' . $GLOBALS['site_url'] . 'about/blog_section');
        exit();
    }
}

// Fetch blog_section section data
$section_title = $blog_section_data['title'] ?? '';
$section_heading = $blog_section_data['heading'] ?? '';
$section_description = $blog_section_data['description'] ?? '';
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

                <div class="card-header">Edit Blog Section</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>about/blog_section" method="post">
                    <div class="card card-primary card-body">

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($section_title); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="heading">Main Heading</label>
                            <input type="text" class="form-control" id="heading" name="heading" value="<?php echo htmlspecialchars($section_heading); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($section_description); ?></textarea>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_details" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Footer -->
        <?php include '../../footer.php'; ?>
    </div>
</body>

</html>