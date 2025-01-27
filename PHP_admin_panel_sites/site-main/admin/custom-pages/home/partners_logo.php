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
$home_json_data = json_decode($home_data['partners_logo'], true) ?? [];

// Function to handle file uploads
function uploadFiles($files, $uploadDir)
{
    $filePaths = [];
    foreach ($files['name'] as $key => $name) {
        $tmp_name = $files['tmp_name'][$key];
        $path = $uploadDir . basename($name);
        if (move_uploaded_file($tmp_name, $path)) {
            $filePaths[] = $path; // Store the relative path
        } else {
            return ['status' => false, 'error' => 'Failed to upload file ' . $name];
        }
    }
    return ['status' => true, 'paths' => $filePaths];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_data'])) {
        $partner_images = $_FILES['images'] ?? null;

        // Upload directory
        $uploadsDir = 'uploads/home/';
        $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;
        if (!file_exists($absoluteUploadsDir)) {
            mkdir($absoluteUploadsDir, 0755, true);
        }

        // Upload images
        $filePaths = [];
        if ($partner_images && $partner_images['error'][0] == 0) {
            $uploadResult = uploadFiles($partner_images, $absoluteUploadsDir);
            if ($uploadResult['status']) {
                // Store relative paths
                foreach ($uploadResult['paths'] as $path) {
                    $filePaths[] = $uploadsDir . basename($path);
                }
            } else {
                $_SESSION['status_error'] = "Error uploading images: " . $uploadResult['error'];
                header('Location: ' . $GLOBALS['site_url'] . 'home/partners_logo');
                exit();
            }
        }

        // Add new partner images
        foreach ($filePaths as $filePath) {
            $home_json_data[] = $filePath;
        }

        // Encode JSON data and update database
        $json_encoded_data = json_encode($home_json_data);
        $data = ['partners_logo' => $json_encoded_data];
        $result = $crud->update('home', $data, ['id' => 1]);

        if ($result === true) {
            $_SESSION['status'] = "Partner logos saved successfully";
        } else {
            $_SESSION['status_error'] = "Partner logos save failed";
        }
        header('Location: ' . $GLOBALS['site_url'] . 'home/partners_logo');
        exit();
    }

    if (isset($_POST['delete_partner'])) {
        // Delete partner
        $delete_index = intval($_POST['delete_index']);

        if (isset($home_json_data[$delete_index])) {
            // Remove partner
            array_splice($home_json_data, $delete_index, 1);

            // Encode JSON data
            $json_encoded_data = json_encode($home_json_data);

            // Update database
            $data = ['partners_logo' => $json_encoded_data];
            $result = $crud->update('home', $data, ['id' => 1]);

            if ($result === true) {
                $_SESSION['status'] = "Partner deleted successfully";
            } else {
                $_SESSION['status_error'] = "Partner deletion failed";
            }

            header('Location: ' . $GLOBALS['site_url'] . 'home/partners_logo');
            exit();
        }
    }
}

// Fetch partners
$partners = $home_json_data;

?>

<body>
    <div class="wrapper">
        <!-- Header -->
        <?php include_once '../../navbar.php'; ?>

        <!-- Sidebar -->
        <?php include '../../sidebar.php'; ?>

        <div class="content-wrapper" style="min-height: 107px;">
            <div class="card card-primary card-body">
                <?php echo  alert_message(); ?>
                <div class="card-header">Add New Partner Logos</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>home/partner_logo" method="post" enctype="multipart/form-data">

                    <div class="card card-primary card-body">
                        <div class="col-md-6 mb-3">
                            <label for="images">Partner Images:</label>
                            <input type="file" name="images[]" id="images" multiple required>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_data" class="btn btn-primary">Save Partner Logos</button>
                        </div>
                    </div>
                </form>


                <hr>
                <h3>Existing Partners</h3>
                <?php foreach ($partners as $index => $partner_image) { ?>
                    <div class="partner-item">
                        <img src="<?= $GLOBALS['site_url'] . $partner_image ?>" alt="Image" style="max-width: 100px; max-height: 100px;">
                        <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>home/partners_logo" method="post" style="display:inline;">
                            <input type="hidden" name="delete_index" value="<?= $index ?>">
                            <button type="submit" name="delete_partner" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                    <hr>
                <?php } ?>
            </div>
        </div>

        <!-- Footer -->
        <?php include '../../footer.php'; ?>
    </div>
</body>

</html>