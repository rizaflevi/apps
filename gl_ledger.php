<?php
//	gl_ledger.php //
//	TODO : Total credit

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE	= $_SESSION['gUSERCODE'];
	$sPERIOD1	= $_SESSION['sCURRENT_PERIOD'];
	$cHELP_FILE = 'Doc/Laporan - Buku Besar.pdf';
	$cACTION 	= '';
	if (isset($_GET['_a']))	$cACTION = $_GET['_a'];

	$cYEAR	= substr($sPERIOD1,0,4);
	$cMONTH	= substr($sPERIOD1,5,2);
  
	$cHEADER 		= S_MSG('I020','Laporan Buku Besar');

	$qLEDGER= OpenTable('AccountBalance', "A.GENERAL='D' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4)." and B.BLNC_MONTH=".substr($sPERIOD1,5,2), 'A.ACCOUNT_NO', 'A.ACCOUNT_NO');
	$cKD_ACCOUNT 	= S_MSG('TA21','Kode Account');
	$cACCOUNT		= S_MSG('F028','Account');
	$cKETERANGAN 	= S_MSG('NJ53','Keterangan');
	$cDEBIT 		= S_MSG('NJ54','Debit');
	$cKREDIT 		= S_MSG('NJ55','Kredit');
 	$cSALDO 		= S_MSG('IF23','Saldo');

    $cNO_JRN 		= S_MSG('NJ58','Nmr. Jurnal');
	$cTANGGAL 		= S_MSG('NJ59','Tanggal');
	$cBEG_BAL 		= S_MSG('I006','Awal');
	$cNIL_DEB 		= S_MSG('NJ75','Nilai Debit');
	$cNIL_KRD		= S_MSG('NJ76','Nilai Kredit');
	$cMESSAG1		= S_MSG('F021','Benar data ini mau di hapus ?');
	
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'View');
		$cZERO_BAL_ON_LEDGER=S_PARA('ZERO_BAL_ON_LEDGER','');
		DEF_WINDOW($cHEADER, '', 'prd');
		TFORM($cHEADER, '', [], $cHELP_FILE);
		TDIV();
			TABLE('example');
			THEAD([$cKD_ACCOUNT, $cACCOUNT, $cBEG_BAL, $cDEBIT, $cKREDIT, $cSALDO], '', [0,0,1,1,1,1]);
				echo '<tbody>';
				while($aREC_LEDGER=SYS_FETCH($qLEDGER)) {
					if($cZERO_BAL_ON_LEDGER=='1' || $aREC_LEDGER['BEG_BALANC']<>0 || $aREC_LEDGER['MT_DEBIT']<>0 || $aREC_LEDGER['MT_CREDIT']<>0 || $aREC_LEDGER['CUR_BALANC']<>0) {
						echo '<tr>';
						echo '<td class=""><div class="star"><i class="fa fa-file-text icon-xs icon-default"></i></div></td>';
						echo "<td><span><a href='?_a=".md5('vi_ew')."&_r=".md5($aREC_LEDGER['ACCOUNT_NO'])."'>". $aREC_LEDGER['ACCOUNT_NO']."</a></span></td>";
						echo "<td><span><a href='?_a=".md5('vi_ew')."&_r=".md5($aREC_LEDGER['ACCOUNT_NO'])."'>". DECODE($aREC_LEDGER['ACCT_NAME'])."</a></span></td>";
						echo '<td align="right">'.CVR($aREC_LEDGER['BEG_BALANC']).'</td>';
						echo '<td align="right">'.CVR($aREC_LEDGER['MT_DEBIT']).'</td>';
						echo '<td align="right">'.CVR($aREC_LEDGER['MT_CREDIT']).'</td>';
						$nSALDO = $aREC_LEDGER['BEG_BALANC'] + (str_contains('15', $aREC_LEDGER['TYPE_ACCT']) ? $aREC_LEDGER['MT_DEBIT'] - $aREC_LEDGER['MT_CREDIT'] : $aREC_LEDGER['MT_CREDIT'] - $aREC_LEDGER['MT_DEBIT']) ;
						echo '<td align="right">'.CVR($nSALDO).'</td>';
						echo '</tr>';
					}
				}
				echo '</tbody>';
			eTABLE();
		eTDIV();
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('vi_ew'):
		$cACCOUNT		= $_GET['_r'];		// md5
		$cHEADER 		= S_MSG('I023','Rincian Buku Besar');
		APP_LOG_ADD($cHEADER, 'View');
		$qACCT			= OpenTable('TbAccount', "md5(ACCOUNT_NO)='$cACCOUNT' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if($aACCT=SYS_FETCH($qACCT)) ;
		else MSG_INFO('Invald account');

		$nBEG_BAL = 0;
		$qBEG			= OpenTable('BalanceHdr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and BLNC_YEAR='$cYEAR' and BLNC_MONTH='$cMONTH' and md5(ACCOUNT_NO)='$cACCOUNT'");
		if($aBEG	= SYS_FETCH($qBEG)) $nBEG_BAL = $aBEG['BEG_BALANC'];
		DEF_WINDOW($cHEADER);
			$aACT = (TRUST($cUSERCODE, 'GLBB_EXCEL') ? ['<a href="gl_ledger_xls.php?_r='. $cACCOUNT. '"><i class="fa fa-solid fa-file-excel"></i>&nbsp excel</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE, '', DECODE($aACCT['ACCT_NAME']));
				TDIV();
					TABLE('example');
						THEAD([$cTANGGAL, DECODE($cDEBIT), $cKREDIT], '', [0,0, 1,1]);
							echo '<tbody>';
								$tDEBIT=$tKREDIT=$nBEG_DEBIT=$nBEG_KREDIT=$tEND_DEBIT=$tEND_CREDIT= 0;
								if(str_contains('15', $aACCT['TYPE_ACCT']))
									$nBEG_DEBIT=$nBEG_BAL;
								else	$nBEG_KREDIT=$nBEG_BAL;
								if($nBEG_DEBIT<0)	{
									$nBEG_KREDIT=abs($nBEG_DEBIT);	
									$nBEG_DEBIT=0;	
								}
								if($nBEG_KREDIT<0)	{
									$nBEG_DEBIT=abs($nBEG_KREDIT);	
									$nBEG_KREDIT=0;	
								}
								echo '<tr><td>Saldo Awal</td><td></td>';
									echo '<td align="right">'.CVR($nBEG_DEBIT).'</td>';
									echo '<td align="right">'.CVR($nBEG_KREDIT).'</td>';
									echo '<tr></tr>';
									echo '</tr>';
								$qDLEDGER = OpenTable('PrLedger', "md5(ACCOUNT_NO)='$cACCOUNT' and year(DATE_TRANS)=".substr($sPERIOD1,0,4)." and month(DATE_TRANS)=".substr($sPERIOD1,5,2)." and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'DATE_TRANS, REFF');
								while($aREC_DLEDGER=SYS_FETCH($qDLEDGER)) {
									$nDEBIT=$aREC_DLEDGER['DEBIT'];	$nKREDIT=$aREC_DLEDGER['CREDIT'];
									echo '<tr>';
										echo '<td>'. $aREC_DLEDGER['DATE_TRANS'].'</td>';
										echo '<td>'. DECODE($aREC_DLEDGER['DESCRIPT']).'</td>';
										echo '<td align="right">'.CVR($nDEBIT).'</td>';
										echo '<td align="right">'.CVR($nKREDIT).'</td>';
									echo '</tr>';
									$tDEBIT+=$nDEBIT;	$tKREDIT+=$nKREDIT;
								}
								if(str_contains('15', $aACCT['TYPE_ACCT'])) {
									$tEND_DEBIT = $nBEG_DEBIT + $tDEBIT - $tKREDIT;
									$tEND_CREDIT = 0;
									if($tEND_DEBIT<0) {
										$tEND_CREDIT = abs($tEND_DEBIT);
										$tEND_DEBIT = 0;
									}
								} else	{
									$tEND_DEBIT = 0;
									$tEND_CREDIT = $nBEG_KREDIT + $tKREDIT - $tDEBIT;
									if($tEND_CREDIT<0) {
										$tEND_DEBIT = abs($tEND_CREDIT);
										$tEND_CREDIT = 0;
									}
								}
								echo '<td></td><td></td><td></td><td></td>';
								echo '<tr></tr>';
								echo '<tr>';
									echo '<td>'.S_MSG('TA49','Saldo Akhir').'</td>';
									echo '<td></td>';
									echo '<td align="right">'.CVR($tEND_DEBIT).'</td>';
									echo '<td align="right">'.CVR($tEND_CREDIT).'</td>';
								echo '</tr>';
							echo '</tbody>';
					eTABLE();
?>
					<div class="text-left">
						<input type="button" class="btn" value=<?php echo $cCLOSE?> onclick=self.history.back()>
					</div>
<?php
				eTDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;
}
?>
