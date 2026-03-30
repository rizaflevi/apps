<?php
/**
 * bm_logout.php
 *
*/
	include "sysfunction.php";
	$USERNAME = '';
	if (isset($_GET['id']))	{
		$USERNAME = $_GET['id'];
	} else {
		session_destroy(); // clean up session ID
		session_unset();
		header("Location: bm.php");
	}
  $_SESSION = array(); // deregister all current session variables

  /**
   * Cookie destroy
   */
  $params = session_get_cookie_params();
  setcookie(session_name(), 0, 1, $params['path']);
  unset($params);
  /*if (isset($_COOKIE[session_name()])) // PHP Manual (session_destroy)
  {
    setcookie(session_name(), '', time() - 42000, '/');
  }*/

	if ($USERNAME!='') {
		$cHEADER 	= 'logout';
		$ADD_LOG	= APP_LOG_ADD();
		$qQUERY0=SYS_QUERY("update ".$database1.".tb_user set USER_LOGIN=0, LAST_LOGIN='$NOW' where USER_CODE='$USERNAME'");
	}
	
	session_destroy(); // clean up session ID

	header("Location: bm.php");
?>
