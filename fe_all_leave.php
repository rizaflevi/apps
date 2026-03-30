<?php
//	fe_all_leave.php //

session_start();
    include "sysfunction.php";
    $cAPP_CODE  = $_SESSION['data_FILTER_CODE'] = $_GET['_app'];
    $cPRS_CODE  = $_SESSION['gUSERCODE'] = $_GET['_prs'];

	$sPERIOD1=date("Y-m-d");
	if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];
	$cHEADER 	= S_MSG('TH93','Cuti Bersama');
    $dTODAY = date('Y-m-d');
    $dLASTDAY = date('Y-m-d', strtotime('-15 days'));

	$cTANGGAL 	= S_MSG('TH03','Tanggal');
	$cSAMPAI    = S_MSG('TH04','Sampai');
	$cKET	    = S_MSG('TH46','Ket.');
	$cJML       = S_MSG('H037','Lembur Masuk');

    require_once("fe_topbar.php");  echo '<br><br><br>';
	require_once("cl_header.php");
    echo '<body>';
    echo '<script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>';
    echo '		<div class="page-container row-fluid">    ';
    TDIV();
        TABLE('', '', 0, '*');
            THEAD([$cTANGGAL, $cSAMPAI, $cKET, $cJML], '', [], '*');
            echo '<tbody>';
            $dBEG   = new DateTime($dTODAY);
            $cPERIOD1=$dBEG->format('Y-m-d');

            $qQUERY=OpenTable('TbAllLeave', "year(START_DATE)=".substr($sPERIOD1,0,4). " and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
            while($aREC_LEAVE=SYS_FETCH($qQUERY)) {
                $date1 = strtotime($aREC_LEAVE['START_DATE']);
                $date2 = strtotime($aREC_LEAVE['FINISH_DATE']);
                $diff = $date2 - $date1;
                $nDAYS = floor($diff / (60 * 60 * 24)) + 1;
                $aCOL=[date("d-M-Y", strtotime($aREC_LEAVE['START_DATE'])), date("d-M-Y", strtotime($aREC_LEAVE['FINISH_DATE'])), $aREC_LEAVE['LEAVE_DESC'], $nDAYS];
                TDETAIL($aCOL, [], '*', []);
            }

            echo '</tbody>';
        eTABLE();
        require_once("js_framework.php");
        SAVE();
        // echo '<p>&nbsp&nbsp<button type="button" class="btn btn-primary" onclick=window.history.go(-1)>Close</button></p>';
    eTDIV();
    END_WINDOW();
    // APP_LOG_ADD($cHEADER, 'View');
?>

