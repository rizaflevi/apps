<?php
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
    }
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$cQUERY ="SELECT tr_loan1.*, tb_member1.* FROM tr_loan1 
		LEFT JOIN tb_member1 ON tr_loan1.NO_MEMBER=tb_member1.KD_MMBR 
		where tr_loan1.APP_CODE='$cFILTER_CODE' and tr_loan1.DELETOR=''";
	
	$qQUERY=SYS_QUERY($cQUERY);

	$cHEADER 		= S_MSG('KC01','Rekening Pinjaman');
	$cKODE_ANGGOTA	= S_MSG('CB07','Kode Anggota');
	$cNAMA_ANGGOTA 	= S_MSG('F004','Nama');
	$cALMT_ANGGOTA 	= S_MSG('F005','Alamat');
	$cTANGGAL		= S_MSG('KB12','Tanggal');
	$cNMR_REKENING 	= S_MSG('KK20','Nomor Rekening');
	$cJENIS_PINJAM 	= S_MSG('KA20','Jenis Pinjaman');
	$cNILAI_PINJAM 	= S_MSG('KK10','Nilai Pinjaman');
	$cSALDO_PINJAM 	= S_MSG('KC19','Saldo Pinjaman');
	$cBUNGA_THN		= S_MSG('KK24','Bunga % (per thn)');
	$cJN_BUNG		= S_MSG('KI53','Jenis Bunga');
	$cTENOR			= S_MSG('KC14','Tenor');
	$cB_ASURANSI	= S_MSG('KC11','Biaya Asuransi');
	$cB_PROVISI		= S_MSG('KA07','Biaya Provisi');
	$cB_ADM			= S_MSG('KC12','Biaya Administrasi');
	$cB_BLN			= S_MSG('KC13','Biaya Adm Bulanan');
	$cKODE_AGUNAN 	= S_MSG('KC34','Kode Agunan');
	$cNILAI_AGUNAN 	= S_MSG('KA94','Harga Taksiran');
	
	$cADD_REK=S_MSG('KK09','Tambah Rek. Pinjaman');
	$cSAVE=S_MSG('F301','Save');
	$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');

	$cMSG_MSH	= S_MSG('KC77','Rekening Pinjaman ini masih mempunyai transaksi, tidak dapat di hapus ?');

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}

	$cHDR_BACK_CLR = S_PARA('_DISP_TABLE_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
		$cHELP_BOX		= S_MSG('KC81','Help Rekening Pinjaman');
		$cHELP_1		= S_MSG('KC82','Ini adalah modul untuk memasukkan data daftar Nomor Rekening Pinjaman/Kredit');
		$cHELP_2		= S_MSG('KC83','Untuk memasukkan data Rekening baru, klik tambah Rekening / add new');
		$cHELP_3		= S_MSG('KC84','Sekarang ini ditampilkan daftar Nomor rekening Pinjaman / Kredit yang telah pernah dimasukkan');
		$cHELP_4		= S_MSG('KC85','Untuk merubah salah satu data Pinjaman, klik di nomor Rekening dan akan masuk ke mode update');
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
					<div class="project-info"> </div>
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
												 <a href="?_a=<?php echo md5('CREATE')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
											</li>
											<li>
												<a href="#help_kop_trans_pinjaman" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNMR_REKENING?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cTANGGAL?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_ANGGOTA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_ANGGOTA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cALMT_ANGGOTA?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNILAI_PINJAM?></th>
														<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cSALDO_PINJAM?></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while($aREC_LOAN=SYS_FETCH($qQUERY)) {
														echo '<tr>';
	//															echo '<td style="width: 1px;"></td>';
															echo '<td class=""><div class="star"><i class="fa fa-cloud-upload icon-xs icon-default"></i></div></td>';
															echo "<td><span><a href='?_a=". md5('UPDate'). "&_p=".md5($aREC_LOAN['LOAN_ACT']). "'>".$aREC_LOAN['LOAN_ACT']."</a></span></td>";
															echo '<td>'.date("d-m-Y", strtotime($aREC_LOAN['LOAN_DATE'])).'</td>';
															echo '<td>'.$aREC_LOAN['NO_MEMBER'].'</td>';
															echo '<td>'.decode_string($aREC_LOAN['NM_DEPAN']).'</td>';
															echo '<td>'.decode_string($aREC_LOAN['ALAMAT']).'</td>';
															echo '<td align="right">'.number_format($aREC_LOAN['LOAN_VAL']).'</td>';
															echo '<td align="right">'.number_format($aREC_LOAN['LOAN_BALAN']).'</td>';
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

case md5('CREATE'):
	$cHELP_BOX		= S_MSG('KC91','Help Tambah Rekening Pinjaman');
	$cHELP_1		= S_MSG('KC92','Ini adalah modul untuk menambahkan data Nomor Rekening Pinjaman/Kredit yang baru');
	$cHELP_2		= S_MSG('KC93','Rekening Pinjaman ini memuat data masing-masing produk pinjaman untuk setiap anggota/nasabah');
	$cHELP_3		= S_MSG('KC94','Data yang dimasukkan pada Rekening Pinjaman / Kredit ini adalah :');
	$cHELP_4		= S_MSG('KC95','Nomor Rekening : adalah nomor rekening pinjaman untuk masing-masing anggota, yang otomatis terisi dengan nomor rekening pinjaman terakhir di tambah 1');

	$q_TAB_PINJ=SYS_QUERY("select * from tab_pinj where APP_CODE='$cFILTER_CODE' and DELETOR=''");
	$aREC_1=SYS_FETCH($q_TAB_PINJ);
	$v_JNS_PINJ = $aREC_1['KODE_PINJM'];

	$cQ_LAST="select LOAN_ACT from tr_loan1 where APP_CODE='$cFILTER_CODE' and DELETOR='' order by LOAN_ACT desc limit 1";
	$qQ_LAST	= SYS_QUERY($cQ_LAST);
	$aREC_TR_SAVE1= SYS_FETCH($qQ_LAST);
	$cLAST_NOM	= $aREC_TR_SAVE1['LOAN_ACT'];
	$nLAST_NOM=intval($cLAST_NOM)+1;
	$cLAST_NOM=str_pad((string)$nLAST_NOM, 10, '0', STR_PAD_LEFT);

?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_header.php");	require_once("scr_topbar.php");	?>
		<body>
			<div class="page-container row-fluid">
				
				<div class="page-sidebar ">
					<div class="page-sidebar-wrapper" id="main-menu-wrapper">
						<?php	require_once("scr_user_info.php");	require_once("scr_menu.php");	?>
					</div>
					<div class="project-info"> </div>
				</div>

				<section id="main-content" class=" ">
					<section class="wrapper main-wrapper" style=''>
						<div class="clearfix"></div>

						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left"><?php echo $cADD_REK?></h2>
									<div class="pull-right hidden-xs">
										<ol class="breadcrumb">
											<li>	<a href="#help_add_kop_trans_pinjaman" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>	</li>
										</ol>
									</div>
								</header>	
								
								<div class="content-body">
									<div class="row">
										<form action ="?_a=tambah" method="post">  <!-- 	onSubmit="return CEK_KOP_TB_PIN(this)">	-->
											<div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNMR_REKENING?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_LOAN_ACT' value="<?php echo $cLAST_NOM?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-2"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-3 form-label-900" data-mask="date" name='ADD_LOAN_DATE' value="<?php echo date('d/m/Y')?>">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cJENIS_PINJAM?></label>
												<select name="ADD_LOAN_CODE" class="col-sm-4 form-label-900" value="<?php echo $v_JNS_PINJ?>">
												<?php 
													while($aREC_TAB_PINJ=SYS_FETCH($q_TAB_PINJ)){
														echo "<option value='$aREC_TAB_PINJ[KODE_PINJM]'  >$aREC_TAB_PINJ[NAMA_PINJM]</option>";
													}
												?>
												</select>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cKODE_ANGGOTA?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_KD_MMBR' id="ADD_KD_MMBR" onblur="Disp_Member(this.value)" autofocus>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="ADD_NM_DEPAN"><?php echo $cNAMA_ANGGOTA?></label>
												<input type="text" class="col-sm-6 form-label-900" name='ADD_NM_DEPAN' id="f_NM_MMBR" disabled="disabled">
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-7"><?php echo $cNILAI_PINJAM?></label>
												<input type="text" class="col-sm-3 form-label-900" name='ADD_LOAN_VAL' data-numeric-align="right" value=0 data-mask="fdecimal" >
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700"><?php echo $cJN_BUNG?></label>
												<select name='ADD_JN_BUNGA' class="col-sm-5 form-label-900">
													<?php 
														echo "<option value=' '  > </option>";
														$REC_TB_INTR=SYS_QUERY("select * from tb_interest where APP_CODE='$cFILTER_CODE' and DELETOR=''");
														while($aREC_TB_INTR=SYS_FETCH($REC_TB_INTR)){
															echo "<option value='$aREC_TB_INTR[KD_INTR]'  >$aREC_TB_INTR[DESC_INTRS]</option>";
														}
													?>
												</select><br>	<div class="clearfix"></div>
<!-- TAB - START -->
												<div class="col-sm-9">
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
															
															<label class="col-sm-4 form-label-700" for="field-8"><?php echo $cBUNGA_THN?></label>
															<input type="text" class="col-sm-2 form-label-900" name='ADD_BU_NGA' data-numeric-align="right" value=0 data-mask="fdecimal"><br>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-8"><?php echo $cTENOR?></label>
															<input type="text" class="col-sm-3 form-label-900" name='ADD_TE_NOR' data-numeric-align="right" value=0 data-mask="fdecimal"><br>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-9"><?php echo $cB_PROVISI?></label>
															<input type="text" class="col-sm-3 form-label-900" name='ADD_BIAYA_PRV' data-numeric-align="right" value=0 data-mask="fdecimal"><br>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-0"><?php echo $cB_ASURANSI?></label>
															<input type="text" class="col-sm-3 form-label-900" name='ADD_BY_ASURANSI' data-numeric-align="right" value=0 data-mask="fdecimal"><br>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-A"><?php echo $cB_ADM?></label>
															<input type="text" class="col-sm-3 form-label-900" data-numeric-align="right" value=0 name='ADD_BIAYA_ADM' data-mask="fdecimal"><br>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-B"><?php echo $cB_BLN?></label>
															<input type="text" class="col-sm-3 form-label-900" data-numeric-align="right" value=0 name='ADD_BIAYA_BLN' data-mask="fdecimal"><br>
															<div class="clearfix"></div>
														</div>		<!-- End of Tab 1 -->
														
														<!-- Tab 2 begin -->
														<div class="tab-pane fade" id="Account-1">
															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cKODE_AGUNAN?></label>
															<div class="col-sm-8">		
																<select name="ADD_KODE_AGGN" class="col-sm-8 form-label-900 m-bot15">
																<?php 
																	echo "<option value=' '  > </option>";
																	$q_TB_AGGN2=SYS_QUERY("select * from tb_aggn2 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
																	while($REC_TB_AGGN2=SYS_FETCH($q_TB_AGGN2)){
																		echo "<option value='$REC_TB_AGGN2[KODE_AGGN]'  >$REC_TB_AGGN2[NAMA_AGN]</option>";
																	}
																?>
																</select>
															</div>		
															<br><br>

															<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNILAI_AGUNAN?></label>
															<input type="text" class="col-sm-4 form-label-900" name='DISP_NILAI_AGGN' id="DISP_NILAI_AGGN"><br><br><br><br><br><br><br><br>

														</div>		<!-- End of Tab 2 -->
														
													</div></br>

												</div>
<!--  TAB - END -->	
												<div class="clearfix"></div>
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

			<div class="modal" id="help_add_kop_trans_pinjaman" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
				<div class="modal-dialog animated bounceInDown">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><?php echo $cHELP_BOX?></h4>
						</div>
						<div class="modal-body">

							<p><?php echo $cHELP_1?></p>	<p><?php echo $cHELP_2?></p>	<p><?php echo $cHELP_3?></p>	<p><?php echo $cHELP_4?></p>

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

case md5('UPDate'):
	$cQUERY = "select tr_loan1.*, tb_member1.* from tr_loan1 
		left join tb_member1 ON tr_loan1.NO_MEMBER=tb_member1.KD_MMBR 
		where md5(tr_loan1.LOAN_ACT)='".$_GET['_p'];
	$cQUERY.= "' and tr_loan1.APP_CODE='".$cFILTER_CODE."' and tr_loan1.DELETOR=''";
	$qQUERY=SYS_QUERY($cQUERY);
	$REC_TR_LOAN1=SYS_FETCH($qQUERY);
	if(SYS_ROWS($qQUERY)==0){
		header('location:kop_trans_pinjaman.php?');
	}
?>
	<!DOCTYPE html>
	<html class=" ">
		<body class=" ">
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
									  <h2 class="title"><?php echo S_MSG('KA96','Edit Rekening Pinjaman')?></h2>
								</div>
								<div class="pull-right">									 
									<ol class="breadcrumb">
										<li>
											<a href="?_a=<?php echo md5('DELete')?>&id=<?php echo md5($REC_TR_LOAN1['LOAN_ACT'])?>" onClick="return confirm('<?php echo $cMSG_DEL?>')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
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
										<form action ="?_a=rubah&id=<?php echo $REC_TR_LOAN1['LOAN_ACT']?>" method="post"  onSubmit="return CEK_KOP_TB_PIN(this)">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNMR_REKENING?></label>
												<input type="text" class="col-sm-3 form-label-900" name='NO_REKP' id="field-1" value=<?php echo $REC_TR_LOAN1['LOAN_ACT']?> disabled="disabled"><br>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cTANGGAL?></label>
												<input type="text" class="col-sm-3 form-label-900 datepicker" data-mask="date" name='UPD_LOAN_DATE' id="field-2" value="<?php echo date("d-m-Y", strtotime($REC_TR_LOAN1['LOAN_DATE']))?>"><br>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700" for="field-4"><?php echo $cJENIS_PINJAM?></label>
												<select name="EDIT_LOAN_CODE" class="col-sm-5 form-label-900">
												<?php 
													$REC_TAB_PINJ=SYS_QUERY("select * from tab_pinj where APP_CODE='$cFILTER_CODE' and DELETOR=''");
													while($aREC_TAB_PINJ=SYS_FETCH($REC_TAB_PINJ)){
														if($REC_TR_LOAN1['LOAN_CODE']==$aREC_TAB_PINJ['KODE_PINJM']){
															echo "<option value='$aREC_TAB_PINJ[KODE_PINJM]' selected='$REC_TR_LOAN1[KLPK_ANGGT]' >$aREC_TAB_PINJ[NAMA_PINJM]</option>";
														} else {
															echo "<option value='$aREC_TAB_PINJ[KODE_PINJM]'  >$aREC_TAB_PINJ[NAMA_PINJM]</option>";
														}
													}
												?>
												</select><br>	<div class="clearfix"></div>

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
												<input type="text" class="col-sm-2 form-label-900" name='EDIT_LOAN_VAL' id="field-2" data-numeric-align="right" data-mask="fdecimal" value=<?php echo $REC_TR_LOAN1['LOAN_VAL']?>><br>
												<div class="clearfix"></div>

												<label class="col-sm-4 form-label-700"><?php echo $cJN_BUNG?></label>
												<select name='UPD_JN_BUNGA' class="col-sm-3 form-label-900">
													<?php 
														echo "<option value=' '  > </option>";
														$REC_TB_INTR=SYS_QUERY("select * from tb_interest where APP_CODE='$cFILTER_CODE' and DELETOR=''");
														while($aREC_TB_INTR=SYS_FETCH($REC_TB_INTR)){
															if($aREC_TB_INTR['KD_INTR']==$REC_TR_LOAN1['JN_BUNGA']){
																echo "<option value='$aREC_TB_INTR[KD_INTR]' selected='$REC_TR_LOAN1[JN_BUNGA]' >$aREC_TB_INTR[DESC_INTRS]</option>";
															} else
															echo "<option value='$aREC_TB_INTR[KD_INTR]'  >$aREC_TB_INTR[DESC_INTRS]</option>";
														}
													?>
												</select><br><div class="clearfix"></div>
											</div>
<!-- TAB - START -->
												<div class="col-sm-9">
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
															
															<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cBUNGA_THN?></label>
															<input type="text" class="col-sm-2 form-label-900" name='EDIT_BUNGA' id="field-3" data-mask="fdecimal" data-numeric-align="right" value=<?php echo $REC_TR_LOAN1['BU_NGA']?>  ><br>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cTENOR?></label>
															<input type="text" class="col-sm-1 form-label-900" id="field-8" name='EDIT_TE_NOR' data-mask="fdecimal" data-numeric-align="right" value=<?php echo $REC_TR_LOAN1['TE_NOR']?> ><br>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cB_PROVISI?></label>
															<input type="text" class="col-sm-3 form-label-900" id="field-8" name='EDIT_BIAYA_PRV' data-mask="fdecimal" data-numeric-align="right" value=<?php echo $REC_TR_LOAN1['BIAYA_PRV']?> ><br>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cB_ASURANSI?></label>
															<input type="text" class="col-sm-3 form-label-900" name='EDIT_BY_ASURANSI' id="field-3" data-mask="fdecimal" data-numeric-align="right" value=<?php echo $REC_TR_LOAN1['BY_ASURANS']?>  ><br>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cB_ADM?></label>
															<input type="text" class="col-sm-3 form-label-900" id="field-8" name='EDIT_BIAYA_ADM' data-mask="fdecimal" data-numeric-align="right" value=<?php echo $REC_TR_LOAN1['BIAYA_ADM']?> ><br>
															<div class="clearfix"></div>

															<label class="col-sm-4 form-label-700" for="field-6"><?php echo $cB_BLN?></label>
															<input type="text" class="col-sm-3 form-label-900" id="field-8" name='EDIT_BIAYA_BLN' data-mask="fdecimal" data-numeric-align="right" value=<?php echo $REC_TR_LOAN1['BIAYA_BLN']?> >
															<div class="clearfix"></div>
															<br><br>

														</div>		<!-- End of Tab 1 -->
														
														<!-- Tab 2 begin -->
														<div class="tab-pane fade" id="Account-1">
															<label class="col-sm-4 form-label-700" for="field-21"><?php echo $cKODE_AGUNAN?></label>
															<select name="EDIT_KODE_AGGN" class="col-sm-3 form-label-900">
															<?php 
																echo "<option value=' '  > </option>";
																$cQUERY=SYS_QUERY("select * from tb_aggn2 where APP_CODE='$cFILTER_CODE' and DELETOR=''");
																while($REC_TB_AGGN2=SYS_FETCH($cQUERY)){
																	if($REC_TB_AGGN2['KODE_AGGN']==$REC_TR_LOAN1['KD_AGUNAN']){
																		echo "<option value='$REC_TR_LOAN1[KD_AGUNAN]' selected='$REC_TR_LOAN1[KD_AGUNAN]' >$REC_TB_AGGN2[NAMA_AGN]</option>";
																	} else
																	echo "<option value='$REC_TB_AGGN2[KODE_AGGN]'  >$REC_TB_AGGN2[NAMA_AGN]</option>";
																}
															?>
															</select>	<div class="clearfix"></div>	<br>

															<label class="col-sm-4 form-label-700" for="field-3"><?php echo $cNILAI_AGUNAN?></label>
															<input type="text" class="col-sm-2 form-label-900" name='DISP_KODE_AGGN' data-mask="fdecimal" data-numeric-align="right" value=<?php echo $REC_TR_LOAN1['KD_AGUNAN']?>  ><br>
															<div class="clearfix"></div>

														</div>		<!-- End of Tab 2 -->
														
													</div></br>

												</div>
<!--  TAB - END -->	
												<div class="clearfix"></div>
												<div class="text-left">
													<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE?>>
													<input type="button" class="btn" value=<?php echo S_MSG('F302','Close')?> onclick=self.history.back()>
												</div>
										</form>
									</div>
								</div>
							</section>
						</div>

					</section>
				</section>
				<!-- END CONTENT -->
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
		$cMSG = S_MSG('KA18','Nomor Rekening tidak boleh kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	if($_POST['ADD_KD_MMBR']=='') {
		$cMSG = S_MSG('KA16','Kode Anggota tidak boleh kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	if($_POST['ADD_LOAN_VAL']==0){
		$cMSG= S_MSG('KC52','Nilai pinjaman masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:tr_payment.php');
	}

	$cQUERY="select * from tb_member1 where APP_CODE='$cFILTER_CODE' and KD_MMBR='$_POST[ADD_KD_MMBR]' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$cMSG = S_MSG('KA17','Kode Anggota tidak ada di master');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	$cQUERY="select * from tr_loan1 where APP_CODE='$cFILTER_CODE' and LOAN_ACT='$cLOAN_ACT' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$cQUERY ="insert into tr_loan1 set NO_MEMBER='$_POST[ADD_KD_MMBR]', LOAN_ACT='$cLOAN_ACT'";
		$cQUERY.=", LOAN_CODE='$_POST[ADD_LOAN_CODE]', LOAN_DATE='$_POST[ADD_LOAN_DATE]'";
		$cQUERY.=", JN_BUNGA='$_POST[ADD_JN_BUNGA]'";
		$cQUERY.=", LOAN_VAL=0".str_replace(',', '', $_POST['ADD_LOAN_VAL']).", BY_ASURANS=0".str_replace(',', '', $_POST['ADD_BY_ASURANSI']);
		$cQUERY.=", BIAYA_PRV=0".str_replace(',', '', $_POST['ADD_BIAYA_PRV']).", BU_NGA=0".str_replace(',', '', $_POST['ADD_BU_NGA']);
		$cQUERY.=", TE_NOR=0".str_replace(',', '', $_POST['ADD_TE_NOR']);
		$cQUERY.=", BIAYA_ADM=0".str_replace(',', '', $_POST['ADD_BIAYA_ADM']);
		$cQUERY.=", BIAYA_BLN=0".str_replace(',', '', $_POST['ADD_BIAYA_BLN']).", KD_AGUNAN='$_POST[ADD_KODE_AGGN]'";
		$cQUERY.=", APP_CODE='$cFILTER_CODE', ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);
		header('location:kop_trans_pinjaman.php');
	} else {
		$cMSG = S_MSG('KA26','Nomor Rekening sudah ada, tidak bisa digunakan lagi, silakan isi yang lain');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
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
	$qQUERY =SYS_QUERY($cQUERY);
	if(SYS_ROWS($qQUERY)==0){
		$cMSG= S_MSG('CB93','Kode Anggota tidak ditemukan');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}

	if($_POST['EDIT_LOAN_VAL']==0){
		$cMSG= S_MSG('KC52','Nilai pinjaman masih kosong');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:tr_payment.php');
	}

	$cQUERY ="update tr_loan1 set  ";
	$cQUERY.=" LOAN_CODE='$_POST[EDIT_LOAN_CODE]', LOAN_DATE='$cDATE', ";
	$cQUERY.=" NO_MEMBER='".$cKD_MEMBER."', ";
	$cQUERY.=" JN_BUNGA='".$_POST['UPD_JN_BUNGA']."', ";
	$cQUERY.=" LOAN_VAL=".str_replace(',', '', $_POST['EDIT_LOAN_VAL']).", BY_ASURANS=".str_replace(',', '', $_POST['EDIT_BY_ASURANSI']).", ";
	$cQUERY.=" BU_NGA=".str_replace(',', '', $_POST['EDIT_BUNGA']).", TE_NOR=".str_replace(',', '', $_POST['EDIT_TE_NOR']).", ";
	$cQUERY.=" BIAYA_PRV=".str_replace(',', '', $_POST['EDIT_BIAYA_PRV']).", BIAYA_ADM=".str_replace(',', '', $_POST['EDIT_BIAYA_ADM']).", ";
	$cQUERY.=" BIAYA_BLN=".str_replace(',', '', $_POST['EDIT_BIAYA_BLN']).", KD_AGUNAN='$_POST[EDIT_KODE_AGGN]', ";
	$cQUERY.=" UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cFILTER_CODE' and LOAN_ACT='$KODE_CRUD'";
	SYS_QUERY($cQUERY);

	header('location:kop_trans_pinjaman.php?');
	break;

case md5('DELete'):
	$TRM_NO_REK=$_GET['id'];
	$q_TRM_LOAN1=SYS_QUERY("select TRM_NO_REK from trm_loan1 where md5(TRM_NO_REK)='$TRM_NO_REK' and APP_CODE='$cFILTER_CODE' and DELETOR='' ");
	if (SYS_ROWS($q_TRM_LOAN1)==0) {
		$NOW = date("Y-m-d H:i:s");
		$cQUERY ="update tr_loan1 set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' ";
		$cQUERY.=" where APP_CODE='$cFILTER_CODE' and md5(LOAN_ACT)='$TRM_NO_REK'";
		SYS_QUERY($cQUERY);
		header('location:kop_trans_pinjaman.php');
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

</script>
