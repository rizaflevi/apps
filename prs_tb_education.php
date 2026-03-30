<?php
//	prs_tb_education.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];	
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Pendidikan.pdf';
	$cHEADER = S_MSG('PB00','Tabel Pendidikan');

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

	$cKODE_TBL 	= S_MSG('PB01','Kode Pendidikan');
	$cNAMA_TBL 	= S_MSG('PB02','Nama Pendidikan');
	$cSAVE=S_MSG('F301','Save');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
		$can_CREATE = TRUST($cUSERCODE, 'TB_EDUCATION_1ADD');
		$qQUERY=OpenTable('TbEducation');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('CREATE_EDU'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
					THEAD([$cKODE_TBL, $cNAMA_TBL]);
						echo '<tbody>';
							while($aREC_PERSONE=SYS_FETCH($qQUERY)) {
								$cHREFF="<a href='?_a=".md5('upd_edu')."&_e=".md5($aREC_PERSONE['EDU_CODE'])."'>";
								TDETAIL([$aREC_PERSONE['EDU_CODE'], DECODE($aREC_PERSONE['EDU_NAME'])], [], '', [$cHREFF, $cHREFF]);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		APP_LOG_ADD( $cHEADER, 'prs_tb_education.php:'.$cAPP_CODE);
		SYS_DB_CLOSE($DB2);
		break;

	case md5('CREATE_EDU'):
		$cADD_REC	= S_MSG('PB11','Tambah Pendidikan');
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,2], '900', 'ADD_EDU_CODE', '', 'focus', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'ADD_EDU_NAME', '', '', '', '', 0, '', 'fix');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		APP_LOG_ADD( $cHEADER, 'prs_tb_education.php:'.$cAPP_CODE);
		SYS_DB_CLOSE($DB2);
		break;

	case md5('upd_edu'):
		$can_UPDATE = TRUST($cUSERCODE, 'TB_EDUCATION_2UPD');	$can_DELETE = TRUST($cUSERCODE, 'TB_EDUCATION_3DEL');
		$qQUERY=OpenTable('TbEducation', "md5(EDU_CODE)='$_GET[_e]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$REC_PERSONE=SYS_FETCH($qQUERY);
		$cEDIT_TBL	= S_MSG('PB12','Edit Tabel Pendidikan');
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_edu').'&_id='. $REC_PERSONE['REC_ID']. '" onClick="return confirm('. "'". S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?'). "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_id='.$REC_PERSONE['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,2], '900', 'EDIT_EDU_CODE', $REC_PERSONE['EDU_CODE'], '', '', '', 0, 'disabled', 'fix');
					LABEL([4,4,4,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'EDIT_EDU_NAME', $REC_PERSONE['EDU_NAME'], '', '', '', 0, '', 'fix');
					SAVE(($can_UPDATE ? $cSAVE : ''));
				eTDIV();
			eTFORM();
		END_WINDOW();
		APP_LOG_ADD( $cHEADER, 'prs_tb_education.php:'.$cAPP_CODE);
		SYS_DB_CLOSE($DB2);
		break;

case 'tambah':
	$nRec_id = date_create()->format('Uv');
	$cRec_id = (string)$nRec_id;
	$cEDU_CODE = ENCODE($_POST['ADD_EDU_CODE']);
	$cEDU_NAME = ENCODE($_POST['ADD_EDU_NAME']);
	if($cEDU_CODE==''){
		MSG_INFO(S_MSG('PB14','Kode Pendidikan belum diisi'));
		return;
	}
	$qQUERY=OpenTable('TbEducation', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and EDU_CODE='$_POST[ADD_EDU_CODE]'");
	if(SYS_ROWS($qQUERY)>0){
		MSG_INFO(S_MSG('PB13','Kode Pendidikan sudah ada'));
		return;
		header('location:prs_tb_education.php');
	} else {
		RecCreate('TbEducation', ['EDU_CODE', 'EDU_NAME', 'ENTRY', 'REC_ID', 'APP_CODE'], [$cEDU_CODE, $cEDU_NAME, $_SESSION['gUSERCODE'], $cRec_id, $_SESSION['data_FILTER_CODE']]);
		header('location:prs_tb_education.php');
	}
	break;

case 'rubah':
	$KODE_CRUD=$_GET['_id'];
	$cEDU_NAME = ENCODE($_POST['EDIT_EDU_NAME']);
	RecUpdate('TbEducation', ['EDU_NAME'], [$cEDU_NAME], "REC_ID='$KODE_CRUD'");
	$ADD_LOG	= APP_LOG_ADD();
	header('location:prs_tb_education.php');
	break;

case md5('del_edu'):
	RecSoftDel($_GET['_id']);
	header('location:prs_tb_education.php');
}
?>

