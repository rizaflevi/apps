<?php
//	sch_rep_ar.php //
//	TODO : filter data

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER 	= S_MSG('RT70','Daftar Tagihan');
	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Laporan Piutang');
	$cHELP_FILE 	= 'Doc/Laporan - Tagihan.pdf';
	$cACTION 	= '';
	if (isset($_GET['action']))	$cACTION = $_GET['action'];

	$d_START_AR 	= S_PARA('START_AR',date("Ymd"));
	$dPERIOD1=substr($d_START_AR,0,4).'-'.substr($d_START_AR,4,2).'-'.substr($d_START_AR,6,2);
	$dPERIOD2=date("Y-m-d");

	$cSEKOLAH 	= '';
	if (isset($_GET['_s'])) $cSEKOLAH=$_GET['_s'];
	$cKELAS 	= '';
	if (isset($_GET['_k'])) $cKELAS=$_GET['_k'];

	$qREV_HDR=OpenTable('SchRepAR', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and A.REV_DATE>='$dPERIOD1' and A.REV_DATE<='$dPERIOD2'");

	DEF_WINDOW($cHEADER, 'collapse');
	TFORM($cHEADER, '', [], $cHELP_FILE, '*');
	TDIV();
		$qSCHOOL=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
		LABEL([1,2,2,4], '700', 'Sekolah');
		echo '<select name="SCHOOL" class="col-sm-3 col-xs-8 form-label-900" onchange="FILT_AR(this.value, '. $cKELAS.')"';
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
			THEAD([S_MSG('RT71','No. Faktur'), S_MSG('RS02','Tanggal'), S_MSG('RS03','Jt.Tempo'), S_MSG('RS04','Pelanggan'), S_MSG('RT75','Nama'), S_MSG('RS08','Jumlah'), S_MSG('RT77','Bayar'), S_MSG('RT78','Sisa')], '', [0,0,0,0,0,1,1,1]);
			echo '<tbody>';
				$nT_JUAL = 0;	$nT_BAYAR = 0;	$nT_SISA = 0;	$nT_JML = 0;	
				while($aREC_JUAL1=SYS_FETCH($qREV_HDR)) {
					$nBAYAR = 0;
					$qREC_DTL=OpenTable('TrReceiptDtl', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and A.NO_FAKTUR='$aREC_JUAL1[REV_ID]'");
					while($aREC_RECEPT2=SYS_FETCH($qREC_DTL)) {
						$nBAYAR += $aREC_RECEPT2['NILAI'];
					}

					$nJUMLAH = $aREC_JUAL1['REV_VALUE'];
					$nSISA = $nJUMLAH - $nBAYAR;
					if($nSISA>0) {
					echo '<tr>';
						$nT_JUAL += $nJUMLAH;
						$nT_BAYAR += $nBAYAR;
						$nT_SISA += $nSISA;
                        $aDTL = [$aREC_JUAL1['REV_ID'], date("d-M-Y", strtotime($aREC_JUAL1['REV_DATE'])), 
							date("d-M-Y", strtotime($aREC_JUAL1['REV_DUE'])), $aREC_JUAL1['REV_STUDENT'],
							DECODE($aREC_JUAL1['CUST_NAME']), CVR($nJUMLAH), CVR($nBAYAR), CVR($nSISA)];
						TDETAIL($aDTL, [0,0,0,0,0,1,1,1], $cICON);
					echo '</tr>';
					}
				}
				TTOTAL(['Total', '', '', '', '', CVR($nT_JUAL), CVR($nT_BAYAR), CVR($nT_SISA)], [0,0,0,0,0,1,1,1]);
			echo '</tbody>';
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

function FILT_AR(_SKLH, _KLAS) {
	window.location.assign("?_s="+_SKLH + "&_k="+_KLAS);
}

</script>

