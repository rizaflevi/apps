<?php
//	tb_sch_student.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHEADER = S_MSG('TS60','Data Siswa');

	$qTB_SCH=OpenTable('SchStudent', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

	$cKODE_TBL 	= S_MSG('F003','No. Induk');
	$cNAMA_TBL 	= S_MSG('TS62','Nama Siswa');
	$cADD_REC	= S_MSG('KA11','Tambah');
	$cDAFTAR	= S_MSG('TS69','Daftar Siswa');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cEDIT_TBL	= S_MSG('TS70','Edit Tabel Siswa');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$cTTIP_KODE	= S_MSG('TS71','Setiap Siswa diberi No. Induk supaya bisa di akses berdasarkan No. Induk');
	$cTTIP_NAMA	= S_MSG('TS72','Nama Siswa sbg keterangan');
	
switch($cACTION){
	default:
        $can_CREATE = TRUST($cUSERCODE, 'TB_STUDENT_1ADD');
        $cHELP_BOX	= S_MSG('TS9A','Help Tabel Data Siswa');
		$cHELP_1	= S_MSG('TS9B','Ini adalah modul untuk memasukkan data Siswa yang ada.');
		$cHELP_2	= S_MSG('TS9C','Data ini diperlukan untuk menyimpan seluruh data siswa untuk digunakn sistem.');
		$cHELP_3	= S_MSG('TS9D','Untuk memasukkan data Siswa baru, klik tambah / add new');
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
										<?php	echo THEAD([$cKODE_TBL, $cNAMA_TBL, 'Tingkat', 'Kelas', 'Sekolah']);?>
										<tbody>
											<?php
												while($aTB_SCH=SYS_FETCH($qTB_SCH)) {
												echo '<tr>';
													echo '<td style="width: 1px;"></td>';
													echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aTB_SCH['STUDENT_ID'])."'>".decode_string($aTB_SCH['STUDENT_ID'])."</a></span></td>";
													echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aTB_SCH['STUDENT_ID'])."'>".decode_string($aTB_SCH['STUDENT_NAME'])."</a></span></td>";
													echo "<td><span>".$aTB_SCH['LEVEL_NAME']."</span></td>";
													echo "<td><span>".$aTB_SCH['NAMA_AREA']."</span></td>";
													echo "<td><span>".$aTB_SCH['SCH_NAME']."</span></td>";
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
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label class="col-lg-3 col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
										<input type="text" class="col-sm-3 form-label-900" name='ADD_STD_CODE' title="<?php echo $cTTIP_KODE?>" autofocus>
										<div class="clearfix"></div><br>

										<label class="col-lg-3 col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
										<input type="text" class="col-sm-8 form-label-700" name='ADD_STUDENT_NAME' title="<?php echo $cTTIP_NAMA?>">
										<div class="clearfix"></div><br>

                                        <label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-1">Tingkatan</label>
                                        <select name="ADD_LEVEL" class="col-lg-3 col-sm-3 col-xs-6 form-label-900">
                                            <?php 
                                                echo "<option value=' '  ></option>";
                                                $qLEVEL=OpenTable('SchLevel', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
                                                while($aLEVEL=SYS_FETCH($qLEVEL)){
                                                    echo "<option value='$aLEVEL[LEVEL_CODE]'  >$aLEVEL[LEVEL_NAME]</option>";
                                                }
                                            ?>
                                        </select>
                                        <div class="clearfix"></div><br>

                                        <label class="col-lg-3 col-sm-4 form-label-700" for="field-1">Kelas</label>
                                        <select name="ADD_CLASS" class="col-lg-4 col-sm-3 col-xs-6 form-label-900">
                                            <?php 
                                                echo "<option value=' '  ></option>";
                                                $qCLASS=OpenTable('SchClass', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
                                                while($aCLASS=SYS_FETCH($qCLASS)){
                                                    echo "<option value='$aCLASS[KODE_AREA]'  >$aCLASS[NAMA_AREA]</option>";
                                                }
                                            ?>
                                        </select>
										<div class="clearfix"></div><br>

                                        <label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-1">Sekolah</label>
                                        <select name="ADD_SCHOOL" class="col-lg-3 col-sm-4 col-xs-6 form-label-900">
                                            <?php 
                                                echo "<option value=' '  ></option>";
                                                $qLEVEL=OpenTable('SchSchool', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
                                                while($aLEVEL=SYS_FETCH($qLEVEL)){
                                                    echo "<option value='$aLEVEL[SCH_CODE]'  >$aLEVEL[SCH_NAME]</option>";
                                                }
                                            ?>
                                        </select>
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
		SYS_DB_CLOSE($DB2);	break;

	case md5('up_d4t3'):
        $can_UPDATE = TRUST($cUSERCODE, 'TB_SCHOOL_2UPD');
        $can_DELETE = TRUST($cUSERCODE, 'TB_SCHOOL_3DEL');
		$qTB_SCH=OpenTable('SchStudent', "A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete) and md5(A.STUDENT_ID)='$_GET[_r]' ");
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
								<form action ="?_a=rubah&id=<?php echo $aTB_SCH['STUDENT_ID']?>" method="post">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
										<input type="text" class="col-lg-2 col-sm-2 col-xs-6 form-label-900" name='EDIT_STUDENT_ID' value=<?php echo $aTB_SCH['STUDENT_ID']?> disabled="disabled" title="<?php echo $cTTIP_KODE?>">
										<div class="clearfix"></div>

										<label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
										<input type="text" class="col-lg-5 col-sm-8 col-xs-12 form-label-900" name='EDIT_STUDENT_NAME' value="<?php echo decode_string($aTB_SCH['STUDENT_NAME'])?>" title="<?php echo $cTTIP_NAMA?>" autofocus>
										<div class="clearfix"></div><br>

                                        <label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-1">Tingkatan</label>
                                        <select name="UPD_LEVEL" class="col-lg-3 col-sm-3 col-xs-6 form-label-900">
                                            <?php 
                                                echo "<option value=' '  ></option>";
                                                $qLEVEL=OpenTable('SchLevel', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
                                                while($aLEVEL=SYS_FETCH($qLEVEL)){
                                                    if($aLEVEL['LEVEL_CODE']==$aTB_SCH['LEVEL_CODE'])
                                                        echo "<option value='$aTB_SCH[LEVEL_CODE]' selected='$aLEVEL[LEVEL_CODE]' >$aTB_SCH[LEVEL_NAME]</option>";
                                                    else echo "<option value='$aLEVEL[LEVEL_CODE]'  >$aLEVEL[LEVEL_NAME]</option>";
                                            
                                                }
                                            ?>
                                        </select>
                                        <div class="clearfix"></div><br>

                                        <label class="col-lg-3 col-sm-4 form-label-700" for="field-1">Kelas</label>
                                        <select name="UPD_CLASS" class="col-lg-4 col-sm-3 col-xs-6 form-label-900">
                                            <?php 
                                                echo "<option value=' '  ></option>";
                                                $qCLASS=OpenTable('TbArea', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
                                                while($aCLASS=SYS_FETCH($qCLASS)){
                                                    if($aCLASS['KODE_AREA']==$aTB_SCH['CLASS_CODE'])
                                                        echo "<option value='$aTB_SCH[CLASS_CODE]' selected='$aCLASS[KODE_AREA]' >$aTB_SCH[NAMA_AREA]</option>";
                                                    else echo "<option value='$aCLASS[KODE_AREA]'  >$aCLASS[NAMA_AREA]</option>";
                                                }
                                            ?>
                                        </select>
										<div class="clearfix"></div><br>

                                        <label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-1">Sekolah</label>
                                        <select name="UPD_SCHOOL" class="col-lg-3 col-sm-4 col-xs-6 form-label-900">
                                            <?php 
                                                echo "<option value=' '  ></option>";
                                                $qSCHOOL=OpenTable('SchSchool', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
                                                while($aSCHOOL=SYS_FETCH($qSCHOOL)){
                                                    if($aSCHOOL['SCH_CODE']==$aTB_SCH['SCHOOL_CODE'])
                                                        echo "<option value='$aTB_SCH[SCHOOL_CODE]' selected='$aSCHOOL[SCH_CODE]' >$aTB_SCH[SCH_NAME]</option>";
                                                    else echo "<option value='$aSCHOOL[SCH_CODE]'  >$aSCHOOL[SCH_NAME]</option>";
                                                }
                                            ?>
                                        </select>
                                        <div class="clearfix"></div><br>

										<br><div class="text-left">
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
	$cSTUDENT_ID	= encode_string($_POST['ADD_STD_CODE']);	
	if($cSTUDENT_ID==''){
		$cMSG_BLANK	= S_MSG('TS75','No. Induk Siswa belum diisi');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;
	}
	$qTB_SCH=OpenTable('SchStudent', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and STUDENT_ID='$cSTUDENT_ID' ");
	if(SYS_ROWS($qTB_SCH)>0){
		$cMSG_EXIST	= S_MSG('TS76','No. Induk Siswa sudah ada');
		echo "<script> alert('$cMSG_EXIST');	window.history.back();	</script>";
		return;
	} else {
		$cSTUDENT_NAME	= encode_string($_POST['ADD_STUDENT_NAME']);
		RecCreate('SchStudent', ['STUDENT_ID', 'STUDENT_NAME', 'STUDENT_LEVEL', 'CLASS_CODE', 'SCHOOL_CODE', 'ENTRY', 'REC_ID', 'APP_CODE'], 
            [$cSTUDENT_ID, $cSTUDENT_NAME, $_POST['ADD_LEVEL'], $_POST['ADD_CLASS'], $_POST['ADD_SCHOOL'], $cUSERCODE, NowMSecs(), $cAPP_CODE]);
	}
	APP_LOG_ADD($cHEADER, 'Add', '', '', $cSTUDENT_ID);
	header('location:tb_sch_student.php');
	break;

case 'rubah':
	$KODE_CRUD=$_GET['id'];
	$cSTUDENT_NAME	= encode_string($_POST['EDIT_STUDENT_NAME']);
	RecUpdate('SchStudent', ['STUDENT_NAME', 'STUDENT_LEVEL', 'CLASS_CODE', 'SCHOOL_CODE'], 
        [$cSTUDENT_NAME, $_POST['UPD_LEVEL'], $_POST['UPD_CLASS'], $_POST['UPD_SCHOOL']], 
        "APP_CODE='$cAPP_CODE' and STUDENT_ID='$KODE_CRUD'");
	APP_LOG_ADD($cHEADER, 'Update', '', '', encode_string($cSTUDENT_NAME));
	header('location:tb_sch_student.php');
	break;

case md5('del_school'):
	$KODE_CRUD=$_GET['_id'];
	RecSoftDel($KODE_CRUD);
	APP_LOG_ADD($cHEADER, 'Delete', '', $KODE_CRUD);
	header('location:tb_sch_student.php');
}
?>

