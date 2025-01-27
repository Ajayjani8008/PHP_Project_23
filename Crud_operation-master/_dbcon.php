<?php
$server = "localhost";
$user = "root";
$passwrod = "";
$db = "crud";


$con = mysqli_connect($server, $user, $passwrod, $db);

if (!$con) {
    print("error!!!");
    
} else {
    // print("connection succesfull.");
}
