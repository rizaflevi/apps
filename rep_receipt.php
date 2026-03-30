<?php
//	rep_receipt.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Laporan - Penerimaan.pdf';
	$cHEADER 		= S_MSG('NR16','Daftar Penerimaan');
	$ADD_LOG		= APP_LOG_ADD($cHEADER);
	$cACTION = '';
	if (isset($_GET['action']))	$cACTION = $_GET['action'];
    $cCODEUSER=(isset($_GET['_u']) ? $_GET['_u'] : '');

	$cKD_TRM 	= S_MSG('NR02','No. Penerimaan');
	$cTANGGAL 	= S_MSG('NR03','Tanggal');
	$cNIL_TRN	= S_MSG('NR09','Nilai');
	$cKETERANGAN = S_MSG('NR04','Keterangan');
	$cMESSAG1	= S_MSG('F021','Benar data ini mau di hapus ?');
	$cBANK_NAME	= S_MSG('F131','Nama Bank');
	$cTGL1		= S_MSG('RS02','Tanggal');
	$cTGL2		= S_MSG('RS14','s/d');
	$cUSER		= S_MSG('TU1F','User');
	
	$l_RESTO = IS_RESTO($cAPP_CODE);
	$qMAIN_MENU=OpenTable('Main_Menu', "APP_CODE='$cAPP_CODE' and sort>0");
	while($aMAIN_MENU=SYS_FETCH($qMAIN_MENU)) {
		if($aMAIN_MENU['link']=='pos_menu.php')	$l_RESTO=1;
	}

	$dPERIOD1=date("d/m/Y");
	$dPERIOD2=date("d/m/Y");
	$nCHANNEL = '';

	if (isset($_GET['_d1'])) $dPERIOD1 = $_GET['_d1'];
	if (isset($_GET['_d2'])) $dPERIOD2 = $_GET['_d2'];
	if (isset($_GET['_c'])) $nCHANNEL = $_GET['_c'];
	$cFFILT = "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and A.TGL_BAYAR>='".DMY_YMD($dPERIOD1)."' and A.TGL_BAYAR<='".DMY_YMD($dPERIOD2)."'";
	if ($cCODEUSER>'') $cFFILT .= " and A.ENTRY='$cCODEUSER'";
	switch($nCHANNEL){
		case '':
			break;
		case 'Cash':
			$cFFILT.=" and BANK=''";
			break;
		default:
			$cFFILT.=" and BANK='$nCHANNEL'";
			break;
	}
	$qQUERY=OpenTable('TrReceiptHdr', $cFFILT, '', 'A.TGL_BAYAR');
	
	DEF_WINDOW($cHEADER, 'collapse');
	$aACT = (TRUST($cUSERCODE, 'RP_RECEIPT_XLS')==1 ? ['<a href="rep_receipt_excel.php?_d1='.$dPERIOD1.'&_d2='.$dPERIOD2.'"><i class="fa fa-file-excel-o"></i>Excel</a>'] : []);
		TFORM($cHEADER, '', $aACT, $cHELP_FILE, '*');
			LABEL([1,1,1,4], '700', $cTGL1, '', 'right');
			INP_DATE([2,2,3,6], '900', '', $dPERIOD1, '', '', '', '', '', "select_BAYAR(this.value, '$dPERIOD2', '$nCHANNEL')");
			// echo '<span class="col-lg-1 col-sm-1"></span>';
			LABEL([1,1,1,4], '700', $cTGL2, '', 'right');
			INP_DATE([2,2,3,6], '900', '', $dPERIOD2, '', '', '', '', '', "select_BAYAR('$dPERIOD1', this.value, '$nCHANNEL')");

			$qCHANNEL=OpenTable('TbBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
			LABEL([1,2,2,4], '700', $cBANK_NAME, '', 'right');
            SELECT([2,3,3,6], 'SCHOOL', "select_USER('$dPERIOD1', '$dPERIOD2', this.value, '$cCODEUSER')");
				echo "<option value=''> All </option>";
				echo "<option value='Cash'> Cash </option>";
				while($aCHANNEL=SYS_FETCH($qCHANNEL)){
					if($aCHANNEL['B_CODE']==$nCHANNEL){
						echo "<option value='$aCHANNEL[B_CODE]' selected='$aCHANNEL[B_CODE]' >".DECODE($aCHANNEL['B_NAME'])."</option>";
					} else {
						echo "<option value='$aCHANNEL[B_CODE]'  >".DECODE($aCHANNEL['B_NAME'])."</option>";
					}
				}
				// if($nCHANNEL=='Cash')
				// echo "<option value='Cash' selected=Cash> Cash </option>";
				// if($nCHANNEL=='')
				// echo "<option value=' ' selected=All> All </option>";
			echo '</select>';
			if($l_RESTO) {
				$qUSER = OpenTable('TbUser', "APP_CODE='$cAPP_CODE' and DELETOR=''");
				LABEL([1,1,1,4], '700', $cUSER, '', 'right');
				SELECT([2,3,3,6], 'USERLIST', "select_USER('$dPERIOD1', '$dPERIOD2', '$nCHANNEL', this.value)");
					echo "<option value=''> All </option>";
					while($aUSER=SYS_FETCH($qUSER)){
						if($aUSER['USER_CODE']==$nCHANNEL){
							echo "<option value='$aUSER[USER_CODE]' selected='$aUSER[USER_CODE]' >".DECODE($aUSER['USER_NAME'])."</option>";
						} else {
							echo "<option value='$aUSER[USER_CODE]'  >".DECODE($aUSER['USER_NAME'])."</option>";
						}
					}
				echo '</select>';
			}
			TDIV();
				TABLE('example');
					THEAD([$cKD_TRM, $cTANGGAL, $cKETERANGAN, $cNIL_TRN, $cBANK_NAME], '', [0,0,0,1,0]);
					echo '<tbody>';
						$nTOTAL = 0;
						while($aREC_TERIMA1=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								$cICON = 'fa fa-money';
								if(trim($aREC_TERIMA1['BANK'])!='') {
									$cICON = 'fa-bank';
								}
								echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
								echo "<td>". $aREC_TERIMA1['NO_TRM']."</a></span></td>";
								echo '<td>'.date("d-M-Y", strtotime($aREC_TERIMA1['TGL_BAYAR'])).'</td>';
								echo '<td>'.DECODE($aREC_TERIMA1['DESCRP']).'</td>';
								$nAMOUNT = 0;	$cNO_FAKTUR='';
								$cCOND = "A.NO_TRM='$aREC_TERIMA1[NO_TRM]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)";
								if ($cCODEUSER) {
									$cCOND .= " and A.ENTRY='$cCODEUSER'";
								}
								$dQUERY=OpenTable('TrReceiptDtl', $cCOND);
								while($aREC_TERIMA2=SYS_FETCH($dQUERY)) {
									$nAMOUNT 	+= $aREC_TERIMA2['NILAI'];
									$cNO_FAKTUR	.= $aREC_TERIMA2['NO_FAKTUR'];
								}
								echo '<td align="right">'.CVR($nAMOUNT).'</td>';
								echo '<td>'.DECODE($aREC_TERIMA1['B_NAME']).'</td>';
								$nTOTAL += $nAMOUNT;
							echo '</tr>';
						}
					echo '</tbody>';
					TTOTAL(['Total', '', '', CVR($nTOTAL), ''], [0,0,0,1,0]);
				eTABLE();
			eTDIV();
		eTFORM('*');
	END_WINDOW();
	SYS_DB_CLOSE($DB2);	
?>

<script>

function select_BAYAR(TGL_1, TGL_2, _CHN) {
	window.location.assign("rep_receipt.php?_d1="+TGL_1+"&_d2="+TGL_2+"&_c="+_CHN);
}

function select_USER(TGL_1, TGL_2, _CHN, _USR) {
	window.location.assign("rep_receipt.php?_d1="+TGL_1+"&_d2="+TGL_2+"&_c="+_CHN+"&_u="+_USR);
}

</script>

