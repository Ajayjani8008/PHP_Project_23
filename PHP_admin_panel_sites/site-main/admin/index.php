<?php
// session_start();
include_once 'header.php';
include_once 'config/functions.php';
include_once 'auth/authentication.php';


?>
<!-- header end -->

<body>
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center" style="height: 0px;">
            <img class="animation__shake" src="<?php echo $GLOBALS['site_url']; ?>assets/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60" style="display: none;">
        </div>


        <!-- header start -->
        <?php 
        include_once 'navbar.php';
        ?>

        <!-- sidebar start -->
        <?php
        include 'sidebar.php';
        ?>
        <!-- sidebar end -->


        <!-- dashbord area start -->
        <div class="content-wrapper" style="min-height: 107px;">
            <h3>Dashboard Area</h3>
            <?php
            if (isset($_SESSION['status_authorized'])) {
            ?>
                <div class="alert alert-success" style="text-align: center; ">
                    <strong>Hyy! </strong><?php echo $_SESSION['status_authorized']; ?>
                </div>
            <?php
                unset($_SESSION['status_authorized']);
            }
            ?>
        </div>
        <!-- dashbord area end -->


        <!-- footer start -->
        <?php
        include 'footer.php';
        ?>
        <!-- footer end -->
</body>

</html>