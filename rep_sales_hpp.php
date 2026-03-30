<?php
//	rep_sales_hpp.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER 		= S_MSG('RS41','Laporan Penjualan dan HPP');
	$ADD_LOG		= APP_LOG_ADD($cHEADER);
	$cACTION = '';
	if (isset($_GET['action']))	$cACTION = $_GET['action'];
  
	$cNO_INVOICE 	= S_MSG('RS01','Faktur');
	$cTANGGAL 		= S_MSG('RS02','Tanggal');
	$cJT_TEMPO 		= S_MSG('RS03','Jt.Tempo');
	$cPELANGGAN		= S_MSG('RS04','Pelanggan');
	$cNIL_TRN		= S_MSG('RS05','Penjualan');
	$cDISKON	 	= S_MSG('RS06','Diskon');
	$cH_P_P		 	= S_MSG('RH16','Hpp');
	$cJUMLAH	 	= S_MSG('RS08','Jumlah');
	$cPROFIT	 	= S_MSG('RF00','Profit');
	$cJAM_JUAL		= S_MSG('RS09','Jam');
	
	$dPERIOD1=date("Y-m-01");
	$dPERIOD2=date("Y-m-d");

	if (isset($_GET['TANGGAL1'])) $dPERIOD1=$_GET['TANGGAL1'];
	if (isset($_GET['TANGGAL2'])) $dPERIOD2=$_GET['TANGGAL2'];
	
	$qQUERY=OpenTable('TrSalesRept', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and A.TGL_JUAL>='$dPERIOD1' and A.TGL_JUAL<='$dPERIOD2' and A.FKT_VOID=0");
	$cHELP_BOX		= S_MSG('RS1A','Help Laporan Penjualan ( per Faktur )');
	$cHELP_1		= S_MSG('RS1B','Ini adalah modul untuk menampilkan Data Penjualan, baik kontan maupun kredit per tanggal penjualan');
	$cHELP_2		= S_MSG('RS1C','Data yang ditampilkan awalnya adalah data penjualan dari awal bulan sampai dengan hari ini.');
	$cHELP_3		= S_MSG('RS1D','Untuk menampilkan data penjualan diluar tanggal itu, masukkan tanggal yang dimaksud pada kolom filter.');
	$cHELP_4		= S_MSG('RS1E','Begitu juga untuk menampilkan data Penjualan per cabang ( jika ada ), Salesman, Pelanggan atau area tertentu saja.');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	?>
		<body class="sidebar-collapse">
			<?php	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">

				<div class="page-sidebar collapseit">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
						<?php	require_once("scr_menu.php");	?>
					</div>
					<div class="project-info"></div>
				</div>

				<section id="main-content" class="sidebar_shift">
					<section class="wrapper main-wrapper" style=''>
						<div class="clearfix"></div>

						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left"><?php echo $cHEADER?></h2>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>
												<a href="#help_rep_sales_inv" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<label class="col-sm-1 form-label-700" for="field-4"><?php echo S_MSG('RS02','Tanggal')?></label>
											<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $dPERIOD1?>" onchange="select_BAYAR(this.value, '<?php echo $dPERIOD2?>')">

											<label class="col-sm-1 form-label-700" style="text-align:right;"><?php echo S_MSG('RS14','s/d')?></label>
											<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $dPERIOD2?>" onchange="select_BAYAR('<?php echo $dPERIOD1?>', this.value)">

											<table id="example" class="<?php echo S_PARA('_DISP_REPORT_CLASS','display table table-hover table-condensed')?> nowrap">
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNO_INVOICE?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTANGGAL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cJT_TEMPO?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cPELANGGAN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cNIL_TRN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cDISKON?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cJUMLAH?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cH_P_P?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cPROFIT?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														$nT_JUAL = 0;	$nT_DISK = 0;	$nT_PPN = 0;	$nT_JML = 0;	$nT_HPP = 0;	$nT_PROFIT = 0;	
														while($aREC_JUAL1=SYS_FETCH($qQUERY)) {
															$nJUMLAH = $aREC_JUAL1['NILAI'] - $aREC_JUAL1['DISCOUNT'] + $aREC_JUAL1['PPN'];
															$nPROFIT = $nJUMLAH - $aREC_JUAL1['HPP'];
															echo '<tr>';
																$cICON = 'fa fa-money';
																echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
																echo "<td>". $aREC_JUAL1['NOTA']."</a></span></td>";
																echo '<td>'.date("d-M-Y", strtotime($aREC_JUAL1['TGL_JUAL'])).'</td>';
																echo '<td>'.date("d-M-Y", strtotime($aREC_JUAL1['TGL_BAYAR'])).'</td>';
																echo '<td>'.$aREC_JUAL1['CUST_NAME'].'</td>';
																echo '<td align="right">'.number_format($aREC_JUAL1['NILAI']).'</td>';
																echo '<td align="right">'.number_format($aREC_JUAL1['DISCOUNT']).'</td>';
																echo '<td align="right">'.number_format($nJUMLAH).'</td>';
																echo '<td align="right">'.number_format($aREC_JUAL1['HPP']).'</td>';
																echo '<td align="right">'.number_format($nPROFIT).'</td>';
																$nT_JUAL	+= $aREC_JUAL1['NILAI'];
																$nT_DISK	+= $aREC_JUAL1['DISCOUNT'];
																$nT_PPN		+= $aREC_JUAL1['HPP'];
																$nT_JML		+= $nJUMLAH;
																$nT_HPP		+= $aREC_JUAL1['HPP'];
																$nT_PROFIT	+= $nPROFIT;
															echo '</tr>';
														}
													?>
														<tr>	</tr>
														<tr>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;">Total</td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nT_JUAL)?></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nT_DISK)?></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nT_JML)?></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nT_HPP)?></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nT_PROFIT)?></td>
														</tr>
														<td></td><td></td><td></td>
														<tr>	</tr>
												</tbody>
											</table>

										</div>
									</div>
								</div>
							</section>
						</div>

					</section>
				</section>
				<?php	include "scr_chat.php";	?>
			</div>
			<?php	require_once("js_framework.php");	?>
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 

			<div class="modal" id="help_rep_sales_inv" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">

						<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_3?></p>
						<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_4?></p>

						</div>
						<div class="modal-footer">
							<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						</div>
					</div>
				</div>
			</div>
		</body>
	</html>

<script>

function select_BAYAR(TGL_1, TGL_2) {
	window.location.assign("rep_sales_hpp.php?TANGGAL1="+TGL_1+"&TANGGAL2="+TGL_2);
}

</script>

