<?php
//	tb_shift.php //
//	TODO month on change, delete ??

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE']; 
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Shift.pdf';

	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cHEADER 	= S_MSG('PB41','Jadwal Kerja');
	$sPERIOD1=$_SESSION['sCURRENT_PERIOD'];
	if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];


	$cACTION 	= (isset($_GET['_a']) ? $_GET['_a'] : '');
	$cPERSON    = (isset($_GET['_k']) ? $_GET['_k'] : '');

	$cKODE_PEG	= S_MSG('PA02','Kode Peg');
	$cNAMA_PEG 	= S_MSG('PA03','Nama Pegawai');
	$cCUSTOMER	= S_MSG('RS04','Customer');
	$cLOKASI	= S_MSG('PF16','Lokasi');
	$JABATAN	= S_MSG('PF13','Jabatan');

	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	$nSCOPE = ( SYS_FETCH($qSCOPE) ? SYS_ROWS($qSCOPE) : 0);

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER);
			TFORM($cHEADER, '', [], $cHELP_FILE);
				TABLE('example');
					THEAD([$cNAMA_PEG, $JABATAN, $cCUSTOMER, $cLOKASI]);
					echo '<tbody>';
						$qPERON1=OpenTable('PersonSch', "A.APP_CODE='$cAPP_CODE' and DELETOR='' and A.PRSON_CODE not in ( select PRSON_CODE from prs_resign where APP_CODE='$cAPP_CODE' AND DELETOR='') and A.PRSON_SLRY<2".UserScope($cUSERCODE));
						while($aPERSON1=SYS_FETCH($qPERON1)) {
							$cHREFF="<a href='?_a=".md5('update')."&_k=".$aPERSON1['MASTER_CODE']."&_n=".DECODE($aPERSON1['PRSON_NAME'])."'>";
							TDETAIL([DECODE($aPERSON1['PRSON_NAME']), DECODE($aPERSON1['JOB_NAME']), $aPERSON1['CUST_NAME'], $aPERSON1['LOKS_NAME']], [], '', [$cHREFF, $cHREFF, '', '']);
						}
					echo '</tbody>';
				eTABLE();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

    case md5('update'):
        $upd_TAB_JDW = TRUST($cUSERCODE, 'TB_SHIFT_2UPD');
        $can_DELETE = TRUST($cUSERCODE, 'TB_SHIFT_3DEL');
        $cPERSON = $_GET['_k'];
        $cNAME = $_GET['_n'];
        $nYEAR = date('Y');
        $nMONTH= date('m');
		$dLAST = date("Y-m-t", strtotime($nYEAR.'-'. $nMONTH.'-01'));
		$nLAST = intval(substr($dLAST,8,2));
		DEF_WINDOW($cHEADER);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DELETE_REC').'&_k='. $cPERSON. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cHEADER, '?_a=rubah&_k='.$cPERSON, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([2,2,2,6], '700', $cNAMA_PEG);
					INPUT('text', [8,8,8,8], '900', 'EDIT_NAMA_PEG', $cNAME, '', '', '', 0, 'disabled', 'fix');
					LABEL([2,2,2,6], '700', 'Tahun');
					INPUT('text', [1,2,1,6], '900', 'EDIT_TAHUN', $nYEAR, '', '', '', 0, '', '');
					LABEL([2,2,2,6], '700', 'Bulan', '', 'right');
					INPUT('text', [1,1,1,6], '900', 'EDIT_BULAN', $nMONTH, '', '', '', 0, '', 'fix');
					echo '<br>';
					for ($nTGL=1; $nTGL <= $nLAST; $nTGL++) {
						LABEL([1,1,1,1], '700', $nTGL, '', 'right');
						$cSHIFT_CODE = '';
						$cDATE = (string)$nYEAR.'-'.str_pad($nMONTH, 2, '0', STR_PAD_LEFT).'-'.str_pad($nTGL, 2, '0', STR_PAD_LEFT);
						$qSCHEDULE=OpenTable('RegSchedule', "PERSON_CODE='$cPERSON' and WORK_DATE='$cDATE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
						$REC_SCH=SYS_FETCH($qSCHEDULE);
						if (SYS_ROWS($qSCHEDULE)>0) $cSHIFT_CODE = $REC_SCH['SHIFT_CODE'];
						INPUT('text', [1,1,1,1], '900', 'EDIT_TGL'.$nTGL, $cSHIFT_CODE, '', '', '', 0, '', '', 'Jadwal tanggal '.$nTGL);
					}
					CLEAR_FIX();
					SAVE(($upd_TAB_JDW ? $cSAVE : ''));
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case 'rubah':
        $cPERSON = $_GET['_k'];
        $cYEAR = $_POST['EDIT_TAHUN'];
        $cMONTH = $_POST['EDIT_BULAN'];
        for ($nTGL=1; $nTGL <= $nLAST; $nTGL++) {
            $cTGL = str_pad($nTGL, 2, '0', STR_PAD_LEFT);
            $cDATE = $cYEAR.'-'.$cMONTH.'-'.$cTGL;
            $cSCH = $_POST['EDIT_TGL'.$nTGL];
            if ($cSCH>'') {
                $qTBLSCH=OpenTable('PrsSchedule', "DAYL_CODE='$cSCH' and APP_CODE='$cAPP_CODE' and DELETOR=''");
                if (SYS_ROWS($qTBLSCH)>0){
                    $qSCHEDULE=OpenTable('RegSchedule', "PERSON_CODE='$cPERSON' and WORK_DATE='$cDATE' and APP_CODE='$cAPP_CODE' and DELETOR=''", '', "PERSON_CODE, WORK_DATE");
                    if (SYS_ROWS($qSCHEDULE)>0) {
                        RecUpdate('RegSchedule', ['SHIFT_CODE', 'UP_DATE', 'UPD_DATE'], [$cSCH, $cUSERCODE, date("Y-m-d H:i:s")], 
                            "PERSON_CODE='$cPERSON' and WORK_DATE='$cDATE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
                    } else {
                        RecCreate('RegSchedule', ['PERSON_CODE', 'WORK_DATE', 'SHIFT_CODE', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'],
                        [$cPERSON, $cDATE, $cSCH, $cUSERCODE, date("Y-m-d H:i:s"), $cAPP_CODE]);
                    }
                }
            }
        }
		header('location:tb_shift.php');	
		break;
	
	case md5('DELETE_REC'):
		$KODE_CRUD=$_GET['_k'];
		$cDATE=$_GET['_d'];
		RecUpdate('RegSchedule', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and PERSON_CODE='$KODE_CRUD' and year(WORK_DATE)='$cYEAR' and month(WORK_DATE)='$cMONTH'");
		header('location:tb_shift.php');
		break;
}
?>
