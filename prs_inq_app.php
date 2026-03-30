<?php
//	prs_inq_app.php
//	Daftar Lamaran
// TODO : save foto

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();

    $cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	$_SESSION['CALLER']='APP';
	$cHELP_FILE 	= 'Doc/Daftar - Lamaran.pdf';
	$cHEADER		= S_MSG('PL91','Daftar Lamaran');

	$cKODE_PEG 		= S_MSG('F003','Kode');
	$cGENDER 		= S_MSG('PA04','Gender');
	$cJABATAN 		= S_MSG('PA43','Jabatan');
	$cTGL_LAHIR		= S_MSG('PA06','Tanggal Lahir');
	$cPROV 		    = S_MSG('CB81','Prov.');
	$cPENDIDIKAN 	= S_MSG('PA94','Pendidikan');
	$cTTIP_RES 		= S_MSG('PC36','Memilih status karyawan, apakah semua, yang masih bekerja saja, atau yang sudah keluar');
	$cMSG_DEL		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');

	$cFILTER_JABATAN='';
	if (isset($_GET['_j'])) $cFILTER_JABATAN=$_GET['_j'];
	$qPROV=OpenTable('TbProvince', "APP_CODE='$cAPP_CODE'", '', 'nama');

	$cFILTER_PROV=(isset($_GET['_p']) ? $_GET['_p'] : '');
	$cFILTER_SKILL=(isset($_GET['_l']) ? $_GET['_l'] : '');

	$cGENDER		= S_MSG('PA04','Gender');
	$nGENDER		= (isset($_GET['_g']) ? $nGENDER=$_GET['_g'] : 0);
	$cFILTER_RESIGN=(isset($_GET['_rs']) ? $_GET['_rs'] : '');
	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');
	if (isset($_GET['_e'])) $cPERSON=$_GET['_e'];

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'View');
		$cFILT_DATA = "A.APP_CODE='" . $cAPP_CODE ."' and A.DELETOR='' and A.PRSON_SLRY=2 and R.RESIGN_DATE is NULL";
		if ($cFILTER_JABATAN!='') 	$cFILT_DATA.= " and P6.JOB_CODE='".$cFILTER_JABATAN."'";
		if ($nGENDER!=0)				$cFILT_DATA.= " and A.PRSON_GEND=".$nGENDER;
		if ($cFILTER_PROV!='') 	$cFILT_DATA.= " and left(AD.AREA_CODE,2)='".$cFILTER_PROV."'";
		$qQUERY=OpenTable('PersonList', $cFILT_DATA);
	
		DEF_WINDOW($cHEADER, 'collapse', '');
			$aACT = [];
			// array_push($aACT, '<a href="prs_xls_app.php"<i class="fa fa-file-excel-o"></i>Excel</a>');
			TFORM($cHEADER, '', $aACT, $cHELP_FILE, '*');
				LABEL([1,1,1,4], '700', $cJABATAN);
				SELECT([3,3,3,6], 'PILIH_JABTN', "FILT_PERSON(this.value, '$cFILTER_PROV', '$nGENDER', '$cFILTER_SKILL')");
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
				LABEL([1,2,2,4], '700', $cPROV, '', 'right');
				SELECT([3,3,3,6], 'PILIH_PROV', "FILT_PERSON('$cFILTER_JABATAN', this.value, '$nGENDER', '$cFILTER_SKILL')");
					echo "<option value=''  > All</option>";
					while($aPROV=SYS_FETCH($qPROV)){
						if($aPROV['id_prov']==$cFILTER_PROV){
							echo "<option value='$aPROV[id_prov]' selected='$cFILTER_PROV' >$aPROV[nama]</option>";
						} else
							echo "<option value='$aPROV[id_prov]'  >$aPROV[nama]</option>";
					}
				echo '</select>';
				LABEL([1,2,2,4], '700', S_MSG('PG49','Skill'), '', 'right');
				$qSKIL=OpenTable('TbSkill', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SKILL_NAME');
				SELECT([2,2,3,6], 'PILIH_SKILL', "FILT_PERSON('$cFILTER_JABATAN', '$cFILTER_PROV', '$nGENDER', this.value)");
				echo "<option value=''  > All</option>";
				while($aSKILL=SYS_FETCH($qSKIL)){
					if($aSKILL['SKILL_CODE']==$cFILTER_SKILL){
						echo "<option value='$aSKILL[SKILL_CODE]' selected='$cFILTER_SKILL' >$aSKILL[SKILL_NAME]</option>";
					} else
						echo "<option value='$aSKILL[SKILL_CODE]'  >$aSKILL[SKILL_NAME]</option>";
				}
				echo '</select>';
				CLEAR_FIX();
				LABEL([1,2,2,4], '700', $cGENDER);
				SELECT([3,3,3,6], 'PILIH_GENDER', "FILT_PERSON('$cFILTER_JABATAN', '$cFILTER_PROV', this.value, '$cFILTER_SKILL')");
					echo '<option value=0  > All</option>';
					echo '<option value=1 '. ($nGENDER==1 ? 'selected' : '') . '/>'.S_MSG('PD12','Pria').'</option>';
					echo '<option value=2 '. ($nGENDER==2 ? 'selected' : ''). '/>'.S_MSG('PD13','Wanita').'</option>';
				echo '</select>';
				TDIV();?>
					<table data-order='[[ 1, "desc" ]]' cellspacing="0" id="example" class="table table-small-font table-bordered table-striped"><?php
					echo THEAD(['Tanggal', S_MSG('PL92','Nama Pelamar'), $cGENDER, S_MSG('TA54','Umur'), $cJABATAN, 
						$cPROV, S_MSG('CB86','Kab'), S_MSG('PA05','Tempat Lahir'), S_MSG('PA06','Tanggal Lahir'), S_MSG('F005','Alamat'), 
						S_MSG('CB13','RT.'), S_MSG('CB15','RW.'), S_MSG('PA94','Pendidikan'), S_MSG('F105','Email Address'),
						S_MSG('PG68','No. Sertifikat'), S_MSG('PA33','Nomor HP'), S_MSG('F006','Nomor Telp.'), S_MSG('PA40','KTP'), S_MSG('PG83','No. NPWP'), S_MSG('PA07','No. Rekening'), S_MSG('PA08','Nama Bank'), S_MSG('PG8F','No. KTA'), S_MSG('PG89','Berlaku s/d'), S_MSG('PB50','Agama'), S_MSG('PG84','No. BPJS TK'), S_MSG('PG85','No. BPJS KES'), 
						S_MSG('PB61','Jml. Anak'), S_MSG('PB62','Nama Pasangan')]);
					echo '<tbody>';
						while($aREC_PERSON=SYS_FETCH($qQUERY)) {
								$cICON = 'fa fa-male';
								if(trim($aREC_PERSON['PRSON_GEND'])==2) $cICON = 'fa-female';
								$Birth = new DateTime($aREC_PERSON['BIRTH_DATE']);
								$Now = new DateTime();
								$Interval = $Now->diff($Birth);
								$Age = $Interval->y;
								$cPROV = '';
								$cID_PROV= substr($aREC_PERSON['AREA_CODE'],0,2);
								$qPROV = OpenTable('TbProvince', "id_prov='$cID_PROV'");
								if($aPROV=SYS_FETCH($qPROV))	{
									$cPROV=$aPROV['nama'];
								}
								$cDISTRICT = '';
								$cID_KAB = substr($aREC_PERSON['AREA_CODE'],0,4);
								$qDISTRICT = OpenTable('TbLocDistrict', "id_kab='$cID_KAB'");
								if(SYS_ROWS($qDISTRICT)>0)	{
									$aDISTRICT=SYS_FETCH($qDISTRICT);
									$cDISTRICT=$aDISTRICT['kabupaten'];
								}
								$cCHILD = '';	$cSPOUSE = '';
								$qSPOUSE = OpenTable('PrsSpouse', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$aREC_PERSON[PRSON_CODE]' and REC_ID not in ( select DEL_ID from logs_delete )");
								if(SYS_ROWS($qSPOUSE)>0)	{
									$aSPOUSE=SYS_FETCH($qSPOUSE);
									$cCHILD=$aSPOUSE['CHILD_HAVE'];
									$cSPOUSE=$aSPOUSE['SPOUSE_NAME'];
								}
								$cNPWP = '';	$cBPJSTK = '';	$cBPJSKES='';
								$qNUMBER = OpenTable('PrsNumber', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$aREC_PERSON[PRSON_CODE]' and REC_ID not in ( select DEL_ID from logs_delete )");
								if(SYS_ROWS($qNUMBER)>0)	{
									$aNUMBER=SYS_FETCH($qNUMBER);
									$cNPWP=$aNUMBER['NO_NPWP'];
									$cBPJSTK=$aNUMBER['NO_BPJS_TK'];
									$cBPJSKES=$aNUMBER['NO_BPJS_KES'];
								}
								$cCARD = '';	$cVALID = '';
								$qMEMBER = OpenTable('PrsMemberCard', "APP_CODE='$cAPP_CODE' and PERSON_CODE='$aREC_PERSON[PRSON_CODE]' and REC_ID not in ( select DEL_ID from logs_delete )");
								if(SYS_ROWS($qMEMBER)>0)	{
									$aMEMBER=SYS_FETCH($qMEMBER);
									$cCARD=$aMEMBER['CARD_NUMBER'];
									$cVALID=$aMEMBER['VALID_UNTIL'];
								}

								$aDTL = [substr($aREC_PERSON['DATE_ENTRY'],0,10), DECODE($aREC_PERSON['PRSON_NAME']),
									($aREC_PERSON['PRSON_GEND']==1 ? 'Pria' : 'Wanita'), (string)$Age, $aREC_PERSON['JOB_NAME'],
									$cPROV, $cDISTRICT, $aREC_PERSON['BIRTH_PLC'], $aREC_PERSON['BIRTH_DATE'], DECODE($aREC_PERSON['PEOPLE_ADDRESS']), 
									$aREC_PERSON['PRSN_RT'], $aREC_PERSON['PRSN_RW'], $aREC_PERSON['EDU_NAME'], $aREC_PERSON['PPL_EMAIL'],
									$aREC_PERSON['SKILL_SERT'], $aREC_PERSON['PRS_PHN'], $aREC_PERSON['HOME_PHONE'], $aREC_PERSON['PRS_KTP'],
									$cNPWP, $aREC_PERSON['PRSON_ACCN'], $aREC_PERSON['PRSON_BANK'], $cCARD, $cVALID, $aREC_PERSON['RELIGION'],
									$cBPJSTK, $cBPJSKES, $cCHILD, $cSPOUSE];
									// echo "<td><span><a href='prs_tb_person.php?_a=".md5('UP_DATE_PEG')."&_c=".md5($aREC_PERSON['PRSON_CODE'])."&_b=APP'>".DECODE($aREC_PERSON['PRSON_NAME'])."</a></span></td>";
								$aHREF=["<a href='?_a=".md5('ProcEmpl')."&_e=$aREC_PERSON[PRSON_CODE]"."&_n=$aREC_PERSON[PRSON_NAME]'>", 
									"<a href='prs_tb_person.php?_a=".md5('UP_DATE_PEG')."&_c=".md5($aREC_PERSON['PRSON_CODE'])."&_b=APP'>",
									'','','','','','','','','','','','','','','','','','','','','','','','','',''];
									TDETAIL($aDTL, [0,0,0,2,0,0,0,0,0,0,2,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2,0], $cICON, $aHREF);
						}
					echo '</tbody>';
					echo '</table>';
				TDIV();	
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;
	case md5('ProcEmpl'):
		$cNAME=$cPERSON='';
		if (isset($_GET['_e'])) $cPERSON=$_GET['_e'];
		if (isset($_GET['_n'])) $cNAME=$_GET['_n'];
		$can_UPDATE = TRUST($cUSERCODE, 'PRS_CV_LIST_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'PRS_CV_LIST_3DEL');
		$cHEADER=S_MSG('PG8H','Pemasukan data karyawan dari lamaran kerja');
		DEF_WINDOW($cHEADER);
			$aACT =[];
			if ($can_DELETE) array_push($aACT, '<a href="?_a=app_delete&_id='. $cPERSON. '" onClick="return confirm('. "'". $cMSG_DEL. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>');
			TFORM($cHEADER, '?_a=AddEmpl&_e='. $cPERSON, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,4,6], '500', S_MSG('PA03','Nama'));
					LABEL([6,6,6,6], '900', DECODE($cNAME));
					CLEAR_FIX();
					echo '<br>';
					LABEL([3,3,4,6], '500', $cKODE_PEG);
					INPUT('text', [3,3,3,6], '900', 'ADD_KODE_PEG', '', '', '', '', 14, '', 'fix');
					LABEL([3,3,4,6], '500', $cJABATAN);
					SELECT([6,6,6,6], 'ADD_JOB');
						$qOCCUP=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
						while($aUPD_JOB=SYS_FETCH($qOCCUP)){
							echo "<option value='$aUPD_JOB[JOB_CODE]'  >$aUPD_JOB[JOB_NAME]</option>";
						}
					echo '</select>';
					CLEAR_FIX();
					LABEL([3,3,4,6], '500', S_MSG('PF15','Customer'));
					TDIV(6,6,6,6);
						SELECT([6,6,6,6], 'ADD_CUSTOMER', '', '', 'select2');
							$REC_SKIL=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
							while($aREC_CUST=SYS_FETCH($REC_SKIL)){
								echo "<option value='$aREC_CUST[CUST_CODE]'  >$aREC_CUST[CUST_NAME]</option>";
							}
						echo '</select><br>';
					eTDIV();
					CLEAR_FIX();
					LABEL([3,3,4,6], '500', S_MSG('PF16','Lokasi'));
					TDIV(6,6,6,6);
						SELECT([6,6,6,6], 'ADD_LOKASI', '', '', 'select2');
							$qLOKASI=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
							while($aLOKASI=SYS_FETCH($qLOKASI)){
								echo "<option value='$aLOKASI[LOKS_CODE]'  >$aLOKASI[LOKS_NAME]</option>";
							}
						echo '</select>';
					eTDIV();
					CLEAR_FIX();
					echo '<br>';
					LABEL([3,3,4,6], '500', S_MSG('PB64','Mulai Kerja'));
					INP_DATE([2,2,2,6], '900', 'UPD_TGL_MSK', date('d/m/Y'));
					LABEL([2,2,2,6], '500', S_MSG('PB67','Tanggal TMK'), '', 'right');
					INP_DATE([2,2,2,6], '900', 'UPD_TGL_TMK', date('d/m/Y'), '', '', '', 'fix');
					LABEL([3,3,4,6], '500', S_MSG('PF25','Catatan'));
					INPUT('text', [6,6,6,6], '900', 'ADD_OUT_NOTE', '', '', '', '', 0, '', 'fix');
					SAVE(($can_UPDATE ? S_MSG('F301','Save') : ''));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;
case 'AddEmpl':
	$cAPP=$_GET['_e'];
	$cPERSON=$_POST['ADD_KODE_PEG'];
	if ($cPERSON=='') {
		MSG_INFO(S_MSG('PA6F','Kode Pegawai tidak boleh kosong'));
		return;
	}
	$qPERSON = OpenTable('PersonMain', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON' and DELETOR=''");
	if ($qPERSON) {
		if(SYS_ROWS($qPERSON)>0){
			MSG_INFO(S_MSG('PA1Z','Kode Kary sudah ada'));
			return;
		}
	}
	$cCUST=$_POST['ADD_CUSTOMER'];
	if ($cCUST=='') {
		MSG_INFO(S_MSG('F040','Kode Customer tidak boleh kosong'));
		return;
	}
	RecUpdate('PersonMain', ['PRSON_CODE', 'PRSON_SLRY', 'JOIN_DATE', 'JOB_DATE', 'UP_DATE', 'UPD_DATE'], 
		[$cPERSON, 0, DMY_YMD($_POST['UPD_TGL_MSK']), DMY_YMD($_POST['UPD_TGL_TMK']), $cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$cAPP' and DELETOR=''");
	RecUpdate('PrsOccuption', ['PRSON_CODE', 'JOB_CODE', 'CUST_CODE', 'KODE_LOKS', 'UP_DATE', 'UPD_DATE'], [$cPERSON, $_POST['ADD_JOB'], $cCUST, $_POST['ADD_LOKASI'], $cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$cAPP' and DELETOR=''");
	RecUpdate('People', ['PEOPLE_CODE', 'UP_DATE', 'UPD_DATE'], [$cPERSON, $cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$cAPP' and DELETOR=''");
	RecUpdate('PeopleNickName', ['PRSON_CODE'], [$cPERSON] , "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$cAPP'");
	RecUpdate('PeopleAddress', ['PEOPLE_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$cAPP'");
	RecUpdate('PplResAdrs', ['PEOPLE_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$cAPP'");
	RecUpdate('PeopleEMail', ['PEOPLE_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$cAPP'");
	RecUpdate('PeopleHomePhone', ['PEOPLE_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$cAPP'");
	RecUpdate('PeopleTelegram', ['PEOPLE_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$cAPP'");
	RecUpdate('PeopleBlood', ['PEOPLE_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$cAPP'");
	RecUpdate('PrsSpouse', ['PRSON_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$cAPP'");
	RecUpdate('PrsFamCardHdr', ['PRSON_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$cAPP'");
	RecUpdate('PrsFamCardDtl', ['PRSON_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$cAPP'");
	RecUpdate('PrsEducation', ['PRSON_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$cAPP'");
	RecUpdate('PrsEdNonFormal', ['PRSON_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$cAPP'");
	RecUpdate('PrsLicense', ['PERSON_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PERSON_CODE='$cAPP'");
	RecUpdate('PrsMemberCard', ['PERSON_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PERSON_CODE='$cAPP'");
	RecUpdate('PeopleContact', ['PEOPLE_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$cAPP'");
	RecUpdate('PrsExperience', ['PRSON_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$cAPP'");
	RecUpdate('PrsNumber', ['PRSON_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$cAPP'");
	RecUpdate('PrsSize', ['PRSON_CODE'], [$cPERSON], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$cAPP'");
	// $cFOTO_PELAMAR = S_PARA('FTP_PERSON_IMG', '../www/images/person/').$cAPP_CODE.'_PRS_FOTO_'.$cAPP.'.jpg';
	// $cFOTO_PEGAWAI = S_PARA('FTP_PERSON_IMG', '../www/images/person/').$cAPP_CODE.'_PRS_FOTO_'.$cPERSON.'.jpg';
	$cFOTO_PELAMAR = '../www/images/person/'.$cAPP_CODE.'_PRS_FOTO_'.$cAPP.'.jpg';
	$cFOTO_PEGAWAI = '../www/images/person/'.$cAPP_CODE.'_PRS_FOTO_'.$cPERSON.'.jpg';
	if(file_exists($cFOTO_PELAMAR))	{
		if(!rename($cFOTO_PELAMAR, $cFOTO_PEGAWAI)) {
			print_r2('Failed to rename from : '.$cFOTO_PELAMAR.' to : '.$cFOTO_PEGAWAI);
		}
	} 
	// else print_r2('Image file does not exist : '.$cFOTO_PELAMAR.' => '.$cFOTO_PEGAWAI);
	$ADD_LOG	= APP_LOG_ADD($cHEADER, S_MSG('PG8H','Pemasukan data karyawan dari lamaran kerja').' : '.$cPERSON);
	header('location:prs_inq_app.php');
break;
case 'app_delete':
	$cPERSON='';
	if (isset($_GET['_id'])) $cPERSON=$_GET['_id'];
	if ($cPERSON=='')	return;
	RecDelete('PersonMain', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('People', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPERSON'");
	RecDelete('PeopleAddress', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPERSON'");
	RecDelete('PplResAdrs', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPERSON'");
	RecDelete('PeopleEMail', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPERSON'");
	RecDelete('PeopleBlood', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPERSON'");
	RecDelete('PeopleContact', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPERSON'");
	RecDelete('PeopleNickName', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PeopleTelegram', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPERSON'");
	RecDelete('PeopleHomePhone', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPERSON'");
	RecDelete('PrsSpouse', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PrsFamCardHdr', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PrsFamCardDtl', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PrsEducation', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PrsEdNonFormal', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PrsLicense', "APP_CODE='$cAPP_CODE' and PERSON_CODE='$cPERSON'");
	RecDelete('PrsMemberCard', "APP_CODE='$cAPP_CODE' and PERSON_CODE='$cPERSON'");
	RecDelete('PrsOccuption', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PrsExperience', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PrsNumber', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PrsSize', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PrsSkill', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PrsAppOther', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PrsApplc', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$cPERSON'");
	RecDelete('PeopleApp', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPERSON'");
	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Delete : '. $cPERSON);
	header('location:prs_inq_app.php');
	break;
}
?>
<script>
function FILT_PERSON(p_JAB, _PROV, p_GEND, p_LOKS) {
	if (p_LOKS='') {
		p_LOKS = '';
	}
	window.location.assign("?_j="+p_JAB + "&_p="+_PROV + "&_g="+p_GEND + "&_l="+p_LOKS);
}

</script>
