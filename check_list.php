<?php
//	check_list.php //

function OpenTable($_TblCode, $_Condition='', $_Group='', $_Order='') {
	$cAPP_CODE = 'YAZA';
	$cUSER_CODE = 'CS';
	$TblName='';
	$cQuery="";
	$cAPP_FILT = "APP_CODE='$cAPP_CODE' and DELETOR=''";
	$gREC_FILTER = "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )";
	switch($_TblCode) {
		default:	$cQuery="";	break;
		case 'TbChecklist':
			$cQuery="select I.*, ifnull(U.UNIT_NAME, '') as UNIT_NAME from check_list_item I
				left join ( select UNIT_CODE, UNIT_NAME from prs_tb_unit where ".$gREC_FILTER.") U on I.CLI_FREQ=U.UNIT_CODE";
			break;
        case 'TbScopeCeklis':
            $cQuery="select A.*, B.CUST_CODE, ifnull(B.CUST_NAME, '') as CUST_NAME, L.LOKS_CODE, ifnull(L.LOKS_NAME, '') as LOKS_NAME, J.JOB_CODE, ifnull(J.JOB_NAME, '') as JOB_NAME from check_list_scope A";
            $cQuery.=" left join (select CUST_CODE, CUST_NAME from tb_customer where APP_CODE='$cAPP_CODE' and DELETOR='') B ON A.CLS_CUST=B.CUST_CODE ";
            $cQuery.=" left join ( select LOKS_CODE, LOKS_NAME from prs_locs where APP_CODE='$cAPP_CODE' and DELETOR='') L on A.CLS_LOC=L.LOKS_CODE";
            $cQuery.=" left join ( select JOB_CODE, JOB_NAME from prs_tb_occupation where APP_CODE='$cAPP_CODE' and DELETOR='') J on A.CLS_JOB=J.JOB_CODE";
            break;
        case 'PersonCheckList':
            $cQuery="select A.PRSON_CODE, A.DELETOR, B.PRSON_NAME, P6.JOB_CODE, P6.CUST_CODE, P6.KODE_LOKS, J.JOB_NAME from prs_person_main A
                left join (select PEOPLE_CODE, replace(PEOPLE_NAME, '{#44}',' ') as PRSON_NAME from people where APP_CODE='$cAPP_CODE' and DELETOR='' and PEOPLE_CODE>'') B on A.PRSON_CODE=B.PEOPLE_CODE
                left join ( select PRSON_CODE, JOB_CODE, CUST_CODE, KODE_LOKS from prs_person_occupation where ".$cAPP_FILT.") P6 on A.PRSON_CODE=P6.PRSON_CODE
                left join ( select JOB_CODE, JOB_NAME from prs_tb_occupation where APP_CODE='$cAPP_CODE' and DELETOR='') J on P6.JOB_CODE=J.JOB_CODE
				left join ( select PRSON_CODE, RESIGN_DATE from prs_resign where APP_CODE='$cAPP_CODE' and DELETOR='') R on A.PRSON_CODE=R.PRSON_CODE";
				break;
		case 'CheckAreaItem':
			$cQuery="select A.AREA_ID, A.ITEM_ID, A.APP_CODE, B.AREA_NAME, C.CLI_DESC from check_area_item A
				left join (select AREA_CODE, AREA_NAME from tbh_area where APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )) B ON A.AREA_ID=B.AREA_CODE 
				left join (select CLI_CODE, CLI_DESC from check_list_item where APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )) C ON A.ITEM_ID=C.CLI_CODE ";
			break;
		case 'TbhArea':
			$cQuery="select A.*, ifnull(C.CUST_ID, '') as CUST_ID from tbh_area A
				left join ( select AREA_ID, CUST_ID from check_area_cust where ".$gREC_FILTER.") C on A.AREA_CODE=C.AREA_ID";
			break;
        case 'CheckTrans':		$TblName="check_list_trans";			break;
        }
        if ($cQuery=='')	$cQuery='select * from '.$TblName;
        if ($_Condition!='')
            $cQuery .= " where ".$_Condition;
        else
            $cQuery .= " where ".$gREC_FILTER;
    
        if ($_Group!='') $cQuery.=" group by ".$_Group;
        if ($_Order!='') $cQuery.=" order by ".$_Order;
    
//    	if ($_TblCode=='TbhArea') die ($cQuery);
//    	if ($_TblCode=='PersonCheckList') print_r2 ($cQuery);
        
        return SYS_QUERY($cQuery);
}

function SYS_QUERY($_QUERY, $_CONN='$DB2') {
	if (!isset($_SESSION['DB2'])) {
		session_start();
	}
	$DB2 = $_SESSION['DB2'];
	if (empty($_QUERY))		
		return '';	// if not parameter, exit before continue with script execution
	if ($q_SYS_QRY = $DB2 -> query($_QUERY)) return $q_SYS_QRY;
	else
		print_r2 ($_QUERY);
}

function SYS_FETCH($fQUERY) {
    if (empty($fQUERY))	
        return '';
    $aSYS_QRY = $fQUERY -> fetch_array(MYSQLI_ASSOC);
    return $aSYS_QRY;
}

function SYS_ROWS($_rQUERY) {
   $r_SYS_QRY=mysqli_num_rows($_rQUERY);
    return $r_SYS_QRY;
}

function RecCreate($_TblCode, $_aField=[], $_aData=[], $_cConnection='') {
	switch($_TblCode) {
		case 'TbChecklist':		$TblName="check_list_item";			break;
		case 'TbScopeCeklis':	$TblName="check_list_scope";		break;
		case 'TbPersonCeklis':	$TblName="check_list_person";		break;
		case 'TransCheckList':	$TblName="check_list_trans";	    break;
	default:	$TblName=$_TblCode;
	}

	$cSQLADD = 'insert into '.$TblName.' (';
	for ($I=0; $I < sizeof($_aField); $I++) {
		$cSQLADD .= $_aField[$I];
		if ($I < sizeof($_aField)-1)
			$cSQLADD .= ', ';
		else
			$cSQLADD .= ') values (';
	}
	for ($I=0; $I < sizeof($_aData); $I++) {
		$uTYPE = gettype($_aData[$I]);
		switch($uTYPE){
			case 'string':	$cSQLADD .= "'".$_aData[$I]."'";	break;
			case 'integer':	$cSQLADD .= (string)$_aData[$I];	break;
			default: 		echo $uTYPE.'</br>';
		}
		if ($I < sizeof($_aData)-1)
			$cSQLADD .= ', ';
		else
			$cSQLADD .= ')';
	}
	$qSQLUPD		= SYS_QUERY($cSQLADD);
	if ($qSQLUPD=0) 
		die ('Error on RecCreate : '.$cSQLADD);
//	if ($_TblCode=='TbScopeCeklis') die ($cSQLADD);
}

function print_r2($val){
	echo '<pre>';
	print_r($val);
	echo  '</pre>';
}

include "sys_connect.php";
   	$cAPP_CODE = 'YAZA';
    $cUSER_CODE = 'CS';
    $cHEADER = 'Checklist';

	$cACTION='';
	if (isset($_GET['_a'])) 	$cACTION=$_GET['_a'];

switch($cACTION){
	default:
//		print_r2(getMachineId());
//        $qTBCL=OpenTable('TbChecklist', "I.APP_CODE='$cAPP_CODE' and I.REC_ID not in ( select DEL_ID from logs_delete)");
?>
	<!DOCTYPE html>
	<html class=" ">
    	<?php	require_once("cl_header.php");	?>
		<div class="page-container row-fluid">

			<section class=" ">
					<div class="profile-image col-md-6 col-sm-6 col-xs-6">
						<img src="data/images/YAZA2.jpg"" class="img-responsive img-circle">
					</div>
					<div class="pull-center">
								<h1 class="title"><?php echo $cHEADER?></h1>                            
					</div>
					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<section class="box ">
							<div class="content-body">
								<div class="row">
									<form action="?_a=add_Checklist" method="post">
										<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">

											<label class="col-sm-3 form-label-700" for="field-3">Customer</label>
                                            <select name="CUSTOMER" id="s_customer">
												<option></option>
													<?php
														$qCUSTOMER=OpenTable('TbScopeCeklis', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', 'CLS_DESC');
														while($aSCOPE=SYS_FETCH($qCUSTOMER)) {
															$val = implode('|', [$aSCOPE['CLS_CUST'], $aSCOPE['CLS_LOC'], $aSCOPE['CLS_JOB']]);
                                                            echo "<option value='$aSCOPE[CLS_CODE]' data-id='$val'>$aSCOPE[CLS_DESC]</option>";
														}
													?>
											</select>

											<label class="col-sm-12 form-label-700" for="field-3"> # </label>
											<label class="col-sm-3 form-label-700" for="field-3">Personnel</label>
                                            <input name="PERSON" id="s2personel" style="width: 100%"/>
                                            
											<label class="col-sm-12 form-label-700" for="field-3"> # </label>
											<label class="col-sm-3 form-label-700" for="field-3">Area</label>
                                            <input name="AREA" id="s2area" style="width: 100%"/>
                                            <div class="clearfix"></div>
											
											<div id="option-list" style="margin: 2rem 0"></div>
                                            <input type="submit" class="btn btn-primary" value=Save>
                                            <input type="button" class="btn" value=Close onclick=self.history.back()>
										</div>
									</form>
								</div>
							</div>
						</section>
					</div>

			</section>
			<?php	include "scr_chat.php";	?>
		</div>
		<?php	require_once("js_framework.php");	?>
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 
        
        <script src="assets/plugins/select2/select2.min.js" type="text/javascript"></script> 
       	<link href="assets/plugins/select2/select2.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/typeahead/css/typeahead.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/ios-switch/css/switch.css"/>

		</body>
	</html>
<?php
		break;
	case "add_Checklist":	//$_POST['AREA'] // STRING	//$_POST['CHECK'] // ARRAY
		$cCLS_CODE = $_POST['CUSTOMER'];
		if($cCLS_CODE==''){
			$cMSG = 'Kode Checklist masih kosong';
			echo "<script> alert('$cMSG');	</script>";
			header('location:check_list.php');
			break;
		}
		$cPERSON = $_POST['PERSON'];
		$aCHECK = $_POST['CHECK'];
		$cAREA = $_POST['AREA'];
		for ($I=0; $I <= count($aCHECK); $I++) {
			$qTRAN=OpenTable('CheckTrans', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and left(FROM_UNIXTIME(left(REC_ID, 10)),10)=CURRENT_DATE() and ITEM_CODE='$aCHECK[$I]'");
			$nTRAN=SYS_ROWS($qTRAN);
			if ($nTRAN==0) {
				$nRec_id = date_create()->format('Uv');
				$cRec_id = (string)$nRec_id;
			RecCreate('TransCheckList', ['PERSON_CODE', 'ITEM_CODE', 'REC_ID', 'APP_CODE'], [$cPERSON, $aCHECK[$I], $cRec_id, $cAPP_CODE]);
			}
		}
		$cMSG = 'Data Checklist sudah disimpan';
		echo "<script> alert('$cMSG');	</script>";
		header('location:check_list.php');
		break;
	case 'personel':
		$results = [];
		if ((isset($_GET['_cc']) && isset($_GET['_cd'])) && ($cc = $_GET['_cc']) && ($code = $_GET['_cd'])) {
			$ex = explode('|', $code);
			$q=OpenTable('PersonCheckList', "R.RESIGN_DATE is null and A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and P6.CUST_CODE='$ex[0]' and P6.KODE_LOKS='$ex[1]' and P6.JOB_CODE='$ex[2]'", '', 'PRSON_NAME');
			while($aPERSONEL=SYS_FETCH($q)) $results[] = ['id' => $aPERSONEL['PRSON_CODE'], 'text' => $aPERSONEL['PRSON_NAME']];
		}
		print json_encode($results);
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
			while($aCEK=SYS_FETCH($qCEK)) {
				$qTRAN=OpenTable('CheckTrans', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and left(FROM_UNIXTIME(left(REC_ID, 10)),10)=CURRENT_DATE() and ITEM_CODE='$aCEK[ITEM_ID]'");
				$nTRAN=SYS_ROWS($qTRAN);
				echo '<header class="panel_header">';
				echo "<input type='checkbox' name='CHECK[]' value='$aCEK[ITEM_ID]' ".($nTRAN>0 ? "checked" : "")." class='iswitch iswitch-md iswitch-primary'>";
				echo "<span> . <th class='title pull-left'><h5>$aCEK[CLI_DESC]</h5></th></span>";
				echo "<div class='clearfix'></div>";
				echo "</header>";
			}
		} else echo '';
		break;
}
/*
<script src="assets/js/device-uuid.min.js"></script> 

<script>
var uuid = new DeviceUUID().get();
console.log(uuid);
</script>

*/
?>


