<?php
// index-main.php

require_once "sys_connect.php";
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
		echo "<tr> <td colspan='2'>**Login first, please**</td> </tr>";
		header('location:index.php');
		return;
	}

	$USERNAME = encode_first($_POST['_usr']);
	$USERPASS = $_POST['_pwd'];


/* ======================= connect to system data =================================== */
//	mysqli_select_db($DB1, $database1) or die("Can not open DB1");
	$DB1 = $_SESSION['DB1'];
	$cQUERY = "select * from ".$database1.".tb_user where USER_CODE='$USERNAME' AND DELETOR=''";
	$q_TB_USER=$DB1 -> query($cQUERY);
	if(mysqli_num_rows($q_TB_USER)==0) {
		require_once "unautorized.php";
		exit();
	}
	$aDB_SYS = $q_TB_USER -> fetch_array(MYSQLI_ASSOC);
	if ( $aDB_SYS['SYS_MODULE'] == '' ) {
		$ADD_LOG=	mysqli_query($DB1, "insert into ".$database1.".user_log set USER_CODE='$USERNAME', USER_ACT='UnAutorized login'");
		require_once "unautorized.php";
		exit();
	}

	date_default_timezone_set('Asia/Jakarta');
	$NOW = date("Y-m-d H:i:s");
	$cQUERY0 ="update ".$database1.".tb_user set USER_LOGIN=1, LAST_LOGIN='$NOW'  where USER_CODE='$USERNAME'";
	$qQUERY0=mysqli_query($DB1, $cQUERY0);

//	$aDB_SYS=mysqli_fetch_array($q_TB_USER);
	$MODULE_USER = $aDB_SYS['SYS_MODULE'];
/*	if ( $MODULE_USER == '' ) {
		require_once "unactivated.php";
		exit();
	}
*/
	session_start();
	$_SESSION['gUSER_AS']		= $aDB_SYS['USER_AS'];
/*	$_SESSION['gBRANCH']		= $aDB_SYS['BRCH_CODE'];	*/
	$_SESSION['gDASH_BOARD']	= $aDB_SYS['DASH_BOARD'];
//	$_SESSION['DB2']			= $DB2;
	$_SESSION['data_FILTER_CODE']     = $aDB_SYS['APP_CODE'];
	$_SESSION['gUSERCODE']			= $USERNAME;
	
	$c_MODUL="select * from ".$database1.".sys_module where module_code='$MODULE_USER'";
	$q_MODUL=mysqli_query($DB1, $c_MODUL);
	$ADA_MD=mysqli_num_rows($q_MODUL);
	if ( $ADA_MD == 0 ) {
		session_destroy(); // clean up session ID
		require_once "unactivated.php";
		exit();
	}
	$aDB_MODULE=mysqli_fetch_array($q_MODUL);
	$_SESSION['cHOST_DB2']     = $aDB_MODULE['db_host'];
	$_SESSION['cDATA_DB2']     = $aDB_MODULE['db_name'];
	$_SESSION['cUSER_DB2']     = $aDB_MODULE['db_user'];
	$_SESSION['cPASS_DB2']     = $aDB_MODULE['db_pass'];
	$_SESSION['cDASHBOARD_LINK']     = $aDB_MODULE['dashboard_link'];

	$cHOME = $_SESSION['cDASHBOARD_LINK'];
	$_SESSION['gMODULE_USER']     = $MODULE_USER;

	$_SESSION['gSYS_PARA']     = $aDB_MODULE['u_parameters'];
	$_SESSION['sBY_PERIOD']     = substr($aDB_MODULE['u_parameters'],0,1)=='1';

/* -------------------------------------------------------------------------------- */


/* ======================= back to main database ================================= */

require_once "sysfunction.php";

	$qUSER=OpenTable('TbUser', "SYS_MODULE='$MODULE_USER' and USER_CODE='$USERNAME' and PASSWORD='".md5($USERPASS)."'");
	$aUSER=SYS_FETCH($qUSER);
	$KETEMU=SYS_ROWS($qUSER);
	if ($KETEMU)	$MINE=$aUSER['USER_NAME'];

	if ( $KETEMU == 0 ) {
//		$qUSER=OpenTable('TbUser', "USER_CODE='$USERNAME' and PASSWORD=''");
		$cUSER="SELECT * FROM tb_user where USER_CODE='$USERNAME' and PASSWORD=''";
		$qUSER=$DB1 -> query($cUSER);
		$aUSER=SYS_FETCH($qUSER);
		$nUSER = mysqli_num_rows($qUSER);
		if ($nUSER>0) {
		$cMODUL = $aUSER['SYS_MODULE'];
		if ($cMODUL>'') {
//			$_SESSION['DB1']			= $DB1;
			header('Location: password2x.php?_u='.md5($USERNAME));
			exit();
		}
		}
		$ADD_LOG=	$DB1 -> query("insert into ".$database1.".user_log set USER_CODE='$USERNAME', USER_ACT='UnAutorized login'");
		session_destroy(); // clean up session ID
		SYS_DB_CLOSE($DB1);
		require_once "unautorized.php";
		exit();
	}
	
	$cAPP_CODE = $_SESSION['data_FILTER_CODE'];
	$ADD_LOG=	$DB1->query("insert into ".$database1.".user_log set USER_CODE='$USERNAME', USER_ACT='Login sucsess'");

	// $qQRY_SYS=$DB2->query("select * from rainbow where KEY_FIELD='LANG' and APP_CODE='$cAPP_CODE'");
	// $cREC_SYS=SYS_FETCH($qQRY_SYS);

	$_SESSION['gSYS_NAME']     = 'Rainbow Sys';
	$_SESSION['gUSERCODE']     = $aUSER['USER_CODE'];
	$_SESSION['gUSERNAME']     = $MINE;
	$_SESSION['gSCR_HEADER']   = 'DASHBOARD';
	$_SESSION['sLANG']     	= substr(S_PARA('LANG', '1'),0,1);
	$_SESSION['sCURRENT_PERIOD']	= date('Y-m');

	if ($cHOME!='') {
		$_SESSION['cDASHBOARD_LINK']     = $cHOME;
		  header('Location: '.$cHOME);			// custom dashboard link
		  return;
	}
 	header('Location: scr_main.php');
?>
