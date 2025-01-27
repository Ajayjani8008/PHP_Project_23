<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php if (isset($title)) {
            echo $title;
        } else {
            echo " ";
        } ?>
    </title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['new_site_url']; ?>assets/header-footer-css/header-footer.css">

    <?php

    $page = basename($_SERVER['PHP_SELF']);
    $stylesheet = '';

    switch ($page) {
        case 'index.php':
            $stylesheet = 'assets/home/css/style.css';
            break;
        case 'services.php':
            $stylesheet = 'assets/services/css/style.css';
            break;
        case 'about.php':
            $stylesheet = 'assets/about_us/css/style.css';
            break;
        case 'blog.php':
            $stylesheet = 'assets/blog/css/style.css';
            break;
        case 'blog_details.php':
            $stylesheet = 'assets/blog_details/css/style.css';
            break;
        case 'career_details.php':
            $stylesheet = 'assets/blog_details/css/style.css';
            break;
        case 'career.php':
            $stylesheet = 'assets/career/css/style.css';
            break;
        case 'contact_us.php':
            $stylesheet = 'assets/contacts/css/style.css';
            break;
        case 'team.php':
            $stylesheet = 'assets/team/css/style.css';
            break;
        case 'projects.php':
            $stylesheet = 'assets/projects/css/style.css';
            break;
        case 'pricing.php':
            $stylesheet = 'assets/pricing/css/style.css';
            break;
        case 'project_details.php':
            $stylesheet = 'assets/project-details/css/style.css';
            break;
        case '404.php':
            $stylesheet = 'assets/404/css/style.css';
            break;
        case 'about.php':
            $stylesheet = 'assets/about_us/css/style.css';
            break;
        default:
            $stylesheet = 'assets/home/css/style.css';
            break;
    }

    // Include the page-specific stylesheet
    if (!empty($stylesheet)) {
    ?>
        <link rel="stylesheet" href="<?php echo $GLOBALS['new_site_url'] . $stylesheet; ?>">
    <?php
    }
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/header-footer-js/header-footer.js"></script>
</head>