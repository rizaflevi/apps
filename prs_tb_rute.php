<?php
//	prs_tb_rute.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 	= $_SESSION['gUSERCODE'];
	$cHEADER = S_MSG('PN60','Tabel Rute Baca Meter');

	$can_CREATE = TRUST($cUSERCODE, 'TB_RUTE_1ADD');
	$can_UPDATE = TRUST($cUSERCODE, 'TB_RUTE_2UPD');
	$can_DELETE = TRUST($cUSERCODE, 'TB_RUTE_3DEL');
	
	$qQUERY=OpenTable('TbRbm', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

	$cKODE_TBL 	= S_MSG('PN61','Kode Rute');
	$cNAMA_TBL 	= S_MSG('PN62','Nama Rute');
	$cKETERANGAN = S_MSG('PN63','Keterangan');
	$cADD_REC	= S_MSG('PN71','Tambah Rute');
	$cDAFTAR	= S_MSG('PN70','Daftar Tabel Rute');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cEDIT_TBL	= S_MSG('PN72','Edit Tabel Rute');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$cTTIP_KODE	= S_MSG('PN81','Setiap Rute baca meter diberi kode untuk keperluan akses data.');
	$cTTIP_NAMA	= S_MSG('PN82','Nama Rute perjalanan');
	
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	$cTBL_LIST_DISPLAY_COLOR = S_PARA('_DISP_TABLE_LIST_FCOLOR','black');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('PN91','Help Tabel Rute Perjalanan');
		$cHELP_1	= S_MSG('PN92','Ini adalah modul untuk memasukkan data rute perjalanan kunjungan baca meter pelanggan.');
		$cHELP_2	= S_MSG('PN93','Tabel ini diperlukan untuk pembagian rute tugas personel yang mengunjungi pelanggan untuk pembacaan meter.');
		$cHELP_3	= S_MSG('PN94','Untuk memasukkan data Rute baru, klik tambah Rute / add new');

?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	?>
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
												<a href="#help_prs_tb_area" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKETERANGAN?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_TB_AREA=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td style="width: 1px;"></td>';
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_AREA['KODE_RUTE'])."' style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($aREC_TB_AREA['KODE_RUTE'])."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_AREA['KODE_RUTE'])."' style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($aREC_TB_AREA['NAMA_RUTE'])."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_AREA['KODE_RUTE'])."' style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($aREC_TB_AREA['KET_RUTE'])."</a></span></td>";
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

			<div class="modal" id="help_prs_tb_area" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_3?></p>
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

					<div class='col-lg-9 col-md-9 col-sm-9 col-xs-9'>
						<div class="page-title">

							<div class="pull-left">
								<h2 class="title"><?php echo $cADD_REC?></h2>                            
							</div>
							<div class="pull-right hidden-xs"></div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
						<section class="box ">
							<div class="content-body">
								<div class="row">
									<form action ="?_a=tambah" method="post">
										<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
											<input type="text" class="col-sm-3 form-label-900" name='ADD_KODE_RUTE' title="<?php echo $cTTIP_KODE?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
											<input type="text" class="col-sm-8 form-label-700" name='ADD_NAMA_RUTE' title="<?php echo $cTTIP_NAMA?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
											<input type="text" class="col-sm-8 form-label-700" name='ADD_KET_RUTE' id="field-2">
											<div class="clearfix"></div><br>

											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE?>>
												<input type="button" class="btn" value=<?php echo $cCLOSE?> onclick=self.history.back()>
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

case md5('up_d4t3'):
		$cQUERY ="select * from bm_tb_rute where APP_CODE='$cAPP_CODE' and md5(KODE_RUTE)='$_GET[_r]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$REC_TB_AREA=SYS_FETCH($qQUERY);
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

						<div class='col-lg-9 col-md-9 col-sm-9 col-xs-9'>
							<div class="page-title">

								<div class="pull-left">
									  <h2 class="title"><?php echo $cEDIT_TBL?></h2>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<?php echo '<a href="?_a='.md5('del_area').'&id='. md5($REC_TB_AREA['KODE_RUTE']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
							<section class="box ">
								<div class="pull-right hidden-xs"></div>
								<div class="content-body">
									<div class="row">
										<form action ="?_a=rubah&id=<?php echo $REC_TB_AREA['KODE_RUTE']?>" method="post">
											<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
												<input type="text" class="col-sm-3 form-label-900" name='EDIT_KODE_RUTE' value=<?php echo $REC_TB_AREA['KODE_RUTE']?> disabled="disabled" title="<?php echo $cTTIP_KODE?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-8 form-label-900" name='EDIT_NAMA_RUTE' value="<?php echo decode_string($REC_TB_AREA['NAMA_RUTE'])?>" title="<?php echo $cTTIP_NAMA?>" autofocus>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-8 form-label-900" name='EDIT_KET_RUTE' value="<?php echo decode_string($REC_TB_AREA['KET_RUTE'])?>">
												<div class="clearfix"></div><br>

												<div class="text-left">
													<?php 
														if($can_UPDATE == 1) {
															echo '<input type="submit" class="btn btn-primary" value=' . $cSAVE.'>';
														}
													?>
													<input type="button" class="btn" value=<?php echo $cCLOSE?> onclick=self.history.back()>
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
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>
<?php
	SYS_DB_CLOSE($DB2);	break;

case 'tambah':
	$NOW = date("Y-m-d H:i:s");
	$cKODE_RUTE	= encode_string($_POST['ADD_KODE_RUTE']);	
	if($cKODE_RUTE==''){
		$cMSG_BLANK	= S_MSG('PN76','Kode Rute belum diisi');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from bm_tb_rute where APP_CODE='$cAPP_CODE' and DELETOR='' and KODE_RUTE='$cKODE_RUTE'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG_EXIST	= S_MSG('PN77','Kode Rute sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
		header('location:prs_tb_rute.php');
	} else {
		$cNAMA_RUTE	= encode_string($_POST['ADD_NAMA_RUTE']);	
		$cDESC_AREA	= encode_string($_POST['ADD_KET_RUTE']);	
		$cQUERY="insert into bm_tb_rute set KODE_RUTE='$cKODE_RUTE', NAMA_RUTE='$cNAMA_RUTE', KET_RUTE='$cDESC_AREA', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:prs_tb_rute.php');
	}
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cNAMA_RUTE	= encode_string($_POST['EDIT_NAMA_RUTE']);	
	$cDESC_AREA	= encode_string($_POST['EDIT_KET_RUTE']);	
	$cQUERY ="update bm_tb_rute set NAMA_RUTE='$cNAMA_RUTE', KET_RUTE='$cDESC_AREA', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cAPP_CODE' and KODE_RUTE='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:prs_tb_rute.php');
	break;

case md5('del_area'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update bm_tb_rute set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cAPP_CODE' and md5(KODE_RUTE)='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:prs_tb_rute.php');
}
?>

