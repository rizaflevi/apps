<?php
//	app_code.php //

require_once "sysfunction.php";
// if (!IS_LOCALHOST()) {
// 	echo "<script>self.history.back();</script>";
// }
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - AppCode.pdf';

	$can_CREATE = TRUST($cUSERCODE, 'TB_BANK_1ADD');
	$cHEADER = 'Tabel AppCode';

	$qQUERY=OpenTable('TbmPassword', "true");

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

	$cAPPCODE 	= 'App Code';
	$cCOMPANY 	= 'Company';
	$cPASSWD 	= 'Password';
	$cCOPY_FROM	= 'Copy from';
    $cADMIN_CD  = 'Kode Admin';
	$cEDIT_TBL	= 'Edit';
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');


switch($cACTION){
	default:
		if (IS_LOCALHOST())		UPDATE_DATE();
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cAPPCODE, $cCOMPANY, $cPASSWD]);
						echo '<tbody>';
							while($aREC_APP_CODE=SYS_FETCH($qQUERY)) {
								$cHREFF="<a href='?_a=".md5('up_d4t3')."&_b=".md5($aREC_APP_CODE['APP_CODE'])."'>";
								$aHREFF=[$cHREFF, $cHREFF, $cHREFF];
								$aCOL=[$aREC_APP_CODE['APP_CODE'], $aREC_APP_CODE['COMPANY'], $aREC_APP_CODE['PASSWORD']];
								TDETAIL($aCOL, [0,0,0], '', $aHREFF);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
	break;

	case md5('cr34t3'):
		$cADD_REC	= 'Tambah AppCode';
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah');
			TDIV();
				LABEL([3,3,3,6], '700', $cAPPCODE);
				INPUT('text', [2,3,3,6], '900', 'NEW_APP_CODE', '', 'focus', '', '', 10, '', 'fix');
				LABEL([3,3,3,6], '700', $cCOMPANY);
				INPUT('text', [6,6,6,6], '900', 'ADD_COMPANY', '', '', '', '', 100, '', 'fix');
				LABEL([3,3,3,6], '700', $cPASSWD);
				INPUT('text', [6,6,6,6], '900', 'ADD_PASWD', '', '', '', '', 50, '', 'fix');
				LABEL([3,3,3,6], '700', $cCOPY_FROM);
				INPUT('text', [6,6,6,6], '900', 'ADD_COPY_FROM', '', '', '', '', 10, '', 'fix');
				LABEL([3,3,3,6], '700', $cADMIN_CD);
				INPUT('text', [6,6,6,6], '900', 'ADD_ADMIN', '', '', '', '', 20, '', 'fix');
				LABEL([3,3,3,6], '700', 'Nama Admin');
				INPUT('text', [6,6,6,6], '900', 'ADD_NM_ADMIN', '', '', '', '', 45, '', 'fix');
				LABEL([3,3,3,6], '700', 'Presence');
				INPUT('checkbox', [1,1,1,1], '900', 'PRESENCE', true, '', '', '', 0, '', 'fix');
				LABEL([3,3,3,6], '700', 'Reservation');
				INPUT('checkbox', [1,1,1,1], '900', 'RESERVATION', true, '', '', '', 0, '', 'fix');
				SAVE($cSAVE);
			eTFORM();
		END_WINDOW();
	break;

	case md5('up_d4t3'):
		if (IS_LOCALHOST())		UPDATE_DATE();
		$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$can_DELETE = 1;
		$APPCODE = $_GET['_b'];
		$qQUERY=OpenTable('tbm_password', "md5(APP_CODE)='$APPCODE'");
		$REC_APP_CODE=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ['<a href="?_a='.md5('del_AppCode').'&_id='. $REC_APP_CODE['APP_CODE']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'];
			TFORM($cEDIT_TBL, '?_a=UPD_APP&_r='. $REC_APP_CODE['APP_CODE'], $aACT);
				TDIV();
					LABEL([3,3,3,6], '700', $cAPPCODE);
					INPUT('text', [2,3,3,6], '900', 'EDIT_APP_CODE', $REC_APP_CODE['APP_CODE'], '', '', '', 10, 'disabled', 'fix');
					LABEL([3,3,3,6], '700', $cCOMPANY);
					INPUT('text', [6,6,6,6], '900', 'EDIT_COMPANY', DECODE($REC_APP_CODE['COMPANY']), '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cPASSWD);
					INPUT('text', [6,6,6,6], '900', 'EDIT_PASWD', $REC_APP_CODE['PASSWORD'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', 'Presence');
					INPUT('checkbox', [1,1,1,1], '900', 'PRESENCE', true, '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', 'Reservation');
					INPUT('checkbox', [1,1,1,1], '900', 'RESERVATION', true, '', '', '', 0, '', 'fix');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
	break;

	case 'tambah':
        $cAPP_CODE=(isset($_POST['NEW_APP_CODE']) ? $_POST['NEW_APP_CODE'] : '');
		if($cAPP_CODE==''){
			MSG_INFO('Appcode belum diisi');
			return;
		}
		$qQUERY=OpenTable('TbmPassword', "APP_CODE='$cAPP_CODE'");
		if(SYS_ROWS($qQUERY)>0){
			MSG_INFO('Appcode sudah ada');
			header('location:app_code.php');
		}
		$cCOMPANY = (isset($_POST['ADD_COMPANY']) ? ENCODE($_POST['ADD_COMPANY']) : '');
		$cPASSWORD = (isset($_POST['ADD_PASWD']) ? ENCODE($_POST['ADD_PASWD']) : '');
		$cNEW_APPCODE = (isset($_POST['NEW_APP_CODE']) ? ENCODE($_POST['NEW_APP_CODE']) : '');
		$cPRESENCE = (isset($_POST['PRESENCE']) ? 1 : 0);
		$cRESERVATION = (isset($_POST['RESERVATION']) ? 1 : 0);
		RecCreate('TbmPassword', ['APP_CODE', 'COMPANY', 'PASSWORD', 'PRESENCE', 'RESERVATION'], 
			[ENCODE($cAPP_CODE), $cCOMPANY, $cPASSWORD, $cPRESENCE, $cRESERVATION]);
		$cAPP_SOURCE = (isset($_POST['ADD_COPY_FROM']) ? $_POST['ADD_COPY_FROM'] : '');
		$cAPP_ADMIN = (isset($_POST['ADD_ADMIN']) ? $_POST['ADD_ADMIN'] : '');
		$cNM_ADMIN = (isset($_POST['ADD_NM_ADMIN']) ? $_POST['ADD_NM_ADMIN'] : '');

		$qRAINBOW=OpenTable('RAINBOW', "DELETOR='' and APP_CODE='$cAPP_SOURCE'");
		while ($aREC = SYS_FETCH($qRAINBOW)) {
			RecCreate('rainbow', ['KEY_FIELD', 'KEY_CONTEN', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
				[$aREC['KEY_FIELD'], $aREC['KEY_CONTEN'], $cNEW_APPCODE, $cUSERCODE, date("Y-m-d H:i:s")]);
		}
		$qDASH=OpenTable('sys_dash', "APP_CODE='$cAPP_SOURCE'");
		while ($aD = SYS_FETCH($qDASH)) {
			RecCreate('sys_dash', ['JOB_CODE', 'DASH_LABEL', 'DASH_ENGLISH', 'DASH_ORDER', 'DASH_WIDTH', 'DASH_COLOR', 'DASH_ICON', 'DASH_CLASS', 'DASH_FA', 'DASH_LINK', 'DASH_JOB', 'DASH_PARA', 'APP_CODE'], 
				[$aD['JOB_CODE'], $aD['DASH_LABEL'], $aD['DASH_ENGLISH'], $aD['DASH_ORDER'], $aD['DASH_WIDTH'], $aD['DASH_COLOR'], $aD['DASH_ICON'], $aD['DASH_CLASS'], $aD['DASH_FA'], $aD['DASH_LINK'], $aD['DASH_JOB'], $aD['DASH_PARA'], $cNEW_APPCODE]);
		}
		$qMENU=OpenTable('sys_menu', "APP_CODE='$cAPP_SOURCE'");
		while ($aD = SYS_FETCH($qMENU)) {
			$cENG_PARENT = (is_null($aD['ENG_PARENT']) ? '' : $aD['ENG_PARENT']);
			RecCreate('sys_menu', ['parent', 'name', 'link', 'icon_class', '`order`', 'sort', 'ENG_PARENT', 'IN_ENGLISH', 'JOB_CODE', 'APP_CODE'], 
				[$aD['parent'], $aD['name'], $aD['link'], $aD['icon_class'], $aD['order'], $aD['sort'], $cENG_PARENT, $aD['IN_ENGLISH'], $aD['JOB_CODE'], $cNEW_APPCODE]);
		}
		$qMESG=OpenTable('sys_msg', "APP_CODE='$cAPP_SOURCE'");
		while ($aD = SYS_FETCH($qMESG)) {
			RecCreate('sys_msg', ['MSG_CODE', 'SYS_MS', 'ENG_MS', 'MESSG_DATE', 'APP_CODE'], 
				[$aD['MSG_CODE'], $aD['SYS_MS'], $aD['ENG_MS'], date("Y-m-d H:i:s"), $cNEW_APPCODE]);
		}

		if ($cAPP_ADMIN) {
			$qUSER_QUERY = OpenTable('TbUser', "USER_CODE='$cAPP_ADMIN' and DELETOR=''");
			if (SYS_ROWS($qUSER_QUERY) > 0) {
				MSG_INFO(S_MSG('TU31', 'Kode User sudah ada'));
				return;
			} else {
				RecCreate('TbUser',
					['USER_CODE', 'USER_NAME', 'ENTRY', 'DATE_ENTRY', 'SYS_MODULE', 'APP_CODE'],
					[$cAPP_ADMIN, $cNM_ADMIN, $cUSERCODE, date("Y-m-d H:i:s"), 'RAINBOW', $cNEW_APPCODE]
				);
			}
			$qMESG=OpenTable('sys_previllage', "APP_CODE='$cAPP_SOURCE'");
			while ($aD = SYS_FETCH($qMESG)) {
				RecCreate('sys_previllage', ['USER_CODE', 'JOB_CODE', 'TRUSTEE_CODE', 'DATE_ENTRY', 'APP_CODE'], 
					[$cAPP_ADMIN, $aD['JOB_CODE'], $aD['TRUSTEE_CODE'], date("Y-m-d H:i:s"), $cNEW_APPCODE]);
			}
		}
		header('location:app_code.php');
		break;

	case 'UPD_APP':
		$cREC_ID=$_GET['_r'];
		$lCHECK = (isset($_POST['PRESENCE']) ? 1 : 0);
		$lRES = (isset($_POST['RESERVATION']) ? 1 : 0);
		RecUpdate('tbm_password', ['COMPANY', 'PASSWORD', 'PRESENCE', 'RESERVATION'], 
			[$_POST['EDIT_COMPANY'], $_POST['EDIT_PASWD'], $lCHECK, $lRES], 
			"APP_CODE='$cREC_ID'");
		header('location:app_code.php');
		break;

	case md5('del_AppCode'):
		$cREC_APP = $_GET['_id'];
		RecDelete('So1', "APP_CODE='$cREC_APP'");
		RecDelete('So2', "APP_CODE='$cREC_APP'");
		AppPurge($cREC_APP, 'TbUser', 1);
		AppPurge($cREC_APP, 'UserScope', 1);
		AppPurge($cREC_APP, 'rainbow', 1);
		AppPurge($cREC_APP, 'People', 1);
		AppPurge($cREC_APP, 'PeopleAddress');
		AppPurge($cREC_APP, 'PeopleBlood');
		AppPurge($cREC_APP, 'PeopleHomePhone');
		AppPurge($cREC_APP, 'PeopleTelegram');
		AppPurge($cREC_APP, 'PeopleContact');
		AppPurge($cREC_APP, 'PeopleEMail');
		AppPurge($cREC_APP, 'PeopleNotes');
		AppPurge($cREC_APP, 'PeopleNickName');
		AppPurge($cREC_APP, 'PplAnywhere', 1);
		AppPurge($cREC_APP, 'PersonMain', 1);
		AppPurge($cREC_APP, 'PrsLicense');
		AppPurge($cREC_APP, 'TbReligion');
		AppPurge($cREC_APP, 'PrsLocation', 1);
		AppPurge($cREC_APP, 'PrsDelta', 1);
		AppPurge($cREC_APP, 'PrsAddBpjs', 1);
		AppPurge($cREC_APP, 'Presence', 1);
		AppPurge($cREC_APP, 'Aktivasi', 1);
		AppPurge($cREC_APP, 'RegSchedule', 1);
		AppPurge($cREC_APP, 'Invent');
		AppPurge($cREC_APP, 'TbInvGroup');
		AppPurge($cREC_APP, 'TbiCategory');
		AppPurge($cREC_APP, 'TbiCashDisc');
		AppPurge($cREC_APP, 'TbiVolWeight');
		AppPurge($cREC_APP, 'TbiNotes');
		AppPurge($cREC_APP, 'TbCustomer', 1);
		AppPurge($cREC_APP, 'TbPayType');
		AppPurge($cREC_APP, 'TrSalesHdr');
		AppPurge($cREC_APP, 'TrSalesDtl');
		AppPurge($cREC_APP, 'TrPurchaseHdr');
		AppPurge($cREC_APP, 'TrPurchaseDtl', 3);
		AppPurge($cREC_APP, 'TrReceiptHdr');
		AppPurge($cREC_APP, 'TrReceiptDtl');
		AppPurge($cREC_APP, 'TrReceiptBank');
		AppPurge($cREC_APP, 'TrPaymentHdr', 1);
		AppPurge($cREC_APP, 'TrPaymentDtl', 1);
		AppPurge($cREC_APP, 'TbAccount');
		AppPurge($cREC_APP, 'TbCalk');
		AppPurge($cREC_APP, 'TrJrnHdr');
		AppPurge($cREC_APP, 'TrJrnDtl');
		AppPurge($cREC_APP, 'BalanceHdr');
		AppPurge($cREC_APP, 'BalanceDtl');
		AppPurge($cREC_APP, 'PrLedger', 1);
		AppPurge($cREC_APP, 'TbRatio');
		AppPurge($cREC_APP, 'fa_master');
		AppPurge($cREC_APP, 'FaGroup');
		AppPurge($cREC_APP, 'FaCalc');
		AppPurge($cREC_APP, 'GlInterface');
		AppPurge($cREC_APP, 'TbIdentity');
		AppPurge($cREC_APP, 'TbArea');
		AppPurge($cREC_APP, 'TbFont');
		AppPurge($cREC_APP, 'TbChecklist');
		AppPurge($cREC_APP, 'Tbh_Area');
		AppPurge($cREC_APP, 'CheckItem');
		AppPurge($cREC_APP, 'TbBillPrintHdr', 1);
		AppPurge($cREC_APP, 'TbBillPrn');
		AppPurge($cREC_APP, 'SysPrevillage', 1);
		AppPurge($cREC_APP, 'Dashboard', 1);
		AppPurge($cREC_APP, 'SysMenu', 1);
		AppPurge($cREC_APP, 'SysMessage', 1);
		AppPurge($cREC_APP, 'PosTable');
		AppPurge($cREC_APP, 'Q_Stock');
		SYS_QUERY("DELETE FROM sys_logs where APP_CODE='$cREC_APP'");
		SYS_QUERY("DELETE FROM tbm_password where APP_CODE='$cREC_APP'");
		MSG_INFO('Proses selesai !');
		header('location:app_code.php');
		break;
}
?>

