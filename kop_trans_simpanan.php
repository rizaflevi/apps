<?php
//	kop_trans_simpanan.php
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cQUERY="SELECT tr_save1.SAVE_ACT, tr_save1.SAVE_DATE, tr_save1.SAVE_CODE, tr_save1.KD_MMBR, tr_save1.SV_BALANCE, tr_save1.APP_CODE, tr_save1.DELETOR, tb_member1.KD_MMBR, tb_member1.NM_DEPAN, tb_member1.ALAMAT, ";
	$cQUERY.=" tab_simp.KODE_SIMPN, tab_simp.NAMA_SIMPN FROM tr_save1 ";
	$cQUERY.=" left join tb_member1 ON tr_save1.KD_MMBR=tb_member1.KD_MMBR ";
	$cQUERY.= "left join tab_simp ON tr_save1.SAVE_CODE=tab_simp.KODE_SIMPN ";
	$cQUERY.=" where tr_save1.APP_CODE='$cFILTER_CODE' and tr_save1.DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);
	
	$cJNS_SIMP 	= S_MSG('KK21','Jenis Simpanan');
	$cHEADER	= S_MSG('KB01','Rekening Simpanan');
	$cNOREK		= S_MSG('KK20','Nomor Rekening');
	$cTANGGAL	= S_MSG('KB12','Tanggal');
	$cKODE_ANG	= S_MSG('CB07','Kode Anggota');
	$cNAMA		= S_MSG('F004','Nama');
	$cALAMAT	= S_MSG('F005','Alamat');
	$cSALDO		= S_MSG('KB25','Saldo');
	$cSAVENOTE	= S_MSG('KI56','Keterangan');

	$cSAVE		= S_MSG('F301','Save');
	$cMSG_MSH	= S_MSG('KB77','Rekening simpanan ini masih mempunyai transaksi, tidak dapat di hapus ?');
	$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');

	$cTTIP_REK	= S_MSG('KK31','Nomor rekening simpanan yang tidak sama untuk setiap anggota');
	$cTTIP_ANG	= S_MSG('KK45','Nomor anggota yang membuka rekening simpanan');
	$cTTIP_TGL	= S_MSG('KB31','Tanggal pembukaan rekening simpanan');
	$cTTIP_PRD	= S_MSG('KK33','Nama produk simpanan yang akan dibuka');

	function SEEK_NAMA($KD_MEMBER){
		$fQUERY="select * from tb_member1 where APP_CODE='$cFILTER_CODE' and DELETOR='' and KD_MMBR='$KD_MEMBER'";
	}

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
switch($cACTION){
	default:
		$cHELP_BOX		= S_MSG('KB81','Help Rekening Simpanan');
		$cHELP_1		= S_MSG('KB82','Ini adalah modul untuk memasukkan data daftar Nomor Rekening Simpanan/Tabungan');
		$cHELP_2		= S_MSG('KB83','Untuk memasukkan data Pembayaran baru, klik tambah Simpanan / add new');
		$cHELP_3		= S_MSG('KB84','Sekarang ini ditampilkan daftar Nomor rekening simpanan / tabungan yang telah pernah dimasukkan');
		$cHELP_4		= S_MSG('KB85','Untuk merubah salah satu data Pembayaran, klik di nomor Rekening dan akan masuk ke mode update');
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
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>
												 <a href="?_a=<?php echo md5('create_simp')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_kop_trans_simpanan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">    
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">

											<table id="example" class="display table table-hover table-condensed" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th style="background-color:LightGray;width: 1px;"></th>
														<th style="background-color:LightGray;"><?php echo $cNOREK?></th>
														<th style="background-color:LightGray;"><?php echo $cJNS_SIMP?></th>
														<th style="background-color:LightGray;"><?php echo $cTANGGAL?></th>
														<th style="background-color:LightGray;"><?php echo $cKODE_ANG?></th>
														<th style="background-color:LightGray;"><?php echo $cNAMA?></th>
														<th style="background-color:LightGray;"><?php echo $cALAMAT?></th>
														<th style="background-color:LightGray;"><?php echo $cSALDO?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_TR_SAVE1=SYS_FETCH($qQUERY)) {
															echo '<tr>';
	//															echo '<td style="width: 1px;"></td>';
															echo '<td class=""><div class="star"><i class="fa fa-money icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?_a=".md5('upd_simp')."&_s=".md5($aREC_TR_SAVE1['SAVE_ACT'])."'>".$aREC_TR_SAVE1['SAVE_ACT']."</a></span></td>";
															echo '<td>'.$aREC_TR_SAVE1['NAMA_SIMPN'].'</td>';
															echo '<td>'.date("d-m-Y", strtotime($aREC_TR_SAVE1['SAVE_DATE'])).'</td>';
															echo '<td>'.$aREC_TR_SAVE1['KD_MMBR'].'</td>';
															echo '<td>'.$aREC_TR_SAVE1['NM_DEPAN'].'</td>';
															echo '<td>'.$aREC_TR_SAVE1['ALAMAT'].'</td>';
															echo '<td align="right">'.number_format($aREC_TR_SAVE1['SV_BALANCE']).'</td>';
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
			<div class="modal" id="help_kop_trans_simpanan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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

case md5('create_simp'):
	$q_TAB_SIMP=SYS_QUERY("select * from tab_simp where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$aREC_1=SYS_FETCH($q_TAB_SIMP);
	$v_JNS_SIMP = $aREC_1['KODE_SIMPN'];

	$cQ_LAST="select SAVE_ACT from tr_save1 where APP_CODE='$cFILTER_CODE' and DELETOR='' order by SAVE_ACT desc limit 1";
	$qQ_LAST	= SYS_QUERY($cQ_LAST);
	$aREC_TR_SAVE1= SYS_FETCH($qQ_LAST);
	$cLAST_NOM	= $aREC_TR_SAVE1['SAVE_ACT'];
	$nLAST_NOM	= intval($cLAST_NOM)+1;
	$cLAST_NOM	= str_pad((string)$nLAST_NOM, 10, '0', STR_PAD_LEFT);

	$cHEADER	= S_MSG('KB76','Tambah Rekening Simpanan');
	$cHELP_BOX	= S_MSG('KB91','Help Tambah Rekening Simpanan');
	$cHELP_1	= S_MSG('KB92','Ini adalah modul untuk memasukkan data Nomor Rekening Simpanan/Tabungan baru');
	$cHELP_2	= S_MSG('KB93','Data yang dimasukkan adalah sebagai berikut :');
	$cHELP_3	= S_MSG('KB94','Nomor Rekening : Nomor rekening Simpanan / tabungan yang baru, default nomor terakhir di tambah 1');
	$cHELP_4	= S_MSG('KB95','Tanggal : tanggal pembukaan rekening, default tanggal hari ini');
?>
	<!DOCTYPE html>
	<html class=" ">
		<body>  
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
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box">
								<div class="pull-right hidden-xs"></div>

								<header class="panel_header">
									<h2 class="title pull-left"><?php echo $cHEADER?></h2>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>
												<a href="#help_add_kop_trans_simpanan" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
											</li>
										</ol>
									</div>
								</header>
								<div class="content-body">
									<div class="row">
										<form name="ADD_TRANS_SIMPANAN" action ="?_a=tambah" method="post">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNOREK?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_SAVE_ACT' id="field-1" value="<?php echo $cLAST_NOM?>" title="<?php echo $cTTIP_REK ?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-2 form-label-900" data-mask="date" name='ADD_SAVE_DATE' id="field-2" value="<?php echo date('d/m/Y')?>" title="<?php echo $cTTIP_TGL ?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cJNS_SIMP?></label>
												<select name="ADD_SAVE_CODE" id="ADD_SAVE_CODE" class="col-sm-4 form-label-900" value="<?php echo $v_JNS_SIMP?>" title="<?php echo $cTTIP_PRD ?>">
												<?php 
													while($aREC_TAB_SIMP=SYS_FETCH($q_TAB_SIMP)){
														echo "<option value='$aREC_TAB_SIMP[KODE_SIMPN]'  >$aREC_TAB_SIMP[NAMA_SIMPN]</option>";
													}
												?>
												</select>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cKODE_ANG?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_KD_MMBR' id="ADD_KD_MMBR" onblur="Disp_Member(this.value)" title="<?php echo $cTTIP_ANG ?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNAMA.", ".$cALAMAT?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_NM_MMBR' id="f_NM_MMBR" disabled="disabled">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-5"><?php echo $cSAVENOTE?></label>
												<input type="text" class="col-sm-6 form-label-900" name="ADD_SAVE_NOTE" id="f_SAVE_NOTE">
												<div class="clearfix"></div><br>

												<div class="text-left">
													<input type="submit" id="SAVE_ADD" class="btn btn-primary" value=<?php echo $cSAVE?> disabled="disabled">
													<input type="button" class="btn" value=<?php echo S_MSG('F302','Close')?> onclick=self.history.back()>
												</div>
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

			<div class="modal" id="help_add_kop_trans_simpanan" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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

case md5('upd_simp'):
	$cQUERY = "select SAVE_CODE, SAVE_DATE, SAVE_ACT, SAVE_NOTE, tr_save1.KD_MMBR, ";
	$cQUERY.= "tb_member1.KD_MMBR, tb_member1.NM_DEPAN, tb_member1.ALAMAT  from tr_save1 ";
	$cQUERY.= "left join tb_member1 ON tr_save1.KD_MMBR=tb_member1.KD_MMBR ";
	$cQUERY.= " where md5(tr_save1.SAVE_ACT)='".$_GET['_s'];
	$cQUERY.= "' and tr_save1.APP_CODE='".$cFILTER_CODE."' and tr_save1.DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);
	if(SYS_ROWS($qQUERY)==0){
		header('location:kop_trans_simpanan.php');
	}
	$REC_TR_SAVE1=SYS_FETCH($qQUERY);
?>
	<!DOCTYPE html>
	<html class=" ">
		<body onload="firstfocus();">  
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
									  <h1 class="title"><?php echo $cHEADER?></h1>
								</div>
								<div class="pull-right">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=delete&id=<?php echo $REC_TR_SAVE1['SAVE_ACT']?>" onClick="return confirm('<?php echo $cMSG_DEL?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs"></div>
			<!-- 
								<header class="panel_header">
									  <h2 class="title pull-left">Basic Info</h2>
									  <div class="actions panel_actions pull-right">
											<i class="box_toggle fa fa-chevron-down"></i>
											<i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
											<i class="box_close fa fa-times"></i>
									  </div>
								</header>	-->
								<div class="content-body">
									<div class="row">
										<form name="trans_simpanan" action ="?_a=rubah&id=<?php echo $REC_TR_SAVE1['SAVE_ACT']?>" method="post"  onSubmit="return CEK_KOP_TB_SIM(this)">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNOREK?></label>
												<input type="text" class="col-sm-3 form-label-900" name='NO_REKP' id="field-1" value=<?php echo $REC_TR_SAVE1['SAVE_ACT']?> disabled="disabled">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-2 form-label-900" data-mask="date" name='UPD_SAVE_DATE' id="field-2" value="<?php echo date("d-m-Y", strtotime($REC_TR_SAVE1['SAVE_DATE']))?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cJNS_SIMP?></label>
												<select name="EDIT_SAVE_CODE" class="col-sm-4 form-label-900">
												<?php 
													$qQUERY=SYS_QUERY("select * from tab_simp where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($aREC_TAB_SIMP=SYS_FETCH($qQUERY)){
														if($REC_TR_SAVE1['SAVE_CODE'] == $aREC_TAB_SIMP['KODE_SIMPN']){
															echo "<option value='$aREC_TAB_SIMP[KODE_SIMPN]' selected='$REC_TR_SAVE1[SAVE_CODE]' >$aREC_TAB_SIMP[NAMA_SIMPN]</option>";
														} else {
															echo "<option value='$aREC_TAB_SIMP[KODE_SIMPN]'  >$aREC_TAB_SIMP[NAMA_SIMPN]</option>";
														}
													}
												?>
												</select>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cKODE_ANG?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_KD_MMBR' id="ADD_KD_MMBR" onblur="Disp_Member(this.value)" value=<?php echo $REC_TR_SAVE1['KD_MMBR']?>>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNAMA?></label>
												<input type="text" class="col-sm-6 form-label-900" id="f_NM_MMBR" value="<?php echo $REC_TR_SAVE1['NM_DEPAN'].', '.$REC_TR_SAVE1['ALAMAT']?>" disabled="disabled">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-5"><?php echo $cSAVENOTE?></label>
												<input type="text" class="col-sm-8 form-label-900" name="UPD_SAVE_NOTE" id="f_SAVE_NOTE" value="<?php echo $REC_TR_SAVE1['SAVE_NOTE']?>">
												<div class="clearfix"></div><br>

												<div class="text-left">
													<input type="submit" id="SAVE_ADD" class="btn btn-primary" value=<?php echo $cSAVE?>>
													<input type="button" class="btn" value=<?php echo S_MSG('F302','Close')?> onclick=self.history.back()>
												</div>
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
	if($_POST['ADD_SAVE_ACT']=='') {
		$cMSG = S_MSG('KB02','Nomor Rekening Simpanan tidak boleh kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	$cQUERY="select * from tr_save1 where LOAN_ACT='$_POST[ADD_LOAN_ACT]' APP_CODE='$cFILTER_CODE' and and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$dTG_TRSAVE = $_POST['ADD_SAVE_DATE'];		// 'dd/mm/yyyy'
		$cDATE = substr($dTG_TRSAVE,6,4). '-'. substr($dTG_TRSAVE,3,2). '-'. substr($dTG_TRSAVE,0,2);
		$cQUERY ="insert into tr_save1 set SAVE_ACT='$_POST[ADD_SAVE_ACT]', SAVE_CODE='$_POST[ADD_SAVE_CODE]'";
		$cQUERY.=", KD_MMBR='$_POST[ADD_KD_MMBR]', SAVE_NOTE='$_POST[ADD_SAVE_NOTE]'";
		$cQUERY.=", SAVE_DATE='$cDATE'";
		$cQUERY.=", APP_CODE='$cFILTER_CODE', ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);
		header('location:kop_trans_simpanan.php');
	} else {
		$cMSG = S_MSG('KB03','Nomor Rekening Simpanan sudah ada');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	break;

case 'rubah':

	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$dTG_TRSAVE = $_POST['UPD_SAVE_DATE'];		// 'dd/mm/yyyy'
	$cDATE = substr($dTG_TRSAVE,6,4). '-'. substr($dTG_TRSAVE,3,2). '-'. substr($dTG_TRSAVE,0,2);
	$cQUERY ="update tr_save1 set SAVE_CODE='$_POST[EDIT_SAVE_CODE]', KD_MMBR='$_POST[ADD_KD_MMBR]'";
	$cQUERY.=", SAVE_DATE='$cDATE', SAVE_NOTE='$_POST[UPD_SAVE_NOTE]'";
	$cQUERY.=", UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and SAVE_ACT='$KODE_CRUD'";
	SYS_QUERY($cQUERY);	// or die ('Error in query.' .mysql_error().' ==> '.$cQUERY);
	header('location:kop_trans_simpanan.php');
	break;

case 'delete':

	$SAVE_ACT=$_GET['id'];
	$q_TRM_TAB1=SYS_QUERY("SELECT SAVE_ACT from trm_tab1 where SAVE_ACT='$SAVE_ACT' and APP_CODE='$cFILTER_CODE' and DELETOR='' ");
//		 or die ('Error in query.' .mysql_error().'==> '.$q_TRM_TAB1);
	if (SYS_ROWS($q_TRM_TAB1)==0) {
		$NOW = date("Y-m-d H:i:s");
		$cQUERY ="update tr_save1 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW'";
		$cQUERY.=" where APP_CODE='$cFILTER_CODE' and SAVE_ACT='$SAVE_ACT'";
		SYS_QUERY($cQUERY);
		header('location:kop_trans_simpanan.php');
	} else {
		echo "<script> alert('$cMSG_MSH');	window.history.back();	</script>";
		return;
	}
}
?>

<script>
function Disp_Member(pkode_member) {
	var btn_stat = document.getElementById("SAVE_ADD");  // the submit button
    if (pkode_member == "") {
        document.getElementById("ADD_KD_MMBR").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("f_NM_MMBR").innerHTML = xmlhttp.responseText;
//				alert(xmlhttp.responseText);
				document.getElementById("f_NM_MMBR").value = xmlhttp.responseText;
            }
			if (document.getElementById("f_NM_MMBR").value == "") {
				document.getElementById("SAVE_ADD").setAttribute('disabled', 'disabled');
			} else {
				document.getElementById("SAVE_ADD").removeAttribute('disabled');
			}
        };
//		alert(btn_stat);
        xmlhttp.open("GET","kop_cek_member.php?ADD_KD_MMBR="+pkode_member,true);
        xmlhttp.send();
		
    }
}

function Disp_Alamat() {
   var x = document.getElementById("SelectMember").value;
//   document.getElementById("f_ALAMAT").value = x;
}

// After form loads focus will go to Address field.  
function firstfocus()  
{  
	var uid = document.trans_simpanan.ADD_KD_MMBR.focus();  
	return true;  
}  

</script>

 
