<?php

include_once '../config/DbConfig.php';
include_once '../config/Crud.php';
include_once '../config/functions.php';
// include 'authentication.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Registration Page </title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo $GLOBALS['site_url'] ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?php echo $GLOBALS['site_url'] ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo $GLOBALS['site_url'] ?>/assets/dist/css/adminlte.min.css">
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> -->
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="<?php echo $GLOBALS['site_url']; ?>index" class="h1"><b>Admin</b>LTE</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register a new membership</p>

                <form action="<?php echo htmlspecialchars($GLOBALS['site_url'].'auth/register'); ?>" id="reg_form" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Full name" name="name">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Retype password" name="confirm_password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                <label for="agreeTerms">
                                    I agree to the <a href="#">terms</a>
                                </label>
                            </div>
                        </div> -->
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <!-- <div class="social-auth-links text-center">
                    <a href="#" class="btn btn-block btn-primary">
                        <i class="fab fa-facebook mr-2"></i>
                        Sign up using Facebook
                    </a>
                    <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i>
                        Sign up using Google+
                    </a>
                </div> -->

                <a href="<?php echo $GLOBALS['site_url'] ?>auth/login" class="text-center">I already have a membership</a>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
    <!-- /.register-box -->

    <!-- jQuery -->
    <script src="<?php echo $GLOBALS['site_url'] ?>plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo $GLOBALS['site_url'] ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo $GLOBALS['site_url'] ?>assets/dist/js/adminlte.min.js"></script>



    <!-- <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../your_php_script.php', // Update with your PHP file path
                    data: formData,
                    success: function(response) {
                        if (response === "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'User registered successfully!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                // Redirect or do something else after success
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Registration failed: ' + response,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while processing your request.',
                        });
                    }
                });
            });
        });
    </script> -->

    <script>
        jQuery(document).ready(function() {
            jQuery("#reg_form").submit(function(event) {
                var emailStr = jQuery("#email").val();
                var regex = /^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,3}$/i;
                if (!regex.test(emailStr)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Enter valid email!',
                    });
                    event.preventDefault(); // Prevent form submission
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</body>

</html>


<?php
include_once '../config/DbConfig.php';
include_once '../config/Crud.php';
include_once '../config/functions.php';
// include_once '../config/validation.php';

$crud = new Crud();
// $validate = new Validation();

// Make sure the Validation class is correctly defined in functions.php

// if ($_SERVER["REQUEST_METHOD"] == "POST") 
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $con_password = $_POST['confirm_password'];
    $token=md5(rand());

    if (empty($name) || empty($email) || empty($password) || empty($con_password)) {
        // Display an error message or redirect back to the registration page
        echo "<script>
      Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'All fields are required!',
      })
        </script>";
        exit(); // Stop further execution
    }

    // Hash the password before saving it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $existingUser = $crud->getData('users', "email='$email'", "", "");

    if ($existingUser) {
        // Email already exists, registration failed
        echo "<script>
      Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Email already exists!',
      })
  </script>";
        exit(); // Stop further execution
    }


    if ($password !== $con_password) {
        // Passwords do not match, registration failed
        echo "<script>
      Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Passwords do not match!',
      })
  </script>";
        exit(); // Stop further execution
    }


    $data = array(
        'name' => $name,
        'email' => $email,
        'password' => $hashed_password,
        'role' => 'admin',
        'status' => 'active',
        'token'=>$token,
    );
    $result = $crud->insert('users', $data);

    if ($result === true) {
        echo "<script>
      Swal.fire({
          icon: 'success',
          title: 'Registration Successful',
          text: 'Welcome to LinkRank!',
          showConfirmButton: false,
          timer: 2000
      }).then(function() {
          window.location.href = '" . $GLOBALS['site_url'] . "auth/login'; 
      });
  </script>";
        exit();
    }
}
?>