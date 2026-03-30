<?php
//	rep_cash_daily.php //
// TODO : convert to excel

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 	= $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Laporan - Arus Kas.pdf';
	$cHEADER 	= S_MSG('RK00','Laporan Mutasi Kas Harian');
	$ADD_LOG	= APP_LOG_ADD($cHEADER);
	$can_PRINT  = TRUST($cUSERCODE, 'KAS_HRN_PRINT');
  
	$cTANGGAL 	= S_MSG('RS02','Tanggal');
	$cNO_BUKTI 	= S_MSG('RK04','No. Bukti');
	$cKET		= S_MSG('RK05','Keterangan');
	$cDEBIT		  = S_MSG('RK06','Debit');
	$cKREDIT	= S_MSG('RK07','Kredit');
	$cSALDO		= S_MSG('I007','Saldo Akhir');
	$cACCOUNT 	= S_MSG('NR07','Account');
	
	$cACTION    = '';
	if (isset($_GET['action']))	$cACTION = $_GET['action'];

	$sPERIOD1=$dPERIOD2=date('Y-m-d');

	if (isset($_GET['_d1'])) $sPERIOD1=$_GET['_d1'];

	$cYEAR	= substr($sPERIOD1,0,4);
	$cMONTH	= substr($sPERIOD1, 5 ,2);
	$cCASH_ACCT = GET_IFACE('CASH');
    $nBEG_BAL = 0;
	$qBEG_BAL = OpenTable('BalanceHdr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and BLNC_YEAR='$cYEAR' and BLNC_MONTH='$cMONTH' and ACCOUNT_NO='$cCASH_ACCT'");
    if ($aREC_BAL=SYS_FETCH($qBEG_BAL))     $nBEG_BAL = $aREC_BAL['BEG_BALANC'];

    RecDelete('RepAbsen', "APP_CODE='$cUSERCODE'");

    $qQUERY=OpenTable('TrReceiptDHdr', "B.BANK='' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and B.TGL_BAYAR<=".$sPERIOD1);
    while($aREC_TRM=SYS_FETCH($qQUERY)) {
		if($aREC_TRM['TGL_BAYAR']!=$sPERIOD1) {
			$nBEG_BAL+=$aREC_TRM['NILAI'];
		} else {
			$cACCT = '';
            $qACCOUNT=OpenTable('TbAccount', "ACCOUNT_NO='$aREC_TRM[ACCOUNT]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
            if(SYS_ROWS($qACCOUNT)>0) {
                $aACCOUNT = SYS_FETCH($qACCOUNT);
                $cACCT = $aACCOUNT['ACCT_NAME'];
            }
            RecCreate('RepAbsen', ['PRSON_CODE', 'ABSN_DATE', 'PRSON_NAME', 'CUST_CODE', 'CUST_NAME', 'LATITUDE', 'APP_CODE'], [$aREC_TRM['NO_TRM'], $aREC_TRM['TGL_BAYAR'], $aREC_TRM['DESCRP'], $aREC_TRM['ACCOUNT'], $cACCT, $aREC_TRM['NILAI'], $cUSERCODE]);
        }
    }
	$qQUERY=OpenTable('TrPaymentDHdr', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and B.BDV_DATE<='$sPERIOD1'");
    while($aREC_PAY=SYS_FETCH($qQUERY)) {
		$cACCT = '';
		if($aREC_PAY['BDV_DATE']<$sPERIOD1) {
			$nBEG_BAL-=$aREC_PAY['BDV_DAM'];
		} else {
			$qACCOUNT=OpenTable('TbAccount', "ACCOUNT_NO='$aREC_PAY[BDV_REFF]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
			if(SYS_ROWS($qACCOUNT)>0) {
				$aACCOUNT = SYS_FETCH($qACCOUNT);
				$cACCT = $aACCOUNT['ACCT_NAME'];
			}
		}
		RecCreate('RepAbsen', ['PRSON_CODE', 'ABSN_DATE', 'PRSON_NAME', 'CUST_CODE', 'CUST_NAME', 'LONGITUDE', 'APP_CODE'], [$aREC_PAY['BDV_NO'], $aREC_PAY['BDV_DATE'], $aREC_PAY['BDV_DESC'], $aREC_PAY['BDV_REFF'], $cACCT, $aREC_PAY['BDV_DAM'], $cUSERCODE]);
	}
	$qQUERY=OpenTable('RepAbsen', "APP_CODE='$cUSERCODE' and ABSN_DATE='$sPERIOD1'", '', 'ABSN_DATE');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	DEF_WINDOW($cHEADER, 'collapse');
	$aACT = (TRUST($cUSERCODE, 'KAS_HRN_EXCEL')==1 ? ['<a href="rep_cash_daily_xl.php?_d1='.$dPERIOD1.'&_d2='.$dPERIOD2.'"><i class="fa fa-file-excel-o"></i>Excel</a>'] : []);
	TFORM($cHEADER, '', [], $cHELP_FILE, '*');
	TDIV();
?>
		<label class="col-sm-1 form-label-700" for="field-4"><?php echo S_MSG('RS02','Tanggal')?></label>
		<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $sPERIOD1?>" onchange="SELECT_DATE(this.value)">
		<?php 
			TABLE('example');
			THEAD([$cTANGGAL, $cNO_BUKTI, $cKET, $cACCOUNT, $cDEBIT, $cKREDIT, $cSALDO], '', [0,0,0,0,1,1,1])
		?>
			<tbody>
				<?php
					$nT_DEBET = 0;	$nT_KREDIT = 0;	$nJUMLAH = 0;
					echo '<tr>';
						echo '<td></td>';
						echo '<td>Saldo Awal</td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td align="right">'.CVR($nBEG_BAL).'</td>';
					echo '</tr>';
					while($aREC_CASH=SYS_FETCH($qQUERY)) {
						$nJUMLAH += $aREC_CASH['LATITUDE'] - $aREC_CASH['LONGITUDE'];
						echo '<tr>';
							$cICON = 'fa fa-money';
							echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
							echo '<td>'.date("d-M-Y", strtotime($aREC_CASH['ABSN_DATE'])).'</td>';
							echo "<td>". $aREC_CASH['PRSON_CODE']."</a></span></td>";
							echo '<td>'.$aREC_CASH['PRSON_NAME'].'</td>';
							echo '<td>'.decode_string($aREC_CASH['CUST_NAME']).'</td>';
							echo '<td align="right">'.CVR($aREC_CASH['LATITUDE']).'</td>';
							echo '<td align="right">'.CVR($aREC_CASH['LONGITUDE']).'</td>';
							echo '<td align="right">'.CVR($nBEG_BAL+$nJUMLAH).'</td>';
							$nT_DEBET += $aREC_CASH['LATITUDE'];
							$nT_KREDIT += $aREC_CASH['LONGITUDE'];
						echo '</tr>';
					}
					TTOTAL(['Total', '', '', '', CVR($nT_DEBET), CVR($nT_KREDIT), CVR($nBEG_BAL+$nJUMLAH)], [0,0,0,0,1,1,1]);
				?>
			</tbody>
<?php
			eTABLE();
		eTDIV();
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
?>

<script>

function SELECT_DATE(TGL_1) {
	window.location.assign("rep_cash_daily.php?_d1="+TGL_1);
}

</script>

