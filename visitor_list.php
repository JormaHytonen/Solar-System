<?php
    /**
    * The Solar System Visitors list
    *
    * @package    solar-system
    * @author     Datatuki, Jorma Hytönen
    * @version    1.3
    * @ref        https://www.positronx.io/create-pagination-in-php-with-mysql-and-bootstrap
    * @custom-css https://www.jquery-az.com/bootstrap-4-pagination-9-demos-custom-default-css
    * @table-css  https://bootstrapshuffle.com/classes/tables/table-hover
    *
    */

    // Set Timezone
    if(!defined('TIMEZONE')) {
        define('TIMEZONE', 'Europe/Helsinki');
    }
    date_default_timezone_set(TIMEZONE);

  // Database
  include('config/db.php');

  // Set session
  session_start();
  if(isset($_POST['records-limit'])){
      $_SESSION['records-limit'] = $_POST['records-limit'];
  }

  $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 5;
  $page = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;
  $paginationStart = ($page - 1) * $limit;
  $visitors = $connection->query("SELECT * FROM visitors_table ORDER BY visitors_table.ID ASC LIMIT $paginationStart, $limit")->fetchAll();

  // Get total records
  $sql = $connection->query("SELECT count(id) AS id FROM visitors_table")->fetchAll();
  $allRecrods = $sql[0]['id'];

  // Calculate total pages
  $totoalPages = ceil($allRecrods / $limit);

  // Prev + Next
  $prev = $page - 1;
  $next = $page + 1;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="refresh" content="300">
    <link rel="shortcut icon" href="img/favicon-visitors.ico" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="js/jquery.tableSortable.js"></script>

    <title>Solar System Visitors</title>
    <style>
        .container {
            max-width: 1000px;
        }

        .table-hover {
        tbody tr {
            @include hover {
                background-color: $table-hover-bg;
                }
            }
        }
        .table-hover {
        $hover-background: darken($background, 5%);
            .table-#{$state} {
                @include hover {
                background-color: $hover-background;
                    > td,
                    > th {
                        background-color: $hover-background;
                    }
                }
            }
        }

        .page-link {
            z-index: 1;
            color: black;
            background-color: #E9ECEF;
            border-color: #202F11;
        }
        .page-item.active .page-link {
            z-index: 1;
            color: #fff;
            background-color: #6C757D;
            border-color: #202F11;
        }
    </style>
</head>

<body>
    <div class="container-fluid">

        <div class='jumbotron' style="margin-top: 4px;">
            <h2>Solar System Visitors</h2>
            <p>
            This is a list of visitors in the Solar System model<br />
            <span style="font-size:16px;">Using: <a href="https://github.com/davidjaenson/bootstrap-3-table-sortable and">Sortable table</a>&nbsp;
            and <a href="https://www.jquery-az.com/bootstrap-select-5-beautiful-styles/">Bootstrap Select</a></span>
            </p>
        </div>

        <!-- Select dropdown -->
        <div class="d-flex flex-row-reverse bd-highlight mb-3" style="padding-bottom: 6px;">
            <form action="visitor_list.php" method="post">
                Limit: &nbsp;
                <select class="selectpicker" data-width="80px"
                    name="records-limit" id="records-limit" data-style="btn-secondary">
                    <option disabled selected>Set</option>
                    <?php foreach([5,7,10,12] as $limit) { ?>
                    <option
                        <?php if(isset($_SESSION['records-limit']) && $_SESSION['records-limit'] == $limit) echo 'selected'; ?>
                        value="<?= $limit; ?>">
                        <?= $limit; ?>
                    </option>
                    <?php } ?>
                </select>
                <span>
                    &nbsp;&nbsp;Total Visitors: <?php echo $allRecrods; ?>
                    &nbsp;&nbsp; <?php echo date("d.m.Y H:i"); ?>
                </span>
            </form>
        </div>

        <!-- Datatable -->
        <table class="table table-sortable table-hover table-bordered mb-5">
            <thead>
                <tr class="table-secondary" style="background-color: #E9ECEF;">
                    <th data-table-sortable-disable>#</th>
                    <th data-table-sortable-type="date">Timestamp</th>
                    <th>IP</th>
                    <th>Browser</th>
                    <th>Refferer</th>
                    <th>Page</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($visitors as $visitor) { ?>
                <tr>
                    <td><?php echo $visitor['ID']; ?></td>
                    <td><?php echo $visitor['visitor_date']; ?></td>
                    <td><?php echo $visitor['visitor_ip']; ?></td>
                    <td><?php echo $visitor['visitor_browser']; ?></td>
                    <td><?php echo $visitor['visitor_refferer']; ?></td>
                    <td><?php echo $visitor['visitor_page']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="text-center">
            <nav aria-label="Visitors Pagination mt-5">
                <ul class="pagination">
                    <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                        <a class="page-link"
                            href="<?php if($page <= 1){ echo '#'; } else { echo "?page=" . $prev; } ?>">Previous</a>
                    </li>

                    <?php for($i = 1; $i <= $totoalPages; $i++ ) { ?>
                    <li class="page-item <?php if($page == $i) {echo 'active'; } ?>">
                        <a class="page-link" href="visitor_list.php?page=<?= $i; ?>"> <?= $i; ?> </a>
                    </li>
                    <?php } ?>

                    <li class="page-item <?php if($page >= $totoalPages) { echo 'disabled'; } ?>">
                        <a class="page-link"
                            href="<?php if($page >= $totoalPages){ echo '#'; } else {echo "?page=". $next; } ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#records-limit').change(function () {
                $('form').submit();
            })
        });
    </script>
</body>

</html>
