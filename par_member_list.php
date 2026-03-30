<?php
// par_member_list.php
// daftar anggota partai/membership

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cQUERY="SELECT tb_member1.*, kabupaten.id_kab, kabupaten.nama as 'NAMA_KAB', provinsi.id_prov, provinsi.nama as 'NAMA_PROV', kecamatan.id_kec, kecamatan.nama as 'NAMA_PAC' FROM tb_member1 
		LEFT JOIN kabupaten ON tb_member1.KD_KAB=kabupaten.id_kab 
		LEFT JOIN provinsi ON tb_member1.KD_PROV=provinsi.id_prov 
		LEFT JOIN kecamatan ON kabupaten.id_kab=kecamatan.id_kab 
		where tb_member1.APP_CODE='$cFILTER_CODE' GROUP BY tb_member1.NMR_REG ORDER BY tb_member1.NM_DEPAN DESC";
	$qQUERY=mysql_query($cQUERY);
?>

<!DOCTYPE html>
<html class=" ">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>Rainbow Sys : <?php echo S_MSG('CR01','Laporan Daftar Anggota')?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon" />    <!-- Favicon -->
        <link rel="apple-touch-icon-precomposed" href="assets/images/apple-touch-icon-57-precomposed.png">	<!-- For iPhone -->
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/apple-touch-icon-114-precomposed.png">    <!-- For iPhone 4 Retina display -->
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/apple-touch-icon-72-precomposed.png">    <!-- For iPad -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/apple-touch-icon-144-precomposed.png">    <!-- For iPad Retina display -->




        <!-- CORE CSS FRAMEWORK - START -->
        <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>
        <!-- CORE CSS FRAMEWORK - END -->

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
        <link href="assets/plugins/datatables/css/jquery.dataTables.css" rel="stylesheet" type="text/css" media="screen"/>
		  <link href="assets/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet" type="text/css" media="screen"/>
		  <link href="assets/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet" type="text/css" media="screen"/>
		  <link href="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" type="text/css" media="screen"/>        
		  <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


        <!-- CORE CSS TEMPLATE - START -->
        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>
        <!-- CORE CSS TEMPLATE - END -->

    </head>
    <!-- END HEAD -->

    <!-- BEGIN BODY -->
    <body class=" ">
		<?php
			require_once("scr_topbar.php");
		?>

		<!-- START CONTAINER -->
			<div class="page-container row-fluid">

            <!-- SIDEBAR - START -->
            <div class="page-sidebar ">

                <!-- MAIN MENU - START -->
                <div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
						<?php
							require_once("scr_user_info.php");
							require_once("scr_menu.php");
						?>
                </div>
<!--  
                <div class="project-info">

                    <div class="block1">
                        <div class="data">
                            <span class='title'>New&nbsp;Orders</span>
                            <span class='total'>2,345</span>
                        </div>
                        <div class="graph">
                            <span class="sidebar_orders">...</span>
                        </div>
                    </div>

                    <div class="block2">
                        <div class="data">
                            <span class='title'>Visitors</span>
                            <span class='total'>345</span>
                        </div>
                        <div class="graph">
                            <span class="sidebar_visitors">...</span>
                        </div>
                    </div>

                </div>
-->


            </div>
            <!--  SIDEBAR - END -->
            <!-- START CONTENT -->
            <section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <div class="page-title">

									<div class="pull-left">
                                <h1 class="title"><?php echo S_MSG('CR01','Laporan Daftar Anggota')?></h1>                            
									</div>

                            <div class="pull-right hidden-xs">
                                <ol class="breadcrumb">
												<li>
													 <a href="#section-settings" data-toggle="modal"> <i class="fa fa-print"></i>Print</a>
												</li>
 <!--
                                    <li>
                                        <a href="index.html"><i class="fa fa-home"></i>Home</a>
                                    </li>
                                    <li>
                                        <a href="tables_basic.php">Tables</a>
                                    </li>
                                    <li class="active">
                                        <strong>Data Tables</strong>
                                    </li>	-->
                                </ol>
                            </div>

                        </div>
						</div>		
						  
						<div class="clearfix"></div>

						<div class="col-lg-12">
                        <section class="box ">
                            <header class="panel_header">
                                <h2 class="title pull-left"></h2>
<!--
                                <div class="actions panel_actions pull-right">
                                    <i class="box_toggle fa fa-chevron-down"></i>
                                    <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                                    <i class="box_close fa fa-times"></i>
                                </div>
-->
                            </header>
                            <div class="content-body">    <div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">

											
												<table id="example" class="display table table-hover table-condensed" cellspacing="0" width="100%">
													  <thead>
															<tr>
																 <th><?php echo S_MSG('CB07','Kode Anggota')?></th>
																 <th><?php echo S_MSG('CB03','Nama')?></th>
																 <th><?php echo S_MSG('CB61','Nama DPD')?></th>
																 <th><?php echo S_MSG('CB33','Nama DPC')?></th>
																 <th><?php echo S_MSG('CB71','Ranting')?></th>
																 <th><?php echo S_MSG('F005','Alamat')?></th>
															</tr>
													  </thead>

													<tbody>
															<?php
																while($aREC_LOAN=mysql_fetch_array($qQUERY)) {
																echo '<tr>';
																	echo '<td>'.$aREC_LOAN['NMR_REG'].'</td>';
																	echo '<td>'.$aREC_LOAN['NM_DEPAN'].'</td>';
																	echo '<td>'.$aREC_LOAN['NAMA_PROV'].'</td>';
																	echo '<td>'.$aREC_LOAN['NAMA_KAB'].'</td>';
																	echo '<td>'.$aREC_LOAN['NAMA_PAC'].'</td>';
																	echo '<td>'.$aREC_LOAN['ALAMAT'].'</td>';
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
            <!-- END CONTENT -->
            <div class="page-chatapi hideit">
                <div class="search-bar">
                    <input type="text" placeholder="Search" class="form-control">
                </div>
					<div class="chat-wrapper">
						<?php
						  require_once("scr_groups_chat.php");
						  require_once("scr_more_favorites.php");
						  require_once("scr_more_contact.php");
						?>
					</div>
            </div>
            <div class="chatapi-windows ">	</div>    
			</div>
        <!-- END CONTAINER -->
        <!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->

			<?php
				require_once("js_framework.php");
			?>

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


        <!-- CORE TEMPLATE JS - START --> 
        <script src="assets/js/scripts.js" type="text/javascript"></script> 
        <!-- END CORE TEMPLATE JS - END --> 

        <!-- Sidebar Graph - START --> 
        <script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="assets/js/chart-sparkline.js" type="text/javascript"></script>
        <!-- Sidebar Graph - END --> 


        <!-- General section box modal start -->
        <div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Print daftar Anggota</h4>
                    </div>
                    <div class="modal-body">

                        Modul belum siap ..........

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
<!--                        <button class="btn btn-success" type="button">Save changes</button> 	-->
                    </div>
                </div>
            </div>
        </div>
        <!-- modal end -->
    </body>
</html>



