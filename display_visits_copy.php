<?php

require_once('visitors_connections.php');   //the file with connection code and functions

echo "
<!DOCTYPE html>
<html>
<head>

<title>Solar System Visitors</title>
<meta charset='UTF-8'>
<meta name='author' content='Datatuki, Jorma Hytönen'>
<meta name='viewport' content='width=device-width, initial-scale=1.0>
<meta name='description' content='3D Solar System Model' />
<meta name='keywords' content='Solar System Model' />

<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>

<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css'>
<script src='https://kit.fontawesome.com/577845f6a5.js' crossorigin='anonymous'></script>

<script src='https://code.jquery.com/jquery-3.5.1.slim.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js'></script>
<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js'> </script>

<style>
    #visitTable td {
        font-size: 14px;
    }
</style>

</head>
<body>

<div class='jumbotron'>
	<h2>Solar System Visitors</h2>
	<p>This is a list of visitors in the Solar System model</p>
</div>
";


if ($_GET['start'] == "") $start = 0;
else $start = $_GET['start'];

$limit = 15;

$additionalQuery = "SQL_CALC_FOUND_ROWS ";

// mysql_select_db($database_visitors, $visitors);
$query_visitors = "SELECT ".$additionalQuery." * FROM visitors_table WHERE ID > 0";

if ($_POST['day'] != "") {
    $query_visitors .= " AND visitor_day = '".$_POST['day']."'";
} else {
    if ($_POST['month'] != "") {
        $query_visitors .= " AND visitor_month = '".$_POST['month']."'";
    }
    if ($_POST['year'] != "") {
        $query_visitors .= " AND visitor_year = '".$_POST['year']."'";
    }
}

$query_visitors .= " ORDER BY visitors_table.ID ASC";
// $query_visitors .= " LIMIT $start, $limit";

echo "<span style='font-size:12px; padding-left:8px;'>" . $query_visitors . "</span>";

$sth = $dbh->prepare($query_visitors);
$sth->execute();
$nbItems = $sth->rowCount();

if ($nbItems>($start+$limit)) $final = $start+$limit;
else $final = $nbItems;

$_POST = array();

echo '
    <div class="table-responsive">
    <table id="visitTable" class="table table-condensed" style="width:100%; border:1px dashed #CCC" cellpadding="3">
      <form id="form_visit" name="form_visit" method="post" action="display_visits.php">
       <tr>
        <td>Day
        <select name="day" id="day" class="custom-select" style="width:80px;">
          <option value="" selected="selected"></option>
          <option value="01">01</option>
          <option value="02">02</option>
          <option value="03">03</option>
          <option value="04">04</option>
          <option value="05">05</option>
          <option value="06">06</option>
          <option value="07">07</option>
          <option value="08">08</option>
          <option value="09">09</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
          <option value="24">24</option>
          <option value="25">25</option>
          <option value="26">26</option>
          <option value="27">27</option>
          <option value="28">28</option>
          <option value="29">29</option>
          <option value="30">30</option>
          <option value="31">31</option>
        </select></td>
        <td>Month
        <select name="month" id="month" class="custom-select" style="width:80px;">
          <option value="" selected="selected"></option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
        </select></td>
        <td>Year
        <select name="year" id="year" class="custom-select" style="width:80px;">
          <option value="" selected="selected"></option>
          <option value="2020">2020</option>
          <option value="2021">2021</option>
        </select></td>
        <td>
            <input type="submit" name="Submit" value="Submit" class="btn btn-secondary" />
            &nbsp;&nbsp;
            <input type="button" value="Refresh" onClick="location.href=location.href" class="btn btn-secondary">
        </td>
        <td></td>
       </tr>
      </form>';

echo  '<tr style="background-color: #e9ecef;">
        <td style="width:10%;border-bottom:1px solid #CCC">ID</td>
        <td style="width:10%;border-bottom:1px solid #CCC">Time</td>
        <td style="width:10%;border-bottom:1px solid #CCC">IP</td>
        <td style="width:20%;border-bottom:1px solid #CCC">Browser</td>
        <td style="width:20%;border-bottom:1px solid #CCC">Refferer</td>
        <td style="width:40%;border-bottom:1px solid #CCC">Page</td>
       </tr>';

while($row = $sth->fetch()) {
    echo
      '<tr onmouseout="this.style.backgroundColor=\'\'"
      onmouseover="this.style.backgroundColor=\'#EAFFEA\'">
        <td>'.$row['ID'].'</td>
        <td>'.$row['visitor_date'].'</td>
        <td>'.$row['visitor_ip'].'</td>
        <td>'.$row['visitor_browser'].'</td>
        <td>'.$row['visitor_refferer'].'</td>
        <td>'.$row['visitor_page'].'</td>
       </tr>';
}

echo '</table>
    </div>';

paginate($start, $limit, $nbItems, "display_visits.php", "");

