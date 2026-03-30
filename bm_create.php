<?php
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cNOMOR_MTR 	= $_SESSION['SNMR_METER'];
	$nKONDISI_RMH 	= $_POST['ADD_KONDISI_RMH'];

	$cSEGEL_TEL 	= '';
	if (isset($_POST['ADD_SEGEL_TEL'])) {
		$cSEGEL_TEL 	= $_POST['ADD_SEGEL_TEL'];
	}
	$nSEGEL_TEL 	= 0;
	if ($cSEGEL_TEL=='') {
		$nSEGEL_TEL 	= 1;
	}

	$cSEGEL_TER 	= '';
	if (isset($_POST['ADD_SEGEL_TER'])) {
		$cSEGEL_TER 	= $_POST['ADD_SEGEL_TER'];
	}
	$nSEGEL_TER 	= 0;
	if ($cSEGEL_TER=='') {
		$nSEGEL_TER 	= 1;
	}

	$cLAMPU_INDI	= '';
	if (isset($_POST['ADD_LAMPU_INDI'])) {
		$cLAMPU_INDI 	= $_POST['ADD_LAMPU_INDI'];
	}
	$nLAMPU_INDI 	= 0;
	if ($cLAMPU_INDI=='') {
		$nLAMPU_INDI 	= 1;
	}

	$cNOTE 			= encode_string($_POST['ADD_NOTE']);
	$c_PARA = "_api=updata&_data=0811526550&_idpelanggan=".$_SESSION['SKODE_PLGN']."&_rbm=".$_POST['ADD_KODE_RBM']."&_token=".$_POST['ADD_SISA_TOKEN']."&_rumah=".$nKONDISI_RMH."&_sgl1=".$nSEGEL_TEL."&_sgl2=".$nSEGEL_TER."&_indikator=".$nLAMPU_INDI."&_waktu=04/01/2017 03:16:00&_latt=-6.280000&_long=106.989000&_note=test&_alamat=".$cNOTE;
//	var_dump($c_PARA); die; exit();

	header('location:bm_mobile.php?'.$c_PARA);

	
/*	
	$c_TB_USER="select * from ".$database1.".tb_user where DELETOR='' and USER_CODE='$_SESSION[gUSERCODE]'";
	$q_TB_USER=SYS_QUERY($c_TB_USER);
	$a_TB_USER=SYS_FETCH($q_TB_USER);
	$NOW = date("Y-m-d H:i:s");
	$LVS = date("Y-m-d");

	$cQUERY="insert into bm_dt_baca set TGL_BACA='$NOW', IDPEL='$_SESSION[SKODE_PLGN]', 
		SISA_TOKEN='$_POST[ADD_SISA_TOKEN]', KODE_RBM='$_POST[ADD_KODE_RBM]', NO_URUT='$nNOMOR_URUT', KONDISI_RMH='$nKONDISI_RMH', SEGEL_TLG='$nSEGEL_TEL', SEGEL_TER='$nSEGEL_TER', LAMPU_INDI='$nLAMPU_INDI', NOTE='$cNOTE', 
		LATTI='$a_TB_USER[LATTITUDE]', LONGI='$a_TB_USER[LONGITUDE]', PETUGAS='$_SESSION[gCAT_TER]',
		ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
	SYS_QUERY($cQUERY);

	$cTB_PEL	= "update bm_tb_plg set PETUGAS='$_SESSION[gCAT_TER]', KODE_RUTE='$_POST[ADD_KODE_RBM]', NO_URUT='$nNOMOR_URUT', LAST_VISIT='$LVS', UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW' 
		where APP_CODE='$cFILTER_CODE' and DELETOR='' and IDPEL='$_SESSION[SKODE_PLGN]'";
	SYS_QUERY($cTB_PEL);
*/
	header('location:bm_meter.php');
?>
