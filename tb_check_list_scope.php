<?php
//	tb_check_list_scope.php //

include "sysfunction.php";
$cAPP_CODE = $_SESSION['data_FILTER_CODE'];	
$cUSERCODE = $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Tabel - Scope Checklist.pdf';
// $cCALL_FROM = '';
// 	if (isset($_GET['_call'])) {
// 		$cCALL_FROM=$_GET['_call'];
//         if ($cCALL_FROM=='fromdesktop') {
//             $cAPP_CODE = $_GET['_app'];
//             $cUSERCODE = $_GET['_u'];
//             $_SESSION['data_FILTER_CODE'] = $cAPP_CODE;
//             $_SESSION['gUSERCODE'] = $cUSERCODE;
//             $_SESSION['cHOST_DB2'] = $_GET['_dbserver'];
//             include "sys_function.php";
//         } else die ($cCALL_FROM);
//     } else {
//         if (!isset($_SESSION['data_FILTER_CODE'])) {
//             session_start();
//         }
//         $cAPP_CODE = $_SESSION['data_FILTER_CODE'];
//         $cUSERCODE = $_SESSION['gUSERCODE'];
//         include "sysfunction.php";
//     }

$cHEADER = S_MSG('CL41','Tabel Scope Checklist');
$can_CREATE = TRUST($cUSERCODE, 'TB_SCOPECEKLIS_1ADD');
$can_UPDATE = TRUST($cUSERCODE, 'TB_SCOPECEKLIS_2UPD');
$can_DELETE = TRUST($cUSERCODE, 'TB_SCOPECEKLIS_3DEL');

$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');
$cKODE_TBL 	= S_MSG('CL02','Kode');
$cNAMA_TBL 	= S_MSG('CL03','Keterangan');
$cCUSTOMER 	= S_MSG('RS04','Customer');
$cLOCATION 	= S_MSG('PF16','Lokasi');
$cJABATAN 	= S_MSG('PF13','Jabatan');

$cSAVE_DATA	= S_MSG('F301','Save');		
$cCLOSE_DATA= S_MSG('F302','Close');

$cScope = "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)";
$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
if (SYS_ROWS($qSCOPE)>0) $cScope .= " and A.CLS_CUST in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR='')";

$qTBCL=OpenTable('TbScopeCeklis', $cScope);

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('add_CL_Scope'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV(12,12,12,12);
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL, $cCUSTOMER, $cLOCATION, $cJABATAN], '', [], '*');
						echo '<tbody>';
							while($aREC_CHECKSCOPE=SYS_FETCH($qTBCL)) {
								$aCOL=[$aREC_CHECKSCOPE['CLS_CODE'], DECODE($aREC_CHECKSCOPE['CLS_DESC']), DECODE($aREC_CHECKSCOPE['CUST_NAME']), DECODE($aREC_CHECKSCOPE['LOKS_NAME']), DECODE($aREC_CHECKSCOPE['JOB_NAME'])];
								$cREFF="<a href=?_a=".md5('DTL_SCOPE')."&_o=".$aREC_CHECKSCOPE['REC_ID']."&_c=".$aREC_CHECKSCOPE['CUST_CODE'].$aREC_CHECKSCOPE['CLS_CODE'].">";
								TDETAIL($aCOL, [], '*', [$cREFF, $cREFF,'', '', '']);
							}
						echo '</tbody>';
                    eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('add_CL_Scope'):
		$cADD_NEW	= S_MSG('CL50','Tambah Scope');
		DEF_WINDOW($cADD_NEW);
			TFORM($cADD_NEW, '?_a=add_CheckScope', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'ADD_CL_CODE', '', 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [9,9,9,6], '900', 'ADD_CLS_DESC', '', '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cCUSTOMER);
					TDIV(8,6,6,12);
						SELECT([8,8,8,6], 'PILIH_CUST', '', '', 'select2');
							echo '<option></option>';
							$qCUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
							while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
								echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME]</option>";
							}
						echo '</select>';
					eTDIV();
					LABEL([3,3,3,6], '700', $cLOCATION);
					TDIV(8,6,6,12);
						SELECT([8,8,8,6], 'PILIH_LOCS', '', '', 'select2');
							echo '<option></option>';
							$qLOCATION=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
							while($aLOCATION=SYS_FETCH($qLOCATION)){
								echo "<option value='$aLOCATION[LOKS_CODE]'>$aLOCATION[LOKS_NAME]</option>";
							}
						echo '</select>';
					eTDIV();
					LABEL([3,3,3,6], '700', $cJABATAN);
					TDIV(8,6,6,12);
						SELECT([8,8,8,6], 'PILIH_JOBS', '', '', 'select2');
							echo '<option></option>';
							$qOCCUPATION=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
							while($aOCCUPATION=SYS_FETCH($qOCCUPATION)){
								echo "<option value='$aOCCUPATION[JOB_CODE]'>$aOCCUPATION[JOB_NAME]</option>";
							}
						echo '</select>';
					eTDIV();
					CLEAR_FIX();
				SAVE(($can_UPDATE ? $cSAVE_DATA : ''));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

    case md5('DTL_SCOPE'):
        $cREC_ID = $_GET['_o'];
		$cCUST=$_GET['_c'];
        $qTBCL=OpenTable('TbScopeCeklis', "REC_ID='$cREC_ID' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$aREC_CHECKSCOPE=SYS_FETCH($qTBCL);
		$cSCOPE_CODE=$aREC_CHECKSCOPE['CLS_CODE'];
		$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$cEDIT_REC	= S_MSG('CL51','Edit Scope');
		DEF_WINDOW($cEDIT_REC);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=del_SCOPE&_id='. $cREC_ID. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_REC, '?_a=upd_SCOPE&_id='.$aREC_CHECKSCOPE['REC_ID'].'&_s='.$cSCOPE_CODE, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_CLS_CODE', $aREC_CHECKSCOPE['CLS_CODE'], '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [9,9,9,6], '900', 'EDIT_CLS_DESC', $aREC_CHECKSCOPE['CLS_DESC'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cCUSTOMER);
					TDIV(9,9,9,12);
						SELECT([8,8,8,6], 'PILIH_CUST', '', '', 'select2');
							echo '<option>All</option>';
							$qCUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
							while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
								if($aREC_CHECKSCOPE['CLS_CUST']==$aCUSTOMER['CUST_CODE']){
									echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$aREC_CHECKSCOPE[CLS_CUST]' >$aCUSTOMER[CUST_NAME]</option>";
								} else {	echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME]</option>";
								}
							}
						echo '</select>';
					eTDIV();
					LABEL([3,3,3,6], '700', $cLOCATION);
					TDIV(9,9,9,12);
						SELECT([8,8,8,6], 'PILIH_LOCS', '', '', 'select2');
						echo '<option>All</option>';
							$qLOCATION=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
							while($aLOCATION=SYS_FETCH($qLOCATION)){
								if($aREC_CHECKSCOPE['CLS_LOC']==$aLOCATION['LOKS_CODE']){
									echo "<option value='$aLOCATION[LOKS_CODE]' selected='$aREC_CHECKSCOPE[CLS_LOC]' >$aLOCATION[LOKS_NAME]</option>";
								} else {	echo "<option value='$aLOCATION[LOKS_CODE]'  >$aLOCATION[LOKS_NAME]</option>";
								}
							}
						echo '</select>';
					eTDIV();
					LABEL([3,3,3,6], '700', $cJABATAN);
					TDIV(9,9,9,12);
						SELECT([8,8,8,6], 'PILIH_JOBS', '', '', 'select2');
						echo '<option>All</option>';
							$qOCCUPATION=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
							while($aOCCUPATION=SYS_FETCH($qOCCUPATION)){
								if($aREC_CHECKSCOPE['CLS_JOB']==$aOCCUPATION['JOB_CODE']){
									echo "<option value='$aOCCUPATION[JOB_CODE]' selected='$aREC_CHECKSCOPE[CLS_JOB]' >$aOCCUPATION[JOB_NAME]</option>";
								} else {	echo "<option value='$aOCCUPATION[JOB_CODE]'  >$aOCCUPATION[JOB_NAME]</option>";
								}
							}
						echo '</select>';
					eTDIV();
					CLEAR_FIX();
					echo '<br>';
					TABLE('myTable');
						THEAD(['Area Kerja']);
						echo '<tbody>';
								$qAREA=OpenTable('TbhArea', "C.SCOPE_ID ='$aREC_CHECKSCOPE[CLS_CODE]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
								while($aAREA=SYS_FETCH($qAREA)) {
								echo '<tr>';
									echo '<td style="width: 1px;"></td>';
									echo "<td><span><a href='?_a=".md5('UPD_AREA')."&_r=".md5($aAREA['AREA_CODE'])."&_c=".$cSCOPE_CODE."'>".$aAREA['AREA_NAME']."</a></span></td>";
								echo '</tr>';
								}
						echo '</tbody>';
					eTABLE();
					CLEAR_FIX();
					SELECT([6,6,6,6], 'NEW_AREA', '', '', 'select2');
						echo '<option></option>';
						$qADD_AREA=OpenTable('Tbh_Area', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) 
							and AREA_CODE not in ( select AREA_ID from check_area_cust where SCOPE_ID='$cSCOPE_CODE' 
							and APP_CODE='$cAPP_CODE')");
						while($aADD_AREA=SYS_FETCH($qADD_AREA)){
							echo "<option value='$aADD_AREA[AREA_CODE]'  >".DECODE($aADD_AREA['AREA_NAME'])."</option>";
						}
					echo '</select><br>';
				SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('UPD_AREA'):
		$cAREA = $_GET['_r'];
		$cSKOP=$_GET['_c'];
		$cHEADER='Edit Area';
		$cREC_AREA='';
		$qAREA=OpenTable('ScopeArea', "md5(A.AREA_ID)='$cAREA' and A.SCOPE_ID='$cSKOP' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qAREA)>0)	{
			$aAREA=	SYS_FETCH($qAREA);
			$cREC_AREA=$aAREA['REC_ID'];
			$cKOD_AREA=$aAREA['AREA_ID'];
		}
		DEF_WINDOW($cHEADER);
			$aACT = ['<a href="?_a=DB_DEL_AREA&_id='. $cREC_AREA. '" onClick="return confirm('. "'". S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?'). "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'];
			TFORM($cHEADER, '?_a=DB_UPD_AREA&_c='.$cREC_AREA, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', 'Area');
                	SELECT([5,5,5,6], 'UPD_AREA');
						$q_AREA =OpenTable('Tbh_Area', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($a_AREA=SYS_FETCH($q_AREA)){
							if($cKOD_AREA == $a_AREA['AREA_CODE'])
								echo "<option class='col-sm-4 form-label-900' value='$a_AREA[AREA_CODE]' selected='$a_AREA[AREA_CODE]'>$a_AREA[AREA_NAME]</option>";
							else 
								echo "<option value='$a_AREA[AREA_CODE]'  >$a_AREA[AREA_NAME]</option>";
						}
					echo '</select><br>';
					CLEAR_FIX();
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
	break;

	case 'DB_UPD_AREA':
		$cREC=$_GET['_c'];
		$cAREA=$_POST['UPD_AREA'];
		RecUpdate('CheckAreaCust', ['AREA_ID'], [$cAREA], "REC_ID='$cREC'");
		echo "<script> window.history.go(-2);	</script>";
	break;

	case 'del_SCOPE':
		RecSoftDel($_GET['_id']);
		header('location:tb_check_list_scope.php');
	break;
	
	case 'DB_DEL_AREA':
		RecSoftDel($_GET['_id']);
		header('location:tb_check_list_scope.php');
	break;
	
	case "upd_SCOPE":
		$KODE_CRUD=$_GET['_id'];
		$cCLS_DESC = ENCODE($_POST['EDIT_CLS_DESC']);
		$cCLS_CUST = $_POST['PILIH_CUST'];
		$cCLS_LOC = $_POST['PILIH_LOCS'];
		$cCLS_JOB = $_POST['PILIH_JOBS'];
		RecUpdate('TbScopeCeklis', ['CLS_DESC', 'CLS_CUST', 'CLS_LOC', 'CLS_JOB'], 
			[$cCLS_DESC, $cCLS_CUST, $cCLS_LOC, $cCLS_JOB], "APP_CODE='$cAPP_CODE' and REC_ID='$KODE_CRUD'");
		$cADD_NEW=$_POST['NEW_AREA'];
		if($cADD_NEW) {
			RecCreate('CheckAreaCust', ['AREA_ID', 'SCOPE_ID', 'ENTRY', 'REC_ID', 'APP_CODE'], 
				[$cADD_NEW, $_GET['_s'], $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		}
		header('location:tb_check_list_scope.php');
	break;
	
	case "add_CheckScope":
		if($_POST['ADD_CL_CODE']==''){
			MSG_INFO(S_MSG('CL23','Kode Checklist masih kosong'));
			return;
		}
		$qTBCL=OpenTable('TbScopeCeklis', "A.CLS_CODE='$_POST[ADD_CL_CODE]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qTBCL)>0){
			MSG_INFO(S_MSG('CL22','Kode Checklist sudah ada'));
			return;
			header('location:tb_check_list_scope.php');
		} else {
			$nRec_id = date_create()->format('Uv');
			$cRec_id = (string)$nRec_id;
			$cCLS_DESC = ENCODE($_POST['ADD_CLS_DESC']);
            $cCUST = ( isset($_POST['PILIH_CUST']) ? $_POST['PILIH_CUST'] : '');
			RecCreate('TbScopeCeklis', ['CLS_CODE', 'CLS_DESC', 'CLS_CUST', 'CLS_LOC', 'CLS_JOB', 'ENTRY', 'REC_ID', 'APP_CODE'], [$_POST['ADD_CL_CODE'], $cCLS_DESC, $cCUST, $_POST['PILIH_LOCS'], $_POST['PILIH_JOBS'], $cUSERCODE, $cRec_id, $cAPP_CODE]);
			header('location:tb_check_list_scope.php');
		}
	break;
}
?>

