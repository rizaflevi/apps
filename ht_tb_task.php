<?php
//	ht_tb_task.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('H663','Tabel Task');

	$qQUERY=SYS_QUERY("select * from htk_task where APP_CODE='$cFILTER_CODE' and DELETOR=''");

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

	$cKODE_TBL 		= S_MSG('H661','Kode Task');
	$cNAMA_TBL 		= S_MSG('H662','Nama Task');
	$cKETERANGAN 	= S_MSG('H667','Keterangan');
	$cADD_REC		= S_MSG('H669','Tambah Task');
	$cDAFTAR		= S_MSG('H666','Daftar Tabel Task');
	$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cEDIT_TBL		= S_MSG('H66A','Edit Tabel Task');
	$cMSG_EXIST		= S_MSG('H66B','Kode Task sudah ada');
	$cMSG_BLANK		= S_MSG('H66C','Kode Task belum diisi');
	$cSAVE			= S_MSG('F301','Save');
	$cCLOSE			= S_MSG('F302','Close');
	
	$cTTIP_KODE		= S_MSG('H664','Setiap Task di beri kode, supaya bisa diakses berdasarkan kode');
	$cTTIP_NAMA		= S_MSG('H665','Nama Pekerjaan');
	$cTTIP_DESC		= S_MSG('H668','Keterangan mengenai pekerjaan');
	
switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('H66G','Help Tabel Task Hotel');
		$cHELP_1	= S_MSG('H66H','Ini adalah modul untuk memasukkan data Task / pekerjaan yang ada harus dikerjakan oleh karyawan hotel');
		$cHELP_2	= S_MSG('H66I','Tabel ini digunakan oleh personel house keeping untuk memonitor pekerjaan yang harus dikerjakan');
		$cHELP_3	= S_MSG('H68J','Untuk memasukkan data Task baru, klik tambah Task / add new');

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
												<a href="?_a=<?php echo md5('cr34t3')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_prs_tb_task" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>">
												<thead>
													<tr>
														<th style="<?php echo S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray')?>;width: 1px;"></th>
														<th style="<?php echo S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray')?>;"><?php echo $cKODE_TBL?></th>
														<th style="<?php echo S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray')?>;"><?php echo $cNAMA_TBL?></th>
														<th style="<?php echo S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray')?>;"><?php echo $cKETERANGAN?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($r_HTK_TASK=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td style="width: 1px;"></td>';
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_t=".md5($r_HTK_TASK['TASK_CODE'])."'>".decode_string($r_HTK_TASK['TASK_CODE'])."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_t=".md5($r_HTK_TASK['TASK_CODE'])."'>".decode_string($r_HTK_TASK['TASK_NAME'])."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_t=".md5($r_HTK_TASK['TASK_CODE'])."'>".decode_string($r_HTK_TASK['TASK_DESC'])."</a></span></td>";
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
			<div class="modal" id="help_prs_tb_task" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
				<div class="project-info"></div>
			</div>

			<section id="main-content" class=" ">
				<section class="wrapper main-wrapper" style=''>

					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="page-title">
							<div class="pull-left">
								<h1 class="title"><?php echo $cADD_REC?></h1>                            
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
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
											<input type="text" class="col-sm-2 form-label-900" name='ADD_TASK_CODE' title="<?php echo $cTTIP_KODE?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_TASK_NAME' title="<?php echo $cTTIP_NAMA?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_TASK_DESC' title="<?php echo $cTTIP_DESC?>">
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
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

<?php
	break;

case md5('up_d4t3'):
		$cQUERY ="select * from htk_task";
		$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(TASK_CODE)='$_GET[_t]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$REC_HTK_AREA=SYS_FETCH($qQUERY);
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
									  <h2 class="title"><?php echo $cEDIT_TBL?></h2>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<?php echo '<a href="?_a='.md5('del_task').'&id='. md5($REC_HTK_AREA['TASK_CODE']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs"></div>
								<div class="content-body">
									<div class="row">
										<form action ="?_a=rubah&id=<?php echo $REC_HTK_AREA['TASK_CODE']?>" method="post">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_TASK_CODE' id="field-1" value=<?php echo $REC_HTK_AREA['TASK_CODE']?> disabled="disabled" title="<?php echo $cTTIP_KODE?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_TASK_NAME' id="field-2" value="<?php echo $REC_HTK_AREA['TASK_NAME']?>" title="<?php echo $cTTIP_NAMA?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_TASK_DESC' id="field-2" value="<?php echo $REC_HTK_AREA['TASK_DESC']?>" title="<?php echo $cTTIP_DESC?>">
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
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>
<?php
	break;

case 'tambah':
	$NOW = date("Y-m-d H:i:s");
	if($_POST['ADD_TASK_CODE']==''){
		$cMSG = $cMSG_BLANK;	echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from htk_task where APP_CODE='$cFILTER_CODE' and DELETOR='' and TASK_CODE='$_POST[ADD_TASK_CODE]'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG = $cMSG_EXIST;
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:ht_tb_task.php');
	} else {
		$cTASK_NAME	= encode_string($_POST['ADD_TASK_NAME']);	
		$cTASK_DESC	= encode_string($_POST['ADD_TASK_DESC']);	
		$cQUERY="insert into htk_task set TASK_CODE='$_POST[ADD_TASK_CODE]', TASK_NAME='$cTASK_NAME', 
			TASK_DESC='$cTASK_DESC', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:ht_tb_task.php');
	}
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cTASK_NAME	= encode_string($_POST['EDIT_TASK_NAME']);	
	$cTASK_DESC	= encode_string($_POST['EDIT_TASK_DESC']);	
	$cQUERY ="update htk_task set TASK_NAME='$cTASK_NAME', TASK_DESC='$cTASK_DESC', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and TASK_CODE='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:ht_tb_task.php');
	break;

case md5('del_task'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update htk_task set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(TASK_CODE)='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:ht_tb_task.php');
}
?>

