<?php
//	tr_purchase.php //
// TODO : full test

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();

	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$sPERIOD1 = $_SESSION['sCURRENT_PERIOD'];
	$cHELP_FILE = 'Doc/Transaksi - Pembelian.pdf';
	if (isset($_GET['PERIOD']))		$sPERIOD1 = $_GET['PERIOD'];

	$cHEADER = S_MSG('TP01','Pembelian');
	$cACTION = '';
	if (isset($_GET['_a']))		$cACTION = $_GET['_a'];
	$can_CREATE = TRUST($cUSERCODE, 'TR_PURCHASE_1ADD');

	$cEDIT_PURCH 	= S_MSG('TP40','Edit');
	$cADD_DTL_SLS 	= S_MSG('TP04','Tambah Detil Pembelian');
	$cEDIT_DTL_SLS 	= S_MSG('TP05','Edit Detil Pembelian');

	$cNO_FAKTUR		= S_MSG('TP13','No.Faktur');
	$cTANGGAL 		= S_MSG('TP14','Tanggal');
	$cTGL_JTMP		= S_MSG('NR11','Jatuh Tempo');
	$cSUPPLIER 		= S_MSG('TP26','Supplier');
	$cALMT_SPL 		= S_MSG('F005','Alamat');
	$cGUDANG		= S_MSG('RH53','Gudang');
	$cJUMLAH		= S_MSG('TP41','Jumlah');
	$cNIL_TRN		= S_MSG('NR09','Nilai');
	$cDISKON		= S_MSG('NJ15','Diskon');
	$cDISK2			= S_MSG('TP45','Diskon 2');
	$cP_P_N			= S_MSG('TP46','Ppn');
	$cJT_TEMPO 		= S_MSG('RS03','Jt.Tempo');
	$cKODE_BRG		= S_MSG('NP61','Kode Brg');
	$cNAMA_BRG		= S_MSG('F052','Nama Barang');
	$cQUANTITY		= S_MSG('NP59','Crtn.Ls.Bj');
	$cHRG_BRG		= S_MSG('F055','Hrg.Beli');
	$cHRG_JUAL		= S_MSG('F053','Harga Jual');
	$cJUMLAH_KT		= $cNILAI_FKT = S_MSG('TP49','Total');
	
	$cTTIP_NOTA		= S_MSG('TP31','Nomor faktur/invoice dari supplier');
	$cTTIP_TGLJ		= S_MSG('TP32','Tanggal pembelian, default tanggal input/hari ini');
	$cTTIP_TJTP		= S_MSG('TP34','Tanggal jatuh tempo faktur, default tanggal input/hari ini');
	$cTTIP_QTY		= S_MSG('NJ37','Jumlah barang pembelian');
	$cTTIP_SPL		= S_MSG('TP21','Klik untuk menentukan kode supplier');
	$cTTIP_INV 		= S_MSG('TP59','Klik untuk memilih kode persediaan');
	$cTTIP_HRG_BELI	= S_MSG('TP62','Harga beli dari supplier, sebelum Pajak dan diskon.');

	$cSAVE_DATA=S_MSG('F301','Save');
	$cCLOSE_DATA=S_MSG('F302','Close');
	$cMSG_DEL		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');

	$qWHOUSE=OpenTable('TbWarehouse', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$nWHOUSE=SYS_ROWS($qWHOUSE);
	$qINVENT=OpenTable('InvAndGroup', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", '', 'A.GROUP_INV');
	$allINVENT = ALL_FETCH($qINVENT);
	$qINV_GRUP=OpenTable('TbInvGroup', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$nINV_GROUP=SYS_ROWS($qINV_GRUP);

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'view');
		$qQUERY=OpenTable('TrPurchHdrVAct', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and left(A.DATE_REC,7)='".substr($sPERIOD1,0,7)."'", '', "A.INVOICE desc");
		DEF_WINDOW($cHEADER, '', 'prd');
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cNO_FAKTUR, $cTANGGAL, $cSUPPLIER, $cNIL_TRN, $cDISKON, $cDISK2, $cP_P_N, $cJUMLAH, $cTGL_JTMP], '', [0,0,0,1,1,1,1,1,0]);
						echo '<tbody>';
							$nTOTAL = $nJUMLAH= $nDISKON= $nT_P_R = $nPPN = 0;	
							while($aREC_MASUK1=SYS_FETCH($qQUERY)) {
								$cICON = 'fa fa-money';
								$nAMOUNT = $aREC_MASUK1['JUMLAH']+$aREC_MASUK1['PPN']-$aREC_MASUK1['DISK'];
								$nJUMLAH += $aREC_MASUK1['JUMLAH'];
								$nPPN 	+= $aREC_MASUK1['PPN'];
								$nTOTAL += $nAMOUNT;
								$nDISKON += $aREC_MASUK1['DISK'];
								$nT_P_R += $aREC_MASUK1['DISK2'];
								$cHREFF="<a href='?_a=".md5('upd4t3')."&_r=".md5($aREC_MASUK1['INVOICE'])."'>";
								TDETAIL([$aREC_MASUK1['INVOICE'], date("d-M-Y", strtotime($aREC_MASUK1['DATE_REC'])), $aREC_MASUK1['NAMA_VND'],
									CVR($aREC_MASUK1['JUMLAH']), CVR($aREC_MASUK1['DISK']), CVR($aREC_MASUK1['DISK2']), CVR($aREC_MASUK1['PPN']),
									CVR($nAMOUNT), date("d-M-Y", strtotime($aREC_MASUK1['DUE_DATE']))], 
									[0,0,0,1,1,1,1,1,0], '', [$cHREFF, $cHREFF, '', '', '', '', '', '', '']);
							}
						echo '</tbody>';
						TTOTAL(['Total', '', '', CVR($nJUMLAH), CVR($nDISKON), CVR($nT_P_R), CVR($nPPN), CVR($nTOTAL), ''], [0,0,0,1,1,1,1,1,0]);
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('cr34t3'):
		$cADD_PURCH 	= S_MSG('TP16','Tambah');
		DEF_WINDOW($cADD_PURCH);
			TFORM($cADD_PURCH, '?_a=REC_ADD', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cNO_FAKTUR);
					INPUT('text', [2,2,2,3], '900', 'ADD_INVOICE', '', '', '', '', 0, '', 'fix', $cTTIP_NOTA);
					LABEL([3,3,3,6], '700', $cTANGGAL);
					INP_DATE([2,2,3,6], '900', 'ADD_DATE_REC', date('d/m/Y'), '', '', '', '', $cTTIP_TGLJ);
					LABEL([1,1,1,1], '700', '');
					LABEL([2,2,2,6], '700', $cTGL_JTMP, '', 'right');
					INP_DATE([2,2,3,6], '900', 'ADD_TGL_JTMP', date('d/m/Y'), '', '', '', 'fix', $cTTIP_TJTP);
					LABEL([3,3,3,6], '700', $cSUPPLIER, '', '');
					TDIV(8,8,8,8);
						SELECT([4,4,4,6], 'ADD_VENDOR', '', '','s2AddVendor', 'select2');
							echo '<option></option>';
							$qREC_VND_GROUP = OpenTable('TbVendorGrp', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							if(SYS_ROWS($qREC_VND_GROUP)==0){
								$qQUERY=OpenTable('Vendor', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', "NAMA_VND");
								while($aREC_VENDOR=SYS_FETCH($qQUERY)){
									echo "<option value='$aREC_VENDOR[KODE_VND]'  >$aREC_VENDOR[NAMA_VND]</option>";
								}
							} else {
								while($aqREC_VND_GROUP=SYS_FETCH($qREC_VND_GROUP)){
									echo "<optgroup label='$aqREC_VND_GROUP[VG_DESC]'>";
									$REC_SUPPLIER=OpenTable('Vendor', "VND_GROUP='$aqREC_VND_GROUP[VG_CODE]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', "NAMA_VND");
									while($aREC_VENDOR=SYS_FETCH($REC_SUPPLIER)){
										echo "<option value='$aREC_VENDOR[KODE_VND]'  >$aREC_VENDOR[NAMA_VND]</option>";
									}
									echo '</optgroup>';
								}
							}
						echo '</select><br>';
					eTDIV();
					CLEAR_FIX();
					if($nWHOUSE>0) {
						LABEL([3,3,3,6], '700', $cGUDANG, '', '');
						SELECT([4,4,4,6], 'PILIH_GUDANG');
							while($aREC_GR_DATA=SYS_FETCH($qWHOUSE)){
								if($aREC_GR_DATA['KODE_GDG']==$cFILTER_GUDANG){
									echo "<option value='$aREC_GR_DATA[KODE_GDG]' selected='$REC_EDIT[KODE_GDG]' >$aREC_GR_DATA[NAMA_GDG]</option>";
								} else {
									echo "<option value='$aREC_GR_DATA[KODE_GDG]'  >$aREC_GR_DATA[NAMA_GDG]</option>";
								}

							}
						echo '</select>';
					}
					CLEAR_FIX();
					TABLE('example');
						THEAD([$cNAMA_BRG, S_MSG('TP08','Jml Barang'), $cHRG_BRG, $cHRG_JUAL, S_MSG('TP11','Jumlah'), S_MSG('TP12','Margin')], '', [0,0,1,1,1,1], '*', [3,2,2,2,2,1]);
						echo '<tbody><tr>';
							echo '<td>';
								SELECT([12,12,12,12], 'ADD_DTL_INVENT', 'SELECT_INV()', 's2AddInv', '', $cTTIP_INV);
									$I=0;
									$nSIZE_ARRAY = count($allINVENT);
									echo '<option></option>';
									if($nINV_GROUP>0) {
										while($aGRUP=SYS_FETCH($qINV_GRUP)){
											echo '<optgroup label="'.$aGRUP['NAMA_GRP'].'">';
											while($I<$nSIZE_ARRAY-1) {
												if ($allINVENT[$I]['GROUP_INV']==$aGRUP['KODE_GRP']) {
													$cSELECT = $allINVENT[$I]['NAMA_BRG']."  /  ".$allINVENT[$I]['KODE_BRG']."  /  ".CVR($allINVENT[$I]['HARGA_JUAL'])."  /  ".$allINVENT[$I]['NAMA_GRP'];
													$cVALUE = $allINVENT[$I]['KODE_BRG'];
													echo '<option value="'.$cVALUE.'">'.$cSELECT.'</option>';
												}
												$I++;
											}
										}
									} else {
										while($I<$nSIZE_ARRAY-1) {
											$cSELECT = $allINVENT[$I]['NAMA_BRG']."  /  ".$allINVENT[$I]['KODE_BRG']."  /  ".CVR($allINVENT[$I]['HARGA_JUAL']);
											$cVALUE = $allINVENT[$I]['KODE_BRG'];
											echo '<option value="'.$cVALUE.'">'.$cSELECT.'</option>';
											$I++;
										}
									}
								echo '</select><br>';
							echo '</td>';
								INPUT('text', [12,12,12,12], '900', 'ADD_JUMLAH_BRG', '', '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
								INPUT('text', [12,12,12,12], '900', 'ADD_HARGA_BRG', '', '', 'fdecimal', 'right', 0, '', 'fix', '', '', '', '', '', 'td');
								INPUT('text', [12,12,12,12], '900', 'ADD_HRG_JUAL', '', '', 'fdecimal', 'right', 0, '', 'fix', '', '', '', '', '', 'td');
								echo '<td><input type="text" class="col-lg-12 col-sm-12 form-label-900" name="ADD_JML" id="ADD_JML" style="text-align:right" onblur="Add_Purchase_Quantity(this.value) title="'.$cTTIP_QTY.'"></td>';
								// INPUT('text', [12,12,12,12], '900', 'ADD_JML', '', '', 'fdecimal', 'right', 0, '', 'fix', '', '', '', '', '', 'td');
								INPUT('text', [12,12,12,12], '900', 'ADD_MRG', '', '', 'fdecimal', 'right', 0, 'disabled', 'fix', '', '', '', '', '', 'td');
						echo '<tr></tbody>';
					eTABLE();
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
	break;

	case md5('upd4t3'):
		$can_DELETE = TRUST($cUSERCODE, 'TR_PURCHASE_3DEL');
		$qPHDR=OpenTable('TrPurchaseHdr', "md5(INVOICE)='$_GET[_r]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		$aREC_MASUK1=SYS_FETCH($qPHDR);
		$cREC=$aREC_MASUK1['REC_ID'];
		$cNOTA = $aREC_MASUK1['INVOICE'];
		DEF_WINDOW($cEDIT_PURCH);
			$aACT=[];
			if ($can_DELETE==1) {
				array_push($aACT, '<a href="?_a=DEL_PURCHASE&_r='.$cREC. '" onClick="return confirm('. "'". $cMSG_DEL. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>'.S_MSG('F304','Delete').'</a>');
			}
			TFORM($cEDIT_PURCH, '?_a=DB_SAVE&_r='.$cREC, [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cNO_FAKTUR);
					INPUT('text', [2,2,2,6], '900', 'EDIT_NOTA', $cNOTA, '', '', '', 0, 'disable', 'fix', $cTTIP_NOTA);
					LABEL([3,3,3,6], '700', $cTANGGAL);
					INP_DATE([2,2,3,6], '900', 'EDIT_DATE_REC', date("d/m/Y", strtotime($aREC_MASUK1['DATE_REC'])), '', '', '', '', $cTTIP_TGLJ);
					LABEL([1,1,1,1], '700', '');
					LABEL([3,3,3,6], '700', $cTGL_JTMP, '', 'right');
					INP_DATE([2,2,3,6], '900', 'UPD_TGL_JTMP', date("d/m/Y", strtotime($aREC_MASUK1['DUE_DATE'])), '', '', '', 'fix', $cTTIP_TJTP);
					LABEL([3,3,3,6], '700', $cSUPPLIER);
					SELECT([5,5,5,6], 'UPD_VENDOR', '', '', '', $cTTIP_SPL);
						$qQUERY=OpenTable('Vendor', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', "NAMA_VND");
						while($aREC_VENDOR=SYS_FETCH($qQUERY)){
							if($aREC_VENDOR['KODE_VND']==$aREC_MASUK1['VENDOR']){
								echo "<option value='$aREC_MASUK1[VENDOR]' selected='$aREC_MASUK1[VENDOR]' >$aREC_VENDOR[NAMA_VND]</option>";
							} else
							echo "<option value='$aREC_VENDOR[KODE_VND]'  >$aREC_VENDOR[NAMA_VND]</option>";
						}
					echo '</select><br>';
				eTDIV();
				TDIV();
					TABLE('example');
						THEAD([$cKODE_BRG, $cNAMA_BRG, $cQUANTITY, $cHRG_BRG], '', [], '*');
							echo '<tbody>';
								$qQ_MSK=OpenTable('TrPurchDtlInv', "A.INVOICE='$cNOTA' and A.HDR_ID='$aREC_MASUK1[REC_ID]' and A.REC_ID not in ( select DEL_ID from logs_delete )");
								$nTOTAL = 0;
								while($aREC_MASUK2=SYS_FETCH($qQ_MSK)) {
									echo '<tr>';
										echo "<td><span><a href='?_a=".md5('edit_detail_trans')."&_r=$aREC_MASUK2[REC_ID]'>". $aREC_MASUK2['KODE_BRG'].'</a></span></td>';
										echo "<td><span><a href='?_a=".md5('edit_detail_trans')."&_r=$aREC_MASUK2[REC_ID]'>". $aREC_MASUK2['NAMA_BRG'].'</a></span></td>';
										echo '<td align="right">'.$aREC_MASUK2['JML'].'</td>';
										echo '<td align="right">'.number_format($aREC_MASUK2['HRG_BELI']).'</td>';
									echo '</tr>';
									$nTOTAL += $aREC_MASUK2['HRG_BELI'];
								}
								TTOTAL(['Total', '', CVR($nTOTAL)], [0,1,0,1]);
							echo '</tbody>';
					eTABLE();
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('edit_detail_trans'):
		$cREC_ID = $_GET['_r'];
		$qQUERY=OpenTable('TrPurchDtlInv', "A.REC_ID='$cREC_ID'");
		$aREC_DETAIL=SYS_FETCH($qQUERY);
		DEF_WINDOW($cHEADER);
			TFORM($cHEADER, '?_a=upd_upd_dtl&_r='.$cREC_ID, [], $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cNAMA_BRG);
					TDIV(8,8,8,8);
						SELECT([6,6,6,6], 'UPD_UPD_INVENT', '', '', 'select2');
							$cREC_GRUP=OpenTable('TbInvGroup', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							if(SYS_ROWS($cREC_GRUP)==0){
								$qREC_INVEN=OpenTable('Invent', "NO_ACTIVE=0 and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'NO_ACTIVE');
								while($aREC_INVENT=SYS_FETCH($qREC_INVEN)){
									if($aREC_INVENT['KODE_BRG']==$aREC_DETAIL['KODE_BRG'])
										echo "<option value='$aREC_DETAIL[KODE_BRG]' selected='$aREC_DETAIL[KODE_BRG]' >".DECODE($aREC_INVENT['NAMA_BRG'])."</option>";
									else
										echo "<option value='$aREC_INVENT[KODE_BRG]'  >$aREC_INVENT[NAMA_BRG]</option>";
								}
							} else {
								while($aREC_GRUP=SYS_FETCH($cREC_GRUP)){
									echo "<optgroup label='$aREC_GRUP[NAMA_GRP]'>";
									$oREC_INVEN=OpenTable('Invent', "NO_ACTIVE=0 and GROUP_INV='$aREC_GRUP[KODE_GRP]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'NO_ACTIVE');
									while($aREC_INVEN=SYS_FETCH($oREC_INVEN)){
										echo "<option value='$aREC_INVEN[KODE_BRG]'  >".$aREC_INVEN['KODE_BRG'].":".$aREC_INVEN['NAMA_BRG']."</option>";
									}
									echo '</optgroup>';
								}
							}
						echo '</select>';
					eTDIV();
					LABEL([4,4,4,6], '700', $cQUANTITY);
					INPUT('text', [2,2,2,6], '900', 'UPD_UPD_QTY', $aREC_DETAIL['JUMLAH'], '', '', '', 0, '', 'fix', $cTTIP_HRG_BELI);
					LABEL([4,4,4,6], '700', $cHRG_BRG);
					INPUT('text', [3,3,3,6], '900', 'UPD_UPD_PRICE', $aREC_DETAIL['HRG_BELI'], '', '', '', 0, '', 'fix', $cTTIP_HRG_BELI);
					LABEL([4,4,4,6], '700', $cJUMLAH);
					INPUT('text', [3,3,3,6], '900', 'UPD_UPD_AMOUNT', $aREC_DETAIL['JUMLAH']*$aREC_DETAIL['HRG_BELI'], '', '', '', 0, 'disable', 'fix');
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case 'REC_ADD':
		$dTG_BAYAR	= $_POST['ADD_DATE_REC'];		// 'dd/mm/yyyy'
		$cJML 	= str_replace(',', '', $_POST['ADD_JUMLAH_BRG']);
		$cNOTA		= $_POST['ADD_INVOICE'];
		if($cNOTA==''){
			MSG_INFO(S_MSG('NJ49','Nomor Faktur masih kosong'));
			return;
		}
		if($_POST['ADD_VENDOR']==''){
			MSG_INFO(S_MSG('TP5A','Supplier masih kosong'));
			return;
		}
		if(!$cJML){
			MSG_INFO(S_MSG('TP5B','Jumlah Pembelian masih kosong'));
			return;
		}
		$qQUERY=OpenTable('TrPurchaseHdr', "INVOICE='$cNOTA' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		if(SYS_FETCH($qQUERY)){
			MSG_INFO(S_MSG('TP5C','Nomor faktur pembelian sudah ada'));
			return;
		} else {
			$cREC=NowMSecs();
			$nJML=(integer)$nVALcJMLUE;
			$nHRG_BELI=(integer)$_POST['ADD_HARGA_BRG'];
			RecCreate('TrPurchaseHdr', ['INVOICE', 'DATE_REC', 'DUE_DATE', 'ENTRY', 'APP_CODE', 'REC_ID'], 
				[$cNOTA, DMY_YMD($dTG_BAYAR), DMY_YMD($_POST['ADD_TGL_JTMP']), $cUSERCODE, $cAPP_CODE, $cREC]);

			RecCreate('TrPurchaseDtl', ['INVOICE', 'KODE_BRG', 'HRG_BELI', 'JUMLAH', 'ENTRY', 'REC_ID', 'HDR_ID'], 
				[$cNOTA, $_POST['ADD_DTL_INVENT'], $nHRG_BELI, $nJML, $cUSERCODE, NowMSecs(), $cREC]);
			APP_LOG_ADD($cHEADER, 'add '.$cNOTA);
			header('location:tr_purchase.php');
		}
		break;

	case 'DB_SAVE':
		$cREC	= $_GET['_r'];
		$dTG_JUAL 	= DMY_YMD($_POST['EDIT_DATE_REC']);
		$dTG_BAYAR 	= DMY_YMD($_POST['UPD_DUE_DATE']);
		$cJT_TEMPO	= substr($dTG_BAYAR,6,4). '-'. substr($dTG_BAYAR,3,2). '-'. substr($dTG_BAYAR,0,2);

		RecUpdate('TrPurchaseHdr', ['VENDOR', 'DATE_REC', 'DUE_DATE'], [$_POST['UPD_VENDOR'], $dTG_JUAL, $dTG_BAYAR], "REC_ID='$cREC'");
		APP_LOG_ADD($cHEADER, 'edit '.$cREC);
		header('location:tr_purchase.php');
		break;

	case md5('VOID_INVOICE'):
		$cNOTA=$_GET['_r'];
		RecSoftDel($cNOTA);
		$q_MASUK2=OpenTable('TrPurchaseDtl', "APP_CODE='$cAPP_CODE' and HDR_ID='$cNOTA'");
		while($r_MASUK2=SYS_FETCH($q_MASUK2)){
			$q_STOCK_QRY=OpenTable('Q_Stock', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete ) and INV_CODE='$r_MASUK2[KODE_BRG]' and WAREHOUSE='$r_MASUK2[KODE_GDG]' and ST_YEAR=".left($sPERIOD1,4)." and ST_MONTH=".substr($sPERIOD1,5,2));
			if(SYS_ROWS($q_STOCK_QRY)>0){
				$cREC_STOCK=SYS_FETCH($q_STOCK_QRY);
				RecUpdate('Q_Stock', ['PURCHASE', 'CUR_STOCK'], [$q_STOCK_QRY['PURCHASE'] - $r_MASUK2['QUANTITY']], "INV_CODE='$r_MASUK2[KODE_BRG]' and WAREHOUSE='$r_MASUK2[KODE_GDG]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
			} else {
	// TODO : add APP_CODE
				RecCreate('Q_Stock', ['INV_CODE', 'CUR_STOCK'], [$r_MASUK2['KODE_BRG'], $q_STOCK_QRY['PURCHASE'] - $r_MASUK2['QUANTITY']]);
			}
			RecSoftDel($r_MASUK2['REC_ID']);
		}
		APP_LOG_ADD($cHEADER, 'delete '.$cNOTA);
		header('location:tr_purchase.php');
		break;

	case 'upd_add_dtl':
		$NOW = date("Y-m-d H:i:s");
		$cPOST = $_POST['ADD_UPD_KODE_BRG'];
		if($cPOST==''){
			MSG_INFO(S_MSG('TP65','Kode barang masih kosong'));
			return;
			header('location:tr_purchase.php');
		}
		if($_POST['ADD_DTL_VALUE']==0){
			MSG_INFO(S_MSG('TP66','Jumlah pembelian masih kosong'));
			return;
			header('location:tr_purchase.php');
		}

		$NOMOR_TERIMA = $_GET['NOMOR_TERIMA'];
		$nVALUE = str_replace(',', '', $_POST['ADD_DTL_VALUE']);
		$cQUERY="select * from masuk2 where APP_CODE='$cAPP_CODE' and DELETOR='' and INVOICE='$NOMOR_TERIMA'";
		$cCEK_KODE=SYS_QUERY($cQUERY) or die ('Error in query.' .mysql_error().'==>'.$cQUERY);
		$cQUERY="insert into masuk2 set INVOICE='$NOMOR_TERIMA', 
			KODE_BRG='$_POST[ADD_UPD_KODE_BRG]', JUAL_C='$_POST[ADD_UPD_JUAL_C]', 
			JUMLAH='$nVALUE', 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:tr_purchase.php?_a='.md5('upd4t3').'&_r='.$NOMOR_TERIMA);
		break;

	case 'upd_upd_dtl':
		$nREC_NO = $_GET['_r'];
		$qUPD_DTL_QUERY=OpenTable('TrPurchDtlInv', "A.INVOICE='$cNOTA' and A.HDR_ID='$aREC_MASUK1[REC_ID]' and A.REC_ID not in ( select DEL_ID from logs_delete )");
		$aREC_UPD_DETAIL=SYS_FETCH($qUPD_DTL_QUERY);
		$nDEBET = $_POST['UPD_UPD_VALUE'];
		$cINV = $_POST['UPD_UPD_INVENT'];
		$nQTY = $_POST['UPD_UPD_QTY'];
		if($cINV==''){
			MSG_INFO(S_MSG('NR45','Kode account penerimaan masih kosong'));
			return;
			header('location:tr_purchase.php');
		}
		if($nDEBET==0){
			MSG_INFO(S_MSG('NR46','Nilai penerimaan masih kosong'));
			header("location:tr_purchase.php?_a=".md5('upd4t3')."&_r=".md5($aREC_UPD_DETAIL['INVOICE']));
			return;
		}

		RecUpdate('TrPurchaseDtl', ['KODE_BRG', 'JUMLAH'], [$cINV, $nQTY], "REC_ID=$nREC_NO");
		header("location:tr_purchase.php?_a=".md5('upd4t3')."&_r=".md5($aREC_UPD_DETAIL['INVOICE']));
		APP_LOG_ADD($cHEADER, 'update detil '.$aREC_UPD_DETAIL['INVOICE']);
		return;
		break;

	case 'upd_del_dtl':
		RecSoftDel($_GET['_r']);
		header("location:tr_purchase.php?_a=".md5('upd4t3')."&_r=$aREC_UPD_DETAIL[INVOICE]");
		return;
		break;
	case 'load_alamat':
		$cKODE = $_GET['_x'];
		$qQUERY=OpenTable('Vendor', "APP_CODE='$cAPP_CODE' and REC_ID in ( select DEL_ID from logs_delete ) and KODE_VND=$cKODE");
		$aALAMAT=SYS_FETCH($qQUERY);
		header('Content-type: application/json');
		echo json_encode($aALAMAT);
		return;
		break;

	case 'load_harga':
		$cKODE = $_GET['_x'];
		$qQUERY=OpenTable('Invent', "APP_CODE='$cAPP_CODE' and REC_ID in ( select DEL_ID from logs_delete ) and KODE_BRG='$cKODE'");
		$aLOAD_HARGA=SYS_FETCH($qQUERY);
		header('Content-type: application/json');
		echo json_encode($aLOAD_HARGA);
		return;
	break;
}
?>

<script>
	$(function() {
		if($('#s2AddVendor').val() == ' ') {
			$('#ALM_SPL').hide();
		} else {
			$('#ALM_SPL').show();
		}
	});
	$("#s2AddVendor").select2({});

	$(function() {
		if($('#s2AddInv').val() == ' ') {
			$('#ADD_HRG_BRG').hide();
		} else {
			$('#ADD_HRG_BRG').show();
		}
	});
	$("#s2AddInv").select2({});


function Add_Purchase_Quantity(pQuantity) {
	var btn_stat = document.getElementById("SAVE_BUTTON");  // the submit button
//		alert(pQuantity);
    if (pQuantity == "") {
        document.getElementById("ADD_INVOICE").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("ADD_ALAMAT").innerHTML = xmlhttp.responseText;
//				alert(xmlhttp.responseText);
				document.getElementById("ADD_ALAMAT").value = xmlhttp.responseText;
            }
			if (document.getElementById("ADD_JML").value == "") {
				document.getElementById("SAVE_BUTTON").setAttribute('disabled', 'disabled');
			} else {
				document.getElementById("SAVE_BUTTON").removeAttribute('disabled');
			}
        };
        xmlhttp.open("GET","tr_sales_cek_qty.php?_QTY="+pQuantity,true);
        xmlhttp.send();
		
    }
}

function SELECT_INV() {
	$.ajax({
		url:'tr_purchase.php?_a=load_harga',
		type:'get',
		data: {_x: $('#s2AddInv').val()},
		success: function(data) {
			$('#ADD_HARGA_BRG').val(data.HARGA_BELI);
			$('#ADD_HRG_JUAL').val(data.HARGA_JUAL);
		}
	})
}

</script>

