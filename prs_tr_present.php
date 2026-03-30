<?php
//	prs_tr_present.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cHELP_FILE = 'Doc/Transaksi - Absen.pdf';
	$cHEADER 	= S_MSG('PH81','Absen Karyawan');
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];	
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$can_CREATE = TRUST($cUSERCODE, 'PRS_ABSN_1ADD');

	$cACTION = (isset($_GET['_a']) ? $_GET['_a'] : '');
  	$cFILTER_PERSON=(isset($_GET['_p']) ? $_GET['_p'] : '');

	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];

	$cKD_PERSON 	= S_MSG('PA02','Kode Peg');
	$cNM_PERSON 	= S_MSG('PA03','Nama Karyawan');
	$cKETERANGAN 	= S_MSG('PA98','Keterangan');
	$cTANGGAL 		= S_MSG('RS02','Tanggal');
	$cMASUK		    = S_MSG('PI22','Msk');
	$cPULANG		= S_MSG('PI23','Plg');
	$cOVT_IN		= S_MSG('PI41','Ovt in');
	$cOVT_OUT		= S_MSG('PI42','Ovt out');
	$cJAM_LBR		= S_MSG('PA72','Jam');

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'View');
        $cADD_ABSEN 	= S_MSG('PH92','Add Absent');

		$can_VW_ALL	= TRUST($cUSERCODE, 'PRS_ABSN_5UPD_ALL');
		$cFILTER_PEOPLE = "M.PRSON_SLRY<2 and P.APP_CODE='$cAPP_CODE' and P.DELETOR='' and P.PEOPLE_CODE not in ( select PRSON_CODE from prs_resign where APP_CODE='$cAPP_CODE' and P.DELETOR='')";
		if ($can_VW_ALL==0) $cFILTER_PEOPLE .= " and C.CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE')";
		$qPEOPLE=OpenTable('PeopleCustomer', $cFILTER_PEOPLE, '', 'PEOPLE_NAME');
		$allPEOPLE = ALL_FETCH($qPEOPLE);

		DEF_WINDOW($cADD_ABSEN);
            TFORM($cADD_ABSEN, '?_a=tambah', [], $cHELP_FILE);
                TDIV();
					LABEL([4,4,4,4], '700', $cNM_PERSON);
                    SELECT([8,8,8,8], 'ADD_PRSON_CODE', '', '', 'select2');
                    // echo '<select name="ADD_PRSON_CODE" class="select2-container" id="s2example-1">';
                        echo '<option></option>';
                        $qCUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''". ($can_VW_ALL==0 ? " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE')" : ""), '', 'CUST_NAME');
                        while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
                            echo '<optgroup label="'.$aCUSTOMER['CUST_NAME'].'">';
                            $I=0;
                            $nSIZE_ARRAY = count($allPEOPLE);
                            while($I<$nSIZE_ARRAY-1) {
                                if ($allPEOPLE[$I]['CUST_CODE']==$aCUSTOMER['CUST_CODE']) {
                                    $cSELECT = $allPEOPLE[$I]['PEOPLE_NAME']."  /  ".$allPEOPLE[$I]['PEOPLE_CODE']."  /  ".$allPEOPLE[$I]['LOKS_NAME'];
                                    $cVALUE = $allPEOPLE[$I]['PEOPLE_CODE'];
                                    echo '<option value="'.$cVALUE.'">'.$cSELECT.'</option>';
                                }
                                $I++;
                            }
                            echo '</optgroup>';
                        }
			        echo '</select><br><br>';
                    CLEAR_FIX();
					LABEL([4,4,4,4], '', $cTANGGAL);
					INP_DATE([2,3,3,5], '900', 'ADD_ABSN_DATE', date('d/m/Y'), '', '', '', 'fix');
					LABEL([4,4,4,4], '', $cMASUK);
					INP_TIME([], '', 'ADD_MASUK', '', '', '99:99', '', 'fix');
					LABEL([4,4,4,4], '', $cPULANG);
					INP_TIME([], '', 'ADD_PULANG', '', '', '', '', 'fix');
					LABEL([4,4,4,4], '', $cOVT_IN);
					INP_TIME([], '', 'ADD_LBR_MSK', '', '', '', '', 'fix');
					LABEL([4,4,4,4], '', $cOVT_OUT);
					INP_TIME([], '', 'ADD_LBR_PLG', '', '', '', '', 'fix');
					LABEL([4,4,4,4], '', $cJAM_LBR);
                    INPUT('number', [1,1,1,1], '900', 'ADD_JAM_LBR', '', '', '', '', 0, '', 'Fix');
					SAVE(S_MSG('F301','Save'));
                TDIV();
            eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
    break;

    case md5('up_da_te'):
        $xPERSON    = $_GET['_p'];
        $dGET       = $_GET['_d'];
        // $dTGL       = substr($dGET,8,2).'-'.substr($dGET,5,2).'-'.substr($dGET,0,4);
        $dTGL       = DMY_YMD($dGET);
        $cNOTE		= S_MSG('PE08','Catatan');
        $can_UPDATE = TRUST($cUSERCODE, 'PRS_PRESENT_UPD');
        $can_DELETE = TRUST($cUSERCODE, 'PRS_PRESENT_DEL');

        $qQUERY=OpenTable('People', "md5(PEOPLE_CODE)='$xPERSON' and APP_CODE='$cAPP_CODE' and DELETOR=''");
        if(SYS_ROWS($qQUERY)==0)    return;
        $a_PERSON=SYS_FETCH($qQUERY);
        $cPRSON_CODE = $a_PERSON['PEOPLE_CODE'];
        $cPRSON_NAME = DECODE($a_PERSON['PEOPLE_NAME']);
        $qOCCUP=OpenTable('PrsOccuption', "PRSON_CODE='$cPRSON_CODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
        if(SYS_ROWS($qOCCUP)==0) return;
        $aOCCUP = SYS_FETCH($qOCCUP);

        $qDT_ABS=OpenTable('RepAbsen', "PRSON_CODE='$cPRSON_CODE' and ABSN_DATE='$dTGL' and APP_CODE='$cUSERCODE'");
        if(SYS_ROWS($qDT_ABS)==0) {
            $ct_MSK=$ct_PLG=$ctLMSK=$ctLPLG='';
            $nJ_LBR=0;
            $qDT_MSK=OpenTable('ArrPresence', "PRESENT_CODE=0 and PEOPLE_CODE='$cPRSON_CODE' and DATE(PPL_PRESENT)='$dTGL' and APP_CODE='$cAPP_CODE'");
            if(SYS_ROWS($qDT_ABS)>0) {
                $aREC_MSK=SYS_FETCH($qDT_MSK);
                $ct_MSK=substr($aREC_MSK['PPL_PRESENT'], 11, 5);
            }
            $qDT_PLG=OpenTable('ArrPresence', "PRESENT_CODE=1 and PEOPLE_CODE='$cPRSON_CODE' and DATE(PPL_PRESENT)='$dTGL' and APP_CODE='$cAPP_CODE'");
            if(SYS_ROWS($qDT_ABS)>0) {
                $aREC_PLG=SYS_FETCH($qDT_PLG);
                $ct_PLG=substr($aREC_PLG['PPL_PRESENT'], 11, 5);
            }
            $qDT_LBR=OpenTable('PrsOvertime', "PRSON_CODE='$cPRSON_CODE' and DATE(OVT_START)='$dTGL' and APP_CODE='$cAPP_CODE'");
            if(SYS_ROWS($qDT_LBR)>0) {
                $aREC_LBR=SYS_FETCH($qDT_LBR);
                $ctLMSK=substr($aREC_LBR['OVT_START'], 11, 5);
                $ctLPLG=substr($aREC_LBR['OVT_END'], 11, 5);
                $nJ_LBR=floor($aREC_LBR['OVT_MINUTE']/60);
            }
        } else {
            $aREC_ABSEN=SYS_FETCH($qDT_ABS);
            $ct_MSK=$aREC_ABSEN['ABSN_MSK'];
            $ct_PLG=$aREC_ABSEN['ABSN_KLR'];
            $ctLMSK=$aREC_ABSEN['OVERTIME_IN'];
            $ctLPLG=$aREC_ABSEN['OVERTIME_OUT'];
            $nJ_LBR=$aREC_ABSEN['OVERTIME_QTY'];
        }

        $cSHIFT_CODE = Get_Personal_Schedule_Code($cPRSON_CODE, $dTGL);
        if ($cSHIFT_CODE=='') {
            $cSHIFT_CODE = Get_Group_Schedule_Code($aOCCUP['CUST_CODE'], $aOCCUP['KODE_LOKS'], $aOCCUP['JOB_CODE'], $dTGL);
        }
        if ($cSHIFT_CODE!='') {
            $qTZ = OpenTable('Timezone', "DAYL_CODE='$cSHIFT_CODE' and APP_CODE='$cUSERCODE'");
        }

        $nSERVER_TIME=7;		// server time
        $nCONVERSI_TIME=0;
        $qTIMEZONE	= OpenTable('Timezone', "A.DAYL_CODE='$cSHIFT_CODE' and A.APP_CODE='$cAPP_CODE'  and A.DELETOR=''");
        if (SYS_ROWS($qTIMEZONE)>0) {
            $aTIMEZONE	= SYS_FETCH($qTIMEZONE);
            $nCONVERSI_TIME = ($aTIMEZONE ? $aTIMEZONE['TIME_TO_UTC'] : 7 ) - $nSERVER_TIME;
        }
        if($nCONVERSI_TIME!=0) {
            if($ct_MSK!='') {
                $nct_MSK = (int) substr($ct_MSK,0,2) + $nCONVERSI_TIME;
                $ct_MSK = str_pad($nct_MSK , 2 , "0" , STR_PAD_LEFT).':'.substr($ct_MSK,3,2);
            }
            if($ct_PLG!='') {
                $nct_PLG = (int) substr($ct_PLG,0,2) + $nCONVERSI_TIME;
                $ct_PLG = str_pad($nct_PLG , 2 , "0" , STR_PAD_LEFT).':'.substr($ct_PLG,3,2);
            }
            if($ctLMSK!='') {
                $nctLMSK = (int) substr($ctLMSK,0,2) + $nCONVERSI_TIME;
                $ctLMSK = str_pad($nctLMSK , 2 , "0" , STR_PAD_LEFT).':'.substr($ctLMSK,3,2);
            }
            if($ctLPLG!='') {
                $nctLPLG = (int) substr($ctLPLG,0,2) + $nCONVERSI_TIME;
                $ctLPLG = str_pad($nctLPLG , 2 , "0" , STR_PAD_LEFT).':'.substr($ctLPLG,3,2);
            }
        }
        $cEDIT_ABSEN 	= S_MSG('PH93','Edit Absent');
        // die ($dGET);
        DEF_WINDOW($cEDIT_ABSEN);
            $cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
            $aACT = ($can_DELETE==1 ? ['<a href="?_a=del_absen&_p='. $cPRSON_CODE.'&_d='. $dGET. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
            TFORM($cEDIT_ABSEN, '?_a=update_abs&_p='.$cPRSON_CODE.'&_d='.$dGET, $aACT, $cHELP_FILE);
                TDIV();
                    LABEL([4,4,4,6], '700', $cKD_PERSON);
                    LABEL([5,5,5,6], '900', $cPRSON_CODE.' / '.$cPRSON_NAME, 'fix');
                    LABEL([4,4,4,6], '700', $cTANGGAL);
                    INP_DATE([2,2,3,6], '', 'UPD_ABSN_DATE', $dGET, '', '', '', 'fix');
                    LABEL([4,4,4,6], '700', $cMASUK);
                    INP_TIME([], '', 'UPD_MASUK', $ct_MSK, '', '', '', 'fix');
                    LABEL([4,4,4,6], '700', $cPULANG);
                    INP_TIME([], '', 'UPD_PULANG', $ct_PLG, '', '', '', 'fix');
                    LABEL([4,4,4,6], '700', $cOVT_IN);
                    INP_TIME([], '', 'UPD_LBR_MSK', $ctLMSK, '', '', '', 'fix');
                    LABEL([4,4,4,6], '700', $cOVT_OUT);
                    INP_TIME([], '', 'UPD_LBR_PLG', $ctLPLG, '', '', '', 'fix');
                    LABEL([4,4,4,4], '', $cJAM_LBR);
                    INPUT('number', [1,1,1,1], '900', 'UPD_JAM_LBR', $nJ_LBR, '', '', '', 0, '', 'Fix');
                TDIV();
                SAVE(($can_UPDATE ? S_MSG('F301','Save') : ''));
            eTFORM();
            include "scr_chat.php";
            require_once("js_framework.php");
        END_WINDOW();
        SYS_DB_CLOSE($DB2);	
    break;
        
    case 'tambah':
        $cPERSON = $_POST['ADD_PRSON_CODE'];
        if(empty($cPERSON)){
            MSG_INFO(S_MSG('PA6F','Kode Pegawai tidak boleh kosong'));
            return;
        }
        $dTGL = $_POST['ADD_ABSN_DATE'];
        $dDAY_DATE = DMY_YMD($dTGL);
        $qPLACE	= OpenTable('PrsOccuption', "PRSON_CODE='$cPERSON' and APP_CODE='$cAPP_CODE'  and DELETOR=''");
        if(SYS_ROWS($qPLACE)==0)    return;
        $aPLACE = SYS_FETCH($qPLACE); 
        $nSERVER_TIME=7;	$nCONVERSI_TIME=0;
        $cSHIFT_CODE = Get_Personal_Schedule_Code($cPERSON, $dDAY_DATE);
        if ($cSHIFT_CODE=='') {
            $cSHIFT_CODE = Get_Group_Schedule_Code($aPLACE['CUST_CODE'], $aPLACE['KODE_LOKS'], $aPLACE['JOB_CODE'], $dDAY_DATE);
        }
        if ($cSHIFT_CODE!='') {
            $qTZ = OpenTable('Timezone', "DAYL_CODE='$cSHIFT_CODE' and APP_CODE='$cUSERCODE'");
        }
        $qTIMEZONE	= OpenTable('Timezone', "A.DAYL_CODE='$cSHIFT_CODE' and A.APP_CODE='$cAPP_CODE'  and A.DELETOR=''");
        if (SYS_ROWS($qTIMEZONE)>0) {
            $aTIMEZONE	= SYS_FETCH($qTIMEZONE);
            $nCONVERSI_TIME = ($aTIMEZONE ? $aTIMEZONE['TIME_TO_UTC'] : 7 ) - $nSERVER_TIME;
        }

        $cJAM_MSK = $_POST['ADD_MASUK'];
        $cJAM_PLG = $_POST['ADD_PULANG'];
        $cLBR_MSK = $_POST['ADD_LBR_MSK'];
        $cLBR_PLG = $_POST['ADD_LBR_PLG'];
        $nJAM_LBR = $_POST['ADD_JAM_LBR'];
        if(!$nJAM_LBR)  $nJAM_LBR=0;
        if($cJAM_MSK!='')  {
            $nJAM_MSK = (int) substr($cJAM_MSK,0,2) - $nCONVERSI_TIME;
            $cJAM_MSK = str_pad($nJAM_MSK , 2 , "0" , STR_PAD_LEFT).':'.substr($cJAM_MSK,3,2);
        } 
        if($cJAM_PLG!='')  {
            $nJAM_MSK = (int) substr($cJAM_PLG,0,2) - $nCONVERSI_TIME;
            $cJAM_PLG = str_pad($nJAM_MSK , 2 , "0" , STR_PAD_LEFT).':'.substr($cJAM_PLG,3,2);
        } 
        if($cLBR_MSK!='')  {
            $nJAM_MSK = (int) substr($cLBR_MSK,0,2) - $nCONVERSI_TIME;
            $cLBR_MSK = str_pad($nJAM_MSK , 2 , "0" , STR_PAD_LEFT).':'.substr($cLBR_MSK,3,2);
        } 
        if($cLBR_PLG!='')  {
            $nJAM_MSK = (int) substr($cLBR_PLG,0,2) - $nCONVERSI_TIME;
            $cLBR_PLG = str_pad($nJAM_MSK , 2 , "0" , STR_PAD_LEFT).':'.substr($cLBR_PLG,3,2);
        } 
        $dt_MSK=$dt_PLG=$dtLMSK=$dtLPLG='';
        $cMSK = $dDAY_DATE.' '.$cJAM_MSK.':00';
        $cPLG = $dDAY_DATE.' '.$cJAM_PLG.':00';
        $cLMSK= $dDAY_DATE.' '.$cLBR_MSK.':00';
        $cLPLG= $dDAY_DATE.' '.$cLBR_PLG.':00';
        if(strlen($cMSK)==19)    $dt_MSK = $cMSK;
        if(strlen($cPLG)==19)    $dt_PLG = $cPLG;
        if(strlen($cLMSK)==19)   $dtLMSK = $cLMSK;
        if(strlen($cLPLG)==19)   $dtLPLG = $cLPLG;

        if($dt_MSK>'') {
            RecCreate('Presence', ['PEOPLE_CODE', 'PPL_PRESENT', 'PRESENT_CODE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY', 'UPD_DATE'],
                [$cPERSON, $dt_MSK, 0, $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]);
        }
        if($dt_PLG>'') {
            RecCreate('Presence', ['PEOPLE_CODE', 'PPL_PRESENT', 'PRESENT_CODE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY', 'UPD_DATE'],
                [$cPERSON, $dt_PLG, 1, $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]);
        }
        if($dtLMSK>'' || $nJAM_LBR>0) {
            $qOVERTIME	= OpenTable('PrsOvertime', "PRSON_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and date(OVT_START)='$dDAY_DATE'");
            if(SYS_ROWS($qOVERTIME)==0) {
                $dtLPLG = ($dtLPLG>'' ? $dtLPLG : '0000-00-00 00:00:00');
                RecCreate('PrsOvertime', ['PRSON_CODE', 'OVT_START', 'OVT_END', 'OVT_MINUTE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY', 'UPD_DATE'],
                    [$cPERSON, $dtLMSK, $dtLPLG, $nJAM_LBR*60, $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]);
            } else {
                RecUpdate('PrsOvertime', ['OVT_START', 'OVT_END', 'OVT_MINUTE'], [$dtLMSK, $dtLPLG, $nJAM_LBR*60], 
                    "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON' and date(OVT_START)='$dtLMSK'");
            }
        }
        echo "<script> window.history.back();	</script>";
    //	header('location:prs_rp_absen.php');
        header('location: '.$_SERVER['PHP_SELF']);
    break;

    case 'update_abs':
        $cPERSON = $_GET['_p'];
        $dORGN = $_GET['_d'];
        $dTGL = $_POST['UPD_ABSN_DATE'];

        $dYMD_DATE = substr($dORGN,6,4).'-'.substr($dORGN,3,2).'-'.substr($dORGN,0,2);
        $dDAY_DATE = $dORGN;
        $qPLACE	= OpenTable('PrsOccuption', "PRSON_CODE='$cPERSON' and APP_CODE='$cAPP_CODE'  and DELETOR=''");
        if(SYS_ROWS($qPLACE)==0) {
            echo "<script> window.history.back();	</script>";
            return;
        }
        $aPLACE = SYS_FETCH($qPLACE); 
        $nSERVER_TIME=7;	$nCONVERSI_TIME=0;
        $cSHIFT_CODE = Get_Personal_Schedule_Code($cPERSON, $dYMD_DATE);
        if ($cSHIFT_CODE=='') {
            $cSHIFT_CODE = Get_Group_Schedule_Code($aPLACE['CUST_CODE'], $aPLACE['KODE_LOKS'], $aPLACE['JOB_CODE'], $dYMD_DATE);
        }
        if ($cSHIFT_CODE!='') {
            $qTZ = OpenTable('Timezone', "DAYL_CODE='$cSHIFT_CODE' and APP_CODE='$cUSERCODE'");
        }
        $qTIMEZONE	= OpenTable('Timezone', "A.DAYL_CODE='$cSHIFT_CODE' and A.APP_CODE='$cAPP_CODE'  and A.DELETOR=''");
        if (SYS_ROWS($qTIMEZONE)>0) {
            $aTIMEZONE	= SYS_FETCH($qTIMEZONE);
            $nCONVERSI_TIME = ($aTIMEZONE ? $aTIMEZONE['TIME_TO_UTC'] : 7 ) - $nSERVER_TIME;
        }

        $cJAM_MSK = $_POST['UPD_MASUK'];
        $cJAM_PLG = $_POST['UPD_PULANG'];
        $cLBR_MSK = $_POST['UPD_LBR_MSK'];
        $cLBR_PLG = $_POST['UPD_LBR_PLG'];
        $nJAM_LBR = $_POST['UPD_JAM_LBR'];

        if($cJAM_MSK!='')  {
            $nJAM_MSK = (int) substr($cJAM_MSK,0,2) - $nCONVERSI_TIME;
            $cJAM_MSK = str_pad($nJAM_MSK , 2 , "0" , STR_PAD_LEFT).':'.substr($cJAM_MSK,3,2);
        } 
        if($cJAM_PLG!='')  {
            $nJAM_MSK = (int) substr($cJAM_PLG,0,2) - $nCONVERSI_TIME;
            $cJAM_PLG = str_pad($nJAM_MSK , 2 , "0" , STR_PAD_LEFT).':'.substr($cJAM_PLG,3,2);
        } 
        if($cLBR_MSK!='')  {
            $nJAM_MSK = (int) substr($cLBR_MSK,0,2) - $nCONVERSI_TIME;
            $cLBR_MSK = str_pad($nJAM_MSK , 2 , "0" , STR_PAD_LEFT).':'.substr($cLBR_MSK,3,2);
        } 
        if($cLBR_PLG!='')  {
            $nJAM_MSK = (int) substr($cLBR_PLG,0,2) - $nCONVERSI_TIME;
            $cLBR_PLG = str_pad($nJAM_MSK , 2 , "0" , STR_PAD_LEFT).':'.substr($cLBR_PLG,3,2);
        } 
        $dt_MSK=$dt_PLG=$dtLMSK=$dtLPLG='';
        $cMSK = $dYMD_DATE.' '.$cJAM_MSK.':00';
        $cPLG = $dYMD_DATE.' '.$cJAM_PLG.':00';
        $cLMSK= $dYMD_DATE.' '.$cLBR_MSK.':00';
        $cLPLG= $dYMD_DATE.' '.$cLBR_PLG.':00';
        if(strlen($cMSK)==19)    $dt_MSK = $cMSK;
        if(strlen($cPLG)==19)    $dt_PLG = $cPLG;
        if(strlen($cLMSK)==19)   $dtLMSK = $cLMSK;
        if(strlen($cLPLG)==19)   $dtLPLG = $cLPLG;

        if($dt_MSK>'') {
            $qPRESENCE	= OpenTable('Presence', "PEOPLE_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and date(PPL_PRESENT)='$dYMD_DATE' and PRESENT_CODE=0");
            if(SYS_ROWS($qPRESENCE)==0) {
                RecCreate('Presence', ['PEOPLE_CODE', 'PPL_PRESENT', 'PRESENT_CODE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY', 'UPD_DATE'],
                    [$cPERSON, $dt_MSK, 0, $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]);
            } else {
                RecUpdate('Presence', ['PPL_PRESENT', 'UP_DATE', 'UPD_DATE'], [$dt_MSK, $cUSERCODE, date("Y-m-d H:i:s")],
                    "PEOPLE_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and date(PPL_PRESENT)='$dYMD_DATE' and PRESENT_CODE=0");
            }
        }
        if($dt_PLG>'') {
            $qPRESENCE	= OpenTable('Presence', "PEOPLE_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and date(PPL_PRESENT)='$dYMD_DATE' and PRESENT_CODE=1");
            if(SYS_ROWS($qPRESENCE)==0) {
                RecCreate('Presence', ['PEOPLE_CODE', 'PPL_PRESENT', 'PRESENT_CODE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY', 'UPD_DATE'],
                    [$cPERSON, $dt_PLG, 1, $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]);
            } else {
                RecUpdate('Presence', ['PPL_PRESENT', 'UP_DATE', 'UPD_DATE'], [$dt_PLG, $cUSERCODE, date("Y-m-d H:i:s")],
                    "PEOPLE_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and date(PPL_PRESENT)='$dYMD_DATE' and PRESENT_CODE=1");
            }
        }

        if($dtLMSK>'' || $nJAM_LBR>0) {
            $qOVERTIME	= OpenTable('PrsOvertime', "PRSON_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and date(OVT_START)='$dYMD_DATE'");
            if(SYS_ROWS($qOVERTIME)==0) {
                RecCreate('PrsOvertime', ['PRSON_CODE', 'OVT_START', 'OVT_END', 'OVT_MINUTE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY', 'UPD_DATE'],
                    [$cPERSON, $dtLMSK, $dtLPLG, $nJAM_LBR*60, $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]);
            } else {
                $nOVT=intval($nJAM_LBR)*60;
                RecUpdate('PrsOvertime', ['OVT_START', 'OVT_END', 'OVT_MINUTE'], [$dtLMSK, $dtLPLG, $nOVT], 
                    "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON' and date(OVT_START)='$dORGN'");
            }
        }
        echo '<script language="javascript"> window.history.go(-2) </script>';
    break;

    case 'del_absen':
        $NOW = date("Y-m-d H:i:s");
        $cPERSON = $_GET['_p'];
        $dDATE=$_GET['_d'];
    //	RecUpdate('TrAbsent', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, $NOW], "ABSEN_REC=".$NMR_URUT); 
        header('location:prs_rp_absen.php');
	break;
}
?>

