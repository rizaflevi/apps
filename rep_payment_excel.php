<?php
// prs_absen_excel.php

require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE = $_SESSION['data_FILTER_CODE']; 
$cUSERCODE = $_SESSION['gUSERCODE'];

$cHEADER 		= S_MSG('NP03','Daftar Pembayaran');
$cKD_BYR 	= S_MSG('NP11','No. Pembayaran');
$cTANGGAL 	= S_MSG('NP12','Tanggal');
$cKETERANGAN = S_MSG('NJ53','Keterangan');
$cNIL_TRN	= S_MSG('NP36','Nilai');
$cBANK_NAME	= S_MSG('F131','Nama Bank');

$dPERIOD1=date("Y-m-01");
$dPERIOD2=date("Y-m-d");
if (isset($_GET['_d1'])) $dPERIOD1=$_GET['_d1'];
if (isset($_GET['_d2'])) $dPERIOD2=$_GET['_d2'];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->getStyle('A')->getNumberFormat()->setFormatCode('@');
$sheet->getStyle('H')->getNumberFormat()->setFormatCode('@');
$sheet->getColumnDimension('A')->setWidth(110, 'px');
$sheet->getColumnDimension('B')->setWidth(80, 'px');
$sheet->getColumnDimension('C')->setWidth(300, 'px');
$sheet->getColumnDimension('D')->setWidth(80, 'px');
$sheet->getColumnDimension('E')->setWidth(300, 'px');
$cFONT_COM = 'Arial';   $nSIZE_COM = 12;  $cBOLD_COM=' '; $cITAL_COM=' '; $cUNDRL_COM=' ';
$qFONT= OpenTable('TbFont', "KEY_ID='PAYMENT_COM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
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

$sheet->setCellValue('A1', S_PARA('CO1','Rainbow Co.'));
$sheet->setCellValue('A2', S_PARA('CO2','Rainbow Co.'));
$sheet->setCellValue('C3', $cHEADER);
$sheet->setCellValue('A5', $cKD_BYR);
$sheet->setCellValue('B5', $cTANGGAL);
$sheet->setCellValue('C5', $cKETERANGAN);
$sheet->setCellValue('D5', $cNIL_TRN);
$sheet->setCellValue('E5', $cBANK_NAME);

$sheet->getStyle('D')->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');

$sheet->getStyle('A5:E5')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A5:E5')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$nROW=$mROW=6;
$qQUERY = OpenTable('TrPaymentHdr', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and A.BDV_DATE>='$dPERIOD1' and A.BDV_DATE<='$dPERIOD2'");
while($aREC_PAY=SYS_FETCH($qQUERY)) {
    $nROW++;
    CELL_BY_COL_ROW($sheet, 1, $nROW, $aREC_PAY['BDV_NO']);
    $dPAY_DATE = strtotime( $aREC_PAY['BDV_DATE'] );
    $cPAY_DATE = date( 'd/m/Y', $dPAY_DATE );
    CELL_BY_COL_ROW($sheet, 2, $nROW, $cPAY_DATE);
    CELL_BY_COL_ROW($sheet, 3, $nROW, DECODE($aREC_PAY['BDV_DESC']));
    $dQUERY = OpenTable('TrPaymentDtl', "A.BDV_NO='$aREC_PAY[BDV_NO]' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
    $nAMOUNT =0;
    while($aREC_PAYMENT=SYS_FETCH($dQUERY)) {
        $nAMOUNT 	+= $aREC_PAYMENT['BDV_DAM'];
    }
    
    CELL_BY_COL_ROW($sheet, 4, $nROW, $nAMOUNT);
    CELL_BY_COL_ROW($sheet, 5, $nROW, DECODE($aREC_PAY['B_NAME']));
}
CELL_BY_COL_ROW($sheet, 3, $nROW+1, 'Jumlah');
CELL_BY_COL_ROW($sheet, 4, $nROW+1, '=sum(D'.(string)$mROW.':D'.(string)$nROW.')');

ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Payment.xlsx"');
header('Cache-Control: max-age=0');

// (E) OUTPUT
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
exit($writer->save('php://output'));
echo "<script> alert('OK');	window.history.back();	</script>";

