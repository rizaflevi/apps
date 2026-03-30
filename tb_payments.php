<?php
//	tb_payments.php 
// tabel tipe pembayaran

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Tipe Pembayaran.pdf';
	$cHEADER 	= S_MSG('H145','Tabel Tipe Pembayaran');
	$cKD_ID  	= S_MSG('H140','Kode Pembayaran');
	$cNM_ID  	= S_MSG('H141','Nama Pembayaran');
	$cPAY_OPTION = S_MSG('H153','Payment Option');
	$cDAFTAR	= S_MSG('H148','Daftar Pembayaran');
	$cADD_TBL	= S_MSG('H149','Add Pembayaran');
	$cEDIT_TBL	= S_MSG('H167','Edit Pembayaran');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');

	$qQUERY=OpenTable('TbPayType');

	$cACTION=(isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');

switch($cACTION){
	default:
	    $can_CREATE = TRUST($cUSERCODE, 'PAYMENT_TYPE_1ADD');
		$aACT = ($can_CREATE==1 ? ['<a href="?_a=create"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new') .'</a>'] : []);
		DEF_WINDOW($cHEADER);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKD_ID, $cNM_ID]);
						echo '<tbody>';
						while($aPAY_TYPE=SYS_FETCH($qQUERY)) {
							$cHREFF="<a href='?_a=update&KD_PAYMENT=".$aPAY_TYPE['PAY_CODE']."'>";
							$aCOL=[$aPAY_TYPE['PAY_CODE'], $aPAY_TYPE['PAY_DESC']];
							$aHREFF=[$cHREFF, $cHREFF];
							TDETAIL($aCOL, [0,0], '', $aHREFF);
						}
						echo '</tbody>';
					eTABLE();
				TDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case "create":
		DEF_WINDOW($cADD_TBL);
			TFORM($cADD_TBL, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKD_ID);
					INPUT('text', [2,2,2,6], '900', 'ADD_PAY_CODE', '', 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNM_ID);
					INPUT('text', [6,6,6,6], '900', 'ADD_PAY_DESC', '', '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case "update":
		$can_UPDATE = TRUST($cUSERCODE, 'PAYMENT_TYPE_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'PAYMENT_TYPE_3DEL');
		$qQUERY=OpenTable('TbPayType', "PAY_CODE='$_GET[KD_PAYMENT]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$aPAY_TYPE=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
		$aACT = ($can_DELETE==1 ? ['<a href="?_a=delete&_id='. $aPAY_TYPE['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_c='.$aPAY_TYPE['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
				LABEL([3,3,3,6], '700', $cKD_ID);
				INPUT('text', [2,2,2,6], '900', 'EDIT_PAY_CODE', $aPAY_TYPE['PAY_CODE'], '', '', '', 0, 'disabled', 'fix');
				LABEL([3,3,3,6], '700', $cNM_ID);
				INPUT('text', [6,6,6,6], '900', 'EDIT_PAY_DESC', $aPAY_TYPE['PAY_DESC'], 'focus', '', '', 0, '', 'fix');
				SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;
case 'tambah':
	$cPAY_CODE = ENCODE($_POST['ADD_PAY_CODE']);
	$cPAY_DESC = ENCODE($_POST['ADD_PAY_DESC']);
	if($cPAY_CODE==''){
		MSG_INFO('** payment Id masih kosong **');
		// header('location:tb_payments.php');
	}
	if($cPAY_DESC==''){
		MSG_INFO('** Nama payment masih kosong **');
		// header('location:tb_payments.php');
	}
	$qQUERY=OpenTable('TbPayType', "PAY_CODE='$_POST[ADD_PAY_CODE]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if ($qQUERY) {
		if(SYS_ROWS($qQUERY)>0){
			return;
		}
	}
	RecCreate('TbPayType', ['PAY_CODE', 'PAY_DESC', 'APP_CODE', 'ENTRY', 'REC_ID'], [$cPAY_CODE, $cPAY_DESC, $cAPP_CODE, $_SESSION['gUSERCODE'], NowMSecs()]);
	header('location:tb_payments.php');
	break;
case 'rubah':
	$KODE_CRUD=$_GET['_c'];
	$cPAY_DESC = ENCODE($_POST['EDIT_PAY_DESC']);
	RecUpdate('TbPayType', ['PAY_DESC'], [$cPAY_DESC], "APP_CODE='$cAPP_CODE' and REC_ID='$cPAY_CODE'");
	header('location:tb_payments.php');
	break;
case 'delete':
	$KODE_CRUD=$_GET['_id'];
	RecSoftDel($KODE_CRUD);
	APP_LOG_ADD($cHEADER, 'Delete : '.$cKODE_INV);
	header('location:tb_payments.php');
	break;
}
?>

