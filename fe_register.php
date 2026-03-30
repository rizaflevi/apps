<?php
// fe_register.php

$_SESSION['cHOST_DB2'] = 'riza_db';
include "sys_connect2.php";
include "sys_function.php";
// date_default_timezone_set('Asia/Jakarta');

$cDEVICE='';
if (isset($_GET['_d']))	{
    $cDEVICE=$_GET['_d'];
}
$cHEADER		= 'Mobile Apps';
?>

<!DOCTYPE html>
<html class=" ">
	<?php	require_once("scr_header.php");	?>
	<link href="assets/plugins/icheck/skins/square/orange.css" rel="stylesheet" type="text/css" media="screen"/>        
    <body class=" login_page">
        <div class="register-wrapper">
            <div id="register" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-0 col-xs-12">
                <h1><a href="#" title="Login Page">Rainbow TAD</a></h1>

                <form name="fe_reg_form" id="fe_reg_form" action="fe_reg_cek.php?_d=<?php echo $cDEVICE?>" method="post">
                    <p>
						<label for="fe_reg_form">Nama Lengkap</label>
						<input type="text" name="nam" class="input" value="" size="20"/>
                    </p>
                    <p>
                        <label for="fe_reg_form">Lokasi Kerja</label>
						<input type="text" name="loc" class="input" value="" size="20"/>
                    </p>
                    <p>
                        <label for="fe_reg_form">Jabatan</label>
						<input type="text" name="job" class="input" value="" size="20"/>
                    </p>
                    <p>
                        <label for="fe_reg_form">Password</label>
						<input type="text" name="pass" class="input" value="" size="20" placeholder=""/>
                    </p>

                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-orange btn-block" value="Register" />
                    </p>
                </form>

                <div class="clearfix"></div>
            </div>
        </div>

		<?php	require_once("js_framework.php");	?>
        <script src="assets/plugins/icheck/icheck.min.js" type="text/javascript"></script>
		<script src="sys_js.js" type="text/javascript"></script>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 
    </body>
</html>



