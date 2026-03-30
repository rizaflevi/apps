<?php
// pos_entry.php		entry pos
require_once 'vendor/fpdf/fpdf.php';
require_once 'sysfunction.php';
if (!isset($_SESSION['data_FILTER_CODE'])) 
	session_start();
$cUSERCODE 	= $_SESSION['gUSERCODE'];
$cORDER=(isset($_GET['_o']) ? $_GET['_o'] : '');
$cTABLE=(isset($_GET['_t']) ? $_GET['_t'] : '');
$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
$cHEADER	= 'POS Entry';
$cFILE_LOGO_COMP = 'data/images/'. 'LOGO1_'.$cAPP_CODE.'.jpg';
$cSALES_ORDER = '';
$qPAYMENT=OpenTable('TbBank', "IN_FRONT=1 and APP_CODE='$cAPP_CODE'");
$nTPAYMENT = SYS_ROWS($qPAYMENT);
$cPAYMENT = S_MSG('H144','Pembayaran');
$aHEAD = ['Menu', 'Harga', 'Qty', 'Jml Harga'];
$cFOLDER_IMG = 'data/images_inventory/';
// if (isLocalhost())	$cFOLDER_IMG = 'data/images/resto/';
if (IS_LOCALHOST())		UPDATE_DATE();
$cCO1	= S_PARA('CO1', '');
$cCO2	= S_PARA('CO2', '');
$cCO3	= S_PARA('CO3', '');
$cLOGO	= 'data/images/'.$cAPP_CODE.'_LOGO.jpg';
$cGUEST ='';
$nTOTAL=0;
$qSO_HDR=OpenTable(
	'So1', 
	"SO_VOID=0 and TABLE_CODE='$cTABLE' and FAKTUR='' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )"
);
$nSO_HDR=SYS_ROWS($qSO_HDR);
if ($nSO_HDR) {
	$aSO1=SYS_FETCH($qSO_HDR);
	$cGUEST =$aSO1['CUST_NAME'];
	$nTOTAL =$aSO1['NILAI'];
}
$cORDR='Nota Meja : '.$cTABLE;
switch($cORDER){
	case 'd': 
		$cORDR='Delivery : '.$cTABLE;
		break;
	case 't': 
		$cORDR='Take Away : '.$cTABLE;
		break;
}
$cACTION = (isset($_GET['_a']) ? $_GET['_a'] : '');
switch((isset($_GET['_a']) ? $_GET['_a'] : '')) {
	case 'ADD_CART':
		$cMENU=(isset($_POST['_m']) ? $_POST['_m'] : '');
		$cMN_CODE=(isset($_POST['_c']) ? $_POST['_c'] : '');
		$cTABLE=(isset($_POST['_t']) ? $_POST['_t'] : '');
		$cORDER=(isset($_POST['_o']) ? $_POST['_o'] : '');
		$RESPONSE = array("header" => array("TABLE" => "", "NILAI" => 0), "detail" => array());
		$TOTAL_PRICE = 0;
		if ($cMENU) {
			$qMENU=OpenTable('Invent', "APP_CODE='$cAPP_CODE' and KODE_BRG='$cMN_CODE' and HARGA_JUAL > 0");
			$aMENU=SYS_FETCH($qMENU);
			$nPRICE = $aMENU['HARGA_JUAL'];
			$cREC_ID= NowMSecs();
			$q_SO=OpenTable('So1', "SO_VOID=0 and TABLE_CODE='$cTABLE' and FAKTUR='' and APP_CODE='$cAPP_CODE'");
			$nSO1=SYS_ROWS($q_SO);
			if ($nSO1) {
				$aSO1=SYS_FETCH($q_SO);
				$cREC_ID = $aSO1['REC_ID'];
				$TOTAL_PRICE = $aSO1['NILAI'] + $nPRICE;
			} else {
				RecCreate('So1', 
					['REC_ID', 'TEAM', 'TABLE_CODE', 'TANGGAL', 'JT_TMP', 'NILAI', 'APP_CODE'], 
					[$cREC_ID, $cORDER, $cTABLE, date('Y-m-d'), date('Y-m-d'), $nPRICE, $cAPP_CODE]
				);
				$TOTAL_PRICE = $nPRICE;
			}
			$cSALES_ORDER=$cREC_ID;
			$qSO2=OpenTable('So2', "SO1_ID='$cREC_ID' and KODE_BRG='$cMN_CODE'");
			if (SYS_ROWS($qSO2)) {
				$aSO2 = SYS_FETCH($qSO2);
				$nQTY = $aSO2['JML']+1;
				$cSO2_ID = $aSO2['REC_ID'];
				$nJML_HRG = $nQTY * $aSO2['HARGA'];
				RecUpdate('So2', ['JML', 'JML_HARGA'], [$nQTY, $nJML_HRG], "REC_ID='$aSO2[REC_ID]'");
			}	else {
				RecCreate(
					'So2', 
					['REC_ID', 'SO1_ID', 'KODE_BRG', 'HARGA', 'JML', 'JML_HARGA'], 
					[NowMSecs(), $cREC_ID, $cMN_CODE, $nPRICE, 1, $nPRICE]
				);
			}
			RecUpdate('So1', ['NILAI'], [$TOTAL_PRICE], "REC_ID='$cREC_ID'");
			$RESPONSE['header']['TABLE'] = $cTABLE;
			$RESPONSE['header']['NILAI'] = $TOTAL_PRICE;
			// $So2Result = OpenTable('So2', "SO1_ID='$cREC_ID' and REC_ID not in ( select DEL_ID from logs_delete )");
			$So2Result = OpenTable('So2Inv', "A.SO1_ID='$cREC_ID' and A.REC_ID not in ( select DEL_ID from logs_delete )");
			while ($aSo2 = SYS_FETCH($So2Result)) {
				// $MENU_DESC = ($aSo2['SHORT_NAME']>'' ? $aSo2['SHORT_NAME'] : $aSo2['NAMA_BRG']);
				// print_r2($MENU_DESC);
				$mapValue = array_map('trim', $aSo2);
				$cHREF = "<a href='#' class='edit-row' 
				data-id='{$mapValue['SO1_ID']}'
				data-nama='{$mapValue['NAMA_BRG']}'	
				data-harga='{$mapValue['HARGA']}'
				data-jml='{$mapValue['JML']}'
				data-toggle='modal' 
				data-target='#editModal'>{$mapValue['NAMA_BRG']}</a>";
				$RESPONSE['detail'][] = array($cHREF, number_format($mapValue['HARGA'], 0, '.', '.'), $mapValue['JML'], number_format($mapValue['JML_HARGA'], 0, '.', '.'));
			}
		}
		print(json_encode($RESPONSE));
		exit;
	default:
		if (IS_LOCALHOST())		UPDATE_DATE();
?>
<style>
#example td,
#example th {
  color: black !important;
}
.form-control:focus {
  box-shadow: none;
  outline: none;
}
.modal.show .modal-dialog {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}
#item_cart {
  max-height: none;
  overflow: visible;
}

</style>

<!DOCTYPE html>
<html class=" ">
	<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
  <!-- Custom CSS from SB -->
	<link href="sb/css/bootstrap.min.css" rel="stylesheet">
	<link href="sb/css/sb-admin-2.css" rel="stylesheet">
	<link href="sb/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<body oncontextmenu="return false;" class="sidebar-collapse">
		<?php	/* require_once("scr_header.php");	require_once("scr_topbar.php");	*/ ?>
		<div class="page-container row-fluid">
			
			<div class="page-sidebar collapseit">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper">
					<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
				</div>
				<div class="project-info"></div>
			</div>

			<section id="main-content"  class="sidebar_shift">
				<section class="wrapper main-wrapper">

					<!-- <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 page-title">
							<div class="pull-left">
								  <h1 class="title"><?php /*echo $cHEADER */?></h1>
							</div>
						</div>
					<div class="text-left"></div> -->
					<!-- <input type="button" class="btn btn-dark btn-lg" style="font-size:30px; padding:10px 20px; color:green; width:100px;" value="<==" onclick="window.location.href='pos.php'"/> -->
					<div class="clearfix"></div>

					<div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
						<section class="box">
							<div class="pull-right hidden-xs">	</div>

							<div class="content-body">
								<div class="row">
									<?php
										$qGMENU=OpenTable('tbi_group', "APP_CODE='$cAPP_CODE' and GRP_MENU=1", '', 'NO_URUT');
										echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div id="portfolio" class="">
												<div class="text-center">
													<ul class="portfolio-filter list-inline">';
													while($aGMENU=SYS_FETCH($qGMENU)) {
														echo '<li><a class="btn btn-primary" href="#" data-filter=".'.$aGMENU['KODE_GRP'].'">'.($aGMENU['SHORT_NAME'] ? $aGMENU['SHORT_NAME'] : $aGMENU['NAMA_GRP']).'</a></li>';
													}
													echo '<span> &emsp;&emsp;</span>';
													// echo '<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">';
													echo '<input type="button" class="btn btn-dark btn-lg" style="font-size:30px; padding:5px 5px; color:green; width:100px;" value="Back" onclick="window.location.href=\'pos.php\'"/>';
													echo '</ul>
												</div>
											</div>';

											echo '<div class="portfolio-items">';
											$qMENU=OpenTable('Invent', "NO_ACTIVE=0 and APP_CODE='$cAPP_CODE' and HARGA_JUAL > 0", '', 'GROUP_INV');
											while($aMENU=SYS_FETCH($qMENU)) {
												$cCODE = $aMENU['KODE_BRG'];
												$itemName = ($aMENU['SHORT_NAME'] ? $aMENU['SHORT_NAME'] : $aMENU['NAMA_BRG']);
												$cFILE_IMAGE = $cAPP_CODE.'_INV_'.$cCODE.'.jpg';
												$cFILE_FOTO = $cFOLDER_IMG.$cFILE_IMAGE;
												// $url = 'pos_entry.php?_a=x&_m='.$aMENU['MENU_DESC'].'&_c='.$cCODE.'&_t='.$cTABLE;
												echo '<div class="portfolio-item col-lg-2 col-md-3 col-sm-4 col-xs-6 '. $aMENU['GROUP_INV'].'">
														<div class="portfolio-item-inner">
															<img class="img-responsive" src="'.$cFILE_FOTO.'" style="height: 10em; width: 15em;" alt="" 
																onclick="ADD_CART(\''.$cCODE.'\', \''.$itemName.'\', \''.$cTABLE.'\', \''.$cORDER.'\')" title="'. $aMENU['INV_NOTES'].'
																">
															<div class="portfolio-info animated fadeInUp animated-duration-600ms">';
																echo '<div class="text-center" style="max-width: 150px; white-space: nowrap; text-overflow: ellipsis;">
																	<h4>'.$itemName.'</h4>
																</div>';

																// echo '<span class="desc">'. $aMENU['MENU_NOTES'].'</span>';
																// echo '<a class="preview" href="'.$cFILE_FOTO.'" rel="prettyPhoto"><i class="fa fa-eye"></i></a>';
															echo '</div>
														</div>
													</div>';
											}
											echo '</div>';
										echo '</div>';
									?>
								</div>

						</section>
					</div>
					<div id="not_printable" class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
						<?php
							echo '<h3>'.$cORDR.'</h3>';
							LABEL([3,4,4,4], '900', 'A / n');	
						?>
							<input type="text" class="form-control border-0 border-bottom rounded-0 shadow-none bg-transparent p-0 input-lg" name="GUEST_NAME" id="GUEST_NAME" value="<?php echo $cGUEST; ?>" 
								onblur="ON_BEHALF_OF('<?php echo $cTABLE ?>', '<?php echo $cORDER ?>')" style="font-size:20px; height:50px;"/>
						<?php
							$qSO21=OpenTable('SO21', "C.SO_VOID=0 and TABLE_CODE='$cTABLE' and C.FAKTUR='' and C.APP_CODE='$cAPP_CODE'");
							TABLE('item_cart', _PAGING:'*', _SEARCHING:'*');
							THEAD($aHEAD,[], [0,1,1,1], '*');
								while($aSO21=SYS_FETCH($qSO21)) {
									$MENU_DESC = ($aSO21['SHORT_NAME'] ? $aSO21['SHORT_NAME'] : $aSO21['NAMA_BRG']);
									$aKOLOM = [$aSO21['NAMA_BRG'], number_format($aSO21['HARGA'], 0, '.', '.'), $aSO21['JML'], number_format($aSO21['JML_HARGA'], 0, '.', '.')];
									$dataAttr = "data-id='{$aSO21['SO2_ID']}' data-nama='{$MENU_DESC}' data-harga='{$aSO21['HARGA']}' data-jml='{$aSO21['JML']}' data-jmlharga='{$aSO21['JML_HARGA']}'";
									$cHREFF="<a href='#' class='edit-row' {$dataAttr} data-toggle='modal' data-target='#editModal'>";
								// echo '<td style="font-size: 0.85em; max-width:110px; white-space: normal; overflow-wrap:break-word;">'.$cINV_NAME.'</td>';

									TDETAIL($aKOLOM, [0,1,1,1], '*', [$cHREFF, '', '', ''], _aSTYLE:['style="font-size: 0.85em; max-width:110px; white-space: normal; overflow-wrap:break-word;"', '', '', '']);
								}
							eTABLE();
							echo '<hr style="height: 4px; background-color: #000; border: none;">';
							LABEL([6,6,6,6], '900', 'Total');
								$qSO1=OpenTable('So1', "SO_VOID=0 and TABLE_CODE='$cTABLE' and FAKTUR='' and APP_CODE='$cAPP_CODE'");
								$aSO1=SYS_FETCH($qSO1);
								$NILAI = $aSO1['NILAI'] ?? 0;
								$cNILAI = CVR($NILAI);
								echo '<label id="ttl" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-label-900" style="text-align:right; font-size:40px; font-weight:bold;">'.$cNILAI.'</label>';
							echo '</div>';
							echo '<div class="col-lg-9 col-md-9 col-xs-12 col-sm-9"></div>';
							
							echo '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">';
								echo '<hr style="height: 4px; background-color: #000; border: none;">';
								if($nTPAYMENT > 0) {
									LABEL([8,8,8,8], '700', $cPAYMENT);
									echo '<select style="height: 50px;" name="PAYMENT_TYPE" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-label-900 selectpicker">';
										echo "<option value=''  >CASH</option>";
										// $qPAYMENT=OpenTable('TbPayType');
										while($aPAYMENT=SYS_FETCH($qPAYMENT)){
											echo "<option value='$aPAYMENT[B_CODE]'  >$aPAYMENT[B_NAME]</option>";
										}
									echo '</select>';
								}
								CLEAR_FIX();
							$NO_TA = $aSO1['REC_ID'] ?? '';
 						?>
						<div class="row" style="margin-top: 40px;">
							<div class="col-lg-6">
								<input type="button" class="btn btn-dark btn-lg" 
								style="font-size: 30px; padding: 10px 20px; color: blue;" 
								value="D o n e" 
								onclick="submitWithPayment()">
							</div>
							<!-- <div class="col-lg-6">
								<input type="button" class="btn btn-success btn-lg" 
								style="font-size: 30px; padding: 10px 20px;" 
								value="Print" onclick="printDiv()"/>
							</div> -->
							<!-- <div class="col-lg-6">
								<input type="button" class="btn btn-success btn-lg" 
								style="font-size: 30px; padding: 10px 20px;" 
								value="Print"  onclick="printNOTA('<?php /* echo $NO_TA */ ?>')"/>
							</div> -->
						</div>
					</div>
 				</section>
<!-- print nota				 -->
				<div class="clearfix"></div>
				<div style="height: 2em;"></div>

				<?php 
					if (IS_LOCALHOST()) 
						echo '<div id="nota" class="col-lg-2 col-md-3 col-sm-3 col-xs-8">';
					else echo '<div id="nota" class="col-lg-2 col-md-3 col-sm-3 col-xs-8" style="display: none;">';
				?>
					<img class="img-responsive" src="<?php echo $cLOGO ?>" style="height: 5em; width: 5em; display: block; margin: auto;">

					<h5 style="text-align: center; margin-top: 0.5em; margin-bottom: 0.3em;"><?php echo $cCO1 ?></h5>
					<h5 style="text-align: center; letter-spacing: -0.5px; margin-top: 0.3em; margin-bottom: 0.3em;"><?php echo $cCO2 ?></h5>
					<h5 style="text-align: center; letter-spacing: -0.5px; margin-top: 0.3em; margin-bottom: 0.3em;"><?php echo $cCO3 ?></h5>
<?php							
					$qSO21=OpenTable('SO21', "C.SO_VOID=0 and TABLE_CODE='$cTABLE' and C.FAKTUR='' and C.APP_CODE='$cAPP_CODE'");
					TABLE('item_nota', _PAGING:'*', _SEARCHING:'*');
					// THEAD($aHEAD,[], [0,1,1,1]);
						$NILAI = 0;
						while($aSO21=SYS_FETCH($qSO21)) {
							$NILAI = $aSO21['NILAI'] ?? 0;
							$aKOLOM = [$aSO21['NAMA_BRG'], CVR($aSO21['HARGA']), $aSO21['JML'], CVR($aSO21['JML_HARGA'])];
							echo '<tr>';
								$cINV_NAME = $aSO21['NAMA_BRG'];
								echo '<td style="font-size: 0.85em; max-width:110px; white-space: normal; overflow-wrap:break-word;">'.$cINV_NAME.'</td>';
								// echo '<td style="text-align: right; font-size: 0.85em;">'.CVR($aSO21['HARGA']).'</td>';
								echo '<td style="text-align: right; font-size: 0.85em;">x'.CVR($aSO21['JML']).'</td>';
								echo '<td style="text-align: right; font-size: 0.85em;">'.CVR($aSO21['JML_HARGA']).'</td>';
							echo '</tr>';
						}
					eTABLE();
					echo '<hr style="height: 4px; background-color: #000; border: none;">';
					LABEL([4,4,4,4], '700', '');
						$cNILAI = CVR($NILAI);
						echo '<label id="ttl" class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="text-align:right; font-size:30px; font-weight:bold;">'.$cNILAI.'</label>';
?>
				</div>
			</section>

			<div class="modal" id="editModal" tabindex="-1" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated fadeIn" style="margin-top: 8em;">
					<form method="POST" action="?_a=DEL_SO2&_t=<?php echo $cTABLE?>&_o=<?php echo $cORDER?>">
						<div class="modal-content">
							<div class="modal-header">
							<h5 class="modal-title" id="editModalLabel">Edit Data Pesanan</h5>
							<!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
							</div>
							<div class="modal-body">
							<input type="hidden" name="id" id="edit-id">
							<div class="mb-5">
								<label for="edit-nama" class="form-label">Nama Barang</label>
								<input type="text" class="form-control" name="nama" id="edit-nama">
							</div>
							<div class="mb-3">
								<label for="edit-harga" class="form-label">Harga</label>
								<input type="number" class="form-control" name="harga" id="edit-harga">
							</div>
							<div class="mb-3">
								<label for="edit-jml" class="form-label">Jumlah</label>
								<input type="number" class="form-control" name="jml" id="edit-jml">
							</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">Delete</button>
								<input type="button"  style="width:50%" class="btn btn-orange btn-lg" value="Cancel" data-dismiss="modal">
							</div>
						</div>
					</form>
				</div>
			</div>
  		</div>
 		<?php	require_once("js_framework.php");	?>
 		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

<script>
function printNOTA(_NOTA) {
	let start = Date.now();
	
    const TA_BLE = "<?php echo $cTABLE ?>";
    const ORDER_TYPE = "<?php echo $cORDER ?>";
	const CO1 = "<?php echo $cCO1 ?>";
	console.log(_NOTA)
	window.location.href = "rawbt://print?text=" + CO1
}

function printDiv() {
    const divContents = document.getElementById("nota").innerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print</title></head><body>');
    printWindow.document.write(divContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}

function submitWithPayment() {
	let PTYPE = "";
	if(document.querySelector('[name="PAYMENT_TYPE"]') != null) {
		const paymentType = document.querySelector('[name="PAYMENT_TYPE"]').value;
		PTYPE = encodeURIComponent(paymentType);
	}
    const table = "<?php echo $cTABLE ?>";
    const order = "<?php echo $cORDER ?>";

    window.location.href = `pos_entry.php?_a=DONE&_t=${table}&_o=${order}&PAYMENT_TYPE=${PTYPE}`;
}

function ADD_NOTA(kode, nama, qty, total) {
  const tbody = document.querySelector('#item_nota tbody');
  const row = document.createElement('tr');

  row.innerHTML = `
    <td style="font-size: 0.85em; max-width:110px; white-space: normal; overflow-wrap:break-word;">${nama}</td>
    <td style="text-align: right; font-size: 0.85em;">x ${qty}</td>
    <td style="text-align: right; font-size: 0.85em;">${Number(total).toLocaleString('id-ID')}</td>
  `;

  tbody.appendChild(row);
}

window.onPrinterStatus = function(status) {
    console.log("Printer status dari Flutter:", status);
    // Bisa update UI, misalnya:
    document.getElementById("printer-status").innerText = 
      status === "connected" ? "Printer siap ✅" : "Printer tidak terhubung ❌";
  };

</script>


	</body>
</html>

<?php
	break;

case 'DEL_SO2': 
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$REC_ID2= $_POST['id'];
		$ORDER = $_GET['_o'];
		$TABLE = $_GET['_t'];
		// $nama  = $_POST['nama'];
		// $harga = $_POST['harga'];
		// $jml   = $_POST['jml'];
		$nNILAI = $jNILAI = 0;
		$qSO2=OpenTable('So2', "REC_ID='$REC_ID2'");
		if($aSO2= SYS_FETCH($qSO2)) {
			$KEY2 = $aSO2['SO1_ID'];
			$nSO2_NILAI = $aSO2['JML'] * $aSO2['HARGA'];
			$qSO1=OpenTable('So1', "REC_ID='$KEY2'");
			$nNILAI = 0;	$KEY1='';
			if($aSO1= SYS_FETCH($qSO1)) {
				$KEY1 = $aSO1['REC_ID'];
				$nNILAI = $aSO1['NILAI'];
			}
			$jNILAI = ($nNILAI>$nSO2_NILAI ? $nNILAI-$nSO2_NILAI : 0);
			RecUpdate('So1', ['NILAI', 'SO_NO'], [$jNILAI, 0], "REC_ID='$KEY1'");
		}
		RecDelete('So2', "REC_ID='$REC_ID2'");
		header("Location: pos_entry.php?_t=".$TABLE."&_o=".$ORDER);
	}
	break;
case 'x': 
    $cMENU=(isset($_GET['_m']) ? $_GET['_m'] : '');
	$cMN_CODE=(isset($_GET['_c']) ? $_GET['_c'] : '');
	$cTABLE=(isset($_GET['_t']) ? $_GET['_t'] : '');
	$nPRICE=0;
    if ($cMENU) {
		$qMENU=OpenTable('Invent', "APP_CODE='$cAPP_CODE' and KODE_BRG='$cMN_CODE'");
		$aMENU=SYS_FETCH($qMENU);
		$nPRICE = $aMENU['MENU_PRICE'];
		$cREC_ID= NowMSecs();
		$q_SO=OpenTable('So1', "SO_VOID=0 and TABLE_CODE='$cTABLE' and FAKTUR='' and APP_CODE='$cAPP_CODE'");
        $nSO1=SYS_ROWS($q_SO);
        if ($nSO1) {
			$aSO1=SYS_FETCH($q_SO);
			$cREC_ID = $aSO1['REC_ID'];
			$jPRICE = $aSO1['NILAI'] + $nPRICE;
			RecUpdate('So1', ['NILAI'], [$jPRICE], "REC_ID='$cREC_ID'");
		} else {
            RecCreate('So1', ['REC_ID', 'TABLE_CODE', 'TANGGAL', 'JT_TMP', 'NILAI', 'APP_CODE'], [$cREC_ID, $cTABLE, date('Y-m-d'), date('Y-m-d'), $nPRICE, $cAPP_CODE]);
        }
    	RecCreate('So2', ['REC_ID', 'SO1_ID', 'KODE_BRG', 'HARGA', 'JML', 'JML_HARGA'], [NowMSecs(), $cREC_ID, $cMN_CODE, $nPRICE, 1, $nPRICE]);
        // MSG_INFO(" Klik menu : ".$cMENU." telah ditambahkan : ".$cMN_CODE);
    }
	header('location:pos_entry.php?_t='.$cTABLE);
	break;

case 'ON_BEHALF': 
    $cDATA=(isset($_GET['_d']) ? $_GET['_d'] : '');
	$cORDER=(isset($_GET['_o']) ? $_GET['_o'] : '');
	if($cDATA) {
		$cTABLE=(isset($_GET['_t']) ? $_GET['_t'] : '');
		$cREC_ID= NowMSecs();
		$q_SO=OpenTable('So1', "SO_VOID=0 and TABLE_CODE='$cTABLE' and FAKTUR='' and APP_CODE='$cAPP_CODE'");
		$nSO1=SYS_ROWS($q_SO);
		if ($nSO1) {
			$aSO1=SYS_FETCH($q_SO);
			$cREC_ID = $aSO1['REC_ID'];
			RecUpdate('So1', ['CUST_NAME'], [$cDATA], "REC_ID='$cREC_ID'");
		} else {
            RecCreate('So1', ['REC_ID', 'TABLE_CODE', 'TEAM', 'TANGGAL', 'JT_TMP', 'CUST_NAME', 'APP_CODE'], [$cREC_ID, $cTABLE, $cORDER, date('Y-m-d'), date('Y-m-d'), $cDATA, $cAPP_CODE]);
		}
		$cSALES_ORDER=$cREC_ID;
	}
	break;
case 'PRINT': 
	$cTABLE=(isset($_GET['_t']) ? $_GET['_t'] : '');
	$cORDER=(isset($_GET['_o']) ? $_GET['_o'] : '');
	$q_SO=OpenTable('So1', "SO_VOID=0 and TABLE_CODE='$cTABLE' and FAKTUR='' and APP_CODE='$cAPP_CODE'");
	$nSO1=SYS_ROWS($q_SO);
	if ($nSO1) {

		$cFORM		= S_PARA('FORMAT_BILL', 'BILL');
		$cFILE_PDF = 'data/invoice/'. $cAPP_CODE.'_BILL.pdf';
		$qTB_BILL=OpenTable('TbBillPrintHdr', "PRNTR_CODE='$cFORM' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qTB_BILL)==0)  {
			$qTB_BILL=OpenTable('TbBillPrintHdr', "PRNTR_CODE='$cFORM' and APP_CODE='' and REC_ID not in ( select DEL_ID from logs_delete)");
			if(SYS_ROWS($qTB_BILL)==0)  {
				MSG_INFO('No record');
				return;
			}
			$cAPP_CODE='';
		}
		$aREC_TB_BILL=SYS_FETCH($qTB_BILL);

		$cPAPER = 'C7';
		if($aREC_TB_BILL['PAPER_SIZE']>'')   $cPAPER = $aREC_TB_BILL['PAPER_SIZE'];
		$cORIEN = 'P';
		if($aREC_TB_BILL['ORIENTATION']>'')   $cORIEN = $aREC_TB_BILL['ORIENTATION'];
		$pdf=new FPDF($cORIEN, 'mm', $cPAPER);
		$R_fdf=PRINT_HDR($pdf, $cFORM);
		// $R_fdf->Text(GET_FORMAT($cFORM, 'TGGL_LEFT')+27, GET_FORMAT($cFORM, 'KONST4_ROW'), $sPERIOD1);
		$cFONT_STYLE='';	$cFONT_NAME ='Arial';	$nFONT_SIZE = 12;
		$cFONT_CODE = GET_FORMAT($cFORM, 'DETAIL_DTA_FONT_CODE');

		$aSO1=SYS_FETCH($q_SO);
		$cREC_ID = $aSO1['REC_ID'];
		$qQUERY = OpenTable('So2', "SO1_ID='$cREC_ID' and REC_ID not in ( select DEL_ID from logs_delete )");
		while($aSo2=SYS_FETCH($qQUERY)) {
		}
		$R_fdf->Output('F', $cPDF_PERSON, true);
		$R_fdf->Close();
	}
	MSG_INFO('Print ! > '.$cORDER);
	// header('location:pos_print.php?_SO='.$cORDER);
	break;
case 'DONE': 
	$cTABLE=(isset($_GET['_t']) ? $_GET['_t'] : '');
	$cORDER=(isset($_GET['_o']) ? $_GET['_o'] : '');
	$cCHANNEL = (isset($_GET['PAYMENT_TYPE']) ? $_GET['PAYMENT_TYPE'] : '');
	$nLAST_INVOICE = 0;
	$qQUERY=OpenTable('TrSalesHdr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', "NOTA desc limit 1");
	$aINVOICE=SYS_FETCH($qQUERY);
	$INVOICE = $aINVOICE['NOTA'] ?? 0;
	$nINVOICE = intval($INVOICE) + 1;
	$cINVOICE = str_pad($nINVOICE, 6, '0', STR_PAD_LEFT);
	$q_SO=OpenTable('So1', "SO_VOID=0 and TABLE_CODE='$cTABLE' and FAKTUR='' and APP_CODE='$cAPP_CODE'");
	$nSO1=SYS_ROWS($q_SO);
	if ($nSO1) {
		$aSO1=SYS_FETCH($q_SO);
		$cREC_ID = $aSO1['REC_ID'];
		RecCreate('TrSalesHdr', ['NOTA', 'TEAM', 'TGL_JUAL', 'TGL_BAYAR', 'NILAI', 'FKT_LUNAS', 'ENTRY', 'REC_ID', 'APP_CODE'], 
			[$cINVOICE, $cORDER, date('Y-m-d'), date('Y-m-d'), $aSO1['NILAI'], 1, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		$qQUERY = OpenTable('So2', "SO1_ID='$cREC_ID' and REC_ID not in ( select DEL_ID from logs_delete )");
		while($aSo2=SYS_FETCH($qQUERY)) {
			RecCreate('TrSalesDtl', ['NOTA', 'KODE_BRG', 'HARGA', 'QUANTITY', 'ENTRY', 'REC_ID', 'APP_CODE'], 
				[$cINVOICE, $aSo2['KODE_BRG'], $aSo2['HARGA'], $aSo2['JML'], $cUSERCODE, NowMSecs(), $cAPP_CODE]);
			$nQTY=$aSo2['JML'];
			$nVALUE = $aSo2['JML'] * $aSo2['HARGA'];
			$qSUMM=OpenTable('SummSalesQty', "DATE_SLS='".date('Y-m-d')."' and INV_CODE='$aSo2[KODE_BRG]' and APP_CODE='$cAPP_CODE'");
			$nSUMM=SYS_ROWS($qSUMM);
			if ($nSUMM) {
				$aQSUMM=SYS_FETCH($qSUMM);
				$cREC_SUMM = $aQSUMM['REC_ID'];
				$nQTY = $aQSUMM['QTY'] + $aSo2['JML'];
				$nINVOICE = $aQSUMM['INVOICE'] + 1;
				RecUpdate('SummSalesQty', ['QTY', 'INVOICE', 'SLS_VAL'], [$nQTY, $nINVOICE, $nVALUE], "REC_ID='$cREC_SUMM'");
			} else {
				RecCreate('SummSalesQty', ['DATE_SLS', 'INV_CODE', 'QTY', 'SLS_VAL', 'INVOICE', 'REC_ID', 'APP_CODE'], 
					[date('Y-m-d'), $aSo2['KODE_BRG'], $nQTY, $nVALUE, 1, NowMSecs(), $cAPP_CODE]);
			}
		}

		$nVALUE = $aSO1['NILAI'];
		$qSUMM=OpenTable('SummSalesValue', "SALES_DATE='".date('Y-m-d')."' and SALES_TYPE='$cORDER' and SALES_MAN='$cUSERCODE' and APP_CODE='$cAPP_CODE'");
		$nSUMM=SYS_ROWS($qSUMM);
		if ($nSUMM) {
			$aQSUMM=SYS_FETCH($qSUMM);
			$cREC_SUMM = $aQSUMM['REC_ID'];
			$nVALUE += $aQSUMM['SALES_VALUE'];
			RecUpdate('SummSalesValue', ['SALES_VALUE', 'APP_CODE'], [$nVALUE, $cAPP_CODE], "REC_ID='$cREC_SUMM'");
		} else {
			RecCreate('SummSalesValue', ['SALES_DATE', 'SALES_MAN', 'SALES_VALUE', 'SALES_TYPE', 'REC_ID', 'APP_CODE'], 
				[date('Y-m-d'), $cUSERCODE, $nVALUE, $cORDER, NowMSecs(), $cAPP_CODE]);
		}

		$qQUERY = OpenTable('TrReceiptHdr', "APP_CODE='$cAPP_CODE'", '', "NO_TRM desc limit 1");
		$aRECEIPT = SYS_FETCH($qQUERY);
		$RECEIPT_NO = $aRECEIPT['NO_TRM'] ?? 0;
		$nRECEIPT_NO = intval($RECEIPT_NO) + 1;
		$cRECEIPT_NO = str_pad($nRECEIPT_NO, 6, '0', STR_PAD_LEFT);
		RecCreate('TrReceiptHdr', ['NO_TRM', 'TGL_BAYAR', 'BANK', 'ENTRY', 'REC_ID', 'APP_CODE'], 
			[$cRECEIPT_NO, date('Y-m-d'), $cCHANNEL, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		RecCreate('TrReceiptDtl', ['NO_TRM', 'NO_FAKTUR', 'NILAI', 'ENTRY', 'REC_ID', 'APP_CODE'], 
			[$cRECEIPT_NO, $cINVOICE, $aSO1['NILAI'], $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		RecUpdate('So1', ['FAKTUR', 'SO_NO'], [$cINVOICE, 0], "REC_ID='$cREC_ID'");

		// if ($cCHANNEL) {
		// 	RecCreate('TrReceiptBank', ['NO_TRM', 'DUE_DATE', 'ENTRY', 'REC_ID', 'APP_CODE'], 
		// 		[$cRECEIPT_NO, date('Y-m-d'), $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		// }
	}
	APP_LOG_ADD($cHEADER, 'add : ' . $cINVOICE);
	header('location:pos.php?_o='.$cORDER);
	break;
}
?>

<script>
function ON_BEHALF_OF(_TBL, _ORDER) {
	var cNAME = document.getElementById('GUEST_NAME').value;
  fetch("pos_entry.php?_a=ON_BEHALF&_t="+_TBL+"&_o="+_ORDER+"&_d=" + encodeURIComponent(cNAME))
  .then(response => response.text())
  .then(data => {
    console.log("Respons dari PHP:", data);
  });
}


document.querySelectorAll('.portfolio-filter a').forEach(function(btn) {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    const filterClass = this.getAttribute('data-filter');

    document.querySelectorAll('.portfolio-item').forEach(function(item) {
      if (filterClass === '*' || item.classList.contains(filterClass.substring(1))) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  });
});

$('#editModal').on('show.bs.modal', function (e) {
	const clickedElement = $(e.relatedTarget),
		modalBody =$(this).find('.modal-body')
	modalBody.find('input#edit-id').val(clickedElement.data('id'))
	modalBody.find('input#edit-nama').val(clickedElement.data('nama'))
	modalBody.find('input#edit-harga').val(clickedElement.data('harga'))
	modalBody.find('input#edit-jml').val(clickedElement.data('jml'))
	//console.log(clickedElement.data('nama'))
})

// modal
// document.addEventListener('DOMContentLoaded', function () {
//   document.querySelectorAll('.edit-row').forEach(function (el) {
//     el.addEventListener('click', function () {
//       document.getElementById('edit-nama').value = this.dataset.nama;
//       document.getElementById('edit-harga').value = this.dataset.harga;
//       document.getElementById('edit-jml').value = this.dataset.jml;
//       document.getElementById('edit-id').value = this.dataset.id;
//     });
//   });
// });

</script>
