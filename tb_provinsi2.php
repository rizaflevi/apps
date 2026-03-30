
<?php
	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
	}
		 
	$FILTDATA 	= $_SESSION['data_FILTER_CODE'];

	$qQUERY=OpenTable('TbProvince', "APP_CODE='$cFILTER_CODE' and DELETOR=''");

	$cHEADER = S_MSG('CO00','Tabel Provinsi');
	

?>
<!DOCTYPE html>
<html class="js mq no-touch">
<?php
  require_once("scr_header.php");
?>

	<!--=============================================================================-->
	<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.js"></script>
	<style>
	th {
		background-color: #4CAF50;
		color: white;
		}
	tr:hover {
		background: lightgrey; 
		} 
	td a {
		display: block; 
		border: 1px solid blue;
		padding: 16px; 
		}
	</style>
	<!--=============================================================================-->

	<style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style><style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style><style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style></head>

	<?php
	  require_once("scr_topbar.php");
	?>

	<div class="page-container row-fluid">
		<div class="page-sidebar collapseit">
			<div style="height: 513px;" class="page-sidebar-wrapper ps-container ps-active-y" id="main-menu-wrapper"> 
				<?php
					require_once("scr_user_info.php");
					require_once("scr_menu.php");
				?>
			</div>
		 <div style="" class="project-info">	</div>
		</div>
		<section id="main-content" class="sidebar_shift">
			<section class="wrapper main-wrapper" style="">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="page-title">
						<div class="pull-left">
							<h1 class="title"><?php echo $cHEADER?></h1>
						</div>
						<div class="pull-right hidden-xs">
							  <ol class="breadcrumb">
									<li>
										 <a href="tb_provinsi_add.php"><i class="fa fa-plus-square"></i><?php echo S_MSG('CB68','Add Prov.')?></a>
									</li>
									<li>
										 <a href="tb_prov_print.php"><i class="fa fa-print"></i>Print</a>
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
								<div class="col-md-6 col-sm-6 col-xs-6">
									<label class="form-label"><h2><strong><?php echo S_MSG('CO34','Daftar DPD')?></strong></h2></label>
									<div class="table-responsive">
										<table cellspacing="0" id="tabelprov" class="table table-small-font table-bordered table-striped" style="cursor:pointer">
											<tr>
												<th><?php echo S_MSG('CB02','Kode DPD')?></th>
												<th><?php echo S_MSG('CB04','Nama DPD')?></th>
											</tr>
										<?php
										while($row = SYS_FETCH($qQUERY)){
											?>
											<tr class="data_list">
												<td class="kd_prov"><?= $row['id_prov'] ?></td>
												<td class="nm_prov"><?= $row['nama'] ?></td>
											</tr>
										<?php
										}
										?>
										</table>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="form-group has-success">
										<label class="form-label"><h2><strong><?php echo S_MSG('CB65','Detil Data DPD')?></strong></h2></label>
										<div class="controls">
											<form action="tb_provinsi_crud.php?action=update" method="post">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo S_MSG('CB02','Kode DPD')?></label>
													<div class="col-sm-8">
														<input class="form-control" name='KODE_PROV' id="f_ucode" placeholder="Kode Prov" type="text"></br></br>
													</div>
												<label class="form-label-700" for="field-1"><?php echo S_MSG('CO01','Nama Prov')?></label>
													<input class="form-control" name='NAMA_PROV' id="f_unamer" placeholder="<?php echo S_MSG('CB61','Nama DPD')?>" type="text"></br>
												<label class="form-label-700" for="field-1"><?php echo S_MSG('F005','Alamat')?></label>
													<input class="form-control" name='ADDRESS' id="f_uadd" placeholder="<?php echo S_MSG('CO03','Alamat DPD')?>" type="text"></br>
												<label class="form-label-700" for="field-1"><?php echo S_MSG('CB06','Kota')?></label>
													<input class="form-control" name='CITY' id="f_ucity" placeholder="Kota" type="text"></br>
												<label class="form-label-700" for="field-1"><?php echo S_MSG('H650','Kode Pos')?></label>
													<input class="form-control" name='ZIP' id="f_uzip" placeholder="Kode Pos" type="text"></br>
												<label class="form-label-700" for="field-1"><?php echo S_MSG('F006','Nomor Telpon')?></label>
													<input class="form-control" name='PHONE' id="f_uzip" placeholder="Nmr. Telpon"text"></br>
												<label class="form-label-700" for="field-1"><?php echo S_MSG('F007','Nomor Fax')?></label>
													<input class="form-control" name='FAX' id="f_uzip" placeholder="Nomor Fax"text"></br>
												<label class="form-label-700" for="field-1"><?php echo S_MSG('CO11','Email Address')?></label>
													<input class="form-control" name='EMAIL' id="f_uzip" placeholder="email address"text"></br>
												<label class="form-label-700" for="field-1"><?php echo S_MSG('CO12','Web Sites')?></label>
													<input class="form-control" name='WEB' id="f_uzip" placeholder="web site"text"></br>

												<button type="submit" value=Simpan class="btn btn-info btn-lg"><?php echo S_MSG('F303','UPDATE')?></button>
												<button type="reset" class="btn btn-purple btn-lg pull-right"><?php echo S_MSG('F304','DELETE')?></button>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</section>
		</section>
			<!-- END CONTENT -->
<!--			<div class="page-chatapi hideit">

				<div class="search-bar">
					<input placeholder="Search" class="form-control" type="text">
				</div>

				<div style="height: 559px;" class="chat-wrapper ps-container ps-active-y">
						<?php
						  //require_once("scr_groups_chat.php");
						  //require_once("scr_more_favorites.php");
						  //require_once("scr_more_contact.php");
						?>
					<div style="left: 0px; bottom: 3px;" class="ps-scrollbar-x-rail">
						<div style="left: 0px; width: 0px;" class="ps-scrollbar-x">
						</div>
					</div>
					<div style="top: 0px; height: 559px; right: 3px;" class="ps-scrollbar-y-rail">
						<div style="top: 0px; height: 242px;" class="ps-scrollbar-y"></div>
					</div>
					<div style="left: 0px; bottom: 3px;" class="ps-scrollbar-x-rail">
						<div style="left: 0px; width: 0px;" class="ps-scrollbar-x"></div>
					</div>
					<div style="top: 0px; height: 559px; right: 3px;" class="ps-scrollbar-y-rail">
						<div style="top: 0px; height: 242px;" class="ps-scrollbar-y"></div>
					</div>
					<div style="left: 0px; bottom: 3px;" class="ps-scrollbar-x-rail">
						<div style="left: 0px; width: 0px;" class="ps-scrollbar-x"></div>
					</div>
					<div style="top: 0px; height: 559px; right: 3px;" class="ps-scrollbar-y-rail">
						<div style="top: 0px; height: 242px;" class="ps-scrollbar-y"></div>
					</div>
				</div>
			</div>
		<div class="chatapi-windows hideit"></div>    -->
	</div>
	<!-- END CONTAINER -->
	<!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->

	<?php	require_once("js_framework.php");			?>

	<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
	  <script src="assets/plugins/responsive-tables/js/rwd-table.min.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


	  <!-- CORE TEMPLATE JS -  --> 
	  <script src="assets/js/scripts.js" type="text/javascript"></script> 

	  <!-- Sidebar Graph -  --> 
	  <script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js" type="text/javascript"></script>
	  <script src="assets/js/chart-sparkline.js" type="text/javascript"></script>
	  
	<!-- General section box modal start -->
	<div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
		<div class="modal-dialog animated bounceInDown">
			 <div class="modal-content">
				  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">?</button>
						<h4 class="modal-title">Section Settings</h4>
				  </div>
				  <div class="modal-body">

						Kode Provinsi sudah ada ............

				  </div>
				  <div class="modal-footer">
						<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						<button class="btn btn-success" type="button">Save changes</button>
				  </div>
			 </div>
		</div>
	</div>
	<!-- modal end --> 


	<!--<script type="text/javascript">//<![CDATA[
	//$(window).load(function(){
	//$(".data_list").click(function() {
	//	var $row = $(this).closest("tr");    // Find the row
	//	var $title = $row.find(".kd_prov").text(); // Find the text
	//	var $author = $row.find(".nm_prov").text();
	//	var $time = $row.find(".al_prov").text();
	//	var $city = $row.find(".ct_prov").text();
    
		// Let's test it out
	//	document.getElementById("f_ucode").value = ($title);
	//	document.getElementById("f_unamer").value = ($author);
	//	document.getElementById("f_uadd").value = ($time);
	//	document.getElementById("f_ucity").value = ($city);
	//	});

	//});//]]> 


</script>
--> 

<script>
	var table = document.getElementById('tabelprov');
	
	for(var i = 0; i < table.rows.length; i++) {
		table.rows[i].onclick = function() {
			//rIndex = this.rowsIndex;
			//console.log(rIndex);
			document.getElementById("f_ucode").value = this.cells[0].innerHTML;
            document.getElementById("f_unamer").value = this.cells[1].innerHTML;
		}
	}
</script>
</body></html>

