<?php
//	tpi_tabulasi_lelang.php
//	laporan summary/tabulasi hasil lelang bulanan per jenis ikan

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('TB76','Tabulasi Lelang per Jenis Ikan');

	APP_LOG_ADD($cHEADER, 'view');
	$cHELP_BOX	= S_MSG('TB55','Help Laporan Summary Hasil Timbang per Jenis Ikan');
	$cHELP_1	= S_MSG('TB56','Ini adalah laporan dari data timbangan berdasarkan bulan dan jenis ikan.');
	$cHELP_2	= S_MSG('TB57','Dengan laporan ini dapat dilihat perkembangan data timbangan dari bulan ke bulan.');
	$cHELP_3	= S_MSG('TB58','Data di laporan ini di dapat dari console timbangan, masuk ke server back-office, kemudian daemon bekerja untuk mentransfer data ke web server yang sudah ditentukan.');
	// $cHELP_4	= S_MSG('RF15','Laporan ini juga dapat di konversi ke excel, pdf atau format text, cara nya dengan memilih di dropdown output yang disediakan.');


	$cKODE_TABEL = S_MSG('F003','Kode');
	$cNAMA_TABEL = S_MSG('TF03','Nama Ikan');

	$sPERIOD1=date("Y-m-d");
	$cTGL1 = substr($sPERIOD1,8,2);
	$nTGL1 = intval($cTGL1);
	$nTAHUN= substr($sPERIOD1,0,4);
	$nTAHUN_AKHIR= substr($sPERIOD1,0,4);
	$c_FORMAT 	= S_PARA('PICT_STRUK_TIMBANG', '1');

	if (isset($_GET['TAHUN'])) {
		$nTAHUN=$_GET['TAHUN'];
	}

	$qREC_BID=OpenTable('DtBidd', "APP_CODE='$cAPP_CODE'",'', 'DATA_DATE, DATA_TIME limit 1');
	$aFIRST_BIDD = SYS_FETCH($qREC_BID);
	$cFIRST_BIDD = $aFIRST_BIDD['DATA_DATE'];
	$nTAHUN_AWAL	= substr($cFIRST_BIDD,0,4);
//	die ('First: '.$nTAHUN_AWAL);

	$qQUERY=OpenTable('DtScale', "APP_CODE='$cAPP_CODE'",'', 'DATA_DATE, DATA_TIME limit 1');
	$aLAST_BIDD = SYS_FETCH($qQUERY);
	$cLAST_BIDD = $aLAST_BIDD['DATA_DATE']. ' '.$aLAST_BIDD['DATA_TIME'];

	$qQUERY=OpenTable('TbFish', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
?>

<!DOCTYPE html>
<html class=" ">
	<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>

    <body class="sidebar-collapse">
		<?php	require_once("scr_topbar.php");	?>
        <div class="page-container row-fluid">
            <div class="page-sidebar collapseit">
                <div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
					<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
				</div>
				<div class="project-info"></div>
            </div>

            <section id="main-content" class="sidebar_shift">
                <section class="wrapper main-wrapper" style=' '>

					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <div class="page-title">

							<div class="pull-left">
								<h2 class="title"><?php echo $cHEADER?></h2>
							</div>

                            <div class="pull-right hidden-xs">
								<ol class="breadcrumb">
                                    <li>
										<a href="#help_tpi_tabulasi_timbang" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
                                    </li>
								</ol>
                            </div>
                        </div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12">
                        <section class="box ">
							<div class="content-body">    
								<div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
										<label class="col-sm-2 form-label-700" for="field-4"><?php echo S_MSG('MN20', 'Periode data')?></label>
										<select name='THN' class="form-label-900 col-sm-2" onchange="Select_Year(this.value)">
										<?php
											for ($i=$nTAHUN_AWAL; $i<=$nTAHUN_AKHIR; $i++){
												if ($i==$nTAHUN)
													echo "<option value=$i selected>$i</option>";
												else
													echo "<option value=$i>$i</option>";
											}
											echo "</select> ";
										?>
										<label class="col-sm-5 form-label-700"></label>
										<label class="col-sm-3 form-label-700" align="right"><?php echo 'Terakhir : '.$cLAST_BIDD?></label>

                                        <div class="<?php echo S_PARA('_BIG_REPORT_CLASS','table-responsive')?>" data-pattern="priority-columns">
                                            <table cellspacing="0" id="tech-companies-1" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;" data-priority="1"><?php echo $cNAMA_TABEL?></th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="3">Januari</th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="1">Februari</th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="3">Maret</th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="3">April</th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="6">Mei</th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="6">Juni</th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="6">Juli</th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="6">Agustus</th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="6">September</th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="6">Oktober</th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="6">November</th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="6">Desember</th>
                                                        <th style="<?php echo $cHDR_BACK_CLR?>;text-align:right;" data-priority="6">Jumlah</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
														$tNIL_JAN=0;	$tNIL_FEB=0;	$tNIL_MAR=0;	$tNIL_APR=0;	$tNIL_MEI=0; $tNIL_JUN=0;	$tNIL_JUL=0;	$tNIL_AGT=0;	$tNIL_SEP=0;	$tNIL_OKT=0;	$tNIL_NOV=0;	$tNIL_DES=0;
														$tBRT_JAN=0;	$tBRT_FEB=0;	$tBRT_MAR=0;	$tBRT_APR=0;	$tBRT_MEI=0; $tBRT_JUN=0;	$tBRT_JUL=0;	$tBRT_AGT=0;	$tBRT_SEP=0;	$tBRT_OKT=0;	$tBRT_NOV=0;	$tBRT_DES=0;
														while($aREC_TB_FISH=SYS_FETCH($qQUERY)) {
															echo '<tr>';
																echo "<td><span>".$aREC_TB_FISH['FISH_NAME']." </span></td>";
																
																$cFISH_CODE=$aREC_TB_FISH['FISH_CODE'];
																
																$BRT_JAN=0;	$NIL_JAN=0;
																$cQUERY="select * from summ_llg where BLN_LELANG=1 and THN_LELANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cAPP_CODE."' and DELETOR=''";
//																die ($cQUERY);
																$JAN=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($JAN)){
																	$BRT_JAN+=$j['FISH_WEIGT'];
																	$NIL_JAN+=$j['FISH_PRICE'];
																}
																$tBRT_JAN+=$BRT_JAN;
																$tNIL_JAN+=$NIL_JAN;
		
																echo '<td align="right">'.number_format($BRT_JAN, $c_FORMAT).'<br>'.number_format($NIL_JAN).'</td>';
																
																$BRT_FEB=0;	$NIL_FEB=0;
																$cQUERY="select * from summ_llg where BLN_LELANG=2 and THN_LELANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cAPP_CODE."' and DELETOR=''";
																$FEB=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($FEB)){
																	$BRT_FEB+=$j['FISH_WEIGT'];
																	$NIL_FEB+=$j['FISH_PRICE'];
																}
																$tBRT_FEB+=$BRT_FEB;
																echo '<td align="right">'.number_format($BRT_FEB, $c_FORMAT).'<br>'.number_format($NIL_FEB).'</td>';
																
																$BRT_MAR=0;	$NIL_MAR=0;
																$cQUERY="select * from summ_llg where BLN_LELANG=3 and THN_LELANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cAPP_CODE."' and DELETOR=''";
																$MAR=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($MAR)){
																	$BRT_MAR+=$j['FISH_WEIGT'];
																	$NIL_MAR+=$j['FISH_PRICE'];
																}
																$tBRT_MAR+=$BRT_MAR;
																echo '<td align="right">'.number_format($BRT_MAR, $c_FORMAT).'<br>'.number_format($NIL_MAR).'</td>';
																
																$BRT_APR=0;	$NIL_APR=0;
																$cQUERY="select * from summ_llg where BLN_LELANG=4 and THN_LELANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cAPP_CODE."' and DELETOR=''";
																$APR=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($APR)){
																	$BRT_APR+=$j['FISH_WEIGT'];
																	$NIL_APR+=$j['FISH_PRICE'];
																}
																$tBRT_APR+=$BRT_APR;
																echo '<td align="right">'.number_format($BRT_APR, $c_FORMAT).'<br>'.number_format($NIL_APR).'</td>';

																$BRT_MEI=0;	$NIL_MEI=0;
																$cQUERY="select * from summ_llg where BLN_LELANG=5 and THN_LELANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cAPP_CODE."' and DELETOR=''";
																$MEI=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($MEI)){
																	$BRT_MEI+=$j['FISH_WEIGT'];
																	$NIL_MEI+=$j['FISH_PRICE'];
																}
																$tBRT_MEI+=$BRT_MEI;
																echo '<td align="right">'.number_format($BRT_MEI, $c_FORMAT).'<br>'.number_format($NIL_MEI).'</td>';

																$BRT_JUN=0;	$NIL_JUN=0;
																$cQUERY="select * from summ_llg where BLN_LELANG=6 and THN_LELANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cAPP_CODE."' and DELETOR=''";
																$JUN=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($JUN)){
																	$BRT_JUN+=$j['FISH_WEIGT'];
																	$NIL_JUN+=$j['FISH_PRICE'];
																}
																$tBRT_JUN+=$BRT_JUN;
																echo '<td align="right">'.number_format($BRT_JUN, $c_FORMAT).'<br>'.number_format($NIL_JUN).'</td>';

																$BRT_JUL=0;	$NIL_JUL=0;
																$cQUERY="select * from summ_llg where BLN_LELANG=7 and THN_LELANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cAPP_CODE."' and DELETOR=''";
																$JUL=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($JUL)){
																	$BRT_JUL+=$j['FISH_WEIGT'];
																	$NIL_JUL+=$j['FISH_PRICE'];
																}
																$tBRT_JUL+=$NIL_JUL;
																echo '<td align="right">'.number_format($BRT_JUL, $c_FORMAT).'<br>'.number_format($NIL_JUL).'</td>';

																$BRT_AGT=0;	$NIL_AGT=0;
																$cQUERY="select * from summ_llg where BLN_LELANG=8 and THN_LELANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cAPP_CODE."' and DELETOR=''";
																$AGT=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($AGT)){
																	$BRT_AGT+=$j['FISH_WEIGT'];
																	$NIL_AGT+=$j['FISH_PRICE'];
																}
																$tBRT_AGT+=$BRT_AGT;
																echo '<td align="right">'.number_format($BRT_AGT, $c_FORMAT).'<br>'.number_format($NIL_AGT).'</td>';

																$BRT_SEP=0;	$NIL_SEP=0;
																$cQUERY="select * from summ_llg where BLN_LELANG=9 and THN_LELANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cAPP_CODE."' and DELETOR=''";
																$SEP=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($SEP)){
																	$BRT_SEP+=$j['FISH_WEIGT'];
																	$NIL_SEP+=$j['FISH_PRICE'];
																}
																$tBRT_SEP+=$BRT_SEP;
																echo '<td align="right">'.number_format($BRT_SEP, $c_FORMAT).'<br>'.number_format($NIL_SEP).'</td>';

																$BRT_OKT=0;	$NIL_OKT=0;
																$cQUERY="select * from summ_llg where BLN_LELANG=10 and THN_LELANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cAPP_CODE."' and DELETOR=''";
																$OKT=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($OKT)){
																	$BRT_OKT+=$j['FISH_WEIGT'];
																	$NIL_OKT+=$j['FISH_PRICE'];
																}
																$tBRT_OKT+=$BRT_OKT;
																echo '<td align="right">'.number_format($BRT_OKT, $c_FORMAT).'<br>'.number_format($NIL_OKT).'</td>';

																$BRT_NOV=0;	$NIL_NOV=0;
																$cQUERY="select * from summ_llg where BLN_LELANG=11 and THN_LELANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cAPP_CODE."' and DELETOR=''";
																$NOV=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($NOV)){
																	$BRT_NOV+=$j['FISH_WEIGT'];
																	$NIL_NOV+=$j['FISH_PRICE'];
																}
																$tBRT_NOV+=$BRT_NOV;
																echo '<td align="right">'.number_format($BRT_NOV, $c_FORMAT).'<br>'.number_format($NIL_NOV).'</td>';

																$BRT_DES=0;	$NIL_DES=0;
																$cQUERY="select * from summ_llg where BLN_LELANG=12 and THN_LELANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cAPP_CODE."' and DELETOR=''";
																$DES=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($DES)){
																	$BRT_DES+=$j['FISH_WEIGT'];
																	$NIL_DES+=$j['FISH_PRICE'];
																}
																$tBRT_DES+=$BRT_DES;
																echo '<td align="right">'.number_format($BRT_DES, $c_FORMAT).'<br>'.number_format($NIL_DES).'</td>';

																$BRT_IKAN=$BRT_JAN+$BRT_FEB+$BRT_MAR+$BRT_MEI+$BRT_JUN+$BRT_JUL+$BRT_AGT+$BRT_SEP+$BRT_OKT+$BRT_NOV+$BRT_DES;
																$NIL_IKAN=$NIL_JAN+$NIL_FEB+$NIL_MAR+$NIL_MEI+$NIL_JUN+$NIL_JUL+$NIL_AGT+$NIL_SEP+$NIL_OKT+$NIL_NOV+$NIL_DES;
																echo '<td align="right">'.number_format($BRT_IKAN, $c_FORMAT).'<br>'.number_format($NIL_IKAN).'</td>';

															echo '</tr>';
														}
													?>

                                                </tbody>
												<?php
													$tBRT_ALL=$tBRT_JAN+$tBRT_FEB+$tBRT_MAR+$tBRT_MEI+$tBRT_JUN+$tBRT_JUL+$tBRT_AGT+$tBRT_SEP+$tBRT_OKT+$tBRT_NOV+$tBRT_DES;
													$tNIL_ALL=$tNIL_JAN+$tNIL_FEB+$tNIL_MAR+$tNIL_MEI+$tNIL_JUN+$tNIL_JUL+$tNIL_AGT+$tNIL_SEP+$tNIL_OKT+$tNIL_NOV+$tNIL_DES;
													echo '<tr>
														<td style="background-color:LightGray;" align="right">T O T A L</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_JAN, $c_FORMAT).'<br>'.number_format($tNIL_JAN).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_FEB, $c_FORMAT).'<br>'.number_format($tNIL_FEB).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_MAR, $c_FORMAT).'<br>'.number_format($tNIL_MAR).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_APR, $c_FORMAT).'<br>'.number_format($tNIL_APR).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_MEI, $c_FORMAT).'<br>'.number_format($tNIL_MEI).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_JUN, $c_FORMAT).'<br>'.number_format($tNIL_JUN).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_JUL, $c_FORMAT).'<br>'.number_format($tNIL_JUL).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_AGT, $c_FORMAT).'<br>'.number_format($tNIL_AGT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_SEP, $c_FORMAT).'<br>'.number_format($tNIL_SEP).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_OKT, $c_FORMAT).'<br>'.number_format($tNIL_OKT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_NOV, $c_FORMAT).'<br>'.number_format($tNIL_NOV).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_DES, $c_FORMAT).'<br>'.number_format($tNIL_DES).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_ALL, $c_FORMAT).'<br>'.number_format($tNIL_ALL).'</td>
													</tr>';
												?>
											</table>
										</div>  
                                    </div>
                                </div>
                            </div>
                        </section>
					<div>
                </section>
            </section>
			<?php	include "scr_chat.php";	?>
		</div>
		<?php	require_once("js_framework.php");	?>
        <script src="assets/plugins/responsive-tables/js/rwd-table.js" type="text/javascript"></script>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 

        <div class="modal" id="help_tpi_tabulasi_timbang" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
                    </div>
                    <div class="modal-body">

						<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_3?></p>

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script>

function Select_Year(year) {
	window.location.assign("tpi_tabulasi_timbang.php?TAHUN="+year);
}

</script>



