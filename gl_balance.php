<?php
//	gl_balance.php
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHELP_FILE = 'Doc/Inquery - Saldo Harian.pdf';
	$cHEADER	= S_MSG('I015','Laporan Saldo Keuangan Harian');

	APP_LOG_ADD($cHEADER, 'gl_balance.php');

	$cBEG_BALANCE = S_MSG('I018','Saldo Awal');

	$sPERIOD1	= $_SESSION['sCURRENT_PERIOD'];
	if (isset($_GET['_p']))	$sPERIOD1 = $_GET['_p'];
	$dTO_DAY=date('Y-m-d');
	if ($dTO_DAY>date($sPERIOD1.'-t'))	$dTO_DAY=date($sPERIOD1.'-t');
	$nTGL1 = strval(substr($dTO_DAY,8,2));

	$qQUERY = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4)." and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'A.ACCOUNT_NO');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	DEF_WINDOW($cHEADER, '', 'prd');
    	TFORM($cHEADER, "?_p=1", [], $cHELP_FILE, '*');
			TDIV();
				TABLE('example');
?>
					<thead>
						<tr>
							<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>	
							<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo S_MSG('TA21','Kode Account')?></th>
							<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo S_MSG('TA23','Nama Account')?></th>
							<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo S_MSG('IF23','Saldo')?></th>
							<?php
								for ($I = $nTGL1; $I > 0; $I--) {
									echo '<th style="'.$cHDR_BACK_CLR.';">Balance tgl'.$I.'</th>';
								}
							?>
							<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cBEG_BALANCE?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							while($aREC_ACCOUNT=SYS_FETCH($qQUERY)) {
								$cSTRONG='';	$eSTRONG='';
								if($aREC_ACCOUNT['GENERAL']=='G')	{
									$cSTRONG='<strong>';
									$eSTRONG='</strong>';
								}
								echo '<tr>';
									echo '<td style="width: 1px;"></td>';
									echo "<td><span>".str_pad($aREC_ACCOUNT['ACCOUNT_NO'],  6, "  .")."  </span></td>";
									echo "<td><span>".$cSTRONG.decode_string($aREC_ACCOUNT['ACCT_NAME']).$eSTRONG." </span></td>";
									echo '<td align="right"><span>'.CVR($aREC_ACCOUNT['CUR_BALANC']).' </span></td>';
									for ($I = $nTGL1; $I > 0; $I--) {
										$nBL = 0;
										$qDAILY = OpenTable('BalanceDtl', "ACCOUNT_NO='$aREC_ACCOUNT[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and BLNC_DAY='".substr($sPERIOD1,0,7)."-".$I."'");
										if($qDAILY) {
											if (SYS_ROWS($qDAILY)) {
												$aDAILY = SYS_FETCH($qDAILY);
												$nBL = $aDAILY['VAL_BALANC'];
											}
										}
										echo '<td align="right">'.CVR($nBL).'</td>';
									}
									echo '<td align="right"><span>'.CVR($aREC_ACCOUNT['BEG_BALANC']).' </span></td>';
								echo '</tr>';
							}
						?>
					</tbody>
<?php
				eTABLE();
			eTDIV();
		eTFORM('*');
	END_WINDOW();
	?>
<script>
function SET_PERIOD(pPRD) {
	window.location.assign("?_p=" + pPRD);
}

</script>

