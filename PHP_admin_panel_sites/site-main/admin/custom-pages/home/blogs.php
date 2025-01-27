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
$home_data = $crud->getData('home', "id=1", '', '')[0];
$home_json_data = json_decode($home_data['blog'], true) ?? [];

// print_r($home_json_data);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_blog_section'])) {
        // Update blog section data
        $section_heading = $_POST['blog_section_heading'] ?? '';
        $section_sub_heading = $_POST['blog_section_title'] ?? '';
        $section_description = $_POST['blog_section_description'] ?? '';

        // Construct the section data
        $section_data = [
            'blog_section_heading' => $section_heading,
            'blog_section_title' => $section_sub_heading,
            'blog_section_description' => $section_description
        ];

        // Merge section data with existing blog data
        $home_json_data['section'] = $section_data;

        // Encode JSON data
        $json_encoded_data = json_encode($home_json_data);

        // Update database
        $data = ['blog' => $json_encoded_data];
        $result = $crud->update('home', $data, ['id' => 1]); // Corrected function name

        if ($result === true) {
            $_SESSION['status'] = "Blog section updated successfully";
        } else {
            $_SESSION['status_error'] = "Blog section update failed";
        }

        header('Location: ' . $GLOBALS['site_url'] . 'home/blogs');
        exit();
    }

}
// Fetch blog section data
$blog_section_heading = $home_json_data['section']['blog_section_heading'] ?? '';
$blog_section_title = $home_json_data['section']['blog_section_title'] ?? '';
$blog_section_description = $home_json_data['section']['blog_section_description'] ?? '';


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
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>home/blogs" method="post">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="blog_section_heading">Main Heading</label>
                            <input type="text" class="form-control" id="blog_section_heading" name="blog_section_heading" value="<?php echo htmlspecialchars($blog_section_heading); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="blog_section_title">Title</label>
                            <input type="text" class="form-control" id="blog_section_title" name="blog_section_title" value="<?php echo htmlspecialchars($blog_section_title); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="blog_section_description">Description</label>
                            <textarea class="form-control" id="blog_section_description" name="blog_section_description" rows="3" required><?php echo htmlspecialchars($blog_section_description); ?></textarea>
                        </div>
                        <div class="card-footer">

                            <button type="submit" name="save_blog_section" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
               
            </div>
        </div>

        <!-- Footer -->
        <?php include '../../footer.php'; ?>
    </div>
</body>
<html>
