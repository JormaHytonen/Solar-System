<?php
    /**
    * This is the description for PHP Page Template
    *
    * @package    solar-system
    * @author     Datatuki, Jorma Hytönen
    * @version    1.1
    *
    */

    include("includes/a_config.php");
    include('visitor_tracking.php');
?>

<!DOCTYPE html>
<html>
<head>
    <?php include("includes/head-tag-contents.php"); ?>
</head>
<body>

<?php include("includes/design-top.php"); ?>

<div class="container" id="main-content">
    <h2>How small is Planet Earth</h2>
    <p>
        <video controls="controls" src="media/How-small-is-planet-earth.mp4">
            Your browser does not support the HTML5 Video element.
        </video>
    </p>
</div>

<?php include("includes/footer.php"); ?>

</body>
</html>
