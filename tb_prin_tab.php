<?php
//	tb_prin_tab.php
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE']; 
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cKODE_BILL = strtoupper($_SESSION['KD_BILL']);

	$cNUMBER=S_MSG('PS27','No. Nota');

	$qQUERY=OpenTable('TbBillPrn', "APP_CODE='$cAPP_CODE' and BILL_CODE='$cKODE_BILL' and SET_NAME='DETAIL_START_ROW' and REC_ID not in ( select DEL_ID from logs_delete)");
	$REC_DT_BILL=SYS_FETCH($qQUERY);
	TDIV();
		TAB(['HEADER', 'DETAIL', 'GARIS', 'KONSTAN'], ['fa-cog', 'fa-cog', 'fa-cog', 'fa-cog'], 
			[S_MSG('PS62','Header'), S_MSG('PS64','Detail, Total && Footnote'), S_MSG('PS67','Garis & kotak'), S_MSG('PS68','Fixed Text')]);
		echo '<div class="tab-content primary">';
			echo '<div class="tab-pane fade in active" id="HEADER">';
				LABEL([5,5,5,6], '700', '');
				LABEL([1,1,1,6], '700', $cCETAK);
				LABEL([1,1,1,6], '700', $cKOLOM);
				LABEL([1,1,1,6], '700', $cBARIS);
				LABEL([1,1,1,6], '700', S_MSG('PS13','Tengah'));
				LABEL([1,1,1,6], '700', S_MSG('PS14','Lebar'));
				LABEL([1,1,1,6], '700', S_MSG('PS15','Tinggi'));
				CLEAR_FIX();
				LABEL([5,5,5,6], '700', S_MSG('PS16','Logo Perusahaan'));
				INPUT('checkbox', [1,1,1,1], '900', 'LOGO_CETAK', GET_FORMAT($cKODE_BILL, 'LOGO_CETAK')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'LOGO_CETAK')=='1');
				INPUT('text', [1,1,1,3], '900', 'LOGO_LEFT', GET_FORMAT($cKODE_BILL, 'LOGO_LEFT'), '', '', '', 0, '', '');
				INPUT('text', [1,1,1,3], '900', 'LOGO_TOP', GET_FORMAT($cKODE_BILL, 'LOGO_TOP'), '', '', '', 0, '', '');
				INPUT('checkbox', [1,1,1,1], '900', 'LOGO_CENTER', GET_FORMAT($cKODE_BILL, 'LOGO_CENTR')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'LOGO_CENTR')=='1');
				INPUT('number', [1,1,1,3], '900', 'LOGO_WIDTH', GET_FORMAT($cKODE_BILL, 'LOGO_WIDTH'));
				INPUT('number', [1,1,1,3], '900', 'LOGO_HEIGT', GET_FORMAT($cKODE_BILL, 'LOGO_HEIGT'));
				CLEAR_FIX();
				LABEL([5,5,5,6], '700', S_MSG('SL17','Nama Perusahaan'));
				INPUT('checkbox', [1,1,1,1], '900', 'COMP_CETAK', GET_FORMAT($cKODE_BILL, 'COMP_CETAK')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'COMP_CETAK')=='1');
				INPUT('number', [1,1,1,3], '900', 'COMP_LEFT', GET_FORMAT($cKODE_BILL, 'COMP_LEFT'));
				INPUT('number', [1,1,1,3], '900', 'COMP_TOP', GET_FORMAT($cKODE_BILL, 'COMP_TOP'));
				INPUT('checkbox', [1,1,1,1], '900', 'COMP_CENTR', GET_FORMAT($cKODE_BILL, 'COMP_CENTR')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'COMP_CENTR')=='1');
				SELECT([3,3,3,6], 'COMP_FONT_CODE');
					echo "<option value=''  ></option>";
					$qFONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					$cBILL_FONT = GET_FORMAT($cKODE_BILL, 'COMP_FONT_CODE');
					while($aREC_FONT=SYS_FETCH($qFONT)){
						if($aREC_FONT['KEY_ID']==$cBILL_FONT){
							echo "<option value='$aREC_FONT[KEY_ID]' selected='$aREC_FONT[KEY_ID]' >$aREC_FONT[DESCRIPT]</option>";
						} else
							echo "<option value='$aREC_FONT[KEY_ID]'  >$aREC_FONT[DESCRIPT]</option>";
					}
				echo '</select>';
				CLEAR_FIX();
				LABEL([5,5,4,6], '700', S_MSG('SL22','Alamat'));
				INPUT('checkbox', [1,1,1,1], '900', 'ADD1_CETAK', GET_FORMAT($cKODE_BILL, 'ADD1_CETAK')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'ADD1_CETAK')=='1');
				INPUT('number', [1,1,1,3], '900', 'ADD1_LEFT', GET_FORMAT($cKODE_BILL, 'ADD1_LEFT'));
				INPUT('number', [1,1,1,3], '900', 'ADD1_TOP', GET_FORMAT($cKODE_BILL, 'ADD1_TOP'));
				INPUT('checkbox', [1,1,1,1], '900', 'ADD1_CENTR', GET_FORMAT($cKODE_BILL, 'ADD1_CENTR')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'ADD1_CENTR')=='1');

				SELECT([3,3,3,6], 'ADD1_FONT_CODE');
					echo "<option value=''  ></option>";
					$qFONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					$cBILL_FONT = GET_FORMAT($cKODE_BILL, 'ADD1_FONT_CODE');
					while($aREC_FONT=SYS_FETCH($qFONT)){
						if($aREC_FONT['KEY_ID']==$cBILL_FONT){
							echo "<option value='$aREC_FONT[KEY_ID]' selected='$aREC_FONT[KEY_ID]' >$aREC_FONT[DESCRIPT]</option>";
						} else
							echo "<option value='$aREC_FONT[KEY_ID]'  >$aREC_FONT[DESCRIPT]</option>";
					}
				echo '</select>';
				CLEAR_FIX();
				LABEL([5,5,4,6], '700', S_MSG('SL22','Alamat'));
				INPUT('checkbox', [1,1,1,1], '900', 'ADD2_CETAK', GET_FORMAT($cKODE_BILL, 'ADD2_CETAK')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'ADD2_CETAK')=='1');
				INPUT('number', [1,1,1,3], '900', 'ADD2_LEFT', GET_FORMAT($cKODE_BILL, 'ADD2_LEFT'));
				INPUT('number', [1,1,1,3], '900', 'ADD2_TOP', GET_FORMAT($cKODE_BILL, 'ADD2_TOP'));
				INPUT('checkbox', [1,1,1,1], '900', 'ADD2_CENTR', GET_FORMAT($cKODE_BILL, 'ADD2_CENTR')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'ADD2_CENTR')=='1');
				SELECT([3,3,3,6], 'ADD2_FONT_CODE');
					echo "<option value=''  ></option>";
					$qFONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					$cBILL_FONT = GET_FORMAT($cKODE_BILL, 'ADD2_FONT_CODE');
					while($aREC_FONT=SYS_FETCH($qFONT)){
						if($aREC_FONT['KEY_ID']==$cBILL_FONT){
							echo "<option value='$aREC_FONT[KEY_ID]' selected='$aREC_FONT[KEY_ID]' >$aREC_FONT[DESCRIPT]</option>";
						} else
							echo "<option value='$aREC_FONT[KEY_ID]'  >$aREC_FONT[DESCRIPT]</option>";
					}
				echo '</select>';
				CLEAR_FIX();

				LABEL([5,5,4,6], '700', $cNUMBER);
				INPUT('checkbox', [1,1,1,1], '900', 'N_NOTA_CTK', GET_FORMAT($cKODE_BILL, 'N_NOTA_CTK')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'N_NOTA_CTK')=='1');
				INPUT('number', [1,1,1,3], '900', 'NOTA_LEFT', GET_FORMAT($cKODE_BILL, 'NOTA_LEFT'));
				INPUT('number', [1,1,1,3], '900', 'NOTA_TOP', GET_FORMAT($cKODE_BILL, 'NOTA_TOP'));
				INPUT('checkbox', [1,1,1,1], '900', 'NOTA_CENTR', GET_FORMAT($cKODE_BILL, 'NOTA_CENTR')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'NOTA_CENTR')=='1');

				SELECT([3,3,3,6], 'NOTA_FONT_CODE');
					echo "<option value=''  ></option>";
					$qFONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					$cBILL_FONT = GET_FORMAT($cKODE_BILL, 'NOTA_FONT_CODE');
					while($aREC_FONT=SYS_FETCH($qFONT)){
						if($aREC_FONT['KEY_ID']==$cBILL_FONT){
							echo "<option value='$aREC_FONT[KEY_ID]' selected='$aREC_FONT[KEY_ID]' >$aREC_FONT[DESCRIPT]</option>";
						} else
							echo "<option value='$aREC_FONT[KEY_ID]'  >$aREC_FONT[DESCRIPT]</option>";
					}
				echo '</select>';
				CLEAR_FIX();

				LABEL([5,5,4,6], '700', S_MSG('PS28','Tanggal'));
				INPUT('checkbox', [1,1,1,1], '900', 'TGGL_CTK', GET_FORMAT($cKODE_BILL, 'TGGL_CTK')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'TGGL_CTK')=='1');
				INPUT('number', [1,1,1,3], '900', 'TGGL_LEFT', GET_FORMAT($cKODE_BILL, 'TGGL_LEFT'));
				INPUT('number', [1,1,1,3], '900', 'TGGL_TOP', GET_FORMAT($cKODE_BILL, 'TGGL_TOP'));
				INPUT('checkbox', [1,1,1,1], '900', 'TGGL_CENTR', GET_FORMAT($cKODE_BILL, 'TGGL_CENTR')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'TGGL_CENTR')=='1');
				SELECT([3,3,3,6], 'TGGL_FONT_CODE');
					echo "<option value=''  ></option>";
					$qFONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					$cBILL_FONT = GET_FORMAT($cKODE_BILL, 'TGGL_FONT_CODE');
					while($aREC_FONT=SYS_FETCH($qFONT)){
						if($aREC_FONT['KEY_ID']==$cBILL_FONT){
							echo "<option value='$aREC_FONT[KEY_ID]' selected='$aREC_FONT[KEY_ID]' >$aREC_FONT[DESCRIPT]</option>";
						} else
							echo "<option value='$aREC_FONT[KEY_ID]'  >$aREC_FONT[DESCRIPT]</option>";
					}
				echo '</select>';
				CLEAR_FIX();

				LABEL([5,5,4,6], '700', S_MSG('PS29','Jam'));
				INPUT('checkbox', [1,1,1,1], '900', 'JAM_CTK', GET_FORMAT($cKODE_BILL, 'JAM_CTK')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'JAM_CTK')=='1');
				INPUT('number', [1,1,1,3], '900', 'JAM_LEFT', GET_FORMAT($cKODE_BILL, 'JAM_LEFT'));
				INPUT('number', [1,1,1,3], '900', 'JAM_TOP', GET_FORMAT($cKODE_BILL, 'JAM_TOP'));
				INPUT('checkbox', [1,1,1,1], '900', 'JAM_CENTR', GET_FORMAT($cKODE_BILL, 'JAM_CENTR')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'JAM_CENTR')=='1');
				SELECT([3,3,3,6], 'JAM_FONT_CODE');
					echo "<option value='' ></option>";
					$qFONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					$cBILL_FONT = GET_FORMAT($cKODE_BILL, 'JAM_FONT_CODE');
					while($aREC_FONT=SYS_FETCH($qFONT)){
						if($aREC_FONT['KEY_ID']==$cBILL_FONT){
							echo "<option value='$aREC_FONT[KEY_ID]' selected='$aREC_FONT[KEY_ID]' >$aREC_FONT[DESCRIPT]</option>";
						} else
							echo "<option value='$aREC_FONT[KEY_ID]'  >$aREC_FONT[DESCRIPT]</option>";
					}
				echo '</select>';
				CLEAR_FIX();

				LABEL([5,5,4,6], '700', S_MSG('PS83','Keterangan'));
				INPUT('checkbox', [1,1,1,1], '900', 'KET_CTK', GET_FORMAT($cKODE_BILL, 'KET_CTK')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'KET_CTK')=='1');
				INPUT('number', [1,1,1,3], '900', 'KET_LEFT', GET_FORMAT($cKODE_BILL, 'KET_LEFT'));
				INPUT('number', [1,1,1,3], '900', 'KET_TOP', GET_FORMAT($cKODE_BILL, 'KET_TOP'));
				INPUT('checkbox', [1,1,1,1], '900', 'KET_CENTR', GET_FORMAT($cKODE_BILL, 'KET_CENTR')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'KET_CENTR')=='1');
				SELECT([3,3,3,6], 'KET_FONT_CODE');
					echo "<option value='' ></option>";
					$qFONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					$cBILL_FONT = GET_FORMAT($cKODE_BILL, 'KET_FONT_CODE');
					while($aREC_FONT=SYS_FETCH($qFONT)){
						if($aREC_FONT['KEY_ID']==$cBILL_FONT){
							echo "<option value='$aREC_FONT[KEY_ID]' selected='$aREC_FONT[KEY_ID]' >$aREC_FONT[DESCRIPT]</option>";
						} else
							echo "<option value='$aREC_FONT[KEY_ID]'  >$aREC_FONT[DESCRIPT]</option>";
					}
				echo '</select>';
				CLEAR_FIX();
			eTDIV();
			echo '<div class="tab-pane fade in" id="DETAIL">';
				LABEL([3,3,3,6], '700', S_MSG('PS19','Mulai Baris'));
				INPUT('number', [1,1,2,3], '900', 'DETAIL_START_ROW', GET_FORMAT($cKODE_BILL, 'DETAIL_START_ROW'));
				LABEL([2,2,3,3], '700', 'Font', '', 'right');
				SELECT([3,3,3,6], 'DETAIL_DTA_FONT_CODE');
					echo "<option value=''  ></option>";
					$qFONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					$cBILL_FONT = GET_FORMAT($cKODE_BILL, 'DETAIL_DTA_FONT_CODE');
					while($aREC_FONT=SYS_FETCH($qFONT)){
						if($aREC_FONT['KEY_ID']==$cBILL_FONT){
							echo "<option value='$aREC_FONT[KEY_ID]' selected='$aREC_FONT[KEY_ID]' >$aREC_FONT[DESCRIPT]</option>";
						} else
							echo "<option value='$aREC_FONT[KEY_ID]'  >$aREC_FONT[DESCRIPT]</option>";
					}
				echo '</select>';
				CLEAR_FIX();
				LABEL([3,3,3,3], '700', '');
				LABEL([2,1,1,1], '700', 'Header');
				LABEL([1,1,1,1], '700', $cCETAK);
				LABEL([1,1,1,1], '700', $cKOLOM);
				LABEL([2,1,1,1], '700', 'Cetak Total');
				LABEL([2,1,1,1], '700', 'Caption Total', 'fix');

				$qQUERY=OpenTable('TbBillCol', "BILL_CODE='$cKODE_BILL' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
				$I=0;
				while($aDTL_COL=SYS_FETCH($qQUERY)) {
					$I++;
					$J=(string)$I;
					LABEL([3,3,3,3], '700', $aDTL_COL['COL_NAME']);
					$cDATA = 'DETAIL'.$J.'_HEAD_LABEL';
					$cSTTS = 'DETAIL'.$J.'_HEAD_STATUS';
					INPUT('number', [2,2,2,2], '900', 'DETAIL'.$J.'_HEAD_LABEL', GET_FORMAT($cKODE_BILL, 'DETAIL'.$J.'_HEAD_LABEL'));
					INPUT('checkbox', [1,1,1,1], '900', 'DETAIL'.$J.'_HEAD_STATUS', GET_FORMAT($cKODE_BILL, $cSTTS)=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, $cSTTS)=='1');
					INPUT('number', [1,1,1,1], '900', 'DETAIL'.$J.'_DATA_COL', GET_FORMAT($cKODE_BILL, 'DETAIL'.$J.'_DATA_COL'));
					INPUT('checkbox', [2,2,2,2], '900', 'DETAIL'.$J.'_STATUS', GET_FORMAT($cKODE_BILL, 'TOTAL'.$J.'_STATUS')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'TOTAL'.$J.'_STATUS')=='1');
					INPUT('number', [2,2,2,2], '900', 'DETAIL'.$J.'_LABEL', GET_FORMAT($cKODE_BILL, 'DETAIL'.$J.'_LABEL'));
					CLEAR_FIX();
				}
				LABEL([5,5,3,3], '700', S_MSG('PS35','Line after detail'));
				INPUT('checkbox', [1,1,1,1], '900', 'LINE_AFTER_DETAIL', GET_FORMAT($cKODE_BILL, 'LINE_AFTER_DETAIL')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'LINE_AFTER_DETAIL')=='1');
				INPUT('number', [1,1,1,1], '900', 'LINE_AFTER_DTL_COL', GET_FORMAT($cKODE_BILL, 'LINE_AFTER_DTL_COL'));
				LABEL([1,1,1,3], '700', 'Length');
				INPUT('number', [1,1,1,1], '900', 'LINE_AFTER_DTL_LENGTH', GET_FORMAT($cKODE_BILL, 'LINE_AFTER_DTL_LENGTH'));
				LABEL([1,1,1,3], '700', 'Point');
				INPUT('number', [1,1,1,1], '900', 'LINE_AFTER_DTL_POINT', GET_FORMAT($cKODE_BILL, 'LINE_AFTER_DTL_POINT'));
				CLEAR_FIX();
				LABEL([5,5,3,3], '700', 'Terbilang');
				INPUT('checkbox', [1,1,1,1], '900', 'TOTAL_SAYS_STATUS', GET_FORMAT($cKODE_BILL, 'TOTAL_SAYS_STATUS')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'TOTAL_SAYS_STATUS')=='1');
				INPUT('number', [1,1,1,1], '900', 'TOTAL_SAYS_COL', GET_FORMAT($cKODE_BILL, 'TOTAL_SAYS_COL'));
				INPUT('number', [1,1,1,1], '900', 'TOTAL_SAYS_ROW', GET_FORMAT($cKODE_BILL, 'TOTAL_SAYS_ROW'));
				LABEL([1,1,1,3], '700', 'Font');
				SELECT([3,3,3,6], 'TOTAL_SAYS_FONT_CODE');
				echo "<option value=''  ></option>";
				$qFONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
				$cBILL_FONT = GET_FORMAT($cKODE_BILL, 'TOTAL_SAYS_FONT_CODE');
				while($aREC_FONT=SYS_FETCH($qFONT)){
					if($aREC_FONT['KEY_ID']==$cBILL_FONT){
						echo "<option value='$aREC_FONT[KEY_ID]' selected='$aREC_FONT[KEY_ID]' >$aREC_FONT[DESCRIPT]</option>";
					} else
						echo "<option value='$aREC_FONT[KEY_ID]'  >$aREC_FONT[DESCRIPT]</option>";
				}
				echo '</select>';
				CLEAR_FIX();
			eTDIV();
			echo '<div class="tab-pane fade in" id="GARIS">';
				LABEL([5,1,1,1], '700', '');
				LABEL([1,1,1,1], '700', $cCETAK);
				LABEL([1,1,1,1], '700', $cKOLOM);
				LABEL([1,1,1,1], '700', $cBARIS);
				LABEL([1,1,1,1], '700', S_MSG('PS26','S/d'));
				LABEL([1,1,1,1], '700', $cKOLOM);
				LABEL([1,1,1,1], '700', $cBARIS);
				LABEL([1,1,1,1], '700', S_MSG('PS25','Tebal'), 'fix');
				LABEL([4,3,3,3], '700', S_MSG('PS20','Garis'));
				for($I = 1; $I<=9; $I++):
					$J=(string)$I;
					LABEL([1,1,1,1], '700', $J);
					INPUT('checkbox', [1,1,1,1], '900', 'LINE.'.$J.'_CTK', GET_FORMAT($cKODE_BILL, 'LINE'.$J.'_CTK')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'LINE'.$J.'_CTK')=='1');
					INPUT('number', [1,1,1,1], '900', 'LINE'.$J.'_LEFT_COL', GET_FORMAT($cKODE_BILL, 'LINE'.$J.'_LEFT_COL'));
					INPUT('number', [1,1,1,1], '900', 'LINE'.$J.'_LEFT_ROW', GET_FORMAT($cKODE_BILL, 'LINE'.$J.'_LEFT_ROW'));
					LABEL([1,1,1,1], '700', '');
					INPUT('number', [1,1,1,1], '900', 'LINE'.$J.'_RIGHT_COL', GET_FORMAT($cKODE_BILL, 'LINE'.$J.'_RIGHT_COL'));
					INPUT('number', [1,1,1,1], '900', 'LINE'.$J.'_RIGHT_ROW', GET_FORMAT($cKODE_BILL, 'LINE'.$J.'_RIGHT_ROW'));
					INPUT('number', [1,1,1,1], '900', 'LINE'.$J.'_POINT', GET_FORMAT($cKODE_BILL, 'LINE'.$J.'_POINT'));
					CLEAR_FIX();
					LABEL([4,4,3,3], '700', '');
				endfor;
				CLEAR_FIX();
				LABEL([4,4,3,3], '700', S_MSG('PS90','Kotak'));
				for($I = 1; $I<=9; $I++):
					$J=(string)$I;
					LABEL([1,1,1,1], '700', '#'.$J);
					INPUT('checkbox', [1,1,1,1], '900', 'BOX.'.$J.'_CTK', GET_FORMAT($cKODE_BILL, 'BOX'.$J.'_CTK')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'BOX'.$J.'_CTK')=='1');
					INPUT('number', [1,1,1,1], '900', 'BOX'.$J.'_LEFT_COL', GET_FORMAT($cKODE_BILL, 'BOX'.$J.'_LEFT_COL'));
					INPUT('number', [1,1,1,1], '900', 'BOX'.$J.'_LEFT_ROW', GET_FORMAT($cKODE_BILL, 'BOX'.$J.'_LEFT_ROW'));
					LABEL([1,1,1,1], '700', '');
					INPUT('number', [1,1,1,1], '900', 'BOX'.$J.'_RIGHT_COL', GET_FORMAT($cKODE_BILL, 'BOX'.$J.'_RIGHT_COL'));
					INPUT('number', [1,1,1,1], '900', 'BOX'.$J.'_RIGHT_ROW', GET_FORMAT($cKODE_BILL, 'BOX'.$J.'_RIGHT_ROW'));
					INPUT('number', [1,1,1,1], '900', 'BOX'.$J.'_POINT', GET_FORMAT($cKODE_BILL, 'BOX'.$J.'_POINT'));
					CLEAR_FIX();
					LABEL([4,4,3,3], '700', '');
				endfor;
				CLEAR_FIX();
			eTDIV();
			echo '<div class="tab-pane fade in" id="KONSTAN">';
				LABEL([5,5,6,6], '700', 'Fixed Text');
				LABEL([1,1,1,1], '700', $cCETAK);
				LABEL([1,1,1,1], '700', $cKOLOM);
				LABEL([1,1,1,1], '700', $cKOLOM);
				LABEL([1,1,1,1], '700', 'Font');
					for($I = 1; $I<=12; $I++):
						$J=(string)$I;
						INPUT('text', [5,5,1,3], '900', 'KONST'.$J.'_CONTENT"', GET_FORMAT($cKODE_BILL, 'KONST'.$J.'_CONTENT'));
						INPUT('checkbox', [1,1,1,1], '900', 'KONST'.$J.'_STAT', GET_FORMAT($cKODE_BILL, 'KONST'.$J.'_STAT')=='1', '', '', '', 0, '', '', '', GET_FORMAT($cKODE_BILL, 'KONST'.$J.'_STAT')=='1');
						INPUT('number', [1,1,1,1], '900', 'KONST'.$J.'_COL', GET_FORMAT($cKODE_BILL, 'KONST'.$J.'_COL'));
						INPUT('number', [1,1,1,1], '900', 'KONST'.$J.'_ROW', GET_FORMAT($cKODE_BILL, 'KONST'.$J.'_ROW'));
						echo '<select name="KONST'.$J.'_FONT_CODE" class="col-lg-3 col-md-2 col-sm-5 form-label-900">';
						echo '<option value=""  ></option>';
							$qFONT=OpenTable('TbFont', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							$cBILL_FONT = GET_FORMAT($cKODE_BILL, 'KONST'.$J.'_FONT_CODE');
							while($aREC_FONT=SYS_FETCH($qFONT)){
								if($aREC_FONT['KEY_ID']==$cBILL_FONT){
									echo "<option value='$aREC_FONT[KEY_ID]' selected='$aREC_FONT[KEY_ID]' >$aREC_FONT[DESCRIPT]</option>";
								} else
									echo "<option value='$aREC_FONT[KEY_ID]'  >$aREC_FONT[DESCRIPT]</option>";
							}
						echo '</select>';
						CLEAR_FIX();
					endfor;
					CLEAR_FIX();
			eTDIV();
	eTDIV();
?>
