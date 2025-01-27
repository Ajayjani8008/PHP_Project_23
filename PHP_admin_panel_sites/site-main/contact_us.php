<?php
session_start();
$title = 'Contact Us';
include_once 'admin/config/Crud.php';
include_once 'admin/config/functions.php';
include_once 'includes/header.php';

// Fetch the current data
$contact_page_data = $crud->getData('contact_us', "id=1", '', '')[0];
$contact_json_data = json_decode($contact_page_data['section_heading'], true) ?? [];

$form_field_detail = $contact_page_data['form_info'] ?? '{}';
$form_field_detail_json = json_decode($form_field_detail, true) ?? [];

?>

<body>

    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php include_once 'includes/navbar.php'; ?>
    <!-- header end -->

    <!-- contact us section start -->
    <div class="contact-us-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5><?php echo $contact_json_data['heading']; ?></h5>
                <h4><?php echo $contact_json_data['title']; ?></h4>
                <p><?php echo $contact_json_data['description']; ?></p>
            </div>
            <br><br><div style="text-align: center;"><?php alert_message() ?></div>
            <div class="contact-us-row">
            
                <div class="contact-us-left">
                    <img src="<?php echo $GLOBALS['site_url'] . $contact_json_data['img']; ?>" alt="">
                </div>
                <div class="contact-us-right">
                    <form action="<?php echo $GLOBALS['new_site_url'];?>form_submission" method="post" id="contactForm">
                        <div class="form-group">
                            <label for="Name"><?php echo $form_field_detail_json['name_filed'] ?></label>
                            <div class="input-text">
                                <span><i class="fas fa-user"></i></span>
                                <input type="text" name="name" required class="form-control" placeholder="<?php echo $form_field_detail_json['name_filed_placeholder'] ?>" id="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email"><?php echo $form_field_detail_json['email_field'] ?></label>
                            <div class="input-text">
                                <span><i class="fas fa-envelope-open"></i></span>
                                <input type="email" required name="email" class="form-control" placeholder="<?php echo $form_field_detail_json['email_field_placeholder'] ?>" id="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message"><?php echo $form_field_detail_json['message_field'] ?></label>
                            <textarea name="message" required class="form-control" rows="4" id="message" placeholder="<?php echo $form_field_detail_json['message_field_placeholder'] ?>"></textarea>
                        </div>
                        <input type="hidden" id="service" name="service" value="">
                    </form>
                </div>
            </div>
            <div class="contact-us-service">
                <h4><?php echo $form_field_detail_json['services_title'] ?></h4>
                <ul>
                    <?php foreach ($form_field_detail_json['services'] as $service) : ?>
                        <li><a href="javascript:void(0)" onclick="selectService('<?php echo $service['service_name']; ?>')"><?php echo $service['service_name']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <div class="send-btn">
                    <a href="javascript:void(0)" onclick="submitContactForm();"><?php echo $form_field_detail_json['send_btn'] ?></a>
                </div>
                
            </div>
            <br>
            
        </div>
    </div>
    <!-- contact us section end -->

    <!-- footer section start -->
    <?php include_once 'includes/footer.php'; ?>
    <script>
        function selectService(service) {
            document.getElementById('service').value = service;
        }

        function submitContactForm() {
            const service = document.getElementById('service').value;
            if (!service) {
                alert('Please select a service.');
                return;
            }
            document.getElementById('contactForm').submit();
        }
    </script>
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/contacts/js/main.js"></script>
    <!-- footer section end -->

</body>

</html>