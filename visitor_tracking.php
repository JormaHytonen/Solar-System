<?php

/**
* This is the description for Solar System Visitor Tracking
*
* @package    Visitors
* @subpackage solar-system
* @author     Datatuki, Jorma Hytönen
* @version    1.12
* @source     http://geeklabel.com/tutorial/track-visitors-php-tutorial/
*
*/

require_once('visitors_connections.php');   //the file with connection code and functions

if(!defined('TIMEZONE')) {
    define('TIMEZONE', 'Europe/Helsinki');
}
date_default_timezone_set(TIMEZONE);

/**
 * Test IP address
 */
$url = "datatuki.net/server/INFO/ipInfo.txt";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$fileIP = curl_exec($ch);
curl_close($ch);
$myIP = strstr($fileIP, '@', true);

// get the required data
$visitor_ip = GetHostByName($_SERVER['REMOTE_ADDR']);
$visitor_browser = getBrowserType();
$visitor_hour = date("h");
$visitor_minute = date("i");
$visitor_day = date("d");
$visitor_month = date("m");
$visitor_year = date("Y");
$visitor_refferer = GetHostByName($_SERVER['HTTP_REFERER']);
$visitor_page = selfURL();
$visitor_date = date('Y-m-d H:i:s');

if ($visitor_ip == $myIP) {
    // Do NOT track myIP
    // print_r("&nbsp;&nbsp;My IP : " . $visitor_ip);
    return false;
}

$sql = "INSERT INTO visitors_table (visitor_ip, visitor_browser, visitor_hour,
 visitor_minute, visitor_date, visitor_day, visitor_month, visitor_year,
 visitor_refferer, visitor_page) VALUES ('$visitor_ip', '$visitor_browser',
 '$visitor_hour', '$visitor_minute', '$visitor_date', '$visitor_day', '$visitor_month',
 '$visitor_year', '$visitor_refferer', '$visitor_page')";

$result = $dbh->query($sql);
