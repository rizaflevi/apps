<?php
// prs_rp_abs_upd.php
// call from prs_rp_overtime.php

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
$cUSERCODE 		= $_SESSION['gUSERCODE'];
$can_UPD = TRUST($cUSERCODE, 'PRS_PRESENT_UPD');
if(!$can_UPD)       return;
$nPRSNT=(isset($_POST['_f']) ? (integer)$_POST['_f'] : 0);

$cVALUE=$cPERSON=$cDATE=$dTGL_ABSEN='';
if (isset($_POST['_v'])) $cVALUE=$_POST['_v'];  else return;
if (isset($_POST['_p'])) $cPERSON=$_POST['_p']; else return;
if (isset($_POST['_d'])) $cDATE=$_POST['_d'];   else return;

$cSHIFT_CODE = Get_Personal_Schedule_Code($cPERSON, $cDATE);
if ($cSHIFT_CODE=='') {
    $q_OCCU=OpenTable('PrsOccuption', "PRSON_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and DELETOR=''");
    if($aOCCU=SYS_FETCH($q_OCCU)){
        $cSHIFT_CODE = Get_Group_Schedule_Code($aOCCU['CUST_CODE'], $aOCCU['KODE_LOKS'], $aOCCU['JOB_CODE'], $dDATE);
    }

}
if ($cSHIFT_CODE!='') {
    $qTZ = OpenTable('Timezone', "DAYL_CODE='$cSHIFT_CODE' and APP_CODE='$cAPP_CODE'");
    $nSERVER_TIME=7;
    if (SYS_ROWS($qTZ)>0) {
        $aTIMEZONE	= SYS_FETCH($qTZ);
        $nCONVERSI_TIME = ($aTIMEZONE ? $aTIMEZONE['TIME_TO_UTC'] : 7 ) - $nSERVER_TIME;
    }
}

if ($cVALUE){
    $nJAM = (int) substr($cVALUE,0,2) - $nCONVERSI_TIME;
    $cSRV_TM = str_pad($nJAM , 2 , "0" , STR_PAD_LEFT).':'.substr($cVALUE,3,2);
    $dTGL_ABSEN=$cDATE.' '.$cSRV_TM.':00';
} else return;

if ($nPRSNT==4) {
    $dTGL_ABSEN=$cDATE.' 00:00:00';
    $qPRESENCE	= OpenTable('PrsOvertime', "APP_CODE='$cAPP_CODE' and date(OVT_START) = '$cDATE' and PRSON_CODE='$cPERSON'");
    if(SYS_ROWS($qPRESENCE)==0)
        RecCreate('PrsOvertime', ['PRSON_CODE', 'OVT_START', 'OVT_MINUTE', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], [$cPERSON, $dTGL_ABSEN, 60*(integer)$cVALUE, $cUSERCODE, date("Y-m-d H:i:s"), $cAPP_CODE]);
    else
        RecUpdate('PrsOvertime', ['OVT_MINUTE', 'UP_DATE', 'UPD_DATE'], [60*(integer)$cVALUE, $cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON' and date(OVT_START)='$cDATE'");
} else {
    if ($nPRSNT>1) {
        $qPRESENCE	= OpenTable('PrsOvertime', "APP_CODE='$cAPP_CODE' and date(OVT_START) = '$cDATE' and PRSON_CODE='$cPERSON'");
        if(SYS_ROWS($qPRESENCE)==0) {
            if($nPRSNT==2)  
                RecCreate('PrsOvertime', ['PRSON_CODE', 'OVT_START', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], [$cPERSON, $dTGL_ABSEN, $cUSERCODE, date("Y-m-d H:i:s"), $cAPP_CODE]);
            else
                RecCreate('PrsOvertime', ['PRSON_CODE', 'OVT_END', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], [$cPERSON, $dTGL_ABSEN, $cUSERCODE, date("Y-m-d H:i:s"), $cAPP_CODE]);
        } else {
            if($nPRSNT==2)
                RecUpdate('PrsOvertime', ['OVT_START', 'UP_DATE', 'UPD_DATE'], [$dTGL_ABSEN, $cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON' and date(OVT_START)='$cDATE'");
            else
                RecUpdate('PrsOvertime', ['OVT_END', 'UP_DATE', 'UPD_DATE'], [$dTGL_ABSEN, $cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON' and date(OVT_START)='$cDATE'");
        }
    } else {
        $qPRESENCE	= OpenTable('ArrPresence', "PRESENT_CODE='$nPRSNT' and APP_CODE='$cAPP_CODE' and date(PPL_PRESENT) = '$cDATE' and DELETOR='' and PEOPLE_CODE='$cPERSON'");
        if(SYS_ROWS($qPRESENCE)==0) {
            RecCreate('Presence', ['PEOPLE_CODE', 'PPL_PRESENT', 'PRESENT_CODE', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], [$cPERSON, $dTGL_ABSEN, $nPRSNT, $cUSERCODE, date("Y-m-d H:i:s"), $cAPP_CODE]);
        } else {
            RecUpdate('Presence', ['PPL_PRESENT', 'UP_DATE', 'UPD_DATE'], [$dTGL_ABSEN, $cUSERCODE, date("Y-m-d H:i:s")], "PRESENT_CODE='$nPRSNT' and APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPERSON' and date(PPL_PRESENT)='$cDATE'");
        }
    }
}
?>

