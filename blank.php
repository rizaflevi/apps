<?php
// blank.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cTITLE=S_MSG('MN03','Isi Identitas Perusahaan');
	$cFILE_LOGO_COMP = 'data/images/'. 'LOGO1_'.$cFILTER_CODE.'.jpg';
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
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

							<div class="pull-left">	</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<section class="box ">
							<div class="pull-right hidden-xs"></div>

								<div class="content-body">
									<div class="row">
<!-- ****************************************** start content ********************************************* -->


<!-- ****************************************** start content ********************************************* -->

									</div>
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

		<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE -  --> 
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>

		<!-- CORE TEMPLATE JS -  --> 
		<script src="assets/js/scripts.js" type="text/javascript"></script> 

		<!-- Sidebar Graph -  --> 
		<script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
		<script src="assets/js/chart-sparkline.js" type="text/javascript"></script>

		<!-- My defined java script  --> 
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

