<?php
session_start();
include_once("../config/functions.php");
include_once("../config/DbConfig.php");
include_once("../config/Crud.php");

$conn = new DbConfig();
$crud = new Crud();

if (!userHasPermission($_SESSION['user_data']['user_id'], 'delete')) {
    header("Location:" . $GLOBALS['admin_site_url'] . "page/pages.php");
    exit();
}

$page_slug = $_GET['slug'];
$record = $crud->getData('pages', "slug='".$page_slug."'", "", "id");

if (empty($record)) {
    $_SESSION['deletation_status_error'] = "Page not found.";
    header("Location:" . $GLOBALS['admin_site_url'] . "page/pages.php");
    exit();
}

$page_id = $record[0];

if (!isset($page_id['id'])) {
    $_SESSION['deletation_status_error'] = "Page ID not found.";
    header("Location:" . $GLOBALS['admin_site_url'] . "page/pages.php");
    exit();
}

$result = $crud->delete('pages', $page_id['id']);

if ($result) {
    $_SESSION['deletation_status'] = "Page deleted successfully";
    header("Location:" . $GLOBALS['admin_site_url'] . "page/pages.php");
    exit();
} else {
    $_SESSION['deletation_status_error'] = "Page not Deleted !!!";
    header("Location:" . $GLOBALS['admin_site_url'] . "page/pages.php");
    exit();
}
