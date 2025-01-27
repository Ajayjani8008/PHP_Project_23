<?php
include_once 'admin/config/functions.php';
$title = 'About us';
include_once 'admin/config/Crud.php';
include_once 'includes/header.php';
$crud = new Crud();


?>

<body>

    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php
    include_once 'includes/navbar.php';
    ?>
    <!-- header end -->


    <!-- banner section start -->
    <?php
    $result = $crud->getData('about_us', 'id=1', '', '');
    $bannerData = $result[0];
    $bannerData = json_decode($bannerData['banner_section'], true);

    ?>
    <div class="about-banner-section">
        <div class="container">
            <div class="banner-text-wapper">
                <h2><?php echo $bannerData['heading'] ?></h2>
                <p><?php echo $bannerData['description'] ?></p>
                <div class="banber-lorem-btn">
                    <a href="<?php echo $bannerData['button_url'] ?>"><?php echo $bannerData['button_name'] ?></a>
                </div>
            </div>
        </div>
    </div>



    <!-- banner section end -->


    <?php

    $services = $crud->getData('services_page', 'id=1', '', '')[0];

    $services_data = $services['banner_section'] ?? '{}';
    // Decode JSON data
    $services_data_json = json_decode($services_data, true) ?? [];

    ?>
    <!-- Service section start -->

    <div class="about-miles-esther-section">
        <div class="container">
            <div class="all-us-title-wapper">

                <?php
                $about_data = $crud->getData('about_us', "id=1", '', '')[0];
                $service_data = json_decode($about_data['service_section'], true) ?? [];

                ?>
                <h5><?php echo $service_data['title'] ?></h5>
                <h4><?php echo $service_data['heading'] ?></h4>
                <p><?php echo $service_data['description'] ?></p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="miles-card-wapper bg-color-FFF5EB">
                        <div class="miles-card-img">
                            <img src="<?php echo $GLOBALS['site_url'] . $services_data_json['blocks'][0]['img']; ?>" alt="">
                        </div>
                        <div class="miles-card-inner">
                            <h6><?php echo $services_data_json['blocks'][0]['title'] ?></h6>
                            <p><?php echo $services_data_json['blocks'][0]['description'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="miles-card-wapper bg-color-FFEDEB">
                        <div class="miles-card-img">
                            <img src="<?php echo $GLOBALS['site_url'] . $services_data_json['blocks'][1]['img']; ?>" alt="">
                        </div>
                        <div class="miles-card-inner">
                            <h6><?php echo $services_data_json['blocks'][0]['title'] ?></h6>
                            <p><?php echo $services_data_json['blocks'][0]['description'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="miles-card-wapper bg-color-F2F8F3">
                        <div class="miles-card-img">
                            <img src="<?php echo $GLOBALS['site_url'] . $services_data_json['blocks'][2]['img']; ?>" alt="">
                        </div>
                        <div class="miles-card-inner">
                            <h6><?php echo $services_data_json['blocks'][0]['title'] ?></h6>
                            <p><?php echo $services_data_json['blocks'][0]['description'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service section end -->



    <!-- Our Teams section start -->
    <?php
    $team_members = $crud->getData('our_team', '', '', '');

    $about_data = $crud->getData('about_us', "id=1", '', '')[0];
    $our_team_section = json_decode($about_data['team_section'], true) ?? [];
    ?>
    <div class="our-teams-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5><?php echo $our_team_section['title'] ?></h5>
                <h4><?php echo $our_team_section['heading'] ?></h4>
                <p><?php echo $our_team_section['description'] ?> </p>
            </div>

            <div class="our-teams-row">
                <?php foreach ($team_members as $member) : ?>
                    <div class="our-teams-card-wapper">
                        <div class="our-teams-card-img">
                            <img src="<?php echo $GLOBALS['site_url'] . $member['img'] ?>" alt="">
                        </div>
                        <div class="our-teams-card-inner">
                            <div class="our-teams-card-inner-left">
                                <h4><?php echo $member['name'] ?></h4>
                                <span><?php echo $member['role'] ?></span>
                            </div>
                            <div class="our-teams-view-btn">
                                <a href="<?php echo $member['url'] ?>">View</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Our Teams section end -->

    <?php $blogs = $crud->getData('blogs', "", '', '');

    $about_data = $crud->getData('about_us', "id=1", '', '')[0];
    $blog_section_data = json_decode($about_data['blog_section'], true) ?? []; ?>



    <!-- blog section satrt -->

    <div class="about-blog-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5><?php echo $blog_section_data['title'] ?></h5>
                <h4><?php echo $blog_section_data['heading'] ?></h4>
                <p><?php echo $blog_section_data['description'] ?> </p>
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



    <!-- Partner section start -->

    <!-- add  in footer  -->
    <!-- Partner section end -->



    <!-- footer section start -->


    <?php
    include_once 'includes/footer.php';
    ?>


    <!-- footer section end -->
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/about_us/js/main.js"></script>

    <!-- footer section end -->

</body>

</html>