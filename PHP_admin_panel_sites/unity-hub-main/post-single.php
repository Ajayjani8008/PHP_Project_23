<?php
include_once  "includes/header.php";

if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];

    $result = $crud->getData('posts', "slug='" . $slug . "'", "", "");

    if ($result) {
        $post = $result[0];
    } else {
        header("Location:".$GLOBALS['main_site_url']."404.php");
        exit();
    }
} else {
    header("Location:".$GLOBALS['main_site_url']."404.php");
    exit();
}

?>
        <div class="container">
            <section class="section">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                                <p><strong>Posted on:</strong> <?php echo date('F j, Y', strtotime($post['publish_date'])); ?></p>
                                <div class="post-content">
                                    <?php echo nl2br($post['content']); ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
<?php
include_once 'includes/footer.php';
?>
