<?php
//	bm_rep_plan.php
//	Laporan baca meter

//	include "preloader.php";
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('RU01','Laporan Meter Belum Baca');

	$cTANGGAL 		= S_MSG('RQ03','Tanggal');
	$cNAMA_TBL 		= S_MSG('PQ03','Nama Pelanggan');
	$cALAMAT 		= S_MSG('PQ04','Alamat');
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
	$cSTATUS		= S_MSG('RQ18','Status');
	$cKETERANGAN 	= S_MSG('PN10','Keterangan');
	$cPTGS			= S_MSG('RQ08','Petugas');

	$cAREA 			= S_MSG('PQ05','Area');

	$nADD = 0;
	$nUPD = 0;
	$q_DT_BACA=SYS_QUERY("select * from bm_dt_baca where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	while($a_DT_BACA =SYS_FETCH($q_DT_BACA)) {
		if($a_DT_BACA['IDPEL']!='N/A') {
			$qQUERY = SYS_QUERY("select IDPEL, TRF_TPSG, PETUGAS, KODE_RUTE, LAST_VISIT FROM bm_tb_plg where IDPEL ='$a_DT_BACA[IDPEL]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
			if(SYS_ROWS($qQUERY)==0) {
				$cQ_TB_PLG = "INSERT into bm_tb_plg ( `IDPEL`, `KODE_RUTE`, `PETUGAS`, `APP_CODE`, `LAST_VISIT`, `DATE_ENTRY` ) values ";
				$cQ_TB_PLG .= "('". $a_DT_BACA['IDPEL']."', '". $a_DT_BACA['KODE_RBM']."', '". $a_DT_BACA['PETUGAS']."', '". $cFILTER_CODE  . "', '" . $a_DT_BACA['TGL_BACA']. "', '". $NOW. "'), ";
				$q_ADDR=SYS_QUERY($cQ_TB_PLG);
				$nADD++;
			} else {
				$cUPD_P="update bm_tb_plg set KODE_RUTE='$a_DT_BACA[KODE_RBM]', PETUGAS='$a_DT_BACA[PETUGAS]', LAST_VISIT='$a_DT_BACA[TGL_BACA]' where IDPEL='$a_DT_BACA[IDPEL]' and APP_CODE='$cFILTER_CODE'";
				$q_UPDP=SYS_QUERY($cUPD_P);
				$nUPD++;
			}
		}
	}
	echo 'add : '.$nADD.'<br>';
	echo 'upd : '.$nUPD.'<br>';
	exit;
	
	$r_TB_AREA	= SYS_FETCH(SYS_QUERY("select * from tb_area where APP_CODE='$cFILTER_CODE' and DELETOR=''"));
	$cFILTER_AREA=$r_TB_AREA['KODE_AREA'];
	if (isset($_GET['_a'])) {
		$cFILTER_AREA=$_GET['_a'];
	}
//	var_dump ($cFILTER_AREA); die; exit();

	$r_TB_CATTER	= SYS_FETCH(SYS_QUERY("select * from bm_tb_catter1 where APP_CODE='$cFILTER_CODE' and DELETOR=''"));
	$c_PTGS=$r_TB_CATTER['KODE_CATTER'];
	$cFILTER_PTGS=" and PETUGAS='".$r_TB_CATTER['KODE_CATTER']."'";
	if($cFILTER_AREA=='') {
		$cFILTER_PTGS='';
		$c_PTGS='';
	}
	if (isset($_GET['_c'])) {
		if($_GET['_c']=='') {
			$cFILTER_PTGS='';
		} else {
			$cFILTER_PTGS=" and PETUGAS='".$_GET['_c']."'";
		}
		$c_PTGS=$_GET['_c'];
	} else {
		$cFILTER_PTGS='';
	}

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

	$cHELP_BOX	= S_MSG('RU20','Help Laporan Meter Belum Baca');
	$cHELP_1	= S_MSG('RU21','Ini adalah modul untuk menampilkan Daftar pelanggan yang belum di baca untuk periode tertentu');
	$cHELP_2	= S_MSG('RU22','Laporan ini berguna untuk mengetahui pelanggan mana saja yang belum dilakukan pembacaan meter');
	$cHELP_3	= S_MSG('RU23','Untuk menampilkan data dengan area tertentu, pilih dropdown area');
	$cHELP_4	= S_MSG('RQ23','Untuk menampilkan data dengan petugas tertentu, pilih dropdown petugas');
	$cHELP_5	= S_MSG('RQ24','Untuk menampilkan data dengan kode rute tertentu, pilih dropdown kode RBM');
	$cHELP_6	= S_MSG('RQ25','Untuk menampilkan data tanggal tertentu, klik di bagian tanggal mulai dan tanggal akhir.');
	$cHELP_7	= S_MSG('RQ26','Untuk meng export laporan ke excel, klik tombol EXCEL.');

	$cSTART_DATE = S_PARA('BM_START_DATE', date('Y-01-01'));
	$cBM_TB_PEL = "select A.UNITUP, A.IDPEL, A.NAMA_PEL, A.ALAMAT, A.KODE_TARIF, A.DAYA, A.NOMOR_KWH, A.MERK_KWH, B.PETUGAS, B.LAST_VISIT, B.KODE_RUTE, B.TRF_TPSG, bm_tb_catter1.NAMA_CATTER FROM bm_tb_pel A 
		inner join (select IDPEL, TRF_TPSG, PETUGAS, KODE_RUTE, LAST_VISIT FROM bm_tb_plg where LAST_VISIT<'$cSTART_DATE' $cFILTER_PTGS $nFILTER_RBM) B ON B.IDPEL=A.IDPEL 
		left join bm_tb_catter1 on B.PETUGAS=bm_tb_catter1.KODE_CATTER
		WHERE A.UNITUP='$cFILTER_AREA' AND A.APP_CODE='$cFILTER_CODE' AND A.DELETOR=''";
//		var_dump($cBM_TB_PEL);	exit();
	$qQUERY=mysql_query($cBM_TB_PEL);

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

								<label class="col-sm-1 form-label-700" for="field-4"><?php echo $cAREA?></label>
								<select name="PILIH_AREA" class="col-sm-3 form-label-900" onchange="FILTER_DATA(this.value, '<?php echo $c_PTGS?>', '<?php echo $c_RBM?>')">
								<?php 
									$q_TB_AREA=SYS_QUERY("select * from tb_area where APP_CODE='$cFILTER_CODE' and DELETOR=''");
									echo "<option value=''  > All</option>";
									while($a_TB_AREA =SYS_FETCH($q_TB_AREA)){

										if($a_TB_AREA['KODE_AREA']==$cFILTER_AREA){
											echo "<option value='".$a_TB_AREA['KODE_AREA']. "' selected='$a_TB_AREA[NAMA_AREA]' >$a_TB_AREA[NAMA_AREA]</option>";
										} else {
											echo "<option value='".$a_TB_AREA['KODE_AREA']. "'  >$a_TB_AREA[NAMA_AREA]</option>";
										}
									}
								?>
								</select>

								<label class="col-sm-3 form-label-700"> </label>
								<label class="col-sm-2 form-label-700" style="text-align: right"><?php echo $cPTGS?></label>
								<select name="PILIH_AREA" class="col-sm-2 form-label-900" value="<?php echo $c_PTGS?>" onchange="FILTER_DATA('<?php echo $cFILTER_AREA?>', this.value, '<?php echo $c_RBM?>')">
								<?php 
									$qCATTER1=SYS_QUERY("select * from bm_tb_catter1 where KODE_AREA = '$cFILTER_AREA' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
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

								<label class="col-sm-1 form-label-700"><?php echo $cRUTE_BACA?></label>
								<select name="HARI_BACA" class="col-sm-1 form-label-900"  onchange="FILTER_DATA('<?php echo $cFILTER_AREA?>', '<?php echo $c_PTGS?>', this.value)">
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
								</select>	<div class="clearfix"></div>

								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="<?php echo S_PARA('_BIG_REPORT_CLASS','table-responsive')?> data-pattern='priority-columns'">
												<table id="example-4" class="<?php echo S_PARA('_DISP_REPORT_ABSEN','display')?>">
	<!--                                            <table cellspacing="0" id="myTable" class="table table-small-font table-bordered table-striped">	-->
													<thead>
														<tr>
<!--															<th style="</?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>	-->
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cAREA?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTANGGAL?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNMR_METER?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cPTGS?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cRUTE_BACA?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cID_PELANGGAN?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_TBL?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cALAMAT?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTARIF?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cDAYA?></th>
														</tr>
													</thead>

													<tbody>
														<?php
															while($r_BM_DT_BACA=SYS_FETCH($qQUERY)) {
																	echo '<tr>';
//																	echo '<td class=""><div class="star"><i class="fa fa-user icon-xs icon-default"></i></div></td>';
//																	echo '<td style="width: 1px;"></td>';
																	echo "<td><span>".$r_BM_DT_BACA['UNITUP']."  </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['LAST_VISIT']."  </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['NOMOR_KWH']." </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['NAMA_CATTER']." </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['KODE_RUTE']." </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['IDPEL']." </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['NAMA_PEL']." </span></td>";
																	echo "<td><span>".$r_BM_DT_BACA['ALAMAT']." </span></td>";
																	if($r_BM_DT_BACA['TRF_TPSG']!='') {
																		echo "<td><span>".$r_BM_DT_BACA['TRF_TPSG']." </span></td>";
																	} else {
																		echo "<td><span>".$r_BM_DT_BACA['KODE_TARIF']." </span></td>";
																	}
																	echo "<td align='right'><span>".number_format($r_BM_DT_BACA['DAYA'])." </span></td>";
																	echo '</tr>';
															}
														?>
													</tbody>
												</table>

											</div><br>
											<div class="text-left">
												<input type="button" class="col-sm-2 btn btn-info btn-lg" value="Excel" onclick=window.location.href='?_a=<?php echo $cFILTER_AREA?>&_c=<?php echo $c_PTGS?>&_h=<?php echo $c_RBM?>&_e=EXCEL'>
											</div>	<div class="clearfix"></div><br>
										</div>
									</div>
								</div>
							</section>
						</div>
<?php /* var_dump($cBM_TB_PEL); */	?>
					</section>
				</section>
				<?php	include "scr_chat.php";	?>
			</div>
			<?php	require_once("js_framework.php");	?>
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
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

case 'EXCEL' :
	header('Location:bm_xls_bc_mtr.php?_a='.$cFILTER_AREA.'&_c='.$c_PTGS.'&_r='.$c_RBM);
	mysql_close($DB2);
}
?>
<script>
function FILTER_DATA(p_AREA, p_CATER, p_HARI) {
	window.location.assign("?_a="+p_AREA + "&_c="+p_CATER + "&_h="+p_HARI);
}

</script>

