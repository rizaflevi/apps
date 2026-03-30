<?php
/**
 * mail_compose.php
 * tulis email baru
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
        <title>Rainbow Sys : Mailbox</title>
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

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - --> 
        <link href="assets/plugins/bootstrap3-wysihtml5/css/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" media="screen"/>
		<link href="assets/plugins/tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" media="screen"/>

        <!-- CORE CSS TEMPLATE - START -->
        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>
        <!-- CORE CSS TEMPLATE - END -->

    </head>
    <!-- END HEAD -->

    <!-- BEGIN BODY -->
    <body class=" "><!-- START TOPBAR -->
		<?php	require_once("scr_topbar.php");	?>
        <div class="page-container row-fluid">
           
            <div class="page-sidebar ">		<!-- SIDEBAR - START -->
                
                <div class="page-sidebar-wrapper" id="main-menu-wrapper"> 		<!-- MAIN MENU - START -->
					<?php	require_once("scr_user_info.php");	?>
                    <ul class='wraplist'>	
						<?php	require_once("scr_menu.php");	?>
                    </ul>
                </div>
                <div class="project-info">	</div>

            </div>
            <!--  SIDEBAR - END -->
            <!-- START CONTENT -->
            <section id="main-content" class=" ">
                <section class="wrapper main-wrapper" style=''>

                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <div class="page-title">

                            <div class="pull-left">
                                <h1 class="title">Mailbox</h1>	
							</div>

                            <div class="pull-right hidden-xs">
                                <ol class="breadcrumb">
                                    <li>
                                        <a href="index.php"><i class="fa fa-home"></i>Home</a>
                                    </li>
                                    <li>
                                        <a href="mail_inbox.php">Mailbox</a>
                                    </li>
                                    <li class="active">
                                        <strong>Compose</strong>
                                    </li>
                                </ol>
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>


                    <div class="col-lg-12">
                        <section class="box nobox">
                            <div class="content-body">    <div class="row">

                                    <div class="col-md-3 col-sm-4 col-xs-12">

                                        <a class="btn btn-primary btn-block" href='mail_compose.php'>Compose</a>

                                        <ul class="list-unstyled mail_tabs">
                                            <li class="">
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
                                                    <h3 class="mail_head">Compose</h3>
                                                    <i class='fa fa-refresh icon-primary icon-xs icon-orange mail_head_icon'></i>
                                                    <i class='fa fa-search icon-primary icon-xs icon-orange mail_head_icon'></i>
                                                    <i class='fa fa-cog icon-primary icon-xs icon-orange mail_head_icon pull-right'></i>
                                                </div>

                                                <div class="col-md-12 col-sm-12 col-xs-12 mail_view_title">

                                                    <div class='pull-right'>
                                                        <button class="btn btn-default btn-icon" rel="tooltip" data-color-class="primary" data-animate=" animated fadeIn" data-toggle="tooltip" data-original-title="Send" data-placement="top">
                                                            <i class="fa fa-paper-plane-o icon-xs"></i>
                                                        </button>
                                                        <button class="btn btn-default btn-icon" rel="tooltip" data-color-class="primary" data-animate=" animated fadeIn" data-toggle="tooltip" data-original-title="Save" data-placement="top">
                                                            <i class="fa fa-floppy-o icon-xs"></i>
                                                        </button>
                                                        <button class="btn btn-default btn-icon" rel="tooltip" data-color-class="primary" data-animate=" animated fadeIn" data-toggle="tooltip" data-original-title="Trash" data-placement="top">
                                                            <i class="fa fa-trash-o icon-xs"></i>
                                                        </button>
                                                    </div>

                                                </div>

                                                <div class="col-md-12 col-sm-12 col-xs-12 mail_view_info">

                                                    <div class="form-group mail_cc_bcc">
                                                        <label class="form-label" for="field-1">To:</label>
                                                        <span class="desc">e.g. "someemail@example.com"</span>
                                                        <div class="labels"><span class='label label-secondary cc'>CC</span> <span class='label label-secondary bcc'>BCC</span>
                                                        </div>
                                                        <div class="controls">
                                                            <input type="text" class="form-control mail_compose_to" value="" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group mail_compose_cc">
                                                        <label class="form-label" for="field-1">CC:</label>
                                                        <span class="desc">e.g. "someemail@example.com"</span>
                                                        <div class="controls">
                                                            <input type="text" class="form-control mail_compose_to" value="" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group mail_compose_bcc">
                                                        <label class="form-label" for="field-1">BCC:</label>
                                                        <span class="desc">e.g. "someemail@example.com"</span>
                                                        <div class="controls">
                                                            <input type="text" class="form-control mail_compose_to" value="" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label" for="field-1">Subject:</label>
                                                        <span class="desc">e.g. "Meeting in 1st week"</span>
                                                        <div class="controls">
                                                            <input type="text" class="form-control" value="" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label" for="field-1">Message:</label>
                                                        <textarea class="mail-compose-editor" placeholder="Enter text ..." style="width: 100%; height: 250px; font-size: 14px; line-height: 23px;padding:15px;"></textarea>
                                                    </div>

                                                </div>

                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class='pull-left'>
                                                        <button class="btn btn-primary">
                                                            <i class="fa fa-paper-plane-o icon-xs"></i> &nbsp; SEND
                                                        </button>
                                                        <button class="btn btn-purple">
                                                            <i class="fa fa-floppy-o icon-xs"></i> &nbsp; SAVE
                                                        </button>
                                                        <button class="btn btn-secondary">
                                                            <i class="fa fa-trash-o icon-xs"></i> &nbsp; TRASH
                                                        </button>
                                                    </div>
                                                </div>

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
			<?php	include "scr_chat.php";	?>
		</div>
        <!-- END CONTAINER -->
        <!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->

		<?php	require_once("js_framework.php");	?>

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE -  --> 
        <script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/tagsinput/js/bootstrap-tagsinput.min.js" type="text/javascript"></script>
		<script src="assets/plugins/bootstrap3-wysihtml5/js/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>

        <!-- CORE TEMPLATE JS - --> 
        <script src="assets/js/scripts.js" type="text/javascript"></script> 

        <!-- Sidebar Graph - 
        <script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="assets/js/chart-sparkline.js" type="text/javascript"></script>	--> 


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



