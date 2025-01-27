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
$bannerData = json_decode($edit['data']['banner_data'], true);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_data'])) {

    $heading = $crud->escape_string($_POST['heading']);
    $description = $crud->escape_string($_POST['description']);
    $button_name = $crud->escape_string($_POST['button_name']);
    $button_url = $crud->escape_string($_POST['button_url']);
    $insert_id = $crud->escape_string($_POST['insert_id']);

    $miles_block1_title = $crud->escape_string($_POST['miles_block1_title']);
    $miles_block1_description = $crud->escape_string($_POST['miles_block1_description']);
    $miles_block2_title = $crud->escape_string($_POST['miles_block2_title']);
    $miles_block2_description = $crud->escape_string($_POST['miles_block2_description']);
    $miles_block3_title = $crud->escape_string($_POST['miles_block3_title']);
    $miles_block3_description = $crud->escape_string($_POST['miles_block3_description']);

    $existingFilePaths = [
        'miles_block1_img' => $bannerData['blocks'][0]['img'] ?? '',
        'miles_block2_img' => $bannerData['blocks'][1]['img'] ?? '',
        'miles_block3_img' => $bannerData['blocks'][2]['img'] ?? '',
        'banner_img' => $bannerData['banner_img'] ?? '',
    ];
    $filesToUpload = [
        'miles_block1_img' => $_FILES["miles_block1_img"],
        'miles_block2_img' => $_FILES["miles_block2_img"],
        'miles_block3_img' => $_FILES["miles_block3_img"],
        'banner_img' => $_FILES['banner_img']
    ];


    $uploadsDir = 'uploads/home/';
    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;

    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }

    $filePaths = [];
    foreach ($filesToUpload as $key => $file) {
        if ($file['error'] == 0) {
            // Upload new file
            $uploadResult = uploadFile($file, $absoluteUploadsDir);
            if ($uploadResult['status']) {
                // Update file path only if it has changed
                if ($existingFilePaths[$key] !== '/' . $uploadsDir . basename($file["name"])) {
                    $filePaths[$key] = '/' . $uploadsDir . basename($file["name"]);
                }
            } else {
                $_SESSION['status_error'] = "Error: " . $uploadResult['message'];
            }
        } else {
            // Handle case where file input was cleared
            // Retain existing file path
            $filePaths[$key] = $existingFilePaths[$key];
        }
    }
    $bannerDataUpdated = array(
        'heading' => $heading,
        'description' => $description,
        'button_name' => $button_name,
        'button_url' => $button_url,
        'banner_img' => $filePaths['banner_img'],
        'blocks' => array(
            array(
                'title' => $miles_block1_title,
                'description' => $miles_block1_description,
                'img' => $filePaths['miles_block1_img']
            ),
            array(
                'title' => $miles_block2_title,
                'description' => $miles_block2_description,
                'img' => $filePaths['miles_block2_img']
            ),
            array(
                'title' => $miles_block3_title,
                'description' => $miles_block3_description,
                'img' => $filePaths['miles_block3_img']
            )
        )
    );

    $jsonBannerData = json_encode($bannerDataUpdated);

    $data = array('banner_data' => $jsonBannerData);

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

    header('Location: ' . $GLOBALS['site_url'] . 'home/banner_area');
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
                <div class="card-header">Banner Section</div>
                <form action="<?php echo $GLOBALS['site_url']; ?>home/banner_area" method="post" enctype="multipart/form-data">

                    <div class="card card-primary card-body">
                        <input type="hidden" name="insert_id" value="<?= $edit['id'] ?? 'insert'; ?>">

                        <div class="form-group">
                            <label for="heading">Heading</label>
                            <input type="text" class="form-control" id="heading" name="heading" placeholder="Enter Heading Text For Banner" value="<?= htmlspecialchars($bannerData['heading'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Banner Description"><?= htmlspecialchars($bannerData['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="banner_img">Select Banner Image:</label>
                            <?php
                            $banner_img = $bannerData['banner_img'] ?? '';
                            ?>
                            <img id="banner_img_preview" src="<?= !empty($banner_img) ? $GLOBALS['site_url'] . $banner_img : ''; ?>" alt="Current Image" style="max-width: 100px; max-height: 100px; margin-bottom: 10px; <?= empty($banner_img) ? 'display: none;' : ''; ?>"><br>
                            <input type="file" name="banner_img" id="banner_img" onchange="previewImage('banner_img', 'banner_img_preview')">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="button_name">Button</label>
                                <input type="text" class="form-control" name="button_name" id="button_name" placeholder="Enter Button Text" value="<?= htmlspecialchars($bannerData['button_name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="button_url">Button(URL)</label>
                                <input type="url" class="form-control" id="button_url" name="button_url" placeholder="Enter Button URL" value="<?= htmlspecialchars($bannerData['button_url'] ?? ''); ?>">
                            </div>
                        </div>
                        <hr>

                        <div class="form-group">
                            <label for="miles_block1_title">Miles Block1 Title</label>
                            <input type="text" class="form-control" id="miles_block1_title" name="miles_block1_title" placeholder="Enter Heading Text For Banner" value="<?= htmlspecialchars($bannerData['blocks'][0]['title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="miles_block1_description">Miles Block1 Description</label>
                            <textarea class="form-control" id="miles_block1_description" name="miles_block1_description" rows="3" placeholder="Enter Banner Description"><?= htmlspecialchars($bannerData['blocks'][0]['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="miles_block1_img">Select icon image Miles Block1 :</label>
                            <?php
                            $miles_block1_img = $bannerData['blocks'][0]['img'] ?? '';
                            ?>
                            <img id="miles_block1_img_preview" src="<?= !empty($miles_block1_img) ? $GLOBALS['site_url'] . $miles_block1_img : ''; ?>" alt="Current Image" style="max-width: 100px; max-height: 100px; margin-bottom: 10px; <?= empty($miles_block1_img) ? 'display: none;' : ''; ?>"><br>
                            <input type="file" name="miles_block1_img" value="<?php echo $miles_block1_img; ?>" id="miles_block1_img" onchange="previewImage('miles_block1_img', 'miles_block1_img_preview')">
                        </div>

                        <div class="form-group">
                            <label for="miles_block2_title">Miles Block2 Title</label>
                            <input type="text" class="form-control" id="miles_block2_title" name="miles_block2_title" placeholder="Enter Heading Text For Banner" value="<?= htmlspecialchars($bannerData['blocks'][1]['title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="miles_block2_description">Miles Block2 Description</label>
                            <textarea class="form-control" id="miles_block2_description" name="miles_block2_description" rows="3" placeholder="Enter Banner Description"><?= htmlspecialchars($bannerData['blocks'][1]['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="miles_block2_img">Select icon image Miles Block2 :</label>
                            <?php
                            $miles_block2_img = $bannerData['blocks'][1]['img'] ?? '';
                            ?>
                            <img id="miles_block2_img_preview" src="<?= !empty($miles_block2_img) ? $GLOBALS['site_url'] . $miles_block2_img : ''; ?>" alt="Current Image" style="max-width: 100px; max-height: 100px; margin-bottom: 10px; <?= empty($miles_block2_img) ? 'display: none;' : ''; ?>"><br>
                            <input type="file" name="miles_block2_img" id="miles_block2_img" onchange="previewImage('miles_block2_img', 'miles_block2_img_preview')">
                        </div>

                        <div class="form-group">
                            <label for="miles_block3_title">Miles Block3 Title</label>
                            <input type="text" class="form-control" id="miles_block3_title" name="miles_block3_title" placeholder="Enter Heading Text For Banner" value="<?= htmlspecialchars($bannerData['blocks'][2]['title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="miles_block3_description">Miles Block3 Description</label>
                            <textarea class="form-control" id="miles_block3_description" name="miles_block3_description" rows="3" placeholder="Enter Banner Description"><?= htmlspecialchars($bannerData['blocks'][2]['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="miles_block3_img">Select icon image Miles Block3 :</label>
                            <?php
                            $miles_block3_img = $bannerData['blocks'][2]['img'] ?? '';
                            ?>
                            <img id="miles_block3_img_preview" src="<?= !empty($miles_block3_img) ? $GLOBALS['site_url'] . $miles_block3_img : ''; ?>" alt="Current Image" style="max-width: 100px; max-height: 100px; margin-bottom: 10px; <?= empty($miles_block3_img) ? 'display: none;' : ''; ?>"><br>
                            <input type="file" name="miles_block3_img" id="miles_block3_img" onchange="previewImage('miles_block3_img', 'miles_block3_img_preview')">
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