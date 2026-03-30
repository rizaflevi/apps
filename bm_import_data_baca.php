<?php
// bm_import_data_baca.php
// bm_tb_pel = 124923
// bm_tb_plg = 131983
// bm_dt_baca= 25664
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

	$cFILTER_CODE = 'YAZA';
	$c_SYS_PARA = "select * from rainbow WHERE KEY_FIELD='LAST_ORDER' and APP_CODE='$cFILTER_CODE'";
	$q_SYS_PARA=mysql_query($c_SYS_PARA);
	$r_SYS_PARA=mysql_fetch_array($q_SYS_PARA);
	$nREC = intval($r_SYS_PARA['KEY_CONTEN']);
			$n_PLG	= 0;
			$cQUERYBACA = "INSERT into bm_dt_baca ( `TGL_BACA`, `SISA_TOKEN`, `LATTI`, `LONGI` , `PETUGAS`, `KONDISI_RMH`, `SEGEL_TLG`, `SEGEL_TER`, `LAMPU_INDI`, `KODE_RBM`, `IDPEL`, `NOTE`, `LOC_FOTO`, `APP_CODE`, `ENTRY` ) values ";
			$cQ_TB_PLG = "INSERT into bm_tb_plg ( `IDPEL`, `KODE_RUTE`, `PETUGAS`, `APP_CODE`, `LAST_VISIT`, `DATE_ENTRY` ) values ";
			$qQ_DTB = mysql_query("select * from bm_dt_temp where PRO_CESS=0 and APP_CODE='$cFILTER_CODE' limit 250");
			$n_PEL = 0;
			while($aDATA_TEMP =mysql_fetch_array($qQ_DTB)){
/*				if($nREC==21923); {
					$nREC=48191;
				}
*/
				$n_PEL++;
				$nREC++;
				$cRAYON = $aDATA_TEMP['UNITUP'];
				$cNMTR = $aDATA_TEMP['NMR_METER'];
				$nNTKN = $aDATA_TEMP['SISA_TOKEN'];

				$cPETUGAS = $aDATA_TEMP['PETUGAS'];
				$nKONDISI = $aDATA_TEMP['KONDISI_RMH'];
				$nSEGEL_TLG = $aDATA_TEMP['SEGEL_TLG'];
				$nSEGEL_TER = $aDATA_TEMP['SEGEL_TER'];
				$nLAMPU_INDI = $aDATA_TEMP['LAMPU_INDI'];
				$cKD_RUT = $aDATA_TEMP['KODE_RBM'];
				$cID_PEL = $aDATA_TEMP['IDPEL'];
				$cNM_PEL = $aDATA_TEMP['NAMA_PEL'];
				$cAL_PEL = $aDATA_TEMP['ALM_PEL'];
				$cKD_TRF = $aDATA_TEMP['KODE_TRF'];
				$nDAYA = $aDATA_TEMP['DAYA'];
				$nREC_NO = $aDATA_TEMP['REC_NO'];

				$cQUERYBACA .= "('". $aDATA_TEMP['TGL_BACA'] . "', " . $nNTKN . ", " . $aDATA_TEMP['LATTI'] . ", " . $aDATA_TEMP['LONGI'] . ", '" . $cPETUGAS . "', " . $nKONDISI . ", " . $nSEGEL_TLG . ", " . $nSEGEL_TER . ", " . $nLAMPU_INDI . ", '" . $cKD_RUT . "', '" . $cID_PEL . "', '" . $aDATA_TEMP['NOTE'] . "', '" . $aDATA_TEMP['LOC_FOTO'] . "', '" . $cFILTER_CODE . "', '". $aDATA_TEMP['ENTRY']."'), ";

				$cQUERY="select * from bm_tb_pel where IDPEL='$cID_PEL' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
				$qQUERY=mysql_query($cQUERY);
				if(mysql_num_rows($qQUERY)==0) {
					$NOW = date("Y-m-d H:i:s");
					$cQ_ADD_PEL = "insert into bm_tb_pel set UNITUP='".$cRAYON."', IDPEL='". $cID_PEL."', NAMA_PEL='". $cNM_PEL."', ALAMAT='". $cAL_PEL."', KODE_TARIF='". $cKD_TRF. "', DAYA=". $nDAYA. ", NOMOR_KWH='". $cNMTR. "', LAST_VISIT_BY='". $cPETUGAS. "', LAST_VISIT_DATE='". $cDB_TGL."', APP_CODE='" . $cFILTER_CODE  . "', ENTRY='import', DATE_ENTRY='". $NOW. "'";
					$q_ADDN=mysql_query($cQ_ADD_PEL);
				}

				$cQUERY="select * from bm_tb_plg where IDPEL='$cID_PEL' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
				$qQUERY=mysql_query($cQUERY);
				if(mysql_num_rows($qQUERY)==0) {
					$n_PLG = 1;
					$NOW = date("Y-m-d H:i:s");
					$cQ_TB_PLG .= "('". $cID_PEL."', '". $cKD_RUT."', '". $cPETUGAS."', '". $cFILTER_CODE  . "', '" . $aDATA_TEMP['TGL_BACA']. "', '". $NOW. "'), ";
				} else {
					$cUPD_P="update bm_tb_plg set KODE_RUTE='$cKD_RUT', PETUGAS='$cPETUGAS', LAST_VISIT='$aDATA_TEMP[TGL_BACA]' where IDPEL='$cID_PEL' and APP_CODE='$cFILTER_CODE'";
					$q_UPDP=mysql_query($cUPD_P);
				}
					
				$NOW = date("Y-m-d H:i:s");
				$cQUERY="update bm_dt_temp set PRO_CESS=1, UP_DATE='". $NOW . "' where REC_NO='$nREC_NO'";
				$q_UPDP=mysql_query($cQUERY);
			}
			
			if($n_PEL>0) {
				$cQUERYBACA .= "; ";
				$cQUERYBACA = str_replace( ", ;" , ";", $cQUERYBACA );
				$qQUERYBACA = mysql_query($cQUERYBACA);
			}

			if($n_PLG==1) {
				$cQ_TB_PLG .= "; ";
				$cQ_TB_PLG = str_replace( ", ;" , ";", $cQ_TB_PLG );
				$qQ_TB_PLG = mysql_query($cQ_TB_PLG);
			}

//			echo $nREC.' records has been Imported';
//	$cREPLACE 	= mysql_query("update rainbow set KEY_CONTEN='$nREC' WHERE KEY_FIELD='LAST_ORDER' and APP_CODE='$cFILTER_CODE'");
	mysql_close($DB2);

?>

