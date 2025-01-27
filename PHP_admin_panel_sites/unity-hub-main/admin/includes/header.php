<?php
session_start();
include_once '../config/functions.php';
include_once '../config/DbConfig.php';
include_once '../config/Crud.php';
// Fetch the current user role_id
$current_role_id = $_SESSION['user_data']['role_id'];

$conn = new DbConfig();
$crud = new Crud();
$site_settings_data = $crud->getData("site_settings", "id=1", "", "");
$siteData = $site_settings_data[0];
$siteIcon = $siteData['site_icon'] ?? null;
$siteLogo = $siteData['site_logo'] ?? null;
$siteName = $siteData['site_name'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - <?php echo $siteName; ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?php echo $GLOBALS['admin_site_url'] . $siteIcon ?>" rel="icon">
  <link href="<?php echo $GLOBALS['admin_site_url']; ?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/vendor/simple-datatables/style.css" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="<?php echo $GLOBALS['admin_site_url'] ?>assets/css/style.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>

<body>