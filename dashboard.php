<?php
	require_once "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE']) || !isset($_SESSION['gUSERCODE'])) {
		session_start();
	}
	$MINE 		= $_SESSION['gUSERNAME'];
	$USER_AS 	= $_SESSION['gUSER_AS'];
	$cUSER_CODE = strtoupper($_SESSION['gUSERCODE']);

	$l_BTN_PEGAWAI=0;	$l_BTN_CUSTOMER=0;	$l_BTN_JUAL=0;	$l_BTN_INVENTORY=0;	$l_BTN_PELANGGAN=0;
	$l_BTN_LAP_ABSEN=0;	$l_BTN_BACA_MTR=0;	$nREC_ABSEN= 0;

//	$qCEK_DASH=OpenTable('Dashboard', "A.DASH_JOB>'' and A.APP_CODE='$cFILTER_CODE' and upper(B.USER_CODE) = '$cUSER_CODE' and B.TRUSTEE_CODE is not null and A.DASH_ORDER>0", "A.JOB_CODE", "A.DASH_ORDER, A.DASH_ORDER, B.TRUSTEE_CODE");
	if($l_BTN_PELANGGAN==1){
		// $q_REC_ABSEN 	= OpenTable('Presence', "date(PPL_PRESENT)='".date("Y-m-d")."' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
		// $nREC_ABSEN 	= SYS_ROWS($q_REC_ABSEN);
	}
	$nPEGAWAI=0;
	if($l_BTN_PEGAWAI==1){
		$q_PEGAWAI=OpenTable('PersonMain', "APP_CODE='$cFILTER_CODE' and DELETOR=''");
		$nPEGAWAI = SYS_ROWS($q_PEGAWAI);
	}


?>

<!DOCTYPE html>
<html class=" ">

	<?php  	require_once("scr_header.php");  	require_once("scr_topbar.php");	?>		<!-- END TOPBAR -->

    <!-- BEGIN BODY -->
	<body class="sidebar-collapse">

		<!-- START CONTAINER -->
		<div class="page-container row-fluid">

            <!-- SIDEBAR - START -->
            <div class="page-sidebar ">

                <!-- MAIN MENU - START -->
                <div class="page-sidebar-wrapper" id="main-menu-wrapper"> 

                    <!-- USER INFO - START -->
					<?php require_once("scr_user_info.php"); ?>
                    <ul class='wraplist'>	
						<?php	require_once("scr_menu.php");	?>
					</ul>

                </div>   <!-- MAIN MENU - END -->
                <div class="project-info">
<!--                <div class="block1">
                        <div class="data">
                            <span class='title'>New&nbsp;Orders</span>
                            <span class='total'>2,345</span>
                        </div>
                        <div class="graph">
                            <span class="sidebar_orders">...</span>
                        </div>
                    </div>

                    <div class="block2">
                        <div class="data">
                            <span class='title'>Visitors</span>
                            <span class='total'>345</span>
                        </div>
                        <div class="graph">
                            <span class="sidebar_visitors">...</span>
                        </div>
                    </div>	-->
                </div>	<!--  SIDEBAR - END -->	<!-- START CONTENT -->
			</div>

			<section id="main-content" class=" ">
				<section class="wrapper main-wrapper" style=''>

                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <section class="box nobox">
                            <div class="content-body">    <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">

                                    </div>
                                </div>
                            </div>
                        </section>
					</div>

                    <div class="col-lg-12">
                        <section class="box ">
                            <header class="panel_header">
                                <h2 class="title pull-left">Dashboard</h2>
                                <div class="actions panel_actions pull-right">
                                    <i class="box_toggle fa fa-chevron-down"></i>
                                    <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                                    <i class="box_close fa fa-times"></i>
                                </div>
                            </header>
                            <div class="content-body">    <div class="row">
							<?php
										$qMAIN_MENU=OpenTable('Dashboard', "A.DASH_PARA>'' and A.DASH_JOB>'' and A.APP_CODE='$cFILTER_CODE' and upper(B.USER_CODE) = '$cUSER_CODE' and B.TRUSTEE_CODE is not null and A.DASH_ORDER>0", "A.JOB_CODE", "A.DASH_ORDER, A.DASH_ORDER, B.TRUSTEE_CODE");
										while($aMAIN_MENU=SYS_FETCH($qMAIN_MENU)) {
											echo '<div class="col-lg-'.$aMAIN_MENU['DASH_WIDTH'].' col-md-6">';
												echo '<a href="'.$aMAIN_MENU['DASH_LINK'].'">';
												echo '<div class="panel '.$aMAIN_MENU['DASH_COLOR'].'">';
													echo '<div class="panel-heading">';
														echo '<div class="row">';
															echo '<div class="col-xs-3">';
																echo '<i class="fa '.$aMAIN_MENU['DASH_FA'].'"></i>';
															echo '</div>';
															$cVAR = $aMAIN_MENU['DASH_PARA'];
																echo '<div class="col-xs-9 text-right">';
																echo '<div class="huge">'.number_format(${$cVAR}).'</div>';
																echo '<div>' . $aMAIN_MENU['DASH_LABEL'] .'</div>';
															echo '</div>';
														echo '</div>';
													echo '</div>
														<div class="panel-footer">
															<span class="pull-left">View Details</span>
															<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
															<div class="clearfix"></div>
														</div>
													</div>
												</a>
											</div>';
										}
									?>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">

                                        <div class="wid-social facebook">
                                            <div class="social-icon">
                                                <i class='fa fa-facebook text-light icon-xlg pull-right'></i>
                                            </div>
                                            <div class="social-info">
                                                <h3 data-speed="3000" data-from="0" data-to="143" class="number_counter bold count text-light">0</h3>
                                                <h3 class="bold count text-light"> K</h3>                     <h4 class="counttype text-light">Likes</h4>
                                                <span class="percent">5% increase from last week</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">

                                        <div class="wid-social twitter">
                                            <div class="social-icon">
                                                <i class='fa fa-twitter text-light icon-xlg pull-right'></i>
                                            </div>
                                            <div class="social-info">
                                                <h3 data-speed="3000" data-from="0" data-to="3454" class="number_counter bold count text-light">0</h3>
                                                <h4 class="counttype text-light">Tweets</h4>
                                                <span class="percent">2% increase from last week</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">

                                        <div class="wid-social google-plus">
                                            <div class="social-icon">
                                                <i class='fa fa-google-plus text-light icon-xlg pull-right'></i>
                                            </div>
                                            <div class="social-info">
                                                <h3 data-speed="3000" data-from="0" data-to="523" class="number_counter bold count text-light">0</h3>
                                                <h4 class="counttype text-light">Circles</h4>
                                                <span class="percent">2% increase from last week</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">

                                        <div class="wid-social dribbble">
                                            <div class="social-icon">
                                                <i class='fa fa-dribbble text-light icon-xlg pull-right'></i>
                                            </div>
                                            <div class="social-info">
                                                <h3 data-speed="3000" data-from="0" data-to="2525" class="number_counter bold count text-light">0</h3>
                                                <h4 class="counttype text-light">Subscribers</h4>
                                                <span class="percent">7% increase from last week</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">

                                        <div class="wid-social linkedin">
                                            <div class="social-icon">
                                                <i class='fa fa-linkedin text-light icon-xlg pull-right'></i>
                                            </div>
                                            <div class="social-info">
                                                <h3 data-speed="3000" data-from="0" data-to="2525" class="number_counter bold count text-light">0</h3>
                                                <h4 class="counttype text-light">Connections</h4>
                                                <span class="percent">7% increase from last week</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">

                                        <div class="wid-social youtube">
                                            <div class="social-icon">
                                                <i class='fa fa-youtube text-light icon-xlg pull-right'></i>
                                            </div>
                                            <div class="social-info">
                                                <h3 data-speed="3000" data-from="0" data-to="1523" class="number_counter bold count text-light">0</h3>
                                                <h4 class="counttype text-light">Subscribers</h4>
                                                <span class="percent">7% increase from last week</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">

                                        <div class="wid-social skype">
                                            <div class="social-icon">
                                                <i class='fa fa-skype text-light icon-xlg pull-right'></i>
                                            </div>
                                            <div class="social-info">
                                                <h3 data-speed="3000" data-from="0" data-to="2721" class="number_counter bold count text-light">0</h3>
                                                <h4 class="counttype text-light">Contacts</h4>
                                                <span class="percent">7% increase from last week</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">

                                        <div class="wid-social flickr">
                                            <div class="social-icon">
                                                <i class='fa fa-flickr text-light icon-xlg pull-right'></i>
                                            </div>
                                            <div class="social-info">
                                                <h3 data-speed="3000" data-from="0" data-to="1221" class="number_counter bold count text-light">0</h3>
                                                <h4 class="counttype text-light">Media</h4>
                                                <span class="percent">7% increase from last week</span>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-12">
                        <section class="box ">
                            <header class="panel_header">
                                <h2 class="title pull-left">Counter & Progress Tiles</h2>
                                <div class="actions panel_actions pull-right">
                                    <i class="box_toggle fa fa-chevron-down"></i>
                                    <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                                    <i class="box_close fa fa-times"></i>
                                </div>
                            </header>
                            <div class="content-body">    <div class="row">

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">
                                        <div class="tile-counter bg-primary">
                                            <div class="content">
                                                <i class='fa fa-thumbs-up icon-lg'></i>
                                                <h2 class="number_counter" data-speed="3000" data-from="1001" data-to="3504">1001</h2>
                                                <div class="clearfix"></div>
                                                <span>People liked it !</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">
                                        <div class="tile-counter bg-purple">
                                            <div class="content">
                                                <i class='fa fa-heart icon-lg'></i>
                                                <h2 class="number_counter" data-speed="3000" data-from="1001" data-to="4504">1001</h2>
                                                <div class="clearfix"></div>
                                                <span>Loved your post !</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">
                                        <div class="tile-counter bg-orange">
                                            <div class="content">
                                                <i class='fa fa-user icon-lg'></i>
                                                <h2 class="number_counter" data-speed="3000" data-from="1001" data-to="3304">1001</h2>
                                                <div class="clearfix"></div>
                                                <span>New Users</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">
                                        <div class="tile-counter bg-warning">
                                            <div class="content">
                                                <i class='fa fa-share icon-lg'></i>
                                                <h2 class="number_counter" data-speed="3000" data-from="1001" data-to="7504">1001</h2>
                                                <div class="clearfix"></div>
                                                <span>Shared this post</span>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="clearfix"></div>


                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">

                                        <div class="tile-progress bg-info">
                                            <div class="content">
                                                <h4><i class='fa fa-dashboard icon-sm'></i> Server Load</h4>
                                                <div class="progress"><div class="progress-bar inviewport animated" data-vp-add-class="visible slideInLeft" data-vp-repeat="true" data-vp-offset="100" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;"></div></div>
                                                <span>40% increase</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">

                                        <div class="tile-progress bg-success">
                                            <div class="content">
                                                <h4><i class='fa fa-dashboard icon-sm'></i> Server Load</h4>
                                                <div class="progress"><div class="progress-bar inviewport animated" data-vp-add-class="visible slideInLeft" data-vp-repeat="true" data-vp-offset="100" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;"></div></div>
                                                <span>40% increase</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">

                                        <div class="tile-progress bg-danger">
                                            <div class="content">
                                                <h4><i class='fa fa-dashboard icon-sm'></i> Server Load</h4>
                                                <div class="progress"><div class="progress-bar inviewport animated" data-vp-add-class="visible slideInLeft" data-vp-repeat="true" data-vp-offset="100" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;"></div></div>
                                                <span>40% increase</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6">

                                        <div class="tile-progress bg-secondary">
                                            <div class="content">
                                                <h4><i class='fa fa-dashboard icon-sm'></i> Server Load</h4>
                                                <div class="progress"><div class="progress-bar inviewport animated" data-vp-add-class="visible slideInLeft" data-vp-repeat="true" data-vp-offset="100" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;"></div></div>
                                                <span>40% increase</span>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </section>
                    </div>


                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                        <section class="box nobox">
                            <div class="content-body">    <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">

                                        <div class="r2_map db_box wid-vectormap">
                                            <h4>Visitor's Statistics</h4>
                                            <div class="row">
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <figure>
                                                        <div id="db-world-map-markers" style="width: 100%; height: 300px"></div>        
                                                    </figure>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-12 map_progress">
                                                    <h4>Unique Visitors</h4>
                                                    <span class='text-muted'><small>Last Week Rise by 62%</small></span>
                                                    <div class="progress"><div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100" style="width: 62%"></div></div>
                                                    <br>
                                                    <h4>Registrations</h4>
                                                    <span class='text-muted'><small>Up by 57% last 7 days</small></span>
                                                    <div class="progress"><div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: 57%"></div></div>
                                                    <br>
                                                    <h4>Direct Sales</h4>
                                                    <span class='text-muted'><small>Last Month Rise by 22%</small></span>
                                                    <div class="progress"><div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: 22%"></div></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <section class="box nobox">
                            <div class="content-body">
                                <div class="row">

                                    <div class="col-md-12 col-sm-12 col-xs-12">

                                        <div class="wid-sparkgraph bg-orange">
                                            <span class='bold'>28.95%</span>
                                            <span class='pull-right'><small>Resources Used</small></span>
                                            <div class="clearfix"></div>
                                            <span class="wid_dynamicbar">Loading...</span>
                                        </div>


                                        <div class="wid-sparkgraph bg-success">
                                            <span class='bold'>5312</span>
                                            <span class='pull-right'><small>Orders Placed</small></span>
                                            <div class="clearfix"></div>
                                            <span class="wid_linesparkline">Loading...</span>
                                        </div>

                                        <div class="wid-sparkgraph bg-info">
                                            <span class='bold'>542/115</span>
                                            <span class='pull-right'><small>Total / Unique visitors</small></span>
                                            <div class="clearfix"></div>
                                            <span class="wid_compositebar">Loading...</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
			</section>
			<!-- END CONTENT -->
			<div class="page-chatapi hideit">

                <div class="search-bar">
                    <input type="text" placeholder="Search" class="form-control">
                </div>

                <div class="chat-wrapper">
                    <h4 class="group-head">Groups</h4>
                    <ul class="group-list list-unstyled">
                        <li class="group-row">
                            <div class="group-status available">
                                <i class="fa fa-circle"></i>
                            </div>
                            <div class="group-info">
                                <h4><a href="#">Work</a></h4>
                            </div>
                        </li>
                        <li class="group-row">
                            <div class="group-status away">
                                <i class="fa fa-circle"></i>
                            </div>
                            <div class="group-info">
                                <h4><a href="#">Friends</a></h4>
                            </div>
                        </li>

                    </ul>


                    <h4 class="group-head">Favourites</h4>
                    <ul class="contact-list">

                        <li class="user-row" id='chat_user_1' data-user-id='1'>
                            <div class="user-img">
                                <a href="#"><img src="data/profile/avatar-1.png" alt=""></a>
                            </div>
                            <div class="user-info">
                                <h4><a href="#">Clarine Vassar</a></h4>
                                <span class="status available" data-status="available"> Available</span>
                            </div>
                            <div class="user-status available">
                                <i class="fa fa-circle"></i>
                            </div>
                        </li>
                        <li class="user-row" id='chat_user_2' data-user-id='2'>
                            <div class="user-img">
                                <a href="#"><img src="data/profile/avatar-2.png" alt=""></a>
                            </div>
                            <div class="user-info">
                                <h4><a href="#">Brooks Latshaw</a></h4>
                                <span class="status away" data-status="away"> Away</span>
                            </div>
                            <div class="user-status away">
                                <i class="fa fa-circle"></i>
                            </div>
                        </li>
                        <li class="user-row" id='chat_user_3' data-user-id='3'>
                            <div class="user-img">
                                <a href="#"><img src="data/profile/avatar-3.png" alt=""></a>
                            </div>
                            <div class="user-info">
                                <h4><a href="#">Clementina Brodeur</a></h4>
                                <span class="status busy" data-status="busy"> Busy</span>
                            </div>
                            <div class="user-status busy">
                                <i class="fa fa-circle"></i>
                            </div>
                        </li>

                    </ul>


                    <h4 class="group-head">More Contacts</h4>
                    <ul class="contact-list">

                        <li class="user-row" id='chat_user_4' data-user-id='4'>
                            <div class="user-img">
                                <a href="#"><img src="data/profile/avatar-4.png" alt=""></a>
                            </div>
                            <div class="user-info">
                                <h4><a href="#">Carri Busey</a></h4>
                                <span class="status offline" data-status="offline"> Offline</span>
                            </div>
                            <div class="user-status offline">
                                <i class="fa fa-circle"></i>
                            </div>
                        </li>
                        <li class="user-row" id='chat_user_5' data-user-id='5'>
                            <div class="user-img">
                                <a href="#"><img src="data/profile/avatar-5.png" alt=""></a>
                            </div>
                            <div class="user-info">
                                <h4><a href="#">Melissa Dock</a></h4>
                                <span class="status offline" data-status="offline"> Offline</span>
                            </div>
                            <div class="user-status offline">
                                <i class="fa fa-circle"></i>
                            </div>
                        </li>
                        <li class="user-row" id='chat_user_6' data-user-id='6'>
                            <div class="user-img">
                                <a href="#"><img src="data/profile/avatar-1.png" alt=""></a>
                            </div>
                            <div class="user-info">
                                <h4><a href="#">Verdell Rea</a></h4>
                                <span class="status available" data-status="available"> Available</span>
                            </div>
                            <div class="user-status available">
                                <i class="fa fa-circle"></i>
                            </div>
                        </li>
                        <li class="user-row" id='chat_user_7' data-user-id='7'>
                            <div class="user-img">
                                <a href="#"><img src="data/profile/avatar-2.png" alt=""></a>
                            </div>
                            <div class="user-info">
                                <h4><a href="#">Linette Lheureux</a></h4>
                                <span class="status busy" data-status="busy"> Busy</span>
                            </div>
                            <div class="user-status busy">
                                <i class="fa fa-circle"></i>
                            </div>
                        </li>
                        <li class="user-row" id='chat_user_8' data-user-id='8'>
                            <div class="user-img">
                                <a href="#"><img src="data/profile/avatar-3.png" alt=""></a>
                            </div>
                            <div class="user-info">
                                <h4><a href="#">Araceli Boatright</a></h4>
                                <span class="status away" data-status="away"> Away</span>
                            </div>
                            <div class="user-status away">
                                <i class="fa fa-circle"></i>
                            </div>
                        </li>
                        <li class="user-row" id='chat_user_9' data-user-id='9'>
                            <div class="user-img">
                                <a href="#"><img src="data/profile/avatar-4.png" alt=""></a>
                            </div>
                            <div class="user-info">
                                <h4><a href="#">Clay Peskin</a></h4>
                                <span class="status busy" data-status="busy"> Busy</span>
                            </div>
                            <div class="user-status busy">
                                <i class="fa fa-circle"></i>
                            </div>
                        </li>
                        <li class="user-row" id='chat_user_10' data-user-id='10'>
                            <div class="user-img">
                                <a href="#"><img src="data/profile/avatar-5.png" alt=""></a>
                            </div>
                            <div class="user-info">
                                <h4><a href="#">Loni Tindall</a></h4>
                                <span class="status away" data-status="away"> Away</span>
                            </div>
                            <div class="user-status away">
                                <i class="fa fa-circle"></i>
                            </div>
                        </li>
                        <li class="user-row" id='chat_user_11' data-user-id='11'>
                            <div class="user-img">
                                <a href="#"><img src="data/profile/avatar-1.png" alt=""></a>
                            </div>
                            <div class="user-info">
                                <h4><a href="#">Tanisha Kimbro</a></h4>
                                <span class="status idle" data-status="idle"> Idle</span>
                            </div>
                            <div class="user-status idle">
                                <i class="fa fa-circle"></i>
                            </div>
                        </li>
                        <li class="user-row" id='chat_user_12' data-user-id='12'>
                            <div class="user-img">
                                <a href="#"><img src="data/profile/avatar-2.png" alt=""></a>
                            </div>
                            <div class="user-info">
                                <h4><a href="#">Jovita Tisdale</a></h4>
                                <span class="status idle" data-status="idle"> Idle</span>
                            </div>
                            <div class="user-status idle">
                                <i class="fa fa-circle"></i>
                            </div>
                        </li>

                    </ul>
                </div>

			</div>
			<div class="chatapi-windows "></div>    
		</div>
        <!-- END CONTAINER -->
        <!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->

		<?php	require_once("js_framework.php");	?>

		<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
        <script src="assets/plugins/icheck/icheck.min.js" type="text/javascript"></script><script src="assets/plugins/count-to/countto.js" type="text/javascript"></script> <script src="assets/plugins/jvectormap/jquery-jvectormap-2.0.1.min.js" type="text/javascript"></script><script src="assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


        <!-- CORE TEMPLATE JS - START --> 
        <script src="assets/js/scripts.js" type="text/javascript"></script> 
        <!-- END CORE TEMPLATE JS - END --> 

        <!-- Sidebar Graph - START --> 
        <script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="assets/js/chart-sparkline.js" type="text/javascript"></script>
        <!-- Sidebar Graph - END --> 



        <!-- General section box modal start -->
        <div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Section Settings</h4>
                    </div>
                    <div class="modal-body">

                        Body goes here...

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        <button class="btn btn-success" type="button">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal end -->
    </body>
</html>



