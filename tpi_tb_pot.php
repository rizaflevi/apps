<?php
//	tpi_tb_pot.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 	= $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/TPI - Tabel - Auction Fee.pdf';
	$cHEADER = S_MSG('TE01','Tabel Potongan Lelang');

	$qQUERY=OpenTable('TbAuctionFee', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

	$cKODE_TBL 	= S_MSG('TE02','Kode Potongan');
	$cNAMA_TBL 	= S_MSG('TE03','Nama Potongan');
	$cPOTONGAN 	= S_MSG('TE04','Potongan (%)');
	$cDAFTAR	= S_MSG('TE09','Daftar Potongan');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$cTTIP_KODE	= S_MSG('TE21','Setiap Jenis Potongan diberi kode untuk keperluan akses dan pengurutan');
	$cTTIP_NAMA	= S_MSG('TE22','Nama Potongan sbg keterangan');
	$cTTIP_PRSN	= S_MSG('TE23','Besar potongan dalam satuan persen');
	
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
		$can_CREATE = TRUST($cUSERCODE, 'TPI_TB_POT_1ADD');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL, $cPOTONGAN], '', [0,0,1]);
						echo '<tbody>';
								while($aREC_POT=SYS_FETCH($qQUERY)) {
								echo '<tr>';
									echo '<td style="width: 1px;"></td>';
									echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_POT['KODE_POT'])."'>".decode_string($aREC_POT['KODE_POT'])."</a></span></td>";
									echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_POT['KODE_POT'])."'>".decode_string($aREC_POT['NAMA_POT'])."</a></span></td>";
									echo '<td align="right">'.number_format($aREC_POT['PERSEN'], 2).'</a></span></td>';
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
		$cADD_REC	= S_MSG('TE06','Tambah');
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'ADD_KODE_POT', '', 'focus', '', '', 0, '', 'fix', $cTTIP_KODE);
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [8,8,8,6], '900', 'ADD_NAMA_POT', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([3,3,3,6], '700', $cPOTONGAN);
					INPUT('number', [1,1,1,6], '900', 'ADD_PERSEN', 0, '', 'fdecimal', 'right', 0, '', 'fix', $cTTIP_PRSN);
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END2WINDOW();		
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('up_d4t3'):
		$cEDIT_TBL	= S_MSG('TE07','Edit Tabel Potongan');
		$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$can_UPDATE = TRUST($cUSERCODE, 'TPI_TB_POT_2UPD');
		$qQUERY=OpenTable('TbAuctionFee', "APP_CODE='$cAPP_CODE' and md5(KODE_POT)='$_GET[_r]' and REC_ID not in ( select DEL_ID from logs_delete)");
		$REC_POT=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_area').'&id='. md5($REC_POT['KODE_POT']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_id='.$REC_POT['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_KODE_POT', $REC_POT['KODE_POT'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [8,8,8,8], '900', 'EDIT_NAMA_POT', DECODE($REC_POT['NAMA_POT']), 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cPOTONGAN);
					INPUT('text', [1,1,1,6], '900', 'EDIT_PERSEN', $REC_POT['PERSEN'], '', 'fdecimal', 'right', 0, '', 'fix');
					SAVE($can_UPDATE==1 ? $cSAVE : '');
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

case 'tambah':
	$NOW = date("Y-m-d H:i:s");
	$cKODE_POT	= ENCODE($_POST['ADD_KODE_POT']);	
	if($cKODE_POT==''){
		MSG_INFO(S_MSG('TE31','Kode Potongan belum diisi'));
		return;
	}
	$cQUERY="select * from tb_bidd_pot where APP_CODE='$cAPP_CODE' and DELETOR='' and KODE_POT='$cKODE_POT'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		MSG_INFO(S_MSG('TE32','Kode Potongan sudah ada'));
		return;
		header('location:tpi_tb_pot.php');
	} else {
		$cNAMA_POT	= ENCODE($_POST['ADD_NAMA_POT']);
		$cQUERY="insert into tb_bidd_pot set KODE_POT='$cKODE_POT', NAMA_POT='$cNAMA_POT', PERSEN=0".str_replace(',', '', $_POST['ADD_PERSEN']);
		$cQUERY.=", ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW', APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:tpi_tb_pot.php');
	}
	SYS_DB_CLOSE($DB2);	
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cNAMA_POT	= ENCODE($_POST['EDIT_NAMA_POT']);
	$cQUERY ="update tb_bidd_pot set NAMA_POT='$cNAMA_POT', ";
	$cQUERY.=" PERSEN=0".str_replace(',', '', $_POST['EDIT_PERSEN']).", UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cAPP_CODE' and KODE_POT='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:tpi_tb_pot.php');
	SYS_DB_CLOSE($DB2);	
	break;

case md5('del_area'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update tb_bidd_pot set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cAPP_CODE' and md5(KODE_POT)='$KODE_CRUD'";
	$qQUERY =SYS_QUERY($cQUERY);
	SYS_DB_CLOSE($DB2);	
	header('location:tpi_tb_pot.php');
}
?>

