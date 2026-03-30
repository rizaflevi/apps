<?php
//	tb_holiday.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$sPERIOD1=date("Y-m-d");
	if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Hari Libur.pdf';
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cHEADER 	= S_MSG('TH01','Table hari libur');
	$can_CREATE = TRUST($cUSERCODE, 'TB_HOLIDAY_1ADD');

	$qQUERY=OpenTable('TbHoliday', "left(HOLI_YEAR,4)=".substr($sPERIOD1,0,4). " and APP_CODE='$cAPP_CODE' and DELETOR=''");

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];
	$cHOLIDAY_CODE 	= S_MSG('TH02', 'Hari libur');
	$cHOLIDAY_START = S_MSG('TH03', 'Mulai tanggal');
	$cHOLIDAY_FINIS = S_MSG('TH04', 'Sampai tanggal');
	$cADD_NEW		= S_MSG('TH06', 'Tambah Hari libur');
	$cEDIT_TBL		= S_MSG('TH07', 'Edit Hari libur');
	$cDAFTAR		= S_MSG('TH11', 'Daftar Hari libur');

	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$cTTIP_NAMA		= S_MSG('TH16','Keterangan hari libur');
	$cTTIP_DATE1	= S_MSG('TH17','Tanggal mulai libur');
	$cTTIP_DATE2	= S_MSG('TH18','Tanggal akhir libur');

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'view');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('CREATE_NEW'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cHOLIDAY_CODE, $cHOLIDAY_START, $cHOLIDAY_FINIS]);
						echo '<tbody>';
							while($aREC_HOLI_DESC=SYS_FETCH($qQUERY)) {
								echo '<tr>';
									echo '<td class=""><div class="star"><i class="fa fa-calendar-o icon-xs icon-default"></i></div></td>';
									echo "<td><span><a href='?_a=update&HOLI_DESC=$aREC_HOLI_DESC[HOLI_DESC]'>".$aREC_HOLI_DESC['HOLI_DESC']."</a></span></td>";
									echo "<td>".date("d-m-Y", strtotime($aREC_HOLI_DESC['START_DATE']))."</td>";
									echo "<td>".date("d-m-Y", strtotime($aREC_HOLI_DESC['FINISH_DT']))."</td>";
								echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
				TDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('CREATE_NEW'):
		DEF_WINDOW($cADD_NEW);
			TFORM($cADD_NEW, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cHOLIDAY_CODE);
					INPUT('text', [7,7,7,6], '900', 'ADD_HOLI_DESC', '', 'focus', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([4,4,4,6], '700', $cHOLIDAY_START);
					INP_DATE([3,3,3,6], '900', 'ADD_START_DATE', date('d/m/Y'), '', '', '', 'fix', $cTTIP_DATE1);
					LABEL([4,4,4,6], '700', $cHOLIDAY_FINIS);
					INP_DATE([3,3,3,6], '900', 'ADD_FINIS_DATE', date('d/m/Y'), '', '', '', 'fix', $cTTIP_DATE2);
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case "update":
		$can_UPDATE = TRUST($cUSERCODE, 'TB_HOLIDAY_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'TB_HOLIDAY_3DEL');
		$qQUERY=OpenTable('TbHoliday', "APP_CODE='$cAPP_CODE' and HOLI_DESC='$_GET[HOLI_DESC]' and DELETOR=''");
		$REC_holiday=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DELETE_REC').'&_id='. $REC_holiday['HOLI_DESC']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_id='.md5($REC_holiday['HOLI_REC']), $aACT, $cHELP_FILE);
				TDIV(8,8,9,12);
					LABEL([4,4,4,6], '700', $cHOLIDAY_CODE);
					INPUT('text', [7,7,7,6], '900', 'EDIT_HOLI_DESC', $REC_holiday['HOLI_DESC'], 'focus', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([4,4,4,6], '700', $cHOLIDAY_START);
					INP_DATE([3,3,3,6], '900', 'EDIT_START_DATE', date("d/m/Y", strtotime($REC_holiday['START_DATE'])), '', '', '', 'fix', $cTTIP_DATE1);
					LABEL([4,4,4,6], '700', $cHOLIDAY_START);
					INP_DATE([3,3,3,6], '900', 'EDIT_FINIS_DATE', date("d/m/Y", strtotime($REC_holiday['FINISH_DT'])), '', '', '', 'fix', $cTTIP_DATE2);
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case md5('DELETE_REC'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['_id'];
	RecUpdate('TbHoliday', ['DELETOR', 'DEL_DATE'], [$_SESSION['gUSERCODE'], $NOW], "APP_CODE='$cAPP_CODE' and HOLI_DESC='$KODE_CRUD'");
	APP_LOG_ADD($cHEADER, 'delete : '.$KODE_CRUD);
	header('location:tb_holiday.php');
	break;
	
	
case "rubah":
	$dTG_MULAI = $_POST['EDIT_START_DATE'];		// 'dd/mm/yyyy'
	$cDATE_START = substr($dTG_MULAI,6,4). '-'. substr($dTG_MULAI,3,2). '-'. substr($dTG_MULAI,0,2);
	$dTG_AKHIR = $_POST['EDIT_FINIS_DATE'];		// 'dd/mm/yyyy'
	$cDATE_FINIS = substr($dTG_AKHIR,6,4). '-'. substr($dTG_AKHIR,3,2). '-'. substr($dTG_AKHIR,0,2);
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cHOLI_DESC	= ENCODE($_POST['EDIT_HOLI_DESC']);	
	RecUpdate('TbHoliday', ['HOLI_DESC', 'START_DATE', 'FINISH_DT', 'UP_DATE', 'UPD_DATE'], 
		[$cHOLI_DESC, $cDATE_START, $cDATE_FINIS, $_SESSION['gUSERCODE'], $NOW], "APP_CODE='$cAPP_CODE' and HOLI_DESC='$KODE_CRUD'");
	APP_LOG_ADD($cHEADER, 'update : '.$KODE_CRUD);
	header('location:tb_holiday.php');
	break;
	
case "tambah":
	$dTG_MULAI = $_POST['ADD_START_DATE'];		// 'dd/mm/yyyy'
	$cDATE_START = substr($dTG_MULAI,6,4). '-'. substr($dTG_MULAI,3,2). '-'. substr($dTG_MULAI,0,2);
	$dTG_AKHIR = $_POST['ADD_FINIS_DATE'];		// 'dd/mm/yyyy'
	$cDATE_FINIS = substr($dTG_AKHIR,6,4). '-'. substr($dTG_AKHIR,3,2). '-'. substr($dTG_AKHIR,0,2);
	$NOW = date("Y-m-d H:i:s");
	$cHOL_DESC = $_POST['ADD_HOLI_DESC'];
	if($cHOL_DESC==''){
		MSG_INFO(S_MSG('TH13','Keterangan Hari libur belum diisi'));
		return;
	}
	$cYEAR = substr($cDATE_START,0,4);
	$qQUERY=OpenTable('TbHoliday', "left(HOLI_YEAR,4) = '$cYEAR' and APP_CODE='$cAPP_CODE' and HOLI_DESC='".encode_string($_POST['ADD_HOLI_DESC'])."' and DELETOR=''");
	if(SYS_ROWS($qQUERY)>0){
		MSG_INFO(S_MSG('TH12', 'Hari libur sudah ada'));
		return;
		header('location:tb_holiday.php');
	} else {
		RecCreate('TbHoliday', ['HOLI_DESC', 'START_DATE', 'FINISH_DT', 'HOLI_YEAR', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], 
			[encode_string($_POST['ADD_HOLI_DESC']), $cDATE_START, $cDATE_FINIS, $cYEAR, $_SESSION['gUSERCODE'], $NOW, $cAPP_CODE]);
		header('location:tb_holiday.php');
	}
	APP_LOG_ADD($cHEADER, 'add : '.$KODE_CRUD);
}
?>

