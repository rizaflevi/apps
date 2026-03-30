<?php
//	tb_prin.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHEADER = S_MSG('FB01','Bill Printer Table');
	$cHELP_FILE = 'Doc/Tabel - Format Cetak.pdf';

	$qTB_BILL=OpenTable('TbBillPrintHdr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

	$cKODE_TBL 	= S_MSG('CL02','Kode');
	$cNAMA_TBL = S_MSG('CL03','Keterangan');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cEDIT_TBL	= S_MSG('FB19','Edit Tabel format');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');
	$cCETAK		= S_MSG('PS10','Cetak');
	$cKOLOM		= S_MSG('PS11','Kolom');
	$cBARIS		= S_MSG('PS12','Baris');
	$aPAPER_SIZE = array(' ', 'F4', 'A4', 'A5', 'C7');
	$aORIENTATION = array(' ', 'Portrait', 'Landscape');

switch($cACTION){
	default:
		if (IS_LOCALHOST())		UPDATE_DATE();
		$can_CREATE = TRUST($cUSERCODE, 'BILL_PRN_1ADD');
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL]);
						echo '<tbody>';
							while($aREC_TB_BILL=SYS_FETCH($qTB_BILL)) {
							echo '<tr>';
								echo '<td style="width: 1px;"></td>';
								echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_BILL['REC_ID'])."'>".DECODE($aREC_TB_BILL['PRNTR_CODE'])."</a></span></td>";
								echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_BILL['REC_ID'])."'>".DECODE($aREC_TB_BILL['BILL_PNAME'])."</a></span></td>";
							echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('cr34t3'):
		if (IS_LOCALHOST())		UPDATE_DATE();
		$cADD_REC	= S_MSG('FB17','Tambah format');
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,5], '700', $cKODE_TBL);
					INPUT('text', [2,2,3,5], '900', 'ADD_KODE_BILL', '', 'autofocus', '', '', 0, '', 'Fix');
					LABEL([3,3,3,5], '700', $cNAMA_TBL);
					INPUT('text', [5,6,8,7], '900', 'ADD_NAMA_BILL', '', '', '', '', 0, '', 'Fix');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('up_d4t3'):
		$can_UPDATE = TRUST($cUSERCODE, 'BILL_PRN_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'BILL_PRN_3DEL');
		$qTB_BILL=OpenTable('TbBillPrintHdr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and md5(REC_ID)='$_GET[_r]' ");
		$aREC_TB_BILL=SYS_FETCH($qTB_BILL);
		DEF_WINDOW($cEDIT_TBL);
			$aACT=[];
			if ($can_DELETE==1) {
				array_push($aACT, '<a href="?_a='.md5('del_format').'&_id='.$aREC_TB_BILL['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>'.S_MSG('F304','Delete').'</a>');
			}
			TFORM($cEDIT_TBL, '?_a=rubah&_c='.$aREC_TB_BILL['PRNTR_CODE'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,3,6], '900', 'EDIT_KODE_BILL', $aREC_TB_BILL['PRNTR_CODE'], '', '', '', 0, 'disabled', 'Fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [5,6,8,12], '900', 'EDIT_NAMA_BILL', DECODE($aREC_TB_BILL['BILL_PNAME']), 'autofocus', '', '', 0, '', 'Fix');
					LABEL([3,3,3,6], '700', 'Ukuran Kertas');
					echo '<select name="EDIT_PAPER_SZ" class="col-sm-3 form-label-900">';
						for ($I=0; $I<sizeof($aPAPER_SIZE); $I++){
							if ($aPAPER_SIZE[$I] == $aREC_TB_BILL['PAPER_SIZE'])
								echo "<option class='form-label-900' value=$I selected>".$aPAPER_SIZE[$I]."</option>";
							else 
								echo "<option class='form-label-900' value=$I>".$aPAPER_SIZE[$I]."</option>"; 
						}
					echo '</select>';
					CLEAR_FIX();
					LABEL([3,3,3,6], '700', 'Orientasi');
					echo '<select name="EDIT_ORIENTASI" class="col-sm-3 form-label-900">';
						for ($I=0; $I<sizeof($aORIENTATION); $I++){
							if (substr($aORIENTATION[$I],0,1) == $aREC_TB_BILL['ORIENTATION'])
								echo "<option class='form-label-900' value=$I selected>".$aORIENTATION[$I]."</option>";
							else 
								echo "<option class='form-label-900' value=$I>".$aORIENTATION[$I]."</option>"; 
						}
					echo '</select>';
					CLEAR_FIX();
					echo '<br>';
					$_SESSION['KD_BILL']     = $aREC_TB_BILL['PRNTR_CODE'];
					require_once("tb_prin_tab.php");
					SAVE($can_UPDATE==1 ? $cSAVE : '');
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case 'tambah':
		$cKODE_BILL	= ENCODE($_POST['ADD_KODE_BILL']);	
		if($cKODE_BILL==''){
			MSG_INFO(S_MSG('TC93','Kode format belum diisi'));
			return;
		}
		$qTB_BILL=OpenTable('TbBillPrintHdr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and PRNTR_CODE='$cKODE_BILL' ");
		if(SYS_ROWS($qTB_BILL)>0){
			MSG_INFO(S_MSG('TC94','Kode format sudah ada'));
			return;
			header('location:tb_prin.php');
		} else {
			$cCONTENT	= ENCODE($_POST['ADD_NAMA_BILL']);
			RecCreate('TbBillPrintHdr', ['PRNTR_CODE', 'BILL_PNAME', 'ENTRY', 'REC_ID', 'APP_CODE'], [$cKODE_BILL, $cCONTENT, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
			header('location:tb_prin.php');
		}
		SYS_DB_CLOSE($DB2);	
	break;

	case 'rubah':
		$KODE_CRUD=$_GET['_c'];
		$cCONTENT	= ENCODE($_POST['EDIT_NAMA_BILL']);
		$cSIZE = $aPAPER_SIZE[$_POST['EDIT_PAPER_SZ']];	
		$cORT = substr($aORIENTATION[$_POST['EDIT_ORIENTASI']],0,1);
		RecUpdate('TbBillPrintHdr', ['BILL_PNAME', 'PAPER_SIZE', 'ORIENTATION'], [$cCONTENT, $cSIZE, $cORT], "APP_CODE='$cAPP_CODE' and PRNTR_CODE='$KODE_CRUD'");
		$lCHECK = (isset($_POST['LOGO_CETAK']) ? '1' : '');
		BILL_UPDATE($KODE_CRUD, 'LOGO_CETAK', $lCHECK);
		BILL_UPDATE($KODE_CRUD, 'LOGO_LEFT', $_POST['LOGO_LEFT']);
		BILL_UPDATE($KODE_CRUD, 'LOGO_TOP', $_POST['LOGO_TOP']);
		BILL_UPDATE($KODE_CRUD, 'LOGO_WIDTH', $_POST['LOGO_WIDTH']);
		BILL_UPDATE($KODE_CRUD, 'LOGO_HEIGT', $_POST['LOGO_HEIGT']);

		$lCOMP = (isset($_POST['COMP_CETAK']) ? '1' : '');
		$lCENTER = (isset($_POST['COMP_CENTR']) ? '1' : '');
		BILL_UPDATE($KODE_CRUD, 'COMP_CETAK', $lCOMP);
		BILL_UPDATE($KODE_CRUD, 'COMP_CENTR', $lCENTER);
		BILL_UPDATE($KODE_CRUD, 'COMP_LEFT', $_POST['COMP_LEFT']);
		BILL_UPDATE($KODE_CRUD, 'COMP_TOP', $_POST['COMP_TOP']);
		BILL_UPDATE($KODE_CRUD, 'COMP_FONT_CODE', $_POST['COMP_FONT_CODE']);

		$lADD1 = (isset($_POST['ADD1_CETAK']) ? '1' : '');
		$lCENTER = (isset($_POST['ADD1_CENTR']) ? '1' : '');
		BILL_UPDATE($KODE_CRUD, 'ADD1_CETAK', $lADD1);
		BILL_UPDATE($KODE_CRUD, 'ADD1_CENTR', $lCENTER);
		BILL_UPDATE($KODE_CRUD, 'ADD1_LEFT', $_POST['ADD1_LEFT']);
		BILL_UPDATE($KODE_CRUD, 'ADD1_TOP', $_POST['ADD1_TOP']);
		BILL_UPDATE($KODE_CRUD, 'ADD1_FONT_CODE', $_POST['ADD1_FONT_CODE']);

		$lADD2 = (isset($_POST['ADD2_CETAK']) ? '1' : '');
		$lCENTER = (isset($_POST['ADD2_CENTR']) ? '1' : '');
		BILL_UPDATE($KODE_CRUD, 'ADD2_CETAK', $lADD2);
		BILL_UPDATE($KODE_CRUD, 'ADD2_CENTR', $lCENTER);
		BILL_UPDATE($KODE_CRUD, 'ADD2_LEFT', $_POST['ADD2_LEFT']);
		BILL_UPDATE($KODE_CRUD, 'ADD2_TOP', $_POST['ADD2_TOP']);
		BILL_UPDATE($KODE_CRUD, 'ADD2_FONT_CODE', $_POST['ADD2_FONT_CODE']);

		$lNOTA = (isset($_POST['N_NOTA_CTK']) ? '1' : '');
		$lCENTER = (isset($_POST['NOTA_CENTR']) ? '1' : '');
		BILL_UPDATE($KODE_CRUD, 'N_NOTA_CTK', $lNOTA);
		BILL_UPDATE($KODE_CRUD, 'NOTA_CENTR', $lCENTER);
		BILL_UPDATE($KODE_CRUD, 'NOTA_LEFT', $_POST['NOTA_LEFT']);
		BILL_UPDATE($KODE_CRUD, 'NOTA_TOP', $_POST['NOTA_TOP']);
		BILL_UPDATE($KODE_CRUD, 'NOTA_FONT_CODE', $_POST['NOTA_FONT_CODE']);

		$lDATE = (isset($_POST['TGGL_CTK']) ? '1' : '');
		$lCENTER = (isset($_POST['TGGL_CENTR']) ? '1' : '');
		BILL_UPDATE($KODE_CRUD, 'TGGL_CTK', $lDATE);
		BILL_UPDATE($KODE_CRUD, 'TGGL_CENTR', $lCENTER);
		BILL_UPDATE($KODE_CRUD, 'TGGL_LEFT', $_POST['TGGL_LEFT']);
		BILL_UPDATE($KODE_CRUD, 'TGGL_TOP', $_POST['TGGL_TOP']);
		BILL_UPDATE($KODE_CRUD, 'TGGL_FONT_CODE', $_POST['TGGL_FONT_CODE']);

		$l_JAM = (isset($_POST['JAM_CTK']) ? '1' : '');
		$lCENTER = (isset($_POST['JAM_CENTR']) ? '1' : '');
		BILL_UPDATE($KODE_CRUD, 'JAM_CTK', $l_JAM);
		BILL_UPDATE($KODE_CRUD, 'JAM_CENTR', $lCENTER);
		BILL_UPDATE($KODE_CRUD, 'JAM_LEFT', $_POST['JAM_LEFT']);
		BILL_UPDATE($KODE_CRUD, 'JAM_TOP', $_POST['JAM_TOP']);
		BILL_UPDATE($KODE_CRUD, 'JAM_FONT_CODE', $_POST['JAM_FONT_CODE']);

		$l_KET = '';	if (isset($_POST['KET_CTK']))		$l_KET = '1';
		$lCENTER = '';	if (isset($_POST['KET_CENTR']))		$lCENTER = '1';
		BILL_UPDATE($KODE_CRUD, 'KET_CTK', $l_KET);
		BILL_UPDATE($KODE_CRUD, 'KET_CENTR', $lCENTER);
		BILL_UPDATE($KODE_CRUD, 'KET_LEFT', $_POST['KET_LEFT']);
		BILL_UPDATE($KODE_CRUD, 'KET_TOP', $_POST['KET_TOP']);
		BILL_UPDATE($KODE_CRUD, 'KET_FONT_CODE', $_POST['KET_FONT_CODE']);

		for($I = 1; $I<=12; $I++):
			$J=(string)$I;
			$cKONST = '';	if (isset($_POST['KONST'.$J.'_STAT']))		$cKONST = '1';
			BILL_UPDATE($KODE_CRUD, 'KONST'.$J.'_STAT', $cKONST);
			BILL_UPDATE($KODE_CRUD, 'KONST'.$J.'_COL', $_POST['KONST'.$J.'_COL']);
			BILL_UPDATE($KODE_CRUD, 'KONST'.$J.'_ROW', $_POST['KONST'.$J.'_ROW']);
			BILL_UPDATE($KODE_CRUD, 'KONST'.$J.'_CONTENT', $_POST['KONST'.$J.'_CONTENT']);
			BILL_UPDATE($KODE_CRUD, 'KONST'.$J.'_FONT_CODE', $_POST['KONST'.$J.'_FONT_CODE']);
		endfor;

		for($I = 1; $I<=9; $I++):
			$J=(string)$I;
			$cLINEx = '';	if (isset($_POST['LINE'.$J.'_CTK']))		$cLINEx = '1';
			BILL_UPDATE($KODE_CRUD, 'LINE'.$J.'_CTK', $cLINEx);
			BILL_UPDATE($KODE_CRUD, 'LINE'.$J.'_LEFT_COL', $_POST['LINE'.$J.'_LEFT_COL']);
			BILL_UPDATE($KODE_CRUD, 'LINE'.$J.'_LEFT_ROW', $_POST['LINE'.$J.'_LEFT_ROW']);
			BILL_UPDATE($KODE_CRUD, 'LINE'.$J.'_RIGHT_COL', $_POST['LINE'.$J.'_RIGHT_COL']);
			BILL_UPDATE($KODE_CRUD, 'LINE'.$J.'_RIGHT_ROW', $_POST['LINE'.$J.'_RIGHT_ROW']);
			BILL_UPDATE($KODE_CRUD, 'LINE'.$J.'_POINT', $_POST['LINE'.$J.'_POINT']);
		endfor;

		if($_POST['DETAIL_START_ROW']>'0')	BILL_UPDATE($KODE_CRUD, 'DETAIL_START_ROW', $_POST['DETAIL_START_ROW']);
		$qQUERY=OpenTable('TbBillCol', "BILL_CODE='RECEIPT' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
		$I=0;
		while($aDTL_COL=SYS_FETCH($qQUERY)) {
			$I++;
			$J=(string)$I;
			$cDATA = 'DETAIL'.$J.'_HEAD_LABEL';
			$cHD_LBL=(isset($_POST[$cDATA]) ? $_POST[$cDATA] : '');
			$cSTTS = 'DETAIL'.$J.'_HEAD_STATUS';
			$lSTTS = (isset($_POST[$cSTTS]) ? '1' : '');
			$cDATA_COL= (isset($_POST['DETAIL'.$J.'_DATA_COL']) ? $_POST['DETAIL'.$J.'_DATA_COL'] : '');
			$lTOTAL= (isset($_POST['TOTAL'.$J.'_STATUS']) ? '1' : '');
			$cLABEL= (isset($_POST['TOTAL'.$J.'_LABEL']) ? $_POST['TOTAL'.$J.'_LABEL'] : '');
			BILL_UPDATE($KODE_CRUD, $cDATA, $cHD_LBL);
			BILL_UPDATE($KODE_CRUD, $cSTTS, $lSTTS);
			BILL_UPDATE($KODE_CRUD, 'DETAIL'.$J.'_DATA_COL', $cDATA_COL);
			BILL_UPDATE($KODE_CRUD, 'TOTAL'.$J.'_STATUS', $lTOTAL);
			BILL_UPDATE($KODE_CRUD, 'TOTAL'.$J.'_LABEL', $cLABEL);
		}
		$cFONT=(isset($_POST['DETAIL_DTA_FONT_CODE']) ? $_POST['DETAIL_DTA_FONT_CODE'] : '');
		BILL_UPDATE($KODE_CRUD, 'DETAIL_DTA_FONT_CODE', $cFONT);
		$lLINE = '';	if (isset($_POST['LINE_AFTER_DETAIL']))		$lLINE = '1';
		BILL_UPDATE($KODE_CRUD, 'LINE_AFTER_DETAIL', $lLINE);
		BILL_UPDATE($KODE_CRUD, 'LINE_AFTER_DTL_COL', $_POST['LINE_AFTER_DTL_COL']);
		BILL_UPDATE($KODE_CRUD, 'LINE_AFTER_DTL_LENGTH', $_POST['LINE_AFTER_DTL_LENGTH']);
		BILL_UPDATE($KODE_CRUD, 'LINE_AFTER_DTL_POINT', $_POST['LINE_AFTER_DTL_POINT']);

		$lSAYS = '';	if (isset($_POST['TOTAL_SAYS_STATUS']))		$lSAYS = '1';
		BILL_UPDATE($KODE_CRUD, 'TOTAL_SAYS_STATUS', $lSAYS);
		BILL_UPDATE($KODE_CRUD, 'TOTAL_SAYS_COL', $_POST['TOTAL_SAYS_COL']);
		BILL_UPDATE($KODE_CRUD, 'TOTAL_SAYS_ROW', $_POST['TOTAL_SAYS_ROW']);
		BILL_UPDATE($KODE_CRUD, 'TOTAL_SAYS_FONT_CODE', $_POST['TOTAL_SAYS_FONT_CODE']);

		for($I = 1; $I<=9; $I++):
			$J=(string)$I;
			$cBOXx = '';	if (isset($_POST['BOX'.$J.'_CTK']))		$cBOXx = '1';
			BILL_UPDATE($KODE_CRUD, 'BOX'.$J.'_CTK', $cBOXx);
			BILL_UPDATE($KODE_CRUD, 'BOX'.$J.'_LEFT_COL', $_POST['BOX'.$J.'_LEFT_COL']);
			BILL_UPDATE($KODE_CRUD, 'BOX'.$J.'_LEFT_ROW', $_POST['BOX'.$J.'_LEFT_ROW']);
			BILL_UPDATE($KODE_CRUD, 'BOX'.$J.'_RIGHT_COL', $_POST['BOX'.$J.'_RIGHT_COL']);
			BILL_UPDATE($KODE_CRUD, 'BOX'.$J.'_RIGHT_ROW', $_POST['BOX'.$J.'_RIGHT_ROW']);
			BILL_UPDATE($KODE_CRUD, 'BOX'.$J.'_POINT', $_POST['BOX'.$J.'_POINT']);
		endfor;
		SYS_DB_CLOSE($DB2);	
		header('location:tb_prin.php');
	break;

	case md5('del_format'):
		$qTB_BILL=OpenTable('TbBillPrintHdr', "REC_ID='$KODE_CRUD' ");
		$KODE_CRUD=$_GET['_id'];
		RecSoftDel($KODE_CRUD);
		if(SYS_ROWS($qTB_BILL)>0) {
			if($aDTL_DT=SYS_FETCH($qTB_BILL)) {
				$cKODE_BILL=$aDTL_DT['PRNTR_CODE'];
				$qQUERY=OpenTable('TbBillPrn', "APP_CODE='$cAPP_CODE' and BILL_CODE='$cKODE_BILL' and REC_ID not in ( select DEL_ID from logs_delete)");
				while($aDT_BILL=SYS_FETCH($qQUERY)){
					RecSoftDel($aDT_BILL['REC_ID']);
				}
			}
		}
		SYS_DB_CLOSE($DB2);	
		header('location:tb_prin.php');
}
?>

