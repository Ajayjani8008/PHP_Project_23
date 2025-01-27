
<?php
$home_data = $crud->getData('home', "id=1", '', '')[0];
$home_json_data = json_decode($home_data['partners_logo'], true) ?? [];
$footer_data = $crud->getData('home', "id=1", '', '')[0];
$footer_json = $footer_data['footer_section'] ?? '{}';
$footer_json_data = json_decode($footer_json, true) ?? [];

?>
<!-- Partner section start -->
<div class="partner-section">
    <div class="container w-1557">
        <div class="partner-wapper">
            <div class="partner-inner">
                <?php foreach ($home_json_data as $partner_image) : ?>
                    <div class="partner-card">
                        <img src="<?= $GLOBALS['site_url'] . $partner_image ?>" alt="Partner Logo">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<!-- Partner section end -->

<div class="footer-section">
    <div class="container">
        <div class="top-footer">
            <!-- Follow Us Section -->
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="footer-follow-us">
                        <h3>Follow Us</h3>
                        <ul>
                            <?php foreach ($footer_json_data['follow_us']['social_media'] ?? [] as $social_media) : ?>
                                <li><a href="<?= $social_media['url'] ?>"><img src="<?= $GLOBALS['site_url']  . $social_media['icon'] ?>" alt="<?= $social_media['platform'] ?>"></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <!-- Contact Us Section -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="footer-contact-us">
                        <h3>Contact Us</h3>
                        <ul>
                            <li>
                                <p><?= $footer_json_data['footer_contact_us']['address'] ?? '' ?></p>
                            </li>
                            <li><a href="mailto:<?= $footer_json_data['footer_contact_us']['email'] ?? '' ?>"><?= $footer_json_data['footer_contact_us']['email'] ?? '' ?></a></li>
                            <li><a href="tel:<?= $footer_json_data['footer_contact_us']['phone'] ?? '' ?>"><?= $footer_json_data['footer_contact_us']['phone'] ?? '' ?></a></li>
                        </ul>
                    </div>
                </div>
                <!-- Useful Links Section -->
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="footer-usefull-link">
                        <h3>Useful Links</h3>
                        <div class="footer-usefull-link-ul-wapper">
                            <ul>
                                <?php foreach ($footer_json_data['footer_useful_links']['links'] ?? [] as $useful_link) : ?>
                                    <li><a href="<?= $useful_link['url'] ?>"><?= $useful_link['title'] ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container custom-border">
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