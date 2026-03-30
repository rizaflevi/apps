<?php
//	tr_receipt.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHELP_FILE = 'Doc/Transaksi - Penerimaan.pdf';

	$cUSERCODE 	= $_SESSION['gUSERCODE'];
	$can_CREATE = TRUST($cUSERCODE, 'TR_RECEIPT_1ADD');
	$can_ALL = TRUST($cUSERCODE, 'TR_RECEIPT_5VIEW_ALL');
	$cFILTER = ($can_ALL ? '' : " and ENTRY='".$cUSERCODE."'");

	$sPERIOD1=$_SESSION['sCURRENT_PERIOD'];
	if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];

	$cHEADER = S_MSG('NR01','Penerimaan Kas');
	$cACTION = (isset($_GET['_a']) ? $_GET['_a'] : '');
  
	$l_SCHOOL = IS_SCHOOL($cAPP_CODE);
	if($l_SCHOOL>0)	$cHELP_FILE = 'Doc/School - Transaksi - Penerimaan.pdf';
	$l_INVOICE = (IS_TRADING($cAPP_CODE) || $l_SCHOOL || IS_OUTSOURCING_WITH_MATERIAL($cAPP_CODE) ? 1 : 0);
	$l_DUEDATE = (S_PARA('RECEIPT_NO_DUE_DATE')=='1' ? 0 : 1);

	$nMANUAL= (S_PARA('RECEIPT_NUM_MANUAL', '')=='1' ? 1 : 0);
	$cADD_RECEIPT 	= S_MSG('NR23','Tambah Penerimaan');
	$cADD_DTL_RCP 	= S_MSG('NR26','Tambah Detil Penerimaan');

	$cKD_TRM 		= S_MSG('NR02','No. Penerimaan');
	$cINVOICE		= S_MSG('NJ02','No. Kirim');
	$cTANGGAL 		= S_MSG('NR03','Tanggal');
	$cNIL_TRN		= S_MSG('NR09','Nilai');
	$cACCOUNT		= S_MSG('NR08','Nama Account');
	$cKETERANGAN 	= S_MSG('NR04','Keterangan');
	$cMESSAG1		= S_MSG('F021','Benar data ini mau di hapus ?');
	$cBANK_NAME		= S_MSG('F131','Nama Bank');
	$cNOMOR_CEK		= S_MSG('NR10','No. Cek');
	$cDUE_DATE		= S_MSG('NR11','Jatuh Tempo');
	$cTTIP_NOMOR	= S_MSG('NR12','Nomor bukti penerimaan');
	$cTTIP_TGLTR	= S_MSG('NR14','Tanggal terima');
	$cTTIP_KETRG	= S_MSG('NR15','Keterangan tambahan mengenai penerimaan ini');
	$cTTIP_BANK		= S_MSG('NR18','klik untuk memilih kode bank dimana masuk nya penerimaan');
	$cTTIP_DESC		= S_MSG('NR1C','Keterangan mengenai detil penerimaan');
	$cTTIP_VALUE	= S_MSG('NR1D','Nilai atau jumlah penerimaan');

	$cSAVE_DATA=S_MSG('F301','Save');
	
switch($cACTION){
	default:
		DEF_WINDOW($cHEADER, '', 'prd');
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. $cADD_RECEIPT.'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKD_TRM, $cTANGGAL, $cKETERANGAN, $cNIL_TRN], '', [0,0,1,1]);
						echo '<tbody>';
							$nTOTAL = 0;
							$qQUERY=OpenTable('TrReceiptHdr', "left(A.TGL_BAYAR,7)='".substr($sPERIOD1,0,7)."' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)".$cFILTER, '', "A.NO_TRM desc");
							while($aREC_TERIMA1=SYS_FETCH($qQUERY)) {
								echo '<tr>';
									$cICON = 'fa fa-money';
									if(trim($aREC_TERIMA1['BANK'])!='') 	$cICON = 'fa-bank';
									echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
									echo "<td><span><a href='?_a=".md5('upd4t3')."&_r=".md5($aREC_TERIMA1['REC_ID'])."'>". $aREC_TERIMA1['NO_TRM']."</a></span></td>";
									echo '<td>'.date("d-M-Y", strtotime($aREC_TERIMA1['TGL_BAYAR'])).'</td>';
									echo '<td>'.DECODE($aREC_TERIMA1['DESCRP']).'</td>';
									$nAMOUNT = 0;
									$dQUERY=OpenTable('TrReceiptDtl', "A.NO_TRM='$aREC_TERIMA1[NO_TRM]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
									while($aREC_RECEIPT=SYS_FETCH($dQUERY)) {
										$nAMOUNT 	+= $aREC_RECEIPT['NILAI'];
									}
									echo '<td align="right">'.CVR($nAMOUNT).'</td>';
									$nTOTAL += $nAMOUNT;
								echo '</tr>';
							}
						echo '<tbody>';
						TTOTAL(['Total', '', '', CVR($nTOTAL)], [0,0,0,1]);
					eTABLE();
				TDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('cr34t3'):
		$cMONTH_FILT= (S_PARA('RESET_NUM_RECEIPT_MONTHLY', '')=='1' ? " and left(A.TGL_BAYAR,7)='".substr($sPERIOD1,0,7)."'" : '');
		$cPICT_OR 	= S_PARA('PICT_OR', '999999');
		$qQ_LAST 	= OpenTable('TrReceiptHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)".$cMONTH_FILT, '', "A.NO_TRM desc limit 1");
		$nLAST_NOM	= 1;
		if($aREC_TERIMA1= SYS_FETCH($qQ_LAST)) {
			$cLAST_NOM	= $aREC_TERIMA1['NO_TRM'];
			$nLAST_NOM	= intval($cLAST_NOM)+1;
		}
		$cLAST_NOM	= str_pad((string)$nLAST_NOM, strlen($cPICT_OR), '0', STR_PAD_LEFT);
		$dLAST_DATE=date('t/m/Y', strtotime($sPERIOD1));
		DEF_WINDOW($cADD_RECEIPT);
			TFORM($cADD_RECEIPT, '?_a=tam_bah', [], $cHELP_FILE);
				TDIV();
                    LABEL([4,4,4,6], '700', $cKD_TRM);
                    INPUT('text', [3,3,3,6], '900', 'ADD_NO_TRM', $cLAST_NOM, ($nMANUAL ? 'autofocus' : ''), '', '', 0, ($nMANUAL ? '' : 'readonly'), 'fix', $cTTIP_NOMOR);
                    LABEL([4,4,4,6], '700', $cTANGGAL);
					INP_DATE([2,2,3,6], '', 'ADD_TGL_BAYAR1', date("d/m/Y"), '', '', '', 'fix', $cTTIP_TGLTR);
                    LABEL([4,4,4,6], '700', $cKETERANGAN);
                    INPUT('text', [6,6,6,6], '900', 'ADD_DESCRP1', $cLAST_NOM, '', '', '', 0, '', 'fix', $cTTIP_KETRG);
                    LABEL([4,4,4,6], '700', $cBANK_NAME);
					SELECT([4,4,4,6], 'ADD_BANK1', '', 'Select_Rec_Bank');
						echo "<option value=' '  >Cash</option>";
						$qQUERY_BANK=OpenTable('TbBank', "ACCOUNT!='' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_BANK=SYS_FETCH($qQUERY_BANK)){
							echo "<option value='$aREC_BANK[B_CODE]'  >".DECODE($aREC_BANK['B_NAME'])."</option>";
						}
					echo '</select><br>';
					CLEAR_FIX();
					echo '<div id="NUM_CEK" class="form-group">';
                    	LABEL([4,4,4,6], '700', $cNOMOR_CEK);
                    	INPUT('text', [6,6,6,6], '900', 'ADD_TRM_CEK1', '', '', '', '', 0, '', 'fix', $cTTIP_KETRG);
						if($l_DUEDATE)	{
							LABEL([4,4,4,6], '700', $cDUE_DATE);
							echo '<input type="text" class="col-sm-3 form-label-900" data-mask="date" name="ADD_TRM_DD" id="field-2" value='.date("d-m-Y").'>';
						}
					eTDIV();
					CLEAR_FIX();
					echo '<br>';
					TABLE('example');
						if($l_INVOICE==1)
							THEAD([$cINVOICE, $cKETERANGAN, $cACCOUNT, $cNIL_TRN], '', [0,0,0,1], '*');
						else
							THEAD([$cKETERANGAN, $cACCOUNT, $cNIL_TRN], '', [0,0,1], '*');
						echo '<tbody>';
							for ($I=1; $I < 5; $I++) {
								$cIDX 	= (string)$I;
								echo '<tr>';
									if($l_INVOICE==1)
										INPUT('text', [12,12,12,12], '900', 'ADD_INVOICE'.$cIDX, '', '', '', '', 0, '', '', '', '', '', '', '', 'td');
									INPUT('text', [12,12,12,12], '900', 'ADD_DTL_DESCRP'.$cIDX, '', '', '', '', 0, '', '', '', '', '', '', '', 'td');
									echo '<td style="min-width:350px;"><div class="form-group">';
										SELECT([12,12,12,12], 'ADD_DTL_ACCOUNT'.$cIDX, '', '', 'select2');
											echo '<option></option>';
											$REC_DATA=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
											while($aREC_DETAIL=SYS_FETCH($REC_DATA)){
												echo "<option value='$aREC_DETAIL[ACCOUNT_NO]'  >".DECODE($aREC_DETAIL['ACCT_NAME'])."</option>";
											}
										echo '</select>
									</div></td>';
									INPUT('number', [12,12,12,12], '900', 'ADD_AMOUNT_'.$cIDX, '', '', 'fdecimal', 'right', 0, '', '', '', '', '', '', '', 'td');
								echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
		END2WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('upd4t3'):
		$cEDIT_RECEIPT 	= S_MSG('NR25','Edit Penerimaan');
		$can_UPDATE 	= TRUST($cUSERCODE, 'TR_RECEIPT_2UPD');
		$can_DELETE 	= TRUST($cUSERCODE, 'TR_RECEIPT_3DEL');
		$can_PRINT 		= TRUST($cUSERCODE, 'TR_RECEIPT_4PRT');
		$xREC_ID 		= $_GET['_r'];

		$qQUERY=OpenTable('TrReceiptHdr', "md5(REC_ID)='$xREC_ID'", '', "A.NO_TRM desc");
		$aREC_TERIMA1	= SYS_FETCH($qQUERY);
		$cNO_TRM 		= $aREC_TERIMA1['NO_TRM'];
		$cREC_ID 		= $aREC_TERIMA1['REC_ID'];
		$cREC_CEK = '';		$cREC_DD=date('Y/m/d');
		$qRBANK = OpenTable('TrReceiptBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and NO_TRM='$cNO_TRM'");
		$aREC_BANK=SYS_FETCH($qRBANK);
		if(SYS_ROWS($qRBANK)>0)	{
			$cREC_CEK = $aREC_BANK['C_NO'];		
			$cREC_DD=$aREC_BANK['DUE_DATE'];
		}
		DEF_WINDOW($cEDIT_RECEIPT);
			$aACT=[];
			if ($can_DELETE) {
				array_push($aACT, '<a href="?_a=del_receipt&_r='.$aREC_TERIMA1['REC_ID']. '" onClick="return confirm('. "'". $cMESSAG1. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>'.S_MSG('F304','Delete').'</a>');
			}
			if ($can_PRINT) {
				array_push($aACT, '<a href="?_a='. md5('rec_print'). '&_c='.$aREC_TERIMA1['REC_ID'].'"  title="print this receipt"><i class="glyphicon glyphicon-print"></i>Print</a>'); 
			}
			TFORM($cEDIT_RECEIPT, '?_a=ru_bah&_r='.$cNO_TRM, $aACT, $cHELP_FILE);
				LABEL([4,4,4,6], '700', $cKD_TRM);
				INPUT('text', [3,3,3,6], '900', 'EDIT_NO_TRM', $aREC_TERIMA1['NO_TRM'], '', '', '', 0, 'disable', 'fix', $cTTIP_NOMOR);
				LABEL([4,4,4,6], '700', $cTANGGAL);
				INP_DATE([3,3,3,6], '900', 'EDIT_TGL_BAYAR', date("d/m/Y", strtotime($aREC_TERIMA1['TGL_BAYAR'])), '', '', '', 'fix');
				LABEL([4,4,4,6], '700', $cKETERANGAN);
				INPUT('text', [6,6,6,6], '900', 'EDIT_DESCRP', $aREC_TERIMA1['DESCRP'], '', '', '', 0, '', 'fix', $cTTIP_KETRG);
				LABEL([4,4,4,6], '700', $cBANK_NAME);
				SELECT([6,6,6,6], 'UPD_BANK', '', 'SelectUpdBank');
					echo "<option value=' '  >Cash</option>";
					$qQUERY=OpenTable('TbBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					while($aREC_BANK=SYS_FETCH($qQUERY)){
						if($aREC_BANK['B_CODE']==$aREC_TERIMA1['BANK']){
							echo "<option value='$aREC_TERIMA1[BANK]' selected='$aREC_TERIMA1[BANK]' >".DECODE($aREC_BANK['B_NAME'])."</option>";
						} else
						echo "<option value='$aREC_BANK[B_CODE]'  >".DECODE($aREC_BANK['B_NAME'])."</option>";
					}
				echo '</select>';
				CLEAR_FIX();
				echo '<div id="UPD_NUM_CEK" class="form-group">';
					LABEL([4,4,4,6], '700', $cNOMOR_CEK);
					INPUT('text', [4,4,4,6], '900', 'UPD_CEK', $cREC_CEK, '', '', '', 0, '', 'fix');
					if($l_DUEDATE)	{
						LABEL([4,4,4,6], '700', $cDUE_DATE);
						INP_DATE([3,3,3,6], '900', 'UPD_TRM_DD', date("d/m/Y", strtotime($cREC_DD)), '', '', '', 'fix');
					}
				eTDIV();
				CLEAR_FIX();	
				echo '<br><br>';
				TDIV();
					TABLE('example');
					$aHEAD=[$cKETERANGAN, $cACCOUNT, $cNIL_TRN];	$aALGN=[0,0,1];	$aSIZE=[4,4,2];
					if($l_INVOICE==1) {
						array_splice($aHEAD, 0, 0, [$cINVOICE]);	array_splice($aALGN, 0, 0, [0]);	$aSIZE=[2,4,4,2];
					}
					THEAD($aHEAD, '', $aALGN, '*', $aSIZE);
					echo '<tbody>';
						$cQ_DTL=OpenTable('TrReceiptDtl', "A.NO_TRM='$cNO_TRM' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
						$nTOTAL = 0;
						while($aREC_DTL_RECEIPT=SYS_FETCH($cQ_DTL)) {
							echo '<tr>';
								$cREC_ID=md5($aREC_DTL_RECEIPT['REC_ID']);
								if($l_INVOICE==1)
									echo "<td><span><a href=?_a=".md5('edit_detail_trans')."&_r=".$cREC_ID.">". $aREC_DTL_RECEIPT['NO_FAKTUR'].'</a></span></td>';
								echo "<td><span><a href=?_a=".md5('edit_detail_trans')."&_r=".$cREC_ID.">". $aREC_DTL_RECEIPT['KET'].'</a></span></td>';
								echo "<td><span><a href=?_a=".md5('edit_detail_trans')."&_r=".$cREC_ID.">". DECODE($aREC_DTL_RECEIPT['ACCT_NAME']).'</a></span></td>';
								echo '<td align="right">'.CVR($aREC_DTL_RECEIPT['NILAI']).'</td>';
							echo '</tr>';
							$nTOTAL += $aREC_DTL_RECEIPT['NILAI'];
						}
						echo '<tr>';
							if($l_INVOICE==1) {
								INPUT('text', [12,12,12,12], '900', 'NEW_INVOICE', '', '', '', '', 20, '', 'fix', '', '', '', '', '', 'td');
							}
							INPUT('text', [12,12,12,12], '900', 'NEW_DTL1', '', '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
							echo '<td>';
								SELECT([12,12,12,12], 'NEW_ACCOUNT1', '', '', 'select2');
									echo '<option></<option>';
									$REC_DATA=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
									while($aREC_DETAIL=SYS_FETCH($REC_DATA)){
										echo "<option value='$aREC_DETAIL[ACCOUNT_NO]'  >".DECODE($aREC_DETAIL['ACCT_NAME'])."</option>";
									}
								echo '</select>';
							echo '</td>';
							INPUT('number', [12,12,12,12], '900', 'NEW_AMOUNT1', '', '', 'fdecimal', 'right', 0, '', 'fix', '', '', '', '', '', 'td');
						echo '<tr>';
						if($l_INVOICE) TTOTAL(['', 'Total', CVR($nTOTAL)], [0,0,1]);
						else TTOTAL(['Total', CVR($nTOTAL)], [0,1]);
					echo '</tbody>';
					eTABLE();
					SAVE($can_UPDATE ? $cSAVE_DATA : '');
				TDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('edit_detail_trans'):
		$cREC_ID = $_GET['_r'];
		$cEDIT_DTL_TRM 	= S_MSG('NR27','Edit Detil Penerimaan');
		$qQUERY=OpenTable('TrReceiptDtl', "md5(A.REC_ID)='$cREC_ID'");
		$aREC_DETAIL=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_DTL_TRM);
			$aACT=[];
			$can_DELETE 	= TRUST($cUSERCODE, 'TR_RECEIPT_3DEL');
			if ($can_DELETE==1) {
				array_push($aACT, '<a href="?_a=upd_del_dtl&_r='.$cREC_ID. '" onClick="return confirm('. "'". $cMESSAG1. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>'.S_MSG('F304','Delete').'</a>');
			}
			TFORM($cEDIT_DTL_TRM, '?_a=upd_upd_dtl&_r='.$cREC_ID, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cACCOUNT);
					TDIV(8,8,8,8);
						SELECT([4,4,4,6], 'UPD_UPD_ACCOUNT_NO', '', '', 'select2');
							echo "<option value=' '  > </option>";
							$qACCOUNT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_ACCOUNT=SYS_FETCH($qACCOUNT)){
								if($aREC_ACCOUNT['ACCOUNT_NO']==$aREC_DETAIL['ACCOUNT']){
									echo "<option value='$aREC_DETAIL[ACCOUNT]' selected='$aREC_DETAIL[ACCOUNT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
								} else
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
							}
						echo '</select>';
					eTDIV();
					LABEL([4,4,4,6], '700', $cKETERANGAN);
                	INPUT('text', [6,6,6,6], '900', 'UPD_UPD_DESCRP', $aREC_DETAIL['KET'], '', '', '', 0, '', 'Fix', $cTTIP_DESC);
					LABEL([4,4,4,6], '700', $cNIL_TRN);
                	INPUT('text', [2,2,2,3], '900', 'UPD_UPD_VALUE', $aREC_DETAIL['NILAI'], '', 'fdecimal', '', 0, '', 'Fix', $cTTIP_VALUE);
					SAVE($cSAVE_DATA);
				TDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case 'tam_bah':
		$dTG_BAYAR	= $_POST['ADD_TGL_BAYAR1'];		// 'dd/mm/yyyy'
		$cDATE 		= substr($dTG_BAYAR,6,4). '-'. substr($dTG_BAYAR,3,2). '-'. substr($dTG_BAYAR,0,2);
		if($l_DUEDATE){
			$dCEK_DATE 	= $dTG_BAYAR;
		} else {
			$dCEK_DATE 	= (isset($_POST['ADD_TRM_DD']) ? $_POST['ADD_TRM_DD'] : $dTG_BAYAR);		// 'dd/mm/yyyy'
		}
		$cCEK_DATE 	= substr($dCEK_DATE,6,4). '-'. substr($dCEK_DATE,3,2). '-'. substr($dCEK_DATE,0,2);
		$cNO_TRM	= trim($_POST['ADD_NO_TRM']);
		$cREC_HDR_ID= NowMSecs();
		if($cNO_TRM==''){
			MSG_INFO(S_MSG('NR41','Nomor Penerimaan masih kosong'));
			return;
		}
		if($_POST['ADD_TGL_BAYAR1']==''){
			MSG_INFO(S_MSG('NR42','Tanggal Penerimaan masih kosong'));
			return;
		}
		$cQUERY=OpenTable('TrReceiptHdr', "NO_TRM='$cNO_TRM' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($cQUERY)>0){
			MSG_INFO(S_MSG('NR28','Nomor penerimaan kas sudah ada'));
			return;
		} else {
			RecCreate('TrReceiptHdr', ['NO_TRM', 'TGL_BAYAR', 'BANK', 'DESCRP', 'ENTRY', 'REC_ID', 'APP_CODE'], 
				[$cNO_TRM, $cDATE, $_POST['ADD_BANK1'], ENCODE($_POST['ADD_DESCRP1']), $cUSERCODE, $cREC_HDR_ID, $cAPP_CODE]);
		}

		$nLOOP=0;
		for ($I=1; $I < 5; $I++) {
			$cIDX 	= (string)$I;
			$nVALUE	= str_replace(',', '', $_POST['ADD_AMOUNT_'.$cIDX]);
			$nVALUE	= str_replace('.', '', $nVALUE);
			$cACCOUNT = $_POST['ADD_DTL_ACCOUNT'.$cIDX];
			if($nVALUE>0){
				$nLOOP++;
				$nRec_id = date_create()->format('Uv');
				$nRec_id += $nLOOP;
				$cREC_ID=(string)$nRec_id;
				$lCREATE=RecCreate('TrReceiptDtl', ['NO_TRM', 'ACCOUNT', 'KET', 'NILAI', 'ENTRY', 'REC_ID', 'APP_CODE'], 
					[$cNO_TRM, $cACCOUNT, ENCODE($_POST['ADD_DTL_DESCRP'.$cIDX]), $nVALUE, $cUSERCODE, $cREC_ID, $cAPP_CODE]);
			}
		}
		$cBANK	= (isset($_POST['ADD_BANK1']) ? $_POST['ADD_BANK1'] : '');
		$c_CEK	= (isset($_POST['ADD_TRM_CEK1']) ? $_POST['ADD_TRM_CEK1'] : '');
		if($cBANK>'') {
			$cQUERY=OpenTable('TrReceiptBank', "NO_TRM='$cNO_TRM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
			if(SYS_ROWS($cQUERY)>0){
				RecUpdate('TrReceiptBank', ['DUE_DATE', 'C_NO'], [$cCEK_DATE, $c_CEK], "REC_ID='$aREC_BANK[REC_ID]'");
			}	else {
				$lCREATE=RecCreate('TrReceiptBank', ['NO_TRM', 'C_NO', 'DUE_DATE', 'ENTRY', 'REC_ID', 'APP_CODE'], 
					[$cNO_TRM, $c_CEK, $cCEK_DATE, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
			}
		}
		APP_LOG_ADD($cHEADER, 'add '.$cNO_TRM);
		header('location:tr_receipt.php?_a='.md5('upd4t3').'&_r='.md5($cREC_HDR_ID));
		break;

	case 'ru_bah':
		$cNO_TRM	= $_GET['_r'];
		$dTG_BAYAR 	= $_POST['EDIT_TGL_BAYAR'];		// 'dd/mm/yyyy'
		$cDATE 		= substr($dTG_BAYAR,6,4). '-'. substr($dTG_BAYAR,3,2). '-'. substr($dTG_BAYAR,0,2);
		if($l_DUEDATE){
			$dCEK_DATE 	= $dTG_BAYAR;
		} else {
			$dCEK_DATE 	= (isset($_POST['UPD_TRM_DD']) ? $_POST['UPD_TRM_DD'] : $dTG_BAYAR);		// 'dd/mm/yyyy'
		}
		$dDUE_DATE 	= $dCEK_DATE;		// 'dd/mm/yyyy'
		$cDUE_DATE = substr($dDUE_DATE,6,4). '-'. substr($dDUE_DATE,3,2). '-'. substr($dDUE_DATE,0,2);
		$cBANK		= $_POST['UPD_BANK'];
		$c_CEK		= $_POST['UPD_CEK'];
		$qQUERY = OpenTable('TrReceiptHdr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and NO_TRM='$cNO_TRM'");
		$aREC_HDR=SYS_FETCH($qQUERY);
		$cKET 	= (isset($_POST['EDIT_DESCRP']) ? $_POST['EDIT_DESCRP'] : $dTG_BAYAR);		// 'dd/mm/yyyy'
		$cKET 	= ENCODE($cKET);
		RecUpdate('TrReceiptHdr', ['DESCRP', 'TGL_BAYAR', 'BANK'], [$cKET, $cDATE, $cBANK], 
			"NO_TRM='$cNO_TRM' and APP_CODE='$cAPP_CODE'");

		$qQUERY = OpenTable('TrReceiptBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and NO_TRM='$cNO_TRM'");
		$aREC_BANK=SYS_FETCH($qQUERY);
		if(SYS_ROWS($qQUERY)==0){
			if($cBANK>'')	RecCreate('TrReceiptBank', ['NO_TRM', 'C_NO', 'DUE_DATE', 'ENTRY', 'REC_ID', 'APP_CODE'], [$cNO_TRM, $c_CEK, $cDUE_DATE, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		} else {
			if($cBANK>'')	{
				RecUpdate('TrReceiptBank', ['DUE_DATE', 'C_NO'], [$cDUE_DATE, $c_CEK], "REC_ID='$aREC_BANK[REC_ID]'");
			} else {
				RecSoftDel($aREC_BANK['REC_ID']);
			}
		}

		$nNEW_VALUE 	= str_replace(',', '', $_POST['NEW_AMOUNT1']);
		if($nNEW_VALUE>0)	{
			RecCreate('TrReceiptDtl', ['NO_TRM', 'ACCOUNT', 'KET', 'NILAI', 'ENTRY', 'REC_ID', 'APP_CODE'], 
			[$cNO_TRM, $_POST['NEW_ACCOUNT1'], $_POST['NEW_DTL1'], $nNEW_VALUE, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		}
		APP_LOG_ADD($cHEADER, 'edit '.$cNO_TRM);
//		header('location:tr_receipt.php');
		header('location:tr_receipt.php?_a='.md5('upd4t3').'&_r='.md5($aREC_HDR['REC_ID']));
		break;

	case 'del_receipt':
		$cRED_ID=$_GET['_r'];
		$qQUERY = OpenTable('TrReceiptHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and REC_ID='$cRED_ID'");
		while($aRHDR = SYS_FETCH($qQUERY)){
			$cQ_DTL=OpenTable('TrReceiptDtl', "A.NO_TRM='$aRHDR[NO_TRM]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
			while($aRDTL = SYS_FETCH($cQ_DTL)){
				RecSoftDel($aRDTL['REC_ID']);
			}
		}
		RecSoftDel($cRED_ID);
		APP_LOG_ADD($cHEADER, 'Delete : '.$_GET['_r']);
		header('location:tr_receipt.php');
		break;

	case 'upd_del_dtl':
		$xRED_ID=$_GET['_r'];
		$cQ_DTL=OpenTable('TrReceiptDtl', "md5(A.REC_ID)='$xRED_ID' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		if($aRDTL = SYS_FETCH($cQ_DTL))		RecSoftDel($aRDTL['REC_ID']);
		APP_LOG_ADD($cHEADER, 'Delete dtl : '.$aRDTL['REC_ID']);
		$qQUERY = OpenTable('TrReceiptHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and NO_TRM='$aRDTL[NO_TRM]'");
		if($aRHDR = SYS_FETCH($qQUERY))
		header('location:tr_receipt.php?_a='.md5('upd4t3').'&_r='.md5($aRHDR['REC_ID']));
		break;
	case 'upd_upd_dtl':
		$cREC_NO = $_GET['_r'];
		print_r2($cREC_NO);
		$qUPD_DTL_QUERY = OpenTable('TrReceiptDtl', "md5(REC_ID)='$cREC_NO'");
		$aREC_UPD_DETAIL=SYS_FETCH($qUPD_DTL_QUERY);
		$nDEBET = str_replace(',', '', $_POST['UPD_UPD_VALUE']);
		$cACCOUNT=$_POST['UPD_UPD_ACCOUNT_NO'];
		$cKET = ENCODE($_POST['UPD_UPD_DESCRP']);
		if($cACCOUNT==''){
			MSG_INFO(S_MSG('NR45','Kode account penerimaan masih kosong'));
			return;
		}
		if($nDEBET==0){
			$MSG_INFO(S_MSG('NR46','Nilai penerimaan masih kosong'));
			return;
		}
		RecUpdate('TrReceiptDtl', ['ACCOUNT', 'KET', 'NILAI'], [$cACCOUNT, $cKET, $nDEBET], "md5(REC_ID)='$cREC_NO'");
		header("location:tr_receipt.php");
		break;
	case md5('rec_print'):
		$cREC_CODE = $_GET['_c'];
		if($cREC_CODE=='') {
			MSG_INFO('Nomor penerimaan masih kosong');
			return;
		}
		$cFORM=S_PARA('FORMAT_RECEIPT', 'RECEIPT');
		$qQUERY = OpenTable('TrReceiptHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and REC_ID='$cREC_CODE'");
		if(SYS_ROWS($qQUERY)==0){
			MSG_INFO('Nomor penerimaan tidak ada');
			return;
		}
		$aPR_HDR=SYS_FETCH($qQUERY);

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

// ------------------------------------------------------------------------------
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
			$pdf->Text(GET_FORMAT($cFORM, 'NOTA_LEFT'), GET_FORMAT($cFORM, 'NOTA_TOP'), $aPR_HDR['NO_TRM']);
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
			$pdf->Text(GET_FORMAT($cFORM, 'TGGL_LEFT'), GET_FORMAT($cFORM, 'TGGL_TOP'), $aPR_HDR['TGL_BAYAR']);

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
// --------------------------------------------------------------------------------------------
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
		$qQ_HDR = OpenTable('TrReceiptHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and REC_ID='$cREC_CODE'");
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
			$pdf->Text(GET_FORMAT($cFORM, 'KET_LEFT'), GET_FORMAT($cFORM, 'KET_TOP'), $aREC_HDR['DESCRP']);
		}
		$cQ_DTL=OpenTable('TrReceiptDtl', "A.NO_TRM='$aREC_HDR[NO_TRM]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		while($aDTL_REC=SYS_FETCH($cQ_DTL)) {
			$I=0;
			$nTOTAL += $aDTL_REC['NILAI'];
			$nSTART_ROW += 7;
			$qBILL_COL=OpenTable('TbBillCol', "BILL_CODE='RECEIPT' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
			$nREC_DTL = SYS_ROWS($qBILL_COL);
			while($aDTL_FLD=SYS_FETCH($qBILL_COL)) {
				$I++;
				$J=(string)$I;
				$cFIELD = $aDTL_FLD['COL_CODE'];
				$nCOL = intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
				if(GET_FORMAT($cFORM, 'DETAIL'.$J.'_HEAD_STATUS')=='1') {
					switch($cFIELD){
						case 'AC_CODE':
							$pdf->Text($nCOL, $nSTART_ROW, $aDTL_REC['ACCOUNT']);
							break;
						case 'AC_NAME':
							$pdf->Text($nCOL, $nSTART_ROW, DECODE($aDTL_REC['ACCT_NAME']));
							break;
						case 'KET':
							$pdf->Text($nCOL, $nSTART_ROW, DECODE($aDTL_REC['KET']));
							break;
						case 'NILAI':
							$pdf->Text($nCOL, $nSTART_ROW, CVR($aDTL_REC['NILAI']));
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
		APP_LOG_ADD($cHEADER, 'print : '.$aPR_HDR['NO_TRM']);
		break;

}
?>

<script>
$(function() {
    if($('#Select_Rec_Bank').val() == ' ') {
		$('#NUM_CEK').hide();
	} else {
		$('#NUM_CEK').show();
	}
    $('#Select_Rec_Bank').change(function(){
        if($('#Select_Rec_Bank').val() != ' ') {
            $('#NUM_CEK').show(); 
        } else {
            $('#NUM_CEK').hide(); 
        } 
    });
});

$(function() {
    if($('#SelectUpdBank').val() == ' ') {
		$('#UPD_NUM_CEK').hide();
	} else {
		$('#UPD_NUM_CEK').show();
	}
    $('#SelectUpdBank').change(function(){
        if($('#SelectUpdBank').val() != ' ') {
            $('#UPD_NUM_CEK').show(); 
        } else {
            $('#UPD_NUM_CEK').hide(); 
        } 
    });
});

</script>

