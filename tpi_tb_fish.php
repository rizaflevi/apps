<?php
//	tpi_tb_fish.php //
// Jenis Ikan

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 	= $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/TPI - Tabel - Jenis Ikan.pdf';

	$qQUERY=OpenTable('TbFish', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION=(isset($_GET['_____a']) ? $_GET['_____a'] : '');

	$cHEADER 	= S_MSG('TF01','Tabel Jenis Ikan');
	$cKODE_TBL 	= S_MSG('F003','Kode');
	$cNAMA_TBL 	= S_MSG('F004','Nama');
	$cHRG_IKAN 	= S_MSG('TF04','Hrg. Ikan');
	$cDAFTAR	= S_MSG('TF16','Daftar Jenis Ikan');
	
	$cSAVE_DATA=S_MSG('F301','Save');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
		$can_CREATE = TRUST($cUSERCODE, 'TPI_TB_FISH_1ADD');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_____a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL]);
						echo '<tbody>';
							while($aREC_FISH=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								echo '<td style="width: 1px;"></td>';
								echo "<td><span><a href='?_____a=".md5('upd_fs')."&_I=".md5($aREC_FISH['FISH_CODE'])."'>".$aREC_FISH['FISH_CODE']."</a></span></td>";
								echo "<td><span><a href='?_____a=".md5('upd_fs')."&_I=".md5($aREC_FISH['FISH_CODE'])."'>".decode_string($aREC_FISH['FISH_NAME'])."</a></span></td>";
							echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
	break;

	case md5('cr34t3'):
		$cADD_REC	= S_MSG('TF17','Tambah Jenis Ikan');
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_____a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'ADD_FISH_CODE', '', 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [5,5,5,6], '900', 'ADD_FISH_NAME', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cHRG_IKAN);
					INPUT('number', [2,2,2,6], '900', 'ADD_FISH_PRICE', 0, '', 'fdecimal', 'right', 0, '', 'fix');
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END2WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('upd_fs'):
		$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$cEDIT_TBL	= S_MSG('TF18','Edit Tabel Jenis Ikan');
		$can_UPDATE = TRUST($cUSERCODE, 'TPI_TB_FISH_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'TPI_TB_FISH_3DEL');
		$qQUERY=OpenTable('TbFish', "md5(FISH_CODE)='".$_GET['_I']."' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$REC_FISH	= SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_fish').'&id='. md5($REC_FISH['FISH_CODE']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_____a=rubah&_id='.$REC_FISH['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_FISH_CODE', $REC_FISH['FISH_CODE'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'EDIT_FISH_NAME', DECODE($REC_FISH['FISH_NAME']), 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cHRG_IKAN);
					INPUT('text', [2,2,2,6], '900', 'EDIT_FISH_PRICE', $REC_FISH['FISH_PRICE'], '', 'fdecimal', 'right', 0, '', 'fix');
					SAVE($can_UPDATE==1 ? $cSAVE_DATA : '');
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case 'tambah':
		$NOW = date("Y-m-d H:i:s");
		$cKODE_FISH	= ENCODE($_POST['ADD_FISH_CODE']);	
		if($cKODE_FISH==''){
			$MSG_INFO(S_MSG('TF20','Kode Jenis Ikan belum diisi'));
			return;
		}
		$qQUERY=OpenTable('TbFish', "FISH_CODE='$cKODE_FISH' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$cCEK_KODE=SYS_QUERY($qQUERY);
		if(SYS_ROWS($cCEK_KODE)>0){
			MSG_INFO(S_MSG('TF19','Kode Jenis Ikan sudah ada'));
			return;
		} else {
			RecCreate('TbFish', ['FISH_CODE', 'FISH_NAME', 'FISH_PRICE', 'APP_CODE', 'ENTRY', 'REC_ID'], 
			[$cKODE_FISH, ENCODE($_POST['ADD_FISH_NAME']), $_POST['ADD_FISH_PRICE'], $cAPP_CODE], $cUSERCODE, NowMSecs());
			$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Add : '.$cKODE_FISH);
			header('location:tpi_tb_fish.php');
		}
	break;

	case 'rubah':
		$NOW = date("Y-m-d H:i:s");
		$KODE_CRUD=$_GET['id'];
		RecUpdate('TbFish', ['FISH_NAME', 'FISH_PRICE'], [ENCODE($_POST['EDIT_FISH_NAME']), $_POST['EDIT_FISH_PRICE']], "REC_ID='$KODE_CRUD'");
		APP_LOG_ADD($cHEADER, 'update : '.$KODE_CRUD);
		header('location:tpi_tb_fish.php');
	break;

	case md5('del_fish'):
		$NOW = date("Y-m-d H:i:s");
		$KODE_CRUD=$_GET['id'];
		RecSoftDel($KODE_CRUD);
		APP_LOG_ADD($cHEADER, 'delete : '.$KODE_CRUD);
		header('location:tpi_tb_fish.php');
}
?>

