<?php

    $_SESSION['cHOST_DB2'] = 'riza_db';
    include "sysfunction.php";
//    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

    $cAPP_CODE  = $_SESSION['data_FILTER_CODE'] = $_GET['_app'];
	$cPRS_CODE  = $_SESSION['gUSERCODE'] = $_GET['_prs'];
	$cDEVICE    = $_GET['_dev'];
	if ($cAPP_CODE=='') return;
	$cIMG_FOLDER = S_PARA('FTP_PERSON_IMG', '../www/images/person');
	$cIMG_FOLDER = 'https://www.fahlevi.co/images/person/';
	$cIMG_FILE = $cIMG_FOLDER.$cAPP_CODE.'_PRS_FOTO_'.$cPRS_CODE.'.jpg';
	if(!REMOTE_FILE_EXISTS($cIMG_FILE))	$cIMG_FILE = "data/images/demo.jpg";
    $cPARA      = $_GET['_para'];
    $nMODE=0;
    switch($cPARA){
        default:
            $cHEADER    = 'Absen Masuk';    
            $cBUTTON    = 'MASUK';    
            $cBTN_CLR   = 'primary';    
            break;
        case 'ABSEN_OUT':
            $cHEADER    = 'Absen Pulang';   
            $cBUTTON    = 'PULANG';
            $cBTN_CLR   = 'success';    
            $nMODE=1;
            break;
        case 'OVERTIME_IN':
            $cHEADER    = 'Lembur Masuk';   
            $cBUTTON    = 'MULAI';
            $cBTN_CLR   = 'warning';    
            $nMODE=2;
            break;
        case 'OVERTIME_OUT':
            $cHEADER    = 'Lembur Selesai';
            $cBUTTON    = 'SELESAI';
            $cBTN_CLR   = 'danger';
            $nMODE=3;
            break;
    }
    $cNAME  = decode_string($_GET['_name']);
    $cJOB   = decode_string($_GET['_job']);
    $cLOC   = $_GET['_loc'];
    $cMPARA = 'fe_absen_in.php?_a=SAVE_ABSEN&_dev='.$cDEVICE.'&_prs='.$cPRS_CODE. '&_app='.$cAPP_CODE. '&_para='.$cPARA. '&_job='.$cJOB. '&_loc='.$cLOC. '&_name='.$cNAME;

    $cACTION='';
    $cACTION= (isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');
    switch($cACTION){
        default:
?>

<!DOCTYPE html>
<html translate="no" class=" ">

<?php
	require_once("cl_header.php");
//	require_once("fe_topbar.php");
?>
<style>
    p{
        color: black;
    }
</style>

<script>
$(function() {
    if (navigator.geolocation) {        // test whether device supports geolocation!
        navigator.geolocation.watchPosition (gotLocation, errLocation, {maximumAge: 5000,enableHighAccuracy: true}); //tiap 5 detik refresh
    } else {
        $('#tekslok').text("Geolocation is not supported by this browser.");
    }

    function gotLocation (position) {
        
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        var accuracy = position.coords.accuracy;
        var timestamp = position.timestamp;
        $('#LATSUBMIT').val(latitude);
        $('#LONSUBMIT').val(longitude);

        $('#tekslok').text(
            "Latitude: " + latitude +
            ", Longitude: " + longitude +
            ", accuracy: " + accuracy +
            ", timestamp: " + timestamp
        );
        let x = $('#lat_loc1').text();
        let y = $('#lon_loc1').text();

        let distance1 = calcCrow(latitude, longitude, x, y).toFixed(1);
        let radius1 = $('#rad_loc1').text();
        if (distance1 < radius1) {
            $('#home_icon1').css('color','green');
            $("#tmbl_submit").prop('disabled', false);
        } else {
            $('#home_icon1').css('color','red');
            $("#tmbl_submit").prop('disabled', true);
        }
        
        $('#dis_loc1').text(distance1);
    }

    function errLocation (error) {
        var errCode = error.code;
        var errMess = error.message;
    }

    //This function takes in latitude and longitude of two location and returns the distance 
    //between them as the crow flies (in km)
    function calcCrow(lat1, lon1, lat2, lon2) 
    {
      var R = 6371; // Radius bumi dalam km
      var dLat = toRad(lat2-lat1);
      var dLon = toRad(lon2-lon1);
      var lat1 = toRad(lat1);
      var lat2 = toRad(lat2);

      var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
      var d = R * c * 1000;
      return d;
    }

    // Converts numeric degrees to radians
    function toRad(Value) 
    {
        return Value * Math.PI / 180;
    }

});
</script>
    <body>
		<div class="page-container row-fluid ">
			<section>
				<div class="profile-image col-md-6 col-sm-6 col-xs-3">
					<img src="data/images/YAZA2.jpg" class="img-responsive img-circle">
				</div>
				<div class="pull-center">
					<h1 class="title"><?php echo $cHEADER?></h1>                            
				</div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <section class="box">
                        <h3><?php echo $cCOMPANY= S_PARA('CO1', '')?></h3>
                        <h5><?php echo $cCOMPANY= S_PARA('CO2', '')?></h5>
                        <h4><?php echo $cCOMPANY= S_PARA('CO3', '')?></h4>
                    </section>
                    <section>
                        <!-- submit -->
                        <form action ="<?php echo $cMPARA?>" method="post">
                            <input type="hidden" class="col-xs-1" name='DEV_ID' id="testlok" value="<?php echo '**'?>">
                            <input type="hidden" name="latitude" id="LATSUBMIT">
                            <input type="hidden" name="longitude" id="LONSUBMIT">
                            <input disabled type="submit" id="tmbl_submit" class="col-xs-5 btn btn-<?php echo $cBTN_CLR?> btn-corner" value=<?php echo $cBUTTON?>>
                            <div class="col-xs-2"></div>
                            <input type="button" class="col-xs-5 btn btn-purple btn-corner" value=CANCEL onclick=self.history.back()>
                        </form>
                    </section>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <section class="box nobox">
                        <div class="content-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="<?php echo 'wid-uprofile bg-'.$cBTN_CLR;?>">

                                        <div class="uprofile-image">
                                            <img src=<?php echo $cIMG_FILE?> class="img-responsive">
                                        </div>
                                        <div class="uprofile-name">
                                            <h3>
                                                <a href="#"><?php echo $cNAME?></a>
                                            </h3>
                                            <p class="uprofile-title"><?php echo $cJOB?></p>
                                        </div>
                                        <div class="uprofile-info">
                                            <ul class="list-unstyled">
                                                <p id="tekslok">your current location:</p>
                                                <?php
                                                    echo '<h4><li>Lokasi Absen :</li></h4>';
                                                    $aGEO_CODE = [];
                                                    $aLAT_AVAILABLE = [];
                                                    $aLON_AVAILABLE = [];
                                                    $aDIS_AVAILABLE = [];
                                                    $qGEO=OpenTable('PrsLocNameGeo', "A.LOCS_CODE='$cLOC' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
                                                    $i=0;
                                                    while($aGEO=SYS_FETCH($qGEO)) {
                                                        $i++;
                                                        $cCLR='red';
                                                        array_push($aGEO_CODE, $aGEO['GEO_CODE']);
                                                        array_push($aLAT_AVAILABLE, $aGEO['LATITUDE']);
                                                        array_push($aLON_AVAILABLE, $aGEO['LONGITUDE']);
                                                        array_push($aDIS_AVAILABLE, $aGEO['DISTANCE']);
                                                        echo '<h4>
                                                            <li>
                                                                <i class="fa fa-home" id="home_icon'.$i.'" style="color:'.$cCLR.';width:30px;"></i>'. 
                                                                $aGEO['GEO_NAME']. ' - 
                                                                Lat : <span id="lat_loc'.$i.'">'. $aGEO['LATITUDE']. '</span> - 
                                                                Lon : <span id="lon_loc'.$i.'">'. $aGEO['LONGITUDE'].'</span> -
                                                                Radius : <span id="rad_loc'.$i.'">'. $aGEO['DISTANCE'].'</span> -
                                                                Distance from your location : <span id="dis_loc'.$i.'">0</span> Meter
                                                            </li>
                                                        </h4>';
                                                    }
                                                    $qADD=OpenTable('PplGeoAdd', "A.PEOPLE_ID='$cPRS_CODE' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
                                                    while($aADD=SYS_FETCH($qADD)) {
                                                        $cCLR='red';
                                                        $cLOC=$aADD['GEO_CODE'];
                                                        $qGEO_ADD=OpenTable('PrsLocNameGeo', "A.GEO_CODE='$cLOC' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
                                                        if($aGEO_ADD=SYS_FETCH($qGEO_ADD)) {
                                                            array_push($aGEO_CODE, $aGEO_ADD['GEO_CODE']);
                                                            array_push($aLAT_AVAILABLE, $aGEO_ADD['LATITUDE']);
                                                            array_push($aLON_AVAILABLE, $aGEO_ADD['LONGITUDE']);
                                                            array_push($aDIS_AVAILABLE, $aGEO_ADD['DISTANCE']);
                                                            echo '<h4><li><i class="fa fa-home" style="color:'.$cCLR.';width:30px;"></i>'. $aGEO_ADD['GEO_NAME'].'</li></h4>';
                                                        }
                                                    }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="clearfix"></div>
            </section>
		</div>
    </body>
</html>

<?php
        break;
    case 'SAVE_ABSEN':
        $qGEO=OpenTable('PrsLocNameGeo', "A.LOCS_CODE='$cLOC' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
        while($aGEO=SYS_FETCH($qGEO)) {
        }
        $nLAT=$_POST['latitude'];
        $nLON=$_POST['longitude'];
        $cDEV=$_POST['DEV_ID'];
        $dTODAY = date('Y-m-d');
        switch($nMODE){
            case 0:
                RecCreate('Presence', ['PEOPLE_CODE', 'PPL_PRESENT', 'PRESENT_CODE', 'PPL_LATTITUDE', 'PPL_LONGITUDE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY', 'UPD_DATE'],
                    [$cPRS_CODE, date("Y-m-d H:m:s"), $nMODE, $nLAT, $nLON, $cAPP_CODE, $cDEVICE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]);
                break;
            case 1:
                RecCreate('Presence', ['PEOPLE_CODE', 'PPL_PRESENT', 'PRESENT_CODE', 'PPL_LATTITUDE', 'PPL_LONGITUDE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY', 'UPD_DATE'],
                    [$cPRS_CODE, date("Y-m-d H:m:s"), $nMODE, $nLAT, $nLON, $cAPP_CODE, $cDEVICE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]);
                break;
            case 2:
                $qOVERTIME	= OpenTable('PrsOvertime', "PRSON_CODE='$cPRS_CODE' and APP_CODE='$cAPP_CODE' and date(OVT_START)='$dTODAY'");
                if(SYS_ROWS($qOVERTIME)==0) {
                    RecCreate('PrsOvertime', ['PRSON_CODE', 'OVT_START', 'APP_CODE', 'ENTRY', 'DATE_ENTRY', 'UPD_DATE'],
                        [$cPRS_CODE, date("Y-m-d H:m:s"), $cAPP_CODE, $cDEVICE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]);
                } else {
                    RecUpdate('PrsOvertime', ['OVT_START'], [date("Y-m-d H:m:s")], 
                        "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPRS_CODE' and date(OVT_START)='$dTODAY'");
                }
                break;
            case 3:
                $dSTART=$tNOW=strtotime(date('Y-m-d H:m:s'));
                $dNOW=date('Y-m-d H:m:s');
                $nJAM_LBR=0;
                $qOVERTIME	= OpenTable('PrsOvertime', "PRSON_CODE='$cPRS_CODE' and APP_CODE='$cAPP_CODE' and date(OVT_START)='$dTODAY'");
                if($aOVERTIME=SYS_FETCH($qOVERTIME)) {
                    $dSTART=strtotime(date($aOVERTIME['OVT_START']));
                    $nJAM_LBR=intval(($tNOW - $dSTART)/60);
                    RecUpdate('PrsOvertime', ['OVT_END', 'OVT_MINUTE'], [$dNOW, $nJAM_LBR], 
                        "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPRS_CODE' and date(OVT_START)='$dTODAY'");
                } else {
                    $dSTART=$aOVERTIME['OVT_START'];
                    $nJAM_LBR=intval(($dNOW - $dSTART)/60);
                    RecCreate('PrsOvertime', ['PRSON_CODE', 'OVT_END', 'OVT_MINUTE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'],
                        [$cPRS_CODE, $dNOW, $nJAM_LBR, $cAPP_CODE, $cDEVICE, date("Y-m-d H:i:s")]);
                }
                break;
        }
        $cMPARA = 'fe_absen_in.php?_a=DONE&_dev='.$cDEVICE.'&_prs='.$cPRS_CODE. '&_app='.$cAPP_CODE. '&_para='.$cPARA. '&_job='.$cJOB. '&_loc='.$cLOC. '&_name='.$cNAME;
		header('location:'.$cMPARA);
        break;
	case 'DONE':
//        $cMPARA = 'https://staff.fahlevi.co/fe_absen_in.php?_dev='.$cDEVICE;
		require_once("scr_header.php");	 require_once("fe_topbar.php");
		TFORM($cHEADER, '', [], '');
        TDIV();
?>
        <div class="alert alert-success alert-dismissible fade in">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <strong>OK</strong>
        </div>
        <p>
            <button type="button" class="btn btn-primary" onclick=window.history.go(-2)>Close</button>
        </p>
<?php
        eTDIV();
		eTFORM('*');
		require_once("js_framework.php");
        break;
    }
?>
