<?php
//	tr_receipt.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$sPERIOD1=date("Y-m-d");
	if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];

	$cHEADER = S_MSG('NR01','Penerimaan Kas');
	$cACTION = '';
	if (isset($_GET['_a']))	$cACTION = $_GET['_a'];
  
	$ada_PURCHASE = false;
	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSER_CODE 	= $_SESSION['gUSERCODE'];

	$cADD_RECEIPT 	= S_MSG('NR23','Tambah Penerimaan');
	$cEDIT_RECEIPT 	= S_MSG('NR25','Edit Penerimaan');
	$cADD_DTL_RCP 	= S_MSG('NR26','Tambah Detil Penerimaan');
	$cEDIT_DTL_JRN 	= S_MSG('NR27','Edit Detil Penerimaan');

	$cKD_TRM 		= S_MSG('NR02','No. Penerimaan');
	$cTANGGAL 		= S_MSG('NR03','Tanggal');
	$cNIL_TRN		= S_MSG('NR09','Nilai');
	$cKD_ACCOUNT 	= S_MSG('NR07','Account');
	$cACCOUNT		= S_MSG('NR08','Nama Account');
	$cKETERANGAN 	= S_MSG('NR04','Keterangan');
	$cMESSAG1		= S_MSG('F021','Benar data ini mau di hapus ?');
	$cBANK_NAME		= S_MSG('F131','Nama Bank');
	$cNOMOR_CEK		= S_MSG('NR10','No. Cek');
	$cDUE_DATE		= S_MSG('NR11','Jatuh Tempo');
	$cTTIP_NOMOR	= S_MSG('NR12','Nomor bukti penerimaan');
	$cTTIP_TGLTR	= S_MSG('NR14','Tanggal terima');
	$cTTIP_KETRG	= S_MSG('NR15','Keterangan tambahan mengenai penerimaan ini');
	$cTTIP_BANK		= S_MSG('NR18','klik untuk memilih kode bank dimana masuk nya penerimaan');
	$cTTIP_DESC		= S_MSG('NR1C','Keterangan mengenai detil penerimaan');
	$cTTIP_VALUE	= S_MSG('NR1D','Nilai atau jumlah penerimaan');

	$cSAVE_DATA=S_MSG('F301','Save');
	$cCLOSE_DATA=S_MSG('F302','Close');
	
	$dDATE1	= date('Y-m-01');
	$dDATE2	= date('Y-m-d');
	
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$cHELP_BOX		= S_MSG('NR24','Help Penerimaan');
		$cHELP_1		= S_MSG('NR31','Ini adalah modul untuk memasukkan data Penerimaan baik penerimaan kas atau bank');
		$cHELP_2		= S_MSG('NR32','Untuk memasukkan data Penerimaan baru, klik tambah Penerimaan / add new');
		$cHELP_3		= S_MSG('NR33','Sekarang ini ditampilkan daftar Penerimaan yang telah pernah dimasukkan');
		$cHELP_4		= S_MSG('NR34','Untuk merubah salah satu data Penerimaan, klik di nomor Penerimaan dan akan masuk ke mode update');
?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	?>
		<body class=" ">
			<?php	require_once("scr_top_prd.php");	?>
			<div class="page-container row-fluid">
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
						<?php	require_once("scr_menu.php");	?>
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
												<a href="#help_tr_receipt" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKD_TRM?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTANGGAL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKETERANGAN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cNIL_TRN?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														$nTOTAL = 0;
														$qQUERY=OpenTable('TrReceiptHdr', "left(A.TGL_BAYAR,7)='".substr($sPERIOD1,0,7)."' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", '', "A.NO_TRM desc");
														while($aREC_TERIMA1=SYS_FETCH($qQUERY)) {
															echo '<tr>';
																$cICON = 'fa fa-money';
																if($aREC_TERIMA1['BANK']!='') {
																	$cICON = 'fa-bank';
																}
																echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
																echo "<td><span><a href='?_a=".md5('upd4t3')."&_r=".md5('99'+$aREC_TERIMA1['NO_TRM'])."'>". $aREC_TERIMA1['NO_TRM']."</a></span></td>";
																echo '<td>'.date("d-M-Y", strtotime($aREC_TERIMA1['TGL_BAYAR'])).'</td>';
																echo '<td>'.$aREC_TERIMA1['DESCRP'].'</td>';
																$nAMOUNT = 0;
																$dQUERY=OpenTable('TrReceiptDtl', "A.NO_TRM='$aREC_TERIMA1[NO_TRM]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
																while($aREC_PAYMENT=SYS_FETCH($dQUERY)) {
																	$nAMOUNT 	+= $aREC_PAYMENT['NILAI'];
																}
																echo '<td align="right">'.number_format($nAMOUNT).'</td>';
																$nTOTAL += $nAMOUNT;
															echo '</tr>';
														}
													?>
												</tbody>
												<tr></tr>
												<tr>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;">Total</td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nTOTAL)?></td>
												</tr>
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
				<!-- END CONTENT -->
				<?php	include "scr_chat.php";	?>
			</div>
			<?php	require_once("js_framework.php");	?>
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<div class="modal" id="help_tr_receipt" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
		APP_LOG_ADD($cHEADER, 'view');
		break;

	case md5('cr34t3'):
		$cHELP_ADD		= S_MSG('NR3A','Help Transaksi Tambah Penerimaan Baru');
		$cHELP_ADD_1	= S_MSG('NR3B','Ini adalah modul untuk memasukkan data Penerimaan baru, yang belum pernah terdapat sebelumnya');
		$cHELP_ADD_2	= S_MSG('NR3C','Nomor Penerimaan dan tanggal Penerimaan diisi otomatis oleh sistem.');
		$cHELP_ADD_3	= S_MSG('NR3D','Nomor Penerimaan tidak bisa diisi, diambil dari nomor terakhir.');
		$cHELP_ADD_4	= S_MSG('NR3E','Tanggal penerimaan default tanggal hari ini.');
		$cHELP_ADD_5	= S_MSG('NR3F','Data yang dimasukkan : ');
		$cHELP_ADD_6	= S_MSG('NR3G','- Keterangan        : Keterangan mengenai penerimaan ini.');
		$cHELP_ADD_7	= S_MSG('NR3H','- Nama Bank         : Pilih Cash/Kontan apabila penerimaan berupa uang cash. Nilai ini akan mengupdate saldo kas.');
		$cHELP_ADD_8	= S_MSG('NR3I','-                     Apabila penerimaan berupa transfer atau cek, pilih bank tempat dana masuk. Daftar bank diambil dari tabel bank yang sudah dimasukkan.');
		$cHELP_ADD_9	= S_MSG('NR3J','- Nama Account      : Untuk menentukan account kredit dari penerimaan ini. ( Account debet nya otomatis kas / bank ).');
		$cHELP_ADD_A	= S_MSG('NR3K','- Nilai             : Nilai penerimaan.');
		$cHELP_ADD_B	= S_MSG('NR3L','- Keterangan        : Keterangan untuk detil penerimaan ini.');

		$cPICT_OR = S_PARA('PICT_OR', '999999');
		$cLAST_NOM	= '0';
		$qQ_LAST	= OpenTable('TrReceiptHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", '', "A.NO_TRM desc limit 1");
		if (SYS_ROWS($qQ_LAST)>0)	{
			$aREC_TERIMA1= SYS_FETCH($qQ_LAST);
			$cLAST_NOM	= $aREC_TERIMA1['NO_TRM'];
		}
		$nLAST_NOM	= intval($cLAST_NOM)+1;
		$cLAST_NOM	= str_pad((string)$nLAST_NOM, strlen($cPICT_OR), '0', STR_PAD_LEFT);
		if (isset($_GET['tabulator'])) {
			exit();
		}
	?>
	<!DOCTYPE html>
	<html class=" ">
    <?php require_once("scr_header.php"); ?>
    <body class=" ">
    <?php require_once("scr_topbar.php");	?>
		<div class="page-container row-fluid">

			<div class="page-sidebar ">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper">
					<?php	require_once("scr_menu.php");	?>
				</div>
				<div class="project-info"></div>
			</div>
			
			<!-- START CONTENT -->
			<section id="main-content" class=" ">
				<section class="wrapper main-wrapper" style=''>
					<div class="clearfix"></div>
					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<div class="pull-right hidden-xs"></div>
						<header class="panel_header">
							<h2 class="title pull-left"><?php echo $cADD_RECEIPT?></h2>
							<div class="pull-right">
								<ol class="breadcrumb">
									<li>	<a href="#help_add_tr_receipt" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>	</li>
								</ol>
							</div>
						</header>	

						<section class="box ">
							<div class="content-body">
								<div class="row">
									<form id="addReceipt" name="FORM_ADD_RECEIPT" action ="?_a=tambahXXX" method="post">
										<div class="col-lg-12 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_TRM?></label>
												<input type="text" class="col-sm-2 form-label-900" name='ADD_NO_TRM' id="field-1" value="<?php echo $cLAST_NOM?>" title="<?php echo $cTTIP_NOMOR?>" readonly>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_TGL_BAYAR' data-mask="date" id="field-2" value="<?php echo date('d/m/Y')?>" title="<?php echo $cTTIP_TGLTR?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_DESCRP' id="field-2" title="<?php echo $cTTIP_KETRG?>" autofocus>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cBANK_NAME?></label>
												<select id="Select_Rec_Bank" name="ADD_BANK" class="col-sm-4 form-label-900" title="<?php echo $cTTIP_BANK?>">
												<?php 
													echo "<option value=' '  >Cash</option>";
													$qQUERY_BANK=OpenTable('TbBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
													while($aREC_BANK=SYS_FETCH($qQUERY_BANK)){
														echo "<option value='$aREC_BANK[B_CODE]'  >$aREC_BANK[B_NAME]</option>";
													}
												?>
												</select><br>
												<div class="clearfix"></div>
												
												<div id="NUM_CEK" class="form-group">
													<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNOMOR_CEK?></label>
													<input type="text" class="col-sm-6 form-label-900" name='ADD_TRM_CEK' id="field-2">
													<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cDUE_DATE?></label>
													<input type="text" class="col-sm-3 form-label-900" data-mask="date" name='ADD_TRM_DD' id="field-2" value=<?php echo date("d-m-Y")?>>
												</div>	<div class="clearfix"></div><br><br>
                                                <div class="" id="tabulator"></div>

												<!--table id="example" class="display table table-hover table-condensed" cellspacing="0">
													<thead>
														<tr>
															<th class="col-lg-5" style="background-color:LightGray;"><?php echo $cKETERANGAN?></th>
															<th class="col-lg-5" style="background-color:LightGray;"><?php echo $cACCOUNT?></th>
															<th class="col-lg-2" style="background-color:LightGray;"><?php echo $cNIL_TRN?></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><input type="text" class="col-lg-12 col-sm-12 form-label-900" name="ADD_DTL_DESCRP1" id="ADD_DTL_DESCRP1"></td>
	                                                        <td><div class="form-group">
																<select id="SelectAccount" style="padding:0" name="ADD_DTL_ACCOUNT1" class="col-lg-12 col-sm-12 col-xs-12 form-label-900">
																<?php 
																	$qQUERY_ACCT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'ACCOUNT_NO');
																	while($aREC_DETAIL=SYS_FETCH($qQUERY_ACCT)){
																		echo "<option value='$aREC_DETAIL[ACCOUNT_NO]'  >$aREC_DETAIL[ACCT_NAME]</option>";
																	}
																?>
																</select><br>	
															</div></td>
															<td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_DTL_AMOUNT1' id="field-3" data-mask="fdecimal" data-numeric-align="right"></td>
														</tr>
														<tr>
															<td><input type="text" class="col-lg-12 col-sm-12 form-label-900" name="ADD_DTL_DESCRP2" id="ADD_DTL_DESCRP1"></td>
	                                                        <td><div class="form-group">
																<select id="SelectAccount" style="padding:0" name="ADD_DTL_ACCOUNT2" class="col-lg-12 col-sm-12 col-xs-12 form-label-900">
																<?php 
																	$qQUERY_ACCT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'ACCOUNT_NO');
																	while($aREC_DETAIL=SYS_FETCH($qQUERY_ACCT)){
																		echo "<option value='$aREC_DETAIL[ACCOUNT_NO]'  >$aREC_DETAIL[ACCT_NAME]</option>";
																	}
																?>
																</select><br>	
															</div></td>

															<td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_DTL_AMOUNT2' id="field-3" data-mask="fdecimal" data-numeric-align="right"></td>
															<div class="clearfix"></div>
														</tr>
													</tbody>
 												</table-->
											</div>
											<div class="text-left">
                                                <button id="saveReceipt" class="btn btn-primary"><?php echo $cSAVE_DATA?></button>
												<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=window.location.href='tr_receipt.php'>
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
    <script type="text/javascript" src="assets/plugins/tabulator/js/tabulator.min.js"></script>
    <script type="text/javascript" src="assets/plugins/select2/select2.min.js"></script>
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

						<p><?php echo $cHELP_ADD_1?></p>	<p><?php echo $cHELP_ADD_2?></p>	<p><?php echo $cHELP_ADD_3?></p>	<p><?php echo $cHELP_ADD_4?></p>
						<p><?php echo $cHELP_ADD_5?></p>	<p><?php echo $cHELP_ADD_6?></p>	<p><?php echo $cHELP_ADD_7?></p>	<p><?php echo $cHELP_ADD_8?></p>
						<p><?php echo $cHELP_ADD_9?></p>	<p><?php echo $cHELP_ADD_A?></p>	<p><?php echo $cHELP_ADD_B?></p>

					</div>
					<div class="modal-footer">
						<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
					</div>
				</div>
            </div>
        </div>
	</body>	</html>

	<?php
		break;

	case md5('upd4t3'):
		$cHELP_UPD		= S_MSG('NPC1','Help Transaksi Update Pembayaran');
		$cHELP_UPD_1	= S_MSG('NPC2','Ini adalah modul untuk merubah / update data Pembayaran yang sudah pernah terdapat sebelumnya');
		$cHELP_UPD_2	= S_MSG('NPC3','Fungsi ini juga digunakan untuk menghapus data pengeluaran Kas / Bank');

		$qQUERY=OpenTable('TrReceiptHdr', "md5('99'+NO_TRM)='$_GET[_r]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", '', "A.NO_TRM desc");
		$aREC_TERIMA1=SYS_FETCH($qQUERY);
		$cNO_TRM = $aREC_TERIMA1['NO_TRM'];
		$UPD_ACCOUNT = '1';
	?>
		<!DOCTYPE html>
		<html class=" ">
			<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">				
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper">
						<?php	require_once("scr_menu.php");	?>
					</div>
					<div class="project-info"></div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">

								<div class="pull-left">
									<h2 class="title"><?php echo $cEDIT_RECEIPT?></h2>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>	<a href="?_a=<?php echo md5('del_receipt')?>&_r=<?php echo $aREC_TERIMA1['REC_ID']?>" onClick="return confirm('<?php echo $cMESSAG1?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>	</li>
										<li>	<a href="#help_upd_tr_receipt" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>	</li>
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
										<form action ="?_a=ru_bah&_r=<?php echo $aREC_TERIMA1['REC_ID']?>" method="post">
											<div class="col-lg-8 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_TRM?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_NO_TRM' id="field-1" value=<?php echo $aREC_TERIMA1['NO_TRM']?> disabled="disabled" title="<?php echo $cTTIP_NOMOR?>">
												<div class="clearfix"></div>
												
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cTANGGAL?></label>
												<input type="date" class="col-sm-3 form-label-900" data-mask="date" name='EDIT_TGL_BAYAR' id="field-2" value=<?php echo $aREC_TERIMA1['TGL_BAYAR']?> title="<?php echo $cTTIP_TGLTR?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_DESCRP' id="field-2" value="<?php echo $aREC_TERIMA1['DESCRP']?>" title="<?php echo $cTTIP_KETRG?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cBANK_NAME?></label>
												<select id="SelectUpdAccount" name="UPD_BANK" class="col-sm-3 form-label-900" title="<?php echo $cTTIP_BANK?>">

												<?php 
													echo "<option value=' '  >Cash</option>";
													$qQUERY=OpenTable('TbBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
													while($aREC_BANK=SYS_FETCH($qQUERY)){
														if($aREC_BANK['B_CODE']==$aREC_TERIMA1['BANK']){
															echo "<option value='$aREC_TERIMA1[BANK]' selected='$aREC_TERIMA1[BANK]' >$aREC_BANK[B_NAME]</option>";
														} else
														echo "<option value='$aREC_BANK[B_CODE]'  >$aREC_BANK[B_NAME]</option>";
													}
												?>
												</select>	<div class="clearfix"></div><br>
											</div>

											<div class="col-md-12 col-sm-12 col-xs-12">
												<table id="example" class="display table table-hover table-condensed" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th style="background-color:LightGray;"><?php echo $cKETERANGAN?></th>
															<th style="background-color:LightGray;"><?php echo $cACCOUNT?></th>
															<th style="background-color:LightGray;text-align:right"><?php echo $cNIL_TRN?></th>
														</tr>
													</thead>
													<tbody>
														<div>
															<a href="#upd_add_detail" data-toggle="modal" > <i class="fa fa-plus-square"></i><?php echo $cADD_DTL_RCP?></a>
														</div>
														<?php
															$cQ_DTL=OpenTable('TrReceiptDtl', "A.NO_TRM='$cNO_TRM' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
															$nTOTAL = 0;
															while($aREC_DTL_RECEIPT=SYS_FETCH($cQ_DTL)) {
																echo '<tr>';
																	echo "<td><span><a href='?_a=".md5('edit_detail_trans')."&_r=$aREC_DTL_RECEIPT[REC_ID]'>". $aREC_DTL_RECEIPT['KET'].'</a></span></td>';
																	echo "<td><span><a href='?_a=".md5('edit_detail_trans')."&_r=$aREC_DTL_RECEIPT[REC_ID]'>". $aREC_DTL_RECEIPT['ACCT_NAME'].'</a></span></td>';
																	echo '<td align="right">'.number_format($aREC_DTL_RECEIPT['NILAI']).'</td>';
																echo '</tr>';
																$nTOTAL += $aREC_DTL_RECEIPT['NILAI'];
															}
														?>
														<tr></tr>
														<tr>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;">Total</td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nTOTAL)?></td>
														</tr>
														<td></td><td></td><td></td>
														<tr></tr>
													</tbody>
												</table>
												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
													<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=window.location.href='tr_receipt.php'>
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

			<div class="modal" id="upd_add_detail" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<form action ="?_a=upd_add_dtl&_r=<?php echo $cNO_TRM?>" method="post">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title"><?php echo $cADD_DTL_RCP?></h4>
							</div>
							<div class="modal-body">

								<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cACCOUNT?></label>
								<select id="SelectUpdAccount" name="ADD_UPD_ACCOUNT" class="col-sm-6 form-label-900 m-bot15">
								<?php 
									$qQUERY_ACCT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
									while($aREC_DETAIL=SYS_FETCH($qQUERY_ACCT)){
										echo "<option value='$aREC_DETAIL[ACCOUNT_NO]'  >$aREC_DETAIL[ACCT_NAME]</option>";
									}
								?>
								</select>
								<div class="clearfix"></div>

								<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cKETERANGAN?></label>
								<input type="text" class="col-sm-6 form-label-900" name='ADD_UPD_DESCRP' id="field-2" style="width:60%">
								<div class="clearfix"></div>

								<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNIL_TRN?></label>
								<input type="text" class="col-sm-3 form-label-900" name='ADD_DTL_VALUE' id="field-3"  data-mask="fdecimal" value=0 style="width:30%">
								<div class="clearfix"></div>
							</div>
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
							<h4 class="modal-title"><?php echo $cEDIT_DTL_JRN?></h4>
						</div>
						<div class="modal-body">

							<div class="form-group">
<!--								<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cACCOUNT?></label>
								<div class="controls">
									<input type="text" class="form-label-900" name='UPD_ACCOUNT_NO' id="field-1" value="<?php echo $_GET['UPD_ACCOUNT']?>" style="width:60%">
								</div>
-->
								<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cKETERANGAN?></label>
								<div class="controls">
									<input type="text" class="form-label-900" name='UPD_DESCRP' id="field-2" style="width:60%">
								</div>
								<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNIL_TRN?></label>
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
		  
			<div class="modal" id="help_upd_tr_receipt" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
			<!-- modal end -->
		</body>
		</html>
	<?php
		break;

	case md5('edit_detail_trans'):
		$cREC_ID = $_GET['_r'];
		$qQUERY=OpenTable('TrReceiptDtl', "A.REC_ID='$cREC_ID'");
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
						<?php	require_once("scr_menu.php");	?>
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
												<a href="?_a=upd_del_dtl&_r=<?php echo $cREC_ID?>" onClick="return confirm('<?php echo $cMESSAG1?>')"><i class="glyphicon glyphicon-minus-sign"></i><?php echo S_MSG('F304','Delete')?></a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									
									<form action ="?_a=upd_upd_dtl&_r=<?php echo aREC_DETAIL['REC_ID']?>" method="post">
										<div class="form-group">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cACCOUNT?></label>
											<select name='UPD_UPD_ACCOUNT_NO' class="col-sm-6 form-label-900" title="<?php echo S_MSG('NR1A','Account untuk detil penerimaan')?>">
												<?php 
													echo "<option value=' '  > </option>";
													$qQUERY_ACCT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
													while($aREC_ACCOUNT=SYS_FETCH($qQUERY_ACCT)){
														if($aREC_ACCOUNT['ACCOUNT_NO']==$aREC_DETAIL['ACCOUNT']){
															echo "<option value='$aREC_DETAIL[ACCOUNT]' selected='$aREC_DETAIL[ACCOUNT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
														} else
														echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
													}
												?>
											</select>
											
											<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cKETERANGAN?></label>
											<input type="text" class="col-sm-6 form-label-900" name='UPD_UPD_DESCRP' id="field-2" value="<?php echo $aREC_DETAIL['KET']?>" title="<?php echo $cTTIP_DESC?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNIL_TRN?></label>
											<input type="number" class="col-sm-3 form-label-900" name='UPD_UPD_VALUE' id="field-3" data-mask="fdecimal" value="<?php echo $aREC_DETAIL['NILAI']?>" title="<?php echo $cTTIP_VALUE?>">
											<div class="clearfix"></div>
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
				<!-- END CONTENT -->
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
		break;

	case 'tambahXXX':
		var_dump($_POST);
		exit();
		$NOW 		= date("Y-m-d H:i:s");
		$dTG_BAYAR	= $_POST['ADD_TGL_BAYAR'];		// 'dd/mm/yyyy'
		$cDATE 		= substr($dTG_BAYAR,6,4). '-'. substr($dTG_BAYAR,3,2). '-'. substr($dTG_BAYAR,0,2);
		$dCEK_DATE 	= $_POST['ADD_TRM_DD'];		// 'dd/mm/yyyy'
		$cCEK_DATE 	= substr($dCEK_DATE,6,4). '-'. substr($dCEK_DATE,3,2). '-'. substr($dCEK_DATE,0,2);
		$cNO_TRM	= $_POST['ADD_NO_TRM'];
		if($cNO_TRM==''){
			$cMSG= S_MSG('NR41','Nomor Penerimaan masih kosong');
			echo "<script> alert('$cMSG');	window.history.back();	</script>";
			return;
		}
		if($_POST['ADD_TGL_BAYAR']==''){
			$cMSG= S_MSG('NR42','Tanggal Penerimaan masih kosong');
			echo "<script> alert('$cMSG');	window.history.back();	</script>";
			return;
		}
		$cQUERY=OpenTable('TrReceiptHdr', "NO_TRM='$cNO_TRM' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($cQUERY)>0){
			$cMSG= S_MSG('NR43','Nomor penerimaan kas sudah ada');
			echo "<script> alert('$cMSG');		window.history.back();		</script>";
			return;
		} else {
			$nRec_id = date_create()->format('Uv');
			$cRec_id = (string)$nRec_id;
			RecCreate('TrReceiptHdr', ['NO_TRM', 'TGL_BAYAR', 'DESCRP', 'ENTRY', 'REC_ID', 'APP_CODE'], 
				[$cNO_TRM, $cDATE, encode_string($_POST['ADD_DESCRP']), $cUSER_CODE, $cRec_id, $cAPP_CODE]);

			for ($I=1; $I < 3; $I++) {
				$cIDX = (string)$I;
				$nRec_id = date_create()->format('Uv');
				$cRec_id = (string)$nRec_id;
				$nVALUE 	= str_replace(',', '', $_POST['ADD_DTL_AMOUNT'.$cIDX]);
				if($nVALUE>0)	{
					RecCreate('TrReceiptDtl', ['NO_TRM', 'ACCOUNT', 'KET', 'NILAI', 'ENTRY', 'REC_ID', 'APP_CODE'], 
					[$cNO_TRM, $_POST['ADD_DTL_ACCOUNT'.$cIDX], encode_string($_POST['ADD_DTL_DESCRP'.$cIDX]), $nVALUE, $cUSER_CODE, $cRec_id, $cAPP_CODE]);
				}
			}
			APP_LOG_ADD($cHEADER, 'add '.$cNO_TRM);
			header('location:tr_receipt.php');
		}
		break;

	case 'ru_bah':
		$NOW 		= date("Y-m-d H:i:s");
		$cNO_TRM	= $_GET['_r'];
		$dTG_BAYAR 	= $_POST['EDIT_TGL_BAYAR'];		// 'dd/mm/yyyy'
		$cDATE 		= substr($dTG_BAYAR,6,4). '-'. substr($dTG_BAYAR,3,2). '-'. substr($dTG_BAYAR,0,2);

		RecUpdate('TrReceiptHdr', ['DESCRP', 'TGL_BAYAR', 'BANK'], [$_POST['EDIT_DESCRP'], $cDATE, $_POST['UPD_BANK']], 
			"REC_ID='$cNO_TRM'");
		APP_LOG_ADD($cHEADER, 'edit '.$cNO_TRM);
		header('location:tr_receipt.php');
		break;

	case 'del_receipt':
		RecSoftDel($_GET['_r']);
		header('location:tr_receipt.php');
		break;

	case 'upd_add_dtl':
		$cPOST = $_POST['ADD_UPD_ACCOUNT'];
		if($cPOST==''){
			$cMSG= S_MSG('NR45','Kode account penerimaan masih kosong');
			echo "<script> alert('$cMSG');	window.history.back();	</script>";
			return;
			header('location:tr_receipt.php');
		}
		if($_POST['ADD_DTL_VALUE']==0){
			$cMSG= S_MSG('NR46','Nilai penerimaan masih kosong');
			echo "<script> alert('$cMSG');	window.history.back();	</script>";
			return;
			header('location:tr_receipt.php');
		}

		$cNO_TRM = $_GET['_r'];
		$nVALUE = str_replace(',', '', $_POST['ADD_DTL_VALUE']);
		$nRec_id = date_create()->format('Uv');
		$cRec_id = (string)$nRec_id;
		RecCreate('TrReceiptDtl', ['NO_TRM', 'KET', 'ACCOUNT', 'NILAI', 'ENTRY', 'APP_CODE', 'REC_ID'], 
			[$cNO_TRM, $_POST['ADD_UPD_DESCRP'], $_POST['ADD_UPD_ACCOUNT'], $nVALUE, $_SESSION['gUSERCODE'], $cAPP_CODE, $cRec_id]);
		header('location:tr_receipt.php?_a='.md5('upd4t3').'&_r='.$cNO_TRM);
		break;

	case 'upd_upd_dtl':
		$cREC_NO = $_GET['_r'];
		$qUPD_DTL_QUERY = OpenTable('TrReceiptDtl', "REC_ID='$cREC_NO'");
		$aREC_UPD_DETAIL=SYS_FETCH($qUPD_DTL_QUERY);
		$nDEBET = $_POST['UPD_UPD_VALUE'];
		if($_POST['UPD_UPD_ACCOUNT_NO']==''){
			$cMSG= S_MSG('NR45','Kode account penerimaan masih kosong');
			echo "<script> alert('$cMSG');	window.history.back();	</script>";
			return;
			header('location:tr_receipt.php');
		}
		if($nDEBET==0){
			$cMSG= S_MSG('NR46','Nilai penerimaan masih kosong');
			echo "<script> alert('$cMSG');	window.history.back();	</script>	";
			header("location:tr_receipt.php?_a=".md5('upd4t3')."&_r=".md5(concat('99'+$aREC_UPD_DETAIL['NO_TRM'])));
			return;
		}

		RecUpdate('TrReceiptDtl', ['ACCOUNT', 'KET', 'NILAI'], 
			[$_POST['UPD_UPD_ACCOUNT_NO'], $_POST['UPD_UPD_DESCRP'], str_replace(',', '', $_POST['UPD_UPD_VALUE'])], "REC_ID='$cREC_NO'");
		header("location:tr_receipt.php?_a=".md5('upd4t3')."&_r=".md5(concat('99',$aREC_UPD_DETAIL['NO_TRM'])));
		return;
		break;

	case 'upd_del_dtl':
		RecSoftDel($_GET['_r']);
		header("location:tr_receipt.php?_a=".md5('upd4t3')."&_r=$aREC_UPD_DETAIL[NO_TRM]");
		break;

		case 'dropdownTable':
			$dropdownTable = array();
			$qQUERY_ACCT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'ACCOUNT_NO');
			while($aREC_DETAIL=SYS_FETCH($qQUERY_ACCT)) $dropdownTable[] = ['text' => $aREC_DETAIL['ACCT_NAME'], 'id' => $aREC_DETAIL['ACCOUNT_NO']];
			print json_encode($dropdownTable);
			exit();
	}
?>

<script>
$(function() {
    if($('#Select_Rec_Bank').val() == ' ') {
		$('#NUM_CEK').hide();
	} else {
		$('#NUM_CEK').show();
	}
    $('#Select_Rec_Bank').change(function(){
        if($('#Select_Rec_Bank').val() != ' ') {
            $('#NUM_CEK').show(); 
        } else {
            $('#NUM_CEK').hide(); 
        } 
    });
});

</script>

