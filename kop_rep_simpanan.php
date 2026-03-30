<?php
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('KR10','Laporan Rekening Simpanan Anggota');

	$dPERIOD1=date("Y-m-d");
	$dPERIOD2=date("Y-m-d");

	if (isset($_GET['TANGGAL1'])) {
		$dPERIOD1=$_GET['TANGGAL1'];
	}

	if (isset($_GET['TANGGAL2'])) {
		$dPERIOD2=$_GET['TANGGAL2'];
	}

	$cQUERY="SELECT tr_save1.*, 
		tb_member1.KD_MMBR, tb_member1.NM_DEPAN, tb_member1.KLPK_ANGGT, tb_member1.TIPE_ANGGT, 
		tab_simp.KODE_SIMPN, tab_simp.NAMA_SIMPN, tipe_otl.*, grouplgn.* FROM tr_save1
			LEFT JOIN tb_member1 ON TR_SAVE1.KD_MMBR=tb_member1.KD_MMBR
			LEFT JOIN tab_simp ON TR_SAVE1.SAVE_CODE=tab_simp.KODE_SIMPN
			LEFT JOIN grouplgn ON tb_member1.KLPK_ANGGT=grouplgn.KODE_GRP
			LEFT JOIN tipe_otl ON tb_member1.TIPE_ANGGT=tipe_otl.KODETIPE
		where tr_save1.APP_CODE='$cFILTER_CODE' and tr_save1.DELETOR='' and
			tr_save1.SAVE_DATE>='$dPERIOD1' and tr_save1.SAVE_DATE<='$dPERIOD2'";
	$qQUERY=SYS_QUERY($cQUERY);

	$cRPT_BODY_CLASS = S_PARA('_RPT_BODY_CLASS','sidebar-collapse');
?>

<!DOCTYPE html>
<html class=" ">
	<?php	require_once("scr_headtr.php");	?>
	<body class="<?php echo $cRPT_BODY_CLASS?>">
		<?php	require_once("scr_topbar.php");	?>
		<div class="page-container row-fluid">
			<div class="page-sidebar collapseit ">
				<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
					<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
				</div>
				<div class="project-info"> </div>
			</div>

			<section id="main-content" class="sidebar_shift">
				<section class="wrapper main-wrapper" style=''>

					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<div class="page-title">
							<div class="pull-left">
								<h2 class="title"><?php echo $cHEADER?></h2>
							</div>
							<div class="pull-right hidden-xs">
								<ol class="breadcrumb">
									<li>
										<a href="#help_kop_rep_simpanan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
										<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $dPERIOD1?>" onchange="select_SIMPAN(this.value, '<?php echo $dPERIOD2?>')"><br><br>

										<label class="col-sm-1 form-label-700" for="field-4"><?php echo S_MSG('RS14','s/d')?></label>
										<input type="text" class="col-sm-2 form-label-900 datepicker" data-format="yyyy-mm-dd" value="<?php echo $dPERIOD2?>" onchange="select_SIMPAN('<?php echo $dPERIOD1?>', this.value)">

										<table id="example" class="display table table-hover table-condensed" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th style="width: 1px;"></th>	
													<th><?php echo S_MSG('KK20','Nomor Rekening')?></th>
													<th><?php echo S_MSG('KB12','Tanggal')?></th>
													<th><?php echo S_MSG('CB07','Kode Anggota')?></th>
													<th><?php echo S_MSG('F004','Nama')?></th>
													<th><?php echo S_MSG('KK21','Jenis Simpanan')?></th>
													<th><?php echo S_MSG('KB25','Saldo Simpanan')?></th>
												</tr>
											</thead>

											<tbody>
												<?php
													while($aREC_SAVE=SYS_FETCH($qQUERY)) {
													echo '<tr>';
														echo '<td style="width: 1px;"></td>';
														echo '<td>'.$aREC_SAVE['SAVE_ACT'].'</td>';
														echo '<td>'.$aREC_SAVE['SAVE_DATE'].'</td>';
														echo '<td>'.$aREC_SAVE['KD_MMBR'].'</td>';
														echo '<td>'.decode_string($aREC_SAVE['NM_DEPAN']).'</td>';
														echo '<td>'.$aREC_SAVE['NAMA_SIMPN'].'</td>';
														echo '<td align="right">'.number_format($aREC_SAVE['SV_BALANCE']).'</td>';
													echo '</tr>';
													}
												?>

											</tbody>
										</table>

									</div>
								</div>
							</div>
						</section>
					</div>

				</section>
			</section>
			<?php	include "scr_chat.php";	?>
		</div>
		<?php	require_once("js_framework.php");	?>
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 

        <div class="modal" id="help_kop_rep_simpanan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><?php echo S_MSG('KR11','Help Laporan Rekening Simpanan Anggota')?></h4>
                    </div>
                    <div class="modal-body">


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

function select_SIMPAN(TGL_1, TGL_2) {
	window.location.assign("kop_rep_simpanan.php?TANGGAL1="+TGL_1+"&TANGGAL2="+TGL_2);
}

</script>


