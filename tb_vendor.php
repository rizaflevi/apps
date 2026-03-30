<?php
//	tb_vendor.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Supplier.pdf';

	$nGROUP_VENDOR = 0;
	$qGROUP = OpenTable('TbVendorGrp', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$nGROUP_VENDOR = SYS_ROWS($qGROUP);

	$qQUERY = OpenTable('Vendor', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cHEADER 		= S_MSG('F104','Tabel Supplier/Vendor');
	$cKODE_SUPP		= S_MSG('F003','Kode');
	$cNM_ANG 		= S_MSG('F004','Nama');
	$cAL_ANG 		= S_MSG('F101','Alamat Supplier');
	$cNO_TELP		= S_MSG('F006','No. Telpon');
	$cNO_HP			= S_MSG('F007','Nomor HP');
	$cALAMAT2		= S_MSG('NL54','Kota');
	$cEMAIL_ADDRESS	= S_MSG('F105','Email address');
	$cAP_ACCOUNT	= S_MSG('F103','Account Utang');
	$cINV_ACCOUNT	= S_MSG('F120','Account Persediaan');
	$cRET_ACCOUNT	= S_MSG('F121','Account Retur');
	$cWEB_SITE		= S_MSG('MN13','Web site');
	$cLAMA_KREDIT 	= S_MSG('F106','Lama Kredit (hr)');
	$cKRD_LIMIT		= S_MSG('F091','Kredit Limit');
	
	$cDATA_UMUM		= S_MSG('F024','Data Umum');
	$cDETIL			= S_MSG('F010','Detil');
	$cKONTAK		= S_MSG('CU21','Kontak');
	$cOTHERS		= S_MSG('F125','Other information');
	$cGBR_CUST		= S_MSG('F128','Foto Supplier');
	
	$cHAPUS=S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cADD_REC=S_MSG('F190','Tambah Supplier');
	$cEDIT_TBL=S_MSG('F191','Edit Tabel Supplier');
	$cDAFTAR	= S_MSG('F119','Daftar Supplier');
	$cMSG_EXIST	= S_MSG('F192','Kode Supplier sudah ada');
	
	$cTTIP_KODE	= S_MSG('F107','Setiap Supplier di beri kode untuk memudahkan meng akses data supplier');
	$cTTIP_NAMA	= S_MSG('F108','Nama supplier, mis. nama orangnya, nama perusahaan dll');
	$cTTIP_ALM1	= S_MSG('F101','Alamat supplier');
	$cTTIP_ALM2	= S_MSG('F109','Tambahan alamat supplier');
	$cTTIP_TELP	= S_MSG('F110','Nomor telpon supplier yang bisa dihubungi');
	$cTTIP_FAXS	= S_MSG('F102','Diisi dengan nomor Fax supplier');
	$cTTIP_MAIL	= S_MSG('F112','Diisi dengan Email supplier');

	$cSAVE_DATA	=S_MSG('F301','Save');
	$cCLOSE_DATA=S_MSG('F302','Close');

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];
switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'VENDOR_1ADD');
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('CREATE_VENDOR'). '"><i class="fa fa-plus-square"></i>'. $cADD_REC.'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_SUPP, $cNM_ANG, $cAL_ANG]);
						echo '<tbody>';
							while($aREC_DISP=SYS_FETCH($qQUERY)) {
								echo '<tr>';
									$cICON = 'fa-male';
									echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
									echo "<td><span><a href='?_a=".md5('UPDATE_VENDOR')."&_v=".md5($aREC_DISP['KODE_VND'])."'>".$aREC_DISP['KODE_VND']."</a></span></td>";
									echo "<td><span><a href='?_a=".md5('UPDATE_VENDOR')."&_v=".md5($aREC_DISP['KODE_VND'])."'>".$aREC_DISP['NAMA_VND']."</a></span></td>";
									echo '<td>'.$aREC_DISP['ALAMAT'].'</td>';
								echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('CREATE_VENDOR'):
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_SUPP);
					INPUT('text', [2,2,2,6], '900', 'KODE_TBL', '', 'focus', '', '', 0, '', 'fix', $cTTIP_KODE);
					LABEL([3,3,3,6], '700', $cNM_ANG);
					INPUT('text', [6,6,6,6], '900', 'ADD_NAMA_VND', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					LABEL([3,3,3,6], '700', $cAL_ANG);
					INPUT('textarea', [6,6,6,6], '900', 'ADD_ALAMAT', '', '', '', '', 0, '', 'fix', $cTTIP_ALM1, '', '', 'autogrow', '5');
					LABEL([3,3,3,6], '700', $cALAMAT2);
					INPUT('text', [4,4,4,6], '900', 'ADD_RT', '', '', '', '', 0, '', 'fix', $cTTIP_ALM2);
					LABEL([3,3,3,6], '700', $cNO_TELP);
					INPUT('text', [4,4,4,6], '900', 'ADD_NO_TELPON', '', '', '', '', 0, '', 'fix', $cTTIP_TELP);
					LABEL([3,3,3,6], '700', $cNO_HP);
					INPUT('text', [4,4,4,6], '900', 'ADD_FAX', '', '', '', '', 0, '', 'fix', $cTTIP_FAXS);
					LABEL([3,3,3,6], '700', $cEMAIL_ADDRESS);
					INPUT('text', [4,4,4,6], '900', 'ADD_EMAIL_VND', '', '', '', '', 0, '', 'fix', $cTTIP_MAIL);
					echo '<br>';
					TAB(['UMUM', 'DETIL', 'FOTO'], 
						['fa-user', 'fa-cog', 'fa-home'], 
						[$cDATA_UMUM, $cDETIL, $cOTHERS]);
					echo '<div class="tab-content primary">';
						echo '<div class="tab-pane fade in active" id="UMUM">';
							LABEL([3,3,3,6], '700', S_MSG('CB64','Kelompok'));
							SELECT([3,3,3,6], 'ADD_GROUP');
								$qGROUP=OpenTable('TbVendorGrp', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'VG_DESC');
								echo "<option value=' '  > </option>";
								while($aREC_GR_DATA=SYS_FETCH($qGROUP)){
									echo "<option value='$aREC_GR_DATA[VG_CODE]'  >$aREC_GR_DATA[VG_DESC]</option>";
								}
							echo '</select>';
							CLEAR_FIX();
						eTDIV();
						echo '<div class="tab-pane fade" id="DETIL">';
							LABEL([3,3,3,6], '700', $cAP_ACCOUNT);
							SELECT([6,6,6,6], 'ADD_AP_ACCOUNT', '', '', 'select2');
								echo "<option value=' '  > </option>";
								$qACCOUNT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'ACCOUNT_NO');
								while($aREC_ACCOUNT=SYS_FETCH($qACCOUNT)){
									echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
								}
							echo '</select>';
							CLEAR_FIX();
							LABEL([3,3,3,6], '700', $cINV_ACCOUNT);
							SELECT([6,6,6,6], 'ADD_INV_ACCOUNT', '', '', 'select2');
								echo "<option value=' '  > </option>";
								$qACCOUNT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'ACCOUNT_NO');
								while($aREC_ACCOUNT=SYS_FETCH($qACCOUNT)){
									echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
								}
							echo '</select>';
							CLEAR_FIX();
						eTDIV();
?>
						<div class="tab-pane fade" id="FOTO">
							<div class="form-group">
								<label class="form-label" for="field-1">Profile Image</label>
								<span class="desc"></span>
								<img class="img-responsive" src="data/patients/patient-1.jpg" alt="" style="max-width:220px;">
								<div class="controls">
										<input type="file" class="form-control" id="field-5">
								</div>
							</div>
						</div>
<?php
				eTDIV();
				CLEAR_FIX();
				SAVE($cSAVE_DATA);
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('UPDATE_VENDOR'):
		$q_VENDOR = OpenTable('Vendor', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and md5(KODE_VND)='$_GET[_v]'");
		if(SYS_ROWS($q_VENDOR)==0) header('location:tb_vendor.php');

		$r_VENDOR=SYS_FETCH($q_VENDOR);
		$cFILE_FOTO_MEMBER = 'data/images/VENDOR_'.str_pad((string)$r_VENDOR['REC_ID'], 11, '0', STR_PAD_LEFT).'.jpg';
		if(file_exists($cFILE_FOTO_MEMBER)==0)	$cFILE_FOTO_MEMBER = "data/images/no.jpg";

		DEF_WINDOW($cEDIT_TBL);
			$can_DELETE = TRUST($cUSERCODE, 'VENDOR_3DEL');
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DELETE_VENDOR').'&id='. md5($r_VENDOR['KODE_VND']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&id='.$r_VENDOR['KODE_VND'], $aACT, $cHELP_FILE);
				LABEL([3,3,3,6], '700', $cKODE_SUPP);
				INPUT('text', [2,2,2,6], '900', 'KD_SUPP', ENCODE($r_VENDOR['KODE_VND']), '', '', '', 0, 'disable', 'fix', $cTTIP_KODE);
				LABEL([3,3,3,6], '700', $cNM_ANG);
				INPUT('text', [6,6,6,6], '900', 'UPD_NAMA_VND', ENCODE($r_VENDOR['NAMA_VND']), 'focus', '', '', 0, '', 'fix', $cTTIP_NAMA);
				LABEL([3,3,3,6], '700', $cAL_ANG);
				INPUT('text', [6,6,6,6], '900', 'EDIT_ALAMAT1', ENCODE($r_VENDOR['ALAMAT']), '', '', '', 0, '', 'fix', $cTTIP_ALM1);
				LABEL([3,3,3,6], '700', $cALAMAT2);
				INPUT('text', [6,6,6,6], '900', 'EDIT_ALAMAT2', ENCODE($r_VENDOR['ALAMAT2']), '', '', '', 0, '', 'fix', $cTTIP_ALM2);
				LABEL([3,3,3,6], '700', $cNO_TELP);
				INPUT('number', [6,6,6,6], '900', 'EDIT_NO_TELPON', ENCODE($r_VENDOR['TELPON']), '', '', '', 0, '', 'fix', $cTTIP_TELP);
				LABEL([3,3,3,6], '700', $cNO_HP);
				INPUT('number', [6,6,6,6], '900', 'EDIT_FAX', ENCODE($r_VENDOR['FAX']), '', '', '', 0, '', 'fix', $cTTIP_FAXS);
				LABEL([3,3,3,6], '700', $cEMAIL_ADDRESS);
				INPUT('text', [6,6,6,6], '900', 'EDIT_EMAIL_VND', ENCODE($r_VENDOR['EMAIL_VND']), '', '', '', 0, '', 'fix', $cTTIP_MAIL);
				eTDIV();
					echo '<br>';
					TAB(['GENERAL', 'DETAIL', 'FOTO'], ['fa-user', 'fa-home', 'fa-cog'], [$cDATA_UMUM, $cDETIL, $cOTHERS]);
					echo '<div class="tab-content primary">';
						echo '<div class="tab-pane fade in active" id="GENERAL">';
								if($nGROUP_VENDOR > 0) {
									LABEL([3,3,3,6], '700', S_MSG('CB64','Kelompok'));
									TDIV(6,6,6,6);
									SELECT([6,6,6,6], 'PILIH_KELOMPOK', '', '', 'select2');
									$qVENDOR=OpenTable('TbVendorGrp', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
										while($aREC_GR_DATA=SYS_FETCH($qVENDOR)){
											if($r_VENDOR['VND_GROUP']==$aREC_GR_DATA['VG_CODE']){
												echo "<option value='$aREC_GR_DATA[VG_CODE]' selected='$r_VENDOR[VND_GROUP]' >$aREC_GR_DATA[VG_DESC]</option>";
											} else {
												echo "<option value='$aREC_GR_DATA[VG_CODE]'  >$aREC_GR_DATA[VG_DESC]</option>";
											}
										}
									echo '</select>';
									eTDIV();
								}
								echo '<br>';
						eTDIV();
						echo '<div class="tab-pane fade" id="DETAIL">';
							LABEL([3,3,3,6], '700', $cAP_ACCOUNT);
							TDIV(6,6,6,6);
								SELECT([6,6,6,6], 'EDIT_AP_ACCOUNT', '', '', 'select2');
									echo "<option value=' '  > </option>";
									$qACCOUNT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
									while($aREC_ACCOUNT=SYS_FETCH($qACCOUNT)){
										if($r_VENDOR['AP_ACCOUNT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
											echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$r_VENDOR[AP_ACCOUNT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
										} else {
										echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
									}
									echo '</select>';
							eTDIV();
							CLEAR_FIX();
							LABEL([3,3,3,6], '700', $cINV_ACCOUNT);
							TDIV(6,6,6,6);
								SELECT([6,6,6,6], 'EDIT_INV_ACCOUNT', '', '', 'select2');
									echo "<option value=' '  > </option>";
									$qACCOUNT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
									while($aREC_ACCOUNT=SYS_FETCH($qACCOUNT)){
										if($r_VENDOR['INV_ACCT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
											echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$r_VENDOR[INV_ACCT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
										} else {
										echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
									}
									echo '</select>';
							eTDIV();
							CLEAR_FIX();
						eTDIV();
?>
							<div class="tab-pane fade" id="FOTO">
								<div class="form-group">
									<label class="form-label" for="field-1"><?php echo $cGBR_CUST?></label>
									<span class="desc"></span>																	
									<img class="img-responsive" src="<?php echo $cFILE_FOTO_MEMBER?>" alt="" style="max-width:220px;">
									<div class="controls">
											<input type="file" class="form-control" id="field-5">
									</div>
								</div>
							</div>
<?php
					eTDIV();
				SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case 'tambah':
		$cKODE_VND	= ENCODE($_POST['KODE_TBL']);	
		if($cKODE_VND=='') {
			MSG_INFO(S_MSG('F193','Kode Supplier tidak boleh kosong'));
			return;
		}
		$q_VENDOR = OpenTable('Vendor', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and KODE_VND='$cKODE_VND'");
		if(SYS_ROWS($q_VENDOR)>0){
			MSG_INFO($cMSG_EXIST);
			return;
		}
		$cNAMA_VND	= ENCODE($_POST['ADD_NAMA_VND']);
		$cALMT_VND	= ENCODE($_POST['ADD_ALAMAT']);	
		$cRT_VND	= ENCODE($_POST['ADD_RT']);	
		$cTELP_VND	= ENCODE($_POST['ADD_NO_TELPON']);	
		$cFAXS_VND	= ENCODE($_POST['ADD_FAX']);
		$cEMIL_VND	= ENCODE($_POST['ADD_EMAIL_VND']);
		RecCreate('Vendor', ['REC_ID', 'APP_CODE', 'KODE_VND', 'NAMA_VND', 'VND_GROUP', 'EMAIL_VND', 'AP_ACCOUNT', 'INV_ACCT', 'ALAMAT', 'ALAMAT2', 'TELPON', 'FAX', 'ENTRY'],
			[NowMSecs(), $cAPP_CODE, $cKODE_VND, $cNAMA_VND, $_POST['ADD_GROUP'], $cEMIL_VND, $_POST['ADD_AP_ACCOUNT'], $_POST['ADD_INV_ACCOUNT'], $cALMT_VND, $cRT_VND, $cTELP_VND, $cFAXS_VND, $cUSERCODE]);
		header('location:tb_vendor.php');
	break;

	case 'rubah':
		$KODE_CRUD=$_GET['id'];
		$cNAMA_VND	= ENCODE($_POST['UPD_NAMA_VND']);	
		$cALMT_VND	= ENCODE($_POST['EDIT_ALAMAT1']);	
		$cALM2_VND	= ENCODE($_POST['EDIT_ALAMAT2']);
		$cTELP_VND	= ENCODE($_POST['EDIT_NO_TELPON']);
		$cFAXS_VND	= ENCODE($_POST['EDIT_FAX']);
		$cEMIL_VND	= ENCODE($_POST['EDIT_EMAIL_VND']);
		$cV_GROUP	= ($nGROUP_VENDOR > 0 ? $_POST['PILIH_KELOMPOK'] : '');
		RecUpdate('Vendor', ['NAMA_VND', 'VND_GROUP', 'AP_ACCOUNT', 'INV_ACCT', 'ALAMAT', 'ALAMAT2', 'TELPON', 'FAX', 'EMAIL_VND'], 
			[$cNAMA_VND, $cV_GROUP, $_POST['EDIT_AP_ACCOUNT'], $_POST['EDIT_INV_ACCOUNT'], $cALMT_VND, $cALM2_VND, $cTELP_VND, $cFAXS_VND, $cEMIL_VND],
			"APP_CODE='$cAPP_CODE' and KODE_VND='$KODE_CRUD'");
		header('location:tb_vendor.php');
	break;

	case md5('DELETE_VENDOR'):
		RecSoftDel($_GET['_id']);
		header('location:tb_vendor.php');
}
?>

