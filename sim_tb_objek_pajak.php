<?php
//	sim_tb_objek_pajak.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$nJENIS_PNDPT = 0;
	$q_JNS_PND=SYS_QUERY("select APP_CODE, DELETOR from jns_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nJENIS_PNDPT = SYS_ROWS($q_JNS_PND);
	
	$c_OBJ_PJK ="SELECT obj_pjk.*, npwpd.* FROM obj_pjk 
		LEFT JOIN npwpd ON obj_pjk.NPWPD_NO=npwpd.NPWPD_NO 
		where obj_pjk.APP_CODE='$cFILTER_CODE' and obj_pjk.DELETOR=''";
	
	$q_OBJ_PJK=SYS_QUERY($c_OBJ_PJK);

	$cHEADER 		= S_MSG('SK50','Data Objek Pajak');
	$cNOMOR_DATA	= S_MSG('SK73','No. Data');
	$cTANGGAL		= S_MSG('SK62','Tanggal');
	$cTHN_BULAN		= S_MSG('SK74','Thn/Bln');
	$cNAMA_NPWPD 	= S_MSG('SK75','Nama WP/WR');
	$cALAMAT_WPWR 	= S_MSG('SN21','Alamat');
	$cNOMOR_NPWPD 	= S_MSG('SM51','NPWPD/RD');
	$cJENIS_PAJAK 	= S_MSG('SK21','PajakRetribusi');
	$cOMZET 		= S_MSG('SK67','Omzet');
	$cJML_PAJAK 	= S_MSG('SK70','Jumlah Pajak');
	$cTARIF			= S_MSG('SK68','Tarif');
	$cPRD_TAGIHAN	= S_MSG('SK71','Periode Tagihan');
	$cGRUP_USAHA	= S_MSG('SK22','Grup Usaha');
	$cJNS_PNDPTN	= S_MSG('SK23','Jenis Pendapatan');
	$cKODE_REK		= S_MSG('SN81','Kode Rekening');
	$cKET_OBJ		= S_MSG('SM40','Ket. Objek Pajak');
	$cKECAMATAN		= S_MSG('SM92','Kecamatan');
	$cKELURAHAN 	= S_MSG('SM93','Kelurahan');
	$cKABUPATEN 	= S_MSG('SN95','Kabupaten/Kota');
	$cPRD_TGHN1		= S_MSG('SK71','Periode Tagihan');
	$cPRD_TGHN2		= S_MSG('SK72','s/d');
	
	$cTTIP_NO_DATA	= S_MSG('SK80','Nomor Pendataan otomatis dibuat oleh sistem');
	$cTTIP_TANGGAL	= S_MSG('SK8B','Tanggal Pendataan Objek Pajak');
	$cTTIP_TAHUN	= S_MSG('SK8C','Tahun Objek Pajak');
	$cTTIP_BULAN	= S_MSG('SK8D','Periode bulan awal objek pajak');
	$cTTIP_NO_NPWP	= S_MSG('SK8E','No. NPWPD/RD yang sudah terdaftar di master induk NPWPD/RD');
	$cTTIP_NM_NPWP	= S_MSG('SK8F','Nama NPWPD/RD, diambil dari master induk');
	$cTTIP_NPWP_ALMT1 = S_MSG('SK8G','Alamat NPWPD/RD, diambil dari master induk');
	$cTTIP_NPWP_LURAH = S_MSG('SK8H','Kelurahan NPWPD/RD, diambil dari master induk');
	$cTTIP_NPWP_CAMAT = S_MSG('SK8I','Kecamatan NPWPD/RD, diambil dari master induk');
	$cTTIP_NPWP_KABKT = S_MSG('SK8J','Kabupaten/Kota NPWPD/RD, diambil dari master induk');
	
	$cADD_REK		= S_MSG('SK91','Tambah Objek Pajak');
	$cSAVE			= S_MSG('F301','Save');
	$cMSG_DEL		= S_MSG('F021','Benar data ini mau di hapus ?');

	$cMSG_MSH		= S_MSG('SK92','Objek Pajak ini masih mempunyai transaksi, tidak dapat di hapus ?');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
switch($cACTION){
	default:
		$cHELP_BOX		= S_MSG('SK9A','Help Data Objek Pajak');
		$cHELP_1		= S_MSG('SK9B','Ini adalah modul untuk memasukkan data Objek Pajak dari wajib pajak yang ada');
		$cHELP_2		= S_MSG('SK9C','Untuk memasukkan data Objek Pajak baru, klik tambah Objek Pajak / add new');
		$cHELP_3		= S_MSG('SN9D','Sekarang ini ditampilkan daftar Objek Pajak yang telah pernah dimasukkan');
		$cHELP_4		= S_MSG('SK9E','Untuk merubah salah satu data Objek Pajak, klik di nomor Objek Pajak dan akan masuk ke mode update');
		$cHELP_5		= S_MSG('SK9F','Nomor Data : nomor objek pajak yang dimiliki oleh setiap WP/WR yang urut berdasarkan urutan pemasukan data. Setiap WP/WR bisa memiliki lebih dari 1 nomor objek pajak.');
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
					<div class="project-info"> </div>
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
												 <a href="?_a=<?php echo md5('CREATE')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_sim_tb_objek_pajak" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="background-color:LightGray;"><?php echo $cNOMOR_DATA?></th>
														<th style="background-color:LightGray;"><?php echo $cTANGGAL?></th>
														<th style="background-color:LightGray;"><?php echo $cNOMOR_NPWPD?></th>
														<th style="background-color:LightGray;"><?php echo $cNAMA_NPWPD?></th>
														<th style="background-color:LightGray;"><?php echo $cALAMAT_WPWR?></th>
														<th style="background-color:LightGray;"><?php echo $cOMZET?></th>
														<th style="background-color:LightGray;"><?php echo $cJML_PAJAK?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($r_OBJ_PJK=SYS_FETCH($q_OBJ_PJK)) {
														echo '<tr>';
															echo '<td class=""><div class="star"><i class="fa fa-cloud-upload icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?_a=". md5('UPDate'). "&_p=".md5($r_OBJ_PJK['NO_OBJPJ']). "'>".$r_OBJ_PJK['NO_OBJPJ']."</a></span></td>";
															echo '<td>'.date("d-m-Y", strtotime($r_OBJ_PJK['TGL_OBJPJK'])).'</td>';
															echo '<td>'.decode_string($r_OBJ_PJK['NPWPD_NO']).'</td>';
															echo '<td>'.decode_string($r_OBJ_PJK['NPWPD_NAME']).'</td>';
															echo '<td>'.decode_string($r_OBJ_PJK['NP_ADD1']).'</td>';
															echo '<td align="right">'.number_format($r_OBJ_PJK['OM_ZET']).'</td>';
															echo '<td align="right">'.number_format($r_OBJ_PJK['JML_PAJAK']).'</td>';
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
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script> 
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 
			<div class="modal" id="help_sim_tb_objek_pajak" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">

							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_3?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_4?></p>	<p><?php echo $cHELP_5?></p>

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

case md5('CREATE'):
	$cHELP_BOX		= S_MSG('SN2A','Help Tambah Objek Pajak');
	$cHELP_1		= S_MSG('SN2B','Ini adalah modul untuk menambahkan data Objek Pajak yang baru');
	$cHELP_2		= S_MSG('SN2C','Data Objek Pajak ini memuat data masing-masing Objek pajak untuk setiap pemegang NPWP');
	$cHELP_3		= S_MSG('SN2D','Data yang dimasukkan pada Data Objek Pajak ini adalah :');
	$cHELP_4		= S_MSG('SN2E','Nomor NPWPD/RD : adalah nomor pokok wajib pajak atau wajib retribusi untuk masing-masing wajib pajak');

	$cQ_LAST	= "select NO_OBJPJ from obj_pjk where APP_CODE='$cFILTER_CODE' and DELETOR='' order by NO_OBJPJ desc limit 1";
	$qQ_LAST	= SYS_QUERY($cQ_LAST);
	$a_OBJ_PJK 	= SYS_FETCH($qQ_LAST);
	$cLAST_NOM	= $a_OBJ_PJK['NO_OBJPJ'];
	$nLAST_NOM	= intval($cLAST_NOM)+1;
	$cLAST_NOM	= str_pad((string)$nLAST_NOM, 7, '0', STR_PAD_LEFT);

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
						<div class="clearfix"></div>

						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left"><?php echo $cADD_REK?></h2>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>	<a href="#help_add_sim_tb_objek_pajak" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>	</li>
										</ol>
									</div>
								</header>	
								
								<div class="content-body">
									<div class="row">
										<form action ="?_a=tambah" method="post">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNOMOR_DATA?></label>
												<input type="text" class="col-sm-2 form-label-900" name='ADD_NO_OBJPJ' value="<?php echo $cLAST_NOM?>">
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-2 form-label-900 datepicker" data-mask="date" name='ADD_TGL_OBJPJK' id="field-2" value="<?php echo date("d-m-Y")?>" title="<?php echo $cTTIP_TANGGAL ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cTHN_BULAN?></label>
												<input type="text" class="col-sm-1 form-label-900" name='ADD_OBJ_THN' data-mask="fdecimal" value="<?php echo date("Y")?>" title="<?php echo $cTTIP_TAHUN ?>">
												<label class="col-sm-1 form-label-700" for="field-3"></label>
												<input type="text" class="col-sm-1 form-label-900" name='ADD_OBJ_BULAN' data-mask="fdecimal" value="<?php echo date("m")?>" title="<?php echo $cTTIP_BULAN ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNOMOR_NPWPD?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_NPWPD_NO' id="FMT_NPWPD_NO" onblur="Disp_NPWP(this.value)" title="<?php echo $cTTIP_NO_NPWP ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-2"><?php echo $cNAMA_NPWPD?></label>
												<input type="text" class="col-sm-6 form-label-900" name='UPD_NAMA_NPWP' id="field-2" disabled="disabled"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cALAMAT_WPWR?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_NP_ADD1' id="ADD_NP_ADD1" disabled="disabled" title="<?php echo $cTTIP_NPWP_ALMT1 ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-4"><?php echo $cKECAMATAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_CAMAT_NAME' id="ADD_CAMAT_NAME" disabled="disabled" title="<?php echo $cTTIP_NPWP_CAMAT ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-21"><?php echo $cKELURAHAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_LURAH_NAME' id="ADD_LURAH_NAME" disabled="disabled" title="<?php echo $cTTIP_NPWP_LURAH ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-21"><?php echo $cKABUPATEN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_KABK_NAME' id="ADD_KABK_NAME" disabled="disabled" title="<?php echo $cTTIP_NPWP_KABKT ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700"><?php echo $cJENIS_PAJAK?></label>
												<select name='ADD_KLP_PND' class="col-sm-4 form-label-900">
												<?php 
													$q_KLP_PND =SYS_QUERY("select KPND_CODE, KPND_DESC, APP_CODE, DELETOR from klp_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($a_KLP_PND=SYS_FETCH($q_KLP_PND)){
														echo "<option value='$a_KLP_PND[KPND_CODE]'  >$a_KLP_PND[KPND_DESC]</option>";
													}
												?>
												</select><br><div class="clearfix"></div>

												<?php 
													if($nJENIS_PNDPT > 0) {
														echo '<label class="col-sm-3 form-label-700" for="field-5">'.$cJNS_PNDPTN.'</label>
															<select name="ADD_PND_CODE" class="col-sm-5 form-label-900">';
														$q_JNS_PND=SYS_QUERY("select * from jns_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
														while($r_JNS_PND=SYS_FETCH($q_JNS_PND)){
																echo "<option value='$r_JNS_PND[PND_CODE]'  >$r_JNS_PND[PND_DESC]</option>";
														}
														echo '</select>';
													} else {
														echo '<br><br>';
													}
												?>	<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-6"><?php echo $cKODE_REK?></label>
												<select name='ADD_KODE_REK' class="col-sm-4 form-label-900">
												<?php 
													echo "<option value=' '  > </option>";
													$q_REK_PND =SYS_QUERY("select * from rek_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($a_REK_PND=SYS_FETCH($q_REK_PND)){
														echo "<option value='$a_REK_PND[RPND_CODE]'  >$a_REK_PND[RPND_DESC]</option>";
													}
												?>
												</select><br><div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cOMZET?></label>
												<input type="text" class="col-sm-2 form-label-900" name='ADD_OM_ZET' id="field-2" data-mask="fdecimal" data-numeric-align="right"><br>
												<div class="clearfix"></div>
														
												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cTARIF?></label>
												<input type="text" class="col-sm-2 form-label-900" name='ADD_TP_RIF' id="field-3" data-mask="fdecimal" data-numeric-align="right"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-6"><?php echo $cJML_PAJAK?></label>
												<input type="text" class="col-sm-2 form-label-900" id="field-8" name='ADD_JML_PAJAK' data-mask="fdecimal" data-numeric-align="right"><br>
												<div class="clearfix"></div><br>
											
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cPRD_TGHN1?></label>
												<input type="text" class="col-sm-2 form-label-900 datepicker" data-mask="date" name='ADD_PRD_TGHN1' id="field-2" value="<?php echo date("d-m-Y")?>">
												<label class="col-sm-1 form-label-700" for="field-1"></label>
												<label class="col-sm-1 form-label-700" style="text-align:right;"><?php echo $cPRD_TGHN2?></label>
												<input type="text" class="col-sm-2 form-label-900 datepicker" data-mask="date" name='ADD_PRD_TGHN2' id="field-2" value="<?php echo date("d-m-Y")?>"><br>
												<div class="clearfix"></div>

												<div class="text-left">
													<input type="submit" id="SAVE_ADD" class="btn btn-primary" value=<?php echo $cSAVE?> disabled="disabled">
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

			<div class="modal" id="help_add_sim_tb_objek_pajak" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
	SYS_DB_CLOSE($DB2);
	break;

case md5('UPDate'):
	$c_OBJ_PJK = "select obj_pjk.*, npwpd.*, 
		kcamatan.CAMAT_CODE, kcamatan.CAMAT_NAME, kelurahan.LURAH_CODE, kelurahan.LURAH_NAME , kab_kota.KABK_CODE, kab_kota.KABK_NAME
		from obj_pjk 
		left join npwpd ON obj_pjk.NPWPD_NO=npwpd.NPWPD_NO 
		left join kcamatan ON kcamatan.CAMAT_CODE=npwpd.CAMAT_CODE 
		left join kelurahan ON kelurahan.LURAH_CODE=npwpd.LURAH_CODE 
		left join kab_kota ON kab_kota.KABK_CODE=npwpd.KABK_CODE 
		where md5(obj_pjk.NO_OBJPJ)='".$_GET['_p'];
	$c_OBJ_PJK.= "' and obj_pjk.APP_CODE='".$cFILTER_CODE."' and obj_pjk.DELETOR=''";
	$q_OBJ_PJK=SYS_QUERY($c_OBJ_PJK);
	$r_OBJ_PJK=SYS_FETCH($q_OBJ_PJK);
	if(SYS_ROWS($q_OBJ_PJK)==0){
		header('location:sim_tb_objek_pajak.php?');
	}
?>
	<!DOCTYPE html>
	<html class=" ">
		<body class=" ">
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
									  <h2 class="title"><?php echo S_MSG('SK93','Edit Data Objek Pajak')?></h2>
								</div>
								<div class="pull-right">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=<?php echo md5('DELete')?>&id=<?php echo md5($r_OBJ_PJK['NO_OBJPJ'])?>" onClick="return confirm('<?php echo $cMSG_DEL?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
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
										<form action ="?_a=rubah&id=<?php echo $r_OBJ_PJK['NO_OBJPJ']?>" method="post">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label class="col-sm-3 form-label-700" for="field-2"><?php echo $cNOMOR_DATA?></label>
												<input type="text" class="col-sm-2 form-label-900" name='UPD_NO_OBJPJ' id="field-2" value="<?php echo $r_OBJ_PJK['NO_OBJPJ']?>" disabled="disabled" title="<?php echo $cTTIP_NO_DATA ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-2 form-label-900 datepicker" data-mask="date" name='UPD_TGL_OBJPJK' id="field-2" value="<?php echo date("d-m-Y", strtotime($r_OBJ_PJK['TGL_OBJPJK']))?>" title="<?php echo $cTTIP_TANGGAL ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cTHN_BULAN?></label>
												<input type="text" class="col-sm-1 form-label-900" name='UPD_OBJ_THN' data-mask="fdecimal" value="<?php echo $r_OBJ_PJK['OBJ_THN']?>" title="<?php echo $cTTIP_TAHUN ?>">
												<label class="col-sm-1 form-label-700" for="field-3"></label>
												<input type="text" class="col-sm-1 form-label-900" name='UPD_OBJ_BULAN' data-mask="fdecimal" value="<?php echo $r_OBJ_PJK['OBJ_BULAN']?>" title="<?php echo $cTTIP_BULAN ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNOMOR_NPWPD?></label>
												<input type="text" class="col-sm-3 form-label-900" name='UPD_NPWPD_NO' id="FMT_NPWPD_NO" value="<?php echo decode_string($r_OBJ_PJK['NPWPD_NO'])?>" title="<?php echo $cTTIP_NO_NPWP ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-2"><?php echo $cNAMA_NPWPD?></label>
												<input type="text" class="col-sm-6 form-label-900" name='UPD_NAMA_NPWP' id="field-2" value="<?php echo decode_string($r_OBJ_PJK['NPWPD_NAME'])?>" disabled="disabled"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cALAMAT_WPWR?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ALMT_ANG' id="field-2" value="<?php echo decode_string($r_OBJ_PJK['NP_ADD1'])?>" disabled="disabled" title="<?php echo $cTTIP_NPWP_ALMT1 ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-4"><?php echo $cKECAMATAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='UPD_CAMAT_NAME' id="field-2" value="<?php echo decode_string($r_OBJ_PJK['CAMAT_NAME'])?>" disabled="disabled" title="<?php echo $cTTIP_NPWP_CAMAT ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-21"><?php echo $cKELURAHAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='UPD_LURAH_NAME' id="field-2" value="<?php echo decode_string($r_OBJ_PJK['LURAH_NAME'])?>" disabled="disabled" title="<?php echo $cTTIP_NPWP_LURAH ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-21"><?php echo $cKABUPATEN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='UPD_KABK_NAME' id="field-2" value="<?php echo decode_string($r_OBJ_PJK['KABK_NAME'])?>" disabled="disabled" title="<?php echo $cTTIP_NPWP_KABKT ?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700"><?php echo $cJENIS_PAJAK?></label>
												<select name='UPD_KLP_PND' class="col-sm-4 form-label-900">
												<?php 
//													echo "<option value=' '  > </option>";
													$q_KLP_PND =SYS_QUERY("select KPND_CODE, KPND_DESC, APP_CODE, DELETOR from klp_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($a_KLP_PND=SYS_FETCH($q_KLP_PND)){
														if($r_OBJ_PJK['KLP_PND']==$a_KLP_PND['KPND_CODE']){
															echo "<option value='$a_KLP_PND[KPND_CODE]' selected='$r_OBJ_PJK[KLP_PND]' >$a_KLP_PND[KPND_DESC]</option>";
														} else
														echo "<option value='$a_KLP_PND[KPND_CODE]'  >$a_KLP_PND[KPND_DESC]</option>";
													}
												?>
												</select><br><div class="clearfix"></div>

												<?php 
													if($nJENIS_PNDPT > 0) {
														echo '<label class="col-sm-3 form-label-700" for="field-5">'.$cJNS_PNDPTN.'</label>
															<select name="UPD_PND_CODE" class="col-sm-5 form-label-900">';
														$q_JNS_PND=SYS_QUERY("select * from jns_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
														while($r_JNS_PND=SYS_FETCH($q_JNS_PND)){
															if($r_OBJ_PJK['JENIS_PND']==$r_JNS_PND['PND_DESC']){
																echo "<option value='$r_JNS_PND[PND_CODE]' selected='$r_OBJ_PJK[JENIS_PND]' >$r_JNS_PND[PND_DESC]</option>";
															} else {
																echo "<option value='$r_JNS_PND[PND_CODE]'  >$r_JNS_PND[PND_DESC]</option>";
															}
														}
														echo '</select>';
													} else {
														echo '<br><br>';
													}
												?>	<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-6"><?php echo $cKODE_REK?></label>
												<select name='UPD_KODE_REK' class="col-sm-4 form-label-900">
												<?php 
													echo "<option value=' '  > </option>";
													$q_REK_PND =SYS_QUERY("select * from rek_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($a_REK_PND=SYS_FETCH($q_REK_PND)){
														if($r_OBJ_PJK['KODE_REK']==$a_REK_PND['RPND_CODE']){
															echo "<option value='$a_REK_PND[RPND_CODE]' selected='$r_OBJ_PJK[KODE_REK]' >$a_REK_PND[RPND_DESC]</option>";
														} else
														echo "<option value='$a_REK_PND[RPND_CODE]'  >$a_REK_PND[RPND_DESC]</option>";
													}
												?>
												</select><br><div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cOMZET?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_OM_ZET' id="field-2" data-mask="fdecimal" data-numeric-align="right" value=<?php echo $r_OBJ_PJK['OM_ZET']?>><br>
												<div class="clearfix"></div>
														
												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cTARIF?></label>
												<input type="text" class="col-sm-2 form-label-900" name='UPD_TP_RIF' id="field-3" data-mask="fdecimal" data-numeric-align="right" value=<?php echo $r_OBJ_PJK['TP_RIF']?>  ><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-6"><?php echo $cJML_PAJAK?></label>
												<input type="text" class="col-sm-2 form-label-900" id="field-8" name='UPD_JML_PAJAK' data-mask="fdecimal" data-numeric-align="right" value=<?php echo $r_OBJ_PJK['JML_PAJAK']?> ><br>
												<div class="clearfix"></div><br>
											
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cPRD_TGHN1?></label>
												<input type="text" class="col-sm-2 form-label-900 datepicker" data-mask="date" name='UPD_PRD_TGHN1' id="field-2" value="<?php echo date("d-m-Y", strtotime($r_OBJ_PJK['PRD_TGHN1']))?>">
												<label class="col-sm-1 form-label-700" for="field-1"></label>
												<label class="col-sm-1 form-label-700" style="text-align:right;"><?php echo $cPRD_TGHN2?></label>
												<input type="text" class="col-sm-2 form-label-900 datepicker" data-mask="date" name='UPD_PRD_TGHN2' id="field-2" value="<?php echo date("d-m-Y", strtotime($r_OBJ_PJK['PRD_TGHN2']))?>"><br>
												<div class="clearfix"></div>

												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE?>>
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
			<script src="assets/plugins/select2/select2.min.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 
		</body>
	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case 'tambah':
	$cNO_OBJPJ = encode_string($_POST['ADD_NO_OBJPJ']);
	if($cNO_OBJPJ=='') {
		$cMSG = S_MSG('SK94','Nomor Objek Pajak tidak boleh kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	$cNO_NPWP = encode_string($_POST['ADD_NPWPD_NO']);
	if($cNO_NPWP=='') {
		$cMSG = S_MSG('SK95','Nomor NPWP tidak boleh kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	if($_POST['ADD_OM_ZET']==0){
		$cMSG= S_MSG('SK96','Nilai Omzet masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	$cQUERY="select * from npwpd where APP_CODE='$cFILTER_CODE' and NPWPD_NO='$cNO_NPWP' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$cMSG = S_MSG('SK97','Nomor NPWP tidak ditemukan');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	$cQUERY="select * from obj_pjk where APP_CODE='$cFILTER_CODE' and NO_OBJPJ='$cNO_OBJPJ' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$cQUERY ="insert into obj_pjk set NO_OBJPJ='$cNO_OBJPJ', NPWPD_NO='$cNO_NPWP'";
		$cQUERY.=", OBJ_THN=0".str_replace(',', '', $_POST['ADD_OBJ_THN']).", OBJ_BULAN=0".str_replace(',', '', $_POST['ADD_OBJ_BULAN']).", TGL_OBJPJK='$_POST[ADD_TGL_OBJPJK]'";
		$cQUERY.=", OM_ZET=0".str_replace(',', '', $_POST['ADD_OM_ZET']).", BY_ASURANS=0".str_replace(',', '', $_POST['ADD_BY_ASURANSI']);
		$cQUERY.=", BIAYA_PRV=0".str_replace(',', '', $_POST['ADD_BIAYA_PRV']).", TP_RIF=0".str_replace(',', '', $_POST['ADD_TP_RIF']);
		$cQUERY.=", JML_PAJAK=0".str_replace(',', '', $_POST['ADD_JML_PAJAK']);
		$cQUERY.=", BIAYA_ADM=0".str_replace(',', '', $_POST['ADD_BIAYA_ADM']);
		$cQUERY.=", BIAYA_BLN=0".str_replace(',', '', $_POST['ADD_BIAYA_BLN']).", KD_AGUNAN='$_POST[ADD_KODE_AGGN]'";
		$cQUERY.=", APP_CODE='$cFILTER_CODE', ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);
		header('location:sim_tb_objek_pajak.php');
	} else {
		$cMSG = S_MSG('KA26','Nomor Rekening sudah ada, tidak bisa digunakan lagi, silakan isi yang lain');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	break;

case 'rubah':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$str_DATE = $_POST['UPD_TGL_OBJPJK'];		// 'dd/mm/yyyy'
	$cDATE = substr($str_DATE,6,4). '-'. substr($str_DATE,3,2). '-'. substr($str_DATE,0,2);
	$cNO_NPWP = encode_string($_POST['UPD_NPWPD_NO']);
	$c_NPWPD ="select NPWPD_NO from npwpd where NPWPD_NO='$cNO_NPWP' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$q_NPWPD =SYS_QUERY($c_NPWPD);
	if(SYS_ROWS($q_NPWPD)==0){
		$cMSG= S_MSG('SK97','Nomor NPWP tidak ditemukan');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	if($_POST['EDIT_OM_ZET']==0){
		$cMSG= S_MSG('SK96','Nilai Omzet masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	$cQUERY ="update obj_pjk set ";
	$cQUERY.=" TGL_OBJPJK='$cDATE', OBJ_THN=0".str_replace(',', '', $_POST['UPD_OBJ_THN']).", OBJ_BULAN=0".str_replace(',', '', $_POST['UPD_OBJ_BULAN']);
	$cQUERY.=" NPWPD_NO='".$cNO_NPWP."', ";
	$cQUERY.=" OM_ZET=".str_replace(',', '', $_POST['EDIT_OM_ZET']).", ";
	$cQUERY.=" TP_RIF=".str_replace(',', '', $_POST['UPD_TP_RIF']).", JML_PAJAK=".str_replace(',', '', $_POST['UPD_JML_PAJAK']).", ";
	$cQUERY.=" BIAYA_ADM=".str_replace(',', '', $_POST['EDIT_BIAYA_ADM']).", ";
	$cQUERY.=" BIAYA_BLN=".str_replace(',', '', $_POST['EDIT_BIAYA_BLN']).", KD_AGUNAN='$_POST[EDIT_KODE_AGGN]', ";
	$cQUERY.=" UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and NO_OBJPJ='$KODE_CRUD'";
	SYS_QUERY($cQUERY);

	header('location:sim_tb_objek_pajak.php?');
	break;

case md5('DELete'):
	$NOMOR_DATA=$_GET['id'];
	$q_SKPD =SYS_QUERY("select NO_OBJPJ from skpd where md5(NO_OBJPJ)='$NOMOR_DATA' and APP_CODE='$cFILTER_CODE' and DELETOR='' ");
	if (SYS_ROWS($q_SKPD)==0) {
		$NOW = date("Y-m-d H:i:s");
		$cQUERY ="update obj_pjk set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
		$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(NO_OBJPJ)='$NOMOR_DATA'";
		SYS_QUERY($cQUERY);
		header('location:sim_tb_objek_pajak.php');
	} else {
		echo "<script> alert('$cMSG_MSH');	window.history.back();	</script>";
		return;
	}
}
?>


<script>
function Disp_NPWP(pkode_member) {
	var btn_stat = document.getElementById("SAVE_ADD");  // the submit button
    if (pkode_member == "") {
        document.getElementById("ADD_NPWPD_NO").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("f_NM_MMBR").innerHTML = xmlhttp.responseText;
//				alert(xmlhttp.responseText);
				document.getElementById("f_NM_MMBR").value = xmlhttp.responseText;
            }
			if (document.getElementById("f_NM_MMBR").value == "") {
				document.getElementById("SAVE_ADD").setAttribute('disabled', 'disabled');
			} else {
				document.getElementById("SAVE_ADD").removeAttribute('disabled');
			}
        };
//		alert(btn_stat);
        xmlhttp.open("GET","sim_cek_npwp.php?ADD_NPWPD_NO="+pkode_member,true);
        xmlhttp.send();
		
    }
}

function Disp_NP_ADD1() {
   var x = document.getElementById("SelectMember").value;
//   document.getElementById("f_NP_ADD1").value = x;
}

// After form loads focus will go to Address field.  
function firstfocus()  
{  
	var uid = document.trans_simpanan.ADD_NPWPD_NO.focus();  
	return true;  
}  

</script>
