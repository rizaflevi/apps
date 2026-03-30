<?php
//	rep_payment.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Laporan - Pembayaran.pdf';
	$cHEADER 		= S_MSG('NP03','Daftar Pembayaran');
	$ADD_LOG	= APP_LOG_ADD($cHEADER);
	$cACTION = '';
	if (isset($_GET['action']))	$cACTION = $_GET['action'];

	$cKD_BYR 	= S_MSG('NP11','No. Pembayaran');
	$cTANGGAL 	= S_MSG('NP12','Tanggal');
	$cNIL_TRN	= S_MSG('NP36','Nilai');
	$cKETERANGAN = S_MSG('NJ53','Keterangan');
	$cMESSAG1	= S_MSG('F021','Benar data ini mau di hapus ?');
	$cBANK_NAME	= S_MSG('F131','Nama Bank');
	$cTGL1		= S_MSG('RS02','Tanggal');
	$cTGL2		= S_MSG('RS14','s/d');
	
	$dPERIOD1=date("01/m/Y");
	$dPERIOD2=date("d/m/Y");
	$nCHANNEL = '';

	if (isset($_GET['_d1'])) $dPERIOD1=$_GET['_d1'];
	if (isset($_GET['_d2'])) $dPERIOD2=$_GET['_d2'];
	if (isset($_GET['_c'])) $nCHANNEL = $_GET['_c'];

	$cPAYMENT_FILTER = "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and A.BDV_DATE>='".DMY_YMD($dPERIOD1)."' and A.BDV_DATE<='".DMY_YMD($dPERIOD2)."'";
	switch($nCHANNEL){
		case '':
			break;
		case 'Cash':
			$cPAYMENT_FILTER.=" and BDV_BANK=''";
			break;
		default:
			$cPAYMENT_FILTER.=" and BDV_BANK='$nCHANNEL'";
			break;
	}
	
	// $qQUERY = OpenTable('TrPaymentHdr', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and A.BDV_DATE>='$dPERIOD1' and A.BDV_DATE<='$dPERIOD2'", '', 'A.BDV_DATE');
	$qQUERY = OpenTable('TrPaymentHdr', $cPAYMENT_FILTER, '', 'A.BDV_DATE');
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	DEF_WINDOW($cHEADER, 'collapse');
		$aACT = (TRUST($cUSERCODE, 'RP_PAYMENT_XLS')==1 ? ['<a href="rep_payment_excel.php?_d1='.$dPERIOD1.'&_d2='.$dPERIOD2.'"><i class="fa fa-file-excel-o"></i>Excel</a>'] : []);
		TFORM($cHEADER, '', $aACT, $cHELP_FILE, '*');
			LABEL([1,1,1,4], '700', $cTGL1);
			INP_DATE([2,2,2,6], '900', '', $dPERIOD1, '', '', '', '', '', "select_BAYAR(this.value, '$dPERIOD2', '$nCHANNEL')");
			LABEL([1,1,1,4], '700', $cTGL2, '', 'right');
			INP_DATE([2,2,2,6], '900', '', $dPERIOD2, '', '', '', '', '', "select_BAYAR('$dPERIOD1', this.value, '$nCHANNEL')");
			$qCHANNEL=OpenTable('TbBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
			LABEL([2,2,2,4], '700', $cBANK_NAME, '', 'right');
            SELECT([3,3,3,6], 'SCHOOL', "select_BAYAR('$dPERIOD1', '$dPERIOD2', this.value)");
				echo "<option value=' '> All </option>";
				echo "<option value='Cash'> Cash </option>";
				while($aCHANNEL=SYS_FETCH($qCHANNEL)){
					if($aCHANNEL['B_CODE']==$nCHANNEL){
						echo "<option value='$aCHANNEL[B_CODE]' selected='$aCHANNEL[B_CODE]' >".DECODE($aCHANNEL['B_NAME'])."</option>";
					} else {
						echo "<option value='$aCHANNEL[B_CODE]'  >".DECODE($aCHANNEL['B_NAME'])."</option>";
					}
				}
				if($nCHANNEL=='Cash')
				echo "<option value='Cash' selected=Cash> Cash </option>";
				if($nCHANNEL=='')
				echo "<option value=' ' selected=All> All </option>";
			echo '</select>';

			TDIV();
				TABLE('example');
					THEAD([$cKD_BYR, $cTANGGAL, $cKETERANGAN, $cNIL_TRN, $cBANK_NAME], '', [0,0,0,1,0]);
					echo '<tbody>';
						$nTOTAL = 0;
						while($aREC_BAYAR1=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								$cICON = 'fa fa-money';
								if(trim($aREC_BAYAR1['BDV_BANK'])!='') {
									$cICON = 'fa-bank';
								}
								echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
								echo "<td>". $aREC_BAYAR1['BDV_NO']."</a></span></td>";
								echo '<td>'.date("d-M-Y", strtotime($aREC_BAYAR1['BDV_DATE'])).'</td>';
								echo '<td>'.$aREC_BAYAR1['BDV_DESC'].'</td>';
								$nAMOUNT = 0;
								$dQUERY = OpenTable('TrPaymentDtl', "A.BDV_NO='$aREC_BAYAR1[BDV_NO]' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
								while($aREC_PAYMENT=SYS_FETCH($dQUERY)) {
									$nAMOUNT 	+= $aREC_PAYMENT['BDV_DAM'];
								}
								echo '<td align="right">'.number_format($nAMOUNT).'</td>';
								echo '<td>'.decode_string($aREC_BAYAR1['B_NAME']).'</td>';
								$nTOTAL += $nAMOUNT;
							echo '</tr>';
						}
						TTOTAL(['Total', '', '', CVR($nTOTAL), ''], [0,0,0,1,0]);
					echo '</tbody>';
				eTABLE();
			TDIV();
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
?>

<script>

function select_BAYAR(TGL_1, TGL_2, _CHN) {
	window.location.assign("rep_payment.php?_d1="+TGL_1+"&_d2="+TGL_2+"&_c="+_CHN);
}

</script>

