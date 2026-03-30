<?php
//	patrol_check_scope.php //

include "sysfunction.php";
$cAPP_CODE = $_SESSION['data_FILTER_CODE'];	
$cUSERCODE = $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Tabel - Scope Patrol Check.pdf';

$cHEADER = S_MSG('PC41','Tabel Scope Patrol Check');
$can_CREATE = TRUST($cUSERCODE, 'PCHECK_SCOPE_1ADD');
$can_UPDATE = TRUST($cUSERCODE, 'PCHECK_SCOPE_2UPD');
$can_DELETE = TRUST($cUSERCODE, 'PCHECK_SCOPE_3DEL');

$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');
$cKODE_TBL 	= S_MSG('F003','Kode');
$cNAMA_TBL 	= S_MSG('F002','Keterangan');
$cLOCATION 	= S_MSG('PF16','Lokasi');
$cJABATAN 	= S_MSG('PF13','Jabatan');

$ada_OUTSOURCING=IS_OUTSOURCING($cAPP_CODE);
$aHDR =[$cKODE_TBL, $cNAMA_TBL,  $cLOCATION, $cJABATAN];
if ($ada_OUTSOURCING) {
    $cCUSTOMER 	= S_MSG('RS04','Customer');
    $aHDR =[$cKODE_TBL, $cNAMA_TBL, $cCUSTOMER, $cLOCATION, $cJABATAN];
}
$cLOCATION 	= S_MSG('PF16','Lokasi');
$cJABATAN 	= S_MSG('PF13','Jabatan');

$cSAVE_DATA	= S_MSG('F301','Save');		
$cCLOSE_DATA= S_MSG('F302','Close');

$cScope = "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)";
$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
if (SYS_ROWS($qSCOPE)>0) $cScope .= " and A.CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR='')";

$qTBCL=OpenTable('PatrolScope', $cScope);

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('add_PC_Scope'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV(12,12,12,12);
					TABLE('example');
						THEAD($aHDR, '', [], '*');
						echo '<tbody>';
							while($aREC_CHECKSCOPE=SYS_FETCH($qTBCL)) {
								$aCOL=[$aREC_CHECKSCOPE['SCOPE_CODE'], DECODE($aREC_CHECKSCOPE['PC_DESC']), DECODE($aREC_CHECKSCOPE['CUST_NAME']), DECODE($aREC_CHECKSCOPE['LOKS_NAME']), DECODE($aREC_CHECKSCOPE['JOB_NAME'])];
								$cREFF="<a href=?_a=".md5('DTL_SCOPE')."&_r=".$aREC_CHECKSCOPE['REC_ID']."&_c=".$aREC_CHECKSCOPE['SCOPE_CODE'].">";
                                if ($ada_OUTSOURCING) {
                                    $aREFF = [$cREFF, $cREFF,'', '', ''];
                                } else {
    								$aCOL=[$aREC_CHECKSCOPE['SCOPE_CODE'], DECODE($aREC_CHECKSCOPE['PC_DESC']), DECODE($aREC_CHECKSCOPE['LOKS_NAME']), DECODE($aREC_CHECKSCOPE['JOB_NAME'])];
                                    $aREFF = [$cREFF, $cREFF,'', ''];
                                }
                                TDETAIL($aCOL, [], '*', $aREFF);
							}
						echo '</tbody>';
                    eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('add_PC_Scope'):
		$cADD_NEW	= S_MSG('PC45','Tambah Scope');
		DEF_WINDOW($cADD_NEW);
			TFORM($cADD_NEW, '?_a=add_PCheck_Scope', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'ADD_PC_CODE', '', 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [9,9,9,6], '900', 'ADD_PC_DESC', '', '', '', '', 0, '', 'fix');
                    if ($ada_OUTSOURCING) {
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
                    }
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
					LABEL([3,3,3,6], '700', 'Lokasi Patrol Cek');
					TDIV(8,6,6,12);
                        SELECT([6,6,6,6], 'ADD_LOC', '', '', 'select2');
                            echo '<option></option>';
                            $qADD_PLOC=OpenTable('TbPatrolLoc');
                            while($aADD_PLOC=SYS_FETCH($qADD_PLOC)){
                                echo "<option value='$aADD_PLOC[PC_CODE]'  >".DECODE($aADD_PLOC['PC_DESC'])."</option>";
                            }
                        echo '</select><br>';
                    eTDIV();
					CLEAR_FIX();
				    SAVE(($can_UPDATE ? $cSAVE_DATA : ''));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

    case md5('DTL_SCOPE'):
        $cREC_ID = $_GET['_r'];
		$cSCOPE=$_GET['_c'];
        $qTBCL=OpenTable('PatrolScope', "REC_ID='$cREC_ID' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		$aREC_CHECKSCOPE=SYS_FETCH($qTBCL);
		$cSCOPE_CODE=$aREC_CHECKSCOPE['SCOPE_CODE'];
		$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$cEDIT_REC	= S_MSG('CL51','Edit Scope');
		DEF_WINDOW($cEDIT_REC);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=del_SCOPE&_id='. $cREC_ID. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_REC, '?_a=upd_SCOPE&_id='.$aREC_CHECKSCOPE['REC_ID'].'&_s='.$cSCOPE, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_PATROL_CODE', $cSCOPE, '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [9,9,9,6], '900', 'PC_DESC', $aREC_CHECKSCOPE['PC_DESC'], '', '', '', 0, '', 'fix');
                    if ($ada_OUTSOURCING) {
                        LABEL([3,3,3,6], '700', $cCUSTOMER);
                        TDIV(9,9,9,12);
                            SELECT([8,8,8,6], 'PILIH_CUST', '', '', 'select2');
                                echo '<option>All</option>';
                                $qCUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
                                while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
                                    if($aREC_CHECKSCOPE['CUST_CODE']==$aCUSTOMER['CUST_CODE']){
                                        echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$aREC_CHECKSCOPE[CUST_CODE]' >$aCUSTOMER[CUST_NAME]</option>";
                                    } else {	echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME]</option>";
                                    }
                                }
                            echo '</select>';
                        eTDIV();
                    }
					LABEL([3,3,3,6], '700', $cLOCATION);
					TDIV(9,9,9,12);
						SELECT([8,8,8,6], 'PILIH_LOCS', '', '', 'select2');
						echo '<option>All</option>';
							$qLOCATION=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
							while($aLOCATION=SYS_FETCH($qLOCATION)){
								if($aREC_CHECKSCOPE['LOC_CODE']==$aLOCATION['LOKS_CODE']){
									echo "<option value='$aLOCATION[LOKS_CODE]' selected='$aREC_CHECKSCOPE[LOC_CODE]' >$aLOCATION[LOKS_NAME]</option>";
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
								if($aREC_CHECKSCOPE['JOB_CODE']==$aOCCUPATION['JOB_CODE']){
									echo "<option value='$aOCCUPATION[JOB_CODE]' selected='$aREC_CHECKSCOPE[JOB_CODE]' >$aOCCUPATION[JOB_NAME]</option>";
								} else {	echo "<option value='$aOCCUPATION[JOB_CODE]'  >$aOCCUPATION[JOB_NAME]</option>";
								}
							}
						echo '</select>';
					eTDIV();
					CLEAR_FIX();
					echo '<br>';
					TABLE('myTable');
						THEAD(['Lokasi Patrol Check']);
						echo '<tbody>';
								$qPLOC=OpenTable('PCScopeLoc', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and A.SCOPE_CODE='$cSCOPE'");
								while($aPLOC=SYS_FETCH($qPLOC)) {
								echo '<tr>';
									echo '<td style="width: 1px;"></td>';
									echo "<td><span><a href='?_a=".md5('UPD_PATLOC')."&_r=".$aPLOC['REC_ID']."&_c=".$cSCOPE."&_l=".$aPLOC['LOC_CODE']."'>".$aPLOC['PC_NAME']."</a></span></td>";
								echo '</tr>';
								}
						echo '</tbody>';
					eTABLE();
					CLEAR_FIX();
					SELECT([6,6,6,6], 'NEW_AREA', '', '', 'select2');
						echo '<option></option>';
						$qADD_PCHECK=OpenTable('PatrolCheck');
						while($aADD_PCHECK=SYS_FETCH($qADD_PCHECK)){
							echo "<option value='$aADD_PCHECK[PC_CODE]'  >".DECODE($aADD_PCHECK['PC_NAME'])."</option>";
						}
					echo '</select><br>';
				SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('UPD_PATLOC'):
		$cREC_ID = $_GET['_r'];
		$cSKOP=$_GET['_c'];
		$cLOC=$_GET['_l'];
		$cHEADER='Edit Lokasi Patrol Check';
		DEF_WINDOW($cHEADER);
			$aACT = ['<a href="?_a=DEL_PCHECK_LOC&_id='. $cREC_ID. '" onClick="return confirm('. "'". S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?'). "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'];
			TFORM($cHEADER, '?_a=UPD_DB_PATLOC&_r='.$cREC_ID, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', 'Lokasi Patrol Check');
                	SELECT([6,6,6,6], 'UPD_LOC', '', '', 'select2');
						$qPCHECK =OpenTable('PatrolCheck');
						while($aPLOC=SYS_FETCH($qPCHECK)){
							if($cLOC == $aPLOC['PC_CODE'])
								echo "<option value='$aPLOC[PC_CODE]' selected='$aPLOC[PC_CODE]'>$aPLOC[PC_NAME]</option>";
							else 
								echo "<option value='$aPLOC[PC_CODE]'  >$aPLOC[PC_NAME]</option>";
						}
					echo '</select><br>';
					CLEAR_FIX();
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
	break;

	case 'UPD_DB_PATLOC':
		$cREC=$_GET['_r'];
		$cLOC=$_POST['UPD_LOC'];
		RecUpdate('PCScopeLoc', ['LOC_CODE'], [$cLOC], "REC_ID='$cREC'");
		echo "<script> window.history.go(-2);	</script>";
	break;

	case 'del_SCOPE':
		RecSoftDel($_GET['_id']);
		header('location:patrol_check_scope.php');
	break;
	
	case 'DEL_PCHECK_LOC':
		RecSoftDel($_GET['_id']);
		echo "<script> window.history.go(-2);	</script>";
		// header('location:patrol_check_scope.php');
	break;
	
	case "upd_SCOPE":
		$KODE_CRUD=$_GET['_id'];
		$cPC_DESC = ENCODE($_POST['PC_DESC']);
        $cCUST = ( isset($_POST['PILIH_CUST']) ? $_POST['PILIH_CUST'] : '');
		$cCLS_LOC = $_POST['PILIH_LOCS'];
		$cJOB_CODE = $_POST['PILIH_JOBS'];
		RecUpdate('PatrolScope', ['PC_DESC', 'CUST_CODE', 'LOC_CODE', 'JOB_CODE'], 
			[$cPC_DESC, $cCUST, $cCLS_LOC, $cJOB_CODE], "APP_CODE='$cAPP_CODE' and REC_ID='$KODE_CRUD'");
		$cADD_NEW=$_POST['NEW_AREA'];
		if($cADD_NEW) {
			RecCreate('PCScopeLoc', ['SCOPE_CODE', 'LOC_CODE', 'ENTRY', 'REC_ID', 'APP_CODE'], 
				[$_GET['_s'], $cADD_NEW, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
		}
		header('location:patrol_check_scope.php');
	break;
	
	case "add_PCheck_Scope":
        $cPC_CODE= $_POST['ADD_PC_CODE'];
		if($cPC_CODE==''){
			MSG_INFO(S_MSG('PC43','Kode Patrol Check Scope masih kosong'));
			return;
		}
		$qTBCL=OpenTable('PatrolScope', "A.SCOPE_CODE='$cPC_CODE' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qTBCL)>0){
			MSG_INFO(S_MSG('PC44','Kode Patrol Check Scope sudah ada'));
			return;
		} else {
			$nRec_id = date_create()->format('Uv');
			$cRec_id = (string)$nRec_id;
			$cPC_DESC = ENCODE($_POST['ADD_PC_DESC']);
            $cCUST = ( isset($_POST['PILIH_CUST']) ? $_POST['PILIH_CUST'] : '');
			RecCreate('PatrolScope', ['SCOPE_CODE', 'PC_DESC', 'CUST_CODE', 'LOC_CODE', 'JOB_CODE', 'ENTRY', 'REC_ID', 'APP_CODE'], [$_POST['ADD_PC_CODE'], $cPC_DESC, $cCUST, $_POST['PILIH_LOCS'], $_POST['PILIH_JOBS'], $cUSERCODE, $cRec_id, $cAPP_CODE]);
            $cADD_NEW=$_POST['ADD_LOC'];
            if($cADD_LOC) {
                RecCreate('PCScopeLoc', ['SCOPE_CODE', 'LOC_CODE', 'ENTRY', 'REC_ID', 'APP_CODE'], 
                    [$cPC_CODE, $cADD_LOC, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
            }
			header('location:patrol_check_scope.php');
		}
	break;
}
?>

