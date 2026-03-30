<?php
// bm_import_pel.php
// import data pelanggan dari csv file

//	include "preloader.php";
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cHEADER		= S_MSG('MN43','CSV File Upload : ');
	$cKETERANGAN 	= S_MSG('PN10','Keterangan');
	$cKET_1 		= S_MSG('PQ81','Format file yang di upload adalah berupa file CSV saja.');
	$cKET_2 		= S_MSG('PQ82','Susunan kolom file CSV nya harus seperti berikut :');
	$cKET_3 		= S_MSG('PQ83','Kolom 1 : IDPEL untuk Id Pelanggan');
	$cKET_4 		= S_MSG('PQ84','Kolom 2 : NAMA  untuk Nama Pelanggan');
	$cKET_5 		= S_MSG('PQ85','Kolom 3 : ALAMAT untuk Alamat Pelanggan');
	$cKET_6 		= S_MSG('PQ86','Kolom 4 : UNITUPI untuk Kode unit daerah layanan');
	$cKET_7 		= S_MSG('PQ87','Kolom 5 : UNITAP');
	$cKET_8 		= S_MSG('PQ88','Kolom 6 : UNITUP');
	$cKET_9 		= S_MSG('PQ89','Kolom 7 : TARIF untuk kode tarif');
	$cKET_10		= S_MSG('PQ8A','Kolom 8 : DAYA');
	$cKET_11		= S_MSG('PQ8B','Kolom 9 : KOGOL');
	$cKET_12		= S_MSG('PQ8C','Kolom 10: FAKMKWH');
	$cKET_13		= S_MSG('PQ8D','Kolom 11: RPBP');
	$cKET_14		= S_MSG('PQ8E','Kolom 12: RPUJL');
	$cKET_15		= S_MSG('PQ8F','Kolom 13: PEMDA');
	$cKET_16		= S_MSG('PQ8G','Kolom 14: NOMORKWH');
	$cKET_17		= S_MSG('PQ8H','Kolom 15: STATUSPLG');
	$cKET_18		= S_MSG('PQ8I','Kolom 16: MERK METER');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}


switch($cACTION){
	default:
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

								<div class="pull-left">	</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs"></div>

								<div class="content-body">
									<div class="row">
	<!-- ****************************************** start content ********************************************* -->

										<div class="tab-pane fade in" id="File_CSV">
											<form action ="?_a=up_load" enctype="multipart/form-data" method="post" role="form">
												<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h4 class="modal-title"><?php echo $cHEADER?></h4>
													</div>
													<div class="form-group">
														<input type="file" name="file" id="file" size="150">
														<p class="help-block">Only CSV File Import.</p>
													</div>
													<div class="text-left">
														<button type="submit" class="btn btn-default" name="Import" value="Import">Upload</button>
													</div><br><br>
<!--
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div id='jumlah_data'></div>
											<div class="progress">
											
												<div id="IMPORT_PROGRESS" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
													<span id="myBar" class="sr-only">0% Complete</span>
												</div>
											</div>                
										</div>                

	<!-- ****************************************** start content ********************************************* -->
													<label class="col-sm-12 form-label-900" for="field-1"><i><?php echo $cKETERANGAN?></i></label><br>
													<label class="col-sm-12"><?php echo $cKET_1?></label>
													<label class="col-sm-12"><?php echo $cKET_2?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_3?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_4?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_5?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_6?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_7?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_8?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_9?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_10?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_11?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_12?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_13?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_14?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_15?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_16?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_17?></label>
													<label class="col-sm-12 form-label-700"><?php echo $cKET_18?></label>
												</div>
											</form>
										</div>
										<div class="clearfix"></div>
										<br><br><br>

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
			<script type='text/javascript'>
				function move() {
				  var elem = document.getElementById("IMPORT_PROGRESS");   
				  var width = 10;
				  var id = setInterval(frame, 10);
				  function frame() {
					if (width >= 100) {
					  clearInterval(id);
					} else {
					  width++; 
					  elem.style.width = width + '%'; 
					  document.getElementById("myBar").innerHTML = width * 1  + '%';
					}
				  }
				}
			</script>
		</body>
	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case 'FINISHED' :
	$cHEADER=S_MSG('MS61','Import data pelanggan sudah selesai');
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

								<h1 class="title"><?php echo $cHEADER?></h1>                            
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs"></div>

								<div class="content-body">
									<div class="row">
<!-- ****************************************** start content ********************************************* -->


<!-- ****************************************** start content ********************************************* -->

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
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

<?php
	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'import pelanggan sukses');
	SYS_DB_CLOSE($DB2);	break;

case 'up_load' :

	if(isset($_POST["Import"]))
	{
		$filename=$_FILES["file"]["tmp_name"];
		if($_FILES["file"]["size"] > 0)
		{
			$file 	= fopen($filename, "r");
			$count 	= 0;
			$n_PLG	= 0;
//			$rows = explode("\n", $file);
//			$nMAX	= count($rows);
//			$nMAX	= 100;
			$xPROGRESS = 2;
			$cQ_TB_PEL = "INSERT into bm_tb_pel ( `IDPEL`, `NAMA_PEL`, `ALAMAT`, `UNITUP`, `KODE_TARIF`, `DAYA`, `NOMOR_KWH`, `MERK_KWH` , `APP_CODE` ) values ";
			$cQ_TB_PLG = "INSERT into bm_tb_plg ( `IDPEL`, `APP_CODE`, `ENTRY`, `DATE_ENTRY` ) values ";
			while (($emapData = fgetcsv($file, 5000000, ",")) !== FALSE)
			{
				$count++;
//				print_r($emapData[0]);
//				print_r($count);
//				exit();

				$cID_PEL = $emapData[0];
				if($count==1){
					if($cID_PEL!='IDPEL') {
						APP_LOG_ADD($cHEADER, 'import pelanggan Format file tidak benar');
						$cMSG_FORMAT = S_MSG('MN44','Format file tidak benar !');
						echo "<script> alert('$cMSG_FORMAT');	window.history.back();	</script>";
						return;
					}
				}
				$cNM_PEL = encode_string($emapData[1]);
				$cAL_PEL = encode_string($emapData[2]);
				$cUNITUP = encode_string($emapData[5]);
				$cKD_TRF = encode_string($emapData[6]);
				$cDAYA   = encode_string($emapData[7]);
				$cNMR_KW = encode_string($emapData[13]);
				$cMRK_KW = encode_string($emapData[15]);
				$cQ_TB_PEL .= "('". $cID_PEL."', '". $cNM_PEL . "', '" . $cAL_PEL . "', '" . $cUNITUP . "', '" . $cKD_TRF . "', " . $cDAYA . ", '" . $cNMR_KW . "', '" . $cMRK_KW . "', '" . $cFILTER_CODE . "'), ";
/*
					$cQUERY="select * from bm_tb_plg where IDPEL='$cID_PEL' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
					$qQUERY=SYS_QUERY($cQUERY);
					if(SYS_ROWS($qQUERY)==0) {
						$n_PLG = 1;
						$NOW = date("Y-m-d H:i:s");
						$cQ_TB_PLG .= "('". $cID_PEL."', '". $cFILTER_CODE  . "', '" . $_SESSION['gUSERCODE']. "', '". $NOW. "'), ";
					}


				if($nMAX>0) {
					$nPROGRESS = intval($count/$nMAX*100);
					if ($nPROGRESS!=$xPROGRESS) {
						$cRUN_STATUS = S_REPL('IMPORT_RUN_PROGRESS', $nPROGRESS);
						print_r($nPROGRESS);	echo'<br>';
						exit();
						$xPROGRESS=$nPROGRESS;
					}
				}
*/
			}
			if($count>1) {
				$cQ_DEL = "delete from bm_tb_pel";
				$qQ_DEL = SYS_QUERY($cQ_DEL);
			}
			$cQ_TB_PEL .= "; ";
			$cQ_TB_PEL = str_replace( ", ;" , ";", $cQ_TB_PEL );
			$qQ_TB_PEL = SYS_QUERY($cQ_TB_PEL);
/*
			if($n_PLG==1) {
				$cQ_TB_PLG .= "; ";
				$cQ_TB_PLG = str_replace( ", ;" , ";", $cQ_TB_PLG );
				$qQ_TB_PLG = SYS_QUERY($cQ_TB_PLG);
			}
*/
			fclose($file);
		}
		else
			echo 'Invalid File:Please Upload CSV File';
			APP_LOG_ADD($cHEADER, 'import pelanggan Invalid File:Please Upload CSV File');
//			$cRUN_STATUS = S_REPL('IMPORT_RUN_PROGRESS', '0');
		SYS_DB_CLOSE($DB2);
	}
	header('Location: bm_import_pel.php?_a=FINISHED');
}
?>

<script type='text/javascript'>
	setInterval(ajax, 1000);
	function ajax()
	{
		$.ajax({
		  type: "POST",
		  url: 'proses.php',
		  success: function(data){
			  $('#IMPORT_PROGRESS').attr('style','width: '+data+'%');
			  $('#jumlah_data').text(data+' Progress');
		  }
		});
	}
	function disp_progr(_nPROGRESS) {
	  var elem = document.getElementById("IMPORT_PROGRESS");   
	  var width = _nPROGRESS;
	  var id = setInterval(pframe, 10);
	  function pframe() {
		  elem.style.width = width + '%'; 
		  document.getElementById("myBar").innerHTML = width * 1  + '% Complete';
	  }
	}
</script>
