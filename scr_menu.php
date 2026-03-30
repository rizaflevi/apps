<?php
// scr_menu.php
	include "sys_connect1.php";
	require_once("scr_user_info.php");	
	if (!isset($_SESSION['data_FILTER_CODE']) || !isset($_SESSION['DB1'])) 	session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE']; 
	$cUSERCODE = $_SESSION['gUSERCODE'];

	// $cDASHBOARD_LINK = $_SESSION['cDASHBOARD_LINK'];
	// if($cDASHBOARD_LINK=='') $cDASHBOARD_LINK = 'prs_dashboard.php';
	// $cDASHBOARD_LINK = (IS_LOCALHOST()) ? '' : 'app_code.php';
	$cDASHBOARD_LINK = 'prs_dashboard.php';

	$qCEK_MENU=OpenTable('TbUser', "USER_CODE='$cUSERCODE' and DELETOR=''");
	$a_TB_USER=SYS_FETCH($qCEK_MENU);
//	$nUSER_STAT = $a_TB_USER['USER_STAT'];

	$qMAIN_MENU=OpenTable('MainMenu', "A.APP_CODE='$cAPP_CODE' and upper(B.USER_CODE)='".strtoupper($cUSERCODE)."' and B.DELETOR='' and A.sort>0 and A.JOB_CODE<>''", 'A.parent', 'A.order');
?>

	<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
		<ul class="wraplist">	
			<li class=""> 
				<a href="<?php echo $cDASHBOARD_LINK?>">
					<i class="fa fa-solid fa-gauge"></i>
					<span class="title">Dashboard</span>
				</a>
			</li>

			<?php
				while($aMAIN_MENU=SYS_FETCH($qMAIN_MENU)){

					$cICON = 'fa-regular fa-newspaper';
					if ($aMAIN_MENU['icon_class'] != '') {
						$cICON = $aMAIN_MENU['icon_class'];
					}
					echo '<li class=""> ';
						echo '<a href="javascript:;">';
							echo '<i class="'.$cICON.'" ></i>';
							echo '<span class="title">'.( S_PARA('LANG', '1')=='2' ? $aMAIN_MENU['ENG_PARENT'] : $aMAIN_MENU['parent']).'</span>';
							echo '<span class="arrow "></span>';
			//											echo '<span class="label label-orange">NEW</span>';
						echo '</a>';
						echo '<ul class="sub-menu" >';

							$cSUB_QUERY=OpenTable('SubMenu', "A.parent = '". $aMAIN_MENU['parent']."' and A.APP_CODE='$cAPP_CODE'
								and A.sort>0 and B.JOB_CODE is not null", 'A.JOB_CODE', 'A.sort');
							while($aSUB_MENU=SYS_FETCH($cSUB_QUERY)){
								$cICON = $aSUB_MENU['icon_class'];
								echo '<li>';
									echo '<a href='. $aSUB_MENU['link'].'>
											<i class="'.$cICON.'"></i>
											&nbsp;&nbsp;&nbsp;
											<span>'
											. ( S_PARA('LANG', '1')=='2' ? $aSUB_MENU['IN_ENGLISH'] : $aSUB_MENU['name']) .'
									  		</span>
										</a>';
								echo '</li>';
							}
							echo '</li>';
						echo '</ul>';
					echo '</li>';
				}
			?>
		</ul>
	</div>
