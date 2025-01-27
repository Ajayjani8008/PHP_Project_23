<?php
session_start();
$title = 'Career Detail';
include_once 'admin/config/Crud.php';
include_once 'admin/config/functions.php';
include_once 'includes/header.php';

// Initialize Crud object
$crud = new Crud();


?>

<body>
    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php include_once 'includes/navbar.php'; ?>
    <!-- header end -->
    <?php
    // Check if career ID is provided in the URL
    if (isset($_GET['slug'])) {
        // Get career ID from the URL
        $slug = $_GET['slug'];

        // Fetch career details from the database based on the slug
        $career_details = $crud->getData('careers', "slug='$slug'", '', '');

        // Check if career details are found
        if (!empty($career_details)) {
            // Display career details
            $career = $career_details[0]; // Assuming only one career is fetched

    ?>




            <!-- career detail section start -->
            <div class="blog-detail-shap">
                <div class="blog-detail-p-section">
                    <div class="container w-1000">
                        <div class="all-us-title-wapper">
                            <h5>Career Details</h5>
                            <h4><?php echo $career['main_heading']; ?></h4>
                            <p><?php echo $career['main_description']; ?></p>
                        </div>
                        <div class="blog-detail-p-main-wapper">
                            <div class="blog-detail-p-img">
                                <img src="<?php echo $GLOBALS['site_url'] . $career['top_img']; ?>" alt="career featured img">
                            </div>
                            <div class="blog-detail-p-text-wapper">
                                <div class="blog-detail-p-select-text">
                                    <h5><?php echo $career['highlighted_lines']; ?></h5>
                                </div>
                            </div>
                            <div class="blog-detail-p-text-wapper">
                                <p><?php echo $career['career_content']; ?> </p>
                            </div>
                            <div class="blog-detail-p-img">
                                <img src="<?php echo $GLOBALS['site_url'] . $career['bottom_img'] ?>" alt="career featured img ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- career detail section end -->

    <?php
        } else {
            // Handle case when career details are not found
            echo "career not found.";
        }
    } else {
        // Handle case when career ID is not provided in the URL
        echo "career slug is missing.";
    }
    ?>
    <?php
    $careers = $crud->getData('careers', "", '', ''); // Fetch all careers from the database
    // $career_details_page = $crud->getData('career_details_page', "", '', '');
    // echo '<pre>';
    // var_dump($career_details_page);


    ?>
    <!-- career section start -->
    <div class="career-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5><?php echo $career_details_page['careers_section_title']; ?></h5>
                <h4><?php echo $career_details_page['careers_section_heading']; ?></h4>
                <p><?php echo $career_details_page['careers_section_description']; ?></p>
            </div>
        </div>
        <div class="career-slider-wapper">
            <div class="owl-carousel" id="h-career-slider">
                <?php foreach ($careers as $career) { ?>
                    <div class="item">
                        <div class="career-card-wapper">
                            <div class="career-card-img">
                                <a href="career_details.php?slug=<?php echo $career['slug']; ?>">
                                    <img src="<?php echo $GLOBALS['site_url'] . htmlspecialchars($career['career_img']); ?>" alt="">
                                </a>
                            </div>
                            <div class="career-card-inner">
                                <h6><?php echo htmlspecialchars($career['career_author_role']); ?></h6>
                                <h4><a href="career_details.php?slug=<?php echo $career['slug']; ?>"><?php echo htmlspecialchars($career['career_heading']); ?></a></h4>
                                <ul>
                                    <li><span><i class="fas fa-comment-dots"></i></span><a href="#"><?php echo htmlspecialchars($career['career_comment_count']); ?></a></li>
                                    <li><span><i class="fas fa-eye"></i></span><a href="#"><?php echo htmlspecialchars($career['career_views']); ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php }; ?>
            </div>
        </div>
    </div>
    <!-- career section end -->

    <!-- footer section start -->

    <?php
    include_once("includes/footer.php");
    ?>
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/career/js/main.js"></script>
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/blog_details/js/main.js"></script>
    <!-- footer section end -->

</body>

</html>