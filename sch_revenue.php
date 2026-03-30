<?php
//	sch_revenue.php //
//	TODO : Menambahkan rincian data baru belum bisa.

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 	= $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Transaksi - Pendapatan.pdf';

    $can_CREATE = TRUST($cUSERCODE, 'TR_SALES_1ADD');

	$sPERIOD1=$_SESSION['sCURRENT_PERIOD'];
	if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];

	$cHEADER = S_MSG('NJ01','Pendapatan');
	APP_LOG_ADD($cHEADER, 'view');
	$cACTION = '';
	if (isset($_GET['_a']))	$cACTION = $_GET['_a'];
  
	$nMANUAL= (S_PARA('SALES_NUM_MANUAL', '')=='1' ? 1 : 0);
	$cADD_REVENUE 	= S_MSG('NJ06','Tambah Pendapatan');
	$cADD_DTL_RCP 	= S_MSG('NJ24','Tambah Detil Pendapatan');
	$cEDIT_DTL_JRN 	= S_MSG('NJ28','Edit Detil Pendapatan');

	$cINVOICE		= S_MSG('NJ02','No. Invoice');
	$cTANGGAL 		= S_MSG('NJ03','Tanggal');
	$cNIL_TRN		= S_MSG('NR09','Nilai');
	$cKD_ACCOUNT 	= S_MSG('NR07','Account');
	$cACCOUNT		= S_MSG('NR08','Nama Account');
	$cKETERANGAN 	= S_MSG('NR04','Keterangan');
	$cSISWA 	    = S_MSG('NJ04','Siswa');
	$cMESSAG1		= S_MSG('F021','Benar data ini mau di hapus ?');
	$cDUE_DATE		= S_MSG('NR11','Jatuh Tempo');

	$cSAVE_DATA=S_MSG('F301','Save');
	$cCLOSE_DATA=S_MSG('F302','Close');
	
	$dDATE1	= date('Y-m-01');
	$dDATE2	= date('Y-m-d');
	
switch($cACTION){
	default:
		DEF_WINDOW($cHEADER, '', 'prd');
		$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. $cADD_REVENUE.'</a>'] : []);
		TFORM($cHEADER, '', $aACT, $cHELP_FILE);
		TDIV();
		TABLE('example');
			THEAD([$cINVOICE, $cTANGGAL, $cDUE_DATE, $cSISWA, $cNIL_TRN, $cKETERANGAN], '', [0,0,0,0,1,0]);
			echo '<tbody>';
				$nTOTAL = 0;
				$qQUERY=OpenTable('SchRevStd', "left(A.REV_DATE,7)='".substr($sPERIOD1,0,7)."' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", '', "A.REC_ID desc");
				while($aREC_REV_HDR=SYS_FETCH($qQUERY)) {
					echo '<tr>';
						$cICON = 'fa fa-money';
						echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
						echo "<td><span><a href='?_a=".md5('upd4t3')."&_r=".md5($aREC_REV_HDR['REC_ID'])."'>". $aREC_REV_HDR['REV_ID']."</a></span></td>";
						echo '<td>'.date("d-M-Y", strtotime($aREC_REV_HDR['REV_DATE'])).'</td>';
						echo '<td>'.date("d-M-Y", strtotime($aREC_REV_HDR['REV_DUE'])).'</td>';
						echo '<td>'.$aREC_REV_HDR['CUST_NAME'].'</td>';
						$nAMOUNT = 0;
						$dQUERY=OpenTable('SchRevDtl', "A.REV_HDR_ID='$aREC_REV_HDR[REV_ID]' and A.APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_REV_DTL=SYS_FETCH($dQUERY)) {
							$nAMOUNT 	+= $aREC_REV_DTL['REV_VALUE'];
						}
						echo '<td align="right">'.CVR($nAMOUNT).'</td>';
						$nTOTAL += $nAMOUNT;
						echo '<td>'.$aREC_REV_HDR['NOTES'].'</td>';
					echo '</tr>';
				}
			echo '</tbody>';
			TTOTAL(['', 'Total', '', '', CVR($nTOTAL), ''], [0,0,0,0,1,0]);
		eTABLE();
		eTDIV();
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('cr34t3'):
        $cHEADER = S_MSG('TR62','Tambah Pendapatan');
        $cLAST_NOM	= '';
        if($nMANUAL==0) {
            $cPICT_INV 	= S_PARA('PICT_INV', '999999');
            $qQ_LAST 	= OpenTable('SchRevHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", '', "A.NO_TRM desc limit 1");
            if(SYS_ROWS($qQ_LAST)==0) {
                $nLAST_NOM	= 1;
            } else {
                $aREC_REV_HDR= SYS_FETCH($qQ_LAST);
                $cLAST_NOM	= $aREC_REV_HDR['NO_TRM'];
                $nLAST_NOM	= intval($cLAST_NOM)+1;
            }
            $cLAST_NOM	= str_pad((string)$nLAST_NOM, strlen($PICT_INV), '0', STR_PAD_LEFT);
        }
        $qSTUDENT=OpenTable('CustAll', "A.APP_CODE='$cAPP_CODE' and A.DELETOR=''", 'A.CUST_CODE', 'A.CUST_NAME');
		$allPEOPLE = ALL_FETCH($qSTUDENT);
        DEF_WINDOW($cHEADER);
			TFORM($cHEADER, "?_a=tam_bah", '', $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cINVOICE);
					INPUT('text', [3,3,3,6], '900', 'ADD_NO_INV', $cLAST_NOM, 'autofocus', '', '', 0, ($nMANUAL==1 ? '' : 'readonly'), 'fix');
					LABEL([4,4,4,6], '700', $cTANGGAL);
					// INPUT_DATE([2,2,3,6], '900', 'ADD_REV_DATE1', date('Y-m-d'), '', '', '', 0, '', 'fix');
?>
		<!-- <label class="col-lg-4 col-sm-4 col-xs-6 form-label-700" for="field-1"><?php echo $cTANGGAL?></label> -->
		<input type="date" class="col-lg-2 col-sm-3 col-xs-6 form-label-900" name='ADD_REV_DATE1' data-format="dd/mm/yyyy" id="field-2" value="<?php echo date('Y-m-d')?>">
		<div class="clearfix"></div>

		<label class="col-lg-4 col-sm-4 col-xs-6 form-label-700" for="field-1"><?php echo $cDUE_DATE?></label>
		<input type="date" class="col-lg-2 col-sm-3 col-xs-6 form-label-900" name='ADD_REV_DUE' data-format="dd/mm/yyyy" id="field-2" value="<?php echo date('Y-m-d')?>">
		<div class="clearfix"></div>

		<div class="form-group">
			<label class="col-lg-4 col-sm-4 col-xs-6 form-label-700">Pilih siswa</label>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<select name="ADD_STUDENT" class="col-lg-4 col-sm-4 col-xs-6 form-label-700 select2">
					<option></option>
					<?php
						$qSCHOOL=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'NAMA_GRP');
						while($aSCHOOL=SYS_FETCH($qSCHOOL)){
							echo '<optgroup label="'.$aSCHOOL['NAMA_GRP'].'">';
							$I=0;
							$nSIZE_ARRAY = count($allPEOPLE);
							while($I<$nSIZE_ARRAY-1) {
								if ($allPEOPLE[$I]['CUST_GROUP']==$aSCHOOL['KODE_GRP']) {
									$cSELECT = $allPEOPLE[$I]['CUST_NAME']."  /  ".$allPEOPLE[$I]['CUST_CODE']."  /  ".$allPEOPLE[$I]['NAMA_AREA'];
									$cVALUE = $allPEOPLE[$I]['CUST_CODE'];
									echo '<option value="'.$cVALUE.'">'.$cSELECT.'</option>';
								}
								$I++;
							}
						}
					?>
					</optgroup>
				</select>
			</div>
		</div>
		<div class="clearfix"></div><br>

		<label class="col-lg-4 col-sm-4 col-xs-6 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
		<input type="text" class="col-sm-6 col-xs-12 form-label-900" name='ADD_DESCRP1' id="field-2">
		<div class="clearfix"></div>

		<?php TABLE('example');
			THEAD([$cKETERANGAN, $cACCOUNT, $cNIL_TRN], '', [0,0,1], '*', [5,5,2]);
			echo '<tbody>';
				for ($I=1; $I < 5; $I++) {
					$cIDX 	= (string)$I;
					echo '<tr>
						<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name="ADD_DTL_DESCRP'.$cIDX.'" id="field-2"></td>
						<td style="min-width:350px;"><div class="form-group">
							<select id="SelectAccount" name="ADD_DTL_ACCOUNT'.$cIDX.'" class="col-lg-12 col-sm-12 col-xs-12 form-label-900 select2">
								<option> </option>';
								$REC_DATA=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
								while($aREC_DETAIL=SYS_FETCH($REC_DATA)){
									echo "<option value='$aREC_DETAIL[ACCOUNT_NO]'  >".DECODE($aREC_DETAIL['ACCT_NAME'])."</option>";
								}
							echo '</select>
						</div></td>
						<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name="ADD_AMOUNT_'.$cIDX.'" id="field-3" data-mask="fdecimal" data-numeric-align="right"></td>
					</tr>';
				}
			echo '</tbody>';
			eTABLE();
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");	
		END2WINDOW();
		break;

	case md5('upd4t3'):
        $cEDIT_REVENUE 	= S_MSG('TR63','Edit Pendapatan');
		$can_UPDATE 	= TRUST($cUSERCODE, 'TR_SALES_2UPD');
		$can_DELETE 	= TRUST($cUSERCODE, 'TR_SALES_3DEL');
		$can_PRINT 		= TRUST($cUSERCODE, 'TR_SALES_4PRT');
		$xREC_ID 		= $_GET['_r'];

		$qQUERY=OpenTable('SchRevStd', "md5(A.REC_ID)='$xREC_ID'");
		$aREC_REV_HDR	= SYS_FETCH($qQUERY);
		$cNO_INV 		= $aREC_REV_HDR['REV_ID'];
		$cREC_ID 		= $aREC_REV_HDR['REC_ID'];
		$UPD_ACCOUNT 	= '1';
		DEF_WINDOW($cEDIT_REVENUE);
			$aACT =[];
			if ($can_DELETE==1) 
				array_push($aACT, '<a href="?_a='.md5('del_revenue'). '&_r='.$cNO_INV. '" onClick="return confirm('. "'". $cMESSAG1. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>');
			if ($can_PRINT==1) 
				array_push($aACT, '<a href="?_a='. md5('rev_print'). '&_c='.$aREC_REV_HDR['REC_ID'].'" onClick="return confirm('. $cMESSAG1.')" title="print this payment"><i class="glyphicon glyphicon-print"></i>Print</a>');
			TFORM($cEDIT_REVENUE, '?_a=ru_bah&_r='.$cNO_INV, $aACT, $cHELP_FILE);
				LABEL([4,4,4,6], '700', $cINVOICE);
				INPUT('text', [3,3,3,6], '900', 'EDIT_NO_TRM', $aREC_REV_HDR['REV_ID'], '', '', '', 0, 'disable', 'fix');
				LABEL([4,4,4,6], '700', $cTANGGAL);
?>		
		<input type="date" class="col-lg-2 col-sm-3 col-xs-6 form-label-900" name='EDIT_REV_DATE' data-format="dd/mm/yyyy" id="field-2" value="<?php echo date("Y-m-d", strtotime($aREC_REV_HDR['REV_DATE']))?>">
		<div class="clearfix"></div>

		<label class="col-sm-4 col-xs-6 form-label-700" for="field-1"><?php echo $cDUE_DATE?></label>
		<input type="date" class="col-lg-2 col-sm-3 col-xs-6 form-label-900" name='EDIT_DUE_DATE' data-format="dd/mm/yyyy" id="field-2" value="<?php echo date("Y-m-d", strtotime($aREC_REV_HDR['REV_DUE']))?>">
		<div class="clearfix"></div>

		<label class="col-sm-4 col-xs-6 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
<?php
			INPUT('text', [4,4,4,6], '900', 'EDIT_DESCRP', $aREC_REV_HDR['CUST_NAME'], '', '', '', 0, '', 'fix');
			TDIV();
			TABLE('example');
				THEAD([$cKETERANGAN, $cACCOUNT, $cNIL_TRN], '', [0,0,1], '*', [5,5,2]);
				echo '<tbody>';
				$dQUERY=OpenTable('SchRevDtl', "A.REV_HDR_ID='$cNO_INV' and A.APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
					$nTOTAL = 0;
					while($aREC_DTL_REVENUE=SYS_FETCH($dQUERY)) {
						echo '<tr>';
							echo "<td><span><a href=?_a=".md5('edit_detail_trans')."&_r=".$aREC_DTL_REVENUE['REC_ID'].">". $aREC_DTL_REVENUE['REV_DESC'].'</a></span></td>';
							echo "<td><span><a href=?_a=".md5('edit_detail_trans')."&_r=".$aREC_DTL_REVENUE['REC_ID'].">". $aREC_DTL_REVENUE['ACCT_NAME'].'</a></span></td>';
							echo '<td align="right">'.CVR($aREC_DTL_REVENUE['REV_VALUE']).'</td>';
						echo '</tr>';
						$nTOTAL += $aREC_DTL_REVENUE['REV_VALUE'];
					}
?>
				<tr>
					<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='NEW_DTL1' id="field-2"></td>
					<td><div class="form-group">
						<select name="NEW_ACCOUNT1" class="col-lg-12 col-sm-12 form-label-900 select2">
						<option></<option>
						<?php 
							$REV_DATA=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_DETAIL=SYS_FETCH($REV_DATA)){
								echo "<option value='$aREC_DETAIL[ACCOUNT_NO]'  >".DECODE($aREC_DETAIL['ACCT_NAME'])."</option>";
							}
						?>
						</select>
					</div></td>
					<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='NEW_AMOUNT1' id="field-3" data-mask="fdecimal" data-numeric-align="right"></td>
				</tr>
				<tr></tr>
				<tr>
					<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
					<td style="font-size: 24px;color: Brown;background-color: LightGray ;">Total</td>
					<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo CVR($nTOTAL)?></td>
				</tr>
				<td></td><td></td><td></td>
				<tr></tr>
<?php
					echo '</tbody>';
				eTABLE();
				TDIV();
				SAVE($can_UPDATE ? $cSAVE_DATA : '');
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");	
		END2WINDOW();
		break;

	case md5('edit_detail_trans'):
		$can_DELETE 	= TRUST($cUSERCODE, 'TR_SALES_3DEL');
		$cREC_ID = $_GET['_r'];
		$qQUERY=OpenTable('SchRevDtl', "A.REC_ID='$cREC_ID'");
		$aREC_DETAIL=SYS_FETCH($qQUERY);
		DEF_WINDOW($cHEADER);
		$aACT =[];
		if ($can_DELETE==1) 
			array_push($aACT, '<a href="?_a=upd_del_dtl&_r='.$cREC_ID. '" onClick="return confirm('. "'". $cMESSAG1. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>'.S_MSG('F304','Delete').'</a>');
		TFORM($cHEADER, '?_a=upd_upd_dtl&_r='.$cREC_ID, $aACT, $cHELP_FILE);
		TDIV();
?>
		<div class="form-group">
			<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cACCOUNT?></label>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<select name='UPD_UPD_ACCOUNT_NO' class="col-sm-6 form-label-900 select2" title="<?php echo S_MSG('NR1A','Account untuk detil penerimaan')?>">
					<?php 
						echo "<option value=' '  > </option>";
						$qACCOUNT=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_ACCOUNT=SYS_FETCH($qACCOUNT)){
							if($aREC_ACCOUNT['ACCOUNT_NO']==$aREC_DETAIL['REV_ACCOUNT']){
								echo "<option value='$aREC_DETAIL[REV_ACCOUNT]' selected='$aREC_DETAIL[REV_ACCOUNT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
							} else
							echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
						}
					?>
				</select>
			</div>
			
			<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cKETERANGAN?></label>
			<input type="text" class="col-sm-6 form-label-900" name='UPD_UPD_DESCRP' id="field-2" value="<?php echo DECODE($aREC_DETAIL['REV_DESC'])?>">
			<div class="clearfix"></div>

			<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNIL_TRN?></label>
			<input type="text" class="col-sm-2 form-label-900" name='UPD_UPD_VALUE' id="field-3" data-mask="fdecimal" value="<?php echo $aREC_DETAIL['REV_VALUE']?>">
			<div class="clearfix"></div>
		</div>
<?php
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case 'tam_bah':
		$cDATE	= $_POST['ADD_REV_DATE1'];
		$cCEK_DATE 	= $_POST['ADD_REV_DUE'];
		$cNO_INV	= $_POST['ADD_NO_INV'];
		$cSTUDENT	= $_POST['ADD_STUDENT'];
		if($cNO_INV==''){
			MSG_INFO(S_MSG('NJ49','Nomor Faktur masih kosong'));
			return;
		}
		if($_POST['ADD_REV_DATE1']==''){
            MSG_INFO(S_MSG('NJ4A','Tanggal Penjualan masih kosong'));
			return;
		}
		$cQUERY=OpenTable('SchRevHdr', "REV_ID='$cNO_INV' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($cQUERY)>0){
            MSG_INFO(S_MSG('NJ48','Nomor faktur penjualan sudah ada'));
			return;
		} else {
			RecCreate('SchRevHdr', ['REV_ID', 'REV_STUDENT', 'REV_DATE', 'NOTES', 'ENTRY', 'REC_ID', 'APP_CODE'], 
				[$cNO_INV, $cSTUDENT, $cDATE, ENCODE($_POST['ADD_DESCRP1']), $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		}

		for ($I=1; $I < 5; $I++) {
			$cIDX 	= (string)$I;
			$nVALUE	= str_replace(',', '', $_POST['ADD_AMOUNT_'.$cIDX]);
			$cACCOUNT = $_POST['ADD_DTL_ACCOUNT'.$cIDX];
			if($nVALUE>0){
				RecCreate('SchRevDtl', ['REV_HDR_ID', 'ACCOUNT', 'REV_DESC', 'NILAI', 'ENTRY', 'REC_ID', 'APP_CODE'], 
					[$cNO_INV, $cACCOUNT, ENCODE($_POST['ADD_DTL_DESCRP'.$cIDX]), $nVALUE, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
			}
		}
		APP_LOG_ADD($cHEADER, 'add '.$cNO_INV);
		header('location:sch_revenue.php');
		break;

	case 'ru_bah':
		$cNO_INV	= $_GET['_r'];
		$dTG_BAYAR 	= $_POST['EDIT_REV_DATE'];		// 'dd/mm/yyyy'
		$cDATE 		= substr($dTG_BAYAR,6,4). '-'. substr($dTG_BAYAR,3,2). '-'. substr($dTG_BAYAR,0,2);
		$dDUE_DATE 	= $_POST['UPD_TRM_DD'];		// 'dd/mm/yyyy'
		$cDUE_DATE = substr($dDUE_DATE,6,4). '-'. substr($dDUE_DATE,3,2). '-'. substr($dDUE_DATE,0,2);
		$cBANK		= $_POST['UPD_BANK'];
		$c_CEK		= $_POST['UPD_CEK'];
		RecUpdate('SchRevHdr', ['REV_DUE', 'REV_DATE', 'BANK'], [$cDUE_DATE, $cDATE, $cBANK], 
			"NO_TRM='$cNO_INV' and APP_CODE='$cAPP_CODE'");

		$nNEW_VALUE 	= str_replace(',', '', $_POST['NEW_AMOUNT1']);
		if($nNEW_VALUE>0)	{
			RecCreate('SchRevDtl', ['REV_HDR_ID', 'ACCOUNT', 'REV_DESC', 'REV_VALUE', 'ENTRY', 'REC_ID', 'APP_CODE'], 
			[$cNO_INV, $_POST['NEW_ACCOUNT1'], $_POST['NEW_DTL1'], $nNEW_VALUE, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		}
		APP_LOG_ADD($cHEADER, 'edit '.$cNO_INV);
		header('location:sch_revenue.php');
		break;

	case 'del_revenue':
		$cINVOICE=$_GET['_r'];
		$qQUERY=OpenTable('SchRevHdr', "APP_CODE='$cAPP_CODE' and REV_ID='$cINVOICE'");
		if($aINVOICE=SYS_FETCH($qQUERY)) {
			RecSoftDel($aINVOICE['REC_ID']);
			$dQUERY=OpenTable('SchRevDtl', "A.APP_CODE='$cAPP_CODE' and REV_HDR_ID='$cINVOICE'");
			while($aREC_DTL_REVENUE=SYS_FETCH($dQUERY)) {
				RecSoftDel($aREC_DTL_REVENUE['REC_ID']);
			}
		}
		header('location:sch_revenue.php');
		break;
	
	case 'upd_del_dtl':
		RecSoftDel($_GET['_r']);
		header('location:sch_revenue.php');
		break;

	case 'upd_upd_dtl':
		$cREC_NO = $_GET['_r'];
		$qUPD_DTL_QUERY = OpenTable('SchRevDtl', "REC_ID='$cREC_NO'");
		$aREC_UPD_DETAIL=SYS_FETCH($qUPD_DTL_QUERY);
		$nDEBET = str_replace(',', '', $_POST['UPD_UPD_VALUE']);
		$cACCOUNT=$_POST['UPD_UPD_ACCOUNT_NO'];
		print_r2($cACCOUNT);
		$cKET = ENCODE($_POST['UPD_UPD_DESCRP']);
		if($cACCOUNT==''){
			MSG_INFO(S_MSG('NR45','Kode account penerimaan masih kosong'));
			return;
		}
		if($nDEBET==0){
			MSG_INFO(S_MSG('NJ4C','Nilai pendapatan masih kosong'));
		}
		RecUpdate('TrReceiptDtl', ['ACCOUNT', 'KET', 'NILAI'], [$cACCOUNT, $cKET, $nDEBET], "REC_ID='$cREC_NO'");
		header("location:sch_revenue.php");
		break;
	case md5('rev_print'):
		$cREC_CODE = $_GET['_c'];
		if($cREC_CODE=='') {
			MSG_INFO(S_MSG('NJ49','Nomor Invoice masih kosong'));
			return;
		}
		$cFORM=S_PARA('FORMAT_RECEIPT', 'RECEIPT');
		if(!$cFORM) {
			MSG_INFO('Format Kwitansi tidak ada');
			return;
		}
		$qQUERY = OpenTable('TrReceiptHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and REC_ID='$cREC_CODE'");
		if(SYS_ROWS($qQUERY)==0){
			MSG_INFO('Nomor Invoice tidak ada');
			return;
		}
		$aPR_HDR=SYS_FETCH($qQUERY);
		APP_LOG_ADD($cHEADER, 'edit '.$aPR_HDR['REV_ID']);

		require('vendor/fpdf/fpdf.php');
		$qTB_BILL=OpenTable('TbBillPrintHdr', "PRNTR_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qTB_BILL)==0)  return;
		$aREC_TB_BILL=SYS_FETCH($qTB_BILL);
		$cPAPER = 'A4';
		if($aREC_TB_BILL['PAPER_SIZE']>'')   $cPAPER = $aREC_TB_BILL['PAPER_SIZE'];
		$cORIEN = 'P';
		if($aREC_TB_BILL['ORIENTATION']>'')   $cORIEN = $aREC_TB_BILL['ORIENTATION'];
		$pdf=new FPDF($cORIEN, 'mm', $cPAPER);	
		$pdf->AddPage();

		$pdf->AddFont('Verdana','','verdana.php');
		$pdf->AddFont('Verdana','B','verdanab.php');

		if(GET_FORMAT($cFORM, 'LOGO_CETAK')=='1') {
			$cLOGO_FILE = 'data/images/'.$cAPP_CODE.'_KOP.jpg';
			$nLOGO_LEFT = intval(GET_FORMAT($cFORM, 'LOGO_LEFT'));
			$nLOGO_TOP = intval(GET_FORMAT($cFORM, 'LOGO_TOP'));
			if($nLOGO_LEFT>0 && $nLOGO_TOP>0 && file_exists($cLOGO_FILE))
				$pdf->Image($cLOGO_FILE, $nLOGO_LEFT, $nLOGO_TOP, -300);
		}
		$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
		if(GET_FORMAT($cFORM, 'COMP_CETAK')=='1') {
			$cFONT_CODE = GET_FORMAT($cFORM, 'COMP_FONT_CODE');
			$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
			if(SYS_ROWS($qFONT)>0) {
				$aFONT = SYS_FETCH($qFONT);
				$cFONT_NAME = $aFONT['NAME'];
				if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
				if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
				if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
				$nFONT_SIZE = $aFONT['SIZE'];
			}
			$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
			$pdf->Text(GET_FORMAT($cFORM, 'COMP_LEFT'), GET_FORMAT($cFORM, 'COMP_TOP'), S_PARA('CO1',''));
		}
		$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
		if(GET_FORMAT($cFORM, 'ADD1_CETAK')=='1') {
			$cFONT_CODE = GET_FORMAT($cFORM, 'ADD1_FONT_CODE');
			$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
			if(SYS_ROWS($qFONT)>0) {
				$aFONT = SYS_FETCH($qFONT);
				$cFONT_NAME = $aFONT['NAME'];
				if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
				if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
				if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
				$nFONT_SIZE = $aFONT['SIZE'];
			}
			$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
			$pdf->Text(GET_FORMAT($cFORM, 'ADD1_LEFT'), GET_FORMAT($cFORM, 'ADD1_TOP'), S_PARA('CO2',''));
		}
		if(GET_FORMAT($cFORM, 'N_NOTA_CTK')=='1') {
			$cFONT_CODE = GET_FORMAT($cFORM, 'NOTA_FONT_CODE');
			$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
			if(SYS_ROWS($qFONT)>0) {
				$aFONT = SYS_FETCH($qFONT);
				$cFONT_NAME = $aFONT['NAME'];
				if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
				if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
				if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
				$nFONT_SIZE = $aFONT['SIZE'];
			}
			$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
			$pdf->Text(GET_FORMAT($cFORM, 'NOTA_LEFT'), GET_FORMAT($cFORM, 'NOTA_TOP'), $aPR_HDR['NO_TRM']);
		}
		if(GET_FORMAT($cFORM, 'TGGL_CTK')=='1') {
			$cFONT_CODE = GET_FORMAT($cFORM, 'TGGL_FONT_CODE');
			$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
			if(SYS_ROWS($qFONT)>0) {
				$aFONT = SYS_FETCH($qFONT);
				$cFONT_NAME = $aFONT['NAME'];
				if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
				if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
				if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
				$nFONT_SIZE = $aFONT['SIZE'];
			}
			$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
			$pdf->Text(GET_FORMAT($cFORM, 'TGGL_LEFT'), GET_FORMAT($cFORM, 'TGGL_TOP'), $aPR_HDR['REV_DATE']);

		}
		if(GET_FORMAT($cFORM, 'JAM_CTK')=='1') {
			date_default_timezone_set('Asia/Jakarta');
			$cFONT_CODE = GET_FORMAT($cFORM, 'JAM_FONT_CODE');
			$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
			if(SYS_ROWS($qFONT)>0) {
				$aFONT = SYS_FETCH($qFONT);
				$cFONT_NAME = $aFONT['NAME'];
				if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
				if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
				if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
				$nFONT_SIZE = $aFONT['SIZE'];
			}
			$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
			$pdf->Text(GET_FORMAT($cFORM, 'JAM_LEFT'), GET_FORMAT($cFORM, 'JAM_TOP'), date('H:i', time()));

		}

		for($I = 1; $I<=12; $I++):
			$J=(string)$I;
			if(GET_FORMAT($cFORM, 'KONST'.$J.'_STAT')=='1') {
				$cFONT_CODE = GET_FORMAT($cFORM, 'KONST'.$J.'_FONT_CODE');
				$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
				if(SYS_ROWS($qFONT)>0) {
					$aFONT = SYS_FETCH($qFONT);
					$cFONT_NAME = $aFONT['NAME'];
					if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
					if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
					if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
					$nFONT_SIZE = $aFONT['SIZE'];
				}
				$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
				$pdf->Text(GET_FORMAT($cFORM, 'KONST'.$J.'_COL'), GET_FORMAT($cFORM, 'KONST'.$J.'_ROW'), GET_FORMAT($cFORM, 'KONST'.$J.'_CONTENT'));

			}
		endfor;
		for($I = 1; $I<=9; $I++):
			$J=(string)$I;
			if(GET_FORMAT($cFORM, 'LINE'.$J.'_CTK')=='1') {
				$pdf->SetLineWidth(intval(GET_FORMAT($cFORM, 'LINE'.$J.'_POINT')/10));
				$pdf->Line(GET_FORMAT($cFORM, 'LINE'.$J.'_LEFT_COL'), GET_FORMAT($cFORM, 'LINE'.$J.'_LEFT_ROW'), GET_FORMAT($cFORM, 'LINE'.$J.'_RIGHT_COL'), GET_FORMAT($cFORM, 'LINE'.$J.'_RIGHT_ROW'));
			}
		endfor;

		$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
		$cFONT_CODE = GET_FORMAT($cFORM, 'DETAIL_DTA_FONT_CODE');
		$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		if(SYS_ROWS($qFONT)>0) {
			$aFONT = SYS_FETCH($qFONT);
			$cFONT_NAME = $aFONT['NAME'];
			if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
			if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
			if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
			$nFONT_SIZE = $aFONT['SIZE'];
		}

		$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
		$qBILL_COL=OpenTable('TbBillCol', "BILL_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
		$I=0;
		$cSTART_ROW = GET_FORMAT($cFORM, 'DETAIL_START_ROW');
		$nSTART_ROW = intval($cSTART_ROW);
		while($aDTL_COL=SYS_FETCH($qBILL_COL)) {
			$I++;
			$J=(string)$I;
			$cSTTS = 'DETAIL'.$J.'_HEAD_STATUS';
			if(GET_FORMAT($cFORM, $cSTTS)=='1') {
				$cDTL_COL =$aDTL_COL['COL_NAME'];
				$nCOL = intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
				$cSTTS = 'DETAIL'.$J.'_HEAD_STATUS';
				$pdf->Text($nCOL, $nSTART_ROW, GET_FORMAT($cFORM, 'DETAIL'.$J.'_HEAD_LABEL'));
			}
		}

		$nSTART_ROW += 3;
		$nREC_DTL = 0;
		$nTOTAL = 0;
		$qQ_HDR = OpenTable('TrReceiptHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and REC_ID='$cREC_CODE'");
		$aREC_HDR = SYS_FETCH($qQ_HDR);
		if(GET_FORMAT($cFORM, 'KET_CTK')=='1') {
			$cFONT_CODE = GET_FORMAT($cFORM, 'KET_FONT_CODE');
			$qFONT= OpenTable('TbFont', "KEY_ID='$cFONT_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
			if(SYS_ROWS($qFONT)>0) {
				$aFONT = SYS_FETCH($qFONT);
				$cFONT_NAME = $aFONT['NAME'];
				if($aFONT['BOLD']==1)	$cFONT_STYLE.='B';
				if($aFONT['ITALIC']==1)	$cFONT_STYLE.='I';
				if($aFONT['UNDERLINE']==1)	$cFONT_STYLE.='U';
				$nFONT_SIZE = $aFONT['SIZE'];
			}
			$pdf->SetFont($cFONT_NAME, $cFONT_STYLE, $nFONT_SIZE);
			$pdf->Text(GET_FORMAT($cFORM, 'KET_LEFT'), GET_FORMAT($cFORM, 'KET_TOP'), $aREC_HDR['DESCRP']);
		}
		$cQ_DTL=OpenTable('TrReceiptDtl', "A.NO_TRM='$aREC_HDR[NO_TRM]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		while($aDTL_REC=SYS_FETCH($cQ_DTL)) {
			$I=0;
			$nTOTAL += $aDTL_REC['NILAI'];
			$nSTART_ROW += 7;
			$qBILL_COL=OpenTable('TbBillCol', "BILL_CODE='RECEIPT' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'SEQUENT');
			$nREC_DTL = SYS_ROWS($qBILL_COL);
			while($aDTL_FLD=SYS_FETCH($qBILL_COL)) {
				$I++;
				$J=(string)$I;
				$cFIELD = $aDTL_FLD['COL_CODE'];
				$nCOL = intval(GET_FORMAT($cFORM, 'DETAIL'.$J.'_DATA_COL'));
				if(GET_FORMAT($cFORM, 'DETAIL'.$J.'_HEAD_STATUS')=='1') {
					switch($cFIELD){
						case 'AC_CODE':
							$pdf->Text($nCOL, $nSTART_ROW, $aDTL_REC['ACCOUNT']);
							break;
						case 'AC_NAME':
							$pdf->Text($nCOL, $nSTART_ROW, $aDTL_REC['ACCT_NAME']);
							break;
						case 'KET':
							$pdf->Text($nCOL, $nSTART_ROW, $aDTL_REC['KET']);
							break;
						case 'NILAI':
							$pdf->Text($nCOL, $nSTART_ROW, CVR($aDTL_REC['NILAI']));
							break;
					}
				}
			}
		}
		if(GET_FORMAT($cFORM, 'LINE_AFTER_DETAIL')=='1') {
			$nCOL = intval(GET_FORMAT($cFORM, 'LINE_AFTER_DTL_COL'));
			$nLEN = intval(GET_FORMAT($cFORM, 'LINE_AFTER_DTL_LENGTH'));
			$pdf->SetLineWidth(intval(GET_FORMAT($cFORM, 'LINE_AFTER_DTL_POINT')/10));
			$pdf->Line($nCOL, $nSTART_ROW + 5, $nCOL+$nLEN,  $nSTART_ROW + 5);
		}
		for($I = 1; $I<=$nREC_DTL; $I++):			//	total
			$J=(string)$I;
			if(GET_FORMAT($cFORM, 'TOTAL'.$J.'_STATUS')=='1') {
				$cDATA_COL = GET_FORMAT($cFORM, 'TOTAL'.$J.'_DATA_COL');
				if($cDATA_COL>'0')
					$pdf->Text($cDATA_COL, $nSTART_ROW+10, CVR($nTOTAL));
			}
		endfor;

		if(GET_FORMAT($cFORM, 'TOTAL_SAYS_STATUS')=='1') {
			$nCOL = intval(GET_FORMAT($cFORM, 'TOTAL_SAYS_COL'));
			$nROW = intval(GET_FORMAT($cFORM, 'TOTAL_SAYS_ROW'));
			$pdf->Text($nCOL, $nROW, SAYS($nTOTAL));
		}
	
		for($I = 1; $I<=9; $I++):			//	box
			$J=(string)$I;
			if(GET_FORMAT($cFORM, 'BOX'.$J.'_CTK')=='1') {
				$nLEFT = GET_FORMAT($cFORM, 'BOX'.$J.'_LEFT_COL');
				$nROW  = GET_FORMAT($cFORM, 'BOX'.$J.'_LEFT_ROW');
				$nRIGHT= GET_FORMAT($cFORM, 'BOX'.$J.'_RIGHT_COL');
				$nBOTTOM= GET_FORMAT($cFORM, 'BOX'.$J.'_RIGHT_ROW');
				$nWIDTH = $nRIGHT - $nLEFT;
				$nHEIGHT= $nBOTTOM - $nROW;
				$nBOX_LINE = 0.1;
				if(GET_FORMAT($cFORM, 'BOX'.$J.'_POINT')>0) $nBOX_LINE = intval(GET_FORMAT($cFORM, 'BOX'.$J.'_POINT')/10);
				$pdf->SetLineWidth($nBOX_LINE);
				$pdf->Rect($nLEFT, $nROW, $nWIDTH, $nHEIGHT);
			}
		endfor;

		$pdf->Output();
		break;

}
?>
