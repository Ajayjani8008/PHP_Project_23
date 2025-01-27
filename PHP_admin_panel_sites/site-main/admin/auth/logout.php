<?php
session_start();
include_once '../config/DbConfig.php';
include_once '../config/Crud.php';
include_once '../config/validation.php';
include_once '../config/functions.php';

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    <script>
        Swal.fire({
            icon: "success",
            title: "Logged Out Successfully!",
            text: "You have been successfully logged out.",
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = "<?php echo $GLOBALS['site_url']; ?>auth/login";
        });
    </script>
</body>
</html>
