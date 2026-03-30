<?php
//	tb_calk.php //
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHEADER = S_MSG('TC90','Tabel CALK');
	$cHELP_FILE = 'Doc/Tabel - Jabatan.pdf';
	$can_CREATE = TRUST($cUSERCODE, 'TB_CALK_1ADD');

	$qTB_CALK=OpenTable('TbCalk', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

	$cKODE_TBL 	= S_MSG('CL02','Kode');
	$cNAMA_TBL 	= S_MSG('CL03','Keterangan');
	$cTYPE 		= S_MSG('H543','Tipe');
	$cACCOUNT	= S_MSG('F030','Kode Account');
	$cADD_REC	= S_MSG('TC93','Tambah CALK');
	$cDAFTAR	= S_MSG('TC91','Daftar Tabel CALK');
	$cSAVE		= S_MSG('F301','Save');
	$aTYPE		= ['text', 'textarea'];
	$aDIM		= ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
	$aBORDER	= ['0', '1', '2'];
	$aALIGN		= ['left', 'center', 'right'];

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL]);
						echo '<tbody>';
						while($aREC_TB_CALK=SYS_FETCH($qTB_CALK)) {
							echo '<tr>';
								echo '<td style="width: 1px;"></td>';
								echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_CALK['REC_ID'])."'>".decode_string($aREC_TB_CALK['CODE'])."</a></span></td>";
								echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_CALK['REC_ID'])."'>".decode_string($aREC_TB_CALK['CON10'])."</a></span></td>";
							echo '</tr>';
						}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('cr34t3'):
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=new_calk', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,3], '900', 'ADD_KODE_CALK', '', 'autofocus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [5,5,8,12], '900', 'ADD_NAMA_CALK', '', '', '', '', 0, '', 'fix');
					LABEL([3,2,3,6], '700', $cTYPE);
					SELECT([2,4,5,6], 'ADD_TYPE_CALK');
						echo "<option value=' '  ></option>";
						foreach ($aTYPE as $mTYPE) {
							echo '<option value="'.$mTYPE.'"  >'. $mTYPE. '</option>"';
						}
					echo '</select>';
					CLEAR_FIX();

					LABEL([3,4,4,6], '700', 'LG');
					SELECT([1,1,1,6], 'ADD_LG');
						echo "<option value=' '  ></option>";
						foreach ($aDIM as $mDIM) {
							echo '<option value="'.$mDIM.'"  >'. $mDIM. '</option>"';
						}
					echo '</select>';

					LABEL([1,1,1,1], '700', 'MD', '', 'right');
					SELECT([1,1,1,6], 'ADD_MD');
						echo "<option value=' '  ></option>";
						foreach ($aDIM as $mDIM) {
							echo '<option value="'.$mDIM.'"  >'. $mDIM. '</option>"';
						}
					echo '</select>';

					LABEL([1,1,1,1], '700', 'SM', '', 'right');
					SELECT([1,1,1,6], 'ADD_SM');
						echo "<option value=' '  ></option>";
						foreach ($aDIM as $mDIM) {
							echo '<option value="'.$mDIM.'"  >'. $mDIM. '</option>"';
						}
					echo '</select>';

					LABEL([1,1,1,1], '700', 'XS', '', 'right');
					SELECT([1,1,1,6], 'ADD_XS');
						echo "<option value=' '  ></option>";
						foreach ($aDIM as $mDIM) {
							echo '<option value="'.$mDIM.'"  >'. $mDIM. '</option>"';
						}
					echo '</select>';
					CLEAR_FIX();

					LABEL([3,4,4,6], '700', 'Border');
					SELECT([1,1,1,6], 'ADD_BORDER');
						echo "<option value=' '  ></option>";
						foreach ($aBORDER as $mBORDER) {
							echo '<option value="'.$mBORDER.'"  >'. $mBORDER. '</option>"';
						}
					echo '</select>';
					CLEAR_FIX();

					LABEL([3,4,4,6], '700', 'Align');
					SELECT([2,2,2,6], 'ADD_ALIGN');
						echo "<option value=' '  ></option>";
						foreach ($aALIGN as $mALIGN) {
							echo '<option value="'.$mALIGN.'"  >'. $mALIGN. '</option>"';
						}
					echo '</select>';
					CLEAR_FIX();

					LABEL([3,4,4,6], '700', 'Font');
					SELECT([5,5,5,6], 'ADD_FONT_CODE');
						echo "<option value=' '  ></option>";
						$qFONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_FONT=SYS_FETCH($qFONT)){
							echo "<option value='$aREC_FONT[KEY_ID]'  >$aREC_FONT[DESCRIPT]</option>";
						}
					echo '</select>';
					CLEAR_FIX();

					LABEL([3,4,4,6], '700', 'Cell');
					INPUT('text', [1,1,1,6], '900', 'ADD_CELL_CALK', '', '', '', '', 0, '', 'fix');

					LABEL([3,4,4,6], '700', $cACCOUNT);
					SELECT([5,5,5,6], 'ADD_ACCOUNT');
						echo "<option value=' '  > </option>";
						$qACCTN=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_ACCOUNT=SYS_FETCH($qACCTN)){
							echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
					echo '</select>';
					CLEAR_FIX();
					SAVE($cSAVE);
				TDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('up_d4t3'):
		$can_UPDATE = TRUST($cUSERCODE, 'TB_CALK_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'TB_CALK_3DEL');
		$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$qTB_CALK=OpenTable('TbCalk', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and md5(REC_ID)='$_GET[_r]' ");
		$aREC_TB_CALK=SYS_FETCH($qTB_CALK);
		$cEDIT_TBL	= S_MSG('TC92','Edit Tabel CALK');
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_calk').'&_r='. $aREC_TB_CALK['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&id='.$aREC_TB_CALK['CODE'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_KODE_CALK', $aREC_TB_CALK['CODE'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [8,8,8,6], '900', 'EDIT_KODE_CALK', DECODE($aREC_TB_CALK['CON10']), 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cTYPE);
					SELECT([2,2,3,6], 'UPD_TYPE_CALK');
						echo "<option value=' '  ></option>";
						foreach ($aTYPE as $mTYPE) {
							if($mTYPE==$aREC_TB_CALK['TY_PE']){
								echo "<option value='$mTYPE' selected='$aREC_TB_CALK[TY_PE]' >$aREC_TB_CALK[TY_PE]</option>";
							} else
								echo '<option value="'.$mTYPE.'"  >'. $mTYPE. '</option>"';
						}
					echo '</select>';
					CLEAR_FIX();
					LABEL([3,3,3,6], '700', 'LG');
					SELECT([1,1,1,6], 'UPD_LG');
						echo "<option value=' '  ></option>";
						foreach ($aDIM as $mDIM) {
							if($mDIM==$aREC_TB_CALK['LG']){
								echo "<option value='$mDIM' selected='$aREC_TB_CALK[LG]' >$aREC_TB_CALK[LG]</option>";
							} else
								echo '<option value="'.$mDIM.'"  >'. $mDIM. '</option>"';
						}
					echo '</select>';
					LABEL([1,1,1,1], '700', 'MD', '', 'right');
					SELECT([1,1,1,6], 'UPD_MD');
						echo "<option value=' '  ></option>";
						foreach ($aDIM as $mDIM) {
							if($mDIM==$aREC_TB_CALK['MD']){
								echo "<option value='$mDIM' selected='$aREC_TB_CALK[MD]' >$aREC_TB_CALK[MD]</option>";
							} else
								echo '<option value="'.$mDIM.'"  >'. $mDIM. '</option>"';
						}
					echo '</select>';
					LABEL([1,1,1,1], '700', 'SM', '', 'right');
					SELECT([1,1,1,6], 'UPD_SM');
						echo "<option value=' '  ></option>";
						foreach ($aDIM as $mDIM) {
							if($mDIM==$aREC_TB_CALK['SM']){
								echo "<option value='$mDIM' selected='$aREC_TB_CALK[SM]' >$aREC_TB_CALK[SM]</option>";
							} else
								echo '<option value="'.$mDIM.'"  >'. $mDIM. '</option>"';
						}
					echo '</select>';
					LABEL([1,1,1,1], '700', 'XS', '', 'right');
					SELECT([1,1,1,6], 'UPD_XS');
						echo "<option value=' '  ></option>";
						foreach ($aDIM as $mDIM) {
							if($mDIM==$aREC_TB_CALK['XS']){
								echo "<option value='$mDIM' selected='$aREC_TB_CALK[XS]' >$aREC_TB_CALK[XS]</option>";
							} else
								echo '<option value="'.$mDIM.'"  >'. $mDIM. '</option>"';
						}
					echo '</select>';
					CLEAR_FIX();
					LABEL([3,3,6,6], '700', 'Border');
					SELECT([1,1,1,6], 'UPD_BORDER');
						echo "<option value=' '  ></option>";
						foreach ($aBORDER as $mBORDER) {
							if($mBORDER==$aREC_TB_CALK['BORDER']){
								echo "<option value='$mBORDER' selected='$aREC_TB_CALK[BORDER]' >$aREC_TB_CALK[BORDER]</option>";
							} else
								echo '<option value="'.$mBORDER.'"  >'. $mBORDER. '</option>"';
						}
					echo '</select>';
					CLEAR_FIX();
					LABEL([3,3,6,6], '700', 'Align');
					SELECT([2,2,2,6], 'UPD_ALIGN');
						echo "<option value=' '  ></option>";
						foreach ($aALIGN as $mALIGN) {
							if($mALIGN==$aREC_TB_CALK['ALIGN']){
								echo "<option value='$mALIGN' selected='$aREC_TB_CALK[ALIGN]' >$aREC_TB_CALK[ALIGN]</option>";
							} else
								echo '<option value="'.$mALIGN.'"  >'. $mALIGN. '</option>"';
						}
					echo '</select>';
					CLEAR_FIX();
					LABEL([3,3,6,6], '700', 'Font');
					SELECT([5,5,5,6], 'UPD_FONT_CODE');
						echo "<option value=' '  ></option>";
						$qFONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_FONT=SYS_FETCH($qFONT)){
							if($aREC_FONT['KEY_ID']==$aREC_TB_CALK['FONT']){
								echo "<option value='$aREC_FONT[KEY_ID]' selected='$aREC_TB_CALK[FONT]' >$aREC_FONT[DESCRIPT]</option>";
							} else
								echo "<option value='$aREC_FONT[KEY_ID]'  >$aREC_FONT[DESCRIPT]</option>";
						}
					echo '</select>';
					CLEAR_FIX();
					LABEL([3,3,6,6], '700', 'Cell');
					INPUT('text', [1,1,1,3], '900', 'EDIT_CELL_CALK', DECODE($aREC_TB_CALK['CELL']), '', '', '', 0, '', 'fix');
					SAVE($cSAVE);
				TDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case 'new_calk':
	$cKODE_CALK	= encode_string($_POST['ADD_KODE_CALK']);
	if($cKODE_CALK==''){
		MSG_INFO(S_MSG('TC94','Kode CALK belum diisi'));
		return;
	}
	$cTYPE_CALK	= $_POST['ADD_TYPE_CALK'];
	if($cTYPE_CALK=='')	$cTYPE_CALK='text';
	$qTB_CALK=OpenTable('TbCalk', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and CODE='$cKODE_CALK' ");
	if(SYS_ROWS($qTB_CALK)>0){
		MSG_INFO(S_MSG('TC95','Kode CALK sudah ada'));
		return;
	}
	$cCONTENT	= encode_string($_POST['ADD_NAMA_CALK']);
	$cFONT_CD	= $_POST['ADD_FONT_CODE'];
	$cACCOUNT	= $_POST['ADD_ACCOUNT'];
	RecCreate('TbCalk', ['CODE', 'CON10', 'TY_PE', 'LG', 'MD', 'SM', 'XS', 'BORDER', 'ALIGN', 'CELL', 'FONT', 'ACCOUNT', 'ENTRY', 'REC_ID', 'APP_CODE'], 
		[$cKODE_CALK, $cCONTENT, $cTYPE_CALK, $_POST['ADD_LG'], $_POST['ADD_MD'], $_POST['ADD_SM'], $_POST['ADD_XS'], 
		$_POST['ADD_BORDER'], $_POST['ADD_ALIGN'], $_POST['ADD_CELL_CALK'], $cFONT_CD, $cACCOUNT, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
	SYS_QUERY($cQUERY);
	header('location:tb_calk.php');
	break;

case 'rubah':
	$KODE_CRUD=$_GET['id'];
	$cCONTENT	= encode_string($_POST['EDIT_NAMA_CALK']);
	RecUpdate('TbCalk', ['CON10', 'TY_PE', 'LG', 'MD', 'SM', 'XS', 'BORDER', 'ALIGN', 'FONT', 'CELL'], 
		[$cCONTENT, $_POST['UPD_TYPE_CALK'], $_POST['UPD_LG'], $_POST['UPD_MD'], $_POST['UPD_SM'], $_POST['UPD_XS'], $_POST['UPD_BORDER'], $_POST['UPD_ALIGN'], $_POST['UPD_FONT_CODE'], $_POST['EDIT_CELL_CALK']], "APP_CODE='$cAPP_CODE' and CODE='$KODE_CRUD'");
	header('location:tb_calk.php');
	break;
	
case md5('del_calk'):
	$KODE_CRUD=$_GET['_id'];
	RecSoftDel($KODE_CRUD);
	header('location:tb_calk.php');
}
?>

