<?php

// app desc : Aplikasi mobile Baca Meter PLN Prabayar

	if (isset($_SESSION['DB1'])) {
		mysql_close($_SESSION['DB1']);
	}
	if (isset($_SESSION['DB2'])) {
		mysql_close($_SESSION['DB2']);
	}
	$_SESSION = array(); // deregister all current session variables
	$cHEADER = 'Login Page';

?>
<!DOCTYPE html>
<html class=" ">
	<?php	require_once("scr_headtr.php");	?>
	<body class="login_page">
		<div class="register-wrapper">
            <div id="login" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-0 col-xs-12">
                <h1><a href="#" title="Login Page">Rainbow Sys</a></h1>
				<div class="col-md-12 col-sm-12 col-xs-12">

                <form name="loginform" id="loginform" action="bm_login.php" method="post">
					<label for="user_login" style="color:orange">Username</label>
					<input type="text" name="_usr" id="user_login" class="input form-control" value="" size="20" style="padding:5px 9px;"/>
					<label for="user_pass" style="color:orange">Password</label>
					<input type="password" name="_pwd" id="user_pass" class="input form-control" value="" size="20" style="padding:3px 9px;"/><br><br>
                    <div class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-orange btn-block" value="Sign In">
                    </div>
					<p></p>
               </form>

                <div class="clearfix"></div>
				<br><br><br><br>
            </div>
            </div>
		</div>

		<?php	require_once("js_framework.php");	?>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script type="text/javascript"></script>

	</body>
</html>

<script>
</script>



