<?php
// pos.php		pos dashboard

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cMODE=(isset($_GET['_o']) ? $_GET['_o'] : '');
$cAPP_CODE = $_SESSION['data_FILTER_CODE'];

$cTITLE		= 'Table List';
switch($cMODE) {
	case 'd': 
		$cTITLE='Delivery';
		break;
	case 't': 
		$cTITLE='Take Away';
		break;
}

?>
<!DOCTYPE html>
<html class=" ">
	<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
  <!-- Custom CSS from SB -->
	<link href="sb/css/bootstrap.min.css" rel="stylesheet">
	<link href="sb/css/sb-admin-2.css" rel="stylesheet">
	<link href="sb/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<body oncontextmenu="return false;" class="sidebar-collapse">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
		<div class="page-container row-fluid">
			
			<div class="page-sidebar collapseit">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper">
					<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
				</div>
				<div class="project-info"></div>
			</div>

			<section id="main-content"  class="sidebar_shift">
				<section class="wrapper main-wrapper" style=''>

					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 page-title">
							<div class="pull-left">
								  <h1 class="title"><?php echo $cTITLE?></h1>
							</div>
						</div>
						<div class="text-left"></div>
						<input type="button" class="btn btn-dark btn-lg" style="font-size:30px; padding:10px 20px; color:green; width:133px;" value="Back" onclick="window.location.href='prs_dashboard.php'"/>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<section class="box ">
							<div class="pull-right hidden-xs">	</div>

							<div class="content-body">
								<div class="row">
								<?php
								switch($cMODE) {
									default:

										// $qPOS_TABLE=OpenTable('PosTable');
										$qPOS_TABLE=OpenTable('PosTblOrder', "A.APP_CODE='$cAPP_CODE'", '', 'TABLE_CODE');
										while($aTABLE=SYS_FETCH($qPOS_TABLE)) {
											$nVALUE = $aTABLE['ORDER_VALUE'];
											$cCOLOR = ($nVALUE > 0) ? 'red' : 'white';
											echo '<div class="col-lg-1 col-md-2 col-sm-4 col-xs-6">';
												echo '<a href="pos_entry.php?_t='.$aTABLE['TABLE_CODE'].'&_o='.$cMODE.'">';
													echo '<div class="panel panel-primary">
														<div class="panel-heading">
															<div class="row">
																	<div class="huge" style="color:' . $cCOLOR . '; margin: 0 auto; display: block; width: 100%; text-align : center;">' .$aTABLE['TABLE_CODE'].'</div>
															</div>
														</div>
													</div>
												</a>
											</div>';
										}
									break;

								case 'd': 
									$nDLVRY = 0;
									$q_SO=OpenTable('So1', "SO_VOID=0 and TEAM='d' and FAKTUR='' and APP_CODE='$cAPP_CODE'");
									while($aDLVRY=SYS_FETCH($q_SO)) {
										$nDLVRY++;
										echo '<div class="col-lg-1 col-md-3">';
											echo '<a href="pos_entry.php?_o=d&_t='.$aDLVRY['TABLE_CODE'].'">';
												echo '<div class="panel panel-primary">
													<div class="panel-heading">
														<div class="row">
															<div class="col-xs-9 text-right">
																<div class="huge" style="color:red;">'.$aDLVRY['TABLE_CODE'].'</div>
															</div>
														</div>
													</div>
												</div>
											</a>
										</div>';
									}
									$nDLVRY++;
									$cDLVRY = 'D'.$nDLVRY;
										echo '<div class="col-lg-1 col-md-3">';
											echo '<a href="pos_entry.php?_o=d&_t='.$cDLVRY.'">';
												echo '<div class="panel panel-primary">
													<div class="panel-heading">
														<div class="row">
															<div class="col-xs-9 text-right">
																<div class="huge">'.$cDLVRY.'</div>
															</div>
														</div>
													</div>
												</div>
											</a>
										</div>';
									break;
								case 't': 
									$nTAKE = 0;
									$q_SO=OpenTable('So1', "SO_VOID=0 and TEAM='t' and FAKTUR='' and APP_CODE='$cAPP_CODE'");
									while($aTAKE_AWAY=SYS_FETCH($q_SO)) {
										$nTAKE++;
										echo '<div class="col-lg-1 col-md-3">';
											echo '<a href="pos_entry.php?_o=d&_t='.$aTAKE_AWAY['TABLE_CODE'].'">';
												echo '<div class="panel panel-primary">
													<div class="panel-heading">
														<div class="row">
															<div class="col-xs-9 text-right">
																<div class="huge" style="color:red;">'.$aTAKE_AWAY['TABLE_CODE'].'</div>
															</div>
														</div>
													</div>
												</div>
											</a>
										</div>';
									}
									$nTAKE++;
									$cTAKE_AWAY = 'T'.$nTAKE;
										echo '<div class="col-lg-1 col-md-3">';
											echo '<a href="pos_entry.php?_o=t&_t='.$cTAKE_AWAY.'">';
												echo '<div class="panel panel-primary">
													<div class="panel-heading">
														<div class="row">
															<div class="col-xs-9 text-right">
																<div class="huge">'.$cTAKE_AWAY.'</div>
															</div>
														</div>
													</div>
												</div>
											</a>
										</div>';
									break;
								}
								?>
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
		<script src="assets/js/chart-flot.js" type="text/javascript"></script><script src="assets/plugins/icheck/icheck.min.js" type="text/javascript"></script> --> 
		<!-- CORE TEMPLATE JS -  --> 
		<script src="assets/js/scripts.js" type="text/javascript"></script> 

		<!-- Sidebar Graph - --> 
		<script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
		<script src="assets/js/chart-sparkline.js" type="text/javascript"></script>

		<!-- My defined java script  --> 
		<script src="sys_js.js" type="text/javascript"></script> 

	</body>
</html>
