<?php

// gl_balance_sheet_print.php

require('vendor/fpdf/fpdf.php');

// include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE  = $_SESSION['data_FILTER_CODE']; 
$cUSERCODE  = $_SESSION['gUSERCODE'];
$sPERIOD1   = $_SESSION['sCURRENT_PERIOD'];
$sTYPE      = $_SESSION['REP_TYPE'];
$can_LYEAR 	= TRUST($cUSERCODE, 'NERACA_LYEAR');
$sAC_INC    = [1=>1, 2, 3];
$aJUMLAH    = array(1=> S_MSG('TA33', 'AKTIVA'), S_MSG('TA34', 'PASIVA'), S_MSG('TA35', 'MODAL'));
$cFORM      = S_PARA('FORMAT_NERACA', 'NERACA');
$cSALDO_NOL	= S_PARA('ZERO_PRINT', 'Y');
$cRIGHT_ALIGN 	= S_PARA('FIN_REP_RIGHT_ALIGN', '');

if($sTYPE=='LABARUGI')  {
    $sAC_INC = [1=>4, 5];
    $aJUMLAH = [1=> S_MSG('TA36', 'Pendapatan'), S_MSG('TA37', 'Biaya/HPP')];
    $cFORM  = 'LABARUGI';
}

$qTB_BILL=OpenTable('TbBillPrintHdr', "PRNTR_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
if(SYS_ROWS($qTB_BILL)==0)  return;
$aREC_TB_BILL=SYS_FETCH($qTB_BILL);
$cPAPER = 'A4';
if($aREC_TB_BILL['PAPER_SIZE']>'')   $cPAPER = $aREC_TB_BILL['PAPER_SIZE'];
$cORIEN = 'P';
if($aREC_TB_BILL['ORIENTATION']>'')   $cORIEN = $aREC_TB_BILL['ORIENTATION'];
$pdf=new FPDF($cORIEN, 'mm', $cPAPER);
$R_PDF=PRINT_HDR($pdf, $cFORM, '', 'N', 'N');

$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
$cFONT_CODE = GET_FORMAT($cFORM, 'DETAIL_DTA_FONT_CODE');
$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
if(SYS_ROWS($qFONT)>0) {
    $aFONT = SYS_FETCH($qFONT);
    $cFONT_NAME = $aFONT['NAME'];
    if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
    if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
    if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
    $nFONT_SIZE = $aFONT['SIZE'];
}

$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
$cFONT_CODE = GET_FORMAT($cFORM, 'DETAIL_DTA_FONT_CODE');
$R_PDF->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
$qBILL_COL=OpenTable('TbBillCol', "BILL_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
$I=0;
$nSTART_ROW = intval(GET_FORMAT($cFORM, 'DETAIL_START_ROW'));
$nLINE_PER_PAGE=intval(GET_FORMAT($cFORM, 'LINE_PER_PAGE'));

while($aDTL_COL=SYS_FETCH($qBILL_COL)) {
    $I++;
    $J=(string)$I;
    $cSTTS = 'DETAIL'.$J.'_HEAD_STATUS';
    if(GET_FORMAT($cFORM, $cSTTS)=='1') {
        $cDTL_COL =$aDTL_COL['COL_NAME'];
        $nCOL = intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
        $cSTTS = 'DETAIL'.$J.'_HEAD_STATUS';
//        $R_PDF->Text($nCOL, $nSTART_ROW, GET_FORMAT($cFORM, 'DETAIL'.$J.'_HEAD_LABEL'));
    }
}

$nCTL = $nSTART_ROW + 10;
$nSPACE = intval(GET_FORMAT($cFORM, 'LINE_SPACE'));
$nSPACE = ($nSPACE==0 ? 5 : $nSPACE);

$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
$cREC_HDR = 'per : '.date('t-M-Y', strtotime($sPERIOD1));
if(GET_FORMAT($cFORM, 'KET_CTK')=='1') {
    $cFONT_CODE = GET_FORMAT($cFORM, 'KET_FONT_CODE');
    $qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
    if(SYS_ROWS($qFONT)>0) {
        $aFONT = SYS_FETCH($qFONT);
        $cFONT_NAME = $aFONT['NAME'];
        if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
        if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
        if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
        $nFONT_SIZE = $aFONT['SIZE'];
    }
    $R_PDF->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
    $R_PDF->Text(GET_FORMAT($cFORM, 'KET_LEFT'), GET_FORMAT($cFORM, 'KET_TOP'), $cREC_HDR);
}

$nTOTAL = $nREC_DTL = $nQTY_TYPE=0;
for($nACCT = $sAC_INC[1]; $nACCT<=sizeof($aJUMLAH)+$sAC_INC[1]-1; $nACCT++):
    $nQTY_TYPE++;
    $cFONT_CODE = GET_FORMAT($cFORM, 'DETAIL_DTA_FONT_CODE');
    $qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
    if(SYS_ROWS($qFONT)>0) {
        $aFONT = SYS_FETCH($qFONT);
        $cFONT_NAME = $aFONT['NAME'];
        if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
        if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
        if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
        $nFONT_SIZE = $aFONT['SIZE'];
    }
    $R_PDF->SetFont($cFONT_NAME, $cFONT_STYLE.'B', $nFONT_SIZE);

    $cTYPE=(string)$nACCT;
    $qNERACA = OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and TYPE_ACCT='$cTYPE' and LEVEL='1' and REC_ID not in ( select DEL_ID from logs_delete)");	//  and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2)
    $aNERACA = SYS_FETCH($qNERACA);
    $nTOTAL = 0;
    $nLAST_TOTAL = 0;
    if ($aNERACA>0) {
        $qBLNS = OpenTable('BalanceHdr', "APP_CODE='$cAPP_CODE' and ACCOUNT_NO='$aNERACA[ACCOUNT_NO]' and REC_ID not in ( select DEL_ID from logs_delete) and BLNC_YEAR=".substr($sPERIOD1,0,4). " and BLNC_MONTH=".substr($sPERIOD1,5,2));
        $aDTL = SYS_FETCH($qBLNS);
        if ($aDTL>0) $nTOTAL = $aDTL['CUR_BALANC'];

        $qLYEAR = OpenTable('BalanceHdr', "ACCOUNT_NO='$aNERACA[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
        $aLAST = SYS_FETCH($qLYEAR);
        if ($aLAST>0)	$nLAST_TOTAL=$aLAST['CUR_BALANC'];

		$qBILL_COL=OpenTable('TbBillCol', "BILL_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
		$nREC_DTL = SYS_ROWS($qBILL_COL);
        $I=0;
		while($aDTL_FLD=SYS_FETCH($qBILL_COL)) {
			$I++;
			$J=(string)$I;
			$cFIELD = $aDTL_FLD['COL_CODE'];
			$nCOL = intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
            $cSTATUS=GET_FORMAT($cFORM, 'DETAIL'.$J.'_HEAD_STATUS');
			if($cSTATUS=='1') {
				switch($cFIELD){
					case 'AC_CODE':		    $R_PDF->Text($nCOL, $nCTL, $aNERACA['ACCOUNT_NO']);	break;
					case 'AC_NAME':		    $R_PDF->Text($nCOL, $nCTL, decode_string($aNERACA['ACCT_NAME']));	break;
					case 'CURRENT_BLC':	    $R_PDF->Text($nCOL, $nCTL, CVR($nTOTAL));	break;
					case 'LAST_BALANCE':    $R_PDF->Text($nCOL, $nCTL, CVR($nLAST_TOTAL));	break;
				}
			}
		}

        $qNRC2 = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and GEN_ACCT='$aNERACA[ACCOUNT_NO]' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');
        while($aNRC2 = SYS_FETCH($qNRC2)) {
            $nCTL += $nSPACE;
            if($nLINE_PER_PAGE>0 && $nCTL>$nLINE_PER_PAGE) {
                $nCTL = $nSTART_ROW + 10;
                $R_PDF=PRINT_HDR($R_PDF, $cFORM, '', 'N', 'N');
            }
            $nLAST2 = 0;
            $qLYEAR = OpenTable('BalanceHdr', "ACCOUNT_NO='$aNRC2[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
            $aLAST = SYS_FETCH($qLYEAR);
            if ($aLAST>0)	$nLAST2=$aLAST['CUR_BALANC'];

            $qBILL_COL=OpenTable('TbBillCol', "BILL_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
            $nREC_DTL = SYS_ROWS($qBILL_COL);
            $I=0;
            while($aDTL_FLD=SYS_FETCH($qBILL_COL)) {
                $I++;
                $J=(string)$I;
                $cFIELD = $aDTL_FLD['COL_CODE'];
                $nCOL = intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
                $cSTATUS=GET_FORMAT($cFORM, 'DETAIL'.$J.'_HEAD_STATUS');
                if($cSTATUS=='1') {
                    $R_PDF->SetFont($cFONT_NAME, ($aNRC2['GENERAL']=='G' ? $cFONT_STYLE.'B' : $cFONT_STYLE), $nFONT_SIZE);
                    switch($cFIELD){
                        case 'AC_CODE':         $R_PDF->Text($nCOL+5, $nCTL, $aNRC2['ACCOUNT_NO']);    break;
                        case 'AC_NAME':         $R_PDF->Text($nCOL+5, $nCTL, decode_string($aNRC2['ACCT_NAME']));    break;
                        case 'CURRENT_BLC':     $R_PDF->Text($nCOL-($cRIGHT_ALIGN=='1' ? 0 : 10), $nCTL, CVR($aNRC2['CUR_BALANC']));    break;
                        case 'LAST_BALANCE':    $R_PDF->Text($nCOL, $nCTL, CVR($nLAST2));    break;
                    }
                }
            }
    
            $qNRC3 = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and GEN_ACCT='$aNRC2[ACCOUNT_NO]' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');

            while($aNRC3 = SYS_FETCH($qNRC3)) {
                $nLAST3 = 0;
                $qLYEAR3 = OpenTable('BalanceHdr', "ACCOUNT_NO='$aNRC3[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
                $aLAST3 = SYS_FETCH($qLYEAR3);
                if ($aLAST3>0)	$nLAST3=$aLAST3['CUR_BALANC'];
                if($cSALDO_NOL=='Y' || $aNRC3['CUR_BALANC']>'0' || $nLAST3>0 || $aNRC3['ZERO_PRINT']==1) {
                    $nCTL += $nSPACE;
                    if($nLINE_PER_PAGE>0 && $nCTL>$nLINE_PER_PAGE) {
                        $nCTL = $nSTART_ROW + 10;
                        $R_PDF=PRINT_HDR($R_PDF, $cFORM, '', 'N', 'N');
                    }
                    $qBILL_COL=OpenTable('TbBillCol', "BILL_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
                    $nREC_DTL = SYS_ROWS($qBILL_COL);
                    $I=0;
                    while($aDTL_FLD=SYS_FETCH($qBILL_COL)) {
                        $I++;
                        $J=(string)$I;
                        $cFIELD = $aDTL_FLD['COL_CODE'];
                        $nCOL = intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
                        $cSTATUS=GET_FORMAT($cFORM, 'DETAIL'.$J.'_HEAD_STATUS');
                        if($cSTATUS=='1') {
                            $R_PDF->SetFont($cFONT_NAME, ($aNRC3['GENERAL']=='G' ? $cFONT_STYLE.'B' : $cFONT_STYLE), $nFONT_SIZE);
                            switch($cFIELD){
                                case 'AC_CODE':         $R_PDF->Text($nCOL+10, $nCTL, $aNRC3['ACCOUNT_NO']);     break;
                                case 'AC_NAME':         $R_PDF->Text($nCOL+10, $nCTL, decode_string($aNRC3['ACCT_NAME']));    break;
                                case 'CURRENT_BLC':     $R_PDF->Text($nCOL-($cRIGHT_ALIGN=='1' ? 0 : 20), $nCTL, CVR($aNRC3['CUR_BALANC']));    break;
                                case 'LAST_BALANCE':    $R_PDF->Text($nCOL, $nCTL, CVR($nLAST3));    break;
                            }
                        }
                    }
            
                    $qNRC4 = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and GEN_ACCT='$aNRC3[ACCOUNT_NO]' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');
                    while($aNRC4 = SYS_FETCH($qNRC4)) {
                        $nLAST4 = 0;
                        $qLYEAR4 = OpenTable('BalanceHdr', "ACCOUNT_NO='$aNRC4[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
                        $aLAST4 = SYS_FETCH($qLYEAR4);
                        if ($aLAST4>0)	$nLAST4=$aLAST4['CUR_BALANC'];
                        if($cSALDO_NOL=='Y' || $aNRC4['CUR_BALANC']>'0' || $nLAST4>0 || $aNRC4['ZERO_PRINT']==1) {
                            $nCTL += $nSPACE;
                            if($nLINE_PER_PAGE>0 && $nCTL>$nLINE_PER_PAGE) {
                                $nCTL = $nSTART_ROW + 10;
                                $R_PDF=PRINT_HDR($R_PDF, $cFORM, '', 'N', 'N');
                            }
                            $qBILL_COL=OpenTable('TbBillCol', "BILL_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
                            $nREC_DTL = SYS_ROWS($qBILL_COL);
                            $I=0;
                            while($aDTL_FLD=SYS_FETCH($qBILL_COL)) {
                                $I++;
                                $J=(string)$I;
                                $cFIELD = $aDTL_FLD['COL_CODE'];
                                $nCOL = intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
                                $cSTATUS=GET_FORMAT($cFORM, 'DETAIL'.$J.'_HEAD_STATUS');
                                if($cSTATUS=='1') {
                                    $R_PDF->SetFont($cFONT_NAME, ($aNRC4['GENERAL']=='G' ? $cFONT_STYLE.'B' : $cFONT_STYLE), $nFONT_SIZE);
                                    switch($cFIELD){
                                        case 'AC_CODE':         $R_PDF->Text($nCOL+15, $nCTL, $aNRC4['ACCOUNT_NO']);    break;
                                        case 'AC_NAME':         $R_PDF->Text($nCOL+15, $nCTL, decode_string($aNRC4['ACCT_NAME']));    break;
                                        case 'CURRENT_BLC':     $R_PDF->Text($nCOL-($cRIGHT_ALIGN=='1' ? 0 : 30), $nCTL, CVR($aNRC4['CUR_BALANC']));    break;
                                        case 'LAST_BALANCE':    $R_PDF->Text($nCOL, $nCTL, CVR($nLAST4));    break;
                                    }
                                }
                            }

                            $qNRC5 = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and GEN_ACCT='$aNRC4[ACCOUNT_NO]' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');
                            while($aNRC5 = SYS_FETCH($qNRC5)) {
                                $nLAST5 = 0;
                                $qLYEAR5 = OpenTable('BalanceHdr', "ACCOUNT_NO='$aNRC5[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
                                $aLAST5 = SYS_FETCH($qLYEAR5);
                                if ($aLAST5>0)	$nLAST5=$aLAST5['CUR_BALANC'];
                                if($cSALDO_NOL=='Y' || $aNRC5['CUR_BALANC']>'0' || $nLAST5>0 || $aNRC5['ZERO_PRINT']==1) {
                                    $nCTL += $nSPACE;
                                    if($nLINE_PER_PAGE>0 && $nCTL>$nLINE_PER_PAGE) {
                                        $nCTL = $nSTART_ROW + 10;
                                        $R_PDF=PRINT_HDR($R_PDF, $cFORM, '', 'N', 'N');
                                    }
                                    $qBILL_COL=OpenTable('TbBillCol', "BILL_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
                                    $nREC_DTL = SYS_ROWS($qBILL_COL);
                                    $I=0;
                                    while($aDTL_FLD=SYS_FETCH($qBILL_COL)) {
                                        $I++;
                                        $J=(string)$I;
                                        $cFIELD = $aDTL_FLD['COL_CODE'];
                                        $nCOL = intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
                                        $cSTATUS=GET_FORMAT($cFORM, 'DETAIL'.$J.'_HEAD_STATUS');
                                        if($cSTATUS=='1') {
                                            switch($cFIELD){
                                                case 'AC_CODE':        $R_PDF->Text($nCOL+20, $nCTL, $aNRC5['ACCOUNT_NO']);    break;
                                                case 'AC_NAME':        $R_PDF->Text($nCOL+20, $nCTL, decode_string($aNRC5['ACCT_NAME']));    break;
                                                case 'CURRENT_BLC':    $R_PDF->Text($nCOL-($cRIGHT_ALIGN=='1' ? 0 : 40), $nCTL, CVR($aNRC5['CUR_BALANC']));    break;
                                                case 'LAST_BALANCE':   $R_PDF->Text($nCOL, $nCTL, CVR($nLAST5));    break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    $nCTL = $nSTART_ROW + 10;
    if($nQTY_TYPE!=sizeof($aJUMLAH))   
        $R_PDF=PRINT_HDR($R_PDF, $cFORM, '', 'N', 'N');
    else {
		for($I = 1; $I<=9; $I++):
			$J=(string)$I;
			if(GET_FORMAT($cFORM, 'LINE'.$J.'_CTK')=='1') {
				$nPOINT=intval(GET_FORMAT($cFORM, 'LINE'.$J.'_POINT'));
				if($nPOINT==0)	$nPOINT=1;
				$pdf->SetLineWidth($nPOINT/10);
				$pdf->Line(GET_FORMAT($cFORM, 'LINE'.$J.'_LEFT_COL'), GET_FORMAT($cFORM, 'LINE'.$J.'_LEFT_ROW'), GET_FORMAT($cFORM, 'LINE'.$J.'_RIGHT_COL'), GET_FORMAT($cFORM, 'LINE'.$J.'_RIGHT_ROW'));
			}
		endfor;
		for($I = 1; $I<=9; $I++):			//	box
			$J=(string)$I;
			if(GET_FORMAT($cFORM, 'BOX'.$J.'_CTK')=='1') {
				$nCOL = GET_FORMAT($cFORM, 'BOX'.$J.'_LEFT_COL');
				$nROW  = GET_FORMAT($cFORM, 'BOX'.$J.'_LEFT_ROW');
				$nRIGHT= GET_FORMAT($cFORM, 'BOX'.$J.'_RIGHT_COL');
				$nBOTTOM= GET_FORMAT($cFORM, 'BOX'.$J.'_RIGHT_ROW');
				$nWIDTH = $nRIGHT - $nCOL;
				$nHEIGHT= $nBOTTOM - $nROW;
				$nBOX_LINE = 0.1;
				if(GET_FORMAT($cFORM, 'BOX'.$J.'_POINT')>0) $nBOX_LINE = intval(GET_FORMAT($cFORM, 'BOX'.$J.'_POINT')/10);
				$pdf->SetLineWidth($nBOX_LINE);
				$pdf->Rect($nCOL, $nROW, $nWIDTH, $nHEIGHT);
			}
		endfor;
        for($I = 1; $I<=9; $I++):
            $J=(string)$I;
            if(GET_FORMAT($cFORM, 'KONST'.$J.'_ADD_STAT')=='1') {
                $cFONT_CODE = GET_FORMAT($cFORM, 'KONST'.$J.'_ADD_FONT');
                $qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
                if(SYS_ROWS($qFONT)>0) {
                    $aFONT = SYS_FETCH($qFONT);
                    $cFONT_NAME = $aFONT['NAME'];
                    if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
                    if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
                    if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
                    $nFONT_SIZE = $aFONT['SIZE'];
                }
                $nCOL=GET_FORMAT($cFORM, 'KONST'.$J.'_ADD_COL');
                $nROW=GET_FORMAT($cFORM, 'KONST'.$J.'_ADD_ROW');
                if($nCOL>'' && $nROW>'') {
                    $pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
                    $pdf->Text($nCOL, $nROW, GET_FORMAT($cFORM, 'KONST'.$J.'_ADD_CONT'));
                }
            }
        endfor;
    }
endfor;
$R_PDF->Output('D', 'LK.pdf');

?>
<script>
	window.open(R_PDF);
</script>

