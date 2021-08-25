<?php

/* **
https://www.geeksforgeeks.org/how-to-zip-a-directory-in-php/
** */

if( !defined('TIMEZONE') ) {
    define('TIMEZONE', 'Europe/Helsinki');
    date_default_timezone_set(TIMEZONE);
}

$t=time();
$d = date("Y-m-d H:i:s.u", $t);
echo "Start compress....... : " . $d . "\n";

// Enter the name of directory
$pathdir = "/data/htdocs/fmiweb/FI101586-all/";

// Enter the name to creating zipped directory
$zipcreated = "/data/htdocs/fmiweb/FI101586-zip/fmi-all.zip";

// Create new zip class
$zip = new ZipArchive;

if($zip -> open($zipcreated, ZipArchive::CREATE ) === TRUE) {

    // Store the path into the variable
    $dir = opendir($pathdir);

    while($file = readdir($dir)) {
        if(is_file($pathdir.$file)) {
            $zip -> addFile($pathdir.$file, $file);
        }
    }
    $zip ->close();
}

$t=time();
$d = date("Y-m-d H:i:s.u", $t);
echo "Compressed succesfully: " . $d . "\n";
echo "ZIP Created: " . $zipcreated . "\n";

?>

