<?php

	if (!isset($_SESSION['data_FILTER_CODE'])) {
		session_start();
	}

 	$qREC_FAV = mysql_query("select * from ".$database1.".userpren where USER_CODE='$_SESSION[gUSERCODE]' and PREN_STAT=2");
//	die ($qREC_FAV);
	if (mysql_num_rows($qREC_FAV)==0) {
		return;
	}

	$cFAVOURITES = S_MSG('UL11','Favourites');
?>
<!DOCTYPE html>

<h4 class="group-head"><?php echo $cFAVOURITES?></h4>
<ul class="contact-list">

	<?php
		$iUSER = 0;
		while($aREC_FAVORITE=mysql_fetch_array($qREC_FAV)) {
			$cUSER_PREN = $aREC_FAVORITE['USER_PREN'];
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

/*
<!--
				<li class="user-row" id='chat_user_2' data-user-id='2'>
					<div class="user-img">
						<a href="#"><img src="data/profile/avatar-2.png" alt=""></a>
					</div>
					<div class="user-info">
						<h4><a href="#">Brooks Latshaw</a></h4>
						<span class="status away" data-status="away"> Away</span>
					</div>
					<div class="user-status away">
						<i class="fa fa-circle"></i>
					</div>
				</li>
				<li class="user-row" id='chat_user_3' data-user-id='3'>
					<div class="user-img">
						<a href="#"><img src="data/profile/avatar-3.png" alt=""></a>
					</div>
					<div class="user-info">
						<h4><a href="#">Clementina Brodeur</a></h4>
						<span class="status busy" data-status="busy"> Busy</span>
					</div>
					<div class="user-status busy">
						<i class="fa fa-circle"></i>
					</div>
				</li>
-->	*/	?>

</ul>
