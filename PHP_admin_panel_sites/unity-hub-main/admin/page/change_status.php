<?php
session_start();
include_once "../config/functions.php";
include_once "../config/DbConfig.php";
include_once "../config/Crud.php";

$conn = new DbConfig();
$crud = new Crud();

if (!userHasPermission($_SESSION['user_data']['user_id'], 'edit')) {
    header("Location:" . $GLOBALS['admin_site_url'] . "page/pages.php");
    exit;
}

if (isset($_GET['slug']) && isset($_GET['status'])) {
    $slug = $_GET['slug'];
    $status = $_GET['status'];

    if ($status == 'published' || $status == 'draft') {
      
        $data = ['status' => $status];
      
        $update = $crud->update('pages', $data,['slug' => $slug]);
        if ($update) {
            $_SESSION['status_success'] = "Page Published successfully.";
        } else {
            $_SESSION['status_error'] = "Failed to Published Page.";
        }
    } else {
        $_SESSION['status_error'] = "Invalid status.";
    }
} else {
    $_SESSION['status_error'] = "Invalid request.";
}

header("Location:" . $GLOBALS['admin_site_url'] . "page/pages.php");
exit;
