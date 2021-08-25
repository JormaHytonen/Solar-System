<?php

require_once 'db/_db.php';

?>

<!DOCTYPE html>
<html>  
<head>  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
    <link href="icons/favicon.ico" rel="icon" type="image/x-icon" />

    <title>Show FMI Data</title>
    <script src="js/jquery-1.12.4.min.js" type="text/javascript"></script>  
    <script src="js/bootstrap.min.js" type="text/javascript"></script>  
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />  
    <link href="css/form_style.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" language="javascript" src="js/dataModal.js"></script>
</head>  
<body>  

<div id="mainForm" style="width:100%;">
    <div id="formHeader">
        <h2 class="formInfo">FMI Open Data</h2>
        <p class="formInfo">Ilmatieteen laitoksen avoin data&nbsp;&nbsp;
        <a style="color:#CCAD3E" href='http://datatuki.net/'>Datatuki.net</a> &nbsp;&copy; Jorma Hytönen</p>
    </div>
    <br />
    <div>
        &nbsp;&nbsp;
        <input type="button" id="search" class="btn btn-default" value=" Get <?php echo $xmlFile ?> " 
            data-toggle="tooltip" data-placement="top" title="Näytä data" />
        &nbsp;&nbsp;
        <input type="button" id="close" class="btn btn-default" value=" Poistu " onClick="window.close();" />
    </div>
    <br />
    
    <script type="text/javascript">
        $("#search").click(function(s) {
            var modal = new dataModal.Modal();
            modal.onClosed = function(args) {
                // loadEvents();
            };
            modal.height = 420;
            modal.width = 980;                                   
            modal.showUrl("data_panel.php?do=search");
        });        
    </script>
</div>


</body>  
</html>  


