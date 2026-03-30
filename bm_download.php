<?php
// bm_rekap.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$d_BM_START 	= S_PARA('BM_START', date("Y-01-01"));
//	var_dump($d_BM_START); exit();

	$cHEADER		= S_MSG('RQ61','Download Data');
	$cKODE_RBM		= S_MSG('RQ15','Kode RBM');
	$cPROSES		= S_MSG('F308','Proses');
	$cBATAL			= S_MSG('F306','Batal');

	$dPERIOD1=date("Y-m-d");
	$cSQLCOMMAND= "SELECT IDPEL, PETUGAS, NOTE	from bm_dt_baca
		where left(TGL_BACA,10)='$dPERIOD1' and PETUGAS='$_SESSION[gCAT_TER]' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
//		var_dump($cSQLCOMMAND);	exit();
	$qQUERY=SYS_QUERY($cSQLCOMMAND);
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<div class="page-container row-fluid">
			<section class="wrapper main-wrapper" style='margin:0px'>
				<div class="clearfix"></div>
				<div class="col-xs-12">
					<header class="panel_header">
						<h2 class="title pull-left"><?php echo $cHEADER?></h2>
					</header>
					<div class="content-body">
						<div class="row">
<!-- ****************************************** start content ********************************************* -->
							<form action ="bm_dl_proses.php" method="post">

								<div class="col-xs-12">
									<label class="col-xs-6 form-label-700" style="font-size:150%;" for="field-1"><?php echo $cKODE_RBM?></label>
									<select name="ADD_KODE_RBM" class="col-xs-6 form-label-900" style="font-size:200%;">
									<?php 
										$REC_DATA=SYS_QUERY("select KODE_RUTE, NAMA_RUTE from bm_tb_rute where APP_CODE='$cFILTER_CODE' and DELETOR=''");
										while($aREC_DETAIL=SYS_FETCH($REC_DATA)){
											echo "<option value='$aREC_DETAIL[KODE_RUTE]'  >$aREC_DETAIL[KODE_RUTE]</option>";
										}
									?>
									</select>
									<div class="clearfix"></div><br><br>

									<div class="text-left">
										<input type="submit" class="col-sm-4 btn btn-info btn-lg" value=<?php echo $cPROSES?> onclick="create_db()">
										<input type="button" class="btn" value=<?php echo $cBATAL?> onclick=self.history.back()>
									</div>	<div class="clearfix"></div><br>
								</div>
<!-- ****************************************** start content ********************************************* -->
							</form>
						</div>
					</div>
				</div>
			</section>
		</div>
		<?php	require_once("js_framework.php");	?>
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

<script>
function create_db() {
    try{
     if(window.openDatabase){
             var shortName   =  'db_edentiti';
             var version   =  '1.0';
             var displayName  =  'Edentiti Information';
             var maxSize   =  65536; // in bytes
             db    =  openDatabase(shortName, version, displayName, maxSize);
                    alert('Sqlite Database created');
         }
    }catch(e){
     alert(e);
    }
 }
</script>
