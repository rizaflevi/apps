<?php
//	tr_sales.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();

	$sPERIOD1 = $_SESSION['sCURRENT_PERIOD'];
	if (isset($_GET['PERIOD']))			$sPERIOD1 = $_GET['PERIOD'];
	$cHELP_FILE = 'Doc/Transaksi - Penjualan.pdf';

	$cHEADER = S_MSG('NJ01','Penjualan');
	$cACTION = '';
	if (isset($_GET['_a']))	$cACTION = $_GET['_a'];

	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE']; $cUSERCODE = $_SESSION['gUSERCODE'];
	$cADD_SALES 	= S_MSG('NJ06','Tambah Penjualan');
	$cEDIT_SALES 	= S_MSG('NJ28','Edit Penjualan');
	$cADD_DTL_SLS 	= S_MSG('NJ24','Tambah Detil Penjualan');

	$cNO_FAKTUR		= S_MSG('NJ02','No.Faktur');
	$cTANGGAL 		= S_MSG('NJ03','Tanggal');
	$cTGL_JTMP		= S_MSG('NR11','Jatuh Tempo');
	$cPELANGAN 		= S_MSG('NJ04','Pelanggan');
	$cALMT_PLG 		= S_MSG('F005','Alamat');
	$cJUMLAH		= S_MSG('NP72','Jumlah');
	$cNIL_TRN		= S_MSG('NR09','Nilai');
	$cDISKON		= S_MSG('NJ15','Diskon');
	$cT_P_R			= S_MSG('TP57','T P R');
	$cP_P_N			= S_MSG('TP46','Ppn');
	$cJT_TEMPO 		= S_MSG('RS03','Jt.Tempo');
	$cKODE_BRG		= S_MSG('NP61','Kode Brg');
	$cNAMA_BRG		= S_MSG('F052','Nama Barang');
	$cQUANTITY		= S_MSG('NP59','Crtn.Ls.Bj');
	$cHRG_BRG		= S_MSG('NL15','Harga');
	$cKETERANGAN	= S_MSG('NP87','Keterangan');
	$cMESSAG1		= S_MSG('NJ26','Benar faktur ini mau di batalkan ?');
	$cTTIP_NOTA		= S_MSG('NJ31','Nomor Faktur penjualan');
	$cTTIP_TGLJ		= S_MSG('NJ33','Tanggal faktur penjualan, default tanggal input/hari ini');
	$cTTIP_TJTP		= S_MSG('NJ34','Tanggal Jatuh tempo, untuk penjualan kredit');
	$cTTIP_QTY		= S_MSG('NJ37','Jumlah barang penjualan');
	$cTTIP_KET		= S_MSG('NJ42','Keterangan tambahan mengenai Penjualan ini, jika diperlukan');
	$cTTIP_LGN		= S_MSG('F023','Daftar Pelanggan');

	$cSAVE_DATA		= S_MSG('F301','Save');		$cCLOSE_DATA	= S_MSG('F302','Close');
	
	$dDATE1	= date('Y-m-01');
	$dDATE2	= date('Y-m-d');
	
	$q_JUAL1 = OpenTable('TrSalesCust', "left(A.TGL_JUAL,7)='".substr($sPERIOD1,0,7)."' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
	
	$cQ_WAREHOUSE	= OpenTable('TbWarehouse', "GDG_DEFLT=1 and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$cGDG_CODE	= '';
	if($aREC_WAREHOUSE	= SYS_FETCH($cQ_WAREHOUSE))		$cGDG_CODE = $aREC_WAREHOUSE['KODE_GDG'];

/*	$cQ_BRANCH	= OpenTable('TbBranch', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$nBRANCH		= 0;
	if($cQ_BRANCH) {
		$aREC_BRANCH	= SYS_FETCH($cQ_BRANCH);
		$nBRANCH		= SYS_ROWS($cQ_BRANCH);
		if($aREC_BRANCH['KODE_GDG']!='')	$cGDG_CODE	= $aREC_BRANCH['KODE_GDG'];
	}	*/
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	$cSTOK_NEGATIF = S_PARA('STOCK_NEGATIF','');

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'view');
		DEF_WINDOW($cHEADER, '' , 'prd');
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
										<a href="?_a=<?php echo md5('cr34t3')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
									</li>
									<li>
										<a href="<?php echo $cHELP_FILE; ?>"> <i class="fa fa-question"></i>Help</a>
									</li>
								</ol>
							</div>
						</header>
						<div class="content-body">    
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">

									<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?> nowrap">
										<thead>
											<tr>
												<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNO_FAKTUR?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTANGGAL?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cPELANGAN?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cNIL_TRN?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cDISKON?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cT_P_R?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cP_P_N?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;"><?php echo $cJUMLAH?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTGL_JTMP?></th>
											</tr>
										</thead>

										<tbody>
											<?php
												$nTOTAL = 0;	$nJUMLAH= 0;	$nDISKON= 0;	$nT_P_R = 0;	$nPPN = 0;	
												while($aRec_Jual1=SYS_FETCH($q_JUAL1)) {
													echo '<tr>';
														$cICON = 'fa fa-money';
														$nAMOUNT = $aRec_Jual1['NILAI']+$aRec_Jual1['PPN']-$aRec_Jual1['DISCOUNT']-$aRec_Jual1['TPR'];
														echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
														echo "<td><span><a href='?_a=".md5('upd4t3')."&_r=".md5('99'+$aRec_Jual1['NOTA'])."'>". $aRec_Jual1['NOTA']."</a></span></td>";
														echo '<td>'.date("d-M-Y", strtotime($aRec_Jual1['TGL_JUAL'])).'</td>';
														echo '<td>'.$aRec_Jual1['CUST_NAME'].'</td>';
														echo '<td align="right">'.number_format($aRec_Jual1['NILAI']).'</td>';
														echo '<td align="right">'.number_format($aRec_Jual1['DISCOUNT']).'</td>';
														echo '<td align="right">'.number_format($aRec_Jual1['TPR']).'</td>';
														echo '<td align="right">'.number_format($aRec_Jual1['PPN']).'</td>';
														echo '<td align="right">'.number_format($nAMOUNT).'</td>';
														echo '<td>'.date("d-M-Y", strtotime($aRec_Jual1['TGL_BAYAR'])).'</td>';
														$nJUMLAH += $aRec_Jual1['NILAI'];
														$nPPN 	+= $aRec_Jual1['PPN'];
														$nTOTAL += $nAMOUNT;
														$nDISKON += $aRec_Jual1['DISCOUNT'];
														$nT_P_R += $aRec_Jual1['TPR'];
													echo '</tr>';
												}
											?>
										</tbody>
										<tr></tr>
										<tr>
											<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
											<td style="font-size: 24px;color: Brown;background-color: LightGray ;">Total</td>
											<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
											<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
											<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nJUMLAH)?></td>
											<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nDISKON)?></td>
											<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nT_P_R)?></td>
											<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nPPN)?></td>
											<td style="font-size: 24px;color: Brown;background-color: LightGray ;" align="right"><?php echo number_format($nTOTAL)?></td>
											<td style="font-size: 24px;color: Brown;background-color: LightGray ;"></td>
										</tr>
										<td></td><td></td><td></td>
										<tr></tr>	
									</table>
								</div>
							</div>
						</div>
					</section>
				</div>

			</section>
		</section>
<?php
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case md5('cr34t3'):
	$cHEADER = S_MSG('NJ06','Entry data Penjualan');

	$cQ_LAST	= OpenTable('TrSalesHdr', "APP_CODE='$cAPP_CODE'", '', 'NOTA desc limit 1');
	$cLAST_NOM	= '';
	if(SYS_ROWS($cQ_LAST)>0) {
		$aREC_jual1= SYS_FETCH($cQ_LAST);
		$cLAST_NOM	= $aREC_jual1['NOTA'];
	}
	$nLAST_NOM	= intval($cLAST_NOM)+1;
	$cLAST_NOM	= str_pad((string)$nLAST_NOM, 6, '0', STR_PAD_LEFT);

	$qCUST=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''");
	$allCUST = ALL_FETCH($qCUST);
	DEF_WINDOW($cHEADER);
?>
			<section id="main-content" class=" ">
				<section class="wrapper main-wrapper" style=''>
					<div class="clearfix"></div>
					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

						<div class="pull-right hidden-xs"></div>
	 
						<header class="panel_header">
							<h2 class="title pull-left"><?php echo $cADD_SALES?></h2>
							<div class="pull-right">
								<ol class="breadcrumb">
									<li>	
										<a href="<?php echo $cHELP_FILE; ?>"> <i class="fa fa-question"></i>Help</a>
									</li>
								</ol>
							</div>
						</header>	

						<section class="box ">
							<div class="content-body">
								<div class="row">
									<form action ="?_a=tambah" method="post">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNO_FAKTUR?></label>
											<input type="text" class="col-sm-2 form-label-900" name='ADD_NOTA' value="<?php echo $cLAST_NOM?>" title="<?php echo $cTTIP_NOTA?>" readonly>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cTANGGAL?></label>
											<input type="date" class="col-md-2 col-sm-4 form-label-900" name='ADD_TGL_JUAL' data-format="dd/mm/yyyy" id="field-2" value="<?php echo date('Y-m-d')?>" title="<?php echo $cTTIP_TGLJ?>">
											<label class="col-sm-2 form-label-700"  style="text-align:right;"><?php echo $cTGL_JTMP?></label>
											<input type="date" class="col-sm-2 form-label-900" name='ADD_TGL_JTMP' data-format="dd/mm/yyyy" id="field-2" value="<?php echo date('Y-m-d')?>" title="<?php echo $cTTIP_TJTP?>">
											<div class="clearfix"></div>

											<div class="form-group">
												<label class="col-lg-3 col-sm-3 col-xs-6 form-label-700"><?php echo $cPELANGAN?></label>
												<select name="ADD_CUST" class="col-lg-4 col-sm-4 col-xs-6 form-label-700 select2" onchange="SELECT_PLG()" title="<?php echo S_MSG('F023','Daftar Pelanggan')?>">
													<option></option>
													<?php
														$qAREA=OpenTable('TbArea', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
														while($aAREA=SYS_FETCH($qAREA)){
															echo '<optgroup label="'.$aAREA['NAMA_AREA'].'">';
															$I=0;
															$nSIZE_ARRAY = count($allCUST);
															while($I<$nSIZE_ARRAY-1) {
																if ($allCUST[$I]['CUST_AREA']==$aAREA['KODE_AREA']) {
																	$cSELECT = $allCUST[$I]['CUST_NAME']."  /  ".$allCUST[$I]['CUST_CODE']."  /  ".$allCUST[$I]['CUST_ADDRESS'];
																	$cVALUE = $allCUST[$I]['CUST_CODE'];
																	echo '<option value="'.$cVALUE.'">'.$cSELECT.'</option>';
																}
																$I++;
															}
														}
													?>
													</optgroup>
												</select>
                                            </div>
											<div class="clearfix"></div>

											<div id="ALM_PLG" class="form-group">
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cALMT_PLG?></label>
												<input type="text" class="col-sm-6 form-label-900" name="ADD_ALAMAT">
												<div class="clearfix"></div>
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cALMT_PLG?></label>
												<input type="text" class="col-sm-6 form-label-900" name="ADD_ALAMAT2">
											</div>
											<div class="clearfix"></div><br>

											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNAMA_BRG?></label>
											<select name="ADD_KODE_BRG" class="col-sm-6 form-label-900 select2" onchange="SELECT_INV()">
											<?php 
												$qQUERY=OpenTable('TbInvGroup', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
												if(SYS_ROWS($cREC_GRUP)==0){
													$REC_DATA=OpenTable('Invent', "NO_ACTIVE=0 and APP_CODE='$cFILTER_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
													while($aREC_DETAIL=SYS_FETCH($REC_DATA)){
														echo "<option value='$aREC_DETAIL[KODE_BRG]'  >$aREC_DETAIL[NAMA_BRG]</option>";
													}
												} else {
													while($aREC_GRUP=SYS_FETCH($cREC_GRUP)){
														echo "<optgroup label='$aREC_GRUP[NAMA_GRP]'>";
														$qREC_INVEN=OpenTable('InvAndStock', "STOK_YEAR=". substr($sPERIOD1,0,4) . " and STOK_MONTH=" . substr($sPERIOD1,5,2)." and 
															A.GROUP_INV='$aREC_GRUP[KODE_GRP]' and (A.NO_ACTIVE=0 or B.STOCK_CTN<>' ') and B.KODE_GDG='$cGDG_CODE' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
														$qREC_INVEN=SYS_QUERY($oREC_INVEN);
														while($aREC_INVEN=SYS_FETCH($qREC_INVEN)){
															echo "<option value='$aREC_INVEN[KODE_BRG]'  >".$aREC_INVEN['KODE_BRG'].":".$aREC_INVEN['NAMA_BRG']."</option>";
														}
														echo '</optgroup>';
													}
												}
											?>
											</select>	
											<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cHRG_BRG?></label>
												<input type="text" class="col-sm-2 form-label-900" name="ADD_HARGA_BRG" id="ADD_HARGA_BRG" data-mask="fdecimal" data-numeric-align="right"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700"><?php echo $cQUANTITY?></label>
											<input type="text" class="col-sm-2 form-label-900" name='ADD_DTL_QTY' id="ADD_DTL_QTY" style="text-align:right" onblur="Sales_Quantity(this.value)" title="<?php echo $cTTIP_QTY?>"><br><br>
											<div class="clearfix"></div>

											<div id="ADD_JUMLAH" class="form-group">
												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cJUMLAH?></label>
												<input type="number" class="col-sm-2 form-label-900" name="ADD_JUMLAH" id="ADD_JUMLAH" data-mask="fdecimal" data-numeric-align="right"><br>
											</div>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700"><?php echo $cKETERANGAN?></label>
											<input type="text" class="col-sm-7 form-label-900" name='ADD_DESCRP' id="ADD_DESCRP" title="<?php echo $cTTIP_KET?>"><br><br>
											<div class="clearfix"></div>
											<div class="text-left">
												<input type="submit" id="SAVE_BUTTON" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
												<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=window.location.href='tr_sales.php'>
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
        <script src="assets/plugins/select2/select2.min.js" type="text/javascript"></script>
        <script src="assets/js/scripts.js" type="text/javascript"></script>
		<script src="sys_js.js" type="text/javascript"></script> 
	</body>
</html>

<?php
	break;

case md5('upd4t3'):
	$qQUERY=OpenTable('TrSalesHdr', "md5('99'+NOTA)='$_GET[_r]' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$aREC_jual1=SYS_FETCH($qQUERY);
	$cNOTA = $aREC_jual1['NOTA'];
	$UPD_ACCOUNT = '1';
	DEF_WINDOW($cHEADER);
?>
				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">

								<div class="pull-left">
									<h2 class="title"><?php echo $cEDIT_SALES?></h2>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>	<a href="?_a=<?php echo md5('VOID_INVOICE')?>&KJ=<?php echo md5($aREC_jual1['NOTA'])?>" onClick="return confirm('<?php echo $cMESSAG1?>')"><i class="glyphicon glyphicon-minus-sign"></i>Void</a>	</li>
										<li>
											<a href="<?php echo $cHELP_FILE; ?>"> <i class="fa fa-question"></i>Help</a>
										</li>
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
										<form action ="?_a=rubah&KJ=<?php echo $aREC_jual1['NOTA']?>&NJ=<?php echo $aREC_jual1['NO_TRANS']?>" method="post"  onSubmit="return CEK_UPD_jual1(this)">
											<div class="col-lg-12 col-xs-12">
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNO_FAKTUR?></label>
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_NOTA' id="field-1" value=<?php echo $aREC_jual1['NOTA']?> disabled="disabled" title="<?php echo $cTTIP_NOTA?>">
												<div class="clearfix"></div>
												
												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-3 form-label-900" data-mask="date" name='EDIT_TGL_JUAL' value=<?php echo date("d-m-Y", strtotime($aREC_jual1['TGL_JUAL']))?> title="<?php echo $cTTIP_TGLJ?>">
												<label class="col-sm-1 form-label-700"></label>
												<label class="col-sm-2 form-label-700" for="field-1"><?php echo $cTGL_JTMP?></label>
												<input type="text" class="col-sm-2 form-label-900" name='UPD_TGL_JTMP' data-mask="date" value="<?php echo date('d/m/Y')?>" title="<?php echo $cTTIP_TJTP?>">
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cPELANGAN?></label>
												<select name="UPD_KODE_LGN" class="col-sm-5 form-label-900" title="<?php echo $cTTIP_LGN?>">
												<?php 
													$qREC_LANGGAN=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
													while($aREC_LANGGAN=SYS_FETCH($qREC_LANGGAN)){
														if($aREC_LANGGAN['CUST_CODE'] == $aREC_jual1['KODE_LGN']){
															echo "<option value='$aREC_jual1[KODE_LGN]' selected='$aREC_jual1[CUST_CODE]' >$aREC_LANGGAN[NAMA_LGN]</option>";
														} else
														echo "<option value='$aREC_LANGGAN[CUST_CODE]'  >$aREC_LANGGAN[CUST_NAME]</option>";
													}
												?>
												</select><br>
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKETERANGAN?></label>
												<input type="text" class="col-sm-6 form-label-900" name='UPD_DESCRP' id="field-2" value="<?php echo $aREC_jual1['DESCRP']?>" title="<?php echo $cTTIP_KET?>">
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
															<a href="#upd_add_detail" data-toggle="modal" > <i class="fa fa-plus-square"></i><?php echo $cADD_DTL_SLS?></a>
														</div>
														<?php
															$cQ_DTL ="select A.*, B.* from jual2 A
																left join (select KODE_BRG, NAMA_BRG from invent where APP_CODE='$cAPP_CODE'  and DELETOR='') B ON A.KODE_BRG=B.KODE_BRG
																where A.NOTA='$cNOTA' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''";
															$qQ_TRM =SYS_QUERY($cQ_DTL);
															$nTOTAL = 0;
															while($aREC_JUAL2=SYS_FETCH($qQ_TRM)) {
																echo '<tr>';
																	echo "<td><span><a href='?_a=".md5('edit_detail_trans')."&DTL_REC_NO=$aREC_JUAL2[JUAL2_RECN]'>". $aREC_JUAL2['KODE_BRG'].'</a></span></td>';
																	echo "<td><span><a href='?_a=".md5('edit_detail_trans')."&DTL_REC_NO=$aREC_JUAL2[JUAL2_RECN]'>". $aREC_JUAL2['NAMA_BRG'].'</a></span></td>';
																	echo '<td align="right">'.$aREC_JUAL2['JUAL_C'].'</td>';
																	echo '<td align="right">'.number_format($aREC_JUAL2['HARGA']).'</td>';
																echo '</tr>';
																$nTOTAL += $aREC_JUAL2['HARGA'];
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
													<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=window.location.href='tr_sales.php'>
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
								<h4 class="modal-title"><?php echo $cADD_DTL_SLS?></h4>
							</div>
							<div class="modal-body">

								<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_BRG?></label>
								<select name="ADD_UPD_KODE_BRG" class="col-sm-6 form-label-900">
								<?php
									$oREC_INVEN="select A.KODE_BRG, A.NAMA_BRG, A.GROUP_INV, A.HRGJ_CTN, A.HRG_CTN, A.UNIT_JUAL, A.INV_ISI, A.NO_ACTIVE, B.KODE_BRG, B.STOCK_CTN, B.KODE_GDG, B.STOK_YEAR, B.STOK_MONTH  from invent A ";
									$oREC_INVEN.=" left join (select KODE_BRG, KODE_GDG, STOCK_CTN, STOK_YEAR, STOK_MONTH from stock where STOK_YEAR=". substr($sPERIOD1,0,4) . " and STOK_MONTH=" . substr($sPERIOD1,5,2) ." and APP_CODE='$cAPP_CODE' and DELETOR='') B on A.KODE_BRG=B.KODE_BRG ";
									$oREC_INVEN.=" where STOK_YEAR=". substr($sPERIOD1,0,4) . " and STOK_MONTH=" . substr($sPERIOD1,5,2)." and ";
									$oREC_INVEN.=" (A.NO_ACTIVE=0 or B.STOCK_CTN<>' ') and B.KODE_GDG='$cGDG_CODE' and A.APP_CODE='$cAPP_CODE' and A.DELETOR='' order by NAMA_BRG";
									$REC_DATA=SYS_QUERY($oREC_INVEN);
									while($aREC_INVEN=SYS_FETCH($REC_DATA)){
										echo "<option value='$aREC_INVEN[KODE_BRG]'  >$aREC_INVEN[NAMA_BRG]</option>";
									}
								?>
								</select>
								<div class="clearfix"></div>

								<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cQUANTITY?></label>
								<input type="text" class="col-sm-6 form-label-900" name='ADD_UPD_JUAL_C' id="field-2" style="width:60%">
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
		  
			<div class="modal" id="upd_upd-detail" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cEDIT_DTL_SLS?></h4>
						</div>
						<div class="modal-body">

							<div class="form-group">
<!--								<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_BRG?></label>
								<div class="controls">
									<input type="text" class="form-label-900" name='UPD_ACCOUNT_NO' id="field-1" value="<?php echo $_GET['UPD_ACCOUNT']?>" style="width:60%">
								</div>
-->
								<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cPELANGAN?></label>
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
	$qQUERY=SYS_QUERY("select * from jual2 where JUAL2_RECN=$eDTL_REC_NO");
//	die ($qQUERY);
	$aREC_DETAIL=SYS_FETCH($qQUERY);

?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<body class=" ">
			<?php	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
						<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
					</div>
					<div class="project-info"></div>
				</div>
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
												<a href="?_a=upd_del_dtl&DTL_RECN=<?php echo $aREC_DETAIL['TRM3_RECNO']?>" onClick="return confirm('<?php echo $cMESSAG1?>')"><i class="glyphicon glyphicon-minus-sign"></i><?php echo S_MSG('F304','Delete')?></a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									
									<form action ="?_a=upd_upd_dtl&DTL_RECN=<?php echo $eDTL_REC_NO?>" method="post">
										<div class="form-group">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_BRG?></label>
											<select name='UPD_UPD_ACCOUNT_NO' class="col-sm-6 form-label-900" title="<?php echo S_MSG('NR1A','Account untuk detil penerimaan')?>">
												<?php 
													echo "<option value=' '  > </option>";
													$REC_ACCT=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cAPP_CODE' and DELETOR=''");
													while($aREC_ACCOUNT=SYS_FETCH($REC_ACCT)){
														if($aREC_ACCOUNT['ACCOUNT_NO']==$aREC_DETAIL['ACCOUNT']){
															echo "<option value='$aREC_DETAIL[ACCOUNT]' selected='$aREC_DETAIL[ACCOUNT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
														} else
														echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
													}
												?>
											</select>
											
											<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cPELANGAN?></label>
											<input type="text" class="col-sm-6 form-label-900" name='UPD_UPD_DESCRP' id="field-2" value="<?php echo $aREC_DETAIL['KET']?>" title="<?php echo S_MSG('NR1C','Keterangan mengenai detil penerimaan')?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNIL_TRN?></label>
											<input type="text" class="col-sm-3 form-label-900" name='UPD_UPD_VALUE' id="field-3" data-mask="fdecimal" value="<?php echo $aREC_DETAIL['NILAI']?>" title="<?php echo S_MSG('NR1D','Nilai atau jumlah penerimaan')?>">
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

		</body>
	</html>
<?php
	break;

case 'tambah':
	$NOW 		= date("Y-m-d H:i:s");
	$dTG_BAYAR	= $_POST['ADD_TGL_JUAL'];		// 'dd/mm/yyyy'
	$cDATE 		= substr($dTG_BAYAR,6,4). '-'. substr($dTG_BAYAR,3,2). '-'. substr($dTG_BAYAR,0,2);
	$dDUE_DATE 	= $_POST['ADD_TGL_JTMP'];		// 'dd/mm/yyyy'
	$cDUE_DATE 	= substr($dDUE_DATE,6,4). '-'. substr($dDUE_DATE,3,2). '-'. substr($dDUE_DATE,0,2);
	$nHRG_JUAL	= str_replace(',', '', $_POST['ADD_HARGA_BRG']);
	$cNOTA	= $_POST['ADD_NOTA'];
	if($cNOTA==''){
		$cMSG= S_MSG('NJ49','Nomor Faktur masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	if($_POST['ADD_TGL_JUAL']==''){
		$cMSG= S_MSG('NJ4A','Tanggal Penjualan masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	if($nHRG_JUAL==0){
		$cMSG= S_MSG('NJ47','Jumlah Penjualan masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from jual1 where APP_CODE='$cAPP_CODE' and DELETOR='' and NOTA='$cNOTA'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG= S_MSG('NJ48','Nomor faktur penjualan sudah ada');
		echo "<script> alert('$cMSG');		window.history.back();		</script>";
		return;
	} else {
		$cQUERY="insert into jual1 set NOTA='$cNOTA', 
			TGL_JUAL='$cDATE', DESCRP='$_POST[ADD_DESCRP]', KODE_LGN='$_POST[ADD_CUST]', 
			TGL_BAYAR='$cDUE_DATE', TRM_CEK='$_POST[ADD_TRM_CEK]', 
			ENTRY='$cUSERCODE', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);

		$cQUERY="insert into jual2 set NOTA='$cNOTA', 
			KODE_BRG='$_POST[ADD_KODE_BRG]', HARGA='$_POST[ADD_DTL_DESCRP]', 
			HARGA='$nHRG_JUAL', 
			ENTRY='$cUSERCODE', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		APP_LOG_ADD($cHEADER, 'add '.$cNOTA);
		header('location:tr_sales.php');
	}
	break;

case 'rubah':
	$NOW 		= date("Y-m-d H:i:s");
	$cNOTA	= $_GET['KJ'];
	$dTG_JUAL 	= $_POST['EDIT_TGL_JUAL'];		// 'dd/mm/yyyy'
	$cDATE 		= substr($dTG_JUAL,6,4). '-'. substr($dTG_JUAL,3,2). '-'. substr($dTG_JUAL,0,2);
	$dTG_BAYAR 	= $_POST['UPD_TGL_JTMP'];		// 'dd/mm/yyyy'
	$cJT_TEMPO	= substr($dTG_BAYAR,6,4). '-'. substr($dTG_BAYAR,3,2). '-'. substr($dTG_BAYAR,0,2);

	$cQUERY = "update jual1 set KODE_LGN='$_POST[UPD_KODE_LGN]', TGL_JUAL='$cDATE', TGL_BAYAR='$cJT_TEMPO', ";
	$cQUERY.= " DESCRP='$_POST[UPD_DESCRP]', UP_DATE='$cUSERCODE', UPD_DATE='$NOW' ";
	$cQUERY.= " where APP_CODE='$cAPP_CODE' and NOTA='$cNOTA' and DELETOR=''";
//	die ($cQUERY);
	$qQUERY = SYS_QUERY($cQUERY);
	APP_LOG_ADD($cHEADER, 'edit '.$cNOTA);
	header('location:tr_sales.php');
	break;

case md5('VOID_INVOICE'):
	$NOW = date("Y-m-d H:i:s");
	$cNOTA=$_GET['KJ'];
	$cQUERY ="update jual1 set DELETOR='$cUSERCODE', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cAPP_CODE' and md5(NOTA)='$cNOTA'";
	$qQUERY =SYS_QUERY($cQUERY);

	$q_JUAL2=SYS_QUERY("select * from jual2 where APP_CODE='$cAPP_CODE' and DELETOR='' and NOTA='$cNOTA'");
	while($r_JUAL2=SYS_FETCH($q_JUAL2)){
		$q_STOCK_QRY=SYS_QUERY("select * from stock where APP_CODE='$cAPP_CODE' and DELETOR='' and KODE_BRG='$r_JUAL2[KODE_BRG]' and KODE_GDG='$r_JUAL2[KODE_GDG]' and STOK_YEAR=".left($sPERIOD1,4)." and STOK_MONTH=".substr($sPERIOD1,5,2));
		if(SYS_ROWS($q_STOCK_QRY)>0){
			$cREC_STOCK=SYS_FETCH(q_STOCK_QRY);
			$cSTOCK_CTN=ADD_QTY($cREC_STOCK['STOCK_CTN'], $r_JUAL2['JUAL_C'], $r_JUAL2['INV_ISI']);
			$cQUERY="update stock set STOCK_CTN='$cSTOCK_CTN', UP_DATE='$cUSERCODE', UPD_DATE='$NOW' where KODE_BRG='$r_JUAL2[KODE_BRG]' and KODE_GDG='$r_JUAL2[KODE_GDG]' and APP_CODE='$cAPP_CODE' and DELETOR=''";
			$q_JUAL2=SYS_QUERY($cQUERY);
		} else {
			$cQUERY="insert into stock set KODE_BRG='$r_JUAL2[KODE_BRG]', 
				TGL_JUAL='$cDATE', DESCRP='$_POST[ADD_DESCRP]', BANK='$_POST[ADD_CUST]', 
				TRM_DD='$cCEK_DATE', TRM_CEK='$_POST[ADD_TRM_CEK]', 
				ENTRY='$cUSERCODE', DATE_ENTRY='$NOW',
				APP_CODE='$_SESSION[data_FILTER_CODE]'";
			SYS_QUERY($cQUERY);
		}
	}
	APP_LOG_ADD($cHEADER, 'delete '.$cNOTA);
	header('location:tr_sales.php');
	break;

case 'upd_add_dtl':
	$NOW = date("Y-m-d H:i:s");
	$cPOST = $_POST['ADD_UPD_KODE_BRG'];
	if($cPOST==''){
		$cMSG= S_MSG('NR45','Kode account penerimaan masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:tr_sales.php');
	}
	if($_POST['ADD_DTL_VALUE']==0){
		$cMSG= S_MSG('NR46','Nilai penerimaan masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:tr_sales.php');
	}

	$NOMOR_TERIMA = $_GET['NOMOR_TERIMA'];
	$nVALUE = str_replace(',', '', $_POST['ADD_DTL_VALUE']);
	$cQUERY="select * from jual2 where APP_CODE='$cAPP_CODE' and DELETOR='' and NOTA='$NOMOR_TERIMA'";
	$cCEK_KODE=SYS_QUERY($cQUERY) or die ('Error in query.' .mysql_error().'==>'.$cQUERY);
	$cQUERY="insert into jual2 set NOTA='$NOMOR_TERIMA', 
		KODE_BRG='$_POST[ADD_UPD_KODE_BRG]', JUAL_C='$_POST[ADD_UPD_JUAL_C]', 
		NILAI='$nVALUE', 
		ENTRY='$cUSERCODE', DATE_ENTRY='$NOW',
		APP_CODE='$_SESSION[data_FILTER_CODE]'";
	SYS_QUERY($cQUERY);
	header('location:tr_sales.php?_a='.md5('upd4t3').'&_r='.$NOMOR_TERIMA);
	break;

case 'upd_upd_dtl':
	$NOW = date("Y-m-d H:i:s");
	$nREC_NO = $_GET['DTL_RECN'];
	$qUPD_DTL_QUERY=SYS_QUERY("select NOTA from jual2 where TRM2_RECNO=$nREC_NO");
	$aREC_UPD_DETAIL=SYS_FETCH($qUPD_DTL_QUERY);
	$nDEBET = $_POST['UPD_UPD_VALUE'];
	if($_POST['UPD_UPD_ACCOUNT_NO']==''){
		$cMSG= S_MSG('NR45','Kode account penerimaan masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:tr_sales.php');
	}
	if($nDEBET==0){
		$cMSG= S_MSG('NR46','Nilai penerimaan masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		header("location:tr_sales.php?_a=".md5('upd4t3')."&_r=".md5(concat('99'+$aREC_UPD_DETAIL['NOTA'])));
		return;
	}

	$cQUERY="update jual2 set ACCOUNT='$_POST[UPD_UPD_ACCOUNT_NO]', ";
	$cQUERY.=" KET='$_POST[UPD_UPD_DESCRP]', ";
	$cQUERY.=" NILAI=".str_replace(',', '', $_POST['UPD_UPD_VALUE']).", "; 
	$cQUERY.=" UP_DATE='$cUSERCODE', UPD_DATE='$NOW' where TRM2_RECNO=$nREC_NO";
	SYS_QUERY($cQUERY);
	header("location:tr_sales.php?_a=".md5('upd4t3')."&_r=".md5(concat('99',$aREC_UPD_DETAIL['NOTA'])));
	APP_LOG_ADD($cHEADER, 'update detil '.$aREC_UPD_DETAIL['NOTA']);
	return;
	break;

case 'upd_del_dtl':
	$NOW = date("Y-m-d H:i:s");
	$nREC_NO = $_GET['DTL_RECN'];
	$qUPD_DTL_QUERY=SYS_QUERY("select NOTA from jual2 where TRM3_RECNO=$nREC_NO");
	$aREC_UPD_DETAIL=SYS_FETCH($qUPD_DTL_QUERY);
	$cQUERY="update jual2 set DELETOR='$cUSERCODE', DEL_DATE='$NOW' 
			where TRM3_RECNO=$nREC_NO";
	$qQUERY =SYS_QUERY($cQUERY);
	header("location:tr_sales.php?_a=".md5('upd4t3')."&_r=$aREC_UPD_DETAIL[NOTA]");
	return;
	break;

case 'load_alamat':
	$cKODE = $_GET['_x'];
	$aLOAD_ALAMAT=SYS_FETCH(SYS_QUERY("select KODE_LGN, ALAMAT, ALAMAT2 from langgan where APP_CODE='$cAPP_CODE' and DELETOR='' and KODE_LGN=$cKODE"));
	header('Content-type: application/json');
	echo json_encode($aLOAD_ALAMAT);
	return;
	break;

case 'load_harga':
	$cKODE = $_GET['_x'];
	$aLOAD_HARGA=SYS_FETCH(SYS_QUERY("select KODE_BRG, HRGJ_CTN from invent where APP_CODE='$cAPP_CODE' and DELETOR='' and KODE_BRG=$cKODE"));
	header('Content-type: application/json');
	echo json_encode($aLOAD_HARGA);
	return;
	break;
}
?>

<script>
	$(function() {
		if($('#s2AddPlgn').val() == ' ') {
			$('#ALM_PLG').hide();
		} else {
			$('#ALM_PLG').show();
		}
		$('#Select_Rec_Bank').change(function(){
			if($('#Select_Rec_Bank').val() != ' ') {
				$('#ALM_PLG').show(); 
			} else {
				$('#ALM_PLG').hide(); 
			} 
		});
	});

	$("#s2AddPlgn").select2({});

	$(function() {
		if($('#s2AddInv').val() == ' ') {
			$('#ADD_HRG_BRG').hide();
		} else {
			$('#ADD_HRG_BRG').show();
		}
	});

	$("#s2AddInv").select2({});

/*
function Button_Save_St(pQuantity) {
	var btn_stat = document.getElementById("SAVE_BUTTON");  // the submit button
//		alert(pQuantity);
    if (pQuantity == "") {
        document.getElementById("ADD_DTL_QTY").innerHTML = "";
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
                document.getElementById("ADD_DTL_QTY").innerHTML = xmlhttp.responseText;
//				alert(xmlhttp.responseText);
				document.getElementById("ADD_DTL_QTY").value = xmlhttp.responseText;
            }
			if (document.getElementById("ADD_DTL_QTY").value == "") {
				document.getElementById("SAVE_BUTTON").setAttribute('disabled', 'disabled');
			} else {
				document.getElementById("SAVE_BUTTON").removeAttribute('disabled');
			}
        };
        xmlhttp.open("GET","tr_sales_cek_qty.php?_QTY="+pQuantity,true);
        xmlhttp.send();
		
    }
}
*/

function Sales_Quantity(pQuantity) {
	$.ajax({
		url:'tr_sales_cek_qty.php?_QTY'+pQuantity,
		type:'get',
		data: {_x: $('#ADD_DTL_QTY').val()},
		success: function(data) {
			$('#ADD_DTL_QTY').val(data.ALAMAT);
			$('#ADD_JUMLAH').val(data.ALAMAT2);
		}
	})
}

function SELECT_PLG() {
	$.ajax({
		url:'tr_sales.php?_a=load_alamat',
		type:'get',
		data: {_x: $('#s2AddPlgn').val()},
		success: function(data) {
			$('#ADD_ALAMAT').val(data.ALAMAT);
			$('#ADD_ALAMAT2').val(data.ALAMAT2);
		}
	})
}

function SELECT_INV() {
	$.ajax({
		url:'tr_sales.php?_a=load_harga',
		type:'get',
		data: {_x: $('#s2AddInv').val()},
		success: function(data) {
			$('#ADD_HARGA_BRG').val(data.HRGJ_CTN);
		}
	})
}

</script>

