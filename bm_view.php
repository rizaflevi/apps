<?php
// blank.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('RQ92','PT. Yaza Pratama');

//	$cNMR_METER 	= S_MSG('RQ04','Nomor Meter');
	$cNAMA_TBL 		= S_MSG('PQ03','Nama Pelanggan');
	$cALAMAT 		= S_MSG('PQ04','Alamat');
	$cRUBAH_ALM		= S_MSG('RQ37','Perubahan alamat');
	$cMCB			= S_MSG('RQ40','MCB Terpasang');

	$cSUBMIT=S_MSG('F305','OK');
	$cCANCEL=S_MSG('F306','Batal');

	$cNMR_METER = encode_string($_POST['ADD_NOMOR_KWH']);
	if($cNMR_METER==''){
		$cMSG_BLANK	= S_MSG('PQ71','Nomor Meter tidak boleh kosong');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	
	$cPELANGGAN = "select bm_tb_pel.IDPEL, bm_tb_pel.NAMA_PEL, bm_tb_pel.ALAMAT, bm_tb_pel.KODE_TARIF, bm_tb_pel.DAYA, bm_tb_pel.NOMOR_KWH,
		bm_tb_plg.IDPEL, bm_tb_plg.ALAMAT_PLG
		from bm_tb_pel 
		LEFT JOIN bm_tb_plg ON bm_tb_pel.IDPEL=bm_tb_plg.IDPEL
		where bm_tb_pel.APP_CODE='$cFILTER_CODE' and bm_tb_pel.DELETOR='' and bm_tb_pel.NOMOR_KWH='$cNMR_METER'";
	$q_PELANGGAN=SYS_QUERY($cPELANGGAN);
	$n_PELANGGAN=SYS_ROWS($q_PELANGGAN);
//	var_dump($n_PELANGGAN);	exit();
	if($n_PELANGGAN==0){
		$cMSG_NFOUND=S_MSG('PQ72','Nomor Meter tidak ada di data Pelanggan');
		echo "<script> alert('$cMSG_NFOUND');	window.history.back();	</script>";
		return;
	}
	$a_PELANGGAN=SYS_FETCH($q_PELANGGAN);
	
	$_SESSION['SNMR_METER']	= $cNMR_METER;
	$_SESSION['SKODE_PLGN']	= $a_PELANGGAN['IDPEL'];
	$_SESSION['SNAMA_PLGN']	= $a_PELANGGAN['NAMA_PEL'];
	
	$cDATA_ALAMAT = $a_PELANGGAN['ALAMAT'];
	if($a_PELANGGAN['ALAMAT_PLG']!='') {
		$cDATA_ALAMAT = $a_PELANGGAN['ALAMAT_PLG'];
	}
?>
	<!DOCTYPE html>
	<html class="login_page">
		<?php	require_once("scr_headtr.php");	?>
		<body class=" ">
			<div class="page-container row-fluid">
					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="page-title">
							<div class="pull-left">	</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<section class="box ">
							<div class="pull-right hidden-xs"></div>

							<div class="content-body">
								<div class="row">

<!--											<h4>Primary</h4>	-->
									<form action ="bm_entry.php" method="post">
										<div class="col-md-12">

											<div class="panel-group primary" id="accordion-2" role="tablist" aria-multiselectable="true">
												<div class="panel panel-default">
													<div class="panel-heading" role="tab" id="headingOne">
														<h4 class="panel-title">
															<a data-toggle="collapse" data-parent="#accordion-2" href="#collapseOne-2" aria-expanded="true" aria-controls="collapseOne-2">
																<i class='fa fa-check form-label-900' ></i> <font size="6" ><?php echo $cNMR_METER?></font>
															</a>
														</h4>
													</div>
													<div id="collapseOne-2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
														<div class="panel-body">

															<div class="form-group">
																<div class="controls">
																	<input type="text" class="form-control col-lg-12 form-label-900"  style="padding:0 5px;font-size:200%;" value="<?php echo $a_PELANGGAN['IDPEL']?>" readonly>
																</div><div class="clearfix"></div>
																<div class="controls">
																	<input type="text" class="form-control col-lg-12 form-label-900"  style="padding:0 5px;font-size:200%;" value="<?php echo $a_PELANGGAN['NAMA_PEL']?>" readonly>
																</div><div class="clearfix"></div>
																<div class="controls">
																	<textarea class="form-control col-lg-12 form-label-900"  style="padding:0 5px;font-size:125%;" readonly><?php echo $cDATA_ALAMAT?></textarea> 
																</div>

																<div class="form-group">
																	<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cRUBAH_ALM?></label><br>
																	<textarea class="form-control col-sm-12 form-label-900" style="font-size:150%;" name="UPD_ALAMAT"></textarea> 
																</div>

																<div class="form-group">
																	<label class="col-xs-6 form-label-700" for="field-1"><?php echo $cMCB?></label>
											<select name="UPD_KODE_TRF" class="col-xs-6 form-label-900" style="font-size:200%;">
												<?php 
													$q_TB_TARIF =SYS_QUERY("select KODE_TRF from bm_tarif where APP_CODE='$cFILTER_CODE' and DELETOR=''");
				//									echo "<option value=''  > All</option>";
													while($r_BM_TARIF =SYS_FETCH($q_TB_TARIF)){

														if($r_BM_TARIF['KODE_TRF']==$a_PELANGGAN['KODE_TARIF']){
															echo "<option value='".$r_BM_TARIF['KODE_TRF']. "' selected='$a_PELANGGAN[KODE_TARIF]' >$a_PELANGGAN[KODE_TARIF]</option>";
														} else {
															echo "<option value='".$r_BM_TARIF['KODE_TRF']. "'  >$r_BM_TARIF[KODE_TRF]</option>";
														}
													}
												?>
											</select><br>
<!--												<input type="text" class="col-xs-6 form-label-900" value="<?php echo $a_PELANGGAN['KODE_TARIF'].' / '.$a_PELANGGAN['DAYA']?>"><br>	-->
																</div>
															</div><br><br>

														</div>
													</div>
												</div>

											</div>

										</div>

										<div class="text-left">
											<input type="submit" style="width:40%" class="btn btn-info btn-lg" value=<?php echo $cSUBMIT?>> <!--  onclick=window.location.href='bm_entry.php'>	-->
											<input type="button" style="width:30%" class="btn btn btn-orange btn-lg" value=<?php echo $cCANCEL?> onclick=self.history.back()>
										</div>
									</form>
								</div>
							</div>
						</section>
					</div>
		</div>
		<?php	require_once("js_framework.php");	?>
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

