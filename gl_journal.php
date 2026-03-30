<?php
//	gl_journal.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Transaksi - Jurnal.pdf';
	$can_CREATE = TRUST($cUSERCODE, 'TR_JOURNAL_1ADD');

	$sPERIOD1=$_SESSION['sCURRENT_PERIOD'];
	$cACTION = '';
	if (isset($_GET['_a']))	$cACTION = $_GET['_a'];
  
	$cHEADER 		= S_MSG('NJ50','Jurnal');
	$cADD_DTL_JRN 	= S_MSG('NJ65','Add Detil Jurnal');
	$cEDIT_DTL_JRN 	= S_MSG('NJ69','Edit Detil Jurnal');

	$qQUERY	= OpenTable('TrJrnHdr', "left(DATE_TRANS,7)='".substr($sPERIOD1,0,7)."' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cKD_JRN 		= S_MSG('NJ57','Kode Journal');
	$cNO_JRN 		= S_MSG('NJ58','Nmr. Jurnal');
	$cTANGGAL 		= S_MSG('NJ59','Tanggal');
// 	$cLABEL4 		= S_MSG('NJ60','Keterangan');
	$cNIL_DEB 		= S_MSG('NJ75','Nilai Debit');
	$cNIL_KRD		= S_MSG('NJ76','Nilai Kredit');
	$cKD_ACCOUNT 	= S_MSG('TA21','Kode Account');
	$cACCOUNT		= S_MSG('F028','Account');
	$cKETERANGAN 	= S_MSG('NJ53','Keterangan');
	$cDEBIT 		= S_MSG('NJ54','Debit');
	$cKREDIT 		= S_MSG('NJ55','Kredit');
	$cMESSAG1		= S_MSG('F021','Benar data ini mau di hapus ?');
	
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER, '' , 'prd');
		$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
		TFORM($cHEADER, '', $aACT, $cHELP_FILE);
        // TDIV();
            TABLE('example', '', 10, '*', '*', '*');
				THEAD([$cKD_JRN, $cNO_JRN, $cTANGGAL, $cKETERANGAN, $cNIL_DEB, $cNIL_KRD]);
				echo '<tbody>';
					while($aREC_JOURNALH=SYS_FETCH($qQUERY)) {
					echo '<tr>';
						echo '<td class=""><div class="star"><i class="fa fa-file-text icon-xs icon-default"></i></div></td>';
						echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_JOURNALH['REC_ID'])."'>". $aREC_JOURNALH['JRN_CODE']."</a></span></td>";
						echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_JOURNALH['REC_ID'])."'>". $aREC_JOURNALH['NO_TRANS']."</a></span></td>";
						echo '<td>'.date("d-m-Y", strtotime($aREC_JOURNALH['DATE_TRANS'])).'</td>';
						echo '<td>'.$aREC_JOURNALH['DESCR'].'</td>';
						$nDEBET = 0;
						$nKREDIT = 0;
						$dQUERY = OpenTable('TrJrnDtl', "HDR_ID='$aREC_JOURNALH[REC_ID]' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_JOURNAL=SYS_FETCH($dQUERY)) {
							$nDEBET 	+= $aREC_JOURNAL['DEBIT'];
							$nKREDIT += $aREC_JOURNAL['CREDIT'];
						}
						echo '<td align="right">'.CVR($nDEBET).'</td>';
						echo '<td align="right">'.CVR($nKREDIT).'</td>';
					echo '</tr>';
					}
				echo '</tbody>';
			eTABLE();
		eTFORM('*');
	END_WINDOW();
	break;

	case md5('cr34t3'):
		$cHEADER 		= S_MSG('NJ63','Tambah Jurnal');
		$cMONTH_FILT= (S_PARA('RESET_NUM_JOURNAL_MONTHLY', '')=='1' ? " and left(A.DATE_TRANS,7)='".substr($sPERIOD1,0,7)."'" : '');

		$qQ_LAST=OpenTable('TrJrnHdr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'REC_ID desc limit 1');
		$aREC_JURNAL= SYS_FETCH($qQ_LAST);
		$cLAST_CODE='';
		$cLAST_NOM='0';
		if($aREC_JURNAL>0) {
			$cLAST_CODE	= $aREC_JURNAL['JRN_CODE'];
			$cLAST_NOM	= $aREC_JURNAL['NO_TRANS'];
		}
		$nLAST_NOM=intval($cLAST_NOM)+1;
		$cLAST_NOM=str_pad((string)$nLAST_NOM, 4, '0', STR_PAD_LEFT);
		$dLAST_DATE=date('t/m/Y', strtotime($sPERIOD1));
		DEF_WINDOW($cHEADER);
			TFORM($cHEADER, '?_a=AddJrn', [], $cHELP_FILE);
				LABEL([4,4,4,6], '700', $cKD_JRN);
				INPUT('text', [1,1,1,6], '900', 'ADD_JRN_CODE', $cLAST_CODE, '', '', '', 2, '', 'fix');
				LABEL([4,4,4,6], '700', $cNO_JRN);
				INPUT('text', [2,2,2,6], '900', 'ADD_NO_TRANS', $cLAST_NOM, '', '', '', 10, '', 'fix');
				LABEL([4,4,4,6], '700', $cTANGGAL);
				INP_DATE([2,2,3,6], '900', 'ADD_DATE_TRANS', $dLAST_DATE, '', '', '', 'fix');
				LABEL([4,4,4,6], '700', $cKETERANGAN);
				INPUT('text', [8,6,6,6], '900', 'ADD_DESCR', '', '', '', '', 50, '', 'fix');
				echo '<br>';
				TABLE('example4');
					THEAD([$cACCOUNT, $cKETERANGAN, $cDEBIT, $cKREDIT], '', [0,0,1,1], '*', [4,4,2,2]);
					echo '<tbody>';
					for ($I=1; $I < 5; $I++) {
						$cIDX 	= (string)$I;
						echo '<tr>';
							SELECT([12,12,12,12], 'ADD_DTL_ACCOUNT'.$cIDX, '', '', 'select2', '', 'td');
								echo '<option value=""> </option>';
								$qQUERY_ACCT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'ACCT_NAME');
								while($aREC_DETAIL=SYS_FETCH($qQUERY_ACCT)){
									echo "<option value='$aREC_DETAIL[ACCOUNT_NO]'  >$aREC_DETAIL[ACCT_NAME]</option>";
								}
							echo '</select></td>';
							INPUT('text', [12,12,12,12], '900', 'ADD_DTL_DESC_'.$cIDX, '', '', '', '', 50, '', '', '', '', '', '', '', 'td');
							INPUT('number', [12,12,12,12], '900', 'ADD_DTL_DEBIT_'.$cIDX, '', '', 'fdecimal', 'right', 0, '', '', '', '', '', '', '', 'td');
							INPUT('number', [12,12,12,12], '900', 'ADD_DTL_CREDIT_'.$cIDX, '', '', 'fdecimal', 'right', 0, '', '', '', '', '', '', '', 'td');
						echo '</tr>';
					}
					echo '</tbody>';
				eTABLE();
				SAVE($cSAVE);
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('up_d4t3'):
		$xREC_ID 		= $_GET['_r'];		// md5
		$cHEADER 		= S_MSG('NJ64','Edit Jurnal');
		$qQUERY			= OpenTable('TrJrnHdr', "md5(REC_ID)='$xREC_ID'");
		$aREC_JOURNALH	= SYS_FETCH($qQUERY);
		$cHDR_ID		= $aREC_JOURNALH['REC_ID'];
		$UPD_ACCOUNT 	= '1';
		$can_UPDATE = TRUST($cUSERCODE, 'TR_JOURNAL_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'TR_JOURNAL_3DEL');
		DEF_WINDOW($cHEADER);
			$aACT =[];
			if ($can_DELETE==1) 
				array_push($aACT, '<a href="?_a=del_JRNH&_r='.$cHDR_ID. '" onClick="return confirm('. "'". $cMESSAG1. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>');
			TFORM($cHEADER, '?_a=rubah&_r='.$cHDR_ID, $aACT, $cHELP_FILE);
				TDIV();
				LABEL([4,4,4,6], '700', $cKD_JRN);
				INPUT('text', [1,1,1,6], '900', 'EDIT_JRN_CODE', $aREC_JOURNALH['JRN_CODE'], '', '', '', 2, 'disabled', 'fix');
				LABEL([4,4,4,6], '700', $cNO_JRN);
				INPUT('text', [1,2,2,6], '900', 'EDIT_NO_TRANS', $aREC_JOURNALH['NO_TRANS'], '', '', '', 10, 'disabled', 'fix');
				LABEL([4,4,4,6], '700', $cTANGGAL);
				INP_DATE([2,3,3,6], '900', 'EDIT_DATE_TRANS', date("d/m/Y", strtotime($aREC_JOURNALH['DATE_TRANS'])), '', '', '', 'fix');
				LABEL([4,4,4,6], '700', $cKETERANGAN);
				INPUT('text', [8,8,8,6], '900', 'EDIT_DESCR', $aREC_JOURNALH['DESCR'], '', '', '', 50, '', 'fix');
				TABLE('example4');
					THEAD([$cACCOUNT, $cKETERANGAN, $cDEBIT, $cKREDIT], '', [0,0,1,1], '*', [4,4,2,2]);
					echo '<tbody>';
						$qQ_JRN = OpenTable('TrJrnDHdr', "md5(A.HDR_ID)='$xREC_ID' and A.REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_JOURNAL=SYS_FETCH($qQ_JRN)) {
							echo '<tr>';
								echo "<td><span><a href='?_a=".md5('edit_detail_journal')."&_r=$aREC_JOURNAL[DREC_ID]'>". $aREC_JOURNAL['ACCT_NAME'].'</a></span></td>';
								echo "<td><span><a href='?_a=".md5('edit_detail_journal')."&_r=$aREC_JOURNAL[DREC_ID]'>". $aREC_JOURNAL['DESCRIPT'].'</a></span></td>';
								echo '<td align="right">'.CVR($aREC_JOURNAL['DEBIT']).'</td>';
								echo '<td align="right">'.CVR($aREC_JOURNAL['CREDIT']).'</td>';
							echo '</tr>';
						}
						echo '<tr>
							<td><select id="SelectAccount" name="NEW_ACCOUNT" class="col-lg-12 col-sm-12 col-xs-12 form-label-900 select2">';
								$qQUERY_ACCT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'ACCT_NAME');
								echo '<option> </option>';
								while($aREC_DETAIL=SYS_FETCH($qQUERY_ACCT)){
									echo "<option value='$aREC_DETAIL[ACCOUNT_NO]'  >$aREC_DETAIL[ACCT_NAME]</option>";
								}
							echo '</select></td>';
							INPUT('text', [12,12,12,12], '900', 'NEW_DESC', '', '', '', '', 50, '', '', '', '', '', '', '', 'td');
							INPUT('number', [12,12,12,12], '900', 'NEW_DEBIT', '', '', 'fdecimal', 'right', 0, '', '', '', '', '', '', '', 'td');
							INPUT('number', [12,12,12,12], '900', 'NEW_CREDIT', '', '', 'fdecimal', 'right', 0, '', '', '', '', '', '', '', 'td');
						echo '</tr>';
					echo '</tbody>';
				eTABLE();
				SAVE($can_UPDATE ? $cSAVE : '');
			eTFORM();
		END_WINDOW();
	break;

	case md5('edit_detail_journal'):
		$can_DELETE = TRUST($cUSERCODE, 'TR_JOURNAL_3DEL');
		$eJRN_REC_NO = $_GET['_r'];
		$qREC_JOURNAL = OpenTable('TrJrnDtl', "REC_ID='$eJRN_REC_NO'");
		$aREC_JOURNALD=SYS_FETCH($qREC_JOURNAL);
		DEF_WINDOW($cHEADER);
		$aACT = ($can_DELETE==1 ? ['<a href="?_a=del_detail&_jr='. $eJRN_REC_NO. '" onClick="return confirm('. "'". $cMESSAG1. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
		TFORM($cHEADER, '?_a=upd_upd_dtl&GL_RECN='.$eJRN_REC_NO, $aACT, $cHELP_FILE);
		TDIV();
			LABEL([4,4,4,6], '700', $cACCOUNT);
			TDIV(8,8,8,12);
				SELECT([4,4,4,4], 'UPD_UPD_ACCOUNT', '', '', 'select2');
					echo "<option value=''  > </option>";
					$qREC_ACCT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					while($aREC_ACCOUNT=SYS_FETCH($qREC_ACCT)){
						if($aREC_ACCOUNT['ACCOUNT_NO']==$aREC_JOURNALD['ACCOUNT_NO']){
							echo "<option value='$aREC_JOURNALD[ACCOUNT_NO]' selected='$aREC_JOURNALD[ACCOUNT_NO]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
						} else
						echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
					}
                echo'</select>';
			eTDIV();
			CLEAR_FIX();
			LABEL([4,4,4,6], '700', $cKETERANGAN);
			INPUT('text', [8,8,8,6], '900', 'UPD_UPD_DESCRIPT', $aREC_JOURNALD['DESCRIPT'], '', '', '', 50, '', 'fix');
?>
			<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cDEBIT?></label>
			<input type="text" class="col-lg-2 col-sm-3 form-label-900" name='UPD_UPD_DEBIT' id="field-3" data-mask="fdecimal" data-numeric-align="right" value="<?php echo $aREC_JOURNALD['DEBIT']?>">
			<div class="clearfix"></div>
			
			<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cKREDIT?></label>
			<input type="text" class="col-lg-2 col-sm-3 form-label-900" name='UPD_UPD_CREDIT' id="field-4" data-mask="fdecimal" data-numeric-align="right" value="<?php echo $aREC_JOURNALD['CREDIT']?>">
			<div class="clearfix"></div>
		</div>
		<div class="text-left">
			<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE?>>
			<input type="button" class="btn" value=<?php echo $cCLOSE?> onclick=self.history.back()>
		</div>
<?php
		eTDIV();
		eTFORM();
	END_WINDOW();
	break;

case 'AddJrn':
	$cJRN_CODE=$_POST['ADD_JRN_CODE'];
	if($cJRN_CODE==''){
		MSG_INFO(S_MSG('NJ66','Kode jurnal masih kosong'));
		return;
	}
	$cNO_TRANS=$_POST['ADD_NO_TRANS'];
	if($cNO_TRANS==''){
		MSG_INFO(S_MSG('NJ67','No. Trans masih kosong'));
		return;
	}
	$dTG_JURNAL = $_POST['ADD_DATE_TRANS'];		// 'dd/mm/yyyy'
	if($dTG_JURNAL==''){
		MSG_INFO(S_MSG('NJ68','Tanggal jurnal masih kosong'));
		return;
	}
	$qQUERY = OpenTable('TrJrnHdr', "JRN_CODE='$cJRN_CODE' and NO_TRANS='$cNO_TRANS' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if($qQUERY) {
		if(SYS_ROWS($qQUERY)>0){
			MSG_INFO(S_MSG('NJ69','Kode jurnal dan No. trans sudah ada'));
			return;
		}
	}
	$cDATE = substr($dTG_JURNAL,6,4). '-'. substr($dTG_JURNAL,3,2). '-'. substr($dTG_JURNAL,0,2);
	$hRec_id = NowMSecs();

	RecCreate('TrJrnHdr', ['JRN_CODE', 'NO_TRANS', 'DATE_TRANS', 'DESCR', 'ENTRY', 'APP_CODE', 'REC_ID'], 
		[$cJRN_CODE, $cNO_TRANS, $cDATE, $_POST['ADD_DESCR'], $cUSERCODE, $cAPP_CODE, $hRec_id]);

	for ($I=1; $I < 5; $I++) {
		$cIDX = (string)$I;
		$nRec_id = date_create()->format('Uv');
		$nRec_id += $I;
		$cREC_ID=(string)$nRec_id;
		$cACCOUNT = $_POST['ADD_DTL_ACCOUNT_'.$cIDX];
		$cINDEX='ADD_DTL_DESC_'.$cIDX;
		$cDESC = (isset($_POST[$cINDEX]) ? $_POST[$cINDEX] : '');
		$cDEBIT = (isset($_POST['ADD_DTL_DEBIT_'.$cIDX]) ? $_POST['ADD_DTL_DEBIT_'.$cIDX] : 0);
		$nDEBIT = str_replace(',', '', $cDEBIT);
		$nDEBIT = str_replace('.', '', $nDEBIT);
		$cCREDIT = (isset($_POST['ADD_DTL_CREDIT_'.$cIDX]) ? $_POST['ADD_DTL_CREDIT_'.$cIDX] : 0);
		$cCREDIT = str_replace(',', '', $cCREDIT);
		$nCREDIT = str_replace('.', '', $cCREDIT);
		if ($nDEBIT=='')	$nDEBIT=0;
		if ($nCREDIT=='')	$nCREDIT=0;
		if ($cACCOUNT>'') {
			RecCreate('TrJrnDtl', ['HDR_ID', 'ACCOUNT_NO', 'DESCRIPT', 'DEBIT', 'CREDIT', 'APP_CODE', 'ENTRY', 'REC_ID'],
				[$hRec_id, $cACCOUNT, $cDESC, $nDEBIT, $nCREDIT, $cAPP_CODE, $cUSERCODE, $cREC_ID]);
		}
	}
	APP_LOG_ADD($cHEADER, 'add '.$cJRN_CODE.'-'.$cNO_TRANS);
	header('location:gl_journal.php');
	break;

case 'upd_add_dtl':
	$xREC_ID = $_GET['_r'];
	$cJRN = $_GET['_g'];

	$nUPD_DEBIT=0;
	if($_POST['ADD_UPD_DEBIT']!='' ){ $nUPD_DEBIT=$_POST['ADD_UPD_DEBIT']; }
	$nUPD_CREDIT=0;
	if($_POST['ADD_UPD_CREDIT']!='' ){ $nUPD_CREDIT=$_POST['ADD_UPD_CREDIT']; }
	if($_POST['ADD_UPD_ACCOUNT_DN']==''){
		MSG_INFO(S_MSG('NJ80','Kode account masih kosong'));
		header("location:gl_journal.php?_a=".md5('up_d4t3')."&_r=$xREC_ID");
		return;
	}
	if($nUPD_DEBIT==0 && $nUPD_CREDIT==0 ){
		MSG_INFO(S_MSG('NJ81','Jumlah debet atau kredit harus diisi salah satu nya'));
		header("location:gl_journal.php?_a=".md5('up_d4t3')."&_r='$xREC_ID'");
		return;
	}

	$qJRN_HDR = OpenTable('TrJrnHdr', "md5(REC_ID)='$xREC_ID'");
	$aHDR_ID = SYS_FETCH($qJRN_HDR);
	$cHDR_ID = $aHDR_ID['REC_ID'];
	$nDEBIT = "0".str_replace(',', '', $_POST['ADD_UPD_DEBIT']);
	$nCREDIT = "0".str_replace(',', '', $_POST['ADD_UPD_CREDIT']);
	RecCreate('TrJrnDtl', ['HDR_ID', 'ACCOUNT_NO', 'DESCRIPT', 'DEBIT', 'CREDIT', 'ENTRY', 'REC_ID', 'APP_CODE'], 
			[$cHDR_ID, $_POST['ADD_UPD_ACCOUNT_DN'], $_POST['ADD_UPD_DESCRIPT'], $nDEBIT, $nCREDIT, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
	header("location:gl_journal.php?_a=".md5('up_d4t3')."&_r=$xREC_ID");
	break;

case 'upd_upd_dtl':
	$nREC_NO = $_GET['GL_RECN'];
	if($_POST['UPD_UPD_ACCOUNT']==''){
		MSG_INFO(S_MSG('NJ80','Kode account masih kosong'));
		return;
	}
	if($_POST['UPD_UPD_DEBIT']==0 AND $_POST['UPD_UPD_CREDIT']==0){
		MSG_INFO(S_MSG('NJ81','Jumlah debet atau kredit harus diisi salah satu nya'));
		return;
	}

	$qQUERY=Opentable('TrJrnDtl', "REC_ID='$nREC_NO'");
	$aREC_DTL=SYS_FETCH($qQUERY);
	RecUpdate('TrJrnDtl', ['ACCOUNT_NO', 'DESCRIPT', 'DEBIT', 'CREDIT'], 
		[$_POST['UPD_UPD_ACCOUNT'], $_POST['UPD_UPD_DESCRIPT'], "0".str_replace(',', '', $_POST['UPD_UPD_DEBIT']), "0".str_replace(',', '', $_POST['UPD_UPD_CREDIT'])],
		"REC_ID='$nREC_NO'");
	header("location:gl_journal.php?_a=".md5('up_d4t3')."&_r=".md5($aREC_DTL['HDR_ID']));
	break;

case 'rubah':
	$cREC_ID=$_GET['_r'];
	$dTG_JURNAL = $_POST['EDIT_DATE_TRANS'];		// 'dd/mm/yyyy'
	$cDATE = substr($dTG_JURNAL,6,4). '-'. substr($dTG_JURNAL,3,2). '-'. substr($dTG_JURNAL,0,2);
	RecUpdate('TrJrnHdr', ['DESCR', 'DATE_TRANS'], [$_POST['EDIT_DESCR'], $cDATE], "REC_ID='$cREC_ID'");
	$cNEW_ACCOUNT= $_POST['NEW_ACCOUNT'];
	$cDEBIT=str_replace('.', '', $_POST['NEW_DEBIT']);
	$nDEBIT=intval(str_replace(',', '', $cDEBIT));
	$cCREDIT=str_replace('.', '', $_POST['NEW_CREDIT']);
	$nCREDIT=intval(str_replace(',', '', $cCREDIT));
	if($cNEW_ACCOUNT>'' && ($nDEBIT>0 || $nCREDIT>0)) {
		RecCreate('TrJrnDtl', ['HDR_ID', 'ACCOUNT_NO', 'DESCRIPT', 'DEBIT', 'CREDIT', 'ENTRY', 'REC_ID', 'APP_CODE'], 
			[$cREC_ID, $cNEW_ACCOUNT, $_POST['NEW_DESC'], $nDEBIT, $nCREDIT, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
	}
	$qJRN_HDR = OpenTable('TrJrnHdr', "REC_ID='$cREC_ID'");
	if($aJRN_HDR=SYS_FETCH($qJRN_HDR)) {
		APP_LOG_ADD($cHEADER, 'update '.$aJRN_HDR['JRN_CODE'].'-'.$aJRN_HDR['NO_TRANS']);
	}
	header('location:gl_journal.php?_a='.md5('up_d4t3').'&_r='.md5($cREC_ID));
	break;

case 'del_JRNH':
	$cREC_ID=$_GET['_r'];
	$qDTLJ = OpenTable('TrJrnDtl', "HDR_ID='$cREC_ID' and REC_ID not in ( select DEL_ID from logs_delete)");
	while($aREC_JURNAL=SYS_FETCH($qDTLJ)) {
		RecSoftDel($aREC_JURNAL['REC_ID']);
	}
	RecSoftDel($cREC_ID);
	APP_LOG_ADD($cHEADER, 'delete '.$cREC_ID);
	header('location:gl_journal.php');
	break;

case 'del_detail':
	$cREC_ID=$_GET['_jr'];
	$qREC_JOURNAL = OpenTable('TrJrnDtl', "REC_ID='$cREC_ID' and REC_ID not in ( select DEL_ID from logs_delete)");
	$aREC_JOURNALD=SYS_FETCH($qREC_JOURNAL);
	$cHDR_ID=$aREC_JOURNALD['HDR_ID'];
	RecSoftDel($cREC_ID);
	APP_LOG_ADD($cHEADER, 'delete '.$cREC_ID);
	header("location:gl_journal.php?_a=".md5('up_d4t3')."&_r=".md5($cHDR_ID));
	break;
}
?>
