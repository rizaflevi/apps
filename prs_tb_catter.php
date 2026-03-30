<?php
//	prs_tb_catter.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('PN01','Tabel Pencatat Meter');

	$c_FILTER_RAYON = '';
	$q=OpenTable('TbUser', "USER_CODE='$$_SESSION[gUSERCODE]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	$a_USER_RAYON =SYS_FETCH($q);
	$c_FILTER_RAYON = " and KODE_RUTE='$a_USER_RAYON[USER_UNIT]'";
	$c_RAYON = $a_USER_RAYON['USER_UNIT'];
	
	if ($c_RAYON=='') {
		$q_TB_AREA=OpenTable('TbArea', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$aREC_TB_AREA=SYS_FETCH($q_TB_AREA);
		
		$cFILTER_AREA=$aREC_TB_AREA['KODE_AREA'];
		if (isset($_GET['_r'])) $cFILTER_AREA=$_GET['_r'];
	} else {
		$cFILTER_AREA=$c_RAYON;
	}

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

	$cKODE_TBL 	= S_MSG('PN02','Kode Pencatat');
	$cNAMA_TBL 	= S_MSG('PN03','Nama Pencatat');
	$cNOMOR_HP 	= S_MSG('PN04','Nomor HP');
	$cTARGET 	= S_MSG('PN06','Target Baca');
	$cOS_HP 	= S_MSG('PN11','O/S HP');
	$cTIPE_HP 	= S_MSG('PN12','Tipe HP');
	$cKD_USER	= S_MSG('TU01','Kode User');
	$cAREA 		= S_MSG('PQ05','Area');
	$cJML_PLG 	= S_MSG('PN13','Pelanggan');
	$cKETERANGAN = S_MSG('PN10','Keterangan');
	$cDAFTAR	= S_MSG('PN30','Daftar Tabel Pencatat');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$cTTIP_KODE	= S_MSG('PN21','Setiap Pencatat Meter diberi kode keperluan akses data');
	$cTTIP_NAMA	= S_MSG('PN22','Nama Pencatat Meter');
	$cTTIP_NOHP	= S_MSG('PN23','Nomor HP Pencatat Meter yang digunakan untuk memasukkan data.');
	$cTTIP_TRGT	= S_MSG('PN26','Target jumlah pencatatan setiap periode.');
	$cTTIP_USER	= S_MSG('PN28','Kode user seperti yang terdapat pada tabel user.');
	$cTTIP_KET	= S_MSG('PN27','Keterangan tambahan mengenai pencatat meter.');

	$cHDR_BACK_CLR = S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
		$cHELP_BOX	= S_MSG('PN51','Help Tabel Pencatat Meter');
		$cHELP_1	= S_MSG('PN52','Ini adalah modul untuk memasukkan data Pencatat Meter / Bill Man yang bertugas dilapangan.');
		$cHELP_2	= S_MSG('PN53','Tabel ini diperlukan untuk menyimpan data mengenai petugas pencatat, seperti nama, rute perjalanan, dll');
		$cHELP_3	= S_MSG('PN54','Untuk memasukkan data Pencatat Meter baru, klik tambah Pencatat Meter / add new');
		$cHELP_4	= S_MSG('PN55','Untuk merubah data Pencatat Meter, klik pada kode atau nama Pencatat Meter, akan di tampilkan form update');

		$c_CATTER1="select * from bm_tb_catter1 
			LEFT JOIN tb_area ON bm_tb_catter1.KODE_AREA=tb_area.KODE_AREA
			where bm_tb_catter1.KODE_AREA='$cFILTER_AREA' and bm_tb_catter1.APP_CODE='$cAPP_CODE' and bm_tb_catter1.DELETOR=''";
		$q_CATTER1=SYS_QUERY($c_CATTER1);

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
												<a href="#help_prs_bm_tb_catter1" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>

								<?php 
									if ($c_RAYON=='') {
										echo '<label class="col-sm-1 form-label-700" for="field-4">'. $cAREA.'</label>';
										echo '<select name="PILIH_AREA" class="col-sm-3 form-label-900"  onchange="FILTER_DATA(this.value)">';

										$q_TB_AREA =SYS_QUERY("select * from tb_area where APP_CODE='$cAPP_CODE' and DELETOR=''");
	//									echo "<option value=''  > All</option>";
										while($aREC_TB_AREA=SYS_FETCH($q_TB_AREA)){

											if($aREC_TB_AREA['KODE_AREA']==$cFILTER_AREA){
												echo "<option value='".$aREC_TB_AREA['KODE_AREA']. "' selected='$qQUERY[UNITUP]' >$aREC_TB_AREA[NAMA_AREA]</option>";
											} else {
												echo "<option value='".$aREC_TB_AREA['KODE_AREA']. "'  >$aREC_TB_AREA[NAMA_AREA]</option>";
											}
										}
									}
								?>
								</select>

								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?> nowrap">
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNOMOR_HP?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cTARGET?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKD_USER?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cAREA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cJML_PLG?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cOS_HP?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTIPE_HP?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_BM_TB_CATTER1=SYS_FETCH($q_CATTER1)) {
														echo '<tr>';
															echo '<td style="width: 1px;"></td>';
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_BM_TB_CATTER1['KODE_CATTER'])."'>".decode_string($aREC_BM_TB_CATTER1['KODE_CATTER'])."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_BM_TB_CATTER1['KODE_CATTER'])."'>".decode_string($aREC_BM_TB_CATTER1['NAMA_CATTER'])."</a></span></td>";
															echo "<td>".decode_string($aREC_BM_TB_CATTER1['NOMOR_HP'])."</td>";
															echo "<td align='right'>".number_format($aREC_BM_TB_CATTER1['TARGET'])."</td>";
															echo "<td>".decode_string($aREC_BM_TB_CATTER1['USER_CODE'])."</td>";
															echo "<td>".$aREC_BM_TB_CATTER1['NAMA_AREA']."</td>";
		$q_TB_PLG =SYS_QUERY("select count(*) PLG from bm_tb_plg where PETUGAS='$aREC_BM_TB_CATTER1[KODE_CATTER]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		$aCOUNT_PLG =SYS_FETCH($q_TB_PLG);
															echo "<td align='right'>".number_format($aCOUNT_PLG['PLG'])."</td>";
															echo "<td>".$aREC_BM_TB_CATTER1['OS_HP']."</td>";
															echo "<td>".$aREC_BM_TB_CATTER1['TIPE_HP']."</td>";
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

			<div class="modal" id="help_prs_bm_tb_catter1" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_3?>	<p><?php echo $cHELP_4?></p>
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
	$cHEADER	= S_MSG('PN31','Tambah Pencatat');
	$cHELP_BOX	= S_MSG('PN5J','Help Tambah Pencatat Meter');
	$cHELP_1	= S_MSG('PN5K','Ini adalah modul untuk memasukkan data Pencatat Meter / Bill Man yang BARU apabila ada penambahan.');
	$cHELP_2	= S_MSG('PN5L','Msukkan kode, nama, nomor HP, target baca per hari dan catatan seperlunya.');
	$cHELP_3	= S_MSG('PN5M','Harap diperhatikan, masukkan nomor HP dengan benar, karena diperlukan untuk pencatat meter untuk login di aplikasi mobile.');
	$cHELP_4	= S_MSG('PN5N','Hanya pencatat meter yang terdaftar nomor HP nya yang bisa menggunakan aplikasi dan memasukkan data pelanggan.');
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");		require_once("scr_topbar.php");	?>
		<body class=" ">
			<div class="page-container row-fluid">
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper">
						<?php	require_once("scr_menu.php");	?>
					</div>
					<div class="project-info"></div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class='col-lg-9 col-md-12 col-sm-9 col-xs-9'>
							<div class="page-title">

								<div class="pull-left">
									<h2 class="title"><?php echo $cHEADER?></h2>                            
								</div>
								<div class="pull-right hidden-xs"></div>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>
												<a href="#help_add_prs_bm_tb_catter1" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
							<section class="box ">
								<div class="content-body">
									<div class="row">
										<form action ="?_a=tambah" method="post">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='ADD_KODE_CATTER' title="<?php echo $cTTIP_KODE?>" autofocus>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_NAMA_CATTER' title="<?php echo $cTTIP_NAMA?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNOMOR_HP?></label>
												<input type="number" class="col-sm-5 form-label-900" name='ADD_NOMOR_HP' title="<?php echo $cTTIP_NOHP?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cTARGET?></label>
												<input type="number" class="col-sm-2 form-label-900" name='ADD_TARGET' id="field-4" data-mask="fdecimal" title="<?php echo $cTTIP_TRGT ?>"  maxlength="3">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_USER?></label>
												<input type="text" class="col-sm-5 form-label-900" name='ADD_USER_CODE' id="field-2">
												<div class="clearfix"></div><br>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cAREA?></label>
												<select name="ADD_KODE_AREA" class="col-sm-3 form-label-900">
												<?php 
													$q_TB_AREA=SYS_QUERY("select * from tb_area where APP_CODE='$cAPP_CODE' and DELETOR=''");
													while($a_TB_AREA =SYS_FETCH($q_TB_AREA)){
														echo "<option value='".$a_TB_AREA['KODE_AREA']. "'  >$a_TB_AREA[NAMA_AREA]</option>";
													}
												?>
												</select>	<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-8 form-label-900" name='ADD_CATTER_NOTE' id="field-2">
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
			
			<div class="modal" id="help_add_prs_bm_tb_catter1" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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

case md5('up_d4t3'):
	$cHEADER	= S_MSG('PN32','Edit Tabel Pencatat');
	$cHELP_BOX	= S_MSG('PN5A','Help Edit Tabel Pencatat Meter');
	$cHELP_1	= S_MSG('PN5B','Ini adalah fungsi untuk mengedit data Pencatat Meter / Bill Man yang sudah pernah masuk.');
	$cHELP_2	= S_MSG('PN5C','Isi kan perubahan yang diperlukan pada masing-masing data, yaitu Nama, Nomor HP dan Keterangan.');
	$cHELP_3	= S_MSG('PN5D','Klik Save untuk menyimpan perubahan yang telah dilakukan, atau Close untuk mengabaikan perubahan.');

	$cQUERY ="select * from bm_tb_catter1 where APP_CODE='$cAPP_CODE' and md5(KODE_CATTER)='$_GET[_r]' and DELETOR=''";
	$qQUERY =SYS_QUERY($cQUERY);
	$REC_bm_tb_catter1=SYS_FETCH($qQUERY);
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
					<div class="project-info"></div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class='col-lg-9 col-md-9 col-sm-9 col-xs-9'>
							<div class="page-title">

								<div class="pull-left">
									  <h2 class="title"><?php echo $cHEADER?></h2>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<?php echo '<a href="?_a='.md5('del_area').'&_i='. md5($REC_bm_tb_catter1['KODE_CATTER']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
										</li>
										<li>
											<a href="#help_upd_prs_bm_tb_catter1" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
										<form action ="?_a=rubah&id=<?php echo $REC_bm_tb_catter1['KODE_CATTER']?>" method="post">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_KODE_CATTER' value=<?php echo $REC_bm_tb_catter1['KODE_CATTER']?> disabled="disabled" title="<?php echo $cTTIP_KODE?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-8 form-label-900" name='EDIT_NAMA_CATTER' value="<?php echo decode_string($REC_bm_tb_catter1['NAMA_CATTER'])?>" title="<?php echo $cTTIP_NAMA?>" autofocus>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNOMOR_HP?></label>
												<input type="text" class="col-sm-5 form-label-900" name='EDIT_NOMOR_HP' value="<?php echo decode_string($REC_bm_tb_catter1['NOMOR_HP'])?>" title="<?php echo $cTTIP_NOHP?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cTARGET?></label>
												<input type="text" class="col-sm-2 form-label-900" name='UPD_TARGET' id="field-4" data-mask="fdecimal" value=<?php echo $REC_bm_tb_catter1['TARGET']?> title="<?php echo $cTTIP_TRGT ?>"  maxlength="3">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_USER?></label>
												<input type="text" class="col-sm-3 form-label-900" name='UPD_USER_CODE' value="<?php echo decode_string($REC_bm_tb_catter1['USER_CODE'])?>" title="<?php echo $cTTIP_USER?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cAREA?></label>
												<select name="UPD_KODE_AREA" class="col-sm-3 form-label-900">
												<?php 
													$q_TB_AREA=SYS_QUERY("select * from tb_area where APP_CODE='$cAPP_CODE' and DELETOR=''");
													while($a_TB_AREA =SYS_FETCH($q_TB_AREA)){

														if($a_TB_AREA['KODE_AREA']==$REC_bm_tb_catter1['KODE_AREA']){
															echo "<option value='".$a_TB_AREA['KODE_AREA']. "' selected='$a_TB_AREA[NAMA_AREA]' >$a_TB_AREA[NAMA_AREA]</option>";
														} else {
															echo "<option value='".$a_TB_AREA['KODE_AREA']. "'  >$a_TB_AREA[NAMA_AREA]</option>";
														}
													}
												?>
												</select>	<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-8 form-label-900" name='EDIT_CATTER_NOTE' value="<?php echo decode_string($REC_bm_tb_catter1['CATTER_NOTE'])?>" title="<?php echo $cTTIP_KET ?>">
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

			<div class="modal" id="help_upd_prs_bm_tb_catter1" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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

case 'tambah':
	$NOW = date("Y-m-d H:i:s");
	$cKODE_CATTER	= encode_string($_POST['ADD_KODE_CATTER']);	
	if($cKODE_CATTER==''){
		$cMSG_BLANK	= S_MSG('PN36','Kode Pencatat Meter belum diisi');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from bm_tb_catter1 where APP_CODE='$cAPP_CODE' and DELETOR='' and KODE_CATTER='$cKODE_CATTER'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG_EXIST	= S_MSG('PN37','Kode Pencatat Meter sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
		header('location:bm_tb_catter1.php');
	} else {
		$cNAMA_CATTER	= encode_string($_POST['ADD_NAMA_CATTER']);	
		$cNOMOR_HP		= encode_string($_POST['ADD_NOMOR_HP']);	
		$cKODE_USER		= encode_string($_POST['ADD_USER_CODE']);	
		$cDESC_CATTER	= encode_string($_POST['ADD_CATTER_NOTE']);	
		$nTARGET		= str_replace(',', '', $_POST['ADD_TARGET']);
		$cQUERY="insert into bm_tb_catter1 set KODE_CATTER='$cKODE_CATTER', NAMA_CATTER='$cNAMA_CATTER', NOMOR_HP='$cNOMOR_HP', USER_CODE='$cKODE_USER', CATTER_NOTE='$cDESC_CATTER', TARGET='$nTARGET', KODE_AREA='$_POST[ADD_KODE_AREA]', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		APP_LOG_ADD($cHEADER, 'add : '.$cKODE_CATTER);
		header('location:prs_tb_catter.php');
	}
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cNAMA_CATTER	= encode_string($_POST['EDIT_NAMA_CATTER']);	
	$cDESC_CATTER	= encode_string($_POST['EDIT_CATTER_NOTE']);	
	$cNOMOR_HP		= encode_string($_POST['EDIT_NOMOR_HP']);
	$cKODE_USER		= encode_string($_POST['UPD_USER_CODE']);	
	$nTARGET		= str_replace(',', '', $_POST['UPD_TARGET']);
	$cQUERY ="update bm_tb_catter1 set NAMA_CATTER='$cNAMA_CATTER', NOMOR_HP='$cNOMOR_HP', TARGET='$nTARGET', CATTER_NOTE='$cDESC_CATTER', USER_CODE='$cKODE_USER', KODE_AREA='$_POST[UPD_KODE_AREA]', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cAPP_CODE' and KODE_CATTER='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	APP_LOG_ADD($cHEADER, 'edit : '.$KODE_CRUD);
	header('location:prs_tb_catter.php');
	break;

case md5('del_area'):
	$NOW 	= date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['_i'];
	$cQUERY ="update bm_tb_catter1 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cAPP_CODE' and md5(KODE_CATTER)='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	APP_LOG_ADD($cHEADER, 'delete : '.$KODE_CRUD);
	header('location:prs_tb_catter.php');
}
?>

<script>

function FILTER_DATA(pAREA) {
	window.location.assign("?_r="+pAREA);
}

</script>
