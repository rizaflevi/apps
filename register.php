<?php
// register.php

include "sys_connect.php";
date_default_timezone_set('Asia/Jakarta');

	$qQUERY = OpenTable('SysModule', 'app_active=1');
	$cHEADER = 'Registration Page';

?>

<!DOCTYPE html>
<html class=" ">
	<?php	require_once("scr_headtr.php");	?>
	<link href="assets/plugins/icheck/skins/square/orange.css" rel="stylesheet" type="text/css" media="screen"/>        
    <body class=" login_page">
        <div class="register-wrapper">
            <div id="register" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-0 col-xs-12">
                <h1><a href="#" title="Login Page">Rainbow Sys</a></h1>

                <form name="loginform" id="loginform" action="register_cek.php" method="post">
                    <p>
						<label for="user_login">Full Name</label>
						<input type="text" name="nam" id="user_login" class="input" value="" size="20"/>
                    </p>
                    <p>
                        <label for="user_login">System Module</label>
						<select id="SelectModule" name="SELECT_MODULE" class="form-label-900 m-bot15" style="width:100%;height:50px;">
						<?php 
							echo "<option value=' '  > </option>";
							while($REC_MODUL=mysql_fetch_array($qQUERY)){
								echo "<option value='$REC_MODUL[module_code]'  >$REC_MODUL[module_name]</option>";
							}
						?>
						</select>
                    </p>
                    <p><label for="user_login">Username</label><input type="text" name="usr" id="user_login" class="input" value="" size="20"/></p>
                    <p><label for="user_pass">Password</label><input type="password" name="pwd" id="user_pass" class="input" value="" size="20" /></p>
                    <p><label for="user_pass">Confirm Password</label><input type="password" name="pwd1" id="user_pass1" class="input" value="" size="20" /></p>
                    <p class="forgetmenot">
                        <label class="icheck-label form-label" for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" class="skin-square-orange" checked> I agree to terms to conditions</label>
                    </p>

                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-orange btn-block" value="Sign Up" />
                    </p>
                </form>

                <p id="nav">
                    <a class="pull-right" href="login.php" title="Sign Up">Sign In</a>
                </p>
                <div class="clearfix"></div>
            </div>
        </div>

		<?php	require_once("js_framework.php");	?>
        <script src="assets/plugins/icheck/icheck.min.js" type="text/javascript"></script>
		<script src="sys_js.js" type="text/javascript"></script>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 
    </body>
</html>



