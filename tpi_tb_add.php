<?php
//	tpi_tb_add.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('TE61','Tabel Retribusi Bakul');

	$qQUERY=SYS_QUERY("select * from tb_bidd_add where APP_CODE='$cFILTER_CODE' and DELETOR=''");

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

	$cKODE_TBL 	= S_MSG('TE62','Kode Retribusi');
	$cNAMA_TBL 	= S_MSG('TE63','Nama Retribusi');
	$cPOTONGAN 	= S_MSG('TE64','Jumlah (%)');
	$cADD_REC	= S_MSG('TE66','Tambah');
	$cDAFTAR	= S_MSG('TE69','Daftar Retribusi');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cEDIT_TBL	= S_MSG('TE70','Edit Tabel Retribusi');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$cTTIP_KODE	= S_MSG('TE81','Setiap Jenis Retribusi diberi kode untuk keperluan akses dan pengurutan');
	$cTTIP_NAMA	= S_MSG('TE82','Nama Retribusi sebagai keterangan');
	$cTTIP_PRSN	= S_MSG('TE83','Besar Retribusi dalam satuan persen');
	
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('TE91','Help Tabel Retribusi Bakul');
		$cHELP_1	= S_MSG('TE92','Ini adalah modul untuk memasukkan data Jenis Retribusi Bakul hasil lelang.');
		$cHELP_2	= S_MSG('TE93','Tabel ini diperlukan untuk mencatat retribusi yang dikenakan kepada bakul sebagai kompensasi untuk bisa melakukan lelang.');
		$cHELP_3	= S_MSG('TE94','Contoh potongan : retribusi daerah, simpanan sukarela yang berlaku di masing-masing TPI / koperasi');
		$cHELP_4	= S_MSG('TE95','Untuk memasukkan data Retribusi baru, klik tambah Retribusi / add new');

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
												<a href="#help_tb_bidd_add" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cPOTONGAN?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_tb_bidd_add=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td style="width: 1px;"></td>';
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_tb_bidd_add['KODE_ADD'])."'>".decode_string($aREC_tb_bidd_add['KODE_ADD'])."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_tb_bidd_add['KODE_ADD'])."'>".decode_string($aREC_tb_bidd_add['NAMA_ADD'])."</a></span></td>";
															echo '<td align="right">'.number_format($aREC_tb_bidd_add['PERSEN'], 2).'</a></span></td>';
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

			<div class="modal" id="help_tb_bidd_add" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_3?></p>	<p><?php echo $cHELP_4?></p>
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
											<input type="text" class="col-sm-3 form-label-900" name='ADD_KODE_ADD' title="<?php echo $cTTIP_KODE?>" autofocus>
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
											<input type="text" class="col-sm-8 form-label-700" name='ADD_NAMA_ADD' title="<?php echo $cTTIP_NAMA?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cPOTONGAN?></label>
											<input type="text" class="col-sm-3 form-label-900" name='ADD_PERSEN' title="<?php echo $cTTIP_PRSN?>" data-mask="fdecimal">
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
		$cQUERY ="select * from tb_bidd_add where APP_CODE='$cFILTER_CODE' and md5(KODE_ADD)='$_GET[_r]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$REC_tb_bidd_add=SYS_FETCH($qQUERY);
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
											<?php echo '<a href="?_a='.md5('del_area').'&id='. md5($REC_tb_bidd_add['KODE_ADD']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
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
										<form action ="?_a=rubah&id=<?php echo $REC_tb_bidd_add['KODE_ADD']?>" method="post">
											<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_KODE_ADD' value=<?php echo $REC_tb_bidd_add['KODE_ADD']?> disabled="disabled" title="<?php echo $cTTIP_KODE?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-8 form-label-900" name='EDIT_NAMA_ADD' value="<?php echo decode_string($REC_tb_bidd_add['NAMA_ADD'])?>" title="<?php echo $cTTIP_NAMA?>" autofocus>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cPOTONGAN?></label>
												<input type="text" class="col-sm-3 form-label-900" name='EDIT_PERSEN' title="<?php echo $cTTIP_PRSN?>" data-mask="fdecimal" value="<?php echo $REC_tb_bidd_add['PERSEN']?>">
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
	$NOW = date("Y-m-d H:i:s");
	$cKODE_ADD	= encode_string($_POST['ADD_KODE_ADD']);	
	if($cKODE_ADD==''){
		$cMSG_BLANK	= S_MSG('TE71','Kode Retribusi belum diisi');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from tb_bidd_add where APP_CODE='$cFILTER_CODE' and DELETOR='' and KODE_ADD='$cKODE_ADD'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG_EXIST	= S_MSG('TE72','Kode Retribusi sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
		header('location:tpi_tb_add.php');
	} else {
		$cNAMA_ADD	= encode_string($_POST['ADD_NAMA_ADD']);
		$cQUERY="insert into tb_bidd_add set KODE_ADD='$cKODE_ADD', NAMA_ADD='$cNAMA_ADD', PERSEN=0".str_replace(',', '', $_POST['ADD_PERSEN']);
		$cQUERY.=", ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:tpi_tb_add.php');
	}
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cNAMA_ADD	= encode_string($_POST['EDIT_NAMA_ADD']);
	$cQUERY ="update tb_bidd_add set NAMA_ADD='$cNAMA_ADD',";
	$cQUERY.=" PERSEN=0".str_replace(',', '', $_POST['EDIT_PERSEN']).", UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and KODE_ADD='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:tpi_tb_add.php');
	break;

case md5('del_area'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update tb_bidd_add set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(KODE_ADD)='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:tpi_tb_add.php');
}
?>

