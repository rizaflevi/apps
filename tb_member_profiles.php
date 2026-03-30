<?php
// tb_member_profiles.php

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
	}
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$ID_MEMBER = $_GET['ID_MEMBER'];
	$cQUERY=SYS_QUERY("select * FROM tb_member1 where APP_CODE='$cFILTER_CODE' and NMR_REG=$ID_MEMBER");
	$aMEMBER=SYS_FETCH($cQUERY);
	$cFILE_FOTO = 'data/images/FOTO_'.str_pad((string)$aMEMBER['KD_MEMBER'], 11, '0', STR_PAD_LEFT).'.jpg';
?>

	<!DOCTYPE html>
	<html class=" ">
		<div class='page-topbar '>
			<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
		</div>
		<div class="page-container row-fluid">
            <div class="page-sidebar ">
                <div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
					<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
                </div>
                <div class="project-info"></div>
            </div>

            <section id="main-content" class=" ">
				<section class="wrapper main-wrapper" style=''>

				<div class="clearfix"></div>

					<div class="col-lg-12">
						<section class="box nobox">
							 <div class="content-body">    
								<div class="row">
									<div class="col-md-3 col-sm-4 col-xs-12">
										<div class="uprofile-image">
											  <img src="<?php echo $cFILE_FOTO?>" class="img-responsive">
										</div>
										<div class="uprofile-name">
											<h3>
												<a href="#"><?php echo $aMEMBER['NM_DEPAN']?></a>
												<!-- Available statuses: online, idle, busy, away and offline -->
												<span class="uprofile-status online"></span>
											</h3>
											<p class="uprofile-title"><?php echo $aMEMBER['PEKERJAAN']?></p>
										</div>
										<div class="uprofile-info">
											<ul class="list-unstyled">
												<li><i class='fa fa-home'></i><?php echo $aMEMBER['ALAMAT']?></li>
												<li><i class='fa fa-mobile-phone'></i> <?php echo $aMEMBER['NO_TEL_HP']?></li>
											</ul>
										</div>

										<div class="uprofile-buttons">
											<a href="tb_member.php" class="btn btn-md btn-primary">List Anggota</a>
											<a href="#section-settings" data-toggle="modal" class="btn btn-md btn-primary">Print</a>	
										</div>
<!--
										<div class=" uprofile-social">
											<a href="#" class="btn btn-primary btn-md facebook"><i class="fa fa-facebook icon-xs"></i></a>
											<a href="#" class="btn btn-primary btn-md twitter"><i class="fa fa-twitter icon-xs"></i></a>
											<a href="#" class="btn btn-primary btn-md google-plus"><i class="fa fa-google-plus icon-xs"></i></a>
											<a href="#" class="btn btn-primary btn-md dribbble"><i class="fa fa-dribbble icon-xs"></i></a>
										</div> 
-->
									</div>

									<div class="col-md-9 col-sm-8 col-xs-12">
										<div class="uprofile-content">
											<div class="">

												<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
													<section class="box ">
														<header class="panel_header">
															<h2 class="help-block">Basic Info</h2>
															<div class="actions panel_actions pull-right">
																<i class="box_toggle fa fa-chevron-down"></i>
							<!--                                    <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
																 <i class="box_close fa fa-times"></i>	-->
															</div>
														</header>
														<div class="content-body">
															<div class="col-md-8 col-sm-9 col-xs-10">

																<div class="form-group">
																	<label class="form-label" for="field-4"><?php echo S_MSG('CB07','Kode Anggota')?></label>
																	<div class="controls">
																		<input type="text" value=<?php echo $aMEMBER['KD_MEMBER']?> class="form-label-900">
																	</div>
																</div>
																<div class="form-group">
																	<label class="form-label" for="field-1"><?php echo S_MSG('CB03','Nama')?></label>
																	<div class="controls">
																		<input type="text" value=<?php echo $aMEMBER['NM_DEPAN']?> class="form-label-900" id="field-1" >
																	</div>
																</div>

																<div class="form-group">
																	<label class="form-label" for="field-2"><?php echo S_MSG('PA48','No. KTP')?></label>
																	<div class="controls">
																		<input type="text" value="<?php echo $aMEMBER['NO_KTP']?>" class="form-label-900" id="field-2" >
																	</div>
																</div>

                                        <div class="form-group">
                                            <label class="form-label" for="field-3">Email</label>
                                            <span class="desc">e.g. "me@somesite.com"</span>
                                            <div class="controls">
                                                <input type="text" class="form-label-900" id="field-3" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="field-5"><?php echo S_MSG('CB22','Nomor Kartu')?></label>
											<div class="controls">
                                                <input type="text" id="field-5" value="<?php echo $aMEMBER['NMR_KARTU']?>" class="form-label-900" disabled="disabled">
											</div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="field-6">Text area</label>
                                            <div class="controls">
                                                <textarea class="form-label-900" cols="5" id="field-6"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="field-7">Auto grow</label>
                                            <span class="desc">e.g. "This text area field auto grows"</span>
                                            <div class="controls">
                                                <textarea class="form-control autogrow" cols="5" id="field-7" placeholder="This textarea will grow in size with new lines." style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 50px;"></textarea>
                                            </div>
                                        </div>


                                        <div class="form-group has-error">
                                            <label class="form-label" for="field-8">Error Field</label>
                                            <span class="desc">e.g. "Beautiful Mind"</span>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="field-8" placeholder="I am a error field">
                                            </div>
                                        </div>

                                        <div class="form-group has-warning">
                                            <label class="form-label" for="field-9">Warning Field</label>
                                            <span class="desc">e.g. "Beautiful Mind"</span>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="field-9" placeholder="I am a warning field" >
                                            </div>
                                        </div>

                                        <div class="form-group has-success">
                                            <label class="form-label" for="field-10">Success Field</label>
                                            <span class="desc">e.g. "Beautiful Mind"</span>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="field-10" placeholder="I am a success field" >
                                            </div>
                                        </div>

                                        <div class="form-group has-info">
                                            <label class="form-label" for="field-11">Info Field</label>
                                            <span class="desc">e.g. "Beautiful Mind"</span>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="field-11" placeholder="I am a info field" >
                                            </div>
                                        </div>

                                        <div class="form-group has-focus">
                                            <label class="form-label" for="field-12">Focus</label>
                                            <span class="desc">e.g. "Beautiful Mind"</span>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="field-12" placeholder="I am a focused field ">
                                            </div>
                                        </div>

                                        <div class="form-group has-help">
                                            <label class="form-label" for="field-13">Help Field</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="field-13" placeholder="I am a help field">
                                                <span class="help-block">A help or brief description message of the input field.</span>
                                            </div>
                                        </div>
                                    </div>
								</div>
							</section>
						</div>
											</div>                
										</div>
									</div>

													  <button type="submit" class="btn btn-primary">Save</button>
									  </div>
                            </div>
                        </section>
						</div>
					</section>
            </section>
			<?php	include "scr_chat.php";	?>

		</div>
		<?php	require_once("js_framework.php");	?>
        <script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 

        <div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Cetak profile anggota</h4>
                    </div>
                    <div class="modal-body">


                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>



