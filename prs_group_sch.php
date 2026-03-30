<?php
//	prs_group_sch.php //

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cHELP_FILE = 'Doc/Tabel - Jadwal Group.pdf';
$cAPP_CODE = $_SESSION['data_FILTER_CODE']; $cUSERCODE = $_SESSION['gUSERCODE'];
$cHEADER = S_MSG('PI00','Tabel Jam Kerja');
$iOUTSOURCING=IS_OUTSOURCING($cAPP_CODE);

$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

$cCUSTOMER	= S_MSG('RS04','Customer');
$cLOKASI	= S_MSG('PF16','Lokasi');
$cJABATAN	= S_MSG('PF13','Jabatan');

$cKETERANGAN = S_MSG('PA98','Keterangan');
$cJADWAL	= S_MSG('PB41','Jadwal Kerja');
$cADD_SCH	= S_MSG('CR21','Tambah Jam Kerja');

$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
$nSCOPE = ( SYS_FETCH($qSCOPE) ? SYS_ROWS($qSCOPE) : 0);

switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'PRS_GROUP_SCH_1ADD');
		$ADD_LOG	= APP_LOG_ADD($cHEADER);
		$qQUERY=OpenTable('PrsGroupSch', "G.APP_CODE='$cAPP_CODE' and G.DELETOR=''");
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE ? ['<a href="?_a='. md5('CR34T3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						$aHEAD=[$cLOKASI, $cJABATAN, $cJADWAL, $cKETERANGAN];
						if($iOUTSOURCING)	array_splice($aHEAD, 0, 0, $cCUSTOMER);
						THEAD($aHEAD, '', []);
						echo '<tbody>';
							while($aGROUP_SCH=SYS_FETCH($qQUERY)) {
								$aDTL=[$aGROUP_SCH['LOKS_NAME'], $aGROUP_SCH['JOB_NAME'], $aGROUP_SCH['DESC_CRPTN'], $aGROUP_SCH['SCH_NOTE']];
								$cREFF="<a href='?_a=".md5('upd4t3')."&_s=$aGROUP_SCH[SCH_REC]'>";
								$aREFF=[$cREFF, $cREFF, $cREFF, $cREFF];
								if($iOUTSOURCING)	{
									array_splice($aHEAD, 0, 0, $aGROUP_SCH['CUST_NAME']);
									array_splice($aREFF, 0, 0, $aREFF);
								}
								TDETAIL($aDTL, [], '', $aREFF);
							}
						echo '/<tbody>';
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('CR34T3'):
		$qCUST=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
		$allCUST = ALL_FETCH($qCUST);
		DEF_WINDOW($cADD_SCH);
			TFORM($cADD_SCH, '?_a=create', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cCUSTOMER);
					TDIV(8,8,8,8);
						SELECT([5,5,5,5], 'ADD_CUST', '', '', 'select2');
							echo '<option value=" " ></option>';
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
							echo '</select><br><br>';
					eTDIV();
					CLEAR_FIX();
					LABEL([3,3,3,6], '700', $cLOKASI);
					TDIV(8,8,8,8);
						SELECT([5,5,5,5], 'ADD_LOC', '', '', 'select2');
							echo '<option value=" " ></option>';
								$qLOKASI=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
									while($aUPD_LOKASI=SYS_FETCH($qLOKASI)){
										echo "<option value='$aUPD_LOKASI[LOKS_CODE]'  >$aUPD_LOKASI[LOKS_NAME]</option>";
									}
						echo '</select><br><br>';
					eTDIV();
					CLEAR_FIX();
					LABEL([3,3,3,6], '700', $cJABATAN);
					TDIV(8,8,8,8);
						SELECT([5,5,5,5], 'ADD_JOB', '', '', 'select2');
							echo '<option value=" " ></option>';
								$qQUERY=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
								while($aUPD_JOB=SYS_FETCH($qQUERY)){
									echo "<option value='$aUPD_JOB[JOB_CODE]'  >$aUPD_JOB[JOB_NAME]</option>";
								}
						echo '</select><br><br>';
					eTDIV();
					CLEAR_FIX();
					LABEL([3,3,3,6], '700', $cJADWAL);
					TDIV(8,8,8,8);
						SELECT([5,5,5,5], 'ADD_SCHD', '', '', 'select2');
							echo '<option value=" " ></option>';
									$qQUERY=OpenTable('PrsSchedule', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'DESC_CRPTN');
									while($aADD_SCH=SYS_FETCH($qQUERY)){
											echo "<option value='$aADD_SCH[DAYL_CODE]'  >$aADD_SCH[DESC_CRPTN]</option>";
									}
						echo '</select><br><br>';
					eTDIV();
					CLEAR_FIX();
					LABEL([3,3,3,6], '700', $cKETERANGAN);
					INPUT('text', [8,8,8,8], '900', 'ADD_DESC', '', '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				TDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('upd4t3'):
		$cEDIT_SCH	= S_MSG('PI11','Edit Jam Kerja');
		$cDEL_MSG	= S_MSG('F021','Benar data ini mau di hapus ?');
		$can_UPDATE = TRUST($cUSERCODE, 'PRS_GROUP_SCH_2UPD');	
		$can_DELETE = TRUST($cUSERCODE, 'PRS_GROUP_SCH_3DEL');
		$qQUERY=OpenTable('PrsGroupSch', "G.SCH_REC='$_GET[_s]' and G.APP_CODE='$cAPP_CODE' and G.DELETOR=''");
		$aREC_SCHEDULE=SYS_FETCH($qQUERY);
		$qCUST=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
		$allCUST = ALL_FETCH($qCUST);
		DEF_WINDOW($cEDIT_SCH);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=del_data&_id='.$aREC_SCHEDULE['SCH_REC']. '" onClick="return confirm('. "'". $cDEL_MSG. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_SCH, '?_a=update&id='.$aREC_SCHEDULE['SCH_REC'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cCUSTOMER);
?>
					<div class="form-group">
						<div class="col-sm-8 col-md-8">
							<select name="UPD_CUST" class="select2" display:inline-block>
								<option value=' ' > </option>
								<?php 
									$qCUST_GROUP=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'NAMA_GRP');
									while($aCUST_GROUP=SYS_FETCH($qCUST_GROUP)){
										echo '<optgroup label="'.$aCUST_GROUP['NAMA_GRP'].'">';
										$I=0;
										$nSIZE_ARRAY = count($allCUST);
										while($I<$nSIZE_ARRAY-1) {
											if ($allCUST[$I]['CUST_GROUP']==$aCUST_GROUP['KODE_GRP']) {
												$cSELECT = $allCUST[$I]['CUST_NAME'];
												$cVALUE = $allCUST[$I]['CUST_CODE'];

												if($aREC_SCHEDULE['SCH_CUST']==$cVALUE)
													echo "<option value='$cVALUE' selected='$cVALUE'>$cSELECT</option>";
												else
													echo "<option value='$cVALUE' >".$cSELECT." </option>";
											}
											$I++;
										}
									}
								?>
								</optgroup>
							</select><br><br>
						</div>
					</div>
					<div class="clearfix"></div>

					<label class="col-sm-3 form-label-700"><?php echo $cLOKASI?></label>
					<div class="form-group">
						<div class="col-sm-8 col-md-8">
							<select name="UPD_LOC" class="select2">
								<option value=' ' ></option>
								<?php 
									$qLOKASI=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
									while($aUPD_LOKASI=SYS_FETCH($qLOKASI)){
										if($aREC_SCHEDULE['SCH_LOC']==$aUPD_LOKASI['LOKS_CODE'])
											echo "<option value='$aUPD_LOKASI[LOKS_CODE]' selected='$aREC_SCHEDULE[SCH_LOC]' >$aUPD_LOKASI[LOKS_NAME]</option>";
										else
											echo "<option value='$aUPD_LOKASI[LOKS_CODE]'  >$aUPD_LOKASI[LOKS_NAME]</option>";
									}
								?>
								</optgroup>
							</select><br><br>
						</div>
					</div>
					<div class="clearfix"></div>
					<label class="col-sm-3 form-label-700"><?php echo $cJABATAN?></label>
					<div class="form-group">
						<div class="col-sm-8 col-md-8">
							<select name="UPD_JOB" class="select2">
							<option value=' ' ></option>
								<?php
									$qQUERY=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
									while($aUPD_JOB=SYS_FETCH($qQUERY)){
										if($aREC_SCHEDULE['SCH_JOB'] == $aUPD_JOB['JOB_CODE'])
											echo "<option value='$aUPD_JOB[JOB_CODE]' selected='$aREC_SCHEDULE[SCH_JOB]'".">".$aUPD_JOB['JOB_NAME']."</option>";
										else
											echo "<option value='$aUPD_JOB[JOB_CODE]'  >$aUPD_JOB[JOB_NAME]</option>";
									}
								?>
							</select><br><br>
						</div>
					</div>
					<div class="clearfix"></div>

					<label class="col-sm-3 form-label-700"><?php echo $cJADWAL?></label>
					<div class="form-group">
						<div class="col-sm-8 col-md-8">
							<select name="UPD_SCHD" class="select2">
							<option value=' ' ></option>
								<?php
									$qQUERY=OpenTable('PrsSchedule', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'DESC_CRPTN');
									while($aUPD_SCH=SYS_FETCH($qQUERY)){
										if($aREC_SCHEDULE['SHIFT_CODE'] == $aUPD_SCH['DAYL_CODE'])
											echo "<option value='$aUPD_SCH[DAYL_CODE]' selected='$aREC_SCHEDULE[SHIFT_CODE]'".">".$aUPD_SCH['DESC_CRPTN']."</option>";
										else
											echo "<option value='$aUPD_SCH[DAYL_CODE]'  >$aUPD_SCH[DESC_CRPTN]</option>";
									}
								?>
							</select><br><br>
						</div>
					</div>
<?php
					CLEAR_FIX();
					LABEL([3,3,3,6], '700', $cKETERANGAN);
					INPUT('text', [8,8,8,8], '900', 'UPD_KET', '', '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				TDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case 'del_data':
		$KODE_CRUD=$_GET['_id'];
		RecUpdate('PrsGroupSch', ['DELETOR', 'DEL_DATE'], [$cUSERCODE,date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and SCH_REC='$KODE_CRUD'");
		header('location:prs_group_sch.php');
		break;

	case 'update':
		$KODE_CRUD=$_GET['id'];
		RecUpdate('PrsGroupSch', ['SCH_CUST', 'SCH_LOC', 'SCH_JOB', 'SHIFT_CODE', 'SCH_NOTE', 'UP_DATE', 'UPD_DATE'], 
			[$_POST['UPD_CUST'], $_POST['UPD_LOC'], $_POST['UPD_JOB'], $_POST['UPD_SCHD'], $_POST['UPD_KET'], $cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and SCH_REC='$KODE_CRUD'");
		header('location:prs_group_sch.php');
		break;

	case 'create':
		$cADD_DESC = ENCODE($_POST['ADD_DESC']);
		if($cADD_DESC==''){
			MSG_INFO(S_MSG('PB43','Nama jadwal masih kosong'));
			header('location:prs_group_sch.php');
			return;
		}
		$cCUST=($_POST['ADD_CUST']=='' ? '' : $_POST['ADD_CUST']);
		RecCreate('PrsGroupSch', ['SCH_CUST', 'SCH_LOC', 'SCH_JOB', 'SHIFT_CODE', 'SCH_NOTE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
			[$cCUST, $_POST['ADD_LOC'], $_POST['ADD_JOB'], $_POST['ADD_SCHD'], $cADD_DESC, $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s")]);
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Create : '.$cCUST.', '.$_POST['ADD_LOC'].', '.$_POST['ADD_JOB']);
		header('location:prs_group_sch.php');
}
?>

