<?php
//	sim_sys_parameter.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cSPACE90 	= ".". str_repeat(' ', 88) . ".";
//	kadis
	$qSYS_RECORD=SYS_QUERY("select * from rainbow where KEY_FIELD='KADIS_PD' and APP_CODE='$cFILTER_CODE'");
	$cSYS_RECORD=SYS_FETCH($qSYS_RECORD);
	if(SYS_ROWS($qSYS_RECORD)==0){
		$cQUERY="insert into rainbow set KEY_FIELD='KADIS_PD', KEY_CONTEN='$cSPACE90', APP_CODE='$cFILTER_CODE'";
		SYS_QUERY($cQUERY);
	}
	$lNAMA_KADIS = S_MSG('SP41','Nama Kadis Pendapatan Daerah');
	$rNAMA_KADIS=SYS_FETCH(SYS_QUERY("select * from rainbow where KEY_FIELD='KADIS_PD' and APP_CODE='$cFILTER_CODE'"));
	$cNAMA_KADIS=$rNAMA_KADIS['KEY_CONTEN'];
	
	$qNIP_RECORD=SYS_QUERY("select * from rainbow where KEY_FIELD='KADIS_NIP_PD' and APP_CODE='$cFILTER_CODE'");
	$cSYS_RECORD=SYS_FETCH($qNIP_RECORD);
	if(SYS_ROWS($qNIP_RECORD)==0){
		$cQUERY="insert into rainbow set KEY_FIELD='KADIS_NIP_PD', KEY_CONTEN='$cSPACE90', APP_CODE='$cFILTER_CODE'";
		SYS_QUERY($cQUERY);
	}
	$lNIP_KADIS = S_MSG('SP42','NIP Kadis Pendapatan Daerah');
	$rNIP_KADIS=SYS_FETCH(SYS_QUERY("select * from rainbow where KEY_FIELD='KADIS_NIP_PD' and APP_CODE='$cFILTER_CODE'"));
	$cNIP_KADIS=$rNIP_KADIS['KEY_CONTEN'];

//	kabid
	$qSYS_RECORD=SYS_QUERY("select * from rainbow where KEY_FIELD='KABID_PD' and APP_CODE='$cFILTER_CODE'");
	$cSYS_RECORD=SYS_FETCH($qSYS_RECORD);
	if(SYS_ROWS($qSYS_RECORD)==0){
		$cQUERY="insert into rainbow set KEY_FIELD='KABID_PD', KEY_CONTEN='$cSPACE90', APP_CODE='$cFILTER_CODE'";
		SYS_QUERY($cQUERY);
	}
	$lNAMA_KABID = S_MSG('SP74','Nama Kabid Pendapatan Daerah');
	$rNAMA_KABID=SYS_FETCH(SYS_QUERY("select * from rainbow where KEY_FIELD='KABID_PD' and APP_CODE='$cFILTER_CODE'"));
	$cNAMA_KABID=$rNAMA_KABID['KEY_CONTEN'];
	
	$qNIP_RECORD=SYS_QUERY("select * from rainbow where KEY_FIELD='KABID_NIP_PD' and APP_CODE='$cFILTER_CODE'");
	$cSYS_RECORD=SYS_FETCH($qNIP_RECORD);
	if(SYS_ROWS($qNIP_RECORD)==0){
		$cQUERY="insert into rainbow set KEY_FIELD='KABID_NIP_PD', KEY_CONTEN='$cSPACE90', APP_CODE='$cFILTER_CODE'";
		SYS_QUERY($cQUERY);
	}
	$lNIP_KABID = S_MSG('SP48','NIP Kabid Pendapatan Daerah');
	$rNIP_KABID=SYS_FETCH(SYS_QUERY("select * from rainbow where KEY_FIELD='KABID_NIP_PD' and APP_CODE='$cFILTER_CODE'"));
	$cNIP_KABID=$rNIP_KABID['KEY_CONTEN'];

	
	$cSYS_RECORD=SYS_FETCH(SYS_QUERY("select * from rainbow where KEY_FIELD='PICT_ACCT'"));
	$cPICT_ACCT=$cSYS_RECORD['KEY_CONTEN'];
	$cSYS_RECORD=SYS_FETCH(SYS_QUERY("select * from rainbow where KEY_FIELD='PICT_OR'"));
	$cPICT_OR=$cSYS_RECORD['KEY_CONTEN'];
	$cSYS_RECORD=SYS_FETCH(SYS_QUERY("select * from rainbow where KEY_FIELD='PICT_BDV'"));
	$cPICT_BDV=$cSYS_RECORD['KEY_CONTEN'];
	$cSYS_RECORD=SYS_FETCH(SYS_QUERY("select * from rainbow where KEY_FIELD='PICT_LGN'"));

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('SH9A','Help System Parameter');

?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	  require_once("scr_topbar.php");	?>
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
								  <h2 class="title">System Parameters</h2>
							</div>
							<div class="pull-right hidden-xs">
								<ol class="breadcrumb">
									<li>
										<a href="#help_sys_para" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
									</li>
								</ol>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12">
						<section class="box ">
							<div class="content-body">    
								<div class="row">
									<form action ="?_a=SAVE" method="post">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="row">
												<div class="col-md-12">
		<!--												<h4>Primary</h4>		-->
													<ul class="nav nav-tabs primary">
														<li>
															<a href="#nama_nama" data-toggle="tab">
																<i class="fa fa-user"></i> <?php echo S_MSG('SP00','Nama-nama')?>
															</a>
														</li>
														<li class="active">
															  <a href="#home-1" data-toggle="tab">
																	<i class="fa fa-home"></i> Template
															  </a>
														</li>
													</ul>
													<div class="tab-content primary">
														<div class="tab-pane fade" id="nama_nama">
															<label class="col-md-4 form-label-700" for="field-3"><?php echo $lNAMA_KADIS?></label>
															<input type="text" class="col-md-5 form-label-900" name="NAMA_KADIS" value="<?php echo $cNAMA_KADIS?>">
															<div class="clearfix"></div>

															<label class="col-md-4 form-label-700" for="field-3"><?php echo $lNIP_KADIS?></label>
															<input type="text" class="col-md-3 form-label-900" name="NIP_KADIS" value="<?php echo $cNIP_KADIS?>">
															<div class="clearfix"></div>

															<label class="col-md-4 form-label-700" for="field-3"><?php echo $lNAMA_KABID?></label>
															<input type="text" class="col-md-5 form-label-900" name="NAMA_KABID" value="<?php echo $cNAMA_KABID?>">
															<div class="clearfix"></div>

															<label class="col-md-4 form-label-700" for="field-3"><?php echo $lNIP_KADIS?></label>
															<input type="text" class="col-md-3 form-label-900" name="NIP_KABID" value="<?php echo $cNIP_KABID?>">
															<div class="clearfix"></div>

														</div>
														<div class="tab-pane fade in active" id="home-1">
															<div>
																<label class="col-md-5 form-label-700" for="field-1"><?php echo S_MSG('SP23','Format Kode Account / Perkiraan')?></label>
																<input type="text" class="col-md-3 form-label-900" id="field-1" value=<?php echo $cPICT_ACCT?> >
																<div class="clearfix"></div>

																<div class="form-group">
																<label class="col-md-5 form-label-700" for="field-1"><?php echo S_MSG('SP24','Format Nomor Bukti Penerimaan Kas/Bank')?></label>
																<input type="text" class="col-md-3 form-label-900" id="field-1" value=<?php echo $cPICT_OR?> >
																<div class="clearfix"></div>

																<div class="form-group">
																<label class="col-md-5 form-label-700" for="field-1"><?php echo S_MSG('SP25','Format Nomor Bukti Pembayaran Kas/Bank')?></label>
																<input type="text" class="col-md-3 form-label-900" id="field-1" value=<?php echo $cPICT_BDV?> >
																<div class="clearfix"></div>
															</div>
														</div>
													</div>
												</div>
												<div class="clearfix"><br></div>	
											</div>
											<div class="clearfix"></div><br>
											<button type="submit" class="btn btn-info btn-lg">UPDATE</button>
											<div class="clearfix hidden-md hidden-lg"><br></div>
										</div>
									</form>
								</div>
							</div>
						</section>
					</div>

				</section>
			</section>
			<?php	include "scr_chat.php";	?>
		</div>
		<?php	require_once("js_framework.php");	?>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 

        <div class="modal" id="help_sys_para" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>
	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case "SAVE":
	$cNAMA_KADIS=encode_string($_POST['NAMA_KADIS']);
	$cQUERY =SYS_QUERY("update rainbow set KEY_CONTEN='$cNAMA_KADIS' where KEY_FIELD='KADIS_PD'");

	$cNIP_KADIS=encode_string($_POST['NIP_KADIS']);
	$cQUERY =SYS_QUERY("update rainbow set KEY_CONTEN='$cNIP_KADIS' where KEY_FIELD='KADIS_NIP_PD'");
	
	$cNAMA_KABID=encode_string($_POST['NAMA_KABID']);
	$cQUERY =SYS_QUERY("update rainbow set KEY_CONTEN='$cNAMA_KABID' where KEY_FIELD='KABID_PD'");

	$cNIP_KABID=encode_string($_POST['NIP_KABID']);
	$cQUERY =SYS_QUERY("update rainbow set KEY_CONTEN='$cNIP_KABID' where KEY_FIELD='KABID_NIP_PD'");
	
	header('location:sim_sys_parameter.php');
	break;

}
?>


