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
$edit = $crud->getbyid('home', 1);
$home_data = $crud->getData('home', "id=1", '', '')[0];

// Decode JSON data
$home_json_data = json_decode($home_data['testimonial'], true) ?? [];

// Handle form submission for testimonial section
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_testimony_section'])) {
        // Update testimonial section data
        $section_heading = $_POST['testimonial_title'] ?? '';
        $section_sub_heading = $_POST['testimonial_sub_title'] ?? '';
        $section_description = $_POST['testimonial_description'] ?? '';

        // Construct the section data
        $section_data = [
            'heading' => $section_heading,
            'sub_title' => $section_sub_heading,
            'description' => $section_description
        ];

        // Merge section data with existing testimonial data
        $home_json_data['section'] = $section_data;

        // Encode JSON data
        $json_encoded_data = json_encode($home_json_data);

        // Update database
        $data = ['testimonial' => $json_encoded_data];
        $result = $crud->update_testimony('home', $data, ['id' => 1]);

        if ($result === true) {
            $_SESSION['status'] = "Testimonial section updated successfully";
        } else {
            $_SESSION['status_error'] = "Testimonial section update failed";
        }

        header('Location: ' . $GLOBALS['site_url'] . 'home/testimonial_section');
        exit();
    }
}

// Fetch testimonial section data
$testimonial_section_heading = $home_json_data['section']['heading'] ?? '';
$testimonial_section_sub_heading = $home_json_data['section']['sub_title'] ?? '';
$testimonial_section_description = $home_json_data['section']['description'] ?? '';

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

                <div class="card-header">
                    Edit Testimony Section
                </div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>home/testimonial_section" method="post">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="testimonial_title">Main Heading</label>
                            <input type="text" class="form-control" id="testimonial_title" name="testimonial_title" value="<?php echo htmlspecialchars($testimonial_section_heading); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="testimonial_sub_title">Title</label>
                            <input type="text" class="form-control" id="testimonial_sub_title" name="testimonial_sub_title" value="<?php echo htmlspecialchars($testimonial_section_sub_heading); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="testimonial_description">Description</label>
                            <textarea class="form-control" id="testimonial_description" name="testimonial_description" rows="3" required><?php echo htmlspecialchars($testimonial_section_description); ?></textarea>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_testimony_section" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- footer start -->
        <?php
        include '../../footer.php';
        ?>
    </div>
</body>

</html>