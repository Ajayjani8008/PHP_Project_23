<?php
session_start();
include_once("../config/functions.php");
include_once("../config/DbConfig.php");
include_once("../config/Crud.php");

$conn = new DbConfig();
$crud = new Crud();

$pages = $crud->getData('pages', '', '', '');

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
                    <?php unset($_SESSION['deletation_status']);
                    endif; ?>

                    <?php if (isset($_SESSION['deletation_status_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <?php echo $_SESSION['deletation_status_error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php unset($_SESSION['deletation_status_error']);
                    endif; ?>

                    <?php if (isset($_SESSION['status_success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            <?php echo $_SESSION['status_success']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php unset($_SESSION['status_success']);
                    endif; ?>

                    <?php if (isset($_SESSION['status_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <?php echo $_SESSION['status_error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php unset($_SESSION['status_error']);
                    endif; ?>

                    <div class="card-body">
                        <h5 class="card-title">Pages</h5>

                        <?php if (userHasPermission($_SESSION['user_data']['user_id'], 'create')): ?>
                            <a class="btn btn-primary" href="<?php echo $GLOBALS['admin_site_url'] ?>page/add_page.php">Add New Page</a>
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
                                foreach ($pages as $page): ?>
                                    <tr>
                                        <td><?php echo $counter++; ?></td>
                                        <td><?php echo htmlspecialchars($page['title']); ?></td>
                                        <td><?php echo htmlspecialchars($page['author']); ?></td>
                                        <td><?php echo date('F j, Y', strtotime($page['created_at'])); ?></td>
                                        <td>
                                            <?php if (userHasPermission($_SESSION['user_data']['user_id'], 'view')): ?>
                                                <a class="btn btn-primary" href="<?php echo $GLOBALS['main_site_url'] ?>page.php?slug=<?php echo $page['slug']; ?>">View</a>
                                            <?php endif; ?>
                                            <?php if (userHasPermission($_SESSION['user_data']['user_id'], 'edit')): ?>
                                                <a class="btn btn-success" href="edit_page.php?slug=<?php echo htmlspecialchars($page['slug']); ?>">Edit</a>
                                            <?php endif; ?>
                                            <?php if (userHasPermission($_SESSION['user_data']['user_id'], 'delete')): ?>
                                                <a class="btn btn-danger" href="delete_page.php?slug=<?php echo htmlspecialchars($page['slug']); ?>">Delete</a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($page['status'] == 'published'): ?>
                                                <span class="badge bg-success">Published</span>
                                            <?php elseif ($page['status'] == 'draft'): ?>
                                                <span class="badge bg-secondary">Draft</span>
                                                <?php if (userHasPermission($_SESSION['user_data']['user_id'], 'edit')): ?>
                                                    <a class="btn btn-warning btn-sm" href="change_status.php?slug=<?php echo htmlspecialchars($page['slug']); ?>&status=published">Publish</a>
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