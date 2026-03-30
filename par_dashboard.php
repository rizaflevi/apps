<?php
// par_dashboard.php
// Membership
// include chart_flot.html

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cTITLE		= 'Dashboard';
	$cANGGOTA	= S_MSG('K000','Anggota');
	$cEVENTS	= S_MSG('CE01','Events');
	$cPILKADA	= S_MSG('SN50','Pilkada');
	// $cPROFIT	= S_MSG('RF00','Profit');
	$cFILE_LOGO_COMP = 'data/images/'. 'LOGO1_'.$cFILTER_CODE.'.jpg';
	
	$q_ANGGOTA=SYS_QUERY("select * from member where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nANGGOTA = number_format(SYS_ROWS($q_ANGGOTA));

	$q_EVENTS=SYS_QUERY("select * from events where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nEVENTS = 0;
	while($aREC_EVENTS=SYS_FETCH($q_EVENTS)) {
		$nEVENTS += $aREC_EVENTS['SV_BALANCE'];
	}

/*	$q_PILKADA=SYS_QUERY("select * from tr_loan1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nPILKADA = 0;
	while($aREC_PILKADA=SYS_FETCH($q_PILKADA)) {
		$nPILKADA += $aREC_PILKADA['LOAN_VAL'];
	}
*/
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>

        <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>

		<link href="sb/css/bootstrap.min.css" rel="stylesheet">
		<link href="sb/css/sb-admin-2.css" rel="stylesheet">
		<link href="sb/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		<div class="page-container row-fluid">
			
			<div class="page-sidebar ">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper">
					<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
				</div>
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
									<div class="col-lg-3 col-md-6">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
														<i class="fa fa-user icon-lg animated fadeIn"></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge"><?php echo $nANGGOTA?></div>
														<div><?php echo $cANGGOTA?></div>
													</div>
												</div>
											</div>
											<a href="tb_anggota.php">
												<div class="panel-footer">
													<span class="pull-left">View Details</span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>
									<div class="col-lg-3 col-md-6">
										<div class="panel panel-green">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
														<i class="fa fa-download icon-lg"></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge"><?php echo number_format(SYS_ROWS($q_EVENTS))?></div>
														<div><?php echo $cEVENTS?></div>
													</div>
												</div>
											</div>
											<a href="kop_teller_EVENTS.php">
												<div class="panel-footer">
													<span class="pull-left"><?php echo number_format($nEVENTS)?></span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>
									<div class="col-lg-3 col-md-6">
										<div class="panel panel-yellow">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
														<i class="fa  fa-external-link fa-3x"></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge"><?php echo number_format(SYS_ROWS($q_PILKADA))?></div>
														<div><?php echo $cPILKADA?></div>
													</div>
												</div>
											</div>
											<a href="kop_teller_PILKADA.php">
												<div class="panel-footer">
													<span class="pull-left"><?php echo number_format($nPILKADA)?></span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>
<!--
									<div class="col-lg-3 col-md-6">
										<div class="panel panel-red">
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
											<a href="#">
												<div class="panel-footer">
													<span class="pull-left">View Details</span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>
 ========================================== --> 

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
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
        
		<!--
		<script src="assets/plugins/flot-chart/jquery.flot.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.fillbetween.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.navigate.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.pie.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.stack.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.crosshair.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.time.js" type="text/javascript"></script>
		<script src="assets/plugins/flot-chart/jquery.flot.selection.js" type="text/javascript"></script>
		<script src="assets/js/chart-flot.js" type="text/javascript"></script><script src="assets/plugins/icheck/icheck.min.js" type="text/javascript"></script>

		<!-- CORE TEMPLATE JS - START --> 
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

