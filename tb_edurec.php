<?php
//	tb_edurec.php //

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();

	$cAPP_CODE 	= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Edurec.pdf';
	$cHAPUS		= S_MSG('H007','Apakah Anda benar-benar mau menghapusnya?');
	$cHEADER 	= 'Table Penerimaan Edupay';
	$can_CREATE = 1;

	$qQUERY=OpenTable('SchRecCol', "A.NON_VALUE=0 and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");

	$cACTION='';
	if (isset($_GET['_a'])) $cACTION=$_GET['_a'];
	$cCOLUMN_CODE 	= 'Kolom';
	$cDESCRIPTION   = 'Keterangan';
	$cBANK          = S_MSG('F131','Nama Bank');
	$cRECEIVABLE    = 'Account Piutang';
	$cADD_NEW		= 'Add new';
	$cEDIT_TBL		= 'Edit';

	$cSAVE		    = S_MSG('F301','Save');
	$cCLOSE		    = S_MSG('F302','Close');

	$cTTIP_COL		= 'Kolom di excel yang di upload ke penerimaan edupay';
	$cTTIP_DESC	    = 'Keterangan';
	$cTTIP_AR	    = 'Account piutang siswa yang melakukan pembayaran';
	$cTTIP_BANK	    = 'Bank tempat masuk dana penerimaan';

switch($cACTION){
	default:
		APP_LOG_ADD($cHEADER, 'view');
		DEF_WINDOW($cHEADER);
		$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('CREATE_NEW'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
		TFORM($cHEADER, '', $aACT, $cHELP_FILE);
		TDIV();
		TABLE('example');
		THEAD([$cCOLUMN_CODE, $cDESCRIPTION, $cRECEIVABLE, $cBANK]);
		echo '<tbody>';
			while($aEDU_REC=SYS_FETCH($qQUERY)) {
				echo '<tr>';
					echo '<td class=""><div class="star"><i class="fa fa-eur icon-xs icon-default"></i></div></td>';
					echo "<td><span><a href='?_a=update&_r=$aEDU_REC[REC_COLUMN]'>".$aEDU_REC['REC_COLUMN']."</a></span></td>";
					echo "<td><span><a href='?_a=update&_r=$aEDU_REC[REC_COLUMN]'>".$aEDU_REC['DESCRIPTION']."</a></span></td>";
					echo "<td>".DECODE($aEDU_REC['ACCT_NAME'])."</td>";
					echo "<td>".DECODE($aEDU_REC['B_NAME'])."</td>";
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
				INPUT('text', [1,1,1,12], '900', 'ADD_COLUMN', '', '', '', '', 0, '', 'fix', $cTTIP_COL);
				LABEL([3,3,3,6], '700', $cDESCRIPTION);
				INPUT('text', [8,8,8,6], '900', 'ADD_DESC', '', '', '', '', 0, '', 'fix', $cTTIP_DESC);
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
			<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cBANK?></label>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<select name="ADD_REV_BANK" class="form-label-900 select2" title="<?php echo $cTTIP_BANK?>">
			<?php 
				echo "<option value=' '  > </option>";
				$qQUERY=OpenTable('TbBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
				while($aBANK=SYS_FETCH($qQUERY)){
					echo "<option value='$aBANK[B_CODE]'  >".DECODE($aBANK['B_NAME'])."</option>";
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
		$qQUERY=OpenTable('SchRecCol', "A.NON_VALUE=0 and A.APP_CODE='$cAPP_CODE' and A.REC_COLUMN='$_GET[_r]' and A.REC_ID not in ( select DEL_ID from logs_delete)");
		$aREC_AR_REV=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DELETE_REC').'&_id='. $aREC_AR_REV['REC_ID']. '" onClick="return confirm('. "'". $cHAPUS. "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a=rubah&_id='.md5($aREC_AR_REV['REC_ID']), $aACT, $cHELP_FILE);
			TDIV();
				LABEL([3,3,3,6], '700', $cCOLUMN_CODE);
				INPUT('text', [2,2,2,6], '900', 'UPD_COLUMN', $aREC_AR_REV['REC_COLUMN'], '', '', '', 0, 'disabled', 'fix', $cTTIP_COL);
				LABEL([3,3,3,6], '700', $cDESCRIPTION);
				INPUT('text', [6,6,6,6], '900', 'UPD_DESC', $aREC_AR_REV['DESCRIPTION'], '', '', '', 0, '', 'fix', $cTTIP_DESC);
?>
				<div class="form-group">
					<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cRECEIVABLE?></label>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<select name="UPD_AR_ACCOUNT" class="col-sm-5 col-xs-12 form-label-900 select2" title="<?php echo $cTTIP_AR?>">
					<?php 
						echo "<option value=' '  > </option>";
						$qQUERY=OpenTable('TbAccount', "APP_CODE='$cAPP_CODE' and GENERAL='D' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aREC_ACCOUNT=SYS_FETCH($qQUERY)){
							if($aREC_AR_REV['REC_ACCOUNT'] == $aREC_ACCOUNT['ACCOUNT_NO']){
								echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]' selected='$aREC_AR_REV[REC_ACCOUNT]' >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>";
							} else {
							echo "<option value='$aREC_ACCOUNT[ACCOUNT_NO]'  >".DECODE($aREC_ACCOUNT['ACCT_NAME'])."</option>"; }
						}
					?>
					</select>
				</div>
				<div class="clearfix"></div><br>

				<div class="form-group">
					<label class="col-sm-3 form-label-700" for="field-1"><?php echo $cBANK?></label>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<select name="UPD_REV_ACCOUNT" class="col-sm-5 col-xs-12 form-label-900 select2" title="<?php echo $cTTIP_BANK?>">
					<?php 
						echo "<option value=' '  > </option>";
						$qQUERY=OpenTable('TbBank', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
						while($aBANK=SYS_FETCH($qQUERY)){
							if($aREC_AR_REV['BANK_CODE'] == $aBANK['B_CODE']){
								echo "<option value='$aBANK[B_CODE]' selected='$aREC_AR_REV[BANK_CODE]' >".DECODE($aBANK['B_NAME'])."</option>";
							} else {
							echo "<option value='$aBANK[B_CODE]'  >".DECODE($aBANK['B_NAME'])."</option>"; }
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
	header('location:tb_edurec.php');
	break;
	
	
case "rubah":
	$KODE_CRUD=$_GET['_id'];
	$cDESC = $_POST['UPD_DESC'];
	$cAR = $_POST['UPD_AR_ACCOUNT'];
	$cREV= $_POST['UPD_REV_ACCOUNT'];
	$NOW = date("Y-m-d H:i:s");
	$cDESCRIPTION	= ENCODE($_POST['UPD_DESC']);	
	RecUpdate('SchReceiptCol', ['DESCRIPTION', 'REC_ACCOUNT', 'BANK_CODE'], 
		[ENCODE($cDESC), $cAR, $cREV], "APP_CODE='$cAPP_CODE' and md5(REC_ID)='$KODE_CRUD'");
	APP_LOG_ADD($cHEADER, 'update : '.$cDESC);
	header('location:tb_edurec.php');
	break;
	
case "tambah":
	$cCOLUMN= $_POST['ADD_COLUMN'];
	$cDESC  = $_POST['ADD_DESC'];
	$cAR    = $_POST['ADD_AR_ACCOUNT'];
	$cREV   = $_POST['ADD_REV_BANK'];
	if($cCOLUMN==''){
		MSG_INFO('Kolom belum diisi');
		return;
	}
	$qQUERY=OpenTable('SchReceiptCol', "NON_VALUE=0 and APP_CODE='$cAPP_CODE' and REC_COLUMN='".$cCOLUMN."' and REC_ID not in ( select DEL_ID from logs_delete)");
	if(SYS_ROWS($qQUERY)>0){
		MSG_INFO('Kode kolom sudah ada');
		return;
	}
    RecCreate('SchReceiptCol', ['REC_COLUMN', 'DESCRIPTION', 'REC_ACCOUNT', 'BANK_CODE', 'ENTRY', 'REC_ID', 'APP_CODE'], 
        [$cCOLUMN, ENCODE($cDESC), $cAR, $cREV, $cUSERCODE, NowMSecs(), $cAPP_CODE]);
    header('location:tb_edurec.php');
	APP_LOG_ADD($cHEADER, 'add : '.$KODE_CRUD);
}
?>

