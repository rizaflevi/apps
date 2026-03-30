<?php
//	tb_agama.php //

	include "sysfunction.php";
	$cCALL_FROM = '';
	if (isset($_GET['_call'])) $cCALL_FROM=$_GET['_call'];
	
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Agama.pdf';
	
	$cHEADER 	= S_MSG('PH50','Tabel Agama');
	$can_CREATE = TRUST($cUSERCODE, 'TB_RELIGION_1ADD');

	$cKODE_AGAMA= S_MSG('F003','Kode');
	$cNAMA_AGAMA= S_MSG('PH51','Agama');
	$cDAFTAR	= S_MSG('PH52','Daftar Agama');
	$cADD_TBL	= S_MSG('PH53','Tambah Agama');
	$cEDIT_TBL	= S_MSG('PH58','Edit Kode Agama');
	
	$cTTIP_NAG	= S_MSG('PH61','Nama Agama sebagai keterangan');

	$cSAVE_DATA	= S_MSG('F301','Save');

	$qQUERY=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$cACTION=(isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD('Tabel agama', 'open');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV(12,12,12,12);
					TABLE('example');
						THEAD([$cKODE_AGAMA, $cNAMA_AGAMA]);
						echo '<tbody>';
							while($aREC_DISP=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								echo '<td class=""><div class="star"><i class="fa fa-star-half-empty icon-xs icon-default"></i></div></td>';
								echo "<td><span><a href='?_a=".md5('updRel')."&_id=$aREC_DISP[REC_ID]'>".$aREC_DISP['KODE']."</a></span></td>";
								echo "<td><span><a href='?_a=".md5('updRel')."&_id=$aREC_DISP[REC_ID]'>".$aREC_DISP['NAMA']."</a></span></td>";
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
	DEF_WINDOW($cADD_TBL);
		TFORM($cADD_TBL, '?_a=addRel', [], $cHELP_FILE);
			TDIV();
				LABEL([4,4,4,6], '700', $cKODE_AGAMA);
				INPUT('text', [2,2,2,6], '900', 'KODE_AGAMA', '', '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cNAMA_AGAMA);
				INPUT('text', [4,4,4,6], '900', 'NAMA_AGAMA', '', '', '', '', 0, '', 'fix', $cTTIP_NAG);
				SAVE($cSAVE_DATA);
			eTDIV();
		eTFORM();
		include "scr_chat.php";
		require_once("js_framework.php");
	END_WINDOW();
	break;

	case md5('updRel'):
		$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$can_UPDATE = TRUST($cUSERCODE, 'TB_RELIGION_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'TB_RELIGION_3DEL');
		$qQUERY=OpenTable('TbReligion', "REC_ID='$_GET[_id]'");
		$REC_AGAM=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='. md5('del_agama').'&_id='. $REC_AGAM['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cHEADER, '?_a=saveRel&_id='.$REC_AGAM['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_AGAMA);
					INPUT('text', [2,2,2,6], '900', 'KD_AGAM', $REC_AGAM['KODE'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_AGAMA);
					INPUT('text', [6,6,6,6], '900', 'NM_AGAM', $REC_AGAM['NAMA'], '', '', '', 0, '', 'fix');
					SAVE($can_UPDATE==1 ? $cSAVE_DATA : '');
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END2WINDOW();
		break;

case 'addRel':
	$cKODE_AGAMA = encode_string($_POST['KODE_AGAMA']);
	if($cKODE_AGAMA=='') {
		MSG_INFO(S_MSG('PH55','Kode Agama tidak boleh kosong'));
		return;
	}
	$qQUERY=OpenTable('TbReligion', "KODE='$cKODE_AGAMA' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qQUERY)==0){
		$cNAMA_AGAMA = encode_string($_POST['NAMA_AGAMA']);
		RecCreate('TbReligion', ['KODE', 'NAMA', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cKODE_AGAMA, $cNAMA_AGAMA, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
		header('location:tb_agama.php');
	} else {
		MSG_INFO(S_MSG('PH54','Kode Agama sudah ada'));
		return;
	}
	APP_LOG_ADD($cHEADER, 'add '.$cKODE_AGAMA);
	break;

case 'saveRel':
	$KODE_CRUD=$_GET['_id'];
	RecUpdate('TbReligion', ['NAMA'], [$_POST['NM_AGAM']], "REC_ID='$KODE_CRUD'");
	APP_LOG_ADD($cHEADER, 'update '.$cKODE_AGAMA);
	header('location:tb_agama.php');
	break;

case md5('del_agama'):
	RecSoftDel($_GET['_id']);
	APP_LOG_ADD($cHEADER, 'delete '.$_GET['_id']);
	header('location:tb_agama.php');
}
?>

