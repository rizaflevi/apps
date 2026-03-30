<?php
//  sch_upload_edurec.php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
$cUSERCODE 	= $_SESSION['gUSERCODE'];

$cHEADER	= S_MSG('TS9M','Upload Penerimaan Edupay');
$cHELP_FILE = 'Doc/Proses - Penerimaan Edupay.pdf';
$cACTION=(isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');

switch($cACTION){
	default:
		DEF_WINDOW($cHEADER);
		TFORM($cHEADER, '', [], $cHELP_FILE);
		TDIV();
?>
			<div class="alert alert-primary alert-dismissible fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4>Proses ini digunakan untuk meng upload data excel PENERIMAAN yang didapat dari Aplikasi EduPay.</h4>
				<p>Silahkan pilih file excel yang akan di upload, pastikan file yang akan di upload valid.</p>
				<p>Klik Continue untuk melanjutkan, atau Cancel untuk batal.</p>

				<br></br>
				<form action="?_a=proc_upload" method="POST" enctype="multipart/form-data">
					<table border="0">
						<tr></tr><br>
						<tr>
							<td><input type="file" name="namafile" maxlength="255"/></td>
						</tr><br>
						<tr>
							<td></td>
							<td></td>
						</tr>
					</table><br>
					<p>
						<button type="submit" class="btn btn-default" onclick="window.location.href='?_a=upload'">Continue</button>
						<button type="button" class="btn btn-default" onclick="window.location.href='prs_dashboard.php'">Close</button>
					</p>
				</form>
			</div>
<?php
		eTDIV();
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		break;

	case 'proc_upload':
		require 'vendor/autoload.php';
		$cTYPE =explode(".",$_FILES['namafile']['name']);
		if (empty($_FILES['namafile']['name']))
			MSG_INFO('Oops! Silahkan pilih file dulu ...');
		else if(strtolower(end($cTYPE)) !='xlsx')
			MSG_INFO('Oops! Silahkan pilih hanya XLSx file ...');
		else {
			$target = basename($_FILES['namafile']['name']) ;
			move_uploaded_file($_FILES['namafile']['tmp_name'], $target);
			$reader    =new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$spreadsheet = $reader->load($_FILES['namafile']['name']);
			$sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
			$osheet = $spreadsheet->getActiveSheet();
			array_shift($sheet);
			$nlARR=sizeof($sheet);
			$qCOLUMN=OpenTable('SchReceiptCol', "APP_CODE='$cAPP_CODE' and REC_ACCOUNT='' and REC_ID not in ( select DEL_ID from logs_delete)");
			$cINVOICE=$cTANGGAL=$cNOINDUK=$cNAMA='';
			while($aCOLUMN=SYS_FETCH($qCOLUMN))	{
				if($aCOLUMN['DESCRIPTION']=='Invoice')	$cINVOICE=$aCOLUMN['REC_COLUMN'];
				if($aCOLUMN['DESCRIPTION']=='Tanggal')	$cTANGGAL=$aCOLUMN['REC_COLUMN'];
				if($aCOLUMN['DESCRIPTION']=='NoInduk')	$cNOINDUK=$aCOLUMN['REC_COLUMN'];
				if($aCOLUMN['DESCRIPTION']=='Nama')		$cNAMA=$aCOLUMN['REC_COLUMN'];
				if($aCOLUMN['DESCRIPTION']=='Sekolah')	$cSEKOLAH=$aCOLUMN['REC_COLUMN'];
				if($aCOLUMN['DESCRIPTION']=='Jumlah')	$nJUMLAH=$aCOLUMN['REC_COLUMN'];
			}
			if($cINVOICE=='') {	
				MSG_INFO('Kolom nomor invoice di excel belum sesuai');	return;	
			}
			if($cNOINDUK=='') {	
				MSG_INFO('Kolom nomor induk di excel belum sesuai');	return;	
			}
			if($cNAMA=='') {	
				MSG_INFO('Kolom nama siswa di excel belum sesuai');	return;	
			}
			for($nIDX=0; $nIDX<$nlARR; $nIDX++) {
				$cBANK	= $sheet[$nIDX]['E'];
				$qBANK=OpenTable('TbBank', "B_CODE='$cBANK' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
				if(SYS_ROWS($qBANK)==0) {
					MSG_INFO('Ada kode bank yang belum sesuai');	return;	
				}
				$cINV  	= $sheet[$nIDX][$cINVOICE];
				if(!$cINV) {
					MSG_INFO('Ada nomor invoice yang masih kosong');	return;	
				}
			}
			$cPICT_OR 	= S_PARA('PICT_OR', '999999');
			$nRec_id = date_create()->format('Uv');
			for($nIDX=0; $nIDX<$nlARR; $nIDX++) {
				$cINV  	= $sheet[$nIDX][$cINVOICE];
				if(!$cINV) $cINV='';
				$cINDUK = $sheet[$nIDX][$cNOINDUK];
				$cSTDN  = ENCODE($sheet[$nIDX][$cNAMA]);
				$dTGL   = $sheet[$nIDX][$cTANGGAL];
				$cBANK	= $sheet[$nIDX]['E'];
				if(empty($cBANK))	$cBANK='';
				$cTGL 	= date('Y-m-d', strtotime($dTGL));
				$cREC_NO= '';
				$qLAST	= OpenTable('TrReceiptHdr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and left(TGL_BAYAR,7)='".substr($cTGL,0,7)."'", '', "NO_TRM desc limit 1");
				$nLAST	= intval(substr($cTGL,0,4).substr($cTGL,5,2).'001');
				if($aREC_TERIMA1= SYS_FETCH($qLAST)) {
					$cLAST	= $aREC_TERIMA1['NO_TRM'];
					$nLAST	= intval($cLAST)+1;
				}
				$cREC_NO= str_pad((string)$nLAST, strlen($cPICT_OR), '0', STR_PAD_LEFT);
				$xREC_NO= $cREC_NO;

/*				$qCEK_REC=OpenTable('TrReceiptDtl', "APP_CODE='$cAPP_CODE' and NO_FAKTUR='$cINV' and REC_ID not in ( select DEL_ID from logs_delete)");
				if($aCEK_TGHN=SYS_FETCH($qCEK_REC))	{
					$cREC_NO=$aCEK_TGHN['NO_TRM'];
					RecUpdate('TrReceiptHdr', ['DESCRP', 'TGL_BAYAR', 'BANK'], [$cINDUK.' - '.$cSTDN, $cTGL, $cBANK], 
						"NO_TRM='$cREC_NO' and APP_CODE='$cAPP_CODE'");
					RecDelete('TrReceiptDtl', "NO_TRM='$cREC_NO' and APP_CODE='$cAPP_CODE'");
				} else {	*/
					$nRec_id++;	$cREC_ID=(string)$nRec_id;
					RecCreate('TrReceiptHdr', ['NO_TRM', 'DESCRP', 'TGL_BAYAR', 'BANK', 'REC_ID', 'ENTRY', 'APP_CODE'], 
						[$cREC_NO, $cINDUK.' - '.$cSTDN, $cTGL, $cBANK, $cREC_ID, $cUSERCODE, $cAPP_CODE]);
/*				}
				$qCEK_BANK=OpenTable('TrReceiptBank', "APP_CODE='$cAPP_CODE' and NO_TRM='$cREC_NO' and REC_ID not in ( select DEL_ID from logs_delete)");
				if($aCEK_BANK=SYS_FETCH($qCEK_BANK))	{
					if($cBANK>'') {
						RecUpdate('TrReceiptBank', ['DUE_DATE'], [$cTGL], "NO_TRM='$cREC_NO' and APP_CODE='$cAPP_CODE'");
					} else {
						RecSoftDel($aCEK_BANK['REC_ID']);
					}
				} else {
					$nRec_id++;	*/
					$cREC_ID=(string)$nRec_id;
					RecCreate('TrReceiptBank', ['NO_TRM', 'DUE_DATE', 'ENTRY', 'REC_ID', 'APP_CODE'], [$cREC_NO, $cTGL, $cUSERCODE, $cREC_ID, $cAPP_CODE]);
//				}
				$highestColumn = $osheet->getHighestDataColumn();
				$nCOL_NUMBER = LETTERS_TO_NUM($highestColumn);
				// print_r2($nCOL_NUMBER);
				$qREC_COL=OpenTable('SchReceiptCol', "APP_CODE='$cAPP_CODE' and REC_ACCOUNT>'' and BANK_CODE>'' and REC_COLUMN>'' and REC_ID not in ( select DEL_ID from logs_delete)");
				while($aREC_COLUMN=SYS_FETCH($qREC_COL)) {
					if(LETTERS_TO_NUM($aREC_COLUMN['REC_COLUMN'])<=$nCOL_NUMBER) {
						$nVALUE = trim($sheet[$nIDX][trim($aREC_COLUMN['REC_COLUMN'])]);
						$cVALUE = trim(str_replace(',', '', $nVALUE));
						if($aREC_COLUMN['FIXED_VALUE']>0)	 $cVALUE = (string)$aREC_COLUMN['FIXED_VALUE'];
						if($cVALUE>'0') {
							$nRec_id++;
							$cREC_ID=(string)$nRec_id;
							RecCreate('TrReceiptDtl', ['NO_TRM', 'KET', 'NO_FAKTUR', 'NILAI', 'ACCOUNT', 'REC_ID', 'ENTRY', 'APP_CODE'], 
								[$cREC_NO, $aREC_COLUMN['DESCRIPTION'], $cINV, $cVALUE, $aREC_COLUMN['REC_ACCOUNT'], $cREC_ID, $cUSERCODE, $cAPP_CODE]);
						}
					}
				}
			}
			MSG_INFO('Import Excel XLS file success, silahkan lihat hasil nya pada transaksi penerimaan');
			unlink($_FILES['namafile']['name']);
		}
		break;
	case 'done_upload':
		DEF_WINDOW($cHEADER);
		TFORM($cHEADER, '', [], $cHELP_FILE);
?>
		<div class="alert alert-success alert-dismissible fade in">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<strong>Sukses:</strong> Upload data telah selesai.
		</div>
		<p>
			<button type="button" class="btn btn-default" onclick="window.location.href='prs_dashboard.php'">Close</button>
		</p>
<?php
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
	    break;
}
?>
