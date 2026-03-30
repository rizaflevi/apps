<?php
//	scr_more_contact.php
	$cMORE_CONTACT = S_MSG('UL21','More Contacts');
	$cUSER_CODE = $_SESSION['gUSERCODE'];

	$cQ_PREN 	= mysql_query("SELECT * FROM ".$database1.".userpren where USER_CODE='$cUSER_CODE' and PREN_STAT!=2") or die ('Error in query.' .mysql_error());
	$aREC_PREN 	= mysql_fetch_array($cQ_PREN);

	if (mysql_num_rows($cQ_PREN)==0) {
		return;
	}

	$cQ_USER=mysql_query("SELECT * FROM ".$database1.".tb_user where USER_CODE='$cUSER_CODE'") or die ('Error in query.' .mysql_error());
	$aREC_USER =mysql_fetch_array($cQ_USER);
//	die ();
 ?>
<h4 class="group-head"><?php echo $cMORE_CONTACT?></h4>
<ul class="contact-list">

	<?php
		$iUSER = 0;
		while($aREC_FRIEND=mysql_fetch_array($cQ_PREN)) {
			$cUSER_PREN = $aREC_FRIEND['USER_PREN'];
			$qTB_USER=mysql_fetch_array(mysql_query("select * from ".$database1.".tb_user where DELETOR='' and USER_CODE='$cUSER_PREN'"));
			$cFOTO = 'data/images_user/'.$qTB_USER['USER_CODE'].'.jpg';
			$cSTATUS = 'Offline';
			$dSTATUS = 'offline';
			if ($qTB_USER['USER_LOGIN']==1) {
				$cSTATUS = 'Available';
				$dSTATUS = 'available';
			}
			if ($qTB_USER['USER_LOGIN']==3) {
				$cSTATUS = 'Busy';
				$dSTATUS = 'busy';
			}
			if ($qTB_USER['USER_LOGIN']==4) {
				$cSTATUS = 'Away';
				$dSTATUS = 'away';
			}
			$iUSER ++;
			echo '<li class="user-row" id="'.$cUSER_PREN.'" data-user-id="'.$cUSER_PREN.'">';
				echo '<div class="user-img">';
					echo '<a href="#"><img src="'.$cFOTO.'" alt=""></a>';
				echo '</div>';
				echo '<div class="user-info">';
					echo '<h4><a href="#">'.$qTB_USER['USER_NAME'].'</a></h4>';
					echo '<span class="status '.$dSTATUS.'" data-status="'.$dSTATUS.'"> '.$cSTATUS.'</span>';
				echo '</div>';
				echo '<div class="user-status '.$dSTATUS.'">';
					echo '<i class="fa fa-circle"></i>';
				echo '</div>';
			echo '</li>';
		}
 	?>
</ul>

