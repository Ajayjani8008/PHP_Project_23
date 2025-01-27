<?php

error_reporting(E_ALL);

session_start();
include_once '../../config/Dbconfig.php';
include_once '../../config/Crud.php';
include_once '../../config/functions.php';
include_once '../../auth/authentication.php';
include_once '../../header.php';
$crud = new Crud();

$contact_page_data = $crud->getData('contact_us', "id=1", '', '')[0];

$form_submission_data = $contact_page_data['form_submission_data'] ?? '{}';

$form_submission_data_json = json_decode($form_submission_data, true) ?? [];

?>

<body>
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center" style="height: 0px;">
            <img class="animation__shake" src="<?php echo $GLOBALS['site_url']; ?>assets/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60" style="display: none;">
        </div>

        <!-- header start -->
        <?php
        include_once '../../navbar.php';
        ?>
        <!-- header end -->

        <!-- sidebar start -->
        <?php
        include '../../sidebar.php';
        ?>
        <!-- sidebar end -->

        <div class="content-wrapper" style="min-height: 107px;">
            <div class="container mt-5">
                <div class="card">
                    <div class="card-header" style="background-color:beige">
                        <h3 class="card-title">Contact Us List</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" id="table_search" class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0 table-responsive" style="max-height: 665px; overflow-y: auto;">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Service</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody id="contact_table">
                                <?php if (isset($form_submission_data_json['client_contact']) && is_array($form_submission_data_json['client_contact'])) {
                                    // Reverse the array to show the newest entries first
                                    $form_submission_data_json['client_contact'] = array_reverse($form_submission_data_json['client_contact']);
                                ?>
                                    <?php foreach ($form_submission_data_json['client_contact'] as $index => $value) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($index + 1); ?></td>
                                            <td><?php echo htmlspecialchars($value['name']); ?></td>
                                            <td><?php echo htmlspecialchars($value['email']); ?></td>
                                            <td><?php echo htmlspecialchars($value['service']); ?></td>
                                            <td style="width: 300px;"><?php echo htmlspecialchars($value['message']); ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
            echo alert_message();
            ?>
        </div>

        <!-- footer start -->
        <?php
        include '../../footer.php';
        ?>
        <!-- footer end -->
    </div>
    <style>
        .content-wrapper {
            margin-left: 250px;
            /* Adjust this if your sidebar width is different */
            padding: 20px;
        }

        .container {
            max-width: 100%;
            width: 100%;
        }

        .card {
            width: 100%;
            overflow: hidden;
        }

        .card-body {
            padding: 0;
        }

        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
    <script>
        document.getElementById('table_search').addEventListener('keyup', function() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('table_search');
            filter = input.value.toLowerCase();
            table = document.getElementById('contact_table');
            tr = table.getElementsByTagName('tr');

            for (i = 0; i < tr.length; i++) {
                tr[i].style.display = 'none'; // Initially hide all rows
                td = tr[i].getElementsByTagName('td');
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = ''; // Show the row if a match is found
                            break;
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>