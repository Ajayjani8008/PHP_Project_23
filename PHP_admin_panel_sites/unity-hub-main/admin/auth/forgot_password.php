<?php
session_start();
// include necessary files

include_once '../config/DbConfig.php';
include_once '../config/Crud.php';
include_once '../config/Validation.php';
include_once '../config/functions.php';
include_once '../includes/header.php';

if (isset($_SESSION['auth'])) {
    $_SESSION['status_authorized'] = 'you are already logged in';
    header('Location:' . $GLOBALS['admin_site_url']);
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
                                <img src="<?php echo !empty($siteLogo) ? $GLOBALS['admin_site_url'] . $siteLogo : $GLOBALS['admin_site_url'] . 'assets/img/logo.png'; ?>" alt="">
                                <span class="d-none d-lg-block"><?php echo $siteName; ?></span>
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
                                    <h5 class="card-title text-center pb-0 fs-4">Forgot Your Password</h5>

                                </div>
                                <br>

                                <form class="row g-3 needs-validation" method="POST" action="forgot_password_code.php" novalidate id="forgotForm">


                                    <div class="col-12">
                                        <label for="email" class="form-label">Enter Your Registered Email</label>
                                        <div class="input-group has-validation">
                                            <input type="email" placeholder="Email" name="email" class="form-control" id="email" required>

                                            <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                    </div>
                                    <div class="col-12">
                                        <button id="new_password_link" name="new_password_link" class="btn btn-primary w-100" type="submit">Request New Password</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0"><a href="<?php echo $GLOBALS['admin_site_url'] ?>/auth/login.php">Login</a></p>
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
        jQuery("#forgotForm").submit(function(event) {
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