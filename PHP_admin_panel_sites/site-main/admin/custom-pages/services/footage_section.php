<?php

error_reporting(E_ALL);

session_start();
include_once '../../config/Dbconfig.php';
include_once '../../config/Crud.php';
include_once '../../config/functions.php';
include_once '../../auth/authentication.php';
include_once '../../header.php';
$crud = new Crud();

$services_page_data = $crud->getData('services_page', "id=1", '', '')[0];
// echo "<pre>";
// print_r($services_page_data);
// echo "</pre>";

$services_page_data_json = json_decode($services_page_data['footage_section'], true) ?? [];
// echo "<pre>";
// print_r($services_page_data_json);
// echo "</pre>";



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_data'])) {

    $insert_id = intval($_POST['insert_id']);
    $footage_title = $crud->escape_string($_POST['footage_title']);
    $heading = $crud->escape_string($_POST['heading']);
    $description = $crud->escape_string($_POST['description']);
    $video_url = $_POST['video_url'];
    $video_image = $_FILES['video_image'];
    $existingFilePaths = $services_page_data_json['video_image'];



    $uploadsDir = 'uploads/service/';
    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;

    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }
    $filePaths = '';
    if ($video_image['error'] == 0) {
        // upload new file
        $uploadResult = uploadFile($video_image, $absoluteUploadsDir);
        if ($uploadResult['status']) {
            // update file path only if it has changed
            if ($existingFilePaths !== '/' . $uploadsDir . basename($video_image["name"])) {
                $filePaths = '/' . $uploadsDir . basename($video_image["name"]);
            }
        } else {
            $_SESSION['status_error']="Error: " . $uploadResult['message'];
        }
    } else {
        $filePaths = $existingFilePaths;
    }

    $footageUpdated = array(
        'footage_title' => $footage_title,
        'heading' => $heading,
        'description' => $description,
        'video_url' => $video_url,
        'video_image' => $filePaths,
    );

    $jsonfootageSectionData = json_encode($footageUpdated);
    $data = array('footage_section' => $jsonfootageSectionData);

    // var_dump($crud);
    if ($insert_id == 'insert') {

        $existing = $crud->getData('services_page', "id=1", '', '')[0];
        // $existing = $crud->getbyid('services_page', 1);
        if ($existing) {
            $result = $crud->update('services_page', $data, ['id' => 1]);
        } else {
            $result = $crud->insert('services_page', $data);
        }
    } else {
        $result = $crud->update('services_page', $data, array('id' => 1));
    }

    if ($result === true) {
        $_SESSION['status'] = "Data saved successfully";
    } else {
        $_SESSION['status_error'] = "Data save failed";
    }
    header('Location: ' . $GLOBALS['site_url'] . 'services/footage_section');
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
            <?php
            echo alert_message();
            ?>

                <div class="card-header">Contact Heading Details</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>services/footage_section" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                        <input type="hidden" name="insert_id" value="<?= $services_page_data_json['id'] ?? 'insert'; ?>">

                        <div class="form-group">
                            <label for="footage_title">Footage Section Title</label>
                            <input type="text" class="form-control" id="footage_title" name="footage_title" placeholder="Enter footage_title Text (Contact Us)" value="<?= htmlspecialchars($services_page_data_json['footage_title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="heading">Heading</label>
                            <input type="text" class="form-control" id="heading" name="heading" placeholder="Enter Heading Text " value="<?= htmlspecialchars($services_page_data_json['heading'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Description"><?= htmlspecialchars($services_page_data_json['description'] ?? ''); ?></textarea>
                        </div>
 

                        <div class="form-group">
                            <label for="video_url">Video:</label>
                            <input type="url" class="form-control" id="video_url" name="video_url" placeholder="Enter Video URL " value="<?= htmlspecialchars($services_page_data_json['video_url'] ?? ''); ?>">
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="video_image">Select Video Image :</label>
                            <?php
                            $video_image = $services_page_data_json['video_image'] ?? '';
                            ?>
                            <img id="video_image_preview" src="<?= !empty($video_image) ? $GLOBALS['site_url'] . $video_image : ''; ?>" alt="Current Image" style="max-width: 100px; max-height: 100px; margin-bottom: 10px; <?= empty($video_image) ? 'display: none;' : ''; ?>"><br>
                            <input type="file" name="video_image" value="<?php echo $video_image; ?>" id="video_image" onchange="previewImage('video_image', 'video_image_preview')">
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_data" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
          

        </div>
        <!-- footer start -->
        <?php
        include '../../footer.php';
        ?>
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
