<?php
//	tb_bank.php //

	require_once "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Bank.pdf';

	$can_CREATE = TRUST($cUSERCODE, 'TB_BANK_1ADD');
	$cHEADER = S_MSG('F136','Tabel Bank');

	$qACCT=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	$nACCT = SYS_ROWS($qACCT);
	$qQUERY=OpenTable('TbBankAc', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

	$cKODE_TBL 	= S_MSG('F130','Kode Bank');
	$cNAMA_TBL 	= S_MSG('F131','Nama Bank');
	$cNO_REK 	= S_MSG('F134','Nomor Rekening');
	$cNM_PMLK	= S_MSG('F135','Nama Pemilik');
	$cACCOUNT	= S_MSG('F028','Account');
	$cDAFTAR	= S_MSG('F146','Daftar Bank');
	$cEDIT_TBL	= S_MSG('F147','Edit Tabel Bank');
	$cIN_FRONT	= 'Acceptable';
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$cTTIP_KODE	= S_MSG('F137','Setiap bank tempat rekening giro kita berada harus di beri kode, supaya bisa diakses berdasarkan kode');
	$cTTIP_NAMA	= S_MSG('F138','Nama bank tempat rekening kita berada');
	$cTTIP_ALMT	= S_MSG('F139','Alamat bank');
	$cTTIP_NREK	= S_MSG('F141','Nomor Rekening di bank ybs.');
	$cTTIP_PMLK	= S_MSG('F143','Nama pemilik rekening');
	$cTTIP_ACCT	= S_MSG('F142','Kode Account di Lap Keu');
	$cTTIP_INFRONT = 'Centang jika bisa diterima sebagai pembayaran di front liner';

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL, $cNO_REK, $cACCOUNT]);
						echo '<tbody>';
							while($aREC_TAB_BANK=SYS_FETCH($qQUERY)) {
								$aCOL = [$aREC_TAB_BANK['B_CODE'], DECODE($aREC_TAB_BANK['B_NAME']), DECODE($aREC_TAB_BANK['B_NREK'])];
								$cREFF = "<a href=?_a=" . md5('up_d4t3') . "&_b=" . md5($aREC_TAB_BANK['B_CODE']) . ">";
								$aREFF = [$cREFF, $cREFF, ''];
								TDETAIL($aCOL, [], '', $aREFF);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		APP_LOG_ADD( $cHEADER, 'tb_bank.php:'.$cAPP_CODE);
	break;

	case md5('cr34t3'):
		$cADD_REC	= S_MSG('F148','Tambah Bank');
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah','', $cHELP_FILE);
			TDIV();
				LABEL([3,3,3,6], '700', $cKODE_TBL);
				INPUT('text', [2,3,3,6], '900', 'ADD_B_CODE', '', 'focus', '', '', 10, '', '', $cTTIP_KODE);
				LABEL([2,2,2,6], '700', $cIN_FRONT, '', 'right');
				INPUT('checkbox', [1,1,1,1], '900', 'ADD_IN_FRONT', false, '', '', '', 0, '', 'fix', $cTTIP_INFRONT);
				LABEL([3,3,3,6], '700', $cNAMA_TBL);
				INPUT('text', [6,6,6,6], '900', 'ADD_B_NAME', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
				LABEL([3,3,3,6], '700', $cNO_REK);
				INPUT('text', [6,6,6,6], '900', 'ADD_B_NREK', '', '', '', '', 0, '', 'fix', $cTTIP_NREK);
				LABEL([3,3,3,6], '700', $cNM_PMLK);
				INPUT('text', [6,6,6,6], '900', 'ADD_B_OWN_REK', '', '', '', '', 0, '', 'fix', $cTTIP_PMLK);
				if ($nACCT) {
					LABEL([3,3,3,6], '700', $cACCOUNT);
					TDIV(8,6,6,6);
						SELECT([6,6,6,6], 'ADD_ACCOUNT', '', '', 'select2', $cTTIP_ACCT);
							echo "<option value=' '  > </option>";
							$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and GENERAL='D' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".decode_string($aREC_ACCOUNT['ACCT_NAME'])."</option>";
							}
						echo '</select>';
					eTDIV();
					CLEAR_FIX();
				}
				SAVE($can_CREATE ? $cSAVE : '');
			eTFORM();
		END_WINDOW();
	break;

	case md5('up_d4t3'):
		$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$can_UPDATE = TRUST($cUSERCODE, 'TB_BANK_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'TB_BANK_3DEL');
		$qQUERY=OpenTable('TbBank', "APP_CODE='$cAPP_CODE' and md5(B_CODE)='$_GET[_b]' and REC_ID not in ( select DEL_ID from logs_delete)");
		$REC_TAB_BANK=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_bank').'&_id='. $REC_TAB_BANK['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_r='. $REC_TAB_BANK['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,3,3,6], '900', 'EDIT_B_CODE', $REC_TAB_BANK['B_CODE'], '', '', '', 10, 'disabled', '', $cTTIP_KODE);
					LABEL([2,2,2,6], '700', $cIN_FRONT, '', 'right');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_IN_FRONT', $REC_TAB_BANK['IN_FRONT']==1, '', '', '', 0, '', 'fix', $cTTIP_INFRONT, $REC_TAB_BANK['IN_FRONT']==1);
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'EDIT_B_NAME', DECODE($REC_TAB_BANK['B_NAME']), '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([3,3,3,6], '700', $cNO_REK);
					INPUT('text', [6,6,6,6], '900', 'EDIT_B_NREK', $REC_TAB_BANK['B_NREK'], '', '', '', 0, '', 'fix', $cTTIP_NREK);
					LABEL([3,3,3,6], '700', $cNM_PMLK);
					INPUT('text', [6,6,6,6], '900', 'EDIT_B_OWN_REK', DECODE($REC_TAB_BANK['B_OWN_REK']), '', '', '', 0, '', 'fix', $cTTIP_PMLK);
					if ($nACCT) {
						LABEL([3,3,3,6], '700', $cACCOUNT);
						TDIV(8,6,6,6);
							SELECT([6,6,6,6], 'UPD_ACCOUNT', '', '', 'select2', $cTTIP_ACCT);
								echo "<option value=' '  > </option>";
								$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and GENERAL='D' and REC_ID not in ( select DEL_ID from logs_delete)");
								while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
									if($REC_TAB_BANK['ACCOUNT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
										echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$REC_TAB_BANK[ACCOUNT]' >".decode_string($aREC_ACCOUNT['ACCT_NAME'])."</option>";
									} else {
									echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".decode_string($aREC_ACCOUNT['ACCT_NAME'])."</option>"; }
								}
							echo '</select>';
						eTDIV();
						CLEAR_FIX();
					}
					SAVE($can_UPDATE ? $cSAVE : '');
				eTDIV();
			eTFORM();
		END_WINDOW();
	break;

	case 'tambah':
		$lCHECK = (isset($_POST['ADD_IN_FRONT']) ? 1 : 0);
		$cBANK_CODE=(isset($_POST['ADD_B_CODE']) ? $_POST['ADD_B_CODE'] : '');
		if($cBANK_CODE==''){
			MSG_INFO(S_MSG('F150','Kode Bank belum diisi'));
			return;
		}
		$cBANK_ACT=(isset($_POST['ADD_ACCOUNT']) ? $_POST['ADD_ACCOUNT'] : '');
		$qQUERY=OpenTable('TbBank', "B_CODE='$_POST[ADD_B_CODE]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qQUERY)>0){
			MSG_INFO(S_MSG('F149','Kode Bank sudah ada'));
			return;
			header('location:tb_bank.php');
		} else {
			$cBANK_NAME= (isset($_POST['ADD_B_NAME']) ? ENCODE($_POST['ADD_B_NAME']) : '');
			$cBANK_REK = (isset($_POST['ADD_B_NREK']) ? ENCODE($_POST['ADD_B_NREK']) : '');
			$cBANK_OWN = (isset($_POST['ADD_B_OWN_REK']) ? ENCODE($_POST['ADD_B_OWN_REK']) : '');
			RecCreate('TbBank', ['B_CODE', 'B_NAME', 'B_NREK', 'B_OWN_REK', 'ACCOUNT', 'IN_FRONT', 'ENTRY', 'REC_ID', 'APP_CODE'], 
				[ENCODE($cBANK_CODE), $cBANK_NAME, $cBANK_REK, $cBANK_OWN, $cBANK_ACT, $lCHECK, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
			header('location:tb_bank.php');
		}
		break;

	case 'rubah':
		$cREC_ID=$_GET['_r'];
		$cACCOUNT = (isset($_POST['UPD_ACCOUNT']) ? $_POST['UPD_ACCOUNT'] : '');
		$lCHECK = (isset($_POST['UPD_IN_FRONT']) ? 1 : 0);
		RecUpdate('TbBank', ['B_NAME', 'B_NREK', 'B_OWN_REK', 'ACCOUNT', 'IN_FRONT'], 
			[$_POST['EDIT_B_NAME'], $_POST['EDIT_B_NREK'], ENCODE($_POST['EDIT_B_OWN_REK']), $cACCOUNT, $lCHECK], 
			"REC_ID='$cREC_ID'");
		$ADD_LOG	= APP_LOG_ADD($cEDIT_TBL);
		header('location:tb_bank.php');
		break;

	case md5('del_bank'):
		$cREC_BANK = $_GET['_id'];
		$qQUERY=OpenTable('TbBank', "REC_ID='$cREC_BANK'");
		if(SYS_ROWS($qQUERY)==0)	return;
		$aR_BANK = SYS_FETCH($qQUERY);
		$qQUERY=OpenTable('TrReceiptHdr', "BANK='$aR_BANK[B_CODE]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qQUERY)>0){
			MSG_INFO('Kode bank dipergunakan pada transaksi penerimaan kas, belum dapat di hapus !');
			return;
		}
		$qQUERY=OpenTable('TrPaymentHdr', "A.BDV_BANK='$aR_BANK[B_CODE]' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
		if(SYS_ROWS($qQUERY)>0){
			MSG_INFO('Kode bank dipergunakan pada transaksi pembayaran/pengeluaran kas, belum dapat di hapus !');
			return;
		}
		if($aR_BANK['ACCOUNT']>'')	{
		$qQUERY=OpenTable('TrJrnDtl', "ACCOUNT_NO='$aR_BANK[ACCOUNT]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qQUERY)>0){
			MSG_INFO('Kode account bank dipergunakan pada transaksi jurnal umum, belum dapat di hapus !');
			return;
			}
		}
		RecSoftDel($cREC_BANK);
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'delete : '.$cREC_BANK);
		header('location:tb_bank.php');
}
?>

