<?php
//	tpi_tb_bidder.php //
// 	Peserta lelang / bakul

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$qQUERY=SYS_QUERY("select * from tb_beedr where APP_CODE='$cFILTER_CODE' and DELETOR=''");

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

	$cHEADER 	= S_MSG('TF71','Tabel Peserta Lelang');
	$cKODE_TBL 	= S_MSG('F003','Kode');
	$cNAMA_TBL 	= S_MSG('F004','Nama');
	$cNOMOR_HP 	= S_MSG('TF54','No. HP');
	$cDEPOSIT 	= S_MSG('TF75','Deposit');
	$cDAFTAR	= S_MSG('TF80','Daftar Peserta Lelang');
	$cADD_REC	= S_MSG('TF86','Tambah Peserta Lelang');
	$cEDIT_TBL	= S_MSG('TF87','Edit Tabel Peserta Lelang');
	
	$cTTIP_KODE	= S_MSG('TF81','Setiap peserta lelang di beri kode');
	$cTTIP_NAMA	= S_MSG('TF82','Nama peserta lelang yang terdaftar');
	$cTTIP_HAPE	= S_MSG('TF83','Nomor HP peserta lelang');
	$cTTIP_DEPO	= S_MSG('TF84','Jumlah deposit peserta lelang sebagai syarat lelang');
	
switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'view');
		$cHELP_BOX	= S_MSG('TF8A','Help Tabel Peserta Lelang');
		$cHELP_1	= S_MSG('TF8B','Ini adalah modul untuk memasukkan data Peserta Lelang yang terdapat pada sistem lelang di sebuah TPI.');
		$cHELP_2	= S_MSG('TF8C','Untuk memasukkan data Peserta Lelang baru, klik tambah Peserta Lelang / add new');
		$cHELP_3	= S_MSG('TF8D','Untuk merubah data Peserta Lelang yang sudah masuk, klik di kode Peserta Lelang atau nama Peserta Lelang');
		$cHELP_4	= S_MSG('TF8E','Terdapat pilihan apakah Peserta Lelang bisa mengikuti lelang, harus dengan deposit atau tidak. Pilihan ini bisa di set di bagian sistem parameter');

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
									<h2 class="title pull-left"><?php echo $cHEADER?></h2>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>
												<a href="?_a=<?php echo md5('cr34t3')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_tb_bidder" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="background-color:LightGray;"><?php echo $cKODE_TBL?></th>
														<th style="background-color:LightGray;"><?php echo $cNAMA_TBL?></th>
														<th style="background-color:LightGray;text-align:right;"><?php echo $cDEPOSIT?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_BIDDR=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td style="width: 1px;"></td>';
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_i=".md5($aREC_BIDDR['BEEDR_CODE'])."'>".$aREC_BIDDR['BEEDR_CODE']."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('up_d4t3')."&_i=".md5($aREC_BIDDR['BEEDR_CODE'])."'>".decode_string($aREC_BIDDR['BEEDR_NAME'])."</a></span></td>";
															echo '<td align="right">'.number_format($aREC_BIDDR['DEPO_SIT']).'</td>';
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

			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 

			<div class="modal" id="help_tb_bidder" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
	$cHELP_BOX	= S_MSG('TF8J','Help Tambah Tabel Jenis Ikan baru');
	$cHELP_1	= S_MSG('TF8K','Ini adalah modul untuk memasukkan data peserta lelang baru ketika ada peserta lelang baru yang mau di daftarkan.');
	$cHELP_2	= S_MSG('TF8L','Masukkan kode peserta lelang baru, kemudian masukkan nama peserta lelang, no. hp dan jumlah deposit saat ini jika ada.');
	$cHELP_3	= S_MSG('TF8M','Klik Save untuk menyimpan data peserta lelang baru yang ditambahkan atau close untuk mengabaikan.');
?>
	<!DOCTYPE html>
	<html class=" ">	<body>
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
								<h2 class="title"><?php echo $cADD_REC?></h2>                            
							</div>
							<div class="pull-right">									 
								<ol class="breadcrumb">
									<li>
										<a href="#help_add_tb_bidder" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
									</li>
								</ol>
							</div>		
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<section class="box ">
							<div class="content-body">
								<div class="row">
									<form name="Add_New_record" action ="?_a=tambah" method="post">
										<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
										<input type="text" class="col-sm-2 form-label-900" name='ADD_BEEDR_CODE' title="<?php echo $cTTIP_KODE?>" autofocus><br><br>

										<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
										<input type="text" class="col-sm-5 form-label-900" name='ADD_BEEDR_NAME' title="<?php echo $cTTIP_NAMA?>"><br><br>

										<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNOMOR_HP?></label>
										<input type="text" class="col-sm-5 form-label-900" name='ADD_BEEDR_CELL' title="<?php echo $cTTIP_HAPE?>"><br><br>

										<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cDEPOSIT?></label>
										<input type="text" class="col-sm-3 form-label-900" name='ADD_DEPO_SIT' title="<?php echo $cTTIP_DEPO?>" data-mask="fdecimal">

										<div class="clearfix"></div><br><br>
										<div class="text-left">
											<input type="submit" class="btn btn-primary" value=<?php echo S_MSG('F301','Save')?>>
											<input type="button" class="btn" value=<?php echo S_MSG('F302','Close')?> onclick=self.history.back()>
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

		<div class="modal" id="help_add_tb_bidder" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
	</body>	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case md5('up_d4t3'):
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cQUERY 	= "select * from tb_beedr where APP_CODE='$cFILTER_CODE' and md5(BEEDR_CODE)='$_GET[_i]' and DELETOR=''";
	$qQUERY 	= SYS_QUERY($cQUERY);
	$REC_BIDDR	= SYS_FETCH($qQUERY);
?>
	<!DOCTYPE html>
	<html class=" ">
		<body class=" ">
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
									  <h2 class="title"><?php echo $cEDIT_TBL?></h2>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<?php echo '<a href="?_a='.md5('d3l_biddr').'&_i='. md5($REC_BIDDR['BEEDR_CODE']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
										</li>
									<li>
										<a href="#help_tpi_upd_tb_fish" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
										<form action ="?_a=rubah&_i=<?php echo $REC_BIDDR['BEEDR_CODE']?>" method="post">
											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
											<input type="text" class="col-sm-2 form-label-900" name='EDIT_BEEDR_CODE' title="<?php echo $cTTIP_KODE?>" value=<?php echo $REC_BIDDR['BEEDR_CODE']?> disabled="disabled"><br><br>

											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
											<input type="text" class="col-sm-5 form-label-900" name='EDIT_BEEDR_NAME' title="<?php echo $cTTIP_NAMA?>" value="<?php echo decode_string($REC_BIDDR['BEEDR_NAME'])?>"><br><br>

											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNOMOR_HP?></label>
											<input type="text" class="col-sm-5 form-label-900" name='EDIT_BEEDR_CELL' title="<?php echo $cTTIP_HAPE?>" value="<?php echo decode_string($REC_BIDDR['BEEDR_CELL'])?>"><br><br>

											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cDEPOSIT?></label>
											<input type="text" class="col-sm-3 form-label-900" name='EDIT_DEPO_SIT' title="<?php echo $cTTIP_DEPO?>" data-mask="fdecimal" value="<?php echo $REC_BIDDR['DEPO_SIT']?>">

											<div class="clearfix"></div><br><br>
											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo S_MSG('F301','Save')?>>
												<input type="button" class="btn" value=<?php echo S_MSG('F302','Close')?> onclick=self.history.back()>
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

		</body>
	</html>
<?php
	SYS_DB_CLOSE($DB2);	break;

case 'tambah':
	$NOW = date("Y-m-d H:i:s");
	$cKODE_BIDR	= encode_string($_POST['ADD_BEEDR_CODE']);	
	if($cKODE_BIDR==''){
		$cMSG_BLANK	= S_MSG('TF88','Kode Peserta Lelang belum diisi');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from tb_beedr where APP_CODE='$cFILTER_CODE' and DELETOR='' and BEEDR_CODE='$cKODE_BIDR'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG_EXIST	= S_MSG('TF89','Kode Peserta Lelang sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
	} else {
		$cBEEDR_NAME = encode_string($_POST[ADD_BEEDR_NAME]);
		$cBEEDR_CELL = encode_string($_POST[ADD_BEEDR_CELL]);
		$cQUERY="insert into tb_beedr set BEEDR_CODE='$cKODE_BIDR', BEEDR_NAME='$cBEEDR_NAME', BEEDR_CELL='$$cBEEDR_CELL'";
		$cQUERY.=", DEPO_SIT=0".str_replace(',', '', $_POST['ADD_DEPO_SIT']);
		$cQUERY.=", ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		APP_LOG_ADD($cHEADER, 'add '.$cKODE_BIDR);
		header('location:tpi_tb_bidder.php');
	}
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['_i'];
	$cBEEDR_NAME = encode_string($_POST[EDIT_BEEDR_NAME]);
	$cBEEDR_CELL = encode_string($_POST[EDIT_BEEDR_CELL]);
	$cQUERY ="update tb_beedr set BEEDR_NAME='$cBEEDR_NAME'";
		$cQUERY.=", BEEDR_CELL='$cBEEDR_CELL'";
		$cQUERY.=", DEPO_SIT=0".str_replace(',', '', $_POST['EDIT_DEPO_SIT']);
		$cQUERY.=", UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'";
		$cQUERY.=" where APP_CODE='$cFILTER_CODE' and BEEDR_CODE='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	APP_LOG_ADD($cHEADER, 'update : '.$KODE_CRUD);
	header('location:tpi_tb_bidder.php');
	break;

case md5('d3l_biddr'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['_i'];
	$cQUERY ="update tb_beedr set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(BEEDR_CODE)='$KODE_CRUD'";
	$qQUERY.=SYS_QUERY($cQUERY);
	APP_LOG_ADD($cHEADER, 'delete : '.$KODE_CRUD);
	header('location:tpi_tb_bidder.php');
}
?>

<script>
</script>

