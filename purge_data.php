<?php
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cHELP_FILE = 'Doc/File - Purge Data.pdf';
	$cHEADER	= 'Purge Data';
	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

switch($cACTION){
	default:
		DEF_WINDOW($cHEADER);
			TFORM($cHEADER, 'purge_data.php?_a=purge', [], $cHELP_FILE, '*');
				TDIV();
?>
					<div class="alert alert-success alert-dismissible fade in" role="alert">
						<h4>Proses ini akan menghapus permanen data yang sudah dihapus.</h4>
						<p>Dan tidak akan bisa di kembalikan lagi data yang di hapus soft delete.</p>
						<p>Klik Continue untuk melanjutkan, atau Close untuk batal.</p>
					<!-- </div> -->
<?php
					SAVE('Continue');
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		break;

	case 'purge':
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'purge');
		RecPurge('TbUser', 1);
		RecPurge('UserScope', 1);
		RecPurge('SysPrevillage', 1);
		RecPurge('People', 1);
		RecPurge('PeopleAddress');
		RecPurge('PeopleBlood');
		RecPurge('PeopleHomePhone');
		RecPurge('PeopleTelegram');
		RecPurge('PeopleContact');
		RecPurge('PeopleEMail');
		RecPurge('PeopleNotes');
		RecDelete('PeopleNotes', "PEOPLE_NOTES='' and APP_CODE='$cAPP_CODE'");
		RecPurge('PplAnywhere', 1);
		RecPurge('PersonMain', 1);
		RecPurge('PrsLicense');
		RecPurge('PrsLocation', 1);
		RecDelete('PrsDelta', "(DELTA=0 or DELETOR!='') and APP_CODE='$cAPP_CODE'");
		RecDelete('PrsAddBpjs', "(ADDING=0 or DELETOR!='') and APP_CODE='$cAPP_CODE'");
		RecPurge('Presence', 1);
		RecDelete('Aktivasi', "(DELETOR!='' or PEOPLE_CODE='X') and APP_CODE='$cAPP_CODE'");
		RecPurge('RegSchedule', 1);
		RecPurge('Invent');
		RecPurge('TbInvGroup');
		RecPurge('TbiCategory');
		RecPurge('TbiCashDisc');
		RecPurge('TbiVolWeight');
		RecPurge('TbiNotes');
		RecPurge('TbCustomer', 1);
		RecPurge('TbPayType');
		RecPurge('TrSalesHdr');
		RecPurge('TrSalesDtl');
		RecPurge('TrPurchaseHdr');
		RecPurge('TrPurchaseDtl', 3);
		RecPurge('TrReceiptHdr');
		RecPurge('TrReceiptDtl');
		RecPurge('TrReceiptBank');
		RecPurge('TrPaymentHdr', 1);
		RecPurge('TrPaymentDtl', 1);
		RecPurge('TbAccount');
		RecPurge('TbCalk');
		RecPurge('TrJrnHdr');
		RecPurge('TrJrnDtl');
		RecPurge('BalanceHdr');
		RecPurge('BalanceDtl');
		RecPurge('TbRatio');
		RecPurge('FixedAssets');
		RecPurge('FaGroup');
		RecPurge('FaCalc');
		RecPurge('GlInterface');
		RecPurge('TbIdentity');
		RecPurge('TbArea');
		RecPurge('TbFont');
		RecPurge('Fixed_Assets');
		RecPurge('TbChecklist');
		RecPurge('Tbh_Area');
		RecPurge('CheckItem');
		RecPurge('TbBillPrintHdr', 1);
		RecPurge('TbBillPrn');
		SYS_QUERY('DELETE FROM prs_payslip where SLIP_YEAR<=YEAR(NOW())-3');
		SYS_QUERY('TRUNCATE `rabs_sched`');
		$nBLN=(integer)S_PARA('ABSEN_DATA', '99');
		if($nBLN>0) {
			$cQUERY="DELETE FROM people_present where PRESENT_CODE>1 and APP_CODE='$cAPP_CODE'";
			SYS_QUERY($cQUERY);
			$cQUERY="DELETE FROM people_present where TIMESTAMPDIFF(MONTH, DATE(PPL_PRESENT), DATE(NOW()))>".$nBLN." and APP_CODE='$cAPP_CODE'";
			SYS_QUERY($cQUERY);
			$cQUERY="DELETE FROM prs_overtime where TIMESTAMPDIFF(MONTH, DATE(OVT_START), DATE(NOW()))>".$nBLN." and APP_CODE='$cAPP_CODE'";
			SYS_QUERY($cQUERY);
			$cQUERY="DELETE FROM prs_schedule where TIMESTAMPDIFF(MONTH, WORK_DATE, DATE(NOW()))>".$nBLN." and APP_CODE='$cAPP_CODE'";
			SYS_QUERY($cQUERY);
			$cQUERY="DELETE FROM check_list_trans where TIMESTAMPDIFF(MONTH, left(FROM_UNIXTIME(left(REC_ID, 10)),10), DATE(NOW()))>".$nBLN." and APP_CODE='$cAPP_CODE'";
			SYS_QUERY($cQUERY);
		}
		
		DEF_WINDOW($cHEADER);
?>
			<section id="main-content" class=" ">
				<section class="wrapper main-wrapper">
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
											<strong>Sukses:</strong> Purge data telah selesai.
										</div>

									</div>
								</div>
							</div>
						</section>
					</div>

				</section>
			</section>
<?php
		END_WINDOW();
	    break;
	case 'spin':
		DEF_WINDOW($cHEADER);
?>
			<section id="main-content" class=" ">
				<section class="wrapper main-wrapper">
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
											<strong>Sukses:</strong> Harap tunggu sampai proses ini selesai.
										</div>

									</div>
								</div>
							</div>
						</section>
					</div>

				</section>
			</section>
<?php
		END_WINDOW();
		header('purge_data.php?_a=purge');
	    break;
}
?>
