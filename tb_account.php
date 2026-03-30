<?php
require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include "sysfunction.php";

	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 	= $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/COA.pdf';

	$sPERIOD1	= $_SESSION['sCURRENT_PERIOD'];
	$can_LYEAR 	= TRUST($cUSERCODE, 'COA_LYEAR');
	$can_BEG_UP = TRUST($cUSERCODE, 'COA_BEG_UPD');

	$aTYPE_ACCOUNT = array(1=> S_MSG('TA33','Aset/Harta'));
		array_push($aTYPE_ACCOUNT,	S_MSG('TA34','Utang'));
		array_push($aTYPE_ACCOUNT,	S_MSG('TA35','Modal/Laba'));
		array_push($aTYPE_ACCOUNT,	S_MSG('TA36','Pendapatan'));
		array_push($aTYPE_ACCOUNT,	S_MSG('TA37','Biaya/HPP'));
	$aGENERAL 		= array(1=> S_MSG('TA26','General'), S_MSG('TA28','Detil'));
	$cHEADER 		= S_MSG('TA20','Chart of Account');
	$cKODE_ACCOUNT 	= S_MSG('TA21','Kode Account');
	$cNAMA_ACCOUNT 	= S_MSG('TA23','Nama Account');
	$cGEN_ACCOUNT 	= S_MSG('TA25','General Account');
	$cTYPE_ACCOUNT 	= S_MSG('TA27','Tipe Account');
	$cGEN_DTL 		= S_MSG('TA29','General / Detil');
	$cLEVEL 		= S_MSG('TA31','Level');
	$cLBL_PRINT		= S_MSG('TA13','Print jika saldo 0');
	$cZERO_PRINT	= S_PARA('ZERO_PRINT', '');

	$cPICT_ACCT		= S_PARA('PICT_ACCT', '999999');
	if($cPICT_ACCT=='')	$cPICT_ACCT='999999';

	$cSAVE	= S_MSG('F301','Save');
	$cCLOSE	= S_MSG('F302','Close');

	$cACTION=(isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');
	$IS_NON_PROFIT= IS_NON_PROFIT($cAPP_CODE);

switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'COA_1ADD');
		$can_EXCEL 	= TRUST($cUSERCODE, 'COA_5EXCEL');
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'View');
		DEF_WINDOW($cHEADER);
			$aACT =[];
			if ($can_CREATE) 
				array_push($aACT, '<a href="?_a='.md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'.S_MSG('KA11','Add new').'</a>');
			if ($can_EXCEL) 
				array_push($aACT, '<a href="?_a='. md5('to_excel'). '"><i class="fa fa-file-excel-o"></i>Excel</a></li>');
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example4');
						$aHEAD=[$cKODE_ACCOUNT, $cNAMA_ACCOUNT, $cGEN_ACCOUNT, $cTYPE_ACCOUNT, $cGEN_DTL, $cLEVEL];
						if($IS_NON_PROFIT)	array_push($aHEAD,	'Status');
						THEAD($aHEAD);
						echo '<tbody>';
							$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )", '', 'TYPE_ACCT, ACCOUNT_NO');
							while($aREC_ACCOUNT=SYS_FETCH($qQUERY)) {
								$cIDENT='';
								$nLEVEL=intval($aREC_ACCOUNT['LEVEL']);
								$cGENRL=$aREC_ACCOUNT['GENERAL'];
								if($nLEVEL>1) {
									for($I=1; $I<$nLEVEL; $I++) {
										$cIDENT.='<ul>';
									}
								}
								$cSTRONG=$eSTRONG='';
								if($cGENRL=='G')	{
									$cSTRONG='<strong>';
									$eSTRONG='</strong>';
								}
								$nTYPE=$aREC_ACCOUNT['TYPE_ACCT'];
								$cTYPE=($nTYPE>0 ? $aTYPE_ACCOUNT[$nTYPE] : '');
								$nLEVEL=$aREC_ACCOUNT['LEVEL'];
								if($nLEVEL>1) {
									for($I=1; $I<5; $I++) {
										echo '<ul>';
									}
								}

								echo '<tr>';
									echo '<td style="width: 1px;"></td>';
									echo "<td style='text-align:left'><span><a href='?_a=".md5('upd4t3')."&_r=".md5($aREC_ACCOUNT['REC_ID'])."'>".$aREC_ACCOUNT['ACCOUNT_NO']."  </a></span></td>";
									echo "<td><span><a href='?_a=".md5('upd4t3')."&_r=".md5($aREC_ACCOUNT['REC_ID'])."'>".$cIDENT.$cSTRONG.DECODE($aREC_ACCOUNT['ACCT_NAME']).$eSTRONG."  </a></span></td>";
									echo '<td>'.$aREC_ACCOUNT['GEN_ACCT'].'</td>';
									echo '<td>'.$cTYPE.'</td>';
									echo '<td>'.$cGENRL.'</td>';
									echo '<td>'.$nLEVEL.'</td>';
									if($IS_NON_PROFIT)	echo '<td>'.($aREC_ACCOUNT['SPE_CIFIC']==1 ? 'Terikat' : '').'</td>';
								echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('cr34t3'):
		DEF_WINDOW($cHEADER);
		TFORM(S_MSG('TA10','Tambah Account Baru'), '?_a=tambah');
			TDIV();
				LABEL([4,4,4,6], '700', $cKODE_ACCOUNT);
				INPUT('text', [2,2,2,3], '900', 'ADD_ACCOUNT_NO', '', 'focus', $cPICT_ACCT, '', 10, '', 'fix');
				LABEL([4,4,4,6], '700', $cNAMA_ACCOUNT);
				INPUT('text', [6,6,6,6], '900', 'ADD_ACCT_NAME', '', '', '', '', 50, '', 'fix');
				LABEL([4,4,4,6], '700', $cGEN_ACCOUNT);
				INPUT('text', [2,2,2,3], '900', 'ADD_GEN_ACCT', '', '', $cPICT_ACCT, '', 10, '', 'fix');
				LABEL([4,4,4,6], '700', $cTYPE_ACCOUNT);
				SELECT([3,3,3,3], 'ADD_TYPE_ACCT');
					for ($I=1; $I<=5; $I++){
						echo "<option class='form-label-900' value=$I>".$aTYPE_ACCOUNT[$I]."</option>"; 
					}
				echo '</select>';
				CLEAR_FIX();
				LABEL([4,4,4,6], '700', $cGEN_DTL);
				SELECT([3,3,3,3], 'ADD_GENERAL');
					for ($I=1; $I<=2; $I++){
						echo "<option class='form-label-900' value='$aGENERAL[$I]'>".$aGENERAL[$I]."</option>"; 
					}
				echo '</select>';
				CLEAR_FIX();
				LABEL([4,4,4,6], '700', $cLEVEL);
				INPUT('text', [1,1,1,3], '900', 'ADD_LEVEL', '', '', '', '', 1, '', 'fix');
				SAVE($cSAVE);
			eTDIV();
		eTFORM();
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('upd4t3'):
		$can_UPDATE = TRUST($cUSERCODE, 'COA_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'COA_3DEL');
		$can_BEG_VW = TRUST($cUSERCODE, 'COA_BEG_VIEW');
		$cREC_ACCOUNT = $_GET['_r'];
		$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and md5(REC_ID)='$cREC_ACCOUNT' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qQUERY)==0){
			header('location:tb_account.php');
		}
		$REC_ACCOUNT=SYS_FETCH($qQUERY);
		$nLYEAR = 0;
		$dBOY= new DateTime(substr($sPERIOD1,0,5).'01-01');
		$dEOY=date_modify($dBOY,'-1 day');
		$cEOY=$dEOY->format('Y-m-d');
		$cLAST_YEAR=substr($cEOY,0,4);
		$cLAST_MONTH=substr($cEOY,5,2);
		$qLYEAR = OpenTable('BalanceHdr', "ACCOUNT_NO='$REC_ACCOUNT[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
		if($aLAST = SYS_FETCH($qLYEAR))	$nLYEAR=$aLAST['CUR_BALANC'];
		$nBEG = 0;
		$qTYEAR = OpenTable('BalanceHdr', "ACCOUNT_NO='$REC_ACCOUNT[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = ".substr($sPERIOD1,0,4)." and BLNC_MONTH=" .substr($sPERIOD1,5,2));
		$aTHIS = SYS_FETCH($qTYEAR);
		if ($aTHIS>0)	$nBEG=$aTHIS['BEG_BALANC'];
		$cHEADER=S_MSG('TA11','Edit Chart of Account');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=d3l3t3&_r='. $REC_ACCOUNT['REC_ID']. '" onClick="return confirm('. "'". S_MSG("F021","Benar data ini mau di hapus ?"). "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cHEADER, "?_a=rubah&_r=".$REC_ACCOUNT['ACCOUNT_NO'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_ACCOUNT);
					INPUT('text', [2,2,2,6], '900', 'EDIT_KODE_ACCOUNT', $REC_ACCOUNT['ACCOUNT_NO'], '', '', '', 10, 'disabled', 'fix');
					LABEL([4,4,4,6], '700', $cNAMA_ACCOUNT);
					INPUT('text', [6,6,6,6], '900', 'EDIT_ACCT_NAME', DECODE($REC_ACCOUNT['ACCT_NAME']), '', '', '', 50, '', 'fix');
					LABEL([4,4,4,6], '700', $cGEN_ACCOUNT);
					INPUT('text', [2,2,2,6], '900', 'EDIT_GEN_ACCT', DECODE($REC_ACCOUNT['GEN_ACCT']), '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cTYPE_ACCOUNT);
					SELECT([3,3,3,3], 'EDIT_TYPE_ACCT');
						for ($I=1; $I<=5; $I++){
							if ($I == $REC_ACCOUNT['TYPE_ACCT'])
								echo "<option class='form-label-900' value=$I selected>".$aTYPE_ACCOUNT[$I]."</option>";
							else 
								echo "<option class='form-label-900' value=$I>".$aTYPE_ACCOUNT[$I]."</option>"; 
						}
					echo '</select>';
					CLEAR_FIX();
					LABEL([4,4,4,6], '700', $cGEN_DTL);
					INPUT('text', [1,1,1,6], '900', 'EDIT_GENERAL', DECODE($REC_ACCOUNT['GENERAL']), '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cLEVEL);
					INPUT('text', [1,1,1,6], '900', 'EDIT_LEVEL', DECODE($REC_ACCOUNT['LEVEL']), '', '', '', 0, '', 'fix');

					if ($can_LYEAR) {
						LABEL([4,4,4,6], '700', 'Saldo Tahun Lalu');
						INPUT('number', [2,2,2,4], '900', 'EDIT_LYEAR', $nLYEAR, '', '', 'right', 0, '', 'fix');
					}
					if (($can_BEG_VW==1 || $can_BEG_UP==1) && $REC_ACCOUNT['GENERAL']=='D') {
						LABEL([4,4,4,6], '700', S_MSG('I006', 'Saldo Awal'));
						INPUT('number', [2,2,2,4], '900', 'EDIT_BEG_BL', $nBEG, '', '', 'right', 0, ($can_BEG_UP ? "" : 'disabled="disabled"'), 'fix');
					}
					LABEL([4,4,4,6], '700', $cLBL_PRINT);
					RADIO('UPD_ZERO_PRINT', [1,0], [$REC_ACCOUNT['ZERO_PRINT']==1, $REC_ACCOUNT['ZERO_PRINT']!=1], ['Ya', 'Tidak']);
					CLEAR_FIX();
					if($IS_NON_PROFIT){
						LABEL([4,4,4,6], '700', 'Terikat');
						RADIO('UPD_ZERO_PRINT', [1,0], [$REC_ACCOUNT['SPE_CIFIC']==1, $REC_ACCOUNT['SPE_CIFIC']!=1], ['Ya', 'Tidak']);
					}
					CLEAR_FIX();
					$cSAVE		= ($can_UPDATE ? S_MSG('F301','Save') : '');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

case 'tambah':
	$sKODE_ACCT=str_replace('_', '', $_POST['ADD_ACCOUNT_NO']);
	$pKODE_ACCT	= str_replace('_', '', $sKODE_ACCT);
	$pKODE_ACCT	= str_replace('..', '.', $pKODE_ACCT);
	$pKODE_ACCT	= str_replace('. ', '', $pKODE_ACCT);
	if($pKODE_ACCT=='') {
		MSG_INFO(S_MSG('NJ80','Kode Account tidak boleh kosong'));
		return;
	}
	$qCEK_KODE=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and ACCOUNT_NO='$pKODE_ACCT' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qCEK_KODE)>0){
		MSG_INFO(S_MSG('TA08','Kode Account sudah ada'));
		return;
	}
	$nLEVEL = 1;
	$cTYPE = $_POST['ADD_TYPE_ACCT'];
	$cGEN_ACCT = str_replace('.__', '', $_POST['ADD_GEN_ACCT']);
	$sGEN_ACCT = str_replace('._', '', $cGEN_ACCT);
	$sGEN_ACCT = str_replace('_', '', $cGEN_ACCT);
	$cGEN_ACCT	= str_replace('..', '.', $sGEN_ACCT);
	$cGEN_ACCT	= trim($sGEN_ACCT);
	if ($cGEN_ACCT>'') {
		$qCEK_GEN=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and ACCOUNT_NO='$cGEN_ACCT' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qCEK_GEN)==0) {
			MSG_INFO("Kode akun induk : ".$cGEN_ACCT." tidak di temukan !");
			return;
		}
		$aACCT = SYS_FETCH($qCEK_GEN);
		RecUpdate('TbAccount', ['GENERAL'], ['G'], "REC_ID='$aACCT[REC_ID]'");
		$nLEVEL = strval($aACCT['LEVEL'])+1;
		$cTYPE = $aACCT['TYPE_ACCT'];
	}
	$cACCT_NAME = ENCODE($_POST['ADD_ACCT_NAME']);
	RecCreate('TbAccount', ['ACCOUNT_NO', 'ACCT_NAME', 'GENERAL', 'GEN_ACCT', 'TYPE_ACCT', 'LEVEL', 'ENTRY', 'REC_ID', 'APP_CODE'],
		[$pKODE_ACCT, $cACCT_NAME, 'D', $cGEN_ACCT, $cTYPE, $nLEVEL, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
	APP_LOG_ADD($cHEADER, 'Add account : '.$sKODE_ACCT);
	header('location:tb_account.php');
	break;

case 'rubah':
	$cACCT_CODE=str_replace('_', '', $_GET['_r']);
	$cGEN_ACCT=str_replace('.__', '',  $_POST['EDIT_GEN_ACCT']);
	$cGEN_ACCT=str_replace('_', '',  $cGEN_ACCT);
	APP_LOG_ADD($cHEADER, 'Upd account : '.$cACCT_CODE);
	$cACCT_NAME = ENCODE($_POST['EDIT_ACCT_NAME']);
	$nZERO_PRINT=$nUPD_SPECIFIC=0;
	if (isset($_POST['UPD_ZERO_PRINT'])) $nZERO_PRINT=$_POST['UPD_ZERO_PRINT'];
	if (isset($_POST['UPD_SPECIFIC'])) $nUPD_SPECIFIC=$_POST['UPD_SPECIFIC'];
	RecUpdate('TbAccount', ['ACCT_NAME', 'GEN_ACCT', 'TYPE_ACCT', 'GENERAL', 'LEVEL', 'ZERO_PRINT', 'SPE_CIFIC'], 
		[$cACCT_NAME, $cGEN_ACCT, $_POST['EDIT_TYPE_ACCT'], $_POST['EDIT_GENERAL'], $_POST['EDIT_LEVEL'], $nZERO_PRINT, $nUPD_SPECIFIC], 
		"ACCOUNT_NO='$cACCT_CODE' and APP_CODE='$cAPP_CODE'");
	if ($can_LYEAR==1) {
		$nVALUE 	= str_replace(',', '', $_POST['EDIT_LYEAR']);
//		if ($nVALUE>0) {
			$dBOY= new DateTime(substr($sPERIOD1,0,5).'01-01');
			$cBOY=$dBOY->format('Y-m-d');
			$dEOY=date_modify($dBOY,'-1 day');
			$cEOY=$dEOY->format('Y-m-d');
			$cLYEAR=substr($cEOY,0,4);
			$cLMONTH=substr($cEOY,5,2);
			$qBLNC_CEK = OpenTable('BalanceHdr', "ACCOUNT_NO='$cACCT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLYEAR and BLNC_MONTH= $cLMONTH");
			if (!$qBLNC_CEK || SYS_ROWS($qBLNC_CEK)==0) {
				RecCreate('BalanceHdr', ['ACCOUNT_NO', 'CUR_BALANC', 'BLNC_YEAR', 'BLNC_MONTH', 'APP_CODE', 'REC_ID'], [$cACCT_CODE, $nVALUE, $cLYEAR, $cLMONTH, $cAPP_CODE, NowMSecs()]);
			} else {
				RecUpdate('BalanceHdr', ['CUR_BALANC'], [$nVALUE], 
					"ACCOUNT_NO='$cACCT_CODE' and APP_CODE='$cAPP_CODE' and BLNC_YEAR = '$cLYEAR' and BLNC_MONTH= '$cLMONTH'");
			}

			$cBYEAR=substr($cBOY,0,4);
			$cBMONTH=substr($cBOY,5,2);
			$qBLNC_CEK = OpenTable('BalanceHdr', "ACCOUNT_NO='$cACCT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cBYEAR and BLNC_MONTH= $cBMONTH");
			if (!$qBLNC_CEK || SYS_ROWS($qBLNC_CEK)==0) {
				RecCreate('BalanceHdr', ['ACCOUNT_NO', 'BEG_BALANC', 'BLNC_YEAR', 'BLNC_MONTH', 'APP_CODE', 'REC_ID'], [$cACCT_CODE, $nVALUE, $cBYEAR, $cBMONTH, $cAPP_CODE, NowMSecs()]);
			} else {
				RecUpdate('BalanceHdr', ['BEG_BALANC'], [$nVALUE], 
					"ACCOUNT_NO='$cACCT_CODE' and APP_CODE='$cAPP_CODE' and BLNC_YEAR = '$cBYEAR' and BLNC_MONTH= '$cBMONTH'");
			}
//		}
	}
	if ($can_BEG_UP==1) {
		$cTHIS_YEAR=substr($sPERIOD1,0,4);
		$cTHIS_MONTH=substr($sPERIOD1,5,2);
		$nBEG = str_replace(',', '', $_POST['EDIT_BEG_BL']);
		$qBLNC_CEK = OpenTable('BalanceHdr', "ACCOUNT_NO='$cACCT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cTHIS_YEAR and BLNC_MONTH= $cTHIS_MONTH");
		if (!$qBLNC_CEK || SYS_ROWS($qBLNC_CEK)==0)
			RecCreate('BalanceHdr', ['ACCOUNT_NO', 'BEG_BALANC', 'BLNC_YEAR', 'BLNC_MONTH', 'APP_CODE', 'REC_ID'], [$cACCT_CODE, $nBEG, $cTHIS_YEAR, $cTHIS_MONTH, $cAPP_CODE, NowMSecs()]);
		RecUpdate('BalanceHdr', ['BEG_BALANC'], [$nBEG], 
			"ACCOUNT_NO='$cACCT_CODE' and APP_CODE='$cAPP_CODE' and BLNC_YEAR = '$cTHIS_YEAR' and BLNC_MONTH= '$cTHIS_MONTH'");
	}
	header('location:tb_account.php');
	break;

case 'd3l3t3':
	$cACCT_CODE = $_GET['_r'];
	$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and md5(REC_ID)='$cACCT_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if($aACCT = SYS_FETCH($qQUERY))	{
		$cACCOUNT =$aACCT['ACCOUNT_NO'];
		$qIFACE=OpenTable('GlInterface', "APP_CODE='$cAPP_CODE' and GI_VAL='$cACCOUNT' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qIFACE)>0) {
			MSG_INFO(S_MSG('MS32','Kode account masih digunakan di G/L interface, belum bisa di hapus'));
			return;
		}
		$qGEN=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and GEN_ACCT='$cACCOUNT' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qGEN)>0) {
			MSG_INFO(S_MSG('MS33','Ada account yang punya induk account ini, belum bisa di hapus'));
			return;
		}
		RecDelete('PrLedger', "APP_CODE='$cAPP_CODE' and ACCOUNT_NO='$cACCOUNT'");
		RecDelete('BalanceHdr', "APP_CODE='$cAPP_CODE' and ACCOUNT_NO='$cACCOUNT'");
		RecDelete('BalanceDtl', "APP_CODE='$cAPP_CODE' and ACCOUNT_NO='$cACCOUNT'");
	}
	 else 
		header('location:tb_account.php');
	APP_LOG_ADD($cHEADER, 'Del account : '.$cACCT_CODE);
	RecSoftDel($cACCT_CODE);
	break;

case md5('to_excel'):
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	
	$sheet->getStyle('A')->getNumberFormat()->setFormatCode('@');
	$sheet->getStyle('H')->getNumberFormat()->setFormatCode('@');
    $sheet->getColumnDimension('A')->setWidth(100, 'px');
    $sheet->getColumnDimension('B')->setWidth(30, 'px');
    $sheet->getColumnDimension('C')->setWidth(30, 'px');
    $sheet->getColumnDimension('D')->setWidth(30, 'px');
    $sheet->getColumnDimension('E')->setWidth(30, 'px');
    $sheet->getColumnDimension('F')->setWidth(30, 'px');
    $sheet->getColumnDimension('G')->setWidth(300, 'px');
    $sheet->getColumnDimension('H')->setWidth(80, 'px');
    $sheet->getColumnDimension('J')->setWidth(100, 'px');
	$cFONT_COM = 'Arial';   $nSIZE_COM = 12;  $cBOLD_COM=' '; $cITAL_COM=' '; $cUNDRL_COM=' ';
	$qFONT= OpenTable('TbFont', "KEY_ID='COA_HEAD' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	if($aFONT = SYS_FETCH($qFONT)) {
		$cFONT_COM = $aFONT['NAME'];
		if($aFONT['BOLD']==1)	$cBOLD_COM='B';
		if($aFONT['ITALIC']==1)	$cITAL_COM='I';
		if($aFONT['UNDERLINE']==1)	$cUNDRL_COM='U';
		$nSIZE_COM = intval($aFONT['SIZE']);
	}
    $sheet->getStyle('A1:C3')->getFont()->setName($cFONT_COM);
    $sheet->getStyle('A1:C3')->getFont()->setSize($nSIZE_COM);
    if($cBOLD_COM=='B') $sheet->getStyle('A1')->getFont()->setBold(true);
    if($cITAL_COM=='I') $sheet->getStyle('A1')->getFont()->setItalic(true);
    if($cUNDRL_COM=='U') $sheet->getStyle('A1')->getFont()->setUnderline(true);

	$aLV_COLUMN = ['A', 'B', 'C', 'D', 'E', 'F'];

    $sheet->setCellValue('A1', S_PARA('CO1','Rainbow Co.'));
    $sheet->setCellValue('A2', S_PARA('CO2','Rainbow Co.'));
    $sheet->setCellValue('C3', $cHEADER);
    $sheet->setCellValue('A5', $cKODE_ACCOUNT);
    $sheet->setCellValue('B5', $cNAMA_ACCOUNT);
    $sheet->setCellValue('H5', $cGEN_ACCOUNT);
    $sheet->setCellValue('I5', $cTYPE_ACCOUNT);
    $sheet->setCellValue('J5', $cGEN_DTL);
    $sheet->getStyle('A5:J5')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A5:J5')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	$nROW=6;
	$qACCOUNT=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'ACCOUNT_NO');
	while($aREC_ACCOUNT=SYS_FETCH($qACCOUNT)) {
		$nLVL = (integer)$aREC_ACCOUNT['LEVEL'];
		$cGEN = $aREC_ACCOUNT['GENERAL'];
		$nROW++;
		CELL_BY_COL_ROW($sheet, 1, $nROW, $aREC_ACCOUNT['ACCOUNT_NO']);
		CELL_BY_COL_ROW($sheet, $nLVL+2, $nROW, DECODE($aREC_ACCOUNT['ACCT_NAME']));
		CELL_BY_COL_ROW($sheet, 8, $nROW, DECODE($aREC_ACCOUNT['GEN_ACCT']));
		CELL_BY_COL_ROW($sheet, 9, $nROW, DECODE($aREC_ACCOUNT['TYPE_ACCT']));
		CELL_BY_COL_ROW($sheet, 10, $nROW, DECODE($aREC_ACCOUNT['GENERAL']));
		if($cGEN=='G') {
			for ($I=1; $I<6; $I++) {
				$sheet->getStyle($aLV_COLUMN[$I].(string)$nROW)->getFont()->setBold(true);
			}
		}
	}

	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="coa.xlsx"');
	header('Cache-Control: max-age=0');
	
	// (E) OUTPUT
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
	exit($writer->save('php://output'));
	echo "<script> alert('OK');	window.history.back();	</script>";
	
	APP_LOG_ADD($cHEADER, 'Account to excel : ');
	header('location:tb_account.php');
}
?>
	

<script>
$(document).ready( function () {
	table.destroy();
  var table = $('#example').DataTable({
    columnDefs: [
      {
        orderable: false, targets: 0,
        type: 'string'
      }
    ]
  });
} );

</script>


