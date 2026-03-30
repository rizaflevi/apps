<?php
//	bm_pasca_tb_plg.php 
//	pelanggan pasca bayar

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('PT01','Tabel Pelanggan Pasca Bayar');

	$can_UPDATE = 1;	// TRUST($_SESSION['gUSERCODE'], 'BM_PEL', 'U');

	$cKODE_TBL 	= S_MSG('PQ02','Id Pelanggan');
	$cNAMA_TBL 	= S_MSG('PQ03','Nama Pelanggan');
	$cALAMAT 	= S_MSG('PQ04','Alamat');
	$cAREA 		= S_MSG('PQ05','Area');
	$cKD_TARIF	= S_MSG('PQ06','Kode Tarif');
	$cDAYA		= S_MSG('PQ07','Daya');
	$cLBR_TGHN	= S_MSG('PT08','Lembar Tghn');
	$cNIL_TGHN 	= S_MSG('PT10','Nilai Tagihan');
	$cKODE		= S_MSG('F003','Kode');
	$cPTGS		= S_MSG('RQ08','Petugas');
	$cRUTE_BACA	= S_MSG('RQ15','Kode RBM');

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

	$c_FILTER_RAYON = '';
	$q_USER_RAYON=SYS_QUERY("select * from tb_user where USER_CODE='$_SESSION[gUSERCODE]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$a_USER_RAYON =SYS_FETCH($q_USER_RAYON);
	$c_FILTER_RAYON = " and KODE_RUTE='$a_USER_RAYON[USER_UNIT]'";
	$c_RAYON = $a_USER_RAYON['USER_UNIT'];

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
	$c_RBM='';
	if (isset($_GET['_h'])) {
		$cFILTER_RBM=$_GET['_h'];
		$c_RBM=$_GET['_h'];
	}

	$cFILTER_PTGS='';
	$c_PTGS='';
	if (isset($_GET['_p'])) {
		$cFILTER_PTGS=$_GET['_p'];
		$c_PTGS=$_GET['_p'];
	}

	$cBM_PEL_PASCA = "SELECT A.UNITUP, A.IDPEL, A.KODE_RBM, A.NAMA_PEL, A.ALAMAT, A.DAYA, substr(A.KODE_RBM,4,3) as PETUGAS, substr(A.KODE_RBM,7,1) as KODE_RUTE, B.LEMBAR, B.TAGIHAN, B.RPBK, bm_tb_catter1.NAMA_CATTER FROM bm_pel_pasca A 
		INNER JOIN (SELECT IDPEL, TAHUN, BULAN, LEMBAR, TAGIHAN, RPBK FROM bm_plg_pasca B WHERE APP_CODE='$cFILTER_CODE'";
	if ($cFILTER_PTGS!='') {
		$cBM_PEL_PASCA .= " AND PETUGAS='$cFILTER_PTGS'";
	}
	if ($cFILTER_RBM!='') {
		$cBM_PEL_PASCA .= " AND KODE_RUTE='$cFILTER_RBM'";
	}
	$cBM_PEL_PASCA .= ") B ON B.IDPEL=A.IDPEL";
	$cBM_PEL_PASCA .= " left join bm_tb_catter1 on substr(A.KODE_RBM,4,3) = bm_tb_catter1.KODE_CATTER";
	$cBM_PEL_PASCA .= " WHERE A.APP_CODE='$cFILTER_CODE' AND A.DELETOR=''";
	$qBM_PEL_PASCA=SYS_QUERY($cBM_PEL_PASCA);		// ." limit 1000"
//	var_dump($cBM_PEL_PASCA); exit;

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
		$cHELP_BOX	= S_MSG('PT51','Help Tabel Pelanggan Pasca Bayar');
		$cHELP_1	= S_MSG('PT52','Ini adalah modul untuk melihat, memasukkan, merubah atau menghapus data Pelanggan Pasca Bayar yang didapat dari pihak PLN.');
		$cHELP_2	= S_MSG('PT53','Tabel ini diperlukan untuk menampung data mengenai pelanggan yang akan di tagih oleh billman sesuai rute perjalanan atau jadwal kunjungan.');
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

								<label class="col-sm-1 form-label-700" for="field-4"><?php echo $cAREA?></label>
								<select name="PILIH_AREA" class="col-sm-3 form-label-900"  onchange="FILTER_DATA(this.value, '<?php echo $c_PTGS?>', '<?php echo $cFILTER_PTGS?>')">
								<?php 
									$q_TB_AREA =SYS_QUERY("select * from tb_area where APP_CODE='$cFILTER_CODE' and DELETOR=''");
//									echo "<option value=''  > All</option>";
									while($aREC_TB_AREA=SYS_FETCH($q_TB_AREA)){

										if($aREC_TB_AREA['KODE_AREA']==$cFILTER_AREA){
											echo "<option value='".$aREC_TB_AREA['KODE_AREA']. "' selected='$rec_PEL_PASCA[UNITUP]' >$aREC_TB_AREA[NAMA_AREA]</option>";
										} else {
											echo "<option value='".$aREC_TB_AREA['KODE_AREA']. "'  >$aREC_TB_AREA[NAMA_AREA]</option>";
										}
									}
								?>
								</select>

								<label class="col-sm-2 form-label-700" style="text-align: right"><?php echo $cRUTE_BACA?></label>
								<select name="HARI_BACA" class="col-sm-1 form-label-900"  onchange="FILTER_DATA('<?php echo $c_AREA?>', '<?php echo $c_PTGS?>', this.value)">
								<?php
									$q_RUTEBM=SYS_QUERY("select * from bm_tb_rute where APP_CODE='$cFILTER_CODE' and DELETOR=''");
									echo "<option value=''  > All</option>";
									while($a_TB_RUTEBM =SYS_FETCH($q_RUTEBM)){

										if($a_TB_RUTEBM['KODE_RUTE']==$c_RBM){
											echo "<option value='".$a_TB_RUTEBM['KODE_RUTE']. "' selected='$qQUERY[KODE_RBM]' >$a_TB_RUTEBM[KODE_RUTE]</option>";
										} else {
											echo "<option value='".$a_TB_RUTEBM['KODE_RUTE']. "'  >$a_TB_RUTEBM[KODE_RUTE]</option>";
										}

									}
								?>
								</select>

								<label class="col-sm-2 form-label-700" style="text-align: right"><?php echo $cPTGS?></label>
								<select name="PILIH_PTGS" class="col-sm-2 form-label-900" onchange="FILTER_DATA('<?php echo $cFILTER_AREA?>', '<?php echo $nFILTER_HARI?>', this.value)">
								<?php 
									$qCATTER1=SYS_QUERY("select * from bm_tb_catter1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
									echo "<option value=''  > Kosong</option>";
									while($a_TB_CATTER =SYS_FETCH($qCATTER1)){

										if($a_TB_CATTER['KODE_CATTER']==$cFILTER_PTGS){
											echo "<option value='".$a_TB_CATTER['KODE_CATTER']. "' selected='$aREC_TB_PEL[PETUGAS]' >$a_TB_CATTER[NAMA_CATTER]</option>";
										} else {
											echo "<option value='".$a_TB_CATTER['KODE_CATTER']. "'  >$a_TB_CATTER[NAMA_CATTER]</option>";
										}

									}
								?>
								</select><div class="clearfix"></div><br>

								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?> nowrap">
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cALAMAT?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cDAYA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cLBR_TGHN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNIL_TGHN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cPTGS?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cRUTE_BACA?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														echo '<tr>';
														while($rec_PEL_PASCA=SYS_FETCH($qBM_PEL_PASCA)) {
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($rec_PEL_PASCA['IDPEL'])."' style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($rec_PEL_PASCA['IDPEL'])."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($rec_PEL_PASCA['IDPEL'])."' style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($rec_PEL_PASCA['NAMA_PEL'])."</a></span></td>";
															echo "<td style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($rec_PEL_PASCA['ALAMAT'])."</td>";
															echo '<td style="color:'.$cTBL_LIST_DISPLAY_COLOR.';" align="right">'.number_format($rec_PEL_PASCA['DAYA']).'</td>';
															echo '<td style="color:'.$cTBL_LIST_DISPLAY_COLOR.';" align="right">'.number_format($rec_PEL_PASCA['LEMBAR']).'</td>';
															echo '<td style="color:'.$cTBL_LIST_DISPLAY_COLOR.';" align="right">'.number_format($rec_PEL_PASCA['TAGIHAN']).'</td>';
															echo "<td style='color:".$cTBL_LIST_DISPLAY_COLOR.";'>".decode_string($rec_PEL_PASCA['PETUGAS'])."</td>";
															echo '<td style="color:'.$cTBL_LIST_DISPLAY_COLOR.';">'.$rec_PEL_PASCA['NAMA_CATTER']."</td>";
															echo '<td style="color:'.$cTBL_LIST_DISPLAY_COLOR.';">'.$rec_PEL_PASCA['KODE_RUTE']."</td>";
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
			<script src="assets/js/jquery-1.11.2.min.js" type="text/javascript"></script> 
		</body>
	</html>
<?php
	SYS_DB_CLOSE($DB2);	break;

case md5('up_d4t3'):
	SYS_DB_CLOSE($DB2);	break;
}
?>
