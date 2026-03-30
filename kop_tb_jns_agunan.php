<?php
//	kop_tb_jns_agunan.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('KA51','Tabel Jenis Agunan');
	$cHEAD1 = S_MSG('KA60','Edit Jenis Agunan');
	$cHEAD2 = S_MSG('KA61','Tambah Jenis Agunan');
	$cLABEL1 = S_MSG('KA52','Kode Agunan');
	$cLABEL2 = S_MSG('KA53','Jenis Agunan');
	$cLABEL3 = S_MSG('H218','Keterangan');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cSAVE_DATA=S_MSG('F301','Save');

	$qQUERY=SYS_QUERY("select * from tb_aggn1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
switch($cACTION){
	default:

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
												<a href="#help_kop_tb_jns_agunan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
	<!--
									  <div class="actions panel_actions pull-right">
											<i class="box_toggle fa fa-chevron-down"></i>
											<i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
											<i class="box_close fa fa-times"></i>
									  </div>
	-->
								</header>
								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="display table table-hover table-condensed" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th style="background-color:LightGray;width: 1px;"></th>
														<th style="background-color:LightGray;"><?php echo $cLABEL1?></th>
														<th style="background-color:LightGray;"><?php echo $cLABEL2?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_TB_AGGN1=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td style="width: 1px;"></td>';
															echo "<td><span><a href='?_a=".md5('up_date')."&_j=$aREC_TB_AGGN1[KODE_AGGN]'>".$aREC_TB_AGGN1['KODE_AGGN']."</a></span></td>";
															echo "<td><a href='?_a=".md5('up_date')."&_j=$aREC_TB_AGGN1[KODE_AGGN]'>".$aREC_TB_AGGN1['NAMA_AGN']."</a></td>";
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
				<!-- END CONTENT -->
				<?php	  include "scr_chat.php";	?>
			</div>
			<?php		require_once("js_framework.php");		?>
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
			<script src="assets/js/scripts.js" type="text/javascript"></script> 

			<div class="modal" id="help_kop_tb_jns_agunan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo S_MSG('KA50','Daftar Jenis Agunan')?></h4>
						</div>
						<div class="modal-body">


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

						<div class='col-lg-9 col-md-9 col-sm-9 col-xs-9'>
							<div class="page-title">

								<div class="pull-left">
									<h2 class="title"><?php echo $cHEAD2?></h2>                            
								</div>
								<div class="pull-right hidden-xs"></div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
								<section class="box ">
									<div class="content-body">
										<div class="row">
											<form action ="?_a=tambah" method="post">
												<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
													<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cLABEL1?></label>
													<input type="text" class="col-sm-2 form-label-900" name="ADD_KODE_AGGN">
													<div class="clearfix"></div>

													<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cLABEL2?></label>
													<input type="text" class="col-sm-8 form-label-900" name='ADD_NAMA_AGGN'>
													<div class="clearfix"></div><br>

													<div class="text-left">
														<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
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
				<?php	  include "scr_chat.php";			?>
			</div>
			<?php		require_once("js_framework.php");		?>
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
		$cQUERY ="select * from tb_aggn1";
		$cQUERY.=" where APP_CODE='$cFILTER_CODE' and KODE_AGGN='$_GET[_j]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$REC_TB_AGGN1=SYS_FETCH($qQUERY);
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
									  <h1 class="title"><?php echo S_MSG('KA60','Edit Jenis Agunan')?></h1>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<?php echo '<a href="?_a='.md5('del_aggn').'&id='. md5($REC_TB_AGGN1['KODE_AGGN']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs">	</div>
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
										<form action ="kop_tb_jns_agunan_crud.php?_a=update&id=<?php echo $REC_TB_AGGN1['KODE_AGGN']?>" method="post"  onSubmit="return CEK_tb_aggn1(this)">
											<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
												<div class="form-group">
													<label class="form-label" for="field-1"><?php echo $cLABEL1?></label>
													<div class="controls">
														<input type="text" class="form-label-900" name='EDIT_KODE_AGN' id="field-1" value=<?php echo $REC_TB_AGGN1['KODE_AGGN']?> disabled="disabled">
													</div>
												</div>

												<div class="form-group">
													<label class="form-label" for="field-1"><?php echo $cLABEL2?></label>
													<div class="controls">
														<input type="text" class="form-label-900" name='EDIT_NAMA_AGN' id="field-2" value="<?php echo $REC_TB_AGGN1['NAMA_AGN']?>">
													</div>
												</div>

												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
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
				<?php	  include "scr_chat.php";			?>
			</div>
			<?php		require_once("js_framework.php");		?>
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
	$cKODE_AGGN	= encode_string($_POST['ADD_KODE_AGGN']);	
	if($cKODE_AGGN==''){
		$cMSG_BLANK	= S_MSG('KA66','Kode Jenis Agunan belum diisi');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from tb_aggn1 where APP_CODE='$cFILTER_CODE' and DELETOR='' and KODE_AGGN='$cKODE_AGGN'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		header('location:kop_tb_jns_agunan.php');
	} else {
		$cNAMA_AGGN	= encode_string($_POST['ADD_NAMA_AGGN']);	
		$cQUERY="insert into tb_aggn1 set KODE_AGGN='$cKODE_AGGN', NAMA_AGN='$cNAMA_AGGN', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:kop_tb_jns_agunan.php');
	}

case md5('del_aggn'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update tb_aggn1 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(KODE_AGGN)='$KODE_CRUD'";
//	echo $cQUERY;
//	exit();
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:kop_tb_jns_agunan.php');
}
?>

