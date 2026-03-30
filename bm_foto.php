<?php
// bm_foto.php

// https://tutorialzine.github.io/pwa-photobooth/
// https://github.com/tutorialzine/pwa-photobooth/
// Progressive Web App: PhotoBooth
	
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

//	echo "<script> alert('Masih dalam taraf pengembangan');	window.location.href='bm_meter.php';	</script>";

	$cHEADER	= S_MSG('RQ41','Ambil foto');
	$cAMBIL_F	= S_MSG('RQ42','Pengambilan foto');
	$cCAP_TAP	= S_MSG('RQ43','Tap disini untuk memulai');
	$cULANGI	= S_MSG('RQ44','Ulangi');
	$cJEPRET	= S_MSG('RQ45','Jepret');
	$cKIRIM		= S_MSG('RQ46','Kirim');
	$cCANCEL	= S_MSG('F306','Batal');

	$cALAMAT_PLG	= '';
	if (isset($_GET['_a'])) {
		$cALAMAT_PLG=$_GET['_a'];
	}
	$cKODE_TARIF	= '';
	if (isset($_GET['_t'])) {
		$cKODE_TARIF=$_GET['_t'];
	}
?>
<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title><?php echo $cHEADER?></title>

		<link rel="stylesheet" href="foto/styles.css">

		<!-- Meta tag for changing browser colors. -->
		<meta id="theme-color" name="theme-color" content="#37474F">

		<!-- Meta tag for App-like behaviour in iOS -->
		<meta name=”apple-mobile-web-app-capable” content=”yes”>

		<!-- Web Manifest -->
		<link rel="manifest" href="foto/manifest.json">

	</head>
	<body>
		<h3><?php echo $cAMBIL_F?></h3>
		<div class="container">
			<div class="app">
				<a href="#" id="start-camera" class="visible"><?php echo $cCAP_TAP?></a>
				<video muted id="camera-stream"></video>
				<img id="snap">

				<p id="error-message"></p>
				<div class="controls">
					<a href="#" id="delete-photo" title="Delete Photo" class="disabled"><i class="material-icons"><?php echo $cULANGI?></i></a>
					<a href="#" id="take-photo" onclick="img();" title="Take Photo"><i class="material-icons"><?php echo $cJEPRET?></i></a>
					<a href="#" id="download-photo" download="selfie.png" title="Save Photo" class="disabled"><i class="material-icons"><?php echo $cKIRIM?></i></a>  
				</div>
				<?php
					echo '<img id="hasil_foto">';
				?>

				<!-- Hidden canvas element. Used for taking snapshot of video. -->
				<canvas id="MyFoto"> </canvas>	<br>
			</div>
			<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
				<div class="text-left">
					<input type="button" class="col-sm-12 btn btn btn-orange btn-lg" value=<?php echo $cCANCEL?> onclick=window.location.href='bm_entry.php?_a=<?php echo $cALAMAT_PLG?>&_t=<?php echo $cKODE_TARIF?>'>
				</div>
			</div>
		</div>

		<script>
		function img() {
			if ($('#snap').prop('src') != '') {
				alert('foto nye OK');
			}
		}
		// Register Service Worker.

		if ('serviceWorker' in navigator) {
			// Path is relative to the origin, not project root.
			navigator.serviceWorker.register('foto/sw.js')
			.then(function(reg) {
				console.log('Registration succeeded. Scope is ' + reg.scope);
			})
			.catch(function(error) {
				console.error('Registration failed with ' + error);
			});
		}
		</script>
		<script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
		<script src="foto/script.js"></script>

	</body>
</html>

<script>
var dataURL = canvas.toDataURL();

$.ajax({
  type: "POST",
  url: "foto/simpan_ft.php",
  data: { 
     imgBase64: dataURL
  }
}).done(function(o) {
  console.log('saved'); 
});

            // Upload image to sever 
            document.getElementById("upload").addEventListener("click", function(){
                var dataUrl = canvas.toDataURL("image/jpeg", 0.85);
                $("#uploading").show();
                $.ajax({
                  type: "POST",
                  url: "save_foto2.php",
                  data: { 
                     imgBase64: dataUrl,
                     user: "Joe",       * 
                     userid: 25         * 
                  }
                }).done(function(msg) {
                  console.log("saved");
                  $("#uploading").hide();
                  $("#uploaded").show();
                });
            });

</script>
