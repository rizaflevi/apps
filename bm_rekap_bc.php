<?php
//	bm_rekap_bc.php
//	Laporan rekap baca meter per rayon

//	include "preloader.php";
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('RQ71','Laporan Rekap Baca Meter per Rayon');

	$cTANGGAL 		= S_MSG('RQ03','Tanggal');

	$cKD_AREA	= S_MSG('TA01','Kode Area');
	$cAREA 		= S_MSG('TA02','Nama Area');
//	$cAREA 			= S_MSG('PQ05','Area');

	$NAMA_BLN=array(1=> "Januari", "Februari", "Maret", "April", "Mei", 
                      "Juni", "Juli", "Agustus", "September", 
                      "Oktober", "November", "Desember");
	$t_BACA =array(1=> 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
	$THN='2017';
	$dPERIOD1 = S_PARA('BM_START_DATE', date('Y-01-01'));
	$dPERIOD2=date("Y-m-d");

	if (isset($_GET['_t1'])) {
		$dPERIOD1=$_GET['_t1'];
	}

	if (isset($_GET['_t2'])) {
		$dPERIOD2=$_GET['_t2'];
	}

//	$r_TB_CATTER	= SYS_FETCH(SYS_QUERY("select * from bm_tb_catter1 where APP_CODE='$cFILTER_CODE' and DELETOR=''"));

	$cHELP_BOX	= S_MSG('RQ7A','Help Laporan Rekap Baca Meter per Rayon');
	$cHELP_1	= S_MSG('RQ7B','Ini adalah modul untuk menampilkan Laporan Rekap Baca Meter dengan per Rayon untuk periode tertentu');
	$cHELP_2	= S_MSG('RQ7C','Laporan ini memuat rekap hasil pembacaan meter untuk setiap rayon');
	$cHELP_3	= S_MSG('RQ7D','Hasil pembacaan per rayon ini adalah akumulasi pembacaan semua petugas');
	$cHELP_4	= S_MSG('RQ25','Untuk menampilkan data tanggal tertentu, klik di bagian tanggal mulai dan tanggal akhir.');
	$cHELP_5	= S_MSG('RQ26','Untuk meng export laporan ke excel, klik tombol EXCEL.');

//	var_dump($cSQLCOMMAND);	exit();

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	$cRPT_BODY_CLASS = S_PARA('_RPT_BODY_CLASS','sidebar-collapse');

	$cACTION='';
	if (isset($_GET['_e'])) {
		$cACTION=$_GET['_e'];
	}

	APP_LOG_ADD($cHEADER, 'view');

switch($cACTION){
	default:
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<body class="<?php echo $cRPT_BODY_CLASS?>">
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

						<div class="col-xs-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left"><?php echo $cHEADER?></h2>
									<div class="actions panel_actions pull-right">
										<i class="box_setting fa fa-question" data-toggle="modal" href="#section-settings">Help</i>
									</div>
								</header>

								<label class="col-sm-1 form-label-700" for="field-4"><?php echo $cTANGGAL?></label>
								<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $dPERIOD1?>" onchange="FILTER_DATA('this.value, '<?php echo $dPERIOD2?>')">

								<label class="col-sm-1 form-label-700" style="text-align: right"><?php echo S_MSG('RS14','s/d')?></label>
								<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $dPERIOD2?>" onchange="FILTER_DATA('<?php echo $dPERIOD1?>', this.value)"><br>
								<div class="clearfix"></div>

								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="<?php echo S_PARA('_BIG_REPORT_CLASS','table-responsive')?> data-pattern='priority-columns'">
											<table id="example-4" class="<?php echo S_PARA('_DISP_REPORT_ABSEN','display')?>">
<!--                                            <table cellspacing="0" id="myTable" class="table table-small-font table-bordered table-striped">	-->
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKD_AREA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cAREA?></th>
														<?php
															$nBLN = 0;
															while($nBLN<intval(substr($dPERIOD2,5,2))) {
																$nBLN ++;
																echo '<th style="'.$cHDR_BACK_CLR.';text-align:right;">'. $NAMA_BLN[$nBLN]. '</th>';
															
															}
														?>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo S_MSG('RQ96','Target')?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo S_MSG('RQ97','Terbaca')?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;">%</th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo S_MSG('RQ98','Blm Terbaca')?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														$t_TARGET = 0;
														$t_TERBACA = 0;
														$t_PERSEN = 0;
														$t_BLM_TERBACA = 0;
														$t_CAPAIAN = 0;
														$q_TB_AREA=SYS_QUERY("select KODE_AREA, NAMA_AREA from tb_area where APP_CODE='$cFILTER_CODE' and DELETOR=''");
														while($a_TB_AREA =SYS_FETCH($q_TB_AREA)){

															$q_PELANGGAN = SYS_QUERY("select * from bm_tb_pel where UNITUP ='$a_TB_AREA[KODE_AREA]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
															$n_PELANGGAN = SYS_ROWS($q_PELANGGAN);
															$q_TB_PTGS=SYS_QUERY("select * from bm_tb_catter1 where KODE_AREA='$a_TB_AREA[KODE_AREA]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
															$n_CAPAIAN = 0;
															$n_TARGET = 0;
															while($a_TB_PTGS =SYS_FETCH($q_TB_PTGS)){
																$n_TARGET += $a_TB_PTGS['TARGET'];
															}
															$t_TARGET += $n_PELANGGAN;
															echo '<tr>';
															echo "<td><span>".$a_TB_AREA['KODE_AREA']."  </span></td>";
															echo "<td><span>".$a_TB_AREA['NAMA_AREA']."  </span></td>";
															$nBLN = 0;	$n_JML_BLN=0;
															while($nBLN < intval(substr($dPERIOD2,5,2))) {
																$nBLN ++;
																$cSQLCOMMAND= "SELECT bm_dt_baca.TGL_BACA, bm_dt_baca.IDPEL, bm_dt_baca.PETUGAS, bm_dt_baca.KODE_RBM, C.IDPEL, C.UNITUP
																from bm_dt_baca
																	inner join (select UNITUP, IDPEL FROM bm_tb_pel where UNITUP='$a_TB_AREA[KODE_AREA]' and APP_CODE='$cFILTER_CODE' and DELETOR='') C ON C.IDPEL=bm_dt_baca.IDPEL 
																	where bm_dt_baca.APP_CODE='$cFILTER_CODE' and bm_dt_baca.DELETOR='' and
																		left(bm_dt_baca.TGL_BACA,10)>='".$dPERIOD1."' and left(bm_dt_baca.TGL_BACA,10)<='".$dPERIOD2."'";
																$cSQLCOMMAND.= " and month(TGL_BACA)= ".$nBLN. " and year(TGL_BACA)= ". $THN;
																$qQUERY=SYS_QUERY($cSQLCOMMAND);
																
																$n_JMLBLN = SYS_ROWS($qQUERY);
																$n_JML_BLN += $n_JMLBLN;

																echo "<td align='right'><span>".number_format($n_JMLBLN)." </span></td>";
																$t_BACA[$nBLN] += $n_JMLBLN;
															}
															$t_TERBACA += $n_JML_BLN;
															if($n_PELANGGAN>0) {
																$n_CAPAIAN = $n_JML_BLN / $n_PELANGGAN * 100;
															}
															echo "<td align='right'><span>".number_format($n_PELANGGAN)." </span></td>";
															echo "<td align='right'><span>".number_format($n_JML_BLN)." </span></td>";
															echo "<td align='right'><span>".number_format($n_CAPAIAN, 2)." </span></td>";
															echo "<td align='right'><span>".number_format($n_PELANGGAN- $n_JML_BLN)." </span></td>";
															echo '</tr>';
															$t_BLM_TERBACA += $t_TARGET - $t_TERBACA;
														}
													?>
												</tbody>
												<tr></tr>
												<tr>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;">Total</td>
													<?php
														$nBLN = 0;
														while($nBLN < intval(substr($dPERIOD2,5,2))) {
															$nBLN ++;
															echo '<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right">'.number_format($t_BACA[$nBLN]).'</td>';
														}
														if($t_TARGET>0) {
															$t_CAPAIAN = $t_TERBACA / $t_TARGET * 100;
														}
													?>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($t_TARGET)?></td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($t_TERBACA)?></td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($t_CAPAIAN,2)?></td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($t_BLM_TERBACA)?></td>
												</tr>
												<td></td><td></td><td></td>
												<tr></tr>	
											</table>

										</div><br>
<!--										<div class="text-left">
											<input type="button" class="col-sm-2 btn btn-info btn-lg" value="Excel" onclick=window.location.href="?_t1=<?php echo $dPERIOD1?>&_t2=<?php echo $dPERIOD2?>&_e=EXCEL">
										</div>	<div class="clearfix"></div><br>
-->
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
			<script src="assets/js/scripts.js" type="text/javascript"></script> 

			<div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_3?></p>
							<p><?php echo $cHELP_4?></p>	<p><?php /* echo $cHELP_5 */?></p>
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
	mysql_close($DB2);	break;

case 'EXCEL' :
	header('Location:bm_rekap_bc.php');
//	header('Location:bm_xls_bc_mtr.php?_t1='.$dPERIOD1.'&_t2='.$dPERIOD2);
	mysql_close($DB2);
}
?>
<script>
function FILTER_DATA(p_TGL1, p_TGL2) {
	window.location.assign("bm_rekap_bc.php?_t1="+p_TGL1 + "&_t2="+p_TGL2);
}

</script>

