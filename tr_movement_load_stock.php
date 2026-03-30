<?php
// tr_movement_load_stock.php

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
  	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$sPERIOD1 = $_SESSION['sCURRENT_PERIOD'];
	$cGUDANG = $_POST['_g'];
	$Y=substr($sPERIOD1, 0, 4);
	$M=substr($sPERIOD1, 5, 2);
	$cSQLCOMMAND = "select A.*, B.* from invent A ";
	$cSQLCOMMAND .= " inner join (select * from stock where APP_CODE='$cAPP_CODE'  and DELETOR='' and ";
	$cSQLCOMMAND .= " STOK_YEAR=$Y and STOK_MONTH=$M and KODE_GDG='$cGUDANG' and STOCK_CTN>' ') B ON A.KODE_BRG=B.KODE_BRG";
	$cSQLCOMMAND .= " where A.APP_CODE='$cAPP_CODE'  and A.DELETOR='' group by A.KODE_BRG order by A.GROUP_INV";
//	var_dump($cSQLCOMMAND); exit;
	$q_INVENTORY=SYS_QUERY($cSQLCOMMAND);
	while($aINVENT=SYS_FETCH($q_INVENTORY)) {
		echo "<option value='$aINVENT[KODE_BRG]'  >$aINVENT[NAMA_BRG]</option>";
	}
	return;
?>
