<?php

$server = "localhost";
// $server = "36.69.104.35";
$username = "user1";
$password = "user1";
$database1 = "sys_data";
$database2 = "tpi";

// $DB2=mysql_connect($server,$username,$password) or die("Koneksi gagal");
$DB1=mysql_connect($server,$username,$password, true) or die("Koneksi gagal");
// mysql_select_db($database2, $DB2) or die("Database tidak bisa dibuka");
mysql_select_db($database1, $DB1) or die("Database tidak bisa dibuka");


?>
