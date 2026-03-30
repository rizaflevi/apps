<?php
// rainbow_ext.php

$cAPP_CODE = (isset($_GET['_apc']) ? $_GET['_apc'] : 'YAZA'); 
$cUSERCODE = (isset($_GET['_usr']) ? $_GET['_usr'] : 'guest');
$cHOST_DB1 = (isset($_GET['_cDB1']) ? $_GET['_cDB1'] : 'riza_sys_data');
$cHOST_DB2 = (isset($_GET['_cDB1']) ? $_GET['_cDB2'] : 'riza_db');

$_SESSION['data_FILTER_CODE'] = $cAPP_CODE;
$_SESSION['gUSERCODE'] 	= $cUSERCODE;
$_SESSION['gSYS_PARA'] 	= 'JNS_PRSHN';
$_SESSION['sLANG']		= '1';
$_SESSION['gSYS_NAME']     = 'Rainbow Sys';
$_SESSION['cHOST_DB1'] = $cHOST_DB1;
$_SESSION['cHOST_DB2'] = $cHOST_DB2;

// session_start();

include "sysfunction.php";

	$cDEVICE = $_REQUEST["q"];

	if($cDEVICE=='') {
		MSG_INFO('Invalid device !');
		return;
	}

    $q_DEVICE=OpenTable('FeDevice', "DEVICE_ID='$cDEVICE' and REC_ID not in ( select DEL_ID from logs_delete)");
    if ($a_DEV = SYS_FETCH($q_DEVICE)) {
        $cFE_PERSON_CODE = $a_DEV['PEOPLE_CODE'];
        if ($cFE_PERSON_CODE=='') header('location:fe_register_done.php');
		$cAPP_CODE=$a_DEV['APP_CODE'];
    } else {
		header('location:fe_register.php?_d='.$cDEVICE);
		return;
	}

	$_SESSION['data_FILTER_CODE'] = $cAPP_CODE;

	$cHEADER		= 'Mobile Apps';
	$c_LOGO_COMP = 'data/images/'. 'LOGO_CIRCLE_'.$cAPP_CODE.'.jpg';
	$c_LOGO_BAR  = 'data/images/'. 'LOGO_BAR_'.$cAPP_CODE.'.jpg';

    $q_PERSON=OpenTable('PrsOccuption', "PRSON_CODE='$cFE_PERSON_CODE' and APP_CODE='$cAPP_CODE' and DELETOR=''");
    if ($a_PERSON = SYS_FETCH($q_PERSON)) {
		$cAPP_CODE = $a_PERSON['APP_CODE'];
		$_SESSION['cAPP_CODE'] = $cAPP_CODE;   
		$cCUSTOMER = $a_PERSON['CUST_CODE'];
		$cLOCATION = $a_PERSON['KODE_LOKS'];
		$cJOB = $a_PERSON['JOB_CODE'];
	} else	{
        MSG_INFO('Kode Pegawai tidak di temukan !');
        return;
    }

    $q_GROUP=OpenTable('TbCustomer', "CUST_CODE='$cCUSTOMER' and APP_CODE='$cAPP_CODE' and DELETOR=''");
    $a_GROUP = SYS_FETCH($q_GROUP);
	$cCUST_GROUP = ($a_GROUP ? $a_GROUP['CUST_GROUP'] : '');

    $q_PERSON=OpenTable('PeoplePresence', "A.PRSON_CODE='$cFE_PERSON_CODE' and A.APP_CODE='$cAPP_CODE' and A.DELETOR=''");
    $a_PERSON = SYS_FETCH($q_PERSON);
    $cPRS_CODE = $a_PERSON['PRSON_CODE'];
    $cPRS_NAME = DECODE($a_PERSON['PEOPLE_NAME']);
//    $_SESSION['cPRS_NAME'] = $cPRS_NAME;   
    $_SESSION['cDEVICE'] = $cDEVICE; 
    $cPRS_JOB = $a_PERSON['JOB_NAME'];

	$cDASH_CODE = "(";
	$qSCOPE=OpenTable('FeScope','', '', 'DASH_ORDER');
	while($aSCOPE	= SYS_FETCH($qSCOPE)) {
		if ($aSCOPE['CUST_GROUP']=='') {
			if ($aSCOPE['CUST_CODE']=='') {
				if ($aSCOPE['LOC_CODE']=='') {
					if ($aSCOPE['JOB_CODE']=='') {
						$cDASH_CODE .="'".$aSCOPE['DASH_CODE']."', ";
					} else {
						if ($aSCOPE['JOB_CODE']==$cJOB) {
							$cDASH_CODE .="'".$aSCOPE['DASH_CODE']."', ";
						}
					}
				} else {
					if ($aSCOPE['LOC_CODE']==$cLOCATION && ($aSCOPE['JOB_CODE']==$cJOB || $aSCOPE['JOB_CODE']=='')) {
						$cDASH_CODE .="'".$aSCOPE['DASH_CODE']."', ";
					}
				}
			} else {
				if ($aSCOPE['CUST_CODE']==$cCUSTOMER) {
				 	if ($aSCOPE['LOC_CODE']=='') {
						if ($aSCOPE['JOB_CODE']=='') {
							$cDASH_CODE .="'".$aSCOPE['DASH_CODE']."', ";
						} else {
							if ($aSCOPE['JOB_CODE']==$cJOB) {
								$cDASH_CODE .="'".$aSCOPE['DASH_CODE']."', ";
							}
						}
					} else {
						if ($aSCOPE['JOB_CODE']==$cJOB)
							$cDASH_CODE .="'".$aSCOPE['DASH_CODE']."', ";
					}
				} else {
				}
			}
		} else {
			if ($aSCOPE['CUST_CODE']=='') {
				if ($aSCOPE['LOC_CODE']=='') {
					if ($aSCOPE['JOB_CODE']=='') {
						$cDASH_CODE .="'".$aSCOPE['DASH_CODE']."', ";
					} else {
						if ($aSCOPE['JOB_CODE']==$cJOB) {
							$cDASH_CODE .="'".$aSCOPE['DASH_CODE']."', ";
						}
					}
				} else {
					if ($aSCOPE['LOC_CODE']==$cLOCATION && ($aSCOPE['JOB_CODE']==$cJOB || $aSCOPE['JOB_CODE']=='')) {
						$cDASH_CODE .="'".$aSCOPE['DASH_CODE']."', ";
					}
				}
			} else {
				if ($aSCOPE['CUST_CODE']==$cCUSTOMER) {
				 	if ($aSCOPE['LOC_CODE']=='') {
						if ($aSCOPE['JOB_CODE']=='') {
							$cDASH_CODE .="'".$aSCOPE['DASH_CODE']."', ";
						} else {
							if ($aSCOPE['JOB_CODE']==$cJOB) {
								$cDASH_CODE .="'".$aSCOPE['DASH_CODE']."', ";
							}
						}
					} else {
						if ($aSCOPE['JOB_CODE']==$cJOB)
							$cDASH_CODE .="'".$aSCOPE['DASH_CODE']."', ";
					}
				} else {
				}
			}
		}
	}
	$cDASH_CODE .="'XYZ')";

	$aD=[];
    $qDASH 	= OpenTable('FeDashboard', "APP_CODE='$cAPP_CODE' and DASH_CODE in ".$cDASH_CODE, '', 'DASH_ORDER desc');
	while($aDASH	= SYS_FETCH($qDASH)) {
        $aNEW = array('DASH_CODE' => $aDASH['DASH_CODE'], 'DASH_LABEL' => $aDASH['DASH_LABEL'], 'DASH_WIDTH' => $aDASH['DASH_WIDTH'],
            'DASH_LINK' => $aDASH['DASH_LINK'], 'DASH_COLOR' => $aDASH['DASH_COLOR'], 'DASH_FA' => $aDASH['DASH_FA'],
            'DASH_PARA' => $aDASH['DASH_PARA'], 'DASH_LABEL' => $aDASH['DASH_LABEL'], 'DASH_TOOLTIP' => $aDASH['DASH_TOOLTIP']);
        array_splice($aD,0,0,array('random_string'));
        $aD[0] = $aNEW;
	}

	$_SESSION['cAPP_CODE'] = $cAPP_CODE;   
	require_once("scr_header.php");	
	require_once("fe_topbar.php");
?>

	<!-- Custom CSS from SB -->
	<link href="sb/css/bootstrap.min.css" rel="stylesheet">
	<link href="sb/css/sb-admin-2.css" rel="stylesheet">
	<link href="sb/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<body >
		<div class="page-container row-fluid">
			<section class="wrapper main-wrapper" style=' margin-top: 30px'>
				<div class="clearfix"></div>
				<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 uprofile-name">
						<h3 style="background-color:#8EC6CE";> <?php echo $cPRS_NAME;?></h3>
						<h5 style="background-color:#D4CDCA";> <?php echo '( '.$cPRS_JOB.' )';?></h5>
						<h6 style="background-color:#D4CDCA";> <?php echo '( '.DECODE($a_PERSON['CUST_NAME']).' - '.DECODE($a_PERSON['LOKS_NAME']).' )';?></h6>
					</div>
					<?php
						$nSIZE_ARRAY = count($aD);
						$I=0;
						while($I<$nSIZE_ARRAY) {
							echo '<div class="col-lg-'.$aD[$I]['DASH_WIDTH'].' col-md-6 col-xs-'.$aD[$I]['DASH_WIDTH'].'">';
								echo '<a href="'.$aD[$I]['DASH_LINK'].
									'?_dev='.$cDEVICE.
									'&_prs='.$cPRS_CODE.
									'&_app='.$cAPP_CODE.
									'&_para='.$aD[$I]['DASH_CODE'].
									'&_job='.$cPRS_JOB.
									'&_loc='.$cLOCATION.
									'&_name='.$cPRS_NAME.
								'">';
								echo '<div class="panel '.$aD[$I]['DASH_COLOR'].'">';
									echo '<div class="panel-heading">';
										echo '<div class="row">';
											echo '<div class="col-xs-3">';
												echo '<i class="fa '.$aD[$I]['DASH_FA'].'"></i>';
												echo '</div>';
												$cVAR = $aD[$I]['DASH_PARA'];
												echo '<div class="col-xs-9 text-right">';
												if ($cVAR!='') 	echo '<div class="huge">'.number_format(${$cVAR}).'</div>';
												echo '<div>' . $aD[$I]['DASH_LABEL'] .'</div>';
											echo '</div>';
										echo '</div>';
									echo '</div>
									</div>
								</a>
							</div>';
							$I++;
						}
					?>
				</div>
			</section>
		</div>
		<?php	require_once("js_framework.php");	?>
		<script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>
		<script src="assets/js/scripts.js" type="text/javascript"></script> 
		<script src="sys_js.js" type="text/javascript"></script> 
	</body>
</html>
