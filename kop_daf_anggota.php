<?php
//	kop_daf_anggota.php
//	Daftar Anggota ( koperasi)

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('CR01','Laporan Daftar Anggota');

	$cKODE_TBL 	= S_MSG('CB07','Kode Anggota');
	$cNAMA_TBL 	= S_MSG('CB03','Nama');
	$cALAMAT 	= S_MSG('F005','Alamat');
	$cKELOMPOK	= S_MSG('CB64','Kelompok');
	$cTIPE		= S_MSG('CB65','Tipe');

	$sPERIOD1	= date("Y-m-d");
	$nTGL1 		= substr($sPERIOD1,8,2);

	$cFILTER_GRUP='';
	if (isset($_GET['_g'])) {
		$cFILTER_GRUP=$_GET['_g'];
	}

	$cFILTER_TIPE='';
	if (isset($_GET['_t'])) {
		$cFILTER_TIPE=$_GET['_t'];
	}

	$cHELP_BOX	= S_MSG('CR11','Help Laporan Daftar Anggota');
	$cHELP_1	= S_MSG('CR12','Ini adalah modul untuk menampilkan daftar Anggota berdasarkan kriteria-kriteria tertentu');
	$cHELP_2	= S_MSG('CR13','Untuk menampilkan anggota dengan jenis kelamin tertentu, pilih dropdown jenis kelamin/gender');

	$cSQLCOMMAND= "SELECT tb_member1.KD_MMBR as MASTER_CODE, tb_member1.NM_DEPAN, tb_member1.ALAMAT, tb_member1.GRUP_MEM , tb_member1.KLPK_ANGGT, tb_member1.TIPE_ANGGT, tb_member1.APP_CODE, tb_member1.DELETOR, grouplgn.* , tipe_otl.* from tb_member1
		LEFT JOIN grouplgn ON tb_member1.KLPK_ANGGT=grouplgn.KODE_GRP
		LEFT JOIN tipe_otl ON tb_member1.TIPE_ANGGT=tipe_otl.KODETIPE
		where tb_member1.APP_CODE='$cFILTER_CODE' and tb_member1.DELETOR=''";
	if ($cFILTER_GRUP!='') {
		$cSQLCOMMAND.= " and md5(KLPK_ANGGT)='$cFILTER_GRUP'";
	}
	if ($cFILTER_TIPE!='') {
		$cSQLCOMMAND.= " and md5(TIPE_ANGGT)='$cFILTER_TIPE'";
	}
	$cSQLCOMMAND.= " group by tb_member1.KD_MMBR";
	$qQUERY=SYS_QUERY($cSQLCOMMAND);

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<body class=" sidebar-collapse">
			<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">
				<div class="page-sidebar  collapseit ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
						<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
					</div>
					<div class="project-info"></div>
				</div>
				<section id="main-content" class="sidebar_shift">
					<section class="wrapper main-wrapper" style=''>
						<div class="clearfix"></div>

						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left"><?php echo $cHEADER?></h2>
									  <div class="actions panel_actions pull-right">
											<i class="box_setting fa fa-question" data-toggle="modal" href="#section-settings">Help</i>
	<!--
											<i class="box_toggle fa fa-chevron-down"></i>
											<a href="#help_gl_journal" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											<i class="box_close fa fa-times"></i>
	-->
									  </div>
								</header>

								<label class="col-sm-2 form-label-700" for="field-4"><?php echo S_MSG('F009','Kelompok')?></label>
								<select name="PILIH_GROUP" class="col-sm-3 form-label-900" onchange="FILTER_DATA(this.value, '<?php echo $cFILTER_TIPE?>')">
								<?php 
									$REC_GROUP=SYS_QUERY("select * from grouplgn where APP_CODE='$cFILTER_CODE' and DELETOR=''");
									echo "<option value=''  > All</option>";
									while($aREC_GR_DATA=SYS_FETCH($REC_GROUP)){

										if(md5($aREC_GR_DATA['KODE_GRP'])==$cFILTER_GRUP){
											echo "<option value='".md5($aREC_GR_DATA['KODE_GRP']). "' selected='$REC_EDIT[KLPK_ANGGT]' >$aREC_GR_DATA[NAMA_GRP]</option>";
										} else {
											echo "<option value='".md5($aREC_GR_DATA['KODE_GRP']). "'  >$aREC_GR_DATA[NAMA_GRP]</option>";
										}

									}
								?>
								</select>

								<label class="col-sm-2 form-label-700"> </label>
								<label class="col-sm-2 form-label-700" for="field-4"><?php echo S_MSG('F008','Tipe')?></label>
								<select name="PILIH_GROUP" class="col-sm-3 form-label-900" onchange="FILTER_DATA('<?php echo $cFILTER_GRUP?>', this.value)">
								<?php 
									$REC_TIPE=SYS_QUERY("select * from tipe_otl where APP_CODE='$cFILTER_CODE' and DELETOR=''");
									echo "<option value=''  > All</option>";
									while($aREC_GR_DATA=SYS_FETCH($REC_TIPE)){

										if($aREC_GR_DATA['KODETIPE']==$cFILTER_TIPE){
											echo "<option value='".md5($aREC_GR_DATA['KODETIPE']). "' selected='$REC_EDIT[KLPK_ANGGT]' >$aREC_GR_DATA[NAMATIPE]</option>";
										} else {
											echo "<option value='".md5($aREC_GR_DATA['KODETIPE']). "'  >$aREC_GR_DATA[NAMATIPE]</option>";
										}

									}
								?>
								</select>

								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<table id="example" class="<?php echo S_PARA('_DISP_REPORT_CLASS','display table table-hover table-condensed')?>">
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>	
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cALAMAT?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKELOMPOK?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTIPE?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_ANGGOTA=SYS_FETCH($qQUERY)) {
															echo '<tr>';
																echo '<td class=""><div class="star"><i class="fa fa-user icon-xs icon-default"></i></div></td>';
	//															echo '<td style="width: 1px;"></td>';
																echo "<td><span>".$aREC_ANGGOTA['MASTER_CODE']."  </span></td>";
																echo "<td><span>".decode_string($aREC_ANGGOTA['NM_DEPAN'])." </span></td>";
																echo "<td><span>".decode_string($aREC_ANGGOTA['ALAMAT'])." </span></td>";
																echo "<td><span>".decode_string($aREC_ANGGOTA['NAMA_GRP'])." </span></td>";
																echo "<td><span>".$aREC_ANGGOTA['NAMATIPE']." </span></td>";
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

			<div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>
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
	mysql_close($DB2);
?>
<script>
function FILTER_DATA(pGROUP, pTIPE) {
	window.location.assign("?_g="+pGROUP + "&_t="+pTIPE);
}

</script>

