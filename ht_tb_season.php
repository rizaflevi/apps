<?php
//	ht_tb_season.php
// Tariff group, eg. weekend/weekdays

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER 		= S_MSG('HS01','Tabel Season');
	$cKD_TBL  		= S_MSG('HS01','Kode Season');
	$cNM_TBL  		= S_MSG('HS03','Nama Season');
	$cDEFAULT 		= S_MSG('HS26','Default');
	$cKET_MULAI		= S_MSG('H074','Mulai          :');
	$cDAFTAR		= S_MSG('H083','Daftar Jenis Season');
	$cEDIT_TBL		= S_MSG('HS11','Edit Tabel Season');
	$cADD_TBL		= S_MSG('HS12','Tambah Tabel Season');

	$cQUERY="select * from ht_seasn where APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);

	$cACTION='';
	if (isset($_GET['action'])) {
		$cACTION=$_GET['action'];
	}

	$cSAVE	= S_MSG('F301','Save');
	$cCLOSE	= S_MSG('F302','Close');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('HS31','Help Tabel Season');
		$cHELP_1	= S_MSG('HS32','Ini adalah modul untuk memasukkan data Season');
		$cHELP_2	= S_MSG('HS33','Seperti contoh nya Tahun Baru atau hari besar dan lain-lain');
		$cHELP_3	= S_MSG('HS34','Untuk memasukkan data Season baru, klik tambah Season / add new');

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
												<a href="?action=create"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_ht_tb_season" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKD_TBL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNM_TBL?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_HT_SEASN=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td class=""><div class="star"><i class="fa fa-newspaper-o icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?action=".md5('up_date')."&_s=".md5($aREC_HT_SEASN['SSN_CODE'])."'>".$aREC_HT_SEASN['SSN_CODE']."</a></span></td>";
															echo "<td><a href='?action=".md5('up_date')."&_s=".md5($aREC_HT_SEASN['SSN_CODE'])."'>".decode_string($aREC_HT_SEASN['SSN_DESC'])."</a></td>";
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
			<div class="modal" id="help_ht_tb_season" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
		</body>
	</html>
<?php
	break;

case "create":
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
								<h1 class="title"><?php echo $cADD_TBL?></h1>                            
							</div>
							<div class="pull-right hidden-xs"></div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<section class="box ">
							<div class="content-body">
								<div class="row">
									<form action ="?action=tambah" method="post">
										<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKD_TBL?></label>
											<input type="text" class="col-sm-2 form-label-900" name='ADD_SSN_CODE' id="field-1">
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNM_TBL?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_SSN_DESC' id="field-2">
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cDEFAULT?></label>
											<div class="form-block"><input name='ADD_SSN_DEFLT' type="checkbox" class="iswitch iswitch-md iswitch-secondary"></div>
											<div class="clearfix"></div><br>

											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE?>>
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
		<?php	require_once("js_framework.php");		?>
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>

		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

<?php
	break;

case md5('up_date'):
		$cQUERY ="select * from ht_seasn where APP_CODE='$cFILTER_CODE' and md5(SSN_CODE)='$_GET[_s]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$REC_HT_SEASN=SYS_FETCH($qQUERY);
?>
		<!DOCTYPE html>
		<html class=" ">
			<?php require_once("scr_header.php");	require_once("scr_topbar.php");	?>
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
											<a href="?action=delete&id=<?php echo $REC_HT_SEASN['SSN_CODE']?>" onClick="return confirm('Apakah Anda benar-benar mau menghapusnya?')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs">
								</div>
								<div class="content-body">
									<div class="row">
										<form action ="?action=rubah&id=<?php echo $REC_HT_SEASN['SSN_CODE']?>" method="post">
											<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKD_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_SSN_CODE' id="field-1" value=<?php echo $REC_HT_SEASN['SSN_CODE']?> disabled="disabled">
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNM_TBL?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_SSN_DESC' id="field-2" value="<?php echo decode_string($REC_HT_SEASN['SSN_DESC'])?>">
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cDEFAULT?></label>
												<div class="form-block"><input type="checkbox" name="EDIT_SSN_DEFLT" <?php if($REC_HT_SEASN['SSN_DEFLT']==1) { echo 'checked'; }?> class="iswitch iswitch-md iswitch-secondary"></div>
												<div class="clearfix"></div><br>

												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE?>>
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

			</body>
		</html>
<?php
	break;
case 'tambah':
	$NOW = date("Y-m-d H:i:s");
	$cSSN_CODE = encode_string($_POST['ADD_SSN_CODE']);
	if($cSSN_CODE=='') {
		$cMSG_BLANK		= S_MSG('HS13','Kode Season tidak boleh kosong');
		echo "<script> alert('$cMSG_BLANK');		window.history.back();		</script>";
		return;
	}
	$cQUERY="select * from ht_seasn where APP_CODE='$cFILTER_CODE' and DELETOR='' and SSN_CODE='$cSSN_CODE'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG_EXIST	= S_MSG('HS14','Kode Season sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
	} else {
		$lCHECK = 0;
		if (isset($_POST['ADD_SSN_DEFLT'])){
			$lCHECK = 1;
		}
		$cSSN_DESC = encode_string($_POST[ADD_SSN_DESC]);
		$cQUERY="insert into ht_seasn set SSN_CODE='$_POST[ADD_SSN_CODE]', SSN_DESC='$cSSN_DESC', SSN_DEFLT=$lCHECK, 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
	}
	$ADD_LOG	= APP_LOG_ADD();
	header('location:ht_tb_season.php');
	break;
case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$lCHECK = 0;
	if (isset($_POST['EDIT_SSN_DEFLT'])){
		$lCHECK = 1;
	}
	$cSSN_DESC = encode_string($_POST[EDIT_SSN_DESC]);
	$cSQL_COMMAND="update ht_seasn set SSN_DESC='$cSSN_DESC', SSN_DEFLT=$lCHECK, 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and SSN_CODE='$KODE_CRUD'";
	SYS_QUERY($cSQL_COMMAND);
	header('location:ht_tb_season.php');
	break;
case 'delete':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY = "update ht_seasn set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' 
		where APP_CODE='$cFILTER_CODE' and SSN_CODE='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:ht_tb_season.php');

	break;
}
?>

