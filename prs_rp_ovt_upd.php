<?php
// prs_rp_ovt_upd.php
// call from prs_rp_overtime.php

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];

$cFIELD=(isset($_POST['_f']) ? $_POST['_f'] : '');

$cVALUE=$cPERSON=$cDATE='';
if (isset($_POST['_p'])) $cPERSON=$_POST['_p'];
if (isset($_POST['_d'])) $cDATE=$_POST['_d'];
if($cFIELD=='OVT_MINUTE')   {
    if (isset($_POST['_v'])) $cVALUE=str_replace('_', '', $_POST['_v']);
    $cTG=intval($cVALUE)*60;
} else {
    if (isset($_POST['_v'])) $cVALUE=str_replace('_', '0', $_POST['_v']);
    $cSHIFT_CODE = Get_Personal_Schedule_Code($cPERSON, $cDATE);
    if ($cSHIFT_CODE=='') {
        $q_OCCU=OpenTable('PrsOccuption', "PRSON_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and DELETOR=''");
        if($aOCCU=SYS_FETCH($q_OCCU)){
            $cSHIFT_CODE = Get_Group_Schedule_Code($aOCCU['CUST_CODE'], $aOCCU['KODE_LOKS'], $aOCCU['JOB_CODE'], $cDATE);
        }

    }
    if ($cSHIFT_CODE!='') {
        $qTZ = OpenTable('Timezone', "DAYL_CODE='$cSHIFT_CODE' and APP_CODE='$cAPP_CODE'");
    }
    $nSERVER_TIME=7;
    if (SYS_ROWS($qTZ)>0) {
        $aTIMEZONE	= SYS_FETCH($qTZ);
        $nCONVERSI_TIME = ($aTIMEZONE ? $aTIMEZONE['TIME_TO_UTC'] : 7 ) - $nSERVER_TIME;
    }

    if ($cVALUE){
        $nJAM = (int) substr($cVALUE,0,2) - $nCONVERSI_TIME;
        $cSRV_TM = str_pad($nJAM , 2 , "0" , STR_PAD_LEFT).':'.substr($cVALUE,3,2);
        $cTG=$cDATE.' '.$cSRV_TM.':00';
    }
}
RecUpdate('PrsOvertime', [$cFIELD], [$cTG], "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON' and date(OVT_START)='$cDATE'");
?>

