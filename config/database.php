<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbnm = "apotek_integrated_shift";

mysql_connect($host, $user, $pass) or die(mysql_error());
mysql_select_db($dbnm) or die(mysql_error());
?>
