<?php
$title = "Pricing";
include_once 'admin/config/functions.php';
include_once 'admin/config/Crud.php';
include_once 'includes/header.php';

$crud = new Crud();
$page_data = $crud->getData('pricing_page', "id=1", '', '')[0];

$pricing_plan = $crud->getData('pricing', '', '', '');
// echo '<pre>';
// var_dump($pricing_plan);
// exit();


?>

<body>

    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php
    include_once 'includes/navbar.php';
    ?>
    <!-- header end -->

    <div class="pricing-bg-shap">

        <!-- pricing page section satrt -->
        <div class="pricing-p-section">
            <div class="container w-1000">
                <div class="all-us-title-wapper">
                    <h5><?php echo $page_data['title'] ?></h5>
                    <h4><?php echo $page_data['heading'] ?></h4>
                    <p><?php echo $page_data['description'] ?> </p>
                </div>

                <div class="pricing-p-inner-wapper">
                    <?php foreach ($pricing_plan as $plan) : ?>
                        <div class="pricing-p-inner-item">
                            <div class="pricing-p-inner-item-left">
                                <div class="pricing-p-inner-item-left-card">
                                    <div class="pricing-p-plan-name">
                                        <h4><?php echo $plan['plan_name'] ?></h4>
                                        <h5>Save <?php echo $plan['saving'] ?>%</h5>
                                    </div>
                                    <div class="pricing-p-plan-price">
                                        <h2><span><?php echo $page_data['currency_type'] ?></span><?php echo $plan['price'] ?></h2>
                                        <h6><?php echo $page_data['time_period'] ?></h6>
                                    </div>
                                    <div class="lorem-btn">
                                        <a href="<?php echo $plan['button_link'] ?>"><?php echo $page_data['button_text'] ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="pricing-p-inner-item-right">
                                <div class="price-right-ul">
                                    <ul>
                                        <?php
                                        $specification = json_decode($plan['specification'], true);;
                                        foreach ($specification['on_service'] as $spec) :
                                        ?>

                                            <li class="check-red"><!-- <li class="check-col"> -->
                                                   
                                                <p><?php echo $spec ?></p><span><i class="fas fa-check"></i></span>
                                            </li>
                                            <?php //$count++ ?>
                                        <?php endforeach;


                                        foreach ($specification['off_service'] as $spec) :
                                        ?>
                                            <li class="text-check min">
                                                <p><?php echo $spec ?></p><span><i class="fas fa-minus"></i></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- pricing page section end -->



        <!-- Partner section start -->

        <!-- already add in footer section -->

        <!-- Partner section end -->

    </div>


    <!-- footer section start -->

    <?php
    include_once("includes/footer.php");
    ?>
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/pricing/js/main.js"></script>
    <!-- footer section end -->

</body>

</html>