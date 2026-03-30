<?php
/*	prs_leave_approval.php //
	Tabel utk men setting TAD/Karyawan yang bisa meng approve cuti TAD */

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cHELP_FILE = 'Doc/Tabel - Persetujuan Cuti.pdf';
$cAPP_CODE 	= $_SESSION['data_FILTER_CODE']; 
$cUSERCODE 	= $_SESSION['gUSERCODE'];
$cHEADER 	= S_MSG('PJ01','Persetujuan Cuti');
$can_CREATE = TRUST($cUSERCODE, 'PRS_LEAVE_APP_1ADD');

$cACTION='';
if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

$cCUSTOMER	= S_MSG('RS04','Customer');
$cLOKASI	= S_MSG('PF16','Lokasi');
$cJABATAN	= S_MSG('PF13','Jabatan');

$cKETERANGAN = S_MSG('PA98','Keterangan');
$cSPV		= S_MSG('PA9S','Karyawan');

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'View');
		$qQUERY=OpenTable('PrsLeaveApp', "G.APP_CODE='$cAPP_CODE' and G.REC_ID not in ( select DEL_ID from logs_delete)");
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('CR34T3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cCUSTOMER, $cLOKASI, $cJABATAN, $cSPV, $cKETERANGAN], '', []);
						echo '<tbody>';
							while($aGROUP_APP=SYS_FETCH($qQUERY)) {
								TDETAIL([$aGROUP_APP['CUST_NAME'], $aGROUP_APP['LOKS_NAME'], $aGROUP_APP['JOB_NAME'], $aGROUP_APP['PEOPLE_NAME'], $aGROUP_APP['LEAVE_NOTE']], 
									[], '', ["<a href='?_a=".md5('upd4t3')."&_s=$aGROUP_APP[REC_ID]'>", "<a href='?_a=".md5('upd4t3')."&_s=$aGROUP_APP[REC_ID]'>", "<a href='?_a=".md5('upd4t3')."&_s=$aGROUP_APP[REC_ID]'>", "<a href='?_a=".md5('upd4t3')."&_s=$aGROUP_APP[REC_ID]'>", "<a href='?_a=".md5('upd4t3')."&_s=$aGROUP_APP[REC_ID]'>"]);
							}
						echo '/<tbody>';
					eTABLE();
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('CR34T3'):
		$qCUST=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
		$allCUST = ALL_FETCH($qCUST);
		$cADD_APP	= S_MSG('PJ02','Tambah Persetujuan');
		DEF_WINDOW($cADD_APP);
			TFORM($cADD_APP, '?_a=ADD_DB', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cCUSTOMER);
?>
					<div class="form-group">
						<div class="col-sm-8 col-md-8">
							<select name="ADD_CUST" class="select2">
								<option value=' ' ></option>
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
							<select name="ADD_LOC" class="select2-container" id="s2example-2">
								<option value=' ' ></option>
								<?php 
									$qLOKASI=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
									while($aUPD_LOKASI=SYS_FETCH($qLOKASI)){
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
							<select name="ADD_JOB" class="select2">
							<option value=' ' ></option>
								<?php
									$qQUERY=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
									while($aUPD_JOB=SYS_FETCH($qQUERY)){
										echo "<option value='$aUPD_JOB[JOB_CODE]'  >$aUPD_JOB[JOB_NAME]</option>";
									}
								?>
							</select><br><br><br><br>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php
                        LABEL([3,3,3,6], '700', 'Exclude Cuti Bersama');
						INPUT('checkbox', [1,1,1,1], '900', 'ADD_CUTI_BERSAMA', '', '', '', '', 0, '', 'fix', 'Centang jika tidak memperhitungkan cuti bersama');
					?>
					<br>

					<label class="col-sm-3 form-label-700"><?php echo $cSPV?></label>
					<div class="form-group">
						<div class="col-sm-8 col-md-8">
							<select name="ADD_SPV" class="select2">
							<option value=' ' ></option>
								<?php
									$qQUERY=OpenTable('PersonSch', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and A.PRSON_SLRY=0", '', 'B.PRSON_NAME');
									while($aADD_APP=SYS_FETCH($qQUERY)){
											echo "<option value='$aADD_APP[MASTER_CODE]'  >".$aADD_APP['PRSON_NAME'].' / '.$aADD_APP['CUST_NAME'].' / '.$aADD_APP['LOKS_NAME'].' / '.$aADD_APP['JOB_NAME']."</option>";
									}
								?>
							</select><br><br>
						</div>
					</div>
<?php
					CLEAR_FIX();
					LABEL([3,3,3,6], '700', $cKETERANGAN);
					INPUT('text', [8,8,8,8], '900', 'ADD_DESC', '', '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('upd4t3'):
		$cEDIT_SCH	= S_MSG('PJ06','Edit Persetujuan cuti');
		$cDEL_MSG	= S_MSG('F021','Benar data ini mau di hapus ?');
		$can_UPDATE = TRUST($cUSERCODE, 'PRS_LEAVE_APP_2UPD');	
		$can_DELETE = TRUST($cUSERCODE, 'PRS_LEAVE_APP_3DEL');
		$qQUERY=OpenTable('PrsLeaveApp', "G.REC_ID='$_GET[_s]' and G.APP_CODE='$cAPP_CODE' and G.REC_ID not in ( select DEL_ID from logs_delete)");
		$aUPD_LEAVE=SYS_FETCH($qQUERY);
		$qCUST=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
		$allCUST = ALL_FETCH($qCUST);
		DEF_WINDOW($cEDIT_SCH);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=del_data&_id='.$aUPD_LEAVE['REC_ID']. '" onClick="return confirm('. "'". $cDEL_MSG. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_SCH, '?_a=update&id='.$aUPD_LEAVE['REC_ID'], $aACT, $cHELP_FILE);
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

												if($aUPD_LEAVE['CUST_CODE']==$cVALUE)
													echo "<option value='$cVALUE' selected='$cVALUE'>".DECODE($cSELECT)."</option>";
												else
													echo "<option value='$cVALUE' >".DECODE($cSELECT)." </option>";
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
										if($aUPD_LEAVE['LOC_CODE']==$aUPD_LOKASI['LOKS_CODE'])
											echo "<option value='$aUPD_LOKASI[LOKS_CODE]' selected='$aUPD_LEAVE[LOC_CODE]' >$aUPD_LOKASI[LOKS_NAME]</option>";
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
										if($aUPD_LEAVE['JOB_CODE'] == $aUPD_JOB['JOB_CODE'])
											echo "<option value='$aUPD_JOB[JOB_CODE]' selected='$aUPD_LEAVE[JOB_CODE]'".">".$aUPD_JOB['JOB_NAME']."</option>";
										else
											echo "<option value='$aUPD_JOB[JOB_CODE]'  >$aUPD_JOB[JOB_NAME]</option>";
									}
								?>
							</select><br><br>
						</div>
					</div>
					<div class="clearfix"></div>

					<?php
                        LABEL([3,3,3,6], '700', 'Exclude Cuti Bersama');
						INPUT('checkbox', [1,1,1,1], '900', 'UPD_CUTI_BERSAMA', '', '', '', '', 0, '', 'fix', 'Centang jika tidak memperhitungkan cuti bersama', $aUPD_LEAVE['INC_LEAVE']);
					?>
					<br>

					<label class="col-sm-3 form-label-700"><?php echo $cSPV?></label>
					<div class="form-group">
						<div class="col-sm-8 col-md-8">
							<select name="UPD_APP" class="select2">
							<option value=' ' ></option>
								<?php
									$qQUERY=OpenTable('PersonSch', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and A.PRSON_SLRY=0", '', 'B.PRSON_NAME');
									while($aUPD_SPV=SYS_FETCH($qQUERY)){
										if($aUPD_LEAVE['PERSON_CODE'] == $aUPD_SPV['MASTER_CODE'])
											echo "<option value='$aUPD_SPV[MASTER_CODE]' selected='$aUPD_LEAVE[PERSON_CODE]'".">".$aUPD_LEAVE['PEOPLE_NAME']."</option>";
										else
											echo "<option value='$aUPD_SPV[MASTER_CODE]'  >".$aUPD_SPV['PRSON_NAME'].' / '.$aUPD_SPV['CUST_NAME'].' / '.$aUPD_SPV['LOKS_NAME'].' / '.$aUPD_SPV['JOB_NAME']."</option>";
									}
								?>
							</select><br><br>
						</div>
					</div>
					<div class="clearfix"></div>
					<label class="col-lg-3 col-md-3 col-sm-3 col-xs-4 form-label-700">Input</label>
					<div class="form-group">
						<div class="col-sm-8 col-md-8">
						<select name="UPD_IN" class="">
							<option value=' ' ></option>
<?php
							$qLEVEL=OpenTable('FeLevel', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aTB_LEVEL=SYS_FETCH($qLEVEL)){
								if($aUPD_LEAVE['IN_STATUS'] == $aTB_LEVEL['LEVEL_CODE'])
									echo "<option value='$aTB_LEVEL[LEVEL_CODE]' selected='$aUPD_LEAVE[IN_STATUS]'".">".$aTB_LEVEL['LEVEL_DESC']."</option>";
								else
									echo "<option value='$aTB_LEVEL[LEVEL_CODE]'  >$aTB_LEVEL[LEVEL_DESC]</option>";
							}
						echo '</select><br><br>';
						echo '</div>';
					echo '</div>';
					CLEAR_FIX();
?>
					<label class="col-lg-3 col-md-3 col-sm-3 col-xs-4 form-label-700">Output</label>
					<div class="form-group">
						<div class="col-sm-8 col-md-8">
						<select name="UPD_OUT" class="">
							<option value=' ' ></option>
<?php
							$qLEVEL=OpenTable('FeLevel', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aTB_LEVEL=SYS_FETCH($qLEVEL)){
								if($aUPD_LEAVE['OUT_STATUS'] == $aTB_LEVEL['LEVEL_CODE'])
									echo "<option value='$aTB_LEVEL[LEVEL_CODE]' selected='$aUPD_LEAVE[OUT_STATUS]'".">".$aTB_LEVEL['LEVEL_DESC']."</option>";
								else
									echo "<option value='$aTB_LEVEL[LEVEL_CODE]'  >$aTB_LEVEL[LEVEL_DESC]</option>";
							}
						echo '</select><br><br>';
						echo '</div>';
					echo '</div>';
					CLEAR_FIX();

					LABEL([3,3,3,6], '700', $cKETERANGAN);
					INPUT('text', [8,8,8,8], '900', 'UPD_KET', $aUPD_LEAVE['LEAVE_NOTE'], '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				TDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case 'del_data':
		RecSoftDel($_GET['_id']);
		APP_LOG_ADD($cHEADER, 'delete set leave approval: '.$_GET['_id']);
		header('location:prs_leave_approval.php');
		break;

	case 'update':
		$cID=$_GET['id'];
		$nALL_LV =(isset($_POST['UPD_CUTI_BERSAMA']) ? 1 : 0);
		RecUpdate('PrsLeaveApp', ['CUST_CODE', 'LOC_CODE', 'JOB_CODE', 'PERSON_CODE', 'LEAVE_NOTE', 'IN_STATUS', 'OUT_STATUS'], 
			[$_POST['UPD_CUST'], $_POST['UPD_LOC'], $_POST['UPD_JOB'], $_POST['UPD_APP'], $_POST['UPD_KET'], $_POST['UPD_IN'], $_POST['UPD_OUT']], "APP_CODE='$cAPP_CODE' and REC_ID='$cID'");
		APP_LOG_ADD($cHEADER, 'update : '.$cID);
		header('location:prs_leave_approval.php');
		break;

	case 'ADD_DB':
		$cADD_SPV = $_POST['ADD_SPV'];
		if($cADD_SPV==''){
			MSG_INFO(S_MSG('PJ04','Nama Persetujuan masih kosong'));
			return;
		}
		$cADD_DESC = $_POST['ADD_DESC'];
		$cCUST=($_POST['ADD_CUST']=='' ? '' : $_POST['ADD_CUST']);
		$cLOCS=($_POST['ADD_LOC']=='' ? '' : $_POST['ADD_LOC']);
		$nCBRSAMA=($_POST['ADD_CUTI_BERSAMA']=='' ? 1 : 0);
		RecCreate('PrsLeaveApp', ['CUST_CODE', 'LOC_CODE', 'JOB_CODE', 'INC_LEAVE', 'PERSON_CODE', 'LEAVE_NOTE', 'APP_CODE', 'ENTRY', 'REC_ID'], 
			[$cCUST, $_POST['ADD_LOC'], $_POST['ADD_JOB'], $nCBRSAMA, $cADD_SPV, ENCODE($cADD_DESC), $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		APP_LOG_ADD($cHEADER, 'add : '.$cADD_DESC);
		header('location:prs_leave_approval.php');
}
?>

