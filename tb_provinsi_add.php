<?php
	include "sysfunction.php";
?>
<!DOCTYPE html>
<html class=" ">
	<?php
		require_once("scr_header.php");
		require_once("scr_topbar.php");
	?>
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

            </div>
            <!--  SIDEBAR - END -->
            <!-- START CONTENT -->
            <section id="main-content" class=" ">
                <section class="wrapper main-wrapper" style=''>

                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <div class="page-title">

                            <div class="pull-left">
                                <h1 class="title"><?php echo S_MSG('CB68','DPD Baru')?></h1>                            </div>

                            <div class="pull-right hidden-xs">
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <section class="box ">
                            <div class="content-body">
                                <div class="row">
                                    <form action ="#tb_provinsi_crud.php?action=create" method="post">
                                        <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('CB02','Kode DPD')?></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="field-1"></br>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('CB61','Nama DPD')?></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="field-2"></br>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('F005','Alamat')?></label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control autogrow" cols="5" id="field-6"></textarea></br>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-4 form-label-700" for="field-7"><?php echo S_MSG('F006','Nomor Telpon')?></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="field-2" data-mask="phone"  placeholder="(999) 999-9999"></br>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('CO11','Email Address')?></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="field-3"></br>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('CO12','Web Sites')?></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="field-61"></br>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="field-1"><?php echo S_MSG('TC36','Foto kantor')?></label>
                                                <span class="desc"></span>
                                                <div class="controls">
                                                    <input type="file" class="form-control" id="field-5">
                                                </div>
											</div>
										</div>

                                        <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12 padding-bottom-30">
                                            <div class="text-left">
                                                <button type="button" class="btn btn-primary"><?php echo S_MSG('F301','Save')?></button>
                                                <button type="button" class="btn"><?php echo S_MSG('F302','Cancel')?></button>
                                            </div>
										</div>
                                    </form>
                                </div>


                            </div>
                        </section>
					</div>
                </section>
            </section>    <!-- END CONTENT -->
				<?php	include "scr_chat.php";	?>

			</div>		<!-- END CONTAINER -->
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
        <script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> <script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script><script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


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

                        Kode prov kosong .....

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



