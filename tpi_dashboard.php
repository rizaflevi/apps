<?php
// tpi_dashboard.php
// eTPI dashboard System

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];

	$cJUDUL 	= 'Produksi';
	$cTITLE		= 'Dashboard';
	$cNELAYAN	= S_MSG('TF50','Nelayan');
	$cIKAN		= S_MSG('TF00','Ikan');
	$cBAKUL		= S_MSG('TF88','Peserta Lelang');
	$cTODAY_SCL	= S_MSG('TF42','Timbang Hari ini');
	$cTODAY_BID	= S_MSG('TW21','Lelang Hari ini');
	$cPROFIT	= S_MSG('RF00','Profit');
	$cFILE_LOGO_COMP = 'data/images/'. 'LOGO1_'.$cAPP_CODE.'.jpg';
	$dTODAY		= date('Y-m-d');
	
	$q_NELAYAN=OpenTable('TbFishr', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$nNELAYAN = CVR(SYS_ROWS($q_NELAYAN));

	$q_IKAN=OpenTable('TbFish', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$nIKAN = 0;
	while($aREC_IKAN=SYS_FETCH($q_IKAN)) {
		$nIKAN += 1;
	}

	$q_BAKUL=OpenTable('TbBidder', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$nBAKUL = CVR(SYS_ROWS($q_BAKUL));

	$q_LELANG=OpenTable('DtBidd', "DATA_DATE='$dTODAY' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$nLELANG= 0;
	while($aREC_NILAI=SYS_FETCH($q_LELANG)) {
		$nLELANG += $aREC_NILAI['FISH_WEIGT'] * $aREC_NILAI['FISH_PRICE'];
	}
	$nLELANG = $nLELANG / 1000;

	$q_BIDDING=OpenTable('DtBidd', "DATA_DATE='$dTODAY' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)", '', "DATA_DATE desc, DATA_TIME desc limit 1");
//	$c_BIDDING="select DATA_DATE, DATA_TIME from dt_bidd WHERE APP_CODE='$cAPP_CODE' order by DATA_DATE desc, DATA_TIME desc limit 1";
//	$q_BIDDING=SYS_QUERY($c_BIDDING);
	$aLAST_BIDD = SYS_FETCH($q_BIDDING);
	$cLAST_BIDD = '';
	if ($aLAST_BIDD)
	$cLAST_BIDD = $aLAST_BIDD['DATA_DATE']. ' '.$aLAST_BIDD['DATA_TIME'];

	$q_TODAY=OpenTable('DtScale', "DATA_DATE='$dTODAY' and APP_CODE='$cAPP_CODE'");
	$nBERAT= 0;
	while($aREC_BERAT=SYS_FETCH($q_TODAY)) {
		$nBERAT += $aREC_BERAT['FISH_WEIGT'];
	}

	$qQUERY	= OpenTable('DtScale', "APP_CODE='$cAPP_CODE'", '', "DATA_DATE desc, DATA_TIME desc limit 1");
	$aLAST_SCALE = SYS_FETCH($qQUERY);
	$cLAST_SCALE = $aLAST_SCALE['DATA_DATE']. ' '.$aLAST_SCALE['DATA_TIME'];

	$THN_INI 		= date('Y');
	$THN_LALU		= $THN_INI-1;
	$aBRT_THN_LALU 	= array();
	$aBRT_THN_INI 	= array();
	$tBRT_THN_INI 	= 0;
	$tBRT_THN_LALU	= 0;
	for ($nBLN = 0; $nBLN <= 11; $nBLN++) {

		$nBRT_BLN = 0;
		$nBULAN = $nBLN +1;
		$qQUERY	= OpenTable('SummScale', "BL_TIMBANG=".$nBULAN." and TH_TIMBANG=".$THN_INI." and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		while($r_QUERY=SYS_FETCH($qQUERY)){
			$nBRT_BLN+=$r_QUERY['SUMM_WEIGT'];
		}
		array_push($aBRT_THN_INI, $nBRT_BLN);
		$tBRT_THN_INI += $nBRT_BLN;

		$nBRT_BLN = 0;
		$nBULAN = $nBLN +1;
		$qQUERY	= OpenTable('SummScale', "BL_TIMBANG=".$nBULAN." and TH_TIMBANG=".$THN_INI." and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
		while($r_QUERY=SYS_FETCH($qQUERY)){
			$nBRT_BLN+=$r_QUERY['SUMM_WEIGT'];
		}
		array_push($aBRT_THN_LALU, $nBRT_BLN);
		$tBRT_THN_LALU += $nBRT_BLN;
	}
//	$cJUDUL 		= implode(", ", $aBRT_THN_INI);

	$aGRAPH1 = implode(", ", $aBRT_THN_LALU);
	$aGRAPH2 = implode(", ", $aBRT_THN_INI);
	$aLABELS = '"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"';

	$cDBOARD_SIZE = S_PARA('_DASHBOARD_SIZE','col-lg-3 col-md-6');

?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>

		<!-- Custom CSS from SB -->
		<link href="sb/css/bootstrap.min.css" rel="stylesheet">
		<link href="sb/css/sb-admin-2.css" rel="stylesheet">
		<link href="sb/font-awesome-4.1.0/css/font-awesome.css" rel="stylesheet" type="text/css">
		<!-- START CONTAINER -->

		<div class="page-container row-fluid">
			
			<div class="page-sidebar ">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper">
					<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
				</div>
				<div class="project-info"></div>
			</div>

			<section id="main-content" class=" ">
				<section class="wrapper main-wrapper">

					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="page-title">

							<div class="pull-left">
								<h1 class="title"><?php echo $cTITLE?></h1>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<section class="box ">
							<div class="pull-right hidden-xs">	</div>

							<div class="content-body">
								<div class="row">
								
<!-- ================================================================================================= --> 
									<div class="<?php echo $cDBOARD_SIZE?>">
										<a href="tpi_tb_nelayan.php">
											<div class="panel panel-primary">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
															<i class="fa fa-user icon-lg animated fadeIn"></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo $nNELAYAN?></div>
															<div><?php echo $cNELAYAN?></div>
														</div>
													</div>
												</div>
												<div class="panel-footer">
													<span class="pull-left">View Details</span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</div>
										</a>
									</div>

									<div class="<?php echo $cDBOARD_SIZE?>">
										<div class="panel panel-green">
											<a href="tpi_tb_fish.php">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
															<i class="fa fa-bug icon-lg"></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo CVR(SYS_ROWS($q_IKAN))?></div>
															<div><?php echo $cIKAN?></div>
														</div>
													</div>
												</div>
												<div class="panel-footer">
													<span class="pull-left">View Details</span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>

									<div class="<?php echo $cDBOARD_SIZE?>">
										<div class="panel panel-yellow">
											<a href="tpi_summary_timbang_per_nelayan.php">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
															<i class="fa  fa-external-link fa-3x"></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo CVR($nBERAT).' Kg'?></div>
															<div><?php echo $cTODAY_SCL?></div>
														</div>
													</div>
												</div>
												<div class="panel-footer">
													<span class="pull-left"><?php echo 'Last : '.$cLAST_SCALE?></span>
<!--													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>	-->
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>

									<div class="<?php echo $cDBOARD_SIZE?>">
										<div class="panel panel-red">
											<a href="tpi_tb_bidder.php">
												<div class="panel-heading">
													<div class="row">
														<div class="col-xs-3">
																<i class='fa fa-support fa-3x icon-rounded icon-danger inviewport animated  animated-delay-2400ms animated-duration-1400ms' data-vp-add-class='visible rotateIn'></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo CVR($nBAKUL)?></div>
															<div><?php echo $cBAKUL?></div>
														</div>
													</div>
												</div>
												<div class="panel-footer">
													<span class="pull-left">View Details</span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>

									<div class="<?php echo $cDBOARD_SIZE?>">
										<div class="wid-social google-plus">
											<a href="tpi_trans_lelang.php">
												<div class="social-icon">
													<div class="row">
														<div class="col-xs-3">
															<i class="fa fa-th-list fa-3x"></i>
														</div>
														<div class="col-xs-9 text-right">
															<div class="huge"><?php echo CVR($nLELANG).' rb'?></div>
															<div><?php echo $cTODAY_BID?></div>
														</div>
													</div>
												</div>
												<div class="panel-footer">
													<span class="pull-left"><?php echo 'Last : '.$cLAST_BIDD?></span>
<!--													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>	-->
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>
<!-- ========================================== --> 
								</div>
							</div>
						</section>
					</div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <section class="box ">
                            <header class="panel_header">
                                <h2 class="title pull-left"><?php echo $cJUDUL ?></h2>
                                <div class="actions panel_actions pull-right">
                                    <i class="box_toggle fa fa-chevron-down"></i>
                                    <i class="box_close fa fa-times"></i>
                                </div>
                            </header>
                            <div class="content-body">    
								<div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <canvas id="bar_chartjs"></canvas>
                                    </div>
                                </div><br>
								<ul class="nav nav-pills" role="tablist">
									<li><a href="#" class="badge-primary"><?php echo $THN_LALU ?> <span class="badge"><?php echo CVR($tBRT_THN_LALU) ?></span></a></li>
									<li><a href="#" class="badge-purple"><?php echo $THN_INI ?> <span class="badge"><?php echo CVR($tBRT_THN_INI) ?></span></a></li>
								</ul>
                            </div><br>
							
                        </section>
					</div>

				</section>
			</section>
			<?php	include "scr_chat.php";	?>
 
		</div>
		<?php	require_once("js_framework.php");	?>
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
        <script src="assets/plugins/chartjs-chart/Chart.min.js" type="text/javascript"></script>
<?php /*		<script src="assets/js/chart-chartjs.js" type="text/javascript"></script> */ ?>
		<script src="assets/js/scripts.js" type="text/javascript"></script>
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

<script type="text/javascript">

    $(function () {

        var barChartData = {
            labels: [ <?php echo $aLABELS ?> ],
            datasets: [{
                fillColor: "rgba(31,181,172,1)",
                strokeColor: "rgba(31,181,172,0.8)",
                highlightFill: "rgba(31,181,172,0.8)",
                highlightStroke: "rgba(31,181,172,1)",
                data: [ <?php echo $aGRAPH1 ?> ]
            }, {
                fillColor: "rgba(153,114,181,1.0)",
                strokeColor: "rgba(153,114,181,0.8)",
                highlightFill: "rgba(153,114,181,0.8)",
                highlightStroke: "rgba(153,114,181,1.0)",
                data: [ <?php echo $aGRAPH2 ?> ]
            }]

        }

        var ctxb = document.getElementById("bar_chartjs").getContext("2d");
        window.myBar = new Chart(ctxb).Bar(barChartData, {
            responsive: true
        });
    });

</script>
