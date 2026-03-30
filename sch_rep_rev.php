<?php
//	sch_rep_rev.php //  lap. pendapatan => SCH_REP_REV
//	TODO : filter data

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER 	= S_MSG('RS51','Laporan Pendapatan');
	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Laporan Pendapatan');
	$cHELP_FILE 	= 'Doc/Laporan - Pendapatan.pdf';
	$cTGL1		= S_MSG('RS02','Tanggal');
	$cTGL2		= S_MSG('RS14','s/d');
	$cACTION 	= '';
	if (isset($_GET['action']))	$cACTION = $_GET['action'];

	$dPERIOD1=$dPERIOD2=date("d/m/Y");
	if (isset($_GET['_d1'])) $dPERIOD1 = $_GET['_d1'];
	if (isset($_GET['_d2'])) $dPERIOD2 = $_GET['_d2'];

    $aREV=[];
    $qREV_COL=OpenTable('SchRevCol', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and NON_VALUE=0", '', '');
    while($aREV_COL=SYS_FETCH($qREV_COL)) {
        array_push($aREV, $aREV_COL['REV_COLUMN']);
    }
	$cSEKOLAH 	= (isset($_GET['_s']) ? $_GET['_s'] : '');
	$cFILTER	= "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and A.REV_DATE>='".DMY_YMD($dPERIOD1)."' and A.REV_DATE<='".DMY_YMD($dPERIOD2)."'";
	if ($cSEKOLAH>'') $cFILTER.=" and CG.KODE_GRP='$cSEKOLAH'";

	$qREV_HDR=OpenTable('SchRepAR', $cFILTER);

	DEF_WINDOW($cHEADER, 'collapse');
	TFORM($cHEADER, '', [], $cHELP_FILE, '*');
        TDIV();
			LABEL([1,1,1,4], '700', $cTGL1);
			INP_DATE([2,2,2,6], '900', '', $dPERIOD1, '', '', '', '', '', "FILT_REV(this.value, '$dPERIOD2', '$cSEKOLAH')");
			LABEL([1,1,1,4], '700', $cTGL2, '', 'right');
			INP_DATE([2,2,2,6], '900', '', $dPERIOD2, '', '', '', '', '', "FILT_REV('$dPERIOD1', this.value, '$cSEKOLAH')");
            $qSCHOOL=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
            LABEL([1,2,2,4], '700', 'Sekolah', '', 'right');
            SELECT([3,3,3,6], 'SCHOOL', "FILT_REV('$dPERIOD1', '$dPERIOD2', this.value)");
                echo "<option value=''> All </option>";
                while($aSCHOOL=SYS_FETCH($qSCHOOL)){
                    if($aSCHOOL['KODE_GRP']==$cSEKOLAH){
                        echo "<option value='$aSCHOOL[KODE_GRP]' selected='$cSEKOLAH' >$aSCHOOL[NAMA_GRP]</option>";
                    } else {
                        echo "<option value='$aSCHOOL[KODE_GRP]'  >$aSCHOOL[NAMA_GRP]</option>";
                    }
                }
                echo '</select>';
        
            $cICON = 'fa fa-money';
            TABLE('example');
                $aHEAD = [S_MSG('RT71','No. Faktur'), S_MSG('RS02','Tanggal'), S_MSG('RS03','Jt.Tempo'), S_MSG('RS04','Pelanggan'), S_MSG('RT75','Nama'), S_MSG('RS08','Jumlah'), S_MSG('TS22','Sekolah'), S_MSG('TA02','Nama')];
                $aLIGN = [0,0,0,0,0,1,0,0];
                THEAD($aHEAD, '', $aLIGN);
                echo '<tbody>';
                    $nT_JUAL = $nT_BAYAR = $nT_SISA = $nT_JML = 0;	
                    while($aREC_JUAL1=SYS_FETCH($qREV_HDR)) {
                        $nBAYAR = 0;
                        $qREC_DTL=OpenTable('SchRevDtl', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and A.REV_HDR_ID='$aREC_JUAL1[REV_ID]'");
                        while($aREC_DETAIL=SYS_FETCH($qREC_DTL)) {
                            $nBAYAR += $aREC_DETAIL['REV_VALUE'];
                        }

                        $nJUMLAH = $aREC_JUAL1['REV_VALUE'];
                        $nT_JUAL += $nJUMLAH;
                        $nT_BAYAR += $nBAYAR;
                        $aDTL = [$aREC_JUAL1['REV_ID'], date("d-M-Y", strtotime($aREC_JUAL1['REV_DATE'])), 
                            date("d-M-Y", strtotime($aREC_JUAL1['REV_DUE'])), $aREC_JUAL1['REV_STUDENT'],
                            DECODE($aREC_JUAL1['CUST_NAME']), CVR($nJUMLAH), $aREC_JUAL1['NAMA_GRP'], $aREC_JUAL1['NAMA_AREA']];
                        TDETAIL($aDTL, [0,0,0,0,0,1,0,0], $cICON);
                    }
                echo '</tbody>';
                TTOTAL(['Total', '', '', '', '', CVR($nT_JUAL), '', ''], [0,0,0,0,0,1,0,0]);
            eTABLE();
        eTDIV();
	eTFORM('*');
	include "scr_chat.php";
	require_once("js_framework.php");
    END_WINDOW();
	APP_LOG_ADD( $cHEADER, 'sch_rep_ar.php:'.$cAPP_CODE);
	SYS_DB_CLOSE($DB2);
?>
<script>

function FILT_REV(_D1, _D2, _S) {
	window.location.assign("?_d1="+_D1 + "&_d2=" + _D2+ "&_s="+_S);
}

</script>

