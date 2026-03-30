<?php
// prs_tb_person_upd.php
//	TODO : update add_loc_geo, Delta+tmk switch
	$can_UPDATE = TRUST($cUSERCODE, 'PRS_PERSON_MST_2UPD');
	$IS_DELTA = IS_DELTA($cAPP_CODE);
	$upd_PRS_MAIN=TRUST($cUSERCODE, 'PRS_PERSON_MST_2UPD');
    $view_TAB_UMUM=TRUST($cUSERCODE, 'PRS_PTAB_GNRL_VIEW');		$upd_TAB_UMUM=TRUST($cUSERCODE, 'PRS_PTAB_GNRL_UPD');
	$view_TAB_GAJI=TRUST($cUSERCODE, 'PRS_PTAB_SLRY_VIEW');		$upd_TAB_GAJI=TRUST($cUSERCODE, 'PRS_PTAB_SLRY_UPD');
	$view_TAB_FOTO=TRUST($cUSERCODE, 'PRS_PTAB_FOTO_VIEW');		$upd_TAB_FOTO=TRUST($cUSERCODE, 'PRS_PTAB_FOTO_UPDATE');
	$view_TAB_EDU=TRUST($cUSERCODE, 'PRS_PTAB_EDU_VIEW');		$upd_TAB_EDU=TRUST($cUSERCODE, 'PRS_PTAB_EDU_UPD');
	$view_TAB_LOCS=TRUST($cUSERCODE, 'PRS_PTAB_LOCS_VIEW'); 	$upd_TAB_LOCS=TRUST($cUSERCODE, 'PRS_PTAB_LOCS_UPD');
	$view_TAB_KK=TRUST($cUSERCODE, 'PRS_PTAB_KK_VIEW'); 		$upd_TAB_KK=TRUST($cUSERCODE, 'PRS_PTAB_KK_UPD');
	$view_TAB_SKILL=TRUST($cUSERCODE, 'PRS_PTAB_SKILL_VIEW'); 	$upd_TAB_SKILL=TRUST($cUSERCODE, 'PRS_PTAB_SKILL_UPD');
	$view_TAB_SIZE=TRUST($cUSERCODE, 'PRS_PTAB_SIZE_VIEW'); 	$upd_TAB_SIZE=TRUST($cUSERCODE, 'PRS_PTAB_SIZE_UPD');
	$view_TAB_NMR=TRUST($cUSERCODE, 'PRS_PTAB_NOMOR_VIEW'); 	$upd_TAB_NMR=TRUST($cUSERCODE, 'PRS_PTAB_NOMOR_UPD');
	$view_TAB_ABSN=TRUST($cUSERCODE, 'PRS_PTAB_ABSEN_VIEW'); 	$upd_TAB_ABSN=TRUST($cUSERCODE, 'PRS_PTAB_ABSEN_UPD');
	$view_TAB_INTV=TRUST($cUSERCODE, 'PRS_PTAB_INTVW_VIEW'); 	$upd_TAB_INTV=TRUST($cUSERCODE, 'PRS_PTAB_INTVW_UPD');

	$q_EDUCATE =OpenTable('PrsEducation');
	$n_EDUCATE =SYS_ROWS($q_EDUCATE);

	$cACT=(isset($_GET['_a']) ? $_GET['_a'] : '');
	$cCALLER=trim($_SESSION['CALLER']);
	$cKODE = $_GET['_c'];
	$qQUERY=OpenTable('PERSON1', "A.APP_CODE='$cAPP_CODE' and md5(A.PRSON_CODE)='$cKODE' and A.DELETOR=''");
	$REC_EDIT=SYS_FETCH($qQUERY);
	$cPERSON_CODE = $REC_EDIT['MASTER_CODE'];

/*	$q_PERSON6=OpenTable('PrsOccuption', "PRSON_CODE='$cPERSON_CODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	if(SYS_ROWS($q_PERSON6)==0 and $cPERSON_CODE){
		RecCreate('PrsOccuption', ['PRSON_CODE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
		[$cPERSON_CODE, $cAPP_CODE, $_SESSION['gUSERCODE'], date('Y-m-d H:i:s')]);
		$q_PERSON6=OpenTable('PrsOccuption', "PRSON_CODE='$cPERSON_CODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	}
*/
//	$aREC_PENEMPATAN=SYS_FETCH($q_PERSON6);

	$cNPWP=$cBPJS_TK=$cBPJS_KES=$cDPLK=$cSERT='';
	$qNOMOR=OpenTable('PrsNumber', "APP_CODE='$cAPP_CODE' and md5(PRSON_CODE)='$cKODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	if($aNOMOR=SYS_FETCH($qNOMOR)) {
		$cNPWP = $aNOMOR['NO_NPWP'];
		$cBPJS_TK = $aNOMOR['NO_BPJS_TK'];
		$cBPJS_KES = $aNOMOR['NO_BPJS_KES'];
		$cDPLK = $aNOMOR['NO_REK_DPLK'];
		$cSERT = $aNOMOR['NO_SERT_LAIN'];
	}
	$nADD_BPJS=0;
	$qADD_BPJS=OpenTable('PrsAddBpjs', "APP_CODE='$cAPP_CODE' and md5(PRSON_CODE)='$cKODE' and DELETOR=''");
	if($aADD_BPJS=SYS_FETCH($qADD_BPJS)) {
		$nADD_BPJS=$aADD_BPJS['ADDING'];
	}
	$REC_HP=OpenTable('PeopleHomePhone', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and md5(PEOPLE_CODE)='$_GET[_c]'");
	$aREC_HP=SYS_FETCH($REC_HP);
	$REC_EMAIL=OpenTable('PeopleEMail', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and md5(PEOPLE_CODE)='$_GET[_c]'");
	$aREC_EMAIL=SYS_FETCH($REC_EMAIL);
	$REC_BLOOD=OpenTable('PeopleBlood', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and md5(PEOPLE_CODE)='$_GET[_c]'");
	$aREC_BLOOD=SYS_FETCH($REC_BLOOD);
	$cBLOOD = ($aREC_BLOOD) ? $aREC_BLOOD['PEOPLE_BLOOD_GROUP'] : '';
	$REC_CARD=OpenTable('PrsMemberCard', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and md5(PERSON_CODE)='$_GET[_c]'");
	$aREC_CARD=SYS_FETCH($REC_CARD);
	$cCARD = ($aREC_CARD) ? $aREC_CARD['CARD_NUMBER'] : '';
	$cVALD = ($aREC_CARD) ? $aREC_CARD['VALID_UNTIL'] : '';
	$cFILE_FOTO = S_PARA('FTP_PERSON_IMG', '/home/riza/www/images/person/').$cAPP_CODE.'_PRS_FOTO_'.$cPERSON_CODE.'.jpg';	// tdk kebaca ??
	if($_SERVER['HTTP_HOST']=='localhost') {
		$cFILE_FOTO = 'data/images/person/'.$cAPP_CODE.'_PRS_FOTO_'.$cPERSON_CODE.'.jpg';
		if(!file_exists($cFILE_FOTO))		$cFILE_FOTO = "data/images/no.jpg";
	} else {
		$cFILE_FOTO = 'https://www.fahlevi.co/images/person/'.$cAPP_CODE.'_PRS_FOTO_'.$cPERSON_CODE.'.jpg';
		if(!REMOTE_FILE_EXISTS($cFILE_FOTO))	$cFILE_FOTO = "data/images/no.jpg";
	}
	// print_r2($cFILE_FOTO);

	$can_DELETE = TRUST($cUSERCODE, 'PRS_PERSON_MST_3DEL');	
	$cSCHED	= date('Y-m-d');
	$cEDIT_TBL=S_MSG('PA8A','Edit Data Pegawai');
	DEF_WINDOW($cEDIT_TBL);
		$aACT = ($can_DELETE ? ['<a href="?_a='.md5('delete_p3gawai').'&_id='. $cPERSON_CODE. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
		TFORM($cEDIT_TBL, '?_a=sa_ve&id='.$cPERSON_CODE.'&_b='.$cCALLER, $aACT, $cHELP_FILE, _ID:'form1');
			TDIV();
				LABEL([3,3,3,6], '700', S_MSG('PA02','Kode Peg'));
				INPUT('text', [2,2,3,6], '900', 'UPD_PRSON_CODE', $cPERSON_CODE, '', '', '', 14, 'Disable', 'fix');
				LABEL([3,3,3,6], '700', $cNAMA);
				INPUT('text', [7,7,7,6], '900', 'UPD_PRSON_NAME', DECODE($REC_EDIT['PRSON_NAME']), '', '', '', 50, '', 'Fix', $cTTIP_NAMA);
				LABEL([3,3,3,6], '700', $cGENDER);
				RADIO('UPD_PRSON_GEND', [1,2], [$REC_EDIT['PRSON_GEND']==1, $REC_EDIT['PRSON_GEND']==2], [$cLBL_PRIA, $cLBL_WANITA]);
				LABEL([3,3,3,6], '700', $cALAMAT);
				INPUT('text', [7,7,7,6], '900', 'UPD_ADDR1', DECODE($REC_EDIT['PEOPLE_ADDRESS']), '', '', '', 255, '', 'Fix');
				LABEL([3,3,3,3], '700', $cERTE);
				INPUT('text', [1,1,2,3], '900', 'UPD_PRSN_RT', DECODE($REC_EDIT['PRSN_RT']), '', '999', '', 3, '', '');
				LABEL([1,1,1,3], '700', $cERWE);
				INPUT('text', [1,1,2,3], '900', 'UPD_PRSN_RW', DECODE($REC_EDIT['PRSN_RW']), '', '999', '', 3, '', 'Fix');
				LABEL([3,3,3,3], '700', $cPROPINSI);
				TDIV(9,9,9,9);
                	SELECT([], 'PILIH_PROV', '', 'prov_s2');
						echo '<option></option>';
						$qPROV=OpenTable('TbProvince', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'nama');
						while($aPROV=SYS_FETCH($qPROV)){
							if(substr($REC_EDIT['AREA_CODE'],0,2) == $aPROV['id_prov'])
								echo "<option value='$aPROV[id_prov]' selected='substr($REC_EDIT[AREA_CODE],0,2)'>$aPROV[nama]</option>";
							else
								echo "<option value='$aPROV[id_prov]'  >$aPROV[nama]</option>";
						}
					echo '</select>';
				eTDIV();
				CLEAR_FIX();
				LABEL([3,3,3,3], '700', $cKOTA);
				TDIV(9,9,9,9);
					?>
						<select name="PILIH_KAB" id="s2example-2">
							<option></option>
								<?php
									$qKOTA=OpenTable('TbLocDistrict', "id_prov='".substr($REC_EDIT['AREA_CODE'],0,2)."'", '', 'kabupaten');
									while($aKOTA=SYS_FETCH($qKOTA)){
										if(substr($REC_EDIT['AREA_CODE'],0,4) == $aKOTA['id_kab'])
											echo "<option value='$aKOTA[id_kab]' selected='substr($REC_EDIT[AREA_CODE],0,4)'>$aKOTA[kabupaten]</option>";
										else
										echo "<option value='$aKOTA[id_kab]'  >$aKOTA[kabupaten]</option>";
									}
								?>
						</select>
					</div>
				<div class="clearfix"></div>

				<div class="form-group">
					<label class="col-sm-3 col-xs-3 form-label-700"><?php echo $cKECAMATAN?></label>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-9">
						<select name="PILIH_KEC" class="select2" id="s2example-4">
							<?php
								$qKEC=OpenTable('SubDistrict', "id_districts='".substr($REC_EDIT['AREA_CODE'],0,4)."'", '', 'sub_district');
								while($aKEC=SYS_FETCH($qKEC)){
									if(substr($REC_EDIT['AREA_CODE'],0,6) == $aKEC['id_sub_district'])
										echo "<option value='$aKEC[id_sub_district]' selected='substr($REC_EDIT[AREA_CODE],0,6)'>$aKEC[sub_district]</option>";
									else
									echo "<option value='$aKEC[id_sub_district]'  >$aKEC[sub_district]</option>";
								}
							?>
						</select>
					</div>
				</div>
				<div class="clearfix"></div>

				<div class="form-group">
					<label class="col-sm-3 col-xs-3 form-label-700" for="PILIH_KEL"><?php echo $cKELURAHAN?></label>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-9">
						<select name="PILIH_KEL" class="select2" id="PILIH_KEL">
							<option></option>
								<?php
									$qKEL=OpenTable('TbLocVillage', "id_sub_district='".substr($REC_EDIT['AREA_CODE'],0,6)."'", '', 'village');
									while($aKEL=SYS_FETCH($qKEL)){
										if($REC_EDIT['AREA_CODE'] == $aKEL['id_village'])
											echo "<option value='$aKEL[id_village]' selected='$REC_EDIT[AREA_CODE]'>$aKEL[village]</option>";
										else
										echo "<option value='$aKEL[id_village]'  >$aKEL[village]</option>";
									}
								?>
						</select>
						</div>
				</div>
<?php
				CLEAR_FIX();
				LABEL([3,3,3,6], '700', $cKODE_POS);
				INPUT('text', [1,1,1,6], '900', 'UPD_PRS_ZIP', $REC_EDIT['PEOPLE_ZIP'], '', '99999', '', 5, '', 'fix');
				LABEL([3,3,3,6], '700', $cNO_KTP);
				INPUT('text', [3,3,4,6], '900', 'UPD_PRS_KTP', $REC_EDIT['PRS_KTP'], '', '9999999999999999', '', 16, '', 'fix');
				echo '<h4> </br></h4>';
				echo '<ul class="nav nav-tabs">';
					if ($view_TAB_UMUM==1 || $upd_TAB_UMUM==1)
						echo '<li class="active"><a href="#TabUpdUmum" data-toggle="tab"><i class="fa fa-user"></i>'.$cTAB_UMUM.'</a></li>';
					if ($view_TAB_GAJI==1 || $upd_TAB_GAJI==1)
						echo '<li><a href="#TabUpdGaji" data-toggle="tab"><i class="fa fa-money"></i>'.$cTAB_GAJI.'</a></li>';
					if ($view_TAB_FOTO==1 || $upd_TAB_FOTO==1)
						echo '<li><a href="#TabUpdFoto" data-toggle="tab"><i class="fa fa-photo"></i>'.$cTAB_FOTO.'</a></li>'; 
					if ($view_TAB_EDU==1 || $upd_TAB_EDU==1)
						echo '<li><a href="#Pendidikan" data-toggle="tab"><i class="fa fa-mortar-board"></i>'. $cTAB_PENDIDIKAN.'</a></li>';
					if ($view_TAB_LOCS==1 || $upd_TAB_LOCS==1)
						echo '<li><a href="#Penempatan" data-toggle="tab"><i class="fa fa-home"></i>'. $cTAB_PENEMPATAN.'</a></li>';
					if ($view_TAB_KK==1 || $upd_TAB_KK==1)
						echo '<li><a href="#KartuKeluarga" data-toggle="tab"><i class="fa fa-home"></i>'. $cTAB_KKELUARGA. '</a></li>';
					if ($view_TAB_SKILL==1 || $upd_TAB_SKILL==1)
						echo '<li><a href="#Keahlian" data-toggle="tab"><i class="fa fa-wrench"></i>'. $cTAB_SKILL. '</a></li>';
					if ($view_TAB_SIZE==1 || $upd_TAB_SIZE==1)
						echo '<li><a href="#Size" data-toggle="tab"><i class="fa fa-arrows-h"></i>'. S_MSG('PG93','Ukuran'). '</a></li>';
					if ($view_TAB_NMR==1 || $upd_TAB_NMR==1)
						echo '<li><a href="#TAB_NOMOR" data-toggle="tab"><i class="fa fa-book"></i>'. S_MSG('PG81','Nomor'). '</a></li>';
					if($cCALLER=='APP') {
						if ($view_TAB_INTV==1 || $upd_TAB_INTV==1)
							echo '<li><a href="#Interview" data-toggle="tab"><i class="fa fa-pencil-square-o"></i>'. S_MSG('PG78','Interview'). '</a></li>';
					} else {
						if ($view_TAB_ABSN==1 || $upd_TAB_ABSN==1)
							echo '<li><a href="#Lainlain" data-toggle="tab"><i class="fa fa-location-arrow"></i>'. S_MSG('PG71','Lain-lain'). '</a></li>';
					}
				echo '</ul>';
				echo '<div class="tab-content primary">';
					echo '<div class="tab-pane fade in active" id="TabUpdUmum">';
						LABEL([3,3,3,6], '700', $cNO_TELP);
						INPUT('number', [3,3,3,6], '900', 'UPD_PRS_HP', $REC_EDIT['PRS_PHN'], '', '', '', 0, '', '');
						LABEL([3,3,3,6], '700', $cHOMEPHN, '', 'right');
						INPUT('number', [3,3,3,6], '900', 'UPD_HOME_PH', ($aREC_HP ? $aREC_HP['HOME_PHONE'] : ''), '', '', '', 0, '', 'fix');
						LABEL([3,3,3,6], '700', $cTMP_LAHIR);
						INPUT('text', [3,3,3,6], '900', 'UPD_BIRTH_PLC', $REC_EDIT['BIRTH_PLC'], '', '', '', 0, '', '');
						LABEL([3,3,3,6], '700', $cTGL_LAHIR, '', 'right');
						INP_DATE([3,3,3,6], '900', 'UPD_BIRTH_DATE', YMD_DMY($REC_EDIT['BIRTH_DATE']));
						LABEL([3,3,3,6], '700', $cEMAIL_ADR);
						INPUT('text', [3,3,3,6], '900', 'UPD_PRS_EMAIL', ($aREC_EMAIL ? $aREC_EMAIL['PPL_EMAIL'] : ''));
						LABEL([3,3,3,6], '700', $cWEB_SITE, '', 'right');
						INPUT('text', [3,3,3,6], '900', 'UPD_PRS_WEB', ($aREC_EMAIL ? $aREC_EMAIL['PPL_WEB'] : ''));
						LABEL([3,3,3,6], '700', $cNO_REK);
						INPUT('text', [3,3,3,6], '900', 'UPD_PRSON_ACCN', $REC_EDIT['PRSON_ACCN']);
						LABEL([3,3,3,6], '700', $cNAMA_BANK, '', 'right');
						INPUT('text', [3,3,3,6], '900', 'UPD_PRSON_BANK', $REC_EDIT['PRSON_BANK']);
						LABEL([3,3,3,6], '700', $cAGAMA);
						SELECT([3,3,3,3], 'UPD_PRSON_RELG');
							$REC_AGAMA=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_AG_DATA=SYS_FETCH($REC_AGAMA)){
								if($REC_EDIT['PRSON_RELG'] == $aREC_AG_DATA['KODE'])
									echo "<option class='col-sm-3 col-xs-6 form-label-900' value='$aREC_AG_DATA[KODE]' selected='$REC_EDIT[PRSON_RELG]'>$aREC_AG_DATA[NAMA]</option>";
								else
									echo "<option class='col-sm-3 col-xs-6 form-label-900' value='$aREC_AG_DATA[KODE]'  >$aREC_AG_DATA[NAMA]</option>";
							}
						echo '</select>';
						LABEL([3,3,3,6], '700', $cGOL_DAR, '', 'right');
						SELECT([3,3,3,3], 'UPD_BLOOD_GRUP');
							for ($I=1; $I<=4; $I++) {
								if ($aGOL_DAR[$I] == $cBLOOD)
									echo "<option value=$aGOL_DAR[$I] selected>".$cBLOOD."</option>";
								else
								echo "<option value=$aGOL_DAR[$I]>$aGOL_DAR[$I]</option>";
							}
						echo '</select>';
						CLEAR_FIX();
						LABEL([3,3,3,6], '700', $cSTATUS);
						SELECT([3,3,3,3], 'UPD_MARRIAGE');
							for ($S=1; $S<=2; $S++) {
								if ($S == $REC_EDIT['MARRIAGE'])
									echo "<option class='col-sm-3 col-xs-6 form-label-900' value=$S selected>".$aSTATUS[$S]."</option>";
								else
									echo "<option class='col-sm-3 col-xs-6 form-label-900' value=$S>$aSTATUS[$S]</option>";
							}
						echo '</select>';
						LABEL([3,3,3,6], '700', $cTGL_MASUK, '', 'right');
						INP_DATE([3,3,3,6], '900', 'UPD_TGL_MSK', YMD_DMY($REC_EDIT['JOIN_DATE']), '', '', '', 'fix');
						$REC_SPOUSE=OpenTable('PrsSpouse', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and md5(PRSON_CODE)='$_GET[_c]'");
						$aSPOUSE=SYS_FETCH($REC_SPOUSE);
						LABEL([3,3,3,6], '700', $cJML_ANAK);
						INPUT('text', [1,1,1,6], '900', 'UPD_CHILD_HAVE', ($aSPOUSE ? $aSPOUSE['CHILD_HAVE'] : ''), '', 'fdecimal', '', 1, '', '');
						LABEL([2,2,2,2], '700', '');
						LABEL([3,3,3,4], '700', $cTGL_TMK, '', 'right');
						INP_DATE([3,3,3,6], '900', 'UPD_TGL_TMK', YMD_DMY($REC_EDIT['JOB_DATE']), '', '', '', 0, '', 'fix');
						CLEAR_FIX();
						LABEL([3,3,3,6], '700', $cPASANGAN);
						INPUT('text', [3,3,3,6], '900', 'UPD_SUPPOSE', ($aSPOUSE ? $aSPOUSE['SPOUSE_NAME'] : ''), '', '', '', 50, '', 'fix');
					eTDIV();

					echo '<div class="tab-pane fade" id="TabUpdGaji">';
						$nSLRY=0;
						$dSLRY=date("Y-m-d");
						$qSLRY=OpenTable('PrsSalary', "APP_CODE='$cAPP_CODE' and md5(PRSON_CODE)='$_GET[_c]' and REC_ID not in ( select DEL_ID from logs_delete)");
						if($aSLRY=SYS_FETCH($qSLRY)) {
							$nSLRY = $aSLRY['BASIC_SLRY'];
							$dSLRY = $aSLRY['SLRY_DATE'];
						}
						LABEL([3,3,3,6], '700', $cGAJI_POKOK);
						INPUT('text', [2,2,2,6], '900', 'UPD_BASIC_SLRY', $nSLRY, '', 'fdecimal', 'right', 14, '', 'fix');
						LABEL([3,3,3,6], '700', $cGAJI_MULAI);
						INP_DATE([2,2,2,6], '900', 'UPD_SLRY_DATE', YMD_DMY($dSLRY));
						if($IS_DELTA)	{
							$nDELTA='';
							$qDELTA=OpenTable('PrsDelta', "APP_CODE='$cAPP_CODE' and md5(PRSON_CODE)='$_GET[_c]' and DELETOR=''");
							if ($aDELTA = SYS_FETCH($qDELTA)) 	$nDELTA = $aDELTA['DELTA'];
							LABEL([3,3,3,6], '700', S_MSG('PA25','Delta'), '', 'right');
							INPUT('number', [2,2,2,6], '900', 'UPD_DELTA', $nDELTA, '', '', '', 0, '', 'fix');
						}

						TABLE('myTable');
							THEAD([$cNAMA_TUNJANGAN, $cNILAI_TUNJANGAN, $cSAT_TUNJANGAN], '*', [0,1,0], '*', [8,2,2]); 
							CLEAR_FIX();
							echo '<tbody>';
								$qALLOW=OpenTable('RelAllowance', "A.APP_CODE='$cAPP_CODE' and md5(A.PRSON_CODE)='$_GET[_c]' and A.REC_ID not in ( select DEL_ID from logs_delete)");
								$nTOTAL = 0;
								while($aALLWN=SYS_FETCH($qALLOW)) {
									echo '<tr>';
										echo "<td><span><a href='?_a=".md5('edit_dtl_tunjangan')."&_r=$aALLWN[REC_ID]'>". $aALLWN['TNJ_NAME'].'</a></span></td>';
										echo '<td align="right">'.CVR($aALLWN['TNJ_AMNT']).'</td>';
										echo "<td>". $aALLWN['UNIT_NAME'].'</a></span></td>';
									echo '</tr>';
									$nTOTAL += $aALLWN['TNJ_AMNT'];
								}
								echo '<tr></tr>';
								echo '<td>';
									SELECT([12,12,12,12], 'ADD_TUNJANGAN', '', '', 'select2');
										echo '<option value="" selected></<option>';
										$qTUNJ=OpenTable('TbAllowance', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
										while($aTUNJ=SYS_FETCH($qTUNJ)){
											echo "<option value='$aTUNJ[TNJ_CODE]'  >$aTUNJ[TNJ_NAME]</option>";
										}
									echo '</select>';
								echo '</td>';
								INPUT('text', [12,12,12,12], '900', 'ADD_VALUE', '', '', 'fdecimal', 'right', 14, '', 'fix', '', '', '', '', '', 'td');
								SELECT([12,12,12,12], 'ADD_SATUAN_TUNJ', '', '', 'select2', '', 'td');
									$REC_UNIT=OpenTable('PrsTbUnit');
									while($aREC_UNIT=SYS_FETCH($REC_UNIT)){
										echo "<option value='$aREC_UNIT[UNIT_CODE]'  >$aREC_UNIT[UNIT_NAME]</option>";
									}
								echo '</select></td>';
								TTOTAL([$cJML_TUNJANGAN, CVR($nTOTAL)], [0,1]);
							echo '</tbody>';
						eTABLE();
						CLEAR_FIX();
					eTDIV();
					echo '<div class="tab-pane fade" id="TabUpdFoto">';
						LABEL([12,12,12,12], '700', $cTAB_FOTO);
?>
						<span class="desc"></span>																	
						<img class="img-responsive" id="preview_IMG" src="<?php echo $cFILE_FOTO?>" style="max-width:220px;">
<?php
						if($upd_TAB_FOTO) {
							echo '<input type="file" name="UPLOAD_IMAGE" accept="image/*" class="form-control" onchange="imagePerson(event)" form="form1">';
						}
					eTDIV();

					echo '<div class="tab-pane fade" id="Pendidikan">';
						TABLE('example');
							THEAD([$cPENDDK, $cNM_PENDIDIKAN, $cJRSN_PNDDKKAN, $cT1_PENDIDIKAN, $cT2_PENDIDIKAN, $cKT_PENDIDIKAN], 'wrap', [], '*', [4,2,2,1,1,2]);
								echo '<tbody>';
									$qEDU=OpenTable('PrsEdu_TbEdu', "md5(A.PRSON_CODE)='$_GET[_c]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", 'A.REC_ID', "A.EDU_CODE");
									while($rEDU=SYS_FETCH($qEDU)) {
										echo '<tr>';
											echo "<td><span><a href='?_a=EDIT_EDU&_r=$rEDU[REC_ID]'>". $rEDU['EDU_NAME'].'</a></span></td>';
											echo "<td><span><a href='?_a=EDIT_EDU&_r=$rEDU[REC_ID]'>". $rEDU['EDU_DESC'].'</a></span></td>';
											echo '<td>'. $rEDU['EDU_JRSN'].'</a></span></td>';
											echo '<td>'. $rEDU['YEAR_IN'].'</a></span></td>';
											echo '<td>'. $rEDU['YEAR_OUT'].'</a></span></td>';
											echo '<td>'. $rEDU['EDU_NOTE'].'</a></span></td>';
										echo '</tr>';
									}
									echo '<td>';
?>
										<select name="ADD_EDU_CODE" class="col-lg-12 col-sm-12 form-label-900 select2">
											<option value="" selected></<option>
											<?php 
												$qEDU=OpenTable('TbEducation', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
												while($aEDU=SYS_FETCH($qEDU)){
													echo "<option value='$aEDU[EDU_CODE]'  >$aEDU[EDU_NAME]</option>";
												}
										echo '</select>';
									echo '</td>';
									INPUT('text', [12,12,12,12], '900', 'ADD_EDU_DESC', '', '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
									INPUT('text', [12,12,12,12], '900', 'ADD_JURUSAN', '', '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
									INPUT('text', [12,12,12,12], '900', 'ADD_YEAR_IN', '', '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
									INPUT('text', [12,12,12,12], '900', 'ADD_YEAR_OUT', '', '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
									INPUT('text', [12,12,12,12], '900', 'ADD_EDU_NOTE', '', '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
								echo '</tbody>';
						echo '</table><br>';
					eTDIV();
					CLEAR_FIX();

					echo '<div class="tab-pane fade" id="Penempatan">';
						LABEL([3,3,3,6], '500', $cJABATAN);
						TDIV(6,6,6,12);
							SELECT([10,10,10,10], 'UPD_PRS_JOB', '', '', 'select2');
								$qOCCUP=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
								while($aUPD_JOB=SYS_FETCH($qOCCUP)){
									if($REC_EDIT['JOB_CODE'] == $aUPD_JOB['JOB_CODE'])
										echo "<option value='$aUPD_JOB[JOB_CODE]' selected='$REC_EDIT[JOB_CODE]'".">".$aUPD_JOB['JOB_NAME']."</option>";
									else
										echo "<option value='$aUPD_JOB[JOB_CODE]'  >$aUPD_JOB[JOB_NAME]</option>";
								}
							echo '</select>';
						eTDIV();
						CLEAR_FIX();
						if($cCALLER!='APP' && $ada_OUTSOURCING) {
							LABEL([3,3,3,6], '500', $cCUSTOMER);
							TDIV(6,6,6,12);
								SELECT([10,10,10,10], 'UPD_CUSTOMER', '', '', 'select2');
									echo "<option value=' '  > </option>";
									$qCUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
									while($aUPD_CUSTOMER=SYS_FETCH($qCUSTOMER)){
										if($REC_EDIT['CUST_CODE']==$aUPD_CUSTOMER['CUST_CODE'])
											echo "<option value='$aUPD_CUSTOMER[CUST_CODE]' selected='$REC_EDIT[CUST_CODE]' >$aUPD_CUSTOMER[CUST_NAME]</option>";
										else
											echo "<option value='$aUPD_CUSTOMER[CUST_CODE]'  >$aUPD_CUSTOMER[CUST_NAME]</option>";
									}
								echo '</select>';
							eTDIV();
							CLEAR_FIX();
						}	

						LABEL([3,3,3,6], '500', $cLOKASI);
							TDIV(6,6,6,12);
								SELECT([10,10,10,10], 'UPD_LOKASI', '', '', 'select2');
								echo "<option value=' '  > </option>";
								$qLOKASI=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
									while($aLOKASI=SYS_FETCH($qLOKASI)){
										if($REC_EDIT['KODE_LOKS']==$aLOKASI['LOKS_CODE'])
											echo "<option value='$aLOKASI[LOKS_CODE]' selected='$REC_EDIT[KODE_LOKS]' >$aLOKASI[LOKS_NAME]</option>";
										else
											echo "<option value='$aLOKASI[LOKS_CODE]'  >$aLOKASI[LOKS_NAME]</option>";
									}
								echo '</select>';
							eTDIV();
						CLEAR_FIX();
						LABEL([3,3,3,6], '700', $cCATATAN);
						INPUT('text', [8,8,8,6], '900', 'UPD_OUT_NOTE', $REC_EDIT['OUT_NOTE'], '', '', '', 0, '', 'fix');
					eTDIV();

					echo '<div class="tab-pane fade" id="Size">';
						$qSIZE=OpenTable('PrsSize', "APP_CODE='$cAPP_CODE' and md5(PRSON_CODE)='$_GET[_c]' and REC_ID not in ( select DEL_ID from logs_delete)");
						$nHEIGHT=0;	$nWEIGHT=0;	$cSHIRT='';	$nSHOES=0;	$nPANTS=0;	$nHEAD=0;
						if (SYS_ROWS($qSIZE)>0) {
							$aSIZE = SYS_FETCH($qSIZE);
							$nHEIGHT = $aSIZE['PRS_HEIGHT'];
							$nWEIGHT = $aSIZE['PRS_WEIGHT'];
							$cSHIRT = $aSIZE['PRS_SHIRT'];
							$nSHOES = $aSIZE['PRS_SHOE'];
							$nPANTS = $aSIZE['PRS_PDL'];
							$nHEAD = $aSIZE['PRS_HEAD'];
						} 
						LABEL([3,3,3,6], '700', $cHEIGHT);
						INPUT('number', [1,1,1,6], '900', 'UPD_HEIGHT', $nHEIGHT, '', '', '', 0, '', 'fix');
						LABEL([3,3,3,6], '700', $cWEIGHT);
						INPUT('number', [1,1,1,6], '900', 'UPD_WEIGHT', $nWEIGHT, '', '', '', 0, '', 'fix');
						LABEL([3,3,3,6], '700', S_MSG('PA0C','Ukuran Baju'));
						INPUT('number', [1,1,1,6], '900', 'UPD_SHIRT', $cSHIRT, '', '', '', 0, '', 'fix');
						LABEL([3,3,3,6], '700', S_MSG('PA0D','Ukuran Sepatu'));
						INPUT('number', [1,1,1,6], '900', 'UPD_SHOES', $nSHOES, '', '', '', 0, '', 'fix');
						LABEL([3,3,3,6], '700', S_MSG('PA0F','Ukuran Celana'));
						INPUT('number', [1,1,1,6], '900', 'UPD_PANTS', $nPANTS, '', '', '', 0, '', 'fix');
						LABEL([3,3,3,6], '700', S_MSG('PA0X','Lingkar Kepala'));
						INPUT('number', [1,1,1,6], '900', 'UPD_HEAD', $nHEAD, '', '', '', 0, '', 'fix');
					eTDIV();

					$qFAMCARD=OpenTable('PrsFamCardHdr', "md5(PRSON_CODE)='$_GET[_c]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					$aREC_KK_HDR=SYS_FETCH($qFAMCARD);
					echo '<div class="tab-pane fade" id="KartuKeluarga">';
						LABEL([4,4,4,6], '700', $cNO_KKELUARGA);
						INPUT('text', [4,4,4,12], '900', 'UPD_NO_KKLRG', ($aREC_KK_HDR ? $aREC_KK_HDR['NO_KKLRG'] : ''), '', '', '', 0, '', 'fix');
						LABEL([4,4,4,6], '700', $cNM_KKELUARGA);
						INPUT('text', [6,6,6,12], '900', 'UPD_NM_KKLRG', ($aREC_KK_HDR ? $aREC_KK_HDR['NM_KKLRG'] : ''), '', '', '', 0, '', 'fix');
						TABLE('example');	
							THEAD([$cFULL_NAME, $cNIK_NIKS, $cGENDER, $cTMP_LAHIR, $cTGL_LAHIR, $cAGAMA, $cPENDIDIKAN, $cPEKERJAAN, $cSTATUS, $cSTA_TUS, $cKWARGA, $cAYAH, $cIBU], 
								'wrap', [], '*', [1,1,1,1,2,1,1,1,1,1,1,1,1]);
							echo '<tbody>';
									$qFCDTL=OpenTable('Fam_Card', "md5(PRSON_CODE)='$_GET[_c]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
									while($rPERSON7=SYS_FETCH($qFCDTL)) {
										echo '<tr>';
											echo "<td><span><a href='?_a=".md5('edit_dtl_kkeluarga')."&_r=$rPERSON7[REC_ID]'>". $rPERSON7['FULL_NAME'].'</a></span></td>';
											echo '<td>'.$rPERSON7['N_I_K'].'</td>';
											$cGENDER=$cLBL_WANITA;
											if ($rPERSON7['GENDER']==1) $cGENDER=$cLBL_PRIA;
											echo '<td>'. $cGENDER .'</td>';
											echo '<td>'. $rPERSON7['BIRTH_PLCE'].'</td>';
											echo '<td>'. date("d-M-Y", strtotime($rPERSON7['BIRTH_DATE'])).'</td>';
											echo '<td>'. $rPERSON7['AGAMA'].'</td>';
											echo '<td>'. $rPERSON7['EDU_NAME'].'</td>';
											echo '<td>'. $rPERSON7['J_O_B'].'</td>';
											echo '<td>'. $rPERSON7['MAR_STAT'].'</td>';
											echo '<td>'. $rPERSON7['STA_TUS'].'</td>';
											echo '<td>'. $rPERSON7['CITI_ZEN'].'</td>';
											echo '<td>'. $rPERSON7['FATH_NAME'].'</td>';
											echo '<td>'. $rPERSON7['MOTH_NAME'].'</td>';
										echo '</tr>';
									}
									INPUT('text', [12,12,12,12], '900', 'ADD_FULL_NAME', '', '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
									INPUT('text', [12,12,12,12], '900', 'ADD_N_I_K', '', '', '', '', 14, '', 'fix', '', '', '', '', '', 'td');
									RADIO('ADD_GENDER', [1,2], [true, false], [$cLBL_PRIA, $cLBL_WANITA], '', 'td');
									INPUT('text', [12,12,12,12], '900', 'ADD_BIRTH_PLCE', '', '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
									INPUT('text', [12,12,12,12], '900', 'ADD_BIRTH_DATE', '', 'date', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
									SELECT([12,12,12,612], 'ADD_RELI_GION', '', '', '', '', 'td');
										$qPRS_RLGN=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
										// echo "<option value=' '  > </option>";
										while($aREC_PRS_RLGN=SYS_FETCH($qPRS_RLGN)){
											echo "<option class='form-label-900' value='$aREC_PRS_RLGN[KODE]'  >$aREC_PRS_RLGN[NAMA]</option>";
										}
									echo '</select></td>';
							?>
						
						<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_EDUCATE' value=""></td>
						<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_J_O_B' value=""></td>
						<td><div class="form-group">
							<select name='ADD_STATUS' class="form-label-900 m-bot15">
							<?php
								$qSTATUS=OpenTable('TbStatus', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
								echo "<option value=' '  > </option>";
								while($aREC_STATUS=SYS_FETCH($qSTATUS)){
									echo "<option value='$aREC_STATUS[KODE]'  >$aREC_STATUS[NAMA]</option>";
								}
							?>
							</select>
						</div></td>
						<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_STA_TUS' value=""></td>
						<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_CITI_ZEN' value=""></td>
						<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_FATH_NAME' value=""></td>
						<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_MOTH_NAME' value=""></td>
					</tbody>
				</table>
				<div class="clearfix"></div>
			</div>

			<div class="tab-pane fade" id="Keahlian">
<?php	
				TABLE('myTable');
					THEAD([$cTAB_SKILL, $cKET_SKILL, $cTHN_SKILL, $cSERT_SKILL, $cREG_SKILL, $cNOTE_SKILL], '', [3,2,1,2,2,2], '*');
					echo '<tbody>';
						$qSKILL=OpenTable('PrsSkill', "md5(A.PRSON_CODE)='$_GET[_c]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
						while($rSKILL=SYS_FETCH($qSKILL)) {
							TDETAIL([$rSKILL['SKILL_NAME'], $rSKILL['SKILL_DESC'], $rSKILL['YEAR_SKILL'], $rSKILL['SKILL_SERT'], $rSKILL['SKILL_REG'], $rSKILL['SKILL_NOTE']], [], '*', 
							["<a href='?_a=".md5('upd_dtl_skill')."&_r=$rSKILL[REC_ID]'>", '', '', '', '', '']);
						}
						echo '<td>';
							SELECT([12,12,12,12], 'ADD_SKILL_CODE', '', '', 'select2');
								echo '<option value="" selected></<option>';
									$REC_SKIL=OpenTable('TbSkill', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SKILL_NAME');
									while($aREC_SKILL=SYS_FETCH($REC_SKIL)){
										echo "<option value='$aREC_SKILL[SKILL_CODE]'  >$aREC_SKILL[SKILL_NAME]</option>";
									}
							echo '</select><br>';
						echo '</td>';
						INPUT('text', [12,12,12,12], '900', 'ADD_SKILL_DESC', '', '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
						INPUT('text', [12,12,12,12], '900', 'ADD_SKILL_YEAR', '', '', '9999', '', 4, '', 'fix', '', '', '', '', '', 'td');
						INPUT('text', [12,12,12,12], '900', 'ADD_SKILL_SERT', '', '', '', '', 40, '', 'fix', '', '', '', '', '', 'td');
						INPUT('text', [12,12,12,12], '900', 'ADD_SKILL_REG', '', '', '', '', 40, '', 'fix', '', '', '', '', '', 'td');
						INPUT('text', [12,12,12,12], '900', 'ADD_SKILL_NOTE', '', '', '', '', 40, '', 'fix', '', '', '', '', '', 'td');
					echo '</tbody>';
				eTABLE();
			eTDIV();	
			echo '<div class="tab-pane fade" id="TAB_NOMOR">';
				LABEL([3,3,3,5], '700', S_MSG('PG83','No. NPWP'));
				INPUT('text', [3,3,3,7], '900', 'UPD_NPWP', $cNPWP);
				LABEL([3,3,3,5], '700', S_MSG('PG8F','No. KTA'), '', 'right');
				INPUT('text', [3,3,3,7], '900', 'UPD_NMR_KTA', $cCARD, '', '', '', 0, '', 'fix');
				LABEL([3,3,3,5], '700', S_MSG('PG84','No. BPJS TK'));
				INPUT('text', [3,3,3,7], '900', 'UPD_BPJS_TK', $cBPJS_TK);
				LABEL([3,3,3,5], '700', S_MSG('PG89','Berlaku s/d'), '', 'right');
				INPUT_DATE([2,3,3,7], '900', 'UPD_TGL_KTA', $cVALD, '', '', '', 0, '', 'fix');
				LABEL([3,3,3,5], '700', S_MSG('PG85','No. BPJS KES'));
				INPUT('text', [3,3,3,7], '900', 'UPD_BPJS_KES', $cBPJS_KES, '', '', '', 0, '', 'fix');
				LABEL([3,3,3,5], '700', 'Tambahan');
				INPUT('number', [1,2,2,7], '900', 'UPD_BPJS_ADD', $nADD_BPJS, '', '', '', 0, '', 'fix');
				LABEL([3,3,3,5], '700', S_MSG('PG86','No. DPLK'));
				INPUT('text', [3,3,3,7], '900', 'UPD_DPLK', $cDPLK, '', '', '', 0, '', 'fix');
				LABEL([3,3,3,5], '700', S_MSG('PG87','No. Sert lain'));
				INPUT('text', [3,3,3,7], '900', 'UPD_SERT', $cSERT, '', '', '', 0, '', 'fix');
			eTDIV();

			echo '<div class="tab-pane fade" id="Lainlain">';
				LABEL([3,3,3,6], '700', S_MSG('F019','Catatan'));
				$cNOTES='';
				$qPNOTE=OpenTable('PeopleNotes', "APP_CODE='$cAPP_CODE' and md5(PEOPLE_CODE)='$_GET[_c]' and REC_ID not in ( select DEL_ID from logs_delete)");
				if ($aNOTES = SYS_FETCH($qPNOTE)) 	$cNOTES = $aNOTES['PEOPLE_NOTES'];
				INPUT('text', [9,9,9,9], '900', 'UPD_CATATAN', DECODE($cNOTES), '', '', '', 0, '', 'fix');
				$nANYWH=0;	$cGEO_N = '';	$dANYW1 = '';	$dANYW2 = '';
				$qPANYW=OpenTable('PplAnywhere', "APP_CODE='$cAPP_CODE' and md5(PEOPLE_CODE)='$_GET[_c]' and DELETOR=''");
				if ($aANYWH = SYS_FETCH($qPANYW)) {
					$nANYWH = 1;
					$cGEO_N = $aANYWH['GEO_NAME'];
					$dANYW1 = $aANYWH['AW_BEGIN'];
					$dANYW2 = $aANYWH['AW_END'];
				}
				echo '<br>';
				LABEL([3,3,3,6], '700', S_MSG('PG7A','Lokasi absen').' '.S_MSG('PG7B','Dimana saja'));
				INPUT('checkbox', [1,1,1,1], '900', 'UPD_ANYWHERE', $nANYWH, '', '', '', 0, '', 'fix', '', $nANYWH);
				LABEL([3,3,3,6], '700', 'Keterangan Lokasi');
				INPUT('text', [4,4,4,6], '900', 'UPD_KET_ANYW', DECODE($cGEO_N), '', '', '', 0, '', 'fix');
				LABEL([3,3,3,6], '700', S_MSG('RH11','Tanggal'));
				INPUT_DATE([2,2,2,6], '900', 'UPD_AWL_DATE', $dANYW1);
				LABEL([1,1,3,6], '700', S_MSG('RS14','s/d'));
				INPUT_DATE([2,2,2,6], '900', 'UPD_AKH_DATE', $dANYW2);
				$cDEVICE='';
				$qDEVICE=OpenTable('Aktivasi', "APP_CODE='$cAPP_CODE' and md5(PEOPLE_CODE)='$_GET[_c]' and DELETOR=''");
				if ($aDEV = SYS_FETCH($qDEVICE))	$cDEVICE = $aDEV['ENTRY'];
				LABEL([1,1,1,6], '700', S_MSG('PF72','Device'));
				INPUT('text', [3,4,4,6], '900', 'UPD_DEVICE', $cDEVICE, '', '', '', 0, '*');
				echo '<br><br><br>';
				TDIV();
					TABLE('example');
						THEAD(['Lokasi'], '', [], '*');
						echo '<tbody>';
							$qGEO_ADD=OpenTable('PplGeoAdd', "A.PEOPLE_ID='$cPERSON_CODE' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
							while($rGEO_ADD=SYS_FETCH($qGEO_ADD)) {
									echo "<tr><td><span><a href='?_a=upd_geo&_p=$rGEO_ADD[PEOPLE_ID]&_g=$rGEO_ADD[GEO_CODE]'>". $rGEO_ADD['GEO_NAME'].'</a></span></td></tr>';
							}
							echo '<tr><td><select name="ADD_GEO_CODE" class="col-lg-12 col-sm-12 col-xs-12 form-label-900 select2">';
								echo '<option> </option>';
								$qREC_GEO=OpenTable('TbGeofence', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'GEO_NAME');
								while($aREC_LOKS=SYS_FETCH($qREC_GEO)){
									echo "<option value='$aREC_LOKS[GEO_CODE]'  >$aREC_LOKS[GEO_NAME]</option>";
								}
							echo '</td></tr>';
						echo '</tbody>';
					eTABLE();
				eTDIV();
				echo '<br><br><br><br><br>';
			eTDIV();
			echo '<div class="tab-pane fade" id="Interview">';
					$cHOBBY=$cSELF=$cKRONIS=$cADVTG=$cDISAV=$cPRESTASI=$cEXPR=$cWHY=$cJOB=$cPICT=$cINFO=$cREFF=$cOUTS=$cRESULT=$cREASON=$cINTERVIEWER='';	
					$nSMOKE=$nDRUGS=$nDRUNK=$nTATTO=$nANYW=$nTRAIN=$nOK=$nNOT_OK=0;
					$qINTVW=OpenTable('PrsInterview', "APP_CODE='$cAPP_CODE' and md5(PRSON_CODE)='$_GET[_c]' and REC_ID not in ( select DEL_ID from logs_delete)");
					if ($aINTVW = SYS_FETCH($qINTVW)) 	{
						$cHOBBY = $aINTVW['HOBBY'];
						$cKRONIS= $aINTVW['KRONIS'];
						$cSELF	= $aINTVW['SELF_DESC'];
						$nSMOKE = $aINTVW['SMOKING'];
						$nDRUGS = $aINTVW['DRUGS'];
						$nDRUNK = $aINTVW['DRUNK'];
						$nTATTO = $aINTVW['TATTO'];
						$cADVTG = $aINTVW['ADVANTAGE'];
						$cDISAV = $aINTVW['DIS_ADVANTAGE'];
						$cEXPR 	= $aINTVW['EXPERIENCE'];
						$cJOB 	= $aINTVW['JOB'];
						$cWHY 	= $aINTVW['WHY'];
						$cPICT 	= $aINTVW['PICT'];
						$cINFO 	= $aINTVW['INFO'];
						$cREFF 	= $aINTVW['REFF'];
						$cOUTS 	= $aINTVW['OUTS'];
						$nANYWH = $aINTVW['ANY_WHERE'];
						$nTRAIN = $aINTVW['TRAINING'];
						$cINTERVIEWER = $aINTVW['INTERVIEWER'];
						$cRESULT = $aINTVW['CONCLUSION'];
						$nOK = $aINTVW['OK'];
						$cREASON = $aINTVW['REASON'];
					}
					LABEL([2,2,2,4], '700', 'Hobi');
					INPUT('text', [10,10,10,8], '900', 'UPD_HOBBY', DECODE($cHOBBY), '', '', '', 0, '', '', 'Hobi dan alasan mengapa tertarik dengan hobi ini');
					LABEL([2,2,2,4], '700', 'Penyakit Kronis');
					INPUT('text', [10,10,10,8], '900', 'UPD_KRONIS', DECODE($cKRONIS), '', '', '', 0, '', 'fix', 'Apakah pernah mengidap penyakit kronis');
					LABEL([2,2,2,4], '700', 'Gambaran Diri');
					INPUT('text', [10,10,10,8], '900', 'UPD_SELF', DECODE($cSELF), '', '', '', 0, '', 'fix', 'Ceritakan / Gambarkan dengan bahasa dn cara Anda sendiri, siapa Anda ini ???');
					LABEL([2,2,2,4], '700', 'Kelebihan');
					INPUT('text', [10,10,10,8], '900', 'UPD_ADVT', DECODE($cADVTG), '', '', '', 0, '', 'fix');
					LABEL([2,2,2,4], '700', 'Kekurangan');
					INPUT('text', [10,10,10,8], '900', 'UPD_DIS_ADVT', DECODE($cDISAV), '', '', '', 0, '', 'fix');
					echo '<br>';
					LABEL([2,2,2,4], '700', 'Merokok');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_SMOKING', $nSMOKE, '', '', '', 0, '', 'fix', '', $nSMOKE);
					LABEL([2,2,2,4], '700', 'Narkoba');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_DRUGS', $nDRUGS, '', '', '', 0, '', 'fix', '', $nDRUGS);
					LABEL([2,2,2,4], '700', 'Mabuk');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_DRUNK', $nDRUNK, '', '', '', 0, '', 'fix', '', $nDRUNK);
					LABEL([2,2,2,4], '700', 'Bertato');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_TATTO', $nTATTO, '', '', '', 0, '', 'fix', '', $nTATTO);
					LABEL([2,2,2,4], '700', 'Prestasi');
					INPUT('text', [10,10,10,8], '900', 'UPD_PRESTASI', DECODE($cPRESTASI), '', '', '', 0, '', 'fix');
					LABEL([2,2,2,4], '700', 'Pengalaman Kerja');
					INPUT('text', [10,10,10,8], '900', 'UPD_EXPR', DECODE($cEXPR), '', '', '', 0, '', 'fix');
					LABEL([2,2,2,4], '700', 'Posisi Jabatan');
					INPUT('text', [10,10,10,8], '900', 'UPD_JOB', DECODE($cJOB), '', '', '', 0, '', 'fix', 'Jabatan yang dilamar');
					LABEL([2,2,2,4], '700', 'Kenapa memilih Jabatan ini ?');
					INPUT('text', [10,10,10,8], '900', 'UPD_WHY', DECODE($cWHY), '', '', '', 0, '', 'fix');
					LABEL([2,2,2,4], '700', 'Gambaran Pekerjaan');
					INPUT('text', [10,10,10,8], '900', 'UPD_PICT', DECODE($cPICT), '', '', '', 0, '', 'fix', 'Berikan gambaran tentang pekerjaan yang Anda lamar ini.');
					LABEL([2,2,2,4], '700', 'Info Melamar');
					INPUT('text', [10,10,10,8], '900', 'UPD_NFO', DECODE($cINFO), '', '', '', 0, '', 'fix', 'Dari mana Anda mendapatkan informasi untuk melamar pekerjaan disini.');
					LABEL([2,2,2,4], '700', 'Referensi');
					INPUT('text', [10,10,10,8], '900', 'UPD_REFF', DECODE($cREFF), '', '', '', 0, '', 'fix', 'Apakah Anda melamar disini ada referensi ? Siapa namanya, kerja dimana dan apa hubungan nya.');
					LABEL([2,2,2,4], '700', 'Outsourcing');
					INPUT('text', [10,10,10,8], '900', 'UPD_OUTS', DECODE($cOUTS), '', '', '', 0, '', 'fix', 'Apa yang Anda ketahui tentang perusahaan Outsourcing / Manpower Agency ?');
					LABEL([2,2,2,4], '700', 'Dimana Saja');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_ANYWH', $nANYWH, '', '', '', 0, '', 'fix', 'Apakah bersedia ditempatkan dimana saja ?', $nANYWH);
					LABEL([2,2,2,4], '700', 'Training');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_TRAIN', $nTRAIN, '', '', '', 0, '', 'fix', 'Apakah bersedia untuk melakukan training di perusahaan ini ?', $nTRAIN);
					echo '<br>';
					LABEL([2,3,3,6], '700', 'Tangal Wawancara');
					INPUT_DATE([2,2,2,6], '900', 'UPD_INT_DATE', date('Y-m-d'));
					LABEL([3,3,3,6], '700', 'Nama Wawancara', '', 'right');
					INPUT('text', [3,3,3,6], '900', 'UPD_INTERVIEWER', DECODE($cINTERVIEWER), '', '', '', 0, '', 'fix', 'Nama petugas pewawancara');
					echo '<br><br>';
					LABEL([3,3,3,6], '700', 'Kesimpulan Hasil Wawancara');
					INPUT('textarea', [12,12,12,12], '900', 'UPD_RESULT', DECODE($cRESULT), '', '', '', 0, '', 'fix');	//						<!-- <textarea class="form-control col-sm-12 form-label-900" style="font-size:150%;" name="UPD_RESULT"></textarea>  -->
					CLEAR_FIX();
					LABEL([2,2,2,4], '700', 'Dapat Disarankan');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_OK', $nOK, '', '', '', 0, '', 'fix', '', $nOK);
					LABEL([2,3,3,6], '700', 'Alasan');
					INPUT('text', [10,9,9,6], '900', 'UPD_REASON', DECODE($cREASON), '', '', '', 0, '', 'fix', 'Alasan - alasan bisa di rekomendasi / tidak');
					echo '<br><br><br>';
					eTDIV();
				eTDIV();
				CLEAR_FIX();
				SAVE(($can_UPDATE ? $cSAVE_DATA : ''));
			eTFORM('*');
		eTDIV();
	END_WINDOW();
?>
