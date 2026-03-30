<?php
//	prs_tb_lokasi.php //
//	Tabel Lokasi penempatan karyawan outsourcing

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];	
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Lokasi.pdf';

	$cHEADER = S_MSG('PE61','Tabel Lokasi');

	$can_CREATE = TRUST($cUSERCODE, 'PRS_LOCS_1ADD');
	$can_UPDATE = TRUST($cUSERCODE, 'PRS_LOCS_2UPD');

	$cACTION='';
	if (isset($_GET['_a'])) 	$cACTION=$_GET['_a'];
	$cKODE_TBL 	= S_MSG('F003','Kode');
	$cNAMA_TBL 	= S_MSG('PE62','Lokasi');
	$cCATATAN 	= S_MSG('F019','Catatan');
	$cADD_NEW	= S_MSG('PE63','Tambah Lokasi');
	$cEDIT_LOKS	= S_MSG('PE64','Edit Lokasi');
	$cDAFTAR	= S_MSG('PE60','Daftar Lokasi Penempatan');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');

	$cSAVE	= S_MSG('F301','Save');
	$cCLOSE	= S_MSG('F302','Close');

switch($cACTION){
	default:
		$ADD_LOG	= APP_LOG_ADD();
        RecUpdate('sys_msg', ['MESSG_DATE'], [date("Y-m-d h:m:s")], 'true order by MESSG_DATE limit 1');

		$qQUERY=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''");
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('CREATE_LOKASI'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE_TBL, $cNAMA_TBL, $cCATATAN]);
							echo '<tbody>';
								while($aREC_KODE_LOKS=SYS_FETCH($qQUERY)) {
								echo '<tr>';
									echo '<td style="width: 1px;"></td>';
									echo "<td><span><a href='?_a=".md5('update')."&_c=$aREC_KODE_LOKS[LOKS_CODE]'>".$aREC_KODE_LOKS['LOKS_CODE']."</a></span></td>";
									echo "<td><span><a href='?_a=update&_c=$aREC_KODE_LOKS[LOKS_CODE]'>".DECODE($aREC_KODE_LOKS['LOKS_NAME'])."</a></span></td>";
									echo "<td><span><a>".$aREC_KODE_LOKS['LOKS_NOTE']."</a></span></td>";
								echo '</tr>';
								// $aCOL =[$aREC_KODE_LOKS['LOKS_CODE'], DECODE($aREC_KODE_LOKS['LOKS_NAME']), $aREC_KODE_LOKS['LOKS_NOTE']];
								// $cREFF="<a href=?_a=update&_c=$aREC_KODE_LOKS[LOKS_CODE]";
								// TDETAIL($aCOL, [], '', [$cREFF, $cREFF, $cREFF]);
								}
							echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('CREATE_LOKASI'):
		DEF_WINDOW($cADD_NEW);
			TFORM($cADD_NEW, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,3], '900', 'ADD_LOKS_CODE', 0, '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'ADD_LOKS_NAME', 0, '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cCATATAN);
					INPUT('text', [6,6,6,6], '900', 'ADD_LOKS_NOTE', 0, '', '', '', 0, '', 'fix');
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('update'):
		$cGET_LOC = $_GET['_c'];
		$can_DELETE = TRUST($cUSERCODE, 'PRS_LOCS_3DEL');
		$can_UPDGEO = TRUST($cUSERCODE, 'PRS_LOCS_GEO_UPD');
		$cHAPUS			= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
		$qQUERY=OpenTable('PrsLocation', "LOKS_CODE='$cGET_LOC' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		$REC_PRS_LOKS=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_LOKS);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a=delete&id='. $REC_PRS_LOKS['LOKS_CODE']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_LOKS, '?_a=rubah&id='.$REC_PRS_LOKS['LOKS_CODE'], $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'EDIT_LOKS_CODE', $REC_PRS_LOKS['LOKS_CODE'], '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [6,6,6,6], '900', 'EDIT_LOKS_NAME', $REC_PRS_LOKS['LOKS_NAME'], '', '', '', 0, '', 'fix');
					LABEL([3,3,3,6], '700', $cCATATAN);
					INPUT('text', [6,6,6,6], '900', 'EDIT_LOKS_NOTE', $REC_PRS_LOKS['LOKS_NOTE'], '', '', '', 0, '', 'fix');
					echo '<h4> <br></h4>';
					if ($can_UPDGEO) {
						TAB(['TabUpdGeo', 'TabPatrolCek'], ['fa-user', 'fa-map-marker'], [S_MSG('PE8C','Geofence'), 'Patrol Cek']);
							echo '<div class="tab-pane fade in active" id="TabUpdGeo">';
								TABLE('example');
									THEAD([S_MSG('PG7A','Lokasi absen')], '', [], []);
									echo '<tbody>';
										$qGEO=OpenTable('PrsLocNameGeo', "A.LOCS_CODE='$REC_PRS_LOKS[LOKS_CODE]' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''", '', "B.GEO_NAME");
											while($aGEO=SYS_FETCH($qGEO)) {
												echo "<tr><td><span><a href='?_a=".md5('edit_geo')."&_r=".md5($aGEO['REC_ID'])."'>". DECODE($aGEO['GEO_NAME'])."</a></span></td></tr>";
											}
											CLEAR_FIX();
										echo '</tbody>';
								eTABLE();
								SELECT([12,12,12,12], 'ADD_GEOFENCE', '', '', 'select2');
									echo '<option value="" selected></<option>';
										$qREC_GEOF=OpenTable('TbGeofence', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'GEO_NAME');
										while($aREC_GEOF=SYS_FETCH($qREC_GEOF)){
											echo "<option value='$aREC_GEOF[GEO_CODE]'  >".DECODE($aREC_GEOF['GEO_NAME'])."</option>";
										}
								echo '</select><br>';
							eTDIV();
							CLEAR_FIX();
							echo '<div class="tab-pane fade" id="TabPatrolCek">';
								TABLE('example');
									THEAD(['Lokasi Patrol Cek'], '', [], []);
									echo '<tbody>';
										$qGEO=OpenTable('TbPatrolCard', "LOC_ID='$REC_PRS_LOKS[LOKS_CODE]' and APP_CODE='$cAPP_CODE' and REC_ID  not in ( select DEL_ID from logs_delete )", '', "POINT_DESC");
											while($aCARD=SYS_FETCH($qGEO)) {
												echo "<tr>
													<td><span><a href='?_a=".md5('edit_card')."&_r=".md5($aCARD['REC_ID'])."'>". DECODE($aCARD['POINT_DESC'])."</a></span></td>
												</tr>";
											}
											CLEAR_FIX();
										echo '</tbody>';
								eTABLE();
								INPUT('text', [6,6,6,6], '900', 'EDIT_CARD', '', '', '', '', 0, '', 'fix');
							eTDIV();
					}
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('edit_geo'):
		$can_DELETE = TRUST($cUSERCODE, 'PRS_LOCS_3DEL');
		$xREC_ID = $_GET['_r'];
		$qQUERY=OpenTable('PrsLocNameGeo', "md5(A.REC_ID)='$xREC_ID'");
		$aREC_GEO=SYS_FETCH($qQUERY);
		$cID=$aREC_GEO['REC_ID'];
		$cCODE=$aREC_GEO['GEO_CODE'];
		DEF_WINDOW($cEDIT_LOKS);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_dtl').'&_id='. $cID. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_LOKS, '?_a=up_date&_id='.$cID, $aACT, $cHELP_FILE);
				TDIV();
					SELECT([12,12,12,12], 'UPD_GEOFENCE', '', '', 'select2');
						echo '<option value="" selected></<option>';
							$qREC_GEOF=OpenTable('TbGeofence', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'GEO_NAME');
							while($aREC_GEOF=SYS_FETCH($qREC_GEOF)){
								if($aREC_GEOF['GEO_CODE']==$cCODE){
									echo "<option value='$aREC_GEOF[GEO_CODE]' selected='$aREC_GEOF[GEO_CODE]' >".DECODE($aREC_GEOF['GEO_NAME'])."</option>";
								} else {
									echo "<option value='$aREC_GEOF[GEO_CODE]'  >".DECODE($aREC_GEOF['GEO_NAME'])."</option>";
								}
							}
					echo '</select><br><br><br>';
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case md5('del_dtl'):
		$KODE_CRUD=$_GET['_id'];
		RecUpdate('PrsLocGeo', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, date("Y-m-d H:i:s")], "REC_ID='$KODE_CRUD'");
		APP_LOG_ADD($cHEADER, 'Delete', '', '', ENCODE($KODE_CRUD));
		header('location:prs_tb_lokasi.php');
	break;

	case 'up_date':
		$KODE_CRUD=$_GET['_id'];
		$cKODE_GEO	= ENCODE($_POST['UPD_GEOFENCE']);
		RecUpdate('PrsLocGeo', ['GEO_CODE'], [$cKODE_GEO], "APP_CODE='$cAPP_CODE' and REC_ID='$KODE_CRUD'");
		APP_LOG_ADD($cHEADER, 'Update', '', '', ENCODE($cKODE_GEO));
		header('location:prs_tb_lokasi.php');
	break;

	case "delete":
		$KODE_CRUD=$_GET['id'];
		RecUpdate('PrsLocation', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and LOKS_CODE='$KODE_CRUD'");
		APP_LOG_ADD($cHEADER, 'Delete', '', '', ENCODE($KODE_CRUD));
		header('location:prs_tb_lokasi.php');
	break;
		
	case "rubah":
		$NOW = date("Y-m-d H:i:s");
		$KODE_CRUD=$_GET['id'];
		$cNAMA_LOKS	= ENCODE($_POST['EDIT_LOKS_NAME']);
		RecUpdate('PrsLocation', ['LOKS_NAME', 'LOKS_NOTE', 'UP_DATE', 'UPD_DATE'], [$cNAMA_LOKS, $_POST['EDIT_LOKS_NOTE'], $_SESSION['gUSERCODE'], $NOW], "APP_CODE='$cAPP_CODE' and LOKS_CODE='$KODE_CRUD'");
		$cPOS_GEO = $_POST['ADD_GEOFENCE'];
		if(isset($cPOS_GEO)) {
			$nRec_id = date_create()->format('Uv');
			$cRec_id = (string)$nRec_id;
			RecCreate('PrsLocGeo', ['REC_ID', 'LOCS_CODE', 'GEO_CODE', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], 
				[$cRec_id, $KODE_CRUD, $cPOS_GEO, $cUSERCODE, date("Y-m-d H:i:s"), $cAPP_CODE]);
			APP_LOG_ADD($cHEADER, 'Update', '', '', ENCODE($KODE_CRUD));
			echo "<script> window.history.back();	</script>";
		}
		header('location:prs_tb_lokasi.php');
	break;

	case "tambah":
		$NOW = date("Y-m-d H:i:s");
		if($_POST['ADD_LOKS_CODE']==''){
			MSG_INFO(S_MSG('PE69','Kode Lokasi belum diisi'));
			return;
		}
		$qCEK_KODE=OpenTable('PrsLocation', "LOKS_CODE='$_POST[ADD_LOKS_CODE]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		if(SYS_ROWS($qCEK_KODE)>0){
			MSG_INFO(S_MSG('PE68','Kode Lokasi sudah ada'));
			header('location:prs_tb_lokasi.php');
			return;
		} else {
			$cKODE_LOKS	= ENCODE($_POST['ADD_LOKS_CODE']);
			$cNAMA_LOKS	= ENCODE($_POST['ADD_LOKS_NAME']);
			$cNOTE_LOKS	= ENCODE($_POST['ADD_LOKS_NOTE']);
			RecCreate('PrsLocation', ['LOKS_CODE', 'LOKS_NAME', 'LOKS_NOTE', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'], [$cKODE_LOKS, $cNAMA_LOKS, $cNOTE_LOKS, $cUSERCODE, $NOW, $cAPP_CODE]);
			APP_LOG_ADD($cHEADER, 'Add', '', '', ENCODE($KODE_CRUD));
			header('location:prs_tb_lokasi.php');
		}
}
?>

