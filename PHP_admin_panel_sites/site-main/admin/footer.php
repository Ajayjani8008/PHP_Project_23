<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.2.0
    </div>
    <strong>Copyright © 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
</footer>



<aside class="control-sidebar control-sidebar-dark" style="display: none; top: 60.5px;">
    <!-- Control sidebar content goes here -->
</aside>
<div id="sidebar-overlay"></div>
</div>


<div class="daterangepicker ltr show-ranges opensright">
    <div class="ranges">
        <ul>
            <li data-range-key="Today">Today</li>
            <li data-range-key="Yesterday">Yesterday</li>
            <li data-range-key="Last 7 Days">Last 7 Days</li>
            <li data-range-key="Last 30 Days">Last 30 Days</li>
            <li data-range-key="This Month">This Month</li>
            <li data-range-key="Last Month">Last Month</li>
            <li data-range-key="Custom Range">Custom Range</li>
        </ul>
    </div>
    <div class="drp-calendar left">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span><button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button><button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button> </div>
</div>
<div class="jqvmap-label" style="display: none;"></div>



<!-- jQuery -->
<script src="<?php echo $GLOBALS['site_url']; ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo $GLOBALS['site_url']; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Ekko Lightbox -->
<script src="<?php echo $GLOBALS['site_url']; ?>plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $GLOBALS['site_url']; ?>assets/dist/js/adminlte.min.js"></script>
<!-- Filterizr-->
<script src="<?php echo $GLOBALS['site_url']; ?>plugins/filterizr/jquery.filterizr.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $GLOBALS['site_url']; ?>assets/dist/js/demo.js"></script>
<!-- Page specific script -->
<script src="<?php echo $GLOBALS['site_url']; ?>assets/dist/js/pages/dashboard.js"></script>
<!-- Include Summernote JS  Files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>

<script>
    // Function to filter table rows based on search input
    function filterTable(tableId) {
        var input = document.getElementById(tableId + "-searchInput");
        var filter = input.value.toUpperCase();
        var table = document.getElementById(tableId);
        var rows = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName("td");
            var found = false;
            for (var j = 0; j < cells.length; j++) {
                var cell = cells[j];
                if (cell) {
                    if (cell.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            if (found) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    // Add event listener to the search input field for each table
    var tables = document.querySelectorAll(".filterable-table");
    tables.forEach(function(table) {
        var tableId = table.getAttribute("id");
        document.getElementById(tableId + "-searchInput").addEventListener("keyup", function() {
            filterTable(tableId);
        });
    });
</script>