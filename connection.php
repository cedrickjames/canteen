<?php

$dbhost = "192.168.5.6";
$dbuser = "CanteenSystem";
$dbpass = "dyBkxSm&jJZ4";
$dbname = "canteen_transactions";

if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)){
    die("Failed to Connect to Database!");
}

?>
