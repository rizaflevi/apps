<?php
// panggil fungsi validasi xss dan injection
// prs_fp_db_connect.php

$config_path = dirname(__DIR__) . '/.rrrini/Rainbow.ini';
$CONFIG_INI = parse_ini_file($config_path);

//$CONFIG_INI = parse_ini_file("Rainbow.ini");
$server = $CONFIG_INI['DBSERVER'];
$username = $CONFIG_INI['DBUSER'];
$password = $CONFIG_INI['DBPASS'];
$c_DB_FP = "kasitu";

$DB1=mysql_connect($server,$username,$password, true) or die("Can not connect to database");
mysql_select_db($c_DB_FP, $DB1) or die("Can not connect to database");

?>
