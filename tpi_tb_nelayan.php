<?php
//	tpi_tb_nelayan.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 	= $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Nelayan.pdf';

	$qQUERY=OpenTable('TbFishr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

	$cHEADER 		= S_MSG('TF51','Tabel Nelayan');
	$cKODE_TBL 		= S_MSG('TF52','Kode Nelayan');
	$cNAMA_TBL 		= S_MSG('TF53','Nama Nelayan');
	$cNAMA_KAPAL 	= S_MSG('TF46','Nama Kapal');
	$cNAMA_NAHODA 	= S_MSG('TF47','Nama Nakhoda');
	$cNOMOR_HP 		= S_MSG('TF54','No. HP');
	$cEMAIL_ADR		= S_MSG('TF77','Email');
	$cPORT_NUM 		= S_MSG('TF55','Port no.');
	$cSMS_Q_IN 		= S_MSG('TF57','SMS masuk antrian');
	$cSMS_FINISH	= S_MSG('TF58','SMS selesai timbang');
	$cNO_HP_SMS		= S_MSG('TF48','No. HP SMS');
	$cANTRIAN		= S_MSG('TF59','Antrian');
	
	$cTTIP_PORT		= S_MSG('TF65','Nomor port sms gateway nelayan untuk kirim berita SMS');
	$cTTIP_SMSN		= S_MSG('TF67','Centang jika diinginkan dikirim SMS sewaktu memasuki area deteksi GPS');
	$cTTIP_TIMB		= S_MSG('TF68','Centang jika diinginkan dikirim SMS sewaktu selesai proses timbang');
	$cTTIP_NOHP		= S_MSG('TF66','Nomor HP nelayan');
	
	$cDAFTAR		= S_MSG('TF70','Daftar Nelayan');
	
	$cSAVE_DATA=S_MSG('F301','Save');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
		$can_CREATE = TRUST($cUSERCODE, 'TPI_TTB_FISHR_1ADD');
		$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
		DEF_WINDOW($cHEADER);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_KAPAL, $cNAMA_TBL]);
						echo '<tbody>';
							while($aREC_FISHR=SYS_FETCH($qQUERY)) {
								echo '<tr>';
									echo '<td style="width: 1px;"></td>';
									echo "<td><span><a href='?_a=".md5('up__date')."&_n=".md5($aREC_FISHR['FISHR_CODE'])."'>".$aREC_FISHR['FISHR_CODE']."</a></span></td>";
									echo "<td><span><a href='?_a=".md5('up__date')."&_n=".md5($aREC_FISHR['FISHR_CODE'])."'>".decode_string($aREC_FISHR['FISHR_NAME'])."</a></span></td>";
									echo "<td><span><a href='?_a=".md5('up__date')."&_n=".md5($aREC_FISHR['FISHR_CODE'])."'>".decode_string($aREC_FISHR['FISHR_JRGN'])."</a></span></td>";
								echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('cr34t3'):
		$cADD_REC	= S_MSG('TF61','Tambah Nelayan');
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'ADD_FISHR_CODE', '', 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_KAPAL);
					INPUT('text', [6,6,6,6], '900', 'ADD_SHIP_NAME', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'ADD_FISHR_JRGN', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_NAHODA);
					INPUT('text', [6,6,6,6], '900', 'ADD_FISHR_CAPT', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNOMOR_HP);
					INPUT('text', [4,4,4,6], '900', 'ADD_FISHR_CELL', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cEMAIL_ADR);
					INPUT('text', [6,6,6,6], '900', 'ADD_FISHR_EMAIL', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cPORT_NUM);
					INPUT('text', [1,1,1,6], '900', 'ADD_PORT_NMBR', '', '', '', '', 0, '', 'fix', $cTTIP_PORT);
					LABEL([3,3,3,6], '700', $cSMS_Q_IN);
					INPUT('checkbox', [1,1,1,1], '900', 'ADD_SMS_IN', false, '', '', '', 0, '', 'fix', $cTTIP_SMSN, false);
					LABEL([3,3,3,6], '700', $cSMS_FINISH);
					INPUT('checkbox', [1,1,1,1], '900', 'ADD_SMS_TMBANG', false, '', '', '', 0, '', 'fix', $cTTIP_TIMB, false);
					LABEL([3,3,3,6], '700', $cNO_HP_SMS);
					INPUT('text', [4,4,4,6], '900', 'ADD_FISHR_SMS', '', '', '', '', 0, '', 'fix', $cTTIP_NOHP);
					LABEL([3,3,3,6], '700', $cANTRIAN);
					INPUT('checkbox', [1,1,1,1], '900', 'ADD_FISHR_QUE', false, '', '', '', 0, '', 'fix', '', false);
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('up__date'):
		$cEDIT_TBL		= S_MSG('TF49','Edit Tabel Nelayan');
		$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$can_UPDATE = TRUST($cUSERCODE, 'TPI_TTB_FISHR_2UPD');
		$qQUERY=OpenTable('TbFishr', "md5(FISHR_CODE)='".$_GET['_n']."' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$REC_FISHR=SYS_FETCH($qQUERY);
		$can_DELETE = TRUST($cUSERCODE, 'TPI_TTB_FISHR_3DEL');
		$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('dFISHR').'&_id='. $REC_FISHR['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
		DEF_WINDOW($cEDIT_TBL);
			TFORM($cEDIT_TBL, '?_a=rubah&_id='.$REC_FISHR['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_FISHR_CODE', $REC_FISHR['FISHR_CODE'], '', '', '', 0, 'disabled', 'fix');
					LABEL([4,4,4,6], '700', $cNAMA_KAPAL);
					INPUT('text', [6,6,6,6], '900', 'EDIT_SHIP_NAME', DECODE($REC_FISHR['FISHR_NAME']), 'focus', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'EDIT_FISHR_JRGN', DECODE($REC_FISHR['FISHR_JRGN']), '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cNAMA_NAHODA);
					INPUT('text', [6,6,6,6], '900', 'EDIT_FISHR_CAPT', DECODE($REC_FISHR['FISHR_CAPT']), '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cNOMOR_HP);
					INPUT('text', [6,6,6,6], '900', 'EDIT_FISHR_CELL', DECODE($REC_FISHR['FISHR_CELL']), '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cEMAIL_ADR);
					INPUT('text', [6,6,6,6], '900', 'EDIT_FISHR_EMAIL', DECODE($REC_FISHR['FISHR_EMAIL']), '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cPORT_NUM);
					INPUT('text', [1,1,1,6], '900', 'EDIT_PORT_NMBR', DECODE($REC_FISHR['PORT_NMBR']), '', '', '', 0, '', 'fix', $cTTIP_PORT);
					LABEL([4,4,4,6], '700', $cSMS_Q_IN);
					INPUT('checkbox', [1,1,1,1], '900', 'EDIT_SMS_IN', $REC_FISHR['SMS_IN']==1, '', '', '', 0, '', 'fix', $cTTIP_SMSN, $REC_FISHR['SMS_IN']==1);
					LABEL([4,4,4,6], '700', $cSMS_FINISH);
					INPUT('checkbox', [1,1,1,1], '900', 'EDIT_SMS_TMBANG', $REC_FISHR['SMS_TMBANG']==1, '', '', '', 0, '', 'fix', $cTTIP_TIMB, $REC_FISHR['SMS_TMBANG']==1);
					LABEL([4,4,4,6], '700', $cNO_HP_SMS);
					INPUT('text', [4,4,4,6], '900', 'EDIT_FISHR_SMS', DECODE($REC_FISHR['FISHR_SMS']), '', '', '', 0, '', 'fix', $cTTIP_NOHP);
					LABEL([4,4,4,6], '700', $cANTRIAN);
					INPUT('checkbox', [1,1,1,1], '900', 'EDIT_FISHR_QUE', $REC_FISHR['FISHR_QUE']==1, '', '', '', 0, '', 'fix', $cTTIP_TIMB, $REC_FISHR['FISHR_QUE']==1);
					SAVE($can_UPDATE==1 ? $cSAVE_DATA : '');
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

case 'tambah':
	$cCODE = ENCODE($_POST['ADD_FISHR_CODE']);
	if($cCODE==''){
		MSG_INFO(S_MSG('TF78','Kode Nelayan belum diisi'));
		return;
	}
	$qQUERY=OpenTable('TbFishr', "FISHR_CODE='$cCODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qQUERY)>0){
		MSG_INFO(S_MSG('TF79','Kode Nelayan sudah ada'));
		return;
	} else {
		$cNAME = ENCODE($_POST['ADD_SHIP_NAME']);
		$cKAPL = ENCODE($_POST['ADD_FISHR_JRGN']);
		$cCAPT = ENCODE($_POST['ADD_FISHR_CAPT']);
		$cMAIL = ENCODE($_POST['ADD_FISHR_EMAIL']);
		RecCreate('TbFishr', ['FISHR_CODE', 'FISHR_NAME', 'FISHR_JRGN', 'FISHR_CAPT', 'FISHR_CELL', 'FISHR_EMAIL', 'PORT_NMBR', 'APP_CODE', 'ENTRY', 'REC_ID'], 
			[$cCODE, $cNAME, $cKAPL, $cCAPT, $_POST['ADD_FISHR_CELL'], $cMAIL, '0'.str_replace(',', '', $_POST['ADD_PORT_NMBR']), $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		APP_LOG_ADD("tambah table nelayan", $cCODE);
		header('location:tpi_tb_nelayan.php');
	}
	break;

case 'rubah':
	$KODE_CRUD=$_GET['_id'];
	$lSMS_IN = (isset($_POST['EDIT_SMS_IN']) ? 1 : 0);
	$lSMS_TM = (isset($_POST['EDIT_SMS_TMBANG']) ? 11 : 0);
	$lSMS_QU = (isset($_POST['EDIT_FISHR_QUE']) ? 1 : 0);
	$cCAPT = (isset($_POST['EDIT_FISHR_CAPT']) ? $_POST['EDIT_FISHR_CAPT'] : '');
	$cNAME = ENCODE($_POST['EDIT_SHIP_NAME']);
	$cJRGN = ENCODE($_POST['EDIT_FISHR_JRGN']);
	RecUpdate('TbFishr', ['FISHR_NAME', 'FISHR_JRGN', 'FISHR_CAPT', 'FISHR_CELL', 'FISHR_EMAIL', 'PORT_NMBR', 'SMS_IN', 'FISHR_QUE', 'FISHR_SMS'], 
		[$cNAME, $cJRGN, $cCAPT, $_POST['EDIT_FISHR_CELL'], $_POST['EDIT_FISHR_EMAIL'], '0'.str_replace(',', '', $_POST['EDIT_PORT_NMBR']), $lSMS_IN, $lSMS_QU, $_POST['EDIT_FISHR_SMS']], "REC_ID='$KODE_CRUD'");
	APP_LOG_ADD('edit table nelayan', 'tpi_tb_nelayan.php');
	header('location:tpi_tb_nelayan.php');
	break;

case md5('dFISHR'):
	$KODE_CRUD=$_GET['_id'];
	RecSoftDel($KODE_CRUD);
	APP_LOG_ADD('delete table nelayan : '.$KODE_CRUD, 'tpi_tb_nelayan.php');
	header('location:tpi_tb_nelayan.php');
}
?>

