<?php
//	set_period.php //
	if (!isset($_SESSION['sCURRENT_PERIOD'])) session_start();
	$cPERIOD = $_GET['_p'];
	if (isset($_GET['_p']))	{
		$_SESSION['sCURRENT_PERIOD'] = $_GET['_p'];
	}
	echo "<script> window.history.back();	</script>";
?>
