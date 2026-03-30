<?php
	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE']; $cUSERCODE = $_SESSION['gUSERCODE'];

	$qQUERY=OpenTable('TbCustType', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");

	$cACTION='';
	if (isset($_GET['_a'])) {
		$cACTION=$_GET['_a'];
	}
	$cHEADER 	= S_MSG('TT31','Tabel Tipe Pelanggan');
	$cKODE_TBL 	= S_MSG('TT03','Kode Tipe');
	$cNAMA_TBL	= S_MSG('TT05','Nama Tipe');
	$cEDIT_TBL	= S_MSG('TT39','Edit Tipe Anggota');

	$cSAVE=S_MSG('F301','Save');
	$cCLOSE=S_MSG('F302','Close');

	$cHELP_BOX	= S_MSG('TT33','Help Tabel Tipe Anggota');
	$cHELP_1	= S_MSG('TT34','Ini adalah modul untuk memasukkan data Tipe Anggota untuk keperluan pengelompokan');
	$cHELP_2	= S_MSG('TB33','Dengan pengelompokan ini akan memudahkan inquery data berdasarkan tipe anggota');
	$cHELP_3	= S_MSG('TB34','Untuk memasukkan data kode Tipe Anggota baru, klik tambah Tipe / add new');

	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
	DEF_WINDOW($cHEADER);
?>
		<section id="main-content" class=" ">
			<section class="wrapper main-wrapper">
				<div class="clearfix"></div>

				<div class="col-lg-12">
					<section class="box ">
						<header class="panel_header">
							<h2 class="title pull-left"><?php echo $cHEADER?></h2>
							<div class="pull-right">
								<ol class="breadcrumb">
									<li>
										<a href="?_a=<?php echo md5('create_tipe')?>"><i class="fa fa-plus-square"></i><?php echo S_MSG('KA11','Add new')?></a>
									</li>
									<li>
										<a href="#help_tb__tipe_customer" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
									</li>
								</ol>
							</div>
						</header>
						<div class="content-body">    
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">

									<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>">
										<?php echo THEAD([$cKODE_TBL, $cNAMA_TBL]);?>
										<tbody>
											<?php
												while($aREC_DISP=SYS_FETCH($qQUERY)) {
												echo '<tr>';
													echo '<td style="width: 1px;"></td>';
													echo "<td><span><a href='?_a=".md5('upd_tipe')."&_t=".md5($aREC_DISP['KODE_TIPE'])."'>".$aREC_DISP['KODE_TIPE']."</a></span></td>";
													echo "<td><span><a href='?_a=".md5('upd_tipe')."&_t=".md5($aREC_DISP['KODE_TIPE'])."'>".$aREC_DISP['NAMA_TIPE'].'</td>';
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
	HELP_MOD('help_tb__tipe_customer', $cHELP_BOX, [$cHELP_1, $cHELP_2, $cHELP_3]);
    END_WINDOW();
	break;

	case md5('create_tipe'):
		$cHEADER 	= S_MSG('TT3A','Tambah Tipe');
		DEF_WINDOW($cHEADER);
?>
		<section id="main-content" class=" ">
			<section class="wrapper main-wrapper">

				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<div class="page-title">

						<div class="pull-left">
								<h2 class="title"><?php echo $cHEADER?></h2>	
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
										<input type="text" class="col-sm-2 form-label-900" name='KODE_TIPE' id="field-1">
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
										<input type="text" class="col-sm-6 form-label-900" name='NAMA_TIPE' id="field-2">
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
		
	case md5('upd_tipe'):
		$cKODE_TIPE= $_GET['_t'];
		
		$qQUERY=OpenTable('TbCustType', "md5(KODE_TIPE)='$cKODE_TIPE' and APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
		$REC_TIPE=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
?>
		<section id="main-content" class=" ">
			<section class="wrapper main-wrapper">

				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<div class="page-title">

							<div class="pull-left">
									<h1 class="title"><?php echo $cEDIT_TBL?></h1>	
							</div>

							<div class="pull-right">
								<ol class="breadcrumb">
									<li>
										<a href="?_a=<?php echo md5('DELETE_TIPE')?>&id=<?php echo md5($REC_TIPE['REC_ID'])?>" onClick="return confirm('Apakah Anda benar-benar mau menghapusnya?')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>
									</li>
									<li>
										<a href="#help_upd_tb_tipe_customer" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
								<form action ="?_a=rubah&ktipe=<?php echo $REC_TIPE['KODE_TIPE']?>" method="post"  onSubmit="return CEK_TIPE(this)">
									<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
										<input type="text" class="col-sm-2 form-label-900" name='KD_TIPE' id="field-1" value=<?php echo $REC_TIPE['KODE_TIPE']?> disabled="disabled">
										<div class="clearfix"></div>

										<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
										<input type="text" class="col-sm-6 form-label-900" name='NM_TIPE' id="field-2" value="<?php echo $REC_TIPE['NAMA_TIPE']?>">
										<div class="clearfix"></div><br>

										<div class="text-left">
											<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE?> onclick="UPD_TIPE($cKODE_TIPE)">
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
	HELP_MOD('help_upd_tb_tipe_customer', $cHELP_BOX, ['']);
    END_WINDOW();
	break;

case 'tambah':
	$NOW = date("Y-m-d H:i:s");
	$pKODE_TIPE=encode_string($_POST['KODE_TIPE']);
	if($pKODE_TIPE=='') {
		MSG_INFO(S_MSG('TT16','Kode Tipe Customer tidak boleh kosong'));
		return;
	}
	
	$qQUERY=OpenTable('TbCustType', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	if($qQUERY){
		if(SYS_ROWS($qQUERY)>0) {
			MSG_INFO(S_MSG('TT38','Kode tipe pelanggan sudah ada'));
			return;
		}
	}
	$cNAMA_TIPE=encode_string($_POST['NAMA_TIPE']);
	$nRec_id = date_create()->format('Uv');
	$cRec_id = (string)$nRec_id;
	RecCreate('TbCustType', ['KODE_TIPE', 'NAMA_TIPE', 'APP_CODE', 'ENTRY', 'REC_ID'],
		[$pKODE_TIPE, $cNAMA_TIPE, $cAPP_CODE, $cUSERCODE, $cRec_id]);
	header('location:tb_tipe_customer.php');
break;

case "rubah":
	$NOW = date("Y-m-d H:i:s");
	$TIPE_CRUD=$_GET['ktipe'];
	$cNAMA_TIPE=encode_string($_POST['NM_TIPE']);
	RecUpdate('TbCustType', ['NAMA_TIPE'], [$cNAMA_TIPE], "APP_CODE='$cAPP_CODE' and KODE_TIPE='$TIPE_CRUD'");
	header('location:tb_tipe_customer.php');
	break;

case md5('DELETE_TIPE'):
	RecSoftDel($_GET['_id']);
	header('location:tb_tipe_customer.php');
}
?>

