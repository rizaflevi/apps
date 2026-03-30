<?php
//	tb_team.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 	= $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Team Salesman.pdf';

	$cHEADER	= S_MSG('F153','Tabel Team Salesman');
	$cHAPUS		= S_MSG('F156','Delete Business Source');
	$cKODE_TBL	=S_MSG('F003','Kode');
	$cNAMA_TBL	=S_MSG('F004','Nama');
	$cDAFTAR	=S_MSG('F157','Daftar Team Salesman');
	$cADD_TBL	=S_MSG('F158','Tambah Team Salesman');
	$cEDIT_TBL	=S_MSG('F159','Edit Kode Team Salesman');

	$cSAVE_DATA	=S_MSG('F301','Save');
	$cCLOSE_DATA=S_MSG('F302','Close');

	$qQUERY=OpenTable('TbTeam');

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];
	$cHDR_BACK_CLR = S_PARA('_DISP_REPORT_HEAD_BCOLOR','background-color:LightGray');

switch($cACTION){
	default:
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
										<?php echo '<a href="?_a='.md5('cr34t3').'"> <i class="fa fa-plus-square"></i>'.S_MSG('KA11','Add new').'</a>'?>
									</li>
									<li>
										<a href="#help_tb_team" data-toggle="modal" > <i class="fa fa-question"></i>Help</a>
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
													echo '<td class=""><div class="star"><i class="fa fa-star-half-empty icon-xs icon-default"></i></div></td>';
													echo "<td><span><a href='?_a=".md5('up_date_team')."&_c=".md5($aREC_DISP['KODETEAM'])."'>".$aREC_DISP['KODETEAM']."</a></span></td>";
													echo "<td><span><a href='?_a=".md5('up_date_team')."&_c=".md5($aREC_DISP['KODETEAM'])."'>".$aREC_DISP['NAMATEAM']."</a></span></td>";
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
		SYS_DB_CLOSE($DB2);	break;

	case md5('cr34t3'):
		DEF_WINDOW($cHEADER);
?>
		<section id="main-content" class=" ">
			<section class="wrapper main-wrapper" style=''>

				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<div class="page-title">
						<div class="pull-left">
							<h1 class="title"><?php echo $cADD_TBL?></h1>                            
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
											<div class="form-group">
												<label class="form-label" for="field-1"><?php echo $cKODE_TBL?></label>
												<span class="desc"></span>
												<div class="controls">
													<input type="text" style="width:25%;" class="form-control" name='KODE_TEAM' id="field-1">
												</div>
											</div>

											<div class="form-group">
												<label class="form-label" for="field-1"><?php echo $cNAMA_TBL?></label>
												<span class="desc"></span>
												<div class="controls">
													<input type="text" class="form-control" name='NAMA_TEAM' id="field-2">
												</div>
											</div>

											<div class="text-left">
												<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
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
<?php
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('up_date_team'):
		$qQUERY=OpenTable('TbTeam', "APP_CODE='$cAPP_CODE' and md5(KODETEAM)='$_GET[_c]' and REC_ID not in ( select DEL_ID from logs_delete )");
		$REC_TEAM=SYS_FETCH($qQUERY);
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
									<?php echo '<a href="?_a='.md5('d3l_team').'&id='. md5($REC_TEAM['KODETEAM']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>' ?>	 
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
								<form action ="?_a=rubah&id=<?php echo $REC_TEAM['KODETEAM']?>" method="post"">
									<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
										<div class="form-group">
											<label class="form-label" for="field-1"><?php echo $cKODE_TBL?></label>
											<span class="desc"></span>
											<div class="controls">
												<input type="text" style="width:25%;" class="form-label-900" name='KD_TEAM' id="field-1" value=<?php echo $REC_TEAM['KODETEAM']?> disabled="disabled">
											</div>
										</div>

										<div class="form-group">
											<label class="form-label" for="field-1"><?php echo $cNAMA_TBL?></label>
											<span class="desc"></span>
											<div class="controls">
												<input type="text" style="width:75%;" class="form-label-900" name='UPD_NAMATEAM' id="field-2" value="<?php echo $REC_TEAM['NAMATEAM']?>">
											</div>
										</div>

										<div class="text-left">
											<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE_DATA?>>
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
<?php
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case "tambah":
	$cTEAM_CODE = encode_string($_POST['KODE_TEAM']);
	if($cTEAM_CODE=='') {
		$cNO_BLANK=S_MSG('F151','Kode Team Salesman tidak boleh kosong');
		echo "<script> alert('$cNO_BLANK');	window.history.back();	</script>";
		return;
	}
	$qQUERY=OpenTable('TbTeam', "APP_CODE='$cAPP_CODE' and md5(KODETEAM)='$cTEAM_CODE' and REC_ID not in ( select DEL_ID from logs_delete )");
	if(SYS_ROWS($qQUERY)==0){
		$cNAMA_TEAM = encode_string($_POST['NAMA_TEAM']);
		$cQUERY ="insert into team set APP_CODE='$cAPP_CODE', KODETEAM='$cTEAM_CODE', NAMATEAM='$cNAMA_TEAM'";
		$cQUERY.=", ENTRY='$cUSERCODE', REC_ID='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);
		header('location:tb_team.php');
	} else {
		$cEXIST=S_MSG('F152','Kode Team Salesman sudah ada');
		echo "<script> alert('$cEXIST');	window.history.back();	</script>";
		return;
	}
	break;

case "rubah":
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cNAMA_TEAM = encode_string($_POST['UPD_NAMATEAM']);
	$cQUERY ="update team set NAMATEAM='$cNAMA_TEAM', UP_DATE='$cUSERCODE', UPD_DATE='$NOW'";
	$cQUERY.=" where APP_CODE='$cAPP_CODE' and KODETEAM='$KODE_CRUD'";
	$X = SYS_QUERY($cQUERY);
	header('location:tb_team.php');
	break;

case md5('d3l_team'):
	$KODE_CRUD=$_GET['id'];
	$cQUERY ="update team set DELETOR='$cUSERCODE', DEL_DATE='$NOW'";
	$cQUERY.="where APP_CODE='$cAPP_CODE' and md5(KODETEAM)='$KODE_CRUD'";
	$X = SYS_QUERY($cQUERY);
	header('location:tb_team.php');
}
?>

