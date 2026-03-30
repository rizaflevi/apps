<?php
//	sim_tb_klpk_pendapatan.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('SN75','Tabel Kelompok Pendapatan');

	$qQUERY=SYS_QUERY("select * from klp_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

	$cKODE_TBL 		= S_MSG('SN71','Kode Kelompok');
	$cNAMA_TBL 		= S_MSG('SN72','Kelompok Pendapatan');
	$cSUMBER_P 		= S_MSG('SN62','Sumber Pendapatan');
	$cKETERANGAN 	= S_MSG('SM23','Keterangan');
	$cADD_REC		= S_MSG('SN7A','Tambah Kelompok Pendapatan');
	$cEDIT_TBL		= S_MSG('SN7B','Edit Tabel Kelompok Pendapatan');
	$cDAFTAR		= S_MSG('SN7C','Daftar Kelompok Pendapatan');
	$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	
	$cTTIP_KODE		= S_MSG('SN7J','Setiap jenis Kelompok Pendapatan di beri kode, untuk keperluan pengelompokan');
	$cTTIP_NAMA		= S_MSG('SN7K','Nama Kelompok Pendapatan');
	$cTTIP_NOTE		= S_MSG('SN6L','Keterangan tambahan mengenai Kelompok Pendapatan');
	
	$cSAVE			= S_MSG('F301','Save');
	$cCLOSE			= S_MSG('F302','Close');
	
	
switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('SN7P','Help Tabel Kelompok Pendapatan');
		$cHELP_1	= S_MSG('SN7Q','Ini adalah modul untuk memasukkan data Kelompok Pendapatan yang terdapat di daerah ini');
		$cHELP_2	= S_MSG('SN7R','Tabel ini digunakan untuk pengelompokan pendapatan apa saja yang ada');
		$cHELP_3	= S_MSG('SN7S','Untuk memasukkan data Kelompok Pendapatan baru, klik tambah Kelompok Pendapatan / add new');

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
												<a href="#help_sim_tb_klpk_pendapatan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_t=".md5($r_SMB_PND['KPND_CODE'])."'>".$r_SMB_PND['KPND_CODE']."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_t=".md5($r_SMB_PND['KPND_CODE'])."'>".$r_SMB_PND['KPND_DESC']."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_t=".md5($r_SMB_PND['KPND_CODE'])."'>".$r_SMB_PND['KPND_NOTE']."</a></span></td>";
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
				<?php include "scr_chat.php";	?>
			</div>
			<?php require_once("js_framework.php");	?>
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<div class="modal" id="help_sim_tb_klpk_pendapatan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
												<input type="text" class="col-sm-2 form-label-900" name='ADD_KPND_CODE' title="<?php echo $cTTIP_KODE?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_KPND_DESC' title="<?php echo $cTTIP_NAMA?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cSUMBER_P?></label>
												<select name="ADD_SUMBER" class="col-sm-4 form-label-900">
												<?php 
													$q_SMB_PND=SYS_QUERY("select * from smb_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													echo "<option value=' '  > </option>";
													while($r_SMB_PND=SYS_FETCH($q_SMB_PND)){
														echo "<option value='$r_SMB_PND[SPND_CODE]'  >$r_SMB_PND[SPND_DESC]</option>";
													}
												?>
												</select>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_KPND_NOTE' title="<?php echo $cTTIP_NOTE?>">
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
		$cQUERY ="select * from klp_pnd";
		$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(KPND_CODE)='$_GET[_t]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$a_KLP_PND=SYS_FETCH($qQUERY);
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
											<?php echo '<a href="?_a='.md5('del_smb_pnd').'&id='. md5($a_KLP_PND['KPND_CODE']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
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
										<form action ="?_a=rubah&id=<?php echo $a_KLP_PND['KPND_CODE']?>" method="post">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_KPND_CODE' title="<?php echo $cTTIP_KODE?>" value="<?php echo $a_KLP_PND['KPND_CODE']?>" disabled="disabled">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_KPND_DESC' title="<?php echo $cTTIP_NAMA?>" value="<?php echo $a_KLP_PND['KPND_DESC']?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cSUMBER_P?></label>
												<select name="UPD_SPND_CODE" class="col-sm-4 form-label-900">
												<?php 
													$q_SMB_PND=SYS_QUERY("select * from smb_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													echo "<option value=' '  > </option>";
													while($r_SMB_PND=SYS_FETCH($q_SMB_PND)){
														if($a_KLP_PND['SPND_CODE']==$r_SMB_PND['SPND_CODE']){
															echo "<option value='$r_SMB_PND[SPND_CODE]' selected='$a_KLP_PND[SPND_CODE]' >$r_SMB_PND[SPND_DESC]</option>";
														} else {
															echo "<option value='$r_SMB_PND[SPND_CODE]'  >$r_SMB_PND[SPND_DESC]</option>";
														}
													}
												?>
												</select>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_KPND_NOTE' title="<?php echo $cTTIP_NOTE?>" value="<?php echo $a_KLP_PND['KPND_NOTE']?>">
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
	$NOW 	= date("Y-m-d H:i:s");
	$cCODE	= encode_string($_POST['ADD_KPND_CODE']);
	if($cCODE==''){
		$cMSG_BLANK		= S_MSG('SN6E','Kode Pendapatan belum diisi');
		$cMSG = $cMSG_BLANK;	echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from klp_pnd where APP_CODE='$cFILTER_CODE' and DELETOR='' and KPND_CODE='$cCODE'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG_EXIST		= S_MSG('SN6D','Kode Pendapatan sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
		header('location:sim_tb_klpk_pendapatan.php');
	} else {
		$cNAME	= encode_string($_POST['ADD_KPND_DESC']);
		$cNOTE	= encode_string ($_POST['ADD_KPND_NOTE']);
		$cQUERY	= "insert into klp_pnd set KPND_CODE='$cCODE', KPND_DESC='$cNAME', KPND_NOTE='$cNOTE', SPND_CODE='$_POST[ADD_SUMBER]'
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:sim_tb_klpk_pendapatan.php');
	}
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cNAME	= encode_string($_POST['EDIT_KPND_DESC']);
	$cNOTE	= encode_string ($_POST['EDIT_KPND_NOTE']);
	$cQUERY ="update klp_pnd set KPND_DESC='$cNAME', KPND_NOTE='$cNOTE', SPND_CODE='$_POST[UPD_SPND_CODE]', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and KPND_CODE='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:sim_tb_klpk_pendapatan.php');
	break;

case md5('del_smb_pnd'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update klp_pnd set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(KPND_CODE)='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:sim_tb_klpk_pendapatan.php');
}
?>

