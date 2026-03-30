<?php
//	tb_geofencing.php //

	require_once "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Geofencing.pdf';

	$cHEADER = S_MSG('TG51','Tabel Geofence');

	$can_CREATE = TRUST($cUSERCODE, 'TB_GEOFENCE_1ADD');

	$qTB_GEOFENCE=OpenTable('TbGeofence', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'GEO_NAME');
	
	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

	$cKODE_TBL 	= S_MSG('F003','Kode');
	$cNAMA_TBL 	= S_MSG('PI02','Keterangan');
	$cLATITUDE 	= S_MSG('TG56','Latitude');
	$cLONGITUDE = S_MSG('TG57','Longitude');
	$cDISTANCE  = S_MSG('TG58','Jarak');
	$cNOTE  	= S_MSG('TI16','Note');
	$cADD_REC	= S_MSG('TG53','Tambah Geofence');
	$cEDIT_TBL	= S_MSG('TG54','Edit Tabel Geofence');
	$cSAVE		= S_MSG('F301','Save');

	$cTTIP_KODE	= S_MSG('TG6A','Setiap lokasi absen perlu diberi kode supaya bisa di akses berdasarkan kode nya masing-masing.');
	$cTTIP_NAMA	= S_MSG('TG6B','Keterangan lokasi geofence');
	
switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
?>

	<!DOCTYPE html>
	<html>
		<?php	require_once("scr_header.php");	?>
		<body class=" ">
			<link rel="stylesheet" href="assets/leaflet/leaflet.css" />
			<script src="assets/leaflet/leaflet.js"></script>
			<link rel="stylesheet" href="assets/leaflet/Leaflet.PinSearch.css">
			<script src="assets/leaflet/Leaflet.PinSearch.js"></script>
			<?php	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
						<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
					</div>
					<div class="project-info"></div>
				</div>

				<section id="main-content" class=" ">
					<style>
						#main-content{
							height: 1200px;
						}
					</style>
					<section class="wrapper main-wrapper" style=''>
						<div class="clearfix"></div>

						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left"><?php echo $cHEADER?></h2>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>
											<?php	if ($can_CREATE==1)
												echo '<a href="?_a=' .md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'.S_MSG('KA11','Add new'). '</a>'; ?>
											</li>
											<li>
												<a href="#help_prs_tb_geof" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									<div class="row">
										<div>
											<table class="table table-striped table-bordered" id="example" style="width:100%">
												<thead>
													<tr>
														<!-- <td>#</td> -->
														<td>KODE GEOFENCING</td>
														<td>NAMA</td>
													</tr>
												</thead>
												<tbody>
<?php
										// TDIV();
											// TABLE('example');
												// THEAD([$cKODE_TBL, $cNAMA_TBL]);
												// echo '<tbody>';
													while($aTB_GEOFENCE=SYS_FETCH($qTB_GEOFENCE)) {
													echo '<tr>';
														// echo '<td style="width: 1px;"></td>';
														echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aTB_GEOFENCE['GEO_CODE'])."'>".decode_string($aTB_GEOFENCE['GEO_CODE'])."</a></span></td>";
														echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aTB_GEOFENCE['GEO_CODE'])."'>".decode_string($aTB_GEOFENCE['GEO_NAME'])."</a></span></td>";
													echo '</tr>';
													}
												// echo '</tbody>';
											// echo '</table>';
										// eTDIV();
?>
																	</tbody>
																	</table>
																	</div>
									</div>
								</div>
							</section>
							<section class="box">
								<h1>leaflet geofence</h1>
								<!-- <p>Klik kanan pada peta untuk mendapatkan titik latitude dan longitude pada peta.</p> -->
								<div id="map" style="height:500px;width:500px;"></div>
							</section>
						</div>

					</section>
				</section>
				<?php	//include "scr_chat.php";	?>
			</div>
			<?php	require_once("js_framework.php");	?>
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script>
			<script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 

		</body>
	</html>
<?php
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('cr34t3'):
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=DB_ADD', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,3], '900', 'ADD_GEO_CODE', '', '', '', '', 10, '', 'fix', $cTTIP_KODE);
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'ADD_GEO_NAME', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([3,3,3,6], '700', $cLATITUDE);
					INPUT('text', [3,3,3,6], '900', 'ADD_LATITUDE', '', '', 'fdecimal', 'right', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cLONGITUDE);
					INPUT('text', [3,3,3,6], '900', 'ADD_LONGITUDE', '', '', 'fdecimal', 'right', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cDISTANCE);
					INPUT('text', [1,1,1,6], '900', 'ADD_DISTANCE', '', '', 'fdecimal', 'right', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNOTE);
					INPUT('text', [8,8,8,6], '900', 'ADD_GEO_NOTE', '', '', '', '', 0, '', 'fix');
					$cSAVE		= ($can_CREATE ? S_MSG('F301','Save') : '');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('up_d4t3'):
		$can_UPDATE = TRUST($cUSERCODE, 'TB_GEOFENCE_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'TB_GEOFENCE_3DEL');
		$qTB_GEOFENCE=OpenTable('TbGeofence', "APP_CODE='$cAPP_CODE' and DELETOR='' and md5(GEO_CODE)='$_GET[_r]' ");
		$REC_TB_GEOF=SYS_FETCH($qTB_GEOFENCE);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_geof').'&_id='. $REC_TB_GEOF['GEO_CODE']. '" onClick="return confirm('. "'". S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?'). "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_id='.$REC_TB_GEOF['GEO_CODE'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_GEO_CODE', $REC_TB_GEOF['GEO_CODE'], '', '', '', 0, 'disabled', 'fix', $cTTIP_KODE);
?>
					<label class="col-sm-4 form-label-700" for="field-1"><?= $cNAMA_TBL?></label>
					<input id="geoname" type="text" class="col-sm-8 form-label-900" name='EDIT_GEO_NAME' value="<?= DECODE($REC_TB_GEOF['GEO_NAME'])?>" title="<?= $cTTIP_NAMA?>" autofocus>
					<div class="clearfix"></div>

					<label class="col-sm-4 form-label-700" for="field-3"><?= $cLATITUDE?></label>
					<input id="geolat" type="text" class="col-sm-3 form-label-900" name='UPD_LATITUDE' value="<?= $REC_TB_GEOF['LATITUDE']?>" data-mask="fdecimal" data-numeric-align="right"><br><br>
					<div class="clearfix"></div>
					
					<label class="col-sm-4 form-label-700" for="field-3"><?= $cLONGITUDE?></label>
					<input id="geolon" type="text" class="col-sm-3 form-label-900" name='UPD_LONGITUDE' value="<?= $REC_TB_GEOF['LONGITUDE']?>" data-mask="fdecimal" data-numeric-align="right"><br><br>
					<div class="clearfix"></div>

					<label class="col-sm-4 form-label-700" for="field-3"><?= $cDISTANCE?></label>
					<input id="georad" type="text" class="col-sm-2 form-label-900" name='UPD_DISTANCE' value="<?= $REC_TB_GEOF['DISTANCE']?>" data-mask="fdecimal" data-numeric-align="right" title="<?= S_MSG('TG65','Jarak toleransi ke titik absen')?>"><br><br>
					<div class="clearfix"></div>

					<label class="col-sm-4 form-label-700" for="field-1"><?= $cNOTE?></label>
					<input id="geonote" type="text" class="col-sm-7 form-label-700" name='UPD_GEO_NOTE' value="<?= $REC_TB_GEOF['GEO_NOTE']?>" title="<?= S_MSG('TG66','Keterangan tambahan')?>">
					<div class="clearfix"></div><br>

					<?php SAVE($can_UPDATE==1 ? $cSAVE : ''); ?>
				</div>
		</div>
	</section>
		</br>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
		<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
		<script src="assets/js/jquery-3.7.0.min.js" type="text/javascript"></script>
		<section class='box'>
			<h1>leaflet geofence</h1>
			<p>catatan: lingkaran merah adalah lokasi absen sebelum update, titik marker biru adalah data posisi 15 titik dimana karyawan terakhir absen</p>
			<p>Klik kanan pada peta untuk mendapatkan titik latitude dan longitude pada peta.</p>
			<div id='mapz' style='height:350px;width:350px;'></div>
		</section>
	</div>
	
		</section>
	</section>
		
			
<?php
		// eTFORM();
		require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		
		break;

case 'DB_ADD':
	$cGEO_CODE	= ENCODE($_POST['ADD_GEO_CODE']);	
	if($cGEO_CODE==''){
		MSG_INFO(S_MSG('TG67','Kode Geofence belum diisi'));
		return;
	}
	$cGEO_NOTE	= ENCODE($_POST['ADD_GEO_NOTE']);
	$nLATT=($_POST['ADD_LATITUDE'] ? $_POST['ADD_LATITUDE'] : 0);
	$nLONG=($_POST['ADD_LONGITUDE'] ? $_POST['ADD_LONGITUDE'] : 0);
	$nDIST=($_POST['ADD_DISTANCE'] ? $_POST['ADD_DISTANCE'] : 0);
	$qTB_GEOFENCE=OpenTable('TbGeofence', "APP_CODE='$cAPP_CODE' and DELETOR='' and GEO_CODE='$cGEO_CODE'");
	if(SYS_ROWS($qTB_GEOFENCE)>0){
		MSG_INFO(S_MSG('TG68','Kode Geofence sudah ada'));
		return;
		header('location:tb_geofencing.php');
	} else {
		$cGEO_NAME	= ENCODE($_POST['ADD_GEO_NAME']);
        RecCreate('TbGeofence', ['GEO_CODE', 'GEO_NAME', 'LATITUDE', 'LONGITUDE', 'DISTANCE', 'GEO_NOTE', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], 
			[$cGEO_CODE, $cGEO_NAME, $nLATT, $nLONG, $nDIST, $cGEO_NOTE, $cUSERCODE, date("Y-m-d H:i:s"), $cAPP_CODE]);
		header('location:tb_geofencing.php');
	}
	break;

case 'rubah':
	$KODE_CRUD=$_GET['_id'];
	$cGEO_NAME	= ENCODE($_POST['EDIT_GEO_NAME']);
	$cGEO_NOTE	= ENCODE($_POST['UPD_GEO_NOTE']);
	$nLATT=($_POST['UPD_LATITUDE'] ? $_POST['UPD_LATITUDE'] : 0);
	$nLONG=($_POST['UPD_LONGITUDE'] ? $_POST['UPD_LONGITUDE'] : 0);
	$nDIST=($_POST['UPD_DISTANCE'] ? $_POST['UPD_DISTANCE'] : 0);
    RecUpdate('TbGeofence', ['GEO_NAME', 'LATITUDE', 'LONGITUDE', 'DISTANCE', 'GEO_NOTE', 'UP_DATE', 'UPD_DATE'], 
		[$cGEO_NAME, $nLATT, $nLONG, $nDIST, $cGEO_NOTE, $cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE'  and GEO_CODE='$KODE_CRUD'");
	header('location:tb_geofencing.php');
	break;

case md5('del_geof'):
	$KODE_CRUD=$_GET['_id'];
	RecUpdate('TbGeofence', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and GEO_CODE='$KODE_CRUD'");
	header('location:tb_geofencing.php');
}
?>

<script type="text/javascript" language="JavaScript">
$(document).ready(function(){
    if($('#map').length > 0 ){
 
		//  Formatted version of a popular md5 implementation
		//  Original copyright (c) Paul Johnston & Greg Holt.
		//  The function itself is now 42 lines long.

		function md5(inputString) {
			var hc="0123456789abcdef";
			function rh(n) {var j,s="";for(j=0;j<=3;j++) s+=hc.charAt((n>>(j*8+4))&0x0F)+hc.charAt((n>>(j*8))&0x0F);return s;}
			function ad(x,y) {var l=(x&0xFFFF)+(y&0xFFFF);var m=(x>>16)+(y>>16)+(l>>16);return (m<<16)|(l&0xFFFF);}
			function rl(n,c)            {return (n<<c)|(n>>>(32-c));}
			function cm(q,a,b,x,s,t)    {return ad(rl(ad(ad(a,q),ad(x,t)),s),b);}
			function ff(a,b,c,d,x,s,t)  {return cm((b&c)|((~b)&d),a,b,x,s,t);}
			function gg(a,b,c,d,x,s,t)  {return cm((b&d)|(c&(~d)),a,b,x,s,t);}
			function hh(a,b,c,d,x,s,t)  {return cm(b^c^d,a,b,x,s,t);}
			function ii(a,b,c,d,x,s,t)  {return cm(c^(b|(~d)),a,b,x,s,t);}
			function sb(x) {
				var i;var nblk=((x.length+8)>>6)+1;var blks=new Array(nblk*16);for(i=0;i<nblk*16;i++) blks[i]=0;
				for(i=0;i<x.length;i++) blks[i>>2]|=x.charCodeAt(i)<<((i%4)*8);
				blks[i>>2]|=0x80<<((i%4)*8);blks[nblk*16-2]=x.length*8;return blks;
			}
			var i,x=sb(""+inputString),a=1732584193,b=-271733879,c=-1732584194,d=271733878,olda,oldb,oldc,oldd;
			for(i=0;i<x.length;i+=16) {olda=a;oldb=b;oldc=c;oldd=d;
				a=ff(a,b,c,d,x[i+ 0], 7, -680876936);d=ff(d,a,b,c,x[i+ 1],12, -389564586);c=ff(c,d,a,b,x[i+ 2],17,  606105819);
				b=ff(b,c,d,a,x[i+ 3],22,-1044525330);a=ff(a,b,c,d,x[i+ 4], 7, -176418897);d=ff(d,a,b,c,x[i+ 5],12, 1200080426);
				c=ff(c,d,a,b,x[i+ 6],17,-1473231341);b=ff(b,c,d,a,x[i+ 7],22,  -45705983);a=ff(a,b,c,d,x[i+ 8], 7, 1770035416);
				d=ff(d,a,b,c,x[i+ 9],12,-1958414417);c=ff(c,d,a,b,x[i+10],17,     -42063);b=ff(b,c,d,a,x[i+11],22,-1990404162);
				a=ff(a,b,c,d,x[i+12], 7, 1804603682);d=ff(d,a,b,c,x[i+13],12,  -40341101);c=ff(c,d,a,b,x[i+14],17,-1502002290);
				b=ff(b,c,d,a,x[i+15],22, 1236535329);a=gg(a,b,c,d,x[i+ 1], 5, -165796510);d=gg(d,a,b,c,x[i+ 6], 9,-1069501632);
				c=gg(c,d,a,b,x[i+11],14,  643717713);b=gg(b,c,d,a,x[i+ 0],20, -373897302);a=gg(a,b,c,d,x[i+ 5], 5, -701558691);
				d=gg(d,a,b,c,x[i+10], 9,   38016083);c=gg(c,d,a,b,x[i+15],14, -660478335);b=gg(b,c,d,a,x[i+ 4],20, -405537848);
				a=gg(a,b,c,d,x[i+ 9], 5,  568446438);d=gg(d,a,b,c,x[i+14], 9,-1019803690);c=gg(c,d,a,b,x[i+ 3],14, -187363961);
				b=gg(b,c,d,a,x[i+ 8],20, 1163531501);a=gg(a,b,c,d,x[i+13], 5,-1444681467);d=gg(d,a,b,c,x[i+ 2], 9,  -51403784);
				c=gg(c,d,a,b,x[i+ 7],14, 1735328473);b=gg(b,c,d,a,x[i+12],20,-1926607734);a=hh(a,b,c,d,x[i+ 5], 4,    -378558);
				d=hh(d,a,b,c,x[i+ 8],11,-2022574463);c=hh(c,d,a,b,x[i+11],16, 1839030562);b=hh(b,c,d,a,x[i+14],23,  -35309556);
				a=hh(a,b,c,d,x[i+ 1], 4,-1530992060);d=hh(d,a,b,c,x[i+ 4],11, 1272893353);c=hh(c,d,a,b,x[i+ 7],16, -155497632);
				b=hh(b,c,d,a,x[i+10],23,-1094730640);a=hh(a,b,c,d,x[i+13], 4,  681279174);d=hh(d,a,b,c,x[i+ 0],11, -358537222);
				c=hh(c,d,a,b,x[i+ 3],16, -722521979);b=hh(b,c,d,a,x[i+ 6],23,   76029189);a=hh(a,b,c,d,x[i+ 9], 4, -640364487);
				d=hh(d,a,b,c,x[i+12],11, -421815835);c=hh(c,d,a,b,x[i+15],16,  530742520);b=hh(b,c,d,a,x[i+ 2],23, -995338651);
				a=ii(a,b,c,d,x[i+ 0], 6, -198630844);d=ii(d,a,b,c,x[i+ 7],10, 1126891415);c=ii(c,d,a,b,x[i+14],15,-1416354905);
				b=ii(b,c,d,a,x[i+ 5],21,  -57434055);a=ii(a,b,c,d,x[i+12], 6, 1700485571);d=ii(d,a,b,c,x[i+ 3],10,-1894986606);
				c=ii(c,d,a,b,x[i+10],15,   -1051523);b=ii(b,c,d,a,x[i+ 1],21,-2054922799);a=ii(a,b,c,d,x[i+ 8], 6, 1873313359);
				d=ii(d,a,b,c,x[i+15],10,  -30611744);c=ii(c,d,a,b,x[i+ 6],15,-1560198380);b=ii(b,c,d,a,x[i+13],21, 1309151649);
				a=ii(a,b,c,d,x[i+ 4], 6, -145523070);d=ii(d,a,b,c,x[i+11],10,-1120210379);c=ii(c,d,a,b,x[i+ 2],15,  718787259);
				b=ii(b,c,d,a,x[i+ 9],21, -343485551);a=ad(a,olda);b=ad(b,oldb);c=ad(c,oldc);d=ad(d,oldd);
			}
			return rh(a)+rh(b)+rh(c)+rh(d);
		}

		(async function(){
		await fetch("get-geofencing-data.php") 
        .then((response) => {
            if(!response.ok){
                throw new Error("Something went wrong!");
            }
            return response.json(); // Parse the JSON data.
        })
        .then((data) => {
                var map = L.map('map').setView([data[0]["LATITUDE"], data[0]["LONGITUDE"]], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '<a href="https://github.com/cyclosm/cyclosm-cartocss-style/releases" title="CyclOSM - Open Bicycle render">CyclOSM</a> | Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
        
                L.control.scale().addTo(map);

				// console.log(Object.keys(data).length);
				for(let i = 0; i < Object.keys(data).length ; i++){
					var marker = L.marker([data[i]["LATITUDE"], data[i]["LONGITUDE"]], {
						title: data[i]["GEO_NAME"],
						color: 'red'
						// fillColor: '#f03',
						// fillOpacity: 0.5
						// radius: data[i]["DISTANCE"]
					}).addTo(map);
					marker.bindPopup(
                        data[i]["GEO_NAME"]+
                        "<br>Lat : "+ data[i]["LATITUDE"]+
                        "<br>Long : "+ data[i]["LONGITUDE"]+
                        "<br>Radius : "+ data[i]["DISTANCE"]+
                        "<br><a href='?_a=920bd41164eb9cdf53b65ce418fa162f&_r="+md5(data[i]['GEO_CODE'])+"'>EDIT</a>"
                        );
					var popup = L.popup();
				}

				var popup = L.popup();

				function onMapClick(e) {
					popup
						.setLatLng(e.latlng)
						.setContent("You clickedz the map at " + e.latlng.toString())
						.openOn(map);
				}

				map.on('click', onMapClick);

				// L.marker([51.505, -0.09], { title: 'Marker 1' }).addTo(map); //debugging
				// L.marker([51.51, -0.1], { title: 'Marker 2' }).addTo(map);//debugging
				// L.marker([51.515, -0.09], { title: 'Marker 3' }).addTo(map);//debugging
				// PinSearch component
				var searchBar = L.control.pinSearch({
					position: 'topright',
					placeholder: 'Search...',
					buttonText: 'Search',
					onSearch: function(query) {
						console.log('Search query:', query);
						// Handle the search query here
					},
					searchBarWidth: '200px',
					searchBarHeight: '30px',
					maxSearchResults: 3
				}).addTo(map);
			})
        .catch((error) => {
            alert(error);
        });

        //Right click on the map activated
        // map.on('contextmenu', function(e) {
        //     alert(e.latlng);
        // });

        })(); //async


        //Right click on the different layers
        // var layers_and_features = map._layers;
        // for (var lay in layers_and_features) {
        //     //Hacky way to get geojson type layer (can work for other vector layer, see Leaflet API, searching keyword contextmenu)
        //     if (layers_and_features[lay].addData) {
        //         layers_and_features[lay].on('contextmenu', function(e) {
        //             alert(e.latlng);
        //         });
        //     }
        // }
          //your code here 
    }
    if($('#mapz').length > 0 ){
        let geocode = $("#EDIT_GEO_CODE").val();
        let geoname = $("#geoname").val();
        let geolat = $("#geolat").val();
        let geolon = $("#geolon").val();
        let georad = $("#georad").val();

        console.log(geocode);
        let mapz = L.map('mapz').setView([geolat,geolon],15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '<a href="https://github.com/cyclosm/cyclosm-cartocss-style/releases" title="CyclOSM - Open Bicycle render">CyclOSM</a> | Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mapz);

        L.control.scale().addTo(mapz);

        var circlez = L.circle([geolat, geolon], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: georad
        }).addTo(mapz);
        circlez.bindPopup(
            geoname+
            "<br>Lat : "+ geolat+
            "<br>Long : "+ geolon+
            "<br>Radius : "+ georad
            );
        var popupz = L.popup();
    
        (async function(){
		await fetch("get-last-present-coords.php?geocode="+geocode+"") 
			.then((response) => {
				if(!response.ok){
					throw new Error("Something went wrong!");
				}
                console.log('true');
				return response.json(); // Parse the JSON data.
			})
			.then((data) => {
				console.log(Object.keys(data).length);
				for(let i = 0; i < Object.keys(data).length ; i++){
					L.marker([data[i]["PPL_LATTITUDE"], data[i]["PPL_LONGITUDE"]]).addTo(mapz);
				}
			})
        .catch((error) => {
            alert(error);
        });

        //Right click on the map activated
        mapz.on('contextmenu', function(e) {
            alert(e.latlng);
        });
        })();
    }
});
</script>
