<?php
//	kop_cek_rek_pinjaman.php
//	cek nomor rekening tabungan, apakah ada di database

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$KODE=$_GET['NO_REK'];

	$cQUERY="select LOAN_ACT, NO_MEMBER, LOAN_CODE from tr_loan1 where APP_CODE='$cFILTER_CODE' and LOAN_ACT='$KODE' and DELETOR=''";
//	die ($cQUERY);
	$qQUERY=SYS_QUERY($cQUERY);
	$Rec_rek_pinjaman = SYS_FETCH($qQUERY);

	$cRETURN_DATA = '';
	if(SYS_ROWS($qQUERY)==0){
		echo "";
	} else {
		$Rec_Member = SYS_FETCH(SYS_QUERY("select KD_MMBR, NM_DEPAN, ALAMAT from tb_member1 where APP_CODE='$cFILTER_CODE' and KD_MMBR='$Rec_rek_pinjaman[NO_MEMBER]' and DELETOR=''"));
		echo $Rec_Member['NM_DEPAN'].', '.$Rec_Member['ALAMAT'];
	}
?>
