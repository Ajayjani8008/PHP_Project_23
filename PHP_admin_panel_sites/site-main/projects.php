<?php
include_once 'admin/config/functions.php';
include_once 'admin/config/Crud.php';
$title = 'Projects';

include_once 'includes/header.php';

$crud = new Crud();
$project_page = $crud->getData('project_page', "id=1", '', '')[0];
$projects=$crud->getData('projects','', '', '');

// echo '<pre>';
// var_dump($projects);

?>

<body>

    <div class="m-overlay-bg"></div>
    <!-- header start -->
    <?php
    include_once 'includes/navbar.php';
    ?>
    <!-- header end -->


    <!-- project section start -->

    <div class="project-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5><?php echo $project_page['title'] ?></h5>
                <h4><?php echo $project_page['heading'] ?></h4>
                <p><?php echo $project_page['description'] ?></p>
            </div>
            <div class="project-row-wapper">


                <?php foreach ($projects as $project) : ?>
                    <div class="project-card-wapper">
                        <a href="project/<?php echo $project['slug']?>">
                        <div class="project-card-img">
                            <img src="<?php echo $GLOBALS['site_url'].$project['project_page_img']?>" alt="">
                        </div></a>
                        <div class="project-card-inner">
                            <div class="our-teams-card-inner-left">
                                <h4><a style="color: black;" href="project/<?php echo $project['slug']?>"><?php echo $project['project_name']?></h4></a>
                                <span><?php echo $project['project_sub_title']?></span>
                            </div>
                            <div class="our-teams-view-btn">
                                <a href="project/<?php echo $project['slug']?>"><?php echo $project_page['button_text']?></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <!-- project section end -->

    <!-- contact us section satrt -->

    <!-- <div class="contact-us-section">
        <div class="container">
            <div class="all-us-title-wapper">
                <h5>Contact Us</h5>
                <h4>Laborum magna nulla duis</h4>
                <p>Nulla Lorem mollit cupidatat irure. Laborum magna nulla duis ullamco cillum dolor. Voluptate exercitation incididunt aliquip deserunt.</p>
            </div>
            <div class="contact-us-row">
                <div class="contact-us-left">
                    <img src="img/Rectangle 65.png" alt="">
                </div>
                <div class="contact-us-right">
                    <form action="">
                        <div class="form-group">
                            <label for="Name">Name</label>
                            <div class="input-text">
                                <span><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" placeholder="Paula Ramsey" id="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Email">Email</label>
                            <div class="input-text">
                                <span><i class="fas fa-envelope-open"></i></span>
                                <input type="Email" class="form-control" placeholder="example@gmail.com" id="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Message">Message</label>
                            <textarea name="Message"  class="form-control" rows="4" id="Message" placeholder="Type here"></textarea>
                        </div>
                    </form>
                </div>
            </div>
            <div class="contact-us-service">
                <h4>Service</h4>
                <ul>
                    <li><a href="javascript:void(0)">Web Service</a></li>
                    <li><a href="javascript:void(0)">Web Development</a></li>
                    <li><a href="javascript:void(0)">Branding</a></li>
                    <li><a href="javascript:void(0)">Content Creative</a></li>
                    <li><a href="javascript:void(0)">Copywriting</a></li>
                </ul>
                <div class="send-btn">
                    <a href="#">Send</a>
                </div>
            </div>
        </div>
    </div> -->

    <!-- contact us section end -->





    <!-- Partner section start -->

    <!-- included in footer section -->

    <!-- Partner section end -->



    <!-- footer section start -->


    <?php
    include_once('includes/footer.php');
    ?>
    <script src="<?php echo $GLOBALS['new_site_url']; ?>assets/projects/js/main.js"></script>
    <!-- footer section end -->

</body>

</html>