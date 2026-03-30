<?php
//	scr_main.php //

//	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
?>

<!DOCTYPE html>
<html class=" ">
	<?php	require_once("scr_header.php");		 require_once("scr_topbar.php");	 ?>
	<body class="sidebar-collapse">
		<div class="page-container row-fluid">
			<div class="page-sidebar ">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
					<?php	require_once("scr_menu.php");	?>
				</div>
				<div class="project-info"></div>
			</div>
			<section id="main-content" class="sidebar_shift">

				<section class="wrapper main-wrapper" style=''>
<!--
					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="page-title">
							<div class="pull-left">
								<h1 class="title">Dashboard</h1>
							</div>
						</div>
					</div>
-->
					<div class="clearfix"></div>
					<div class="col-lg-12">
						<section class="box nobox">
							<div class="content-body">
								<div class="row">
	<!--

									<div class="col-md-3 col-sm-5 col-xs-12">
										 <div class="r1_graph1 db_box">
											  <span class='bold'>98.95%</span>
											  <span class='pull-right'><small>SERVER UP</small></span>
											  <div class="clearfix"></div>
											  <span class="db_dynamicbar">Loading...</span>
										 </div>

										 <div class="r1_graph2 db_box">
											  <span class='bold'>2332</span>
											  <span class='pull-right'><small>USERS ONLINE</small></span>
											  <div class="clearfix"></div>
											  <span class="db_linesparkline">Loading...</span>
										 </div>

										 <div class="r1_graph3 db_box hidden-xs">
											  <span class='bold'>342/123</span>
											  <span class='pull-right'><small>ORDERS / SALES</small></span>
											  <div class="clearfix"></div>
											  <span class="db_compositebar">Loading...</span>
										 </div>
									</div>
-->
<br><br>

									<div class="col-md-10 col-sm-7 col-xs-12">
										 <div class="r1_maingraph db_box" >
											  <span class='pull-left'>
													<i class='icon-purple fa fa-square icon-xs'></i>&nbsp;<small>PAGE VIEWS</small>&nbsp; &nbsp;<i class='fa fa-square icon-xs icon-primary'></i>&nbsp;<small>UNIQUE VISITORS</small>
											  </span>
											  <span class='pull-right switch'>
													<i class='icon-default fa fa-line-chart icon-xs'></i>&nbsp; &nbsp;<i class='icon-secondary fa fa-bar-chart icon-xs'></i>&nbsp; &nbsp;<i class='icon-secondary fa fa-area-chart icon-xs'></i>
											  </span>

											  <div id="db_morris_line_graph" style="height:272px;width:95%;"></div>
											  <div id="db_morris_area_graph" style="height:272px;width:95%;display:none;"></div>
											  <div id="db_morris_bar_graph" style="height:272px;width:95%;display:none;"></div>
										 </div>
									</div>
								</div>


								<div class="row">
									<div class="col-md-4 col-sm-12 col-xs-12">

										<div class="r2_graph1 db_box">
										
											<form id="rickshaw_side_panel">
													<section><div id="legend"></div></section>
											</form>

											  <div id="chart_container" class="rickshaw_ext">
													<div id="chart"></div>
													<div id="timeline"></div>
											  </div>

											  <div id='rickshaw_side_panel' class="rickshaw_sliders">
													<section>
														 <div id="smoother"></div>
													</section>
													<section>
														 <div id="preview" class="rickshaw_ext_preview"></div>
													</section>
											  </div>

										</div>
									</div>

								</div>

								<div class="row">
									<div class="col-md-3 col-sm-6 col-xs-12">
										 <div class="r4_counter db_box">
											  <i class='pull-left fa fa-thumbs-up icon-md icon-rounded icon-primary'></i>
											  <div class="stats">
													<h4><strong>45%</strong></h4>
													<span>New Orders</span>
											  </div>
										 </div>
									</div>
									<div class="col-md-3 col-sm-6 col-xs-12">
										 <div class="r4_counter db_box">
											  <i class='pull-left fa fa-shopping-cart icon-md icon-rounded icon-orange'></i>
											  <div class="stats">
													<h4><strong>243</strong></h4>
													<span>New Products</span>
											  </div>
										 </div>
									</div>
									<div class="col-md-3 col-sm-6 col-xs-12">
										 <div class="r4_counter db_box">
											  <i class='pull-left fa fa-dollar icon-md icon-rounded icon-purple'></i>
											  <div class="stats">
													<h4><strong>$3424</strong></h4>
													<span>Profit Today</span>
											  </div>
										 </div>
									</div>
									<div class="col-md-3 col-sm-6 col-xs-12">
										 <div class="r4_counter db_box">
											  <i class='pull-left fa fa-users icon-md icon-rounded icon-warning'></i>
											  <div class="stats">
													<h4><strong>1433</strong></h4>
													<span>New Users</span>
											  </div>
										 </div>
									</div>
								</div>

								<div class="row">
									<?php require_once("scr_notifications.php"); ?>
								</div>
							</div>

						</section>
					</div>
				</section>
			</section>
			<?php /* include "scr_chat.php"; */?>
		</div>
		<?php require_once("js_framework.php"); ?>

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
        <script src="assets/plugins/rickshaw-chart/vendor/d3.v3.js" type="text/javascript"></script> 
		  <script src="assets/plugins/jquery-ui/smoothness/jquery-ui.min.js" type="text/javascript"></script> 
		  <script src="assets/plugins/rickshaw-chart/js/Rickshaw.All.js"></script>
		  <script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
		  <script src="assets/plugins/easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
		  <script src="assets/plugins/morris-chart/js/raphael-min.js" type="text/javascript"></script>
		  <script src="assets/plugins/morris-chart/js/morris.min.js" type="text/javascript"></script><script src="assets/plugins/jvectormap/jquery-jvectormap-2.0.1.min.js" type="text/javascript"></script><script src="assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script><script src="assets/plugins/gauge/gauge.min.js" type="text/javascript"></script><script src="assets/plugins/icheck/icheck.min.js" type="text/javascript"></script><script src="assets/js/dashboard.js" type="text/javascript"></script>

        <script src="assets/js/scripts.js" type="text/javascript"></script> 

        <!-- Sidebar Graph - START --> 
        <script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="assets/js/chart-sparkline.js" type="text/javascript"></script>

        <div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Section Settings</h4>
                    </div>
                    <div class="modal-body">

                        <?php echo S_MSG('MS53','User / password tidak terdaftar')?>
                        <?php echo $USERPASS?>

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        <button class="btn btn-success" type="button">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>
