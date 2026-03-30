<?php
//	prs_tb_fac.php 
//  jurusan pendidikan

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$cHEADER = S_MSG('PB30','Tabel Jurusan');

	$qQUERY=SYS_QUERY("select * from prs_jrsn where APP_CODE='$cFILTER_CODE' and DELETOR=''");

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

	$cKODE_TBL 	= S_MSG('PB31','Kode Jurusan');
	$cNAMA_TBL 	= S_MSG('PB32','Nama Jurusan');
	$cNOTE_TBL 	= S_MSG('PB33','Keterangan');
	$cDAFTAR	= S_MSG('PB40','Daftar Jurusan');
	$cADD_REC	= S_MSG('PB41','Tambah Jurusan');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cEDIT_TBL	= S_MSG('PB42','Edit Tabel Jurusan');
	$cMSG_EXIST	= S_MSG('PB43','Kode Jurusan sudah ada');
	$cMSG_BLANK	= S_MSG('PB44','Kode Jurusan belum diisi');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');
	
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');
	$cTBL_LIST_DISPLAY_COLOR = S_PARA('_DISP_TABLE_LIST_FCOLOR','black');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
		DEF_WINDOW($cHEADER);
?>
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
										<a href="?_a=<?php echo md5('CREATE_EDU')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
									</li>
									<li>
										<a href="#help_prs_tb_education" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
												<th style="<?php echo $cHDR_BACK_CLR?>;width: 1px;"></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cKODE_TBL?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNAMA_TBL?></th>
												<th style="<?php echo $cHDR_BACK_CLR?>;"><?php echo $cNOTE_TBL?></th>
											</tr>
										</thead>

										<tbody>
											<?php
												while($a_PRS_JRSN=SYS_FETCH($qQUERY)) {
												echo '<tr>';
													echo '<td style="width: 1px;"></td>';
													echo "<td><span><a href='?_a=".md5('upd_edu')."&_e=".md5($a_PRS_JRSN['JRSN_CODE'])."'>".$a_PRS_JRSN['JRSN_CODE']."</a></span></td>";
													echo "<td><span><a href='?_a=".md5('upd_edu')."&_e=".md5($a_PRS_JRSN['JRSN_CODE'])."'>".$a_PRS_JRSN['JRSN_DESC']."</a></span></td>";
													echo '<td>'.decode_string($a_PRS_JRSN['JRSN_NOTE']).'</td>';
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
<?php
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		break;

case md5('CREATE_EDU'):
	DEF_WINDOW($cADD_REC);
?>
		<section id="main-content" class=" ">
			<section class="wrapper main-wrapper" style=''>

				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<div class="page-title">
						<div class="pull-left">
							<h2 class="title"><?php echo $cADD_REC?></h2>                            
						</div>
						<div class="pull-right hidden-xs"></div>
					</div>
				</div>
				<div class="clearfix"></div>

				<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					<section class="box ">
						<div class="content-body">
							<div class="row">
								<form action ="?_a=tambah" method="post">
									<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
										<input type="text" class="col-sm-2 form-label-900" name='ADD_JRSN_CODE' id="field-1">
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
										<input type="text" class="col-sm-6 form-label-900" name='ADD_JRSN_DESC' id="field-2">
										<div class="clearfix"></div><br>

										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNOTE_TBL?></label>
										<input type="text" class="col-sm-6 form-label-900" name='ADD_JRSN_NOTE' id="field-2">
										<div class="clearfix"></div><br>

										<div class="text-left">
											<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE?>>
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
<?php
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		break;

case md5('upd_edu'):
		$cQUERY ="select * from prs_jrsn
			where prs_jrsn.APP_CODE='$cFILTER_CODE' and md5(JRSN_CODE)='$_GET[_e]' and DELETOR=''";
		$qQUERY =SYS_QUERY($cQUERY);
		$REC_PERSONE=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
?>
		<section id="main-content" class=" ">
			<section class="wrapper main-wrapper" style=''>

				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<div class="page-title">

						<div class="pull-left">
							<h1 class="title"><?php echo $cEDIT_TBL?></h1>
						</div>
						<div class="pull-right hidden-xs">									 
							<ol class="breadcrumb">
								<li>
									<?php echo '<a href="?_a='.md5('del_edu').'&id='. md5($REC_PERSONE['JRSN_CODE']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
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
								<form action ="?_a=rubah&id=<?php echo $REC_PERSONE['JRSN_CODE']?>" method="post">
									<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
										<input type="text" class="col-sm-2 form-label-900" name='EDIT_JRSN_CODE' id="field-1" value=<?php echo $REC_PERSONE['JRSN_CODE']?> disabled="disabled">
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
										<input type="text" class="col-sm-6 form-label-900" name='EDIT_JRSN_DESC' id="field-2" value="<?php echo $REC_PERSONE['JRSN_DESC']?>">
										<div class="clearfix"></div><br>

										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNOTE_TBL?></label>
										<input type="text" class="col-sm-6 form-label-900" name='UPD_JRSN_NOTE' id="field-2" value="<?php echo $REC_PERSONE['JRSN_NOTE']?>">
										<div class="clearfix"></div><br>

										<div class="text-left">
											<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE?>>
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
<?php
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		break;

case 'tambah':
	$NOW = date("Y-m-d H:i:s");
	$cJRSN_CODE = encode_string($_POST['ADD_JRSN_CODE']);
	$cJRSN_DESC = encode_string($_POST['ADD_JRSN_DESC']);
	if($cJRSN_CODE==''){
		$cMSG = $cMSG_BLANK;
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	$cQUERY="select * from prs_jrsn where APP_CODE='$cFILTER_CODE' and DELETOR='' and JRSN_CODE='$_POST[ADD_JRSN_CODE]'";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)>0){
		$cMSG = $cMSG_EXIST;
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
		header('location:prs_tb_education.php');
	} else {
		$cQUERY="insert into prs_jrsn set JRSN_CODE='$cJRSN_CODE', JRSN_DESC='$cJRSN_DESC', JRSN_NOTE='$_POST[ADD_JRSN_NOTE]'
			ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW',
			APP_CODE='$_SESSION[data_FILTER_CODE]'";
		SYS_QUERY($cQUERY);
		header('location:prs_tb_education.php');
	}
	break;

case 'rubah':
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cJRSN_DESC = encode_string($_POST['EDIT_JRSN_DESC']);
	$cJRSN_NOTE = encode_string($_POST['UPD_JRSN_NOTE']);
	$cQUERY ="update prs_jrsn set JRSN_DESC='$cJRSN_DESC', JRSN_NOTE='$cJRSN_NOTE', 
		UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW'
		where APP_CODE='$cFILTER_CODE' and JRSN_CODE='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	$ADD_LOG	= APP_LOG_ADD();
	header('location:prs_tb_education.php');
	break;

case md5('del_edu'):
	$NOW = date("Y-m-d H:i:s");
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update prs_jrsn set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW' 
		where APP_CODE='$cFILTER_CODE' and md5(JRSN_CODE)='$KODE_CRUD'";
	$qQUERY = SYS_QUERY($cQUERY);
	header('location:prs_tb_education.php');
}
?>

