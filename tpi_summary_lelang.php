<?php
//	tpi_summary_lelang.php
//	laporan summary/tabulasi lelang

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('TB71','Summary Hasil Lelang Ikan');

	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
	$cHELP_BOX	= S_MSG('TB7A','Help Laporan Summary Hasil Lelang Ikan Nelayan');
	$cHELP_1	= S_MSG('TB7B','Ini adalah laporan dari data lelang berdasarkan Nelayan dan jenis ikan.');
	$cHELP_2	= S_MSG('TB7C','Dengan laporan ini Nelayan dan bakul dapat memonitor hasil lelang nya berdasarkan Nelayan / pemilik ikan, peserta lelang / bakul, dan jenis ikan');
	$cHELP_3	= S_MSG('TB4D','Laporan ini dapat dimonitor dari mana saja sepanjang ada koneksi internet, sehingga akan memudahkan Nelayan mengikuti perkembangan lelang.');
	// $cHELP_4	= S_MSG('RF15','Laporan ini juga dapat di konversi ke excel, pdf atau format text, cara nya dengan memilih di dropdown output yang disediakan.');


	$cKODE_TABEL = S_MSG('F003','Kode');
	$cNAMA_TABEL = S_MSG('TF53','Nama Nelayan');
	$cNAMA_IKAN  = S_MSG('TF03','Nama Ikan');
	$cNAMA_BAKUL = S_MSG('TF88','Peserta Lelang');
	$cJML_BERAT  = S_MSG('TF64','Berat');
	$cJML_HARGA  = S_MSG('SPL4','Harga');

	$c_FORMAT = S_PARA('PICT_STRUK_LELANG', '1');
	$c_TTL_STYLE = S_PARA('_REPORT_TOTAL_STYLE', 'font-size: 24px;color: Brown;background-color: LightGray ;');
	$c_HDR_STYLE = S_PARA('_REPORT_HEADER_STYLE', 'background-color:LightGray;');

	$dPERIOD1=date("Y-m-d");
	$dPERIOD2=date("Y-m-d");

	if (isset($_GET['_t1'])) {
		$dPERIOD1=$_GET['_t1'];
	}

	if (isset($_GET['_t2'])) {
		$dPERIOD2=$_GET['_t2'];
	}

	$cNELAYAN='';
	if (isset($_GET['_n'])) {
		$cNELAYAN=$_GET['_n'];
	}

	$cIKAN='';
	if (isset($_GET['_i'])) {
		$cIKAN=$_GET['_i'];
	}

	$cBIDDER='';
	if (isset($_GET['_b'])) {
		$cBIDDER=$_GET['_b'];
	}

	$cDT_BIDD="select * from dt_bidd WHERE APP_CODE='$cFILTER_CODE' order by DATA_DATE desc, DATA_TIME desc limit 1";
	$qDT_BIDD=SYS_QUERY($cDT_BIDD);
	$aLAST_SCALE = SYS_FETCH($qDT_BIDD);
	$cLAST_SCALE = $aLAST_SCALE['DATA_DATE']. ' '.$aLAST_SCALE['DATA_TIME'];

	$cTB_FISHR= "select dt_bidd.FISHR_CODE, dt_bidd.FISH_CODE, dt_bidd.FISH_PRICE, tb_fishr.FISHR_NAME from tb_fishr
		LEFT JOIN dt_bidd on dt_bidd.FISHR_CODE=tb_fishr.FISHR_CODE
		WHERE dt_bidd.DATA_DATE>='$dPERIOD1' and dt_bidd.DATA_DATE<='$dPERIOD2' and ";
	if ($cNELAYAN!='') {
		$cTB_FISHR.=" dt_bidd.FISHR_CODE='$cNELAYAN' and ";
	}
	if ($cIKAN!='') {
		$cTB_FISHR.=" dt_bidd.FISH_CODE='$cIKAN' and ";
	}	
	if ($cBIDDER!='') {
		$cTB_FISHR.=" dt_bidd.BIDDR_CODE='$cBIDDER' and ";
	}	
	$cTB_FISHR.=" tb_fishr.APP_CODE='$cFILTER_CODE' and tb_fishr.DELETOR='' group by tb_fishr.FISHR_CODE order by tb_fishr.FISHR_CODE";
	$qTB_FISHR=SYS_QUERY($cTB_FISHR);
//	var_dump($cTB_FISHR); exit;
	
	$cTB_FISH= "select * from tb_fish
		LEFT JOIN dt_bidd on dt_bidd.FISH_CODE=tb_fish.FISH_CODE
		WHERE dt_bidd.DATA_DATE>='$dPERIOD1' and dt_bidd.DATA_DATE<='$dPERIOD2' and ";
	if ($cNELAYAN!='') {
		$cTB_FISH.=" dt_bidd.FISHR_CODE='$cNELAYAN' and ";
	}
	if ($cIKAN!='') {
		$cTB_FISH.=" dt_bidd.FISH_CODE='$cIKAN' and ";
	}	
	if ($cBIDDER!='') {
		$cTB_FISH.=" dt_bidd.BIDDR_CODE='$cBIDDER' and ";
	}	
	$cTB_FISH.=" tb_fish.APP_CODE='$cFILTER_CODE' and tb_fish.DELETOR='' group by tb_fish.FISH_CODE order by tb_fish.FISH_CODE";
	$qTB_FISH=SYS_QUERY($cTB_FISH);
	
	$cTB_BIDD= "select * from tb_beedr
		LEFT JOIN dt_bidd on dt_bidd.BIDDR_CODE=tb_beedr.BEEDR_CODE
		WHERE dt_bidd.DATA_DATE>='$dPERIOD1' and dt_bidd.DATA_DATE<='$dPERIOD2' and ";
	if ($cNELAYAN!='') {
		$cTB_BIDD.=" dt_bidd.FISHR_CODE='$cNELAYAN' and ";
	}
	if ($cIKAN!='') {
		$cTB_BIDD.=" dt_bidd.FISH_CODE='$cIKAN' and ";
	}
	if ($cBIDDER!='') {
		$cTB_BIDD.=" dt_bidd.BIDDR_CODE='$cBIDDER' and ";
	}	
	$cTB_BIDD.=" tb_beedr.APP_CODE='$cFILTER_CODE' and tb_beedr.DELETOR='' group by tb_beedr.BEEDR_CODE order by tb_beedr.BEEDR_CODE";
	$qTB_BIDD=SYS_QUERY($cTB_BIDD);

	$cDT_BIDD="select A.BIDDR_CODE, A.FISHR_CODE, A.FISH_CODE, A.DATA_DATE, A.DATA_TIME, A.FISH_WEIGT, A.FISH_PRICE, B.FISHR_NAME, C.FISH_NAME, D.BEEDR_NAME from dt_bidd A
		LEFT JOIN (select FISHR_CODE, FISHR_NAME from tb_fishr where APP_CODE='$cFILTER_CODE'  and DELETOR='') B on A.FISHR_CODE=B.FISHR_CODE
		LEFT JOIN (select FISH_CODE, FISH_NAME from tb_fish where APP_CODE='$cFILTER_CODE'  and DELETOR='') C on A.FISH_CODE=C.FISH_CODE
		LEFT JOIN (select BEEDR_CODE, BEEDR_NAME from tb_beedr where APP_CODE='$cFILTER_CODE'  and DELETOR='') D on A.BIDDR_CODE=D.BEEDR_CODE
		WHERE A.DATA_DATE>='$dPERIOD1' and A.DATA_DATE<='$dPERIOD2' and ";
	if ($cNELAYAN!='') {
		$cDT_BIDD.=" A.FISHR_CODE='$cNELAYAN' and ";
	}
	if ($cIKAN!='') {
		$cDT_BIDD.=" A.FISH_CODE='$cIKAN' and ";
	}
	if ($cBIDDER!='') {
		$cDT_BIDD.=" A.BIDDR_CODE='$cBIDDER' and ";
	}	
	$cDT_BIDD.=" A.APP_CODE='$cFILTER_CODE' and A.DELETOR='' order by A.DATA_DATE, A.DATA_TIME";
//	var_dump($cDT_BIDD); exit;
	$c_FORMAT 	= S_PARA('PICT_STRUK_TIMBANG', '1');
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

        <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon" />
        <link rel="apple-touch-icon-precomposed" href="assets/images/apple-touch-icon-57-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/apple-touch-icon-144-precomposed.png">

        <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>

        <link href="assets/plugins/responsive-tables/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen"/>
 
		<link href="assets/plugins/tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" media="screen"/>
		<link href="assets/plugins/typeahead/css/typeahead.css" rel="stylesheet" type="text/css" media="screen"/>

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
										<a href="#tpi_summary_lelang" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
										<label class="col-sm-1 form-label-700" for="field-4"><?php echo S_MSG('RS02','Tanggal')?></label>
										<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $dPERIOD1?>" onchange="select_LELANG(this.value, '<?php echo $dPERIOD2?>', '<?php echo $cNELAYAN?>', '<?php echo $cIKAN?>', '<?php echo $cBIDDER?>')">

										<label class="col-sm-1 form-label-700"></label>
										<label class="col-sm-2 form-label-700" for="field-4"><?php echo S_MSG('TB53','Nelayan')?></label>
										<select name='NLYN' class="col-sm-3 form-label-900" onchange="select_LELANG('<?php echo $dPERIOD1?>', '<?php echo $dPERIOD2?>', this.value, '<?php echo $cIKAN?>', '<?php echo $cBIDDER?>')">
										<?php
											echo "<option value=''  >All</option>";
											while($aREC_NELAYAN=SYS_FETCH($qTB_FISHR)){
												if($cNELAYAN == $aREC_NELAYAN['FISHR_CODE']){
													echo "<option class='form-label-900' value='$aREC_NELAYAN[FISHR_CODE]' selected='$aREC_NELAYAN[FISHR_CODE]'>$aREC_NELAYAN[FISHR_NAME]</option>";
												} else { 
													echo "<option class='form-label-900' value='$aREC_NELAYAN[FISHR_CODE]'  >$aREC_NELAYAN[FISHR_NAME]</option>";
												}
											}
										?>
										</select>
										<label class="col-sm-1 form-label-700"></label>
										<label class="col-sm-3 form-label-700" align="right"><?php echo 'Terakhir : '.$cLAST_SCALE?></label><br>

										<div class="clearfix"></div>
										<label class="col-sm-1 form-label-700" for="field-4"><?php echo S_MSG('RS14','s/d')?></label>
										<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $dPERIOD2?>" onchange="select_LELANG('<?php echo $dPERIOD1?>', this.value, '<?php echo $cNELAYAN?>', '<?php echo $cIKAN?>', '<?php echo $cBIDDER?>')">

										<label class="col-sm-1 form-label-700"></label>
										<label class="col-sm-2 form-label-700" for="field-4"><?php echo S_MSG('TB33','Ikan')?></label>
										<select name='IKAN' class="col-sm-3 form-label-900" onchange="select_LELANG('<?php echo $dPERIOD1?>', '<?php echo $dPERIOD2?>', '<?php echo $cNELAYAN?>', this.value, '<?php echo $cBIDDER?>')">
										<?php
											echo "<option value=''  >All</option>";
											while($aREC_IKAN=SYS_FETCH($qTB_FISH)){
												if($cIKAN == $aREC_IKAN['FISH_CODE']){
													echo "<option class='form-label-900' value='$aREC_IKAN[FISH_CODE]' selected='$aREC_IKAN[FISH_CODE]'>$aREC_IKAN[FISH_NAME]</option>";
												} else { 
													echo "<option class='form-label-900' value='$aREC_IKAN[FISH_CODE]'  >$aREC_IKAN[FISH_NAME]</option>";
												}
											}
										?>
										</select>

										<label class="col-sm-1 form-label-700" for="BIDDR_CODE"><?php echo S_MSG('TB34','Bakul')?></label>
										<select name='IKAN' class="col-sm-2 form-label-900" onchange="select_LELANG('<?php echo $dPERIOD1?>', '<?php echo $dPERIOD2?>', '<?php echo $cNELAYAN?>', '<?php echo $cIKAN?>', this.value)">
										<?php
											echo "<option value=''  >All</option>";
											while($aREC_BIDD=SYS_FETCH($qTB_BIDD)){
												if($cBIDDER == $aREC_BIDD['BIDDR_CODE']){
													echo "<option class='form-label-900' value='$aREC_BIDD[BIDDR_CODE]' selected='$aREC_BIDD[BIDDR_CODE]'>$aREC_BIDD[BEEDR_NAME]</option>";
												} else { 
													echo "<option class='form-label-900' value='$aREC_BIDD[BIDDR_CODE]'  >$aREC_BIDD[BEEDR_NAME]</option>";
												}
											}
										?>
										</select>

										<div class="clearfix"></div>

                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table cellspacing="0" id="tech-companies-1" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="<?php echo $c_HDR_STYLE?>" data-priority="1"><?php echo $cKODE_TABEL?></th>
                                                        <th style="background-color:LightGray;" data-priority="1"><?php echo $cNAMA_TABEL?></th>
                                                        <th style="background-color:LightGray;" data-priority="1"><?php echo $cNAMA_IKAN?></th>
                                                        <th style="background-color:LightGray;" data-priority="1"><?php echo $cNAMA_BAKUL?></th>
                                                        <th style="background-color:LightGray;" data-priority="1"><?php echo $cJML_BERAT?></th>
                                                        <th style="background-color:LightGray;text-align:right;" data-priority="3"><?php echo S_MSG('TB72','Nilai Lelang')?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
														$tBRT_ALL=0;
														$tNIL_ALL=0;
														$qDT_BIDD=SYS_QUERY($cDT_BIDD);
														while($aREC_FISHR=SYS_FETCH($qDT_BIDD)){
															$cFISHR_NAME=$aREC_FISHR['FISHR_NAME'];
															$cKODE_IKAN = $aREC_FISHR['FISH_CODE'];
															$cNAMA_IKAN = $aREC_FISHR['FISH_NAME'];
															$nNILAI_LELANG = $aREC_FISHR['FISH_WEIGT'] * $aREC_FISHR['FISH_PRICE'];
															echo '<tr>';
																echo "<td><span>".$aREC_FISHR['FISHR_CODE']." </span></td>";
																echo "<td><span>".$cFISHR_NAME." </span></td>";
																echo "<td><span>".$cNAMA_IKAN." </span></td>";
																echo "<td><span>".$aREC_FISHR['BEEDR_NAME']." </span></td>";
																echo '<td align="right"><span>'.number_format($aREC_FISHR['FISH_WEIGT'], $c_FORMAT).' </span></td>';
																echo '<td align="right"><span>'.number_format($nNILAI_LELANG).' </span></td>';
																
																$tBRT_ALL+=$aREC_FISHR['FISH_WEIGT'];
																$tNIL_ALL+=$nNILAI_LELANG;
																
															echo '</tr>';
														}
													?>

                                                </tbody>
												<?php
													echo '<tr>
														<td style="'.$c_TTL_STYLE.'" align="right">T O T A L</td>
														<td style="'.$c_TTL_STYLE.'" align="right"></td>
														<td style="'.$c_TTL_STYLE.'" align="right"></td>
														<td style="'.$c_TTL_STYLE.'" align="right"></td>
														<td style="'.$c_TTL_STYLE.'" align="right">'.number_format($tBRT_ALL, $c_FORMAT).'</td>
														<td style="'.$c_TTL_STYLE.'" align="right">'.number_format($tNIL_ALL).'</td>
													</tr>';
												?>
											</table>
										</div>  
                                    </div>

									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<section class="box ">
											<header class="panel_header">
												<h2 class="title pull-left"><?php echo S_MSG('TB73','Summary Nelayan')?></h2>
												<div class="actions panel_actions pull-right">
													<i class="box_toggle fa fa-chevron-down"></i>
												</div>
											</header>
											<div class="content-body">    
												<div class="row">
													<label class="col-sm-8 form-label-700" for="field-2"><?php echo S_MSG('TB74','Nilai Lelang')?></label>
													<input type="text" name="NILAI_LELANG" class="col-sm-3 form-label-900 text-right" data-mask="fdecimal" value=<?php echo number_format($tNIL_ALL)?>><br>
													<?php
														$qQUERY=SYS_QUERY("select * from tb_bidd_pot where APP_CODE='$cFILTER_CODE' and DELETOR=''");
														while($aREC_tb_bidd_pot=SYS_FETCH($qQUERY)) {
															$nNILAI = $aREC_tb_bidd_pot['PERSEN'] / 100 * $tNIL_ALL;
															echo '<input type="text" name="NAMA_POT" class="col-sm-6 form-label-900" value='.decode_string($aREC_tb_bidd_pot['NAMA_POT']).'>';
															echo '<input type="text" name="PERSENAN" class="col-sm-2 form-label-900 text-right" data-mask="fdecimal" value='.number_format($aREC_tb_bidd_pot['PERSEN'], 2).'%>';
															echo '<input type="text" name="NAMA_POT" class="col-sm-3 form-label-900 text-right" data-mask="fdecimal" value='.number_format($nNILAI).'><br>';
														}
													?>

												</div>
											</div>
										</section>
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

		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
        <script src="assets/plugins/responsive-tables/js/rwd-table.js" type="text/javascript"></script>

        <script src="assets/js/scripts.js" type="text/javascript"></script> 

        <div class="modal" id="tpi_summary_lelang" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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

<?php
	SYS_DB_CLOSE($DB2);
?>
function Select_TGL_1(TGL_1, TGL_2, KODE_NELAYAN) {
	window.location.assign("tpi_summary_lelang.php?_t1="+TGL_1+"&_t2="+TGL_2+"&_n="+KODE_NELAYAN);
}

function Select_TGL_2(TGL_1, TGL_2, KODE_NELAYAN) {
	window.location.assign("tpi_summary_lelang.php?_t1="+TGL_1+"&_t2="+TGL_2+"&_n="+KODE_NELAYAN);
}

function select_LELANG(TGL_1, TGL_2, KODE_NELAYAN, KODE_IKAN, KODE_BAKUL) {
	window.location.assign("tpi_summary_lelang.php?_t1="+TGL_1+"&_t2="+TGL_2+"&_n="+KODE_NELAYAN+"&_i="+KODE_IKAN+"&_b="+KODE_BAKUL);
}

</script>



