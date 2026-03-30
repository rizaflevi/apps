<?php
// blank.php
// https://datatables.net/extensions/scroller/examples/initialisation/server-side_processing.html

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
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

										<table id="example" class="display nowrap" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>ID</th>
													<th>First name</th>
													<th>Last name</th>
													<th>ZIP / Post code</th>
													<th>Country</th>
												</tr>
											</thead>
										</table>
	
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
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="assets/js/jquery-1.11.2.min.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

<script>

$(document).ready(function() {
    $('#example').DataTable( {
		url:"test/get_data.php";
        serverSide: true,
        ordering: false,
        searching: false,
        ajax: function ( data, callback, settings ) {
            var out = [];
 
            for ( var i=data.start, ien=data.start+data.length ; i<ien ; i++ ) {
                out.push( [ i+'-1', i+'-2', i+'-3', i+'-4', i+'-5' ] );
            }
 
            setTimeout( function () {
                callback( {
                    draw: data.draw,
                    data: out,
                    recordsTotal: 160421,
                    recordsFiltered: 160421
                } );
            }, 50 );
        },
        scrollY: 600,
        scroller: {
            loadingIndicator: true
        }
    } );
} );
<script>

