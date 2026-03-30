<?php
//	prs_rp_person.php
//	Daftar Karyawan
//	TODO : add KK

	require_once "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	$cHEADER		= S_MSG('PL11','Laporan Daftar Karyawan');
	$cHELP_FILE 	= 'Doc/Laporan - Daftar Pegawai.pdf';

	$cKODE_PEG 		= S_MSG('PA02','Kode Peg');
	$cNAMA_PEG 		= S_MSG('PA03','Nama Pegawai');
	$cGENDER 		= S_MSG('PA04','Gender');
	$cJABATAN 		= S_MSG('PA43','Jabatan');
	$cUMUR 			= S_MSG('TA54','Umur');
	$cMASUK 		= S_MSG('PI22','Masuk');
	$cTMK 			= S_MSG('PB67','Tanggal TMK');
	$cMASKER		= S_MSG('PB91','Masa Kerja');
	$cTMP_LAHIR		= S_MSG('PA05','Tempat Lahir');
	$cTGL_LAHIR		= S_MSG('PA06','Tanggal Lahir');
	$cALAMAT		= S_MSG('F005','Alamat');
//	$cKELURAHAN		= S_MSG('PA38','Kelurahan');
	$cPENDIDIKAN 	= S_MSG('PA94','Pendidikan');
	$cEMAIL 		= S_MSG('F105','Email Address');
	$cSERT 			= S_MSG('PG68','No. Sertifikat');
	$cHAPE 			= S_MSG('PA33','Nomor HP');
	$cTELP			= S_MSG('F006','Nomor Telp.');
	$cKTP			= S_MSG('PA40','KTP');
	$cNPWP			= S_MSG('PG83','No. NPWP');
	$cNOREK			= S_MSG('PA07','No. Rekening');
	$cBANK			= S_MSG('PA08','Nama Bank');
	$cKTA			= S_MSG('PG8F','No. KTA');
	$cEXP			= S_MSG('PG89','Berlaku s/d');
	$cBPJS_TK		= S_MSG('PG84','No. BPJS TK');
	$cHEIGHT		= S_MSG('PA0A','Tinggi badan');
	$cWEIGHT		= S_MSG('PA0B','Berat badan');
	$cSHIRT			= S_MSG('PA0C','Ukuran Baju');
	$cSHOE			= S_MSG('PA0D','Ukuran Sepatu');
	$cPANTS			= S_MSG('PA0F','Ukuran Celana');
	$cDIA_HEAD		= S_MSG('PA0X','Lingkar Kepala');

	$cBPJS_KES		= S_MSG('PG85','No. BPJS KES');
	$cTTIP_JAB 		= S_MSG('PL16','Pilih jabatan yang akan di filter');
	$cTTIP_RES 		= S_MSG('PC36','Memilih status karyawan, apakah semua, yang masih bekerja saja, atau yang sudah keluar');

	$ada_OUTSOURCING=IS_OUTSOURCING($cAPP_CODE);
	if($ada_OUTSOURCING) {
		$cCUSTOMER 	= S_MSG('RS04','Customer');
		$cLOKASI	= S_MSG('PF16','Lokasi');
	}

	$cFILTER_JABATAN=(isset($_GET['_j']) ? $_GET['_j'] : '');

	$cScope = "APP_CODE='$cAPP_CODE' and DELETOR=''";
	$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	if (SYS_ROWS($qSCOPE)>0) $cScope .= " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR='')";
	
	//query lama tapi lokasi penempatan masih termasuk karyawan yang aktif
	// $qCUSTOMER=OpenTable('TbCustomer', $cScope, '', 'CUST_NAME');

	// query baru
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
	//query baru

	$cFILTER_CUSTOMER='';
	if (isset($_GET['_c'])) {
		$cFILTER_CUSTOMER=$_GET['_c'];
	// } else {
	// 	if($cFILTER_CUSTOMER=='') {
	// 		$qCUSTOMER = OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
	// 		$aCUST_FIRST = SYS_FETCH($qCUSTOMER);
	// 		$cFILTER_CUSTOMER=$aCUST_FIRST['CUST_CODE'];
	// 	}
	}

	$cFILTER_LOKASI=(isset($_GET['_l']) ? $_GET['_l'] : '');

	$cGENDER		= S_MSG('PA04','Gender');
	$nGENDER=(isset($_GET['_g']) ? $nGENDER=$_GET['_g'] : 0);

	$cFILTER_RESIGN=(isset($_GET['_rs']) ? $_GET['_rs'] : '');

	$ADD_LOG	= APP_LOG_ADD($cHEADER);

	$cFILT_DATA = "A.APP_CODE='" . $cAPP_CODE ."' and A.DELETOR='' and PRSON_SLRY<2 and R.RESIGN_DATE is NULL";
	if ($cFILTER_JABATAN!='') 	$cFILT_DATA.= " and P6.JOB_CODE='".$cFILTER_JABATAN."'";
	if ($cFILTER_CUSTOMER!='')	$cFILT_DATA.= " and P6.CUST_CODE='".$cFILTER_CUSTOMER."'";
	if ($nGENDER!=0)				$cFILT_DATA.= " and A.PRSON_GEND=".$nGENDER;
	if ($cFILTER_LOKASI!='') 	$cFILT_DATA.= " and P6.KODE_LOKS='".$cFILTER_LOKASI."'";
//    $qCUSTOMER = OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''".UserScope($cUSERCODE), '', 'CUST_NAME');
	$qLOCS=OpenTable('PersonList', $cFILT_DATA.UserScope($cUSERCODE), 'LOC.LOKS_CODE', 'LOC.LOKS_NAME');
	$qQUERY=OpenTable('PersonList', $cFILT_DATA.UserScope($cUSERCODE));
	DEF_WINDOW($cHEADER, 'collapse');
		$aACT = (TRUST($cUSERCODE, 'PRS_PRS_LIST_EXCEL')==1 ? ['<a href="prs_excel.php?_c='.$cFILTER_CUSTOMER.'"><i class="fa fa-file-excel-o"></i>Excel</a>'] : []);
		TFORM($cHEADER, '', $aACT, $cHELP_FILE, '*');
			TDIV();
				LABEL([1,2,3,4], '700', $cJABATAN);
				SELECT([3,3,3,8], 'PILIH_JABTN', "FILT_PERSON(this.value, '$cFILTER_CUSTOMER', '$nGENDER', '$cFILTER_LOKASI')");
				$REC_JABATAN=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
				echo "<option value=''  > All</option>";
				while($aREC_GR_DATA=SYS_FETCH($REC_JABATAN)){
					if($aREC_GR_DATA['JOB_CODE']==$cFILTER_JABATAN){
						echo "<option value='$aREC_GR_DATA[JOB_CODE]' selected='$cFILTER_JABATAN' >$aREC_GR_DATA[JOB_NAME]</option>";
					} else {
						echo "<option value='$aREC_GR_DATA[JOB_CODE]'  >$aREC_GR_DATA[JOB_NAME]</option>";
					}
				}
				echo '</select>';
				if($ada_OUTSOURCING) {
					LABEL([1,2,2,4], '700', $cCUSTOMER, '', 'right');
					SELECT([3,3,3,8], 'PILIH_JABTN', "FILT_PERSON('$cFILTER_JABATAN', this.value, '$nGENDER', '$cFILTER_LOKASI')");
					echo "<option value=''  > All</option>";
					while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
						if($aCUSTOMER['CUST_CODE']==$cFILTER_CUSTOMER){
							echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$cFILTER_CUSTOMER' >$aCUSTOMER[CUST_NAME] - $aCUSTOMER[employee_count] Pekerja</option>";
						} else
							echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME] - $aCUSTOMER[employee_count] Pekerja</option>";
					}
					echo '</select>';

					LABEL([1,2,2,4], '700', S_MSG('PE62','Lokasi'), '', 'right');
					SELECT([2,2,2,8], 'PILIH_LOKASI', "FILT_PERSON('$cFILTER_JABATAN', '$cFILTER_CUSTOMER', '$nGENDER', this.value)");
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
				LABEL([1,2,2,4], '700', $cGENDER);
				SELECT([2,2,2,8], 'PILIH_GENDER', "FILT_PERSON('$cFILTER_JABATAN', '$cFILTER_CUSTOMER', this.value, '$cFILTER_LOKASI')");
				?>
					<option value=0  > All</option>
					<option value=1 <?php if ($nGENDER==1) { echo 'selected';} echo '/>'.S_MSG('PD12','Pria')?></option>
					<option value=2 <?php if ($nGENDER==2) { echo 'selected';} echo '/>'.S_MSG('PD13','Wanita')?></option>
				</select>

				<div class="content-body">    
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<table data-order='[[ 9, "asc" ]]' cellspacing="0" id="example" class="table table-small-font table-bordered table-striped">
								<?php
									$aHEADER = [$cKODE_PEG, $cNAMA_PEG, $cGENDER, $cUMUR, $cJABATAN, $cMASUK, $cTMK, 
										$cMASKER, $cTMP_LAHIR, S_MSG('PA06','Tanggal Lahir'), $cALAMAT, S_MSG('PA94','Pendidikan'),
										$cEMAIL, $cSERT, $cHAPE, $cTELP, $cKTP, $cNPWP, $cNOREK, $cBANK, $cKTA, $cEXP, $cBPJS_TK, $cBPJS_KES,
										S_MSG('PB61','Jml. Anak'), S_MSG('PB62','Nama Pasangan'), $cHEIGHT, $cWEIGHT, $cSHOE, $cPANTS, $cSHIRT, $cDIA_HEAD];
									if($ada_OUTSOURCING) {
										array_splice($aHEADER, 5, 0, [$cCUSTOMER, $cLOKASI]);
									}
									echo THEAD($aHEADER);
									echo '<tbody>';
										while($aREC_PERSON=SYS_FETCH($qQUERY)) {
											$Birth = new DateTime($aREC_PERSON['BIRTH_DATE']);
											$Now = new DateTime();
											$Interval = $Now->diff($Birth);
											$Age = $Interval->y;
											$aDTL = [$aREC_PERSON['PRSON_CODE'], DECODE($aREC_PERSON['PRSON_NAME']), ($aREC_PERSON['PRSON_GEND']==1 ? 'Pria' : 'Wanita'), $Age, $aREC_PERSON['JOB_NAME']];
											if($ada_OUTSOURCING) {
												array_push($aDTL, DECODE($aREC_PERSON['CUST_NAME']));
												array_push($aDTL, DECODE($aREC_PERSON['LOKS_NAME']));
											}
											$aDTL=array_merge($aDTL, [$aREC_PERSON['JOIN_DATE'], $aREC_PERSON['JOB_DATE'], $aREC_PERSON['WORK_AGE'], $aREC_PERSON['BIRTH_PLC'], $aREC_PERSON['BIRTH_DATE'], DECODE($aREC_PERSON['PEOPLE_ADDRESS']), $aREC_PERSON['EDU_NAME'], $aREC_PERSON['PPL_EMAIL'], $aREC_PERSON['SKILL_SERT'], $aREC_PERSON['PRS_PHN'], $aREC_PERSON['HOME_PHONE'], $aREC_PERSON['PRS_KTP']]);
											$cNP_WP = '';	$cBPJSTK = '';	$cBPJSKES='';
											$qNUMBER = OpenTable('PrsNumber', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$aREC_PERSON[PRSON_CODE]' and REC_ID not in ( select DEL_ID from logs_delete )");
											if($aNUMBER=SYS_FETCH($qNUMBER))	{
												$cNP_WP=$aNUMBER['NO_NPWP'];
												$cBPJSTK=$aNUMBER['NO_BPJS_TK'];
												$cBPJSKES=$aNUMBER['NO_BPJS_KES'];
											}
											$cCARD = '';	$cVALID = '';
											$qMEMBER = OpenTable('PrsMemberCard', "APP_CODE='$cAPP_CODE' and PERSON_CODE='$aREC_PERSON[PRSON_CODE]' and REC_ID not in ( select DEL_ID from logs_delete )");
											if($aMEMBER=SYS_FETCH($qMEMBER))	{
												$cCARD=$aMEMBER['CARD_NUMBER'];
												$cVALID=$aMEMBER['VALID_UNTIL'];
											}
											$cCHILD = '';	$cSPOUSE = '';
											$qSPOUSE = OpenTable('PrsSpouse', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$aREC_PERSON[PRSON_CODE]' and REC_ID not in ( select DEL_ID from logs_delete )");
											if($aSPOUSE=SYS_FETCH($qSPOUSE))	{
												$cCHILD=$aSPOUSE['CHILD_HAVE'];
												$cSPOUSE=$aSPOUSE['SPOUSE_NAME'];
											}

											$nWEIGHT= 0;	$nHEIGHT = 0;	$cSZ_SHIRT='';	$nSHOE=0;	$nPANTS=0;	$nDIA_HEAD=0;
											$qSIZE = OpenTable('PrsSize', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$aREC_PERSON[PRSON_CODE]' and REC_ID not in ( select DEL_ID from logs_delete )");
											if(SYS_ROWS($qSIZE)>0)	{
												$aSIZE=SYS_FETCH($qSIZE);
												$nWEIGHT=$aSIZE['PRS_WEIGHT'];
												$nHEIGHT=$aSIZE['PRS_HEIGHT'];
												$nSHOE=$aSIZE['PRS_SHOE'];
												$nPANTS=$aSIZE['PRS_PDL'];
												$cSZ_SHIRT=$aSIZE['PRS_SHIRT'];
												$nDIA_HEAD=$aSIZE['PRS_HEAD'];
											}
											$aDTL=array_merge($aDTL, [$cNP_WP, $aREC_PERSON['PRSON_ACCN'], $aREC_PERSON['PRSON_BANK'], $cCARD, $cVALID, $cBPJSTK, $cBPJSKES, $cCHILD, $cSPOUSE, $nHEIGHT, $nWEIGHT, $nSHOE, $nPANTS, $cSZ_SHIRT, $nDIA_HEAD]);
											TDETAIL($aDTL, [], '');
										}
									echo '</tbody>';
								?>
								
							</table>
						</div>
					</div>
				</div>
<?php
			eTDIV();
		eTFORM('*');
		// include "scr_chat.php";
		require_once("js_framework.php");
		APP_LOG_ADD( $cHEADER, 'prs_rp_person.php:'.$cAPP_CODE);
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
?>
<script>
function FILT_PERSON(p_JAB, p_CUST, p_GEND, p_LOKS='') {
window.location.assign("?_j="+p_JAB + "&_c="+p_CUST + "&_g="+p_GEND + "&_l="+p_LOKS);
}

</script>
