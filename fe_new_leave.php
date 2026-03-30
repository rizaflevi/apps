<?php
session_start();
$_SESSION['sLANG']		= '1';
include "sysfunction.php";
$cAPP_CODE  = $_SESSION['data_FILTER_CODE'] = $_GET['_app'];
$cHEADER    = 'Permohonan Cuti';
$cACTION    = (isset($_GET['_a']) ? $_GET['_a'] : '');
$cDEVICE    = (isset($_GET['_dev']) ? $_GET['_dev'] : '');
$cPRS_CODE  = (isset($_GET['_prs']) ? $_GET['_prs'] : '');

$cAPP_CODE=$_GET['_app'];
switch($cACTION){
	default:
        FE_WINDOW($cHEADER);
            FE_FORM('', '?_a=SAVE&_dev='.$cDEVICE.'&_prs='.$cPRS_CODE.'&_app='.$cAPP_CODE);
                TDIV();
                    LABEL([3,3,3,6], '700', S_MSG('PE17','Tanggal mulai cuti'));
                    // INPUT_DATE([2,2,3,5], '900', 'ADD_START_DATE', date('d/m/Y'), '', '', '', 0, '', 'fix');
                    echo '<input type="text" id="date1" class="col-xs-5 form-label-900" value="'.date('d/M/Y').'" readonly="readonly" />';
                    CLEAR_FIX();
                    
                    LABEL([3,3,3,6], '700', S_MSG('RS14','s/d'));
                    // echo '<span>    sampai dengan    </span>';
                    echo '<input type="text" id="date2" class="col-xs-5 form-label-900" value="'.date('d/M/Y').'" readonly="readonly" /><div class="clearfix"></div>';
                    // INPUT_DATE([2,2,3,5], '900', 'ADD_FINISH_DATE', date('d/m/Y'), '', '', '', 0, '', 'fix', '', 'countDays()');
                    LABEL([3,3,3,6], '700', 'Jumlah hari cuti');
                    // INPUT('number', [1,1,2,3], '900', 'ADD_HARICUTI', 1, '', '', '', 0, '', 'fix');
                    echo '<input type="text" id="jumlahcuti" name="ADD_HARICUTI" class="form-label-900 col-xs-2" value="1" /><div class="clearfix"></div>';
                    LABEL([3,3,3,5], '700', 'Nama Pengganti');
                    INPUT('text', [6,6,6,7], '900', 'ADD_NM_PGS', '', '', '', '', 0, '', 'fix');
                    LABEL([3,3,3,5], '700', 'HP Pengganti');
                    INPUT('text', [6,6,6,7], '900', 'ADD_HP_PGS', '', '', '', '', 0, '', 'fix');
                    LABEL([3,3,3,5], '700', S_MSG('PE07','Alasan Cuti'));
                    INPUT('text', [6,6,6,7], '900', 'ADD_REASON', '', '', '', '', 0, '', 'fix');
                    LABEL([3,3,3,5], '700', S_MSG('PE08','Catatan'));
                    INPUT('text', [6,6,6,7], '900', 'ADD_NOTE', '', '', '', '', 0, '', 'fix');
                    SAVE(S_MSG('F301','Save'));
                    echo '<br><br>';
                eTDIV();
                TDIV();
                    $nBERSAMA = 0;
                    $qPERSON=OpenTable('PrsOccuption', "PRSON_CODE='$cPRS_CODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
                    if($aPERSON=SYS_FETCH($qPERSON)) {
                        $cFILT_DATA = "APP_CODE='" . $cAPP_CODE ."' and REC_ID not in ( select DEL_ID from logs_delete) and ";
                        $cFILT_DATA.= "CUST_CODE='$aPERSON[CUST_CODE]' or CUST_CODE='' and ";
                        $cFILT_DATA.= "LOC_CODE='$aPERSON[KODE_LOKS]' or LOC_CODE='' and ";
                        $cFILT_DATA.= "JOB_CODE='$aPERSON[JOB_CODE]' or JOB_CODE=''";
                        $qSCOPE=OpenTable('PrsLeaveScope', $cFILT_DATA);
                        if($aSCOPE=SYS_FETCH($qSCOPE)) {
                            $nBERSAMA = $aSCOPE['INC_LEAVE'];
                        }
                    }
                    $nALL_LEAVE=0;
                    if($nBERSAMA > 0) {
                        $qQUERY=OpenTable('TbAllLeave', "year(START_DATE)=".date('Y'). " and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
                        while($aALL_LEAVE=SYS_FETCH($qQUERY)) {
                            $nDIFF=strtotime($aALL_LEAVE['FINISH_DATE']) - strtotime($aALL_LEAVE['START_DATE']);
                            $nDAYS = floor($nDIFF / (60 * 60 * 24)) + 1;
                            $nALL_LEAVE+=$nDAYS;
                        }
                    }
                    $nCUTI=0;
                    $qQUERY=OpenTable('TrLeave', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and A.PRSON_CODE='$cPRS_CODE' AND YEAR(A.LEV_DATE1)=".date('Y')." and LEAVE_STTS=0");
                    while($a_PRS_CUTI=SYS_FETCH($qQUERY)) {
                        $nCUTI+=$a_PRS_CUTI['DURATION'];
                    }
                    echo '<br>';
                    LABEL([9,9,9,9], '900', 'Sisa Cuti '.(string)date('Y'));
                    LABEL([3,3,3,3], '900', (string)12-$nALL_LEAVE-$nCUTI);
                    if($nALL_LEAVE) {
                        LABEL([9,9,9,9], '900', 'Cuti Bersama Thn '.(string)date('Y'));
                        LABEL([3,3,3,3], '900', (string)$nALL_LEAVE);
                    }
                    if($nCUTI>0) {
                        LABEL([9,9,9,9], '900', 'Cuti yang telah diambil ');
                        LABEL([3,3,3,3], '900', (string)$nCUTI, '');
                        TABLE('example');
                        THEAD(['Mulai', 'Sampai', 'Jumlah', 'Catatan']);
                        $qQUERY=OpenTable('TrLeave', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and A.PRSON_CODE='$cPRS_CODE' AND YEAR(A.LEV_DATE1)=".date('Y')." and LEAVE_STTS=0");
                        while($a_PRS_CUTI=SYS_FETCH($qQUERY)) {
                            $aCOL=[(string)$a_PRS_CUTI['LEAVE_NO']. date("d-M", strtotime($a_PRS_CUTI['LEV_DATE1'])), date("d-M", strtotime($a_PRS_CUTI['LEV_DATE2'])), (string)$a_PRS_CUTI['DURATION'], $a_PRS_CUTI['LEAVE_NOTE']];
                            TDETAIL($aCOL, [0,0,2,0]);
                            CLEAR_FIX();
                        }
                    }
                eTDIV();
            eTFORM();
            echo '<script>
                function countDays(){
            
                var start_date = new Date(document.getElementById("ADD_START_DATE").value);
                var end_date = new Date(document.getElementById("ADD_FINISH_DATE").value);
                var time_difference = end_date.getTime() - start_date.getTime();
                var days_difference = time_difference / (1000*3600*24);
                document.getElementById("ADD_HARICUTI").value = days_difference;
                }
        
            </script>
            <script>
                $(document).ready(function () {
        
                    $(function () {
                        $("#date1").
                        datepicker({
                            dateFormat: "dd/mm/yy",
                            maxDate: "+40d", //Permohonan cuti diminta maksimal 40 hari sebelum cutinya mulai
                            minDate: "-0d" //Hari itu juga bisa memohon cuti
                        });
                        $("#date2").
                        datepicker({
                            dateFormat: "dd/mm/yy",
                            minDate: "+1d"
                        });
                        //});
                        $("#date1").change(function() {
                            date = $(this).datepicker("getDate");
                            const maxDate = new Date(date.getTime());
                            maxDate.setDate(maxDate.getDate() + 5); //MAKSIMAL 5+1 HARI CUTI BERTURUT-TURUT
                            $("#date2").datepicker("option", {minDate: date, maxDate: maxDate}).
                                    datepicker("setDate", date);
                        }); 
                        $("#date2").change(function() {
                            date1 = $("#date1").datepicker("getDate");
                            date2 = $(this).datepicker("getDate");
                            const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                            const firstDate = new Date(date1.getTime());
                            const secondDate = new Date(date2.getTime());

                            const diffDays = Math.round(Math.abs((firstDate - secondDate) / oneDay));
                            document.getElementById("jumlahcuti").value = diffDays+1;
                            // console.log(diffdays);
                        }); 
                        
//source code https://www.geeksforgeeks.org/jquery-ui-date-picker/
                    });
                }) 
            </script>';
        eFE_WINDOW();
        break;

    case "SAVE":
        $cREASON = ENCODE($_POST['ADD_REASON']);
        $cNOTES = ENCODE($_POST['ADD_NOTE']);
        $cNM_PGS = ENCODE($_POST['ADD_NM_PGS']);
        $cHP_PGS = ENCODE($_POST['ADD_HP_PGS']);
        $cPRS_CODE = $_GET['_prs'];
        if($cREASON==''){
            MSG_INFO('Alasan masih kosong');
            return;
        }
		$dLEAVE_ST = $_POST['ADD_START_DATE'];		// 'dd/mm/yyyy'
		$dLEAVE_FN = $_POST['ADD_FINISH_DATE'];		// 'dd/mm/yyyy'
		if (!$dLEAVE_ST || !$dLEAVE_FN) {
			MSG_INFO('Tanggal cuti masih kosong');
			return;
		}
		$nHARI = $_POST['ADD_HARICUTI'];
        // if($nHARI>)

        $qQUERY=OpenTable('TrLeave', "A.APP_CODE='$cAPP_CODE'", '', 'LEAVE_NO desc limit 1');
		$rLEAVE = SYS_FETCH($qQUERY);
		$nLAST = $rLEAVE['LEAVE_NO']+1;
		RecCreate('TrLeave', ['LEAVE_NO', 'LEV_DATE1', 'LEV_DATE2', 'DURATION', 'PRSON_CODE', 'LEAVE_STTS', 'LEAVE_RSON', 'LEAVE_NOTE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'],
			[$nLAST, $dLEAVE_ST, $dLEAVE_FN, $_POST['ADD_HARICUTI'], $cPRS_CODE, 3, $cREASON, $cNOTES, $cAPP_CODE, 'self', date("Y-m-d H:i:s")]);
		if ($cNM_PGS || $cHP_PGS) {
            RecCreate('TrCLeave', ['LEAVE_NO', 'CHANGER_NAME', 'CHANGER_PHN', 'APP_CODE', 'ENTRY', 'REC_ID'],
                [$nLAST, $cNM_PGS, $cHP_PGS, $cAPP_CODE, 'self', NowMSecs()]);
        }

		header('location:rainbow_ext.php?q='.$cDEVICE);
        break;
}
?>

