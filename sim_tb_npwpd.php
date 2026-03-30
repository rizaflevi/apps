<?php
//	sim_tb_npwpd.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$nJNS_PAJAK = 0;
	$q_KLP_PND=SYS_QUERY("select APP_CODE, DELETOR from klp_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nJNS_PAJAK = SYS_ROWS($q_KLP_PND);
	
	$nGROUP_USAHA = 0;
	$q_GRUP_USH=SYS_QUERY("select APP_CODE, DELETOR from grup_ush where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nGROUP_USAHA = SYS_ROWS($q_GRUP_USH);
	
	$nJENIS_USAHA = 0;
	$q_JNS_USH=SYS_QUERY("select APP_CODE, DELETOR from jns_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nJENIS_USAHA = SYS_ROWS($q_JNS_USH);
	
/*	$cREC_SYS=SYS_FETCH(SYS_QUERY("select * from rainbow where KEY_FIELD='JNS_PRSHN' and APP_CODE='$cFILTER_CODE'"));
	$ada_DIST=0;
	if(substr($cREC_SYS['KEY_CONTEN'],0,1)!='') {
		$ada_DIST=1;
	}
*/
	
	$q_NPWPD=SYS_QUERY("select * from npwpd where APP_CODE='$cFILTER_CODE' and DELETOR=''");

	$cHEADER 		= S_MSG('SA01','Data NPWPD');
	$cNO_NPWPD		= S_MSG('SM51','NPWPD/RD');
	$cNO_DAFTAR		= S_MSG('SA03','Nomor Pendaftaran');
	$cNAMA_USAHA 	= S_MSG('SA04','Nama Usaha');
	$cALAMAT1 		= S_MSG('SA05','Alamat');
	$cALAMAT2		= S_MSG('NL54','Kota');
	$cKECAMATAN		= S_MSG('SA06','Kecamatan');
	$cKELURAHAN		= S_MSG('SA07','Kelurahan');
	$cNO_TELP		= S_MSG('SA08','No. Telpon');
	$cNO_REK		= S_MSG('F030','Kode Account');
	$cPERUSAHAAN	= S_MSG('PA50','Perusahaan Tempat Bekerja');
	$cDEPARTEMEN	= S_MSG('PA49','Departemen / Bagian');
	$cNAMA_PEMILIK	= S_MSG('SA21','Nama Pemilik');
	$cALMT_PEMILIK	= S_MSG('SN97','Alamat Pemilik');
	$cKEC_PEMILIK	= S_MSG('SN92','Kecamatan');
	$cKEL_PEMILIK	= S_MSG('SN93','Kelurahan');
	$cTELP_PEMILIK	= S_MSG('SN94','No. Telpon');
	$cKABUPATEN		= S_MSG('SA09','Kabupaten/Kota');
	
	$cDATA_UMUM		= S_MSG('SK20','Data Detil');
	$cPAJAK_RET		= S_MSG('SK21','Pajak / Retribusi');
	$cGRUP_USH		= S_MSG('SK22','Grup Usaha');
	$cJNS_PNDPTN	= S_MSG('SK23','Jenis Pendapatan');
	$cDETIL			= S_MSG('SA20','Data Pemilik');
	
	$cTIPE_CUST		= S_MSG('F008','Tipe');
	$cDATA_LAIN		= S_MSG('SK30','Data Lainnya');
	$cNMR_KUKUH		= S_MSG('SK28','Nmr. Pengukuhan');
	$cTGL_KUKUH		= S_MSG('SK27','Tgl. Pengukuhan');
	$cTGL_KARTU		= S_MSG('SK24','Tgl. Kartu');
	$cTGL_KIRIM		= S_MSG('SK25','Tgl. Dikirim');
	
	$cADD_REC		= S_MSG('SA80','Tambah Master Npwpd');
	$cEDIT_TBL		= S_MSG('SA81','Edit Master Npwpd');
	$cDAFTAR		= S_MSG('SA82','Daftar Npwpd');
	
	$cSAVE_DATA		= S_MSG('F301','Save');
	$cCLOSE_DATA	= S_MSG('F302','Close');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
switch($cACTION){
	default:
	$ADD_LOG	= APP_LOG_ADD();
	$cHELP_BOX	= S_MSG('SA91','Help Data NPWPD');
	$cHELP_1	= S_MSG('SA92','Ini adalah modul untuk memasukkan data Master NPWPD');
	$cHELP_2	= S_MSG('SA93','Untuk memasukkan data Master NPWPD baru, klik tambah Master NPWPD');
	$cHELP_3	= S_MSG('SA94','Untuk melihat atau merubah data Master NPWPD, klik di kode atau nama Master NPWPD yang akan di rubah');
	$cHELP_4	= S_MSG('SA95','Arahkan mouse pointer ke baris data Master NPWPD yang akan dirubah, ketika ditampilkan garis bawah pada data nya, klik untuk merubah atau melihat detil nya');
	$cHELP_5	= S_MSG('SA96','Data NPWPD ini memuat semua Nomor Pokok Wajib Pajak, termasuk Wajib Retribusi yang terdaftar di Dinas Pendapatan Daerah Kabupaten / Kota setempat.');
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
									<div class="pull-right">
										<ol class="breadcrumb">
											<li>
												<a href="?_a=<?php echo md5('CREATE_CUST')?>"><i class="fa fa-plus-square"></i><?php echo $cADD_REC?></a>
											</li>
											<li>
												<a href="#help_tbl_penpwpd" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="display table table-hover table-condensed" cellspacing="0">
												<thead>
													<tr>
														<th style="background-color:LightGray;width: 1px;"></th>
														<th style="background-color:LightGray;"><?php echo $cNO_NPWPD?></th>
														<th style="background-color:LightGray;"><?php echo $cNAMA_USAHA?></th>
														<th style="background-color:LightGray;"><?php echo $cNAMA_PEMILIK?></th>
														<th style="background-color:LightGray;"><?php echo $cALAMAT1?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($a_NPWPD=SYS_FETCH($q_NPWPD)) {
														echo '<tr>';
															$cICON = 'fa-male';
															if($a_NPWPD['NON_ACTIVE'] == 1) {
																$cICON = 'fa-circle-o';
															}
//															echo '<td style="width: 1px;"></td>';
															echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?_a=".md5('UPD_NPWPD')."&_c=".md5($a_NPWPD['NPWPD_NO'])."'>".decode_string($a_NPWPD['NPWPD_NO'])."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('UPD_NPWPD')."&_c=".md5($a_NPWPD['NPWPD_NO'])."'>".decode_string($a_NPWPD['NPWPD_NAME'])."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('UPD_NPWPD')."&_c=".md5($a_NPWPD['NPWPD_NO'])."'>".decode_string($a_NPWPD['NPWPD_OWN'])."</a></span></td>";
															echo '<td>'.decode_string($a_NPWPD['NP_ADD1']).'</td>';
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
			<div class="modal" id="help_tbl_penpwpd" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_3?></p>	<p><?php echo $cHELP_4?></p>	<p><?php echo $cHELP_5?></p>
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

case md5('CREATE_CUST'):
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
						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">
								<div class="pull-left">
									<h2 class="title"><?php echo $cADD_REC?></h2>                            
								</div>
							</div>
						</div>	<div class="clearfix"></div>

						<section class="box ">
							<div class="content-body">
								<div class="row" style="margin-right: 0px">
									<form action ="?_a=tambah" method="post">
										<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNO_NPWPD?></label>
											<input type="text" class="col-sm-3 form-label-900" name='ADD_NPWPD_NO' id="field-1"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-32"><?php echo $cNO_DAFTAR?></label>
											<input type="text" class="col-sm-4 form-label-900" name='ADD_NO_DAFTAR' id="field-31"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-2"><?php echo $cNAMA_USAHA?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_NPWPD_NAME' id="field-2">
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cALAMAT1?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_NP_ADD1' id="field-6"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-3"></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_NP_ADD2' id="field-6"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-3"></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_NP_ADD3' id="field-6"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKECAMATAN?></label>
											<select name="ADD_CAMAT_CODE" class="col-sm-4 form-label-900">
											<?php 
												echo "<option value=' '  > </option>";
												$q_KCAMATAN=SYS_QUERY("select * from kcamatan where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($r_KCAMATAN=SYS_FETCH($q_KCAMATAN)){
													echo "<option value='$r_KCAMATAN[CAMAT_CODE]'  >$r_KCAMATAN[CAMAT_NAME]</option>";
												}
											?>
											</select>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKELURAHAN?></label>
											<select name="ADD_LURAH_CODE" class="col-sm-4 form-label-900">
											<?php 
												echo "<option value=' '  > </option>";
												$q_KELURAHAN=SYS_QUERY("select * from kelurahan where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($r_KELURAHAN=SYS_FETCH($q_KELURAHAN)){
													echo "<option value='$r_KELURAHAN[LURAH_CODE]'  >$r_KELURAHAN[LURAH_NAME]</option>";
												}
											?>
											</select>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cNO_TELP?></label>
											<input type="text" class="col-sm-4 form-label-900" name='ADD_NO_TELPON' id="field-31"><br>
											<div class="clearfix"></div>
												
											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKELURAHAN?></label>
											<select name="ADD_KABK_CODE" class="col-sm-4 form-label-900">
											<?php 
												echo "<option value=' '  > </option>";
												$q_KAB_KOTA=SYS_QUERY("select * from kab_kota where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($r_KAB_KOTA=SYS_FETCH($q_KAB_KOTA)){
													echo "<option value='$r_KAB_KOTA[KABK_CODE]'  >$r_KAB_KOTA[KABK_NAME]</option>";
												}
											?>
											</select>
											<div class="clearfix"></div>

	<!-- TAB - START of update -->
											<div class="col-sm-12">
												<h4> </br></h4>
												<ul class="nav nav-tabs primary">
													 <li class="active">
														<a href="#TAB_UMUM" data-toggle="tab">
															<i class="fa fa-user"></i> <?php echo $cDATA_UMUM?>
														</a>
													 </li>
													 <li>
														<a href="#TAB_DETAIL" data-toggle="tab">
															<i class="fa fa-home"></i> <?php echo $cDETIL?> 
														</a>
													 </li>
													 <li>
														<a href="#TAB_FOTO" data-toggle="tab">
															<i class="fa fa-cog"></i> <?php echo $cDATA_LAIN?> 
														</a>
													 </li>
												</ul>

												<div class="tab-content primary">
													<div class="tab-pane fade in active" id="TAB_UMUM">
														<?php 
															if($nJNS_PAJAK > 0) {
																echo '<label class="col-sm-3 form-label-700" for="field-4">'. $cPAJAK_RET. '</label>';
																	echo '<select name="ADD_KLP_PND" class="col-sm-5 form-label-900">';
																		$q_KLP_PND=SYS_QUERY("select * from klp_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
																		while($r_KLP_PND=SYS_FETCH($q_KLP_PND)){
																			echo "<option value='$r_KLP_PND[KPND_CODE]'  >$r_KLP_PND[KPND_DESC]</option>";
																		}
																	echo '</select>';
															} else {
																echo '<br><br>';
															}
														?>	<div class="clearfix"></div>

														<?php 
															if($nGROUP_USAHA > 0) {
																echo '<label class="col-sm-3 form-label-700" for="field-4">'. $cGRUP_USH. '</label>';
																	echo '<select name="ADD_GRP_USAHA" class="col-sm-5 form-label-900">';
																		$q_GRUP_USH=SYS_QUERY("select * from grup_ush where APP_CODE='$cFILTER_CODE' and DELETOR=''");
																		while($r_GRUP_USH=SYS_FETCH($q_GRUP_USH)){
																			echo "<option value='$r_GRUP_USH[GRUP_CODE]'  >$r_GRUP_USH[GRUP_NAME]</option>";
																		}
																	echo '</select>';
															} else {
																echo '<br><br>';
															}
														?>	<div class="clearfix"></div>

														<?php 
															if($nJENIS_USAHA > 0) {
																echo '<label class="col-sm-3 form-label-700" for="field-5">'.$cJNS_PNDPTN.'</label>
																	<select name="ADD_PND_CODE" class="col-sm-5 form-label-900">';
																$q_JNS_USH=SYS_QUERY("select * from jns_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
																while($r_JNS_PND=SYS_FETCH($q_JNS_USH)){
																	echo "<option value='$r_JNS_PND[PND_CODE]'  >$r_JNS_PND[PND_DESC]</option>";
																}
															}
														?>
														</select>	<br><br><br>	<div class="clearfix"></div>
													</div>
													
													<div class="tab-pane fade" id="TAB_DETAIL">	<!-- Tab 2 begin -->
														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cNAMA_PEMILIK?></label>
														<input type="text" class="col-sm-6 form-label-900" name='ADD_NPWPD_OWN' id="field-31"><br>
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cALMT_PEMILIK?></label>
														<input type="text" class="col-sm-6 form-label-900" name='ADD_OWN_ADD1' id="field-31"><br>
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cKEC_PEMILIK?></label>
														<input type="text" class="col-sm-6 form-label-900" name='ADD_CAMAT_OWNR' id="field-31"><br>
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cKEL_PEMILIK?></label>
														<input type="text" class="col-sm-6 form-label-900" name='ADD_LURAH_OWNR' id="field-31"><br>
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cTELP_PEMILIK?></label>
														<input type="text" class="col-sm-6 form-label-900" name='ADD_TELP_OWNR' id="field-31"><br>
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cKABUPATEN?></label>
														<input type="text" class="col-sm-6 form-label-900" name='ADD_KABK_OWNR' id="field-31"><br>
														<div class="clearfix"></div>

													</div>		<!-- End of Tab 2 -->
													
													<div class="tab-pane fade" id="TAB_FOTO">
														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cNMR_KUKUH?></label>
														<input type="text" class="col-sm-6 form-label-900" name='ADD_NMR_KUKUH' id="field-31"><br>
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cTGL_KUKUH?></label>
														<input type="text" class="col-sm-3 form-label-900 datepicker" data-format="dd-mm-yyyy" data-mask="date" name='ADD_TGL_KUKUH' value="<?php echo date("d-m-Y")?>">
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cTGL_KARTU?></label>
														<input type="text" class="col-sm-3 form-label-900 datepicker" data-format="dd-mm-yyyy" data-mask="date" name='ADD_TGL_KARTU' value="<?php echo date("d-m-Y")?>">
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cTGL_KIRIM?></label>
														<input type="text" class="col-sm-3 form-label-900 datepicker" data-format="dd-mm-yyyy" data-mask="date" name='ADD_TGL_KIRIM' value="<?php echo date("d-m-Y")?>">
														<div class="clearfix"></div>
													</div>

												</div></br>

											</div>
											<div class="clearfix"></div>
	<!--  TAB - END -->	
											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
												<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
											</div>
										</div>
									</form>
								</div>
							</div>
						</section>

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
	SYS_DB_CLOSE($DB2);
	break;

case md5('UPD_NPWPD'):
		$c_NPWPD ="select * from npwpd where APP_CODE='$cFILTER_CODE' and md5(NPWPD_NO)='$_GET[_c]' and DELETOR=''";
		$q_NPWPD =SYS_QUERY($c_NPWPD);
		if(SYS_ROWS($q_NPWPD)==0){
			header('location:tb_customer.php');
		}
		$a_NPWPD=SYS_FETCH($q_NPWPD);
		$cFILE_FOTO_WP = 'data/images_member/WP_'. $a_NPWPD['NPWPD_NO'] .'.jpg';
		if(file_exists($cFILE_FOTO_WP)==0)	{
			$cFILE_FOTO_WP = "data/images/no.jpg";
		}
		$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
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

						<div class="page-title">
							<div class="pull-left">
								  <h2 class="title"><?php echo $cEDIT_TBL?></h2>
							</div>
							<div class="pull-right">									 
								<ol class="breadcrumb">
									<li>
										<?php echo '<a href="?_a='.md5('del_cust').'&id='. md5($a_NPWPD['NPWPD_NO']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
									</li>
								</ol>
							</div>
						</div>
						<div class="clearfix"></div>

						<section class="box ">
							<div class="pull-right hidden-xs"></div>
							<div class="content-body">
								<div class="row">
									<form action ="?_a=rubah&id=<?php echo $a_NPWPD['NPWPD_NO']?>" method="post"  onSubmit="return CEK_ANGGOTA(this)">
										<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNO_NPWPD?></label>
											<input type="text" class="col-sm-3 form-label-900" name='UPD_NPWPD_NO' id="field-1" value=<?php echo $a_NPWPD['NPWPD_NO']?> disabled="disabled"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-32"><?php echo $cNO_DAFTAR?></label>
											<input type="text" class="col-sm-4 form-label-900" name='UPD_NO_DAFTAR' id="field-31" value=<?php echo $a_NPWPD['NO_DAFTAR']?>><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-2"><?php echo $cNAMA_USAHA?></label>
											<input type="text" class="col-sm-6 form-label-900" name='UPD_NPWPD_NAME' id="field-2" value="<?php echo decode_string($a_NPWPD['NPWPD_NAME'])?>">
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cALAMAT1?></label>
											<input type="text" class="col-sm-6 form-label-900" name='UPD_NP_ADD1' id="field-6" value="<?php echo decode_string($a_NPWPD['NP_ADD1'])?>"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-3"></label>
											<input type="text" class="col-sm-6 form-label-900" name='UPD_NP_ADD2' id="field-6" value="<?php echo decode_string($a_NPWPD['NP_ADD2'])?>"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-3"></label>
											<input type="text" class="col-sm-6 form-label-900" name='UPD_NP_ADD3' id="field-6" value="<?php echo decode_string($a_NPWPD['NP_ADD3'])?>"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKECAMATAN?></label>
											<select name="UPD_CAMAT_CODE" class="col-sm-4 form-label-900">
											<?php 
												echo "<option value=' '  > </option>";
												$q_KCAMATAN=SYS_QUERY("select * from kcamatan where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($r_KCAMATAN=SYS_FETCH($q_KCAMATAN)){
													if($a_NPWPD['CAMAT_CODE'] == $r_KCAMATAN['CAMAT_CODE']){
														echo "<option value='$r_KCAMATAN[CAMAT_CODE]' selected='$a_NPWPD[CAMAT_CODE]' >$r_KCAMATAN[CAMAT_NAME]</option>";
													} else {
													echo "<option value='$r_KCAMATAN[CAMAT_CODE]'  >$r_KCAMATAN[CAMAT_NAME]</option>"; }
												}
											?>
											</select>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKELURAHAN?></label>
											<select name="UPD_LURAH_CODE" class="col-sm-4 form-label-900">
											<?php 
												echo "<option value=' '  > </option>";
												$q_KELURAHAN=SYS_QUERY("select * from kelurahan where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($r_KELURAHAN=SYS_FETCH($q_KELURAHAN)){
													if($a_NPWPD['LURAH_CODE'] == $r_KELURAHAN['LURAH_CODE']){
														echo "<option value='$r_KELURAHAN[LURAH_CODE]' selected='$a_NPWPD[LURAH_CODE]' >$r_KELURAHAN[LURAH_NAME]</option>";
													} else {
													echo "<option value='$r_KELURAHAN[LURAH_CODE]'  >$r_KELURAHAN[LURAH_NAME]</option>"; }
												}
											?>
											</select>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cNO_TELP?></label>
											<input type="text" class="col-sm-4 form-label-900" name='EDIT_NO_TELPON' id="field-31" value=<?php echo decode_string($a_NPWPD['NO_TELP'])?>><br>
											<div class="clearfix"></div>
												
											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKELURAHAN?></label>
											<select name="UPD_KABK_CODE" class="col-sm-4 form-label-900">
											<?php 
												echo "<option value=' '  > </option>";
												$q_KAB_KOTA=SYS_QUERY("select * from kab_kota where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($r_KAB_KOTA=SYS_FETCH($q_KAB_KOTA)){
													if($a_NPWPD['KABK_CODE'] == $r_KAB_KOTA['KABK_CODE']){
														echo "<option value='$r_KAB_KOTA[KABK_CODE]' selected='$a_NPWPD[KABK_CODE]' >$r_KAB_KOTA[KABK_NAME]</option>";
													} else {
													echo "<option value='$r_KAB_KOTA[KABK_CODE]'  >$r_KAB_KOTA[KABK_NAME]</option>"; }
												}
											?>
											</select>
											<div class="clearfix"></div>

	<!-- TAB - START of update -->
											<div class="col-sm-12">
												<h4> </br></h4>
												<ul class="nav nav-tabs primary">
													 <li class="active">
														<a href="#TAB_UMUM" data-toggle="tab">
															<i class="fa fa-user"></i> <?php echo $cDATA_UMUM?>
														</a>
													 </li>
													 <li>
														<a href="#TAB_DETAIL" data-toggle="tab">
															<i class="fa fa-home"></i> <?php echo $cDETIL?> 
														</a>
													 </li>
													 <li>
														<a href="#TAB_FOTO" data-toggle="tab">
															<i class="fa fa-cog"></i> <?php echo $cDATA_LAIN?> 
														</a>
													 </li>
												</ul>

												<div class="tab-content primary">
													<div class="tab-pane fade in active" id="TAB_UMUM">
														<?php 
															if($nJNS_PAJAK > 0) {
																echo '<label class="col-sm-3 form-label-700" for="field-4">'. $cPAJAK_RET. '</label>';
																	echo '<select name="UPD_KLP_PND" class="col-sm-5 form-label-900">';
																		$q_KLP_PND=SYS_QUERY("select * from klp_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
																		while($r_KLP_PND=SYS_FETCH($q_KLP_PND)){
																			if($a_NPWPD['KLP_PND']==$r_KLP_PND['KPND_CODE']){
																				echo "<option value='$r_KLP_PND[KPND_CODE]' selected='$a_NPWPD[KLP_PND]' >$r_KLP_PND[KPND_DESC]</option>";
																			} else {
																				echo "<option value='$r_KLP_PND[KPND_CODE]'  >$r_KLP_PND[KPND_DESC]</option>";
																			}
																		}
																	echo '</select>';
															} else {
																echo '<br><br>';
															}
														?>	<div class="clearfix"></div>

														<?php 
															if($nGROUP_USAHA > 0) {
																echo '<label class="col-sm-3 form-label-700" for="field-4">'. $cGRUP_USH. '</label>';
																	echo '<select name="UPD_GRP_USAHA" class="col-sm-5 form-label-900">';
																		$q_GRUP_USH=SYS_QUERY("select * from grup_ush where APP_CODE='$cFILTER_CODE' and DELETOR=''");
																		while($r_GRUP_USH=SYS_FETCH($q_GRUP_USH)){
																			if($a_NPWPD['GRP_USAHA']==$r_GRUP_USH['GRUP_CODE']){
																				echo "<option value='$r_GRUP_USH[GRUP_CODE]' selected='$a_NPWPD[GRP_USAHA]' >$r_GRUP_USH[GRUP_NAME]</option>";
																			} else {
																				echo "<option value='$r_GRUP_USH[GRUP_CODE]'  >$r_GRUP_USH[GRUP_NAME]</option>";
																			}
																		}
																	echo '</select>';
															} else {
																echo '<br><br>';
															}
														?>	<div class="clearfix"></div>

														<?php 
															if($nJENIS_USAHA > 0) {
																echo '<label class="col-sm-3 form-label-700" for="field-5">'.$cJNS_PNDPTN.'</label>
																	<select name="UPD_PND_CODE" class="col-sm-5 form-label-900">';
																$q_JNS_USH=SYS_QUERY("select * from jns_pnd where APP_CODE='$cFILTER_CODE' and DELETOR=''");
																while($r_JNS_PND=SYS_FETCH($q_JNS_USH)){
																	if($a_NPWPD['PND_CODE']==$r_JNS_PND['PND_DESC']){
																		echo "<option value='$r_JNS_PND[PND_CODE]' selected='$a_NPWPD[PND_CODE]' >$r_JNS_PND[PND_DESC]</option>";
																	} else {
																		echo "<option value='$r_JNS_PND[PND_CODE]'  >$r_JNS_PND[PND_DESC]</option>";
																	}
																}
																echo '</select>';
															} else {
																echo '<br><br>';
															}
														?>	<div class="clearfix"></div>
													</div>
													
													<div class="tab-pane fade" id="TAB_DETAIL">	<!-- Tab 2 begin -->
														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cNAMA_PEMILIK?></label>
														<input type="text" class="col-sm-6 form-label-900" name='EDIT_NPWPD_OWN' id="field-31" value=<?php echo decode_string($a_NPWPD['NPWPD_OWN'])?>><br>
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cALMT_PEMILIK?></label>
														<input type="text" class="col-sm-6 form-label-900" name='UPD_OWN_ADD1' id="field-31" value=<?php echo decode_string($a_NPWPD['OWN_ADD1'])?>><br>
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cKEC_PEMILIK?></label>
														<input type="text" class="col-sm-6 form-label-900" name='UPD_CAMAT_OWNR' id="field-31" value=<?php echo decode_string($a_NPWPD['CAMAT_OWNR'])?>><br>
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cKEL_PEMILIK?></label>
														<input type="text" class="col-sm-6 form-label-900" name='UPD_LURAH_OWNR' id="field-31" value=<?php echo decode_string($a_NPWPD['LURAH_OWNR'])?>><br>
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cTELP_PEMILIK?></label>
														<input type="text" class="col-sm-6 form-label-900" name='UPD_NO_TELP' id="field-31" value=<?php echo decode_string($a_NPWPD['NO_TELP'])?>><br>
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cKABUPATEN?></label>
														<input type="text" class="col-sm-6 form-label-900" name='UPD_KABK_OWNR' id="field-31" value=<?php echo decode_string($a_NPWPD['KABK_OWNR'])?>><br>
														<div class="clearfix"></div>

													</div>		<!-- End of Tab 2 -->
													
													<div class="tab-pane fade" id="TAB_FOTO">
														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cNMR_KUKUH?></label>
														<input type="text" class="col-sm-6 form-label-900" name='UPD_NMR_KUKUH' id="field-31" value=<?php echo $a_NPWPD['NMR_KUKUH']?>><br>
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cTGL_KUKUH?></label>
														<input type="text" class="col-sm-3 form-label-900 datepicker" data-format="dd-mm-yyyy" data-mask="date" name='UPD_TGL_KUKUH' value="<?php echo date("d-m-Y", strtotime($a_NPWPD['TGL_KUKUH']))?>">
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cTGL_KARTU?></label>
														<input type="text" class="col-sm-3 form-label-900 datepicker" data-format="dd-mm-yyyy" data-mask="date" name='UPD_TGL_KARTU' value="<?php echo date("d-m-Y", strtotime($a_NPWPD['TGL_KARTU']))?>">
														<div class="clearfix"></div>

														<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cTGL_KIRIM?></label>
														<input type="text" class="col-sm-3 form-label-900 datepicker" data-format="dd-mm-yyyy" data-mask="date" name='UPD_TGL_KIRIM' value="<?php echo date("d-m-Y", strtotime($a_NPWPD['TGL_KIRIM']))?>">
														<div class="clearfix"></div>
													</div>

												</div></br>

											</div>
											<div class="clearfix"></div>
	<!--  TAB - END -->	
											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
												<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
											</div>
										</div>
									</form>
								</div>
							</div>
						</section>
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
	$cNO_NPWP = encode_string($_POST['ADD_NPWPD_NO']);
	if($cNO_NPWP=='') {
		$cMSG_BLANK=S_MSG('SA85','Nomor WP/WR tidak boleh kosong');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$cNM_NPWP 	= encode_string($_POST['ADD_NPWPD_NAME']);
	if($cNM_NPWP=='') {
		$cMSG_BLANK=S_MSG('SA86','Nama WP/WR tidak boleh kosong');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select NPWPD_NO, APP_CODE, DELETOR from npwpd where NPWPD_NO='$_POST[ADD_NPWPD_NO]' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$cOWN_NPWP 	= encode_string($_POST['ADD_NPWPD_OWN']);
		$cNO_DAFTAR	= encode_string($_POST['ADD_NO_DAFTAR']);
		$cQUERY ="insert into npwpd set APP_CODE='$cFILTER_CODE', NPWPD_NO='$_POST[ADD_NPWPD_NO]', 
			NO_DAFTAR='$cNO_DAFTAR', NPWPD_NAME='$cNM_NPWP', 
			GRP_USAHA='$_POST[ADD_GRP_USAHA]', PND_CODE='$_POST[ADD_PND_CODE]',  
			NP_ADD1='$_POST[ADD_NP_ADD1]', NP_ADD2='$_POST[ADD_NP_ADD2]', TELPON='$_POST[ADD_NO_TELPON]', NO_DAFTAR='$_POST[ADD_NO_DAFTAR]', 
			NPWPD_OWN='$cOWN_NPWP', 
			OWN_ADD1='$_POST[ADD_OWN_ADD1]', CAMAT_OWNR='$_POST[ADD_CAMAT_OWNR]', LURAH_OWNR='$_POST[ADD_LURAH_OWNR]', TELP_OWNR='$_POST[ADD_TELP_OWNR]', KABK_OWNR='$_POST[ADD_KABK_OWNR]', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);
		SYS_DB_CLOSE($DB2);	
		header('location:sim_tb_npwpd.php');
	} else {
		$cMSG_EXIST=S_MSG('SA86','Nomor WP/WR sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
	}
	break;

case 'rubah':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$dTGL_KUKUH=$_POST['UPD_TGL_KUKUH'];
	$cTGL_KUKUH = substr($dTGL_KUKUH,6,4). '-'. substr($dTGL_KUKUH,3,2). '-'. substr($dTGL_KUKUH,0,2);
	$dTGL_KARTU=$_POST['UPD_TGL_KARTU'];
	$cTGL_KARTU = substr($dTGL_KARTU,6,4). '-'. substr($dTGL_KARTU,3,2). '-'. substr($dTGL_KARTU,0,2);
	$dTGL_KIRIM=$_POST['UPD_TGL_KIRIM'];
	$cTGL_KIRIM = substr($dTGL_KIRIM,6,4). '-'. substr($dTGL_KIRIM,3,2). '-'. substr($dTGL_KIRIM,0,2);
//	die ('tgl : '.$cTGL_KUKUH);
	$cNM_NPWP = encode_string($_POST['UPD_NPWPD_NAME']);
	if($cNM_NPWP=='') {
		$cMSG_BLANK=S_MSG('SA86','Nama WP/WR tidak boleh kosong');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$cNO_DAFTAR = encode_string($_POST['UPD_NO_DAFTAR']);
	$cNP_ADD1 	= encode_string($_POST['UPD_NP_ADD1']);
	$cNP_ADD2 	= encode_string($_POST['UPD_NP_ADD2']);
	$cNP_ADD3 	= encode_string($_POST['UPD_NP_ADD3']);
	$cNO_TELPON = encode_string($_POST['EDIT_NO_TELPON']);
	$cQUERY ="update npwpd set NPWPD_NAME='$cNM_NPWP', NO_DAFTAR='$cNO_DAFTAR', 
		NP_ADD1='$cNP_ADD1', NP_ADD2='$cNP_ADD2', NP_ADD3='$cNP_ADD3', NO_TELP='$cNO_TELPON', 
		CAMAT_CODE='$_POST[UPD_CAMAT_CODE]', LURAH_CODE='$_POST[UPD_LURAH_CODE]', KABK_CODE='$_POST[UPD_KABK_CODE]', 
		OWN_ADD1='$_POST[UPD_OWN_ADD1]', CAMAT_OWNR='$_POST[UPD_CAMAT_OWNR]', LURAH_OWNR='$_POST[UPD_LURAH_OWNR]', NO_TELP='$_POST[UPD_NO_TELP]', KABK_OWNR='$_POST[UPD_KABK_OWNR]', 
		NMR_KUKUH='$_POST[UPD_NMR_KUKUH]', TGL_KUKUH='$cTGL_KUKUH', TGL_KARTU='$cTGL_KARTU', TGL_KIRIM='$cTGL_KIRIM', ";
	if($nJNS_PAJAK > 0) {
		$cQUERY.=" KLP_PND='$_POST[UPD_KLP_PND]', ";
	}
	if($nGROUP_USAHA > 0) {
		$cQUERY.=" GRP_USAHA='$_POST[UPD_GRP_USAHA]', ";
	}
	if($nJENIS_USAHA > 0) {
		$cQUERY.=" PND_CODE='$_POST[UPD_PND_CODE]', ";
	}
	$cQUERY.="UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW' ";
	$cQUERY.="where APP_CODE='$cFILTER_CODE' and NPWPD_NO='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	SYS_DB_CLOSE($DB2);
	header('location:sim_tb_npwpd.php');
	break;

case md5('del_cust'):
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cQUERY ="update npwpd set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW'";
	$cQUERY.="where APP_CODE='$cFILTER_CODE' and md5(NPWPD_NO)='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	SYS_DB_CLOSE($DB2);
	header('location:sim_tb_npwpd.php');
}
?>

