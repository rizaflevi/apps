<?php
//	tb_area.php //
//	Class for school

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = (IS_SCHOOL($cAPP_CODE) ? 'Doc/Tabel - Kelas.pdf' : 'Doc/Tabel - Area.pdf');
	$cHEADER = S_MSG('TA03','Tabel Area Pelanggan');

	$qTB_AREA=OpenTable('TbArea', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION='';
	$cACTION=(isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');

	$cKODE_TBL 	= S_MSG('TA01','Kode');
	$cNAMA_TBL 	= S_MSG('TA02','Nama');
	$cADD_REC	= S_MSG('TA06','Tambah');
	$cDAFTAR	= S_MSG('TA09','Daftar Tabel');
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cEDIT_TBL	= S_MSG('TA07','Edit Tabel Area');
	$cSAVE		= S_MSG('F301','Save');
	$cCLOSE		= S_MSG('F302','Close');

	$cTTIP_KODE	= S_MSG('TA0A','Setiap Area pelanggan diberi kode supaya bisa dikelompokkan');
	$cTTIP_NAMA	= S_MSG('TA04','Nama Area pelanggan sbg keterangan');
	
switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'TB_AREA_1ADD');
		DEF_WINDOW($cHEADER);
		$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('cr34t3'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
		TFORM($cHEADER, '', $aACT, $cHELP_FILE);
		TDIV();
			TABLE('example');
				THEAD([$cKODE_TBL, $cNAMA_TBL]);
				echo '<tbody>';
					while($aREC_TB_AREA=SYS_FETCH($qTB_AREA)) {
					echo '<tr>';
						echo '<td style="width: 1px;"></td>';
						echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_AREA['KODE_AREA'])."'>".decode_string($aREC_TB_AREA['KODE_AREA'])."</a></span></td>";
						echo "<td><span><a href='?_a=".md5('up_d4t3')."&_r=".md5($aREC_TB_AREA['KODE_AREA'])."'>".decode_string($aREC_TB_AREA['NAMA_AREA'])."</a></span></td>";
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

	case md5('cr34t3'):
		DEF_WINDOW($cADD_REC);
			TFORM($cADD_REC, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cKODE_TBL);
					INPUT('text', [2,2,2,6], '900', 'ADD_KODE_AREA', '', 'autofocus', '', '', 0, '', 'fix', $cTTIP_KODE);
					LABEL([3,3,3,6], '700', $cNAMA_TBL);
					INPUT('text', [8,8,8,6], '900', 'ADD_NAMA_AREA', '', '', '', '', 0, '', 'fix', $cTTIP_NAMA);
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('up_d4t3'):
		$can_DELETE = TRUST($cUSERCODE, 'TB_AREA_3DEL');
		$qTB_AREA=OpenTable('TbArea', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and md5(KODE_AREA)='$_GET[_r]' ");
		$REC_TB_AREA=SYS_FETCH($qTB_AREA);
		DEF_WINDOW($cEDIT_TBL);
		$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('del_area').'&_id='. $REC_TB_AREA['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
		TFORM($cEDIT_TBL, '?_a=rubah&_id='.$REC_TB_AREA['KODE_AREA'], $aACT, $cHELP_FILE);
		TDIV();
?>
			<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cKODE_TBL?></label>
			<input type="text" class="col-sm-2 form-label-900" name='EDIT_KODE_AREA' value=<?php echo $REC_TB_AREA['KODE_AREA']?> disabled="disabled" title="<?php echo $cTTIP_KODE?>">
			<div class="clearfix"></div>

			<label class="col-sm-4 form-label-700" for="field-1"><?php echo $cNAMA_TBL?></label>
			<input type="text" class="col-sm-8 form-label-900" name='EDIT_NAMA_AREA' value="<?php echo decode_string($REC_TB_AREA['NAMA_AREA'])?>" title="<?php echo $cTTIP_NAMA?>" autofocus>
			<div class="clearfix"></div><br>

			<div class="text-left">
				<input type="submit" class="btn btn-primary" value=<?php echo $cSAVE?>>
				<input type="button" class="btn" value=<?php echo $cCLOSE?> onclick=self.history.back()>
			</div>
<?php
		eTDIV();
		eTFORM();
		include "scr_chat.php";
		require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case 'tambah':
	$cKODE_AREA	= ENCODE($_POST['ADD_KODE_AREA']);	
	if($cKODE_AREA==''){
		MSG_INFO(S_MSG('H684','Kode Area belum diisi'));
		return;
	}
	$qTB_AREA=OpenTable('TbArea', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete) and KODE_AREA='$cKODE_AREA' ");
	if(SYS_ROWS($qTB_AREA)>0){
		MSG_INFO(S_MSG('H683','Kode Area sudah ada'));
		return;
	} else {
		$cNAMA_AREA	= ENCODE($_POST['ADD_NAMA_AREA']);
		RecCreate('TbArea', ['KODE_AREA', 'NAMA_AREA', 'ENTRY', 'REC_ID', 'APP_CODE'], [$cKODE_AREA, $cNAMA_AREA, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
	}
	APP_LOG_ADD($cHEADER, 'Add', '', '', $cKODE_AREA);
	header('location:tb_area.php');
	break;

case 'rubah':
	$KODE_CRUD=$_GET['_id'];
	$cNAMA_AREA	= ENCODE($_POST['EDIT_NAMA_AREA']);
	RecUpdate('TbArea', ['NAMA_AREA'], [$cNAMA_AREA], "APP_CODE='$cAPP_CODE' and KODE_AREA='$KODE_CRUD'");
	APP_LOG_ADD($cHEADER, 'Update', '', '', ENCODE($cNAMA_AREA));
	header('location:tb_area.php');
	break;

case md5('del_area'):
	$KODE_CRUD=$_GET['_id'];
	RecSoftDel($KODE_CRUD);
	APP_LOG_ADD($cHEADER, 'Delete', '', $KODE_CRUD);
	header('location:tb_area.php');
}
?>

