<?php
//	gl_closing.php
//	TODO : closing period

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
$cUSERCODE = $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Proses - Closing.pdf';

$cHEADER	= S_MSG('PR60','TUTUP BULAN');

$cACTION='';
if (isset($_GET['action'])) $cACTION=$_GET['action'];

$sPERIOD1=$_SESSION['sCURRENT_PERIOD'];
$dNEXT_PERIOD = date('Y-m', strtotime($sPERIOD1. ' + 1 months'));
$cYEAR	= substr($sPERIOD1,0,4);
$cMONTH	= substr($sPERIOD1, 5 ,2);
$cNEXT_YEAR		= substr($dNEXT_PERIOD,0,4);
$cNEXT_MONTH	= substr($dNEXT_PERIOD,5,2);

$cCASH_ACCT = GET_IFACE('CASH');
$cEARN_ACCT = GET_IFACE('MR_EARNING');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
		DEF_WINDOW($cHEADER, '', 'prd');
		TFORM($cHEADER, '', [], $cHELP_FILE);
?>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="alert alert-primary alert-dismissible fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4>Proses ini akan menutup saldo periode transaksi yang sekarang untuk dipindahkan ke saldo awal periode selanjutnya.</h4>
					<p>Periode data yang akan di tutup : <h3><?php echo $sPERIOD1 ?></h3>.</p>
					<p>Periode data berikutnya         : <h3><?php echo $dNEXT_PERIOD; ?></h3>.</p>
					<p>Klik Continue untuk melanjutkan, atau Cancel untuk batal.</p>
					<p>
						<button type="button" class="btn btn-default" onclick="window.location.href='gl_closing.php?action=posting'">Continue</button>
						<button type="button" class="btn btn-default" onclick="window.history.back()">Cancel</button>
					</p>
				</div>
			</div>
<?php
		eTFORM('*');
		END_WINDOW();
		break;

case 'posting':
	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Closing start');
	$dDATE_POST=date('Y-m-d');
	if ($dDATE_POST>date($sPERIOD1.'-t'))	$dDATE_POST=date($sPERIOD1.'-t');

//	======================== Empty Ledger and zero balance =====================================================

	RecUpdate('BalanceHdr', ['MT_DEBIT', 'MT_CREDIT', 'MUTATION', 'CUR_BALANC'], [0,0,0,0], "APP_CODE='$cAPP_CODE' and BLNC_YEAR = $cYEAR and BLNC_MONTH= $cMONTH");
	RecUpdate('BalanceHdr', ['BEG_BALANC'], [0], "APP_CODE='$cAPP_CODE' and BLNC_YEAR = $cYEAR and BLNC_MONTH= $cMONTH and ACCOUNT_NO in ( select ACCOUNT_NO from account where APP_CODE='$cAPP_CODE' and (TYPE_ACCT='4' or TYPE_ACCT='5' or GENERAL='G'))");
	RecUpdate('BalanceDtl', ['VAL_BALANC'], [0], "APP_CODE='$cAPP_CODE' and BLNC_DAY = '$dDATE_POST'");
	RecDelete('PrLedger', "APP_CODE='$cAPP_CODE' and LDGR_YEAR = '$cYEAR' and LDGR_MONTH = '$cMONTH'");
	
//	================== akumulasi ke parent account ( semua account ) ===============================================
	$qSQL = "update balance_hdr set CUR_BALANC = BEG_BALANC+MUTATION where APP_CODE='".$cAPP_CODE."' and BLNC_YEAR = $cYEAR and BLNC_MONTH= $cMONTH";
	SYS_QUERY($qSQL);
//	RecUpdate('BalanceHdr', ['CUR_BALANC'], [BEG_BALANC+MUTATION], "APP_CODE='$cAPP_CODE' and BLNC_YEAR = $cYEAR and BLNC_MONTH= $cMONTH and ACCOUNT_NO in ( select ACCOUNT_NO from account where APP_CODE='$cAPP_CODE' and (TYPE_ACCT='4' or TYPE_ACCT='5' or GENERAL='G'))");

	RecUpdate('BalanceHdr', ['MT_DEBIT', 'MT_CREDIT', 'CUR_BALANC'], [0, 0, 0], "APP_CODE='$cAPP_CODE' and BLNC_YEAR = '$cYEAR' and BLNC_MONTH= '$cMONTH' and ACCOUNT_NO in ( select ACCOUNT_NO from account where APP_CODE='$cAPP_CODE' and GENERAL='G')");
/*	$qBLNC_HDR = OpenTable('BlncHdrAcct', "B.GENERAL='G' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and A.BLNC_YEAR = $cYEAR and A.BLNC_MONTH= $cMONTH");
	while($aREC_BALANCE=SYS_FETCH($qBLNC_HDR)) {
		RecUpdate('BalanceHdr', ['MT_DEBIT', 'MT_CREDIT', 'CUR_BALANC'], [0, 0, 0], "ACCOUNT_NO='$aREC_BALANCE[ACCOUNT_NO]' and APP_CODE='$cAPP_CODE' and BLNC_YEAR = '$cYEAR' and BLNC_MONTH= '$cMONTH'");
	}
*/	
	for($LVL=5; $LVL>=1; $LVL--) {
		$nLEVEL = $LVL - 1;
		$qAKM = OpenTable('TbAccount', "LEVEL='$nLEVEL' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		while($aREC_ACCT=SYS_FETCH($qAKM)) {

			$cACCT = $aREC_ACCT['ACCOUNT_NO'];
			$nMUTASI_DEBIT  = 0;	$nMUTASI_KREDT  = 0;	$nGBALANCE  = 0;
			$qBLNC_HDR = OpenTable('BlncHdrAcct', "B.GEN_ACCT='$cACCT' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and A.BLNC_YEAR = $cYEAR and A.BLNC_MONTH= $cMONTH");
			while($aREC_SUB=SYS_FETCH($qBLNC_HDR)) {
				$nMUTASI_DEBIT  += $aREC_SUB['MT_DEBIT'];
				$nMUTASI_KREDT  += $aREC_SUB['MT_CREDIT'];
				$nGBALANCE 		+= $aREC_SUB['CUR_BALANC'];
			}
			if ($nMUTASI_DEBIT+$nMUTASI_KREDT+$nGBALANCE>0) {
				$qBLNC_CEK = OpenTable('BalanceHdr', "ACCOUNT_NO='$cACCT' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cYEAR and BLNC_MONTH= $cMONTH");
				if (!$qBLNC_CEK || SYS_ROWS($qBLNC_CEK)==0) {
					RecCreate('BalanceHdr', ['ACCOUNT_NO', 'BLNC_YEAR', 'BLNC_MONTH', 'APP_CODE', 'REC_ID'], [$cACCT, $cYEAR, $cMONTH, $cAPP_CODE, NowMSecs()]);
				} else {
					RecUpdate('BalanceHdr', ['MT_DEBIT', 'MT_CREDIT', 'CUR_BALANC'], 
						[$nMUTASI_DEBIT, $nMUTASI_KREDT, $nGBALANCE], 
						"ACCOUNT_NO='$cACCT' and APP_CODE='$cAPP_CODE' and BLNC_YEAR = '$cYEAR' and BLNC_MONTH= '$cMONTH'");
				}
			}
		}
	}

//	================== akumulasi ke parent account ( khusus account modal saja ) ===============================================
			$qBLNC_HDR = OpenTable('BlncHdrAcct', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and A.BLNC_YEAR = $cYEAR and A.BLNC_MONTH= $cMONTH");
			while($aREC_SUB=SYS_FETCH($qBLNC_HDR)) {
				$nMUTASI_DEBIT  += $aREC_SUB['MT_DEBIT'];
				$nMUTASI_KREDT  += $aREC_SUB['MT_CREDIT'];
				$nGBALANCE 		+= $aREC_SUB['CUR_BALANC'];
			}
			if ($nMUTASI_DEBIT+$nMUTASI_KREDT+$nGBALANCE>0) {
				$qBLNC_CEK = OpenTable('BalanceHdr', "ACCOUNT_NO='$cACCT' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and BLNC_YEAR = $cYEAR and BLNC_MONTH= $cMONTH");
				if (!$qBLNC_CEK || SYS_ROWS($qBLNC_CEK)==0) {
					RecCreate('BalanceHdr', ['ACCOUNT_NO', 'BLNC_YEAR', 'BLNC_MONTH', 'APP_CODE', 'REC_ID'], [$cACCT, $cYEAR, $cMONTH, $cAPP_CODE, NowMSecs()]);
				} else {
					RecUpdate('BalanceHdr', ['MT_DEBIT', 'MT_CREDIT', 'CUR_BALANC'], 
						[$nMUTASI_DEBIT, $nMUTASI_KREDT, $nGBALANCE], 
						"ACCOUNT_NO='$cACCT' and APP_CODE='$cAPP_CODE' and BLNC_YEAR = '$cYEAR' and BLNC_MONTH= '$cMONTH'");
				}
			}
//	================== update ke balance harian per tanggal ===================================================================
	$qBHDR = OpenTable('BalanceHdr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and BLNC_YEAR='$cYEAR' and BLNC_MONTH='$cMONTH'");
	while($aREC_BLNC_HDR=SYS_FETCH($qBHDR)) {
		$nBLNC = $aREC_BLNC_HDR['CUR_BALANC'];
		$cACCT = $aREC_BLNC_HDR['ACCOUNT_NO'];
		$qBDTL = OpenTable('BalanceDtl', "ACCOUNT_NO='$cACCT' and BLNC_DAY='$dDATE_POST' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if ($qBDTL && SYS_ROWS($qBDTL)>0) {
			$aREC_DT = SYS_FETCH($qBDTL);
			$cREC_ID = $aREC_DT['REC_ID'];
			RecUpdate('BalanceDtl', ['VAL_BALANC'], [$nBLNC], "REC_ID='$cREC_ID'");
		} else {
			RecCreate('BalanceDtl', ['BLNC_DAY', 'VAL_BALANC', 'ACCOUNT_NO', 'APP_CODE', 'REC_ID'], [$dDATE_POST, $nBLNC, $cACCT, $cAPP_CODE, NowMSecs()]);
		}
	}
?>
<!DOCTYPE html>
<html class=" ">
	<?php	require_once("scr_header.php");	?>
    <body class=" ">
		<?php	require_once("scr_topbar.php");	?>
        <div class="page-container row-fluid">
            <div class="page-sidebar ">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
					<?php	require_once("scr_menu.php");	?>
				</div>
				<div class="project-info"> </div>
            </div>
            <section id="main-content" class=" ">
                <section class="wrapper main-wrapper" style=''>
                    <div class="clearfix"></div>
                    <div class="col-lg-12">
                        <section class="box ">
                            <header class="panel_header">
                                <h2 class="title pull-left"><?php echo $cHEADER?></h2>
                            </header>
                            <div class="content-body">    
								<div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">

                                        <div class="alert alert-success alert-dismissible fade in">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            <strong>Sukses:</strong> Proses Closing Period telah selesai, data saldo keuangan sudah dipindahkan ke periode selanjutnya.
										</div>

                                    </div>
                                </div>
                            </div>
                        </section>
					</div>

                </section>
            </section>
			<?php	include "scr_chat.php";	?>
		</div>
		<?php	require_once("js_framework.php");	?>
        <script src="assets/plugins/messenger/js/messenger.min.js" type="text/javascript"></script>
		  <script src="assets/plugins/messenger/js/messenger-theme-future.js" type="text/javascript"></script>
		  <script src="assets/plugins/messenger/js/messenger-theme-flat.js" type="text/javascript"></script>
		  <script src="assets/js/messenger.js" type="text/javascript"></script>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 
    </body>
</html>

<?php
}
?>
