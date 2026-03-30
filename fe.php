<?php
// fe.php
// $_SESSION['data_FILTER_CODE'] = 'YAZA';
// $_SESSION['gUSERCODE'] 	= 'guest';
// $_SESSION['gSYS_PARA'] 	= 'JNS_PRSHN';
// $_SESSION['sLANG']		= '1';
// $_SESSION['gSYS_NAME']     = 'Rainbow Sys';

// $_SESSION['cHOST_DB1'] = 'riza_sys_data';
// $_SESSION['cHOST_DB2'] = 'riza_db';
// include "sys_connect.php";

$nRec_id = date_create()->format('Uv');
$cRec_id = (string)$nRec_id;

?>

<!DOCTYPE html>
<html>
<script>
	function getUniqueId() {

		let uniqueId = localStorage.getItem('UniqueId');
		
//		if(typeof localStorage["UniqueId"] === "undefined") {
		if (!uniqueId) {
			uniqueId = <?php echo $cRec_id?>;
			localStorage.setItem('UniqueId', uniqueId);
		} else {
			let uniqueId = localStorage.getItem('UniqueId');
		}
		return uniqueId;
	};
	let x = getUniqueId();
    window.location.replace("rainbow_ext.php?q="+x);
</script>
</html>
