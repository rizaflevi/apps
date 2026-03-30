<?php
//	ht_tb_tariff_plan.php
// Tariff planp, eg. daily, weekly, monthly

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER 		= S_MSG('H042','Tabel Tarif');
	$cKD_TBL  		= S_MSG('H035','Kode Tarif');
	$cNM_TBL  		= S_MSG('H036','Nama Tarif');
	$cDEFAULT 		= S_MSG('HT15','Default');
	$cDAYS	 		= S_MSG('H046','Jml. Hari      :');
	$cDAFTAR		= S_MSG('H058','Daftar Jenis Tarif');
	$cEDIT_TBL		= S_MSG('H065','Edit Tabel Tarif');
	$cADD_TBL		= S_MSG('H066','Tambah Tabel Tarif');
	$cMSG_EXIST		= S_MSG('H068','Kode Tarif sudah ada');

	$cQUERY="select * from ht_tarif where APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

	$cSAVE	= S_MSG('F301','Save');
	$cCLOSE	= S_MSG('F302','Close');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$cHELP_BOX	= S_MSG('HH01','Help Tabel Tarif (plan)');
		$cHELP_1		= S_MSG('HH02','Ini adalah modul untuk memasukkan data Tariff Plan');
		$cHELP_2		= S_MSG('HH03','Seperti contoh nya Tarif daily, weekly atau monthly');
		$cHELP_3		= S_MSG('HH04','Untuk memasukkan data Grup Tarif plan, klik tambah Tarif / add new');
		$cHELP_4		= S_MSG('HH05','Tarif plan adalah tarif kamar per hari yang di tentukan berdasarkan rencana inap');
		$cHELP_5		= S_MSG('HH06','Ini diperlukan apabila ada perbedaan tarif untuk tamu yang berencana untuk inap sehari, se minggu, atau se bulan ');

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
												<a href="?a=<?php echo md5('cr34t3')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_ht_tb_tariff_plan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														while($aREC_HT_TARIF=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															echo '<td class=""><div class="star"><i class="fa fa-newspaper-o icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?_a=".md5('update_tarif')."&KODE_TARIF=$aREC_HT_TARIF[TRF_CODE]'>".$aREC_HT_TARIF['TRF_CODE']."</a></span></td>";
															echo "<td><a href='?_a=".md5('update_tarif')."&KODE_TARIF=$aREC_HT_TARIF[TRF_CODE]'>".$aREC_HT_TARIF['TRF_DESC']."</a></td>";
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
			<div class="modal" id="help_ht_tb_tariff_plan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">

							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_4?></p>	<p><?php echo $cHELP_2?></p>
							<p><?php echo $cHELP_5?></p>	<p><?php echo $cHELP_3?></p>

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
		$cHELP_BOX	= S_MSG('HH0A','Help Tambah Tabel Tarif (plan)');
		$cHELP_1		= S_MSG('HH0B','Ini adalah modul untuk memasukkan data Tariff Plan yang baru');
		$cHELP_2		= S_MSG('HH0C','Data yang di masukkan adalah sebagai berikut :');
		$cHELP_3		= S_MSG('HH0D','Tariff code : untuk memasukkan kode tarif plan yang baru, yang belum pernah ada sebelumnya');
		$cHELP_4		= S_MSG('HH0E','Tariff desc : nama / penjelasan dari masing-masing tarif plan');
		$cHELP_5		= S_MSG('HH0F','Default     : conteng jika merupakan tarif dasar yang berlaku');
		$cHELP_6		= S_MSG('HH0F','Day(s)      : rencana hari inap, dalam satuan hari');

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

					<div class='col-lg-8 col-md-8 col-sm-8 col-xs-8'>
						<div class="page-title">

							<div class="pull-left">
								<h2 class="title"><?php echo $cADD_TBL?></h2>                            
							</div>
							<div class="pull-right hidden-xs">
								<ol class="breadcrumb">
									<li>
										<a href="#help_add_ht_tb_tariff_plan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
									</li>
								</ol>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-8 col-md-8 col-xs-8 col-sm-8">
						<section class="box ">
							<div class="content-body">
								<div class="row">
									<form action ="?a=tambah" method="post">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_TBL?></label>
											<input type="text" class="col-sm-2 form-label-900" name='ADD_TRF_CODE' id="field-1">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNM_TBL?></label>
											<input type="text" class="col-sm-8 form-label-700" name='ADD_TRF_DESC' id="field-2">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cDEFAULT?></label>
											<div class="form-block"><input name='ADD_TRF_DFLT' type="checkbox" class="iswitch iswitch-md iswitch-secondary"></div>
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cDAYS?></label>
											<input type="text" class="col-sm-2 form-label-900" name='ADD_TRF_DAYS' id="field-2" data-mask="fdecimal" value=0><br><br>

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

        <div class="modal" id="help_add_ht_tb_tariff_plan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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

case md5('update_tarif'):
		$cQUERY ="select * from ht_tarif";
		$cQUERY.=" where APP_CODE='$cFILTER_CODE' and TRF_CODE='$_GET[KODE_TARIF]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$REC_HT_TARIF=SYS_FETCH($qQUERY);
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
									  <h2 class="title"><?php echo $cEDIT_TBL?></h2>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<a href="?a=delete&id=<?php echo $REC_HT_TARIF['TRF_CODE']?>" onClick="return confirm('Apakah Anda benar-benar mau menghapusnya?')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
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
										<form action ="?a=rubah&id=<?php echo $REC_HT_TARIF['TRF_CODE']?>" method="post">
											<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKD_TBL?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_TRF_CODE' id="field-1" value=<?php echo $REC_HT_TARIF['TRF_CODE']?> disabled="disabled"><br><br>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNM_TBL?></label>
												<input type="text" class="col-sm-5 form-label-900" name='EDIT_TRF_DESC' id="field-2" value="<?php echo $REC_HT_TARIF['TRF_DESC']?>"><br><br>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cDEFAULT?></label>
												<div class="form-block"><input type="checkbox" name="EDIT_TRF_DFLT" <?php if($REC_HT_TARIF['TRF_DFLT']==1) { echo 'checked'; }?>  class="iswitch iswitch-md iswitch-secondary"></div><br>

												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cDAYS?></label>
												<input type="text" class="col-sm-2 form-label-900" name='UPD_TRF_DAYS' id="field-2" data-mask="fdecimal" value=<?php echo $REC_HT_TARIF['TRF_DAYS']?>><br><br>

												<div class="clearfix"></div>
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
case 'tambah':
	$NOW = date("Y-m-d H:i:s");
	if($_POST['ADD_TRF_CODE']=='') {
		$cMSG_BLANK		= S_MSG('H067','Kode Tarif tidak boleh kosong');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from ht_tarif where APP_CODE='$cFILTER_CODE' and DELETOR='' and TRF_CODE='$_POST[ADD_TRF_CODE]'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG = $cMSG_EXIST;
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	} else {
		$lCHECK = 0;
		if (isset($_POST['ADD_TRF_DFLT'])){
			$lCHECK = 1;
		}
		$cQUERY="insert into ht_tarif set TRF_CODE='$_POST[ADD_TRF_CODE]', TRF_DESC='$_POST[ADD_TRF_DESC]', TRF_DFLT='$lCHECK', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
	}
	header('location:ht_tb_tariff_plan.php');
	break;
case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$lCHECK = 0;
	$nDAYS = str_replace(',', '', $_POST['UPD_TRF_DAYS']);
	if (isset($_POST['EDIT_TRF_DFLT'])){
		$lCHECK = 1;
	}
	$cSQL_COMMAND="update ht_tarif set TRF_DESC='$_POST[EDIT_TRF_DESC]', TRF_DFLT=$lCHECK, TRF_DAYS='$nDAYS',
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and TRF_CODE='$KODE_CRUD'";
	SYS_QUERY($cSQL_COMMAND);
	header('location:ht_tb_tariff_plan.php');
	break;
case 'delete':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update ht_tarif set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and TRF_CODE='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:ht_tb_tariff_plan.php');
	break;
}
?>

