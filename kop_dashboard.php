<?php
// kop_dashboard.php
// Koperasi
// include chart_flot.html

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cTITLE		= 'Dashboard';
	$cANGGOTA	= S_MSG('K000','Anggota');
	$cSIMPANAN	= S_MSG('KA00','Simpanan');
	$cPINJAMAN	= S_MSG('KC00','Pinjaman');
	$cPROFIT	= S_MSG('RF00','Profit');
	$cFILE_LOGO_COMP = 'data/images/'. 'LOGO1_'.$cFILTER_CODE.'.jpg';
	
	$q_ANGGOTA=OpenTable('Member',  "A.APP_CODE='$cFILTER_CODE' and A.DELETOR=''");
//	$q_ANGGOTA=SYS_QUERY("select * from tb_member1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nANGGOTA = number_format(SYS_ROWS($q_ANGGOTA));

	$q_SIMPANAN=SYS_QUERY("select * from tr_save1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nSIMPANAN = 0;
	while($aREC_SIMPANAN=SYS_FETCH($q_SIMPANAN)) {
		$nSIMPANAN += $aREC_SIMPANAN['SV_BALANCE'];
	}

	$q_PINJAMAN=SYS_QUERY("select * from tr_loan1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nPINJAMAN = 0;
	while($aREC_PINJAMAN=SYS_FETCH($q_PINJAMAN)) {
		$nPINJAMAN += $aREC_PINJAMAN['LOAN_VAL'];
	}
	
	$cDBOARD_SIZE = S_PARA('_DASHBOARD_SIZE','col-lg-3 col-md-6');

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
        <!-- CORE CSS FRAMEWORK - END -->

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START 
        <link href="assets/plugins/icheck/skins/minimal/minimal.css" rel="stylesheet" type="text/css" media="screen"/>        
		  <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 

        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
		<?php /* <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>	*/ ?>

	  <!-- Custom CSS from SB -->
		<link href="sb/css/bootstrap.min.css" rel="stylesheet">
		<link href="sb/css/sb-admin-2.css" rel="stylesheet">
		<link href="sb/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		<div class="page-container row-fluid">
			
			<div class="page-sidebar ">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper">
					<?php require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
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
									<div class="<?php echo $cDBOARD_SIZE?>">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<a href="tb_anggota.php">
													<div style="color: white;" class="row">
														<div class="col-xs-3">
															<i class="fa fa-user icon-lg animated fadeIn"></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo $nANGGOTA?></div>
															<div><?php echo $cANGGOTA?></div>
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
									<div class="<?php echo $cDBOARD_SIZE?>">
										<div class="panel panel-green">
											<a href="kop_teller_simpanan.php">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
															<i class="fa fa-download icon-lg"></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo number_format(SYS_ROWS($q_SIMPANAN))?></div>
															<div><?php echo $cSIMPANAN?></div>
														</div>
													</div>
												</div>
												<div class="panel-footer">
													<span class="pull-left"><?php echo number_format($nSIMPANAN)?></span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>
									<div class="<?php echo $cDBOARD_SIZE?>">
										<div class="panel panel-yellow">
											<a href="kop_teller_pinjaman.php">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
														<i class="fa  fa-external-link fa-3x"></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge"><?php echo number_format(SYS_ROWS($q_PINJAMAN))?></div>
														<div><?php echo $cPINJAMAN?></div>
													</div>
												</div>
											</div>
												<div class="panel-footer">
													<span class="pull-left"><?php echo number_format($nPINJAMAN)?></span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>
									<div class="<?php echo $cDBOARD_SIZE?>">
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
			<?php include "scr_chat.php"; ?>
		</div>
		<?php require_once("js_framework.php"); ?>

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
 OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


		<script src="assets/js/scripts.js" type="text/javascript"></script> 

		<!-- Sidebar Graph - START 
		<script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
		<script src="assets/js/chart-sparkline.js" type="text/javascript"></script>
		<!-- Sidebar Graph - END --> 

		<script src="sys_js.js" type="text/javascript"></script> 
		</body>
	</html>

