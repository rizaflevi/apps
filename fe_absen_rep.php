<?php
//	fe_absen_rep.php //

    $_SESSION['cHOST_DB2'] = 'riza_db';
    $_SESSION['sLANG']		= '1';
    include "sysfunction.php";
//    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
    $cAPP_CODE  = $_SESSION['data_FILTER_CODE'] = $_GET['_app'];
    $cPRS_CODE  = $_SESSION['gUSERCODE'] = $_GET['_prs'];

	$sPERIOD1=date("Y-m-d");
	if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];
	$cHEADER 	= S_MSG('PI21','Laporan Absen Karyawan');
    $dTODAY = date('Y-m-d');
    $dLASTDAY = date('Y-m-d', strtotime('-15 days'));

	$cTANGGAL 	= S_MSG('RS02','Tanggal');
	$cMASUK     = S_MSG('PI22','Masuk');
	$cPULANG	= S_MSG('PI23','Pulang');
	$cLBR_MSK   = S_MSG('PI41','Lembur Masuk');
	$cLBR_KLR   = S_MSG('PI42','Lembur Pulang');
	$cNAMA_HARI = S_MSG('PA71','Hari');
	$cJAM       = 'Jam';
	$aNAMA_HARI		= array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', '');

    require_once("fe_topbar.php");  echo '<br><br><br>';
	require_once("cl_header.php");
    echo '<body>';
    echo '<script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>';
    echo '		<div class="page-container row-fluid">    ';
    TABLE('myTable');
        THEAD([$cTANGGAL, $cNAMA_HARI, $cMASUK, $cPULANG, $cLBR_MSK, $cLBR_KLR, $cJAM], '', [], '*');
        echo '<tbody>';
        $dBEG   = new DateTime($dTODAY);
        $dEND   = new DateTime($dLASTDAY);
        $cPERIOD1=$dBEG->format('Y-m-d');
        $cPERIOD2=$dEND->format('Y-m-d');

        RecDelete('RepAbsen', "APP_CODE='$cPRS_CODE'");
        $cQRY = "insert into prs_rabs (PRSON_CODE, ABSN_DATE, ABSN_MSK, APP_CODE) ";
        $cQRY .= "select PEOPLE_CODE, DATE(PPL_PRESENT), substr(PPL_PRESENT, 12,5), '". $cPRS_CODE;
        $cQRY .= "' from people_present where PRESENT_CODE=0 and APP_CODE='$cAPP_CODE' and date(PPL_PRESENT) between '$cPERIOD2' and '$cPERIOD1' and DELETOR='' and PEOPLE_CODE='$cPRS_CODE' group by PEOPLE_CODE, date(PPL_PRESENT), PRESENT_CODE";
        SYS_QUERY($cQRY);
        $qPRESENCE	= OpenTable('ArrPresence', "PRESENT_CODE=1 and APP_CODE='$cAPP_CODE' and date(PPL_PRESENT) between '$cPERIOD2' and '$cPERIOD1' and DELETOR='' and PEOPLE_CODE='$cPRS_CODE'");
        while($a_ABSEN=SYS_FETCH($qPRESENCE)){
            $cPEOPLE_CODE = $a_ABSEN['PEOPLE_CODE'];
            $dTGL_ABSEN = $a_ABSEN['TGL'];
            $cJAM_ABSEN = substr($a_ABSEN['PPL_PRESENT'], 11,5);
            $nKODE_ABSN = $a_ABSEN['PRESENT_CODE'];
            $qREP_ABSN=OpenTable('RepAbsen', "APP_CODE='$cPRS_CODE' and PRSON_CODE='$cPEOPLE_CODE' and ABSN_DATE='$dTGL_ABSEN'");
            if (SYS_ROWS($qREP_ABSN)==0) {
                RecCreate('RepAbsen', ['PRSON_CODE', 'ABSN_DATE', 'APP_CODE'], [$cPEOPLE_CODE, $dTGL_ABSEN, $cPRS_CODE]);
            }

            if ($nKODE_ABSN==1) RecUpdate('RepAbsen', ['ABSN_KLR'], [$cJAM_ABSEN], "APP_CODE='$cPRS_CODE' and PRSON_CODE='$cPEOPLE_CODE' and ABSN_DATE='$dTGL_ABSEN'");
        }
        $qOVERTIME	= OpenTable('PrsOvertime', "APP_CODE='$cAPP_CODE' and date(OVT_START) between '$cPERIOD2' and '$cPERIOD1' and PRSON_CODE='$cPRS_CODE'");
        while($aOVT=SYS_FETCH($qOVERTIME)){
            $dTGL_ABSEN=substr($aOVT['OVT_START'], 0,10);
            $qREP_ABSN=OpenTable('RepAbsen', "APP_CODE='$cPRS_CODE' and PRSON_CODE='$aOVT[PRSON_CODE]' and ABSN_DATE='$dTGL_ABSEN'");
            if (SYS_ROWS($qREP_ABSN)==0) {
                RecCreate('RepAbsen', ['PRSON_CODE', 'ABSN_DATE', 'APP_CODE'], [$aOVT['PRSON_CODE'], $dTGL_ABSEN, $cPRS_CODE]);
            }
            RecUpdate('RepAbsen', ['OVERTIME_IN', 'OVERTIME_OUT', 'OVERTIME_QTY'], [substr($aOVT['OVT_START'],11,5), substr($aOVT['OVT_END'],11,5), intVal($aOVT['OVT_MINUTE']/60)], 
                "APP_CODE='$cPRS_CODE' and PRSON_CODE='$aOVT[PRSON_CODE]' and ABSN_DATE='$dTGL_ABSEN'");
        }
        $nCONVERSI_TIME=0;
        $qTEMP_PRESENCE = OpenTable('FePresence', "APP_CODE='$cPRS_CODE'", 'PRSON_CODE, ABSN_DATE, ABSN_MSK, ABSN_KLR', 'ABSN_DATE desc');
        while($aREC_TEMP=SYS_FETCH($qTEMP_PRESENCE)){
            $dDATE=$aREC_TEMP['ABSN_DATE'];
            $cSHIFT_CODE = Get_Personal_Schedule_Code($aREC_TEMP['PRSON_CODE'], $dDATE);
            if ($cSHIFT_CODE=='') {
                $cSHIFT_CODE = Get_Group_Schedule_Code($aREC_TEMP['CUST_CODE'], $aREC_TEMP['KODE_LOKS'], $aREC_TEMP['JOB_CODE'], $dDATE);
            }
            if ($cSHIFT_CODE!='') {
                $qTZ = OpenTable('Timezone', "DAYL_CODE='$cSHIFT_CODE' and APP_CODE='$cAPP_CODE'");
            }
            $nSERVER_TIME=7;		// server time
            $qTIMEZONE	= OpenTable('Timezone', "A.DAYL_CODE='$cSHIFT_CODE' and A.APP_CODE='$cAPP_CODE'  and A.DELETOR=''");
            if ($aTIMEZONE	= SYS_FETCH($qTIMEZONE)) {
                $nCONVERSI_TIME = ($aTIMEZONE ? $aTIMEZONE['TIME_TO_UTC'] : 7 ) - $nSERVER_TIME;
            }

            $nHARI = date("w", strtotime($dDATE));
            $cDATE=substr($dDATE, 8, 2).'/'.substr($dDATE, 5, 2).'/'.substr($dDATE, 2, 2);

            $cABS_MASUK = $aREC_TEMP['ABSN_MSK'];
            if ($cABS_MASUK!='' && $cABS_MASUK!='00:00'){
                $nABS_MASUK = (int) substr($cABS_MASUK,0,2) + $nCONVERSI_TIME;
                $cABS_MASUK = str_pad($nABS_MASUK , 2 , "0" , STR_PAD_LEFT).':'.substr($cABS_MASUK,3,2);
            }
            
            $cABS_PULANG = $aREC_TEMP['ABSN_KLR'];
            if ($cABS_PULANG!='' && $cABS_PULANG!='00:00'){
                $nABS_PULANG = (int) substr($cABS_PULANG,0,2) + $nCONVERSI_TIME;
                $cABS_PULANG = str_pad($nABS_PULANG , 2 , "0" , STR_PAD_LEFT).':'.substr($cABS_PULANG,3,2);
            }

            $cLBR_MASUK = $aREC_TEMP['OVERTIME_IN'];
            if ($cLBR_MASUK!=''){
                $nABS_MASUK = (int) substr($cLBR_MASUK,0,2) + $nCONVERSI_TIME;
                $cLBR_MASUK = str_pad($nABS_MASUK , 2 , "0" , STR_PAD_LEFT).':'.substr($cLBR_MASUK,3,2);
            }

            $cLBR_PULANG = $aREC_TEMP['OVERTIME_OUT'];
            if ($cLBR_PULANG!='' && $cLBR_PULANG!='00:00'){
                $nLBR_PULANG = (int) substr($cLBR_PULANG,0,2) + $nCONVERSI_TIME;
                $cLBR_PULANG = str_pad($nLBR_PULANG , 2 , "0" , STR_PAD_LEFT).':'.substr($cLBR_PULANG,3,2);
            } else {
                $cLBR_PULANG = '';
            }

            $nLBR_MSK = (int) substr($cLBR_MASUK,0,2)*60 + (int) substr($cLBR_MASUK, 3,2);
            $nLBR_PLG = (int) substr($cLBR_PULANG,0,2) *60 + (int) substr($cABS_PULANG, 3,2);
            $nJAM_LBR=0;
            if($cLBR_MASUK!='') {
                $cLEMBUR = ($nLBR_PLG-$nLBR_MSK)/60 . ':' . ($nLBR_PLG-$nLBR_MSK)%60;
                $nLEMBUR = $nLBR_PLG-$nLBR_MSK;
                $nJAM_LBR = $nLEMBUR/60;
            }

            echo '<tr>';
            echo "<td>".$cDATE."</td>";
            echo "<td>".$aNAMA_HARI[$nHARI]."</td>";
            echo "<td>".$cABS_MASUK."</td>";
            echo "<td>".$cABS_PULANG."</td>";
            echo "<td>".$cLBR_MASUK."</td>";
            echo "<td>".$cLBR_PULANG."</td>";
            echo "<td align='left'><span>".($nJAM_LBR>0 ? number_format($nJAM_LBR, 0) : '')." </span></td>";
            echo "<td></td>";
            echo '</tr>';
        }
        echo '</tbody>';
    eTABLE();
    echo '<p><button type="button" class="btn btn-primary" onclick=window.history.go(-1)>Close</button></p>';
    END_WINDOW();

?>

