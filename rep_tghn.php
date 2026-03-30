<?php
//	rep_tghn.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER 	= S_MSG('RT70','Daftar Tagihan');
	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'rep_tghn');
	$cHELP_FILE 	= 'Doc/Laporan - Tagihan.pdf';
	$cACTION 	= '';
	if (isset($_GET['action']))	$cACTION = $_GET['action'];
  
	$cNO_INVOICE 	= S_MSG('RT71','No. Faktur');
	$cTANGGAL 		= S_MSG('RS02','Tanggal');
	$cJT_TEMPO 		= S_MSG('RS03','Jt.Tempo');
	$cPELANGGAN		= S_MSG('RS04','Pelanggan');
	$cKODE_PLGN		= S_MSG('RT74','Kode');
	$cJUMLAH	 	= S_MSG('RS08','Jumlah');
	$cBAYAR		 	= S_MSG('RT77','Bayar');
	$cSISA		 	= S_MSG('RT78','Sisa');

	$d_START_AR 	= S_PARA('START_AR',date("Ymd"));
	$dPERIOD1=substr($d_START_AR,0,4).'-'.substr($d_START_AR,4,2).'-'.substr($d_START_AR,6,2);
	$dPERIOD2=date("Y-m-d");

	if (isset($_GET['SALESMAN'])) $cSALESMAN=$_GET['SALESMAN'];
	if (isset($_GET['TANGGAL2'])) $dPERIOD2=$_GET['TANGGAL2'];

	$multi_SLSMAN = false;
	$qQUERY=OpenTable('TbSalesman', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if ($qQUERY)
		if (SYS_ROWS($qQUERY)>0)	$multi_SLSMAN = SYS_ROWS($qQUERY)>0;

	$qQUERY=OpenTable('TrSalesTghn', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and A.FKT_VOID=0 and A.FKT_LUNAS=0 and A.TGL_JUAL>='$dPERIOD1' and A.TGL_JUAL<='$dPERIOD2'");

	DEF_WINDOW($cHEADER);
	TFORM($cHEADER, '', [], $cHELP_FILE, '*');
		TDIV();
		TABLE('example');
			THEAD([$cNO_INVOICE, $cTANGGAL, $cJT_TEMPO, $cKODE_PLGN, $cPELANGGAN, $cJUMLAH, $cBAYAR, $cSISA], '', [0,0,0,0,0,1,1,1]);
			echo '<tbody>';
				$nT_JUAL = 0;	$nT_BAYAR = 0;	$nT_SISA = 0;	$nT_JML = 0;	
				while($aREC_JUAL1=SYS_FETCH($qQUERY)) {
					$nBAYAR = 0;
					$qREC_DTL=OpenTable('TrReceiptDtl', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and A.NO_FAKTUR='$aREC_JUAL1[NOTA]'");
					while($aREC_RECEPT2=SYS_FETCH($qREC_DTL)) {
						$nBAYAR += $aREC_RECEPT2['NILAI'];
					}

					$nJUMLAH = $aREC_JUAL1['NILAI'] - $aREC_JUAL1['DISCOUNT']  - $aREC_JUAL1['TPR'] + $aREC_JUAL1['PPN'];
					$nSISA = $nJUMLAH - $nBAYAR;
					if($nSISA>0) {
					echo '<tr>';
						$cICON = 'fa fa-money';
						echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
						echo "<td>". $aREC_JUAL1['NOTA']."</a></span></td>";
						echo '<td>'.date("d-M-Y", strtotime($aREC_JUAL1['TGL_JUAL'])).'</td>';
						echo '<td>'.date("d-M-Y", strtotime($aREC_JUAL1['TGL_BAYAR'])).'</td>';
						echo '<td>'.$aREC_JUAL1['KODE_LGN'].'</td>';
						echo '<td>'.$aREC_JUAL1['CUST_NAME'].'</td>';
						echo '<td align="right">'.CVR($nJUMLAH).'</td>';
						echo '<td align="right">'.CVR($nBAYAR).'</td>';
						echo '<td align="right">'.CVR($nSISA).'</td>';
						$nT_JUAL += $nJUMLAH;
						$nT_BAYAR += $nBAYAR;
						$nT_SISA += $nSISA;
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
	APP_LOG_ADD( $cHEADER, 'rep_tghn.php:'.$cAPP_CODE);
	SYS_DB_CLOSE($DB2);
?>
<script>

function select_BAYAR(TGL_1, TGL_2) {
	window.location.assign("rep_tghn.php?&TANGGAL2="+TGL_2);
}

</script>

