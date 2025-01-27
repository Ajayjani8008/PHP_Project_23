<?php
session_start();
include_once("../config/functions.php");
include_once("../config/DbConfig.php");
include_once("../config/Crud.php");

$conn = new DbConfig();
$crud = new Crud();

if (!userHasPermission($_SESSION['user_data']['user_id'], 'delete')) {
    header("Location:" . $GLOBALS['admin_site_url'] . "post/posts.php");
    exit();
}

$post_slug = $_GET['slug'];
$record = $crud->getData('posts', "slug='".$post_slug."'", "", "id");

if (empty($record)) {
    $_SESSION['deletation_status_error'] = "Post not found.";
    header("Location:" . $GLOBALS['admin_site_url'] . "post/posts.php");
    exit();
}

$post_id = $record[0];

if (!isset($post_id['id'])) {
    $_SESSION['deletation_status_error'] = "Post ID not found.";
    header("Location:" . $GLOBALS['admin_site_url'] . "post/posts.php");
    exit();
}

$result = $crud->delete('posts', $post_id['id']);

if ($result) {
    $_SESSION['deletation_status'] = "Post deleted successfully";
    header("Location:" . $GLOBALS['admin_site_url'] . "post/posts.php");
    exit();
} else {
    $_SESSION['deletation_status_error'] = "Post not Deleted !!!";
    header("Location:" . $GLOBALS['admin_site_url'] . "post/posts.php");
    exit();
}
