<?php
//	tb_provinsi.php
//	TODO : update detail UMP/UMK

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE']))  session_start();

	$cAPP_CODE = $_SESSION['data_FILTER_CODE']; $cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Provinsi.pdf';

	$qQUERY=OpenTable('TbProvince', "APP_CODE='$cAPP_CODE' and DELETOR=''");

	$cHEADER 	= S_MSG('CO00','Tabel Propinsi');
	$cKODE	    = S_MSG('CB02','Kode Propinsi');
	$cNM_PROV   = S_MSG('CB61','Nama Propinsi');
	$cDAFTAR	= S_MSG('CO34','Daftar Propinsi');
	$cADD_PROV	= S_MSG('CB68','Tambah Propinsi');
	$cEDIT_TBL	= S_MSG('CB69','Edit Propinsi');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cSAVE		= S_MSG('F301','Save');
	
	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];

switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'TB_PROVINCE_1ADD');
		$ADD_LOG	= APP_LOG_ADD('View', $cHEADER);
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('create_PROV'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$cKODE, $cNM_PROV]);
						echo '<tbody>';
							while($aREC_DISP=SYS_FETCH($qQUERY)) {
								echo '<tr>';
									echo '<td style="width: 1px;"></td>';
									echo "<td><span><a href='?_a=".md5('up__date')."&_p=".md5($aREC_DISP['id_prov'])."'>".$aREC_DISP['id_prov']."</a></span></td>";
									echo "<td><span><a href='?_a=".md5('up__date')."&_p=".md5($aREC_DISP['id_prov'])."'>".$aREC_DISP['nama']."</td>";
								echo '</tr>';
							}
						echo '</tbody>';
					eTABLE();
					TDIV();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
	break;

	case md5('create_PROV'):
		DEF_WINDOW($cADD_PROV);
			TFORM($cADD_PROV, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE);
					INPUT('text', [2,2,3,6], '900', 'KODE_PROV', '', 'autofocus', '', '', 0, '', 'Fix');
					LABEL([3,3,3,6], '700', $cNM_PROV);
					INPUT('text', [6,6,6,6], '900', 'NAMA_TIPE', '', '', '', '', 0, '', 'Fix');
					SAVE($cSAVE);
				TDIV();
			eTFORM();
		END_WINDOW();
	break;

	case md5('up__date'):
		$can_UPDATE = TRUST($cUSERCODE, 'TB_PROVINCE_2UPD');	$can_DELETE = TRUST($cUSERCODE, 'TB_PROVINCE_3DEL');
		$can_UPD_DTL = TRUST($cUSERCODE, 'TB_PROV_TDTL_UPD');	$can_VIEW_DTL = TRUST($cUSERCODE, 'TB_PROV_TDTL_VIEW');
		$can_UPD_UMP = TRUST($cUSERCODE, 'TB_PROV_TUMP_UPD');	$can_VIEW_UMP = TRUST($cUSERCODE, 'TB_PROV_TUMP_VIEW');
		$can_UPD_UMK = TRUST($cUSERCODE, 'TB_PROV_TUMK_UPD');	$can_VIEW_UMK = TRUST($cUSERCODE, 'TB_PROV_TUMK_VIEW');
		$aTAB=$aICON=$aCAPTION=$aTOOLTIP=[];
		if($can_UPD_UMP or $can_VIEW_UMP) {
			array_push($aTAB, 'UMP');
			array_push($aICON, 'fa-solid fa-shield-halved');
			array_push($aCAPTION, S_MSG('CO41','UMP'));
			array_push($aTOOLTIP, S_MSG('CO42','Data UMP'));
			array_push($aTAB, 'UMK');
			array_push($aICON, 'fa-light fa-shield-halved');
			array_push($aCAPTION, S_MSG('CO51','UMK'));
			array_push($aTOOLTIP, S_MSG('CO53','Data UMK tiap kabupaten'));
		}

		$cX_PROV= $_GET['_p'];
		$view_TAB_TUNJ=TRUST($cUSERCODE, 'TB_PROV_TUMK_VIEW'); $upd_TAB_TUNJ=TRUST($cUSERCODE, 'TB_PROV_TUMK_UPD');
		
		$cMSG_DEL	= S_MSG('F021','Benar data ini mau di hapus ?');
		$nUMP=0;
		$qQUERY=OpenTable('TbLocProvUmp', "APP_CODE='$cAPP_CODE' and DELETOR='' and md5(id_prov)='$cX_PROV'");
		if($aUMP=SYS_FETCH($qQUERY))
		$nUMP=($aUMP['UMP']);
		$qQUERY=OpenTable('TbProvince', "APP_CODE='$cAPP_CODE' and DELETOR='' and md5(id_prov)='$cX_PROV'");
		$aREC_PROV=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5("delProv").'&_id='. md5($aREC_PROV['id_prov']). '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a='.md5("saveProv").'&_id='.md5($aREC_PROV['id_prov']), $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE);
					INPUT('text', [2,2,3,6], '900', 'KODE_PROV', $aREC_PROV['id_prov'], '', '', '', 0, 'disable', 'Fix');
					LABEL([3,3,3,6], '700', $cNM_PROV);
					INPUT('text', [6,6,6,6], '900', 'NM_GRUP', $aREC_PROV['nama'], 'autofocus', '', '', 0, '', 'Fix');
					echo '<br><br>';
					TAB($aTAB, $aICON, $aCAPTION, $aTOOLTIP);
					echo '<div class="tab-content primary">';
						echo '<div class="tab-pane fade in active" id="UMP">';
							LABEL([3,3,3,6], '700', S_MSG('CO43','Nilai UMP'));
							INPUT('text', [2,2,2,6], '900', 'VAL_UMK', CVR($nUMP), 'autofocus', '', '', 0, '', 'Fix');
						eTDIV();
						echo '<div class="tab-pane fade" id="UMK">';
							TABLE('myTable');
								THEAD([S_MSG('CB82','Kabupaten'), S_MSG('CO51','UMK')], '', [0,1], '*', [8,2]);
								echo '<tbody>';
									$qDUMK=OpenTable('DistrictUMK', "A.id_prov='".$aREC_PROV['id_prov']."' and C.APP_CODE='$cAPP_CODE' and C.DIST_UMK is not null and (C.DELETOR is null or C.DELETOR='')");
									while($rDUMK=SYS_FETCH($qDUMK)) {
										echo '<tr>';
											echo "<td><span><a href='?_a=".md5('upd_dtl_UMK')."&_r=".md5($rDUMK['id_kab'])."'>". $rDUMK['kabupaten'].'</a></span></td>';
											echo '<td align="right">'.CVR($rDUMK['DIST_UMK']).'</td>';
										echo '</tr>';
									}
									SELECT([12,12,12,612], 'ADD_UMK', '', '', '', '', 'td');
										$qKAB=OpenTable('TbLocDistrict', "id_prov='".$aREC_PROV['id_prov']."'");
										echo "<option value=' '  > </option>";
										while($aKAB=SYS_FETCH($qKAB)){
											echo "<option class='form-label-900' value='$aKAB[id_kab]'  >$aKAB[kabupaten]</option>";
										}
									echo '</select></td>';
									INPUT('text', [12,12,12,12], '900', 'ADD_UMK', '', '', '', '', 0, '', 'fix', '', '', '', '', '', 'td');
								echo '<tbody>';
							echo '<table>';
						eTDIV();
					eTDIV();
					CLEAR_FIX();
					SAVE($can_UPDATE ? $cSAVE : '');
				eTDIV();
			eTFORM();
		END_WINDOW();
	break;

	case md5('upd_dtl_UMK'):
		$xKAB = $_GET['_r'];
		$cKAB = '';
		$nUMK = 0;
		$qDIST_UMK=OpenTable('DistrictUMK', "md5(id_kab)='$xKAB' and C.APP_CODE='$cAPP_CODE' and C.DIST_UMK is not null and ( C.DELETOR is null or C.DELETOR='')");
		// die ($qDIST_UMK);
		if($aDIST_UMK=SYS_FETCH($qDIST_UMK)) {
			$cKAB = $aDIST_UMK['id_kab'];
			$nUMK = $aDIST_UMK['DIST_UMK'];
		} else
			print_r2($xKAB);
		$cHEADER='Edit UMK';
		DEF_WINDOW($cHEADER);
			$aACT = ['<a href="?_a=DEL_UMK&_c='. $cKAB. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'];
			TFORM($cHEADER, '?_a=UPD_UMK&id='.$xKAB, $aACT, $cHELP_FILE);
				TDIV();
					LABEL([4,4,4,6], '700', S_MSG('CB82','Kabupaten'));
                	SELECT([5,5,5,6], 'UPD_DTL_KAB');
						$q_KAB=OpenTable('TbLocDistrict', "id_prov='$aDIST_UMK[id_prov]'");
						while($aKAB=SYS_FETCH($q_KAB)){
							if($aKAB['id_kab'] == $cKAB)
								echo "<option value='$aKAB[id_kab]' selected='$cKAB'>$aKAB[kabupaten]</option>";
							else 
								echo "<option value='$aKAB[id_kab]'  >$aKAB[kabupaten]</option>";
						}
					echo '</select><br>';
					LABEL([4,4,4,6], '700', S_MSG('CO5','UMK'));
					INPUT('text', [2,2,2,6], '900', 'UPD_DTL_UMK', $nUMK, '', 'fdecimal', 'right', 0, '', 'Fix');
					CLEAR_FIX();
					SAVE($cSAVE);
				eTDIV();
			eTFORM('*');
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	
	break;

	case 'DEL_UMK':
		$KODE_KAB=$_GET['_c'];
		RecUpdate('TbLocDistUmk', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and md5(id_prov)='".$KODE_KAB."'");
		echo "<script> window.history.go(-2);	</script>";
		// header('location:tb_provinsi.php?');
		SYS_DB_CLOSE($DB2);	
	break;

	case "tambah":
		$pKODE_PROV=$_POST['KODE_PROV'];
		if($pKODE_PROV=='') {
			MSG_INFO('**Kode provinsi tidak boleh kosong**');
			return;
		}
		$qQUERY=OpenTable('TbProvince', "APP_CODE='$cAPP_CODE' and DELETOR='' and id_prov='$pKODE_PROV'");
		if(SYS_ROWS($qQUERY)==0){
			RecCreate('TbProvince', ['id_prov', 'nama', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], [$pKODE_PROV, $_POST['NAMA_TIPE'], $cAPP_CODE, $_SESSION['gUSERCODE'], date("Y-m-d H:i:s")]);
			header('location:tb_provinsi.php');
		} else {
			MSG_INFO('Kode provinsi sudah ada');
			return;
		}
	break;

	case md5('saveProv'):
		$KODE_CRUD=$_GET['_id'];
		RecUpdate('TbProvince', ['nama', 'UP_DATE', 'UPD_DATE'], [$_POST['NM_GRUP'], $cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and md5(id_prov)='".$KODE_CRUD."'");
		header('location:tb_provinsi.php');
	break;

	case md5('delProv'):
		$KODE_CRUD=$_GET['_id'];
		RecUpdate('TbProvince', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and md5(id_prov)='".$KODE_CRUD."'");
		header('location:tb_provinsi.php');
	break;
}
?>

