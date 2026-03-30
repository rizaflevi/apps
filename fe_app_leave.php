<?php
/*  fe_app_leave.php
    fasilitas atasan utk menyetujui cuti
*/

$_SESSION['cHOST_DB2'] = 'riza_db';
$_SESSION['gUSERCODE'] = 'guest';
$_SESSION['sLANG']		= '1';
include "sysfunction.php";
$cAPP_CODE  = $_SESSION['data_FILTER_CODE'] = $_GET['_app'];
$cHEADER    = 'Persetujuan Cuti';
$cACTION= (isset($_GET['_a']) ? $_GET['_a'] : '');
$cDEVICE = (isset($_GET['_dev']) ? $_GET['_dev'] : '');
$cPRS_CODE = (isset($_GET['_prs']) ? $_GET['_prs'] : '');

$cAPP_CODE=$_GET['_app'];
$qPEOPLE=OpenTable('People', "PEOPLE_CODE='$cPRS_CODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
if(SYS_ROWS($qPEOPLE)==0)    {
    MSG_INFO('Employee not found !');
    return;
}
$a_PERSON=SYS_FETCH($qPEOPLE);
$cPRSON_NAME = DECODE($a_PERSON['PEOPLE_NAME']);

switch($cACTION){
	default:
        APP_LOG_ADD($cHEADER, 'View');
        $qSCOPE=OpenTable('PrsLeaveScope', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and PERSON_CODE='$cPRS_CODE'");
        FE_WINDOW($cHEADER);
            echo '<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 uprofile-name">
                <h4 style="background-color:#8EC6CE";>'. $a_PERSON['PEOPLE_NAME'].'</h4>
            </div>';
            TDIV();
                TABLE('', '', 0, '*');
                    THEAD(['Pemohon Cuti'], '', [], '*');
                    echo '<tbody>';
                        while($aREC_SCOPE=SYS_FETCH($qSCOPE)) {
                            $cFILT_DATA = "A.APP_CODE='" . $cAPP_CODE ."' and A.DELETOR='' and LEAVE_STTS=".$aREC_SCOPE['IN_STATUS']." and YEAR(A.LEV_DATE1)=".date('Y');
                            $cFILT_DATA.= ($aREC_SCOPE['CUST_CODE'] ? " and F.CUST_CODE='".$aREC_SCOPE['CUST_CODE']."'" : '');
                            $cFILT_DATA.= ($aREC_SCOPE['LOC_CODE'] ? " and LOCS.LOKS_CODE='".$aREC_SCOPE['LOC_CODE']."'" : '');
                            $cFILT_DATA.= ($aREC_SCOPE['JOB_CODE'] ? " and JOB.JOB_CODE='".$aREC_SCOPE['JOB_CODE']."'" : '');
                            $qQUERY=OpenTable('TrLeave', $cFILT_DATA);
                            while($aDT_LEAVE=SYS_FETCH($qQUERY)) {
                                echo '<td style="font-family:courier;font-size:200%;background-color:powderblue;"><a href="?_a=VIEW&_i='.$aDT_LEAVE['LEAVE_REC'].'&_dev='.$cDEVICE.'&_app='.$cAPP_CODE.'&_prs='.$aDT_LEAVE['PRSON_CODE'].'&_scope='.$aREC_SCOPE['REC_ID'].'">'.DECODE($aDT_LEAVE['PRSON_NAME'])."</a></td>";
                            }
                        }
                        echo '</tbody>';
                eTABLE();
                SAVE();
            eTDIV();
        eFE_WINDOW();
        break;
    case "VIEW":
            $nREC_ID=(isset($_GET['_i']) ? $_GET['_i'] : '');
            $cSCOPE_ID=(isset($_GET['_scope']) ? $_GET['_scope'] : '');
            $qLEAVE=OpenTable('TrLeave', "A.LEAVE_REC='$nREC_ID' and A.DELETOR='' and LEAVE_STTS>0");
            if($aREC_LEAVE=SYS_FETCH($qLEAVE)) {
                $qCLEAVE=OpenTable('TrCLeave', "LEAVE_NO='$aREC_LEAVE[LEAVE_NO]'");
                $cCHR_NAME=$cCHR_PHONE='';
                if($aCHANGER=SYS_FETCH($qCLEAVE)) {
                    $cCHR_NAME=$aCHANGER['CHANGER_NAME'];
                    $cCHR_PHONE=$aCHANGER['CHANGER_PHN'];
                }
                FE_WINDOW($cHEADER);
                    FE_FORM('Permohonan Cuti', '?_a=SAVE&_r='.$nREC_ID.'&_dev='.$cDEVICE.'&_prs='.$cPRS_CODE.'&_app='.$cAPP_CODE.'&_s='.$cSCOPE_ID);
                        TDIV();
                            LABEL([12,12,12,12], '900', $cPRSON_NAME, 'fix', 'center');
                            echo '<br>';
                            LABEL([3,3,3,5], '700', S_MSG('PE17','Tanggal mulai cuti'));
                            INPUT_DATE([2,2,3,5], '900', 'ADD_START_DATE', $aREC_LEAVE['LEV_DATE1'], '', '', '', 0, '', 'fix');
                            LABEL([3,3,3,5], '700', S_MSG('RS14','s/d'));
                            INPUT_DATE([2,2,3,5], '900', 'ADD_FINISH_DATE', $aREC_LEAVE['LEV_DATE2'], '', '', '', 0, '', 'fix');
                            LABEL([3,3,3,5], '700', 'Jml hr cuti');
                            INPUT('number', [1,1,2,3], '900', 'ADD_HARICUTI', $aREC_LEAVE['DURATION'], '', '', '', 0, '', 'fix');
                            LABEL([3,3,3,5], '700', 'Nama Pengganti');
                            INPUT('text', [6,6,6,7], '900', 'ADD_NM_PGS', $cCHR_NAME, '', '', '', 0, '', 'fix');
                            LABEL([3,3,3,5], '700', 'HP Pengganti');
                            INPUT('text', [6,6,6,7], '900', 'ADD_HP_PGS', $cCHR_PHONE, '', '', '', 0, '', 'fix');
                            LABEL([3,3,3,5], '700', S_MSG('PE07','Alasan Cuti'));
                            INPUT('text', [6,6,6,7], '900', 'ADD_REASON', $aREC_LEAVE['LEAVE_RSON'], '', '', '', 0, '', 'fix');
                            LABEL([3,3,3,5], '700', S_MSG('PE08','Catatan'));
                            INPUT('text', [6,6,6,7], '900', 'ADD_NOTE', $aREC_LEAVE['LEAVE_NOTE'], '', '', '', 0, '', 'fix');
                            SAVE('Approve');
                            echo '&nbsp&nbsp<input type="button" class="btn" value="Hapus" onclick=self.history.back()>';
                            eTDIV();
                        echo '<br><br>';
                        $nALL_LEAVE=0;
                        $qQUERY=OpenTable('TbAllLeave', "year(START_DATE)=".date('Y'). " and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
                        while($aALL_LEAVE=SYS_FETCH($qQUERY)) {
                            $nDIFF=strtotime($aALL_LEAVE['FINISH_DATE']) - strtotime($aALL_LEAVE['START_DATE']);
                            $nDAYS = floor($nDIFF / (60 * 60 * 24)) + 1;
                            $nALL_LEAVE+=$nDAYS;
                        }
                    eTFORM();
                eFE_WINDOW();
            } else {
                MSG_INFO('Data Cuti tidak di temukan!');
            }
            break;
    case "SAVE":
        $nREC_ID=$_GET['_r'];
        $cSCOPE_ID=$_GET['_s'];
        $qSCOPE=OpenTable('PrsLeaveScope', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and REC_ID='$cSCOPE_ID'");
        if($aSCOPE=SYS_FETCH($qSCOPE)) {
    		RecUpdate('TrLeave', ['LEAVE_STTS'], [$aSCOPE['OUT_STATUS']], "LEAVE_REC=".$nREC_ID); 
        }
        break;
}