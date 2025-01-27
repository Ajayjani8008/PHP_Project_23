  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

          <li class="nav-item">
              <a class="nav-link " href="<?php echo $GLOBALS['admin_site_url']; ?>">
                  <i class="bi bi-grid"></i>
                  <span>Dashboard</span>
              </a>
          </li><!-- End Dashboard Nav -->

          <li class="nav-item">
              <a class="nav-link collapsed" href="<?php echo $GLOBALS['admin_site_url'] ?>users-profile.php">
                  <i class="bi bi-person"></i>
                  <span>Profile</span>
              </a>
          </li><!-- End Profile Page Nav -->


          <li class="nav-item">
              <a class="nav-link collapsed" href="<?php echo $GLOBALS['admin_site_url']; ?>post/posts.php">
                  <i class="bi bi-file-post"></i>
                  <span>Posts</span>
              </a>
          </li><!-- End Post Page Nav -->

          <li class="nav-item">
              <a class="nav-link collapsed" href="<?php echo $GLOBALS['admin_site_url']; ?>page/pages.php">
                  <i class="bi bi-file-richtext-fill"></i>
                  <span>Pages</span>
              </a>
          </li><!-- End Page Nav -->

          <?php
            if ((userHasPermission($_SESSION['user_data']['user_id'], 'create')) || userHasPermission($_SESSION['user_data']['user_id'], 'edit')) {
            ?>
              <li class="nav-item">
                  <a class="nav-link collapsed" href="<?php echo $GLOBALS['admin_site_url']; ?>page/manage_categories.php">
                      <i class="bi bi-ui-checks-grid"></i>
                      <span>Menage Categories</span>
                  </a>
              </li>
          <?php
            }
            if ($_SESSION['user_data']['role_id'] == 1) {
            ?>
              <li class="nav-item">
                  <a class="nav-link collapsed" href="<?php echo $GLOBALS['admin_site_url']; ?>site-settings.php">
                      <i class="bi bi-gear-fill"></i>
                      <span>Site Settings</span>
                  </a>
              </li><!-- End site settings Nav -->
          <?php  } ?>
      </ul>

  </aside><!-- End Sidebar-->