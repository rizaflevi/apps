<?php
// index-main.php

include "sys_connect.php";
    function encode_first($str)
    {
            $str = str_replace( '<' , '',$str );
            $str = str_replace( '>' , '',$str );
            $str = str_replace( '*' , '',$str );
            $str = str_replace( "'" , '',$str );
            $str = str_replace( '"' , '',$str );
            $str = str_replace( '{' , '',$str );
            $str = str_replace( '}' , '',$str );
            $str = str_replace( '`' , '',$str );
            $str = str_replace( '[' , '',$str );
            $str = str_replace( ']' , '',$str );
            $str = str_replace( '(' , '',$str );
            $str = str_replace( ')' , '',$str );
            $str = str_replace( 'script' , '',$str );
        return $str;
    }


	if( !isset($_POST['_usr']) )	{
		echo "<tr> <td colspan='2'>**Harap Anda login dulu**</td> </tr>";
		header('location:bm.php');
		return;
	}
//	die ('user : '.$_POST['_usr']);

	$USERNAME = encode_first($_POST['_usr']);
	$USERPASS = $_POST['_pwd'];
	$cUSER_PROV = '';
	$cUSER_KAB  = '';
	$cUSER_KEC  = '';


/* ======================= connect to sys data =================================== */
	mysql_select_db($database1, $DB1) or die("Can not open DB1");
	$q_TB_USER=mysql_query("select * from ".$database1.".tb_user where USER_CODE='$USERNAME'");
	$KETEMU=mysql_num_rows($q_TB_USER);
	if ( $KETEMU == 0 ) {
		$ADD_LOG=	mysql_query("insert into ".$database1.".user_log set USER_CODE='$USERNAME', USER_ACT='UnAutorized login'");
		include "bm_fault.php";
		exit();
	}
	date_default_timezone_set('Asia/Jakarta');
	$NOW = date("Y-m-d H:i:s");
	$cQUERY0 ="update ".$database1.".tb_user set USER_LOGIN=1, LAST_LOGIN='$NOW'  where USER_CODE='$USERNAME'";
	$qQUERY0=mysql_query($cQUERY0);

	$aDB_SYS=mysql_fetch_array($q_TB_USER);
	$MODULE_USER = $aDB_SYS['SYS_MODULE'];
	if ( $MODULE_USER == '' ) {
		include "unactivated.php";
		exit();
	}

	session_start();
	$_SESSION['gUSER_AS']		= $aDB_SYS['USER_AS'];
	$_SESSION['gBRANCH']		= $aDB_SYS['BRANCH'];
	$_SESSION['gDASH_BOARD']	= $aDB_SYS['DASH_BOARD'];
	$_SESSION['DB1']			= $DB1;
	
	$c_MODUL="select * from ".$database1.".sys_module where module_code='$MODULE_USER'";
//	die ($MODULE_USER);
	$q_MODUL=mysql_query($c_MODUL);
	$ADA_MD=mysql_num_rows($q_MODUL);
	if ( $ADA_MD == 0 ) {
		session_destroy(); // clean up session ID
		include "unactivated.php";
		exit();
	}
	$aDB_MODULE=mysql_fetch_array($q_MODUL);
	$_SESSION['cHOST_DB2']     = $aDB_MODULE['db_host'];
	$_SESSION['cDATA_DB2']     = $aDB_MODULE['db_name'];
	$_SESSION['cUSER_DB2']     = $aDB_MODULE['db_user'];
	$_SESSION['cPASS_DB2']     = $aDB_MODULE['db_pass'];
	$_SESSION['data_FILTER_CODE']     = $aDB_MODULE['app_code'];
	$_SESSION['cDASHBOARD_LINK']     = $aDB_MODULE['dashboard_link'];

	$cHOME = $_SESSION['cDASHBOARD_LINK'];
	$_SESSION['gMODULE_USER']     = $MODULE_USER;

	$_SESSION['gSYS_PARA']     = $aDB_MODULE['u_parameters'];
//	$_SESSION['sBY_PERIOD']     = substr($aDB_MODULE['u_parameters'],0,1)=='1';
//	mysql_close($DB1);
	
/* -------------------------------------------------------------------------------- */


/* ======================= back to main database ================================= */

include "sysfunction.php";

	$qQUERY="SELECT * FROM ".$database1.".tb_user where SYS_MODULE='$MODULE_USER' and USER_CODE='$USERNAME' and PASSWORD='".md5($USERPASS)."'";
	$cQUERY=SYS_QUERY($qQUERY);
	$aUSER=SYS_FETCH($cQUERY);
	$KETEMU=SYS_ROWS($cQUERY);
	$MINE=$aUSER['USER_NAME'];

	if ( $KETEMU == 0 ) {
		$ADD_LOG=	SYS_QUERY("insert into ".$database1.".user_log set USER_CODE='$USERNAME', APP_CODE='$_SESSION[data_FILTER_CODE]', USER_ACT='UnAutorized login'");
		session_destroy(); // clean up session ID
		SYS_DB_CLOSE($DB1);
		include "bm_fault.php";
		exit();
	}
	
	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$ADD_LOG=	SYS_QUERY("insert into ".$database1.".user_log set USER_CODE='$USERNAME', USER_ACT='Login sucsess'");

	$q_CATR="SELECT * FROM bm_tb_catter1 where APP_CODE='$cFILTER_CODE' and USER_CODE='$USERNAME'";
	$c_CATR=SYS_QUERY($q_CATR);
	$aCATR=SYS_FETCH($c_CATR);

	$cQRY_SYS="select * from rainbow where KEY_FIELD='LANG' and APP_CODE='$cFILTER_CODE'";
	$qQRY_SYS=SYS_QUERY($cQRY_SYS);
	$cREC_SYS=SYS_FETCH($qQRY_SYS);

	$_SESSION['gSYS_NAME']     = 'Rainbow Sys';
	$_SESSION['gUSERCODE']     = $aUSER['USER_CODE'];
	$_SESSION['gCAT_TER']     = $aCATR['KODE_CATTER'];
	$_SESSION['gUSERNAME']     = $MINE;
	$_SESSION['gSCR_HEADER']   = 'DASHBOARD';
	$_SESSION['sLANG']     	= substr($cREC_SYS['KEY_CONTEN'],0,1);
	$_SESSION['sCURRENT_PERIOD']	= date('Y-m');

 	header('Location: bm_dashboard.php');
?>
