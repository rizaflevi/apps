
<?php

$_SESSION['cHOST_DB2'] = 'riza_db';
$_SESSION['data_FILTER_CODE'] = 'YAZA';
$_SESSION['gUSERCODE'] 	= 'guest';
include "sys_connect2.php";
include "sys_function.php";

    $cDEVICE  = $_GET['_d'];
	$REG_NAME = $_POST['nam'];
	$REG_LOCS = $_POST['loc'];
	$REG_JOBS = $_POST['job'];
	$REG_PASS = $_POST['pass'];
	$qAPP_CODE = OpenTable('TbmPassword', "PASSWORD='$REG_PASS'");
    if (SYS_ROWS($qAPP_CODE)==0) {
        MSG_INFO('Password tidak terdaftar');
		return;
    }
    $aAPP_CODE = SYS_FETCH($qAPP_CODE);
    $cAPP_CODE = $aAPP_CODE['APP_CODE'];
	$_SESSION['data_FILTER_CODE'] = $cAPP_CODE;

	if ($REG_NAME=='') {
		MSG_INFO('Anda belum mengisikan Nama Anda');
		return;
	}
	if ($REG_LOCS=='') {
		MSG_INFO('Anda belum mengisikan Lokasi kerja');
		return;
	}
	if ($REG_JOBS=='') {
		MSG_INFO('Anda belum mengisikan Jabatan');
		return;
	}

    if (preg_match('#[\^£$%&*()}{@~?><>,|=_+¬-]/#', $REG_NAME)) {
		MSG_INFO('Nama tidak boleh ada special character!');
		return;
	}
    $q_DEVICE=OpenTable('FeDevice', "DEVICE_ID='$cDEVICE' and REC_ID not in ( select DEL_ID from logs_delete)");
    if ($a_DEV = SYS_FETCH($q_DEVICE)) {
	} else {
		RecCreate('FeDevice', ['REC_ID', 'DEVICE_ID', 'PEOPLE_NAME', 'PEOPLE_LOCS', 'PEOPLE_JOB', 'APP_CODE'],
			[NowMSecs(), $cDEVICE, $REG_NAME, $REG_LOCS, $REG_JOBS, $cAPP_CODE]);
	}
	include "fe_register_done.php";
 ?>

 <script src="sys_js.js" type="text/javascript"></script>
