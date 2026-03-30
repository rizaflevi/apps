<?php
//	rep_fin_monthly.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 	= $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Laporan - Mutasi Keuangan Bulanan.pdf';
	$cHEADER 	= S_MSG('RK31','Laporan Mutasi Keuangan Bulanan');
	$ADD_LOG	= APP_LOG_ADD($cHEADER);
	$can_PRINT  = TRUST($cUSERCODE, 'RP_MON_FIN_PRINT');
	$can_EXCEL  = TRUST($cUSERCODE, 'RP_MON_FIN_EXCEL');
	$cSALDO_NOL		= S_PARA('ZERO_PRINT', 'Y');
  
	$cBEG_BAL 	= S_MSG('I006','Awal');
	$cCODE 	    = S_MSG('TA21','Kode');
	$cDEBIT		= S_MSG('RK06','Debit');
	$cKREDIT	= S_MSG('RK07','Kredit');
	$cSALDO		= S_MSG('I007','Saldo Akhir');
	$cACCOUNT 	= S_MSG('TA23','Account');
	
	$cACTION    = '';
	if (isset($_GET['action']))	$cACTION = $_GET['action'];

	$sPERIOD1	= $_SESSION['sCURRENT_PERIOD'];
	$dBOY= new DateTime(substr($sPERIOD1,0,5).'01-01');
	$dEOY=date_modify($dBOY,'-1 day');
	$cEOY=$dEOY->format('Y-m-d');
	$cLAST_YEAR=substr($cEOY,0,4);
	$cLAST_MONTH=substr($cEOY,5,2);

	if (isset($_GET['_d1'])) $sPERIOD1=$_GET['_d1'];

	$cYEAR	= substr($sPERIOD1,0,4);
	$cMONTH	= substr($sPERIOD1, 5 ,2);
	$cCASH_ACCT = GET_IFACE('CASH');
	$qQUERY = OpenTable('BlncHdrAcct', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and A.BLNC_YEAR='$cYEAR' and A.BLNC_MONTH='$cMONTH'", '', 'A.ACCOUNT_NO');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	DEF_WINDOW($cHEADER, 'collapse', 'prd');
?>
		<section id="main-content" class="sidebar_shift">
			<section class="wrapper main-wrapper" style=''>
				<div class="clearfix"></div>

				<div class="col-lg-12">
					<section class="box ">
						<?php   HELP_PDF($cHEADER, $cHELP_FILE);  ?>
						<div class="content-body">    
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">

									<table id="example" class="<?php echo S_PARA('_DISP_REPORT_CLASS','display table table-hover table-condensed')?> nowrap">
										<thead>
											<tr>
												<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cCODE?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cACCOUNT?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cBEG_BAL?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cDEBIT?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cKREDIT?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cSALDO?></th>
											</tr>
										</thead>

										<tbody>
											<?php
												$nT_DEBET = 0;	$nT_KREDIT = 0;	$nJUMLAH = 0;
												while($aREC_CASH=SYS_FETCH($qQUERY)) {
                                                    if($cSALDO_NOL=='Y' || $aREC_CASH['BEG_BALANC']>'0' || $aREC_CASH['MT_DEBIT']>'0' || $aREC_CASH['MT_CREDIT']>'0' || $aREC_CASH['CUR_BALANC']>'0' || $aREC_CASH['ZERO_PRINT']==1) {
                                                        $nJUMLAH += $aREC_CASH['MT_DEBIT'] - $aREC_CASH['MT_CREDIT'];
                                                        echo '<tr>';
                                                            $cICON = 'fa fa-money';
                                                            echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
                                                            echo '<td'.($aREC_CASH['GENERAL']=='G' ? ' style="font-weight:bold">' : '><span><a href="gl_ledger.php?_a='.md5('vi_ew').'&_r='.md5($aREC_CASH['ACCOUNT_NO']).'">').decode_string($aREC_CASH['ACCOUNT_NO']).'</a></span></td>';
                                                            echo '<td'.($aREC_CASH['GENERAL']=='G' ? ' style="font-weight:bold">' : '><span><a href="gl_ledger.php?_a='.md5('vi_ew').'&_r='.md5($aREC_CASH['ACCOUNT_NO']).'">').decode_string($aREC_CASH['ACCT_NAME']).'</td>';
                                                            echo '<td align="right">'.CVR($aREC_CASH['BEG_BALANC']).'</td>';
                                                            echo '<td align="right">'.CVR($aREC_CASH['MT_DEBIT']).'</td>';
                                                            echo '<td align="right">'.CVR($aREC_CASH['MT_CREDIT']).'</td>';
                                                            echo '<td align="right">'.CVR($aREC_CASH['CUR_BALANC']).'</td>';
                                                            $nT_DEBET += $aREC_CASH['MT_DEBIT'];
                                                            $nT_KREDIT += $aREC_CASH['MT_CREDIT'];
                                                        echo '</tr>';
                                                    }
                                                }
											?>
												<tr>	</tr>
												<tr>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;">Total</td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo CVR($nT_DEBET)?></td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo CVR($nT_KREDIT)?></td>
													<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo CVR($nT_DEBET-$nT_KREDIT)?></td>
												</tr>
												<td></td><td></td><td></td>
												<tr></tr>
										</tbody>
									</table>

								</div>
							</div>
						</div>
					</section>
				</div>

			</section>
		</section>

<?php
	include "scr_chat.php";
	require_once("js_framework.php");
    END_WINDOW();
?>

