<?php
//	tr_sales.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 	= $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Transaksi - Penjualan.pdf';

    $can_CREATE = TRUST($cUSERCODE, 'TR_SALES_1ADD');

	$sPERIOD1=$_SESSION['sCURRENT_PERIOD'];
	if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];

	$cHEADER = S_MSG('NJ01','Pengiriman');
	APP_LOG_ADD($cHEADER, 'view');
	$cACTION = (isset($_GET['_a']) ? $_GET['_a'] : '');
  
	$nMANUAL= (S_PARA('SALES_NUM_MANUAL', '')=='1' ? 1 : 0);
	$nMULTI_UNIT	= (S_PARA('INVENTORY_UNIT','4')=='4' ? 0 : 1);

	$cADD_SALES 	= S_MSG('NJ06','Add');
	$cADD_DTL_RCP 	= S_MSG('NJ24','Add dtl');
	$cEDIT_DTL_JRN 	= S_MSG('NJ28','Edit dtl');

	$cINVOICE		= S_MSG('NJ02','No. Kirim');
	$cTANGGAL 		= S_MSG('NJ03','Tanggal');
	$cNIL_TRN		= S_MSG('NP72','Jumlah');
	$cKD_ACCOUNT 	= S_MSG('NR07','Account');
	$cPRODUCT		= S_MSG('F052','Produk');
	$cQUANTITY		= S_MSG('NP59','Qty');
	$cHRG_BRG		= S_MSG('NL15','Hrg');
	$cKETERANGAN 	= S_MSG('NR04','Ket');
	$cCUSTOMER 	    = S_MSG('NJ04','Cust');
	$cMESSAG1		= S_MSG('F021','Konf ?');
	$cDUE_DATE		= S_MSG('NR11','Jt. Tempo');
	$cDISCOUNT		= S_MSG('NJ15','Diskon');
	$cT_P_R			= S_MSG('TP57','T P R');
	$cP_P_N			= S_MSG('TP46','Ppn');

	$cSAVE_DATA=S_MSG('F301','Save');
	$nTRADE = IS_TRADING($cAPP_CODE);
	$nTPR	= IS_TPR($cAPP_CODE);
	$nNON_PROFIT=(IS_NON_PROFIT($cAPP_CODE)>0 ? 1 : 0);
	if($nNON_PROFIT==1)	$cHELP_FILE = 'Doc/Transaksi - Pendapatan.pdf';
	$nOUTSOURCING=(IS_OUTSOURCING($cAPP_CODE)>0 ? 1 : 0);
	if($nOUTSOURCING==1)	$cHELP_FILE = 'Doc/Transaksi - Pengiriman.pdf';

switch($cACTION){
	default:
		DEF_WINDOW($cHEADER, '', 'prd');
			$aACT = ($can_CREATE ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. $cADD_SALES.'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TABLE('example');
				$aHEAD = [$cINVOICE, $cTANGGAL, $cDUE_DATE, $cCUSTOMER, $cNIL_TRN];
				$aALIGN= [0,0,0,0,1];
				if($nTRADE) {
					array_push($aHEAD, $cDISCOUNT);	array_push($aALIGN, 1);
				}
				if($nTPR)	{
					array_push($aHEAD, $cT_P_R);	array_push($aALIGN, 1);
				}
				array_push($aHEAD, $cKETERANGAN);	array_push($aALIGN, 0);
				echo THEAD($aHEAD, '', $aALIGN);
					echo '<tbody>';
						$nTOTAL=$nDISCOUNT=$tTPR=0;
						$qQUERY=OpenTable('TrSalesCust', "left(A.TGL_JUAL,7)='".substr($sPERIOD1,0,7)."' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", '', "A.REC_ID desc");
						while($aREC_SLS_HDR=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								$cICON = 'fa fa-money';
								echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
								echo "<td><span><a href='?_a=".md5('upd4t3')."&_r=".md5($aREC_SLS_HDR['REC_ID'])."'>". $aREC_SLS_HDR['NOTA']."</a></span></td>";
								echo '<td>'.date("d-M-Y", strtotime($aREC_SLS_HDR['TGL_JUAL'])).'</td>';
								echo '<td>'.date("d-M-Y", strtotime($aREC_SLS_HDR['TGL_BAYAR'])).'</td>';
								echo '<td>'.$aREC_SLS_HDR['CUST_NAME'].'</td>';
								$nAMOUNT 	= $aREC_SLS_HDR['NILAI'];
								echo '<td align="right">'.CVR($nAMOUNT).'</td>';
								$nTOTAL += $nAMOUNT;
								if($nTRADE)	{
									echo '<td align="right">'.CVR($aREC_SLS_HDR['DISCOUNT']).'</td>';
									$nDISCOUNT+=$aREC_SLS_HDR['DISCOUNT'];
								}
								if($nTPR)	{
									echo '<td align="right">'.CVR($aREC_SLS_HDR['TPR']).'</td>';
									$tTPR+=$aREC_SLS_HDR['TPR'];
								}
								echo '<td>'.$aREC_SLS_HDR['DESCRIPTION'].'</td>';
							echo '</tr>';
						}
					echo '</tbody>';
					$aTTL = ['Total', '', '', '', CVR($nTOTAL)];
					$ALGN = [0,0,0,0,1];
					if($nTRADE)	{
						array_push($aTTL, CVR($nDISCOUNT));
						array_push($ALGN, 1);
					}
					if($nTPR)	{
						array_push($aTTL, CVR($tTPR));
						array_push($ALGN, 1);
					}
					array_push($aTTL, '');
					array_push($ALGN, 0);
						TTOTAL($aTTL, $ALGN);
				eTABLE();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('cr34t3'):
        $cHEADER = S_MSG('NJ06','Tambah Pendapatan');
		$can_PRICE = TRUST($cUSERCODE, 'TR_SALES_PRICE');
        $cLAST_NOM	= '';
        if($nMANUAL==0) {
            $cPICT_INV 	= S_PARA('PICT_INV', '999999');
            $qQ_LAST 	= OpenTable('TrSalesHdr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', "NOTA desc limit 1");
            if(SYS_ROWS($qQ_LAST)==0) {
                $nLAST_NOM	= 1;
            } else {
                $aREC_SLS_HDR= SYS_FETCH($qQ_LAST);
                $cLAST_NOM	= $aREC_SLS_HDR['NOTA'];
                $nLAST_NOM	= intval($cLAST_NOM)+1;
            }
            $cLAST_NOM	= str_pad((string)$nLAST_NOM, strlen($cPICT_INV), '0', STR_PAD_LEFT);
        }
        $qCUSTOMER=OpenTable('CustAll', "A.APP_CODE='$cAPP_CODE' and A.DELETOR=''", '', 'A.CUST_NAME');
		$aCUSTOMER = ALL_FETCH($qCUSTOMER);
		$qGRUP=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
		$nCUST_GROUP=SYS_ROWS($qGRUP);
        DEF_WINDOW($cHEADER);
			TFORM($cHEADER, '?_a=tam_bah','', $cHELP_FILE);
				LABEL([4,4,4,6], '700', $cINVOICE);
				INPUT('text', [3,3,3,6], '900', 'ADD_NO_INV', $cLAST_NOM, 'autofocus', '', '', 0, ($nMANUAL==1 ? '' : ''), 'fix');
				LABEL([4,4,4,6], '700', $cTANGGAL);
				INP_DATE([2,2,3,6], '900', 'ADD_TGL_JUAL', date('d/m/Y'), '', '', '', 'fix');
				LABEL([4,4,4,6], '700', $cDUE_DATE);
				INP_DATE([2,2,3,6], '900', 'ADD_TGL_BAYAR', date('d/m/Y'), '', '', '', 'fix');
				LABEL([4,4,4,6], '700', $cCUSTOMER);
				TDIV(6,6,6,12);
					SELECT([4,4,4,6], 'ADD_CUST', '', '', 'select2');
						echo '<option></option>';
							$I=0;
							$nSIZE_ARRAY = count($aCUSTOMER);
							if($nCUST_GROUP>0) {
								while($aGRUP=SYS_FETCH($qGRUP)){
									echo '<optgroup label="'.$aGRUP['NAMA_GRP'].'">';
									while($I<$nSIZE_ARRAY-1) {
										if ($aCUSTOMER[$I]['CUST_GROUP']==$aGRUP['KODE_GRP']) {
											$cSELECT = $aCUSTOMER[$I]['CUST_NAME']."  /  ".$aCUSTOMER[$I]['CUST_CODE']."  /  ".$aCUSTOMER[$I]['CUST_ADDRESS']."  /  ".$aCUSTOMER[$I]['CUST_CITY'];
											$cVALUE = $aCUSTOMER[$I]['CUST_CODE'];
											echo '<option value="'.$cVALUE.'">'.$cSELECT.'</option>';
										}
										$I++;
									}
								}
							} else {
								while($I<$nSIZE_ARRAY-1) {
									$cSELECT = $aCUSTOMER[$I]['CUST_NAME']."  /  ".$aCUSTOMER[$I]['CUST_CODE']."  /  ".$aCUSTOMER[$I]['CUST_ADDRESS']."  /  ".$aCUSTOMER[$I]['CUST_CITY'];
									$cVALUE = $aCUSTOMER[$I]['CUST_CODE'];
									echo '<option value="'.$cVALUE.'">'.$cSELECT.'</option>';
									$I++;
								}
							}
						echo '</optgroup>';
					echo '</select>';
				eTDIV();
				CLEAR_FIX();
				LABEL([4,4,4,6], '700', $cKETERANGAN);
				INPUT('text', [6,6,6,6], '900', 'ADD_DESCRP1', '', '', '', '', 0, '', 'fix');
				TABLE('example');
					$aHDR = [$cPRODUCT];
					$aALIGN=[0];
					$aWIDTH=[6];
					if($nTRADE || $nOUTSOURCING) {
						array_push($aHDR, $cQUANTITY);
						array_push($aHDR, $cHRG_BRG);
						array_push($aALIGN, 1);
						array_push($aALIGN, 1);
						array_push($aWIDTH, 2);
						array_push($aWIDTH, 2);
					} else {
						array_push($aHDR, $cKETERANGAN);
						array_push($aALIGN, 0);
						array_push($aWIDTH, 4);
					}
					array_push($aHDR, $cNIL_TRN);
					array_push($aALIGN, 1);
					array_push($aWIDTH, 2);
					THEAD($aHDR, '', $aALIGN, '*', $aWIDTH);
					echo '<tbody>';
						for ($I=1; $I < 5; $I++) {
							$cIDX 	= (string)$I;
							echo '<tr>
								<td><div class="form-group">
									<select onchange="val_tr_sales('.$cIDX.')" id="SELECT_INVENT'.$cIDX.'" name="ADD_PRODUCT'.$cIDX.'" class="col-lg-12 col-sm-12 col-xs-12 form-label-900 select2"> onBlur="fillPrice()"
										<option> </option>';
										$a_DATA=OpenTable('Invent', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
										while($aREC_DETAIL=SYS_FETCH($a_DATA)){
											echo "<option value='".$aREC_DETAIL['KODE_BRG']."' data-price='".CVR($aREC_DETAIL['HARGA_JUAL'])."' >"
											.DECODE($aREC_DETAIL['NAMA_BRG']).' - Rp.'. CVR($aREC_DETAIL['HARGA_JUAL']).
											"</option>";
										}
									echo '</select>
								</div></td>';
								if($nTRADE || $nOUTSOURCING) {
									echo '<td><input id="quantity-'.$cIDX.'" onchange="perkalian_tr_sales('.$cIDX.')"  type="text" min="0" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name="ADD_QUANTITY'.$cIDX.'" style="text-align: right;"></td>';
									echo '<td><input id="harga-'.$cIDX.'" type="text" readonly="readonly" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name="ADD_PRICE'.$cIDX.'"  style="text-align: right;"></td>';
								} else {
									echo '<td><input id="deskripsi-'.$cIDX.'" type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name="ADD_DTL_DESCRP'.$cIDX.'" ></td>';
								}
								echo '  <td>
									<input id="subtotal-'.$cIDX.'" type="hidden" readonly="readonly" 
										class="col-lg-12 col-sm-12 col-xs-12 form-label-900" 
										name="ADD_AMOUNT_'.$cIDX.'" 
										style="text-align: right;" value="" />
									<input id="fakesubtotal-'.$cIDX.'" type="text" readonly="readonly" 
										class="col-lg-12 col-sm-12 col-xs-12 form-label-900" 
										style="text-align: right;" />
								</td>
							</tr>';
						}
					echo '</tbody>';
				eTABLE();
			?>

<script type="application/javascript">
    // JQUERY FUNCTION XYZFR
function val_tr_sales(n) {
    // const select1 = document.getElementById("SELECT_INVENT1");
    // price = select1.dataset.price;
    // let price = select1.getAttribute("price");
    // let price = $("#SELECT_INVENT"+n).select2().find(":selected").data("price");
    let price = $("#SELECT_INVENT"+n).select2().find(":selected").data("price");
	price = price.replace('.', '');
    console.log(price);
    console.log(parseFloat(price).toLocaleString("id-ID"));
    $("#harga-"+n).val(parseFloat(price).toLocaleString("id-ID"));
    $("#quantity-"+n).val(0);
    $("#subtotal-"+n).val(0);
    $("#fakesubtotal-"+n).val(0);
}

function perkalian_tr_sales(n) {
    // const select1 = document.getElementById("SELECT_INVENT1");
    // price = select1.dataset.price;
    // let price = select1.getAttribute("price");
    let price = $("#SELECT_INVENT"+n).select2().find(":selected").data("price");
	price = price.replace('.', '');
    let quantity = $("#quantity-"+n).val();
    let subtotal = price*quantity;
    console.log(subtotal);
    $("#subtotal-"+n).val(subtotal);
    $("#fakesubtotal-"+n).val(parseFloat(subtotal).toLocaleString("id-ID"));
}
</script>

<?php
				SAVE($cSAVE_DATA);
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");	
		END2WINDOW();
		break;

	case md5('upd4t3'):
        $cEDIT_REVENUE 	= S_MSG('NJ28','Edit');
		$can_UPDATE 	= TRUST($cUSERCODE, 'TR_SALES_2UPD');
		$can_DELETE 	= TRUST($cUSERCODE, 'TR_SALES_3DEL');
		$can_PRINT 		= TRUST($cUSERCODE, 'TR_SALES_4PRT');
		$xREC_ID 		= $_GET['_r'];

		$qQUERY=OpenTable('TrSalesCust', "md5(A.REC_ID)='$xREC_ID'");
		if($aREC_SLS_HDR=SYS_FETCH($qQUERY)){
			$cNO_INV 		= $aREC_SLS_HDR['NOTA'];
			$cREC_ID 		= $aREC_SLS_HDR['REC_ID'];
		} else return;
		DEF_WINDOW($cEDIT_REVENUE);
			$aACT=[];
			if ($can_DELETE==1) 
				array_push($aACT, '<a href="?_a='.md5('DEL_DT'). '&_r='.$aREC_SLS_HDR['REC_ID']. '" onClick="return confirm('. "'". $cMESSAG1. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>');
			if ($can_PRINT==1)
				array_push($aACT, '<a href="?_a='. md5('TRN_PRINT'). '&_c='.$aREC_SLS_HDR['REC_ID'].'" onClick="return confirm('. $cMESSAG1.')" title="print this sales"><i class="glyphicon glyphicon-print"></i>Print</a>');
			TFORM($cEDIT_REVENUE, '?_a=ru_bah&_r='.$cNO_INV, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cINVOICE);
					INPUT('text', [3,3,3,6], '900', 'EDIT_NOTA', $aREC_SLS_HDR['NOTA'], '', '', '', 0, 'disable', 'fix');
					LABEL([4,4,4,6], '700', $cTANGGAL);
					INP_DATE([2,2,3,6], '900', 'EDIT_TGL_JUAL', date("d/m/Y", strtotime($aREC_SLS_HDR['TGL_JUAL'])), '', '', '', 'fix');
					LABEL([4,4,4,6], '700', $cDUE_DATE);
					INP_DATE([2,2,3,6], '900', 'EDIT_DUE_DATE', date("d/m/Y", strtotime($aREC_SLS_HDR['TGL_BAYAR'])), '', '', '', 'fix');
					LABEL([4,4,4,6], '700', $cCUSTOMER);
					SELECT([4,4,4,6], 'UPD_CUST', '', '', 'select2');
					LABEL([4,4,4,6], '700', $cKETERANGAN);
					INPUT('text', [6,6,6,6], '900', 'EDIT_DESCRP', $aREC_SLS_HDR['DESCRIPTION'], '', '', '', 0, '', 'fix');
					TABLE('example');
						$aHEAD=[$cPRODUCT, $cQUANTITY, $cHRG_BRG, $cNIL_TRN];	$aALGN=[0,1,1,1];	$aSIZE=[6,2,2,2];
						THEAD($aHEAD, '', $aALGN, '*', $aSIZE);
						echo '<tbody>';
							$qSALES=OpenTable('TrSalesDtlHdr', "A.NOTA='$cNO_INV' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
							$nTOTAL = 0;
							while($aSALES=SYS_FETCH($qSALES)) {
								$nQTY = $aSALES['QUANTITY'];
								echo '<tr>';
									if($nTRADE) {
										echo "<td><span><a href=?_a=".md5('edit_detail_trans')."&_r=".$aSALES['REC_ID'].">". $aSALES['NAMA_BRG'].'</a></span></td>';
										echo "<td><span><a href=?_a=".md5('edit_detail_trans')."&_r=".$aSALES['REC_ID'].">". $nQTY.'</a></span></td>';
									}else{

									}
									echo '<td align="right">'.CVR($aSALES['HARGA']).'</td>';
								echo '</tr>';
								$nTOTAL += $aSALES['HARGA'];
							}
							echo '<tr>';
								echo '<td>';
									SELECT([12,12,12,12], 'NEW_INVENT1', '', '', 'select2');
										echo '<option></<option>';
										$REV_DATA=OpenTable('Invent', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
										while($aREC_DETAIL=SYS_FETCH($REV_DATA)){
											echo "<option value='$aREC_DETAIL[KODE_BRG]'  >$aREC_DETAIL[NAMA_BRG]</option>";
										}
									echo '</select>';
								echo '</td>';
								if($nTRADE || $nOUTSOURCING) {
									INPUT('text', [12,12,12,12], '900', 'ADD_QUANTITY1', '', '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
									INPUT('number', [12,12,12,12], '900', 'ADD_PRICE1', 0, '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
									INPUT('number', [12,12,12,12], '900', 'ADD_JUMLAH1', 0, '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
								}
							echo '</tr>';
							TTOTAL(['', 'Total', CVR($nTOTAL)], [0,0,1]);
						echo '</tbody>';
					eTABLE();
					SAVE($can_UPDATE==1 ? $cSAVE_DATA : '');
				TDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('edit_detail_trans'):
		$cREC_ID = $_GET['_r'];
		$qQUERY=OpenTable('TrSalesDtlHdr', "A.REC_ID='$cREC_ID'");
		$aREC_DETAIL=SYS_FETCH($qQUERY);
		DEF_WINDOW($cHEADER);
			$aACT=[];
			if ($can_UPDATE==1) 
				array_push($aACT, '<a href="?_a=upd_del_dtl&_r='.$cREC_ID. '" onClick="return confirm('. "'". $cMESSAG1. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>'.S_MSG('F304','Delete').'</a>');
			TFORM($cHEADER, '?_a=upd_upd_dtl&_r='.$cREC_ID, $aACT, $cHELP_FILE);
				LABEL([4,4,4,6], '700', $cPRODUCT);
				SELECT([6,6,6,6], 'NEW_INVENT1', '', '', 'select2');
?>
					<select name='UPD_UPD_INVENT' class="col-sm-6 form-label-900 select2" title="<?php echo S_MSG('NR1A','Account untuk detil penerimaan')?>">
						<?php 
							echo "<option value=' '  > </option>";
							$qINVENT=OpenTable('Invent', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_INVENT=SYS_FETCH($qINVENT)){
								if($aREC_INVENT['KODE_BRG']==$aREC_DETAIL['KODE_BRG']){
									echo "<option value='$aREC_DETAIL[KODE_BRG]' selected='$aREC_DETAIL[KODE_BRG]' >$aREC_INVENT[NAMA_BRG]</option>";
								} else
								echo "<option value='$aREC_INVENT[KODE_BRG]'  >$aREC_INVENT[NAMA_BRG]</option>";
							}
					echo '</select>';
					if($nTRADE) {
					}else{ 
						LABEL([4,4,4,6], '700', $cKETERANGAN);
						INPUT('text', [6,6,6,6], '900', 'UPD_UPD_DESCRP', $aREC_DETAIL['KET'], '', '', '', 0, '', 'fix');
					}
					LABEL([4,4,4,6], '700', $cNIL_TRN);
					INPUT('number', [3,3,3,6], '900', 'UPD_UPD_VALUE', $aREC_DETAIL['HARGA'], '', '', '', 0, '', 'fix');
				SAVE($cSAVE_DATA);
			eTFORM();
		END2WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case 'tam_bah':
		$dTG_JUAL	= DMY_YMD($_POST['ADD_TGL_JUAL']);		// 'yyyy-mm-dd'
		$dDUE_DATE 	= DMY_YMD($_POST['ADD_TGL_BAYAR']);		// 'yyyy-mm-dd'
		$cNO_INV	= $_POST['ADD_NO_INV'];
		if($cNO_INV==''){
			MSG_INFO(S_MSG('NJ49','Nomor Faktur masih kosong'));
			return;
		}
		if($_POST['ADD_TGL_JUAL']==''){
            MSG_INFO(S_MSG('NJ4A','Tanggal Penjualan masih kosong'));
			return;
		}
		if($_POST['ADD_CUST']==''){
            MSG_INFO(S_MSG('F040','Kode Pelanggan tidak boleh kosong'));
			return;
		}
		
		$cQUERY=OpenTable('TrSalesHdr', "NOTA='$cNO_INV' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($cQUERY)>0){
            MSG_INFO(S_MSG('NJ48','Nomor faktur penjualan sudah ada'));
			return;
		} else {
			RecCreate('TrSalesHdr', ['NOTA', 'TGL_JUAL', 'TGL_BAYAR', 'ENTRY', 'REC_ID', 'APP_CODE'], 
				[$cNO_INV, $dTG_JUAL, $dDUE_DATE, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		}

		for ($I=1; $I < 5; $I++) {
			$cIDX 	= (string)$I;
			$nQTY	= str_replace(',', '', $_POST['ADD_PRICE'.$cIDX]);
			$cADD_PROD= $_POST['ADD_QUANTITY'.$cIDX];
			if($nQTY>0){
				if($nTRADE) {
					RecCreate('TrSalesDtl', ['NOTA', 'KODE_BRG', 'QUANTITY', 'HARGA', 'ENTRY', 'REC_ID', 'APP_CODE'], 
					[$cNO_INV, $cADD_PROD, $_POST['ADD_QUANTITY'.$cIDX], $nQTY, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
				}
			}
		}
		APP_LOG_ADD($cHEADER, 'add '.$cNO_INV);
		SYS_DB_CLOSE($DB2);	
		header('location:tr_sales.php');
	break;

	case 'ru_bah':
		$cNO_INV	= $_GET['_r'];
		$dTR_DATE 	= $_POST['EDIT_TGL_JUAL'];		// 'dd/mm/yyyy'
		$dDUE_DATE 	= $_POST['EDIT_DUE_DATE'];		// 'dd/mm/yyyy'
		$cCUSTOMER 	= $_POST['UPD_CUST'];		// 'dd/mm/yyyy'
		RecUpdate('TrSalesHdr', ['TGL_BAYAR', 'TGL_JUAL', 'KODE_LGN'], [DMY_YMD($dDUE_DATE), DMY_YMD($dTR_DATE), $cCUSTOMER], 
			"NO_TRM='$cNO_INV' and APP_CODE='$cAPP_CODE'");

		$nNEW_VALUE 	= str_replace(',', '', $_POST['NEW_AMOUNT1']);
		if($nNEW_VALUE>0)	{
			RecCreate('TrSalesDtl', ['NOTA', 'KODE_BRG', 'KET', 'HARGA', 'ENTRY', 'REC_ID', 'APP_CODE'], 
			[$cNO_INV, $_POST['NEW_INVENT1'], $_POST['NEW_DTL1'], $nNEW_VALUE, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		}
		APP_LOG_ADD($cHEADER, 'edit '.$cNO_INV);
		SYS_DB_CLOSE($DB2);	
		header('location:tr_sales.php');
	break;

	case 'DEL_DT':
		$cREC_ID = $_GET['_r'];
		$qQUERY=OpenTable('TrSalesHdr', "REC_ID='$cREC_ID' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		while($aRHDR = SYS_FETCH($qQUERY)){
			$dQUERY=OpenTable('TrSalesDtl', "NOTA='$aRHDR[NOTA]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
			while($aRDTL = SYS_FETCH($dQUERY)){
				RecSoftDel($aRDTL['REC_ID']);
			}
		}
		RecSoftDel($cREC_ID);
		SYS_DB_CLOSE($DB2);	
		header('location:tr_sales.php');
	break;

	case 'upd_upd_dtl':
		$cREC_NO = $_GET['_r'];
		$qUPD_DTL_QUERY = OpenTable('TrReceiptDtl', "REC_ID='$cREC_NO'");
		$aREC_UPD_DETAIL=SYS_FETCH($qUPD_DTL_QUERY);
		$nDEBET = str_replace(',', '', $_POST['UPD_UPD_VALUE']);
		$cINVENT=$_POST['UPD_UPD_INVENT'];
		$cKET = ENCODE($_POST['UPD_UPD_DESCRP']);
		if($cINVENT==''){
			MSG_INFO(S_MSG('NR45','Kode account penerimaan masih kosong'));
			return;
		}
		if($nDEBET==0){
			MSG_INFO(S_MSG('NR46','Nilai penerimaan masih kosong'));
		}
		RecUpdate('TrReceiptDtl', ['ACCOUNT', 'KET', 'NILAI'], [$cACCOUNT, $cKET, $nDEBET], "REC_ID='$cREC_NO'");
		SYS_DB_CLOSE($DB2);	
		header("location:tr_sales.php");
		break;
	case md5('TRN_PRINT'):
		$cREC_CODE = $_GET['_c'];
		if($cREC_CODE=='') {
			MSG_INFO(S_MSG('NR46','Nomor Invoice masih kosong'));
			return;
		}
		$cFORM=S_PARA('FORMAT_INVOICE', 'INVOICE');
		if($cFORM=='')	{
			MSG_INFO('Format Invoice tidak ada');
			return;
		}
		$qQUERY = OpenTable('TrReceiptHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and REC_ID='$cREC_CODE'");
		if(SYS_ROWS($qQUERY)==0){
			MSG_INFO('Nomor Invoice tidak ada');
			return;
		}
		$aPR_HDR=SYS_FETCH($qQUERY);
		APP_LOG_ADD($cHEADER, 'print : '.$aPR_HDR['NOTA']);

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

		// ------------------------------------------------------------------------------
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
			$pdf->Text(GET_FORMAT($cFORM, 'TGGL_LEFT'), GET_FORMAT($cFORM, 'TGGL_TOP'), $aPR_HDR['TGL_JUAL']);

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
// --------------------------------------------------------------------------------------------
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
		SYS_DB_CLOSE($DB2);	
	break;

}
?>

