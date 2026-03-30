<?php
// TODO : kalender cuti
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();
	$cHEADER		= 'Calendar';
	$cAPP_CODE = $_SESSION['data_FILTER_CODE']; $cUSERCODE = $_SESSION['gUSERCODE'];
?>
<!DOCTYPE html>
<html class=" ">
	<?php   require_once("scr_header.php");	 require_once("scr_topbar.php"); ?>
    <link href="assets/plugins/calendar/fullcalendar.css" rel="stylesheet" type="text/css" media="screen"/><link href="assets/plugins/icheck/skins/minimal/minimal.css" rel="stylesheet" type="text/css" media="screen"/>        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
    <!-- BEGIN BODY -->
    <body class=" "><!-- START TOPBAR -->
        <?php	require_once("scr_topbar.php");	?>
        <div class="page-container row-fluid">
            <div class="page-sidebar ">
                <div class="page-sidebar-wrapper" id="main-menu-wrapper">
                    <?php	require_once("scr_menu.php");	?>
                </div>
                <?php	require_once("project_info.php");	?>
            </div>

            <section id="main-content" class=" ">
                <section class="wrapper main-wrapper" style=''>

                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <header class="panel_header">
                            <h2 class="title pull-left"><?php echo $cHEADER?></h2>
                            <div class="actions panel_actions pull-right">
                                <i class="box_setting fa fa-question" data-toggle="modal" href="#section-settings">Help</i>
                            </div>
                        </header>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-lg-12">
                        <section class="box nobox">
                            <div class="content-body">
                                <div class="row">
                                    <div class="col-md-2 col-sm-3 col-xs-3">

                                        <form method="post" id="add_event_form">
                                            <input type="text" class="form-control new-event-form" placeholder="Add new event..." />
                                        </form>

                                        <div id='external-events'>
                                            <h4>Draggable Events</h4>
                                            <div class='fc-event bg-purple'>My Event 1</div>
                                            <div class='fc-event bg-purple'>My Event 2</div>
                                            <div class='fc-event bg-purple'>My Event 3</div>
                                            <div class='fc-event bg-purple'>My Event 4</div>
                                            <div class='fc-event bg-purple'>My Event 5</div>
                                            <br>
                                            <label class="form-label" for='drop-remove' style="font-size:13px;">
                                                <input type="checkbox"  id='drop-remove' class="iCheck"> <span>Drop&nbsp;&&nbsp;Remove</span>
                                            </label>

                                            <h4>Created Events</h4>
                                        </div>
                                    </div>


                                    <div id='calendar' class="col-md-10 col-sm-9 col-xs-9"></div>

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

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
        <script src="assets/plugins/calendar/moment.min.js" type="text/javascript"></script>
        <script src="assets/plugins/jquery-ui/smoothness/jquery-ui.min.js" type="text/javascript"></script>
        <script src="assets/plugins/calendar/fullcalendar.min.js" type="text/javascript"></script>
        <script src="assets/plugins/icheck/icheck.min.js" type="text/javascript"></script>

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
                    </div>
                </div>
            </div>
        </div>
        <!-- modal end -->
    </body>
</html>



