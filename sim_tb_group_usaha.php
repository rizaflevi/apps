<?php
//	sim_tb_group_usaha.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('SM25','Tabel Grup Usaha');

	$qQUERY=SYS_QUERY("select * from grup_ush where APP_CODE='$cFILTER_CODE' and DELETOR=''");

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

	$cKODE_TBL 		= S_MSG('SM21','Kode Grup');
	$cNAMA_TBL 		= S_MSG('SM22','Nama Grup Usaha');
	$cKETERANGAN 	= S_MSG('SM23','Keterangan');
	$cADD_REC		= S_MSG('SM2A','Tambah Grup Usaha');
	$cDAFTAR		= S_MSG('SM2C','Daftar Grup Usaha');
	$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cUPD_TBL		= S_MSG('SM2B','Edit Tabel Grup Usaha');
	
	$cTTIP_KODE		= S_MSG('SM2J','Setiap Grup Usaha di beri kode, untuk keperluan pengelompokan dan akses data');
	$cTTIP_NAMA		= S_MSG('SM2K','Nama Grup Usaha');
	$cTTIP_NOTE		= S_MSG('SM2L','Keterangan tambahan mengenai Grup Usaha');
	
	$cSAVE			= S_MSG('F301','Save');
	$cCLOSE			= S_MSG('F302','Close');
	
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	
switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('SN6O','Help Tabel Tabel Sumber Pendapatan');
		$cHELP_1	= S_MSG('SN6P','Ini adalah modul untuk memasukkan data jenis Sumber Pendapatan yang terdapat di daerah ini');
		$cHELP_2	= S_MSG('SN6Q','Tabel ini digunakan untuk pengelompokan jenis sumber pendapatan apa saja yang ada');
		$cHELP_3	= S_MSG('SN6R','Untuk memasukkan data Sumber Pendapatan baru, klik tambah Sumber Pendapatan / add new');

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
												<a href="#help_sim_tb_sumber_pendapatan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKETERANGAN?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($r_SMB_PND=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td style="width: 1px;"></td>';
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_t=".md5($r_SMB_PND['GRUP_CODE'])."'>".$r_SMB_PND['GRUP_CODE']."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_t=".md5($r_SMB_PND['GRUP_CODE'])."'>".$r_SMB_PND['GRUP_NAME']."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_t=".md5($r_SMB_PND['GRUP_CODE'])."'>".$r_SMB_PND['GRUP_KETR']."</a></span></td>";
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
			<div class="modal" id="help_sim_tb_sumber_pendapatan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
		<body class=" ">
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
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='ADD_GRUP_CODE' title="<?php echo $cTTIP_KODE?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_GRUP_NAME' title="<?php echo $cTTIP_NAMA?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_GRUP_KETR' title="<?php echo $cTTIP_NOTE?>">
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
	SYS_DB_CLOSE($DB2);	break;

case md5('up_d4t3'):
	$cQUERY ="select * from grup_ush where APP_CODE='$cFILTER_CODE' and md5(GRUP_CODE)='$_GET[_t]' and DELETOR=''";
	$qQUERY =SYS_QUERY($cQUERY);
	$a_SMB_PND=SYS_FETCH($qQUERY);
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
		<body>
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
									  <h2 class="title"><?php echo $cUPD_TBL?></h2>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<?php echo '<a href="?_a='.md5('del_record').'&id='. md5($a_SMB_PND['GRUP_CODE']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
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
										<form action ="?_a=rubah&id=<?php echo $a_SMB_PND['GRUP_CODE']?>" method="post">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='UPD_GRUP_CODE' title="<?php echo $cTTIP_KODE?>" value="<?php echo $a_SMB_PND['GRUP_CODE']?>" disabled="disabled">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-6 form-label-900" name='UPD_GRUP_NAME' title="<?php echo $cTTIP_NAMA?>" value="<?php echo $a_SMB_PND['GRUP_NAME']?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='UPD_GRUP_KETR' title="<?php echo $cTTIP_NOTE?>" value="<?php echo $a_SMB_PND['GRUP_KETR']?>">
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
	SYS_DB_CLOSE($DB2);	break;

case 'tambah':
	$NOW 	= date("Y-m-d H:i:s");
	$cCODE	= encode_string($_POST['ADD_GRUP_CODE']);
	if($cCODE==''){
		$cMSG_BLANK		= S_MSG('SM2E','Kode Grup Usaha belum diisi');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from grup_ush where APP_CODE='$cFILTER_CODE' and DELETOR='' and GRUP_CODE='$cCODE'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG_EXIST		= S_MSG('SM2D','Kode Grup Usaha sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
		header('location:sim_tb_group_usaha.php');
	} else {
		$cNAME	= encode_string($_POST['ADD_GRUP_NAME']);
		$cNOTE	= encode_string ($_POST['ADD_GRUP_KETR']);
		$cQUERY	= "insert into grup_ush set GRUP_CODE='$cCODE', GRUP_NAME='$cNAME', GRUP_KETR='$cNOTE', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:sim_tb_group_usaha.php');
	}
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cNAME	= encode_string($_POST['UPD_GRUP_NAME']);
	$cNOTE	= encode_string ($_POST['UPD_GRUP_KETR']);
	$cQUERY ="update grup_ush set GRUP_NAME='$cNAME', GRUP_KETR='$cNOTE', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and GRUP_CODE='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:sim_tb_group_usaha.php');
	break;

case md5('del_record'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update grup_ush set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(GRUP_CODE)='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:sim_tb_group_usaha.php');
}
?>

