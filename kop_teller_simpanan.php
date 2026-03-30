<?php
/* -------------------------------------------------------------------
	kop_teller_simpanan.php
	memasukkan data setoran tabungan di teller
*/

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER 	= S_MSG('KS31','Transaksi Simpanan');

	$cQUERY="SELECT trm_tab1.SAVE_ACT, trm_tab1.KD_TELLER, trm_tab1.SAVE_DATE, trm_tab1.NO_VOUCHER, trm_tab1.SV_AMOUNT, trm_tab1.SAVE_NOTE, trm_tab1.REC_NO, ";
	$cQUERY.=" tr_save1.SAVE_CODE, tr_save1.SAVE_ACT, tr_save1.KD_MMBR, tb_member1.KD_MMBR, tb_member1.NM_DEPAN, ";
	$cQUERY.=" tab_simp.KODE_SIMPN, tab_simp.NAMA_SIMPN from trm_tab1 ";
	$cQUERY.=" left join tr_save1 ON trm_tab1.SAVE_ACT=tr_save1.SAVE_ACT ";
	$cQUERY.=" left join tb_member1 ON tr_save1.KD_MMBR=tb_member1.KD_MMBR ";
	$cQUERY.=" left join tab_simp ON tr_save1.SAVE_CODE=tab_simp.KODE_SIMPN ";
	$cQUERY.=" where trm_tab1.APP_CODE='$cFILTER_CODE' and trm_tab1.DELETOR=''";
//	die ($cQUERY);
	$q_RTAB=SYS_QUERY($cQUERY) or die ('Error in query.' .mysql_error());
	$cNO_REK	= S_MSG('KK20','Nomor Rekening');
	$cJN_SIM	= S_MSG('KK21','Jenis Simpanan');
	$cKD_ANG 	= S_MSG('CB07','Kode Anggota');
	$cNAMA_ANG 	= S_MSG('F004','Nama');
	$cCATATAN 	= S_MSG('KB64','Catatan');
	$cTANGGAL 	= S_MSG('KB12','Tanggal');
	$cNOMINAL 	= S_MSG('KB13','Jumlah Setoran');
	$cVOUCHER 	= S_MSG('KB14','Nomor Voucher');
	$cTAMBAH	= S_MSG('KS33','Tambah Setoran Simpanan');
	$cPREVIEW	= S_MSG('KB08','Preview Setoran Simpanan');
	
	$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');
	$cMSG_EXIST	= S_MSG('KB05','Nomor Rekening Simpanan dan nomor voucher sudah ada');
	$cNOT_SIMP 	= S_MSG('KB07','Jenis Rekening Simpanan tidak ditemukan');
	$cNOT_MEMBER = S_MSG('KB09','Kode Anggota tidak ditemukan');
	$cNOT_FOUND	= S_MSG('KB06','Nomor Rekening tidak ditemukan');

	$cSAVE	= S_MSG('F301','Save');
	$cCLOSE	= S_MSG('F302','Close');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('KB42','Help Transaksi Simpanan');
		$cHELP_1	= S_MSG('KB43','Ini adalah modul untuk memasukkan data Transaksi Simpanan oleh anggota / nasabah');
		$cHELP_2	= S_MSG('KB44','Anggota menyetorkan simpanan dengan menyerahkan slip setoran kepada Teller');
		$cHELP_3	= S_MSG('KB45','Untuk memasukkan data Transaksi Simpanan baru, klik tambah Simpanan / add new');
		$cHELP_4	= S_MSG('KB46','Pertama akan ditampilkan daftar transaksi setoran simpanan. Tampilan daftar ini akan muncul apabila user mempunyai previllage / kewenangan untuk menampilkan daftar. Apabila tidak diberi kewenangan, maka user akan langsung ko form entry saja, tanpa bisa melihat daftar transasksi yang sudah pernah dimasukkan.');

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
									<h1 class="title pull-left"><?php echo $cHEADER?></h1>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>
												 <a href="?_a=<?php echo md5('ADD_NEW')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_kop_teller_simpanan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cVOUCHER?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNO_REK?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cJN_SIM?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTANGGAL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKD_ANG?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_ANG?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNOMINAL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cCATATAN?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_TRM_TAB1=SYS_FETCH($q_RTAB)) {
														echo '<tr>';
//															die ($aREC_TRM_TAB1['SV_AMOUNT']);
															echo '<td class=""><div class="star"><i class="fa fa-money icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TRM_TAB1['REC_NO'])."'>".$aREC_TRM_TAB1['NO_VOUCHER']."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TRM_TAB1['REC_NO'])."'>".$aREC_TRM_TAB1['SAVE_ACT']."</a></span></td>";
															echo '<td>'.$aREC_TRM_TAB1['NAMA_SIMPN'].'</td>';
															echo '<td>'.date("d-M-Y", strtotime($aREC_TRM_TAB1['SAVE_DATE'])).'</td>';
															echo '<td>'.$aREC_TRM_TAB1['KD_MMBR'].'</td>';
															echo '<td>'.$aREC_TRM_TAB1['NM_DEPAN'].'</td>';
															echo '<td align="right">'.number_format($aREC_TRM_TAB1['SV_AMOUNT']).'</td>';
															echo '<td>'.$aREC_TRM_TAB1['SAVE_NOTE'].'</td>';
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

			<div class="modal" id="help_kop_teller_simpanan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
	SYS_DB_CLOSE($DB2);	break;

	case md5('ADD_NEW'):
		$cHELP_BOX	= S_MSG('KB52','Help Tambah Transaksi Simpanan');
		$cHELP_1	= S_MSG('KB53','Ini adalah modul untuk memasukkan data Transaksi Simpanan baru oleh petugas Teller');
		$cHELP_2	= S_MSG('KB54','Masukkan data yang diperlukan sesuai formulir setoran yang sudah diisi oleh anggota/nasabah');
		$cHELP_3	= S_MSG('KB55','Setelah selesai klik tombol SAVE untuk menyimpan perubahan yang telah dilakukan');

?>
	<!DOCTYPE html>
	<html class=" ">
		<body onload="Kop_Teller_Simpanan_focus();">  
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
									  <h2 class="title"><?php echo $cTAMBAH?></h2>
								</div>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>
												<a href="#help_kop_teller_simpanan_add" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
										<form name="Add_New_record" action ="?_a=tambah" method="post">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNO_REK?></label>
												<input type="text" class="col-sm-3 form-label-900" name="ADD_SAVE_ACT" id="ADD_SAVE_ACT" onblur="Disp_Rek_Tab(this.value)"><br><br>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNAMA_ANG?></label>
												<input type="text" class="col-sm-5 form-label-900" name='ADD_NM_MMBR' id="NAMA_ANGGOTA" disabled="disabled"><br><br>

												<label class="col-sm-4 form-label-700" for="field-5"><?php echo $cVOUCHER?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_NO_VOUCHER' id="f_VOUCHER"><br><br>

												<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-2 form-label-900" data-mask="date" name='ADD_SAVE_DATE' id="field-2" value="<?php echo date('d/m/Y')?>" disabled="disabled"><br><br>

												<label class="col-sm-4 form-label-700" for="field-7"><?php echo $cNOMINAL?></label>
												<input type="text" class="col-sm-2 form-label-900" data-mask="fdecimal" name='ADD_SV_AMOUNT' id="field-2"><br><br>

												<label class="col-sm-4 form-label-700" for="field-7"><?php echo $cCATATAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_SAVE_NOTE' id="field-2"><br><br><br>
											</div>

											<div class="clearfix"></div>
											<div class="text-left">
												<input type="submit" id="SAVE_BUTTON" class="btn btn-success" value=<?php echo $cSAVE?> disabled="disabled">	
												<input type="button" class="btn" value=<?php echo $cCLOSE?> onclick=self.history.back()>
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

			<div class="modal" id="help_kop_teller_simpanan_add" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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

			<div class="modal" id="save_kop_teller_simpanan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cTAMBAH?></h4>
						</div>
						<div class="modal-body">

							<form name="ADDSIMP" action ="?_a=tambah" method="post">
								<div class="form-group">
									<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNO_REK?></label>
									<div class="col-sm-8">
										<label class="col-sm-4 form-label-700" id="field_1"><script>document.getElementById("NO_REK").value </script></label>
									</div>
								</div>
							</form>

						</div>
						<div class="modal-footer">
							<input type="submit" id="SAVE_BUTTON" class="btn btn-primary" value=<?php echo $cSAVE?> disabled="disabled">
							<input type="button" class="btn" value=<?php echo S_MSG('F302','Close')?> onclick=self.history.back()>
						</div>
					</div>
				</div>
			</div>

		</body>
	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case md5('up_d4t3'):

	$cHELP_BOX	= S_MSG('KB5A','Help Edit Transaksi Simpanan');
	$cHELP_1	= S_MSG('KB5B','Ini adalah modul untuk merubah data Transaksi Simpanan yang telah dimasukkan oleh petugas Teller');
	$cHELP_2	= S_MSG('KB5C','Pertama akan di tampilkan data Transaksi Simpanan, klik pada field yang mau di edit');
	$cHELP_3	= S_MSG('KB5D','Field No. Rekening, Jenis Simpanan, Kode, Nama dan Alamat anggota tidak bisa dirubah');
	$cHELP_4	= S_MSG('KB5E','Data yang bisa dirubah adalah No. Voucher, Tanggal, Jumlah Setoran dan Catatan / keterangan tambahan');
	$cHELP_5	= S_MSG('KB5F','Setelah selesai klik tombol SAVE intuk menampilkan data secara lengkap untuk persetujuan penyimpanan');

	$cQUERY="select SAVE_ACT, SV_AMOUNT, SAVE_DATE, NO_VOUCHER, SAVE_NOTE, REC_NO from trm_tab1 where md5(trm_tab1.REC_NO)='".$_GET['_r']."'";
	$qQUERY=SYS_QUERY($cQUERY) or die ('Error in query.' .mysql_error().'==> '.$cQUERY);
	$aREC_UPD_TRM_TAB1=SYS_FETCH($qQUERY);
	if(SYS_ROWS($qQUERY)==0){
		header('location:kop_teller_simpanan.php');
	}
	
	$cQUERY="select SAVE_ACT, SAVE_CODE, KD_MMBR from tr_save1 where SAVE_ACT='$aREC_UPD_TRM_TAB1[SAVE_ACT]' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$qCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($qCEK_KODE)==0){
		$cERR = $cNOT_FOUND.' => '.$aREC_UPD_TRM_TAB1['SAVE_ACT'];
		echo "<script> alert('$cERR');	window.history.back();	</script>";
		return;
	}
	$aREC_NOMOR_REK_TAB = SYS_FETCH($qCEK_KODE);

	$cQUERY="select KODE_SIMPN, NAMA_SIMPN from tab_simp where KODE_SIMPN='$aREC_NOMOR_REK_TAB[SAVE_CODE]' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$qCEK_JENIS=SYS_QUERY($cQUERY) or die ('Error in query.' .mysql_error().' ==> '.$cQUERY);
/*	if(SYS_ROWS($qCEK_JENIS)==0){
		echo "<script> alert('$cNOT_SIMP');
		window.history.back();
		</script>	";
		return;
	}	*/
	$aREC_JENIS_SIMPANAN = SYS_FETCH($qCEK_JENIS);

	$cQUERY="select KD_MMBR, NM_DEPAN, ALAMAT from tb_member1 where KD_MMBR='$aREC_NOMOR_REK_TAB[KD_MMBR]' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$qCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($qCEK_KODE)==0){
		echo "<script> alert('$cNOT_SIMP');
		window.history.back();
		</script>	";
//		return;
	}
	$aREC_TB_MEMBER1 = SYS_FETCH($qCEK_KODE);

//	die ($aREC_UPD_TRM_TAB1['SAVE_CODE']);
?>
	<!DOCTYPE html>
	<html class=" ">
		<body onload="Kop_Teller_Upd_Simpanan_focus();">  
			<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">
				
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper">
						<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
					</div>
					<div class="project-info">		</div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">

								<div class="pull-left">
									  <h2 class="title"><?php echo S_MSG('KS32','Edit Setoran Simpanan')?></h2>
								</div>
								<div class="pull-right">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=<?php echo md5('del_tsimp')?>&id=<?php echo $aREC_UPD_TRM_TAB1['REC_NO']?>" onClick="return confirm('<?php echo $cMSG_DEL?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
										</li>
										<li>
											<a href="#help_kop_teller_simpanan_upd" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
										<form name="Upd_Rek_record" action ="?_a=rubah&id=<?php echo $aREC_UPD_TRM_TAB1['REC_NO']?>&x_AMOUNT=<?php echo $aREC_UPD_TRM_TAB1['SV_AMOUNT']?>&cREK=<?php echo $aREC_UPD_TRM_TAB1['SAVE_ACT']?>" method="post"  onSubmit="return CEK_KOP_TB_SIM(this)">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNO_REK?></label>
												<input type="text" class="col-sm-3 form-label-900" name='UPD_SAVE_ACT' id="field-1" value="<?php echo $aREC_UPD_TRM_TAB1['SAVE_ACT']?>" disabled="disabled"><br><br>

												<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cJN_SIM?></label>
												<input type="text" class="col-sm-4 form-label-900" name='UPD_KODE_SIMPN' id="field-1" value="<?php echo $aREC_JENIS_SIMPANAN['NAMA_SIMPN']?>" disabled="disabled"><br><br>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cKD_ANG?></label>
												<input type="text" class="col-sm-4 form-label-900" name='NM_ANGG' id="f_KD_MMBR" value=<?php echo $aREC_NOMOR_REK_TAB['KD_MMBR']?> disabled="disabled"><br><br>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNAMA_ANG?></label>
												<input type="text" class="col-sm-5 form-label-900" name='UPD_TB_MEMBER1' id="field-1" value="<?php echo $aREC_TB_MEMBER1['NM_DEPAN']?>" disabled="disabled"><br><br>

												<label class="col-sm-4 form-label-700" for="field-5"><?php echo S_MSG('F005','Alamat')?></label>
												<input type="text" class="col-sm-5 form-label-900" name='ALMT_ANG' id="f_ALAMAT" value="<?php echo $aREC_TB_MEMBER1['ALAMAT']?>" disabled="disabled"><br><br>

												<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cVOUCHER?></label>
												<input type="text" class="col-sm-4 form-label-900" name='UPD_NO_VOUCHER' value="<?php echo decode_string($aREC_UPD_TRM_TAB1['NO_VOUCHER'])?>"><br><br>

												<label class="col-sm-4 form-label-700" for="field-7"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-2 form-label-900" data-mask="date" name='UPD_SAVE_DATE' id="field-7" value=<?php echo date("d-m-Y", strtotime($aREC_UPD_TRM_TAB1['SAVE_DATE']))?>><br><br>

												<label class="col-sm-4 form-label-700" for="field-5"><?php echo $cNOMINAL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='UPD_SV_AMOUNT' data-mask="fdecimal" data-numeric-align="right" value="<?php echo $aREC_UPD_TRM_TAB1['SV_AMOUNT']?>"><br><br>

												<label class="col-sm-4 form-label-700" for="field-7"><?php echo $cCATATAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='UPD_SAVE_NOTE' value="<?php echo $aREC_UPD_TRM_TAB1['SAVE_NOTE']?>"><br><br>

												<div class="clearfix"></div>
												<div class="text-left">
													<input type="submit" id="SAVE_BUTTON" class="btn btn-primary" value=<?php echo $cSAVE?>>
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
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 

<?php /*
			<!-- Sidebar Graph -  
			<script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
			<script src="assets/js/chart-sparkline.js" type="text/javascript"></script>

			<!-- My defined java script  --> 
*/	?>
			<script src="sys_js.js" type="text/javascript"></script> 

			<div class="modal" id="help_kop_teller_simpanan_upd" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">

							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>
							<p><?php echo $cHELP_3?></p>	<p><?php echo $cHELP_4?></p>	<p><?php echo $cHELP_5?></p>

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

case 'tambah':
	$cSAVE_ACT	= encode_string($_POST['ADD_SAVE_ACT']);	
	$nAMOUNT = str_replace(',', '', $_POST['ADD_SV_AMOUNT']);
	if($cSAVE_ACT=='') {
		$cMSG_BLANK	= S_MSG('KB02','Nomor Rekening Simpanan tidak boleh kosong');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}

	if($_POST['ADD_SV_AMOUNT']<1) {
		$cMSG = S_MSG('KB03','Jumlah nominal setoran tidak boleh kosong');
		echo "<script> alert('$cMSG');	window..back();	</script>";
		return;
	}

	$q_SAVE=SYS_QUERY("select SAVE_ACT, SV_BALANCE, APP_CODE, DELETOR  from tr_save1 where SAVE_ACT='$cSAVE_ACT' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
	if(SYS_ROWS($q_SAVE)==0){
		echo "<script> alert('$cNOT_FOUND'); </script>";
		return;
	}
	$aREC_TR_SAVE1=SYS_FETCH($q_SAVE);
	$nBALANCE = $aREC_TR_SAVE1['SV_BALANCE']+$nAMOUNT;
	$q_SAVE=SYS_QUERY("update tr_save1 set SV_BALANCE='$nBALANCE' 
		where SAVE_ACT='$cSAVE_ACT' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
	
	$cNO_VOUCHER	= encode_string($_POST['ADD_NO_VOUCHER']);	
	$cQUERY="select * from trm_tab1 where SAVE_ACT='$cSAVE_ACT' and NO_VOUCHER='$cNO_VOUCHER' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$dTG_SETOR = $_POST['ADD_SAVE_DATE'];		// 'dd/mm/yyyy'
		$cDATE = substr($dTG_SETOR,6,4). '-'. substr($dTG_SETOR,3,2). '-'. substr($dTG_SETOR,0,2);
		$cQUERY ="insert into trm_tab1 set SAVE_ACT='$cSAVE_ACT'";
		$cQUERY.=", NO_VOUCHER='$cNO_VOUCHER', SAVE_DATE='$cDATE'";
		$cQUERY.=", SAVE_NOTE='$_POST[ADD_SAVE_NOTE]'";
//		$cQUERY.=", KD_TELLER='$_SESSION[gUSERCODE]'";
		$cQUERY.=", SV_AMOUNT=0".$nAMOUNT;
		$cQUERY.=", APP_CODE='$cFILTER_CODE', ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);
		header('location:kop_teller_simpanan.php');
	} else {
		$cMSG = $cMSG_EXIST;
		echo "<script> alert('$cMSG');	window.location.href='kop_teller_simpanan.php';	</script>";
		return;
	}
	break;


case "rubah":
	$KODE_CRUD=$_GET['id'];
	$x_AMOUNT=$_GET['x_AMOUNT'];
	$cREK = $_GET['cREK'];
	$NOW = date("Y-m-d H:i:s");
	$dTG_SETOR = $_POST['UPD_SAVE_DATE'];		// 'dd/mm/yyyy'
	$cDATE = substr($dTG_SETOR,6,4). '-'. substr($dTG_SETOR,3,2). '-'. substr($dTG_SETOR,0,2);
	$nAMOUNT = str_replace(',', '', $_POST['UPD_SV_AMOUNT']);

	$q_SAVE=SYS_QUERY("select SAVE_ACT, SV_BALANCE, APP_CODE, DELETOR  from tr_save1 
		where SAVE_ACT='$cREK' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
	if(SYS_ROWS($q_SAVE)==0){
		echo "<script> alert('$cNOT_FOUND');	</script>";
		return;
	}
	$aREC_TR_SAVE1=SYS_FETCH($q_SAVE);
	$nBALANCE = $aREC_TR_SAVE1['SV_BALANCE']-$x_AMOUNT+$nAMOUNT;
//	die ($nBALANCE);
	$q_SAVE=SYS_QUERY("update tr_save1 set SV_BALANCE='$nBALANCE' 
		where SAVE_ACT='$cREK' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
	
	$cQUERY ="update trm_tab1 set SV_AMOUNT='$nAMOUNT', NO_VOUCHER='$_POST[UPD_NO_VOUCHER]', SAVE_NOTE='$_POST[UPD_SAVE_NOTE]', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW' where REC_NO=$KODE_CRUD";
	SYS_QUERY($cQUERY);
	header('location:kop_teller_simpanan.php');
	break;


case md5('del_tsimp'):
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	
	$qCEK_KODE=SYS_QUERY("select * from trm_tab1 where REC_NO='$KODE_CRUD'") or die ('Error in query.' .mysql_error().'==> '.$cQUERY);
	$aREC_TRM_TAB1=SYS_FETCH($qCEK_KODE);
	$nAMOUNT = $aREC_TRM_TAB1['SV_AMOUNT'];
	$cREK = $aREC_TRM_TAB1['SAVE_ACT'];
	$q_SAVE=SYS_QUERY("select SAVE_ACT, SV_BALANCE, APP_CODE, DELETOR  from tr_save1 
		where SAVE_ACT='$cREK' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
	if(SYS_ROWS($q_SAVE)==0){
		echo "<script> alert('$cNOT_FOUND');	</script>";
		return;
	}

	$aREC_TR_SAVE1=SYS_FETCH($q_SAVE);
	$nJADINYA= $aREC_TR_SAVE1['SV_BALANCE'] - $nAMOUNT;
	$cQUERY = "update tr_save1 set SV_BALANCE='$nJADINYA' 
		where SAVE_ACT='$cREK' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	SYS_QUERY($cQUERY) or die ('Error in query.' .mysql_error());

	$cQUERY ="update trm_tab1 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and REC_NO='$KODE_CRUD'";
	SYS_QUERY($cQUERY) or die ('Error in query.' .mysql_error().'==> '.$cQUERY);
	header('location:kop_teller_simpanan.php?');

}
?>

<script>
function Disp_Rek_Tab(pkode_rekening) {
	var btn_stat = document.getElementById("SAVE_BUTTON");  // the submit button
//		alert(pkode_rekening);
    if (pkode_rekening == "") {
        document.getElementById("ADD_SAVE_ACT").innerHTML = "";
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
                document.getElementById("NAMA_ANGGOTA").innerHTML = xmlhttp.responseText;
//				alert(xmlhttp.responseText);
				document.getElementById("NAMA_ANGGOTA").value = xmlhttp.responseText;
            }
			if (document.getElementById("NAMA_ANGGOTA").value == "") {
				document.getElementById("SAVE_BUTTON").setAttribute('disabled', 'disabled');
			} else {
				document.getElementById("SAVE_BUTTON").removeAttribute('disabled');
			}
        };
        xmlhttp.open("GET","kop_cek_rek_tab.php?NO_REK="+pkode_rekening,true);
        xmlhttp.send();
		
    }
}

function Kop_Teller_Simpanan_focus()  
{  
	var uid = document.Add_New_record.ADD_SAVE_ACT.focus();  
	return true;  
}  

function Kop_Teller_Upd_Simpanan_focus()  
{  
	var uid = document.Upd_Rek_record.UPD_SAVE_ACT.focus();  
	return true;  
}  

</script>

 
