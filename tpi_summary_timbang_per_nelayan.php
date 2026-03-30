<?php
//	tpi_summary_timbang_per_nelayan.php
//	laporan summary/tabulasi hasil timbang per nelayan

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('TB46','Summary Hasil Timbang Nelayan');

	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
	$cHELP_BOX	= S_MSG('TB4A','Help Laporan Summary Hasil Timbang per Nelayan');
	$cHELP_1	= S_MSG('TB4B','Ini adalah laporan dari data timbangan berdasarkan Nelayan dan jenis ikan.');
	$cHELP_2	= S_MSG('TB4C','Dengan laporan ini Nelayan dapat memonitor hasil timbangan nya berdasarkan tanggal timbang dan jenis ikan');
	$cHELP_3	= S_MSG('TB4D','Pada laporan ini di tampilkan hasil timbangan setiap nelayan beserta rekap per jenis ikan, sehingga dapat memudahkan Nelayan mengikuti perkembangan timbangan.');
	$cHELP_4	= S_MSG('TB4E','Data yang ditampilkan di laporan ini bisa di filter berdasarkan tanggal timbang, per nelayan tertentu, atau per jenis ikan tertentu.');


	$cKODE_TABEL = S_MSG('F003','Kode');
	$cNAMA_TABEL = S_MSG('TF53','Nama Nelayan');
	$cNAMA_IKAN  = S_MSG('TF03','Nama Ikan');

	$c_FORMAT = S_PARA('PICT_STRUK_TIMBANG', '1');
	$c_TTL_STYLE = S_PARA('_REPORT_TOTAL_STYLE', 'font-size: 24px;color: Brown;background-color: LightGray ;');
	$c_HDR_STYLE = S_PARA('_REPORT_HEADER_STYLE', 'background-color:LightGray;');

	$dTODAY		= date('Y-m-d');
	$dPERIOD1	= date("Y-m-d");
	$dPERIOD2	= date("Y-m-d");

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

/*
	$cQUERY="select * from dt_scale WHERE APP_CODE='$cAPP_CODE' order by DATA_DATE, DATA_TIME limit 1";
	$qQUERY=SYS_QUERY($cQUERY);
	$aFIRST_SCALE = SYS_FETCH($qQUERY);
	$cFIRST_SCALE = $aFIRST_SCALE['DATA_DATE'];
	$nTAHUN_AWAL	= substr($cFIRST_SCALE,0,4);
*/
	$qDT_SCALE=OpenTable('DtScale', "DATA_DATE='$dTODAY' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', "DATA_DATE desc, DATA_TIME desc limit 1");
/*	$cDT_SCALE="select * from dt_scale WHERE APP_CODE='$cAPP_CODE' order by DATA_DATE desc, DATA_TIME desc limit 1";
	$qDT_SCALE=SYS_QUERY($cDT_SCALE);	*/
	$aLAST_SCALE = SYS_FETCH($qDT_SCALE);
	$cLAST_SCALE = '';
	if ($aLAST_SCALE)
	$cLAST_SCALE = $aLAST_SCALE['DATA_DATE']. ' '.$aLAST_SCALE['DATA_TIME'];

	$qTB_FISHR=OpenTable('FisherScale', "B.DATA_DATE>='$dPERIOD1' and B.DATA_DATE<='$dPERIOD2' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)", 'A.FISHR_CODE', "A.FISHR_CODE");
/*	$cTB_FISHR= "select dt_scale.FISHR_CODE, dt_scale.FISH_CODE, dt_scale.FISH_WEIGT, tb_fishr.FISHR_NAME from tb_fishr
		LEFT JOIN dt_scale on dt_scale.FISHR_CODE=tb_fishr.FISHR_CODE
		WHERE dt_scale.DATA_DATE>='$dPERIOD1' and dt_scale.DATA_DATE<='$dPERIOD2' and";
	if ($cNELAYAN!='') {
		$cTB_FISHR.=" dt_scale.FISHR_CODE='$cNELAYAN' and";
	}
	if ($cIKAN!='') {
		$cTB_FISH.=" dt_scale.FISH_CODE='$cIKAN' and";
	}	
	$cTB_FISHR.=" tb_fishr.APP_CODE='$cAPP_CODE' and tb_fishr.DELETOR='' group by tb_fishr.FISHR_CODE order by tb_fishr.FISHR_CODE";
	$qTB_FISHR=SYS_QUERY($cTB_FISHR);
*/
	$cFILT = "B.DATA_DATE>='$dPERIOD1' and B.DATA_DATE<='$dPERIOD2' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)";
	if ($cNELAYAN!='') {
		$cFILT.=" and B.FISHR_CODE='$cNELAYAN";
	}
	if ($cIKAN!='') {
		$cTB_FISH.=" B.FISH_CODE='$cIKAN'";
	}	
	$qTB_FISHR=OpenTable('FishScale', $cFILT, 'A.FISH_CODE', "A.FISH_CODE");
	
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

        <!-- CORE CSS FRAMEWORK - -->
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
					<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");
					?>
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
										<a href="#help_tpi_summary_timbang_per_nelayan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
										<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $dPERIOD1?>" onchange="select_TIMBANG(this.value, '<?php echo $dPERIOD2?>', '<?php echo $cNELAYAN?>', '<?php echo $cIKAN?>')">

										<label class="col-sm-1 form-label-700"></label>
										<label class="col-sm-2 form-label-700" for="field-4"><?php echo S_MSG('TN53','Nelayan')?></label>
										<select name='NLYN' class="col-sm-3 form-label-900" onchange="select_TIMBANG('<?php echo $dPERIOD1?>', '<?php echo $dPERIOD2?>', this.value, '<?php echo $cIKAN?>')">
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
										<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $dPERIOD2?>" onchange="select_TIMBANG('<?php echo $dPERIOD1?>', this.value, '<?php echo $cNELAYAN?>', '<?php echo $cIKAN?>')">

										<label class="col-sm-1 form-label-700"></label>
										<label class="col-sm-2 form-label-700" for="field-4"><?php echo S_MSG('TF00','Ikan')?></label>
										<select name='IKAN' class="col-sm-3 form-label-900" onchange="select_TIMBANG('<?php echo $dPERIOD1?>', '<?php echo $dPERIOD2?>', '<?php echo $cNELAYAN?>', this.value)">
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

										<div class="clearfix"></div>

                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table cellspacing="0" id="tech-companies-1" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="<?php echo $c_HDR_STYLE?>" data-priority="1"><?php echo $cKODE_TABEL?></th>
                                                        <th style="background-color:LightGray;" data-priority="1"><?php echo $cNAMA_TABEL?></th>
                                                        <th style="background-color:LightGray;" data-priority="1"><?php echo $cNAMA_IKAN?></th>
                                                        <th style="background-color:LightGray;text-align:right;" data-priority="3"><?php echo S_MSG('TF64','Berat')?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
														$tBRT_ALL=0;
//														$dTB_FISHR=SYS_QUERY($cTB_FISHR);
														while($aREC_FISHR=SYS_FETCH($qTB_FISHR)){
															$cFISHR_CODE=$aREC_FISHR['FISHR_CODE'];
															$cFISHR_NAME=decode_string($aREC_FISHR['FISHR_NAME']);
															$cCOND = "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)";
															if ($cIKAN!='') $cCOND.="  and dt_scale.FISH_CODE='$cIKAN' ";
															$qDT_SCALE=OpenTable('ScaleFisher', $cCOND);
/*															$cDT_SCALE="select * from dt_scale 
																LEFT JOIN tb_fishr on tb_fishr.FISHR_CODE=dt_scale.FISHR_CODE
																LEFT JOIN tb_fish on tb_fish.FISH_CODE=dt_scale.FISH_CODE
																where dt_scale.FISHR_CODE='$cFISHR_CODE' and 
																	dt_scale.DATA_DATE>='$dPERIOD1' and dt_scale.DATA_DATE<='$dPERIOD2'";
															if ($cIKAN!='') {
																$cDT_SCALE.="  and dt_scale.FISH_CODE='$cIKAN' ";
															}
															$cDT_SCALE.=" and dt_scale.APP_CODE='".$cAPP_CODE."' and dt_scale.DELETOR='' group by tb_fishr.FISHR_CODE, tb_fish.FISH_CODE order by tb_fishr.FISHR_CODE, tb_fish.FISH_CODE";
															$qDT_SCALE=SYS_QUERY($cDT_SCALE);
*/
															while($aREC_TB_FISH=SYS_FETCH($qDT_SCALE)) {
																$nBERAT = 0;
																$cKODE_IKAN = $aREC_TB_FISH['FISH_CODE'];
																$cNAMA_IKAN = decode_string($aREC_TB_FISH['FISH_NAME']);
																$cCOND = "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and 
																	FISHR_CODE='$cFISHR_CODE' and FISH_CODE='$cKODE_IKAN' 
																	and DATA_DATE>='$dPERIOD1' and DATA_DATE<='$dPERIOD2'";
																$eDT_SCALE=OpenTable('ScaleFisher', $cCOND);
/*																$dDT_SCALE="select FISHR_CODE, FISH_CODE, FISH_WEIGT, APP_CODE, DELETOR from dt_scale 
																			where FISHR_CODE='$cFISHR_CODE' and FISH_CODE='$cKODE_IKAN' 
																				and DATA_DATE>='$dPERIOD1' and DATA_DATE<='$dPERIOD2'";
																$eDT_SCALE=SYS_QUERY($dDT_SCALE); */
																while($rDT_SCALE=SYS_FETCH($eDT_SCALE)) {
																	$nBERAT += $rDT_SCALE['FISH_WEIGT'];
																}
																echo '<tr>';
																	echo "<td><span>".$cFISHR_CODE." </span></td>";
																	echo "<td><span>".$cFISHR_NAME." </span></td>";
																	echo "<td><span>".$cNAMA_IKAN." </span></td>";
																	echo '<td align="right"><span>'.number_format($nBERAT, $c_FORMAT).' </span></td>';
																	
																	$tBRT_ALL+=$nBERAT;
																	
																echo '</tr>';
															}
														}
													?>

                                                </tbody>
												<?php
													echo '<tr>
														<td style="'.$c_TTL_STYLE.'" align="right">T O T A L</td>
														<td style="'.$c_TTL_STYLE.'" align="right"></td>
														<td style="'.$c_TTL_STYLE.'" align="right"></td>
														<td style="'.$c_TTL_STYLE.'" align="right">'.number_format($tBRT_ALL, $c_FORMAT).'</td>
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

		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
        <script src="assets/plugins/responsive-tables/js/rwd-table.js" type="text/javascript"></script>

        <script src="assets/js/scripts.js" type="text/javascript"></script> 

        <div class="modal" id="help_tpi_summary_timbang_per_nelayan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
                    </div>
                    <div class="modal-body">
						<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_3?></p>	<p><?php echo $cHELP_4?></p>
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

function select_TIMBANG(TGL_1, TGL_2, KODE_NELAYAN, KODE_IKAN) {
	window.location.assign("tpi_summary_timbang_per_nelayan.php?_t1="+TGL_1+"&_t2="+TGL_2+"&_n="+KODE_NELAYAN+"&_i="+KODE_IKAN);
}

</script>



