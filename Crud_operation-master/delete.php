<?php
include '_dbcon.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $deleteQuery = "DELETE FROM dtable WHERE id = $id";

    if (mysqli_query($con, $deleteQuery)) {
        header("Location: selectq.php");
        exit();
    } else {
        echo "Error deleting user";
    }
}
?>
