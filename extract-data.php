<?php

/* **
https://www.geeksforgeeks.org/how-to-zip-a-directory-in-php/
** */

if( !defined('TIMEZONE') ) {
    define('TIMEZONE', 'Europe/Helsinki');
    date_default_timezone_set(TIMEZONE);
}

$path = "/home/datatu6/public_html/server/weather/";
$file = "data/fmi-all.zip";

$t=time();
$d = date("Y-m-d H:i:s.u", $t);
echo "Start extracting... : " . $d . "\n";

// Create new zip class
$zip = new ZipArchive;

// Add zip filename which need to unzip
$zip->open( $path . $file);

// Extracts to directory
$zip->extractTo( $path . 'data/');

$zip->close();

$t=time();
$d = date("Y-m-d H:i:s.u", $t);
echo "Extract successfully: " . $d . "\n";
echo "From ZIP file: " . $path . $file . "\n";

?>

