<?php
//	tb_salutation.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];

	$qQUERY=OpenTable('HtSaluttion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

	$cHEADER 	= S_MSG('H255','Tabel Salutation');
	$cKODE_TBL 	= S_MSG('H256','Kode Salutation :');
	$cNAMA_TBL 	= S_MSG('H257','Nama Salutation :');
	$cADD_REC	= S_MSG('H266','Tambah Salutation');
	$cDAFTAR	= S_MSG('H265','Daftar Salutation');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cEDIT_TBL	= S_MSG('H267','Edit Tabel Salutation');
	$cMSG_EXIST	= S_MSG('H268','Kode Salutation sudah ada');
	$cMSG_BLANK	= S_MSG('H269','Kode Salutation belum diisi');
	
switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'SALUTATION_1ADD');
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'View');

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
												<a href="?_a=create"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_tb_salutation" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_HT_SALUT=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td style="width: 1px;"></td>';
															echo "<td><span><a href='?_a=update&KODE_TBL=$aREC_HT_SALUT[SALUT_CODE]'>".$aREC_HT_SALUT['SALUT_CODE']."</a></span></td>";
															echo "<td><span><a href='?_a=update&KODE_TBL=$aREC_HT_SALUT[SALUT_CODE]'>".$aREC_HT_SALUT['SALUT_DESC']."</a></span></td>";
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
		</body>
	</html>
<?php
	break;

case "create":
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
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">

								<div class="pull-left">
									<h1 class="title"><?php echo $cADD_REC?></h1>                            
								</div>
								<div class="pull-right hidden-xs">		</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="content-body">
									<div class="row">
										<form action ="?_a=tambah" method="post">
											<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
												<div class="form-group">
													<label class="form-label" for="field-1"><?php echo $cKODE_TBL?></label>
													<span class="desc"></span>
													<div class="controls">
														<input type="text" class="form-control" name='ADD_SALUT_CODE' id="field-1">
													</div>
												</div>

												<div class="form-group">
													<label class="form-label" for="field-1"><?php echo $cNAMA_TBL?></label>
													<span class="desc"></span>
													<div class="controls">
														<input type="text" class="form-control" name='ADD_SALUT_DESC' id="field-2">
													</div>
												</div>

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
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case "update":
		$cQUERY ="select * from ht_salut";
		$cQUERY.=" where ht_salut.APP_CODE='$cAPP_CODE' and SALUT_CODE='$_GET[KODE_TBL]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$REC_HT_SALUT=SYS_FETCH($qQUERY);
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
												<?php echo '<a href="?_a=delete&id='. $REC_HT_SALUT['SALUT_CODE']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
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
											<form action ="?_a=rubah&id=<?php echo $REC_HT_SALUT['SALUT_CODE']?>" method="post">
												<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
													<div class="form-group">
														<label class="form-label" for="field-1"><?php echo $cKODE_TBL?></label>
														<div class="controls">
															<input type="text" class="form-label-900" name='EDIT_SALUT_CODE' id="field-1" value=<?php echo $REC_HT_SALUT['SALUT_CODE']?> disabled="disabled">
														</div>
													</div>

													<div class="form-group">
														<label class="form-label" for="field-1"><?php echo $cNAMA_TBL?></label>
														<div class="controls">
															<input type="text" class="form-label-900" name='EDIT_SALUT_DESC' id="field-2" value="<?php echo $REC_HT_SALUT['SALUT_DESC']?>">
														</div>
													</div>


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
	if($_POST['ADD_SALUT_CODE']==''){
		$cMSG = $cMSG_BLANK;
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from ht_salut where APP_CODE='$cAPP_CODE' and DELETOR='' and SALUT_CODE='$_POST[ADD_SALUT_CODE]'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG = $cMSG_EXIST;
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:tb_salutation.php');
	} else {
		$cQUERY="insert into ht_salut set SALUT_CODE='$_POST[ADD_SALUT_CODE]', SALUT_DESC='$_POST[ADD_SALUT_DESC]', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:tb_salutation.php');
	}
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update ht_salut set SALUT_DESC='$_POST[EDIT_SALUT_DESC]', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cAPP_CODE' and SALUT_CODE='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:tb_salutation.php');
	break;

case 'delete':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update ht_salut set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cAPP_CODE' and SALUT_CODE='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:tb_salutation.php');
}
?>

