<?php
session_start();
include_once 'config/DbConfig.php';
include_once 'config/Crud.php';
include_once 'config/Validation.php';
include_once 'config/functions.php';

$conn = new DbConfig();
$crud = new Crud(); 


if (!isset($_SESSION['auth'])) {
    $_SESSION['status_unauthorized'] = 'Please Login First !!!';
    header('Location: ' . $GLOBALS['admin_site_url'] . 'auth/login.php');
    exit();
  }

$username = $_SESSION['user_data']['user_username'];
$userData = $crud->getData('users', "username='" . $username . "'", "", "")[0];
$profileData = json_decode($userData['profile_data'], true);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_image') {

    $userData = $crud->getData('users', "username='" . $username . "'", "", "")[0];
    $profileData = json_decode($userData['profile_data'], true);
    $profileImage = $profileData['profileImage'];

    if ($profileImage && $profileImage !== 'assets/img/profile_image.jpg') {
      
        @unlink($GLOBALS['uploads_dir_root'] . $profileImage); 
    }

  
    $profileData['profileImage'] = 'assets/img/profile_image.jpg';

 
    $jsonData = json_encode($profileData);
    $updateFields = ['profile_data' => $jsonData];
    $whereCondition = ['username' => $username];

    $updateResult = $crud->update('users', $updateFields, $whereCondition);

    if ($updateResult === true) {
        echo 'Profile image deleted and set to default.';
    } else {
        echo 'Failed to update profile image.';
    }
    exit;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {

    $profileImage = $profileData['profileImage']; 
    $targetDir = "uploads/";

    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $targetDir;
   
    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }

    if (!empty($_FILES['profileImage']['name'])) {
        $uploadResult = uploadFile($_FILES['profileImage'], $absoluteUploadsDir);

        if ($uploadResult['status']) {
            $profileImage = $targetDir.htmlspecialchars($uploadResult['file_name']);
        } else {
            echo $uploadResult['message'];
        }
    } else {
        $profileImage = $profileData['profileImage'];
    }


    $fullName = $_POST['fullName'] ?? '';
    $about = $_POST['about'] ?? '';
    $company = $_POST['company'] ?? '';
    $job = $_POST['job'] ?? '';
    $country = $_POST['country'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $twitter = $_POST['twitter'] ?? '';
    $facebook = $_POST['facebook'] ?? '';
    $instagram = $_POST['instagram'] ?? '';
    $linkedin = $_POST['linkedin'] ?? '';

    $jsonData = json_encode([
        'profileImage' => $profileImage,
        'about' => $about,
        'company' => $company,
        'job' => $job,
        'country' => $country,
        'address' => $address,
        'phone' => $phone,
        'twitter' => $twitter,
        'facebook' => $facebook,
        'instagram' => $instagram,
        'linkedin' => $linkedin
    ]);

    $username = $_SESSION['user_data']['user_username'];
    $updateFields = [
        'name' => $fullName,
        'email' => $email,
        'profile_data' => $jsonData
    ];

    $whereCondition = [
        'username' => $username
    ];

    $tableName = 'users'; 
    $updateResult = $crud->update($tableName, $updateFields, $whereCondition);

    if ($updateResult === true) {
        header("Location: users-profile.php"); 
    } else {
        echo "Error: " . $updateResult;
    }
}
?>
