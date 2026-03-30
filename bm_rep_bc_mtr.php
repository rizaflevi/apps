<?php
//	bm_rep_bc_mtr.php
//	Laporan baca meter

//	include "preloader.php";
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('RQ01','Laporan Hasil Baca Meter');

	$cTANGGAL 		= S_MSG('RQ03','Tanggal');
	$cTGL_JAM 		= S_MSG('RQ09','Tanggal & Jam Baca');
	$cNAMA_TBL 		= S_MSG('PQ03','Nama Pelanggan');
	$cALAMAT 		= S_MSG('PQ04','Alamat');
	$cALM_PERSIL	= S_MSG('PQ11','Alamat Persil');
	$cNMR_METER 	= S_MSG('RQ04','Nomor Meter');
	$cSISA_TOKEN 	= S_MSG('RQ05','Sisa Token');
	$cLATTITUDE		= S_MSG('RQ06','Lattitude');
	$cLONGITUDE		= S_MSG('RQ07','Longitude');
	$cKONDISI_RMH	= S_MSG('RQ11','Kondisi Rumah');
	$cSEGEL_TEL		= S_MSG('RQ12','Segel Telinga');
	$cSEGEL_TERM	= S_MSG('RQ13','Segel Terminal');
	$cLAM_INDIKATOR	= S_MSG('RQ14','Lampu Indikator');
	$cRUTE_BACA		= S_MSG('RQ15','Kode RBM');
	$cID_PELANGGAN	= S_MSG('RQ16','IDPEL');
	$cTARIF			= S_MSG('RQ17','Tarif');
	$cDAYA			= S_MSG('PQ07','Daya');
	$cMCB_TRPSG		= S_MSG('RQ40','MCB Terpasang');
	$cSTATUS		= S_MSG('RQ18','Status');
	$cKETERANGAN 	= S_MSG('PN10','Keterangan');
	$cPTGS			= S_MSG('RQ08','Petugas');

	$cAREA 			= S_MSG('PQ05','Area');

	$dPERIOD1=date("Y-m-d");
	$dPERIOD2=date("Y-m-d");

	if (isset($_GET['_t1'])) {
		$dPERIOD1=$_GET['_t1'];
	}

	if (isset($_GET['_t2'])) {
		$dPERIOD2=$_GET['_t2'];
	}

	$c_FILTER_RAYON = '';
	$q_USER_RAYON=SYS_QUERY("select * from tb_user where USER_CODE='$_SESSION[gUSERCODE]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$a_USER_RAYON =SYS_FETCH($q_USER_RAYON);
	$c_FILTER_RAYON = " and KODE_RUTE='$a_USER_RAYON[USER_UNIT]'";
	$c_RAYON = $a_USER_RAYON['USER_UNIT'];
	
	$cFILTER_AREA='';
	if ($c_RAYON=='') {
		$r_TB_AREA	= SYS_FETCH(SYS_QUERY("select * from tb_area where APP_CODE='$cFILTER_CODE' and DELETOR=''"));
		$c_AREA=$r_TB_AREA['KODE_AREA'];
		$cFILTER_AREA=" and UNITUP='". $r_TB_AREA['KODE_AREA'] . "'";
		if (isset($_GET['_a'])) {
			if($_GET['_a']=='') {
				$cFILTER_AREA='';
			} else {
				$cFILTER_AREA=" and UNITUP='". $_GET['_a'] . "'";
			}
			$c_AREA=$_GET['_a'];
		}
	} else {
		$c_AREA=$c_RAYON;
	}
//	var_dump($c_RAYON); exit;
//	$cCEK_DOBEL 	= "select REC_ID, TGL_BACA, SISA_TOKEN, COUNT(*) c from bm_dt_baca where TGL_BACA>'2017-06-01' group by TGL_BACA, IDPEL, APP_CODE HAVING c > 1 order by TGL_BACA desc";

//	$r_TB_CATTER	= SYS_FETCH(SYS_QUERY("select * from bm_tb_catter1 where APP_CODE='$cFILTER_CODE' and DELETOR=''"));
	$nFILTER_RBM='';
	$c_RBM='';
	if (isset($_GET['_h'])) {
		if($_GET['_h']=='') {
			$nFILTER_RBM='';
		} else {
			$nFILTER_RBM=" and KODE_RUTE='". $_GET['_h'] ."'";
		}
		$c_RBM=$_GET['_h'];
	}

	$c_PTGS='';
	$cFILTER_PTGS='';
	if (isset($_GET['_c'])) {
		if($_GET['_c']=='') {
			$cFILTER_PTGS='';
		} else {
			$cFILTER_PTGS=" and A.PETUGAS='".$_GET['_c']."'";
		}
		$c_PTGS=$_GET['_c'];
	}

	$cHELP_BOX	= S_MSG('RQ20','Help Laporan Baca Meter');
	$cHELP_1	= S_MSG('RQ21','Ini adalah modul untuk menampilkan Laporan Baca Meter dengan filtering data tertentu');
	$cHELP_2	= S_MSG('RQ22','Untuk menampilkan data dengan area tertentu, pilih dropdown area');
	$cHELP_3	= S_MSG('RQ23','Untuk menampilkan data dengan petugas tertentu, pilih dropdown petugas');
	$cHELP_4	= S_MSG('RQ24','Untuk menampilkan data dengan kode rute tertentu, pilih dropdown kode RBM');
	$cHELP_5	= S_MSG('RQ25','Untuk menampilkan data tanggal tertentu, klik di bagian tanggal mulai dan tanggal akhir.');
	$cHELP_6	= S_MSG('RQ26','Untuk meng export laporan ke excel, klik tombol EXCEL.');
	$cHELP_7	= S_MSG('RQ27','Untuk men download foto, klik tombol DOWNLOAD.');

	$cSQLCOMMAND= "SELECT A.TGL_BACA, A.IDPEL, A.SISA_TOKEN, A.LATTI, A.LONGI, A.PETUGAS, A.KODE_RBM, A.MCB, A.KONDISI_RMH, A.SEGEL_TLG, A.SEGEL_TER, A.LAMPU_INDI, A.NOTE, B.TRF_TPSG, B.ALAMAT_PLG, C.IDPEL, C.UNITUP, C.NOMOR_KWH, C.NAMA_PEL, C.KODE_TARIF, C.DAYA, C.ALAMAT
		from bm_dt_baca A
		inner join (select IDPEL, ALAMAT_PLG, TRF_TPSG, PETUGAS, KODE_RUTE, LAST_VISIT FROM bm_tb_plg where APP_CODE='$cFILTER_CODE' and DELETOR='' ) B ON B.IDPEL=A.IDPEL 
		inner join (select UNITUP, IDPEL, NOMOR_KWH, NAMA_PEL, ALAMAT, KODE_TARIF, DAYA FROM bm_tb_pel where APP_CODE='$cFILTER_CODE' and DELETOR='' $cFILTER_AREA) C ON C.IDPEL=A.IDPEL 
		where A.APP_CODE='$cFILTER_CODE' and A.DELETOR='' $nFILTER_RBM and
			left(A.TGL_BACA,10)>='".$dPERIOD1."' and left(A.TGL_BACA,10)<='".$dPERIOD2."' $cFILTER_PTGS order by A.TGL_BACA desc";

//	var_dump($cSQLCOMMAND);	exit();
	$qQUERY=SYS_QUERY($cSQLCOMMAND);

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	$cRPT_BODY_CLASS = S_PARA('_RPT_BODY_CLASS','sidebar-collapse');

	$cACTION='';
	if (isset($_GET['_e'])) {
		$cACTION=$_GET['_e'];
	}

	APP_LOG_ADD($cHEADER, 'view');

switch($cACTION){
	default:
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<body class="<?php echo $cRPT_BODY_CLASS?>">
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

						<div class="col-xs-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left"><?php echo $cHEADER?></h2>
									 <div class="actions panel_actions pull-right">
										<i class="box_setting fa fa-question" data-toggle="modal" href="#section-settings">Help</i>
									 </div>
								</header>

								<?php 
									if ($c_RAYON=='') {
										echo '<label class="col-sm-1 form-label-700" for="field-4">'. $cAREA.'</label>';
										echo '<select name="PILIH_AREA" class="col-sm-3 form-label-900" onchange="FILTER_DATA(this.value, ' . "'". $c_PTGS. "'". ', '. "'". $dPERIOD1 . "'".', ' . "'". $dPERIOD2 . "'".', ' . "'". $c_RBM . "'".')">';
										$q_TB_AREA=SYS_QUERY("select * from tb_area where APP_CODE='$cFILTER_CODE' and DELETOR=''");
										echo "<option value=''  > All</option>";
										while($a_TB_AREA =SYS_FETCH($q_TB_AREA)){

											if($a_TB_AREA['KODE_AREA']==$c_AREA){
												echo "<option value='".$a_TB_AREA['KODE_AREA']. "' selected='$a_TB_AREA[NAMA_AREA]' >$a_TB_AREA[NAMA_AREA]</option>";
											} else {
												echo "<option value='".$a_TB_AREA['KODE_AREA']. "'  >$a_TB_AREA[NAMA_AREA]</option>";
											}
										}
										echo '</select><div class="clearfix"></div>';
									}
								?>

								<label class="col-sm-1 form-label-700" for="field-4"><?php echo $cTANGGAL?></label>
								<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $dPERIOD1?>" onchange="FILTER_DATA('<?php echo $c_AREA?>', '<?php echo $c_PTGS?>', this.value, '<?php echo $dPERIOD2?>', '<?php echo $c_RBM?>')">

								<label class="col-sm-1 form-label-700" style="text-align: right"><?php echo S_MSG('RS14','s/d')?></label>
								<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $dPERIOD2?>" onchange="FILTER_DATA('<?php echo $c_AREA?>', '<?php echo $c_PTGS?>', '<?php echo $dPERIOD1?>', this.value, '<?php echo $c_RBM?>')"><br>
								<div class="clearfix"></div>

								<label class="col-sm-1 form-label-700"><?php echo $cRUTE_BACA?></label>
								<select name="HARI_BACA" class="col-sm-1 form-label-900"  onchange="FILTER_DATA('<?php echo $c_AREA?>', '<?php echo $c_PTGS?>', '<?php echo $dPERIOD1?>', '<?php echo $dPERIOD2?>', this.value)">
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

								<?php 
									echo '<label class="col-sm-2 form-label-700" style="text-align: right">' . $cPTGS.'</label>';
									echo '<select name="PILIH_PTGS" class="col-sm-2 form-label-900" value="' . $c_PTGS.'" onchange="FILTER_DATA(' . $c_AREA. ', this.value, ' . "'".$dPERIOD1."'". ', ' . "'".$dPERIOD2 ."'".', ' ."'". $c_RBM ."'".')">';
									$qCATTER1=SYS_QUERY("select * from bm_tb_catter1 where KODE_AREA = '$c_AREA' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
									echo "<option value=''  > All</option>";
									while($a_TB_CATTER =SYS_FETCH($qCATTER1)){

										if($a_TB_CATTER['KODE_CATTER']==$c_PTGS){
											echo "<option value='".$a_TB_CATTER['KODE_CATTER']. "' selected='$qQUERY[PETUGAS]' >$a_TB_CATTER[NAMA_CATTER]</option>";
										} else {
											echo "<option value='".$a_TB_CATTER['KODE_CATTER']. "'  >$a_TB_CATTER[NAMA_CATTER]</option>";
										}

									}
								?>
								</select><div class="clearfix"></div>

								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="<?php echo S_PARA('_BIG_REPORT_CLASS','table-responsive')?> data-pattern='priority-columns'">
											<table id="example-4" class="<?php echo S_PARA('_DISP_REPORT_ABSEN','display')?> nowrap">
<!--                                            <table cellspacing="0" id="myTable" class="table table-small-font table-bordered table-striped">	-->
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cAREA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTGL_JAM?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNMR_METER?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cSISA_TOKEN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cLATTITUDE?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cLONGITUDE?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cPTGS?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cRUTE_BACA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cID_PELANGGAN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cALAMAT?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cALM_PERSIL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTARIF?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cDAYA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cMCB_TRPSG?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKONDISI_RMH?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cSEGEL_TEL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cSEGEL_TERM?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cLAM_INDIKATOR?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKETERANGAN?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($r_BM_DT_BACA=SYS_FETCH($qQUERY)) {

																$qCATTER1=SYS_QUERY("select NAMA_CATTER from bm_tb_catter1 where KODE_CATTER = '$r_BM_DT_BACA[PETUGAS]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																$a_TB_CATTER =SYS_FETCH($qCATTER1);
																$q_AREA =SYS_QUERY("select NAMA_AREA from tb_area where KODE_AREA = '$r_BM_DT_BACA[UNITUP]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																$a_TB_AREA =SYS_FETCH($q_AREA);
																$q_KOND =SYS_QUERY("select NAMA_KOND from bm_tb_kond where KODE_KOND = '$r_BM_DT_BACA[KONDISI_RMH]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																$a_TB_KOND =SYS_FETCH($q_KOND);

																if ($c_AREA=='' or $r_BM_DT_BACA['UNITUP'] == $c_AREA) {
																	echo '<tr>';
																	echo "<td><span>".$r_BM_DT_BACA['UNITUP']."  </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['TGL_BACA']."  </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['NOMOR_KWH']." </span></td>";
																	echo "<td align='right'><span>".number_format($r_BM_DT_BACA['SISA_TOKEN'],2)." </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['LATTI']." </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['LONGI']." </span></td>";
																	echo "<td><span>".$a_TB_CATTER['NAMA_CATTER']." </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['KODE_RBM']." </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['IDPEL']." </span></td>";
																	echo "<td><span>".decode_string($r_BM_DT_BACA['NAMA_PEL'])." </span></td>";
																	echo "<td><span>".decode_string($r_BM_DT_BACA['ALAMAT'])." </span></td>";
																	echo "<td><span>".decode_string($r_BM_DT_BACA['ALAMAT_PLG'])." </span></td>";
																	if($r_BM_DT_BACA['TRF_TPSG']!='') {
																		echo "<td><span>".$r_BM_DT_BACA['TRF_TPSG']." </span></td>";
																	} else {
																		echo "<td><span>".$r_BM_DT_BACA['KODE_TARIF']." </span></td>";
																	}
																	echo "<td align='right'><span>".number_format($r_BM_DT_BACA['DAYA'])." </span></td>";
																	echo "<td align='right'><span>".$r_BM_DT_BACA['MCB']." </span></td>";
																	echo "<td><span>". $a_TB_KOND['NAMA_KOND'] ." </span></td>";
																	echo "<td><span>". ($r_BM_DT_BACA['SEGEL_TLG']==1 ? 'TIDAK LENGKAP' : 'Lengkap') ." </span></td>";
																	echo "<td><span>". ($r_BM_DT_BACA['SEGEL_TER']==1 ? 'TIDAK LENGKAP' : 'Lengkap') ." </span></td>";
																	echo "<td><span>". ($r_BM_DT_BACA['LAMPU_INDI']==1 ? 'TIDAK MENYALA' : 'Menyala') ." </span></td>";
																	echo "<td><span>".decode_string($r_BM_DT_BACA['NOTE'])." </span></td>";
																	echo '</tr>';
																}
														}
													?>
												</tbody>
											</table>

										</div><br>
										<div class="text-left">
											<input type="button" class="col-sm-2 btn btn-info btn-lg" value="Excel" onclick=window.location.href="?_a=<?php echo $c_AREA?>&_c=<?php echo $c_PTGS?>&_t1=<?php echo $dPERIOD1?>&_t2=<?php echo $dPERIOD2?>&_h=<?php echo $c_RBM?>&_e=EXCEL">
											<label class="col-sm-1 form-label-700"></label>
											<input type="button" class="col-sm-2 btn btn-info btn-lg" value="Download" onclick=window.location.href="?_a=<?php echo $c_AREA?>&_c=<?php echo $c_PTGS?>&_t1=<?php echo $dPERIOD1?>&_t2=<?php echo $dPERIOD2?>&_h=<?php echo $c_RBM?>&_e=DL">
										</div>	<div class="clearfix"></div><br>
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
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
<!--			<script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
-->
			<script src="assets/js/scripts.js" type="text/javascript"></script> 

			<div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_3?></p>
							<p><?php echo $cHELP_4?></p>	<p><?php echo $cHELP_5?></p>	<p><?php echo $cHELP_6?></p>
							<p><?php echo $cHELP_7?></p>
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
	mysql_close($DB2);	break;

case 'DL' :
	$files = array();
	while($r_BM_DT_BACA=SYS_FETCH($qQUERY)) {
		$c_files = 'upload/'.$r_BM_DT_BACA['IDPEL']. '_' . substr($r_BM_DT_BACA['TGL_BACA'],0,10).'.jpg';
		array_push($files, $c_files);
	}
	$zipname = 'foto.zip';
	unlink($zipname);
	$zip = new ZipArchive;
	$zip->open($zipname, ZipArchive::CREATE);
	foreach ($files as $file) {
		if(file_exists($file)) {
			$zip->addFile($file);
		}
	}
	$zip->close();

	header('Content-Type: application/zip');
	header('Content-disposition: attachment; filename='.$zipname);
	header('Content-Length: ' . filesize($zipname));
	readfile($zipname);

	mysql_close($DB2);	break;

case 'EXCEL' :
	header('Location:bm_xls_bc_mtr.php?_a='.$c_AREA.'&_t1='.$dPERIOD1.'&_t2='.$dPERIOD2.'&_c='.$c_PTGS.'&_r='.$nFILTER_RBM);
	mysql_close($DB2);
}
?>
<script>
function FILTER_DATA(p_AREA, p_CATER, p_TGL1, p_TGL2, p_HARI) {
	window.location.assign("?_a="+p_AREA + "&_c="+p_CATER + "&_t1="+p_TGL1 + "&_t2="+p_TGL2 + "&_h="+p_HARI);
}

</script>

