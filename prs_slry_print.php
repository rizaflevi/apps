<?php
//	prs_slry_print.php
//	Slip Gaji pdf

require('vendor/fpdf/fpdf.php');
include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
$cUSERCODE 	= $_SESSION['gUSERCODE'];
$sPERIOD1	= $_SESSION['sCURRENT_PERIOD'];
if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];

$l_SELECTED=false;
$aaPCODE	= $_SESSION['aPCODE'];
for ($iPRS=0; $iPRS<sizeof($aaPCODE); $iPRS++) {
	if(isset($_POST['MARK_'.$aaPCODE[$iPRS]]))
	$l_SELECTED=true;
	$cPERSON=$aaPCODE[$iPRS];
}
if(!$l_SELECTED) {
	MSG_INFO('Tidak ada data yg dipilih');
	return;
}

$cHEADER	= 'SLIP GAJI';
$cFORM		= S_PARA('FORMAT_SLIP', 'SLIP');
$qTB_BILL=OpenTable('TbBillPrintHdr', "PRNTR_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
if(SYS_ROWS($qTB_BILL)==0)  {
	MSG_INFO('No record');
	return;
}
$cFILE_PDF = S_PARA('PSLIP_FOLDER', 'pdf/').$cAPP_CODE.'_PRS_SLIP_'.$sPERIOD1.'_';	// tdk kebaca ??
$aREC_TB_BILL=SYS_FETCH($qTB_BILL);
$aPERSON=[];

for ($iPRS=0; $iPRS<sizeof($aaPCODE); $iPRS++) {
	if(isset($_POST['MARK_'.$aaPCODE[$iPRS]])) {
		$cPERSON=$aaPCODE[$iPRS];
		array_push($aPERSON, $cPERSON);
		$cPDF_PERSON=$cFILE_PDF.$cPERSON.'.pdf';

		$cFILT_DATA = "A.APP_CODE='" . $cAPP_CODE ."' and A.DELETOR='' and PRSON_SLRY<2 and R.RESIGN_DATE is NULL";
		$qPRINT=OpenTable('PersonSalary', $cFILT_DATA." and A.PRSON_CODE='$cPERSON'");

		$nSTART_ROW = 0;
		while($aREC_PERSON=SYS_FETCH($qPRINT)) {
			$cPAPER = 'A4';
			if($aREC_TB_BILL['PAPER_SIZE']>'')   $cPAPER = $aREC_TB_BILL['PAPER_SIZE'];
			$cORIEN = 'P';
			if($aREC_TB_BILL['ORIENTATION']>'')   $cORIEN = $aREC_TB_BILL['ORIENTATION'];
			$pdf=new FPDF($cORIEN, 'mm', $cPAPER);
			$qTEMP=OpenTable('RepAbsen', "PRSON_CODE='$cPERSON' and APP_CODE='$cUSERCODE'");
			if($aTEMP=SYS_FETCH($qTEMP)) {
				$R_fdf=PRINT_HDR($pdf, $cFORM);
				$R_fdf->Text(GET_FORMAT($cFORM, 'TGGL_LEFT')+27, GET_FORMAT($cFORM, 'KONST4_ROW'), $sPERIOD1);
				$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
				$cFONT_CODE = GET_FORMAT($cFORM, 'DETAIL_DTA_FONT_CODE');
				$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
				if(SYS_ROWS($qFONT)>0) {
					$aFONT = SYS_FETCH($qFONT);
					$cFONT_NAME = $aFONT['NAME'];
					if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
					if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
					if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
					$nFONT_SIZE = $aFONT['SIZE'];
				}
				$R_fdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
				$R_fdf->Text((integer)GET_FORMAT($cFORM, 'DETAIL1_DATA_COL'), (integer)GET_FORMAT($cFORM, 'DETAIL1_DATA_ROW'), DECODE($aREC_PERSON['PRSON_NAME']));
				$R_fdf->Text((integer)GET_FORMAT($cFORM, 'DETAIL2_DATA_COL'), (integer)GET_FORMAT($cFORM, 'DETAIL2_DATA_ROW'), DECODE($aREC_PERSON['JOB_NAME']));
				$R_fdf->Text((integer)GET_FORMAT($cFORM, 'DETAIL3_DATA_COL'), (integer)GET_FORMAT($cFORM, 'DETAIL3_DATA_ROW'), CVR($aTEMP['P_LATE']));
			}
			$nDT=3;
			$qTEMP=OpenTable('PrApBln', "AP_MASUK=0 and INVOICE='$aREC_PERSON[PRSON_CODE]' and APP_CODE='$cUSERCODE'");
			$tTUNJANGAN = 0;
			while($aTUNJANGAN=SYS_FETCH($qTEMP)) {
				$nDT++;
				$cDT=(string)$nDT;

				$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 11;
				$cFONT_CODE = GET_FORMAT($cFORM, 'DETAIL_HDR_FONT_CODE');
				$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
				if(SYS_ROWS($qFONT)>0) {
					$aFONT = SYS_FETCH($qFONT);
					$cFONT_NAME = $aFONT['NAME'];
					if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
					if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
					if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
					$nFONT_SIZE = $aFONT['SIZE'];
				}
				$R_fdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
				$R_fdf->Text(GET_FORMAT($cFORM, 'DETAIL'.$cDT.'_LABEL_COL'), GET_FORMAT($cFORM, 'DETAIL'.$cDT.'_DATA_ROW'), $aTUNJANGAN['NAMA_VND']);

				$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
				$cFONT_CODE = GET_FORMAT($cFORM, 'DETAIL_DTA_FONT_CODE');
				$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
				if(SYS_ROWS($qFONT)>0) {
					$aFONT = SYS_FETCH($qFONT);
					$cFONT_NAME = $aFONT['NAME'];
					if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
					if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
					if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
					$nFONT_SIZE = $aFONT['SIZE'];
				}
				$R_fdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
				$R_fdf->Text(GET_FORMAT($cFORM, 'DETAIL'.$cDT.'_DATA_COL'), GET_FORMAT($cFORM, 'DETAIL'.$cDT.'_DATA_ROW'), CVR($aTUNJANGAN['JUMLAH']));
				$tTUNJANGAN += $aTUNJANGAN['JUMLAH'];
			}
			$nDT=6;
			$qTEMP=OpenTable('PrApBln', "AP_MASUK=1 and INVOICE='$aREC_PERSON[PRSON_CODE]' and APP_CODE='$cUSERCODE'");
			$tPOTONGAN = 0;
			while($aPOTONGAN=SYS_FETCH($qTEMP)) {
				$nDT++;
				$cDT=(string)$nDT;

				$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
				$cFONT_CODE = GET_FORMAT($cFORM, 'DETAIL_HDR_FONT_CODE');
				$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
				if(SYS_ROWS($qFONT)>0) {
					$aFONT = SYS_FETCH($qFONT);
					$cFONT_NAME = $aFONT['NAME'];
					if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
					if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
					if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
					$nFONT_SIZE = $aFONT['SIZE'];
				}
				$R_fdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
				$R_fdf->Text(GET_FORMAT($cFORM, 'DETAIL'.$cDT.'_LABEL_COL'), GET_FORMAT($cFORM, 'DETAIL'.$cDT.'_DATA_ROW'), $aPOTONGAN['NAMA_VND']);

				$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
				$cFONT_CODE = GET_FORMAT($cFORM, 'DETAIL_DTA_FONT_CODE');
				$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
				if(SYS_ROWS($qFONT)>0) {
					$aFONT = SYS_FETCH($qFONT);
					$cFONT_NAME = $aFONT['NAME'];
					if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
					if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
					if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
					$nFONT_SIZE = $aFONT['SIZE'];
				}
				$R_fdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
				$R_fdf->Text(GET_FORMAT($cFORM, 'DETAIL'.$cDT.'_DATA_COL'), GET_FORMAT($cFORM, 'DETAIL'.$cDT.'_DATA_ROW'), CVR($aPOTONGAN['JUMLAH']));
				$tPOTONGAN += $aPOTONGAN['JUMLAH'];
			}
			$nBASIC_SALARY = intval($aTEMP['P_LATE']);
			$nTOTAL=[$nBASIC_SALARY+$tTUNJANGAN, $tPOTONGAN, $nBASIC_SALARY+$tTUNJANGAN-$tPOTONGAN];
			for($I = 0; $I<3; $I++):
				$J=(string)$I+1;
				if(GET_FORMAT($cFORM, 'TOTAL'.$J.'_STATUS')=='1') {
					$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
					$cFONT_CODE = GET_FORMAT($cFORM, 'TOTAL'.$J.'_FONT_CODE');
					$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
					if(SYS_ROWS($qFONT)>0) {
						$aFONT = SYS_FETCH($qFONT);
						$cFONT_NAME = $aFONT['NAME'];
						if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
						if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
						if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
						$nFONT_SIZE = $aFONT['SIZE'];
					}
					$R_fdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
					$R_fdf->Text(intval(GET_FORMAT($cFORM, 'TOTAL'.$J.'_DATA_COL')), intval(GET_FORMAT($cFORM, 'TOTAL'.$J.'_DATA_ROW')), CVR(intval($nTOTAL[$I])));
				}
			endfor;
			if(GET_FORMAT($cFORM, 'IMAGE_STATUS')=='1') {
				$cLOGO_FILE = 'data/images/'.$cAPP_CODE.'_KOP.jpg';
				$nLOGO_LEFT = intval(GET_FORMAT($cFORM, 'IMAGE_LEFT'));
				$nLOGO_TOP = intval(GET_FORMAT($cFORM, 'IMAGE_TOP'));
				$nIMG_WID = intval(GET_FORMAT($cFORM, 'IMAGE_WIDTH'));
				$nIMG_HIG = intval(GET_FORMAT($cFORM, 'IMAGE_HEIGTH'));
				if($nLOGO_LEFT>0 && $nLOGO_TOP>0 && file_exists($cLOGO_FILE))
					$R_fdf->Image($cLOGO_FILE, $nLOGO_LEFT, $nLOGO_TOP, $nIMG_WID, $nIMG_HIG);
			}
			// if($_SERVER['HTTP_HOST']=='localhost') {
			// 	$R_fdf->Output($cPDF_PERSON, 'I');
			// } else {
				// $R_fdf->Output($cPDF_PERSON, 'F');
				$R_fdf->Output('F', $cPDF_PERSON, true);
				// $R_fdf->Output();
			// }
			$R_fdf->Close();
			APP_LOG_ADD($cHEADER, 'Print : '.$cPERSON);
		}
	}
}

// echo "<script>	window.history.back();	</script>";


   for ($P=0; $P<sizeof($aPERSON); $P++) {
		$cPDF_PERSON=$cFILE_PDF.$aPERSON[$P].'.pdf';
	?>
	
       <iframe src="<?php echo $cPDF_PERSON?>" width=45% height=50%></iframe>
    
	<?php
}
?>
