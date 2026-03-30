<?php
require_once "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE = $_SESSION['data_FILTER_CODE'];

$sth=OpenTable('TbGeofence', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'DISTANCE limit 500');

// $conn = mysqli_connect("103.56.148.108", "rifan", "YazaPratama@23B", "riza_db");

// $sth = mysqli_query($conn, "SELECT GEO_CODE, GEO_NAME, LATITUDE, LONGITUDE, DISTANCE, GEO_NOTE FROM tb_geofence WHERE APP_CODE = '$cAPP_CODE' ORDER BY DISTANCE ASC LIMIT 500");
$rows = array();
while($r = mysqli_fetch_assoc($sth)) {
    $rows[] = $r;
}

echo json_encode($rows);