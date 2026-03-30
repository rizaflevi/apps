<?php
// scr_user_info.php
    if (!isset($_SESSION['gUSERCODE'])) 	session_start();
	$cUSER_CODE 		= $_SESSION['gUSERCODE'];
	// $cFILE_USER = S_PARA('FTP_USER_FOLDER', '/home/riza/www/images/admin/'). $cUSER_CODE.'.jpg';
	$cFILE_USER = 'data/images_user/'. $cUSER_CODE.'.jpg';
	if(!file_exists($cFILE_USER))	{
		$cFILE_USER = "data/images/demo.jpg";
	}
	$cPROFILE_LINK = (IS_LOCALHOST()) ? 'app_code.php' : '';
?>
	<div class="profile-info row">
		<div class="profile-image col-md-4 col-sm-4 col-xs-4">
			 <a href="<?php echo $cPROFILE_LINK?>">
				  <img src="<?php echo $cFILE_USER?>" class="img-responsive img-circle">
			 </a>
		</div>
		<div class="profile-details col-md-8 col-sm-8 col-xs-8">
			 <h3>
				  <a href="ui-profile.php"><?php echo $cUSER_CODE?></a>
				  <span class="profile-status online"></span>
			 </h3>
			 <p class="profile-title"><?php echo $_SESSION['gUSER_AS']?></p>
		</div>
	</div>
