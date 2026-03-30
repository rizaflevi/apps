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
		$nREC=0;
		for($nIDX=0; $nIDX<$nlARR; $nIDX++) {
			$nREC++;
			$cTGHN  =$sheet[$nIDX]['A'];
			$id     =$sheet[$nIDX]['B'];
			$cNAMA  =encode_string($sheet[$nIDX]['C']);
			$dTGL    =$sheet[$nIDX]['F'];
			$dDUE    =$sheet[$nIDX]['G'];
			$cNOMINAL =$sheet[$nIDX]['O'];
			$nVALUE = 	trim($cNOMINAL);
			$cVALUE = trim(str_replace(',', '', $nVALUE));

			$cTGL = substr($dTGL, 0, 10);
			$cDUE = substr($dDUE, 0, 10);
			$qCEK_HDR=OpenTable('SchRevHdr', "APP_CODE='$cAPP_CODE' and REV_ID='$cTGHN' and REC_ID not in ( select DEL_ID from logs_delete)");
			if($aCEK_TGHN=SYS_FETCH($qCEK_HDR)) {
				$cREC_ID=$aCEK_TGHN['REC_ID'];
			} else {
				$nRec_id = date_create()->format('Uv')+$nREC;
				$cREC_ID=(string)$nRec_id;
				RecCreate('SchRevHdr', ['REV_ID', 'REV_STUDENT', 'REV_DATE', 'REV_DUE', 'REV_VALUE', 'REC_ID', 'ENTRY', 'APP_CODE'], 
					[$cTGHN, $id, $cTGL, $cDUE, $cVALUE, $cREC_ID, $cUSERCODE, $cAPP_CODE]);
			}
			RecDelete('SchRevDtl', "APP_CODE='$cAPP_CODE' and REV_HDR_ID='$cTGHN' and REC_ID not in ( select DEL_ID from logs_delete)");
			$qREV_AR=OpenTable('SchRevAr', "APP_CODE='$cAPP_CODE' and REV_ACCOUNT>'' and AR_ACCOUNT>'' and REV_COLUMN>'' and REC_ID not in ( select DEL_ID from logs_delete)");
			while($aREC_REV_AR=SYS_FETCH($qREV_AR)) {
				$nVALUE = trim($sheet[$nIDX][trim($aREC_REV_AR['REV_COLUMN'])]);
				$cVALUE = trim(str_replace(',', '', $nVALUE));
				if($cVALUE>'0') {
					$nREC++;
					$nRec_id = date_create()->format('Uv')+$nREC;
					$cREC_ID=(string)$nRec_id;
					RecCreate('SchRevDtl', ['REV_HDR_ID', 'REV_DESC', 'REV_AR_ID', 'REV_VALUE', 'REC_ID', 'ENTRY', 'APP_CODE'], 
						[$cTGHN, $aREC_REV_AR['DESCRIPTION'], $aREC_REV_AR['REV_COLUMN'], $cVALUE, $cREC_ID, $cUSERCODE, $cAPP_CODE]);
				}
			}
//			$nIDX++;
		}

		unlink($_FILES['namafile']['name']);
		APP_LOG_ADD($cHEADER);
		header('location:sch_upload_edupay.php?_a=done_upload');
		MSG_INFO('Import Excel XLS file success, silahkan lihat hasil nya pada transaksi pendapatan');
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
