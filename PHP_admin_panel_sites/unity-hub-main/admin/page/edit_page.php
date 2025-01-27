<?php
session_start();
include_once "../config/functions.php";
include_once "../config/DbConfig.php";
include_once "../config/Crud.php";
include_once "../includes/header.php";

$conn = new DbConfig();
$crud = new Crud();

if (!userHasPermission($_SESSION['user_data']['user_id'], 'edit')) {
    header("Location:" . $GLOBALS['admin_site_url'] . "page/pages.php");
    exit;
}

$page_slug = $_GET['slug'];

if (!isset($page_slug)) {
    header("Location:" . $GLOBALS['admin_site_url'] . "page/pages.php");
    exit();
}

$result = $crud->getData('pages', "slug='" . $page_slug . "'", "", "*");


if ($result[0]) {
    $page = $result[0];
} else {
    echo "Page not found.";
    exit;
}

$authors = $crud->getData('users', "", "", "");
$categories = $crud->getData('categories', "", "", "*");
$tags = $crud->getData('tags', "", "", "*");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_page'])) {
    // Get the form data
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = $_POST['author'];
    $updated_at = date('Y-m-d H:i:s');
    $slug = make_unique_slug_base_name($title, 'pages', $crud);
    $category = isset($_POST['categories']) ? implode(',', $_POST['categories']) : null;
    $tags = isset($_POST['tags']) ? implode(',', $_POST['tags']) : null;
    $meta_description = trim($_POST['meta_description']);
    $status = $_POST['status'];

    if (strlen($meta_description) > 200) {
        $_SESSION['status_error'] = "Meta description must be less than 200 characters.";
        header("Location:" . $GLOBALS['admin_site_url'] . "page/edit_page.php?slug=" . $page_slug);
        exit;
    }

    $uploadDir = 'uploads/page/';
    $absoluteUploadsDir = $GLOBALS['uploads_dir_root'] . $uploadDir;
    $image = $page['featured_image'];

    if (!file_exists($absoluteUploadsDir)) {
        mkdir($absoluteUploadsDir, 0755, true);
    }

    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $uploadResponse = uploadFile($_FILES['featured_image'], $absoluteUploadsDir);
        if ($uploadResponse['status']) {
            $image = $uploadDir . $uploadResponse['file_name'];
        } else {
            $_SESSION['status_error'] = "Error uploading image: " . $uploadResponse['message'];
            header("Location:" . $GLOBALS['admin_site_url'] . "page/edit_page.php?slug=" . $page_slug);
            exit;
        }
    }

    $data = [
        'title' => $title,
        'content' => $content,
        'author' => $author,
        'updated_at' => $updated_at,
        'slug' => $slug,
        'category' => $category,
        'tags' => $tags,
        'meta_description' => htmlspecialchars($meta_description),
        'status' => $status
    ];

    if ($image) {
        $data['featured_image'] = $image;
    }

    $update_data = $crud->update('pages', $data, ["slug" => $page_slug]);

    if ($update_data) {
        $_SESSION['status_success'] = "Page updated successfully.";
    } else {
        $_SESSION['status_error'] = "Page update failed.";
    }

    header("Location:" . $GLOBALS['admin_site_url'] . "page/pages.php");
    exit;
}

include_once "../includes/navbar.php";
include_once "../includes/sidebar.php";
?>

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Page:</h5>
                    <form class="row g-3" action="" method="POST" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" name="title" class="form-control" id="title" placeholder="Title" value="<?php echo htmlspecialchars($page['title']); ?>" required>
                                <label for="title">Title</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea name="content" class="tinymce-editor" placeholder="Page Content"><?php echo htmlspecialchars($page['content']); ?></textarea>
                            </div>
                        </div>

                        <?php
                        function displayCategories($categories, $parent_id = 0, $level = 0)
                        {
                            global $page;
                            foreach ($categories as $category) {
                                if ($category['parent_id'] == $parent_id) {
                                    $selected = in_array($category['id'], explode(',', $page['category'])) ? 'selected' : '';
                                    echo '<option value="' . $category['id'] . '" ' . $selected . '>' . str_repeat('&nbsp;&nbsp;- ', $level) . htmlspecialchars($category['name']) . '</option>';
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
                                    <option value="<?php echo $tag['id']; ?>" <?php echo in_array($tag['id'], explode(',', $page['tags'])) ? 'selected' : ''; ?>><?php echo htmlspecialchars($tag['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating">
                                <textarea name="meta_description" class="form-control" id="meta_description" placeholder="Meta Description"><?php echo htmlspecialchars($page['meta_description']); ?></textarea>
                                <label for="meta_description">Meta Description</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="status" class="form-select" id="status" required>
                                    <option value="draft" <?php echo $page['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="published" <?php echo $page['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                                </select>
                                <label for="status">Status</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="author" class="form-select" id="author" required>
                                    <?php foreach ($authors as $author): ?>
                                        <option value="<?php echo $author['name']; ?>" <?php echo $page['author'] == $author['name'] ? 'selected' : ''; ?>><?php echo $author['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="author">Author</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input class="form-control" type="file" name="featured_image" id="featured_image" onchange="previewImage(event)">
                                <label for="featured_image">Featured Image</label>
                                <div id="image-preview" style="margin-top: 10px;">
                                    <?php if (!empty($page['featured_image'])): ?>
                                        <?php
                                        $imageUrl = $GLOBALS['admin_site_url'] . $page['featured_image'];
                                        ?>
                                        <img src="<?php echo $imageUrl; ?>" alt="Image Preview" style="max-width: 200px; display: block;">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="update_page" class="btn btn-success">Update Page</button>
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

<?php include_once "../includes/footer.php"; ?>