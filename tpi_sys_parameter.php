<?php
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
//	die ($_SESSION['gSYS_PARA']);
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$USERCODE 		= $_SESSION['gUSERCODE'];
	$MINE 			= $_SESSION['gUSERNAME'];
	$USER_AS 		= $_SESSION['gUSER_AS'];
	$xSCR_HEADER 	= $_SESSION['gSCR_HEADER'];
	$_SESSION['gSCR_HEADER']     = 'DASHBOARD';
	$cSPACE90 	= ".". str_repeat(' ', 88) . ".";

	$c_JNS_PRSHN = S_PARA('JNS_PRSHN','1                                                 I        P          A                  ');
/*
	$qSYS_RECORD=SYS_QUERY("select * from rainbow where KEY_FIELD='JNS_PRSHN' and APP_CODE='$cFILTER_CODE'");
	$cSYS_RECORD=SYS_FETCH($qSYS_RECORD);
	if(SYS_ROWS($qSYS_RECORD)==0){
		$cQUERY="insert into rainbow set KEY_FIELD='JNS_PRSHN', KEY_CONTEN='$cSPACE90', APP_CODE='$cFILTER_CODE'";
		SYS_QUERY($cQUERY) or die ('Error in query.' .$cQUERY);
	}
*/	
	$l_KOPERASI	= substr($c_JNS_PRSHN,23,1);
	$l_G_L 		= substr($c_JNS_PRSHN,46,1);
	$l_PAYROLL 	= substr($c_JNS_PRSHN,49,1);
	$ada_HOTEL=0;
	if(substr($c_JNS_PRSHN,2,1)=='3') {
		$ada_HOTEL=1;
	}

	$cLINE_CHR = S_PARA('LINE_CHR','-');
	$sLATTITUDE	=substr($cLINE_CHR,0,10);
	$sLONGITUDE	=substr($cLINE_CHR,10,10);
	$sHRG_LELANG=substr($cLINE_CHR,60,1);
	$sDEP_LELANG=substr($cLINE_CHR,61,1);
	$nKERANJANG1	=substr($cLINE_CHR,22,4);
	$nKERANJANG2	=substr($cLINE_CHR,26,4);
	$nKERANJANG3	=substr($cLINE_CHR,30,4);
	$nKERANJANG4	=substr($cLINE_CHR,34,4);
	$nKERANJANG5	=substr($cLINE_CHR,38,4);
	$nKERANJANG6	=substr($cLINE_CHR,42,4);
	$nKERANJANG7	=substr($cLINE_CHR,46,4);
	$nKERANJANG8	=substr($cLINE_CHR,50,4);
	$nKERANJANG9	=substr($cLINE_CHR,54,4);
	
	$qNOTE1=SYS_QUERY("select * from rainbow where KEY_FIELD='NOTE1' and APP_CODE='$cFILTER_CODE'");
	$cNOTE1=SYS_FETCH($qNOTE1);
	if(SYS_ROWS($qNOTE1)==0){
		$cQUERY="insert into rainbow set KEY_FIELD='NOTE1', KEY_CONTEN='$cSPACE90', APP_CODE='$cFILTER_CODE'";
		SYS_QUERY($cQUERY);
	}
	$cINTRO_SMS=$cNOTE1['KEY_CONTEN'];

	$qNOTE2=SYS_QUERY("select * from rainbow where KEY_FIELD='NOTE2' and APP_CODE='$cFILTER_CODE'");
	$cNOTE2=SYS_FETCH($qNOTE2);
	if(SYS_ROWS($qNOTE2)==0){
		$cQUERY="insert into rainbow set KEY_FIELD='NOTE2', KEY_CONTEN='$cSPACE90', APP_CODE='$cFILTER_CODE'";
		SYS_QUERY($cQUERY);
	}
	$cBODY_SMS	=$cNOTE2['KEY_CONTEN'];
	
	$qNOTE3=SYS_QUERY("select * from rainbow where KEY_FIELD='NOTE3' and APP_CODE='$cFILTER_CODE'");
	$cNOTE3=SYS_FETCH($qNOTE3);
	if(SYS_ROWS($qNOTE3)==0){
		$cQUERY="insert into rainbow set KEY_FIELD='NOTE3', KEY_CONTEN='$cSPACE90', APP_CODE='$cFILTER_CODE'";
		SYS_QUERY($cQUERY);
	}
	$cFOOT_SMS	=$cNOTE3['KEY_CONTEN'];
	
	$cREC_SYS=substr($cLINE_CHR,20,1);
	$l_PRINT_LABEL=0;		// print label setiap timbang
	if($cREC_SYS=='P') {
		$l_PRINT_LABEL=1;
	}
//-6.26564 108.12345P    1   2   3   4   5   6   7   8   91221  1000000   1000   5000
	$cREC_PAY=SYS_FETCH(SYS_QUERY("select * from rainbow where APP_CODE='$cFILTER_CODE' and KEY_FIELD='PAY_ROLL'"));
	$c_PAYROLL=$cREC_PAY['KEY_CONTEN'];		// setting payroll
	$PROLL_JAM_MSK 		= substr($c_PAYROLL,0,5);
	$PROLL_JAM_KLR		= substr($c_PAYROLL,5,5);
	$PROLL_TOLERAN		= substr($c_PAYROLL,10,3);
	$PROLL_CUTI_THN		= substr($c_PAYROLL,13,2);
	$PROLL_FINGERPRINT	= substr($c_PAYROLL,14,1);

	$cHARI_KERJA	= S_MSG('PA45','Hari Kerja');
	$cMINGGU		= S_MSG('FF63','Sunday');
	$cSENIN 		= S_MSG('FF51','Senin');
	$cSELASA		= S_MSG('FF53','Selasa');
	$cRABU			= S_MSG('FF55','Rabu');
	$cKAMIS			= S_MSG('FF57','Kamis');
	$cJUMAT			= S_MSG('FF59','Jumat');
	$cSABTU			= S_MSG('FF61','Sabtu');

	$cLBL_BHS = S_MSG('SP13','Bahasa');

	$cACTION='';
	if (isset($_GET['ACTION'])) {
		$cACTION=$_GET['ACTION'];
	}
switch($cACTION){
	default:

?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	  require_once("scr_header.php");	  require_once("scr_topbar.php");	?>
		<div class="page-container row-fluid">

			<div class="page-sidebar ">
				 <div class="page-sidebar-wrapper" id="main-menu-wrapper">
					<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
				</div>
				<div class="project-info"></div>
			</div>
			
			<section id="main-content" class=" ">
				 <section class="wrapper main-wrapper" style=''>

					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="page-title">
							<div class="pull-left">
								  <h1 class="title">System Parameters</h1>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12">
						<section class="box ">
							<div class="content-body">    
								<div class="row">
									<form action ="?ACTION=SAVE" method="post">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<!-- Horizontal - start -->
										<div class="row">
											<div class="col-md-12">
	<!--												<h4>Primary</h4>		-->
												<ul class="nav nav-tabs primary">
													<li>
														<a href="#system_1" data-toggle="tab">
															<i class="fa fa-user"></i> System
														</a>
													</li>
													<li class="active">
														  <a href="#home-1" data-toggle="tab">
																<i class="fa fa-home"></i> <?php echo S_MSG('SPL1','Lelang')?>
														  </a>
													</li>
												</ul>
												<div class="tab-content primary">
													<div class="tab-pane fade" id="system_1">
													
														<label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('SP01','Area deteksi antrian')?></label>
														<label class="col-sm-2 form-label-700" for="field-2"><?php echo S_MSG('SP02','Latitude')?></label>
														<input type="text" class="col-sm-2 form-label-900" name="LATTITUDE" value=<?php echo $sLATTITUDE?> data-mask="fdecimal"  ><br><br>
														
														<label class="col-sm-4 form-label-700" for="field-1"></label>
														<label class="col-sm-2 form-label-700" for="field-1"><?php echo S_MSG('SP03','Longitude')?></label>
														<input type="text" class="col-sm-2 form-label-900" name="LONGITUDE" value=<?php echo $sLONGITUDE?> data-mask="fdecimal"><br><br>

														<label class="col-md-4 form-label-700" for="field-1"><?php echo S_MSG('SP07','Print label')?></label>
														<div class="form-block"><input type="checkbox" name="BLH_EDIT" <?php if($l_PRINT_LABEL==1) { echo 'checked'; }?> class="iswitch iswitch-md iswitch-secondary"></div><br>

														<label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('SP05','Format sms')?></label>
														<input type="text" class="col-sm-6 form-label-900" name="FORMAT_SMS1" value="<?php echo $cINTRO_SMS?>"><br>

														<label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('SP10','Format sms hasil timbangan')?></label>
														<input type="text" class="col-sm-6 form-label-900" name="FORMAT_SMS2" value="<?php echo $cBODY_SMS?>"><br>

														<label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('SP12','Format sms penutup')?></label>
														<input type="text" class="col-sm-6 form-label-900" name="FORMAT_SMS3" value="<?php echo $cFOOT_SMS?>"><br>

														<label class="col-sm-3 form-label-700" for="field-1"><?php echo S_MSG('SP31','Berat keranjang ikan')?></label>
														<input type="text" class="col-sm-1 form-label-900" name="KERANJANG1" value=<?php echo $nKERANJANG1?> data-mask="fdecimal">
														<input type="text" class="col-sm-1 form-label-900" name="KERANJANG2" value=<?php echo $nKERANJANG2?> data-mask="fdecimal">
														<input type="text" class="col-sm-1 form-label-900" name="KERANJANG3" value=<?php echo $nKERANJANG3?> data-mask="fdecimal">
														<input type="text" class="col-sm-1 form-label-900" name="KERANJANG4" value=<?php echo $nKERANJANG4?> data-mask="fdecimal">
														<input type="text" class="col-sm-1 form-label-900" name="KERANJANG5" value=<?php echo $nKERANJANG5?> data-mask="fdecimal">
														<input type="text" class="col-sm-1 form-label-900" name="KERANJANG6" value=<?php echo $nKERANJANG6?> data-mask="fdecimal">
														<input type="text" class="col-sm-1 form-label-900" name="KERANJANG7" value=<?php echo $nKERANJANG7?> data-mask="fdecimal">
														<input type="text" class="col-sm-1 form-label-900" name="KERANJANG8" value=<?php echo $nKERANJANG8?> data-mask="fdecimal">
														<input type="text" class="col-sm-1 form-label-900" name="KERANJANG9" value=<?php echo $nKERANJANG9?> data-mask="fdecimal">
														
														<div class="clearfix"><br></div>	

													</div>
													<div class="tab-pane fade in active" id="home-1">

														<div class="form-group">
															<label class="col-md-6 form-label-700" for="field-3"><?php echo S_MSG('SPL3','Harga awal lelang')?></label>
															<input type="radio" name="BAHASA_STAT" value='1' <?php if($sHRG_LELANG=='1') { echo "checked";}?> /><?php echo S_MSG('SPL4','Harga per Kg')?>
															<span class="desc">  </span>
															<span class="desc">  </span>
															<input type="radio" name="BAHASA_STAT" value='2'  <?php if($sHRG_LELANG=='2') { echo "checked";}?> /><?php echo S_MSG('SPL5','Harga Total')?><br>
														</div>

														<div class="form-group">
															<label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('SP25','Format Nomor Bukti Pembayaran Kas/Bank')?></label>
															<span class="desc">e.g. "999999"</span>
															<div class="controls">
																<input type="text" class="form-control" name="field-1" value=<?php echo $cINTRO_SMS?> >
															</div>
														</div>


													</div>

												</div>

											</div>
											<div class="clearfix"><br></div>	
										</div>

										<!-- Horizontal - end -->

										<div class="clearfix"></div><br>
										<button type="submit" class="btn btn-info btn-lg">UPDATE</button>
										<div class="clearfix hidden-md hidden-lg"><br></div>
									</div>
									</form>
								</div>
							</div>
						</section>
					</div>

				</section>
			</section>
			<?php	include "scr_chat.php";	?>
		</div>
		<?php	require_once("js_framework.php");	?>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 
	</html>

<?php
	break;

case "SAVE":
	$lCHECK = 0;
	if (isset($_POST['BLH_EDIT'])){
		$lCHECK = 1;
	}
	$qSYS_RECORD=SYS_QUERY("select * from rainbow where APP_CODE='$cFILTER_CODE' and KEY_FIELD='PAY_ROLL'");
	if(SYS_ROWS($qSYS_RECORD)==0){
		$cADD_RECORD="insert into rainbow set APP_CODE='$cFILTER_CODE', KEY_FIELD='PAY_ROLL'";
		$qQUERY=SYS_QUERY($cADD_RECORD);
	}

	$cQUERY ="update rainbow set KEY_CONTEN='$_POST[BAHASA_STAT]' where KEY_FIELD='LANG'";
	SYS_QUERY($cQUERY);

	$cQUERY ="update rainbow set KEY_CONTEN='$lCHECK' where KEY_FIELD='UEDIT_PAST'";
	SYS_QUERY($cQUERY);

//		die ($l_PAYROLL);
	if($l_PAYROLL=='P') {
//		die ($_POST['UPD_TOLERAN']);
	}
	
	header('location:tpi_sys_parameter.php');
	break;

}
?>


