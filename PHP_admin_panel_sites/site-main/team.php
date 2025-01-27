<?php
include_once 'admin/config/functions.php';
include_once 'admin/config/Crud.php';
$title = 'Our Team';

include_once 'includes/header.php';


$our_team_page = $crud->getData('our_team_page', "id=1", '', '')[0];
$heading_section = json_decode($our_team_page['our_team_heading_section'], true);
$testimonial_section = json_decode($our_team_page['testimonial_section'], true);
// echo '<pre>';
//  var_dump($our_team_page);

$team_members = $crud->getData('our_team', '', '', '');

?>

<body>

    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php
    include_once 'includes/navbar.php';
    ?>
    <!-- header end -->


    <div class="our-team-bg-shap">

        <!-- teams section start -->

        <div class="team-section-wapper">
            <div class="container">
                <div class="all-us-title-wapper">
                    <h5><?php echo $heading_section['title'] ?></h5>
                    <h4><?php echo $heading_section['heading'] ?></h4>
                    <p><?php echo $heading_section['description'] ?></p>
                </div>
                <div class="team-row-wapper">

                    <?php foreach ($team_members as $member) : ?>
                        <div class="team-card-wapper">
                            <div class="team-card-img">
                                <img src="<?php echo $GLOBALS['site_url']. $member['img'] ?>" alt="">
                            </div>
                            <div class="team-card-inner">
                                <div class="our-teams-card-inner-left">
                                    <h4><?php echo $member['name'] ?></h4>
                                    <span><?php echo $member['role'] ?></span>
                                </div>
                                <div class="our-teams-view-btn">
                                    <a href="<?php echo $member['url'] ?>"><?php echo $heading_section['button_text'] ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- teams section end -->


        <?php $testimonials = $crud->getData('testimonials', '', '', ''); ?>

        <!-- Testimony section satrt -->

        <div class="testimonial-section">
            <div class="container">
                <div class="all-us-title-wapper">
                    <h5><?php echo $testimonial_section['title'] ?></h5>
                    <h4><?php echo $testimonial_section['heading'] ?></h4>
                    <p><?php echo $testimonial_section['description'] ?></p>
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

        <!-- Testimony section end -->


    </div>



    <!-- Partner section start -->

    <!-- included in footer section -->

    <!-- Partner section end -->






    <!-- footer section start -->


    <?php

    include_once('includes/footer.php');
    ?>
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/team/js/main.js"></script>
    <!-- footer section end -->

</body>

</html>