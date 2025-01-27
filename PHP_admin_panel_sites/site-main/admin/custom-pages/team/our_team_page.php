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
$our_team_page = $crud->getData('our_team_page', "id=1", '', '');
$existing_data = $our_team_page ? $our_team_page[0] : null;

// Initialize variables
$team_page_title = '';
$team_page_heading = '';
$team_page_description = '';
$team_page_button_text = '';
$testimonial_title = '';
$testimonial_heading = '';
$testimonial_description = '';

if ($existing_data) {
    // If data exists, decode JSON
    $existing_data['our_team_heading_section'] = json_decode($existing_data['our_team_heading_section'], true);
    $existing_data['testimonial_section'] = json_decode($existing_data['testimonial_section'], true);

    // Assign values
    $team_page_title = $existing_data['our_team_heading_section']['title'] ?? '';
    $team_page_heading = $existing_data['our_team_heading_section']['heading'] ?? '';
    $team_page_description = $existing_data['our_team_heading_section']['description'] ?? '';
    $team_page_button_text = $existing_data['our_team_heading_section']['button_text'] ?? '';
    $testimonial_title = $existing_data['testimonial_section']['title'] ?? '';
    $testimonial_heading = $existing_data['testimonial_section']['heading'] ?? '';
    $testimonial_description = $existing_data['testimonial_section']['description'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_team_section'])) {
        // Update team section data
        $team_page_title = $_POST['team_page_title'] ?? '';
        $team_page_heading = $_POST['team_page_heading'] ?? '';
        $team_page_description = $_POST['team_page_description'] ?? '';
        $team_page_button_text = $_POST['team_page_button_text'] ?? '';
        $testimonial_title = $_POST['testimonial_title'] ?? '';
        $testimonial_heading = $_POST['testimonial_heading'] ?? '';
        $testimonial_description = $_POST['testimonial_description'] ?? '';

        // Construct the section data
        $our_team_heading_section = json_encode([
            'title' => $team_page_title,
            'heading' => $team_page_heading,
            'description' => $team_page_description,
            'button_text' => $team_page_button_text
        ]);

        $testimonial_section = json_encode([
            'title' => $testimonial_title,
            'heading' => $testimonial_heading,
            'description' => $testimonial_description
        ]);

        $section_data = [
            'our_team_heading_section' => $our_team_heading_section,
            'testimonial_section' => $testimonial_section
        ];

        if ($existing_data) {
            // Update the existing row
            $updateResult = $crud->update('our_team_page', $section_data, ['id' => 1]);
            if ($updateResult === true) {
                $_SESSION['status'] = 'Team Details updated successfully';
                // Redirect to prevent resubmission
                header('Location: ' . $GLOBALS['site_url'] . 'team/our_team_page');
                exit();
            } else {
                $_SESSION['status_error'] = 'Error updating Team Details: ' . $updateResult;
            }
        } else {
            // Insert a new row (let the auto-increment handle the id)
            $insertResult = $crud->insert('our_team_page', $section_data);
            if ($insertResult === true) {
                $_SESSION['status'] = 'Team Details inserted successfully';
                // Redirect to prevent resubmission
                header('Location: ' . $GLOBALS['site_url'] . 'team/our_team_page');
                exit();
            } else {
                $_SESSION['status_error'] = 'Error inserting Team Details: ' . $insertResult;
            }
        }
    }
}

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

                <div class="card-header">Our Team Page </div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>team/our_team_page" method="post">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="team_page_title">Team Page Title :</label>
                            <input type="text" class="form-control" id="team_page_title" name="team_page_title" value="<?php echo htmlspecialchars($team_page_title); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="team_page_heading">Team Page Heading:</label>
                            <input type="text" class="form-control" id="team_page_heading" name="team_page_heading" value="<?php echo htmlspecialchars($team_page_heading); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="team_page_button_text">Team Member view Button Text:</label>
                            <input type="text" class="form-control" id="team_page_button_text" name="team_page_button_text" value="<?php echo htmlspecialchars($team_page_button_text); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="team_page_description">Team Page Description:</label>
                            <textarea class="form-control" id="team_page_description" name="team_page_description" rows="3" required><?php echo htmlspecialchars($team_page_description); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="testimonial_title">Testimonial Title:</label>
                            <input type="text" class="form-control" id="testimonial_title" name="testimonial_title" value="<?php echo htmlspecialchars($testimonial_title); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="testimonial_heading">Testimonial Heading:</label>
                            <input type="text" class="form-control" id="testimonial_heading" name="testimonial_heading" value="<?php echo htmlspecialchars($testimonial_heading); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="testimonial_description">Testimonial Description:</label>
                            <textarea class="form-control" id="testimonial_description" name="testimonial_description" rows="3" required><?php echo htmlspecialchars($testimonial_description); ?></textarea>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_team_section" class="btn btn-primary">Save Changes</button>
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