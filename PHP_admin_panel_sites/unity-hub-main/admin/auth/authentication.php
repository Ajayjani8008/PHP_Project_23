<?php
session_start();
include_once '../config/DbConfig.php';
include_once '../config/functions.php';
include_once '../config/Crud.php';

$conn = new DbConfig();
$crud = new Crud();

if (!isset($_SESSION['auth'])) {
    $_SESSION['status_unauthorized'] = 'Please Login First !!!';
    header('Location: ' . $GLOBALS['admin_site_url'] . 'auth/login.php');
    exit();
}

if (isset($_SESSION['role_id'])) {
    $role_id = $_SESSION['role_id'];

    global $pdo; 
    $stmt = $pdo->prepare("SELECT role_name FROM roles WHERE id = ?");
    $stmt->execute([$role_id]);
    $role = $stmt->fetch();

   
    if (!$role || $role['role_name'] !== 'admin') {
        $_SESSION['status_unauthorized'] = 'Access Denied!';
        header('Location: ' . $GLOBALS['admin_site_url'] . 'auth/login.php');
        exit();
    }
}
