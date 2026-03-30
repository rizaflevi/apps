<?php
//	fe_check_list.php //

if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
// $_SESSION['cHOST_DB2'] = 'riza_db';
$_SESSION['sLANG'] = '1';
include "sysfunction.php";

$cAPP_CODE  = $_SESSION['data_FILTER_CODE'] = $_GET['_app'];
$cPRS_CODE  = $_SESSION['gUSERCODE'] = $_GET['_prs'];
$cDEVICE = $_GET['_dev'];
if ($cAPP_CODE=='') return;

$cHEADER = 'Checklist';
$cCUSTOMER = $cLOCATION = $cJOB = $cAREA = $cSCOPE = '';

$q_PERSON=OpenTable('PrsOccuption', "PRSON_CODE='$cPRS_CODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
if($a_PERSON = SYS_FETCH($q_PERSON))	{
	$cCUSTOMER = $a_PERSON['CUST_CODE'];
	$cLOCATION = $a_PERSON['KODE_LOKS'];
	$cJOB = $a_PERSON['JOB_CODE'];
	$qSCOPE=OpenTable('TbScopeCeklis', "A.CLS_CUST='$cCUSTOMER' and A.CLS_LOC='$cLOCATION' and A.CLS_JOB='$cJOB' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
	if($aSCOPE = SYS_FETCH($qSCOPE)) {
		$cSCOPE = $aSCOPE['CLS_CODE'];
		$qAREA=OpenTable('CheckAreaCust', "SCOPE_ID='$cSCOPE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qAREA)==0) {
			MSG_INFO('Anda belum mempunyai area checklist');
			return;
		}
	} else {
		MSG_INFO('Scope tidak ada');
		return;
	}
}
$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

switch($cACTION){
	default:
		FE_WINDOW($cHEADER);
            FE_FORM('', '?_a=add_Checklist&_dev='.$cDEVICE.'&_prs='.$cPRS_CODE.'&_app='.$cAPP_CODE);
				TDIV();
					LABEL([3,3,3,3], '700', 'Area');
					SELECT([], 'area', '', 'sII_area');
					// echo '<select name="area" id="sII_area">';
						echo '<option>  </option>';
						// tbh_area + check_area_cust
						$qAREA=OpenTable('TbhArea', "C.SCOPE_ID='$cSCOPE' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", '', 'A.AREA_NAME');
						while($aSCOPE=SYS_FETCH($qAREA)) {
							echo "<option value='$aSCOPE[AREA_CODE]'>$aSCOPE[AREA_NAME]</option>";
						}
					echo '</select>';
					echo '<div id="option-list" style="margin: 2rem 0"></div>';
					SAVE('Save');
				TDIV();
            eTFORM();
		END_WINDOW();
	break;
	case "add_Checklist":	//$_POST['AREA'] // STRING	//$_POST['CHECK'] // ARRAY
		$aCHECK = $_POST['CHECK'];
		$cAREA = $_POST['AREA'];
		for ($I=0; $I < count($aCHECK); $I++) {
			$qTRAN=OpenTable('CheckTrans', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and left(FROM_UNIXTIME(left(REC_ID, 10)),10)=CURRENT_DATE() and ITEM_CODE='$aCHECK[$I]'");
			$nTRAN=SYS_ROWS($qTRAN);
			if ($nTRAN==0) {
				$nRec_id = date_create()->format('Uv');
				$cRec_id = (string)$nRec_id;
			RecCreate('TransCheckList', ['PERSON_CODE', 'ITEM_CODE', 'REC_ID', 'APP_CODE'], [$cPRS_CODE, $aCHECK[$I], $cRec_id, $cAPP_CODE]);
			}
		}
		MSG_INFO('Data Checklist sudah disimpan');
		header('location:fe_check_list.php?_dev='.$cDEVICE.'&_prs='.$cPRS_CODE.'&_app='.$cAPP_CODE);
		break;
	case 'area':
		$results = [];
		if ((isset($_GET['_cc']) && isset($_GET['_cd'])) && ($cc = $_GET['_cc']) && ($code = $_GET['_cd'])) {
			$ex = explode('|', $code);
			$qAREA=OpenTable('TbhArea', "C.CUST_ID='$ex[0]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", '', 'AREA_NAME');
			while($aAREA=SYS_FETCH($qAREA)) $results[] = ['id' => $aAREA['AREA_CODE'], 'text' => $aAREA['AREA_NAME']];
		}
		print json_encode($results);
		break;
	case 'options':
		if (isset($_GET['area']) && ($area = $_GET['area'])) { // $area
			$qCEK=OpenTable('CheckAreaItem', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and AREA_ID='$_GET[area]'");
            echo '<ul class="list-unstyled">';
            while($aCEK=SYS_FETCH($qCEK)) {
				$qTRAN=OpenTable('CheckTrans', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and left(FROM_UNIXTIME(left(REC_ID, 10)),10)=CURRENT_DATE() and ITEM_CODE='$aCEK[ITEM_ID]'");
				$nTRAN=SYS_ROWS($qTRAN);
                echo "<li>";
                echo '<input tabindex="5" type="checkbox" id="square-checkbox-1" name="CHECK[]"  '." value=$aCEK[ITEM_ID]".' class="skin-square-red"'.($nTRAN>0 ? ' checked' : ' ').'>';
                echo '<label class="icheck-label form-label" for="square-checkbox-1">&nbsp;&nbsp;&nbsp;'.DECODE($aCEK['CLI_DESC']).'</label>';
				// echo "<input tabindex='5' type='checkbox' id='square-checkbox-1' name='CHECK[]' value='$aCEK[ITEM_ID]' ".($nTRAN>0 ? "checked" : "")." class='iswitch iswitch-md iswitch-primary'>";
				// echo "<span> . <th class='title pull-left'><h5>$aCEK[CLI_DESC]</h5></th></span>";
                echo "</li>";
			}
            echo "</ul>";
		} else echo '';
		break;
}
?>


