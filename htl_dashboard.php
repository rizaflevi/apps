<?php
// htl_dashboard.php
// Hotel

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	include "sys_connect.php";
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$q_ROOM=OpenTable('HtRoom');
	$nROOM = number_format(SYS_ROWS($q_ROOM));

	$c_GUEST="select tbh_room.*, ";
	$c_GUEST.=" tbh_rtype.HTR_CODE, tbh_rtype.HTR_BACK0, ";
	$c_GUEST.=" cust_res.ROOM_NO,cust_res.FIRST_NAME, cust_res.LAST_NAME, cust_res.ADDRESS1, cust_res.ADDRESS2, cust_res.ARRIVAL_DT  from tbh_room ";
	$c_GUEST.=" LEFT JOIN tbh_rtype ON tbh_room.ROOMTYPE=tbh_rtype.HTR_CODE ";
	$c_GUEST.="	LEFT JOIN cust_res ON tbh_room.ROOM_NO=cust_res.ROOM_NO ";
	$c_GUEST.=" where tbh_rtype.HTR_CODE!='' and cust_res.ROOM_NO!='' and tbh_room.APP_CODE='$cFILTER_CODE' and tbh_room.DELETOR=''";
//	$n_GUEST = number_format(SYS_ROWS(SYS_QUERY($c_GUEST)));
	$n_GUEST = 0;
	
	$n_CHECK_IN = 0;

	$cDBOARD_SIZE = S_PARA('_DASHBOARD_SIZE','col-lg-3 col-md-6');

	$cTITLE='Frontdesk Dashboard';
	$cFILE_LOGO_COMP = 'data/images/'. 'LOGO1_'.$cFILTER_CODE.'.jpg';
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>

		<!-- Custom CSS from SB -->
		<link href="sb/css/bootstrap.min.css" rel="stylesheet">
		<link href="sb/css/sb-admin-2.css" rel="stylesheet">
		<link href="sb/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<!-- START CONTAINER -->

		<div class="page-container row-fluid">
			
			<div class="page-sidebar ">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper">
					<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
				</div>
				<div class="project-info">	</div>
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
										<a href="ht_tb_rooms.php">
											<div class="panel panel-primary">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
															<i class="fa fa-comments icon-lg animated fadeIn"></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo $nROOM?></div>
															<div>Rooms</div>
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
									<div class="<?php echo $cDBOARD_SIZE?>">
										<div class="panel panel-green">
											<a href="ht_frontdesk.php">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
															<i class="fa fa-tasks icon-lg"></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo $n_GUEST?></div>
															<div>Guests</div>
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
										<div class="panel panel-yellow">
											<a href="#">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
															<i class="fa fa-shopping-cart fa-5x"></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge">124</div>
															<div>New Orders!</div>
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
										<div class="panel panel-info">
											<a href="ht_check_in.php">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
															<i class='fa  fa-bell-o fa-3x icon-rounded icon-purple inviewport animated  animated-delay-2400ms animated-duration-1400ms' data-vp-add-class='visible rotateIn'></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo $n_CHECK_IN?></div>
															<div>Check In</div>
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
<!--
									<div class="<?php echo $cDBOARD_SIZE?>">
										<div class="panel panel-red">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
															<i class='fa fa-support fa-3x icon-rounded icon-danger inviewport animated  animated-delay-2400ms animated-duration-1400ms' data-vp-add-class='visible rotateIn'></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge">13</div>
														<div>Support Tickets!</div>
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
<!-- ========================================== --> 
								</div>
							</div>
						</section>
					</div>

				</section>
			</section>
			<?php	include "scr_chat.php";	?>
 		</div>
		<?php	require_once("js_framework.php");	?>
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

