<?php

error_reporting(E_ALL);

session_start();
include_once '../../config/Dbconfig.php';
include_once '../../config/Crud.php';
include_once '../../config/functions.php';
include_once '../../auth/authentication.php';
include_once '../../header.php';
$crud = new Crud();
$edit = $crud->getbyid('contact_us', 1);
// echo "<pre>";
// print_r($edit);
// echo "</pre>";


$edit = $crud->getbyid('contact_us', 1);
$contact_json_data1 = json_decode($edit['data']['section_heading'], true);
// echo "<pre>";
// print_r($contact_json_data1);
// echo "</pre>";


$contact_page_data = $crud->getData('contact_us', "id=1", '', '')[0];
// echo "<pre>";
// print_r($contact_page_data);
// echo "</pre>";



$contact_json_data = json_decode($contact_page_data['section_heading'], true) ?? [];
// echo "<pre>";
// print_r($contact_json_data);
// echo "</pre>";



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_data'])) {

    $heading = $crud->escape_string($_POST['heading']);
    $title = $crud->escape_string($_POST['title']);
    $description = $crud->escape_string($_POST['description']);
    $featured_image = $_FILES['featured_image'];
    $insert_id = intval($_POST['insert_id']);
    $existingFilePaths = $contact_json_data['img'];


    $uploadsDir = 'uploads/contact_us/';
    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;

    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }
    $filePaths = '';
    if ($featured_image['error'] == 0) {
        // upload new file
        $uploadResult = uploadFile($featured_image, $absoluteUploadsDir);
        if ($uploadResult['status']) {
            // update file path only if it has changed
            if ($existingFilePaths !== '/' . $uploadsDir . basename($featured_image["name"])) {
                $filePaths = '/' . $uploadsDir . basename($featured_image["name"]);
            }
        } else {
            $_SESSION['status_error'] = "Error: " . $uploadResult['message'];
        }
    } else {
        $filePaths = $existingFilePaths;
    }

    $contactHeadingUpdated = array(
        'heading' => $heading,
        'title' => $title,
        'description' => $description,
        'img' => $filePaths,
    );

    $jsonContactHeadingData = json_encode($contactHeadingUpdated);
    $data = array('section_heading' => $jsonContactHeadingData);

    // var_dump($crud);
    if ($insert_id == 'insert') {

        $existing = $crud->getData('contact_us', "id=1", '', '')[0];
        // $existing = $crud->getbyid('contact_us', 1);
        if ($existing) {
            $result = $crud->update('contact_us', $data, ['id' => 1]);
        } else {
            $result = $crud->insert('contact_us', $data);
        }
    } else {
        $result = $crud->update('contact_us', $data, array('id' => 1));
    }

    if ($result === true) {
        $_SESSION['status'] = "Data saved successfully";
    } else {
        $_SESSION['status_status'] = "Data save failed";
    }
    header('Location: ' . $GLOBALS['site_url'] . 'contact_us/section_headings');
    exit();
}


?>

<body>
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center" style="height: 0px;">
            <img class="animation__shake" src="<?php echo $GLOBALS['new_site_url']; ?>assets/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60" style="display: none;">
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
                <?php
                echo alert_message();
                ?>
                <div class="card-header">Contact Heading Details</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>contact_us/section_headings" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                        <input type="hidden" name="insert_id" value="<?= $contact_page_data['id'] ?? 'insert'; ?>">

                        <div class="form-group">
                            <label for="heading">Heading</label>
                            <input type="text" class="form-control" id="heading" name="heading" placeholder="Enter Heading Text (Contact Us)" value="<?= htmlspecialchars($contact_json_data['heading'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="<?= htmlspecialchars($contact_json_data['title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Description"><?= htmlspecialchars($contact_json_data['description'] ?? ''); ?></textarea>
                        </div>
                        <hr>
                        <div class="col-md-6 mb-3">
                            <label for="featured_image">Select Form Featured image :</label>
                            <?php
                            $featured_image = $contact_json_data['img'] ?? '';
                            ?>
                            <img id="featured_image_preview" src="<?= !empty($featured_image) ? $GLOBALS['site_url'] . $featured_image : ''; ?>" alt="Current Image" style="max-width: 100px; max-height: 100px; margin-bottom: 10px; <?= empty($featured_image) ? 'display: none;' : ''; ?>"><br>
                            <input type="file" name="featured_image" value="<?php echo $featured_image; ?>" id="featured_image" onchange="previewImage('featured_image', 'featured_image_preview')">
                        </div>

                        <div class="card-footer">
                            <button type="submit" name="save_data" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <?php
        include '../../footer.php';
        ?>
    </div>
    <!-- footer end -->
    <script>
        function previewImage(inputId, imgId) {
            const input = document.getElementById(inputId);
            const img = document.getElementById(imgId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    img.style.display = "block";
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                img.src = "";
                img.style.display = "none";
            }
        }
    </script>
</body>

</html>