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
$result = $crud->getData('project_page', "id=1", '', '');

$project_page = [];
if (!empty($result)) {
    $project_page = $result[0];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_project_page'])) {
        // Update project section data
        $project_page_title = $_POST['project_page_title'] ?? '';
        $project_page_heading = $_POST['project_page_heading'] ?? '';
        $project_page_description = $_POST['project_page_description'] ?? '';
        $button_text = $_POST['button_text'] ?? '';

        // Construct the section data
        $project_page_data = [
            'title' => $project_page_title,
            'heading' => $project_page_heading,
            'description' => $project_page_description,
            'button_text' => $button_text
        ];

        // Determine whether to insert or update
        if (!empty($result)) {
            // Update the existing row
            $updateResult = $crud->update('project_page', $project_page_data, ['id' => 1]);
            if ($updateResult === true) {
                $_SESSION['status'] = 'Project Details Page Updated successfully';
            } else {
                $_SESSION['status_error'] = 'Error updating Project Page Details: ' . $updateResult;
            }
        } else {
            // Insert a new row (let the auto-increment handle the id)
            $insertResult = $crud->insert('project_page', $project_page_data);
            if ($insertResult === true) {
                $_SESSION['status'] = 'Project Page Details inserted successfully';
            } else {
                $_SESSION['status_error'] = 'Error inserting Project Page Details: ' . $insertResult;
            }
        }

        header('Location: ' . $GLOBALS['site_url'] . 'projects/project_page');
        exit();
    }
}

$project_page_title_text = isset($project_page['title']) ? $project_page['title'] : '';
$project_page_heading_text = isset($project_page['heading']) ? $project_page['heading'] : '';
$project_page_description_text = isset($project_page['description']) ? $project_page['description'] : '';
$button_text = isset($project_page['button_text']) ? $project_page['button_text'] : '';
?>

<body>
    <div class="wrapper">
        <!-- Header -->
        <?php include_once '../../navbar.php'; ?>

        <!-- Sidebar -->
        <?php include '../../sidebar.php'; ?>

        <div class="content-wrapper" style="min-height: 107px;">
            <div class="card card-primary card-body">
                <!-- PHP logic for handling form submissions and displaying status messages -->
                <?php echo alert_message(); ?>

                <!-- Form for editing project section -->

                <div class="card-header">Project Page</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>projects/project_page" method="post">

                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="project_page_title">Project Page Title:</label>
                            <input type="text" class="form-control" id="project_page_title" name="project_page_title" value="<?php echo htmlspecialchars($project_page_title_text); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="project_page_heading">Project Page Heading:</label>
                            <input type="text" class="form-control" id="project_page_heading" name="project_page_heading" value="<?php echo htmlspecialchars($project_page_heading_text); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="project_page_description">Project Page Description:</label>
                            <textarea class="form-control" id="project_page_description" name="project_page_description" rows="3" required><?php echo htmlspecialchars($project_page_description_text); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="button_text">Project Button Text:</label>
                            <input type="text" class="form-control" id="button_text" name="button_text" value="<?php echo htmlspecialchars($button_text); ?>" required>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_project_page" class="btn btn-primary">Save Details</button>
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