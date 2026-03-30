<?php

	$cFE_PERSON='';
	$cWIDTH_LOGO='375px';
	if (isset($_GET['_fe'])) {
		$cFE_PERSON=$_GET['_fe'];
		$cWIDTH_LOGO='100%';
	}
    $cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cFILE_USER = 'data/images_user/'. $cFE_PERSON.'.jpg';
	if(file_exists($cFILE_USER)==0)	$cFILE_USER = "data/images/LOGO_CIRCLE_".$cAPP_CODE.".jpg";

	$COMPANY = 'Rainbow1';
	$LOGO = 'data/images/'.$COMPANY.'.jpg';
	if ($cAPP_CODE!='') {
		$LOGO = 'data/images/'.$cAPP_CODE.'_MOBILE.jpg';
	}
?>

	<!-- <div class="pace-activity"></div>
        <div class='page-topbar'>
            <div class='logo-area'>

            </div>
 		</div>
	</div> -->

	<div class="page-topbar">
		<img class="col-xs-12" src="<?php echo $LOGO ?>" style="height: 60px;">
	</div>