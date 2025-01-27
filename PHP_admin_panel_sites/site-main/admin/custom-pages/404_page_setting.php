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
$page_data = $crud->getData('404_page', "id=1", '', '');
$page_data = $page_data ? $page_data[0] : [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_page_data'])) {
        // Retrieve form data
        $page_title = $_POST['page_title'] ?? '';
        $page_heading = $_POST['page_heading'] ?? '';
        $page_description = $_POST['page_description'] ?? '';
        $button_text = $_POST['button_text'] ?? '';

        // Construct the data to be updated
        $page_data = [
            '404_title' => $page_title,
            'heading' => $page_heading,
            'description' => $page_description,
            'button_text' => $button_text
        ];

        // Update or insert data into the database
        if (!empty($page_data['404_title'])) { // Assuming title is never empty for an existing record
            $updateResult = $crud->update('404_page', $page_data, ['id' => 1]);
            $_SESSION['status'] = $updateResult === true ? 'Page details updated successfully' : 'Error updating page details: ' . $updateResult;
        } else {
            $insertResult = $crud->insert('404_page', $page_data);
            $_SESSION['status'] = $insertResult === true ? 'Page details inserted successfully' : 'Error inserting page details: ' . $insertResult;
        }

        header('Location: ' . $GLOBALS['site_url'] . '404_page_setting');
        exit();
    }
}

// Fetch existing data from the database
$page_title = $page_data['404_title'] ?? '';
$page_heading = $page_data['heading'] ?? '';
$page_description = $page_data['description'] ?? '';
$button_text = $page_data['button_text'] ?? '';
?>

<body>
    <div class="wrapper">
        <!-- Header -->
        <?php include_once '../navbar.php'; ?>

        <!-- Sidebar -->
        <?php include '../sidebar.php'; ?>

        <div class="content-wrapper" style="min-height: 107px;">
            <div class="card card-primary card-body">
                <?php echo alert_message(); ?>

                <div class="card-header">404 Page Details</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>404_page_setting" method="post">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="page_title">404 Page Title:</label>
                            <input type="text" class="form-control" id="page_title" name="page_title" value="<?php echo htmlspecialchars($page_title); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="page_heading">404 Page Heading:</label>
                            <input type="text" class="form-control" id="page_heading" name="page_heading" value="<?php echo htmlspecialchars($page_heading); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="page_description">404 Page Description:</label>
                            <textarea class="form-control" id="page_description" name="page_description" rows="3" required><?php echo htmlspecialchars($page_description); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="button_text">Button Text:</label>
                            <input type="text" class="form-control" id="button_text" name="button_text" value="<?php echo htmlspecialchars($button_text); ?>" required>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_page_data" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <?php include '../footer.php'; ?>
    </div>
</body>

</html>