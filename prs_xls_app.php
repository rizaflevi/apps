<?php
// (A) LOAD PHPSPREADSHEET
// TODO : continue

require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE = $_SESSION['data_FILTER_CODE'];

$sCO1 = S_PARA('CO1','Rainbow Co.');
$sCO2 = S_PARA('CO2','Rainbow Co.');
// (B) CREATE A NEW SPREADSHEET
$spreadsheet = new Spreadsheet();
 
// (C) GET WORKSHEET
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Test');
$sheet->setCellValue("A1", $sCO1);
$sheet->setCellValue("A2", $sCO2);
$sheet->setCellValue("I1", S_MSG('PL11','Laporan Daftar Karyawan'));
 
// (D) ADD NEW WORKSHEET + YOU CAN ALSO USE FORMULAS!
$spreadsheet->createSheet();
$sheet = $spreadsheet->getSheet(1);
$sheet->setTitle("Formula");
$sheet->setCellValue("A1", "5");
$sheet->setCellValue("A2", "6");
$sheet->setCellValue("A3", "=SUM(A1:A2)");
 
// (E) OUTPUT
$writer = new Xlsx($spreadsheet);

// (E1) SAVE TO A FILE ON THE SERVER
$writer->save("Apps.xlsx");
echo "<script> alert('OK');	window.history.back();	</script>";

