<?php
//	tb_branch.php // tdk jadi di pakai => tb_branch.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];

	$cHEADER 		= S_MSG('TB50','Tabel Cabang');
	$cKODE_BRCH		= S_MSG('TB51','Kode');
	$cNAMA_BRCH 	= S_MSG('TB52','Nama Cabang');
	$cBRCH_ADDR1 	= S_MSG('TB53','Alamat Cabang');
	$cBRCH_ADDR2	= '';
	$cBRCH_CITY		= S_MSG('NL54','Kota');
	$cNO_TELP		= S_MSG('F006','No. BRCH_PHONE');
	$cNO_HP			= S_MSG('F007','Nomor HP');
	$cEMAIL_ADDR	= S_MSG('F105','Email address');
	
	$cTIP_KODE		= S_MSG('TB54','Setiap Cabang di beri kode untuk memudahkan meng akses data cabang');
	$cTIP_NAMA		= S_MSG('TB55','Nama cabang');
	$cTIP_ALM1		= S_MSG('TB56','Alamat Cabang');
	$cTIP_TELP		= S_MSG('TB58','Nomor telpon cabang');
		
	$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cADD_REC		= S_MSG('TB7F','Tambah Cabang');
	$cEDIT_TBL		= S_MSG('TB7G','Edit Tabel Cabang');
	$cDAFTAR		= S_MSG('TB7H','Daftar Cabang');
	
	$cSAVE_DATA		= S_MSG('F301','Save');
	$cCLOSE_DATA	= S_MSG('F302','Close');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

switch($cACTION){
	default:
	$ADD_LOG	= APP_LOG_ADD();
	$cHELP_BOX	= S_MSG('TB8A','Help Tabel Cabang');
	$cHELP_1	= S_MSG('TB8B','Ini adalah modul untuk memasukkan data Master Cabang');
	$cHELP_2	= S_MSG('TB8C','Untuk memasukkan data Cabang baru, klik tambah Cabang');
	$cHELP_3	= S_MSG('TB8D','Untuk melihat atau merubah data Cabang, klik di kode atau nama Cabang yang akan di rubah');
	$cHELP_4	= S_MSG('TB8E','Arahkan mouse pointer ke baris data Cabang yang akan dirubah, ketika ditampilkan garis bawah pada data nya, klik untuk merubah atau melihat detil nya');

	$qQUERY=OpenTable('TbBranch');

?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<body class=" ">
			<?php	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
						<?php	require_once("scr_menu.php");	?>
					</div>
					<div class="project-info"></div>
				</div>
				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>
						<div class="clearfix"></div>

						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left"><?php echo $cHEADER?></h2>
									<div class="pull-right">
										<ol class="breadcrumb">
											<li>
												<a href="?_a=<?php echo md5('CREATE_BRANCH')?>"><i class="fa fa-plus-square"></i><?php echo $cADD_REC?></a>
											</li>
											<li>
												<a href="#help_tbl_branch" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>" cellspacing="0">
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_BRCH?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_BRCH?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cBRCH_ADDR1?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_DISP=SYS_FETCH($qQUERY)) {
														echo '<tr>';
															$cICON = 'fa-sitemap';
															echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?_a=".md5('UPD_BRANCH')."&_c=".md5($aREC_DISP['BRCH_CODE'])."'>".$aREC_DISP['BRCH_CODE']."</a></span></td>";
															echo "<td><span><a href='?_a=".md5('UPD_BRANCH')."&_c=".md5($aREC_DISP['BRCH_CODE'])."'>".$aREC_DISP['BRCH_NAME']."</a></span></td>";
															echo '<td>'.$aREC_DISP['BRCH_ADDR1'].'</td>';
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

			<div class="modal" id="help_tbl_branch" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">

							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>
							<p><?php echo $cHELP_3?></p>	<p><?php echo $cHELP_4?></p>

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
<?php
	SYS_DB_CLOSE($DB2);	break;

case md5('CREATE_BRANCH'):
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<body class=" ">
			<?php	require_once("scr_topbar.php");	?>
			<!-- START CONTAINER -->
			<div class="page-container row-fluid">

				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper">
						<?php	require_once("scr_menu.php");	?>
					</div>
					<div class="project-info"> </div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>
						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">
								<div class="pull-left">
									<h2 class="title"><?php echo $cADD_REC?></h2>                            
								</div>
								<div class="pull-right hidden-xs">
									<ol class="breadcrumb">
										<li>	<a href="#help_add_tb_branch" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>	</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<section class="box ">
							<div class="content-body">
								<div class="row" style="margin-right: 0px">
									<form name="FORM_ADD_TB_BRANCH" action ="?_a=tambah" method="post">
										<div class="col-sm-9">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_BRCH?></label>
											<input type="text" class="col-sm-2 form-label-900" name='ADD_BRCH_CODE' autofocus title="<?php echo S_MSG('TB54','Setiap Cabang di beri kode untuk memudahkan meng akses data cabang')?>"><br>
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_BRCH?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_BRCH_NAME' id="field-2" title="<?php echo S_MSG('TB55','Nama cabang')?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-25"><?php echo $cBRCH_ADDR1?></label>
											<textarea name='ADD_BRCH_ADDR1' class="col-sm-6 form-label-900 autogrow" cols="5" id="field-25"  style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 50px;"></textarea>
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cBRCH_ADDR2?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_BRCH_ADDR2' id="field-6"/>
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-31"><?php echo $cNO_TELP?></label>
											<input type="text" class="col-sm-4 form-label-900" name='ADD_BRCH_PHONE'>
											<div class="clearfix"></div>
											<label class="col-sm-4 form-label-700" for="field-32"><?php echo $cNO_HP?></label>
											<input type="text" class="col-sm-4 form-label-900" name='ADD_BRCH_FAX' id="field-31" ><br>
											<div class="clearfix"></div><br>

											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
												<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
											</div>

										</div>
									</form>
								</div>
							</div>
						</section>

					</section>
				</section>
				<?php	include "scr_chat.php";	?>
			</div>
			<?php	require_once("js_framework.php");	?>
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 
		</body>
	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case md5('UPD_BRANCH'):
		$qQUERY = OpenTable('TbBranch', "APP_CODE='$cAPP_CODE' and md5(BRCH_CODE)='$_GET[_c]' and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qQUERY)==0){
			header('location:tb_branch.php');
		}
		$a_BRANCH=SYS_FETCH($qQUERY);
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
		<body class=" ">
			<div class="page-container row-fluid">
				
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper">
						<?php	require_once("scr_menu.php");	?>
					</div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class="page-title">
							<div class="pull-left">
								  <h2 class="title"><?php echo $cEDIT_TBL?></h2>
							</div>
							<div class="pull-right">									 
								<ol class="breadcrumb">
									<li>
										<?php echo '<a href="?_a='.md5('del_branch').'&id='. md5($a_BRANCH['BRCH_CODE']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
									</li>
								</ol>
							</div>
						</div>
						<div class="clearfix"></div>

							<section class="box ">
								<div class="pull-right hidden-xs"></div>
								<div class="content-body">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
										<form action ="?_a=rubah&id=<?php echo $a_BRANCH['BRCH_CODE']?>" method="post">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_BRCH?></label>
											<input type="text" class="col-sm-2 form-label-900" name='UPD_BRCH_CODE' id="field-1" value=<?php echo $a_BRANCH['BRCH_CODE']?> disabled="disabled" title="<?php echo $cTIP_KODE?>"><br>
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cNAMA_BRCH?></label>
											<input type="text" class="col-sm-6 form-label-900" name='UPD_BRCH_NAME' id="field-2" value="<?php echo $a_BRANCH['BRCH_NAME']?>" title="<?php echo $cTIP_NAMA?>">
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cBRCH_ADDR1?></label>
											<input type="text" class="col-sm-6 form-label-900" name='UPD_BRCH_ADDR1' id="field-6" value="<?php echo $a_BRANCH['BRCH_ADDR1']?>" title="<?php echo $cTIP_ALM1?>"><br>
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cBRCH_CITY?></label>
											<input type="text" class="col-sm-6 form-label-900" name='UPD_BRCH_CITY' id="field-6" value="<?php echo $a_BRANCH['BRCH_CITY']?>"><br>
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-31"><?php echo $cNO_TELP?></label>
											<input type="text" class="col-sm-4 form-label-900" name='UPD_BRCH_PHONE' id="field-31" value="<?php echo $a_BRANCH['BRCH_PHONE']?>"><br>
											<div class="clearfix"></div>
												
											<label class="col-sm-4 form-label-700" for="field-32"><?php echo $cNO_HP?></label>
											<input type="text" class="col-sm-4 form-label-900" name='UPD_BRCH_FAX' id="field-31" value="<?php echo $a_BRANCH['BRCH_FAX']?>"><br>
											<div class="clearfix"></div>

<!-- TAB - START of update -->
											<div class="col-sm-9">
												<h4> </br></h4>
												<ul class="nav nav-tabs primary">
													 <li class="active">
														<a href="#BRC_ACCCOUNT" data-toggle="tab">
															<i class="fa fa-user"></i> <?php echo $cDATA_ACCOUNT?>
														</a>
													 </li>
													 <li>
														<a href="#PAY_ACCOUNT" data-toggle="tab">
															<i class="fa fa-home"></i> <?php echo $cJNS_BYR?> 
														</a>
													 </li>
												</ul>

												<div class="tab-content primary">
													<div class="tab-pane fade in active" id="BRC_ACCCOUNT">
														<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cACCT_CASH?></label>';
														<select name="UPD_ACCT_CASH" class="col-sm-5 form-label-900">';
														<?php 
															echo "<option value=' '  > None </option>";
															$qQUERY=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																	if($a_BRANCH['CASH_ACCT']==$aREC_ACCOUNT['ACCOUNT_NO']){
																		echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$a_BRANCH[CASH_ACCT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	} else {
																		echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
																	}
																}
															echo '</select>';
														?>	<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-5"><?php echo $cACCT_SALES?></label>
														<select name='UPD_SALES_ACCT' class="col-sm-5 form-label-900">
														<?php 
															echo "<option value=' '  > None </option>";
															$qQUERY=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
															while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																if($a_BRANCH['SALES_ACCT']==$aREC_ACCOUNT['ACCOUNT_NO']){
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$a_BRANCH[SALES_ACCT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																} else {	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";	}
															}
														?>
														</select>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-5"><?php echo $cACCT_COGS?></label>
														<select name='UPD_COST_ACCT' class="col-sm-5 form-label-900">
														<?php
															echo "<option value=' '  > None </option>";
															$qQUERY=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
															while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																if($a_BRANCH['COST_ACCT']==$aREC_ACCOUNT['ACCOUNT_NO']){
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$a_BRANCH[COST_ACCT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																} else {	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
															}
														?>
														</select>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cACCT_INV?></label>
														<select name="UPD_INV_ACCT" class="col-sm-5 form-label-900">
														<?php
															echo "<option value=' '  > None </option>";
															$qQUERY=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
															while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																if($a_BRANCH['INV_ACCT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$a_BRANCH[INV_ACCT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																} else {
																echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
															}
														?>
														</select>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cACCT_RETR?></label>
														<select name="UPD_RT_ACCOUNT" class="col-sm-5 form-label-900">
														<?php
															echo "<option value=' '  > None </option>";
															$qQUERY=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
															while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																if($a_BRANCH['RT_ACCOUNT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$a_BRANCH[RT_ACCOUNT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																} else {
																echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
															}
														?>
														</select>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cACCT_UTANG?></label>
														<select name="UPD_AP_ACCOUNT" class="col-sm-5 form-label-900">
														<?php
															echo "<option value=' '  > None </option>";
															$qQUERY=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
															while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																if($a_BRANCH['AP_ACCOUNT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$a_BRANCH[AP_ACCOUNT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																} else {
																echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
															}
														?>
														</select>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cACCT_BIAYA?></label>
														<select name="UPD_DFLT_ACCT" class="col-sm-5 form-label-900">
														<?php
															echo "<option value=' '  > None </option>";
															$qQUERY=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
															while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																if($a_BRANCH['COST_ACCT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$a_BRANCH[COST_ACCT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																} else {
																echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
															}
														?>
														</select>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cACCT_DISKON?></label>
														<select name="UPD_DISKP_ACCT" class="col-sm-5 form-label-900">
														<?php 
															echo "<option value=' '  > None </option>";
															$qQUERY=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
															while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																if($a_BRANCH['DISKP_ACCT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$a_BRANCH[DISKP_ACCT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																} else {
																echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
															}
														?>
														</select>
														<div class="clearfix"></div>

														<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cACCT_SETOR?></label>
														<select name="UPD_SETOR_ACCT" class="col-sm-5 form-label-900">
														<?php 
															echo "<option value=' '  > None </option>";
															$qQUERY=OpenTable('TbAccount', "GENERAL='D' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
															while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
																if($a_BRANCH['SETOR_ACCT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
																	echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$a_BRANCH[SETOR_ACCT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
																} else {
																echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
															}
														?>
														</select>	<div class="clearfix"></div>
													</div>		<!-- End of Tab 1 -->
													
													<!-- Tab 2 begin -->
													<div class="tab-pane fade" id="PAY_ACCOUNT">
														<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>">
															<thead>
																<tr>
																	<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
																	<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo S_MSG('TB43','Jenis Pembayaran')?></th>
																	<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo S_MSG('TB44','Pakai')?></th>
																</tr>
															</thead>
															<tbody>
																<?php
																	$qQUERY=OpenTable('TbPayType', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																	while($aREC_PAY_TYPE=SYS_FETCH($qQUERY)) {
																		echo '<tr>';
																			echo '<td class=""><div class="star"><i class="fa fa-calendar-o icon-xs icon-default"></i></div></td>';
																			echo "<td>".$aREC_PAY_TYPE['PAY_DESC']."</td>";
																			$q_PAY_BRCH=SYS_QUERY("select * from pay_brch where BRCH_CODE=$a_BRANCH[BRCH_CODE] and PAY_CODE='".$aREC_PAY_TYPE['PAY_CODE']. "' and APP_CODE='$cAPP_CODE' and DELETOR=''");
																			$aREC_PAY_BRCH=SYS_FETCH($q_PAY_BRCH);
																			$cCHECK = '';
																			if($aREC_PAY_BRCH['DI_ACCEPT']==1) { 
																				$cCHECK = 'checked';
																			}
																			echo '<td><input name="UPD_'.$aREC_PAY_TYPE['PAY_CODE'].'" type="checkbox" '. $cCHECK. ' class="iswitch iswitch-md iswitch-secondary"><td>';
																		echo '</tr>';
																	}
																?>
															</tbody>
														</table>
													</div>		<!-- End of Tab 2 -->
												</div></br>

											</div>
											<div class="clearfix"></div>
<!--  TAB - END -->	
											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
												<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
											</div>
											</div>
										</form>
									</div>
								</div>
							</section>

					</section>
				</section>
				<?php	include "scr_chat.php";	?>
			</div>
			<?php	require_once("js_framework.php");	?>
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 

		</body>
	</html>
<?php
	SYS_DB_CLOSE($DB2);	break;

case 'tambah':
	$cBRCH_CODE = encode_string($_POST['ADD_BRCH_CODE']);
	if($cBRCH_CODE=='') {
		$cMSG_BLANK='Kode cabang tidak boleh kosong';
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$qQUERY = OpenTable('TbBranch', "APP_CODE='$cAPP_CODE' and BRCH_CODE='$cBRCH_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qQUERY)==0){
		$cBRCH_NAME = encode_string($_POST['ADD_BRCH_NAME']);
		$cBRCH_ADD1 = encode_string($_POST['ADD_BRCH_ADDR1']);
		$cQUERY ="insert into langgan set APP_CODE='$cAPP_CODE', BRCH_CODE='$cBRCH_CODE', BRCH_NAME='$cBRCH_NAME', ";
		$cQUERY.=" GROUP_LGN='$_POST[ADD_GROUP]', TIPE_LGN='$_POST[ADD_TIPE]', ";
		$cQUERY.=" CANVAS='$_POST[ADD_CARA_ORDER]', ";
		$cQUERY.=" ACCOUNT='$_POST[ADD_ACCOUNT]', ";
		$cQUERY.="BRCH_ADDR1='$cBRCH_ADD1', BRCH_ADDR2='$_POST[BRCH_CITY]', ";
		$cQUERY.="BRCH_PHONE='$_POST[ADD_BRCH_PHONE]', BRCH_FAX='$_POST[ADD_BRCH_FAX]', ";
		$cQUERY.=" ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);
		header('location:tb_branch.php');
	} else {
		$cMSG_EXIST=S_MSG('F035','Kode Pelanggan sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
	}
	break;

case 'rubah':
	$KODE_BRCH=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cBRCH_NAME	= encode_string($_POST['UPD_BRCH_NAME']);	
	$cBRCH_ADD1 = encode_string($_POST['UPD_BRCH_ADDR1']);
	$cBRCH_CITY	= encode_string($_POST['UPD_BRCH_CITY']);	
	$cBRCH_PHON	= encode_string($_POST['UPD_BRCH_PHONE']);	
	$cBRCH_FAXS	= encode_string($_POST['UPD_BRCH_FAX']);	
	$cQUERY ="update branch set BRCH_NAME='$cBRCH_NAME',
		BRCH_ADDR1='$cBRCH_ADD1', BRCH_CITY='$cBRCH_CITY', BRCH_PHONE='$cBRCH_PHON', BRCH_FAX='$cBRCH_FAXS', 
		CASH_ACCT='$_POST[UPD_ACCT_CASH]', SALES_ACCT='$_POST[UPD_SALES_ACCT]', 
		COST_ACCT='$_POST[UPD_COST_ACCT]', INV_ACCT='$_POST[UPD_INV_ACCT]',
		RT_ACCOUNT='$_POST[UPD_RT_ACCOUNT]', AP_ACCOUNT='$_POST[UPD_AP_ACCOUNT]', 
		DFLT_ACCT='$_POST[UPD_DFLT_ACCT]', DISKP_ACCT='$_POST[UPD_DISKP_ACCT]', SETOR_ACCT='$_POST[UPD_SETOR_ACCT]', 
		OFF_DOWN='$_POST[UPD_OFF_DOWN]', COPY_BILL='$_POST[UPD_COPY_BILL]', ";
	$cQUERY.=" UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW' ";
	$cQUERY.="where APP_CODE='$cAPP_CODE' and BRCH_CODE='$KODE_BRCH'";
	SYS_QUERY($cQUERY);

	$qQUERY=SYS_QUERY("select * from pay_type where APP_CODE='$cAPP_CODE' and DELETOR=''");
	while($aREC_PAY_TYPE=SYS_FETCH($qQUERY)) {
		$q_PAY_BRCH=SYS_QUERY("select * from pay_brch where BRCH_CODE='$KODE_BRCH' and PAY_CODE='".$aREC_PAY_TYPE['PAY_CODE']. "' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		$aREC_PAY_BRCH=SYS_FETCH($q_PAY_BRCH);
		$lCHECK = 0;
		$cCHECK = 'UPD_'.$aREC_PAY_TYPE['PAY_CODE'];
		$nCHECK = isset($_POST[$cCHECK]);
		if ($nCHECK == '1') {
			if(SYS_ROWS($q_PAY_BRCH)>0){
				$q_UPD_PAY_BRCH = SYS_QUERY("update pay_brch set DI_ACCEPT=1 where BRCH_CODE='$KODE_BRCH' and PAY_CODE='" .$aREC_PAY_TYPE['PAY_CODE']. "' and APP_CODE='$cAPP_CODE' and DELETOR=''");
			} else {
				$q_UPD_PAY_BRCH = SYS_QUERY("insert into pay_brch set DI_ACCEPT=1, BRCH_CODE='$KODE_BRCH', PAY_CODE='$aREC_PAY_TYPE[PAY_CODE]', APP_CODE='$cAPP_CODE'");
			}
		} else {
			if(SYS_ROWS($q_PAY_BRCH)>0){
				$q_UPD_PAY_BRCH = SYS_QUERY("update pay_brch set DI_ACCEPT=0 where BRCH_CODE='$KODE_BRCH' and PAY_CODE='".$aREC_PAY_TYPE['PAY_CODE']. "' and APP_CODE='$cAPP_CODE' and DELETOR=''");
			}
		}
//echo 'Cek : '.$cCHECK.' = '.$nCHECK.', ';
	}
//	die (' ');
	header('location:tb_branch.php');
	break;

case md5('del_branch'):
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cQUERY ="update langgan set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' 
		where APP_CODE='$cAPP_CODE' and md5(BRCH_CODE)='$KODE_CRUD'";
	SYS_QUERY($cQUERY);	// ;
	header('location:tb_branch.php');
}
?>
