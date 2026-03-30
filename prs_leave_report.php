<?php
//	prs_leave_report.php
//	Laporan cuti Karyawan
// TODO : range by date

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();
	$cHELP_FILE = 'Doc/Laporan - Cuti.pdf';

    $cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	$cHEADER		= S_MSG('PE60','Laporan Cuti');

	$qLEAVE=OpenTable('TrLeave', "A.APP_CODE='$cAPP_CODE' and A.DELETOR=''", '', "LEV_DATE1 limit 1");
	$aFIRST_LEAVE = SYS_FETCH($qLEAVE);
	$cFIRST_LEAVE = ($aFIRST_LEAVE ? $aFIRST_LEAVE['LEV_DATE1'] : '2020');
	$nTAHUN_AWAL	= substr($cFIRST_LEAVE,0,4);
	$nTAHUN_AKHIR   = date('Y');
	$nTHN   = date('Y');
	if (isset($_GET['_y'])) $nTHN=$_GET['_y'];

    $ada_OUTSOURCING=IS_OUTSOURCING($cAPP_CODE);
	if($ada_OUTSOURCING) {
		$cCUSTOMER 	= S_MSG('RS04','Customer');
		$cLOKASI	= S_MSG('PF16','Lokasi');
	}

	$cScope = "APP_CODE='$cAPP_CODE' and DELETOR=''";
	$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	if (SYS_ROWS($qSCOPE)>0) $cScope .= " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR='')";
	$qCUSTOMER=OpenTable('TbCustomer', $cScope, '', 'CUST_NAME');

	$cFILTER_CUSTOMER='';
	$cFILT_CUST_NAME='';
	if (isset($_GET['_c'])) $cFILTER_CUSTOMER=$_GET['_c'];

	$cFILTER_GROUP='';
	if (isset($_GET['_g'])) $cFILTER_GROUP=$_GET['_g'];

	$qQUERY=OpenTable('TrLeave', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and year(LEV_DATE1)='$nTHN'". ($cFILTER_GROUP=='' ? '' : " and KODE_GRP='$cFILTER_GROUP'"). ($cFILTER_CUSTOMER=='' ? '' : " and F.CUST_CODE='$cFILTER_CUSTOMER'"), '', 'LEAVE_NO desc');
	DEF_WINDOW($cHEADER, 'collapse');
		TFORM($cHEADER, '', [], $cHELP_FILE, '*');
			LABEL([1,1,2,6], '700', 'Tahun');
			SELECT([1,1,2,6], 'THN', "FILT_PERSON(this.value, '', '')", '', '');
				for ($i=$nTAHUN_AWAL; $i<=$nTAHUN_AKHIR; $i++){
					if ($i==$nTHN)
						echo "<option value=$i selected>$i</option>";
					else
						echo "<option value=$i>$i</option>";
				}
			echo '</select>';

			if($ada_OUTSOURCING) {
				LABEL([1,1,2,4], '700', S_MSG('P060','Klmpk'));
				$qGROUP=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'NAMA_GRP');
				SELECT([3,3,2,6], 'THN', "FILT_PERSON('$nTHN', this.value, '')", '', '');
					echo "<option value=''  > All</option>";
					while($aGROUP=SYS_FETCH($qGROUP)){
						if($aGROUP['KODE_GRP']==$cFILTER_GROUP){
							echo "<option value='$aGROUP[KODE_GRP]' selected='$aREC_LEAVE[KODE_GRP]' >$aGROUP[NAMA_GRP]</option>";
						} else
							echo "<option value='$aGROUP[KODE_GRP]'  >$aGROUP[NAMA_GRP]</option>";
					}
				echo '</select>';
				LABEL([1,1,2,4], '700', $cCUSTOMER);
				TDIV(5,5,5,112);
					SELECT([3,3,2,6], 'PILIH_CUSTOMER', "FILT_PERSON('$nTHN', '$cFILTER_GROUP', this.value)", '', 'select2');
					echo "<option value=''  > All</option>";
					while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
						if($aCUSTOMER['CUST_CODE']==$cFILTER_CUSTOMER){
							echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$aREC_LEAVE[CUST_CODE]' >$aCUSTOMER[CUST_NAME]</option>";
						} else
							echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME]</option>";
					}
					echo '</select>';
				echo '</div>';
			}
			CLEAR_FIX();
			TDIV();
				TABLE('example');
					$aHEAD = ['No', S_MSG('PA02','Kode Peg'),S_MSG('PA03','Nama Pegawai'), S_MSG('PE06','Tgl. Mulai Cuti'), S_MSG('PE18','Tanggal akhir cuti'), 'Hari', 'Sisa'];
					if($ada_OUTSOURCING) {
						array_push($aHEAD, $cCUSTOMER);
						array_push($aHEAD, $cLOKASI);
					}
					array_push($aHEAD, S_MSG('PA43','Jabatan'));
					THEAD($aHEAD);
					echo '<tbody>';
						while($aREC_LEAVE=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								echo "<td style='width: 1px;'></td>";
								echo "<td><span>".$aREC_LEAVE['LEAVE_NO']."  </span></td>";
								echo "<td><span>".$aREC_LEAVE['PRSON_CODE']."  </span></td>";
								echo "<th><span>".DECODE($aREC_LEAVE['PRSON_NAME'])." </span></th>";
								echo "<td><span>".date("d-M-Y", strtotime($aREC_LEAVE['LEV_DATE1']))." </span></td>";
								echo "<td><span>".date("d-M-Y", strtotime($aREC_LEAVE['LEV_DATE2']))." </span></td>";
								echo "<td><span>".$aREC_LEAVE['DURATION']."  </span></td>";
								$qSISA=OpenTable('Tr_Leave', "APP_CODE='$cAPP_CODE' and DELETOR='' and year(LEV_DATE1)='$nTHN' and PRSON_CODE='$aREC_LEAVE[PRSON_CODE]'");
								$nSISA = 12;
								while($aSISA=SYS_FETCH($qSISA)) {
									$nSISA -= $aSISA['DURATION'];
								}
								echo "<td><span>".$nSISA."  </span></td>";
								if($ada_OUTSOURCING>0) {
									echo "<td><span>".DECODE($aREC_LEAVE['CUST_NAME'])." </span></td>";
									echo "<td><span>".$aREC_LEAVE['LOKS_NAME']." </span></td>";
								}
								echo "<td><span>".$aREC_LEAVE['JOB_NAME']."  </span></td>";
							echo '</tr>';
						}
					echo '</tbody>';
				echo '</table>';
			eTDIV();
		eTFORM('*');
    END_WINDOW();
	APP_LOG_ADD( $cHEADER, 'prs_leave_report.php:'.$cAPP_CODE);
	SYS_DB_CLOSE($DB2);
?>
<script>
function FILT_PERSON(p_THN, p_GROUP, p_CUST) {
	window.location.assign("?_y="+p_THN + "&_g="+p_GROUP + "&_c="+p_CUST);
}

</script>
