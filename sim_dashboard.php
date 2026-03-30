<?php
// sim_dashboard.php
// Simpada dashboard
// include chart_flot.html

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cTITLE		= 'Dashboard';
	$cDASH_WP	= S_MSG('SM55','Data NPWPD');
	$cOBJ_PAJAK	= S_MSG('SK59','Objek Pajak');
	$cSKPD		= S_MSG('SK38','SKPD');
	$cPROFIT	= S_MSG('SJ30','Surplus');
	$cFILE_LOGO_COMP = 'data/images/'. 'LOGO1_'.$cFILTER_CODE.'.jpg';
	
	$q_NPWPD =SYS_QUERY("select NPWPD_NO from npwpd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nNPWPD = number_format(SYS_ROWS($q_NPWPD));

	$q_OBJ_PJK =SYS_QUERY("select NPWPD_NO from obj_pjk where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nOBJ_PJK = number_format(SYS_ROWS($q_OBJ_PJK));
	$nSKPD = 0;
	$q_SKPD=SYS_QUERY("select * from skpd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	while($r_SKPD=SYS_FETCH($q_SKPD)) {
		$nSKPD += $r_SKPD['JML_PAJAK'];
	}
/*
	$q_PINJAMAN=SYS_QUERY("select * from masuk1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	while($aREC_PINJAMAN=SYS_FETCH($q_PINJAMAN)) {
		$nSKPD += $aREC_PINJAMAN['JUMLAH'];
	}
*/
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>

        <!-- CORE CSS FRAMEWORK - -->
        <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>

        <!-- CORE CSS TEMPLATE - -->
        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>

	  <!-- Custom CSS from SB -->
		<link href="sb/css/bootstrap.min.css" rel="stylesheet">
		<link href="sb/css/sb-admin-2.css" rel="stylesheet">
		<link href="sb/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		<div class="page-container row-fluid">
			
			<div class="page-sidebar ">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper">
					<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
				</div>
				<div class="project-info"></div>
			</div>

			<section id="main-content" class=" ">
				<section class="wrapper main-wrapper" style=''>

					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="page-title">
							<div class="pull-left">
								  <h1 class="title"><?php echo $cTITLE?></h1>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<section class="box ">
							<div class="pull-right hidden-xs"></div>

							<div class="content-body">
								<div class="row">
								
<!-- ================================================================================================= --> 
									<div class="col-lg-3 col-md-6" >
										<a href="sim_tb_npwpd.php">
											<div class="panel panel-primary">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
															<i class="fa fa-user icon-lg animated fadeIn"></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo $nNPWPD?></div>
															<div><?php echo $cDASH_WP?></div>
														</div>
													</div>
												</div>
												<div class="panel-footer">
													<span class="pull-left">View Details</span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</div>
										</a>
									</div>
									<div class="col-lg-3 col-md-6">
										<div class="panel panel-green">
											<a href="sim_tb_objek_pajak.php">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
															<i class="fa fa-download icon-lg"></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo $nOBJ_PJK?></div>
															<div><?php echo $cOBJ_PAJAK?></div>
														</div>
													</div>
												</div>
												<div class="panel-footer">
													<span class="pull-left">View Details</span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>
									<div class="col-lg-3 col-md-6">
										<div class="panel panel-yellow">
											<a href="sim_tb_skpd.php">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
															<i class="fa  fa-external-link fa-3x"></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo number_format(SYS_ROWS($q_SKPD))?></div>
															<div><?php echo $cSKPD?></div>
														</div>
													</div>
												</div>
												<div class="panel-footer">
													<span class="pull-left"><?php echo number_format($nSKPD)?></span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>
									<div class="col-lg-3 col-md-6">
										<div class="panel panel-red">
											<a href="#">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
															<i class='fa fa-support fa-3x icon-rounded icon-danger inviewport animated  animated-delay-2400ms animated-duration-1400ms' data-vp-add-class='visible rotateIn'></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge">0</div>
															<div><?php echo $cPROFIT?></div>
														</div>
													</div>
												</div>
												<div class="panel-footer">
													<span class="pull-left">View Details</span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>
<!-- ========================================== --> 

<!-- =========== pie chart from ultra =============================== 

                                    <div class="col-md-12 col-sm-12 col-xs-12">


                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                            <div class="flot-demo-container">
                                                <div id="flotpie" class="flot-demo-placeholder"></div>
                                            </div>
                                        </div>	
                                        <div class=" col-md-4 col-sm-4 col-xs-4">
                                            <div id="flotpiemenu">
                                                <button class="btn btn-success" id="pieexample-1">Default Options</button>
                                                <button class="btn btn-default" id="pieexample-2">Without Legend</button>
                                                <button class="btn btn-default" id="pieexample-3">Label Formatter</button>
                                                <button class="btn btn-default" id="pieexample-4">Label Radius</button>
                                                <button class="btn btn-default" id="pieexample-5">Label Styles #1</button>
                                                <button class="btn btn-default" id="pieexample-6">Label Styles #2</button>
                                                <button class="btn btn-default" id="pieexample-7">Hidden Labels</button>
                                                <button class="btn btn-default" id="pieexample-8">Combined Slice</button>
                                                <button class="btn btn-default" id="pieexample-9">Rectangular Pie</button>
                                                <button class="btn btn-default" id="pieexample-10">Tilted Pie</button>
                                                <button class="btn btn-default" id="pieexample-11">Donut Hole</button>
                                                <button class="btn btn-default" id="pieexample-12">Interactivity</button>
                                            </div>
                                        </div>

                                    </div>

 ========================================== --> 
								</div>
							</div>
						</section>
					</div>

				</section>
			</section>
			<!-- END CONTENT -->
			<?php	include "scr_chat.php";	?>
 		</div>
		<?php	require_once("js_framework.php");	?>

		<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
		<!--
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
        
		<script src="assets/plugins/flot-chart/jquery.flot.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.fillbetween.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.navigate.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.pie.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.stack.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.crosshair.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.time.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.selection.js" type="text/javascript"></script>
		<script src="assets/js/chart-flot.js" type="text/javascript"></script><script src="assets/plugins/icheck/icheck.min.js" type="text/javascript"></script>

		<!-- CORE TEMPLATE JS -  --> 
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
		<script src="assets/js/chart-sparkline.js" type="text/javascript"></script>
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

