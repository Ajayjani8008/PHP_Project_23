<?php
session_start();
$title = 'Blog';
include_once 'admin/config/Crud.php';
include_once 'admin/config/functions.php';
include_once 'includes/header.php';

$crud = new Crud();


// Pagination settings
$results_per_page = 3;

if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}

$start_index = ($page - 1) * $results_per_page;

// Fetch blogs for the current page from the database
$blogs = $crud->getData('blogs', "", "$start_index, $results_per_page", '');

// Count total number of blogs
$total_blogs = count($crud->getData('blogs', "", '', ''));

// Calculate total number of pages
$total_pages = ceil($total_blogs / $results_per_page);

?>

<body>

    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php
    include_once 'includes/navbar.php';
    ?>
    <!-- header end -->

    <!--blog page section start  -->
    <div class="blog-p-section">
        <div class="container">
            <?php
            $blog_page = $crud->getData('blog_page', "id=1", '', '')[0];
            ?>
            <div class="blog-p-main-wapper">
                <div class="blog-p-banner-img">
                    <img src="<?php echo $GLOBALS['site_url'] . $blog_page['blog_page_img'] ?>" alt="">
                </div>
                <h3><?php echo $blog_page['blog_page_heading'] ?></h3>
                <p><?php echo $blog_page['blog_page_description'] ?></p>
                <div class="blog-p-wapper">

                    <?php foreach ($blogs as $blog) : ?>

                        <div class="blog-p-item">
                            <div class="blog-p-item-img">
                                <a href="blog/<?php echo $blog['slug'] ?>">
                                    <img src="<?php echo $GLOBALS['site_url'] . $blog['blog_img'] ?>" alt=""></a>
                            </div>
                            <div class="blog-p-item-text">
                                <a style="color:black" href="blog/<?php echo $blog['slug'] ?>"><h3><?php echo $blog['blog_heading'] ?></h3></a>
                                <p><?php echo $blog['blog_short_description'] ?></p>
                                <div class="blog-p-author-wapper">
                                    <div class="blog-p-author-img-name-wapper">
                                        <div class="blog-p-author-img">
                                            <img src="<?php echo $GLOBALS['site_url'] . $blog['author_img'] ?>" alt="">
                                        </div>
                                        <div class="blog-p-author-name">
                                            <h5><?php echo $blog['blog_author'] ?></h5>
                                            <span><?php
                                                $date = new DateTime($blog['updated_at']);
                                                echo $date->format('F j, Y');
                                                ?></span>
                                        </div>
                                    </div>
                                    <div class="blog-p-author-more-dt-btn">
                                        <a href="#"><span><i class="fas fa-bookmark"></i></span></a>
                                        <a href="#"><span><i class="fas fa-ellipsis-h"></i></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>

                <div class="blog-pagination-wapper">
                    <ul>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li><a href="?page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a></li>
                        <?php endfor; ?>
                    </ul>
                    <div class="blog-pagination-next-pre-btn">
                        <?php if ($page > 1) : ?>
                            <div class="before-btn pagination-btn">
                                <a href="?page=<?php echo $page - 1; ?>"><span><i class="fas fa-arrow-left"></i></span>Before</a>
                            </div>
                        <?php endif; ?>
                        <?php if ($page < $total_pages) : ?>
                            <div class="next-btn pagination-btn">
                                <a href="?page=<?php echo $page + 1; ?>">Next<span><i class="fas fa-arrow-right"></i></span></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--blog page section end  -->

    <!-- footer section start -->
    <?php
    include_once("includes/footer.php");
    ?>
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/blog/js/main.js"></script>
    <!-- footer section end -->

</body>

</html>
