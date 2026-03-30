<?php
// sys_company.php
// G/L Interface table

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

?>
	<!DOCTYPE html>
	<html class=" ">
		<?php
			require_once("scr_header.php");
			require_once("scr_topbar.php");
		?>
		<!-- START CONTAINER -->
		<div class="page-container row-fluid">
			
			<div class="page-sidebar ">		<!-- SIDEBAR - START -->
				<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 			 <!-- MAIN MENU - START -->
						<?php
							require_once("scr_user_info.php");
							require_once("scr_menu.php");
						?>
				</div>
				<div class="project-info">            </div>
			</div>									<!--  SIDEBAR - END -->
			<!-- START CONTENT -->


<!-- ****************************************** start content ********************************************* -->


						<?php
							require_once("image_crop_tampilanImg.php");
					//		require_once("image_crop_samping.php");
					//		require_once("image_crop_bawah.php");
						?>

<!-- ****************************************** start content ********************************************* -->



			<!-- END CONTENT -->
			<?php
			  include "scr_chat.php";
			?>
		</div>
		<!-- END CONTAINER -->
		<!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->


		<!-- CORE JS FRAMEWORK - START --> 
		<script src="assets/js/jquery-1.11.2.min.js" type="text/javascript"></script> 
		<script src="assets/js/jquery.easing.min.js" type="text/javascript"></script> 
		<script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
		<script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>  
		<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js" type="text/javascript"></script> 
		<script src="assets/plugins/viewport/viewportchecker.js" type="text/javascript"></script>  
		<!-- CORE JS FRAMEWORK - END --> 


		<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
		<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


		<!-- CORE TEMPLATE JS - START --> 
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<!-- END CORE TEMPLATE JS - END --> 

		<!-- Sidebar Graph - START --> 
		<script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
		<script src="assets/js/chart-sparkline.js" type="text/javascript"></script>
		<!-- Sidebar Graph - END --> 

		<!-- My defined java script START --> 
		<script src="sys_js.js" type="text/javascript"></script> 
		<script src="image_crop_main.js" type="text/javascript"></script> 
		<script src="image_crop_cropper.js" type="text/javascript"></script> 
		<!-- My defined java script - END --> 

		</body>
	</html>

