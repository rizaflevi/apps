<?php
//	bm_pelanggan.php //

	$database1 = 'sys_data';
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('PQ01','Tabel Pelanggan');

	$can_UPDATE = 1;	// TRUST($_SESSION['gUSERCODE'], 'BM_PEL', 'U');
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
	$cHARI_BACA = S_MSG('PQ1A','Hari Baca');
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
								<select name="PILIH_AREA" class="col-sm-3 form-label-900"  onchange="FILTER_DATA(this.value, '<?php echo $nFILTER_HARI?>', '<?php echo $cFILTER_PTGS?>')">
								<?php 
									$q_TB_AREA =SYS_QUERY("select * from tb_area where APP_CODE='$cFILTER_CODE' and DELETOR=''");
//									echo "<option value=''  > All</option>";
									while($aREC_TB_AREA=SYS_FETCH($q_TB_AREA)){

										if($aREC_TB_AREA['KODE_AREA']==$cFILTER_AREA){
											echo "<option value='".$aREC_TB_AREA['KODE_AREA']. "' selected='$aREC_TB_PEL[UNITUP]' >$aREC_TB_AREA[NAMA_AREA]</option>";
										} else {
											echo "<option value='".$aREC_TB_AREA['KODE_AREA']. "'  >$aREC_TB_AREA[NAMA_AREA]</option>";
										}
									}
								?>
								</select>

								<label class="col-sm-2 form-label-700" style="text-align: right"><?php echo $cHARI_BACA?></label>
								<select name="HARI_BACA" class="col-sm-2 form-label-900"  onchange="FILTER_DATA('<?php echo $cFILTER_AREA?>', this.value, '<?php echo $cFILTER_PTGS?>')">
								<?php 
									echo "<option value=0  > Kosong</option>";
									$I=0;
									while($I<100){

										$I++;
										if($nFILTER_HARI==$I){
											echo "<option value=".$I. " selected='$aREC_TB_PEL[HARI_KUNJ]' >$I</option>";
										} else {
											echo "<option value=".$I. " >$I</option>";
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
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cHARI_BACA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cPTGS?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cRUTE_BACA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNO_URUT?></th>
													</tr>
												</thead>

												<tbody>
													<?php
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
															echo '<td style="color:'.$cTBL_LIST_DISPLAY_COLOR.';" align="center">'.$aREC_TB_PEL['HARI_KUNJ']."</td>";
															echo '<td style="color:'.$cTBL_LIST_DISPLAY_COLOR.';">'.$aREC_TB_PEL['PETUGAS']."</td>";
															echo '<td style="color:'.$cTBL_LIST_DISPLAY_COLOR.';">'.$aREC_TB_PEL['KODE_RUTE']."</td>";
															echo '<td style="color:'.$cTBL_LIST_DISPLAY_COLOR.';">'.$aREC_TB_PEL['NO_URUT']."</td>";
														echo '</tr>';
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
			<script type="text/javascript" charset="utf-8">
				$(document).ready(function() {
					$('#example').dataTable( {
						"bProcessing": true,
						"bServerSide": true,
						"sAjaxSource": "test/ssp.php"
					} );
				} );
			</script>

		</body>
	</html>
