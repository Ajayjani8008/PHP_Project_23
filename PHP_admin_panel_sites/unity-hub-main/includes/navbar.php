<?php
$result = $crud->getData("pages", "", "", "");
if ($result) {
    $pages = $result;
}
$site_settings_data = $crud->getData("site_settings", "id=1", "", "");
$siteData=$site_settings_data[0];

$siteIcon = $siteData['site_icon'] ?? null;
$siteLogo = $siteData['site_logo'] ?? null;
$siteName = $siteData['site_name'] ?? null;
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $GLOBALS['main_site_url']; ?>"><?php echo $siteName;?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php foreach ($pages as $page): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $GLOBALS['main_site_url'] ?>page.php?slug=<?php echo $page['slug']; ?>">
                            <?php echo htmlspecialchars($page['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>