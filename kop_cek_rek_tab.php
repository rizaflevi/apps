<?php
//	kop_cek_rek_tab.php
//	cek nomor rekening tabungan, apakah ada di database

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$KODE=$_GET['NO_REK'];

	$cQUERY="select SAVE_ACT, KD_MMBR, SAVE_CODE from tr_save1 where APP_CODE='$cFILTER_CODE' and SAVE_ACT='$KODE' and DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);
	$Rec_rek_tabungan = SYS_FETCH($qQUERY);

	$cRETURN_DATA = '';
	if(SYS_ROWS($qQUERY)==0){
		echo "";
	} else {
		$Rec_Member = SYS_FETCH(SYS_QUERY("select KD_MMBR, NM_DEPAN, ALAMAT from tb_member1 where APP_CODE='$cFILTER_CODE' and KD_MMBR='$Rec_rek_tabungan[KD_MMBR]' and DELETOR=''"));
		echo $Rec_Member['NM_DEPAN'].', '.$Rec_Member['ALAMAT'];
	}
?>
