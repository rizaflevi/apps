<?php

	if (isset($_SESSION['DB1'])) {
		mysql_close($_SESSION['DB1']);
	}
	if (isset($_SESSION['DB2'])) {
		mysql_close($_SESSION['DB2']);
	}
  $_SESSION = array(); // deregister all current session variables

?>
<!DOCTYPE html>
<html class=" ">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta charset="utf-8" />
        <title>Rainbow Sys : Login Page</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon" />
        <link rel="apple-touch-icon-precomposed" href="assets/images/apple-touch-icon-57-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/apple-touch-icon-144-precomposed.png">

        <!-- CORE CSS FRAMEWORK - -->
        <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE -   
        <link href="assets/plugins/icheck/skins/square/orange.css" rel="stylesheet" type="text/css" media="screen"/>    	-->    
		  <script src="sys_js.js" type="text/javascript"></script>

        <!-- CORE CSS TEMPLATE -  -->
        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>
    </head>

	<body class=" login_page">
		<div class="login-wrapper">
            <div id="login" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-0 col-xs-12">
                <h1><br><br><br><br><br><a href="#" title="Login Page" tabindex="-1">Rainbow Sys</a></h1>
				<form name="PosLoginForm" id="PosLoginForm" action="pos.php" method="post"  onSubmit="return validasi(this)">
					<p>
						<label for="user_login" style="color:orange">Username<br />
						<input type="text" name="username" id="user_login" class="input"/></label><br>
						<label for="user_pass" style="color:orange">Password<br />
						<input type="password" name="password" id="user_pass" class="input"/></label><br><br>
					</p>
					<p class="submit">
						<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-orange btn-block" value="Sign In" />
					</p>
				</form>
				<br><br><br><br><br>
                <p id="nav">
                </p><br><br><br><br><br>
            </div>
		</div>
		<?php	require_once("js_framework.php");	?>
        <script src="assets/plugins/icheck/icheck.min.js" type="text/javascript"></script>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script type="text/javascript">		</script>
	</body>
</html>



