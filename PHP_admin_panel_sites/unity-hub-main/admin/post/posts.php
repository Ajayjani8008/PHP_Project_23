<?php
session_start();
include_once("../config/functions.php");
include_once("../config/DbConfig.php");
include_once("../config/Crud.php");

$conn = new DbConfig();
$crud = new Crud();

// Fetch posts data
$posts = $crud->getData('posts', '', '', '');

include_once "../includes/header.php";
include_once "../includes/navbar.php";
include_once "../includes/sidebar.php";
?>

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <?php if (isset($_SESSION['deletation_status'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            <?php echo $_SESSION['deletation_status']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['deletation_status']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['deletation_status_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <?php echo $_SESSION['deletation_status_error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['deletation_status_error']); ?>
                    <?php endif; ?>

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
                        <h5 class="card-title">Posts</h5>

                        <?php if (userHasPermission($_SESSION['user_data']['user_id'], 'create')): ?>
                            <a class="btn btn-primary" href="<?php echo $GLOBALS['admin_site_url'] ?>post/add-post.php">Add New Post</a>
                        <?php endif; ?>

                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Last Modified</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 1;
                                foreach ($posts as $post): ?>
                                    <tr>
                                        <td><?php echo $counter++; ?></td>
                                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                                        <td><?php echo htmlspecialchars($post['author']); ?></td>
                                        <td><?php echo date('F j, Y', strtotime($post['updated_at'])); ?></td>
                                        <td>
                                            <?php if (userHasPermission($_SESSION['user_data']['user_id'], 'view')): ?>
                                                <a class="btn btn-primary" href="<?php echo $GLOBALS['main_site_url'] ?>post-single.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">View</a>
                                            <?php endif; ?>
                                            <?php if (userHasPermission($_SESSION['user_data']['user_id'], 'edit')): ?>
                                                <a class="btn btn-success" href="edit-post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">Edit</a>
                                            <?php endif; ?>
                                            <?php if (userHasPermission($_SESSION['user_data']['user_id'], 'delete')): ?>
                                                <a class="btn btn-danger" href="delete-post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">Delete</a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($post['status'] == 'published'): ?>
                                                <span class="badge bg-success">Published</span>
                                            <?php elseif ($post['status'] == 'draft'): ?>
                                                <span class="badge bg-secondary">Draft</span>
                                                <?php if (userHasPermission($_SESSION['user_data']['user_id'], 'edit')): ?>
                                                    <a class="btn btn-warning btn-sm" href="change_status.php?slug=<?php echo htmlspecialchars($post['slug']); ?>&status=published">Publish</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
include_once '../includes/footer.php';
?>
