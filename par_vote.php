<?php
// register.php

include "par_connect.php";
date_default_timezone_set('Asia/Jakarta');

	$qQUERY = mysql_query("select * from tbl_pemilu where is_active=1");
	$cHEADER = 'Vote Page';

?>

<!DOCTYPE html>
<html class=" ">
	<?php	require_once("scr_headtr.php");	?>

	<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE -  --> 
	<link href="assets/plugins/icheck/skins/square/orange.css" rel="stylesheet" type="text/css" media="screen"/>        

    <!-- BEGIN BODY -->
    <body class=" login_page">

        <div class="register-wrapper">
            <div id="register" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-0 col-xs-12">
                <h1><a href="#" title="Login Page">Rainbow Sys</a></h1>

                <form name="loginform" id="loginform" action="register_cek.php" method="post">
                    <p>
                        <label for="user_login">Pilkada</label>
						<select id="PilihPemilu" name="SELECT_PEMILU" class="form-label-900 m-bot15" style="width:100%;height:50px;">
						<?php 
							echo "<option value=' '  > </option>";
							while($REC_PEMILU=mysql_fetch_array($qQUERY)){
								echo "<option value='$REC_PEMILU[id]'  >$REC_PEMILU[nama]</option>";
							}
						?>
						</select>
                    </p>
                    <p>
						<label for="user_login">Kabupaten</label>
						<select id="PilihKabupeten" name="SELECT_KABUPATEN" class="form-label-900 m-bot15" style="width:100%;height:50px;">
						<?php 
							echo "<option value=' '  > </option>";
							while($REC_KABUPATEN=mysql_fetch_array($qQUERY)){
								echo "<option value='$REC_KABUPATEN[id]'  >$REC_KABUPATEN[nama]</option>";
							}
						?>
						</select>
                    </p>
                    <p>
						<label for="user_login">Kecamatan</label>
						<select id="PilihKecamaten" name="SELECT_KECAMATAN" class="form-label-900 m-bot15" style="width:100%;height:50px;">
						<?php 
							echo "<option value=' '  > </option>";
							while($REC_KECAMATAN=mysql_fetch_array($qQUERY)){
								echo "<option value='$REC_KECAMATAN[id]'  >$REC_KECAMATAN[nama]</option>";
							}
						?>
						</select>
					</p>
                    <p><label for="user_pass">Kelurahan</label>
						<select id="PilihKelurahan" name="SELECT_KELURAHAN" class="form-label-900 m-bot15" style="width:100%;height:50px;">
						<?php 
							echo "<option value=' '  > </option>";
							while($REC_KELURAHAN=mysql_fetch_array($qQUERY)){
								echo "<option value='$REC_KECAMATAN[id]'  >$REC_KECAMATAN[nama]</option>";
							}
						?>
						</select>
                    </p>

                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-orange btn-block" value="Submit" />
                    </p>
                </form>
                <div class="clearfix"></div>
            </div>
        </div>

		<?php	require_once("js_framework.php");	?>
		<script src="sys_js.js" type="text/javascript"></script>

        <!-- CORE TEMPLATE JS -  --> 
        <script src="assets/js/scripts.js" type="text/javascript"></script> 

    </body>
</html>



