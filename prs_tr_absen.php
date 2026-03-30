<?php
//	prs_tr_absen.php //
//	

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

$cHEADER 	= S_MSG('PH81','Absen Karyawan');
$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];	$cUSERCODE = $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Transaksi - Absen.pdf';
$can_CREATE = TRUST($cUSERCODE, 'PRS_ABSN_1ADD');
$can_VW_ALL	= TRUST($cUSERCODE, 'PRS_ABSN_5UPD_ALL');

$cACTION =(isset($_GET['_a']) ? $_GET['_a'] : '');

$cFILTER_PERSON=(isset($_GET['_p']) ? $_GET['_p'] : '');

$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
$cADD_ABSEN 	= S_MSG('PH92','Tambah Absen');
$cEDIT_ABSEN 	= S_MSG('PH93','Edit Absen');
$cADD_DTL_ABS 	= S_MSG('PH94','Tambah Detil Absen');
$cEDIT_DTL_ABS 	= S_MSG('PH95','Edit Detil Absen');

$cKD_PERSON 	= S_MSG('PA02','Kode Peg');
$cNM_PERSON 	= S_MSG('PA03','Nama Karyawan');
$cNO_URUT		= S_MSG('PM16','No. Urut');
$cKETERANGAN 	= S_MSG('PA98','Keterangan');
$cTANGGAL 		= S_MSG('RS02','Tanggal');
$cSAMPAI		= S_MSG('RS14','s/d');

$cSAVE_DATA		= S_MSG('F301','Save');
$cCLOSE_DATA	= S_MSG('F302','Close');

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'View');
		$qQUERY=OpenTable('TrAbsent', "APP_CODE='$cAPP_CODE' and DELETOR=''". ( $can_VW_ALL==1 ? '' : " and G.CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE')"), '', 'ABSEN_NO desc');

		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
?>
					<table data-order='[[ 0, "desc" ]]' data-page-length='25' id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>">
<?php	
						echo THEAD([$cNO_URUT, $cTANGGAL, $cSAMPAI, $cNM_PERSON, $cKETERANGAN]);
						echo '<tbody>';
								$nTOTAL = 0;
								while($a_PRS_ABSN=SYS_FETCH($qQUERY)) {
									$aCOL=[$a_PRS_ABSN['ABSEN_NO'], date("d-M-Y", strtotime($a_PRS_ABSN['ABSEN_DATE'])), date("d-M-Y", strtotime($a_PRS_ABSN['ABS_UNTIL'])), DECODE($a_PRS_ABSN['PRSON_NAME']), $a_PRS_ABSN['ABSEN_NOTE']];
									TDETAIL($aCOL, [], '', ['', '', '', "<a href='?_a=".md5('up__date')."&_p3r50n=".md5($a_PRS_ABSN['ABSEN_REC'])."'>", '']);
								}
						echo '</tbody>';
					echo '</table>';
				eTDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case md5('cr34t3'):
	$can_VW_ALL	= TRUST($cUSERCODE, 'PRS_ABSN_5UPD_ALL');
	$cREASON		= S_MSG('PH84','Alasan Absen');
	$cNOTE			= S_MSG('PE08','Catatan');
	$qQUERY=OpenTable('Tr_Absent', "APP_CODE='$cAPP_CODE'", '', 'ABSEN_NO desc limit 1');
	$rABS = SYS_FETCH($qQUERY);
	$nLAST = ($rABS ? $rABS['ABSEN_NO'] : 0);
	$cFILTER_PEOPLE = "M.PRSON_SLRY<2 and P.APP_CODE='$cAPP_CODE' and P.DELETOR='' and P.PEOPLE_CODE not in ( select PRSON_CODE from prs_resign where APP_CODE='$cAPP_CODE' and P.DELETOR='')";
	if ($can_VW_ALL==0) $cFILTER_PEOPLE .= " and C.CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE')";
	$qPEOPLE=OpenTable('PeopleCustomer', $cFILTER_PEOPLE, '', 'PEOPLE_NAME');
	$allPEOPLE = ALL_FETCH($qPEOPLE);
	DEF_WINDOW($cADD_ABSEN);
		TFORM($cADD_ABSEN, '?_a=tambah', [], $cHELP_FILE);
			TDIV();
				LABEL([3,3,3,6], '700', $cNO_URUT);
				INPUT('text', [2,2,2,3], '900', 'ADD_RECORD_NO', $nLAST+1, '', '', '', 0, 'disable', 'fix');
				LABEL([3,3,3,6], '700', $cTANGGAL);
				INPUT_DATE([2,2,3,6], '900', 'ADD_START_DATE', date('Y-m-d'), '', '', '', 0, '', 'fix');
				LABEL([3,3,3,6], '700', $cSAMPAI);
				INPUT_DATE([2,2,3,6], '900', 'ADD_FINISH_DATE', date('Y-m-d'), '', '', '', 0, '', 'fix');
				LABEL([3,3,3,6], '700', $cNM_PERSON);
?>
				<div class="form-group">
					<div class="col-sm-8 col-md-8">
						<select name="ADD_PRSON_CODE" class="select2-container" id="s2example-1">
							<option></option>
							<?php
								$qCUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''". ($can_VW_ALL==0 ? " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE')" : ""), '', 'CUST_NAME');
								while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
									echo '<optgroup label="'.$aCUSTOMER['CUST_NAME'].'">';
									$I=0;
									$nSIZE_ARRAY = count($allPEOPLE);
									while($I<$nSIZE_ARRAY-1) {
										if ($allPEOPLE[$I]['CUST_CODE']==$aCUSTOMER['CUST_CODE']) {
											$cSELECT = $allPEOPLE[$I]['PEOPLE_NAME']."  /  ".$allPEOPLE[$I]['PEOPLE_CODE']."  /  ".$allPEOPLE[$I]['LOKS_NAME'];
											$cVALUE = $allPEOPLE[$I]['PEOPLE_CODE'];
											echo '<option value="'.$cVALUE.'">'.$cSELECT.'</option>';
										}
										$I++;
									}
								}
							?>
							</optgroup>
						</select><br><br>
					</div>
				</div>
<?php
				LABEL([3,3,3,6], '700', $cREASON);
				INPUT('text', [9,9,9,6], '900', 'ADD_REASON', '', '', '', '', 0, '', 'fix');
				LABEL([3,3,3,6], '700', $cNOTE);
				INPUT('text', [9,9,9,6], '900', 'ADD_NOTE', '', '', '', '', 0, '', 'fix');
				SAVE($cSAVE_DATA);
			eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('up__date'):
		$cCUSTOMER	= S_MSG('RS04','Customer');
		$cLOKASI	= S_MSG('PF16','Lokasi');
		$cJABATAN	= S_MSG('PF13','Jabatan');
		$cREASON	= S_MSG('PH84','Alasan Absen');
		$cNOTE		= S_MSG('PE08','Catatan');
		$can_UPDATE = TRUST($cUSERCODE, 'PRS_ABSN_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'PRS_ABSN_3DEL');
		$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');

		$qQUERY=OpenTable('TrAbsent', "md5(ABSEN_REC)='$_GET[_p3r50n]'");
		$a_PRS_ABSN=SYS_FETCH($qQUERY);
		$cPRSON_CODE = $a_PRS_ABSN['PRSON_CODE'];
		DEF_WINDOW($cEDIT_ABSEN);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=del_absen&_d='. $a_PRS_ABSN['ABSEN_REC'].'" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_ABSEN, '?_a=upd_abs&_r='.$a_PRS_ABSN['ABSEN_REC'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKD_PERSON);
					INPUT('text', [2,2,2,3], '900', 'EDIT_PRSON_CODE', $a_PRS_ABSN['PRSON_CODE'], '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cNM_PERSON);
					INPUT('text', [6,6,6,6], '900', 'EDIT_PRSON_CODE', DECODE($a_PRS_ABSN['PRSON_NAME']), '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cCUSTOMER);
					INPUT('text', [6,6,6,6], '900', 'EDIT_PRSON_CODE', DECODE($a_PRS_ABSN['CUST_NAME']), '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cLOKASI);
					INPUT('text', [6,6,6,6], '900', 'EDIT_PRSON_CODE', DECODE($a_PRS_ABSN['LOKS_NAME']), '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cJABATAN);
					INPUT('text', [6,6,6,6], '900', 'EDIT_PRSON_CODE', DECODE($a_PRS_ABSN['JOB_NAME']), '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cTANGGAL);
					INPUT_DATE([2,2,3,6], '900', 'EDIT_TANGGAL1', $a_PRS_ABSN['ABSEN_DATE'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cSAMPAI);
					INPUT_DATE([2,2,3,6], '900', 'EDIT_TANGGAL2', $a_PRS_ABSN['ABS_UNTIL'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cREASON);
					INPUT('text', [6,6,6,6], '900', 'EDIT_REASON', DECODE($a_PRS_ABSN['ABSEN_RESN']), '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNOTE);
					INPUT('text', [6,6,6,6], '900', 'EDIT_NOTE', DECODE($a_PRS_ABSN['ABSEN_NOTE']), '', '', '', 0, '', 'fix');
					SAVE($can_UPDATE==1 ? S_MSG('F301','Save') : '');
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case 'tambah':
	if($_POST['ADD_PRSON_CODE']==''){
		MSG_INFO(S_MSG('PA6F','Kode Pegawai tidak boleh kosong'));
		return;
	}
	$qQUERY=OpenTable('Tr_Absent', "APP_CODE='$cAPP_CODE'", '', 'ABSEN_NO desc limit 1');
	$rABS = SYS_FETCH($qQUERY);
	$nLAST = $rABS['ABSEN_NO']+1;
	RecCreate('TrAbsent', ['ABSEN_NO', 'ABSEN_DATE', 'ABS_UNTIL', 'PRSON_CODE', 'ABSEN_RESN', 'ABSEN_NOTE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'],
		[$nLAST, $_POST['ADD_START_DATE'], $_POST['ADD_FINISH_DATE'], $_POST['ADD_PRSON_CODE'], $_POST['ADD_REASON'], $_POST['ADD_NOTE'], $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s")]);
	header('location:prs_tr_absen.php');
	break;

case 'upd_abs':
	$NOW = date("Y-m-d H:i:s");
	$NMR_URUT=$_GET['_r'];

	RecUpdate('TrAbsent', ['ABSEN_DATE', 'ABS_UNTIL', 'ABSEN_RESN', 'ABSEN_NOTE', 'UP_DATE', 'UPD_DATE'], 
		[$_POST['EDIT_TANGGAL1'], $_POST['EDIT_TANGGAL2'], $_POST['EDIT_REASON'], $_POST['EDIT_NOTE'], $cUSERCODE, $NOW], "ABSEN_REC=".$NMR_URUT); 
	header('location:prs_tr_absen.php');
	break;

case 'del_absen':
	$NOW = date("Y-m-d H:i:s");
	$NMR_URUT=$_GET['_d'];
	RecUpdate('TrAbsent', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, $NOW], "ABSEN_REC=".$NMR_URUT); 
	header('location:prs_tr_absen.php');
	break;
}
?>

