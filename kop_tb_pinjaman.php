<?php
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cQUERY="SELECT tab_pinj.*, tab_jasa.* FROM tab_pinj LEFT JOIN tab_jasa ON tab_pinj.PINJ_PENDJ=tab_jasa.KODE_PENDJ where tab_pinj.APP_CODE='$cFILTER_CODE' and tab_pinj.DELETOR='' ORDER BY tab_pinj.KODE_PINJM";
	$qQUERY=SYS_QUERY($cQUERY);

	$cHEADER 		= S_MSG('KA01','Table Jenis Pinjaman');
	$cKODE_PINJAMAN = S_MSG('KA02','Kode Pinjaman');
	$cNAMA_PINJAMAN = S_MSG('KA03','Nama Pinjaman');
	$cJNS_PENDAPATAN = S_MSG('KA04','Jenis Pendapatan');
	$cBUNGA_THN 	= S_MSG('KA05','Bunga / thn');
	$cJN_BUNG		= S_MSG('KI53','Jenis Bunga');
	$cBIAYA_ADM 	= S_MSG('KA06','Biaya Administrasi');
	$cBIAYA_PRV 	= S_MSG('KA07','Biaya Provisi');
	$cBIAYA_ASR 	= S_MSG('KC11','Biaya Asuransi');
	$cACCT_PEND_PROVISI=S_MSG('KK70','Account Pendapatan Provisi');
	$cACCT_PIUTANG	=S_MSG('KK71','Account Pinjaman');
	$cACCT_BEA_DAFTAR=S_MSG('KK29','Account Bea pendaftaran');
	$cACCT_BEA_BULAN=S_MSG('KK30','Account Bea bulanan');
	$cACCT_PEND_JASA=S_MSG('KK41','Account Pendapatan jasa');
	$cMSG_DEL		= S_MSG('F021','Benar data ini mau di hapus ?');

	$cTTIP_KODE		= S_MSG('KK12','Kode tipe pinjaman');
	$cTTIP_PINJ		= S_MSG('KK13','Nama sebagai keterangan mengenai tipe pinjaman');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('KA0A','Help tabel produk Pinjaman');
		$cHELP_1	= S_MSG('KA0B','Ini adalah modul untuk memasukkan data Produk Pinjaman apa saja yang ada di lembaga.');
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
												<a href="#help_tb_pinjaman" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_PINJAMAN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_PINJAMAN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cJNS_PENDAPATAN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cBUNGA_THN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cBIAYA_ADM?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_TPIN=SYS_FETCH($qQUERY)) {
															echo '<tr>';
//																echo '<td style="width: 1px;"></td>';
																echo '<td class=""><div class="star"><i class="fa fa-cloud-upload icon-xs icon-default"></i></div></td>';
																echo "<td><span><a href='?_a=update&KODE_PINJM=$aREC_TPIN[KODE_PINJM]'>".$aREC_TPIN['KODE_PINJM']."</a></span></td>";
																echo "<td><span><a href='?_a=update&KODE_PINJM=$aREC_TPIN[KODE_PINJM]'>".$aREC_TPIN['NAMA_PINJM']."</a></span></td>";
																echo '<td>'.$aREC_TPIN['NAMA_PENDJ'].'</td>';
																echo '<td align="right">'.number_format($aREC_TPIN['PINJ_BUNGA'],2).'</td>';
																echo '<td align="right">'.number_format($aREC_TPIN['BIAYA_ADM']).'</td>';
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
			<div class="modal" id="help_tb_pinjaman" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>
						</div>
						<div class="modal-footer">
							<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						</div>
					</div>
				</div>
			</div>
        <!-- modal end -->
		</body>
	</html>

<?php
	break;

case md5('cr34t3'):
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
								  <h2 class="title"><?php echo S_MSG('KA10','Tambah Table Jenis Pinjaman')?></h2>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<section class="box ">
							<div class="pull-right hidden-xs"></div>
							<div class="content-body">
								<div class="row">
									<form action ="?_a=tambah&id=<?php echo $REC_EDIT['KODE_PINJM']?>" method="post"  onSubmit="return CEK_KOP_TB_PIN(this)">
										<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_PINJAMAN?></label>
											<input type="text" class="col-sm-3 form-label-900" name='ADD_KODE_PINJM' title="<?php echo $cTTIP_KODE?>"><br>
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cNAMA_PINJAMAN?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_NAMA_PINJ' title="<?php echo $cTTIP_PINJ?>"><br>
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cJNS_PENDAPATAN?></label>
											<select name='ADD_PINJ_PENDJ' class="form-label-900 col-sm-4">
											<?php 
												$REC_PENDA=SYS_QUERY("select * from tab_jasa where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($aREC_TB_JASA=SYS_FETCH($REC_PENDA)){
													echo "<option value='$aREC_TB_JASA[KODE_PENDJ]'  >$aREC_TB_JASA[NAMA_PENDJ]</option>";
												}
											?>
											</select><br>
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cBUNGA_THN?></label>
											<input type="text" class="col-sm-2 form-label-900" name='P_BUNGA' id="field-2" data-mask="fdecimal" data-numeric-align="right"><br>
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
														
														<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cBIAYA_ADM?></label>
														<input type="text" class="col-sm-3 form-label-900" name='P_ADMIN' id="field-2" data-mask="fdecimal" data-numeric-align="right" Value=0><br>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cBIAYA_PRV?></label>
														<input type="text" class="col-sm-3 form-label-900" name='P_PROVI' id="field-2" data-mask="fdecimal" data-numeric-align="right" Value=0><br>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cBIAYA_ASR?></label>
														<input type="text" class="col-sm-3 form-label-900" name='BY_ASURANSI' id="field-3" data-mask="fdecimal" data-numeric-align="right" Value=0><br>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('KC12','Biaya Administrasi')?></label>
														<input type="text" name='ADD_BIAYA_DAF' class="col-sm-3 form-label-900" id="field-4" data-mask="fdecimal" data-numeric-align="right" Value=0><br>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('KC13','Biaya Adm Bulanan')?></label>
														<input type="text" name='ADD_BIAYA_BLN' class="col-sm-3 form-label-900" id="field-8" data-mask="fdecimal" data-numeric-align="right" Value=0><br>
														<div class="clearfix"></div>

													</div>		<!-- End of Tab 1 -->
													
													<!-- Tab 2 begin -->
													<div class="tab-pane fade" id="Account-1">
														<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_PEND_PROVISI?></label>
														<select name='ADD_ACCT_PRVSI' class="col-sm-6 form-label-900">
														<?php 
															echo "<option value=' '  > </option>";
															$REC_DATA=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
															while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
																echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
															}
														?>
														</select>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_PIUTANG?></label>
														<select name='ADD_ACCT_PIN' class="col-sm-6 form-label-900">
														<?php 
															echo "<option value=' '  > </option>";
															$REC_DATA=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
															while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
																echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
															}
														?>
														</select>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_BEA_DAFTAR?></label>
														<select name='ADD_ACCT_DAF' class="col-sm-6 form-label-900">
														<?php 
															echo "<option value=' '  > </option>";
															$REC_DATA=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
															while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
																echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
															}
														?>
														</select>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_BEA_BULAN?></label>
														<select name='ADD_ACCT_BLN' class="col-sm-6 form-label-900">
														<?php 
															echo "<option value=' '  > </option>";
															$REC_DATA=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
															while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
																echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
															}
														?>
														</select>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cACCT_PEND_JASA?></label>
														<select name='ADD_ACCT_BUNGA' class="col-sm-6 form-label-900">
														<?php 
															echo "<option value=' '  > </option>";
															$REC_DATA=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
															while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
																echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
															}
														?>
														</select>
														<div class="clearfix"></div>
													</div>
												</div></br>
											</div>
<!--  TAB - END -->	
											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo S_MSG('F301','Save')?>>
												<input type="button" class="btn" value=<?php echo S_MSG('F302','Close')?> onclick=self.history.back()>
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
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 	<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>	<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>	<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 
		</body>
	</html>

<?php
	break;

case "update":
	$cQUERY="select tab_pinj.*, tab_jasa.* FROM tab_pinj 
		LEFT JOIN tab_jasa ON tab_pinj.PINJ_PENDJ=tab_jasa.KODE_PENDJ 
		where tab_pinj.KODE_PINJM='$_GET[KODE_PINJM]' and tab_pinj.APP_CODE='$cFILTER_CODE' and tab_pinj.DELETOR='' 
		ORDER BY tab_pinj.KODE_PINJM";
	$qQUERY=SYS_QUERY($cQUERY);
	if(SYS_ROWS($qQUERY)==0){
		header('location:kop_tb_pinjaman.php');
	}
	$REC_EDIT=SYS_FETCH($qQUERY);
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
								  <h2 class="title"><?php echo S_MSG('KA08','Edit Table Jenis Pinjaman')?></h2>
							</div>
							<div class="pull-right">									 
								<ol class="breadcrumb">
									<li>
										<a href="?_a=delete&id=<?php echo $REC_EDIT['KODE_PINJM']?>" onClick="return confirm('<?php echo $cMSG_DEL?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
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
									<form action ="?_a=rubah&id=<?php echo $REC_EDIT['KODE_PINJM']?>" method="post"  onSubmit="return CEK_KOP_TB_PIN(this)">
										<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_PINJAMAN?></label>
											<input type="text" class="col-sm-2 form-label-900" name='KD_PINJ' id="field-1" value=<?php echo $REC_EDIT['KODE_PINJM']?> disabled="disabled">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cNAMA_PINJAMAN?></label>
											<input type="text" class="col-sm-7 form-label-900" name='NM_PINJ' id="field-2" value="<?php echo $REC_EDIT['NAMA_PINJM']?>" title="<?php echo $cTTIP_PINJ?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cJNS_PENDAPATAN?></label>
											<select name='UPD_PINJ_PENDJ' class="col-sm-5 form-label-900 m-bot15">
											<?php 
												echo "<option value=' '  > </option>";
												$REC_PENDA=SYS_QUERY("select * from tab_jasa where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($aREC_TB_JASA=SYS_FETCH($REC_PENDA)){
													if($aREC_TB_JASA['KODE_PENDJ']==$REC_EDIT['KODE_PENDJ']){
														echo "<option value='$aREC_TB_JASA[KODE_PENDJ]' selected='$REC_EDIT[PINJ_PENDJ]' >$aREC_TB_JASA[NAMA_PENDJ]</option>";
													} else
													echo "<option value='$aREC_TB_JASA[KODE_PENDJ]'  >$aREC_TB_JASA[NAMA_PENDJ]</option>";
												}
											?>
											</select><br>
											<div class="clearfix"></div>

											<div class="form-group">
												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cBUNGA_THN?></label>
												<input type="text" class="col-sm-2 form-label-900" name='P_BUNGA' id="field-2" data-mask="fdecimal" value=<?php echo $REC_EDIT['PINJ_BUNGA']?>><br>
											</div>

											<div class="form-group">
												<label class="col-sm-4 form-label-700"><?php echo $cJN_BUNG?></label>
												<select name='UPD_JN_BUNGA' class="col-sm-5 form-label-900">
													<?php 
														echo "<option value=' '  > </option>";
														$REC_TB_INTR=SYS_QUERY("select * from tb_interest where APP_CODE='$cFILTER_CODE' and DELETOR=''");
														while($aREC_TB_INTR=SYS_FETCH($REC_TB_INTR)){
															if($aREC_TB_INTR['KD_INTR']==$REC_EDIT['JN_BUNGA']){
																echo "<option value='$aREC_TB_INTR[KD_INTR]' selected='$REC_EDIT[JN_BUNGA]' >$aREC_TB_INTR[DESC_INTRS]</option>";
															} else
															echo "<option value='$aREC_TB_INTR[KD_INTR]'  >$aREC_TB_INTR[DESC_INTRS]</option>";
														}
													?>
												</select><br>
											</div>

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
														
														<div class="form-group">
															<label class="col-sm-4 form-label-700"><?php echo $cBIAYA_ADM?></label>
															<input type="text" class="col-sm-3 form-label-900" name='P_ADMIN' data-mask="fdecimal" data-numeric-align="right" value=<?php echo $REC_EDIT['BIAYA_ADM']?>><br>
														</div>

														<div class="form-group">
															<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cBIAYA_PRV?></label>
															<input type="text" class="col-sm-3 form-label-900" name='P_PROVI' id="field-2" data-mask="fdecimal" data-numeric-align="right" value=<?php echo $REC_EDIT['BIAYA_PRV']?>><br>
														</div>

														<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cBIAYA_ASR?></label>
														<input type="text" class="col-sm-3 form-label-900" name='BY_ASURANSI' id="field-3" data-mask="fdecimal" data-numeric-align="right" value=<?php echo $REC_EDIT['BY_ASURANS']?>><br>
														<br>

														<div class="form-group">
															<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('KC12','Biaya Administrasi')?></label>
															<input type="text" class="col-sm-3 form-label-900" name='UPD_BIAYA_DAF' value="<?php echo $REC_EDIT['BIAYA_DAF']?>" id="field-4" data-mask="fdecimal" data-numeric-align="right"><br>
														</div>		

														<label class="col-sm-4 form-label-700" for="field-6"><?php echo S_MSG('KC13','Biaya Adm Bulanan')?></label>
														<input type="text" name='UPD_BIAYA_BLN' value="<?php echo $REC_EDIT['BIAYA_BLN']?>" class="col-sm-3 form-label-900" id="field-8" data-mask="fdecimal" data-numeric-align="right">
														<br>

													</div>		<!-- End of Tab 1 -->
													
													<!-- Tab 2 begin -->
													<div class="tab-pane fade" id="Account-1">
														<label class="col-sm-5 form-label-700" for="field-21"><?php echo $cACCT_PIUTANG?></label>
														<div class="col-sm-6">		
															<select name='UPD_ACCT_PIN' class="form-control m-bot15">
															<?php 
																echo "<option value=' '  > </option>";
																$REC_DATA=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
																	if($aREC_ACCOUNT['ACCOUNT_NO']==$REC_EDIT['ACCT_PIN']){
																		echo "<option value='$REC_EDIT[ACCT_PIN]' selected='$REC_EDIT[ACCT_PIN]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	} else
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																}
															?>
															</select>
														</div>		
														<br><br>

														<label class="col-sm-5 form-label-700" for="field-21"><?php echo $cACCT_PEND_PROVISI?></label>
														<div class="col-sm-6">		
															<select name='UPD_ACCT_PRVSI' class="form-control m-bot15">
															<?php 
																echo "<option value=' '  > </option>";
																$REC_DATA=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
																	if($aREC_ACCOUNT['ACCOUNT_NO']==$REC_EDIT['ACCT_PRVSI']){
																		echo "<option value='$REC_EDIT[ACCT_PRVSI]' selected='$REC_EDIT[ACCT_PRVSI]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	} else
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																}
															?>
															</select>
														</div>		
														<br><br>

														<label class="col-sm-5 form-label-700" for="field-21"><?php echo $cACCT_BEA_DAFTAR?></label>
														<div class="col-sm-6">		
															<select name='UPD_ACCT_DAF' class="form-control col-sm-6">
															<?php 
																echo "<option value=' '  > </option>";
																$REC_DATA=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
																	if($aREC_ACCOUNT['ACCOUNT_NO']==$REC_EDIT['ACCT_DAF']){
																		echo "<option value='$REC_EDIT[ACCT_DAF]' selected='$REC_EDIT[ACCT_DAF]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	} else
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																}
															?>
															</select>
														</div>		
														<br><br>

														<label class="col-sm-5 form-label-700" for="field-21"><?php echo $cACCT_BEA_BULAN?></label>
														<div class="col-sm-6">		
															<select name='UPD_ACCT_BLN' class="form-control col-sm-6">
															<?php 
																echo "<option value=' '  > </option>";
																$REC_DATA=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
																	if($aREC_ACCOUNT['ACCOUNT_NO']==$REC_EDIT['ACCT_BLN']){
																		echo "<option value='$REC_EDIT[ACCT_BLN]' selected='$REC_EDIT[ACCT_BLN]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	} else
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																}
															?>
															</select>
														</div><br>

														<label class="col-sm-5 form-label-700" for="field-21"><?php echo $cACCT_PEND_JASA?></label>
														<div class="col-sm-6">		
															<select name='UPD_ACCT_BUNGA' class="form-control col-sm-6">
															<?php 
																echo "<option value=' '  > </option>";
																$REC_DATA=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
																while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
																	if($aREC_ACCOUNT['ACCOUNT_NO']==$REC_EDIT['ACCT_BUNGA']){
																		echo "<option value='$REC_EDIT[ACCT_BUNGA]' selected='$REC_EDIT[ACCT_BUNGA]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	} else
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																}
															?>
															</select>
														</div><br><br>

													</div>		<!-- End of Tab 2 -->
													
												</div></br>

											</div>
											<div class="clearfix"></div>
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
			<!-- END CONTENT -->
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
	$NOW = date("Y-m-d H:i:s");
	$cKODE_PINJM	= encode_string($_POST['ADD_KODE_PINJM']);	
	if($cKODE_PINJM=='') {
		$cMSG = S_MSG('KA13','Kode Pinjaman tidak boleh kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;

	}
	$cQUERY="select * from tab_pinj where APP_CODE='$cFILTER_CODE' and KODE_PINJM='$_POST[ADD_KODE_PINJM]' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$cNAMA_PINJM	= encode_string($_POST['ADD_NAMA_PINJ']);	
		$cQUERY ="insert into tab_pinj set KODE_PINJM='$_POST[ADD_KODE_PINJM]', NAMA_PINJM='$cNAMA_PINJM'";
		$cQUERY.=", PINJ_PENDJ='$_POST[ADD_PINJ_PENDJ]', PINJ_BUNGA='$_POST[P_BUNGA]', JN_BUNGA='$_POST[ADD_JN_BUNGA]'";
		$cQUERY.=", BIAYA_ADM=".str_replace(',', '', $_POST['P_ADMIN']);
		$cQUERY.=", BIAYA_PRV=".str_replace(',', '', $_POST['P_PROVI']);
		$cQUERY.=", BY_ASURANS=".str_replace(',', '', $_POST['BY_ASURANSI']).", BIAYA_DAF=".str_replace(',', '', $_POST['ADD_BIAYA_DAF']);
		$cQUERY.=", BIAYA_BLN=".str_replace(',', '', $_POST['ADD_BIAYA_BLN']);
		$cQUERY.=", ACCT_PRVSI='$_POST[ADD_ACCT_PRVSI]', ACCT_PIN='$_POST[ADD_ACCT_PIN]'";
		$cQUERY.=", ACCT_DAF='$_POST[ADD_ACCT_DAF]', ACCT_BLN='$_POST[ADD_ACCT_BLN]'";
		$cQUERY.=", ACCT_BUNGA='$_POST[ADD_ACCT_BUNGA]'";
		$cQUERY.=", APP_CODE='$cFILTER_CODE', ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW'";
		SYS_QUERY($cQUERY);
		header('location:kop_tb_pinjaman.php');
	} else {
		$cMSG = S_MSG('KA14','Kode Pinjaman sudah ada');
		echo "<script> alert('$cMSG');		window.history.back();		</script>";
		return;
	}
	break;

case 'rubah':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cNAMA_PINJM	= encode_string($_POST['NM_PINJ']);	
	$cQUERY ="update tab_pinj set NAMA_PINJM='$cNAMA_PINJM',  PINJ_PENDJ=$_POST[UPD_PINJ_PENDJ], PINJ_BUNGA=$_POST[P_BUNGA], JN_BUNGA='$_POST[UPD_JN_BUNGA]', ";
	$cQUERY.=" BIAYA_ADM=".str_replace(',', '', $_POST['P_ADMIN']).", BIAYA_PRV=".str_replace(',', '', $_POST['P_PROVI']).", ";
	$cQUERY.=" BY_ASURANS=".str_replace(',', '', $_POST['BY_ASURANSI']).", BIAYA_DAF=".str_replace(',', '', $_POST['UPD_BIAYA_DAF']).", ";
	$cQUERY.=" BIAYA_BLN=".str_replace(',', '', $_POST['UPD_BIAYA_BLN']);
	$cQUERY.=", ACCT_PRVSI='$_POST[UPD_ACCT_PRVSI]', ACCT_PIN='$_POST[UPD_ACCT_PIN]'";
	$cQUERY.=", ACCT_DAF='$_POST[UPD_ACCT_DAF]', ACCT_BLN='$_POST[UPD_ACCT_BLN]'";
	$cQUERY.=", ACCT_BUNGA='$_POST[UPD_ACCT_BUNGA]'";
	$cQUERY.=", UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and KODE_PINJM='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:kop_tb_pinjaman.php');
	break;

case 'delete':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cQUERY ="update tab_pinj set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW'";
	$cQUERY.="where APP_CODE='$cFILTER_CODE' and KODE_PINJM='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:kop_tb_pinjaman.php?action=1');
}
?>


