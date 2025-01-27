<?php
session_start();

include_once 'config/functions.php';
include_once 'config/DbConfig.php';
include_once 'config/Crud.php';
include_once 'includes/header.php';

$conn = new DbConfig();
$crud = new Crud();

if (!isset($_SESSION['user_data']['user_username'])) {
  header("Location: login.php");
  exit();
}

$username = $_SESSION['user_data']['user_username'];
$userData = $crud->getData('users', "username='" . $username . "'", "", "")[0];
$profileData = json_decode($userData['profile_data'], true);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['changepassword'])) {
  $password = $_POST['password'];
  $newpassword = $_POST['newpassword'];
  $renewpassword = $_POST['renewpassword'];

  if (empty($password) || empty($newpassword) || empty($renewpassword)) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'All fields are required!',
        })
        </script>";
    exit();
  }

  $user_data = $crud->getData("users", "username='" . $crud->escape_string($_SESSION['user_data']['user_username']) . "'", "", "password");

  if (isset($user_data[0]['password'])) {
    $user_hashed_password = $user_data[0]['password'];

    if (password_verify($password, $user_hashed_password)) {
      if ($newpassword === $renewpassword) {

        $hashedNewPassword = password_hash($newpassword, PASSWORD_DEFAULT);

        $update_status = $crud->update('users', ['password' => $hashedNewPassword], ["username" => $_SESSION['user_data']['user_username']]);

        if ($update_status) {
          echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Password updated successfully!',
                    }).then(function() {
                        window.location = 'users-profile.php';
                    });
                    </script>";
        } else {
          echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update password!',
                    })
                    </script>";
        }
      } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'New passwords do not match!',
                })
                </script>";
      }
    } else {
      echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Current password is incorrect!',
            })
            </script>";
    }
  } else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'User not found!',
        })
        </script>";
  }
}

include_once "includes/navbar.php";
include_once 'includes/sidebar.php';
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Profile</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item">Users</li>
        <li class="breadcrumb-item active">Profile</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section profile">
    <div class="row">
      <div class="col-xl-4">

        <div class="card">
          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

            <img src="<?php echo !empty($profileData['profileImage']) ? $GLOBALS['admin_site_url'] . $profileData['profileImage'] : $GLOBALS['admin_site_url'] . 'assets/img/profile_image.jpg'; ?>" alt="Profile" class="rounded-circle">
            <h2><?php echo $_SESSION['user_data']['user_name'] ?></h2>
            <h3><?php echo $_SESSION['user_data']['role_name'] ?></h3>
            <div class="social-links mt-2">
              <a href="<?php echo $profileData['twitter']; ?>" class="twitter"><i class="bi bi-twitter"></i></a>
              <a href="<?php echo $profileData['facebook']; ?>" class="facebook"><i class="bi bi-facebook"></i></a>
              <a href="<?php echo $profileData['instagram']; ?>" class="instagram"><i class="bi bi-instagram"></i></a>
              <a href="<?php echo $profileData['linkedin']; ?>" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>
          </div>
        </div>

      </div>

      <div class="col-xl-8">

        <div class="card">
          <div class="card-body pt-3">
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered">

              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
              </li>

            </ul>
            <div class="tab-content pt-2">

              <div class="tab-pane fade show active profile-overview" id="profile-overview">
                <?php if (!empty($profileData['about'])): ?>
                  <h5 class="card-title">About</h5>
                  <p class="small fst-italic"><?php echo $profileData['about']; ?></p>
                <?php endif; ?>

                <h5 class="card-title">Profile Details</h5>
                <?php if (!empty($userData['name'])): ?>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo $userData['name']; ?></div>
                  </div>
                <?php endif; ?>

                <?php if (!empty($profileData['company'])): ?>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Company</div>
                    <div class="col-lg-9 col-md-8"><?php echo $profileData['company']; ?></div>
                  </div>
                <?php endif; ?>

                <?php if (!empty($profileData['job'])): ?>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Job</div>
                    <div class="col-lg-9 col-md-8"><?php echo $profileData['job']; ?></div>
                  </div>
                <?php endif; ?>

                <?php if (!empty($profileData['country'])): ?>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Country</div>
                    <div class="col-lg-9 col-md-8"><?php echo $profileData['country']; ?></div>
                  </div>
                <?php endif; ?>

                <?php if (!empty($profileData['address'])): ?>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Address</div>
                    <div class="col-lg-9 col-md-8"><?php echo $profileData['address']; ?></div>
                  </div>
                <?php endif; ?>

                <?php if (!empty($profileData['phone'])): ?>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Phone</div>
                    <div class="col-lg-9 col-md-8"><?php echo $profileData['phone']; ?></div>
                  </div>
                <?php endif; ?>

                <?php if (!empty($userData['email'])): ?>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?php echo $userData['email']; ?></div>
                  </div>
                <?php endif; ?>

              </div>

              <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                <!-- Profile Edit Form -->
                <form action="profile_edit_code.php" method="POST" enctype="multipart/form-data">
                  <div class="row mb-3">
                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                    <div class="col-md-8 col-lg-9">
                      <img src="<?php echo !empty($profileData['profileImage']) ? $GLOBALS['admin_site_url'] . $profileData['profileImage'] : $GLOBALS['admin_site_url'] . 'assets/img/profile_image.jpg'; ?>" alt="Profile" id="profileImagePreview">
                      <div class="pt-2">

                        <input type="file" name="profileImage" id="profileImageInput" style="display: none;" onchange="previewImage(event)">

                        <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image" onclick="document.getElementById('profileImageInput').click(); return false;">
                          <i class="bi bi-upload"></i>
                        </a>

                        <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image" onclick="deleteImage(); return false;">
                          <i class="bi bi-trash"></i>
                        </a>


                        <!-- Hidden input to track delete action -->
                        <input type="hidden" name="delete_image" id="deleteImageInput" value="0">
                      </div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="fullName" type="text" class="form-control" id="fullName" value="<?php echo $userData['name']; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                    <div class="col-md-8 col-lg-9">
                      <textarea name="about" class="form-control" id="about" style="height: 100px"><?php echo $profileData['about']; ?></textarea>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="company" class="col-md-4 col-lg-3 col-form-label">Company</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="company" type="text" class="form-control" id="company" value="<?php echo $profileData['company']; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="Job" class="col-md-4 col-lg-3 col-form-label">Job</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="job" type="text" class="form-control" id="Job" value="<?php echo $profileData['job']; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="Country" class="col-md-4 col-lg-3 col-form-label">Country</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="country" type="text" class="form-control" id="Country" value="<?php echo $profileData['country']; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="address" type="text" class="form-control" id="Address" value="<?php echo $profileData['address']; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="phone" type="text" class="form-control" id="Phone" value="<?php echo $profileData['phone']; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="email" type="email" class="form-control" id="Email" value="<?php echo $userData['email']; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter Profile</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="twitter" type="text" class="form-control" id="Twitter" value="<?php echo $profileData['twitter']; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="facebook" type="text" class="form-control" id="Facebook" value="<?php echo $profileData['facebook']; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="instagram" type="text" class="form-control" id="Instagram" value="<?php echo $profileData['instagram']; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin Profile</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="linkedin" type="text" class="form-control" id="Linkedin" value="<?php echo $profileData['linkedin']; ?>">
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                  </div>
                </form><!-- End Profile Edit Form -->

              </div>

              <div class="tab-pane fade pt-3" id="profile-settings">

                <!-- Settings Form -->
                <form>

                  <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                    <div class="col-md-8 col-lg-9">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="changesMade" checked>
                        <label class="form-check-label" for="changesMade">
                          Changes made to your account
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="newProducts" checked>
                        <label class="form-check-label" for="newProducts">
                          Information on new products and services
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="proOffers">
                        <label class="form-check-label" for="proOffers">
                          Marketing and promo offers
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                        <label class="form-check-label" for="securityNotify">
                          Security alerts
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
                </form><!-- End settings Form -->

                <div>

                  <a href="delete_account.php">Delete My Account</a>
                </div>

              </div>

              <div class="tab-pane fade pt-3" id="profile-change-password">
                <!-- Change Password Form -->
                <form method="POST" action="" id="chanepassForm" name="chanepassForm">

                  <div class="row mb-3">
                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="password" type="password" class="form-control" id="currentPassword">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="newpassword" type="password" class="form-control" id="newPassword">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" name="changepassword" class="btn btn-primary">Change Password</button>
                  </div>
                </form><!-- End Change Password Form -->

              </div>

            </div><!-- End Bordered Tabs -->

          </div>
        </div>

      </div>
    </div>
  </section>

</main><!-- End #main -->

<script>
  function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
      var output = document.getElementById('profileImagePreview');
      output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
  }

  function deleteImage() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'profile_edit_code.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      if (xhr.status === 200) {
        document.getElementById('profileImagePreview').src = '<?php echo $GLOBALS['admin_site_url']; ?>/assets/img/profile_image.jpg';
      } else {
        alert('Error: ' + xhr.responseText);
      }
    };
    xhr.send('action=delete_image');
  }
</script>

<?php
include_once "includes/footer.php";
?>