<?php
// bm_import_pel.php
// import data pelanggan dari csv file

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cTITLE=S_MSG('MN43','CSV File Upload : ');
	$cFILE_LOGO_COMP = 'data/images/'. 'LOGO1_'.$cFILTER_CODE.'.jpg';
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
														<h4 class="modal-title"><?php echo $cTITLE?></h4>
													</div>
													<div class="form-group">
														<input type="file" name="file" id="file" size="150">
														<p class="help-block">Only Excel/CSV File Import.</p>
													</div>
													<div class="text-left">
														<button type="submit" class="btn btn-default" name="Import" value="Import" onclick="move()">Upload</button>
													</div>
												</div>
											</form>
										</div>
										<div class="clearfix"></div>
										<br><br><br>

										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="progress">
												<div id="IMPORT_PROGRESS" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
													<span id="myBar" class="sr-only">0% Complete</span>
												</div>
											</div>                
										</div>                

	<!-- ****************************************** start content ********************************************* -->

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
	$cTITLE=S_MSG('MS61','Import data pelanggan sudah selesai');
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

								<h1 class="title"><?php echo $cTITLE?></h1>                            
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
								</div>
							</section>
						</div>

					</section>
				</section>
				<!-- END CONTENT -->
				<?php	include "scr_chat.php";	?>
			</div>
			<?php	require_once("js_framework.php");	?>
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
			<script src="assets/js/chart-sparkline.js" type="text/javascript"></script>
			<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case 'up_load' :

    if(isset($_POST["Import"]))
	{
		$filename=$_FILES["file"]["tmp_name"];
		if($_FILES["file"]["size"] > 0)
		{
			$cQ_DEL = "delete from bm_tb_pel";
			$qQ_DEL = SYS_QUERY($cQ_DEL);
			$file 	= fopen($filename, "r");
			$count 	= 0;
			$n_PLG	= 0;
			$nMAX	= count($file);
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
						$cMSG_FORMAT = S_MSG('MN44','Format file tidak benar !');
						echo "<script> alert('$cMSG_FORMAT');	window.history.back();	</script>";
						return;
					}
				} else {
					$cQ_TB_PEL .= "('". $cID_PEL."', '". encode_string($emapData[1]) . "', '" . encode_string($emapData[2]) . "', '" . $emapData[3] . "', '" . $emapData[4] . "', " . $emapData[5] . ", '" . $emapData[6] . "', '" . $emapData[7] . "', '" . $cFILTER_CODE . "'), ";

					$cQUERY="select * from bm_tb_plg where IDPEL='$cID_PEL' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
					$qQUERY=SYS_QUERY($cQUERY);
					if(SYS_ROWS($qQUERY)==0) {
						$n_PLG = 1;
						$NOW = date("Y-m-d H:i:s");
						$cQ_TB_PLG .= "('". $cID_PEL."', '". $cFILTER_CODE  . "', '" . $_SESSION['gUSERCODE']. "', '". $NOW. "'), ";
					}

				}
			}
			$cQ_TB_PEL .= "; ";
			$cQ_TB_PEL = str_replace( ", ;" , ";", $cQ_TB_PEL );
			$qQ_TB_PEL = SYS_QUERY($cQ_TB_PEL);

			if($n_PLG==1) {
				$cQ_TB_PLG .= "; ";
				$cQ_TB_PLG = str_replace( ", ;" , ";", $cQ_TB_PLG );
				$qQ_TB_PLG = SYS_QUERY($cQ_TB_PLG);
			}

			fclose($file);
			echo 'CSV File has been successfully Imported';
		}
		else
			echo 'Invalid File:Please Upload CSV File';
		SYS_DB_CLOSE($DB2);
	}
	header('Location: bm_import_pel.php?_a=FINISHED');
}
?>

