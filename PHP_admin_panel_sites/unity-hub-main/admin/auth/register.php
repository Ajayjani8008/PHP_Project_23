<?php
session_start();
include_once '../config/DbConfig.php';
include_once '../config/Crud.php';
include_once '../config/functions.php';
include_once '../includes/header.php';

$conn = new DbConfig();
$crud = new Crud();


if (isset($_POST['register'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $token = md5(rand());

  if (empty($name) || empty($email) || empty($username) || empty($password)) {
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
  $existingUsername = $crud->getData('users', "username='$username'", "", "");

  if ($existingUser) {
    // Email already exists, registration failed
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Email already exists!',
            showConfirmButton: true,
        }).then(function() {
            window.location.href = '" . $GLOBALS['admin_site_url'] . "auth/register.php';
        });
    </script>";
    exit(); // Stop further execution
}

if ($existingUsername) {
    // Username already exists, registration failed
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Username already exists!',
            showConfirmButton: true,
        }).then(function() {
            window.location.href = '" . $GLOBALS['admin_site_url'] . "auth/register.php';
        });
    </script>";
    exit(); // Stop further execution
}


  $data = array(
    'name' => $name,
    'email' => $email,
    'username' => $username,
    'password' => $hashed_password,
    'role_id' => 5, //by default subscriber  (can not login untill super admin  give permission)
    'token' => $token,
  );

  $result = $crud->insert('users', $data);



  if ($result === true) {
    echo "<script>
      Swal.fire({
          icon: 'success',
          title: 'Registration Successful',
          text: 'Welcome to Unity Hub!',
          showConfirmButton: false,
          timer: 2000
      }).then(function() {
          window.location.href = '" . $GLOBALS['admin_site_url'] . "auth/login.php'; 
      });
  </script>";
    exit();
  }
}

$site_settings_data = $crud->getData("site_settings", "id=1", "", "");
$siteData = $site_settings_data[0];
$siteIcon = $siteData['site_icon'] ?? null;
$siteLogo = $siteData['site_logo'] ?? null;
$siteName = $siteData['site_name'] ?? null;

?>

<main>
  <div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

            <div class="d-flex justify-content-center py-4">
              <a href="index.html" class="logo d-flex align-items-center w-auto">
                <img src="<?php echo !empty($siteLogo) ? $GLOBALS['admin_site_url'].$siteLogo : $GLOBALS['admin_site_url'].'assets/img/logo.png'; ?>" alt="">
                <span class="d-none d-lg-block"><?php echo $siteName;?></span>
              </a>
            </div><!-- End Logo -->

            <div class="card mb-3">

              <div class="card-body">

                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                  <p class="text-center small">Enter your personal details to create account</p>
                </div>

                <form action="<?php echo htmlspecialchars($GLOBALS['admin_site_url'].'auth/register.php'); 
                              ?>" id="reg_form" method="post">
                  <div class="col-12">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" name="name" class="form-control" id="name" required>
                    <div class="invalid-feedback">Please, enter your name!</div>
                  </div>

                  <div class="col-12">
                    <label for="email" class="form-label">Your Email</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                    <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                  </div>

                  <div class="col-12">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text" id="inputGroupPrepend">@</span>
                      <input type="text" name="username" class="form-control" id="username" required>
                      <div class="invalid-feedback">Please choose a username.</div>
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                    <div class="invalid-feedback">Please enter your password!</div>
                  </div>

                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
                      <label class="form-check-label" for="acceptTerms">I agree and accept the <a href="#">terms and conditions</a></label>
                      <div class="invalid-feedback">You must agree before submitting.</div>
                    </div>
                  </div>
                  <div class="col-12">
                    <button name="register" class="btn btn-primary w-100" type="submit">Create Account</button>
                  </div>
                  <div class="col-12">
                    <p class="small mb-0">Already have an account? <a href="<?php echo $GLOBALS['admin_site_url'] ?>auth/login.php">Log in</a></p>
                  </div>
                </form>

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