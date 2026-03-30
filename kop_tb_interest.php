<?php
//	kop_tb_interest.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('KI51','Tabel Perhitungan Bunga');
	$cKD_BUNG= S_MSG('KI52','Kode Bunga');
	$cJN_BUNG= S_MSG('KI53','Jenis Bunga');
	$cPERHIT = S_MSG('KI54','Perhitungan');
	$cADD_TB = S_MSG('KI55','Tambah Kode Bunga');
	$cNOTE_TB = S_MSG('KI56','Keterangan');
	$cED_TB_BUNGA = S_MSG('KI57','Edit Tabel Bunga');
	$aBUNGA_TYPE = array(1=> 'Bunga Flat', 'Bunga Menurun', 'Efektif / Sliding Rate', 'Anuitas');
	$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');
//	$cMSG_INTRS1 = S_MSG('KI58','Anda belum mengisikan Kode Bunga');
//	$cMSG_INTRS2 = S_MSG('KI59','Anda belum mengisikan Nama Bunga');

	$qQUERY=SYS_QUERY("select * from tb_interest where APP_CODE='$cFILTER_CODE' and DELETOR=''");

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
	$NOW = date("Y-m-d H:i:s");

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX		= S_MSG('KI71','Help Tabel Perhitungan Bunga');
		$cHELP_1		= S_MSG('KI72','Ini adalah modul untuk memasukkan data daftar Perhitungan Bunga, baik Simpanan maupun Pinjaman/Kredit');
		$cHELP_2		= S_MSG('KI73','Untuk memasukkan data Perhitungan Bunga baru, klik tambah Perhitungan / add new');
		$cHELP_3		= S_MSG('KI74','Sekarang ini ditampilkan daftar Perhitungan Bunga yang telah pernah dimasukkan');
		$cHELP_4		= S_MSG('KI75','Untuk merubah salah satu data Perhitungan Bunga, klik di Kode atau Jenis Bunga dan akan masuk ke mode update');

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
												<a href="?_a=<?php echo md5('ADD_INTEREST')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_kop_tb_interest" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="<?php echo S_PARA('_DISP_REPORT_CLASS','display table table-hover table-condensed')?>">
												<thead>
													<tr>
														<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKD_BUNG?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cJN_BUNG?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cPERHIT?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNOTE_TB?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_tb_interest=SYS_FETCH($qQUERY)) {
														echo '<tr>';
//															echo '<td style="width: 1px;"></td>';
															echo '<td class=""><div class="star"><i class="fa fa-star-o icon-xs icon-orange"></i></div></td>';
															echo "<td><span><a href='?_a=".md5('UPD_INTEREST')."&_b=".md5($aREC_tb_interest['KD_INTR'])."'>".$aREC_tb_interest['KD_INTR']."</a></span></td>";
															echo "<td><a href='?_a=".md5('UPD_INTEREST')."&_b=".md5($aREC_tb_interest['KD_INTR'])."'>".$aREC_tb_interest['DESC_INTRS']."</a></td>";
															echo "<td>".$aBUNGA_TYPE[$aREC_tb_interest['TYPE_INTRS']]."</td>";
															echo "<td>".$aREC_tb_interest['DESC_INTRS']."</td>";
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
			<div class="modal" id="help_kop_tb_interest" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_3?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_4?></p>
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

case md5('ADD_INTEREST'):
	$cHELP_BOX		= S_MSG('KI7A','Help tambah Tabel Perhitungan Bunga');
	$cHELP_1		= S_MSG('KI7B','Ini adalah modul untuk memasukkan data daftar Perhitungan Bunga baru, yang belum pernah ada sebelumnya.');
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
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
								<h2 class="title"><?php echo $cADD_TB?></h2>                            
							</div>
							<div class="pull-right hidden-xs">
								<ol class="breadcrumb">
									<li>
										<a href="#help_add_kop_tb_interest" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
									</li>
								</ol>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
						<section class="box ">
							<div class="content-body">
								<div class="row">
									<form action ="?_a=tambah" method="post"  onSubmit="return CEK_tb_interest(this)">
										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_BUNG?></label>
										<input type="text" class="col-sm-2 form-label-900" name='ADD_KD_INTR' id="field-1">
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cJN_BUNG?></label>
										<input type="text" class="col-sm-6 form-label-900" name='ADD_DESC_INTRS' id="field-2">
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cPERHIT?></label>
										<select name='ADD_TYPE_INTRS' class="col-sm-5 form-label-900">
											<?php 
												echo "<option value=' '  > </option>";
												for ($I=1; $I<=4; $I++){
												  echo "<option class='form-label-900' value=$I>$aBUNGA_TYPE[$I]</option>";
												}
											?>
										</select>
										<div class="clearfix"></div><br>

										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNOTE_TB?></label>
										<input type="text" class="col-sm-6 form-label-900" name='ADD_IDENT_DESC' id="field-2">
										<div class="clearfix"></div>

										<div class="text-left">
											<input type="submit" class="btn btn-primary" value=<?php echo S_MSG('F301','Save')?>>
											<input type="button" class="btn" value=<?php echo S_MSG('F302','Close')?> onclick=self.history.back()>
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
		<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
		<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
		<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 
			<div class="modal" id="help_add_kop_tb_interest" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">
							<p><?php echo $cHELP_1?></p>
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

case md5('UPD_INTEREST'):
		$cQUERY ="select * from tb_interest where APP_CODE='$cFILTER_CODE' and md5(KD_INTR)='$_GET[_b]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$REC_tb_interest=SYS_FETCH($qQUERY);
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
									  <h1 class="title"><?php echo $cED_TB_BUNGA?></h1>
								</div>
								<div class="pull-right hidden-xs">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=<?php echo md5('DEL_INTREREST')?>&_i=<?php echo md5($REC_tb_interest['KD_INTR'])?>" onClick="return confirm('<?php echo $cMSG_DEL?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs"></div>
								<div class="content-body">
									<div class="row">
										<form action ="?_a=rubah&id=<?php echo $REC_tb_interest['REC_NO']?>" method="post"  onSubmit="return CEK_tb_interest(this)">
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKD_BUNG?></label>
											<input type="text" class="col-sm-2 form-label-900" name='EDIT_KD_INTR' id="field-1" value=<?php echo $REC_tb_interest['KD_INTR']?> disabled="disabled">
											<div class="clearfix"></div>
											
											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cJN_BUNG?></label>
											<select name='UPD_TYPE_INTRS' class="col-sm-4 form-label-900">
												<?php 
													echo "<option value=' '  > </option>";
													for ($I=1; $I<=4; $I++){
														if($I==$REC_tb_interest['TYPE_INTRS']){
															echo "<option value='$REC_tb_interest[ACCT_PIN]' selected='$REC_tb_interest[TYPE_INTRS]' >$aBUNGA_TYPE[$I]</option>";
														} else
														echo "<option class='form-label-900' value=$I>$aBUNGA_TYPE[$I]</option>";
													}
												?>
											</select>
											<div class="clearfix"></div>

											<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNOTE_TB?></label>
											<input type="text" class="col-sm-6 form-label-900" name='EDIT_DESC_INTRS' id="field-2" value="<?php echo $REC_tb_interest['DESC_INTRS']?>">
											<div class="clearfix"></div><br>

											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo S_MSG('F301','Save')?>>
												<input type="button" class="btn" value=<?php echo S_MSG('F302','Cancel')?> onclick=self.history.back()>
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
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script src="sys_js.js" type="text/javascript"></script> 

			</body>
		</html>
<?php
	break;

case 'tambah':
	if($_POST['ADD_KD_INTR']==''){
		echo "<tr> <td colspan='2'>**Kode Bunga masih kosong**</td> </tr>";
		header('location:kop_tb_interest.php');
		return;
	}
	$cQUERY="select * from tb_interest where APP_CODE='$cFILTER_CODE' and DELETOR='' and KD_INTR='$_POST[ADD_KD_INTR]'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		header('location:kop_tb_interest.php');
	} else {
		$cQUERY="insert into tb_interest set KD_INTR='$_POST[ADD_KD_INTR]', DESC_INTRS='$_POST[ADD_DESC_INTRS]', 
			TYPE_INTRS=$_POST[ADD_TYPE_INTRS], 
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:kop_tb_interest.php');
	}
	break;

case 'rubah':
	$cQUERY="update tb_interest set DESC_INTRS='$_POST[EDIT_DESC_INTRS]', TYPE_INTRS='$_POST[UPD_TYPE_INTRS]', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='".date("Y-m-d H:i:s")."'
		where REC_NO=$_GET[id]";
	SYS_QUERY($cQUERY);
	header('location:kop_tb_interest.php');
	break;

case md5('DEL_INTREREST'):
	$KODE_CRUD=$_GET['_i'];
	$cQUERY ="update tb_interest set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(KD_INTR)='$KODE_CRUD'";

	$qQUERY =SYS_QUERY($cQUERY);
	header('location:kop_tb_interest.php');
	break;

}
?>

