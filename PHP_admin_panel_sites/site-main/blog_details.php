<?php
session_start();
$title = 'Blog Detail';
include_once 'admin/config/Crud.php';
include_once 'admin/config/functions.php';
include_once 'includes/header.php';

// Initialize Crud object
$crud = new Crud();

$blog_details_page = $crud->getData('blog_details_page', "id=1", '', '')[0];
?>

<body>
    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php include_once 'includes/navbar.php'; ?>
    <!-- header end -->

    <?php
    // Check if blog slug is provided in the URL
    if (isset($_GET['slug'])) {
        // Get blog slug from the URL
        $slug = $_GET['slug'];

        // Fetch blog details from the database based on the slug
        $blog_details = $crud->getData('blogs', "slug='$slug'", '', '');

        // Check if blog details are found
        if (!empty($blog_details)) {
            // Display blog details
            $blog = $blog_details[0]; // Assuming only one blog is fetched

    ?>



            <!-- blog detail section start -->
            <div class="blog-detail-shap">
                <div class="blog-detail-p-section">
                    <div class="container w-1000">
                        <div class="all-us-title-wapper">
                            <h5><?php echo $blog_details_page['blog_details_title'] ?></h5>
                            <h4><?php echo $blog['main_heading']; ?></h4>
                            <p><?php echo $blog['main_description']; ?></p>
                        </div>
                        <div class="blog-detail-p-main-wapper">
                            <div class="blog-detail-p-img">
                                <img src="<?php echo $GLOBALS['site_url'].$blog['top_img']; ?>" alt="blog featured img">
                            </div>
                            <!-- <div class="blog-detail-p-text-wapper">
                                <div class="blog-detail-p-select-text">
                                    <h5><?php //echo $blog['highlighted_lines']; 
                                        ?></h5>
                                </div>
                            </div> -->
                            <div class="blog-detail-p-text-wapper">
                                <p><?php echo $blog['blog_content']; ?> </p>
                            </div>
                            <div class="blog-detail-p-img">
                                <img src="<?php echo $GLOBALS['site_url'].$blog['bottom_img'] ?>" alt="blog featured img ">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- blog detail section end -->

        <?php
        } else {
            // Handle case when blog details are not found
            echo "Blog not found.";
        }
    } else {
        // Handle case when blog ID is not provided in the URL
        echo "Blog Slug is missing.";
    }
        ?>
        <?php
        $blogs = $crud->getData('blogs', "", '', ''); // Fetch all blogs from the database



        ?>
        <!-- blog section start -->
        <div class="blog-section">
            <div class="container">
                <div class="all-us-title-wapper">
                    <h5><?php echo $blog_details_page['blogs_section_title']; ?></h5>
                    <h4><?php echo $blog_details_page['blogs_section_heading']; ?></h4>
                    <p><?php echo $blog_details_page['blogs_section_description']; ?></p>
                </div>
            </div>
            <div class="blog-slider-wapper">
                <div class="owl-carousel" id="h-blog-slider">
                    <?php foreach ($blogs as $blog) { ?>
                        <div class="item">
                            <div class="blog-card-wapper">
                                <div class="blog-card-img">
                                    <a href="<?php echo $blog['slug']; ?>">
                                        <img src="<?php echo $GLOBALS['site_url'] . htmlspecialchars($blog['blog_img']); ?>" alt="">
                                    </a>
                                </div>
                                <div class="blog-card-inner">
                                    <h6><?php echo htmlspecialchars($blog['blog_author_role']); ?></h6>
                                    <h4><a href="<?php echo $blog['slug']; ?>"><?php echo htmlspecialchars($blog['blog_heading']); ?></a></h4>
                                    <ul>
                                        <li><span><i class="fas fa-comment-dots"></i></span><a href="#"><?php echo htmlspecialchars($blog['blog_comment_count']); ?></a></li>
                                        <li><span><i class="fas fa-eye"></i></span><a href="#"><?php echo htmlspecialchars($blog['blog_views']); ?></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>
                </div>
            </div>
        </div>
        <!-- blog section end -->
            </div>
            <!-- footer section start -->

            <?php
            include_once("includes/footer.php");
            ?>
            <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/blog_details/js/main.js"></script>
            <!-- footer section end -->

</body>

</html>