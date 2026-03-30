<?php
//	prs_group_salary.php //
//	TODO : add

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cHELP_FILE 	= 'Doc/Tabel - Kelompok Gaji.pdf';

	$cAPP_CODE = $_SESSION['data_FILTER_CODE']; 
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHEADER = S_MSG('PF66','Tabel Kelompok Gaji');
	$can_CREATE = TRUST($cUSERCODE, 'PRS_GROUP_SLRY_1ADD');

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

	$cCUSTOMER	= S_MSG('RS04','Customer');
	$cLOKASI	= S_MSG('PF16','Lokasi');
	$cJABATAN	= S_MSG('PF13','Jabatan');

	$cKODE	    = S_MSG('F003','Kode');
	$cKETERANGAN = S_MSG('H667','Keterangan');
	$cADD_SCH	= S_MSG('F300','Tambah');
	$cDEL_MSG	= S_MSG('F021','Benar data ini mau di hapus ?');
	$cSAVE		= S_MSG('F301','Save');
	
	$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	$nSCOPE = ( SYS_FETCH($qSCOPE) ? SYS_ROWS($qSCOPE) : 0);
	$can_UPDATE = TRUST($cUSERCODE, 'PRS_GROUP_SLRY_2UPD');	$can_DELETE = TRUST($cUSERCODE, 'PRS_GROUP_SLRY_3DEL');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('PI1A','Help Tabel Jam Kerja');

		$qQUERY=OpenTable('PrsGroupSlry', "S.APP_CODE='$cAPP_CODE' and S.REC_ID not in ( select DEL_ID from logs_delete)".($nSCOPE==0 ? "" : " and SLRY_CUST in ( select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE')"));
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
				TABLE('example');
				THEAD([$cKODE, $cKETERANGAN, $cCUSTOMER, $cLOKASI, $cJABATAN]);
						echo '<tbody>';
							while($aGROUP_SLRY=SYS_FETCH($qQUERY)) {
								$cHREFF="<a href='?_a=".md5('upd4t3')."&_s=$aGROUP_SLRY[REC_ID]'>";
								$aCOL = [$aGROUP_SLRY['SLRY_CODE'], DECODE($aGROUP_SLRY['SLRY_DESC']), DECODE($aGROUP_SLRY['CUST_NAME']), $aGROUP_SLRY['LOKS_NAME'], $aGROUP_SLRY['JOB_NAME']];
								TDETAIL($aCOL, [], '', [$cHREFF, $cHREFF, $cHREFF, $cHREFF, $cHREFF]);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('cr34t3'):
		$qCUST=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
		$allCUST = ALL_FETCH($qCUST);
		DEF_WINDOW($cADD_SCH);
			TFORM($cADD_SCH, '?_a=GRP_NEW', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE);
					INPUT('text', [2,2,2,6], '900', 'ADD_KODE', '', 'focus', '', '', 10, '', 'fix');
					LABEL([3,3,3,6], '700', $cKETERANGAN);
					INPUT('text', [5,5,5,5], '900', 'ADD_KET', '', '', '', '', 50, '', 'fix');
					echo '<br>';
					LABEL([3,3,3,6], '700', $cCUSTOMER);
					TDIV(6,6,6,12);
						SELECT([1,1,2,5], 'ADD_CUST', '', 's2example-1');
						echo '<option value="" selected>All</option>';
							$qCUST_GROUP=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'NAMA_GRP');
							while($aCUST_GROUP=SYS_FETCH($qCUST_GROUP)){
								echo '<optgroup label="'.$aCUST_GROUP['NAMA_GRP'].'">';
								$I=0;
								$nSIZE_ARRAY = count($allCUST);
								while($I<$nSIZE_ARRAY-1) {
									if ($allCUST[$I]['CUST_GROUP']==$aCUST_GROUP['KODE_GRP']) {
										$cSELECT = $allCUST[$I]['CUST_NAME'];
										$cVALUE = $allCUST[$I]['CUST_CODE'];
										echo "<option value='$cVALUE' >".$cSELECT." </option>";
									}
									$I++;
								}
							}
							echo '</optgroup>';
						echo '</select>';
					eTDIV();
					CLEAR_FIX();

					LABEL([3,3,3,6], '700', $cLOKASI);
					TDIV(6,6,6,12);
						SELECT([1,1,2,5], 'ADD_LOC', '', 's2example-2');
							echo '<option value="" selected>All</option>';
								$qLOKASI=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
								while($aUPD_LOKASI=SYS_FETCH($qLOKASI)){
									echo "<option value='$aUPD_LOKASI[LOKS_CODE]'  >$aUPD_LOKASI[LOKS_NAME]</option>";
								}
								echo '</optgroup>';
							echo '</select>';
					eTDIV();
					CLEAR_FIX();

					LABEL([3,3,3,6], '700', $cJABATAN);
					TDIV(6,6,6,12);
						SELECT([1,1,2,5], 'ADD_JOB', '', '', 'select2');
						echo '<option value="" selected>All</option>';
							$qQUERY=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
							while($aUPD_JOB=SYS_FETCH($qQUERY)){
								echo "<option value='$aUPD_JOB[JOB_CODE]'  >$aUPD_JOB[JOB_NAME]</option>";
							}
						echo '</select><br><br><br>';
					eTDIV();
					CLEAR_FIX();

					LABEL([3,3,3,6], '700', S_MSG('CB81','Prov.'));
					TDIV(6,6,6,12);
					//         SELECT([], 'ADD_PROPINSI', '', 'prov_s2', 'select2');
					// 		echo '<option>All</option>';
					//                 $qPROV=OpenTable('TbProvince', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'nama');
					//                 while($aPROV=SYS_FETCH($qPROV)) print "<option value='$aPROV[id_prov]'  >$aPROV[nama]</option>";
					//         echo'</select>';
					// eTDIV();

						echo '<select name="ADD_PROV" class="select2" id="s2example-1">';
						echo '<option></option>';
							$qPROV=OpenTable('TbProvince', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'nama');
							while($aPROV=SYS_FETCH($qPROV)){
								echo "<option value='$aPROV[id_prov]'  >$aPROV[nama]</option>";
							}
						echo '</select><br><br>';
					eTDIV();
					CLEAR_FIX();

					LABEL([3,3,3,6], '700', S_MSG('CB82','Kabupaten'));
					TDIV(6,6,6,12);
						SELECT([6,6,6,6], 'ADD_KAB', '', 's2example-1', 'select2');
							echo '<option></option>';
							$qKAB=OpenTable('TbLocDistrict', "id_prov='$aREC_SALARY[SLRY_PROV]'", '', 'kabupaten');
							while($aKAB=SYS_FETCH($qKAB)){
								echo "<option value='$aKAB[id_kab]'  >$aKAB[kabupaten]</option>";
							}
						echo '</select><br><br>';
					eTDIV();
					CLEAR_FIX();
				SAVE(S_MSG('F301','Save'));
			eTFORM();
		END2WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('upd4t3'):
		$cEDIT	    = S_MSG('PF67','Edit Kelompok Gaji');
		$view_TAB_TUNJ=1;   $upd_TAB_TUNJ=1;
		$view_TAB_UM=1;   $upd_TAB_UM=1;
		$qQUERY=OpenTable('PrsGroupSlry', "S.REC_ID='$_GET[_s]' and S.APP_CODE='$cAPP_CODE' and S.REC_ID not in ( select DEL_ID from logs_delete)");
		$aREC_SALARY=SYS_FETCH($qQUERY);
		$qCUST=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
		$allCUST = ALL_FETCH($qCUST);
		DEF_WINDOW($cEDIT);
			$aACT =[];
			if ($can_DELETE==1) array_push($aACT, '<a href="?_a='.md5('del_data').'&id='.$aREC_SALARY['REC_ID']. '" onClick="return confirm('. "'". $cDEL_MSG. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>');
			TFORM($cEDIT, '?_a=GRP_UPD&id='.$aREC_SALARY['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE);
					INPUT('text', [2,2,2,3], '900', 'UPD_KODE', $aREC_SALARY['SLRY_CODE'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,6], '700', $cKETERANGAN);
					INPUT('text', [6,6,6,6], '900', 'UPD_KET', DECODE($aREC_SALARY['SLRY_DESC']), 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cCUSTOMER);
					TDIV(6,6,6,12);
						SELECT([6,6,6,6], 'UPD_CUST', '', '', 'select2');
							echo '<option value="" selected> All </option>';
								$qCUST_GROUP=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'NAMA_GRP');
								while($aCUST_GROUP=SYS_FETCH($qCUST_GROUP)){
									echo '<optgroup label="'.$aCUST_GROUP['NAMA_GRP'].'">';
									$I=0;
									$nSIZE_ARRAY = count($allCUST);
									while($I<$nSIZE_ARRAY-1) {
										if ($allCUST[$I]['CUST_GROUP']==$aCUST_GROUP['KODE_GRP']) {
											$cSELECT = $allCUST[$I]['CUST_NAME'];
											$cVALUE = $allCUST[$I]['CUST_CODE'];

											if($aREC_SALARY['SLRY_CUST']==$cVALUE)
												echo "<option value='$cVALUE' selected='$cVALUE'>$cSELECT</option>";
											else
												echo "<option value='$cVALUE' >".$cSELECT." </option>";
										}
										$I++;
									}
								}
							echo '</optgroup>';
						echo '</select><br>';
					eTDIV();
					CLEAR_FIX();
					LABEL([3,3,3,6], '700', $cLOKASI);
					TDIV(6,6,6,12);
						SELECT([6,6,6,6], 'UPD_LOC', '', '', 'select2');
							echo '<option></option>';
								$qLOKASI=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
								while($aUPD_LOKASI=SYS_FETCH($qLOKASI)){
									if($aREC_SALARY['SLRY_LOC']==$aUPD_LOKASI['LOKS_CODE'])
										echo "<option value='$aUPD_LOKASI[LOKS_CODE]' selected='$aREC_SALARY[LOKS_CODE]' >$aUPD_LOKASI[LOKS_NAME]</option>";
									else
										echo "<option value='$aUPD_LOKASI[LOKS_CODE]'  >$aUPD_LOKASI[LOKS_NAME]</option>";
								}
							echo '</optgroup>';
						echo '</select><br>';
					eTDIV();
					CLEAR_FIX();
					LABEL([3,3,3,6], '700', $cJABATAN);
					TDIV(6,6,6,12);
						SELECT([6,6,6,6], 'UPD_JOB', '', '', 'select2');
							echo '<option>All</option>';
								$qQUERY=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
								while($aUPD_JOB=SYS_FETCH($qQUERY)){
									if($aREC_SALARY['SLRY_JOB'] == $aUPD_JOB['JOB_CODE'])
										echo "<option value='$aUPD_JOB[JOB_CODE]' selected='$aREC_SALARY[JOB_CODE]'".">".$aUPD_JOB['JOB_NAME']."</option>";
									else
										echo "<option value='$aUPD_JOB[JOB_CODE]'  >$aUPD_JOB[JOB_NAME]</option>";
								}
						echo '</select><br>';
					eTDIV();
					CLEAR_FIX();
					LABEL([3,3,3,6], '700', S_MSG('CB81','Prov.'));
					TDIV(6,6,6,12);
						SELECT([6,6,6,6], 'UPD_PROV', '', '', 'select2');
						// echo '<select name="UPD_PROV" class="select2-container" id="s2example-1">';
							echo '<option></option>';
								$qPROV=OpenTable('TbProvince', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'nama');
								while($aPROV=SYS_FETCH($qPROV)){
									if($aREC_SALARY['SLRY_PROV'] == $aPROV['id_prov'])
										echo "<option value='$aPROV[id_prov]' selected='$aREC_SALARY[SLRY_PROV]'".">".$aPROV['nama']."</option>";
									else
										echo "<option value='$aPROV[id_prov]'  >$aPROV[nama]</option>";
								}
						echo '</select><br>';
					eTDIV();
					CLEAR_FIX();
					LABEL([3,3,3,6], '700', S_MSG('CB82','Kabupaten'));
					TDIV(6,6,6,12);
						SELECT([6,6,6,6], 'UPD_KAB', '', '', 'select2');
						// echo '<select name="UPD_KAB" class="select2" id="s2example-1">';
							echo '<option></option>';
								$qKAB=OpenTable('TbLocDistrict', "id_prov='$aREC_SALARY[SLRY_PROV]'", '', 'kabupaten');
								while($aKAB=SYS_FETCH($qKAB)){
									if($aREC_SALARY['SLRY_DIST'] == $aKAB['id_kab'])
										echo "<option value='$aKAB[id_kab]' selected='$aREC_SALARY[SLRY_DIST]'".">".$aKAB['kabupaten']."</option>";
									else
										echo "<option value='$aKAB[id_kab]'  >$aKAB[kabupaten]</option>";
								}
						echo '</select><br><br>';
					eTDIV();
					CLEAR_FIX();
					echo '<br>';
					TDIV();
						$aTAB=[];	$aICON=[];	$aCAPTION=[];
						if ($view_TAB_TUNJ==1 || $upd_TAB_TUNJ==1) {
							array_push($aTAB, 'TabUpdTunj');	array_push($aICON, 'fa-home');	array_push($aCAPTION, 'Tunjangan');
						}
						if ($view_TAB_UM==1 || $upd_TAB_UM==1) {
							array_push($aTAB, 'TabUpdUM');	array_push($aICON, 'fa-home');	array_push($aCAPTION, 'Uang Makan');
						}
						TAB($aTAB, $aICON, $aCAPTION);
						echo '<div class="tab-pane fade in active" id="TabUpdTunj">';
							TABLE('myTable');
							THEAD(['Kode', 'Nama Tunjangan', 'Nilai Tunjangan'], '*', [0,0,1], '*'); ?>
								<tbody><br>
									<div>
										<a href="#Add_Tunjangan" data-toggle="modal" > <i class="fa fa-plus-square"></i>'<?php echo 'Add new' ?></a>
									</div>
									<?php
										$qTUNJ=OpenTable('PrsGroupAllw', "SLRY_CODE='$aREC_SALARY[SLRY_CODE]' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''", '', "A.ALLW_CODE");
										while($aREC_TUNJ=SYS_FETCH($qTUNJ)) {
											echo '<tr>';
												echo "<td><span><a href='?_a=edit_dtl_tunj&_r=$aREC_TUNJ[ALLW_REC]'>". $aREC_TUNJ['TNJ_CODE'].'</a></span></td>';
												echo "<td><span><a href='?_a=edit_dtl_tunj&_r=$aREC_TUNJ[ALLW_REC]'>". $aREC_TUNJ['TNJ_NAME'].'</a></span></td>';
												echo '<td style="text-align:right"><span>'. CVR($aREC_TUNJ['ALLW_VALUE']).'</a></span></td>';
												echo '</tr>';
										}
								echo '</tbody>';
							echo '</table><br><br><br><br>';
						eTDIV();
						echo '<div class="tab-pane fade" id="TabUpdUM">';
							LABEL([3,3,3,6], '700', 'Uang Makan');
							INPUT('text', [2,2,2,6], '900', 'UPD_UANGMAKAN', CVR($aREC_SALARY['SLRY_MEALS']), '', '', '', 0, '', 'Fix');
						eTDIV();
	?>
						<div class="modal" id="Add_Tunjangan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
							<div class="modal-dialog animated bounceInDown">
								<div class="modal-content">
								<form action ="?_a=AddPend&KODE_PEG=<?php echo $REC_EDIT['MASTER_CODE']?>" method="post">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><?php echo S_MSG('PA81','Tmb Tunj')?></h4>
									</div>
									<div class="modal-body">

										<div class="form-group">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('PJ57','Nama Tunj.')?></label>
											<div class="controls">
												<select id="SelectTunjang" name="ADD_EDU_CODE" class="col-sm-5 form-label-900">
												<?php 
													$REC_TUNJ=OpenTable('TbAllowance', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
													while($aREC_ADD_TJ=SYS_FETCH($REC_TUNJ)){
														echo "<option value='$aREC_ADD_TJ[TNJ_CODE]'  >$aREC_ADD_TJ[TNJ_NAME]</option>";
													}
												?>
												</select><br>
											</div>
										</div>
											
										<label class="col-sm-4 form-label-700" for="field-3"><?php echo S_MSG('PA63','Nilai Tunjangan')?></label>
										<input type="text" class="col-sm-4 form-label-900" name='ADD_NIL_TUNJ'><br><br>
										<div class="col-sm-7 form-block"><input type="checkbox" name="ADD_LBR_TNJ" checked class="iswitch iswitch-md iswitch-secondary">'<?php echo S_MSG('PF69','Komp. Lembur')?>'</div><br>
											
									</div>
									<div class="modal-footer">
										<input type="submit" class="btn btn-primary" value=<?php echo S_MSG('F301','Save')?>>
										<button data-dismiss="modal" class="btn btn-default" type="button"><?php echo S_MSG('F302','Close')?></button>
									</div>
								</form>
								</div>
							</div>
						</div>
<?php
					eTDIV();
					SAVE($can_UPDATE==1 ? S_MSG('F301','Save') : '');
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

case md5('del_data'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	RecUpdate('PrsGroupSlry', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, $NOW], "APP_CODE='$cAPP_CODE' and REC_ID='$KODE_CRUD'");
	header('location:prs_group_salary.php');
	break;

case 'GRP_UPD':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	RecUpdate('PrsGroupSlry', ['SLRY_CUST', 'SLRY_LOC', 'SLRY_JOB', 'SLRY_PROV', 'SLRY_DIST', 'SLRY_DESC'], 
		[$_POST['UPD_CUST'], $_POST['UPD_LOC'], $_POST['UPD_JOB'], $_POST['UPD_PROV'], $_POST['UPD_KAB'], $_POST['UPD_KET']], "REC_ID='$KODE_CRUD'");
	header('location:prs_group_salary.php');
	break;

	case 'GRP_NEW':
		$cCODE = ENCODE($_POST['ADD_KODE']);
		if($cCODE==''){
			MSG_INFO('Kode kelompok gaji masih kosong');
		} else {
			RecCreate('PrsGroupSlry', ['SLRY_CODE', 'SLRY_DESC', 'SLRY_CUST', 'SLRY_LOC', 'SLRY_JOB', 'SLRY_PROV', 'SLRY_DIST', 'APP_CODE', 'ENTRY', 'REC_ID'], 
				[$_POST['ADD_KODE'], ENCODE($_POST['ADD_KET']), $_POST['ADD_CUST'], $_POST['ADD_LOC'], $_POST['ADD_JOB'], $_POST['ADD_PROV'], $_POST['ADD_KAB'], $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		}
		header('location:prs_group_salary.php');
	break;

	case 'edit_dtl_tunj':
		$cHDR=S_MSG('PA6D','Edit Tunjangan');
        $eDTL_REC_NO = $_GET['_r'];
        $qQUERY=OpenTable('PrsGroupAllw', "ALLW_REC='$eDTL_REC_NO' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''", '', "A.ALLW_CODE");
        $aREC_ALLOWN = SYS_FETCH($qQUERY);
		DEF_WINDOW($cHDR);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=pnd_dtl_delete&_id='. $eDTL_REC_NO. '" onClick="return confirm('. "'". $cDEL_MSG. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cHDR, '?_a=DB_TUNJ&id='.$eDTL_REC_NO, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', 'Tunjangan');
					echo '<select name="UPD_DTL_ALLW" class="col-sm-5 form-label-900">';
						$qREC_TB_ALLWN=OpenTable('TbAllowance', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_ALLOWANCE=SYS_FETCH($qREC_TB_ALLWN)){
							if($aREC_ALLOWN['ALLW_CODE'] == $aREC_ALLOWANCE['TNJ_CODE']){
								echo "<option class='col-sm-4 form-label-900' value='$aREC_ALLOWANCE[TNJ_CODE]' selected='$aREC_ALLOWANCE[TNJ_CODE]'>$aREC_ALLOWANCE[TNJ_NAME]</option>";
							} else { 
								echo "<option value='$aREC_ALLOWANCE[TNJ_CODE]'  >$aREC_ALLOWANCE[TNJ_NAME]</option>";
							}
						}
					echo '</select><br>';
					CLEAR_FIX();
					LABEL([4,4,4,6], '700', 'Nilai');
					INPUT('number', [2,2,2,6], '900', 'UPD_NILAI', $aREC_ALLOWN['ALLW_VALUE'], '', '', 'right', 0, '', 'fix');
					SAVE($can_UPDATE==1 ? $cSAVE : '');
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

case 'DB_TUNJ':
	$cCODE = $_GET['id'];
	$cALLW = $_POST['UPD_DTL_ALLW'];
	$cVAL = $_POST['UPD_NILAI'];
	print_r2($cCODE);
	print_r2($cALLW);
	print_r2($cVAL);
	MSG_INFO('DB_TUNJ');
	break;
}
?>

