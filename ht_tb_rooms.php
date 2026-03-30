<?php
//	ht_tb_rooms.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cHELP_FILE = 'Doc/Tabel - Area.pdf';

	$nROOM_TYPE = 0;
	$qTB_RTYPE=OpenTable('HtRoomType', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$nROOM_TYPE = ($qTB_RTYPE ? SYS_ROWS($qTB_RTYPE) : 0);
	
	$qTB_ROOM=OpenTable('HtRoom', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cHEADER 		= S_MSG('H117','Tabel Nomor Kamar');
	$cKODE_TBL		= S_MSG('H110','Nomor Kamar');
	$cNAMA_TBL 		= S_MSG('H111','Nama Kamar');
	$cROOM_TYPE		= S_MSG('H112','Tipe Kamar');
	$cROOM_SECTION	= S_MSG('H113','Section');
	$cCONNECTION 	= S_MSG('H114', 'Sambung ke');
	$cPHONE_EXT		= S_MSG('H115', 'Extension Telpon');
	$cROOM_NOTE		= S_MSG('H116','Catatan');
	
	$cDATA_UMUM		= S_MSG('F024','Data Umum');
	$cFOTO_ROOM		= S_MSG('H130','Foto Kamar');
	$cAMENITIES		= S_MSG('H133','Amenities');
	
	$cEDIT_TBL		= S_MSG('H119','Edit Kamar');
	$cDAFTAR		= S_MSG('H132','Daftar Kamar');
	$cTTIP_ROOM_NO	= S_MSG('H120','Setiap Nomor Kamar di beri kode untuk memudahkan meng akses data');
	$cTTIP_ROOM_NM	= S_MSG('H122','Nama kamar sebagai penjelasa dari nomor kamar');
	$cTTIP_ROOM_TP	= S_MSG('H123','Tipe kamar');
	$cTTIP_ROOM_SC	= S_MSG('H124','Section/floor dari kamar');
	$cTTIP_ROOM_CN	= S_MSG('H125','Nomor kamar yang tersambung dengan kamar ini');
	$cTTIP_ROOM_EX	= S_MSG('H126','Extension telpon yang digunakan kamar ini');
	$cTTIP_ROOM_NT	= S_MSG('H127','Catatan tambahan mengenai kamar ini');

	$cSAVE_DATA		= S_MSG('F301','Save');
	$cCLOSE_DATA	= S_MSG('F302','Close');
	$can_CREATE = TRUST($cUSERCODE, 'HT_ROOMS_1ADD');

	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD($cHEADER);
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('create_room'). '"><i class="fa fa-plus-square"></i>'. $cADD_REC.'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL, $cCONNECTION]);
						echo '<tbody>';
							while($aREC_DISP=SYS_FETCH($qTB_ROOM)) {
								echo '<tr>';
									$cICON = 'fa-cube';
									echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
									echo "<td><span><a href='?_a=".md5('upd_room')."&_r=".$aREC_DISP['REC_ID']."'>".$aREC_DISP['ROOM_NO']."</a></span></td>";
									echo "<td><span><a href='?_a=".md5('upd_room')."&_r=".$aREC_DISP['REC_ID']."'>".$aREC_DISP['ROOM_DESC']."</a></span></td>";
									echo '<td>'.$aREC_DISP['RCONNECT'].'</td>';
								echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
	break;

	case md5('create_room'):
		$cADD_REC		= S_MSG('H118','Tambah Kamar');
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,3], '900', 'ADD_ROOM_NO', '', 'focus', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'NAMA_KAMAR', '', '', '', '', 0, '', 'fix', $cTTIP_ROOM_NM);
					LABEL([3,3,3,6], '700', $cROOM_TYPE);
					SELECT([3,3,3,6], 'ROOMTYPE');
						$qREC_RTYPE=OpenTable('HtRoomType', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
							echo "<option value=' '  > </option>";
							while($aREC_HT_RTYPE=SYS_FETCH($qREC_RTYPE)){
								echo "<option value='$aREC_HT_RTYPE[HTR_CODE]'  >$aREC_HT_RTYPE[HTR_DESC]</option>";
							}
					echo '</select>';
					CLEAR_FIX();

					LABEL([3,3,3,6], '700', $cROOM_SECTION);
					SELECT([3,3,3,6], 'ADD_ROOM_SECT', '', '', '', $cTTIP_ROOM_SC);
						$REC_LOCT=SYS_QUERY("select * from ht_locat where APP_CODE='$cAPP_CODE' and DELETOR=''");
						echo "<option value=' '  > </option>";
						while($aREC_ROOM_SECT=SYS_FETCH($REC_LOCT)){
							echo "<option value='$aREC_ROOM_SECT[LOC_CODE]'  >$aREC_ROOM_SECT[LOC_DESC]</option>";
						}
					echo '</select>';
					CLEAR_FIX();

					LABEL([3,3,3,6], '700', $cCONNECTION);
					INPUT('text', [2,2,2,2], '900', 'ADD_RCONNECT', '', '', '', '', 0, '', 'fix', $cTTIP_ROOM_CN);
					LABEL([3,3,3,6], '700', $cPHONE_EXT);
					INPUT('text', [2,2,2,2], '900', 'ADD_ROOM_EXT', '', '', '', '', 0, '', 'fix', $cTTIP_ROOM_EX);
					LABEL([3,3,3,6], '700', $cROOM_NOTE);
					INPUT('text', [6,6,6,6], '900', 'ADD_NOTE', '', '', '', '', 0, '', 'fix', $cTTIP_ROOM_NT);
					TDIV();
					?>
											<ul class="nav nav-tabs primary" style="width:100%">
												 <li class="active">
													  <a href="#TAB_AMEN" data-toggle="tab">	<i class="fa fa-home"></i> <?php echo $cAMENITIES?>	</a>
												 </li>
												 <li>
													  <a href="#foto-0" data-toggle="tab">	<i class="fa fa-cog"></i> <?php echo S_MSG('F017','Foto')?>	</a>
												 </li>
											</ul>

											<div class="tab-content primary">
												<div class="tab-pane fade in active" id="TAB_AMEN">	<br>

												</div>		<!-- End of Tab 1 of create -->
												
												<div class="tab-pane fade" id="foto-0">
												  <div class="form-group">
														<label class="form-label" for="field-1">Room Image</label>
														<span class="desc"></span>
														<img class="img-responsive" src="data/patients/patient-1.jpg" alt="" style="max-width:220px;">
														<div class="controls">
															 <input type="file" class="form-control" id="field-5">
														</div>
												  </div>
												</div>
<?php
					eTDIV();
					SAVE($cSAVE_DATA);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);
	break;

	case md5('upd_room'):
		$cHAPUS	= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$can_DELETE = TRUST($cUSERCODE, 'HT_ROOMS_3DEL');
		$cROOM = $_GET['_r'];
		$qTB_ROOM=OpenTable('HtRoom', "APP_CODE='$cAPP_CODE' and REC_ID='$cROOM'  and REC_ID not in ( select DEL_ID from logs_delete)");
		if(SYS_ROWS($qTB_ROOM)==0)	header('location:ht_tb_rooms.php');
		$REC_EDIT=SYS_FETCH($qTB_ROOM);
		$cFILE_FOTO_MEMBER = 'data/images_member/ROOM_'.$cROOM.'.jpg';
		if(file_exists($cFILE_FOTO_MEMBER)==0)	{
			$cFILE_FOTO_MEMBER = "data/images/no.jpg";
		}
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE ? ['<a href="?_a=delete&id='. $REC_EDIT['ROOM_NO']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&id='.$REC_EDIT['ROOM_NO'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'UPD_CODE', $REC_EDIT['ROOM_NO'], '', '', '', 0, 'disabled', 'fix', $cTTIP_ROOM_NO);
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [4,4,4,6], '900', 'NM_ROOM', $REC_EDIT['ROOM_DESC'], 'focus', '', '', 0, '', 'fix', $cTTIP_ROOM_NM);
					if($nROOM_TYPE > 0) {
						LABEL([3,3,3,6], '700', $cROOM_TYPE);
						echo '<select name="PILIH_TIPE" class="col-sm-6 form-label-900">';
							$qQUERY=SYS_QUERY("select * from ht_rtype where APP_CODE='$cAPP_CODE' and DELETOR=''");
							while($aREC_HT_RTYPE=SYS_FETCH($qQUERY)){
								if($REC_EDIT['ROOMTYPE']==$aREC_HT_RTYPE['HTR_CODE']){
									echo "<option value='$aREC_HT_RTYPE[HTR_CODE]' selected='$REC_EDIT[ROOMTYPE]' >$aREC_HT_RTYPE[HTR_DESC]</option>";
								} else {
									echo "<option value='$aREC_HT_RTYPE[HTR_CODE]'  >$aREC_HT_RTYPE[HTR_DESC]</option>";
								}
							}
						echo '</select>';
						CLEAR_FIX();
					}
					LABEL([3,3,3,6], '700', $cROOM_SECTION);
					?>

											<select name='PILIH_SECTION' class="col-sm-6 form-label-900">
											<?php 
												$qQUERY=SYS_QUERY("select * from ht_locat where APP_CODE='$cAPP_CODE' and DELETOR=''");
												while($aREC_ROOM_SECT=SYS_FETCH($qQUERY)){
													if($REC_EDIT['ROOM_SECT']==$aREC_ROOM_SECT['LOC_CODE']){
														echo "<option value='$aREC_ROOM_SECT[LOC_CODE]' selected='$REC_EDIT[ROOM_SECT]' >$aREC_ROOM_SECT[LOC_DESC]</option>";
													} else {	echo "<option value='$aREC_ROOM_SECT[LOC_CODE]'  >$aREC_ROOM_SECT[LOC_DESC]</option>";
													}
												}
											?>
											</select><br><div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-25"><?php echo $cCONNECTION?></label>
											<input type="text" class="col-sm-4 form-label-900" name='EDIT_RCONNECT' id="field-31" value=<?php echo $REC_EDIT['RCONNECT']?>><br><br>
											<div class="clearfix"></div>

											<label class="col-sm-3 form-label-700" for="field-31"><?php echo $cPHONE_EXT?></label>
											<input type="text" class="col-sm-2 form-label-900" name='UPD_PHONE_EXT' id="field-31" value=<?php echo $REC_EDIT['ROOM_EXT']?> title="<?php echo S_MSG('H126','Extension telpon yang digunakan kamar ini')?>"><br>
											<div class="clearfix"></div>
												
											<label class="col-sm-3 form-label-700" for="field-32"><?php echo $cROOM_NOTE?></label>
											<textarea class="col-sm-2 form-label-900" name="UPD_NOTE" cols="5" id="field-25" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 50px; width: 337px;" value="<?php echo $REC_EDIT['ROOMNOTE']?>"></textarea>
											<div class="clearfix"></div>

											<div class="col-sm-12">
												<h4> </br></h4>
												<ul class="nav nav-tabs primary">
													 <li>
														  <a href="#detail_u" data-toggle="tab">	<i class="fa fa-home"></i> <?php echo $cAMENITIES?>	</a>
													 </li>
													 <li>
														  <a href="#foto-1" data-toggle="tab">		<i class="fa fa-cog"></i> <?php echo $cFOTO_ROOM?>	</a>
													 </li>
												</ul>

												<div class="tab-content primary">
												
													<div class="tab-pane fade" id="detail_u"></div>
													<div class="tab-pane fade" id="foto-1">
														<div class="form-group">
															<span class="desc"></span>																	
															<img class="img-responsive" src="<?php echo $cFILE_FOTO_MEMBER?>" alt="" style="max-width:220px;">
															<div class="controls">	<input type="file" class="form-control" id="field-5">	</div>
														</div>
													</div>

												</div></br>

											</div>
<?php
					SAVE($cSAVE_DATA);
				TDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		APP_LOG_ADD( $cHEADER, 'view');
		SYS_DB_CLOSE($DB2);
	break;

case 'tambah':
	$cROOM_NO = ENCODE($_POST['ADD_ROOM_NO']);
	if($cROOM_NO=='') {
		$cMSG_BLANK		= S_MSG('H136','Kode Kamar tidak boleh kosong');
		echo "<script> alert('$cMSG_BLANK');	window.history.back();	</script>";
		return;

	}
	$cHEADER ='Add '.$cHEADER;
	$ADD_LOG	= APP_LOG_ADD();
	$cQUERY="select * from ht_room where APP_CODE='$cAPP_CODE' and ROOM_NO='$cROOM_NO' and DELETOR=''";
	$cCEK_KODE=SYS_QUERY($cQUERY);
	if(SYS_ROWS($cCEK_KODE)==0){
		$cROOM_DESC = ENCODE($_POST['NAMA_KAMAR']);
		$cROOM_EXT  = ENCODE($_POST['ADD_ROOM_EXT']);
		$cROOM_NOTE = ENCODE($_POST['ADD_NOTE']);
		$cQUERY ="insert into ht_room set APP_CODE='$cAPP_CODE', ROOM_NO='$cROOM_NO', ROOM_DESC='$cROOM_DESC', ";
		$cQUERY.=" ROOMTYPE='$_POST[ADD_ROOMTYPE]', ROOM_SECT='$_POST[ADD_ROOM_SECT]', ";
		$cQUERY.="RCONNECT='$_POST[ADD_RCONNECT]', AREA_CODE='$_POST[ADD_ROOM_SECT]', ";
		$cQUERY.="ROOM_EXT='$cROOM_EXT', ROOMNOTE='$cROOM_NOTE', ";
		$cQUERY.=" ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='".date('Y-m-d H:i:s')."'";
		SYS_QUERY($cQUERY);
		header('location:ht_tb_rooms.php');
	} else {
		$cMSG = $S_MSG('H135','Kode Kamar sudah ada');
		echo "<script> alert('$cMSG');	window.history.back();	</script>";
		return;
	}
	break;

case 'rubah':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cROOM_DESC = ENCODE($_POST['NM_ROOM']);
	$cROOM_EXT  = ENCODE($_POST['UPD_PHONE_EXT']);
	$cROOM_NOTE = ENCODE($_POST['UPD_NOTE']);
	$cQUERY ="update ht_room set ROOM_DESC='$cROOM_DESC', ";
	$cQUERY.=" ROOMTYPE='$_POST[PILIH_TIPE]', ";
	$cQUERY.="ROOM_SECT='$_POST[PILIH_SECTION]', ";
	$cQUERY.="RCONNECT='$_POST[EDIT_RCONNECT]', ";
	$cQUERY.="ROOM_EXT='$cROOM_EXT', ROOMNOTE='$cROOM_NOTE', ";
//	$cQUERY.="FOTO='$nama_file_unik', ";
	$cQUERY.="UP_DATE='$_SESSION[gUSERCODE]', UPD_DATE='$NOW' ";
	$cQUERY.="where APP_CODE='$cAPP_CODE' and ROOM_NO='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:ht_tb_rooms.php');
	break;

case 'delete':
	$KODE_CRUD=$_GET['id'];
	$NOW = date("Y-m-d H:i:s");
	$cQUERY ="update ht_room set DELETOR='$_SESSION[gUSERCODE]', DEL_DATE='$NOW'";
	$cQUERY.="where APP_CODE='$cAPP_CODE' and ROOM_NO='$KODE_CRUD'";
	SYS_QUERY($cQUERY);
	header('location:ht_tb_rooms.php');
}
?>
