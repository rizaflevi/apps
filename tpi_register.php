<?php
// sys_company.php
// G/L Interface table

include "sys_connect.php";
date_default_timezone_set('Asia/Jakarta');

//	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];

	$qQUERY = SYS_QUERY("select * from sys_module where app_active=1");

?>

<!DOCTYPE html>
<html class=" ">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>Rainbow Sys : Registrasi eTPI</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon" />
        <link rel="apple-touch-icon-precomposed" href="assets/images/apple-touch-icon-57-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/apple-touch-icon-144-precomposed.png">

        <!-- CORE CSS FRAMEWORK -  -->
        <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE -  --> 
        <link href="assets/plugins/icheck/skins/square/orange.css" rel="stylesheet" type="text/css" media="screen"/>        

        <!-- CORE CSS TEMPLATE -  -->
        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>

    </head>

    <body class=" login_page">

        <div class="register-wrapper">
            <div id="register" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-0 col-xs-12">
                <h1><a href="#" title="Login Page">Rainbow Sys</a></h1>

                <form name="loginform" id="loginform" action="register_cek.php" method="post">
                    <p>
                        <label for="user_login">Nama Lengkap</label>
						<input type="text" name="nam" id="user_login" class="input" value="" size="20"/>
                    </p>
                    <p>
                        <label for="user_login">System Module</label>
						<select id="SelectModule" name="SELECT_MODULE" class="form-label-900 m-bot15" style="width:100%;height:50px;">
						<?php 
							echo "<option value=' '  > </option>";
							while($REC_MODUL=SYS_FETCH($qQUERY)){
								echo "<option value='$REC_MODUL[module_code]'  >$REC_MODUL[module_name]</option>";
							}
						?>
						</select>
                    </p>
                    <p>
                        <label for="user_login">Username</label>
						<input type="text" name="usr" id="user_login" class="input" value="" size="20"/>
                    </p>
                    <p>
                        <label for="user_pass">Password</label>
						<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" />
                    </p>
                    <p>
                        <label for="user_pass">Password Konfirmasi</label>
						<input type="password" name="pwd1" id="user_pass1" class="input" value="" size="20" />
                    </p>
                    <p class="forgetmenot">
                        <label class="icheck-label form-label" for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" class="skin-square-orange" checked> I agree to terms to conditions</label>
                    </p>

                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-orange btn-block" value="Sign Up" />
                    </p>
                </form>

                <p id="nav">
<!--                      <a class="pull-left" href="#" title="Password Lost and Found">Forgot password?</a>	 -->
                    <a class="pull-right" href="tpi_login.php" title="Sign Up">Login</a>
                </p>
                <div class="clearfix"></div>
            </div>
        </div>
		<?php	require_once("js_framework.php");	?>
        <script src="assets/plugins/icheck/icheck.min.js" type="text/javascript"></script>
		  
        <!--
        <script src="assets/plugins/messenger/js/messenger.min.js" type="text/javascript"></script>
		  <script src="assets/plugins/messenger/js/messenger-theme-future.js" type="text/javascript"></script>
		  <script src="assets/plugins/messenger/js/messenger-theme-flat.js" type="text/javascript"></script>
		  <script src="assets/js/messenger.js" type="text/javascript"></script>	--> 
		  
		<script src="sys_js.js" type="text/javascript"></script>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 

        <div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Section Settings</h4>
                    </div>
                    <div class="modal-body">


                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        <button class="btn btn-success" type="button">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>



