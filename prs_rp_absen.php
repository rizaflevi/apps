<?php
//	prs_rp_absen.php //
//	Laporan absen pegawai
//	TODO : inline edit

	require_once "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	$cHEADER 		= S_MSG('PI21','Laporan Absen Karyawan');
	$cHELP_FILE 	= 'Doc/Laporan - Absen.pdf';
  
	$chKODE_PEG 	= S_MSG('PA02','Kode Peg');
	$chNAMA_PEG 	= S_MSG('PA03','Nama Pegawai');
	$chTELAT 		= S_MSG('PI24','Terlambat');
	$chCEPAT 		= S_MSG('PI29','Plg cpt');
	$cLEMBUR 		= S_MSG('PI25','Lembur');
	$cNAMA_HARI 	= S_MSG('PA71','Hari');
	$aNAMA_HARI		= array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', '');

	$chJAM_MASUK	= S_MSG('PI22','Masuk');
	// $chJAM_MASUK	= 'Jam Masuk';
	$chJAM_PULANG	= S_MSG('PI23','Pulang');
	$chTANGGAL 		= S_MSG('RS02','Tanggal');
	$cJAM			= S_MSG('PS29','Jam');
	$cCUSTOMER 		= S_MSG('RS04','Customer');
	$cLOKASI 		= S_MSG('PF16','Lokasi');
	$cJABATAN		= S_MSG('PF13','Jabatan');
	
	$cPAY_ROLL = S_PARA('PAY_ROLL','');
	$cJAM_KERJA_M = substr($cPAY_ROLL, 0,5);
	$cJAM_KERJA_K = substr($cPAY_ROLL, 5,5);

	$cPERIOD1=date("d/m/Y");
	$cPERIOD2=date("d/m/Y");
	$iOUTSOURCING=IS_OUTSOURCING($cAPP_CODE);

	if (isset($_GET['_d1'])) $cPERIOD1=$_GET['_d1'];
	if (isset($_GET['_d2'])) $cPERIOD2=$_GET['_d2'];
	$dPERIOD1=DMY_YMD($cPERIOD1);
	$dPERIOD2=DMY_YMD($cPERIOD2);

	$cFILTER_CUST=(isset($_GET['_c']) ? $cFILTER_CUST=$_GET['_c'] : '');
	$cFILTER_JABATAN=(isset($_GET['_j']) ? $cFILTER_JABATAN=$_GET['_j'] : '');
	$cFILTER_LOKASI=(isset($_GET['_l']) ? $cFILTER_LOKASI=$_GET['_l'] : '');
	$cRELOAD=(isset($_GET['_r']) ? $cRELOAD=$_GET['_r'] : '*');

	$cScope = "APP_CODE='$cAPP_CODE' and DELETOR=''";
	$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	if (SYS_ROWS($qSCOPE)>0) $cScope .= " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR='')";
	$qCUSTOMER=OpenTable('TbCustomer', $cScope, '', 'CUST_NAME');
	$aCUSTOMER=SYS_FETCH($qCUSTOMER);
	if ($cFILTER_CUST=='' && $aCUSTOMER)	$cFILTER_CUST=$aCUSTOMER['CUST_CODE'];

	$cPERSON_FILTER = "A.APP_CODE='$cAPP_CODE'". ($cFILTER_CUST!='' ? " and P6.CUST_CODE='$cFILTER_CUST'" : "") . " and A.DELETOR='' and A.PRSON_SLRY<2";
	if ($cFILTER_LOKASI!='') $cPERSON_FILTER.=" and P6.KODE_LOKS='$cFILTER_LOKASI'";
	if ($cFILTER_JABATAN!='') $cPERSON_FILTER.=" and J.JOB_CODE='$cFILTER_JABATAN'";
	$cPERSON_FILTER .= " and RESIGN_DATE is null";

	$qLOKASI		= OpenTable('PeoplePresence', $cPERSON_FILTER, 'P6.KODE_LOKS', 'L.LOKS_NAME');
	$qJABATAN		= OpenTable('PeoplePresence', $cPERSON_FILTER, "J.JOB_CODE", 'J.JOB_NAME');
	$qPERSON_MAIN	= OpenTable('PeoplePresence', $cPERSON_FILTER.UserScope($cUSERCODE));
	$s_PERSON = '';
	while($aREC_PERSON=SYS_FETCH($qPERSON_MAIN)){
		$s_PERSON.= "'".$aREC_PERSON['PRSON_CODE']."', ";
	}
	$s_PERSON.= "'x'";
	RecDelete('RepAbsen', "APP_CODE='$cUSERCODE'");
	$cQRY = "insert into prs_rabs (PRSON_CODE, ABSN_DATE, ABSN_MSK, APP_CODE) ";
	$cQRY .= "select PEOPLE_CODE, DATE(PPL_PRESENT), substr(PPL_PRESENT, 12,5), '". $cUSERCODE;
	$cQRY .= "' from people_present where PRESENT_CODE=0 and APP_CODE='$cAPP_CODE' and date(PPL_PRESENT) between '".$dPERIOD1."' and '".$dPERIOD2."' and DELETOR='' and PEOPLE_CODE in (".$s_PERSON.") group by PEOPLE_CODE, date(PPL_PRESENT), PRESENT_CODE";
	SYS_QUERY($cQRY);

	if($cFILTER_CUST) {
		$currentDate = strtotime($dPERIOD1);
		while ($currentDate <= strtotime($dPERIOD2)) {
			$cCURRENT_DATE=date('Y-m-d', $currentDate);
			$qPERSON_MAIN	= OpenTable('PeoplePresence', $cPERSON_FILTER.UserScope($cUSERCODE));
			while($aREC_PERSON=SYS_FETCH($qPERSON_MAIN)) {
				$cPERSON_CODE=$aREC_PERSON['PRSON_CODE'];
				$qRABS=OpenTable('RepAbsen', "ABSN_DATE='$cCURRENT_DATE)' and PRSON_CODE='$cPERSON_CODE' and APP_CODE='$cUSERCODE'");
				if(SYS_ROWS($qRABS)==0) {
					RecCreate('RepAbsen', ['PRSON_CODE', 'ABSN_DATE', 'APP_CODE'], [$cPERSON_CODE, $cCURRENT_DATE, $cUSERCODE]);
				}
			}
			$currentDate = strtotime('+1 day', $currentDate);
		}
	}	

	$qPRESENCE	= OpenTable('ArrPresence', "PRESENT_CODE=1 and APP_CODE='$cAPP_CODE' and date(PPL_PRESENT) between '".$dPERIOD1."' and '".$dPERIOD2."' and DELETOR='' and PEOPLE_CODE in (".$s_PERSON.")");
	while($a_ABSEN=SYS_FETCH($qPRESENCE)){
		$cPEOPLE_CODE = $a_ABSEN['PEOPLE_CODE'];
		$dTGL_ABSEN = $a_ABSEN['TGL'];
		$cJAM_ABSEN = substr($a_ABSEN['PPL_PRESENT'], 11,5);
		$nKODE_ABSN = $a_ABSEN['PRESENT_CODE'];
		$qREP_ABSN=OpenTable('RepAbsen', "APP_CODE='$cUSERCODE' and PRSON_CODE='$cPEOPLE_CODE' and ABSN_DATE='$dTGL_ABSEN'");
		if (SYS_ROWS($qREP_ABSN)==0) {
			RecCreate('RepAbsen', ['PRSON_CODE', 'ABSN_DATE', 'APP_CODE'], [$cPEOPLE_CODE, $dTGL_ABSEN, $cUSERCODE]);
		}

		if ($nKODE_ABSN==1) RecUpdate('RepAbsen', ['ABSN_KLR'], [$cJAM_ABSEN], "APP_CODE='$cUSERCODE' and PRSON_CODE='$cPEOPLE_CODE' and ABSN_DATE='$dTGL_ABSEN'");
	}
	$qOVERTIME	= OpenTable('PrsOvertime', "APP_CODE='$cAPP_CODE' and date(OVT_START) between '".$dPERIOD1."' and '".$dPERIOD2."' and PRSON_CODE in (".$s_PERSON.") ");
	while($aOVT=SYS_FETCH($qOVERTIME)){
		$dTGL_ABSEN=substr($aOVT['OVT_START'], 0,10);
		$qREP_ABSN=OpenTable('RepAbsen', "APP_CODE='$cUSERCODE' and PRSON_CODE='$aOVT[PRSON_CODE]' and ABSN_DATE='$dTGL_ABSEN'");
		if (SYS_ROWS($qREP_ABSN)==0) {
			RecCreate('RepAbsen', ['PRSON_CODE', 'ABSN_DATE', 'APP_CODE'], [$aOVT['PRSON_CODE'], $dTGL_ABSEN, $cUSERCODE]);
		}
		RecUpdate('RepAbsen', ['OVERTIME_IN', 'OVERTIME_OUT', 'OVERTIME_QTY'], [substr($aOVT['OVT_START'],11,5), substr($aOVT['OVT_END'],11,5), intVal($aOVT['OVT_MINUTE']/60)], 
			"APP_CODE='$cUSERCODE' and PRSON_CODE='$aOVT[PRSON_CODE]' and ABSN_DATE='$dTGL_ABSEN'");
	}
	$can_ADD = TRUST($cUSERCODE, 'PRS_PRESENT_ADD');
	$can_UPD = TRUST($cUSERCODE, 'PRS_PRESENT_UPD');
	$can_EXCEL = TRUST($cUSERCODE, 'PRS_PRESENT_EXCEL');

    DEF_WINDOW($cHEADER, 'collapse');
		$aACT=[];
		if ($can_ADD==1) 	array_push($aACT, '<a href="prs_tr_present.php"><i class="fa fa-plus-square"></i>Add</a>&nbsp&nbsp&nbsp');
		if ($can_EXCEL==1)	array_push($aACT, '<a href="prs_absen_excel.php?_d1='.DMY_YMD($cPERIOD1).'&_d2='.DMY_YMD($cPERIOD2).'&_p='.$s_PERSON.'"><i class="fa fa-file-excel-o"></i>Excel</a>&nbsp');
		TFORM($cHEADER, '', $aACT, $cHELP_FILE, '*');
			TDIV();
				LABEL([1,2,2,6], '700', $chTANGGAL, '', 'right'); 
				INP_DATE([2,2,2,6], '900', '', $cPERIOD1, '', '', '', '', '', "FILT_ABSEN(this.value, '".$cPERIOD2."', '".$cFILTER_CUST."', '". $cFILTER_LOKASI. "', '".$cFILTER_JABATAN."', 'r')");
				if ($iOUTSOURCING and $aCUSTOMER) {
					LABEL([1,2,2,6], '700', $cCUSTOMER, '', 'right');
					SELECT([3,3,3,6], 'PILIH_CUSTOMER', "FILT_ABSEN('$cPERIOD1', '$cPERIOD2', this.value, '', '', 'r')");
						echo "<option value=''> All </option>";
							while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
								if($aCUSTOMER['CUST_CODE']==$cFILTER_CUST){
									echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$cFILTER_CUST' > $aCUSTOMER[CUST_NAME] - $aCUSTOMER[CUST_CODE]</option>";
								} else
									echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME] - $aCUSTOMER[CUST_CODE]</option>";
							}
					echo '</select>';
				}
				LABEL([1,2,2,6], '700', $cLOKASI, '', 'right');
				SELECT([2,3,3,6], 'PILIH_LOKASI', "FILT_ABSEN('$cPERIOD1', '$cPERIOD2', '$cFILTER_CUST', this.value, '$cFILTER_JABATAN', 'r')");
					echo "<option value=''  > All </option>";
						while($aLOKASI=SYS_FETCH($qLOKASI)){
							if($aLOKASI['LOKS_CODE']==$cFILTER_LOKASI){
								echo "<option value='$aLOKASI[LOKS_CODE]' selected='$cFILTER_LOKASI' >$aLOKASI[LOKS_NAME] - $aLOKASI[LOKS_CODE]</option>";
							} else
								echo "<option value='$aLOKASI[LOKS_CODE]'  >$aLOKASI[LOKS_NAME] - $aLOKASI[LOKS_CODE]</option>";
						}
				echo '</select>';
				CLEAR_FIX();
				LABEL([1,2,2,6], '700', S_MSG('RS14','s/d'), '', 'right');
				INP_DATE([2,2,2,6], '900', '', $cPERIOD2, '', '', '', '', '', "FILT_ABSEN('$cPERIOD1', this.value, '".$cFILTER_CUST."', '". $cFILTER_LOKASI. "', '".$cFILTER_JABATAN."', 'r')");
				LABEL([1,2,2,6], '700', $cJABATAN, '', 'right');
				SELECT([3,3,3,6], 'PILIH_JABATAN', "FILT_ABSEN('$cPERIOD1', '$cPERIOD2', '$cFILTER_CUST', '$cFILTER_LOKASI', this.value, 'r')");
					echo "<option value=''  > All </option>";
						while($aJABATAN=SYS_FETCH($qJABATAN)){
							if($aJABATAN['JOB_CODE']==$cFILTER_JABATAN){
								echo "<option value='$aJABATAN[JOB_CODE]' selected='$cFILTER_JABATAN' >$aJABATAN[JOB_NAME]</option>";
							} else
								echo "<option value='$aJABATAN[JOB_CODE]'  >$aJABATAN[JOB_NAME]</option>";
						}
				echo '</select>';
				CLEAR_FIX();
			?>

				<div class="content-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<table cellspacing="0" id="example" class="table table-small-font table-bordered table-striped">
								<thead>
									<tr>
										<th colspan="3"> </th>
										<th colspan="2" style="text-align: center">Jadwal</th>
										<th> </th>
										<th colspan="2" style="text-align: center">Hadir</th>
										<th colspan="2" style="text-align: center"></th>
										<th colspan="3" style="text-align: center"><?php echo $cLEMBUR?></th>
									</tr>
									<?php 
									/* echo THEAD([$chKODE_PEG, $chNAMA_PEG, $chTANGGAL, $chJAM_MASUK, $chJAM_PULANG, $cNAMA_HARI, $chJAM_MASUK, $chJAM_PULANG,
										$chTELAT, $chCEPAT, $chJAM_MASUK, $chJAM_PULANG, S_MSG('PA72','Jam'), S_MSG('PA98','Keterangan'), S_MSG('PI43','Laporan'),
										$cLOKASI, $cJABATAN]);	*/  
										TRTH([$chKODE_PEG, $chNAMA_PEG, $chTANGGAL, $chJAM_MASUK, $chJAM_PULANG, 
											$cNAMA_HARI, $chJAM_MASUK, $chJAM_PULANG, $chTELAT, $chCEPAT, $chJAM_MASUK, 
											$chJAM_PULANG, S_MSG('PA72','Jam'), S_MSG('PA98','Keterangan'), S_MSG('PI43','Laporan'), $cLOKASI, $cJABATAN]);
									?>
								</thead>

								<tbody>
									<?php
										$ctTELAT='';	$nCONVERSI_TIME=0;
										$qTEMP_PRESENCE = OpenTable('TmpPresence', "APP_CODE='$cUSERCODE'", 'PRSON_CODE, ABSN_DATE, ABSN_MSK, ABSN_KLR');
										while($aREC_TEMP=SYS_FETCH($qTEMP_PRESENCE)){
											$tTELAT = 0;	$tCEPAT=0;
											$cPERSON = $aREC_TEMP['PRSON_CODE'];
											$dDAY_DATE = $aREC_TEMP['ABSN_DATE'];
											
											$cJDW_MASUK = $cJAM_KERJA_M;
											$cJDW_PULANG = $cJAM_KERJA_K;
											$lNEXT_DAY=0;
											$ctTELAT=$ctCEPAT='';
											$cSHIFT_CODE = Get_Personal_Schedule_Code($cPERSON, $dDAY_DATE);
											if ($cSHIFT_CODE=='') {
												$cSHIFT_CODE = Get_Group_Schedule_Code($aREC_TEMP['CUST_CODE'], $aREC_TEMP['KODE_LOKS'], $aREC_TEMP['JOB_CODE'], $dDAY_DATE);
											}
											if ($cSHIFT_CODE!='') {
												$qTZ = OpenTable('Timezone', "DAYL_CODE='$cSHIFT_CODE' and APP_CODE='$cUSERCODE'");
											}
										
											$nSERVER_TIME=7;		// server time
											$qTIMEZONE	= OpenTable('Timezone', "A.DAYL_CODE='$cSHIFT_CODE' and A.APP_CODE='$cAPP_CODE'  and A.DELETOR=''");
											if (SYS_ROWS($qTIMEZONE)>0) {
												$aTIMEZONE	= SYS_FETCH($qTIMEZONE);
												$nCONVERSI_TIME = ($aTIMEZONE ? $aTIMEZONE['TIME_TO_UTC'] : 7 ) - $nSERVER_TIME;
												$cJDW_MASUK = $aTIMEZONE['JAM_MASUK'];
												$cJDW_PULANG = $aTIMEZONE['JAM_KELUAR'];
											}
								
											$cABS_MASUK =$cABS_PULANG=$cTELAT=$cLBR_MASUK=$cLBR_PULANG=$cCEPAT='';
											$nTELAT = $nLEMBUR=$nCEPAT=0;	
											$nHARI = date("w", strtotime($dDAY_DATE));
											$cABS_MASUK = $aREC_TEMP['ABSN_MSK'];
											if ($cABS_MASUK!='' && $cABS_MASUK!='00:00'){
												$nABS_MASUK = (int) substr($cABS_MASUK,0,2) + $nCONVERSI_TIME;
												$cABS_MASUK = str_pad($nABS_MASUK , 2 , "0" , STR_PAD_LEFT).':'.substr($cABS_MASUK,3,2);
												$nNORM_MSK = (int) substr($cJDW_MASUK,0,2)*60 + (int) substr($cJDW_MASUK, 3,2);
												$nABSN_MSK = (int) substr($cABS_MASUK,0,2) *60 + (int) substr($cABS_MASUK, 3,2);
												if($nABSN_MSK>$nNORM_MSK and $cABS_MASUK!='') {
													$nTELAT = $nABSN_MSK-$nNORM_MSK;
													$cTELAT = intval(($nABSN_MSK-$nNORM_MSK)/60) . ':' . ($nABSN_MSK-$nNORM_MSK)%60;
												}
											}
											$tTELAT += $nTELAT;
											$cABS_PULANG = $aREC_TEMP['ABSN_KLR'];
											if ($cABS_PULANG!='' && $cABS_PULANG!='00:00'){
												$nABS_PULANG = (int) substr($cABS_PULANG,0,2) + $nCONVERSI_TIME;
												$cABS_PULANG = str_pad($nABS_PULANG , 2 , "0" , STR_PAD_LEFT).':'.substr($cABS_PULANG,3,2);
												$nNORM_PLG = (int) substr($cJDW_PULANG,0,2)*60 + (int) substr($cJDW_PULANG, 3,2);
												$nABSN_PLG = (int) substr($cABS_PULANG,0,2) *60 + (int) substr($cABS_PULANG, 3,2);
												if($nABSN_PLG<$nNORM_PLG and $cABS_PULANG!='') {
													$nCEPAT=$nNORM_PLG-$nABSN_PLG;
													$cCEPAT=intval($nCEPAT/60) . ':' . $nCEPAT%60;
													$tCEPAT+= $nCEPAT;
												}
											}
											$cLBR_MASUK = $aREC_TEMP['OVERTIME_IN'];
											if ($cLBR_MASUK!=''){
												$nABS_PULANG = (int) substr($cLBR_MASUK,0,2) + $nCONVERSI_TIME;
												$cLBR_MASUK = str_pad($nABS_PULANG , 2 , "0" , STR_PAD_LEFT).':'.substr($cLBR_MASUK,3,2);
											}
											$cLBR_PULANG = $aREC_TEMP['OVERTIME_OUT'];
											if ($cLBR_PULANG!='' && $cLBR_PULANG!='00:00'){
												$nABS_PULANG = (int) substr($cLBR_PULANG,0,2) + $nCONVERSI_TIME;
												$cLBR_PULANG = str_pad($nABS_PULANG , 2 , "0" , STR_PAD_LEFT).':'.substr($cLBR_PULANG,3,2);
											}
											$nLBR_MSK = (int) substr($cLBR_MASUK,0,2)*60 + (int) substr($cLBR_MASUK, 3,2);
											$nLBR_PLG = (int) substr($cLBR_PULANG,0,2) *60 + (int) substr($cABS_PULANG, 3,2);
											// $nJAM_LBR=0;
											// if($cLBR_MASUK!='') {
											// 	$cLEMBUR = ($nLBR_PLG-$nLBR_MSK)/60 . ':' . ($nLBR_PLG-$nLBR_MSK)%60;
											// 	$nLEMBUR = $nLBR_PLG-$nLBR_MSK;
											// 	$nJAM_LBR = $nLEMBUR/60;
											// }
											$nJAM_LBR=$aREC_TEMP['OVERTIME_QTY'];

											$qABSENCE	= OpenTable('PrsAbsen', "PRSON_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and ABSEN_DATE = '$dDAY_DATE' and DELETOR=''");
											$aNOTE_ABSEN = SYS_FETCH($qABSENCE);
											$cNOTE_ABSEN = ($aNOTE_ABSEN ? $aNOTE_ABSEN['ABSEN_RESN'].'-'.$aNOTE_ABSEN['ABSEN_NOTE'] : '');
											$qREPORT	= OpenTable('PrsReport', "PRSON_CODE='$cPERSON' and APP_CODE='$cAPP_CODE' and date(REP_TIME) = '$dDAY_DATE' and DELETOR=''");
											$aREP_ABSEN = SYS_FETCH($qREPORT);
											$cREP_ABSEN = ($aREP_ABSEN ? $aREP_ABSEN['REP_CONTENT'] : '');
											$ctTELAT = CVR($tTELAT,'9999');
											if($tTELAT>60) {
												$ctTELAT = intval($tTELAT/60) . ':' . $tTELAT%60;
											}
											RecUpdate('RepAbsen', ['ABSN_MSK', 'ABSN_KLR', 'NORMAL_IN', 'NORMAL_OUT', 'P_LATE', 'EARLY_OUT', 'OVERTIME_QTY'], 
												[$cABS_MASUK, $cABS_PULANG, substr($cJDW_MASUK,0,5), substr($cJDW_PULANG,0,5), $nTELAT, $nCEPAT, intval($nJAM_LBR)], "REC_NO='$aREC_TEMP[REC_NO]'");
											$cHREFF="<a href='prs_tr_present.php?_a=".md5('up_da_te')."&_p=".md5($cPERSON)."&_d=".date('d/m/Y', strtotime($dDAY_DATE))."'>";
											TDETAIL([$aREC_TEMP['PRSON_CODE'], DECODE($aREC_TEMP['PRSON_NAME']), date("d-M-Y", strtotime($dDAY_DATE)), substr($cJDW_MASUK,0,5), 
												substr($cJDW_PULANG,0,5), $aNAMA_HARI[$nHARI], $cABS_MASUK, $cABS_PULANG, ($nTELAT>0 ? CVR($nTELAT,'9999') : ''),
												$cCEPAT, $cLBR_MASUK, $cLBR_PULANG, ($nJAM_LBR>0 ? CVR($nJAM_LBR,'9999') : ''), $cNOTE_ABSEN, $cREP_ABSEN,
												$aREC_TEMP['LOKS_NAME'], $aREC_TEMP['JOB_NAME']], [2,0,2,2,2,2,2,2,2,2,2,2,2,0,0,0,0], '*', 
													[$cHREFF, $cHREFF, '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],		// ref
													[80, 150, 0, 0, 0, 0, 70, 70, 70, 70, 70, 70, 50, 0, 0, 0, 0 ],					// width
													($can_UPD ? ['', '', '', '', '', '', 'text', 'text', '', '', 'text', 'text', 'text', '', '', '', ''] : []),			// type
													['', '', '', '', '', '', 'ABS_MASUK', 'ABS_PULANG', '', '', 'LBR_MASUK', 'LBR_PULANG', 'JAM_LBR', '', '', '', ''],		// name 
													['', '', '', '', '', '', '99:99', '99:99', '', '', '99:99', '99:99', '99', '', '', '', ''],			// mask
													['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],				// patern
													['', '', '', '', '', '', "TM_EDIT(this, 0, '$cPERSON', '$dDAY_DATE')", "TM_EDIT(this, 1, '$cPERSON', '$dDAY_DATE')", '', '', "TM_EDIT(this, 2, '$cPERSON', '$dDAY_DATE')", "TM_EDIT(this, 3, '$cPERSON', '$dDAY_DATE')", "TM_EDIT(this, 4, '$cPERSON', '$dDAY_DATE')", '', '', '', ''],			// on blur
													['', '', '', '', '', '', $cABS_MASUK, $cABS_PULANG, '', '', $cLBR_MASUK, $cLBR_PULANG, '', '', '', '', ''],		// old value
													['', '', '', '', '', '', "OFF_INS(this)", "OFF_INS(this)", '', '', "OFF_INS(this)", "OFF_INS(this)", "OFF_INS(this)", '', '', '', '']	// on focus
											);
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
<?php
			eTDIV();
		eTFORM('*');
		$cLOG='';
		$qCUST=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and CUST_CODE='$cFILTER_CUST'");
		if(SYS_ROWS($qCUST)>0)	{
			$aCUST=SYS_FETCH($qCUST);
			$cLOG.=' - '.$aCUST['CUST_NAME'];
		}
		$qLOK=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and LOKS_CODE='$cFILTER_LOKASI'");
		if(SYS_ROWS($qLOK)>0)	{
			$aLOKS=SYS_FETCH($qLOK);
			$cLOG.=' - '.$aLOKS['LOKS_NAME'];
		}
		$cLOG.=($cFILTER_JABATAN ? ' - '.$cFILTER_JABATAN : '');
		APP_LOG_ADD( $cHEADER, 'prs_rp_absen.php : '.$cLOG);
	END_WINDOW();
	SYS_DB_CLOSE($DB2);
?>

<script>
function FILT_ABSEN(p_TGL1, p_TGL2, p_CUST, p_LOK, p_JAB, p_DATA) {
	window.location.assign("?_d1="+p_TGL1 + "&_d2="+p_TGL2 + "&_c="+p_CUST + "&_l="+p_LOK + "&_j="+p_JAB + "&_r="+p_DATA);
}

function TM_EDIT(_EDITABLE, _PRESENT, _PERSON, _DATE) {
	if($(_EDITABLE).attr('data-old_value') === _EDITABLE.value)
		return false;
	$.ajax({
		url: "prs_rp_abs_upd.php",
		type: "POST",
		dataType: "json",
		data:'_f='+_PRESENT+'&_v='+_EDITABLE.value+'&_p='+_PERSON+'&_d='+_DATE,
		success: function(response) {
		// set updated value as old value
		$(_EDITABLE).attr('data-old_value',_EDITABLE.value);
		$(_EDITABLE).css("background","#FDFDFD");
		},
		error: function () {
			// console.log("errr");
			return false;
		}
	});
}

</script>
