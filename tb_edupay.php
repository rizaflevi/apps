<?php
//	tb_edupay.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Edupay.pdf';
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cHEADER 	= 'Table Edupay';
	$can_CREATE = 1;

	$qQUERY=OpenTable('SchRevAr', "A.NON_VALUE=0 and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];
	$cCOLUMN_CODE 	= 'Kolom';
	$cDESCRIPTION   = 'Keterangan';
	$cREVENUE       = 'Account Pendapatan';
	$cRECEIVABLE    = 'Account Piutang';
	$cADD_NEW		= 'Add new';
	$cEDIT_TBL		= 'Edit';

	$cSAVE		    = S_MSG('F301','Save');
	$cCLOSE		    = S_MSG('F302','Close');

	$cTTIP_COL		= 'Kolom di excel yang di upload ke edupay';
	$cTTIP_DESC	    = 'Keterangan';
	$cTTIP_REV	    = 'Account untuk pendapatan, yang meng kredit transaksi';
	$cTTIP_AR	    = 'Account untuk piutang, yang men debet transaksi';

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'view');
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('CREATE_NEW'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
			TDIV();
				TABLE('example');
					THEAD([$cCOLUMN_CODE, $cDESCRIPTION, $cRECEIVABLE, $cREVENUE]);
					echo '<tbody>';
						while($aREC_EDUPAY=SYS_FETCH($qQUERY)) {
							echo '<tr>';
								echo '<td class=""><div class="star"><i class="fa fa-eur icon-xs icon-default"></i></div></td>';
								echo "<td><span><a href='?_a=update&_r=$aREC_EDUPAY[REV_COLUMN]'>".$aREC_EDUPAY['REV_COLUMN']."</a></span></td>";
								echo "<td><span><a href='?_a=update&_r=$aREC_EDUPAY[REV_COLUMN]'>".DECODE($aREC_EDUPAY['DESCRIPTION'])."</a></span></td>";
								echo "<td>".DECODE($aREC_EDUPAY['ACCT_NAME_AR'])."</td>";
								echo "<td>".DECODE($aREC_EDUPAY['ACCT_NAME_REV'])."</td>";
							echo '</tr>';
						}
					echo '</tbody>';
				eTABLE();
			eTDIV();
			eTFORM('*');
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case md5('CREATE_NEW'):
		DEF_WINDOW($cADD_NEW);
			TFORM($cADD_NEW, '?_a=tambah', [], $cHELP_FILE);
				TDIV();
				LABEL([3,3,3,6], '700', $cCOLUMN_CODE);
				INPUT('text', [1,2,2,6], '900', 'ADD_COLUMN', '', '', '', '', 0, '', 'fix', $cTTIP_COL);
				LABEL([3,3,3,6], '700', $cDESCRIPTION);
				INPUT('text', [6,6,6,6], '900', 'ADD_DESC', '', '', '', '', 0, '', 'fix', $cTTIP_DESC);
?>
				<div class="form-group">
					<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cRECEIVABLE?></label>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<select name="ADD_AR_ACCOUNT" class="form-label-900 select2" title="<?php echo $cTTIP_AR?>">
					<?php 
						echo "<option value=' '  > </option>";
						$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and GENERAL='D' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
							echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
						}
					?>
					</select>
				</div>
				<div class="clearfix"></div><br>

				<div class="form-group">
					<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cREVENUE?></label>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<select name="ADD_REV_ACCOUNT" class="form-label-900 select2" title="<?php echo $cTTIP_REV?>">
					<?php 
						echo "<option value=' '  > </option>";
						$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and GENERAL='D' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
							echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
						}
					?>
					</select>
				</div>
				<div class="clearfix"></div><br>
<?php
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

	case "update":
		$can_UPDATE = 1;
		$can_DELETE = 1;
		$qQUERY=OpenTable('SchRevAr', "A.APP_CODE='$cAPP_CODE' and A.REV_COLUMN='$_GET[_r]' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		$aREC_AR_REV=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DELETE_REC').'&_id='. $aREC_AR_REV['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_id='.md5($aREC_AR_REV['REC_ID']), $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $cCOLUMN_CODE);
					INPUT('text', [2,2,2,3], '900', 'EDIT_COLUMN', $aREC_AR_REV['REV_COLUMN'], '', '', '', 0, 'disable', 'fix');
					LABEL([3,3,3,6], '700', $cDESCRIPTION);
					INPUT('text', [6,6,6,6], '900', 'UPD_DESC', $aREC_AR_REV['DESCRIPTION'], '', '', '', 0, '', 'fix', $cTTIP_DESC);
?>
					<br><div class="form-group">
						<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cRECEIVABLE?></label>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<select name="UPD_AR_ACCOUNT" class="col-sm-5 col-xs-12 form-label-900 select2" title="<?php echo $cTTIP_AR?>">
						<?php 
							echo "<option value=' '  > </option>";
							$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and GENERAL='D' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
								if($aREC_AR_REV['AR_ACCOUNT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
									echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$aREC_AR_REV[AR_ACCOUNT]' >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
								} else {
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>"; }
							}
						?>
						</select>
					</div>
					<div class="clearfix"></div><br>

					<div class="form-group">
						<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cREVENUE?></label>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<select name="UPD_REV_ACCOUNT" class="col-sm-5 col-xs-12 form-label-900 select2" title="<?php echo $cTTIP_AR?>">
						<?php 
							echo "<option value=' '  > </option>";
							$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and GENERAL='D' and REC_ID not in ( select DEL_ID from logs_delete)");
							while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
								if($aREC_AR_REV['REV_ACCOUNT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
									echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$aREC_AR_REV[REV_ACCOUNT]' >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
								} else {
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>"; }
							}
						?>
						</select>
					</div>
					<div class="clearfix"></div><br>
<?php
					SAVE($cSAVE);
				eTDIV();
			eTFORM();
			include "scr_chat.php";
			require_once("js_framework.php");
		END_WINDOW();
		SYS_DB_CLOSE($DB2);	break;

case md5('DELETE_REC'):
	$KODE_CRUD=$_GET['_id'];
	RecSoftDel($KODE_CRUD);
	APP_LOG_ADD($cHEADER, 'delete : '.$KODE_CRUD);
	header('location:tb_edupay.php');
	break;
	
	
case "rubah":
	$cREC_ID=$_GET['_id'];
	$cDESC = $_POST['UPD_DESC'];
	$cAR = $_POST['UPD_AR_ACCOUNT'];
	$cREV= $_POST['UPD_REV_ACCOUNT'];
	$NOW = date("Y-m-d H:i:s");
	$cDESCRIPTION	= ENCODE($cDESC);	
	RecUpdate('SchRevAr', ['DESCRIPTION', 'AR_ACCOUNT', 'REV_ACCOUNT'], 
		[ENCODE($cDESC), $cAR, $cREV], "APP_CODE='$cAPP_CODE' and md5(REC_ID)='$cREC_ID'");
	APP_LOG_ADD($cHEADER, 'update : '.$cDESC);
	header('location:tb_edupay.php');
	break;
	
case "tambah":
	$cCOLUMN= $_POST['ADD_COLUMN'];
	$cDESC  = $_POST['ADD_DESC'];
	$cAR    = $_POST['ADD_AR_ACCOUNT'];
	$cREV   = $_POST['ADD_AR_ACCOUNT'];
	if($cCOLUMN==''){
		MSG_INFO('Kolom belum diisi');
		return;
	}
	$qQUERY=OpenTable('SchRevAr', "APP_CODE='$cAPP_CODE' and REV_COLUMN='".$cCOLUMN."' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qQUERY)>0){
		MSG_INFO('Kode kolom sudah ada');
		return;
	}
    RecCreate('SchRevAr', ['REV_COLUMN', 'DESCRIPTION', 'AR_ACCOUNT', 'REV_ACCOUNT', 'ENTRY', 'REC_ID', 'APP_CODE'], 
        [$cCOLUMN, ENCODE($cDESC), $cAR, $cREV, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
    header('location:tb_edupay.php');
	APP_LOG_ADD($cHEADER, 'add : '.$cCOLUMN);
}
?>

