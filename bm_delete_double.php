<?php
// bm_delete_double.php
// bm_dt_baca= 25954
// bm_dt_temp= 10.418 rec

//	include "sysfunction.php";
$config_path = dirname(__DIR__) . '/.rrrini/Rainbow.ini';
$CONFIG_INI = parse_ini_file($config_path);

//$CONFIG_INI = parse_ini_file("Rainbow.ini");

	$server 	= $CONFIG_INI['DBSERVER'];
	$database 	= $CONFIG_INI['DBPAYROLL'];
	$username 	= $CONFIG_INI['DBUSER'];
	$password 	= $CONFIG_INI['DBPASS'];
	$DB2=mysql_connect($server,$username,$password) or die("Can not open DB2");
	mysql_select_db($database, $DB2) or die("Can not open DB2");

	$qQ_DTB = mysql_query("SELECT *, COUNT(*) c FROM bm_dt_baca GROUP BY TGL_BACA, IDPEL, SISA_TOKEN, APP_CODE HAVING c > 1 order by REC_ID");
	$n_PEL = 0;
	while($aDATA_TEMP =mysql_fetch_array($qQ_DTB)){
		$n_PEL++;
		$nREC = $aDATA_TEMP['REC_ID'];

		$cQUERY="delete from bm_dt_baca where REC_ID = '$nREC'";
		$q_UPDP=mysql_query($cQUERY);
		echo $nREC.' : '.$aDATA_TEMP['TGL_BACA'].', '.$aDATA_TEMP['IDPEL'].', '.$aDATA_TEMP['PETUGAS'].', '.$aDATA_TEMP['SISA_TOKEN'];
		echo '<br>';
		echo $n_PEL.' deleted ';
	}
			

	mysql_close($DB2);

?>

