<?php
//	prs_shift.php //
//	TODO : help pdf

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Jam Kerja.pdf';

	$cHEADER		= S_MSG('PI00','Tabel Jam Kerja');
	$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cKODE			= S_MSG('F003','Kode');
	$cDESKRIPSI		= S_MSG('PI02','Keterangan');
	$cJAM_MASUK		= S_MSG('PI03','Jam Masuk Kerja');
	$cJAM_PULANG	= S_MSG('PI04','Jam Pulang Kerja');
	$cJAM_MSABTU	= S_MSG('PI05','Jam Masuk Sabtu');
	$cJAM_PSABTU	= S_MSG('PI06','Jam Pulang Sabtu');
	$cTIME_ZONE		= S_MSG('PI20','Time zone');
	$cDAFTAR		= S_MSG('PI08','Daftar Jam Kerja');
	$cADD_TBL		= S_MSG('PI10','Tambah Jam Kerja');
	$cEDIT_TBL		= S_MSG('PI11','Edit Jam Kerja');
	
	$cTTIP_NAG	= S_MSG('PI15','Isi dengan kode shift, huruf, angka atau kombinasi. Tidak boleh dengan tanda baca.');
	$cTTIP_KET	= S_MSG('NL73','Keterangan tambahan');
	$cTTIP_MSK	= S_MSG('PI17','Jam masuk kerja');
	$cTTIP_PLG	= S_MSG('PI18','Jam pulang kerja');
	
	$cSAVE_DATA	= S_MSG('F301','Save');
	$cCLOSE_DATA= S_MSG('F302','Close');

	$qQUERY=OpenTable('Timezone', "APP_CODE='$cAPP_CODE' and DELETOR=''");
	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD('Tabel Jadwal Kerja', 'open');
		$can_CREATE = TRUST($cUSERCODE, 'PRS_SHIFT_1ADD');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE, $cDESKRIPSI, $cJAM_MASUK, $cJAM_PULANG, $cTIME_ZONE]);
						echo '<tbody>';
							while($aREC_DISP=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								echo '<td class=""><div class="star"><i class="fa fa-star-half-empty icon-xs icon-default"></i></div></td>';
								echo "<td><span><a href='?_a=".md5('update')."&_id=$aREC_DISP[DAYL_CODE]'>".$aREC_DISP['DAYL_CODE']."</a></span></td>";
								echo "<td><span><a href='?_a=".md5('update')."&_id=$aREC_DISP[DAYL_CODE]'>".$aREC_DISP['DESC_CRPTN']."</a></span></td>";
								echo '<td>'.$aREC_DISP['JAM_MASUK'].'</td>';
								echo '<td>'.$aREC_DISP['JAM_KELUAR'].'</td>';
								echo '<td>'.$aREC_DISP['TIME_ZONE'].'</td>';
							echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('cr34t3'):
		DEF_WINDOW($cADD_TBL);
			TFORM($cADD_TBL, '?_a=a_d_d', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE);
					INPUT('text', [2,2,2,6], '900', 'KODE_JADWAL', '', '', '', '', 5, '', 'fix', $cTTIP_NAG);
					LABEL([3,3,3,6], '700', $cDESKRIPSI);
					INPUT('text', [6,6,6,6], '900', 'NAMA_JDW', '', '', '', '', 0, '', 'fix', $cTTIP_KET);
					LABEL([3,3,3,6], '700', $cJAM_MASUK);
					INP_TIME([], '', 'JAM_MASUK', '', '', '', '', 'fix', $cTTIP_MSK);
					LABEL([3,3,3,6], '700', $cJAM_PULANG);
					INP_TIME([], '', 'JAM_PULANG', '', '', '', '', 'fix', $cTTIP_PLG);
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('update'):
		$can_UPDATE = TRUST($cUSERCODE, 'PRS_SHIFT_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'PRS_SHIFT_3DEL');
		$qQUERY=OpenTable('PrsSchedule', "DAYL_CODE='$_GET[_id]'");
		$aRECORD=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=delete&_id='. $aRECORD['DAYL_CODE']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=saveShift&_id='.md5($aRECORD['DAYL_CODE']), $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE);
					INPUT('text', [2,2,2,6], '900', 'KODE_JADWAL', $aRECORD['DAYL_CODE'], '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cDESKRIPSI);
					INPUT('text', [6,6,6,6], '900', 'NAMA_JADWAL', $aRECORD['DESC_CRPTN'], '', '', '', 0, '', 'fix', $cTTIP_NAG);
					echo '<br>';
					LABEL([3,3,3,6], '700', $cJAM_MASUK);
					INP_TIME([], '', 'JAM_MASUK', $aRECORD['JAM_MASUK'], '', '', '', '', $cTTIP_MSK);
					LABEL([3,3,3,6], '700', $cJAM_PULANG, '', 'right');
					INP_TIME([], '', 'JAM_PULANG', $aRECORD['JAM_KELUAR'], '', '', '', 'fix', $cTTIP_PLG);
					echo '<br>';
					LABEL([3,3,3,6], '700', $cJAM_MSABTU);
					INP_TIME([], '', 'MSK_SABTU', $aRECORD['SAT_IN'], '', '', '', '', $cTTIP_MSK);
					LABEL([3,3,3,6], '700', $cJAM_PSABTU, '', 'right');
					INP_TIME([], '', 'PLG_SABTU', $aRECORD['SAT_OUT'], '', '', '', 'fix', $cTTIP_PLG);
					CLEAR_FIX();
					echo '<br>';
					LABEL([3,3,3,6], '700', $cTIME_ZONE);
					SELECT([5,5,5,5], 'TIMEZONE');
						echo '<option></option>';
						$qTZONE=OpenTable('TbTimezone', "APP_CODE='$cAPP_CODE'");
						while($aTZONE=SYS_FETCH($qTZONE)){
							if($aRECORD['TZONE_CODE']==$aTZONE['ZONE_CODE'])
								echo "<option value='$aTZONE[ZONE_CODE]' selected='$aRECORD[TZONE_CODE]' >$aTZONE[TIME_ZONE]</option>";
							else 
								echo "<option value='$aTZONE[ZONE_CODE]'  >$aTZONE[TIME_ZONE]</option>";
						}
					echo '</select>';
					CLEAR_FIX();
					SAVE(($can_UPDATE ? $cSAVE_DATA : ''));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case 'a_d_d':
		$cKODE = ENCODE($_POST['KODE_JADWAL']);
		$cJMSK=str_replace('_', '0', $_POST['JAM_MASUK']);
		$cJKLR=str_replace('_', '0', $_POST['JAM_PULANG']);
		if($cKODE=='') {
			MSG_INFO(S_MSG('PI40','Kode jam kerja tidak boleh kosong'));
			return;
		}
		$qQUERY=OpenTable('PrsSchedule', "DAYL_CODE='$cKODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		if(SYS_ROWS($qQUERY)==0){
			$cDESKRIPSI = ENCODE($_POST['NAMA_JDW']);
			RecCreate('PrsSchedule', ['DAYL_CODE', 'DESC_CRPTN', 'JAM_MASUK', 'JAM_KELUAR', 'ENTRY', 'APP_CODE', 'DATE_ENTRY'], 
				[$cKODE, $cDESKRIPSI, $cJMSK, $cJKLR, $cUSERCODE, $cAPP_CODE, date('Y-m-d H:i:s')]);
			APP_LOG_ADD($cHEADER, 'add '.$cKODE.'-'.$cDESKRIPSI);
			header('location:prs_shift.php');
		} else {
			MSG_INFO(S_MSG('PI39','Kode jam kerja sudah ada'));
			return;
		}
	break;

	case 'saveShift':
		$KODE_CRUD=$_GET['_id'];
		$cJMSK=str_replace('_', '0', $_POST['JAM_MASUK']);
		$cJKLR=str_replace('_', '0', $_POST['JAM_PULANG']);
		RecUpdate('PrsSchedule', ['DESC_CRPTN', 'JAM_MASUK', 'JAM_KELUAR', 'SAT_IN', 'SAT_OUT', 'TZONE_CODE'], 
			[$_POST['NAMA_JADWAL'], $cJMSK, $cJKLR, $_POST['MSK_SABTU'], $_POST['PLG_SABTU'], $_POST['TIMEZONE']], "md5(DAYL_CODE)='$KODE_CRUD'");
		APP_LOG_ADD($cHEADER, 'Update '.$_POST['NAMA_JADWAL']);
		header('location:prs_shift.php');
	break;

	case 'delete':
		$KODE_CRUD=$_GET['_id'];
		RecUpdate('PrsSchedule', ['DELETOR', 'DEL_DATE'], [$_SESSION['gUSERCODE'], date("Y-m-d H:i:s")], "DAYL_CODE='$KODE_CRUD'");
		APP_LOG_ADD($cHEADER, 'Delete '.$KODE_CRUD);
		header('location:prs_shift.php');
}
?>

