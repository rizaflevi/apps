<?php
//	bm_pelanggan.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('PQ01','Tabel Pelanggan');

	$can_UPDATE = TRUST($_SESSION['gUSERCODE'], 'BM_PEL', 'U');
	$can_DELETE = TRUST($_SESSION['gUSERCODE'], 'BM_PEL', 'D');

	$c_FILTER_RAYON = '';
	$q_USER_RAYON=SYS_QUERY("select * from tb_user where USER_CODE='$_SESSION[gUSERCODE]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$a_USER_RAYON =SYS_FETCH($q_USER_RAYON);
	$c_FILTER_RAYON = " and KODE_RUTE='$a_USER_RAYON[USER_UNIT]'";
	$c_RAYON = $a_USER_RAYON['USER_UNIT'];
//	var_dump($a_USER_RAYON); exit;

	
	if ($c_RAYON=='') {
		$q_TB_AREA=SYS_QUERY("select * from tb_area where APP_CODE='$cFILTER_CODE' and DELETOR=''");
		$aREC_TB_AREA=SYS_FETCH($q_TB_AREA);
		
		$cFILTER_AREA=$aREC_TB_AREA['KODE_AREA'];
		if (isset($_GET['_r'])) {
			$cFILTER_AREA=$_GET['_r'];
		}
	} else {
		$cFILTER_AREA=$c_RAYON;
	}

	$cFILTER_RBM='';
	if (isset($_GET['_h'])) {
		$cFILTER_RBM=$_GET['_h'];
	}

	$cFILTER_PTGS='';
	if (isset($_GET['_p'])) {
		$cFILTER_PTGS=$_GET['_p'];
	}

	$cBM_TB_PEL = "SELECT A.UNITUP, A.IDPEL, A.NAMA_PEL,A.ALAMAT,A.KODE_TARIF,A.DAYA,A.NOMOR_KWH,A.MERK_KWH,A.LAST_VISIT_DATE, bm_tb_catter1.KODE_CATTER, bm_tb_catter1.NAMA_CATTER, B.KODE_RUTE FROM bm_tb_pel A 
		INNER JOIN (SELECT IDPEL, PETUGAS, KODE_RUTE FROM bm_tb_plg WHERE APP_CODE='$cFILTER_CODE'";
	if ($cFILTER_PTGS!='') {
		$cBM_TB_PEL .= " AND PETUGAS='$cFILTER_PTGS'";
	}
	if ($cFILTER_RBM!='') {
		$cBM_TB_PEL .= " AND KODE_RUTE='$cFILTER_RBM'";
	}
	$cBM_TB_PEL .= ") B ON B.IDPEL=A.IDPEL";
	$cBM_TB_PEL .= " left join bm_tb_catter1 on B.PETUGAS = bm_tb_catter1.KODE_CATTER";
	$cBM_TB_PEL .= " WHERE A.UNITUP='$cFILTER_AREA' AND A.APP_CODE='$cFILTER_CODE' AND A.DELETOR=''";
/*
	$cQUERY="select bm_tb_pel.IDPEL, bm_tb_pel.NAMA_PEL, bm_tb_pel.ALAMAT, bm_tb_pel.KODE_TARIF, bm_tb_pel.DAYA, bm_tb_pel.NOMOR_KWH, bm_tb_pel.MERK_KWH, bm_tb_pel.UNITUP, 
		bm_tb_plg.IDPEL, bm_tb_plg.PETUGAS, bm_tb_plg.KODE_RUTE, bm_tb_plg.NO_URUT, 
		bm_tb_catter1.KODE_CATTER, bm_tb_catter1.NAMA_CATTER
		from bm_tb_pel
		left join bm_tb_plg on bm_tb_pel.IDPEL = bm_tb_plg.IDPEL
		left join bm_tb_catter1 on bm_tb_plg.PETUGAS = bm_tb_catter1.KODE_CATTER
		where bm_tb_pel.APP_CODE='$cFILTER_CODE' and bm_tb_pel.DELETOR=''";
	$cQUERY.= " and bm_tb_pel.UNITUP='".$cFILTER_AREA."'";
//	if ($cFILTER_RBM!='') {
		$cQUERY.= " and bm_tb_plg.KODE_RUTE='".$cFILTER_RBM."'";
//	}
	$cQUERY.= " and bm_tb_plg.PETUGAS='".$cFILTER_PTGS."'";
	if ($cFILTER_RBM=='' or $cFILTER_PTGS=='') {
		$cQUERY.= " limit 10";
	}
*/	
//	var_dump($cBM_TB_PEL); die;	exit();
	$qQUERY=SYS_QUERY($cBM_TB_PEL);		// ." limit 1000"

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

	$cKODE_TBL 	= S_MSG('PQ02','Id Pelanggan');
	$cNAMA_TBL 	= S_MSG('PQ03','Nama Pelanggan');
	$cALAMAT 	= S_MSG('PQ04','Alamat');
	$cAREA 		= S_MSG('PQ05','Area');
	$cKD_TARIF	= S_MSG('PQ06','Kode Tarif');
	$cDAYA		= S_MSG('PQ07','Daya');
	$cNOMOR_KWH	= S_MSG('PQ08','Nomor KWH');
	$cMERK_KWH	= S_MSG('PQ09','Merk KWH');
	$cKETERANGAN = S_MSG('PQ10','Keterangan');
	$cPTGS		= S_MSG('RQ08','Petugas');
	$cRUTE_BACA	= S_MSG('RQ15','Kode RBM');
	$cNO_URUT 	= S_MSG('RQ39','No. Urut');

	$cADD_REC	= S_MSG('PQ21','Tambah Pelanggan');
	$cEDIT_TBL	= S_MSG('PQ22','Edit Tabel Pelanggan');
	$cDAFTAR	= S_MSG('PQ30','Daftar Tabel Pelanggan');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$cTTIP_KODE	= S_MSG('PQ12','Identitas / Nomor Pelanggan');
	$cTTIP_NAMA	= S_MSG('PQ13','Nama Pelanggan');
	$cTTIP_ALMT	= S_MSG('PQ14','Alamat pelanggan');
	$cTTIP_AREA	= S_MSG('PQ15','Area lokasi pelanggan');
	$cTTIP_TRIF	= S_MSG('PQ16','Kode kelas tarif pelanggan');
	$cTTIP_DAYA	= S_MSG('PQ17','Daya / watt maksimum');
	$cTTIP_NKWH	= S_MSG('PQ18','Nomor KWH pelanggan');
	$cTTIP_MKWH	= S_MSG('PQ19','Merek KWH pelanggan');
	$cTTIP_KETR	= S_MSG('PQ20','Keterangan lain mengenai pelanggan');
	
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	$cTBL_LIST_DISPLAY_COLOR = S_PARA('_DISP_TABLE_LIST_FCOLOR','black');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
		$cHELP_BOX	= S_MSG('PQ51','Help Tabel Pelanggan');
		$cHELP_1	= S_MSG('PQ52','Ini adalah modul untuk melihat, memasukkan, merubah atau menghapus data Pelanggan yang didapat dari pihak PLN.');
		$cHELP_2	= S_MSG('PQ53','Tabel ini diperlukan untuk menampung data mengenai pelanggan yang akan di kunjungi oleh billman sesuai rute perjalanan atau jadwal kunjungan.');
		$cHELP_3	= S_MSG('PQ54','Untuk memasukkan data Pelanggan baru, klik tambah Pelanggan / add new');

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
												<a href="#help_prs_bm_tb_catter1" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>

								<?php 
									if ($c_RAYON=='') {
										echo '<label class="col-sm-1 form-label-700" for="field-4">'. $cAREA.'</label>';
										echo '<select name="PILIH_AREA" class="col-sm-3 form-label-900"  onchange="FILTER_DATA(this.value, '. $cFILTER_RBM. ', '. $cFILTER_PTGS. ')">';

										$q_TB_AREA =SYS_QUERY("select * from tb_area where APP_CODE='$cFILTER_CODE' and DELETOR=''");
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

								<label class="col-sm-2 form-label-700" style="text-align: right"><?php echo $cRUTE_BACA?></label>
								<select name="HARI_BACA" class="col-sm-2 form-label-900"  onchange="FILTER_DATA('<?php echo $cFILTER_AREA?>', this.value, '<?php echo $cFILTER_PTGS?>')">
								<?php 
									$q_RUTEBM=SYS_QUERY("select * from bm_tb_rute where APP_CODE='$cFILTER_CODE' and DELETOR=''");
									echo "<option value=''  > All</option>";
									while($a_TB_RUTEBM =SYS_FETCH($q_RUTEBM)){

										if($a_TB_RUTEBM['KODE_RUTE']==$cFILTER_RBM){
											echo "<option value='".$a_TB_RUTEBM['KODE_RUTE']. "' selected='$qQUERY[KODE_RBM]' >$a_TB_RUTEBM[KODE_RUTE]</option>";
										} else {
											echo "<option value='".$a_TB_RUTEBM['KODE_RUTE']. "'  >$a_TB_RUTEBM[KODE_RUTE]</option>";
										}

									}
								?>
								</select>

								<label class="col-sm-2 form-label-700" style="text-align: right"><?php echo $cPTGS?></label>
								<select name="PILIH_PTGS" class="col-sm-2 form-label-900" onchange="FILTER_DATA('<?php echo $cFILTER_AREA?>', '<?php echo $cFILTER_RBM?>', this.value)">
								<?php 
									$qCATTER1=SYS_QUERY("select * from bm_tb_catter1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
									echo "<option value=''  > All</option>";
									while($a_TB_CATTER =SYS_FETCH($qCATTER1)){

										if($a_TB_CATTER['KODE_CATTER']==$cFILTER_PTGS){
											echo "<option value='".$a_TB_CATTER['KODE_CATTER']. "' selected='$qQUERY[PETUGAS]' >$a_TB_CATTER[NAMA_CATTER]</option>";
										} else {
											echo "<option value='".$a_TB_CATTER['KODE_CATTER']. "'  >$a_TB_CATTER[NAMA_CATTER]</option>";
										}

									}
								?>
								</select><div class="clearfix"></div><br>

								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>">
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cALAMAT?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKD_TARIF?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cDAYA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNOMOR_KWH?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cMERK_KWH?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cAREA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cPTGS?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cRUTE_BACA?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_TB_PEL=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td style="width: 1px;"></td>';
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_PEL['IDPEL'])."' style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($aREC_TB_PEL['IDPEL'])."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_PEL['IDPEL'])."' style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($aREC_TB_PEL['NAMA_PEL'])."</a></span></td>";
															echo "<td style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($aREC_TB_PEL['ALAMAT'])."</td>";
															echo "<td style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($aREC_TB_PEL['KODE_TARIF'])."</td>";
															echo '<td style="color:'.$cTBL_LIST_DISPLAY_COLOR.';" align="right;">'.number_format($aREC_TB_PEL['DAYA']).'</td>';
															echo "<td style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($aREC_TB_PEL['NOMOR_KWH'])."</td>";
															echo "<td style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($aREC_TB_PEL['MERK_KWH'])."</td>";
															echo "<td style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($aREC_TB_PEL['UNITUP'])."</td>";
															echo '<td style="color:'.$cTBL_LIST_DISPLAY_COLOR.';">'.$aREC_TB_PEL['NAMA_CATTER']."</td>";
															echo '<td style="color:'.$cTBL_LIST_DISPLAY_COLOR.';">'.$aREC_TB_PEL['KODE_RUTE']."</td>";
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

					<div class='col-lg-9 col-md-12 col-sm-9 col-xs-9'>
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
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
											<input type="text" class="col-sm-3 form-label-900" name='ADD_IDPEL' title="<?php echo $cTTIP_KODE?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_NAMA_PEL' title="<?php echo $cTTIP_NAMA?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cALAMAT?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_ALAMAT' title="<?php echo $cTTIP_ALMT?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cAREA?></label>
											<select name="ADD_UNITUP" class="col-sm-3 form-label-900">
											<?php 
												$q_TB_AREA=SYS_QUERY("select * from tb_area where APP_CODE='$cFILTER_CODE' and DELETOR=''");
//												echo "<option value=' '  > </option>";
												while($r_TB_AREA=SYS_FETCH($q_TB_AREA)){
													echo "<option value='$r_TB_AREA[KODE_AREA]'  >$r_TB_AREA[NAMA_AREA]</option>";
												}
											?>
											</select>	<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNOMOR_KWH?></label>
											<input type="text" class="col-sm-3 form-label-900" name='ADD_NOMOR_KWH' id="field-2">
											<div class="clearfix"></div><br>

												<label class="col-sm-4 form-label-700"><?php echo $cPTGS?></label>
												<select name="ADD_PETUGAS" class="col-sm-4 form-label-900">
												<?php 
													$qCATTER1=SYS_QUERY("select * from bm_tb_catter1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
//									echo "<option value=''  > All</option>";
													while($a_TB_CATTER =SYS_FETCH($qCATTER1)){
														echo "<option value='".$a_TB_CATTER['KODE_CATTER']. "'  >$a_TB_CATTER[NAMA_CATTER]</option>";
													}
												?>
												</select><div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cRUTE_BACA?></label>
												<input type="text" class="col-sm-4 form-label-900" name='ADD_KODE_RUTE'>
												<div class="clearfix"></div>
												
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNO_URUT?></label>
												<input type="text" class="col-sm-4 form-label-900" name='ADD_NO_URUT'>
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
		$cQUERY ="select * from bm_tb_pel ";
		$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(IDPEL)='$_GET[_r]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$aREC_BM_TB_PEL=SYS_FETCH($qQUERY);

		$cQUERY ="select * from bm_tb_plg 
		where APP_CODE='$cFILTER_CODE' and md5(IDPEL)='$_GET[_r]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$aREC_BM_TB_PLG=SYS_FETCH($qQUERY);
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

						<div class='col-lg-9 col-md-9 col-sm-9 col-xs-9'>
							<div class="page-title">

								<div class="pull-left">
									  <h2 class="title"><?php echo $cEDIT_TBL?></h2>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<?php 
											if($can_DELETE == 1) {
												echo '<li>';
													echo '<a href="?_a='.md5('del_area').'&id='. md5($aREC_BM_TB_PEL['IDPEL']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'; 
												echo '</li>';
											}
										?>
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
										<form action ="?_a=rubah&id=<?php echo $aREC_BM_TB_PEL['IDPEL']?>" method="post">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
												<input type="text" class="col-sm-3 form-label-900" name='EDIT_IDPEL' value=<?php echo $aREC_BM_TB_PEL['IDPEL']?> disabled="disabled" title="<?php echo $cTTIP_KODE?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-8 form-label-900" name='EDIT_NAMA_PEL' value="<?php echo decode_string($aREC_BM_TB_PEL['NAMA_PEL'])?>" title="<?php echo $cTTIP_NAMA?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cALAMAT?></label>
												<input type="text" class="col-sm-8 form-label-900" name='EDIT_ALAMAT' value="<?php echo decode_string($aREC_BM_TB_PEL['ALAMAT'])?>" title="<?php echo $cTTIP_ALMT?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cAREA?></label>
												<select name="UPD_UNITUP" class="col-sm-4 form-label-900">
												<?php 
													$q_TB_AREA=SYS_QUERY("select * from tb_area where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													echo "<option value=' '  > </option>";
													while($r_TB_AREA=SYS_FETCH($q_TB_AREA)){
														if($aREC_BM_TB_PEL['UNITUP']==$r_TB_AREA['KODE_AREA']){
															echo "<option value='$r_TB_AREA[KODE_AREA]' selected='$aREC_BM_TB_PEL[UNITUP]' >$r_TB_AREA[NAMA_AREA]</option>";
														} else {
															echo "<option value='$r_TB_AREA[KODE_AREA]'  >$r_TB_AREA[NAMA_AREA]</option>";
														}
													}
												?>
												</select>	<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cKD_TARIF?></label>
												<select name="UPD_KODE_TARIF" class="col-sm-4 form-label-900">
												<?php 
													$q_TB_TARIF =SYS_QUERY("select * from bm_tarif where APP_CODE='$cFILTER_CODE' and DELETOR=''");
				//									echo "<option value=''  > All</option>";
													while($r_BM_TARIF =SYS_FETCH($q_TB_TARIF)){

														if($r_BM_TARIF['KODE_TRF']==$aREC_BM_TB_PEL['KODE_TARIF']){
															echo "<option value='".$r_BM_TARIF['KODE_TRF']. "' selected='$aREC_BM_TB_PEL[KODE_TARIF]' >$r_BM_TARIF[KET_TARIF]</option>";
														} else {
															echo "<option value='".$r_BM_TARIF['KODE_TRF']. "'  >$r_BM_TARIF[KET_TARIF]</option>";
														}
													}
												?>
												</select>	<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNOMOR_KWH?></label>
												<input type="text" class="col-sm-4 form-label-900" name='UPD_NOMOR_KWH' value="<?php echo decode_string($aREC_BM_TB_PEL['NOMOR_KWH'])?>">
												<div class="clearfix"></div><br>
												
												<label class="col-sm-4 form-label-700"><?php echo $cPTGS?></label>
												<select name="UPD_PETUGAS" class="col-sm-4 form-label-900">
												<?php 
													$qCATTER1=SYS_QUERY("select * from bm_tb_catter1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
//									echo "<option value=''  > All</option>";
													while($a_TB_CATTER =SYS_FETCH($qCATTER1)){

														if($a_TB_CATTER['KODE_CATTER']==$aREC_BM_TB_PLG['PETUGAS']){
															echo "<option value='".$a_TB_CATTER['KODE_CATTER']. "' selected='$aREC_BM_TB_PLG[PETUGAS]' >$a_TB_CATTER[NAMA_CATTER]</option>";
														} else {
															echo "<option value='".$a_TB_CATTER['KODE_CATTER']. "'  >$a_TB_CATTER[NAMA_CATTER]</option>";
														}

													}
												?>
												</select><div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cRUTE_BACA?></label>
												<input type="text" class="col-sm-4 form-label-900" name='UPD_KODE_RUTE' value="<?php echo decode_string($aREC_BM_TB_PLG['KODE_RUTE'])?>">
												<div class="clearfix"></div>
												
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNO_URUT?></label>
												<input type="text" class="col-sm-4 form-label-900" name='UPD_NO_URUT' value="<?php echo $aREC_BM_TB_PLG['NO_URUT']?>">
												<div class="clearfix"></div><br>
												
												<div class="text-left">
													<?php 
														if($can_UPDATE == 1) {
															echo '<input type="submit" class="btn btn-primary" value=' . $cSAVE.'>';
														}
													?>
													
													<input type="button" class="btn" value=<?php echo $cCLOSE?> onclick=self.history.back()>
												</div>	<div class="clearfix"></div>

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
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>
<?php
	SYS_DB_CLOSE($DB2);	break;

case 'tambah':
	$NOW = date("Y-m-d H:i:s");
	$cIDPEL	= encode_string($_POST['ADD_IDPEL']);	
	if($cIDPEL==''){
		$cMSG_BLANK	= S_MSG('PQ26','Id Pelanggan belum diisi');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from bm_tb_pel where APP_CODE='$cFILTER_CODE' and DELETOR='' and IDPEL='$cIDPEL'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG_EXIST	= S_MSG('PQ27','Kode Pelanggan sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
	} else {
		$cNAMA_PEL	= encode_string($_POST['ADD_NAMA_PEL']);	
		$cALAMAT		= encode_string($_POST['ADD_ALAMAT']);	
		$cQUERY="insert into bm_tb_pel set IDPEL='$cIDPEL', NAMA_PEL='$cNAMA_PEL', ALAMAT='$cALAMAT', UNITUP='$_POST[ADD_UNITUP]', KODE_TARIF='$_POST[ADD_KODE_TARIF]', NOMOR_KWH='$_POST[ADD_NOMOR_KWH]', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'add');
		header('location:bm_pelanggan.php');
	}
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD	= $_GET['id'];
	$cNAMA_PEL	= encode_string($_POST['EDIT_NAMA_PEL']);	
	$cNOMOR_KWH	= encode_string($_POST['UPD_NOMOR_KWH']);	
	$cALAMAT	= encode_string($_POST['EDIT_ALAMAT']);	
	$cQUERY ="update bm_tb_pel set NAMA_PEL='$cNAMA_PEL', ALAMAT='$cALAMAT', NOMOR_KWH='$cNOMOR_KWH', UNITUP='$_POST[UPD_UNITUP]', KODE_TARIF='$_POST[UPD_KODE_TARIF]', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and IDPEL='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	$cQUERY ="update bm_tb_plg set PETUGAS='$_POST[UPD_PETUGAS]', KODE_RUTE='$_POST[UPD_KODE_RUTE]', NO_URUT='$_POST[UPD_NO_URUT]', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and IDPEL='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'update');
	header('location:bm_pelanggan.php?_r='.$cFILTER_AREA.'&_h='.$cFILTER_RBM.'&_p='.$cFILTER_PTGS);
	break;

case md5('del_area'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update bm_tb_pel set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(IDPEL)='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:bm_pelanggan.php');
}
?>


<script>

function FILTER_DATA(pAREA, pHARI, pPETUGAS) {
	window.location.assign("?_r="+pAREA + "&_h="+pHARI + "&_p="+pPETUGAS);
}

</script>
