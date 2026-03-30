<?php
//	tb_customer.php //
//	TODO : tab lokasi, uang makan, catatan

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Customer.pdf';
	$l_SCHOOL = IS_SCHOOL($cAPP_CODE);
	if($l_SCHOOL)	$cHELP_FILE = 'Doc/School - Tabel - Siswa.pdf';

	$cHEADER 		= S_MSG('F039','Tabel Pelanggan');
	$cKODE_CUST		= S_MSG('F003','Kode');
	$cNM_ANG 		= S_MSG('F004','Nama');
	$cAL_ANG 		= S_MSG('F005','Alamat');
	$cNO_TELP		= S_MSG('F006','No. Telpon');
	$cNO_HP			= S_MSG('F007','Nomor HP');
	$cALAMAT2		= S_MSG('NL54','Kota');
	$cTAKING_ORDER	= S_MSG('F013','Taking Order');
	$cNO_REK		= S_MSG('F030','Kode Account');
	$cCANVAS		= S_MSG('F014','Canvas');
	$cKELOMPOK		= S_MSG('F009','Kelompok');
	$cWEB_SITE		= S_MSG('MN13','Web site');
	
	$cDATA_UMUM		= S_MSG('F024','Data Umum');
	$cDETIL			= S_MSG('F010','Detil');
	$cKONTAK		= S_MSG('CU21','Kontak');
	$cGBR_CUST		= S_MSG('F011','Gbr Anggota');
	
	$cCARA_ORDER	= S_MSG('F012','Cara Order');
	$cTIPE_CUST		= S_MSG('F008','Tipe');
	$cAREA_CUST		= S_MSG('RS15','Area');
	$cFOTO_CUST		= S_MSG('F017','Foto');
	
	$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cEDIT_TBL		= S_MSG('F031','Edit Tabel Pelanggan');
	
	$cSAVE_DATA		= S_MSG('F301','Save');
	$cCLOSE_DATA	= S_MSG('F302','Close');

	$is_OUTSOURCING=IS_OUTSOURCING($cAPP_CODE);
	$ada_DIST=IS_TPR($cAPP_CODE);

	$cACTION='';	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

	$cTBL_LIST_DISPLAY_COLOR = S_PARA('_DISP_TABLE_LIST_FCOLOR','black');
	$can_GENERAL = TRUST($cUSERCODE, 'TB_CUSTOMER_UMUM_VIE');
	$can_DETAIL = TRUST($cUSERCODE, 'TB_CUSTOMER_DTL_VIEW');
	$can_FOTO 	= TRUST($cUSERCODE, 'TB_CUST_FOTO_VIEW');

switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'TB_CUSTOMER_1ADD');

		$cScope = "APP_CODE='$cAPP_CODE' and DELETOR=''";
		$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		if (SYS_ROWS($qSCOPE)>0) $cScope .= " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR='')";
		$qCUST=OpenTable('TbCustomer', $cScope);
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('CREATE_CUST'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_CUST, $cNM_ANG, $cAL_ANG]);
						while($a_LANGGAN=SYS_FETCH($qCUST)) {
							$cREFF="<a href=?_a=".md5('UPD_CUST')."&_c=".md5($a_LANGGAN['CUST_CODE']).">";
							$aCOL=[DECODE($a_LANGGAN['CUST_CODE']), DECODE($a_LANGGAN['CUST_NAME']), DECODE($a_LANGGAN['CUST_ADDRESS'])];
							$aREFF=[$cREFF, $cREFF, ''];
							TDETAIL($aCOL, [], '', $aREFF);
						}
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
	break;

	case md5('CREATE_CUST'):
		$cADD_REC		= S_MSG('D033','Tambah Pelanggan');
		if ($is_OUTSOURCING==1){
			$qCGROUP = OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
			if(!$qCGROUP) {
				MSG_INFO('Group Customer belum diisi');
				return;
			}
			if (SYS_ROWS($qCGROUP)==0) {
				MSG_INFO('Group Customer belum diisi');
				return;
			}
		}
		$q_TIPE_OTL=OpenTable('TbCustType', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$nTYPE_CUSTOMER = SYS_ROWS($q_TIPE_OTL);
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=ADD_CUST','', $cHELP_FILE);
				LABEL([4,4,4,6], '700', $cKODE_CUST);
				INPUT('text', [2,2,2,3], '900', 'KODE_TBL', '', '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cNM_ANG);
				INPUT('text', [6,6,6,6], '900', 'ADD_CUST_NAME', '', '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cAL_ANG);
				INPUT('text', [6,6,6,6], '900', 'ADD_ALAMAT', '', '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cALAMAT2);
				INPUT('text', [6,6,6,6], '900', 'ADD_CITY', '', '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cNO_TELP);
				INPUT('text', [6,6,6,6], '900', 'ADD_NO_TELPON', '', '', '', '', 0, '', 'fix');
				LABEL([4,4,4,6], '700', $cNO_HP);
				INPUT('text', [6,6,6,6], '900', 'ADD_FAX', '', '', '', '', 0, '', 'fix');
				TDIV();
					echo '<br>';
					$aTAB=[];	$aICON=[];	$aCAPTION=[];
					if($can_GENERAL)	{ array_push($aTAB, 'TAB_UMUM');	array_push($aICON, 'fa-user');	array_push($aCAPTION, $cDATA_UMUM);	}
					if($can_DETAIL)		{ array_push($aTAB, 'TAB_DETAIL');	array_push($aICON, 'fa-home');	array_push($aCAPTION, $cDETIL);	}
					if($can_FOTO)		{ array_push($aTAB, 'TAB_FOTO');	array_push($aICON, 'fa-cog');	array_push($aCAPTION, $cFOTO_CUST);	}
					TAB($aTAB, $aICON, $aCAPTION);
?>
			<div class="tab-content primary">
				<div class="tab-pane fade in active" id="TAB_UMUM">
					<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cKELOMPOK?></label>
					<select name="ADD_GROUP" class="col-sm-4 form-label-900">
					<?php
						$REC_GROUP=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
						echo "<option value=' '  > </option>";
						while($aREC_GR_DATA=SYS_FETCH($REC_GROUP)){
							echo "<option value='$aREC_GR_DATA[KODE_GRP]'  >$aREC_GR_DATA[NAMA_GRP]</option>";
						}
					?>
					</select>
					<div class="clearfix"></div>

					<?php 
						if($nTYPE_CUSTOMER > 0) {
							echo '<label class="col-sm-4 form-label-700" for="field-4">'.$cTIPE_CUST.'</label>';
							echo '<select name="ADD_TIPE" class="col-sm-4 form-label-900">';
								$REC_TIPE=OpenTable('TbCustType', "");
									echo "<option value=' '  > </option>";
									while($aREC_TP_DATA=SYS_FETCH($REC_TIPE)){
										echo "<option value='$aREC_TP_DATA[KODE_TIPE]'  >$aREC_TP_DATA[NAMA_TIPE]</option>";
									}
						}
						echo '</select>';
						echo '<div class="clearfix"></div>';

						if($ada_DIST) {
							LABEL([4,4,4,6], '700', $cCARA_ORDER);
							RADIO('ADD_CARA_ORDER', [1,2], [true, false], [$cTAKING_ORDER, $cCANVAS]);
						}
					?>
					<br>

				</div>
				<div class="tab-pane fade" id="TAB_DETAIL">
					<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNO_REK?></label>
					<select name="ADD_ACCOUNT" class="col-sm-4 form-label-900">
					<?php 
						echo "<option value=' '  > </option>";
						$qACCTN=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_ACCOUNT=SYS_FETCH($qACCTN)){
							echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
					?>
					</select>
					<div class="clearfix"></div>
				</div>
				
				<div class="tab-pane fade" id="TAB_FOTO">
					<label class="form-label" for="field-1">Profile Image</label>
					<span class="desc"></span>
					<img class="img-responsive" src="data/patients/patient-1.jpg" alt="" style="max-width:220px;">
					<div class="controls">
							<input type="file" class="form-control" id="field-5">
					</div>
				</div>
			</div></br>
<?php
				SAVE($cSAVE_DATA);
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		break;

	case md5('UPD_CUST'):
		$can_UPDATE = TRUST($cUSERCODE, 'TB_CUSTOMER_2UPD');
		$q_GROUPLGN=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
		$nGROUP_CUSTOMER = ($q_GROUPLGN ? SYS_ROWS($q_GROUPLGN) : 0);
		
		$q_TIPE_OTL=OpenTable('TbCustType', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$nTYPE_CUSTOMER = ($q_TIPE_OTL ? SYS_ROWS($q_TIPE_OTL) : 0);

		$q_AREA_OTL=OpenTable('TbArea', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$nAREA_CUSTOMER = ($q_AREA_OTL ? SYS_ROWS($q_AREA_OTL) : 0);

		$can_DELETE = TRUST($cUSERCODE, 'TB_CUSTOMER_3DEL');
		$q_LANGGAN=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR='' and md5(CUST_CODE)='$_GET[_c]'");
		if($a_LANGGAN = SYS_FETCH($q_LANGGAN)) {
			$cFILE_FOTO_CUST = 'data/images_member/CUST_'.str_pad((string)$a_LANGGAN['CUST_CODE'], 11, '0', STR_PAD_LEFT).'.jpg';
			if(file_exists($cFILE_FOTO_CUST)==0)	$cFILE_FOTO_MEMBER = "data/images/no.jpg";
		} else header('location:tb_customer.php');
		$cLEGAL_NAME='';
		$qLEGAL=OpenTable('TbCustLegal', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and CUST_CODE='$a_LANGGAN[CUST_CODE]'");
		if($aLEGAL = SYS_FETCH($qLEGAL)) $cLEGAL_NAME=$aLEGAL['CUST_NAME'];
		DEF_WINDOW($cEDIT_TBL);
		$cACTION = [];
		$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_cust').'&_id='. md5($a_LANGGAN['CUST_CODE']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
		TFORM($cEDIT_TBL, "?_a=up_date&id=".$a_LANGGAN['CUST_CODE'], $aACT, $cHELP_FILE);
			LABEL([4,4,4,6], '700', $cKODE_CUST);
			INPUT('text', [2,2,2,3], '900', 'KD_AGGT', $a_LANGGAN['CUST_CODE'], '', '', '', 0, 'disable', 'fix');
			LABEL([4,4,4,6], '700', $cNM_ANG);
			INPUT('text', [6,6,6,6], '900', 'UPD_CUST_NAME', DECODE($a_LANGGAN['CUST_NAME']), '', '', '', 0, '', 'fix');
			LABEL([4,4,4,6], '700', $cAL_ANG);
			INPUT('text', [6,6,6,6], '900', 'EDIT_ALAMAT', DECODE($a_LANGGAN['CUST_ADDRESS']), '', '', '', 0, '', 'fix');
			LABEL([4,4,4,6], '700', $cALAMAT2);
			INPUT('text', [6,6,6,6], '900', 'EDIT_ALAMAT2', DECODE($a_LANGGAN['CUST_CITY']), '', '', '', 0, '', 'fix');
			LABEL([4,4,4,6], '700', $cNO_TELP);
			INPUT('text', [6,6,6,6], '900', 'EDIT_NO_TELPON', DECODE($a_LANGGAN['CUST_PHONE']), '', '', '', 0, '', 'fix');
			LABEL([4,4,4,6], '700', $cNO_HP);
			INPUT('text', [6,6,6,6], '900', 'EDIT_FAX', DECODE($a_LANGGAN['CUST_FAX']), '', '', '', 0, '', 'fix');
			TDIV();
				echo '<h4> </br></h4>';
					$aTAB=[];	$aICON=[];	$aCAPTION=[];
					if($can_GENERAL)	{ array_push($aTAB, 'TAB_UMUM');	array_push($aICON, 'fa-user');	array_push($aCAPTION, $cDATA_UMUM);	}
					if($can_DETAIL)		{ array_push($aTAB, 'TAB_DETAIL');	array_push($aICON, 'fa-home');	array_push($aCAPTION, $cDETIL);	}
					if($can_FOTO)		{ array_push($aTAB, 'TAB_FOTO');	array_push($aICON, 'fa-cog');	array_push($aCAPTION, $cFOTO_CUST);	}
					TAB($aTAB, $aICON, $aCAPTION);

				echo '<div class="tab-content primary">';
						if($can_GENERAL==1)	{
							echo '<div class="tab-pane fade in active" id="TAB_UMUM">';
								if($nGROUP_CUSTOMER > 0) {
									LABEL([3,3,3,6], '700', $cKELOMPOK);
										echo '<select name="UPD_GROUP" class="col-sm-5 form-label-900">';
											$qGROUP=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
											while($aREC_GR_DATA=SYS_FETCH($qGROUP)){
												if($a_LANGGAN['CUST_GROUP']==$aREC_GR_DATA['KODE_GRP']){
													echo "<option value='$aREC_GR_DATA[KODE_GRP]' selected='$a_LANGGAN[CUST_GROUP]' >$aREC_GR_DATA[NAMA_GRP]</option>";
												} else {
													echo "<option value='$aREC_GR_DATA[KODE_GRP]'  >$aREC_GR_DATA[NAMA_GRP]</option>";
												}
											}
										echo '</select>';
								} else	echo '<br><br>';
								echo '<div class="clearfix"></div>';
								
								if($nTYPE_CUSTOMER > 0) {
									echo '<label class="col-sm-3 form-label-700" for="field-5">'.$cTIPE_CUST.'</label>
									<select name="PILIH_TIPE" class="col-sm-5 form-label-900">';
									$qTYPE=OpenTable('TbCustType', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
									while($aREC_TP_DATA=SYS_FETCH($qTYPE)){
										if($a_LANGGAN['CUST_TYPE']==$aREC_TP_DATA['KODE_TIPE']){
											echo "<option value='$aREC_TP_DATA[KODE_TIPE]' selected='$a_LANGGAN[CUST_TYPE]' >$aREC_TP_DATA[NAMA_TIPE]</option>";
										} else {	echo "<option value='$aREC_TP_DATA[KODE_TIPE]'  >$aREC_TP_DATA[NAMA_TIPE]</option>";
										}
									}
									echo '</select>		<div class="clearfix"></div>';
								}
								if($nAREA_CUSTOMER > 0) {
									echo '<label class="col-sm-3 form-label-700" for="field-5">'.$cAREA_CUST.'</label>
									<select name="PILIH_TIPE" class="col-sm-5 form-label-900">';
									$qAREA=OpenTable('TbArea', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
									while($aREC_AREA=SYS_FETCH($qAREA)){
										if($a_LANGGAN['CUST_AREA']==$aREC_AREA['KODE_AREA']){
											echo "<option value='$aREC_AREA[KODE_AREA]' selected='$a_LANGGAN[CUST_AREA]' >$aREC_AREA[NAMA_AREA]</option>";
										} else {	echo "<option value='$aREC_AREA[KODE_AREA]'  >$aREC_AREA[NAMA_AREA]</option>";
										}
									}
									echo '</select>		<div class="clearfix"></div>';
								}
								if($is_OUTSOURCING) {
									LABEL([3,3,3,6], '700', S_MSG('TC41','Nama resmi'));
									INPUT('text', [8,8,8,6], '900', 'EDIT_LEGAL', $cLEGAL_NAME, '', '', '', 0, '', 'fix');
									echo '<br><br><br><br>';
								}
							echo '</div>';
						}

						if($can_DETAIL)	{
							echo '<div class="tab-pane fade" id="TAB_DETAIL">';
								if($ada_DIST) {
									$cCHECK_TAKING_ORDER='';
									$cCHECK_CANVAS='';
									if($a_LANGGAN['CANVAS']=='1') { 
										$cCHECK_TAKING_ORDER='checked';
									}
									if($a_LANGGAN['CANVAS']=='2') { 
										$cCHECK_CANVAS='checked';
									}
									LABEL([3,3,3,6], '700', $cCARA_ORDER);
									echo '<input type="radio" name="PILIH_CARA_ORDER" value="1"' . $cCHECK_TAKING_ORDER.'/>'.$cTAKING_ORDER;
									echo '<ol><input type="radio" name="PILIH_CARA_ORDER" value="2"' . $cCHECK_CANVAS.'/>'.$cCANVAS.'</ol><br><br>';
								}
								CLEAR_FIX();

							LABEL([3,3,3,6], '700', $cNO_REK);
							echo '<select name="EDIT_ACCOUNT" class="form-label-900 m-bot15">';
							echo "<option value=' '  > </option>";
							$qACCTN=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and GENERAL='D' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_ACCOUNT=SYS_FETCH($qACCTN)){
								if($a_LANGGAN['CUST_ACCOUNT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
									echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$a_LANGGAN[CUST_ACCOUNT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
								} else {
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
							}
							echo '</select>';
							echo '</div>';
							echo '<div class="clearfix"></div>';
						}

				
						if($can_FOTO)	{
							echo '<div class="tab-pane fade" id="TAB_FOTO">';
								echo '<label class="form-label" for="field-1">'.$cGBR_CUST.'</label>';
								echo '<span class="desc"></span>';
								echo '<img class="img-responsive" src="'.$cFILE_FOTO_MEMBER.'" alt="" style="max-width:220px;">';
								echo '<div class="controls">';
									echo '<input type="file" class="form-control" id="field-5">';
								echo '</div>';
							echo '</div>';
						}
					echo '</div></br>';
				eTDIV();
				SAVE(($can_UPDATE ? $cSAVE_DATA : ''));
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");	
		END_WINDOW();
		break;

case 'ADD_CUST':
	if($_POST['KODE_TBL']=='') {
		MSG_INFO(S_MSG('F040','Kode Pelanggan tidak boleh kosong'));
		return;
	}
	$cTYPE=($nTYPE_CUSTOMER ? $_POST['ADD_TIPE'] : '');
	$q_LANGGAN=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR='' and md5(CUST_CODE)='$_POST[KODE_TBL]'");
	if(SYS_ROWS($q_LANGGAN)==0){
		$cCANVAS = '';
		if (isset($_POST['ADD_CARA_ORDER']))	$cCANVAS = $_POST['ADD_CARA_ORDER'];
		RecCreate('TbCustomer', ['CUST_CODE', 'CUST_NAME', 'CUST_ADDRESS', 'CUST_CITY', 'CUST_PHONE', 'CUST_FAX', 'CUST_GROUP', 'CUST_TYPE', 'CANVAS', 'CUST_ACCOUNT', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
			[$_POST['KODE_TBL'], ENCODE($_POST['ADD_CUST_NAME']), ENCODE($_POST['ADD_ALAMAT']), ENCODE($_POST['ADD_CITY']), ENCODE($_POST['ADD_NO_TELPON']), ENCODE($_POST['ADD_FAX']), $_POST['ADD_GROUP'], $cTYPE, $cCANVAS, $_POST['ADD_ACCOUNT'], $cAPP_CODE, $_SESSION['gUSERCODE'], date('Y-m-d H:i:s')]);
		header('location:tb_customer.php');
	} else {
		MSG_INFO(S_MSG('F035','Kode Pelanggan sudah ada'));
		return;
	}
	break;

case 'up_date':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cCUST_NAME=ENCODE($_POST['UPD_CUST_NAME']);
	$q_GROUPLGN=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
	$nGROUP_CUSTOMER = SYS_ROWS($q_GROUPLGN);
	$q_TIPE_OTL=OpenTable('TbCustType', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$nTYPE_CUSTOMER = SYS_ROWS($q_TIPE_OTL);
	$cACCOUNT = ($_POST['EDIT_ACCOUNT']>'' ? $_POST['EDIT_ACCOUNT'] : '');
	if($is_OUTSOURCING) {
		$cLEGAL = (isset($_POST['EDIT_LEGAL']) ? $_POST['EDIT_LEGAL'] : '');
		$qLEGAL=OpenTable('TbCustLegal', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and CUST_CODE='$KODE_CRUD'");
		if($aLEGAL = SYS_FETCH($qLEGAL)) {
			RecUpdate('TbCustLegal', ['CUST_NAME'], [$cLEGAL], "APP_CODE='$cAPP_CODE' and  CUST_CODE='$KODE_CRUD'");
		} else {
			RecCreate('TbCustLegal', ['CUST_CODE', 'CUST_NAME', 'APP_CODE', 'ENTRY', 'REC_ID'], 
				[$KODE_CRUD, $cLEGAL, $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		}
	}
	RecUpdate('TbCustomer', ['CUST_NAME', 'CUST_ACCOUNT', 'CUST_ADDRESS', 'CUST_CITY', 'CUST_PHONE', 'CUST_FAX', 'UP_DATE', 'UPD_DATE'], 
		[$cCUST_NAME, $cACCOUNT, $_POST['EDIT_ALAMAT'], $_POST['EDIT_ALAMAT2'], $_POST['EDIT_NO_TELPON'], $_POST['EDIT_FAX'], $cUSERCODE, $NOW], "APP_CODE='$cAPP_CODE' and CUST_CODE='$KODE_CRUD'");
	if($ada_DIST) 
		RecUpdate('TbCustomer', ['CANVAS'], [(isset($_POST['PILIH_CARA_ORDER']) ? $_POST['PILIH_CARA_ORDER'] : '')], "APP_CODE='$cAPP_CODE' and CUST_CODE='$KODE_CRUD'");
	if($nTYPE_CUSTOMER) 
		RecUpdate('TbCustomer', ['CUST_TYPE'], [(isset($_POST['PILIH_TIPE']) ? $_POST['PILIH_TIPE'] : '')], "APP_CODE='$cAPP_CODE' and CUST_CODE='$KODE_CRUD'");
	if($nGROUP_CUSTOMER) 
		RecUpdate('TbCustomer', ['CUST_GROUP'], [(isset($_POST['UPD_GROUP']) ? $_POST['UPD_GROUP'] : '')], "APP_CODE='$cAPP_CODE' and CUST_CODE='$KODE_CRUD'");
	APP_LOG_ADD($cHEADER, 'edit '.$KODE_CRUD);
	header('location:tb_customer.php');
	break;

case md5('del_cust'):
	$KODE_CRUD=$_GET['id'];
	RecUpdate('TbCustomer', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and md5(CUST_CODE)='$KODE_CRUD'");
	APP_LOG_ADD($cHEADER, 'Delete : '.$KODE_CRUD);
	header('location:tb_customer.php');
}
?>

