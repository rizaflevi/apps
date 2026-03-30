<?php
//	tb_salesman.php
//	TODO delete function

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE']))  session_start();

	$cAPP_CODE = $_SESSION['data_FILTER_CODE']; 
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Salesman.pdf';

	$qQUERY=OpenTable('TbSalesman', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");

	$cHEADER 	= S_MSG('TS03','Tabel Salesman');
	$KD_KLMP	= S_MSG('TS01','Kode');
	$cNM_KLPK	= S_MSG('TS02','Nama Salesman');
	$cDAFTAR	= S_MSG('TS11','Daftar Salesman');
	$cADD_KLPK	= S_MSG('KA11','Tambah');
	$cEDIT_TBL	= S_MSG('TS12','Edit Salesman');
	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'TB_SALESMAN_1ADD');
		$ADD_LOG	= APP_LOG_ADD('View', $cHEADER);
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$KD_KLMP, $cNM_KLPK]);
						echo '<tbody>';
							while($aREC_DISP=SYS_FETCH($qQUERY)) {
								$cHREFF="<a href='?_a=".md5('up__date')."&_g=".md5($aREC_DISP['KODE_SLS'])."'>";
								$aCOL = [$aREC_DISP['KODE_SLS'], DECODE($aREC_DISP['NAMA_SLS'])];
								TDETAIL($aCOL, [], '', [$cHREFF, $cHREFF, '']);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('cr34t3'):
		DEF_WINDOW($cHEADER);
			TFORM($cADD_KLPK, '?_a=tambah');
				TDIV();
					LABEL([3,3,3,6], '700', $KD_KLMP);
					INPUT('text', [2,2,2,6], '900', 'KODE_SLMN', '', 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNM_KLPK);
					INPUT('text', [6,6,6,6], '900', 'NAMA_SLMN', '', '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;
	
	case md5('up__date'):
		$can_UPDATE = TRUST($cUSERCODE, 'TB_SALESMAN_2UPD');	
		$can_DELETE = TRUST($cUSERCODE, 'TB_SALESMAN_3DEL');
		$cKODE_GRUP= $_GET['_g'];
		
		$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');
		$qQUERY=OpenTable('TbSalesman', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and md5(KODE_SLS)='$cKODE_GRUP'");
		$aREC_SLMN=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=DEL_RASIO&_id='. $aREC_SLMN['REC_ID']. '" onClick="return confirm('. "'". $cMSG_DEL. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a='.md5('DEL_SM').'&_id='.$aREC_SLMN['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $KD_KLMP);
					INPUT('text', [2,2,2,6], '900', 'KODE_SLMN', $aREC_SLMN['KODE_SLS'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,6], '700', $cNM_KLPK);
					INPUT('text', [5,5,5,5], '900', 'EDIT_NAMA_RTO1', $aREC_SLMN['NAMA_SLS'], 'focus', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
		break;
	case "tambah":
		$pKODE_SLMN=$_POST['KODE_SLMN'];
		if($pKODE_SLMN=='') {
			MSG_INFO(S_MSG('TS14','Kode Salesman masih kosong'));
			return;
		}
		$qQUERY=OpenTable('TbSalesman', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and KODE_SLS='$_POST[KODE_GRUP]'");
		if(SYS_ROWS($qQUERY)==0){
			RecCreate('TbSalesman', ['KODE_SLS', 'NAMA_SLS', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], [$_POST['KODE_SLMN'], $_POST['NAMA_SLMN'], $cAPP_CODE, $_SESSION['gUSERCODE'], date("Y-m-d H:i:s")]);
			header('location:tb_salesman.php');
		} else {
			MSG_INFO(S_MSG('TS13','Kode Salesman sudah ada'));
			return;
		}
	break;

	case md5('save'):
		$KODE_CRUD=$_GET['_g'];
		RecUpdate('TbSalesman', ['NAMA_SLS'], [$_POST['NM_SLMN']], "APP_CODE='$cAPP_CODE' and md5(KODE_SLS)='".$KODE_CRUD."'");
		header('location:tb_salesman.php?');
	break;

	case md5('DEL_SM'):
		RecSoftDel($_GET['_id']);
		header('location:tb_salesman.php');
	break;
}
?>

