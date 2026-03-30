<?php
// kop_tb_simpanan.php
// Tabel jenis simpanan

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cQUERY="SELECT tab_simp.*, tab_jasa.* FROM tab_simp LEFT JOIN tab_jasa ON tab_simp.KODE_PENDJ=tab_jasa.KODE_PENDJ where tab_simp.APP_CODE='$cFILTER_CODE' and tab_simp.DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);
	$cHEADER 	= S_MSG('KA21','Table Jenis Simpanan');
	$cADD 		= S_MSG('KK64','Tambah Jenis Simpanan');
	$cKODE_TBL 	= S_MSG('F003','Kode');
	$cNAMA_TBL 	= S_MSG('KK22','Nama Simpanan');
	$cJNS_JASA 	= S_MSG('KK23','Jenis jasa');
	$cBUNGA_THN = S_MSG('KK24','Bunga % (per thn)');
	$cMIN_TRANS = S_MSG('KK25','Minimal Transaksi');
	$cMIN_STRN 	= S_MSG('KK26','Minimal Setoran Awal');
	$cMIN_SALDO = S_MSG('KA22','Minimum Saldo');
	$cBIAYA_ADM = S_MSG('KA23','Biaya Adm Pembukaan');
	$cBIAYA_BLN = S_MSG('KA24','Biaya Adm Bulanan');
	$cBIAYA_PPH = S_MSG('KA25','Pajak Penghasilan (PPh)');
	$cACCT_KAS 	= S_MSG('KK27','Account K a s');
	$cACCT_SIM 	= S_MSG('KK28','Account Simpanan');
	$cACCT_DAF 	= S_MSG('KK29','Account Bea pendaftaran');
	$cACCT_BLN 	= S_MSG('KK30','Account Bea bulanan');
	$cSAVE_DATA	= S_MSG('F301','Save');
	$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');
	
	$cTTIP_NAMA_SIMP	= S_MSG('KK32','Nama produk simpanan sebagai keterangan');
	$cTTIP_JNS_JASA		= S_MSG('KK33','Jenis produk jasa');
	$cTTIP_BUNGA_BLN	= S_MSG('KK34','Bunga per bulan dalam persen');
	$cTTIP_MIN_TRANS	= S_MSG('KK35','Jumlah nilai transaksi minimum tiap setoran');
	$cTTIP_MIN_STAWAL	= S_MSG('KK36','Jumlah setoran awal minimum pada saat pembukaan');
	$cTTIP_MIN_SALDO	= S_MSG('KA32','Saldo minimum yang diijinkan untuk jenis simpanan ini');
	$cTTIP_BIAYA_BUKA	= S_MSG('KA33','Jumlah Biaya administrasi yang dikenakan pada waktu pembukaan rekening simpanan');
	$cTTIP_BIAYA_ADM	= S_MSG('KA34','Jumlah Biaya simpanan yang dikenakan setiap bulan terhadap rekening simpanan');
	$cTTIP_BIAYA_PPH	= S_MSG('KA35','Prosentasi biaya Pph default pada waktu membuat rekening simpanan baru');
	$cTTIP_ACCT_KAS		= S_MSG('KK37','Kode account kas di laporan neraca');
	$cTTIP_ACCT_SIM		= S_MSG('KK38','Kode account simpanan yang berjenis hutang di neraca');
	$cTTIP_ACCT_DAF		= S_MSG('KK39','Kode account pendapatan jasa keuangan di lap. rugi/laba');
	$cTTIP_ACCT_BLN		= S_MSG('KK40','Kode account pendapatan bea pendaftaran di lap. rugi/laba');
	$cTTIP_ACCT_JAS		= S_MSG('KK42','Kode account pendapatan jasa keuangan di lap. rugi/laba');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('KB71','Help Tabel Jenis Simpanan');
		$cHELP_1	= S_MSG('KB72','Ini adalah modul untuk memasukkan data Jenis Simpanan, untuk pengelompokan jenis-jenis Simpanan');
		$cHELP_2	= S_MSG('KB73','Untuk memasukkan data Jenis Simpanan baru, klik tambah Simpanan / add new');
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
											<li>	<a href="?_a=create"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>	</li>
											<li>	<a href="#help_kop_tb_simpanan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>	</li>
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
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cJNS_JASA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cBUNGA_THN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cMIN_TRANS?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cMIN_STRN?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_TAB_SIMP=SYS_FETCH($qQUERY)) {
															echo '<tr>';
															echo '<td style="width: 1px;"></td>';
															echo "<td><span><a href='?_a=".md5('update')."&KODE_SIMPAN=$aREC_TAB_SIMP[KODE_SIMPN]'>".$aREC_TAB_SIMP['KODE_SIMPN']."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('update')."&KODE_SIMPAN=$aREC_TAB_SIMP[KODE_SIMPN]'>".decode_string($aREC_TAB_SIMP['NAMA_SIMPN'])."</a></span></td>";
															echo '<td>'.$aREC_TAB_SIMP['NAMA_PENDJ'].'</td>';
															echo '<td align="center">'.number_format($aREC_TAB_SIMP['P_BUNGA_BL'],2).'</td>';
//															echo '<td>'.$aREC_TAB_SIMP['P_BUNGA_BL'].'</td>';
															echo '<td align="right">'.number_format($aREC_TAB_SIMP['P_MIN_TRAN']).'</td>';
															echo '<td align="right">'.number_format($aREC_TAB_SIMP['P_MIN_SETR']).'</td>';
															echo '</tr>';	}
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
			<div class="modal" id="help_kop_tb_simpanan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
		</body>
	</html>

<?php
	break;

case "create":
		$cHELP_BOX		= S_MSG('KB7A','Help Tambah Jenis Simpanan');
		$cHELP_1		= S_MSG('KB7B','Ini adalah modul untuk memasukkan data Jenis Simpanan yang baru, apabila lembaga keuangan ingin mengeluarkan produk Simpanan yang baru.');
		$cHELP_2		= S_MSG('KB7C','Data yang dimasukkan adalah : ');
		$cHELP_3		= S_MSG('KB7D','Kode : Untuk memasukkan kode jenis simpanan/tabungan yang baru, yang belum pernah ada sebelumnya.');
		$cHELP_4		= S_MSG('KB7E','Nama Simpanan : Nama dari produk jenis simpanan/tabungan yang baru, misalnya simpanan sukarela.');
		$cHELP_5		= S_MSG('KB7F','Jenis Jasa : Untuk menentuka produk simpanan/tabungan ini termasuk produk lembaga yang mana.');
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
									  <h2 class="title"><?php echo $cADD?></h2>
								</div>
								<div class="pull-right">									 
									<ol class="breadcrumb">
									<li>
										<a href="#help_add_kop_tb_simpanan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
									</li>
									</ol>
								</div>		
							</div>
						</div>
						<div class="clearfix"></div>

							<section class="box ">
								<div class="pull-right hidden-xs"></div>
								<div class="content-body">
									<div class="row">
										<form action ="?_a=tambah" method="post"  onSubmit="return CEK_KOP_TB_SIM(this)">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='ADD_KODE_SIMPN' >
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_NAMA_SIMPN' title="<?php echo $cTTIP_NAMA_SIMP ?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cJNS_JASA?></label>
												<select name="ADD_KODE_PENDJ" class="col-sm-6 form-label-900" title="<?php echo $cTTIP_JNS_JASA ?>">
												<?php 
													$qQUERY=SYS_QUERY("select * from tab_jasa where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($aREC_TAB_JASA=SYS_FETCH($qQUERY)){
															echo "<option value='$aREC_TAB_JASA[KODE_PENDJ]'  >$aREC_TAB_JASA[NAMA_PENDJ]</option>";
													}
												?>
												</select>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cBUNGA_THN?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_P_BUNGA_BL' title="<?php echo $cTTIP_BUNGA_BLN ?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-5"><?php echo $cMIN_TRANS?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_P_MIN_TRAN' title="<?php echo $cTTIP_MIN_TRANS ?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cMIN_STRN?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_P_MIN_SETR' title="<?php echo $cTTIP_MIN_STAWAL ?>">
												<div class="clearfix"></div>

<!-- TAB - START -->
												<div class="col-sm-12">
													<h4> </br></h4>
													<ul class="nav nav-tabs primary">
														 <li class="active">
															  <a href="#Detil-1" data-toggle="tab">
																	<i class="fa fa-user"></i> <?php echo S_MSG('F010','Detil')?>
															  </a>
														 </li>
														 <li>
															  <a href="#Account-1" data-toggle="tab">
																	<i class="fa fa-home"></i> <?php echo S_MSG('F028','Account')?> 
															  </a>
														 </li>
													</ul>

													<div class="tab-content primary">
														<div class="tab-pane fade in active" id="Detil-1">
						
															<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cMIN_SALDO?></label>
															<input type="text" class="col-sm-3 form-label-900" name='ADD_SALDO_MIN' title="<?php echo $cTTIP_MIN_SALDO ?>"  maxlength="10"><br>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cBIAYA_ADM?></label>
															<input type="text" class="col-sm-3 form-label-900" name='ADD_BIAYA_DAF' title="<?php echo $cTTIP_BIAYA_BUKA ?>">
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cBIAYA_BLN?></label>
															<input type="text" class="col-sm-3 form-label-900" name='ADD_BIAYA_BLN' title="<?php echo $cTTIP_BIAYA_ADM ?>">
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cBIAYA_PPH?></label>
															<input type="text" class="col-sm-3 form-label-900" name='ADD_P_P_H' title="<?php echo $cTTIP_BIAYA_PPH ?>">
															<div class="clearfix"></div>

														</div>		<!-- End of Tab 1 -->
				
														<!-- Tab 2 begin -->
														<div class="tab-pane fade" id="Account-1">
															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_KAS?></label>
															<select name="ADD_ACCT_KAS" class="col-sm-6 form-label-900" title="<?php echo $cTTIP_ACCT_KAS ?>">
																<?php 
																	echo "<option value=' '  > </option>";
																	$qQUERY=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																	while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																		echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	}
																?>
															</select>
															<div class="clearfix"></div>
															
															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_SIM?></label>
															<select name="ADD_ACCT_SIM" class="col-sm-6 form-label-900" title="<?php echo $cTTIP_ACCT_SIM ?>">
																<?php 
																	echo "<option value=' '  > </option>";
																	$qQUERY=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																	while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																		echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	}
																?>
															</select>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_DAF?></label>
															<select name="ADD_ACCT_DAF" class="col-sm-6 form-label-900" title="<?php echo $cTTIP_ACCT_DAF ?>"><br><br>
																<?php 
																	echo "<option value=' '  > </option>";
																	$qQUERY=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																	while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																		echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	}
																?>
															</select>
															<div class="clearfix"></div>
															
															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_BLN?></label>
															<select name="ADD_ACCT_BLN" class="col-sm-6 form-label-900" title="<?php echo $cTTIP_ACCT_JAS ?>">
																<?php 
																	echo "<option value=' '  > </option>";
																	$qQUERY=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																	while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																		echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	}
																?>
															</select>
															<div class="clearfix"></div>
															
															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_BLN?></label>
															<select name="ADD_ACCT_BUNGA" class="col-sm-6 form-label-900" title="<?php echo $cTTIP_ACCT_JAS ?>">
																<?php 
																	echo "<option value=' '  > </option>";
																	$qQUERY=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																	while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																		echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	}
																?>
															</select>
															<div class="clearfix"></div>
														</div>		<!-- End of Tab 2 -->
													</div>

												</div>	<div class="clearfix"></div><br>
<!--  TAB - END -->	
												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
													<input type="button" class="btn" value=<?php echo S_MSG('F302','Cancel')?> onclick=self.history.back()>
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

			<div class="modal" id="help_add_kop_tb_simpanan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
	break;

case md5('update'):
	$cQUERY="SELECT tab_simp.*, tab_jasa.* FROM tab_simp LEFT JOIN tab_jasa ON tab_simp.KODE_PENDJ=tab_jasa.KODE_PENDJ where tab_simp.APP_CODE='$cFILTER_CODE' and tab_simp.DELETOR=''";
	$cQUERY = "select tab_simp.*, tab_jasa.* from tab_simp ";
	$cQUERY.= "left join tab_jasa ON tab_simp.KODE_PENDJ=tab_jasa.KODE_PENDJ ";
	$cQUERY.= " where tab_simp.KODE_SIMPN='".$_GET['KODE_SIMPAN'];
	$cQUERY.= "' and tab_simp.APP_CODE='".$cFILTER_CODE."' and tab_simp.DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);
	if(SYS_ROWS($qQUERY)==0){	header('location:kop_tb_simpanan.php');	}
	$REC_TAB_SIMP=SYS_FETCH($qQUERY);
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

						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">

								<div class="pull-left">
									  <h1 class="title"><?php echo S_MSG('KK50','Edit Jenis Simpanan')?></h1>
								</div>
								<div class="pull-right">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=delete&id=<?php echo $REC_TAB_SIMP['KODE_SIMPN']?>" onClick="return confirm('<?php echo $cMSG_DEL?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

							<section class="box ">
								<div class="pull-right hidden-xs"></div>
								<div class="content-body">
									<div class="row">
										<form action ="?_a=rubah&id=<?php echo $REC_TAB_SIMP['KODE_SIMPN']?>" method="post"  onSubmit="return CEK_KOP_TB_SIM(this)">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_KODE_SIMPN' id="field-1" value=<?php echo $REC_TAB_SIMP['KODE_SIMPN']?> disabled="disabled">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cNAMA_TBL?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_NAMA_SIMPN' value="<?php echo decode_string($REC_TAB_SIMP['NAMA_SIMPN'])?>" title="<?php echo $cTTIP_NAMA_SIMP ?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cJNS_JASA?></label>
												<select name="EDIT_KODE_PENDJ" class="col-sm-6 form-label-900" title="<?php echo $cTTIP_JNS_JASA ?>">
												<?php 
													$qQUERY=SYS_QUERY("select * from tab_jasa where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($aREC_TAB_JASA=SYS_FETCH($qQUERY)){
														if($REC_TAB_SIMP['KODE_PENDJ'] == $aREC_TAB_JASA['KODE_PENDJ']){
															echo "<option value='$aREC_TAB_JASA[KODE_PENDJ]' selected='$REC_TAB_SIMP[KODE_PENDJ]' >$aREC_TAB_JASA[NAMA_PENDJ]</option>";
														} else {
															echo "<option value='$aREC_TAB_JASA[KODE_PENDJ]'  >$aREC_TAB_JASA[NAMA_PENDJ]</option>";
														}
													}
												?>
												</select>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cBUNGA_THN?></label>
												<input type="text" class="col-sm-3 form-label-900" name='EDIT_P_BUNGA_BL' id="field-4" data-mask="fdecimal" value=<?php echo $REC_TAB_SIMP['P_BUNGA_BL']?> title="<?php echo $cTTIP_BUNGA_BLN ?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-5"><?php echo $cMIN_TRANS?></label>
												<input type="text" class="col-sm-3 form-label-900" name='EDIT_P_MIN_TRAN' id="field-5" data-mask="fdecimal" value=<?php echo $REC_TAB_SIMP['P_MIN_TRAN']?> title="<?php echo $cTTIP_MIN_TRANS ?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cMIN_STRN?></label>
												<input type="text" class="col-sm-3 form-label-900" name='EDIT_P_MIN_SETR' id="field-2" data-mask="fdecimal" value=<?php echo $REC_TAB_SIMP['P_MIN_SETR']?> title="<?php echo $cTTIP_MIN_STAWAL ?>">
												<div class="clearfix"></div>

<!-- TAB - START -->
												<div class="col-sm-12">
													<h4> </br></h4>
													<ul class="nav nav-tabs primary">
														 <li class="active">
															  <a href="#Detil-1" data-toggle="tab">
																	<i class="fa fa-user"></i> <?php echo S_MSG('F010','Detil')?>
															  </a>
														 </li>
														 <li>
															  <a href="#Account-1" data-toggle="tab">
																	<i class="fa fa-home"></i> <?php echo S_MSG('F028','Account')?> 
															  </a>
														 </li>
													</ul>

													<div class="tab-content primary">
														<div class="tab-pane fade in active" id="Detil-1">
						
															<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cMIN_SALDO?></label>
															<input type="text" value=<?php echo $REC_TAB_SIMP['SALDO_MIN']?> class="col-sm-3 form-label-900" name='EDIT_SALDO_MIN' data-mask="fdecimal" maxlength="10" title="<?php echo $cTTIP_MIN_SALDO ?>"><br>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cBIAYA_ADM?></label>
															<input type="text" value="<?php echo $REC_TAB_SIMP['BIAYA_DAF']?>" class="col-sm-3 form-label-900" name='EDIT_BIAYA_DAF' data-mask="fdecimal" title="<?php echo $cTTIP_BIAYA_BUKA ?>">
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cBIAYA_BLN?></label>
															<input type="text" value="<?php echo $REC_TAB_SIMP['BIAYA_BLN']?>" class="col-sm-3 form-label-900" name='EDIT_BIAYA_BLN' data-mask="fdecimal" title="<?php echo $cTTIP_BIAYA_ADM ?>">
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cBIAYA_PPH?></label>
															<input type="text" value="<?php echo $REC_TAB_SIMP['P_P_H']?>" class="col-sm-3 form-label-900" name='EDIT_P_P_H' data-mask="fdecimal" title="<?php echo $cTTIP_BIAYA_PPH ?>">
															<div class="clearfix"></div>

														</div>		<!-- End of Tab 1 -->
				
														<!-- Tab 2 begin -->
														<div class="tab-pane fade" id="Account-1">
															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_KAS?></label>
															<select name="EDIT_ACCT_KAS" class="col-sm-6 form-label-900" title="<?php echo $cTTIP_ACCT_KAS ?>">
																<?php 
																	echo "<option value=' '  > </option>";
																	$qQUERY=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																	while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																		if($REC_TAB_SIMP['ACCT_KAS'] == $aREC_ACCOUNT['ACCOUNT_NO']){
																			echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$REC_TAB_SIMP[ACCT_KAS]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																		} else {
																		echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
																	}
																?>
															</select>
															<div class="clearfix"></div>
															
															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_SIM?></label>
															<select name="EDIT_ACCT_SIM" class="col-sm-6 form-label-900" title="<?php echo $cTTIP_ACCT_SIM ?>">
																<?php 
																	echo "<option value=' '  > </option>";
																	$qQUERY=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																	while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																		if($REC_TAB_SIMP['ACCT_SIM'] == $aREC_ACCOUNT['ACCOUNT_NO']){
																			echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$REC_TAB_SIMP[ACCT_SIM]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																		} else
																		echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	}
																?>
															</select>
															<div class="clearfix"></div>
															
															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_DAF?></label>
															<select name="EDIT_ACCT_DAF" class="col-sm-6 form-label-900" title="<?php echo $cTTIP_ACCT_DAF ?>">
																<?php 
																	echo "<option value=' '  > </option>";
																	$qQUERY=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																	while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																		if($REC_TAB_SIMP['ACCT_DAF'] == $aREC_ACCOUNT['ACCOUNT_NO']){
																			echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$REC_TAB_SIMP[ACCT_DAF]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																		} else
																		echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	}
																?>
															</select>
															<div class="clearfix"></div>
															
															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_BLN?></label>
															<select name="EDIT_ACCT_BLN" class="col-sm-6 form-label-900" title="<?php echo $cTTIP_ACCT_BLN ?>">
																<?php 
																	echo "<option value=' '  > </option>";
																	$qQUERY=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																	while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																		if($REC_TAB_SIMP['ACCT_BLN'] == $aREC_ACCOUNT['ACCOUNT_NO']){
																			echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$REC_TAB_SIMP[ACCT_BLN]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																		} else
																		echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	}
																?>
															</select>
															<div class="clearfix"></div>
															
															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_BLN?></label>
															<select name="EDIT_ACCT_BUNGA" class="col-sm-6 form-label-900" title="<?php echo $cTTIP_ACCT_JAS ?>">
																<?php 
																	echo "<option value=' '  > </option>";
																	$qQUERY=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																	while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																		if($REC_TAB_SIMP['ACCT_BUNGA'] == $aREC_ACCOUNT['ACCOUNT_NO']){
																			echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$REC_TAB_SIMP[ACCT_BUNGA]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																		} else
																		echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	}
																?>
															</select>
															<div class="clearfix"></div>

														</div><br></br><br>		<!-- End of Tab 2 -->
													</div>
												</div><br>
<!--  TAB - END -->	
												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
													<input type="button" class="btn" value=<?php echo S_MSG('F302','Cancel')?> onclick=self.history.back()>
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
	break;

case 'tambah':
	if($_POST['ADD_KODE_SIMPN']=='') {
		$cMSG = S_MSG('KB67','Kode Jenis Simpanan tidak boleh kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;

	}
	$cQUERY="select * from tab_simp where APP_CODE='$cFILTER_CODE' and KODE_SIMPN='$_POST[ADD_KODE_SIMPN]' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$cKODE_SIMPN = encode_string($_POST['ADD_KODE_SIMPN']);
		$cNAMA_SIMPN = encode_string($_POST['ADD_NAMA_SIMPN']);
		$cQUERY ="insert into tab_simp set APP_CODE='$cFILTER_CODE', KODE_SIMPN='$cKODE_SIMPN', NAMA_SIMPN='$cNAMA_SIMPN'";
		$cQUERY.=", KODE_PENDJ='$_POST[ADD_KODE_PENDJ]', P_BUNGA_BL='$_POST[ADD_P_BUNGA_BL]', P_MIN_TRAN='$_POST[ADD_P_MIN_TRAN]'";
		$cQUERY.=", P_MIN_SETR='$_POST[ADD_P_MIN_SETR]', SALDO_MIN='$_POST[ADD_SALDO_MIN]', BIAYA_DAF='$_POST[ADD_BIAYA_DAF]'";
		$cQUERY.=", BIAYA_BLN='$_POST[ADD_BIAYA_BLN]', P_P_H='$_POST[ADD_P_P_H]', ACCT_KAS='$_POST[ADD_ACCT_KAS]'";
		$cQUERY.=", ACCT_SIM='$_POST[ADD_ACCT_SIM]', ACCT_DAF='$_POST[ADD_ACCT_DAF]', ACCT_BLN='$_POST[ADD_ACCT_BLN]'";
		$cQUERY.=", ACCT_BUNGA='$_POST[ADD_ACCT_BUNGA]'";
		$cQUERY.=", ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);
		header('location:kop_tb_simpanan.php');
	} else {
		$cMSG = S_MSG('KB68','Kode Jenis Simpanan sudah ada');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	break;

case 'rubah':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cNAMA_SIMPN = encode_string($_POST['EDIT_NAMA_SIMPN']);
	$cQUERY ="update tab_simp set NAMA_SIMPN='$cNAMA_SIMPN', KODE_PENDJ='$_POST[EDIT_KODE_PENDJ]', ";
	$cQUERY.=" P_BUNGA_BL=".str_replace(',', '', $_POST['EDIT_P_BUNGA_BL']);
	$cQUERY.=", P_MIN_TRAN=".str_replace(',', '', $_POST['EDIT_P_MIN_TRAN']);
	$cQUERY.=", P_MIN_SETR=".str_replace(',', '', $_POST['EDIT_P_MIN_SETR']);
	$cQUERY.=", SALDO_MIN='$_POST[EDIT_SALDO_MIN]', BIAYA_DAF='$_POST[EDIT_BIAYA_DAF]', BIAYA_BLN='$_POST[EDIT_BIAYA_BLN]', ";
	$cQUERY.=" P_P_H='$_POST[EDIT_P_P_H]', ACCT_KAS='$_POST[EDIT_ACCT_KAS]', ACCT_SIM='$_POST[EDIT_ACCT_SIM]', ";
	$cQUERY.=" ACCT_DAF='$_POST[EDIT_ACCT_DAF]', ACCT_BLN='$_POST[EDIT_ACCT_BLN]', ACCT_BUNGA='$_POST[EDIT_ACCT_BUNGA]', ";
	$cQUERY.=" UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and KODE_SIMPN='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:kop_tb_simpanan.php');
	break;

case 'delete':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cQUERY ="update tab_simp set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW'";
	$cQUERY.="where APP_CODE='$cFILTER_CODE' and KODE_SIMPN=$KODE_CRUD";
	SYS_QUERY($cQUERY);
	header('location:kop_tb_simpanan.php');
}
?>


