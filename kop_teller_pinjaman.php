<?php
// kop_teller_pinjaman.php
// Memasukkan angsuran pinjaman di kasir

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$mDATE1 = date('Y-m-01');
	$mDATE2 = date('Y-m-d');
//	die ($mDATE1);

	$cQUERY="SELECT trm_loan1.*, tr_loan1.*, tb_member1.*, ";
	$cQUERY.=" tab_pinj.KODE_PINJM, tab_pinj.NAMA_PINJM FROM trm_loan1 ";
	$cQUERY.=" left join tr_loan1 ON trm_loan1.TRM_NO_REK=tr_loan1.LOAN_ACT ";
	$cQUERY.=" left join tb_member1 ON tr_loan1.NO_MEMBER=tb_member1.KD_MMBR ";
	$cQUERY.=" left join tab_pinj ON tab_pinj.KODE_PINJM=tr_loan1.LOAN_CODE ";
	$cQUERY.=" where trm_loan1.APP_CODE='$cFILTER_CODE' and trm_loan1.DELETOR=''";
//	exit();
	$qQUERY		= SYS_QUERY($cQUERY);
	$cHEADER 	= S_MSG('KC51','Daftar Angsuran Pinjaman');
	$cNO_REK 	= S_MSG('KK20','Nomor Rekening');
	$cKD_ANG = S_MSG('CB07','Kode Anggota');
	$cNM_ANG = S_MSG('F004','Nama');
	$cAL_ANG = S_MSG('F005','Alamat');
	$cNOMINAL = S_MSG('KB61','Nominal');
	$cCATATAN = S_MSG('KB64','Catatan');
	$c_POKOK = S_MSG('KB66','Angsuran Pokok');
	$c_BUNGA = S_MSG('KB68','Nilai Bunga');
	$cTG_PIN = S_MSG('KB12','Tanggal');
	$cJN_PIN = S_MSG('KA20','Jenis Pinjaman');
	$cADD_PIN = S_MSG('KC46','Tambah Setoran Pinjaman');
	$cVOUCHER =	S_MSG('KB14','Nomor Voucher');
	$nJML = 0;

	$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');

	$cSAVE	= S_MSG('F301','Save');
	$cCLOSE	= S_MSG('F302','Close');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

	$cHDR_BACK_CLR = S_PARA('_DISP_TABLE_CLASS','background-color:LightGray');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('KC81','Help Transaksi Pinjaman');

?>

	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<body class=" ">
			<?php	require_once("scr_topbar.php");?>
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
									<h2 class="title pull-left"><?php echo $cHEADER?></h2><br>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>
												 <a href="?_a=<?php echo md5('ADD_NEW')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_kop_teller_pinjaman" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNO_REK?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cJN_PIN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cVOUCHER?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTG_PIN?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKD_ANG?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNM_ANG?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNOMINAL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cCATATAN?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_TRM_LOAN1=SYS_FETCH($qQUERY)) {
														echo '<tr>';
//															echo '<td style="width: 1px;"></td>';
															echo '<td class=""><div class="star"><i class="fa fa-cloud-upload icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?_a=update&NMR_RECORD=$aREC_TRM_LOAN1[REC_NO]'>".$aREC_TRM_LOAN1['TRM_NO_REK']."</a></span></td>";
															echo '<td>'.$aREC_TRM_LOAN1['NAMA_PINJM'].'</td>';
															echo '<td>'.$aREC_TRM_LOAN1['NO_VOUCHER'].'</td>';
															echo '<td>'.$aREC_TRM_LOAN1['TRM_DATE'].'</td>';
															echo '<td>'.$aREC_TRM_LOAN1['KD_MMBR'].'</td>';
															echo '<td>'.decode_string($aREC_TRM_LOAN1['NM_DEPAN']).'</td>';
															echo '<td align="right">'.number_format($aREC_TRM_LOAN1['TRM_VALUE']).'</td>';
															echo '<td>'.decode_string($aREC_TRM_LOAN1['TRM_NOTE']).'</td>';
														echo '</tr>';
														$nJML += $aREC_TRM_LOAN1['TRM_VALUE'];
														}
													?>
												</tbody>
											</table>
											<div class="col-md-12 invoice-infoblock text-right">
												 <h3 class="text-muted">Total : <?php echo $nJML?></h3> 
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
			<script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
			<script src="assets/js/scripts.js" type="text/javascript"></script> 

			<div class="modal" id="help_kop_teller_pinjaman" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
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
		</body>
	</html>

<?php
	break;

case md5('ADD_NEW'):
?>
	<!DOCTYPE html>
	<html class=" ">
		<body onload="Kop_Teller_Pinjaman_focus();">  
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
									  <h2 class="title"><?php echo $cADD_PIN?></h2>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
							<section class="box ">
								<div class="pull-right hidden-xs"></div>
								<div class="content-body">
									<div class="row">
										<form name="Add_New_record" action ="?_a=tambah" method="post">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNO_REK?></label>
												<input type="text" class="col-sm-2 form-label-900" name='ADD_LOAN_ACT' id="ADD_LOAN_ACT" onblur="Disp_Rek_Pin(this.value)">
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-3"><?php echo $cNM_ANG?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_NM_MMBR' id="NAMA_ANGGOTA" disabled="disabled">
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-5"><?php echo $cVOUCHER?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_NO_VOUCHER' id="f_VOUCHER">
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cTG_PIN?></label>
												<input type="text" class="col-sm-2 form-label-900" data-mask="date" name='ADD_DATE_TRANS' id="field-2" value="<?php echo date('d/m/Y')?>">
												<div class="clearfix"></div>

												<label class="col-sm-3 form-label-700" for="field-7"><?php echo $cNOMINAL?></label>
												<input type="text" class="col-sm-2 form-label-900" data-mask="fdecimal" name='ADD_TRM_VALUE' id="field-2">
												<div class="clearfix"></div>

												<div class="text-left">
													<input type="submit" id="SAVE_BUTTON" class="btn btn-primary" value=<?php echo $cSAVE?> disabled="disabled">
													<input type="button" class="btn" value=<?php echo $cCLOSE?> onclick=self.history.back()>
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

case "update":
	$cQUERY="SELECT trm_tab1.*, tr_save1.* , tb_member1.* FROM trm_tab1 ";
	$cQUERY.=" left join tr_save1 ON trm_tab1.SAVE_ACT=tr_save1.SAVE_ACT ";
	$cQUERY.= "left join tb_member1 ON tr_save1.KD_MMBR=tb_member1.KD_MMBR ";
	$cQUERY.=" where trm_tab1.REC_NO='".$_GET['NMR_RECORD']." and trm_tab1.APP_CODE='$cFILTER_CODE' and trm_tab1.DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);
	if(SYS_ROWS($qQUERY)==0){
		header('location:kop_trans_simpanan.php');
	}
	$aREC_TRM_LOAN1=SYS_FETCH($qQUERY);
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
									  <h2 class="title"><?php echo S_MSG('KS32','Edit Setoran Simpanan')?></h2>
								</div>
								<div class="pull-right">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=delete&id=<?php echo $aREC_TRM_LOAN1['REC_NO']?>" onClick="return confirm('<?php echo $cMSG_DEL?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
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
										<form action ="?_a=update&id=<?php echo $aREC_TRM_LOAN1['SAVE_ACT']?>" method="post"  onSubmit="return CEK_KOP_TB_SIM(this)">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">

												<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cNO_REK?></label>
												<input type="text" class="col-sm-2 form-label-900" name='NO_REKP' id="field-1" value=<?php echo $aREC_TRM_LOAN1['SAVE_ACT']?> disabled="disabled">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-2"><?php echo S_MSG('KK21','Jenis Simpanan')?></label>
												<select name="EDIT_SAVE_CODE" class="col-sm-4 form-label-900">
												<option value=""></option>
												<?php 
													$qQUERY=SYS_QUERY("select * from tab_simp where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($aREC_TAB_PINJ=SYS_FETCH($qQUERY)){
														if($aREC_TRM_LOAN1['SAVE_CODE'] == $aREC_TAB_PINJ['KODE_SIMPN']){
															echo "<option value='$aREC_TAB_PINJ[KODE_SIMPAN]' selected='$aREC_TRM_LOAN1[SAVE_CODE]' >$aREC_TAB_PINJ[NAMA_SIMPN]</option>";
														} else {
															echo "<option value='$aREC_TAB_PINJ[KODE_SIMPAN]'  >$aREC_TAB_PINJ[NAMA_SIMPN]</option>";
														}
													}
												?>
												</select>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNM_ANG?></label>
												<select id="SelectMember" name="EDIT_KD_MMBR" class="col-sm-4 form-label-900">
												<option value=""></option>
												<?php 
													$qQUERY=SYS_QUERY("select * from tb_member1 where APP_CODE='$cFILTER_CODE' and DELETOR='' order by NM_DEPAN");
													while($aREC_TB_MEMBER1=SYS_FETCH($qQUERY)){
														if($aREC_TRM_LOAN1['KD_MMBR'] == $aREC_TB_MEMBER1['KD_MMBR']){
															echo "<option value='$aREC_TB_MEMBER1[KD_MMBR]' selected='$aREC_TRM_LOAN1[KD_MMBR]' >$aREC_TB_MEMBER1[NM_DEPAN]</option>";
														} else {
															echo "<option value='$aREC_TB_MEMBER1[KD_MMBR]'  >$aREC_TB_MEMBER1[NM_DEPAN]</option>";
														}
													}
												?>
												</select>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo cKD_ANG?></label>
												<input type="text" class="col-sm-3 form-label-900" name='NM_ANGG' id="f_KD_MMBR" value=<?php echo $aREC_TRM_LOAN1['KD_MMBR']?>>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-5"><?php echo S_MSG('F005','Alamat')?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ALMT_ANG' id="f_ALAMAT" value=<?php echo decode_string($aREC_TRM_LOAN1['ALAMAT'])?>>
												<div class="clearfix"></div>

												<div class="text-left">
													<input type="submit" id="SAVE_BUTTON" class="btn btn-primary" value=<?php echo $cSAVE?> disabled="disabled">
													<input type="button" class="btn" value=<?php echo $cCLOSE?> onclick=self.history.back()>
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
	$cLOAN_ACT = encode_string($_POST['ADD_LOAN_ACT']);
	if($cLOAN_ACT=='') {
		$cMSG = S_MSG('KC52','Nomor Rekening Pinjaman tidak boleh kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	if($_POST['ADD_TRM_VALUE']<1) {
		$cMSG = S_MSG('KC53','Jumlah nominal setoran pinjaman tidak boleh kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	$cNO_VOUCHER = encode_string($_POST['ADD_NO_VOUCHER']);
	if($cNO_VOUCHER=='') {
		$cMSG = S_MSG('KC54','Nomor voucher setoran pinjaman tidak boleh kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	$cQUERY="select * from trm_loan1 where LOAN_ACT='$cLOAN_ACT' and NO_VOUCHER='$cNO_VOUCHER' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$dTG_SETOR = $_POST['ADD_SAVE_DATE'];		// 'dd/mm/yyyy'
		$cDATE = substr($dTG_SETOR,6,4). '-'. substr($dTG_SETOR,3,2). '-'. substr($dTG_SETOR,0,2);
		$cQUERY ="insert into tr_save1 set LOAN_ACT='$cLOAN_ACT'";
		$cQUERY.=", NO_VOUCHER='$cNO_VOUCHER', SAVE_DATE='$cDATE'";
		$cQUERY.=", TRM_VALUE=0".str_replace(',', '', $_POST['ADD_TRM_VALUE']).", ";
		$cQUERY.=", APP_CODE='$cFILTER_CODE', ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);
		header('location:kop_teller_pinjaman.php');
	} else {
		$cMSG = S_MSG('KB03','Nomor Rekening Simpanan sudah ada');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	break;


case "rubah":
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cSAVE_CODE = encode_string($_POST['EDIT_SAVE_CODE']);
	$cQUERY ="update tr_save1 set SAVE_CODE='$cSAVE_CODE', KD_MMBR='$_POST[EDIT_KD_MMBR]', ";
	$cQUERY.=" UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'";
	$cQUERY.=" where REC_NO='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:kop_teller_pinjaman.php');
	break;

case "delete":
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	
	$qCEK_KODE=SYS_QUERY("select * from trm_loan1 where REC_NO='$KODE_CRUD'");
	$AREC_TRM_LOAN1=SYS_FETCH($qCEK_KODE);
	$nAMOUNT = $AREC_TRM_LOAN1['TRM_VALUE'];
	$cREK = $AREC_TRM_LOAN1['TRM_NO_REK'];
	$q_LOAN=SYS_QUERY("select LOAN_ACT, LOAN_BALAN, APP_CODE, DELETOR  from tr_loan1 
		where LOAN_ACT='$cREK' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
	if(SYS_ROWS($q_LOAN)==0){
		$cNOT_FOUND= S_MSG('KC56','Nomor Rekening Pinjaman tidak ditemukan');
		echo "<script> alert('$cNOT_FOUND');	</script>";
		return;
	}

	$AREC_TR_LOAN11=SYS_FETCH($q_LOAN);
	$nJADINYA= $aREC_TR_LOAN1['LOAN_BALAN'] - $nAMOUNT;
	$cQUERY = "update tr_loan1 set LOAN_BALAN='$nJADINYA' 
		where LOAN_ACT='$cREK' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	SYS_QUERY($cQUERY);

	$cQUERY ="update trm_loan1 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and REC_NO='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:kop_teller_pinjaman.php?');
}
?>

<script>
function Disp_Rek_Pin(pkode_rekening) {
	var btn_stat = document.getElementById("SAVE_BUTTON");  // the submit button
//		alert(pkode_rekening);
    if (pkode_rekening == "") {
        document.getElementById("ADD_LOAN_ACT").innerHTML = "";
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
                document.getElementById("NAMA_ANGGOTA").innerHTML = xmlhttp.responseText;
//				alert(xmlhttp.responseText);
				document.getElementById("NAMA_ANGGOTA").value = xmlhttp.responseText;
            }
			if (document.getElementById("NAMA_ANGGOTA").value == "") {
				document.getElementById("SAVE_BUTTON").setAttribute('disabled', 'disabled');
			} else {
				document.getElementById("SAVE_BUTTON").removeAttribute('disabled');
			}
        };
        xmlhttp.open("GET","kop_cek_rek_pinjaman.php?NO_REK="+pkode_rekening,true);
        xmlhttp.send();
		
    }
}

function Kop_Teller_Pinjaman_focus()  
{  
	var uid = document.Add_New_record.ADD_LOAN_ACT.focus();  
	return true;  
}  

</script>

 
