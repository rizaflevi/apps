<?php
//	tpi_trans_timbang.php
//	transaksi data timbang

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('TB31','Data Hasil Timbangan');

	$ADD_LOG	= APP_LOG_ADD($cHEADER, 'view');
	$cHELP_BOX	= S_MSG('TB3A','Help Laporan Data Hasil Timbang');
	$cHELP_1	= S_MSG('TB3B','Ini adalah laporan dari data timbangan berdasarkan Tanggal timbang, Nelayan dan jenis ikan.');
	$cHELP_2	= S_MSG('TB3C','Dengan laporan ini dapat dilihat data hasil timbangan nya berdasarkan tanggal timbang dan jenis ikan, maupun per nelayan tertentu');
	$cHELP_3	= S_MSG('TB3D','Data ini didapat dari setiap timbangan yang dilakukan oleh juru timbang di area penimbangan ikan.');
	$cHELP_4	= S_MSG('TB4E','Data yang ditampilkan di laporan ini bisa di filter berdasarkan tanggal timbang, per nelayan tertentu, atau per jenis ikan tertentu.');


	$cTGL_TIMBANG	= S_MSG('RS02','Tanggal');
	$cJAM_TIMBANG	= S_MSG('NL02','Waktu');
	$cKODE_TABEL	= S_MSG('TF52','Kode Nelayan');
	$cNAMA_TABEL = S_MSG('TF46','Nama Kapal');
	$cKODE_IKAN  = S_MSG('TF02','Kode Ikan');
	$cNAMA_IKAN  = S_MSG('TF03','Nama Ikan');

	$c_FORMAT = S_PARA('PICT_STRUK_TIMBANG', '1');
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

/*
	$cQUERY="select * from dt_scale WHERE APP_CODE='$cFILTER_CODE' order by DATA_DATE, DATA_TIME limit 1";
	$qQUERY=SYS_QUERY($cQUERY);
	$aFIRST_SCALE = SYS_FETCH($qQUERY);
	$cFIRST_SCALE = $aFIRST_SCALE['DATA_DATE'];
	$nTAHUN_AWAL	= substr($cFIRST_SCALE,0,4);
*/
	$cDT_SCALE="select * from dt_scale where APP_CODE='$cFILTER_CODE' order by DATA_DATE desc, DATA_TIME desc limit 1";
	$qDT_SCALE=SYS_QUERY($cDT_SCALE);
	$aLAST_SCALE = SYS_FETCH($qDT_SCALE);
	$cLAST_SCALE = $aLAST_SCALE['DATA_DATE']. ' '.$aLAST_SCALE['DATA_TIME'];

	$cDT_SCALE= "select A.DATA_DATE, A.DATA_TIME, A.FISHR_CODE, A.FISH_CODE, A.FISH_WEIGT, B.FISHR_CODE, B.FISHR_NAME, C.FISH_CODE, C.FISH_NAME from dt_scale A
		left join (select * from tb_fishr where APP_CODE='$cFILTER_CODE' and DELETOR='') B on A.FISHR_CODE=B.FISHR_CODE
		left join (select * from tb_fish where APP_CODE='$cFILTER_CODE' and DELETOR='') C on A.FISH_CODE=C.FISH_CODE
		where A.DATA_DATE>='$dPERIOD1' and A.DATA_DATE<='$dPERIOD2' and";
	if ($cNELAYAN!='') {
		$cDT_SCALE.=" A.FISHR_CODE='$cNELAYAN' and";
	}
	if ($cIKAN!='') {
		$cDT_SCALE.=" A.FISH_CODE='$cIKAN' and";
	}	
	$cDT_SCALE.=" A.APP_CODE='$cFILTER_CODE' and A.DELETOR='' order by A.DATA_DATE desc, A.DATA_TIME desc";
	
	$cTB_FISHR= "select B.FISHR_CODE, B.FISH_CODE, B.FISH_WEIGT, A.FISHR_NAME from tb_fishr A
		left join (select * from dt_scale where APP_CODE='$cFILTER_CODE' and DELETOR='') B on B.FISHR_CODE=A.FISHR_CODE
		where B.DATA_DATE>='$dPERIOD1' and B.DATA_DATE<='$dPERIOD2' and";
	if ($cNELAYAN!='') {
		$cTB_FISHR.=" B.FISHR_CODE='$cNELAYAN' and";
	}
	if ($cIKAN!='') {
		$cTB_FISH.=" B.FISH_CODE='$cIKAN' and";
	}	
	$cTB_FISHR.=" A.APP_CODE='$cFILTER_CODE' and A.DELETOR='' group by A.FISHR_CODE order by A.FISHR_CODE";
	$qTB_FISHR=SYS_QUERY($cTB_FISHR);
	
	$cTB_FISH= "select * from tb_fish
		left join dt_scale on dt_scale.FISH_CODE=tb_fish.FISH_CODE
		where dt_scale.DATA_DATE>='$dPERIOD1' and dt_scale.DATA_DATE<='$dPERIOD2' and";
	if ($cNELAYAN!='') {
		$cTB_FISH.=" dt_scale.FISHR_CODE='$cNELAYAN' and";
	}
	if ($cIKAN!='') {
		$cTB_FISH.=" dt_scale.FISH_CODE='$cIKAN' and";
	}	
	$cTB_FISH.=" tb_fish.APP_CODE='$cFILTER_CODE' and tb_fish.DELETOR='' group by tb_fish.FISH_CODE order by tb_fish.FISH_CODE";
	$qTB_FISH=SYS_QUERY($cTB_FISH);
	
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
										<label class="col-sm-2 form-label-700" for="field-4"><?php echo S_MSG('TB53','Nelayan')?></label>
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
										<label class="col-sm-2 form-label-700" for="field-4"><?php echo S_MSG('TB33','Ikan')?></label>
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
                                                        <th style="<?php echo $c_HDR_STYLE?>" data-priority="1"><?php echo $cTGL_TIMBANG?></th>
                                                        <th style="<?php echo $c_HDR_STYLE?>" data-priority="1"><?php echo $cJAM_TIMBANG?></th>
                                                        <th style="<?php echo $c_HDR_STYLE?>" data-priority="1"><?php echo $cKODE_TABEL?></th>
                                                        <th style="background-color:LightGray;" data-priority="1"><?php echo $cNAMA_TABEL?></th>
                                                        <th style="background-color:LightGray;" data-priority="1"><?php echo $cKODE_IKAN?></th>
                                                        <th style="background-color:LightGray;" data-priority="1"><?php echo $cNAMA_IKAN?></th>
                                                        <th style="background-color:LightGray;text-align:right;" data-priority="3"><?php echo S_MSG('TF64','Berat')?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
														$tBRT_ALL=0;
														$qDT_SCALE=SYS_QUERY($cDT_SCALE);
														while($rDT_SCALE=SYS_FETCH($qDT_SCALE)) {
															echo '<tr>';
																echo "<td><span>".$rDT_SCALE['DATA_DATE']." </span></td>";
																echo "<td><span>".$rDT_SCALE['DATA_TIME']." </span></td>";
																echo "<td><span>".$rDT_SCALE['FISHR_CODE']." </span></td>";
																echo "<td><span>".decode_string($rDT_SCALE['FISHR_NAME'])." </span></td>";
																echo "<td><span>".$rDT_SCALE['FISH_CODE']." </span></td>";
																echo "<td><span>".decode_string($rDT_SCALE['FISH_NAME'])." </span></td>";
																echo '<td align="right"><span>'.number_format($rDT_SCALE['FISH_WEIGT'], $c_FORMAT).' </span></td>';
																
																$tBRT_ALL+=$rDT_SCALE['FISH_WEIGT'];
																
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
	window.location.assign("tpi_trans_timbang.php?_t1="+TGL_1+"&_t2="+TGL_2+"&_n="+KODE_NELAYAN+"&_i="+KODE_IKAN);
}

</script>



