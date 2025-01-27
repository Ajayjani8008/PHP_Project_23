<?php
include_once 'Crud.php';

class paginate
{

    function __construct()
    {
        // parent::__construct();
    }

    public function dataview($limit, $table, $where, $column)
    {
        $crud = new Crud();
        $results = $crud->execute("SELECT $column FROM $table $where LIMIT {$limit}");
        return $results;
    }

    public function paging($query, $records_per_page)
    {
        $starting_position = 0;
        if (isset($_GET["page_no"])) {
            $starting_position = ($_GET["page_no"] - 1) * $records_per_page;
        }
        $query2 = "$starting_position,$records_per_page";
        return $query2;
    }

    public function paginglink($total_no_of_records, $records_per_page, $query)
    {
        if ($_SERVER['PHP_SELF'] == "/satyam/ajax_list.php") {
            $self = "report.php";
        } else {
            $self = $_SERVER['PHP_SELF'];
        }
        if (empty($query)) {
            $query = "";
        }

        if ($total_no_of_records > 0) {
            $total_no_of_pages = ceil($total_no_of_records / $records_per_page);
            $current_page = 1;
            $second_last = $total_no_of_pages - 1;
            if (isset($_GET["page_no"])) {
                $current_page = $_GET["page_no"];
            }
            if ($current_page != 1) {
                $previous = $current_page - 1;
                echo "<a href='" . $self . "?page_no=1&select=" . $query . "' class='pg_no'  style='padding: 5px 14px;border: 1px solid #dee2e6;margin: 5px;'>First</a>&nbsp;";
                echo "<a href='" . $self . "?page_no=" . $previous . "&select=" . $query . "' class='pg_no'  style='padding: 5px 14px;border: 1px solid #dee2e6;margin: 5px;'>Previous</a>&nbsp;&nbsp;";
            } else {
                $previous = $current_page - 1;
                echo "<span class='closed_links'>First</span>&nbsp;";
                echo "<span class='closed_links'>Previous</span>&nbsp;&nbsp;";
            }

            if ($total_no_of_pages <= 10) {
                for ($i = 1; $i <= $total_no_of_pages; $i++) {
                    if ($i == $current_page) {
                        echo "<a href='" . $self . "?page_no=" . $i . "&select=" . $query . "' class='pg_no' style='color:red;font-weight:bold;color:white;padding:5px 11px;background-color:#007bff;'>" . $i . "</a>&nbsp;&nbsp;";
                    } else {
                        echo "<a href='" . $self . "?page_no=" . $i . "&select=" . $query . "' class='pg_no' style='text-decoration:none;padding:5px 11px;background-color:#dee2e6;'>" . $i . "</a>&nbsp;&nbsp;";
                    }
                }
            } elseif ($total_no_of_pages > 10) {
                if ($current_page <= 4) {
                    for ($i = 1; $i < 8; $i++) {
                        if ($i == $current_page) {
                            echo "<a href='" . $self . "?page_no=" . $i . "&select=" . $query . "' class='pg_no' style='color:red;font-weight:bold;color:white;padding:5px 11px;background-color:#007bff;'>" . $i . "</a>&nbsp;&nbsp;";
                        } else {
                            echo "<a href='" . $self . "?page_no=" . $i . "&select=" . $query . "' class='pg_no' style='text-decoration:none;padding:5px 11px;background-color:#dee2e6;'>" . $i . "</a>&nbsp;&nbsp;";
                        }
                    }
                    echo "<a class='more'>...</a>";
                    echo "<a href='" . $self . "?page_no=" . $second_last . "&select=" . $query . "' class='pg_no' style='text-decoration:none;padding:5px 11px;background-color:#dee2e6;'>" . $second_last . "</a>&nbsp;&nbsp;";
                    echo "<a href='" . $self . "?page_no=" . $total_no_of_pages . "&select=" . $query . "' class='pg_no' style='text-decoration:none;padding:5px 11px;background-color:#dee2e6;'>" . $total_no_of_pages . "</a>&nbsp;&nbsp;";
                } elseif ($current_page > 4 && $current_page < $total_no_of_pages - 4) {
                    echo "<a href='" . $self . "?page_no=1&select=" . $query . "' class='pg_no' style='text-decoration:none;padding:5px 11px;background-color:#dee2e6;'>1</a>&nbsp;&nbsp;";
                    echo "<a href='" . $self . "?page_no=2&select=" . $query . "' class='pg_no' style='text-decoration:none;padding:5px 11px;background-color:#dee2e6;'>2</a>&nbsp;&nbsp;";
                    echo "<a class='more'>...</a>";
                    for ($i = $current_page - 2; $i <= $current_page + 2; $i++) {
                        if ($i == $current_page) {
                            echo "<a href='" . $self . "?page_no=" . $i . "&select=" . $query . "' class='pg_no' style='color:red;font-weight:bold;color:white;padding:5px 11px;background-color:#007bff;'>" . $i . "</a>&nbsp;&nbsp;";
                        } else {
                            echo "<a href='" . $self . "?page_no=" . $i . "&select=" . $query . "' class='pg_no' style='text-decoration:none;padding:5px 11px;background-color:#dee2e6;'>" . $i . "</a>&nbsp;&nbsp;";
                        }
                    }
                    echo "<a class='more'>...</a>";
                    echo "<a href='" . $self . "?page_no=" . $second_last . "&select=" . $query . "' class='pg_no' style='text-decoration:none;padding:5px 11px;background-color:#dee2e6;'>" . $second_last . "</a>&nbsp;&nbsp;";
                    echo "<a href='" . $self . "?page_no=" . $total_no_of_pages . "&select=" . $query . "' class='pg_no' style='text-decoration:none;padding:5px 11px;background-color:#dee2e6;'>" . $total_no_of_pages . "</a>&nbsp;&nbsp;";
                } else {
                    echo "<a href='" . $self . "?page_no=1&select=" . $query . "' class='pg_no' style='text-decoration:none;padding:5px 11px;background-color:#dee2e6;'>1</a>&nbsp;&nbsp;";
                    echo "<a href='" . $self . "?page_no=2&select=" . $query . "' class='pg_no' style='text-decoration:none;padding:5px 11px;background-color:#dee2e6;'>2</a>&nbsp;&nbsp;";
                    echo "<a class='more'>...</a>";

                    for ($i = $total_no_of_pages - 6; $i <= $total_no_of_pages; $i++) {
                        if ($i == $current_page) {
                            echo "<a href='" . $self . "?page_no=" . $i . "&select=" . $query . "' class='pg_no' style='color:red;font-weight:bold;color:white;padding:5px 11px;background-color:#007bff;'>" . $i . "</a>&nbsp;&nbsp;";
                        } else {
                            echo "<a href='" . $self . "?page_no=" . $i . "&select=" . $query . "' class='pg_no' style='text-decoration:none;padding:5px 11px;background-color:#dee2e6;'>" . $i . "</a>&nbsp;&nbsp;";
                        }
                    }
                }
            }

            if ($current_page != $total_no_of_pages) {
                $next = $current_page + 1;

                echo "<a href='" . $self . "?page_no=" . $next . "&select=" . $query . "' class='pg_no'  style='padding: 5px 14px;border: 1px solid #dee2e6;margin: 5px;'>Next</a>&nbsp;";
                echo "<a href='" . $self . "?page_no=" . $total_no_of_pages . "&select=" . $query . "' class='pg_no'  style='padding: 5px 14px;border: 1px solid #dee2e6;margin: 5px;'>Last</a>&nbsp;&nbsp;";
            } else {
                $next = $current_page + 1;
                echo "<span class='closed_links'>Next</span>&nbsp;";
                echo "<span class='closed_links'>Last</span>&nbsp;&nbsp;";
            }
        }
    }
}
