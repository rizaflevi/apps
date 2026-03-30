<?php
//	htl_tb_currency.php //
// 	Tabel mata uang

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Area.pdf';

	$qQUERY=OpenTable('TbCurrency', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

	$cHEADER 	= S_MSG('HT20','Currency Table');
	$cKODE_TBL 	= S_MSG('HT21','Currency Code');
	$cNAMA_TBL 	= S_MSG('HT22','Currency Desc');
	$cCURR_RATE = S_MSG('HT23','Last Rate');
	$cCURR_SIGN = S_MSG('HT24','Currency Sign');
	$cCURR_COUNTRY = S_MSG('HT25','Country');
	$cDAFTAR	= S_MSG('HT28','Daftar Currency');
	$cADD_REC	= S_MSG('HT35','Tambah Currency');
	$cMSG_EXIS	= S_MSG('HT37','Kode Currency sudah ada');
	$cMSG_BLANK	= S_MSG('HT38','Kode Currency belum diisi');
	$cSAVE_DATA		= S_MSG('F301','Save');

	$cHDR_BACK_CLR = S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray');
	$can_CREATE = TRUST($cUSERCODE, 'HT_CURR_1ADD');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER);
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL]);
						echo '<tbody>';
							while($aREC_HT_CURR=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								echo '<td style="width: 1px;"></td>';
								echo "<td><span><a href='?_a=".md5('upd_curr')."&_c=".md5($aREC_HT_CURR['CURR_CODE'])."'>".$aREC_HT_CURR['CURR_CODE']."</a></span></td>";
								echo "<td><span><a href='?_a=".md5('upd_curr')."&_c=".md5($aREC_HT_CURR['CURR_CODE'])."'>".$aREC_HT_CURR['CURR_DESC']."</a></span></td>";
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
		$cADD_REC		= S_MSG('HT35','Add Currency');
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,3], '900', 'ADD_CURR_CODE', '', 'focus', '', '', 0, '', 'fix', S_MSG('HT26','Untuk memasukkan Currency baru, klik dan masukkan kode currency disini'));
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [4,4,4,6], '900', 'ADD_CURR_DESC', '', '', '', '', 0, '', 'fix', S_MSG('HT27','klik dan masukkan nama currency disini'));
					LABEL([3,3,3,6], '700', $cCURR_RATE);
					INPUT('number', [2,2,2,6], '900', 'ADD_DEF_RATE', '', '', '', '', 0, '', 'fix', S_MSG('HT2B','klik dan masukkan rate terakhir yang digunakan'));
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
	break;

	case md5('upd_curr'):
		$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$cEDIT_TBL	= S_MSG('HT36','Edit Tabel Currency');
		$can_DELETE = TRUST($cUSERCODE, 'HT_CURR_3DEL');
		$qQUERY=OpenTable('TbCurrency', "md5(CURR_CODE)='$_GET[_c]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$REC_HT_CURR=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE ? ['<a href="?_a=delete&id='. $REC_HT_CURR['CURR_CODE']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&id='.$REC_HT_CURR['CURR_CODE'], $aACT, $cHELP_FILE);
				TDIV();
				LABEL([3,3,3,6], '700', $cKODE_TBL);
				INPUT('text', [2,2,2,6], '900', 'EDIT_CURR_CODE', $REC_HT_CURR['CURR_CODE'], '', '', '', 0, 'disabled', 'fix', S_MSG('HT2A','Kode mata uang pada mode edit di tampilkan saja, tidak bisa di rubah.'));
				LABEL([3,3,3,6], '700', $cNAMA_TBL);
				INPUT('text', [4,4,4,6], '900', 'EDIT_CURR_DESC', $REC_HT_CURR['CURR_DESC'], 'focus', '', '', 0, '', 'fix', S_MSG('HT27','klik dan masukkan nama currency disini'));
				LABEL([3,3,3,6], '700', $cNAMA_TBL);
				INPUT('number', [2,2,2,6], '900', 'EDIT_DEF_RATE', $REC_HT_CURR['DEF_RATE'], 'focus', '', '', 0, '', 'fix', S_MSG('HT2B','klik dan masukkan rate terakhir yang digunakan'));
				SAVE($cSAVE_DATA);
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		APP_LOG_ADD( $cHEADER, 'view');
		SYS_DB_CLOSE($DB2);
	break;

case 'tambah':
	$NOW = date("Y-m-d H:i:s");
	$cCURR_CODE = ENCODE($_POST['ADD_CURR_CODE']);
	if($cCURR_CODE==''){
		MSG_INFO($cMSG_BLANK);
		return;
	}
	$cQUERY="select * from ht_curr where APP_CODE='$cAPP_CODE' and DELETOR='' and CURR_CODE='$cCURR_CODE'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		MSG_INFO($cMSG_EXIS);
		return;
	} else {
		$cCURR_DESC = ENCODE($_POST['ADD_CURR_DESC']);
		$cQUERY ="insert into ht_curr set CURR_CODE='$cCURR_CODE', CURR_DESC='$cCURR_DESC'";
		$cQUERY.=", DEF_RATE=".str_replace(',', '', $_POST['ADD_DEF_RATE']). ", CURR_SIGN='$_POST[ADD_CURR_SIGN]'";
		$cQUERY.=", ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		echo $cQUERY;
		exit();
		SYS_QUERY($cQUERY);
		header('location:htl_tb_currency.php');
	}
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update ht_curr set CURR_DESC='$_POST[EDIT_CURR_DESC]'";
	$cQUERY.=", DEF_RATE=".str_replace(',', '', $_POST['EDIT_DEF_RATE']). ", CURR_SIGN='$_POST[EDIT_CURR_SIGN]', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cAPP_CODE' and CURR_CODE='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:htl_tb_currency.php');
	break;

case 'delete':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update ht_curr set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cAPP_CODE' and CURR_CODE='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	header('location:htl_tb_currency.php');
}
?>

