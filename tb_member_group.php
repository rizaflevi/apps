<?php
/* tb_member_group.php
	tabel kelompok anggota
*/

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
		 
	$USERCODE 	= $_SESSION['gUSERCODE'];
	$MINE 		= $_SESSION['gUSERNAME'];
	$USER_AS 	= $_SESSION['gUSER_AS'];
	$cHEADER = S_MSG('F161','Tabel kelompok Anggota');

	$cQUERY=mysql_query("select * FROM tb_group where APP_CODE='$cFILTER_CODE' and DELETOR=''");

?>

<html class="js mq no-touch">
	<?php	  require_once("scr_header.php");	?>

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

		<?php	  require_once("scr_topbar.php");	?>
		<div class="page-container row-fluid">
			<div class="page-sidebar collapseit">
					 <div style="height: 513px;" class="page-sidebar-wrapper ps-container ps-active-y" id="main-menu-wrapper"> 
						<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
					 </div>
					 <div style="" class="project-info"></div>
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
									<li>	<a href="tb_user_add.php"><i class="fa fa-plus-square"></i><?php echo S_MSG('F171','Add group')?></a>	</li>
									<li>	<a href="tb_user_print.php"><i class="fa fa-print"></i>Print</a>	</li>
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
									  <label class="form-label"><h2><strong><?php echo S_MSG('F167','Daftar Kelompok Anggota')?></strong></h2></label>
										<div class="table-responsive">
											<table cellspacing="0" id="tech-companies-1" class="table table-small-font table-bordered table-striped" style="cursor:pointer">
												<tr>
													<th><?php echo S_MSG('F162','Kode Kelompok')?></th>
													<th><?php echo S_MSG('F163','Nama Kelompok')?></th>
												</tr>
											<?php
											while($row = mysql_fetch_array($cQUERY)){
												?>
												<tr class="data_list">
													<td class="title"><?php echo $row['KODE_GRP'] ?></td>
													<td class="author"><?php echo $row['NAMA_GRP'] ?></td>
												</tr>
											<?php
											}
											?>
											</table>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<div class="form-group has-success">
											<label class="form-label"><h2><strong><?php echo S_MSG('TU18','Detil Data User')?></strong></h2></label>
											<div class="controls">
												<form action="tb_user_save.php" method="post">
													<label class="form-label-700" for="field-1"><?php echo S_MSG('F162','Kode Kelompok')?></label>
													<input class="form-control" id="f_title" placeholder="Kode User" type="text"></br>
													<label class="form-label-700" for="field-1"><?php echo S_MSG('F163','Nama Kelompok')?></label>
													<input class="form-control" id="f_author" placeholder="Nama User" type="text"></br>

													<button type="submit" value=Simpan class="btn btn-info btn-lg">UPDATE</button>
													<button type="reset" class="btn btn-purple btn-lg pull-right">RESET</button>
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
			<?php	include "scr_chat.php";	?>
		</div>
		<?php	require_once("js_framework.php");	?>
		<script src="assets/plugins/responsive-tables/js/rwd-table.min.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
			<div class="modal-dialog animated bounceInDown">
				 <div class="modal-content">
					  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">?</button>
							<h4 class="modal-title">Section Settings</h4>
					  </div>
					  <div class="modal-body">

					  </div>
					  <div class="modal-footer">
							<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
							<button class="btn btn-success" type="button">Save changes</button>
					  </div>
				 </div>
			</div>
		</div>
		<!-- modal end --> 

<script type="text/javascript">//<![CDATA[
	$(window).load(function(){
	$(".data_list").click(function() {
		var $row = $(this).closest("tr");    // Find the row
		var $title = $row.find(".title").text(); // Find the text
		var $author = $row.find(".author").text();
		var $time = $row.find(".time").text();
    
		// Let's test it out
		document.getElementById("f_title").value = ($title);
		document.getElementById("f_author").value = ($author);
		document.getElementById("f_time").value = ($time);
		});

	});//]]> 

</script>
<script>

	function doaction( celDiv, id ) {
    	$( celDiv ).click( function() {
    		var nim = $(this).parent().parent().children('td').eq(0).text();
    		$.getJSON ('index.php',{action:'get_mhs',nim:nim}, function (json) {
    			$('#nim').val(json.nim);
    			$('#nama').val(json.nama);
    			$('#alamat').val(json.alamat);
    		}); 
    		$('#nim').attr('readonly','readonly');
    		$('#input').attr('disabled','disabled');
    		$('#edit, #delete').removeAttr('disabled');
    	});
	}

</script>

</body></html>

