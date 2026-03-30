<?php
//	prs_tb_occupation.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHEADER = S_MSG('PA5D','Tabel Jabatan');
	$cHELP_FILE = 'Doc/Tabel - Jabatan.pdf';

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

	$cKODE_TBL 	= S_MSG('F003','Kode');
	$cNAMA_TBL 	= S_MSG('PA55','Jabatan');
	$cEDIT_LOKS	= S_MSG('PA5B','Edit Jabatan');
	$cDAFTAR	= S_MSG('PA5C','Daftar Jabatan');

switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'PRS_OCCUPATION_1ADD');
		$qQUERY=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''");
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('CREATE_OCCU'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL]);
						echo '<tbody>';
							while($r_PERSONJ=SYS_FETCH($qQUERY)) {
								$cHREFF="<a href='?_a=".md5('upda_te')."&_o=".md5($r_PERSONJ['JOB_CODE'])."'>";
								TDETAIL([$r_PERSONJ['JOB_CODE'], DECODE($r_PERSONJ['JOB_NAME'])], [], '', [$cHREFF, $cHREFF]);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('CREATE_OCCU'):
		$cADD_NEW	= S_MSG('PA5A','Tambah Jabatan');
		DEF_WINDOW($cADD_NEW);
			TFORM($cADD_NEW, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,3], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,2], '900', 'ADD_JOB_CODE', '', 'autofocus', '', '', 0, '', 'fix');
					LABEL([3,3,3,3], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'ADD_JOB_NAME', '', '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('upda_te'):
		$can_UPDATE = TRUST($cUSERCODE, 'PRS_OCCUPATION_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'PRS_OCCUPATION_3DEL');
		$qQUERY=OpenTable('TbOccupation', "md5(JOB_CODE)='$_GET[_o]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		$REC_PRS_JOB=SYS_FETCH($qQUERY);
		$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$cJOB_CODE=$REC_PRS_JOB['JOB_CODE'];
		DEF_WINDOW($cEDIT_LOKS);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_occu').'&id='. md5($REC_PRS_JOB['JOB_CODE']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_LOKS, '?_a=rubah&id='.$REC_PRS_JOB['JOB_CODE'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,3], '700', $cKODE_TBL);
					INPUT('text', [2,2,4,4], '700', 'EDIT_JOB_CODE', $cJOB_CODE, '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,3], '700', $cNAMA_TBL);
					INPUT('text', [5,5,5,5], '700', 'EDIT_JOB_NAME', DECODE($REC_PRS_JOB['JOB_NAME']), '', '', '', 0, '', 'fix');
					LABEL([3,3,3,3], '700', 'public');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_PUBLIC', $REC_PRS_JOB['PUBLIC']==1, '', '', '', 0, '', 'fix', '', $REC_PRS_JOB['PUBLIC']==1);
					SAVE(($can_UPDATE ? S_MSG('F301','Save') : ''));
                eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('del_occu'):
		$NOW = date("Y-m-d H:i:s");
		$KODE_CRUD=$_GET['id'];
		RecUpdate('TbOccupation', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, $NOW], "APP_CODE='$cAPP_CODE' and md5(JOB_CODE)='$KODE_CRUD'");
		header('location:prs_tb_occupation.php');
	break;
		
		
	case "rubah":
		$NOW = date("Y-m-d H:i:s");
		$KODE_CRUD=$_GET['id'];
		$cPUB= $_POST['UPD_PUBLIC'];
		$nPUB= ($cPUB=='on' ? 1 : 0);
		$cJOB_NAME = ENCODE($_POST['EDIT_JOB_NAME']);
		RecUpdate('TbOccupation', ['JOB_NAME', 'PUBLIC', 'UP_DATE', 'UPD_DATE'], [$cJOB_NAME, $nPUB, $cUSERCODE, $NOW], "APP_CODE='$cAPP_CODE' and JOB_CODE='$KODE_CRUD'");
		header('location:prs_tb_occupation.php');
	break;
		
		
	case "tambah":
		$NOW = date("Y-m-d H:i:s");
		$cJOB = $_POST['ADD_JOB_CODE'];
		if($cJOB==''){
			MSG_INFO(S_MSG('PA5F','Kode Jabatan masih kosong'));
			return;
		}
		$qQUERY=OpenTable('TbOccupation', "JOB_CODE='$cJOB' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		if(SYS_ROWS($qQUERY)>0){
			MSG_INFO(S_MSG('PA5E','Kode Jabatan sudah ada'));
			return;
		} else {
			$cJOB_NAME = ENCODE($_POST['ADD_JOB_NAME']);
			RecCreate('TbOccupation', ['JOB_CODE', 'JOB_NAME', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], [$cJOB, $cJOB_NAME, $cUSERCODE, $NOW, $cAPP_CODE]);
			$ADD_LOG	= APP_LOG_ADD();
		}
		header('location:prs_tb_occupation.php');
}
?>

