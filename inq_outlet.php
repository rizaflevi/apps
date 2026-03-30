<?php
//	rep_stock.php
//	Laporan stock

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('RP67','Laporan Harian Outlet');

	$cFAKTUR 	= S_MSG('RB02','Faktur');
	$cTANGGAL	= S_MSG('RS02','Tanggal');
	$cCABANG	= S_MSG('PA47','Cabang');
	$cNAMA_TBL 	= S_MSG('RB06','Barang');
	$cJUMLAH 	= S_MSG('RB07','Jumlah');
	$cJAM_JUAL	= S_MSG('RS09','Jam');
	$cHARGA		= S_MSG('RB08','Harga');
	$cNILAI		= S_MSG('RB09','Nilai');
	$cPROMO		= S_MSG('RB10','Promo');
	$cTOTAL		= S_MSG('PS37','Total');
	$cJML_JUAL	= S_MSG('RH59','Penjualan');
	$cKLR_PNDH	= S_MSG('RH71','Klr. Pindah');
	$cRTR_BELI	= S_MSG('RH72','Retur Beli');

	$dDATE	= date("Y-m-d");
	if (isset($_GET['_d']))	{
		$dDATE = $_GET['_d'];
	}

	$cFILTER_BRANCH='';
	if (isset($_GET['_b'])) {
		$cFILTER_BRANCH=$_GET['_b'];
	}

	$cFILTER_GUDANG='';
	if (isset($_GET['_t'])) {
		$cFILTER_GUDANG=$_GET['_t'];
	}

	$cHELP_BOX	= S_MSG('RP7A','Help Inquery Laporan Harian Outlet');
	$cHELP_1	= S_MSG('RP7B','Ini adalah modul untuk menampilkan Inquery Laporan Harian Outlet');
	$cHELP_2	= S_MSG('RP7C','Menampilkan data penjualan outlet / cabang yang bisa di filter per outlet atau per tanggal');

	$c_JUAL2 = "SELECT A.NOTA, A.KODE_BRG, A.JUAL_C, A.HARGA, A.DISK_1, B.KODE_BRG, B.NAMA_BRG, B.INV_ISI, C.* FROM jual2 A
				LEFT JOIN ( select KODE_BRG, NAMA_BRG, INV_ISI from invent where APP_CODE='$cFILTER_CODE' and DELETOR='') B  ON A.KODE_BRG=B.KODE_BRG
				LEFT JOIN ( select * from jual1 where APP_CODE='$cFILTER_CODE' and DELETOR='' and TGL_JUAL='$dDATE') C on A.NOTA=C.NOTA
		WHERE C.TGL_JUAL=" . $dDATE . " and C.FKT_VOID=0";
	if ($cFILTER_BRANCH!='') {
		$c_JUAL2.= " and md5(C.BRCH_CODE)='$cFILTER_BRANCH'";
	}
	$c_JUAL2 .= " ORDER BY A.NOTA";

	$q_JUAL2=SYS_QUERY($c_JUAL2);
	
	$tJML_STOCK	= 0;
	$tJML_ADJSM	= 0;
	$tSTOK_AWAL	= 0;
	$tSTOK_BELI	= 0;
	$tMSK_PNDAH	= 0;
	$tJML_RJUAL	= 0;
	$tJML_JUAL	= 0;
	$tKLR_PNDAH	= 0;
	$tRTR_BELI	= 0;
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<body class=" sidebar-collapse">
			<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">
				<div class="page-sidebar  collapseit ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
						<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
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
									  <div class="actions panel_actions pull-right">
											<i class="box_setting fa fa-question" data-toggle="modal" href="#section-settings">Help</i>
	<!--
											<i class="box_toggle fa fa-chevron-down"></i>
											<a href="#help_gl_journal" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											<i class="box_close fa fa-times"></i>
	-->
									  </div>
								</header>

								<label class="col-sm-2 form-label-700" for="field-4"><?php echo $cCABANG?></label>
								<select name="PILIH_CABANG" class="col-sm-3 form-label-900" onchange="FILTER_DATA(this.value, '<?php echo $cFILTER_GUDANG?>')">
								<?php 
									$q_BRANCH=SYS_QUERY("select * from branch where APP_CODE='$cFILTER_CODE' and DELETOR=''");
									echo "<option value=''  > All</option>";
									while($a_BRANCH=SYS_FETCH($q_BRANCH)){

										if(md5($a_BRANCH['BRCH_CODE'])==$cFILTER_GRUP){
											echo "<option value='".md5($a_BRANCH['BRCH_CODE']). "' selected='$REC_EDIT[GROUP_INV]' >$a_BRANCH[BRCH_NAME]</option>";
										} else {
											echo "<option value='".md5($a_BRANCH['BRCH_CODE']). "'  >$a_BRANCH[BRCH_NAME]</option>";
										}

									}
								?>
								</select>

								<label class="col-sm-3 form-label-700"> </label>
								<label class="col-sm-1 form-label-700" for="field-4"><?php echo $cTANGGAL?></label>
								<select name="PILIH_CABANG" class="col-sm-3 form-label-900" onchange="FILTER_DATA('<?php echo $cFILTER_GRUP?>', this.value)">
								<?php 
									$REC_TIPE=SYS_QUERY("select * from gudang where APP_CODE='$cFILTER_CODE' and DELETOR=''");
									echo "<option value=''  > All</option>";
									while($aREC_GR_DATA=SYS_FETCH($REC_TIPE)){

										if($aREC_GR_DATA['KODE_GDG']==$cFILTER_GUDANG){
											echo "<option value='$aREC_GR_DATA[KODE_GDG]' selected='$REC_EDIT[KODE_GDG]' >$aREC_GR_DATA[NAMA_GDG]</option>";
										} else {
											echo "<option value='$aREC_GR_DATA[KODE_GDG]'  >$aREC_GR_DATA[NAMA_GDG]</option>";
										}

									}
								?>
								</select>

								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<table id="example" class="display table table-hover table-condensed" cellspacing="0">
												<thead>
													<tr>
														<th style="background-color:LightGray;width: 1px;"></th>	
														<th style="background-color:LightGray;"><?php echo $cFAKTUR?></th>
														<th style="background-color:LightGray;"><?php echo $cTANGGAL?></th>
														<th style="background-color:LightGray;"><?php echo $cJAM_JUAL?></th>
														<th style="background-color:LightGray;"><?php echo $cNAMA_TBL?></th>
														<th style="background-color:LightGray;"><?php echo $cJUMLAH?></th>
														<th style="background-color:LightGray;"><?php echo $cHARGA?></th>
														<th style="background-color:LightGray;"><?php echo $cNILAI?></th>
														<th style="background-color:LightGray;"><?php echo $cPROMO?></th>
														<th style="background-color:LightGray;"><?php echo $cTOTAL?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($r_JUAL2=SYS_FETCH($q_JUAL2)) {
															echo '<tr>';
																echo '<td class=""><div class="star"><i class="fa fa-user icon-xs icon-default"></i></div></td>';
	//															echo '<td style="width: 1px;"></td>';
																echo "<td><span>".$r_JUAL2['KODE_BARANG']."  </span></td>";
																echo "<td><span>".decode_string($r_JUAL2['NAMA_BRG'])." </span></td>";
																echo "<td><span>".decode_string($r_JUAL2['NAMA_GDG'])." </span></td>";
																echo "<td style='text-align:right;'><span>".$r_JUAL2['STOCK_CTN']." </span></td>";
																echo "<td style='text-align:right;'><span>".$r_JUAL2['ADJ']."  </span></td>";
																echo "<td style='text-align:right;'><span>".$r_JUAL2['STA_CTN']."  </span></td>";
																echo "<td style='text-align:right;'><span>".$r_JUAL2['MSKB_CTN']."  </span></td>";
																echo "<td style='text-align:right;'><span>".$r_JUAL2['MSKP_CTN']."  </span></td>";
																echo "<td style='text-align:right;'><span>".$r_JUAL2['RJUAL']."  </span></td>";
															echo '</tr>';
															$tJML_STOCK += $r_JUAL2['STOCK_CTN'];
															$tJML_ADJSM += $r_JUAL2['ADJ'];
															$tSTOK_AWAL += $r_JUAL2['STA_CTN'];
															$tSTOK_BELI += $r_JUAL2['MSKB_CTN'];
															$tMSK_PNDAH += $r_JUAL2['MSKP_CTN'];
															$tJML_RJUAL += $r_JUAL2['RJUAL'];
															$tJML_JUAL += $r_JUAL2['JUAL_CTN'];
															$tKLR_PNDAH += $r_JUAL2['KLRP_CTN'];
															$tRTR_BELI += $r_JUAL2['RETUR'];
														}
													?>
												</tbody>
												<tr></tr>
												<tr>
													<td style="font-size: 16px;color: Brown;background-color: LightGray ;"></td>
													<td style="font-size: 16px;color: Brown;background-color: LightGray ;"></td>
													<td style="font-size: 16px;color: Brown;background-color: LightGray ;"></td>
													<td style="font-size: 16px;color: Brown;background-color: LightGray ;"></td>
													<td style="font-size: 16px;color: Brown;background-color: LightGray ;"></td>
													<td style="font-size: 16px;color: Brown;background-color: LightGray ;">Total</td>
													<td style="font-size: 16px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($tJML_STOCK)?></td>
													<td style="font-size: 16px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($tJML_ADJSM)?></td>
													<td style="font-size: 16px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($tSTOK_AWAL)?></td>
													<td style="font-size: 16px;color: Brown;background-color: LightGray ;"></td>
												</tr>
												<td></td><td></td><td></td>
												<tr></tr>	
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
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
			<script src="assets/js/scripts.js" type="text/javascript"></script> 

			<div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>
						</div>
						<div class="modal-footer">
							<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						</div>
					</div>
				</div>
			</div>
		</body>
	</html>

<?php  
//Tutup koneksi engine MySQL
	mysql_close($DB2);
?>
<script>
function FILTER_DATA(pBRANCH, pDATE) {
	window.location.assign("?_b="+pBRANCH + "&_d="+pDATE);
}

</script>

