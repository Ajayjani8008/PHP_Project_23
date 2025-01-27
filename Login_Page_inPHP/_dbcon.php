<?php
// Creating a connection with the database
$conn = mysqli_connect('localhost', 'root','','users') or die(mysqli_error($conn));
if (!$conn) {
    die("There is a problem connecting to the database.");
}

// Create the database
$database = "CREATE DATABASE IF NOT EXISTS users";
if (mysqli_query($conn, $database)) {
    // echo "Database created successfully";
} else {
    echo "There is an error in creating the database: " . mysqli_error($conn);
}

// Create the user_data table
$table = "CREATE TABLE IF NOT EXISTS user_data (
    srno INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    userpass VARCHAR(30) NOT NULL
)";
if (mysqli_query($conn, $table)) {
    // echo "Table created successfully";
} else {
    echo "There is an error in creating the table: " . mysqli_error($conn);
}

// mysqli_close($conn);
