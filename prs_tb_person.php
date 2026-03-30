<?php
//	prs_tb_person.php //
//	SELECT *, COUNT(*) c FROM sys_msg GROUP BY MSG_CODE, APP_CODE HAVING c > 1
//	TODO : upload foto

include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];	
	$cUSERCODE = $_SESSION['gUSERCODE'];
	if(isset($_GET['_b'])) $_SESSION['CALLER']='APP';
	else	$_SESSION['CALLER']='PERSON';
	$cHELP_FILE = 'Doc/Personalia - Karyawan.pdf';

	$cHEADER 		= S_MSG('PA01','Master Pegawai');
	$can_CREATE = TRUST($cUSERCODE, 'PRS_PERSON_MST_1ADD');

	$aGOL_DAR = array(1=> 'A ', 'B ', 'AB', 'O');
	$aSTATUS  = array(1=> S_MSG('PB58','Lajang'), S_MSG('PB59','Kawin'), S_MSG('PB60','Cerai'));

	$ada_OUTSOURCING=IS_OUTSOURCING($cAPP_CODE);
	$qREC_RELGN=OpenTable('TbReligion');
	if (!$qREC_RELGN)			MSG_INFO(S_MSG('PH55', 'Kode Agama tidak boleh kosong'));
	$cKODE			= S_MSG('PA02','Kode Peg'); $cNAMA=S_MSG('PA03','Nama');
	$cGENDER		= S_MSG('F018','Jenis');
	$cALAMAT 		= S_MSG('F005','Alamat');
	$cKOTA			= S_MSG('NL54','Kota');
	$cERTE			= S_MSG('PA36','RT');
	$cERWE			= S_MSG('PA37','RW');
	$cKELURAHAN		= S_MSG('PA38','Kelurahan');
	$cKECAMATAN		= S_MSG('PA39','Kec');
	$cPROPINSI		= S_MSG('CB81','Propinsi');
	$cKODE_POS		= S_MSG('H650','Kode Pos');
	$cNO_TELP		= S_MSG('PA33','No. Telpon');
	$cHOMEPHN		= S_MSG('F006','Nomor Telpon');
	$cNO_HAPE		= S_MSG('PA34','Nomor HP');
	$cTMP_LAHIR		= S_MSG('PA05','Tempat Lahir');
	$cTGL_LAHIR		= S_MSG('PA06','Tanggal Lahir');
	$cNO_KTP		= S_MSG('PA40','No. KTP');
	$cLBL_PRIA		= S_MSG('PD12','Pria');
	$cLBL_WANITA	= S_MSG('PD13','Wanita');
	$cEMAIL_ADR		= S_MSG('F105','Email Address');
	$cWEB_SITE		= S_MSG('H522','Web site');
	$cNO_REK		= S_MSG('PA07','No. Rekening');
	$cNAMA_BANK		= S_MSG('PA08','Nama Bank');
	$cAGAMA			= S_MSG('PB50','Agama');
	$cSTATUS		= S_MSG('PB57','Status');
	$cJML_ANAK		= S_MSG('PB61','Jml. Anak');
	$cPASANGAN		= S_MSG('PB62','Nama Pasangan');
	$cTGL_RESIGN	= S_MSG('PB65','Tgl. Berhenti');
	$cGOL_DAR		= S_MSG('PB63','Golongan Darah');
	$cPERUSAHAAN	= S_MSG('PA50','Perusahaan Tempat Bekerja');
	$cJABATAN		= S_MSG('PA43','Jabatan');
	$cALMT_KTR		= S_MSG('PA52','Alamat Kantor');

	$cTTIP_NAMA		= S_MSG('PA12','Nama Pegawai Menurut KTP');
	$cTTIP_ALM1		= S_MSG('PA13','Alamat Pegawai');
	$cTTIP_KOTA		= S_MSG('PA15','Kota tempat tinggal Pegawai yang sekarang');
	$cTTIP_KPOS		= S_MSG('PA16','Kode pos sesuai alamat di KTP');

	$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');

	$cTAB_UMUM		= S_MSG('PD22','Umum');
	$cTAB_GAJI		= S_MSG('PA21','Gaji');
	$cTAB_FOTO		= S_MSG('PA31','Foto Pegawai');
	$cTAB_JDW_KERJA	= S_MSG('PB41','Jadwal kerja');
	$cTAB_PENDIDIKAN= S_MSG('PA91','Pendidikan');
	$cTAB_PENEMPATAN= ($_SESSION['CALLER']=='PERSON' ? S_MSG('PF11','Penempatan') : $cJABATAN);
	$cLOKASI		= S_MSG('PF16','Lokasi');
	$cTAB_KKELUARGA	= S_MSG('PG01','Kartu Keluarga');
	$cTAB_SKILL		= S_MSG('PG49','Skill');
	
	$cGAJI_POKOK	= S_MSG('PA23','Gaji Pokok');
	$cGAJI_MULAI	= S_MSG('PA24','Mulai berlaku');
	$cADD_DTL_TUNJ 	= S_MSG('PA81','Tambah Tunj baru');
	$cNAMA_TUNJANGAN	= S_MSG('PA62','Nama Tunjangan');
	$cNILAI_TUNJANGAN	= S_MSG('PA63','Nilai Tunjangan');
	$cSAT_TUNJANGAN	= S_MSG('PA64','Satuan');
	$cJML_TUNJANGAN	= S_MSG('TP36',' Jumlah ');
	$cCATATAN		= S_MSG('PF25','Catatan');
	$cOUT_JABATAN	= S_MSG('PF13','Jabatan');
	$cCUSTOMER		= S_MSG('PF15','Customer');
	$cEDIT_PDIDIKAN	= S_MSG('PA90','Edit Pendidikan');
	$cKD_PENDIDIKAN	= S_MSG('PA94','Pendidikan');
	$cNM_PENDIDIKAN	= S_MSG('PA95','Nama Pendidian');
	$cJRSN_PNDDKKAN	= S_MSG('PA9A','Jurusan');
	$cT1_PENDIDIKAN	= S_MSG('PA96','Tahun masuk');
	$cT2_PENDIDIKAN	= S_MSG('PA97','Tahun keluar');
	$cKT_PENDIDIKAN	= S_MSG('PA98','Keterangan');
	$cADD_DTL_PEND 	= S_MSG('PA99','Tambah Pend baru');
	$cUPD_KKELUARGA	= S_MSG('PG09','Edit kartu keluarga');
	$cNO_KKELUARGA 	= S_MSG('PG03','Nomor kartu keluarga');
	$cNM_KKELUARGA 	= S_MSG('PG05','Nama Kepala Kel');
	$cFULL_NAME 	= S_MSG('PG11','Nama Lengkap');
	$cNIK_NIKS		= S_MSG('PG12','NIK/NIKS');
	$cPENDIDIKAN	= S_MSG('PA91','Pendidikan');
	$cPEKERJAAN		= S_MSG('PA41','Pekerjaan');
	$cSTATUS		= S_MSG('PB57','Status');
	$cKWARGA		= S_MSG('PG16','Kewarganegaraan');
	$cSTA_TUS		= 'Status dalam keluarga';
	$cPASSPORT		= S_MSG('PA8B','No. Passport');
	$cKITAS			= S_MSG('PA8C','No.KITAS/KITAP');
	$cAYAH			= S_MSG('PA8D','Ayah');
	$cIBU			= S_MSG('PA8E','Ibu');
	$cPENDDK		= S_MSG('PA94','Pendidian');
	$cADD_DTL_KK 	= S_MSG('PG08','Tambah KK baru');
	$cBPJS			= S_MSG('PG07','No. BPJS');

	$cNAMA_SKILL	= S_MSG('PG53','Nama Keahlian');
	$cKET_SKILL		= S_MSG('PA98','Keterangan');
	$cTHN_SKILL		= S_MSG('PG67','Tahun');
	$cSERT_SKILL	= S_MSG('PG68','No. Sertifikat');
	$cREG_SKILL		= S_MSG('PG69','No. Registrasi');
	$cNOTE_SKILL	= S_MSG('F019','Catatan');
	$cTMB_SKILL 	= S_MSG('PG48','Tambah Skill baru');
	$cTGL_MASUK		= S_MSG('PB64','Mulai Kerja');
	$cTGL_TMK		= S_MSG('PB67','Tanggal TMK');
	$cHEIGHT		= S_MSG('PA0A','Tinggi badan');
	$cWEIGHT		= S_MSG('PA0B','Berat badan');

	$cSAVE_DATA		= S_MSG('F301','Save');
	$cCLOSE_DATA	= S_MSG('F302','Close');
	$cMSG_DEL		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');

	$cACTION='';
	if(isset($_GET['_a'])) $cACTION=$_GET['_a'];
	if(isset($_GET['_b'])) $_SESSION['CALLER']=$_GET['_b'];
	$cCALLER=$_SESSION['CALLER'];

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'View');
		require_once("prs_tb_person_def.php");
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('upd_dtl_skill'):
		$cHEADER='Edit Skill';
		$can_DELETE = TRUST($cUSERCODE, 'PRS_PERSON_MST_3DEL');	
		$cREC_ID = $_GET['_r'];
		$qUPD_SKILL=OpenTable('PrsSkill', "A.REC_ID='$cREC_ID'");
		$rUPD_SKILL=SYS_FETCH($qUPD_SKILL);
		DEF_WINDOW($cHEADER);
			$aACT = ($can_DELETE ? ['<a href="?_a=skill_dtl_del&_r='. $cREC_ID. '" onClick="return confirm('. "'". $cMSG_DEL. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cHEADER, '?_a=db_upd_skill&id='.$cREC_ID, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cNAMA_SKILL);
					SELECT([5,5,5,6], 'UPD_SKILL_CODE');
						$REC_SKIL=OpenTable('TbSkill', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SKILL_NAME');
						while($aREC_SKILL=SYS_FETCH($REC_SKIL)){
							if($rUPD_SKILL['SKILL_CODE']==$aREC_SKILL['SKILL_CODE'])
								echo "<option value='$aREC_SKILL[SKILL_CODE]' selected='$rUPD_SKILL[SKILL_CODE]' >$aREC_SKILL[SKILL_NAME]</option>";
							else
								echo "<option value='$aREC_SKILL[SKILL_CODE]'  >$aREC_SKILL[SKILL_NAME]</option>";
						}
					echo '</select><br>';
					LABEL([4,4,4,6], '700', $cKET_SKILL);
					INPUT('text', [7,7,7,6], '900', 'UPD_SKILL_DESC', DECODE($rUPD_SKILL['SKILL_DESC']), '', '', '', 0, '', 'Fix');
					LABEL([4,4,4,6], '700', $cTHN_SKILL);
					INPUT('text', [1,1,1,6], '900', 'UPD_YEAR_SKILL', $rUPD_SKILL['YEAR_SKILL'], '', '', '', 0, '', 'Fix');
					LABEL([4,4,4,6], '700', $cSERT_SKILL);
					INPUT('text', [5,5,5,6], '900', 'UPD_SKILL_SERT', $rUPD_SKILL['SKILL_SERT'], '', '', '', 0, '', 'Fix');
					LABEL([4,4,4,6], '700', 'No. Registrasi');
					INPUT('text', [5,5,5,6], '900', 'UPD_SKILL_REG', $rUPD_SKILL['SKILL_REG'], '', '', '', 0, '', 'Fix');
					LABEL([4,4,4,6], '700', $cNOTE_SKILL);
					INPUT('text', [5,5,5,6], '900', 'UPD_SKILL_NOTE', $rUPD_SKILL['SKILL_NOTE'], '', '', '', 0, '', 'Fix');
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;
	
	case md5('edit_dtl_tunjangan'):
		$eDTL_REC_NO = $_GET['_r'];
		$q_PERSON2=OpenTable('PrsAllowance', "REC_ID=$eDTL_REC_NO");
		$r_PERSON2=SYS_FETCH($q_PERSON2);
		$cHEADER='Edit Tunjangan';
		DEF_WINDOW($cHEADER);
			$aACT = ['<a href="?_a=tnj_dtl_delete&id='. $eDTL_REC_NO. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'];
			TFORM($cHEADER, '?_a=DB_UPD_TUNJ&id='.$eDTL_REC_NO, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cNAMA_TUNJANGAN);
                	SELECT([5,5,5,6], 'UPD_DTL_TUNJANGAN');
						$r_PERSONT =OpenTable('TbAllowance', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_DTL_TUNJ=SYS_FETCH($r_PERSONT)){
							if($r_PERSON2['TNJ_CODE'] == $aREC_DTL_TUNJ['TNJ_CODE'])
								echo "<option class='col-sm-4 form-label-900' value='$aREC_DTL_TUNJ[TNJ_CODE]' selected='$REC_EDIT[TNJ_CODE]'>$aREC_DTL_TUNJ[TNJ_NAME]</option>";
							else 
								echo "<option value='$aREC_DTL_TUNJ[TNJ_CODE]'  >$aREC_DTL_TUNJ[TNJ_NAME]</option>";
						}
					echo '</select><br>';
					LABEL([4,4,4,6], '700', $cNILAI_TUNJANGAN);
					INPUT('text', [2,2,2,6], '900', 'UPD_TUNJ_NILAI', $r_PERSON2['TNJ_AMNT'], '', 'fdecimal', 'right', 0, '', 'Fix');
					LABEL([4,4,4,6], '700', $cSAT_TUNJANGAN);
                	SELECT([2,2,2,6], 'UPD_DTL_SATUAN_TUNJ');
						$REC_UNIT=OpenTable('PrsTbUnit');
						while($aREC_UNIT=SYS_FETCH($REC_UNIT)){
							if($aREC_DETAIL['TIME_UNIT'] == $aREC_UNIT['UNIT_CODE'])
								echo "<option value='$aREC_UNIT[UNIT_CODE]' selected='$REC_EDIT[UNIT_CODE]'>$aREC_UNIT[UNIT_NAME]</option>";
							else 
								echo "<option value='$aREC_UNIT[UNIT_CODE]'  >$aREC_UNIT[UNIT_NAME]</option>";
						}
					echo '</select>';
					CLEAR_FIX();
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case 'EDIT_EDU':
		$eDTL_REC_NO = $_GET['_r'];
		$qQUERY=OpenTable('PrsEducation', "REC_ID=$eDTL_REC_NO");
		$aREC_PERSON5 = SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_PDIDIKAN);
			$aACT = (TRUST($cUSERCODE, 'PRS_PTAB_EDU_UPD') ? ['<a href="?_a=pnd_dtl_delete&id='. $eDTL_REC_NO. '" onClick="return confirm('. "'". $cMSG_DEL. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_PDIDIKAN, '?_a=pdd_rubah&_id='.$eDTL_REC_NO, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cKD_PENDIDIKAN);
					SELECT([5,5,5,6], 'UPD_DTL_PENDIDIKAN');
						$REC_TBL_PEND=OpenTable('TbEducation', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_TBL_PEND=SYS_FETCH($REC_TBL_PEND)){
							if($aREC_PERSON5['EDU_CODE'] == $aREC_TBL_PEND['EDU_CODE']){
								echo "<option class='col-sm-4 form-label-900' value='$aREC_TBL_PEND[EDU_CODE]' selected='$REC_EDIT[EDU_CODE]'>$aREC_TBL_PEND[EDU_NAME]</option>";
							} else { 
								echo "<option value='$aREC_TBL_PEND[EDU_CODE]'  >$aREC_TBL_PEND[EDU_NAME]</option>";
							}
						}
					echo '</select><br>';
					CLEAR_FIX();
					LABEL([4,4,4,6], '700', $cNM_PENDIDIKAN);
					INPUT('text', [3,3,3,6], '900', 'UPD_EDU_DESC', $aREC_PERSON5['EDU_DESC'], '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cJRSN_PNDDKKAN);
					INPUT('text', [6,6,6,6], '900', 'UPD_DTL_JURUSAN', $aREC_PERSON5['EDU_JRSN'], '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cT1_PENDIDIKAN);
					INPUT('text', [1,1,1,6], '900', 'UPD_YEAR_IN', $aREC_PERSON5['YEAR_IN'], '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cT2_PENDIDIKAN);
					INPUT('text', [1,1,1,6], '900', 'UPD_YEAR_OUT', $aREC_PERSON5['YEAR_OUT'], '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cCATATAN);
					INPUT('text', [6,6,6,6], '900', 'UPD_EDU_NOTE', $aREC_PERSON5['EDU_NOTE'], '', '', '', 0, '', 'fix');
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case 'db_upd_skill':
	//	TODO : ?
		echo "<script> window.history.back();	</script>";
		break;
	case 'AddLokasi':
		$KODE_PEG=$_GET['_e'];
		$cLOKASI = $_POST['ADD_GEO_CODE'];
		if($cLOKASI)
			RecCreate('PplGeoAdd', ['PEOPLE_ID', 'GEO_CODE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'],
				[$KODE_PEG, $cLOKASI, $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s")]);
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Add Lokasi : '.$KODE_PEG);
		SYS_DB_CLOSE($DB2);	
		echo "<script> window.history.back();	</script>";
		break;
		
	case 'kk_rubah':
		$cRECID=$_GET['_r'];
		$cAGAMA=($_POST['UPD_RELI_GION'] ? $_POST['UPD_RELI_GION'] : '');
		$cBIRTH=$_POST['UPD_BIRTH_DATE'];
		if(!$cBIRTH)		$cBIRTH='0000-00-00';
		RecUpdate('PrsFamCardDtl', ['FULL_NAME', 'N_I_K', 'GENDER', 'BIRTH_PLCE', 'BIRTH_DATE', 'RELI_GION', 'EDUCATE', 
			'J_O_B', 'STATUS_MAR', 'STA_TUS', 'CITI_ZEN', 'FATH_NAME', 'MOTH_NAME'],
				[$_POST['UPD_FULL_NAME'], $_POST['UPD_N_I_K'], $_POST['UPD_GENDER'], $_POST['UPD_BIRTH_PLCE'], $cBIRTH, $cAGAMA, $_POST['UPD_EDUCATE'],
				$_POST['UPD_J_O_B'], $_POST['UPD_STATUS_MAR'], $_POST['UPD_STA_TUS'], $_POST['UPD_CITI_ZEN'],
				$_POST['UPD_FATH_NAME'], $_POST['UPD_MOTH_NAME']], "REC_ID='$cRECID'");
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Upd KK : '.$cRECID);
		SYS_DB_CLOSE($DB2);	
		header('location:prs_tb_person.php');
	break;

	case md5('edit_dtl_kkeluarga'):
		$cKK_REC_ID = $_GET['_r'];
		$qQUERY=OpenTable('PrsFamCardDtl', "REC_ID=$cKK_REC_ID");
		$rPERSON7=SYS_FETCH($qQUERY);
		DEF_WINDOW($cUPD_KKELUARGA);
			$aACT = (TRUST($cUSERCODE, 'PRS_PTAB_KK_UPD')==1 ? ['<a href="?_a=dtl_kkeluarga_delete&id='. $cKK_REC_ID. '" onClick="return confirm('. "'". $cMSG_DEL. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cUPD_KKELUARGA, '?_a=kk_rubah&_r='.$cKK_REC_ID, $aACT, $cHELP_FILE);
				LABEL([4,4,4,6], '700', $cFULL_NAME);
				INPUT('text', [7,7,7,6], '900', 'UPD_FULL_NAME', $rPERSON7['FULL_NAME'], '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cNIK_NIKS);
				INPUT('text', [4,4,4,6], '900', 'UPD_N_I_K', $rPERSON7['N_I_K'], '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cGENDER);
				RADIO('UPD_GENDER', [1,2], [$rPERSON7['GENDER']==1, $rPERSON7['GENDER']==2], [$cLBL_PRIA, $cLBL_WANITA]);
				LABEL([4,4,4,6], '700', $cTMP_LAHIR);
				INPUT('text', [4,4,4,6], '900', 'UPD_BIRTH_PLCE', $rPERSON7['BIRTH_PLCE'], '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cTGL_LAHIR);
				INPUT_DATE([3,3,3,6], '900', 'UPD_BIRTH_DATE', $rPERSON7['BIRTH_DATE'], '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cAGAMA);
				SELECT([2,2,2,6], 'UPD_RELI_GION');
					$qPRS_RLGN=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					echo "<option value=' '  > </option>";
					while($aREC_PRS_RLGN=SYS_FETCH($qPRS_RLGN)){
						if($rPERSON7['RELI_GION'] == $aREC_PRS_RLGN['KODE']){
							echo "<option class='col-sm-3 form-label-900' value='$aREC_PRS_RLGN[KODE]' selected='$aREC_PRS_RLGN[KODE]'>$aREC_PRS_RLGN[NAMA]</option>";
						} else { 
							echo "<option class='form-label-900' value='$aREC_PRS_RLGN[KODE]'  >$aREC_PRS_RLGN[NAMA]</option>";
						}
					}
				echo '</select>';
				CLEAR_FIX();
				LABEL([4,4,4,6], '700', $cKD_PENDIDIKAN);
				SELECT([5,5,5,6], 'UPD_EDUCATE');
					$qQUERY=OpenTable('TbEducation', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					echo "<option value=' '  > </option>";
					while($aREC_PNDDKN=SYS_FETCH($qQUERY)){
						if($rPERSON7['EDUCATE'] == $aREC_PNDDKN['EDU_CODE']){
							echo "<option class='col-sm-3 form-label-900' value='$aREC_PNDDKN[EDU_CODE]' selected='$rPERSON7[EDUCATE]'>$aREC_PNDDKN[EDU_NAME]</option>";
						} else { 
							echo "<option value='$aREC_PNDDKN[EDU_CODE]'  >$aREC_PNDDKN[EDU_NAME]</option>";
						}
					}
				echo '</select><br>';
				LABEL([4,4,4,6], '700', $cPEKERJAAN);
				INPUT('text', [6,6,6,6], '900', 'UPD_J_O_B', $rPERSON7['J_O_B'], '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cSTATUS);
				SELECT([2,2,2,6], 'UPD_STATUS_MAR');
					$qSTATUS=OpenTable('TbStatus', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					echo "<option value=' '  > </option>";
					while($aREC_STATUS=SYS_FETCH($qSTATUS)){
						if($rPERSON7['STATUS_MAR'] == $aREC_STATUS['KODE']){
							echo "<option class='col-sm-3 form-label-900' value='$aREC_STATUS[KODE]' selected='$rPERSON7[STATUS_MAR]'>$aREC_STATUS[NAMA]</option>";
						} else { 
							echo "<option value='$aREC_STATUS[KODE]'  >$aREC_STATUS[NAMA]</option>";
						}
					}
				echo '</select>';
				CLEAR_FIX();

				LABEL([4,4,4,6], '700', $cSTA_TUS);
				INPUT('text', [4,4,4,6], '900', 'UPD_STA_TUS', $rPERSON7['STA_TUS'], '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cKWARGA);
				INPUT('text', [4,4,4,6], '900', 'UPD_CITI_ZEN', $rPERSON7['CITI_ZEN'], '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cAYAH);
				INPUT('text', [4,4,4,6], '900', 'UPD_FATH_NAME', $rPERSON7['FATH_NAME'], '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cIBU);
				INPUT('text', [4,4,4,6], '900', 'UPD_MOTH_NAME', $rPERSON7['MOTH_NAME'], '', '', '', 0, '', 'fix');
				SAVE($cSAVE_DATA);
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

// - ==================================================================================================== 

	case md5('CR34T3_PEG'):
		require_once("prs_tb_person_add.php");
		break;

	case md5('UP_DATE_PEG'):
		require_once("prs_tb_person_upd.php");
		SYS_DB_CLOSE($DB2);	break;

	case 'addNew':
		$cPERSON_CODE = $_POST['ADD_PRSON_CODE'];
		if($cPERSON_CODE==''){
			MSG_INFO(S_MSG('PA6F','Kode Pegawai tidak boleh kosong'));
			return;
		}
		if($_POST['ADD_PRSON_NAME']==''){
			MSG_INFO(S_MSG('PA6E','Nama Pegawai tidak boleh kosong'));
			return;
		}
		// if($_POST['ADD_PRSON_GEND']==0){
		// 	MSG_INFO('Gender tidak boleh kosong');
		// 	return;
		// }
		// if($_POST['ADD_ADDRESS']==''){
		// 	MSG_INFO('Alamat tidak boleh kosong');
		// 	return;
		// }
		if($_POST['ADD_PROPINSI']==''){
			MSG_INFO('Propinsi tidak boleh kosong');
			return;
		}
		if($ada_OUTSOURCING) {
			if($_POST['ADD_CUST']==''){
				MSG_INFO('Customer tidak boleh kosong');
				return;
			}
		}
		
		if($ada_OUTSOURCING) {
			if ((!isset($_POST['ADD_LOC']) || $_POST['ADD_LOC']=='')){
			MSG_INFO('Lokasi penempatan tidak boleh kosong');
			return;
			}
		}
		if(!isset($_POST['ADD_JOB']) || $_POST['ADD_JOB']=='' ) {
			MSG_INFO('Jabatan tidak boleh kosong');
			return;
		}
		$cJOB=$_POST['ADD_JOB'];
		$qQUERY=OpenTable('PersonMain', "PRSON_CODE='$_POST[ADD_PRSON_CODE]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		if(SYS_ROWS($qQUERY)>0){
			MSG_INFO(S_MSG('PA6J','Kode Pegawai sudah ada'));
			return;
		}

		$nJML_ANAK 	= ($_POST['ADD_CHILD_HAVE'] ? (integer)$_POST['ADD_CHILD_HAVE'] : 0);
		$nAGAMA		= 1;
		if (isset($_POST['ADD_PRSON_RELG'])) {
			if ($_POST['ADD_PRSON_RELG']!='')	$nAGAMA	= $_POST['ADD_PRSON_RELG'];
		}
		$NOW = date("Y-m-d H:i:s");
		RecCreate('PersonMain', ['PRSON_CODE', 'PRSON_GEND', 'PRSN_RT', 'PRSN_RW', 'PRS_PHN','PRS_KTP', 'BIRTH_PLC', 'BIRTH_DATE', 'PRSON_ACCN', 'PRSON_BANK', 'PRSON_RELG', 'MARRIAGE', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], 
			[$cPERSON_CODE, $_POST['ADD_PRSON_GEND'], $_POST['ADD_RT'], $_POST['ADD_RW'], $_POST['ADD_PRS_PHN'], $_POST['ADD_PRS_KTP'], $_POST['ADD_BIRTH_PLC'], DMY_YMD($_POST['ADD_BIRTH_DATE']), $_POST['ADD_PRSON_ACCN'], $_POST['ADD_PRSON_BANK'], $nAGAMA, $_POST['ADD_MARRIAGE'], $cUSERCODE, $NOW, $cAPP_CODE]);

		$NOW = date("Y-m-d H:i:s");
		RecCreate('People', ['PEOPLE_CODE', 'PEOPLE_NAME', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], [$cPERSON_CODE, $_POST['ADD_PRSON_NAME'], $cUSERCODE, $NOW, $cAPP_CODE]);

		$cAREA=$_POST['ADD_PRSN_KEL'];
		if(!$cAREA)	$cAREA=$_POST['ADD_PRSN_KEC'];
		if(!$cAREA)	$cAREA=$_POST['ADD_KAB_KOTA'];
		if(!$cAREA)	$cAREA=$_POST['ADD_PROPINSI'];
		RecCreate('PeopleAddress', ['PEOPLE_CODE', 'PEOPLE_ADDRESS', 'AREA_CODE', 'PEOPLE_ZIP', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, ENCODE($_POST['ADD_ADDRESS']), $cAREA, $_POST['ADD_PRS_ZIP'], $cAPP_CODE, NowMSecs()]);

		$cHOME_PHONE=$_POST['ADD_HOMEPHN'];
		if ($cHOME_PHONE!=''){
			RecCreate('PeopleHomePhone', ['PEOPLE_CODE', 'HOME_PHONE', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cHOME_PHONE, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
		}
		$cEMAIL=$_POST['ADD_PRS_EMAIL'];
		if ($cHOME_PHONE!=''){
			RecCreate('PeopleEMail', ['PEOPLE_CODE', 'PPL_EMAIL', 'PPL_WEB', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cEMAIL, $_POST['ADD_PRS_WEB'], $cUSERCODE, $cAPP_CODE, NowMSecs()]);
		}
		$cBLOOD=$_POST['ADD_BLOOD_GRUP'];
		if ($cBLOOD!=''){
			RecCreate('PeopleBlood', ['PEOPLE_CODE', 'PEOPLE_BLOOD_GROUP', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cBLOOD, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
		}
		$cSPOUSE=$_POST['ADD_SPOUSE'];
		if ($cSPOUSE!=''){
			RecCreate('PrsSpouse', ['PRSON_CODE', 'SPOUSE_NAME', 'CHILD_HAVE', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cSPOUSE, $nJML_ANAK, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
		}

		$cEDUCAT='';
		if(isset($_POST['ADD_EDU_CODE'])) $cEDUCAT=$_POST['ADD_EDU_CODE'];
		if($cEDUCAT!=''){
			$nYIN=$_POST['ADD_YEAR_IN'];
			if(!$nYIN)		$nYIN=0;
			$nYOT=$_POST['ADD_YEAR_OUT'];
			if(!$nYOT)		$nYOT=0;
			RecCreate('PrsEducation', ['PRSON_CODE', 'EDU_CODE', 'EDU_DESC', 'EDU_JRSN', 'YEAR_IN', 'YEAR_OUT', 'EDU_NOTE', 'ENTRY', 'APP_CODE', 'REC_ID'], 
				[$cPERSON_CODE, $cEDUCAT, $_POST['ADD_EDU_DESC'], $_POST['ADD_JURUSAN'], $nYIN, $nYOT, $_POST['ADD_EDU_NOTE'], $cUSERCODE, $cAPP_CODE, NowMSecs()]);
		}

		if($_POST['ADD_SKILL_CODE']){
			$nYSK=$_POST['YEAR_SKILL'];
			if(!$nYSK)		$nYSK=0;
			RecCreate('PrsSkill', ['PRSON_CODE', 'SKILL_CODE', 'SKILL_DESC', 'YEAR_SKILL', 'SKILL_SERT', 'SKILL_NOTE', 'ENTRY', 'APP_CODE', 'REC_ID'], 
				[$cPERSON_CODE, $_POST['ADD_SKILL_CODE'], $_POST['ADD_SKILL_DESC'], $nYSK, $_POST['ADD_SKILL_SERT'], $_POST['ADD_SKILL_NOTE'], $cUSERCODE, $cAPP_CODE, NowMSecs()]);
		}

		$cCUST=(isset($_POST['ADD_CUST']) ? $_POST['ADD_CUST'] : '');
		$cLOC=(isset($_POST['ADD_LOC']) ? $_POST['ADD_LOC'] : '');
		RecCreate('PrsOccuption', ['PRSON_CODE', 'JOB_CODE', 'CUST_CODE', 'KODE_LOKS', 'OUT_NOTE', 'ENTRY', 'APP_CODE', 'DATE_ENTRY'], 
			[$cPERSON_CODE, $cJOB, $cCUST, $cLOC, $_POST['ADD_OUT_NOTE'], $cUSERCODE, $cAPP_CODE, date("Y-m-d H:i:s")]);
		$ADD_LOG	= APP_LOG_ADD("add ".$cHEADER, $cPERSON_CODE);
		SYS_DB_CLOSE($DB2);	
		echo "<script> window.history.back();	</script>";
		return;
	break;


	case 'sa_ve':
		$KODE_CRUD=$_GET['id'];
		$NOW = date("Y-m-d H:i:s");
		$cCUST=$cLOCS='';
		if( isset($_POST['UPD_CUSTOMER']) ){
			$cCUST=$_POST['UPD_CUSTOMER'];
		}
		if( isset($_POST['UPD_LOKASI']) ){
			$cLOCS=$_POST['UPD_LOKASI'];
		}

		$dTG_GAJI  = $_POST['UPD_SLRY_DATE'];		// 'dd/mm/yyyy'
		$cTG_GAJI  = substr($dTG_GAJI,6,4). '-'. substr($dTG_GAJI,3,2). '-'. substr($dTG_GAJI,0,2);

		$qQUERY=OpenTable('People', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD'");
		if(SYS_ROWS($qQUERY)==0) {
			RecCreate('People', ['PEOPLE_CODE', 'PEOPLE_NAME', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
				[$KODE_CRUD, ENCODE($_POST['UPD_PRSON_NAME']), $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s")]);
		} else {
			RecUpdate('People', ['PEOPLE_NAME', 'UP_DATE', 'UPD_DATE'], [ENCODE($_POST['UPD_PRSON_NAME']), $cUSERCODE, date("Y-m-d H:i:s")], 
				"APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$KODE_CRUD'");
		}

		$cAREA=$_POST['PILIH_KEL'];
		if(!$cAREA && isset($_POST['PILIH_KEC']))	$cAREA=$_POST['PILIH_KEC'];
		if(!$cAREA)	$cAREA=$_POST['PILIH_KAB'];
		if(!$cAREA)	$cAREA=$_POST['PILIH_PROV'];
		$cADDRESS = $_POST['UPD_ADDR1'];
		$cZIP = $_POST['UPD_PRS_ZIP'];
		$qQUERY=OpenTable('PeopleAddress', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD'");
		if(SYS_ROWS($qQUERY)==0) {
			if($cADDRESS>'') {
				RecCreate('PeopleAddress', ['PEOPLE_CODE', 'PEOPLE_ADDRESS', 'PEOPLE_ZIP', 'AREA_CODE', 'APP_CODE', 'ENTRY', 'REC_ID'], 
					[$KODE_CRUD, ENCODE($cADDRESS), $cZIP, $cAREA, $cAPP_CODE, $cUSERCODE, NowMSecs()]);
			}
		} else {
			RecUpdate('PeopleAddress', ['PEOPLE_ADDRESS', 'PEOPLE_ZIP', 'AREA_CODE'], [ENCODE($cADDRESS), $cZIP, $cAREA], 
				"APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$KODE_CRUD'");
		}

		$qQUERY=OpenTable('PeopleBlood', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qQUERY)==0) {
			RecCreate('PeopleBlood', ['PEOPLE_CODE', 'PEOPLE_BLOOD_GROUP', 'APP_CODE', 'ENTRY', 'REC_ID'], 
				[$KODE_CRUD, $_POST['UPD_BLOOD_GRUP'], $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		} else {
			RecUpdate('PeopleBlood', ['PEOPLE_BLOOD_GROUP'], [$_POST['UPD_BLOOD_GRUP']], 
				"APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$KODE_CRUD'");
		}

		$cTGL_MSK=DMY_YMD($_POST['UPD_TGL_MSK']);
		if(!$cTGL_MSK)		$cTGL_MSK='0000-00-00';
		$cJOB_DT=DMY_YMD($_POST['UPD_TGL_TMK']);
		if(!$cJOB_DT)		$cJOB_DT='0000-00-00';
		$cBIRTH=DMY_YMD($_POST['UPD_BIRTH_DATE']);
		if(!$cBIRTH)		$cBIRTH='0000-00-00';
		$qQUERY=OpenTable('PersonMain', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if(SYS_ROWS($qQUERY)==0) {
			RecCreate('PersonMain', ['PRSON_CODE', 'PRSON_GEND', 'PRSN_RT', 'PRSN_RW', 'PRS_KTP', 'PRS_PHN', 'BIRTH_PLC', 'BIRTH_DATE', 'PRSON_ACCN', 'PRSON_BANK', 'MARRIAGE', 'PRSON_RELG', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
				[$KODE_CRUD, $_POST['UPD_PRSON_GEND'], $_POST['UPD_PRSN_RT'], $_POST['UPD_PRSN_RW'], $_POST['UPD_PRS_KTP'], $_POST['UPD_PRS_HP'], $_POST['UPD_BIRTH_PLC'], $cBIRTH, $_POST['UPD_PRSON_ACCN'], $_POST['UPD_PRSON_BANK'], $_POST['UPD_MARRIAGE'], $_POST['UPD_PRSON_RELG'], $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s")]);
		} else {
			RecUpdate('PersonMain', ['PRSON_GEND', 'PRSN_RT', 'PRSN_RW', 'PRS_KTP', 'PRS_PHN', 'BIRTH_PLC', 'BIRTH_DATE', 'PRSON_ACCN', 'PRSON_BANK', 'MARRIAGE', 'PRSON_RELG', 'JOIN_DATE', 'JOB_DATE', 'UP_DATE', 'UPD_DATE'], 
				[$_POST['UPD_PRSON_GEND'], $_POST['UPD_PRSN_RT'], $_POST['UPD_PRSN_RW'], $_POST['UPD_PRS_KTP'], $_POST['UPD_PRS_HP'], 
				$_POST['UPD_BIRTH_PLC'], $cBIRTH, $_POST['UPD_PRSON_ACCN'], $_POST['UPD_PRSON_BANK'], $_POST['UPD_MARRIAGE'], $_POST['UPD_PRSON_RELG'], $cTGL_MSK, $cJOB_DT, $cUSERCODE, date("Y-m-d H:i:s")], 
				"APP_CODE='$cAPP_CODE' and  PRSON_CODE='$KODE_CRUD'");
		}

		$qQUERY=OpenTable('PrsOccuption', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if(SYS_ROWS($qQUERY)==0) {
			RecCreate('PrsOccuption', ['PRSON_CODE', 'JOB_CODE', 'CUST_CODE', 'KODE_LOKS', 'OUT_NOTE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
				[$KODE_CRUD, $_POST['UPD_PRS_JOB'], $cCUST, $cLOCS, $_POST['UPD_OUT_NOTE'], $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s")]);
		} else {
			RecUpdate('PrsOccuption', ['JOB_CODE', 'CUST_CODE', 'KODE_LOKS', 'OUT_NOTE', 'UP_DATE', 'UPD_DATE'], 
				[$_POST['UPD_PRS_JOB'], $cCUST, $cLOCS, $_POST['UPD_OUT_NOTE'], $cUSERCODE, date("Y-m-d H:i:s")], 
				"APP_CODE='$cAPP_CODE' and  PRSON_CODE='$KODE_CRUD'");
		}

		$qQUERY=OpenTable('PeopleEMail', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD'");
		if(SYS_ROWS($qQUERY)==0) {
			RecCreate('PeopleEMail', ['PEOPLE_CODE', 'PPL_EMAIL', 'PPL_WEB', 'APP_CODE', 'ENTRY', 'REC_ID'], 
				[$KODE_CRUD, ENCODE($_POST['UPD_PRS_EMAIL']), $_POST['UPD_PRS_WEB'], $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		} else {
			RecUpdate('PeopleEMail', ['PPL_EMAIL', 'PPL_WEB'], [ENCODE($_POST['UPD_PRS_EMAIL']), $_POST['UPD_PRS_WEB']], 
				"APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$KODE_CRUD'");
		}

		$cHOME_PHN = $_POST['UPD_HOME_PH'];
		$qQUERY=OpenTable('PeopleHomePhone', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and PEOPLE_CODE='$KODE_CRUD'");
		if(SYS_ROWS($qQUERY)==0) {
			if($cHOME_PHN>'') {
				RecCreate('PeopleHomePhone', ['PEOPLE_CODE', 'HOME_PHONE', 'APP_CODE', 'ENTRY', 'REC_ID'], 
					[$KODE_CRUD, $cHOME_PHN, $cAPP_CODE, $cUSERCODE, NowMSecs()]); 
			}
		} else {
			RecUpdate('PeopleHomePhone', ['HOME_PHONE'], [$cHOME_PHN], "APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$KODE_CRUD'");
		}
		
		$cSPOUSE = $_POST['UPD_SUPPOSE'];
		$nJML_ANAK = (integer)$_POST['UPD_CHILD_HAVE'];
		if ($cSPOUSE!='' || $nJML_ANAK>0) {
			$qQUERY=OpenTable('PrsSpouse', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD' and REC_ID not in ( select DEL_ID from logs_delete)");
			if (SYS_ROWS($qQUERY)==0) {
				RecCreate('PrsSpouse', ['PRSON_CODE', 'CHILD_HAVE', 'SPOUSE_NAME', 'APP_CODE', 'ENTRY', 'REC_ID'], 
					[$KODE_CRUD, $nJML_ANAK, $cSPOUSE, $cAPP_CODE, $cUSERCODE, NowMSecs()]); 
			} else {
				RecUpdate('PrsSpouse', ['CHILD_HAVE', 'SPOUSE_NAME'], [$nJML_ANAK, $cSPOUSE], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$KODE_CRUD'");
			}
		}

		$nSALARY = str_replace(',', '', $_POST['UPD_BASIC_SLRY']);
		$dSALARY = DMY_YMD($_POST['UPD_SLRY_DATE']);
		$qQUERY=OpenTable('PrsSalary', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qQUERY)==0) {
			if ($nSALARY>0) {
				RecCreate('PrsSalary', ['PRSON_CODE', 'BASIC_SLRY', 'SLRY_DATE', 'APP_CODE', 'ENTRY', 'REC_ID'], 
					[$KODE_CRUD, $nSALARY, $dSALARY, $cAPP_CODE, $cUSERCODE, NowMSecs()]); 
			}
		} else {
			RecUpdate('PrsSalary', ['BASIC_SLRY', 'SLRY_DATE'], [$nSALARY, $dSALARY], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$KODE_CRUD'");
		}

		if(IS_DELTA($cAPP_CODE))	{
			$nDELTA 	= (integer)str_replace(',', '', $_POST['UPD_DELTA']);
			$qQUERY=OpenTable('PrsDelta', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD' and DELETOR=''");
			if (SYS_ROWS($qQUERY)==0) {
				if ($nDELTA>0) {
					RecCreate('PrsDelta', ['PRSON_CODE', 'DELTA', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
						[$KODE_CRUD, $nDELTA, $cAPP_CODE, $cUSERCODE, $NOW]); 
				}
			} else {
				RecUpdate('PrsDelta', ['DELTA'], [$nDELTA], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$KODE_CRUD'");
			}
		}
		
		$cTUNJ_CODE=$_POST['ADD_TUNJANGAN'];
		$nVALUE=str_replace('.', '', $_POST['ADD_VALUE']);
		$nUNIT=(isset($_POST['ADD_SATUAN_TUNJ']) ? $_POST['ADD_SATUAN_TUNJ'] : 0);
		if($cTUNJ_CODE and $nVALUE){
			$cTIME_UNIT=( $nUNIT ? $nUNIT : 0);
			RecCreate('PrsAllowance', ['PRSON_CODE', 'TNJ_CODE', 'TNJ_AMNT', 'TIME_UNIT', 'APP_CODE', 'ENTRY', 'REC_ID'], 
				[$KODE_CRUD, $cTUNJ_CODE, $nVALUE, $cTIME_UNIT, $cAPP_CODE, $cUSERCODE, NowMSecs()]); 
		}

		if($_POST['ADD_EDU_CODE'] && $_POST['ADD_EDU_DESC']){
			$nYIN=(integer)$_POST['ADD_YEAR_IN'];
			$nYOT=(integer)$_POST['ADD_YEAR_OUT'];
			RecCreate('PrsEducation', ['PRSON_CODE', 'EDU_CODE', 'EDU_DESC', 'EDU_JRSN', 'YEAR_IN', 'YEAR_OUT', 'EDU_NOTE', 'APP_CODE', 'ENTRY', 'REC_ID'], 
				[$KODE_CRUD, $_POST['ADD_EDU_CODE'], $_POST['ADD_EDU_DESC'], $_POST['ADD_JURUSAN'], $nYIN, $nYOT, $_POST['ADD_EDU_NOTE'], $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		}

		$cNO_KKELUARGA = $_POST['UPD_NO_KKLRG'];
		$cKEP_KKELUARGA = $_POST['UPD_NM_KKLRG'];
		if($cNO_KKELUARGA!='' || $cKEP_KKELUARGA!='') {
			$qQUERY=OpenTable('PrsFamCardHdr', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD' and REC_ID not in ( select DEL_ID from logs_delete)");
			if(SYS_ROWS($qQUERY)==0) {
				if($cNO_KKELUARGA>'') {
					RecCreate('PrsFamCardHdr', ['PRSON_CODE', 'NO_KKLRG', 'NM_KKLRG', 'APP_CODE', 'ENTRY', 'REC_ID'], 
						[$KODE_CRUD, $cNO_KKELUARGA, $cKEP_KKELUARGA, $cAPP_CODE, $cUSERCODE, NowMSecs()]); 
				}
			} else {
				$aNO_KK = SYS_FETCH($qQUERY);
				RecUpdate('PrsFamCardHdr', ['NO_KKLRG', 'NM_KKLRG'], [$cNO_KKELUARGA, $cKEP_KKELUARGA], "REC_ID='$aNO_KK[REC_ID]'");
			}
		}
		if($_POST['ADD_FULL_NAME']>''){
			$cCODE = ENCODE($KODE_CRUD);
			$cNAME = ENCODE($_POST['ADD_FULL_NAME']);
			$cNIK = (isset($_POST['ADD_N_I_K']) ? $_POST['ADD_N_I_K'] : '');
			$nGENDER = (isset($_POST['ADD_GENDER']) ? $_POST['ADD_GENDER'] : 1);
			$cBIRTH=$_POST['ADD_BIRTH_DATE'];
			if(!$cBIRTH)		$cBIRTH='0000-00-00';
			RecCreate('PrsFamCardDtl', ['PRSON_CODE', 'FULL_NAME', 'N_I_K', 'GENDER', 'BIRTH_DATE', 'BIRTH_PLCE', 'RELI_GION', 'EDUCATE', 'J_O_B', 'STATUS_MAR', 'STA_TUS', 'CITI_ZEN', 'FATH_NAME', 'MOTH_NAME', 'APP_CODE', 'ENTRY', 'REC_ID'], 
				[$KODE_CRUD, $cNAME, $cNIK, $nGENDER, $cBIRTH, $_POST['ADD_BIRTH_PLCE'], $_POST['ADD_RELI_GION'], $_POST['ADD_EDUCATE'], $_POST['ADD_J_O_B'], $_POST['ADD_STATUS'], $_POST['ADD_STA_TUS'], $_POST['ADD_CITI_ZEN'], $_POST['ADD_FATH_NAME'], $_POST['ADD_MOTH_NAME'], $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		}

		if($_POST['ADD_SKILL_CODE'] && $_POST['ADD_SKILL_DESC']){
			$nYSK=$_POST['ADD_SKILL_YEAR'];
			if(!$nYSK)		$nYSK=0;
			RecCreate('PrsSkill', ['PRSON_CODE', 'SKILL_CODE', 'SKILL_DESC', 'YEAR_SKILL', 'SKILL_SERT', 'SKILL_REG', 'SKILL_NOTE', 'ENTRY', 'APP_CODE', 'REC_ID'], 
				[$KODE_CRUD, $_POST['ADD_SKILL_CODE'], $_POST['ADD_SKILL_DESC'], $nYSK, $_POST['ADD_SKILL_SERT'], $_POST['ADD_SKILL_REG'], $_POST['ADD_SKILL_NOTE'], $cUSERCODE, $cAPP_CODE, NowMSecs()]);
		}

		$cPPL_NOTES = ENCODE($_POST['UPD_CATATAN']);
		$qQUERY=OpenTable('PeopleNotes', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD' and REC_ID not in ( select DEL_ID from logs_delete)");
		if($aNOTES = SYS_FETCH($qQUERY)) {
			RecUpdate('PeopleNotes', ['PEOPLE_NOTES'], [$cPPL_NOTES, ], "APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$KODE_CRUD'");
		} else {
			if($cPPL_NOTES>'') {
				RecCreate('PeopleNotes', ['PEOPLE_CODE', 'PEOPLE_NOTES', 'APP_CODE', 'ENTRY', 'REC_ID'], 
					[$KODE_CRUD, $cPPL_NOTES, $cAPP_CODE, $cUSERCODE, NowMSecs()]); 
			}
		}
		$cUPD_NPWP = ENCODE($_POST['UPD_NPWP']);
		$cUPD_BPJS_TK = ENCODE($_POST['UPD_BPJS_TK']);
		$cUPD_BPJS_KES = ENCODE($_POST['UPD_BPJS_KES']);
		$cUPD_BPJS_ADD = $_POST['UPD_BPJS_ADD'];
		$cUPD_DPLK = ENCODE($_POST['UPD_DPLK']);
		$cUPD_SERT = ENCODE($_POST['UPD_SERT']);
		$qQUERY=OpenTable('PrsNumber', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD' and REC_ID not in ( select DEL_ID from logs_delete)");
		if($aNOMOR = SYS_FETCH($qQUERY)) {
			if($cUPD_NPWP>'' || $cUPD_BPJS_TK>'' || $cUPD_BPJS_KES>'' || $cUPD_BPJS_ADD>0 || $cUPD_DPLK>'' || $cUPD_SERT>'') {
				RecUpdate('PrsNumber', ['NO_NPWP', 'NO_BPJS_TK', 'NO_BPJS_KES', 'NO_REK_DPLK', 'NO_SERT_LAIN'], 
					[$cUPD_NPWP, $cUPD_BPJS_TK, $cUPD_BPJS_KES, $cUPD_DPLK, $cUPD_SERT], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$KODE_CRUD'");
			}
		} else {
			if($cUPD_NPWP>'' || $cUPD_BPJS_TK>'' || $cUPD_BPJS_KES>'' || $cUPD_BPJS_ADD>0 || $cUPD_DPLK>'' || $cUPD_SERT>'') {
				RecCreate('PrsNumber', ['PRSON_CODE', 'NO_NPWP', 'NO_BPJS_TK', 'NO_BPJS_KES', 'NO_REK_DPLK', 'NO_SERT_LAIN', 'APP_CODE', 'ENTRY', 'REC_ID'], 
					[$KODE_CRUD, $cUPD_NPWP, $cUPD_BPJS_TK, $cUPD_BPJS_KES, $cUPD_DPLK, $cUPD_SERT, $cAPP_CODE, $cUSERCODE, NowMSecs()]); 
			}
		}
		$nUPD_BPJS_ADD = $_POST['UPD_BPJS_ADD'];
		$qQUERY=OpenTable('PrsAddBpjs', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD' and DELETOR=''");
		if($aNOMOR = SYS_FETCH($qQUERY)) {
			if($nUPD_BPJS_ADD>'')
			RecUpdate('PrsAddBpjs', ['ADDING'], [$nUPD_BPJS_ADD], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$KODE_CRUD'");
		} else {
			if($nUPD_BPJS_ADD>'') {
				RecCreate('PrsAddBpjs', ['PRSON_CODE', 'ADDING', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
					[$KODE_CRUD, $nUPD_BPJS_ADD, $cAPP_CODE, $cUSERCODE, $NOW]); 
			}
		}
		$nHEIGHT	= (integer)str_replace(',', '', $_POST['UPD_HEIGHT']);
		$nWEIGHT	= (integer)str_replace(',', '', $_POST['UPD_WEIGHT']);
		$cSHIRT		= $_POST['UPD_SHIRT'];
		$nSHOE		= (integer)str_replace(',', '', $_POST['UPD_SHOES']);
		$nPANTS		= (integer)str_replace(',', '', $_POST['UPD_PANTS']);
		$nHEAD		= (integer)str_replace(',', '', $_POST['UPD_HEAD']);
		$nHEIGHT=($nHEIGHT ? $nHEIGHT : 0);
		$qQUERY=OpenTable('PrsSize', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qQUERY)==0) {
			if($nHEIGHT>0 || $nWEIGHT>0 || $cSHIRT>'' || $nSHOE>0 || $nPANTS>0 || $nHEAD>0) {
				RecCreate('PrsSize', ['PRSON_CODE', 'PRS_HEIGHT', 'PRS_WEIGHT', 'PRS_SHIRT', 'PRS_SHOE', 'PRS_PDL', 'PRS_HEAD', 'REC_ID', 'ENTRY', 'APP_CODE'], 
					[$KODE_CRUD, $nHEIGHT, $nWEIGHT, $cSHIRT, $nSHOE, $nPANTS, $nHEAD, NowMSecs(), $cUSERCODE, $cAPP_CODE]); 
			}
		} else {
			RecUpdate('PrsSize', ['PRS_HEIGHT', 'PRS_WEIGHT', 'PRS_SHIRT', 'PRS_SHOE', 'PRS_PDL', 'PRS_HEAD'], 
				[$nHEIGHT, $nWEIGHT, $cSHIRT, $nSHOE, $nPANTS, $nHEAD], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$KODE_CRUD'");
		}

		$cNMR_KTA = $_POST['UPD_NMR_KTA'];
		$dMASA_LAKU = $_POST['UPD_TGL_KTA'];
		if ($cNMR_KTA || $dMASA_LAKU) {
			if(!$dMASA_LAKU)	$dMASA_LAKU = '0000-00-00';
			$qQUERY=OpenTable('PrsMemberCard', "APP_CODE='$cAPP_CODE' and PERSON_CODE='$KODE_CRUD' and REC_ID not in ( select DEL_ID from logs_delete)");
			if (SYS_ROWS($qQUERY)==0) {
				RecCreate('PrsMemberCard', ['PERSON_CODE', 'CARD_NUMBER', 'VALID_UNTIL', 'APP_CODE', 'ENTRY', 'REC_ID'], 
					[$KODE_CRUD, $cNMR_KTA, $dMASA_LAKU, $cAPP_CODE, $cUSERCODE, NowMSecs()]); 
			} else {
				RecUpdate('PrsMemberCard', ['CARD_NUMBER', 'VALID_UNTIL'], [$cNMR_KTA, $dMASA_LAKU], "APP_CODE='$cAPP_CODE' and  PERSON_CODE='$KODE_CRUD'");
			}
		}

		if($cCALLER=='PERSON') {
			$l_ANYWH = (isset($_POST['UPD_ANYWHERE']) ? 1 : 0);
			$cGEO_NAME = (isset($_POST['UPD_KET_ANYW']) ? $cGEO_NAME = $_POST['UPD_KET_ANYW'] : '');
			$dSTART = '0000-00-00';
			if(isset($_POST['UPD_AWL_DATE']))	{
				$dSTART = $_POST['UPD_AWL_DATE'];
				if(!$dSTART)	$dSTART = '0000-00-00';
			}
			$dFINISH= '0000-00-00';
			if(isset($_POST['UPD_AKH_DATE']))	{
				$dFINISH = $_POST['UPD_AKH_DATE'];
				if(!$dFINISH)	$dFINISH = '0000-00-00';
			}
			$qQUERY=OpenTable('PplAnywhere', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD' and DELETOR=''");
			if($qQUERY){
				if(SYS_ROWS($qQUERY)==0) {
					if($l_ANYWH>0) {
						RecCreate('PplAnywhere', ['PEOPLE_CODE', 'GEO_NAME', 'AW_BEGIN', 'AW_END', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
							[$KODE_CRUD, $cGEO_NAME, $dSTART, $dFINISH, $cAPP_CODE, $cUSERCODE, $NOW]); 
					}
				} else {
					if($l_ANYWH==0) {
						RecUpdate('PplAnywhere', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, $NOW], "APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$KODE_CRUD'");
					} else {
						RecUpdate('PplAnywhere', ['GEO_NAME', 'AW_BEGIN', 'AW_END', 'UP_DATE', 'UPD_DATE'], [$cGEO_NAME, $dSTART, $dFINISH, $cUSERCODE, $NOW], "APP_CODE='$cAPP_CODE' and  PEOPLE_CODE='$KODE_CRUD' and DELETOR=''");
					}
				}
			} else {
				if($l_ANYWH>0) {
					RecCreate('PplAnywhere', ['PEOPLE_CODE', 'GEO_NAME', 'AW_BEGIN', 'AW_END', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
						[$KODE_CRUD, $cGEO_NAME, $dSTART, $dFINISH, $cAPP_CODE, $cUSERCODE, $NOW]); 
				}
			}

			if(isset($_POST['ADD_GEO_CODE'])) {
				$cLOKASI = $_POST['ADD_GEO_CODE'];
				if($cLOKASI)
					RecCreate('PplGeoAdd', ['PEOPLE_ID', 'GEO_CODE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'],
						[$KODE_CRUD, $cLOKASI, $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s")]);
			}
		} else {
			if(TRUST($cUSERCODE, 'PRS_PTAB_INTVW_UPD')) {
				$dTGL=$_POST['UPD_INT_DATE'];
				$nSMOKE=$nDRUGS=$nDRUNK=$nTATTO=$nANYWH=$nTRAIN=$nOK=0;
				if(isset($_POST['UPD_SMOKING']))		$nSMOKE = 1;
				if(isset($_POST['UPD_DRUGS']))			$nDRUGS = 1;
				if(isset($_POST['UPD_DRUNK']))			$nDRUNK = 1;
				if(isset($_POST['UPD_TATTO']))			$nTATTO = 1;
				if(isset($_POST['UPD_ANYWH']))			$nANYWH = 1;
				if(isset($_POST['UPD_TRAIN']))			$nTRAIN = 1;
				if(isset($_POST['UPD_OK']))				$nOK = 1;
				$qQUERY=OpenTable('PrsInterview', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD' and REC_ID not in ( select DEL_ID from logs_delete)");
				if(SYS_ROWS($qQUERY)==0) {
					RecCreate('PrsInterview', ['PRSON_CODE', 'HOBBY', 'KRONIS', 'SELF_DESC', 'ADVANTAGE', 'DIS_ADVANTAGE', 
							'SMOKING', 'DRUGS', 'DRUNK', 'TATTO', 'PRESTASI', 'EXPERIENCE', 'JOB', 'WHY', 'PICT', 'INFO', 'OUTS', 'ANY_WHERE', 'PLACE',
							'TRAINING', 'INTERVIEWER', 'CONCLUSION', 'INTERVIEW_DATE', 'REC_ID', 'ENTRY', 'APP_CODE'], 
						[$KODE_CRUD, $_POST['UPD_HOBBY'], $_POST['UPD_KRONIS'], $_POST['UPD_SELF'], $_POST['UPD_ADVT'], $_POST['UPD_DIS_ADVT'], 
							$nSMOKE, $nDRUGS, $nDRUNK, $nTATTO, $_POST['UPD_PRESTASI'], $_POST['UPD_EXPR'], $_POST['UPD_JOB'], $_POST['UPD_WHY'], $_POST['UPD_PICT'], $_POST['UPD_NFO'], $_POST['UPD_OUTS'], $nANYWH, '', 
							$nTRAIN, $_POST['UPD_INTERVIEWER'], $_POST['UPD_RESULT'], $dTGL, NowMSecs(), $cUSERCODE, $cAPP_CODE]); 
				} else {
					RecUpdate('PrsInterview', ['HOBBY', 'KRONIS', 'SELF_DESC', 'ADVANTAGE', 'DIS_ADVANTAGE', 
							'SMOKING', 'DRUGS', 'DRUNK', 'TATTO', 'PRESTASI', 'EXPERIENCE', 'JOB', 'WHY', 'PICT', 'INFO', 'OUTS', 'ANY_WHERE', 'PLACE',
							'TRAINING', 'INTERVIEWER', 'CONCLUSION', 'INTERVIEW_DATE'], 
						[$_POST['UPD_HOBBY'], $_POST['UPD_KRONIS'], $_POST['UPD_SELF'], $_POST['UPD_ADVT'], $_POST['UPD_DIS_ADVT'], 
							$nSMOKE, $nDRUGS, $nDRUNK, $nTATTO, $_POST['UPD_PRESTASI'], $_POST['UPD_EXPR'], $_POST['UPD_JOB'], $_POST['UPD_WHY'], $_POST['UPD_PICT'], $_POST['UPD_NFO'], $_POST['UPD_OUTS'], $nANYWH, '', 
							$nTRAIN, $_POST['UPD_INTERVIEWER'], $_POST['UPD_RESULT'], $dTGL], "APP_CODE='$cAPP_CODE' and  PRSON_CODE='$KODE_CRUD'");
				}
			}
		}

		if(isset($_FILES['UPLOAD_IMAGE'])) {
			// print_r2('Foto sudah ter set : '.$KODE_CRUD);
			$cFILE_DIR = S_PARA('FTP_PERSON_IMG','/home/riza/www/images/person/');
			if(IS_LOCALHOST()) $cFILE_DIR = 'data/images/person/';	// test lokal
			$cFILE_FOTO = $cAPP_CODE.'_PRS_FOTO_'.$KODE_CRUD.'.jpg';
			$UPLOAD_FILE      = $_FILES['UPLOAD_IMAGE'];
			// if (!empty($UPLOAD_FILE)){  
			// 	if(!move_uploaded_file($UPLOAD_FILE, $cFILE_DIR.$cFILE_FOTO)) {
			// 		MSG_INFO($UPLOAD_FILE.' : '.$cFILE_DIR.$cFILE_FOTO);
			// 	}
			// } // else print_r2('FOTO KOSONG');
		} else {
			// print_r2('Foto tidak ter set : '.$KODE_CRUD);
		}
		$ADD_LOG	= APP_LOG_ADD("update ".$cHEADER, $_GET['id']);
		SYS_DB_CLOSE($DB2);	
		echo "<script> window.history.back();	</script>";
	break;

	case md5('delete_p3gawai'):
		$KODE_CRUD=$_GET['_id'];
		$ADD_LOG	= APP_LOG_ADD("delete ".$cHEADER, $KODE_CRUD);
		$qQUERY=OpenTable('PeopleAddress', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_ADDRS=SYS_FETCH($qQUERY);
			if($aPPL_ADDRS)		RecSoftDel($aPPL_ADDRS['REC_ID']);
		}
		$qQUERY=OpenTable('PplResAdrs', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PeopleEMail', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PeopleBlood', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PeopleContact', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PeopleNickName', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PeopleTelegram', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PeopleHomePhone', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PrsSpouse', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PrsFamCardHdr', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PrsFamCardDtl', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PrsEducation', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PrsEdNonFormal', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PrsLicense', "APP_CODE='$cAPP_CODE' and PERSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PrsMemberCard', "APP_CODE='$cAPP_CODE' and PERSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PrsExperience', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PrsNumber', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PrsSize', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PrsSkill', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PrsAppOther', "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$qQUERY=OpenTable('PeopleApp', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD'");
		if($qQUERY) {
			$aPPL_REC=SYS_FETCH($qQUERY);
			if($aPPL_REC)		RecSoftDel($aPPL_REC['REC_ID']);
		}
		$NOW = date("Y-m-d H:i:s");
		RecUpdate('PersonMain', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, $NOW], "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		RecUpdate('PrsOccuption', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, $NOW], "APP_CODE='$cAPP_CODE' and PRSON_CODE='$KODE_CRUD'");
		RecUpdate('People', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, $NOW], "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$KODE_CRUD'");
		SYS_DB_CLOSE($DB2);	
		header('location:prs_tb_person.php');
	break;

	case 'tnj_dtl_delete':
		$DEL_ID=$_GET['id'];
		RecSoftDel($DEL_ID);
		SYS_DB_CLOSE($DB2);	
		echo "<script> window.history.go(-2);	</script>";
	break;

	case 'pnd_dtl_delete':
		$cREC_ID=$_GET['id'];
		$qEDU = OpenTable('PrsEducation', "REC_ID='$cREC_ID' and REC_ID not in ( select DEL_ID from logs_delete)");
		if($rEDU = SYS_FETCH($qEDU)) {
			$cNO_KRY = $rEDU['PRSON_CODE'];
			RecSoftDel($cREC_ID);
		}
		SYS_DB_CLOSE($DB2);	
		echo "<script> window.history.go(-2);	</script>";
	break;

	case 'DB_UPD_TUNJ':
		$KODE_CRUD=$_GET['id'];
		$NOW = date("Y-m-d H:i:s");
		RecUpdate('PrsAllowance', ['TNJ_CODE', 'TNJ_AMNT', 'TIME_UNIT'],
			[$_POST['UPD_DTL_TUNJANGAN'], str_replace(',', '', $_POST['UPD_TUNJ_NILAI']), $_POST['UPD_DTL_SATUAN_TUNJ']],
			"REC_ID='$KODE_CRUD'");
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Upd Tunj : '.$KODE_CRUD);

		// $aREC_CURREN=SYS_FETCH(SYS_QUERY("select PRSON_CODE from person2 where TNJ_EM_REC=$KODE_CRUD"));
		// $cNO_KRY=$aREC_CURREN['PRSON_CODE'];
		SYS_DB_CLOSE($DB2);	
		header('location:prs_tb_person.php?_a='.md5('UP_DATE_PEG').'&_c='.$cNO_KRY);
	break;

	case 'upd_geo':
		$can_DELETE = TRUST($cUSERCODE, 'PRS_PERSON_MST_3DEL');	
        $cPEOPLE_CODE = $_GET['_p'];
        $cGEO_CODE = $_GET['_g'];
        $qTB_SCOPE=OpenTable('PplGeoAdd', "A.PEOPLE_ID='$cPEOPLE_CODE' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
		$aPPL_GEO_ADD=SYS_FETCH($qTB_SCOPE);
		$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$cEDIT_REC	= S_MSG('PE64','Edit Lokasi');
		DEF_WINDOW($cEDIT_REC);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=del_GEOFN&_p='. $cPEOPLE_CODE. '&_g='.$cGEO_CODE.'" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_REC, '?_a=upd_GEO_LOC&_p='.$aPPL_GEO_ADD['PEOPLE_ID']. '&_g='.$cGEO_CODE. '&_o='.$aPPL_GEO_ADD['GEO_CODE'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', S_MSG('PG7A','Lokasi absen'));
					TDIV(9,9,9,12);
						SELECT([8,8,8,6], 'PILIH_LOC', '', '', 'select2');
							echo "<option value=''>All</option>";
							$qGEOFENCE=OpenTable('TbGeofence', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'GEO_NAME');
							while($aGEOFENCE=SYS_FETCH($qGEOFENCE)){
								if($aPPL_GEO_ADD['GEO_CODE']==$aGEOFENCE['GEO_CODE']){
									echo "<option value='$aGEOFENCE[GEO_CODE]' selected='$aPPL_GEO_ADD[GEO_CODE]' >$aGEOFENCE[GEO_NAME]</option>";
								} else {	echo "<option value='$aGEOFENCE[GEO_CODE]'  >$aGEOFENCE[GEO_NAME]</option>";
								}
							}
						echo '</select>';
					eTDIV();
				eTDIV();
				CLEAR_FIX();
				SAVE($cSAVE_DATA);
			eTFORM();
		END_WINDOW();
		break;
	case 'upd_GEO_LOC':
		$cPERSON=$_GET['_p'];
		$cGEOLOC=$_GET['_g'];
		$cOLD_GEOLOC=$_GET['_o'];
		$cGEOLOC=$_POST['PILIH_LOC'];
		RecUpdate('PplGeoAdd', ['GEO_CODE'], [$cGEOLOC], "PEOPLE_ID='$cPERSON' and GEO_CODE='$cOLD_GEOLOC'");
		SYS_DB_CLOSE($DB2);	
		echo "<script> window.history.go(-2);	</script>";
		break;
	case 'del_GEOFN':
		$cPERSON=$_GET['_p'];
		$cGEOLOC=$_GET['_g'];
		RecUpdate('PplGeoAdd', ['DELETOR'], [$cUSERCODE], "PEOPLE_ID='$cPERSON' and GEO_CODE='$cGEOLOC'");
		SYS_DB_CLOSE($DB2);	
		echo "<script> window.history.go(-2);	</script>";
		break;
	case 'pdd_rubah':
		$nREC_PRS5=$_GET['_id'];
		$nYIN=$_POST['UPD_YEAR_IN'];
		if(!$nYIN)	$nYIN=0;
		$nYOT=$_POST['UPD_YEAR_OUT'];
		if(!$nYOT)	$nYOT=0;
		RecUpdate('PrsEducation', ['EDU_CODE', 'EDU_DESC', 'EDU_JRSN', 'YEAR_IN', 'YEAR_OUT', 'EDU_NOTE'],
			[$_POST['UPD_DTL_PENDIDIKAN'], $_POST['UPD_EDU_DESC'], $_POST['UPD_DTL_JURUSAN'], $nYIN, $nYOT, $_POST['UPD_EDU_NOTE']],
			"REC_ID='$nREC_PRS5'");

		$aREC_CURREN=SYS_FETCH(OpenTable('PrsEducation', "REC_ID='$nREC_PRS5'"));
		$cNO_KRY=$aREC_CURREN['PRSON_CODE'];
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Upd Edu : '.$cNO_KRY);
		SYS_DB_CLOSE($DB2);	
		echo "<script> window.history.go(-2);	</script>";
	break;

}
?>
<script>
function imagePerson(event) {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview_IMG');
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>

