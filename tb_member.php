<?php
	include "sysfunction.php";


?>
<!DOCTYPE html>
<html class=" ">
	<?php	require_once("scr_header.php");		require_once("scr_topbar.php");	?>
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
                                <h1 class="title"><?php echo S_MSG('F039','Tabel Anggota')?></h1>                            </div>

								<div class="pull-right hidden-xs">
									<ol class="breadcrumb">
										<li>
											 <a href="tb_member_add.php"><i class="fa fa-plus-square"></i><?php echo S_MSG('CB98','Add Member')?></a>
										</li>
										<li>
											 <a href="tb_member_print.php"><i class="fa fa-print"></i><?php echo S_MSG('MS15','Cetak')?></a>
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12">
                        <section class="box ">
<!--                       
									<header class="panel_header">
                                <h2 class="title pull-left">All Staffs</h2>
                                <div class="actions panel_actions pull-right">
                                    <i class="box_toggle fa fa-chevron-down"></i>
                                    <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                                    <i class="box_close fa fa-times"></i>
                                </div>
									</header>	-->
									<div class="content-body">    
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">

												<div class="row">
													<div class="col-lg-6 col-sm-12 col-md-6 col-md-12">
															<div class="team-member col">
																 <div class="team-img col-lg-4 col-md-4 col-sm-4 col-xs-4">
																	  <img class="img-responsive" src="data/images/staff-1.jpg" alt="">
																 </div>
																 <div class="team-info col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
																	  <h4><a href="tb_member_profiles.php?ID_MEMBER='1171000102'">Mrs. Brodeur</a></h4>
																	  <span class='team-member-edit'><a href="hos-staff-edit.html"><i class='fa fa-pencil icon-xs'></i></a></span>
																	  <span>Salesman, Laboratory<br>
																			<small>Age:</small> 45 yrs<br>
																			<small>Phone:</small> +1 233 454 4343<br>
																			<small>Email:</small> email@example.com</span>
																 </div>
															</div>
													</div>
													<div class="col-lg-6 col-sm-12 col-md-6 col-md-12">
															<div class="team-member col">
																 <div class="team-img col-lg-4 col-md-4 col-sm-4 col-xs-4">
																	  <img class="img-responsive" src="data/images/staff-2.jpg" alt="">
																 </div>
																 <div class="team-info col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
																	  <h4><a href="hos-staff-profile.html">Ms. Latshaw</a></h4>
																	  <span class='team-member-edit'><a href="hos-staff-edit.html"><i class='fa fa-pencil icon-xs'></i></a></span>
																	  <span>Dept. Head, Housekeeping<br>
																			<small>Age:</small> 39 yrs<br>
																			<small>Phone:</small> +1 233 454 4343<br>
																			<small>Email:</small> email@example.com</span>
																 </div>
															</div>
													</div>
													<div class="col-lg-6 col-sm-12 col-md-6 col-md-12">
															<div class="team-member col">
																 <div class="team-img col-lg-4 col-md-4 col-sm-4 col-xs-4">
																	  <img class="img-responsive" src="data/images/staff-3.jpg" alt="">
																 </div>
																 <div class="team-info col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
																	  <h4><a href="hos-staff-profile.html">Mrs. Clementina</a></h4>
																	  <span class='team-member-edit'><a href="hos-staff-edit.html"><i class='fa fa-pencil icon-xs'></i></a></span>
																	  <span>Manager, Housekeeping<br>
																			<small>Age:</small> 34 yrs<br>
																			<small>Phone:</small> +1 233 454 4343<br>
																			<small>Email:</small> email@example.com</span>
																 </div>
															</div>
													</div>
													<div class="col-lg-6 col-sm-12 col-md-6 col-md-12">
															<div class="team-member col">
																 <div class="team-img col-lg-4 col-md-4 col-sm-4 col-xs-4">
																	  <img class="img-responsive" src="data/images/staff-4.jpg" alt="">
																 </div>
																 <div class="team-info col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
																	  <h4><a href="hos-staff-profile.html">Mr. Carri Busey</a></h4>
																	  <span class='team-member-edit'><a href="hos-staff-edit.html"><i class='fa fa-pencil icon-xs'></i></a></span>
																	  <span>Manager, Laboratory<br>
																			<small>Age:</small> 29 yrs<br>
																			<small>Phone:</small> +1 233 454 4343<br>
																			<small>Email:</small> email@example.com</span>
																 </div>
															</div>
													</div>
													<div class="col-lg-6 col-sm-12 col-md-6 col-md-12">
															<div class="team-member col">
																 <div class="team-img col-lg-4 col-md-4 col-sm-4 col-xs-4">
																	  <img class="img-responsive" src="data/images/staff-5.jpg" alt="">
																 </div>
																 <div class="team-info col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
																	  <h4><a href="hos-staff-profile.html">Ms. Clay Dock</a></h4>
																	  <span class='team-member-edit'><a href="hos-staff-edit.html"><i class='fa fa-pencil icon-xs'></i></a></span>
																	  <span>Salesman, Pharmacy<br>
																			<small>Age:</small> 65 yrs<br>
																			<small>Phone:</small> +1 233 454 4343<br>
																			<small>Email:</small> email@example.com</span>
																 </div>
															</div>
													</div>
													<div class="col-lg-6 col-sm-12 col-md-6 col-md-12">
															<div class="team-member col">
																 <div class="team-img col-lg-4 col-md-4 col-sm-4 col-xs-4">
																	  <img class="img-responsive" src="data/images/staff-6.jpg" alt="">
																 </div>
																 <div class="team-info col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
																	  <h4><a href="hos-staff-profile.html">Mr. Mark Peskin</a></h4>
																	  <span class='team-member-edit'><a href="hos-staff-edit.html"><i class='fa fa-pencil icon-xs'></i></a></span>
																	  <span>Assistant, Laboratory<br>
																			<small>Age:</small> 57 yrs<br>
																			<small>Phone:</small> +1 233 454 4343<br>
																			<small>Email:</small> email@example.com</span>
																 </div>
															</div>
													</div>
													<div class="col-lg-6 col-sm-12 col-md-6 col-md-12">
															<div class="team-member col">
																 <div class="team-img col-lg-4 col-md-4 col-sm-4 col-xs-4">
																	  <img class="img-responsive" src="data/images/staff-7.jpg" alt="">
																 </div>
																 <div class="team-info col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
																	  <h4><a href="hos-staff-profile.html">Mr. Arthur John.</a></h4>
																	  <span class='team-member-edit'><a href="hos-staff-edit.html"><i class='fa fa-pencil icon-xs'></i></a></span>
																	  <span>Assistant, Pharmacy<br>
																			<small>Age:</small> 65 yrs<br>
																			<small>Phone:</small> +1 233 454 4343<br>
																			<small>Email:</small> email@example.com</span>
																 </div>
															</div>
													</div>
													<div class="col-lg-6 col-sm-12 col-md-6 col-md-12">
															<div class="team-member col">
																 <div class="team-img col-lg-4 col-md-4 col-sm-4 col-xs-4">
																	  <img class="img-responsive" src="data/images/staff-8.jpg" alt="">
																 </div>
																 <div class="team-info col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
																	  <h4><a href="hos-staff-profile.html">Mr. Carri Busey</a></h4>
																	  <span class='team-member-edit'><a href="hos-staff-edit.html"><i class='fa fa-pencil icon-xs'></i></a></span>
																	  <span>Assistant, Laboratory<br>
																			<small>Age:</small> 29 yrs<br>
																			<small>Phone:</small> +1 233 454 4343<br>
																			<small>Email:</small> email@example.com</span>
																 </div>
															</div>
													</div>
													<div class="col-lg-6 col-sm-12 col-md-6 col-md-12">
														<div class="team-member col">
																 <div class="team-img col-lg-4 col-md-4 col-sm-4 col-xs-4">
																	  <img class="img-responsive" src="data/images/staff-9.jpg" alt="">
																 </div>
																 <div class="team-info col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
																	  <h4><a href="hos-staff-profile.html">Ms. Clay Dock</a></h4>
																	  <span class='team-member-edit'><a href="hos-staff-edit.html"><i class='fa fa-pencil icon-xs'></i></a></span>
																	  <span>Front Desk, Administration<br>
																			<small>Age:</small> 65 yrs<br>
																			<small>Phone:</small> +1 233 454 4343<br>
																			<small>Email:</small> email@example.com</span>
																 </div>
														</div>
													</div>
													<div class="col-lg-6 col-sm-12 col-md-6 col-md-12">
															<div class="team-member col">
																 <div class="team-img col-lg-4 col-md-4 col-sm-4 col-xs-4">
																	  <img class="img-responsive" src="data/images/staff-10.jpg" alt="">
																 </div>
																 <div class="team-info col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
																	  <h4><a href="hos-staff-profile.html">Mr. Mark Peskin</a></h4>
																	  <span class='team-member-edit'><a href="hos-staff-edit.html"><i class='fa fa-pencil icon-xs'></i></a></span>
																	  <span>Assistant, Pharmacy<br>
																			<small>Age:</small> 57 yrs<br>
																			<small>Phone:</small> +1 233 454 4343<br>
																			<small>Email:</small> email@example.com</span>
																 </div>
															</div>
													</div>
													<div class="col-lg-6 col-sm-12 col-md-6 col-md-12">
															<div class="team-member col">
																 <div class="team-img col-lg-4 col-md-4 col-sm-4 col-xs-4">
																	  <img class="img-responsive" src="data/images/staff-11.jpg" alt="">
																 </div>
																 <div class="team-info col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
																	  <h4><a href="hos-staff-profile.html">Mr. Arthur John.</a></h4>
																	  <span class='team-member-edit'><a href="hos-staff-edit.html"><i class='fa fa-pencil icon-xs'></i></a></span>
																	  <span>Clerk, Administration<br>
																			<small>Age:</small> 65 yrs<br>
																			<small>Phone:</small> +1 233 454 4343<br>
																			<small>Email:</small> email@example.com</span>
																 </div>
															</div>
													</div>
													<div class="col-lg-6 col-sm-12 col-md-6 col-md-12">
															<div class="team-member col">
																 <div class="team-img col-lg-4 col-md-4 col-sm-4 col-xs-4">
																	  <img class="img-responsive" src="data/images/staff-12.jpg" alt="">
																 </div>
																 <div class="team-info col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
																	  <h4><a href="hos-staff-profile.html">Mrs. Jonh A.</a></h4>
																	  <span class='team-member-edit'><a href="hos-staff-edit.html"><i class='fa fa-pencil icon-xs'></i></a></span>
																	  <span>Assistant, Front Desk<br>
																			<small>Age:</small> 29 yrs<br>
																			<small>Phone:</small> +1 233 454 4343<br>
																			<small>Email:</small> email@example.com</span>
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
		<?php	require_once("js_framework.php");			?>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 

        <div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Section Settings</h4>
                    </div>
                    <div class="modal-body">

                        <?php echo S_MSG('MS51','Modul cetak data masih dalam tahap pengerjaan')?>

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
<!--                        <button class="btn btn-success" type="button">Save changes</button>		-->
                    </div>
                </div>
            </div>
        </div>
         
    </body>
</html>



