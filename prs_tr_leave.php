<?php
//	prs_tr_leave.php //

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cHEADER 	= S_MSG('PE01','Permohonan Cuti');
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Transaksi - Cuti.pdf';

	$can_CREATE = TRUST($cUSERCODE, 'PRS_LEAVE_1ADD');
	$can_VW_ALL	= TRUST($cUSERCODE, 'PRS_LEAVE_5VW_OTH');

	$cACTION = (isset($_GET['_a']) ? $_GET['_a'] : '');
  
	$cADD_LEAVE 	= S_MSG('PE03','Tambah');
	$cEDIT_CUTI 	= S_MSG('PE14','Edit');

	$cKD_PERSON 	= S_MSG('PA02','Kode Peg');
	$cNM_PERSON 	= S_MSG('PA03','Nama Karyawan');
	$cNO_URUT		= S_MSG('PE02','No. Urut');
	$cREASON	 	= S_MSG('PE07','Alasan Cuti');
	$cCATATAN 	    = S_MSG('PE08','Catatan');
	$cTANGGAL 		= S_MSG('PE17','Tanggal mulai cuti');
	$cSAMPAI		= S_MSG('RS14','s/d');
	$cJML_HR		= 'Jml Hr Cuti';

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'View');
		$qQUERY=OpenTable('TrLeave', "A.APP_CODE='$cAPP_CODE' and A.DELETOR=''". ( $can_VW_ALL==1 ? '' : " and G.CUST_CODE in ( select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE')"). " and A.LEV_DATE1> now() - INTERVAL 12 month and LEAVE_STTS=0", 'LEAVE_NO', 'LEAVE_NO desc');

		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cNO_URUT, $cTANGGAL, $cSAMPAI, $cNM_PERSON, $cREASON]);
						echo '<tbody>';
							while($a_PRS_CUTI=SYS_FETCH($qQUERY)) {
								$aCOL=[$a_PRS_CUTI['LEAVE_NO'], date("d-M-Y", strtotime($a_PRS_CUTI['LEV_DATE1'])), date("d-M-Y", strtotime($a_PRS_CUTI['LEV_DATE2'])), DECODE($a_PRS_CUTI['PRSON_NAME']), $a_PRS_CUTI['LEAVE_NOTE']];
								TDETAIL($aCOL, [], '', ['', '', '', "<a href='?_a=".md5('up__date')."&_r=$a_PRS_CUTI[LEAVE_REC]'>", '']);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('cr34t3'):
		$can_VW_ALL	= TRUST($cUSERCODE, 'PRS_LEAVE_5VW_OTH');
		$cNOTE			= S_MSG('PE08','Catatan');
		$qQUERY=OpenTable('Tr_Leave', "APP_CODE='$cAPP_CODE'", '', 'LEAVE_NO desc limit 1');
		$nLAST = 0;
		if($rLEAVE = SYS_FETCH($qQUERY))	$nLAST = $rLEAVE['LEAVE_NO'];
		$cFILTER_PEOPLE = "M.PRSON_SLRY<2 and P.APP_CODE='$cAPP_CODE' and P.DELETOR='' and P.PEOPLE_CODE not in ( select PRSON_CODE from prs_resign where APP_CODE='$cAPP_CODE' and P.DELETOR='')";
		if ($can_VW_ALL==0) $cFILTER_PEOPLE .= " and C.CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE')";
		$qPEOPLE=OpenTable('PeopleCustomer', $cFILTER_PEOPLE, '', 'PEOPLE_NAME');
			$allPEOPLE = ALL_FETCH($qPEOPLE);

		DEF_WINDOW($cADD_LEAVE);
			TFORM($cADD_LEAVE, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cNO_URUT);
					INPUT('text', [2,2,2,3], '900', 'ADD_RECORD_NO', $nLAST+1, '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cTANGGAL);
					INP_DATE([2,2,3,6], '900', 'ADD_START_DATE', date('d-m-Y'));
					LABEL([1,1,2,6], '700', $cSAMPAI);
					INP_DATE([2,2,3,6], '900', 'ADD_FINISH_DATE', date('d-m-Y'), '', '', '','fix');
					LABEL([3,3,3,6], '700', 'Jml hr cuti');
					INPUT('number', [1,1,2,3], '900', 'ADD_HARICUTI', 0, '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNM_PERSON);
					TDIV(8,8,8,8);
                		SELECT([], 'ADD_PRSON_CODE', '', 's2example-1');
							echo '<option></option>';
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
								echo '</optgroup>';
							}
						echo '</select><br><br><br>';
					echo '</div>';
					LABEL([3,3,3,6], '700', 'Nama Pengganti');
					INPUT('text', [6,6,6,6], '900', 'ADD_NM_PGS', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', 'HP Pengganti');
					INPUT('text', [6,6,6,6], '900', 'ADD_HP_PGS', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cREASON);
					INPUT('text', [6,6,6,6], '900', 'ADD_REASON', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNOTE);
					INPUT('text', [6,6,6,6], '900', 'ADD_NOTE', '', '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('up__date'):
		$cCUSTOMER	= S_MSG('RS04','Customer');
		$cLOKASI	= S_MSG('PF16','Lokasi');
		$cJABATAN	= S_MSG('PF13','Jabatan');
		$cNOTE		= S_MSG('PE08','Catatan');
		$can_UPDATE = TRUST($cUSERCODE, 'PRS_LEAVE_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'PRS_LEAVE_3DEL');
		$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');

		$qQUERY=OpenTable('TrLeave', "LEAVE_REC='$_GET[_r]'");
		$a_PRS_CUTI=SYS_FETCH($qQUERY);
		$cPRSON_CODE = ( $a_PRS_CUTI ? $a_PRS_CUTI['PRSON_CODE'] : '');
		DEF_WINDOW($cEDIT_CUTI);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=del_cuti&_d='. $a_PRS_CUTI['LEAVE_REC']. '&_n='.$a_PRS_CUTI['LEAVE_NO']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_CUTI, '?_a=upd_ate&_r='.$a_PRS_CUTI['LEAVE_REC'].'&_n='.$a_PRS_CUTI['LEAVE_NO'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKD_PERSON);
					INPUT('text', [2,2,2,6], '900', 'EDIT_PRSON_CODE', $a_PRS_CUTI['PRSON_CODE'], '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cNM_PERSON);
					INPUT('text', [6,6,6,6], '900', 'EDIT_PRSON_NAME', DECODE($a_PRS_CUTI['PRSON_NAME']), '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cCUSTOMER);
					INPUT('text', [6,6,6,6], '900', 'EDIT_CUST_NAME', DECODE($a_PRS_CUTI['CUST_NAME']), '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cLOKASI);
					INPUT('text', [6,6,6,6], '900', 'EDIT_LOKS_NAME', DECODE($a_PRS_CUTI['LOKS_NAME']), '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cJABATAN);
					INPUT('text', [6,6,6,6], '900', 'EDIT_JOB_NAME', DECODE($a_PRS_CUTI['JOB_NAME']), '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cTANGGAL);
					INPUT_DATE([2,2,3,6], '900', 'EDIT_TANGGAL1', $a_PRS_CUTI['LEV_DATE1']);
					LABEL([1,1,1,6], '700', $cSAMPAI);
					INPUT_DATE([2,2,3,6], '900', 'EDIT_TANGGAL2', $a_PRS_CUTI['LEV_DATE2'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', 'Jml hr cuti');
					INPUT('number', [1,1,1,1], '900', 'EDIT_HARICUTI', $a_PRS_CUTI['DURATION'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', 'Nama Pengganti');
					INPUT('text', [6,6,6,6], '900', 'EDIT_NM_PGS', $a_PRS_CUTI['CHANGER_NAME'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', 'HP Pengganti');
					INPUT('text', [6,6,6,6], '900', 'EDIT_HP_PGS', $a_PRS_CUTI['CHANGER_PHN'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cREASON);
					INPUT('text', [6,6,6,6], '900', 'EDIT_REASON', $a_PRS_CUTI['LEAVE_RSON'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNOTE);
					INPUT('text', [6,6,6,6], '900', 'EDIT_NOTE', $a_PRS_CUTI['LEAVE_NOTE'], '', '', '', 0, '', 'fix');
					SAVE(($can_UPDATE ? S_MSG('F301','Save') : ''));
					TDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case 'tambah':
		$dLEAVE_ST = $_POST['ADD_START_DATE'];		// 'dd/mm/yyyy'
		$dLEAVE_FN = $_POST['ADD_FINISH_DATE'];		// 'dd/mm/yyyy'
		if (!$dLEAVE_ST || !$dLEAVE_FN) {
			MSG_INFO('Tanggal cuti masih kosong');
			return;
		}
		if($_POST['ADD_PRSON_CODE']==''){
			MSG_INFO('Pegawai belum dipilih');
			return;
		}
		$qQUERY=OpenTable('TrLeave', "A.APP_CODE='$cAPP_CODE'", '', 'LEAVE_NO desc limit 1');
		$rLEAVE = SYS_FETCH($qQUERY);
		$nLAST = $rLEAVE['LEAVE_NO']+1;
		RecCreate('TrLeave', ['LEAVE_NO', 'LEV_DATE1', 'LEV_DATE2', 'DURATION', 'PRSON_CODE', 'LEAVE_RSON', 'LEAVE_NOTE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'],
			[$nLAST, DMY_YMD($dLEAVE_ST), DMY_YMD($dLEAVE_FN), $_POST['ADD_HARICUTI'], $_POST['ADD_PRSON_CODE'], $_POST['ADD_REASON'], $_POST['ADD_NOTE'], $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s")]);
		RecCreate('TrCLeave', ['LEAVE_NO', 'CHANGER_NAME', 'CHANGER_PHN', 'APP_CODE', 'ENTRY', 'REC_ID'],
			[$nLAST, $_POST['ADD_NM_PGS'], $_POST['ADD_HP_PGS'], $cAPP_CODE, $cUSERCODE, NowMSecs()]);

		header('location:prs_tr_leave.php');
		break;

	case 'upd_ate':
		$NMR_URUT=$_GET['_r'];
		$NMR_CUTI=$_GET['_n'];
		$dLEAVE_ST = $_POST['EDIT_TANGGAL1'];		// 'dd/mm/yyyy'
		$dLEAVE_FN = $_POST['EDIT_TANGGAL2'];		// 'dd/mm/yyyy'

		RecUpdate('TrLeave', ['LEV_DATE1', 'LEV_DATE2', 'DURATION', 'LEAVE_RSON', 'LEAVE_NOTE', 'UP_DATE', 'UPD_DATE'], 
			[$dLEAVE_ST, $dLEAVE_FN, $_POST['EDIT_HARICUTI'], $_POST['EDIT_REASON'], $_POST['EDIT_NOTE'], $cUSERCODE, date("Y-m-d H:i:s")], "LEAVE_REC=".$NMR_URUT); 

		$qQUERY=OpenTable('TrCLeave', "LEAVE_NO='$NMR_CUTI' and APP_CODE='$cAPP_CODE'");
		if (SYS_ROWS($qQUERY)==0) {
			if ($_POST['EDIT_NM_PGS']!='' || $_POST['EDIT_HP_PGS']!='') {
				RecCreate('TrCLeave', ['LEAVE_NO', 'CHANGER_NAME', 'CHANGER_PHN', 'APP_CODE', 'ENTRY', 'REC_ID'],
					[$NMR_CUTI, $_POST['EDIT_NM_PGS'], $_POST['EDIT_HP_PGS'], $cAPP_CODE, $cUSERCODE, NowMSecs()]);
			}
		} else {
			RecUpdate('TrCLeave', ['CHANGER_NAME', 'CHANGER_PHN'], [$_POST['EDIT_NM_PGS'], $_POST['EDIT_HP_PGS']], "LEAVE_NO=".$NMR_CUTI." and APP_CODE='$cAPP_CODE'"); 
		}
		header('location:prs_tr_leave.php');
	break;
		
	case 'del_cuti':
		$NMR_URUT=$_GET['_d'];
		RecUpdate('TrLeave', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, date("Y-m-d H:i:s")], "LEAVE_REC=".$NMR_URUT); 
		$NMR_CUTI=$_GET['_n'];
		RecDelete('TrCLeave', 'LEAVE_NO='.$NMR_CUTI);
		header('location:prs_tr_leave.php');
	break;
}
?>
