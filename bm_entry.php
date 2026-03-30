<?php
// bm_entry.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('RQ92','PT. Yaza Pratama');

	$cKODE_RBM 		= S_MSG('RQ15','Kode RBM');
	$cNO_URUT 		= S_MSG('RQ39','No. Urut');
	$cSISA_TOKEN 	= S_MSG('RQ05','Sisa Token');
	$cKONDISI_RMH	= S_MSG('RQ11','Kondisi Rumah');
	$cSEGEL_TEL		= S_MSG('RQ12','Segel Telinga');
	$cSEGEL_TERM	= S_MSG('RQ13','Segel Terminal');
	$cLAM_INDIKATOR	= S_MSG('RQ14','Lampu Indikator');
	$cKETERANGAN 	= S_MSG('RQ19','Keterangan');
	$cPENGHUNI 		= S_MSG('RQ31','Berpenghuni');
	$cKOSONG 		= S_MSG('RQ32','Kosong');
	$cBAGUS 		= S_MSG('RQ33','Bagus');
	$cRUSAK 		= S_MSG('RQ34','Rusak');
	$cNYALA 		= S_MSG('RQ35','Menyala');
	$cLENGKAP 		= S_MSG('RQ28','Lengkap');
	$cTLENGKAP		= S_MSG('RQ29','Tidak Lengkap');
	$cPADAM 		= S_MSG('RQ36','Padam');

	$cSUBMIT	= S_MSG('F301','Save');
	$cCANCEL	= S_MSG('F302','Batal');
	$cFOTO		= S_MSG('RQ38','Foto');
	
	$_SESSION['NLATTITUDE'] = 0;
	$_SESSION['NLONGITUDE'] = 0;
	$nLONGITUDE= 0;
	
	$cQUERY="select * from bm_tb_plg where APP_CODE='$cFILTER_CODE' and DELETOR='' and IDPEL='$_SESSION[SKODE_PLGN]'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	$nROWS = SYS_ROWS($cCEK_KODE);
	if($nROWS=0){
		$cQUERY="insert into bm_tb_plg set IDPEL='$_SESSION[SKODE_PLGN]', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
	}
	$cALAMAT_PLG='';
	if (isset($_GET['_a'])) {
	} else {
		$cALAMAT_PLG = encode_string($_POST['UPD_ALAMAT']);
	}
	if($cALAMAT_PLG!=''){
		$cTB_PEL	= "update bm_tb_plg set ALAMAT_PLG='$cALAMAT_PLG' where APP_CODE='$cFILTER_CODE' and DELETOR='' and IDPEL='$_SESSION[SKODE_PLGN]'";
		SYS_QUERY($cTB_PEL);
	}

	$cKODE_TARIF='';
	if (isset($_GET['_t'])) {
	} else {
		$cKODE_TARIF = $_POST['UPD_KODE_TRF'];
	}
	$cTB_PEL	= "update bm_tb_plg set TRF_TPSG='$cKODE_TARIF' where APP_CODE='$cFILTER_CODE' and DELETOR='' and IDPEL='$_SESSION[SKODE_PLGN]'";
	SYS_QUERY($cTB_PEL);
	$_SESSION['INPUT_KODE_TARIF'] = $cKODE_TARIF;
	$cHEADER=$_SESSION['SNMR_METER'].'/'.$_SESSION['SNAMA_PLGN'];
//	DEF_WINDOW($cHEADER);
?>
	<!DOCTYPE html>
	<html class="login_page">
		<?php	require_once("scr_headtr.php");	?>
		<link href="assets/plugins/ios-switch/css/switch.css" rel="stylesheet" type="text/css" media="screen"/>
		<body onload="get_lokasi();">
			<div class="page-container row-fluid">


				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<div class="page-title">

						<div class="pull-left">
							<h2 class="title"><?php echo $_SESSION['SNMR_METER'].'/'.$_SESSION['SNAMA_PLGN']?></h2>                            
						</div>
					</div>
				</div>
				<div class="clearfix"></div>

				<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					<section class="box ">
						<div class="pull-right hidden-xs"></div>

						<div class="content-body">
							<div class="row">
								<form action ="bm_create.php" method="post">
									<div class="col-sm-12 col-xs-12">
										<label class="col-xs-6 form-label-700" style="font-size:150%;" for="field-1"><?php echo $cKODE_RBM?></label>
											<select name="ADD_KODE_RBM" class="col-xs-6 form-label-900" style="font-size:200%;">
											<?php 
												$REC_DATA=SYS_QUERY("select KODE_RUTE, NAMA_RUTE from bm_tb_rute where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($aREC_DETAIL=SYS_FETCH($REC_DATA)){
													echo "<option value='$aREC_DETAIL[KODE_RUTE]'  >$aREC_DETAIL[KODE_RUTE]</option>";
												}
											?>
											</select>
										<div class="clearfix"></div>

										<div class="form-group">
											<label class="col-xs-6 form-label-700" style="font-size:150%;"><?php echo $cNO_URUT?></label>
											<input name='ADD_NO_URUT' type="number" class="col-xs-6 form-label-900" style="padding:0 5px;font-size:200%;" data-mask="fdecimal" maxlength="3"><br>
										</div>
										<div class="clearfix"></div>

										<label class="col-xs-6 form-label-700" style="font-size:150%;"><?php echo $cSISA_TOKEN?></label>
										<input type="number" step="0.01" class="col-xs-6 form-label-900" name='ADD_SISA_TOKEN' style="padding:0 5px;font-size:200%;" data-mask="fdecimal"><br>
										<div class="clearfix"></div>

										<label class="col-xs-4 form-label-700" style="font-size:150%;" for="field_1"><?php echo $cKONDISI_RMH?></label>
											<select name="ADD_KONDISI_RMH" class="col-xs-8 form-label-900" style="font-size:150%;">
											<?php 
												$REC_DATA=SYS_QUERY("select KODE_KOND, NAMA_KOND from bm_tb_kond where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($a_TB_KOND =SYS_FETCH($REC_DATA)){
													echo "<option style='font-size:200%;' value='$a_TB_KOND[KODE_KOND]'  >$a_TB_KOND[NAMA_KOND]</option>";
												}
											?>
											</select>
										<div class="clearfix"></div><br>

										<div class="form-group">
											<label class="col-sm-4 form-label-700" style="font-size:150%;" for="field-1"><?php echo $cSEGEL_TEL?></label>
											<div class="form-block">
												<input name="ADD_SEGEL_TEL" id="ADD_SEGEL_TEL" type="checkbox" class="col-sm-1 iswitch iswitch-lg iswitch-primary" checked>
												<label id='label_tel' class="col-sm-4 form-label-900" style="font-size:200%;"><i><?php echo $cLENGKAP?></i>
											</div>
										</div>	<div class="clearfix"></div><br>

										<div class="form-group">
											<label class="col-sm-4 form-label-700" style="font-size:150%;" for="field-1"><?php echo $cSEGEL_TERM?></label>
											<div class="form-block">
												<input name="ADD_SEGEL_TER" id="ADD_SEGEL_TER" type="checkbox" class="col-sm-1 iswitch iswitch-lg iswitch-primary" checked>
												<label id='label_ter' class="col-sm-4 form-label-900" style="font-size:200%;"><i><?php echo $cLENGKAP?></i>
											</div>
										</div>	<div class="clearfix"></div><br>

										<div class="form-group">
											<label class="col-sm-4 form-label-700" style="font-size:150%;" for="field-1"><?php echo $cLAM_INDIKATOR?></label>
											<div class="form-block">
												<input name="ADD_LAMPU_INDI" id="ADD_LAMPU_INDI" type="checkbox" class="col-sm-1 iswitch iswitch-lg iswitch-primary" checked>
												<label id='label_ind' class="col-sm-4 form-label-900" style="font-size:200%;"><i><?php echo $cNYALA?></i>
											</div>
										</div>	<div class="clearfix"></div><br>

										<div class="form-group">
											<label class="col-sm-4 form-label-700" style="font-size:150%;" for="field-1"><?php echo $cKETERANGAN?></label>
											<input type="text" class="form-control col-sm-6 form-label-900"  style="font-size:125%;" name="ADD_NOTE">
										</div>	<div class="clearfix"></div><br>

										<div class="text-left">
											<input type="submit" class="col-sm-4 btn btn-info btn-lg" value=<?php echo $cSUBMIT?>>
											<input type="button" class="col-sm-3 btn btn btn-orange btn-lg" value=<?php echo $cCANCEL?> onclick=window.location.href='bm_meter.php'>
											<input type="button" class="col-sm-4 btn btn-info btn-lg" value=<?php echo $cFOTO?> onclick=window.location.href="bm_foto.php?_a=<?php echo $cALAMAT_PLG?>&_t=">
										</div>	<div class="clearfix"></div><br>

									</div>
								</form>
							</div>
						</div>
					</section>
<?php
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
?>

<script>

$('#ADD_KONDISI_RMH').on('click',function(){
	var checked = document.getElementById("ADD_KONDISI_RMH").checked;
	if (checked) {
		$('#label_rmh').html('<?php echo $cPENGHUNI?>');
	} else {
		$('#label_rmh').html('<?php echo $cKOSONG?>');
	}
});

$('#ADD_SEGEL_TEL').on('click',function(){
	var checked = document.getElementById("ADD_SEGEL_TEL").checked;
	if (checked) {
		$('#label_tel').html('<?php echo $cLENGKAP?>');
	} else {
		$('#label_tel').html('<?php echo $cTLENGKAP?>');
	}
});

$('#ADD_SEGEL_TER').on('click',function(){
	var checked = document.getElementById("ADD_SEGEL_TER").checked;
	if (checked) {
		$('#label_ter').html('<?php echo $cLENGKAP?>');
	} else {
		$('#label_ter').html('<?php echo $cTLENGKAP?>');
	}
});

$('#ADD_LAMPU_INDI').on('click',function(){
	var checked = document.getElementById("ADD_LAMPU_INDI").checked;
	if (checked) {
		$('#label_ind').html('<?php echo $cNYALA?>');
	} else {
		$('#label_ind').html('<?php echo $cPADAM?>');
	}
});

function GET_FOTO() {
	navigator.getUserMedia  = navigator.getUserMedia ||
							  navigator.webkitGetUserMedia ||
							  navigator.mozGetUserMedia ||
							  navigator.msGetUserMedia;

	var video = document.querySelector('video');

	if (navigator.getUserMedia) {
	  navigator.getUserMedia({audio: true, video: true}, function(stream) {
		video.src = window.URL.createObjectURL(stream);
	  }, errorCallback);
	} else {
	  video.src = 'somevideo.webm'; // fallback.
	}
}

function get_lokasi() {

	if (navigator.geolocation)	{
		navigator.geolocation.getCurrentPosition(showPosition);
	}

	function showPosition(position)	{
		$.post('bm_lokasi.php',{
			_la:position.coords.latitude,
			_lo:position.coords.longitude
		});
	}
}

</script>
