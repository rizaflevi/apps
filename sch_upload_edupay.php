<?php
//  sch_upload_edupay.php
//	TODO : admin blm masuk

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
	include "sysfunction.php";
	set_error_handler('errHandle');
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];

	$cHEADER	= S_MSG('TS9F','Upload Edupay');
	$cHELP_FILE = 'Doc/Proses - Upload Edupay.pdf';

	$cACTION='';
	$cACTION= (isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');

switch($cACTION){
	default:
		DEF_WINDOW($cHEADER);
		TFORM($cHEADER, '', [], $cHELP_FILE);
?>
		<div class="alert alert-primary alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<h4>Proses ini digunakan untuk meng upload data excel yang didapat dari Aplikasi EduPay.</h4>
			<p>Silahkan pilih file excel yang akan di upload, pastikan file yang akan di upload valid.</p>
			<p>Klik Continue untuk melanjutkan, atau Close untuk batal.</p>

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
					<button type="submit" class="btn btn-default" onclick="window.location.href='?_a=proc_upload'">Continue</button>
					<button type="button" class="btn btn-default" onclick="window.location.href='prs_dashboard.php'">Close</button>
				</p>
			</form>
		</div>
<?php
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		break;

	case 'proc_upload':
//		RecDelete('RepAbsen', "APP_CODE='$cUSERCODE'");
		require 'vendor/autoload.php';
    
		$type         =explode(".",$_FILES['namafile']['name']);
			
		if(empty($_FILES['namafile']['name'])) {
			MSG_INFO('Oops! Please fill all / select file ...');
			return;
		} 
		if(strtolower(end($type)) !='xlsx'){
			MSG_INFO('Oops! Please upload only Excel XLSx file ...');
			return;
		}
			
		$target = basename($_FILES['namafile']['name']) ;
		move_uploaded_file($_FILES['namafile']['tmp_name'], $target);
	
		$reader    =new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load($_FILES['namafile']['name']);
		$sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
		array_shift($sheet);
		$nlARR=sizeof($sheet);
		$cCOL_INVOICE=$cCOL_TANGGAL=$cCOL_NOINDUK=$cCOL_NAMA=$cCOL_SEKOLAH=$cCOL_KELAS=$cCOL_JUMLAH='';
		$qCOLUMN=OpenTable('SchRevAr', "A.NON_VALUE=1 and A.APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		while($aCOLUMN=SYS_FETCH($qCOLUMN))	{
			if($aCOLUMN['DESCRIPTION']=='Invoice')	$cCOL_INVOICE=$aCOLUMN['REV_COLUMN'];
			if($aCOLUMN['DESCRIPTION']=='Tanggal')	$cCOL_TANGGAL=$aCOLUMN['REV_COLUMN'];
			if($aCOLUMN['DESCRIPTION']=='NoInduk')	$cCOL_NOINDUK=$aCOLUMN['REV_COLUMN'];
			if($aCOLUMN['DESCRIPTION']=='Nama')		$cCOL_NAMA=$aCOLUMN['REV_COLUMN'];
			if($aCOLUMN['DESCRIPTION']=='Sekolah')	$cCOL_SEKOLAH=$aCOLUMN['REV_COLUMN'];
			if($aCOLUMN['DESCRIPTION']=='Kelas')	$cCOL_KELAS=$aCOLUMN['REV_COLUMN'];
			if($aCOLUMN['DESCRIPTION']=='Jumlah')	$cCOL_JUMLAH=$aCOLUMN['REV_COLUMN'];
			if($aCOLUMN['DESCRIPTION']=='JtTempo')	$cCOL_JT_TMP=$aCOLUMN['REV_COLUMN'];
		}
		$nREC=0;
		for($nIDX=0; $nIDX<$nlARR; $nIDX++) {
			$nREC++;
			$cTGHN  = $sheet[$nIDX][$cCOL_INVOICE];
			$cNO_INDUK = $sheet[$nIDX][$cCOL_NOINDUK];
			$cNAMA  = ENCODE($sheet[$nIDX][$cCOL_NAMA]);
			$dTGL   = $sheet[$nIDX][$cCOL_TANGGAL];
			$dDUE   = $sheet[$nIDX][$cCOL_JT_TMP];
			$cNOMINAL	= $sheet[$nIDX][$cCOL_JUMLAH];
			$nVALUE = trim($cNOMINAL);
			$cVALUE = trim(str_replace(',', '', $nVALUE));
			if($cCOL_SEKOLAH>'')	$cSEKOLAH	= $sheet[$nIDX][$cCOL_SEKOLAH];
			if($cCOL_KELAS>'')		$cKELAS		= trim(ENCODE($sheet[$nIDX][$cCOL_KELAS]));

			$cTGL = substr($dTGL, 0, 10);
			$cDUE = substr($dDUE, 0, 10);
			$qCEK_HDR=OpenTable('SchRevHdr', "APP_CODE='$cAPP_CODE' and REV_ID='$cTGHN' and REC_ID not in ( select DEL_ID from logs_delete)");
			if($aCEK_TGHN=SYS_FETCH($qCEK_HDR)) {
				$cREC_ID=$aCEK_TGHN['REC_ID'];
				RecUpdate('SchRevHdr', ['REV_STUDENT', 'REV_DATE', 'REV_DUE', 'REV_VALUE'], [$cNO_INDUK, $cTGL, $cDUE, $cVALUE], "REC_ID='$cREC_ID'");
			} else {
				$nRec_id = date_create()->format('Uv')+$nREC;
				$cREC_ID=(string)$nRec_id;
				RecCreate('SchRevHdr', ['REV_ID', 'REV_STUDENT', 'REV_DATE', 'REV_DUE', 'REV_VALUE', 'REC_ID', 'ENTRY', 'APP_CODE'], 
					[$cTGHN, $cNO_INDUK, $cTGL, $cDUE, $cVALUE, $cREC_ID, $cUSERCODE, $cAPP_CODE]);
			}
			RecDelete('SchRevDtl', "APP_CODE='$cAPP_CODE' and REV_HDR_ID='$cTGHN' and REC_ID not in ( select DEL_ID from logs_delete)");
			$qREV_AR=OpenTable('SchRevAr', "APP_CODE='$cAPP_CODE' and REV_ACCOUNT>'' and AR_ACCOUNT>'' and REV_COLUMN>'' and REC_ID not in ( select DEL_ID from logs_delete)");
			while($aREC_REV_AR=SYS_FETCH($qREV_AR)) {
				$nVALUE = trim($sheet[$nIDX][trim($aREC_REV_AR['REV_COLUMN'])]);
				$cVALUE = trim(str_replace(',', '', $nVALUE));
				if($aREC_REV_AR['FIXED_VALUE']>0) $cVALUE = (string)$aREC_REV_AR['FIXED_VALUE'];
				if($cVALUE>'0') {
					$nREC++;
					$nRec_id = date_create()->format('Uv')+$nREC;
					$cREC_ID=(string)$nRec_id;
					RecCreate('SchRevDtl', ['REV_HDR_ID', 'REV_DESC', 'REV_AR_ID', 'REV_VALUE', 'REC_ID', 'ENTRY', 'APP_CODE'], 
						[$cTGHN, $aREC_REV_AR['DESCRIPTION'], $aREC_REV_AR['REV_COLUMN'], $cVALUE, $cREC_ID, $cUSERCODE, $cAPP_CODE]);
				}
			}
			$cKD_KELAS='';
			$qKELAS=OpenTable('TbArea', "trim(NAMA_AREA)='$cKELAS' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
			if($aKELAS=SYS_FETCH($qKELAS)) {
				$cKD_KELAS=$aKELAS['KODE_AREA'];
			} else {
				RecCreate('TbArea', ['KODE_AREA', 'NAMA_AREA', 'ENTRY', 'REC_ID', 'APP_CODE'], [ENCODE($cKELAS), ENCODE($cKELAS), $cUSERCODE, NowMSecs(), $cAPP_CODE]);
			}
			$qSTUDENT=OpenTable('TbCustomer', "CUST_CODE='$cNO_INDUK' and APP_CODE='$cAPP_CODE' and DELETOR=''");
			if($aSTUDENT=SYS_FETCH($qSTUDENT)) {
				RecUpdate('TbCustomer', ['CUST_NAME', 'CUST_GROUP', 'CUST_AREA', 'UP_DATE', 'UPD_DATE'], 
					[$cNAMA, $cSEKOLAH, $cKD_KELAS, $cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and CUST_CODE='$cNO_INDUK'");
			} else {
				RecCreate('TbCustomer', ['CUST_CODE', 'CUST_NAME', 'CUST_GROUP', 'CUST_AREA', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
				[$cNO_INDUK, $cNAMA, $cSEKOLAH, $cKD_KELAS, $cAPP_CODE, $_SESSION['gUSERCODE'], date('Y-m-d H:i:s')]);
			}
		}

		unlink($_FILES['namafile']['name']);
		APP_LOG_ADD($cHEADER);
		header('location:sch_upload_edupay.php?_a=done_upload');
		MSG_INFO('Import Excel XLS file success, silahkan lihat hasil nya pada transaksi pendapatan');
		break;
	case 'done_upload':
		DEF_WINDOW($cHEADER);
		TFORM($cHEADER, '', [], $cHELP_FILE);
		TDIV();
?>
		<div class="alert alert-success alert-dismissible fade in">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<strong>Sukses:</strong> Upload data telah selesai.
		</div>
		<p>
			<button type="button" class="btn btn-default" onclick="window.location.href='prs_dashboard.php'">Close</button>
		</p>
<?php
		eTDIV();
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
	    break;
}
?>
