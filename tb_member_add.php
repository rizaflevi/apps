<?php
	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
	}

	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$q_GRUP=SYS_QUERY("select * from grouplgn where APP_CODE='$cFILTER_CODE'");
	$q_TIPE=SYS_QUERY("select * from tipe_otl where APP_CODE='$cFILTER_CODE'");
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

                            <div class="pull-left">
                                <h1 class="title"><?php echo S_MSG('CB98','Anggota Baru')?></h1>
							</div>

                            <div class="pull-right hidden-xs"></div>

                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <section class="box ">
            <!-- 
                            <header class="panel_header">
                                <h2 class="title pull-left">Basic Info</h2>
                                <div class="actions panel_actions pull-right">
                                    <i class="box_toggle fa fa-chevron-down"></i>
                                    <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                                    <i class="box_close fa fa-times"></i>
                                </div>
                            </header>	-->
                            <div class="content-body">
                                <div class="row">
                                    <form action ="#" method="post">
                                        <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
														<div class="form-group">
															<label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('CB07','Kode Anggota')?></label>
     <!-- 															<span class="desc"></span>	-->
															<div class="col-sm-8">
																<input type="text" class="form-label-900" name='KODE_ANGG' id="field-1"></br>
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-4 form-label-700" for="field-2"><?php echo S_MSG('CB03','Nama Lengkap')?></label>
															<div class="col-sm-8">
																<input type="text" class="form-label-900" name='NAMA_ANGG' id="field-2">
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-4 form-label-700" for="field-3"><?php echo S_MSG('PA48','No. KTP')?></label>
															<div class="col-sm-8">
																<input type="text" class="form-label-900" name='NOMOR_KTP' id="field-3">
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-4 form-label-700" for="field-4"><?php echo S_MSG('CS37','Kelompok')?></label>
															<div class="col-sm-8">
															<select class="form-control">
																	<option value=""></option>
																	<?php
																		while($aGROUP_LGN=SYS_FETCH($q_GRUP)) {
																			echo '<option value="'.$aGROUP_LGN['NAMA_GRP'].'">'.$aGROUP_LGN['NAMA_GRP'].'</option>';
																		}
																	?>
															</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-sm-4 form-label-700" for="field-5"><?php echo S_MSG('CR02','Tipe')?></label>
															<div class="col-sm-8">
																<select class="form-control">
																	<option value=""></option>
																	<?php
																		while($aGROUP_TPE=SYS_FETCH($q_TIPE)) {
																			echo '<option value="'.$aGROUP_TPE['NAMATIPE'].'">'.$aGROUP_TPE['NAMATIPE'].'</option>';
																		}
																	?>
																</select>
															</div>
														</div><br></br>
														<div class="form-group">
                                                <label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('CB59','Posisi Anggota')?></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="field-1"></br>
                                                </div>
														</div>
                                            <div class="form-group">
                                                <label class="col-sm-4 form-label-700" for="field-5"><?php echo S_MSG('CB55','Tanggal Lahir')?></label>
                                                <span class="desc">e.g. "04/03/2015"</span>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control datepicker" data-format="mm/dd/yyyy" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 form-label-700" for="field-5"><?php echo S_MSG('F018','Jenis')?></label>
												<div class="col-sm-8">
													<select class="form-control">
														<option></option>
														<option><?php echo S_MSG('PD12','Pria')?></option>
														<option><?php echo S_MSG('PD13','Wanita')?></option>
													</select>
												</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="field-1">Profile Image</label>
                                                <span class="desc"></span>
                                                <div class="controls">
                                                    <input type="file" class="form-control" id="field-5">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="field-6">Brief</label>
                                                <span class="desc">e.g. "Enter any size of text description here"</span>
                                                <div class="controls">
                                                    <textarea class="form-control autogrow" cols="5" id="field-6"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="field-1">Website URL</label>
                                                <span class="desc"></span>
                                                <div class="controls">
                                                    <input type="text" class="form-control" id="field-61">
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
                        </section></div>


                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <section class="box ">
                            <header class="panel_header">
                                <h2 class="title pull-left">Staff Account Info</h2>
                                <div class="actions panel_actions pull-right">
                                    <i class="box_toggle fa fa-chevron-down"></i>
                                    <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                                    <i class="box_close fa fa-times"></i>
                                </div>
                            </header>
                            <div class="content-body">
                                <div class="row">
                                    <form action="#" method="post">
                                        <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                            <div class="form-group">
                                                <label class="form-label" for="field-1">Email</label>
                                                <span class="desc"></span>
                                                <div class="form-label-900">
                                                    <input type="text" class="form-control" id="field-3">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="field-2">Phone</label>
                                                <span class="desc">e.g. "(534) 253-5353"</span>
                                                <div class="controls">
                                                    <input type="text" class="form-control" id="field-2" data-mask="phone"  placeholder="(999) 999-9999">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="field-2">Password</label>
                                                <span class="desc"></span>
                                                <div class="controls">
                                                    <input type="password" value="" class="form-control" id="field-2">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="field-2">Confirm Password</label>
                                                <span class="desc"></span>
                                                <div class="controls">
                                                    <input type="password" value="" class="form-control" id="field-21">
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
                        </section></div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <section class="box ">
                            <header class="panel_header">
                                <h2 class="title pull-left">Staff Social Media Info</h2>
                                <div class="actions panel_actions pull-right">
                                    <i class="box_toggle fa fa-chevron-down"></i>
                                    <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                                    <i class="box_close fa fa-times"></i>
                                </div>
                            </header>
                            <div class="content-body">
                                <div class="row">
                                    <form action="#" method="post">
                                        <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                            <div class="form-group">
                                                <label class="form-label" for="field-1">Facebook URL</label>
                                                <span class="desc"></span>
                                                <div class="controls">
                                                    <input type="text" class="form-control" value="http://www.facebook.com/userID" id="field-31">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="field-1">Twitter URL</label>
                                                <span class="desc"></span>
                                                <div class="controls">
                                                    <input type="text" class="form-control" value="http://www.twitter.com/userID" id="field-41">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="field-1">Google Plus URL</label>
                                                <span class="desc"></span>
                                                <div class="controls">
                                                    <input type="text" class="form-control" value="http://www.plus.google.com/userID" id="field-51">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12 padding-bottom-30">
                                            <div class="text-left">
                                                <button type="button" class="btn btn-primary">Save</button>
                                                <button type="button" class="btn">Cancel</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>

                            </div>
                        </section></div>

                </section>
            </section>
			<?php	include "scr_chat.php";	?>
			</div>
			<?php	require_once("js_framework.php");	?>
        <script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> <script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script><script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
        <script src="assets/js/scripts.js" type="text/javascript"></script> 

        <div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Section Settings</h4>
                    </div>
                    <div class="modal-body">


                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        <button class="btn btn-success" type="button">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>



