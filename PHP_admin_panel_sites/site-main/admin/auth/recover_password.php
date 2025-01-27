<?php
session_start();
// include_once '../config/DbConfig.php';
include_once '../config/Crud.php';
include_once '../config/validation.php';
include_once '../config/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Recover Password </title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $GLOBALS['site_url']; ?>plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo $GLOBALS['site_url']; ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $GLOBALS['site_url']; ?>assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">

    <?php if (isset($_SESSION['status'])) :

    ?>
      <div class="alert alert-info" role="alert">
        <?php echo $_SESSION['status'];
        unset($_SESSION['status']); ?>
      </div>

    <?php endif; ?>

    <?php if (isset($_SESSION['status_success'])) :

    ?>
      <div class="alert alert-success" role="alert">
        <?php echo $_SESSION['status_success'];
        unset($_SESSION['status_success']); ?>
      </div>

    <?php endif; ?>



    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="<?php echo $GLOBALS['site_url']; ?>index" class="h1"><b>Admin</b>LTE</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
        <form action="forgot_password_code" method="post">

          <div class="input-group mb-3">
            <input type="hidden" value="<?php if (isset($_GET['token'])) {
                                          echo $_GET['token'];
                                        } ?>" class="form-control" placeholder="token" name="password_token">
          </div>

          <div class="input-group mb-3">
            <input type="text" value="<?php if (isset($_GET['email'])) {
                                        echo $_GET['email'];
                                      } ?>" class="form-control" placeholder="email" name="email">
          </div>


          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="new_password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block" name="change_password">Change password</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <p class="mt-3 mb-1">
          <a href="<?php echo $GLOBALS['site_url']; ?>auth/login">Login</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?php echo $GLOBALS['site_url']; ?>plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?php echo $GLOBALS['site_url']; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo $GLOBALS['site_url']; ?>assets/dist/js/adminlte.min.js"></script>
</body>

</html>