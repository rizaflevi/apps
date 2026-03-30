<?php
// tb_aging.php
// maintain data umur utang/piutang

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Aging.pdf';

	$qQUERY=OpenTable('TbAging');

	$cHEADER 		= S_MSG('TA51','Tabel Umur Utang/Piutang');
	$cKODE_AGING 	= S_MSG('TA52','Kode Aging');
	$cNAMA_AGING 	= S_MSG('TA53','Keterangan');
	$cUMUR 			= S_MSG('TA54','Umur');
	$cSAMPAI_DGN 	= S_MSG('RS14','S/D');
	$cSAVE			= S_MSG('F301','Save');

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');


switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'view');
		$can_CREATE = TRUST($cUSERCODE, 'TAB_AGING_1ADD');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE ? ['<a href="?_a='. md5('CREATE_NEW'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '?_a='.md5('add_aging'), $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_AGING, $cNAMA_AGING, $cUMUR, $cSAMPAI_DGN]);
						echo '<tbody>';
						while($aREC_AGING=SYS_FETCH($qQUERY)) {
							$aCOL=[$aREC_AGING['KODE_AGING'], DECODE($aREC_AGING['NAMA_AGING']), $aREC_AGING['FROM_DAY'], $aREC_AGING['UNTIL_DAY']];
							TDETAIL($aCOL, [], '', ["<a href='?_a=".md5('upd_aging')."&_c=$aREC_AGING[KODE_AGING]'>", '', '', '']);
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

	case md5('CREATE_NEW'):
		$cADD_NEW		= S_MSG('TA71','Tambah Data Umur Piutang');
		DEF_WINDOW($cADD_NEW);
			TFORM($cADD_NEW, '?_a=DB_ADD', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_AGING);
					INPUT('text', [2,2,2,3], '900', 'ADD_KODE_AGING', '', 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_AGING);
					INPUT('text', [5,5,5,6], '900', 'ADD_NAMA_AGING', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cUMUR);
					INPUT('number', [1,1,1,6], '900', 'ADD_FROM_DAY', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cSAMPAI_DGN);
					INPUT('number', [1,1,1,6], '900', 'ADD_UNTIL_DAY', '', '', '', '', 0, '', 'fix');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('upd_aging'):
		$cAGING = $_GET['_c'];
		$cEDIT_TBL=S_MSG('TA72','Edit Table Aging');
		$qQUERY=OpenTable('TbAging', "KODE_AGING='$cAGING' and APP_CODE='".$cAPP_CODE."' and REC_ID not in ( select DEL_ID from logs_delete )", '', "KODE_AGING");
		$REC_EDIT=SYS_FETCH($qQUERY);
		$can_DELETE = TRUST($cUSERCODE, 'TAB_AGING_3DEL');
		DEF_WINDOW($cEDIT_TBL);
			$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=de_lete&_c='. $REC_EDIT['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_c='.$cAGING, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_AGING);
					INPUT('text', [2,2,2,3], '900', 'UPD_KODE_AGING', $REC_EDIT['KODE_AGING'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_AGING);
					INPUT('text', [5,5,5,6], '900', 'UPD_NAMA_AGING', DECODE($REC_EDIT['NAMA_AGING']), 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cUMUR);
					INPUT('number', [1,1,1,6], '900', 'UPD_FROM_DAY', $REC_EDIT['FROM_DAY'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cSAMPAI_DGN);
					INPUT('number', [1,1,1,6], '900', 'UPD_UNTIL_DAY', $REC_EDIT['UNTIL_DAY'], '', '', '', 0, '', 'fix');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;
case 'DB_ADD':
	$NOW = date("Y-m-d H:i:s");
	$cKODE_AGE =ENCODE($_POST['ADD_KODE_AGING']);
	if($cKODE_AGE=='') {
		MSG_INFO(S_MSG('TA76','Kode Aging tidak boleh kosong'));
		return;
	}
	$cAGE1 = str_replace(',', '', $_POST['ADD_FROM_DAY']);
	$cAGE2 = str_replace(',', '', $_POST['ADD_UNTIL_DAY']);
	$qQUERY=OpenTable('TbAging', "KODE_AGING='$cKODE_AGE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )", '', "KODE_AGING");
	if(SYS_ROWS($qQUERY)==0){
		$nRec_id = date_create()->format('Uv');
		$cRec_id = (string)$nRec_id;
		RecCreate('TbAging', ['KODE_AGING', 'NAMA_AGING', 'APP_CODE', 'ENTRY', 'REC_ID'], 
			[$cKODE_AGE, ENCODE($_POST['ADD_NAMA_AGING']), $cAPP_CODE, $cUSERCODE, $cRec_id]);
		header('location:tb_aging.php');
	} else {
		MSG_INFO(S_MSG('TA74','Kode Aging sudah ada'));
		return;
	}
	break;

case 'rubah':
	$cREC_ID=$_GET['_c'];
	$NOW = date("Y-m-d H:i:s");
	$cNAMA_AGING	= ENCODE($_POST['UPD_NAMA_AGING']);
	$nFROM = str_replace(',', '', $_POST['UPD_FROM_DAY']);
	$nUNTIL= str_replace(',', '', $_POST['UPD_UNTIL_DAY']);
	RecUpdate('TbAging', ['NAMA_AGING', 'FROM_DAY', 'UNTIL_DAY'], [$cNAMA_AGING, $nFROM, $nUNTIL], "REC_ID='$cREC_ID'");
	header('location:tb_aging.php');
	break;

case 'de_lete':
	RecSoftDel($_GET['_c']);
	header('location:tb_aging.php');

}
?>


