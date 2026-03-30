<?php
//	tb_all_leave.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$sPERIOD1=date("Y-m-d");
	if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Cuti Bersama.pdf';
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cHEADER 	= S_MSG('TH31','Table Cuti Bersama');
	$can_CREATE = TRUST($cUSERCODE, 'TB_ALL_LEAVE_1ADD');

	$qQUERY=OpenTable('TbAllLeave', "year(START_DATE)=".substr($sPERIOD1,0,4). " and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];
	$cLEAVE_DESC 	= S_MSG('TH32', 'Cuti Bersama');
	$cLEAVE_START   = S_MSG('TH03', 'Mulai tanggal');
	$cLEAVE_FINIS   = S_MSG('TH04', 'Sampai tanggal');

	$cSAVE		= S_MSG('F301','Save');

	$cTTIP_NAMA		= S_MSG('TH46','Keterangan Cuti Bersama');
	$cTTIP_DATE1	= S_MSG('TH47','Tanggal mulai Cuti Bersama');
	$cTTIP_DATE2	= S_MSG('TH48','Tanggal akhir Cuti Bersama');

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'view');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE ? ['<a href="?_a='. md5('CREATE_NEW'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TABLE('example');
					THEAD([$cLEAVE_DESC, $cLEAVE_START, $cLEAVE_FINIS]);
					echo '<tbody>';
						while($aREC_LEAVE=SYS_FETCH($qQUERY)) {
							$aCOL=[$aREC_LEAVE['LEAVE_DESC'], date("d-M-Y", strtotime($aREC_LEAVE['START_DATE'])), date("d-M-Y", strtotime($aREC_LEAVE['FINISH_DATE']))];
							TDETAIL($aCOL, [], '', ["<a href='?_a=".md5('ALL_LEAVE_UPDATE')."&_id=$aREC_LEAVE[REC_ID]'>", '', '']);
						}
					echo '</tbody>';
				eTABLE();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('CREATE_NEW'):
		$cADD_NEW		= S_MSG('TH36', 'Tambah Cuti Bersama');
		DEF_WINDOW($cADD_NEW);
			TFORM($cADD_NEW, '?_a=DB_ADD', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cLEAVE_DESC);
					INPUT('text', [8,8,8,6], '900', 'ADD_LEAVE_DESC', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([3,3,3,6], '700', $cLEAVE_START);
					INPUT_DATE([2,2,2,6], '900', 'ADD_START_DATE', date('Y-m-d'), '', '', '', 0, '', 'fix', $cTTIP_DATE1);
					LABEL([3,3,3,6], '700', $cLEAVE_FINIS);
					INPUT_DATE([2,2,2,6], '900', 'ADD_FINIS_DATE', date('Y-m-d'), '', '', '', 0, '', 'fix', $cTTIP_DATE2);
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('ALL_LEAVE_UPDATE'):
        $cID=$_GET['_id'];
		$can_UPDATE = TRUST($cUSERCODE, 'TB_ALL_LEAVE_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'TB_ALL_LEAVE_3DEL');
		$cEDIT_TBL		= S_MSG('TH37', 'Edit Cuti Bersama');
		$qQUERY=OpenTable('TbAllLeave', "APP_CODE='$cAPP_CODE' and REC_ID='$cID' and REC_ID not in ( select DEL_ID from logs_delete)");
		$aUPD_REC=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DELETE_REC').'&_id='. $cID. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=DB_UPDATE&_id='.$cID, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cLEAVE_START);
					INPUT_DATE([2,2,2,6], '900', 'EDIT_START_DATE', $aUPD_REC['START_DATE'], '', '', '', 0, '', 'fix', $cTTIP_DATE1);
					LABEL([3,3,3,6], '700', $cLEAVE_FINIS);
					INPUT_DATE([2,2,2,6], '900', 'EDIT_FINIS_DATE', $aUPD_REC['FINISH_DATE'], '', '', '', 0, '', 'fix', $cTTIP_DATE2);
					LABEL([3,3,3,6], '700', $cLEAVE_DESC);
					INPUT('text', [8,8,8,6], '900', 'EDIT_LEAVE_DESC', $aUPD_REC['LEAVE_DESC'], '', '', '', 0, '', 'fix');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case md5('DELETE_REC'):
	RecSoftDel($_GET['_id']);
	APP_LOG_ADD($cHEADER, 'delete : '.$_GET['_id']);
	header('location:tb_all_leave.php');
	break;
	
	
case "DB_UPDATE":
	$dTG_MULAI = $_POST['EDIT_START_DATE'];		// 'dd/mm/yyyy'
	$dTG_AKHIR = $_POST['EDIT_FINIS_DATE'];		// 'dd/mm/yyyy'
	$KODE_CRUD=$_GET['_id'];
	$cLEAVE_DESC	= ENCODE($_POST['EDIT_LEAVE_DESC']);	
	RecUpdate('TbAllLeave', ['LEAVE_DESC', 'START_DATE', 'FINISH_DATE'], 
		[$cLEAVE_DESC, $dTG_MULAI, $dTG_AKHIR], "REC_ID='$KODE_CRUD'");
	APP_LOG_ADD($cHEADER, 'update : '.$KODE_CRUD);
	header('location:tb_all_leave.php');

	break;
	
	
case "DB_ADD":
	$dTG_MULAI = $_POST['ADD_START_DATE'];		// 'dd/mm/yyyy'
	$dTG_AKHIR = $_POST['ADD_FINIS_DATE'];		// 'dd/mm/yyyy'
	$cLEV_DESC = $_POST['ADD_LEAVE_DESC'];
	if($cLEV_DESC==''){
		MSG_INFO(S_MSG('TH33','Keterangan Cuti bersama belum diisi'));
		return;
	}
    RecCreate('TbAllLeave', ['LEAVE_DESC', 'START_DATE', 'FINISH_DATE', 'ENTRY', 'REC_ID', 'APP_CODE'], 
        [ENCODE($_POST['ADD_LEAVE_DESC']), $dTG_MULAI, $dTG_AKHIR, $_SESSION['gUSERCODE'], NowMSecs(), $cAPP_CODE]);
	APP_LOG_ADD($cHEADER, 'add : Collective leave : '.$cLEV_DESC);
    header('location:tb_all_leave.php');
}
?>

