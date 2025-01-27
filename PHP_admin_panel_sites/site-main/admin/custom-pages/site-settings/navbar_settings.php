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
$home_json_data = json_decode($home_data['header_section'], true) ?? [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_data'])) {
        $partner_logo = $_FILES['logo'] ?? null;
        $sign_button = $_POST['sign_button'] ?? '';
        $sign_button_url = $_POST['sign_button_url'] ?? '';

        // Upload directory
        $uploadsDir = 'uploads/home/';
        $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;
        if (!file_exists($absoluteUploadsDir)) {
            mkdir($absoluteUploadsDir, 0755, true);
        }

        // Upload logo
        $logoPath = $home_json_data['logo'] ?? '';
        if ($partner_logo && $partner_logo['error'] == 0) {
            $uploadResult = uploadFile($partner_logo, $absoluteUploadsDir);
            if ($uploadResult['status']) {
                $logoPath = $uploadsDir . basename($uploadResult['file_path']);
            } else {
                $_SESSION['status_error'] = "Error uploading logo: " . $uploadResult['message'];
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }
        }

        // Update JSON data
        $home_json_data['logo'] = $logoPath;
        $home_json_data['sign_button'] = $sign_button;
        $home_json_data['sign_button_url'] = $sign_button_url;

        // Encode JSON data and update database
        $json_encoded_data = json_encode($home_json_data);
        $data = ['header_section' => $json_encoded_data];
        $result = $crud->update('home', $data, ['id' => 1]);

        if ($result === true) {
            $_SESSION['status'] = "Header settings saved successfully";
        } else {
            $_SESSION['status_error'] = "Header settings save failed";
        }
        header('Location: ' . $GLOBALS['site_url'] . 'site-settings/navbar_settings');
        exit();
    }
}

// Fetch header
$header = $home_json_data;

?>

<body>
    <div class="wrapper">
        <!-- Header -->
        <?php include_once '../../navbar.php'; ?>

        <!-- Sidebar -->
        <?php include '../../sidebar.php'; ?>
        <?php if (isset($_SESSION['status_authorized'])) { ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['status_authorized']; ?>
            </div>
        <?php unset($_SESSION['status_authorized']);
        } ?>

        <div class="content-wrapper" style="min-height: 107px;">
            <div class="card card-primary card-body">
                <!-- PHP logic for handling form submissions and displaying status messages -->
                <?php echo alert_message(); ?>

                <!-- Form for adding partner logos -->
                <div class="card-header">Header Settings</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>site-settings/navbar_settings" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                        <div class="form-group col-md-6 mb-3">
                            <label for="logo">Brand Logo:</label>
                            <?php if (!empty($header['logo'])) : ?>
                                <div>
                                    <img src="<?php echo $GLOBALS['site_url'] . $header['logo']; ?>" alt="Current Logo" width="100">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="logo" id="logo">
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label for="sign_button">Sign in Button:</label>
                            <input type="text" class="form-control" name="sign_button" id="sign_button" value="<?php echo $header['sign_button'] ?? ''; ?>" required>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label for="sign_button_url">Sign in Button URL:</label>
                            <input type="text" class="form-control" name="sign_button_url" id="sign_button_url" value="<?php echo $header['sign_button_url'] ?? ''; ?>" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" name="save_data" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
            <!-- Display status messages -->
            <?php echo alert_message(); ?>
        </div>

        <!-- Footer -->
        <?php include '../../footer.php'; ?>
    </div>

    <script>
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const imgPreview = document.createElement('img');
                    imgPreview.src = event.target.result;
                    imgPreview.width = 100;
                    const previewContainer = document.querySelector('.col-md-6.mb-3 > div');
                    if (previewContainer) {
                        previewContainer.innerHTML = '';
                        previewContainer.appendChild(imgPreview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
<html>