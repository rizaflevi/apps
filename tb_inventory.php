<?php
//	tb_inventory.php //

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cAPP_CODE = $_SESSION['data_FILTER_CODE'];	$cUSERCODE = $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Tabel - Produk.pdf';
if (IS_RESTO($cAPP_CODE))	$cHELP_FILE = 'Doc/Tabel - Menu.pdf';
$lSALES=IS_TRADING($cAPP_CODE) || IS_OUTSOURCING_WITH_MATERIAL($cAPP_CODE);
$lHOTEL=IS_HOTEL($cAPP_CODE);
if($lSALES)	$cHELP_FILE = 'Doc/Tabel - Inventory.pdf';

$vwGENERAL = TRUST($cUSERCODE, 'INVENTORY_VW_GENERAL');	
$updGENERAL = TRUST($cUSERCODE, 'INVENTORY_UP_GENERAL');	
$vwDETAIL = TRUST($cUSERCODE, 'INVENTORY_VW_DETAIL');	
$updDETAIL = TRUST($cUSERCODE, 'INVENTORY_UP_DETAIL');	

$nGROUP_INVENTORY = 0;
$q_BGROUP=OpenTable('TbInvGroup', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
$nGROUP_INVENTORY = SYS_ROWS($q_BGROUP);
$cFILTER_GRUP=(isset($_GET['_g']) ? $_GET['_g'] : '');

$nDIV_INVENTORY = 0;
$q_DIV_BRG=OpenTable('TbiDivision');
$nDIV_INVENTORY = SYS_ROWS($q_DIV_BRG);

$cREC_SYS 		= S_PARA('JNS_PRSHN',' ');
$nMULTI_UNIT	= (S_PARA('INVENTORY_UNIT','4')=='4' ? 0 : 1);

$cHEADER 		= S_MSG('F048','Tabel Persediaan / Inventory');
$cKODE_INV		= S_MSG('F051','Kode Persediaan');
$cNO_ACTIVE		= S_MSG('F026','Non Aktif');
$cNAMA_BRG 		= S_MSG('F052','Nama Persediaan');
$cSHORT_NM		= S_MSG('TK11','Nama Pendek');
$cHRG_JUAL 		= S_MSG('F053','Harga Jual');
$cSAT_JUAL		= S_MSG('F054','Satuan Jual');
$cHRG_BELI		= S_MSG('F055','Harga Beli');
$cSAT_BELI		= S_MSG('F056','Satuan Beli');
$cINV_WEIGHT	= S_MSG('TI74','Berat');
$cKODE_VENDOR	= S_MSG('F058','Supplier');
$cINV_VOLUME	= S_MSG('TI76','Volume');
$cKELOMPOK		= S_MSG('F060','Kelompok');

$cDATA_UMUM		= S_MSG('F024','Data Umum');
$cDETIL			= S_MSG('F010','Detil');
$cPOT_CASH		= S_MSG('TI39','Potongan kontan');
$cMAX_STOCK		= S_MSG('F073','Stock Maximum');
$cMIN_STOCK		= S_MSG('F074','Stock Minimum');
$cHRG_AKHIR		= S_MSG('F075','Harga Beli Terakhir');
$cINV_ISI		= S_MSG('F057','Isi per Carton');
$cGBR_INV		= S_MSG('F011','Gbr Anggota');

$cDIV_BRG		= S_MSG('TD01','Kode Divisi');
$cFOTO_INV		= S_MSG('F069','Foto Inv.');

$cTTIP_KODE		= S_MSG('F062','Setiap Persediaan di beri kode untuk memudahkan meng akses');
$cTTIP_NAMA		= S_MSG('F063','Nama persediaan yang menjelaskan persediaan itu sendiri');
$cTTIP_HRGJ		= S_MSG('F064','Harga jual persediaan, diisi dengan harga sesuai satuan jual nya, mis hrg 1 dus');
$cTTIP_HRGB		= S_MSG('F066','Harga beli ke supplier, sesuai satuan beli nya');
$cTTIP_MAX		= S_MSG('F077','Stock maximum untuk menentukan batas pemesanan');
$cTTIP_MIN		= S_MSG('F078','Stock minimum sebelum re-order');
$cTTIP_AKH		= S_MSG('F075','Harga beli terakhir');
$cTTIP_BRT		= S_MSG('TI75','Berat inventori per satuan unit');
$cTTIP_VOL		= S_MSG('TI76','Volume inventori per satuan unit');
$cTTIP_POTD		= S_MSG('TI40','Potongan pembelian kontan / cashback');

$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
$cADD_REC		= S_MSG('KA11','Add new');
// $cADD_REC		= S_MSG('F06A','Tambah Persediaan');
$cEDIT_TBL		= S_MSG('F06B','Edit Tabel Persediaan');
$cDAFTAR		= S_MSG('F146','Daftar Persediaan');

$cSAVE_DATA		= S_MSG('F301','Save');
$cCLOSE_DATA	= S_MSG('F302','Close');
// $cFOLDER_IMG	= S_PARA('FTP_INVENT_FOLDER', '/home/riza/test/data/images_inventory/');
// if (IS_LOCALHOST()) {
	$cFOLDER_IMG = 'data/images_inventory/';
// }
$cACTION=(isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();

		$q_INVENT=OpenTable('Invent', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)",'',  "NO_ACTIVE, KODE_BRG");
		DEF_WINDOW($cHEADER);
			$aACT = (TRUST($cUSERCODE, 'INVENTORY_1ADD')==1 ? ['<a href="?_a='. md5('CREATE_INV'). '"><i class="fa fa-plus-square"></i>'. $cADD_REC.'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						$aHEAD=[$cKODE_INV, $cNAMA_BRG, $cHRG_JUAL];
						$aALIGN=[0,0,1];
						if($lSALES)	{
							array_push($aHEAD, $cHRG_BELI);
							array_push($aALIGN, 1);
						}
						THEAD($aHEAD, '', $aALIGN);
						echo '<tbody>';           
							while($a_INVENT=SYS_FETCH($q_INVENT)) {
								$cHREFF="<a href='?_a=".md5('UPD_INVENT')."&_c=".md5($a_INVENT['KODE_BRG'])."'>";
								$aCOL=[$a_INVENT['KODE_BRG'], DECODE($a_INVENT['NAMA_BRG']), CVR($a_INVENT['HARGA_JUAL'])];
								$aHREFF=[$cHREFF, $cHREFF, ''];
								if($lSALES)	{
									array_push($aCOL, CVR($a_INVENT['HARGA_BELI']));
									array_push($aHREFF, '');
								}
								TDETAIL($aCOL, $aALIGN, '', $aHREFF);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('CREATE_INV'):
		$can_DETAIL = (TRUST($cUSERCODE, 'INVENTORY_TAB_DTL_VI')==1 || TRUST($cUSERCODE, 'INVENTORY_TAB_DTL_UP')==1 ? 1 : 0);
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah','', $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_INV);
					INPUT('text', [3,3,3,6], '900', 'ADD_KODE_BRG', '', 'focus', '', '', 10, '', 'fix');
					LABEL([4,4,4,6], '700', $cNAMA_BRG);
					INPUT('text', [6,6,6,6], '900', 'ADD_NAMA_BRG', '', '', '', '', 100, '', 'fix');
					LABEL([4,4,4,6], '700', $cSHORT_NM);
					INPUT('text', [3,3,4,6], '900', 'ADD_SHORT_NAME', '', '', '', '', 13, '', 'fix');
					LABEL([4,4,4,6], '700', $cHRG_JUAL);
					INPUT('number', [2,2,2,6], '900', 'ADD_HRGJ_CTN', '', '', 'fdecimal', 'right', 0, '', 'fix');
					if($lSALES) {
						LABEL([2,2,2,6], '700', $cSAT_JUAL, '', 'right');
						INPUT('text', [2,2,2,6], '900', 'ADD_UNIT_JUAL', '', '', '', '', 20, '', 'fix');
						LABEL([4,4,4,6], '700', $cHRG_BELI);
						INPUT('number', [2,2,2,6], '900', 'ADD_HRG_CTN', '', '', 'fdecimal', 'right', 0, '', '');
						LABEL([2,2,2,6], '700', $cSAT_BELI, '', 'right');
						INPUT('text', [2,2,2,6], '900', 'ADD_UNIT_BELI', '', '', '', '', 20, '', 'fix');
					}
					if($nGROUP_INVENTORY > 0) {
						LABEL([4,4,4,6], '700', $cKELOMPOK);
						echo '<select name="ADD_GROUP_INV" class="col-sm-4 form-label-900">';
							$REC_GROUP=OpenTable('TbInvGroup', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
							echo "<option value=' '  > </option>";
							while($aREC_GR_DATA=SYS_FETCH($REC_GROUP)){
								echo "<option value='$aREC_GR_DATA[KODE_GRP]'  >$aREC_GR_DATA[NAMA_GRP]</option>";
							}
						echo '</select>';
						CLEAR_FIX();
					}
					echo '<h4> </br></h4>';
					$aTAB=['INV_IMG'];
					$aICON=['fa-cog'];
					$aCAPTION=[$cFOTO_INV];
					if($vwGENERAL || $updGENERAL) {
						array_push($aTAB, 'GENERAL');
						array_push($aICON, 'fa-home');
						array_push($aCAPTION, $cDATA_UMUM);
					}  
					if($vwDETAIL || $updDETAIL) {
						array_push($aTAB, 'STOCK');
						array_push($aICON, 'fa-home');
						array_push($aCAPTION, $cDETIL);
					}
					TAB($aTAB, $aICON, $aCAPTION);
 
					// TAB(['GENERAL', 'STOCK', 'INV_IMG'], ['fa-user', 'fa-home', 'fa-cog'], [$cDATA_UMUM, $cDETIL, $cFOTO_INV]);

					if($vwGENERAL or $updGENERAL) {
						echo '<div class="tab-content primary">
							<div class="tab-pane fade" id="GENERAL">';
							if($nMULTI_UNIT==1) {
								LABEL([4,4,4,6], '700', $cINV_ISI);
								INPUT('number', [1,1,2,6], '900', 'ADD_INV_ISI', '', '', '', '', 0, '', 'fix');
							}
							LABEL([4,4,4,6], '700', $cKODE_VENDOR);
							echo '<select name="ADD_KODE_VND" class="col-sm-4 form-label-900">';
								echo "<option value=' '  > </option>";
								$q_VENDOR=OpenTable('Vendor', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
								while($r_VENDOR=SYS_FETCH($q_VENDOR)){
									echo "<option value='$r_VENDOR[KODE_VND]'  >$r_VENDOR[NAMA_VND]</option>"; 
								}
							echo '</select>';
						CLEAR_FIX();

						if($nDIV_INVENTORY > 0) {
							LABEL([3,3,3,6], '700', $cDIV_BRG);
							echo '<select name="ADD_KODE_DVB" class="col-sm-4 form-label-900">';
								$q_DIV_BRG=OpenTable('TbiDivision', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
								while($r_DIV_BRG=SYS_FETCH($q_DIV_BRG)){
									echo "<option value='$r_DIV_BRG[KODE_DVB]'  >$r_DIV_BRG[NAMA_DVB]</option>";
								}
							echo '</select>';
							CLEAR_FIX();
						}
					}
				eTDIV();
				echo '<div class="tab-pane fade" id="STOCK">';
					LABEL([3,3,3,6], '700', $cMAX_STOCK);
					INPUT('text', [2,2,2,6], '900', 'UPD_MAXCSTOCK', '', '', '', '', 0, '', 'fix', $cTTIP_MAX);
					LABEL([3,3,3,6], '700', $cMIN_STOCK);
					INPUT('text', [2,2,2,6], '900', 'UPD_MINCSTOCK', '', '', '', '', 0, '', 'fix', $cTTIP_MIN);
					LABEL([3,3,3,6], '700', $cHRG_AKHIR);
					INPUT('number', [2,2,2,6], '900', 'UPD_LAST_PRICE', '', '', '', '', 0, '', 'fix', $cTTIP_AKH);
					LABEL([3,3,3,6], '700', $cINV_WEIGHT);
					INPUT('number', [2,2,2,6], '900', 'UPD_LAST_PRICE', '', '', '', '', 0, '', 'fix');
				eTDIV();
?>
				<div class="tab-pane fade in active" id="INV_IMG">
					<label class="form-label" for="field-1">Profile Image</label>
					<span class="desc"></span>
					<img class="img-responsive" id="preview_IMG" src="data/images/no.jpg" alt="" style="max-width:220px;">
					<div class="controls">
						<input name="ADD_INV_IMAGE" type="file" accept="image/*" class="form-control" onchange="previewIMAGE(event)">
					</div>
				</div>
<?php
				SAVE($cSAVE_DATA);
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

case 'tambah':
	$cKODE_BR = ENCODE($_POST['ADD_KODE_BRG']);
	if($cKODE_BR=='') {
		MSG_INFO(S_MSG('F083','Kode Persediaan tidak boleh kosong'));
		return;
	}
	$cNAMA_BR = ENCODE($_POST['ADD_NAMA_BRG']);
	if($cNAMA_BR=='') {
		MSG_INFO(S_MSG('F084','Nama Persediaan tidak boleh kosong'));
		return;
	}
	$cSHORT_NM = (isset($_POST['ADD_SHORT_NAME']) ? ENCODE($_POST['ADD_SHORT_NAME']) : '');
	$qQUERY=OpenTable('invent', "KODE_BRG='$cKODE_BR' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qQUERY)==0){
		$nINV_ISI = 1;
		if($nMULTI_UNIT==1) 	$nINV_ISI = isset($_POST['ADD_INV_ISI']) ? (int)$_POST['ADD_INV_ISI'] : 1;
		$SELL_PRICE = (isset($_POST['ADD_HRGJ_CTN']) ? $_POST['ADD_HRGJ_CTN'] : '');
		$PURC_PRICE = (isset($_POST['ADD_HRG_CTN']) ? $_POST['ADD_HRG_CTN'] : '');
		$nHRGJ_CTN 	= (int)str_replace(',', '', $SELL_PRICE);
		$nHRG_CTN 	= (int)str_replace(',', '', $PURC_PRICE);
	    $cVENDOR=(isset($_POST['ADD_KODE_VND']) ? $_POST['ADD_KODE_VND'] : '');
	    $cSALES_UNIT=(isset($_POST['ADD_UNIT_JUAL']) ? $_POST['ADD_UNIT_JUAL'] : '');
	    $cPURC_UNIT=(isset($_POST['ADD_UNIT_BELI']) ? $_POST['ADD_UNIT_BELI'] : '');
	    $cGROUP=(isset($_POST['ADD_GROUP_INV']) ? $_POST['ADD_GROUP_INV'] : '');
	    $cDIVISI=(isset($_POST['ADD_KODE_DVB']) ? $_POST['ADD_KODE_DVB'] : '');
		RecCreate('invent', ['KODE_BRG', 'NAMA_BRG', 'SHORT_NAME', 'SUPPLIER', 'HARGA_JUAL', 'HARGA_BELI', 'UNIT_JUAL', 'UNIT_BELI', 'INV_ISI', 'GROUP_INV', 'KODE_DVB', 'APP_CODE', 'ENTRY', 'REC_ID'], 
			[$cKODE_BR, $cNAMA_BR, $cSHORT_NM, $cVENDOR, $nHRGJ_CTN, $nHRG_CTN, $cSALES_UNIT, $cPURC_UNIT, $nINV_ISI, $cGROUP, $cDIVISI, $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		header('location:tb_inventory.php');
	} else {
		MSG_INFO(S_MSG('F084','Kode Persediaan sudah ada'));
		return;
	}
 	if(isset($_FILES['ADD_INV_IMAGE'])) {
		$cFILE_IMAGE = $cAPP_CODE.'_INV_'.$cKODE_BR.'.jpg';
		$cFILE_FOTO = $cFOLDER_IMG.$cFILE_IMAGE;

		$target_file = $cFOLDER_IMG . basename($_FILES["ADD_INV_IMAGE"]["name"]);
		$check = getimagesize($_FILES["ADD_INV_IMAGE"]["tmp_name"]);
		if($check) {
			if (move_uploaded_file($_FILES["ADD_INV_IMAGE"]["tmp_name"], $cFILE_FOTO)) {
				MSG_INFO("Gambar berhasil diupload.");
				echo "<img src='$cFILE_FOTO' alt='Gambar yang diupload' style='max-width:300px;'>";
			} else {
				MSG_INFO("Terjadi kesalahan saat mengupload gambar.");
			}
		} else {
			MSG_INFO("File bukan gambar.");
		}
	}
	APP_LOG_ADD($cHEADER, 'add : ' . $cKODE_BR);
	break;

	case md5('UPD_INVENT'):
		$can_DELETE = TRUST($cUSERCODE, 'INVENTORY_3DEL');
		$cINV_CODE = $_GET['_c'];
		$q_INVENT=OpenTable('Invent', "md5(KODE_BRG)='$cINV_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)",'',  "NO_ACTIVE, KODE_BRG");
		if($a_INVENT=SYS_FETCH($q_INVENT)){
			$KODE_INV = $a_INVENT['KODE_BRG'];
			$cFILE_FOTO_INV = 'data/images_inventory/'.$cAPP_CODE.'_INV_'.$KODE_INV.'.jpg';
			if(file_exists($cFILE_FOTO_INV)==0)	{
				$cFILE_FOTO_INV = "data/images/no.jpg";
			}
		} else {
			header('location:tb_inventory.php');
			return;
		}
		$q_INVEN3=OpenTable('TbiCashDisc', "md5(KODE_BRG)='$cINV_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$n_POT_CASH=0;
		if($REC_INVEN3 =SYS_FETCH($q_INVEN3)){
			$n_POT_CASH=$REC_INVEN3['CASH_DISK'];
		}
		$q_INVEN4=OpenTable('TbiVolWeight', "md5(KODE_BRG)='$cINV_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)",'',  "KODE_BRG");
		$n_INV_VOL =0;
		$n_INV_BRT =0;
		if($REC_INVEN4 =SYS_FETCH($q_INVEN4)){
			$n_INV_VOL=$REC_INVEN4['INV_VOL'];
			$n_INV_BRT=$REC_INVEN4['INV_WEIGHT'];
		}
		$qINV_MAX=OpenTable('TbiMaxMin', "md5(KODE_BRG)='$cINV_CODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)",'',  "KODE_BRG");
		$n_MAX =$n_MIN =0;
		if($REC_INVMAX =SYS_FETCH($qINV_MAX)){
			$n_MAX=$REC_INVMAX['MAX_STOCK'];
			$n_MIN=$REC_INVMAX['MIN_STOCK'];
		}
		DEF_WINDOW($cEDIT_TBL);
			$cACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DEL_INV').'&_id='. md5($a_INVENT['KODE_BRG']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, "?_a=upd__inv&_c=".$cINV_CODE, $cACT, $cHELP_FILE);
				LABEL([3,3,3,6], '700', $cKODE_INV);
				INPUT('text', [3,3,3,6], '900', 'UPD_KODE_BRG', $a_INVENT['KODE_BRG'], '', '', '', 0, 'disabled', '', $cTTIP_KODE);
				LABEL([2,2,2,6], '700', $cNO_ACTIVE, '', 'right');
				INPUT('checkbox', [1,1,1,1], '900', 'UPD_NO_ACTIVE', $a_INVENT['NO_ACTIVE']==1, '', '', '', 0, '', 'fix', '', $a_INVENT['NO_ACTIVE']==1);
				LABEL([3,3,3,6], '700', $cNAMA_BRG);
				INPUT('text', [6,6,6,6], '900', 'UPD_NAMA_BRG', DECODE($a_INVENT['NAMA_BRG']), 'focus', '', '', 100, '', 'fix', $cTTIP_NAMA);
				LABEL([3,3,3,6], '700', $cSHORT_NM);
				INPUT('text', [3,3,3,6], '900', 'UPD_SHORT_NM', DECODE($a_INVENT['SHORT_NAME']), '', '', '', 13, '', 'fix', $cTTIP_NAMA);
				LABEL([3,3,3,6], '700', $cHRG_JUAL);
				INPUT('text', [2,2,2,3], '900', 'EDIT_HRG_JUAL', $a_INVENT['HARGA_JUAL'], '', 'fdecimal', 'right', 0, '', 'fix', $cTTIP_HRGJ);
				if($lSALES) {
					LABEL([2,2,2,6], '700', $cSAT_JUAL, '', 'right');
					INPUT('text', [2,2,2,3], '900', 'EDIT_UNIT_JUAL', $a_INVENT['UNIT_JUAL'], '', '', 'right', 20, '', 'fix');
					LABEL([3,3,3,6], '700', $cHRG_BELI);
					INPUT('text', [2,2,2,3], '900', 'EDIT_HRG_BELI', $a_INVENT['HARGA_BELI'], '', 'fdecimal', 'right', 0, '', '', $cTTIP_HRGB);
					LABEL([2,2,2,6], '700', $cSAT_BELI, '', 'right');
					INPUT('text', [2,2,2,3], '900', 'EDIT_UNIT_BELI', $a_INVENT['UNIT_BELI'], '', '', '', 20, '', 'fix');
				}
				if($nGROUP_INVENTORY > 0) {
					LABEL([3,3,3,6], '700', $cKELOMPOK);
					echo '<select name="UPD_GROUP_INV" class="col-sm-5 form-label-900">';
						$q_BGROUP=OpenTable('TbInvGroup', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($r_BGROUP=SYS_FETCH($q_BGROUP)){
							if($a_INVENT['GROUP_INV']==$r_BGROUP['KODE_GRP']){
								echo "<option value='$r_BGROUP[KODE_GRP]' selected='$a_INVENT[GROUP_INV]' >$r_BGROUP[NAMA_GRP]</option>";
							} else {
								echo "<option value='$r_BGROUP[KODE_GRP]'  >$r_BGROUP[NAMA_GRP]</option>";
							}
						}
					echo '</select>';
				}
				CLEAR_FIX();

				TDIV();
					echo '<h4> </br></h4>';

					$aTAB=['upd_FOTO'];
					$aICON=['fa-cog'];
					$aCAPTION=[$cFOTO_INV];
					if($vwGENERAL || $updGENERAL) {
						array_push($aTAB, 'upd_UMUM');
						array_push($aICON, 'fa-home');
						array_push($aCAPTION, $cDATA_UMUM);
					}  
					if($vwDETAIL || $updDETAIL) {
						array_push($aTAB, 'upd_DETAIL');
						array_push($aICON, 'fa-cog');
						array_push($aCAPTION, $cDETIL);
					}
					TAB($aTAB, $aICON, $aCAPTION);
					// TAB(['upd_UMUM', 'upd_DETAIL', 'upd_FOTO'], ['fa-user', 'fa-home', 'fa-cog'], [$cDATA_UMUM, $cDETIL, $cFOTO_INV]);
					echo '<div class="tab-content primary">
						<div class="tab-pane fade" id="upd_UMUM">';
							if($nMULTI_UNIT==1) {
								LABEL([3,3,3,3], '700', $cINV_ISI);
								INPUT('text', [1,1,1,3], '900', 'UPD_INV_ISI', $a_INVENT['INV_ISI'], '', 'fdecimal', 'right', 0, '', 'fix');
							}
							LABEL([3,3,3,6], '700', $cKODE_VENDOR);
							echo '<select name="EDIT_SPL_CODE" class="col-sm-4 form-label-900">';
								echo "<option value=' '  > </option>";
								$qQUERY=OpenTable('Vendor', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
								while($aREC_SPL_CODE=SYS_FETCH($qQUERY)){
									if($a_INVENT['SUPPLIER'] == $aREC_SPL_CODE['KODE_VND']){
										echo "<option value='$aREC_SPL_CODE[KODE_VND]' selected='$a_INVENT[SUPPLIER]' >$aREC_SPL_CODE[NAMA_VND]</option>";
									} else {
									echo "<option value='$aREC_SPL_CODE[KODE_VND]'  >$aREC_SPL_CODE[NAMA_VND]</option>"; }
								}
							echo '</select>';
							CLEAR_FIX();

							if($nDIV_INVENTORY > 0) {
								LABEL([3,3,3,6], '700', $cDIV_BRG);
								echo '<select name="UPD_KODE_DVB" class="col-sm-5 form-label-900">';
									$qQUERY=OpenTable('TbiDivision', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
									while($r_DIV_BRG=SYS_FETCH($qQUERY)){
										if($a_INVENT['KODE_DVB']==$r_DIV_BRG['KODETIPE']){
											echo "<option value='$r_DIV_BRG[KODETIPE]' selected='$a_INVENT[KODE_DVB]' >$r_DIV_BRG[NAMA_DVB]</option>";
										} else {	echo "<option value='$r_DIV_BRG[KODETIPE]'  >$r_DIV_BRG[NAMA_DVB]</option>";
										}
									}
								echo '</select>';
							}
							CLEAR_FIX();
							LABEL([3,3,3,6], '700', $cINV_WEIGHT);
							INPUT('text', [2,2,2,3], '900', 'UPD_INV_WEIGHT', $n_INV_VOL, '', 'fdecimal', 'right', 0, '', 'fix', $cTTIP_BRT);
							LABEL([3,3,3,6], '700', $cINV_VOLUME);
							INPUT('text', [2,2,2,3], '900', 'UPD_INV_VOL', $n_INV_BRT, '', 'fdecimal', 'right', 0, '', 'fix', $cTTIP_VOL);
						eTDIV();
						echo '<div class="tab-pane fade" id="upd_DETAIL">';
						LABEL([3,3,3,6], '700', $cPOT_CASH);
						INPUT('text', [2,2,2,3], '900', 'UPD_CASH_DISK', $n_POT_CASH, '', 'fdecimal', 'right', 0, '', 'fix', $cTTIP_POTD);
						LABEL([3,3,3,6], '700', $cMAX_STOCK);
						INPUT('text', [2,2,2,3], '900', 'UPD_MAXCSTOCK', $n_MAX, '', 'fdecimal', 'right', 0, '', 'fix', $cTTIP_MAX);
						LABEL([3,3,3,6], '700', $cMIN_STOCK);
						INPUT('text', [2,2,2,3], '900', 'UPD_MINCSTOCK', $n_MIN, '', 'fdecimal', 'right', 0, '', 'fix', $cTTIP_MIN);
						LABEL([3,3,3,6], '700', $cHRG_AKHIR);
						INPUT('text', [2,2,2,3], '900', 'UPD_LAST_PRICE', $a_INVENT['LAST_PRICE'], '', 'fdecimal', 'right', 0, '', 'fix', $cTTIP_AKH);
					eTDIV();
?>
					<div class="tab-pane fade in active" id="upd_FOTO">
						<label class="form-label" for="field-1"><?php echo $cGBR_INV?></label>
						<span class="desc"></span>																	
						<img class="img-responsive" id="preview_IMG" src="<?php echo $cFILE_FOTO_INV?>" alt="" style="max-width:220px;">
						<div class="controls">
							<input type="file" name="IMG_FILE_NAME" class="form-control" onchange="previewIMAGE(event)">
						</div>
					</div>
<?php
				eTDIV();
				SAVE($cSAVE_DATA);
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case 'upd__inv':
	$KODE_CRUD=$_GET['_c'];
	$cNAMA_BRG = ENCODE($_POST['UPD_NAMA_BRG']);
	$cSHORT_NM = ENCODE($_POST['UPD_SHORT_NM']);
	$cSPL_CODE = ENCODE($_POST['EDIT_SPL_CODE']);
	$nHRG_JUAL 	= '0'.str_replace(',', '', $_POST['EDIT_HRG_JUAL']);

	$nHRG_BELI 	= '0'.str_replace(',', '', $_POST['EDIT_HRG_BELI']);
	$cSALES_UNIT = (isset($_POST['EDIT_UNIT_JUAL']) ? ENCODE($_POST['EDIT_UNIT_JUAL']) : '');
	$cPURCH_UNIT = (isset($_POST['EDIT_UNIT_BELI']) ? ENCODE($_POST['EDIT_UNIT_BELI']) : '');
	$cINV_GROUP = (isset($_POST['UPD_GROUP_INV']) ? ENCODE($_POST['UPD_GROUP_INV']) : '');
	$cINV_DIVSN = (isset($_POST['UPD_KODE_DVB']) ? ENCODE($_POST['UPD_KODE_DVB']) : '');
	$nLAST_PR 	= '0'.str_replace(',', '', $_POST['UPD_LAST_PRICE']);
	$nPOT_CASH	= '0'.str_replace(',', '', $_POST['UPD_CASH_DISK']);
	$nWEIGHT	= '0'.str_replace(',', '', $_POST['UPD_INV_WEIGHT']);
	$nVOLUME	= '0'.str_replace(',', '', $_POST['UPD_INV_VOL']);

	
	$lCHECK = (isset($_POST['UPD_NO_ACTIVE']) ? 1 : 0);
	$q_INVENT=OpenTable('Invent', "md5(KODE_BRG)='$KODE_CRUD' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if($aINV = SYS_FETCH($q_INVENT))
		$KODE_INV = $aINV['KODE_BRG'];
	else
		return;

	$nCONTENT=($nMULTI_UNIT==1 ? intval($_POST['UPD_INV_ISI']) : 1);
	RecUpdate('Invent', 
		['NAMA_BRG', 'SHORT_NAME', 'INV_ISI', 'SUPPLIER', 'NO_ACTIVE', 'LAST_PRICE', 'HARGA_JUAL', 'HARGA_BELI', 'UNIT_JUAL', 'UNIT_BELI', 'GROUP_INV', 'KODE_DVB'], 
		[$cNAMA_BRG, $cSHORT_NM, $nCONTENT, $cSPL_CODE, $lCHECK, $nLAST_PR, $nHRG_JUAL, $nHRG_BELI, $cSALES_UNIT, $cPURCH_UNIT, $cINV_GROUP, $cINV_DIVSN], 
		"APP_CODE='$cAPP_CODE' and KODE_BRG='$KODE_INV'");
	$qCASH_DISC=OpenTable('TbiCashDisc', "KODE_BRG='$KODE_INV' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(intval($nPOT_CASH=0)) {
		if($aINV = SYS_FETCH($qCASH_DISC)){
			RecSoftDel($aINV['REC_ID']);
		}
	} else {
		if(SYS_ROWS($qCASH_DISC)==0){
			RecCreate('TbiCashDisc', ['KODE_BRG', 'APP_CODE', 'ENTRY', 'REC_ID'], [$KODE_INV, $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		} else {
			RecUpdate('TbiCashDisc', ['CASH_DISK'], [$nPOT_CASH], "APP_CODE='$cAPP_CODE' and KODE_BRG='$KODE_INV' and REC_ID not in ( select DEL_ID from logs_delete)");
		}
	}
	$qVOL_WGT =OpenTable('TbiVolWeight', "APP_CODE='$cAPP_CODE' and KODE_BRG='$KODE_INV' and REC_ID not in ( select DEL_ID from logs_delete)");
	if($nWEIGHT==0 and $nVOLUME==0) {
		if($aINV = SYS_FETCH($qVOL_WGT)){
			RecSoftDel($aINV['REC_ID']);
		}
	} else {
		if(SYS_ROWS($qVOL_WGT)==0){
			RecCreate('TbiVolWeight', ['KODE_BRG', 'INV_WEIGHT', 'APP_CODE', 'ENTRY', 'REC_ID'], [$KODE_INV, $nWEIGHT, $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		} else {
			RecUpdate('TbiVolWeight', ['INV_WEIGHT'], [$nWEIGHT], "APP_CODE='$cAPP_CODE' and KODE_BRG='$KODE_INV' and REC_ID not in ( select DEL_ID from logs_delete)");
		}
	}

	if(isset($_FILES['IMG_FILE_NAME'])) {
		$cFILE_IMAGE = $cAPP_CODE.'_INV_'.$KODE_INV.'.jpg';
		$cFILE_FOTO = $cFOLDER_IMG.$cFILE_IMAGE;

		$target_file = $cFOLDER_IMG . basename($_FILES["IMG_FILE_NAME"]["name"]);
		$TMP_NAME = $_FILES["IMG_FILE_NAME"]["tmp_name"];
		if ($TMP_NAME) {
			$check = getimagesize($TMP_NAME);
			if($check) {
			print_r2($cFILE_FOTO);
			unlink($cFILE_FOTO);
				if (move_uploaded_file($_FILES["IMG_FILE_NAME"]["tmp_name"], $cFILE_FOTO)) {
					echo "<img src='$cFILE_FOTO' alt='Gambar yang diupload' style='max-width:300px;'>";
				} else {
					MSG_INFO("Terjadi kesalahan saat mengupload gambar.");
				}
			} else {
				MSG_INFO("File bukan gambar.");
			}
		} else {
			MSG_INFO("Tidak ada file yang diupload.");
		}

	}
	APP_LOG_ADD($cHEADER, 'edit '.$KODE_INV);
	header('location:tb_inventory.php');
	break;

case md5('DEL_INV'):
	$KODE_CRUD=$_GET['_id'];
	$q_INVENT=OpenTable('Invent', "md5(KODE_BRG)='$KODE_CRUD' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if($aINV = SYS_FETCH($q_INVENT))
		$KODE_INV = $aINV['KODE_BRG'];
	else
		return;
	
	$qCASH_DISC=OpenTable('TbiCashDisc', "KODE_BRG='$KODE_INV' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	while($a_REC=SYS_FETCH($qCASH_DISC)) {
		RecSoftDel($a_REC['REC_ID']);
	}
	$qVOL_WGT=OpenTable('TbiVolWeight', "KODE_BRG='$KODE_INV' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	while($a_REC=SYS_FETCH($qVOL_WGT)) {
		RecSoftDel($a_REC['REC_ID']);
	}
	$qVOL_WGT=OpenTable('TbiNotes', "INV_CODE='$KODE_INV' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	while($a_REC=SYS_FETCH($qVOL_WGT)) {
		RecSoftDel($a_REC['REC_ID']);
	}
	RecSoftDel($KODE_CRUD);
	APP_LOG_ADD($cHEADER, 'Delete : '.$KODE_INV);
	header('location:tb_inventory.php');
}
?>

<script>
function previewIMAGE(event) {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview_IMG');
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>

