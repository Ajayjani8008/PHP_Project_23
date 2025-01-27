<?php
// Start session
session_start();

// Include necessary files
include_once '../../config/Dbconfig.php';
include_once '../../config/Crud.php';
include_once '../../config/functions.php';
include_once '../../auth/authentication.php';
include_once '../../header.php';
// $main_site_url = getMainSiteUrl();

// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create instance of Crud class
$crud = new Crud();

// Get footer data
$footer_data = $crud->getData('home', "id=1", '', '')[0];
$footer_json = $footer_data['footer_section'] ?? '{}';
$footer_json_data = json_decode($footer_json, true) ?? [];




// Update footer section if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_footer_section'])) {
        // Update footer section data from form inputs
        $footer_json_data['follow_us_title'] = $_POST['follow_us_title'] ?? '';
        $footer_json_data['contact_us_title'] = $_POST['contact_us_title'] ?? '';
        $footer_json_data['useful_links_title'] = $_POST['useful_links_title'] ?? '';
        $footer_json_data['copyright_line'] = $_POST['copyright_line'] ?? '';

        // Encode data to JSON
        $json_encoded_data = json_encode($footer_json_data);
        $data = ['footer_section' => $json_encoded_data];
        // Update database
        $result = $crud->update('home', $data, ['id' => 1]);

        // Set session status message
        // $_SESSION['status'] = ($result === true) ? "Footer updated successfully" : "Footer update failed";
        if ($result === true) {
            $_SESSION['status'] = "Footer updated successfully";
        } else {
            $_SESSION['status_error'] = "Footer update failed";
        }

        // Redirect to current page
        header('Location: ' . $GLOBALS['site_url'] . 'site-settings/footer_settings');
        exit();
    } elseif (isset($_POST['save_follow_us'])) {
        // Handle adding new social media link
        // Directory for uploading files
        $uploadDir = 'uploads/home/';
        $absoluteUploadsDir =  $GLOBALS['uploads_dir_root'] . $uploadDir;
        // Create directory if it doesn't exist
        if (!file_exists($absoluteUploadsDir)) {
            mkdir($absoluteUploadsDir, 0755, true);
        }

        // Upload file
        $fileUploadResponse = uploadFile($_FILES['social_icon'], $absoluteUploadsDir);
        if ($fileUploadResponse['status']) {
            // Get uploaded file path
            $iconImagePath = '/' . $uploadDir . basename($_FILES['social_icon']["name"]);
            // Get platform and URL from form
            $platform = $_POST['platform'] ?? '';
            $url = $_POST['url'] ?? '';
            if ($platform && $url) {
                // Create social media link array
                $social_media_link = [
                    'platform' => $platform,
                    'icon' => $iconImagePath,
                    'url' => $url
                ];
                // Add social media link to existing data
                $footer_json_data['follow_us']['social_media'][] = $social_media_link;

                // Update footer section data
                $json_encoded_data = json_encode($footer_json_data);
                $data = ['footer_section' => $json_encoded_data];
                $result = $crud->update('home', $data, ['id' => 1]);

                // Set session status message
                // $_SESSION['status'] = ($result === true) ? "Social media link added successfully" : "Failed to add social media link";
                if ($result === true) {
                    $_SESSION['status'] = "Social media link added successfully";
                } else {
                    $_SESSION['status_error'] = "Failed to add social media link";
                }
            }
        } else {
            // Set error message
            $_SESSION['status_error'] = "Error: " . $fileUploadResponse['message'];
        }
    } elseif (isset($_POST['save_contact_us'])) {
        // Handle updating contact us section
        // Update contact us data from form inputs
        $footer_json_data['footer_contact_us'] = [
            'address' => $_POST['address'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? ''
        ];

        // Encode data to JSON
        $json_encoded_data = json_encode($footer_json_data);
        $data = ['footer_section' => $json_encoded_data];
        // Update database
        $result = $crud->update('home', $data, ['id' => 1]);

        // Set session status message
        $_SESSION['status'] = ($result === true) ? "Contact us section updated successfully" : "Failed to update contact us section";
    } elseif (isset($_POST['save_links'])) {
        // Handle adding useful link
        // Get link title and URL from form
        $link_title = $_POST['link_title'] ?? '';
        $link_url = $_POST['link_url'] ?? '';
        if ($link_title && $link_url) {
            // Create useful link array
            $useful_link = [
                'title' => $link_title,
                'url' => $link_url
            ];
            // Add useful link to existing data
            $footer_json_data['footer_useful_links']['links'][] = $useful_link;

            // Update footer section data
            $json_encoded_data = json_encode($footer_json_data);
            $data = ['footer_section' => $json_encoded_data];
            $result = $crud->update('home', $data, ['id' => 1]);

            // Set session status message
            // $_SESSION['status'] = ($result === true) ? "Useful link added successfully" : "Failed to add useful link";
            if ($result === true) {
                $_SESSION['status'] = "Useful link added successfully";
            } else {
                $_SESSION['status_error'] = "Failed to add useful link";
            }
        }
    } elseif (isset($_POST['save_logo'])) {
        // Handle adding new social media link
        // Directory for uploading files
        $uploadDir = 'uploads/home/';
        $absoluteUploadsDir = $_SERVER['DOCUMENT_ROOT'] . '/site/' . $uploadDir;
        // Create directory if it doesn't exist
        if (!file_exists($absoluteUploadsDir)) {
            mkdir($absoluteUploadsDir, 0755, true);
        }

        // Upload file
        $fileUploadResponse = uploadFile($_FILES['divice_logo'], $absoluteUploadsDir);
        if ($fileUploadResponse['status']) {
            // Get uploaded file path
            $diviceLogoPath = '/' . $uploadDir . basename($_FILES['divice_logo']["name"]);
            // Get platform and URL from form

            $url = $_POST['url'] ?? '';

            $compatible_divices = [
                'logo' => $diviceLogoPath,
                'url' => $url
            ];
            // Add social media link to existing data
            $footer_json_data['compatible_divices']['divice'][] = $compatible_divices;

            // Update footer section data
            $json_encoded_data = json_encode($footer_json_data);
            $data = ['footer_section' => $json_encoded_data];
            $result = $crud->update('home', $data, ['id' => 1]);

            // Set session status message
            // $_SESSION['status'] = ($result === true) ? " compatible divice   added successfully" : "Failed to add compatible divices";
            if ($result === true) {
                $_SESSION['status'] = "ompatible divice   added successfully";
            } else {
                $_SESSION['status_error'] = "Failed to add compatible divices";
            }
        } else {
            // Set error message
            $_SESSION['status_error'] = "Error: " . $fileUploadResponse['message'];
        }
    } elseif (isset($_POST['delete_follow_us'])) {
        // Handle deleting social media link
        $delete_index = isset($_POST['delete_index']) ? intval($_POST['delete_index']) : -1;
        if ($delete_index >= 0 && isset($footer_json_data['follow_us']['social_media'][$delete_index])) {
            // Remove link from array
            array_splice($footer_json_data['follow_us']['social_media'], $delete_index, 1);

            // Update footer section data
            $json_encoded_data = json_encode($footer_json_data);
            $data = ['footer_section' => $json_encoded_data];
            $result = $crud->update('home', $data, ['id' => 1]);

            // Set session status message
            // $_SESSION['status'] = ($result === true) ? "Social media link deleted successfully" : "Failed to delete social media link";
            if ($result === true) {
                $_SESSION['status'] = "Social media link deleted successfully";
            } else {
                $_SESSION['status_error'] = "Failed to delete social media link";
            }
        }
    } elseif (isset($_POST['delete_link'])) {
        // Handle deleting useful link
        $delete_index = isset($_POST['delete_index']) ? intval($_POST['delete_index']) : -1;
        if ($delete_index >= 0 && isset($footer_json_data['footer_useful_links']['links'][$delete_index])) {
            // Remove link from array
            array_splice($footer_json_data['footer_useful_links']['links'], $delete_index, 1);

            // Update footer section data
            $json_encoded_data = json_encode($footer_json_data);
            $data = ['footer_section' => $json_encoded_data];
            $result = $crud->update('home', $data, ['id' => 1]);

            // Set session status message
            // $_SESSION['status'] = ($result === true) ? "Useful link deleted successfully" : "Failed to delete useful link";
            if ($result === true) {
                $_SESSION['status'] = "Useful link deleted successfully";
            } else {
                $_SESSION['status_error'] = "Failed to delete useful link";
            }
        }
    } elseif (isset($_POST['remove_divice'])) {
        // Handle deleting compatible device
        $delete_index = isset($_POST['delete_index']) ? intval($_POST['delete_index']) : -1;
        if ($delete_index >= 0 && isset($footer_json_data['compatible_divices']['divice'][$delete_index])) {
            // Remove device from array
            array_splice($footer_json_data['compatible_divices']['divice'], $delete_index, 1);

            // Update footer section data
            $json_encoded_data = json_encode($footer_json_data);
            $data = ['footer_section' => $json_encoded_data];
            $result = $crud->update('home', $data, ['id' => 1]);

            // Set session status message
            // $_SESSION['status'] = ($result === true) ? "Device removed successfully" : "Failed to remove device";
            if ($result === true) {
                $_SESSION['status'] = "Device removed successfully";
            } else {
                $_SESSION['status_error'] = "Failed to remove device";
            }
        }
    }

    header('Location: ' . $GLOBALS['site_url'] . 'site-settings/footer_settings');
    exit();
}
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
                <?php echo alert_message(); ?>

                <div class="card-header">Edit Footer Heading Section</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>site-settings/footer_settings" method="post">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="follow_us_title">Follow Us Title</label>
                            <input type="text" class="form-control" required id="follow_us_title" name="follow_us_title" value="<?php echo htmlspecialchars($footer_json_data['follow_us_title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="contact_us_title">Contact Us Title</label>
                            <input type="text" class="form-control" required id="contact_us_title" name="contact_us_title" value="<?php echo htmlspecialchars($footer_json_data['contact_us_title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="useful_links_title">Useful Links Title</label>
                            <input type="text" class="form-control" required id="useful_links_title" name="useful_links_title" value="<?php echo htmlspecialchars($footer_json_data['useful_links_title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="copyright_line">Copyright Line</label>
                            <input type="text" class="form-control" required id="copyright_line" name="copyright_line" value="<?php echo htmlspecialchars($footer_json_data['copyright_line'] ?? ''); ?>">
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_footer_section" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
                <br>

                <div class="card-header">Add New Social Media Link</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>site-settings/footer_settings" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="social_icon">Social Media Icon Image</label>
                            <input type="file" required class="form-control-file" id="social_icon" name="social_icon">
                        </div>
                        <div class="form-group">
                            <label for="platform">Platform</label>
                            <input type="text" class="form-control" id="platform" name="platform">
                        </div>
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="url" class="form-control" id="url" name="url">
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_follow_us" class="btn btn-primary">Save Social Media Link</button>
                        </div>
                    </div>
                </form>
                <br>
                <div class="card-header">Edit Contact Us Section</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>site-settings/footer_settings" method="post">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="contact_address">Address : </label>
                            <input type="text" class="form-control" id="contact_address" name="address" value="<?php echo htmlspecialchars($footer_json_data['footer_contact_us']['address'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_email">Email</label>
                            <input type="email" class="form-control" id="contact_email" name="email" value="<?php echo htmlspecialchars($footer_json_data['footer_contact_us']['email'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_phone">Phone</label>
                            <input type="text" class="form-control" id="contact_phone" name="phone" value="<?php echo htmlspecialchars($footer_json_data['footer_contact_us']['phone'] ?? ''); ?>" required>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_contact_us" class="btn btn-primary">Save Contact Info</button>
                        </div>
                    </div>
                </form>
                <br>

                <div class="card-header">Add Useful Link</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>site-settings/footer_settings" method="post">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="link_title">Link Title</label>
                            <input type="text" class="form-control" id="link_title" name="link_title" required>
                        </div>
                        <div class="form-group">
                            <label for="link_url">Link URL</label>
                            <input type="url" class="form-control" id="link_url" name="link_url" required>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_links" class="btn btn-primary">Save Link</button>
                        </div>
                    </div>
                </form>
                <br>

                <div class="card-header">Compatible With:</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>site-settings/footer_settings" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="divice_logo">Device Logo</label>
                            <input type="file" id="divice_logo" name="divice_logo" required>
                        </div>
                        <div class="form-group">
                            <label for="logo_url">Logo URL</label>
                            <input type="url" class="form-control" id="logo_url" name="logo_url" required>
                        </div>

                        <div class="card-footer">
                            <button type="submit" name="save_logo" class="btn btn-primary">Add Compatible</button>
                        </div>
                    </div>
                </form>
                <br>
                <hr>
                <!-- List existing social media links -->
                <h3>Existing Social Media Links</h3>
                <hr>
                <ul>
                    <?php if (isset($footer_json_data['follow_us']['social_media']) && is_array($footer_json_data['follow_us']['social_media'])) { ?>
                        <?php foreach ($footer_json_data['follow_us']['social_media'] as $index => $social_media) { ?>
                            <li>
                                <?php echo htmlspecialchars($social_media['platform']); ?> - <a href="<?php echo htmlspecialchars($social_media['url']); ?>" target="_blank"><?php echo htmlspecialchars($social_media['url']); ?></a>
                                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>site-settings/footer_settings" method="post" style="display:inline;">
                                    <input type="hidden" name="delete_index" value="<?php echo $index; ?>">
                                    <button type="submit" name="delete_follow_us" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </li>
                            <br>
                        <?php } ?>
                    <?php } ?>
                </ul>
                <br>
                <hr>
                <!-- List existing useful links -->
                <h3>Existing Useful Links</h3>
                <hr>
                <ul>
                    <?php if (isset($footer_json_data['footer_useful_links']['links']) && is_array($footer_json_data['footer_useful_links']['links'])) { ?>
                        <?php foreach ($footer_json_data['footer_useful_links']['links'] as $index => $link) { ?>
                            <li>
                                <?php echo htmlspecialchars($link['title']); ?> - <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank"><?php echo htmlspecialchars($link['url']); ?></a>
                                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>site-settings/footer_settings" method="post" style="display:inline;">
                                    <input type="hidden" name="delete_index" value="<?php echo $index; ?>">
                                    <button type="submit" name="delete_link" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </li>
                            <br>
                        <?php } ?>
                    <?php } ?>
                </ul>
                <br>
                <hr>
                <!-- List existing compatible devices -->
                <h3>Existing Compatible Devices:</h3>
                <hr>
                <ul>
                    <?php if (isset($footer_json_data['compatible_divices']['divice']) && is_array($footer_json_data['compatible_divices']['divice'])) { ?>
                        <?php foreach ($footer_json_data['compatible_divices']['divice'] as $index => $logo) { ?>
                            <li>
                                <img src="<?php echo $GLOBALS['site_url'] . htmlspecialchars($logo['logo'] ?? ''); ?>" alt="Device Logo" style="width:50px;height:50px;">
                                <a href="<?php echo htmlspecialchars($logo['url'] ?? ''); ?>" target="_blank"><?php echo  htmlspecialchars($logo['url'] ?? ''); ?></a>
                                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>site-settings/footer_settings" method="post" style="display:inline;">
                                    <input type="hidden" name="delete_index" value="<?php echo $index; ?>">
                                    <button type="submit" name="remove_divice" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </li>
                        <?php } ?>
                    <?php } else { ?>
                        <li>No compatible devices added yet.</li>
                    <?php } ?>
                </ul>
            </div>
            <?php echo alert_message(); ?>
        </div>

        <!-- Footer -->
        <?php include '../../footer.php'; ?>
    </div>
</body>

</html>