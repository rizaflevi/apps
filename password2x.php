<?php
// password2x.php

	include "sysfunction.php";
//	if (isset($_SESSION['DB1'])) mysqli_close($_SESSION['DB1']);
//	if (isset($_SESSION['DB2'])) mysqli_close($_SESSION['DB2']);

//	$_SESSION = array(); // deregister all current session variables
// $DB1 = $_SESSION['DB1'];
$cHEADER = 'Enter Password';

	$cUSER='';
	if (isset($_GET['_u'])) 	$cUSER=$_GET['_u'];

$cACTION='';
if (isset($_GET['_a'])) 
	$cACTION=$_GET['_a'];

switch($cACTION){
	default:
?>
	<!DOCTYPE html>
	<html class=" ">
		<?php	require_once("scr_headtr.php");	?>
		<body class=" login_page">
				<div id="passw" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-4 col-md-6 col-sm-offset-4 col-sm-6 col-xs-offset-0 col-xs-12">
					<h1><a href="#" title=<?php echo $cHEADER ?>>Rainbow Sys</a></h1>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<form name="pass2form" id="pass2form" action="?_a=pass&_u=<?php echo $cUSER?>" method="post">
							<label for="user_p1" style="color:orange">Password</label>
							<input type="password" name="_p1" id="user_p1" class="input form-control" value="" size="20"/>
							<label for="user_p2" style="color:orange">Re-type Password</label>
							<input type="password" name="_p2" id="user_p2" class="input form-control" value="" size="20"/><br><br>
							<div class="submit">
								<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-orange btn-block" value="OK">
							</div>
							<p></p>
						</form>
						<div id="nav">
							<a class="pull-right" href="login.php" title="Cancel for password">Cancel</a>
						</div>
						<div class="clearfix"></div>
						<br><br><br>
					</div>
				</div>

			<?php	require_once("js_framework.php");	?>
			<script src="assets/js/scripts.js" type="text/javascript"></script> 
			<script type="text/javascript"></script>

		</body>
	</html>

<?php
	break;
case 'pass':
	$cPASS1=$_POST['_p1'];
    $cPASS2=$_POST['_p2'];
	$cUSER=$_GET['_u'];
	if ($cPASS1!=$cPASS2) {
		header('Location: unautorized.php');
		exit();
	}
	if ($cPASS1=='') {
		header('Location: unautorized.php');
		return;
	}
//	print_r2($cUSER);
//	RecUpdate('TbUser', ['PASSWORD'], [md5($cPASS1)], "md5(USER_CODE)='$cUSER'");
		include "sys_connect1.php";
		$DB1 = $_SESSION['DB1'];
		$cQUERY=	$DB1 -> query("update tb_user set PASSWORD='".md5($cPASS1)."' where md5(USER_CODE)='$cUSER'");
        header('Location: login.php');
	break;
}

?>



