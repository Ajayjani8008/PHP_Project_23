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
$career_page = $crud->getData('career_page', "id=1", '', '')[0];

// echo '<pre>';
// var_dump($career_page);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_career_page'])) {
        // Retrieve form data
        $page_title = $_POST['page_title'] ?? '';
        $page_heading = $_POST['page_heading'] ?? '';
        $page_description = $_POST['page_description'] ?? '';
        $button_text = $_POST['button_text'] ?? '';

        // Fetch existing images if available
        $career_page_images = isset($career_page['images']) ? json_decode($career_page['images'], true) : [];

        // Handle image uploads
        $uploadsDir = 'uploads/career/';
        $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;

        // Process each image
        for ($i = 1; $i <= 4; $i++) {
            $fileKey = "career_page_img{$i}";
            if ($_FILES[$fileKey]['error'] == 0) {
                $uploadedFile = uploadFile($_FILES[$fileKey], $absoluteUploadsDir);
                if ($uploadedFile && $uploadedFile['status']) {
                    $career_page_images[$fileKey] = $uploadsDir . $uploadedFile['file_name'];
                } else {
                    $_SESSION['status_error'] = "Error uploading $fileKey image: " . $uploadedFile['message'];
                }
            }
        }

        // Construct the data to be updated
        $career_page_data = [
            'page_title' => $page_title,
            'page_heading' => $page_heading,
            'page_description' => $page_description,
            'button_text' => $button_text
        ];

        // Merge image paths if they exist
        if (!empty($career_page_images)) {
            $career_page_data['images'] = json_encode($career_page_images);
        }

        // Update or insert data into the database
        if (!empty($career_page)) {
            $updateResult = $crud->update('career_page', $career_page_data, ['id' => 1]);
            $_SESSION['status'] = $updateResult === true ? 'Career details updated successfully' : 'Error updating career details: ' . $updateResult;
        } else {
            $insertResult = $crud->insert('career_page', $career_page_data);
            $_SESSION['status_error'] = $insertResult === true ? 'Career details inserted successfully' : 'Error inserting career details: ' . $insertResult;
        }

        header('Location: ' . $GLOBALS['site_url'] . 'career/career_page');
        exit();
    }
}

// Fetch existing data from the database
$page_title = isset($career_page['page_title']) ? $career_page['page_title'] : '';
$page_heading = isset($career_page['page_heading']) ? $career_page['page_heading'] : '';
$page_description = isset($career_page['page_description']) ? $career_page['page_description'] : '';
$button_text = isset($career_page['button_text']) ? $career_page['button_text'] : '';
$career_page_images = isset($career_page['images']) ? json_decode($career_page['images'], true) : [];

?>

<body>
    <div class="wrapper">
        <!-- Header -->
        <?php include_once '../../navbar.php'; ?>

        <!-- Sidebar -->
        <?php include '../../sidebar.php'; ?>

        <div class="content-wrapper" style="min-height: 107px;">
            <div class="card card-primary card-body">
                <!-- Display status messages -->
                <?php echo alert_message(); ?>

                <div class="card-header">Career Details Page</div>

                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>career/career_page" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="page_title">Career Page Title:</label>
                            <input type="text" class="form-control" id="page_title" name="page_title" value="<?php echo htmlspecialchars($page_title); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="page_heading">Career Page Heading:</label>
                            <input type="text" class="form-control" id="page_heading" name="page_heading" value="<?php echo htmlspecialchars($page_heading); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="page_description">Career Page Description:</label>
                            <textarea class="form-control" id="page_description" name="page_description" rows="3" required><?php echo htmlspecialchars($page_description); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="button_text">Button Text:</label>
                            <input type="text" class="form-control" id="button_text" name="button_text" value="<?php echo htmlspecialchars($button_text); ?>" required>
                        </div>
                        <div class="row">
                            <?php for ($i = 1; $i <= 4; $i++) { ?>
                                <div class="form-group">
                                    <label for="career_page_img<?php echo $i; ?>">Career Page Featured Image <?php echo $i; ?>:</label>
                                    <input type="file" class="form-control-file" id="career_page_img<?php echo $i; ?>" name="career_page_img<?php echo $i; ?>">
                                    <?php if (!empty($career_page_images["career_page_img$i"])) { ?>
                                        <input type="hidden" name="existing_career_page_img<?php echo $i; ?>" value="<?php echo $career_page_images["career_page_img$i"]; ?>">
                                        <img src="<?php echo $GLOBALS['site_url'] . $career_page_images["career_page_img$i"]; ?>" alt="Career Image <?php echo $i; ?>" style="max-width: 200px; margin-top: 10px;">
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="card-footer">
                            <button type="submit" name="save_career_page" class="btn btn-primary">Save Changes</button>
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