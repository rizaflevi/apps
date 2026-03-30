<?php
//	tb_user.php //
// TODO : upload foto

include "sysfunction.php";
include "sys_connect.php";
if (!isset($_SESSION['data_FILTER_CODE']) || !isset($_SESSION['DB1'])) 	session_start();

$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
$cUSERCODE = $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Tabel - User.pdf';

$cHEADER 	= S_MSG('TU00', 'Tabel User');
$cSAVE		= S_MSG('F301', 'Save');
$cCLOSE		= S_MSG('F302', 'Close');
$can_CREATE = TRUST($cUSERCODE, 'PREVILLEGE_1ADD');
$can_UPDATE = TRUST($cUSERCODE, 'PREVILLEGE_2UPD');
$cLIST_USER = S_MSG('FU03', 'User List');
$cDTL_USER 	= S_MSG('F182', 'User Detail');
$cKD_USR 	= S_MSG('F003', 'Kode');
$cNM_USR 	= S_MSG('CB04', 'Nama');
$cAL_USR 	= S_MSG('TU06', 'Alamat User');
$cTELPON 	= S_MSG('F006', 'Telpon');
$cEMAIL 	= S_MSG('TU07', 'Email User');
$cBAHASA 	= S_MSG('TU08', 'Bahasa');

// $cFOLDER_FOTO = S_PARA('FTP_USER_FOLDER', '/home/riza/images/admin');
$cFOLDER_FOTO = '/data/images_user/';
$cACTION = (isset($_GET['_a']) ? $cACTION = $_GET['_a'] : '');

$qQUERY = OpenTable('TbUser', "USER_CODE!='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
switch ($cACTION) {
	default:
		APP_LOG_ADD($cHEADER, 'view');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE == 1 ? ['<a href="?_a=' . md5('cr34t3') . '"><i class="fa fa-plus-square"></i>' . S_MSG('KA11', 'Add new') . '</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
			TDIV();
			TABLE('example');
			THEAD([$cKD_USR, $cNM_USR]);
			echo '<tbody>';
			while ($aREC_TB_USER = SYS_FETCH($qQUERY)) {
				$aCOL = [$aREC_TB_USER['USER_CODE'], DECODE($aREC_TB_USER['USER_NAME'])];
				$cREFF = "<a href=?_a=" . md5('up__d4t3') . "&_u=" . md5($aREC_TB_USER['USER_CODE']) . ">";
				$aREFF = [$cREFF, $cREFF];
				TDETAIL($aCOL, [], '', $aREFF);
			}
			echo '</tbody>';
			eTABLE();
			eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
		break;

	case md5('cr34t3'):
		$cADD_REC	= S_MSG('TU26', 'Tambah User');
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([4, 4, 4, 6], '700', $cKD_USR);
					INPUT('text', [2, 2, 2, 6], '900', 'ADD_USER_CODE', '', 'autofocus', '', '', 0, '', 'fix');
					LABEL([4, 4, 4, 6], '700', $cNM_USR);
					INPUT('text', [4, 4, 4, 6], '900', 'ADD_USER_NAME', '', '', '', '', 0, '', 'fix');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
		break;

	case 'Upd_Scope':
		$cUSER_SCP = $_GET['id'];
		$cCUSTOMER = $_POST['UPD_CUSTOMER'];
		$cLOKASI = $_POST['UPD_LOKASI'];
		$cJABATAN = $_POST['UPD_JOB'];
		RecUpdate(
			'UserScope',
			['USER_CUST', 'USER_LOCS', 'USER_JOB', 'UP_DATE', 'UPD_DATE'],
			[$cCUSTOMER, $cLOKASI, $cJABATAN, $cUSERCODE, date("Y-m-d H:i:s")],
			"REC_NO='$cUSER_SCP'"
		);
		APP_LOG_ADD($cHEADER, 'update scope : ' . $cUSER_SCP);
		SYS_DB_CLOSE($DB2);
		header('location:tb_user.php');
		break;

	case md5('updateScope'):
		$cEDIT_TBL	= S_MSG('TU81', 'Edit User Scope');
		$cHAPUS		= S_MSG('H007', 'Apakah Anda benar-benar mau menghapusnya?');

		$can_UPDATE = TRUST($cUSERCODE, 'PREVILLEGE_SCOPE_UPD');
		$can_DELETE = TRUST($cUSERCODE, 'PREVILLEGE_SCOPE_DEL');
		$nREC_NO = $_GET['_r'];
		$cSCOPE_CUST = $cSCOPE_LOCS = $cSCOPE_JOBS = '';
		$qDSCOPE = OpenTable('UserScope', "REC_NO='$nREC_NO'");
		if ($aREC_SCOPE = SYS_FETCH($qDSCOPE)) {
			$cSCOPE_CUST = $aREC_SCOPE['USER_CUST'];
			$cSCOPE_LOCS = $aREC_SCOPE['USER_LOCS'];
			$cSCOPE_JOBS = $aREC_SCOPE['USER_JOB'];
		}
		DEF_WINDOW('Edit User Scope');
		$aACT = ($can_DELETE == 1 ? ['<a href="?_a=DbDelScope&_r=' . $nREC_NO . '" onClick="return confirm(' . "'" . $cHAPUS . "'" . ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
		TFORM($cEDIT_TBL, '?_a=Upd_Scope&id=' . $nREC_NO, $aACT, $cHELP_FILE);
		TDIV();
		LABEL([3, 3, 3, 6], '700', S_MSG('PF15', 'Customer'));
		SELECT([9, 9, 9, 6], 'UPD_CUSTOMER', '', '', 'select2');
		echo '<option value=""> All </option>';
		$qCUSTOMER = OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
		while ($aCUSTOMER = SYS_FETCH($qCUSTOMER)) {
			if ($aREC_SCOPE['USER_CUST'] == $aCUSTOMER['CUST_CODE'])
				echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$aREC_SCOPE[USER_CUST]' >$aCUSTOMER[CUST_NAME]</option>";
			else
				echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME]</option>";
		}
		echo '</select>';
		CLEAR_FIX();

		LABEL([3, 3, 3, 6], '700', S_MSG('PF16', 'Lokasi'));
		SELECT([9, 9, 9, 6], 'UPD_LOKASI', '', '', 'select2');
		echo '<option value=""> All </option>';
		$qLOKASI = OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
		while ($aLOKASI = SYS_FETCH($qLOKASI)) {
			if ($aREC_SCOPE['USER_LOCS'] == $aLOKASI['LOKS_CODE'])
				echo "<option value='$aLOKASI[LOKS_CODE]' selected='$aREC_SCOPE[USER_LOCS]' >$aLOKASI[LOKS_NAME]</option>";
			else
				echo "<option value='$aLOKASI[LOKS_CODE]'  >$aLOKASI[LOKS_NAME]</option>";
		}
		echo '</select>';
		CLEAR_FIX();

		LABEL([3, 3, 3, 6], '700', S_MSG('PA43', 'Jabatan'));
		SELECT([9, 9, 9, 6], 'UPD_JOB', '', '', 'select2');
		echo '<option value=""> All </option>';
		$qOCCUP = OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
		while ($aJOB = SYS_FETCH($qOCCUP)) {
			if ($aREC_SCOPE['USER_JOB'] == $aJOB['JOB_CODE'])
				echo "<option value='$aJOB[JOB_CODE]' selected='$aREC_SCOPE[USER_JOB]'" . ">" . $aJOB['JOB_NAME'] . "</option>";
			else
				echo "<option value='$aJOB[JOB_CODE]'  >$aJOB[JOB_NAME]</option>";
		}
		echo '</select>';
		CLEAR_FIX();
		SAVE($can_UPDATE ? $cSAVE : '');
		eTDIV();
		eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
		break;

	case md5('up__d4t3'):
		$cEDIT_TBL	= S_MSG('TU13', 'Edit User');
		$cHAPUS		= S_MSG('H007', 'Apakah Anda benar-benar mau menghapusnya?');
		$cTTIP_ALAMAT	= S_MSG('TU86', 'Klik dan masukkan alamat user');

		$can_UPDATE = TRUST($cUSERCODE, 'PREVILLEGE_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'PREVILLEGE_3DEL');
		$can_DELPAS = TRUST($cUSERCODE, 'PREVILLEGE_5BLANK');
		$cKODE_USER = $_GET['_u'];		// md5
		$qQUERY = OpenTable('TbUser', " md5(USER_CODE)='$cKODE_USER' and DELETOR=''");
		$REC_TB_USER = SYS_FETCH($qQUERY);
		$cUSER_CODE = $REC_TB_USER['USER_CODE'];
		$cUSER_NAME = $REC_TB_USER['USER_NAME'];
		DEF_WINDOW($cEDIT_TBL);
			$aACT = [];
			if ($can_DELETE == 1)
				array_push($aACT, '<a href="?_a=' . md5('DELETE_USER') . '&_id=' . $cUSER_CODE . '" onClick="return confirm(' . "'" . S_MSG('H007', 'Apakah Anda benar-benar mau menghapusnya?') . "'" . ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>');
			if ($can_DELETE == 1)
				array_push($aACT, '<a href="#delete_password" data-toggle="modal" > <i class="fa fa-times"></i>Delete Password</a>');
			TFORM($cEDIT_TBL, '?_a=rubah&_id=' . $cUSER_CODE, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4, 4, 4, 6], '700', $cKD_USR);
					INPUT('text', [2, 2, 2, 6], '900', 'EDIT_USER_CODE', $cUSER_CODE, '', '', '', 0, 'disabled', 'fix');
					LABEL([4, 4, 4, 6], '700', $cNM_USR);
					INPUT('text', [4, 4, 4, 6], '900', 'UPD_USER_NAME', DECODE($cUSER_NAME), '', '', '', 0, '', 'fix');
					echo '<br>';
					$_SESSION['KD_USER']     = $cUSER_CODE;
					require_once("tb_user_tab.php");
					$cSAVE		= ($can_UPDATE ? $cSAVE : '');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
?>
			<div class="modal" id="delete_password" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<form action="?_a=del_pass&_id=<?php echo $cKODE_USER ?>" method="post" enctype='multipart/form-data'>
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title"><?php echo S_MSG('TU66', 'Kosongkan Password') ?></h4>
							</div>
							<div class="modal-body">
								<p><?php echo S_MSG('TU68', 'Benar password mau di kosongkan ?') ?></p>
							</div>
							<div class="modal-footer">
								<input type="submit" class="btn btn-danger" value=Delete>
								<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
							</div>
						</div>
					</form>
				</div>
			</div>
<?php
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
	break;

	case 'del_pass':
		$cKODE_USER = $_GET['_id'];
		$qQUERY = OpenTable('TbUser', "md5(USER_CODE)='$cKODE_USER' and DELETOR=''");
		if (SYS_ROWS($qQUERY) == 0) {
			MSG_INFO('Kode User tidak ditemukan');
			return;
		} else {
			RecUpdate('TbUser', ['PASSWORD'], [''], "md5(USER_CODE)='$cKODE_USER'");
			APP_LOG_ADD($cHEADER, 'delete passw : ' . $KODE_CRUD);
			echo "<script> window.history.back();	</script>";
		}
		break;
	case 'DbDelScope':
		$KODE_CRUD = $_GET['_r'];
		RecUpdate('UserScope', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, date("Y-m-d H:i:s")], "REC_NO='$KODE_CRUD'");
		APP_LOG_ADD($cHEADER, 'delete scope : ' . $KODE_CRUD);
		SYS_DB_CLOSE($DB2);
		header('location:tb_user.php');
		break;

	case 'tambah':
		$cUSER_CODE = ENCODE($_POST['ADD_USER_CODE']);
		if ($cUSER_CODE == '') {
			MSG_INFO(S_MSG('TU25', 'Kode User tidak boleh kosong'));
			return;
		}
		$cUSER_NAME = ENCODE($_POST['ADD_USER_NAME']);
		if ($cUSER_NAME == '') {
			MSG_INFO(S_MSG('TU30', 'Nama User tidak boleh kosong'));
			return;
		}
		$qQUERY = OpenTable('TbUser', "USER_CODE='$cUSER_CODE' and DELETOR=''");
		if (SYS_ROWS($qQUERY) > 0) {
			MSG_INFO(S_MSG('TU31', 'Kode User sudah ada'));
		} else {
			RecCreate('TbUser',
				['USER_CODE', 'USER_NAME', 'ENTRY', 'DATE_ENTRY', 'SYS_MODULE', 'APP_CODE'],
				[$cUSER_CODE, $cUSER_NAME, $cUSERCODE, date("Y-m-d H:i:s"), $_SESSION['gMODULE_USER'], $cAPP_CODE]
			);
			// RecCreate('UserPren', ['USER_CODE', 'USER_PREN', 'APP_CODE'], [$cUSER_CODE, 'Riza', $cAPP_CODE]);
			// RecCreate('UserPren', ['USER_CODE', 'USER_PREN', 'APP_CODE'], ['Riza', $cUSER_CODE, $cAPP_CODE]);
			$ADD_LOG	= APP_LOG_ADD($cHEADER, 'add : ' . $cUSER_CODE);
		}
		SYS_DB_CLOSE($DB2);
		header('location:tb_user.php');
		break;

	case 'rubah':
		$cKODE_USER = $_GET['_id'];
		$aUSER	= SYS_FETCH(OpenTable('TbUser', "USER_CODE='$cKODE_USER' and DELETOR=''"));
		$cUSER_CODE = $aUSER['USER_CODE'];
		$NOW 	= date("Y-m-d H:i:s");
		$cUSER_NAME = ENCODE($_POST['UPD_USER_NAME']);
		$cCUSTOMER = $_POST['NEW_CUSTOMER'];
		$cLOCATION = $_POST['NEW_LOCATION'];
		$cJOB = $_POST['NEW_JOB'];

		$cFILE_IMAGE = $cKODE_USER . '.jpg';
		$cFOLDER_IMG = S_PARA('FTP_USER_FOLDER', '../www/images/admin/');
		$cFILE_FOTO = $cFOLDER_IMG . $cFILE_IMAGE;
		if (isset($_FILES['FOTO_ADMIN'])) {
			$cFILE_FOLDER   = $_FILES['FOTO_ADMIN']['name'];
			if (!empty($cFILE_FOLDER)) {
				if (move_uploaded_file($_FILES['FOTO_ADMIN']['tmp_name'], $cFILE_FOTO)) {
					// unlink($cFILE_FOTO_USER);
					APP_LOG_ADD($cHEADER, 'add / update foto : ' . $cKODE_USER);
				} else {
					print_r2('Foto user gagal upload => ' . $cFILE_FOLDER . ' => ' . $cFILE_FOTO);
				}
			} else {
				print_r2('Foto User belum dipilh');
			}
		}

		RecUpdate('TbUser', ['USER_NAME', 'UP_DATE', 'UPD_DATE'], [$cUSER_NAME, $cUSERCODE, $NOW], "APP_CODE='$cAPP_CODE' and md5(USER_CODE)='$cKODE_USER'");

		$qQUERY = OpenTable('UserDtl', "USER_CODE='$cKODE_USER' and REC_ID not in ( select DEL_ID from logs_delete)");
		if (SYS_ROWS($qQUERY) == 0) {
			RecCreate(
				'UserDtl',
				['USER_CODE', 'ENTRY', 'REC_ID', 'APP_CODE'],
				[$cKODE_USER, $cUSERCODE, NowMSecs(), $cAPP_CODE]
			);
		}
		RecUpdate(
			'UserDtl',
			['USER_ADDR', 'USER_PHON', 'USER_EMAIL'],
			[$_POST['EDIT_USER_ADDR1'], $_POST['EDIT_USER_PHON'], $_POST['EDIT_USER_EMAIL']],
			"APP_CODE='$cAPP_CODE' and USER_CODE='$cKODE_USER'"
		);

		if ($cCUSTOMER or $cLOCATION or $cJOB) {
			RecCreate(
				'UserScope',
				['USER_CODE', 'USER_CUST', 'USER_LOCS', 'USER_JOB', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'],
				[$cKODE_USER, $cCUSTOMER, $cLOCATION, $cJOB, $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s")]
			);
			$ADD_LOG	= APP_LOG_ADD($cHEADER, 'add scope : ' . $cKODE_USER);
		}



		//	update previllages
		$qPRVLG = OpenTable('SysPrevillage', "APP_CODE='$cAPP_CODE' and USER_CODE='$cUSERCODE' and DELETOR=''");
		$I = 1;
		while ($aPRVLG = SYS_FETCH($qPRVLG)) {
			$cTRST = $aPRVLG['TRUSTEE_CODE'];
			if (isset($_POST[$cTRST])) {
				// print_r2($cTRST);
				$qQUERY = OpenTable('SysPrevillage', "TRUSTEE_CODE='$cTRST' and APP_CODE='$cAPP_CODE' and USER_CODE='$cKODE_USER' and DELETOR=''");
				if (SYS_ROWS($qQUERY) == 0) {
					RecCreate(
						'SysPrevillage',
						['USER_CODE', 'JOB_CODE', 'TRUSTEE_CODE', 'ENTRY', 'APP_CODE', 'DATE_ENTRY'],
						[$cKODE_USER, $aPRVLG['JOB_CODE'], $cTRST, $cUSERCODE, $cAPP_CODE, date("Y-m-d H:i:s")]
					);
				}
			} else {
				RecDelete('SysPrevillage', "TRUSTEE_CODE='$cTRST' and APP_CODE='$cAPP_CODE' and USER_CODE='$cKODE_USER'");
			}
		}

		APP_LOG_ADD($cHEADER, 'update : ' . $cUSER_NAME);
		echo "<script> window.history.back();	</script>";
		//	header('location:tb_user.php');
		break;

	case md5('DELETE_USER'):
		$KODE_CRUD = $_GET['_id'];
		$NOW = date("Y-m-d H:i:s");
		RecDelete('TbUser', "USER_CODE='$KODE_CRUD'");
		RecDelete('UserDtl', "APP_CODE='$cAPP_CODE' and USER_CODE='$KODE_CRUD'");
		RecDelete('UserScope', "APP_CODE='$cAPP_CODE' and USER_CODE='$KODE_CRUD'");

		RecDelete('SysPrevillage', "USER_CODE='$KODE_CRUD'");
		// RecDelete('UserPren', "USER_CODE='$KODE_CRUD'");
		$cUSER_FOTO = S_PARA('FTP_USER_FOLDER', 'https://www.fahlevi.co/images/admin/') . $KODE_CRUD . '.jpg';
		unlink($cUSER_FOTO);
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'delete : ' . $KODE_CRUD);
		header('location:tb_user.php');
}
?>