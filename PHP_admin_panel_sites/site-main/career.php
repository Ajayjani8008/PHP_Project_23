<?php
session_start();
$title = 'Career';
include_once 'admin/config/Crud.php';
include_once 'admin/config/functions.php';
include_once 'includes/header.php';

$crud = new Crud();

// Fetch career for the current page from the database
$careers = $crud->getData('careers', '', '', '');
$career_page = $crud->getData('career_page', 'id=1', '', '')[0];
//page Images
$featured_images=json_decode($career_page['images'],true);

?>

<body> 


    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php
    include_once 'includes/navbar.php';
    ?>
    <!-- header end -->

    <!--career page section start  -->

    <div class="career-bg-shap">
        <!-- career img section start -->
        <div class="career-page-img-section">
            <div class="container">
                <div class="career-img-row">
                    <div class="career-img-left-wapper">
                        <div class="career-img-left-item career-img-one">
                            <img src="<?php echo $GLOBALS['site_url'].$featured_images['career_page_img1']?>" alt="">
                        </div>
                        <div class="career-img-left-item career-img-two">
                            <img src="<?php echo $GLOBALS['site_url'].$featured_images['career_page_img2']?>" alt="">
                        </div>
                    </div>
                    <div class="career-img-right-wapper">
                        <div class="career-img-left-item career-img-three">
                            <img src="<?php echo $GLOBALS['site_url'].$featured_images['career_page_img3']?>" alt="">
                        </div>
                        <div class="career-img-left-item career-img-fore">
                            <img src="<?php echo $GLOBALS['site_url'].$featured_images['career_page_img4']?>" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- career img section start -->


        <!-- Career inner section start -->

        <div class="career-inner-section">
            <div class="container">
                <div class="all-us-title-wapper">
                    <h5><?php echo $career_page['page_title']?></h5>
                    <h4><?php echo $career_page['page_heading']?></h4>
                    <p><?php echo $career_page['page_description']?></p>
                </div>
                <div class="career-inner-row">

                    <?php foreach ($careers as $career) : ?>
                        <div class="career-inner-item">
                            <div class="career-inner-card-wapper">
                               <a style="color: black;" href="admin/career/<?php echo $career['slug']?>" ><h4><?php echo $career['career_name'];?></h4></a>
                                <p><?php echo $career['career_short_description'];?></p>
                                <div class="lorem-btn">
                                    <a href="career/<?php echo $career['slug'] ?>"><?php echo $career_page['button_text']?></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Career inner section end -->

    </div>

    <!-- footer section start -->
    <?php
    include_once("includes/footer.php");
    ?>
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/career/js/main.js"></script>
    <!-- footer section end -->



</body>

</html>