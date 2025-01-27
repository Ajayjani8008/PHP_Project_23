<?php
session_start();
// include necessary files

include_once '../config/DbConfig.php';
include_once '../config/Crud.php';
include_once '../config/Validation.php';
include_once '../config/functions.php';
include_once '../includes/header.php';


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
                                if (isset($_SESSION['status_error'])) {
                                ?>
                                    <div class="alert alert-danger" role="alert" style="text-align: center;">
                                        <?php echo $_SESSION['status_error']; ?>
                                    </div>
                                <?php
                                    unset($_SESSION['status_error']);
                                }

                                if (isset($_SESSION['status_success'])) {
                                ?>
                                    <div class="alert alert-success" role="alert" style="text-align: center;">
                                        <?php echo $_SESSION['status_success']; ?>

                                    </div>
                                    <div class="col-12">
                                        <a href="<?php echo $GLOBALS['admin_site_url'] ?>auth/login.php">
                                            <p class="text-center small">Go To Login </p>
                                    </div>
                                <?php
                                    unset($_SESSION['status_success']);
                                }
                                ?>

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Recover Your Password</h5>
                                        <p class="text-center small">Enter your new password</p>
                                    </div>
                                    <form class="row g-3 needs-validation" method="POST" action="forgot_password_code.php" novalidate id="recoverForm">




                                        <div class="input-group mb-3">
                                            <input type="hidden" value="<?php if (isset($_GET['token'])) {
                                                                            echo $_GET['token'];
                                                                        } ?>" class="form-control" placeholder="token" name="password_token">
                                        </div>
                                        <div class="col-12">
                                            <label for="email" class="form-label">Change Password For </label>

                                            <div class="input-group has-validation">
                                                <input type="text" name="email" class="form-control" id="email" value="<?php if (isset($_GET['email'])) {
                                                                                                                            echo $_GET['email'];
                                                                                                                        } ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <div class="input-group has-validation">
                                                <input type="password" placeholder="New Password" name="new_password" class="form-control" id="new_password" required>
                                                <div class="invalid-feedback">Please enter a valid password!</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="confirm_password" class="form-label">Confirm Password</label>
                                            <div class="input-group has-validation">
                                                <input type="password" placeholder="Confirm Password" name="confirm_password" class="form-control" id="confirm_password" required>
                                                <div class="invalid-feedback">Please confirm your password!</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button id="change_password" name="change_password" class="btn btn-primary w-100" type="submit">Reset Password</button>
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
    <script>
        jQuery(document).ready(function() {
            jQuery("#recoverForm").submit(function(event) {
                var newPassword = jQuery("#new_password").val();
                var confirmPassword = jQuery("#confirm_password").val();

                // Check if the passwords match
                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Passwords do not match!',
                    });
                    event.preventDefault(); // Prevent form submission
                }
            });
        });
    </script>
