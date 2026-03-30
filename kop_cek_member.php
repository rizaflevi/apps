<?php
//	kop_cek_member.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$KODE=$_GET['ADD_KD_MMBR'];

	$cQUERY="select KD_MMBR, NM_DEPAN, ALAMAT from tb_member1 where APP_CODE='$cFILTER_CODE' and KD_MMBR='$KODE' and DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);

	$cRETURN_DATA = '';
	if(SYS_ROWS($qQUERY)==0){
		$cMSG = 'Kode Anggota tidak ada';
		echo "";
	} else {
		$row = SYS_FETCH($qQUERY);
		echo $row['NM_DEPAN'].', '.$row['ALAMAT'];
	}
	echo $cRETURN_DATA;
?>
