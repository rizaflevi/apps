<?php
//	tb_sch_school.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHEADER = S_MSG('TS20','Tabel Sekolah');

	$qTB_SCH=OpenTable('SchSchool');

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

	$cKODE_TBL 	= S_MSG('TS21','Kode Sekolah');
	$cNAMA_TBL 	= S_MSG('TS22','Nama Sekolah');
	$cADD_REC	= S_MSG('KA11','Tambah');
	$cDAFTAR	= S_MSG('TS29','Daftar Sekolah');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cEDIT_TBL	= S_MSG('TS30','Edit Tabel Sekolah');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$cTTIP_KODE	= S_MSG('TS41','Setiap Sekolah diberi kode supaya bisa di akses berdasarkan kode');
	$cTTIP_NAMA	= S_MSG('TS42','Nama Sekolah sbg keterangan');
	
switch($cACTION){
	default:
        $can_CREATE = TRUST($cUSERCODE, 'TB_SCHOOL_1ADD');
        $cHELP_BOX	= S_MSG('TS5A','Help Tabel Data Sekolah');
		$cHELP_1	= S_MSG('TS5B','Ini adalah modul untuk memasukkan data sekolah-sekola yang ada.');
		$cHELP_2	= S_MSG('TS5C','Tabel ini diperlukan untuk pembagian murid berdasar sekolah yang ditempuh.');
		$cHELP_3	= S_MSG('TS5D','Untuk memasukkan data Sekolah baru, klik tambah / add new');
		DEF_WINDOW($cHEADER);
?>
		<section id="main-content" class=" ">
			<section class="wrapper main-wrapper" style=''>
				<div class="clearfix"></div>

				<div class="col-lg-12 col-xs-12">
					<section class="box ">
						<header class="panel_header">
							<h2 class="title pull-left"><?php echo $cHEADER?></h2>
							<div class="pull-right hidden-xs">
								<ol class="breadcrumb">
									<li>	<?php if ($can_CREATE==1)
										echo '<a href="?_a='. md5('cr34t3').'"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>';   ?>
									</li>
									<li>
										<a href="#help_tb_school" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
									</li>
								</ol>
							</div>
						</header>
						<div class="content-body">    
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<table id="example" class="<?php echo S_PARA('_DISP_TABLE_CLASS','display table table-hover table-condensed')?>" cellspacing="0" width="100%">
										<?php	echo THEAD([$cKODE_TBL, $cNAMA_TBL]);?>
										<tbody>
											<?php
												while($aTB_SCH=SYS_FETCH($qTB_SCH)) {
												echo '<tr>';
													echo '<td style="width: 1px;"></td>';
													echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aTB_SCH['SCH_CODE'])."'>".decode_string($aTB_SCH['SCH_CODE'])."</a></span></td>";
													echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aTB_SCH['SCH_CODE'])."'>".decode_string($aTB_SCH['SCH_NAME'])."</a></span></td>";
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
		HELP_MOD('help_tb_school', $cHELP_BOX, [$cHELP_1, $cHELP_2, $cHELP_3]);
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('cr34t3'):
		DEF_WINDOW($cADD_REC);
		TFORM($cADD_REC, '?_a=tambah');
?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
				<input type="text" class="col-sm-3 form-label-900" name='ADD_SCH_CODE' title="<?php echo $cTTIP_KODE?>" autofocus>
				<div class="clearfix"></div>

				<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
				<input type="text" class="col-sm-8 form-label-700" name='ADD_SCH_NAME' title="<?php echo $cTTIP_NAMA?>">
				<div class="clearfix"></div>

				<div class="text-left">
					<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE?>>
					<input type="button" class="btn" value=<?php echo $cCLOSE?> onclick=self.history.back()>
				</div>
			</div>
<?php
		eTFORM();
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('up_d4t3'):
        $can_UPDATE = TRUST($cUSERCODE, 'TB_SCHOOL_2UPD');
        $can_DELETE = TRUST($cUSERCODE, 'TB_SCHOOL_3DEL');
		$qTB_SCH=OpenTable('SchSchool', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and md5(SCH_CODE)='$_GET[_r]' ");
		$aTB_SCH=SYS_FETCH($qTB_SCH);
		DEF_WINDOW($cEDIT_TBL);
?>
		<section id="main-content" class=" ">
			<section class="wrapper main-wrapper" style=''>

				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<div class="page-title">

						<div class="pull-left">
								<h2 class="title"><?php echo $cEDIT_TBL?></h2>
						</div>
						<div class="pull-right hidden-xs">									 
							<ol class="breadcrumb">
								<li>    <?php if ($can_DELETE==1)
									echo '<a href="?_a='.md5('del_school').'&_id='. $aTB_SCH['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>
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
								<form action ="?_a=rubah&id=<?php echo $aTB_SCH['SCH_CODE']?>" method="post">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
										<input type="text" class="col-lg-2 col-sm-2 col-xs-6 form-label-900" name='EDIT_SCH_CODE' value=<?php echo $aTB_SCH['SCH_CODE']?> disabled="disabled" title="<?php echo $cTTIP_KODE?>">
										<div class="clearfix"></div>

										<label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
										<input type="text" class="col-lg-5 col-sm-8 col-xs-12 form-label-900" name='EDIT_SCH_NAME' value="<?php echo decode_string($aTB_SCH['SCH_NAME'])?>" title="<?php echo $cTTIP_NAMA?>" autofocus>
										<div class="clearfix"></div><br><br>

										<div class="text-left">
                                            <?php if ($can_UPDATE==1)
											    echo '<input type="submit" class="btn btn-primary" value='. $cSAVE.'>'; ?>
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
		SYS_DB_CLOSE($DB2);	break;

case 'tambah':
	$cSCH_CODE	= encode_string($_POST['ADD_SCH_CODE']);	
	if($cSCH_CODE==''){
		$cMSG_BLANK	= S_MSG('TS35','Kode Sekolah belum diisi');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$qTB_SCH=OpenTable('SchSchool', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and SCH_CODE='$cSCH_CODE' ");
	if(SYS_ROWS($qTB_SCH)>0){
		$cMSG_EXIST	= S_MSG('TS36','Kode Sekolah sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
	} else {
		$cSCH_NAME	= encode_string($_POST['ADD_SCH_NAME']);
		RecCreate('SchSchool', ['SCH_CODE', 'SCH_NAME', 'ENTRY', 'REC_ID', 'APP_CODE'], [$cSCH_CODE, $cSCH_NAME, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
	}
	APP_LOG_ADD($cHEADER, 'Add', '', '', $cSCH_CODE);
	header('location:tb_sch_school.php');
	break;

case 'rubah':
	$KODE_CRUD=$_GET['id'];
	$cSCH_NAME	= encode_string($_POST['EDIT_SCH_NAME']);
	RecUpdate('SchSchool', ['SCH_NAME'], [$cSCH_NAME], "APP_CODE='$cAPP_CODE' and SCH_CODE='$KODE_CRUD'");
	APP_LOG_ADD($cHEADER, 'Update', '', '', encode_string($cSCH_NAME));
	header('location:tb_sch_school.php');
	break;

case md5('del_school'):
	$KODE_CRUD=$_GET['_id'];
	RecSoftDel($KODE_CRUD);
	APP_LOG_ADD($cHEADER, 'Delete', '', $KODE_CRUD);
	header('location:tb_sch_school.php');
}
?>

