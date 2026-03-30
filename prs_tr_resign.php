<?php
//	prs_tr_resign.php //
//	TODO : help desc

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cHEADER 	= S_MSG('PI81','Resign Karyawan');
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Transaksi - Resign.pdf';

	$cACTION = '';
	if (isset($_GET['_a']))	$cACTION = $_GET['_a'];
  
	$cFILTER_PERSON=(isset($_GET['_p']) ? $_GET['_p'] : '');

	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];

	$cKD_PERSON 	= S_MSG('PA02','Kode Peg');
	$cNM_PERSON 	= S_MSG('PA03','Nama Karyawan');
	$cNO_URUT		= S_MSG('PE02','Nomor Urut');
	$cKETERANGAN 	= S_MSG('PA98','Keterangan');
	$cTANGGAL 		= S_MSG('PI87','Tanggal');
	$cREASON		= S_MSG('PI84','Alasan');
	
	$cSAVE_DATA		= S_MSG('F301','Save');

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'View');
		$can_CREATE = TRUST($cUSERCODE, 'PRS_RESIGN_1ADD');
		$qQUERY=OpenTable('Tr_Resign', "A.APP_CODE='$cAPP_CODE' and A.DELETOR=''".UserScope($cUSERCODE), '', 'A.RESIGN_REC desc');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
?>
				<table data-order='[[ 0, "desc" ]]' id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>">
					<?php	echo THEAD([$cNO_URUT, $cKD_PERSON, $cTANGGAL, $cNM_PERSON, $cREASON, $cKETERANGAN]);
						echo '<tbody>';
							$nTOTAL = 0;
							while($a_PRS_RSGN=SYS_FETCH($qQUERY)) {
								echo '<tr>';
									$cICON = 'fa fa-clock-o';
									echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
									echo "<td><span>". $a_PRS_RSGN['RESIGN_NO'] ." </span></td>";
									echo "<td><span>". $a_PRS_RSGN['PRSON_CODE'] ." </span></td>";
									echo '<td>'.date("d-M-Y", strtotime($a_PRS_RSGN['RESIGN_DATE'])).'</td>';
									echo "<td><span><a href='?_a=".md5('up__date')."&_p3r50n=".md5($a_PRS_RSGN['RESIGN_REC'])."'>".decode_string($a_PRS_RSGN['PRSON_NAME']).'</td>';
									echo '<td>'.$a_PRS_RSGN['RESIGN_REASON'].'</td>';
									echo '<td>'.$a_PRS_RSGN['RESIGN_NOTE'].'</td>';
								echo '</tr>';
							}
						echo '</tbody>';
				echo '</table>';
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('cr34t3'):
		$cNOTE = S_MSG('PE08','Catatan');
		$qQUERY=OpenTable('PrsResign', "APP_CODE='$cAPP_CODE'", '', 'RESIGN_NO desc limit 1');
		$rABS = SYS_FETCH($qQUERY);
		$nLAST = $rABS['RESIGN_NO'];
		$cFILTER_PEOPLE = "M.PRSON_SLRY<2 and P.APP_CODE='$cAPP_CODE' and P.DELETOR='' and P.PEOPLE_CODE not in ( select PRSON_CODE from prs_resign where APP_CODE='$cAPP_CODE' and P.DELETOR='')";
		$qPEOPLE=OpenTable('PeopleCustomer', $cFILTER_PEOPLE, '', 'PEOPLE_NAME');
		$allPEOPLE = ALL_FETCH($qPEOPLE);
		$cADD_ABSEN 	= S_MSG('PI8A','Tambah Resign');
		DEF_WINDOW($cADD_ABSEN);
			TFORM($cADD_ABSEN, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,4], '', $cNO_URUT);
                    INPUT('text', [2,2,2,2], '900', 'ADD_RECORD_NO', $nLAST+1, '', '', '', 0, 'disabled', 'Fix');
					LABEL([4,4,4,4], '', $cTANGGAL);
					INP_DATE([2,2,2,6], '900', 'ADD_RESIGN_DATE', date('d/m/Y'), '', '', '', 'fix');
					LABEL([4,4,4,4], '', $cNM_PERSON);
					echo '<select name="ADD_PRSON_CODE" class="select2-container" id="s2example-1">';
						echo '<option></option>';
						$qCUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
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
						echo '</optgroup>';
					echo '</select><br><br>';
					CLEAR_FIX();
					LABEL([4,4,4,4], '', $cREASON);
                    INPUT('text', [8,8,8,6], '900', 'ADD_REASON', '', '', '', '', 0, '', 'Fix');
					LABEL([4,4,4,4], '', $cNOTE);
                    INPUT('text', [8,8,8,6], '900', 'ADD_NOTE', '', '', '', '', 0, '', 'Fix');
					CLEAR_FIX();
					SAVE($cSAVE_DATA);
				TDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

case md5('up__date'):
	$cCUSTOMER	= S_MSG('RS04','Customer');
	$cLOKASI	= S_MSG('PF16','Lokasi');
	$cJABATAN	= S_MSG('PF13','Jabatan');
	$cNOTE		= S_MSG('PE08','Catatan');
	$can_UPDATE = TRUST($cUSERCODE, 'PRS_RESIGN_2UPD');
	$can_DELETE = TRUST($cUSERCODE, 'PRS_RESIGN_3DEL');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');

    $qQUERY=OpenTable('Tr_Resign', "md5(RESIGN_REC)='$_GET[_p3r50n]'");
	$a_PRS_RSGN=SYS_FETCH($qQUERY);
	$cPRSON_CODE = $a_PRS_RSGN['PRSON_CODE'];
	$cEDIT_RESIGN	= S_MSG('PI8B','Edit Resign');
	DEF_WINDOW($cEDIT_RESIGN);
		$aACT = ($can_DELETE==1 ? ['<a href="?_a=del_resign&_d='. $a_PRS_RSGN['RESIGN_REC']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
		TFORM($cEDIT_RESIGN, '?_a=upd_resign&_r='.$a_PRS_RSGN['RESIGN_REC'], $aACT, $cHELP_FILE);
			TDIV();
				LABEL([3,3,3,3], '', $cNOTE);
				INPUT('text', [3,3,3,6], '900', 'EDIT_PRSON_CODE', $a_PRS_RSGN['PRSON_CODE'], '', '', '', 0, 'disabled', 'Fix');
				LABEL([3,3,3,3], '', $cNM_PERSON);
				INPUT('text', [6,6,6,6], '900', 'EDIT_PRSON_CODE', DECODE($a_PRS_RSGN['PRSON_NAME']), '', '', '', 0, 'disabled', 'Fix');
				LABEL([3,3,3,3], '', $cCUSTOMER);
				INPUT('text', [6,6,6,6], '900', 'EDIT_PRSON_CODE', DECODE($a_PRS_RSGN['CUST_NAME']), '', '', '', 0, 'disabled', 'Fix');
				LABEL([3,3,3,3], '', $cLOKASI);
				INPUT('text', [6,6,6,6], '900', 'EDIT_LOCS_NAME', DECODE($a_PRS_RSGN['LOKS_NAME']), '', '', '', 0, 'disabled', 'Fix');
				LABEL([3,3,3,3], '', $cJABATAN);
				INPUT('text', [6,6,6,6], '900', 'EDIT_JOB_NAME', DECODE($a_PRS_RSGN['JOB_NAME']), '', '', '', 0, 'disabled', 'Fix');
                LABEL([3,3,3,3], '700', $cTANGGAL);
				INP_DATE([2,2,3,6], '', 'EDIT_TANGGAL1', date('d/m/Y', strtotime($a_PRS_RSGN['RESIGN_DATE'])), '', '', '', 'fix');
				LABEL([3,3,3,3], '', $cREASON);
				INPUT('text', [6,6,6,6], '900', 'EDIT_REASON', DECODE($a_PRS_RSGN['RESIGN_REASON']), '', '', '', 0, '', 'Fix');
				LABEL([3,3,3,3], '', $cNOTE);
				INPUT('text', [6,6,6,6], '900', 'EDIT_NOTE', DECODE($a_PRS_RSGN['RESIGN_NOTE']), '', '', '', 0, '', 'Fix');
				SAVE(($can_UPDATE ? $cSAVE_DATA : ''));
			TDIV();
		eTFORM();
		include "scr_chat.php";
		require_once("js_framework.php");
	END_WINDOW();
	SYS_DB_CLOSE($DB2);	
	break;

case 'tambah':
	$cPERSON_CODE = $_POST['ADD_PRSON_CODE'];
	if($cPERSON_CODE==''){
		MSG_INFO('Pegawai belum dipilih');
		return;
	}
	$qQUERY=OpenTable('PrsResign', "APP_CODE='$cAPP_CODE'", '', 'RESIGN_REC desc limit 1');
	$rABS = SYS_FETCH($qQUERY);
	$nLAST = $rABS['RESIGN_NO']+1;
	RecCreate('PrsResign', ['RESIGN_NO', 'RESIGN_DATE', 'RESIGN_REASON', 'PRSON_CODE', 'RESIGN_NOTE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'],
		[$nLAST, DMY_YMD($_POST['ADD_RESIGN_DATE']), ENCODE($_POST['ADD_REASON']), $_POST['ADD_PRSON_CODE'], ENCODE($_POST['ADD_NOTE']), $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s")]);
	APP_LOG_ADD($cHEADER, 'add : '.(string)$nLAST. ' / '.$cPERSON_CODE);
	
	/////////////////////////////////
	///// 2025-05-24

	// jika resign maka hapus data perangkat di tabel people_device
	// Database connection details
	$host = 'localhost';
	$dbname = 'riza_db';
	$username = 'rifan';
	$password = 'YazaPratama@23B';

	// Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "DELETE FROM people_device WHERE PEOPLE_CODE = :people_code AND APP_CODE = :app_code";

    $stmt = $pdo->prepare($sql);
	$stmt->execute(['people_code' => $cPERSON_CODE, 'app_code' => $cAPP_CODE]);
	///// 2025-05-24
	/////////////////////////////////

	header('location:prs_tr_resign.php');
	break;

case 'upd_resign':
	$NMR_URUT=$_GET['_r'];

	RecUpdate('PrsResign', ['RESIGN_DATE', 'RESIGN_REASON', 'RESIGN_NOTE', 'UP_DATE', 'UPD_DATE'], 
		[DMY_YMD($_POST['EDIT_TANGGAL1']), $_POST['EDIT_REASON'], $_POST['EDIT_NOTE'], $cUSERCODE, date("Y-m-d H:i:s")], "RESIGN_REC=".$NMR_URUT); 
	APP_LOG_ADD($cHEADER, 'update : '.$NMR_URUT);
	header('location:prs_tr_resign.php');
	break;

case 'del_resign':
	$NMR_URUT=$_GET['_d'];
	RecUpdate('PrsResign', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, date("Y-m-d H:i:s")], "RESIGN_REC=".$NMR_URUT); 
	APP_LOG_ADD($cHEADER, 'delete : '.$NMR_URUT);
	header('location:prs_tr_resign.php');
	break;
}
?>

