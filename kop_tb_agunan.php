<?php
// kop_tb_agunan.php
// Tabel Agunan

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cHEADER 	= S_MSG('KA80','Tabel Master Agunan');
	$ADD_LOG	= APP_LOG_ADD();

	$cLABEL1  	= S_MSG('KA52','Kode Agunan');
	$cNAMA_AGN  = S_MSG('KA54','Nama Agunan');
	$cJNS_AGN  	= S_MSG('KA53','Jenis Agunan');
	$cNAMA_PMLK  = S_MSG('KA64','Nama Pemilik');
	$cKETERANGAN  = S_MSG('NP13','Keterangan');
	$cLUAS_TNH 	= S_MSG('KA83','Luas Tanah');
	$cNO_SERT 	= S_MSG('KA84','No. Sertifikat');
	$cTGL_SERTI = S_MSG('KA86','Tgl Sertifikat');
	$cSRT_UKUR 	= S_MSG('KA87','No. Surat Ukur');
	$cHRG_TKSRN = S_MSG('KA94','Harga Taksiran');
	$cJNS_KNDRN = S_MSG('KA43','Jenis Kendaraan');

	$cQUERY="SELECT tb_aggn2.*, tb_aggn1.KODE_AGGN as KD_JNS_AGGN, tb_aggn1.NAMA_AGN as JENIS_AGGN FROM tb_aggn2 LEFT JOIN tb_aggn1 ON tb_aggn2.JNS_AGN=tb_aggn1.KODE_AGGN where tb_aggn2.APP_CODE='$cFILTER_CODE' and tb_aggn2.DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);

	$cACTION='';
	if (isset($_GET['action'])) $cACTION=$_GET['action'];

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$cHELP_BOX	= 'Help ' .$cHEADER;
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
												 <a href="?action=create"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_kop_tb_agunan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="width: 1px;"></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cLABEL1?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_AGN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cJNS_AGN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_PMLK?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKETERANGAN?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_TB_AGGN2=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td class=""><div class="star"><i class="fa fa-home icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href=?action=".md5('Update')."&_g='$aREC_TB_AGGN2[KODE_AGGN]'>".$aREC_TB_AGGN2['KODE_AGGN']."</a></span></td>";
															echo "<td><span><a href=?action=".md5('Update')."&_g='$aREC_TB_AGGN2[KODE_AGGN]'>".$aREC_TB_AGGN2['NAMA_AGN']."</a></span></td>";
															echo '<td>'.$aREC_TB_AGGN2['JENIS_AGGN'].'</td>';
															echo '<td>'.$aREC_TB_AGGN2['NM_PEMILIK'].'</td>';
															echo '<td>'.$aREC_TB_AGGN2['AGN_NOTE'].'</td>';
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
			<?php	require_once("js_framework.php");	
                HELP_MOD('help_kop_tb_agunan', $cHELP_BOX, ['']);
//                HELP_PDF('COA');
            ?>
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 

		</body>
	</html>

<?php
	break;

case "create":
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
		<body class=" " onload="Kop_Tb_Agunaan_Add_Focus();">
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
									  <h1 class="title"><?php echo $cHEADER?></h1>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs"></div>
								<div class="content-body">
									<div class="row">
										<form name="FORM_ADD_TB_AGUNAN" action ="?action=tambah" method="post"  onSubmit="return CEK_KOP_ADD_TB_AGN(this)">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cLABEL1?></label>
												<input type="text" class="col-sm-2 form-label-900" name='ADD_KODE_AGGN' id="field-1"><br><br>

												<label class="col-sm-3 form-label-700" for="field-2"><?php echo $cNAMA_AGN?></label>
												<input type="text" class="col-sm-8 form-label-900" name='ADD_NAMA_AGN' id="field-2"><br><br>

												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cJNS_AGN?></label>
												<select name="ADD_JNS_AGN" class="col-sm-5 form-label-900 m-bot15">
												<?php 
													$qQUERY=SYS_QUERY("select * from tb_aggn1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($aREC_TB_AGGN1=SYS_FETCH($qQUERY)){
															echo "<option value='$aREC_TB_AGGN1[KODE_AGGN]'  >$aREC_TB_AGGN1[NAMA_AGN]</option>";
														}
												?>
												</select><br><br>

												<label class="col-sm-3 form-label-700" for="field-4"><?php echo $cNAMA_PMLK?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_NM_PEMILIK' id="field-4"><br><br>

												<label class="col-sm-3 form-label-700" for="field-5"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-8 form-label-900" name='ADD_AGN_NOTE' id="field-5"><br><br>

<!-- TAB - START -->
												<div class="col-sm-12">
													<h4> </br></h4>
													<ul class="nav nav-tabs primary">
														 <li class="active">
															<a href="#Detil-1" data-toggle="tab">
																	<i class="fa fa-user"></i> <?php echo S_MSG('KA81','Tanah/Rmh')?>
															</a>
														 </li>
														 <li>
															<a href="#Account-1" data-toggle="tab">
																	<i class="fa fa-home"></i> <?php echo S_MSG('KA41','Kendaraan')?> 
															</a>
														 </li>
													</ul>

													<div class="tab-content primary">
														<div class="tab-pane fade in active" id="Detil-1">
															
															<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cLUAS_TNH?></label>
															<input type="text" class="col-sm-2 form-label-900" name='EDIT_LUAS_TANAH' id="field-3"><br><br>

															<label class="col-sm-3 form-label-700" for="field-6"><?php echo $cNO_SERT?></label>
															<input type="text" class="col-sm-5 form-label-900" name='EDIT_NO_SERTFKT' id="field-8"><br><br>

															<label class="col-sm-3 form-label-700" for="field-6"><?php echo $cTGL_SERTI?></label>
															<input type="text" class="col-sm-3 form-label-900 datepicker" name='EDIT_TG_SERTFKT' data-format="yyyy-mm-dd" id="field-8"><br><br>

															<label class="col-sm-3 form-label-700" for="field-6"><?php echo $cSRT_UKUR?></label>
															<input type="text" class="col-sm-5 form-label-900" name='EDIT_NO_SRT_UKR' id="field-8"><br><br>

															<label class="col-sm-3 form-label-700" for="field-6"><?php echo $cHRG_TKSRN?></label>
															<input type="text" class="col-sm-3 form-label-900" name='EDIT_HRG_TAKSIR' id="field-8"><br><br>

														</div>		<!-- End of Tab 1 -->
														
														<!-- Tab 2 begin -->
														<div class="tab-pane fade" id="Account-1">
															
															<label class="col-sm-3 form-label-700" for="field-21"><?php echo $cJNS_KNDRN?></label>
															<input type="text" class="col-sm-4 form-label-900" name='EDIT_JNS_KDRN' id="field-8"><br><br>
																
															<label class="col-sm-3 form-label-700" for="field-3"><?php echo S_MSG('KA44','Merk Kendaraan')?></label>
															<input type="text" class="col-sm-4 form-label-900" name='EDIT_MERK_KDRN' id="field-2"><br><br>

															<label class="col-sm-3 form-label-700" for="field-3"><?php echo S_MSG('KA45','Thn Pembuatan')?></label>
															<input type="text" class="col-sm-2 form-label-900" name='EDIT_THN_BUAT' id="field-2"><br><br>

															<label class="col-sm-3 form-label-700" for="field-3"><?php echo S_MSG('KA46','No. Polisi')?></label>
															<input type="text" class="col-sm-3 form-label-900" name='EDIT_NMR_POLISI' id="field-2"><br><br>

															<label class="col-sm-3 form-label-700" for="field-3"><?php echo S_MSG('KA47','Alamat Pemilik')?></label>
															<input type="text" class="col-sm-8 form-label-900" name='EDIT_ALMT_MILIK' id="field-2"><br><br>

														</div>		<!-- End of Tab 2 -->
														
													</div></br>

												</div>
<!--  TAB - END -->	
												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo S_MSG('F301','Save')?>>
													<input type="button" class="btn" value=<?php echo S_MSG('F302','Cancel')?> onclick=self.history.back()>
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
	break;

case md5('Update'):
//	$cKODE_AGUNAN	= encode_string($_GET['_g']);
	$cKODE_AGUNAN	= $_GET['_g'];
	if(empty($cKODE_AGUNAN)) return 0;
	$cQ3="select * from tb_aggn3 where KODE_AGGN=$cKODE_AGUNAN and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$qQ3=SYS_QUERY($cQ3);
	if(SYS_ROWS($qQ3)==0){
		$cQUERY_TB_AGGN3="insert into tb_aggn3 set APP_CODE='$cFILTER_CODE', KODE_AGGN=$cKODE_AGUNAN";
		$cQUERY_TB_AGGN3.=", ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY_TB_AGGN3);
	}
	$cQ4="select * from tb_aggn4 where KODE_AGGN=$cKODE_AGUNAN and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$qQ4=SYS_QUERY($cQ4);
	if(SYS_ROWS($qQ4)==0){
		$cQUERY_TB_AGGN4="insert into tb_aggn4 set APP_CODE='$cFILTER_CODE', KODE_AGGN=$cKODE_AGUNAN";
		$cQUERY_TB_AGGN4.=", ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY_TB_AGGN4);
	}

	$cQUERY = "select A.*, B.KODE_AGGN as KD_JNS_AGGN, B.NAMA_AGN as JENIS_AGGN from tb_aggn2 A ";
	$cQUERY.= " left join ( select * from tb_aggn1 where APP_CODE='" . $cFILTER_CODE . "' and DELETOR='') B ON A.KODE_AGGN=B.KODE_AGGN ";
	$cQUERY.= " where A.KODE_AGGN=$cKODE_AGUNAN";
	$cQUERY.= " and A.APP_CODE='" . $cFILTER_CODE . "' and A.DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);
	if(SYS_ROWS($qQUERY)==0){
		header('location:kop_tb_agunan.php');
	}
	$REC_TB_AGGN2=SYS_FETCH($qQUERY);
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
									<h2 class="title"><?php echo $cHEADER?></h2>
								</div>
								<div class="pull-right">									 
									<ol class="breadcrumb">
										<li>
											<a href="?action=delete&id=<?php echo $REC_TB_AGGN2['KODE_AGGN']?>" onClick="return confirm('Apakah Anda benar-benar mau menghapusnya?')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
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
										<form action ="?action=rubah&id=<?php echo $REC_TB_AGGN2['KODE_AGGN']?>" method="post">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cLABEL1?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_KODE_AGGN' id="field-1" value=<?php echo $REC_TB_AGGN2['KODE_AGGN']?> disabled="disabled">
												<div class="clearfix"></div><br>

												<label class="col-sm-3 form-label-700" for="field-2"><?php echo $cNAMA_AGN?></label>
												<input type="text" class="col-sm-8 form-label-900" name='EDIT_NAMA_AGN' id="field-2" value="<?php echo $REC_TB_AGGN2['NAMA_AGN']?>">
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cJNS_AGN?></label>
												<select name="EDIT_JNS_AGN" class="col-sm-5 form-label-900">
												<?php 
													$qQUERY=SYS_QUERY("select * from tb_aggn1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($aREC_TB_AGGN1=SYS_FETCH($qQUERY)){
														if($REC_TB_AGGN2['KD_JNS_AGGN'] == $aREC_TB_AGGN1['KODE_AGGN']){
															echo "<option value='$aREC_TB_AGGN1[KODE_AGGN]' selected='$REC_TB_AGGN2[KD_JNS_AGGN]' >$aREC_TB_AGGN1[NAMA_AGN]</option>";
														} else {
															echo "<option value='$aREC_TB_AGGN1[KODE_AGGN]'  >$aREC_TB_AGGN1[NAMA_AGN]</option>";
														}
													}
												?>
												</select><br>	<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-4"><?php echo $cNAMA_PMLK?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_NM_PEMILIK' id="field-4" value=<?php echo $REC_TB_AGGN2['NM_PEMILIK']?>>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-5"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-8 form-label-900" name='EDIT_AGN_NOTE' id="field-5" value="<?php echo $REC_TB_AGGN2['AGN_NOTE']?>">
												<div class="clearfix"></div>

<!-- TAB - START -->
												<div class="col-sm-12">
													<h4> </br></h4>
													<ul class="nav nav-tabs primary">
														 <li class="active">
															  <a href="#Detil-1" data-toggle="tab">
																	<i class="fa fa-user"></i> <?php echo S_MSG('KA81','Tanah/Rmh')?>
															  </a>
														 </li>
														 <li>
															  <a href="#Account-1" data-toggle="tab">
																	<i class="fa fa-home"></i> <?php echo S_MSG('KA41','Kendaraan')?> 
															  </a>
														 </li>
													</ul>

													<div class="tab-content primary">
														<div class="tab-pane fade in active" id="Detil-1">
															
															<?php $cQUERY="select * from tb_aggn3 where KODE_AGGN=$cKODE_AGUNAN and APP_CODE='$cFILTER_CODE' and DELETOR=''";
																$qQUERY=SYS_QUERY($cQUERY);
																$aREC_TB_AGGN3=SYS_FETCH($qQUERY);
																if(SYS_ROWS($qQUERY)==0){
																	return;
																}
															?>
															
															<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cLUAS_TNH?></label>
															<input type="text" value=<?php echo $aREC_TB_AGGN3['LUAS_TANAH']?> class="col-sm-2 form-label-900" name='EDIT_LUAS_TANAH' id="field-3">
															<div class="clearfix"></div>

															<label class="col-sm-3 form-label-700" for="field-6"><?php echo $cNO_SERT?></label>
															<input type="text" value="<?php echo $aREC_TB_AGGN3['NO_SERTFKT']?>" class="col-sm-4 form-label-900" name='EDIT_NO_SERTFKT' id="field-8">
															<div class="clearfix"></div>

															<label class="col-sm-3 form-label-700" for="field-6"><?php echo $cTGL_SERTI?></label>
															<input type="text" value="<?php echo date("d-m-Y", strtotime($aREC_TB_AGGN3['TG_SERTFKT']))?>" class="col-sm-3 form-label-900" name='EDIT_TG_SERTFKT' data-mask="date" id="field-8">
															<div class="clearfix"></div>

															<label class="col-sm-3 form-label-700" for="field-6"><?php echo $cSRT_UKUR?></label>
															<input type="text" value="<?php echo $aREC_TB_AGGN3['NO_SRT_UKR']?>" class="col-sm-4 form-label-900" name='EDIT_NO_SRT_UKR' id="field-8">
															<div class="clearfix"></div>

															<label class="col-sm-3 form-label-700" for="field-6"><?php echo $cHRG_TKSRN?></label>
															<input type="text" value="<?php echo $aREC_TB_AGGN3['HRG_TAKSIR']?>" class="col-sm-3 form-label-900" name='EDIT_HRG_TAKSIR' id="field-8">
															<div class="clearfix"></div>

														</div>		<!-- End of Tab 1 -->
														
														<!-- Tab 2 begin -->
														<div class="tab-pane fade" id="Account-1">
															<?php $cQUERY="select * from tb_aggn4 where KODE_AGGN=$cKODE_AGUNAN and APP_CODE='$cFILTER_CODE' and DELETOR=''";
																$qQUERY=SYS_QUERY($cQUERY);
																$aREC_TB_AGGN4=SYS_FETCH($qQUERY);
																if(SYS_ROWS($qQUERY)==0){
																	return;
																}
															?>
															
															<label class="col-sm-3 form-label-700" for="field-21"><?php echo $cJNS_KNDRN?></label>
															<input type="text" class="col-sm-4 form-label-900" name='EDIT_JNS_KDRN' id="field-8" value="<?php echo $aREC_TB_AGGN4['JNS_KDRN']?>">
															<div class="clearfix"></div>
																
															<label class="col-sm-3 form-label-700" for="field-3"><?php echo S_MSG('KA44','Merk Kendaraan')?></label>
															<input type="text" class="col-sm-4 form-label-900" name='EDIT_MERK_KDRN' id="field-2" value="<?php echo $aREC_TB_AGGN4['MERK_KDRN']?>">
															<div class="clearfix"></div>

															<label class="col-sm-3 form-label-700" for="field-3"><?php echo S_MSG('KA45','Thn Pembuatan')?></label>
															<input type="text" class="col-sm-2 form-label-900" name='EDIT_THN_BUAT' id="field-2" value="<?php echo $aREC_TB_AGGN4['THN_BUAT']?>">
															<div class="clearfix"></div>

															<label class="col-sm-3 form-label-700" for="field-3"><?php echo S_MSG('KA46','No. Polisi')?></label>
															<input type="text" class="col-sm-4 form-label-900" name='EDIT_NMR_POLISI' id="field-2" value="<?php echo $aREC_TB_AGGN4['NMR_POLISI']?>">
															<div class="clearfix"></div>

															<label class="col-sm-3 form-label-700" for="field-3"><?php echo S_MSG('KA47','Alamat Pemilik')?></label>
															<input type="text" class="col-sm-8 form-label-900" name='EDIT_ALMT_MILIK' id="field-2" value="<?php echo $aREC_TB_AGGN4['ALMT_MILIK']?>">
															<div class="clearfix"></div>

														</div></br>		<!-- End of Tab 2 -->
														
													</div></br>

												</div>
<!--  TAB - END -->	
												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo S_MSG('F301','Save')?>>
													<input type="button" class="btn" value=<?php echo S_MSG('F302','Cancel')?> onclick=self.history.back()>
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
	break;

case 'tambah':
	if($_POST['ADD_KODE_AGGN']=='') {
		$cMSG = S_MSG('KA97','Kode Agunan tidak boleh kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;

	}
	$cQUERY="select * from tb_aggn2 where APP_CODE='$cFILTER_CODE' and KODE_AGGN='$_POST[ADD_KODE_AGGN]' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$cQUERY ="insert into tb_aggn2 set KODE_AGGN='$_POST[ADD_KODE_AGGN]', NAMA_AGN='$_POST[ADD_NAMA_AGN]'";
		$cQUERY.=", JNS_AGN='$_POST[ADD_JNS_AGN]', NM_PEMILIK='$_POST[ADD_NM_PEMILIK]', AGN_NOTE='$_POST[ADD_AGN_NOTE]'";
		$cQUERY.=", APP_CODE='$cFILTER_CODE', ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);
		header('location:kop_tb_agunan.php');
	} else {
		$cMSG = S_MSG('KA98','Kode Agunan sudah ada');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	header('location:kop_tb_agunan.php');
	break;

case 'rubah':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$dTG_SERTFKT = $_POST['EDIT_TG_SERTFKT'];
	if($dTG_SERTFKT=='') {
		$dTG_SERTFKT = '0000-00-00';
	}
	$cQUERY ="update tb_aggn2 set NAMA_AGN='$_POST[EDIT_NAMA_AGN]', JNS_AGN='$_POST[EDIT_JNS_AGN]', ";
	$cQUERY.=" NM_PEMILIK='$_POST[EDIT_NM_PEMILIK]', AGN_NOTE='$_POST[EDIT_AGN_NOTE]', ";
	$cQUERY.=" UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and KODE_AGGN='$KODE_CRUD'";
	$qQUERY=SYS_QUERY($cQUERY);

	$cQUERY ="update tb_aggn3 set LUAS_TANAH=$_POST[EDIT_LUAS_TANAH], NO_SERTFKT='$_POST[EDIT_NO_SERTFKT]', ";
	$cQUERY.=" TG_SERTFKT=$dTG_SERTFKT, NO_SRT_UKR='$_POST[EDIT_NO_SRT_UKR]', HRG_TAKSIR=$_POST[EDIT_HRG_TAKSIR], ";
	$cQUERY.=" UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and KODE_AGGN='$KODE_CRUD'";
	$qQUERY=SYS_QUERY($cQUERY);

	$cQUERY ="update tb_aggn4 set JNS_KDRN='$_POST[EDIT_JNS_KDRN]', MERK_KDRN='$_POST[EDIT_MERK_KDRN]', THN_BUAT=$_POST[EDIT_THN_BUAT], ";
	$cQUERY.=" NMR_POLISI='$_POST[EDIT_NMR_POLISI]', ALMT_MILIK='$_POST[EDIT_ALMT_MILIK]', ";
	$cQUERY.=" UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and KODE_AGGN='$KODE_CRUD'";
	$qQUERY=SYS_QUERY($cQUERY);
	header('location:kop_tb_agunan.php');
	break;

case 'delete':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cQUERY ="update tb_aggn2 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and KODE_AGGN=$KODE_CRUD";
	$qQUERY=SYS_QUERY($cQUERY);
	$cQUERY ="update tb_aggn3 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and KODE_AGGN=$KODE_CRUD";
	$qQUERY=SYS_QUERY($cQUERY);
	$cQUERY ="update tb_aggn4 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and KODE_AGGN=$KODE_CRUD";
	SYS_QUERY($cQUERY);
	header('location:kop_tb_agunan.php');
}
?>


<script>

function Kop_Tb_Agunaan_Add_Focus()  
{  
	var uid = document.FORM_ADD_TB_AGUNAN.ADD_KODE_AGGN.focus();  
	return true;  
}

function CEK_KOP_ADD_TB_AGN(form){
	if (form.ADD_KODE_AGGN.value == ""){
		alert("Anda belum mengisikan Kode Agunan");
		form.ADD_KODE_AGGN.focus();
		return (false);
	}
			  
	if (form.ADD_NAMA_AGN.value == ""){
		alert("Anda belum mengisikan Nama Identitas");
		form.ADD_NAMA_AGN.focus();
		return (false);
	}
	return (true);
}

</script>


