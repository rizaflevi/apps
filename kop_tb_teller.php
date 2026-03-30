<?php
// kop_tb_teller.php
// Tabel jenis simpanan

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cQUERY="SELECT tb_teller.*, ".$database1.".tb_user.* FROM tb_teller 
		LEFT JOIN ".$database1.".tb_user ON tb_teller.KD_TELLER=".$database1.".tb_user.USER_CODE 
		where tb_teller.APP_CODE='$cFILTER_CODE' and tb_teller.DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);
	$cHEADER 	= S_MSG('KT01','Table Teller');
	$cKODE_TBL 	= S_MSG('KT02','Kode Teller');
	$cNAMA_TBL 	= S_MSG('KT03','Nama User');
	$cMAX_TRAN 	= S_MSG('KT04','Max. Transaksi');
	$cJAB_TELR 	= S_MSG('KT05','Jabatan');
	$cNOTE_TELR = S_MSG('KT06','Keterangan');
	$cACCT_KAS 	= S_MSG('KK27','Account K a s');
	$cADD 		= S_MSG('KT21','Tambah Teller');
	$cSAVE_DATA	= S_MSG('F301','Save');
	$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('KT71','Help Tabel Teller');
		$cHELP_1	= S_MSG('KT72','Ini adalah modul untuk memasukkan data Teller / Kasir yang melayani transaksi setoran atau pengambilan uang');
		$cHELP_2	= S_MSG('KT73','Untuk memasukkan data Teller baru, klik tambah Teller / add new');
?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<body class=" ">
			<?php	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
						<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
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
										<div class="pull-right hidden-xs">
											<ol class="breadcrumb">
												<li>
													 <a href="?_a=<?php echo md5('cr34t3')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
												</li>
												<li>
													<a href="#help_kop_tb_teller" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
												</li>
											</ol>
										</div>
									</header>
									<div class="content-body">    
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">

												<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>">
													<thead>
														<tr>
															<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_TBL?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_TBL?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cMAX_TRAN?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cJAB_TELR?></th>
															<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNOTE_TELR?></th>
														</tr>
													</thead>

													<tbody>
														<?php
															while($aREC_TB_TELLER=SYS_FETCH($qQUERY)) {
															echo '<tr>';
																echo '<td style="width: 1px;"></td>';
																echo "<td><span><a href='?_a=".md5('upd_telr')."&_t=".md5($aREC_TB_TELLER['KD_TELLER'])."'>".$aREC_TB_TELLER['KD_TELLER']."</a></span></td>";
																echo "<td><span><a href='?_a=".md5('upd_telr')."&_t=".md5($aREC_TB_TELLER['KD_TELLER'])."'>".$aREC_TB_TELLER['USER_NAME']."</a></span></td>";
																echo '<td align="right">'.number_format($aREC_TB_TELLER['MAX_TRANS']).'</td>';
																echo '<td>'.$aREC_TB_TELLER['JAB_TELLER'].'</td>';
																echo '<td>'.$aREC_TB_TELLER['NOTE_TELLER'].'</td>';
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
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<div class="modal" id="help_kop_tb_teller" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">

							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>

						</div>
						<div class="modal-footer">
							<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						</div>
					</div>
				</div>
			</div>
		</body>
	</html>

<?php
	break;

case md5('cr34t3'):
		$cHELP_BOX		= S_MSG('KT7A','Help Tambah Teller');
		$cHELP_1		= S_MSG('KT7B','Ini adalah modul untuk memasukkan data Teller yang baru.');
		$cHELP_2		= S_MSG('KT7C','Data yang dimasukkan adalah : ');
		$cHELP_3		= S_MSG('KT7D','Kode 		: Untuk memasukkan kode Teller yang baru, yang belum pernah ada sebelumnya.');
		$cHELP_4		= S_MSG('KT7E','Kode User 	: Kode user untuk masing-masing Teller, yang diambil dari tabel user, karena setiap Teller harus sudah menjadi user sistem ini.');
		$cHELP_5		= S_MSG('KT7F','Nama Teller	: Menampilkan nama Teller yang sudah terdapat pada nama user.');
?>
	<!DOCTYPE html>
	<html class=" ">
			<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper">
							<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
					</div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">

								<div class="pull-left">
									<h1 class="title"><?php echo $cADD?></h1>
								</div>
								<div class="pull-right">									 
									<ol class="breadcrumb">
										<li>
											<a href="#help_add_kop_tb_teller" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
										</li>
									</ol>
								</div>		
							</div>
						</div>
						<div class="clearfix"></div>

						<section class="box ">
							<div class="pull-right hidden-xs"></div>
							<div class="content-body">
								<div class="row">
									<form action ="?_a=tambah" method="post">
										<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
											<input type="text" class="col-sm-2 form-label-900" name='ADD_KD_TELLER' ><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cNAMA_TBL?></label>
											<select name="ADD_KD_USR_TLR" class="col-sm-4 form-label-900">
											<?php 
												$qQUERY=SYS_QUERY("select * from ".$database1.".tb_user where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($aREC_TAB_USER=SYS_FETCH($qQUERY)){
													echo "<option value='$aREC_TAB_USER[USER_CODE]'  >$aREC_TAB_USER[USER_NAME]</option>";
												}
											?>
											</select><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-5"><?php echo $cJAB_TELR?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_JAB_TELLER'>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cNOTE_TELR?></label>
											<input type="text" class="col-sm-6 form-label-900" name='ADD_NOTE_TELLER' id="field-2">
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-21"><?php echo $cACCT_KAS?></label>
											<select name="ADD_KAS_ACCOUNT" class="col-sm-6 form-label-900">
												<?php 
													echo "<option value=' '  > </option>";
													$qQUERY=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
														echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>";
													}
												?>
											</select>
											<div class="clearfix"></div><br>

											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
												<input type="button" class="btn" value=<?php echo S_MSG('F302','Cancel')?> onclick=self.history.back()>
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

			<div class="modal" id="help_add_kop_tb_teller" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_3?></p>	<p><?php echo $cHELP_4?></p>	<p><?php echo $cHELP_5?></p>
						</div>
						<div class="modal-footer">
							<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						</div>
					</div>
				</div>
			</div>
		</body>
	</html>

<?php
	SYS_DB_CLOSE($DB2);	break;

case md5('upd_telr'):
	$cQUERY="SELECT tb_teller.*, ".$database1.".tb_user.* FROM tb_teller 
		LEFT JOIN ".$database1.".tb_user ON tb_teller.KD_USR_TLR=".$database1.".tb_user.USER_CODE 
		where md5(tb_teller.KD_TELLER)='$_GET[_t]' and tb_teller.APP_CODE='$cFILTER_CODE' and tb_teller.DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);
	if(SYS_ROWS($qQUERY)==0){
		header('location:kop_tb_teller.php');
	}
	$REC_TB_TELLER=SYS_FETCH($qQUERY);
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
			<div class="page-container row-fluid">
				
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper">
						<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
					</div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>

						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class="page-title">

								<div class="pull-left">
									  <h1 class="title"><?php echo S_MSG('KK50','Edit Jenis Simpanan')?></h1>
								</div>
								<div class="pull-right">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=<?php echo md5('del_teler')?>&id=<?php echo md5($REC_TB_TELLER['KD_TELLER'])?>" onClick="return confirm('<?php echo $cMSG_DEL?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<section class="box ">
							<div class="pull-right hidden-xs"></div>
							<div class="content-body">
								<div class="row">
									<form action ="?_a=rubah&id=<?php echo md5($REC_TB_TELLER['KD_TELLER'])?>" method="post"  onSubmit="return CEK_KOP_TB_SIM(this)">
										<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
											<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
											<input type="text" class="col-sm-2 form-label-900" name='EDIT_KD_TELLER' id="field-1" value=<?php echo $REC_TB_TELLER['KD_TELLER']?> disabled="disabled"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cNAMA_TBL?></label>
											<select name="UPD_KD_USR_TLR" class="col-sm-4 form-label-900">
											<?php 
												$qQUERY=SYS_QUERY("select * from ".$database1.".tb_user where APP_CODE='$cFILTER_CODE' and DELETOR=''");
												while($aREC_TAB_USER=SYS_FETCH($qQUERY)){
													if($REC_TB_TELLER['KD_USR_TLR'] == $aREC_TAB_USER['USER_CODE']){
														echo "<option value='$aREC_TAB_USER[USER_CODE]' selected='$REC_TB_TELLER[KAS_ACCOUNT]' >$aREC_TAB_USER[USER_NAME]</option>";
													} else {
														echo "<option value='$aREC_TAB_USER[USER_CODE]'  >$aREC_TAB_USER[USER_NAME]</option>"; }
												}
											?>
											</select><br><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-5"><?php echo $cJAB_TELR?></label>
											<input type="text" class="col-sm-4 form-label-900" name='EDIT_JAB_TELLER' id="field-5" value="<?php echo $REC_TB_TELLER['JAB_TELLER']?>"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cNOTE_TELR?></label>
											<input type="text" class="col-sm-4 form-label-900" name='EDIT_NOTE_TELLER' id="field-2" value="<?php echo $REC_TB_TELLER['NOTE_TELLER']?>"><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-21"><?php echo $cACCT_KAS?></label>
											<select name="EDIT_KAS_ACCOUNT" class="col-sm-6 form-label-900">
												<?php 
													echo "<option value=' '  > </option>";
													$qQUERY=SYS_QUERY("select * from account where GENERAL='D' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
														if($REC_TB_TELLER['KAS_ACCOUNT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
															echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$REC_TB_TELLER[KAS_ACCOUNT]' >$aREC_ACCOUNT[ACCT_NAME]</option>";
														} else {
														echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >$aREC_ACCOUNT[ACCT_NAME]</option>"; }
													}
												?>
											</select><br><br>
											<div class="clearfix"></div>
											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
												<input type="button" class="btn" value=<?php echo S_MSG('F302','Cancel')?> onclick=self.history.back()>
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
	$cCODE	= encode_string($_POST['ADD_KD_TELLER']);
	if($cCODE=='') {
		$cMSG_BLANK	= S_MSG('KT23','Kode Teller tidak boleh kosong');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;

	}
	$cQUERY="select * from tb_teller where APP_CODE='$cFILTER_CODE' and KD_TELLER='$cCODE' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$cKD_USR_TLR	= encode_string($_POST['ADD_KD_USR_TLR']);
		$cJAB_TELLER	= encode_string($_POST['ADD_JAB_TELLER']);
		$cNOTE_TELLER	= encode_string($_POST['ADD_NOTE_TELLER']);
		$cQUERY ="insert into tb_teller set APP_CODE='$cFILTER_CODE', KD_TELLER='$cCODE', 
			KD_USR_TLR='$cKD_USR_TLR', JAB_TELLER='$cJAB_TELLER', NOTE_TELLER='$cNOTE_TELLER', 
			KAS_ACCOUNT='$_POST[ADD_KAS_ACCOUNT]', ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);
		header('location:kop_tb_teller.php');
	} else {
		$cMSG_EXIST	= S_MSG('KT22','Kode Teller sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
	}
	break;

case 'rubah':
	$KODE_CRUD	= $_GET['id'];
	$NOW 		= date("Y-m-d H:i:s");
	$cJAB_TELLER	= encode_string($_POST['EDIT_JAB_TELLER']);
	$cNOTE_TELLER	= encode_string($_POST['EDIT_NOTE_TELLER']);
	$cQUERY ="update tb_teller set KD_USR_TLR='$_POST[UPD_KD_USR_TLR]', 
		JAB_TELLER='$cJAB_TELLER', NOTE_TELLER='$cNOTE_TELLER', KAS_ACCOUNT='$_POST[EDIT_KAS_ACCOUNT]', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and md5(KD_TELLER)='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:kop_tb_teller.php');
	break;

case md5('del_teler'):
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cQUERY ="update tb_teller set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(KD_TELLER)='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:kop_tb_teller.php');
}
?>


