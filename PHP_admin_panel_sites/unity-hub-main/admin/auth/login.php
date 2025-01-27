<?php
session_start();



include_once '../config/DbConfig.php';
include_once '../config/Crud.php';
include_once '../config/Validation.php';
include_once '../config/functions.php';
include_once '../includes/header.php';

if (isset($_SESSION['auth'])) {
  $_SESSION['status_authorized'] = 'you are already logged in';

  if ($_SESSION['user_data']['role_id'] == 1) {
    header("Location:" . $GLOBALS['admin_site_url'] . "superadmin_dashboard.php");
  }
  if ($_SESSION['user_data']['role_id'] == 2) {
    header("Location:" . $GLOBALS['admin_site_url'] . "admin_dashboard.php");
  }
  if ($_SESSION['user_data']['role_id'] == 3) {
    header("Location:" . $GLOBALS['admin_site_url'] . "user_dashboard.php");
  }
  if ($_SESSION['user_data']['role_id'] == 4) {
    header("Location:" . $GLOBALS['admin_site_url'] . "writer_dashboard.php");
  }
  if ($_SESSION['user_data']['role_id'] > 4) {
    header("Location:../../");
  }
}

$conn = new DbConfig();
$validate = new Validation();
$crud = new Crud();

$site_settings_data = $crud->getData("site_settings", "id=1", "", "");
$siteData = $site_settings_data[0];
$siteIcon = $siteData['site_icon'] ?? null;
$siteLogo = $siteData['site_logo'] ?? null;
$siteName = $siteData['site_name'] ?? null;
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<main>
  <div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

            <div class="d-flex justify-content-center py-4">
              <a href="<?php echo $GLOBALS['admin_site_url'] ?>index.php" class="logo d-flex align-items-center w-auto">
                <img src="<?php echo !empty($siteLogo) ? $GLOBALS['admin_site_url'].$siteLogo : $GLOBALS['admin_site_url'].'assets/img/logo.png'; ?>" alt="">
                <span class="d-none d-lg-block"><?php echo $siteName;?></span>
              </a>
            </div><!-- End Logo -->

            <div class="card mb-3">
              <?php
              if (isset($_SESSION['status_unauthorized'])) {
              ?>
                <div class="alert alert-warning" role="alert" style="text-align: center;">
                  <?php echo $_SESSION['status_unauthorized']; ?>
                </div>
              <?php
                unset($_SESSION['status_unauthorized']);
              }
              if (isset($_SESSION['status'])) {
              ?>
                <div class="alert alert-warning" role="alert" style="text-align: center;">
                  <?php echo $_SESSION['status']; ?>
                </div>
              <?php
                unset($_SESSION['status']);
              }

              ?>

              <div class="card-body">

                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                  <p class="text-center small">Enter your username & password to login</p>
                </div>

                <form class="row g-3 needs-validation" method="POST" action="" novalidate id="loginForm">


                  <div class="col-12">
                    <label for="yourUsername" class="form-label">Username</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text" id="inputGroupPrepend">@</span>
                      <input type="text" name="username" class="form-control" id="yourUsername" required>
                      <div class="invalid-feedback">Please enter your username.</div>
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="yourPassword" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="yourPassword" required>
                    <div class="invalid-feedback">Please enter your password!</div>
                  </div>

                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <button id="loginbtn" name="loginbtn" class="btn btn-primary w-100" type="submit">Login</button>
                  </div>
                  <div class="col-12">
                    <p class="small mb-0"><a href="<?php echo $GLOBALS['admin_site_url'] ?>auth/forgot_password.php">Forgot Password</a></p>
                    <p class="small mb-0">Don't have account? <a href="<?php echo $GLOBALS['admin_site_url'] ?>auth/register.php">Create an account</a></p>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>

    </section>

  </div>

</main><!-- End #main -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<?php
include_once '../includes/auth-footer.php';
?>




<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['loginbtn'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  if (empty($username) || empty($password)) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'All fields are required!',
        })
        </script>";

    exit();
  }


  $result = $crud->getJoinData("users", "username='" . $username . "'", "users.*, roles.role_name", "roles", "users.role_id = roles.id");




  if ($result) {
    $user = $result[0];
    $hashed_password_from_database = $user['password'];

    if (password_verify($password, $hashed_password_from_database)) {

      $user_name = $user['name'];
      $role_name = $user['role_name'];
      $user_username = $user['username'];
      $user_password = $user['password'];
      $user_id = $user['id'];
      $role_id = $user['role_id'];
      $user_email = $user['email'];

      $_SESSION['user_data'] = [
        'role_id' => $role_id,
        'role_name' =>  $role_name,
        'user_name' => $user_name,
        'user_username' => $user_username,
        'user_password' => $user_password,
        'email' => $user_email,
        'user_id' => $user_id,
      ];

      $_SESSION['auth'] = true;

      if ($user['role_id'] == 1) {
        $_SESSION['super_auth'] = true;
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Login Successful!',
            text: 'Welcome back, super admin!',
        }).then((result) => {
            // Redirect to dashboard or another page
            window.location.href = '../superadmin_dashboard.php';
        });
      </script>";

        exit();
      } elseif ($user['role_id'] == 2) {
        $_SESSION['admin_auth'] = true;

        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Login Successful!',
            text: 'Welcome back, admin!',
        }).then((result) => {
          // redirect to home page
            window.location.href = '../admin_dashboard.php';
        });
      </script>";
      } elseif ($user['role_id'] == 3) {
        $_SESSION['user'] = true;
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Login Successful!',
            text: 'Welcome back, user!',
        }).then((result) => {
          // redirect to home page
            window.location.href = '../user_dashboard.php';
        });
      </script>";
      } elseif ($user['role_id'] == 4) {
        $_SESSION['writer'] = true;
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Login Successful!',
            text: 'Welcome back, writer!',
        }).then((result) => {
          // redirect to home page
            window.location.href = '../writer_dashboard.php';
        });
      </script>";
      } elseif ($user['role_id'] > 4) {
        $_SESSION['user'] = true;
        echo "<script>
      Swal.fire({
          icon: 'success',
          title: 'Login Successful!',
          text: 'Welcome back, subscriber!',
      }).then((result) => {
        // redirect to home page
          window.location.href = '../../';
      });
    </script>";
      }
    } else {

      echo "<script>
      Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Invalid username or password!',
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
  header('Location:' . $GLOBALS['admin_site_url'] . 'auth/login.php');
}
