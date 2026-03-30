<?php
//	tb_patrol_checks.php //

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
$cUSERCODE      = $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Tabel - Lokasi Patrol Cek.pdf';

$cHEADER 		= S_MSG('FE11','Tabel Lokasi Patrol Check');
	$cKD_ID  		= S_MSG('F003','Kode');
	$cNM_ID  		= S_MSG('F004','Nama');
	$cKETRANGAN 	= S_MSG('CL03','Keterangan');
	$cLATITUDE 	    = S_MSG('TG56','Latitude');
	$cLONGITUDE     = S_MSG('TG57','Longitude');
	$cDISTANCE      = S_MSG('TG58','Jarak');
	$cNOTE  	    = S_MSG('TI16','Note');
	$cDAFTAR		= S_MSG('FE13','Daftar Patrol Check');
    $cADD_REC       = S_MSG('FE15','Tambah Data Lokasi Patrol Check');

	$cSAVE_DATA		= S_MSG('F301','Save');
	$cCLOSE_DATA	= S_MSG('F302','Close');

	$cTTIP_KODE		= S_MSG('FE31','Setiap Lokasi Patrol Check harus di beri kode, supaya bisa diakses berdasarkan kode');
	$cTTIP_NAMA		= S_MSG('FE32','Nama Lokasi Patrol Check');

    $aHDR = [$cKD_ID, $cNM_ID, $cKETRANGAN, $cLATITUDE, $cLONGITUDE];

$qQUERY=OpenTable('PatrolCheck');
$can_CREATE = TRUST($cUSERCODE, 'TB_PCHECK_1ADD');

$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

switch($cACTION){
	default:
        UPDATE_DATE();
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV(12,12,12,12);
					TABLE('example');
						THEAD($aHDR, '', [], '*');
							while($aPC=SYS_FETCH($qQUERY)) {
                                $cREFF="<a href=?_a=".md5('up_dat3')."&_r=".$aPC['REC_ID'].">";
                                $aCOL=[DECODE($aPC['PC_CODE']), DECODE($aPC['PC_NAME']), DECODE($aPC['PC_DESC']), $aPC['LATITUDE'], $aPC['LONGITUDE']];
                                $aREFF=[$cREFF, $cREFF, '', '', ''];
								TDETAIL($aCOL, [], '*', $aREFF);
							}
                    eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
        break;

	case md5('cr34t3'):
        UPDATE_DATE();
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=DB_ADD', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKD_ID);
					INPUT('text', [2,2,2,3], '900', 'ADD_PC_CODE', '', 'focus', '', '', 10, '', 'fix', $cTTIP_KODE);
					LABEL([3,3,3,6], '700', $cNM_ID);
					INPUT('text', [6,6,6,6], '900', 'ADD_PC_NAME', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([3,3,3,6], '700', $cLATITUDE);
					INPUT('text', [3,3,3,6], '900', 'ADD_LATTITUDE', '', '', 'fdecimal', 'right', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cLONGITUDE);
					INPUT('text', [3,3,3,6], '900', 'ADD_LONGITUDE', '', '', 'fdecimal', 'right', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cDISTANCE);
					INPUT('text', [1,1,1,6], '900', 'ADD_DISTANCE', '', '', 'fdecimal', 'right', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNOTE);
					INPUT('text', [8,8,8,6], '900', 'ADD_PC_DESC', '', '', '', '', 0, '', 'fix');
					$cSAVE		= ($can_CREATE ? S_MSG('F301','Save') : '');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;


case md5('up_dat3'):
    UPDATE_DATE();
	$cEDIT_TBL		= S_MSG('FE14','Edit Tabel Patrol Check');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	
	$can_UPDATE = TRUST($cUSERCODE, 'TB_PCHECK_2UPD');
	$can_DELETE = TRUST($cUSERCODE, 'TB_PCHECK_3DEL');
	$cREC_ID = $_GET['_r'];
	$qQUERY=OpenTable('PatrolCheck', "REC_ID='$cREC_ID'");
	$REC_PC=SYS_FETCH($qQUERY);
	DEF_WINDOW($cEDIT_TBL);
        $aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DEL_PC').'&_id='. $cREC_ID. '" onClick="return confirm('. "'". S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?'). "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
        TFORM($cEDIT_TBL, '?_a=rubah&_id='.$cREC_ID, $aACT, $cHELP_FILE);
            TDIV();
                LABEL([3,3,3,6], '700', $cKD_ID);
                INPUT('text', [2,2,2,6], '900', 'UPD_CODE', $REC_PC['PC_CODE'], '', '', '', 0, 'disabled', 'fix');
                LABEL([3,3,3,6], '700', $cNM_ID);
                INPUT('text', [6,6,6,6], '900', 'UPD_NAME', DECODE($REC_PC['PC_NAME']), '', '', '', 0, '', 'fix');
                LABEL([3,3,3,6], '700', $cLATITUDE);
                INPUT('text', [2,2,2,6], '900', 'UPD_LATITUDE', $REC_PC['LATITUDE'], '', 'fdecimal', 'right', 0, '', 'fix');
                LABEL([3,3,3,6], '700', $cLONGITUDE);
                INPUT('text', [2,2,2,6], '900', 'UPD_LONGITUDE', $REC_PC['LONGITUDE'], '', 'fdecimal', 'right', 0, '', 'fix');
                LABEL([3,3,3,6], '700', $cDISTANCE);
                INPUT('text', [1,1,1,6], '900', 'UPD_DISTANCE', $REC_PC['DISTANCE'], '', 'fdecimal', 'right', 0, '', 'fix');
                LABEL([3,3,3,6], '700', $cNOTE);
                INPUT('text', [6,6,6,6], '900', 'UPD_PC_DESC', $REC_PC['PC_DESC'], '', '', '', 0, '', 'fix');
                echo '<br>';
                SAVE(($can_UPDATE ? $cSAVE_DATA : ''));
            eTDIV();
        eTFORM();
    END_WINDOW();
    SYS_DB_CLOSE($DB2);	
    break;
case 'DB_ADD':

	$cCODE = $_POST['ADD_PC_CODE'];
	if($cCODE==''){
        MSG_INFO(S_MSG('H229','Kode Identitas belum diisi'));
		return;
	}
	$qQUERY=OpenTable('PatrolCheck', "APP_CODE='$cAPP_CODE' and PC_CODE='$cCODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	if(SYS_ROWS($qQUERY)>0){
        MSG_INFO(S_MSG('FE34','Kode Lokasi sudah ada'));
		header('location:tb_patrol_checks.php');
		return;
	} else {
		$cPC_CODE = ENCODE($_POST['ADD_PC_CODE']);
		$cPC_NAME = ENCODE($_POST['ADD_PC_NAME']);
		$cPC_DESC = ENCODE($_POST['ADD_PC_DESC']);
		$nLATT = ($_POST['ADD_LATTITUDE']=='' ? 0 : (integer)$_POST['ADD_LATTITUDE']);
		$nLONG = ($_POST['ADD_LONGITUDE']=='' ? 0 : (integer)$_POST['ADD_LONGITUDE']);
        $nDIST = ($_POST['ADD_DISTANCE']=='' ? 0 : (integer)$_POST['ADD_DISTANCE']);
		RecCreate('PatrolCheck', ['REC_ID', 'PC_CODE', 'PC_NAME', 'LATITUDE', 'LONGITUDE', 'DISTANCE', 'PC_DESC', 'APP_CODE', 'ENTRY'],
			[NowMSecs(), $cPC_CODE, $cPC_NAME, $nLATT, $nLONG, $nDIST, $cPC_DESC, $cAPP_CODE, $cUSERCODE]);
	}
	header('location:tb_patrol_checks.php');
	break;
case 'rubah':
	$REC_ID=$_GET['_id'];
	$cPC_NAME = ENCODE($_POST['UPD_NAME']);
	$cLATT = $_POST['UPD_LATITUDE'];
	$cLONG = $_POST['UPD_LONGITUDE'];
	$cDIST = $_POST['UPD_DISTANCE'];
	$cPC_DESC = ENCODE($_POST['UPD_PC_DESC']);
	RecUpdate('PatrolCheck', ['PC_NAME', 'LATITUDE', 'LONGITUDE', 'DISTANCE', 'PC_DESC'], [$cPC_NAME, $cLATT, $cLONG, $cDIST, $cPC_DESC], "REC_ID='$REC_ID'");
	header('location:tb_patrol_checks.php');
	break;
case md5('DEL_PC'):
	RecSoftDel($_GET['_id']);
	header('location:tb_patrol_checks.php');
	break;
}
?>

