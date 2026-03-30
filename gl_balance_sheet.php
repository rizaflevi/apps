<?php
//	gl_balance_sheet.php
//	laporan neraca
//	TODO : lengkapi CALK

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
$cUSERCODE 	= $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Laporan - Neraca.pdf';
if(IS_SCHOOL($cAPP_CODE))	$cHELP_FILE = 'Doc/Laporan - Keuangan.pdf';

$sPERIOD1	= $_SESSION['sCURRENT_PERIOD'];
$can_LYEAR 	= TRUST($cUSERCODE, 'NERACA_LYEAR');
$can_CALK 	= TRUST($cUSERCODE, 'NERACA_CALK');
$can_NPRINT = TRUST($cUSERCODE, 'NERACA_PRINT');
$can_NRC_EXCEL = TRUST($cUSERCODE, 'NERACA_EXCEL');
$can_RL_EXCEL = TRUST($cUSERCODE, 'RUGILABA_EXCEL');
$can_RPRINT = TRUST($cUSERCODE, 'RUGILABA_PRINT');
$can_UPDATE = TRUST($cUSERCODE, 'TB_CALK_2UPD');
$can_CALK_EXCEL = TRUST($cUSERCODE, 'CALK_EXCEL');
$dBOY= new DateTime(substr($sPERIOD1,0,5).'01-01');
$dEOY=date_modify($dBOY,'-1 day');
$cEOY=$dEOY->format('Y-m-d');
$cLAST_YEAR=substr($cEOY,0,4);
$cLAST_MONTH=substr($cEOY,5,2);

$cKODE_ACCOUNT = S_MSG('NP34','Account');
$cNAMA_ACCOUNT = S_MSG('TA23','Nama Account');

$sPERIOD1=$_SESSION['sCURRENT_PERIOD'];
if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];

$cHEADER		= S_MSG('RF05', 'Laporan Keuangan');
$tTYPE='';
if (isset($_GET['_r'])) 	$tTYPE=$_GET['_r'];

$cRIGHT_ALIGN 	= S_PARA('FIN_REP_RIGHT_ALIGN', '');
$cSALDO_NOL		= S_PARA('ZERO_PRINT', 'N');

$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
/*
$qACCOUNT= OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
while($aACCOUNT= SYS_FETCH($qACCOUNT)) {
	$qBALN = OpenTable('BalanceHdr', "APP_CODE='$cAPP_CODE' and ACCOUNT_NO='$aACCOUNT[ACCOUNT_NO]' and REC_ID not in ( select DEL_ID from logs_delete) and BLNC_YEAR=".substr($sPERIOD1,0,4). " and BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');
	if($qBALN) {
		if(SYS_ROWS($qBALN)==0) {
			RecCreate('BalanceHdr', ['ACCOUNT_NO', 'BLNC_YEAR', 'BLNC_MONTH', 'APP_CODE', 'REC_ID'], 
				[$aACCOUNT['ACCOUNT_NO'], substr($sPERIOD1,0,4), substr($sPERIOD1,5,2), $cAPP_CODE, NowMSecs()]);
		}
	}
}
*/

switch($tTYPE){
	default:
		break;
	
	case 'PROFIT':
			$cHEADER		= S_MSG('RF03', 'LAPORAN LABA / RUGI');
			$cQUERY = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2)." and A.TYPE_ACCT in ('4', '5')");
			APP_LOG_ADD($cHEADER, $cHEADER, 'view');
			break;
			
	case md5('to_excel'):
		APP_LOG_ADD($cHEADER, 'excel');
		require_once("gl_balance_sheet_excel.php");	
		break;
	case 'lr_print':
		APP_LOG_ADD($cHEADER, 'print L/R');
		$_SESSION['REP_TYPE']     = 'LABARUGI';
		require_once("gl_balance_sheet_print.php");
		break;
	case 'nrc_print':
		APP_LOG_ADD($cHEADER, 'print Neraca');
		$_SESSION['REP_TYPE']     = 'NERACA';
		require_once("gl_balance_sheet_print.php");	
		break;
}
	DEF_WINDOW($cHEADER, 'collapse', 'prd');
		$aACT = ($can_NRC_EXCEL==1 ? ['<a href="?_r='. md5('to_excel'). '"><i class="fa fa-file-excel-o"></i>Excel</a>'] : []);
		TFORM($cHEADER, '', $aACT, $cHELP_FILE, '*');
			TDIV();
?>
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#neraca" data-toggle="tab">
							<i class="fa fa-home"></i> <?php echo S_MSG('RF02', 'Neraca')?>
						</a>
					</li>
					<li>
						<a href="#labarugi" data-toggle="tab">
							<i class="fa fa-angle-double-down"></i> <?php echo S_MSG('RF03', 'LabaRugi')?>
						</a>
					</li>
					<?php
						if ($can_CALK==1) {
							echo '<li>
								<a href="#calk_tab" data-toggle="tab">
									<i class="fa fa-pencil-square-o"></i>'. S_MSG('RF07', 'Catatan Atas Laporan Keuangan');
								echo '</a>
							</li>';
						}
					?>
				</ul>
				<div class="tab-content primary">
					<div class="tab-pane fade in active" id="neraca">
<?php
						$nTTL_PASIVA = $nLAST_PASIVA=0;
						$aJUMLAH = array(1=> S_MSG('TA33', 'AKTIVA'), S_MSG('TA34', 'PASIVA'), S_MSG('TA35', 'MODAL'));
						for($N = 1; $N<=3; $N++):
							$cTYPE=(string)$N;
							$qNERACA = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and A.LEVEL='1' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2));
							while($aNERACA = SYS_FETCH($qNERACA)) {
								if($can_LYEAR) {
									$nLAST_TOTAL = 0;
									$qLYEAR = OpenTable('BalanceLHdr', "ACCOUNT_NO='$aNERACA[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
									if ($aLAST = SYS_FETCH($qLYEAR))	$nLAST_TOTAL=$aLAST['CUR_BALANC'];
								}
								echo '<section class="box">
									<header class="panel_header">
										<h2 class="title pull-left">'.$aNERACA['ACCT_NAME'].'</h2>
										<div class="actions panel_actions pull-right">
											<i class="box_toggle fa fa-chevron-down"></i>
											<i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
										</div>
									</header>';
									echo '<div class="content-body">
										<div class="row">';
											TDIV();
												$qNRC2 = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and GEN_ACCT='$aNERACA[ACCOUNT_NO]' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');
												while($aNRC2 = SYS_FETCH($qNRC2)) {
													$nLAST2 = 0;
													if($can_LYEAR) {
														$qLYEAR = OpenTable('BalanceLHdr', "ACCOUNT_NO='$aNRC2[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
														if ($aLAST = SYS_FETCH($qLYEAR))	$nLAST2=$aLAST['CUR_BALANC'];
													}
													echo '<label class="col-lg-9"><h3>'.decode_string($aNRC2['ACCT_NAME']).'</h3></label>';
													echo '<div class="col-lg-1">';
													$cNILAI = '<div class="pull-right"';
													echo $cNILAI.'><h3>'.CVR($aNRC2['CUR_BALANC']).'</h3></div></div>';
													if($can_LYEAR)
														echo '<div class="col-lg-2 col-xs-3 form-label-900" style="font-size: 18px;color: Black;background-color: White ;" align="right">'.CVR($nLAST2).'</div>';
													echo '<div class="clearfix"></div>';

													$qNRC3 = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and GEN_ACCT='$aNRC2[ACCOUNT_NO]' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');
													while($aNRC3 = SYS_FETCH($qNRC3)) {
														$nLAST3 = 0;
														$qLYEAR3 = OpenTable('BalanceLHdr', "ACCOUNT_NO='$aNRC3[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
														if ($aLAST3 = SYS_FETCH($qLYEAR3))	$nLAST3=$aLAST3['CUR_BALANC'];
														echo '<ul>';
															if($cSALDO_NOL=='Y' || $aNRC3['CUR_BALANC']<>'0' || $nLAST3<>0 || $aNRC3['ZERO_PRINT']==1) {
																echo '<label class="col-lg-'.($cRIGHT_ALIGN=='1' ? '8' : '7').' col-xs-12 "><h4>'.decode_string($aNRC3['ACCT_NAME']).'</h4></label>';
																echo '<label class="col-lg-2 col-xs-6" align="right"><h4>'.CVR($aNRC3['CUR_BALANC']).'</h4></label>';
																if($can_LYEAR)
																	echo '<div class="col-lg-'.($cRIGHT_ALIGN=='1' ? '2' : '3').' col-xs-6 form-label-900" style="font-size: 18px;color: Black;background-color: White;" align="right">'.CVR($nLAST3).'</div>';
															}
															$qNRC4 = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and GEN_ACCT='$aNRC3[ACCOUNT_NO]' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');
															while($aNRC4 = SYS_FETCH($qNRC4)) {
																$nLAST4 = 0;
																$qLYEAR4 = OpenTable('BalanceLHdr', "ACCOUNT_NO='$aNRC4[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
																if ($aLAST4 = SYS_FETCH($qLYEAR4))	$nLAST4=$aLAST4['CUR_BALANC'];
																echo '<ul>';
																	if($cSALDO_NOL=='Y' || $aNRC4['CUR_BALANC']<>'0' || $nLAST4<>0 || $aNRC4['ZERO_PRINT']==1) {
																		echo '<label class="col-lg-'.($cRIGHT_ALIGN=='1' ? '8' : '6').' col-xs-12"><h5>'.decode_string($aNRC4['ACCT_NAME']).'</h5></label>';
																		echo '<label class="col-lg-2" align="right"><h5>'.CVR($aNRC4['CUR_BALANC']).'</h5></label>';
																		if($can_LYEAR)
																			echo '<div class="col-lg-'.($cRIGHT_ALIGN=='1' ? '2' : '4').' col-xs-3 form-label-900" style="font-size: 18px;color: Black;background-color: White;" align="right">'.CVR($nLAST4).'</div>';
																	}
																	$qNRC5 = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and GEN_ACCT='$aNRC4[ACCOUNT_NO]' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');
																	while($aNRC5 = SYS_FETCH($qNRC5)) {
																		$nLAST5 = 0;
																		$qLYEAR5 = OpenTable('BalanceHdr', "ACCOUNT_NO='$aNRC5[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
																		if ($aLAST5 = SYS_FETCH($qLYEAR5))	$nLAST5=$aLAST5['CUR_BALANC'];
																		echo '<ul>';
																			if($cSALDO_NOL=='Y' || $aNRC5['CUR_BALANC']<>'0' || $nLAST5<>0 || $aNRC5['ZERO_PRINT']==1) {
																				echo '<label class="col-lg-'.($cRIGHT_ALIGN=='1' ? '8' : '5').' col-xs-12 form-label-300">'.decode_string($aNRC5['ACCT_NAME']).'</label>';
																				echo '<label class="col-lg-2 col-lg-6 form-label-300" align="right"><h5>'.CVR($aNRC5['CUR_BALANC']).'</h5></label>';
																				if($can_LYEAR)
																					echo '<div class="col-lg-'.($cRIGHT_ALIGN=='1' ? '2' : '5').' col-xs-6 form-label-900" style="font-size: 18px;color: Black;background-color: White;" align="right">'.CVR($nLAST5).'</div>';
																			}
																		echo '</ul>';
																	}
																echo '</ul>';
															}
														echo '</ul>';
													}
												}
												CLEAR_FIX();
												echo '<label class="col-lg-8 col-xs-8 form-label-700" style="font-size: 24px;color: Black;background-color: LightGray ;">'. 'Jumlah : '.$aJUMLAH[$N].'</label>';
												echo '<label class="col-lg-2 col-xs-3 form-label-700" style="font-size: 24px;color: Black;background-color: LightGray ;" align="right">'. CVR($aNERACA['CUR_BALANC']).'</label>';
												if($cTYPE!='1')	{
													$nTTL_PASIVA += $aNERACA['CUR_BALANC'];
													if($can_LYEAR)	$nLAST_PASIVA += $nLAST_TOTAL;
												}
												if($can_LYEAR)
													echo '<label class="col-lg-2 col-xs-3" style="font-size: 24px;color: Black;background-color: LightGray ;" align="right">'. CVR($nLAST_TOTAL).'</label>';
									echo '</div></div></div>';
								echo '</section>';
							}	
						endfor;
						echo '<div class="clearfix"></div><br>';
						echo '<label class="col-lg-8 col-xs-8 form-label-700" style="font-size: 24px;color: Black;background-color: LightGray ;">'. 'Jumlah : '.$aJUMLAH[2].' & '.$aJUMLAH[3].'</label>';
						echo '<label class="col-lg-2 col-xs-3 form-label-700" style="font-size: 24px;color: Black;background-color: LightGray ;" align="right">'. CVR($nTTL_PASIVA).'</label>';
						if($can_LYEAR)
							echo '<label class="col-lg-2 col-xs-3" style="font-size: 24px;color: Black;background-color: LightGray ;" align="right">'. CVR($nLAST_PASIVA).'</label>';
						echo '<div class="text-left">';
							if($can_NPRINT)	echo "<button type='button' class='btn btn-success btn-icon' onclick=window.location.href='?_r=nrc_print'>Print</button>"; 
?>
					</div>
				</div>
				<div class="tab-pane fade" id="labarugi">
<?php
					$aJUMLAH = array(4=> S_MSG('TA36', 'PENDAPATAN'), S_MSG('TA37', 'BIAYA'));
					for($R = 4; $R<=5; $R++):
						$cTYPE=(string)$R;
						$qRL = OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and TYPE_ACCT='$cTYPE' and LEVEL='1' and REC_ID not in ( select DEL_ID from logs_delete)");
						$aNERACA = SYS_FETCH($qRL);
						$nTOTAL = $nLAST_TOTAL = 0;
						if ($aNERACA>0) {
							$qBLNS = OpenTable('BalanceHdr', "APP_CODE='$cAPP_CODE' and ACCOUNT_NO='$aNERACA[ACCOUNT_NO]' and REC_ID not in ( select DEL_ID from logs_delete) and BLNC_YEAR=".substr($sPERIOD1,0,4). " and BLNC_MONTH=".substr($sPERIOD1,5,2));
							$aTTL = SYS_FETCH($qBLNS);
							if ($aTTL>0) $nTOTAL = $aTTL['CUR_BALANC'];
							$qLYEAR = OpenTable('BalanceHdr', "ACCOUNT_NO='$aNERACA[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
							$aLAST = SYS_FETCH($qLYEAR);
							if ($aLAST>0)	$nLAST_TOTAL=$aLAST['CUR_BALANC'];
							echo '<section class="box">
								<header class="panel_header">
									<h2 class="title pull-left">'.$aNERACA['ACCT_NAME'].'</h2>
									<div class="actions panel_actions pull-right">
										<i class="box_toggle fa fa-chevron-down"></i>
									</div>
								</header>';
								echo '<div class="content-body">
									<div class="row">';
										TDIV();
											$qNRC2 = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and GEN_ACCT='$aNERACA[ACCOUNT_NO]' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');
											while($aNRC2 = SYS_FETCH($qNRC2)) {
												$qLYEAR = OpenTable('BalanceHdr', "ACCOUNT_NO='$aNRC2[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
												$aLAST = SYS_FETCH($qLYEAR);
												$nLAST2 = ($aLAST>0 ? $nLAST2=$aLAST['CUR_BALANC'] : 0);
												echo '<label class="col-lg-9"><h3>'.$aNRC2['ACCT_NAME'].'</h3></label>';
												echo '<div class="col-lg-1">';
												$cNILAI = '<div class="pull-right"';
												if ($aNRC2['ACCT_NAME']=='G') {
													$cNILAI .= ' style="background:#bbb8b8; align=right"';
												}
												echo $cNILAI.'><h3>'.CVR($aNRC2['CUR_BALANC']).'</h3></div></div>';
												if($can_LYEAR)
													echo '<div class="col-lg-2 col-xs-3 form-label-900" style="font-size: 18px;color: Black;background-color: White ;" align="right">'.CVR($nLAST2).'</div>';
												echo '<div class="clearfix"></div>';

												$qNRC3 = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and GEN_ACCT='$aNRC2[ACCOUNT_NO]' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');
												while($aNRC3 = SYS_FETCH($qNRC3)) {
													$nLAST3 = 0;
													$qLYEAR3 = OpenTable('BalanceHdr', "ACCOUNT_NO='$aNRC3[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
													$aLAST3 = SYS_FETCH($qLYEAR3);
													if ($aLAST3>0)	$nLAST3=$aLAST3['CUR_BALANC'];
														echo '<ul>';
														if($cSALDO_NOL=='Y' || $aNRC3['CUR_BALANC']<>'0' || $nLAST3<>0 || $aNRC3['ZERO_PRINT']==1) {
															echo '<label class="col-lg-'.($cRIGHT_ALIGN=='1' ? '8' : '7').'"><h4>'.$aNRC3['ACCT_NAME'].'</h4></label>';
															echo '<label class="col-lg-2" align="right"><h4>'.CVR($aNRC3['CUR_BALANC']).'</h4></label>';
															if($can_LYEAR)
																echo '<div class="col-lg-'.($cRIGHT_ALIGN=='1' ? '2' : '3').' col-xs-3 form-label-900" style="font-size: 18px;color: Black;background-color: White;" align="right">'.CVR($nLAST3).'</div>';
														}
														$qNRC4 = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and GEN_ACCT='$aNRC3[ACCOUNT_NO]' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');
														while($aNRC4 = SYS_FETCH($qNRC4)) {
															$nLAST4 = 0;
															$qLYEAR4 = OpenTable('BalanceHdr', "ACCOUNT_NO='$aNRC4[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
															$aLAST4 = SYS_FETCH($qLYEAR4);
															if ($aLAST4>0)	$nLAST3=$aLAST4['CUR_BALANC'];
															echo '<ul>';
															if($cSALDO_NOL=='Y' || $aNRC4['CUR_BALANC']<>'0' || $nLAST4>0 || $aNRC4['ZERO_PRINT']==1) {
																echo '<label class="col-lg-'.($cRIGHT_ALIGN=='1' ? '8' : '6').'"><h5>'.decode_string($aNRC4['ACCT_NAME']).'</h5></label>';
																echo '<label class="col-lg-2" align="right"><h5>'.CVR($aNRC4['CUR_BALANC']).'</h5></label>';
																if($can_LYEAR)
																	echo '<div class="col-lg-'.($cRIGHT_ALIGN=='1' ? '2' : '4').' col-xs-3 form-label-900" style="font-size: 18px;color: Black;background-color: White;" align="right">'.CVR($nLAST4).'</div>';
															}
																$qNRC5 = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.TYPE_ACCT='$cTYPE' and GEN_ACCT='$aNRC4[ACCOUNT_NO]' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2), '', 'ACCOUNT_NO');
																	while($aNRC5 = SYS_FETCH($qNRC5)) {
																		$nLAST5 = 0;
																		$qLYEAR5 = OpenTable('BalanceHdr', "ACCOUNT_NO='$aNRC4[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cLAST_YEAR and BLNC_MONTH= $cLAST_MONTH");
																		$aLAST5 = SYS_FETCH($qLYEAR5);
																		if ($aLAST5>0)	$nLAST3=$aLAST5['CUR_BALANC'];
																		echo '<ul>';
																			if($cSALDO_NOL=='Y' || $aNRC5['CUR_BALANC']<>'0' || $nLAST5>0 || $aNRC5['ZERO_PRINT']==1) {
																				echo '<label class="col-lg-'.($cRIGHT_ALIGN=='1' ? '8' : '5').' form-label-300">'.$aNRC5['ACCT_NAME'].'</label>';
																				echo '<label class="col-lg-2 form-label-300" align="right">'.$aNRC5['CUR_BALANC'].'</label>';
																				if($can_LYEAR)
																					echo '<div class="col-lg-'.($cRIGHT_ALIGN=='1' ? '2' : '5').' col-xs-3 form-label-900" style="font-size: 18px;color: Black;background-color: White;" align="right">'.CVR($nLAST5).'</div>';
																			}
																		echo '</ul>';
																	}
															echo '</ul>';
														}
													echo '</ul>';
												}
											}
											CLEAR_FIX();
											echo '<label class="col-lg-8 col-xs-8 form-label-700" style="font-size: 24px;color: Black;background-color: LightGray ;">'. 'Jumlah : '.$aJUMLAH[$R].'</label>';
											echo '<label class="col-lg-2 col-xs-3 form-label-700" style="font-size: 24px;color: Black;background-color: LightGray ;" align="right">'. CVR($nTOTAL).'</label>';
											if($can_LYEAR)
												echo '<label class="col-lg-2 col-xs-3" style="font-size: 24px;color: Black;background-color: LightGray ;" align="right">'. CVR($nLAST_TOTAL).'</label>';
										echo '</div></div></div>';
									echo '</section>';
						}
					endfor;
					if($can_NPRINT==1)	echo "<button type='submit' formtarget='_blank' class='btn btn-success btn-icon' onclick=window.location.href='?_r=lr_print'>Print</button>"; 
?>
					</div>
					<div class="tab-pane fade" id="calk_tab">
						<form action ="?_a=calk" method="post">
							<?php
								$qCALK = OpenTable('TbCalk', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete )", '', 'A.CODE');
								while($aCALK = SYS_FETCH($qCALK)) {
									if($aCALK['UL']>'')	echo '<ul>';
									if($aCALK['UL']>'1')	echo '<ul>';
									if($aCALK['UL']>'2')	echo '<ul>';
									CALK_INPUT($aCALK['TY_PE'], $aCALK['LG'], $aCALK['MD'], $aCALK['SM'], $aCALK['XS'], $aCALK['FMT'], $aCALK['CODE'], $aCALK['BORDER'], $aCALK['ALIGN'], $aCALK['FONT_NAME'], $aCALK['FONT_SIZE'], $aCALK['CON10'], $aCALK['HI'], $aCALK['BC'], $aCALK['LN'], $aCALK['ACCOUNT']);
									if($aCALK['UL']>'2')	echo '</ul>';
									if($aCALK['UL']>'')	echo '</ul>';
									if($aCALK['UL']>'1')	echo '</ul>';
								}
								CLEAR_FIX();
							?>
							<div class="text-left">
								<?php 
									if ($can_UPDATE==1)	echo '<input type="submit" class="btn btn-primary" value='.S_MSG('F301','Save').'>&nbsp';
									if($can_CALK_EXCEL==1)	echo "<button type='button' class='btn btn-success btn-icon' onclick=window.location.href='?_r=excel'>Excel</button>"; 
								?>
							</div>
						</form>
					</div>
				</div>
<?php
			eTDIV();
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
	END_WINDOW();
	SYS_DB_CLOSE($DB2);	
?>

