<?php
// scr_topbar.php
    if (!isset($_SESSION['gSYS_PARA']) || !isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSER_CODE	= $_SESSION['gUSERCODE'];
	$cSYS_PARA 	= $_SESSION['gSYS_PARA'];

	$cFE_PERSON='';
	$cWIDTH_LOGO='300px';
	if (isset($_GET['_fe'])) {
		$cFE_PERSON=$_GET['_fe'];
		$cWIDTH_LOGO='100%';
	}
	// $cFILE_USER = S_PARA('FTP_USER_FOLDER', '/home/riza/www/images/admin/'). $cUSER_CODE.'.jpg';
	$cFILE_USER = 'data/images_user'. $cUSER_CODE.'.jpg';
	if(file_exists($cFILE_USER)==0)	$cFILE_USER = "data/images_user/demo.jpg";

	$COMPANY = 'Rainbow1';
	$LOGO = 'data/images/'.$COMPANY.'.jpg';
	$HELP_FILE = 'Doc/User Manual Rainbow Web.pdf';
//	TODO : view multiple pdf
	if ($cAPP_CODE!='') {
		$LOGO = 'data/images/'.$cAPP_CODE.'.jpg';
		if (!file_exists($LOGO))	$LOGO = 'data/images/rainbow1.jpg';
		$PDF_FL = 'Doc/'.$cAPP_CODE.'.pdf';
		if(file_exists($PDF_FL)==1)	$HELP_FILE = $PDF_FL;
	}
	?>

	<div class="pace-activity"></div>
        <div class='page-topbar '>
            <div class='logo-area'>

            </div>
 		</div>
	</div>

	<div class="page-topbar sidebar_shift">
		<div style="width: 21.4em; background-color: #5972b5; display: block; min-height: 4.3em; float: left;">
			<img src="<?php echo $LOGO ?>" style="height: 60px; width:<?php echo $cWIDTH_LOGO?>">
		</div>
		<div class="quick-area">
			<div class="pull-left">
				<ul class="info-menu left-links list-inline list-unstyled">
					<li class="sidebar-toggle-wrap">
						 <a href="#" data-toggle="sidebar" class="sidebar_toggle">
							  <i class="fa fa-bars" title="show/hide menu" ></i>
						 </a>
					</li>
<?php /*
					<li class="message-toggle-wrapper showopacity hidden-sm hidden-xs">
						<a href="#" data-toggle="dropdown" class="toggle">
							<i class="fa fa-envelope"></i>
							<span class="badge badge-primary">7</span>
						</a>
						<ul class="dropdown-menu messages animated fadeIn">
							  <img src="<?php echo $LOGO ?>" style="height: 60px; width:300px">

							<li class="list ps-container">

								<ul class="dropdown-menu-list list-unstyled ps-scrollbar">
									<li class="unread status-available">
										<a href="javascript:;">
											<div class="user-img">
												<img src="data/profile/avatar-1.png" alt="user-image" class="img-circle img-inline">
											</div>
											<div>
												<span class="name">
													<strong>Riza F. Levi</strong>
													<span class="time small">- 15 mins ago</span>
													<span class="profile-status available pull-right"></span>
												</span>
												<span class="desc small">
													Any question please contact me at 0811526550.
												</span>
											</div>
										</a>
									</li>
									<li class=" status-away">
										<a href="javascript:;">
											<div class="user-img">
													<img src="data/profile/avatar-2.png" alt="user-image" class="img-circle img-inline">
											</div>
											<div>
												<span class="name">
													<strong>Fajar Hazmy F.</strong>
													<span class="time small">- 45 mins ago</span>
													<span class="profile-status away pull-right"></span>
												</span>
												<span class="desc small">
													A stock market, equity market or share market is the aggregation of buyers and sellers (a loose network of economic transactions, not a physical facility or discrete entity) of stocks (also called shares), which represent ownership claims on businesses; these may include securities listed on a public stock exchange as well as those only traded privately. Examples of the latter include shares of private companies which are sold to investors through equity crowdfunding platforms. Stock exchanges list shares of common equity as well as other security types, e.g. corporate bonds and convertible bonds.
												</span>
											</div>
										</a>
									</li>
									<li class=" status-busy">
										<a href="javascript:;">
											<div class="user-img">
												<img src="data/profile/avatar-3.png" alt="user-image" class="img-circle img-inline">
											</div>
											<div>
												<span class="name">
													<strong>Clementina Brodeur</strong>
													<span class="time small">- 1 hour ago</span>
													<span class="profile-status busy pull-right"></span>
												</span>
												<span class="desc small">
													The trend towards forms of saving with a higher risk has been accentuated by new rules for most funds and insurance.
												</span>
											</div>
										</a>
									</li>
									<li class=" status-offline">
										<a href="javascript:;">
											<div class="user-img">
													<img src="data/profile/avatar-4.png" alt="user-image" class="img-circle img-inline">
											</div>
											<div>
												<span class="name">
													<strong>Vivaldi Fachmy</strong>
													<span class="time small"> - 5 hours ago</span>
													<span class="profile-status offline pull-right"></span>
												</span>
												<span class="desc small">
													The NASDAQ is a virtual exchange, where all of the trading is done over a computer network. The process is similar to the New York Stock Exchange. 
												</span>
											</div>
										</a>
									</li>
									<li class=" status-offline">
										<a href="javascript:;">
											<div class="user-img">
												<img src="data/profile/avatar-5.png" alt="user-image" class="img-circle img-inline">
											</div>
											<div>
												<span class="name">
													<strong>Faridah Fahlevi</strong>
													<span class="time small">- Yesterday</span>
													<span class="profile-status offline pull-right"></span>
												</span>
												<span class="desc small">
													NASDAQ market makers will always provide a bid and ask price at which they will always purchase or sell 'their' stock.
												</span>
											</div>
										</a>
									</li>
									<li class=" status-available">
										<a href="javascript:;">
											<div class="user-img">
													<img src="data/profile/avatar-1.png" alt="user-image" class="img-circle img-inline">
											</div>
											<div>
												<span class="name">
													<strong>Verdell Rea</strong>
													<span class="time small">- 14th Mar</span>
													<span class="profile-status available pull-right"></span>
												</span>
												<span class="desc small">
													The stock market is one of the most important ways for companies to raise money.
												</span>
											</div>
										</a>
									</li>
									<li class=" status-busy">
										<a href="javascript:;">
											<div class="user-img">
													<img src="data/profile/avatar-2.png" alt="user-image" class="img-circle img-inline">
											</div>
											<div>
													<span class="name">
														<strong>Linette Lheureux</strong>
														<span class="time small">- 16th Mar</span>
														<span class="profile-status busy pull-right"></span>
													</span>
													<span class="desc small">
														Rising share prices, for instance, tend to be associated with increased business investment and vice versa.
													</span>
											</div>
										</a>
									</li>
									<li class=" status-away">
										<a href="javascript:;">
											<div class="user-img">
													<img src="data/profile/avatar-3.png" alt="user-image" class="img-circle img-inline">
											</div>
											<div>
												<span class="name">
													<strong>Araceli Boatright</strong>
													<span class="time small">- 16th Mar</span>
													<span class="profile-status away pull-right"></span>
												</span>
												<span class="desc small">
													The financial system in most western countries has undergone a remarkable transformation.
												</span>
											</div>
										</a>
									</li>

								</ul>

								<div style="left: 0px; bottom: 3px;" class="ps-scrollbar-x-rail">
									<div style="left: 0px; width: 0px;" class="ps-scrollbar-x"></div>
								</div>
								<div style="top: 0px; right: 3px;" class="ps-scrollbar-y-rail">
									<div style="top: 0px; height: 0px;" class="ps-scrollbar-y"></div>
								</div>
								<div style="left: 0px; bottom: 3px;" class="ps-scrollbar-x-rail">
									<div style="left: 0px; width: 0px;" class="ps-scrollbar-x"></div>
								</div>
								<div style="top: 0px; right: 3px;" class="ps-scrollbar-y-rail">
									<div style="top: 0px; height: 0px;" class="ps-scrollbar-y"></div>
								</div>
								<div style="left: 0px; bottom: 3px;" class="ps-scrollbar-x-rail">
									<div style="left: 0px; width: 0px;" class="ps-scrollbar-x"></div>
								</div>
								<div style="top: 0px; right: 3px;" class="ps-scrollbar-y-rail">
									<div style="top: 0px; height: 0px;" class="ps-scrollbar-y"></div>
								</div>
							</li>

							<li class="external">
								<a href="?">	<span>Read All Messages</span>	</a>
<!--								<a href="mail_inbox.php">	<span>Read All Messages</span>	</a>	-->
							</li>
						</ul>

					</li>
					<li class="notify-toggle-wrapper showopacity hidden-sm hidden-xs">
						<a href="#" data-toggle="dropdown" class="toggle">
							<i class="fa fa-bell"></i>
							<span class="badge badge-orange">3</span>
						</a>
						<ul class="dropdown-menu notifications animated fadeIn">
							<li class="total">
								<span class="small">
									You have <strong>3</strong> new notifications.
									<a href="javascript:;" class="pull-right">Mark all as Read</a>
								</span>
							</li>
							<li class="list ps-container">

								<ul class="dropdown-menu-list list-unstyled ps-scrollbar">
									<li class="unread available">
										<a href="javascript:;">
											<div class="notice-icon">
												 <i class="fa fa-check"></i>
											</div>
											<div>
												 <span class="name">
													  <strong>Server needs to reboot</strong>
													  <span class="time small">15 mins ago</span>
												 </span>
											</div>
										</a>
									</li>
									<li class="unread away">
										<a href="mail_inbox.php">
											<div class="notice-icon">
												 <i class="fa fa-envelope"></i>
											</div>
											<div>
												<span class="name">
													<strong>45 new messages</strong>
													<span class="time small">45 mins ago</span>
												</span>
											</div>
										</a>
									</li>
									<li class=" busy">
										<a href="javascript:;">
											<div class="notice-icon">
												 <i class="fa fa-times"></i>
											</div>
											<div>
												 <span class="name">
													  <strong>Server IP Blocked</strong>
													  <span class="time small">1 hour ago</span>
												 </span>
											</div>
										</a>
									</li>
									<li class=" offline">
										<a href="javascript:;">
											<div class="notice-icon">
												 <i class="fa fa-user"></i>
											</div>
											<div>
												 <span class="name">
													  <strong>10 Orders Shipped</strong>
													  <span class="time small">5 hours ago</span>
												 </span>
											</div>
										</a>
									</li>
									<li class=" offline">
										<a href="javascript:;">
											<div class="notice-icon">
												 <i class="fa fa-user"></i>
											</div>
											<div>
												 <span class="name">
													  <strong>New Comment on blog</strong>
													  <span class="time small">Yesterday</span>
												 </span>
											</div>
										</a>
									</li>
									<li class=" available">
										<a href="javascript:;">
											<div class="notice-icon">
												 <i class="fa fa-check"></i>
											</div>
											<div>
												 <span class="name">
													  <strong>Great Speed Notify</strong>
													  <span class="time small">14th Mar</span>
												 </span>
											</div>
										</a>
									</li>
									<li class=" busy">
										<a href="javascript:;">
											<div class="notice-icon">
												 <i class="fa fa-times"></i>
											</div>
											<div>
												 <span class="name">
													  <strong>Team Meeting at 6PM</strong>
													  <span class="time small">16th Mar</span>
												 </span>
											</div>
										</a>
									</li>
								</ul>
								<div style="left: 0px; bottom: 3px;" class="ps-scrollbar-x-rail"><div style="left: 0px; width: 0px;" class="ps-scrollbar-x"></div></div><div style="top: 0px; right: 3px;" class="ps-scrollbar-y-rail"><div style="top: 0px; height: 0px;" class="ps-scrollbar-y"></div></div><div style="left: 0px; bottom: 3px;" class="ps-scrollbar-x-rail"><div style="left: 0px; width: 0px;" class="ps-scrollbar-x"></div></div><div style="top: 0px; right: 3px;" class="ps-scrollbar-y-rail"><div style="top: 0px; height: 0px;" class="ps-scrollbar-y"></div></div><div style="left: 0px; bottom: 3px;" class="ps-scrollbar-x-rail"><div style="left: 0px; width: 0px;" class="ps-scrollbar-x"></div></div><div style="top: 0px; right: 3px;" class="ps-scrollbar-y-rail"><div style="top: 0px; height: 0px;" class="ps-scrollbar-y"></div></div>
							</li>
							<li class="external">
								<a href="mail_inbox.php">	<span>Read All Notifications</span>	</a>
							</li>
						</ul>
					</li>
<!--
					<li class="hidden-sm hidden-xs searchform showopacity">
						<div class="input-group">
							  <span class="input-group-addon input-focus">
									<i class="fa fa-search"></i>
							  </span>
							  <form action="search_page.php" method="post">
									<input class="form-control animated fadeIn" placeholder="Search &amp; Enter" name='SEARCH_VALUE' type="text">
									<input value="" type="submit">
							  </form>
						</div>
					</li>
-->
*/ ?>
				</ul>
			</div>
			
			<div class="pull-right">
				<ul class="info-menu right-links list-inline list-unstyled">
                    <a class="" href="prs_dashboard.php" title="go to home" style="color:orange"><i class="fa fa-solid fa-home"></i>home</a>

					<?php /* - ***************** full screen from ubolt *******************************************************	-*/ ?>
					<li class="hidden-xs">
						<a href="#" id="btn-fullscreen" class="waves-effect"><i class="icon-size-fullscreen"></i></a>
					</li>

					<li class="profile showopacity">
						 <a href="#" data-toggle="dropdown" class="toggle">
							  <img src="<?php echo $cFILE_USER?>" alt="user-image" class="img-circle img-inline">
							  <span><?php echo $cUSER_CODE?> <i class="fa fa-angle-down"></i></span>
						 </a>
						 <ul class="dropdown-menu profile animated fadeIn">
							  <li>	<a href="#settings">		<i class="fa fa-wrench"></i>	Settings	</a>	</li>
							  <li>	<a href="ui-profile.php">	<i class="fa fa-user"></i>		Profile		</a>	</li>
							  <li>	<a href="<?php echo $HELP_FILE; ?>" target="_blank"> <i class="fa fa-info"></i>   	Help		</a>	</li>
							  <li class="last">
									<a href="logout.php?id=<?php echo $cUSER_CODE?>">
										 <i class="fa fa-lock"></i>
										 Logout
									</a>
							  </li>
						 </ul>
					</li>
<!--
					<li class="chat-toggle-wrapper">
						<a href="#" data-toggle="chatbar" class="toggle_chat" title="<?php /* echo S_MSG('MN41','Show/hide chat window') */?>">
							<i class="fa fa-comments"></i>
							  <span class="badge badge-warning">9</span>
							<i class="fa fa-times"></i>
						</a>
					</li>
-->
				</ul>			
			</div>		
		</div>
		<script src="sys_js.js" type="text/javascript"></script> 
	</div>
   
<script>
</script>