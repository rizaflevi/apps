<?php
// prs_tb_tunjangan.php
// Tabel jenis tunjangan

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cHELP_FILE = 'Doc/Tabel - Tunjangan.pdf';

$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
$cUSERCODE = $_SESSION['gUSERCODE'];

$cHEADER 	= S_MSG('PA60','Tabel Tunjangan');
$cKODE_TBL 	= S_MSG('PA61','Kode Tunjangan');
$cNAMA_TBL 	= S_MSG('PA62','Nama Tunjangan');
$cSHORT_NAME= S_MSG('TK11','Nama Pendek');
$cNIL_TUNJ 	= S_MSG('PA63','Nilai Tunjangan');
$cSATUAN 	= S_MSG('PA64','Satuan');
$cPEMBULATAN= S_MSG('PA75','Pembulatan');
$cKE_ATAS	= S_MSG('PA77','Ke atas');
$cKE_BAWAH	= S_MSG('PA76','Ke bawah');
$cSAVE_DATA	= S_MSG('F301','Save');
$cCLOSE		= S_MSG('F302','Close');


$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');
switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
		$can_CREATE = TRUST($cUSERCODE, 'PRS_ALLOWANCE_1ADD');
		$qQUERY=OpenTable('UntAllowance', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('create'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL, $cSHORT_NAME, $cNIL_TUNJ, $cSATUAN]);
						echo '<tbody>';
							while($aREC_ALLOW=SYS_FETCH($qQUERY)) {
								echo '<tr>';
									echo '<td style="width: 1px;"></td>';
									echo "<td><span><a href='?_a=".md5('update')."&_c=$aREC_ALLOW[REC_ID]'>".$aREC_ALLOW['TNJ_CODE']."</a></span></td>";
									echo "<td><span><a href='?_a=".md5('update')."&_c=$aREC_ALLOW[REC_ID]'>".$aREC_ALLOW['TNJ_NAME']."</a></span></td>";
									echo '<td>'.$aREC_ALLOW['TNJ_SNAME'].'</td>';
									echo '<td align="right">'.CVR($aREC_ALLOW['TNJ_AMNT']).'</td>';
									echo '<td>'.$aREC_ALLOW['UNIT_NAME'].'</td>';
								echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
	break;

	case md5("create"):
		$cADD = S_MSG('PA6A','Tambah Tunjangan');
		DEF_WINDOW($cADD);
			TFORM($cADD, '?_a=ADD', [], $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,4], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,2], '900', 'ADD_TNJ_CODE', '', 'focus', '', '', 0, '', 'fix');
					LABEL([4,4,4,4], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'ADD_TNJ_NAME', '', '', '', '', 0, '', 'fix');
					LABEL([4,4,4,4], '700', $cSHORT_NAME);
					INPUT('text', [3,3,3,6], '900', 'ADD_TNJ_SNAME', '', '', '', '', 0, '', 'fix');
					LABEL([4,4,4,4], '700', $cNIL_TUNJ);
					INPUT('number', [3,3,3,6], '900', 'ADD_TNJ_AMNT', '', '', '', '', 0, '', 'fix');
					LABEL([4,4,4,4], '700', $cSATUAN);
                	SELECT([2,2,2,2], 'ADD_TNJ_UNIT');
						echo "<option value=' '  > </option>";
						$REC_UNIT=OpenTable('PrsTbUnit', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_UNIT=SYS_FETCH($REC_UNIT)){
							echo "<option class='form-label-900' value='$aREC_UNIT[UNIT_CODE]'  >$aREC_UNIT[UNIT_NAME]</option>";
						}
					echo '</select><br>';
					CLEAR_FIX();
					LABEL([4,4,4,4], '700', $cPEMBULATAN);
					RADIO('ADD_TNJ_ROUND', [1,2], [true, false], [$cKE_BAWAH, $cKE_ATAS]);
					CLEAR_FIX();
					SAVE($cSAVE_DATA);
				eTDIV();
				CLEAR_FIX();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5("update"):
		$cKODE = $_GET['_c'];
		$can_UPDATE = TRUST($cUSERCODE, 'PRS_ALLOWANCE_2UPD');
		$can_DELETE = TRUST($cUSERCODE, 'PRS_ALLOWANCE_3DEL');
		$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');
		$qQUERY=OpenTable('UntAllowance', "A.REC_ID='$cKODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qQUERY)==0){
			header('location:prs_tb_tunjangan.php');
		}
		$REC_PERSONT=SYS_FETCH($qQUERY);
		$qALLOW=OpenTable('TbAllowance', "REC_ID='$cKODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$cREC_ID='';
		if($aREC_AllOW=SYS_FETCH($qALLOW)) $cREC_ID=$aREC_AllOW['REC_ID'];
		else return;
		$cHEADER=S_MSG('PA6D','Edit Tunjangan');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_DELETE ? ['<a href="?_a=del_allow&_id='. $cREC_ID. '" onClick="return confirm('. "'". $cMSG_DEL. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cHEADER, '?_a=rubah&id='.$cKODE, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_TNJ_CODE', DECODE($REC_PERSONT['TNJ_CODE']), '', '', '', 0, 'disable', 'fix');
					LABEL([4,4,4,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'UPD_TNJ_NAME', DECODE($REC_PERSONT['TNJ_NAME']), 'focus', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cSHORT_NAME);
					INPUT('text', [4,4,4,6], '900', 'EDIT_TNJ_SNAME', DECODE($REC_PERSONT['TNJ_SNAME']), '', '', '', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cNIL_TUNJ);
					INPUT('number', [2,2,2,6], '900', 'UPD_TNJ_AMNT', $REC_PERSONT['TNJ_AMNT'], '', '', 'right', 0, '', 'fix');
					LABEL([4,4,4,6], '700', $cSATUAN);
                	SELECT([2,2,2,2], 'EDIT_TNJ_UNIT');
						echo "<option value=' '  > </option>";
							$REC_UNIT=OpenTable('PrsTbUnit', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_UNIT=SYS_FETCH($REC_UNIT)){

							if($aREC_UNIT['UNIT_CODE']==$REC_PERSONT['TNJ_UNIT']){
								echo "<option value='$REC_PERSONT[TNJ_UNIT]' selected='$REC_PERSONT[TNJ_UNIT]' >$aREC_UNIT[UNIT_NAME]</option>";
							} else
							echo "<option class='form-label-900' value=$aREC_UNIT[UNIT_CODE]>$aREC_UNIT[UNIT_NAME]</option>";
						}
					echo '</select>';
					CLEAR_FIX();
					LABEL([4,4,4,6], '700', $cPEMBULATAN);
					RADIO('UPD_TNJ_ROUND', [1,2], [$REC_PERSONT['TNJ_ROUND']==1, $REC_PERSONT['TNJ_ROUND']==2], [$cKE_BAWAH, $cKE_ATAS]);
					CLEAR_FIX();
					SAVE(($can_UPDATE ? $cSAVE_DATA : ''));
				eTDIV();
			eTFORM('*');
		eTDIV();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case 'ADD':
		$cCODE=$_POST['ADD_TNJ_CODE'];
		if($_POST['ADD_TNJ_CODE']=='') {
			MSG_INFO(S_MSG('PA6C','Kode Tunjangan tidak boleh kosong'));
			return;
		}
		$nNIL_TUNJGN = str_replace('.', '', $_POST['ADD_TNJ_AMNT']);
		$cUNIT=$_POST['ADD_TNJ_UNIT'];
		if($cUNIT=' ' or $cUNIT='') $cUNIT=0;
		$qQUERY=OpenTable('TbAllowance', "APP_CODE='$cAPP_CODE' and TNJ_CODE='$cCODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qQUERY)==0){
			RecCreate('TbAllowance', ['TNJ_CODE', 'TNJ_NAME', 'TNJ_SNAME', 'TNJ_UNIT', 'TNJ_AMNT', 'APP_CODE', 'ENTRY', 'REC_ID'],
				[$_POST['ADD_TNJ_CODE'], $_POST['ADD_TNJ_NAME'], $_POST['ADD_TNJ_SNAME'], $cUNIT, $nNIL_TUNJGN, $cAPP_CODE, $cUSERCODE, NowMSecs()]);
			$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Add '.$cCODE);
			SYS_DB_CLOSE($DB2);	
			header('location:prs_tb_tunjangan.php');
		} else {
			MSG_INFO(S_MSG('PA6B','Kode Tunjangan sudah ada'));
			return;
		}
	break;

	case 'rubah':
		$cREC_ID=$_GET['id'];
		$NOW = date("Y-m-d H:i:s");
		$nNIL_TUNJGN = str_replace(',', '', $_POST['UPD_TNJ_AMNT']);
		RecUpdate('TbAllowance', ['TNJ_NAME', 'TNJ_SNAME', 'TNJ_UNIT', ], [$_POST['UPD_TNJ_NAME'], $_POST['EDIT_TNJ_SNAME'], $_POST['EDIT_TNJ_UNIT']], "REC_ID='$cREC_ID'");
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Update '.$cREC_ID);
		header('location:prs_tb_tunjangan.php');
	break;

	case 'del_allow':
		RecSoftDel($_GET['_id']);
		$ADD_LOG	= APP_LOG_ADD($cHEADER, 'Delete '.$_GET['_id']);
		header('location:prs_tb_tunjangan.php');
}
?>


