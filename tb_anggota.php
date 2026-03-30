<?php
//	tb_anggota.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$aGOL_DAR = array(1=> 'A ', 'B ', 'AB', 'O');

	$qQUERY=OpenTable('Member', "A.APP_CODE='$cFILTER_CODE' and A.DELETOR=''");

	$cHEADER 		= S_MSG('F039','Tabel Anggota');
	$cKODE			= S_MSG('CB07','Kode Anggota');
	$cNM_ANG 		= S_MSG('CB03','Nama Lengkap');
	$cAL_ANG 		= S_MSG('F005','Alamat');
	$cNO_TELP		= S_MSG('F006','No. Telpon');
	$cNO_KTP		= S_MSG('PA48','No. KTP');
	$cLBL_PRIA		= S_MSG('PD12','Pria');
	$cLBL_WANITA	= S_MSG('PD13','Wanita');
	$cWEB_SITE		= S_MSG('MN13','Web site');
	$cTANDA_TANGAN	= S_MSG('K011','Tanda Tangan');
	$cPEKERJAAN		= S_MSG('PA41','Pekerjaan');
	$cPERUSAHAAN	= S_MSG('PA50','Perusahaan Tempat Bekerja');
	$cDEPARTEMEN	= S_MSG('PA49','Departemen / Bagian');
	$cJABATAN		= S_MSG('PA43','Jabatan');
	$cALMT_KTR		= S_MSG('PA52','Alamat Kantor');

	$cADD_CUST		= S_MSG('CB98','Tambah Anggota');
	$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');

	$cTAB_UMUM		= S_MSG('PD22','Umum');
	$cTAB_DOMISILI	= S_MSG('CB18','Domisili');

	$cSAVE_DATA		= S_MSG('F301','Save');
	$cCLOSE_DATA	= S_MSG('F302','Close');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
	
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$cHELP_BOX		= S_MSG('K101','Help Master Anggota');
		$cHELP_1		= S_MSG('K102','Ini adalah modul untuk memasukkan data Anggota Koperasi yang telah terdaftar');
		$cHELP_2		= S_MSG('NPA3','Untuk memasukkan data Anggota baru, klik tambah Anggota / add new');
		$cHELP_3		= S_MSG('NPA4','Sekarang ini ditampilkan daftar Anggota yang telah pernah dimasukkan');
		$cHELP_4		= S_MSG('NPA5','Untuk merubah salah satu data Anggota, klik di kolom Kode atau Nama Anggota');
?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<link href="croppic/croppic.css" rel="stylesheet" type="text/css"/>
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
									<div class="pull-right">
										<ol class="breadcrumb">
											<li>
												<a href="?_a=<?php echo md5('cr34t3')?>"><i class="fa fa-plus-square"></i><?php echo $cADD_CUST?></a>
											</li>
											<li>
												<a href="#help_tb_anggota" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNM_ANG?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cAL_ANG?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_DISP=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															$cICON = 'fa-male';
															if($aREC_DISP['JK'] == 'W') {
																$cICON = 'fa-female';
															}
//															echo '<td style="width: 1px;"></td>';
															echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?_a=update&TB_MEMBER1_REC_NO=$aREC_DISP[PRSON_CODE]'>".$aREC_DISP['PRSON_CODE']."</a></span></td>";
															echo "<td><span><a href='?_a=update&TB_MEMBER1_REC_NO=$aREC_DISP[PRSON_CODE]'>".$aREC_DISP['PRSON_NAME']."</a></span></td>";
															echo '<td>'.$aREC_DISP['PEOPLE_ADDRESS'].'</td>';
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
			<div class="modal" id="help_tb_anggota" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">

							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_3?></p>
							<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_4?></p>

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
				<div class="project-info">	</div>	
			</div>

			<section id="main-content" class=" ">
				<section class="wrapper main-wrapper" style=''>

					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="page-title">

							<div class="pull-left">
								<h1 class="title"><?php echo S_MSG('CB98','Tambah Anggota')?></h1>                            
							</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<section class="box ">
							<div class="content-body">
								<div class="row" style="margin-right: 0px">
									<form action ="?_a=tambah" method="post"  onSubmit="return CEK_ANGGOTA(this)">

										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE?></label>
										<input type="text" class="col-sm-3 form-label-900" name='KODE_ANGGOTA' id="field-1"><br>
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNM_ANG?></label>
										<input type="text" class="col-sm-6 form-label-900" name='NAMA_ANGGOTA' id="field-2">
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNO_KTP?></label>
										<input type="text" class="col-sm-4 form-label-900" name='NO_KTP' id="field-3">
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-4"><?php echo S_MSG('CB64','Kelompok')?></label>
										<select name="ADD_GROUP" class="col-sm-3 form-label-900">
										<?php 
											$REC_GROUP=OpenTable('TbCustGroup', "APP_CODE='$cFILTER_CODE' and DELETOR=''");
											echo "<option value=' '  > </option>";
											while($aREC_GR_DATA=SYS_FETCH($REC_GROUP)){
												echo "<option value='$aREC_GR_DATA[KODE_GRP]'  >$aREC_GR_DATA[NAMA_GRP]</option>";
											}
										?>
										</select><br><br>

										<label class="col-sm-4 form-label-700" for="field-4"><?php echo S_MSG('CB65','Tipe')?></label>
										<select name="ADD_TIPE" class="col-sm-3 form-label-900">
										<?php 
											$REC_TIPE=OpenTable('TbCustType', "APP_CODE='$cFILTER_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
											echo "<option value=' '  > </option>";
											while($aREC_TP_DATA=SYS_FETCH($REC_TIPE)){
												echo "<option value='$aREC_TP_DATA[KODETIPE]'  >$aREC_TP_DATA[NAMATIPE]</option>";
											}
										?>
										</select><br><br>

<!-- TAB - START of create -->
										<div class="col-sm-12" style="width:100%">
											<h4> </br></h4>
											<ul class="nav nav-tabs primary" style="width:100%">
												 <li class="active">
													  <a href="#umum" data-toggle="tab">
															<i class="fa fa-user"></i> <?php echo $cTAB_UMUM?>
													  </a>
												 </li>
												 <li>
													  <a href="#domisili" data-toggle="tab">
															<i class="fa fa-home"></i> <?php echo $cTAB_DOMISILI?> 
													  </a>
												 </li>
												 <li>
													  <a href="#kontak-0" data-toggle="tab">
															<i class="fa fa-phone"></i> <?php echo S_MSG('CU21','Kontak')?> 
													  </a>
												 </li>
												 <li>
													  <a href="#foto-0" data-toggle="tab">
															<i class="fa fa-cog"></i> <?php echo S_MSG('F017','Foto')?> 
													  </a>
												 </li>
												 <li>
													  <a href="#sign-0" data-toggle="tab">
															<i class="fa fa-pencil-square-o"></i> <?php echo $cTANDA_TANGAN?> 
													  </a>
												 </li>
											</ul>

											<div class="tab-content primary">
												<div class="tab-pane fade in active" id="umum">
													<label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('F018','Jenis')?></label>
													<input type="radio" name="ADD_JKEL" value="P" checked /><?php echo $cLBL_PRIA?>
													<input type="radio" name="ADD_JKEL" value="W" /><?php echo $cLBL_WANITA?><br><br>
													
													<label class="col-sm-4 form-label-700" for="field-3"><?php echo S_MSG('PA05','Tempat Lahir')?></label>
													<input type="text" class="form-label-900" name='ADD_TMP_LAHIR' id="field-3"/><br>

													<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('PA06','Tanggal Lahir')?></label>
													<input type="text" name='ADD_LAHIR' class="form-label-900 datepicker" data-format="yyyy-mm-dd" value="1990-01-01" ><br>

													<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('PH51','Agama')?></label>
													<select name='ADD_AGAMA' class="col-sm-2 form-label-900">
														<?php 
															$REC_DATA=OpenTable('TbReligion', "APP_CODE='$cFILTER_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
															echo "<option value=' '  > </option>";
															while($aREC_AG_DATA=SYS_FETCH($REC_DATA)){
																echo "<option class='form-label-900' value='$aREC_AG_DATA[KODE]'  >$aREC_AG_DATA[NAMA]</option>";
															}
														?>
													</select><br><br>

													<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('PB63','Golongan Darah')?></label>
													<select name='ADD_GOLDAR' class="col-sm-2 form-label-900">
														<?php 
															echo "<option value=' '  > </option>";
															for ($I=1; $I<=4; $I++){
															  echo "<option class='form-label-900' value=$aGOL_DAR[$I]>$aGOL_DAR[$I]</option>";
															}
														?>
													</select><br><br>

													<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('CB14','Pekerjaan')?></label>
													<input type="text" class="form-label-900" name='ADD_GAWIAN' id="field-6"/><br>

													<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('CB16','Pendidikan')?></label>
													<select name='ADD_PEND' class="col-sm-3 form-label-900">
													<?php 
														$qQUERY=OpenTable('TbEducation', "APP_CODE='$cFILTER_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
														echo "<option value=' '  > </option>";
														while($aREC_ED_DATA = SYS_FETCH($qQUERY)){
															echo "<option value='$aREC_ED_DATA[EDU_CODE]'  >$aREC_ED_DATA[EDU_NAME]</option>";
														}
													?>
													</select><br><br>

													<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('CB17','Status Perkawinan')?></label>
													<select name='ADD_STATUS' class="col-sm-3 form-label-900 m-bot15">
													<?php
														$qQUERY=OpenTable('TbStatus', "APP_CODE='$cFILTER_CODE' and DELETOR=''");
														echo "<option value=' '  > </option>";
														while($aREC_KW_DATA=SYS_FETCH($qQUERY)){
															echo "<option value='$aREC_KW_DATA[KODE]'  >$aREC_KW_DATA[NAMA]</option>";
														}
													?>
													</select><br><br>

													<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('CB37','Nama istri/suami')?></label>
													<input type="text" class="col-sm-4 form-label-900" name='ADD_PASANGAN' id="field-6"/><br><br>

													<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('K001','Nama Ibu Kandung')?></label>
													<input type="text" class="col-sm-4 form-label-900" name='ADD_IBU_KDUNG' id="field-6"/><br><br>

												</div>		<!-- End of Tab 1 of create -->
												
												<!-- Tab 2 begin of create -->
												<div class="tab-pane fade" id="domisili">
													<label class="col-sm-4 form-label-700" for="field-21"><?php echo S_MSG('CB81','Propinsi')?></label>
													<select name="ADD_PROV" class="col-sm-4 form-label-900">
													<?php 
														$qQUERY=OpenTable('TbProvince', "APP_CODE='$cFILTER_CODE' and DELETOR=''");
														echo "<option value=' '  > </option>";
														while($aREC_PR_DATA=SYS_FETCH($qQUERY)){
															echo "<option value='$aREC_PR_DATA[id_prov]' >$aREC_PR_DATA[nama]</option>";
														}
													?>
													</select><br><br>

													<label class="col-sm-4 form-label-700" for="field-22"><?php echo S_MSG('CB82','Kabupaten')?></label>
													<select name="ADD_KAB" class="col-sm-4 form-label-900" style="width:300px">
													<?php 
														$qQUERY=OpenTable('TbLocDistrict', "id_prov='$aREC_PR_DATA[id_prov]'");
														echo "<option value=' '  > </option>";
														while($aREC_KAB_DATA=SYS_FETCH($qQUERY)){
															echo "<option value='$aREC_KAB_DATA[id_kab]'  >$aREC_KAB_DATA[nama]</option>";
														}
													?>
													</select><br><br>

													<label class="col-sm-4 form-label-700" for="field-23"><?php echo S_MSG('CB83','Kecamatan')?></label>
													<select name="ADD_KEC" class="col-sm-4 form-label-900">
													<?php 
														$qQUERY=OpenTable('TbLocDistrict', "id_kab='$aREC_KAB_DATA[id_kab]'");
														$qQUERY=mysql_query("select * from kecamatan where id_kab='$aREC_KAB_DATA[id_kab]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
														echo "<option value=' '  > </option>";
														while($aREC_KEC_DATA=mysql_fetch_array($qQUERY)){
															echo "<option value='$aREC_KEC_DATA[id_kec]'  >$aREC_KEC_DATA[nama]</option>";
														}
													?>
													</select><br><br>

													<label class="col-sm-4 form-label-700" for="field-24"><?php echo S_MSG('CB12','Kelurahan')?></label>
													<select name="ADD_KEL" class="col-sm-4 form-label-900">
													<?php 
														$qQUERY=OpenTable('TbLocVillage', "id_sub_district='$aREC_KEC_DATA[id_kec]'");
														$REC_DATA=mysql_query("select * from kelurahan where id_kec='$aREC_KEC_DATA[id_kec]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
														echo "<option value=' '  > </option>";
														while($aREC_KEL_DATA=mysql_fetch_array($REC_DATA)){
															echo "<option value='$aREC_KEL_DATA[id_kel]'  >$aREC_KEL_DATA[nama]</option>";
														}
													?>
													</select><br><br>

													<div class="form-group">
														<label class="col-sm-4 form-label-700" for="field-25"><?php echo $cAL_ANG?></label>
														<div class="controls">
															<textarea name='ADD_ALAMAT' class="form-control autogrow" cols="5" id="field-25"  style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 50px;"></textarea>
														</div>
													</div>

													<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('CB13','RT / RW')?></label>
													<input type="text" class="form-label-900" name='ADD_RT' id="field-6"/><br>

													<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('CB15','RW')?></label>
													<input type="text" class="form-label-900" name='ADD_RW' id="field-6" ><br>

													<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('H650','Kode Pos')?></label>
													<input type="text" class="form-label-900" name='ADD_KD_POS' id="field-6" ><br>
												</div>		<!-- End of Tab 2 -->
												
												<div class="tab-pane fade" id="kontak-0">
													<label class="col-sm-4 form-label-700" for="field-31"><?php echo $cNO_TELP?></label>
													<input type="text" class="form-label-900" name='ADD_NO_TELPON' id="field-31" ><br>
														
													<label class="col-sm-4 form-label-700" for="field-32"><?php echo S_MSG('F007','Nomor HP')?></label>
													<input type="text" class="form-label-900" name='ADD_NO_TEL_HP' id="field-31" ><br>
														
													<label class="col-sm-4 form-label-700" for="field-31"><?php echo S_MSG('F105','Email Address')?></label>
													<input type="text" class="form-label-900" name='ADD_EMAIL' id="field-31" ><br>
														
													<label class="col-sm-4 form-label-700" for="field-32"><?php echo $cWEB_SITE?></label>
													<input type="text" class="form-label-900" name='ADD_WEB_SITE' id="field-31" ><br>
												</div>

												<div class="tab-pane fade" id="foto-0">
												  <div class="form-group">
													<label class="form-label" for="field-1">Profile Image</label>
													<span class="desc"></span>
													<img class="img-responsive" src="data/patients/patient-1.jpg" alt="" style="max-width:220px;">
													<div class="controls">
														 <input type="file" class="form-control" id="field-5">
													</div>
												  </div>
												</div>

												<div class="tab-pane fade" id="sign-0">
													<div class="form-group">
														<label class="form-label" for="field-1"><?php echo $cTANDA_TANGAN?></label>
														<span class="desc"></span>
														<img class="img-responsive" src="data/images/tanda_tangan.jpg" alt="" style="max-width:220px;">
														<div class="controls">
															<input type="file" class="form-control" id="field-5">
														</div>
													</div>
												</div>
											</div></br>

										</div>
<!--  TAB - END -->	
										<div class="text-left">
											<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
											<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
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
		<!-- croppic java script --> 
		<script src="croppic/croppic.js" type="text/javascript"></script> 
		<script src="croppic/croppic.min.js" type="text/javascript"></script> 
		<script src="croppic/jquery.mousewheel.min.js" type="text/javascript"></script> 

		</body>
	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case "update":
		$cQUERY ="select * from tb_member1";
		$cQUERY.=" where tb_member1.APP_CODE='$cFILTER_CODE' and REC_NO=$_GET[TB_MEMBER1_REC_NO] and DELETOR=''";
		$qQUERY =mysql_query($cQUERY);
		if(mysql_num_rows($qQUERY)==0){
			header('location:tb_anggota.php');
		}
		$r_TB_MEMBER1=mysql_fetch_array($qQUERY);
		$cFILE_FOTO_MEMBER = 'data/images_member/FOTO_'.str_pad((string)$r_TB_MEMBER1['REC_NO'], 11, '0', STR_PAD_LEFT).'.jpg';
		if(file_exists($cFILE_FOTO_MEMBER)==0)	{
			$cFILE_FOTO_MEMBER = "data/images/no.jpg";
		}
?>
	<!DOCTYPE html>
	<html class=" ">
		<link href="assets/plugins/select2/select2.css" rel="stylesheet" type="text/css" media="screen"/>
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

					<div class="page-title">

						<div class="pull-left">
							  <h1 class="title"><?php echo S_MSG('CB10','Edit Kode Anggota')?></h1>
						</div>
						<div class="pull-right">									 
							<ol class="breadcrumb">
								<li>
									<?php echo '<a href="?_a=delete&id='. $r_TB_MEMBER1['REC_NO']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
								</li>
							</ol>
						</div>
					</div>
					<div class="clearfix"></div>

						<section class="box ">
							<div class="pull-right hidden-xs"></div>
							<div class="content-body">
								<div class="row" style="margin-right: 0px">
									<form action ="?_a=rubah&id=<?php echo $r_TB_MEMBER1['REC_NO']?>" method="post"  onSubmit="return CEK_ANGGOTA(this)">
										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE?></label>
										<input type="text" class="col-sm-3 form-label-900" name='KD_AGGT' id="field-1" value=<?php echo $r_TB_MEMBER1['KD_MMBR']?> disabled="disabled">
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cNM_ANG?></label>
										<input type="text" class="col-sm-6 form-label-900" name='NM_AGGT' id="field-2" value="<?php echo $r_TB_MEMBER1['NM_DEPAN']?>">
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNO_KTP?></label>
										<input type="text" class="col-sm-4 form-label-900" name='NO_KTP' id="field-3" value=<?php echo $r_TB_MEMBER1['NO_KTP']?>>
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-4"><?php echo S_MSG('CB64','Kelompok')?></label>
										<select name='PILIH_KELOMPOK' class="col-sm-3 form-label-900 m-bot15">
											<?php 
												$qQUERY=mysql_query("select * from grouplgn where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($aREC_GR_DATA=mysql_fetch_array($qQUERY)){
													if($r_TB_MEMBER1['KLPK_ANGGT']==$aREC_GR_DATA['KODE_GRP']){
														echo "<option value='$aREC_GR_DATA[KODE_GRP]' selected='$r_TB_MEMBER1[KLPK_ANGGT]' >$aREC_GR_DATA[NAMA_GRP]</option>";
													} else {	echo "<option value='$aREC_GR_DATA[KODE_GRP]'  >$aREC_GR_DATA[NAMA_GRP]</option>";
													}
												}
											?>
										</select>
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-5"><?php echo S_MSG('CB65','Tipe')?></label>
										<select name='PILIH_TIPE' class="col-sm-3 form-label-900 m-bot15">
											<?php 
												$qQUERY=mysql_query("select * from tipe_otl where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($aREC_TP_DATA=mysql_fetch_array($qQUERY)){
													if($r_TB_MEMBER1['TIPE_ANGGT']==$aREC_TP_DATA['KODETIPE']){
														echo "<option value='$aREC_TP_DATA[KODETIPE]' selected='$r_TB_MEMBER1[TIPE_ANGGT]' >$aREC_TP_DATA[NAMATIPE]</option>";
													} else {	echo "<option value='$aREC_TP_DATA[KODETIPE]'  >$aREC_TP_DATA[NAMATIPE]</option>";
													}
												}
											?>
										</select>
										<div class="clearfix"></div>
<!-- TAB - START of update -->
										<div class="col-sm-12">
											<h4> </br></h4>
											<ul class="nav nav-tabs primary">
												 <li class="active">
													  <a href="#umum-1" data-toggle="tab">
															<i class="fa fa-user"></i> <?php echo $cTAB_UMUM?>
													  </a>
												 </li>
												 <li>
													  <a href="#domisili-1" data-toggle="tab">
															<i class="fa fa-home"></i> <?php echo $cTAB_DOMISILI?> 
													  </a>
												 </li>
												 <li>
													  <a href="#kontak-1" data-toggle="tab">
															<i class="fa fa-phone"></i> <?php echo S_MSG('CU21','Kontak')?> 
													  </a>
												 </li>
												 <li>
													  <a href="#pekerjaan-1" data-toggle="tab">
															<i class="fa fa-phone"></i> <?php echo $cPEKERJAAN?> 
													  </a>
												 </li>
												 <li>
													  <a href="#foto-1" data-toggle="tab">
															<i class="fa fa-cog"></i> <?php echo S_MSG('F017','Foto')?> 
													  </a>
												 </li>
												 <li>
													  <a href="#sign-1" data-toggle="tab">
															<i class="fa fa-pencil-square-o"></i> <?php echo $cTANDA_TANGAN?> 
													  </a>
												 </li>
											</ul>

											<div class="tab-content primary">
												<div class="tab-pane fade in active" id="umum-1">
													<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('F018','Jenis')?></label>
													<input type="radio" name="PILIH_JKEL" value="P" <?php if($r_TB_MEMBER1['JK']=='P') { echo "checked";} echo '/>'.$cLBL_PRIA?>>
													<input type="radio" name="PILIH_JKEL" value="W" <?php if($r_TB_MEMBER1['JK']=='W') { echo "checked";} echo '/>'.$cLBL_WANITA?>>
													<div class="clearfix"></div>
													
													<label class="col-sm-4 form-label-700" for="field-7"><?php echo S_MSG('PA05','Tempat Lahir')?></label>
													<input type="text" class="col-sm-3 form-label-900" name='TMP_LAHIR' id="field-3" value=<?php echo $r_TB_MEMBER1['KOTA_LAHIR']?>>
													<div class="clearfix"></div>

													<label class="col-sm-4 form-label-700" for="field-8"><?php echo S_MSG('PA06','Tanggal Lahir')?></label>
													<input type="text" name='TGL_LAHIR' value="<?php echo $r_TB_MEMBER1['TGL_LAHIR']?>" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="">
													<div class="clearfix"></div>

													<label class="col-sm-4 form-label-700" for="field-9"><?php echo S_MSG('PH51','Agama')?></label>
													<select name='PILIH_AGAMA' class="col-sm-3 form-label-900">
														<?php 
															$REC_DATA=mysql_query("select * from prs_rlgn where APP_CODE='$cFILTER_CODE' and DELETOR=''");
															while($aREC_AG_DATA=mysql_fetch_array($REC_DATA)){
																if($r_TB_MEMBER1['KD_AGAMA'] == $aREC_AG_DATA['RLGN_CODE']){
																	echo "<option value='$aREC_AG_DATA[RLGN_CODE]' selected='$r_TB_MEMBER1[KD_AGAMA]'>$aREC_AG_DATA[RLGN_NAME]</option>";
																} else { 
																	echo "<option class='form-label-900' value='$aREC_AG_DATA[RLGN_CODE]'  >$aREC_AG_DATA[RLGN_NAME]</option>";
																}
															}
														?>
													</select>	<div class="clearfix"></div>

													<label class="col-sm-4 form-label-700" for="field-10"><?php echo S_MSG('PB63','Golongan Darah')?></label>
													<select name='PILIH_GOLDAR' class="col-sm-1 form-label-900">
														<?php 
															for ($I=1; $I<=4; $I++){
																if ($aGOL_DAR[$I] == $r_TB_MEMBER1['GOL_DAR'])
																	echo "<option value=$aGOL_DAR[$I] selected>".$r_TB_MEMBER1[GOL_DAR]."</option>";
																else
																  echo "<option value=$aGOL_DAR[$I]>$aGOL_DAR[$I]</option>";
															}
														?>
													</select>	<div class="clearfix"></div>

													<label class="col-sm-4 form-label-700" for="field-12"><?php echo S_MSG('CB16','Pendidikan')?></label>
													<select name='UPD_KD_PENDIDIKAN' class="col-sm-3 form-label-900">
													<?php 
														$qQUERY=mysql_query("select * from persone where APP_CODE='$cFILTER_CODE' and DELETOR=''");
														while($aREC_ED_DATA = mysql_fetch_array($qQUERY)){
															if($r_TB_MEMBER1['KD_PENDIDIKAN'] == $aREC_ED_DATA['EDU_CODE']){
																echo "<option value='$aREC_ED_DATA[EDU_CODE]' selected='$r_TB_MEMBER1[KD_PENDIDIKAN]'>$aREC_ED_DATA[EDU_NAME]</option>";
															} else { 
																echo "<option value='$aREC_ED_DATA[EDU_CODE]'  >$aREC_ED_DATA[EDU_NAME]</option>";
															}
														}
													?>
													</select>	<div class="clearfix"></div>

													<label class="col-sm-4 form-label-700" for="field-13"><?php echo S_MSG('CB17','Status Perkawinan')?></label>
													<select name='PILIH_STATUS' class="col-sm-3 form-label-900">
													<?php
														$qQUERY=mysql_query("select * from tb_stts_kwn where APP_CODE='$cFILTER_CODE' and DELETOR=''");
														while($aREC_KW_DATA=mysql_fetch_array($qQUERY)){
															if($r_TB_MEMBER1[STATUS_KAWIN] == $aREC_KW_DATA[STTS_ID]){
																echo "<option value='$aREC_KW_DATA[STTS_ID]' selected=$r_TB_MEMBER1[STATUS]>$aREC_KW_DATA[STATUS]</option>";
															} else {	
																echo "<option value='$aREC_KW_DATA[STTS_ID]'  >$aREC_KW_DATA[STATUS]</option>";
															}
														}
													?>
													</select>	<div class="clearfix"></div>

													<label class="col-sm-4 form-label-700" for="field-14"><?php echo S_MSG('CB37','Nama istri/suami')?></label>
													<input type="text" class="col-sm-4 form-label-900" name='NAMA_PASANGAN' id="field-6" value=<?php echo $r_TB_MEMBER1['NAMA_PASANGAN']?>><br>
													<div class="clearfix"></div>

													<label class="col-sm-4 form-label-700" for="field-15"><?php echo S_MSG('K001','Nama Ibu Kandung')?></label>
													<input type="text" class="col-sm-4 form-label-900" name='IBU_KDUNG' id="field-6" value=<?php echo $r_TB_MEMBER1['IBU_KDUNG']?>><br>
													<div class="clearfix"></div>

												</div>		<!-- End of Tab 1 -->
												
												<!-- Tab 2 begin -->
												<div class="tab-pane fade" id="domisili-1">
													<label class="col-sm-4 form-label-700" for="field-21"><?php echo S_MSG('CB81','Propinsi')?></label>
													<select onchange="{loadKabupaten()}" id="PILIH_PROV" name="PILIH_PROV" class="col-sm-4 form-label-900"></select>
													<div class="clearfix"></div>

													<label class="col-sm-4 form-label-700" for="field-22"><?php echo S_MSG('CB82','Kabupaten')?></label>
													<select onchange="{loadKecamatan()}" id="PILIH_KAB" name="PILIH_KAB" class="col-sm-4 form-label-900"></select>
													<div class="clearfix"></div>

													<div class="form-group_5">
														<label class="col-sm-4 form-label-700" for="field-23"><?php echo S_MSG('CB83','Kecamatan')?></label>
														<select onchange="{loadKelurahan()}" id="PILIH_KEC" name="PILIH_KEC" class="col-sm-4 form-label-900"></select>
													</div>			
													<div class="clearfix"></div>

													<div class="form-group_5">
														<label class="col-sm-4 form-label-700" for="field-24"><?php echo S_MSG('CB12','Kelurahan')?></label>
														<select id="PILIH_KEL" name="PILIH_KEL" class="col-sm-4 form-label-900"></select>
													</div>			
													<div class="clearfix"></div>

													<label class="col-sm-4 form-label-700" for="field-25"><?php echo $cAL_ANG?></label>
													<textarea class="col-sm-8 form-label-900 autogrow" name="EDIT_ALAMAT" cols="5" id="field-25" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 50px; width: 337px;" value="<?php echo $r_TB_MEMBER1['ALAMAT']?>"></textarea><br><br>

													<label class="col-sm-4 form-label-700" for="field-26"><?php echo S_MSG('CB13','RT')?></label>
													<input type="text" class="col-sm-1 form-label-900" name='EDIT_RT' id="field-6" value=<?php echo $r_TB_MEMBER1['RT']?>>

													<label class="col-sm-2 form-label-700" for="field-27" style="text-align:right;"><?php echo S_MSG('CB15','RW')?></label>
													<input type="text" class="col-sm-1 form-label-900" name='EDIT_RW' id="field-6" value=<?php echo $r_TB_MEMBER1['RW']?>><br><br>

													<div class="clearfix"></div>
													<label class="col-sm-4 form-label-700" for="field-28"><?php echo S_MSG('H650','Kode Pos')?></label>
													<input type="text" class="col-sm-2 form-label-900" name='EDIT_KD_POS' id="field-6" style="width: 100px" value=<?php echo $r_TB_MEMBER1['KD_POS']?>><br><br>
												</div>		<!-- End of Tab 2 -->
												
												<div class="tab-pane fade" id="kontak-1">
													<label class="col-sm-4 form-label-700" for="field-31"><?php echo $cNO_TELP?></label>
													<input type="text" class="col-sm-4 form-label-900" name='EDIT_NO_TELPON' id="field-31" value=<?php echo $r_TB_MEMBER1['NO_TEL_RMH']?>><br>
													<div class="clearfix"></div>
														
													<label class="col-sm-4 form-label-700" for="field-32"><?php echo S_MSG('F007','Nomor HP')?></label>
													<input type="text" class="col-sm-4 form-label-900" name='EDIT_NO_TEL_HP' id="field-31" value=<?php echo $r_TB_MEMBER1['NO_TEL_HP']?>><br>
													<div class="clearfix"></div>

													<label class="col-sm-4 form-label-700" for="field-31"><?php echo S_MSG('F105','Email Address')?></label>
													<input type="text" class="col-sm-4 form-label-900" name='EDIT_EMAIL' id="field-31" value=<?php echo $r_TB_MEMBER1['EMAIL']?>><br>
													<div class="clearfix"></div>

													<label class="col-sm-4 form-label-700" for="field-32"><?php echo $cWEB_SITE?></label>
													<input type="text" class="col-sm-4 form-label-900" name='EDIT_WEB_SITE' id="field-31" value=<?php echo $r_TB_MEMBER1['WEB_SITE']?>><br>
													<br><br><br><br><br><br><br>
												</div>

												<div class="tab-pane fade" id="pekerjaan">
													<label class="col-sm-4 form-label-700" for="field-11"><?php echo S_MSG('CB14','Pekerjaan')?></label>
													<input type="text" class="form-label-900" name='GAWIAN' id="field-6" value=<?php echo $r_TB_MEMBER1['PEKERJAAN']?>><br>

													<label class="col-sm-4 form-label-700" for="field-31"><?php echo $cPERUSAHAAN?></label>
													<input type="text" class="form-label-900" name='EDIT_OFFICE' id="field-31" value="<?php echo $r_TB_MEMBER1['OFFICE']?>" style="width:50%;"><br>
														
													<label class="col-sm-4 form-label-700" for="field-32"><?php echo $cALMT_KTR?></label>
													<input type="text" class="form-label-900" name='EDIT_ADDRESS_OFFICE' id="field-31" value="<?php echo $r_TB_MEMBER1['ADDRESS_OFFICE']?>" style="width:50%;"><br>
														
													<label class="col-sm-4 form-label-700" for="field-32"><?php echo $cDEPARTEMEN?></label>
													<input type="text" class="form-label-900" name='EDIT_DEPARTEMENT' id="field-31" value="<?php echo $r_TB_MEMBER1['DEPARTEMENT']?>" style="width:50%;"><br>
														
													<label class="col-sm-4 form-label-700" for="field-31"><?php echo $cJABATAN?></label>
													<input type="text" class="form-label-900" name='EDIT_POSITION' id="field-31" value="<?php echo $r_TB_MEMBER1['POSITION']?>" style="width:50%;"><br>
													
													<br><br><br><br><br>
												</div>

												<div class="tab-pane fade" id="foto-1">
<!--													<div class="form-group">
														<label class="form-label" for="field-1"><?php echo S_MSG('F011','Gbr Anggota')?></label>
														<span class="desc"></span>																	
														<img class="img-responsive" src="<?php echo $cFILE_FOTO_MEMBER?>" alt="" style="max-width:220px;">
														<div class="controls">
															 <input type="file" class="form-control" id="field-5">
														</div>
													</div>
 ================== croppic ======================================================================== -->
													<!--div class="col-lg-8">
														<div id="cropContainerModalKTP" class="cropContainerModal" style="width:420px;height:260px;background-color:#999">
															  
														</div>
													</div-->
													<div class="col-md-4 col-sm-12 col-xs-12 docs-preview clearfix">
														<div class="img-preview preview-lg"></div>
													</div>
													<div class="col-md-4 col-sm-12 col-xs-12">
														<a data-toggle="modal" href="#uploadFoto" class="btn btn-primary btn-block">Ganti Foto</a><br>
														<a href="#" class="btn btn-danger btn-block">Hapus Foto</a><br>
													</div>
													<div class="clearfix"></div>
													<br><br><br><br><br><br>
<!-- =================================================================================================== -->
												</div>

												<div class="tab-pane fade" id="sign-1">
												  <div class="form-group">
														<label class="form-label" for="field-41"><?php echo $cTANDA_TANGAN?></label>
														<span class="desc"></span>
														<img class="img-responsive" src="data/images/tanda_tangan.jpg" alt="" style="max-width:220px;">
														<div class="controls">
															 <input type="file" class="form-control" id="field-5">
														</div>
												  </div>
												</div>
											</div></br>

										</div>
<!--  TAB - END -->	
										<div class="text-left">
											<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
											<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
										</div>
									</form>
								</div>
							</div>
						</section>

					</section>
				</section>
				<!-- END CONTENT -->
				<?php	include "scr_chat.php";	include "mdlFotoUser.php";	?>
			</div>
			<?php	require_once("js_framework.php");	?><script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			
			<script src="assets/plugins/image-cropper/js/cropper.js" type="text/javascript"></script>
			<script src="assets/plugins/image-cropper/js/main.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 
			<!-- croppic java script --> 
			<script src="croppic/croppic.js" type="text/javascript"></script> 
			<script src="croppic/croppic.min.js" type="text/javascript"></script> 
			<script src="croppic/jquery.mousewheel.min.js" type="text/javascript"></script>
			<script src="assets/plugins/select2/select2.min.js" type="text/javascript"></script>
			<script type="text/javascript">
			$('#PILIH_PROV').select2();
			$.ajax({
				url: "?_a=propinsi",
				type: 'POST',
				data: {},
				success: function (data) {
					$("#PILIH_PROV").html(data);
					$("#PILIH_PROV").select2().select2('val', "<?php echo $r_TB_MEMBER1['KD_PROV'] ?>");
					loadKabupaten();
				},
				error: function (jqXHR, status, err) {
					alert(err);
				}
			});
			$('#PILIH_KAB').select2();
			function loadKabupaten() {
				$.ajax({
					url: "?_a=kabupaten",
					type: 'POST',
					data: {PILIH_PROV: $('#PILIH_PROV').val()},
					success: function (data) {
						$("#PILIH_KAB").html(data);
						$("#PILIH_KAB").select2().select2('val', '<?php echo $r_TB_MEMBER1["KD_KAB"] ?>');
						loadKecamatan();
					},
					error: function (jqXHR, status, err) {
						alert(err);
					}
				});
			}
			$('#PILIH_KEC').select2();
			function loadKecamatan() {
				$.ajax({
					url: "?_a=kecamatan",
					type: 'POST',
					data: {PILIH_KAB: $('#PILIH_KAB').val()},
					success: function (data) {
						$('#PILIH_KEC').html(data);
						$("#PILIH_KEC").select2().select2('val', '<?php echo $r_TB_MEMBER1["KD_KEC"] ?>');
						loadKelurahan();
					},
					error: function (jqXHR, status, err) {
						alert(err);
					}
				});
			}
			$('#PILIH_KEL').select2();
			function loadKelurahan() {
				$.ajax({
					url: "?_a=kelurahan",
					type: 'POST',
					data: {PILIH_KEC: $('#PILIH_KEC').val()},
					success: function (data) {
						$('#PILIH_KEL').html(data);
						$("#PILIH_KEL").select2().select2('val', '<?php echo $r_TB_MEMBER1["KD_KEL"] ?>');
					},
					error: function (jqXHR, status, err) {
						alert(err);
					}
				});
			}
			</script>
			
		</body>
	</html>
<?php
	break;
case 'propinsi':
	$q_PROVINSI = SYS_QUERY("SELECT ' ' as id_prov,'--PILIH PROVINSI--' as nama UNION ALL SELECT id_prov,nama FROM provinsi");
	while($ProvDT=SYS_FETCH($q_PROVINSI)) {
		echo "<option value='$ProvDT[id_prov]'  >$ProvDT[nama]</option>";
	}
	break;
case 'kabupaten':
	$cKD_PROV = $_POST['PILIH_PROV'];
	$q_KABUPATEN = SYS_QUERY("SELECT ' ' as id_kab,'--PILIH KABUPATEN--' as name UNION ALL SELECT id_kab,nama FROM kabupaten WHERE id_prov='$cKD_PROV'");
	while($a_KABUPATEN=SYS_FETCH($q_KABUPATEN)) {
		echo "<option value='$a_KABUPATEN[id_kab]'  >$a_KABUPATEN[name]</option>";
	}
	break;
case 'kecamatan':
	$kab = $_POST['PILIH_KAB'];
	$cKec = SYS_QUERY("SELECT '' as id_kec,'--PILIH KECAMATAN--' as name UNION ALL SELECT id_kec,nama FROM kecamatan WHERE id_kab='$kab'");
	while($KecDT=SYS_FETCH($cKec)) {
		echo "<option value='".$KecDT['id_kec']."'>".$KecDT['name']."</option>";
	}; break;
case 'kelurahan':
	$kec = $_POST['PILIH_KEC'];
	$cKel = SYS_QUERY("SELECT '' as id_kel,'--PILIH KELURAHAN--' as name UNION ALL SELECT id_kel,nama FROM kelurahan WHERE id_kec='$kec'");
	while($KelDT=SYS_FETCH($cKel)) {
		echo "<option value='".$KelDT['id_kel']."'>".$KelDT['name']."</option>";
	}; break;

case 'tambah':
	$cKODE_ANGG		=encode_string($_POST['KODE_ANGGOTA']);
	$pNAMA_DEPN		=encode_string($_POST['NAMA_ANGGOTA']);
	$cNOMOR_KTP		=encode_string($_POST['NO_KTP']);
	$cKOTA_LAHIR	=encode_string($_POST['ADD_TMP_LAHIR']);
	$cPASANGAN		=encode_string($_POST['ADD_PASANGAN']);
	$cPEKERJAAN		=encode_string($_POST['ADD_GAWIAN']);
	$cIBU_KANDUNG	=encode_string($_POST['ADD_IBU_KDUNG']);
	if($pKODE_ANGG=='') {
		$cMSG = S_MSG('CB91','Kode Anggota tidak boleh kosong');
		echo "<script> alert('$cMSG');		window.history.back();		</script>	";
		return;

	}
	$cQUERY="select * from tb_member1 where APP_CODE='$cFILTER_CODE' and KD_MMBR='$cKODE_ANGG' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(mysql_num_rows($cCEK_KODE)==0){
		$cQUERY ="insert into tb_member1 set APP_CODE='$cFILTER_CODE', KD_MMBR='$cKODE_ANGG', NM_DEPAN='$pNAMA_DEPN', ";
		$cQUERY.=" NO_KTP='$cNOMOR_KTP', KLPK_ANGGT='$_POST[ADD_GROUP]', TIPE_ANGGT='$_POST[ADD_TIPE]', ";
		$cQUERY.=" JK='$_POST[ADD_JKEL]', KOTA_LAHIR='$_POST[ADD_LAHIR]', TGL_LAHIR='$_POST[ADD_LAHIR]', ";
		$cQUERY.=" KD_AGAMA='$_POST[ADD_AGAMA]', GOL_DAR='$_POST[ADD_GOLDAR]', KD_PENDIDIKAN='$_POST[ADD_PEND]', ";
		$cQUERY.=" STATUS_KAWIN='$_POST[ADD_STATUS]', NAMA_PASANGAN='$cPASANGAN', PEKERJAAN='$cPEKERJAAN', ";
		$cQUERY.=" IBU_KDUNG='$cIBU_KANDUNG', ";
		$cQUERY.="KD_PROV='$_POST[ADD_PROV]', KD_KAB='$_POST[ADD_KAB]', KD_KEC='$_POST[ADD_KEC]', KD_KEL='$_POST[ADD_KEL]', ";
		$cQUERY.="ALAMAT='$_POST[ADD_ALAMAT]', RT='$_POST[ADD_RT]', RW='$_POST[ADD_RW]', KD_POS='$_POST[ADD_KD_POS]', ";
		$cQUERY.="NO_TEL_RMH='$_POST[ADD_NO_TELPON]', NO_TEL_HP='$_POST[ADD_NO_TEL_HP]', EMAIL='$_POST[ADD_EMAIL]', WEB_SITE='$_POST[ADD_WEB_SITE]', ";
		$cQUERY.=" ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
//	echo $cQUERY;
//	exit();
		SYS_QUERY($cQUERY);
		header('location:tb_anggota.php');
	} else {
		$cMSG = S_MSG('CB92','Kode Anggota sudah ada');
		echo "<script> alert('$cMSG');		window.history.back();		</script>	";
		return;
	}
	break;

case 'rubah':
/*
  $lokasi_file    = $_FILES['fupload']['tmp_name'];
  $tipe_file      = $_FILES['fupload']['type'];
  $nama_file      = $_FILES['fupload']['name'];
//  $acak           = rand(000000,999999);
// $nama_file_unik = $acak.$nama_file;
  $nama_file_unik = $nama_file;

	
//	if (!empty($lokasi_file)){  
		Uploadfoto($nama_file_unik);
		$cQUERY=$cQUERY.", FOTO='$nama_file_unik'";
//	}
	$cQUERY=$cQUERY." where userid='$_POST[id]'";
	
	mysql_query($cQUERY);
	header('location:media.php?module=user&act=default&id='.$_POST['id']);
*/
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cKD_PEN = $_POST['UPD_KD_PENDIDIKAN'];
	$cQUERY ="update tb_member1 set NM_DEPAN='$_POST[NM_AGGT]', NO_KTP='$_POST[NO_KTP]', ";
	$cQUERY.="KLPK_ANGGT='$_POST[PILIH_KELOMPOK]', TIPE_ANGGT='$_POST[PILIH_TIPE]', ";
	$cQUERY.="JK='$_POST[PILIH_JKEL]', KOTA_LAHIR='$_POST[TMP_LAHIR]', ";
	$cQUERY.="TGL_LAHIR='$_POST[TGL_LAHIR]', KD_AGAMA='$_POST[PILIH_AGAMA]', ";
	$cQUERY.="PEKERJAAN='$_POST[GAWIAN]', GOL_DAR='$_POST[PILIH_GOLDAR]', ";
	$cQUERY.="KD_PENDIDIKAN='$cKD_PEN', STATUS_KAWIN='$_POST[PILIH_STATUS]', ";
	$cQUERY.="NAMA_PASANGAN='$_POST[NAMA_PASANGAN]', IBU_KDUNG='$_POST[IBU_KDUNG]', ";
	$cQUERY.="KD_PROV='$_POST[PILIH_PROV]', KD_KAB='$_POST[PILIH_KAB]', KD_KEC='$_POST[PILIH_KEC]', KD_KEL='$_POST[PILIH_KEL]', ";
	$cQUERY.="ALAMAT='$_POST[EDIT_ALAMAT]', RT='$_POST[EDIT_RT]', RW='$_POST[EDIT_RW]', KD_POS='$_POST[EDIT_KD_POS]', ";
	$cQUERY.="NO_TEL_RMH='$_POST[EDIT_NO_TELPON]', NO_TEL_HP='$_POST[EDIT_NO_TEL_HP]', EMAIL='$_POST[EDIT_EMAIL]', WEB_SITE='$_POST[EDIT_WEB_SITE]', ";
	$cQUERY.="OFFICE='$_POST[EDIT_OFFICE]', ADDRESS_OFFICE='$_POST[EDIT_ADDRESS_OFFICE]', DEPARTEMENT='$_POST[EDIT_DEPARTEMENT]', POSITION='$_POST[EDIT_POSITION]', ";
//	$cQUERY.="FOTO='$nama_file_unik', ";
	$cQUERY.="UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW' ";
	$cQUERY.="where APP_CODE='$cFILTER_CODE' and REC_NO=$KODE_CRUD";
//	die ($cQUERY);
	SYS_QUERY($cQUERY);
	header('location:tb_anggota.php');
	break;

case 'delete':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cQUERY ="update tb_member1 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW'";
	$cQUERY.="where APP_CODE='$cFILTER_CODE' and REC_NO=$KODE_CRUD";
	SYS_QUERY($cQUERY);
	header('location:tb_anggota.php');
}
?>