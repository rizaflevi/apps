<?php
// blank.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('RQ92','PT. Yaza Pratama');

	$cNMR_METER 	= S_MSG('RQ04','Nomor Meter');

	$cSUBMIT=S_MSG('F305','OK');
	$cCANCEL=S_MSG('F302','Close');

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
								<form action ="bm_view.php" method="post">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="col-xs-10 form-label-700" style="font-size:150%;"><?php echo $cNMR_METER?></label>
                                            <a class="col-xs-2 bg-warning" data-toggle="modal" href="barcode/index.php"><i class="fa fa-camera"></i></a><br>
											<span class="desc"></span>
											<div class="form-group">
												<input type="number" class="form-control col-lg-12 form-label-900"  style="padding:0 5px;font-size:200%;" name="ADD_NOMOR_KWH">
											</div>
										</div><br><br>

										<div class="form-group">
											<input type="submit" style="width:40%" class="btn btn-info btn-lg" value=<?php echo $cSUBMIT?>>
											<input type="button"  style="width:30%" class="btn btn-orange btn-lg" value=<?php echo $cCANCEL?>  onclick=window.location.href='bm_dashboard.php'>
										</div>
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

			<div class="modal" id="bm_camera" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo 'Scan barcode pelanggan'?></h4>
						</div>
						<div class="modal-body">
							<?php echo 'Masih dalam pengembangan'?>
						</div>
						<div class="modal-footer">
							<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						</div>
					</div>
				</div>
			</div>
		</body>
	</html>

