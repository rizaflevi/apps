<?php
//	check_list_report.php //
//	Laporan check list

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	$cHEADER        = S_MSG('CL81','Laporan Check List Karyawan');
  
	$cTANGGAL		= S_MSG('RS02','Tanggal');
	
	$sPERIOD1=$_SESSION['sCURRENT_PERIOD'];		// Y/m
	if (isset($_GET['PERIOD']))	$sPERIOD1 = $_GET['PERIOD'];
	// print_r2($sPERIOD1);
	$cFILTER_CUST=(isset($_GET['_c']) ? $cFILTER_CUST=$_GET['_c'] : '');
	$cFILTER_JABATAN=(isset($_GET['_j']) ? $cFILTER_JABATAN=$_GET['_j'] : '');
	$cFILTER_LOKASI=(isset($_GET['_l']) ? $cFILTER_LOKASI=$_GET['_l'] : '');

	$nScope = 0;
	$qUSR_SCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	$nScope = SYS_ROWS($qUSR_SCOPE);

	$qCUSTOMER=OpenTable('TbScopeCeklis', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete )". ($nScope>1 ? " and CLS_CUST in ( select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR='')" : ""), 'B.CUST_CODE', 'B.CUST_NAME');
	$qLOKASI=OpenTable('TbScopeCeklis', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete )", 'L.LOKS_CODE', 'L.LOKS_CODE');
	$qJABATAN=OpenTable('TbScopeCeklis', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete )", "J.JOB_CODE", 'J.JOB_NAME');

	$aCUSTOMER=SYS_FETCH($qCUSTOMER);
	if ($cFILTER_CUST=='')	$cFILTER_CUST=$aCUSTOMER['CUST_CODE'];

	$cPERSON_FILTER = "A.APP_CODE='$cAPP_CODE' and A.CLS_CUST='". ($cFILTER_CUST!='' ? $cFILTER_CUST : "") . "' and A.CLS_CUST is not null";
	if ($cFILTER_LOKASI!='') $cPERSON_FILTER.=" and A.CLS_LOC='$cFILTER_LOKASI'";
	if ($cFILTER_JABATAN!='') $cPERSON_FILTER.=" and A.CLS_JOB='$cFILTER_JABATAN'";
	// $cPERSON_FILTER .= " and R.RESIGN_DATE is null";
	// $cPERSON_FILTER .= " and PM.PRSON_SLRY < 2";
	$qSCOPE	= OpenTable('TbScopeCeklis', $cPERSON_FILTER, '', 'A.CLS_CUST, A.CLS_LOC, A.CLS_JOB');

	$cHDR_BACK_CLR 	= S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	DEF_WINDOW($cHEADER, 'collapse', 'prd');
		TFORM($cHEADER, '', [], 'Doc/Checlist Report.pdf', '*');
			LABEL([1,1,1,4], '700', S_MSG('RS04','Customer'), '', 'right');
			SELECT([3,3,3,8], 'PILIH_CUSTOMER', "FILT_CHECKLIST(this.value, '', '', 'r')");
				echo '<option value=""  >'. $aCUSTOMER['CUST_NAME'].' </option>';
				while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
					if($aCUSTOMER['CUST_CODE']==$cFILTER_CUST){
						echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$cFILTER_CUST' > $aCUSTOMER[CUST_NAME]</option>";
					} else
						echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME]</option>";
				}
			echo '</select>';
			LABEL([1,1,1,4], '700', S_MSG('PF16','Lokasi'), '', 'right');
			SELECT([3,3,3,8], 'PILIH_LOKASI', "FILT_CHECKLIST('$cFILTER_CUST', this.value, '$cFILTER_JABATAN', 'r')");
				echo '<option value=""  > All </option>';
				while($aLOKASI=SYS_FETCH($qLOKASI)){
					if($aLOKASI['LOKS_CODE']==$cFILTER_LOKASI){
						echo "<option value='$aLOKASI[LOKS_CODE]' selected='$cFILTER_LOKASI' >$aLOKASI[LOKS_NAME]</option>";
					} else
						echo "<option value='$aLOKASI[LOKS_CODE]'  >$aLOKASI[LOKS_NAME]</option>";
				}
			echo '</select>';
			LABEL([1,1,1,4], '700', S_MSG('PF13','Jabatan'), '', 'right');
			SELECT([3,3,3,8], 'PILIH_JABATAN', "FILT_CHECKLIST('$cFILTER_CUST', '$cFILTER_LOKASI', this.value, 'r')");
				echo '<option value=""  > All </option>';
				while($aJABATAN=SYS_FETCH($qJABATAN)){
					if($aJABATAN['JOB_CODE']==$cFILTER_JABATAN){
						echo "<option value='$aJABATAN[JOB_CODE]' selected='$cFILTER_JABATAN' >$aJABATAN[JOB_NAME]</option>";
					} else
						echo "<option value='$aJABATAN[JOB_CODE]'  >$aJABATAN[JOB_NAME]</option>";
				}
			echo '</select>';
			CLEAR_FIX();
			// TABLE('example-4', 'table-responsive dataTables_scrollBody')
			?>

			<div class="content-body">
				<div class="table-responsive dataTables_scrollBody" style="position: relative; overflow: auto; width: 100%; max-height: 600px;">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<table cellspacing="0" id="example-4" class="table table-small-font table-bordered table-striped display nowrap" style="width:100%">
							<thead>
								<tr>
									<th colspan="3"> </th>
									<th colspan="31" style="text-align: center"><?php echo $cTANGGAL?></th>
								</tr>
								<tr>
									<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo S_MSG('PA02','Kode Peg')?></th>
									<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo S_MSG('PA03','Nama Pegawai')?></th>
									<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo S_MSG('H672','Nama Area')?></th>
									<?php
										for ($T=1; $T<=31; $T++) {
											echo '<th style="'.$cHDR_BACK_CLR.';">'.$T.'</th>';
										}
									?>
								</tr>
							</thead>

							<tbody>
								<?php
									while($aREC_SCOPE=SYS_FETCH($qSCOPE)) {
										$qDETAIL=OpenTable('CheckListRpt', "P6.APP_CODE='$cAPP_CODE' and P6.DELETOR='' and P6.CUST_CODE='$aREC_SCOPE[CLS_CUST]' and P6.KODE_LOKS='$aREC_SCOPE[CLS_LOC]' and P6.JOB_CODE='$aREC_SCOPE[CLS_JOB]' and R.RESIGN_DATE is null and P1.PEOPLE_NAME is not null", '', 'P6.PRSON_CODE');
										while($aREC_DETAIL=SYS_FETCH($qDETAIL)) {
											$cCUST = $aREC_DETAIL['CUST_CODE'];
											$qPEOPLE = OpenTable('People', "PEOPLE_CODE='$aREC_DETAIL[PRSON_CODE]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
											$aPEOPLE = SYS_FETCH($qPEOPLE);
											echo '<tr>';
											echo '<th ><span>'.$aREC_DETAIL['PRSON_CODE'].'  </span></th>';
											echo "<td><span>".DECODE($aPEOPLE['PEOPLE_NAME'])." </span></td>";
											$qTRANS=OpenTable('CheckRTrans', "T.PERSON_CODE='$aREC_DETAIL[PRSON_CODE]' and T.APP_CODE='$cAPP_CODE' and T.REC_ID not in ( select DEL_ID from logs_delete ) and 
												FROM_UNIXTIME(T.REC_ID/1000,'%Y-%m')='".$sPERIOD1."'", 'A.AREA_ID', 'T.PERSON_CODE, A.AREA_ID');
											while($aREC_DT=SYS_FETCH($qTRANS)) {
												$cAREA_ID=$aREC_DT['AREA_ID'];
												$qAREA=OpenTable('TbhArea', "A.AREA_CODE='$cAREA_ID' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", '', 'AREA_NAME');
												while($aAREA=SYS_FETCH($qAREA)) {
													echo '<th><span>'.$aAREA['AREA_NAME'].'</span></th><tr>';

													echo '<td><span></span><span></span></td>';
													echo '<td><span></span><span></span></td>';
													$q_ITEM=OpenTable('CheckAreaItem', "A.AREA_ID='$aAREA[AREA_CODE]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", '', 'A.ITEM_ID');
													while($a_ITEM=SYS_FETCH($q_ITEM)) {

														echo '<td ><span>'.DECODE($a_ITEM['CLI_DESC']).'</span></td>';
														$q_DTL_TRANS=OpenTable('CheckRTrans', "A.AREA_ID='$aAREA[AREA_CODE]' and ITEM_CODE='$a_ITEM[ITEM_ID]' and T.PERSON_CODE='$aREC_DETAIL[PRSON_CODE]' and T.APP_CODE='$cAPP_CODE' and T.REC_ID not in ( select DEL_ID from logs_delete ) and 
															FROM_UNIXTIME(T.REC_ID/1000,'%Y-%m')='".$sPERIOD1."'", 
															'', 'T.PERSON_CODE, A.AREA_ID');
														$aJAM = array_fill(1, 31, ' ');
														$I=0;
														while($a_DTL_TRANS=SYS_FETCH($q_DTL_TRANS)) {
															$nTGL = intval(substr($a_DTL_TRANS['TGL'], 8, 2));
															$aJAM[$nTGL] = $a_DTL_TRANS['JAM'];
														}
														for ($D=1; $D<=31; $D++) {
															echo '<td style="'.$cHDR_BACK_CLR.';">'.$aJAM[$D].'</td>';
														}
														echo '</tr>';
														echo '<td><span> </span><td><span></span></td>';
														$I++;
													}
												}
											}
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
<?php 
		eTFORM('*');
    END_WINDOW();
	APP_LOG_ADD( $cHEADER, 'check_list_report.php:'.$cAPP_CODE);
	SYS_DB_CLOSE($DB2);
?>
<script>
function FILT_CHECKLIST(p_CUST, p_LOK, p_JAB, p_DATA) {
	window.location.assign("?_c="+p_CUST + "&_l="+p_LOK + "&_j="+p_JAB);
}
</script>
