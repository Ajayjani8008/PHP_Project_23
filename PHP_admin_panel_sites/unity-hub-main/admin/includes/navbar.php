<?php
$username = $_SESSION['user_data']['user_username'];
$userData = $crud->getData('users', "username='" . $username . "'", "", "")[0];
$profileData = json_decode($userData['profile_data'], true);

?>
<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="index.php" class="logo d-flex align-items-center">
      <img src="<?php echo !empty($siteLogo) ? $GLOBALS['admin_site_url'].$siteLogo : $GLOBALS['admin_site_url'] . '/assets/img/logo.png'; ?>" alt="site-logo">
      <span class="d-none d-lg-block"><?php echo $siteName;?></span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div>
  <!-- End Logo -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      <li class="nav-item dropdown">

        <?php if ($current_role_id == 1): ?>
      <li><a href="<?php echo $GLOBALS['admin_site_url']; ?>user_management.php">
          <i class="bi bi-people-fill"></i>
          Manage Users
        </a></li>
      <style>
        nav ul li a {
          display: flex;
          align-items: center;
          padding: 10px;
          text-decoration: none;
          color: #333;
        }

        nav ul li a i {
          margin-right: 8px;
          font-size: 20px;
        }
      </style>
    <?php endif; ?>

    </li>

    <li class="nav-item dropdown pe-3">

      <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img src="<?php echo !empty($profileData['profileImage']) ? $GLOBALS['admin_site_url'] . $profileData['profileImage'] : $GLOBALS['admin_site_url'] . '/assets/img/profile_image.jpg'; ?>" alt="Profile" class="rounded-circle">

        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $userData['name'] ?></span>
      </a>
      <!-- End Profile Iamge Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
          <h6><?php echo $userData['name'] ?></h6>
          <span><?php echo $profileData['job']; ?></span>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="<?php echo  $GLOBALS['admin_site_url'] ?>users-profile.php">
            <i class="bi bi-person"></i>
            <span>My Profile</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="<?php echo  $GLOBALS['admin_site_url'] ?>users-profile.php">
            <i class="bi bi-gear"></i>
            <span>Account Settings</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="faq.php">
            <i class="bi bi-question-circle"></i>
            <span>Need Help?</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="<?php echo $GLOBALS['admin_site_url'] ?>auth/log_out.php">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
          </a>
        </li>

      </ul><!-- End Profile Dropdown Items -->
    </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->

</header><!-- End Header -->