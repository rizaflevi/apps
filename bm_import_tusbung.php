<?php
// bm_import_tusbung.php
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
	$cKET_3 		= S_MSG('PQ91','Kolom 1 : No. untuk Nomor Urut');
	$cKET_4 		= S_MSG('PQ92','Kolom 2 : IDPEL untuk Kode pelanggan');
	$cKET_5			= S_MSG('PQ93','Kolom 3 : NO RBM untuk kode RBM');
	$cKET_6 		= S_MSG('PQ94','Kolom 4 : NAMA PELANGGAN untuk Nama Pelanggan');
	$cKET_7 		= S_MSG('PQ95','Kolom 5 : ALAMAT untuk Alamat Pelanggan');
	$cKET_8 		= S_MSG('PQ96','Kolom 6 : DAYA');
	$cKET_9			= S_MSG('PQ97','Kolom 7 : LBR untuk jumlah lembar tagihan');
	$cKET_10		= S_MSG('PQ98','Kolom 8 : RPTAG untuk jumlah tagihan');
	$cKET_11		= S_MSG('PQ99','Kolom 9 : RPBK');
	
	$nTHN			= date('Y');
	$nBLN			= date('m');

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
										<div class="tab-pane fade in" id="File_CSV">
											<form action ="?_a=up_load" enctype="multipart/form-data" method="post" role="form">
												<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
													<label class="col-sm-2 form-label-700">Periode Tagihan</label>

													<?php
														$cTAHUN_AWAL 	= S_PARA('START_TNT',date('Y-m-d'));
														$dSTART    		= (new DateTime($cTAHUN_AWAL));
														$cSTART    		=  $dSTART->format('Y-m');
														$dFINISH   		= (new DateTime(date('Y-m-d')));
														$cFINISH   		= $dFINISH->format('Y-m');
														$INTERVAL 		= DateInterval::createFromDateString('1 month');
														$PERIOD_X  		= new DatePeriod($dSTART, $INTERVAL, $dFINISH);
														$cTAHUN_BLN		= substr($_SESSION['sCURRENT_PERIOD'],0,7);	// yyyy-mm


														echo '<select name="SYS_THN" class="form-label-900 col-sm-2">';
															foreach ($PERIOD_X as $dt) {
																$xYM = $dt->format("Y-m");
																if ($xYM==$cFINISH)
																	echo "<option value=$xYM selected>$xYM</option>";
																else
																	echo "<option value=$xYM>$xYM</option>";
															}
														echo "</select> ";
													?>

													<div class="clearfix"></div>
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
			<script type='text/javascript'></script>
		</body>
	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case 'FINISHED' :
	$cHEADER=S_MSG('PQ9A','Import data tagihan pelanggan sudah selesai');
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
	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'import tagihan pelanggan sukses');
	SYS_DB_CLOSE($DB2);	break;

case 'up_load' :
	$cTHN	= substr($_POST['SYS_THN'], 0, 4);
	$cBLN	= substr($_POST['SYS_THN'], 5, 2);
	$nTHN	= (int)$cTHN;
	$nBLN	= (int)$cBLN;
//	var_dump($_POST); exit;
	if(isset($_POST["Import"]))
	{
		$filename=$_FILES["file"]["tmp_name"];
		if($_FILES["file"]["size"] > 0)
		{
			$file 	= fopen($filename, "r");
			$count = 0;
			$n_PLG	= 0;
//			$cQ_TB_PLG = "INSERT into bm_plg_pasca ( `IDPEL`, `LEMBAR`, `TAGIHAN`, `TAHUN`, `BULAN`, `RPBK`, `APP_CODE` ) values ";
			while (($emapData = fgetcsv($file, 5000000, ",")) !== FALSE)
			{
				$count++;

				if($count>1 and $emapData[0]!=''){

					$cID_PEL = $emapData[1];
					$cNO_RBM = trim($emapData[2]);
					$cKODE_AREA 	= substr($cNO_RBM, 0, 3);
					$cKODE_PETUGAS	= substr($cNO_RBM, 3, 3);
					$cKODE_RBM		= substr($cNO_RBM, 7, 1);
					$cNMR_URUT		= substr($cNO_RBM, 8, 3);
					$cNAMA = $emapData[3];
					$cNAMA = str_replace( "'" , "", $cNAMA );
					$cALM = $emapData[4];
					$cDAYA = $emapData[5];
					$nDAYA = str_replace( "," , "", $cDAYA );
					$nLEMBAR = $emapData[6];
					$cTGHN = $emapData[7];
					$nTGHN = str_replace( "," , "", $cTGHN );
					$cRPBK = $emapData[8];
					$nRPBK = str_replace( "," , "", $cRPBK );
					
					$qQUERY=SYS_QUERY("select * from bm_pel_pasca where IDPEL='$cID_PEL' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
					if(SYS_ROWS($qQUERY)==0) {
						$cQUERY = "INSERT into bm_pel_pasca ( `IDPEL`, `NAMA_PEL`, `ALAMAT`, `KODE_RBM` , `DAYA`, `APP_CODE`, `ENTRY`, `DATE_ENTRY` ) values ";
						$cQUERY .= "('". $cID_PEL . "', '" . $cNAMA . "', '" . $cALM . "', '" . $cNO_RBM . "', " . $nDAYA . ", '" . $cFILTER_CODE. "', '" . $_SESSION[gUSERCODE]. "', '" . date("Y-m-d H:i:s") . "') ";
					} else {
						$NOW = date("Y-m-d H:i:s");
						$cQUERY="update bm_pel_pasca set NAMA_PEL='$cNAMA', ALAMAT='$cALM', KODE_RBM='$cNO_RBM', DAYA='$nDAYA', LEMBAR='$nLEMBAR', TAGIHAN='$nTGHN', RPBK='$nRPBK', UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW' where IDPEL='$cID_PEL' and APP_CODE='$cFILTER_CODE'";
					}
					SYS_QUERY($cQUERY);

					$cQUERY="select IDPEL from bm_plg_pasca where IDPEL='$cID_PEL' and TAHUN='$nTHN' and BULAN='$nBLN' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
					$qQUERY=SYS_QUERY($cQUERY);
					if(SYS_ROWS($qQUERY)==0) {
						$cQUERY = "INSERT into bm_plg_pasca ( `IDPEL`, `TAHUN`, `BULAN`, `LEMBAR`, `TAGIHAN`, `RPBK`, `APP_CODE` ) values ";
						$cQUERY .= "('". $cID_PEL . "', " .$nTHN . ", " . $nBLN . ", " . $nLEMBAR . ", " . $nTGHN . ", " . $nRPBK . ", '" . $cFILTER_CODE . "') ";
					} else {
						$cQUERY="update bm_plg_pasca set IDPEL='$cID_PEL', LEMBAR=".$nLEMBAR. ", TAGIHAN=".$nTGHN.", RPBK='$nRPBK', TAHUN='$nTHN', BULAN='$nBLN' where IDPEL='$cID_PEL' and APP_CODE='$cFILTER_CODE'";
					}
					$q_UPDP=SYS_QUERY($cQUERY);
				}
			}


			fclose($file);
		}
		else
			echo 'Invalid File:Please Upload CSV File';
			APP_LOG_ADD($cHEADER, 'import pelanggan Invalid File:Please Upload CSV File');
//			$cRUN_STATUS = S_REPL('IMPORT_RUN_PROGRESS', '0');
		SYS_DB_CLOSE($DB2);
	}
	header('Location: bm_import_tusbung.php?_a=FINISHED');
}
?>
