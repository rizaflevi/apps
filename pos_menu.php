<?php
//	pos_menu.php //

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('upload_max_filesize', '20M');
ini_set('max_execution_time', 10); //10 seconds

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

$cAPP_CODE = $_SESSION['data_FILTER_CODE'];	$cUSERCODE = $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Tabel - Menu.pdf';

$nGROUP_MENU = 0;
$q_BGROUP=OpenTable('tbi_group', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
$nGROUP_MENU = SYS_ROWS($q_BGROUP);
$cFILTER_GRUP=(isset($_GET['_g']) ? $_GET['_g'] : '');

$cHEADER 		= S_MSG('H623','Tabel Menu');
$cMENU_CODE		= S_MSG('H621','Kode');
$cNO_ACTIVE		= S_MSG('F026','Non Aktif');
$cMENU_DESC 	= S_MSG('H622','Nama');
$cPRICE 		= S_MSG('F053','Harga Jual');
$cSELLING_UNIT	= S_MSG('F054','Satuan Jual');
$cGROUP			= S_MSG('F060','Kelompok');
$cMENU_NOTES	= S_MSG('PM04','Ket');
$cMENU_GROUP	= S_MSG('H607','Group');

$cFOTO_MENU		= S_MSG('H638','Foto');

$cTTIP_KODE		= 'Setiap Menu di beri kode untuk memudahkan meng akses';
$cTTIP_NAMA		= 'Nama menu yang menjelaskan keterangan mengenai menu';
$cTTIP_HRGJ		= 'Harga jual per menu, diisi dengan harga sesuai satuan jual nya';

$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
$cADD_REC		= S_MSG('KA11','Add new');
$cEDIT_TBL		= 'Edit Tabel Menu';

$cSAVE_DATA		= S_MSG('F301','Save');
$cCLOSE_DATA	= S_MSG('F302','Close');

// $cFOLDER_IMG = '/home/riza/www/images/resto/';
$cFOLDER_IMG = 'data/images_inventory/';

$cACTION=(isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');

switch($cACTION){
	default:
		UPDATE_DATE();
		$ADD_LOG	= APP_LOG_ADD();

		$q_MENU=OpenTable('PosMenuNGroup', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)",'',  "A.NO_ACTIVE, A.GROUP_INV");
		DEF_WINDOW($cHEADER);
			$aACT = (TRUST($cUSERCODE, 'TB_MENU_1ADD')==1 ? ['<a href="?_a='. md5('CREATE_MENU'). '"><i class="fa fa-plus-square"></i>'. $cADD_REC.'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						$aHEAD=[$cMENU_CODE, $cMENU_DESC, $cMENU_GROUP, $cPRICE];
						$aALIGN=[0,0,0,1];
						THEAD($aHEAD, '', $aALIGN);
						echo '<tbody>';
							while($aTB_MENU=SYS_FETCH($q_MENU)) {
								$cHREFF="<a href='?_a=".md5('UPD_MENU')."&_c=".md5($aTB_MENU['REC_ID'])."'>";
								$aCOL=[$aTB_MENU['KODE_BRG'], DECODE($aTB_MENU['NAMA_BRG']), DECODE($aTB_MENU['NAMA_GRP']), CVR($aTB_MENU['HARGA_JUAL'])];
								$aHREFF=[$cHREFF, $cHREFF, $cHREFF, ''];
								TDETAIL($aCOL, $aALIGN, '', $aHREFF);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('CREATE_MENU'):
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=addMenu','', $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cMENU_CODE);
					INPUT('text', [2,3,3,6], '900', 'ADD_MENU_CODE', '', 'FOCUS', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cMENU_DESC);
					INPUT('text', [6,6,6,6], '900', 'ADD_MENU_DESC', '', '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cPRICE);
					INPUT('number', [2,2,2,6], '900', 'ADD_MENU_PRICE', '', '', '', 'right', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cSELLING_UNIT, '', '');
					INPUT('text', [2,2,2,3], '900', 'ADD_SELLING_UNIT', '', '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cMENU_NOTES, '', '');
					INPUT('text', [8,8,8,8], '900', 'ADD_MENU_NOTES', '', '', '', '', 0, '', 'fix');

						if($nGROUP_MENU > 0) {
							LABEL([4,4,4,6], '700', $cGROUP);
							echo '<select name="ADD_GROUP_MENU" class="col-sm-4 form-label-900">';
								$REC_GROUP=OpenTable('tbi_group', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
								echo "<option value=' '  > </option>";
								while($aREC_GR_DATA=SYS_FETCH($REC_GROUP)){
									echo "<option value='$aREC_GR_DATA[KODE_GRP]'  >$aREC_GR_DATA[NAMA_GRP]</option>";
								}
							echo '</select>';
							CLEAR_FIX();
						}
					echo '<h4> </br></h4>';
					TAB(['MENU_IMG'], ['fa-cog'], [$cFOTO_MENU]);
?>
			<div class="tab-content primary">
				<div class="tab-pane fade in active" id="MENU_IMG">
					<label class="form-label" for="field-1"></label>
					<span class="desc"></span>
					<img class="img-responsive" id="preview_IMG" src="data/images/no.jpg" alt="" style="max-width:220px;">
					<div class="controls">
						<input name="ADD_IMG_MENU" type="file" accept="image/*" class="form-control" onchange="previewGambar(event)">
					</div>
				</div>
			</div>
<?php
				eTDIV();
				SAVE($cSAVE_DATA);
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('UPD_MENU'):
		$can_DELETE = TRUST($cUSERCODE, 'TB_MENU_3DEL');
		$cREC_ID = $_GET['_c'];
		$q_MENU=OpenTable('invent', "md5(REC_ID)='$cREC_ID' and REC_ID not in ( select DEL_ID from logs_delete)",'',  "NO_ACTIVE, KODE_BRG");
		if($aTB_MENU=SYS_FETCH($q_MENU)){
			$cFILE_FOTO_MENU = $cFOLDER_IMG.$cAPP_CODE.'_INV_'.$aTB_MENU['KODE_BRG'].'.jpg';
			if(file_exists($cFILE_FOTO_MENU)==0)	{
				$cFILE_FOTO_MENU = "data/images/no.jpg";
			}
		} else {
			header('location:pos_menu.php');
			return;
		}
		DEF_WINDOW($cEDIT_TBL);
			$cACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DEL_MENU').'&_id='. $aTB_MENU['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, "?_a=upd__menu&_c=".$aTB_MENU['REC_ID'], $cACT, $cHELP_FILE);
				LABEL([3,3,3,6], '700', $cMENU_CODE);
				INPUT('text', [2,3,3,6], '900', 'UPD_MENU_CODE', $aTB_MENU['KODE_BRG'], '', '', '', 0, 'disabled', '', $cTTIP_KODE);
				LABEL([2,2,2,6], '700', $cNO_ACTIVE, '', 'right');
				INPUT('checkbox', [1,1,1,1], '900', 'UPD_NO_ACTIVE', $aTB_MENU['NO_ACTIVE']==1, '', '', '', 0, '', 'fix', '', $aTB_MENU['NO_ACTIVE']==1);
				LABEL([3,3,3,6], '700', $cMENU_DESC);
				INPUT('text', [6,6,6,6], '900', 'UPD_MENU_DESC', DECODE($aTB_MENU['NAMA_BRG']), 'focus', '', '', 0, '', 'fix', $cTTIP_NAMA);
				LABEL([3,3,3,6], '700', $cPRICE);
				INPUT('text', [2,2,2,3], '900', 'EDIT_PRICE', $aTB_MENU['HARGA_JUAL'], '', 'fdecimal', 'right', 0, '', 'fix', $cTTIP_HRGJ);
				LABEL([3,3,3,6], '700', $cSELLING_UNIT, '', '');
				INPUT('text', [1,2,2,3], '900', 'EDIT_SELLING_UNIT', $aTB_MENU['UNIT_JUAL'], '', '', '', 0, '', 'fix');
				LABEL([3,3,3,6], '700', $cMENU_NOTES, '', '');
				INPUT('text', [8,8,8,8], '900', 'EDIT_MENU_NOTES', $aTB_MENU['INV_NOTES'], '', '', '', 0, '', 'fix');

				if($nGROUP_MENU > 0) {
					LABEL([3,3,3,6], '700', $cGROUP);
					echo '<select name="UPD_GROUP_MENU" class="col-sm-5 form-label-900">';
						$qGROUP=OpenTable('tbi_group', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($rGROUP=SYS_FETCH($qGROUP)){
							if($aTB_MENU['GROUP_INV']==$rGROUP['KODE_GRP']){
								echo "<option value='$rGROUP[KODE_GRP]' selected='$aTB_MENU[GROUP_INV]' >$rGROUP[NAMA_GRP]</option>";
							} else {
								echo "<option value='$rGROUP[KODE_GRP]'  >$rGROUP[NAMA_GRP]</option>";
							}
						}
					echo '</select>';
				}
				TDIV();
				echo '<h4> </br></h4>';
				TAB(['upd_FOTO'], ['fa-cog'], [$cFOTO_MENU]);
?>
					<div class="tab-pane fade in active" id="upd_FOTO">
						<label class="form-label" for="field-1"></label>
						<span class="desc"></span>																	
						<img class="img-responsive" id="preview_IMG" src="<?php echo $cFILE_FOTO_MENU?>" alt="" style="max-width:220px;">
						<div class="controls">
							<input type="file" name="IMG_FILE_NAME" accept="image/*" class="form-control" onchange="previewGambar(event)">
						</div>
					</div>
<?php
				SAVE($cSAVE_DATA);
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case 'addMenu':
	$cCODE = ENCODE($_POST['ADD_MENU_CODE']);
	if($cCODE=='') {
		MSG_INFO(S_MSG('H631','Tidak boleh kosong'));
		return;
	}
	$cNAME = ENCODE($_POST['ADD_MENU_DESC']);
	if($cNAME=='') {
		MSG_INFO(S_MSG('H631','Tidak boleh kosong'));
		return;
	}
	$cNOTES = ENCODE($_POST['ADD_MENU_NOTES']);
	$qQUERY=OpenTable('invent', "KODE_BRG='$cCODE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qQUERY)==0){
		$nPRICE 	= str_replace(',', '', $_POST['ADD_MENU_PRICE']);
		RecCreate('invent', ['KODE_BRG', 'NAMA_BRG', 'HARGA_JUAL', 'UNIT_JUAL', 'GROUP_INV', 'INV_NOTES', 'APP_CODE', 'ENTRY', 'REC_ID'], 
			[$cCODE, $cNAME, $nPRICE, $_POST['ADD_SELLING_UNIT'], $_POST['ADD_GROUP_MENU'], $cNOTES, $cAPP_CODE, $cUSERCODE, NowMSecs()]);
		header('location:pos_menu.php');
	} else {
		MSG_INFO(S_MSG('H625','Sudah ada'));
		return;
	}
// ================ upload image menu ================================ //
 	if(isset($_FILES['ADD_IMG_MENU'])) {
		$cFILE_IMAGE = $cAPP_CODE.'_INV_'.$cCODE.'.jpg';
		$cFILE_FOTO = $cFOLDER_IMG.$cFILE_IMAGE;

		$target_file = $cFOLDER_IMG . basename($_FILES["ADD_IMG_MENU"]["name"]);
		$check = getimagesize($_FILES["ADD_IMG_MENU"]["tmp_name"]);
		if($check) {
			if (move_uploaded_file($_FILES["ADD_IMG_MENU"]["tmp_name"], $cFILE_FOTO)) {
				MSG_INFO("Gambar berhasil diupload.");
				echo "<img src='$cFILE_FOTO' alt='Gambar yang diupload' style='max-width:300px;'>";
			} else {
				MSG_INFO("Terjadi kesalahan saat mengupload gambar.");
			}
		} else {
			MSG_INFO("File bukan gambar.");
		}
	} else {
		MSG_INFO('Image tidak di set');
	}
// ================ end of upload image menu ================================ //


case 'upd__menu':
	$REC_ID=$_GET['_c'];
	$cMENU_DESC = ENCODE($_POST['UPD_MENU_DESC']);
	$cMENU_NOTES = ENCODE($_POST['EDIT_MENU_NOTES']);
	$nPRICE 	= '0'.str_replace(',', '', $_POST['EDIT_PRICE']);
	
	$lCHECK = (isset($_POST['UPD_NO_ACTIVE']) ? 1 : 0);
	$q_MENU=OpenTable('invent', "REC_ID='$REC_ID' and REC_ID not in ( select DEL_ID from logs_delete)");
	if($aMENU = SYS_FETCH($q_MENU))
		$cMENU_CODE = $aMENU['KODE_BRG'];
	else {
		MSG_INFO('Error ob fetch data');
		return;
	}
	RecUpdate('invent', ['NAMA_BRG', 'NO_ACTIVE', 'HARGA_JUAL', 'UNIT_JUAL', 'GROUP_INV', 'INV_NOTES'], 
		[$cMENU_DESC, $lCHECK, $nPRICE, $_POST['EDIT_SELLING_UNIT'], 
		($nGROUP_MENU > 0 ? $_POST['UPD_GROUP_MENU'] : "''"), $cMENU_NOTES], 
		"REC_ID='$REC_ID'");

	if(isset($_FILES['IMG_FILE_NAME'])) {
		$cFILE_IMAGE = $cAPP_CODE.'_INV_'.$cMENU_CODE.'.jpg';
		$cFILE_FOTO = $cFOLDER_IMG.$cFILE_IMAGE;

		$target_file = $cFOLDER_IMG . basename($_FILES["IMG_FILE_NAME"]["name"]);
		$TMP_NAME = $_FILES["IMG_FILE_NAME"]["tmp_name"];
		if ($TMP_NAME) {
			$check = getimagesize($TMP_NAME);
			if($check) {
				if (move_uploaded_file($_FILES["IMG_FILE_NAME"]["tmp_name"], $cFILE_FOTO)) {
					MSG_INFO("Gambar berhasil diupload.");
					echo "<img src='$cFILE_FOTO' alt='Gambar yang diupload' style='max-width:300px;'>";
				} else {
					MSG_INFO("Terjadi kesalahan saat mengupload gambar.");
				}
			} else {
				MSG_INFO("File bukan gambar.");
			}
		}

	} else {
		MSG_INFO('Image tidak di set');
	}
	APP_LOG_ADD($cHEADER, 'edit '.$cMENU_CODE);
	header('location:pos_menu.php');
	break;

case md5('DEL_MENU'):
	$REC_ID=$_GET['_id'];
	$q_MENU=OpenTable('invent', "REC_ID='$REC_ID'");
	$cMENU_CODE = '';
	if($aINV = SYS_FETCH($q_MENU))
		$cMENU_CODE = $aINV['KODE_BRG'];
	
	RecDelete('invent', "REC_ID='$REC_ID'");
	APP_LOG_ADD($cHEADER, 'Delete : '.$cMENU_CODE);
	header('location:pos_menu.php');
}
?>

<script>
function previewGambar(event) {
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

