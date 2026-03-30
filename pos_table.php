<?php
//	pos_table.php //

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('upload_max_filesize', '20M');
// ini_set('max_execution_time', 60); //60 seconds

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

$cAPP_CODE = $_SESSION['data_FILTER_CODE'];	$cUSERCODE = $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Tabel - Meja.pdf';

$nGROUP_MENU = 0;
$qTABLE=OpenTable('TbInvGroup', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
$nGROUP_MENU = SYS_ROWS($qTABLE);

$cHEADER 		= S_MSG('PM01','Tabel Meja');
$cTABLE_CODE	= S_MSG('PM02','Kode');
$cTABLE_DESK 	= S_MSG('PM03','Lokasi');
$cCAPACITY		= 'Kapasitas';

$cTTIP_KODE		= 'Nomor Meja';
$cTTIP_NAMA		= 'Lokasi meja';
$cTTIP_KAPS		= 'Kapasitas kursi';

$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
$cADD_REC		= S_MSG('KA11','Add new');
$cEDIT_TBL		= 'Edit Tabel Menu';

$cSAVE_DATA		= S_MSG('F301','Save');
$cCLOSE_DATA	= S_MSG('F302','Close');

$cACTION=(isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');

switch($cACTION){
	default:
		UPDATE_DATE();
		$ADD_LOG	= APP_LOG_ADD();

		$q_MENU=OpenTable('PosTable', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)",'',  "TABLE_CODE");
		DEF_WINDOW($cHEADER);
			$aACT = (TRUST($cUSERCODE, 'POS_TABLE_1ADD')==1 ? ['<a href="?_a='. md5('CREATE_TABLE'). '"><i class="fa fa-plus-square"></i>'. $cADD_REC.'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						$aHEAD=[$cTABLE_CODE, $cTABLE_DESK, $cCAPACITY];
						$aALIGN=[0,0,1];
						THEAD($aHEAD, '', $aALIGN);
						echo '<tbody>';
							while($aTB_MENU=SYS_FETCH($q_MENU)) {
								$cHREFF="<a href='?_a=".md5('UPD_TABLE')."&_c=".md5($aTB_MENU['REC_ID'])."'>";
								$aCOL=[$aTB_MENU['TABLE_CODE'], DECODE($aTB_MENU['TABLE_DESK']), $aTB_MENU['CAPACITY']];
								$aHREFF=[$cHREFF, $cHREFF, ''];
								TDETAIL($aCOL, $aALIGN, '', $aHREFF);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('CREATE_TABLE'):
		UPDATE_DATE();
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=ADDD__TABLE','', $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cTABLE_CODE);
					INPUT('text', [2,3,3,6], '900', 'ADD_TABLE_CODE', '', 'FOCUS', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cTABLE_DESK);
					INPUT('text', [6,6,6,6], '900', 'ADD_TABLE_DESK', '', '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cCAPACITY);
					INPUT('text', [6,6,6,6], '900', 'ADD_CAPACITY', '', '', '', '', 0, '', 'fix');
					echo '<h4> </br></h4>';
				eTDIV();
				SAVE($cSAVE_DATA);
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('UPD_TABLE'):
		UPDATE_DATE();
		$can_DELETE = TRUST($cUSERCODE, 'POS_TABLE_3DEL');
		$cREC_ID = $_GET['_c'];
		$q_MENU=OpenTable('PosTable', "md5(REC_ID)='$cREC_ID' and REC_ID not in ( select DEL_ID from logs_delete)",'',  "TABLE_CODE");
		if(!$aTB_MENU=SYS_FETCH($q_MENU)){
			header('location:pos_table.php');
			return;
		}
		DEF_WINDOW($cEDIT_TBL);
			$cACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DEL_TABLE').'&_id='. $aTB_MENU['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, "?_a=UPD__TABLE&_c=".$aTB_MENU['REC_ID'], $cACT, $cHELP_FILE);
				LABEL([3,3,3,6], '700', $cTABLE_CODE);
				INPUT('text', [2,3,3,6], '900', 'UPD_TABLE_CODE', $aTB_MENU['TABLE_CODE'], '', '', '', 0, 'disabled', 'fix', $cTTIP_KODE);
				LABEL([3,3,3,6], '700', $cTABLE_DESK);
				INPUT('text', [6,6,6,6], '900', 'UPD_TABLE_DESK', DECODE($aTB_MENU['TABLE_DESK']), 'focus', '', '', 0, '', 'fix', $cTTIP_NAMA);
				LABEL([3,3,3,6], '700', $cCAPACITY);
				INPUT('text', [6,6,6,6], '900', 'UPD_CAPACITY', $aTB_MENU['CAPACITY'], 'focus', '', '', 0, '', 'fix', $cTTIP_NAMA);
				TDIV();
				echo '<h4> </br></h4>';
				SAVE($cSAVE_DATA);
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case 'ADDD__TABLE':
	UPDATE_DATE();
	$cCODE = ENCODE($_POST['ADD_TABLE_CODE']);
	if($cCODE=='') {
		MSG_INFO(S_MSG('PM26','Tidak boleh kosong'));
		return;
	}
	$cNAME = ENCODE($_POST['ADD_TABLE_DESK']);
	$nCAP = ($_POST['ADD_CAPACITY'] ? $_POST['ADD_CAPACITY'] : 0);
	$qQUERY=OpenTable('PosTable', "TABLE_CODE='$cCODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qQUERY)==0){
		RecCreate('PosTable', ['TABLE_CODE', 'TABLE_DESK', 'CAPACITY', 'APP_CODE', 'ENTRY', 'REC_ID'], 
			[$cCODE, $cNAME, $nCAP, $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		header('location:pos_table.php');
	} else {
		MSG_INFO(S_MSG('PM27','Sudah ada'));
		return;
	}


case 'UPD__TABLE':
	UPDATE_DATE();
	$REC_ID=$_GET['_c'];
	$cTABLE_DESK = ENCODE($_POST['UPD_TABLE_DESK']);
	
	$q_MENU=OpenTable('PosTable', "REC_ID='$REC_ID' and REC_ID not in ( select DEL_ID from logs_delete)");
	if($aMENU = SYS_FETCH($q_MENU))
		$cTABLE_CODE = $aMENU['TABLE_CODE'];
	else {
		MSG_INFO('Error ob fetch data');
		return;
	}
	$nCAP = ($_POST['UPD_CAPACITY'] ? $_POST['UPD_CAPACITY'] : 0);
	RecUpdate('PosTable', ['TABLE_DESK', 'CAPACITY'], [$cTABLE_DESK, $nCAP],	"REC_ID='$REC_ID'");

	APP_LOG_ADD($cHEADER, 'edit '.$cTABLE_CODE);
	header('location:pos_table.php');
	break;

case md5('DEL_TABLE'):
	UPDATE_DATE();
	$REC_ID=$_GET['_id'];
	$q_MENU=OpenTable('PosTable', "REC_ID='$REC_ID'");
	$cTABLE_CODE = '';
	if($aINV = SYS_FETCH($q_MENU))
		$cTABLE_CODE = $aINV['TABLE_CODE'];
	
	RecDelete('PosTable', "REC_ID='$REC_ID'");
	APP_LOG_ADD($cHEADER, 'Delete : '.$cTABLE_CODE);
	header('location:pos_table.php');
}
?>

<script>
function previewGambar(event) {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview_IMG');
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>

