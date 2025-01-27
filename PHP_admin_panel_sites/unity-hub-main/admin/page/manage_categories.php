<?php
session_start();
include_once("../config/functions.php");
include_once("../config/DbConfig.php");
include_once("../config/Crud.php");
include_once "../includes/header.php";

$conn = new DbConfig();
$crud = new Crud();

if (!userHasPermission($_SESSION['user_data']['user_id'], 'create') || !userHasPermission($_SESSION['user_data']['user_id'], 'edit')) {
    header("Location:" . $GLOBALS['admin_site_url']);
    exit;
}

$allCategories = $crud->getData('categories', "", "", "*");
$tags = $crud->getData('tags', "", "", "");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_category'])) {
        $name = trim($_POST['category_name']);
        $parent_id = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;

        if (!empty($name)) {
            $categoryExists = array_filter($allCategories, function($category) use ($name) {
                return strcasecmp($name, $category['name']) == 0;
            });

            if ($categoryExists) {
                $_SESSION['status_error'] = "Category Name Already Exists!";
            } else {
                $crud->insert('categories', ['name' => $name, 'parent_id' => $parent_id]);
                $_SESSION['status_success'] = "Category added.";
                $allCategories = $crud->getData('categories', "", "", "*");
            }
        } else {
            $_SESSION['status_error'] = "Category name cannot be empty.";
        }
    }

    if (isset($_POST['add_tag'])) {
        $name = trim($_POST['tag_name']);
        if (!empty($name)) {
            $tagExists = array_filter($tags, function($tag) use ($name) {
                return strcasecmp($name, $tag['name']) == 0;
            });

            if ($tagExists) {
                $_SESSION['status_error'] = "Tag Name Already Exists!";
            } else {
                $crud->insert('tags', ['name' => $name]);
                $_SESSION['status_success'] = "Tag added.";
                $tags = $crud->getData('tags', "", "", "");
            }
        } else {
            $_SESSION['status_error'] = "Tag name cannot be empty.";
        }
    }

    if (isset($_POST['delete_category'])) {
        $category_id = intval($_POST['category_id']);
        if ($category_id) {
            $delete_result = $crud->delete('categories', $category_id);
            $_SESSION['status_success'] = $delete_result ? "Category deleted successfully." : "Failed to delete category.";
        } else {
            $_SESSION['status_error'] = "Invalid category ID.";
        }
    }

    if (isset($_POST['edit_category'])) {
        $category_id = $_POST['category_id'];
        $new_name = trim($_POST['edit_category_name']);

        if (!empty($new_name)) {
            $categoryExists = array_filter($allCategories, function($category) use ($new_name, $category_id) {
                return strcasecmp($new_name, $category['name']) == 0 && $category['id'] != $category_id;
            });

            if ($categoryExists) {
                $_SESSION['status_error'] = "Category Name Already Exists!";
            } else {
                $update_result = $crud->update('categories', ['name' => $new_name], ['id' => $category_id]);
                $_SESSION['status_success'] = $update_result ? "Category updated successfully." : "Failed to update category.";
            }
        } else {
            $_SESSION['status_error'] = "Category name cannot be empty.";
        }
    }

    if (isset($_POST['delete_tag'])) {
        $id = intval($_POST['tag_id']);
        if ($id) {
            $delete_result = $crud->delete('tags', $id);
            $_SESSION['status_success'] = $delete_result ? "Tag deleted." : "Failed to delete tag.";
        } else {
            $_SESSION['status_error'] = "Invalid tag ID.";
        }
    }

    if (isset($_POST['edit_tag'])) {
        $tag_id = $_POST['tag_id'];
        $new_name = trim($_POST['edit_tag_name']);

        if (!empty($new_name)) {
            $tagExists = array_filter($tags, function($tag) use ($new_name, $tag_id) {
                return strcasecmp($new_name, $tag['name']) == 0 && $tag['id'] != $tag_id;
            });

            if ($tagExists) {
                $_SESSION['status_error'] = "Tag Name Already Exists!";
            } else {
                $update_result = $crud->update('tags', ['name' => $new_name], ['id' => $tag_id]);
                $_SESSION['status_success'] = $update_result ? "Tag updated successfully." : "Failed to update tag.";
            }
        } else {
            $_SESSION['status_error'] = "Tag name cannot be empty.";
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

include_once "../includes/navbar.php";
include_once "../includes/sidebar.php";
?>

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="card">

                <?php if (isset($_SESSION['status_success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i>
                        <?php echo $_SESSION['status_success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['status_success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['status_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <?php echo $_SESSION['status_error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['status_error']); ?>
                <?php endif; ?>

                <div class="card-body">
                    <h5 class="card-title">Manage Categories and Tags</h5>

                    <!-- add category form -->
                    <form action="" method="POST">
                        <h4>Add Category</h4>
                        <div class="form-group">
                            <input type="text" name="category_name" class="form-control" placeholder="Category Name" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="parent_category">Parent Category (Optional)</label>
                            <select name="parent_id" class="form-select">
                                <option value="">No Parent (This is a main category)</option>
                                <?php foreach ($allCategories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="submit" name="add_category" class="btn btn-primary mt-2" value="Add Category">
                        <br>
                    </form>

                    <h5 class="mt-4">Categories</h5>
                    <ul class="list-group">
                        <?php if (!empty($allCategories)): ?>
                            <?php foreach ($allCategories as $category): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="category-name"><?php echo htmlspecialchars($category['name']); ?></span>
                                        <!-- Edit Form -->
                                        <form action="" method="POST" class="d-inline edit-form" style="display:none;">
                                            <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($category['id']); ?>">
                                            <input type="text" name="edit_category_name" class="form-control form-control-sm" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                                            <button type="submit" name="edit_category" class="btn btn-primary btn-sm mt-2">Save</button>
                                            <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="toggleEditForm(this)">Cancel</button>
                                        </form>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-info btn-sm" onclick="toggleEditForm(this)">Edit</button>
                                        <form action="" method="POST" class="d-inline">
                                            <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($category['id']); ?>">
                                            <button type="submit" name="delete_category" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">No categories found</li>
                        <?php endif; ?>
                    </ul>

                    <!-- add tag form -->
                    <form action="" method="POST" class="mt-4">
                        <h4>Add Tag</h4>
                        <div class="form-group">
                            <input type="text" name="tag_name" class="form-control" placeholder="Tag Name" required>
                        </div>
                        <input type="submit" name="add_tag" class="btn btn-primary mt-2" value="Add Tag">
                        <br>
                    </form>

                    <h5 class="mt-4">Tags</h5>
                    <ul class="list-group">
                        <?php if (!empty($tags)): ?>
                            <?php foreach ($tags as $tag): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="tag-name"><?php echo htmlspecialchars($tag['name']); ?></span>
                                        <!-- edit Form -->
                                        <form action="" method="POST" class="d-inline edit-form" style="display:none;">
                                            <input type="hidden" name="tag_id" value="<?php echo htmlspecialchars($tag['id']); ?>">
                                            <input type="text" name="edit_tag_name" class="form-control form-control-sm" value="<?php echo htmlspecialchars($tag['name']); ?>" required>
                                            <button type="submit" name="edit_tag" class="btn btn-primary btn-sm mt-2">Save</button>
                                            <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="toggleEditForm(this)">Cancel</button>
                                        </form>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-info btn-sm" onclick="toggleEditForm(this)">Edit</button>
                                        <form action="" method="POST" class="d-inline">
                                            <input type="hidden" name="tag_id" value="<?php echo htmlspecialchars($tag['id']); ?>">
                                            <button type="submit" name="delete_tag" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">No tags found</li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>
        </div>
    </section>
</main>

<script>
function toggleEditForm(button) {
    const form = button.closest('.list-group-item').querySelector('form.edit-form');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}
</script>

<?php include_once "../includes/footer.php"; ?>
