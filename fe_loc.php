<?php
// fe_loc.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];

	$cACTION='';	$cLAT='';	$cLON='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];
    switch($cACTION){
		case 'LOC':
			if (isset($_GET['_la'])) $cLAT=$_GET['_la'];
			if (isset($_GET['_lo'])) $cLON=$_GET['_lo'];
			
			print_r2('<div>Lat: '.$cLAT.'<br> Long: '.$cLON.'</div>');
			break;
		case 'DEV':
			break;
		default:
			break;
	}
?>
