<?php

error_reporting(E_ALL);

session_start();
include_once '../../config/Dbconfig.php';
include_once '../../config/Crud.php';
include_once '../../config/functions.php';
include_once '../../auth/authentication.php';
include_once '../../header.php';
$crud = new Crud();

$contact_page_data = $crud->getData('contact_us', "id=1", '', '')[0];
$form_field_detail = $contact_page_data['form_info'] ?? '{}';
$form_field_detail_json = json_decode($form_field_detail, true) ?? [];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['save_filed_data'])) {
        $form_field_detail_json['name_filed'] = $_POST['name_filed'] ?? '';
        $form_field_detail_json['name_filed_placeholder'] = $_POST['name_filed_placeholder'] ?? '';
        $form_field_detail_json['email_field'] = $_POST['email_field'] ?? '';
        $form_field_detail_json['email_field_placeholder'] = $_POST['email_field_placeholder'] ?? '';
        $form_field_detail_json['message_field'] = $_POST['message_field'] ?? '';
        $form_field_detail_json['message_field_placeholder'] = $_POST['message_field_placeholder'] ?? '';
        $form_field_detail_json['services_title'] = $_POST['services_title'] ?? '';
        $form_field_detail_json['send_btn'] = $_POST['send_btn'] ?? '';

        $form_field_detail = json_encode($form_field_detail_json);
        $data = ['form_info' => $form_field_detail];

        $result = $crud->update('contact_us', $data, ['id' => 1]);
        if ($result === true) {
            $_SESSION['status'] = "From Fields updated successfully";
        } else {
            $_SESSION['status_error'] = "From Fields update failed";
        }
    } elseif (isset($_POST['save_service'])) {
        $service = $_POST['service'] ?? '';
        if ($service) {
            $services = [
                'service_name' => $service,
            ];
        }

        // array_push($myArray, "element1", "element2", "element3");
        $form_field_detail_json['services'][] = $services;

        $form_field_detail = json_encode($form_field_detail_json);
        $data = ['form_info' => $form_field_detail];
        $result = $crud->update('contact_us', $data, ['id' => 1]);

        if ($result === true) {
            $_SESSION['status'] = "Service added successfully";
        } else {
            $_SESSION['status_error'] = "Failed to add Service";
        }

        // header('Location: ' . $_SERVER['PHP_SELF']);
        // exit();
    } elseif (isset($_POST['delete_service'])) {
        $delete_index = isset($_POST['delete_index']) ? intval($_POST['delete_index']) : -1;
        if ($delete_index >= 0 && isset($form_field_detail_json['services'][$delete_index])) {
            array_splice($form_field_detail_json['services'], $delete_index, 1);

            $form_field_detail = json_encode($form_field_detail_json);
            $data = ['form_info' => $form_field_detail];
            $result = $crud->update('contact_us', $data, ['id' => 1]);
            if ($result === true) {
                $_SESSION['status'] = "Service deleted successfully";
            } else {
                $_SESSION['status_error'] = "Failed to delete Service";
            }
        }
    }
    header('Location: ' . $GLOBALS['site_url'] . 'contact_us/form_info');
    exit();
}


?>

<body>
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center" style="height: 0px;">
            <img class="animation__shake" src="<?php echo $GLOBALS['site_url']; ?>assets/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60" style="display: none;">
        </div>

        <!-- header start -->
        <?php
        include_once '../../navbar.php';
        ?>
        <!-- header end -->

        <!-- sidebar start -->
        <?php
        include '../../sidebar.php';
        ?>
        <!-- sidebar end -->


        <div class="content-wrapper" style="min-height: 107px;">
            <div class="card card-primary card-body">
                <?php echo alert_message(); ?>

                <div class="card-header">Contact From Field Details</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>contact_us/form_info" method="post">
                    <div class="card card-primary card-body">
                        <input type="hidden" name="insert_id" value="<?= $contact_page_data['id'] ?? 'insert'; ?>">

                        <div class="form-group">
                            <label for="name_filed">Name Field Text:</label>
                            <input type="text" class="form-control" id="name_filed" name="name_filed" placeholder="Enter Name Field Text (e.g.Name)" value="<?= htmlspecialchars($form_field_detail_json['name_filed'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="name_filed_placeholder">Name Field Placeholder:</label>
                            <input type="text" class="form-control" id="name_filed_placeholder" name="name_filed_placeholder" placeholder="Enter Name Field Placeholder (e.g.Your Name)" value="<?= htmlspecialchars($form_field_detail_json['name_filed_placeholder'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="email_field ">Email Field Text:</label>
                            <input type="text" class="form-control" id="email_field" name="email_field" placeholder="Enter Email Filed Text (e.g.Email)" value="<?= htmlspecialchars($form_field_detail_json['email_field'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email_field_placeholder">Email Field Placeholder:</label>
                            <input type="text" class="form-control" id="email_field_placeholder" name="email_field_placeholder" placeholder="Enter Email Filed Placeholder (e.g.Your Email)" value="<?= htmlspecialchars($form_field_detail_json['email_field_placeholder'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="message_field">Message Field Text:</label>
                            <input type="text" class="form-control" id="message_field" name="message_field" placeholder="Enter Message Filed Text (e.g.Message)" value="<?= htmlspecialchars($form_field_detail_json['message_field'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="message_field_placeholder">Message Field Placeholder:</label>
                            <input type="text" class="form-control" id="message_field_placeholder" name="message_field_placeholder" placeholder="Enter Message Filed Placeholder (e.g.Type Here)" value="<?= htmlspecialchars($form_field_detail_json['message_field_placeholder'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="send_btn">Send Button Name:</label>
                            <input type="text" class="form-control" id="send_btn" name="send_btn" placeholder="Send Button Name " value="<?= htmlspecialchars($form_field_detail_json['send_btn'] ?? ''); ?>">
                        </div>
                        <!-- <div class="form-group col-md-6 mb-3">
                        <label for="send_url">Send Button Url:</label>
                        <input type="text" class="form-control" id="send_url" name="send_url" placeholder="Send Button Url " value="<? //= htmlspecialchars($form_field_detail_json['send_url'] ?? ''); 
                                                                                                                                    ?>">
                    </div> -->
                        <h5>Services Section</h5>
                        <div class="form-group">
                            <label for="services_title">Heading:</label>
                            <input type="text" class="form-control" id="services_title" name="services_title" placeholder="Service Section Heading" value="<?= htmlspecialchars($form_field_detail_json['services_title'] ?? ''); ?>">
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_filed_data" class="btn btn-primary">Update Fields</button>
                        </div>
                    </div>
                </form>

                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>contact_us/form_info" method="post">
                    <div class="card card-primary card-body">
                        <div class="form-group col-md-6 mb-3">
                            <label for="service">Services:</label>
                            <input type="text" required class="form-control" id="service" name="service">
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_service" class="btn btn-primary">Save Service</button>
                        </div>
                    </div>
                </form>
                <br>


               
                <h3>Existing Services</h3>
                <hr>
                <ul style="list-style-type: decimal;">
                    <?php if (isset($form_field_detail_json['services']) && is_array($form_field_detail_json['services'])) { ?>
                        <?php foreach ($form_field_detail_json['services'] as $index => $service) { ?>
                            <li>
                                <?php echo htmlspecialchars($service['service_name']); ?>
                                <form action="<?php echo $GLOBALS['site_url'];?>contact_us/form_info" method="post" style="display:inline;">
                                    <input type="hidden" name="delete_index" value="<?php echo $index; ?>">
                                    <button type="submit" name="delete_service" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </li>
                            <br>
                        <?php } ?>
                    <?php } ?>
                </ul>
                <br>
                <hr>

            </div>
            <?php
            echo alert_message();
            ?>

        </div>
        <!-- footer start -->
        <?php
        include '../../footer.php';
        ?>
        <!-- footer end -->
</body>

</html>