<?php
$_SESSION['data_FILTER_CODE'] = '';
$_SESSION['gUSERCODE'] 	= 'guest';
$_SESSION['gSYS_PARA'] 	= 'JNS_PRSHN';
$_SESSION['sLANG']		= '1';
$_SESSION['gSYS_NAME']     = 'Rainbow Sys';

$_SESSION['cHOST_DB1'] = 'riza_sys_data';
$_SESSION['cHOST_DB2'] = 'riza_db';
include "sys_connect.php";
    require_once 'sys_function.php';
    MSG_INFO('Terimakasih. Data Anda sudah di rekam di sistem kami. \n\rHarap tunggu Admin untuk memverifikasi pendaftaran Anda');
	// header('location:https://google.com'); 1115125402 7895336611
    exit;
?>
