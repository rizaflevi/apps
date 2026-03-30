<?php
// prs_import_peg.php
// import data pelanggan dari csv file

//	include "preloader.php";
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cTITLE=S_MSG('MN43','CSV File Upload : ');

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
														<p class="help-block">Only CSV File Import.</p>
													</div>
													<div class="text-left">
														<button type="submit" class="btn btn-default" name="Import" value="Import">Upload</button>
													</div><br><br>
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
	SYS_DB_CLOSE($DB2);	break;

case 'up_load' :

	if(isset($_POST["Import"]))
	{
		$filename=$_FILES["file"]["tmp_name"];
		if($_FILES["file"]["size"] > 3)
		{
			$cP1_DEL = SYS_QUERY("delete from person1 where APP_CODE='$cFILTER_CODE'");
			$cP5_DEL = SYS_QUERY("delete from person5 where APP_CODE='$cFILTER_CODE'");
			$cP6_DEL = SYS_QUERY("delete from person6 where APP_CODE='$cFILTER_CODE'");
			$cP7_DEL = SYS_QUERY("delete from person7 where APP_CODE='$cFILTER_CODE'");
			$cP8_DEL = SYS_QUERY("delete from person8 where APP_CODE='$cFILTER_CODE'");
			$file 	= fopen($filename, "r");
			$count 	= 0;
			$n_PLG	= 0;
			$xPROGRESS = 2;
			$cPERSON1 = "INSERT into person1 ( `PRSON_CODE`, `PRSON_NAME`, `PRSON_GEND`, `ADDR1`, `PRSN_KEL`, `PRSN_KEC`, `PRS_CITY`, `PRS_PHN`, `BIRTH_PLC` , `BIRTH_DATE`, `PRSON_GEND`, `PRSON_RELG`, `MARRIAGE`, `PRS_KTP`, `JOB_TYPE`, `PRSON_ACCN`, `PRSON_BANK`, `PRSON_PDDK`, `NO_KKLRG`, `NM_KKLRG`, `SUPPOSE`, `BPJS_SEHAT`, `BPJS_KERJA`, `RESIGN_DATE`, `APP_CODE` ) values ";
			$cPERSON5 = "INSERT into person5 ( `PRSON_CODE`, `EDU_CODE`, `EDU_JRSN`, `APP_CODE` ) values ";
			$cPERSON6 = "INSERT into person6 ( `PRSON_CODE`, `APP_CODE`, `JOB_CODE`, `KODE_LOKS`, `WORK_DAY` ) values ";
			$cPERSON7 = "INSERT into person7 ( `PRSON_CODE`, `APP_CODE`, `FULL_NAME`, `NO_BPJS`, `N_I_K`, `GENDER`, `BIRTH_PLCE`, `BIRTH_DATE`, `EDUCATE`, `J_O_B`, `STATUS_MAR`, `STA_TUS`, `CITI_ZEN`, `FATH_NAME`, `MOTH_NAME`, `RELI_GION` ) values ";
			$cPERSON8 = "INSERT into person8 ( `PRSON_CODE`, `APP_CODE`, `SKILL_CODE`, `SKILL_REG`, `SKILL_SERT` ) values ";
			while (($emapData = fgetcsv($file, 5000000, ",")) !== FALSE)
			{
				$count++;
				$cSN_AREA 	= $emapData[0];
				$cKODE_PEG 	= $emapData[1];
				$cNAMA_PEG 	= encode_string($emapData[2]);
				$cJNS_KLM 	= ltrim($emapData[3]);
				$cJABATAN 	= ltrim($emapData[4]);
				$cJABATAN 	= rtrim($cJABATAN);
				$cCUSTOMER 	= ltrim($emapData[5]);
				$cLOKASI 	= ltrim($emapData[6]);
				$cLOKASI 	= rtrim($cLOKASI);
				$cTMP_LH 	= encode_string($emapData[7]);
				$cTGL_LH 	= $emapData[8];
				$dTGL_LH 	= date("y-m-d", strtotime($cTGL_LH) );
				$cALAMAT 	= encode_string($emapData[9]);
				$cKELURAHAN = encode_string($emapData[10]);
				$cKECAMATAN = encode_string($emapData[11]);
				$cKOTA   	= encode_string($emapData[12]);
				$cKABUPATEN = encode_string($emapData[13]);
				$cPROVINSI 	= encode_string($emapData[14]);
				$cPENDIDIKAN = ltrim($emapData[15]);
				$cPENDIDIKAN = rtrim($cPENDIDIKAN);
				$cJURUSAN 	= ltrim($emapData[16]);
				$cPEND_SATPAM = encode_string($emapData[17]);
				$cREG_SATPAM = encode_string($emapData[18]);
				$cIJZ_SATPAM = encode_string($emapData[19]);
				$cNO_HAPE 	= encode_string($emapData[20]);
				$cNO_KTP 	= encode_string($emapData[21]);
				$cTGL_MSK 	= $emapData[22];
				$cNO_BPJST	= encode_string($emapData[23]);
				$cNO_BPJSK	= str_replace( "'" , '',($emapData[24]));
				$cNM_ISTRI	= encode_string($emapData[25]);
				$cTMP_LHR_ISTRI	= encode_string($emapData[26]);
				$cTGL_LHR_ISTRI 	= $emapData[27];
				$dTGL_LHR_ISTRI = date("y-m-d", strtotime($cTGL_LHR_ISTRI) );
				$cNIK_ISTRI	= encode_string($emapData[28]);
				$cBPJS_ISTRI= $emapData[29];
				$cBPJS_ISTRI = str_replace( "'" , '',$cBPJS_ISTRI);

				$cNAMA_ANAK1	= encode_string($emapData[30]);
				$cTMP_LHR_ANAK1	= encode_string($emapData[31]);
				$cTGL_LHR_ANAK1 = $emapData[32];
				$dTGL_LHR_ANAK1 = date("y-m-d", strtotime($cTGL_LHR_ANAK1) );
				$cNIK_ANAK1		= str_replace( "'" , '',$emapData[33]);
				$cBPJS_ANAK1	= str_replace( "'" , '',$emapData[34]);

				$cNAMA_ANAK2	= encode_string($emapData[35]);
				$cTMP_LHR_ANAK2	= encode_string($emapData[36]);
				$cTGL_LHR_ANAK2 = $emapData[37];
				$dTGL_LHR_ANAK2 = date("y-m-d", strtotime($cTGL_LHR_ANAK2) );
				$cNIK_ANAK2		= str_replace( "'" , '',$emapData[38]);
				$cBPJS_ANAK2	= str_replace( "'" , '',$emapData[39]);

				$cNAMA_ANAK3	= encode_string($emapData[40]);
				$cTMP_LHR_ANAK3	= encode_string($emapData[41]);
				$cTGL_LHR_ANAK3 = $emapData[42];
				$dTGL_LHR_ANAK3 = date("y-m-d", strtotime($cTGL_LHR_ANAK3) );
				$cNIK_ANAK3		= str_replace( "'" , '',$emapData[43]);
				$cBPJS_ANAK3	= str_replace( "'" , '',$emapData[44]);

				$cNO_REK	= $emapData[46];
				$cNM_BANK	= $emapData[47];
				$cRE_SIGN 	= $emapData[48];
/*
				if($count==2){
					if($cSN_AREA!='S/N AREA') {
						$cMSG_FORMAT = S_MSG('MN44','Format file tidak benar !');
						echo "<script> alert('$cMSG_FORMAT');	window.history.back();	</script>";
						return;
					}
				}
*/
				if($count>3){
					$nKLMN = '2';
					if($cJNS_KLM=='L') {
						$nKLMN = '1';
					}
					$cPERSONJ="select * from personj where JOB_NAME like '%".$cJABATAN."%' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
					$qPERSONJ=SYS_QUERY($cPERSONJ);
					if (SYS_ROWS($qPERSONJ)>0){
					} else {
						die ('Jabatan not found : -'.$cJABATAN.'- on line '.$count);
					}
					$a_PERSONJ = SYS_FETCH($qPERSONJ);
					$cJABATAN = $a_PERSONJ['JOB_CODE'];

					$cPERSONE="select * from persone where EDU_NAME like '%".$cPENDIDIKAN."%' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
					$qPERSONE=SYS_QUERY($cPERSONE);
					if (SYS_ROWS($qPERSONE)>0){
					} else {
						die ('Penddk not found : -'.$cPENDIDIKAN.'- on line '.$count);
					}
					$a_PERSONE = SYS_FETCH($qPERSONE);
					$cKODE_PDDKN = $a_PERSONE['EDU_CODE'];

					$cPRS_LOKS="select * from prs_loks where LOKS_NAME like '%".$cLOKASI."%' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
					$qPRS_LOKS=SYS_QUERY($cPRS_LOKS);

					if (SYS_ROWS($qPRS_LOKS)>0){
					} else {
						die ('Lokasi not found : -'.$cLOKASI.'- on line '.$count);
					}
					$a_PRS_LOKS = SYS_FETCH($qPRS_LOKS);
					$cLOKASI_KERJA = $a_PRS_LOKS['LOKS_CODE'];

					$cSKILL_PERSON = '';
					if($cPEND_SATPAM!='') {
						$cPRS_SKIL="select * from prs_skil where SKILL_NAME like '%".$cPEND_SATPAM."%' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
						$qPRS_SKIL=SYS_QUERY($cPRS_SKIL);

						if (SYS_ROWS($qPRS_SKIL)>0){
							$a_PRS_SKIL = SYS_FETCH($qPRS_SKIL);
							$cSKILL_PERSON = $a_PRS_SKIL['SKILL_CODE'];
						}
						
					}
					$cPERSON1 .= "('".$cKODE_PEG."', '".$cNAMA_PEG."', ".$nKLMN.", '".$cALAMAT."', '".$cKELURAHAN."', '".$cKECAMATAN."', '".$cKOTA."', '".$cNO_HAPE."', '".$cTMP_LH."', '" . $dTGL_LH . "',1, 1, 2, '" . $cNO_KTP .  "', '" .$cJABATAN ."', '" .$cNO_REK ."', '" .$cNM_BANK ."', '".$cPENDIDIKAN ."', '".$cNO_KTP."', '".$cNAMA_PEG."', '".$cNM_ISTRI."', '".$cNO_BPJSK."', '".$cNO_BPJST."', '".$cRE_SIGN."', '".$cFILTER_CODE."'), ";
					$NOW = date("Y-m-d H:i:s");
					$cPERSON5 .= "('". $cKODE_PEG."', '". $cKODE_PDDKN."', '". $cJURUSAN . "', '" . $cFILTER_CODE. "'), ";

					$cPERSON6 .= "('". $cKODE_PEG."', '". $cFILTER_CODE  . "', '" . $cJABATAN. "', '". $cLOKASI_KERJA. "', ' 111111'), ";
					$cPERSON7 .= "('". $cKODE_PEG."', '". $cFILTER_CODE  . "', '" . $cNAMA_PEG. "', '". $cNO_BPJSK. "', '".$cNO_KTP."', 1, '". $cTMP_LH."', '" . $dTGL_LH."', '" . $cPENDIDIKAN. "', 'Swasta', 2, 'Kepala Keluarga', 'Indonesia', ' ', ' ', 1), ";
					if ($cNM_ISTRI!='') {
						$cPERSON7 .= "('". $cKODE_PEG."', '". $cFILTER_CODE  . "', '" . $cNM_ISTRI. "', '". $cBPJS_ISTRI. "', '".$cNIK_ISTRI."', 2, '". $cTMP_LHR_ISTRI. "', '".$dTGL_LHR_ISTRI. "',' ', ' ', 2, 'Istri', 'Indonesia', ' ', ' ', 1), ";
					}
					if ($cNAMA_ANAK1!='') {
						$cPERSON7 .= "('". $cKODE_PEG."', '". $cFILTER_CODE  . "', '" . $cNAMA_ANAK1. "', '". $cBPJS_ANAK1. "', '".$cNIK_ANAK1."', 1, '". $cTMP_LHR_ANAK1. "', '".$dTGL_LHR_ANAK1. "',' ', ' ', 1, 'Anak', 'Indonesia', '".$cNAMA_PEG."', '".$cNM_ISTRI."', 1), ";
					}
					if ($cNAMA_ANAK2!='') {
						$cPERSON7 .= "('". $cKODE_PEG."', '". $cFILTER_CODE  . "', '" . $cNAMA_ANAK2. "', '". $cBPJS_ANAK2. "', '".$cNIK_ANAK2."', 1, '". $cTMP_LHR_ANAK2. "', '".$dTGL_LHR_ANAK2. "',' ', ' ', 1, 'Anak', 'Indonesia', '".$cNAMA_PEG."', '".$cNM_ISTRI."', 1), ";
					}
					if ($cNAMA_ANAK3!='') {
						$cPERSON7 .= "('". $cKODE_PEG."', '". $cFILTER_CODE  . "', '" . $cNAMA_ANAK3. "', '". $cBPJS_ANAK3. "', '".$cNIK_ANAK3."', 1, '". $cTMP_LHR_ANAK3. "', '".$dTGL_LHR_ANAK3. "',' ', ' ', 1, 'Anak', 'Indonesia', '".$cNAMA_PEG."', '".$cNM_ISTRI."', 1), ";
					}
					if ($cSKILL_PERSON!='') {
						$cPERSON8 .= "('". $cKODE_PEG."', '". $cFILTER_CODE  . "', '" . $cSKILL_PERSON. "', '". $cREG_SATPAM. "', '".$cIJZ_SATPAM. "'), ";
					}
				}
			}
			$cPERSON1 .= "; ";
			$cPERSON1 = str_replace( ", ;" , ";", $cPERSON1 );
			$qQ_TB_PEL = SYS_QUERY($cPERSON1);

			$cPERSON5 .= "; ";
			$cPERSON5 = str_replace( ", ;" , ";", $cPERSON5 );
			$qPERSON5 = SYS_QUERY($cPERSON5);

			$cPERSON6 .= "; ";
			$cPERSON6 = str_replace( ", ;" , ";", $cPERSON6 );
			$qPERSON6 = SYS_QUERY($cPERSON6);

			$cPERSON7 .= "; ";
			$cPERSON7 = str_replace( ", ;" , ";", $cPERSON7 );
			$qPERSON7 = SYS_QUERY($cPERSON7);

			$cPERSON8 .= "; ";
			$cPERSON8 = str_replace( ", ;" , ";", $cPERSON8 );
			$qPERSON8 = SYS_QUERY($cPERSON8);

			fclose($file);
		}
		else
			echo 'Invalid File:Please Upload CSV File';
		SYS_DB_CLOSE($DB2);
	}
	header('Location: prs_import_peg.php?_a=FINISHED');
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
