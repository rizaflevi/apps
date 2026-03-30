
<?php

include "sys_connect.php";

	$REG_NAME = $_POST['nam'];
	$REG_USER = $_POST['usr'];
	$REG_PASS = $_POST['pwd'];
	$NOW = date("Y-m-d H:i:s");

	if ($REG_NAME=='') {
		echo "<script> alert('Anda belum mengisikan Nama Anda');
		window.history.back();	</script>";
		return;
	}
	if ($REG_USER=='') {
		echo "<script> alert('Anda belum mengisikan Username');
		window.history.back();	</script>";
		return;
	}
	if ($_POST['pwd']=='') {
		echo "<script> alert('Anda belum mengisikan Password');
		window.history.back();	</script>";
		return;
	}
	if ($_POST['pwd1']=='') {
		echo "<script> alert('Anda belum mengisikan Password');
		window.history.back();	</script>";
		return;
	}
	if ($_POST['pwd']!=$_POST['pwd1']) {
		echo "<script> alert('Password tidak sama');
		window.history.back();	</script>";
		return;
	}
/* ======================= connect to system data =================================== */


  mysql_select_db($database1, $DB1) or die("Can not open DB1");
	$cQUERY1=mysql_query("SELECT * FROM tb_user where user_code='$REG_USER'");
	$SDH_REG=mysql_num_rows($cQUERY1);

	if ( $SDH_REG > 0 ) {
//		already_regs();
		echo "<script> alert('User name already exist!');
		window.history.back();	</script>";
		return;
		exit();
	} elseif (preg_match('#[\^£$%&*()}{@~?><>,|=_+¬-]/#', $REG_USER)) {
		echo "<script> alert('User code can not contain a special character!');
		window.history.back();	</script>";
		die ('/[\^£$%&*()}{@#~?><>,|=_+¬-]/');
		return;
		exit();
	}
//	die ($REG_USER);
	$cQUERY			= "insert into tb_user set USER_CODE='$REG_USER', USER_NAME='$_POST[nam]',
						ENTRY='Register', DATE_ENTRY='$NOW',
						SYS_MODULE='$_POST[SELECT_MODULE]', PASSWORD='".md5($REG_PASS)."'";
	$aADD_USER=	mysql_query($cQUERY);

	$ADD_LOG=	mysql_query("insert into user_log set USER_CODE='$REG_USER', APP_CODE='$_POST[SELECT_MODULE]',
						USER_ACT='Register'");

	$cQUERY			= "insert into userpren set USER_CODE='$REG_USER', USER_PREN='Riza'";
	$aADD_PREN=	mysql_query($cQUERY);
	$cQUERY			= "insert into userpren set USER_CODE='Riza', USER_PREN='$REG_USER'";
	$aADD_PREN=	mysql_query($cQUERY);

	include "register_done.php";
 ?>

 <script src="sys_js.js" type="text/javascript"></script>
