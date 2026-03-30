<?php
//	pos_group_menu.php //

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('upload_max_filesize', '20M');
ini_set('max_execution_time', 10); //10 seconds

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
$cUSERCODE = $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Tabel - Pos Menu Group.pdf';

$qQUERY=OpenTable('TbInvGroup');

$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

$cHEADER 	= S_MSG('H603','Tabel kelompok Menu');
$cKODE_TBL 	= S_MSG('H601','Kode Kelompok');
$cNAMA_TBL 	= S_MSG('H602','Nama Kelompok');
$cSHORT_NM 	= S_MSG('TK11','Nama Pendek');
$cSEQUENCE 	= S_MSG('TK17','No. Urut');
$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');

$cTTIP_KODE	= S_MSG('TK25','Setiap Kelompok diberi kode supaya bisa dikelompokkan');
$cTTIP_NAMA	= S_MSG('F093','Nama Kelompok sbg keterangan');
$cTTIP_SHRT	= S_MSG('TK12','Nama Pendek untuk laporan');
	
	
switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'POS_GRP_MENU_1ADD');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('create_group'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL, $cSHORT_NM, $cSEQUENCE]);
						echo '<tbody>';
							while($aREC_TABLE=SYS_FETCH($qQUERY)) {
								$cHREFF="<a href='?_a=".md5('up_da_te')."&_id=".$aREC_TABLE['REC_ID']."'>";
								TDETAIL([$aREC_TABLE['KODE_GRP'], $aREC_TABLE['NAMA_GRP'], $aREC_TABLE['SHORT_NAME'], $aREC_TABLE['NO_URUT']], 
								[], '', [$cHREFF, $cHREFF, $cHREFF, '']);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('create_group'):
		$cADD_REC	= S_MSG('KA11','Add new');
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah');
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,3], '900', 'ADD_MGROUP_CODE', '', 'focus', '', '', 0, '', 'fix', $cTTIP_KODE);
					LABEL([4,4,4,6], '700', $cNAMA_TBL);
					INPUT('text', [8,8,8,6], '900', 'ADD_MGROUP_NAME', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([4,4,4,6], '700', $cSHORT_NM);
					INPUT('text', [5,5,5,6], '900', 'ADD_SHORT_NAME', '', '', '', '', 0, '', 'fix', $cTTIP_SHRT);
					LABEL([4,4,4,6], '700', $cSEQUENCE);
					INPUT('text', [5,5,5,6], '900', 'ADD_SEQ_NO', '', '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('up_da_te'):
		$can_DELETE = TRUST($cUSERCODE, 'POS_GRP_MENU_3DEL');
		$cEDIT_TBL	= S_MSG('TK22','Edit Tabel Kelompok');
        $cREC_ID = $_GET['_id'];
		$qQUERY=OpenTable('TbInvGroup', "REC_ID='$cREC_ID' and REC_ID not in ( select DEL_ID from logs_delete )");
		$REC_TABLE=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DELETE_GRUP').'&_id='. $cREC_ID. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_id='.$cREC_ID, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,3], '900', 'EDIT_MGROUP_CODE', $REC_TABLE['KODE_GRP'], '', '', '', 0, 'disabled', 'fix', $cTTIP_KODE);
					LABEL([4,4,4,6], '700', $cNAMA_TBL);
					INPUT('text', [8,8,8,6], '900', 'EDIT_MGROUP_NAME', $REC_TABLE['NAMA_GRP'], '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([4,4,4,6], '700', $cSHORT_NM);
					INPUT('text', [5,5,5,6], '900', 'UPD_SHORT_NAME', $REC_TABLE['SHORT_NAME'], '', '', '', 0, '', 'fix', $cTTIP_SHRT);
					LABEL([4,4,4,6], '700', $cSEQUENCE);
					INPUT('text', [1,1,1,3], '900', 'UPD_SEQ_NO', $REC_TABLE['NO_URUT'], '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

case 'tambah':
	$cKODE_GRUP	= ENCODE($_POST['ADD_MGROUP_CODE']);	
	if($cKODE_GRUP==''){
		MSG_INFO(S_MSG('TK24','Kode Kelompok belum diisi'));
		return;
	}
	$qCEK_KODE=OpenTable('TbInvGroup', "APP_CODE='$cAPP_CODE' and KODE_GRP='$cKODE_GRUP' and REC_ID not in ( select DEL_ID from logs_delete )");
	if(SYS_ROWS($qCEK_KODE)>0){
		MSG_INFO(S_MSG('TK23','Kode Kelompok sudah ada'));
		return;
	} else {
		$cNAMA_GRUP	= ENCODE($_POST['ADD_MGROUP_NAME']);	
		$cNAMA_SHRT	= ENCODE($_POST['ADD_SHORT_NAME']);
		RecCreate('TbInvGroup', ['KODE_GRP', 'NAMA_GRP', 'SHORT_NAME', 'NO_URUT', 'ENTRY', 'REC_ID', 'APP_CODE'], 
			[$cKODE_GRUP, $cNAMA_GRUP, $cNAMA_SHRT, $_POST['ADD_SEQ_NO'], $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		header('location:pos_group_menu.php');
	}
	break;

case 'rubah':
	$REC_ID=$_GET['_id'];
	$cNAMA_GRUP	= ENCODE($_POST['EDIT_MGROUP_NAME']);
	$cNAMA_SHRT	= ENCODE($_POST['UPD_SHORT_NAME']);
	$cSEQ_NO	= $_POST['UPD_SEQ_NO'];
	RecUpdate('TbInvGroup', ['NAMA_GRP', 'SHORT_NAME', 'NO_URUT'], [$cNAMA_GRUP, $cNAMA_SHRT, $cSEQ_NO],
		"REC_ID='$REC_ID'");
	header('location:pos_group_menu.php');
	break;

case md5('DELETE_GRUP'):
	$REC_ID=$_GET['_id'];
	RecDelete('TbInvGroup', "REC_ID='$REC_ID'");
	header('location:pos_group_menu.php');
}
?>

