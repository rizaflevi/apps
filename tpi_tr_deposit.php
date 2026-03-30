<?php
/* -------------------------------------------------------------------
	tpi_tr_deposit.php
	memasukkan data deposit bakul
*/

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$sCURRENT_PERIOD=date('Y-m-d');
	if (isset($_GET['PERIOD'])) {
		$sCURRENT_PERIOD=$_GET['PERIOD'];
	}

	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER 	= S_MSG('TF90','Entry Deposit Lelang');

	$cQUERY="SELECT dt_depst.*, tb_beedr.* FROM dt_depst LEFT JOIN tb_beedr ON dt_depst.BIDDR_CODE=tb_beedr.BEEDR_CODE WHERE left(dt_depst.DEP_DATE,7)='".substr($sCURRENT_PERIOD,0,7);
	$cQUERY.="' and dt_depst.APP_CODE='$cAPP_CODE' and dt_depst.DELETOR=''";
	$q_RDEP=SYS_QUERY($cQUERY);
	$cNO_BUKTI	= S_MSG('TF93','Nomor Bukti');
	$cTANGGAL 	= S_MSG('TF92','Tgl. Terima');
	$cKD_BAKUL 	= S_MSG('TF72','Kode Peserta');
	$cNAMA_ANG 	= S_MSG('TF73','Nama Peserta');
	$cNOMINAL 	= S_MSG('TF91','Deposit');
	$cCATATAN 	= S_MSG('NE63','Catatan');
	$cTAMBAH	= S_MSG('TF90','Entry Deposit Lelang');
	
	$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');

	$cSAVE	= S_MSG('F301','Save');
	$cCLOSE	= S_MSG('F302','Close');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('TF9A','Help Transaksi Deposit');
		$cHELP_1	= S_MSG('TF9B','Ini adalah modul untuk memasukkan data Transaksi deposit peserta lelang yang akan mengikuti lelang ikan');
		$cHELP_2	= S_MSG('TF9C','Transaksi ini diperlukan apabila di persyarat kan untuk setiap peserta lelang di haruskan memiliki deposit');
		$cHELP_3	= S_MSG('TF9D','Untuk memasukkan data Transaksi deposit baru, klik tambah deposit / add new');
		$cHELP_4	= S_MSG('TF9E','Transaksi deposit ini akan menambah saldo kas penrima setoran apabila di setor kontan, atau menambah saldo bank jika ditransfer lewat bank.');
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

											<table id="example" class="display table table-hover table-condensed" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th style="background-color:LightGray;width: 1px;"></th>
														<th style="background-color:LightGray;"><?php echo $cNO_BUKTI?></th>
														<th style="background-color:LightGray;"><?php echo $cTANGGAL?></th>
														<th style="background-color:LightGray;"><?php echo $cKD_BAKUL?></th>
														<th style="background-color:LightGray;"><?php echo $cNAMA_ANG?></th>
														<th style="background-color:LightGray;"><?php echo $cNOMINAL?></th>
														<th style="background-color:LightGray;"><?php echo $cCATATAN?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_DT_DEPST=SYS_FETCH($q_RDEP)) {
														echo '<tr>';
//															die ($aREC_DT_DEPST['DEP_VALUE']);
															echo '<td class=""><div class="star"><i class="fa fa-money icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=$aREC_DT_DEPST[DEP_NO]'>".$aREC_DT_DEPST['DEP_NO']."</a></span></td>";
															echo '<td>'.date("d-M-Y", strtotime($aREC_DT_DEPST['DEP_DATE'])).'</td>';
															echo '<td>'.$aREC_DT_DEPST['BIDDR_CODE'].'</td>';
															echo '<td>'.$aREC_DT_DEPST['BEEDR_NAME'].'</td>';
															echo '<td align="right">'.number_format($aREC_DT_DEPST['DEP_VALUE']).'</td>';
															echo '<td>'.$aREC_DT_DEPST['DEP_NOTE'].'</td>';
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

			<script src="assets/plugins/select2/select2.min.js" type="text/javascript"></script> 

			<script src="assets/js/scripts.js" type="text/javascript"></script> 

			<div class="modal" id="help_kop_teller_simpanan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>
							<p><?php echo $cHELP_3?></p>	<p><?php echo $cHELP_4?></p>
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
	$cHELP_BOX	= S_MSG('TF9M','Help Tambah Transaksi Deposit');
	$cHELP_1	= S_MSG('TF9N','Ini adalah modul untuk memasukkan data Transaksi Deposit baru oleh petugas Kasir');
	$cHELP_2	= S_MSG('TF9O','Masukkan data yang diperlukan sesuai formulir setoran yang sudah diisi oleh peserta lelang');
	$cHELP_3	= S_MSG('TF9P','Setelah selesai klik tombol SAVE untuk menyimpan perubahan yang telah dilakukan');

	$cQ_LAST	= "select DEP_NO from dt_depst where APP_CODE='$cAPP_CODE' order by DEP_NO desc limit 1";
	$qQ_LAST	= SYS_QUERY($cQ_LAST);
	$aREC_LAST	= SYS_FETCH($qQ_LAST);
	$cLAST_DEP	= $aREC_LAST['DEP_NO'];
	$nLAST_DEP	= intval($cLAST_DEP)+1;
	$cLAST_DEP	= str_pad((string)$nLAST_DEP, 6, '0', STR_PAD_LEFT);
//	die ('last : '.$nLAST_DEP)
?>
	<!DOCTYPE html>
	<html class=" ">
		<body>  
			<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
			<link href="assets/plugins/select2/select2.css" rel="stylesheet" type="text/css" media="screen"/>
			
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

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNO_BUKTI?></label>
												<input type="text" class="col-sm-3 form-label-900" name="ADD_DEP_NO" id="ADD_DEP_NO" value="<?php echo $cLAST_DEP?>"><br><br>


												<label class="col-sm-4 form-label-700"><?php echo $cKD_BAKUL?></label>
												<div class="col-sm-3 form-label-900" style="padding:0px;">
													<input type="hidden" name="ADD_BIDDR_CODE" id="s2peserta" autofocus/>
												</div>
												<div class="clearfix"></div>
<!--
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_BAKUL?></label>
												<input type="text" class="col-sm-3 form-label-900" name="ADD_BIDDR_CODE" id="ADD_BIDDR_CODE" onblur="Disp_Nama_Bakul(this.value)"><br><br>
-->
												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNAMA_ANG?></label>
												<input type="text" class="col-sm-5 form-label-900" name='ADD_BEEDR_NAME' id="NAMA_ANGGOTA" disabled="disabled"><br><br>

												<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-2 form-label-900" data-mask="date" name='ADD_DEP_DATE' value="<?php echo date('d/m/Y')?>"><br><br>

												<label class="col-sm-4 form-label-700" for="field-7"><?php echo $cNOMINAL?></label>
												<input type="text" class="col-sm-2 form-label-900" data-mask="fdecimal" name='ADD_DEP_VALUE' id="field-2"><br><br>

												<label class="col-sm-4 form-label-700" for="field-7"><?php echo $cCATATAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_DEP_NOTE' id="DEP_NOTE"><br><br><br>
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

			<script src="assets/plugins/select2/select2.min.js" type="text/javascript"></script>
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
									<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNO_BUKTI?></label>
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

	$cHELP_BOX	= S_MSG('TG21','Help Edit Transaksi Deposit');
	$cHELP_1	= S_MSG('TG22','Ini adalah modul untuk merubah data Transaksi Deposit yang telah dimasukkan oleh petugas Kasir');
	$cHELP_2	= S_MSG('TG23','Pertama akan di tampilkan data Transaksi Deposit, klik pada field yang mau di edit');
	$cHELP_9	= S_MSG('TG29','Setelah selesai klik tombol SAVE untuk menyimpan perubahan yang dilakukan.');

	$cQUERY="select DEP_NO, DEP_DATE, BIDDR_CODE from dt_depst where REC_NO='$_GET[_r]' and APP_CODE='$cAPP_CODE' and DELETOR=''";
	$qCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($qCEK_KODE)==0){
		$cNOT_FOUND	= S_MSG('TG07','Nomor Deposit tidak ditemukan');
		$cERR = $cNOT_FOUND." => ".$aREC_UPD_DT_DEPST['DEP_NO'];
		echo "<script> alert('$cERR');
		window.history.back();
		</script>	";
		return;
	}
	$aREC_NOMOR_REK_TAB = SYS_FETCH($qCEK_KODE);

	$cQUERY="select BEEDR_CODE, BEEDR_NAME, BEEDR_CELL from tb_beedr where BEEDR_CODE='$aREC_NOMOR_REK_TAB[BIDDR_CODE]' and APP_CODE='$cAPP_CODE' and DELETOR=''";
	$qCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($qCEK_KODE)==0){
	die ('Q : '. $cQUERY);
		$cNOT_SIMP 	= S_MSG('TG08','Kode Peserta Lelang tidak ditemukan');
		echo "<script> alert('$cNOT_SIMP');
		window.history.back();
		</script>	";
//		return;
	}
	$aREC_TB_MEMBER1 = SYS_FETCH($qCEK_KODE);

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
					<div class="project-info"></div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">

								<div class="pull-left">
									  <h2 class="title"><?php echo S_MSG('TF97','Edit Deposit Lelang')?></h2>
								</div>
								<div class="pull-right">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=<?php echo md5('DEL_DEPOS')?>&id=<?php echo $aREC_UPD_DT_DEPST['REC_NO']?>" onClick="return confirm('<?php echo $cMSG_DEL?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
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
										<form name="Upd_Rek_record" action ="?_a=rubah&id=<?php echo $aREC_UPD_DT_DEPST['REC_NO']?>&x_AMOUNT=<?php echo $aREC_UPD_DT_DEPST['DEP_VALUE']?>&cREK=<?php echo $aREC_UPD_DT_DEPST['SAVE_ACT']?>" method="post"  onSubmit="return CEK_KOP_TB_SIM(this)">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNO_BUKTI?></label>
												<input type="text" class="col-sm-3 form-label-900" name='UPD_DEP_NO' id="field-1" value="<?php echo $aREC_UPD_DT_DEPST['DEP_NO']?>" disabled="disabled"><br><br>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cKD_BAKUL?></label>
												<input type="text" class="col-sm-4 form-label-900" name='NM_ANGG' id="f_BIDDR_CODE" value="<?php echo $aREC_NOMOR_REK_TAB['BIDDR_CODE']?>" disabled="disabled"><br><br>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNAMA_ANG?></label>
												<input type="text" class="col-sm-5 form-label-900" name='UPD_TB_MEMBER1' id="field-1" value="<?php echo $aREC_TB_MEMBER1['NM_DEPAN']?>" disabled="disabled"><br><br>

												<label class="col-sm-4 form-label-700" for="field-5"><?php echo S_MSG('F005','Alamat')?></label>
												<input type="text" class="col-sm-5 form-label-900" name='ALMT_ANG' id="f_ALAMAT" value="<?php echo $aREC_TB_MEMBER1['ALAMAT']?>" disabled="disabled"><br><br>

												<label class="col-sm-4 form-label-700" for="field-7"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-2 form-label-900" data-mask="date" name='UPD_DEP_DATE' id="field-7" value=<?php echo date("d-m-Y", strtotime($aREC_UPD_DT_DEPST['DEP_DATE']))?>><br><br>

												<label class="col-sm-4 form-label-700" for="field-5"><?php echo $cNOMINAL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='UPD_DEP_VALUE' data-mask="fdecimal" value="<?php echo $aREC_UPD_DT_DEPST['DEP_VALUE']?>"><br><br>

												<label class="col-sm-4 form-label-700" for="field-7"><?php echo $cCATATAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='UPD_DEP_NOTE' value="<?php echo $aREC_UPD_DT_DEPST['DEP_NOTE']?>"><br><br>

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
			<script src="sys_js.js" type="text/javascript"></script> 

			<div class="modal" id="help_kop_teller_simpanan_upd" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">

							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_9?></p>

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
	$nAMOUNT = str_replace(',', '', $_POST['ADD_DEP_VALUE']);
	$dTG_SETOR = $_POST['ADD_DEP_DATE'];		// 'dd/mm/yyyy'
	if($_POST['ADD_DEP_NO']=='') {
		$cMSG_BLANK	= S_MSG('TG06','Nomor Deposit tidak boleh kosong');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}

	if($_POST['ADD_DEP_VALUE']<1) {
		$cMSG = S_MSG('TG07','Jumlah nominal setoran tidak boleh kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	$c_BIDD="select BEEDR_CODE, DEPO_SIT, APP_CODE, DELETOR  from tb_beedr where BEEDR_CODE='$_POST[ADD_BIDDR_CODE]' and APP_CODE='$cAPP_CODE' and DELETOR=''";
	$q_BIDD=SYS_QUERY($c_BIDD);
	if(SYS_ROWS($q_BIDD)==0){
		$cNOT_FOUND	= S_MSG('TG08','Kode Peserta Lelang tidak ditemukan');
		echo "<script> alert('$cNOT_FOUND');	window.history.back();	</script>";
		return;
	}
	
	$cQUERY="select * from dt_depst where DEP_NO='$_POST[ADD_DEP_NO]' and DEP_NO='$_POST[ADD_DEP_NO]' and APP_CODE='$cAPP_CODE' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$cDATE = substr($dTG_SETOR,6,4). '-'. substr($dTG_SETOR,3,2). '-'. substr($dTG_SETOR,0,2);
		$cDEP_NOTE	= encode_string($_POST['ADD_DEP_NOTE']);
		$cQUERY ="insert into dt_depst set DEP_NO='$_POST[ADD_DEP_NO]'";
		$cQUERY.=", DEP_DATE='$cDATE'";
		$cQUERY.=", DEP_NOTE='$cDEP_NOTE'";
		$cQUERY.=", BIDDR_CODE='$_POST[ADD_BIDDR_CODE]'";
		$cQUERY.=", DEP_VALUE=0".$nAMOUNT;
		$cQUERY.=", APP_CODE='$cAPP_CODE', ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);

		$aREC_TB_BIDDR=SYS_FETCH($q_BIDD);
		$nBALANCE = $aREC_TB_BIDDR['DEPO_SIT']+$nAMOUNT;
		$q_DT_DEPST=SYS_QUERY("update tb_beedr set DEPO_SIT='$nBALANCE' 
			where BEEDR_CODE='$_POST[ADD_BIDDR_CODE]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		SYS_DB_CLOSE($DB2);
		header('location:tpi_tr_deposit.php');
	} else {
		$cMSG_EXIST	= S_MSG('TG05','Nomor Deposit sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
	}
	SYS_DB_CLOSE($DB2);	break;


case "rubah":
	$KODE_CRUD=$_GET['id'];
	$x_AMOUNT=$_GET['x_AMOUNT'];
	$cREK = $_GET['cREK'];
//	die ($cREK);
	$NOW = date("Y-m-d H:i:s");
	$dTG_SETOR = $_POST['UPD_DEP_DATE'];		// 'dd/mm/yyyy'
	$cDATE = substr($dTG_SETOR,6,4). '-'. substr($dTG_SETOR,3,2). '-'. substr($dTG_SETOR,0,2);
	$nAMOUNT = str_replace(',', '', $_POST['UPD_DEP_VALUE']);

	$q_BIDD=OpenTable('TbBidder', "BEEDR_CODE='$cREK' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	if(SYS_ROWS($q_BIDD)==0){
		$cNOT_FOUND	= S_MSG('TG07','Nomor Deposit tidak ditemukan');
		echo "<script> alert('$cNOT_FOUND');	</script>";
		SYS_DB_CLOSE($DB2);
		return;
	}
	$aREC_TB_BIDDR=SYS_FETCH($q_BIDD);
	$nBALANCE = $aREC_TB_BIDDR['DEPO_SIT']-$x_AMOUNT+$nAMOUNT;
	RecUpdate('TbBidder', ['DEPO_SIT'], [$nBALANCE], "BEEDR_CODE='$cREK' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	
	$RecUpdate('DtDeposit', ['DEP_VALUE', 'DEP_NO', 'DEP_NOTE', 'UP_DATE', 'UPD_DATE'], 
		['$nAMOUNT', $_POST['UPD_DEP_NO'], '$_POST[UPD_DEP_NOTE]', $_SESSION['gUSERCODE'], $NOW],
		"REC_NO=$KODE_CRUD");
	header('location:tpi_tr_deposit.php');
	break;


case md5('DEL_DEPOS'):
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	
	$qCEK_KODE=SYS_QUERY("select * from trm_tab1 where REC_NO='$KODE_CRUD'");
	$aREC_DT_DEPST=SYS_FETCH($qCEK_KODE);
	$nAMOUNT = $aREC_DT_DEPST['DEP_VALUE'];
	$cREK = $aREC_DT_DEPST['DEP_NO'];
	$q_SAVE=SYS_QUERY("select DEP_NO, SV_BALANCE, APP_CODE, DELETOR  from tr_save1 
		where DEP_NO='$cREK' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	if(SYS_ROWS($q_SAVE)==0){
		echo "<script> alert('$cNOT_FOUND');	</script>";
		SYS_DB_CLOSE($DB2);
		return;
	}

	$aREC_TR_SAVE1=SYS_FETCH($q_SAVE);
	$nJADINYA= $aREC_TR_SAVE1['SV_BALANCE'] - $nAMOUNT;
	$cQUERY = "update tr_save1 set SV_BALANCE='$nJADINYA' 
		where DEP_NO='$cREK' and APP_CODE='$cAPP_CODE' and DELETOR=''";
	SYS_QUERY($cQUERY);

	RecSoftDel($_GET['_id']);
	header('location:tpi_tr_deposit.php?');

}

?>

<script>
$("#DEP_NOTE").inputmask("AAA-99999");


            $("#s2peserta").select2({
                minimumInputLength: 1,
                placeholder: 'Search',
                ajax: {
                    url: "tpi_select2peserta.php",
                    dataType: 'json',
                    quietMillis: 100,
                    data: function(term, page) {
                        return {
                            limit: -1,
                            q: term
                        };
                    },
                    results: function(data, page) {
                        return {
                            results: data
                        }
                    }
                },
                formatResult: function(student) {
                    return "<div class='select2-user-result'>" + student.name + "</div>";
                },
                formatSelection: function(student) {
                    return student.name;
                }

            });

function Disp_Nama_Bakul(pkode_rekening) {
	var btn_stat = document.getElementById("SAVE_BUTTON");  // the submit button
//		alert(pkode_rekening);
    if (pkode_rekening == "") {
        document.getElementById("ADD_BIDDR_CODE").innerHTML = "";
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
        xmlhttp.open("GET","tpi_cek_bakul.php?KD_BKL="+pkode_rekening,true);
        xmlhttp.send();
		
    }
}

</script>

 
