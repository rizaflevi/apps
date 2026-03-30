<?php
//	tb_status.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Status.pdf';
	$cHEADER = S_MSG('PM51','Tabel Status');

	$qTB_STTS=OpenTable('TbStatus', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION='';
	$cACTION=(isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');

	$cKODE_TBL 	= S_MSG('PM61','Kode Status');
	$cNAMA_TBL 	= S_MSG('PM62','Nama Status');
	$cADD_REC	= S_MSG('PM63','Tambah Status');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cEDIT_TBL	= S_MSG('PM64','Edit Status');
	$cSAVE		= S_MSG('F301','Save');

	$cTTIP_KODE	= S_MSG('PM61','Setiap Status diberi kode supaya bisa dikelompokkan');
	$cTTIP_NAMA	= S_MSG('PM62','Nama Status sbg keterangan');
	
switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'STATUS_1ADD');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL]);
						echo '<tbody>';
							while($aREC_TB_STTS=SYS_FETCH($qTB_STTS)) {
								$cHREFF="<a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_STTS['KODE'])."'>";
								$aCOL = [$aREC_TB_STTS['KODE'], DECODE($aREC_TB_STTS['NAMA'])];
								TDETAIL($aCOL, [], '', [$cHREFF, $cHREFF]);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('cr34t3'):
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'ADD_KODE', '', 'focus', '', '', 0, '', 'fix', $cTTIP_KODE);
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'ADD_NAMA', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('up_d4t3'):
		$can_UPDATE = TRUST($cUSERCODE, 'STATUS_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'STATUS_3DEL');
		$qTB_STTS=OpenTable('TbStatus', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and md5(KODE)='$_GET[_r]' ");
		$aREC_TB_STTS=SYS_FETCH($qTB_STTS);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_area').'&_id='. $aREC_TB_STTS['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_id='.$aREC_TB_STTS['KODE'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_KODE', $aREC_TB_STTS['KODE'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'EDIT_NAMA', DECODE($aREC_TB_STTS['NAMA']), 'focus', '', '', 0, '', 'fix');
					SAVE($can_UPDATE==1 ? $cSAVE : '');
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

case 'tambah':
	$cKODE	= ENCODE($_POST['ADD_KODE']);	
	if($cKODE==''){
		MSG_INFO(S_MSG('PM55','Kode Status belum diisi'));
		return;
	}
	$qTB_STTS=OpenTable('TbStatus', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and KODE='$cKODE' ");
	if(SYS_ROWS($qTB_STTS)>0){
		MSG_INFO(S_MSG('PM54','Kode Status sudah ada'));
		return;
	} else {
		$cNAMA	= ENCODE($_POST['ADD_NAMA']);
		RecCreate('TbStatus', ['KODE', 'NAMA', 'ENTRY', 'REC_ID', 'APP_CODE'], [$cKODE, $cNAMA, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
	}
	APP_LOG_ADD($cHEADER, 'Add', '', '', $cKODE);
	header('location:tb_status.php');
	break;

case 'rubah':
	$KODE_CRUD=$_GET['_id'];
	$cNAMA	= ENCODE($_POST['EDIT_NAMA']);
	RecUpdate('TbStatus', ['NAMA'], [$cNAMA], "APP_CODE='$cAPP_CODE' and KODE='$KODE_CRUD'");
	APP_LOG_ADD($cHEADER, 'Update', '', '', ENCODE($cNAMA));
	header('location:tb_status.php');
	break;

case md5('del_area'):
	$KODE_CRUD=$_GET['_id'];
	RecSoftDel($KODE_CRUD);
	APP_LOG_ADD($cHEADER, 'Delete', '', $KODE_CRUD);
	header('location:tb_status.php');
}
?>

