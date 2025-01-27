<?php
session_start();
include_once '../../config/Dbconfig.php';
include_once '../../config/Crud.php';
include_once '../../config/functions.php';
include_once '../../auth/authentication.php';
include_once '../../header.php';

$crud = new Crud();
$edit = $crud->getbyid('home', 1);

// Decode JSON data
$pricing_data = json_decode($edit['data']['pricing_tab'], true);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_data'])) {
    $insert_id = $crud->escape_string($_POST['insert_id']);
    $price_title = $crud->escape_string($_POST['price_title']);
    $heading = $crud->escape_string($_POST['heading']);
    $description = $crud->escape_string($_POST['description']);
    $button_text = $crud->escape_string($_POST['button_text']);

    $pricing_dataUpdated = array(
        'price_title' => $price_title,
        'heading' => $heading,
        'description' => $description,
        'button_text' => $button_text,
    );

    $jsonpricing_data = json_encode($pricing_dataUpdated);
    $data = array('pricing_tab' => $jsonpricing_data);

    if ($insert_id == 'insert') {
        $existing = $crud->getbyid('home', 1);
        if ($existing['status'] == 200) {
            $result = $crud->update('home', $data, ['id' => 1]);
        } else {
            $result = $crud->insert('home', $data);
        }
    } else {
        $result = $crud->update('home', $data, ['id' => $insert_id]);
    }

    if ($result === true) {
        $_SESSION['status'] = "Data saved successfully";
    } else {
        $_SESSION['status_error'] = "Data save failed";
    }

    header('Location: ' . $GLOBALS['site_url'] . 'home/pricing_tab');
    exit();
}
?>

<body>
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center" style="height: 0px;">
            <img class="animation__shake" src="<?php echo $GLOBALS['site_url']; ?>assets/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60" style="display: none;">
        </div>

        <!-- header start -->
        <?php include_once '../../navbar.php'; ?>
        <!-- header end -->

        <!-- sidebar start -->
        <?php include '../../sidebar.php'; ?>
        <!-- sidebar end -->
        <div class="content-wrapper" style="min-height: 107px;">

            <div class="card card-primary card-body">
                <?php
                echo alert_message();
                ?>

                <div class="card-header">Pricing Tab Section</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>home/pricing_tab" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                        <input type="hidden" name="insert_id" value="<?= $edit['id'] ?? 'insert'; ?>">

                        <div class="form-group">
                            <label for="price_title">Price Section Title</label>
                            <input type="text" class="form-control" id="price_title" name="price_title" placeholder="Enter Price Section Title" value="<?= htmlspecialchars($pricing_data['price_title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="heading">Heading</label>
                            <input type="text" class="form-control" id="heading" name="heading" placeholder="Enter Main Heading Title" value="<?= htmlspecialchars($pricing_data['heading'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Main Description"><?= htmlspecialchars($pricing_data['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4 mb-3">
                                <label for="button_text">Button Text</label>
                                <input type="text" class="form-control" id="button_text" name="button_text" placeholder="Enter Button Text" value="<?= htmlspecialchars($pricing_data['button_text'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_data" class="btn btn-primary">Save Data</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>


        <!-- footer start -->
        <?php
        include '../../footer.php';
        ?>
    </div>
    <!-- footer end -->
</body>

</html>