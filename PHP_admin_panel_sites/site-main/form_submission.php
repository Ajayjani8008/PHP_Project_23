<?php
session_start();

error_reporting(E_ALL);

include_once 'admin/config/Dbconfig.php';
include_once 'admin/config/Crud.php';
include_once 'admin/config/functions.php';
// include_once 'admin/auth/authentication.php';
// include_once 'admin/header.php';
$crud = new Crud();

$contact_page_data = $crud->getData('contact_us', "id=1", '', '')[0];
$form_submission_data = $contact_page_data['form_submission_data'] ?? '{}';
$form_submission_json = json_decode($form_submission_data, true) ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));
    $service = htmlspecialchars(trim($_POST['service']));

    // validation
    if (!$name || !$email || !$message || !$service) {
        $_SESSION['status_error'] = "All fields are required.";
        header('Location:'.$GLOBALS['new_site_url'].'contact_us');
        exit;
    }

    // Prepare data for JSON encoding
    $form_data = [
        'name' => $name,
        'email' => $email,
        'message' => $message,
        'service' => $service
    ];

    $form_submission_json['client_contact'][] = $form_data;

    // Encode data as JSON
    $json_encoded_data = json_encode($form_submission_json);
    $data = ['form_submission_data' => $json_encoded_data];
    $result = $crud->update('contact_us', $data, ['id' => 1]);


    if ($result === true) {
        $_SESSION['status'] = 'Your message has been sent successfully! Thank You For Conect With Us. We Are Contact You Shortly !!!';
    } else {
        $_SESSION['status_error'] = "Error: " . $result;
    }
    header('Location:'.$GLOBALS['new_site_url'].'contact_us');
    exit();
}
