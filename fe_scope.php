<?php
//	fe_scope.php //

include "sysfunction.php";
$cAPP_CODE = $_SESSION['data_FILTER_CODE'];	
$cUSERCODE = $_SESSION['gUSERCODE'];
$cHELP_FILE = 'Doc/Tabel - Scope Dashboard.pdf';

$cHEADER = S_MSG('FE01','Tabel Scope Front-end');
$can_CREATE = TRUST($cUSERCODE, 'FE_SCOPE_1ADD');
$can_UPDATE = TRUST($cUSERCODE, 'FE_SCOPE_2UPD');
$can_DELETE = TRUST($cUSERCODE, 'FE_SCOPE_3DEL');

$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');
$cKODE_TBL 	= S_MSG('F003','Kode');
$cNAMA_TBL 	= S_MSG('F002','Keterangan');
$cLOCATION 	= S_MSG('PF16','Lokasi');
$cJABATAN 	= S_MSG('PF13','Jabatan');
$cIDASHBOARD = 'Item Dashboard';

$cKODE_PEG	= S_MSG('PA02','Kode Peg');	
$cNAMA_PEG 	= S_MSG('PA03','Nama');
$cSAVE_DATA	= S_MSG('F301','Save');		
$cCLOSE_DATA= S_MSG('F302','Close');

$ada_OUTSOURCING=IS_OUTSOURCING($cAPP_CODE);
if ($ada_OUTSOURCING) {
    $cCUSTOMER 	= S_MSG('RS04','Customer');
    $aHDR =[$cNAMA_TBL, $cCUSTOMER, $cLOCATION, $cJABATAN, $cIDASHBOARD];
} else {
    $aHDR =[$cNAMA_TBL, $cLOCATION, $cJABATAN, $cIDASHBOARD];
}

$qTBCL=OpenTable('Fe_Scope', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete )");

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('add_FE_Scope'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV(12,12,12,12);
					TABLE('example');
						THEAD($aHDR, '', [], '*');
							while($aREC_FE_SCOPE=SYS_FETCH($qTBCL)) {
                                $cREFF="<a href=?_a=".md5('DTL_SCOPE')."&_o=".$aREC_FE_SCOPE['REC_ID'].">";
                                if ($ada_OUTSOURCING) {
                                    $aCOL=[DECODE($aREC_FE_SCOPE['SCOPE_DESC']), DECODE($aREC_FE_SCOPE['CUST_NAME']), DECODE($aREC_FE_SCOPE['LOKS_NAME']), DECODE($aREC_FE_SCOPE['JOB_NAME']), DECODE($aREC_FE_SCOPE['DASH_LABEL'])];
                                    $aREFF=[$cREFF, $cREFF, $cREFF, $cREFF, $cREFF];
                                } else {
                                    $aCOL=[DECODE($aREC_FE_SCOPE['SCOPE_DESC']), DECODE($aREC_FE_SCOPE['LOKS_NAME']), DECODE($aREC_FE_SCOPE['JOB_NAME']), DECODE($aREC_FE_SCOPE['DASH_LABEL'])];
                                    $aREFF=[$cREFF, $cREFF, $cREFF, $cREFF];
                                }
								TDETAIL($aCOL, [], '*', $aREFF);
							}
                    eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('add_FE_Scope'):
		$cADD_NEW	= S_MSG('CL50','Tambah Scope');
		DEF_WINDOW($cADD_NEW);
			TFORM($cADD_NEW, '?_a=add_Scope', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [9,9,9,6], '900', 'ADD_FE_DESC', '', 'focus', '', '', 0, '', 'fix');
                    if ($ada_OUTSOURCING) {
                        LABEL([3,3,3,6], '700', $cCUSTOMER);
                        TDIV(8,6,6,12);
                            SELECT([8,8,8,6], 'PILIH_CUST', '', '', 'select2');
                                echo "<option value=''></option>";
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
							echo "<option value=''></option>";
							$qLOCATION=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
							while($aLOCATION=SYS_FETCH($qLOCATION)){
								echo "<option value='$aLOCATION[LOKS_CODE]'>$aLOCATION[LOKS_NAME]</option>";
							}
						echo '</select>';
					eTDIV();
					LABEL([3,3,3,6], '700', $cJABATAN);
					TDIV(8,6,6,12);
						SELECT([8,8,8,6], 'PILIH_JOBS', '', '', 'select2');
							echo "<option value=''></option>";
							$qOCCUPATION=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
							while($aOCCUPATION=SYS_FETCH($qOCCUPATION)){
								echo "<option value='$aOCCUPATION[JOB_CODE]'>$aOCCUPATION[JOB_NAME]</option>";
							}
						echo '</select>';
					eTDIV();
					LABEL([3,3,3,6], '700', $cIDASHBOARD);
					SELECT([4,4,4,6], 'PLH_DASH', '', 'Select_DASH');
						echo "<option value=''  ></option>";
						$qDASH=OpenTable('FeDashboard', "APP_CODE='$cAPP_CODE'", '', 'DASH_ORDER');
						while($aDASH=SYS_FETCH($qDASH)){
							echo "<option value='$aDASH[DASH_CODE]'  >".DECODE($aDASH['DASH_LABEL'])."</option>";
						}
					echo '</select><br>';
                    CLEAR_FIX();
    				SAVE(($can_CREATE ? $cSAVE_DATA : ''));
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

    case md5('DTL_SCOPE'):
        UPDATE_DATE();
        $cREC_ID = $_GET['_o'];
        $qTB_SCOPE=OpenTable('FeScope', "REC_ID='$cREC_ID' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		$aREC_CHECKSCOPE=SYS_FETCH($qTB_SCOPE);
		$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$cEDIT_REC	= S_MSG('CL51','Edit Scope');
		DEF_WINDOW($cEDIT_REC);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=del_SCOPE&_id='. $cREC_ID. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_REC, '?_a=upd_SCOPE&_id='.$aREC_CHECKSCOPE['REC_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [9,9,9,6], '900', 'EDIT_FE_SCOPE_DESC', $aREC_CHECKSCOPE['SCOPE_DESC'], '', '', '', 0, '', 'fix');
                    if ($ada_OUTSOURCING) {
                        LABEL([3,3,3,6], '700', $cCUSTOMER);
                        TDIV(9,9,9,12);
                            SELECT([8,8,8,6], 'PILIH_CUST', '', '', 'select2');
                                echo "<option value=''>All</option>";
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
						echo "<option value=''>All</option>";
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
						    echo "<option value=''>All</option>";
							$qOCCUPATION=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
							while($aOCCUPATION=SYS_FETCH($qOCCUPATION)){
								if($aREC_CHECKSCOPE['JOB_CODE']==$aOCCUPATION['JOB_CODE']){
									echo "<option value='$aOCCUPATION[JOB_CODE]' selected='$aREC_CHECKSCOPE[JOB_CODE]' >$aOCCUPATION[JOB_NAME]</option>";
								} else {	echo "<option value='$aOCCUPATION[JOB_CODE]'  >$aOCCUPATION[JOB_NAME]</option>";
								}
							}
						echo '</select>';
					eTDIV();
					LABEL([3,3,3,6], '700', $cIDASHBOARD);
					TDIV(9,9,9,12);
                        SELECT([4,4,4,6], 'PLH_DASH', '', 'Select_DASH');
                            echo "<option value=''  ></option>";
                            $qDDASH=OpenTable('FeDashboard', "APP_CODE='$cAPP_CODE'", '', 'DASH_ORDER');
                            while($aDASH=SYS_FETCH($qDDASH)){
                                if($aREC_CHECKSCOPE['DASH_CODE']==$aDASH['DASH_CODE']) {
                                    echo "<option value='$aDASH[DASH_CODE]' selected='$aREC_CHECKSCOPE[DASH_CODE]' >$aDASH[DASH_LABEL]</option>";
                                }
                                echo "<option value='$aDASH[DASH_CODE]'  >".DECODE($aDASH['DASH_LABEL'])."</option>";
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

	case md5('UPD_DASHB'):
        UPDATE_DATE();
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
			TFORM($cHEADER, '?_a=DB_UPD&_c='.$cREC_AREA, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', 'Area');
                	SELECT([5,5,5,6], 'UPD_DASHB');
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

	case 'DB_UPD':
        UPDATE_DATE();
		$cREC=$_GET['_c'];
		$cAREA=$_POST['UPD_DASHB'];
		RecUpdate('CheckAreaCust', ['AREA_ID'], [$cAREA], "REC_ID='$cREC'");
		echo "<script> window.history.go(-2);	</script>";
	break;

	case 'del_SCOPE':
		RecSoftDel($_GET['_id']);
		header('location:fe_scope.php');
	break;
	
	case 'DB_DEL_AREA':
		RecSoftDel($_GET['_id']);
		header('location:fe_scope.php');
	break;
	
	case "upd_SCOPE":
        UPDATE_DATE();
		$KODE_CRUD=$_GET['_id'];
		$cCLS_DESC = ENCODE($_POST['EDIT_FE_SCOPE_DESC']);
        $cCUST = ( isset($_POST['PILIH_CUST']) ? $_POST['PILIH_CUST'] : '');
		$cCLS_LOC = $_POST['PILIH_LOCS'];
		$cCLS_JOB = $_POST['PILIH_JOBS'];
		$cPLH_DASH = $_POST['PLH_DASH'];
		RecUpdate('Fe_Scope', ['SCOPE_DESC', 'CUST_CODE', 'LOC_CODE', 'JOB_CODE', 'DASH_CODE'], 
			[$cCLS_DESC, $cCUST, $cCLS_LOC, $cCLS_JOB, $cPLH_DASH], "REC_ID='$KODE_CRUD'");
		header('location:fe_scope.php');
	break;
	
	case "add_Scope":
        UPDATE_DATE();
        $nRec_id = date_create()->format('Uv');
        $cRec_id = (string)$nRec_id;
        $cFE_DESC = ENCODE($_POST['ADD_FE_DESC']);
        $cCUST = ( isset($_POST['PILIH_CUST']) ? $_POST['PILIH_CUST'] : '');
		$cDASH = ( isset($_POST['PLH_DASH']) ? $_POST['PLH_DASH'] : '');
        RecCreate('FeScope', ['SCOPE_DESC', 'CUST_CODE', 'LOC_CODE', 'JOB_CODE', 'DASH_CODE', 'REC_ID', 'APP_CODE'], 
            [$cFE_DESC, $cCUST, $_POST['PILIH_LOCS'], $_POST['PILIH_JOBS'], $cDASH, $cRec_id, $cAPP_CODE]);
		if ($cDASH) {

		}
        header('location:fe_scope.php');
	break;
}
?>

