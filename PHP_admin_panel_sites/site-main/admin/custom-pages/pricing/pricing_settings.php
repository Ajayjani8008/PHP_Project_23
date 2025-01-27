<?php
// pricing_settings.php

session_start();
include_once '../../config/DbConfig.php';
include_once '../../config/Crud.php';
include_once '../../config/functions.php';
include_once '../../auth/authentication.php';
include_once '../../header.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$crud = new Crud();

// Check if 'edit' parameter is present in the URL
$existingData = [];
if (isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    $existingDataResult = $crud->getbyid('pricing', $editId);
    if ($existingDataResult['status'] === 200) {
        $existingData = $existingDataResult['data'];
    } else {
        $_SESSION['status_error'] = 'Error Fetching plan data for editing';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null; // Get the ID form submission
    // Set form field values based on existing data if available
    $plan_name = $_POST['plan_name'] ?? '';
    $price = $_POST['price'] ?? '';
    $saving = $_POST['saving'] ?? '';
    $button_link = $_POST['button_link'] ?? '';

    // Handle multiple on and off services
    $specification = [
        'on_service' => [],
        'off_service' => []
    ];

    if (!empty($_POST['on_service']) && is_array($_POST['on_service'])) {
        foreach ($_POST['on_service'] as $service) {
            $specification['on_service'][] = $service;
        }
    }

    if (!empty($_POST['off_service']) && is_array($_POST['off_service'])) {
        foreach ($_POST['off_service'] as $service) {
            $specification['off_service'][] = $service;
        }
    }

    // Define data array
    $data = [
        'plan_name' => $plan_name,
        'price' => $price,
        'saving' => $saving,
        'button_link' => $button_link,
        'specification' => json_encode($specification)
    ];




    if ($id) {
        // update the plan with the new data
        $updateResult = $crud->update('pricing', $data, ['id' => $id]);
        if ($updateResult === true) {
            $_SESSION['status'] = 'Plan updated successfully';
        } else {
            $_SESSION['status_error'] = 'Error updating plan: ' . $updateResult;
        }
    } else {
        // insert new plan
        $insertResult = $crud->insert('pricing', $data);
        if ($insertResult === true) {
            $_SESSION['status'] = 'Plan created successfully';
        } else {
            $_SESSION['status_error'] = 'Error creating plan: ' . $insertResult;
        }
    }
    header('Location: ' . $GLOBALS['site_url'] . 'pricing/pricing_settings');
    exit();
}

// Delete 
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $deleteResult = $crud->delete('pricing', $deleteId);
    if ($deleteResult === true) {
        $_SESSION['status'] = 'Plan deleted successfully';
    } else {
        $_SESSION['status_error'] = 'Error deleting plan: ' . $deleteResult;
    }
    header('Location: ' . $GLOBALS['site_url'] . 'pricing/pricing_settings');
    exit();
}

$plans = $crud->getData('pricing', '', '', '');
?>

<body>
    <div class="wrapper">
        <?php include_once '../../navbar.php'; ?>
        <?php include '../../sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="card card-primary card-body">
                <?php echo alert_message(); ?>
                <div class="card-header">Manage Pricing</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>pricing/pricing_settings" method="post">
                    <input type="hidden" name="id" value="<?php echo $existingData['id'] ?? ''; ?>">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="plan_name">Plan Name:</label>
                            <input type="text" class="form-control" required name="plan_name" value="<?php echo $existingData['plan_name'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="text" class="form-control" required name="price" value="<?php echo $existingData['price'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="saving">Saving Percentage:</label>
                            <input type="text" class="form-control" required name="saving" value="<?php echo $existingData['saving'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="button_link">Button Link:</label>
                            <input type="text" class="form-control" required name="button_link" value="<?php echo $existingData['button_link'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="on_service">Available Services:</label>
                            <?php if ($existingData) : ?>
                                <?php foreach (json_decode($existingData['specification'], true)['on_service'] as $index => $service) : ?>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" required name="on_service[]" value="<?php echo $service; ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-danger delete-service" type="button">Delete</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <input type="text" class="form-control" required name="on_service[]" placeholder="Add Avaliable Service">
                            <?php endif; ?>
                            <div class="on_service"></div><br>
                            <!-- Additional input fields can be added via JavaScript -->
                            <button type="button" id="addOnService" class="btn btn-primary">Add On Service</button>
                        </div>
                        <div class="form-group ">
                            <label for="off_service">Unavailable Services:</label>
                            <?php if ($existingData) : ?>
                                <?php foreach (json_decode($existingData['specification'], true)['off_service'] as $index => $service) : ?>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" required name="off_service[]" value="<?php echo $service; ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-danger delete-service" type="button">Delete</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <input type="text" class=" form-control" required name="off_service[]" placeholder="Add Unavaliable Service">
                            <?php endif; ?>
                            <div class="off_service"></div><br>
                            <!-- Additional input fields can be added via JavaScript -->
                            <button type="button" id="addOffService" class="btn btn-primary">Add Off Service</button>
                        </div>
                        <div class="card-footer">
                            <input type="submit" class="btn btn-primary" value="<?php echo isset($existingData['id']) ? 'Update Plan' : 'Add Plan'; ?>">
                        </div>
                    </div>
                </form>


                <h2>Plans List</h2>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Plan Name</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($plans as $plan) : ?>
                                            <tr>
                                                <td><?php echo $plan['id']; ?></td>
                                                <td><?php echo $plan['plan_name']; ?></td>
                                                <td><?php echo $plan['price']; ?></td>
                                                <td>
                                                    <a href="pricing_settings?edit=<?php echo $plan['id']; ?>">Edit</a>
                                                    <a href="pricing_settings?delete=<?php echo $plan['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>

        <?php include '../../footer.php'; ?>
    </div>

    <script>
        document.getElementById('addOnService').addEventListener('click', function() {
            var input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.name = 'on_service[]';
            input.placeholder = 'Add Available Service';
            document.querySelector('.on_service').appendChild(input);
        });

        document.getElementById('addOffService').addEventListener('click', function() {
            var input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.name = 'off_service[]';
            input.placeholder = 'Add Unavailable Service';
            document.querySelector('.off_service').appendChild(input);
        });

        document.querySelectorAll('.delete-service').forEach(function(button) {
            button.addEventListener('click', function() {
                var service = this.closest('.input-group').querySelector('input').value;
                var isOnService = this.closest('.form-group').classList.contains('on_service');

                // Remove from DOM
                this.closest('.input-group').remove();

                // Remove from database
                if (isOnService) {
                    var url = 'pricing_settings?type=on_service&service=' + encodeURIComponent(service);
                } else {
                    var url = 'pricing_settings?type=off_service&service=' + encodeURIComponent(service);
                }

                fetch(url, {
                        method: 'DELETE'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(data.message);
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            });
        });
    </script>
</body>

</html>