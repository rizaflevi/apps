<?php
//	rep_stock.php
//	Laporan stock

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER	= S_MSG('RH50','Laporan Stock Barang (mutasi)');
	$cHELP_FILE = 'Doc/Laporan - Stok.pdf';

	$cKODE_TBL 	= S_MSG('RH51','KodeBrg');
	$cNAMA_TBL 	= S_MSG('RH52','Nama Barang');
	$cSTOCK 	= S_MSG('RH60','Stock');
	$cADJUSMENT	= S_MSG('RH54','Adjustment');
	$cSTOK_AWAL	= S_MSG('RH55','Stock Awal');
	$cMSK_BELI	= S_MSG('RH56','Pembelian');
	$cMSK_PNDH	= S_MSG('RH57','Pindah');
	$cRTR_JUAL	= S_MSG('RH58','Retur Jual');
	$cJML_JUAL	= S_MSG('RH59','Penjualan');
	$cKLR_PNDH	= S_MSG('RH71','Klr. Pindah');
	$cRTR_BELI	= S_MSG('RH72','Retur Beli');
	$cKELOMPOK	= S_MSG('RH73','Klmpk Brg');
	$cGUDANG	= S_MSG('RH53','Gudang');

	$sPERIOD1	= $_SESSION['sCURRENT_PERIOD'];
	if (isset($_GET['_p']))	$sPERIOD1 = $_GET['_p'];

	$cFILTER_GRUP=(isset($_GET['_g']) ? $cFILTER_GRUP=$_GET['_g'] : '');

	$cFILTER_GUDANG='';
	$qGDG = OpenTable('TbWarehouse', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$r_TB_GUDANG	= SYS_FETCH($qGDG);
	if ($r_TB_GUDANG)
		$cFILTER_GUDANG=$r_TB_GUDANG['KODE_GDG'];
	if (isset($_GET['_w'])) $cFILTER_GUDANG=$_GET['_w'];

	$NOW = date("Y-m-d H:i:s");
	$cFILTER = "B.KODE_BRG!='' and A.ST_YEAR=". substr($sPERIOD1,0,4) . " and A.ST_MONTH=" . substr($sPERIOD1,5,2);
	$cFILTER.= ($cFILTER_GRUP!='' ? " and md5(GROUP_INV)='$cFILTER_GRUP'" : "");

	if ($cFILTER_GUDANG!='') {
		$cFILTER.= " and A.WAREHOUSE ='$cFILTER_GUDANG'";
	}
	$q_STOCK=OpenTable('QStockRep', $cFILTER);

	$tJML_STOCK=$tJML_ADJSM=$tSTOK_AWAL=$tSTOK_BELI=$tMSK_PNDAH=$tJML_RJUAL=$tJML_JUAL=$tKLR_PNDAH=$tRTR_BELI=0;
	$cFTR_FONT_CLR = S_PARA('_REPORT_TOTAL_COLOR','brown');
	DEF_WINDOW($cHEADER, 'collapse', 'prd');
		TFORM($cHEADER, '', [], $cHELP_FILE, '*');
			TDIV();
			LABEL([2,2,2,6], '700', S_MSG('F009','Kelompok'));
			echo '<select name="PILIH_GROUP" class="col-xs-4 form-label-900" onchange="FILTER_DATA('. $sPERIOD1.', this.value, '. $cFILTER_GUDANG.')">';
				$q_BGROUP=OpenTable('TbInvGroup', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
				echo "<option value=''  > All</option>";
				while($aREC_GR_DATA=SYS_FETCH($q_BGROUP)){

					if(md5($aREC_GR_DATA['KODE_GRP'])==$cFILTER_GRUP){
						echo "<option value='".md5($aREC_GR_DATA['KODE_GRP']). "' selected='$REC_EDIT[GROUP_INV]' >$aREC_GR_DATA[NAMA_GRP]</option>";
					} else {
						echo "<option value='".md5($aREC_GR_DATA['KODE_GRP']). "'  >$aREC_GR_DATA[NAMA_GRP]</option>";
					}

				}
			echo '</select>	';
			LABEL([2,2,2,2], '700', $cGUDANG, '', 'right');
			?>
				<select name="PILIH_GUDANG" class="col-xs-4 form-label-900" onchange="FILTER_DATA('<?php echo $sPERIOD1?>', '<?php echo $cFILTER_GRUP?>', this.value)">
<?php 
					$qGDG = OpenTable('TbWarehouse', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					echo "<option value=''  > All</option>";
					while($aREC_GR_DATA=SYS_FETCH($qGDG)){

						if($aREC_GR_DATA['KODE_GDG']==$cFILTER_GUDANG){
							echo "<option value='$aREC_GR_DATA[KODE_GDG]' selected='$REC_EDIT[KODE_GDG]' >$aREC_GR_DATA[NAMA_GDG]</option>";
						} else {
							echo "<option value='$aREC_GR_DATA[KODE_GDG]'  >$aREC_GR_DATA[NAMA_GDG]</option>";
						}

					}
				echo '</select>';

			TABLE('example');
				THEAD([$cKODE_TBL, $cNAMA_TBL, $cGUDANG, $cSTOCK, $cADJUSMENT, $cSTOK_AWAL, $cMSK_BELI, $cMSK_PNDH, $cRTR_JUAL, $cJML_JUAL, $cKLR_PNDH, $cRTR_BELI, $cKELOMPOK]);
				echo '<tbody>';
					while($r_STOCK=SYS_FETCH($q_STOCK)) {
						TDETAIL([$r_STOCK['KODE_BARANG'], DECODE($r_STOCK['NAMA_BRG']), DECODE($r_STOCK['NAMA_GDG']), $r_STOCK['CUR_STOCK'], 
							$r_STOCK['ADJUSTMENT'], $r_STOCK['BEG_STOCK'], $r_STOCK['PURCHASE'], $r_STOCK['MOVE_IN'], $r_STOCK['SLS_RET'], 
							$r_STOCK['SALES'], $r_STOCK['MOVE_OUT'], $r_STOCK['PRCH_RET']], 
							[0,0,0,1,1,1,1,1,1,1,1,1]);
						$tJML_STOCK += $r_STOCK['CUR_STOCK'];
						$tJML_ADJSM += $r_STOCK['ADJUSTMENT'];
						$tSTOK_AWAL += $r_STOCK['BEG_STOCK'];
						$tSTOK_BELI += $r_STOCK['PURCHASE'];
						$tMSK_PNDAH += $r_STOCK['MOVE_IN'];
						$tJML_RJUAL += $r_STOCK['SLS_RET'];
						$tJML_JUAL += $r_STOCK['SALES'];
						$tKLR_PNDAH += $r_STOCK['MOVE_OUT'];
						$tRTR_BELI += $r_STOCK['PRCH_RET'];
					}
				echo '</tbody>';
?>
				<tr></tr>
							<tr>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;"></td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;"></td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;"></td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;">Total</td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;" align="right"><?php echo number_format($tJML_STOCK)?></td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;" align="right"><?php echo number_format($tJML_ADJSM)?></td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;" align="right"><?php echo number_format($tSTOK_AWAL)?></td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;" align="right"><?php echo number_format($tSTOK_BELI)?></td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;" align="right"><?php echo number_format($tMSK_PNDAH)?></td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;" align="right"><?php echo number_format($tJML_RJUAL)?></td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;" align="right"><?php echo number_format($tJML_JUAL)?></td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;" align="right"><?php echo number_format($tKLR_PNDAH)?></td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;" align="right"><?php echo number_format($tRTR_BELI)?></td>
								<td style="font-size: 16px;color: <?php echo $cFTR_FONT_CLR?>;background-color: LightGray ;"></td>
							</tr>
							<td></td><td></td><td></td>
							<tr></tr>
<?php 
			eTABLE();
			eTDIV();
		eTFORM('*');
		include "scr_chat.php";
		APP_LOG_ADD( $cHEADER, 'rep_stock.php:'.$cAPP_CODE);
		require_once("js_framework.php");
	END_WINDOW();
	SYS_DB_CLOSE($DB2);
?>
<script>
function FILTER_DATA(pPRD, pGROUP, pWHOUSE) {
	window.location.assign("?_p=" + pPRD+ "&_g="+pGROUP + "&_w="+pWHOUSE);
}

</script>

