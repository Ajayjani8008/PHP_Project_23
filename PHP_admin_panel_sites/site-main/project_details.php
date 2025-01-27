<?php
session_start();
$title = 'Project Detail';
include_once 'admin/config/Crud.php';
include_once 'admin/config/functions.php';
include_once 'includes/header.php';

// Initialize Crud object
$crud = new Crud();

// $project_details_page = $crud->getData('project_details_page', "slug=1", '', '')[0];
?>

<body>
    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php include_once 'includes/navbar.php'; ?>
    <!-- header end -->

    <?php
    // Check if project slug is provsluged in the URL
    if (isset($_GET['slug'])) {
        // Get project slug from the URL
        $slug = $_GET['slug'];

        // Fetch project details from the database based on the slug
        $project_details = $crud->getData('projects', "slug='$slug'", '', '');

        // Check if project details are found
        if (!empty($project_details)) {
            // Display project details
            $project = $project_details[0]; // Assuming only one project is fetched

    ?>


            <!-- project Details section start -->

            <div class="project-details-section">
                <div class="container">
                    <div class="project-details-wapper">
                        <div class="project-details-img">
                            <img src="<?php echo $GLOBALS['site_url'].$project['featured_img'] ?>" alt="">
                        </div>
                        <div class="project-details-text-wapper">
                            <div class="project-details-text-wapper-innar">
                                <div class="project-details-text-left">
                                    <h5>Project Details</h5>
                                    <h3><?php echo  $project['project_heading'] ?></h3>
                                    <p><?php echo $project['additional_content'] ?></p>
                                </div>
                                <div class="project-details-text-right">
                                    <div class="project-details-text-right-card">
                                        <div class="project-details-text-right-card-img">
                                            <img src="<?php echo $GLOBALS['site_url'] . $project['sub_image'] ?>" alt="">
                                        </div>
                                        <div class="project-details-text-right-card-inner">
                                            <ul>
                                                <li><span>Client</span>
                                                    <p><?php echo $project['client'] ?></p>
                                                </li>
                                                <li><span>Categories</span>
                                                    <p><?php echo $project['categories'] ?></p>
                                                </li>
                                                <li><span>Date</span>
                                                    <p>
                                                        <?php

                                                        $date = new DateTime($project['date']);
                                                        echo $date->format('F j, Y');
                                                        ?>
                                                    </p>
                                                </li>
                                                <li><span>Tags</span>
                                                    <p><?php echo $project['tags'] ?></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- <p>Nulla Lorem mollit cupslugatat irure. Laborum magna nulla duis ullamco cillum dolor. Volup Nulla Lorem mollit cupslugatat irure. Laborum magna nulla duis ullamco cillum dolor. Voluptate exercitation incslugslugunt aliquip deserunt. tate exercitation incslugslugunt aliquip deserunt. </p>
                                    <p>Nulla Lorem mollit cupslugatat irure. Laborum magna nulla duis ullamco cillum dolor. Voluptate exercitation incslugslugunt aliquip deserunt. </p> -->
                                </div>
                            </div>
                        </div>
                        <div class="project-details-innar-img-wapper">
                            <!-- <div class="project-details-innar-img">
                                <img src="img/Rectangle 63.png" alt="">
                            </div>
                            <div class="project-details-innar-img">
                                <img src="img/Rectangle 62(3).png" alt="">
                            </div> -->
                        </div>
                        <div class="project-details-text-wapper">
                            <!-- <p>Nulla Lorem mollit cupslugatat irure. Laborum magna nulla duis ullamco cillum dolor. Volup Nulla Lorem mollit cupslugatat irure. Laborum magna nulla duis ullamco cillum dolor. Voluptate exercitation incslugslugunt aliquip deserunt. tate exercitation incslugslugunt aliquip deserunt. Nulla Lorem mollit cupslugatat irure. Laborum magna nulla duis ullamco cillum dolor. Volup Nulla Lorem mollit cupslugatat irure. Laborum magna nulla duis ullamco cillum dolor. Voluptate exercitation incslugslugunt aliquip deserunt. tate exercitation incslugslugunt aliquip deserunt. </p> -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- project Details section end -->

    <?php
        } else {
            // Handle case when project details are not found
            echo "project not found.";
        }
    } else {
        // Handle case when project slug is not provsluged in the URL
        echo "project slug is missing.";
    }
    ?>

    <?php
    $projects = $crud->getData('projects', '', '', ''); // Fetch all projects from the database
    // echo '<pre>';

    // var_dump($projects);



    ?>

    <!-- Other project section start -->

    <div class="other-project-section">
        <div class="container">
            <h3>Other project</h3>
        </div>
        
        <div class="other-project-wapper">
            <div class="owl-carousel" id="h-other-project-slider">
                <?php foreach ($projects as $project) : ?>
                    
                    <div class="item">
                        <a href="<?php echo $project['slug']?>"><div class="other-project-card-wapper">
                            <div class="other-project-card-img">
                                <img src="<?php echo $GLOBALS['site_url'].$project['project_page_img']; ?>" alt="">
                            </div>
                            <div class="other-project-card-inner">
                                <h4><?php echo $project['project_name']; ?></h4>
                                <p><?php echo $project['project_heading']; ?></p>
                            </div>
                        </div></a>
                    </div>
                <?php endforeach; ?>


                <!-- <div class="item">
                    <div class="other-project-card-wapper">
                        <div class="other-project-card-img">
                            <img src="img/Rectangle 45(6).png" alt="">
                        </div>
                        <div class="other-project-card-inner">
                            <h4>Profesional</h4>
                            <p>Amet minim mollit non deserunt ullamco est sit aliqua minim mollit.</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="other-project-card-wapper">
                        <div class="other-project-card-img">
                            <img src="img/Rectangle 45(7).png" alt="">
                        </div>
                        <div class="other-project-card-inner">
                            <h4>Profesional</h4>
                            <p>Amet minim mollit non deserunt ullamco est sit aliqua minim mollit.</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="other-project-card-wapper">
                        <div class="other-project-card-img">
                            <img src="img/Rectangle 45(5).png" alt="">
                        </div>
                        <div class="other-project-card-inner">
                            <h4>Profesional</h4>
                            <p>Amet minim mollit non deserunt ullamco est sit aliqua minim mollit.</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="other-project-card-wapper">
                        <div class="other-project-card-img">
                            <img src="img/Rectangle 45(6).png" alt="">
                        </div>
                        <div class="other-project-card-inner">
                            <h4>Profesional</h4>
                            <p>Amet minim mollit non deserunt ullamco est sit aliqua minim mollit.</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="other-project-card-wapper">
                        <div class="other-project-card-img">
                            <img src="img/Rectangle 45(7).png" alt="">
                        </div>
                        <div class="other-project-card-inner">
                            <h4>Profesional</h4>
                            <p>Amet minim mollit non deserunt ullamco est sit aliqua minim mollit.</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="other-project-card-wapper">
                        <div class="other-project-card-img">
                            <img src="img/Rectangle 45(5).png" alt="">
                        </div>
                        <div class="other-project-card-inner">
                            <h4>Profesional</h4>
                            <p>Amet minim mollit non deserunt ullamco est sit aliqua minim mollit.</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="other-project-card-wapper">
                        <div class="other-project-card-img">
                            <img src="img/Rectangle 45(6).png" alt="">
                        </div>
                        <div class="other-project-card-inner">
                            <h4>Profesional</h4>
                            <p>Amet minim mollit non deserunt ullamco est sit aliqua minim mollit.</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="other-project-card-wapper">
                        <div class="other-project-card-img">
                            <img src="img/Rectangle 45(7).png" alt="">
                        </div>
                        <div class="other-project-card-inner">
                            <h4>Profesional</h4>
                            <p>Amet minim mollit non deserunt ullamco est sit aliqua minim mollit.</p>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

    </div>

    <!-- Other project section end -->




    <!-- Partner section start -->

    <!-- add already in footer  -->

    <!-- Partner section end -->



    <!-- footer section start -->


    <?php include_once 'includes/footer.php' ?>
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/project-details/js/main.js"></script>

    <!-- footer section end -->

</body>

</html>