<?php
// prs_absen_excel.php

// Enable error reporting
// if (!isset($_SESSION['cHOST_DB2'])) http_response_code(404);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once "sysfunction.php";
// session_start();
$cAPP_CODE = isset($_SESSION['data_FILTER_CODE']) ? $_SESSION['data_FILTER_CODE'] : '';
$cUSERCODE = isset($_SESSION['gUSERCODE']) ? $_SESSION['gUSERCODE'] : '';





$aNAMA_HARI		    = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', '');
$nama_bulan		    = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
$bulan_romawi       = array('I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII');

$cPERIOD1=$cPERIOD2=date("Y-m-d");
if (isset($_GET['_d1'])) $cPERIOD1=$_GET['_d1'];
if (isset($_GET['_d2'])) $cPERIOD2=$_GET['_d2'];


// $cPERIOD1=DMY_YMD($cPERIOD1);
// $cPERIOD2=DMY_YMD($cPERIOD2);

$dDATE1 = new DateTime($cPERIOD1);
$dDATE2 = new DateTime($cPERIOD2);
$interval = DateInterval::createFromDateString('1 day');
$TANGGAL = new DatePeriod($dDATE1, $interval, $dDATE2);

$month = (int)$dDATE2->format('m');
$year = (int)$dDATE2->format('Y');
$periode_tagihan = ' '.$nama_bulan[$month - 1] . ' ' . $year;

$current_date = new DateTime(); //now / current server time
$current_day = (int)$current_date->format('d');
$current_month = (int)$current_date->format('m');
$current_year = (int)$current_date->format('Y');

$current_romawi_month = $bulan_romawi[$current_month - 1];
$current_month_indo = $nama_bulan[$current_month - 1];

$cCUST_CODE='';
if (isset($_GET['_c'])) $cCUST_CODE=$_GET['_c'];
// print_r2($cPERIOD1);
// if ($cCUST_CODE=='') return;
$cCUST_GROUP='';
$qCUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR='' and CUST_CODE='$cCUST_CODE' and DELETOR=''");
if($aCUST = SYS_FETCH($qCUSTOMER))     $cCUST_GROUP=$aCUST['CUST_GROUP'];

$spreadsheet = new Spreadsheet();
// Get the default style untuk semua sheet
$defaultStyle = $spreadsheet->getDefaultStyle();

// Set the default font untuk semua sheet
$defaultStyle->getFont()->setName('Arial Narrow');

// Optionally, you can also set the font size untuk semua sheet
$defaultStyle->getFont()->setSize(10);

$sheet = $spreadsheet->getActiveSheet();

$qMAIN_MENU=OpenTable('Main_Menu', "APP_CODE='$cAPP_CODE' and link='prs_group_resign.php'");
$ada_OUTSOURCING=(SYS_ROWS($qMAIN_MENU)>0 ? 1 : 0);
if($ada_OUTSOURCING>0) {
    $cCUSTOMER 	= S_MSG('RS04','Customer');
    $cLOKASI	= S_MSG('PF16','Lokasi');
}

$nSHEET = 0;
$cKEY_CST='XLS_OVERTIME_CUST';
$cKEY_DTL='XLS_OVERTIME_DTL';
$cREK_DTL='XLS_SUMM_OVTM_DTL';

// -------------------------------------------------------------------------------------------------------------------------------------------
// SHEET RINCIAN LEMBUR START
// -------------------------------------------------------------------------------------------------------------------------------------------


$cFONT_COM = 'Arial Narrow';   $nSIZE_COM = 12;  $cBOLD_COM=' '; $cITAL_COM=' '; $cUNDRL_COM=' ';
$qFONT= OpenTable('TbFont', "KEY_ID='XLS_OVERTIME_CO1' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
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
$sheet->setCellValue('A1', 'PT YAZA PRATAMA');

$cFONT_PRD = 'Arial Narrow';   $nSIZE_PRD = 10;  $cBOLD_PRD=' '; $cITAL_PRD=' '; $cUNDRL_PRD=' ';
$qFONT= OpenTable('TbFont', "KEY_ID='XLS_OVERTIME_PRD' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
if($aFONT = SYS_FETCH($qFONT)) {
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
$sheet->setCellValue('B3', '  ('.date($dDATE1->format('d-m-Y')). ' s/d '. date($dDATE2->format('d-m-Y')).')');
$sheet->setCellValue('A2', S_MSG('RS22', 'Periode :  ').$periode_tagihan);

/* ------------- header ---------------------- */
$cFONT_HDR = 'Arial Narrow';   $nSIZE_HDR = 12;  $cBOLD_HDR=' '; $cITAL_HDR=' '; $cUNDRL_HDR=' ';
$qFONT= OpenTable('TbFont', "KEY_ID='XLS_OVERTIME_HDR' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
if($aFONT = SYS_FETCH($qFONT)) {
    $cFONT_HDR = $aFONT['NAME'];
    if($aFONT['BOLD']==1)	$cBOLD_HDR='B';
    if($aFONT['ITALIC']==1)	$cITAL_HDR='I';
    if($aFONT['UNDERLINE']==1)	$cUNDRL_HDR='U';
    $nSIZE_HDR = intval($aFONT['SIZE']);
}
$sheet->setCellValue('E1', S_MSG('PL31','Laporan Lembur Karyawan')); //font size masih 16
$sheet->setCellValue('E2', $aCUST['CUST_NAME']);
$sheet->getStyle('E2')->getFont()->setSize('14');
$sheet->getStyle('E1')->getFont()->setName($cFONT_HDR);
$sheet->getStyle('E1')->getFont()->setSize('16');
if($cBOLD_HDR=='B') $sheet->getStyle('E1')->getFont()->setBold(true);
if($cITAL_HDR=='I') $sheet->getStyle('E1')->getFont()->setItalic(true);
if($cUNDRL_HDR=='U') $sheet->getStyle('E1')->getFont()->setUnderline(true);
/* ------------- end of header ---------------------- */

$sheet->setCellValue('A5', S_MSG('RP51','No.'));
$sheet->setCellValue('B5', S_MSG('PL32','NAMA DAN JABATAN'));
$sheet->setCellValue('C4', S_MSG('PL33','UMP/UMK'));
$sheet->setCellValue('C5', S_MSG('PA44','Tunjangan'));
$sheet->setCellValue('F4', S_MSG('PL34','Pelaksanaan Kerja Lembur'));
$sheet->setCellValue('D5', S_MSG('TP54','Tanggal'));
$sheet->setCellValue('E5', S_MSG('PA71','Hari'));
$sheet->setCellValue('F5', S_MSG('PL35','AWAL'));
$sheet->setCellValue('G5', S_MSG('PL36','AKHIR'));
$sheet->setCellValue('H5', S_MSG('PL37','JML'));
$sheet->setCellValue('I5', '1.5');
$sheet->setCellValue('J5', '2');
$sheet->setCellValue('K5', S_MSG('PL38','Jml'));
$sheet->setCellValue('L5', S_MSG('PL39','JUMLAH Rp.'));
$sheet->setCellValue('M5', S_MSG('PL40','U. Makan'));
$sheet->setCellValue('N5', S_MSG('PS37','Total'));
$sheet->getStyle('A4:N4')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A5:N5')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);

// Set the default column width to 64 pixels
$sheet->getDefaultColumnDimension()->setWidth(-1); //8.43 approx of 64px

$sheet->getColumnDimension('A')->setWidth(40, 'px');
$sheet->getColumnDimension('B')->setWidth(193, 'px');
$sheet->getColumnDimension('C')->setWidth(100, 'px');
$sheet->getColumnDimension('D')->setWidth(65, 'px');
$sheet->getColumnDimension('E')->setWidth(65, 'px');
$sheet->getColumnDimension('F')->setWidth(65, 'px');
$sheet->getColumnDimension('G')->setWidth(65, 'px');
$sheet->getColumnDimension('H')->setWidth(65, 'px');
$sheet->getColumnDimension('I')->setWidth(65, 'px');
$sheet->getColumnDimension('J')->setWidth(65, 'px');
$sheet->getColumnDimension('K')->setWidth(65, 'px');
$sheet->getColumnDimension('L')->setWidth(100, 'px');
$sheet->getColumnDimension('M')->setWidth(100, 'px');
$sheet->getColumnDimension('N')->setWidth(100, 'px');
$sheet->getRowDimension('1')->setRowHeight(27);

$sheet->getStyle('C')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_RIGHT);
$sheet->getStyle('D')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('E')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('F')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('G')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('H')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('I')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('J')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('K')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('L')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_RIGHT);
$sheet->getStyle('M')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_RIGHT);
$sheet->getStyle('N')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_RIGHT);
$sheet->getStyle('C')->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');
$sheet->getStyle('L')->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');
$sheet->getStyle('M')->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');
$sheet->getStyle('N')->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');

include_once("sys_connect.php");
$dbhost = $server;
$dbuser = $username;
$dbpass = $password;
$dbname = $database2;
$dbdsn = "mysql:dbname={$dbname};host={$dbhost}";

try {
    $db = new PDO($dbdsn, $dbuser, $dbpass);
    // var_dump($db);
} catch (PDOException $e) {
  echo 'Connection failed: '.$e->getMessage();
}

$queryHoliday = $db->prepare(
    "SELECT START_DATE
      FROM tb_holiday 
      WHERE APP_CODE='YAZA' 
      and START_DATE>='$cPERIOD1' 
      and FINISH_DT<='$cPERIOD2' 
      and DELETOR=''
      ORDER BY START_DATE ASC");
$queryHoliday->execute();
$holiday_list = $queryHoliday->fetchAll(PDO::FETCH_COLUMN, 0);

///---------------------------------------------
///QUERY PALING PENTING DARI HALAMAN INI
//query untuk menghitung jumlah person code yang lembur (work) group by
$qOVERTIME=OpenTable('RpOvertime', "A.APP_CODE='$cAPP_CODE' and A.OVT_MINUTE>0 and date(OVT_START)>='$cPERIOD1' and date(OVT_START)<='$cPERIOD2' and F.CUST_CODE='$cCUST_CODE'", 'PRSON_CODE', 'PRSON_CODE');
///QUERY PALING PENTING DARI HALAMAN INI
///---------------------------------------------

$nROW = 7; $nURUT = 0;     $nTUNJ=0;    $nSHEET=0;  $aPERSON_NAME=array();
$tNILAI = 0;

$nSERVER_TIME=7;		// server time (utc +7 jakarta / wita)

while($aREC_OVT=SYS_FETCH($qOVERTIME)) {//looping untuk setiap person code yang lembur
    $cPPL = '';
    $cPRSN=$aREC_OVT['PRSON_CODE'];
    $qPEOPLE = OpenTable('People', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPRSN'");
    if($aPEOPLE = SYS_FETCH($qPEOPLE)) {
        $cPPL = decode_string($aPEOPLE['PEOPLE_NAME']);
        $nURUT ++;
    }//jalan
    $nPROW=$nROW;
    $aPERSON_NAME[$nURUT]['Name']='=Rincian!B'.(string)$nPROW;
    $aPERSON_NAME[$nURUT]['Jobs']='=Rincian!B'.(string)$nPROW+1;//jalan
    
    
    $sheet->getStyle('A'.$nROW.':N'.$nROW)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    CELL_BY_COL_ROW($sheet, 1, $nROW, (string)$nURUT); //nomor urut
    CELL_BY_COL_ROW($sheet, 2, $nROW, $cPPL); //nama karyawan kolom 2  //jalan


    $sheet->getCellByColumnAndRow(2,$nROW)->getStyle()->getFont()->setBold(true);
    $sheet->getCellByColumnAndRow(2,$nROW)->getStyle()->getFont()->setSize(10);

    //fine grained query
    //prioritas: cust > loc > job > appcode
    $qOCCU = OpenTable('PrsOccuption', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPRSN'"); 
    if($aOCCU = SYS_FETCH($qOCCU)) { //jika ada data pekerjaan

        //ambil data dari table prs_locs
        $qLOCS = OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and LOKS_CODE='$aOCCU[KODE_LOKS]' and DELETOR=''");

        if($aLOCS = SYS_FETCH($qLOCS))  $aPERSON_NAME[$nURUT]['Locs']=$aLOCS['LOKS_NAME'];

        //inisialisasi default variabel
        $nUMK=0;    $cPROV='';  $cKAB='';   $nU_MKN=0;

        //cust > loc > job > appcode
        //cust > loc  >appcode
        


        //ambil data dari table prs_group_slry yg ada job code
        $qGROUP_SLY = OpenTable('prs_group_slry', "APP_CODE='$cAPP_CODE' and SLRY_CUST='$aOCCU[CUST_CODE]' and SLRY_LOC='$aOCCU[KODE_LOKS]' and SLRY_JOB='$aOCCU[JOB_CODE]' and REC_ID not in ( select DEL_ID from logs_delete )");
        
        //jika query di atas ada hasil
        if($aGRP_SLR = SYS_FETCH($qGROUP_SLY)) {
            // trigger_error('foo');
            $cPROV=$aGRP_SLR['SLRY_PROV']; //kode provinsi 2 digit
            $cKAB=$aGRP_SLR['SLRY_DIST']; //kode kabupaten/kota 4 digit
            $nU_MKN=$aGRP_SLR['SLRY_MEALS']; //uang makan yang dihitung apabila melebihi batas jam tertentu
        } else { //jika query tidak menemukan row, coba query yg lebih umum tanpa job code
        $qGROUP_SLY = OpenTable('prs_group_slry', "APP_CODE='$cAPP_CODE' and SLRY_CUST='$aOCCU[CUST_CODE]' and SLRY_LOC='$aOCCU[KODE_LOKS]' and REC_ID not in ( select DEL_ID from logs_delete )");
            
            if($aGRP_SLR = SYS_FETCH($qGROUP_SLY)) {
                $cPROV=$aGRP_SLR['SLRY_PROV'];
                $cKAB=$aGRP_SLR['SLRY_DIST'];
                $nU_MKN=$aGRP_SLR['SLRY_MEALS'];
            } else { //jika masih belum menemukan row, coba query tanpa loc code dan tanpa job code

        $qGROUP_SLY = OpenTable('prs_group_slry', 
                "APP_CODE='$cAPP_CODE' 
                and SLRY_CUST='$aOCCU[CUST_CODE]' 
                and SLRY_JOB='' and SLRY_LOC='' 
                and REC_ID not in ( select DEL_ID from logs_delete )");

                if($aGRP_SLR = SYS_FETCH($qGROUP_SLY)) {
                    $cPROV=$aGRP_SLR['SLRY_PROV'];  
                    $cKAB=$aGRP_SLR['SLRY_DIST'];  
                    $nU_MKN=$aGRP_SLR['SLRY_MEALS'];
                } else {
        $qGROUP_SLY = OpenTable('prs_group_slry', 
                    "APP_CODE='$cAPP_CODE' 
                    and SLRY_LOC='$aOCCU[KODE_LOKS]' 
                    and SLRY_JOB='' 
                    and SLRY_CUST='' 
                    and REC_ID not in ( select DEL_ID from logs_delete )");
                    if($aGRP_SLR = SYS_FETCH($qGROUP_SLY)) {
                        $cPROV=$aGRP_SLR['SLRY_PROV'];  
                        $cKAB=$aGRP_SLR['SLRY_DIST'];  
                        $nU_MKN=$aGRP_SLR['SLRY_MEALS'];
                    }else{
                        //pilihan terakhir, default
                        $cPROV=substr($aREC_OVT['AREA_CODE'], 0, -2); //4 digit kurang  2 digit
                        $cKAB=$aREC_OVT['AREA_CODE'];  
                        $nU_MKN=15000;//jika lembur >=3 jam maka dapat 15000
                    }
                }
            }
        }

        //jika ada string kode provinsi dan kabupaten (tidak kosong)
        if($cPROV.$cKAB>'') { //WARNING: JIKA PROVINSI DAN KABUPATEN TETAP KOSONG MAKA UMP/UMK TIDAK AKAN TERISI (ERROR)

            
            if($cKAB=='') { //jika kabupaten/kota kosong, maka ambil ump dari tabel ump provinsi
                //ambil ump dari provinsi
                $qUMK=OpenTable('TbLocProvUmp', "APP_CODE='$cAPP_CODE' and id_prov='$cPROV' and DELETOR=''");
                if($aUMK = SYS_FETCH($qUMK)) {
                    $nUMK = $aUMK['UMP'];
                }
            } else { //jika ada data kode kabupaten/kota, maka utamakan nilai ini

                $qUMK=OpenTable('TbLocDistUmk', "APP_CODE='$cAPP_CODE' and DIST_CODE='$cKAB' and DELETOR=''");
                if($aUMK = SYS_FETCH($qUMK)) {
                    $nUMK = $aUMK['DIST_UMK'];
                }
            }
            CELL_BY_COL_ROW($sheet, 3, $nROW, $nUMK); //ump/umk di samping kanan nama
            // $sheet->getCellByColumnAndRow(3,$nROW)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
            // $sheet->getCellByColumnAndRow(3,$nROW+1)->getStyle()->getNumberFormat()->setFormatCode('#,##0');

            // $cJOB = '';
            // $qJOB=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and JOB_CODE='$aOCCU[JOB_CODE]' and DELETOR=''");
            // if($aJOB = SYS_FETCH($qJOB)) {
                // $cJOB = $aJOB['JOB_NAME']; //nama jabatan untuk output di bawah nama (kolom ke 2)
            // }
            $cJOB = $aREC_OVT['JOB_NAME'];; //nama jabatan untuk output di bawah nama (kolom ke 2)
            $nTUNJ = 0;
            $qTUNJ=OpenTable('PrsOccuAllow', "INCL_OVT=1 and APP_CODE='$cAPP_CODE' and CUST_GROUP='$cCUST_GROUP' and JOB_CODE='$aOCCU[JOB_CODE]' and DELETOR=''");
            if($aTUNJ = SYS_FETCH($qTUNJ)) {
                $nTUNJ = $aTUNJ['ALLOWANCE'];
            }
            $nGAJI = $nUMK + $nTUNJ; //gaji pokok umk/ump + tunjangan
            CELL_BY_COL_ROW($sheet, 2, $nROW+1, $cJOB); //jabatan
            CELL_BY_COL_ROW($sheet, 3, $nROW+1, ($nTUNJ > 0 ? $nTUNJ : 0)); //isi cell tunjangan, jika tidak data fetch kosong maka isi nilai kosong


            $I = 0; $jROW_LBR=$nROW;
            
            $SERVER_TIME = 7;		// server time (utc +7 jakarta / wita)
            
            //query utama
            $qDETAIL=OpenTable('RpOvertime', "A.APP_CODE='$cAPP_CODE' and A.OVT_MINUTE>0 and date(OVT_START)>='$cPERIOD1' and date(OVT_START)<='$cPERIOD2' and A.PRSON_CODE='$cPRSN'", '', 'OVT_START');
            while($aREC_DTL=SYS_FETCH($qDETAIL)) {
                $dTANGGAL=$aREC_DTL['OVT_START']; //FORMAT 0000-00-00 00:00:00
                $lHOLIDAY=0;
                $dTGL=substr($dTANGGAL, 0, 10);  //FORMATTED TO 0000-00-00
                
                $HOUR_OFFSET = $aREC_DTL['UTC_OFFSET'] - $SERVER_TIME;

                $cOVT_IN=substr($aREC_DTL['OVT_START'],11,5); //11,5 = hh:mm
                if ($cOVT_IN!=''){
                    $nABS_PULANG = (int) substr($cOVT_IN,0,2) + $HOUR_OFFSET;
                    $cOVT_IN = str_pad($nABS_PULANG , 2 , "0" , STR_PAD_LEFT).':'.substr($cOVT_IN,3,2);
                }//kolom masuk
                
                $cOVT_OUT=substr($aREC_DTL['OVT_END'],11,5);
                if ($cOVT_OUT!='' && $cOVT_OUT!='00:00'){
                    $nABS_PULANG = (int) substr($cOVT_OUT,0,2) + $HOUR_OFFSET;
                    $cOVT_OUT = str_pad($nABS_PULANG , 2 , "0" , STR_PAD_LEFT).':'.substr($cOVT_OUT,3,2);
                }//kolom pulang
                
                
                //n+1 problem bug, harusnya query jangan didalam loop, nanti ganti jadi if(in_array())
                // $qHOLI=OpenTable('TbHoliday', "APP_CODE='$cAPP_CODE' and START_DATE>='$dTGL' and FINISH_DT<='$dTGL' and DELETOR=''"); //expensive
                // if($aHOLI = SYS_FETCH($qHOLI)) {
                //     $lHOLIDAY = 1;
                // }

                if(in_array($dTGL, $holiday_list)) {
                    $lHOLIDAY = 1;
                }
                
                $dSAMPAI=$aREC_DTL['OVT_END'];
                $nLBR_JAM=intval($aREC_DTL['OVT_MINUTE']/60);
                
                $nHARI = date("w", strtotime($dTANGGAL));
                CELL_BY_COL_ROW($sheet, 4, $nROW, substr($dTANGGAL, 8, 2).'/'.substr($dTANGGAL, 5, 2)); //tanggal
                CELL_BY_COL_ROW($sheet, 5, $nROW, $aNAMA_HARI[$nHARI]); //hari
                CELL_BY_COL_ROW($sheet, 6, $nROW, $cOVT_IN); //jam lembur masuk
                CELL_BY_COL_ROW($sheet, 7, $nROW, $cOVT_OUT); //jam lembur keluar
                CELL_BY_COL_ROW($sheet, 8, $nROW, $nLBR_JAM);
                $nLBR1=0; $nLBR2=0; $nJML_LBR=$nLBR_JAM;
                if($lHOLIDAY==1 || $nHARI==0 || $nHARI==6) { //jika hari libur, atau hari minggu, atau hari sabtu maka rumus kali 2 nyala
                    $nLBR2=$nJML_LBR;
                } else {
                    if($nJML_LBR>0) {
                        $nLBR1=1.5;
                        if($nJML_LBR>1)    $nLBR2=$nJML_LBR-1;
                    }
                }
                $nLBR2 *= 2;
                $nHIT_LBR = $nLBR1 + $nLBR2;
                $nNIL_LBR = intval($nHIT_LBR * $nGAJI / 173);
                $nUMKN=0;
                if($nLBR_JAM>=3) $nUMKN+=$nU_MKN;
                if($nLBR_JAM>=8) $nUMKN+=$nU_MKN;
                CELL_BY_COL_ROW($sheet, 9, $nROW, $nLBR1);//kali 1.5 //kolom ke 9 dst
                CELL_BY_COL_ROW($sheet, 10, $nROW, $nLBR2);//kali 2
                CELL_BY_COL_ROW($sheet, 11, $nROW, '=$I'.(string)$nROW.'+$J'.(string)$nROW);//jml
                CELL_BY_COL_ROW($sheet, 12, $nROW, '=int(($C'.(string)$nPROW.'+$C'.(string)($nPROW+1).')/173*K'.(string)$nROW.')');
                CELL_BY_COL_ROW($sheet, 13, $nROW, $nUMKN);
                CELL_BY_COL_ROW($sheet, 14, $nROW, '=$L'.(string)$nROW.'+$M'.(string)$nROW);
                // $sheet->getCellByColumnAndRow(12,$nROW)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
                // $sheet->getCellByColumnAndRow(13,$nROW)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
                // $sheet->getCellByColumnAndRow(14,$nROW)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
                $nROW++;
                $tNILAI += $nNIL_LBR + $nUMKN;
            }
            $nROW++;

            //print baris jumlah akhir/subtotal per pegawai
            CELL_BY_COL_ROW($sheet, 3, $nROW+1, 'JUMLAH :');
            CELL_BY_COL_ROW($sheet, 8, $nROW+1, '=sum(H'.(string)$jROW_LBR.':H'.(string)$nROW.')');
            CELL_BY_COL_ROW($sheet, 11, $nROW+1, '=sum(K'.(string)$jROW_LBR.':K'.(string)$nROW.')');
            CELL_BY_COL_ROW($sheet, 12, $nROW+1, '=sum(L'.(string)$jROW_LBR.':L'.(string)$nROW.')');
            CELL_BY_COL_ROW($sheet, 13, $nROW+1, '=sum(M'.(string)$jROW_LBR.':M'.(string)$nROW.')');
            CELL_BY_COL_ROW($sheet, 14, $nROW+1, '=sum(N'.(string)$jROW_LBR.':N'.(string)$nROW.')'); //paling ujung kanan
            // $sheet->setBreak($nROW+1, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);//page break untuk printing

            // $sheet->getCellByColumnAndRow(12,$nROW+1)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
            // $sheet->getCellByColumnAndRow(13,$nROW+1)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
            // $sheet->getCellByColumnAndRow(14,$nROW+1)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
            $aPERSON_NAME[$nURUT]['Jml']='=Rincian!N'.(string)$nROW+1; //untuk sheet rekap

            
            for ($I=1; $I < 15; $I++) { // 15 adalah panjang column
                $sheet->getCellByColumnAndRow($I,$nROW+1)->getStyle()->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getCellByColumnAndRow($I,$nROW+1)->getStyle()->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $nROW+=4;
        }
    }//else: jika tidak ada data pekerjaannya tidak diprint
    // $sheet->insertNewRowBefore($nROW+2, 5);
    $nROW = $nROW + 3;
}
// $sheet->getStyle('H:K')->getNumberFormat()->setFormatCode('#,##0.00');
// $sheet->getStyle('H:K')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
$sheet->getStyle('H:K')->getNumberFormat()->setFormatCode('#,##0.00;[Red]-#,##0.00;"-"');
$sheet->getStyle('L:N')->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0;"-"');
$sheet->getStyle('C')->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0;"-"');
$sheet->getStyle('H:K')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_RIGHT);
$sheet->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$sheet->setTitle('Rincian');
$nSHEET++;
// -------------------------------------------------------------------------------------------------------------------------------------------
// SHEET RINCIAN LEMBUR END
// -------------------------------------------------------------------------------------------------------------------------------------------



// -------------------------------------------------------------------------------------------------------------------------------------------
// SHEET REKAP
// -------------------------------------------------------------------------------------------------------------------------------------------

$worksheet2 = $spreadsheet->createSheet();
$sheet = $spreadsheet->getSheet($nSHEET);
$worksheet2->getPageSetup()->setFitToWidth(1);
$worksheet2->getPageSetup()->setFitToHeight(0);
$sheet->setTitle('Rekap');
// $sheet->getDefaultRowDimension()->setRowHeight(25);
/* -------------------  rekap header ---------------------- */
$cFONT_HDR = 'Arial Narrow';   $nSIZE_HDR = 12;  $cBOLD_HDR=' '; $cITAL_HDR=' '; $cUNDRL_HDR=' ';
$qFONT= OpenTable('TbFont', "KEY_ID='XLS_SUMM_OVTM_HEADER' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
if(SYS_ROWS($qFONT)>0) {
    $aFONT = SYS_FETCH($qFONT);
    $cFONT_HDR = $aFONT['NAME'];
    if($aFONT['BOLD']==1)	$cBOLD_HDR='B';
    if($aFONT['ITALIC']==1)	$cITAL_HDR='I';
    if($aFONT['UNDERLINE']==1)	$cUNDRL_HDR='U';
    $nSIZE_HDR = intval($aFONT['SIZE']);
}
$sheet->setCellValue('A1', S_MSG('PL61','REKAP LEMBUR').' '.$aCUST['CUST_NAME']);
$sheet->getStyle('A1')->getFont()->setName($cFONT_HDR);
$sheet->getStyle('A1')->getFont()->setSize($nSIZE_HDR);
if($cBOLD_HDR=='B') $sheet->getStyle('A1')->getFont()->setBold(true);
if($cITAL_HDR=='I') $sheet->getStyle('A1')->getFont()->setItalic(true);
if($cUNDRL_HDR=='U') $sheet->getStyle('A1')->getFont()->setUnderline(true);

$sheet->setCellValue('A2', '=Rincian!A2');
$sheet->mergeCells('A1:E1');
$sheet->mergeCells('A2:E2');
$sheet->getStyle('A1')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A2')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);

$sheet->getStyle('A4:E4')->getFont()->setBold(true);

$sheet->setCellValue('A4', S_MSG('RP51','No.'));
$sheet->setCellValue('B4', S_MSG('PS42','Nama'));
$sheet->setCellValue('C4', S_MSG('PE62','Lokasi'));
$sheet->setCellValue('D4', S_MSG('PA52','Jabatan'));
$sheet->setCellValue('E4', S_MSG('PL62','Jlh Lembur'));
/* ------------- end of header ---------------------- */
// Set the default column width to 64 pixels
$sheet->getDefaultColumnDimension()->setWidth(64, 'px');

$sheet->getColumnDimension('A')->setWidth(40, 'px');
$sheet->getColumnDimension('B')->setWidth(193, 'px');
$sheet->getColumnDimension('C')->setWidth(285, 'px');
$sheet->getColumnDimension('D')->setWidth(194, 'px');
$sheet->getColumnDimension('E')->setWidth(110, 'px');
$sheet->getStyle('A4:E4')->getFont()->setName($cFONT_HDR);
$sheet->getStyle('A4:E4')->getFont()->setSize($nSIZE_HDR);
$sheet->getStyle('A4:E4')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A4:E4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
$sheet->getRowDimension('4')->setRowHeight(25);
// $sheet->getRowDimension('5')->setRowHeight(25);

$cFONT_DTL = 'Arial Narrow';   $nSIZE_DTL = 12;  $cBOLD_DTL=' '; $cITAL_DTL=' '; $cUNDRL_DTL=' ';
$qFONT= OpenTable('TbFont', "KEY_ID='XLS_SUMM_OVTM_DTL' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
if(SYS_ROWS($qFONT)>0) {
    $aFONT = SYS_FETCH($qFONT);
    $cFONT_DTL = $aFONT['NAME'];
    if($aFONT['BOLD']==1)	$cBOLD_DTL='B';
    if($aFONT['ITALIC']==1)	$cITAL_DTL='I';
    if($aFONT['UNDERLINE']==1)	$cUNDRL_DTL='U';
    $nSIZE_DTL = intval($aFONT['SIZE']);
}
$I=1;
while ($I <= count($aPERSON_NAME)) {
    CELL_BY_COL_ROW($sheet, 1, $I+4, $I); //kolom 1 dst
    CELL_BY_COL_ROW($sheet, 2, $I+4, $aPERSON_NAME[$I]['Name']);
    CELL_BY_COL_ROW($sheet, 3, $I+4, $aPERSON_NAME[$I]['Locs']);
    CELL_BY_COL_ROW($sheet, 4, $I+4, $aPERSON_NAME[$I]['Jobs']);
    CELL_BY_COL_ROW($sheet, 5, $I+4, $aPERSON_NAME[$I]['Jml']);
    $sheet->getRowDimension($I+4)->setRowHeight(22);
    $sheet->getCellByColumnAndRow(1,$nROW)->getStyle()->getFont()->setName($cFONT_DTL);
    if($cBOLD_DTL=='B')    $sheet->getCellByColumnAndRow(1,$I+4)->getStyle()->getFont()->setBold(true);
    $sheet->getCellByColumnAndRow(1,$nROW)->getStyle()->getFont()->setSize($nSIZE_DTL);
    $sheet->getCellByColumnAndRow(5,$I+4)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
    $I++;
}
$sheet->getRowDimension($I+4)->setRowHeight(22);
$sheet->getRowDimension($I+5)->setRowHeight(24);

$sheet->getStyle('A' . ($I + 5) . ':E' . ($I + 5))->getFont()->setBold(true);
$sheet->getStyle('A' . ($I + 5) . ':E' . ($I + 5))->getFont()->setSize(12);

CELL_BY_COL_ROW($sheet, 4, $I+5, 'Total');
CELL_BY_COL_ROW($sheet, 5, $I+5, '=sum(E5:E'.(string)($I+4).')');
$sheet->getCellByColumnAndRow(5,$I+5)->getStyle()->getNumberFormat()->setFormatCode('#,##0');

$nTROW=$I+5;
for ($I=1; $I < 6; $I++) {
    $sheet->getCellByColumnAndRow($I,$nTROW)->getStyle()->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getCellByColumnAndRow($I,$nTROW)->getStyle()->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
}

// -------------------------------------------------------------------------------------------------------------------------------------------
// SHEET KUITANSI
// -------------------------------------------------------------------------------------------------------------------------------------------

if(TRUST($cUSERCODE, 'PRS_OVERTIME_KWTN')) {
    $nSHEET++;
    $worksheet3 = $spreadsheet->createSheet();
    $sheet = $spreadsheet->getSheet($nSHEET);
    $worksheet3->getPageSetup()->setFitToWidth(1);
    $worksheet3->getPageSetup()->setFitToHeight(1);
    $sheet->getPageMargins()
    ->setLeft(0.5)
    ->setRight(0.1)
    ->setTop(0.1)
    ->setBottom(0.1)
    ->setHeader(0)
    ->setFooter(0);

    $sheet->setTitle('Kuitansi');

    $sheet->mergeCells('B2:C2');
    $sheet->mergeCells('D2:K2');
    $sheet->mergeCells('E14:I14');
    $sheet->getStyle('E14')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('D2')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
    
    // Set the default column width to 64 pixels
    
    $sheet->getDefaultColumnDimension()->setWidth(64,'px');

    $sheet->getColumnDimension('A')->setWidth(20, 'px');
    $sheet->getColumnDimension('B')->setWidth(72, 'px');
    $sheet->getColumnDimension('C')->setWidth(90, 'px');
    $sheet->getColumnDimension('D')->setWidth(27, 'px');
    $sheet->getColumnDimension('E')->setWidth(34, 'px');
    $sheet->getColumnDimension('F')->setWidth(128, 'px');
    $sheet->getColumnDimension('G')->setWidth(35, 'px');
    $sheet->getColumnDimension('H')->setWidth(64, 'px');
    $sheet->getColumnDimension('I')->setWidth(64, 'px');
    $sheet->getColumnDimension('J')->setWidth(81, 'px');
    $sheet->getColumnDimension('K')->setWidth(156, 'px');

    // $sheet->setCellValue('D2', S_MSG('PL71','KUITANSI'));
    
    $sheet->setCellValue('D2', 'KUITANSI');
    $cFONT_HDR = 'Arial Narrow';   $nSIZE_HDR = 12;  $cBOLD_HDR=' '; $cITAL_HDR=' '; $cUNDRL_HDR=' ';
    $qFONT= OpenTable('TbFont', "KEY_ID='XLS_OVTM_KW_HEADER' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
    if(SYS_ROWS($qFONT)>0) {
        $aFONT = SYS_FETCH($qFONT);
        $cFONT_HDR = $aFONT['NAME'];
        if($aFONT['BOLD']==1)	$cBOLD_HDR='B';
        if($aFONT['ITALIC']==1)	$cITAL_HDR='I';
        if($aFONT['UNDERLINE']==1)	$cUNDRL_HDR='U';
        $nSIZE_HDR = intval($aFONT['SIZE']);
    }
    // $sheet->getStyle('D2')->getFont()->setName($cFONT_HDR);
    $sheet->getStyle('D2')->getFont()->setName('Arial Narrow'); //KUITANSI
    $sheet->getStyle('D2')->getFont()->setSize(28);
    // if($cBOLD_HDR=='B') $sheet->getStyle('D2')->getFont()->setBold(true);
    // if($cITAL_HDR=='I') $sheet->getStyle('D2')->getFont()->setItalic(true);
    // if($cUNDRL_HDR=='U') $sheet->getStyle('D2')->getFont()->setUnderline(true);
    
    
    $sheet->setCellValue('B3', 'PT YAZA PRATAMA'); // judul di bawah logo
    $sheet->getStyle('B3')->getFont()->setName('Broadway');
    $sheet->getStyle('B3')->getFont()->setSize(10);
    $cFONT_HDR = 'Arial Narrow';   $nSIZE_HDR = 12;  $cBOLD_HDR=' '; $cITAL_HDR=' '; $cUNDRL_HDR=' ';
    $qFONT= OpenTable('TbFont', "KEY_ID='XLS_OVTM_KW_CO1' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
    if(SYS_ROWS($qFONT)>0) {
        $aFONT = SYS_FETCH($qFONT);
        $cFONT_HDR = $aFONT['NAME'];
        if($aFONT['BOLD']==1)	$cBOLD_HDR='B';
        if($aFONT['ITALIC']==1)	$cITAL_HDR='I';
        if($aFONT['UNDERLINE']==1)	$cUNDRL_HDR='U';
        $nSIZE_HDR = intval($aFONT['SIZE']);
    }
    // $sheet->getStyle('B3')->getFont()->setName($cFONT_HDR);
    // $sheet->getStyle('B3')->getFont()->setSize($nSIZE_HDR);
    // if($cBOLD_HDR=='B') $sheet->getStyle('B3')->getFont()->setBold(true);
    // if($cITAL_HDR=='I') $sheet->getStyle('B3')->getFont()->setItalic(true);
    // if($cUNDRL_HDR=='U') $sheet->getStyle('B3')->getFont()->setUnderline(true);

    $sheet->getStyle('A5:K27')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A5:K27')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A5:K27')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A5:K27')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $cLEGAL_NAME=$aCUST['CUST_NAME'];
    $qCUST_LEG=OpenTable('TbCustLegal', "APP_CODE='$cAPP_CODE' and CUST_CODE='$cCUST_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
    if($aCUST_LEG = SYS_FETCH($qCUST_LEG))     $cLEGAL_NAME=$aCUST_LEG['CUST_NAME'];

    $sheet->setCellValue('B6', S_MSG('PL72','Sudah Terima Dari'));
    $sheet->setCellValue('D6', ':');
    $sheet->setCellValue('E6', $cLEGAL_NAME);
    $sheet->setCellValue('B8', S_MSG('PL73','Uang sejumlah'));
    $sheet->setCellValue('D8', ':');
    // $sheet->setCellValue('E8', 'Rp.');
    $sheet->setCellValue('E8', '=Rekap!E'.(string)$nTROW);
    $sheet->setCellValue('B9', S_MSG('PL74','Terbilang'));
    $sheet->setCellValue('D9', ':');
    // $sheet->setCellValue('E9', ucwords(SAYS($tNILAI)). ' Rupiah.');
    $rumus = '=IF(E8=0,"nol",IF(E8<0,"minus ","")& SUBSTITUTE(TRIM(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE(SUBSTITUTE( IF(--MID(TEXT(ABS(E8),"000000000000000"),1,3)=0,"",MID(TEXT(ABS(E8),"000000000000000"),1,1)&" ratus "&MID(TEXT(ABS(E8),"000000000000000"),2,1)&" puluh "&MID(TEXT(ABS(E8),"000000000000000"),3,1)&" triliun ")& IF(--MID(TEXT(ABS(E8),"000000000000000"),4,3)=0,"",MID(TEXT(ABS(E8),"000000000000000"),4,1)&" ratus "&MID(TEXT(ABS(E8),"000000000000000"),5,1)&" puluh "&MID(TEXT(ABS(E8),"000000000000000"),6,1)&" miliar ")& IF(--MID(TEXT(ABS(E8),"000000000000000"),7,3)=0,"",MID(TEXT(ABS(E8),"000000000000000"),7,1)&" ratus "&MID(TEXT(ABS(E8),"000000000000000"),8,1)&" puluh "&MID(TEXT(ABS(E8),"000000000000000"),9,1)&" juta ")& IF(--MID(TEXT(ABS(E8),"000000000000000"),10,3)=0,"",IF(--MID(TEXT(ABS(E8),"000000000000000"),10,3)=1,"*",MID(TEXT(ABS(E8),"000000000000000"),10,1)&" ratus "&MID(TEXT(ABS(E8),"000000000000000"),11,1)&" puluh ")&MID(TEXT(ABS(E8),"000000000000000"),12,1)&" ribu ")& IF(--MID(TEXT(ABS(E8),"000000000000000"),13,3)=0,"",MID(TEXT(ABS(E8),"000000000000000"),13,1)&" ratus "&MID(TEXT(ABS(E8),"000000000000000"),14,1)&" puluh "&MID(TEXT(ABS(E8),"000000000000000"),15,1)),1,"satu"),2,"dua"),3,"tiga"),4,"empat"),5,"lima"),6,"enam"),7,"tujuh"),8,"delapan"),9,"sembilan"),"0 ratus",""),"0 puluh",""),"satu puluh 0","sepuluh"),"satu puluh satu","sebelas"),"satu puluh dua","dua belas"),"satu puluh tiga","tiga belas"),"satu puluh empat","empat belas"),"satu puluh lima","lima belas"),"satu puluh enam","enam belas"),"satu puluh tujuh","tujuh belas"),"satu puluh delapan","delapan belas"),"satu puluh sembilan","sembilan belas"),"satu ratus","seratus"),"*satu ribu","seribu"),0,""))," "," "))&" rupiah"';
    $sheet->setCellValue('E9', (string)$rumus); //angka
    $sheet->getStyle('E8')->getAlignment()->setWrapText(true);
    $sheet->getStyle('E9')->getAlignment()->setWrapText(true);
    $sheet->getStyle('E8')->getFont()->setBold(true);
    $sheet->getStyle('E8')->getFont()->setItalic(true);
    $sheet->getStyle('E9')->getFont()->setBold(true);
    $sheet->getStyle('E9')->getFont()->setItalic(true);
    $sheet->getStyle('E37')->getFont()->setBold(true);
    $sheet->getStyle('E37')->getFont()->setItalic(true);
    $sheet->getStyle('E8')->getNumberFormat()->setFormatCode('Rp#,##0');
    $sheet->getStyle('E36')->getNumberFormat()->setFormatCode('Rp#,##0');
    $sheet->getStyle('E8')->getFont()->setSize(15);

    $sheet->mergeCells('E8:J8');
    $sheet->mergeCells('E9:J9');
    $sheet->getStyle('E8')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_LEFT);

    $sheet->getStyle('B8:E8')->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER);
    $sheet->getStyle('B9:F9')->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER);
    $sheet->getRowDimension('8')->setRowHeight(35);
    $sheet->getRowDimension('9')->setRowHeight(40);
    $sheet->setCellValue('B11', S_MSG('PL75','Untuk Pembayaran'));
    $sheet->setCellValue('D11', ':');
    $sheet->setCellValue('E11', '=Rincian!A2');
    $sheet->getStyle('E11')->getFont()->setBold(true);
    $sheet->setCellValue('J11', S_MSG('PL76','Biaya Tidak Tetap (Lembur)'));
    $sheet->getStyle('J11')->getFont()->setItalic(true);
    $sheet->getStyle('J11')->getFont()->setBold(true);
    $sheet->setCellValue('E12', S_MSG('PL77','Pemborongan Pekerjaan Nomor WBJ/7.2/363/R'));
    $sheet->setCellValue('E13', S_MSG('PL78','Tanggal 31 Desember 2019'));
    $sheet->setCellValue('E14', "Invoice No. : ---- /Inv-YP/".$current_romawi_month."/".date("Y")); //TODO convert month to roman numerals later
    
    
   
    $sheet->setCellValue('B16', 'Banjarmasin, ' . $current_day . ' ' . $current_month_indo . ' ' . $current_year);
    // $sheet->setCellValue('E16', S_PARA('CO3','Banjarmasin'));

    $sheet->mergeCells('B16:D16');
    $sheet->getStyle('B16')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER); // kota, tanggal
    // $sheet->setCellValue('B17', S_PARA('CO1','Rainbow Inc')); 
    $sheet->setCellValue('B17', 'PT YAZA PRATAMA'); 
    $sheet->mergeCells('B17:D17');
    $sheet->getStyle('B17')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER); // nama company
    $sheet->setCellValue('B18', 'Hormat kami');
    $sheet->mergeCells('B18:D18');
    $sheet->getStyle('B18')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER); // hormat kami
    // $sheet->setCellValue('B24', S_PARA('CO_DIR_NAME','Riza Fahlevi'));
    $sheet->setCellValue('B24', 'Kumala Sari');
    $sheet->mergeCells('B24:D24');
    $sheet->getStyle('B24')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER); // nama penandatangan
    $sheet->getStyle('B24')->getFont()->setUnderline(true);
    $sheet->getStyle('B24')->getFont()->setBold(true);
    $sheet->setCellValue('B25', S_PARA('CO_DIR_JOB','Direktur'));
    $sheet->mergeCells('B25:D25');
    $sheet->getStyle('B25')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER); // jabatan penandatangan

    $logo = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $logo->setName('logo');
    $logo->setDescription('logo');
    $logo->setPath('data/images/'.$cAPP_CODE.'_KOP.jpg');
    $logo->setWidthAndHeight(60, 60);
    $logo->setCoordinates('B1');
    $logo->setOffsetX(40); // menambah margin kiri agar terlihat seperti di tengah
    $logo->setOffsetY(9); // menambah margin atas kebawah sedikit
    $logo->setWorksheet($sheet);






//------------------------------------------------------------------------------------------
// -- KUITANSI BAGIAN BAWAH
//------------------------------------------------------------------------------------------
    $logo2 = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $logo2->setName('logo2');
    $logo2->setDescription('logo2');
    $logo2->setPath('data/images/'.$cAPP_CODE.'_KOP.jpg');
    $logo2->setWidthAndHeight(60, 60);
    $logo2->setCoordinates('B29');
    $logo2->setOffsetX(40); // menambah margin kiri agar terlihat seperti di tengah
    $logo2->setOffsetY(8); // menambah margin atas kebawah sedikit
    $logo2->setWorksheet($sheet);
    
    $sheet->mergeCells('D30:K30');
    // $sheet->getStyle('E14')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('D30')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER);
    
    // Set the default column width to 64 pixels
    $sheet->getDefaultColumnDimension()->setWidth(64,'px');

    $sheet->getColumnDimension('A')->setWidth(20, 'px');
    $sheet->getColumnDimension('B')->setWidth(72, 'px');
    $sheet->getColumnDimension('C')->setWidth(90, 'px');
    $sheet->getColumnDimension('D')->setWidth(27, 'px');
    $sheet->getColumnDimension('E')->setWidth(34, 'px');
    $sheet->getColumnDimension('F')->setWidth(128, 'px');
    $sheet->getColumnDimension('G')->setWidth(35, 'px');
    $sheet->getColumnDimension('H')->setWidth(64, 'px');
    $sheet->getColumnDimension('I')->setWidth(64, 'px');
    $sheet->getColumnDimension('J')->setWidth(81, 'px');
    $sheet->getColumnDimension('K')->setWidth(156, 'px');

    $sheet->setCellValue('D30', 'KUITANSI');
    $cFONT_HDR = 'Arial Narrow';   $nSIZE_HDR = 12;  $cBOLD_HDR=' '; $cITAL_HDR=' '; $cUNDRL_HDR=' ';
    $qFONT= OpenTable('TbFont', "KEY_ID='XLS_OVTM_KW_HEADER' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");


    $sheet->getStyle('D30')->getFont()->setName('Arial Narrow'); //KUITANSI
    $sheet->getStyle('D30')->getFont()->setSize(28);
    
    $sheet->setCellValue('B31', 'PT YAZA PRATAMA'); // judul di bawah logo
    $sheet->getStyle('B31')->getFont()->setName('Broadway');
    $sheet->getStyle('B31')->getFont()->setSize(10);
    $sheet->getStyle('A33:K55')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A33:K55')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A33:K55')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A33:K55')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $sheet->setCellValue('B34', S_MSG('PL72','Sudah Terima Dari'));
    $sheet->setCellValue('D34', ':');
    $sheet->setCellValue('E34', '=E6');
    $sheet->setCellValue('B36', S_MSG('PL73','Uang sejumlah'));
    $sheet->setCellValue('D36', ':');
    // $sheet->setCellValue('E36', 'Rp.');
    $sheet->setCellValue('E36', '=E8');
    $sheet->getStyle('E36')->getNumberFormat()->setFormatCode('Rp#,##0');
    
    $sheet->mergeCells('E36:J36');
    $sheet->mergeCells('E37:J37');
    $sheet->getStyle('E36')->getAlignment()->setWrapText(true);
    $sheet->getStyle('E37')->getAlignment()->setWrapText(true);
    $sheet->getStyle('E36')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_LEFT);

    $sheet->setCellValue('B37', S_MSG('PL74','Terbilang'));
    $sheet->setCellValue('D37', ':');
    $sheet->setCellValue('E37', '=E9');
    // E8 -> E36
    $sheet->getStyle('E36')->getFont()->setBold(true);
    $sheet->getStyle('E36')->getFont()->setItalic(true);
    $sheet->getStyle('E36')->getFont()->setSize(15);
    $sheet->getStyle('B36:E36')->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER);
    $sheet->getStyle('B36:F37')->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER);
    $sheet->getRowDimension('36')->setRowHeight(35);
    $sheet->getRowDimension('37')->setRowHeight(40);
    $sheet->setCellValue('B39', 'Untuk Pembayaran');
    $sheet->setCellValue('D39', ':');
    $sheet->setCellValue('E39', '=Rincian!A2');
    $sheet->getStyle('E39')->getFont()->setBold(true);
    $sheet->setCellValue('J39', S_MSG('PL76','Biaya Tidak Tetap (Lembur)'));
    $sheet->getStyle('J39')->getFont()->setItalic(true);
    $sheet->getStyle('J39')->getFont()->setBold(true);
    $sheet->setCellValue('E40', '=E12');
    $sheet->setCellValue('E41', '=E13');
    $sheet->setCellValue('E42', '=E14');
    
    $sheet->setCellValue('B44', '=B16');

    //17=45, 18=46, 24=52, 25=53
    $sheet->mergeCells('B44:D44');
    $sheet->getStyle('B44')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER); // kota, tanggal
    $sheet->setCellValue('B45', 'PT YAZA PRATAMA'); 
    $sheet->mergeCells('B45:D45');
    $sheet->getStyle('B45')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER); // nama company
    $sheet->setCellValue('B46', 'Hormat kami');
    $sheet->mergeCells('B46:D46');
    $sheet->getStyle('B46')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER); // hormat kami
    $sheet->setCellValue('B52', 'Kumala Sari');
    $sheet->mergeCells('B52:D52');
    $sheet->getStyle('B52')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER); // nama penandatangan
    $sheet->getStyle('B52')->getFont()->setUnderline(true);
    $sheet->getStyle('B52')->getFont()->setBold(true);
    $sheet->setCellValue('B53', S_PARA('CO_DIR_JOB','Direktur'));
    $sheet->mergeCells('B53:D53');
    $sheet->getStyle('B53')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER); // jabatan penandatangan

}

ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="lembur-'.$aCUST['ALT_NAME'].'.xlsx"');
header('Cache-Control: max-age=0');

// (E) OUTPUT
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
exit($writer->save('php://output'));
echo "<script> alert('OK');	window.history.back();	</script>";

