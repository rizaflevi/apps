<?php
//	tpi_tabulasi_timbang.php
//	laporan summary/tabulasi hasil timbang bulanan per jenis ikan

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('TB49','Summary Timbang per Jenis Ikan');

	APP_LOG_ADD($cHEADER, 'view');
	$cHELP_BOX	= S_MSG('TB55','Help Laporan Summary Hasil Timbang per Jenis Ikan');
	$cHELP_1	= S_MSG('TB56','Ini adalah laporan dari data timbangan berdasarkan bulan dan jenis ikan.');
	$cHELP_2	= S_MSG('TB57','Dengan laporan ini dapat dilihat perkembangan data timbangan dari bulan ke bulan.');
	$cHELP_3	= S_MSG('TB58','Data di laporan ini di dapat dari console timbangan, masuk ke server back-office, kemudian daemon bekerja untuk mentransfer data ke web server yang sudah ditentukan.');
	// $cHELP_4	= S_MSG('RF15','Laporan ini juga dapat di konversi ke excel, pdf atau format text, cara nya dengan memilih di dropdown output yang disediakan.');


	$cKODE_TABEL = S_MSG('F003','Kode');
	$cNAMA_TABEL = S_MSG('TF03','Nama Ikan');

	$sPERIOD1=date("Y-m-d");
	$cTGL1 		= substr($sPERIOD1,8,2);
	$nTGL1 		= intval($cTGL1);
	$nTAHUN		= substr($sPERIOD1,0,4);
	$nTAHUN_AKHIR= substr($sPERIOD1,0,4);
	$c_FORMAT 	= S_PARA('PICT_STRUK_TIMBANG', '1');

	if (isset($_GET['TAHUN'])) {
		$nTAHUN=$_GET['TAHUN'];
	}

/*	die ($nTGL1);
	$cQUERY="SELECT summ_tmb.*, tb_fishr.*, tb_fish.* FROM summ_tmb 
		LEFT JOIN tb_fishr on tb_fishr.FISHR_CODE=summ_tmb.FISHR_CODE 
		LEFT JOIN tb_fish on tb_fish.FISH_CODE=summ_tmb.FISH_CODE  
		WHERE summ_tmb.APP_CODE='$cFILTER_CODE' and summ_tmb.DELETOR='' AND summ_tmb.TH_TIMBANG=".substr($sPERIOD1,0,4). " AND BL_TIMBANG=".substr($sPERIOD1,5,2)." ORDER BY summ_tmb.FISH_CODE, summ_tmb.FISHR_CODE";
*/
	$qQUERY=OpenTable('DtScale', "APP_CODE='$cFILTER_CODE'", '', "DATA_DATE, DATA_TIME limit 1");
	$aFIRST_SCALE = SYS_FETCH($qQUERY);
	$cFIRST_SCALE = $aFIRST_SCALE['DATA_DATE'];
	$nTAHUN_AWAL	= substr($cFIRST_SCALE,0,4);
//	die ('First: '.$nTAHUN_AWAL);

	$qQUERY=OpenTable('DtScale', "APP_CODE='$cFILTER_CODE'", '', "DATA_DATE desc, DATA_TIME desc limit 1");
	$aLAST_SCALE = SYS_FETCH($qQUERY);
	$cLAST_SCALE = $aLAST_SCALE['DATA_DATE']. ' '.$aLAST_SCALE['DATA_TIME'];

	$qQUERY=OpenTable('TbFish', "APP_CODE='$cFILTER_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', "DATA_DATE desc, DATA_TIME desc limit 1");

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
?>

<!DOCTYPE html>
<html class=" ">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>Rainbow Sys : <?php echo $cHEADER?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon" />    <!-- Favicon -->
        <link rel="apple-touch-icon-precomposed" href="assets/images/apple-touch-icon-57-precomposed.png">	<!-- For iPhone -->
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/apple-touch-icon-114-precomposed.png">    <!-- For iPhone 4 Retina display -->
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/apple-touch-icon-72-precomposed.png">    <!-- For iPad -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/apple-touch-icon-144-precomposed.png">    <!-- For iPad Retina display -->

        <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>

        <link href="assets/plugins/responsive-tables/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen"/>        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
    </head>

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
<!--                     	<header class="panel_header">
                                <h2 class="title pull-left">Responsive Table - Focus & Select Columns</h2>
                                <div class="actions panel_actions pull-right">
                                    <i class="box_toggle fa fa-chevron-down"></i>
                                    <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                                    <i class="box_close fa fa-times"></i>
                                </div>
                            </header>
-->
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
										<label class="col-sm-3 form-label-700" align="right"><?php echo 'Terakhir : '.$cLAST_SCALE?></label>

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
																
																$BRT_JAN=0;
																$cQUERY="select * from summ_tmb where BL_TIMBANG=1 and TH_TIMBANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cFILTER_CODE."' and DELETOR=''";
//																die ($cQUERY);
																$JAN=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($JAN)){
																	$BRT_JAN+=$j['SUMM_WEIGT'];
																}
																$tBRT_JAN+=$BRT_JAN;
		
																echo '<td align="right">'.number_format($BRT_JAN, $c_FORMAT).'</td>';
																
																$BRT_FEB=0;
																$cQUERY="select * from summ_tmb where BL_TIMBANG=2 and TH_TIMBANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cFILTER_CODE."' and DELETOR=''";
																$FEB=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($FEB)){
																	$BRT_FEB+=$j['SUMM_WEIGT'];
																}
																$tBRT_FEB+=$BRT_FEB;
																echo '<td align="right">'.number_format($BRT_FEB, $c_FORMAT).'</td>';
																
																$BRT_MAR=0;
																$cQUERY="select * from summ_tmb where BL_TIMBANG=3 and TH_TIMBANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cFILTER_CODE."' and DELETOR=''";
																$MAR=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($MAR)){
																	$BRT_MAR+=$j['SUMM_WEIGT'];
																}
																$tBRT_MAR+=$BRT_MAR;
																echo '<td align="right">'.number_format($BRT_MAR, $c_FORMAT).'</td>';
																
																$BRT_APR=0;
																$cQUERY="select * from summ_tmb where BL_TIMBANG=4 and TH_TIMBANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cFILTER_CODE."' and DELETOR=''";
																$APR=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($APR)){
																	$BRT_APR+=$j['SUMM_WEIGT'];
																}
																$tBRT_APR+=$BRT_APR;
																echo '<td align="right">'.number_format($BRT_APR, $c_FORMAT).'</td>';

																$BRT_MEI=0;
																$cQUERY="select * from summ_tmb where BL_TIMBANG=5 and TH_TIMBANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cFILTER_CODE."' and DELETOR=''";
																$MEI=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($MEI)){
																	$BRT_MEI+=$j['SUMM_WEIGT'];
																}
																$tBRT_MEI+=$BRT_MEI;
																echo '<td align="right">'.number_format($BRT_MEI, $c_FORMAT).'</td>';

																$BRT_JUN=0;
																$cQUERY="select * from summ_tmb where BL_TIMBANG=6 and TH_TIMBANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cFILTER_CODE."' and DELETOR=''";
																$JUN=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($JUN)){
																	$BRT_JUN+=$j['SUMM_WEIGT'];
																}
																$tBRT_JUN+=$BRT_JUN;
																echo '<td align="right">'.number_format($BRT_JUN, $c_FORMAT).'</td>';

																$BRT_JUL=0;
																$cQUERY="select * from summ_tmb where BL_TIMBANG=7 and TH_TIMBANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cFILTER_CODE."' and DELETOR=''";
																$JUL=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($JUL)){
																	$BRT_JUL+=$j['SUMM_WEIGT'];
																}
																$tBRT_JUL+=$BRT_JUL;
																echo '<td align="right">'.number_format($BRT_JUL, $c_FORMAT).'</td>';

																$BRT_AGT=0;
																$cQUERY="select * from summ_tmb where BL_TIMBANG=8 and TH_TIMBANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cFILTER_CODE."' and DELETOR=''";
																$AGT=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($AGT)){
																	$BRT_AGT+=$j['SUMM_WEIGT'];
																}
																$tBRT_AGT+=$BRT_AGT;
																echo '<td align="right">'.number_format($BRT_AGT, $c_FORMAT).'</td>';

																$BRT_SEP=0;
																$cQUERY="select * from summ_tmb where BL_TIMBANG=9 and TH_TIMBANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cFILTER_CODE."' and DELETOR=''";
																$SEP=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($SEP)){
																	$BRT_SEP+=$j['SUMM_WEIGT'];
																}
																$tBRT_SEP+=$BRT_SEP;
																echo '<td align="right">'.number_format($BRT_SEP, $c_FORMAT).'</td>';

																$BRT_OKT=0;
																$cQUERY="select * from summ_tmb where BL_TIMBANG=10 and TH_TIMBANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cFILTER_CODE."' and DELETOR=''";
																$OKT=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($OKT)){
																	$BRT_OKT+=$j['SUMM_WEIGT'];
																}
																$tBRT_OKT+=$BRT_OKT;
																echo '<td align="right">'.number_format($BRT_OKT, $c_FORMAT).'</td>';

																$BRT_NOV=0;
																$cQUERY="select * from summ_tmb where BL_TIMBANG=11 and TH_TIMBANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cFILTER_CODE."' and DELETOR=''";
																$NOV=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($NOV)){
																	$BRT_NOV+=$j['SUMM_WEIGT'];
																}
																$tBRT_NOV+=$BRT_NOV;
																echo '<td align="right">'.number_format($BRT_NOV, $c_FORMAT).'</td>';

																$BRT_DES=0;
																$cQUERY="select * from summ_tmb where BL_TIMBANG=12 and TH_TIMBANG=".$nTAHUN." and FISH_CODE='".$cFISH_CODE."' and APP_CODE='".$cFILTER_CODE."' and DELETOR=''";
																$DES=SYS_QUERY($cQUERY);
																while($j=SYS_FETCH($DES)){
																	$BRT_DES+=$j['SUMM_WEIGT'];
																}
																$tBRT_DES+=$BRT_DES;
																echo '<td align="right">'.number_format($BRT_DES, $c_FORMAT).'</td>';

																$BRT_IKAN=$BRT_JAN+$BRT_FEB+$BRT_MAR+$BRT_MEI+$BRT_JUN+$BRT_JUL+$BRT_AGT+$BRT_SEP+$BRT_OKT+$BRT_NOV+$BRT_DES;
																echo '<td align="right">'.number_format($BRT_IKAN, $c_FORMAT).'</td>';

															echo '</tr>';
														}
													?>

                                                </tbody>
												<?php
													$tBRT_ALL=$tBRT_JAN+$tBRT_FEB+$tBRT_MAR+$tBRT_MEI+$tBRT_JUN+$tBRT_JUL+$tBRT_AGT+$tBRT_SEP+$tBRT_OKT+$tBRT_NOV+$tBRT_DES;
													echo '<tr>
														<td style="background-color:LightGray;" align="right">T O T A L</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_JAN, $c_FORMAT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_FEB, $c_FORMAT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_MAR, $c_FORMAT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_APR, $c_FORMAT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_MEI, $c_FORMAT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_JUN, $c_FORMAT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_JUL, $c_FORMAT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_AGT, $c_FORMAT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_SEP, $c_FORMAT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_OKT, $c_FORMAT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_NOV, $c_FORMAT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_DES, $c_FORMAT).'</td>
														<td style="background-color:LightGray;" align="right">'.number_format($tBRT_ALL, $c_FORMAT).'</td>
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



