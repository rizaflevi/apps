<?php
//	prs_rp_overtime.php

	include_once "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();

	$cHELP_FILE = 'Doc/Laporan - Lembur.pdf';
    $cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	$cHEADER		= S_MSG('PL31','Laporan Lembur Karyawan');

	$dTGL_AWAL	= date('Y-m-d');
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
	if (SYS_ROWS($qSCOPE)>0) $cScope .= " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR='')";
	
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

	APP_LOG_ADD($cHEADER, 'View via : prs_rp_overtime.php');
	$can_ADD = TRUST($cUSERCODE, 'PRS_PRESENT_ADD');
	$can_UPD = TRUST($cUSERCODE, 'PRS_PRESENT_UPD');
	$can_EXCEL = TRUST($cUSERCODE, 'PRS_OVERTIME_EXCEL');

	//query lama
	// $qOVT=OpenTable('RpOvertime', "A.APP_CODE='$cAPP_CODE' and date(OVT_START)>='".$dTGL_AWAL."' and date(OVT_START)<='".$dTGL_AKHIR."' ".($cFILTER_CUSTOMER=='' ? UserScope($cUSERCODE) : " and F.CUST_CODE='$cFILTER_CUSTOMER'"));
    
	// SYS_QUERY("select

	//query baru
	$qOVT2 = "
		select 
		A.PRSON_CODE, 
		A.OVT_START, A.OVT_END, 
		A.OVT_NOTE, 
		A.OVT_MINUTE,C.PRSON_NAME, 
		F.CUST_CODE, P6.CUST_NAME, 
		F.KODE_LOKS, LOCS.LOKS_NAME, 
		F.JOB_CODE, JOB.JOB_NAME, LOC.UTC_OFFSET
		from prs_overtime A 
		left join ( select PRSON_CODE from prs_person_main where APP_CODE='$cAPP_CODE' and DELETOR='') B ON A.PRSON_CODE=B.PRSON_CODE
		left join (select PEOPLE_CODE, PEOPLE_NAME as PRSON_NAME from people where APP_CODE='$cAPP_CODE') C ON A.PRSON_CODE=C.PEOPLE_CODE
		left join ( select PRSON_CODE, JOB_CODE, KODE_LOKS, CUST_CODE from prs_person_occupation where APP_CODE='$cAPP_CODE'  and DELETOR='') F ON A.PRSON_CODE=F.PRSON_CODE
		left join ( select CUST_CODE, CUST_NAME, CUST_GROUP from tb_customer where APP_CODE='$cAPP_CODE' and DELETOR='') P6 ON F.CUST_CODE=P6.CUST_CODE
		left join ( select LOKS_CODE, LOKS_NAME from prs_locs where APP_CODE='$cAPP_CODE' and DELETOR='') LOCS on F.KODE_LOKS=LOCS.LOKS_CODE
		left join ( select JOB_CODE, JOB_NAME from prs_tb_occupation where APP_CODE='$cAPP_CODE' and DELETOR='') JOB ON F.JOB_CODE=JOB.JOB_CODE
		LEFT JOIN prs_locs LOC ON LOC.LOKS_CODE = F.KODE_LOKS
		WHERE 1=1
		AND A.APP_CODE='$cAPP_CODE'
		AND date(A.OVT_START)>='$dTGL_AWAL'
		AND date(A.OVT_START)<='$dTGL_AKHIR'
	";
	
	if ($cFILTER_CUSTOMER=='') {
		$qOVT2 .= UserScope($cUSERCODE);
	} else {
		$qOVT2 .= " AND F.CUST_CODE='$cFILTER_CUSTOMER'";
	}

	if($ada_OUTSOURCING>0 && $cFILTER_CUSTOMER=='') {
		$qOVT2 .= " AND F.CUST_CODE=''";
	}

	$qOPN_TBLbar = SYS_QUERY($qOVT2);
	if (!$qOPN_TBLbar) {
		print_r2 ($qOVT2);
	}
	
	
	DEF_WINDOW($cHEADER, 'collapse');
		$aACT = ($can_ADD==1 ? ['<a href="prs_tr_present.php"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
		if($can_EXCEL==1)
			array_push($aACT, '<a href="prs_overtime_xl.php?_d1='.$dTGL_AWAL.'&_d2='.$dTGL_AKHIR.'&_c='.$cFILTER_CUSTOMER.'"<i class="fa fa-solid fa-file-excel"></i>   EKSPOR KE EXCEL</a>&nbsp');
		TFORM($cHEADER, '', $aACT, $cHELP_FILE, '*');
		echo '<div class="row">';
		echo '<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">';
		echo '<label class="form-label-700" for="d1">Tanggal awal</label>';
		echo '<input type="date" name="d1" id="d1" class="form-control" value="'.$dTGL_AWAL.'" onchange="FILT_OV(this.value, \''.$dTGL_AKHIR.'\', \''.$cFILTER_CUSTOMER.'\')">';
		echo '</div>';

		echo '<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">';
		echo '<label class="form-label-700" for="d2">Tanggal Akhir</label>';
		echo '<input type="date" name="d2" id="d2" class="form-control" value="'.$dTGL_AKHIR.'" onchange="FILT_OV(\''.$dTGL_AWAL.'\', this.value, \''.$cFILTER_CUSTOMER.'\')">';
		echo '</div>';
		
			
			// INP_DATE([2,2,3,6], '900', '', $dTGL_AKHIR, '', '', '', '', '', "FILT_OV('".$dTGL_AWAL."', this.value, '".$cFILTER_CUSTOMER."')");

			//versi lama
			// LABEL([1,2,3,6], '700', S_MSG('RS02','Tanggal'));
			// INP_DATE([2,2,3,6], '900', '', $dTGL_AWAL, '', '', '', '', '', "FILT_OV(this.value, '".$dTGL_AKHIR."', '".$cFILTER_CUSTOMER."')");
			// LABEL([1,2,3,6], '700', S_MSG('RS14','s/d'), '', 'right');
			// INP_DATE([2,2,3,6], '900', '', $dTGL_AKHIR, '', '', '', '', '', "FILT_OV('".$dTGL_AWAL."', this.value, '".$cFILTER_CUSTOMER."')");
			
			if($ada_OUTSOURCING>0) {
				echo '<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">';
				LABEL([], '700', $cCUSTOMER, '', 'right', [], 'ctmr');
				SELECT([], 'PILIH_CUSTOMER', "FILT_OV('$dTGL_AWAL', '$dTGL_AKHIR', this.value)", 'ctmr');
				echo "<option value=''  > All</option>";
				while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
					if($aCUSTOMER['CUST_CODE']==$cFILTER_CUSTOMER){
						echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$cFILTER_CUSTOMER' >$aCUSTOMER[CUST_NAME] - $aCUSTOMER[CUST_CODE] ($aCUSTOMER[employee_count] TAD)</option>";
					} else
						echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME] - $aCUSTOMER[CUST_CODE] ($aCUSTOMER[employee_count] TAD)</option>";
				}
				echo '</select>';
				echo '</div>';
			}
			echo '</div>';

			CLEAR_FIX();
			TDIV();
				TABLE('example');
					$aHEAD = [S_MSG('PA02','Kode Peg'), S_MSG('PA03','Nama Pegawai'), S_MSG('RS02','Tanggal'), S_MSG('PA71','Hari'), 'Masuk', 'Pulang', S_MSG('PA72','Jam'), S_MSG('PA98','Ket'), S_MSG('PA43','Jabatan')];
					$aALIGN= [0,0,2,2,2,2,2,0,0];
					if($ada_OUTSOURCING) {
						array_push($aHEAD, $cCUSTOMER);
						array_push($aHEAD, $cLOKASI);
						array_push($aALIGN, 0);
						array_push($aALIGN, 0);
					}
					THEAD($aHEAD, '', [], '*');
					$nSERVER_TIME=7;		// server time (utc +7 jakarta / wita)
					echo '<tbody>';
						while($aOVT=SYS_FETCH($qOPN_TBLbar)) { //loop data lembur
							$dDATE = substr($aOVT['OVT_START'],0,10); //potong string depan format 0000-00-00
							$cHREFF="<a href='prs_tr_present.php?_a=".md5('up_da_te')."&_p=".md5($aOVT['PRSON_CODE'])."&_d=".date("d/m/Y", strtotime($dDATE))."'>";
							$aHREFF=[$cHREFF, $cHREFF, '', '', '', '', '', '', ''];
							if($ada_OUTSOURCING) {
								array_push($aHREFF, '');
								array_push($aHREFF, '');
							}
							$nHARI = date("w", strtotime($dDATE)); //kolom ke 4 convert date jadi nama hari
							$cNOTE = DECODE($aOVT['OVT_NOTE']); //keterangan lembur, jarang diisi
							$cPERSON = $aOVT['PRSON_CODE']; //kolom pertama
							// $qREPORT	= OpenTable('PrsReport', "PRSON_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and date(REP_TIME) = '$dDATE' and DELETOR=''");
							// $aREP_ABSEN = SYS_FETCH($qREPORT);
							// $cREP_ABSEN = ($aREP_ABSEN ? $aREP_ABSEN['REP_CONTENT'] : '');
							$cSHIFT_CODE = Get_Personal_Schedule_Code($cPERSON, $dDATE); // mostly irregular shift employee, spt satpam
							//	sys_function.php
							// function Get_Personal_Schedule_Code($_cPERSON, $_dDATE) {
							// 	$gAPP_CODE = $_SESSION['data_FILTER_CODE']; //APP_CODE & DELETOR
							//	table prs_schedule
							// 	$qSCHEDULE = OpenTable('RegSchedule', "PERSON_CODE='$_cPERSON' and WORK_DATE='$_dDATE' and APP_CODE='$gAPP_CODE' and DELETOR=''");
							// 	$aSCHEDULE = SYS_FETCH($qSCHEDULE);
							// 	if(SYS_ROWS($qSCHEDULE)>0)
							// 		return ($qSCHEDULE ? $aSCHEDULE['SHIFT_CODE'] : '');
							// }

							if ($cSHIFT_CODE=='') { 
								$cSHIFT_CODE = Get_Group_Schedule_Code($aOVT['CUST_CODE'], $aOVT['KODE_LOKS'], $aOVT['JOB_CODE'], $dDATE);
							}
							// function Get_Group_Schedule_Code($_cCUST='', $_cLOC='', $_cJOB='', $_dDate='') {
							// 	$gAPP_CODE = $_SESSION['data_FILTER_CODE'];
							// 	if ($_dDate=='') $_dDate=date("Y/m/d");
							// 	$cSCHEDULE = '';
							// 	$qHOLIDAY = OpenTable('TbHoliday', " START_DATE<='$_dDate' and FINISH_DT>='$_dDate' and APP_CODE='$gAPP_CODE' and DELETOR=''");
							// 	if(SYS_ROWS($qHOLIDAY)==0){
							// 		$qGROUP_SCHEDULE = OpenTable('PrsGroupSch', "SCH_CUST='$_cCUST' and SCH_LOC='$_cLOC' and SCH_JOB='$_cJOB' and APP_CODE='$gAPP_CODE' and DELETOR=''");
							// 		if (SYS_ROWS($qGROUP_SCHEDULE)>0) {
							// 			$aGROUP_SCHEDULE = SYS_FETCH($qGROUP_SCHEDULE);
							// 			$cSCHEDULE = $aGROUP_SCHEDULE['SHIFT_CODE'];
							// 		} else {
							// 			$qGROUP_SCHEDULE = OpenTable('PrsGroupSch', "SCH_CUST='$_cCUST' and SCH_LOC='$_cLOC' and APP_CODE='$gAPP_CODE' and DELETOR=''");
							// 			if (SYS_ROWS($qGROUP_SCHEDULE)>0) {
							// 				$aGROUP_SCHEDULE = SYS_FETCH($qGROUP_SCHEDULE);
							// 				$cSCHEDULE = $aGROUP_SCHEDULE['SHIFT_CODE'];
							// 			} else {
							// 				$qGROUP_SCHEDULE = OpenTable('PrsGroupSch', "SCH_CUST='$_cCUST' and SCH_JOB='$_cJOB' and APP_CODE='$gAPP_CODE' and DELETOR=''");
							// 				if (SYS_ROWS($qGROUP_SCHEDULE)>0) {
							// 					$aGROUP_SCHEDULE = SYS_FETCH($qGROUP_SCHEDULE);
							// 					$cSCHEDULE = $aGROUP_SCHEDULE['SHIFT_CODE'];
							// 				} else {
							// 					$qGROUP_SCHEDULE = OpenTable('PrsGroupSch', "SCH_CUST='$_cCUST' and APP_CODE='$gAPP_CODE' and DELETOR=''");
							// 					if (SYS_ROWS($qGROUP_SCHEDULE)>0) {
							// 						$aGROUP_SCHEDULE = SYS_FETCH($qGROUP_SCHEDULE);
							// 						$cSCHEDULE = $aGROUP_SCHEDULE['SHIFT_CODE'];
							// 					}
							// 				}
							// 			}
							// 		}
							// 	}
							// 	return $cSCHEDULE;
							// }






							// if ($cSHIFT_CODE!='') {
							// 	$qTZ = OpenTable('Timezone', "DAYL_CODE='$cSHIFT_CODE' and APP_CODE='$cAPP_CODE'");
							// }
						
							
							$qTIMEZONE	= OpenTable('Timezone', "A.DAYL_CODE='$cSHIFT_CODE' and A.APP_CODE='$cAPP_CODE'  and A.DELETOR=''");
							if (SYS_ROWS($qTIMEZONE)>0) {
								$aTIMEZONE	= SYS_FETCH($qTIMEZONE);
								$nCONVERSI_TIME = ($aTIMEZONE ? $aTIMEZONE['TIME_TO_UTC'] : 7 ) - $nSERVER_TIME; //mis. 
							}

							$cOVT_IN=substr($aOVT['OVT_START'],11,5); //11,5 = hh:mm
							if ($cOVT_IN!=''){
								$nABS_PULANG = (int) substr($cOVT_IN,0,2) + $nCONVERSI_TIME;
								$cOVT_IN = str_pad($nABS_PULANG , 2 , "0" , STR_PAD_LEFT).':'.substr($cOVT_IN,3,2);
							}//kolom masuk
							
							$cOVT_OUT=substr($aOVT['OVT_END'],11,5);
							if ($cOVT_OUT!='' && $cOVT_OUT!='00:00'){
								$nABS_PULANG = (int) substr($cOVT_OUT,0,2) + $nCONVERSI_TIME;
								$cOVT_OUT = str_pad($nABS_PULANG , 2 , "0" , STR_PAD_LEFT).':'.substr($cOVT_OUT,3,2);
							}//kolom pulang


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
								array_push($aDETIL, DECODE($aOVT['CUST_NAME'])); //tambah td baru sebelah kanan $aDETIL
								array_push($aDETIL, $aOVT['LOKS_NAME']); //tambah td baru sebelah kanan $aDETIL setelah nama customer
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
		eTFORM('*');
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
