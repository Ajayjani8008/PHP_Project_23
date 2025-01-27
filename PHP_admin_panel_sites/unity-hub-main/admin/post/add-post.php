<?php
session_start();
include_once("../config/functions.php");
include_once("../config/DbConfig.php");
include_once("../config/Crud.php");
include_once "../includes/header.php";

$conn = new DbConfig();
$crud = new Crud();
$authors = $crud->getData('users', "", "", "");

if (!userHasPermission($_SESSION['user_data']['user_id'], 'create')) {
    header("Location:" . $GLOBALS['admin_site_url'] . "post/posts.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_post'])) {
    $title = $_POST['title'];
    $excerpt = $_POST['excerpt'];
    $author = $_POST['author'];
    $categories = isset($_POST['categories']) ? implode(',', $_POST['categories']) : null;
    $tags = isset($_POST['tags']) ? implode(',', $_POST['tags']) : null;
    $content = $_POST['content'];
    $publish_date = date('Y-m-d H:i:s');
    $slug = make_unique_slug_base_name($title, 'posts', $crud);
    $status = $_POST['status'];

    $uploadDir = 'uploads/post/';
    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadDir;
    $image = null;

    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadResponse = uploadFile($_FILES['image'], $absoluteUploadsDir);
        if ($uploadResponse['status']) {
            $image = $uploadDir . $uploadResponse['file_name'];
        } else {
            $_SESSION['status_error'] = "Error uploading image: " . $uploadResponse['message'];
        }
    }

    $data = [
        'title' => $title,
        'excerpt' => $excerpt,
        'author' => $author,
        'content' => $content,
        'publish_date' => $publish_date,
        'slug' => $slug,
        'category' => $categories,
        'tags' => $tags,
        'status' => $status
    ];

    if ($image) {
        $data['image'] = $image;
    }


    $update_data = $crud->insert('posts', $data);

    if ($update_data) {
        $_SESSION['status_success'] = "Post Created successfully.";
    } else {
        $_SESSION['status_error'] = "Post Creation failed.";
    }

    header("Location:" . $GLOBALS['admin_site_url'] . "post/posts.php");
}

include_once "../includes/navbar.php";
include_once "../includes/sidebar.php";
$categories = $crud->getData('categories', "", "", "*");
$tags = $crud->getData('tags', "", "", "*");

?>

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add New Post:</h5>
                    <form class="row g-3" action="" method="POST" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" name="title" class="form-control" id="title" placeholder="Title" required>
                                <label for="title">Title</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="excerpt" class="form-control" id="excerpt" placeholder="Short Description">
                                <label for="excerpt">Short Description</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea name="content" class="tinymce-editor"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="hidden" name="publish_date" class="form-control" id="publish_date" placeholder="Publish Date" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                        </div>


                        <?php
                        function displayCategories($categories, $parent_id = 0, $level = 0)
                        {
                            foreach ($categories as $category) {
                                if ($category['parent_id'] == $parent_id) {
                                    echo '<option value="' . $category['id'] . '">' . str_repeat('&nbsp;&nbsp;- ', $level) . htmlspecialchars($category['name']) . '</option>';
                                    displayCategories($categories, $category['id'], $level + 1);
                                }
                            }
                        }
                        ?>
                        <div class="col-sm-6" id="categories-container">
                            <label for="categories">Categories</label>
                            <select name="categories[]" class="form-select categories-select" id="categories" multiple>
                                <?php displayCategories($categories); ?>
                            </select>
                        </div>

                        <div class="col-md-6" id="tags-container">
                            <label for="tags">Tags</label>
                            <select name="tags[]" class="form-select tags-select" id="tags" multiple>
                                <?php foreach ($tags as $tag): ?>
                                    <option value="<?php echo $tag['id']; ?>"><?php echo htmlspecialchars($tag['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="status" class="form-select" id="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                                <label for="status">Status</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="author" class="form-select" id="author" required>

                                    <?php foreach ($authors as $author): ?>
                                        <option value="<?php echo $author['name']; ?>"><?php echo $author['name']; ?></option>
                                    <?php endforeach; ?>

                                </select>
                                <label for="author">Author</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="col-md-12">
                                <label for="image" class="col-sm-2 col-form-label">Upload Post Image</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="file" name="image" id="formFile" onchange="previewImage(event)">
                                    <div id="image-preview" style="margin-top: 10px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="create_post" class="btn btn-success">Create Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('image-preview');
            output.innerHTML = '<img src="' + reader.result + '" alt="Image Preview" style="max-width: 200px; display: block;">';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<?php
include_once '../includes/footer.php';
?>