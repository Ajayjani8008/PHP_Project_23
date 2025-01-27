<?php
session_start();
include_once '../../config/Dbconfig.php';
include_once '../../config/Crud.php';
include_once '../../config/functions.php';
include_once '../../auth/authentication.php';
include_once '../../header.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$crud = new Crud();
$page_data = $crud->getData('pricing_page', "id=1", '', '');
$page_data = $page_data ? $page_data[0] : [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_page_data'])) {
        // Retrieve form data
        $page_title = $_POST['title'] ?? '';
        $page_heading = $_POST['heading'] ?? '';
        $page_description = $_POST['description'] ?? '';
        $currency_type = $_POST['currency_type'] ?? '';
        $button_text = $_POST['button_text'] ?? '';
        $time_period = $_POST['time_period'] ?? '';

        // Construct the data to be updated
        $page_data = [
            'title' => $page_title,
            'heading' => $page_heading,
            'description' => $page_description,
            'currency_type' => $currency_type,
            'button_text' => $button_text,
            'time_period' => $time_period,
        ];

        // Update or insert data into the database
        if (!empty($page_data['title'])) { // Assuming title is never empty for an existing record
            $updateResult = $crud->update('pricing_page', $page_data, ['id' => 1]);
            if($updateResult === true){
                $_SESSION['status']='Page details updated successfully';
            }else{
                $_SESSION['status_error']='Error updating page details: ' . $updateResult;
            }

        } else {
            $insertResult = $crud->insert('pricing_page', $page_data);
            if($insertResult === true){
                $_SESSION['status']='Page details inserted successfully';
            }else{
                $_SESSION['status_error']='Error inserting page details: ' . $insertResult;
            }
        }

        header('Location: ' . $GLOBALS['site_url'] . 'pricing/pricing_page');
        exit();
    }
}

// Fetch existing data from the database
$page_title = $page_data['title'] ?? '';
$page_heading = $page_data['heading'] ?? '';
$page_description = $page_data['description'] ?? '';
$currency_type = $page_data['currency_type'] ?? '';
$button_text = $page_data['button_text'] ?? '';
$time_period = $page_data['time_period'] ?? '';
?>

<body>
    <div class="wrapper">
        <!-- Header -->
        <?php include_once '../../navbar.php'; ?>

        <!-- Sidebar -->
        <?php include '../../sidebar.php'; ?>

        <div class="content-wrapper" style="min-height: 107px;">
            <div class="card card-primary card-body">
                <?php echo alert_message(); ?>

                <div class="card-header">Pricing Page</div>
                <form action="<?php echo htmlspecialchars($GLOBALS['site_url']); ?>pricing/pricing_page" method="post">
                    <div class="card card-primary card-body">
                        <div class="form-group">
                            <label for="title">Page Title:</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($page_title); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="heading">Page Heading:</label>
                            <input type="text" class="form-control" id="heading" name="heading" value="<?php echo htmlspecialchars($page_heading); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Page Description:</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($page_description); ?></textarea>
                        </div>
                        <h4> Pricing Block's Common Fields</h4>
                        <div class="form-group">
                            <label for="currency_type">Currency Type:</label>
                            <input type="text" class="form-control" id="currency_type" name="currency_type" value="<?php echo htmlspecialchars($currency_type); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="button_text">Button Name:</label>
                            <input type="text" class="form-control" id="button_text" name="button_text" value="<?php echo htmlspecialchars($button_text); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="time_period">Plan Time Period :</label>
                            <input type="text" class="form-control" id="time_period" placeholder="eg. Daily,Monthly,Yearly...." name="time_period" value="<?php echo htmlspecialchars($time_period); ?>" required>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save_page_data" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <?php include '../../footer.php'; ?>
    </div>
</body>

</html>

<!-- <div class="row">
                                        <div class="form-group col-md-4 mb-3">
                                            <label for="plans[<? //= $i; 
                                                                ?>][period]">Period</label>
                                            <select class="form-control" id="plans[<? //= $i; 
                                                                                    ?>][period]" name="plans[<? //= $i; 
                                                                                                                ?>][period]">
                                                <option value="monthly" <? //=  $plan['period'] === 'monthly' ? 'selected' : ''; 
                                                                        ?>>Monthly</option>
                                                <option value="yearly" <? //= $plan['period'] === 'yearly' ? 'selected' : ''; 
                                                                        ?>>Yearly</option>
                                                <option value="daily" <? //= $plan['period'] === 'daily' ? 'selected' : ''; 
                                                                        ?>>Daily</option>
                                            </select>
                                        </div>
                                    </div> -->