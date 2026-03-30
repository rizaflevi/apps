<?php
//	prs_tr_absen.php //
//	copy prs_fpio ke server

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
	}
	$cHEADER = S_MSG('PH81','Absen Karyawan');
	$cACTION = '';
	if (isset($_GET['_a']))	{
		$cACTION = $_GET['_a'];
	}
  
	$cPERIOD1=date("Y-m-d");
	$cPERIOD2=date("Y-m-d");

	if (isset($_GET['_d1'])) {
		$cPERIOD1=$_GET['_d1'];
	}

	if (isset($_GET['_d2'])) {
		$cPERIOD2=$_GET['_d2'];
	}

	$cFILTER_PERSON='';
	if (isset($_GET['_p'])) {
		$cFILTER_PERSON=$_GET['_p'];
	}

	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cADD_ABSEN 	= S_MSG('PH92','Tambah Absen');
	$cEDIT_ABSEN 	= S_MSG('PH93','Edit Absen');
	$cADD_DTL_ABS 	= S_MSG('PH94','Tambah Detil Absen');
	$cEDIT_DTL_ABS 	= S_MSG('PH95','Edit Detil Absen');

	$cKD_PERSON 	= S_MSG('PA02','Kode Peg');
	$cNM_PERSON 	= S_MSG('PA03','Nama Karyawan');
	$cALAMAT 		= S_MSG('PA13','Alamat');
	$cKETERANGAN 	= S_MSG('PA98','Keterangan');
	$cTANGGAL 		= S_MSG('PE03','Tanggal');
	$cJAM			= S_MSG('PS29','Jam');
	$cMESSAG1		= S_MSG('F021','Benar data ini mau di hapus ?');
	
	$cSAVE_DATA		= S_MSG('F301','Save');
	$cCLOSE_DATA	= S_MSG('F302','Close');

	$cHDR_BACK_CLR = S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray');
	$cFOOTER_STYLE = S_PARA('_FOOTER_STYLE','font-size: 24px;color: Brown;background-color: LightGray');

	$cQUERY="select prs_fpio.USERID, prs_fpio.CHECKDATE, prs_fpio.CHECKTIME, prs_fpio.CHECKTYPE, prs_fpio.PRSON_CODE, prs_fpio.FP_NOTE, prs_fpio.APP_CODE, 
		person1.PRSON_CODE, person1.PRSON_NAME from prs_fpio 
		LEFT JOIN person1 ON prs_fpio.PRSON_CODE=person1.PRSON_CODE
		where prs_fpio.CHECKDATE>='".$cPERIOD1. "' and prs_fpio.CHECKDATE<='".$cPERIOD2. "' and prs_fpio.APP_CODE='$cFILTER_CODE' and prs_fpio.DELETOR=''";
	if($cFILTER_PERSON!='') {
		$cQUERY.=" and  prs_fpio.PRSON_CODE='$cFILTER_PERSON'";
	}
	$qQUERY=SYS_QUERY($cQUERY);
	
	$cTHIS_PERIOD	= date("Y-m");

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'View');
		$cHELP_BOX	= S_MSG('PH9A','Help Absen Karyawan');
		$cHELP_1	= S_MSG('PH9B','Ini adalah modul untuk memasukkan data Absen Karyawan baik absen masuk maupun absen keluar');
		$cHELP_2	= S_MSG('PH9C','Untuk memasukkan data Absen baru, klik tambah Absen / add new');
		$cHELP_3	= S_MSG('PH9D','Sekarang ini ditampilkan daftar karyawan yang terdapat pada master karyawan / pegawai');
		$cHELP_4	= S_MSG('PH9E','Untuk merubah atau menambah salah satu data Absen, klik di kode atau nama karyawan dan akan masuk ke mode update');
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
												<?php echo '<a href="?_a='.md5('cr34t3').'"> <i class="fa fa-plus-square"></i>'.S_MSG('KA11','Add new').'</a>'?>
											</li>
											<li>
												<a href="#help_prs_tr_absen" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>
								<label class="col-sm-1 form-label-700" for="field-4"><?php echo $cTANGGAL?></label>
								<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $cPERIOD1?>" onchange="FILT_ABSEN('<?php echo $cFILTER_PERSON?>', this.value, '<?php echo $cPERIOD2?>')">

								<div class="form-group">
									<label class="col-sm-1 form-label-700" style="text-align: right"><?php echo S_MSG('RS14','s/d')?></label>
									<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $cPERIOD2?>" onchange="FILT_ABSEN('<?php echo $cFILTER_PERSON?>', '<?php echo $cPERIOD1?>', this.value)">
								</div>

								<div class="form-group">
									<label class="col-sm-2 form-label-700" style="text-align: right"><?php echo $cNM_PERSON?></label>
									<select name="PILIH_PEGAWAI" class="col-sm-4 form-label-900" onchange="FILT_ABSEN(this.value, '<?php echo $cPERIOD1?>', '<?php echo $cPERIOD2?>')">
									<?php 
										$REC_PERSON1=SYS_QUERY("select PRSON_CODE, PRSON_NAME from person1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
										while($a_PERSON1=SYS_FETCH($REC_PERSON1)){
											if($a_PERSON1['PRSON_CODE']==$cFILTER_PERSON){
												echo "<option value='$a_PERSON1[PRSON_CODE]' selected='$cFILTER_PERSON' >$a_PERSON1[PRSON_NAME]</option>";
											} else {
												echo "<option value='$a_PERSON1[PRSON_CODE]'  >$a_PERSON1[PRSON_NAME]</option>";
											}
										}
									?>
									</select>
									<div class="clearfix"></div>
								</div>

								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>">
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKD_PERSON?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNM_PERSON?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTANGGAL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;">In/Out</th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cJAM?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKETERANGAN?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														$nTOTAL = 0;
														while($a_PRS_FPIO=SYS_FETCH($qQUERY)) {
															echo '<tr>';
																$cICON = 'fa fa-clock-o';
																echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
																echo "<td><span><a href='?_a=".md5('up__date')."&_p3r50n=".md5($a_PRS_FPIO['PRSON_CODE'])."'>". $a_PRS_FPIO['PRSON_CODE']."</a></span></td>";
																echo "<td><span><a href='?_a=".md5('up__date')."&_p3r50n=".md5($a_PRS_FPIO['PRSON_CODE'])."'>".decode_string($a_PRS_FPIO['PRSON_NAME']).'</td>';
																echo '<td>'.$a_PRS_FPIO['CHECKDATE'].'</td>';
																echo "<td><span>". ($a_PRS_FPIO['CHECKTYPE']=='I' ? 'MASUK' : 'KELUAR') ." </span></td>";
																echo '<td>'.$a_PRS_FPIO['CHECKTIME'].'</td>';
																echo '<td>'.$a_PRS_FPIO['FP_NOTE'].'</td>';
															echo '</tr>';
														}
													?>
												</tbody>
												<tr></tr>
												<td></td><td></td><td></td>
												<tr></tr>	
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
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<div class="modal" id="help_prs_tr_absen" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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

case md5('cr34t3'):
	$cHELP_ADD		= S_MSG('PH9K','Help Transaksi Tambah Absen Baru');
	$cHELP_ADD_1	= S_MSG('PH9L','Ini adalah modul untuk memasukkan data Absen baru, yang belum pernah terdapat sebelumnya');
	$cHELP_ADD_2	= S_MSG('PH9M','Masukkan Kode Pegawai yang akan dimasukkan data absen nya');
?>
	<!DOCTYPE html>
	<html class=" ">    <body class=" " onload="prs_tr_absent_focus();">
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
					<div class="clearfix"></div>
					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<div class="pull-right hidden-xs"></div>
						<header class="panel_header">
							<h2 class="title pull-left"><?php echo $cADD_ABSEN?></h2>
							<div class="pull-right">
								<ol class="breadcrumb">
									<li>
										<a href="#help_add_tr_receipt" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
									</li>
								</ol>
							</div>
						</header>	
						<section class="box ">
							<div class="content-body">
								<div class="row">
									<form name="FORM_ADD_ABSEN" action ="?_a=tambah" method="post">
										<div class="col-lg-8 col-xs-12">
											<div class="form-group">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_PERSON?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_PRSON_CODE' id="field-1" value="<?php echo $cKD_PEGAWAI?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNM_PERSON?></label>
												<div class="controls">
													<input type="text" class="col-sm-3 form-label-900 datepicker" name='ADD_PRSON_NAME' data-mask="date" id="field-2" value="<?php echo date('d/m/Y')?>">
												</div>
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cALAMAT?></label>
												<div class="controls">
													<input type="text" class="col-sm-4 form-label-900" name='ADD_ADDR1' id="field-2" autofocus>
												</div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cJABATAN?></label>
												<select id="SelectUpdEvent" name="ADD_BANK" class="form-label-900 m-bot15">
												<?php 
													echo "<option value=' '  >Cash</option>";
													$REC_DATA=SYS_QUERY("select * from bank where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($aREC_BANK=SYS_FETCH($REC_DATA)){
														echo "<option value='$aREC_BANK[B_CODE]'  >$aREC_BANK[B_NAME]</option>";
													}
												?>
												</select>
											</div>
<!-- ************************************************************ -->
											<div class="form-group">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cACCOUNT?></label>
												<div class="controls">
													<select id="SelectAccount" name="ADD_DTL_ACCOUNT_DN" class="form-label-900 m-bot15" onchange="Disp_Account()">
													<?php 
														$REC_DATA=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
														while($aREC_DETAIL=SYS_FETCH($REC_DATA)){
															echo "<option value='$aREC_DETAIL[ACCOUNT_NO]'  >$aREC_DETAIL[ACCT_NAME]</option>";
														}
													?>
													</select>
												</div>												
												
											</div><br><br>
<!--							
											<div class="form-group">
												<label class="col-sm-4 form-label-700" for="field-2"></?php echo $cKD_ACCOUNT?></label>
												<div class="controls">
													<input type="text" class="form-label-900 m-bot15" name='ADD_DTL_ACCOUNT_NO' id="f_NO_ACCOUNT" style="width:20%">
												</div>
											</div>
-->
											<div class="form-group">
												<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cALAMAT?></label>
												<div class="controls">
													<input type="text" class="form-label-900" name='ADD_DTL_ADDR1' id="field-2" style="width:60%">
												</div>
												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cJABATAN?></label>
												<div class="controls">
													<input type="text" class="form-label-900" name='ADD_DTL_AMOUNT' id="field-3" data-mask="fdecimal" value=0 style="width:30%">
												</div>
											</div>
<!-- ************************************************************ -->
							
											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
<!--												<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>	-->
												<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=window.location.href='prs_tr_absen.php'>
											</div><br><br>
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

        <div class="modal" id="help_add_tr_receipt" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title"><?php echo $cHELP_ADD?></h4>
					</div>
					<div class="modal-body">

					<p><?php echo $cHELP_ADD_1?></p>	<p><?php echo $cHELP_ADD_2?></p>

					</div>
					<div class="modal-footer">
						<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
					</div>
				</div>
            </div>
        </div>
	</body>	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case md5('up__date'):
	$cHELP_UPD		= S_MSG('PH9R','Help Transaksi Update Absen Pegawai');
	$cHELP_UPD_1	= S_MSG('PH9S','Ini adalah modul untuk merubah sekaligus menambahkan absen per pegawai.');
	$cHELP_UPD_2	= S_MSG('PH9T','Fungsi ini juga digunakan untuk menghapus data absen.');

	$cQUERY ="select * from person1 where md5(PRSON_CODE)='$_GET[_p3r50n]' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$qQUERY =SYS_QUERY($cQUERY);
	$a_PRS_FPIO=SYS_FETCH($qQUERY);
	$cPRSON_CODE = $a_PRS_FPIO['PRSON_CODE'];
	$UPD_ACCOUNT = '1';
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
									<h2 class="title"><?php echo $cEDIT_ABSEN?></h2>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=<?php echo md5('del_absen')?>&KJ=<?php echo $a_PRS_FPIO['PRSON_CODE']?>&NJ=<?php echo $a_PRS_FPIO['NO_TRANS']?>" onClick="return confirm('<?php echo $cMESSAG1?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
										</li>
										<li>
											<a href="#help_upd_tr_absent" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
										<form action ="?_a=rubah&KJ=<?php echo $a_PRS_FPIO['PRSON_CODE']?>&NJ=<?php echo $a_PRS_FPIO['NO_TRANS']?>" method="post">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKD_PERSON?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_PRSON_CODE' id="field-1" value=<?php echo $a_PRS_FPIO['PRSON_CODE']?> disabled="disabled"><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNM_PERSON?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_PRSON_NAME' id="field-2" value="<?php echo decode_string($a_PRS_FPIO['PRSON_NAME'])?>" disabled="disabled"><br><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cALAMAT?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_ADDR1' id="field-2" value="<?php echo decode_string($a_PRS_FPIO['ADDR1'])?>" <?php echo ' disabled="disabled">'?>
												<div class="clearfix"></div>

											</div>

											<div class="col-md-12 col-sm-12 col-xs-12">
												<table id="example" class="display table table-hover table-condensed" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTANGGAL?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo 'In/Out'?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right"><?php echo $cJAM?></th>
														</tr>
													</thead>
													<tbody>
														<div>
															<a href="#add_absen" data-toggle="modal" > <i class="fa fa-plus-square"></i><?php echo $cADD_DTL_ABS?></a>
														</div>
														<?php
															$cQ_DTL ="select * from prs_fpio 
																where PRSON_CODE='$cPRSON_CODE' and left(CHECKDATE,7)='$cTHIS_PERIOD' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
//		die ($cQ_DTL);
															$qQ_JRN =SYS_QUERY($cQ_DTL);
															while($aREC_PRS_FPIO=SYS_FETCH($qQ_JRN)) {
																echo '<tr>';
																	echo "<td><span><a href='?_a=edit_detail_trans&DTL_REC_NO=$aREC_PRS_FPIO[FP_REC]'>". $aREC_PRS_FPIO['CHECKDATE'].'</a></span></td>';
																	echo "<td><span><a href='?_a=edit_detail_trans&DTL_REC_NO=$aREC_PRS_FPIO[FP_REC]'>". $aREC_PRS_FPIO['CHECKTIME'].'</a></span></td>';
//																	echo '<td align="right">'.number_format($aREC_PRS_FPIO['NILAI']).'</td>';
																echo '</tr>';
															}
														?>
														<tr></tr>
														<tr>
															 <td style="<?php echo $cFOOTER_STYLE?>;"></td>
															 <td style="<?php echo $cFOOTER_STYLE?>;"></td>
															 <td style="<?php echo $cFOOTER_STYLE?>;"></td>
														</tr>
														<td></td><td></td><td></td>
														<tr></tr>
													</tbody>
												</table>
											</div>
											
											<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="text-left">
<!--												<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>		-->
												<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=window.location.href='prs_tr_absen.php'>
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

			<div class="modal" id="add_absen" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<form action ="?_a=upd_add_dtl&KODE_PEGAWAI=<?php echo $cPRSON_CODE?>" method="post">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title"><?php echo $cADD_DTL_ABS?></h4>
							</div>
							<div class="modal-body">

								<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cTANGGAL?></label>
								<input type="text" class="col-sm-4 form-label-900 datepicker" data-format="dd-MM-yyyy" name='ADD_UPD_TGL' value="<?php echo date('d-M-Y')?>">

								<div class="clearfix"></div>
								<label class="col-sm-4 form-label-700" for="field-1"><?php echo 'Event' ?></label>
								<div class="controls">
									<select id="SelectUpdEvent" name="ADD_UPD_EVENT" class="col-sm-8 form-label-900">
									<?php 
										$r_PRS_EVEN=SYS_QUERY("select EVEN_CODE, EVEN_NAME from prs_even where APP_CODE='$cFILTER_CODE' and DELETOR=''");
										while($aREC_DETAIL=SYS_FETCH($r_PRS_EVEN)){
											echo "<option value='$aREC_DETAIL[EVEN_CODE]'  >'$aREC_DETAIL[EVEN_NAME]'</option>";
										}
									?>
									</select>
								</div><br>

								<div class="clearfix"></div>
.								<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cJAM?></label>
								<input type="text" class="col-sm-3 form-label-900" name='ADD_CHECKTIME' id="field-3" value='00:00'>
							</div><br><br>
							<div class="modal-footer">
								<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
								<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		  
			<div class="modal" id="upd_upd-detail" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cEDIT_DTL_ABS?></h4>
						</div>
						<div class="modal-body">

							<div class="form-group">
								<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cALAMAT?></label>
								<div class="controls">
									<input type="text" class="form-label-900" name='UPD_ADDR1' id="field-2" style="width:60%">
								</div>
								<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cJAM?></label>
								<div class="controls">
									<input type="text" class="form-label-900" name='UPD_DEBIT' id="field-3" data-mask="fdecimal" style="width:30%">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
							<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						</div>
					</div>
				</div>
			</div>
		  
			<div class="modal" id="help_upd_tr_absent" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_UPD?></h4>
						</div>
						<div class="modal-body">

							<p><?php echo $cHELP_UPD_1?></p>	<p><?php echo $cHELP_UPD_2?></p>

						</div>
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
							<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						</div>
					</div>
				</div>
			</div>
		</body>
	</html>
<?php
	SYS_DB_CLOSE($DB2);	break;

case "edit_detail_trans":
	$eDTL_REC_NO = $_GET['DTL_REC_NO'];
	$qQUERY=SYS_QUERY("select * from terima3 where TRM3_RECNO=$eDTL_REC_NO");
	$aREC_DETAIL=SYS_FETCH($qQUERY);

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
					<div class="project-info">	</div>
				</div>
				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>
						<div class="clearfix"></div>

						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left"><?php echo $cHEADER?></h2><br><br>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>
												<a href="?_a=upd_del_dtl&DTL_RECN=<?php echo $aREC_DETAIL['TRM3_RECNO']?>" onClick="return confirm('<?php echo $cMESSAG1?>')"><i class="glyphicon glyphicon-minus-sign"></i><?php echo S_MSG('F304','Delete')?></a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									
									<form action ="?_a=upd_upd_dtl&DTL_RECN=<?php echo $eDTL_REC_NO?>" method="post">
										<div class="form-group">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cACCOUNT?></label>
											<select name='UPD_UPD_ACCOUNT_NO' class="form-label-900 m-bot15">
												<?php 
													echo "<option value=' '  > </option>";
													$REC_ACCT=SYS_QUERY("select * from prs_even where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($aREC_PRS_EVEN=SYS_FETCH($REC_ACCT)){
														if($aREC_PRS_EVEN['ACCOUNT_NO']==$aREC_DETAIL['ACCOUNT']){
															echo "<option value='$aREC_DETAIL[ACCOUNT]' selected='$aREC_DETAIL[ACCOUNT]' >$aREC_PRS_EVEN[ACCT_NAME]</option>";
														} else
														echo "<option value='$aREC_PRS_EVEN[ACCOUNT_NO]'  >$aREC_PRS_EVEN[ACCT_NAME]</option>";
													}
												?>
											</select>
											<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cALAMAT?></label>
											<div class="controls">
												<input type="text" class="form-label-900" name='UPD_UPD_ADDR1' id="field-2" style="width:60%" value="<?php echo $aREC_DETAIL['ADDR1']?>">
											</div>
											<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cJAM?></label>
											<div class="controls">
												<input type="text" class="form-label-900" name='UPD_UPD_VALUE' id="field-3" data-mask="fdecimal" style="width:20%" value="<?php echo $aREC_DETAIL['NILAI']?>">
											</div>
										</div>
										<div class="text-left">
											<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
											<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
										</div>
									</form>
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

			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
		</body>
	</html>
<?php
	SYS_DB_CLOSE($DB2);	break;

case 'tambah':
	$NOW = date("Y-m-d H:i:s");
	$dTG_BAYAR = $_POST['ADD_PRSON_NAME'];		// 'dd/mm/yyyy'
	$cDATE = substr($dTG_BAYAR,6,4). '-'. substr($dTG_BAYAR,3,2). '-'. substr($dTG_BAYAR,0,2);
	$nVALUE = str_replace(',', '', $_POST['ADD_DTL_AMOUNT']);
	if($_POST['ADD_PRSON_CODE']==''){
		$cMSG= S_MSG('NP46','Nomor Pembayaran masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	if($_POST['ADD_PRSON_NAME']==''){
		$cMSG= S_MSG('NP47','Tanggal Pembayaran masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from terima1 where APP_CODE='$cFILTER_CODE' and DELETOR='' and PRSON_CODE='$_POST[ADD_PRSON_CODE]'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG= S_MSG('NP48','Nomor Pembayaran sudah ada');
		echo "<script> alert('$cMSG');
		window.history.back();
		</script>	";
		return;
	} else {
		$cQUERY="insert into terima1 set PRSON_CODE='$_POST[ADD_PRSON_CODE]', 
			PRSON_NAME='$cDATE', ADDR1='$_POST[ADD_ADDR1]', BANK='$_POST[ADD_BANK]', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);

		$cQUERY="insert into terima3 set PRSON_CODE='$_POST[ADD_PRSON_CODE]', 
			ACCOUNT='$_POST[ADD_DTL_ACCOUNT_DN]', ADDR1='$_POST[ADD_DTL_ADDR1]', 
			NILAI='$nVALUE', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:prs_tr_absen.php');
	}
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$NOMOR_BYR=$_GET['KJ'];
	$NO_JRNL=$_GET['NJ'];
	$dTG_BAYAR = $_POST['EDIT_PRSON_NAME'];		// 'dd/mm/yyyy'
	$cDATE = substr($dTG_BAYAR,6,4). '-'. substr($dTG_BAYAR,3,2). '-'. substr($dTG_BAYAR,0,2);

	$cQUERY = "update terima1 set ADDR1='$_POST[EDIT_ADDR1]', ";
	$cQUERY.= " PRSON_NAME='$cDATE', BANK='$_POST[UPD_BANK]', ";
	$cQUERY.= " UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW' ";
	$cQUERY.= " where APP_CODE='$cFILTER_CODE' and PRSON_CODE='$NOMOR_BYR' and DELETOR=''";
//	die ($cQUERY);
	$qQUERY = SYS_QUERY($cQUERY);
	header('location:prs_tr_absen.php');
	break;

case md5('del_absen'):
	$NOW = date("Y-m-d H:i:s");
	$NOMOR_BYR=$_GET['KJ'];
	$NO_JRNL=$_GET['NJ'];
	$cQUERY ="update terima1 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and PRSON_CODE='$NOMOR_BYR'";
	$qQUERY =SYS_QUERY($cQUERY);

	$cQUERY="update terima3 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' 
			where APP_CODE='$cFILTER_CODE' and DELETOR='' and PRSON_CODE='$NOMOR_BYR'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:prs_tr_absen.php');
	break;

case 'adddtl':
	$cPOST = $_POST['ADD_DTL_ACCOUNT_NO'];
	$nDEBET = $_POST['ADD_DTL_DEBIT'];
	if($_POST['ADD_DTL_ACCOUNT_NO']==''){
		$cMSG= S_MSG('NP38','Kode account pembayaran masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
		header('location:prs_tr_absen.php');
	break;

case 'upd_add_dtl':
	$NOW = date("Y-m-d H:i:s");
	$cPOST = $_POST['ADD_UPD_EVENT'];
	if($cPOST==''){
		$cMSG= S_MSG('PH96','Kode event masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:prs_tr_absen.php');
	}
	if($_POST['ADD_CHECKTIME']==0){
		$cMSG= S_MSG('NP39','Nilai pembayaran masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:prs_tr_absen.php');
	}

	$KODE_PEGAWAI = $_GET['KODE_PEGAWAI'];
	$nVALUE = str_replace(',', '', $_POST['ADD_CHECKTIME']);
	$cQUERY="select * from prs_fpio where APP_CODE='$cFILTER_CODE' and DELETOR='' and PRSON_CODE='$KODE_PEGAWAI'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	$cQUERY="insert into prs_fpio set PRSON_CODE='$KODE_PEGAWAI', 
			ACCOUNT='$_POST[ADD_UPD_EVENT]', CHECKDATE='$_POST[ADD_UPD_TGL]', 
			NILAI='$nVALUE', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:prs_tr_absen.php?_a='.md5('up__date').'&KODE_RECORD='.$KODE_PEGAWAI);
	break;

case 'upd_upd_dtl':
	$nREC_NO = $_GET['DTL_RECN'];
	$qUPD_DTL_QUERY=SYS_QUERY("select PRSON_CODE from terima3 where TRM3_RECNO=$nREC_NO");
	$aREC_UPD_DETAIL=SYS_FETCH($qUPD_DTL_QUERY);
//	die ($aREC_UPD_DETAIL['PRSON_CODE']);
	$nDEBET = $_POST['UPD_UPD_VALUE'];
	if($_POST['UPD_UPD_ACCOUNT_NO']==''){
		$cMSG= S_MSG('NJ80','Kode account masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:prs_tr_absen.php');
	}
	if($nDEBET==0){
		$cMSG= S_MSG('NP51','Nilai pembayaran masih kosong, harap diisi');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		header('location:prs_tr_absen.php?_a='.md5('up__date').'&KODE_RECORD=$cPRSON_CODE');
		return;
	}

	$cQUERY="update terima3 set ACCOUNT='$_POST[UPD_UPD_ACCOUNT_NO]', ";
	$cQUERY.=" ADDR1='$_POST[UPD_UPD_ADDR1]', ";
	$cQUERY.=" NILAI=".str_replace(',', '', $_POST['UPD_UPD_VALUE']).", "; 
	$cQUERY.=" UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW' where TRM3_RECNO=$nREC_NO";
//	die ($cQUERY);
		SYS_QUERY($cQUERY);
		echo "<script> window.history.back();	window.history.back();	</script>";
		header("location:prs_tr_absen.php?_a=".md5('up__date')."&KODE_RECORD=$aREC_UPD_DETAIL[PRSON_CODE]");
		return;
	break;

case 'upd_del_dtl':
	$nREC_NO = $_GET['DTL_RECN'];

	$cQUERY="update terima3 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' where TRM3_RECNO=$nREC_NO";
	$qQUERY =SYS_QUERY($cQUERY);
	SYS_QUERY($cQUERY);
	echo "<script> window.history.back();	window.history.back();	</script>";
	return;
	break;
}
?>

<script>

function prs_tr_absent_focus()  
{  
	var uid = document.FORM_ADD_ABSEN.ADD_ADDR1.focus();  
	return true;  
}  
function FILT_ABSEN(p_PRS, p_TGL1, p_TGL2) {
	window.location.assign("?_p="+p_PRS + "&_d1="+p_TGL1 + "&_d2="+p_TGL2);
}
</script>

