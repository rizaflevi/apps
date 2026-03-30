<?php
//	fa_master.php //
// TODO : excel, fa group

require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE      = $_SESSION['gUSERCODE'];
	$cHELP_FILE 	= 'Doc/Tabel - Fixed Asset.pdf';
	$cHEADER 		= S_MSG('FA10','Fixed Assets');
	$cKD_ASET		= S_MSG('FA11','Kode Assets');
	$cNM_ASET  		= S_MSG('FA12','Nama Assets');
	$cQTY 	    	= S_MSG('IS24','Jumlah');
	$cVALUE 	    = S_MSG('FA13','Nilai');
	$cTANGGAL 	    = S_MSG('FA14','Tanggal');
	$cDEPR 	        = S_MSG('FA16','Depresiasi');
	$cGROUP	        = S_MSG('F160','Group');
	$cBOOK 	        = S_MSG('FA15','Nilai Buku');
	$cEND_VAL       = S_MSG('FA17','Nilai Residu');
	$cACCOUNT       = S_MSG('FA19','Account');
	$cCOST_ACT     	= S_MSG('FA24','Account cost');
	$cACM_ACT     	= S_MSG('FA29','Akumulasi Penyusutan');
	$cTTIP_ACT      = S_MSG('FA20','Account fixed asset di neraca');
	$cTTIP_ACM      = S_MSG('FA30','Account Akumulasi penyusutan asset di neraca');
	$cTCOST_ACT     = S_MSG('FA25','Account biaya penyusutan di Rugi/Laba');
	$cADD_DESC     	= S_MSG('FA33','Keterangan');
	$cDAFTAR		= S_MSG('FA21','Daftar Assets');
	$cEDIT_TBL		= S_MSG('FA22','Edit Data Assets');

	$cSAVE_DATA		= S_MSG('F301','Save');
	$cCLOSE_DATA	= S_MSG('F302','Close');

	$cTTIP_KODE		= S_MSG('FA31','Setiap Assets harus di beri kode, supaya bisa diakses berdasarkan kode');
	$cTTIP_NAMA		= S_MSG('FA32','Nama Assets');
	$cTTIP_DESC		= S_MSG('H222','Keterangan Identitas');
	$cTTIP_RESD		= S_MSG('FA18','Nilai residu aset, yaitu nilai tidak di susutkan lagi');

	$qQUERY=OpenTable('FixedAssets', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete )", '', 'A.FA_DATE desc');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

switch($cACTION){
	default:
		$can_CREATE	= TRUST($cUSERCODE, 'FA_MASTER_1ADD');
		$can_EXCEL 	= TRUST($cUSERCODE, 'FA_MASTER_5EXCEL');
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKD_ASET, $cNM_ASET, $cVALUE, $cQTY, $cTANGGAL, $cDEPR, $cEND_VAL, $cGROUP, $cACCOUNT, $cACM_ACT, $cCOST_ACT]);
						echo '<tbody>';
							while($aREC_ASET=SYS_FETCH($qQUERY)) {
								$cHREFF="<a href='?_a=".md5('up_dat3')."&_i=".md5($aREC_ASET['REC_ID'])."'>";
								$aCOL = [$aREC_ASET['FA_CODE'], DECODE($aREC_ASET['FA_DESC']), CVR($aREC_ASET['FA_VALUE']), 
									CVR($aREC_ASET['FA_QTY']), date("d-M-Y", strtotime($aREC_ASET['FA_DATE'])), CVR($aREC_ASET['FA_AGE']), CVR($aREC_ASET['FA_END_VAL']),
									$aREC_ASET['FAG_DESC'], DECODE($aREC_ASET['ACCT_NAME']), $aREC_ASET['ACT_ACCOUNT'], $aREC_ASET['COST_ACCOUNT']];
								TDETAIL($aCOL, [0,0,1,2,0,1,1,0,0,0,0], '', [$cHREFF, $cHREFF, '', '', '', '', '', '', '', '', '']);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('cr34t3'):
		$cHEADER = S_MSG('FA23','Tambah Data Assets');
		DEF_WINDOW($cHEADER);
			TFORM($cHEADER, '?_a=tambah');
				TDIV();
					LABEL([3,3,4,6], '700', $cKD_ASET);
					INPUT('text', [3,3,3,6], '900', 'ADD_ASET_CODE', '', 'autofocus', '', '', 0, '', 'fix', $cTTIP_KODE);
					LABEL([3,3,4,6], '700', $cNM_ASET);
					INPUT('text', [8,8,6,6], '900', 'ADD_ASET_NAME', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([3,3,4,6], '700', $cQTY);
					INPUT('text', [1,1,3,6], '900', 'ADD_ASET_QTY', 1, '', 'fdecimal', 'right', 0, '', 'fix');
					LABEL([3,3,4,6], '700', $cVALUE);
					INPUT('numeric', [2,2,2,6], '900', 'ADD_ASET_VAL', 0, '', 'fdecimal', 'right', 0, '', 'fix');
					LABEL([3,3,4,6], '700', $cTANGGAL);
					// INPUT_DATE([2,2,3,6], '900', 'ADD_ASET_TGL', date('Y-m-d'), '', '', '', 0, '', 'fix');
?>
					<input type="text" class="col-lg-2 col-sm-3 col-xs-6 form-label-900" name='ADD_ASET_TGL' id="ADD_ASET_TGL" value="<?php echo date('d/m/Y')?>">
					<div class="clearfix"></div>

		<label class="col-lg-3 col-sm-4 form-label-700" for="field-1"><?php echo $cDEPR?></label>
		<input type="text" class="col-lg-1 col-sm-2 form-label-900" name='ADD_ASET_DEP' data-mask="fdecimal" data-numeric-align="right">
		<label class="col-sm-1 form-label-700" for="field-1"><?php echo 'Bulan' ?></label>
		<div class="clearfix"></div>

		<label class="col-lg-3 col-sm-4 form-label-700" for="field-1"><?php echo $cGROUP?></label>
		<select name="ADD_ASSET_GROUP" class="col-sm-5 form-label-900">
			<?php
				echo "<option value=' '  ></option>";
				$qCALC=OpenTable('FaGroup', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
				while($aREC_CALC=SYS_FETCH($qCALC)){
					echo "<option value='$aREC_CALC[FAG_CODE]'  >$aREC_CALC[FAG_DESC]</option>";
				}
			?>
		</select>
		<div class="clearfix"></div><br>

		<label class="col-lg-3 col-sm-4 form-label-700" for="field-1"><?php echo $cEND_VAL?></label>
		<input type="text" class="col-lg-2 col-sm-3 form-label-900" name="ADD_RESD_VAL" data-mask="fdecimal" data-numeric-align="right" title="<?php echo $cTTIP_RESD?>">
		<div class="clearfix"></div>

		<label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" ><?php echo $cACCOUNT?></label>
		<select name="ADD_ACCOUNT" class="col-lg-3 select2-container" id="s2example-1" title="<?php echo $cTTIP_ACT?>">
		<option value=' ' > </<option>
			<?php 
				$REC_DATA=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
				while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
					echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
				}
			?>
		</select><br>
		<div class="clearfix"></div><br>

		<label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" ><?php echo $cACM_ACT?></label>
		<select name="ADD_ACM_ACT" class="col-lg-3 select2-container select2" title="<?php echo $cTTIP_ACM?>">
		<option value=' ' > </<option>
			<?php 
				$REC_DATA=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
				while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
					echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
				}
			?>
		</select><br>
		<div class="clearfix"></div><br>

		<label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" ><?php echo $cCOST_ACT?></label>
		<select name="ADD_COST_ACCOUNT" class="col-lg-9 select2-container" id="s2example-2" title="<?php echo $cTCOST_ACT?>">
		<option value=' ' > </<option>
			<?php 
				$REC_DATA=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
				while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
					echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
				}
			?>
		</select><br>
<?php
					CLEAR_FIX();
					LABEL([3,3,4,6], '700', $cADD_DESC);
					INPUT('text', [8,8,8,6], '900', 'ADD_ADD_DESC', '', '', '', '', 0, '', 'fix');
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('up_dat3'):
		$can_DELETE = TRUST($cUSERCODE, 'FA_MASTER_3DEL');
		$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');
		$qQUERY=OpenTable('FixedAssets', "A.APP_CODE='$cAPP_CODE' and md5(A.REC_ID)='$_GET[_i]' and A.REC_ID not in ( select DEL_ID from logs_delete )");
		$REC_UPD_ASET=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=DEL_ASET&_id='.$REC_UPD_ASET['REC_ID']. '" onClick="return confirm('. "'". $cMSG_DEL. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&id='.$REC_UPD_ASET['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,4,6], '700', $cKD_ASET);
					INPUT('text', [3,3,3,6], '900', 'EDIT_ASET_CODE', $REC_UPD_ASET['FA_CODE'], '', '', '', 0, 'disable', 'fix', $cTTIP_KODE);
					LABEL([3,3,4,6], '700', $cNM_ASET);
					INPUT('text', [6,6,6,6], '900', 'EDIT_ASET_NAME', DECODE($REC_UPD_ASET['FA_DESC']), 'focus', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([3,3,3,6], '700', $cQTY);
					INPUT('numeric', [1,1,3,6], '900', 'UPD_ASET_QTY', $REC_UPD_ASET['FA_QTY'], '', 'fdecimal', 'right', 0, '', 'fix');
					LABEL([3,3,4,6], '700', $cVALUE);
					INPUT('numeric', [2,2,3,6], '900', 'UPD_ASET_VAL', $REC_UPD_ASET['FA_VALUE'], '', 'fdecimal', 'right', 0, '', 'fix');
					LABEL([3,3,4,6], '700', $cTANGGAL);
					// INPUT_DATE([2,3,3,6], '900', 'EDIT_ASET_TGL', $REC_UPD_ASET['FA_DATE'], '', '', '', 0, '', 'fix');
?>
					<input type="date" class="col-lg-2 col-sm-3 col-xs-6 form-label-900" name='EDIT_ASET_TGL' value="<?php echo $REC_UPD_ASET['FA_DATE']?>">
					<div class="clearfix"></div>

					<label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-1"><?php echo $cDEPR?></label>
					<input type="numeric" class="col-lg-1 col-sm-3 col-xs-6 form-label-900" name='EDIT_ASET_DEP' data-mask="fdecimal" data-numeric-align="right" value="<?php echo $REC_UPD_ASET['FA_AGE']?>">
					<div class="clearfix"></div><br>

					<label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" ><?php echo $cGROUP?></label>
					<select name='UPD_GROP' class="col-lg-5 col-sm-4 col-xs-6 form-label-900 m-bot15">
						<?php
							echo "<option value=' '  > </option>";
							$qCALC=OpenTable('FaGroup', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_CALC=SYS_FETCH($qCALC)){
								if($aREC_CALC['FAG_CODE']==$REC_UPD_ASET['FA_GROUP']){
									echo "<option value='$REC_UPD_ASET[FA_GROUP]' selected='$aREC_CALC[FAG_CODE]' >$aREC_CALC[FAG_DESC]</option>";
								} else
								echo "<option value='$aREC_CALC[FAG_CODE]'  >$aREC_CALC[FAG_DESC]</option>";
							}
						?>
					</select>
					<div class="clearfix"></div><br>

					<label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-1"><?php echo $cEND_VAL?></label>
					<input type="text" class="col-lg-2 col-sm-4 col-xs-6 form-label-900" name='UPD_END_VAL' id="field-2" value="<?php echo $REC_UPD_ASET['FA_END_VAL']?>" data-mask="fdecimal" data-numeric-align="right">
					<div class="clearfix"></div><br>

					<div class="form-group">
						<label class="col-lg-3 col-sm-4 col-xs-12 form-label-700" ><?php echo $cACCOUNT?></label>
						<div class="col-sm-6 col-md-6">
							<select name="UPD_ACCOUNT" class="col-lg-3 col-xs-12 select2" id="s2example-1" title="<?php echo $cTTIP_ACT?>">
							<option value=' ' > </<option>
								<?php 
									$REC_DATA=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
									while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
										if($aREC_ACCOUNT['ACCOUNT_NO']==$REC_UPD_ASET['FA_ASSET_ACT']){
											echo "<option value='$REC_UPD_ASET[FA_ASSET_ACT]' selected='$REC_UPD_ASET[FA_ASSET_ACT]' >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
										} else
										echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
									}
								?>
							</select><br>
						</div>
					</div>
					<div class="clearfix"></div><br>

					<div class="form-group">
						<label class="col-lg-3 col-sm-4 col-xs-12 form-label-700" ><?php echo $cACM_ACT?></label>
						<div class="col-sm-6 col-md-6">
							<select name="UPD_ACM_ACCT" class="col-lg-9 col-sm-8 col-xs-12 select2" title="<?php echo $cTTIP_ACT?>">
							<option value=' ' > </<option>
								<?php 
									$REC_DATA=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
									while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
										if($aREC_ACCOUNT['ACCOUNT_NO']==$REC_UPD_ASET['FA_ACM_ACT']){
											echo "<option value='$REC_UPD_ASET[FA_ACM_ACT]' selected='$REC_UPD_ASET[FA_ACM_ACT]' >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
										} else
										echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
									}
								?>
							</select><br>
						</div>
					</div>
					<div class="clearfix"></div><br>

					<div class="form-group">
						<label class="col-lg-3 col-sm-4 col-xs-12 form-label-700" ><?php echo $cCOST_ACT?></label>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<select name="UPD_COST_ACCOUNT" class="col-lg-9 col-xs-12 select2-container" id="s2example-2" title="<?php echo $cTCOST_ACT?>">
							<option value=' ' > </<option>
								<?php 
									$REC_DATA=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
									while($aREC_ACCOUNT=SYS_FETCH($REC_DATA)){
										if($aREC_ACCOUNT['ACCOUNT_NO']==$REC_UPD_ASET['FA_COST_ACT']){
											echo "<option value='$REC_UPD_ASET[FA_COST_ACT]' selected='$REC_UPD_ASET[FA_COST_ACT]' >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
										} else
										echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
									}
								?>
							</select><br>
						</div>
					</div>
					<div class="clearfix"></div><br>
<?php
					LABEL([3,3,4,6], '700', $cADD_DESC);
					INPUT('text', [6,6,6,6], '900', 'UPD_ADD_DESC', DECODE($REC_UPD_ASET['FA_ADD_DESC']), '', '', '', 0, '', 'fix');
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;
case 'tambah':
	$cCODE = $_POST['ADD_ASET_CODE'];
	if($cCODE==''){
		MSG_INFO(S_MSG('FA26','Kode Aset belum diisi'));
		return;
	}
	$qQUERY=OpenTable('Fixed_Assets', "APP_CODE='$cAPP_CODE' and FA_CODE='$cCODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	if(SYS_ROWS($qQUERY)>0){
		MSG_INFO(S_MSG('FA27','Kode Aset sudah ada'));
		header('location:fa_master.php');
		return;
	} else {
		$cFA_CODE = encode_string($cCODE);
		$cASET_NAME = encode_string($_POST['ADD_ASET_NAME']);
		$cASET_QTY = str_replace(',', '', $_POST['ADD_ASET_QTY']);
		$cASET_QTY = str_replace('.', '', $cASET_QTY);
		$cASET_VAL = str_replace(',', '', $_POST['ADD_ASET_VAL']);
		$cASET_VAL = str_replace('.', '', $cASET_VAL);
		$cASET_TGL = $_POST['ADD_ASET_TGL'];
		$cASET_DEP = str_replace(',', '', $_POST['ADD_ASET_DEP']);
		$cRESIDU = str_replace(',', '', $_POST['ADD_RESD_VAL']);
		$cRESIDU = str_replace('.', '', $cRESIDU);
		
		RecCreate('FixedAssets', ['REC_ID', 'FA_CODE', 'FA_DESC', 'FA_QTY', 'FA_VALUE', 'FA_DATE', 'FA_AGE', 'FA_END_VAL', 'FA_GROUP', 'FA_ASSET_ACT', 'FA_ACM_ACT', 'FA_COST_ACT', 'FA_ADD_DESC', 'APP_CODE', 'ENTRY'],
			[NowMSecs(), $cFA_CODE, $cASET_NAME, $cASET_QTY, $cASET_VAL, $cASET_TGL, $cASET_DEP, $cRESIDU, $_POST['ADD_ASSET_GROUP'], $_POST['ADD_ACCOUNT'], $_POST['ADD_ACM_ACT'], $_POST['ADD_COST_ACCOUNT'], $_POST['ADD_ADD_DESC'], $cAPP_CODE, $cUSERCODE]);
	}
	header('location:fa_master.php');
	break;
case 'rubah':
	$cID=$_GET['id'];
	$cASET_NAME = encode_string($_POST['EDIT_ASET_NAME']);
	$cASET_VAL = str_replace(',', '', $_POST['UPD_ASET_VAL']);
	$cASET_VAL = str_replace('.', '', $cASET_VAL);
	$cASET_EVAL = str_replace(',', '', $_POST['UPD_END_VAL']);
	$cASET_EVAL = str_replace('.', '', $cASET_EVAL);
	$dTG_ASET = $_POST['EDIT_ASET_TGL'];		// 'dd/mm/yyyy'
	$cASET_DEP = "0".str_replace(',', '', $_POST['EDIT_ASET_DEP']);
	$cDATE = substr($dTG_ASET,0,4). '-'. substr($dTG_ASET,5,2). '-'. substr($dTG_ASET,8,2);
	$cASET_QTY = str_replace('.', '', $_POST['UPD_ASET_QTY']);
	RecUpdate('FixedAssets', ['FA_DESC', 'FA_QTY', 'FA_VALUE', 'FA_DATE', 'FA_AGE', 'FA_END_VAL', 'FA_GROUP', 'FA_ASSET_ACT', 'FA_ACM_ACT', 'FA_COST_ACT', 'FA_ADD_DESC'], 
		[$cASET_NAME, $cASET_QTY, $cASET_VAL, $cDATE, $cASET_DEP, $cASET_EVAL, $_POST['UPD_GROP'], $_POST['UPD_ACCOUNT'], $_POST['UPD_ACM_ACCT'], $_POST['UPD_COST_ACCOUNT'], $_POST['UPD_ADD_DESC']], "REC_ID='$cID'");
	header('location:fa_master.php');
	break;
case 'DEL_ASET':
	RecSoftDel($_GET['_id']);
	header('location:fa_master.php');
	break;

case md5('to_excel'):
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	
	$cFONT_COM = 'Arial';   $nSIZE_COM = 12;  $cBOLD_COM=' '; $cITAL_COM=' '; $cUNDRL_COM=' ';
	$qFONT= OpenTable('TbFont', "KEY_ID='FA_HEAD' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	if($aFONT = SYS_FETCH($qFONT)) {
		$cFONT_COM = $aFONT['NAME'];
		if($aFONT['BOLD']==1)	$cBOLD_COM='B';
		if($aFONT['ITALIC']==1)	$cITAL_COM='I';
		if($aFONT['UNDERLINE']==1)	$cUNDRL_COM='U';
		$nSIZE_COM = intval($aFONT['SIZE']);
	}
    $sheet->getStyle('A1:C3')->getFont()->setName($cFONT_COM);
    $sheet->getStyle('A1:C3')->getFont()->setSize($nSIZE_COM);
    if($cBOLD_COM=='B') $sheet->getStyle('A1')->getFont()->setBold(true);
    if($cITAL_COM=='I') $sheet->getStyle('A1')->getFont()->setItalic(true);
    if($cUNDRL_COM=='U') $sheet->getStyle('A1')->getFont()->setUnderline(true);

    $sheet->setCellValue('A1', S_PARA('CO1','Rainbow Co.'));
    $sheet->setCellValue('A2', S_PARA('CO2','Rainbow Co.'));
    $sheet->setCellValue('C3', $cHEADER);
    $sheet->setCellValue('A5', $cKD_ASET);
    $sheet->setCellValue('B5', $cNM_ASET);
    $sheet->setCellValue('C5', $cVALUE);
    $sheet->setCellValue('D5', $cQTY);
    $sheet->setCellValue('E5', $cTANGGAL);
    $sheet->setCellValue('E5', $cDEPR);

	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="FixedAssets.xlsx"');
	header('Cache-Control: max-age=0');
	
	// (E) OUTPUT
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
	exit($writer->save('php://output'));
	echo "<script> alert('OK');	window.history.back();	</script>";
	
	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Fxed Assets to excel : ');
	header('location:tb_account.php');
}
?>

<script type="text/javascript">
        $(function () {
            $("#ADD_ASET_TGL").datepicker({
                dateFormat: 'dd/mm/yy'
            });
        });
    </script>
