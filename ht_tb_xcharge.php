<?php
//	ht_tb_xcharge.php
// Salutation, eg. Mr. Mrs.

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER 		= S_MSG('H235','Tabel Extra Charge');
	$cKD_TBL  		= S_MSG('H231','Kode Extra Charge');
	$cNM_TBL  		= S_MSG('H232','Nama Extra Charge');
	$cKETERANGAN  	= S_MSG('H233','Keterangan');
	$cLBL_RATE  	= S_MSG('H236','Rate');
	$cTAX_DTL 		= S_MSG('H237','Tax Detail');
	$cDAFTAR		= S_MSG('H245','Daftar Extra Charge');
	$cEDIT_TBL		= S_MSG('H247','Edit Tabel Extra Charge');
	$cADD_TBL		= S_MSG('H248','Tambah Tabel Extra Charge');
	$cCONFIRM		= S_MSG('H007','Benar data ini mau di hapus ?');
	
	$cTTIP_CODE		= S_MSG('H240','Setiap Extra Charge harus di beri kode, supaya bisa diakses berdasarkan kode');
	$cTTIP_NAME		= S_MSG('H241','Nama Extra Charge');

	$cQUERY="select * from ht_xcrge where APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

	$cSAVE	= S_MSG('F301','Save');
	$cCLOSE	= S_MSG('F302','Close');

switch($cACTION){
	default:
		$cHELP_BOX		= S_MSG('H24A','Help Tabel Extra Charge');
		$cHELP_1		= S_MSG('H24B','Ini adalah modul untuk memasukkan data Extra Charge');
		$cHELP_2		= S_MSG('H24C','Seperti contoh nya Early Check-in, Laundry dan lain-lain');
		$cHELP_3		= S_MSG('H24D','Untuk memasukkan data Extra Charge baru, klik tambah Extra Charge / add new');

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
												<a href="?_a=<?php echo md5('cr34t3')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_ht_tb_xcharge" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="background-color:LightGray;"><?php echo $cKD_TBL?></th>
														<th style="background-color:LightGray;"><?php echo $cNM_TBL?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_HT_XCRGE=SYS_FETCH($qQUERY)) {
															echo '<tr>';
															echo '<td class=""><div class="star"><i class="fa fa-newspaper-o icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?_a=".md5('upd4t3')."&_x=".md5($aREC_HT_XCRGE['XCRGE_CODE'])."'>".decode_string($aREC_HT_XCRGE['XCRGE_CODE'])."</a></span></td>";
															echo "<td><a href='?_a=".md5('upd4t3')."&_x=".md5($aREC_HT_XCRGE['XCRGE_CODE'])."'>".decode_string($aREC_HT_XCRGE['XCRGE_NAME'])."</a></td>";
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

			<div class="modal" id="help_ht_tb_xcharge" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
									<form action ="?_a=tambah" method="post">
										<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_TBL?></label>
											<input type="text" class="col-sm-2 form-label-900" name='ADD_XCRGE_CODE' id="field-1" title="<?php echo $cTTIP_CODE?>"><br><br>
											<div class="clearfix"></div>

											<div rel="popover" data-animate=" animated fadeIn " data-container="body" data-color-class="success" data-toggle="popover" data-placement="top" data-content="<?php echo $cTTIP_NAME ?>" data-title="<?php echo $cNM_TBL?>" data-trigger="hover">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNM_TBL?></label>
												<input type="text" class="col-sm-5 form-label-900" name='ADD_XCRGE_NAME' id="field-2"><br><br>
											</div>	<div class="clearfix"></div>

											<div rel="popover" data-animate=" animated fadeIn " data-container="body" data-color-class="success" data-toggle="popover" data-placement="top" data-content="<?php echo S_MSG('H242','Keterangan Extra Charge')?>" data-title="<?php echo $cKETERANGAN?>" data-trigger="hover">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-5 form-label-900" name='ADD_XCRGE_DESC' id="field-2"><br><br>
											</div>	<div class="clearfix"></div>

											<div rel="popover" data-animate=" animated fadeIn " data-container="body" data-color-class="success" data-toggle="popover" data-placement="top" data-content="<?php echo S_MSG('H246','Nilai pengenaan tambahan biaya tambahan')?>" data-title="<?php echo $cLBL_RATE?>" data-trigger="hover">
												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cLBL_RATE?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_XCRGE_RATE' id="field-2" data-mask="fdecimal" value=0><br><br>
											</div>	<div class="clearfix"></div>

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

case md5('upd4t3'):
		$cQUERY ="select * from ht_xcrge";
		$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(XCRGE_CODE)='$_GET[_x]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$REC_HT_XCRGE=SYS_FETCH($qQUERY);
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
									  <h1 class="title"><?php echo $cEDIT_TBL?></h1>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=<?php echo md5('d3l3t3')?>&id=<?php echo md5($REC_HT_XCRGE['XCRGE_CODE'])?>" onClick="return confirm('<?php echo $cCONFIRM?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs"></div>
			<!-- 
								<header class="panel_header">
									  <h2 class="title pull-left">Basic Info</h2>
									  <div class="actions panel_actions pull-right">
											<i class="box_toggle fa fa-chevron-down"></i>
											<i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
											<i class="box_close fa fa-times"></i>
									  </div>
								</header>	-->
								<div class="content-body">
									<div class="row">
										<form action ="?_a=rubah&id=<?php echo $REC_HT_XCRGE['XCRGE_CODE']?>" method="post">
											<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_XCRGE_CODE' id="field-1" value=<?php echo decode_string($REC_HT_XCRGE['XCRGE_CODE'])?> disabled="disabled" title="<?php echo $cTTIP_CODE?>"><br><br>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNM_TBL?></label>
												<input type="text" class="col-sm-5 form-label-900" name='EDIT_XCRGE_NAME' id="field-2" value="<?php echo decode_string($REC_HT_XCRGE['XCRGE_NAME'])?>" title="<?php echo $cTTIP_NAME?>"><br><br>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_XCRGE_DESC' id="field-3" value="<?php echo decode_string($REC_HT_XCRGE['XCRGE_DESC'])?>"><br><br>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cLBL_RATE?></label>
												<input type="text" class="col-sm-3 form-label-900" name='UPD_XCRGE_RATE' id="field-4" data-mask="fdecimal" value=<?php echo $REC_HT_XCRGE['XCRGE_RATE']?>><br><br>

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
	$cXCRGE_CODE = encode_string($_POST['ADD_XCRGE_CODE']);
	if($cXCRGE_CODE=='') {
		$cMSG_BLANK		= S_MSG('H249','Kode Extra Charge tidak boleh kosong');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from ht_xcrge where APP_CODE='$cFILTER_CODE' and DELETOR='' and XCRGE_CODE='$cXCRGE_CODE'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG_EXIST		= S_MSG('H24Z','Kode Extra Charge sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
	} else {
		$cXCRGE_NAME = encode_string($_POST['ADD_XCRGE_NAME']);
		$cXCRGE_DESC = encode_string($_POST['ADD_XCRGE_DESC']);
		$nXCHARGE_RATE = str_replace(',', '', $_POST['UPD_XCRGE_RATE']);
		$cQUERY="insert into ht_xcrge set XCRGE_CODE='$cXCRGE_CODE', XCRGE_NAME='$cXCRGE_NAME', 
			XCRGE_DESC='$cXCRGE_DESC', XCRGE_RATE='$nXCHARGE_RATE', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
	}
	header('location:ht_tb_xcharge.php');
	break;
case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cXCRGE_NAME = encode_string($_POST['EDIT_XCRGE_NAME']);
	$cXCRGE_DESC = encode_string($_POST['EDIT_XCRGE_DESC']);
	$nXCHARGE_RATE = str_replace(',', '', $_POST['UPD_XCRGE_RATE']);
	$cSQL_COMMAND="update ht_xcrge set XCRGE_NAME='$cXCRGE_NAME', 
		XCRGE_DESC='$cXCRGE_DESC', XCRGE_RATE='$nXCHARGE_RATE', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and XCRGE_CODE='$KODE_CRUD'";
	SYS_QUERY($cSQL_COMMAND);
	header('location:ht_tb_xcharge.php');
	break;
case md5('d3l3t3'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update ht_xcrge set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(XCRGE_CODE)='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:ht_tb_xcharge.php');

	break;
}
?>

