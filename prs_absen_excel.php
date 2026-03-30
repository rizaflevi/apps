<?php
// prs_absen_excel.php

require_once "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE = $_SESSION['data_FILTER_CODE']; $cUSERCODE = $_SESSION['gUSERCODE'];

$cPERIOD1=date("Y-m-d");
$cPERIOD2=date("Y-m-d");
if (isset($_GET['_d1'])) $cPERIOD1=$_GET['_d1'];
if (isset($_GET['_d2'])) $cPERIOD2=$_GET['_d2'];

$cFILTER_PERSON='';
if (isset($_GET['_p'])) $cFILTER_PERSON=$_GET['_p'];


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$ada_OUTSOURCING=0;
if (substr(S_PARA('JNS_PRSHN','P'),48,1)!=' ') {
    $ada_OUTSOURCING=1;
    $cCUSTOMER 	= S_MSG('RS04','Customer');
    $cLOKASI	= S_MSG('PF16','Lokasi');
}

$nSHEET = 0;
$cKEY_COM='XLS_ABSENT_HDR';
$cKEY_DTL='XLS_ABSENT_DTL';

$cFONT_COM = 'Arial';   $nSIZE_COM = 12;  $cBOLD_COM=' '; $cITAL_COM=' '; $cUNDRL_COM=' ';
$qFONT= OpenTable('TbFont', "KEY_ID='$cKEY_COM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
if(SYS_ROWS($qFONT)>0) {
    $aFONT = SYS_FETCH($qFONT);
    $cFONT_COM = $aFONT['NAME'];
    if($aFONT['BOLD']==1)	$cBOLD_COM='B';
    if($aFONT['ITALIC']==1)	$cITAL_COM='I';
    if($aFONT['UNDERLINE']==1)	$cUNDRL_COM='U';
    $nSIZE_COM = intval($aFONT['SIZE']);
}

$sheet->getStyle('A1')->getFont()->setName($cFONT_COM);
$sheet->getStyle('A1')->getFont()->setSize($nSIZE_COM);
if($cBOLD_COM=='B') $sheet->getStyle('A1')->getFont()->setBold(true);
if($cITAL_COM=='I') $sheet->getStyle('A1')->getFont()->setItalic(true);
if($cUNDRL_COM=='U') $sheet->getStyle('A1')->getFont()->setUnderline(true);
$sheet->setCellValue('A1', S_PARA('CO1','Rainbow Co.'));

$cKEY_PRD='XLS_ABSEN_PRD';
$cFONT_PRD = 'Arial';   $nSIZE_PRD = 10;  $cBOLD_PRD=' '; $cITAL_PRD=' '; $cUNDRL_PRD=' ';
$qFONT= OpenTable('TbFont', "KEY_ID='$cKEY_COM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
if(SYS_ROWS($qFONT)>0) {
    $aFONT = SYS_FETCH($qFONT);
    $cFONT_PRD = $aFONT['NAME'];
    if($aFONT['BOLD']==1)	$cBOLD_PRD='B';
    if($aFONT['ITALIC']==1)	$cITAL_PRD='I';
    if($aFONT['UNDERLINE']==1)	$cUNDRL_PRD='U';
    $nSIZE_PRD = intval($aFONT['SIZE']);
}
$sheet->getStyle('A2')->getFont()->setName($cFONT_PRD);
$sheet->getStyle('A2')->getFont()->setSize($nSIZE_PRD);
if($cBOLD_PRD=='B') $sheet->getStyle('A2')->getFont()->setBold(true);
if($cITAL_PRD=='I') $sheet->getStyle('A2')->getFont()->setItalic(true);
if($cUNDRL_PRD=='U') $sheet->getStyle('A2')->getFont()->setUnderline(true);
$sheet->setCellValue('A2', 'Periode : '. $cPERIOD1. ' s/d '. $cPERIOD2);

/* ------------- header ---------------------- */
$cFONT_HDR = 'Arial';   $nSIZE_HDR = 12;  $cBOLD_HDR=' '; $cITAL_HDR=' '; $cUNDRL_HDR=' ';
$qFONT= OpenTable('TbFont', "KEY_ID='XLS_ABSENT_HDR' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
if(SYS_ROWS($qFONT)>0) {
    $aFONT = SYS_FETCH($qFONT);
    $cFONT_HDR = $aFONT['NAME'];
    if($aFONT['BOLD']==1)	$cBOLD_HDR='B';
    if($aFONT['ITALIC']==1)	$cITAL_HDR='I';
    if($aFONT['UNDERLINE']==1)	$cUNDRL_HDR='U';
    $nSIZE_HDR = intval($aFONT['SIZE']);
}
$sheet->setCellValue('L1', S_MSG('PI21','Laporan Kehadiran Karyawan'));
$sheet->getStyle('L1')->getFont()->setName($cFONT_HDR);
$sheet->getStyle('L1')->getFont()->setSize($nSIZE_HDR);
if($cBOLD_HDR=='B') $sheet->getStyle('L1')->getFont()->setBold(true);
if($cITAL_HDR=='I') $sheet->getStyle('L1')->getFont()->setItalic(true);
if($cUNDRL_HDR=='U') $sheet->getStyle('L1')->getFont()->setUnderline(true);
/* ------------- end of header ---------------------- */

$dDATE1 = new DateTime($cPERIOD1);
$dDATE2 = new DateTime($cPERIOD2);
$dDATE2->modify('+1 day');
$interval = DateInterval::createFromDateString('1 day');
$TANGGAL = new DatePeriod($dDATE1, $interval, $dDATE2);
$nURUT = 0;

// $qPRESENCE = OpenTable('TmpPresence', "APP_CODE='$cUSERCODE' and A.PRSON_CODE in (".$cFILTER_PERSON.")", 'A.PRSON_CODE, ABSN_DATE, ABSN_MSK, ABSN_KLR', 'PRSON_CODE, ABSN_DATE');
$qPRESENCE = OpenTable('RepAbsen', "APP_CODE='$cUSERCODE'", 'PRSON_CODE', 'PRSON_CODE, ABSN_DATE');
$R = 4;
$aHDR = [1=>'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE'];
while($aREC_PRS=SYS_FETCH($qPRESENCE)) {
    $cPPL = '';
    $qPEOPLE = OpenTable('People', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$aREC_PRS[PRSON_CODE]'");
    if($aPEOPLE = SYS_FETCH($qPEOPLE)) {
        $cPPL = $aPEOPLE['PEOPLE_NAME'];
        $nURUT ++;
    }
//    $sheet->getStyle('A5:AE6')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
//    $sheet->getStyle('A6:AE6')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A6')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $I = 0;
    foreach ($TANGGAL as $dTGL) {
        $I ++;
        $sheet->getStyle($aHDR[$I].(string)$R+1)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle($aHDR[$I].(string)$R+2)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->setCellValueByColumnAndRow($I, $R+1, $dTGL->format('d/m'));
        $sheet->getStyle($aHDR[$I].(string)$R+1)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle($aHDR[$I].(string)$R+3)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getColumnDimension($aHDR[$I])->setWidth(45, 'px');
        $sheet->getStyle($aHDR[$I])->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($aHDR[$I].(string)$R+1)->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($aHDR[$I].(string)$R+3)->getAlignment()->setWrapText(true);

    }
    $sheet->setCellValueByColumnAndRow(1, $R+2, 'No : '.(string)$nURUT);
    $sheet->setCellValueByColumnAndRow(3, $R+2, $cPPL);
    $cBACK_CLR = 'b5a9fd';
    $qFONT= OpenTable('TbFont', "KEY_ID='XLS_ABSN_NAME' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
    if(SYS_ROWS($qFONT)>0) {
        $aBACK_CLR = SYS_FETCH($qFONT);
        $cBACK_CLR = substr($aBACK_CLR['HEX_BACK_CLR'],1,6);
    }
    $iTGL=1;
    foreach ($TANGGAL as $dTGL) {
        $cTGL = $dTGL->format('Y-m-d');
        $nTGL = intval(substr($cTGL,8,2));
        $qDETAIL = OpenTable('RepAbsen', "APP_CODE='$cUSERCODE' and PRSON_CODE='$aREC_PRS[PRSON_CODE]' and ABSN_DATE='".$dTGL->format('Y-m-d')."'");
        if(SYS_ROWS($qDETAIL)>0) {
            $aREC_DTL=SYS_FETCH($qDETAIL);
            $nCOL = date('d', $dTGL->format('d'));
            $sheet->setCellValueByColumnAndRow($iTGL, $R+3, $aREC_DTL['ABSN_MSK'].' '."\r\n".$aREC_DTL['ABSN_KLR']);
        }
        $sheet->getStyle($aHDR[$iTGL].(string)$R+3)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);

        $cNAME_CELL = $aHDR[$iTGL].(string)$R+2;
        $sheet->getStyle($cNAME_CELL)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB($cBACK_CLR);

        $dTGL->modify('+1 day');
        $iTGL++;
    }
    $sheet->getStyle($aHDR[$iTGL-1].(string)$R+2)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getRowDimension((string)$R+2)->setRowHeight(25);
    $sheet->getRowDimension((string)$R+3)->setRowHeight(30);
    $R = $R + 3;
}

ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="absen.xlsx"');
header('Cache-Control: max-age=0');

// (E) OUTPUT
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
exit($writer->save('php://output'));
echo "<script> alert('OK');	window.history.back();	</script>";

