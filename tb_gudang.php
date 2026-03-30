<?php
//	tb_gudang.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Gudang.pdf';
	$ada_ACCOUNT=0;
	$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	$ada_ACCOUNT=SYS_ROWS($qQUERY)>0;

	$qQUERY=OpenTable('TbWarehouse', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

	$cHEADER 	= S_MSG('TG10','Tabel Gudang');
	$cKODE_TBL 	= S_MSG('TG10','Kode Gudang');
	$cNAMA_TBL 	= S_MSG('TG02','Nama Gudang');
	$cNO_REK 	= S_MSG('TG05','Account Persediaan');
	$cSAVE		= S_MSG('F301','Save');

	$cTTIP_KODE	= S_MSG('TG11','Setiap Gudang diber kode supaya memudahkan dalam pengaksesan data');
	$cTTIP_NAMA	= S_MSG('TG12','Nama Gudang atau lokasi penyimpanan');
	$cTTIP_NREK	= S_MSG('NA21','Klik untuk menentukan kode Account');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER);
			$aACT = (TRUST($cUSERCODE, 'GUDANG_1ADD')==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
					THEAD([$cKODE_TBL, $cNAMA_TBL]);
						echo '<tbody>';
							while($aREC_GUDANG=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								echo '<td style="width: 1px;"></td>';
								echo "<td><span><a href='?_a=".md5('up_d4t3')."&_b=".md5($aREC_GUDANG['KODE_GDG'])."'>".$aREC_GUDANG['KODE_GDG']."</a></span></td>";
								echo "<td><span><a href='?_a=".md5('up_d4t3')."&_b=".md5($aREC_GUDANG['KODE_GDG'])."'>".decode_string($aREC_GUDANG['NAMA_GDG'])."</a></span></td>";
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
		$cADD_REC	= S_MSG('TG15','Tambah Gudang');
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=add', [], $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_TBL);
                    INPUT('text', [2,2,2,6], '900', 'ADD_KODE_GDG', '', 'focus', '', '', 0, '', 'fix', $cTTIP_KODE);
					LABEL([4,4,4,6], '700', $cNAMA_TBL);
                    INPUT('text', [6,6,6,6], '900', 'ADD_NAMA_GDG', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([4,4,4,6], '700', $cNO_REK);
					TDIV(8,8,8,8);
						SELECT([6,6,6,6], 'ADD_ACCOUNT', '', '', 'select2', $cTTIP_NREK);
							echo "<option value=' '  > </option>";
							$qQUERY=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
							}
						echo '</select>';
					eTDIV();
					CLEAR_FIX();
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('up_d4t3'):
		$can_DELETE = TRUST($cUSERCODE, 'GUDANG_3DEL');
		$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$cEDIT_TBL	= S_MSG('F140','Edit Tabel Gudang');
		$qQUERY=OpenTable('TbWarehouse', "md5(KODE_GDG)='$_GET[_b]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$REC_GUDANG=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_gdg').'&_id='. md5($REC_GUDANG['REC_ID']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_id='.$REC_GUDANG['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_KODE_GDG', $REC_GUDANG['KODE_GDG'], '', '', '', 0, 'disabled', 'fix', $cTTIP_KODE);
					LABEL([4,4,4,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'EDIT_NAMA_GDG', $REC_GUDANG['NAMA_GDG'], 'focus', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([4,4,4,6], '700', $cNO_REK);
					TDIV(8,8,8,8);
						SELECT([6,6,6,6], 'EDIT_ACCOUNT', '', '', 'select2', $cTTIP_NREK);
							echo "<option value=' '  > </option>";
							$qQUERY=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
								if($REC_GUDANG['ACCOUNT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
									echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$REC_GUDANG[ACCOUNT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
								} else {
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
							}
						echo '</select>';
					eTDIV();
					CLEAR_FIX();
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

case 'add':
	if($_POST['ADD_KODE_GDG']==''){
		MSG_INFO(S_MSG('TG14','Kode Gudang belum diisi'));
		return;
	}
	$qQUERY=OpenTable('TbWarehouse', "KODE_GDG='$_POST[ADD_KODE_GDG]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if($qQUERY){
		if(SYS_ROWS($qQUERY)>0){
			MSG_INFO(S_MSG('TG13','Kode Gudang sudah ada'));
			return;
		}
	}
	RecCreate('TbWarehouse', ['KODE_GDG', 'NAMA_GDG', 'ACCOUNT', 'ENTRY', 'REC_ID', 'APP_CODE'], 
		[$_POST['ADD_KODE_GDG'], encode_string($_POST['ADD_NAMA_GDG']), $_POST['ADD_ACCOUNT'], $cUSERCODE, NowMSecs(), $cAPP_CODE]);
	header('location:tb_gudang.php');
	break;

case 'rubah':
	$KODE_CRUD=$_GET['_id'];
	RecUpdate('TbWarehouse', ['NAMA_GDG', 'ACCOUNT'], [encode_string($_POST['EDIT_NAMA_GDG']), $_POST['EDIT_ACCOUNT']], "REC_ID='$KODE_CRUD'");
	header('location:tb_gudang.php');
	break;

case md5('del_gdg'):
	RecSoftDel($_GET['_id']);
	header('location:tb_gudang.php');
}
?>

