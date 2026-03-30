<?php
//	tb_vnd_group.php //

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
$cUSERCODE 	= $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Tabel - Grup Vendor.pdf';

$cACTION='';
if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

$cHEADER 	= S_MSG('F202','Tabel Grup Supplier');
$cKODE_TBL 	= S_MSG('F204','Kode Grup Supplier');
$cNAMA_TBL 	= S_MSG('F201','Nama Grup Supplier');
$cDAFTAR	= S_MSG('F210','Daftar Tabel Grup Supplier');
$cADD_REC	= S_MSG('TK21','Tambah Kelompok');
$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');

$cTTIP_KODE	= S_MSG('F092','Setiap Kelompok persediaan diberi kode supaya bisa dikelompokkan');
$cTTIP_NAMA	= S_MSG('F093','Nama Kelompok sbg keterangan');
	
switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'VND_GRUP_1ADD');
		$qQUERY=OpenTable('TbVendorGrp', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('create_group'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL]);
						echo '<tbody>';
							while($aREC_TABLE=SYS_FETCH($qQUERY)) {
								echo '<tr>';
									echo '<td style="width: 1px;"></td>';
									echo "<td><span><a href='?_a=".md5('up_da_te')."&_g=".md5($aREC_TABLE['REC_ID'])."'>".DECODE($aREC_TABLE['VG_CODE'])."</a></span></td>";
									echo "<td><span><a href='?_a=".md5('up_da_te')."&_g=".md5($aREC_TABLE['REC_ID'])."'>".DECODE($aREC_TABLE['VG_DESC'])."</a></span></td>";
								echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
				TDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('create_group'):
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'ADD_KODE_GRP', '', 'focus', '', '', 0, '', 'fix', $cTTIP_KODE);
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'ADD_NAMA_GRP', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
				SAVE(S_MSG('F301','Save'));
				TDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

case md5('up_da_te'):
		$cEDIT_TBL	= S_MSG('TK22','Edit Tabel Kelompok');
		$can_DELETE = TRUST($cUSERCODE, 'VND_GRUP_3DEL');
		$cGROUP = $_GET['_g'];
		$qQUERY=OpenTable('TbVendorGrp', "md5(REC_ID)='$cGROUP' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$REC_TABLE=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE ? ['<a href="?_a='.md5('DELETE_GRUP').'&id='. $REC_TABLE['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&id='.$REC_TABLE['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_KODE_GRP', $REC_TABLE['VG_CODE'], '', '', '', 0, 'disabled', 'fix', $cTTIP_KODE);
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'EDIT_NAMA_GRP', DECODE($REC_TABLE['VG_DESC']), '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					SAVE((TRUST($cUSERCODE, 'VND_GRUP_3DEL') ? S_MSG('F301','Save') : ''));
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END2WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case 'tambah':
		$cKODE_GRUP	= ENCODE($_POST['ADD_KODE_GRP']);	
		if($cKODE_GRUP==''){
			MSG_INFO(S_MSG('TK24','Kode Kelompok belum diisi'));
			return;
		}
		$qQUERY=OpenTable('TbVendorGrp', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and VG_CODE='$cKODE_GRUP'");
		if(SYS_ROWS($qQUERY)>0){
			MSG_INFO(S_MSG('TK23','Kode Kelompok sudah ada'));
			return;
		} else {
			$cNAMA_GRUP	= ENCODE($_POST['ADD_NAMA_GRP']);
			RecCreate('TbVendorGrp', ['REC_ID', 'APP_CODE', 'VG_CODE', 'VG_DESC', 'ENTRY'],
				[NowMSecs(), $cAPP_CODE, $cKODE_GRUP, $cNAMA_GRUP, $cUSERCODE]);
			header('location:tb_vnd_group.php');
		}
	break;

	case 'rubah':
		$KODE_CRUD=$_GET['id'];
		$cNAMA_GRUP	= ENCODE($_POST['EDIT_NAMA_GRP']);
		RecUpdate('TbVendorGrp', ['VG_DESC'], [$cNAMA_GRUP], "REC_ID='$KODE_CRUD'");
		header('location:tb_vnd_group.php');
	break;

	case md5('DELETE_GRUP'):
		RecSoftDel($_GET['_id']);
		header('location:tb_vnd_group.php');
	}
?>

