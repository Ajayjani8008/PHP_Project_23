<?php
session_start();

include_once 'config/functions.php';
include_once 'config/DbConfig.php';
include_once 'config/Crud.php';
include_once 'includes/header.php';

$conn = new DbConfig();
$crud = new Crud();

if (!isset($_SESSION['user_data']['user_username'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['user_data']['role_id'] != 1) {
    header("Location: " . $GLOBALS['admin_site_url']);
    exit();
}

$data = $crud->getData("site_settings", "id=1", "", "");
$siteIcon = $data[0]['site_icon'] ?? null;
$siteLogo = $data[0]['site_logo'] ?? null;

include_once 'includes/navbar.php';
include_once "includes/sidebar.php";
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Site Settings</h1><br>

        <div class="card-body">

            <?php if (isset($_SESSION['deletation_setting'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i>
                    <?php echo $_SESSION['deletation_setting']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['deletation_setting']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['deletation_setting_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <?php echo $_SESSION['deletation_setting_error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['deletation_setting_error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['setting_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i>
                    <?php echo $_SESSION['setting_success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['setting_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['setting_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <?php echo $_SESSION['setting_error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['setting_error']); ?>
            <?php endif; ?>

            <form action="site_settings_code.php" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="site_name" id="site_name" value="<?php echo $data[0]['site_name'] ?? ''; ?>">
                            <label for="site_name">Site Name</label>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input class="form-control" type="file" name="site_icon" id="site_icon" onchange="previewImage(event, 'icon-preview')">
                            <label for="site_icon">Site Icon</label>
                            <div id="icon-preview" style="margin-top: 10px;">
                                <?php if ($siteIcon): ?>
                                    <img src="<?php echo $GLOBALS['admin_site_url'] . $siteIcon; ?>" alt="Site Icon" style="max-width: 200px; display: block;">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input class="form-control" type="file" name="site_logo" id="site_logo" onchange="previewImage(event, 'logo-preview')">
                            <label for="site_logo">Site Logo</label>
                            <div id="logo-preview" style="margin-top: 10px;">
                                <?php if ($siteLogo): ?>
                                    <img src="<?php echo $GLOBALS['admin_site_url'] . $siteLogo; ?>" alt="Site-Logo" style="max-width: 200px; display: block;">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" name="save_settings" class="btn btn-primary">Save Settings</button>
            </form>
        </div>
    </div>
</main>

<script>
    function previewImage(event, previewElementId) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById(previewElementId);
            output.innerHTML = '<img src="' + reader.result + '" alt="Image Preview" style="max-width: 200px; display: block;">';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<?php
include_once "includes/footer.php";
?>
