<?php
	

	$_SESSION['NLATTITUDE'] = $_POST['_la'];
	$_SESSION['NLONGITUDE'] = $_POST['_lo'];

//	$_SESSION[gCAT_TER]
	$nLAT = $_POST['_la'];
	$nLNG = $_POST['_lo'];
	$c_LOC ="update ".$database1.".tb_user set LATTITUDE='$nLAT', LONGITUDE='$nLNG'  where USER_CODE='$_SESSION[gUSERCODE]'";
	$q_LOC =mysql_query($c_LOC) or die ('Error in query.' .mysql_error());
//	var_dump($_SESSION['NLATTITUDE']); exit();
?>
