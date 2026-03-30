<?php
//	prs_rp_employee.php //
//	Laporan kegiatan pegawai, yg di input dari aplikasi absen
//  TODO : filter by user

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Inquery - Kegiatan Karyawan.pdf';
	$cHEADER = S_MSG('PI91','Laporan Kegiatan Karyawan');
  
	$chKODE_PEG 	= S_MSG('PA02','Kode Peg');
	$chNAMA_PEG 	= S_MSG('PA03','Nama Pegawai');
	$cCONTENT		= 'Laporan';
	$cKETERANGAN 	= S_MSG('PA98','Keterangan');
	$cNAMA_HARI 	= S_MSG('PA71','Hari');

	$chTANGGAL 		= S_MSG('RS02','Tanggal');
	$cCUSTOMER 		= S_MSG('RS04','Customer');
	$cLOKASI 		= S_MSG('PF16','Lokasi');
	$cJABATAN		= S_MSG('PF13','Jabatan');
	
	$cPAY_ROLL = S_PARA('PAY_ROLL','');

	$cPERIOD1=$cPERIOD2=date("d/m/Y");

	if (isset($_GET['_d1'])) $cPERIOD1=$_GET['_d1'];
	if (isset($_GET['_d2'])) $cPERIOD2=$_GET['_d2'];

	$cScope = "APP_CODE='$cAPP_CODE' and DELETOR=''";
	$qSCOPE = OpenTable('UserScope', "USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	if (SYS_ROWS($qSCOPE)>0) $cScope .= " and CUST_CODE in (select USER_CUST from prs_user_scope where USER_CODE='$cUSERCODE' and APP_CODE='$cAPP_CODE' and DELETOR='')";
	$qCUSTOMER=OpenTable('TbCustomer', $cScope, '', 'CUST_NAME');

	$cFILTER_CUST=(isset($_GET['_c']) && $_GET['_c'] ? $cFILTER_CUST=$_GET['_c'] : '');
	$cFILTER_JABATAN=(isset($_GET['_j']) && $_GET['_j']>'' ? $cFILTER_JABATAN=$_GET['_j'] : '');
	$cFILTER_LOKASI=(isset($_GET['_l']) && $_GET['_l']>'' ? $cFILTER_LOKASI=$_GET['_l'] : '');

	// $qCUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
	$aCUSTOMER=SYS_FETCH($qCUSTOMER);
	// if ($cFILTER_CUST=='')	$cFILTER_CUST=$aCUSTOMER['CUST_CODE'];

	$cPERSON_FILTER = "A.APP_CODE='$cAPP_CODE'". ($cFILTER_CUST ? " and F.CUST_CODE='$cFILTER_CUST'" : "") . " and A.DELETOR='' and P6.PRSON_SLRY<2";
	if ($cFILTER_LOKASI) $cPERSON_FILTER.=" and F.KODE_LOKS='$cFILTER_LOKASI'";
	if ($cFILTER_JABATAN) $cPERSON_FILTER.=" and JOB.JOB_CODE='$cFILTER_JABATAN'";
	$cPERSON_FILTER .= " and A.PRSON_CODE not in ( select PRSON_CODE from prs_resign ) and A.REP_CONTENT!='' and date(A.REP_TIME) between '".DMY_YMD($cPERIOD1)."' and '".DMY_YMD($cPERIOD2)."'";
	$qPERSON_REP	= OpenTable('Prs_Report', $cPERSON_FILTER, '', 'F.CUST_CODE, F.KODE_LOKS, F.JOB_CODE');

	$qLOKASI=OpenTable('Prs_Report', $cPERSON_FILTER, 'F.KODE_LOKS', 'LOCS.LOKS_NAME');
	$qJABATAN=OpenTable('Prs_Report', $cPERSON_FILTER, "JOB.JOB_CODE", 'JOB.JOB_NAME');

	APP_LOG_ADD( $cHEADER, 'prs_rp_employee.php:'.$cAPP_CODE);
	DEF_WINDOW($cHEADER, 'collapse', '');
		TFORM($cHEADER, '', [], $cHELP_FILE, '*');
			LABEL([1,1,1,6], '700', $chTANGGAL, '', 'right');
			INP_DATE([2,2,3,6], '900', '', $cPERIOD1, '', '', '', '', '', "FILT_R_ABSEN(this.value, '".$cPERIOD2."', '".$cFILTER_CUST."', '".$cFILTER_LOKASI."', '".$cFILTER_JABATAN."')");
			LABEL([1,1,1,6], '700', $cCUSTOMER, '', 'right');
			SELECT([3,3,3,6], 'PILIH_CUSTOMER', "FILT_R_ABSEN('$cPERIOD1', '$cPERIOD2', this.value, '$cFILTER_LOKASI', '$cFILTER_JABATAN')");
				echo "<option value=''  >All </option>";
					while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
						if($aCUSTOMER['CUST_CODE']==$cFILTER_CUST){
							echo "<option value='$aCUSTOMER[CUST_CODE]' selected='$cFILTER_CUST' > $aCUSTOMER[CUST_NAME]</option>";
						} else
							echo "<option value='$aCUSTOMER[CUST_CODE]'  >$aCUSTOMER[CUST_NAME]</option>";
					}
			echo '</select>';
			LABEL([1,1,1,6], '700', $cLOKASI, '', 'right');
			SELECT([3,3,3,6], 'PILIH_LOKASI', "FILT_R_ABSEN('$cPERIOD1', '$cPERIOD2', '$cFILTER_CUST', this.value, '$cFILTER_JABATAN')");
				echo "<option value=''  > All </option>";
				while($aLOKASI=SYS_FETCH($qLOKASI)){
					if($aLOKASI['KODE_LOKS']==$cFILTER_LOKASI){
						echo "<option value='$aLOKASI[KODE_LOKS]' selected='$cFILTER_LOKASI' > $aLOKASI[LOKS_NAME]</option>";
					} else
						echo "<option value='$aLOKASI[KODE_LOKS]'  >$aLOKASI[LOKS_NAME]</option>";
				}
			echo '</select>';
			CLEAR_FIX();
			LABEL([1,1,1,6], '700', S_MSG('RS14','s/d'), '', 'right');
			INP_DATE([2,2,3,6], '900', '', $cPERIOD2, '', '', '', '', '', "FILT_R_ABSEN('$cPERIOD1', this.value, '$cFILTER_CUST', '$cFILTER_LOKASI', '$cFILTER_JABATAN')");
			LABEL([1,1,1,6], '700', $cJABATAN, '', 'right');
			SELECT([3,3,3,6], 'PILIH_JABATAN', "FILT_R_ABSEN('$cPERIOD1', '$cPERIOD2', '$cFILTER_CUST', '$cFILTER_LOKASI', this.value)");
				echo "<option value=''  > All </option>";
				while($aJABATAN=SYS_FETCH($qJABATAN)){
					if($aJABATAN['JOB_CODE']==$cFILTER_JABATAN){
						echo "<option value='$aJABATAN[JOB_CODE]' selected='$cFILTER_JABATAN' >$aJABATAN[JOB_NAME]</option>";
					} else
						echo "<option value='$aJABATAN[JOB_CODE]'  >$aJABATAN[JOB_NAME]</option>";
				}
			echo '</select>';
			CLEAR_FIX();
			TDIV();		
				TABLE('example');
				THEAD([$chKODE_PEG, $chNAMA_PEG, $chTANGGAL, S_MSG('PI43','Laporan'), $cLOKASI, $cJABATAN, 'FOTO']);

					echo '<tbody>';
						while($aREC_PERSON=SYS_FETCH($qPERSON_REP)){

							$image = false;
							$filetype = '';
							$datetime = new DateTime($aREC_PERSON['REP_TIME']);
							$date = $datetime->format('_Y-m-d H_i');
							
							//relative path untuk mengecek file_exists di server
							$img_path1 = '../www/images/report/'
							.$cAPP_CODE.'_ACTIVITY_'
							.$aREC_PERSON['PRSON_CODE']
							.$date
							.'_1.';

							//direct image path untuk ditampilkan ke user
							$img_path2 = 'https://www.fahlevi.co/images/report/'
							.$cAPP_CODE.'_ACTIVITY_'
							.$aREC_PERSON['PRSON_CODE']
							.$date
							.'_';
							
							//relative path untuk mengecek looping check foto kedua dan ketiga dst
							$img_path3 = '../www/images/report/'
							.$cAPP_CODE.'_ACTIVITY_'
							.$aREC_PERSON['PRSON_CODE']
							.$date
							.'_';

							if(file_exists($img_path1.'jpg')){
								$filetype = 'jpg';
								$image = true;
							}elseif(file_exists($img_path1.'jpeg')){
								$filetype = 'jpeg';
								$image = true;
							}elseif(file_exists($img_path1.'png')){
								$filetype = 'png';
								$image = true;
							}else{
								$image = false;
							}

							$n_image = 1;
							//looping check foto kedua dan ketiga dst
							if($image){
								$i = 1;
								$xyz = $img_path3.$i.'.'.$filetype;
								while(file_exists($xyz)){
									$i++;
									$xyz = $img_path3.$i.'.'.$filetype;
								}
								$n_image = $i-1; //jumlah akhir foto dalam satu record laporan 
							}

							$cREP_ABSEN = DECODE($aREC_PERSON['REP_CONTENT']);
							echo '<tr>';
							echo '<td class=""><div class="star"><i class="fa fa-square-o icon-xs icon-default"></i></div></td>';
							echo '<th><span>'.$aREC_PERSON['PRSON_CODE'].'</span></th>';
							echo '<td><span>'.DECODE($aREC_PERSON['PRSON_NAME']).'</span></td>';
							echo '<td><span>'.$aREC_PERSON['REP_TIME'].'</span></td>';
							echo '<td><span>'.DECODE($cREP_ABSEN).'</span></td>';
							echo '<td><span>'.DECODE($aREC_PERSON['LOKS_NAME']).'</span></td>';
							echo '<td><span>'.DECODE($aREC_PERSON['JOB_NAME']).'</span></td>';
							echo '<td>';
								if($image){ //misalkan file foto benar ada di server
									$i = 0;
									while ($i < $n_image) {
										$i++;
										echo '<a href="'.$img_path2.$i.'.'.$filetype.'" target="_blank">
										<img src="'
											. $img_path2.$i.'.'.$filetype
											.'" alt="FOTO" width="50" height="50"></a>';
									}
								}
							echo '</td></tr>';
						}
					echo '</tbody>';
				eTABLE();
			eTDIV();
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
	END_WINDOW();
	SYS_DB_CLOSE($DB2);
?>
<script>
function FILT_R_ABSEN(p_TGL1, p_TGL2, p_CUST, p_LOK, p_JAB) {
	window.location.assign("?_d1="+p_TGL1 + "&_d2="+p_TGL2 + "&_c="+p_CUST + "&_l="+p_LOK + "&_j="+p_JAB);
}

</script>
