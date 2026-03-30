<?php
//	kop_tb_produk.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHEADER 	= S_MSG('KK51','Tabel Produk');
	$cHELP_FILE = 'Doc/Tabel - Produk.pdf';
	$cTTIP_PROD	= S_MSG('KK5H','Nama produk keuangan sebagai keterangan');
	$ADD_LOG	= APP_LOG_ADD();

	$qQUERY=OpenTable('KopProduct', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cMESSAG1	= S_MSG('F021','Benar data ini mau di hapus ?');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');
	$cKODE_TBL = S_MSG('KK52','Kode Produk');
	$cNAMA_TBL = S_MSG('KK53','Nama Produk');
	$can_CREATE = TRUST($cUSERCODE, 'TB_PRODUCT_1ADD');

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');
switch($cACTION){
	default:
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL]);
						echo '<tbody>';
							while($aREC_TAB_JASA=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								echo '<td style="width: 1px;"></td>';
								echo "<td><span><a href='?_a=".md5('UPD_JASA')."&_p=".md5($aREC_TAB_JASA['KODE_PENDJ'])."'>".$aREC_TAB_JASA['KODE_PENDJ']."</a></span></td>";
								echo "<td><span><a href='?_a=".md5('UPD_JASA')."&_p=".md5($aREC_TAB_JASA['KODE_PENDJ'])."'>".$aREC_TAB_JASA['NAMA_PENDJ']."</a></span></td>";
							echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
	break;

	case md5('cr34t3'):
		$cADD_REC		= S_MSG('KK57','Tambah Produk');
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,3], '900', 'ADD_ROOM_NO', '', 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'ADD_NAMA_PENDJ', '', '', '', '', 0, '', 'fix');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
	break;

	case md5('UPD_JASA'):
		$cHAPUS	= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$cEDIT_TBL	= S_MSG('KK54','Edit Tabel Produk');
		$can_DELETE = TRUST($cUSERCODE, 'TB_PRODUCT_3DEL');
		$cREC = $_GET['_p'];
		$qQUERY=OpenTable('KopProduct', "md5(KODE_PENDJ)='$cREC' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qQUERY)==0)	header('location:kop_tb_produk.php');
		$REC_TAB_JASA=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE ? ['<a href="?_a='.md5('DELETE_PRODUK').'&_p='. $REC_TAB_JASA['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&id='.$REC_TAB_JASA['KODE_PENDJ'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_KODE_PENDJ', $REC_TAB_JASA['KODE_PENDJ'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [4,4,4,6], '900', 'NM_ROOM', $REC_TAB_JASA['NAMA_PENDJ'], 'focus', '', '', 0, '', 'fix', $cTTIP_PROD);
					SAVE($cSAVE);
				TDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		APP_LOG_ADD( $cHEADER, 'view');
		SYS_DB_CLOSE($DB2);
	break;

case 'tambah':
	if($_POST['ADD_KODE_PENDJ']==''){
		echo "<tr> <td colspan='2'>**Kode produk masih kosong**</td> </tr>";
		header('location:kop_tb_produk.php');
//		return;
	}
	$cQUERY="select * from tab_jasa where KODE_PENDJ='$_POST[ADD_KODE_PENDJ]'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		header('location:kop_tb_produk.php');
	} else {
		SYS_QUERY("insert into tab_jasa set KODE_PENDJ='$_POST[ADD_KODE_PENDJ]', NAMA_PENDJ='$_POST[ADD_NAMA_PENDJ]', 
			APP_CODE='$_SESSION[data_FILTER_CODE]', ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW'");
		header('location:kop_tb_produk.php');
	}
	break;

case 'rubah':
	$cQUERY="update tab_jasa set NAMA_PENDJ='$_POST[EDIT_NAMA_PENDJ]', UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where KODE_PENDJ='$_GET[id]'";
	$qQUERY=SYS_QUERY($cQUERY);
	header('location:kop_tb_produk.php');
	break;

case md5('DELETE_PRODUK'):
	$NOW = date("Y-m-d H:i:s");
	$cQUERY="update tab_jasa set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' 
		where REC_NO='$_GET[_p]'";
	$qQUERY=SYS_QUERY($cQUERY);	// or die ('Error in query.' .mysql_error().'==>'.$cQUERY);
	header('location:kop_tb_produk.php');
}
?>
