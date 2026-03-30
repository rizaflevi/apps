<?php
//	tr_sales_cek_qty.php
//	cek jumlah barang, convert to stock format

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];

	$KODE=$_GET['_QTY'];
	$cTHN = substr($sPERIOD1,0,4);
	$cBLN = substr($sPERIOD1,5,2);

	$qQUERY=OpenTable('Q_Stock', "ST_YEAR='$cTHN' and ST_MONTH='$cBLN' and APP_CODE='$cAPP_CODE' and INV_CODE='$KODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$Rec_STOCK = SYS_FETCH($qQUERY);

	if(SYS_ROWS($qQUERY)==0) echo "";
	else	echo $Rec_STOCK['CUR_STOCK'].', '.$Rec_STOCK['INV_CODE'];
?>
