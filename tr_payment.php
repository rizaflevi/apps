<?php
//	tr_payment.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE']; $cUSERCODE = $_SESSION['gUSERCODE'];
	$can_CREATE = TRUST($cUSERCODE, 'TR_PAYMENT_1ADD');
	$cHELP_FILE = 'Doc/Transaksi - Pembayaran.pdf';
	$sPERIOD1 = $_SESSION['sCURRENT_PERIOD'];
	if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];

	$qMAIN_MENU=OpenTable('Main_Menu', "APP_CODE='$cAPP_CODE' and link='sch_revenue.php'");
	$l_SCHOOL = SYS_ROWS($qMAIN_MENU);
	if($l_SCHOOL>0)	$cHELP_FILE = 'Doc/School - Transaksi - Pembayaran.pdf';

	$cHEADER = S_MSG('NP01','Pembayaran');
	$sPICT_BDV=S_PARA('PICT_BDV', '999999');
	$cACTION =(isset($_GET['_a']) ?	$_GET['_a'] : '');

	$nPURCHASE 		= 0;
	$nMANUAL 		= (S_PARA('PAYMENT_NUM_MANUAL', '')=='1' ? 1 : 0);
	$cADD_PAY 		= S_MSG('NP06','Tambah');
	$cADD_PAY_DTL 	= S_MSG('NP08','Tambah Detil Pembayaran');
	$cEDIT_DTL_JRN 	= S_MSG('NP09','Edit Detil Pembayaran');

	$cKD_BYR		= S_MSG('NP11','No. Pembayaran');
	$cTANGGAL		= S_MSG('NP12','Tanggal');
	$cNIL_TRN		= S_MSG('NP36','Nilai');
	$cKD_ACCOUNT 	= S_MSG('TA21','Kode Account');
	$cACCOUNT		= S_MSG('F028','Account');
	$cKETERANGAN 	= S_MSG('NJ53','Keterangan');
	$cMESSAG1		= S_MSG('F021','Benar data ini mau di hapus ?');
	$cBANK_NAME		= S_MSG('F131','Nama Bank');
	$cNOMOR_CEK		= S_MSG('NP16','Nomor Cek/Giro');
	$cDUE_DATE		= S_MSG('NP10','Jatuh Tempo');
	
	$cSAVE_DATA		= S_MSG('F301','Save');
	$cCLOSE_DATA	= S_MSG('F302','Close');
	$l_DUEDATE 		= (S_PARA('PAYMENT_NO_DUE_DATE')=='1' ? 0 : 1);
	
	$qPAYMENT = OpenTable('TrPaymentHdr', "left(A.BDV_DATE,7)='".substr($sPERIOD1,0,7)."' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
	
switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'list');
		DEF_WINDOW($cHEADER, '', 'prd');
		$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. $cADD_PAY.'</a>'] : []);
		TFORM($cHEADER, '', $aACT, $cHELP_FILE);
			TDIV();
				TABLE('example');
					THEAD([$cKD_BYR, $cTANGGAL, $cKETERANGAN, $cNIL_TRN]);
					echo '<tbody>';
						$nTOTAL = 0;
						while($aREC_BAYAR1=SYS_FETCH($qPAYMENT)) {
							echo '<tr>';
								$cICON = 'fa-money';
								if(trim($aREC_BAYAR1['BDV_BANK'])!='') {
									$cICON = 'fa-bank';
								}
								echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
								echo "<td><span><a href='?_a=".md5('upd4t3')."&_r=".md5($aREC_BAYAR1['BDV_NO'])."'>". $aREC_BAYAR1['BDV_NO']."</a></span></td>";
								echo '<td>'.date("d-M-Y", strtotime($aREC_BAYAR1['BDV_DATE'])).'</td>';
								echo '<td>'.DECODE($aREC_BAYAR1['BDV_DESC']).'</td>';
								$nAMOUNT = 0;
								$dQUERY = OpenTable('TrPaymentDtl', "A.BDV_NO='$aREC_BAYAR1[BDV_NO]' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
								while($aREC_PAYMENT=SYS_FETCH($dQUERY)) {
									$nAMOUNT 	+= $aREC_PAYMENT['BDV_DAM'];
								}
								echo '<td align="right">'.CVR($nAMOUNT).'</td>';
								$nTOTAL += $nAMOUNT;
							echo '</tr>';
						}
					echo '</tbody>';
					TTOTAL(['Total', '', '', CVR($nTOTAL)], [0,0,0,1]);
				eTABLE();
			eTDIV();
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

case md5('cr34t3'):
	$cMONTH_FILT= (S_PARA('RESET_NUM_PAYMENT_MONTHLY', '')=='1' ? " and left(A.BDV_DATE,7)='".substr($sPERIOD1,0,7)."'" : '');
	$qQ_LAST = OpenTable('TrPaymentHdr', "A.DELETOR='' and A.APP_CODE='$cAPP_CODE'".$cMONTH_FILT, '', 'A.BDV_NO desc limit 1');
	$aREC_BAYAR1= SYS_FETCH($qQ_LAST);
	$cLAST_NOM	= (SYS_ROWS($qQ_LAST)>0 ? $aREC_BAYAR1['BDV_NO'] : 0);
	$nLAST_NOM=intval($cLAST_NOM)+1;
	$cLAST_NOM=str_pad((string)$nLAST_NOM, strlen($sPICT_BDV), '0', STR_PAD_LEFT);
	$dLAST_DATE=date('t/m/Y', strtotime($sPERIOD1));
	DEF_WINDOW($cADD_PAY, '', 'prd');
		TFORM($cADD_PAY, '?_a='.md5('tambah'), [], $cHELP_FILE, '*');
			TDIV();
                LABEL([4,4,4,6], '700', $cKD_BYR);
                INPUT('text', [3,3,3,6], '900', 'ADD_BDV_NO', $cLAST_NOM, '', '', '', 0, ($nMANUAL==1 ? '' : 'readonly'), 'Fix');
                LABEL([4,4,4,6], '700', $cTANGGAL);
				INP_DATE([3,3,3,6], '', 'ADD_BDV_DATE', $dLAST_DATE, '', '', '', 'fix');
                LABEL([4,4,4,6], '700', $cKETERANGAN);
                INPUT('text', [6,6,6,6], '900', 'ADD_BDV_DESC', '', '', '', '', 0, '', 'Fix');
                LABEL([4,4,4,6], '700', $cBANK_NAME);
				SELECT([4,4,4,6], 'ADD_BDV_BANK', '', 'SelectUpdAccount');
						echo "<option value=' '  >Cash</option>";
						$REC_DATA=OpenTable('TbBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_BANK=SYS_FETCH($REC_DATA)){
							echo "<option value='$aREC_BANK[B_CODE]'  >".DECODE($aREC_BANK['B_NAME'])."</option>";
						}
				echo '</select>';
				CLEAR_FIX();
?>
					<div id="NUM_CEK" class="form-group">
						<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNOMOR_CEK?></label>
						<input type="text" class="col-sm-6 form-label-900" name='ADD_BDV_CQ' id="field-2">
						<?php
							if($l_DUEDATE)	{
								echo '<label class="col-sm-4 form-label-700" for="field-1">'.$cDUE_DATE.'</label>
									<input type="text" class="col-sm-3 form-label-900" data-mask="date" name="ADD_BDV_DD" id="field-2" value='.date("d-m-Y").'>';
							}
						?>
						
					</div>
					<div class="clearfix"></div>

					<table id="example" class="display table table-hover table-condensed" cellspacing="0">
						<thead>
							<tr>
								<th class="col-lg-5 col-sm-5 col-xs-5" style="background-color:LightGray;"><?php echo $cKETERANGAN?></th>
								<th class="col-lg-5 col-sm-5 col-xs-5" style="background-color:LightGray;"><?php echo $cACCOUNT?></th>
								<th class="col-lg-2 col-sm-2 col-xs-2" style="background-color:LightGray;"><?php echo $cNIL_TRN?></th>
							</tr>
						</thead>
						<tbody>
						<?php 
							for ($I=1; $I < 5; $I++) {
								$cIDX 	= (string)$I;
								echo '<tr>
									<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name="ADD_DTL_'.$cIDX.'" id="field-2"></td>
									<td><div class="form-group">
										<select name="ADD_ACCOUNT_'.$cIDX.'" class="col-lg-12 col-sm-12 form-label-900 select2">';
											echo '<option> </option>';
											$REC_DATA=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
											while($aREC_DETAIL=SYS_FETCH($REC_DATA)){
												echo "<option value='$aREC_DETAIL[ACCOUNT_NO]'  >".DECODE($aREC_DETAIL['ACCT_NAME'])."</option>";
											}
										echo '</select>
									</div></td>
									<td><input type="text" class="col-lg-12 col-sm-12 form-label-900" name="ADD_AMOUNT_'.$cIDX.'" data-mask="fdecimal" data-numeric-align="right" value=0></td>
								</tr>';
							}
						?>
						</tbody>
					</table>

				<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div><br>
<?php
				SAVE($cSAVE_DATA);
			eTDIV();
		eTFORM();
		include "scr_chat.php";
		require_once("js_framework.php");
	END2WINDOW();
	SYS_DB_CLOSE($DB2);	
	break;

case md5('upd4t3'):
	$cEDIT_PAY 		= S_MSG('NP07','Edit Pembayaran');
	$can_UPDATE = TRUST($cUSERCODE, 'TR_PAYMENT_2UPD');
	$can_DELETE = TRUST($cUSERCODE, 'TR_PAYMENT_3DEL');
	$can_PRINT = TRUST($cUSERCODE, 'TR_PAYMENT_4PRINT');
	$xBDV_NO	= $_GET['_r'];

	$qPAYMENT = OpenTable('TrPaymentHdr', "md5(A.BDV_NO)='$xBDV_NO' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
	$aREC_BAYAR1=SYS_FETCH($qPAYMENT);
	$cBDV_NO = $aREC_BAYAR1['BDV_NO'];
	$cBDV_CEK = '';		$cBDV_DD=date('Y/m/d');
	$qPAYBANK = OpenTable('TrPaymentBank', "BDV_NO='$cBDV_NO' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$aREC_BANK=SYS_FETCH($qPAYBANK);
	if(SYS_ROWS($qPAYBANK)>0)	{
		$cBDV_CEK = $aREC_BANK['BDV_CQ'];		
		$cBDV_DD=$aREC_BANK['BDV_DD'];
	}
	$UPD_ACCOUNT = '1';
	DEF_WINDOW($cEDIT_PAY);
		$aACT=[];
		if ($can_DELETE==1) {
			array_push($aACT, '<a href="?_a='.md5('d3l_p4y'). '&_c='.$cBDV_NO. '" onClick="return confirm('. "'". $cMESSAG1. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>'.S_MSG('F304','Delete').'</a>');
		}
		if ($can_PRINT==1) {
			array_push($aACT, '<a href="?_a='. md5('pay_print'). '&_c='.$cBDV_NO.'"  title="print this payment"><i class="glyphicon glyphicon-print"></i>Print</a>'); 
		}
		TFORM($cEDIT_PAY, '?_a=ru_bah&_r='.$cBDV_NO, $aACT, $cHELP_FILE);
			TDIV();
                LABEL([4,4,4,6], '700', $cKD_BYR);
                INPUT('text', [3,3,3,6], '900', 'EDIT_BDV_NO', $aREC_BAYAR1['BDV_NO'], '', '', '', 0, 'disabled', 'Fix');
                LABEL([4,4,4,6], '700', $cTANGGAL);
				INP_DATE([2,2,3,6], '', 'EDIT_BDV_DATE', date("d/m/Y", strtotime($aREC_BAYAR1['BDV_DATE'])), '', '', '', 'fix');
                LABEL([4,4,4,6], '700', $cKETERANGAN);
                INPUT('text', [6,6,6,6], '900', 'EDIT_BDV_DESC', DECODE($aREC_BAYAR1['BDV_DESC']), '', '', '', 0, '', 'Fix');
                LABEL([4,4,4,6], '700', $cBANK_NAME);
				SELECT([6,6,6,6], 'UPD_BDV_BANK', '', 'SelectUpdAccount');
					echo "<option value=' '  >Cash</option>";
					$qBANK=OpenTable('TbBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					while($aREC_BANK=SYS_FETCH($qBANK)){
						if($aREC_BANK['B_CODE']==$aREC_BAYAR1['BDV_BANK']){
							echo "<option value='$aREC_BAYAR1[BDV_BANK]' selected='$aREC_BAYAR1[BDV_BANK]' >".DECODE($aREC_BANK['B_NAME'])."</option>";
						} else
						echo "<option value='$aREC_BANK[B_CODE]'  >".DECODE($aREC_BANK['B_NAME'])."</option>";
					}
				echo '</select><br>';
				CLEAR_FIX();
				echo '<div id="NUM_CEK" class="form-group">';
					LABEL([4,4,4,6], '700', $cNOMOR_CEK);
					INPUT('text', [6,6,6,6], '900', 'UPD_BDV_CQ', $cBDV_CEK, '', '', '', 0, '', 'Fix');
					if($l_DUEDATE)	{
						echo '<label class="col-sm-4 form-label-700" for="field-1">'.$cDUE_DATE.'</label>
						<input type="text" class="col-sm-3 form-label-900" data-mask="date" name="EDIT_BDV_DD" id="field-2" value='. date("d-m-Y", strtotime($cBDV_DD)).'>';
					}
				eTDIV();
				CLEAR_FIX();
				TDIV();
					TABLE('example');
					// THEAD([$cKETERANGAN, $cACCOUNT, $cNIL_TRN], '', [0,0,1], [5,4,2]);
				?>
					<thead>
						<tr>
							<th class="col-lg-5 col-md-4 col-sm-5 col-xs-5" style="background-color:LightGray;"><?php echo $cKETERANGAN?></th>
							<th class="col-lg-5 col-md-4 col-sm-5 col-xs-5" style="background-color:LightGray;"><?php echo $cACCOUNT?></th>
							<th class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="background-color:LightGray;text-align:right"><?php echo $cNIL_TRN?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$q_BAYAR3 = OpenTable('TrPaymentDtl', "A.BDV_NO='$cBDV_NO' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
							$nTOTAL = 0;
							while($r_BAYAR3=SYS_FETCH($q_BAYAR3)) {
								echo '<tr>';
									echo "<td><span><a href='?_a=".md5('edit_detail_trans')."&_r=$r_BAYAR3[BDV3_REC_N]'>". $r_BAYAR3['BDV_DESC'].'</a></span></td>';
									echo "<td><span><a href='?_a=".md5('edit_detail_trans')."&_r=$r_BAYAR3[BDV3_REC_N]'>". DECODE($r_BAYAR3['ACCT_NAME']).'</a></span></td>';
									echo '<td align="right">'.CVR($r_BAYAR3['BDV_DAM']).'</td>';
								echo '</tr>';
								$nTOTAL += $r_BAYAR3['BDV_DAM'];
							}
						?>
						<tr>
							<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='NEW_DTL1' id="field-2"></td>
							<td><div class="form-group">
								<select name="NEW_ACCOUNT1" class="col-lg-12 col-sm-12 form-label-900 select2">
								<option></<option>
								<?php 
									$REC_DATA=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
									while($aREC_DETAIL=SYS_FETCH($REC_DATA)){
										echo "<option value='$aREC_DETAIL[ACCOUNT_NO]'  >".DECODE($aREC_DETAIL['ACCT_NAME'])."</option>";
									}
								?>
								</select>
							</div></td>
							<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='NEW_AMOUNT1' id="field-3" data-mask="fdecimal" data-numeric-align="right" value=0></td>
						</tr>
						<tr></tr>
						<tr>
								<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
								<td style="font-size: 24px;color: Brown;background-color: LightGray ;">Total</td>
								<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo CVR($nTOTAL)?></td>
						</tr>
								<td></td><td></td><td></td>
						<tr>
						</tr>
					</tbody>
<?php
						eTABLE();
					TDIV();
					CLEAR_FIX();
					SAVE($can_UPDATE==1 ? $cSAVE_DATA : '');
				TDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END2WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('edit_detail_trans'):
		$cHEADER 		= S_MSG('NP07','Edit Pembayaran');
		$rDTL = $_GET['_r'];
		$qQUERY = OpenTable('TrPaymentDtl', "BDV3_REC_N=$rDTL");
		$aREC_DETAIL=SYS_FETCH($qQUERY);
		DEF_WINDOW($cHEADER);
			$aACT=[];
			$can_DELETE 	= TRUST($cUSERCODE, 'TR_PAYMENT_3DEL');
			if ($can_DELETE==1) {
				array_push($aACT, '<a href="?_a='.md5('upd_del_dtl').'&_r='.$aREC_DETAIL['BDV3_REC_N']. '" onClick="return confirm('. "'". $cMESSAG1. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>'.S_MSG('F304','Delete').'</a>');
			}
			TFORM($cHEADER, '?_a=upd_upd_dtl&DTL_RECN='.$rDTL, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,4], '700', $cACCOUNT);
					TDIV(6,6,6,6);
						SELECT([4,4,4,4], 'UPD_UPD_ACCOUNT_NO', '', '', 'select2');
							echo "<option value=' '  > </option>";
							$REC_ACCT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_ACCOUNT=SYS_FETCH($REC_ACCT)){
								if($aREC_ACCOUNT['ACCOUNT_NO']==$aREC_DETAIL['BDV_REFF']){
									echo "<option value='$aREC_DETAIL[BDV_REFF]' selected='$aREC_DETAIL[BDV_REFF]' >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
								} else
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
							}
						echo '</select>';
						eTDIV();
					CLEAR_FIX();
					LABEL([4,4,4,4], '700', $cKETERANGAN);
					INPUT('text', [7,7,7,6], '900', 'UPD_UPD_BDV_DESC', $aREC_DETAIL['BDV_DESC'], '', '', '', 0, '', 'fix');
					LABEL([4,4,4,4], '700', $cNIL_TRN);
					INPUT('number', [2,2,2,6], '900', 'UPD_UPD_VALUE', $aREC_DETAIL['BDV_DAM'], '', '', 'right', 0, '', 'fix');
           			SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END2WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

case md5('tambah'):
	$NOW = date("Y-m-d H:i:s");
	$dTG_BAYAR = $_POST['ADD_BDV_DATE'];		// 'dd/mm/yyyy'
	$cDATE = substr($dTG_BAYAR,6,4). '-'. substr($dTG_BAYAR,3,2). '-'. substr($dTG_BAYAR,0,2);
	$dDUE_DATE = $_POST['ADD_BDV_DD'];		// 'dd/mm/yyyy'
	if($l_DUEDATE){
		$dCEK_DATE 	= $dTG_BAYAR;
	} else {
		$dCEK_DATE 	= (isset($_POST['ADD_BDV_DD']) ? $_POST['ADD_BDV_DD'] : $dTG_BAYAR);		// 'dd/mm/yyyy'
	}
	
	$cDUE_DATE = substr($dCEK_DATE,6,4). '-'. substr($dCEK_DATE,3,2). '-'. substr($dCEK_DATE,0,2);
	$cBDV_NO = $_POST['ADD_BDV_NO'];
	if($cBDV_NO==''){
		MSG_INFO(S_MSG('NP46','Nomor Pembayaran masih kosong'));
		return;
	}
	if($_POST['ADD_BDV_DATE']==''){
		MSG_INFO(S_MSG('NP47','Tanggal Pembayaran masih kosong'));
		return;
	}
	$qQUERY = OpenTable('TrPaymentHdr', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and BDV_NO='$cBDV_NO'");
	if($qQUERY){
		if(SYS_ROWS($qQUERY)>0){
			MSG_INFO(S_MSG('NP48','Nomor Pembayaran sudah ada'));
			return;
		}
	}
	RecCreate('TrPaymentHdr', ['BDV_NO', 'BDV_DATE', 'BDV_DESC', 'BDV_BANK', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], 
		[$cBDV_NO, $cDATE, $_POST['ADD_BDV_DESC'], $_POST['ADD_BDV_BANK'], $cUSERCODE, $NOW, $cAPP_CODE]);

	for ($I=1; $I < 5; $I++) {
		$cIDX = (string)$I;
		$nVALUE 	= str_replace(',', '', $_POST['ADD_AMOUNT_'.$cIDX]);
		if($nVALUE>0)	{
			RecCreate('TrPaymentDtl', ['BDV_NO', 'BDV_REFF', 'BDV_DESC', 'BDV_DAM', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], 
			[$cBDV_NO, $_POST['ADD_ACCOUNT_'.$cIDX], $_POST['ADD_DTL_'.$cIDX], $nVALUE, $cUSERCODE, $NOW, $cAPP_CODE]);
		}
	}
	$cBANK	= (isset($_POST['ADD_BDV_BANK']) ? $_POST['ADD_BDV_BANK'] : '');
	$c_CEK	= (isset($_POST['ADD_BDV_CQ']) ? $_POST['ADD_BDV_CQ'] : '');
	if($cBANK>'') {
		$qQUERY = OpenTable('TrPaymentBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and BDV_NO='$cBDV_NO'");
		if(SYS_ROWS($qQUERY)>0){
			RecUpdate('TrPaymentBank', ['BDV_DD', 'C_NO'], [$cDUE_DATE, $c_CEK], "REC_ID='$aREC_BANK[REC_ID]'");
		}	else {
			$lCREATE=RecCreate('TrPaymentBank', ['BDV_NO', 'BDV_CQ', 'BDV_DD', 'ENTRY', 'REC_ID', 'APP_CODE'], 
				[$cBDV_NO, $c_CEK, $cDUE_DATE, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		}
	}
APP_LOG_ADD($cHEADER, 'add : '.$cHEADER.' : '.$cBDV_NO);
	header('location:tr_payment.php');
	break;

case 'ru_bah':
	$NOW = date("Y-m-d H:i:s");
	$cBDV_NO=$_GET['_r'];
	$dTG_BAYAR = $_POST['EDIT_BDV_DATE'];		// 'dd/mm/yyyy'
	$cDATE = DMY_YMD($_POST['EDIT_BDV_DATE']);
	if($l_DUEDATE)	{
		$dDUE_DATE = $_POST['EDIT_BDV_DD'];		// 'dd/mm/yyyy'
		$cDUE_DATE = substr($dDUE_DATE,6,4). '-'. substr($dDUE_DATE,3,2). '-'. substr($dDUE_DATE,0,2);
	} else {
		$cDUE_DATE = $cDATE;
	}
	$cDESC = $_POST['EDIT_BDV_DESC'];
	$cBANK = $_POST['UPD_BDV_BANK'];
	// die ($cBANK);
	$c_CEK = $_POST['UPD_BDV_CQ'];

	RecUpdate('TrPaymentHdr', ['BDV_DESC', 'BDV_DATE', 'BDV_BANK', 'UP_DATE', 'UPD_DATE'], 
		[ENCODE($cDESC), $cDATE, $cBANK, $cUSERCODE, $NOW], 
		"APP_CODE='$cAPP_CODE' and BDV_NO='$cBDV_NO' and DELETOR=''");

	$qQUERY = OpenTable('TrPaymentBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and BDV_NO='$cBDV_NO'");
	$aPAY_BANK=SYS_FETCH($qQUERY);
	if(SYS_ROWS($qQUERY)==0){
		if($cBANK>'')	RecCreate('TrPaymentBank', ['BDV_NO', 'BDV_CQ', 'BDV_DD', 'ENTRY', 'REC_ID', 'APP_CODE'], [$cBDV_NO, $c_CEK, $cDUE_DATE, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
	} else {
		if($cBANK>'')	{
			RecUpdate('TrPaymentBank', ['BDV_DD', 'BDV_CQ'], [$cDUE_DATE, $c_CEK], "REC_ID='$aPAY_BANK[REC_ID]'");
		} else {
			RecSoftDel($aPAY_BANK['REC_ID']);
		}
	}

	$nNEW_VALUE 	= str_replace(',', '', $_POST['NEW_AMOUNT1']);
	if($nNEW_VALUE>0)	{
		RecCreate('TrPaymentDtl', ['BDV_NO', 'BDV_REFF', 'BDV_DESC', 'BDV_DAM', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], 
		[$cBDV_NO, $_POST['NEW_ACCOUNT1'], $_POST['NEW_DTL1'], $nNEW_VALUE, $cUSERCODE, $NOW, $cAPP_CODE]);
	}
	APP_LOG_ADD($cHEADER, 'update : '.$cBDV_NO);
	header('location:tr_payment.php');
	break;

case md5('d3l_p4y'):
	$NOW = date("Y-m-d H:i:s");
	$cBDV_NO=$_GET['_c'];
	RecUpdate('TrPaymentHdr', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, $NOW], 
		"APP_CODE='$cAPP_CODE' and BDV_NO='$cBDV_NO'");
	RecUpdate('TrPaymentDtl', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, $NOW], 
		"APP_CODE='$cAPP_CODE' and BDV_NO='$cBDV_NO'");
	$qQUERY = OpenTable('TrPaymentBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and BDV_NO='$cBDV_NO'");
	$aPAY_BANK=SYS_FETCH($qQUERY);
	if(SYS_ROWS($qQUERY)>0)	RecSoftDel($aPAY_BANK['REC_ID']);
	APP_LOG_ADD($cHEADER, 'delete : '.$cBDV_NO);
	header('location:tr_payment.php');
	break;

case 'upd_upd_dtl':
	$NOW = date("Y-m-d H:i:s");
	$nREC_NO = $_GET['DTL_RECN'];
	$qUPD_DTL_QUERY = OpenTable('TrPaymentDtl', "BDV3_REC_N=$nREC_NO");
	$aREC_UPD_DETAIL=SYS_FETCH($qUPD_DTL_QUERY);
	$cBDV_NO	= $aREC_UPD_DETAIL['BDV_NO'];
	$nNILAI = "0".str_replace(',', '', $_POST['UPD_UPD_VALUE']);
	if($_POST['UPD_UPD_ACCOUNT_NO']==''){
		MSG_INFO(S_MSG('NJ80','Kode account masih kosong'));
		return;
	}
	if($nNILAI==0){
		MSG_INFO(S_MSG('NP51','Nilai pembayaran masih kosong, harap diisi'));
		header('location:tr_payment.php?_a='.md5('upd4t3').'&_r=$cBDV_NO');
		return;
	}

	RecUpdate('TrPaymentDtl', ['BDV_REFF', 'BDV_DESC', 'BDV_DAM', 'UP_DATE', 'UPD_DATE'], 
		[$_POST['UPD_UPD_ACCOUNT_NO'], $_POST['UPD_UPD_BDV_DESC'], $nNILAI, $cUSERCODE, $NOW], "BDV3_REC_N=$nREC_NO");
	APP_LOG_ADD($cHEADER, 'update : '.$cBDV_NO);
	header("location:tr_payment.php?_a=".md5('upd4t3')."&_r=".md5($aREC_UPD_DETAIL['BDV_NO']));
	break;

case md5('upd_del_dtl'):
	$nREC_NO = $_GET['_r'];
	$qQUERY = OpenTable('TrPaymentDtl', "BDV3_REC_N=$nREC_NO");
	if($aREC=SYS_FETCH($qQUERY))	
		RecUpdate('TrPaymentDtl', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, date("Y-m-d H:i:s")], "BDV3_REC_N='$nREC_NO'");
	else {
		return;
	}
	header("location:tr_payment.php?_a=".md5('upd4t3')."&_r=".md5($aREC['BDV_NO']));
	break;
case md5('pay_print'):
	$cPAY_CODE = $_GET['_c'];
	if($cPAY_CODE=='') {
		echo "<script> window.history.back();	</script>";
		return;
	}
	$qQUERY = OpenTable('TrPaymentHdr', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and BDV_NO='$cPAY_CODE'");
	if(SYS_ROWS($qQUERY)==0){
		MSG_INFO(S_MSG('NP28','Nomor Pembayaran tidak ada'));
		return;
	}
	$aPR_HDR=SYS_FETCH($qQUERY);
	$cFORM=S_PARA('FORMAT_PAYMENT', 'PAYMENT');

	if(SYS_ROWS($qQUERY)==0){
		MSG_INFO('Nomor penerimaan tidak ada');
		return;
	}
	APP_LOG_ADD($cHEADER, 'print : '.$cPAY_CODE);

	require('vendor/fpdf/fpdf.php');
	$qTB_BILL=OpenTable('TbBillPrintHdr', "PRNTR_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qTB_BILL)==0)  return;
	$aREC_TB_BILL=SYS_FETCH($qTB_BILL);
	$cPAPER = 'A4';
	if($aREC_TB_BILL['PAPER_SIZE']>'')   $cPAPER = $aREC_TB_BILL['PAPER_SIZE'];
	$cORIEN = 'P';
	if($aREC_TB_BILL['ORIENTATION']>'')   $cORIEN = $aREC_TB_BILL['ORIENTATION'];
	$pdf=new FPDF($cORIEN, 'mm', $cPAPER);	
	$pdf->AddPage();
	$pdf->AddFont('Verdana','','verdana.php');
	$pdf->AddFont('Verdana','B','verdanab.php');
	if(GET_FORMAT($cFORM, 'LOGO_CETAK')=='1') {
		$cLOGO_FILE = 'data/images/'.$cAPP_CODE.'_KOP.jpg';
		$nLOGO_LEFT = intval(GET_FORMAT($cFORM, 'LOGO_LEFT'));
		$nLOGO_TOP = intval(GET_FORMAT($cFORM, 'LOGO_TOP'));
		if($nLOGO_LEFT>0 && $nLOGO_TOP>0 && file_exists($cLOGO_FILE))
			$pdf->Image($cLOGO_FILE, $nLOGO_LEFT, $nLOGO_TOP, -300);
	}
	$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
	if(GET_FORMAT($cFORM, 'COMP_CETAK')=='1') {
		$cFONT_CODE = GET_FORMAT($cFORM, 'COMP_FONT_CODE');
		$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		if(SYS_ROWS($qFONT)>0) {
			$aFONT = SYS_FETCH($qFONT);
			$cFONT_NAME = $aFONT['NAME'];
			if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
			if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
			if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
			$nFONT_SIZE = $aFONT['SIZE'];
		}
		$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
		$pdf->Text(GET_FORMAT($cFORM, 'COMP_LEFT'), GET_FORMAT($cFORM, 'COMP_TOP'), S_PARA('CO1',''));
	}
	$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
	if(GET_FORMAT($cFORM, 'ADD1_CETAK')=='1') {
		$cFONT_CODE = GET_FORMAT($cFORM, 'ADD1_FONT_CODE');
		$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		if(SYS_ROWS($qFONT)>0) {
			$aFONT = SYS_FETCH($qFONT);
			$cFONT_NAME = $aFONT['NAME'];
			if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
			if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
			if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
			$nFONT_SIZE = $aFONT['SIZE'];
		}
		$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
		$pdf->Text(GET_FORMAT($cFORM, 'ADD1_LEFT'), GET_FORMAT($cFORM, 'ADD1_TOP'), S_PARA('CO2',''));
	}

	if(GET_FORMAT($cFORM, 'N_NOTA_CTK')=='1') {
		$cFONT_CODE = GET_FORMAT($cFORM, 'NOTA_FONT_CODE');
		$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		if(SYS_ROWS($qFONT)>0) {
			$aFONT = SYS_FETCH($qFONT);
			$cFONT_NAME = $aFONT['NAME'];
			if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
			if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
			if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
			$nFONT_SIZE = $aFONT['SIZE'];
		}
		$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
		$pdf->Text(GET_FORMAT($cFORM, 'NOTA_LEFT'), GET_FORMAT($cFORM, 'NOTA_TOP'), $aPR_HDR['BDV_NO']);
	}
	if(GET_FORMAT($cFORM, 'TGGL_CTK')=='1') {
		$cFONT_CODE = GET_FORMAT($cFORM, 'TGGL_FONT_CODE');
		$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		if(SYS_ROWS($qFONT)>0) {
			$aFONT = SYS_FETCH($qFONT);
			$cFONT_NAME = $aFONT['NAME'];
			if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
			if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
			if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
			$nFONT_SIZE = $aFONT['SIZE'];
		}
		$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
		$pdf->Text(GET_FORMAT($cFORM, 'TGGL_LEFT'), GET_FORMAT($cFORM, 'TGGL_TOP'), $aPR_HDR['BDV_DATE']);

	}
	if(GET_FORMAT($cFORM, 'JAM_CTK')=='1') {
		date_default_timezone_set('Asia/Jakarta');
		$cFONT_CODE = GET_FORMAT($cFORM, 'JAM_FONT_CODE');
		$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		if(SYS_ROWS($qFONT)>0) {
			$aFONT = SYS_FETCH($qFONT);
			$cFONT_NAME = $aFONT['NAME'];
			if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
			if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
			if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
			$nFONT_SIZE = $aFONT['SIZE'];
		}
		$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
		$pdf->Text(GET_FORMAT($cFORM, 'JAM_LEFT'), GET_FORMAT($cFORM, 'JAM_TOP'), date('H:i', time()));

	}

	for($I = 1; $I<=12; $I++):
		$J=(string)$I;
		if(GET_FORMAT($cFORM, 'KONST'.$J.'_STAT')=='1') {
			$cFONT_CODE = GET_FORMAT($cFORM, 'KONST'.$J.'_FONT_CODE');
			$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
			if(SYS_ROWS($qFONT)>0) {
				$aFONT = SYS_FETCH($qFONT);
				$cFONT_NAME = $aFONT['NAME'];
				if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
				if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
				if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
				$nFONT_SIZE = $aFONT['SIZE'];
			}
			$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
			$pdf->Text(GET_FORMAT($cFORM, 'KONST'.$J.'_COL'), GET_FORMAT($cFORM, 'KONST'.$J.'_ROW'), GET_FORMAT($cFORM, 'KONST'.$J.'_CONTENT'));

		}
	endfor;

	for($I = 1; $I<=9; $I++):
		$J=(string)$I;
		if(GET_FORMAT($cFORM, 'LINE'.$J.'_CTK')=='1') {
			$pdf->SetLineWidth(intval(GET_FORMAT($cFORM, 'LINE'.$J.'_POINT')/10));
			$pdf->Line(GET_FORMAT($cFORM, 'LINE'.$J.'_LEFT_COL'), GET_FORMAT($cFORM, 'LINE'.$J.'_LEFT_ROW'), GET_FORMAT($cFORM, 'LINE'.$J.'_RIGHT_COL'), GET_FORMAT($cFORM, 'LINE'.$J.'_RIGHT_ROW'));
		}
	endfor;

	$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
	$cFONT_CODE = GET_FORMAT($cFORM, 'DETAIL_DTA_FONT_CODE');
	$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	if(SYS_ROWS($qFONT)>0) {
		$aFONT = SYS_FETCH($qFONT);
		$cFONT_NAME = $aFONT['NAME'];
		if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
		if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
		if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
		$nFONT_SIZE = $aFONT['SIZE'];
	}

	$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
	$qBILL_COL=OpenTable('TbBillCol', "BILL_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
	$I=0;
	$cSTART_ROW = GET_FORMAT($cFORM, 'DETAIL_START_ROW');
	$nSTART_ROW = intval($cSTART_ROW);

	while($aDTL_COL=SYS_FETCH($qBILL_COL)) {
		$I++;
		$J=(string)$I;
		$cSTTS = 'DETAIL'.$J.'_HEAD_STATUS';
		if(GET_FORMAT($cFORM, $cSTTS)=='1') {
			$cDTL_COL =$aDTL_COL['COL_NAME'];
			$nCOL = intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
			$cSTTS = 'DETAIL'.$J.'_HEAD_STATUS';
			$pdf->Text($nCOL, $nSTART_ROW, GET_FORMAT($cFORM, 'DETAIL'.$J.'_HEAD_LABEL'));
		}
	}

	$nSTART_ROW += 3;
	$nREC_DTL = 0;
	$nTOTAL = 0;
	$qQ_HDR = OpenTable('TrPaymentHdr', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and BDV_NO='$cPAY_CODE'");
	$aREC_HDR = SYS_FETCH($qQ_HDR);
	if(GET_FORMAT($cFORM, 'KET_CTK')=='1') {
		$cFONT_CODE = GET_FORMAT($cFORM, 'KET_FONT_CODE');
		$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		if(SYS_ROWS($qFONT)>0) {
			$aFONT = SYS_FETCH($qFONT);
			$cFONT_NAME = $aFONT['NAME'];
			if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
			if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
			if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
			$nFONT_SIZE = $aFONT['SIZE'];
		}
		$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
		$pdf->Text(GET_FORMAT($cFORM, 'KET_LEFT'), GET_FORMAT($cFORM, 'KET_TOP'), $aREC_HDR['BDV_DESC']);
	}
	$cQ_DTL=OpenTable('TrPaymentDtl', "A.BDV_NO='$aREC_HDR[BDV_NO]' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
	while($aDTL_REC=SYS_FETCH($cQ_DTL)) {
		$I=0;
		$nTOTAL += $aDTL_REC['BDV_DAM'];
		$nSTART_ROW += 7;
		$qBILL_COL=OpenTable('TbBillCol', "BILL_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
		$nREC_DTL = SYS_ROWS($qBILL_COL);
		while($aDTL_FLD=SYS_FETCH($qBILL_COL)) {
			$I++;
			$J=(string)$I;
			$cFIELD = $aDTL_FLD['COL_CODE'];
			$nCOL = intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
			if(GET_FORMAT($cFORM, 'DETAIL'.$J.'_HEAD_STATUS')=='1') {
				switch($cFIELD){
					case 'AC_CODE':
						$pdf->Text($nCOL, $nSTART_ROW, $aDTL_REC['BDV_REFF']);
						break;
					case 'AC_NAME':
						$pdf->Text($nCOL, $nSTART_ROW, $aDTL_REC['ACCT_NAME']);
						break;
					case 'KET':
						$pdf->Text($nCOL, $nSTART_ROW, $aDTL_REC['BDV_DESC']);
						break;
					case 'NILAI':
						$pdf->Text($nCOL, $nSTART_ROW, CVR($aDTL_REC['BDV_DAM']));
						break;
				}
			}
		}
	}
	if(GET_FORMAT($cFORM, 'LINE_AFTER_DETAIL')=='1') {
		$nCOL = intval(GET_FORMAT($cFORM, 'LINE_AFTER_DTL_COL'));
		$nLEN = intval(GET_FORMAT($cFORM, 'LINE_AFTER_DTL_LENGTH'));
		$pdf->SetLineWidth(intval(GET_FORMAT($cFORM, 'LINE_AFTER_DTL_POINT')/10));
		$pdf->Line($nCOL, $nSTART_ROW + 5, $nCOL+$nLEN,  $nSTART_ROW + 5);
	}
	for($I = 1; $I<=$nREC_DTL; $I++):			//	total
		$J=(string)$I;
		if(GET_FORMAT($cFORM, 'TOTAL'.$J.'_STATUS')=='1') {
			$cDATA_COL = GET_FORMAT($cFORM, 'TOTAL'.$J.'_DATA_COL');
			if($cDATA_COL>'0')
				$pdf->Text($cDATA_COL, $nSTART_ROW+10, CVR($nTOTAL));
		}
	endfor;
	if(GET_FORMAT($cFORM, 'TOTAL_SAYS_STATUS')=='1') {
		$nCOL = intval(GET_FORMAT($cFORM, 'TOTAL_SAYS_COL'));
		$nROW = intval(GET_FORMAT($cFORM, 'TOTAL_SAYS_ROW'));
		$pdf->Text($nCOL, $nROW, SAYS($nTOTAL));
	}

	for($I = 1; $I<=9; $I++):			//	box
		$J=(string)$I;
		if(GET_FORMAT($cFORM, 'BOX'.$J.'_CTK')=='1') {
			$nLEFT = GET_FORMAT($cFORM, 'BOX'.$J.'_LEFT_COL');
			$nROW  = GET_FORMAT($cFORM, 'BOX'.$J.'_LEFT_ROW');
			$nRIGHT= GET_FORMAT($cFORM, 'BOX'.$J.'_RIGHT_COL');
			$nBOTTOM= GET_FORMAT($cFORM, 'BOX'.$J.'_RIGHT_ROW');
			$nWIDTH = $nRIGHT - $nLEFT;
			$nHEIGHT= $nBOTTOM - $nROW;
			$nBOX_LINE = 0.1;
			if(GET_FORMAT($cFORM, 'BOX'.$J.'_POINT')>0) $nBOX_LINE = intval(GET_FORMAT($cFORM, 'BOX'.$J.'_POINT')/10);
			$pdf->SetLineWidth($nBOX_LINE);
			$pdf->Rect($nLEFT, $nROW, $nWIDTH, $nHEIGHT);
		}
	endfor;

$pdf->Output();
	break;
}
?>
<script type="text/javascript">
$(function() {
    if($('#SelectUpdAccount').val() == ' ') {
		$('#NUM_CEK').hide();
	} else {
		$('#NUM_CEK').show();
	}
    $('#SelectUpdAccount').change(function(){
        if($('#SelectUpdAccount').val() != ' ') {
            $('#NUM_CEK').show(); 
        } else {
            $('#NUM_CEK').hide(); 
        } 
    });
});
</script>
