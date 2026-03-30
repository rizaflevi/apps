<?php
//	prs_rp_overtime.php

	include_once "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();

	// $cHELP_FILE = 'Doc/Laporan - Lembur.pdf';
    $cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	// $cHEADER		= S_MSG('PL31','Laporan Lembur Karyawan');
	APP_LOG_ADD('Laporan Lembur Karyawan', 'View via : prs_rp_overtime2.php');

	$dTGL_AWAL	= date('Y-m-d'); //why?
	$dTGL_AKHIR = date('Y-m-d');

	if (isset($_GET['_d1'])) $dTGL_AWAL=$_GET['_d1'];
	if (isset($_GET['_d2'])) $dTGL_AKHIR=$_GET['_d2'];

    $qMAIN_MENU=OpenTable('Main_Menu', "APP_CODE='$cAPP_CODE' and link='prs_resign_group.php'");
    $ada_OUTSOURCING=(SYS_ROWS($qMAIN_MENU)>0 ? 1 : 0);
	if($ada_OUTSOURCING>0) {
		$cCUSTOMER 	= S_MSG('RS04','Customer');
		$cLOKASI	= S_MSG('PF16','Lokasi');
	}
	$aNAMA_HARI		= array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', '');

	$cScope = "APP_CODE='$cAPP_CODE' and DELETOR=''";
	$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	if (SYS_ROWS($qSCOPE)>0) $cScope .= " and CUST_CODE in 
	(select USER_CUST 
	from prs_user_scope 
	where USER_CODE='$cUSERCODE' 
	and APP_CODE='$cAPP_CODE' 
	and DELETOR='')";
	
	//query lama
	// $qCUSTOMER=OpenTable('TbCustomer', $cScope, '', 'CUST_NAME');

	//customer aktif only
	$qCUSTOMER = SYS_QUERY("
		SELECT COUNT(m.PRSON_CODE) AS employee_count, c.*
		FROM prs_person_main m
		LEFT JOIN prs_person_occupation o ON o.PRSON_CODE = m.PRSON_CODE
		LEFT JOIN tb_customer c ON c.CUST_CODE = o.CUST_CODE        
		WHERE 1=1
		AND m.APP_CODE='$cAPP_CODE' 
		AND m.DELETOR='' 
		AND m.PRSON_SLRY<2 
		AND o.APP_CODE='$cAPP_CODE'
		AND m.PRSON_CODE NOT IN (
		SELECT PRSON_CODE
		FROM prs_resign
		WHERE APP_CODE='$cAPP_CODE' AND DELETOR='')
		GROUP BY CUST_CODE
		ORDER BY CUST_NAME
	");

	$cFILTER_CUSTOMER='';
	// if($aCUST = SYS_FETCH($qCUSTOMER)) $cFILTER_CUSTOMER=$aCUST['CUST_CODE'];
	if (isset($_GET['_c'])) $cFILTER_CUSTOMER=$_GET['_c'];

	$cFILTER_JABATAN='';
	$cFILTER_LOKASI=(isset($_GET['_l']) ? $_GET['_l'] : '');

	$can_ADD = TRUST($cUSERCODE, 'PRS_PRESENT_ADD');
	$can_UPD = TRUST($cUSERCODE, 'PRS_PRESENT_UPD');
	$can_EXCEL = TRUST($cUSERCODE, 'PRS_OVERTIME_EXCEL');

	$qOVT=OpenTable('RpOvertime', "A.APP_CODE='$cAPP_CODE' 
	and date(OVT_START)>='".$dTGL_AWAL."' 
	and date(OVT_START)<='".$dTGL_AKHIR."' "
	.($cFILTER_CUSTOMER=='' ? UserScope($cUSERCODE) : " and F.CUST_CODE='$cFILTER_CUSTOMER'"));
    DEF_WINDOW($cHEADER, 'collapse');
		// $aACT = ($can_ADD==1 ? ['<a href="prs_tr_present.php"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
		// if($can_EXCEL==1)
		// 	array_push($aACT, );
		// TFORM($cHEADER, '', $aACT, $cHELP_FILE, '*');

		echo '<section id="main-content" class="sidebar_shift">
		<section class="wrapper main-wrapper" style="">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="page-title">
				<div class="pull-left">
					<h2 class="title">Laporan Lembur Karyawan222</h2>
				</div>
				<div class="pull-right">
					<a href="prs_overtime_xl2.php?_d1='.$dTGL_AWAL.'&_d2='.$dTGL_AKHIR.'&_c='.$cFILTER_CUSTOMER.'"
					<i class="fas fa-file-excel"></i> Ekspor ke Excel</a>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>

		<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
			<section class="box ">
				<div class="content-body">
					<div class="row">';


			// header
			LABEL([1,2,3,6], '700', S_MSG('RS02','Tanggal'));
			INP_DATE([2,2,3,6], '900', '', $dTGL_AWAL, '', '', '', '', '', "FILT_OV(this.value, '".$dTGL_AKHIR."', '".$cFILTER_CUSTOMER."')");
			LABEL([1,2,3,6], '700', S_MSG('RS14','s/d'), '', 'right');
			INP_DATE([2,2,3,6], '900', '', $dTGL_AKHIR, '', '', '', '', '', "FILT_OV('".$dTGL_AWAL."', this.value, '".$cFILTER_CUSTOMER."')");
			
			
			if($ada_OUTSOURCING>0) {
				LABEL([2,3,3,6], '700', $cCUSTOMER, '', 'right');
				SELECT([3,3,3,6], 'PILIH_CUSTOMER', "FILT_OV('$dTGL_AWAL', '$dTGL_AKHIR', this.value)");
				echo "<option value=''  > All</option>";
				while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
					if($aCUSTOMER['CUST_CODE']==$cFILTER_CUSTOMER){
						echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$cFILTER_CUSTOMER' >$aCUSTOMER[CUST_NAME] ($aCUSTOMER[employee_count] TAD)</option>";
					} else
						echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME] ($aCUSTOMER[employee_count] TAD)</option>";
				}
				echo '</select>';
			}



			CLEAR_FIX();
			
			TDIV();

			//mulai tabel
				TABLE('example');
					$aHEAD = [S_MSG('PA02','Kode Peg'), S_MSG('PA03','Nama Pegawai'), S_MSG('RS02','Tanggal'), S_MSG('PA71','Hari'), 'Masuk', 'Pulang', S_MSG('PA72','Jam'), S_MSG('PA98','Ket'), S_MSG('PA43','Jabatan')];
					$aALIGN= [0,0,2,2,2,2,2,0,0];
					if($ada_OUTSOURCING) {
						array_push($aHEAD, $cCUSTOMER);
						array_push($aHEAD, $cLOKASI);
						array_push($aALIGN, 0);
						array_push($aALIGN, 0);
					}//kolom customer dan lokasi


					THEAD($aHEAD, '', [], '*');
					echo '<tbody>';
						while($aOVT=SYS_FETCH($qOVT)) {
							$dDATE = substr($aOVT['OVT_START'],0,10);
							$cHREFF="<a href='prs_tr_present.php?_a=".md5('up_da_te')."&_p=".md5($aOVT['PRSON_CODE'])."&_d=".date("d/m/Y", strtotime($dDATE))."'>";
							$aHREFF=[$cHREFF, $cHREFF, '', '', '', '', '', '', ''];
							if($ada_OUTSOURCING) {
								array_push($aHREFF, '');
								array_push($aHREFF, '');
							}
							$nHARI = date("w", strtotime($dDATE)); //kolom 3
							$cNOTE = DECODE($aOVT['OVT_NOTE']); //kolom4 keterangan
							$cPERSON = $aOVT['PRSON_CODE']; //kolom1 kode pegawai
							$qREPORT	= OpenTable('PrsReport', "PRSON_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and date(REP_TIME) = '$dDATE' and DELETOR=''");
							$aREP_ABSEN = SYS_FETCH($qREPORT);
							$cREP_ABSEN = ($aREP_ABSEN ? $aREP_ABSEN['REP_CONTENT'] : '');
							$cSHIFT_CODE = Get_Personal_Schedule_Code($cPERSON, $dDATE);
							if ($cSHIFT_CODE=='') {
								$cSHIFT_CODE = Get_Group_Schedule_Code($aOVT['CUST_CODE'], $aOVT['KODE_LOKS'], $aOVT['JOB_CODE'], $dDATE);
							}
							if ($cSHIFT_CODE!='') {
								$qTZ = OpenTable('Timezone', "DAYL_CODE='$cSHIFT_CODE' and APP_CODE='$cAPP_CODE'");
							}
						
							$nSERVER_TIME=7;		// server time
							$qTIMEZONE	= OpenTable('Timezone', "A.DAYL_CODE='$cSHIFT_CODE' and A.APP_CODE='$cAPP_CODE'  and A.DELETOR=''");
							if (SYS_ROWS($qTIMEZONE)>0) {
								$aTIMEZONE	= SYS_FETCH($qTIMEZONE);
								$nCONVERSI_TIME = ($aTIMEZONE ? $aTIMEZONE['TIME_TO_UTC'] : 7 ) - $nSERVER_TIME;
							}
							$cOVT_IN=substr($aOVT['OVT_START'],11,5);
							if ($cOVT_IN!=''){
								$nABS_PULANG = (int) substr($cOVT_IN,0,2) + $nCONVERSI_TIME;
								$cOVT_IN = str_pad($nABS_PULANG , 2 , "0" , STR_PAD_LEFT).':'.substr($cOVT_IN,3,2);
							}
							
							$cOVT_OUT=substr($aOVT['OVT_END'],11,5);
							if ($cOVT_OUT!='' && $cOVT_OUT!='00:00'){
								$nABS_PULANG = (int) substr($cOVT_OUT,0,2) + $nCONVERSI_TIME;
								$cOVT_OUT = str_pad($nABS_PULANG , 2 , "0" , STR_PAD_LEFT).':'.substr($cOVT_OUT,3,2);
							}
							$nJAM=intval($aOVT['OVT_MINUTE']/60);
							$aDETIL = [$aOVT['PRSON_CODE'], DECODE($aOVT['PRSON_NAME']), date("d-M-Y", strtotime($dDATE)), $aNAMA_HARI[$nHARI], $cOVT_IN, $cOVT_OUT, $nJAM, $cNOTE, $aOVT['JOB_NAME']];
							$aTYPE  = ['', '', '', '', 'text', 'text', 'text', '', '', ''];
							$aWIDTH = [0, 0, 0, 0, 70, 70, 50, 0, 0, 0];
							$aNAME  = ['', '', '', '', 'OVT_IN', 'OVT_OUT', 'OVT_MINUTE', '', '', ''];
							$aMASK  = ['', '', '', '', '99:99', '99:99', '99', '', '', ''];
							$aPATR  = ['', '', '', '', '', '', '', '', '', ''];
							$aBLUR  = ['', '', '', '', "IN_EDIT(this, 'OVT_START', '$cPERSON', '$dDATE')", "IN_EDIT(this, 'OVT_END', '$cPERSON', '$dDATE')", "IN_EDIT(this, 'OVT_MINUTE', '$cPERSON', '$dDATE')", '', '', ''];
							$aOVAL  = ['', '', '', '', $cOVT_IN, $cOVT_OUT, $nJAM, '', '', ''];
							$aOFCS  = ['', '', '', '', "OFF_INS(this)", "OFF_INS(this)", "OFF_INS(this)", '', '', ''];
							if($ada_OUTSOURCING) {
								array_push($aDETIL, DECODE($aOVT['CUST_NAME']));
								array_push($aDETIL, $aOVT['LOKS_NAME']);
								array_push($aTYPE, '');		array_push($aTYPE, '');
								array_push($aWIDTH, '');	array_push($aWIDTH, '');
								array_push($aNAME, '');		array_push($aNAME, '');
								array_push($aMASK, '');		array_push($aMASK, '');
								array_push($aBLUR, '');		array_push($aBLUR, '');
								array_push($aOVAL, '');		array_push($aOVAL, '');
								array_push($aOFCS, '');		array_push($aOFCS, '');
							}
							TDETAIL($aDETIL, $aALIGN, '*', $aHREFF, $aWIDTH, ($can_UPD ? $aTYPE : []), $aNAME, $aMASK, $aPATR, $aBLUR, $aOVAL, $aOFCS);
						}
					echo '</tbody>';
				eTABLE();
			eTDIV();
		echo '</div></div></section></div></section></section>';
	END_WINDOW();
	SYS_DB_CLOSE($DB2);
?>

<script>
function FILT_OV(_p1, _p2, _c) {
	window.location.assign("?_d1="+_p1 + "&_d2="+_p2 + "&_c="+_c);
}

function IN_EDIT(_EDITABLE, _COLUMN, _PERSON, _DATE) {
	if($(_EDITABLE).attr('data-old_value') === _EDITABLE.innerHTML)
		return false;
	// $(_EDITABLE).css("background","#FFF url(/assets/preloader.gif) no-repeat right");
	$.ajax({
		url: "prs_rp_ovt_upd.php",
		type: "POST",
		dataType: "json",
		data:'_f='+_COLUMN+'&_v='+_EDITABLE.value+'&_p='+_PERSON+'&_d='+_DATE,
		success: function(response) {
		// set updated value as old value
		$(_EDITABLE).attr('data-old_value',_EDITABLE.innerHTML);
		$(_EDITABLE).css("background","#FDFDFD");
		},
		error: function () {
			console.log("errr");
			return false;
		}
	});
}

</script>
