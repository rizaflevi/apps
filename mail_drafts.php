<?php
/**
 * mail_draftse.php
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
        <title>Rainbow System : Mailbox</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon" />    <!-- Favicon -->
        <link rel="apple-touch-icon-precomposed" href="assets/images/apple-touch-icon-57-precomposed.png">	<!-- For iPhone -->
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/apple-touch-icon-114-precomposed.png">    <!-- For iPhone 4 Retina display -->
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/apple-touch-icon-72-precomposed.png">    <!-- For iPad -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/apple-touch-icon-144-precomposed.png">    <!-- For iPad Retina display -->

        <!-- CORE CSS FRAMEWORK -  -->
        <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE -  --> 
        <link href="assets/plugins/icheck/skins/minimal/minimal.css" rel="stylesheet" type="text/css" media="screen"/>        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 

        <!-- CORE CSS TEMPLATE -  -->
        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>

    </head>
    <!-- END HEAD -->

    <!-- BEGIN BODY -->
    <body class=" ">
		<?php	require_once("scr_topbar.php");	?>
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

                            <div class="pull-left">
                                <h1 class="title">Mailbox</h1>
							</div>

                            <div class="pull-right hidden-xs">
                                <ol class="breadcrumb">
                                    <li>
                                        <a href="index-main.php"><i class="fa fa-home"></i>Home</a>
                                    </li>
                                    <li>
                                        <a href="mail_inbox.php">Mailbox</a>
                                    </li>
                                    <li class="active">
                                        <strong>draft</strong>
                                    </li>
                                </ol>
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-lg-12">
                        <section class="box nobox">
                            <div class="content-body">
								<div class="row">

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
                                            <li class="active">
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

                                                    <h3 class="mail_head">Drafts</h3>
                                                    <i class='fa fa-refresh icon-primary icon-xs icon-orange mail_head_icon'></i>
                                                    <i class='fa fa-search icon-primary icon-xs icon-orange mail_head_icon'></i>
                                                    <i class='fa fa-cog icon-primary icon-xs icon-orange mail_head_icon pull-right'></i>


                                                </div>

                                                <div class="col-md-12 col-sm-12 col-xs-12">

                                                    <div class="pull-left">
                                                        <div class="btn-group mail_more_btn">
                                                            <button type="button" class="btn btn-default"><input type='checkbox' class="iCheck"> All</button>
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="#">All</a></li>
                                                                <li><a href="#">Read</a></li>
                                                                <li><a href="#">Unread</a></li>
                                                                <li><a href="#">Starred</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <nav class='pull-right'>
                                                        <ul class="pager mail_nav">
                                                            <li><a href="#"><i class='fa fa-arrow-left icon-xs icon-orange icon-secondary'></i></a></li>
                                                            <li><a href="#"><i class='fa fa-arrow-right icon-xs icon-orange icon-secondary'></i></a></li>
                                                        </ul>
                                                    </nav>
                                                    <span class='pull-right mail_count_nav text-muted'><strong>1</strong> to <strong>20</strong> of 3247</span>

                                                </div>

                                                <div class="col-md-12 col-sm-12 col-xs-12 mail_list">
                                                    <table class="table table-striped table-hover">
                                                        <tr class="unread" id="mail_msg_1">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">John Smith</td>
                                                            <td class="open-view"><span class="label label-primary">Family</span>&nbsp;<span class="msgtext">Hello, hope you having a great day ahead.</span></td>
                                                            <td class="open-view"><span class="msg_time">19:34</span></td>
                                                        </tr>
                                                        <tr class="unread" id="mail_msg_2">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Laura Willson</td>
                                                            <td class="open-view"><span class="msgtext">Your product seems amazingly smart and elegant to use.</span></td>
                                                            <td class="open-view"><span class="msg_time">21:54</span></td>
                                                        </tr>
                                                        <tr class="unread" id="mail_msg_3">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Jane D.</td>
                                                            <td class="open-view"><span class="msgtext">We play, dance and love. Share love all around you.</span></td>
                                                            <td class="open-view"><span class="msg_time">22:28</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_4">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">John Smith</td>
                                                            <td class="open-view"><span class="msgtext">Hello, hope you having a great day ahead.</span></td>
                                                            <td class="open-view"><span class="msg_time">Yesterday</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_5">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Laura Willson</td>
                                                            <td class="open-view"><span class="msgtext">Your product seems amazingly smart and elegant to use.</span></td>
                                                            <td class="open-view"><span class="msg_time">Yesterday</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_6">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Jane D.</td>
                                                            <td class="open-view"><span class="label label-info">Work</span>&nbsp;<span class="msgtext">We play, dance and love. Share love all around you.</span></td>
                                                            <td class="open-view"><span class="msg_time">28 Feb</span></td>
                                                        </tr>
                                                        <tr class="unread">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">John Smith</td>
                                                            <td class="open-view"><span class="label label-info">Work</span>&nbsp;<span class="msgtext">Hello, hope you having a great day ahead.</span></td>
                                                            <td class="open-view"><span class="msg_time">25 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_8">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Laura Willson</td>
                                                            <td class="open-view"><span class="msgtext">Your product seems amazingly smart and elegant to use.</span></td>
                                                            <td class="open-view"><span class="msg_time">25 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_9">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Jane D.</td>
                                                            <td class="open-view"><span class="msgtext">We play, dance and love. Share love all around you.</span></td>
                                                            <td class="open-view"><span class="msg_time">25 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_10">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">John Smith</td>
                                                            <td class="open-view"><span class="label label-success">IMP</span>&nbsp;<span class="msgtext">Hello, hope you having a great day ahead.</span></td>
                                                            <td class="open-view"><span class="msg_time">25 Feb</span></td>
                                                        </tr>
                                                        <tr class="unread">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Laura Willson</td>
                                                            <td class="open-view"><span class="msgtext">Your product seems amazingly smart and elegant to use.</span></td>
                                                            <td class="open-view"><span class="msg_time">21 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_12">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Jane D.</td>
                                                            <td class="open-view"><span class="msgtext">We play, dance and love. Share love all around you.</span></td>
                                                            <td class="open-view"><span class="msg_time">21 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_13">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">John Smith</td>
                                                            <td class="open-view"><span class="label label-success">IMP</span>&nbsp;<span class="msgtext">Hello, hope you having a great day ahead.</span></td>
                                                            <td class="open-view"><span class="msg_time">21 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_14">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Laura Willson</td>
                                                            <td class="open-view"><span class="msgtext">Your product seems amazingly smart and elegant to use.</span></td>
                                                            <td class="open-view"><span class="msg_time">21 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_15">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Jane D.</td>
                                                            <td class="open-view"><span class="msgtext">We play, dance and love. Share love all around you.</span></td>
                                                            <td class="open-view"><span class="msg_time">21 Feb</span></td>
                                                        </tr>
                                                        <tr class="unread">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">John Smith</td>
                                                            <td class="open-view"><span class="label label-info">Work</span>&nbsp;<span class="msgtext">Hello, hope you having a great day ahead.</span></td>
                                                            <td class="open-view"><span class="msg_time">17 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_17">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Laura Willson</td>
                                                            <td class="open-view"><span class="msgtext">Your product seems amazingly smart and elegant to use.</span></td>
                                                            <td class="open-view"><span class="msg_time">17 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_18">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Jane D.</td>
                                                            <td class="open-view"><span class="msgtext">We play, dance and love. Share love all around you.</span></td>
                                                            <td class="open-view"><span class="msg_time">17 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_19">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">John Smith</td>
                                                            <td class="open-view"><span class="label label-danger">Urgent</span>&nbsp;<span class="msgtext">Hello, hope you having a great day ahead.</span></td>
                                                            <td class="open-view"><span class="msg_time">17 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_20">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Laura Willson</td>
                                                            <td class="open-view"><span class="msgtext">Your product seems amazingly smart and elegant to use.</span></td>
                                                            <td class="open-view"><span class="msg_time">17 Feb</span></td>
                                                        </tr>
                                                        <tr class="unread">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Jane D.</td>
                                                            <td class="open-view"><span class="msgtext">We play, dance and love. Share love all around you.</span></td>
                                                            <td class="open-view"><span class="msg_time">16 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_22">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">John Smith</td>
                                                            <td class="open-view"><span class="label label-primary">Family</span>&nbsp;<span class="msgtext">Hello, hope you having a great day ahead.</span></td>
                                                            <td class="open-view"><span class="msg_time">16 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_23">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Laura Willson</td>
                                                            <td class="open-view"><span class="msgtext">Your product seems amazingly smart and elegant to use.</span></td>
                                                            <td class="open-view"><span class="msg_time">16 Feb</span></td>
                                                        </tr>
                                                        <tr id="mail_msg_24">
                                                            <td class=""><input class="iCheck" type="checkbox"></td>
                                                            <td class=""><div class="star"><i class='fa fa-star-o icon-xs icon-orange'></i></div></td>
                                                            <td class="open-view">Jane D.</td>
                                                            <td class="open-view"><span class="msgtext">We play, dance and love. Share love all around you.</span></td>
                                                            <td class="open-view"><span class="msg_time">16 Feb</span></td>
                                                        </tr>
                                                    </table>
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
			<?php	include "scr_chat.php";	?>
		</div>
		<?php	require_once("js_framework.php");	?>
        <script src="assets/plugins/icheck/icheck.min.js" type="text/javascript"></script>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 
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



