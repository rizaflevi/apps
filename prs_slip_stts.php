<?php
//	prs_slip_stts.php
//	status pengiriman Slip Gaji
//	TODO : output to excel & print

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cHELP_FILE = 'Doc/Laporan - Daftar status pengiriman Slip Gaji.pdf';
	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	$cHEADER		= S_MSG('PL81','Inquery Slip Gaji');

	$cKODE_PEG 		= S_MSG('PA02','Kode Peg');
	$cNAMA_PEG 		= S_MSG('PA03','Nama Pegawai');
	$cJABATAN 		= S_MSG('PA43','Jabatan');
	$cGAJI_POKOK	= S_MSG('PA23','Gaji Pokok');
	$cJML_PND		= 'Jml Penghasilan';
	$cEMAIL 		= S_MSG('F105','Email Address');

    $ada_OUTSOURCING=(IS_OUTSOURCING($cAPP_CODE) ? 1 : 0);
	if($ada_OUTSOURCING==1) {
        $cGROUP 	= S_MSG('P060','Klmpk');
		$cCUSTOMER 	= S_MSG('RS04','Customer');
		$cLOKASI	= S_MSG('PF16','Lokasi');
	}
    
	$cFILTER_JABATAN='';
	if (isset($_GET['_j'])) $cFILTER_JABATAN=$_GET['_j'];

	$cScope = "APP_CODE='$cAPP_CODE' and DELETOR=''";
	$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	if (SYS_ROWS($qSCOPE)>0) $cScope .= " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR='')";

	$cFILTER_LOKASI='';
	if (isset($_GET['_l'])) $cFILTER_LOKASI=$_GET['_l'];

	$cFILTER_GROUP		= '';
	if (isset($_GET['_g'])) {
        $cFILTER_GROUP=$_GET['_g'];
    } else {
        $qCUST_GROUP=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
        if(SYS_ROWS( $qCUST_GROUP)>0) {
            $aCUST_GROUP=SYS_FETCH($qCUST_GROUP);
            $cFILTER_GROUP=$aCUST_GROUP['KODE_GRP'];
        }
    }

	$cFILTER_CUSTOMER='';
	if (isset($_GET['_c'])) {
		$cFILTER_CUSTOMER=$_GET['_c'];
	} else {
		if($cFILTER_CUSTOMER=='') {
			$qCUSTOMER = OpenTable('TbCustomer', "CUST_GROUP='$cFILTER_GROUP' and APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
			if($aCUST_FIRST = SYS_FETCH($qCUSTOMER))	$cFILTER_CUSTOMER=$aCUST_FIRST['CUST_CODE'];
		}
	}

	$cFILT_DATA = "A.APP_CODE='" . $cAPP_CODE ."' and A.DELETOR='' and PRSON_SLRY<2 and R.RESIGN_DATE is NULL";
	if ($cFILTER_JABATAN!='') 	$cFILT_DATA.= " and P6.JOB_CODE='".$cFILTER_JABATAN."'";
	if ($cFILTER_CUSTOMER!='')	$cFILT_DATA.= " and P6.CUST_CODE='".$cFILTER_CUSTOMER."'";
	if ($cFILTER_GROUP!='')		$cFILT_DATA.= " and CG.KODE_GRP='".$cFILTER_GROUP."'";
	$cFILT_LOCA = $cFILT_DATA;
	if ($cFILTER_LOKASI!='') 	$cFILT_DATA.= " and P6.KODE_LOKS='".$cFILTER_LOKASI."'";
    $qCUSTOMER = OpenTable('TbCustomer', "CUST_GROUP='$cFILTER_GROUP' and APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
	$qLOCS=OpenTable('SendSlip', $cFILT_LOCA.UserScope($cUSERCODE), 'LOC.LOKS_CODE', 'LOC.LOKS_NAME');
	$qQUERY=OpenTable('SendSlip', $cFILT_DATA.UserScope($cUSERCODE));

	$can_PRINT=0;
	DEF_WINDOW($cHEADER, 'collapse');
		$aACT =[];
		if ($can_PRINT) 
			array_push($aACT, '<a href="prs_slry_print?_c='
				.($cFILTER_CUSTOMER=='' ? '' : md5($cFILTER_CUSTOMER)). '&_l='
				.($cFILTER_LOKASI=='' ? '' : md5($cFILTER_LOKASI)). '&_j='
				.($cFILTER_JABATAN=='' ? '' : md5($cFILTER_JABATAN))
				.'" onClick="return confirm(OK)" title="print this payment"><i class="glyphicon glyphicon-print"></i>Print</a>&nbsp&nbsp'); 
		TFORM($cHEADER, '', $aACT, $cHELP_FILE, '*');
			LABEL([1,2,2,6], '700', $cGROUP);
			SELECT([2,3,4,6], 'PILIH_GROUP', "FILT_GROUP(this.value)");
				$qCUSTGROUP=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
				while($aCUSTGRUP=SYS_FETCH($qCUSTGROUP)){
					if($aCUSTGRUP['KODE_GRP']==$cFILTER_GROUP){
						echo "<option value='$aCUSTGRUP[KODE_GRP]' selected='$cFILTER_GROUP' >$aCUSTGRUP[NAMA_GRP]</option>";
					} else
						echo "<option value='$aCUSTGRUP[KODE_GRP]'  >$aCUSTGRUP[NAMA_GRP]</option>";
				}
				echo '</select>';
			LABEL([1,2,2,6], '700', $cJABATAN, '', 'right');
			SELECT([2,3,4,6], 'PILIH_JABTN', "FILT_PERSON(this.value, '$cFILTER_CUSTOMER', '$cFILTER_GROUP', '$cFILTER_LOKASI')");
				$qJOB=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
				echo "<option value=''  > All</option>";
				while($aREC_JOB=SYS_FETCH($qJOB)){
					if($aREC_JOB['JOB_CODE']==$cFILTER_JABATAN){
						echo "<option value='$aREC_JOB[JOB_CODE]' selected='$cFILTER_JABATAN' >$aREC_JOB[JOB_NAME]</option>";
					} else {
						echo "<option value='$aREC_JOB[JOB_CODE]'  >$aREC_JOB[JOB_NAME]</option>";
					}
				}
			echo '</select>';
			if($ada_OUTSOURCING) {
				LABEL([1,2,2,6], '700', $cCUSTOMER, '', 'right');
				SELECT([2,3,4,6], 'PILIH_CUSTOMER', "FILT_PERSON('$cFILTER_JABATAN', this.value, '$cFILTER_GROUP', '$cFILTER_LOKASI')");
				echo "<option value=''  > All</option>";
				while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
					if($aCUSTOMER['CUST_CODE']==$cFILTER_CUSTOMER){
						echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$cFILTER_CUSTOMER' >$aCUSTOMER[CUST_NAME]</option>";
					} else
						echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME]</option>";
				}
				echo '</select>';

				LABEL([1,2,2,6], '700', S_MSG('PE62','Lokasi'), '', 'right');
				SELECT([2,3,4,6], 'PILIH_LOKASI', "FILT_PERSON('$cFILTER_JABATAN', '$cFILTER_CUSTOMER', '$cFILTER_GROUP', this.value)");
					echo "<option value=''  > All</option>";
					while($aLOKASI=SYS_FETCH($qLOCS)){
						if($aLOKASI['KODE_LOKS']==$cFILTER_LOKASI){
							echo "<option value='$aLOKASI[KODE_LOKS]' selected='$aREC_PERSON[LOKS_CODE]' >$aLOKASI[LOKS_NAME]</option>";
						} else
							echo "<option value='$aLOKASI[KODE_LOKS]'  >$aLOKASI[LOKS_NAME]</option>";
					}
				echo '</select>';
			}
			CLEAR_FIX();
			TDIV();
			?>
				<table data-order='[[ 18, "asc" ]]' cellspacing="0" id="example" class="table table-small-font table-bordered table-striped">
					<?php
						$aHEAD = [$cKODE_PEG, $cNAMA_PEG, $cJABATAN];
						$aFIELD=[];
						if($ada_OUTSOURCING>0)	array_splice($aHEAD, 3, 0, [$cCUSTOMER, $cLOKASI]);
						for ($I=1; $I < 13; $I++) {
							array_push($aHEAD, (string)$I);
							array_push($aFIELD, 'SLIP_'.str_pad($I, 2, '0', STR_PAD_LEFT));
						}
						array_push($aHEAD, $cEMAIL);
						THEAD($aHEAD);
						echo '<tbody>';
							while($aREC_PERSON=SYS_FETCH($qQUERY)) {
								echo '<tr>';
									echo "<td style='width: 1px;'></td>";
									echo "<td ><span>".$aREC_PERSON['PRSON_CODE']."  </span></td>";
									echo "<th><span>".DECODE($aREC_PERSON['PRSON_NAME'])." </span></th>";
									echo "<td><span>".$aREC_PERSON['JOB_NAME']." </span></td>";
									if($ada_OUTSOURCING==1) {
										echo "<td><span>".DECODE($aREC_PERSON['CUST_NAME'])." </span></td>";
										echo "<td><span>".$aREC_PERSON['LOKS_NAME']." </span></td>";
									}

									for ($I=1; $I < 13; $I++) {
										echo "<td style='text-align:center;'><span>".$aREC_PERSON['SLIP_'.str_pad($I, 2, '0', STR_PAD_LEFT)]."  </span></td>";
									}
									$rEMAIL = '';
									$qEMAIL = OpenTable('PeopleEMail', "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$aREC_PERSON[PRSON_CODE]' and REC_ID not in ( select DEL_ID from logs_delete )");
									if($aEMAIL=SYS_FETCH($qEMAIL))	$rEMAIL=$aEMAIL['PPL_EMAIL'];
									echo "<td><span>".$rEMAIL."  </span></td>";
								echo '</tr>';
							}
					echo '</tbody>';
				echo '</table>';
			eTDIV();
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
    END_WINDOW();
	APP_LOG_ADD( $cHEADER, 'prs_slip_stts.php:'.$cAPP_CODE);
	SYS_DB_CLOSE($DB2);
?>
<script>
function FILT_PERSON(p_JAB, p_CUST, p_GROUP, p_LOKS='') {
window.location.assign("?_j="+p_JAB + "&_c="+p_CUST + "&_g="+p_GROUP + "&_l="+p_LOKS);
}

function FILT_GROUP(p_GROUP) {
window.location.assign("?_g="+p_GROUP);
}

</script>
