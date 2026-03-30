<?php
//	tb_inv_group.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Inventory Group.pdf';

	$qQUERY=OpenTable('TbInvGroup');

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

	$cHEADER 	= S_MSG('F086','Tabel kelompok Persediaan/Inventory');
	$cKODE_TBL 	= S_MSG('F087','Kode Kelompok');
	$cNAMA_TBL 	= S_MSG('F082','Nama Kelompok');
	$cCATEGORY_INV = S_MSG('F085','Kode Kategori');
	$cSHORT_NM 	= S_MSG('TK11','Nama Pendek');
	$cSEQUENCE 	= S_MSG('TK17','No. Urut');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	
	$cTTIP_KODE	= S_MSG('F092','Setiap Kelompok persediaan diberi kode supaya bisa dikelompokkan');
	$cTTIP_NAMA	= S_MSG('F093','Nama Kelompok sbg keterangan');
	$cTTIP_SHRT	= S_MSG('TK12','Nama Pendek untuk laporan');
	
	$l_ADA_DIST = IS_TRADING($cAPP_CODE);
	if($l_ADA_DIST) {
		$cHRG_JUAL 	= S_MSG('F053','Harga Jual');
		$cHRG_BELI 	= S_MSG('F055','Harga Beli');
	}
	$IS_CATEGORY = false;
	
switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'INV_GROUP_1ADD');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('create_group'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL]);
						echo '<tbody>';
							while($aREC_TABLE=SYS_FETCH($qQUERY)) {
								$cHREFF="<a href='?_a=".md5('up_da_te')."&_g=".md5($aREC_TABLE['KODE_GRP'])."'>";
								TDETAIL([$aREC_TABLE['KODE_GRP'], $aREC_TABLE['NAMA_GRP']], 
								[], '', [$cHREFF, $cHREFF]);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('create_group'):
		$cADD_REC	= S_MSG('TK21','Tambah Kelompok');
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah');
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,3], '900', 'ADD_KODE_GRP', '', 'focus', '', '', 0, '', 'fix', $cTTIP_KODE);
					LABEL([4,4,4,6], '700', $cNAMA_TBL);
					INPUT('text', [8,8,8,6], '900', 'ADD_NAMA_GRP', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([4,4,4,6], '700', $cSHORT_NM);
					INPUT('text', [5,5,5,6], '900', 'ADD_SHORT_NAME', '', '', '', '', 0, '', 'fix', $cTTIP_SHRT);
					if ($IS_CATEGORY) {
						LABEL([4,4,4,6], '700', $cCATEGORY_INV);
						echo '<select name="ADD_KODE_CTR" class="col-sm-6 form-label-900">';
							echo "<option value=' '  > </option>";
							$qQUERY=OpenTable('TbiCategory');
							while($aREC_CATEGORY=SYS_FETCH($qQUERY)){
								echo "<option value='$aREC_CATEGORY[KODE_CTR]'  >$aREC_CATEGORY[NAMA_CTR]</option>";
							}
						echo '</select>';
						CLEAR_FIX();
					}
					LABEL([4,4,4,6], '700', $cSEQUENCE);
					INPUT('text', [5,5,5,6], '900', 'ADD_NO_URUT', '', '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('up_da_te'):
		$can_DELETE = TRUST($cUSERCODE, 'INV_GROUP_3DEL');
		$cEDIT_TBL	= S_MSG('TK22','Edit Tabel Kelompok');
		$qQUERY=OpenTable('TbInvGroup', "APP_CODE='$cAPP_CODE' and md5(KODE_GRP)='$_GET[_g]' and REC_ID not in ( select DEL_ID from logs_delete )");
		$REC_TABLE=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DELETE_GRUP').'&_id='. md5($REC_TABLE['REC_ID']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_id'.md5($REC_TABLE['KODE_GRP']), $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,3], '900', 'EDIT_KODE_GRP', $REC_TABLE['KODE_GRP'], '', '', '', 0, 'disabled', 'fix', $cTTIP_KODE);
					LABEL([4,4,4,6], '700', $cNAMA_TBL);
					INPUT('text', [8,8,8,6], '900', 'EDIT_NAMA_GRP', $REC_TABLE['NAMA_GRP'], '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([4,4,4,6], '700', $cSHORT_NM);
					INPUT('text', [5,5,5,6], '900', 'UPD_SHORT_NAME', $REC_TABLE['SHORT_NAME'], '', '', '', 20, '', 'fix', $cTTIP_SHRT);
					if ($IS_CATEGORY) {
						LABEL([4,4,4,6], '700', $cCATEGORY_INV);
						echo '<select name="EDIT_CAT" class="col-sm-4 form-label-900">';
						echo "<option value=' '  > </option>";
						$qQUERY=OpenTable('TbiCategory', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
						while($aREC_CATEGORY=SYS_FETCH($qQUERY)){
							if($REC_TABLE['KODE_CTR'] == $aREC_CATEGORY['KODE_CTR']){
								echo "<option value='$aREC_CATEGORY[KODE_CTR]' selected='$REC_TABLE[KODE_CTR]' >$aREC_CATEGORY[NAMA_CTR]</option>";
							} else {
							echo "<option value='$aREC_CATEGORY[KODE_CTR]'  >$aREC_CATEGORY[NAMA_CTR]</option>"; }
						}
						echo '</select>';
						CLEAR_FIX();
					}
					LABEL([4,4,4,6], '700', $cSEQUENCE);
					INPUT('text', [1,1,1,3], '900', 'UPD_NO_URUT', $REC_TABLE['NO_URUT'], '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

case 'tambah':
	$cKODE_GRUP	= ENCODE($_POST['ADD_KODE_GRP']);
    $cCATEGORY=(isset($_POST['ADD_KODE_CTR']) ? $_POST['ADD_KODE_CTR'] : '');
	if($cKODE_GRUP==''){
		MSG_INFO(S_MSG('TK24','Kode Kelompok belum diisi'));
		return;
	}
	$qCEK_KODE=OpenTable('TbInvGroup', "APP_CODE='$cAPP_CODE' and KODE_GRP='$cKODE_GRUP' and REC_ID not in ( select DEL_ID from logs_delete )");
	if(SYS_ROWS($qCEK_KODE)>0){
		MSG_INFO(S_MSG('TK23','Kode Kelompok sudah ada'));
		return;
	} else {
		$cNAMA_GRUP	= ENCODE($_POST['ADD_NAMA_GRP']);	
		$cNAMA_SHRT	= ENCODE($_POST['ADD_SHORT_NAME']);
		RecCreate('TbInvGroup', ['KODE_GRP', 'NAMA_GRP', 'KODE_CTR', 'SHORT_NAME', 'NO_URUT', 'ENTRY', 'REC_ID', 'APP_CODE'], 
			[$cKODE_GRUP, $cNAMA_GRUP, $cCATEGORY, $cNAMA_SHRT, $_POST['ADD_NO_URUT'], $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		header('location:tb_inv_group.php');
	}
	break;

case 'rubah':
	$KODE_CRUD=$_GET['_id'];
	$cNAMA_GRUP	= ENCODE($_POST['EDIT_NAMA_GRP']);
	$cNAMA_SHRT	= ENCODE($_POST['UPD_SHORT_NAME']);
    $cCATEGORY=(isset($_POST['EDIT_CAT']) ? $_POST['EDIT_CAT'] : '');
	RecUpdate('TbInvGroup', ['NAMA_GRP', 'KODE_CTR', 'SHORT_NAME', 'NO_URUT'], [$cNAMA_GRUP, $cCATEGORY, $cNAMA_SHRT, $_POST['UPD_NO_URUT']],
		"APP_CODE='$cAPP_CODE' and KODE_GRP='$KODE_CRUD'");
	header('location:tb_inv_group.php');
	break;

case md5('DELETE_GRUP'):
	RecSoftDel($_GET['_id']);
	header('location:tb_inv_group.php');
}
?>

