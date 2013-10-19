<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbnm = "sendang_agung_17_10_2013";

mysql_connect($host, $user, $pass) or die(mysql_error());
mysql_select_db($dbnm) or die(mysql_error());
?>
