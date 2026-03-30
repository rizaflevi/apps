<?php
//	tb_billman.php //

	include "sysfunction.php";
	$cCALL_FROM = '';
	if (isset($_GET['_call'])) {
		$cCALL_FROM=$_GET['_call'];
	}
	if ($cCALL_FROM=='fromdesktop') {
        $cAPP_CODE = $_GET['_app'];
        $cUSERCODE = $_GET['_u'];
	} else {
        if (!isset($_SESSION['data_FILTER_CODE'])) {
            session_start();
        }
        $cAPP_CODE = $_SESSION['data_FILTER_CODE'];
        $cUSERCODE = $_SESSION['gUSERCODE'];
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHELP_FILE = 'Doc/Tabel - Billman.pdf';
	
	$cHEADER 	= S_MSG('V010','Tabel Billman');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cBM_CODE   = S_MSG('F003','Kode');
	$cKODE		= S_MSG('PA02','Kode Peg');
	$cNAMA_BM   = S_MSG('V012','Nama Billman');
	$cUSER_ACMT = S_MSG('V013','User ACMT');
	$cUSER_KRN  = S_MSG('V013','User ACMT');
	$cEXIST		= S_MSG('V071','Kode Billman sudah ada');
	$cDAFTAR	= S_MSG('V072','Daftar Billman');
	$cADD_TBL	= S_MSG('V073','Tambah Billman');
	$cEDIT_TBL	= S_MSG('V074','Edit Kode Billman');

	$cTTIP_KODE	= S_MSG('V016','Kode pegawai / karyawan ( NPP )');
	$cTTIP_NAMA	= S_MSG('V075','Nama Billman sebagai keterangan');

	$cSAVE_DATA	= S_MSG('F301','Save');
	$cCLOSE_DATA= S_MSG('F302','Close');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
	$can_CREATE=1;

	$cHDR_BACK_CLR = S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD('Tabel Billman', 'open');
        $qQUERY=OpenTable('Billman', "A.APP_CODE='$cFILTER_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cBM_CODE, $cNAMA_BM, $cUSER_ACMT]);
							echo '<tbody>';
									while($aREC_DISP=SYS_FETCH($qQUERY)) {
									echo '<tr>';
										echo '<td class=""><div class="star"><i class="fa fa-star-half-empty icon-xs icon-default"></i></div></td>';
										echo "<td><span><a href='?_a=".md5('updRel')."&_id=$aREC_DISP[REC_ID]'>".$aREC_DISP['BM_CODE']."</a></span></td>";
										echo "<td><span><a href='?_a=".md5('updRel')."&_id=$aREC_DISP[REC_ID]'>".$aREC_DISP['PEOPLE_NAME']."</a></span></td>";
										echo "<td>".$aREC_DISP['USER_ACMT']."</a></span></td>";
										// echo '<td>'.$aREC_DISP['RLGN_NAME'].'</td>';
									echo '</tr>';
									}
							echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

case md5('cr34t3'):
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
									<h2 class="title"><?php echo $cADD_TBL?></h2>                            
								</div>
								<div class="pull-right hidden-xs">		
									<ol class="breadcrumb">
										<li>
											<a href="#help_add_tb_billman" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
										<form action ="?_a=<?php echo md5('addRel')?>" method="post">
											<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cBM_CODE?></label>
												<input type="text" class="col-sm-2 form-label-900" name='KODE_BM' id="field-1">

												<label class="col-sm-3 form-label-700" style="text-align:right;" for="field-1"><?php echo $cKODE?></label>
												<input type="text" class="col-sm-3 form-label-900" name="KODE_PEG" title="<?php echo $cTTIP_KODE?>">
												<div class="clearfix"></div><br>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNAMA_BM?></label>
												<input type="text" class="col-sm-6 form-label-900" name="NAMA_BM" title="<?php echo $cTTIP_NAMA?>" disabled="disabled">
												<div class="clearfix"></div><br>

												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
													<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
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

	case md5('updRel'):
		$qQUERY=OpenTable('Billman', "REC_ID='$_GET[_id]'");
		$REC_BM=SYS_FETCH($qQUERY);
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
										<?php echo '<a href="?_a='.md5('del_billman').'&_id='. $REC_BM['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
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
										<form action ="?_a=<?php echo md5('saveRel')?>&_id=<?php echo $REC_BM['REC_ID']?>" method="post">
											<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cBM_CODE?></label>
												<input type="text" class="col-sm-2 form-label-900" name='KODE_BM' id="field-1" value="<?php echo $REC_BM['BM_CODE']?>" disabled="disabled">
												<div class="clearfix"></div><br>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNAMA_BM?></label>
												<input type="text" class="col-sm-6 form-label-900" name='NAMA_BM' id="field-2" value="<?php echo $REC_BM['BM_NAME']?>" title="<?php echo $cTTIP_NAMA?>">
												<div class="clearfix"></div><br>

												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
													<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
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

case md5('addRel'):
	$cBM_CODE = encode_string($_POST['KODE_BM']);
	if($cBM_CODE=='') {
		$cNO_BLANK	= S_MSG('V079','Kode Billman tidak boleh kosong');
		echo "<script> alert('$cNO_BLANK');	window.history.back();	</script>";
		return;
	}
	$qQUERY=OpenTable('Billman', "BM_CODE='$cBM_CODE' and APP_CODE='$cFILTER_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qQUERY)==0){
		$cNAMA_BM = encode_string($_POST['NAMA_BM']);
		$nRec_id = date_create()->format('Uv');
		$cRec_id = (string)$nRec_id;
		RecCreate('Billman', ['BM_CODE', 'BM_NAME', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cBM_CODE, $cNAMA_BM, $_SESSION['gUSERCODE'], $cFILTER_CODE, $cRec_id]);
		header('location:tb_billman.php');
	} else {
		echo "<script> alert('$cEXIST');	window.history.back();	</script>";
		return;
	}
	break;

case md5('saveRel'):
	$KODE_CRUD=$_GET['_id'];
	RecUpdate('Billman', ['BM_NAME'], [$_POST['NAMA_BM']], "REC_ID='$KODE_CRUD'");
	header('location:tb_billman.php');
	break;

case md5('del_billman'):
	RecSoftDel($_GET['_id']);
	header('location:tb_billman.php');
}
?>

