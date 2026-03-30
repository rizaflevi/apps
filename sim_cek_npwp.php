<?php
//	sim_cek_npwp.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$KODE=$_GET['ADD_NPWPD_NO'];

	$cQUERY="select NPWPD_NO, NPWPD_NAME, NP_ADD1, APP_CODE, DELETOR from npwpd where APP_CODE='$cFILTER_CODE' and KD_MMBR='$KODE' and DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);

	$cRETURN_DATA = '';
	if(SYS_ROWS($qQUERY)==0){
		$cMSG = S_MSG('SK97','Nomor NPWP tidak ditemukan');
		echo "";
	} else {
		$row = SYS_FETCH($qQUERY);
		echo $row['NPWPD_NAME'].', '.$row['NP_ADD1'];
	}
	echo $cRETURN_DATA;
?>
