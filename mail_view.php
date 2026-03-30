<?php
/**
 * mail_view.php
 * baca email user
 */

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
	}
?>

<!DOCTYPE html>
<html class=" ">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>Ultra Admin : Mailbox</title>
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
						<?php
							require_once("scr_user_info.php");
							require_once("scr_menu.php");
						?>

                </div>
                <!-- MAIN MENU - END 



                <div class="project-info">

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

                </div>-->



            </div>
            <!--  SIDEBAR - END -->
            <!-- START CONTENT -->
            <section id="main-content" class=" ">
                <section class="wrapper main-wrapper" style=''>

                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <div class="page-title">

                            <div class="pull-left">
                                <h1 class="title">Mailbox</h1>                            </div>

                            <div class="pull-right hidden-xs">
                                <ol class="breadcrumb">
                                    <li>
                                        <a href="index.php"><i class="fa fa-home"></i>Home</a>
                                    </li>
                                    <li>
                                        <a href="mail_inbox.php">Mailbox</a>
                                    </li>
                                    <li class="active">
                                        <strong>View</strong>
                                    </li>
                                </ol>
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>


                    <div class="col-lg-12">
                        <section class="box nobox">
                            <div class="content-body">    <div class="row">

                                    <div class="col-md-3  col-sm-4 col-xs-12">


                                        <a class="btn btn-primary btn-block" href='mail_compose.php'>Compose</a>

                                        <ul class="list-unstyled mail_tabs">
                                            <li class="active">
                                                <a href="mail_inbox.php">
                                                    <i class="fa fa-inbox"></i> Inbox <span class="badge badge-primary pull-right">6</span>
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="mail_sent.php">
                                                    <i class="fa fa-send-o"></i> Sent
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="mail_drafts.php">
                                                    <i class="fa fa-edit"></i> Drafts <span class="badge badge-orange pull-right">2</span>
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="mail_important.php">
                                                    <i class="fa fa-star-o"></i> Important
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="mail_trash.php">
                                                    <i class="fa fa-trash-o"></i> Trash
                                                </a>
                                            </li>
                                        </ul>

                                    </div>

                                    <div class="col-md-9 col-sm-8 col-xs-12">
                                        <div class="mail_content">

                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">

                                                    <h3 class="mail_head">View</h3>
                                                    <i class='fa fa-refresh icon-primary icon-xs icon-orange mail_head_icon'></i>
                                                    <i class='fa fa-search icon-primary icon-xs icon-orange mail_head_icon'></i>
                                                    <i class='fa fa-cog icon-primary icon-xs icon-orange mail_head_icon pull-right'></i>


                                                </div>

                                                <div class="col-md-12  col-sm-12 col-xs-12 mail_view_title">

                                                    <div class="pull-left">
                                                        <h3 class="">New Project Alloted to your team.</h3>
                                                    </div>

                                                    <div class='pull-right'>
                                                        <button class="btn btn-default btn-icon" rel="tooltip" data-color-class="primary" data-animate=" animated fadeIn" data-toggle="tooltip" data-original-title="Reply" data-placement="top">
                                                            <i class="fa fa-reply icon-xs"></i>
                                                        </button>
                                                        <button class="btn btn-default btn-icon" rel="tooltip" data-color-class="primary" data-animate=" animated fadeIn" data-toggle="tooltip" data-original-title="Reply All" data-placement="top">
                                                            <i class="fa fa-reply-all icon-xs"></i>
                                                        </button>
                                                        <button class="btn btn-default btn-icon" rel="tooltip" data-color-class="primary" data-animate=" animated fadeIn" data-toggle="tooltip" data-original-title="Forward" data-placement="top">
                                                            <i class="fa fa-mail-forward icon-xs"></i>
                                                        </button>
                                                        <button class="btn btn-default btn-icon" rel="tooltip" data-color-class="primary" data-animate=" animated fadeIn" data-toggle="tooltip" data-original-title="Attachments" data-placement="top">
                                                            <i class="fa fa-paperclip icon-xs"></i>
                                                        </button>
                                                        <button class="btn btn-default btn-icon" rel="tooltip" data-color-class="primary" data-animate=" animated fadeIn" data-toggle="tooltip" data-original-title="Trash" data-placement="top">
                                                            <i class="fa fa-trash-o icon-xs"></i>
                                                        </button>
                                                    </div>

                                                </div>

                                                <div class="col-md-12  col-sm-12 col-xs-12 mail_view_info">

                                                    <div class="pull-left">
                                                        <span class=""><strong>Bruce Wayne</strong> (brucewayne@example.com) to <strong>You</strong></span>
                                                    </div>

                                                    <div class='pull-right'>
                                                        <span class='msg_ts text-muted'>12:40 PM, Today</span>
                                                    </div>

                                                </div>



                                                <div class="col-md-12  col-sm-12 col-xs-12 mail_view">
                                                    <p>Hello Jason,</p>
                                                    <h4>What is Graphic Design?</h4>
                                                    <p>Graphic design is the art of visual communication through the use of images, words, and ideas to give information to the viewers. Graphic design can be used for advertising, or just for entertainment intended for the mind.</p>
                                                    <br>
                                                    <h4>Balance</h4>
                                                    <p>Designs in balance (or equilibrium) have their parts arrangement planned, keeping a coherent visual pattern (color, shape, space). "Balance" is a concept based on human perception and the complex nature of the human senses of weight and proportion. Humans can evaluate these visual elements in several situations to find a sense of balance. A design composition does not have to be symmetrical or linear to be considered balanced, the balance is global to all elements even the absence of content. In this context perfectly symmetrical and linear compositions are not necessarily balanced and so asymmetrical or radial distributions of text and graphic elements can achieve balance in a composition.</p>
                                                    <br>
                                                    <h4>Contrast</h4>
                                                    <p>Distinguishing by comparing/creating differences. Some ways of creating contrast among elements in the design include using contrasting colors, sizes, shapes, locations, or relationships. For text, contrast is achieved by mixing serif and sans-serif on the page, by using very different type styles, or by using type in surprising or unusual ways. Another way to describe contrast, is to say "a small object next to a large object will look smaller". As contrast in size diminishes, monotony is approached.</p>
                                                    <br>
                                                </div>


                                                <div class="col-md-12  col-sm-12 col-xs-12 mail_view_attach">
                                                    <h3>
                                                        <i class="fa fa-paperclip icon-sm"></i> Attachments
                                                    </h3><br>

                                                    <ul class="list-unstyled list-inline">
                                                        <li>
                                                            <a href="#" class="file">
                                                                <img src="data/mail/attach-1.png" class="img-thumbnail">
                                                            </a>

                                                            <a href="#" class="title">
                                                                Project_details.jpg
                                                                <span>10KB</span>
                                                            </a>

                                                            <div class="actions">
                                                                <a href="#">View</a> - 
                                                                <a href="#">Download</a>
                                                            </div>
                                                        </li>

                                                        <li>
                                                            <a href="#" class="file">
                                                                <img src="data/mail/attach-2.png" class="img-thumbnail">
                                                            </a>

                                                            <a href="#" class="title">
                                                                Guidlines.jpg
                                                                <span>14KB</span>
                                                            </a>

                                                            <div class="actions">
                                                                <a href="#">View</a> - 
                                                                <a href="#">Download</a>
                                                            </div>
                                                        </li>

                                                        <li>
                                                            <a href="#" class="file">
                                                                <img src="data/mail/attach-3.png" class="img-thumbnail">
                                                            </a>

                                                            <a href="#" class="title">
                                                                Team_info.jpg
                                                                <span>12KB</span>
                                                            </a>

                                                            <div class="actions">
                                                                <a href="#">View</a> - 
                                                                <a href="#">Download</a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>


                                                <div class="col-md-12  col-sm-12 col-xs-12 mail_view_reply">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <textarea class="form-control autogrow" cols="5" id="field-7" placeholder="Reply or Forward this message" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 120px;"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </section></div>


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


            <div class="chatapi-windows ">


            </div>    </div>
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
        <script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


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



