<?php
//	prs_resign_group.php
//	Group resign

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cHELP_FILE = 'Doc/Transaksi - Group Resign.pdf';

	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	$cHEADER		= S_MSG('PI90','Group Re-sign');

	$cKODE_PEG 		= S_MSG('PA02','Kode Peg');
	$cNAMA_PEG 		= S_MSG('PA03','Nama Pegawai');
	$cJABATAN 		= S_MSG('PA43','Jabatan');
	$cUMUR 			= S_MSG('TA54','Umur');
	$cMASUK 		= S_MSG('PI22','Masuk');
	$cTMK 			= S_MSG('PB67','Tanggal TMK');
	$cMASKER		= S_MSG('PB91','Masa Kerja');
	$cTMP_LAHIR		= S_MSG('PA05','Tempat Lahir');
	$cTGL_RESIGN	= S_MSG('PI87','Tanggal');
	$cALASAN		= S_MSG('PI84','Alasan Resign');
	$cNOTE_RESIGN	= S_MSG('PE08','Catatan');

    $cGROUP 	= S_MSG('P060','Klmpk');
    $cCUSTOMER 	= S_MSG('RS04','Customer');
    $cLOKASI	= S_MSG('PF16','Lokasi');
    
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
        if($aCUST_GROUP=SYS_FETCH($qCUST_GROUP)) {
            $cFILTER_GROUP=$aCUST_GROUP['KODE_GRP'];
        }
    }

	$cFILTER_CUSTOMER='';
	if (isset($_GET['_c'])) $cFILTER_CUSTOMER=$_GET['_c'];
	if($cFILTER_CUSTOMER=='') {
		$qCUSTOMER = OpenTable('TbCustomer', "CUST_GROUP='$cFILTER_GROUP' and APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
		if($aCUST_FIRST = SYS_FETCH($qCUSTOMER))
			$cFILTER_CUSTOMER=$aCUST_FIRST['CUST_CODE'];
	}

	$ADD_LOG	= APP_LOG_ADD($cHEADER);

	$cFILT_DATA = "A.APP_CODE='" . $cAPP_CODE ."' and A.DELETOR='' and PRSON_SLRY<2 and R.RESIGN_DATE is NULL";
	if ($cFILTER_JABATAN!='') 	$cFILT_DATA.= " and P6.JOB_CODE='".$cFILTER_JABATAN."'";
	if ($cFILTER_CUSTOMER!='')	$cFILT_DATA.= " and P6.CUST_CODE='".$cFILTER_CUSTOMER."'";
	if ($cFILTER_GROUP!='')		$cFILT_DATA.= " and CG.KODE_GRP='".$cFILTER_GROUP."'";
	$cFILT_LOCA = $cFILT_DATA;
	if ($cFILTER_LOKASI!='') 	$cFILT_DATA.= " and P6.KODE_LOKS='".$cFILTER_LOKASI."'";
    $qCUSTOMER = OpenTable('TbCustomer', "CUST_GROUP='$cFILTER_GROUP' and APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
	$qLOCS=OpenTable('PersonSalary', $cFILT_LOCA.UserScope($cUSERCODE), 'LOC.LOKS_CODE', 'LOC.LOKS_NAME');
	$qACTIVE_PERSON=OpenTable('PersonSalary', $cFILT_DATA.UserScope($cUSERCODE));

	$can_PRINT=1;
	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

switch($cACTION){
	default:
		DEF_WINDOW($cHEADER, 'collapse');
			TFORM($cHEADER, '?_a=proses&_g='.$cFILTER_GROUP.'&_j='.$cFILTER_JABATAN.'&_c='.$cFILTER_CUSTOMER.'&_l='.$cFILTER_LOKASI, [], $cHELP_FILE, '*');
				TDIV();
						LABEL([1,2,2,4], '700', $cGROUP);
						echo '<select name="PILIH_GROUP" class="col-lg-2 col-sm-4 col-xs-8 form-label-900" onchange="FILT_PERSON('. "'".$cFILTER_JABATAN."'".', '. "''".', this.value, '. "''".')">';
						$qCUSTGROUP=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");
						while($aCUSTGRUP=SYS_FETCH($qCUSTGROUP)){
							if($aCUSTGRUP['KODE_GRP']==$cFILTER_GROUP){
								echo "<option value='$aCUSTGRUP[KODE_GRP]' selected='$cFILTER_GROUP' >$aCUSTGRUP[NAMA_GRP]</option>";
							} else
								echo "<option value='$aCUSTGRUP[KODE_GRP]'  >$aCUSTGRUP[NAMA_GRP]</option>";
						}
						echo '</select>';
						LABEL([1,2,2,4], '700', $cJABATAN);
					?>

						<select name="PILIH_JABTN" class="col-lg-2 col-sm-4 col-xs-8 form-label-900" onchange="FILT_PERSON(this.value, '<?php echo $cFILTER_CUSTOMER?>', '<?php echo $cFILTER_GROUP?>', '<?php echo ( $cFILTER_LOKASI!='' ? $cFILTER_LOKASI : '')?>')">
						<?php 
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
							LABEL([1,2,2,4], '700', $cCUSTOMER);
							$cHTML = '<select name="PILIH_CUSTOMER" class="col-lg-2 col-sm-4 col-xs-8 form-label-900" onchange="FILT_PERSON('. "'".$cFILTER_JABATAN. "', this.value, '".$cFILTER_GROUP."', '". $cFILTER_LOKASI ."'". ')">';
							echo $cHTML;
							echo "<option value=''  > All</option>";
							while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
								if($aCUSTOMER['CUST_CODE']==$cFILTER_CUSTOMER){
									echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$cFILTER_CUSTOMER' >$aCUSTOMER[CUST_NAME]</option>";
								} else
									echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME]</option>";
							}
							echo '</select>';

							LABEL([1,2,2,4], '700', S_MSG('PE62','Lokasi'));
							$cHTML = '<select name="PILIH_LOKASI" class="col-lg-2 col-sm-4 col-xs-8 form-label-900" onchange="FILT_PERSON('. "'".$cFILTER_JABATAN. "', '".$cFILTER_CUSTOMER."', '".$cFILTER_GROUP."', this.value)". '">';
							echo $cHTML;
							echo "<option value=''  > All</option>";
							while($aLOKASI=SYS_FETCH($qLOCS)){
								if($aLOKASI['LOKS_CODE']==$cFILTER_LOKASI){
									echo "<option value='$aLOKASI[LOKS_CODE]' selected='$cFILTER_LOKASI' >$aLOKASI[LOKS_NAME]</option>";
								} else
									echo "<option value='$aLOKASI[LOKS_CODE]'  >$aLOKASI[LOKS_NAME]</option>";
							}
							echo '</select>';
							CLEAR_FIX();
						?>

					<div class="content-body">    
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<table data-order='[[ 1, "asc" ]]' cellspacing="0" id="example" class="table table-small-font table-bordered table-striped">
									<?php
										$aHEAD = [$cKODE_PEG, $cNAMA_PEG, $cJABATAN, $cMASUK, $cTMK, $cMASKER, 'Bln'];
										array_splice($aHEAD, 3, 0, [$cCUSTOMER, $cLOKASI]);
										THEAD($aHEAD);
										echo '<tbody>';
											while($aREC_PERSON=SYS_FETCH($qACTIVE_PERSON)) {
												$dJOB_DATE=$aREC_PERSON['JOB_DATE'];
												$Birth = new DateTime($dJOB_DATE);
												if(!$Birth) $Birth = new DateTime($aREC_PERSON['JOIN_DATE']);
												if(!$Birth) $Birth = new DateTime();
												$Now = new DateTime();
												$Interval = $Now->diff($Birth);
												$nMAS_KER = $Interval->y+1;
												$nMONTH = $Interval->m;
												$aCOL=[$aREC_PERSON['PRSON_CODE'], DECODE($aREC_PERSON['PRSON_NAME']), DECODE($aREC_PERSON['JOB_NAME']), 
													DECODE($aREC_PERSON['CUST_NAME']), DECODE($aREC_PERSON['LOKS_NAME']), $aREC_PERSON['JOIN_DATE'],
													$dJOB_DATE, (string)$nMAS_KER, (string)$nMONTH];
												TDETAIL($aCOL, [0,0,0,0,0,2,2,2,2]);
											}
										echo '</tbody>';
								eTABLE();
							eTDIV();
						eTDIV();
					echo '</div><br><br>';
					LABEL([2,2,3,6], '700', $cTGL_RESIGN);
					INPUT_DATE([2,2,3,6], '900', 'RESIGN_DATE', date('Y-m-d'), '', '', '', 0, '', 'fix');
					LABEL([2,2,3,6], '700', $cALASAN);
					INPUT('text', [8,8,8,6], '900', 'RES_REASON', '', '', '', '', 0, '', 'fix');
					LABEL([2,2,3,6], '700', $cNOTE_RESIGN);
					INPUT('text', [8,8,8,6], '900', 'RES_NOTES', '', '', '', '', 0, '', 'fix');
					SAVE('Proses');
				eTDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		APP_LOG_ADD( $cHEADER, 'prs_resign_group.php:'.$cAPP_CODE);
		SYS_DB_CLOSE($DB2);	break;

case 'proses':
	$cDATE=$_POST['RESIGN_DATE'];
	$cRES_REASON=$_POST['RES_REASON'];
	$cRES_NOTES=$_POST['RES_NOTES'];

	if($cDATE==''){
		MSG_INFO('Tanggal masih kosong');
		return;
	}
	if($cRES_REASON==''){
		MSG_INFO('Alasan resign tidak boleh kosong');
		return;
	}
	$qLAST=OpenTable('PrsResign', "APP_CODE='$cAPP_CODE'", '', 'RESIGN_REC desc limit 1');
	$nLAST = 1;
	if($rABS = SYS_FETCH($qLAST))	$nLAST = $rABS['RESIGN_NO']+1;

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

    ///// 2025-05-24
	/////////////////////////////////


	$qPROCESS=OpenTable('PersonSalary', $cFILT_DATA.UserScope($cUSERCODE));
	while($aREC_PERSON=SYS_FETCH($qPROCESS)) {
		RecCreate('PrsResign', ['RESIGN_NO', 'RESIGN_DATE', 'RESIGN_REASON', 'PRSON_CODE', 'RESIGN_NOTE', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'],
			[$nLAST, $cDATE, $cRES_REASON, $aREC_PERSON['PRSON_CODE'], $cRES_NOTES, $cAPP_CODE, $cUSERCODE, date("Y-m-d H:i:s")]);
		
		$sql = "DELETE FROM people_device WHERE PEOPLE_CODE = :people_code AND APP_CODE = :app_code";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['people_code' => $aREC_PERSON['PRSON_CODE'], 'app_code' => $cAPP_CODE]);
		
		$nLAST++;
	}

	header('location:prs_tr_resign.php');
	break;
}
?>
<script>
function FILT_PERSON(p_JAB, p_CUST, p_GRUP, p_LOKS='') {
window.location.assign("?_j="+p_JAB + "&_c="+p_CUST + "&_g="+p_GRUP + "&_l="+p_LOKS);
}

</script>
