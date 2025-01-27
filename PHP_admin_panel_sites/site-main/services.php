<?php
$title = "Services";
include_once 'admin/config/functions.php';
include_once 'admin/config/Crud.php';
include_once 'includes/header.php';
$crud = new Crud();


$services_page_data = $crud->getData('services_page', 'id=1', '', '')[0];

?>

<body>

    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php
    include_once 'includes/navbar.php';
    ?>
    <!-- header end -->

    <!-- Service section start -->
    <?php

    $banner_section_data = $services_page_data['banner_section'] ?? '{}';
    // Decode JSON data
    $banner_section_data_json = json_decode($banner_section_data, true) ?? [];

    ?>
    <div class="about-miles-esther-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5><?= $banner_section_data_json['service_title']; ?></h5>
                <h4><?= $banner_section_data_json['heading']; ?></h4>
                <p><?= $banner_section_data_json['description']; ?> </p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="miles-card-wapper bg-color-FFF5EB">
                        <div class="miles-card-img">
                            <img src="<?= $main_site_url . $banner_section_data_json['blocks'][0]['img']; ?>" alt="">
                        </div>
                        <div class="miles-card-inner">
                            <h6><?= $banner_section_data_json['blocks'][0]['title']; ?></h6>
                            <p><?= $banner_section_data_json['blocks'][0]['description']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="miles-card-wapper bg-color-FFEDEB">
                        <div class="miles-card-img">
                            <img src="<?= $main_site_url . $banner_section_data_json['blocks'][1]['img']; ?>" alt="">
                        </div>
                        <div class="miles-card-inner">
                            <h6><?= $banner_section_data_json['blocks'][1]['title']; ?></h6>
                            <p><?= $banner_section_data_json['blocks'][1]['description']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="miles-card-wapper bg-color-F2F8F3">
                        <div class="miles-card-img">
                            <img src="<?= $main_site_url . $banner_section_data_json['blocks'][2]['img']; ?>" alt="">
                        </div>
                        <div class="miles-card-inner">
                            <h6><?= $banner_section_data_json['blocks'][2]['title']; ?></h6>
                            <p><?= $banner_section_data_json['blocks'][2]['description']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service section end -->




    <!-- Price section satrt -->



    <!-- Price section satrt -->
    <?php


    $services_page_data = $crud->getData('services_page', 'id=1', '', '')[0];
    // Retrieve pricing data
    $pricing_result = $crud->getData('home', "id=1", '', '');
    $pricing_data = json_decode($pricing_result[0]['pricing_tab'], true);

    // Access individual elements for pricing
    $pricing_heading = $pricing_data['heading'];
    $price_title = $pricing_data['price_title'];
    $pricing_description = $pricing_data['description'];
    $currency_type = $pricing_data['currency_type'];
    $time_interval = $pricing_data['time_interval'];
    $button_text = $pricing_data['button_text'];
    $tabs = $pricing_data['tabs'];
    ?>

    <div class="price-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5><?php echo htmlspecialchars($price_title); ?></h5>
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
                                <a href="<?php echo $GLOBALS['new_site_url']."pricing"?>">Choose Plan</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Price section end -->

    <?php 

    $footage_section_data=$services_page_data['footage_section']??'{}';
    $footage_section_data_json=json_decode($footage_section_data,true)??[];
    
    ?>

    <!-- Our Footage section start -->

    <div class="our-footage-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5><?= $footage_section_data_json['footage_title']?></h5>
                <h4><?= $footage_section_data_json['heading']?></h4>
                <p><?= $footage_section_data_json['description']?> </p>
            </div>

            <div class="our-footage-inner-wapper">
                <a data-fancybox href="<?= $footage_section_data_json['video_url']?>">
                    <div class="h-overlay-slider">
                        <i class="fas fa-play"></i>
                    </div>
                    <img class="img-fluid" src="<?= $main_site_url.$footage_section_data_json['video_image']?>">
                </a>
            </div>
        </div>
    </div>


    <!-- Our Footage section end -->



    <!-- footer section start -->

    <?php
    include_once("includes/footer.php");
    ?>
    
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/services/js/main.js"></script>

    <!-- footer section end -->

</body>

</html>