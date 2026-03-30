<?php
// prs_excel.php
// TODO : format text column, wrap text header

require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE = $_SESSION['data_FILTER_CODE']; $cUSERCODE = $_SESSION['gUSERCODE'];
$cFILTER_CUST='';
if (isset($_GET['_c'])) {
    if($_GET['_c']>'')  $cFILTER_CUST=$_GET['_c'];
}
$aHEADER = [S_MSG('F003','Kode'), S_MSG('PA03','Nama Pegawai'), S_MSG('PA04','Gender'), S_MSG('PA43','Jabatan'), S_MSG('TA54','Umur'),
S_MSG('PI22','Masuk'), S_MSG('PB67','Tanggal TMK'), S_MSG('PB91','Masa Kerja'), S_MSG('PA05','Tempat Lahir'), S_MSG('PA06','Tanggal Lahir'),
S_MSG('F005','Alamat'), S_MSG('PA94','Pendidikan'), S_MSG('F105','Email Address'), S_MSG('PG68','No. Sertifikat'), S_MSG('PA33','Nomor HP'),
S_MSG('F006','Nomor Telp.'), S_MSG('PA40','KTP'), S_MSG('PG83','No. NPWP'), S_MSG('PA07','No. Rekening'), S_MSG('PA08','Nama Bank'),
S_MSG('PG8F','No. KTA'), S_MSG('PG89','Berlaku s/d'), S_MSG('PG84','No. BPJS TK'), S_MSG('PG85','No. BPJS KES'), S_MSG('PA0A','Tinggi badan'), S_MSG('PA0B','Berat badan'), S_MSG('PA0C','Ukuran Baju'), S_MSG('PA0D','Ukuran Sepatu'), S_MSG('PA0F','Ukuran Celana'), S_MSG('PA0X','Lingkar Kepala')];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$is_OUTSOURCING=IS_OUTSOURCING($cAPP_CODE);
if ($is_OUTSOURCING) {
    $cCUSTOMER 	= S_MSG('RS04','Customer');
    $cLOKASI	= S_MSG('PF16','Lokasi');
    array_splice($aHEADER, 4, 0, [$cCUSTOMER, $cLOKASI]);
}

$cScope = "APP_CODE='$cAPP_CODE' and DELETOR=''";
$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
if (SYS_ROWS($qSCOPE)>0) $cScope .= " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR='')";
if ($cFILTER_CUST>'')   $cScope .= " and CUST_CODE='".$cFILTER_CUST."'";
$qCUST=OpenTable('TbCustomer', $cScope);
$nSHEET = 0;
$cKEY_COM='XLS_PERSON_LIST_CO1';
$cKEY_HDR='XLS_PERSON_LIST_HDR';
$cKEY_CLM='XLS_PERSON_LIST_CLMN';
$cKEY_DTL='XLS_PERSON_LIST_DTL';

$cFONT_COM = 'Arial';   $nSIZE_COM = 12;  $cBOLD_COM=$cITAL_COM=$cUNDRL_COM=' ';
$qFONT= OpenTable('TbFont', "KEY_ID='$cKEY_COM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
if(SYS_ROWS($qFONT)>0) {
    $aFONT = SYS_FETCH($qFONT);
    $cFONT_COM = $aFONT['NAME'];
    if($aFONT['BOLD']==1)	$cBOLD_COM='B';
    if($aFONT['ITALIC']==1)	$cITAL_COM='I';
    if($aFONT['UNDERLINE']==1)	$cUNDRL_COM='U';
    $nSIZE_COM = intval($aFONT['SIZE']);
}

$cFONT_ADD = 'Arial';   $nSIZE_ADD = 12;  $cBOLD_ADD=$cITAL_ADD=$cUNDRL_ADD=' ';
$qFONT= OpenTable('TbFont', "KEY_ID='$cKEY_COM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
if(SYS_ROWS($qFONT)>0) {
    $aFONT = SYS_FETCH($qFONT);
    $cFONT_NAME = $aFONT['NAME'];
    if($aFONT['BOLD']==1)	$cBOLD_ADD='B';
    if($aFONT['ITALIC']==1)	$cITAL_ADD='I';
    if($aFONT['UNDERLINE']==1)	$cUNDRL_ADD='U';
    $nSIZE_ADD = intval($aFONT['SIZE']);
}

$cFONT_HDR = 'Arial';   $nSIZE_HDR = 12;  $cBOLD_HDR=$cITAL_HDR=$cUNDRL_HDR=' ';
$qFONT= OpenTable('TbFont', "KEY_ID='$cKEY_HDR' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
if(SYS_ROWS($qFONT)>0) {
    $aFONT = SYS_FETCH($qFONT);
    $cFONT_HDR = $aFONT['NAME'];
    if($aFONT['BOLD']==1)	$cBOLD_HDR='B';
    if($aFONT['ITALIC']==1)	$cITAL_HDR='I';
    if($aFONT['UNDERLINE']==1)	$cUNDRL_HDR='U';
    $nSIZE_HDR = intval($aFONT['SIZE']);
}

while($aREC_CUST=SYS_FETCH($qCUST)) {

    $sheet->setCellValue('A1', S_PARA('CO1','Rainbow Co.'));
    $sheet->setCellValue('A2', S_PARA('CO2','Rainbow Co.'));
    $sheet->setCellValue('I1', S_MSG('PL11','Laporan Daftar Karyawan'));
    
    $sheet->getStyle('A1')->getFont()->setName($cFONT_COM);
    $sheet->getStyle('A1')->getFont()->setSize($nSIZE_COM);
    if($cBOLD_COM=='B') $sheet->getStyle('A1')->getFont()->setBold(true);
    if($cITAL_COM=='I') $sheet->getStyle('A1')->getFont()->setItalic(true);
    if($cUNDRL_COM=='U') $sheet->getStyle('A1')->getFont()->setUnderline(true);

    $sheet->getStyle('A2')->getFont()->setName($cFONT_ADD);
    $sheet->getStyle('A2')->getFont()->setSize($nSIZE_ADD);
    if($cBOLD_ADD=='B') $sheet->getStyle('A2')->getFont()->setBold(true);
    if($cITAL_ADD=='I') $sheet->getStyle('A2')->getFont()->setItalic(true);
    if($cUNDRL_ADD=='U') $sheet->getStyle('A2')->getFont()->setUnderline(true);

    $sheet->getStyle('I1')->getFont()->setName($cFONT_HDR);
    $sheet->getStyle('I1')->getFont()->setSize($nSIZE_HDR);
    if($cBOLD_HDR=='B') $sheet->getStyle('I1')->getFont()->setBold(true);
    if($cITAL_HDR=='I') $sheet->getStyle('I1')->getFont()->setItalic(true);
    if($cUNDRL_HDR=='U') $sheet->getStyle('I1')->getFont()->setUnderline(true);

    for ($I=0; $I<sizeof($aHEADER); $I++) {
        CELL_BY_COL_ROW($sheet, $I+1, 5, $aHEADER[$I]);
    }
    
    $sheet->getColumnDimension('A')->setWidth(85, 'px');
    $sheet->getColumnDimension('B')->setWidth(275, 'px');
    $sheet->getColumnDimension('D')->setWidth(140, 'px');
    $sheet->getColumnDimension('E')->setWidth(190, 'px');
    $sheet->getColumnDimension('F')->setWidth(222, 'px');
    $sheet->getColumnDimension('H')->setWidth(80, 'px');
    $sheet->getColumnDimension('I')->setWidth(80, 'px');
    $sheet->getColumnDimension('K')->setWidth(120, 'px');
    $sheet->getColumnDimension('L')->setWidth(84, 'px');
    $sheet->getColumnDimension('N')->setWidth(225, 'px');
    $sheet->getColumnDimension('O')->setWidth(200, 'px');
    $sheet->getColumnDimension('P')->setWidth(112, 'px');
    $sheet->getColumnDimension('T')->setWidth(120, 'px');
    $sheet->getColumnDimension('Y')->setWidth(90, 'px');
    $sheet->getColumnDimension('Z')->setWidth(100, 'px');
    $sheet->getStyle('H')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
    $sheet->getStyle('I')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
    $sheet->getStyle('L')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
    $sheet->getStyle('X')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
    
    $sheet->getStyle('A6')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $aMRG = [1=>'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF'];
    for ($I=1; $I<=sizeof($aMRG); $I++) {
        $sheet->getStyle($aMRG[$I].'5')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle($aMRG[$I].'6')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->mergeCells($aMRG[$I].'5:'.$aMRG[$I].'6');
        $sheet->getStyle($aMRG[$I].'5')->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($aMRG[$I].'5')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
    }
    $sheet->getStyle('A5:'.$aMRG[sizeof($aMRG)].'6')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A6:'.$aMRG[sizeof($aMRG)].'6')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('S')->getNumberFormat()->setFormatCode('@');
    $sheet->getStyle('T')->getNumberFormat()->setFormatCode('@');
    $sheet->getStyle('U')->getNumberFormat()->setFormatCode('@');

    $cCUST_NAME = str_replace('/', '', $aREC_CUST['CUST_NAME']);
    $sheet->setTitle(substr($cCUST_NAME,0,30));

    $qOCCU=OpenTable('PrsOccuption', "CUST_CODE='$aREC_CUST[CUST_CODE]' and APP_CODE='$cAPP_CODE' and DELETOR=''");

    $nROW = 6;
    while($aREC_OCCU=SYS_FETCH($qOCCU)) {
        $cPERSON = $aREC_OCCU['PRSON_CODE'];
        $qPLOC=OpenTable('PrsLocation', "LOKS_CODE='$aREC_OCCU[KODE_LOKS]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
        $aPLOC=SYS_FETCH($qPLOC);
        $cJOB= '';
        $qPJOB=OpenTable('TbOccupation', "JOB_CODE='$aREC_OCCU[JOB_CODE]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
        if($aPJOB=SYS_FETCH($qPJOB))    $cJOB=$aPJOB['JOB_NAME'];
        $cACC_BANK='';  $nPRS_SALARY=3;
        $qPMAIN=OpenTable('PersonMain', "PRSON_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and DELETOR=''");
        $aPMAIN = SYS_FETCH($qPMAIN);
        if(SYS_ROWS($qPMAIN)>0)  {
            $cACC_BANK=$aPMAIN['PRSON_BANK'];
            $nPRS_SALARY=$aPMAIN['PRSON_SLRY'];
        }
        $qPEOPLE=OpenTable('People', "PEOPLE_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and DELETOR=''");
        $aPEOPLE= SYS_FETCH($qPEOPLE);
        $cADDR = '';
        $qPPL_AD=OpenTable('PeopleAddress', "PEOPLE_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
        if($aPPL_AD= SYS_FETCH($qPPL_AD))  $cADDR=$aPPL_AD['PEOPLE_ADDRESS'];

        $cPEND='';
        $qPEND=OpenTable('PrsEdu_TbEdu', "A.PRSON_CODE='$cPERSON' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
        if($aPEND= SYS_FETCH($qPEND))  $cPEND=$aPEND['EDU_NAME'];

        $cSKILL='';
        $qSKILL=OpenTable('PrsSkill', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and A.PRSON_CODE='$cPERSON'");
        if($aSKILL=SYS_FETCH($qSKILL))  $cSKILL=$aSKILL['SKILL_SERT'];

        $cEMAIL='';
        $qEMAIL=OpenTable('PeopleEMail', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and PEOPLE_CODE='$cPERSON'");
        if($aEMAIL=SYS_FETCH($qEMAIL))  $cEMAIL=$aEMAIL['PPL_EMAIL'];
        $qCARD=OpenTable('PrsMemberCard', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and PERSON_CODE='$cPERSON'");
        $cCARDN=$cCARDV=''; 
        if($aCARD=SYS_FETCH($qCARD)) {
            $cCARDN= $aCARD['CARD_NUMBER']; 
            $cCARDV= $aCARD['VALID_UNTIL']; 
        }

        $cNPWP=$cBPJS_TK=$cBPJS_KES='';
        $qNUMB=OpenTable('PrsNumber', "PRSON_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
        if($aNUMB= SYS_FETCH($qNUMB)) {
            $cNPWP=$aNUMB['NO_NPWP'];
            $cBPJS_TK=$aNUMB['NO_BPJS_TK'];
            $cBPJS_KES=$aNUMB['NO_BPJS_KES'];
        }

        $cHMPHN='';
        $qHMPHN=OpenTable('PeopleHomePhone', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and PEOPLE_CODE='$cPERSON'");
        if($aHMPHN=SYS_FETCH($qHMPHN))  $cHMPHN=$aHMPHN['HOME_PHONE'];

        $cHEIGHT=$cWEIGHT=$cSHIRT=$cSHOE=$cPDL='';  
        $nDIA_HEAD=0;
        $qSIZE=OpenTable('PrsSize', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and PRSON_CODE='$cPERSON'");
        if($aSIZE=SYS_FETCH($qSIZE))  {
            $cHEIGHT=$aSIZE['PRS_HEIGHT'];
            $cWEIGHT=$aSIZE['PRS_WEIGHT'];
            $cSHIRT=$aSIZE['PRS_SHIRT'];
            $cSHOE=$aSIZE['PRS_SHOE'];
            $cPDL=$aSIZE['PRS_PDL'];
            $nDIA_HEAD=$aSIZE['PRS_HEAD'];
        }

        $qRESIGN=OpenTable('PrsResign', "APP_CODE='$cAPP_CODE' and DELETOR='' and PRSON_CODE='$cPERSON'");
        $nRESIGN = SYS_ROWS($qRESIGN);

        if($nPRS_SALARY<2 && $nRESIGN==0) {
            $nROW++;  $nCOL=0;
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, $cPERSON);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, DECODE($aPEOPLE['PEOPLE_NAME']));
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, ($aPMAIN['PRSON_GEND']==1 ? 'Pria' : 'Wanita'));
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $cJOB);
            if ($is_OUTSOURCING) {
                $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $aREC_CUST['CUST_NAME']);
                $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $aPLOC['LOKS_NAME']);
            }
            $Birth = new DateTime($aPMAIN['BIRTH_DATE']);
            $Now = new DateTime();
            $Interval = $Now->diff($Birth);
            $Age = $Interval->y;
            $dSTART = new DateTime($aPMAIN['JOB_DATE']);
            if(!$dSTART)    $dSTART = new DateTime($aPMAIN['JOIN_DATE']);
            $MASA = $Now->diff($dSTART);
            $WORK_AGE = $MASA->y;
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, $Age);
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $aPMAIN['JOIN_DATE']);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, $dSTART);
//            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $aPMAIN['JOB_DATE']);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, $WORK_AGE);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, $aPMAIN['BIRTH_PLC']);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, $aPMAIN['BIRTH_DATE']);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, $cADDR);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, $cPEND);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, $cEMAIL);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, $cSKILL);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, $aPMAIN['PRS_PHN']);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, $cHMPHN);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, "'".$aPMAIN['PRS_KTP']);
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, ($cNPWP ? "'".$cNPWP : ''));
            CELL_BY_COL_ROW($sheet, $nCOL=$nCOL+1, $nROW, ($cACC_BANK ? "'".$cACC_BANK : ''));
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $aPMAIN['PRSON_BANK']);
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $cCARDN);
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $cCARDV);
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, "'".$cBPJS_TK);
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, "'".$cBPJS_KES);
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $cHEIGHT);
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $cWEIGHT);
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $cSHIRT);
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $cSHOE);
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $cPDL);
            $sheet->setCellValueByColumnAndRow($nCOL=$nCOL+1, $nROW, $nDIA_HEAD);
        }
    }
    $nSHEET++;
    $worksheet2 = $spreadsheet->createSheet();
    $sheet = $spreadsheet->getSheet($nSHEET);
    $sheet->setTitle(substr($cCUST_NAME,0,30));
}

// (D) ADD NEW WORKSHEET + YOU CAN ALSO USE FORMULAS!
// $spreadsheet->createSheet();
// $sheet = $spreadsheet->getSheet(1);
// $sheet->setTitle("Formula");
// $sheet->setCellValue("A1", "5");
// $sheet->setCellValue("A2", "6");
// $sheet->setCellValue("A3", "=SUM(A1:A2)");
 

ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="person.xlsx"');
header('Cache-Control: max-age=0');

// (E) OUTPUT
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
exit($writer->save('php://output'));
echo "<script> alert('OK');	window.history.back();	</script>";

