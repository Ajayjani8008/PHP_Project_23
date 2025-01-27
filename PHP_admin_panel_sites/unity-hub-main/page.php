<?php
include_once "includes/header.php";

if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
    $result = $crud->getData("pages", "slug='" . $slug . "'", "", "");
    if (!$result) {
        header("Location:" . $GLOBALS['main_site_url'] . "404.php");
        exit;
    }
    $page_data=$result[0];
} else {
    header("Location:" . $GLOBALS['main_site_url'] . "index.php");
    exit;
}

include_once "includes/navbar.php";
?>

<main id="main" class="main">
    <section class="section">
        <div class="container">
            <h1><?php echo htmlspecialchars($page_data['title']); ?></h1>
            <div>
                <?php echo nl2br($page_data['content']); ?>
            </div>
            <div>
                This is simple page view Template
            </div>
        </div>
    </section>
    <section>
        <a href="index.php">goto front page</a>
    </section>
</main>

<?php include_once "includes/footer.php"; ?>