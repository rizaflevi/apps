<?php
//	bm_mbl_login.php
//	Login from mobile

	$server 	= 'localhost';
	$database 	= 'payroll';			// ini untuk sistem utama
	$username 	= 'user1';
	$password 	= 'user1';
	$DB2=mysql_connect($server,$username,$password) or die("Can not open DB2");
	mysql_select_db($database, $DB2) or die("Can not open DB2");
	if (isset($_GET['hp'])) {
		$cHP = $_GET['hp'];
		$q_TB_CATTER	= mysql_query("select NOMOR_HP from bm_tb_catter1  where NOMOR_HP = '$cHP' and  APP_CODE='YAZA' and DELETOR=''");
		$a_TB_CATTER = mysql_fetch_array($q_TB_CATTER);
		$rows[] = $a_TB_CATTER;
		echo json_encode($rows);
	}
?>
