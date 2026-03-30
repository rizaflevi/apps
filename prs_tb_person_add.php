<?php
//	TODO : update with province

	$cADD_PEG	= S_MSG('PA85','Tambah Pegawai');
	$cCODE_MASK		= S_PARA('PICT_PERSON', 'xxxxxx');
	if($cCODE_MASK=='')	$cCODE_MASK='';
	$nMAX_CODE=(strlen($cCODE_MASK)>14 ? 14 : strlen($cCODE_MASK));

	$cFILE_FOTO_PERSON = "data/images/no.jpg";
	$q_EDUCATE =OpenTable('PrsEducation');
	$n_EDUCATE =SYS_ROWS($q_EDUCATE);
	DEF_WINDOW($cADD_PEG);
		TFORM($cADD_PEG, 'prs_tb_person.php?_a=addNew', [], $cHELP_FILE);
			LABEL([3,3,3,3], '700', $cKODE);
			INPUT('text', [2,2,2,2], '900', 'ADD_PRSON_CODE', '', 'focus', $cCODE_MASK, '', $nMAX_CODE, '', 'fix');
			LABEL([3,3,3,3], '700', $cNAMA);
			INPUT('text', [4,4,4,6], '900', 'ADD_PRSON_NAME', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
			LABEL([3,3,3,3], '700', $cGENDER);
			RADIO('ADD_PRSON_GEND', [1,2], [true, false], [$cLBL_PRIA, $cLBL_WANITA]);
			LABEL([3,3,3,3], '700', $cALAMAT);
			INPUT('text', [7,7,7,6], '900', 'ADD_ADDRESS', '', '', '', '', 0, '', 'fix', $cTTIP_ALM1);
			LABEL([3,3,3,3], '700', $cERTE);
			INPUT('text', [1,1,1,2], '900', 'ADD_RT', '', '', '999', '', 3, '', '');
			LABEL([1,1,1,3], '700', $cERWE, '', 'right');
			INPUT('text', [1,1,1,2], '900', 'ADD_RW', '', '', '999', '', 3, '', '');
			LABEL([3,2,2,4], '700', $cKODE_POS, '', 'right');
			INPUT('text', [2,1,1,2], '900', 'ADD_PRS_ZIP', '', '', '99999', '', 5, '', 'fix', $cTTIP_KPOS);
            LABEL([3,3,3,3], '700', S_MSG('CB81','Propinsi'). ' *');
			TDIV(9,9,9,9);
                SELECT([], 'ADD_PROPINSI', '', 'prov_s2');
					$qPROV=OpenTable('TbProvince', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'nama');
					while($aPROV=SYS_FETCH($qPROV)) print "<option value='$aPROV[id_prov]'  >$aPROV[nama]</option>";
                echo '</select>';
			eTDIV();
			// TDIV(9,9,9,9);
            //     SELECT([], 'ADD_KAB_KOTA', '', 'kab_s2');
			// 		$qKAB=OpenTable('TbLocDistrict', "", '', 'kabupaten');
			// 		while($aKAB=SYS_FETCH($qKAB)) print "<option value='$aKAB[id_kab]'  >$aKAB[kabupaten]</option>";
            //     echo '</select>';
			// eTDIV();
?>
			<div class="form-group">
				<label class="col-lg-3 col-sm-3 col-xs-3 form-label-700" for="kab_s2"><?php echo $cKOTA?></label>
				<div class="col-sm-9"><input type="hidden" id="kab_s2" name="ADD_KAB_KOTA"></div>
			</div>
			<div class="clearfix"></div>
			<div class="form-group">
				<label class="col-lg-3 col-sm-3 col-xs-3 form-label-700" for="kec_s2"><?php echo $cKECAMATAN?></label>
				<div class="col-sm-9"><input type="hidden" id="kec_s2" name="ADD_PRSN_KEC"></div>
			</div>
			<div class="clearfix"></div>

			<div class="form-group">
				<label class="col-lg-3 col-sm-3 col-xs-3 form-label-700" for="kel_s2"><?php echo $cKELURAHAN?></label>
				<div class="col-sm-9"><input type="hidden" id="kel_s2" name="ADD_PRSN_KEL"></div>
			</div><br>
<?php
				LABEL([3,3,3,4], '700', $cNO_KTP);
				INPUT('text', [4,4,4,6], '900', 'ADD_PRS_KTP', '', '', '9999999999999999', '', 16, '', 'fix', $cNO_KTP);
				echo '<br>';
				TDIV();
					TAB(['Tab_umum', 'Tab_foto', 'Pendidikan', 'Penempatan', 'KartuKeluarga', 'Keahlian'], 
						['fa-user', 'fa-cog', 'fa-phone', 'fa-pencil-square-o', 'fa-phone', 'fa-phone'], 
						[$cTAB_UMUM, $cTAB_FOTO, $cTAB_PENDIDIKAN, $cTAB_PENEMPATAN, $cTAB_KKELUARGA, $cTAB_SKILL]);
					echo '<div class="tab-content primary">';
						echo '<div class="tab-pane fade in active" id="Tab_umum">';
							LABEL([3,3,3,4], '700', $cNO_TELP);
							INPUT('number', [3,3,3,6], '900', 'ADD_PRS_PHN');
							LABEL([3,3,3,4], '700', $cHOMEPHN);
							INPUT('number', [3,3,3,6], '900', 'ADD_HOMEPHN', '', '', '', '', 0, '', 'fix');
							LABEL([3,3,3,4], '700', $cTMP_LAHIR);
							INPUT('text', [3,3,3,6], '900', 'ADD_BIRTH_PLC');
							LABEL([3,3,3,4], '700', $cTGL_LAHIR);
							INP_DATE([3,3,3,6], '900', 'ADD_BIRTH_DATE', date("d/m/Y"));
							LABEL([3,3,3,4], '700', $cEMAIL_ADR);
							INPUT('text', [9,9,9,6], '900', 'ADD_PRS_EMAIL', '', '', '', '', 0, '', 'fix');
							LABEL([3,3,3,4], '700', $cWEB_SITE);
							INPUT('text', [9,9,9,6], '900', 'ADD_PRS_WEB', '', '', '', '', 0, '', 'fix');
							LABEL([3,3,3,4], '700', $cNO_REK);
							INPUT('text', [3,3,3,6], '900', 'ADD_PRSON_ACCN');
							LABEL([2,2,2,4], '700', $cNAMA_BANK);
							INPUT('text', [4,4,4,6], '900', 'ADD_PRSON_BANK', '', '', '', '', 0, '', 'fix');
							LABEL([3,3,3,6], '700', $cAGAMA);
							SELECT([3,3,3,6], 'ADD_PRSON_RELG');
								$qQUERY=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'KODE');
								while($aREC_AG_DATA=SYS_FETCH($qQUERY)){
									echo "<option class='col-sm-3 form-label-900' value='$aREC_AG_DATA[KODE]'  >$aREC_AG_DATA[NAMA]</option>";
								}
							echo '</select>';
							CLEAR_FIX();
							LABEL([3,3,3,6], '700', $cSTATUS);
							SELECT([3,3,3,6], 'ADD_MARRIAGE');
								for ($S=1; $S<=2; $S++) {
									echo "<option value=$S>$aSTATUS[$S]</option>";
								}
							echo '</select>';
							LABEL([3,3,3,6], '700', $cGOL_DAR);
							SELECT([3,3,3,6], 'ADD_BLOOD_GRUP');
								for ($I=1; $I<=4; $I++) {
									echo "<option value=$aGOL_DAR[$I]>$aGOL_DAR[$I]</option>";
								}
							echo '</select>';
							LABEL([3,3,3,4], '700', $cJML_ANAK);
							INPUT('number', [3,3,3,6], '900', 'ADD_CHILD_HAVE');
							LABEL([2,2,2,4], '700', $cPASANGAN);
							INPUT('text', [4,4,4,6], '900', 'ADD_SPOUSE', '', '', '', '', 0, '', 'fix');
							LABEL([3,3,3,4], '700', $cTGL_MASUK);
							INP_DATE([3,3,3,6], '900', 'ADD_TGL_MSK', date("d/m/Y"));
							LABEL([3,3,3,4], '700', $cTGL_TMK);
							INP_DATE([3,3,3,6], '900', 'UPD_TGL_TMK', date("d/m/Y"));
							echo '<br>';
						eTDIV();
?>						
						<div class="tab-pane fade" id="Tab_foto">
							<label class="form-label" for="upload_image"><?php echo $cTAB_FOTO?></label>
							<span class="desc"></span>																	
							<img class="img-responsive" src="<?php echo $cFILE_FOTO_PERSON?>" alt="" style="max-width:220px;">
							<div class="controls">
								<input name="upload_image" type="file" class="form-control">
							</div>
						</div>
<?php

						echo '<div class="tab-pane fade" id="Pendidikan">';
							LABEL([4,4,4,4], '700', $cKD_PENDIDIKAN);
							SELECT([4,4,4,6], 'ADD_EDU_CODE');
								$REC_PEND=OpenTable('TbEducation');
								while($aREC_PNDDKN=SYS_FETCH($REC_PEND)){
									echo "<option value='$aREC_PNDDKN[EDU_CODE]'  >$aREC_PNDDKN[EDU_NAME]</option>";
								}
							echo '</select><br>';
							CLEAR_FIX();
							LABEL([4,4,4,4], '700', $cNM_PENDIDIKAN);
							INPUT('text', [8,8,8,6], '900', 'ADD_EDU_DESC', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cJRSN_PNDDKKAN);
							INPUT('text', [8,8,8,6], '900', 'ADD_JURUSAN', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cT1_PENDIDIKAN);
							INPUT('text', [2,2,2,6], '900', 'ADD_YEAR_IN', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cT2_PENDIDIKAN);
							INPUT('text', [2,2,2,6], '900', 'ADD_YEAR_OUT', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cKT_PENDIDIKAN);
							INPUT('text', [8,8,8,6], '900', 'ADD_EDU_NOTE', '', '', '', '', 0, '', 'fix');
						eTDIV();
						echo '<div class="tab-pane fade" id="Penempatan">';
							LABEL([3,3,3,4], '700', $cJABATAN);
							SELECT([8,8,8,6], 'ADD_JOB', '', '', 'select2');
									$qQUERY=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
									while($aREC_JOB=SYS_FETCH($qQUERY)){
										echo "<option value='$aREC_JOB[JOB_CODE]'  >$aREC_JOB[JOB_NAME]</option>";
									}
							echo '</select><br>';
							if($ada_OUTSOURCING) {
								LABEL([3,3,3,4], '700', $cCUSTOMER);
								SELECT([8,8,8,6], 'ADD_CUST', '', '', 'select2');
									$q_CUST=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
									while($aREC_CUSTOMER=SYS_FETCH($q_CUST)){
										echo "<option value='$aREC_CUSTOMER[CUST_CODE]' >$aREC_CUSTOMER[CUST_NAME]</option>";
									}
								echo '</select><br>';
							}
							LABEL([3,3,3,4], '700', $cLOKASI);
							SELECT([8,8,8,6], 'ADD_LOC', '', '', 'select2');
								$qLOCS=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
								while($aREC_LOC=SYS_FETCH($qLOCS)){
									echo "<option value='$aREC_LOC[LOKS_CODE]' >$aREC_LOC[LOKS_NAME]</option>";
								}
							echo '</select><br>';
							LABEL([3,3,3,4], '700', $cCATATAN);
							INPUT('text', [8,8,8,6], '900', 'ADD_OUT_NOTE', '', '', '', '', 0, '', 'fix');
						eTDIV();
						echo '<div class="tab-pane fade" id="KartuKeluarga">';
							LABEL([4,4,4,4], '700', $cNO_KKELUARGA);
							INPUT('text', [4,4,4,6], '900', 'ADD_NO_KKLRG', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cNM_KKELUARGA);
							INPUT('text', [6,6,6,6], '900', 'ADD_NM_KKLRG', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cFULL_NAME);
							INPUT('text', [6,6,6,6], '900', 'ADD_FULL_NAME', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cNIK_NIKS);
							INPUT('text', [4,4,4,6], '900', 'ADD_N_I_K', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cBPJS);
							INPUT('text', [4,4,4,6], '900', 'ADD_NO_BPJS', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,6], '700', $cGENDER);
							RADIO('ADD_GENDER', [1,2], [true, false], [$cLBL_PRIA, $cLBL_WANITA]);
							LABEL([4,4,4,4], '700', $cTMP_LAHIR);
							INPUT('text', [4,4,4,6], '900', 'ADD_BIRTH_PLCE', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cTGL_LAHIR);
							INP_DATE([3,3,3,6], '900', 'ADD_BIRTH_DATE', date("d/m/Y"), '', '', '', 'fix');
							LABEL([4,4,4,4], '700', $cAGAMA);
							SELECT([3,3,3,6], 'ADD_RELI_GION');
								$qQUERY=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'KODE');
									echo "<option value=' '  > </option>";
									while($aREC_PRS_RLGN=SYS_FETCH($qQUERY)){
										echo "<option class='form-label-900' value='$aREC_PRS_RLGN[KODE]'  >$aREC_PRS_RLGN[NAMA]</option>";
									}
							echo '</select>';
							CLEAR_FIX();
							LABEL([4,4,4,4], '700', $cKD_PENDIDIKAN);
							SELECT([3,3,3,6], 'ADD_EDUCATE');
								$qQUERY=OpenTable('TbEducation', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
									while($aREC_PNDDKN=SYS_FETCH($qQUERY)){
										echo "<option value='$aREC_PNDDKN[EDU_CODE]'  >$aREC_PNDDKN[EDU_NAME]</option>";
									}
							echo '</select><br>';
							CLEAR_FIX();
							LABEL([4,4,4,4], '700', $cPEKERJAAN);
							INPUT('text', [4,4,4,6], '900', 'ADD_J_O_B', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cSTATUS);
							SELECT([3,3,3,6], 'ADD_STATUS');
								$qSTATUS=OpenTable('TbStatus', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
								echo "<option value=' '  > </option>";
								while($aREC_STATUS=SYS_FETCH($qSTATUS)){
									echo "<option value='$aREC_STATUS[KODE]'  >$aREC_STATUS[NAMA]</option>";
								}
							echo '</select>';
							CLEAR_FIX();
							LABEL([4,4,4,4], '700', $cSTA_TUS);
							INPUT('text', [4,4,4,6], '900', 'ADD_STA_TUS', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cKWARGA);
							INPUT('text', [4,4,4,6], '900', 'ADD_CITI_ZEN', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cPASSPORT);
							INPUT('text', [4,4,4,6], '900', 'ADD_NO_PASPORT', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cKITAS);
							INPUT('text', [4,4,4,6], '900', 'ADD_NO_SITAS', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cAYAH);
							INPUT('text', [4,4,4,6], '900', 'ADD_FATH_NAME', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cIBU);
							INPUT('text', [4,4,4,6], '900', 'ADD_MOTH_NAME', '', '', '', '', 0, '', 'fix');
							CLEAR_FIX();
						eTDIV();
						echo '<div class="tab-pane fade" id="Keahlian">';
							LABEL([4,4,4,4], '700', $cNAMA_SKILL);
							SELECT([5,5,5,6], 'ADD_SKILL_CODE');
								echo '<option></option>';
									$REC_PEND=OpenTable('TbSkill');
									while($aREC_SKILL=SYS_FETCH($REC_PEND)){
										echo "<option value='$aREC_SKILL[SKILL_CODE]'  >$aREC_SKILL[SKILL_NAME]</option>";
									}
							echo '</select><br>';
							LABEL([4,4,4,4], '700', $cKET_SKILL);
							INPUT('text', [8,8,8,6], '900', 'ADD_SKILL_DESC', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cTHN_SKILL);
							INPUT('text', [2,2,2,6], '900', 'ADD_YEAR_SKILL', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cSERT_SKILL);
							INPUT('text', [8,8,8,6], '900', 'ADD_SKILL_SERT', '', '', '', '', 0, '', 'fix');
							LABEL([4,4,4,4], '700', $cNOTE_SKILL);
							INPUT('text', [8,8,8,6], '900', 'ADD_SKILL_NOTE', '', '', '', '', 0, '', 'fix');
						eTDIV();
					eTDIV();
				eTDIV();
			eTDIV();
			CLEAR_FIX();
			SAVE($cSAVE_DATA);
		eTFORM();
	END_WINDOW();
?>
