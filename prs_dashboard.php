<?php
// prs_dashboard.php

	require_once "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE']) || !isset($_SESSION['gUSERCODE'])) session_start();
	require_once "sys_connect.php";
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSER_CODE = strtoupper($_SESSION['gUSERCODE']);
	if (isset($_SESSION['sCURRENT_PERIOD'])) $sPERIOD1=$_SESSION['sCURRENT_PERIOD'];
	else {	
		$mPERIOD1=S_PARA('PERIOD1', date('Ymd'));
		$sPERIOD1=substr($mPERIOD1,0,4). '-'. substr($mPERIOD1,4,2);
	}
	$qQUERY=OpenTable('TbUser', "upper(USER_CODE)='$cUSER_CODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	$a_TB_USER = SYS_FETCH($qQUERY);

	$cTITLE		= 'Dashboard';
	$cHEADER		= 'Dashboard';
	APP_LOG_ADD('Apps Start', 'Dashboard');
	$cFILE_LOGO_COMP = 'data/images/'. 'LOGO1_'.$cAPP_CODE.'.jpg';
	$l_BTN_PEGAWAI=$l_BTN_CUSTOMER=$l_BTN_JUAL=$l_BTN_INVENTORY=$l_BTN_PELANGGAN=$l_BTN_STOCK=0;
	$l_BTN_LAP_ABSEN=$l_BTN_BACA_MTR=$l_BTN_AKTIVASI=$l_BTN_LEAVE=$l_BTN_CAL=$l_BTN_APP=0;
	$l_BTN_PURCHASE=$l_BTN_PAYMENT=$l_BTN_RECEIPT=$l_BTN_JOURNAL=$l_BTN_CASH=$l_BTN_FINA=0;
	$l_FIXED_ASSETS=$l_PAYROLL_LIST=$l_SCHOOL=$l_REVENUE=$l_OVERTIME=$l_LOGS=0;
	$l_DINE_IN=$l_DELIVERY=$l_TAKEAWAY=$nDINE_IN=0;

	$qCEK_DASH=OpenTable('Dashboard', "A.DASH_JOB>'' and A.APP_CODE='$cAPP_CODE' and upper(B.USER_CODE) = '$cUSER_CODE' and B.TRUSTEE_CODE is not null and A.DASH_ORDER>0", "A.JOB_CODE", "A.DASH_ORDER, A.DASH_ORDER, B.TRUSTEE_CODE");
	while($aCEK_DASH = SYS_FETCH($qCEK_DASH)){
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nPEGAWAI'){ $l_BTN_PEGAWAI=1; }				// jml pegawai
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nCUSTOMER'){ $l_BTN_CUSTOMER=1; }			// jml item pelanggan
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nSALES'){ $l_BTN_JUAL=1; }					// nilai penjualan bln ini
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nSTOCK') $l_BTN_STOCK=1; 					// jml item stock
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nPELANGGAN'){ $l_BTN_PELANGGAN=1; }			// pelanggan PLN
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nREC_ABSEN'){ $l_BTN_LAP_ABSEN=1; }			// laporan absen
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nBACA_METER'){ $l_BTN_BACA_MTR=1; }			// laporan baca meter
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nAKTIVASI'){ $l_BTN_AKTIVASI=1; }			// jumlah aplikasi absen blm di aktivasi
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nLEAVE'){ $l_BTN_LEAVE=1; }					// cuti bln ini
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nCAL'){ $l_BTN_CAL=1; }						// calendar
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nAPP'){ $l_BTN_APP=1; }						// data lamaran
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nPURCHASE'){ $l_BTN_PURCHASE=1; }			// data purchasing
		if(rtrim($aCEK_DASH['DASH_PARA'])=='cPAYMENT'){ $l_BTN_PAYMENT=1; }				// payment
		if(rtrim($aCEK_DASH['DASH_PARA'])=='cRECEIPT'){ $l_BTN_RECEIPT=1; }				// receipt
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nJOURNAL'){ $l_BTN_JOURNAL=1; }				// journal
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nCASH') 		$l_BTN_CASH=1; 				// cash
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nPROFIT') 		$l_BTN_FINA=1; 				// Financial report
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nFIXED_ASSETS') $l_FIXED_ASSETS=1; 			// Fixed assets
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nSCHOOL') 		$l_SCHOOL=1; 				// School		
		if(rtrim($aCEK_DASH['DASH_PARA'])=='cREVENUE') 		$l_REVENUE=1; 				// School		
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nOVERTIME') 	$l_OVERTIME=1; 				// Overtime		
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nLOGS') 		$l_LOGS=1; 					// Logs		
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nDINE_IN') 		$l_DINE_IN=1; 				// Delivery on POS system		
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nDELIVERY') 	$l_DELIVERY=1; 				// Delivery on POS system		
		if(rtrim($aCEK_DASH['DASH_PARA'])=='nTAKEAWAY') 	$l_TAKEAWAY=1; 				// Takeaway on POS system		
		if($aCEK_DASH['JOB_CODE']=='DASH_HRD_PAYROL') 		$l_PAYROLL_LIST=1; 			// Paylist
	}

	$nCAL=$nPAYROL=$nREVENUE=0;
	$nDELIVERY=$nTAKE=0;
	if($l_REVENUE==1){
		$q_REV=OpenTable('SchRevHdr', "left(REV_DATE,7)='".substr($sPERIOD1,0,7)."' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		while($aREC_REV=SYS_FETCH($q_REV)) {
			$nREVENUE += $aREC_REV['REV_VALUE']/1000000;
		}
	}
	$cREVENUE = CVR($nREVENUE);

	$nSCHOOL=0;
	if($l_SCHOOL==1){
		$q_REC=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
		$nSCHOOL = SYS_ROWS($q_REC);
	}
	$nFIXED_ASSETS=0;
	if($l_FIXED_ASSETS==1){
		$q_REC=OpenTable('Fixed_Assets', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		while($aREC_FA=SYS_FETCH($q_REC)) {
			$nFIXED_ASSETS += $aREC_FA['FA_END_VAL']/1000000;
		}
	}
	$nFIXED_ASSETS = CVR($nFIXED_ASSETS);
	$nPROFIT=0;
	if($l_BTN_FINA==1){
		$cYEAR	= substr($sPERIOD1,0,4);
		$cMONTH	= substr($sPERIOD1, 5 ,2);
		$cEARN_ACCT = GET_IFACE('TMONTH_EARN');
		$q_REC = OpenTable('AccountBalance', "A.APP_CODE='$cAPP_CODE' and A.ACCOUNT_NO='$cEARN_ACCT' and A.REC_ID not in ( select DEL_ID from logs_delete) and B.BLNC_YEAR=".substr($sPERIOD1,0,4). " and B.BLNC_MONTH=".substr($sPERIOD1,5,2));
		if($aREC_PRO=SYS_FETCH($q_REC)) {
			$nPROFIT = $aREC_PRO['CUR_BALANC']/1000000;
		}
	}
	$nPROFIT = CVR($nPROFIT);
	$nJOURNAL=0;
	$nRECEIPT=0;
	if($l_BTN_RECEIPT==1){
		$q_REC=OpenTable('TrReceiptDHdr', "left(B.TGL_BAYAR,7)='".substr($sPERIOD1,0,7)."' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		while($aREC_PAY=SYS_FETCH($q_REC)) {
			$nRECEIPT += $aREC_PAY['NILAI']/1000000;
		}
	}
	$cRECEIPT = CVR($nRECEIPT);

	$nPAYMENT=0;
	if($l_BTN_PAYMENT==1){
		$q_PAY=OpenTable('TrPaymentDHdr', "left(B.BDV_DATE,7)='".substr($sPERIOD1,0,7)."' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
		while($aREC_PAY=SYS_FETCH($q_PAY)) {
			$nPAYMENT += $aREC_PAY['BDV_DAM']/1000000;
		}
	}
	$cPAYMENT = CVR($nPAYMENT);

	$nCASH=0;
	$cYEAR	= substr($sPERIOD1,0,4);
	$cMONTH	= substr($sPERIOD1, 5 ,2);
	if($l_BTN_CASH==1){
		$cCASH_ACCT = GET_IFACE('CASH');
		$nBEG_BAL = 0;
		$qBEG_BAL = OpenTable('BalanceHdr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and BLNC_YEAR='$cYEAR' and BLNC_MONTH='$cMONTH' and ACCOUNT_NO='$cCASH_ACCT'");
		if ($aREC_BAL=SYS_FETCH($qBEG_BAL)) {
			$nBEG_BAL = $aREC_BAL['BEG_BALANC'];
		}
		$nBEG_BAL /= 1000000;
		$nCASH=$nBEG_BAL+$nRECEIPT-$nPAYMENT;
	}
	$nCASH=CVR($nCASH);

	$nPURCHASE=0;
	if($l_BTN_PURCHASE==1){
		$q_PRC=OpenTable('TrPurchaseHdr', "left(DATE_REC,7)='".substr($sPERIOD1,0,7)."' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if($q_PRC)	$nPURCHASE = SYS_ROWS($q_PRC);
	}

	$nAPP=0;
	if($l_BTN_APP==1){
		$q_APP=OpenTable('PersonMain', "APP_CODE='$cAPP_CODE' and DELETOR='' and PRSON_SLRY=2");
		if($q_APP)	$nAPP = SYS_ROWS($q_APP);
	}

	$nPEGAWAI=0;
	if($l_BTN_PEGAWAI==1){
		$q_PEGAWAI	= SYS_QUERY("select count(*) as RECN from prs_person_main where APP_CODE='$cAPP_CODE' and DELETOR='' and PRSON_SLRY<2 and PRSON_CODE not in ( select PRSON_CODE from prs_resign where APP_CODE='$cAPP_CODE' AND DELETOR='')");
		// $q_PEGAWAI=OpenTable('PersonMain', "APP_CODE='$cAPP_CODE' and DELETOR='' and PRSON_SLRY<2 and PRSON_CODE not in ( select PRSON_CODE from prs_resign where APP_CODE='$cAPP_CODE')");
		// $nPEGAWAI = SYS_ROWS($q_PEGAWAI);
		$aPEGAWAI = SYS_FETCH($q_PEGAWAI);	
		if($aPEGAWAI)	$nPEGAWAI 	= $aPEGAWAI['RECN'];
	}
	$nPEGAWAI = CVR($nPEGAWAI);

	$nCUSTOMER = 0;
	if ($l_BTN_CUSTOMER==1){
		if($l_BTN_CUSTOMER==1){                                                                  
			$q_CUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''");
			if ($q_CUSTOMER) $nCUSTOMER = CVR(SYS_ROWS($q_CUSTOMER));
		}
	}

	$nSALES = 0;
	if($l_BTN_JUAL==1){
		$q_JUAL1=OpenTable('TrSalesHdr', "left(TGL_JUAL,7)='".substr($sPERIOD1,0,7)."' and FKT_VOID=0 and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		while($aREC_JUAL1=SYS_FETCH($q_JUAL1)) {
			$nSALES += $aREC_JUAL1['NILAI']/1000;
		}
	}

	$nSTOCK = 0;
	$nINVENTORY = 0;
	if($l_BTN_INVENTORY==1){
		$q_INVENTORY=OpenTable('Invent', "NO_ACTIVE=0 and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if($q_INVENTORY) $nINVENTORY = SYS_ROWS($q_INVENTORY);
	}

	$nPELANGGAN = 0;
	if($l_BTN_PELANGGAN==1){
		$q_PELANGGAN	= SYS_QUERY("select count(*) as RECN from bm_dil where APP_CODE='$cAPP_CODE'");
		if($a_PELANGGAN = SYS_FETCH($q_PELANGGAN)) {
			$nPELANGGAN 	= $a_PELANGGAN['RECN'];
		}
	}

	$nREC_ABSEN 	= 0;
	if($l_BTN_LAP_ABSEN==1){
		$aREC_ABSEN	= SYS_FETCH(SYS_QUERY("select count(*) as RECN from people_present where date(PPL_PRESENT)='".date("Y-m-d")."' and APP_CODE='$cAPP_CODE' and DELETOR='' and PRESENT_CODE=0"));
		if($aREC_ABSEN)	$nREC_ABSEN 	= $aREC_ABSEN['RECN'];                                        
	}

	$nOVERTIME	= 0;	
	if($l_OVERTIME){
		$cPERIOD1=date("Y-m-d");
		$cPERIOD2=date("Y-m-d");
//		$qOVERTIME = OpenTable('PrsOvertime', "APP_CODE='$cAPP_CODE' and date(OVT_START)>='$cPERIOD1' and date(OVT_START)<='$cPERIOD2'");
//		$nOVERTIME=SYS_ROWS($qOVERTIME);
		$aREC_OVTM	= SYS_FETCH(SYS_QUERY("select count(*) as RECN from prs_overtime where APP_CODE='$cAPP_CODE' and date(OVT_START)>='$cPERIOD1' and date(OVT_START)<='$cPERIOD2'"));
		if ($aREC_OVTM) $nOVERTIME=$aREC_OVTM['RECN'];
	}

	$nAKTIVASI	= 0;	
	if($l_BTN_AKTIVASI==1){
		$qAKTIVASI=OpenTable('Aktivasi', "PEOPLE_CODE='' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		$nAKTIVASI=SYS_ROWS($qAKTIVASI);
	}

	$nLEAVE	= 0;	
	if($l_BTN_LEAVE==1){
		$qQUERY=OpenTable('TrLeave', "CURDATE() between LEV_DATE1 and LEV_DATE2 and A.APP_CODE='$cAPP_CODE' and DELETOR=''");
		$nLEAVE=SYS_ROWS($qQUERY);
	}

	$nLOGS	= 0;	
	if($l_LOGS){
		$cPERIOD1=date("Y-m-d");
		$cPERIOD2=date("Y-m-d");
		$qLOGS = OpenTable('SysLogs', "APP_CODE='$cAPP_CODE' and LOGS>'' and left(DATE_ENTRY,10)>='$cPERIOD1' and left(DATE_ENTRY,10)<='$cPERIOD2'", '', 'DATE_ENTRY desc');
		$nLOGS=SYS_ROWS($qLOGS);
	}

	$nDINE_IN = $nDELIVERY = $nTAKE_AWAY = 0;
	if ($l_DINE_IN || $l_DELIVERY || $l_TAKEAWAY) {
		$q_SO=OpenTable('So1', "SO_VOID=0 and FAKTUR='' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		while($aSO=SYS_FETCH($q_SO)) {
			$cTEAM = $aSO['TEAM'];
			switch ($cTEAM) {
				case '':
					$nDINE_IN += 1;
					break;
				case 'd':
					$nDELIVERY += 1;
					break;
				case 't':
					$nTAKE_AWAY += 1;
					break;
			}
		}

	}

/*	$nBACA_METER	= 0;
	if($l_BTN_BACA_MTR==1){
		$q_BACA_METER =SYS_QUERY("select count(*) as RECN from bm_dt_baca where substr(TGL_BACA,1,10)='".date("Y-m-d")."' and APP_CODE='$cAPP_CODE'", $DB1);
		$a_BACA_METER = SYS_FETCH($q_BACA_METER);	
		$nBACA_METER = $a_BACA_METER['RECN'];
	}


	$n_JML_AREA = 0;
	$c_JNS_PRSHN = S_PARA('JNS_PRSHN', '.');
	$ada_BC_MTR=0;
	if(substr($c_JNS_PRSHN,58,1)!='') {
		$ada_BC_MTR=1;
		$q_JML_AREA 	= SYS_QUERY("select count(*) as RECN from tb_area where APP_CODE='$cAPP_CODE' and DELETOR=''");
		$a_JML_AREA 	= SYS_FETCH($q_JML_AREA);	
		$n_JML_AREA 	= $a_JML_AREA['RECN'];
	}	*/

	?>
<!DOCTYPE html>
<html class=" ">
	<?php 
		require_once("scr_header.php");	 require_once("scr_topbar.php");
	?>

	<!-- Custom CSS from SB -->
	<link href="sb/css/bootstrap.min.css" rel="stylesheet">
	<link href="sb/css/sb-admin-2.css" rel="stylesheet">
	<!-- <link href="sb/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"> -->

<!--	<body onload="get_loc();">		-->
	<body >
		<div class="page-container row-fluid">
			
			<div class="page-sidebar ">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper">
					<?php require_once("scr_menu.php"); ?>
				</div>
				<div class="project-info"></div>
			</div>
			<section id="main-content" class=" ">
				<section class="wrapper main-wrapper">

					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="page-title">

							<div class="pull-left">
								  <h1 class="title"><?php echo $cHEADER?></h1>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<section class="box ">
							<div class="pull-right hidden-xs"></div>

							<div class="content-body">
								<div class="row">
									<?php
										$qMAIN_DASH=OpenTable('Dashboard', "A.DASH_PARA>'' and A.DASH_JOB>'' and A.APP_CODE='$cAPP_CODE' and upper(B.USER_CODE) = '$cUSER_CODE' and B.TRUSTEE_CODE is not null and A.DASH_ORDER>0", "A.JOB_CODE", "A.DASH_ORDER, A.DASH_ORDER, B.TRUSTEE_CODE");
										while($aMAIN_DASH=SYS_FETCH($qMAIN_DASH)) {
											echo '<div class="col-lg-'.$aMAIN_DASH['DASH_WIDTH'].' col-sm-6">';
												echo '<a href="'.$aMAIN_DASH['DASH_LINK'].'">';
													echo '<div class="panel '.$aMAIN_DASH['DASH_COLOR'].'">';
														echo '<div class="panel-heading">';
															echo '<div class="row">';
																echo '<div class="col-xs-3">';
																	echo '<i class="fa '.$aMAIN_DASH['DASH_FA'].'"></i>';
																echo '</div>';
																$cVAR = $aMAIN_DASH['DASH_PARA'];
																$nVAR = ${$cVAR};
																if ($nVAR==0)	$nVAR = '0';
																echo '<div class="col-xs-9 text-right">';
																echo '<div class="huge">'.$nVAR.'</div>';
																echo '<div>' .  ( S_PARA('LANG', '1')=='2' ? $aMAIN_DASH['DASH_ENGLISH'] : $aMAIN_DASH['DASH_LABEL']) .'</div>';
																echo '</div>';
															echo '</div>';
														echo '</div>
															<div class="panel-footer">';
																echo '<span class="pull-left"></span>
																<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
																<div class="clearfix"></div>
															</div>
														</div>';
												echo '</a>
											</div>';
										}
									?>
								</div>
							</div>
						</section>
					</div>

				</section>
			</section>
			<?php	 include "scr_chat.php";	?>
		</div>
		<?php	require_once("js_framework.php");	?>
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

		<script type="text/javascript">
			function get_loc() {

				if (navigator.geolocation)	{
					navigator.geolocation.getCurrentPosition(showPosition);
				}

				function showPosition(position)	{
					$.post('user_loc.php',{
						_la:position.coords.latitude,
						_lo:position.coords.longitude
					});
				}
			}
		</script>
	</body>
</html>

