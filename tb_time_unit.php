<?php
//	ht_tb_area.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('H674','Tabel Area');

	$qTB_AREA=OpenTable('PrsTbUnit', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

	$cKODE_TBL 	= S_MSG('H496','Kode Unit');
	$cNAMA_TBL 	= S_MSG('H497','Nama Unit');
	$cKETERANGAN = S_MSG('H673','Keterangan');
	$cADD_REC	= S_MSG('H49A','Tambah Unit');
	$cDAFTAR	= S_MSG('H49B','Daftar Tabel Unit');
	$cEDIT_TBL	= S_MSG('H49C','Edit Tabel Unit');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$cHDR_BACK_CLR = S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX		= S_MSG('H49G','Help Tabel Unit');
		$cHELP_1		= S_MSG('H49H','Ini adalah modul untuk memasukkan data satuan unit waktu.');
		$cHELP_2		= S_MSG('H49I','Tabel ini diperlukan untuk menyatakan besar nya satuan waktu yang digunakan.');
		$cHELP_3		= S_MSG('H49J','Untuk memasukkan data Unit waktu baru, klik tambah Unit / add new');

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
												<a href="?_a=create"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_prs_tb_area" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
												<thead><tr>	<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
													<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_TBL?></th>
													<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_TBL?></th>
													<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKETERANGAN?></th>	
												</tr></thead>

												<tbody>
													<?php
														while($aREC_HTK_AREA=SYS_FETCH($qTB_AREA)) {
														echo '<tr>';
															echo '<td style="width: 1px;"></td>';
															echo "<td><span><a href='?_a=update&KODE_AREA=$aREC_HTK_AREA[AREA_CODE]'>".$aREC_HTK_AREA['AREA_CODE']."</a></span></td>";
															echo "<td><span><a href='?_a=update&KODE_AREA=$aREC_HTK_AREA[AREA_CODE]'>".$aREC_HTK_AREA['AREA_NAME']."</a></span></td>";
															echo "<td><span><a href='?_a=update&KODE_AREA=$aREC_HTK_AREA[AREA_CODE]'>".$aREC_HTK_AREA['AREA_DESC']."</a></span></td>";
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
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
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
	break;

case "create":
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
								<h2 class="title"><?php echo $cADD_REC?></h2>                            
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
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
											<input type="text" class="col-sm-2 form-label-900" name='ADD_AREA_CODE' id="field-1">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_AREA_NAME' id="field-2">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_AREA_DESC' id="field-2">
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
			<!-- END CONTENT -->
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
	break;

case "update":
		$qTB_AREA=OpenTable('PrsTbUnit', "AREA_CODE='$_GET[KODE_AREA]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$REC_HTK_AREA=SYS_FETCH($qTB_AREA);
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
									  <h1 class="title"><?php echo $cEDIT_TBL?></h1>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<?php echo '<a href="?_a=delete&_id='. $REC_HTK_AREA['REC_ID']. '" onClick="return confirm('. "'". S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?'). "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
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
										<form action ="?_a=up_date&_id=<?php echo $REC_HTK_AREA['REC_ID']?>" method="post">
											<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_AREA_CODE' id="field-1" value=<?php echo $REC_HTK_AREA['AREA_CODE']?> disabled="disabled">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_AREA_NAME' id="field-2" value="<?php echo $REC_HTK_AREA['AREA_NAME']?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_AREA_DESC' id="field-2" value="<?php echo $REC_HTK_AREA['AREA_DESC']?>">
												<div class="clearfix"></div>

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
			<?php require_once("js_framework.php");	?>
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 
		</body>
	</html>
<?php
	break;

case 'tambah':
	$cAREA_CODE = encode_string($_POST['ADD_AREA_CODE']);
	if($cAREA_CODE==''){
		$cMSG_BLANK=S_MSG('H684','Kode Area belum diisi');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$qTB_AREA=OpenTable('PrsTbUnit', "AREA_CODE='$cAREA_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qTB_AREA)>0){
		$cMSG_EXIST=S_MSG('H683','Kode Area sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
		header('location:ht_tb_area.php');
	} else {
		$cAREA_NAME = encode_string($_POST['ADD_AREA_NAME']);
		$cAREA_DESC = encode_string($_POST['ADD_AREA_DESC']);
		$nRec_id = date_create()->format('Uv');
		$cRec_id = (string)$nRec_id;
		RecCreate('PrsTbUnit', ['AREA_CODE', 'AREA_NAME', 'AREA_DESC', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cAREA_CODE, $cAREA_NAME, $cAREA_DESC, $_SESSION['gUSERCODE'], $cAPP_CODE, $cRec_id]);
		header('location:ht_tb_area.php');
	}
	break;

case 'up_date':
	$KODE_CRUD=$_GET['_id'];
	$cAREA_NAME = encode_string($_POST['EDIT_AREA_NAME']);
	$cAREA_DESC = encode_string($_POST['EDIT_AREA_DESC']);
	RecUpdate('PrsTbUnit', ['AREA_NAME', 'AREA_DESC'], [$cAREA_NAME, $cAREA_DESC], "REC_ID='$KODE_CRUD'");
	header('location:ht_tb_area.php');
	break;

case 'delete':
	$KODE_CRUD=$_GET['_id'];
	RecSoftDel($KODE_CRUD);
	header('location:ht_tb_area.php');
}
?>

