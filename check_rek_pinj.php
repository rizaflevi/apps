<?php
//	check_rek_pinj.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$KODE=$_GET['KD_PINJ'];

	$cQUERY="select * from tab_pinj where APP_CODE='$cFILTER_CODE' and KODE_PINJM='$KODE' and DELETOR=''";
//	die ($cQUERY);
	$qQUERY=SYS_QUERY($cQUERY);

	if(SYS_ROWS($qQUERY)==0){
		$cMSG = 'Kode Anggota tidak ada';
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return echo "incorrect";
	}
	return echo "correct";
?>
