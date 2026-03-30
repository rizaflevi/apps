<?php
//	kop_teller_realisasi_pinjaman.php
//	Transaksi pencairan pinjaman di kasir/teller

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cQUERY ="SELECT tr_loan1.*, tb_member1.* FROM tr_loan1 
		LEFT JOIN tb_member1 ON tr_loan1.NO_MEMBER=tb_member1.KD_MMBR 
		where LOAN_CAIR is NULL and tr_loan1.APP_CODE='$cFILTER_CODE' and tr_loan1.DELETOR=''";
	
	$qQUERY=SYS_QUERY($cQUERY);

	$cHEADER 		= S_MSG('KD01','Pencairan Pinjaman');
	$cREK_PINJAMAN 	= S_MSG('KC01','Rekening Pinjaman');
	$cKODE_ANGGOTA	= S_MSG('CB07','Kode Anggota');
	$cNAMA_ANGGOTA 	= S_MSG('F004','Nama');
	$cALMT_ANGGOTA 	= S_MSG('F005','Alamat');
	$cTANGGAL		= S_MSG('KB12','Tanggal');
	$cNMR_REKENING 	= S_MSG('KK20','Nomor Rekening');
	$cJENIS_PINJAM 	= S_MSG('KA20','Jenis Pinjaman');
	$cNILAI_PINJAM 	= S_MSG('KK10','Nilai Pinjaman');
	$cSALDO_PINJAM 	= S_MSG('KC19','Saldo Pinjaman');
	$cBUNGA_THN		= S_MSG('KK24','Bunga % (per thn)');
	$cTENOR			= S_MSG('KC14','Tenor');
	$cB_ASURANSI	= S_MSG('KC11','Biaya Asuransi');
	$cB_PROVISI		= S_MSG('KA07','Biaya Provisi');
	$cB_ADM			= S_MSG('KC12','Biaya Administrasi');
	$cB_BLN			= S_MSG('KC13','Biaya Adm Bulanan');
	$cKODE_AGUNAN 	= S_MSG('KC34','Kode Agunan');
	$cNILAI_AGUNAN 	= S_MSG('KA94','Harga Taksiran');
	
	$cADD_REK	= S_MSG('KK09','Tambah Rek. Pinjaman');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE_DATA= S_MSG('F302','Close');
	$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');

	$cMSG_MSH	= S_MSG('KC77','Rekening Pinjaman ini masih mempunyai transaksi, tidak dapat di hapus ?');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		$cHELP_BOX	= S_MSG('KD71','Help Realisasi Pinjaman');
		$cHELP_1	= S_MSG('KD72','Ini adalah modul untuk memasukkan data Realisasi / Pencairan Pinjaman/Kredit');
		$cHELP_2	= S_MSG('KD73','Sekarang ini ditampilkan daftar Nomor rekening Pinjaman / Kredit yang belum pernah di realisasi sebelumnya');
		$cHELP_3	= S_MSG('KD74','Untuk memasukkan data Realisasi baru, klik nomor Rekening yang akan di cairkan');
		$cHELP_4	= S_MSG('KD75','Kemudian klik Update untuk menyimpan data realisasi pinjaman dan akan ditampilkan kembali daftar rekening yang belum di cairkan jika ada');
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
											<a href="#help_kop_trans_pinjaman" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
										</li>
									</ol>
								</div>
<!--
								  <div class="actions panel_actions pull-right">
										<i class="box_toggle fa fa-chevron-down"></i>
										<i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
										<i class="box_close fa fa-times"></i>
								  </div>
-->
							</header>
							<div class="content-body">    
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12">

										<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>">
											<thead>
												<tr>
													<th style="<?php echo S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray')?>;width: 1px;"></th>
													<th style="<?php echo S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray')?>;"><?php echo $cNMR_REKENING?></th>
													<th style="<?php echo S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray')?>;"><?php echo $cTANGGAL?></th>
													<th style="<?php echo S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray')?>;"><?php echo $cKODE_ANGGOTA?></th>
													<th style="<?php echo S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray')?>;"><?php echo $cNAMA_ANGGOTA?></th>
													<th style="<?php echo S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray')?>;"><?php echo $cALMT_ANGGOTA?></th>
													<th style="<?php echo S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray')?>;"><?php echo $cNILAI_PINJAM?></th>
												</tr>
											</thead>

											<tbody>
												<?php
													while($aREC_LOAN=mysql_fetch_array($qQUERY)) {
													echo '<tr>';
														echo '<td class=""><div class="star"><i class="fa fa-cloud-upload icon-xs icon-default"></i></div></td>';
														echo "<td><span><a href='?_a=".md5('upd_rloan')."&_r=".md5($aREC_LOAN['LOAN_ACT'])."'>".$aREC_LOAN['LOAN_ACT']."</a></span></td>";
														echo '<td>'.date("d-m-Y", strtotime($aREC_LOAN['LOAN_DATE'])).'</td>';
														echo '<td>'.$aREC_LOAN['NO_MEMBER'].'</td>';
														echo '<td>'.$aREC_LOAN['NM_DEPAN'].'</td>';
														echo '<td>'.$aREC_LOAN['ALAMAT'].'</td>';
														echo '<td align="right">'.number_format($aREC_LOAN['LOAN_VAL']).'</td>';
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
			<script src="sys_js.js" type="text/javascript"></script> 

			<div class="modal" id="help_kop_trans_pinjaman" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">

							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_3?></p>
							<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_4?></p>

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
	break;

case md5('upd_rloan'):
	$cQUERY = "select tr_loan1.*, tb_member1.* from tr_loan1 ";
	$cQUERY.= "left join tb_member1 ON tr_loan1.NO_MEMBER=tb_member1.KD_MMBR ";
	$cQUERY.= " where md5(tr_loan1.LOAN_ACT)='".$_GET['_r'];
	$cQUERY.= "' and tr_loan1.APP_CODE='".$cFILTER_CODE."' and tr_loan1.DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);
	$REC_TR_LOAN1=mysql_fetch_array($qQUERY);
	if(mysql_num_rows($qQUERY)==0){
		header('location:kop_teller_realisasi_pinjaman.php');
	}
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
									  <h2 class="title"><?php echo $cHEADER?></h2>
								</div>
								<div class="pull-right">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=delete&id=<?php echo $REC_TR_LOAN1['LOAN_ACT']?>" onClick="return confirm('<?php echo $cMSG_DEL?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
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
										<form action ="?_a=rubah&id=<?php echo $REC_TR_LOAN1['LOAN_ACT']?>" method="post"  onSubmit="return CEK_KOP_TB_PIN(this)">
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNMR_REKENING?></label>
												<input type="text" class="col-sm-3 form-label-900" name='NO_REKP' id="field-1" value=<?php echo $REC_TR_LOAN1['LOAN_ACT']?> disabled="disabled">
												<div class="clearfix"></div><br>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-3 form-label-900" data-mask="date" name='UPD_LOAN_DATE' id="field-2" value="<?php echo date("d-m-Y", strtotime($REC_TR_LOAN1['LOAN_DATE']))?>" disabled="disabled"><br>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cJENIS_PINJAM?></label>
												<select name="EDIT_LOAN_CODE" class="col-sm-5 form-label-900">
												<?php 
													$REC_TAB_PINJ=SYS_QUERY("select * from tab_pinj where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($aREC_TAB_PINJ=mysql_fetch_array($REC_TAB_PINJ)){
														if($REC_TR_LOAN1['LOAN_CODE']==$aREC_TAB_PINJ['KODE_PINJM']){
															echo "<option value='$aREC_TAB_PINJ[KODE_PINJM]' selected='$REC_TR_LOAN1[KLPK_ANGGT]' >$aREC_TAB_PINJ[NAMA_PINJM]</option>";
														} else {
															echo "<option value='$aREC_TAB_PINJ[KODE_PINJM]'  >$aREC_TAB_PINJ[NAMA_PINJM]</option>";
														}
													}
												?>
												</select>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cKODE_ANGGOTA?></label>
												<input type="text" class="col-sm-4 form-label-900" name='EDIT_KD_MMBR' id="field-2" value="<?php echo $REC_TR_LOAN1['NO_MEMBER']?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNAMA_ANGGOTA?></label>
												<input type="text" class="col-sm-6 form-label-900" name='EDIT_NM_DEPAN' id="field-2" value="<?php echo $REC_TR_LOAN1['NM_DEPAN']?>" disabled="disabled"><br>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cALMT_ANGGOTA?></label>
												<input type="text" class="col-sm-8 form-label-900" name='ALMT_ANG' id="field-2" value="<?php echo $REC_TR_LOAN1['ALAMAT']?>" disabled="disabled"><br>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNILAI_PINJAM?></label>
												<input type="text" class="col-sm-3 form-label-900" name='EDIT_LOAN_VAL' id="field-2" data-mask="fdecimal" value=<?php echo $REC_TR_LOAN1['LOAN_VAL']?>><br>
												<div class="clearfix"></div>


<!-- TAB - START -->
												<div class="col-sm-12">
													<h4> </br></h4>
													<ul class="nav nav-tabs primary">
														 <li class="active">
															  <a href="#Detil-1" data-toggle="tab">
																	<i class="fa fa-user"></i> <?php echo S_MSG('F010','Detil')?>
															  </a>
														 </li>
														 <li>
															  <a href="#Account-1" data-toggle="tab">
																	<i class="fa fa-home"></i> <?php echo S_MSG('KA73','Agunan')?> 
															  </a>
														 </li>
													</ul>

													<div class="tab-content primary">
														<div class="tab-pane fade in active" id="Detil-1">
															
															<div class="form-group">
																<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cBUNGA_THN?></label>
																<input type="text" class="col-sm-1 form-label-900" name='EDIT_BUNGA' id="field-3" data-mask="fdecimal" value=<?php echo $REC_TR_LOAN1['BU_NGA']?>  ><br>
															</div>		

															<div class="form-group">
																<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cTENOR?></label>
																<input type="text" class="col-sm-1 form-label-900" id="field-8" name='EDIT_TE_NOR' data-mask="fdecimal" value=<?php echo $REC_TR_LOAN1['TE_NOR']?> ><br>
															</div>		

															<div class="form-group">
																<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cB_PROVISI?></label>
																<input type="text" class="col-sm-3 form-label-900" id="field-8" name='EDIT_BIAYA_PRV' data-mask="fdecimal" value=<?php echo $REC_TR_LOAN1['BIAYA_PRV']?> ><br>
															</div>		

															<div class="form-group">
																<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cB_ASURANSI?></label>
																<input type="text" class="col-sm-3 form-label-900" name='EDIT_BY_ASURANSI' id="field-3" data-mask="fdecimal" value=<?php echo $REC_TR_LOAN1['BY_ASURANS']?>  ><br>
															</div>		

															<div class="form-group">
																<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cB_ADM?></label>
																<input type="text" class="col-sm-3 form-label-900" id="field-8" name='EDIT_BIAYA_ADM' data-mask="fdecimal" value=<?php echo $REC_TR_LOAN1['BIAYA_ADM']?> ><br>
															</div>		

															<div class="form-group">
																<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cB_BLN?></label>
																<input type="text" class="col-sm-3 form-label-900" id="field-8" name='EDIT_BIAYA_BLN' data-mask="fdecimal" value=<?php echo $REC_TR_LOAN1['BIAYA_BLN']?> >
															</div>		
															<br><br>

														</div>		<!-- End of Tab 1 -->
														
														<!-- Tab 2 begin -->
														<div class="tab-pane fade" id="Account-1">
															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cKODE_AGUNAN?></label>
															<div class="col-sm-8">		
																<select name="EDIT_KODE_AGGN" class="col-sm-8 form-label-900">
																<?php 
																	echo "<option value=' '  > </option>";
																	$cQUERY=SYS_QUERY("select * from tb_aggn2 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
																	while($REC_TB_AGGN2=mysql_fetch_array($cQUERY)){
																		if($REC_TB_AGGN2['KODE_AGGN']==$REC_TR_LOAN1['KD_AGUNAN']){
																			echo "<option value='$REC_TR_LOAN1[KD_AGUNAN]' selected='$REC_TR_LOAN1[KD_AGUNAN]' >$REC_TB_AGGN2[NAMA_AGN]</option>";
																		} else
																		echo "<option value='$REC_TB_AGGN2[KODE_AGGN]'  >$REC_TB_AGGN2[NAMA_AGN]</option>";
																	}
																?>
																</select>
															</div>		
															<br><br><br><br>

															<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNILAI_AGUNAN?></label>
															<input type="text" class="form-label-900" name='DISP_KODE_AGGN' data-mask="fdecimal" value=<?php echo $REC_TR_LOAN1['KD_AGUNAN']?>  ><br>

														</div>		<!-- End of Tab 2 -->
														
													</div></br>

												</div>
<!--  TAB - END -->	
												<div class="clearfix"></div>
												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE?>>
													<input type="button" class="btn" value=<?php echo $cCLOSE_DATA?> onclick=self.history.back()>
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
	if($_POST['ADD_LOAN_ACT']=='') {
		$cMSG = S_MSG('KA18','Nomor Rekening tidak boleh kosong');
		echo "<script> alert('$cMSG');		window.history.back();		</script>";
		return;
	}

	if($_POST['ADD_KD_MMBR']=='') {
		$cMSG = S_MSG('KA16','Kode Anggota tidak boleh kosong');
		echo "<script> alert('$cMSG');		window.history.back();		</script>";
		return;
	}

	if($_POST['ADD_LOAN_VAL']==0){
		$cMSG= S_MSG('KC52','Nilai pinjaman masih kosong');
		echo "<script> alert('$cMSG');		window.history.back();		</script>";
		return;
		header('location:kop_teller_realisasi_pinjaman.php');
	}

	$cQUERY="select * from tb_member1 where APP_CODE='$cFILTER_CODE' and KD_MMBR='$_POST[ADD_KD_MMBR]' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(mysql_num_rows($cCEK_KODE)==0){
		$cMSG = S_MSG('KA17','Kode Anggota tidak ada di master');
		echo "<script> alert('$cMSG');		window.history.back();		</script>";
		return;
	}

	$cQUERY="select * from tr_loan1 where APP_CODE='$cFILTER_CODE' and LOAN_ACT='$_POST[ADD_LOAN_ACT]' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY) or die ('Error in query.' .mysql_error());
	if(mysql_num_rows($cCEK_KODE)==0){
		$cQUERY ="insert into tr_loan1 set NO_MEMBER='$_POST[ADD_KD_MMBR]', LOAN_ACT='$_POST[ADD_LOAN_ACT]'";
		$cQUERY.=", LOAN_CODE='$_POST[ADD_LOAN_CODE]', LOAN_DATE='$_POST[ADD_LOAN_DATE]'";
		$cQUERY.=", LOAN_VAL=0".str_replace(',', '', $_POST['ADD_LOAN_VAL']).", BY_ASURANS=0".str_replace(',', '', $_POST['ADD_BY_ASURANSI']);
		$cQUERY.=", BIAYA_PRV=0".str_replace(',', '', $_POST['ADD_BIAYA_PRV']).", BU_NGA=0".str_replace(',', '', $_POST['ADD_BU_NGA']);
		$cQUERY.=", TE_NOR=0".str_replace(',', '', $_POST['ADD_TE_NOR']);
		$cQUERY.=", BIAYA_ADM=0".str_replace(',', '', $_POST['ADD_BIAYA_ADM']);
		$cQUERY.=", BIAYA_BLN=0".str_replace(',', '', $_POST['ADD_BIAYA_BLN']).", KD_AGUNAN='$_POST[ADD_KODE_AGGN]'";
		$cQUERY.=", APP_CODE='$cFILTER_CODE', ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY) or die ('Error in query.' .mysql_error().'==> '.$cQUERY);
		header('location:kop_teller_realisasi_pinjaman.php');
	} else {
		$cMSG = S_MSG('KA26','Nomor Rekening sudah ada, tidak bisa digunakan lagi, silakan isi yang lain');
		echo "<script> alert('$cMSG');		window.history.back();		</script>";
		return;
	}
	break;

case 'rubah':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$str_DATE = $_POST['UPD_LOAN_DATE'];		// 'dd/mm/yyyy'
	$cDATE = substr($str_DATE,6,4). '-'. substr($str_DATE,3,2). '-'. substr($str_DATE,0,2);
	$cKD_MEMBER = $_POST['EDIT_KD_MMBR'];
	$cQUERY ="select KD_MMBR from tb_member1";
	$cQUERY.=" where KD_MMBR=$cKD_MEMBER and APP_CODE='$cFILTER_CODE' and DELETOR=''";
	$qQUERY =SYS_QUERY($cQUERY) or die ('Error in query.' .mysql_error());
	if(mysql_num_rows($qQUERY)==0){
		$cMSG= S_MSG('CB93','Kode Anggota tidak ditemukan');
		echo "<script> alert('$cMSG');		window.history.back();		</script>";
		return;
	}

	if($_POST['EDIT_LOAN_VAL']==0){
		$cMSG= S_MSG('KC52','Nilai pinjaman masih kosong');
		echo "<script> alert('$cMSG');		window.history.back();		</script>";
		return;
		header('location:kop_teller_realisasi_pinjaman.php');
	}

	$cQUERY ="update tr_loan1 set  ";
	$cQUERY.=" LOAN_CODE='$_POST[EDIT_LOAN_CODE]', LOAN_DATE='$cDATE', ";
	$cQUERY.=" NO_MEMBER='".$cKD_MEMBER."', ";
	$cQUERY.=" LOAN_VAL=".str_replace(',', '', $_POST['EDIT_LOAN_VAL']).", BY_ASURANS=".str_replace(',', '', $_POST['EDIT_BY_ASURANSI']).", ";
	$cQUERY.=" BU_NGA=".str_replace(',', '', $_POST['EDIT_BUNGA']).", TE_NOR=".str_replace(',', '', $_POST['EDIT_TE_NOR']).", ";
	$cQUERY.=" BIAYA_PRV=".str_replace(',', '', $_POST['EDIT_BIAYA_PRV']).", BIAYA_ADM=".str_replace(',', '', $_POST['EDIT_BIAYA_ADM']).", ";
	$cQUERY.=" BIAYA_BLN=".str_replace(',', '', $_POST['EDIT_BIAYA_BLN']).", KD_AGUNAN='$_POST[EDIT_KODE_AGGN]', ";
	$cQUERY.=" UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and LOAN_ACT='$KODE_CRUD'";
	SYS_QUERY($cQUERY);

	header('location:kop_teller_realisasi_pinjaman.php?');
	break;

case 'delete':
	$TRM_NO_REK=$_GET['id'];
	$q_BYR_PINJ=SYS_QUERY("SELECT TRM_NO_REK from trm_loan1 where TRM_NO_REK='$TRM_NO_REK' and APP_CODE='$cFILTER_CODE' and DELETOR='' ")
		 or die ('Error in query.' .mysql_error().'==> '.$q_BYR_PINJ);
	if (mysql_num_rows($q_BYR_PINJ)==0) {
		$NOW = date("Y-m-d H:i:s");
		$cQUERY ="update tr_loan1 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
		$cQUERY.=" where APP_CODE='$cFILTER_CODE' and LOAN_ACT='$TRM_NO_REK'";
		SYS_QUERY($cQUERY);
		header('location:kop_teller_realisasi_pinjaman.php');
	} else {
		echo "<script> alert('$cMSG_MSH');		window.history.back();		</script>";
		return;
	}
}
?>

