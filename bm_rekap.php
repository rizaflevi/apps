<?php
// bm_rekap.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cHEADER		= S_MSG('RQ50','Rekap Baca');
	$cTERBACA 		= S_MSG('RQ51','Terbaca');
	$cTERKIRIM 		= S_MSG('RQ52','Terkirim');
	$cPERSENTASI	= S_MSG('RQ53','Persentasi');
	$cNO_URUT 		= S_MSG('RK03','No.');
	$cNMR_METER 	= S_MSG('RQ04','Nomor Meter');
	$cNAMA_TBL 		= S_MSG('PQ03','Nama Pelanggan');
	$cALAMAT 		= S_MSG('PQ04','Alamat');
	$cKETERANGAN 	= S_MSG('PN10','Keterangan');
	$dPERIOD1=date("Y-m-d");
	$q_DATA_BACA= SYS_QUERY("select count(*) as RECN from bm_dt_baca
		where left(TGL_BACA,10)='$dPERIOD1' and PETUGAS='$_SESSION[gCAT_TER]' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$a_TERBACA		= SYS_FETCH($q_DATA_BACA);
	$nTERBACA = $a_TERBACA['RECN'];
//		var_dump();	exit();
	$nTERKIRIM		= $nTERBACA;
	$nPROSEN		= 0;
	if ($nTERBACA>0) {
		$nPROSEN 	= $nTERKIRIM / $nTERBACA * 100;
	}
	$cSQLCOMMAND= "select IDPEL, PETUGAS, NOTE	from bm_dt_baca
		where left(TGL_BACA,10)='$dPERIOD1' and PETUGAS='$_SESSION[gCAT_TER]' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$qQUERY=SYS_QUERY($cSQLCOMMAND);
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<div class="page-container row-fluid">
			
				<section class="wrapper main-wrapper" style='margin:0px'>
					<div class="clearfix"></div>
					<div class="col-xs-12">
                            <header class="panel_header">
                                <h2 class="title pull-left"><?php echo $cHEADER?></h2>
                            </header>
							<div class="content-body">
								<div class="row">
<!-- ****************************************** start content ********************************************* -->

									<div class="col-xs-12">
										<label class="col-xs-6 form-label-900" style="background-color:white;font-size:150%;"><?php echo $cTERBACA?></label>
										<label class="col-xs-6 form-label-900" style="background-color:lightgray;color:black;font-size:200%;text-align:right;line-height:35px"><?php echo $nTERBACA?></label>
										<div class="clearfix"></div>
										<label class="col-xs-6 form-label-900" style="background-color:white;font-size:150%;"><?php echo $cTERKIRIM?></label>
										<label class="col-xs-6 form-label-900" style="background-color:lightgray;color:black;font-size:200%;text-align:right;line-height:35px"><?php echo $nTERKIRIM?></label>
										<div class="clearfix"></div>
										<label class="col-xs-6 form-label-900" style="background-color:white;font-size:150%;"><?php echo $cPERSENTASI?></label>
										<label class="col-xs-6 form-label-900" style="background-color:lightgray;color:black;font-size:200%;text-align:right;line-height:35px"><?php echo $nPROSEN?></label>
										<div class="clearfix"></div><br>

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?php echo $cNMR_METER?></th>
                                                    <th><?php echo $cNAMA_TBL?></th>
                                                    <th><?php echo $cALAMAT?></th>
                                                    <th><?php echo $cKETERANGAN?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
												<?php
													$nROW=0;
													while($r_BM_DT_BACA=SYS_FETCH($qQUERY)) {
														$nROW++;
														echo '<tr class="success">';
														echo '<th scope="row">'.$nROW.'</th>';
														$a_BM_TB_PEL = SYS_FETCH(SYS_QUERY("SELECT NOMOR_KWH, NAMA_PEL, ALAMAT from bm_tb_pel where IDPEL='$r_BM_DT_BACA[IDPEL]' and APP_CODE='$cFILTER_CODE' and DELETOR=''"));
														echo '<td>'.$a_BM_TB_PEL['NOMOR_KWH'].'</td>';
														echo '<td>'.$a_BM_TB_PEL['NAMA_PEL'].'</td>';
														echo '<td>'.$a_BM_TB_PEL['ALAMAT'].'</td>';
														echo '<td>'.$r_BM_DT_BACA['NOTE'].'</td>';
														echo '</tr>';
													}
												?>
                                            </tbody>
                                        </table>
										<div class="text-left">
											<input type="button" class="col-sm-4 btn btn-info btn-lg" value="OK" onclick=window.location.href='bm_dashboard.php'>
										</div>	<div class="clearfix"></div><br>

									</div>
<!-- ****************************************** start content ********************************************* -->

								</div>
							</div>
					</div>

				</section>
		</div>
		<?php	require_once("js_framework.php");	?>
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>

