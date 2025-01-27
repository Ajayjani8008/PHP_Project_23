<?php
session_start();

// Include necessary files
include_once '../config/DbConfig.php';
include_once '../config/Crud.php';
include_once '../config/validation.php';
include_once '../config/functions.php';
// Instantiate Crud class

if (isset($_SESSION['auth'])) {
  $_SESSION['status_authorized'] = 'you are already logged in';
  header('Location:'.$GLOBALS['site_url']);
}

$crud = new Crud();
$validate = new Validation();
$conn = new DbConfig();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in </title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $GLOBALS['site_url']; ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo $GLOBALS['site_url']; ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $GLOBALS['site_url']; ?>/assets/dist/css/adminlte.min.css">
  <!-- sweet alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="<?php echo $GLOBALS['site_url']; ?>" class="h1"><b>Admin</b>LTE</a>
      </div>
      <div class="card-body">
        <?php
        if (isset($_SESSION['status_unauthorized'])) {
        ?>
          <div class="alert alert-warning" role="alert" style="text-align: center;">
            <?php echo $_SESSION['status_unauthorized']; ?>
          </div>
        <?php
          unset($_SESSION['status_unauthorized']);
        }

        ?>
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="<?php echo htmlspecialchars($GLOBALS['site_url'].'auth/login'); ?> " id="loginfrm" method="post">
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email" name="email">
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
          <div class="row">
            <!-- <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div> -->
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" name="loginbtn" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <!-- <div class="social-auth-links text-center mt-2 mb-3">
          <a href="#" class="btn btn-block btn-primary">
            <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
          </a>
          <a href="#" class="btn btn-block btn-danger">
            <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
          </a>
        </div> -->
        <!-- /.social-auth-links -->

        <p class="mb-1">
          <a href="<?php echo $GLOBALS['site_url']; ?>auth/forgot_password">I forgot my password</a>
        </p>
        <p class="mb-0">
          <a href="<?php echo $GLOBALS['site_url']; ?>auth/register" class="text-center">Register a new membership</a>
        </p>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?php echo $GLOBALS['site_url']; ?>/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?php echo $GLOBALS['site_url']; ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo $GLOBALS['site_url']; ?>/assets/dist/js/adminlte.min.js"></script>

  <!-- <script>
    <?php //if (isset($_GET['login'])) : 
    ?>
      <?php //if ($_GET['login'] === 'failed') : 
      ?>
        $(document).ready(function() {
          Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: 'Invalid email or password.',
          });
        });
      <?php // endif; 
      ?>
    <?php //endif; 
    ?>
  </script> -->
  <script>
    jQuery(document).ready(function() {
      jQuery("#loginbtn").submit(function(event) {
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['loginbtn'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  if (empty($email) || empty($password)) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'All fields are required!',
        })
        </script>";
    exit();
  }

  $result = $crud->getData("users", "email='" . $email . "' AND role='admin'", "", "");


  if ($result) {
    // User found, check password
    $user = $result[0]; // Assuming the result is an array of users and we take the first one

    $hashed_password_from_database = $user['password'];

    if (password_verify($password, $hashed_password_from_database)) {

      if ($user['role'] === 'admin') {

        $_SESSION['auth'] = true;

        $user_name = $user['name'];
        $user_email = $user['email'];
        $user_password = $user['password'];
        $user_id = $user['id'];

        $_SESSION['auth_user'] = [
          'user_name' => $user_name,
          'user_email' => $user_email,
          'user_password' => $user_password,
          'user_id' => $user_id,
        ];

        // Redirect to dashboard or another page
        // header("Location: ../index");
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Login Successful!',
            text: 'Welcome back, admin!',
        }).then((result) => {
            // Redirect to dashboard or another page
            window.location.href = '../';
        });
      </script>";

        exit();
      } else {

        echo "You don't have admin privileges";
      }
    } else {

      echo "<script>
      Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Invalid email or password!',
      });
    </script>";
    }
  } else {

    echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'User not found!',
                });
              </script>";
  }
} else {
  // echo "<span style='color: red;'>There is a problem with the login.</span>";
  // $_SESSION['status'] = 'access denied';
  header('Location:'.$GLOBALS['site_url'].'auth/login');
}





// this is session data code  use  them where it's need 
// On other pages where session data is needed
// session_start();
// if (isset($_SESSION['user_id'])) {
//     $user_id = $_SESSION['user_id'];
//     // Fetch additional user data if needed
//     // $user_info = fetch_user_info($user_id);
// } else {
//     // Redirect to login page or display error message
//     header("Location: login");
//     exit();
// }

?>