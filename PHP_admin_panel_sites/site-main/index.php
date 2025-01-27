<?php
include_once 'admin/config/functions.php';
$title = 'Home';
include_once 'admin/config/Crud.php';
include_once 'includes/header.php';

// Retrieve banner data
$result = $crud->getData('home', "id=1", '', '');
$banner_data = $result[0];

// Retrieve home data
$result = $crud->getData('home', "id=1", '', '');
$home_data = $result[0];
$main_site_url = getMainSiteUrl();
$testimonial_data_decoded = json_decode($home_data['testimonial'], true);


// Decode JSON data
$banner_data_decoded = json_decode($banner_data['banner_data'], true);

// Access individual elements
$heading = $banner_data_decoded['heading'];
$description = $banner_data_decoded['description'];
$button_name = $banner_data_decoded['button_name'];
$button_url = $banner_data_decoded['button_url'];

$miles_block1_title = $banner_data_decoded['blocks'][0]['title'];
$miles_block1_description = $banner_data_decoded['blocks'][0]['description'];
$miles_block1_img = $banner_data_decoded['blocks'][0]['img'];

$miles_block2_title = $banner_data_decoded['blocks'][1]['title'];
$miles_block2_description = $banner_data_decoded['blocks'][1]['description'];
$miles_block2_img = $banner_data_decoded['blocks'][1]['img'];

$miles_block3_title = $banner_data_decoded['blocks'][2]['title'];
$miles_block3_description = $banner_data_decoded['blocks'][2]['description'];
$miles_block3_img = $banner_data_decoded['blocks'][2]['img'];

?>

<body>

    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php
    include_once 'includes/navbar.php';
    ?>
    <!-- header end -->
    <!-- banner section start -->
    <div class="banner-section">
        <div class="container">
            <div class="banner-text-wapper">
                <h2><?php echo $heading; ?></h2>
                <p><?php echo $description; ?></p>
                <div class="banber-lorem-btn">
                    <a href="<?php echo $button_url; ?>"><?php echo $button_name; ?></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Miles Esther section start -->
    <div class="miles-esther-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="miles-card-wapper bg-color-FFF5EB">
                        <div class="miles-card-img">
                            <img src="<?php echo $GLOBALS['site_url'] . $miles_block1_img; ?>" alt="">
                        </div>
                        <div class="miles-card-inner">
                            <h6><?php echo $miles_block1_title; ?></h6>
                            <p><?php echo $miles_block1_description; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="miles-card-wapper bg-color-FFEDEB">
                        <div class="miles-card-img">
                            <img src="<?php echo $GLOBALS['site_url'] . $miles_block2_img; ?>" alt="">
                        </div>
                        <div class="miles-card-inner">
                            <h6><?php echo $miles_block2_title; ?></h6>
                            <p><?php echo $miles_block2_description; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="miles-card-wapper bg-color-F2F8F3">
                        <div class="miles-card-img">
                            <img src="<?php echo $GLOBALS['site_url'] . $miles_block3_img; ?>" alt="">
                        </div>
                        <div class="miles-card-inner">
                            <h6><?php echo $miles_block3_title; ?></h6>
                            <p><?php echo $miles_block3_description; ?></p>
                        </div>
                    </div>
                </div>
                <!-- Repeat for other blocks -->
            </div>
        </div>
    </div>

    <!-- Miles Esther section end -->

    <!-- banner section end -->

    <!-- about us sectin satrt -->
    <?php
    $aboutus_data = $result[0];
    $aboutus_data_decoded = json_decode($aboutus_data['aboutus_section'], true);

    // Access individual elements
    $aboutus_heading = $aboutus_data_decoded['heading'];
    $aboutus_description = $aboutus_data_decoded['description'];
    $featured_img = $aboutus_data_decoded['featured_image'];

    // Access block data
    $block1_title = $aboutus_data_decoded['blocks'][0]['title'];
    $block1_description = $aboutus_data_decoded['blocks'][0]['description'];
    $block1_img = $aboutus_data_decoded['blocks'][0]['img'];


    $block2_title = $aboutus_data_decoded['blocks'][1]['title'];
    $block2_description = $aboutus_data_decoded['blocks'][1]['description'];
    $block2_img = $aboutus_data_decoded['blocks'][1]['img'];

    $block3_title = $aboutus_data_decoded['blocks'][2]['title'];
    $block3_description = $aboutus_data_decoded['blocks'][2]['description'];
    $block3_img = $aboutus_data_decoded['blocks'][2]['img'];
    ?>

    <!-- About Us section start -->
    <div class="about-us-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5>About Us</h5>
                <h4><?php echo $aboutus_heading; ?></h4>
                <p><?php echo $aboutus_description; ?></p>
            </div>
            <div class="about-row">
                <div class="about-left-img">
                    <img src="<?php echo $GLOBALS['site_url'] . $featured_img; ?>" alt="">
                </div>
                <div class="about-right-text">
                    <div class="about-right-text-card">
                        <div class="about-right-text-card-img">
                            <img src="<?php echo $GLOBALS['site_url'] . $block1_img; ?>" alt="">
                        </div>
                        <div class="about-right-text-card-inner">
                            <h5><?php echo $block1_title; ?></h5>
                            <p><?php echo $block1_description; ?></p>
                        </div>
                    </div>
                    <div class="about-right-text-card">
                        <div class="about-right-text-card-img">
                            <img src="<?php echo $GLOBALS['site_url'] . $block2_img; ?>" alt="">
                        </div>
                        <div class="about-right-text-card-inner">
                            <h5><?php echo $block2_title; ?></h5>
                            <p><?php echo $block2_description; ?></p>
                        </div>
                    </div>
                    <div class="about-right-text-card">
                        <div class="about-right-text-card-img">
                            <img src="<?php echo $GLOBALS['site_url'] . $block3_img; ?>" alt="">
                        </div>
                        <div class="about-right-text-card-inner">
                            <h5><?php echo $block3_title; ?></h5>
                            <p><?php echo $block3_description; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About Us section end -->



    <!-- Price section satrt -->
    <?php

    // Retrieve pricing data
    $pricing_result = $crud->getData('home', "id=1", '', '');
    $pricing_data = json_decode($result[0]['pricing_tab'], true);

    // Access individual elements for pricing
    $price_title = $pricing_data['price_title'];
    $pricing_heading = $pricing_data['heading'];
    $pricing_description = $pricing_data['description'];
    $currency_type = $pricing_data['currency_type'];
    $time_interval = $pricing_data['time_interval'];
    $button_text = $pricing_data['button_text'];
    $tabs = $pricing_data['tabs'];
    ?>

    <div class="price-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5><?= htmlspecialchars($price_title); ?></h5>
                <h4><?php echo htmlspecialchars($pricing_heading); ?></h4>
                <p><?php echo htmlspecialchars($pricing_description); ?></p>
            </div>

            <?php
            $plans = $crud->getData('pricing', '', '', '');
            ?>
            <div class="price-row">

                <div class="price-left-wapper">
                    <div class="radio-buttons">
                        <ul class="tabs-nav-main">
                            <?php foreach ($plans as $index => $plan) : ?>
                                <li class="tabs-nav-main-inner <?php echo $index === 0 ? 'active' : ''; ?>" tab="t<?php echo $index + 1; ?>">
                                    <label class="custom-radio">
                                        <input type="radio" name="radio" <?php echo $index === 0 ? 'checked' : ''; ?>>
                                        <span class="radio-btn">
                                            <div class="check-and-checked-btn">
                                                <div class="check-btn">
                                                    <i class="far fa-circle"></i>
                                                </div>
                                                <div class="checked-btn">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            </div>
                                            <div class="plan-name-wapper">
                                                <h5><?php echo $plan['plan_name']; ?></h5>
                                                <span>Save <?php echo $plan['saving']; ?>%</span>
                                            </div>
                                            <div class="plan-price-wapper">
                                                <div class="plan-price">
                                                    <span class="dolor-s">$</span>
                                                    <h4><?php echo $plan['price']; ?> <span>/ Monthly</span></h4>
                                                </div>
                                            </div>
                                        </span>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="price-right-wapper">
                    <?php foreach ($plans as $index => $plan) : ?>
                        <?php $services = json_decode($plan['specification'], true); ?>
                        <div id="t<?php echo $index + 1; ?>" class="price-content <?php echo $index === 0 ? 'active' : ''; ?>">
                            <div class="price-right-ul">
                                <ul>
                                    <?php foreach ($services['on_service'] as $service) : ?>
                                        <li>
                                            <p><?php echo $service; ?></p><span><i class="fas fa-check"></i></span>
                                        </li>
                                    <?php endforeach; ?>
                                    <?php foreach ($services['off_service'] as $service) : ?>
                                        <li class="text-check min">
                                            <p><?php echo $service; ?></p><span><i class="fas fa-minus"></i></span>
                                        </li>
                                    <?php endforeach; ?>

                                </ul>
                            </div>
                            <div class="choose-plan-btn">
                                <a href="<?php echo $GLOBALS['new_site_url']?>pricing">Choose Plan</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Price section end -->
    <?php $testimonials = $crud->getData('testimonials', '', '', ''); ?>
    <!-- Testimony section start -->
    <div class="testimonial-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5><?php echo htmlspecialchars($testimonial_data_decoded['section']['heading']); ?></h5>
                <h4><?php echo htmlspecialchars($testimonial_data_decoded['section']['sub_title']); ?></h4>
                <p><?php echo htmlspecialchars($testimonial_data_decoded['section']['description']); ?></p>
            </div>
        </div>
        <div class="testimonial-slider">
            <div class="owl-carousel" id="h-testimonial-slider">
                <?php foreach ($testimonials as $testimonial) { ?>
                    <div class="item">
                        <div class="testimonial-slider-card">
                            <div class="testimonial-slider-card-img">
                                <img src="<?= $GLOBALS['site_url'] . htmlspecialchars($testimonial['image']); ?>" alt="">
                            </div>
                            <div class="testimonial-slider-card-inner">
                                <p>"<?= htmlspecialchars($testimonial['message']) ?>"</p>
                                <h5><?= htmlspecialchars($testimonial['name']) ?></h5>
                                <span><?= htmlspecialchars($testimonial['position']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>


    <!--  testimonial ends-->


    <?php
    $blogs = $crud->getData('blogs', "", '', ''); // Fetch all blogs from the database

    $blog_section = $crud->getData('home', "id=1", '', '')[0];
    $blog_section_decoded = json_decode($blog_section['blog'], true);
    ?>
    <!-- blog section start -->
    <div class="blog-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5><?php echo $blog_section_decoded['section']['blog_section_heading']; ?></h5>
                <h4><?php echo $blog_section_decoded['section']['blog_section_title']; ?></h4>
                <p><?php echo $blog_section_decoded['section']['blog_section_description']; ?></p>
            </div>
        </div>
        <div class="blog-slider-wapper">
            <div class="owl-carousel" id="h-blog-slider">
                <?php foreach ($blogs as $blog) { ?>
                    <div class="item">
                        <div class="blog-card-wapper">
                            <div class="blog-card-img">
                                <a href="blog/<?php echo $blog['slug']; ?>">
                                    <img src="<?php echo $GLOBALS['site_url'] . htmlspecialchars($blog['blog_img']); ?>" alt="">
                                </a>
                            </div>
                            <div class="blog-card-inner">
                                <h6><?php echo htmlspecialchars($blog['blog_author_role']); ?></h6>
                                <h4><a href="blog/<?php echo $blog['slug']; ?>"><?php echo htmlspecialchars($blog['blog_heading']); ?></a></h4>
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



    <!-- footer section start -->

    <?php
    include_once 'includes/footer.php';
    ?>


    <!-- footer section end -->
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/home/js/main.js"></script>
</body>

</html>