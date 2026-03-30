<?php
//	prs_tb_work_hours.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('PI00','Tabel Jam Kerja');

	$qQUERY=SYS_QUERY("select * from prs_dayl where APP_CODE='$cFILTER_CODE' and DELETOR=''");

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
	$cKODE_WORK = S_MSG('F003','Kode');
	$cNAMA_WORK = S_MSG('PI02','Keterangan');
	$cJAM_MASUK = S_MSG('PI03','Jam Masuk Kerja');
	$cJAM_KELUAR= S_MSG('PI04','Jam Pulang Kerja');
	$cADD_SHIFT	= S_MSG('PI10','Tambah Jam Kerja');
	$cEDIT_SHIFT= S_MSG('PI11','Edit Jam Kerja');
	$cDEL_MSG	= S_MSG('F021','Benar data ini mau di hapus ?');
	
	$cTTIP_KETR	= S_MSG('PI16','Keterangan jam kerja');
	$cTTIP_MSUK	= S_MSG('PI17','Jam masuk kerja');
	$cTTIP_PULG	= S_MSG('PI18','Jam pulang kerja');

switch($cACTION){
	default:
	$ADD_LOG	= APP_LOG_ADD();
	$cHELP_BOX	= S_MSG('PI1A','Help Tabel Jam Kerja');

?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<body class=" ">
			<?php	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
						<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
					</div>
					<div class="project-info">	</div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>
						<div class="clearfix"></div>

						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left"><?php echo $cHEADER?></h2>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>
												<a href="?_a=<?php echo md5('cr34t3')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="display table table-hover table-condensed" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th style="background-color:LightGray;width: 1px;"></th>
														<th style="background-color:LightGray;"><?php echo $cKODE_WORK?></th>
														<th style="background-color:LightGray;"><?php echo $cNAMA_WORK?></th>
													</tr>
												</thead>
												<tbody>
													<?php
														while($aREC_KODE_SHIFT=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td style="width: 1px;"></td>';
															echo "<td><span><a href='?_a=".md5('upd4t3')."&_s=$aREC_KODE_SHIFT[DAYL_CODE]'>".$aREC_KODE_SHIFT['DAYL_CODE']."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('upd4t3')."&_s=$aREC_KODE_SHIFT[DAYL_CODE]'>".$aREC_KODE_SHIFT['DESC_CRPTN']."</a></span></td>";
														echo '</tr>';
														}
													?>
												</tbody>
											</table>

										</div>
									</div>
								</div>
							</section>
						</div>

					</section>
				</section>
				<?php	include "scr_chat.php";	?>
			</div>
			<?php	require_once("js_framework.php");	?>
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
		</body>
	</html>
<?php
	SYS_DB_CLOSE($DB2);	break;

case md5('cr34t3'):
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
		<div class="page-container row-fluid">
			
			<div class="page-sidebar ">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper">
					<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
				</div>
			</div>

			<section id="main-content" class=" ">
				<section class="wrapper main-wrapper" style=''>

					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="page-title">

							<div class="pull-left">
								<h2 class="title"><?php echo $cADD_SHIFT?></h2>                            
							</div>
							<div class="pull-right hidden-xs"></div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<section class="box ">
							<div class="content-body">
								<div class="row">
									<form action ="?_a=create" method="post">
										<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_WORK?></label>
											<input type="text" class="col-sm-2 form-label-900" name='ADD_DAYL_CODE' id="field-1">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_WORK?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_DESC_CRPTN' title="<?php echo $cTTIP_KETR ?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cJAM_MASUK?></label>
											<input type="text" class="col-sm-3 form-label-900" name='ADD_JAM_MASUK' id="ADD_JAM_MASUK" title="<?php echo $cTTIP_MSUK ?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cJAM_KELUAR?></label>
											<input type="text" class="col-sm-3 form-label-900" name='ADD_JAM_KELUAR' id="ADD_JAM_KELUAR" title="<?php echo $cTTIP_PULG ?>">
											<div class="clearfix"></div><br>

											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo S_MSG('F301','Save')?>>
												<input type="button" class="btn" value=<?php echo S_MSG('F302','Close')?> onclick=self.history.back()>
											</div>
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
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case md5('upd4t3'):
		$cQUERY ="select * from prs_dayl";
		$cQUERY.=" where APP_CODE='$cFILTER_CODE' and DAYL_CODE='$_GET[_s]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$REC_PRS_DAYL=SYS_FETCH($qQUERY);
?>
		<!DOCTYPE html>
		<html class=" ">
			<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">
				
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper">
						<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
					</div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">

								<div class="pull-left">
									  <h2 class="title"><?php echo $cEDIT_SHIFT?></h2>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=<?php echo md5('del_work')?>&id=<?php echo $REC_PRS_DAYL['DAYL_CODE']?>" onClick="return confirm('<?php echo $cDEL_MSG?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs"></div>
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
										<form action ="?_a=rubah&id=<?php echo $REC_PRS_DAYL['DAYL_CODE']?>" method="post"  onSubmit="return CEK_TAB_BANK(this)">
											<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_WORK?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_DAYL_CODE' id="field-1" value=<?php echo $REC_PRS_DAYL['DAYL_CODE']?> disabled="disabled">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_WORK?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_DESC_CRPTN' value="<?php echo $REC_PRS_DAYL['DESC_CRPTN']?>" title="<?php echo $cTTIP_KETR ?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cJAM_MASUK?></label>
												<input type="text" class="col-sm-3 form-label-900" name='EDIT_JAM_MASUK' id="EDIT_JAM_MASUK" value="<?php echo $REC_PRS_DAYL['JAM_MASUK']?>" title="<?php echo $cTTIP_MSUK ?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cJAM_KELUAR?></label>
												<input type="text" class="col-sm-3 form-label-900" name='EDIT_JAM_KELUAR' id="EDIT_JAM_KELUAR" value="<?php echo $REC_PRS_DAYL['JAM_KELUAR']?>" title="<?php echo $cTTIP_PULG ?>">
												<div class="clearfix"></div><br>

												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo S_MSG('F301','Save')?>>
													<input type="button" class="btn" value=<?php echo S_MSG('F302','Cancel')?> onclick=self.history.back()>
												</div>
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
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 

			</body>
		</html>
<?php
	SYS_DB_CLOSE($DB2);	break;

case md5('del_work'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update prs_dayl set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and DAYL_CODE='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:prs_tb_work_hours.php');
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY="update prs_dayl set DESC_CRPTN='$_POST[EDIT_DESC_CRPTN]', 
		JAM_MASUK='$_POST[EDIT_JAM_MASUK]', JAM_KELUAR='$_POST[EDIT_JAM_KELUAR]', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and DAYL_CODE='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:prs_tb_work_hours.php');
	break;

case 'create':
	$NOW = date("Y-m-d H:i:s");
	$cDAYL_CODE = encode_string($_POST['ADD_DAYL_CODE']);
	$cDAYL_NAME = encode_string($_POST['ADD_DESC_CRPTN']);
	if($cDAYL_CODE==''){
		$cMSG_BLANK=S_MSG('PI08','Kode jam kerja masih kosong');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		header('location:prs_tb_work_hours.php');
		return;
	}
	$cQUERY="select * from prs_dayl where DAYL_CODE='$cDAYL_CODE' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
	} else {
		$cQUERY="insert into prs_dayl set DAYL_CODE='$cDAYL_CODE', DESC_CRPTN='$cDAYL_NAME', 
			JAM_MASUK='$_POST[ADD_JAM_MASUK]', JAM_KELUAR='$_POST[ADD_JAM_KELUAR]', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
	}
	header('location:prs_tb_work_hours.php');
}
?>

<script>
$("#ADD_JAM_MASUK").inputmask("99:99");
$("#ADD_JAM_KELUAR").inputmask("99:99");
$("#EDIT_JAM_MASUK").inputmask("99:99");
$("#EDIT_JAM_KELUAR").inputmask("99:99");

</script>
