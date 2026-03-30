<?php
//	kop_rep_loan.php
//	Laporan daftar rekening pinjaman anggota 

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cHEADER		= S_MSG('KR21','Laporan Rekening Pinjaman Anggota');
	$cHELP_BOX		= S_MSG('KR22','Help Laporan Rekening Pinjaman Anggota');
	$ADD_LOG	= APP_LOG_ADD();

	$cQUERY="SELECT A.NO_MEMBER, A.LOAN_CODE, A.LOAN_DATE, A.LOAN_ACT, A.LOAN_VAL, A.LOAN_BALAN, A.APP_CODE, A.DELETOR, 
				B.KD_MMBR, B.NM_DEPAN, B.KLPK_ANGGT, B.TIPE_ANGGT, 
				tab_pinj.KODE_PINJM, tab_pinj.NAMA_PINJM, tipe_otl.KODETIPE, tipe_otl.NAMATIPE, 
				grouplgn.KODE_GRP, grouplgn.NAMA_GRP
				FROM tr_loan1 A
				LEFT JOIN ( select * from tb_member1 where APP_CODE='$cFILTER_CODE' and DELETOR='' ) B ON A.NO_MEMBER=B.KD_MMBR
				LEFT JOIN tab_pinj ON A.LOAN_CODE=tab_pinj.KODE_PINJM
				LEFT JOIN grouplgn ON B.KLPK_ANGGT=grouplgn.KODE_GRP
				LEFT JOIN tipe_otl ON B.TIPE_ANGGT=tipe_otl.KODETIPE
				where A.APP_CODE='$cFILTER_CODE' and A.DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	$cRPT_BODY_CLASS = S_PARA('_RPT_BODY_CLASS','sidebar-collapse');
	$c_TTL_STYLE = S_PARA('_REPORT_TOTAL_STYLE', 'font-size: 24px;color: Brown;background-color: LightGray ;');

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
				<div class="project-info"></div>
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
										<a href="#help_kop_rep_loan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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

                                        <table id="example" class="display table table-hover table-condensed" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
													<th style="width: 1px;"></th>
													<th><?php echo S_MSG('KB12','Tanggal')?></th>
													<th><?php echo S_MSG('KK20','Nomor Rekening')?></th>
													<th><?php echo S_MSG('CB07','Kode Anggota')?></th>
													<th><?php echo S_MSG('F004','Nama')?></th>
													<th><?php echo S_MSG('KA20','Jenis Pinjaman')?></th>
													<th><?php echo S_MSG('KC09','Nilai Pinjaman')?></th>
													<th><?php echo S_MSG('KC08','Saldo Pinjaman')?></th>
													<th><?php echo S_MSG('TT01','Tipe Anggota')?></th>
													<th><?php echo S_MSG('F160','Kelompok')?></th>
                                                </tr>
                                            </thead>

											<tbody>
												<?php
													while($aREC_LOAN=SYS_FETCH($qQUERY)) {
													echo '<tr>';
														echo '<td class=""><div class="star"><i class="fa fa-file-o icon-xs icon-default"></i></div></td>';
														echo '<td>'.$aREC_LOAN['LOAN_DATE'].'</td>';
														echo '<td>'.$aREC_LOAN['LOAN_ACT'].'</td>';
														echo '<td>'.$aREC_LOAN['NO_MEMBER'].'</td>';
														echo '<td>'.$aREC_LOAN['NM_DEPAN'].'</td>';
														echo '<td>'.$aREC_LOAN['NAMA_PINJM'].'</td>';
														echo '<td align="right">'.number_format($aREC_LOAN['LOAN_VAL']).'</td>';
														echo '<td align="right">'.number_format($aREC_LOAN['LOAN_BALAN']).'</td>';
														echo '<td>'.$aREC_LOAN['NAMATIPE'].'</td>';
														echo '<td>'.$aREC_LOAN['NAMA_GRP'].'</td>';
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
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 
        <div class="modal" id="help_kop_rep_loan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
                    </div>
                    <div class="modal-body">


                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal end -->
    </body>
</html>



