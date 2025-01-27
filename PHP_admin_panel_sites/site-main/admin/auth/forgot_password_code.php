<?php
session_start();

require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);

$dotenv->load();

include_once   '../config/functions.php';
include_once   '../config/DbConfig.php';
include_once   '../config/Crud.php';


$conn = new DbConfig();
$crud = new Crud();

function send_email_link($get_email, $get_name, $token)
{


    $mail = new PHPMailer(true);
    try {
        // $mail->SMTPDebug = 2;
        // $mail->Debugoutput = 'html';

        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];//'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = 'tls'; //PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = $_ENV['SMTP_PORT'];//587;

        $mail->setFrom ($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']); 
        $mail->addAddress($get_email, $get_name);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $body    = 'Click the following link to reset your password for email: ' . htmlspecialchars($get_email) . '<br>'
            . 'Reset your password by clicking this link: <a href="' . $_ENV['SITE_URL'] . 'auth/recover_password?token=' . $token . '&email=' . $get_email . '">Reset Password</a>';
        // $mail->AltBody = 'Click the following link to reset your password for email: ' . htmlspecialchars($get_email) . '\n'
        //     . 'Reset your password by clicking this link: ' . $GLOBALS['site_url'] . 'auth/recover_password.php?token=' . $token . '&email=' . $get_email;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        if ($mail->send()) {
            $_SESSION['status'] = 'Password Reset Link  Sent Your Email Address ' . $get_email . '';
        } else {
            echo "problem sending email: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo "email not be send . mailer Error: {$mail->ErrorInfo}";
    }
}

if (isset($_POST['new_password_link'])) {
    $email = $crud->escape_string($_POST['email']);
    // $token = md5(rand());

    $check_email_exist = $crud->getData('users', "email='$email'", "", "");


    if ($check_email_exist) {
        $user_data = $check_email_exist[0];
        $get_name = $user_data['name'];
        $get_email = $user_data['email'];
        $token=$user_data['token'];

        $update_token = $crud->update('users', ['token' => $token], ["email" => $get_email]);

        if ($update_token) {
            send_email_link($get_email, $get_name, $token);
        } else {
            $_SESSION['status'] = 'Something went wrong.';
        }
    } else {
        $_SESSION['status'] = 'Email not registered';
    }
    header("Location: " . $GLOBALS['site_url'] . "auth/forgot_password");
    exit();
}

//for change(reset password)
if (isset($_POST['change_password'])) {

    $email = $crud->escape_string($_POST['email']);
    $new_password = $crud->escape_string($_POST['new_password']);
    $confirm_password = $crud->escape_string($_POST['confirm_password']);
    $token = $crud->escape_string($_POST['password_token']);

    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

    if (!empty($token)) {
        if (!empty($email) && !empty($new_password) && !empty($confirm_password)) {
            $check_token = $crud->getData('users', "token='$token'", "", "token");

            if ($check_token) {

                if ($new_password == $confirm_password) {
                    $update_password = $crud->update('users', ['password' => $hashed_new_password], ["token" => $token]);

                    if ($update_password) {

                        $new_token=md5(rand())."changedpassword";
                        $update_new_token=$crud->update('users', ['token' => $new_token],["token" => $token]);

                        $_SESSION['status_success'] = 'Password Change successfully.';
                    } else {
                        $_SESSION['status'] = 'Password changed  Failed !!! Somthing went to wrong.';
                    }
                } else {
                    $_SESSION['status'] = 'Password does not match.';
                }
            } else {
                $_SESSION['status'] = 'Invalid Password Token !!!';
            }
        } else {
            $_SESSION['status'] = 'All Feildes are required !!!';
        }
    } else {
        $_SESSION['status'] = 'Toeken Missing !!!';
    }
    header("Location:recover_password?token=$token&email=$email");
    exit();
}
