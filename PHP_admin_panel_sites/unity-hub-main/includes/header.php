<?php 
session_start();
include_once 'admin/config/functions.php';
include_once 'admin/config/DbConfig.php';
include_once 'admin/config/Crud.php';

$conn= new DbConfig();
$crud= new Crud();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?php echo htmlspecialchars($post['title']); ?> - Unity Hub</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/img/favicon.png" rel="icon">
    <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/css/style.css" rel="stylesheet">
</head>

<body>