<?php
/**
 * search_page.php
 * search on top bar 
 */

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
	}
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cHEADER 		= 'Search Result';
	$cKD_USR = S_MSG('F003','Kode');
	$cNM_USR = S_MSG('F004','Nama');

	$cSEARCH_VALUE = $_POST['SEARCH_VALUE'];

	$cQ_USER="select * from ".$database1.".tb_user where concat_ws(' ',USER_CODE,USER_NAME) like '%".$cSEARCH_VALUE."%' and DELETOR=''";
	$qQ_USER=mysql_query($cQ_USER) or die ('Error in query.' .mysql_error());
//	die ($cQ_USER);

?>

	<!DOCTYPE html>
	<html class=" ">
		<head>
			<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
			<meta charset="utf-8" />
			<title>Rainbow Sys : <?php echo $cHEADER?></title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
			<meta content="" name="description" />
			<meta content="" name="author" />

			<link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon" />    <!-- Favicon -->
			<link rel="apple-touch-icon-precomposed" href="assets/images/apple-touch-icon-57-precomposed.png">	<!-- For iPhone -->
			<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/apple-touch-icon-114-precomposed.png">    <!-- For iPhone 4 Retina display -->
			<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/apple-touch-icon-72-precomposed.png">    <!-- For iPad -->
			<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/apple-touch-icon-144-precomposed.png">    <!-- For iPad Retina display -->

			<!-- CORE CSS FRAMEWORK - START -->
			<link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
			<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
			<link href="assets/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
			<link href="assets/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
			<link href="assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
			<link href="assets/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>
			<!-- CORE CSS FRAMEWORK - END -->

			<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
			<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


			<!-- CORE CSS TEMPLATE - START -->
			<link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
			<link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>
			<!-- CORE CSS TEMPLATE - END -->

		</head>
		<!-- END HEAD -->

		<!-- BEGIN BODY -->
		<body class=" "><!-- START TOPBAR -->
			<?php
				require_once("scr_topbar.php");
			?>

        <!-- END TOPBAR -->
        <!-- START CONTAINER -->
        <div class="page-container row-fluid">

            <!-- SIDEBAR - START -->
            <div class="page-sidebar ">

                <!-- MAIN MENU - START -->
                <div class="page-sidebar-wrapper" id="main-menu-wrapper"> 

                    <!-- USER INFO - START -->
						<?php
							require_once("scr_user_info.php");
							require_once("scr_menu.php");
						?>

                    <!-- USER INFO - END -->

                </div>
                <!-- MAIN MENU - END -->

                <div class="project-info">
                <!--  

                    <div class="block1">
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
                    </div>
-->
                </div>

            </div>
            <!--  SIDEBAR - END -->
            <!-- START CONTENT -->
            <section id="main-content" class=" ">
                <section class="wrapper main-wrapper" style=''>
                    <div class="clearfix"></div>

                    <div class="col-lg-12">
                        <section class="box nobox">
                            <div class="content-body">    <div class="row">

                                    <div class="col-md-9 col-sm-12 col-xs-12">

                                        <div class="input-group primary">
                                            <span class="input-group-addon">                
                                                <span class="arrow"></span>
                                                <i class="fa fa-search"></i>
                                            </span>
                                            <input type="text" class="form-control search-page-input" placeholder="Search" value="<?php echo $cSEARCH_VALUE?>">
                                        </div><br>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                        <nav class='pull-right'>
                                            <!-- 								  <ul class="pager" style="margin:0px;">
                                                                                                                <li><a href="#"><i class='fa fa-arrow-left icon-xs icon-orange icon-secondary'></i></a></li>
                                                                                                                <li><a href="#"><i class='fa fa-arrow-right icon-xs icon-orange icon-secondary'></i></a></li>
                                                                                                              </ul> -->

                                            <ul class="pagination pull-right" style="margin:0px;">
                                                <li><a href="#">«</a></li>
                                                <li class="active"><a href="#">1</a></li>
                                                <li><a href="#">2</a></li>
                                                <li><a href="#">3</a></li>
                                                <li><a href="#">»</a></li>
                                            </ul>

                                        </nav>
                                    </div>

                                    <div class="clearfix"></div><br>

                                    <div class="col-md-12 col-sm-12 col-xs-12 search_data">

                                        <ul class="nav nav-tabs vertical col-md-2 col-lg-2 col-sm-3 col-xs-3 left-aligned">
                                            <li class="active">
                                                <a href="#web-1" data-toggle="tab">
                                                    <i class="fa fa-home"></i> Web
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#images-1" data-toggle="tab">
                                                    <i class="fa fa-user"></i> Images
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#contacts-1" data-toggle="tab">
                                                    <i class="fa fa-user"></i> Contacts
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#projects-1" data-toggle="tab">
                                                    <i class="fa fa-envelope"></i> Projects
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#map-1" data-toggle="tab">
                                                    <i class="fa fa-envelope"></i> Map
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#videos-1" data-toggle="tab">
                                                    <i class="fa fa-cog"></i> Videos
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#messages-1" data-toggle="tab">
                                                    <i class="fa fa-envelope"></i> Messages
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#profile-1" data-toggle="tab">
                                                    <i class="fa fa-envelope"></i> Profile
                                                </a>
                                            </li>
                                        </ul>					

                                        <div class="tab-content vertical col-md-10 col-lg-10 col-sm-9 col-xs-9 left-aligned">
                                            <div class="tab-pane fade in active" id="web-1">

                                                <div class="search_result">
                                                    <div class="pull-left col-sm-2 col-xs-3"><img class="img-responsive" src="data/search/1.png"></div>
                                                    <div class="pull-left col-sm-10 col-xs-9">
                                                        <h4><a href="#">Contrast and Similiarity in Graphic Desigh</a></h4>
                                                        <p>Graphic design is the art of visual communication through the use of images, words, and ideas to give information to the viewers. <a href="#" class="pull-right"><small>Read More...</small></a></p>
                                                    </div>
                                                </div>
                                                <div class="search_result">
                                                    <div class="pull-left col-sm-2 col-xs-3"><img class="img-responsive" src="data/search/2.png"></div>
                                                    <div class="pull-left col-sm-10 col-xs-9">
                                                        <h4><a href="#">Contrast and Similiarity in Graphic Desigh</a></h4>
                                                        <p>Graphic design is the art of visual communication through the use of images, words, and ideas to give information to the viewers. <a href="#" class="pull-right"><small>Read More...</small></a></p>
                                                    </div>
                                                </div>
                                                <div class="search_result">
                                                    <div class="pull-left col-sm-2 col-xs-3"><img class="img-responsive" src="data/search/3.png"></div>
                                                    <div class="pull-left col-sm-10 col-xs-9">
                                                        <h4><a href="#">Contrast and Similiarity in Graphic Desigh</a></h4>
                                                        <p>Graphic design is the art of visual communication through the use of images, words, and ideas to give information to the viewers. <a href="#" class="pull-right"><small>Read More...</small></a></p>
                                                    </div>
                                                </div>
                                                <div class="search_result">
                                                    <div class="pull-left col-sm-2 col-xs-3"><img class="img-responsive" src="data/search/4.png"></div>
                                                    <div class="pull-left col-sm-10 col-xs-9">
                                                        <h4><a href="#">Contrast and Similiarity in Graphic Desigh</a></h4>
                                                        <p>Graphic design is the art of visual communication through the use of images, words, and ideas to give information to the viewers. <a href="#" class="pull-right"><small>Read More...</small></a></p>
                                                    </div>
                                                </div>
                                                <div class="search_result">
                                                    <div class="pull-left col-sm-2 col-xs-3"><img class="img-responsive" src="data/search/3.png"></div>
                                                    <div class="pull-left col-sm-10 col-xs-9">
                                                        <h4><a href="#">Contrast and Similiarity in Graphic Desigh</a></h4>
                                                        <p>Graphic design is the art of visual communication through the use of images, words, and ideas to give information to the viewers. <a href="#" class="pull-right"><small>Read More...</small></a></p>
                                                    </div>
                                                </div>
                                                <div class="search_result">
                                                    <div class="pull-left col-sm-2 col-xs-3"><img class="img-responsive" src="data/search/1.png"></div>
                                                    <div class="pull-left col-sm-10 col-xs-9">
                                                        <h4><a href="#">Contrast and Similiarity in Graphic Desigh</a></h4>
                                                        <p>Graphic design is the art of visual communication through the use of images, words, and ideas to give information to the viewers. <a href="#" class="pull-right"><small>Read More...</small></a></p>
                                                    </div>
                                                </div>
                                                <div class="search_result">
                                                    <div class="pull-left col-sm-2 col-xs-3"><img class="img-responsive" src="data/search/2.png"></div>
                                                    <div class="pull-left col-sm-10 col-xs-9">
                                                        <h4><a href="#">Contrast and Similiarity in Graphic Desigh</a></h4>
                                                        <p>Graphic design is the art of visual communication through the use of images, words, and ideas to give information to the viewers. <a href="#" class="pull-right"><small>Read More...</small></a></p>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="tab-pane fade" id="images-1">

                                                <p class="col-md-12">Images Search Results</p>

                                            </div>
                                            <div class="tab-pane fade" id="contacts-1">

                                                <p>Contacts Search Results</p>

												<table id="example" class="display table table-hover table-condensed" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th style="background-color:LightGray;width: 1px;"></th>
															<th style="background-color:LightGray;"><?php echo $cKD_USR?></th>
															<th style="background-color:LightGray;"><?php echo $cNM_USR?></th>
														</tr>
													</thead>

													<tbody>
														<?php
															while($aREC_TB_USER=mysql_fetch_array($qQ_USER)) {
															echo '<tr>';
																echo '<td class=""><div class="star"><i class="fa fa-user icon-xs icon-default"></i></div></td>';
																echo "<td><span><a href='?action=update&KODE_USER=$aREC_TB_USER[USER_CODE]'>".$aREC_TB_USER['USER_CODE']."</a></span></td>";
																echo "<td><a href='?action=update&KODE_USER=$aREC_TB_USER[USER_CODE]'>".$aREC_TB_USER['USER_NAME']."</a></td>";
															echo '</tr>';
															}
														?>
													</tbody>
												</table>


                                            </div>

                                            <div class="tab-pane fade" id="projects-1">
                                                <p>Projects Search Results</p>

                                            </div>

                                            <div class="tab-pane fade" id="map-1">
                                                <p>Location and Maps Search Results</p>
                                            </div>
                                            <div class="tab-pane fade" id="videos-1">
                                                <p>Videos Search Results</p>
                                            </div>
                                            <div class="tab-pane fade" id="messages-1">
                                                <p>Messages Search Results</p>
                                            </div>
                                            <div class="tab-pane fade" id="profile-1">
                                                <p>Profile Search Results</p>
                                            </div>

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
					<?php
					  require_once("scr_groups_chat.php");
					  require_once("scr_more_favorites.php");
					  require_once("scr_more_contact.php");
					?>
                </div>
            </div>

            <div class="chatapi-windows ">	</div>    
		</div>
        <!-- END CONTAINER -->
        <!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->

		<?php
			require_once("js_framework.php");
		?>

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


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



