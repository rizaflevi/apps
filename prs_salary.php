<?php
//	prs_salary.php
//	Daftar Gaji
//	TODO : output to excel & print
//
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	$cHEADER		= S_MSG('PL01','Laporan daftar gaji karyawan');

	$cKODE_PEG 		= S_MSG('PA02','Kode Peg');
	$cNAMA_PEG 		= S_MSG('PA03','Nama Pegawai');
	$cJABATAN 		= S_MSG('PA43','Jabatan');
	$cMASUK 		= S_MSG('PI22','Masuk');
	$cTMK 			= S_MSG('PB67','Tanggal TMK');
	$cMASKER		= S_MSG('PB91','Masa Kerja');
	$cGAJI_POKOK	= S_MSG('PA23','Gaji Pokok');
	$cJML_PND		= 'Jml Penghasilan';
	$cEMAIL 		= S_MSG('F105','Email Address');

    $ada_OUTSOURCING=(IS_OUTSOURCING($cAPP_CODE) ? 1 : 0);
	if($ada_OUTSOURCING==1) {
        $cGROUP 	= S_MSG('P060','Klmpk');
		$cCUSTOMER 	= S_MSG('RS04','Customer');
		$cLOKASI	= S_MSG('PF16','Lokasi');
	}
    
	$cFILTER_JABATAN=(isset($_GET['_j']) ? $_GET['_j'] : '');

	$cScope = "APP_CODE='$cAPP_CODE' and DELETOR=''";
	$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	if (SYS_ROWS($qSCOPE)>0) $cScope .= " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR='')";
//	$qCUSTOMER=OpenTable('TbCustomer', $cScope, '', 'CUST_NAME');

	$cFILTER_LOKASI=(isset($_GET['_l']) ? $_GET['_l'] : '');

	$cFILTER_GROUP		= '';
	if (isset($_GET['_g'])) {
        $cFILTER_GROUP=$_GET['_g'];
    } else {
        $qCUST_GROUP=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
        if(SYS_ROWS( $qCUST_GROUP)>0) {
            $aCUST_GROUP=SYS_FETCH($qCUST_GROUP);
            $cFILTER_GROUP=$aCUST_GROUP['KODE_GRP'];
        }
    }

	$cFILTER_CUSTOMER='';
	if (isset($_GET['_c'])) {
		$cFILTER_CUSTOMER=$_GET['_c'];
	} else {
		if($cFILTER_CUSTOMER=='') {
			$qCUSTOMER = OpenTable('TbCustomer', "CUST_GROUP='$cFILTER_GROUP' and APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
			if($aCUST_FIRST = SYS_FETCH($qCUSTOMER))	$cFILTER_CUSTOMER=$aCUST_FIRST['CUST_CODE'];
		}
	}

	APP_LOG_ADD($cHEADER, 'View');

	RecDelete('RepAbsen', "APP_CODE='$cUSERCODE'");
	RecDelete('PrApBln', "APP_CODE='$cUSERCODE'");
	$cFILT_DATA = "A.APP_CODE='" . $cAPP_CODE ."' and A.DELETOR='' and PRSON_SLRY<2 and R.RESIGN_DATE is NULL";
	if ($cFILTER_JABATAN!='') 	$cFILT_DATA.= " and P6.JOB_CODE='".$cFILTER_JABATAN."'";
	if ($cFILTER_CUSTOMER!='')	$cFILT_DATA.= " and P6.CUST_CODE='".$cFILTER_CUSTOMER."'";
	if ($cFILTER_GROUP!='')		$cFILT_DATA.= " and CG.KODE_GRP='".$cFILTER_GROUP."'";
	$cFILT_LOCA = $cFILT_DATA;
	if ($cFILTER_LOKASI!='') 	$cFILT_DATA.= " and P6.KODE_LOKS='".$cFILTER_LOKASI."'";
    $qCUSTOMER = OpenTable('TbCustomer', "CUST_GROUP='$cFILTER_GROUP' and APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
	$qLOCS=OpenTable('PersonSalary', $cFILT_LOCA.UserScope($cUSERCODE), 'LOC.LOKS_CODE', 'LOC.LOKS_NAME');
//	$qJOB= OpenTable('PersonSalary', $cFILT_DATA.UserScope($cUSERCODE), "JOB.JOB_CODE", 'JOB.JOB_NAME');
	$qQUERY=OpenTable('PersonSalary', $cFILT_DATA.UserScope($cUSERCODE));
	$aPCODE=[];
	$_SESSION['aPCODE'] = $aPCODE;
	$aALLOW = [];	$aCODE_ALLOW=[];	$aSHORT_NAME=[];	$aVAL_ALLOW=[];	$aINCLUDE_BPJS=[]; $aMS_KERJA_STTS=[]; $aIS_NOT_FIX=[];
	$qGRP_ALLOW=OpenTable('TbCustGrpAllow', "GCUST_CODE='$cFILTER_GROUP' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	if(SYS_ROWS($qGRP_ALLOW)>0) {
		while($aGRP_ALLOW=SYS_FETCH($qGRP_ALLOW)) {
			$cGRP_ALLOW = $aGRP_ALLOW['GCUST_ALLOW'];
			array_push($aALLOW,	$cGRP_ALLOW);
			$qTB_ALLOW=OpenTable('TbAllowance', "TNJ_CODE='$cGRP_ALLOW' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
			while($aTB_ALLOW=SYS_FETCH($qTB_ALLOW)) {
				array_push($aCODE_ALLOW, $aTB_ALLOW['TNJ_CODE']);
				array_push($aSHORT_NAME, $aTB_ALLOW['TNJ_SNAME']);
				array_push($aVAL_ALLOW, $aTB_ALLOW['TNJ_AMNT']);
				array_push($aINCLUDE_BPJS, $aGRP_ALLOW['GCUST_BPJS']);
				array_push($aMS_KERJA_STTS, $aTB_ALLOW['WORKING_TIME']);
				array_push($aIS_NOT_FIX, $aTB_ALLOW['IS_NOT_FIX']);
			}
		}
	}
	$aCODE_DEDUCT=[];	$aDEDUC_DESC=[];	$aDE_PROS=[];	$aADDING=[];
	$qGROUP_POT = OpenTable('PrsDeduction', "CUST_GROUP='$cFILTER_GROUP' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	if(SYS_ROWS($qGROUP_POT)>0)   {
		while($aTB_DEDUC=SYS_FETCH($qGROUP_POT)) {
			array_push($aCODE_DEDUCT, $aTB_DEDUC['DEDUCT_CODE']);
			array_push($aDEDUC_DESC, $aTB_DEDUC['DEDUCT_DESC']);
			array_push($aDE_PROS, $aTB_DEDUC['D_PROSENTAGE']);
			array_push($aADDING, $aTB_DEDUC['IS_ADDING']);
		}
	}
	$can_PRINT=TRUST($cUSERCODE, 'PRS_RP_SALARY_PRINT');
	$can_EXCEL=0;
	DEF_WINDOW($cHEADER, 'collapse');
		$aACT =[];
		// if ($can_PRINT) 
		// 	array_push($aACT, '<a href="prs_slry_print.php ?_c='
		// 		.($cFILTER_CUSTOMER=='' ? '' : md5($cFILTER_CUSTOMER)). '&_l='
		// 		.($cFILTER_LOKASI=='' ? '' : md5($cFILTER_LOKASI)). '&_j='
		// 		.($cFILTER_JABATAN=='' ? '' : md5($cFILTER_JABATAN))
		// 		.'" onClick="return confirm(OK)" title="print slip"><i class="glyphicon glyphicon-print"></i>Print</a>&nbsp&nbsp'); 
		if ($can_EXCEL) 
			array_push($aACT, '<a href="?_a='. md5('to_excel'). '"><i class="fa fa-file-excel-o"></i>Excel</a></li>');
		// array_push($aACT, '<a href="prs_slry_print.php" target="_blank"><i class="glyphicon glyphicon-print"></i>Print</a></li>');
		TFORM($cHEADER, "prs_slry_print.php", $aACT, 'Doc/Laporan - Daftar Gaji.pdf', '*');
			TDIV();
				if($ada_OUTSOURCING==1) {
					LABEL([1,2,2,4], '700', $cGROUP, '', 'right');
					SELECT([2,2,4,8], 'PILIH_GROUP', "FILT_GROUP(this.value)");
					$qCUSTGROUP=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
					while($aCUSTGRUP=SYS_FETCH($qCUSTGROUP)){
						if($aCUSTGRUP['KODE_GRP']==$cFILTER_GROUP){
							echo "<option value='$aCUSTGRUP[KODE_GRP]' selected='$cFILTER_GROUP' >$aCUSTGRUP[NAMA_GRP]</option>";
						} else
							echo "<option value='$aCUSTGRUP[KODE_GRP]'  >$aCUSTGRUP[NAMA_GRP]</option>";
					}
					echo '</select>';
				}
				LABEL([1,2,2,4], '700', $cJABATAN, '', 'right');
				SELECT([2,2,4,8], 'PILIH_JABTN', "FILT_PERSON(this.value, '$cFILTER_CUSTOMER', '$cFILTER_GROUP', '$cFILTER_LOKASI')");
					$qJOB=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
					echo "<option value=''  > All</option>";
					while($aREC_JOB=SYS_FETCH($qJOB)){
						if($aREC_JOB['JOB_CODE']==$cFILTER_JABATAN){
							echo "<option value='$aREC_JOB[JOB_CODE]' selected='$cFILTER_JABATAN' >$aREC_JOB[JOB_NAME]</option>";
						} else {
							echo "<option value='$aREC_JOB[JOB_CODE]'  >$aREC_JOB[JOB_NAME]</option>";
						}
					}
				echo '</select>';
				if($ada_OUTSOURCING) {
					LABEL([1,2,2,4], '700', $cCUSTOMER, '', 'right');
					SELECT([2,2,4,8], 'PILIH_CUSTOMER', "FILT_PERSON('$cFILTER_JABATAN', this.value, '$cFILTER_GROUP', '$cFILTER_LOKASI')");
					echo "<option value=''  > All</option>";
					while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
						if($aCUSTOMER['CUST_CODE']==$cFILTER_CUSTOMER){
							echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$cFILTER_CUSTOMER' >$aCUSTOMER[CUST_NAME]</option>";
						} else
							echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME]</option>";
					}
					echo '</select>';

					LABEL([1,2,2,4], '700', S_MSG('PE62','Lokasi'), '', 'right');
					SELECT([2,2,4,8], 'PILIH_LOKASI', "FILT_PERSON('$cFILTER_JABATAN', '$cFILTER_CUSTOMER', '$cFILTER_GROUP', this.value)");
					echo "<option value=''  > All</option>";
					while($aLOKASI=SYS_FETCH($qLOCS)){
						if($aLOKASI['LOKS_CODE']==$cFILTER_LOKASI){
							echo "<option value='$aLOKASI[LOKS_CODE]' selected='$cFILTER_LOKASI' >$aLOKASI[LOKS_NAME]</option>";
						} else
							echo "<option value='$aLOKASI[LOKS_CODE]'  >$aLOKASI[LOKS_NAME]</option>";
					}
					echo '</select>';
				}
				CLEAR_FIX();
				TABLE('example');
						//header -> kode pegawai, nama, jabatan, customer, lokasi, masuk, tmk, masa kerja, bulan, gaji pokok, tunjangan skil, jml penghasilan,bpjs, jml potongan, jumlah, email
						$aHEAD = [$cKODE_PEG, $cNAMA_PEG, $cJABATAN, $cMASUK, $cTMK, $cMASKER, 'Bln', $cGAJI_POKOK];
						if($ada_OUTSOURCING)	
							array_splice($aHEAD, 3, 0, [$cCUSTOMER, $cLOKASI]);
						for ($I=0; $I < sizeof($aCODE_ALLOW); $I++) {
							array_push($aHEAD, $aSHORT_NAME[$I]); //tunjangan gaji dll, tunjangan skill dsb
						}
						array_push($aHEAD, $cJML_PND); //jumlah penghasilan
						for ($I=0; $I < sizeof($aCODE_DEDUCT); $I++) {
							array_push($aHEAD, $aDEDUC_DESC[$I]); //potongan bpjs dll
						}
						array_push($aHEAD, 'Jml Ptgn');
						array_push($aHEAD, 'Jumlah');
						array_push($aHEAD, $cEMAIL);
						THEAD($aHEAD);
							echo '<tbody>';
							while($aREC_PERSON=SYS_FETCH($qQUERY)) {
								$PRS_CODE=$aREC_PERSON['PRSON_CODE'];
								array_push($aPCODE, $PRS_CODE);
								echo '<tr>';
									// echo "<td style='width: 1px;'></td>";
									echo '<td>';
									echo '<input type="checkbox" class="col-lg-12" name="MARK_'.$PRS_CODE.'"  class="iswitch iswitch-md iswitch-secondary">';
									echo '</td>';
									echo "<td ><span>".$aREC_PERSON['PRSON_CODE']."  </span></td>";
									echo "<th><span>".DECODE($aREC_PERSON['PRSON_NAME'])." </span></th>";
									echo "<td><span>".$aREC_PERSON['JOB_NAME']." </span></td>";
									if($ada_OUTSOURCING==1) {
										echo "<td><span>".DECODE($aREC_PERSON['CUST_NAME'])." </span></td>";
										echo "<td><span>".$aREC_PERSON['LOKS_NAME']." </span></td>";
									}
									echo "<td><span>".date('d-M-Y', strtotime($aREC_PERSON['JOIN_DATE']))."  </span></td>";
									$dJOB_DATE=$aREC_PERSON['JOB_DATE'];
									if(substr($dJOB_DATE,0,4)=='0000' || $dJOB_DATE=='' || empty((int)$dJOB_DATE))	$dJOB_DATE=$aREC_PERSON['JOIN_DATE'];
									if(substr($dJOB_DATE,0,4)=='0000' || $dJOB_DATE=='' || empty((int)$dJOB_DATE))	$dJOB_DATE='0000-00-00';
									echo "<td><span>".date('d-M-Y', strtotime($dJOB_DATE))."  </span></td>";
									$Birth = new DateTime($dJOB_DATE);
									$Now = new DateTime();
									$Interval = $Now->diff($Birth);
									$nMAS_KER = $Interval->y+1;
									$nMONTH = $Interval->m;
									echo '<td style="text-align:center"><span>'.(string)$nMAS_KER.' </span></td>'; //masa kerja
									echo '<td style="text-align:center"><span>'.(string)$nMONTH.' </span></td>'; //bulan

									$nBSC_SALARY = 0;	$cPROV=$cKAB='';
									$qSLRY = OpenTable('PrsGroupSlry', "APP_CODE='$cAPP_CODE' and SLRY_CUST='$aREC_PERSON[CUST_CODE]' and SLRY_LOC='$aREC_PERSON[KODE_LOKS]' and SLRY_JOB='$aREC_PERSON[JOB_CODE]' and REC_ID not in ( select DEL_ID from logs_delete )");
									if($aSLRY=SYS_FETCH($qSLRY))	{ //gaji pokok
										$cPROV=$aSLRY['SLRY_PROV'];	$cKAB=$aSLRY['SLRY_DIST'];
									} else {
										$qSLRY = OpenTable('PrsGroupSlry', "APP_CODE='$cAPP_CODE' and SLRY_CUST='$aREC_PERSON[CUST_CODE]' and SLRY_LOC='$aREC_PERSON[KODE_LOKS]' and REC_ID not in ( select DEL_ID from logs_delete )");
										if($aSLRY=SYS_FETCH($qSLRY))	{
											$cPROV=$aSLRY['SLRY_PROV'];	$cKAB=$aSLRY['SLRY_DIST'];
										} else {
											$qSLRY = OpenTable('PrsGroupSlry', "APP_CODE='$cAPP_CODE' and SLRY_CUST='$aREC_PERSON[CUST_CODE]' and SLRY_JOB='$aREC_PERSON[JOB_CODE]' and REC_ID not in ( select DEL_ID from logs_delete )");
											if($aSLRY=SYS_FETCH($qSLRY))	{
												$cPROV=$aSLRY['SLRY_PROV'];	$cKAB=$aSLRY['SLRY_DIST'];
											} else {
												$qSLRY = OpenTable('PrsGroupSlry', "APP_CODE='$cAPP_CODE' and SLRY_CUST='$aREC_PERSON[CUST_CODE]' and SLRY_LOC='' and SLRY_JOB='' and REC_ID not in ( select DEL_ID from logs_delete )");
												if($aSLRY=SYS_FETCH($qSLRY))	{
													$cPROV=$aSLRY['SLRY_PROV'];	$cKAB=$aSLRY['SLRY_DIST'];
												} else {
													$qSLRY = OpenTable('PrsGroupSlry', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
													if($aSLRY=SYS_FETCH($qSLRY))	{
														$cPROV=$aSLRY['SLRY_PROV'];	$cKAB=$aSLRY['SLRY_DIST'];
													}
												}                                                                
											}
										}

									}
									$nUMP=$nUMK=0;
									if($cKAB=='') {
										$qUMP = OpenTable('TbLocProvUmp', "APP_CODE='$cAPP_CODE' and id_prov='$cPROV' and DELETOR=''");
										if(SYS_ROWS($qUMP)>0)   {
											$aUMP=SYS_FETCH($qUMP);	$nUMP=$aUMP['UMP'];
										} 
									} else {
										$qUMK = OpenTable('TbLocDistUmk', "APP_CODE='$cAPP_CODE' and DIST_CODE='$cKAB' and DELETOR=''");
										if(SYS_ROWS($qUMK)>0)   {
											$aUMK=SYS_FETCH($qUMK);	$nUMK=$aUMK['DIST_UMK'];
										} 
									}
									$nBSC_SALARY = ($nUMK==0 ? $nUMP : $nUMK);
									echo "<td style='text-align:right;'><span>".CVR($nBSC_SALARY)."  </span></td>"; //gaji pokok

									if($nMAS_KER>10)	$nMAS_KER=10;
									$tTUNJ=$nJML_INCL_BPJS=0;
									for ($I=0; $I < sizeof($aCODE_ALLOW); $I++) {
										$nTUNJ = 0;
										if($aMS_KERJA_STTS[$I]==1) {
											$qWT = OpenTable('PrsWTAllow', "DURATION='$nMAS_KER' and APP_CODE='$cAPP_CODE' and DELETOR=''"); //rumus TMK PLN
											if($aWT = SYS_FETCH($qWT)) {
												$nTUNJ = $aWT['ALLOWANCE'];
											}
										} else {
											if($aIS_NOT_FIX[$I]==10) {
												$nTUNJ = 10*$nBSC_SALARY/100;
											} else {
												//TUNJANGAN JABATAN ATAU BPK PLN DAN BNI
												$qTNJ = OpenTable('PrsOccuAllow', "CUST_GROUP='$cFILTER_GROUP' and CUST_CODE='$aREC_PERSON[CUST_CODE]' and JOB_CODE='$aREC_PERSON[JOB_CODE]' and ALLOW_CODE='$aCODE_ALLOW[$I]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
												if($aTUNJ=SYS_FETCH($qTNJ))   $nTUNJ=$aTUNJ['ALLOWANCE'];
												else {
													$qTNJ = OpenTable('PrsOccuAllow', "CUST_GROUP='$cFILTER_GROUP' and JOB_CODE='$aREC_PERSON[JOB_CODE]' and ALLOW_CODE='$aCODE_ALLOW[$I]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
													if($aTUNJ=SYS_FETCH($qTNJ))   $nTUNJ=$aTUNJ['ALLOWANCE'];
												}
											}
										}
										$tTUNJ += $nTUNJ;
										if($aINCLUDE_BPJS[$I]==1)	$nJML_INCL_BPJS+=$nTUNJ;
										echo "<td style='text-align:right;'><span>".CVR($nTUNJ)."  </span></td>";
										if($nTUNJ>0) {
											RecCreate('PrApBln', ['INVOICE', 'NAMA_VND', 'AP_MASUK', 'JUMLAH', 'APP_CODE'], 
												[$aREC_PERSON['PRSON_CODE'], $aSHORT_NAME[$I], 0, intval($nTUNJ), $cUSERCODE]);
										}
									}
									echo "<td style='text-align:right;'><span>".CVR($nBSC_SALARY+$tTUNJ)."  </span></td>";

									$tPOT=$nADD=$jADD_BPJS = 0;
									$qADD_BPJS=OpenTable('PrsAddBpjs', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$aREC_PERSON[PRSON_CODE]' and DELETOR=''");
									if ($aADD = SYS_FETCH($qADD_BPJS)) {
										$nADD = $aADD['ADDING'];
										if($nADD>0)	$jADD_BPJS=intval(($nBSC_SALARY+$nJML_INCL_BPJS) * $nADD /100);
										$nADD += $jADD_BPJS;
									}
									for ($I=0; $I < sizeof($aCODE_DEDUCT); $I++) {
										$nPOT = intval(($nBSC_SALARY+$nJML_INCL_BPJS) * $aDE_PROS[$I] / 100,0);
										if($I==1)	$nPOT += $nADD;
										echo "<td style='text-align:right;'><span>".CVR($nPOT)."  </span></td>";
										$tPOT += $nPOT;
										if($nPOT>0) {
											RecCreate('PrApBln', ['INVOICE', 'NAMA_VND', 'AP_MASUK', 'JUMLAH', 'APP_CODE'], 
												[$aREC_PERSON['PRSON_CODE'], $aDEDUC_DESC[$I], 1, $nPOT, $cUSERCODE]);
										}
									}
									echo "<td style='text-align:right;'><span>".CVR($tPOT)."  </span></td>";
									echo "<td style='text-align:right;'><span>".CVR($nBSC_SALARY+$tTUNJ-$tPOT)."  </span></td>";
									$rEMAIL = '';
									$qEMAIL = OpenTable('PeopleEMail', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$aREC_PERSON[PRSON_CODE]' and REC_ID not in ( select DEL_ID from logs_delete )");
									if(SYS_ROWS($qEMAIL)>0)	{
										$aEMAIL=SYS_FETCH($qEMAIL);
										$rEMAIL=$aEMAIL['PPL_EMAIL'];
									}
									echo "<td><span>".$rEMAIL."  </span></td>";
								echo '</tr>';
								RecCreate('RepAbsen', ['PRSON_CODE', 'PRSON_NAME', 'P_LATE', 'JOB_NAME', 'CUST_NAME', 'LOC_NAME', 'ABSN_DATE', 'APP_CODE'], 
									[$aREC_PERSON['PRSON_CODE'], $aREC_PERSON['PRSON_NAME'], $nBSC_SALARY, $aREC_PERSON['JOB_NAME'], $aREC_PERSON['CUST_NAME'], 
									$aREC_PERSON['LOKS_NAME'], $dJOB_DATE, $cUSERCODE]);
							}
							$_SESSION['aPCODE'] = $aPCODE;
					echo '</tbody>';
				eTABLE();
				// echo '<input type="submit" value="Print Slip" formtarget="_blank"/>';
				SAVE('Print slip', true);
			eTDIV();
		eTFORM();
    END_WINDOW();
	APP_LOG_ADD( $cHEADER, 'prs_salary.php:'.$cAPP_CODE);
	SYS_DB_CLOSE($DB2);
?>
<script>
function FILT_PERSON(p_JAB, p_CUST, p_GEND, p_LOKS='') {
window.location.assign("?_j="+p_JAB + "&_c="+p_CUST + "&_g="+p_GEND + "&_l="+p_LOKS);
}

function FILT_GROUP(p_GROUP) {
window.location.assign("?_g="+p_GROUP);
}

</script>
