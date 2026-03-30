<?php
//	tb_ratio.php 
// tabel rasio keuangan

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
$cUSERCODE = $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Tabel - Rasio.pdf';
$can_CREATE = TRUST($cUSERCODE, 'TB_RASIO_1ADD');

$cHEADER 	= S_MSG('TR01','Tabel Rasio Keuangan');
$cKD_TBL  	= S_MSG('TR02','Kode');
$cNM_TBL  	= S_MSG('TR03','Ratio');
$cKETRANGAN = S_MSG('TR04','Keterangan');
$cIDEAL	 	= S_MSG('TR05', 'Ideal');
$cPEMBILANG = S_MSG('TR11','Pembilang');
$cOPERAN 	= S_MSG('TR13','Operan');
$cPENYEBUT 	= S_MSG('TR12','Penyebut');
$cDAFTAR	= S_MSG('TR30','Daftar Rasio Keuangan');
$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
$cADD_TBL	= S_MSG('TR32','Tambah Tabel Ratio');
$aOPERAND 	= array(1=> '+', '-', '*', '/');

$qQUERY=OpenTable('TbRatio');

$cACTION=((isset($_GET['_a'])) ? $_GET['_a'] : '');

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER);
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKD_TBL, $cNM_TBL, $cKETRANGAN]);
						echo '<tbody>';
							while($aREC_RATIO=SYS_FETCH($qQUERY)) {
								$cHREFF="<a href='?_a=".md5('UPD_RASIO')."&_c=".$aREC_RATIO['KODE_RTO']."'>";
								$aCOL = [$aREC_RATIO['KODE_RTO'], DECODE($aREC_RATIO['NAMA_RTO']), $aREC_RATIO['KETERANGAN']];
								TDETAIL($aCOL, [], '', [$cHREFF, $cHREFF, '']);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('cr34t3'):
		DEF_WINDOW($cADD_TBL);
			TFORM($cADD_TBL, '?_a=tambah');
				TDIV();
					LABEL([3,3,3,6], '700', $cKD_TBL);
					INPUT('text', [2,2,2,6], '900', 'ADD_KODE_RTO', '', 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNM_TBL);
					INPUT('text', [6,6,6,6], '900', 'ADD_NAMA_RTO', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cKETRANGAN);
					INPUT('text', [6,6,6,6], '900', 'ADD_KETERANGAN', '', '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('UPD_RASIO'):
		$can_DELETE = TRUST($cUSERCODE, 'TB_RASIO_3DEL');
		$cEDIT_TBL	= S_MSG('TR31','Edit Tabel Rasio');
		$qQUERY = OpenTable('TbRatio', "KODE_RTO='$_GET[_c]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$REC_RATIO=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=DEL_RASIO&_id='. $REC_RATIO['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_id='.$REC_RATIO['KODE_RTO'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKD_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_KODE_RTO', $REC_RATIO['KODE_RTO'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,6], '700', $cNM_TBL);
					INPUT('text', [6,6,6,6], '900', 'EDIT_NAMA_RTO1', $REC_RATIO['NAMA_RTO'], 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cKETRANGAN);
					INPUT('text', [6,6,6,6], '900', 'UPD_KETERANGAN1', $REC_RATIO['KETERANGAN'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cIDEAL);
					INPUT('text', [1,1,1,2], '900', 'EDIT_IDL', $REC_RATIO['IDL']);
					INPUT('text', [2,2,2,3], '900', 'EDIT_IDEAL', $REC_RATIO['IDEAL']);
					LABEL([1,1,1,3], '700', '%');
					CLEAR_FIX();
					LABEL([4,4,4,3], '700', $cPEMBILANG);
					LABEL([1,1,1,3], '700', $cOPERAN);
					LABEL([1,1,1,3], '700', '');
					LABEL([3,3,3,3], '700', $cPENYEBUT);
					LABEL([1,1,1,3], '700', '');
					LABEL([1,1,1,3], '700', $cOPERAN);
					for ($nI=1; $nI<=9; $nI++){
						$cJ=(string)$nI;
						echo '<select name="EDIT_PEMBILANG"'.$cJ.' class="col-sm-3 form-label-900">';
						echo "<option value=' '  > </option>";
						$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'ACCOUNT_NO');
						while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
							if($REC_RATIO['PEMBILANG'.$cJ] == $aREC_ACCOUNT['ACCOUNT_NO'])
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected>".decode_string($aREC_ACCOUNT['ACCT_NAME'])."</option>";
							else
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".decode_string($aREC_ACCOUNT['ACCT_NAME'])."</option>"; 
						}
						echo '</select>';
						LABEL([1,1,1,3], '700', '');
						echo '<select name="EDIT_OPR_BIL'.$cJ.'" class="col-sm-1 form-label-900">';

						echo "<option value=' '  > </option>";
						for ($I=1; $I<=4; $I++){
							if ($aOPERAND[$I] == $REC_RATIO['OPR_PMB'.$cJ])
								echo "<option class='form-label-900' value='$aOPERAND[$I]' selected>".$REC_RATIO['OPR_PMB'.$cJ]."</option>";
							else
								echo "<option class='form-label-900' value='$aOPERAND[$I]' >$aOPERAND[$I]</option>";
						}
						echo '</select>';
						LABEL([1,1,1,3], '700', '');
						echo '<select name="EDIT_PENYEBUT'.$cJ.'" class="col-sm-3 form-label-900">';
						echo "<option value=' '  > </option>";
						$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'ACCOUNT_NO');
						while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
							if($REC_RATIO['PENYEBUT'.$cJ] == $aREC_ACCOUNT['ACCOUNT_NO'])
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected>".decode_string($aREC_ACCOUNT['ACCT_NAME'])."</option>";
							else
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".decode_string($aREC_ACCOUNT['ACCT_NAME'])."</option>"; 
						}
						echo '</select>';
						LABEL([1,1,1,1], '700', '');
						echo '<select name="EDIT_OPR_SEB'.$cJ.'" class="col-sm-1 form-label-900">';
						echo "<option value=' '  > </option>";
						for ($I=1; $I<=4; $I++){
							if ($aOPERAND[$I] == $REC_RATIO['OPR_PNB'.$cJ])
								echo "<option class='form-label-900' value=$aOPERAND[$I] selected>".$REC_RATIO['OPR_PNB'.$cJ]."</option>";
							else
								echo "<option class='form-label-900' value=$aOPERAND[$I]>$aOPERAND[$I]</option>";
						}
						echo '</select>';
					}
				SAVE(S_MSG('F301','Save'));
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case 'tambah':
	if($_POST['ADD_KODE_RTO']=='') {
		MSG_INFO(S_MSG('TR33','Kode  Ratio tidak boleh kosong'));
		return;
	}
	$qQUERY = OpenTable('TbRatio', "KODE_RTO='$_POST[ADD_KODE_RTO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qQUERY)>0){
		MSG_INFO(S_MSG('TR34','Kode Ratio sudah ada'));
		return;
	} else {
		$cRATIO_CODE = ENCODE($_POST['ADD_KODE_RTO']);
		$cRATIO_NAME = ENCODE($_POST['ADD_NAMA_RTO']);
		$cRATIO_NOTE = ENCODE($_POST['ADD_KETERANGAN']);
		RecCreate('TbRatio', ['KODE_RTO', 'NAMA_RTO', 'KETERANGAN', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cRATIO_CODE, $cRATIO_NAME, $cRATIO_NOTE, $_SESSION['gUSERCODE'], $cAPP_CODE, NowMSecs()]);
	}
	header('location:tb_ratio.php');
	break;
case 'rubah':
	$KODE_CRUD=$_GET['_id'];
	$qQUERY = OpenTable('TbRatio', "KODE_RTO='$_GET[_id]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if($aREC_RATIO=SYS_FETCH($qQUERY)) {
		$cPEMBL1=(($_POST['EDIT_PEMBILANG1']) ? $_POST['EDIT_PEMBILANG1'] : $aREC_RATIO['PEMBILANG1']);
		$cRATIO_NAME = ENCODE($_POST['EDIT_NAMA_RTO1']);
		$cRATIO_NOTE = ENCODE($_POST['UPD_KETERANGAN1']);
		RecUpdate('TbRatio', ['NAMA_RTO', 'KETERANGAN', 'PEMBILANG1', 'OPR_PMB1', 'PENYEBUT1', 'OPR_PNB1'], 
			[$cRATIO_NAME, $cRATIO_NOTE, $cPEMBL1, $_POST['EDIT_OPR_BIL1'], $_POST['EDIT_PENYEBUT1'], $_POST['EDIT_OPR_SEB1']], 
			"APP_CODE='$cAPP_CODE' and KODE_RTO='$KODE_CRUD'");
	}
	header('location:tb_ratio.php');
	break;
case 'DEL_RASIO':
	RecSoftDel($_GET['_id']);
	header('location:tb_ratio.php');
	break;
}
?>

