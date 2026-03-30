<?php
//	rep_sales_inv.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Laporan - Penjualan.pdf';
	$cHEADER 		= S_MSG('RS13','Laporan Penjualan (PER FAKTUR)');
	$ADD_LOG		= APP_LOG_ADD($cHEADER);
	$cACTION 		= (isset($_GET['action']) ? $_GET['action'] : '');
  
	$cNO_INVOICE 	= S_MSG('RS01','Faktur');
	$cTANGGAL 		= S_MSG('RS02','Tanggal');
	$cJT_TEMPO 		= S_MSG('RS03','Jt.Tempo');
	$cPELANGGAN		= S_MSG('RS04','Pelanggan');
	$cNIL_TRN		= S_MSG('RS05','Penjualan');
	$cDISKON	 	= S_MSG('RS06','Diskon');
	$cP_P_N		 	= S_MSG('RS07','Ppn');
	$cJUMLAH	 	= S_MSG('RS08','Jumlah');
	$cTGL1			= S_MSG('RS02','Tanggal');
	$cTGL2			= S_MSG('RS14','s/d');
	
	$dPERIOD1=date("d-m-Y");
	$dPERIOD2=date("d-m-Y");

	if (isset($_GET['DATE1'])) $dPERIOD1=$_GET['DATE1'];
	if (isset($_GET['DATE2'])) $dPERIOD2=$_GET['DATE2'];
	
	$q_SALESMAN=OpenTable('TbSalesman');
	$multi_SLSMAN = SYS_ROWS($q_SALESMAN)>0;

	$qQUERY=OpenTable('TrSalesRept', "A.APP_CODE='$cFILTER_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and A.TGL_JUAL>='".DMY_YMD($dPERIOD1)."' and A.TGL_JUAL<='".DMY_YMD($dPERIOD2)."'");
	$lPRINT=TRUST($cUSERCODE, 'TR_SALES_4PRT');
	$aACT = (TRUST($cUSERCODE, 'RP_SALES_INV_PRINT')==1 ? ['<a href="rep_sales_inv_print.php?_d1='.$dPERIOD1.'&_d2='.$dPERIOD2.'"><i class="fa fa-solid fa-print"></i>Print</a>'] : []);
	DEF_WINDOW($cHEADER, 'collapse');
		TFORM($cHEADER, '', $aACT, $cHELP_FILE, '*');
			LABEL([1,1,1,4], '700', $cTGL1, '', 'right');
			INP_DATE([2,2,3,6], '900', '', $dPERIOD1, '', '', '', '', '', "select_BAYAR(this.value, '$dPERIOD2')");
			// echo '<span class="col-lg-1 col-sm-1"></span>';
			LABEL([1,1,1,4], '700', $cTGL2, '', 'right');
			INP_DATE([2,2,3,6], '900', '', $dPERIOD2, '', '', '', '', '', "select_BAYAR('$dPERIOD1', this.value)");
			TDIV();
				TABLE('example');
					THEAD([$cNO_INVOICE, $cTANGGAL, $cJT_TEMPO, $cPELANGGAN, $cNIL_TRN, $cDISKON, $cP_P_N, $cJUMLAH], '', [0,0,0,0,1,1,1,1]);
					echo '<tbody>';
						$nT_JUAL = 0;	$nT_DISK = 0;	$nT_PPN = 0;	$nT_JML = 0;	
						while($aREC_JUAL1=SYS_FETCH($qQUERY)) {
							$nJUMLAH = $aREC_JUAL1['NILAI'] - $aREC_JUAL1['DISCOUNT'] + $aREC_JUAL1['PPN'];
							echo '<tr>';
								$cICON = 'fa fa-money';
								echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
								echo "<td>". $aREC_JUAL1['NOTA']."</a></span></td>";
								echo '<td>'.date("d-M-Y", strtotime($aREC_JUAL1['TGL_JUAL'])).'</td>';
								echo '<td>'.date("d-M-Y", strtotime($aREC_JUAL1['TGL_BAYAR'])).'</td>';
								echo '<td>'.$aREC_JUAL1['CUST_NAME'].'</td>';
								echo '<td align="right">'.CVR($aREC_JUAL1['NILAI']).'</td>';
								echo '<td align="right">'.CVR($aREC_JUAL1['DISCOUNT']).'</td>';
								echo '<td align="right">'.CVR($aREC_JUAL1['PPN']).'</td>';
								echo '<td align="right">'.CVR($nJUMLAH).'</td>';
								$nT_JUAL += $aREC_JUAL1['NILAI'];
								$nT_DISK += $aREC_JUAL1['DISCOUNT'];
								$nT_PPN += $aREC_JUAL1['PPN'];
								$nT_JML += $nJUMLAH;
							echo '</tr>';
						}
						TTOTAL(['Total', '', '', '', CVR($nT_JUAL), CVR($nT_DISK), CVR($nT_PPN), CVR($nT_JML)], [0,0,0,0,1,1,1,1]);
					echo '</tbody>';
				eTABLE();
			eTDIV();
		eTFORM('*');
	END_WINDOW();
	SYS_DB_CLOSE($DB2);	
?>

<script>

function select_BAYAR(TGL_1, TGL_2) {
	window.location.assign("rep_sales_inv.php?DATE1="+TGL_1+"&DATE2="+TGL_2);
}

</script>

