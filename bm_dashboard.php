<?php
//	bm_dashboard.php
// 	dashboard buat mobile

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
//	$database1 = constant('DATABASE_1');

	$cTITLE		= S_MSG('RQ91','Dev. Rainbow System');
	$cHEADER	= S_MSG('RQ92','PT. Yaza Pratama');
	$d_BM_START = S_PARA('BM_START', date("Y-01-01"));
	
	$q_ANGGOTA	= SYS_QUERY("select * from person1 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nANGGOTA 	= number_format(SYS_ROWS($q_ANGGOTA));

	$dPERIOD1	= date("Y-m-d");
	$q_CUSTOMER	= SYS_QUERY("select IDPEL, PETUGAS, NOTE	from bm_dt_baca
		where left(TGL_BACA,10)='$dPERIOD1' and PETUGAS='$_SESSION[gCAT_TER]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$nCUSTOMER = SYS_ROWS($q_CUSTOMER);

	$cPELANGGAN	= S_MSG('PQ00','Pelanggan');
	$nPELANGGAN = 0;
	$c_JNS_PRSHN = S_PARA('JNS_PRSHN',' ');
	$ada_BC_MTR=0;
	if(substr($c_JNS_PRSHN,58,1)!='') {
		$ada_BC_MTR=1;
		$q_PELANGGAN=SYS_QUERY("select count(*) as RECN from bm_tb_plg where (PETUGAS='$_SESSION[gCAT_TER]' or PETUGAS='') and LAST_VISIT<'$d_BM_START' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
		$a_PELANGGAN = SYS_FETCH($q_PELANGGAN);	
		$nPELANGGAN = $a_PELANGGAN['RECN'];
	}
?>
<!DOCTYPE html>
<html class=" ">
		<?php	require_once("scr_headtr.php");	?>

	<!-- Custom CSS from SB -->
	<link href="sb/css/bootstrap.min.css" rel="stylesheet">
	<link href="sb/css/sb-admin-2.css" rel="stylesheet">
	<link href="sb/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<body onload="get_lokasi();">
		<div class="page-container row-fluid">
			
				<section class="wrapper main-wrapper"  style='margin:0px'>
					<div class="clearfix"></div>
					<div class='col-xs-12'>
                            <header class="panel_header">
                                <h2 class="title pull-left"><?php echo $cHEADER?></h2>
                            </header>
					</div>
					<div class="clearfix"></div>

					<div class='col-xs-12'>
							<div class="content-body">
								<div class="row">
								
<!-- ================================================================================================= --> 
									<?php
/* - ================================================================================================= --> */
										$cMAIN_MENU="select * from sys_menu 
										right join sys_dash on sys_menu.PRG = sys_dash.DASH_PRG
										WHERE sys_menu.APP_CODE='$cFILTER_CODE' and sys_menu.MAIN='MOBILE' and sys_menu.ITEM>0 order by sys_menu.ITEM";
										$qMAIN_MENU=SYS_QUERY($cMAIN_MENU);
										while($aMAIN_MENU=SYS_FETCH($qMAIN_MENU)){
											echo '<div class="col-xs-'.$aMAIN_MENU['DASH_WIDTH'].' col-xs-12">';
												echo '<a href="'.$aMAIN_MENU['LINK_PAGE'].'">';
												echo '<div class="panel '.$aMAIN_MENU['DASH_COLOR'].'">';
													echo '<div class="panel-heading">';
														echo '<div class="row">';
															echo '<div class="col-xs-3">';
																echo '<i class="fa '.$aMAIN_MENU['DASH_FA'].'"></i>';
															echo '</div>';
															$cVAR = $aMAIN_MENU['SUB'];
															echo '<div class="col-xs-9 text-right">';
																if($aMAIN_MENU['LOK_ICON']!='') {
																echo '<div class="huge">'.number_format(${$cVAR}).'</div>';
																}
																echo '<div>' . $aMAIN_MENU['PROMPT_MN'] .'</div>';
															echo '</div>';
														echo '</div>';
													echo '</div>';
													if($aMAIN_MENU['LOK_ICON']!='') {
														echo '<div class="panel-footer">
															<span class="pull-left">View Details</span>
															<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
															<div class="clearfix"></div>
														</div>';
														} else {
															echo '<div class="panel-footer">
															<span class="pull-left"></span>
															<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
															<div class="clearfix"></div>';
														}
													echo '</div>
												</a>
											</div>';
										}
									?>

							</div>
						</div>
					</div>

				</section>
		</div>
		<?php	require_once("js_framework.php");	?>
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

	</body>
</html>

<script>
function get_lokasi() {

	if (navigator.geolocation)	{
		navigator.geolocation.getCurrentPosition(showPosition);
	}

	function showPosition(position)	{
		$.post('update_lokasi.php',{
			_la:position.coords.latitude,
			_lo:position.coords.longitude
		});
	}
}
</script>
