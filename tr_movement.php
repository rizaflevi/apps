<?php
//	tr_movement.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();
	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	$sPERIOD1 		= $_SESSION['sCURRENT_PERIOD'];
	$cHELP_FILE 	= 'Doc/Transaksi - Pindah.pdf';
	if (isset($_GET['PERIOD']))		$sPERIOD1 = $_GET['PERIOD'];

	$cHEADER 		= S_MSG('NP74','Pindah Barang');
	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

	$cADD_DATA 		= S_MSG('NP6A','Tambah Pindah Barang');
	$cEDIT_DATA 	= S_MSG('NP6B','Edit Pindah Barang');
	$cADD_DTL_DATA 	= S_MSG('NP6C','Tambah Detil Pindah Barang');
	$cEDIT_DTL_DATA = S_MSG('NP6D','Edit Detil Pindah Barang');

	$cKEY_DATA		= S_MSG('NP83','No. Pindah');
	$cTGL_DATA 		= S_MSG('NP84','Tanggal Pindah');
	$cWH_SOURCE 	= S_MSG('NP79','Gudang Asal');
	$cWH_DEST		= S_MSG('NP81','Gudang Tujuan');
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
	$cNILAI_FKT		= S_MSG('TP49','Total');
	
	$cMESSAG1		= S_MSG('H007','Benar data ini mau di hapus ?');
	$cTTIP_KEY		= S_MSG('NP85','Nomor Pemindahan');
	$cTTIP_TGL		= S_MSG('NP86','Tanggal pemindahan barang, default tanggal input/hari ini');
	$cTTIP_QTY		= S_MSG('NP97','Jumlah barang');
	$cTTIP_ASAL		= S_MSG('NP80','Gudang asal atau sumber');
	$cTTIP_TUJUAN	= S_MSG('NP82','Gudang tujuan pindah');
	$cTTIP_INV 		= S_MSG('TP59','Klik untuk memilih kode persediaan');
	$cTTIP_HRG_BELI	= S_MSG('NP6M','Nilai HPP dari persediaan yang dipindahkan.');
	$cTTIP_JUMLAH	= S_MSG('NP6N','Nilai persediaan yang dipindahkan, jumlah di kali harga.');

	$cSAVE_DATA=S_MSG('F301','Save');
	$cCLOSE_DATA=S_MSG('F302','Close');
	
	$dDATE1	= date('Y-m-01');
	$dDATE2	= date('Y-m-d');

	$qQUERY=OpenTable('TrMovementHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and left(A.TGL_PND,7)='".substr($sPERIOD1,0,7)."'", '', "A.NO_PND desc");	
	$can_CREATE = TRUST($cUSERCODE, 'TR_MOVEMENT_1ADD');

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'view');
		$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
		DEF_WINDOW($cHEADER, '', 'prd');
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKEY_DATA, $cTGL_DATA, $cWH_SOURCE, $cWH_SOURCE]);
						echo '<tbody>';
								$nTOTAL = 0;	$nJUMLAH= 0;	$nDISKON= 0;	$nT_P_R = 0;	$nPPN = 0;	
								while($aREC_PINDAH1=SYS_FETCH($qQUERY)) {
									$aCOL=[$aREC_PINDAH1['NO_PND'], $aREC_PINDAH1['TGL_PND'], $aREC_PINDAH1['NAMA_GDG'], $aREC_PINDAH1['DESTINASI']];
									$cREF="<a href=?_a=".md5('upd4t3')."&_r=".$aREC_PINDAH1['REC_ID'].">";
									TDETAIL($aCOL, [], '', [$cREF, $cREF, '', '']);
								}
						echo '</tbody>';
						TTOTAL(['Total', '', '', '', CVR($nTOTAL)], [0,0,0,0,1]);
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('cr34t3'):
		$cQ_WAREHOUSE	= OpenTable('TbWarehouse', "GDG_DEFLT=1 and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		if($aREC_WAREHOUSE	= SYS_FETCH($cQ_WAREHOUSE))	$cGDG_CODE = $aREC_WAREHOUSE['KODE_GDG'];
		else {
			MSG_INFO("Isi dulu table gudang masih kosong");
			return;
		}
		$qQ_LAST=OpenTable('TrMovementHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and left(A.TGL_PND,7)='".substr($sPERIOD1,0,7)."'", "A.NO_PND desc limit 1");	
		if($aR_PINDAH1	= SYS_FETCH($qQ_LAST)){
			$cLAST_NOM	= $aR_PINDAH1['NO_PND'];
			$nLAST_NOM	= intval($cLAST_NOM)+1;
		}else{
			$nLAST_NOM	= 1;
		}
		$cLAST_NOM	= str_pad((string)$nLAST_NOM, 6, '0', STR_PAD_LEFT);
		DEF_WINDOW($cADD_DATA);
			TFORM($cADD_DATA, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKEY_DATA);
					INPUT('text', [2,2,2,3], '900', 'ADD_NO_PND', $cLAST_NOM, 'focus', '', '', 0, '', 'fix', $cTTIP_KEY);
					LABEL([3,3,3,6], '', $cTGL_DATA);
					INP_DATE([2,2,2,6], '900', 'ADD_TGL_PND', date('d/m/Y'), '', '', '', 'fix', $cTTIP_TGL);
					LABEL([3,3,3,6], '', $cWH_SOURCE);
					TDIV(8,8,8,8);
						SELECT([3,3,3,6], 'ADD_ASAL', '', '', 'select2', $cTTIP_ASAL);
							$REC_WH_ASAL	= OpenTable('TbWarehouse', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )", '', 'GDG_DEFLT desc');
							while($aREC_ASAL =SYS_FETCH($REC_WH_ASAL)){
								echo "<option value='$aREC_ASAL[KODE_GDG]'  >$aREC_ASAL[NAMA_GDG]</option>";
							}
						echo '</select>';
					eTDIV();
					LABEL([3,3,3,6], '', $cWH_DEST);
					TDIV(8,8,8,8);
						SELECT([3,3,3,6], 'ADD_TUJUAN', '', '', 'select2');
							$REC_WH_DEST	= OpenTable('TbWarehouse', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )", '', 'GDG_DEFLT desc');
							while($aREC_DEST =SYS_FETCH($REC_WH_DEST)){
								echo "<option value='$aREC_DEST[KODE_GDG]'  >$aREC_DEST[NAMA_GDG]</option>";
							}
						echo '</select>';
					eTDIV();
					TABLE('example');
						THEAD([$cNAMA_BRG, $cQUANTITY, $cHRG_BRG, $cHRG_JUAL, $cNILAI_FKT], '', [], '*', [5,1,2,2,2]);
						echo '<tbody>';
							for ($I=1; $I < 5; $I++) {
								$cIDX 	= (string)$I;
								echo '<tr>
									<td><div class="form-group">
										<select onchange="val('.$cIDX.')" id="SelectAccount'.$cIDX.'" name="ADD_INVENT'.$cIDX.'" class="col-lg-12 col-sm-12 col-xs-12 form-label-900 select2">"
											<option> </option>';
											$a_DATA=OpenTable('Invent', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
											while($aREC_DETAIL=SYS_FETCH($a_DATA)){
												echo "<option value='".$aREC_DETAIL['KODE_BRG']."' data-price='".$aREC_DETAIL['HARGA_JUAL']."' >"
												.decode_string($aREC_DETAIL['NAMA_BRG']).' - Rp.'. CVR($aREC_DETAIL['HARGA_JUAL']).
												"</option>";
											}
										echo '</select>
									</div></td>';
									echo '<td><input id="quantity-'.$cIDX.'" onchange="perkalian('.$cIDX.')"  type="text" min="0" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name="ADD_QUANTITY'.$cIDX.'" data-mask="fdecimal" data-numeric-align="right" title="'.$cTTIP_QTY.'" onblur="Add_Movement_Quantity(this.value)"></td>';
									echo '<td><input id="harga-'.$cIDX.'" type="text" readonly="readonly" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name="ADD_PURCHASE_PRICE'.$cIDX.'"  data-mask="fdecimal" data-numeric-align="right"></td>';
									echo '<td><input id="sprice-'.$cIDX.'" type="text" readonly="readonly" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name="ADD_SALES_PRICE'.$cIDX.'"  data-mask="fdecimal" data-numeric-align="right"></td>';
									echo '<td><input id="subtotal-'.$cIDX.'" type="text" readonly="readonly" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name="ADD_AMOUNT_'.$cIDX.'"  data-mask="fdecimal" data-numeric-align="right" value="0"></td>
								</tr>';
							}
						echo '</tbody>';
					echo '</table>';
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
		echo '</div></div></section></div></section></section>';
		include "scr_chat.php";	?>
		</div>
		<?php	require_once("js_framework.php");	?>
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
		<script src="assets/js/scripts.js" type="text/javascript"></script>
		<script src="assets/plugins/select2/select2.min.js" type="text/javascript"></script>
		<script src="sys_js.js" type="text/javascript"> </script>
		<script type="text/javascript">
		$(document).ready(function () {
			$("#ADD_DTL_INVENT").select2();
			$("#ADD_ASAL").select2().select2('val', '<?php echo $cGDG_CODE ?>').trigger('change'); // static
			$("#ADD_TUJUAN").select2().select2('val', '<?php echo $cGDG_CODE ?>'); // static
		});
		$("#ADD_ASAL").on("change", function(x) {
			$("#ADD_DTL_INVENT").html('');
			$.ajax({
				url: 'tr_movement_load_stock.php',
				type: 'POST',
				data: {_g: x.val},
				success: function(data) {
					$("#ADD_DTL_INVENT").html(data);
					//$("#ADD_DTL_INVENT").select2().select2('val', '');
				}
			})
		});
		</script>
	</body>
</html>

<?php
		SYS_DB_CLOSE($DB2);
	break;

case 'tambah':
	$cNOTA		= $_POST['ADD_NO_PND'];
	$dTG_PINDAH	= $_POST['ADD_TGL_PND'];		// 'dd/mm/yyyy'
	$cDATE 		= substr($dTG_PINDAH,6,4). '-'. substr($dTG_PINDAH,3,2). '-'. substr($dTG_PINDAH,0,2);
	if($cNOTA==''){
		MSG_INFO(S_MSG('NP6J','Nomor Pindah masih kosong'));
		return;
	}
	if($_POST['ADD_ASAL']==''){
		MSG_INFO(S_MSG('NP6K','Kode Gudang asal / sumber masih kosong'));
		return;
	}
	if($_POST['ADD_TUJUAN']==''){
		MSG_INFO(S_MSG('NP6L','Kode Gudang tujuan masih kosong'));
		return;
	}
	$cQ_LAST=OpenTable('TrMovementHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and A.NO_PND='$cNOTA'");	
	if(SYS_ROWS($cQ_LAST)>0){
		$cMSG= S_MSG('NP6M','Nomor Pindah sudah ada');
		echo "<script> alert('$cMSG');		window.history.back();		</script>";
		return;
	} else {
		RecCreate('TrMovementHdr', ['NO_PND', 'TGL_PND', 'NOTE', 'ENTRY', 'REC_ID', 'APP_CODE'], 
			[$cNOTA, $cDATE, $_POST['ADD_NOTE'], $cUSERCODE, NowMSecs(), $cAPP_CODE]);

		for ($I=1; $I < 5; $I++) {
			$cIDX 	= (string)$I;
			$nRec_id = date_create()->format('Uv');
			$cRec_id = (string)$nRec_id+$I;
			$cINVENT_CODE = $_POST['ADD_INVENT'.$cIDX];
			$nSLS_PRICE = $_POST['ADD_SALES_PRICE'.$cIDX];
			$nPRC_PRICE = $_POST['ADD_PURCHASE_PRICE'.$cIDX];
			$cQTY = $_POST['ADD_QUANTITY'];
			$nQTY = intval($cQTY);
			if($cINVENT_CODE>'') {
				RecCreate('TrMovementDtl', ['NO_PND', 'KODE_BRG', 'HARGA_JUAL', 'HARGA_BELI', 'QTY_MOVE', 'JUMLAH', 'ENTRY', 'REC_ID', 'APP_CODE'], 
					[$cNOTA, $cINVENT_CODE, $nSLS_PRICE, $nPRC_PRICE, $cQTY, $_POST['ADD_JUMLAH'], $cUSERCODE, $cRec_id, $cAPP_CODE]);
			}
		}
		APP_LOG_ADD($cHEADER, 'add '.$cNOTA);
		header('location:tr_movement.php');
	}
	break;

case md5('upd4t3'):
	$xREC_NO=$_GET['_r'];
	$qQUERY=OpenTable('TrMovementHdr', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and md5(A.REC_ID)='$xREC_NO'");	
	$aREC_PINDAH1=SYS_FETCH($qQUERY);
	$cNOTA = $aREC_PINDAH1['NO_PND'];
	$UPD_ACCOUNT = '1';
	DEF_WINDOW($cEDIT_DATA);
?>
				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>
						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">
								<div class="pull-left">
									<h2 class="title"><?php echo $cEDIT_DATA?></h2>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>	<a href="?_a=<?php echo md5('VOID_NO_PND')?>&KJ=<?php echo md5($aREC_PINDAH1['NO_PND'])?>" onClick="return confirm('<?php echo $cMESSAG1?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>	</li>
										<a href="<?php echo $cHELP_FILE; ?>" target="_blank"> <i class="fa fa-question"></i>Help</a>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs"></div>
								<div class="content-body">
									<div class="row">
										<form action ="?_a=rubah&KJ=<?php echo $aREC_PINDAH1['NO_PND']?>" method="post">
											<div class="col-lg-12 col-xs-12">
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKEY_DATA?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_NOTA' id="field-1" value=<?php echo $aREC_PINDAH1['NO_PND']?> disabled="disabled" title="<?php echo $cTTIP_KEY?>">
												<div class="clearfix"></div>
												
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cTGL_DATA?></label>
												<input type="text" class="col-sm-3 form-label-900" data-mask="date" name='EDIT_TGL_PND' id="field-2" value=<?php echo date("d-m-Y", strtotime($aREC_PINDAH1['TGL_PND']))?> title="<?php echo $cTTIP_TGL?>">
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cWH_SOURCE?></label>
												<select name="UPD_ASAL" class="col-sm-5 form-label-900" title="<?php echo $cTTIP_ASAL?>">
												<?php 
													$qREC_GDG	= OpenTable('TbWarehouse', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )", '', 'NAMA_GDG');
													while($aREC_ASAL=SYS_FETCH($qREC_GDG)){
														if($aREC_ASAL['KODE_GDG']==$aREC_PINDAH1['ASAL']){
															echo "<option value='$aREC_PINDAH1[ASAL]' selected='$aREC_PINDAH1[ASAL]' >$aREC_ASAL[NAMA_GDG]</option>";
														} else
														echo "<option value='$aREC_ASAL[KODE_GDG]'  >$aREC_ASAL[NAMA_GDG]</option>";
													}
												?>
												</select><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cWH_DEST?></label>
												<select name="UPD_DEST" class="col-sm-5 form-label-900" title="<?php echo $cTTIP_TUJUAN?>">
												<?php 
													$qREC_DES	= OpenTable('TbWarehouse', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )", '', 'NAMA_GDG');
													while($aREC_DEST=SYS_FETCH($qREC_DES)){
														if($aREC_DEST['KODE_GDG']==$aREC_PINDAH1['TUJUAN']){
															echo "<option value='$aREC_PINDAH1[TUJUAN]' selected='$aREC_PINDAH1[TUJUAN]' >$aREC_DEST[NAMA_GDG]</option>";
														} else
														echo "<option value='$aREC_DEST[KODE_GDG]'  >$aREC_DEST[NAMA_GDG]</option>";
													}
												?>
												</select><br>
												<div class="clearfix"></div><br>
											</div>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<table id="example" class="display table table-hover table-condensed" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th style="background-color:LightGray;"><?php echo $cKODE_BRG?></th>
															<th style="background-color:LightGray;"><?php echo $cNAMA_BRG?></th>
															<th style="background-color:LightGray;text-align:right"><?php echo $cQUANTITY?></th>
															<th style="background-color:LightGray;text-align:right"><?php echo $cHRG_BRG?></th>
														</tr>
													</thead>
													<tbody>
														<div>
															<a href="#upd_add_detail" data-toggle="modal" > <i class="fa fa-plus-square"></i><?php echo $cADD_DTL_DATA?></a>
														</div>
														<?php
															$qQ_PND2=OpenTable('TrMovDtlInv', "A.NO_PND='$cNOTA' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
															$nTOTAL = 0;
															while($aREC_PINDAH2=SYS_FETCH($qQ_PND2)) {
																echo '<tr>';
																	echo "<td><span><a href='?_a=".md5('edit_detail_trans')."&DTL_REC_NO=$aREC_PINDAH2[REC_ID]'>". $aREC_PINDAH2['KODE_BRG'].'</a></span></td>';
																	echo "<td><span><a href='?_a=".md5('edit_detail_trans')."&DTL_REC_NO=$aREC_PINDAH2[REC_ID]'>". $aREC_PINDAH2['NAMA_BRG'].'</a></span></td>';
																	echo '<td align="right">'.$aREC_PINDAH2['JML'].'</td>';
																	echo '<td align="right">'.number_format($aREC_PINDAH2['HARGA_BELI']).'</td>';
																echo '</tr>';
																$nTOTAL += $aREC_PINDAH2['HARGA_BELI'];
															}
														?>
														<tr></tr>
														<tr>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;">Total</td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
															<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nTOTAL)?></td>
														</tr>
														<td></td><td></td><td></td>
														<tr></tr>
													</tbody>
												</table>
												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
													<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=window.location.href='tr_movement.php'>
												</div>
											</div>
										</form>
									</div>
								</div>
							</section>
						</div>
					</section>
				</section>
				<?php	include "scr_chat.php";	?>
			</div>
			<?php	require_once("js_framework.php");	?>
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 
			<div class="modal" id="upd_add_detail" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<form action ="?_a=upd_add_dtl&NOMOR_TERIMA=<?php echo $cNOTA?>" method="post">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title"><?php echo $cADD_DTL_DATA?></h4>
							</div>
							<div class="modal-body">

								<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_BRG?></label>
								<select class="col-sm-6 form-label-900" style="padding:0" name="ADD_UPD_KODE_BRG" id="s2Upd_AddInv" onchange="SELECT_INV()" title="<?php echo $cTTIP_INV?>">
								<?php 
									$qREC_GRUP=OpenTable('TbInvGroup');
									if(SYS_ROWS($qREC_GRUP)==0){
										$qINVENT=OpenTable('Invent');
										while($aREC_DETAIL=SYS_FETCH($qINVENT)){
											echo "<option value='$aREC_DETAIL[KODE_BRG]'  >$aREC_DETAIL[NAMA_BRG]</option>";
										}
									} else {
										while($aREC_GRUP=SYS_FETCH($cREC_GRUP)){
											echo "<optgroup label='$aREC_GRUP[NAMA_GRP]'>";
											$qINVENT=OpenTable('Invent', "GROUP_INV='$aREC_GRUP[KODE_GRP]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'NO_ACTIVE');
											while($aREC_INVEN=SYS_FETCH($qINVENT)){
												echo "<option value='$aREC_INVEN[KODE_BRG]'  >".$aREC_INVEN['KODE_BRG']." : ".$aREC_INVEN['NAMA_BRG']."</option>";
											}
											echo '</optgroup>';
										}
									}
								?>
								</select><br>	
								<div class="clearfix"></div>

								<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cQUANTITY?></label>
								<input type="text" class="col-sm-2 form-label-900" name='ADD_UPD_JUAL_C' id="field-2">
								<div class="clearfix"></div>

								<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNIL_TRN?></label>
								<input type="text" class="col-sm-3 form-label-900" name='ADD_DTL_VALUE' id="field-3"  data-mask="fdecimal" value=0 style="width:30%">
								<div class="clearfix"></div>
							</div>
							<div class="modal-footer">
								<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
								<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		  
			<div class="modal" id="upd_upd_detail" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cEDIT_DTL_DATA?></h4>
						</div>
						<div class="modal-body">

							<div class="form-group">
								<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cWH_SOURCE?></label>
								<div class="controls">
									<input type="text" class="form-label-900" name='UPD_DESCRP' id="field-2" style="width:60%">
								</div>
								<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNIL_TRN?></label>
								<div class="controls">
									<input type="text" class="form-label-900" name='UPD_DEBIT' id="field-3" data-mask="fdecimal" style="width:30%">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
							<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						</div>
					</div>
				</div>
			</div>
		  
		</body>
		</html>
<?php
	break;

case md5('edit_detail_trans'):
	$eDTL_REC_NO = $_GET['DTL_REC_NO'];
	$qQUERY=SYS_QUERY("select * from pindah2 where PND2_RECNO=$eDTL_REC_NO");
	$aREC_DETAIL=SYS_FETCH($qQUERY);

	$cQUERY ="select NO_PND, ASAL from pindah1 where NO_PND='$aREC_DETAIL[NO_PND]' and APP_CODE='$cAPP_CODE' and DELETOR=''";
	$qQUERY =SYS_QUERY($cQUERY);
	$aREC_PINDAH1=SYS_FETCH($qQUERY);

	$cHEADER = S_MSG('NP99','Update detil data Pindah Barang');
	DEF_WINDOW($cHEADER);
?>
				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>
						<div class="clearfix"></div>

						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left"><?php echo $cHEADER?></h2>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>
												<a href="?_a=upd_del_dtl&DTL_RECN=<?php echo $aREC_DETAIL['PND2_RECNO']?>" onClick="return confirm('<?php echo $cMESSAG1?>')"><i class="glyphicon glyphicon-minus-sign"></i><?php echo S_MSG('F304','Delete')?></a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									
									<form action ="?_a=upd_upd_dtl&DTL_RECN=<?php echo $eDTL_REC_NO?>" method="post">
										<div class="form-group">
											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNAMA_BRG?></label>
											<select class="col-sm-6 form-label-900" style="padding:0" name='UPD_UPD_INVENTORY' id='UPD_UPD_INVENTORY' onchange="SELECT_INV()" title="<?php echo $cTTIP_INV?>">
												<?php 	/*
													$cREC_GRUP=SYS_QUERY("select KODE_GRP, NAMA_GRP from bgroup where APP_CODE='$cAPP_CODE' and DELETOR=''");
													if(SYS_ROWS($cREC_GRUP)==0){
														$cREC_DATA="select KODE_BRG, NAMA_BRG, GROUP_INV, NO_ACTIVE, APP_CODE, DELETOR from invent A 
															left join (select KODE_BRG, KODE_GDG, STOCK_CTN, STOK_MONTH, STOK_YEAR from stock where APP_CODE='$cAPP_CODE' and DELETOR='' and 
															STOK_YEAR=". substr($sPERIOD1,0,4) . " and STOK_MONTH=" . substr($sPERIOD1,5,2). " and ";
														$cREC_DATA.= " KODE_GDG='$aREC_PINDAH1[ASAL]' and STOCK_CTN>' ') B ON A.KODE_BRG=B.KODE_BRG where A.NO_ACTIVE=0 and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''";
														$aREC_DATA=SYS_QUERY($cREC_DATA);
														while($aREC_DETAIL=SYS_FETCH($aREC_DATA)){
															echo "<option value='$aREC_DETAIL[KODE_BRG]'  >$aREC_DETAIL[NAMA_BRG]</option>";
														}
													} else {
														while($aREC_GRUP=SYS_FETCH($cREC_GRUP)){
															echo "<optgroup label='$aREC_GRUP[NAMA_GRP]'>";
															$oREC_INVEN="select A.KODE_BRG, A.NAMA_BRG, A.GROUP_INV, A.NO_ACTIVE, A.APP_CODE, A.DELETOR from invent A
																left join (select KODE_BRG, KODE_GDG, STOCK_CTN, STOK_MONTH, STOK_YEAR from stock where APP_CODE='$cAPP_CODE' and DELETOR='' and ";
															$oREC_INVEN.= " STOK_YEAR=". substr($sPERIOD1,0,4) . " and STOK_MONTH=" . substr($sPERIOD1,5,2). " and ";
															$oREC_INVEN.= " KODE_GDG='$aREC_PINDAH1[ASAL]' and STOCK_CTN>' ') B ON A.KODE_BRG=B.KODE_BRG where A.NO_ACTIVE=0 and A.GROUP_INV='$aREC_GRUP[KODE_GRP]' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''";
															$qREC_INVEN = SYS_QUERY($oREC_INVEN);
															while($aREC_INVEN=SYS_FETCH($qREC_INVEN)){
																echo "<option value='$aREC_INVEN[KODE_BRG]'  >".$aREC_INVEN[KODE_BRG].":".$aREC_INVEN[NAMA_BRG]."</option>";
															}
															echo '</optgroup>';
														}
													}
*/												?>
											</select>	<div class="clearfix"></div>
											
											<label class="col-sm-3 form-label-700"><?php echo $cQUANTITY?></label>
											<input type="text" class="col-sm-2 form-label-900" name='ADD_JML' id="ADD_JML" style="text-align:right" onblur="Add_Movement_Quantity(this.value)" title="<?php echo $cTTIP_QTY?>"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-2"><?php echo $cHRG_BRG?></label>
											<input type="text" class="col-sm-3 form-label-900" name='UPD_UPD_DESCRP' id="field-2" data-mask="fdecimal" value="<?php echo $aREC_DETAIL['HRG_BELI']?>" title="<?php echo $cTTIP_HRG_BELI?>">
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cNIL_TRN?></label>
											<input type="text" class="col-sm-3 form-label-900" name='UPD_UPD_VALUE' id="field-3" data-mask="fdecimal" value="<?php echo $aREC_DETAIL['JUMLAH']?>" title="<?php echo $cTTIP_JUMLAH?>">
											<div class="clearfix"></div>
										</div>
										<div class="text-left">
											<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
											<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
										</div>
									</form>
								</div>
							</section>
						</div>

					</section>
				</section>
				<!-- END CONTENT -->
				<?php	include "scr_chat.php";	?>
			</div>
			<?php	require_once("js_framework.php");	?>
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
			  <script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
			  <script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script>
			  <script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>

			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script type="text/javascript">
				$(document).ready(function () {
					$("#UPD_UPD_INVENTORY").select2();
					$("#ADD_ASAL").select2().select2('val', '<?php echo $cGDG_CODE ?>').trigger('change'); // static
					$("#ADD_TUJUAN").select2().select2('val', '<?php echo $cGDG_CODE ?>'); // static
				});
				$("#ADD_ASAL").on("change", function(x) {
					$("#UPD_UPD_INVENTORY").html('');
					$.ajax({
						url: 'tr_movement_load_stock.php',
						type: 'POST',
						data: {_g: x.val},
						success: function(data) {
							$("#UPD_UPD_INVENTORY").html(data);
						}
					})
				});
			</script>

		</body>
	</html>
<?php
	break;

case 'rubah':
	$NOW 		= date("Y-m-d H:i:s");
	$cNOTA	= $_GET['KJ'];
	$dTG_MOVE 	= $_POST['EDIT_TGL_PND'];		// 'dd/mm/yyyy'
	$cDATE 		= substr($dTG_MOVE,6,4). '-'. substr($dTG_MOVE,3,2). '-'. substr($dTG_MOVE,0,2);

	RecUpdate('TrMovementHdr', ['TGL_PND', 'ASAL', 'TUJUAN'], [$cDATE, $_POST['UPD_ASAL'], $_POST['UPD_DEST']], "NO_PND='$cNOTA' and REC_ID not in ( select DEL_ID from logs_delete)");
	APP_LOG_ADD($cHEADER, 'edit '.$cNOTA);
	header('location:tr_movement.php');
	break;

case md5('VOID_NO_PND'):
	$NOW = date("Y-m-d H:i:s");
	$cNOTA=$_GET['KJ'];
	$cQUERY ="update pindah1 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cAPP_CODE' and md5(NO_PND)='$cNOTA'";
	$qQUERY =SYS_QUERY($cQUERY);

	$q_MASUK2=SYS_QUERY("select * from pindah2 where APP_CODE='$cAPP_CODE' and DELETOR='' and NO_PND='$cNOTA'");
	while($r_MASUK2=SYS_FETCH($q_MASUK2)){
		$q_STOCK_QRY=SYS_QUERY("select * from stock where APP_CODE='$cAPP_CODE' and DELETOR='' and KODE_BRG='$r_MASUK2[KODE_BRG]' and KODE_GDG='$r_MASUK2[KODE_GDG]' and STOK_YEAR=".left($sPERIOD1,4)." and STOK_MONTH=".substr($sPERIOD1,5,2));
		if(SYS_ROWS($q_STOCK_QRY)>0){
			$cREC_STOCK=SYS_FETCH(q_STOCK_QRY);
			$cSTOCK_CTN=ADD_QTY($cREC_STOCK['STOCK_CTN'], $r_MASUK2['JUAL_C'], $r_MASUK2['INV_ISI']);
			$cQUERY="update stock set STOCK_CTN='$cSTOCK_CTN', UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW' where KODE_BRG='$r_MASUK2[KODE_BRG]' and KODE_GDG='$r_MASUK2[KODE_GDG]' and APP_CODE='$cAPP_CODE' and DELETOR=''";
			$q_MASUK2=SYS_QUERY($cQUERY);
		} else {
			$cQUERY="insert into stock set KODE_BRG='$r_MASUK2[KODE_BRG]', 
				TGL_PND='$cDATE', NOTE='$_POST[ADD_NOTE]', BANK='$_POST[ADD_PLGN]', 
				TRM_DD='$cCEK_DATE', TRM_CEK='$_POST[ADD_TRM_CEK]', 
				ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
				APP_CODE='$_SESSION[data_FILTER_CODE]'";
			SYS_QUERY($cQUERY);
		}
	}
	APP_LOG_ADD($cHEADER, 'delete '.$cNOTA);
	header('location:tr_movement.php');
	break;

case 'upd_add_dtl':
	$NOW = date("Y-m-d H:i:s");
	$cPOST = $_POST['ADD_UPD_KODE_BRG'];
	if($cPOST==''){
		$cMSG= S_MSG('TP65','Kode barang masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:tr_movement.php');
	}
	if($_POST['ADD_DTL_VALUE']==0){
		$cMSG= S_MSG('TP66','Jumlah pembelian masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:tr_movement.php');
	}

	$NOMOR_TERIMA = $_GET['NOMOR_TERIMA'];
	$nVALUE = str_replace(',', '', $_POST['ADD_DTL_VALUE']);
	$cQUERY="select * from pindah2 where APP_CODE='$cAPP_CODE' and DELETOR='' and NO_PND='$NOMOR_TERIMA'";
	$cCEK_KODE=SYS_QUERY($cQUERY) or die ('Error in query.' .mysql_error().'==>'.$cQUERY);
	$cQUERY="insert into pindah2 set NO_PND='$NOMOR_TERIMA', 
		KODE_BRG='$_POST[ADD_UPD_KODE_BRG]', JUAL_C='$_POST[ADD_UPD_JUAL_C]', 
		JUMLAH='$nVALUE', 
		ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
		APP_CODE='$_SESSION[data_FILTER_CODE]'";
	SYS_QUERY($cQUERY);
	header('location:tr_movement.php?_a='.md5('upd4t3').'&_r='.$NOMOR_TERIMA);
	break;

case 'upd_upd_dtl':
	$NOW = date("Y-m-d H:i:s");
	$nREC_NO = $_GET['DTL_RECN'];
	$qUPD_DTL_QUERY=SYS_QUERY("select NO_PND from pindah2 where TRM2_RECNO=$nREC_NO");
	$aREC_UPD_DETAIL=SYS_FETCH($qUPD_DTL_QUERY);
	$nDEBET = $_POST['UPD_UPD_VALUE'];
	if($_POST['UPD_UPD_INVENTORY']==''){
		$cMSG= S_MSG('NP6G','Kode persediaan masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:tr_movement.php');
	}
	if($nDEBET==0){
		$cMSG= S_MSG('NP6H','Jumlah barang yang dipindahkan masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		header("location:tr_movement.php?_a=".md5('upd4t3')."&_r=".md5($aREC_UPD_DETAIL['NO_PND']));
		return;
	}

	$cQUERY="update pindah2 set KODE_BRG='$_POST[UPD_UPD_INVENTORY]', ";
	$cQUERY.=" KET='$_POST[UPD_UPD_DESCRP]', ";
	$cQUERY.=" JUMLAH=".str_replace(',', '', $_POST['UPD_UPD_VALUE']).", "; 
	$cQUERY.=" UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW' where TRM2_RECNO=$nREC_NO";
	SYS_QUERY($cQUERY);
	header("location:tr_movement.php?_a=".md5('upd4t3')."&_r=".md5($aREC_UPD_DETAIL['NO_PND']));
	APP_LOG_ADD($cHEADER, 'update detil '.$aREC_UPD_DETAIL['NO_PND']);
	return;
	break;

case 'upd_del_dtl':
	$NOW = date("Y-m-d H:i:s");
	$nREC_NO = $_GET['DTL_RECN'];
	$qUPD_DTL_QUERY=SYS_QUERY("select NO_PND from pindah2 where PND2_RECNO=$nREC_NO");
	$aREC_UPD_DETAIL=SYS_FETCH($qUPD_DTL_QUERY);

	$cQUERY="update pindah2 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' 
			where PND2_RECNO=$nREC_NO";
	$qQUERY =SYS_QUERY($cQUERY);
	header("location:tr_movement.php?_a=".md5('upd4t3')."&_r=$aREC_UPD_DETAIL[NO_PND]");
	return;
	break;

case 'load_harga':
	$cKODE = $_GET['_x'];
	$aLOAD_HARGA=SYS_FETCH(SYS_QUERY("select KODE_BRG, HRG_CTN, HRGJ_CTN from invent where APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and KODE_BRG=$cKODE"));
	header('Content-type: application/json');
	echo json_encode($aLOAD_HARGA);
	return;
	break;
}
?>

<script>
	$(function() {
		if($('#ADD_DTL_INVENT').val() == ' ') {
			$('#ADD_HRG_BRG').hide();
		} else {
			$('#ADD_HRG_BRG').show();
		}
	});
	$("#ADD_DTL_INVENT").select2({});
	$("#s2Upd_AddInv").select2({});
	$("#UPD_UPD_INVENTORY").select2({});


function Add_Movement_Quantity(pQuantity) {
	var btn_stat = document.getElementById("SAVE_BUTTON");  // the submit button
//		alert(pQuantity);
    if (pQuantity == "") {
        document.getElementById("ADD_NO_PND").innerHTML = "";
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

function SELECT_ASAL(_GDG) {
	$.ajax({
		url:'tr_movement_load_stock.php?_g='+_GDG,
		type:'get',
		data: {_x: $('#ADD_ASAL').val()},
		success: function(data) {
			$('#SELECT_ASAL').val(data.ALAMAT);
		}
	})
}

function SELECT_INV() {
	$.ajax({
		url:'tr_movement.php?_a=load_harga',
		type:'get',
		data: {_x: $('#ADD_DTL_INVENT').val()},
		success: function(data) {
			$('#ADD_HARGA_BRG').val(data.HRG_CTN);
			$('#ADD_HRG_JUAL').val(data.HRGJ_CTN);
		}
	})
}

function loadStockWhs() {
	$.ajax({
		url: "tr_movement_load_stock.php?_a=load_harga",
		type: 'POST',
		data: {id_wh: $('#s2AddASAL').val()},
		success: function (data) {
			$("#kabupaten").html(data);
			$("#kabupaten").select2().select2('val', '');
			if ("<?php echo $ismode ?>" == "edit") {
				$("#kabupaten").select2().select2('val', '<?php echo $domisili['id_kab'] ?>');
			}

			loadKecamatan();
		},
		error: function (jqXHR, status, err) {
			alert(err);
		}
	});
}



</script>

