<?php
//	bm_cek_plg.php
//	cek validasi bm_tb_plg

$config_path = dirname(__DIR__) . '/.rrrini/Rainbow.ini';
$CONFIG_INI = parse_ini_file($config_path);

//$CONFIG_INI = parse_ini_file("Rainbow.ini");

	$server 	= $CONFIG_INI['DBSERVER'];
	$database 	= $CONFIG_INI['DBPAYROLL'];
	$username 	= $CONFIG_INI['DBUSER'];
	$password 	= $CONFIG_INI['DBPASS'];
	$DB2=mysql_connect($server,$username,$password) or die("Can not open DB2");
	mysql_select_db($database, $DB2) or die("Can not open DB2");

/*	$cSQLCOMMAND= "SELECT A.TGL_BACA, A.IDPEL, A.SISA_TOKEN, A.PETUGAS, A.KODE_RBM, B.IDPEL
		from bm_dt_baca A
		left join bm_tb_plg B ON B.IDPEL=A.IDPEL 
		where A.APP_CODE='YAZA' and A.DELETOR='' order by A.TGL_BACA desc limit 10";
*/
	$cFILTER_CODE = 'YAZA';

	$nADD = 0;
	$nUPD = 0;
	$nREC = 0;
	$q_DT_BACA=mysql_query("select * from bm_dt_baca where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	while($a_DT_BACA =mysql_fetch_array($q_DT_BACA)) {
		if($a_DT_BACA['IDPEL']!='N/A') {
			$qQUERY = mysql_query("select IDPEL, PETUGAS, KODE_RUTE, LAST_VISIT FROM bm_tb_plg where IDPEL ='$a_DT_BACA[IDPEL]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
			if(mysql_num_rows($qQUERY)==0) {
				$cQ_TB_PLG = "INSERT into bm_tb_plg ( `IDPEL`, `KODE_RUTE`, `PETUGAS`, `APP_CODE`, `LAST_VISIT`, `DATE_ENTRY` ) values ";
				$cQ_TB_PLG .= "('". $a_DT_BACA['IDPEL']."', '". $a_DT_BACA['KODE_RBM']."', '". $a_DT_BACA['PETUGAS']."', '". $cFILTER_CODE  . "', '" . $a_DT_BACA['TGL_BACA']. "', '". $NOW. "'), ";
				$q_ADDR=mysql_query($cQ_TB_PLG);
				$nADD++;
				$cREPLACE 	= mysql_query("update rainbow set KEY_CONTEN='$nADD' WHERE KEY_FIELD='LAST_PND' and APP_CODE='$cFILTER_CODE'");
			} else {
				$cUPD_P="update bm_tb_plg set KODE_RUTE='$a_DT_BACA[KODE_RBM]', PETUGAS='$a_DT_BACA[PETUGAS]', LAST_VISIT='$a_DT_BACA[TGL_BACA]' where IDPEL='$a_DT_BACA[IDPEL]' and APP_CODE='$cFILTER_CODE'";
				$q_UPDP=mysql_query($cUPD_P);
				$nUPD++;
				$cREPLACE 	= mysql_query("update rainbow set KEY_CONTEN='$nUPD' WHERE KEY_FIELD='LAST_PNRM' and APP_CODE='$cFILTER_CODE'");
			}
		}
		$nREC++;
		$cREPLACE 	= mysql_query("update rainbow set KEY_CONTEN='$nREC' WHERE KEY_FIELD='LAST_INV2' and APP_CODE='$cFILTER_CODE'");
	}
	echo 'add : '.$nADD.'<br>';
	echo 'upd : '.$nUPD.'<br>';
	mysql_close($DB2);
	
?>
