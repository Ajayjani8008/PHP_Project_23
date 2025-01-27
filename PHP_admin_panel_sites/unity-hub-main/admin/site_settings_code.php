<?php
session_start();

include_once 'config/functions.php';
include_once 'config/DbConfig.php';
include_once 'config/Crud.php';

$conn = new DbConfig();
$crud = new Crud();

if (!isset($_SESSION['user_data']['user_username'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['user_data']['role_id'] != 1) {
    header("Location: " . $GLOBALS['admin_site_url']);
    exit();
}


if (isset($_POST['delete_icon'])) {
    $existingSettings = $crud->getData("site_settings", "id=1", "", "");
    $siteIcon = !empty($existingSettings) ? $existingSettings[0]['site_icon'] : null;

    if ($siteIcon && file_exists($GLOBALS['uploads_dir_root'] . $siteIcon)) {
        unlink($GLOBALS['uploads_dir_root'] . $siteIcon); // Delete the icon
        $crud->update('site_settings', ['site_icon' => null], ['id' => 1]);
        $_SESSION['status_success'] = "Site icon deleted successfully.";
    } else {
        $_SESSION['status_error'] = "No site icon to delete.";
    }
    header("Location: " . $GLOBALS['admin_site_url'] . "site-settings.php");
    exit();
}

if (isset($_POST['save_settings'])) {
    $uploadDir = 'uploads/site-settings/';
    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadDir;

    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }

    $data = [];

    $existingSettings = $crud->getData("site_settings", "id=1", "", "");
    $siteIcon = !empty($existingSettings) ? $existingSettings[0]['site_icon'] : null;
    $siteLogo = !empty($existingSettings) ? $existingSettings[0]['site_logo'] : null;
    $data['site_name'] = $crud->escape_string($_POST['site_name'] ?? '');

    if (isset($_FILES['site_icon']) && $_FILES['site_icon']['error'] == 0) {
        $uploadResponse = uploadFile($_FILES['site_icon'], $absoluteUploadsDir);
        if ($uploadResponse['status']) {
            $data['site_icon'] = $uploadDir . $uploadResponse['file_name'];
            if ($siteIcon) {
                unlink($GLOBALS['uploads_dir_root'] . $siteIcon); // Delete the old icon
            }
        } else {
            $_SESSION['setting_error'] = "Error uploading site icon: " . $uploadResponse['message'];
            header("Location: " . $GLOBALS['admin_site_url'] . "site-settings.php");
            exit();
        }
    }

    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] == 0) {
        $uploadResponse = uploadFile($_FILES['site_logo'], $absoluteUploadsDir);
        if ($uploadResponse['status']) {
            $data['site_logo'] = $uploadDir . $uploadResponse['file_name'];
            if ($siteLogo) {
                unlink($GLOBALS['uploads_dir_root'] . $siteLogo); // Delete the old logo
            }
        } else {
            $_SESSION['setting_error'] = "Error uploading site logo: " . $uploadResponse['message'];
            header("Location: " . $GLOBALS['admin_site_url'] . "site-settings.php");
            exit();
        }
    }

    if (!empty($existingSettings)) {
        $update_data = $crud->update('site_settings', $data, ['id' => 1]);
        if ($update_data) {
            $_SESSION['setting_success'] = "Site settings updated successfully.";
        } else {
            $_SESSION['setting_error'] = "Failed to update site settings.";
        }
    } else {
        $insert_data = $crud->insert('site_settings', $data);
        if ($insert_data) {
            $_SESSION['setting_success'] = "Site settings saved successfully.";
        } else {
            $_SESSION['setting_error'] = "Failed to save site settings.";
        }
    }

    header("Location: " . $GLOBALS['admin_site_url'] . "site-settings.php");
    exit();
}
