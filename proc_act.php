<?php
//	proc_act.php //

	require_once "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE']; 
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Proses -Aktivasi.pdf';

	$cHEADER 	= S_MSG('PF71','Aktivasi Absen Karyawan');

	$cACTION 	= (isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');
	$cID_DEVICE = (isset($_GET['_d']) ? $cID_DEVICE=$_GET['_d'] : '');
	$cPEOPLE_NAME = (isset($_GET['_n']) ? $cPEOPLE_NAME=$_GET['_n'] : '');

	$cNAMA_PEG 	= S_MSG('PA03','Nama Pegawai');
	$cNOMOR_HP  = S_MSG('F006','Nomor HP');
	$cDEVICE    = S_MSG('PF72','Device');

	$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	$nSCOPE = ( SYS_FETCH($qSCOPE) ? SYS_ROWS($qSCOPE) : 0);
	$is_OUTSOURCING=IS_OUTSOURCING($cAPP_CODE);

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$qNOT_ACTIVE=OpenTable('Aktivasi', "PEOPLE_CODE='' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		DEF_WINDOW($cHEADER);
			TFORM($cHEADER, '', [], $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cNAMA_PEG, $cNOMOR_HP, $cDEVICE], '', []);
						echo '<tbody>';
							
							while($aREC_AKTIVASI=SYS_FETCH($qNOT_ACTIVE)) {
								$name = htmlspecialchars($aREC_AKTIVASI['PEOPLE_NAME']);
								// TDETAIL([$aGROUP_APP['CUST_NAME'], $aGROUP_APP['LOKS_NAME'], $aGROUP_APP['JOB_NAME'], $aGROUP_APP['PEOPLE_NAME'], $aGROUP_APP['LEAVE_NOTE']], 
								// 	[], '', ["<a href='?_a=".md5('update')."&_d=".$aREC_AKTIVASI['DEVICE_ID']."'>", "<a href='?_a=".md5('update')."&_d=".$aREC_AKTIVASI['PEOPLE_NAME'].">", '']);
								echo '<tr>';
									echo '<td class=""><div class="star"><i class="fa fa-calendar-o icon-xs icon-default"></i></div></td>';
									echo '<td><span><a href="?_a='.md5('update').'&_d='.$aREC_AKTIVASI['DEVICE_ID'].'&_n='.$name.'">'.$aREC_AKTIVASI['PEOPLE_NAME'].'</a></span></td>';
									echo '<td><span><a href="?_a='.md5('update').'&_d='.$aREC_AKTIVASI['DEVICE_ID'].'&_n='.$name.'">'.$aREC_AKTIVASI['PEOPLE_HP'].'</span></td>';
									echo "<td><span>".$aREC_AKTIVASI['DEVICE_ID']."  </span></td>";
								echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		APP_LOG_ADD($cHEADER, 'Aktivasi View');
		SYS_DB_CLOSE($DB2);	
		break;

	case md5('update'): //3ac340832f29c11538fbe2d6f75e8bcc
		$name = htmlspecialchars($_GET['_n']);
		$qAKTIVASI=OpenTable('Aktivasi', 'DEVICE_ID="'.$cID_DEVICE.'" and PEOPLE_CODE="" and PEOPLE_NAME="'.$name.'" and APP_CODE="'.$cAPP_CODE.'" and DELETOR=""');
		$REC_AKTIVASI=SYS_FETCH($qAKTIVASI);

		$cFLT = "M.PRSON_SLRY<2 and P.APP_CODE='$cAPP_CODE' and P.DELETOR='' and P.PEOPLE_CODE not in ( select PRSON_CODE from prs_resign where APP_CODE='$cAPP_CODE' and P.DELETOR='')";
		if ($nSCOPE>0) $cFLT .= " and C.CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE')";
		if($is_OUTSOURCING){
			$qPEOPLE=OpenTable('PeopleCustomer', $cFLT, '', 'PEOPLE_NAME');
		} else {
			$qPEOPLE=OpenTable('PeopleJob', $cFLT, '', 'PEOPLE_NAME');
		}
		$allPEOPLE = ALL_FETCH($qPEOPLE);
		
		$can_DELETE = TRUST($cUSERCODE, 'PRS_DEACTIVATE_UPD');
		DEF_WINDOW($cHEADER);
			$aACT=[];
			if ($can_DELETE==1) {
				array_push($aACT, '<a href="?_a='.md5('DELETE_REC').'&_d='.$REC_AKTIVASI['DEVICE_ID']. '" onClick="return confirm('. "'". S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?'). "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>'.S_MSG('F304','Delete').'</a>');
			}
			TFORM($cHEADER, '?_a=rubah&_d='.$REC_AKTIVASI['DEVICE_ID'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cNAMA_PEG);
					INPUT('text', [5,5,5,6], '900', 'EDIT_NAMA_PEG', $REC_AKTIVASI['PEOPLE_NAME'], '', '', '', 0, 'disable', 'fix', 'Nama pegawai yang absen nya mau diaktivasi');
					LABEL([3,3,3,6], '700', $cNOMOR_HP);
					INPUT('text', [5,5,5,6], '900', 'EDIT_NO_HP', $REC_AKTIVASI['PEOPLE_HP'], '', '', '', 0, 'disable', 'fix', 'Nomor hp pegawai');
					LABEL([3,3,3,6], '700', $cDEVICE);
					INPUT('text', [5,5,5,6], '900', 'EDIT_DEVICE_ID', $REC_AKTIVASI['DEVICE_ID'], '', '', '', 0, 'disable', 'fix', 'Device Id hp');
					LABEL([3,3,3,6], '700', 'Pilih pegawai');
                	SELECT([6,6,6,6], 'PILIH_PEG', '', '', 'select2');
					if($is_OUTSOURCING){
						echo '<option></option>';
							$qCUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''" . ($nSCOPE==1 ? " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE')" : ""), '', 'CUST_NAME');
							while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
								echo '<optgroup label="'.$aCUSTOMER['CUST_NAME'].'">';
								$I=0;
								$nSIZE_ARRAY = count($allPEOPLE);
								while($I<$nSIZE_ARRAY-1) {
									if ($allPEOPLE[$I]['CUST_CODE']==$aCUSTOMER['CUST_CODE']) {
										$cSELECT = DECODE($allPEOPLE[$I]['PEOPLE_NAME'])."  /  ".$allPEOPLE[$I]['PEOPLE_CODE']."  /  ".$allPEOPLE[$I]['PRS_PHN']."  /  ".$allPEOPLE[$I]['LOKS_NAME']." / ".$allPEOPLE[$I]['JOB_NAME']." / TGL LAHIR:".$allPEOPLE[$I]['BIRTH_DATE']." / NO KTP:".$allPEOPLE[$I]['PRS_KTP'];
										$cVALUE = $allPEOPLE[$I]['PEOPLE_CODE'];
										echo '<option value="'.$cVALUE.'">'.$cSELECT.'</option>';
									}
									$I++;
								}
							}
					} else {
						$I=0;
						$nSIZE_ARRAY = count($allPEOPLE);
						echo '<option></option>';
						while($I<$nSIZE_ARRAY) {
								$cSELECT = DECODE($allPEOPLE[$I]['PEOPLE_NAME'])."  /  ".$allPEOPLE[$I]['PEOPLE_CODE']."  /  ".$allPEOPLE[$I]['PRS_PHN']."  /  ".$allPEOPLE[$I]['LOKS_NAME'];
								$cVALUE = $allPEOPLE[$I]['PEOPLE_CODE'];
								echo '<option value="'.$cVALUE.'">'.$cSELECT.'</option>';
							$I++;
						}
					}
					echo '</select>';
					?>
					<h2 class="title pull-left" style="color:white;"> #</h2>
					<div class="clearfix"></div>
					<div class="text-left">
						<button  onclick="saveAct()" type="submit" value=Simpan class="btn btn-info btn-lg"><?php echo S_MSG('F301','Save')?></button>
						<input type="button" class="btn btn-lg pull-right" value=Close onclick=window.location.href='proc_act.php'>
					</div>
<?php
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
		break;

	case 'rubah':
		$cDEV = $_GET['_d'];
		$cPEG = $_POST['PILIH_PEG'];
		if ($cPEG) {
			$NOW = date("Y-m-d H:i:s");
			RecUpdate('Aktivasi', ['DELETOR', 'DEL_DATE'], [$_SESSION['gUSERCODE'], $NOW], "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPEG'");
			RecUpdate('Aktivasi', ['PEOPLE_CODE', 'UP_DATE', 'UPD_DATE'], 
				[$cPEG, $_SESSION['gUSERCODE'], $NOW], "APP_CODE='$cAPP_CODE' and DEVICE_ID='$cDEV'");
		}
		APP_LOG_ADD($cHEADER, 'Aktivasi : '.$cPEG);
		SYS_DB_CLOSE($DB2);	
		header('location:proc_act.php');	
		break;
	
	case md5('DELETE_REC'):
		$NOW = date("Y-m-d H:i:s");
		$cDEV=$_GET['_d'];
		RecUpdate('Aktivasi', ['DELETOR', 'DEL_DATE'], [$_SESSION['gUSERCODE'], $NOW], "APP_CODE='$cAPP_CODE' and DEVICE_ID='$cDEV'");
		APP_LOG_ADD($cHEADER, 'Aktivasi delete : '.$cDEV);
		SYS_DB_CLOSE($DB2);	
		header('location:proc_act.php');
		break;
	}
?>

<script>
	function saveAct() {
		var select = document.getElementById("s2example-1");
		var value = select.options[select.selectedIndex].value;
		console.log(value);
    }
</script>