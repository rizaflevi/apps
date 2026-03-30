<?php
//	log_user.php //
//	Laporan log admin/user

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cUSERCODE 		= $_SESSION['gUSERCODE'];
	$cHEADER 		= S_MSG('TU3A','Logs User');
	$chTANGGAL 		= S_MSG('RS02','Tanggal');
	$cPERIOD1=$cPERIOD2=date("d/m/Y");

	if (isset($_GET['_d1'])) $cPERIOD1=$_GET['_d1'];
	if (isset($_GET['_d2'])) $cPERIOD2=$_GET['_d2'];

	$cFILTER_USER=(isset($_GET['_u']) ? $_GET['_u'] : '');

	$qUSER=OpenTable('TbUser', "APP_CODE='$cAPP_CODE'", '', 'USER_NAME');
	$aUSER=SYS_FETCH($qUSER);
//	if ($cFILTER_USER=='')	$cFILTER_USER=$aUSER['USER_CODE'];

	DEF_WINDOW($cHEADER, 'collapse');
		TFORM($cHEADER, '', [], 'Doc/File - Logs.pdf', '*');
			LABEL([1,3,3,5], '700', $chTANGGAL, '', 'right');
			INP_DATE([2,2,2,6], '900', '', $cPERIOD1, '', '', '', '', '', "FILT_LOGS(this.value, '$cPERIOD2', '$cFILTER_USER')");
			LABEL([1,3,3,5], '700', $chTANGGAL, '', 'right');
			INP_DATE([2,2,2,6], '900', '', $cPERIOD2, '', '', '', '', '', "FILT_LOGS('$cPERIOD2', this.value, '$cFILTER_USER')");
			LABEL([2,2,2,5], '700', S_MSG('TU02','Nama User'), '', 'right');
			if ($aUSER) {	
				SELECT([3,3,3,3], 'PILIH_USER', "FILT_LOGS('$cPERIOD1', '$cPERIOD2', this.value)");
					echo "<option value=''  > All </option>";
					while($aUSER=SYS_FETCH($qUSER)){
						if($aUSER['USER_CODE']==$cFILTER_USER)
							echo "<option value='$aUSER[USER_CODE]' selected='$aUSER[USER_CODE]' >$aUSER[USER_NAME]</option>";
						else
							echo "<option value='$aUSER[USER_CODE]'  >$aUSER[USER_NAME]</option>";
					}
				echo '</select>';
			}
			CLEAR_FIX();
	?>
			<div class="content-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<table data-order='[[ 2, "desc" ]]' cellspacing="0" id="example" class="table table-small-font table-bordered table-striped">
							<?php	echo THEAD(['User', $chTANGGAL, 'Modul', S_MSG('TD3B','Aktifitas')]);
							echo '<tbody>';
								$qLOGS = OpenTable('SysLogs', "APP_CODE='$cAPP_CODE' and LOGS>'' and left(DATE_ENTRY,10)>='".DMY_YMD($cPERIOD1)."' and left(DATE_ENTRY,10)<='".DMY_YMD($cPERIOD2)."'". ($cFILTER_USER=='' ? '' : " and USER_CODE='$cFILTER_USER'"), '', 'DATE_ENTRY desc');
								while($aREC_LOGS=SYS_FETCH($qLOGS)){
									echo '<td style="width: 1px;"></td>';
									echo "<td><span>".$aREC_LOGS['USER_CODE']." </span></td>";
									echo "<td><span>".date("d-M-Y H:i:s", strtotime($aREC_LOGS['DATE_ENTRY']))." </span></td>";
									echo '<td><span>'.$aREC_LOGS['LOGS'].' </span></td>';
									echo '<td><span>'.$aREC_LOGS['PRGS'].' </span></td>';
									echo '</tr>';
								}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
<?php
		eTFORM('*');
		include "scr_chat.php";
		require_once("js_framework.php");
	END_WINDOW();
	APP_LOG_ADD( $cHEADER, 'view');
	SYS_DB_CLOSE($DB2);
?>
<script>
function FILT_LOGS(p_TGL1, p_TGL2, p_USER) {
	window.location.assign("?_d1="+p_TGL1 + "&_d2="+p_TGL2 + "&_u="+p_USER);
}

</script>
