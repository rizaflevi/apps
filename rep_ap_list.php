<?php
//	rep_ap_list.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER 	= S_MSG('RP05','Daftar Utang dagang');
	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'rep_ap_list');
	$cACTION = '';
	if (isset($_GET['action']))	$cACTION = $_GET['action'];
  
	$cNO_INVOICE 	= S_MSG('R001','No. Faktur');
	$cTANGGAL 		= S_MSG('R002','Tgl.Faktur');
	$cTGL_TRIMA		= S_MSG('R003','Tgl. Terima');
	$cJT_TEMPO 		= S_MSG('R004','Tgl.J.Tempo');
	$cPELANGGAN		= S_MSG('F100','Nama Supplier');
	$cKODE_PLGN		= S_MSG('F003','Kode');
	$cJUMLAH	 	= S_MSG('R005','Jumlah');
	$cBAYAR		 	= S_MSG('R006','Bayar');
	$cSISA		 	= S_MSG('R007','Sisa');
	$cTGL_BYR	 	= S_MSG('R008','TglBayar');

	$d_START_AP 	= S_PARA('START_AP',date("Ymd"));
	$dPERIOD1=substr($d_START_AP,0,4).'-'.substr($d_START_AP,4,2).'-'.substr($d_START_AP,6,2);
	$dPERIOD2=date("Y-m-d");

	if (isset($_GET['SUPPLIER'])) $cSALESMAN=$_GET['SUPPLIER'];
	if (isset($_GET['TANGGAL2'])) $dPERIOD2=$_GET['TANGGAL2'];
	
	$c_WAREHOUSE="select * from gudang where APP_CODE='$cAPP_CODE' and DELETOR=''";
	$q_WAREHOUSE=SYS_QUERY($c_WAREHOUSE);
	$multi_GUDANG = SYS_ROWS($q_WAREHOUSE)>0;

	$cQUERY="select A.BRCH_CODE, A.INVOICE, A.DATE_REC, A.DUE_DATE, A.JUMLAH, A.DISK, A.PPN, A.DISK2, A.PPN_BM, A.VENDOR, A.GUDANG, B.KODE_VND, B.NAMA_VND, B.VND_GROUP, E.BRCH_NAME from masuk1 A
		left join ( select KODE_VND, NAMA_VND, VND_GROUP from vendor where APP_CODE='$cAPP_CODE' and DELETOR='') B on A.VENDOR=B.KODE_VND ";
	$cQUERY.=" left join ( select VG_CODE, VG_DESC from vnd_grup where APP_CODE='$cAPP_CODE' and DELETOR='') C on B.VND_GROUP=C.VG_CODE";
	if($multi_GUDANG) {
		$cQUERY.= " left join ( select KODE_GDG, NAMA_GDG from gudang where APP_CODE='$cAPP_CODE' and DELETOR='') D on D.KODE_GDG=A.GUDANG";
	}
	$cQUERY.= " left join ( select BRCH_CODE, BRCH_NAME from branch where APP_CODE='$cAPP_CODE' and DELETOR='') E on E.BRCH_CODE=A.BRCH_CODE";	
	$cQUERY.= " where A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and A.REC_PROCED=1 and A.PAID_OUT=0 and A.DATE_REC>='$dPERIOD1' and A.DATE_REC<='$dPERIOD2'";
	$qQUERY=SYS_QUERY($cQUERY);
	
	$cHELP_BOX		= S_MSG('RT7A','Help Laporan Tagihan');
	$cHELP_1		= S_MSG('RT7B','Ini adalah modul untuk menampilkan Daftar / laporan tagihan terhadap penjualan-penjualan yang belum di terima pembayaran nya.');
	$cHELP_2		= S_MSG('RT7C','Data yang ditampilkan Nomor Faktur, tanggal faktur, tanggal jatuh tempo, kode dan nama pelanggan, jumlah nilai faktur, pembayaran ( apabila ada pembayaran sebagian ) dan sisa tagihan.');
	$cHELP_3		= S_MSG('RT7D','Laporan tagihan ini di tampilkan data piutang per tanggal hari ini.');
	$cHELP_4		= S_MSG('RT7E','Untuk menampilkan data tagihan per salesman tertentu, pilih Salesman tertentu saja.');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
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
												<a href="#help_rep_ap_list" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="<?php echo S_PARA('_DISP_REPORT_CLASS','display table table-hover table-condensed')?> nowrap">
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNO_INVOICE?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTANGGAL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cJT_TEMPO?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_PLGN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cPELANGGAN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cJUMLAH?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cBAYAR?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cSISA?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														$nT_JUAL = 0;	$nT_BAYAR = 0;	$nT_SISA = 0;	$nT_JML = 0;	
														while($aREC_PRCH1=SYS_FETCH($qQUERY)) {
															$nBAYAR = 0;
															$QRY_BAYAR2="select BDV_NO, BDV_DAM, BDV_INV from bayar3
																where APP_CODE='$cAPP_CODE' and DELETOR='' and BDV_INV='$aREC_PRCH1[INVOICE]'";
															$REC_RECEPT2=SYS_QUERY($QRY_BAYAR2);
															while($aREC_RECEPT2=SYS_FETCH($REC_RECEPT2)) {
																$nBAYAR += $aREC_RECEPT2['BDV_DAM'];
															}

															$nJUMLAH = $aREC_PRCH1['JUMLAH'] - $aREC_PRCH1['DISK'] - $aREC_PRCH1['DISK2']  + $aREC_PRCH1['PPN_BM'] + $aREC_PRCH1['PPN'];
															$nSISA = $nJUMLAH - $nBAYAR;
															if($nSISA>0) {
															echo '<tr>';
																$cICON = 'fa fa-money';
																echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
																echo "<td>". $aREC_PRCH1['INVOICE']."</a></span></td>";
																echo '<td>'.date("d-M-Y", strtotime($aREC_PRCH1['DATE_REC'])).'</td>';
																echo '<td>'.date("d-M-Y", strtotime($aREC_PRCH1['DUE_DATE'])).'</td>';
																echo '<td>'.$aREC_PRCH1['VENDOR'].'</td>';
																echo '<td>'.$aREC_PRCH1['NAMA_VND'].'</td>';
																echo '<td align="right">'.number_format($nJUMLAH).'</td>';
																echo '<td align="right">'.number_format($nBAYAR).'</td>';
																echo '<td align="right">'.number_format($nSISA).'</td>';
																$nT_JUAL += $nJUMLAH;
																$nT_BAYAR += $nBAYAR;
																$nT_SISA += $nSISA;
															echo '</tr>';
															}
														}
													?>
														<tr>	</tr>
														<tr>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right">Total</td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nT_JUAL)?></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nT_BAYAR)?></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nT_SISA)?></td>
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

			<div class="modal" id="help_rep_ap_list" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
	window.location.assign("rep_ap_list.php?&TANGGAL2="+TGL_2);
}

</script>

