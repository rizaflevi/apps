<?php
//	prs_tb_skill.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();
	$cHELP_FILE = 'Doc/Tabel - Skill.pdf';

	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('PG51','Tabel Keahlian');
	$cUSERCODE = $_SESSION['gUSERCODE'];

	$qQUERY=OpenTable('TbSkill');

	$cACTION='';
	if (isset($_GET['_a'])) 	$cACTION=$_GET['_a'];
	$cKODE_TBL 		= S_MSG('PG52','Kode Keahlian');
	$cNAMA_TBL 		= S_MSG('PG53','Nama Keahlian');
	$cKETERANGAN	= S_MSG('PA98','Keterangan');
	$cAHLI_RATE		= S_MSG('PG55','Skill rate');
	$cDAFTAR		= S_MSG('PG50','Daftar Keahlian Karyawan');
	$cADD_NEW		= S_MSG('PG59','Tambah Keahlian');
	$cEDIT_TBL		= S_MSG('PG58','Edit Keahlian');
	$cMSG_DEL		= S_MSG('F021','Benar data ini mau di hapus ?');

	$cSAVE_DATA=S_MSG('F301','Save');
	$cCLOSE_DATA=S_MSG('F302','Close');
	
switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$can_CREATE = TRUST($cUSERCODE, 'PRS_SKILL_1ADD');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL]);
						echo '</tbody>';
							while($aREC_SKILL_CODE=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								echo '<td style="width: 1px;"></td>';
								echo "<td><span><a href='?_a=".md5('upd4t3')."&_c=".md5($aREC_SKILL_CODE['SKILL_CODE'])."'>".decode_string($aREC_SKILL_CODE['SKILL_CODE'])."</a></span></td>";
								echo "<td><span><a href='?_a=".md5('upd4t3')."&_c=".md5($aREC_SKILL_CODE['SKILL_CODE'])."'>".decode_string($aREC_SKILL_CODE['SKILL_NAME'])."</a></span></td>";
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
		DEF_WINDOW($cADD_NEW);
			TFORM($cADD_NEW, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
				LABEL([4,4,4,6], '700', $cKODE_TBL);
				INPUT('text', [2,2,2,6], '900', 'ADD_SKILL_CODE', '', 'focus', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cNAMA_TBL);
				INPUT('text', [6,6,6,6], '900', 'ADD_SKILL_NAME', '', 'focus', '', '', 0, '', 'fix');
				SAVE($cSAVE_DATA);
				TDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

case md5('upd4t3'):
		$can_UPDATE = TRUST($cUSERCODE, 'PRS_SKILL_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'PRS_SKILL_2UPD');

		$cTTIP_KODE		= S_MSG('PG61','Kode keahlian yang diakui');
		$cTTIP_NAMA		= S_MSG('PG63','Nama keahlian karyawan');
		$qQUERY=OpenTable('TbSkill', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and md5(SKILL_CODE)='$_GET[_c]'");
		$REC_PRS_SKILL=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$can_DELETE = TRUST($cUSERCODE, 'VENDOR_3DEL');
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('D3LETE').'&id='. $REC_PRS_SKILL['REC_ID']. '" onClick="return confirm('. "'". $cMSG_DEL. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_id='.$REC_PRS_SKILL['REC_ID'], $aACT, $cHELP_FILE);
				eTDIV();
					LABEL([4,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [8,8,8,6], '900', 'EDIT_SKILL_CODE', $REC_PRS_SKILL['SKILL_CODE'], '', '', '', 0, 'disable', 'fix', $cTTIP_KODE);
					LABEL([4,4,4,6], '700', $cNAMA_TBL);
					INPUT('text', [8,8,8,6], '900', 'EDIT_SKILL_NAME', DECODE($REC_PRS_SKILL['SKILL_NAME']), 'focus', '', '', 0, 'disable', 'fix', $cTTIP_NAMA);
					SAVE($can_UPDATE ? $cSAVE_DATA : '');
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

case md5('D3LETE'):
	RecSoftDel($_GET['id']);
	header('location:prs_tb_skill.php');
	break;
	
	
case "rubah":
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['_id'];
	$cKODE_SKIL	= encode_string($_POST['ADD_SKILL_CODE']);
	RecUpdate('TbSkill', ['SKILL_NAME', 'UP_DATE', 'UPD_DATE'], [$_POST['EDIT_SKILL_NAME'], $_SESSION['gUSERCODE'], $NOW], "APP_CODE='$cAPP_CODE' and REC_ID='$KODE_CRUD'");
	header('location:prs_tb_skill.php');

	break;
	
	
case "tambah":
	$NOW = date("Y-m-d H:i:s");
	$cKODE_SKIL	= encode_string($_POST['ADD_SKILL_CODE']);	
	if($cKODE_SKIL==''){
		MSG_INFO(S_MSG('PG60','Kode Keahlian tidak boleh kosong'));
		return;
	}
	$qQUERY=OpenTable('TbSkill', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and SKILL_CODE='$cKODE_SKIL'");
	if(SYS_ROWS($qQUERY)>0){
		MSG_INFO(S_MSG('PG5A','Kode Keahlian sudah ada'));
		return;
	} else {
		$cNAMA_SKIL	= encode_string($_POST['ADD_SKILL_NAME']);
		$nRec_id = date_create()->format('Uv');
		$cRec_id = (string)$nRec_id;
		RecCreate('TbSkill', ['SKILL_CODE', 'SKILL_NAME', 'ENTRY', 'APP_CODE', 'REC_ID'],
			[$cKODE_SKIL, $cNAMA_SKIL, $_SESSION['gUSERCODE'], $cAPP_CODE, $cRec_id]); 
		header('location:prs_tb_skill.php');
	}
}
?>

<script>
$('#table_id').dataTable({    
    "bInfo": false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
    "paging": false,//Dont want paging                
    "bPaginate": false,//Dont want paging      
})
</script>
