<?php
//	tb_identitas.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHEADER 		= S_MSG('H215','Tabel Identitas');
	$cKD_ID  		= S_MSG('H216','Kode Identitas');
	$cNM_ID  		= S_MSG('H217','Nama Identitas');
	$cKETRANGAN 	= S_MSG('H218','Keterangan');
	$cDAFTAR		= S_MSG('H225','Daftar Identitas');
	$cEDIT_TBL		= S_MSG('H228','Edit Tabel Id');

	$cSAVE_DATA		= S_MSG('F301','Save');
	$cCLOSE_DATA	= S_MSG('F302','Close');

	$cTTIP_KODE		= S_MSG('H220','Setiap Identitas harus di beri kode, supaya bisa diakses berdasarkan kode');
	$cTTIP_NAMA		= S_MSG('H221','Nama Identitas');
	$cTTIP_DESC		= S_MSG('H222','Keterangan Identitas');

	$qQUERY=OpenTable('TbIdentity');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('H22F','Help Tabel Identitas');
?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<body class=" ">
			<?php	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
						<?php	require_once("scr_menu.php");	?>
					</div>
					<div class="project-info"></div>
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
											<li>
												<a href="#help_tb_identitas" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
	<!--
									  <div class="actions panel_actions pull-right">
											<i class="box_toggle fa fa-chevron-down"></i>
											<i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
											<i class="box_close fa fa-times"></i>
									  </div>
	-->
								</header>
								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>">
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKD_ID?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNM_ID?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_IDENT=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td class=""><div class="star"><i class="fa fa-newspaper-o icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?_a=".md5('up_dat3')."&_i=".md5($aREC_IDENT['IDENT_CODE'])."'>".$aREC_IDENT['IDENT_CODE']."</a></span></td>";
															echo "<td><a href='?_a=".md5('up_dat3')."&_i=".md5($aREC_IDENT['IDENT_CODE'])."'>".decode_string($aREC_IDENT['IDENT_NAME'])."</a></td>";
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
			<?php	require_once("js_framework.php");			?>
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<div class="modal" id="help_tb_identitas" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
			<!-- modal end -->
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
					<?php	require_once("scr_menu.php");	?>
				</div>
				<div class="project-info"></div>
			</div>
			<section id="main-content" class=" ">
				<section class="wrapper main-wrapper" style=''>

					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="page-title">

							<div class="pull-left">
								<h1 class="title"><?php echo S_MSG('H227','Tambah Id')?></h1>                            
							</div>
							<div class="pull-right hidden-xs"></div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="content-body">
									<div class="row">
										<form action ="?_a=tambah" method="post">
											<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_ID?></label>
												<input type="text" class="col-sm-2 form-label-900" name='ADD_IDENT_CODE' id="field-1" title="<?php echo $cTTIP_KODE?>" autofocus>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNM_ID?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_IDENT_NAME' id="field-2" title="<?php echo $cTTIP_NAMA?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETRANGAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_IDENT_DESC' id="field-3" title="<?php echo $cTTIP_DESC?>">
												<div class="clearfix"></div><br>


												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
													<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
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
		<?php	require_once("js_framework.php");			?>
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case md5('up_dat3'):
	$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');
	$qQUERY=OpenTable('TbIdentity', "APP_CODE='$cAPP_CODE' and md5(IDENT_CODE)='$_GET[_i]' and REC_ID not in ( select DEL_ID from logs_delete )");
	$REC_HT_IDENT=SYS_FETCH($qQUERY);
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
		<body class=" ">
			<div class="page-container row-fluid">
				
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper">
						<?php	require_once("scr_menu.php");	?>
					</div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">

								<div class="pull-left">
									  <h1 class="title"><?php echo $cEDIT_TBL?></h1>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<?php echo '<a href="?_a='.md5('DEL_IDEN').'&_id='. $REC_HT_IDENT['REC_ID']. '" onClick="return confirm('. "'". $cMSG_DEL. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs">	</div>
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
										<form action ="?_a=rubah&id=<?php echo $REC_HT_IDENT['REC_ID']?>" method="post"  onSubmit="return CEK_HT_IDENT(this)">
											<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_ID?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_IDENT_CODE' id="field-1" value=<?php echo $REC_HT_IDENT['IDENT_CODE']?> disabled="disabled" title="<?php echo $cTTIP_KODE?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNM_ID?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_IDENT_NAME' id="field-2" value="<?php echo decode_string($REC_HT_IDENT['IDENT_NAME'])?>" title="<?php echo $cTTIP_NAMA?>" autofocus>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETRANGAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_IDENT_DESC' id="field-2" value="<?php echo decode_string($REC_HT_IDENT['IDENT_DESC'])?>" title="<?php echo $cTTIP_DESC?>">
												<div class="clearfix"></div><br>

												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
													<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
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
case 'tambah':
	$cCODE = $_POST['ADD_IDENT_CODE'];
	if($cCODE==''){
		$cMSG_BLANK		= S_MSG('H229','Kode Identitas belum diisi');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$qQUERY=OpenTable('TbIdentity', "APP_CODE='$cAPP_CODE' and IDENT_CODE='$cCODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	if(SYS_ROWS($qQUERY)>0){
		$cMSG_EXIST		= S_MSG('H230','Kode Identitas sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		header('location:tb_identitas.php');
		return;
	} else {
		$cIDENT_CODE = ENCODE($_POST['ADD_IDENT_CODE']);
		$cIDENT_NAME = ENCODE($_POST['ADD_IDENT_NAME']);
		$cIDENT_DESC = ENCODE($_POST['ADD_IDENT_DESC']);
		RecCreate('TbIdentity', ['REC_ID', 'IDENT_CODE', 'IDENT_NAME', 'IDENT_DESC', 'APP_CODE', 'ENTRY'],
			[NowMSecs(), $cIDENT_CODE, $cIDENT_NAME, $cIDENT_DESC, $cAPP_CODE, $cUSERCODE]);
	}
	header('location:tb_identitas.php');
	break;
case 'rubah':
	$KODE_CRUD=$_GET['id'];
	$cIDENT_NAME = ENCODE($_POST['EDIT_IDENT_NAME']);
	$cIDENT_DESC = ENCODE($_POST['EDIT_IDENT_DESC']);
	RecUpdate('TbIdentity', ['IDENT_NAME', 'IDENT_DESC'], [$cIDENT_NAME, $cIDENT_DESC], "REC_ID='$KODE_CRUD'");
	header('location:tb_identitas.php');
	break;
case md5('DEL_IDEN'):
	RecSoftDel($_GET['_id']);
	header('location:tb_identitas.php');
	break;
}
?>

