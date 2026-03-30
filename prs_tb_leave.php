<?php
//	prs_tb_leave.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE']))  session_start();

	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('PE51','Tabel Jenis Cuti');
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];	
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Cuti.pdf';

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

	$cKODE_CUTI = S_MSG('PE54','Kode');
	$cNAMA_CUTI = S_MSG('PE52','Jenis Cuti');
	$cDAFTAR	= S_MSG('PE53','Daftar Jenis Cuti');
	$cSAVE_DATA	= S_MSG('F301','Save');
	$cCLOSE_DATA= S_MSG('F302','Close');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$can_CREATE = TRUST($cUSERCODE, 'PRS_ABSN_1ADD');
		$qQUERY=OpenTable('TbLeave', "APP_CODE='$cAPP_CODE' and DELETOR=''");
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('create_leave'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_CUTI, $cNAMA_CUTI]);
						echo '<tbody>';
							while($aREC_CUTI_CODE=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								echo '<td style="width: 1px;"></td>';
								echo "<td><span><a href='?_a=".md5('update_leave')."&_c=".md5($aREC_CUTI_CODE['CUTI_CODE'])."'>".$aREC_CUTI_CODE['CUTI_CODE']."</a></span></td>";
								echo "<td><span><a href='?_a=".md5('update_leave')."&_c=".md5($aREC_CUTI_CODE['CUTI_CODE'])."'>".$aREC_CUTI_CODE['CUTI_NAME']."</a></span></td>";
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

	case md5('create_leave'):
		$cADD_NEW	= S_MSG('PE55','Tambah Jenis Cuti');
		DEF_WINDOW($cADD_NEW);
			TFORM($cADD_NEW, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,3], '700', $cKODE_CUTI);
					INPUT('text', [2,2,2,2], '900', 'ADD_JOB_CODE', '', 'autofocus', '', '', 0, '', 'fix');
					LABEL([3,3,3,3], '700', $cNAMA_CUTI);
					INPUT('text', [6,6,6,6], '900', 'ADD_JOB_NAME', '', '', '', '', 0, '', 'fix');
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('update_leave'):
		$NOW = date("Y-m-d H:i:s");
		$can_UPDATE = TRUST($cUSERCODE, 'PRS_TB_LEAV_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'PRS_TB_LEAV_3DEL');
		$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$cEDIT_CUTI	= S_MSG('PE57','Edit Jenis Cuti');

		$qQUERY=OpenTable('TbLeave', "APP_CODE='$cAPP_CODE' and DELETOR='' and md5(CUTI_CODE)='$_GET[_c]'");
		$REC_PRS_CUTI=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_CUTI);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('delete_leave').'&id='. md5($REC_PRS_CUTI['CUTI_CODE']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_CUTI, '?_a=rubah&id='.$REC_PRS_CUTI['CUTI_CODE'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,3], '700', $cKODE_CUTI);
					INPUT('text', [2,2,4,4], '700', 'EDIT_CUTI_CODE', $REC_PRS_CUTI['CUTI_CODE'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,3], '700', $cNAMA_CUTI);
					INPUT('text', [5,5,5,5], '700', 'EDIT_CUTI_NAME', DECODE($REC_PRS_CUTI['CUTI_NAME']), '', '', '', 0, '', 'fix');
					SAVE(($can_UPDATE ? $cSAVE_DATA : ''));
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('delete_leave'):
		$NOW = date("Y-m-d H:i:s");
		$KODE_CRUD=$_GET['id'];
		RecUpdate('TbLeave', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, $NOW], "CUTI_CODE='$KODE_CRUD' and APP_CODE='$cFILTER_CODE'"); 
		header('location:prs_tb_leave.php');
	break;
		
		
	case "rubah":
		$NOW = date("Y-m-d H:i:s");
		$KODE_CRUD=$_GET['id'];
		RecUpdate('TbLeave', ['CUTI_NAME', 'UP_DATE', 'UPD_DATE'], [$_POST['EDIT_CUTI_NAME'], $cUSERCODE, $NOW], "CUTI_CODE='$KODE_CRUD' and APP_CODE='$cFILTER_CODE'"); 
		header('location:prs_tb_leave.php');
	break;
		
	case "tambah":
		$NOW = date("Y-m-d H:i:s");
		if($_POST['ADD_CUTI_CODE']==''){
			MSG_INFO(S_MSG('PE59','Kode Jenis Cuti tidak boleh kosong'));
			return;
		}
		$qQUERY=OpenTable('TbLeave', "CUTI_CODE='$_POST[ADD_CUTI_CODE]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		if(SYS_ROWS($qQUERY)>0){
			MSG_INFO(S_MSG('PE58','Kode Jenis Cuti sudah ada'));
			return;
		} else {
			RecCreate('TbLeave', ['CUTI_CODE', 'CUTI_NAME', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], [$_POST['ADD_CUTI_CODE'], $_POST['ADD_CUTI_NAME'], $cAPP_CODE, $cUSERCODE, $NOW]);
			header('location:prs_tb_leave.php');
		}
}
?>

