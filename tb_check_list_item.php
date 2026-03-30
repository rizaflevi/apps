<?php
//	tb_check_list_item.php //

$cCALL_FROM = '';
if (isset($_GET['_call'])) {
	$cCALL_FROM=$_GET['_call'];
	if ($cCALL_FROM=='fromdesktop') {
		$cAPP_CODE = $_GET['_app'];
		$cUSERCODE = $_GET['_u'];
		$_SESSION['data_FILTER_CODE'] = $cAPP_CODE;
		$_SESSION['gUSERCODE'] = $cUSERCODE;
		$_SESSION['cHOST_DB2'] = $_GET['_dbserver'];
		$_SESSION['sLANG'] = '1';
		include "sys_function.php";
	} else die ($cCALL_FROM);
} else {
	if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
	}
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	include "sysfunction.php";
}

$cHEADER = S_MSG('CL01','Tabel Checklist');
$can_CREATE = TRUST($cUSERCODE, 'TB_CHECKLIST_1ADD');

$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

$cKODE_TBL 	= S_MSG('CL02','Kode');
$cNAMA_TBL 	= S_MSG('CL03','Keterangan');
$cDAFTAR	= S_MSG('CL21','Daftar Item Checklist');
$cHELP_FILE = 'Doc/Tabel - Item Checklist.pdf';

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'View');
        $qTBCL=OpenTable('TbChecklist', "I.APP_CODE='$cAPP_CODE' and I.REC_ID not in ( select DEL_ID from logs_delete)");
		$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('addChecklist'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
		DEF_WINDOW($cHEADER);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example', '', 10, '*', '*', '*');
						THEAD([$cKODE_TBL, $cNAMA_TBL, 'Freq.']);
						echo '<tbody>';
								while($aREC_CHECKLIST=SYS_FETCH($qTBCL)) {
								echo '<tr>';
									echo '<td style="width: 1px;"></td>';
									echo "<td><span><a href='?_a=".md5('upda_te')."&_o=".md5($aREC_CHECKLIST['CLI_CODE'])."'>".$aREC_CHECKLIST['CLI_CODE']."</a></span></td>";
									echo "<td><span><a href='?_a=".md5('upda_te')."&_o=".md5($aREC_CHECKLIST['CLI_CODE'])."'>".decode_string($aREC_CHECKLIST['CLI_DESC'])."</a></span></td>";
									echo "<td><span>".$aREC_CHECKLIST['UNIT_NAME']."</a></span></td>";
								echo '</tr>';
								}
						echo '</tbody>';
					echo '</table>';
				eTDIV();
			eTFORM('*');
		END_WINDOW();
	break;

	case md5('addChecklist'):
		$cADD_NEW	= S_MSG('CL10','Tambah Item');
		DEF_WINDOW($cADD_NEW);
			TFORM($cADD_NEW, '?_a=add_Checklist', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,3], '900', 'ADD_CL_CODE', '', 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [8,8,8,8], '900', 'ADD_CLI_DESC', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', 'Freq.');
					SELECT([2,2,2,6], 'ADD_FREQ');
						$qFREQ=OpenTable('PrsTbUnit', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						echo "<option value=' '  > </option>";
						while($aREC_UNIT=SYS_FETCH($qFREQ)){
							echo "<option class='form-label-900' value='$aREC_UNIT[UNIT_CODE]'  >$aREC_UNIT[UNIT_NAME]</option>";
						}
					echo '</select>';
					CLEAR_FIX();
					$cSAVE		= ($can_CREATE ? S_MSG('F301','Save') : '');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('upda_te'):
		$cEDIT_ITEM	= S_MSG('CL11','Edit Item');
		$cITEM=$_GET['_o'];
		print_r2($cITEM);
		$qTBCL=OpenTable('TbChecklist', "md5(I.CLI_CODE)='$cITEM' and I.APP_CODE='$cAPP_CODE' and I.REC_ID not in ( select DEL_ID from logs_delete)");
		$aREC_CHECKLIST=SYS_FETCH($qTBCL);
		$can_UPDATE = TRUST($cUSERCODE, 'TB_CHECKLIST_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'TB_CHECKLIST_3DEL');
		DEF_WINDOW($cEDIT_ITEM);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=del_Checklist&_id='. $aREC_CHECKLIST['REC_ID']. '" onClick="return confirm('. "'". S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?'). "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_ITEM, '?_a=upd_Checklist&_id='.$aREC_CHECKLIST['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,3], '900', 'EDIT_CLI_CODE', $aREC_CHECKLIST['CLI_CODE'], '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'EDIT_CLI_DESC', DECODE($aREC_CHECKLIST['CLI_DESC']), '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', 'Freq.');
					SELECT([2,2,2,6], 'UPD_FREQ');
						$qFREQ=OpenTable('PrsTbUnit', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						echo "<option value=' '  > </option>";
						while($aREC_UNIT=SYS_FETCH($qFREQ)){
							if($aREC_CHECKLIST['CLI_FREQ']==$aREC_UNIT['UNIT_CODE']){
								echo "<option value='$aREC_UNIT[UNIT_CODE]' selected='$aREC_CHECKLIST[CLI_FREQ]' >$aREC_UNIT[UNIT_NAME]</option>";
							} else {
								echo "<option value='$aREC_UNIT[UNIT_CODE]'  >$aREC_UNIT[UNIT_NAME]</option>";
							}
						}
					echo '</select>';
					CLEAR_FIX();
					$cSAVE		= ($can_UPDATE ? S_MSG('F301','Save') : '');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case 'del_Checklist':
		RecSoftDel($_GET['_id']);
		header('location:tb_check_list_item.php');
	break;
	
	case "upd_Checklist":
		$KODE_CRUD=$_GET['_id'];
		$cCLI_DESC = ENCODE($_POST['EDIT_CLI_DESC']);
		RecUpdate('TbChecklist', ['CLI_DESC', 'CLI_FREQ'], [$cCLI_DESC, $_POST['UPD_FREQ']], "APP_CODE='$cAPP_CODE' and REC_ID='$KODE_CRUD'");
		echo "<script> window.history.go(-2);	</script>";
	break;
	
	case "add_Checklist":
		if($_POST['ADD_CL_CODE']==''){
			MSG_INFO(S_MSG('CL23','Kode Checklist masih kosong'));
			return;
		}
		$qTBCL=OpenTable('TbChecklist', "CLI_CODE='$_POST[ADD_CL_CODE]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qTBCL)>0){
			MSG_INFO(S_MSG('CL22','Kode Checklist sudah ada'));
			return;
		} else {
			$nRec_id = date_create()->format('Uv');
			$cRec_id = (string)$nRec_id;
			$cCLI_DESC = ENCODE($_POST['ADD_CLI_DESC']);
			$cCLI_FREQ = ENCODE($_POST['ADD_FREQ']);
			RecCreate('TbChecklist', ['CLI_CODE', 'CLI_DESC', 'CLI_FREQ', 'ENTRY', 'REC_ID', 'APP_CODE'], 
				[$_POST['ADD_CL_CODE'], $cCLI_DESC, $cCLI_FREQ, $cUSERCODE, $cRec_id, $cAPP_CODE]);
			header('location:tb_check_list_item.php');
		}
}
?>

