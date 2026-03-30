<?php
//	tpi_cek_bakul.php
//	cek kode bakul yang dimasukkan di deposit bakul, apakah ada di database

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];

	$KODE=$_GET['KD_BKL'];

	$cQUERY="select BEEDR_CODE, BEEDR_NAME, BEEDR_CELL from tb_beedr where APP_CODE='$cAPP_CODE' and BEEDR_CODE='$KODE' and DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);
	$Rec_BIDDER = SYS_FETCH($qQUERY);

	$cRETURN_DATA = '';
	if(SYS_ROWS($qQUERY)==0){
		echo "";
	} else {
		echo $Rec_BIDDER['BEEDR_NAME'].', '.$Rec_BIDDER['BEEDR_CELL'];
	}
?>
