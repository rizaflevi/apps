<?php 
	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
	}

	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$CRUD=$_GET['_a'];
	$NOW = date("Y-m-d H:i:s");


if($CRUD=='create' ){
}
elseif($CRUD=='update' ){
	mysql_query("update tb_aggn1 set IDENT_NAME='$_POST[EDIT_NAMA_AGN]', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW',
		where APP_CODE='$cFILTER_CODE' and KODE_AGN='$_POST[EDIT_KODE_AGN]'");
	header('location:kop_tb_jns_agunan.php');
}

elseif($CRUD=='delete' ){
}


?>
