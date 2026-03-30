<?php
	include "sysfunction.php";
	include "sqlite/functions.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	
//	header('location:bm_mobile.php?_api=pel&_data=0811526550');
	exit();

	$cDBF_NAME 	= 'bm.db';
	$cKODE_RBM	= '';
	if ($_POST['ADD_KODE_RBM']!='') {
		$cKODE_RBM 	= $_POST['ADD_KODE_RBM'];
	}

	if (!file_exists($cDBF_NAME)) {
//		echo "$cDBF_NAME not found<br>";
		echo "<script> alert('$cDBF_NAME not found');	window.history.back();	</script>";
//	  exit;
	}

//open db 120.215
	opendb($cDBF_NAME);

//some configuration
	$ret = $dbh->exec("PRAGMA case_sensitive_like = 0;");
	$ret = $dbh->exec("PRAGMA encoding = \"UTF-8\";");

	$sql="delete from bm_tb_pel where APP_CODE='$cFILTER_CODE'";
	$sth=db_execute($dbh,$sql,1);
//	$settings=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	$NOW = date("Y-m-d H:i:s");

	$cBM_CATTER = "select * from bm_tb_catter1
		where KODE_CATTER='$_SESSION[gCAT_TER]' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$qBM_CATTER=SYS_QUERY($cBM_CATTER);
	$r_BM_CATTER =SYS_FETCH($qBM_CATTER);
	
	$cBM_TB_PEL = "select * from bm_tb_pel
		left join bm_tb_plg on bm_tb_pel.IDPEL = bm_tb_plg.IDPEL
		where bm_tb_pel.UNITUP='$r_BM_CATTER[KODE_AREA]' and bm_tb_plg.KODE_RUTE='$_POST[ADD_KODE_RBM]' and bm_tb_plg.LAST_VISIT<'$cSTART_DATE' and 
			bm_tb_pel.APP_CODE='$cFILTER_CODE' and bm_tb_pel.DELETOR=''";
	$rBM_TB_PEL=SYS_QUERY($cBM_TB_PEL);
	
	while($r_BM_TB_PEL =SYS_FETCH($rBM_TB_PEL)) {
			$cIDPEL			= encode_string($r_BM_TB_PEL['IDPEL']);	
			$cNAMA_PEL		= encode_string($r_BM_TB_PEL['NAMA_PEL']);	
			$cALAMAT		= encode_string($r_BM_TB_PEL['ALAMAT']);	
			$cUNITUP		= $r_BM_TB_PEL['UNITUP'];	
			$cQUERY="insert into bm_tb_pel set IDPEL='$cIDPEL', NAMA_PEL='$cNAMA_PEL', ALAMAT='$cALAMAT', UNITUP='$cUNITUP', 
				KODE_TARIF='$r_BM_TB_PLG[KODE_TARIF]', NOMOR_KWH='$r_BM_TB_PLG[NOMOR_KWH]', DAYA='$r_BM_TB_PLG[DAYA]', 
				MERK_KWH='$r_BM_TB_PLG[MERK_KWH]', 
				ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
			$sth=db_execute($dbh,$cQUERY,1);
		var_dump($sth);	exit();
	}
	header('location:bm_dashboard.php');
?>
