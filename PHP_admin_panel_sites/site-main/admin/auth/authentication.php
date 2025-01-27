<?php 
session_start();
if(!isset($_SESSION['auth'])){
    echo $_SESSION['status_unauthorized']='Please Login First !!!';
    header('Location:'.$GLOBALS['site_url'].'auth/login');
    exit(0);
}

?>