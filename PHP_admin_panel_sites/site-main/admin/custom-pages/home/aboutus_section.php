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
$aboutus_data = json_decode($edit['data']['aboutus_section'], true);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_data'])) {
    $heading = $crud->escape_string($_POST['heading']);
    $description = $crud->escape_string($_POST['description']);
    $insert_id = $crud->escape_string($_POST['insert_id']);

    $block_titles = [
        $crud->escape_string($_POST['block1_title']),
        $crud->escape_string($_POST['block2_title']),
        $crud->escape_string($_POST['block3_title'])
    ];
    $block_descriptions = [
        $crud->escape_string($_POST['block1_description']),
        $crud->escape_string($_POST['block2_description']),
        $crud->escape_string($_POST['block3_description'])
    ];

    $existingFilePaths = [
        'featured_image' => $aboutus_data['featured_image'] ?? '',
        'block1_img' => $aboutus_data['blocks'][0]['img'] ?? '',
        'block2_img' => $aboutus_data['blocks'][1]['img'] ?? '',
        'block3_img' => $aboutus_data['blocks'][2]['img'] ?? '',
    ];
    $filesToUpload = [
        'featured_image' => $_FILES["featured_image"],
        'block1_img' => $_FILES["block1_img"],
        'block2_img' => $_FILES["block2_img"],
        'block3_img' => $_FILES["block3_img"],
    ];

    $uploadsDir = 'uploads/home/';
    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadsDir;

    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }

    $filePaths = [];
    foreach ($filesToUpload as $key => $file) {
        if ($file['error'] == 0) {
            $uploadResult = uploadFile($file, $absoluteUploadsDir);
            if ($uploadResult['status']) {
                $filePaths[$key] = '/' . $uploadsDir . basename($file["name"]);
            } else {
                echo "Error: " . $uploadResult['message'];
                $filePaths[$key] = $existingFilePaths[$key];
            }
        } else {
            $filePaths[$key] = $existingFilePaths[$key];
        }
    }

    $aboutus_dataUpdated = [
        'heading' => $heading,
        'description' => $description,
        'featured_image' => $filePaths['featured_image'] ?? $existingFilePaths['featured_image'],
        'blocks' => [
            [
                'title' => $block_titles[0],
                'description' => $block_descriptions[0],
                'img' => $filePaths['block1_img'] ?? $existingFilePaths['block1_img']
            ],
            [
                'title' => $block_titles[1],
                'description' => $block_descriptions[1],
                'img' => $filePaths['block2_img'] ?? $existingFilePaths['block2_img']
            ],
            [
                'title' => $block_titles[2],
                'description' => $block_descriptions[2],
                'img' => $filePaths['block3_img'] ?? $existingFilePaths['block3_img']
            ]
        ]
    ];

    $jsonaboutus_data = json_encode($aboutus_dataUpdated);
    $data = ['aboutus_section' => $jsonaboutus_data];

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

    header('Location: ' . $GLOBALS['site_url'] . 'home/aboutus_section');
    exit();
}
?>

<body>
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center" style="height: 0px;">
            <img class="animation__shake" src="<?php echo $GLOBALS['site_url']; ?>assets/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60" style="display: none;">
        </div>

        <?php include_once '../../navbar.php'; ?>
        <?php include '../../sidebar.php'; ?>

        <?php if (isset($_SESSION['status_authorized'])) : ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['status_authorized']; ?>
            </div>
            <?php unset($_SESSION['status_authorized']); ?>
        <?php endif; ?>



        <div class="content-wrapper" style="min-height: 107px;">
            <div class="card card-primary card-body">
                <br>
                <?php echo alert_message(); ?>
                <div class="card-header">About Us Section</div>
                <form action="<?php echo $GLOBALS['site_url']; ?>home/aboutus_section" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-body">
                        <input type="hidden" name="insert_id" value="<?= $edit['id'] ?? 'insert'; ?>">

                        <div class="form-group">
                            <label for="heading">Heading</label>
                            <input type="text" class="form-control" id="heading" name="heading" placeholder="Enter Main Heading" value="<?= htmlspecialchars($aboutus_data['heading'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Main Description"><?= htmlspecialchars($aboutus_data['description'] ?? ''); ?></textarea>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="featured_image">Select Featured image:</label>
                            <?php $featured_image = $aboutus_data['featured_image'] ?? ''; ?>
                            <img id="featured_image_preview" src="<?= !empty($featured_image) ? $GLOBALS['site_url'] . $featured_image : ''; ?>" alt="Featured Image" style="max-width: 100px; max-height: 100px; margin-bottom: 10px; <?= empty($featured_image) ? 'display: none;' : ''; ?>"><br>
                            <input type="file" name="featured_image" id="featured_image" onchange="previewImage('featured_image', 'featured_image_preview')">
                        </div>



                        <div class="card-header">About Us Blocks</div>

                        <div class="form-group">
                            <label for="block1_title">Title</label>
                            <input type="text" class="form-control" id="block1_title" name="block1_title" placeholder="Enter Block Title Text" value="<?= htmlspecialchars($aboutus_data['blocks'][0]['title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="block1_description">Description</label>
                            <textarea class="form-control" id="block1_description" name="block1_description" rows="3" placeholder="Enter Block Description"><?= htmlspecialchars($aboutus_data['blocks'][0]['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="block1_img">Icon Image:</label>
                            <?php $block1_img = $aboutus_data['blocks'][0]['img'] ?? ''; ?>
                            <img id="block1_img_preview" src="<?= !empty($block1_img) ? $GLOBALS['site_url'] . $block1_img : ''; ?>" alt="Block Image" style="max-width: 100px; max-height: 100px; margin-bottom: 10px; <?= empty($block1_img) ? 'display: none;' : ''; ?>"><br>
                            <input type="file" name="block1_img" id="block1_img" onchange="previewImage('block1_img', 'block1_img_preview')">
                        </div>

                        <div class="form-group">
                            <label for="block2_title">Title</label>
                            <input type="text" class="form-control" id="block2_title" name="block2_title" placeholder="Enter Block Text" value="<?= htmlspecialchars($aboutus_data['blocks'][1]['title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="block2_description">Description</label>
                            <textarea class="form-control" id="block2_description" name="block2_description" rows="3" placeholder="Enter Block Description"><?= htmlspecialchars($aboutus_data['blocks'][1]['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="block2_img">Icon Image:</label>
                            <?php $block2_img = $aboutus_data['blocks'][1]['img'] ?? ''; ?>
                            <img id="block2_img_preview" src="<?= !empty($block2_img) ? $GLOBALS['site_url'] . $block2_img : ''; ?>" alt="Block Image" style="max-width: 100px; max-height: 100px; margin-bottom: 10px; <?= empty($block2_img) ? 'display: none;' : ''; ?>"><br>
                            <input type="file" name="block2_img" id="block2_img" onchange="previewImage('block2_img', 'block2_img_preview')">
                        </div>

                        <div class="form-group">
                            <label for="block3_title">Title</label>
                            <input type="text" class="form-control" id="block3_title" name="block3_title" placeholder="Enter Block Text" value="<?= htmlspecialchars($aboutus_data['blocks'][2]['title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="block3_description">Description</label>
                            <textarea class="form-control" id="block3_description" name="block3_description" rows="3" placeholder="Enter Block Description"><?= htmlspecialchars($aboutus_data['blocks'][2]['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="block3_img">Icon Image:</label>
                            <?php $block3_img = $aboutus_data['blocks'][2]['img'] ?? ''; ?>
                            <img id="block3_img_preview" src="<?= !empty($block3_img) ? $GLOBALS['site_url'] . $block3_img : ''; ?>" alt="Block Image" style="max-width: 100px; max-height: 100px; margin-bottom: 10px; <?= empty($block3_img) ? 'display: none;' : ''; ?>"><br>
                            <input type="file" name="block3_img" id="block3_img" onchange="previewImage('block3_img', 'block3_img_preview')">
                        </div>

                        <div class="card-footer">

                            <button type="submit" name="save_data" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php include '../../footer.php'; ?>

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