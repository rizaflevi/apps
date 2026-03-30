<?php
//	tb_font.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHEADER = S_MSG('TJ01','Tabel Font');
	$cHELP_FILE = 'Doc/Tabel - Font.pdf';

	$qTB_FONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and IS_HIDDEN=0");

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

	$cKODE_TBL 	= S_MSG('CL02','Kode');
	$cNAMA_TBL 	= S_MSG('H667','Keterangan');
	$cFONT_NAME	= 'Font name';
	$cADD_REC	= S_MSG('KA11','Add');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cEDIT_TBL	= 'Edit Tabel Font';
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

switch($cACTION){
	default:
    	$can_CREATE = TRUST($cUSERCODE, 'TB_FONT_1ADD');
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TABLE('example');
					THEAD([$cKODE_TBL, $cNAMA_TBL]);
					echo '<tbody>';
						while($aREC_TB_FONT=SYS_FETCH($qTB_FONT)) {
							TDETAIL([DECODE($aREC_TB_FONT['KEY_ID']), DECODE($aREC_TB_FONT['DESCRIPT'])], [], '', 
										["<a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_FONT['REC_ID'])."'>", "<a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_FONT['REC_ID'])."'>"]);
						}
					echo '</tbody>';
				eTABLE();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('cr34t3'):
		DEF_WINDOW('Add font');
			TFORM($cHEADER, '?_a=new_font');
				TDIV();
					LABEL([3,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,3,6], '900', 'ADD_KODE_FONT', '', 'focus', '', '', 0, '', 'Fix');
					LABEL([3,4,4,6], '700', $cNAMA_TBL);
					INPUT('text', [5,5,8,12], '900', 'ADD_KET_FONT', '', '', '', '', 0, '', 'Fix');
					LABEL([3,4,4,6], '700', $cFONT_NAME);
					echo '<select name="ADD_FONT_NAME" class="col-lg-2 col-sm-5 form-label-900">';
						echo "<option value=' '  ></option>";
						$qWFONT=OpenTable('WebFont', "true");
						while($aREC_FONT=SYS_FETCH($qWFONT)){
							echo "<option value='$aREC_FONT[FONT_NAME]'  >$aREC_FONT[FONT_NAME]</option>";
						}
					echo '</select>';
					CLEAR_FIX();
					LABEL([3,4,4,6], '700', 'Size');
					INPUT('number', [1,1,2,3], '900', 'ADD_SIZE', '12');
					LABEL([1,1,1,1], '700', 'Bold', '', 'right');
					INPUT('checkbox', [1,1,1,1], '900', 'ADD_BOLD', false, '', '', '', 0, '', '', '', false);
					LABEL([1,1,1,1], '700', 'Italic', '', 'right');
					INPUT('checkbox', [1,1,1,1], '900', 'ADD_ITAL', false, '', '', '', 0, '', '', '', false);
					LABEL([1,1,1,1], '700', 'Underline', '', 'right');
					INPUT('checkbox', [1,1,1,1], '900', 'ADD_UNDR', false, '', '', '', 0, '', '', '', false);
					LABEL([1,1,1,1], '700', 'Strikeout', '', 'right');
					INPUT('checkbox', [1,1,1,1], '900', 'ADD_STRK', false, '', '', '', 0, '', 'fix', '', false);
					LABEL([3,3,3,6], '700', 'Color');
					INPUT('color', [1,1,1,1], '900', 'ADD_COLOR', '#000000', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', 'Back Color');
					INPUT('color', [1,1,1,1], '900', 'ADD_BCOLOR', '#ffffff', '', '', '', 0, '', 'fix');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('up_d4t3'):
		$can_UPDATE = TRUST($cUSERCODE, 'TB_FONT_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'TB_FONT_3DEL');
		$qTB_FONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and md5(REC_ID)='$_GET[_r]' ");
		$aREC_TB_FONT=SYS_FETCH($qTB_FONT);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_font').'&_r='. md5($aREC_TB_FONT['REC_ID']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=upd_font&id='.$aREC_TB_FONT['KEY_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [3,3,3,6], '900', 'EDIT_KODE_FONT', $aREC_TB_FONT['KEY_ID'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,4,4,6], '700', $cNAMA_TBL);
					INPUT('text', [4,4,4,6], '900', 'EDIT_KET_FONT', DECODE($aREC_TB_FONT['DESCRIPT']), 'autofocus', '', '', 0, '', 'fix');
					LABEL([3,4,4,6], '700', $cFONT_NAME);
					echo '<select name="UPD_FONT_NAME" class="col-lg-3 col-sm-5 form-label-900">';
						echo "<option value=' '  ></option>";
						$qWFONT=OpenTable('WebFont', "true");
						while($aREC_FONT=SYS_FETCH($qWFONT)){
							if($aREC_FONT['FONT_NAME']==$aREC_TB_FONT['NAME']){
								echo "<option value='$aREC_TB_FONT[NAME]' selected='$aREC_TB_FONT[NAME]' >$aREC_FONT[FONT_NAME]</option>";
							} else
							echo "<option value='$aREC_FONT[FONT_NAME]'  >$aREC_FONT[FONT_NAME]</option>";
						}
					echo '</select>';
					CLEAR_FIX();
					LABEL([3,4,4,6], '700', 'Size');
					INPUT('number', [1,1,1,3], '900', 'UPD_SIZE', $aREC_TB_FONT['SIZE']);

					LABEL([1,1,1,1], '700', 'Bold', '', 'right');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_BOLD', $aREC_TB_FONT['BOLD']==1, '', '', '', 0, '', '', '', $aREC_TB_FONT['BOLD']==1);
					LABEL([1,1,1,1], '700', 'Italic', '', 'right');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_ITAL', $aREC_TB_FONT['ITALIC']==1, '', '', '', 0, '', '', '', $aREC_TB_FONT['ITALIC']==1);
					LABEL([1,1,1,1], '700', 'Underline', '', 'right');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_UNDR', $aREC_TB_FONT['UNDERLINE']==1, '', '', '', 0, '', '', '', $aREC_TB_FONT['UNDERLINE']==1);
					LABEL([1,1,1,1], '700', 'Strikeout', '', 'right');
					INPUT('checkbox', [1,1,1,1], '900', 'UPD_STRK', $aREC_TB_FONT['STRIKEOUT']==1, '', '', '', 0, '', 'fix', '', $aREC_TB_FONT['STRIKEOUT']==1);
					LABEL([3,3,3,6], '700', 'Color');
					INPUT('color', [1,1,1,1], '900', 'UPD_COLOR', $aREC_TB_FONT['HEX_COLOR'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', 'Back Color');
					INPUT('color', [1,1,1,1], '900', 'UPD_BCOLOR', $aREC_TB_FONT['HEX_BACK_CLR'], '', '', '', 0, '', 'fix');
				SAVE(($can_UPDATE ? $cSAVE : ''));
				TDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case 'new_font':
		$cKODE_FONT	= ENCODE($_POST['ADD_KODE_FONT']);
		if($cKODE_FONT==''){
			MSG_INFO('Kode font belum diisi');
			return;
		}
		$cFNAME	= $_POST['ADD_FONT_NAME'];
		if($cFNAME=='')	$cFNAME='Arial';
		$qTB_FONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and KEY_ID='$cKODE_FONT' ");
		if(SYS_ROWS($qTB_FONT)>0){
			MSG_INFO('Kode font sudah ada');
			return;
		}
		$cCONTENT	= ENCODE($_POST['ADD_KET_FONT']);
		$lBOLD = 0;	if (isset($_POST['ADD_BOLD']))		$lBOLD = 1;
		$lITAL = 0;	if (isset($_POST['ADD_ITAL']))		$lITAL = 1;
		$lUNDR = 0;	if (isset($_POST['ADD_UNDR']))		$lUNDR = 1;
		$lSTRK = 0;	if (isset($_POST['ADD_STRK']))		$lSTRK = 1;
		RecCreate('TbFont', ['KEY_ID', 'DESCRIPT', 'NAME', 'SIZE', 'BOLD', 'ITALIC', 'UNDERLINE', 'STRIKEOUT', 'HEX_COLOR', 'HEX_BACK_CLR', 'ENTRY', 'REC_ID', 'APP_CODE'], 
			[$cKODE_FONT, $cCONTENT, $cFNAME, $_POST['ADD_SIZE'], $lBOLD, $lITAL, $lUNDR, 
			$lSTRK, $_POST['ADD_COLOR'], $_POST['ADD_BCOLOR'], $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		header('location:tb_font.php');
		break;

	case 'upd_font':
		$KODE_CRUD=$_GET['id'];
		$cCONTENT	= ENCODE($_POST['EDIT_KET_FONT']);
		$lBOLD = 0;	if (isset($_POST['UPD_BOLD']))		$lBOLD = 1;
		$lITAL = 0;	if (isset($_POST['UPD_ITAL']))		$lITAL = 1;
		$lUNDR = 0;	if (isset($_POST['UPD_UNDR']))		$lUNDR = 1;
		$lSTRK = 0;	if (isset($_POST['UPD_STRK']))		$lSTRK = 1;
		RecUpdate('TbFont', ['DESCRIPT', 'NAME', 'SIZE', 'BOLD', 'ITALIC', 'UNDERLINE', 'STRIKEOUT', 'HEX_COLOR', 'HEX_BACK_CLR'], 
			[$cCONTENT, $_POST['UPD_FONT_NAME'], $_POST['UPD_SIZE'], $lBOLD, $lITAL, $lUNDR, $lSTRK, $_POST['UPD_COLOR'], $_POST['UPD_BCOLOR']], "APP_CODE='$cAPP_CODE' and KEY_ID='$KODE_CRUD'");
		header('location:tb_font.php');
		break;

	case md5('del_font'):
		$cREC_ID=$_GET['_r'];
		$qCEK_FONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and REC_ID='$cREC_ID' ");
		if(SYS_ROWS($qCEK_FONT)==0)	return;
		$aR_FONT = SYS_FETCH($qCEK_FONT);
		$qCEK_BILL=OpenTable('TbBillPrn', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and SET_VALUE='$aR_FONT[SET_VALUE]'");
		if(SYS_ROWS($qCEK_BILL)>0){
			MSG_INFO('Kode font dipergunakan pada tabel format cetakan, belum dapat di hapus !');
			return;
		}
		$qCEK_BILL=OpenTable('TbCalk', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and FONT='$aR_FONT[SET_VALUE]'");
		if(SYS_ROWS($qCEK_BILL)>0){
			MSG_INFO('Kode font dipergunakan pada tabel CALK, belum dapat di hapus !');
			return;
		}
		RecSoftDel($cREC_ID);
		header('location:tb_font.php');
}
?>

