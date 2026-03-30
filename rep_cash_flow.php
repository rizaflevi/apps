<?php
//	rep_cash_flow.php //
//  TODO : set start system on server, delete UEDIT_PAST

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 	= $_SESSION['gUSERCODE'];
	$cHEADER 	= S_MSG('RK10','Laporan Arus Kas');
	$cHELP_FILE = 'Doc/Laporan - Arus Kas.pdf';
	$ADD_LOG	= APP_LOG_ADD($cHEADER);
	$cACTION    = (isset($_GET['action']) ? $cACTION = $_GET['action'] : '');
  
	$cTANGGAL 		= S_MSG('RS02','Tanggal');
	$cNO_BUKTI 		= S_MSG('RK04','No. Bukti');
	$cKETERANGAN	= S_MSG('RK05','Keterangan');
	$cDEBIT		    = S_MSG('RK06','Debit');
	$cKREDIT	 	= S_MSG('RK07','Kredit');
	$cSALDO		 	= S_MSG('I007','Saldo Akhir');
	$cACCOUNT 	    = S_MSG('NR07','Account');
	
	$dPERIOD1=$_SESSION['sCURRENT_PERIOD'].'-01';
	$dPERIOD2=$_SESSION['sCURRENT_PERIOD'].'-'.date("t");

	if (isset($_GET['_d1'])) $dPERIOD1=$_GET['_d1'];
	if (isset($_GET['_d2'])) $dPERIOD2=$_GET['_d2'];
	$dPERIOD1 = str_replace('/', '-', $dPERIOD1);
	$dPERIOD1 = date("Y-m-d", strtotime($dPERIOD1));
	$dPERIOD2 = str_replace('/', '-', $dPERIOD2);
	$dPERIOD2 = date("Y-m-d", strtotime($dPERIOD2));

    RecDelete('TempCashflow', "APP_CODE='$cUSERCODE'");
	$qQUERY=OpenTable('TrReceiptDHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and B.TGL_BAYAR>='$dPERIOD1' and B.TGL_BAYAR<='$dPERIOD2'");
    if ($qQUERY) {
        while($aREC_TRM=SYS_FETCH($qQUERY)) {
            RecCreate('TempCashflow', ['REC_ID', 'TRANS_DATE', 'TEMP_DESC', 'ACCOUNT_NO', 'MT_DEBIT', 'APP_CODE'], [$aREC_TRM['NO_TRM'], $aREC_TRM['TGL_BAYAR'], $aREC_TRM['DESCRP'], $aREC_TRM['ACCOUNT'], $aREC_TRM['NILAI'], $cUSERCODE]);
        }
    }

	$qQUERY=OpenTable('TrPaymentDHdr', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and B.BDV_DATE>='$dPERIOD1' and B.BDV_DATE<='$dPERIOD2'");
    if ($qQUERY) {
        while($aREC_PAY=SYS_FETCH($qQUERY)) {
            RecCreate('TempCashflow', ['REC_ID', 'TRANS_DATE', 'TEMP_DESC', 'ACCOUNT_NO', 'MT_CREDIT', 'APP_CODE'], [$aREC_PAY['BDV_NO'], $aREC_PAY['BDV_DATE'], $aREC_PAY['BDV_DESC'], $aREC_PAY['BDV_REFF'], $aREC_PAY['BDV_DAM'], $cUSERCODE]);
        }
    }
	$qQUERY=OpenTable('TempCashflow', "APP_CODE='$cUSERCODE' and TRANS_DATE>='$dPERIOD1' and TRANS_DATE<='$dPERIOD2'", '', 'TRANS_DATE');

	DEF_WINDOW($cHEADER, 'collapse');
		TFORM($cHEADER, '', [], $cHELP_FILE, '*');
			TDIV();
				LABEL([1,1,1,6], '700', S_MSG('RS02','Tanggal'));
				INPUT_DATE([2,2,2,6], '900', '', $dPERIOD1, '', '', '', 0, '', '', '', "select_BAYAR(this.value, '$dPERIOD2')");
				LABEL([1,1,1,4], '700', S_MSG('RS14','s/d'), '', 'right');
				INPUT_DATE([2,2,2,6], '900', '', $dPERIOD2, '', '', '', 0, '', '', '', "select_BAYAR('$dPERIOD1', this.value)");

				TABLE('example');
				THEAD([$cTANGGAL, $cNO_BUKTI, $cKETERANGAN, $cACCOUNT, $cDEBIT, $cKREDIT, $cSALDO], '', [0,0,0,0,1,1,1]);
				echo '<tbody>';
						$nT_DEBET = $nT_KREDIT = $nJUMLAH = 0;
						while($aREC_CASH=SYS_FETCH($qQUERY)) {
							$nJUMLAH += $aREC_CASH['MT_DEBIT'] - $aREC_CASH['MT_CREDIT'];
							$nT_DEBET += $aREC_CASH['MT_DEBIT'];
							$nT_KREDIT += $aREC_CASH['MT_CREDIT'];
							TDETAIL([date("d-M-Y", strtotime($aREC_CASH['TRANS_DATE'])), $aREC_CASH['REC_ID'], DECODE($aREC_CASH['TEMP_DESC']), $aREC_CASH['ACCOUNT_NO'], CVR($aREC_CASH['MT_DEBIT']), CVR($aREC_CASH['MT_CREDIT']), CVR($nJUMLAH)], 
								[0,0,0,0,1,1,1], '', []);
						}
				echo '</tbody>';
				TTOTAL(['', 'Total', '', '', CVR($nT_DEBET), CVR($nT_KREDIT), CVR($nJUMLAH)], [0,0,0,0,1,1,1]);
				eTABLE();
			eTDIV();
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
	END_WINDOW();
	APP_LOG_ADD($cHEADER, 'View');
?>

<script>
function select_BAYAR(TGL_1, TGL_2) {
	window.location.assign("rep_cash_flow.php?_d1="+TGL_1+"&_d2="+TGL_2);
}

</script>

