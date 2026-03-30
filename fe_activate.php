<?php
//	fe_activate.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

    $cAPP_CODE = $_SESSION['data_FILTER_CODE']; 
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Proses - Aktivasi.pdf';

	$cHEADER 	= 'Aktivasi Aplikasi TAD';

	$cACTION 	= (isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');
	$cID_DEVICE = (isset($_GET['_d']) ? $cID_DEVICE=$_GET['_d'] : '');
	$cPEOPLE_NAME = (isset($_GET['_n']) ? $cPEOPLE_NAME=$_GET['_n'] : '');

	$cNAMA_PEG 	= S_MSG('HO12','Nama');
	$cLOKASI	= S_MSG('PF16','Lokasi');
	$JABATAN	= S_MSG('PF13','Jabatan');

	$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	$nSCOPE = ( SYS_FETCH($qSCOPE) ? SYS_ROWS($qSCOPE) : 0);

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
        RecUpdate('sys_msg', ['MESSG_DATE'], [date("Y-m-d h:m:s")], 'true order by MESSG_DATE limit 1');
		DEF_WINDOW($cHEADER);
			TFORM($cHEADER, '', [], $cHELP_FILE);
			TDIV();
				TABLE('example');
					THEAD([$cNAMA_PEG, $cLOKASI, $JABATAN]);
					echo '<tbody>';
						$qNOT_ACTIVE=OpenTable('FeDevice', "PEOPLE_CODE='' and NON_ACTIVE=0 and (APP_CODE='$cAPP_CODE' or APP_CODE='')");
						while($aREC_AKTIVASI=SYS_FETCH($qNOT_ACTIVE)) {
							// echo '<tr>';
							// 	echo '<td class=""><div class="star"><i class="fa fa-mobile-phone icon-xs icon-default"></i></div></td>';
							// 	echo "<td><span><a href='?_a=".md5('update')."&_d=".$aREC_AKTIVASI['REC_ID']."&_n=$aREC_AKTIVASI[PEOPLE_NAME]'>".$aREC_AKTIVASI['PEOPLE_NAME']."</a></span></td>";
							// 	echo "<td><span><a href='?_a=".md5('update')."&_d=".$aREC_AKTIVASI['REC_ID']."&_n=$aREC_AKTIVASI[PEOPLE_NAME]'>".$aREC_AKTIVASI['PEOPLE_LOCS']."  </span></td>";
							// 	echo "<td><span>".$aREC_AKTIVASI['PEOPLE_JOB']."  </span></td>";
							// echo '</tr>';
							$cHREFF="<a href='?_a=".md5('update')."&_d=".$aREC_AKTIVASI['REC_ID']."&_n=$aREC_AKTIVASI[PEOPLE_NAME]'>";
							TDETAIL([$aREC_AKTIVASI['PEOPLE_NAME'], DECODE($aREC_AKTIVASI['PEOPLE_LOCS']), DECODE($aREC_AKTIVASI['PEOPLE_JOB'])], [], '', [$cHREFF, $cHREFF, $cHREFF]);
						}
					echo '</tbody>';
				eTABLE();
			eTDIV();
			eTFORM('*');
		END_WINDOW();
		break;

	case md5('update'):
		$can_DELETE = TRUST($cUSERCODE, 'FE_ACTIVATE');
		$cREC=$_GET['_d'];
		$qAKTIVASI=OpenTable('FeDevice', "REC_ID='$cREC'");
		$REC_AKTIVASI=SYS_FETCH($qAKTIVASI);

		$cFLT = "M.PRSON_SLRY<2 and P.APP_CODE='$cAPP_CODE' and P.DELETOR='' and P.PEOPLE_CODE not in ( select PRSON_CODE from prs_resign where APP_CODE='$cAPP_CODE' and P.DELETOR='')";
		if ($nSCOPE>0) $cFLT .= " and C.CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE')";
		$qPEOPLE=OpenTable('PeopleCustomer', $cFLT, '', 'PEOPLE_NAME');
		$allPEOPLE = ALL_FETCH($qPEOPLE);
		DEF_WINDOW($cHEADER);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DELETE_REC').'&_r='. $REC_AKTIVASI['REC_ID']. '" onClick="return confirm('. "'".S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?'). "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cHEADER, '?_a=save&_r='. $cREC, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cNAMA_PEG);
					INPUT('text', [5,5,5,6], '900', 'EDIT_NAMA_PEG', $REC_AKTIVASI['PEOPLE_NAME'], '', '', '', 0, 'disable', 'fix', 'Nama TAD yang aplikasi nya mau di aktivasi');
					LABEL([3,3,3,6], '700', $cLOKASI);
					INPUT('text', [5,5,5,6], '900', 'EDIT_LOKASI', $REC_AKTIVASI['PEOPLE_LOCS'], '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $JABATAN);
					INPUT('text', [5,5,5,6], '900', 'EDIT_JOB', $REC_AKTIVASI['PEOPLE_JOB'], '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', S_MSG('PF72','Device'));
					INPUT('text', [3,3,3,6], '900', 'EDIT_DEV', $REC_AKTIVASI['DEVICE_ID'], '', '', '', 0, 'disable', 'fix', 'Device Id');
					echo '<br>';
					LABEL([3,3,3,6], '500', 'Pilih pegawai');
					SELECT([10,10,10,10], 'PILIH_PEG', '', '', 'select2');
						echo '<option></option>';
							if ($ada_OUTSOURCING) {
								$qCUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''" . ($nSCOPE==1 ? " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE')" : ""), '', 'CUST_NAME');
								while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
									echo '<optgroup label="'.$aCUSTOMER['CUST_NAME'].'">';
									$I=0;
									$nSIZE_ARRAY = count($allPEOPLE);
									while($I<$nSIZE_ARRAY-1) {
										if ($allPEOPLE[$I]['CUST_CODE']==$aCUSTOMER['CUST_CODE']) {
											$cSELECT = DECODE($allPEOPLE[$I]['PEOPLE_NAME'])."  /  ".$allPEOPLE[$I]['PEOPLE_CODE']."  /  ".$allPEOPLE[$I]['PRS_PHN']."  /  ".$allPEOPLE[$I]['LOKS_NAME'];
											$cVALUE = $allPEOPLE[$I]['PEOPLE_CODE'];
											echo '<option value="'.$cVALUE.'">'.$cSELECT.'</option>';
										}
										$I++;
									}
								}
							} else {
								$qPERSON=OpenTable('People', "APP_CODE='$cAPP_CODE' and DELETOR=''");
								while($aPEOPLE=SYS_FETCH($qPERSON)) {
									$cSELECT = DECODE($aPEOPLE['PEOPLE_NAME'])."  /  ".$aPEOPLE['PEOPLE_CODE'];
									$cVALUE = $aPEOPLE['PEOPLE_CODE'];
									echo '<option value="'.$cVALUE.'">'.$cSELECT.'</option>';
								}
							}
						echo '</optgroup>';
					echo '</select>';
					CLEAR_FIX();
					echo '<br>';
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
		END_WINDOW();
		break;

	case 'save':
		$cREC = $_GET['_r'];
		$cPEG = $_POST['PILIH_PEG'];
		if ($cPEG == '') {
            MSG_INFO('Data Registrasi masih kosong');
            return;
        }
		RecUpdate('FeDevice', ['NON_ACTIVE'], [1], "APP_CODE='$cAPP_CODE' and PEOPLE_CODE='$cPEG'");
		RecUpdate('FeDevice', ['PEOPLE_CODE', 'APP_CODE'], [$cPEG, $cAPP_CODE], "REC_ID='$cREC'");
		header('location:fe_activate.php');	
		break;
	
	case md5('DELETE_REC'):
		$cREC=$_GET['_r'];
		RecUpdate('FeDevice', ['NON_ACTIVE'], [1], "REC_ID='$cREC'");
		header('location:fe_activate.php');
		break;
	}
?>
