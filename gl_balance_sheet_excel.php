<?php

// gl_balance_sheet_excel.php
//  TODO : Continue to var data

require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE  = $_SESSION['data_FILTER_CODE']; 
$cUSERCODE  = $_SESSION['gUSERCODE'];
$sPERIOD1   = $_SESSION['sCURRENT_PERIOD'];
$can_LYEAR 	= TRUST($cUSERCODE, 'NERACA_LYEAR');
if($can_LYEAR==1) {
    $dBOY= new DateTime(substr($sPERIOD1,0,5).'01-01');
    $dEOY=date_modify($dBOY,'-1 day');
    $cEOY=$dEOY->format('Y-m-d');
    $cLAST_YEAR=substr($cEOY,0,4);
    $cLAST_MONTH=substr($cEOY,5,2);
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$spreadsheet->getProperties()
    ->setCreator('Rainbow System')
    ->setTitle($cHEADER)
    ->setDescription('This excel is auto create from Rainbow System, for '.S_PARA('CO1',''));

$nSHEET=0;

if($can_NRC_EXCEL==1) {
    $cFORM  = 'NERACA_XLS';
    EXCEL_HDR($sheet, $cFORM);
    $sheet->setTitle(S_MSG('RF02', 'N E R A C A'));

	$cFONT_DTL = 'Arial';   $nSIZE_DTL = 12;  $cBOLD_DTL=' '; $cITAL_DTL=' '; $cUNDRL_DTL=' ';
	$qFONT= OpenTable('TbFont', "KEY_ID='ARIAL12' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	if($aFONT = SYS_FETCH($qFONT)) {
		$cFONT_DTL = $aFONT['NAME'];
		if($aFONT['BOLD']==1)	$cBOLD_DTL='B';
		if($aFONT['ITALIC']==1)	$cITAL_DTL='I';
		if($aFONT['UNDERLINE']==1)	$cUNDRL_DTL='U';
		$nSIZE_DTL = intval($aFONT['SIZE']);
	}
    $sheet->getStyle('H')->getFont()->setName($cFONT_DTL);
    $sheet->getStyle('I')->getFont()->setName($cFONT_DTL);

    for($I = 1; $I<=9; $I++):
		$J=(string)$I;
		if(GET_FORMAT($cFORM, 'DETAIL'.$J.'_STAT')=='1') {
            $nDTL_COL=intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
            if($nDTL_COL>0)
                $cDTL_COL=GET_FORMAT($cFORM, 'DETAIL'.$J.'_LABEL');
                if($cDTL_COL>'')
                    $sheet->getColumnDimension($cDTL_COL)->setWidth($nDTL_COL, 'px');
		}
	endfor;
    $cCTL=GET_FORMAT($cFORM, 'DETAIL_START_ROW');
    $nCTL=($cCTL=='' ? 0 : intval($cCTL));
    $qNERACA = OpenTable('AccountBalance', "A.TYPE_ACCT in ('1', '2', '3') and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'A.ACCOUNT_NO');
    while($aNERACA = SYS_FETCH($qNERACA)) {
        $nLVL=intval($aNERACA['LEVEL']);
        $sheet->setCellValueByColumnAndRow($nLVL, $nCTL, DECODE($aNERACA['ACCT_NAME']));
        $sheet->setCellValueByColumnAndRow(8, $nCTL, $aNERACA['CUR_BALANC']);
        $sheet->getCellByColumnAndRow(8,$nCTL)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
        if($can_LYEAR==1) {
            $qLASTYR = OpenTable('AccountBalance', "A.ACCOUNT_NO ='$aNERACA[ACCOUNT_NO]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=$cLAST_YEAR and B.BLNC_MONTH=$cLAST_MONTH", '', 'A.ACCOUNT_NO');
            if ($aLASTYR = SYS_FETCH($qLASTYR))	
                $sheet->setCellValueByColumnAndRow(9, $nCTL, $aLASTYR['CUR_BALANC']);
                $sheet->getCellByColumnAndRow(9,$nCTL)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
            }
        $nCTL++;
    }
    $sheet->getStyle('H')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle('I')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_RIGHT);
    $nSHEET++;
    $worksheet2 = $spreadsheet->createSheet();
    $sheet = $spreadsheet->getSheet($nSHEET);
}

if($can_RL_EXCEL==1) {
    $cFORM  = 'LABARUGI_XLS';
    EXCEL_HDR($sheet, $cFORM);
    $sheet->setTitle(substr(S_MSG('RF03', 'R/L'),0,31));

	$cFONT_DTL = 'Arial';   $nSIZE_DTL = 12;  $cBOLD_DTL=' '; $cITAL_DTL=' '; $cUNDRL_DTL=' ';
	$qFONT= OpenTable('TbFont', "KEY_ID='ARIAL12' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	if($aFONT = SYS_FETCH($qFONT)) {
		$cFONT_DTL = $aFONT['NAME'];
		if($aFONT['BOLD']==1)	$cBOLD_DTL='B';
		if($aFONT['ITALIC']==1)	$cITAL_DTL='I';
		if($aFONT['UNDERLINE']==1)	$cUNDRL_DTL='U';
		$nSIZE_DTL = intval($aFONT['SIZE']);
	}
    $sheet->getStyle('H')->getFont()->setName($cFONT_DTL);
    $sheet->getStyle('I')->getFont()->setName($cFONT_DTL);
    // $sheet->getStyle('A1:C3')->getFont()->setSize($nSIZE_DTL);
    // if($cBOLD_DTL=='B') $sheet->getStyle('A1')->getFont()->setBold(true);
    // if($cITAL_DTL=='I') $sheet->getStyle('A1')->getFont()->setItalic(true);
    // if($cUNDRL_DTL=='U') $sheet->getStyle('A1')->getFont()->setUnderline(true);


    for($I = 1; $I<=9; $I++):
		$J=(string)$I;
		if(GET_FORMAT($cFORM, 'DETAIL'.$J.'_STAT')=='1') {
            $nDTL_COL=intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
            if($nDTL_COL>0)
                $cDTL_COL=GET_FORMAT($cFORM, 'DETAIL'.$J.'_LABEL');
                if($cDTL_COL>'')
                    $sheet->getColumnDimension($cDTL_COL)->setWidth($nDTL_COL, 'px');
		}
	endfor;
    $cCTL=GET_FORMAT($cFORM, 'DETAIL_START_ROW');
    $nCTL=($cCTL=='' ? 0 : intval($cCTL));
    $qNERACA = OpenTable('AccountBalance', "A.TYPE_ACCT in ('4', '5') and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'A.ACCOUNT_NO');
    while($aNERACA = SYS_FETCH($qNERACA)) {
        $nLVL=intval($aNERACA['LEVEL']);
        $sheet->setCellValueByColumnAndRow($nLVL, $nCTL, DECODE($aNERACA['ACCT_NAME']));
        $sheet->setCellValueByColumnAndRow(8, $nCTL, $aNERACA['CUR_BALANC']);
        $sheet->getCellByColumnAndRow(8,$nCTL)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
        if($can_LYEAR==1) {
            $qLASTYR = OpenTable('AccountBalance', "A.ACCOUNT_NO ='$aNERACA[ACCOUNT_NO]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=$cLAST_YEAR and B.BLNC_MONTH=$cLAST_MONTH", '', 'A.ACCOUNT_NO');
            if ($aLASTYR = SYS_FETCH($qLASTYR))	
                $sheet->setCellValueByColumnAndRow(9, $nCTL, $aLASTYR['CUR_BALANC']);
                $sheet->getCellByColumnAndRow(9,$nCTL)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
            }
        $nCTL++;
    }
    $sheet->getStyle('H')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle('I')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_RIGHT);
    $nSHEET++;
    $worksheet2 = $spreadsheet->createSheet();
    $sheet = $spreadsheet->getSheet($nSHEET);
}

if($can_CALK_EXCEL==1) {
    $sheet->getPageSetup()->setFitToWidth(1);
    $sheet->getColumnDimension('A')->setWidth(44, 'px');
    $sheet->getColumnDimension('B')->setWidth(44, 'px');
    $sheet->getColumnDimension('E')->setWidth(28, 'px');
    $sheet->getColumnDimension('F')->setWidth(220, 'px');
    $sheet->getColumnDimension('G')->setWidth(24, 'px');
    $sheet->getColumnDimension('H')->setWidth(195, 'px');
    $sheet->getColumnDimension('I')->setWidth(24, 'px');
    $sheet->getColumnDimension('J')->setWidth(200, 'px');
    $sheet->getColumnDimension('K')->setWidth(24, 'px');
    $sheet->getColumnDimension('L')->setWidth(200, 'px');

    $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
    $spreadsheet->getDefaultStyle()->getFont()->setSize(14);

    $qCALK = OpenTable('TbCalk', "A.CELL>'' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete )", '', 'A.CODE');
    while($aCALK = SYS_FETCH($qCALK)) {
        $cCELL = $aCALK['CELL'];
        $sheet->setCellValue($cCELL, DECODE($aCALK['CON10']));
        $sheet->getStyle($cCELL)->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_LEFT);
        if($aCALK['CELL2']>'')   {
            $sheet->getStyle($cCELL)->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle($cCELL)->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_LEFT);
            $cCELL=$aCALK['CELL'].":".$aCALK['CELL2'];
            $spreadsheet->getActiveSheet()->getStyle($cCELL)->getAlignment()->setWrapText(true);
            $sheet->mergeCells($cCELL);
        }

    }
    $sheet->getRowDimension('2')->setRowHeight(30, 'pt');
    // $sheet->mergeCells('A1:L1');
    $sheet->getStyle('A1:A4')->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
    $sheet->setTitle(S_MSG('RF07', 'N E R A C A'));
    $nSHEET++;
    $worksheet2 = $spreadsheet->createSheet();
    $sheet = $spreadsheet->getSheet($nSHEET);
}

ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="LK.xlsx"');
header('Cache-Control: max-age=0');

// (E) OUTPUT
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
exit($writer->save('php://output'));
echo "<script> window.history.back();	</script>";

