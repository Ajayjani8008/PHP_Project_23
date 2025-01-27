<?php

include_once 'admin/config/functions.php';
$title = 'Not Found | Error 404 ';
include_once 'admin/config/Crud.php';
include_once 'includes/header.php';
$page_data = $crud->getData('404_page', "id=1", '', '')[0];
?>

<body>

    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php include_once 'includes/navbar.php' ?>
    <!-- header end -->

    <!-- 404 Page not Found section start -->

    <div class="page-not-found-section">
        <div class="container">
            <div class="page-not-found-text-wapper">
                <h3><?php echo $page_data['404_title']?></h3>
                <h4><?php echo $page_data['heading']?></h4>
                <p><?php echo $page_data['description']?> </p>
                <div class="home-btn">
                    <a href="<?php echo $GLOBALS['new_site_url']?>index"><?php echo $page_data['button_text']?></a>
                </div>
            </div>
        </div>
    </div>

    <!-- 404 Page not Found section end -->

    <!-- footer section start -->
    <?php

    $footer_data = $crud->getData('home', "id=1", '', '')[0];
    $footer_json = $footer_data['footer_section'] ?? '{}';
    $footer_json_data = json_decode($footer_json, true) ?? []; 
    
    ?>


    <div class="footer-section find-page-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="footer-copyright-wapper">
                        <div class="footer-copyright-left-text">
                            <p><?= $footer_json_data['copyright_line'] ?? '' ?></p>
                        </div>
                        <div class="footer-copyright-right">
                            <p>Compatible With:</p>
                            <ul>
                            <?php foreach ($footer_json_data['compatible_divices']['divice'] ?? [] as $device) : ?>
                                <li><a href="<?= $device['url'] ?>"><img src="<?= $GLOBALS['site_url'] . $device['logo'] ?>" alt="Device Logo"></a></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer section end -->
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/404/js/main.js"></script>

</body>

</html>